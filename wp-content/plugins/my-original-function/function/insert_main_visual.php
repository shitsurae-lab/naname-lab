<?php
// 見出し戻り値の関数①
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

//見出し戻り値の関数②: singular.phpに対しての見出し関数
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
?>
    <section class="l-mv">
      <div class="p-mv l-container">
        <div class="p-mv__container">
          <div id="particles-js"></div>
        </div>
        <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo $uc_cp_slug; ?></span><span class="p-mv__catch__span">カスタム投稿アーカイブ</span></div>
      </div>
    </section>


  <?php
  elseif (is_archive()) :
    // ### アーカイブページ
    $uploads_baseurl = wp_upload_dir()['baseurl'];

    //カテゴリ・タグ・タクソミーアーカイブでタームスラッグを取得する(カテゴリ・タグ・タクソミーアーカイブ共通)
    $term = get_queried_object();
    $term_slug = $term->slug; //①タームスラッグ
    $term_desc = $term->description; //②タームディスクリプション
    $uc_term_slug = strtoupper($term_slug);
    $term_name = $term->name;
  ?>
    <section class="l-mv">
      <div class="p-mv l-container">
        <div class="p-mv__container">
          <div id="particles-js"></div>
        </div>
        <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo $uc_term_slug; ?></span><span class="p-mv__catch__span">アーカイブページ</span></div>
        <!-- <?php //var_dump($term_name);
              ?> -->
      </div>
    </section>

  <?php elseif (is_singular() && !is_front_page()) :
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

  ?>
    <section class="l-mv">
      <div class="p-mv l-container">
        <div class="p-mv__container">
          <div id="particles-js"></div>
        </div>
        <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo (esc_html(get_title())); ?></span><span class="p-mv__catch__span">by naname lab...固定</span></div>
        <!-- <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo $uc_slug; ?></span><span class="p-mv__catch__span">by naname lab...固定</span></div> -->
      </div>
    </section>
  <?php
  endif;
}
add_action('arkhe_after_header', 'custom_main_visual');

//--カスタム投稿タイプ 投稿詳細ページヘッダー
function insert_before_breadcrumb()
{
  if (is_singular(array('achievement', 'info'))) :
    // カスタム投稿タイプからスラッグを取得
    $cp_slug = get_query_var('post_type');
    $uc_cp_slug = strtoupper($cp_slug);
  ?>
    <section class="l-mv">
      <div class="p-mv l-container">
        <div class="p-mv__container">
          <div id="particles-js"></div>
        </div>
        <div class="p-mv__catch"><span class="p-mv__catch__span"><?php echo $uc_cp_slug; ?></span><span class="p-mv__catch__span">by naname lab.</span></div>
      </div>
    </section>

<?php
  endif;
}
add_action('arkhe_start_content', 'insert_before_breadcrumb', 10);
