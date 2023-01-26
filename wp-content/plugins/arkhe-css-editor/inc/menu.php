<?php
namespace Arkhe_CSS_Editor;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 管理画面にメニューを追加
 */
add_action( 'admin_menu', function () {
	add_menu_page(
		'CSS Editor',
		'CSS Editor',
		'manage_options',
		\Arkhe_CSS_Editor::MENU_SLUG,
		__NAMESPACE__ . '\cb_edit_page',
		ARK_CSS_EDIT_URL . 'assets/img/arkhe-menu-icon.png',
		30 // toolkit, blocks (29) より後
	);
}, 20 );


/**
 * 設定ページの内容
 */
function cb_edit_page() {
	echo '<div id="arkhe_css_editor_setting" class="arkcss-wrap"></div>';
}


/**
 * Toolkitツールバー
 */
add_filter( 'admin_bar_menu', function ( $wp_admin_bar ) {

	$arkhe_menu_id = 'arkhe_css_editor';
	$menu_title    = 'Arkhe CSS Editor';

	if ( method_exists( '\Arkhe', 'get_svg' ) ) {
		$menu_title = '<span class="ab-icon -arkhe">' . \Arkhe::get_svg( 'arkhe-logo' ) . '</span><span class="ab-label">CSS Editor</span>';
	}

	// 親メニュー
	$wp_admin_bar->add_menu(
		[
			'id'    => $arkhe_menu_id,
			'title' => $menu_title,
			'href'  => admin_url( 'admin.php?page=' . \Arkhe_CSS_Editor::MENU_SLUG ),
		]
	);

}, 99 );
