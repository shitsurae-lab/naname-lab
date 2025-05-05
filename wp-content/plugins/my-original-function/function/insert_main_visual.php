<?php
//START: 見出し戻り値の関数①
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
//END: 見出し戻り値の関数①

//START: 見出し戻り値の関数②: singular.phpに対しての見出し関数
function get_title()
{
  //固定ページや投稿ページのスラッグ(URL)を取得
  global $post;
  $slug = $post->post_name;
  $uc_slug = strtoupper($slug);
  $post_title = get_the_title();

  if (is_single()) {
    return get_the_title();
  }
  if (is_page()) {
    return $uc_slug;
  }
}
//END: 見出し戻り値の関数②: singular.phpに対しての見出し関数


function custom_main_visual()
{
  if (is_post_type_archive(array('info', 'achievement'))) :
    // ###カスタム投稿アーカイブ
    //カスタム投稿タイプの名前(slug)を取得
    //cf. WordPress でカスタム投稿タイプのラベルやスラッグを取得(https://hirashimatakumi.com/blog/5271.html)
    $term = get_queried_object();
    $term_name = $term->name;
    $term_desc = $term->description; //②タームディスクリプション
    $uc_term_name = strtoupper($term_name);
    $cp_slug = get_query_var('post_type');
    $uc_cp_slug = strtoupper($cp_slug);
  // $uploads_baseurl = wp_upload_dir()['baseurl'];

  elseif (is_archive()) :
    // ### アーカイブページ
    $uploads_baseurl = wp_upload_dir()['baseurl'];

    //カテゴリ・タグ・タクソミーアーカイブでタームスラッグを取得する(カテゴリ・タグ・タクソミーアーカイブ共通)
    $term = get_queried_object();
    $term_slug = $term->slug; //①タームスラッグ
    $term_desc = $term->description; //②タームディスクリプション
    $uc_term_slug = strtoupper($term_slug);
    $term_name = $term->name;
  elseif (is_singular() && !is_front_page()) :
    // ### 投稿ページ / 固定ページ
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
    $post_title = get_the_title();

  endif;
}
// add_action('arkhe_after_header', 'custom_main_visual');

//--カスタム投稿タイプ 投稿詳細ページヘッダー
function insert_before_breadcrumb()
{
  if (is_singular(array('achievement', 'info'))) :
    // カスタム投稿タイプからスラッグを取得
    $cp_slug = get_query_var('post_type');
    $uc_cp_slug = strtoupper($cp_slug);
  endif;
}
//add_action('arkhe_start_content', 'insert_before_breadcrumb', 10);

add_action('arkhe_after_header', 'add_custom_content_start_message');
function add_custom_content_start_message()
{
  $media_url = wp_upload_dir()['baseurl'];
  if (is_front_page()) {
    $front_page_id = get_option('page_on_front'); //フロントページとして設定された固定ページ
    if ($front_page_id) {
      $mv_title = get_field('mv_title', $front_page_id);
      $mv_subtitle = get_field('mv_subtitle', $front_page_id);
      $mv_desc = get_field('mv_desc', $front_page_id);
      $eyecatch_url = get_the_post_thumbnail_url($front_page_id);
      $alt_text = 'Toshiyuki Kurashima | ' .  get_the_title($front_page_id);
    }
?>
    <section class="p-index-mv">
      <div class="p-index-mv__inner">
        <div class="p-index-mv__image">
          <img src="<?php echo esc_url($eyecatch_url) ?>" alt="<?php echo esc_html($alt_text); ?>">
        </div>
        <div class="p-index-mv__box u-gutter">
          <div class="p-index-mv__content">
            <div class="p-index-mv__title c-hero-heading">
              <div class="c-hero-heading--main u-montserrat u-font--500 u-uppercase"><?php echo nl2br(esc_html($mv_title)); ?></div>
            </div>
            <div class="p-index-mv__subtitle u-color-primary c-en u-bold u-mb-20 u-uppercase"><?php echo esc_html($mv_subtitle); ?></div>
            <p class="p-index-mv__desc"><?php echo nl2br(esc_html($mv_desc)); ?></p>
          </div>
          <!-- END p-page-mv__content -->
        </div>
        <!-- <div class="p-page-mv__scrolldown u-color-secondary">
          <div class="c-scrolldown">
            <div class="c-scrolldown--txt u-uppercase">scroll down</div>
          </div>
          <div class="c-scrolldown--line">
            <div class="c-line"></div>
          </div>
        </div> -->
      </div>
    </section>
  <?php

  } elseif (is_page(array('achievement_cat', 'about', 'contact'))) {
    $page_slugs = array('achievement_cat', 'about', 'contact');
    $current_slug = get_post_field('post_name');
    if (in_array($current_slug, $page_slugs)) {
      $page = get_page_by_path($current_slug);
      if ($page) {
        $page_id = $page->id;
        $mv_title = get_field('mv_title', $page_id);
        $mv_subtitle = get_field('mv_subtitle', $page_id);
        $mv_desc = get_field('mv_desc', $page_id);
        $eyecatch_url = get_the_post_thumbnail_url();
        $alt_text = 'Toshiyuki Kurashima | ' .  get_the_title($page_id);
      }
    }
  ?>
    <section class="p-page-mv">
      <div class="p-page-mv__inner">
        <div class="p-page-mv__image -<?php echo esc_attr($current_slug); ?>">
          <img src="<?php echo esc_url($eyecatch_url) ?>" alt="<?php echo esc_html($alt_text); ?>">
        </div>
        <div class="p-page-mv__box u-gutter">
          <div class="p-page-mv__content">
            <div class="p-page-mv__title c-hero-heading">
              <h1 class="c-hero-heading--main u-montserrat u-font--500 u-uppercase"><?php echo nl2br(esc_html($mv_title)); ?></h1>
            </div>
            <div class="p-page-mv__subtitle u-color-primary c-en u-bold u-mb-20 u-uppercase"><?php echo esc_html($mv_subtitle); ?></div>
            <p class="p-page-mv__desc"><?php echo nl2br(esc_html($mv_desc)); ?></p>
          </div>
          <!-- END p-page-mv__content -->
        </div>
        <!-- <div class="p-page-mv__scrolldown u-color-secondary">
          <div class="c-scrolldown">
            <div class="c-scrolldown--txt u-uppercase">scroll down</div>
          </div>
          <div class="c-scrolldown--line">
            <div class="c-line"></div>
          </div>
        </div> -->
      </div>
    </section>
<?php
  } elseif (is_tax('achievement_cat', 'wordpress')) {
    //WordPressタームのメインビジュアルが入る予定
    //} elseif (is_page('achievement_cat')) {
  }
}
