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

function custom_main_visual()
{
  if (is_post_type_archive(array('info', 'achievement'))) :
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

    <div class="p-hero__particles">
      <div id="particles-js"></div>
      <div class="p-hero__catch">
        <p><span><?php echo $uc_cp_slug; ?></span><span>naname design lab</span></p>
      </div>
    </div>
    <!-- <section class="arkb-section--hero alignfull ark-block-section p-postHero" data-height="content">
      <div class="ark-block-section__color arkb-absLayer"></div>
      <div class="ark-block-section__body" data-content="center-left">
        <div class="ark-block-section__bodyInner ark-keep-mt">
          <div class="p-postHero__container l-container">
            <div class="p-postHero__bg"></div>
            <div class="p-postHero__text">
              <div class="p-postHero__text--inner normal">
                <div class="p-postHero__text--catch">
                  <div class="p-postHero__text--bundle">
                    <p class="c-text">

                      <span class="c-line"><?php echo $uc_term_name; ?></span>
                      <span class="c-line">by nanme design labo...</span>

                    </p>
                  </div>
                </div>
              </div>
              <div class="p-postHero__text--inner burn">
                <div class="p-postHero__text--catch">
                  <div class="p-postHero__text--bundle">
                    <p class="c-text">

                      <span class="c-line"><?php echo $uc_term_name; ?></span>
                      <span class="c-line">by nanme design labo...</span>

                    </p>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>
    </section> -->

  <?php
  elseif (is_archive()) :
    $uploads_baseurl = wp_upload_dir()['baseurl'];

    //カテゴリ・タグ・タクソミーアーカイブでタームスラッグを取得する(カテゴリ・タグ・タクソミーアーカイブ共通)
    $term = get_queried_object();
    $term_slug = $term->slug; //①タームスラッグ
    $term_desc = $term->description; //②タームディスクリプション
    $uc_term_slug = strtoupper($term_slug);
  ?>
    <!-- <section class="arkb-section--hero00 alignfull ark-block-section index-hero" data-height="content">
      <div class="ark-block-section__color arkb-absLayer"></div>
      <div class="ark-block-section__body" data-content="center-left">
        <div class="ark-block-section__bodyInner ark-keep-mt">
          <div class="index-hero__image lazyblock-broken-grid-Z1VVNps index-hero__image wp-block-lazyblock-broken-grid">
            <figure class="index-hero__image__container">
              <picture>
                <source media="(max-width: 999px)" srcset="<?php //echo esc_url($uploads_baseurl);
                                                            ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash360x640.jpg 360w,<?php //echo esc_url($uploads_baseurl);
                                                                                                                        ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash720x1280.jpg 720w">
                <source media="(min-width: 1000px)" srcset="<?php //echo esc_url($uploads_baseurl);
                                                            ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg 1920w">
                <img decoding="async" src="<?php //echo esc_url($uploads_baseurl);
                                            ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" data-src="<?php //echo esc_url($uploads_baseurl);
                                                                                                              ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" alt="" class=" ls-is-cached lazyloaded">
              </picture>
            </figure>
          </div>
          <div class="ark-block-container ark-keep-mt index-hero__container c-emphasis">
            <h1><?php //echo $slug_name;
                ?></h1>
          </div>
        </div>
      </div>
    </section> -->
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $uc_term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $uc_term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
  <?php elseif (is_page() && !is_front_page()) :
    $uploads_baseurl = wp_upload_dir()['baseurl'];

    //カテゴリ・タグ・タクソミーアーカイブでタームスラッグを取得する(カテゴリ・タグ・タクソミーアーカイブ共通)
    $term = get_queried_object();
    $term_slug = $term->slug; //①タームスラッグ
    $term_desc = $term->description; //②タームディスクリプション
    $uc_term_slug = strtoupper($term_slug);
    //固定ページや投稿ページのスラッグ(URL)を取得
    global $post;
    $slug = $post->post_name;
  ?>
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $slug ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $slug ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
  <?php
  elseif (is_tax('info_cat')) : //--カスタムタクソノミーアーカイブ(カテゴリー: infoを指定した場合のタクソノミーアーカイブ)
    //タームタイトルの取得
    //cf. WordPress のタームの取得と表示方法(https://hirashimatakumi.com/blog/164.html)
    $term = get_queried_object();
    $term_slug = strtoupper($term->slug);
    $uploads_baseurl = wp_upload_dir()['baseurl'];
  ?>
    <!-- <section class="arkb-section--hero00 alignfull ark-block-section index-hero" data-height="content">
      <div class="ark-block-section__color arkb-absLayer"></div>
      <div class="ark-block-section__body" data-content="center-left">
        <div class="ark-block-section__bodyInner ark-keep-mt">
          <div class="index-hero__image lazyblock-broken-grid-Z1VVNps index-hero__image wp-block-lazyblock-broken-grid">
            <figure class="index-hero__image__container">
              <picture>
                <source media="(max-width: 999px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash360x640.jpg 360w,<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash720x1280.jpg 720w">
                <source media="(min-width: 1000px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg 1920w">
                <img decoding="async" src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" data-src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" alt="" class=" ls-is-cached lazyloaded">
              </picture>
            </figure>
          </div>


          <div class="ark-block-container ark-keep-mt index-hero__container c-emphasis">
            <h1><?php echo $term_slug; ?></h1>
          </div>
        </div>
      </div>
    </section> -->
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
  <?php
  elseif (is_tax('achievement_cat')) : ///カスタムタクソノミーアーカイブ(カテゴリー: 実績を指定した場合のタクソノミーアーカイブ)
    //タームタイトルの取得
    //cf. WordPress のタームの取得と表示方法(https://hirashimatakumi.com/blog/164.html)
    $term = get_queried_object();
    $term_slug = strtoupper($term->slug);
    $uploads_baseurl = wp_upload_dir()['baseurl'];
  ?>
    <!-- <section class="arkb-section--hero00 alignfull ark-block-section index-hero" data-height="content">
      <div class="ark-block-section__color arkb-absLayer"></div>
      <div class="ark-block-section__body" data-content="center-left">
        <div class="ark-block-section__bodyInner ark-keep-mt">
          <div class="index-hero__image lazyblock-broken-grid-Z1VVNps index-hero__image wp-block-lazyblock-broken-grid">
            <figure class="index-hero__image__container">
              <picture>
                <source media="(max-width: 999px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash360x640.jpg 360w,<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash720x1280.jpg 720w">
                <source media="(min-width: 1000px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg 1920w">
                <img decoding="async" src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" data-src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" alt="" class=" ls-is-cached lazyloaded">
              </picture>
            </figure>
          </div>


          <div class="ark-block-container ark-keep-mt index-hero__container c-emphasis">
            <h1><?php echo $term_slug; ?></h1>
          </div>
        </div>
      </div>
    </section> -->
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
  <?php

  elseif (is_tax('info_tag')) : ///カスタムタクソノミーアーカイブ(タグ: お知らせを指定した場合のタクソノミーアーカイブ)
    //タームタイトルの取得
    //cf. WordPress のタームの取得と表示方法(https://hirashimatakumi.com/blog/164.html)
    $term = get_queried_object();
    $term_slug = strtoupper($term->slug);
    $uploads_baseurl = wp_upload_dir()['baseurl'];
  ?>
    <!-- <section class="arkb-section--hero00 alignfull ark-block-section index-hero" data-height="content">
      <div class="ark-block-section__color arkb-absLayer"></div>
      <div class="ark-block-section__body" data-content="center-left">
        <div class="ark-block-section__bodyInner ark-keep-mt">
          <div class="index-hero__image lazyblock-broken-grid-Z1VVNps index-hero__image wp-block-lazyblock-broken-grid">
            <figure class="index-hero__image__container">
              <picture>
                <source media="(max-width: 999px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash360x640.jpg 360w,<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash720x1280.jpg 720w">
                <source media="(min-width: 1000px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg 1920w">
                <img decoding="async" src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" data-src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" alt="" class=" ls-is-cached lazyloaded">
              </picture>
            </figure>
          </div>


          <div class="ark-block-container ark-keep-mt index-hero__container c-emphasis">
            <h1><?php echo $term_slug; ?></h1>
          </div>
        </div>
      </div>
    </section> -->
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
  <?php
  elseif (is_tax('achievement_tag')) : ///カスタムタクソノミーアーカイブ(タグ: 実績を指定した場合のタクソノミーアーカイブ)
    //タームタイトルの取得
    //cf. WordPress のタームの取得と表示方法(https://hirashimatakumi.com/blog/164.html)
    $term = get_queried_object();
    $term_slug = strtoupper($term->slug);
    $uploads_baseurl = wp_upload_dir()['baseurl'];
  ?>
    <!-- <section class="arkb-section--hero00 alignfull ark-block-section index-hero" data-height="content">
      <div class="ark-block-section__color arkb-absLayer"></div>
      <div class="ark-block-section__body" data-content="center-left">
        <div class="ark-block-section__bodyInner ark-keep-mt">
          <div class="index-hero__image lazyblock-broken-grid-Z1VVNps index-hero__image wp-block-lazyblock-broken-grid">
            <figure class="index-hero__image__container">
              <picture>
                <source media="(max-width: 999px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash360x640.jpg 360w,<?php echo esc_url($uploads_baseurl); ?>/2022/12/el-salanzo-YuNl5xei5wA-unsplash720x1280.jpg 720w">
                <source media="(min-width: 1000px)" srcset="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg 1920w">
                <img decoding="async" src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" data-src="<?php echo esc_url($uploads_baseurl); ?>/2023/01/el-salanzo-YuNl5xei5wA-unsplash1920x800.jpg" alt="" class=" ls-is-cached lazyloaded">
              </picture>
            </figure>
          </div>


          <div class="ark-block-container ark-keep-mt index-hero__container c-emphasis">
            <h1><?php echo $term_slug; ?></h1>
          </div>
        </div>
      </div>
    </section> -->
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $term_slug; ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
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
    <div class="p-hero__particles">
      <div id="particles-js"></div>
      <div class="p-hero__catch">
        <p><?php echo $uc_cp_slug; ?></p>
      </div>
    </div>
    <!-- <div class="p-infinete__slider">
      <div class="swiper infinite__slider">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <p>achievements by naname</p>
          </div>
          <div class="swiper-slide">
            <p>achievements by naname</p>
          </div>
          <div class="swiper-slide">
            <p>achievements by naname</p>
          </div>
        </div>
      </div>
    </div> -->
  <?php
  elseif (is_single()) :
    //投稿からカテゴリーを取得
    $category = get_the_category();
    $slug = $category[0]->category_nicename;
  ?>
    <div class="p-hero__particles">
      <div id="particles-js"></div>
      <div class="p-hero__catch">
        <p><?php echo $slug; ?></p>
      </div>
    </div>
    <!-- <div class="p-infinete__slider">
      <div class="swiper infinite__slider">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <p>post by naname</p>
          </div>
          <div class="swiper-slide">
            <p>post by naname</p>
          </div>
          <div class="swiper-slide">
            <p>post by naname</p>
          </div>
        </div>
      </div>
    </div> -->
<?php
  endif;
}
add_action('arkhe_start_content', 'insert_before_breadcrumb', 10);
