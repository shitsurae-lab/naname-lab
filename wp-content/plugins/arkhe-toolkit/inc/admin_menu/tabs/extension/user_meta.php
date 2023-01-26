<?php
/**
 * ユーザーメタセクション
 */
$remove_settings = [
	'use_user_position' => __( 'Add user position setting field', 'arkhe-toolkit' ),
	'use_user_urls'     => __( 'Add SNS URL information', 'arkhe-toolkit' ),

];
foreach ( $remove_settings as $key => $label ) {
	\Arkhe_Toolkit::output_checkbox([
		'db'    => $cb_args['db'],
		'key'   => $key,
		'label' => $label,
	]);
}
