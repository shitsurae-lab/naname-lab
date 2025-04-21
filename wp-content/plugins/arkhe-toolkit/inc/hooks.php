<?php
namespace Arkhe_Toolkit\Hooks;

/**
 * カテゴリーの説明文に対するフィルター処理を緩める ( wp_filter_kses -> wp_kses_post )
 */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
add_filter( 'pre_term_description', 'wp_kses_post' );


/**
 * ドロワーメニューの拡張
 */
add_filter( 'arkhe_root_attrs', __NAMESPACE__ . '\hook__root_attrs' );
function hook__root_attrs( $attrs ) {

	// ドロワーの展開方法
	$attrs['data-drawer-move'] = \Arkhe_Toolkit::get_data( 'customizer', 'drawer_move' );

	if ( \Arkhe_Toolkit::get_data( 'customizer', 'header_above_drawer' ) ) {
		$attrs['data-header-above'] = '1';
	}

	return $attrs;
}


/**
 * 著者情報に「役職」追加
 */
add_action( 'arkhe_after_author_name', __NAMESPACE__ . '\hook__after_author_name' );
function hook__after_author_name( $author_id ) {
	if ( ! $author_id ) return;
	if ( ! \Arkhe_Toolkit::get_data( 'extension', 'use_user_position' ) ) return;

	$position = get_the_author_meta( 'position', $author_id ) ?: '';
	if ( $position ) {
		echo '<span class="p-authorBox__position u-color-thin">' . esc_html( $position ) . '</span>';
	}
}


/**
 * 著者情報にSNSアイコンリンク追加
 */
add_action( 'arkhe_author_links', __NAMESPACE__ . '\hook__author_links' );
function hook__author_links( $author_id ) {
	if ( ! $author_id ) return;
	if ( ! \Arkhe_Toolkit::get_data( 'extension', 'use_user_urls' ) ) return;

	$icon_links              = [];
	$icon_links['facebook']  = get_the_author_meta( 'facebook_url', $author_id ) ?: '';
	$icon_links['twitter']   = get_the_author_meta( 'twitter_url', $author_id ) ?: '';
	$icon_links['instagram'] = get_the_author_meta( 'instagram_url', $author_id ) ?: '';
	$icon_links['pinterest'] = get_the_author_meta( 'pinterest_url', $author_id ) ?: '';
	$icon_links['github']    = get_the_author_meta( 'github_url', $author_id ) ?: '';
	$icon_links['youtube']   = get_the_author_meta( 'youtube_url', $author_id ) ?: '';
	$icon_links['amazon']    = get_the_author_meta( 'amazon_url', $author_id ) ?: '';

	// 空の要素を排除してリターン
	$icon_links = array_filter( $icon_links );
	?>
	<div class="p-authorBox__iconList">
		<ul class="c-iconList">
		<?php
			foreach ( $icon_links as $key => $href ) :
			if ( empty( $href ) ) continue;

			if ( 'twitter' === $key && apply_filters( 'arkhe_toolkit_twitter_to_x', 1 ) ) {
				$key = 'twitter-x';
			}
		?>
			<li class="c-iconList__item -<?=esc_attr( $key )?>">
				<a href="<?=esc_url( $href )?>" target="_blank" rel="noopener" class="c-iconList__link">
				<?php \Arkhe_Toolkit::the_svg( $key, [ 'class' => 'c-iconList__svg' ] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
