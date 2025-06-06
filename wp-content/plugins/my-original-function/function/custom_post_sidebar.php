<?php
//サイドバーカスタム(投稿詳細ページ)
function custom_post_sidebar()
{
  global $post;
  global $icon_awesome;
  $icon_awesome = '<i class="fa-regular fa-folder"></i>';

  if (is_singular('post')) :
    $cat = get_the_category();
    $cat = $cat[0];
    $cat_name = $cat->name;

    $icon = plugins_url('my-original-plugin/assets/dist/media/twitter-pewter-blue-min.svg');
    $icon_fb = plugins_url('my-original-plugin/assets/dist/media/facebook-pewter-blue-min.svg');
?>
    <div class="p-sidebar__content">
      <div class="p-sidebar__heading">
        <p class="p-sidebar__update">Last update</p>
        <p class="p-sidebar__date">
          <?php $mod_date = get_the_modified_date('Y.m.d'); ?>
          <?php echo esc_html($mod_date); ?>
        </p>
        <p id="recent-posts" class="p-newsList__heading">
          <?php
          echo '<a href="' . get_category_link($cat->term_id) . '">'  . $icon_awesome . '<span>' . $cat_name . '</span></a>';
          ?>
        </p>
      </div>


      <!-- ここはタグかもしれない...参考)WordPress カテゴリー･タグの取得まとめ  https://www.bossraku.com/blog/327/ 無理しないこと get_the_term_list()を使うらしい -->

    </div>
    <!-- END //."p-leftside -->
  <?php
  elseif (is_object_in_term($post->ID, 'achievement_cat', ['ecommerce', 'woocommerce', 'wordpress', 'landing-page', 'banner', 'secret', 'website-building', 'static-site']) && is_singular('achievement')) :
    $terms = get_the_terms($post->ID,  'achievement_cat');

  ?>
    <div class="p-sidebar__content">
      <div class="p-sidebar__heading">
        <p class="p-sidebar__update">Last update</p>
        <p class="p-sidebar__date">
          <?php $mod_date = get_the_modified_date('Y.m.d'); ?>
          <?php echo esc_html($mod_date); ?>
        </p>
      </div>
      <details class="p-tag__details -sidebar js-details">
        <summary class="js-summary"><span class="p-summary__inner">keyword<span class="p-summary__icon p-tag__icon"></span></span></summary>

        <div class="p-tag__details__content js-content">
          <div class="p-tag__details__contentInner">
            <ul>
              <?php foreach ($terms as $term) : ?>
                <?php if ($term) :
                  $term_name = $term->name;
                  $term_slug = $term->slug;
                  $term_link = get_term_link($term_slug, 'achievement_cat');
                ?>
                  <li id="recent-posts" class="p-newsList__heading">
                    <?php echo '<a href="' . $term_link . '">' . $icon_awesome . '<span>' . $term_name . '</span></a>'; ?>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>

          </div>
        </div>
      </details>

    </div>
    <!-- END //.p-sidebar__content -->
  <?php

  // END is_object_in_term($post->ID, 'achievement_cat', ['ecommerce', 'woocommerce', 'wordpress', 'landing-page']) && is_singular('achievement'))

  elseif (is_object_in_term($post->ID, 'achievement_cat', ['containing-ecommerce', 'containing-woocommerce', 'containing-wordpress', 'containing-landing-page', 'containing-banner', 'containing-website-building', 'containing-secret', 'containing-static-site', 'containing-design', 'containing-working-on']) && is_singular('achievement')) :
    $terms = get_the_terms($post->ID,  'achievement_cat');


  ?>
    <div class="p-sidebar__content">
      <div class="p-sidebar__heading">
        <p class="p-sidebar__update">Last update</p>
        <p class="p-sidebar__date">
          <?php $mod_date = get_the_modified_date('Y.m.d'); ?>
          <?php echo esc_html($mod_date); ?>
        </p>
        <!-- 見出しに$term-nameがない場合はfalse(非表示)に: 条件分岐 -->
        <ul>
          <?php foreach ($terms as $term) : ?>

            <?php if ($term) :
              $term_name = $term->name;
              $term_name = str_replace('まとめ', '', $term_name);
              $term_slug = $term->slug;
              $term_slug = str_replace('containing-', '', $term_slug);
              $term_link = get_term_link($term_slug, 'achievement_cat');
            ?>
              <li id="recent-posts" class="p-newsList__heading">
                <?php echo '<a href="' . $term_link . '">' . $icon_awesome . '<span>' . $term_name . '</span></a>'; ?>
              </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>

      </div>
      <!-- END //.p-sidebar__content -->

    <?php

  // END is_object_in_term($post->ID, 'achievement_cat', ['ecommerce', 'woocommerce', 'wordpress', 'landing-page']) && is_singular('achievement'))

  elseif (is_object_in_term($post->ID, 'achievement_tag', ['design', 'building-website', 'working-on']) && is_singular('achievement')) :
    $terms = get_the_terms($post->ID,  'achievement_tag');

    ?>
      <div class="p-sidebar__content">
        <div class="p-sidebar__heading">
          <p class="p-sidebar__update">Last update</p>
          <p class="p-sidebar__date">
            <?php $mod_date = get_the_modified_date('Y.m.d'); ?>
            <?php echo esc_html($mod_date); ?>
          </p>
          <!-- 見出しに$term-nameがない場合はfalse(非表示)に: 条件分岐 -->
          <ul>
            <?php foreach ($terms as $term) : ?>
              <?php if ($term) :
                $term_name = $term->name;
                $term_slug = $term->slug;
                $term_link = get_term_link($term_slug, 'achievement_cat');
              ?>
                <li id="recent-posts" class="p-newsList__heading">
                  <?php echo '<a href="' . $term_link . '">' . $icon_awesome . '<span>' . $term_name . '</span></a>'; ?>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>

        </div>


      </div>
      <!-- END //.p-sidebar__content -->

    <?php

  // END is_object_in_term($post->ID, 'achievement_tag', ['design', 'building-website', 'working-on']) && is_singular('achievement'))


  elseif (is_singular('skill')) :
    global $post;
    $terms = get_the_terms($post->ID, 'skill_cat'); ?>
      <div class="p-sidebar__content">
        <div class="p-sidebar__heading">
          <p class="p-sidebar__update">Last update</p>
          <p class="p-sidebar__date">
            <?php $mod_date = get_the_modified_date('Y.m.d'); ?>
            <?php echo esc_html($mod_date); ?>
          </p>
          <ul>
            <?php foreach ($terms as $term) : ?>
              <?php if ($term) :
                $term_name = $term->name;
                $term_slug = $term->slug;
                $term_link = get_term_link($term_slug, 'achievement_cat');
              ?>
                <li id="recent-posts" class="p-newsList__heading">
                  <?php echo '<a href="' . $term_link . '">' . $icon_awesome . '<span>' . $term_name . '</span></a>'; ?>
                </li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>

      </div>
      <!-- END //.p-sidebar__content -->
  <?php
  endif;
  $post_obj = get_queried_object(); // huga の投稿オブジェクト

  if ($post_obj) {
    $terms = get_the_terms($post_obj->ID, 'achievement_cat'); // カテゴリー表示したい場合用

    // ACFフィールドをこの投稿だけから取得
    $heading = get_field('sidebar_heading', $post_obj->ID);
    $body    = get_field('sidebar_body', $post_obj->ID);

    if ($heading || $body) :
      echo '<div class="p-sidebar__container">';
      if ($heading) {
        echo '<h2>' . esc_html($heading) . '</h2>';
      }
      if ($body) {
        echo '<div>' . wp_kses_post($body) . '</div>';
      }
      echo '</div>';
    endif;
  }
}
add_action('arkhe_start_sidebar', 'custom_post_sidebar', 10);
