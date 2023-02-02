<?php
function over_write_top()
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
    <div class="p-hero__blendParticles">
      <div id="particles-js"></div>
      <div class="p-hero__blendParticles__container normal">
        <div class="p-hero__catch">
          <p><span><?php //echo $heroTitle;
                    ?></span><span>naname design lab</span></p>
        </div>
      </div>
      <div class="p-hero__blendParticles__container burn">
        <div class="p-hero__catch">
          <p><span><?php //echo $heroTitle;
                    ?></span><span>naname design lab</span></p>
        </div>
      </div>
    </div>
    <div class="home-hero__end--title l-main__title">
      <div class="p-page__title c-pageTitle">
        <?php
        //$page = get_post(get_the_ID());
        //echo '<h1 class="c-pageTitle__main">' . get_the_title($the_id) . '</h1>'; // the_title() に倣ってエスケープ関数はなし
        ?>
      </div>
    </div>
<?php
  }

  //return $parts_content; //--> 文末に$parts_contentを記述すると修正前の内容も読み込まれてしまう
  return;
}
add_filter('arkhe_part__page/title', 'over_write_top', 10);
