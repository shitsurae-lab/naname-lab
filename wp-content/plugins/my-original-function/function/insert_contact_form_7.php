<?php
function redirect_to_thanks_page()
{
  $homeUrl = home_url();
  echo <<< EOD
    <script>
      document.addEventListener( 'wpcf7mailsent', function( event ) {
        location = '{$homeUrl}/inquiry-completed';
      }, false );
    </script>
  EOD;
}
add_action('wp_footer', 'redirect_to_thanks_page');
