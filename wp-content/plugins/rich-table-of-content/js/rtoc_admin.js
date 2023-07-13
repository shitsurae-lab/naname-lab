// JavaScript Document
jQuery(function($) {
	// 管理画面のプリセットカラー設定の値をカラー設定に反映する
	if (rtocThemeName.rtocThemeName == 'JIN' || rtocThemeName.rtocThemeName == 'jin-child'){
		const preset1_title = rtocThemeColor.rtocThemeColor;
		const preset1_text = '#333333';
		const preset1_back = '#ffffff';
		const preset1_border = rtocThemeColor.rtocThemeColor;
		const preset1_h2 = rtocThemeColor.rtocThemeColor;
		const preset1_h3 = rtocThemeColor.rtocThemeColor;
		const preset1_button = rtocThemeColor.rtocThemeColor;

		const preset2_title = '#3f9cff';
		const preset2_text = '#555555';
		const preset2_back = '#ffffff';
		const preset2_border = '#3f9cff';
		const preset2_h2 = '#3f9cff';
		const preset2_h3 = '#3f9cff';
		const preset2_button = '#3f9cff';

		const preset3_title = '#333333';
		const preset3_text = '#333333';
		const preset3_back = '#ffffff';
		const preset3_border = '#555555';
		const preset3_h2 = '#333333';
		const preset3_h3 = '#bfbfbf';
		const preset3_button = '#333333';

		const preset4_title = '#ff7fa1';
		const preset4_text = '#877179';
		const preset4_back = '#ffffff';
		const preset4_border = '#ff7fa1';
		const preset4_h2 = '#68d6cb';
		const preset4_h3 = '#68d6cb';
		const preset4_button = '#ff7fa1';

		const preset5_title = '#71dcf2';
		const preset5_text = '#6b5c61';
		const preset5_back = '#ffffff';
		const preset5_border = '#9be3f2';
		const preset5_h2 = '#9be3f2';
		const preset5_h3 = '#9be3f2';
		const preset5_button = '#9be3f2';

		const preset6_title = '#405796';
		const preset6_text = '#5c5f6b';
		const preset6_back = '#ffffff';
		const preset6_border = '#405796';
		const preset6_h2 = '#6A91C1';
		const preset6_h3 = '#6A91C1';
		const preset6_button = '#6a91c1';

		const preset7_title = '#79BD9A';
		const preset7_text = '#757575';
		const preset7_back = '#ffffff';
		const preset7_border = '#79BD9A';
		const preset7_h2 = '#FCAA00';
		const preset7_h3 = '#FCAA00';
		const preset7_button = '#FCAA00';
		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset1 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset1_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset1_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset1_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset1_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset1_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset1_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset1_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset2 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset2_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset2_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset2_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset2_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset2_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset2_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset2_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset3 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset3_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset3_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset3_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset3_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset3_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset3_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset3_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset4 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset4_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset4_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset4_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset4_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset4_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset4_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset4_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset5 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset5_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset5_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset5_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset5_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset5_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset5_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset5_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset6 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset6_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset6_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset6_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset6_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset6_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset6_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset6_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset7 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset7_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset7_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset7_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset7_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset7_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset7_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset7_button).change();
		});

	} else if (rtocThemeName.rtocThemeName == 'JIN:R' || rtocThemeName.rtocThemeName == 'JIN:R child'){
		const preset1_title = rtocThemeColor.rtocThemeColor;
		const preset1_text = rtocTextColor.rtocTextColor;
		const preset1_back = '#ffffff';
		const preset1_border = rtocThemeColor.rtocThemeColor;
		const preset1_h2 = rtocThemeColor.rtocThemeColor;
		const preset1_h3 = rtocThemeColor.rtocThemeColor;
		const preset1_button = rtocThemeColor.rtocThemeColor;

		const preset2_title = '#3f9cff';
		const preset2_text = '#555555';
		const preset2_back = '#ffffff';
		const preset2_border = '#3f9cff';
		const preset2_h2 = '#3f9cff';
		const preset2_h3 = '#3f9cff';
		const preset2_button = '#3f9cff';

		const preset3_title = '#333333';
		const preset3_text = '#333333';
		const preset3_back = '#ffffff';
		const preset3_border = '#555555';
		const preset3_h2 = '#333333';
		const preset3_h3 = '#bfbfbf';
		const preset3_button = '#333333';

		const preset4_title = '#ff7fa1';
		const preset4_text = '#877179';
		const preset4_back = '#ffffff';
		const preset4_border = '#ff7fa1';
		const preset4_h2 = '#68d6cb';
		const preset4_h3 = '#68d6cb';
		const preset4_button = '#ff7fa1';

		const preset5_title = '#71dcf2';
		const preset5_text = '#6b5c61';
		const preset5_back = '#ffffff';
		const preset5_border = '#9be3f2';
		const preset5_h2 = '#9be3f2';
		const preset5_h3 = '#9be3f2';
		const preset5_button = '#9be3f2';

		const preset6_title = '#405796';
		const preset6_text = '#5c5f6b';
		const preset6_back = '#ffffff';
		const preset6_border = '#405796';
		const preset6_h2 = '#6A91C1';
		const preset6_h3 = '#6A91C1';
		const preset6_button = '#6a91c1';

		const preset7_title = '#79BD9A';
		const preset7_text = '#757575';
		const preset7_back = '#ffffff';
		const preset7_border = '#79BD9A';
		const preset7_h2 = '#FCAA00';
		const preset7_h3 = '#FCAA00';
		const preset7_button = '#FCAA00';
		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset1 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset1_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset1_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset1_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset1_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset1_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset1_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset1_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset2 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset2_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset2_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset2_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset2_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset2_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset2_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset2_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset3 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset3_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset3_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset3_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset3_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset3_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset3_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset3_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset4 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset4_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset4_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset4_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset4_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset4_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset4_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset4_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset5 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset5_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset5_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset5_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset5_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset5_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset5_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset5_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset6 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset6_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset6_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset6_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset6_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset6_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset6_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset6_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset7 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset7_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset7_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset7_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset7_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset7_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset7_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset7_button).change();
		});

	} else {
		const preset1_title = '#3f9cff';
		const preset1_text = '#555555';
		const preset1_back = '#ffffff';
		const preset1_border = '#3f9cff';
		const preset1_h2 = '#3f9cff';
		const preset1_h3 = '#3f9cff';
		const preset1_button = '#3f9cff';

		const preset2_title = '#333333';
		const preset2_text = '#333333';
		const preset2_back = '#ffffff';
		const preset2_border = '#555555';
		const preset2_h2 = '#333333';
		const preset2_h3 = '#bfbfbf';
		const preset2_button = '#333333';

		const preset3_title = '#ff7fa1';
		const preset3_text = '#877179';
		const preset3_back = '#ffffff';
		const preset3_border = '#ff7fa1';
		const preset3_h2 = '#68d6cb';
		const preset3_h3 = '#68d6cb';
		const preset3_button = '#ff7fa1';

		const preset4_title = '#71dcf2';
		const preset4_text = '#6b5c61';
		const preset4_back = '#ffffff';
		const preset4_border = '#9be3f2';
		const preset4_h2 = '#9be3f2';
		const preset4_h3 = '#9be3f2';
		const preset4_button = '#9be3f2';

		const preset5_title = '#405796';
		const preset5_text = '#5c5f6b';
		const preset5_back = '#ffffff';
		const preset5_border = '#405796';
		const preset5_h2 = '#6A91C1';
		const preset5_h3 = '#6A91C1';
		const preset5_button = '#6a91c1';

		const preset6_title = '#79BD9A';
		const preset6_text = '#757575';
		const preset6_back = '#ffffff';
		const preset6_border = '#79BD9A';
		const preset6_h2 = '#FCAA00';
		const preset6_h3 = '#FCAA00';
		const preset6_button = '#FCAA00';

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset1 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset1_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset1_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset1_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset1_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset1_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset1_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset1_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset2 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset2_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset2_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset2_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset2_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset2_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset2_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset2_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset3 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset3_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset3_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset3_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset3_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset3_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset3_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset3_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset4 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset4_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset4_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset4_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset4_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset4_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset4_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset4_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset5 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset5_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset5_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset5_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset5_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset5_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset5_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset5_button).change();
		});

		$('#rtoc-config-area input[type="radio"]#rtoc_color_preset6 + label').click(function(){
			$('.rtoc_colorpicker-1 .wp-picker-container input[type=text].wp-color-picker').val(preset6_title).change();
			$('.rtoc_colorpicker-2 .wp-picker-container input[type=text].wp-color-picker').val(preset6_text).change();
			$('.rtoc_colorpicker-3 .wp-picker-container input[type=text].wp-color-picker').val(preset6_back).change();
			$('.rtoc_colorpicker-4 .wp-picker-container input[type=text].wp-color-picker').val(preset6_border).change();
			$('.rtoc_colorpicker-5 .wp-picker-container input[type=text].wp-color-picker').val(preset6_h2).change();
			$('.rtoc_colorpicker-6 .wp-picker-container input[type=text].wp-color-picker').val(preset6_h3).change();
			$('.rtoc_colorpicker-7 .wp-picker-container input[type=text].wp-color-picker').val(preset6_button).change();
		});
	}

	// プレビューの初回作成と2回目以降の動きを関数化
	function SetState() {
		// タイトル中央寄せ
		title_display = $('#rtoc_title_display_left:checked').prop('checked');
		if (title_display == true) {
			$('#rtoc-mokuji-title').addClass('align_left');
			$('#rtoc-mokuji-title').removeClass('align_center');
		}
		title_display = $('#rtoc_title_display_center:checked').prop('checked');
		if (title_display == true) {
			$('#rtoc-mokuji-title').addClass('align_center');
			$('#rtoc-mokuji-title').removeClass('align_left');
		}

		// 表示させる見出し
		display_midashi = $('#rtoc_headline_display option:checked').prop('checked');
		var midashi_val = $('#rtoc_headline_display option:checked').val();
		if (midashi_val == 'h2') {
			$('#rtoc-mokuji-wrapper').addClass('display_h2');
			$('#rtoc-mokuji-wrapper').removeClass('display_h3 display_h4');
		} else if (midashi_val == 'h3') {
			$('#rtoc-mokuji-wrapper').addClass('display_h3');
			$('#rtoc-mokuji-wrapper').removeClass('display_h2 display_h4');
		} else if (midashi_val == 'h4') {
			$('#rtoc-mokuji-wrapper').addClass('display_h4');
			$('#rtoc-mokuji-wrapper').removeClass('display_h2 display_h3');
		}

		// フォント設定
		font_admin = $('#rtoc_font option:checked').prop('checked');
		var midashi_val = $('#rtoc_font option:checked').val();
		if (midashi_val == 'default') {
			$('#rtoc-mokuji-wrapper').addClass('rtoc_default');
			$('#rtoc-mokuji-wrapper').removeClass('rtoc_helvetica rtoc_notosans');
		} else if (midashi_val == 'helvetica') {
			$('#rtoc-mokuji-wrapper').addClass('rtoc_helvetica');
			$('#rtoc-mokuji-wrapper').removeClass('rtoc_default rtoc_notosans');
		} else if (midashi_val == 'noto-sans') {
			$('#rtoc-mokuji-wrapper').addClass('rtoc_notosans');
			$('#rtoc-mokuji-wrapper').removeClass('rtoc_default rtoc_helvetica');
		}

		// h2のリスト
		h2_list = $('#rtoc_list_h2_type_ul:checked').prop('checked');
		if (h2_list == true) {
			$('.level-1').addClass('h2_ul');
			$('.level-1').removeClass('h2_ol h2_ol2 h2_none');
		}
		h2_list = $('#rtoc_list_h2_type_ol:checked').prop('checked');
		if (h2_list == true) {
			$('.level-1').addClass('h2_ol');
			$('.level-1').removeClass('h2_ul h2_ol2 h2_none');
		}
		h2_list = $('#rtoc_list_h2_type_ol2:checked').prop('checked');
		if (h2_list == true) {
			$('.level-1').addClass('h2_ol2');
			$('.level-1').removeClass('h2_ul h2_ol h2_none');
		}
		h2_list = $('#rtoc_list_h2_type_none:checked').prop('checked');
		if (h2_list == true) {
			$('.level-1').addClass('h2_none');
			$('.level-1').removeClass('h2_ul h2_ol h2_ol2');
		}

		// h3のリスト
		h3_list = $('#rtoc_list_h3_type_ul:checked').prop('checked');
		if (h3_list == true) {
			$('.level-2').addClass('h3_ul');
			$('.level-2').removeClass('h3_ol h3_ol2 h3_none');
		}
		h3_list = $('#rtoc_list_h3_type_ol:checked').prop('checked');
		if (h3_list == true) {
			$('.level-2').addClass('h3_ol');
			$('.level-2').removeClass('h3_ul h3_ol2 h3_none');
		}
		h3_list = $('#rtoc_list_h3_type_ol2:checked').prop('checked');
		if (h3_list == true) {
			$('.level-2').addClass('h3_ol2');
			$('.level-2').removeClass('h3_ul h3_ol h3_none');
		}
		h3_list = $('#rtoc_list_h3_type_none:checked').prop('checked');
		if (h3_list == true) {
			$('.level-2').addClass('h3_none');
			$('.level-2').removeClass('h3_ul h3_ol h3_ol2');
		}

		// 枠のデザイン
		border = $('#rtoc_frame_design_frame1:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame1');
			$('#rtoc-mokuji-wrapper').removeClass('frame2 frame3 frame4 frame5 frame6 frame7 frame8');
		}
		border = $('#rtoc_frame_design_frame2:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame2');
			$('#rtoc-mokuji-wrapper').removeClass('frame1 frame3 frame4 frame5 frame6 frame7 frame8');
		}
		border = $('#rtoc_frame_design_frame3:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame3');
			$('#rtoc-mokuji-wrapper').removeClass('frame2 frame1 frame4 frame5 frame6 frame7 frame8');
		}
		border = $('#rtoc_frame_design_frame4:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame4');
			$('#rtoc-mokuji-wrapper').removeClass('frame2 frame3 frame1 frame5 frame6 frame7 frame8');
		}
		border = $('#rtoc_frame_design_frame5:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame5');
			$('#rtoc-mokuji-wrapper').removeClass('frame2 frame3 frame4 frame1 frame6 frame7 frame8');
		}
		border = $('#rtoc_frame_design_frame6:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame6');
			$('#rtoc-mokuji-wrapper').removeClass('frame1 frame2 frame3 frame4 frame5 frame7 frame8');
		}
		border = $('#rtoc_frame_design_frame7:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame7');
			$('#rtoc-mokuji-wrapper').removeClass('frame1 frame2 frame3 frame4 frame5 frame6 frame8');
		}
		border = $('#rtoc_frame_design_frame8:checked').prop('checked');
		if (border == true) {
			$('#rtoc-mokuji-wrapper').addClass('frame8');
			$('#rtoc-mokuji-wrapper').removeClass('frame1 frame2 frame3 frame4 frame5 frame6 frame7');
		}

		// アニメーション
		animation = $('#rtoc_animation_animation-fade:checked').prop('checked');
		if (animation == true) {
			$('#rtoc-mokuji-wrapper').addClass('animation-fade');
			$('#rtoc-mokuji-wrapper').removeClass('animation-slide animation-none');
		}
		animation = $('#rtoc_animation_animation-slide:checked').prop('checked');
		if (animation == true) {
			$('#rtoc-mokuji-wrapper').addClass('animation-slide');
			$('#rtoc-mokuji-wrapper').removeClass('animation-fade animation-none');
		}
		animation = $('#rtoc_animation_animation-none:checked').prop('checked');
		if (animation == true) {
			$('#rtoc-mokuji-wrapper').addClass('animation-none');
			$('#rtoc-mokuji-wrapper').removeClass('animation-fade animation-slide');
		}
	}

	$(document).ready(function () {

		// JINテーマを有効化している時
		if (rtocThemeName.rtocThemeName == 'JIN' || rtocThemeName.rtocThemeName == 'jin-child'){
			if ($('#rtoc_color_preset1[name=rtoc_color]').prop('checked')){
				$('.rtoc_admin_wrapper').addClass('jin-block');
			} else {
				$('.rtoc_admin_wrapper').removeClass('jin-block');
			}
			$('input[name="rtoc_color"]').change(function () {
				if ($('#rtoc_color_preset1[name=rtoc_color]').prop('checked')){
					$('.rtoc_admin_wrapper').addClass('jin-block');
				} else {
					$('.rtoc_admin_wrapper').removeClass('jin-block');
				}
			})
		} else {
			$('.rtoc_admin_wrapper').removeClass('jin-block');
		}

		// タイトルテキストの反映
		// value値を取得して吐き出す
		const rtocInitialMokuji = $('.rtoc_value_text input[type=text]').val();
		$('#rtoc-mokuji-title span').text(rtocInitialMokuji);

		// 変更後の動き
		$('#rtoc_title').keyup(function() {
			const rtocMokujiTitle = $(this).val();
			$('#rtoc-mokuji-title span').text(rtocMokujiTitle);
		});

		// 開閉ボタンのテキスト反映
		let rtocOpenCloseText = $('#rtoc_close_text').val();
		$('#rtoc-mokuji-title button').text(rtocOpenCloseText);
		const OpenClose = document.querySelector('.rtoc_open_close');
		const MokujiWrapper = document.getElementById('rtoc-mokuji-wrapper');

		$('#rtoc_close_text').keyup(function() {
			if ( OpenClose.classList.contains('rtoc_text_close') ){
				let rtocButtonText = $(this).val();
				$('#rtoc-mokuji-title button').text(rtocButtonText);
			}
		});

		$('#rtoc_open_text').keyup(function() {
			if ( OpenClose.classList.contains('rtoc_text_open') ){
				let rtocButtonText = $(this).val();
				$('#rtoc-mokuji-title button').text(rtocButtonText);
			}
		});
		OpenClose.addEventListener('click', () => {
			if ( OpenClose.classList.contains('rtoc_text_close') ){
				rtocOpenCloseText = $('#rtoc_open_text').val();
				$('#rtoc-mokuji-title button').text( rtocOpenCloseText );
				OpenClose.classList.remove('rtoc_text_close');
				MokujiWrapper.classList.remove('rtoc_open');
				OpenClose.classList.add('rtoc_text_open');
				MokujiWrapper.classList.add('rtoc_close');
			} else {
				rtocOpenCloseText = $('#rtoc_close_text').val();
				$('#rtoc-mokuji-title button').text( rtocOpenCloseText );
				OpenClose.classList.remove('rtoc_text_open');
				MokujiWrapper.classList.remove('rtoc_close');
				OpenClose.classList.add('rtoc_text_close');
				MokujiWrapper.classList.add('rtoc_open');
			}
		});

		// デザイン設定の反映
		SetState();
		$("*[name='rtoc_title_display']").click(function(){SetState();});
		$("*[name='rtoc_list_h2_type']").click(function(){SetState();});
		$("*[name='rtoc_list_h3_type']").click(function(){SetState();})
		$("*[name='rtoc_frame_design']").click(function(){SetState();})
		$("*[name='rtoc_animation']").click(function(){SetState();})
		$("*[name='rtoc_headline_display']").change(function(){SetState();})
		$("*[name='rtoc_font']").change(function(){SetState();})
		$("*[name='rtoc_addon_design']").click(function(){SetState();})
		$("*[name='rtoc_initial_display']").click(function(){SetState();})


		// カラー設定の反映
		const targetTitle = document.querySelectorAll('.wp-color-result.button')[0];
		const targetText = document.querySelectorAll('.wp-color-result.button')[1];
		const targetBack = document.querySelectorAll('.wp-color-result.button')[2];
		const targetBorder = document.querySelectorAll('.wp-color-result.button')[3];
		const targetH2 = document.querySelectorAll('.wp-color-result.button')[4];
		const targetH3 = document.querySelectorAll('.wp-color-result.button')[5];

		// 色をあてがうDOM要素を取得
		const mokujiTitle = document.getElementById('rtoc-mokuji-title');
		const mokujiText = document.querySelectorAll('.rtoc-item a')
		const mokujiBack = document.getElementById('rtoc-mokuji-wrapper');
		const mokujiH2 = document.querySelectorAll('.level-1');
		const mokujiH3 = document.querySelectorAll('.level-2');

		// カラー設定の初期値をプレビューに反映する
		mokujiBack.style.backgroundColor = targetBack.style.backgroundColor;
		mokujiBack.style.borderColor = targetBorder.style.backgroundColor;
		mokujiTitle.style.color = targetTitle.style.backgroundColor;
		psuedoBorder();
		function psuedoH2(){
			var styleH2 = document.createElement('style');
			styleH2.type = 'text/css';
			styleH2.innerText =
				'#rtoc-mokuji-wrapper .rtoc-mokuji.level-1.h2_ul > .rtoc-item::before, #rtoc-mokuji-wrapper .rtoc-mokuji.level-1.h2_ol2 > .rtoc-item::after, #rtoc-mokuji-wrapper.h2_timeline .level-1.h2_ol > .rtoc-item::after, #rtoc-mokuji-wrapper.h2_timeline .level-1.h2_ol2 > .rtoc-item::after {background-color: ' +
				targetH2.style.backgroundColor +
				'}';
			document.getElementsByTagName('HEAD').item(0).appendChild(styleH2);
		}

		function psuedoH3(){
			var styleH3 = document.createElement('style');
			styleH3.type = 'text/css';
			styleH3.innerText =
				'#rtoc-mokuji-wrapper .rtoc-mokuji.level-2.h3_ul > .rtoc-item::before, #rtoc-mokuji-wrapper .rtoc-mokuji.level-2.h3_ol2 > .rtoc-item::after, #rtoc-mokuji-wrapper.h3_timeline .level-2.h3_ol > .rtoc-item::after, #rtoc-mokuji-wrapper.h3_timeline .level-2.h3_ol2 > .rtoc-item::after  {background-color: ' +
				targetH3.style.backgroundColor +
				'}';
			document.getElementsByTagName('HEAD').item(0).appendChild(styleH3);
		}

		function psuedoBorder(){
			var styleBorder = document.createElement('style');
			styleBorder.type = 'text/css';
			styleBorder.innerText =
				'#rtoc-mokuji-wrapper.frame2::before, #rtoc-mokuji-wrapper.frame7::before, #rtoc-mokuji-wrapper.frame8::before {border-color:' +
				targetBorder.style.backgroundColor + // frame6 のタイトル背景は,タイトル色ではなく,枠線カラーなので注意 ↓
				'} #rtoc-mokuji-wrapper.frame5::before, #rtoc-mokuji-wrapper.frame5::after, #rtoc-mokuji-wrapper.frame6 #rtoc-mokuji-title, #rtoc-mokuji-wrapper.frame7 #rtoc-mokuji-title::after {background-color: ' +
				targetBorder.style.backgroundColor +
				'} #rtoc-mokuji-wrapper.frame7 #rtoc-mokuji-title span::after {background-color: ' +
				targetTitle.style.backgroundColor +
				'}.cls-1, .cls-2 {stroke: ' +
				targetBorder.style.backgroundColor +
				'!important} ';
			document.getElementsByTagName('HEAD').item(0).appendChild(styleBorder);
		}

		for ( var t=0 ; t<mokujiText.length; t++ ){
			mokujiText[t].style.color = targetText.style.backgroundColor;
		}
		for ( var i=0 ; i<mokujiH2.length; i++ ){
			mokujiH2[i].style.color = targetH2.style.backgroundColor;
			psuedoH2();
		}
		for ( var c=0 ; c<mokujiH3.length; c++ ){
			mokujiH3[c].style.color = targetH3.style.backgroundColor;
			psuedoH3()
		}
		// MutationObserverを使って動的に（初期値以外）プレビューの色を変更する
		const observer = new MutationObserver(records => {
			let titleColor;
			titleColor = targetTitle.style.backgroundColor;

			const textColor = targetText.style.backgroundColor;
			const backColor = targetBack.style.backgroundColor;
			const borderColor = targetBorder.style.backgroundColor;
			const h2Color = targetH2.style.backgroundColor;
			const h3Color = targetH3.style.backgroundColor;

			// 変更したカラー設定をプレビューのカラーへ追加
			mokujiTitle.style.color = titleColor;
			mokujiBack.style.backgroundColor = backColor;
			mokujiBack.style.borderColor = borderColor;
			psuedoBorder();

			for (var t = 0; t < mokujiText.length; t++) {
				mokujiText[t].style.color = textColor;
			}
			for (var i = 0; i < mokujiH2.length; i++) {
				mokujiH2[i].style.color = h2Color;
				psuedoH2();
			}
			for (var c = 0; c < mokujiH3.length; c++) {
				mokujiH3[c].style.color = h3Color;
				psuedoH3();
			}
		})
		// 監視の開始
		observer.observe(targetTitle, {
			attributes: true,
			attributeFilter: ['style']
		})
		observer.observe(targetText, {
			attributes: true,
			attributeFilter: ['style']
		})
		observer.observe(targetBack, {
			attributes: true,
			attributeFilter: ['style']
		})
		observer.observe(targetBorder, {
			attributes: true,
			attributeFilter: ['style']
		})
		observer.observe(targetH2, {
			attributes: true,
			attributeFilter: ['style']
		})
		observer.observe(targetH3, {
			attributes: true,
			attributeFilter: ['style']
		})
	});
});
