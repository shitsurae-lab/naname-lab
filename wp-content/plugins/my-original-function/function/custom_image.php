<?php function add_custom_image()
//アイキャッチ画像のサイズ調整
{
  add_theme_support('post-thumbnails');
  add_image_size('square400-crop', '400', '400', array('center', 'center'));
  add_image_size('square400', '400', '400', false);
  add_image_size('square800-crop', '800', '800', array('center', 'center'));
  add_image_size('square800', '800', '800', false);
}
add_action('after_setup_theme', 'add_custom_image');
