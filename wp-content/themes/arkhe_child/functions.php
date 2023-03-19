<?php
/**
 * Arkhe用子テーマ用 function.php
 */
defined( 'ABSPATH' ) || exit;


/**
 * 子テーマのパス, URI
 */
define( 'ARKHE_CHILD_PATH', get_stylesheet_directory() );
define( 'ARKHE_CHILD_URI', get_stylesheet_directory_uri() );


/**
 * style.css 読み込み
 */
add_action( 'wp_enqueue_scripts', function() {
	// 開発時にブラウザキャッシュされないように、最終変更日時をクエリとして付与
	$time_stamp = date( 'Ymdgis', filemtime( ARKHE_CHILD_PATH . '/style.css' ) );
	wp_enqueue_style( 'arkhe-child-style', ARKHE_CHILD_URI . '/style.css', [], $time_stamp );
} );
