<?php

// 管理画面の構築 (admin screen setup)
function rtoc_admin_screen()
{
	$rtoc_main_title = __('RTOC settings', 'rich-table-of-content');
	add_menu_page(
		'rtoc_admin_screen',
		$rtoc_main_title,
		'manage_options',
		'rtoc_settings',
		'rtoc_setting_screen_contents',
		'dashicons-editor-ul',
		30
	);
}
add_action('admin_menu', 'rtoc_admin_screen', 10);


// 目次設定画面に独自のCSSとJSを読み込む (original script output in Mokuji admin screen)
function rtoc_admin_enqueue($hook_suffix)
{
	if ('toplevel_page_rtoc_settings' === $hook_suffix) {
		wp_enqueue_script('rtoc_admin', plugins_url('js/rtoc_admin.js', dirname(__FILE__), array('jquery')));
		wp_enqueue_style('admin_rtoc_style', plugins_url('css/admin_rtoc_style.css', dirname(__FILE__)));
		$my_theme    = wp_get_theme();
		$theme_name  = $my_theme->get('Name');
		$theme_color = get_theme_mod('theme_color');
		$text_color = '';
		if($theme_name == 'JIN:R' || $theme_name == 'JIN:R child'){
			$theme_color = jinr__theme_color();
			$text_color = jinr__text_color();
			if (get_option('rtoc_color') == 'preset1') {
				update_option('rtoc_title_color', jinr__theme_color());
				update_option('rtoc_text_color', jinr__text_color());
				update_option('rtoc_border_color', jinr__theme_color());
				update_option('rtoc_h2_color', jinr__theme_color());
				update_option('rtoc_h3_color', jinr__theme_color());
				update_option('rtoc_back_button_color', jinr__theme_color());
			}
		} elseif ($theme_name == 'JIN' || $theme_name == 'jin-child') {
			$theme_color = get_theme_mod( 'theme_color', '#3b4675');
		} else{
			$theme_color = '#000';
		}

		$rtoc_theme_name = array('rtocThemeName' => $theme_name);
		wp_localize_script('rtoc_admin', 'rtocThemeName', $rtoc_theme_name);
		$rtoc_theme_color = array('rtocThemeColor' => $theme_color);
		wp_localize_script('rtoc_admin', 'rtocThemeColor', $rtoc_theme_color);
		$rtoc_text_color = array('rtocTextColor' => $text_color);
		wp_localize_script('rtoc_admin', 'rtocTextColor', $rtoc_text_color);


		// Addonの有効時はrtoc_admin_addon.jsを読み込む（RTOC ver1.2〜）.
		if (is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) {
			wp_enqueue_script('rtoc_admin_addon_js', plugins_url('../js/rtoc_admin_addon.js', __FILE__), array('jquery'), false, true);
			// 「タイトルにアイコン追加」の値
			$rtoc_title_icon_add = array(
				'rtocTitleIcon' => get_option('rtoc_title_icon_add')
			);
			wp_localize_script('rtoc_admin_addon_js', 'rtocTitleIcon', $rtoc_title_icon_add);
			// 「アイコンの位置」の値.
			$rtoc_title_icon_location = array(
				'rtocTitleIconLocation' => get_option('rtoc_title_icon_location')
			);
			wp_localize_script('rtoc_admin_addon_js', 'rtocTitleIconLocation', $rtoc_title_icon_location);
			// 「H2リストをタイムライン化」.
			$rtoc_h2_timeline = array(
				'rtocH2Timeline' => get_option('rtoc_h2_timeline')
			);
			wp_localize_script('rtoc_admin_addon_js', 'rtocH2Timeline', $rtoc_h2_timeline);
			// 「H3リストをタイムライン化」.
			$rtoc_h3_timeline = array(
				'rtocH3Timeline' => get_option('rtoc_h3_timeline')
			);
			wp_localize_script('rtoc_admin_addon_js', 'rtocH3Timeline', $rtoc_h3_timeline);
		}
	}
}
add_action('admin_enqueue_scripts', 'rtoc_admin_enqueue');

// 基本設定
function rtoc_basic_settings_init()
{
	$rtoc_basic_title = __('Basic Settings', 'rich-table-of-content');
	add_settings_section(
		'rtoc_basic_section',
		$rtoc_basic_title,
		'rtoc_basic_function_callback',
		'rtoc_basic_setting'
	);
}
add_action('admin_init', 'rtoc_basic_settings_init');

function rtoc_basic_function_callback()
{
	$rtoc_basic_txt = __('Configure the basic settings for the table of contents.', 'rich-table-of-content');
	echo '<p>' . $rtoc_basic_txt . '</p>';
}

function rtoc_basic_setting_field()
{
	$rtoc_title        = __('Table of contents title', 'rich-table-of-content');
	$rtoc_display      = __('The page to display the table of contents', 'rich-table-of-content');
	$rtoc_display_post = __('post', 'rich-table-of-content');
	$rtoc_display_page = __('page', 'rich-table-of-content');
	$rtoc_display_cat  = __('category', 'rich-table-of-content');

	$rtoc_headline    = __('Heading to be displayed', 'rich-table-of-content');
	$rtoc_headline_h2 = __('Display up to H2', 'rich-table-of-content');
	$rtoc_headline_h3 = __('Display up to H3', 'rich-table-of-content');
	$rtoc_headline_h4 = __('Display up to H4', 'rich-table-of-content');

	$rtoc_display_amount = __('Display conditions', 'rich-table-of-content');
	$rtoc_font           = __('Fonts', 'rich-table-of-content');
	$rtoc_font_default   = __('Default', 'rich-table-of-content');

	add_settings_field(
		'rtoc_title',
		$rtoc_title,
		'rtoc_title_callback',
		'rtoc_basic_setting',
		'rtoc_basic_section'
	);
	add_settings_field(
		'rtoc_display',
		$rtoc_display,
		'rtoc_display_callback',
		'rtoc_basic_setting',
		'rtoc_basic_section',
		array(
			'options' => array(
				'post' => $rtoc_display_post,
				'page' => $rtoc_display_page,
				'category' => $rtoc_display_cat,
			)
		)
	);
	add_settings_field(
		'rtoc_headline_display',
		$rtoc_headline,
		'rtoc_headline_display_callback',
		'rtoc_basic_setting',
		'rtoc_basic_section',
		array(
			'options' => array(
				'h2' => $rtoc_headline_h2,
				'h3' => $rtoc_headline_h3,
				'h4' => $rtoc_headline_h4,
			)
		)
	);
	add_settings_field(
		'rtoc_display_headline_amount',
		$rtoc_display_amount,
		'rtoc_display_headline_amount_callback',
		'rtoc_basic_setting',
		'rtoc_basic_section',
		array(
			'options' => array(
				'1' => '1',
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
				'9' => '9',
				'10' => '10',
			)
		)
	);
	add_settings_field(
		'rtoc_font',
		$rtoc_font,
		'rtoc_font_callback',
		'rtoc_basic_setting',
		'rtoc_basic_section',
		array(
			'options' => array(
				'default'   => $rtoc_font_default,
				'helvetica' => 'Helvetica',
				'noto-sans' => 'Noto Sans',
			)
		)
	);

	register_setting('rtoc_config', 'rtoc_title');
	register_setting('rtoc_config', 'rtoc_display');
	register_setting('rtoc_config', 'rtoc_headline_display');
	register_setting('rtoc_config', 'rtoc_display_headline_amount');
	register_setting('rtoc_config', 'rtoc_font');
}
add_action('admin_init', 'rtoc_basic_setting_field', 20);


// デザイン設定
function rtoc_design_settings_init()
{
	$rtoc_design = __('Design settings', 'rich-table-of-content');
	add_settings_section(
		'rtoc_design_section',
		$rtoc_design,
		'rtoc_design_function_callback',
		'rtoc_design_setting'
	);
}
add_action('admin_init', 'rtoc_design_settings_init');

function rtoc_design_function_callback()
{
	$rtoc_design_txt = __('You can freely set the table of contents design.', 'rich-table-of-content');
	echo '<p>' . $rtoc_design_txt . '</p>';
}

function rtoc_design_setting_field()
{
	$rtoc_design_title        = __('Title display', 'rich-table-of-content');
	$rtoc_design_title_left   = __('Left-aligned', 'rich-table-of-content');
	$rtoc_design_title_center = __('Center-aligned', 'rich-table-of-content');

	$rtoc_design_h2      = __('H2 list design', 'rich-table-of-content');
	$rtoc_design_h3      = __('H3 list design', 'rich-table-of-content');
	$rtoc_design_round   = __('round', 'rich-table-of-content');
	$rtoc_design_number1 = __('number1', 'rich-table-of-content');
	$rtoc_design_number2 = __('number2', 'rich-table-of-content');
	$rtoc_design_none    = __('none', 'rich-table-of-content');

	$rtoc_design_frame           = __('Frame design', 'rich-table-of-content');
	$rtoc_design_animation       = __('Display animation', 'rich-table-of-content');
	$rtoc_design_animation_fade  = __('fade', 'rich-table-of-content');
	$rtoc_design_animation_slide = __('slide', 'rich-table-of-content');
	$rtoc_design_animation_none  = __('none', 'rich-table-of-content');

	$rtoc_design_scroll = __('Smooth Scroll', 'rich-table-of-content');

	// Addon（RTOC ver1.2〜）.
	$addon_active = is_plugin_active('rich-table-of-content-addon/rtoc-addon.php');
	if ($addon_active === true) {
		$rtoc_design_title_icon       = __('Icon added to the title', 'rich-table-of-content');
		$rtoc_design_icon_location    = __('Location of the icon', 'rich-table-of-content');
		$rtoc_design_title_icon_above = __('Above the title', 'rich-table-of-content');
		$rtoc_design_title_icon_left  = __('To the left of the title', 'rich-table-of-content');
		$rtoc_design_h2_timeline      = __('Timeline the H2 list', 'rich-table-of-content');
		$rtoc_design_h3_timeline      = __('Timeline the H3 list', 'rich-table-of-content');
	}

	add_settings_field(
		'rtoc_title_display',
		$rtoc_design_title,
		'rtoc_title_display_callback',
		'rtoc_design_setting',
		'rtoc_design_section',
		array(
			'options' => array(
				'left'   => $rtoc_design_title_left,
				'center' => $rtoc_design_title_center
			)
		)
	);
	if ($addon_active === true) {
		// Addon「タイトルにアイコン追加」.
		add_settings_field(
			'rtoc_title_icon_add',
			$rtoc_design_title_icon,
			'rtoc_title_icon_add_callback',
			'rtoc_design_setting',
			'rtoc_design_section'
		);
		// Addon「アイコンの位置」.
		add_settings_field(
			'rtoc_title_icon_location',
			$rtoc_design_icon_location,
			'rtoc_title_icon_location_callback',
			'rtoc_design_setting',
			'rtoc_design_section',
			array(
				'options' => array(
					'above' => $rtoc_design_title_icon_above,
					'left'  => $rtoc_design_title_icon_left,
				),
			)
		);
	}
	add_settings_field(
		'rtoc_list_h2_type',
		$rtoc_design_h2,
		'rtoc_list_h2_type_callback',
		'rtoc_design_setting',
		'rtoc_design_section',
		array(
			'options' => array(
				'ul'   => '<ul class="c-list-style"><li><span class="c-admin-round">H2 TITLE</span></li><li>' . $rtoc_design_round . '</li></ul>',
				'ol'   => '<ul class="c-list-style"><li><span class="c-admin-number">1.</span>H2 TITLE</li><li>' . $rtoc_design_number1 . '</li></ul>',
				'ol2'  => '<ul class="c-list-style"><li><span class="c-admin-decimal">01</span>H2 TITLE</li><li>' . $rtoc_design_number2 . '</li></ul>',
				'none' => '<ul class="c-list-style"><li>H2 TITLE</li><li>' . $rtoc_design_none . '</li></ul>'
			)
		)
	);
	if ($addon_active === true) {
		// Addon「H2リストをタイムライン化」.
		add_settings_field(
			'rtoc_h2_timeline',
			$rtoc_design_h2_timeline,
			'rtoc_h2_timeline_callback',
			'rtoc_design_setting',
			'rtoc_design_section',
			array(
				'options' => array(
					'on'  => 'ON',
					'off' => 'OFF',
				),
			)
		);
	}
	add_settings_field(
		'rtoc_list_h3_type',
		$rtoc_design_h3,
		'rtoc_list_h3_type_callback',
		'rtoc_design_setting',
		'rtoc_design_section',
		array(
			'options' => array(
				'ul'   => '<ul class="c-list-style"><li><span class="c-admin-round">H3 TITLE</span></li><li>' . $rtoc_design_round . '</li></ul>',
				'ol'   => '<ul class="c-list-style"><li><span class="c-admin-number">1.</span>H3 TITLE</li><li>' . $rtoc_design_number1 . '</li></ul>',
				'ol2'  => '<ul class="c-list-style"><li><span class="c-admin-decimal">01</span>H3 TITLE</li><li>' . $rtoc_design_number2 . '</li></ul>',
				'none' => '<ul class="c-list-style"><li>H3 TITLE</li><li>' . $rtoc_design_none . '</li></ul>'
			)
		)
	);
	if ($addon_active === true) {
		// Addon「H3リストをタイムライン化」.
		add_settings_field(
			'rtoc_h3_timeline',
			$rtoc_design_h3_timeline,
			'rtoc_h3_timeline_callback',
			'rtoc_design_setting',
			'rtoc_design_section',
			array(
				'options' => array(
					'on'  => 'ON',
					'off' => 'OFF',
				),
			)
		);
	}
	if ($addon_active === false) {
		add_settings_field(
			'rtoc_frame_design',
			$rtoc_design_frame,
			'rtoc_frame_design_callback',
			'rtoc_design_setting',
			'rtoc_design_section',
			array(
				'options' => array(
					'frame1' => 'デザイン１',
					'frame2' => 'デザイン２',
					'frame3' => 'デザイン３',
					'frame4' => 'デザイン４',
					'frame5' => 'デザイン５',
				),
			)
		);
		// Addon有効化時はフレーム8まで.
	} else {
		add_settings_field(
			'rtoc_frame_design',
			$rtoc_design_frame,
			'rtoc_frame_design_callback',
			'rtoc_design_setting',
			'rtoc_design_section',
			array(
				'options' => array(
					'frame1' => 'デザイン１',
					'frame2' => 'デザイン２',
					'frame3' => 'デザイン３',
					'frame4' => 'デザイン４',
					'frame5' => 'デザイン５',
					'frame6' => 'デザイン６',
					'frame7' => 'デザイン７',
					'frame8' => 'デザイン８',
				),
			)
		);
	}
	if ($addon_active === true) {
		// Addon「アクセントポイントを表示」.
		add_settings_field(
			'rtoc_accent_point',
			$rtoc_accent_point,
			'rtoc_accent_point_callback',
			'rtoc_design_setting',
			'rtoc_design_section',
			array(
				'options' => array(
					'on' => 'ON',
					'off'  => 'OFF',
				),
			)
		);
		// Addon「アクセントポイントのテキスト」.
		add_settings_field(
			'rtoc_accent_point_text',
			$rtoc_accent_point_text,
			'rtoc_accent_point_text_callback',
			'rtoc_design_setting',
			'rtoc_design_section'
		);
	}

	add_settings_field(
		'rtoc_animation',
		$rtoc_design_animation,
		'rtoc_animation_callback',
		'rtoc_design_setting',
		'rtoc_design_section',
		array(
			'options' => array(
				'animation-fade'  => $rtoc_design_animation_fade,
				'animation-slide' => $rtoc_design_animation_slide,
				'animation-none'  => $rtoc_design_animation_none
			)
		)
	);
	add_settings_field(
		'rtoc_scroll_animation',
		$rtoc_design_scroll,
		'rtoc_scroll_animation_callback',
		'rtoc_design_setting',
		'rtoc_design_section',
		array(
			'options' => array(
				'on'  => 'ON',
				'off' => 'OFF'
			)
		)
	);
	register_setting('rtoc_config', 'rtoc_title_display');
	register_setting('rtoc_config', 'rtoc_list_h2_type');
	register_setting('rtoc_config', 'rtoc_list_h3_type');
	register_setting('rtoc_config', 'rtoc_frame_design');
	register_setting('rtoc_config', 'rtoc_animation');
	register_setting('rtoc_config', 'rtoc_scroll_animation');

	if ($addon_active === true) {
		register_setting('rtoc_config', 'rtoc_title_icon_add');
		register_setting('rtoc_config', 'rtoc_title_icon_location');
		register_setting('rtoc_config', 'rtoc_h2_timeline');
		register_setting('rtoc_config', 'rtoc_h3_timeline');
	}
}
add_action('admin_init', 'rtoc_design_setting_field', 15);


// 応用設定
function rtoc_senior_settings_init()
{
	$rtoc_advanced = __('Advanced Settings', 'rich-table-of-content');
	add_settings_section(
		'rtoc_senior_section',
		$rtoc_advanced,
		'rtoc_senior_function_callback',
		'rtoc_senior_setting'
	);
}
add_action('admin_init', 'rtoc_senior_settings_init');

function rtoc_senior_function_callback()
{
	$rtoc_advanced_txt = __('If you want to do advanced customization such as the button to return to the table of contents and the exclusion of plugin CSS, please set here.', 'rich-table-of-content');
	echo '<p>' . $rtoc_advanced_txt . '</p>';
}

function rtoc_senior_setting_field()
{
	$rtoc_advanced_back                = __('Back to Contents button', 'rich-table-of-content');
	$rtoc_advanced_toc_pc             = __('Display the Back to Table of Contents button on the PC', 'rich-table-of-content');
	$rtoc_advanced_display_top         = __('Don\'t display the table of contents on the TOP page', 'rich-table-of-content');
	$rtoc_advanced_back_position       = __('Position of Back to Contents button', 'rich-table-of-content');
	$rtoc_back_text                    = __('Back to Table of Contents button text', 'rich-table-of-content');
	$rtoc_advanced_back_position_left  = __('Left-aligned', 'rich-table-of-content');
	$rtoc_advanced_back_position_right = __('Right-aligned', 'rich-table-of-content');

	$rtoc_advanced_vertical_position     = __('Vertical setting', 'rich-table-of-content');
	$rtoc_advanced_post_exclude          = __('Post ID to exclude', 'rich-table-of-content');
	$rtoc_advanced_page_exclude          = __('Page ID to exclude', 'rich-table-of-content');
	$rtoc_advanced_initial_display       = __('Table of Contents default display settings', 'rich-table-of-content');
	$rtoc_advanced_initial_display_open  = __('Display from the beginning', 'rich-table-of-content');
	$rtoc_advanced_initial_display_close = __('Keep closed', 'rich-table-of-content');

	$rtoc_advanced_open_text  = __('Open text of the open/close button', 'rich-table-of-content');
	$rtoc_advanced_close_text = __('Close text of the open/close button', 'rich-table-of-content');

	$rtoc_advanced_css = __('Don’t load plugin CSS', 'rich-table-of-content');
	$rtoc_hide_openclose = __('Hide open/close buttons', 'rich-table-of-content');

	$rtoc_userate_measure_7 = __('Measure table of contents usage for 7 days', 'rich-table-of-content'); // '7日間の目次使用率を測定'

	add_settings_field(
		'rtoc_back_toc_button',
		$rtoc_advanced_back,
		'rtoc_back_toc_button_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section',
		array(
			'options' => array(
				'on'  => 'ON',
				'off' => 'OFF'
			)
		)
	);
	add_settings_field(
		'rtoc_back_toc_pc',
		$rtoc_advanced_toc_pc,
		'rtoc_back_toc_pc_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_display_top',
		$rtoc_advanced_display_top,
		'rtoc_display_top_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_back_button_position',
		$rtoc_advanced_back_position,
		'rtoc_back_button_position_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section',
		array(
			'options' => array(
				'left'  => $rtoc_advanced_back_position_left,
				'right' => $rtoc_advanced_back_position_right
			)
		)
	);
	add_settings_field(
		'rtoc_back_text',
		$rtoc_back_text,
		'rtoc_back_text_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_back_button_vertical_position',
		$rtoc_advanced_vertical_position,
		'rtoc_back_button_vertical_position_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_exclude_post_toc',
		$rtoc_advanced_post_exclude,
		'rtoc_exclude_post_toc_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_exclude_page_toc',
		$rtoc_advanced_page_exclude,
		'rtoc_exclude_page_toc_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_initial_display',
		$rtoc_advanced_initial_display,
		'rtoc_initial_display_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section',
		array(
			'options' => array(
				'open'  => $rtoc_advanced_initial_display_open,
				'close' => $rtoc_advanced_initial_display_close,
			)
		)
	);
	add_settings_field(
		'rtoc_open_text',
		$rtoc_advanced_open_text,
		'rtoc_open_text_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_close_text',
		$rtoc_advanced_close_text,
		'rtoc_close_text_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_exclude_openclose',
		$rtoc_hide_openclose,
		'rtoc_exclude_openclose_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	add_settings_field(
		'rtoc_exclude_css',
		$rtoc_advanced_css,
		'rtoc_exclude_css_callback',
		'rtoc_senior_setting',
		'rtoc_senior_section'
	);
	// RTOC ver1.2〜で, Addonが 無効 or ｲﾝｽﾄｰﾙなし の時.
	if (!is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) {
		add_settings_field(
			'rtoc_userate_measure_7',
			$rtoc_userate_measure_7,
			'rtoc_userate_measure_7_callback',
			'rtoc_senior_setting',
			'rtoc_senior_section',
			array(
				'options' => array(
					'on'  => 'ON',
					'off' => 'OFF',
				)
			)
		);
	}
	register_setting('rtoc_config', 'rtoc_back_toc_button');
	register_setting('rtoc_config', 'rtoc_back_toc_pc');
	register_setting('rtoc_config', 'rtoc_display_top');
	register_setting('rtoc_config', 'rtoc_back_button_position');
	register_setting('rtoc_config', 'rtoc_back_text');
	register_setting('rtoc_config', 'rtoc_back_button_vertical_position');
	register_setting('rtoc_config', 'rtoc_exclude_post_toc');
	register_setting('rtoc_config', 'rtoc_exclude_page_toc');
	register_setting('rtoc_config', 'rtoc_back_toc_button');
	register_setting('rtoc_config', 'rtoc_initial_display');
	register_setting('rtoc_config', 'rtoc_open_text');
	register_setting('rtoc_config', 'rtoc_close_text');
	register_setting('rtoc_config', 'rtoc_exclude_openclose');
	register_setting('rtoc_config', 'rtoc_exclude_css');
	register_setting('rtoc_config', 'rtoc_userate_measure_7');
}
add_action('admin_init', 'rtoc_senior_setting_field', 15);

// プリセット設定
$my_theme    = wp_get_theme();
$theme_name  = $my_theme->get('Name');
$theme_color = get_theme_mod('theme_color');
if ($theme_name == 'JIN' || $theme_name == 'jin-child') {

	function rtoc_preset_settings_init()
	{
		$rtoc_preset = __('Preset Color', 'rich-table-of-content');
		add_settings_section(
			'rtoc_preset_section',
			$rtoc_preset,
			'rtoc_preset_function_callback',
			'rtoc_preset_setting'
		);
	}
	add_action('admin_init', 'rtoc_preset_settings_init');

	function rtoc_preset_function_callback()
	{
		$rtoc_preset_txt = __('RTOC default design preset. Choose and set the preset that suits your site.', 'rich-table-of-content');
		echo '<p>' . $rtoc_preset_txt . '</p>';
	}

	function rtoc_preset_setting_field()
	{
		$rtoc_preset_color = __('Classic color', 'rich-table-of-content');

		$rtoc_preset_color_jin      = __('JIN color', 'rich-table-of-content');
		$rtoc_preset_color_sunny    = __('Sunny', 'rich-table-of-content');
		$rtoc_preset_color_dark     = __('Dark', 'rich-table-of-content');
		$rtoc_preset_color_feminine = __('Feminine', 'rich-table-of-content');
		$rtoc_preset_color_aqua     = __('Aquamarine', 'rich-table-of-content');
		$rtoc_preset_color_smart    = __('Smart color', 'rich-table-of-content');
		$rtoc_preset_color_citrus   = __('Citrus color', 'rich-table-of-content');
		add_settings_field(
			'rtoc_color',
			$rtoc_preset_color,
			'rtoc_color_callback',
			'rtoc_preset_setting',
			'rtoc_preset_section',
			array(
				'options' => array(
					'preset1' => 'JIN Color',
					'preset2' => $rtoc_preset_color_sunny,
					'preset3' => $rtoc_preset_color_dark,
					'preset4' => $rtoc_preset_color_feminine,
					'preset5' => $rtoc_preset_color_aqua,
					'preset6' => $rtoc_preset_color_smart,
					'preset7' => $rtoc_preset_color_citrus
				)
			)
		);
		register_setting('rtoc_config', 'rtoc_color');
	}
	add_action('admin_init', 'rtoc_preset_setting_field', 15);
} elseif ($theme_name == 'JIN:R' || $theme_name == 'JIN:R child') {

	function rtoc_preset_settings_init()
	{
		$rtoc_preset = __('Preset Color', 'rich-table-of-content');
		add_settings_section(
			'rtoc_preset_section',
			$rtoc_preset,
			'rtoc_preset_function_callback',
			'rtoc_preset_setting'
		);
	}
	add_action('admin_init', 'rtoc_preset_settings_init');

	function rtoc_preset_function_callback()
	{
		$rtoc_preset_txt = __('RTOC default design preset. Choose and set the preset that suits your site.', 'rich-table-of-content');
		echo '<p>' . $rtoc_preset_txt . '</p>';
	}

	function rtoc_preset_setting_field()
	{
		$rtoc_preset_color = __('Classic color', 'rich-table-of-content');

		$rtoc_preset_color_jinr      = __('JIN:R color', 'rich-table-of-content');
		$rtoc_preset_color_sunny    = __('Sunny', 'rich-table-of-content');
		$rtoc_preset_color_dark     = __('Dark', 'rich-table-of-content');
		$rtoc_preset_color_feminine = __('Feminine', 'rich-table-of-content');
		$rtoc_preset_color_aqua     = __('Aquamarine', 'rich-table-of-content');
		$rtoc_preset_color_smart    = __('Smart color', 'rich-table-of-content');
		$rtoc_preset_color_citrus   = __('Citrus color', 'rich-table-of-content');
		add_settings_field(
			'rtoc_color',
			$rtoc_preset_color,
			'rtoc_color_callback',
			'rtoc_preset_setting',
			'rtoc_preset_section',
			array(
				'options' => array(
					'preset1' => $rtoc_preset_color_jinr,
					'preset2' => $rtoc_preset_color_sunny,
					'preset3' => $rtoc_preset_color_dark,
					'preset4' => $rtoc_preset_color_feminine,
					'preset5' => $rtoc_preset_color_aqua,
					'preset6' => $rtoc_preset_color_smart,
					'preset7' => $rtoc_preset_color_citrus
				)
			)
		);
		register_setting('rtoc_config', 'rtoc_color');
	}
	add_action('admin_init', 'rtoc_preset_setting_field', 15);
} else {

	function rtoc_preset_settings_init()
	{
		$rtoc_preset = __('Preset Color', 'rich-table-of-content');
		add_settings_section(
			'rtoc_preset_section',
			$rtoc_preset,
			'rtoc_preset_function_callback',
			'rtoc_preset_setting'
		);
	}
	add_action('admin_init', 'rtoc_preset_settings_init');

	function rtoc_preset_function_callback()
	{
		$rtoc_preset_txt = __('RTOC default design preset. Choose and set the preset that suits your site.', 'rich-table-of-content');
		echo '<p>' . $rtoc_preset_txt . '</p>';
	}

	function rtoc_preset_setting_field()
	{
		$rtoc_preset_color = __('Classic color', 'rich-table-of-content');

		$rtoc_preset_color_sunny    = __('Sunny', 'rich-table-of-content');
		$rtoc_preset_color_dark     = __('Dark', 'rich-table-of-content');
		$rtoc_preset_color_feminine = __('Feminine', 'rich-table-of-content');
		$rtoc_preset_color_aqua     = __('Aquamarine', 'rich-table-of-content');
		$rtoc_preset_color_smart    = __('Smart color', 'rich-table-of-content');
		$rtoc_preset_color_citrus   = __('Citrus color', 'rich-table-of-content');
		add_settings_field(
			'rtoc_color',
			$rtoc_preset_color,
			'rtoc_color_callback',
			'rtoc_preset_setting',
			'rtoc_preset_section',
			array(
				'options' => array(
					'preset1' => $rtoc_preset_color_sunny,
					'preset2' => $rtoc_preset_color_dark,
					'preset3' => $rtoc_preset_color_feminine,
					'preset4' => $rtoc_preset_color_aqua,
					'preset5' => $rtoc_preset_color_smart,
					'preset6' => $rtoc_preset_color_citrus
				)
			)
		);
		register_setting('rtoc_config', 'rtoc_color');
	}
	add_action('admin_init', 'rtoc_preset_setting_field', 15);
}

// 各設定のコールバック（ここで出力します。）
function rtoc_title_callback()
{
	$option = get_option('rtoc_title');
	if ($option == '') {
		update_option('rtoc_title', 'Contents');
		$option = get_option('rtoc_title');
	}
	printf(
		'<input type="text" placeholder="CONTENTS" id="rtoc_title" name="rtoc_title" value="%s" class="rtoc_admin_text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_title_display_callback($args)
{
	$option_name = 'rtoc_title_display';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'left');
		$option = get_option($option_name);
	}
	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" class="rtoc_admin_radio" id="%1$s_%2$s" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"> %3$s</label>', $option_name, $val, $title);
	}
}
function rtoc_display_callback($args)
{
	$option_name = 'rtoc_display';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, array('post' => 'post', 'page' => 'page', 'category' => 'category'));
		$option = get_option($option_name);
	}
	$html = '';
	foreach ($args['options'] as $val => $title) {
		if (isset($option) && is_array($option)) {
			$checked = in_array($val, $option, true) ? 'checked="checked"' : '';
			$html .= sprintf('<input type="checkbox" class="rtoc_admin_check" id="%1$s[%2$s]" name="%1$s[%2$s]" value="%2$s" %3$s />', $option_name, $val, $checked);
		}
		$html .= sprintf('<label for="%1$s[%2$s]"> %3$s</label><br />', $option_name, $val, $title);
	}
	echo $html;
}
// カテゴリーのチェックボックスが一度だけ追加されるようにする
function rtoc_check_category_added() {
    $option_name = 'rtoc_display';
    $category_added = get_option('rtoc_category_added');
    if ($category_added === false) {
        $option = get_option($option_name);
        if ($option == '') {
            update_option($option_name, array('post' => 'post', 'page' => 'page', 'category' => 'category'));
        } else {
            if (!in_array('category', $option, true)) {
                $option['category'] = 'category';
                update_option($option_name, $option);
            }
        }
        update_option('rtoc_category_added', 'yes');
    }
}
add_action('admin_init', 'rtoc_check_category_added');
function rtoc_exclude_post_toc_callback()
{
	$option = get_option('rtoc_exclude_post_toc');
	printf(
		'<input type="text" placeholder="3,28,551" id="rtoc_exclude_post_toc" name="rtoc_exclude_post_toc" class="rtoc_admin_text" value="%s" class="rtoc-text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_exclude_page_toc_callback()
{
	$option = get_option('rtoc_exclude_page_toc');
	printf(
		'<input type="text" placeholder="4,29,552" id="rtoc_exclude_page_toc" class="rtoc_admin_text" name="rtoc_exclude_page_toc" value="%s" class="rtoc-text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_initial_display_callback($args)
{
	$option_name = 'rtoc_initial_display';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'open');
		$option = get_option($option_name);
	}
	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s_%2$s" class="rtoc_admin_radio" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"> %3$s</label>', $option_name, $val, $title);
	}
}
function rtoc_open_text_callback()
{
	$option = get_option('rtoc_open_text');
	if ($option == '') {
		update_option('rtoc_open_text', 'OPEN');
		$option = get_option('rtoc_open_text');
	}
	printf(
		'<input type="text" placeholder="OPEN" id="rtoc_open_text" name="rtoc_open_text" value="%s" class="rtoc_admin_text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_close_text_callback()
{
	$option = get_option('rtoc_close_text');
	if ($option == '') {
		update_option('rtoc_close_text', 'CLOSE');
		$option = get_option('rtoc_close_text');
	}
	printf(
		'<input type="text" placeholder="CLOSE" id="rtoc_close_text" name="rtoc_close_text" value="%s" class="rtoc_admin_text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_headline_display_callback($args)
{
	$option_name = 'rtoc_headline_display';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'h3');
		$option = get_option($option_name);
	}
	print '<select name="rtoc_headline_display" id="rtoc_headline_display" class="rtoc_admin_select">';

	foreach ($args['options'] as $val => $title)
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			$val,
			selected($val, $option, false),
			$title
		);

	print '</select>';
}
function rtoc_font_callback($args)
{
	$option_name = 'rtoc_font';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'default');
		$option = get_option($option_name);
	}
	print '<select name="rtoc_font" id="rtoc_font" class="rtoc_admin_font">';

	foreach ($args['options'] as $val => $title)
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			$val,
			selected($val, $option, false),
			$title
		);
	print '</select>';
}
function rtoc_display_headline_amount_callback($args)
{
	$option_name = 'rtoc_display_headline_amount';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, '4');
		$option = get_option($option_name);
	}
	print '' . _e('Display headings from', 'rich-table-of-content') . ' <select name="rtoc_display_headline_amount" id="rtoc_display_headline_amount">';

	foreach ($args['options'] as $val => $title)
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			$val,
			selected($val, $option, false),
			$title
		);

	print '</select>';
}

function rtoc_list_h2_type_callback($args)
{
	$option_name = 'rtoc_list_h2_type';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'ol2');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s_%2$s" class="rtoc_admin_list" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"> %3$s</label>', $option_name, $val, $title);
	}
}
function rtoc_list_h3_type_callback($args)
{
	$option_name = 'rtoc_list_h3_type';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'ul');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s_%2$s" class="rtoc_admin_list" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"> %3$s</label>', $option_name, $val, $title);
	}
}
function rtoc_color_callback($args)
{
	$option_name = 'rtoc_color';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'preset1');
		$option = get_option($option_name);
	}
	global $theme_name;
	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s_%2$s" class="rtoc_admin_radio visual visual_preset" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		if ($theme_name == 'JIN' || $theme_name == 'jin-child') {
			$format = '<label for="%1$s_%2$s"><div class="preset_bg visual-%2$s"><img src="' . plugins_url('../img/jin/%2$s.png', __FILE__) . '" alt="RTOCのプリセットカラー"><span>' . $title . '</span></div></label>';
		} elseif ($theme_name == 'JIN:R' || $theme_name == 'JIN:R child') {
			
			$format = '<label for="%1$s_%2$s"><div class="preset_bg visual-%2$s"><img src="' . plugins_url('../img/jin/%2$s.png', __FILE__) . '" alt="RTOCのプリセットカラー"><span>' . $title . '</span></div></label>';
		}  else {
			$format = '<label for="%1$s_%2$s"><div class="preset_bg visual-%2$s"><img src="' . plugins_url('../img/%2$s.png', __FILE__) . '" alt="RTOCのプリセットカラー"><span>' . $title . '</span></div></label>';
		}
		printf($format, $option_name, $val, $title);
	}
}
function rtoc_frame_design_callback($args)
{
	$option_name = 'rtoc_frame_design';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'frame2');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s_%2$s" class="rtoc_admin_radio visual %1$s-%2$s" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"><div class="visual_frame"></div></label>', $option_name, $val, $title);
	}
}
function rtoc_animation_callback($args)
{
	$option_name = 'rtoc_animation';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'animation-fade');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s_%2$s" class="rtoc_admin_radio visual visual_animation" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"><ul><li><div class="animation_box"></li><li> %3$s</li></ul></label>', $option_name, $val, $title);
	}
}

function rtoc_scroll_animation_callback($args)
{
	$option_name = 'rtoc_scroll_animation';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'on');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s[%2$s]" class="rtoc_admin_radio" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s[%2$s]"> %3$s</label>', $option_name, $val, $title);
	}
}
function rtoc_back_toc_button_callback($args)
{
	$option_name = 'rtoc_back_toc_button';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'on');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s[%2$s]" class="rtoc_admin_radio" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s[%2$s]"> %3$s</label>', $option_name, $val, $title);
	}
}
function rtoc_back_toc_pc_callback()
{
	$option = get_option('rtoc_back_toc_pc');

	echo '<input type="checkbox" id="rtoc_back_toc_pc" class="rtoc_admin_check" name="rtoc_back_toc_pc" value="1" ' . checked(1, $option, false) . ' />';
}
function rtoc_display_top_callback()
{
	$option = get_option('rtoc_display_top');
	echo '<input type="checkbox" id="rtoc_display_top" class="rtoc_admin_check" name="rtoc_display_top" value="1" ' . checked(1, $option, false) . ' />';
}

function rtoc_back_text_callback()
{
	$option = get_option('rtoc_back_text');
	if ($option == '') {
		update_option('rtoc_back_text', __('TOC', 'rich-table-of-content'));
		$option = get_option('rtoc_back_text');
	}
	printf(
		'<input type="text" placeholder="TOC" id="rtoc_back_text" name="rtoc_back_text" value="%s" class="rtoc_admin_text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_back_button_position_callback($args)
{
	$option_name = 'rtoc_back_button_position';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'left');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" class="rtoc_admin_radio" id="%1$s_%2$s" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s_%2$s"> %3$s</label>', $option_name, $val, $title);
	}
}

function rtoc_back_button_vertical_position_callback()
{
	$option = get_option('rtoc_back_button_vertical_position');
	printf(
		'' . _e('Shift "Back button to table of contents" up and down', 'rich-table-of-content') . '<input type="text" id="rtoc_back_button_vertical_position" name="rtoc_back_button_vertical_position" placeholder="30" value="%s" class="rtoc_vertical_position"/>',
		isset($option) ? esc_attr($option) : ''
	);
}
function rtoc_exclude_openclose_callback()
{
	$option = get_option('rtoc_exclude_openclose');

	echo '<input type="checkbox" id="rtoc_exclude_openclose" class="rtoc_admin_check" name="rtoc_exclude_openclose" value="1" ' . checked(1, $option, false) . ' />';
}
function rtoc_exclude_css_callback()
{
	$option = get_option('rtoc_exclude_css');

	echo '<input type="checkbox" id="rtoc_exclude_css" class="rtoc_admin_check" name="rtoc_exclude_css" value="1" ' . checked(1, $option, false) . ' />';
}
// カラーピッカー
function rtoc_color_picker($name, $value, $label, $class)
{ ?>
	<ul class="rtoc_colorpicker-<?php echo $class; ?>">
		<li>
			<label for="<?php echo $name; ?>">
				<?php echo $label; ?>
			</label>
		</li>
		<li>
			<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
		</li>
	</ul>
<?php wp_enqueue_script('wp-color-picker');
	$data = '(function( $ ) {
		var options = {
			defaultColor: false,
			change: function(event, ui){},
			clear: function() {},
			hide: true,
			palettes: true
		};
		$("input:text[name=' . $name . ']").wpColorPicker(options);
	})( jQuery );';
	wp_add_inline_script('wp-color-picker', $data, 'after');
}
function rtoc_userate_measure_7_callback($args)
{
	$option_name = 'rtoc_userate_measure_7';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'off');
		$option = get_option($option_name);
	}

	foreach ($args['options'] as $val => $title) {
		printf(
			'<input type="radio" id="%1$s[%2$s]" class="rtoc_admin_radio" name="%1$s" value="%2$s" %3$s />',
			$option_name,
			$val,
			checked($val, $option, false)
		);
		printf('<label for="%1$s[%2$s]"> %3$s</label>', $option_name, $val, $title);
	}
}

// Addon（RTOC ver1.2〜）.
/**
 * 「RTOC設定 > デザイン設定」項目（radio）
 *
 * @param array $args         - add_settings_fieldで設定している配列.
 * @param string $option_name - wp_options option_name.
 * @param string $option      - 現在のoption値.
 */
function rtoc_field_callback_radio($args, $option_name, $option)
{
	foreach ($args['options'] as $val => $label) {
		printf(
			'<input type="radio" id="%1$s-%2$s" class="rtoc_admin_radio" name="%1$s" value="%2$s" %3$s >',
			$option_name,
			esc_attr($val),
			checked(esc_attr($val), esc_attr($option), false)
		);
		printf(
			'<label for="%1$s-%2$s">%3$s</label>',
			$option_name,
			esc_attr($val),
			esc_attr($label)
		);
	}
}
// Addon「タイトルにアイコン追加」- callback.
function rtoc_title_icon_add_callback()
{
	$option = get_option('rtoc_title_icon_add');
	if ($option == '') {
		update_option('rtoc_title_icon_add', '');
		$option = get_option('rtoc_title_icon_add');
	}
	printf(
		'<input type="text" placeholder=" jic jin-ifont-home" id="rtoc_title_icon_add" name="rtoc_title_icon_add" value="%s" class="rtoc_admin_text" />',
		isset($option) ? esc_attr($option) : ''
	);
}
// Addon「アイコンの位置」- callback.
function rtoc_title_icon_location_callback($args)
{
	$option_name = 'rtoc_title_icon_location';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'above');
		$option = get_option($option_name);
	}
	rtoc_field_callback_radio($args, $option_name, $option);
}
// Addon「H2リストをタイムライン化」- callback.
function rtoc_h2_timeline_callback($args)
{
	$option_name = 'rtoc_h2_timeline';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'off');
		$option = get_option($option_name);
	}
	rtoc_field_callback_radio($args, $option_name, $option);
}
// Addon「H3リストをタイムライン化」- callback.
function rtoc_h3_timeline_callback($args)
{
	$option_name = 'rtoc_h3_timeline';
	$option = get_option($option_name);
	if ($option == '') {
		update_option($option_name, 'off');
		$option = get_option($option_name);
	}
	rtoc_field_callback_radio($args, $option_name, $option);
}


function rtoc_plugin_activated()
{
	// 各オプション値を取得
	if (!get_option('rtoc_title')) {
		update_option('rtoc_title', true);
	}
	if (!get_option('rtoc_title_display')) {
		update_option('rtoc_title_display', true);
	}
	if (!get_option('rtoc_display')) {
		update_option('rtoc_display', true);
	}
	if (!get_option('rtoc_exclude_post_toc')) {
		update_option('rtoc_exclude_post_toc', true);
	}
	if (!get_option('rtoc_exclude_page_toc')) {
		update_option('rtoc_exclude_page_toc', true);
	}
	if (!get_option('rtoc_initial_display')) {
		update_option('rtoc_initial_display', true);
	}
	if (!get_option('rtoc_open_text')) {
		update_option('rtoc_open_text', true);
	}
	if (!get_option('rtoc_close_text')) {
		update_option('rtoc_close_text', true);
	}
	if (!get_option('rtoc_headline_display')) {
		update_option('rtoc_headline_display', true);
	}
	if (!get_option('rtoc_font')) {
		update_option('rtoc_font', true);
	}
	if (!get_option('rtoc_display_headline_amount')) {
		update_option('rtoc_display_headline_amount', true);
	}
	if (!get_option('rtoc_list_h2_type')) {
		update_option('rtoc_list_h2_type', true);
	}
	if (!get_option('rtoc_list_h3_type')) {
		update_option('rtoc_list_h3_type', true);
	}
	if (!get_option('rtoc_color')) {
		update_option('rtoc_color', true);
	}
	if (!get_option('rtoc_frame_design')) {
		update_option('rtoc_frame_design', true);
	}
	if (!get_option('rtoc_animation')) {
		update_option('rtoc_animation', true);
	}
	if (get_option('rtoc_scroll_animation') === false) {
		update_option('rtoc_scroll_animation', 'on');
	}
	if (!get_option('rtoc_back_toc_button')) {
		update_option('rtoc_back_toc_button', true);
	}
	if (!get_option('rtoc_display_top')) {
		update_option('rtoc_display_top', 1);
	}
	if (!get_option('rtoc_back_text')) {
		update_option('rtoc_back_text', true);
	}
	if (!get_option('rtoc_back_button_position')) {
		update_option('rtoc_back_button_position', true);
	}
	if (!get_option('rtoc_back_button_vertical_position')) {
		update_option('rtoc_back_button_vertical_position', true);
	}
	if (!get_option('rtoc_exclude_css')) {
		update_option('rtoc_exclude_css', true);
	}
	if (!get_option('rtoc_userate_measure_7')) {
		update_option('rtoc_userate_measure_7', true);
	}
	// Addon（RTOC ver1.2〜）.
	if (is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) {
		if (!get_option('rtoc_title_icon_add')) {
			update_option('rtoc_title_icon_add', true);
		}
		if (!get_option('rtoc_title_icon_location')) {
			update_option('rtoc_title_icon_location', true);
		}
		if (!get_option('rtoc_h2_timeline')) {
			update_option('rtoc_h2_timeline', true);
		}
		if (!get_option('rtoc_h3_timeline')) {
			update_option('rtoc_h3_timeline', true);
		}
	}
}
register_activation_hook(__FILE__, 'rtoc_plugin_activated');

// 値の保存とサニタイズ
function rtoc_sanitize()
{

	$rtocTitleisset      = isset($_REQUEST['rtoc_title_color']);
	$rtocTextisset       = isset($_REQUEST['rtoc_text_color']);
	$rtocBackisset       = isset($_REQUEST['rtoc_back_color']);
	$rtocBorderisset     = isset($_REQUEST['rtoc_border_color']);
	$rtocH2isset         = isset($_REQUEST['rtoc_h2_color']);
	$rtocH3isset         = isset($_REQUEST['rtoc_h3_color']);
	$rtocBackButtonisset = isset($_REQUEST['rtoc_back_button_color']);

	if ($rtocTitleisset == true) {
		$rtocTitleColor = sanitize_hex_color($_REQUEST['rtoc_title_color']);
		if ($rtocTitleColor == true) {
			update_option('rtoc_title_color', $rtocTitleColor);
		}
	}

	if ($rtocTextisset == true) {
		$rtocTextColor = sanitize_hex_color($_REQUEST['rtoc_text_color']);
		if ($rtocTextColor == true) {
			update_option('rtoc_text_color', $rtocTextColor);
		}
	}

	if ($rtocBackisset == true) {
		$rtocBackColor = sanitize_hex_color($_REQUEST['rtoc_back_color']);
		if ($rtocBackColor == true) {
			update_option('rtoc_back_color', $rtocBackColor);
		}
	}

	if ($rtocBorderisset == true) {
		$rtocBorderColor = sanitize_hex_color($_REQUEST['rtoc_border_color']);
		if ($rtocBorderColor == true) {
			update_option('rtoc_border_color', $rtocBorderColor);
		}
	}
	if ($rtocH2isset == true) {
		$rtocH2Color = sanitize_hex_color($_REQUEST['rtoc_h2_color']);
		if ($rtocH2Color == true) {
			update_option('rtoc_h2_color', $rtocH2Color);
		}
	}
	if ($rtocH3isset == true) {
		$rtocH3Color = sanitize_hex_color($_REQUEST['rtoc_h3_color']);
		if ($rtocH3Color == true) {
			update_option('rtoc_h3_color', $rtocH3Color);
		}
	}
	if ($rtocBackButtonisset == true) {
		$rtocBackButtonColor = sanitize_hex_color($_REQUEST['rtoc_back_button_color']);
		if ($rtocBackButtonColor == true) {
			update_option('rtoc_back_button_color', $rtocBackButtonColor);
		}
	}
}
rtoc_sanitize();

// 各種オプション設定項目のマークアップ (options setting screen markup)
function rtoc_setting_screen_contents()
{
?>
	<link href=“https://fonts.googleapis.com/css?family=Montserrat&display=swap” rel=“stylesheet”>
	<h1 class="rtoc_main_title"><span><?php echo _e('Rich Table of Contents', 'rich-table-of-content'); ?></span></h1>
	<div id="rtoc-screen-wrapper">
		<div class="rtoc_ad_contents">
			<ul>
				<li>
					<a href="https://jinr.jp/" class="rtoc_ad_link" target="_blank" rel="noopener noreferrer">
						<p class="rtoc_ad_ttl">このプラグインと同じ開発チームで作っています</p>
						<img src="<?php echo (plugins_url('../img/jinr-banner.png', __FILE__)) ?>" alt="WordPressテーマ「JIN:R」" class="rtoc_ad_img">
					</a>
				</li>
			</ul>
		</div>
		<input id="rtoc_settings" type="radio" name="tab_item" checked>
		<label class="tab_item" for="rtoc_settings"><?php echo _e('Settings', 'rich-table-of-content'); ?></label>

		<input id="rtoc_using" type="radio" name="tab_item">
		<label class="tab_item" for="rtoc_using"><?php echo _e('Shortcode', 'rich-table-of-content'); ?></label>

		<input id="rtoc_help" type="radio" name="tab_item">
		<label class="tab_item" for="rtoc_help"><?php echo _e('Help', 'rich-table-of-content'); ?></label>

		<div id="rtoc_first" class="rtoc_admin">
			<form method="post" action="options.php">
				<div id="rtoc-config-area">
					<div id="rtoc_settings">
						<div class="rtoc_admin_wrapper rtoc_admin_blue rtoc_value_text">
							<?php do_settings_sections('rtoc_basic_setting'); ?>
							<?php settings_fields('rtoc_config'); ?>
						</div>
						<div class="rtoc_admin_wrapper rtoc_admin_green">
							<?php do_settings_sections('rtoc_design_setting'); ?>
							<?php settings_fields('rtoc_config'); ?>
						</div>
						<div class="rtoc_admin_wrapper preset_contents rtoc_admin_green">
							<?php do_settings_sections('rtoc_preset_setting'); ?>
							<?php settings_fields('rtoc_config'); ?>
						</div>
						<div class="rtoc_admin_wrapper rtoc_admin_yellow jin-block">
							<h2><?php echo _e('Color Settings（For Advanced User）', 'rich-table-of-content'); ?></h2>
							<ul class="rtoc_admin_color">
								<li>
									<?php
									$rtoc_color_title = __('Title Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_title_color', get_option('rtoc_title_color', '#555555'), $rtoc_color_title, '1');
									?>
								</li>
								<li>
									<?php
									$rtoc_color_text = __('Text Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_text_color', get_option('rtoc_text_color', '#555555'), $rtoc_color_text, '2'); ?>
								</li>
								<li>
									<?php
									$rtoc_color_bg = __('Background Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_back_color', get_option('rtoc_back_color', '#ffffff'), $rtoc_color_bg, '3'); ?>
								</li>
								<li>
									<?php
									$rtoc_color_border = __('Border Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_border_color', get_option('rtoc_border_color', '#3f9cff'), $rtoc_color_border, '4'); ?>
								</li>
								<li>
									<?php
									$rtoc_color_h2 = __('H2 Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_h2_color', get_option('rtoc_h2_color', '#3f9cff'), $rtoc_color_h2, '5'); ?>
								</li>
								<li>
									<?php
									$rtoc_color_h3 = __('H3 Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_h3_color', get_option('rtoc_h3_color', '#3f9cff'), $rtoc_color_h3, '6'); ?>
								</li>
								<li>
									<?php
									$rtoc_color_button = __('Back to button Color', 'rich-table-of-content');
									rtoc_color_picker('rtoc_back_button_color', get_option('rtoc_back_button_color'), $rtoc_color_button, '7'); ?>
								</li>
							</ul>
							<div class="jin_popup">
								<div class="jin_popup_contents">
									<h3><?php echo _e('This preset color reflects the JIN theme color.', 'rich-table-of-content'); ?></h3>
									<p><?php echo _e('The color settings here reflect the JIN theme color. If you want to use another color, please choose another preset color and customize it.', 'rich-table-of-content'); ?></p>
									<!-- <p class="jin-addon-button"><a href="#"><?php echo _e('Check help', 'rich-table-of-content'); ?></a></p> -->
								</div>
							</div>
						</div>
						<!-- <div class="rtoc_admin_wrapper rtoc_addon_contents rtoc_admin_red">
							<?php do_settings_sections('rtoc_addon_setting'); ?>
							<?php settings_fields('rtoc_config'); ?>
						</div> -->
						<div class="rtoc_admin_wrapper rtoc_admin_yellow">
							<?php do_settings_sections('rtoc_senior_setting'); ?>
							<?php settings_fields('rtoc_config'); ?>
						</div>
						<?php submit_button(); ?>
					</div>
				</div>
				<div id="live-preview-area">
					<h2 class="rtoc-caption">Live Preview</h2>
					<div class="preview-area-wrapper">
						<div class="rtoc-preview-box">
							<div class="rtoc-preview-innder-box">
								<div id="rtoc-preview-inner">
									<div id="rtoc-mokuji-wrapper">
										<div id="rtoc-mokuji-title"><span></span><button type="button" class="rtoc_open_close rtoc_text_close"></button></div>
										<ul class="rtoc-mokuji level-1">
											<li class="rtoc-item"><a href="#" class="rtoc-point"><?php echo _e('Heading level 1-1', 'rich-table-of-content'); ?></a></li>
											<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 1-2', 'rich-table-of-content'); ?></a>
												<ul class="rtoc-mokuji level-2">
													<li class="rtoc-item"><a href="#" class="rtoc-point"><?php echo _e('Heading level 2-1-1', 'rich-table-of-content'); ?></a>
														<ul class="rtoc-mokuji level-3">
															<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 3-1-1', 'rich-table-of-content'); ?></a></li>
															<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 3-1-2', 'rich-table-of-content'); ?></a></li>
														</ul>
													</li>
													<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 2-1-2', 'rich-table-of-content'); ?></a>
														<ul class="rtoc-mokuji level-3">
															<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 3-1-1', 'rich-table-of-content'); ?></a></li>
														</ul>
													</li>
													<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 2-1-3', 'rich-table-of-content'); ?></a></li>
												</ul>
											</li>
											<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 1-3', 'rich-table-of-content'); ?></a>
												<ul class="rtoc-mokuji level-2">
													<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 2-2-1', 'rich-table-of-content'); ?></a>
														<ul class="rtoc-mokuji level-3">
															<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 3-1-1', 'rich-table-of-content'); ?></a></li>
															<li class="rtoc-item"><a href="#"><?php echo _e('Heading level 3-1-2', 'rich-table-of-content'); ?></a></li>
														</ul>
													</li>
												</ul>
											</li>
											<li class="rtoc-item"><a href="#" class="rtoc-point"><?php echo _e('Heading level 1-4', 'rich-table-of-content'); ?></a></li>
										</ul><!-- /.rtoc-mokuji level-1 -->
									</div><!-- /#rtoc-mokuji-wrapper -->
								</div><!-- /#rtoc-preview-inner -->
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="rtoc_secoud" class="rtoc_admin">
			<div id="rtoc_using">
				<div class="rtoc_using_wrapper">
					<div class="rtoc_using_box">
						<h2><?php echo _e('Table of contents shortcode', 'rich-table-of-content'); ?></h2>
						<p>
							<?php echo _e('You can display the table of contents by pasting this code anywhere in the article. If you do not enter a value (such as title = "") and it is blank, the setting on the management screen will be reflected.', 'rich-table-of-content'); ?>
						</p>
						<code class="rtoc_admin_code">
							[rtoc_mokuji title="" title_display="" heading="" list_h2_type="" list_h3_type="" display="" frame_design="" animation=""]
						</code>
					</div>
					<div class="rtoc_using_box">
						<h2><?php echo _e('Shortcode explanation', 'rich-table-of-content'); ?></h2>
						<p><?php echo _e('If you want to make settings different from the settings on the management screen, you can customize it freely by entering the code in the following values.', 'rich-table-of-content'); ?></p>
						<code class="rtoc_admin_code">
							[rtoc_mokuji title="Contents" title_display="left" heading="h3" list_h2_type="round" list_h3_type="number1" display="close" frame_design="frame2" animation="slide"]
						</code>
						<div class="rtoc_using_box">
							<table class="using_table">
								<tr>
									<th><?php echo _e('value', 'rich-table-of-content'); ?></th>
									<th><?php echo _e('code', 'rich-table-of-content'); ?></th>
									<th><?php echo _e('explain', 'rich-table-of-content'); ?></th>
								</tr>
								<tr>
									<td>title=""</td>
									<td><?php echo _e('Any text', 'rich-table-of-content'); ?></td>
									<td><?php echo _e('This item allows you to set the title of the table of contents. If this value is not set, the settings on the management screen will be reflected.', 'rich-table-of-content'); ?></td>
								</tr>
								<tr>
									<td>title_display=""</td>
									<td>left,center</td>
									<td><?php echo _e('Enter "left" to align the title to the left; enter "center" to align the title to the center.', 'rich-table-of-content'); ?></td>
								</tr>
								<tr>
									<td>heading=""</td>
									<td>h2,h3,h4</td>
									<td><?php echo _e('You can set which headings are displayed. For example, if you set h3, the headings h2 to h3 will be displayed in the table of contents.', 'rich-table-of-content'); ?></td>
								</tr>
								<tr>
									<td>list_h2_type=""</td>
									<td>round,number1,number2</td>
									<td>
										<?php echo _e('When you enter "round", ● will be displayed, and when you enter "number1" or "number2", the number will be displayed before the h2 heading.', 'rich-table-of-content'); ?>
									</td>
								</tr>
								<tr>
									<td>list_h3_type=""</td>
									<td>round,number1,number2</td>
									<td>
										<?php echo _e('When you enter "round", ● will be displayed, and when you enter "number1" or "number2", the number will be displayed before the h3 heading.', 'rich-table-of-content'); ?>
									</td>
								</tr>
								<tr>
									<td>display=""</td>
									<td>open,close</td>
									<td>
										<?php echo _e('Entering ”open” displays the table of contents opened, and entering ”close” displays the table of contents closed.', 'rich-table-of-content'); ?>
									</td>
								</tr>
								<tr>
									<td>frame_design=""</td>
									<td>frame1,frame2,frame3,frame4,frame5</td>
									<td>
										<?php echo _e('You can set the frame of the table of contents.', 'rich-table-of-content'); ?>
									</td>
								</tr>
								<tr>
									<td>animation=""</td>
									<td>fade,slide,none</td>
									<td>
										<?php echo _e('You can set an animation to display the table of contents.', 'rich-table-of-content'); ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="rtoc_third" class="rtoc_admin">
			<div class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('No table of contents is displayed.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Check the item of "The page to display the table of contents".', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('Make sure that "Post" and "Page" are checked.', 'rich-table-of-content'); ?>
							</p>
						</div>
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Check that the number of headings has reached the number set in the "Display conditions" item.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('For example, if "Display headings from 4" is set, the table of contents will not be displayed for articles with only 3 headings.', 'rich-table-of-content'); ?>
							</p>
						</div>
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Check if there is an ID in "Post ID to exclude" or "Page ID to exclude" in "Advanced settings".', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('This item corresponds to the settings of "Display condition" and "Page to display table of contents". Enter the article ID only if you do not want to display the table of contents for a special article.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The design is not reflected.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Clear all browser cache.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('You can find out how to delete your browser’s cache by checking ”Browser in use + cache + delete”.', 'rich-table-of-content'); ?>
							</p>
						</div>
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Stop all cache plugins.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('If you have enabled the cache plugin, please stop the plugin once and check if the design is reflected.', 'rich-table-of-content'); ?>
							</p>
						</div>
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Stop the server cache service.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('Depending on your server, a cache service may be provided.Check if you have enabled them and see if you can solve the problem.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('What is smooth scroll?', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('Smooth scrolling refers to the behavior of scrolling within a page.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('When you click on a heading in the table of contents, you can choose whether to automatically scroll the page and scroll to that heading.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The message "This preset color reflects the JIN theme color" is displayed.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('This preset color is for WordPress theme JIN users.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('This setting is displayed only when WordPress theme JIN is activated. When JIN users activate RTOC, they will be able to use the table of contents design with the existing color settings without any special settings.If you want to try a different design than the JIN theme color, change the preset color to another and change the color setting.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The top and bottom margins of the "Return to Table of Contents" button are strange.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('If you do not want to change the height, leave it blank.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('If you enter "0" , the height will be 0, so the display will be strange.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The button to return to the table of contents is not displayed.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('The button to return to the table of contents is displayed only when using a smartphone.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('Please check that it is displayed on your smartphone device.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('Can I add a "back to table of contents link" with a text link in the article?', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('It is possible. Please include "#rtoc-mokuji-wrapper" when inserting the link.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('When creating a link, you can create a link back to the table of contents in the article’s text link by adding the #rtoc-mokuji-wrapper to the URL of the article, such as https://~/#rtoc-mokuji-wrapper.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The back to table of contents button design is corrupted.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('If the Back to Table of Contents buttons text is too long, it will ruin the design.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('If you set a short number of characters such as "TOC" or "to TOC", you will be able to use it without problems.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The open/close button design is corrupted.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('The text on the open and close buttons can be too long or it will ruin the design.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('Please set as few characters as possible, such as "OPEN" and "CLOSE".', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('Want to display a table of contents in the sidebar.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('You can display it by adding a Shortcode to the text widget.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('The table of contents design in the sidebar is unified, so changing the value of the shortcode will not reflect the design.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div id="jin_color_help" class="rtoc_qa_box">
				<div class="rtoc_acc_box">
					<label for="label6">
						<img src="<?php echo (plugins_url('../img/question.png', __FILE__)) ?>">
						<?php echo _e('The highlighting in the sidebar of the table of contents does not work.', 'rich-table-of-content'); ?>
					</label>
					<input type="checkbox" id="label6" class="rtoc_acc_input">
					<div class="rtoc_acc_show">
						<div class="rtoc_acc_show_box">
							<p class="qa_answer">
								<img src="<?php echo (plugins_url('../img/answer.png', __FILE__)) ?>">
								<?php echo _e('If the first letter of the id assigned to the table of contents is a number, it will not work properly.', 'rich-table-of-content'); ?>
							</p>
							<p class="qa_explanation">
								<?php echo _e('In certain versions of WordPress, copying a heading may cause sequential numbers to be assigned. Also, if your id has a number at the beginning, it will not work, so please change it to another id and see if the problem goes away.', 'rich-table-of-content'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }
register_activation_hook(__FILE__, 'rtoc_setting_screen_contents');
