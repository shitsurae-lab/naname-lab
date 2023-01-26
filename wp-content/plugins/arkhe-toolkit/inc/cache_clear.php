<?php
namespace Arkhe_Toolkit;

/**
 * カスタマイザーの設定変更後、キャッシュを削除
 */
add_action( 'customize_save_after', function() {
	\Arkhe_Toolkit::clear_cache();
});


/**
 * 投稿の更新時
 */
add_action( 'save_post', function( $post_ID ) {
	\Arkhe_Toolkit::clear_cache();
}, 99);


/**
 * カテゴリー・タグの更新時
 */
add_action( 'edited_terms', function( $post_ID ) {
	\Arkhe_Toolkit::clear_cache();
}, 99);


/**
 * カスタムメニューの更新時
 */
add_action( 'wp_update_nav_menu', function( $menu_id ) {
	\Arkhe_Toolkit::clear_cache();
}, 99);


/**
 * ウィジェット更新時
 */
add_filter( 'widget_update_callback', function( $instance, $new_instance, $old_instance, $this_item ) {

	\Arkhe_Toolkit::clear_cache();
	return $instance;

}, 99, 4);


/**
 * サイト名・キャッチフレーズの設定更新時
 */
// add_action( 'update_option', function( $option_name ) {
// 	if ( 'blogname' === $option_name || 'blogdescription' === $option_name ) {
// 		\Arkhe_Toolkit::clear_cache();
// 	}
// });


/**
 * ウィジェットの登録数が変わっている場合の処理（編集ではなく新規追加時の対応）
 */
add_action( 'widgets_init', function() {
	if ( ! is_admin() ) return;

	// ウィジェット登録状況のキャッシュを取得
	$cache_data = get_transient( 'arkhe_sidebars_widgets' );

	// 最新のウィジェット登録状況を取得
	$new_widget_data = wp_get_sidebars_widgets();

	// ウィジェット登録状況に変化があった場合
	if ( $cache_data !== $new_widget_data ) {

		\Arkhe_Toolkit::clear_cache();

		// 最新のウィジェット登録状況をキャッシュ
		set_transient( 'arkhe_sidebars_widgets', $new_widget_data );
	}
}, 99);
