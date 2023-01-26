<?php
namespace Arkhe_Toolkit\Menu;

defined( 'ABSPATH' ) || exit;

// 設定タブのリスト
$setting_tabs = \Arkhe_Toolkit::$menu_tabs;

// 特殊なタブを追加
// $setting_tabs['reset'] = __( 'Reset', 'arkhe-toolkit' );

// 現在のタブを取得
$now_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'extension'; // phpcs:ignore

// 設定保存完了時、$_REQUEST でデータが渡ってくる
// phpcs:ignore
if ( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] ) {
	$green_message = __( 'Your settings have been saved.', 'arkhe-toolkit' ); // 設定を保存しました。
	echo '<div class="notice updated is-dismissible"><p>' . esc_html( $green_message ) . '</p></div>';
}

?>

<div id="arkhe-menu" class="arkhe-menu wrap">
	<div class="arkhe-menu__head">
		<hr class="wp-header-end">
		<div class="arkhe-menu__container">
			<h1 class="arkhe-menu__title">
				<img src="<?=esc_attr( ARKHE_TOOLKIT_URL . 'assets/img/arkhe-logo.svg' )?>" alt="" width="40" height="40" class="arkhe-menu__titleLogo">
				<?=esc_html__( 'Arkhe Toolkit Settings', 'arkhe-toolkit' )?>
			</h1>
			<div class="nav-tab-wrapper">
				<?php
					foreach ( $setting_tabs as $key => $val ) :
					$tab_url   = admin_url( 'admin.php?page=' . \Arkhe_Toolkit::MENU_SLUG ) . '&tab=' . $key;
					$nav_class = ( $now_tab === $key ) ? 'nav-tab nav-tab-active' : 'nav-tab';

					echo '<a href="' . esc_url( $tab_url ) . '" class="' . esc_attr( $nav_class ) . '">' . esc_html( $val ) . '</a>';
					endforeach;
				?>
			</div>
		</div>
	</div>
	<div class="arkhe-menu__body">
		<?php
			// 特殊な設定のタブ
			if ( 'reset' === $now_tab ) :
			include __DIR__ . '/tabs/' . $now_tab . '.php';
			else :
		?>
			<form method="POST" action="options.php">
				<?php
					// 設定項目の出力
					do_settings_sections( \Arkhe_Toolkit::MENU_PAGE_PREFIX . $now_tab );

					// nonceなどを出力
					settings_fields( \Arkhe_Toolkit::MENU_GROUP_PREFIX . $now_tab );
				?>
				<div class="arkhe-menu__saveBtnWrap">
					<?php submit_button( '', 'primary large', 'submit_' . $now_tab ); ?>
				</div>
			</form>
		<?php endif; ?>
	</div>
</div>
