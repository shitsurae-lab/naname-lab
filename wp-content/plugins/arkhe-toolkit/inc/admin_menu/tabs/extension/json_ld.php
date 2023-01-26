<?php
/**
 * 構造化データ
 */
$remove_settings = [
	'use_jsonld'     => __( 'Automatically generate JSON-LD', 'arkhe-toolkit' ),
	// 'use_gnav_json'  => __( 'Include global navigation structure in JSON-LD', 'arkhe-toolkit' ),
	'use_bread_json' => __( 'Include breadcrumbs structure in JSON-LD', 'arkhe-toolkit' ),
];
foreach ( $remove_settings as $key => $label ) {
	\Arkhe_Toolkit::output_checkbox([
		'db'    => $cb_args['db'],
		'key'   => $key,
		'label' => $label,
	]);
}
