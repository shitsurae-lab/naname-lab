<?php
function addWave()
{
?>
  <div class=c-footer_wave>
    <svg class="u-editorial" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none">
      <defs>
        <path id="gentle-wave" d="M-160 44c30 0
  58-18 88-18s
  58 18 88 18
  58-18 88-18
  58 18 88 18
  v44h-352z" />
      </defs>
      <g class="u-parallax">
        <use xlink:href="#gentle-wave" x="50" y="0" fill="#9EABDE" />
        <use xlink:href="#gentle-wave" x="50" y="3" fill="#6173B3" />
        <use xlink:href="#gentle-wave" x="50" y="6" fill="#14245e" />
      </g>
    </svg>
  </div>
  <!-- END: footer_wave -->
<?php
}
add_action('arkhe_start_footer_inner', 'addWave');
