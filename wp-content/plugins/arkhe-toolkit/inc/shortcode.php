<?php
/**
 * ショートコードを登録する
 */
namespace Arkhe_Toolkit\Shortcode;

/**
 * <br> 系
 */
function spbr() {
	return '<br class="u-only-sp">';
}
if ( ! shortcode_exists( 'spbr' ) ) add_shortcode( 'spbr', __NAMESPACE__ . '\spbr' );

function pcbr() {
	return '<br class="u-only-pc">';
}
if ( ! shortcode_exists( 'pcbr' ) ) add_shortcode( 'pcbr', __NAMESPACE__ . '\pcbr' );


/**
 * アイコン出力
 */
function icon( $atts ) {
	if ( empty( $atts ) ) return;

	$iconname = isset( $atts['class'] ) ? $atts['class'] : $atts[0];
	return '<i class="' . $iconname . '"></i>';
}
if ( ! shortcode_exists( 'icon' ) ) add_shortcode( 'icon', __NAMESPACE__ . '\icon' );


/**
 * SVG出力
 */
function svg( $atts ) {
	if ( empty( $atts ) ) return;

	$name  = isset( $atts['name'] ) ? $atts['name'] : $atts[0];
	$class = 'arkt-svgsc';
	if ( isset( $atts['class'] ) ) {
		$class .= ' ' . $atts['class'];
	}

	$svg = '';
	if ( method_exists( 'Arkhe', 'get_svg' ) ) {
		$svg = \Arkhe::get_svg( $name, [ 'class' => $class ] );
	}
	if ( ! $svg ) {
		$svg = \Arkhe_Toolkit::get_svg( $name, [ 'class' => $class ] );
	}

	return $svg;
}
if ( ! shortcode_exists( 'ark_svg' ) ) add_shortcode( 'ark_svg', __NAMESPACE__ . '\svg' );


/**
 * 再利用ブロックの呼び出し
 */
function echo_wp_block( $atts ) {

	if ( is_admin() ) return '';

	$reuse_id = isset( $atts['id'] ) ? (int) $atts['id'] : 0;
	$class    = isset( $atts['class'] ) ? $atts['class'] : '';

	$content = get_post_field( 'post_content', $reuse_id );

	// ブロックでセットしたクラスを受け取れるように
	$wrap_class = 'c-reuseBlock c-postContent';

	if ( $class ) $wrap_class .= ' ' . $class;

	// 無限ループ回避
	if ( false !== strpos( $content, '[reuse_block id="' . $reuse_id ) ) {
		return 'Infinite loop error. Called itself in a Reuse Block.';
	}

	return '<div class="' . esc_attr( $wrap_class ) . '">' .
		do_blocks( do_shortcode( $content ) ) .
	'</div>';
}
if ( ! shortcode_exists( 'reuse_block' ) ) add_shortcode( 'reuse_block', __NAMESPACE__ . '\echo_wp_block' );


/**
 * HTMLタグをそのまま表示
 */
function do_html_sc( $atts, $content = null ) {

	// html_entity_decode のほうが多くの文字をデコードできるが、重要な"と'に関してはどちらもHTMLとして正しくデコードできないのでwp関数を使う。
	// see : https://developer.wordpress.org/reference/functions/wp_specialchars_decode/
	$content = wp_specialchars_decode( $content, ENT_NOQUOTES );

	// 開始タグの中の属性値を取得
	$pattern   = '/<[a-zA-Z]+\s+([^>]*)>/ms';
	$has_props = preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );
	if ( ! $has_props ) return $content;

	foreach ( $matches as $i => $m ) {
		$props = $m[1];

		// クオート系もデコード
		$props = wp_specialchars_decode( $props, ENT_QUOTES );

		// " が &#8221;、 ' が &#8217; になっていて属性値がバグるので変換しておく。
		// see: https://developer.wordpress.org/reference/functions/convert_invalid_entities/
		$props = str_replace( [ '&#8221;', '&#8217;' ], [ '"', "'" ], $props );

		$content = str_replace( $m[1], $props, $content );
	}

	return $content;
}
if ( ! shortcode_exists( 'html' ) ) add_shortcode( 'html', __NAMESPACE__ . '\do_html_sc' );
