<?php
//固定ページコンテンツ前後
function before_page_content() //Before
{
  echo '<div>固定ページのコンテンツの前です</div>';
}
add_action('arkhe_before_page_content', 'before_page_content');

function after_page_content() //After
{
  echo '<div>固定ページのコンテンツの後です</div>';
}
add_action('arkhe_after_page_content', 'after_page_content');

//ブログページコンテンツ前後
function before_home_content() //Before
{
  echo '<main id="main_content" class="l-main l-article">';
}
add_action('arkhe_before_home_content', 'before_home_content');

function after_home_content() //After
{
  echo '</main>';
}
add_action('arkhe_after_home_content', 'after_home_content');

//その他ページ種別ごとのアクションフック
//https://arkhe-theme.com/ja/manual/hooks/
