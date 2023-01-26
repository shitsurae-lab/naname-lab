<?php
namespace Arkhe_Toolkit\Output;

/**
 * head内コードの出力
 */
add_action( 'wp_head', __NAMESPACE__ . '\output_wp_head' );
function output_wp_head() {
	$head_code = \Arkhe_Toolkit::get_data( 'code', 'head_code' );
	if ( $head_code ) {
		echo '<!-- Arkhe Toolkit : @setting/head -->' . PHP_EOL .
			$head_code . PHP_EOL . // phpcs:ignore WordPress.Security
		'<!-- / Arkhe Toolkit -->' . PHP_EOL;
	}
}


/**
 * head内コードの出力 (meta)
 */
add_action( 'wp_head', __NAMESPACE__ . '\output_wp_head_99', 99 );
function output_wp_head_99() {
	$code = '';
	if ( is_single() || is_page() || is_home() ) {
		$code = get_post_meta( get_queried_object_id(), 'ark_meta_code_head', true );
	}

	if ( $code ) {
		echo PHP_EOL . '<!-- Arkhe Toolkit : @meta/head -->' . PHP_EOL .
			$code . PHP_EOL . // phpcs:ignore WordPress.Security
		'<!-- / Arkhe Toolkit -->' . PHP_EOL;
	}
}


/**
 * bodyタグ開始後コードの出力
 */
add_action( 'wp_body_open', __NAMESPACE__ . '\output_wp_body_open' );
function output_wp_body_open() {
	$body_code = \Arkhe_Toolkit::get_data( 'code', 'body_code' );
	if ( $body_code ) {
		echo PHP_EOL . '<!-- Arkhe Toolkit : @setting/body_open -->' . PHP_EOL .
			$body_code . PHP_EOL . // phpcs:ignore WordPress.Security
		'<!-- / Arkhe Toolkit -->' . PHP_EOL;
	}
}


/**
 * bodyタグ終了前コードの出力
 */
add_action( 'wp_footer', __NAMESPACE__ . '\output_wp_footer__99', 99 );
function output_wp_footer__99() {
	$code = '';

	$foot_code = \Arkhe_Toolkit::get_data( 'code', 'foot_code' );
	if ( $foot_code ) {
		$code .= PHP_EOL . '<!-- @setting/foot -->' . PHP_EOL . $foot_code;
	}

	if ( is_single() || is_page() || is_home() ) {
		$the_id         = get_queried_object_id();
		$foot_meta_code = get_post_meta( $the_id, 'ark_meta_code_foot', true );
		if ( $foot_meta_code ) {
			$code .= PHP_EOL . '<!-- @meta/foot -->' . PHP_EOL . $foot_meta_code;
		}
	}

	if ( $code ) {
		echo '<!-- Arkhe Toolkit -->' .
			$code . PHP_EOL . // phpcs:ignore WordPress.Security
		'<!-- / Arkhe Toolkit -->' . PHP_EOL;
	}
}
