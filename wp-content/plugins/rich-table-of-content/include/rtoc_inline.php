<?php

function rtoc_senior_color()
{
	$rtoc_title_color       = get_option('rtoc_title_color');
	$rtoc_text_color        = get_option('rtoc_text_color');
	$rtoc_back_color        = get_option('rtoc_back_color');
	$rtoc_border_color      = get_option('rtoc_border_color');
	$rtoc_h2_color          = get_option('rtoc_h2_color');
	$rtoc_h3_color          = get_option('rtoc_h3_color');
	$rtoc_back_button_color = get_option('rtoc_back_button_color');
	$rtoc_frame_design      = get_option('rtoc_frame_design');
?>
	<style type="text/css">
		/*<!-- rtoc -->*/
		.rtoc-mokuji-content {
			background-color: <?php echo $rtoc_back_color; ?>;
		}

		.rtoc-mokuji-content.frame1 {
			border: 1px solid <?php echo $rtoc_border_color; ?>;
		}

		.rtoc-mokuji-content #rtoc-mokuji-title {
			color: <?php echo $rtoc_title_color; ?>;
		}

		.rtoc-mokuji-content .rtoc-mokuji li>a {
			color: <?php echo $rtoc_text_color; ?>;
		}

		.rtoc-mokuji-content .mokuji_ul.level-1>.rtoc-item::before {
			background-color: <?php echo $rtoc_h2_color; ?> !important;
		}

		.rtoc-mokuji-content .mokuji_ul.level-2>.rtoc-item::before {
			background-color: <?php echo $rtoc_h3_color; ?> !important;
		}

		.rtoc-mokuji-content.frame2::before,
		.rtoc-mokuji-content.frame3,
		.rtoc-mokuji-content.frame4,
		.rtoc-mokuji-content.frame5 {
			border-color: <?php echo $rtoc_border_color; ?> !important;
		}

		.rtoc-mokuji-content.frame5::before,
		.rtoc-mokuji-content.frame5::after {
			background-color: <?php echo $rtoc_border_color; ?>;
		}

		.widget_block #rtoc-mokuji-wrapper .rtoc-mokuji.level-1 .rtoc-item.rtoc-current:after,
		.widget #rtoc-mokuji-wrapper .rtoc-mokuji.level-1 .rtoc-item.rtoc-current:after,
		#scrollad #rtoc-mokuji-wrapper .rtoc-mokuji.level-1 .rtoc-item.rtoc-current:after,
		#sideBarTracking #rtoc-mokuji-wrapper .rtoc-mokuji.level-1 .rtoc-item.rtoc-current:after {
			background-color: <?php echo $rtoc_h2_color; ?> !important;
		}

		.cls-1,
		.cls-2 {
			stroke: <?php echo $rtoc_border_color; ?>;
		}

		.rtoc-mokuji-content .decimal_ol.level-2>.rtoc-item::before,
		.rtoc-mokuji-content .mokuji_ol.level-2>.rtoc-item::before,
		.rtoc-mokuji-content .decimal_ol.level-2>.rtoc-item::after,
		.rtoc-mokuji-content .decimal_ol.level-2>.rtoc-item::after {
			color: <?php echo $rtoc_h3_color; ?>;
			background-color: <?php echo $rtoc_h3_color; ?>;
		}

		.rtoc-mokuji-content .rtoc-mokuji.level-1>.rtoc-item::before {
			color: <?php echo $rtoc_h2_color; ?>;
		}

		.rtoc-mokuji-content .decimal_ol>.rtoc-item::after {
			background-color: <?php echo $rtoc_h2_color; ?>;
		}

		.rtoc-mokuji-content .decimal_ol>.rtoc-item::before {
			color: <?php echo $rtoc_h2_color; ?>;
		}

		/*rtoc_return*/
		#rtoc_return a::before {
			background-image: url(<?php echo (plugins_url('../img/rtoc_return.png', __FILE__)) ?>);
		}

		#rtoc_return a {
			background-color: <?php echo $rtoc_back_button_color; ?> !important;
		}

		/* アクセントポイント */
		.rtoc-mokuji-content .level-1>.rtoc-item #rtocAC.accent-point::after {
			background-color: <?php echo $rtoc_h2_color; ?>;
		}

		.rtoc-mokuji-content .level-2>.rtoc-item #rtocAC.accent-point::after {
			background-color: <?php echo $rtoc_h3_color; ?>;
		}

		/* rtoc_addon */
		<?php
		// ================================================
		// Addon有効時（RTOC ver1.2〜）.
		// ================================================
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if (is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) :
		?><?php
			// frame6のみタイトル色が違う為, 分岐.
			if ($rtoc_frame_design === 'frame6') : ?>.rtoc-mokuji-content #rtoc-mokuji-title {
			color: <?php echo '#ffffff'; ?>;
		}

		<?php else : ?>.rtoc-mokuji-content #rtoc-mokuji-title {
			color: <?php echo $rtoc_title_color; ?>;
		}

		<?php endif; ?>.rtoc-mokuji-content.frame6,
		.rtoc-mokuji-content.frame7::before,
		.rtoc-mokuji-content.frame8::before {
			border-color: <?php echo $rtoc_border_color; ?>;
		}

		<?php // frame6 のタイトル背景色は,タイトル色ではなく,枠線カラーなので注意. 
		?>.rtoc-mokuji-content.frame6 #rtoc-mokuji-title,
		.rtoc-mokuji-content.frame7 #rtoc-mokuji-title::after {
			background-color: <?php echo $rtoc_border_color; ?>;
		}

		#rtoc-mokuji-wrapper.rtoc-mokuji-content.rtoc_h2_timeline .mokuji_ol.level-1>.rtoc-item::after,
		#rtoc-mokuji-wrapper.rtoc-mokuji-content.rtoc_h2_timeline .level-1.decimal_ol>.rtoc-item::after,
		#rtoc-mokuji-wrapper.rtoc-mokuji-content.rtoc_h3_timeline .mokuji_ol.level-2>.rtoc-item::after,
		#rtoc-mokuji-wrapper.rtoc-mokuji-content.rtoc_h3_timeline .mokuji_ol.level-2>.rtoc-item::after,
		.rtoc-mokuji-content.frame7 #rtoc-mokuji-title span::after {
			background-color: <?php echo $rtoc_h2_color; ?>;
		}

		<?php // frame6はタイトル色とタイトル背景色が別設定の為, .widgetでは元に戻す. 
		?>.widget #rtoc-mokuji-wrapper.rtoc-mokuji-content.frame6 #rtoc-mokuji-title {
			color: <?php echo $rtoc_title_color; ?>;
			background-color: <?php echo $rtoc_back_color; ?>;
		}

		<?php endif; ?>
	</style>
	<?php
}
add_action('wp_head', 'rtoc_senior_color', 12, 1);


$my_theme    = wp_get_theme();
$theme_name  = $my_theme->get('Name');
$theme_color = get_theme_mod('theme_color', '#3b4675');
$jin_preset  = get_option('rtoc_color');

if ($theme_name == 'JIN' || $theme_name == 'jin-child') {
	if ($jin_preset == 'preset1') {

		function rtoc_jin_color()
		{
			$theme_color       = get_theme_mod('theme_color');
			$rtoc_frame_design = get_option('rtoc_frame_design');
	?>
			<style type="text/css">
				.rtoc-mokuji-content #rtoc-mokuji-title {
					color: <?php echo $theme_color; ?>;
				}

				.rtoc-mokuji-content.frame2::before,
				.rtoc-mokuji-content.frame3,
				.rtoc-mokuji-content.frame4,
				.rtoc-mokuji-content.frame5 {
					border-color: <?php echo $theme_color; ?>;
				}

				.rtoc-mokuji-content .decimal_ol>.rtoc-item::before,
				.rtoc-mokuji-content .decimal_ol.level-2>.rtoc-item::before,
				.rtoc-mokuji-content .mokuji_ol>.rtoc-item::before {
					color: <?php echo $theme_color; ?>;
				}

				.rtoc-mokuji-content .decimal_ol>.rtoc-item::after,
				.rtoc-mokuji-content .decimal_ol>.rtoc-item::after,
				.rtoc-mokuji-content .mokuji_ul.level-1>.rtoc-item::before,
				.rtoc-mokuji-content .mokuji_ul.level-2>.rtoc-item::before {
					background-color: <?php echo $theme_color; ?> !important;
				}

				<?php
				// ================================================
				// Addon有効時.（RTOC ver1.2〜）.
				// ================================================
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
				if (is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) :
				?><?php
					// frame6のみタイトル色が違う為, 分岐.
					if ($rtoc_frame_design === 'frame6') : ?>.rtoc-mokuji-content #rtoc-mokuji-title {
					color: <?php echo '#ffffff'; ?>;
				}

				<?php else : ?>.rtoc-mokuji-content #rtoc-mokuji-title {
					color: <?php echo $theme_color; ?>;
				}

				<?php endif; ?>.rtoc-mokuji-content.frame6,
				.rtoc-mokuji-content.frame7::before,
				.rtoc-mokuji-content.frame8::before {
					border-color: <?php echo $theme_color; ?>;
				}

				.rtoc-mokuji-content.frame6 #rtoc-mokuji-head,
				.rtoc-mokuji-content.frame7 #rtoc-mokuji-head::after,
				.rtoc-mokuji-content.frame7 #rtoc-mokuji-head span::after {
					background-color: <?php echo $theme_color; ?>;
				}

				<?php endif; ?>
			</style>
<?php
		}
		add_action('wp_head', 'rtoc_jin_color', 12, 1);
	}
}
?>