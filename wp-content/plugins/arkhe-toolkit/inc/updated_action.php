<?php
/**
 * アップデートチェック
 */
namespace Arkhe_Toolkit\Updated_Action;

defined( 'ABSPATH' ) || exit;

/**
 * バージョン更新時の処理
 *
 * @since 1.6.0
 */
add_action( 'init', __NAMESPACE__ . '\updated_hook', 1 );
function updated_hook() {

	$now_ver = \Arkhe_Toolkit::$version;
	$old_ver = get_option( 'arkhe_toolkit_version' ); // データベースに保存されているバージョン

	if ( false === $old_ver ) {
		// まだバージョン情報が記憶されていなければ

		\Arkhe_Toolkit\Updated_Action\updated_db__common( $now_ver );
		\Arkhe_Toolkit\Updated_Action\updated_db__v16( $now_ver );

	} elseif ( $old_ver !== $now_ver ) {
		// バージョンアップ時の処理

		\Arkhe_Toolkit\Updated_Action\updated_db__common( $now_ver );
		// \Arkhe_Toolkit\Updated_Action\updated_db__updated();

		// 特定のバージョンより古いとこからアップデートされた時に処理する
		// if ( version_compare( $old_ver, '1.6.0', '<' ) ) {}
	}
}

function updated_db__common( $now_ver ) {

	// 現在のバージョンをDBに記憶
	update_option( 'arkhe_toolkit_version', $now_ver );
}

/**
 * updated_action 初期実装後に走らせたい処理。
 */
function updated_db__v16() {
	// 不要なメタデータを削除
	\Arkhe_Toolkit\Updated_Action\clean_post_metas();
	\Arkhe_Toolkit\Updated_Action\clean_term_metas();

	// 保存先の移行
	\Arkhe_Toolkit\Updated_Action\change_saved_options( [
		[
			'key'      => 'use_prefetch',
			'old_name' => 'extension',
			'new_name' => 'speed',
		],
		[
			'key'      => 'prefetch_prevent_keys',
			'old_name' => 'extension',
			'new_name' => 'speed',
		],
	] );

	// use_lazysizes → lazy_type の処理
	$extension_data = get_option( \Arkhe_Toolkit::DB_NAMES['extension'] );
	if ( isset( $extension_data['use_lazysizes'] ) ) {

		// オフになっていた時のみ、lazy_typeをセット
		if ( '' === $extension_data['use_lazysizes'] ) {
			\Arkhe_Toolkit::update_data( 'speed', 'lazy_type', 'lazy' );
		}

		// 'use_lazysizes' 削除
		\Arkhe_Toolkit::delete_data( 'extension', 'use_lazysizes' );
	}
}


/**
 * データ移行
 */
function change_saved_options( $options_to_be_changed ) {

	$toolkit_options = [
		'extension' => get_option( \Arkhe_Toolkit::DB_NAMES['extension'] ),
		'speed'     => get_option( \Arkhe_Toolkit::DB_NAMES['speed'] ),
	];

	foreach ( $options_to_be_changed as $opt ) {
		if ( '' === $opt['new_name'] ) {
			// 単純に削除したいデータ
			\Arkhe_Toolkit::delete_data( $opt['old_name'], $opt['key'] );

		} else {
			// 別のレコードへ移動させたい

			$value = $toolkit_options[ $opt['old_name'] ][ $opt['key'] ] ?? null;
			if ( null !== $value ) {
				// 新しい方へ保存
				\Arkhe_Toolkit::update_data( $opt['new_name'], $opt['key'], $value );

				// 古い方を削除
				\Arkhe_Toolkit::delete_data( $opt['old_name'], $opt['key'] );
			}
		}
	}
}

/**
 * 不要な post meta を削除
 */
function clean_post_metas() {
	$CLEAN_META_KEYS = [
		'ark_meta_subttl'             => '',
		'ark_meta_ttlbg'              => '',
		'ark_meta_ttlpos'             => '',
		'ark_meta_show_thumb'         => '',
		'ark_meta_show_related'       => '',
		'ark_meta_show_author'        => '',
		'ark_meta_hide_widget_top'    => '0',
		'ark_meta_hide_widget_bottom' => '0',
		'ark_meta_show_excerpt'       => '0',
	];

	global $wpdb;
	foreach ( $CLEAN_META_KEYS as $key => $null_val ) {
		// phpcs:disable WordPress.DB
		$wpdb->delete( $wpdb->postmeta, [
			'meta_key'   => $key,
			'meta_value' => $null_val,
		] );
		// phpcs:enable WordPress.DB
	}
}


/**
 * 不要な term meta を削除
 */
function clean_term_metas() {
	$CLEAN_META_KEYS = [
		'ark_meta_show_desc'          => '1',
		'ark_meta_list_type'          => '',
		'ark_meta_ttlpos'             => '',
		'ark_meta_ttlbg'              => '',
		'ark_meta_show_sidebar'       => '',
	];

	global $wpdb;
	foreach ( $CLEAN_META_KEYS as $key => $null_val ) {
		// phpcs:disable WordPress.DB
		$wpdb->delete( $wpdb->termmeta, [
			'meta_key'   => $key,
			'meta_value' => $null_val,
		] );
		// phpcs:enable WordPress.DB
	}
}
