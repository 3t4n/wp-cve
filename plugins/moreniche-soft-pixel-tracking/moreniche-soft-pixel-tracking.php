<?php
/**
 * Plugin Name:  MoreNiche Soft Pixel Tracking
 * Description:  This plugin adds MoreNiche specific code to your website.
 * Version:      1.0.4
 * Author:       MoreNiche
 * Author URI:   https://moreniche.com/
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Add meta
 */
function mnspt_add_to_head() {
	echo '<meta name="Referrer" content="unsafe-url">';
}

add_action( 'wp_head', 'mnspt_add_to_head' );

/**
 * Add soft pixel script
 */
function mnspt_add_soft_script() {
	echo <<<HTML
    <!-- Moreniche soft pixel (START) -->
    <script type="text/javascript">
        function downloadJSAtOnload() {
            var element = document.createElement("script");
            element.src = "https://mixi.mn/pixel.js?ver=1.04";
            document.body.appendChild(element);
        }
        if (window.addEventListener) {
            window.addEventListener("load", downloadJSAtOnload, false);
        } else if (window.attachEvent) {
            window.attachEvent("onload", downloadJSAtOnload);
        } else {
            window.onload = downloadJSAtOnload;
        }
</script>
<noscript><img height="1" width="1" src="https://mixi.mn/pixel.png"/></noscript>
  <!-- Moreniche soft pixel (END)-->
HTML;
}

add_action( 'wp_footer', 'mnspt_add_soft_script' );