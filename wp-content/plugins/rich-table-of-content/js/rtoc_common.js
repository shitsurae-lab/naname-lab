jQuery(function($) {
	const OpenClose = document.querySelector('.rtoc_open_close');
	const listWrapper = document.querySelector('.level-1');
	const listTitle = document.getElementById('rtoc-mokuji-title'); // RTOC ver1.2〜

	// 開閉ボタン
	if (OpenClose != null) {
		if (OpenClose.classList.contains('rtoc_open')) {
			OpenClose.textContent = rtocCloseText.rtocCloseText;
		} else if (OpenClose.classList.contains('rtoc_close')) {
			OpenClose.textContent = rtocOpenText.rtocOpenText;
			listWrapper.classList.add('is_close');
			listTitle.classList.add('is_close'); // RTOC ver1.2〜
		}
		OpenClose.addEventListener('click', () => {
			listWrapper.classList.toggle('is_close');
			listTitle.classList.toggle('is_close'); // RTOC ver1.2〜
			if (listWrapper.classList.contains('is_close')) {
				OpenClose.textContent = rtocOpenText.rtocOpenText;
			} else {
				OpenClose.textContent = rtocCloseText.rtocCloseText;
			}
		});
	}
});
