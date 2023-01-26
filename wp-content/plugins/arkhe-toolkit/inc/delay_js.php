<?php
namespace Arkhe_Toolkit\Delay_Js;

add_action( 'wp', function() {
	if ( is_admin() ) return;

	if ( \Arkhe_Toolkit::get_data( 'speed', 'use_delay_js' ) ) {
		add_action( 'wp_print_footer_scripts', __NAMESPACE__ . '\inject_scripts_for_delay' );
		ob_start( __NAMESPACE__ . '\rewrite_lazyload_scripts' );
	}
} );


function rewrite_lazyload_scripts( $html ) {
	try {
		// Process only GET requests
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || 'GET' !== $_SERVER['REQUEST_METHOD'] ) return false;

		$html = trim( $html );

		// Detect non-HTML
		if ( ! isset( $html ) || '' === $html || strcasecmp( substr( $html, 0, 5 ), '<?xml' ) === 0 || '<' !== $html[0] ) {
			return false;
		}

		// 除外キーワードのリストを取得
		$prevent_pages = \Arkhe_Toolkit::keywords_to_array( \Arkhe_Toolkit::get_data( 'speed', 'delay_js_prevent_pages' ) );
		$prevent_pages = apply_filters( 'arkhe_toolkit_delay_js_prevent_pages', $prevent_pages );

		// 現在のURL
		$current_url = home_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		// 現在のURLが除外キーワードを含むかどうか
		if ( \Arkhe_Toolkit::is_keyword_included( $current_url, $prevent_pages ) ) {
			return false;
		}

		$new_html = preg_replace_callback(
			'/<script([^>]*?)?>(.*?)?<\/script>/ims',
			__NAMESPACE__ . '\replace_scripts',
			$html
		);

		return $new_html;

	} catch ( Exception $e ) {
		return $html;
	}
}

function replace_scripts( $matches ) {

	$script = $matches[0];
	$attrs  = $matches[1];
	$code   = trim( $matches[2] );

	// 遅延読み込み対象のキーワード
	$delay_js_list = \Arkhe_Toolkit::keywords_to_array( \Arkhe_Toolkit::get_data( 'speed', 'delay_js_list' ) );
	$delay_js_list = apply_filters( 'arkhe_toolkit_delay_js_list', $delay_js_list );

	if ( $code ) {
		if ( \Arkhe_Toolkit::is_keyword_included( $code, $delay_js_list ) ) {
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
			$attrs .= ' data-ark-delayedjs="data:text/javascript;base64,' . base64_encode( $code ) . '"';
			$script = '<script ' . $attrs . '></script>';
		}
	} elseif ( ! empty( $attrs ) ) {

		preg_match( '/\ssrc=[\'"](.*?)[\'"]/', $attrs, $matched_src );
		$src = ( $matched_src ) ? $matched_src[1] : '';

		if ( $src ) {
			if ( \Arkhe_Toolkit::is_keyword_included( $src, $delay_js_list ) ) {
				// src を data-srcへ
				$new_attrs = str_replace( ' src=', ' data-ark-delayedjs=', $attrs );

				// attrs入れ替え
				$script = str_replace( $attrs, $new_attrs, $script );
			}
		}
	}

	return $script;
}

function inject_scripts_for_delay() {
	$timeout = \Arkhe_Toolkit::get_data( 'speed', 'delay_js_time' ) ?: 0;
?>
<script type="text/javascript" id="arkhe-lazyloadscripts">
(function () {
	const timeout = <?php echo esc_attr( intval( $timeout ) * 1000 ); ?>;
	const loadTimer = timeout ? setTimeout(loadJs,timeout) : null;
	const userEvents = ["mouseover","keydown","wheel","touchmove touchend","touchstart touchend"];
	userEvents.forEach(function(e){
		window.addEventListener(e,eTrigger,{passive:!0})
	});
	function eTrigger(){
		loadJs();
		if(null !== loadTimer) clearTimeout(loadTimer);
		userEvents.forEach(function(e){
			window.removeEventListener(e,eTrigger,{passive:!0});
		});
	}
	function loadJs(){
		document.querySelectorAll("script[data-ark-delayedjs]").forEach(function(el){
			el.setAttribute("src",el.getAttribute("data-ark-delayedjs"));
		});
	}
})();
</script>
<?php
}
