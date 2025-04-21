<?php
namespace Arkhe_Toolkit;

add_action( 'wp_footer', '\Arkhe_Toolkit\output_json_ld', 20 );


/**
 * wp_footerで出力するコード 優先度:20
 */
function output_json_ld() {

	// JSON LD
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_jsonld' ) ) {
		$json_lds = \Arkhe_Toolkit\get_json_ld_data();
		if ( ! empty( $json_lds ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<script type="application/ld+json">[' . implode( ',', $json_lds ) . ']</script>' . PHP_EOL;
		}
	}
}

/**
 * JSON-LDの生成
 */
function get_json_ld_data() {

	$json_lds = [];

	if ( is_front_page() ) {

		// トップページ
		$home_url            = home_url( '/' );
		$json_lds['WebSite'] = '{
			"@context": "http://schema.org",
			"@type": "WebSite",
			"url": "' . esc_url( $home_url ) . '",
			"potentialAction": {
			"@type": "SearchAction",
			"target": "' . esc_html( $home_url ) . '?s={s}",
			"query-input": "name=s required"
			}
		}';
		// memo : SearchAction{s}は検索フォームのnameに合わせる

	} elseif ( is_single() || is_page() || is_home() ) {

		// 記事系ページ
		$article_json = \Arkhe_Toolkit\get_article_json_data();
		if ( $article_json ) {
			$json_lds['Article'] = $article_json;
		}
	}

	// グローバルナビ（SiteNavigationElement）
	// if ( \Arkhe_Toolkit::get_data( 'extension', 'use_gnav_json' ) ) {
	// 	$gnav_json = \Arkhe_Toolkit\get_gnav_json_data();
	// 	if ( $gnav_json ) {
	// 		$json_lds['SiteNavigationElement'] = $gnav_json;
	// 	}
	// }

	// パンくずリスト（BreadcrumbList）

	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_bread_json' ) ) {
		$bread_json = \Arkhe_Toolkit\get_bread_json_data();
		if ( $bread_json ) {
			$json_lds['BreadcrumbList'] = $bread_json;
		}
	}

	return apply_filters( 'arkhe_toolkit_json_ld', $json_lds );
}


/**
 * 投稿ページ用のJSON-LDの生成
 */
function get_article_json_data() {

	$post_data = get_queried_object();
	if ( empty( $post_data ) ) return '';

	$the_id      = $post_data->ID;
	$author_data = get_userdata( $post_data->post_author );

	// サイトロゴ
	$logo_id  = get_theme_mod( 'custom_logo' ) ?: 0;
	$logo_url = wp_get_attachment_image_url( $logo_id, 'large' ) ?: '';

	$description = '';
	if ( class_exists( '\SSP_Output' ) && method_exists( '\SSP_Output', 'get_meta_data' ) ) {
		$description = \SSP_Output::get_meta_data( 'description' ) ?: '';
	} else {
		$description = $post_data->post_content;
		$description = wp_strip_all_tags( strip_shortcodes( $description ) );
		$description = mb_substr( $description, 0, 120 );
	}

	// フックで調整可能にするデータ群
	$data = [
		'url'                => get_permalink( $the_id ) ?: '',
		'headline'           => wp_strip_all_tags( get_the_title( $the_id ) ) ?: '',
		'image_url'          => get_the_post_thumbnail_url( $the_id, 'large' ) ?: ARKHE_NOIMG_URL,
		'author_name'        => $author_data->display_name ?: '',
		'publisher_name'     => get_option( 'blogname' ) ?: '',
		'publisher_logo_url' => $logo_url,
		'description'        => $description,
	];
	$data = apply_filters( 'arkhe_toolkit_json_ld_article', $data, $the_id );

	// publisher の logo が必須値なので、なければ出力しない。
	if ( ! $data['publisher_logo_url'] ) return '';

	$json = '{
		"@context": "http://schema.org",
		"@type": "Article",
		"mainEntityOfPage":{
			"@type":"WebPage",
			"@id":"' . esc_url( $data['url'] ) . '"
		},
		"headline":"' . esc_html( $data['headline'] ) . '",
		"image": {
			"@type": "ImageObject",
			"url": "' . esc_url( $data['image_url'] ) . '"
		},
		"datePublished": "' . esc_html( $post_data->post_date ) . '",
		"dateModified": "' . esc_html( $post_data->post_modified ) . '",
		"author": {
			"@type": "Person",
			"name": "' . esc_html( $data['author_name'] ) . '"
		},
		"publisher": {
			"@type": "Organization",
			"name": "' . esc_html( $data['publisher_name'] ) . '",
			"logo": {
				"@type": "ImageObject",
				"url": "' . esc_url( $data['publisher_logo_url'] ) . '"
			}
		},
		"description": "' . esc_html( $data['description'] ) . '"
	}';

	return $json;
}


/**
 * グローバルナビ用のJSON-LDの生成
 * ※ SiteNavigationElement は リッチリザルト に適用はされないのでオフにしている
 */
function get_gnav_json_data() {
	// 各ロケーションにセットされているメニューIDを取得
	$locations = get_nav_menu_locations();
	if ( ! isset( $locations['header_menu'] ) ) return '';

	// wp_get_nav_menu_items()は ロケーション名から取得できないので、IDから取得。
	$gnav_id    = $locations['header_menu'];
	$gnav       = wp_get_nav_menu_object( $gnav_id ); // gnav自体情報を取得（WP_Term）
	$gnav_items = wp_get_nav_menu_items( $gnav_id ) ?: []; // gnavの各メニュー項目の情報を取得

	if ( empty( $gnav_items ) ) return '';

	$gnav_names = '';
	$gnav_urls  = '';
	foreach ( $gnav_items as $key => $menu_item ) {
		$menu_title  = wp_strip_all_tags( strip_shortcodes( $menu_item->title ) );
		$gnav_names .= '"' . esc_html( $menu_title ) . '",'; // esc_はここでやらないと " が &quot; になる
		$gnav_urls  .= '"' . esc_url( $menu_item->url ) . '",';
	}

	$gnav_json = '{
		"@context": "http://schema.org",
		"@type": "SiteNavigationElement",
		"name": [' . rtrim( $gnav_names, ',' ) . '],
		"url": [' . rtrim( $gnav_urls, ',' ) . ']
	}';
	return $gnav_json;

}


/**
 * パンくずリスト用のJSON-LDの生成
 */
function get_bread_json_data() {

	if ( ! class_exists( 'Arkhe' ) ) return '';
	if ( empty( \Arkhe::$bread_json_data ) ) return '';

	// 各要素の部分を生成
	$pos       = 1;
	$item_json = '';
	foreach ( \Arkhe::$bread_json_data as $data ) :
		$item_json .= '{' .
		'"@type": "ListItem","position": ' . $pos . ',' .
		'"item": {' .
			'"@id": "' . esc_url( $data['url'] ) . '",' .
			'"name": "' . esc_html( wp_strip_all_tags( $data['name'] ) ) . '"' .
			'}' .
		'},';
		++$pos;
	endforeach;

	// 全体
	$bread_json = '{
		"@context": "http://schema.org",
		"@type": "BreadcrumbList",
		"itemListElement": [' . rtrim( $item_json, ',' ) . ']
	}';
	return $bread_json;

}
