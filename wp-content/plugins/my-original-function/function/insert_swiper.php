<?php


function undefined_null($array, $key)
{
  $return = isset($array[$key]) ? $array[$key] : NULL;
  return $return;
}

function insert_front_slider()
{
  if (!is_front_page()) return;

  $term_slugs = array('wordpress', 'website-building', 'woocommerce', 'landing-page');
  $taxonomy_name = 'achievement_cat';

?>
  <section class="p-index__hero">
    <div class="hero-container">
      <div class="swiper hero-swiper l-container">
        <div class="swiper-wrapper">
          <?php
          foreach ($term_slugs as $slug) {
            $term = get_term_by('slug', $slug, $taxonomy_name);
            if (!$term) continue;

            $term_link = get_term_link($term);
            $term_title = $term->name;
            $term_description = $term->description;

            // ACFで設定された画像（画像IDで保存されている前提）
            $image_id = get_field('term_image', $taxonomy_name . '_' . $term->term_id);
            $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : get_template_directory_uri() . '/assets/img/noimg.png';
          ?>
            <div class="swiper-slide">
              <div class="swiper-slide__container">
                <div class="swiper-slide__content">
                  <div class="swiper-slide__textarea">
                    <h2 class="swiper-slide__heading">
                      <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term_title); ?></a>
                    </h2>
                    <?php if (!empty($term_description)) : ?>
                      <p><?php echo esc_html($term_description); ?></p>
                    <?php endif; ?>
                    <p class="c-more__arrow u-uppercase">
                      <a href="<?php echo esc_url($term_link); ?>">
                        <span class="c-more__arrow--text u-uppercase">read more</span>
                      </a>
                    </p>
                  </div>
                </div>
                <div class="swiper-slide__image">
                  <a href="<?php echo esc_url($term_link); ?>">
                    <figure class="p-clipper">
                      <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term_title); ?>">
                    </figure>
                  </a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="swiper-pagination"></div>
  </section>

  <?php
  $index = 0; // インデックス用カウンター

  foreach ($term_slugs as $slug) {
    $term = get_term_by('slug', $slug, $taxonomy_name);
    if (!$term) continue;

    $term_link = get_term_link($term);
    $term_title = $term->name;
    $term_description = $term->description;

    // ACFで設定された画像（画像IDで保存されている前提）
    $image_id = get_field('term_image', $taxonomy_name . '_' . $term->term_id);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : get_template_directory_uri() . '/assets/img/noimg.png';

    // 偶数/奇数でクラスを切り替える
    $bg_class = ($index % 2 === 0) ? 'u-bg--primary' : 'u-bg--secondary';
    $flex_class = ($index % 2 === 0) ? 'u-flex--primary' : 'u-flex--secondary';
  ?>
    <section class="l-section <?php echo $bg_class; ?> js-appearance" data-section>
      <div class="p-wave">
        <div class="p-wave__container"></div>
      </div>
      <div class="section__inner" data-section-inner>
        <div class="section__content <?php echo $flex_class; ?>">
          <div class="section__image">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term_title); ?>">
          </div>
          <div class="section__txt">
            <h2 style="font-size: 20px;" class="swiper-slide__heading">
              <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term_title); ?></a>
            </h2>
            <?php if (!empty($term_description)) : ?>
              <p style="font-size: 14px;"><?php echo esc_html($term_description); ?></p>
            <?php endif; ?>

            <!-- <div class="ark-block-buttons c-cta__button  u-uppercase u-letter u-ark-button--primary" data-orientation="horizontal">
              <div class="ark-block-button is-btn-fill" data-hover="bright" style="--arkb-btn-color--bg:var(--ark-color--main);--arkb-btn-radius:50px"> -->
            <a href="<?php echo esc_url($term_link); ?>" class="u-arkhe-custom-button" data-has-icon="1"><span class="ark-block-button__text">more</span><svg class="ark-block-button__icon -right" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-icon="LsChevronRight" viewBox="0 0 48 48">
                <path d="m33 25.1-13.1 13c-.8.8-2 .8-2.8 0-.8-.8-.8-2 0-2.8L28.4 24 17.1 12.7c-.8-.8-.8-2 0-2.8.8-.8 2-.8 2.8 0l13.1 13c.6.6.6 1.6 0 2.2z"></path>
              </svg></a>
            <!-- </div>
            </div> -->
          </div>
        </div>
      </div>
    </section>
    <?php
    $index++; // インクリメント
  }

  $page_slugs = array('about', 'achievement_cat');
  $index = 0;
  foreach ($page_slugs as $slug) {
    $page = get_page_by_path($slug);
    if ($page) :
      setup_postdata($page);

      $index++; // カウンター
      // 偶数/奇数でクラスを切り替える
      $bg_class = ($index % 2 === 0) ? 'u-bg--primary' : 'u-bg--secondary';
      $flex_class = ($index % 2 === 1) ? 'u-flex--primary' : 'u-flex--secondary';

      $thumb_url = esc_url(wp_get_attachment_url(get_post_thumbnail_id($page->ID))); // アイキャッチ画像URL
      $title = get_the_title($page->ID); // ページタイトル
      $permalink_url = esc_url(get_permalink($page->ID)); // パーマリンク
      $desc = get_field('page_desc', $page->ID); // ACF説明文
      $heading_en = get_field('page_heading_en', $page->ID); // ACF 英語見出し
    ?>
      <section class="section p-index-section <?php echo $bg_class; ?> js-appearance" data-section>
        <div class="p-index-section__inner" data-section-inner>
          <div class="p-index-section__content  <?php echo $flex_class; ?>">
            <div class="p-index-section__image">
              <img src="<?php echo $thumb_url; ?>" alt="<?php echo esc_attr($title); ?>">
            </div>
            <div class="p-index-section__txt">
              <div class="p-index-section__heading">
                <span class="p-index-section__heading--sub u-uppercase u-dosis"><?php echo esc_html($heading_en); ?></span>
                <h2 class="p-index-section__heading--main"><?php echo esc_html($title); ?></h2>
              </div>
              <p><?php echo nl2br(esc_html($desc)); ?></p>
              <a href="<?php echo $permalink_url; ?>" class="u-arkhe-custom-button" data-has-icon="1"><span class="ark-block-button__text">more</span><svg class="ark-block-button__icon -right" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-icon="LsChevronRight" viewBox="0 0 48 48">
                  <path d="m33 25.1-13.1 13c-.8.8-2 .8-2.8 0-.8-.8-.8-2 0-2.8L28.4 24 17.1 12.7c-.8-.8-.8-2 0-2.8.8-.8 2-.8 2.8 0l13.1 13c.6.6.6 1.6 0 2.2z"></path>
                </svg></a>
            </div>
          </div>
        </div>
      </section>
<?php
      wp_reset_postdata();
    endif;
  }
}


add_action('arkhe_before_front_content', 'insert_front_slider', 10);

function show_achievement_cat_terms_list()
{
  // 固定ページ "achievement_cat" でのみ表示
  if (!is_page('achievement_cat')) return;

  // カスタムタクソノミーの取得
  $terms = get_terms(array(
    'taxonomy' => 'achievement_cat',
    'hide_empty' => false,
  ));

  if (empty($terms) || is_wp_error($terms)) return;

  echo '<div class="c-grid u-grid--3col u-gap--l">'; // グリッドラッパー（3列・Arkheに合うクラス）

  foreach ($terms as $term) {
    $term_link = get_term_link($term);
    $term_title = esc_html($term->name);
    $term_description = esc_html($term->description);

    // ACFの画像（あれば）
    $image_id = get_field('term_image', $term->taxonomy . '_' . $term->term_id);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_template_directory_uri() . '/assets/img/noimg.png';

    echo '<a class="c-card p-archive-card" href="' . esc_url($term_link) . '">';
    echo '  <div class="c-card__img"><img src="' . esc_url($image_url) . '" alt="' . $term_title . '"></div>';
    echo '  <div class="c-card__body">';
    echo '    <h3 class="c-card__title">' . $term_title . '</h3>';
    if ($term_description) {
      echo '    <p class="c-card__desc">' . $term_description . '</p>';
    }
    echo '  </div>';
    echo '</a>';
  }

  echo '</div>';
}
add_action('arkhe_start_page_main', 'show_achievement_cat_terms_list');
