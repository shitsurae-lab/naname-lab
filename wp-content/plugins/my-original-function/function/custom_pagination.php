<?php
function customPagination()
{

  /**
   * 前の記事へ & 次の記事へ
   */
  $is_same_term = Arkhe::get_setting('pn_link_is_same_term');

  $prev_post = get_adjacent_post($is_same_term, '', true);
  $next_post = get_adjacent_post($is_same_term, '', false);
  // $prev_thumb = wp_get_attachment_url(get_post_thumbnail_id($prev_post->ID));
  // $next_thumb = wp_get_attachment_url(get_post_thumbnail_id($next_post->ID));
  // $prev_title = get_the_title($prev_post->ID);
  // $next_title = get_the_title($next_post->ID);

  if ($prev_post or $next_post) :
?>
    <?php if (is_singular('achievement')) : ?>
      <div class="p-pagination__list u-flex">

        <!-- https://hirashimatakumi.com/blog/5271.html -->
        <?php //var_dump(esc_html(get_post_type_object(get_post_type())->name));
        ?>
        <!-- 重要: シングルページでタクソノミー名を取得する等 https://into-the-program.com/get-taxonomy-name/ -->
        <?php
        //タクソノミースラッグ取得(単数)
        $taxonomy_slug = array_keys(get_the_taxonomies());
        //タクソノミー情報取得
        //$taxonomy = get_taxonomy($taxonomy_slug[0]);
        //タクソノミー名取得
        // $taxonomy_name = $taxonomy->label;
        //var_dump($taxonomy_slug[0]);
        ?>
        <?php if ($prev_post) : ?>
          <?php
          $prev_url = get_permalink($prev_post->ID);
          echo "<div class=\"p-pagination__item--onethird u-pagination\">
                  <a href=\"" . esc_url($prev_url) . "\">
                      <div class=\"p-pagination__icon -prev u-flex\">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 128 244\"><path d=\"M120 244a7.95 7.95 0 0 1-5.63-2.32l-112-111c-3.1-3.07-3.17-8.06-.15-11.21l112-117c3.06-3.19 8.12-3.3 11.31-.25 3.19 3.06 3.3 8.12.25 11.31L19.22 124.85l106.42 105.47c3.14 3.11 3.16 8.18.05 11.31a7.956 7.956 0 0 1-5.68 2.37Z\"/>
                            </svg>
                      </div>
                  </a>
                </div>";
          ?>
        <?php else : ?>
        <?php
          echo "<div class=\"p-pagination__item--onethird u-pagination\"></div>";
        endif;
        ?>

        <!-- 一覧へ戻る -->
        <?php
        global $post;
        $terms = get_the_terms($post->ID, $taxonomy_slug[0]);
        foreach ($terms as $term) {
          $term_name = $term->name . 'へ戻る';
        }
        echo "<div class=\"p-pagination__item--onethird u-pagination\">
                <p>
                  <a href=" . get_term_link($term->slug, $taxonomy_slug[0]) . ">$term_name</a>
                </p>
              </div>";
        ?>

        <?php if ($next_post) : ?>
          <?php
          $next_url = get_permalink($next_post->ID);
          echo "<div class=\"p-pagination__item--onethird u-pagination\">
                  <a href=\"" . esc_url($next_url) . "\">
                        <div class=\"p-pagination__icon -next u-flex\">
                          <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 128 244\"><path d=\"M8 244c-1.99 0-3.98-.74-5.53-2.22-3.19-3.06-3.3-8.12-.25-11.31l106.56-111.32L2.37 13.68C-.77 10.57-.79 5.5 2.32 2.37 5.43-.77 10.5-.79 13.63 2.32l112 111c3.1 3.07 3.17 8.06.15 11.21l-112 117A7.976 7.976 0 0 1 8 244Z\"/></svg>
                        </div>
                  </a>
              </div>";
          ?>
        <?php else : ?>
        <?php
          echo "<div class=\"p-pagination__item--onethird u-pagination\"></div>";
        endif;
        ?>
      </div>
    <?php elseif (is_singular('info')) : ?>
      <div class="p-pagination__list u-flex">

        <!-- https://hirashimatakumi.com/blog/5271.html -->
        <?php //var_dump(esc_html(get_post_type_object(get_post_type())->name));
        ?>
        <!-- 重要: シングルページでタクソノミー名を取得する等 https://into-the-program.com/get-taxonomy-name/ -->
        <?php
        //タクソノミースラッグ取得(単数)
        $taxonomy_slug = array_keys(get_the_taxonomies());
        //タクソノミー情報取得
        //$taxonomy = get_taxonomy($taxonomy_slug[0]);
        //タクソノミー名取得
        // $taxonomy_name = $taxonomy->label;
        //var_dump($taxonomy_slug[0]);
        ?>
        <?php if ($prev_post) : ?>
          <?php
          $prev_url = get_permalink($prev_post->ID);
          echo "<div class=\"p-pagination__item--onethird u-pagination\">
                  <a href=\"" . esc_url($prev_url) . "\">
                      <div class=\"p-pagination__icon -prev u-flex\">
                          <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 128 244\"><path d=\"M120 244a7.95 7.95 0 0 1-5.63-2.32l-112-111c-3.1-3.07-3.17-8.06-.15-11.21l112-117c3.06-3.19 8.12-3.3 11.31-.25 3.19 3.06 3.3 8.12.25 11.31L19.22 124.85l106.42 105.47c3.14 3.11 3.16 8.18.05 11.31a7.956 7.956 0 0 1-5.68 2.37Z\"/>
                          </svg>
                      </div>
                  </a>
                </div>";
          ?>
        <?php else : ?>
        <?php
          echo "<div class=\"p-pagination__item--onethird u-pagination\"></div>";
        endif;
        ?>
        <!-- 一覧へ戻る -->
        <?php
        global $post;
        $terms = get_the_terms($post->ID, $taxonomy_slug[0]);
        foreach ($terms as $term) {
          $term_name = $term->name . 'へ戻る';
        }
        echo "<div class=\"p-pagination__item--onethird u-pagination\">
                <p>
                  <a href=" . get_term_link($term->slug, $taxonomy_slug[0]) . ">$term_name</a>
                </p>
              </div>";
        ?>

        <?php if ($next_post) : ?>
          <?php
          $next_url = get_permalink($next_post->ID);
          echo "<div class=\"p-pagination__item--onethird u-pagination\">
                  <a href=\"" . esc_url($next_url) . "\">
                        <div class=\"p-pagination__icon -next u-flex\">
                          <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 128 244\"><path d=\"M8 244c-1.99 0-3.98-.74-5.53-2.22-3.19-3.06-3.3-8.12-.25-11.31l106.56-111.32L2.37 13.68C-.77 10.57-.79 5.5 2.32 2.37 5.43-.77 10.5-.79 13.63 2.32l112 111c3.1 3.07 3.17 8.06.15 11.21l-112 117A7.976 7.976 0 0 1 8 244Z\"/></svg>
                        </div>
                  </a>
              </div>";
          ?>
        <?php else : ?>
        <?php
          echo "<div class=\"p-pagination__item--onethird u-pagination\"></div>";
        endif;
        ?>
      </div>
    <?php elseif (is_single()) : ?>
      <div class="p-pagination__list u-flex">

        <!-- https://hirashimatakumi.com/blog/5271.html -->
        <?php //var_dump(esc_html(get_post_type_object(get_post_type())->name));
        ?>
        <!-- 重要: シングルページでタクソノミー名を取得する等 https://into-the-program.com/get-taxonomy-name/ -->
        <?php
        //タクソノミースラッグ取得(単数)
        $taxonomy_slug = array_keys(get_the_taxonomies());
        //タクソノミー情報取得
        //$taxonomy = get_taxonomy($taxonomy_slug[0]);
        //タクソノミー名取得
        // $taxonomy_name = $taxonomy->label;
        //var_dump($taxonomy_slug[0]);
        ?>
        <?php if ($prev_post) : ?>
          <?php
          $prev_url = get_permalink($prev_post->ID);
          echo "<div class=\"p-pagination__item u-pagination\">
                  <a href=\"" . esc_url($prev_url) . "\">
                      <div class=\"p-pagination__icon -prev u-flex\">
                          <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 128 244\"><path d=\"M120 244a7.95 7.95 0 0 1-5.63-2.32l-112-111c-3.1-3.07-3.17-8.06-.15-11.21l112-117c3.06-3.19 8.12-3.3 11.31-.25 3.19 3.06 3.3 8.12.25 11.31L19.22 124.85l106.42 105.47c3.14 3.11 3.16 8.18.05 11.31a7.956 7.956 0 0 1-5.68 2.37Z\"/>
                          </svg>
                      </div>
                  </a>
                </div>";
          ?>
        <?php else : ?>
        <?php
          echo "<div class=\"p-pagination__item u-pagination\"></div>";
        endif;
        ?>


        <?php if ($next_post) : ?>
          <?php
          $next_url = get_permalink($next_post->ID);
          echo "<div class=\"p-pagination__item u-pagination\">
                  <a href=\"" . esc_url($next_url) . "\">
                        <div class=\"p-pagination__icon -next u-flex\">
                          <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 128 244\"><path d=\"M8 244c-1.99 0-3.98-.74-5.53-2.22-3.19-3.06-3.3-8.12-.25-11.31l106.56-111.32L2.37 13.68C-.77 10.57-.79 5.5 2.32 2.37 5.43-.77 10.5-.79 13.63 2.32l112 111c3.1 3.07 3.17 8.06.15 11.21l-112 117A7.976 7.976 0 0 1 8 244Z\"/></svg>
                        </div>
                  </a>
              </div>";
          ?>
        <?php else : ?>
        <?php
          echo "<div class=\"p-pagination__item u-pagination\"></div>";
        endif;
        ?>
      </div>
<?php endif;

  endif;
  return '';
}
add_filter('arkhe_part_path__single/foot/prev_next_link', 'customPagination', 10);
