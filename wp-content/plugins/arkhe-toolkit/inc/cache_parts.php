<?php
namespace Arkhe_Toolkit\Cache;

/**
 * キャッシュ生成フック
 * Toolkitの設定データを受け取れるタイミングで
 */
add_action( 'wp_loaded', function() {

	// ヘッダーキャッシュ
	if ( \Arkhe_Toolkit::get_data( 'cache', 'cache_header' ) ) {
		add_filter( 'arkhe_part_cache__header', __NAMESPACE__ . '\get_cached_part', 10, 4 );
	}

	// フッターキャッシュ
	if ( \Arkhe_Toolkit::get_data( 'cache', 'cache_footer' ) ) {
		add_filter( 'arkhe_part_cache__footer', __NAMESPACE__ . '\get_cached_part', 10, 4 );
	}

	// サイドバー
	if ( \Arkhe_Toolkit::get_data( 'cache', 'cache_sidebar' ) ) {
		add_filter( 'arkhe_part_cache__sidebar', __NAMESPACE__ . '\get_cached_part', 10, 4 );
	}

}, 20 );


// 共通の処理
function get_cached_part( $return, $slug, $include_path, $args ) {

	$now_page_type = \Arkhe_Toolkit::get_page_type();
	$cache_key     = \Arkhe_Toolkit::CACHE_PREFIX . "_{$slug}_{$now_page_type}";

	// キャッシュの取得or生成
	$cache_data = \Arkhe_Toolkit::cache_the_part( $slug, $include_path, $args, $cache_key );

	return $cache_data;
}
