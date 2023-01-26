<?php
/**
 * ブログカード
 */
$args = array_merge([
	'url'                => '',
	'excerpt'            => '',
	'title'              => '',
	'caption'            => '',
	'thumb_id'           => 0,
	'thumb_url'          => '',
	'icon'               => '',
	'is_newtab'          => false,
	'rel'                => '',
	'type'               => '',
	'anchor'             => '',
	'class'              => '',
	'show_excerpt'       => 'both',
	'show_image'         => true,
], $args );


$url    = $args['url'];
$target = ( $args['is_newtab'] ) ? ' target="_blank"' : '';
$rel    = $args['rel'] ? ' rel="' . $args['rel'] . '"' : '';

$wp_img_class = '';
if ( $args['thumb_id'] ) {
	$wp_img_class = ' wp-image-' . $args['thumb_id'];
}

$favicon = '';
if ( 'external' === $args['type'] ) {
	$favicon = $args['icon'] ? esc_url( $args['icon'] ) : ''; // 'https://www.google.com/s2/favicons?domain_url=' . esc_url( $url );
}

$excerpt_class = 'both' !== $args['show_excerpt'] ? ' arkb-only-' . $args['show_excerpt'] : '';

// ブロックの属性値
$add_class   = $args['class'] ? ' ' . $args['class'] : '';
$block_props = 'class="ark-block-blogCard arkb-boxLink' . esc_attr( $add_class ) . '"';
if ( $args['anchor'] ) {
	$block_props .= ' id="' . esc_attr( $args['anchor'] ) . '"';
}

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<div <?=$block_props?> data-arkb-linkbox>
	<div class="arkb-boxLink__inner" data-type="<?=$args['type']?>">
		<?php if ( $args['thumb_url'] ) : ?>
			<figure class="arkb-boxLink__figure is-fixed-ratio">
				<img src="<?=esc_url( $args['thumb_url'] )?>" alt="" class="arkb-boxLink__img arkb-obf-cover<?=esc_attr( $wp_img_class )?>">
			</figure>
		<?php endif; ?>
		<div class="arkb-boxLink__body">
			<a href="<?=esc_url( $url )?>" class="arkb-boxLink__title" data-arkb-link<?=$target . $rel?>>
				<?=esc_html( $args['title'] )?>
			</a>
			<?php if ( 'none' !== $args['show_excerpt'] ) : ?>
				<div class="arkb-boxLink__content<?=esc_attr( $excerpt_class )?>">
					<?=esc_html( $args['excerpt'] )?>
				</div>
			<?php endif; ?>
			<?php if ( $args['caption'] ) : ?>
				<div class="arkb-boxLink__more">
					<?php if ( $favicon ) : ?>
						<img class="arkb-boxLink__more__favicon" src="<?=$favicon?>" alt="" aria-hidden="true">
					<?php endif; ?>
					<span class="arkb-boxLink__more__text"><?=esc_html( $args['caption'] )?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
