<?php
namespace Arkhe_Toolkit\Meta;

/**
 * ユーザーメタの追加
 */
add_filter( 'user_contactmethods', '\Arkhe_Toolkit\Meta\add_user_meta' );
function add_user_meta( $prof_items ) {
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_user_position' ) ) {
		$prof_items['position'] = __( 'Job title / position', 'arkhe-toolkit' );
	}
	if ( \Arkhe_Toolkit::get_data( 'extension', 'use_user_urls' ) ) {
		$prof_items['facebook_url']  = 'Facebook URL';
		$prof_items['twitter_url']   = 'Twitter URL';
		$prof_items['instagram_url'] = 'Instagram URL';
		$prof_items['pinterest_url'] = 'Pinterest URL';
		$prof_items['github_url']    = 'Github URL';
		$prof_items['youtube_url']   = 'Youtube URL';
		$prof_items['amazon_url']    = __( 'Amazon wish list', 'arkhe-toolkit' );
	}
	return $prof_items;
}
