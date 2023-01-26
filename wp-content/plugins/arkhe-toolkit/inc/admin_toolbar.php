<?php
namespace Arkhe_Toolkit\Toolbar;

// 「カスタマイズ」を管理画面側にも追加
add_filter( 'admin_bar_menu', function ( $wp_admin_bar ) {
	if ( ! is_admin() ) return;
	$wp_admin_bar->add_menu(
		[
			'id'    => 'customize',
			'title' => '<span class="ab-label">' . __( 'Customize' ) . '</span>', // phpcs:ignore WordPress.WP.I18n
			'href'  => admin_url( 'customize.php' ),
		]
	);
}, 50);

// Toolkitツールバー
add_filter( 'admin_bar_menu', function ( $wp_admin_bar ) {

	$arkhe_menu_id = 'arkhe_toolkit';

	// 親メニュー
	$wp_admin_bar->add_menu(
		[
			'id'    => $arkhe_menu_id,
			'title' => '<span class="ab-icon -arkhe">' . \Arkhe_Toolkit::get_svg( 'arkhe-logo' ) . '</span><span class="ab-label">Toolkit</span>',
			'href'  => admin_url( 'admin.php?page=' . \Arkhe_Toolkit::MENU_SLUG ),
			'meta'  => [
				'class' => 'arkhe-menu',
			],
		]
	);

	// サブメニュー
	$wp_admin_bar->add_menu(
		[
			'parent' => $arkhe_menu_id,
			'id'     => $arkhe_menu_id . '_menu',
			'meta'   => [],
			'title'  => __( 'To setting page', 'arkhe-toolkit' ),
			'href'   => admin_url( 'admin.php?page=' . \Arkhe_Toolkit::MENU_SLUG ),
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => $arkhe_menu_id,
			'id'     => $arkhe_menu_id . '_theme_page',
			'meta'   => [],
			'title'  => __( 'To theme page', 'arkhe-toolkit' ),
			'href'   => admin_url( 'themes.php?page=arkhe' ),
		]
	);
	$wp_admin_bar->add_menu(
		[
			'parent' => $arkhe_menu_id,
			'id'     => $arkhe_menu_id . '_clear_cache',
			'meta'   => [
				'class' => 'arkhe-btn-clearCache',
			],
			'title'  => __( 'Clear Cache', 'arkhe-toolkit' ),
			'href'   => '###',
		]
	);

	// ライセンスキーの導線
	if ( ! \Arkhe::get_plugin_data( 'added_toolbar_to_pro' ) && ! \Arkhe::$has_pro_licence ) {
		\Arkhe::set_plugin_data( 'added_toolbar_to_pro', 1 );

		if ( method_exists( '\Arkhe', 'get_toolbar_data' ) ) {

			// since arkhe 1.9 ~
			$wp_admin_bar->add_menu( \Arkhe::get_toolbar_data( 'licence' ) );

		} else {

			// 後方互換のためしばらく残す
			$license_title = __( 'License key registration', 'arkhe-toolkit' );
			$wp_admin_bar->add_menu( [
				'id'     => 'arkhe_licence_check',
				'meta'   => [ 'class' => 'arkhe-menu-licence' ],
				'title'  => '<span class="ab-icon -arkhe">' . \Arkhe_Toolkit::get_svg( 'arkhe-logo' ) . '</span><span class="ab-label">' . $license_title . '</span>',
				'href'   => admin_url( 'themes.php?page=arkhe&tab=licence' ),
			] );
		}
	}
}, 99 );
