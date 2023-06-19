<?php
function start_footer_foot()
{
  $upload_dir = wp_upload_dir();
  echo '<ul class="p-footer"><li><ul><li class="p-footer__logo"><a href="' . home_url() . '"><figure><img src="' . esc_url($upload_dir['baseurl'] . '/2022/12/logo_name_type_white.svg') . '" alt="ななめラボ"></figure></a></li><!-- END p-footer__logo--><li class="p-footer__text"><h2>ななめラボ</h2><ul class="p-footer__text--list"><li class="p-footer__text--ttl">follow</li><li><i class="fab fa-twitter fa-fw icon--primary"></i></li><li><i class="fab fa-facebook-f fa-fw icon--primary"></i></li></ul></li><!-- END //.p-footer__text__inner --></ul></li><!-- END //.p-footer__text --><li>';
}
add_action('arkhe_start_footer_foot_content', 'start_footer_foot');

function before_copyright()
{
  echo '</ul><div class="l-footer__copyright">';
}
// add_action('arkhe_start_footer_foot_content', 'insert_footer__nav');
add_action('arkhe_before_copyright', 'before_copyright');

function after_copyright()
{
  echo '</div><!-- END //.l-footer__copyright -->';
}
add_action('arkhe_after_copyright', 'after_copyright');
