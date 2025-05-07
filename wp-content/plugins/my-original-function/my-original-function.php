<?php

/**
 *Plugin Name: My Original Function Plugin
 *Description: functions.phpのカスタム記述
 *Version: 1.0
 *Author: Toshiyuki Kurashima
 */



/**
 *アクションフック
 */
//フッターのカスタマイズ(Simple !)
include_once(plugin_dir_path(__FILE__) . 'function/custom_footer.php');

//固定ページのコンテンツ前後(...「固定ページのコンテンツの前(後)です」)
// include_once(plugin_dir_path(__FILE__) . 'function/before_after_page.content.php');

//画像サイズの調整
include_once(plugin_dir_path(__FILE__) . 'function/custom_image.php');

//カスタム投稿タイプ-投稿詳細ページサイドバーカスタマイズ(追従)
include_once(plugin_dir_path(__FILE__) . 'function/custom_post_sidebar.php');

//アーカイブページサイドバーカスタマイズ(追従)
include_once(plugin_dir_path(__FILE__) . 'function/custom_archive_sidebar_2.php');

include_once(plugin_dir_path(__FILE__) . 'function/custom_conditional_branch.php');

//画像付きのページネーション
include_once(plugin_dir_path(__FILE__) . 'function/custom_pagination.php');

//カスタム投稿タイプの登録
include_once(plugin_dir_path(__FILE__) . 'function/register_custom_post_type.php');

//ショートコードの登録
include_once(plugin_dir_path(__FILE__) . 'function/register_shortcode.php');

//ローディングアニメーション + オープニングアニメーション
include_once(plugin_dir_path(__FILE__) . 'function/add_opening_interaction.php');

//フロントページのみにスライダーを実装
include_once(plugin_dir_path(__FILE__) . 'function/insert_front_slider.php');

//カスタム投稿タイプ等にメインビジュアルを挿入
include_once(plugin_dir_path(__FILE__) . 'function/insert_main_visual.php');


//details・summaryでアコーディオン
include_once(plugin_dir_path(__FILE__) . 'function/insert_accordion.php');

//固定ページの<main>配下にコンテンツを記述
// include_once(plugin_dir_url(__FILE__) . 'function/insert_page.main.php');

//contact form 7: お問い合わせ完了後に送信完了画面へ遷移
include_once(plugin_dir_path(__FILE__) . 'function/insert_contact_form_7.php');

/**
 *フィルターフック
 */

//記述したFont Awesome Scriptに crossorigin="anonymous"を付加する
include_once(plugin_dir_path(__FILE__) . 'function/custom_script_loader_tag.php');

include_once(plugin_dir_path(__FILE__) . 'function/overwrite_arkhe_term.php');

include_once(plugin_dir_path(__FILE__) . 'function/overwrite_main_query.php');

include_once(plugin_dir_path(__FILE__) . 'function/overwrite_normal.php');

//パスワードで保護されたサイトの「保護中」の文字を消す
include_once(plugin_dir_path(__FILE__) . 'function/removed_protected.php');

include_once(plugin_dir_path(__FILE__) . 'function/add_page_class.php');

include_once(plugin_dir_path(__FILE__) . 'function/filter_nav_menu.php');

//カスタム投稿タイプ等にメインビジュアルを挿入 フィルターフック
// include_once(plugin_dir_path(__FILE__) . 'function/filter_main_visual.php');

//固定ページ(home.phpを含む)のヘッド部分書き換え(特に見出し・キャプション箇所)
// include_once(plugin_dir_path(__FILE__) . 'function/over_write_top.php');
