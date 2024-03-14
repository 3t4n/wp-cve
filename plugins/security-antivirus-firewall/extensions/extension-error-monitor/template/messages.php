<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */
if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div id="extension_error_monitor_messages" class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $extensionTitle; ?> &nbsp;
					<button class="btn btn-xs btn-info js-hide-all-messages">
						<?php _e('Hide all', 'wptsaf_security'); ?>
					</button>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<?php foreach ($messages as $message) : ?>
					<div class="message">
						<?php printf(
							__('Extension "%s" is disabled at %s because errors was happened with him.', 'wptsaf_security'),
							$message['extension'],
							$message['date_gmt']
						); ?>
						&nbsp;
						<button class="btn btn-xs btn-info js-hide-message" data-id="<?php echo $message['id']; ?>">
							<?php _e('Hide', 'wptsaf_security'); ?>
						</button>
						<div class="clear"></div>
						<br>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<script>
	(function ($) {
		var $blockMessages = $('#extension_error_monitor_messages');

		$blockMessages.on('click', 'button.js-hide-message', function () {
			var $this = $(this),
				id = parseInt($this.attr('data-id')),
				$message = $this.closest('div.message');

			wptsafDataAction.processAction(
				$message,
				'action=wptsaf_security&extension=extension-error-monitor&method=hideMessage&args[id]=' + id,
				{},
				false,
				function ($target) {
					$target.remove();
					if (0 == $blockMessages.find('div.message').length) {
						$blockMessages.remove();
					}
				}
			);
		});

		$blockMessages.on('click', 'button.js-hide-all-messages', function () {
			var $this = $(this);

			wptsafDataAction.processAction(
				null,
				'action=wptsaf_security&extension=extension-error-monitor&method=hideAllMessages',
				{},
				false,
				function () {
					$this.remove();
					$blockMessages.remove();
				}
			);
		});
	})(jQuery);
</script>