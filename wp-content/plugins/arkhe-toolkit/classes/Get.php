<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;

trait Get {

	/**
	 * 拡張テンプレートの読み込み
	 */
	public static function get_part( $slug, $args = null ) {
		\Arkhe::$ex_parts_path = ARKHE_TOOLKIT_PATH;
		\Arkhe::get_part( $slug, $args );
		\Arkhe::$ex_parts_path = '';
	}


	/**
	 * ページ種別判定用のスラッグを取得
	 */
	public static function get_page_type() {
		if ( is_front_page() ) {
			return 'front';
		} elseif ( is_page() || is_home() ) {
			return 'page';
		} elseif ( is_single() ) {
			return 'single';
		}
		return 'other';
	}


	/**
	 * シェアボタンのリストを取得
	 */
	public static function get_share_btns_list( $the_id, $share_url, $share_title ) {

		$share_btns = [
			'facebook' => [
				'check_key'   => 'show_share_fb',
				'title'       => __( 'Share on Facebook', 'arkhe-toolkit' ),
				'href'        => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $share_url ),
				'window_size' => 'height=800,width=600',
			],
			'twitter' => [
				'check_key'   => 'show_share_tw',
				'title'       => __( 'Share on Twitter', 'arkhe-toolkit' ),
				'href'        => 'https://twitter.com/share?',
				'window_size' => 'height=400,width=600',
				'querys'      => [
					'url'  => $share_url,
					'text' => $share_title,
				],
			],
			'hatebu' => [
				'check_key'   => 'show_share_hatebu',
				'title'       => __( 'Register in Hatena Bookmark', 'arkhe-toolkit' ),
				'href'        => '//b.hatena.ne.jp/add?mode=confirm&url=' . urlencode( $share_url ),
				'window_size' => 'height=600,width=1000',
			],
			'pocket' => [
				'check_key' => 'show_share_pocket',
				'title'     => __( 'Save to Pocket', 'arkhe-toolkit' ),
				'href'      => 'https://getpocket.com/edit?',
				'querys'    => [
					'url'   => $share_url,
					'title' => $share_title,
				],
			],
			'pinterest' => [
				'check_key' => 'show_share_pin',
				'title'     => __( 'Save pin', 'arkhe-toolkit' ),
				'href'      => 'https://jp.pinterest.com/pin/create/button/',
				'attrs'     => [
					'data-pin-do'     => 'buttonBookmark',
					'data-pin-custom' => 'true',
					'data-pin-lang'   => 'ja',
				],
			],
			'line' => [
				'check_key' => 'show_share_line',
				'title'     => __( 'Send to LINE', 'arkhe-toolkit' ),
				'href'      => 'https://social-plugins.line.me/lineit/share?',
				'querys'    => [
					'url'   => $share_url,
					'text'  => $share_title,
				],
			],
		];

		// 各ボタンを表示するかどうかチェック
		foreach ( $share_btns as $key => $data ) {
			if ( ! \Arkhe_Toolkit::get_data( 'customizer', $data['check_key'] ) ) {
				unset( $share_btns[ $key ] );
			}
			unset( $share_btns[ $key ]['check_key'] );
		}

		return apply_filters( 'arkhe_toolkit_get_share_btns_list', $share_btns, $the_id );
	}


	/**
	 * シェアボタンの属性値を生成
	 */
	public static function generate_share_btn_attrs( $data ) {
		// ボタンのhref 生成
		$href = $data['href'] ?? '';
		if ( isset( $data['querys'] ) ) {
			$href .= http_build_query( $data['querys'], '', '&' );
		}

		// ボタンの属性
		$btn_attrs = [
			'href'    => $href,
			'title'   => $data['title'],
			'target'  => '_blank',
			'rel'     => 'noopener noreferrer',
			'role'    => 'button',
		];

		// onclick属性
		if ( isset( $data['window_size'] ) ) {
			$window_size          = $data['window_size'];
			$btn_attrs['onclick'] = "javascript:window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,${window_size}');return false;";
		}

		// 他に追加の属性がある場合
		if ( isset( $data['attrs'] ) ) {
			$btn_attrs = array_merge( $btn_attrs, $data['attrs'] );
		}

		return $btn_attrs;
	}


	/**
	 * ファイルURLから縦横サイズを取得
	 */
	// public static function get_media_px_size( $file_url ) {

	// 	// ファイル名にサイズがあればそれを返す
	// 	preg_match( '/-([0-9]*)x([0-9]*)\./', $file_url, $matches );
	// 	if ( ! empty( $matches ) ) {
	// 		return [
	// 			'width'  => $matches[1],
	// 			'height' => $matches[2],
	// 		];
	// 	}

	// 	// フルサイズの時
	// 	$file_id   = attachment_url_to_postid( $file_url );
	// 	$file_data = wp_get_attachment_metadata( $file_id );
	// 	if ( ! empty( $file_data ) ) {
	// 		return [
	// 			'width'  => $file_data['width'],
	// 			'height' => $file_data['height'],
	// 		];
	// 	}

	// 	// サイズが取得できなかった場合
	// 	return false;
	// }

}
