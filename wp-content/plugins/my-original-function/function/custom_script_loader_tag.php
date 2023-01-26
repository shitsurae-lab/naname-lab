<?php
//上述したFont Awesome Scriptに crossorigin="anonymous"を付加する
//crossoriginの直前に半角スペース忘れずにね。※入れないと「js"crosorigin=...」のようにダブルクォーテーションとcrossoriginが連続記述になる
function custom_script_loader_tag($tag, $handle)
{
  if ($handle !== 'fontawesome') {
    return $tag;
  }
  return str_replace('></script>', ' crossorigin="anonymous"></script>', $tag);
}
add_filter('script_loader_tag', 'custom_script_loader_tag', 10, 2);
