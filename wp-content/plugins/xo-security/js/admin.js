/* global pagenow, ajaxurl, xoSecurityAdminOptions */
(function ($) {
	var xoSecuritySettings = function () {
		var
			field_interval = $("#field_interval"),
			field_login_page = $("#field_login_page"),
			field_login_page_name = $("#field_login_page_name"),
			field_login_alert = $("#field_login_alert"),
			field_login_alert_subject = $("#field_login_alert_subject"),
			field_login_alert_body = $("#field_login_alert_body"),
			field_login_alert_admin_only = $("#field_login_alert_admin_only"),
			field_rest = $("#field_rest"),
			field_rest_namespace = $("#xo_security_rest_disable input.namespace");
			field_rest_rename = $("#field_rest_rename"),
			field_rest_name = $("#field_rest_name"),
			field_comment_spam = $("#field_comment_spam"),
			field_comment_spam_message = $("#field_comment_spam_message"),

			interval_change = function () {
				if ("0" === field_interval.val()) {
					$("#field_limit_count").attr("disabled", "disabled");
				} else {
					$("#field_limit_count").prop("disabled", false);
				}
			},

			login_page_change = function () {
				if (field_login_page.prop("checked")) {
					field_login_page_name.prop("disabled", false);
				} else {
					field_login_page_name.attr("disabled", "disabled");
				}
				loginurl_update();
			},

			loginurl_update = function () {
				if (field_login_page.prop("checked")) {
					var login_url = field_login_page_name.val();
					if (login_url !== '') {
						login_url = login_url.toLowerCase().replace(/[^\w-]/g, "");
						field_login_page_name.val(login_url);
						$("#login_url").text(xoSecurityAdminOptions.site_url + login_url + ".php");
					} else {
						$("#login_url").text("");
					}
				} else {
					$("#login_url").text(xoSecurityAdminOptions.site_url + "wp-login.php");
				}
			},

			login_alert_change = function () {
				if (field_login_alert.prop("checked")) {
					field_login_alert_subject.prop("readonly", false);
					field_login_alert_body.prop("readonly", false);
					field_login_alert_admin_only.prop("readonly", false);
				} else {
					field_login_alert_subject.attr("readonly", "disabled");
					field_login_alert_body.attr("readonly", "disabled");
					field_login_alert_admin_only.attr("readonly", "disabled");
				}
			},

			rest_change = function () {
				if (field_rest.prop("checked")) {
					$("#xo_security_rest_disable input").prop("disabled", false);
				} else {
					$("#xo_security_rest_disable input").prop("disabled", true);
				}
			},

			rest_namespace_change = function () {
				console.log(this);
				var namespace = $(this).val();
				console.log(namespace);
				if ($(this).prop("checked")) {
					$("input[data-namespace='" + namespace + "']").prop('checked', true);
				} else {
					$("input[data-namespace='" + namespace + "']").prop('checked', false);
				}
			},

			rest_rename_change = function () {
				if (field_rest_rename.prop("checked")) {
					field_rest_name.prop("disabled", false);
				} else {
					field_rest_name.attr("disabled", "disabled");
				}
				rest_name_update();
			},

			rest_name_update = function () {
				var rest_name = field_rest_name.val();
				if (rest_name !== undefined) {
					rest_name = rest_name.toLowerCase().replace(/[^\w-]/g, "");
					field_rest_name.val(rest_name);
				}
			},

			comment_spam_change = function () {
				if (field_comment_spam.prop("checked")) {
					field_comment_spam_message.prop("readonly", false);
				} else {
					field_comment_spam_message.attr("readonly", "disabled");
				}
			};

		field_interval.on('change', interval_change);
		field_login_page.on('change', login_page_change);
		field_login_page_name.on('change', loginurl_update);
		field_login_alert.on('change', login_alert_change);
		field_rest.on('change', rest_change);
		field_rest_namespace.on('change', rest_namespace_change);
		field_rest_rename.on('change', rest_rename_change);
		field_rest_name.on('change', rest_name_update);
		field_comment_spam.on('change', comment_spam_change);

		interval_change();
		login_page_change();
		login_alert_change();
		rest_change();
		rest_rename_change();
		comment_spam_change();
	};

	var xoSecurityDashboard = function () {
		var target = $('#xo_security_dashboard_login_widget .inside');
		if ( target.length ) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: { action: 'xo_security_dashboard', nonce: xoSecurityAdminOptions.nonce },
			})
			.done(function (response) {
				if ( response.success ) {
					target.html(response.data);
				} else {
					target.html('');
				}
			});
		}
	};

	$(document).ready(function () {
		if ('settings_page_xo-security-settings' === pagenow) {
			xoSecuritySettings();
		} else if ('dashboard' === pagenow) {
			xoSecurityDashboard();
		}
	});
}(jQuery));
