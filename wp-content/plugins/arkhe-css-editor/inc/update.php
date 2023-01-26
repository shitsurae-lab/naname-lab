<?php
/**
 * アップデートチェック
 */
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', function() {

	if ( ! class_exists( '\Puc_v4_Factory' ) ) {
		require_once ARK_CSS_EDIT_PATH . 'inc/update/plugin-update-checker.php';
	}
	if ( class_exists( '\Puc_v4_Factory' ) ) {
		\Puc_v4_Factory::buildUpdateChecker(
			'https://loos-cdn.com/arkhe/update/arkhe-css-editor-c522dw.json',
			ARK_CSS_EDIT_PATH . 'arkhe-css-editor.php',
			'arkhe-css-editor'
		);
	}
});
