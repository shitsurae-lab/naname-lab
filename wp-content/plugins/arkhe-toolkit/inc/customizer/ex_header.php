<?php
namespace Arkhe_Toolkit;

use \Arkhe_Theme\Customizer;

$arkhe_section = 'arkhe_section_header';

// ドロワーメニューの展開方式
Customizer::big_title(
	$arkhe_section,
	'drawer_move',
	[
		'label'       => __( 'How to expand the drawer menu', 'arkhe-toolkit' ),
		'classname'   => '-toolkit',
	]
);

Customizer::add(
	$arkhe_section,
	'drawer_move',
	[
		'type'        => 'radio',
		'choices'     => [
			'fade'  => __( 'Fade-in', 'arkhe-toolkit' ),
			'left'  => __( 'Slide from left', 'arkhe-toolkit' ),
			'right' => __( 'Slide from right', 'arkhe-toolkit' ),
		],
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'drawer_move' ),
	]
);


// ヘッダーより下に表示する
Customizer::add(
	$arkhe_section,
	'header_above_drawer',
	[
		'type'        => 'checkbox',
		'label'       => __( 'Display below the header', 'arkhe-toolkit' ),
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'header_above_drawer' ),
	]
);
