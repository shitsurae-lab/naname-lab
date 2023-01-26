<?php
namespace Arkhe_Toolkit\Scripts;

/**
 * ファイルの読み込み
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_front_scripts', 20 );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_scripts', 20 );
add_action( 'wp_footer', __NAMESPACE__ . '\hook_wp_footer_1', 1 );

/**
 * フロントで読み込むファイル
 */
function enqueue_front_scripts() {

	// ウジェットプレビュー時は読み込まない
	if ( defined( 'IFRAME_REQUEST' ) && IFRAME_REQUEST ) return;

	wp_enqueue_style( 'arkhe-toolkit-front', ARKHE_TOOLKIT_URL . 'dist/css/front.css', [], \Arkhe_Toolkit::$file_ver );

	// Prefetch 使用するかどうか
	if ( \Arkhe_Toolkit::get_data( 'speed', 'use_prefetch' ) ) {
		wp_enqueue_script( 'arkhe-toolkit-prefetch', ARKHE_TOOLKIT_URL . 'dist/js/prefetch.js', [], \Arkhe_Toolkit::$file_ver, true );
		wp_localize_script( 'arkhe-toolkit-prefetch', 'arkhePrefetchVars', [
			'ignorePrefetchKeys' => \Arkhe_Toolkit::keywords_to_array( \Arkhe_Toolkit::get_data( 'speed', 'prefetch_prevent_keys' ) ),
		] );
	}

	if ( is_user_logged_in() ) {

		// ツールバー用CSS (memo: Arkhe 1.9~ では不要になったが、後方互換のため残す)
		wp_enqueue_style( 'arkhe-toolkit-toolbar', ARKHE_TOOLKIT_URL . 'dist/css/toolbar.css', [], \Arkhe_Toolkit::$file_ver );

		// ajax関連処理
		wp_enqueue_script( 'arkhe-toolkit-ajax', ARKHE_TOOLKIT_URL . 'dist/js/admin/ajax.js', [ 'jquery' ], \Arkhe_Toolkit::$file_ver, true );
		wp_localize_script( 'arkhe-toolkit-ajax', 'arkheAjaxVars', [
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'ajaxNonce' => wp_create_nonce( 'arkhe-toolkit-ajax-nonce' ),
		] );
	}
}


/**
 * 管理画面で読み込むファイル
 */
function enqueue_admin_scripts( $hook_suffix ) {

	$is_term_page    = 'term.php';
	$is_edit_page    = 'edit.php' === $hook_suffix || 'edit-tags.php' === $hook_suffix;
	$is_post_page    = 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix;
	$is_toolkit_page = str_contains( $hook_suffix, \Arkhe_Toolkit::MENU_SLUG );

	wp_enqueue_style( 'arkhe-toolkit-toolbar', ARKHE_TOOLKIT_URL . 'dist/css/toolbar.css', [], \Arkhe_Toolkit::$file_ver ); // ログイン時のフロントでも読み込む
	wp_enqueue_style( 'arkhe-toolkit-admin', ARKHE_TOOLKIT_URL . 'dist/css/admin.css', [], \Arkhe_Toolkit::$file_ver ); // 管理画面での共通CSS

	// Arkhe Toolkit設定ページのみ
	if ( $is_toolkit_page ) {

		wp_enqueue_style( 'arkhe-toolkit-menu', ARKHE_TOOLKIT_URL . 'dist/css/menu.css', [], \Arkhe_Toolkit::$file_ver );
		wp_enqueue_script( 'arkhe-toolkit-setting', ARKHE_TOOLKIT_URL . 'dist/js/admin/setting.js', [], \Arkhe_Toolkit::$file_ver, true );

	} elseif ( $is_post_page ) {

		wp_enqueue_script( 'arkhe-toolkit-post_editor', ARKHE_TOOLKIT_URL . 'dist/js/admin/post_editor.js', [ 'jquery' ], \Arkhe_Toolkit::$file_ver, true );
		wp_enqueue_style( 'arkhe-toolkit-post_editor', ARKHE_TOOLKIT_URL . 'dist/css/post_editor.css', [], \Arkhe_Toolkit::$file_ver );
	}

	// 投稿の編集ページ & ターム編集ページ
	if ( $is_term_page || $is_post_page ) {
		// メディアアップローダー
		wp_enqueue_media();
		wp_enqueue_script( 'arkhe-mediauploader', ARKHE_TOOLKIT_URL . 'dist/js/admin/media.js', [ 'jquery' ], \Arkhe_Toolkit::$file_ver, true );
	}

	// カスタマイザー
	if ( is_customize_preview() ) {
		wp_enqueue_style( 'arkhe-toolkit-customizer', ARKHE_TOOLKIT_URL . 'dist/css/customizer.css', [], \Arkhe_Toolkit::$file_ver );
	}

	// ajax関連処理 (ツールバーのキャッシュクリアボタンなどの処理)
	wp_enqueue_script( 'arkhe-toolkit-ajax', ARKHE_TOOLKIT_URL . 'dist/js/admin/ajax.js', [], \Arkhe_Toolkit::$file_ver, true );
	wp_localize_script( 'arkhe-toolkit-ajax', 'arkheAjaxVars', [
		'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
		'ajaxNonce'      => wp_create_nonce( 'arkhe-toolkit-ajax-nonce' ),
		'confirmMessage' => __( 'Will you really reset it?', 'arkhe-toolkit' ),
	] );

}


/**
 * wp_footerフック 優先度:1
 */
function hook_wp_footer_1() {

	// ウジェットプレビュー時は読み込まない
	if ( defined( 'IFRAME_REQUEST' ) && IFRAME_REQUEST ) return;

	if ( \Arkhe_Toolkit::is_use( 'clipboard' ) ) {
		wp_enqueue_script( 'clipboard' );
		wp_enqueue_script( 'arkhe-toolkit-clipboard', ARKHE_TOOLKIT_URL . 'dist/js/clipboard.js', [ 'clipboard' ], \Arkhe_Toolkit::$file_ver, true );
	}

	if ( \Arkhe_Toolkit::is_use( 'pinterest' ) ) {
		// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
		echo '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';
	}
}
