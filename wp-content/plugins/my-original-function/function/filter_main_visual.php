<?php

function get_archive_title()
{
  if (!is_archive()) return false;

  //日付アーカイブページなら
  if (is_date()) {
    if (is_year()) {
      $date_name = get_query_var('year') . '年';
    } elseif (is_month()) {
      $date_name = get_query_var('year') . '年' . get_query_var('monthnum') . '月';
    } else {
      $date_name = get_query_var('year') . '年' . get_query_var('monthnum') . '月' . get_query_var('day') . '日';
    }

    //日付アーカイブページかつ、投稿タイプアーカイブページでもある場合
    if (is_post_type_archive()) {
      return $date_name . "の" . post_type_archive_title('', false);
    }
    return $date_name;
  }
  //カスタム投稿タイプのアーカイブページなら
  if (is_post_type_archive()) {
    return post_type_archive_title('', false);
  }

  //投稿者アーカイブページなら
  if (is_author()) {
    return "投稿者" . get_queried_object()->data->display_name;
  } else {
    //それ以外(カテゴリ・タグ・タクソノミーアーカイブページ)
    return single_term_title('', false);
  }
}
// END function get_archive_title()

/**
 *投稿・固定ページ
 **/
add_filter('arkhe_part__top_area/singular', function () {
  /**
   * トップエリア（投稿・固定ページ用）
   */
  $the_id    = get_queried_object_id();  // get_the_ID() は is_home() でアウト。
  $subtitle  = apply_filters('arkhe_page_subtitle', '', $the_id, 'top');
  $excerpt   = apply_filters('arkhe_top_area_excerpt', '', $the_id);
  $bgimg_id  = apply_filters('arkhe_ttlbg_img_id', get_post_thumbnail_id($the_id), $the_id);
  $lazy_type = apply_filters('arkhe_use_lazy_top_area', false) ? Arkhe::get_lazy_type() : '';

  // 追加クラス（画像がなければフィルターもなし）
  $add_area_class = $bgimg_id ? '-filter-' . Arkhe::get_setting('title_bg_filter') : '-filter-none -noimg';

  // ### 固定ページ
  $uploads_baseurl = wp_upload_dir()['baseurl'];
  //カテゴリ・タグ・タクソミーアーカイブでタームスラッグを取得する(カテゴリ・タグ・タクソミーアーカイブ共通)
  $term = get_queried_object();
  $term_slug = $term->slug; //①タームスラッグ
  $term_desc = $term->description; //②タームディスクリプション
  $uc_term_slug = strtoupper($term_slug);
  //固定ページや投稿ページのスラッグ(URL)を取得
  global $post;
  $slug = $post->post_name;
  $uc_slug = strtoupper($slug);

  //投稿からカテゴリーを取得
  // $category = get_the_category();
  // $slug = $category[0]->category_nicename;

?>
  <section class="l-mv">
    <div class="p-mv l-container">
      <div class="p-mv__container">
        <div id="particles-js"></div>
      </div>
      <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo $uc_slug; ?></span><span class="p-mv__catch__span">by naname lab...固定</span></div>
    </div>
    <?php
    // if ($bgimg_id) :
    //   Arkhe::get_image($bgimg_id, array(
    //     'class'       => 'p-topArea__img c-filterLayer__img u-obf-cover',
    //     'alt'         => '',
    //     'loading'     => $lazy_type,
    //     'aria-hidden' => 'true',
    //     'decoding'    => 'async',
    //     'echo'        => true,
    //   ));
    // endif;
    ?>
  </section>
<?php
  return;
}, 10);

/**
 *タームアーカイブ
 **/
add_filter('arkhe_part__top_area/term', function () {
  /**
   * トップエリア（投稿・固定ページ用）
   */
  $the_id    = get_queried_object_id();  // get_the_ID() は is_home() でアウト。
  $subtitle  = apply_filters('arkhe_page_subtitle', '', $the_id, 'top');
  $excerpt   = apply_filters('arkhe_top_area_excerpt', '', $the_id);
  $bgimg_id  = apply_filters('arkhe_ttlbg_img_id', get_post_thumbnail_id($the_id), $the_id);
  $lazy_type = apply_filters('arkhe_use_lazy_top_area', false) ? Arkhe::get_lazy_type() : '';

  // 追加クラス（画像がなければフィルターもなし）
  $add_area_class = $bgimg_id ? '-filter-' . Arkhe::get_setting('title_bg_filter') : '-filter-none -noimg';

  // ### 固定ページ
  $uploads_baseurl = wp_upload_dir()['baseurl'];
  //カテゴリ・タグ・タクソミーアーカイブでタームスラッグを取得する(カテゴリ・タグ・タクソミーアーカイブ共通)
  $term = get_queried_object();
  $term_slug = $term->slug; //①タームスラッグ
  $term_desc = $term->description; //②タームディスクリプション
  $uc_term_slug = strtoupper($term_slug);
  //固定ページや投稿ページのスラッグ(URL)を取得
  global $post;
  $slug = $post->post_name;
  $uc_slug = strtoupper($slug);

  //投稿からカテゴリーを取得
  $category = get_the_category();
  $slug = $category[0]->category_nicename;

?>
  <section class="l-mv">
    <div class="p-mv l-container">
      <div class="p-mv__container">
        <div id="particles-js"></div>
      </div>
      <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo $uc_slug; ?></span><span class="p-mv__catch__span">by naname lab...タームアーカイブ</span></div>
    </div>
    <?php
    // if ($bgimg_id) :
    //   Arkhe::get_image($bgimg_id, array(
    //     'class'       => 'p-topArea__img c-filterLayer__img u-obf-cover',
    //     'alt'         => '',
    //     'loading'     => $lazy_type,
    //     'aria-hidden' => 'true',
    //     'decoding'    => 'async',
    //     'echo'        => true,
    //   ));
    // endif;
    ?>
  </section>
<?php
  return;
}, 10);
