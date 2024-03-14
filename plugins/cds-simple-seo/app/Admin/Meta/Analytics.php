<?php 

namespace app\Admin\Meta;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Analytics class for Google and the website header.
 *
 * @since  2.0.0
 */
class Analytics {
	
	/**
	 * Google Analytics
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		global $post;

		if (!is_object($post)) {
			return;
		}

		$acode = get_option('sseo_ganalytics');
		$gcode = get_option('sseo_g4analytics');

		if (!empty($gcode)) {
			/* double line break, we want to see our header code seperated from everything */
			echo "\n\n".'<!-- This site uses the Google Analytics 4 by Simple SEO plugin '.SSEO_VERSION.' - https://wordpress.org/plugins/cds-simple-seo/ -->'."\n".'<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id='.esc_attr($gcode).'"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \''.esc_attr($gcode).'\');
</script>
<!-- / Google Analytics 4 by Simple SEO -->'."\n\n";
}

		if (!empty($acode)) {
			/* double line break, we want to see our header code seperated from everything */
			echo "\n\n".'<!-- This site uses the Google Analytics by Simple SEO plugin '.SSEO_VERSION.' - https://wordpress.org/plugins/cds-simple-seo/ --><script type="text/javascript" src="https://www.google-analytics.com/analytics.js"></script>
<script>
(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');

ga(\'create\', \''.esc_attr($acode).'\', \'auto\');
ga(\'send\', \'pageview\');
</script>
<!-- / Google Analytics by Simple SEO -->'."\n\n";
		}
	}
	
}

?>