// ================================================
// Addon有効時 / 管理画面「RTOC設定」
// ================================================
jQuery(function ($) {
	// 設定画面「Live Preview」の目次ラップ要素
	const configMokujiWrap = $('#rtoc-mokuji-wrapper');

	// 設定画面「H2のタイムライン」の初回 + 監視
	if (rtocH2Timeline.rtocH2Timeline === 'on') {
		configMokujiWrap.addClass('h2_timeline');
	} else {
		configMokujiWrap.removeClass('h2_timeline');
	}
	$('input[name="rtoc_h2_timeline"]:radio').change(function () {
		var h2Timeline = $(this).val();
		if (h2Timeline === 'on') {
			configMokujiWrap.addClass('h2_timeline');
		} else {
			configMokujiWrap.removeClass('h2_timeline');
		}
	});

	// 設定画面「H3のタイムライン」の初回 + 監視
	if (rtocH3Timeline.rtocH3Timeline === 'on') {
		configMokujiWrap.addClass('h3_timeline');
	} else {
		configMokujiWrap.removeClass('h3_timeline');
	}
	$('input[name="rtoc_h3_timeline"]:radio').change(function () {
		var h3Timeline = $(this).val();
		if (h3Timeline === 'on') {
			configMokujiWrap.addClass('h3_timeline');
		} else {
			configMokujiWrap.removeClass('h3_timeline');
		}
	});
});
