<?php


function undefined_null($array, $key)
{
  $return = isset($array[$key]) ? $array[$key] : NULL;
  return $return;
}

function show_index_content()
{
  if (!is_front_page()) return;
  $index = 0; // インデックス用カウンター
  $page_slugs = array('about', 'achievement_cat');
  //$upload_baseurl = wp_upload_dir()['baseurl']; //メディアまでのURLを$media_urlに格納

  foreach ($page_slugs as $slug) {
    $index++; // カウンター
    $page = get_page_by_path($slug);
    if ($page) :
      setup_postdata($page);

      // 偶数/奇数で背景色クラスを決定
      $is_primary = ($index % 2 === 1); // 奇数ならtrue

      $bg_class = $is_primary ? 'u-bg--primary' : 'u-bg--secondary';

      // 背景色がprimaryならflexもprimary、secondaryならflexもsecondary
      $flex_class = $is_primary ? 'u-flex--primary' : 'u-flex--secondary';

      $thumb_url = esc_url(wp_get_attachment_url(get_post_thumbnail_id($page->ID))); // アイキャッチ画像URL
      $title = get_the_title($page->ID); // ページタイトル
      $permalink_url = esc_url(get_permalink($page->ID)); // パーマリンク
      $desc = get_field('mv_desc', $page->ID); // ACF説明文
      $heading_en = get_field('mv_title', $page->ID); // ACF 英語見出し
      $subheading_en = get_field('mv_subtitle', $page->ID); // ACF 英語見出し



?>
      <section class="section p-index-section <?php echo $bg_class; ?> js-appearance" data-section>

        <div class="p-index-section__inner" data-section-inner>
          <div class="p-index-section__container  <?php echo $flex_class; ?>">
            <div class="p-index-section__image">

              <div class="c-zoom--box">
                <img src="<?php echo $thumb_url; ?>" alt="<?php echo esc_attr($title); ?>" class="c-zoom--img">
              </div>
              <!-- END c-zoom--box -->
            </div>
            <div class="p-index-section__content">
              <div class="p-index-section__head">
                <h2 class="p-index-section__heading">
                  <span class="p-index-section__heading--sub u-uppercase u-montserrat"><?php echo esc_html($heading_en); ?></span>
                  <span class="p-index-section__heading--main"><?php echo esc_html($title); ?></span>
                </h2>
                <p class="p-index-section__desc u-montserrat u-uppercase"><?php echo esc_html($subheading_en); ?></p>
              </div>
              <div class="p-index-section__body">
                <p><?php echo nl2br(esc_html($desc)); ?></p>
              </div>
              <div class="p-index-section__link">
                <a href=" <?php echo $permalink_url; ?>" class="u-arkhe-custom-button" data-has-icon="1"><span class="ark-block-button__text">more</span><svg class="ark-block-button__icon -right" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" data-icon="LsChevronRight" viewBox="0 0 48 48">
                    <path d="m33 25.1-13.1 13c-.8.8-2 .8-2.8 0-.8-.8-.8-2 0-2.8L28.4 24 17.1 12.7c-.8-.8-.8-2 0-2.8.8-.8 2-.8 2.8 0l13.1 13c.6.6.6 1.6 0 2.2z"></path>
                  </svg></a>
              </div>
            </div>
          </div>
        </div>
      </section>
    <?php
      wp_reset_postdata();
    endif;
  }
}

add_action('arkhe_before_front_content', 'show_index_content', 10);

function show_achievement_cat_terms_list()
{
  // 固定ページ "achievement_cat" でのみ表示
  //if (!is_page('achievement_cat')) return;
  if (is_page('achievement_cat')):
    // カスタムタクソノミーの取得
    $terms = get_terms(array(
      'taxonomy' => 'achievement_cat',
      'hide_empty' => false,
    ));

    if (empty($terms) || is_wp_error($terms)) return;

    echo '<ul class="p-postList -type-card">'; // グリッドラッパー（3列・Arkheに合うクラス）

    foreach ($terms as $term) {
      $term_link = get_term_link($term);
      $term_title = esc_html($term->name);
      $term_description = esc_html($term->description);

      // ACFの画像（あれば）
      $image_id = get_field('term_image', $term->taxonomy . '_' . $term->term_id);
      $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : get_template_directory_uri() . '/assets/img/noimg.png';
      echo '<li class="p-postList__item">';
      echo '<a class="p-postList__link" href="' . esc_url($term_link) . '">';
      echo '  <figure class="p-postList__thumb c-postThumb"><img src="' . esc_url($image_url) . '" alt="' . $term_title . '"></figure>';
      echo '  <div class="p-postList__body">';
      echo '    <h3 class="p-postList__title">' . $term_title . '</h3>';
      if ($term_description) {
        echo '    <p class="p-postList__exerpt">' . $term_description . '</p>';
      }
      echo '  </div>';
      echo '</a>';
      echo '</li>';
    }

    echo '</ul>';
  elseif (is_page('about')):
    $media_url = wp_upload_dir()['baseurl']; //メディアまでのURLを$media_urlに格納
    ?>
    <div class="p-container">
      <section class="p-timeline">
        <article class="p-timeline__item">
          <div class="p-timeline__content js-timeline-item">
            <div class="p-timeline__heading -mb40">
              <h2>自己紹介</h2>
            </div>
            <p>フロントエンド開発を中心に、WordPressを用いたサイト構築やJavaScriptによるインタラクション実装を手がけています。
              特に、ユーザー体験を意識したUI開発と、保守性・拡張性を意識した設計に注力しています。
              <br><br>
              WebpackやSCSSによるモジュール設計、ScrollTriggerやGSAPを用いたアニメーション実装にも対応。業務外ではReactやNext.js、GitHub Actionsなどの技術にも継続的に取り組み、開発環境の最適化とコード品質の向上に努めています。
              <br><br>
              デザインと実装の架け橋となり、プロジェクトの目的に寄り添ったWeb体験を届けることを大切にしています。
            </p>
          </div>
        </article>
        <article class="p-timeline__item">
          <div class="p-timeline__content js-timeline-item">
            <div class="p-timeline__heading">
              <h2>スキルセット</h2>
            </div>
            <!--    END p-timeline__heading -->
            <table class="p-skill-table">
              <tbody>
                <tr>
                  <th>開発スキル/環境</th>
                  <td>
                    <p class="p-skill-title">フロントエンド</p>
                    <ul class="slash">
                      <li>HTML</li>
                      <li>CSS(Dart Sass)</li>
                      <li>JavaScript</li>
                      <li>GSAP</li>
                    </ul>
                    <p class="p-skill-title">開発環境</p>
                    <ul class="slash">
                      <li>webpack</li>
                      <li>Gulp</li>
                      <li>Node.js</li>
                      <li>NPM</li>
                    </ul>
                    <p class="p-skill-title">CMS</p>
                    <ul class="slash">
                      <li>WordPress</li>
                    </ul>
                    <p class="p-skill-title">バージョン管理</p>
                    <ul class="slash">
                      <li>Github</li>
                      <li>Sourcetree</li>
                    </ul>
                    <p class="p-skill-title">CI/CD</p>
                    <ul class="slash">
                      <li>Github Actions</li>
                    </ul>

                  </td>
                </tr>

              </tbody>
            </table>

          </div>
          <!--       END p-timeline__content -->
        </article>
        <article class="p-timeline__item">
          <div class="p-timeline__content js-timeline-item">

            <div class="p-timeline__heading -mb40">
              <h2>得意分野</h2>
            </div>
            <div class="p-strengths__list">
              <div class="p-card js-timeline-item">
                <div class="p-card__image">
                  <div class="c-image">
                    <img src="<?php echo esc_url($media_url . '/2025/05/webdesign.svg') ?>" alt="webデザインアイコン">
                  </div>
                  <div class="p-card__content">
                    <h3>Web Design</h3>
                    <p>Photoshop, Illustrator, Figmaを利用したデザイン制作を行っています。</p>
                    <div class="p-card__chevron">
                      <img src=" <?php echo esc_url($media_url . '/2025/04/chevron-down.svg') ?>" alt="下向き矢印(Chevron down)">
                    </div>
                  </div>
                </div>
                <div class="sci">
                  <p>カラーやタイポグラフィーはスタイルガイド化できるよう心がけています。<br>
                    余白や動線を意識したユーザーフレンドリーなサイト制作を心がけています</p>
                </div>
              </div>
              <div class="p-card js-timeline-item">
                <div class="p-card__image">
                  <div class="c-image">
                    <img src="<?php echo esc_url($media_url . '/2025/05/frontend.svg') ?>" alt="フロントエンドアイコン">
                  </div>
                  <div class="p-card__content">
                    <h3>Frontend</h3>
                    <p>静的サイトからWordPressまで、webpackを活用した柔軟な開発環境を構築。</p>
                    <div class="p-card__chevron">
                      <img src="<?php echo esc_url($media_url . '/2025/04/chevron-down.svg') ?>" alt="下向き矢印(Chevron down)">
                    </div>
                  </div>
                </div>
                <div class="sci">
                  <p>プラグイン開発やテーマ制作でも再利用性を意識し、保守性の高いコード設計を行っています。
                    <br>React.jsやNext.jsの導入にも積極的に取り組み、モダンなフロントエンド技術への理解を深めています。
                  </p>
                </div>
              </div>
              <div class="p-card js-timeline-item">
                <div class="p-card__image">
                  <div class="c-image">
                    <img src="<?php echo esc_url($media_url . '/2025/05/wordpress.svg') ?>" alt="cmsアイコン">
                  </div>
                  <div class="p-card__content">
                    <h3>WordPress</h3>
                    <p>テーマ・プラグインの自作を通じて、編集体験と保守性に優れたWordPressサイトを構築しています。</p>
                    <div class="p-card__chevron">
                      <img src=" <?php echo esc_url($media_url . '/2025/04/chevron-down.svg') ?>" alt="下向き矢印(Chevron down)">
                    </div>
                  </div>
                </div>
                <div class="sci">
                  <p>カスタム投稿タイプやACF（Advanced Custom Fields）を活用し、クライアントごとに最適なUIを提供。
                    WooCommerceを用いたECサイトにも対応し、拡張性とユーザビリティの両立を意識した実装を行っています</p>
                </div>
              </div>
            </div>
          </div>

        </article>
      </section>

    </div>
<?php
  endif;
}
add_action('arkhe_start_page_main', 'show_achievement_cat_terms_list');
