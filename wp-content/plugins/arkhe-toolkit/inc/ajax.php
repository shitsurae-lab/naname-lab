<?php
namespace Arkhe_Toolkit;

/**
 * 設定データリセット
 */
add_action( 'wp_ajax_arkhe_toolkit_reset_data', function() {
	if ( \Arkhe_Toolkit::check_ajax_nonce() ) {

		// All Clear
		\Arkhe_Toolkit::reset_data();

		wp_die( wp_json_encode( __( 'The reset is complete.', 'arkhe-toolkit' ) ) );
	}
	wp_die( wp_json_encode( 'Nonce error.' ) );
} );


/**
 * キャッシュのクリア
 */
add_action( 'wp_ajax_arkhe_toolkit_clear_cache', function() {

	if ( \Arkhe_Toolkit::check_ajax_nonce() ) {

		// キャッシュクリア
		\Arkhe_Toolkit::clear_cache();

		wp_die( wp_json_encode( __( 'The cache clear is complete.', 'arkhe-toolkit' ) ) );

	}

	wp_die( wp_json_encode( 'Nonce error.' ) );

} );
