<?php
/**
 * Dashboard file
 *
 * @category   Components
 * @package    CookiePro
 * @author     WordPress Dev <wordpress@cookiepro.com>
 * @license    GPL2
 * @link       https://cookiepro.com
 * @since      1.0.4
 */

?>
<div class="notice notice-success is-dismissible <?php echo esc_html_e( 'cookiepro' ); ?>-notice-welcome">
	<p>Thank you for installing CookiePro.</p>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($) {
		$(document).on( 'click', '.<?php echo esc_html_e( 'cookiepro' ); ?>-notice-welcome button.notice-dismiss', function( event ) {
			event.preventDefault();
			$.post( ajaxurl, {
				action: '<?php echo esc_html_e( 'cookiepro _dismiss_dashboard_notices' ); ?>',

			});
			$('.<?php echo 'cookiepro'; ?>-notice-welcome').remove();
		});
	});
</script>
