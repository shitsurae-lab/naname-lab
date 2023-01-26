<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * プラグイン設定画面用のファイルを読み込む
 */
add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {

	$is_ark_css_page = false !== strpos( $hook_suffix, \Arkhe_CSS_Editor::MENU_SLUG );

	// 設定ページにだけ読み込むファイル
	if ( $is_ark_css_page ) {
		// 依存スクリプト・バージョンが記述されたファイルを読み込み
		$asset_file = include ARK_CSS_EDIT_PATH . 'build/index.asset.php';

		// CSSファイルの読み込み
		wp_enqueue_style(
			'arkhe-css-editor',
			ARK_CSS_EDIT_URL . 'build/index.css',
			[ 'wp-components' ],
			\Arkhe_CSS_Editor::$file_ver
		);

		// JavaScriptファイルの読み込み
		wp_enqueue_script(
			'arkhe-css-editor',
			ARK_CSS_EDIT_URL . 'build/index.js',
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);

		// 翻訳ファイルを登録
		wp_set_script_translations(
			'arkhe-css-editor',
			'arkhe-css-editor',
			ARK_CSS_EDIT_PATH . 'languages'
		);

		// 管理画面側に渡すグローバル変数
		wp_localize_script(
			'arkhe-css-editor',
			'arkheCssEditorVars',
			[
				'fontFamilies'  => \Arkhe_CSS_Editor::FONT_FAMILIES,
				'styleSheetUrl' => ARK_CSS_EDIT_URL . 'assets/css/fonts.css',
			]
		);
	}
} );
