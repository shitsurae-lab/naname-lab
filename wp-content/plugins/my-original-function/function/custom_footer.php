<?php
function start_footer_foot()
{
  $upload_dir = wp_upload_dir();
  echo '<ul class="p-footer"><li class="p-footer__summary"><ul class="p-footer__inner--list"><li class="p-footer__inner--logo"><a href="' . home_url() . '"><figure><img src="' . esc_url($upload_dir['baseurl'] . '/2023/07/logo-naname-circle-wh.svg') . '" alt="ナナメらぼ"></figure></a></li><!-- END p-footer__logo--><li class="p-footer__inner--text"><div class="p-footer__inner--catchphrase"><h2>ナナメらぼ</h2><p>Making Design More Accessible</p></div><!-- END p-footer__inner--catchphrase --><ul class="p-footer__sns"><li class="p-footer__sns--ttl">follow</li><li class="p-footer__sns--icon"><img src="' . esc_url($upload_dir['baseurl']) . '/2023/06/icon-twitter.svg' . '" alt=""></li><li class="p-footer__sns--icon"><img src="' . esc_url($upload_dir['baseurl']) . '/2023/06/icon-github.svg' . '" alt=""></li></ul></li><!-- END //.p-footer__text__inner --></ul></li><!-- END //p-footer__summary --><li class="p-footer__nav">';
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
