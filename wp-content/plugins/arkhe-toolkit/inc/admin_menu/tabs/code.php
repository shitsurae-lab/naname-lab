<?php
/**
 * 「コード追加」タブの設定項目
 */
namespace Arkhe_Toolkit\Menu;

defined( 'ABSPATH' ) || exit;

// PAGE_NAME
$db_name   = 'code';
$page_name = \Arkhe_Toolkit::MENU_PAGE_PREFIX . $db_name;


/**
 * head内コード
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'      => __( 'Code to output in the &lt;head&gt; tag', 'arkhe-toolkit' ),
	'key'        => 'head_code',
	'page_name'  => $page_name,
	'db'         => $db_name,
	'page_cb'    => function ( $args ) {
		\Arkhe_Toolkit::output_textarea([
			'db'      => $args['db'],
			'key'     => $args['section_key'],
			'rows'    => '6',
			'is_wide' => true,
			'desc'    => __( 'The code entered here will be output in the <code>&lt;head&gt;</code> tag.', 'arkhe-toolkit' ) .
					'( ' . __( 'It is output by the<code>wp_head</code>hook.', 'arkhe-toolkit' ) . ' )',
		]);
	},
] );




/**
 * <body>後コード
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'      => __( 'Code to output after starting &lt;body&gt; tag', 'arkhe-toolkit' ),
	'key'        => 'body_code',
	'page_name'  => $page_name,
	'db'         => $db_name,
	'page_cb'    => function ( $args ) {
		\Arkhe_Toolkit::output_textarea([
			'db'      => $args['db'],
			'key'     => $args['section_key'],
			'rows'    => '6',
			'is_wide' => true,
			'desc'    => __( 'The code entered here will be output after the <code>&lt;body&gt;</code> tag starts.', 'arkhe-toolkit' ) .
					'( ' . __( 'It is output by the<code>wp_body_open</code>hook.', 'arkhe-toolkit' ) . ' )',
		]);
	},
] );




/**
 * </body>前コード
 */
\Arkhe_Toolkit::add_menu_section( [
	'title'      => __( 'Code to output before the end of &lt;/body&gt; tag', 'arkhe-toolkit' ),
	'key'        => 'foot_code',
	'page_name'  => $page_name,
	'db'         => $db_name,
	'page_cb'    => function ( $args ) {
		\Arkhe_Toolkit::output_textarea([
			'db'      => $args['db'],
			'key'     => $args['section_key'],
			'rows'    => '6',
			'is_wide' => true,
			'desc'    => __( 'The code entered here will be output before the end of the <code>&lt;/body&gt;</code> tag.', 'arkhe-toolkit' ) .
					'( ' . __( 'It is output by the<code>wp_footer</code>hook.', 'arkhe-toolkit' ) . ' )',
		]);
	},
] );
