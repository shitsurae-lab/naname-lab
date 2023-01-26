<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;

class Data {

	// private static $instance;

	// 設定データを保持する変数
	protected static $data     = [];
	protected static $defaults = [];

	// version
	public static $version  = '';
	public static $file_ver = '';

	// DB名
	// const DB_NAME_OPTIONS = 'arkhe_toolkit_options';
	const DB_NAMES = [
		'customizer' => 'arkhe_toolkit_customizer',
		'extension'  => 'arkhe_toolkit_extension',
		'speed'      => 'arkhe_toolkit_speed',
		'cache'      => 'arkhe_toolkit_cache',
		'code'       => 'arkhe_toolkit_code',
		'remove'     => 'arkhe_toolkit_remove',
	];

	// キャッシュキーのプレフィックス
	const CACHE_PREFIX = 'arkhe_parts';

	// メニューのページスラッグ
	const MENU_SLUG         = 'arkhe_toolkit_settings';
	const MENU_PAGE_PREFIX  = 'arkt_menu_page_';
	const MENU_GROUP_PREFIX = 'arkt_menu_group_';

	// メニューの設定タブ
	public static $menu_tabs = [];

	// JSの読み込みを制御する変数
	public static $use = [];

	// 外部からインスタンス化させない
	private function __construct() {}

	// init()
	public static function init() {
		add_action( 'after_setup_theme', [ __CLASS__, 'data_init' ], 9 );
		add_action( 'wp_loaded', [ __CLASS__, 'customizer_data_init' ] );
	}


	/**
	 * 設定データの初期セット
	 */
	public static function data_init() {

		// デフォルト値
		self::set_defaults();

		foreach ( self::DB_NAMES as $key => $db_name ) {
			if ( 'customizer' === $key ) continue;
			$db_data            = get_option( $db_name ) ?: [];
			self::$data[ $key ] = array_merge( self::$defaults[ $key ], $db_data );
		}
	}


	/**
	 * カスタマイザーデータの初期セット
	 */
	public static function customizer_data_init() {
		$db_data                  = get_option( self::DB_NAMES['customizer'] ) ?: [];
		self::$data['customizer'] = array_merge( self::$defaults['customizer'], $db_data );
	}


	/**
	 * デフォルト値をセット
	 */
	private static function set_defaults() {

		self::$defaults['customizer'] = [
			'drawer_move'           => 'fade',
			'header_above_drawer'   => false,

			// シェアボタン
			'show_sharebtns_top'    => false,
			'show_sharebtns_bottom' => true,
			'show_share_fb'         => true,
			'show_share_tw'         => true,
			'show_share_hatebu'     => true,
			'show_share_pocket'     => true,
			'show_share_pin'        => true,
			'show_share_line'       => true,
			'show_share_urlcopy'    => true,
		];

		self::$defaults['extension'] = [
			// ウィジェット
			'use_page_widget'   => '1',
			'use_post_widget'   => '1',
			'use_home_widget'   => '1',
			'use_fix_sidebar'   => '1',
			'use_before_footer' => '1',

			// コンテンツ
			'use_luminous'      => '1',
			'remove_emp_p'      => '1',

			// json-ld
			'use_jsonld'        => '1',
			'use_bread_json'    => '1',
			// 'use_gnav_json'  => '1',

			// user設定
			'use_user_urls'     => '1',
			'use_user_position' => '1',
		];

		// 1.6.0 ~
		self::$defaults['speed'] = [
			'lazy_type'              => 'lazysizes',
			'use_prefetch'           => '',
			'prefetch_prevent_keys'  => '',

			'use_delay_js'           => '',
			'delay_js_list'          => '' .
				'twitter.com/widgets.js' . PHP_EOL .
				'instagram.com/embed.js' . PHP_EOL .
				'connect.facebook.net' . PHP_EOL .
				'assets.pinterest.com' . PHP_EOL,
			'delay_js_prevent_pages' => '',
			'delay_js_time'          => '5',
		];

		self::$defaults['cache'] = [
			'cache_header'  => '',
			'cache_footer'  => '',
			'cache_sidebar' => '',
		];

		self::$defaults['code'] = [
			'head_code' => '',
			'body_code' => '',
			'foot_code' => '',
		];

		self::$defaults['remove'] = [
			'remove_wpver'         => '1',
			'remove_emoji'         => '1',
			'remove_core_patterns' => '',
			'remove_rel_link'      => '',
			'remove_wlwmanifest'   => '',
			'remove_rsd_link'      => '',
			'remove_self_ping'     => '',
			'remove_sitemap'       => '',
			'remove_rest_link'     => '',
			'remove_srcset'        => '',
			'remove_wptexturize'   => '',
			'remove_feed_link'     => '',
		];
	}


	/**
	 * 設定データのデフォルト値を取得
	 *   キーが指定されていればそれを、指定がなければ全てを返す。
	 */
	public static function get_default_data( $name_key = '', $key = '' ) {

		// DBのID名の指定なければ全部返す
		if ( '' === $name_key ) return self::$defaults;

		// DBのID名が存在しない時
		if ( ! isset( self::$defaults[ $name_key ] ) ) return null;

		// ID指定のみでキーの指定がない時
		if ( '' === $key ) return self::$defaults[ $name_key ];

		// 指定されたIDのデータの中に指定されたキーが存在しない時
		if ( ! isset( self::$defaults[ $name_key ][ $key ] ) ) return '';

		// id, key がちゃんとある時
		return self::$defaults[ $name_key ][ $key ];
	}


	/**
	 * 設定データ（デフォルト値とマージ後）を取得
	 */
	public static function get_data( $name_key = '', $key = '' ) {

		// DBのID名の指定なければ全部返す
		if ( '' === $name_key ) return self::$data;

		// DBのID名が存在しない時
		if ( ! isset( self::$data[ $name_key ] ) ) return null;

		// ID指定のみでキーの指定がない時
		if ( '' === $key ) return self::$data[ $name_key ];

		// 指定されたIDのデータの中に指定されたキーが存在しない時
		if ( ! isset( self::$data[ $name_key ][ $key ] ) ) return '';

		// id, key がちゃんとある時
		return self::$data[ $name_key ][ $key ];
	}


	/**
	 * 設定データを更新
	 */
	public static function update_data( $name_key = '', $key = '', $val = '' ) {
		if ( '' === $name_key || '' === $key ) return false;

		if ( ! isset( self::DB_NAMES[ $name_key ] )) return false;

		$data         = get_option( self::DB_NAMES[ $name_key ] ) ?: [];
		$data[ $key ] = $val;

		return update_option( self::DB_NAMES[ $name_key ], $data );
	}


	/**
	 * 設定データを強制セット(DB更新はしない)
	 */
	public static function set_data( $name_key = '', $key = '', $val = '' ) {
		if ( '' === $name_key || '' === $key ) return;

		// データのセット
		self::$data[ $name_key ][ $key ] = $val;

	}


	/**
	 * 設定データを削除
	 */
	public static function delete_data( $name_key = '', $key = '' ) {
		if ( '' === $name_key && '' === $key ) return false;

		if ( ! isset( self::DB_NAMES[ $name_key ] ) ) return false;

		$data = get_option( self::DB_NAMES[ $name_key ] ) ?: [];

		// 特定のキーを消すか、全部消すかで分岐
		if ( '' === $key ) {
			return delete_option( self::DB_NAMES[ $name_key ] );

		} elseif ( isset( $data[ $key ] ) ) {
			unset( $data[ $key ] );
			return update_option( self::DB_NAMES[ $name_key ], $data );
		}

		return false;
	}


	/**
	 * 設定データをリセット
	 */
	public static function reset_data( $id = '' ) {
		if ( $id ) {
			// 指定されたものだけ削除
			delete_option( \Arkhe_Toolkit::DB_NAMES[ $id ] );
		} else {
			// カスタマイザー以外全削除
			foreach ( \Arkhe_Toolkit::DB_NAMES as $key => $db_name ) {
				if ( 'customizer' === $key ) continue;
				delete_option( $db_name );
			}
		}
	}
}
