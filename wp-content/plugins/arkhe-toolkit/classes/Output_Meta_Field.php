<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;

/**
 * メタフィールド用のフォームパーツ
 */
trait Output_Meta_Field {

	private function __construct() {}

	/**
	 * Switch Checkbox
	 */
	public static function switch_checkbox( $name, $val, $checked, $label_on = '', $label_off = '' ) {
		if ( null === $name ) return;
		$label_on  = $label_on ?: __( 'Show', 'arkhe-toolkit' );
		$label_off = $label_off ?: __( 'Don\'t show', 'arkhe-toolkit' );
	?>
		<div class="ark-switchCheckbox">
			<span class="__label--On"><?=esc_html( $label_on )?></span>
			<label class="__switchBtn" for="<?=esc_attr( $name )?>">
				<input type="checkbox" name="" id="<?=esc_attr( $name )?>" class="__checkbox"<?=esc_attr( $checked )?>>
				<span class="__slider"></span>
			</label>
			<span class="__label--Off"><?=esc_html( $label_off )?></span>
			<input type="hidden" name="<?=esc_attr( $name )?>" value="<?=esc_attr( $val )?>" class="__hidden">
		</div>
	<?php
	}


	/**
	 * 画像アップロード
	 */
	public static function media_btns( $id = '', $val = '', $type = 'id' ) {
		if ( 'id' === $type ) {
			$src = wp_get_attachment_image_url( $val, 'medium' ) ?: '';
		} else {
			$src = $val;
		}
	?>
		<div class="ark-media-uploader">
			<input type="hidden" id="src_<?=esc_attr( $id )?>" name="<?=esc_attr( $id )?>" value="<?=esc_attr( $val )?>" />
			<div id="preview_<?=esc_attr( $id )?>" class="ark-media-uploader__preview">
				<?php if ( $src ) : ?>
					<img src="<?=esc_url( $src )?>" alt="preview">
				<?php endif; ?>
			</div>
			<div class="ark-media-uploader__btns">
				<input class="button button-primary" type="button" name="media-upload-btn" data-id="<?=esc_attr( $id )?>" value="<?=esc_attr__( 'Select image', 'arkhe-toolkit' )?>" data-type="<?=esc_attr( $type )?>" />
				<input class="button" type="button" name="media-clear" value="<?=esc_attr__( 'Delete image', 'arkhe-toolkit' )?>" data-id="<?=esc_attr( $id )?>" />
			</div>
		</div>
	<?php
	}


	/**
	 * 設定用の select
	 */
	public static function meta_select( $id = '', $options = [], $meta = '', $default = '----' ) {
		$id = esc_attr( $id );
	?>
		<select name="<?=esc_attr( $id )?>" id="<?=esc_attr( $id )?>">
			<option value=""><?=esc_html( $default )?></option>
			<?php
			foreach ( $options as $key => $label ) :
				$selected = (string) $key === $meta ? ' selected' : '';
			?>
				<option value="<?=esc_attr( $key )?>"<?=esc_attr( $selected )?>>
					<?=esc_html( $label )?>
				</option>
			<?php endforeach; ?>
		</select>
	<?php
	}


	/**
	 * 設定用の checkbox
	 */
	public static function meta_checkbox( $id = '', $label = '', $meta = '' ) {
		$checked = '1' === $meta ? ' checked' : '';
		$id      = esc_attr( $id );
	?>
		<input type="hidden" name="<?=esc_attr( $id )?>" value="0" />
		<input type="checkbox" name="<?=esc_attr( $id )?>" id="<?=esc_attr( $id )?>" value="1"<?=esc_attr( $checked )?>>
		<label for="<?=esc_attr( $id )?>"><?=esc_html( $label )?></label>
	<?php
	}


	/**
	 * 設定用の text input
	 */
	public static function meta_text_input( $args = [] ) {
		$default = [
			'id'          => '',
			'meta'        => '',
			'placeholder' => '',
			'size'        => '40',
		];
		$args    = array_merge( $default, $args );
		$id      = esc_attr( $args['id'] );
	?>
		<input type="text" id="<?=esc_attr( $id )?>" name="<?=esc_attr( $id )?>" value="<?=esc_attr( $args['meta'] )?>" size="<?=esc_attr( $args['size'] )?>" placeholder="<?=esc_attr( $args['placeholder'] )?>" />
	<?php
	}

	/**
	 * 設定用の radiobox
	 */
	public static function meta_radiobox( $name = '', $choices = '', $meta = '', $is_block = true ) {
		$u_block = $is_block ? ' u-block' : '';
		foreach ( $choices as $key => $label ) :
		$checked  = ( $meta === $key ) ? ' checked' : '';
		$name     = esc_attr( $name );
		$key      = esc_attr( $key );
		$radio_id = $name . '_' . $key;
		?>
			<label for="<?=esc_attr( $radio_id )?>" class="ark-meta__radio<?=esc_attr( $u_block )?>">
				<input type="radio" id="<?=esc_attr( $radio_id )?>" name="<?=esc_attr( $name )?>" value="<?=esc_attr( $key )?>"<?=esc_attr( $checked )?> />
		<?=esc_html( $label )?>
			</label>
		<?php
		endforeach;
	}

	/**
	 * 設定用の option タグ
	 */
	// public static function select_option( $key = '', $label = '', $selected = false ) {
	// 	// $selected は esc_ 通さない。
	// 	echo '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $label ) . '</option>';
	// }

}
