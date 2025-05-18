<?php
function insert_accordion()
{
  /*
* details・summaryでアコーディオン。アーカイブページ (archive.php) の<main>後に実行
*/
  //--- 1. カスタム投稿タイプアーカイブ ---//
  //1-1. カスタム投稿タイプアーカイブ('achievement')
  if (is_post_type_archive('achievement')) :
    $args = [
      'exclude' => []
    ];
    $taxonomy_terms = get_terms('achievement_tag', $args); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo '<details class="p-tag__details js-details">
      <summary class="p-tag__summary js-summary">キーワードで絞り込み<span class="p-tag__icon"></span>
      </summary>
      <div class="p-tag__content js-content">
      <div class="p-tag__content__inner">
      <ul class="p-tag__list">';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = '#' . $taxonomy_term->name;
?>
        <li class="p-tag__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul>
      </div>
      </div>
    </details>';
    }
    ?>
    <?php

  //--- 2. カスタムタクソノミーアーカイブ(カテゴリー) ---//
  //2-1. カスタムタクソノミーアーカイブ('カテゴリー: achievement;)
  elseif (is_tax('achievement_cat')) :
    $args = [
      //'exclude' => [36, 37, 38, 39, 40, 43]
      'exclude' => []
    ];
    $taxonomy_terms = get_terms('achievement_tag', $args); //タクソノミー(タグ)スラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo '<details class="p-tag__details js-details">
      <summary class="p-tag__summary js-summary">スキルで絞り込み<span class="p-tag__icon"></span>
      </summary>
      <div class="p-tag__content js-content">
      <div class="p-tag__content__inner">
      <ul class="p-tag__list">';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = '#' . $taxonomy_term->name;
    ?>
        <li class="p-tag__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul>
      </div>
      </div>
      </details>';
    }
    ?>

    <?php
  //--- 3. カスタムタクソノミーアーカイブ(タグ) ---//
  //3-1. カスタムタクソノミーアーカイブ('タグ: achievement;)
  elseif (is_tax('achievement_tag')) :
    $args = [
      'exclude' => []
    ];
    $taxonomy_terms = get_terms('achievement_cat', $args); //タクソノミースラッグを指定
    if (!empty($taxonomy_terms) && !is_wp_error($taxonomy_terms)) {
      echo '<details class="p-tag__details js-details">
      <summary class="p-tag__summary js-summary">カテゴリーで絞り込み<span class="p-tag__icon"></span>
      </summary>
      <div class="p-tag__content js-content">
      <div class="p-tag__content__inner">
      <ul class="p-tag__list">';
      foreach ($taxonomy_terms as $taxonomy_term) :
        $taxonomy_term_link = get_term_link($taxonomy_term);
        $taxonomy_term_name = '#' . $taxonomy_term->name;
    ?>
        <li class="p-tag__item"><a href="<?php echo esc_url($taxonomy_term_link); ?>"><?php echo esc_html($taxonomy_term_name); ?></a></li>
    <?php
      endforeach;
      echo '</ul>
      </div>
      </div>
      </details>';
    }
    ?>

<?php
  endif;
}
add_action('arkhe_end_archive_main', 'insert_accordion');
