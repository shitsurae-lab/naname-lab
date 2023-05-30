<?php
//サイドバーカスタム(投稿詳細ページ)
function custom_post_sidebar()
{
  global $post;
  if (is_singular('post')) :
    $cat = get_the_category();
    $cat = $cat[0];
    $cat_name = $cat->name;

    $icon = plugins_url('my-original-plugin/assets/dist/media/my-twitter-spindle-min.svg');
    $icon_fb = plugins_url('my-original-plugin/assets/dist/media/my-facebook-min.svg');
?>
    <div class="p-sidebar__content">
      <div class="p-sidebar__heading">
        <p class="p-sidebar__date"><?php the_date('Y.m.d'); ?></p>
        <p id="recent-posts" class="p-newsList__heading">
          <?php
          echo '<a href="' . get_category_link($cat->term_id) . '">' . $cat_name . '</a>';
          ?>
        </p>
      </div>


      <!-- ここはタグかもしれない...参考)WordPress カテゴリー･タグの取得まとめ  https://www.bossraku.com/blog/327/ 無理しないこと get_the_term_list()を使うらしい -->
      <div class="p-sidebar__share">
        <!-- <p class="u-en u-uppercase">share</p> -->
        <ul>
          <li>
            <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title_attribute(); ?>" ; target="_blank"><img src="<?php echo esc_url($icon); ?>" alt=""></a>
          </li>
          <li>
            <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" target="_blank"><img src="<?php echo esc_url($icon_fb); ?>" alt=""></a>
          </li>
        </ul>
      </div>
    </div>
    <!-- END //."p-leftside -->
    <?php
  elseif (is_object_in_term($post->ID, 'achievement_cat', ['ecommerce', 'woocommerce', 'wordpress', 'landing-page', 'banner']) && is_singular('achievement')) :
    $terms = get_the_terms($post->ID,  'achievement_cat');

    foreach ($terms as $term) {
      $term_name = $term->name;
      $term_slug = $term->slug;
      $term_link = get_term_link($term_slug, 'achievement_cat');
    ?>
      <div class="p-sidebar__content">
        <div class="p-sidebar__heading">
          <p class="p-sidebar__date"><?php the_date('Y.m.d'); ?></p>
          <!-- 見出しに$term-nameがない場合はfalse(非表示)に: 条件分岐 -->
          <?php if ($term) : ?>
            <p id="recent-posts" class="p-newsList__heading">
              <?php echo "<a href=\"" . $term_link . "\">" .  $term_name . "</a>"; ?>
            </p>

        </div>
      <?php endif; ?>
      <div class="p-sidebar__share">
        <?php $icon = plugins_url('my-original-plugin/assets/dist/media/my-twitter-spindle-min.svg'); ?>
        <?php $icon_fb = plugins_url('my-original-plugin/assets/dist/media/my-facebook-min.svg'); ?>
        <!-- <p class="u-en u-uppercase">share</p> -->
        <ul>
          <li>
            <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title_attribute(); ?>" ; target="_blank"><img src="<?php echo esc_url($icon); ?>" alt=""></a>
          </li>
          <li>
            <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" target="_blank"><img src="<?php echo esc_url($icon_fb); ?>" alt=""></a>
          </li>
        </ul>
      </div>
      </div>
      <!-- END //.p-sidebar__content -->

    <?php
    }
  // END is_object_in_term($post->ID, 'achievement_cat', ['ecommerce', 'woocommerce', 'wordpress', 'landing-page']) && is_singular('achievement'))

  elseif (is_object_in_term($post->ID, 'achievement_tag', ['design', 'building-website', 'working-on']) && is_singular('achievement')) :
    $terms = get_the_terms($post->ID,  'achievement_tag');

    foreach ($terms as $term) {
      $term_name = $term->name;
      $term_slug = $term->slug;
      $term_link = get_term_link($term_slug, 'achievement_tag');
    ?>
      <div class="p-sidebar__content">
        <div class="p-sidebar__heading">
          <p class="p-sidebar__date"><?php the_date('Y.m.d'); ?></p>
          <!-- 見出しに$term-nameがない場合はfalse(非表示)に: 条件分岐 -->
          <?php if ($term) : ?>
            <p id="recent-posts" class="p-newsList__heading">
              <?php echo "<a href=\"" . $term_link . "\">" .  $term_name . "</a>"; ?>
            </p>

        </div>
      <?php endif; ?>
      <div class="p-sidebar__share">
        <?php $icon = plugins_url('my-original-plugin/assets/dist/media/my-twitter-spindle-min.svg'); ?>
        <?php $icon_fb = plugins_url('my-original-plugin/assets/dist/media/my-facebook-min.svg'); ?>
        <!-- <p class="u-en u-uppercase">share</p> -->
        <ul>
          <li>
            <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title_attribute(); ?>" ; target="_blank"><img src="<?php echo esc_url($icon); ?>" alt=""></a>
          </li>
          <li>
            <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" target="_blank"><img src="<?php echo esc_url($icon_fb); ?>" alt=""></a>
          </li>
        </ul>
      </div>
      </div>
      <!-- END //.p-sidebar__content -->

    <?php
    }
  // END is_object_in_term($post->ID, 'achievement_tag', ['design', 'building-website', 'working-on']) && is_singular('achievement'))
  elseif (is_singular('skill')) :
    global $post;
    $terms = get_the_terms($post->ID,  'skill_cat');
    foreach ($terms as $term) {
      //if ($term) :
      $term_name = $term->name;
      $term_slug = $term->slug;
      $term_link = get_term_link($term_slug, 'skill_cat');
      $icon = plugins_url('my-original-plugin/assets/dist/media/my-twitter-spindle-min.svg');
      $icon_fb = plugins_url('my-original-plugin/assets/dist/media/my-facebook-min.svg');
    ?>
      <div class="p-sidebar__content">
        <div class="p-sidebar__heading">
          <p class="p-sidebar__date"><?php the_date('Y.m.d'); ?></p>
          <!-- 見出しに$term-nameがない場合はfalse(非表示)に: 条件分岐 -->
          <?php if ($term) : ?>
            <p id="recent-posts" class="p-newsList__heading">
              <?php echo "<a href=\"" . $term_link . "\">" .  $term_name . "</a>"; ?>
            </p>

        </div>
      <?php endif; ?>
      <div class="p-sidebar__share">
        <!-- <p class="u-en u-uppercase">share</p> -->
        <ul>
          <li>
            <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title_attribute(); ?>" ; target="_blank"><img src="<?php echo esc_url($icon); ?>" alt=""></a>
          </li>
          <li>
            <a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" target="_blank"><img src="<?php echo esc_url($icon_fb); ?>" alt=""></a>
          </li>
        </ul>
      </div>
      </div>
      <!-- END //.p-sidebar__content -->
    <?php
    }
  elseif (is_singular('about')) :
    global $post;
    ?>
    <div>ここに'about'に関する見出しが入ります</div>
<?php
  endif;
}
add_action('arkhe_start_sidebar', 'custom_post_sidebar', 10);
