<?php
/**
 * Code view.
 *
 * @package GoogleAnalytics
 */

$debug_mode_on = isset( $debug_mode_on ) ? $debug_mode_on : false;

$script = ( true === $debug_mode_on ) ?
	'https://google-analytics.com/analytics_debug.js' :
	'https://google-analytics.com/analytics.js';
?>
<script>
(function() {
	(function (i, s, o, g, r, a, m) {
		i['GoogleAnalyticsObject'] = r;
		i[r] = i[r] || function () {
				(i[r].q = i[r].q || []).push(arguments)
			}, i[r].l = 1 * new Date();
		a = s.createElement(o),
			m = s.getElementsByTagName(o)[0];
		a.async = 1;
		a.src = g;
		m.parentNode.insertBefore(a, m)
	})(window, document, 'script', '<?php echo esc_url( $script ); ?>', 'ga');

	ga('create', '<?php echo esc_attr( $data[ Ga_Admin::GA_WEB_PROPERTY_ID_OPTION_NAME ] ); ?>', 'auto');
	<?php if ( 'on' === $data['anonymization'] ) : ?>
	ga('set', 'anonymizeIp', true);
	<?php endif; ?>
	<?php if ( ! empty( $data['optimize'] ) ) : ?>
	ga('require', '<?php echo esc_html( $data['optimize'] ); ?>' );
	<?php endif; ?>
	ga('send', 'pageview');
	})();
</script>
