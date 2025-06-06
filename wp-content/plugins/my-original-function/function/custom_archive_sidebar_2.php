<?php
//サイドバーカスタム(アーカイブページ)
// ref. WordPress タクソノミーのタームを一覧取得して表示する 投稿/親/子/孫
//url: https://www.nowte.net/wordpress/wordpress-get-term-list/
function custom_archive_sidebar()
{
  $cat_name = 'category';
  $tag_name = 'keyword';
  if (is_post_type_archive('achievement')) :
    $tax_name = 'achievement_cat';
    $args = [
      'exclude' => []
    ];
    $taxonomy_terms = get_terms($tax_name, $args); //ref. 関数リファレンス get_terms: 直近の子タームを返す。0の場合はトップレベルのタームのみを返す
    //URL: https://bit.ly/3IAt8JB
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        if ($taxonomy_term) :
          $taxonomy_term_link = get_term_link($taxonomy_term);
          $taxonomy_term_name = $taxonomy_term->name;
?>
          <li class="p-sidebar__item"><a href=" <?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
        endif;
      endforeach;
      echo '</ul></div>';
    }
    ?>
    <?php elseif (is_post_type_archive('skill')) :
    $tax_name = 'skill_cat';
    $taxonomy_terms = get_terms($tax_name);
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        if ($taxonomy_term) : //親タームがない(0が代入されるとき)条件
          $taxonomy_term_link = get_term_link($taxonomy_term);
          $taxonomy_term_name = $taxonomy_term->name;
    ?>
          <li class="p-sidebar__item"><a href=" <?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
        endif;
      endforeach;
      echo '</ul></div>';
    }

    ?>
    <?php elseif (is_tax('achievement_cat')) :
    $tax_name = 'achievement_cat';
    $args = [
      'exclude' => [52, 56, 57, 58, 59, 60, 61, 64, 68, 71, 72]
    ];
    $taxonomy_terms = get_terms($tax_name, $args, array('parent' => 0));
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        if ($taxonomy_term) :
          $taxonomy_term_link = get_term_link($taxonomy_term);
          $taxonomy_term_name = $taxonomy_term->name;
    ?>
          <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>

    <?php
        endif;
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
    <?php elseif (is_tax('skill_cat')) :
    $tax_name = 'skill_cat';
    $taxonomy_terms = get_terms($tax_name, array('parent' => 0));
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        if ($taxonomy_term->parent == 0) :
          $taxonomy_term_link = get_term_link($taxonomy_term);
          $taxonomy_term_name = $taxonomy_term->name;
    ?>
          <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
        endif;
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
    <?php elseif (is_tax('achievement_tag')) :
    $tax_name = 'achievement_tag';
    $args = [
      'exclude' => [36, 37, 38, 39, 40, 43]
    ];
    $taxonomy_terms = get_terms($tax_name, $args, array('parent' => 0));
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        if ($taxonomy_term->parent == 0) :
          $taxonomy_term_link = get_term_link($taxonomy_term);
          $taxonomy_term_name = $taxonomy_term->name;
    ?>
          <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
        endif;
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
    <?php elseif (is_tax('skill_tag')) :
    $tax_name = 'skill_cat';
    $args = [
      'exclude' => []
    ];
    $taxonomy_terms = get_terms($tax_name, $args, array('parent' => 0));
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        if ($taxonomy_term->parent == 0) :
          $taxonomy_term_link = get_term_link($taxonomy_term);
          $taxonomy_term_name = $taxonomy_term->name;
    ?>
          <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
        endif;
      endforeach;
      echo '</ul><</div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>

  <?php
  elseif (is_page()) :
    global $post;
  ?>
    <div>固定ページのサイドバーの内容です。ここに見出しが入りますだす</div>
    <?php
    echo do_shortcode('[toc]');
    ?>


<?php endif;
}
add_action('arkhe_start_sidebar', 'custom_archive_sidebar', 10);
