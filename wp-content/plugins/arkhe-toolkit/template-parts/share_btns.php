<?php
/**
 * SNSシェアボタン用 拡張テンプレート
 */
defined( 'ABSPATH' ) || exit;

$the_id        = get_the_ID();
$position      = $args['position'] ?: '';
$share_url     = get_permalink( $the_id );
$share_title   = html_entity_decode( get_the_title( $the_id ) );
$share_btns    = \Arkhe_Toolkit::get_share_btns_list( $the_id, $share_url, $share_title );
$show_url_copy = \Arkhe_Toolkit::get_data( 'customizer', 'show_share_urlcopy' );

// ピンタレストがオンの時
if ( isset( $share_btns['pinterest'] ) ) {
	\Arkhe_Toolkit::set_use( 'pinterest', true );
}

// URLコピーボタンがオンの時
if ( $show_url_copy ) {
	\Arkhe_Toolkit::set_use( 'clipboard', true );
}

?>
<div class="c-shareBtns" data-pos="<?=esc_attr( $position )?>">
	<?php do_action( 'arkhe_toolkit_before_share_btns_list' ); ?>
	<ul class="c-shareBtns__list">
		<?php foreach ( $share_btns as $key => $data ) : ?>
			<li class="c-shareBtns__item -<?=esc_attr( $key )?>">
				<a class="c-shareBtns__btn u-flex--c" <?php \Arkhe_Toolkit::print_attrs_as_string( \Arkhe_Toolkit::generate_share_btn_attrs( $data ) ); ?>>
					<?php \Arkhe_Toolkit::the_svg( $key, [ 'class' => 'c-shareBtns__icon' ] ); ?>
				</a>
			</li>
		<?php endforeach; ?>

		<?php if ( $show_url_copy ) : ?>
			<li class="c-shareBtns__item -copy">
				<div class="c-urlcopy c-shareBtns__btn" data-clipboard-text="<?=esc_url( $share_url )?>" title="<?=esc_attr__( 'Copy the URL', 'arkhe-toolkit' )?>">
					<div class="c-urlcopy__content">
						<?php \Arkhe_Toolkit::the_svg( 'clipboard-copy', [ 'class' => 'c-shareBtns__icon -to-copy' ] ); ?>
						<?php \Arkhe_Toolkit::the_svg( 'clipboard-copied', [ 'class' => 'c-shareBtns__icon -copied' ] ); ?>
					</div>
				</div>
				<div class="c-copyedPoppup">URL Copied!</div>
			</li>
		<?php endif; ?>
	</ul>
</div>
