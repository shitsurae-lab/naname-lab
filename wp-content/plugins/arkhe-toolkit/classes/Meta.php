<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;

trait Meta {

	// phpcs:disable WordPress.Security.NonceVerification
	// phpcs:disable WordPress.Security.ValidatedSanitizedInput

	/**
	 * meta保存時の nonceチェックなど
	 */
	public static function can_save_meta( $nonce = '' ) {

		// $_POSTチェック
		if ( empty( $_POST ) || ! isset( $_POST[ $nonce ] ) ) return false;

		// 自動保存時には保存しないように
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;

		// nonceキーチェック
		if ( ! wp_verify_nonce( $_POST[ $nonce ], $nonce ) ) return false;

		return true;
	}


	/**
	 * post meta 保存処理
	 */
	public static function save_post_metas( $post_id, $metas ) {

		if ( ! is_array( $metas ) ) return;

		foreach ( $metas as $key => $type ) {

			$meta_val = $_POST[ $key ] ?? '';

			// 保存したい情報が渡ってきていれば更新作業に入る
			if ( ! $key || ! isset( $_POST[ $key ] ) ) continue;

			$is_null = false;

			// サニタイズとnullチェック
			if ( 'check' === $type ) {
				$meta_val = sanitize_text_field( $meta_val );
				$is_null  = '0' === $meta_val;
			} elseif ( 'code' === $type ) {
				// $meta_val = $meta_val;
				$is_null = '' === $meta_val;
			} else {
				$meta_val = sanitize_text_field( $meta_val );
				$is_null  = '' === $meta_val;
			}

			if ( $is_null ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, $meta_val );
			}
		}
	}


	/**
	 * term meta 保存処理
	 */
	public static function save_term_metas( $term_id, $metas ) {

		if ( ! is_array( $metas ) ) return;

		foreach ( $metas as $key => $type ) {

			$meta_val = $_POST[ $key ] ?? '';

			// 保存したい情報が渡ってきていれば更新作業に入る
			if ( ! $key || ! isset( $_POST[ $key ] ) ) continue;

			$is_null = false;

			// サニタイズとnullチェック
			if ( 'check' === $type ) {
				$meta_val = sanitize_text_field( $meta_val );
				$is_null  = '0' === $meta_val;
			} elseif ( 'check--on' === $type ) {
				$meta_val = sanitize_text_field( $meta_val );
				$is_null  = '1' === $meta_val;
			} else {
				$meta_val = sanitize_text_field( $meta_val );
				$is_null  = '' === $meta_val;
			}

			if ( $is_null ) {
				delete_term_meta( $term_id, $key );
			} else {
				update_term_meta( $term_id, $key, $meta_val );
			}
		}
	}

	// phpcs:enable WordPress.Security.NonceVerification
	// phpcs:enable WordPress.Security.ValidatedSanitizedInput

}
