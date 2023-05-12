// RTOC ver1.2〜
jQuery(function ($) {
	const postId = $('#rtoc-mokuji-wrapper').data('id');
	// console.log(postId);
	const touchEvent = window.ontouchstart;
	const touchPoint = navigator.maxTouchPoints;
	const mediaQuery = window.matchMedia('(max-width:768px)');
	if (mediaQuery.matches && touchEvent !== undefined && 0 < touchPoint) {
		var winWidth = 'sp';
	} else {
		var winWidth = 'pc';
	}
	// console.log(winWidth);
	rtoc_media();

	function rtoc_media() {
		let media = winWidth;
		$.ajax({
			url: rtocUseRate.ajax_url,
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: rtocUseRate.action,
				security: rtocUseRate.nonce,
				id: postId,
				media: media,
				kind: 'view',
			},
		})
			.done(function (data) {
				// console.log('done view');
			})
			.fail(function (data) {
				// console.log('fail view');
			})
			.always(function (data) {
				// console.log('executed view');
			});
	}

	// 目次使用率用のクリック数
	$('a[href^="#rtoc-"]').on('click', function rtoc_only_once_click() {
		let media = winWidth;
		$.ajax({
			url: rtocUseRate.ajax_url,
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: rtocUseRate.action,
				security: rtocUseRate.nonce,
				id: postId,
				media: media,
				kind: 'oneclick',
			},
		})
			.done(function (data) {
				// console.log('done one click');
			})
			.fail(function (data) {
				// console.log('fail one click');
			})
			.always(function (data) {
				// console.log('executed one click');
			});

		// どの見出しをクリックしたとしても、このajaxを行うのは1回のみ。
		$('a[href^="#rtoc-"]').off('click', rtoc_only_once_click);
	});
});
