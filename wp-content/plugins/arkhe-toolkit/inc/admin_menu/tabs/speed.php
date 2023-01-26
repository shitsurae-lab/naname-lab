<?php
/**
 * 「拡張機能」タブの設定を登録
 */
namespace Arkhe_Toolkit\Menu;

defined( 'ABSPATH' ) || exit;

// PAGE_NAME
$db_name   = 'speed';
$page_name = \Arkhe_Toolkit::MENU_PAGE_PREFIX . $db_name;

$GLOBALS['ark_help_multiple'] = __( 'Multiple keywords should be separated by a "," or a line break.', 'arkhe-toolkit' );

/**
 * Prefetch
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Prefetch', 'arkhe-toolkit' ),
	'key'       => 'prefetch',
	'page_name' => $page_name,
	'db'        => $db_name,
	'page_cb'   => function ( $cb_args ) {
		\Arkhe_Toolkit::output_checkbox([
			'db'    => $cb_args['db'],
			'key'   => 'use_prefetch',
			'label' => __( 'Enable the Prefetch function', 'arkhe-toolkit' ),
		]);

		$disable_class = '1' === \Arkhe_Toolkit::get_data( 'speed', 'use_prefetch' ) ? '' : '-disable';
		echo '<div class="arkhe-menu__field ' . esc_attr( $disable_class ) . '" data-toggle="use_prefetch">';
			\Arkhe_Toolkit::output_textarea([
				'db'    => $cb_args['db'],
				'key'   => 'prefetch_prevent_keys',
				'label' => __( 'Keywords for pages not to be prefetched', 'arkhe-toolkit' ),
				'desc'  => __( 'All pages containing the specified character string will not be subject to Prefetch.', 'arkhe-toolkit' ) .
					$GLOBALS['ark_help_multiple'],
			]);
		echo '</div>';
	},
] );


/**
 * 遅延読み込み
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Lazyload of content', 'arkhe-toolkit' ),
	'key'       => 'lazyload',
	'page_name' => $page_name,
	'db'        => $db_name,
	'page_cb'   => function ( $cb_args ) {

		\Arkhe_Toolkit::output_select([
			'db'      => $cb_args['db'],
			'key'     => 'lazy_type',
			'label'   => __( 'How to lazy load images, videos, and embeds', 'arkhe-toolkit' ),
			'choices' => [
				'off'       => __( 'Turn off lazyload', 'arkhe-toolkit' ),
				'lazy'      => __( 'Use loading="lazy"', 'arkhe-toolkit' ),
				'lazysizes' => __( 'Use the script (lazysizes.js)', 'arkhe-toolkit' ),
			],
		]);

		if ( ! method_exists( 'Arkhe', 'get_lazy_type' ) ) {
			echo '<p style="margin-top:1em;color:red">※ ' . esc_html__( 'This setting is currently disabled due to an older version of Arkhe.', 'arkhe-toolkit' ) . '</p>';
		}
	},
] );


/**
 * 遅延読み込み
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'Lazyload of scripts', 'arkhe-toolkit' ),
	'key'       => 'delay_js',
	'page_name' => $page_name,
	'db'        => $db_name,
	'page_cb'   => function ( $cb_args ) {
		\Arkhe_Toolkit::output_checkbox([
			'db'    => $cb_args['db'],
			'key'   => 'use_delay_js',
			'label' => __( 'Enable lazyload of scripts', 'arkhe-toolkit' ),
		]);

		$use_delay_js  = \Arkhe_Toolkit::get_data( 'speed', 'use_delay_js' );
		$disable_class = '1' === $use_delay_js ? '' : '-disable';

		echo '<div class="arkhe-menu__field ' . esc_attr( $disable_class ) . '" data-toggle="use_delay_js">';
			\Arkhe_Toolkit::output_textarea([
				'db'    => $cb_args['db'],
				'key'   => 'delay_js_list',
				'label' => __( 'Keywords for the scripts to be delayed', 'arkhe-toolkit' ),
				'rows'  => 12,
				'desc'  => __( 'Lazy loading of script tags that contain the specified keywords.', 'arkhe-toolkit' ) .
					'<br>' . $GLOBALS['ark_help_multiple'],
			]);

			\Arkhe_Toolkit::output_textarea([
				'db'    => $cb_args['db'],
				'key'   => 'delay_js_prevent_pages',
				'label' => __( 'Page to turn off lazyload of scripts', 'arkhe-toolkit' ),
				'rows'  => 6,
				'desc'  => __( 'The lazyload of scripts will be turned off for pages that contain the specified keywords.', 'arkhe-toolkit' ) .
					'<br>' . $GLOBALS['ark_help_multiple'],

			]);

			\Arkhe_Toolkit::output_select([
				'db'      => $cb_args['db'],
				'key'     => 'delay_js_time',
				'label'   => __( 'Seconds to delay', 'arkhe-toolkit' ),
				'choices' => array_map( function( $i ) {
					return $i . __( 'sec', 'arkhe-toolkit' );
				}, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 ] ),
			]);
		echo '</div>';
	},
] );
