<?php
//サイドバーカスタム(アーカイブページ)

function custom_archive_sidebar()
{
  if (is_post_type_archive('achievement')) :
    $taxonomy_terms = get_terms('achievement_cat'); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = $taxonomy_term->name;
?>
        <li class="p-sidebar__item"><a href=" <?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul></div>';
    }
    ?>
    <?php elseif (is_post_type_archive('info')) :
    $taxonomy_terms = get_terms('info_cat'); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = $taxonomy_term->name;
    ?>
        <li class="p-sidebar__item"><a href=" <?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul></div>';
    }

    ?>
  <?php elseif (is_tax('achievement_cat')) :
  ?>
    <?php
    $taxonomy_terms = get_terms('achievement_cat'); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = $taxonomy_term->name;
    ?>
        <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
  <?php elseif (is_tax('info_cat')) :
  ?>
    <?php
    $taxonomy_terms = get_terms('info_cat'); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = $taxonomy_term->name;
    ?>
        <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
  <?php elseif (is_tax('achievement_tag')) :
  ?>
    <?php
    $taxonomy_terms = get_terms('achievement_tag'); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = $taxonomy_term->name;
    ?>
        <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
  <?php elseif (is_tax('info_tag')) :
  ?>
    <?php
    $taxonomy_terms = get_terms('info_tag'); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) :
      echo ' <div class="p-sidebar__content"><ul>';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = $taxonomy_term->name;
    ?>
        <li class="p-sidebar__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul></div>';
    endif; //タクソノミー有無の条件分岐終了
    ?>
  <?php elseif (is_archive(array('achievement_cat', 'skills_cat'))) :
    $arr = array(
      'hide_empty' => 0, // 投稿のないカテゴリーも含める
    );
    $categories = get_categories($arr);
  ?>
    <div class="p-sidebar__content">
      <ul>
        <?php //foreach ($categories as $category) :
        ?>
        <li class="p-sidebar__item">
          <a href="<?php //echo get_category_link($category->term_id);
                    ?>">
            <?php //echo $category->name;
            ?>
          </a>
        </li>
        <?php //endforeach;
        ?>
      </ul>
    </div>
    <div>ここにタームアーカイブに関する見出しが入ります</div>
  <?php

  elseif (is_page()) :
    global $post;
  ?>
    <div>固定ページのサイドバーの内容です。ここに見出しが入ります</div>
<?php endif;
}
add_action('arkhe_start_sidebar', 'custom_archive_sidebar', 10);
