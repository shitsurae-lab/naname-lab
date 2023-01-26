<?php
namespace Arkhe_CSS_Editor;

defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes', __NAMESPACE__ . '\hook_add_meta_box' );
add_action( 'save_post', __NAMESPACE__ . '\hook_save_post' );


/**
 * メタボックス追加
 */
function hook_add_meta_box() {
	$custom_post_types = get_post_types( [
		'public'   => true,
		'_builtin' => false,
	] );
	$screens           = array_merge( [ 'post', 'page' ], array_keys( $custom_post_types ) );

	add_meta_box(
		'arkhe-css-editor',
		'Arkhe CSS Editor',
		__NAMESPACE__ . '\cb_metabox',
		apply_filters( 'arkhe_css_editor_meta_screens', $screens ),
		'normal',
		'high',
		null
	);
}

function cb_metabox( $post ) {

	wp_nonce_field( \Arkhe_CSS_Editor::NONCE_ACTION, \Arkhe_CSS_Editor::NONCE_NAME );

	$key      = \Arkhe_CSS_Editor::META_KEY;
	$meta_val = get_post_meta( $post->ID, $key, true );

	?>
	<style>
		.ark-css-meta{padding: 12px;}
		#arkhe-css-editor h2.ui-sortable-handle{
			display: flex;align-items: center;justify-content: flex-start;
		}
		#arkhe-css-editor h2.ui-sortable-handle::before {
			content: "";display: block;width: 1.25em;height: 1.25em;margin-right: 0.25em;
			background: url('data:image/svg+xml;utf8,<svg width="16" height="16" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg"><polygon points="34.96,1.89 14.29,22.56 14.29,20.34 14.33,20.29 21.09,13.53 19.31,13.53 30.73,2.11 30.95,1.89 "></polygon><polygon points="26.58,13.32 26.58,15.1 16.12,25.55 15.85,25.82 18.08,25.82 38,5.9 38,1.89 "></polygon><polygon points="38,12.95 25.44,25.51 26.89,25.51 26.89,38.11 2,38.11 2,13.22 14.29,13.22 14.29,11.5 23.9,1.89  27.9,1.89 27.69,2.11 27.69,2.11 14.29,15.51 14.29,14.71 3.49,14.71 3.49,36.61 25.4,36.61 25.4,25.55 25.13,25.82 22.9,25.82 23.17,25.55 26.57,22.14 26.57,20.36 38,8.94"></polygon><polygon points="27.69,2.11 14.29,15.51 27.69,2.11"></polygon><polygon points="32.18,25.82 38,20 38,15.99 28.17,25.82"></polygon><polygon points="38,25.82 38,23.04 35.22,25.82"></polygon><polygon points="20.86,1.89 20.64,2.11 14.29,8.46 14.29,4.46 16.85,1.89"></polygon></svg>');
			background-size: contain;
		}
	</style>
	<div class="ark-css-meta">
		<textarea id="<?=esc_attr( $key )?>" name="<?=esc_attr( $key )?>" rows="8" class="large-text"><?=esc_textarea( $meta_val )?></textarea>
		<p class="description">このページにだけ出力するCSS。&lt;style&gt;タグで囲まれて出力されます。</p>
	</div>
<?php
}


/**
 * 保存処理
 */
function hook_save_post( $the_id ) {

	// $_POST || nonce がなければ return
	if ( empty( $_POST ) || ! isset( $_POST[ \Arkhe_CSS_Editor::NONCE_NAME ] ) ) {
		return;
	}

	// nonceキーチェック
	if ( ! wp_verify_nonce( $_POST[ \Arkhe_CSS_Editor::NONCE_NAME ], \Arkhe_CSS_Editor::NONCE_ACTION ) ) {
		return;
	}

	// 自動保存時には保存しないように
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// 保存したいメタが渡ってきてるか
	$meta_key = \Arkhe_CSS_Editor::META_KEY;
	if ( ! isset( $_POST[ $meta_key ] ) ) return;

	$meta_val = $_POST[ $meta_key ];
	$meta_val = str_replace( "\r\n", "\n", $meta_val );
	$meta_val = \Arkhe_CSS_Editor::convert_utf( $meta_val );
	$meta_val = stripslashes_deep( sanitize_textarea_field( $meta_val ) );

	// DBアップデート
	if ( empty( $meta_val ) ) {
		delete_post_meta( $the_id, $meta_key );
	} else {
		update_post_meta( $the_id, $meta_key, $meta_val );
	}
}
