<?php
namespace Arkhe_Toolkit;

defined( 'ABSPATH' ) || exit;

trait Output_Menu_Field {

	/**
	 * チェックボックス
	 */
	public static function output_checkbox( $args = [] ) {

		extract( array_merge( [
			'db'    => '',
			'label' => '',
			'key'   => '',
			'desc'  => '',
		], $args ) );

		$name = \Arkhe_Toolkit::DB_NAMES[ $db ] . '[' . $key . ']';
		$val  = (string) \Arkhe_Toolkit::get_data( $db, $key );
	?>
		<div class="arkhe-menu__field -checkbox" data-key="<?=esc_attr( $key )?>">
			<input type="hidden" name="<?=esc_attr( $name )?>" value="">
			<input type="checkbox" id="<?=esc_attr( $key )?>" name="<?=esc_attr( $name )?>" value="1" <?php checked( $val, '1' ); ?> />
			<label for="<?=esc_attr( $key )?>"><?=esc_html( $label )?></label>
			<?php if ( $desc ) : ?>
				<p class="arkhe-menu__description"><?=wp_kses_post( $desc )?></p>
			<?php endif; ?>
		</div>
	<?php
	}


	/**
	 * テキストフィールド
	 */
	public static function output_text_field( $args ) {

		extract( array_merge( [
			'db'    => '',
			'label' => '',
			'key'   => '',
			'size'  => '',
		], $args ) );

		$name = \Arkhe_Toolkit::DB_NAMES[ $db ] . '[' . $key . ']';
		$val  = \Arkhe_Toolkit::get_data( $db, $key );
	?>
		<div class="arkhe-menu__field -text" data-key="<?=esc_attr( $key )?>">
			<label for="<?=esc_attr( $key )?>" class="arkhe-menu__field__title"><?=esc_html( $label )?></label>
			<div class="arkhe-menu__item">
				<input type="text" id="<?=esc_attr( $key )?>" name="<?=esc_attr( $name )?>" value="<?=esc_attr( $val )?>" size="40" />
			</div>
		</div>
	<?php
	}


	/**
	 * テキストエリア
	 */
	public static function output_textarea( $args ) {
		extract( array_merge( [
			'db'        => '',
			'label'     => '',
			'key'       => '',
			'rows'      => '4',
			'is_wide'   => false,
			'desc'      => '',
			'classname' => '',
		], $args ) );

		$name = \Arkhe_Toolkit::DB_NAMES[ $db ] . '[' . $key . ']';
		$val  = \Arkhe_Toolkit::get_data( $db, $key );

		$textarea_class = $is_wide ? 'large-text' : 'regular-text';
	?>
		<div class="arkhe-menu__field -textarea <?=esc_attr( $classname )?>" data-key="<?=esc_attr( $key )?>">
			<?php if ( $label ) : ?>
				<label class="arkhe-menu__field__title" for="<?=esc_attr( $key )?>"><?=esc_html( $label )?></label>
			<?php endif; ?>
			<textarea id="<?=esc_attr( $key )?>" class="<?=esc_attr( $textarea_class )?>" name="<?=esc_attr( $name )?>" rows="<?=esc_attr( $rows )?>" ><?=esc_html( $val )?></textarea>
			<?php if ( $desc ) : ?>
				<p class="arkhe-menu__description"><?=wp_kses_post( $desc )?></p>
			<?php endif; ?>
		</div>
	<?php
	}


	/**
	 * ラジオボタン
	 */
	public static function output_radio( $args ) {

		extract( array_merge( [
			'db'      => '',
			'key'     => '',
			'label'   => '',
			'choices' => '',
		], $args ) );

		$name = \Arkhe_Toolkit::DB_NAMES[ $db ] . '[' . $key . ']';
		$val  = \Arkhe_Toolkit::get_data( $db, $key );
	?>
		<div class="arkhe-menu__field -radio" data-key="<?=esc_attr( $key )?>">
			<div class="arkhe-menu__field__title">
				<?=esc_html( $label )?>
			</div>
			<?php foreach ( $choices as $value => $label ) : ?>
				<label for="<?=esc_attr( $key . '_' . $value )?>">
					<input type="radio" id="<?=esc_attr( $key . '_' . $value )?>" name="<?=esc_attr( $name )?>" value="<?=esc_attr( $value )?>" <?php checked( $val, $value ); ?>>
					<span><?=esc_html( $label )?></span>
				</label>
			<?php endforeach; ?>
		</div>
	<?php
	}


	/**
	 * セレクトボックス
	 */
	public static function output_select( $args ) {

		extract( array_merge( [
			'db'      => '',
			'key'     => '',
			'label'   => '',
			'choices' => '',
		], $args ) );

		$name = \Arkhe_Toolkit::DB_NAMES[ $db ] . '[' . $key . ']';
		$val  = \Arkhe_Toolkit::get_data( $db, $key );
	?>
		<div class="arkhe-menu__field -select" data-key="<?=esc_attr( $key )?>">
			<label class="arkhe-menu__field__title" for="<?=esc_attr( $key )?>">
				<?=esc_html( $label )?>
			</label>
			<select id="<?=esc_attr( $key )?>" name="<?=esc_attr( $name )?>">
				<?php foreach ( $choices as $value => $label ) : ?>
					<option value="<?=esc_attr( $value )?>"<?php selected( $value, $val ); ?>><?=esc_html( $label )?></option>
				<?php endforeach; ?>
			</select>
		</div>
	<?php
	}

}
