<?php
namespace Arkhe_Toolkit\Meta;

defined( 'ABSPATH' ) || exit;

/*
 * ターム「編集」画面にフィールド追加
 */
add_action( 'init', function() {
	$custom_taxonomies = get_taxonomies( [
		'public'   => true,
		'_builtin' => false,
	]) ?: [];

	$term_meta_screens = array_merge( [ 'category', 'post_tag' ], array_keys( $custom_taxonomies ) );
	$term_meta_screens = apply_filters( 'arkhe_toolkit_term_meta_screens', $term_meta_screens );
	foreach ( $term_meta_screens as $tax_slug ) {
		add_action( "{$tax_slug}_edit_form_fields", __NAMESPACE__ . '\add_term_metas' );
	}

}, 99 );

function add_term_metas( $term ) {
	if ( ! class_exists( '\Arkhe' ) ) return;

	$the_term_id = $term->term_id;

	// nonce
	wp_nonce_field( 'arkhe_nonce_term_meta', 'arkhe_nonce_term_meta' );
?>
	<tr class="ark-termMetaTitle">
		<th colspan="2">
			<h2>
				<span style="position:relative;top:4px">
					<?php \Arkhe_Toolkit::the_svg( 'arkhe-logo', [ 'size' => '1.25em' ] ); ?>
				</span>
				Arkhe Toolkit
			</h2>
		</th>
	</tr>
	<?php if ( ! isset( \Arkhe::$list_layouts ) ) : ?>
		<tr>
			<th></th>
			<td>※ <?=esc_html__( 'Please update the version of "Arkhe".', 'arkhe-toolkit' )?></td>
		</tr>
	<?php else : ?>
		<tr class="form-field">
			<th></th>
			<td>
				<?php
					$label    = __( 'Show "Description"', 'arkhe-toolkit' );
					$meta_val = get_term_meta( $the_term_id, 'ark_meta_show_desc', 1 );

					// 初期状態：オン
					if ( '' === $meta_val ) $meta_val = '1';

					\Arkhe_Toolkit::meta_checkbox( 'ark_meta_show_desc', $label, $meta_val, true );
				?>
			</td>
		</tr>
		<tr class="form-field">
			<th><?=esc_html__( 'List layout', 'arkhe-toolkit' )?></th>
			<td>
				<?php
					$default  = __( 'Follow base settings', 'arkhe-toolkit' );
					$meta_val = get_term_meta( $the_term_id, 'ark_meta_list_type', 1 );
					$layouts  = method_exists( 'Arkhe', 'get_list_layouts' ) ? \Arkhe::get_list_layouts() : \Arkhe::$list_layouts;
					\Arkhe_Toolkit::meta_select( 'ark_meta_list_type', $layouts, $meta_val, $default );
				?>
			</td>
		</tr>
		<tr class="form-field">
			<th><?=esc_html__( 'Sidebar', 'arkhe-toolkit' )?></th>
			<td>
				<?php
					$meta_val             = get_term_meta( $the_term_id, 'ark_meta_show_sidebar', 1 );
					$show_or_hide_options = [
						'show' => _x( 'Show', 'show', 'arkhe-toolkit' ),
						'hide' => _x( 'Hide', 'show', 'arkhe-toolkit' ),
					];
					\Arkhe_Toolkit::meta_select( 'ark_meta_show_sidebar', $show_or_hide_options, $meta_val, $default );
				?>
			</td>
		</tr>
		<tr class="form-field">
			<th><?=esc_html__( 'Title position', 'arkhe-toolkit' )?></th>
			<td>
				<?php
					// タームでは none は選択肢にいれない。
					$options  = [
						'top'   => __( 'Above the content', 'arkhe-toolkit' ),
						'inner' => __( 'Inside the content', 'arkhe-toolkit' ),
					];
					$meta_val = get_term_meta( $the_term_id, 'ark_meta_ttlpos', 1 );
					\Arkhe_Toolkit::meta_select( 'ark_meta_ttlpos', $options, $meta_val, $default );
				?>
			</td>
		</tr>
		<tr class="form-field">
			<th><?=esc_html__( 'Background image for title', 'arkhe-toolkit' )?></th>
			<td>
				<?php
					$meta_val = get_term_meta( $the_term_id, 'ark_meta_ttlbg', 1 );
					\Arkhe_Toolkit::media_btns( 'ark_meta_ttlbg', $meta_val );
				?>
			</td>
		</tr>
	<?php endif; ?>
<?php

}

// 保存処理
add_action( 'edited_terms', __NAMESPACE__ . '\save_term_metas' );
function save_term_metas( $term_id ) {

	if ( ! \Arkhe_Toolkit::can_save_meta( 'arkhe_nonce_term_meta' ) ) return;

	\Arkhe_Toolkit::save_term_metas( $term_id, [
		'ark_meta_show_desc'          => 'check_on',
		'ark_meta_list_type'          => 'str',
		'ark_meta_ttlpos'             => 'str',
		'ark_meta_ttlbg'              => 'str',
		'ark_meta_show_sidebar'       => 'str',
	] );
}
