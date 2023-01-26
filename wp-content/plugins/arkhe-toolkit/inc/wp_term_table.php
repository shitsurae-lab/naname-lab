<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;
/**
 * 管理画面のターム一覧テーブルの表示をカスタマイズする
 */

add_filter( 'manage_edit-category_columns', '\Arkhe_Toolkit\add_term_columns' );
add_filter( 'manage_edit-post_tag_columns', '\Arkhe_Toolkit\add_term_columns' );
add_filter( 'manage_category_custom_column', '\Arkhe_Toolkit\output_term_columns', 10, 3 );
add_filter( 'manage_post_tag_custom_column', '\Arkhe_Toolkit\output_term_columns', 10, 3 );


/**
 * カテゴリー・タグ一覧テーブルに IDのカラム 追加
 */
function add_term_columns( $columns ) {
	$columns['term_id'] = 'ID';
	return $columns;
}


/**
 * IDカラムの出力
 */
function output_term_columns( $content, $column_name, $term_id ) {
	if ( 'term_id' === $column_name ) {
		$content = $term_id;
	}
	return $content;
}
