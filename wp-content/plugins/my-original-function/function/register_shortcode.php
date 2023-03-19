<?php //呼び出すときは echo do_shortcode('[]');
function register_shortcode()
{
  return '<div class="p-fluid c-fluid--right"></div>'; //ショートコードの基本だが詰まったので記述。return で値を保持
}
add_shortcode('p-fluid--right', 'register_shortcode'); //第一引数はショートコードの名前


function register_svg()
{
  return '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
<path fill="#000000" d="M65.9,-22.4C74.9,6.2,64.4,40.1,41.4,56.7C18.4,73.4,-17,72.7,-41.1,55.4C-65.3,38.2,-78.3,4.3,-69.5,-24C-60.8,-52.3,-30.4,-74.9,-1,-74.6C28.5,-74.3,57,-51,65.9,-22.4Z" transform="translate(100 100)">
<animate attributeType="XML"
    attributeName="d"
    dur="8s"
    repeatCount="indefinite"
    values=" M65.9,-22.4C74.9,6.2,64.4,40.1,41.4,56.7C18.4,73.4,-17,72.7,-41.1,55.4C-65.3,38.2,-78.3,4.3,-69.5,-24C-60.8,-52.3,-30.4,-74.9,-1,-74.6C28.5,-74.3,57,-51,65.9,-22.4Z;            M70.1,-23C79,4.7,66.2,39.2,42.1,56.3C18,73.4,-17.5,73.2,-41.9,55.9C-66.2,38.7,-79.4,4.3,-70.5,-23.3C-61.7,-50.9,-30.8,-71.8,-0.1,-71.8C30.6,-71.7,61.1,-50.8,70.1,-23Z;            M68.8,-23.9C77.6,4.5,65,38.3,41.1,55.4C17.2,72.5,-18,72.9,-41.1,56.2C-64.2,39.5,-75.1,5.8,-66.2,-22.8C-57.3,-51.4,-28.7,-74.8,0.7,-75C30,-75.3,60.1,-52.3,68.8,-23.9Z;            M71,-22.4C80,4.7,67.1,39.5,42.1,57.5C17.2,75.5,-19.6,76.6,-42.6,59.7C-65.5,42.8,-74.5,7.8,-65.1,-19.8C-55.7,-47.5,-27.9,-67.8,1.6,-68.3C31,-68.8,62,-49.5,71,-22.4Z; M69.3,-23.5C78.4,5.5,66.4,40.2,42.2,57.8C17.9,75.4,-18.6,75.7,-42.5,58.4C-66.4,41.1,-77.6,6.1,-68.4,-23C-59.1,-52.2,-29.6,-75.5,0.3,-75.6C30.1,-75.7,60.2,-52.5,69.3,-23.5Z; M62,-19.2C71,7.4,62.4,40.8,40.1,57.5C17.9,74.1,-18,74.2,-41.1,57.3C-64.2,40.4,-74.4,6.4,-65.4,-20.3C-56.4,-47,-28.2,-66.4,-0.8,-66.2C26.5,-65.9,53.1,-45.9,62,-19.2Z; M65.6,-21.9C74.8,6.8,64.8,41.2,42,57.8C19.1,74.4,-16.6,73.3,-41.4,55.6C-66.1,37.9,-79.9,3.8,-71.1,-24.4C-62.4,-52.5,-31.2,-74.6,-1.5,-74.2C28.2,-73.7,56.5,-50.6,65.6,-21.9Z" />
</path>
</svg>';
}
add_shortcode('morphing_svg', 'register_svg');


//START: 下線 + ふわっと丸 + テキスト
function register_arrow_right($atts)
{
  $atts = shortcode_atts(
    array(
      'url' => '/',
      'position' => 'right',
      'text' => 'read more'
    ),
    $atts,
    'c-more'
  );
  return "<p class=\"c-more__arrow u-uppercase -text" . $atts['position'] . "\">
            <a href=\"" . $atts['url'] . "\">
              <span class=\"c-more__arrow--text\">" . strtoupper($atts['text']) . "</span>
            </a>
          </p>";
}
add_shortcode('c-more__right', 'register_arrow_right');
//END: 下線 + ふわっと丸 + テキスト


//START: テキスト + 矢印 横並び
function register_arrow_flex($atts)
{
  $atts = shortcode_atts(
    array(
      'url' => '/',
      'txt' => 'view more'
    ),
    $atts,
    'c-more__flex'
  );
  return  "<div class=\"ark-block-container ark-keep-mt c-more__flex\">
            <p class=\"c-more__flex__container\">
              <a href=\"" . $atts['url'] . "\" class=\"c-more__flex--link u-uppercase\">
                <span class=\"c-more__flex--item\">" . $atts['txt'] . "
                </span>
              </a>
          </p></div>";
}
add_shortcode('c-more__flex', 'register_arrow_flex');

function register_arrow_flex_white($atts)
{
  $atts = shortcode_atts(
    array(
      'url' => '/',
      'txt' => 'view more'
    ),
    $atts,
    'c-more__flex'
  );
  return  "<div class=\"ark-block-container ark-keep-mt c-more__flex\">
            <p class=\"c-more__flex__container\">
              <a href=\"" . $atts['url'] . "\" class=\"c-more__flex--link u-uppercase\">
                <span class=\"c-more__flex--item -white\">" . $atts['txt'] . "
                </span>
              </a>
          </p></div>";
}
add_shortcode('c-more__flex--white', 'register_arrow_flex_white');
//END: テキスト + 矢印 横並び


//START: テキスト + 丸矢印
function register_more_arrow_right($atts)
{
  $image = plugins_url('my-original-plugin/assets/dist/media/arrow_right-copy-min.svg');
  $atts = shortcode_atts(
    array(
      'url' => '/',
      'txt' => 'view more',
      'svg' => $image,
    ),
    $atts,
    'c-more__circle'
  );
  return  "<p class=\"c-more__circle\">
            <a href=\"" . $atts['url'] . "\" class=\"c-more__circle--link\">
            <span class=\"c-more__circle--item\">" . $atts['txt'] . "</span><span class=\"c-more__circle--item\"><svg id=\"more_circle\" xmlns=\"http://www.w3.org/2000/svg\" width=\"12px\" height=\"10px\" viewBox=\"0 0 12 10\"><g id=\"arrow3\"><path d=\"M6.71,10c-.26,0-.52-.1-.72-.3-.19-.19-.28-.45-.28-.7s.1-.52,.3-.72l2.53-2.47H1c-.55,0-1-.45-1-1s.45-1,1-1h7.37L6.05,1.75c-.22-.2-.33-.47-.33-.75,0-.24,.08-.47,.25-.67,.37-.41,1-.45,1.41-.08l4.28,3.81s.02,.02,.04,.03c.08,.08,.15,.17,.2,.26,.01,.03,.02,.05,.03,.08,.05,.11,.07,.23,.08,.36h0v.02s0,.06,0,.08h0s0,0,0,0c0,.1-.03,.19-.06,.27,0,.02-.01,.03-.02,.04-.05,.1-.11,.2-.19,.29,0,.01-.02,.02-.03,.03l-4.28,4.18c-.19,.19-.45,.28-.7,.28Z\"/></g></svg></span>
            </a>
          </p>";
}
add_shortcode('c-more__circle', 'register_more_arrow_right');
//END: テキスト + 丸矢印
