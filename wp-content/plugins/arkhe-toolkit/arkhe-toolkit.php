<?php
/**
 * Plugin Name: Arkhe Toolkit
 * Plugin URI: https://arkhe-theme.com
 * Description: A plugin that extends Arkhe more conveniently
 * Version: 1.11.1
 * Author: LOOS,Inc.
 * Author URI: https://loos.co.jp/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: arkhe-toolkit
 * Domain Path: /languages
 *
 * @package Arkhe Toolkit
 */

defined( 'ABSPATH' ) || exit;

/**
 * 定数定義
 */
define( 'ARKHE_TOOLKIT_URL', plugins_url( '/', __FILE__ ) );
define( 'ARKHE_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );


/**
 * polyfill
 */
require_once __DIR__ . '/inc/polyfill.php';


/**
 * Autoload Class files.
 */
spl_autoload_register(
	function( $classname ) {
		// Arkhe_Toolkit の付いたクラスだけを対象にする。
		if ( ! str_contains( $classname, 'Arkhe_Toolkit' ) ) return;

		$classname = str_replace( '\\', '/', $classname );
		$classname = str_replace( 'Arkhe_Toolkit/', '', $classname );

		$file = ARKHE_TOOLKIT_PATH . 'classes/' . $classname . '.php';
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);


/**
 * プラグイン実行クラス
 */
class Arkhe_Toolkit extends \Arkhe_Toolkit\Data {

	use \Arkhe_Toolkit\SVG,
		\Arkhe_Toolkit\Get,
		\Arkhe_Toolkit\Meta,
		\Arkhe_Toolkit\Utility,
		\Arkhe_Toolkit\Output_Menu_Field,
		\Arkhe_Toolkit\Output_Meta_Field;

	public function __construct() {

		// テーマチェック : IS_ARKHE_THEME は Arkheプラグインで共通
		if ( ! defined( 'IS_ARKHE_THEME' ) ) {
			define( 'IS_ARKHE_THEME', 'arkhe' === get_template() );
		}

		if ( ! IS_ARKHE_THEME ) return;

		// 翻訳ファイルを登録
		if ( 'ja' === determine_locale() ) {
			load_textdomain( 'arkhe-toolkit', ARKHE_TOOLKIT_PATH . 'languages/arkhe-toolkit-ja.mo' );
		} else {
			load_plugin_textdomain( 'arkhe-toolkit' );
		}

		// プラグインのバージョン情報
		$file_data      = get_file_data( __FILE__, [ 'version' => 'Version' ] );
		self::$version  = $file_data['version'];
		self::$file_ver = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? wp_date( 'mdGis' ) : self::$version;

		// データセット
		self::init();

		// setup
		require_once ARKHE_TOOLKIT_PATH . 'inc/setup.php';

		// 管理メニュー
		require_once ARKHE_TOOLKIT_PATH . 'inc/admin_menu.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/admin_toolbar.php';

		// ファイルの読み込み
		require_once ARKHE_TOOLKIT_PATH . 'inc/enqueue_scripts.php';

		// 管理画面の表示
		require_once ARKHE_TOOLKIT_PATH . 'inc/wp_posts_table.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/wp_term_table.php';

		// 機能削除
		require_once ARKHE_TOOLKIT_PATH . 'inc/remove.php';

		// ショートコード
		require_once ARKHE_TOOLKIT_PATH . 'inc/shortcode.php';

		// カスタマイザー
		require_once ARKHE_TOOLKIT_PATH . 'inc/customizer.php';

		// ウィジェット
		require_once ARKHE_TOOLKIT_PATH . 'inc/widget.php';

		// キャッシュ
		require_once ARKHE_TOOLKIT_PATH . 'inc/cache_parts.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/cache_clear.php';

		// ajax
		require_once ARKHE_TOOLKIT_PATH . 'inc/ajax.php';

		// カスタムフィールド
		require_once ARKHE_TOOLKIT_PATH . 'inc/meta_post.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/meta_term.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/meta_user.php';

		// コンテンツフック
		require_once ARKHE_TOOLKIT_PATH . 'inc/the_content.php';

		// JSON-LD
		require_once ARKHE_TOOLKIT_PATH . 'inc/json_ld.php';

		// 出力系
		require_once ARKHE_TOOLKIT_PATH . 'inc/output.php';

		// その他、フック処理
		require_once ARKHE_TOOLKIT_PATH . 'inc/hooks.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/meta_hooks.php';
		require_once ARKHE_TOOLKIT_PATH . 'inc/delay_js.php';

		// 管理者ログイン時
		if ( current_user_can( 'manage_options' ) ) {

			// アップデートチェック
			require_once ARKHE_TOOLKIT_PATH . 'inc/update.php';

			// アップデート時の処理
			require_once ARKHE_TOOLKIT_PATH . 'inc/updated_action.php';

		}
	}
}


/**
 * プラグインファイルの実行
 */
add_action( 'plugins_loaded', function() {
	new Arkhe_Toolkit();
} );
