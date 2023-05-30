<?php


function undefined_null($array, $key)
{
  $return = isset($array[$key]) ? $array[$key] : NULL;
  return $return;
}

function insert_front_slider()
{

  if (is_front_page()) :
    $paged = (get_query_var('page')) ? get_query_var('paged') : 1;
    $list_count = 3; //スライダー数 当初は3

    // -- START: 先頭固定記事制御
    // $sticky = get_option('sticky_posts');
    // if (!empty($sticky))  $list_count -= count($sticky);
    // -- END: 先頭固定記事制御
    global $post;
    // $taxonomy_name = 'achievement_tag';
    $taxonomy_name = 'achievement_cat';
    $post_type = 'achievement';
    $args = array(
      //参考にする https://www.webdesignleaves.com/pr/wp/wp_loops.html
      'posts_per_page' => $list_count,
      'paged' => $paged,
      // * 以下、post_type(カスタム投稿タイプ名)で絞るか、tax_query(カスタムタクソノミー)で絞るかにする
      'post_type' => $post_type, //カスタム投稿タイプ
      'tax_query' => array( //カスタムタクソノミー
        array(
          'taxonomy' => $taxonomy_name,
          // 'field' => 'id',
          // 'terms' => array(21),
          'field' => 'slug',
          'terms' => array('containing-wordpress', 'containing-ecommerce', 'containing-woocommerce', 'containing-private'),
          'operator' => 'IN',
          'include_children' => false,
        )
        // array(
        //   'taxonomy' => 'achievement_cat',
        //   'field' => 'slug',
        //   'terms' => 'achievements'
        // )
      ),
      // 'terms' => 'achievement-category',
      'no_found_rows' => true,
      'order' => 'DESC',  //昇順 or 降順の指定
      'orderby' => 'date'
    );
    $the_query = new WP_Query($args);
    $post_classes = 'swiper-slide';

    //--- START: タームリスト
    $term_query = new WP_Term_Query([
      'taxonomy' => 'achievement_cat'
    ]);
    $list_src = '';
    foreach ($term_query->get_terms() as $term_data) :
      $img = '';
      $term_img = get_term_meta($term_data->term_id, 'ark_meta_ttlbg', true);
      if ($term_img) {
        $img = Arkhe::get_image($term_img, array(
          'size' => 'medium',
          'alt' => $term_data->name,
        ));
      }
      $list_src .= '<a href="' . get_term_link($term_data) . '" class="p-postList__link"><figure class="p-clipper">' . $img . '</figure></a>';
    endforeach;
    //--- END: タームリスト

    /**
     * トップエリア（タームアーカイブ用）
     */
    $term_obj         = get_queried_object();
    $term_id          = $term_obj->term_id;
    $term_description = apply_filters('arkhe_term_description', $term_obj->description, $term_id);
    $show_description = apply_filters('arkhe_show_term_description', !empty($term_description), $term_id);
    $bgimg_id         = apply_filters('arkhe_ttlbg_img_id', 0, $term_id);
    $lazy_type        = apply_filters('arkhe_use_lazy_top_area', false) ? Arkhe::get_lazy_type() : '';

?>
    <section class="p-index__hero">
      <div class="hero-container">
        <div class="swiper hero-swiper l-container">
          <div class="swiper-wrapper">

            <?php if ($the_query->have_posts()) :
              while ($the_query->have_posts()) : $the_query->the_post();

            ?>
                <div id="post-<?php the_id(); ?>" <?php post_class($post_classes); ?>>
                  <div class="swiper-slide__container">
                    <div class="swiper-slide__content">
                      <div class="swiper-slide__textarea">
                        <h2 class="swiper-slide__heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p class="c-more__arrow u-uppercase">
                          <a href="<?php the_permalink(); ?>">
                            <span class="c-more__arrow--text u-uppercase">read more</span>
                          </a>
                        </p>


                      </div>
                      <!-- END //.swiper-slide__textarea -->
                    </div>
                    <!-- END //.swiper-slide__content: image以外のcontent-->
                    <div class="swiper-slide__image">

                      <?php if (has_post_thumbnail()) :
                      ?>

                        <a href="<?php the_permalink(); ?>" class="p-postList__link">
                          <figure class="p-clipper">
                            <?php
                            $post_id = $post->ID;
                            $thumb = get_the_post_thumbnail($post_id, 'full', ['class' => 'c-postThumb__img']);
                            echo $thumb;
                            //アイキャッチ画像URLの取得
                            //$url = get_the_post_thumbnail_url($post_id, 'thumbnail');
                            ?>
                          </figure>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 741.66" width="0" height="0" preserveAspectRatio="xMidYMid meet">
                          <clippath id="cp-top" clipPathUnits="objectBoundingBox">
                            <path transform="scale(0.000833, 0.001348)" d="M1176 0H24C10.75 0 0 10.75 0 24v446.44c0 13.25 10.75 24 24 24h553c12.7 0 23 10.3 23 23v200.22c0 13.25 10.75 24 24 24h552c13.25 0 24-10.75 24-24V24c0-13.25-10.75-24-24-24Z" style="fill:#b3b3b3" />
                          </clippath>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" width="0" height="0" preserveAspectRatio="xMidYMid meet">
                          <clippath id="cp-top--mobile" clipPathUnits="objectBoundingBox">
                            <path transform="scale(0.00125, 0.00125)" d="M776 0H24C10.75 0 0 10.75 0 24v504c0 13.25 10.75 24 24 24h352c13.25 0 24 10.75 24 24v200c0 13.25 10.75 24 24 24h352c13.25 0 24-10.75 24-24V24c0-13.25-10.75-24-24-24Z" style="fill:#b3b3b3" />
                          </clippath>
                        </svg>
                        <!-- </div> -->
                      <?php else : ?>
                        <a href="<?php the_permalink(); ?>">
                          <figure class="p-clipper">
                            <img src="<?php echo esc_url(get_template_directory_uri()) ?>/assets/img/noimg.png" alt="">
                          </figure>
                        </a>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 741.66" width="0" height="0" preserveAspectRatio="xMidYMid meet">
                          <clippath id="cp-top" clipPathUnits="objectBoundingBox">
                            <path transform="scale(0.000833, 0.001348)" d="M1176 0H24C10.75 0 0 10.75 0 24v446.44c0 13.25 10.75 24 24 24h553c12.7 0 23 10.3 23 23v200.22c0 13.25 10.75 24 24 24h552c13.25 0 24-10.75 24-24V24c0-13.25-10.75-24-24-24Z" style="fill:#b3b3b3" />
                          </clippath>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800" width="0" height="0" preserveAspectRatio="xMidYMid meet">
                          <clippath id="cp-top--mobile" clipPathUnits="objectBoundingBox">
                            <path transform="scale(0.00125, 0.00125)" d="M776 0H24C10.75 0 0 10.75 0 24v504c0 13.25 10.75 24 24 24h352c13.25 0 24 10.75 24 24v200c0 13.25 10.75 24 24 24h352c13.25 0 24-10.75 24-24V24c0-13.25-10.75-24-24-24Z" style="fill:#b3b3b3" />
                          </clippath>
                        </svg>
                      <?php endif; ?>

                      <!-- </a> -->
                    </div>
                  </div>
                  <!-- END swiper-slide__container -->
                </div>
                <!-- END swiper-slide -->
            <?php endwhile;
            endif;
            wp_reset_postdata();
            ?>

          </div>
          <!-- END //.swiper-wrapper -->

        </div>
        <!--END //.swiper-->
      </div>
      <!-- END //.hero-container -->
      <div class="swiper-pagination"></div>
      <!-- END //.swiper-pagination -->
    </section>


<?php
  endif;
}
add_action('arkhe_before_front_content', 'insert_front_slider', 10);
