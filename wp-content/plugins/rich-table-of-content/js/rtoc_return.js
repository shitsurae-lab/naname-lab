jQuery(function ($) {
	// 目次へ戻るボタン（スマホのみ）
	const ua = navigator.userAgent;
	if (typeof rtocBackButton === 'undefined'){
		return;
	}
	const rtocBackBtn = rtocBackButton.rtocBackButton;
	if (!rtocBackBtn) return;
	if (rtocBackBtn == 'on') {
		if (ua.indexOf('iPhone') > -1 || (ua.indexOf('Android') > -1 && ua.indexOf('Mobile') > -1)) {
			jQuery('body').append('<div id="rtoc_return"><a href="#rtoc-mokuji-wrapper">' + rtocBackText.rtocBackText + '</a></div>');
			$(window).on('load scroll', function () {
				if ($(this).scrollTop() > 1000) {
					$('#rtoc_return').fadeIn('slow');
				} else {
					$('#rtoc_return').fadeOut('slow');
				}
			});
			$('a[href^="#rtoc-mokuji-wrapper"]').click(function () {
				var speed = 400;
				var href = $(this).attr('href');
				var target = $(href == '#' || href == '' ? 'html' : href);
				var position = target.offset().top;
				$('html, body').animate({ scrollTop: position }, speed, 'swing');
				return false;
			});
		}

		// PCで表示するにチェックが入っている場合
		if (rtocBackDisplayPC.rtocBackDisplayPC) {
			if (!(navigator.userAgent.indexOf('iPhone') > 0 || (navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('Mobile') > 0) || navigator.userAgent.indexOf('iPad') > 0 || navigator.userAgent.indexOf('Android') > 0)) {
				jQuery('body').append('<div id="rtoc_return"><a href="#rtoc-mokuji-wrapper">' + rtocBackText.rtocBackText + '</a></div>');
				$(window).on('load scroll', function () {
					if ($(this).scrollTop() > 600) {
						$('#rtoc_return').fadeIn('slow');
					} else {
						$('#rtoc_return').fadeOut('slow');
					}
				});
				$('a[href^="#rtoc-mokuji-wrapper"]').click(function () {
					const widgetDocument = document.querySelector('#widget-tracking #rtoc-mokuji-widget-wrapper');
					if(widgetDocument){
						widgetDocument.querySelector('.rtoc-mokuji').scrollTo(0, 0);
					}
					var speed = 400;
					var href = $(this).attr('href');
					var target = $(href == '#' || href == '' ? 'html' : href);
					var position = target.offset().top;
					$('html, body').animate({ scrollTop: position }, speed, 'swing');
					return false;
				});
			}
		}
	}

	const back_button = document.querySelector('#rtoc_return');
	const back_button_link = document.querySelector('#rtoc_return a');
	if (!back_button) {
		return;
	}

	if (rtocButtonPosition.rtocButtonPosition == 'left') {
		back_button.classList.add('back_button_left');
	} else if (rtocButtonPosition.rtocButtonPosition == 'right') {
		back_button.classList.add('back_button_right');
	}
	if (rtocVerticalPosition.rtocVerticalPosition.length) {
		back_button_link.style.bottom = rtocVerticalPosition.rtocVerticalPosition + 'px';
	}
});
