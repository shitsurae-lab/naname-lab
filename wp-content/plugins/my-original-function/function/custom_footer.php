<?php
function start_footer_foot()
{
  $upload_dir = wp_upload_dir();
  echo '<ul class="p-footer"><li class="p-footer__summary"><ul class="p-footer__inner--list"><li class="p-footer__inner--logo"><a href="' . home_url() . '"><figure><img src="' . esc_url($upload_dir['baseurl'] . '/2025/05/logo-secondary.svg') . '" alt="ナナメらぼ"></figure></a></li><!-- END p-footer__logo--><li class="p-footer__inner--text"><div class="p-footer__inner--catchphrase"><h2>ナナメらぼ</h2><p>From Design to Code, with Empathy</p></div><!-- END p-footer__inner--catchphrase --></li><!-- END //.p-footer__text__inner --></ul></li><!-- END //p-footer__summary --><li class="p-footer__nav">';
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
