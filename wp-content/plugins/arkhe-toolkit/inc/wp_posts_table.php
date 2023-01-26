<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;
/**
 * 管理画面の投稿一覧テーブルの表示をカスタマイズする
 */
add_filter( 'manage_posts_columns', '\Arkhe_Toolkit\add_post_columns' ); // 投稿 & カスタム投稿
add_filter( 'manage_pages_columns', '\Arkhe_Toolkit\add_post_columns' ); // 固定ページ
add_action( 'manage_posts_custom_column', '\Arkhe_Toolkit\output_post_columns', 10, 2 );  // 投稿 & カスタム投稿
add_action( 'manage_pages_custom_column', '\Arkhe_Toolkit\output_post_columns', 10, 2 );  // 固定ページ


/**
 * 投稿一覧テーブルに アイキャッチ画像などの列を追加。
 */
function add_post_columns( $columns ) {
	global $post_type;

	// 投稿タイプごとに分岐
	if ( in_array( $post_type, ['post', 'page' ], true ) ) {

		// phpcs:ignore WordPress.WP.I18n.TextDomainMismatch
		$columns['thumbnail'] = __( 'Featured image', 'arkhe' );
		$columns['post_id']   = 'ID';

	} elseif ( 'wp_block' === $post_type ) {
		$columns['reuse_shortcode'] = __( 'shortcode', 'arkhe-toolkit' );
	}
	return $columns;
}


/**
 * 投稿ID & サムネイル画像を表示する
 */
function output_post_columns( $column_name, $the_id ) {

	if ( 'thumbnail' === $column_name ) {
		$thumb_id = get_post_thumbnail_id( $the_id );
		if ( $thumb_id ) {
			$thumb_img = wp_get_attachment_image_src( $thumb_id, 'medium' );
			echo '<img src="' . esc_url( $thumb_img[0] ) . '">';
		} else {
			echo '—';  // em dash
		}
	} elseif ( 'post_id' === $column_name ) {
		echo esc_html( $the_id );
	} elseif ( 'reuse_shortcode' === $column_name ) {
		$tag = '[reuse_block id="' . $the_id . '"]';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<input class="ark-shortcode" type="text" onClick="this.select();" value=\'' . $tag . '\' readonly />';
	}
}
