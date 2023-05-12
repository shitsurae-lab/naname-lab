jQuery(function ($) {
	// JIN専用機能
	if (!(navigator.userAgent.indexOf('iPhone') > 0 || (navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('Mobile') > 0) || navigator.userAgent.indexOf('iPad') > 0 || navigator.userAgent.indexOf('Android') > 0)) {
		const jinContentsHeight = $('#main-contents').innerHeight();
		$('#sidebar').css('height', jinContentsHeight);
	}
});
