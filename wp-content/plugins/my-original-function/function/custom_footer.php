<?php
function start_footer_foot()
{
  $upload_dir = wp_upload_dir();
  echo '<div class="l-footer__nav__wrapper"><div class="l-footer__logo"><a href="' . home_url() . '"><img src="' . esc_url($upload_dir['baseurl'] . '/2022/12/logo_name_type_white.svg') . '" alt=""></a></div>';
}
add_action('arkhe_start_footer_foot_content', 'start_footer_foot');

function before_copyright()
{
  echo '</div><div class="l-footer__sns"><nav class="l-footer__sns__container"><ul class="c-sns__list"><li class="c-sns__item"><i class="fab fa-twitter fa-fw"></i></li><li class="c-sns__item"><i class="fab fa-facebook-f fa-fw"></i></li></ul></nav><div class="l-footer__copyright">';
}
// add_action('arkhe_start_footer_foot_content', 'insert_footer__nav');
add_action('arkhe_before_copyright', 'before_copyright');

function after_copyright()
{
  echo '</div><!-- END //.l-footer__copyright --></div><!-- END //.l-footer__sns__wrapper -->';
}
add_action('arkhe_after_copyright', 'after_copyright');
