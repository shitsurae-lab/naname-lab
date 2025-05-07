<?php
add_filter('walker_nav_menu_start_el', 'insert_attr_title_in_menu', 10, 4);
function insert_attr_title_in_menu($item_output, $item)
{
  if (!empty($item->attr_title)) {
    $item_output = preg_replace('/(<a.*?>[^<]*?)</', '$1' . "<span class=\"u-uppercase u-montserrat u-font--600 u-font--xxs\">{$item->attr_title}</span><", $item_output);
  }
  return $item_output;
}
