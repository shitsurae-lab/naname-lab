<?php
function remove_protected($title)
{
  return '%s';
}
add_filter('protected_title_format', 'remove_protected');
