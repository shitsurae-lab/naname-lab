<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * フロントスタイル
 */
add_action( 'wp_head', function() {

	echo PHP_EOL . '<!-- Arkhe CSS Editor -->' . PHP_EOL;

	$front_css  = '';
	$front_css .= \Arkhe_CSS_Editor::minify_css( get_option( \Arkhe_CSS_Editor::DB_NAME['common'] ) );
	$front_css .= \Arkhe_CSS_Editor::minify_css( get_option( \Arkhe_CSS_Editor::DB_NAME['front'] ) );

	if ( $front_css ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<style id="arkhe-css-editor--front">' . $front_css . '</style>' . PHP_EOL;
	}

	if ( is_singular() || is_home() ) {
		$meta_css = get_post_meta( get_queried_object_id(), \Arkhe_CSS_Editor::META_KEY, true );
		if ( $meta_css ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<style id="arkhe-css-editor--meta">' . \Arkhe_CSS_Editor::minify_css( $meta_css ) . '</style>' . PHP_EOL;
		}
	}

	echo '<!-- / Arkhe CSS Editor -->' . PHP_EOL;
});


/**
 * エディタースタイル
 */
add_action( 'admin_head', function() {

	// エディター側の<html>に[data-sidebar]を付与( 投稿リストブロック用 )
	echo PHP_EOL . '<!-- Arkhe CSS Editor -->' . PHP_EOL;

	// phpcs:ignore
	echo '<style id="arkhe-css-editor--menu">.toplevel_page_' . \Arkhe_CSS_Editor::MENU_SLUG . ' .wp-menu-image img {' .
			'width: 20px; height: 20px; padding-top: 6px!important;' .
		'}</style>' . PHP_EOL;

	global $hook_suffix;
	if ( 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix ) {

		$editor_css  = '';
		$editor_css .= \Arkhe_CSS_Editor::minify_css( get_option( \Arkhe_CSS_Editor::DB_NAME['common'] ) );
		$editor_css .= \Arkhe_CSS_Editor::minify_css( get_option( \Arkhe_CSS_Editor::DB_NAME['editor'] ) );
		if ( $editor_css ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<style id="arkhe-css-editor--editor">' . $editor_css . '</style>' . PHP_EOL;
		}
	}

	echo '<!-- / Arkhe CSS Editor -->' . PHP_EOL;
});
