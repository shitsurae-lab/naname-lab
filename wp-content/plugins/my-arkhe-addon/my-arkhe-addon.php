<?php
/**
 * Plugin name: My Arkhe Addon
 * Description: Arkheをカスタマイズするための雛形プラグインです。
 * Version: 1.0.0
 * License: GPL2 or later
 * 
 * このプラグインはあくまでただの雛形です。自由に編集してお使いください。
 */
defined( 'ABSPATH' ) || exit;


/**
 * Arkhe以外のテーマでは無効
 */
if ( 'arkhe' !== get_template() ) {
	return;
}


/**
 * 定数定義
 */
define( 'MY_ARKHE_URL', plugins_url( '/', __FILE__ ) ); // 末尾に「/」が付きます。
define( 'MY_ARKHE_PATH', plugin_dir_path( __FILE__ ) ); // 末尾に「/」が付きます。


/**
 * ファイルの読み込み（フロント用）
 *     ※ jquery を使用する時は、依存指定するか wp_enqueue_script('jquery') を記述してください。
 */
add_action( 'wp_enqueue_scripts', function() {

	// my_style.css 読み込み
	$time_stamp = date( 'Ymdgis', filemtime( MY_ARKHE_PATH . 'assets/css/my_style.css' ) );
	wp_enqueue_style( 'my-arkhe-style', MY_ARKHE_URL . 'assets/css/my_style.css', [], $time_stamp );

	// my_script.js 読み込み
	$time_stamp = date( 'Ymdgis', filemtime( MY_ARKHE_PATH . 'assets/js/my_script.js' ) );
	wp_enqueue_script( 'my-arkhe-script', MY_ARKHE_URL . 'assets/js/my_script.js', [], $time_stamp, true );

}, 20 );


/**
 * ファイルの読み込み（ブロックエディター用）
 */
add_action( 'enqueue_block_editor_assets', function() {

	// my_editor_style.css 読み込み
	$time_stamp = date( 'Ymdgis', filemtime( MY_ARKHE_PATH . 'assets/css/my_editor_style.css' ) );
	wp_enqueue_style( 'my-arkhe-editor-style', MY_ARKHE_URL . 'assets/css/my_editor_style.css', [], $time_stamp );

}, 20 );

