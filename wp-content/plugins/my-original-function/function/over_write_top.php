<?php
function over_write_top($parts_content)
{
  /**
   * 固定ページヘッド部分 (コンテンツ内)
   *   home.php からも呼ばれることに注意。
   */
  $the_id = get_queried_object_id();
  if (!$the_id) return;
  if (is_home()) {
    $heroTitle = strtoupper('news');
?>
    <!-- <section class="l-hero">
      <div class="home-hero">
        <div class="home-hero--text">
          <div class="home-hero--text__contaienr">
            <p><?php echo $heroTitle; ?></p>
            <?php
            // サブタイトル
            $subtitle = apply_filters('arkhe_page_subtitle', '', $the_id, 'inner');
            if ('' !== $subtitle) :
              echo '<p class="c-pageTitle__sub--custom">' . wp_kses($subtitle, Arkhe::$allowed_text_html) . '</p>';
            endif; ?>
          </div>
        </div>
        <div class="home-hero--bg"></div>
      </div>
      <div class="home-hero__end--title l-main__title">
        <div class="p-page__title c-pageTitle">
          <?php
          $page = get_post(get_the_ID());
          echo '<h1 class="c-pageTitle__main">' . get_the_title($the_id) . '</h1>'; // the_title() に倣ってエスケープ関数はなし
          ?>
        </div>
      </div>
    </section> -->
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
                      <span class="c-line"><?php echo $heroTitle; ?></span>
                      <span class="c-line">by nanme design labo...</span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="p-postHero__text--inner burn">
                <div class="p-postHero__text--catch">
                  <div class="p-postHero__text--bundle">
                    <p class="c-text">
                      <span class="c-line"><?php echo $heroTitle; ?></span>
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
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php echo $heroTitle; ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php echo $heroTitle; ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
    <div class="home-hero__end--title l-main__title">
      <div class="p-page__title c-pageTitle">
        <?php
        $page = get_post(get_the_ID());
        echo '<h1 class="c-pageTitle__main">' . get_the_title($the_id) . '</h1>'; // the_title() に倣ってエスケープ関数はなし
        ?>
      </div>
    </div>
<?php
  }

  //return $parts_content; //--> 文末に$parts_contentを記述すると修正前の内容も読み込まれてしまう
  return;
}
add_filter('arkhe_part__page/title', 'over_write_top', 10);
