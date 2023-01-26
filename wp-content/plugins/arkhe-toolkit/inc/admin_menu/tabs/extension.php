<?php
/**
 * 「拡張機能」タブの設定を登録
 */
namespace Arkhe_Toolkit\Menu;

defined( 'ABSPATH' ) || exit;

// PAGE_NAME
$db_name   = 'extension';
$page_name = \Arkhe_Toolkit::MENU_PAGE_PREFIX . $db_name;


/**
 * ウィジェットエリアの追加
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Extension of widget area', 'arkhe-toolkit' ),
	'key'       => 'widget_area',
	'page_name' => $page_name,
	'db'        => $db_name,
] );


/**
 * コンテンツへの追加処理
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Additional processing to the content', 'arkhe-toolkit' ),
	'key'       => 'content',
	'page_name' => $page_name,
	'db'        => $db_name,
] );


/**
 * 構造化データ
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Structured Data', 'arkhe-toolkit' ),
	'key'       => 'json_ld',
	'page_name' => $page_name,
	'db'        => $db_name,
] );


/**
 * ユーザー情報の追加
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Extension of user profile information', 'arkhe-toolkit' ),
	'key'       => 'user_meta',
	'page_name' => $page_name,
	'db'        => $db_name,
] );
