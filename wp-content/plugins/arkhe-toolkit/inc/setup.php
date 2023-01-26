<?php
namespace Arkhe_Toolkit;

add_action( 'init', function() {
	if ( ! class_exists( 'Arkhe' ) ) return;
	\Arkhe::set_plugin_data( 'use_arkhe_toolkit', true );

	// Arkhe::$lazy_type のセット
	$lazy_type = \Arkhe_Toolkit::get_data( 'speed', 'lazy_type' );
	if ( $lazy_type && method_exists( '\Arkhe', 'set_lazy_type' ) ) {
		\Arkhe::set_lazy_type( $lazy_type );
	}

	// lazyloadがオフの時
	if ( 'off' === $lazy_type ) {
		add_filter( 'wp_lazy_loading_enabled', '__return_false' );
	}

	// Luminous使用する場合、画像・ギャラリーブロックがあるかチェック
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_luminous' ) && method_exists( '\Arkhe', 'set_use' ) ) {
		add_filter( 'render_block_core/image', __NAMESPACE__ . '\render_check_for_lb', 10 );
		add_filter( 'render_block_core/gallery', __NAMESPACE__ . '\render_check_for_lb', 10 );
	}
} );


function render_check_for_lb( $block_content ) {
	\Arkhe::set_use( 'luminous', true );
	remove_filter( 'render_block_core/image', __NAMESPACE__ . '\render_check_for_lb', 10 );
	remove_filter( 'render_block_core/gallery', __NAMESPACE__ . '\render_check_for_lb', 10 );
	return $block_content;
}
