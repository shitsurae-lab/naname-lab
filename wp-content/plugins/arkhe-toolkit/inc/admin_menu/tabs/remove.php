<?php
/**
 * 「機能停止」タブの設定項目
 */
namespace Arkhe_Toolkit\Menu;

defined( 'ABSPATH' ) || exit;

// PAGE_NAME
$db_name   = 'remove';
$page_name = \Arkhe_Toolkit::MENU_PAGE_PREFIX . $db_name;


/**
 * WordPressの機能
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'     => __( 'WordPress features', 'arkhe-toolkit' ),
	'key'       => 'remove_core',
	'page_name' => $page_name,
	'db'        => $db_name,
	'page_cb'   => function ( $args ) {
		$remove_settings = [
			'remove_wpver'         => __( 'Stop outputting WordPress version', 'arkhe-toolkit' ),
			'remove_emoji'         => __( 'Stop loading scripts for emoji', 'arkhe-toolkit' ),
			'remove_wlwmanifest'   => __( 'Stop linking with Windows Live Write', 'arkhe-toolkit' ),
			'remove_rsd_link'      => __( 'Stop EditURI (RSD Link)', 'arkhe-toolkit' ),
			'remove_self_ping'     => __( 'Stop self-pingback', 'arkhe-toolkit' ),
			'remove_sitemap'       => __( 'Stop core sitemap functionality', 'arkhe-toolkit' ),
			'remove_rest_link'     => __( 'Stop outputting link tags for REST API', 'arkhe-toolkit' ),
			'remove_srcset'        => __( 'Stop outputting "srcset"', 'arkhe-toolkit' ),
			'remove_rel_link'      => __( 'Stop outputting  "rel = prev / next"', 'arkhe-toolkit' ),
			'remove_wptexturize'   => __( 'Stop automatic conversion of symbols (disable wptexturize)', 'arkhe-toolkit' ),
			'remove_feed_link'     => __( 'Stop RSS feed', 'arkhe-toolkit' ),
		];
		foreach ( $remove_settings as $key => $label ) {
			\Arkhe_Toolkit::output_checkbox([
				'db'    => $args['db'],
				'key'   => $key,
				'label' => $label,
			]);
		}
	},
] );
