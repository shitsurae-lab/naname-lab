<?php
/*
Plugin Name: My Original Plugin
Description: CSS,JSカスタマイズ用のプラグインです
Version: 1.0
Author: Toshiyuki Kurashima
*/
if (!defined('ABSPATH')) exit;

/**
 * 必要な定数を改めて定義&コードの中身を隠す
 */
if (!defined('MY_PLUGIN_VERSION'));
define('MY_PLUGIN_VERSION', '1.0');
if (!defined('MY_PLUGIN_PATH'));
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
if (!defined('MY_PLUGIN_URL'));
define('MY_PLUGIN_URL', plugins_url('/', __FILE__));
if (!defined('MY_CSS_URL'));


// ↑↑ 文字列を定数化
// MY_PLUGIN_PATH -> "/app/public/wp-content/plugins/my-original-plugin/"
// MY_PLUGIN_URL -> "https://example.com/wp-content/plugins/my-original-plugin/"


/* JavaScript・CSSの読み込み */
add_action('wp_enqueue_scripts', function () {
  /* VANTA */
  //three.js
  // wp_enqueue_script('three', '//cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js', array(), MY_PLUGIN_VERSION, true);
  //vanta
  // wp_enqueue_script('clouds', '//cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.clouds.min.js', array(), MY_PLUGIN_VERSION, true);

  //GSAPをnpmで読み込む(index.js)ため、以下CDNをコメントアウト
  /* JavaScript */
  //GSAP
  // wp_enqueue_script('gsap', '//cdn.jsdelivr.net/npm/gsap@3.7.0/dist/gsap.min.js');
  // wp_enqueue_script('triggerJS', '//cdn.jsdelivr.net/npm/gsap@3.7.0/dist/ScrollTrigger.min.js');
  //JS
  // wp_enqueue_script(
  //   'my-original-script',
  //   MY_PLUGIN_URL . 'assets/js/custom.js',
  //   array(),
  //   MY_PLUGIN_VERSION,
  //   true
  // );

  //Font Awesome (Font Awesome Scriptに crossorigin="anonymous"を付加するためにフィルターフックに別途記述)
  wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/27b2d21142.js', array(), '', false);


  //particles.js CDN
  wp_enqueue_script('particles', '//cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js', array(), MY_PLUGIN_VERSION, true);

  wp_enqueue_script('main', MY_PLUGIN_URL . 'assets/dist/js/main.js', array(), MY_PLUGIN_VERSION, true);

  // CSS
  wp_enqueue_style(
    'my-original-style',
    // MY_PLUGIN_URL . 'assets/css/custom.css',
    MY_PLUGIN_URL . 'assets/dist/css/main.css',
    array(),
    MY_PLUGIN_VERSION
  );
});


/* フィルターフック */
//上述したFont Awesome Scriptに crossorigin="anonymous"を付加する
//crossoriginの直前に半角スペース忘れずにね。※入れないと「js"crosorigin=...」のようにダブルクォーテーションとcrossoriginが連続記述になる
// function custom_script_loader_tag($tag, $handle)
// {
//   if ($handle !== 'fontawesome') {
//     return $tag;
//   }
//   return str_replace('></script>', ' crossorigin="anonymous"></script>', $tag);
// }
// add_filter('script_loader_tag', 'custom_script_loader_tag', 10, 2);

/*---- Google Web Fonts ----*/
function add_google_fonts()
{
  wp_register_style(
    'googleFonts',
    // 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,700;0,800;0,900;1,600&display=swap'
    'https://fonts.googleapis.com/css2?family=Alex+Brush&family=Allura&family=Montserrat:wght@200;300;400;500;600;700;800;900&family=Hind:wght@300;400;500;600;700&family=Parisienne&display=swap'
  );
  wp_enqueue_style('googleFonts');
}
add_action('wp_enqueue_scripts', 'add_google_fonts');


/* --Google Tag Manager --*/
//head内にGTMコードを記載
function add_gtm_head()
{
  echo "<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({\'gtm.start\':
new Date().getTime(),event:\'gtm.js\'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!=\'dataLayer\'?\'&l='+l:\'\';j.async=true;j.src=
\'https://www.googletagmanager.com/gtm.js?id=\'+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,\'script\',\'dataLayer\',\'GTM-TV95XN58\');</script>
<!-- End Google Tag Manager -->";
}
add_action('wp_enqueue_scripts', 'add_gtm_head');

//body直下にGTMコード記載
function add_gtm_body()
{
  echo "<!-- Google Tag Manager (noscript) -->
<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-TV95XN58\"
height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->";
}
add_action('wp_body_open', 'add_gtm_body');

/* -- ミエルカヒートマップ --*/
function add_mieruca_head()
{
  echo "\n<!-- Begin Mieruca Embed Code -->
<script type=\"text/javascript\" id=\"mierucajs\">
window.__fid = window.__fid || [];__fid.push([556739964]);
(function() {
function mieruca(){if(typeof window.__fjsld != \"undefined\") return; window.__fjsld = 1; var fjs = document.createElement('script'); fjs.type = 'text/javascript'; fjs.async = true; fjs.id = \"fjssync\"; var timestamp = new Date;fjs.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://hm.mieru-ca.com/service/js/mieruca-hm.js?v='+ timestamp.getTime(); var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(fjs, x); };
setTimeout(mieruca, 500); document.readyState != \"complete\" ? (window.attachEvent ? window.attachEvent(\"onload\", mieruca) : window.addEventListener\"load\", mieruca, false)) : mieruca();
})();
</script>
<!-- End Mieruca Embed Code -->";
}

add_action('wp_enqueue_scripts', 'add_mieruca_head');

function add_search_console()
{
  echo "\n" . '<meta name="google-site-verification" content="WL5pFVBfJSWnBHXvjSHWsKSUI9P54HoT158M2DCigBk" />' . "\n";
}
add_action('wp_enqueue_scripts', 'add_search_console');
