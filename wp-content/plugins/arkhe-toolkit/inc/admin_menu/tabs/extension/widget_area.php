<?php
/**
 * ウィジェットエリアセクション
 */
$remove_settings = [
	'use_page_widget'   => __( 'Add widget area for "Page"', 'arkhe-toolkit' ),
	'use_post_widget'   => __( 'Add widget area for "Post"', 'arkhe-toolkit' ),
	'use_home_widget'   => __( 'Add widget area for "Home"', 'arkhe-toolkit' ),
	'use_fix_sidebar'   => __( 'Add widget area for "Sticky sidebar"', 'arkhe-toolkit' ),
	'use_before_footer' => __( 'Add widget area for "Before Footer"', 'arkhe-toolkit' ),
];
foreach ( $remove_settings as $key => $label ) {
	\Arkhe_Toolkit::output_checkbox([
		'db'    => $cb_args['db'],
		'key'   => $key,
		'label' => $label,
	]);
}
