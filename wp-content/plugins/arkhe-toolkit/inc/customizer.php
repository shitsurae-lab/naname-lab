<?php
namespace Arkhe_Toolkit;

/**
 * カスタマイザーの設定項目を追加
 */
add_action( 'customize_register', '\Arkhe_Toolkit\add_customizer_setttings', 21 ); // テーマよりあとにフック
function add_customizer_setttings( $wp_customize ) {

	include_once ARKHE_TOOLKIT_PATH . 'inc/customizer/ex_header.php';
	include_once ARKHE_TOOLKIT_PATH . 'inc/customizer/share_btns.php';

}
