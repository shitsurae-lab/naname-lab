// RTOC ver1.2〜
jQuery(function ($) {
	// モーダル用クラスの切り替え
	$('#js-rtoc-edit-reset-button').on('click', function () {
		$('#rtoc_meta_click > .inside').addClass('rtoc-edit-modal');
	});
	$('#js-rtoc-edit-popup-close').on('click', function () {
		$('#rtoc_meta_click > .inside').removeClass('rtoc-edit-modal');
	});

	// （編集画面）目次使用率のリセットをクリックしたら
	$('#js-rtoc-edit-reset-execute').on('click', function () {
		const postId = $('#rtoc-edit-table-wrapper').data('id');
		// console.log(postId);

		$.ajax({
			url: rtocEdit.ajax_url,
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: rtocEdit.action,
				security: rtocEdit.nonce,
				id: postId,
			},
		})
			.done(function (data) {
				// console.log('delete done use rate');
			})
			.fail(function (data) {
				// console.log('delete fail use rate');
			})
			.always(function (data) {
				// console.log('delete executed use rate');
			});

		// ポップアップ内のテキスト変更 + pタグ追加
		$('.rtoc-edit-popup-content > h3').text('この記事の目次使用率をリセットしました。').after('<p>（記事の更新 または 再読み込みしてください）</p>');
		$('.rtoc-edit-popup-buttons').remove();
	});
});
