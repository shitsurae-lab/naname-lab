<?php
namespace Arkhe_Toolkit;

/**
 * ウィジェット出力
 */
add_action( 'wp', '\Arkhe_Toolkit\add_ex_widgets' );
function add_ex_widgets() {

	// フッター前
	$use_before_footer = \Arkhe_Toolkit::get_data( 'extension', 'use_before_footer' );
	if ( $use_before_footer ) {
		add_action( 'arkhe_before_footer', function () {
			if ( is_page() || is_single() || ( ! is_front_page() && is_home() ) ) {
				$is_hide = get_post_meta( get_queried_object_id(), 'ark_meta_hide_before_footer', true );
				if ( '1' === $is_hide ) return;
			}
			if ( ! is_active_sidebar( 'before-footer' ) ) return;

			echo '<div class="w-before-footer l-container">';
			dynamic_sidebar( 'before-footer' );
			echo '</div>';

		}, 5 );
	}

	if ( is_home() && \Arkhe_Toolkit::get_data( 'extension', 'use_home_widget' ) ) {

		// ブログページ上部
		add_action( 'arkhe_before_home_content', function () {
			if ( is_active_sidebar( 'home-top' ) ) {
				echo '<div class="w-home-top">';
				dynamic_sidebar( 'home-top' );
				echo '</div>';
			}
		} );

		// ブログページ下部
		add_action( 'arkhe_after_home_content', function () {
			if ( is_active_sidebar( 'home-bottom' ) ) {
				echo '<div class="w-home-bottom">';
				dynamic_sidebar( 'home-bottom' );
				echo '</div>';
			}
		} );

	} elseif ( is_front_page() ) {

		return;

	} elseif ( is_page() && \Arkhe_Toolkit::get_data( 'extension', 'use_page_widget' ) ) {

		// 固定ページ上部
		add_action( 'arkhe_before_page_content', function ( $page_id ) {
			$is_hide = get_post_meta( $page_id, 'ark_meta_hide_widget_top', true );
			if ( '1' === $is_hide || ! is_active_sidebar( 'page-top' ) ) return;

			echo '<div class="w-page-top">';
			dynamic_sidebar( 'page-top' );
			echo '</div>';
		} );

		// 固定ページ下部
		add_action( 'arkhe_after_page_content', function ( $page_id ) {
			$is_hide = get_post_meta( $page_id, 'ark_meta_hide_widget_bottom', true );
			if ( '1' === $is_hide || ! is_active_sidebar( 'page-bottom' ) ) return;

			echo '<div class="w-page-bottom">';
			dynamic_sidebar( 'page-bottom' );
			echo '</div>';
		} );

	} elseif ( is_single() && \Arkhe_Toolkit::get_data( 'extension', 'use_post_widget' ) ) {

		// 投稿ページ上部
		add_action( 'arkhe_before_entry_content', function ( $page_id ) {
			$is_hide = get_post_meta( $page_id, 'ark_meta_hide_widget_top', true );
			if ( '1' === $is_hide || ! is_active_sidebar( 'single-top' ) ) return;

			echo '<div class="w-single-top">';
			dynamic_sidebar( 'single-top' );
			echo '</div>';
		} );

		// 投稿ページ下部
		add_action( 'arkhe_after_entry_content', function ( $page_id ) {
			$is_hide = get_post_meta( $page_id, 'ark_meta_hide_widget_bottom', true );
			if ( '1' === $is_hide || ! is_active_sidebar( 'single-bottom' ) ) return;

			echo '<div class="w-single-bottom">';
			dynamic_sidebar( 'single-bottom' );
			echo '</div>';
		} );

	}

	// 追尾サイドバー
	$use_fix_sidebar = apply_filters( 'arkhe_toolkit_use_fix_sidebar', \Arkhe_Toolkit::get_data( 'extension', 'use_fix_sidebar' ) );
	if ( $use_fix_sidebar ) {
		add_action( 'arkhe_end_sidebar', function () {
			if ( ! is_active_sidebar( 'fix-sidebar' ) ) return;

			echo '<div id="fix_sidebar" class="w-fix-sidebar">';
			dynamic_sidebar( 'fix-sidebar' );
			echo '</div>';
		} );
	}
}


/**
 * ウィジェット登録
 */
add_action( 'widgets_init', '\Arkhe_Toolkit\setup_widgets', 20 );
function setup_widgets() {

	// phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	$widget_desc = __( 'Widgets in this area will be displayed in the %s', 'arkhe-toolkit' );

	// フッター直前ウィジェット
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_before_footer' ) ) {
		$widget_position = __( 'Before footer', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => $widget_position,
				'id'            => 'before-footer',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -before-footer">',
				'after_title'   => '</div>',
			]
		);
	}

	// 固定ページ用
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_page_widget' ) ) {
		$widget_position = __( 'Top of "Page" content', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => $widget_position,
				'id'            => 'page-top',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -page">',
				'after_title'   => '</div>',
			]
		);
		$widget_position = __( 'Bottom of "Page" content', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => __( 'Bottom of "Page" content', 'arkhe-toolkit' ),
				'id'            => 'page-bottom',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -page">',
				'after_title'   => '</div>',
			]
		);
	}

	// 投稿ページ用
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_post_widget' ) ) {
		$widget_position = __( 'Top of "Post" content', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => __( 'Top of "Post" content', 'arkhe-toolkit' ),
				'id'            => 'single-top',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -single">',
				'after_title'   => '</div>',
			]
		);
		$widget_position = __( 'Bottom of "Post" content', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => __( 'Bottom of "Post" content', 'arkhe-toolkit' ),
				'id'            => 'single-bottom',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -single">',
				'after_title'   => '</div>',
			]
		);
	}

	// ブログページ用
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_home_widget' ) ) {
		$widget_position = __( 'Top of "Home" content', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => __( 'Top of "Home" content', 'arkhe-toolkit' ),
				'id'            => 'home-top',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -home">',
				'after_title'   => '</div>',
			]
		);
		$widget_position = __( 'Bottom of "Home" content', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => __( 'Bottom of "Home" content', 'arkhe-toolkit' ),
				'id'            => 'home-bottom',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -home">',
				'after_title'   => '</div>',
			]
		);
	}

	// 追尾サイドバー
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_fix_sidebar' ) ) {
		$widget_position = __( 'bottom of the sidebar', 'arkhe-toolkit' );
		register_sidebar(
			[
				'name'          => __( 'Sticky sidebar', 'arkhe-toolkit' ),
				'id'            => 'fix-sidebar',
				'description'   => sprintf( $widget_desc, $widget_position ),
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="c-widget__title -fix-sidebar">',
				'after_title'   => '</div>',
			]
		);
	}
}
