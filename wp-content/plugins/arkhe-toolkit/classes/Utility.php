<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;

trait Utility {

	/**
	 * $use の値を取得
	 */
	public static function is_use( $key ) {
		if ( ! isset( self::$use[ $key ] ) ) {
			return false;
		}
		return self::$use[ $key ];
	}


	/**
	 * $use の値を取得
	 */
	public static function set_use( $key, $val ) {
		self::$use[ $key ] = $val;
	}


	/**
	 * 設定メニューの項目を追加
	 */
	public static function add_menu_section( $args ) {

		extract( array_merge( [
			'title'      => '',
			'key'        => '',
			'section_cb' => '',
			'page_name'  => '',
			'page_cb'    => '',
			'db'         => '',
		], $args ) );

		$section_name = 'arkhe_' . $key . '_section';

		add_settings_section(
			$section_name,
			$title,
			$section_cb,
			$page_name
		);

		// コールバック関数の指定が特になければ、ファイルを読み込む
		$page_cb = $page_cb ?: function( $cb_args ) {
			include ARKHE_TOOLKIT_PATH . 'inc/admin_menu/tabs/' . $cb_args['db'] . '/' . $cb_args['section_key'] . '.php';
		};

		add_settings_field(
			$section_name . '_fields',
			'',
			$page_cb,
			$page_name,
			$section_name,
			[
				'db'          => $db,
				'section_key' => $key,
			]
		);
	}


	/**
	 * キャッシュの取得 & 生成
	 */
	public static function cache_the_part( $path_key, $include_path, $args, $cache_key = '', $days = 30 ) {

		if ( '' === $cache_key || is_customize_preview() ) return null; // null 以外だとそれが出力されるので注意。

		// キャッシュの取得
		$cache_data = get_transient( $cache_key );

		// キャッシュあればすぐ返す
		if ( ! empty( $cache_data ) ) return $cache_data;

		// キャッシュが消えていれば生成
		ob_start();
		\Arkhe::the_part_content( $path_key, $include_path, $args );
		$cache_data = \Arkhe_Toolkit::minify_html_code( ob_get_clean() );

		// キャッシュ保存期間
		$expiration = 30 * DAY_IN_SECONDS;

		// キャッシュデータの生成
		set_transient( $cache_key, $cache_data, $expiration );

		// 生成したキャッシュデータを返す
		return $cache_data;
	}


	/**
	 * キャッシュのクリア
	 */
	public static function clear_cache( $cache_keys = [] ) {

		// キャッシュキーの指定がなければ全てのキーを取得
		if ( [] === $cache_keys ) {
			self::clear_part_cache_all();
		}

		// 指定されたキャッシュキーを順に削除
		foreach ( $cache_keys as $key ) {
			delete_transient( $key );
		}
	}


	/**
	 * パーツキャッシュの全削除
	 */
	public static function clear_part_cache_all() {
		global $wpdb;
		$option_table = "{$wpdb->prefix}options";
		$prefix       = self::CACHE_PREFIX;

		// phpcs:ignore
		$wpdb->query( "DELETE FROM $option_table WHERE (`option_name` LIKE '%_transient_{$prefix}_%') OR (`option_name` LIKE '%_transient_timeout_{$prefix}_%')" );
	}


	/**
	 * AJAXのNonceチェック
	 */
	public static function check_ajax_nonce( $request_key = 'nonce', $nonce_key = 'arkhe-toolkit-ajax-nonce' ) {
		if ( ! isset( $_POST[ $request_key ] ) ) return false;

		$nonce = $_POST[ $request_key ]; // phpcs:ignore

		if ( wp_verify_nonce( $nonce, $nonce_key ) ) {
			return true;
		}

		return false;
	}


	/**
	 * HTMLソースのminify化
	 */
	public static function minify_html_code( $src ) {
		$search  = [
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s',       // shorten multiple whitespace sequences
			'/<!--[\s\S]*?-->/s', // コメントを削除
		];
		$replace = [
			'>',
			'<',
			'\\1',
			'',
		];
		return preg_replace( $search, $replace, $src );
	}


	/**
	 * lazysizesを使うかどうか
	 */
	public static function is_use_lazysizes() {
		// Arkhe 1.9~
		if ( method_exists( '\Arkhe', 'get_lazy_type' ) ) {
			return 'lazysizes' === \Arkhe::get_lazy_type();
		}
		return false;
	}


	/**
	 * HTML属性値を配列から生成して出力
	 */
	public static function print_attrs_as_string( $attrs ) {
		$return = '';
		foreach ( $attrs as $key => $val ) {
			$key     = esc_attr( $key );
			$val     = esc_attr( $val );
			$return .= " $key=\"$val\"";
		}

		echo trim( $return ); // phpcs:ignore WordPress.Security.EscapeOutput
	}


	/**
	 * 文字列 $str の中に $keywords 内の文字列がひとつでも含まれるかどうかを調べる
	 *
	 * @param string $str
	 * @param array  $keywords
	 */
	public static function is_keyword_included( $str, $keywords ) {
		foreach ( $keywords as $keyword ) {
			if ( ! $keyword ) continue;
			if ( strpos( $str, $keyword ) !== false ) {
				return true;
			}
		}
		return false;
	}


	/**
	 * 文字列を改行または,で分割して配列化
	 */
	public static function keywords_to_array( $str ) {

		$str = trim( trim( $str ), ',' ); // 最初と最後の不要な空白・改行・「,」を削除
		$str = str_replace( [ "\r\n", "\r" ], "\n", $str ); // 改行コードの統一
		$str = str_replace( [ "\n" ], ',', $str ); // 改行を,へ変換

		// 「,」で分割し配列に変換
		$array = explode( ',', $str );
		$array = array_map( 'trim', $array ); // 各行にtrim()をかける
		$array = array_filter( $array ); // 空要素を削除
		$array = array_values( $array ); // 連番に振りなおし

		return $array;
	}

}
