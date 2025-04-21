<?php
namespace Arkhe_Toolkit\Meta;

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', '\Arkhe_Toolkit\Meta\hook_add_meta_box', 5 );
add_action( 'save_post', '\Arkhe_Toolkit\Meta\hook_save_post' );


/**
 * add_meta_box()
 */
function hook_add_meta_box() {
	$custom_post_types = get_post_types( [
		'public'   => true,
		'_builtin' => false,
	] );

	$screens = array_merge( [ 'post', 'page' ], array_keys( $custom_post_types ) );

	add_meta_box(
		'arkhe_toolkit_meta__side',
		'Arkhe Toolkit',
		__NAMESPACE__ . '\arkt_cb_post_meta',
		$screens = apply_filters( 'arkhe_toolkit_side_meta_screens', $screens ),
		'side',
		'default',
		null
	);

	add_meta_box(
		'arkhe_toolkit_meta__code',
		'【Toolkit】' . __( 'Custom Codes', 'arkhe-toolkit' ),
		__NAMESPACE__ . '\arkt_cb_code_meta',
		$screens = apply_filters( 'arkhe_toolkit_code_meta_screens', $screens ),
		'normal',
		'high',
		null // $callback_args
	);
}


/**
 * 【Arkhe設定】
 */
function arkt_cb_post_meta( $post ) {
	$the_id    = $post->ID;
	$post_type = $post->post_type;
	$home_id   = (int) get_option( 'page_for_posts' );
	$is_home   = $home_id === $the_id;
	$is_page   = 'page' === $post_type;

	// nonce
	wp_nonce_field( 'arkhe_nonce_post_meta', '_ark_nonce' );

	$text_only_topttl = __( 'This is valid only when the title position is "Above the content".', 'arkhe-toolkit' );
?>
	<div id="arkhe_post_meta" class="ark-meta -side">
		<?php if ( $is_page ) : ?>

			<div class="ark-meta__item">
				<?php $meta_val = get_post_meta( $the_id, 'ark_meta_ttlbg', true ); ?>
				<label for="ark_meta_ttlbg" class="ark-meta__subttl">
					<?=esc_html__( 'Background image for title', 'arkhe-toolkit' )?>
				</label>
				<div class="ark-meta__field">
					<?php \Arkhe_Toolkit::media_btns( 'ark_meta_ttlbg', $meta_val ); ?>
				</div>
				<p class="ark-meta__desc">
					<?=esc_html( $text_only_topttl )?>
				</p>
			</div>

			<div class="ark-meta__item">
				<div class="ark-meta__subttl">
					<?=esc_html__( 'Subtitle', 'arkhe-toolkit' )?>
				</div>
				<div class="ark-meta__field">
					<?php
						$field_args = [
							'id'   => 'ark_meta_subttl',
							'meta' => get_post_meta( $the_id, 'ark_meta_subttl', true ),
						];
						\Arkhe_Toolkit::meta_text_input( $field_args );
					?>
				</div>
			</div>
		<?php endif; ?>

		<div class="ark-meta__item">
			<div class="ark-meta__subttl">
				<?=esc_html__( 'Display settings', 'arkhe-toolkit' )?>
			</div>
			<?php
				$show_or_hide_options = [
					'show' => _x( 'Show', 'show', 'arkhe-toolkit' ),
					'hide' => _x( 'Hide', 'show', 'arkhe-toolkit' ),
				];

				$meta_items = [];

				if ( $is_page ) :
					$meta_items['ark_meta_ttlpos'] = [
						'title'   => __( 'Title display', 'arkhe-toolkit' ),
						'options' => [
							'top'   => __( 'Above the content', 'arkhe-toolkit' ),
							'inner' => __( 'Inside the content', 'arkhe-toolkit' ),
							'none'  => __( 'Don\'t show', 'arkhe-toolkit' ),
						],
					];
				else :
					$meta_items['ark_meta_show_thumb']   = [
						'title'   => __( 'Featured image', 'arkhe-toolkit' ),
						'options' => $show_or_hide_options,
					];
					$meta_items['ark_meta_show_author']  = [
						'title'   => __( 'Author data', 'arkhe-toolkit' ),
						'options' => $show_or_hide_options,
					];
					$meta_items['ark_meta_show_related'] = [
						'title'   => __( 'Related posts', 'arkhe-toolkit' ),
						'options' => $show_or_hide_options,
					];
				endif;

				// $meta_items['ark_meta_show_sidebar'] = [
				// 	'title'       => __( 'Sidebar', 'arkhe-toolkit' ),
				// 	'options'     => $show_or_hide_options,
				// 	'description' => '※ ' . __( 'Only valid with the default template.', 'arkhe-toolkit' ),
				// ];

				foreach ( $meta_items as $key => $data ) :
					$meta_val = get_post_meta( $the_id, $key, true );
			?>
					<div class="ark-meta__field -select">
						<label for="<?=esc_attr( $key )?>" class="ark-meta__label">
							<?=esc_html( $data['title'] )?>
						</label>
						<?php \Arkhe_Toolkit::meta_select( $key, $data['options'], $meta_val ); ?>
						<?php
							if ( isset( $data['description'] ) ) :
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '<p class="ark-meta__desc">' . $data['description'] . '</p>';
							endif;
						?>
					</div>
			<?php
				endforeach;

				$meta_checkboxes = [];

				if ( ! $is_home ) :
					$meta_checkboxes['ark_meta_hide_widget_top']    = [
						'label' => __( 'Hide top-widget', 'arkhe-toolkit' ),
					];
					$meta_checkboxes['ark_meta_hide_widget_bottom'] = [
						'label' => __( 'Hide bottom-widget', 'arkhe-toolkit' ),
					];
				endif;

				$meta_checkboxes['ark_meta_hide_before_footer'] = [
					'label' => __( 'Hide before-footer-widget', 'arkhe-toolkit' ),
				];

				if ( $is_page ) :
					$meta_checkboxes['ark_meta_show_excerpt'] = [
						'label'       => __( 'Show page "excerpt"', 'arkhe-toolkit' ),
						'description' => $text_only_topttl,
					];
				else :
					$meta_checkboxes['ark_meta_hide_sharebtns'] = [
						'label'       => __( 'Hide share buttons', 'arkhe-toolkit' ),
					];
				endif;

				foreach ( $meta_checkboxes as $key => $data ) :
					$meta_val = get_post_meta( $the_id, $key, true );
				?>
					<div class="ark-meta__field">
						<?php
							\Arkhe_Toolkit::meta_checkbox( $key, $data['label'], $meta_val );

							if ( isset( $data['description'] ) ) :
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo '<p class="ark-meta__desc">' . $data['description'] . '</p>';
							endif;
						?>
					</div>
				<?php
				endforeach;
			?>
		</div>
	</div>
<?php
}


/**
 * メタコード
 */
function arkt_cb_code_meta( $post ) {
	$post_id   = $post->ID;
	$head_code = [
		'key'   => 'ark_meta_code_head',
		'label' => __( 'Code to output in the &lt;head&gt; tag', 'arkhe-toolkit' ),
	];
	$foot_code = [
		'key'   => 'ark_meta_code_foot',
		'label' => __( 'Code to output before the end of &lt;/body&gt; tag', 'arkhe-toolkit' ),
	];

	$head_code_val = get_post_meta( $post_id, $head_code['key'], true );
	$foot_code_val = get_post_meta( $post_id, $foot_code['key'], true );

	?>
	<div id="arkhe_post_meta" class="ark-meta -code">
		<div class="ark-meta__item">
			<label class="ark-meta__subttl" for="<?=esc_attr( $head_code['key'] )?>">
				<?=esc_html( $head_code['label'] )?>
			</label>
			<div class="ark-meta__field">
				<textarea id="<?=esc_attr( $head_code['key'] )?>" name="<?=esc_attr( $head_code['key'] )?>" rows="8" class="large-text"><?=esc_textarea( $head_code_val )?></textarea>
			</div>
		</div>
		<div class="ark-meta__item">
			<label class="ark-meta__subttl" for="<?=esc_attr( $foot_code['key'] )?>">
				<?=esc_html( $foot_code['label'] )?>
			</label>
			<div class="ark-meta__field">
				<textarea id="<?=esc_attr( $foot_code['key'] )?>" name="<?=esc_attr( $foot_code['key'] )?>" rows="8" class="large-text"><?=esc_textarea( $foot_code_val )?></textarea>
			</div>
		</div>
	</div>
	<?php
}


/**
 * 保存処理
 */
function hook_save_post( $post_id ) {

	if ( ! \Arkhe_Toolkit::can_save_meta( 'arkhe_nonce_post_meta' ) ) return;

	\Arkhe_Toolkit::save_post_metas( $post_id, [
		'ark_meta_subttl'             => 'str',
		'ark_meta_ttlbg'              => 'str',
		'ark_meta_ttlpos'             => 'str',
		'ark_meta_show_thumb'         => 'str',
		'ark_meta_show_related'       => 'str',
		'ark_meta_show_author'        => 'str',
		'ark_meta_hide_widget_top'    => 'check',
		'ark_meta_hide_widget_bottom' => 'check',
		'ark_meta_hide_before_footer' => 'check',
		'ark_meta_show_excerpt'       => 'check',
		'ark_meta_hide_sharebtns'     => 'check',
		'ark_meta_code_head'          => 'code',
		'ark_meta_code_foot'          => 'code',
	] );

}
