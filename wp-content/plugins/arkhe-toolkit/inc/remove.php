<?php
namespace Arkhe_Toolkit;

/**
 * 設定に合わせて不要な機能・出力を削除
 */
add_action( 'init', '\Arkhe_Toolkit\remove_functions', 20 );
function remove_functions() {

	$data = \Arkhe_Toolkit::get_data( 'remove' );

	// WordPressのバージョン情報
	if ( $data['remove_wpver'] ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	// srcset
	if ( $data['remove_srcset'] ) {
		add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
	}

	// 絵文字
	if ( $data['remove_emoji'] ) {
		add_filter( 'emoji_svg_url', '__return_false' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	}

	// rel="prev"とrel="next"のlinkタグを自動で書き出さない
	if ( $data['remove_rel_link'] ) {
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	}

	// Windows Live Writeの停止
	if ( $data['remove_wlwmanifest'] ) {
		remove_action( 'wp_head', 'wlwmanifest_link' );
	}
	// EditURI(RSD Link)の停止
	if ( $data['remove_rsd_link'] ) {
		remove_action( 'wp_head', 'rsd_link' );
	}

	// 記号の自動変換停止(wptexturize無効化)
	if ( $data['remove_wptexturize'] ) {
		add_filter( 'run_wptexturize', '__return_false' );
	}

	// RSSフィード
	if ( $data['remove_feed_link'] ) {
		remove_action( 'wp_head', 'feed_links', 2 ); // 記事フィードリンク停止
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // カテゴリ・コメントフィードリンク停止
	} else {
		add_theme_support( 'automatic-feed-links' );
	}

	// HTMLチェックで「Bad value」になるやつ https://api.w.org/ を消す
	if ( $data['remove_rest_link'] ) {
		remove_action( 'wp_head', 'rest_output_link_wp_head' );
	}

	// コアのサイトマップ機能
	if ( $data['remove_sitemap'] ) {
		add_filter( 'wp_sitemaps_enabled', '__return_false' );
		// add_filter( 'wp_sitemaps_users_pre_url_list', '__return_false' );
	}

	// セルフピンバックの停止
	if ( $data['remove_self_ping'] ) {
		add_action( 'pre_ping', function( &$post_links ) {
			$home = home_url();
			foreach ( $post_links as $key => $link ) {
				if ( 0 === strpos( $link, $home ) ) {
					unset( $post_links[ $key ] );
				}
			}
		} );
	}
}
