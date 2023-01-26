<?php
namespace Arkhe_Toolkit;

use \Arkhe_Theme\Customizer;

$arkhe_section = 'arkhe_section_single';

// シェアボタン
Customizer::big_title(
	$arkhe_section,
	'share_btn_settings',
	[
		'label'       => __( 'Share buttons', 'arkhe-toolkit' ),
		'classname'   => '-toolkit',
	]
);

// 記事の上部に表示
Customizer::add(
	$arkhe_section,
	'show_sharebtns_top',
	[
		'classname'   => '',
		'label'       => __( 'Display on the article', 'arkhe-toolkit' ),
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_sharebtns_top' ),
	]
);

// 記事の下部に表示
Customizer::add(
	$arkhe_section,
	'show_sharebtns_bottom',
	[
		'classname'   => '',
		'label'       => __( 'Display below the article', 'arkhe-toolkit' ),
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_sharebtns_bottom' ),
	]
);

// どのボタンを表示するか
Customizer::sub_title(
	$arkhe_section,
	'select_display_btns',
	[
		'label' => __( 'Which button to display', 'arkhe-toolkit' ),
	]
);

// Facebook
Customizer::add(
	$arkhe_section,
	'show_share_fb',
	[
		'classname'   => '',
		'label'       => 'Facebook',
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_fb' ),
	]
);

// Twitter
Customizer::add(
	$arkhe_section,
	'show_share_tw',
	[
		'classname'   => '',
		'label'       => 'Twitter',
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_tw' ),
	]
);

// はてブ
Customizer::add(
	$arkhe_section,
	'show_share_hatebu',
	[
		'classname'   => '',
		'label'       => __( 'Hatebu', 'arkhe-toolkit' ),
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_hatebu' ),
	]
);

// Pocket
Customizer::add(
	$arkhe_section,
	'show_share_pocket',
	[
		'classname'   => '',
		'label'       => 'Pocket',
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_pocket' ),
	]
);

// Pinterest
Customizer::add(
	$arkhe_section,
	'show_share_pin',
	[
		'classname'   => '',
		'label'       => 'Pinterest',
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_pin' ),
	]
);

// LINE
Customizer::add(
	$arkhe_section,
	'show_share_line',
	[
		'classname'   => '',
		'label'       => 'LINE',
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_line' ),
	]
);

// URLコピーボタン
Customizer::add(
	$arkhe_section,
	'show_share_urlcopy',
	[
		'classname'   => '',
		'label'       => __( 'URL copy button', 'arkhe-toolkit' ),
		'type'        => 'checkbox',
		'db'          => \Arkhe_Toolkit::DB_NAMES['customizer'],
		'default'     => \Arkhe_Toolkit::get_default_data( 'customizer', 'show_share_urlcopy' ),
	]
);
