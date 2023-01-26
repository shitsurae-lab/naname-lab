<?php
namespace Arkhe_Toolkit;

/**
 * コンテンツに対する処理
 * memo: 優先度12 → ショートコード展開より後に実行するため
 *       widget_text → カスタムHTMLウィジェット
 *       widget_text_content → テキストウィジェット
 *       widget_block_content → ブロックで出力されるウィジェット
 */
add_action( 'wp', function() {

	// エディター側で走る the_content フックでは処理されないように。
	if ( is_admin() ) return;

	global $wp_embed;

	// 再利用ブロックでも埋め込みを有効化する
	add_filter( 'the_content', [ $wp_embed, 'autoembed' ], 12 );
	add_filter( 'widget_text', [ $wp_embed, 'autoembed' ], 12 );
	add_filter( 'widget_text_content', [ $wp_embed, 'autoembed' ], 12 );
	add_filter( 'widget_block_content', [ $wp_embed, 'autoembed' ], 12 );

	// カスタムHTMLウィジェットでもショートコードを展開
	add_filter( 'widget_text', 'do_shortcode' );

	// 空のpタグ削除
	if ( \Arkhe_Toolkit::get_data( 'extension', 'remove_emp_p' ) ) {
		add_filter( 'the_content', '\Arkhe_Toolkit\remove_empty_p', 12 );
		add_filter( 'widget_block_content', '\Arkhe_Toolkit\remove_empty_p', 12 );
	}

	// add_filter( 'the_content', '\Arkhe_Toolkit\add_toc' ], 12 );

	// lazysizes.js の適用
	if ( \Arkhe_Toolkit::is_use_lazysizes() ) {

		// サーバーサイドレンダー, wp-json/wp/v2 では何もしない
		if ( ! defined( 'REST_REQUEST' ) ) {

			// loading="lazy" の自動付与を停止
			add_filter( 'wp_lazy_loading_enabled', function( $default, $tag_name, $context ) {
				if ( 'the_content' === $context || str_contains( $context, 'widget_' ) ) {
					return false;
				}
				return $default;
			}, 10, 3 );

			// lazysizesを使用
			add_filter( 'the_content', '\Arkhe_Toolkit\add_lazyload', 12 );
			add_filter( 'widget_text', '\Arkhe_Toolkit\add_lazyload', 12 );
			add_filter( 'widget_text_content', '\Arkhe_Toolkit\add_lazyload', 12 );
			add_filter( 'widget_block_content', '\Arkhe_Toolkit\add_lazyload', 12 );
		}
	}
});


/**
 * 空のpタグを除去
 */
function remove_empty_p( $content ) {
	$content = str_replace( '<p></p>', '', $content );
	return $content;
}


/**
 * lazyloadを追加
 */
function add_lazyload( $content ) {

	// iframe
	$content = preg_replace_callback( '/<iframe([^>]*)>/', function( $matches ) {
		$props = rtrim( $matches[1], '/' );

		// すでにlazyload設定が済んでいれば何もせず返す
		if ( str_contains( $props, ' data-src=' ) ) {
			return $matches[0];
		}

		// src を data-srcへ
		$props = str_replace( ' src=', ' data-src=', $props );

		// lazyloadクラスの追加
		$props = \Arkhe_Toolkit\add_lazyload_class( $props );

		return '<iframe' . $props . '>';
	}, $content );

	// img, video
	$content = preg_replace_callback(
		'/<(img|video)([^>]*)>/',
		function( $matches ) {

			$tag   = $matches[1];
			$props = rtrim( $matches[2], '/' );

			// すでにlazyload設定が済んでいれば何もせず返す
			if ( str_contains( $props, ' data-src=' ) || str_contains( $props, ' data-srcset=' ) ) {
				return $matches[0];
			}

			// srcを取得
			preg_match( '/\ssrc="([^"]*)"/', $props, $src_matches );
			$src = ( $src_matches ) ? $src_matches[1] : '';

			// srcなければ何もせず返す
			if ( ! $src ) return $matches[0];

			// lazysizes動かない環境用
			$noscript = '<noscript><' . $tag . $props . '></noscript>';

			// インライン画像かどうか
			$is_inline = str_contains( $props, 'style=' ) && str_contains( $props, 'width:' );

			// インライン画像の場合はloading="lazy"だけにする
			// styleのサイズ指定がaspectratio機能で上書きされて崩れてしまう & aspectratioなしでlazyloadするとCLS悪化のリスクあり )
			if ( $is_inline ) {
				if ( ! wp_lazy_loading_enabled( 'img', 'the_content' ) ) {
					return '<' . $tag . $props . ' loading="lazy">';
				} else {
					return '<' . $tag . $props . '>';
				}
			}

			// src を data-srcへ
			$placeholder = \Arkhe::$placeholder ?? 'data:image/gif;base64,R0lGODlhBgACAPAAAP///wAAACH5BAEAAAAALAAAAAAGAAIAAAIDhI9WADs=';
			$props       = str_replace( ' src=', ' src="' . $placeholder . '" data-src=', $props );

			// srcset を data-srcsetへ
			$props = str_replace( ' srcset=', ' data-srcset=', $props );

			// width , height指定を取得
			$props = \Arkhe_Toolkit\set_aspectratio( $props, $src );

			// lazyloadクラスの追加
			$props = \Arkhe_Toolkit\add_lazyload_class( $props );

			return '<' . $tag . $props . '>';
		},
		$content
	);

	return $content;
}


/**
 * classに lazyload 追加する処理
 */
function add_lazyload_class( $props = '' ) {
	if ( str_contains( $props, 'class=' ) ) {
		// class属性が何かしらある時
		$props = preg_replace_callback( '/class="([^"]*)"/', function( $class_match ) {
			$class_value = $class_match[1];
			// クラスにまだ 'lazyload' が付与されていなければ
			if ( ! str_contains( $class_value, 'lazyload' ) ) {
				return 'class="' . $class_value . ' lazyload"';
			}
			return $class_match[0];
		}, $props );
	} else {
		// classが1つもない時
		$props .= ' class="lazyload" ';

	}
	return $props;
}


/**
 * width,height から aspectratio を指定
 */
function set_aspectratio( $props, $src = '' ) {

	// width , height指定を取得
	preg_match( '/\swidth=["\']([0-9]*)["\']/', $props, $width_matches );
	preg_match( '/\sheight=["\']([0-9]*)["\']/', $props, $height_matches );
	$width  = ( $width_matches ) ? $width_matches[1] : '';
	$height = ( $height_matches ) ? $height_matches[1] : '';

	if ( $width && $height ) {
		$props .= ' data-aspectratio="' . $width . '/' . $height . '"';
	}

	return $props;
}
