<?php
/**
 * Plugin Name: Arkhe CSS Editor
 * Plugin URI: https://arkhe-theme.com/ja/plugins/arkhe-css-editor/
 * Description: You will be able to edit the CSS for the block editor and for the front page from the admin panel.
 * Version: 1.2.2
 * Author: LOOS,Inc.
 * Author URI: https://loos.co.jp/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: arkhe-css-editor
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * 定数宣言
 */
define( 'ARK_CSS_EDIT_URL', plugins_url( '/', __FILE__ ) );
define( 'ARK_CSS_EDIT_PATH', plugin_dir_path( __FILE__ ) );

/**
 * クラス読み込み
 */
require_once ARK_CSS_EDIT_PATH . 'classes/Utility.php';

/**
 * プラグイン実行クラス
 */
class Arkhe_CSS_Editor {

	use \Arkhe_CSS_Editor\Utility;

	const MENU_SLUG    = 'arkhe_css_editor';
	const META_KEY     = 'arkhe_css_editor_meta';
	const NONCE_NAME   = 'arkhe_css_nonce';
	const NONCE_ACTION = 'arkhe_css_nonce_action';

	const DB_NAME = [
		'common'   => 'arkhe_css_common',
		'front'    => 'arkhe_css_front',
		'editor'   => 'arkhe_css_editor',
		'settings' => 'arkhe_css_settings',
		'options'  => 'arkhe_css_options',
	];

	// フォントファミリーのバリエーション
	const FONT_FAMILIES = [
		[
			'label' => 'Fira Code',
			'val'   => 'Fira Code',
		],
		[
			'label' => 'Source Code Pro',
			'val'   => 'Source Code Pro',
		],
		[
			'label' => 'Ubuntu Mono',
			'val'   => 'Ubuntu Mono',
		],
		[
			'label' => 'Anonymous Pro',
			'val'   => 'Anonymous Pro',
		],
	];

	// エディタのデフォルト設定
	const SETTINGS = [
		'theme' => [
			'type' => 'string',
			'val'  => 'vs-dark',
		],
	];

	// エディタのデフォルトオプション
	const OPTIONS = [
		'fontFamily' => [
			'type' => 'string',
			'val'  => 'Fira Code',
		],
		'fontSize' => [
			'type' => 'number',
			'val'  => 14,
		],
		'lineHeight' => [
			'type' => 'number',
			'val'  => 24,
		],
	];

	// version
	public static $version  = '';
	public static $file_ver = '';

	public function __construct() {

		// プラグインのバージョン情報
		$file_data      = get_file_data( __FILE__, [ 'version' => 'Version' ] );
		self::$version  = $file_data['version'];
		self::$file_ver = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? wp_date( 'mdGis' ) : self::$version;

		// 翻訳ファイルを登録
		load_plugin_textdomain( 'arkhe-css-editor', false, ARK_CSS_EDIT_PATH . 'languages' );

		// 実行ファイル読み込み
		require_once ARK_CSS_EDIT_PATH . 'inc/enqueues.php';
		require_once ARK_CSS_EDIT_PATH . 'inc/output.php';
		require_once ARK_CSS_EDIT_PATH . 'inc/register_settings.php';

		if ( is_admin() ) {
			require_once ARK_CSS_EDIT_PATH . 'inc/menu.php';
			require_once ARK_CSS_EDIT_PATH . 'inc/meta_post.php';
			require_once ARK_CSS_EDIT_PATH . 'inc/update.php';
		}
	}
}

/**
 * plugins_loaded
 */
add_action( 'plugins_loaded', function() {
	new Arkhe_CSS_Editor();
}, 11 );
