<?php
/**
 * 各ページごとの表示設定の上書き
 */
namespace Arkhe_Toolkit\Hooks;

/**
 * タームページのリストレイアウト
 */
add_filter( 'arkhe_list_type_on_term', __NAMESPACE__ . '\hook__list_type_on_term', 10, 2 );
function hook__list_type_on_term( $layout, $term_id ) {
	$meta_layout = get_term_meta( $term_id, 'ark_meta_list_type', true );
	return $meta_layout ?: $layout;
}


/**
 * ページタイトルにサブタイトル追加
 */
add_filter( 'arkhe_page_subtitle', __NAMESPACE__ . '\hook__page_subtitle', 10, 2 );
function hook__page_subtitle( $subtitle, $page_id ) {
	$meta_subtitle = get_post_meta( $page_id, 'ark_meta_subttl', true ) ?: '';
	return $meta_subtitle ?: $subtitle;
}


/**
 * タイトル背景画像
 */
add_filter( 'arkhe_ttlbg_img_id', __NAMESPACE__ . '\hook__ttlbg_img_id', 10, 2 );
function hook__ttlbg_img_id( $img_id, $page_id ) {
	if ( is_category() || is_tag() || is_tax() ) {
		$meta_id = get_term_meta( $page_id, 'ark_meta_ttlbg', true );
	} else {
		$meta_id = get_post_meta( $page_id, 'ark_meta_ttlbg', true );
	}
	return $meta_id ?: $img_id;
}


/**
 * 上部タイトルエリアに表示する抜粋分
*/
add_filter( 'arkhe_top_area_excerpt', __NAMESPACE__ . '\hook__top_area_excerpt', 10, 2 );
function hook__top_area_excerpt( $excerpt, $the_id ) {
	$show_excerpt = get_post_meta( $the_id, 'ark_meta_show_excerpt', true );
	if ( $show_excerpt ) {
		$post_data = get_post( $the_id );
		$excerpt   = ! empty( $post_data ) ? $post_data->post_excerpt : '';
	}
	return $excerpt;
}


/**
 * アイキャッチ画像
 */
add_filter( 'arkhe_show_entry_thumb', __NAMESPACE__ . '\hook__show_entry_thumb', 10, 2 );
function hook__show_entry_thumb( $show, $page_id ) {
	$meta = get_post_meta( $page_id, 'ark_meta_show_thumb', true );

	if ( 'show' === $meta ) {
		return true;
	} elseif ( 'hide' === $meta ) {
		return false;
	}
	return $show; // デフォルトの設定
}


/**
 * 著者情報
 */
add_filter( 'arkhe_show_author_box', function( $show, $page_id ) {
	$meta = get_post_meta( $page_id, 'ark_meta_show_author', true );
	if ( 'show' === $meta ) {
		return true;
	} elseif ( 'hide' === $meta ) {
		return false;
	}
	return $show;
}, 10, 2 );


/**
 * 関連記事
 */
add_filter( 'arkhe_show_related_posts', function( $show, $page_id ) {
	$meta = get_post_meta( $page_id, 'ark_meta_show_related', true );
	if ( 'show' === $meta ) {
		return true;
	} elseif ( 'hide' === $meta ) {
		return false;
	}
	return $show;
}, 10, 2 );


/**
 * その他、wp フックで管理するもの
 */
add_action( 'wp', function() {
	$the_id = get_queried_object_id();

	// タイトル位置
	\Arkhe_Toolkit\Hooks\set_ttlpos( $the_id );

	if ( is_single() ) {
		\Arkhe_Toolkit\Hooks\set_share_btns( $the_id );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		\Arkhe_Toolkit\Hooks\set_term_parts( $the_id );
	}
} );


/**
 * シェアボタン
 */
function set_share_btns( $the_id ) {
	$meta    = get_post_meta( $the_id, 'ark_meta_hide_sharebtns', true );
	$is_hide = apply_filters( 'arkhe_toolkit_hide_share_btns', '1' === $meta, $the_id );
	if ( ! $is_hide ) {
		if ( \Arkhe_Toolkit::get_data( 'customizer', 'show_sharebtns_top' ) ) {
			add_action( 'arkhe_before_entry_content', __NAMESPACE__ . '\add_share_btns__top', 9 );
		}
		if ( \Arkhe_Toolkit::get_data( 'customizer', 'show_sharebtns_bottom' ) ) {
			add_action( 'arkhe_after_entry_content', __NAMESPACE__ . '\add_share_btns__bottom', 9 );
		}
	}
}
function add_share_btns__top() {
	\Arkhe_Toolkit::get_part( 'share_btns', [ 'position' => 'top' ] );
}
function add_share_btns__bottom() {
	\Arkhe_Toolkit::get_part( 'share_btns', [ 'position' => 'bottom' ] );
}


/**
 * タームページの個別設定
 */
function set_term_parts( $term_id ) {

	// タームの説明文を表示するかどうか
	$show_desc = get_term_meta( $term_id, 'ark_meta_show_desc', true );
	if ( '0' === $show_desc ) {
		add_filter( 'arkhe_show_term_description', '__return_false' );
	}

	// サイドバーを表示するかどうか
	$show_sidebar = get_term_meta( $term_id, 'ark_meta_show_sidebar', true );
	if ( 'show' === $show_sidebar ) {
		add_filter( 'arkhe_is_show_sidebar', '__return_true' );
	} elseif ( 'hide' === $show_sidebar ) {
		add_filter( 'arkhe_is_show_sidebar', '__return_false' );
	}
}


/**
 * タイトル表示位置
 */
function set_ttlpos( $the_id ) {

	$ttlpos = '';
	if ( is_single() || is_page() || is_home() ) {
		$ttlpos = get_post_meta( $the_id, 'ark_meta_ttlpos', true );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$ttlpos = get_term_meta( $the_id, 'ark_meta_ttlpos', true );
	}

	if ( 'none' === $ttlpos ) {
		add_filter( 'arkhe_is_show_ttltop', '__return_false' );

		// for ~arkhe1.4
		add_filter( 'arkhe_part_cache__page/head', function() {
			return '<!-- The title is hidden -->';
		} );
		// for arkhe1.5~
		add_filter( 'arkhe_part_cache__page/title', function() {
			return '<!-- The title is hidden -->';
		} );

	} elseif ( 'top' === $ttlpos ) {
		add_filter( 'arkhe_is_show_ttltop', '__return_true' );
	} elseif ( 'inner' === $ttlpos ) {
		add_filter( 'arkhe_is_show_ttltop', '__return_false' );
	}
}
