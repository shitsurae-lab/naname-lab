<?php
/**
 * コンテンツへの追加処理
 */
$remove_settings = [
	// 'use_lazysizes'   => __( 'Lazy loading of images / videos / iframes with scripts', 'arkhe-toolkit' ),
	'use_luminous'    => __( 'Allow the image to be enlarged by clicking', 'arkhe-toolkit' ),
	'remove_emp_p'    => __( 'Automatically remove empty p tags', 'arkhe-toolkit' ),
];
foreach ( $remove_settings as $key => $label ) {
	\Arkhe_Toolkit::output_checkbox([
		'db'    => $cb_args['db'],
		'key'   => $key,
		'label' => $label,
	]);
}
