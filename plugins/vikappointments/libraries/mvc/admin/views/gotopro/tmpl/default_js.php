<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

?>

<script>

	(function($) {
		'use strict';

		let isRunning = false;

		window['vikWpValidateLicenseKey'] = () => {
			// request
			var lickey = jQuery('#lickey').val();

			if (isRunning || !lickey.length) {
				// prevent double submission until request is over
				return;
			}

			// start running
			vikWpStartValidation();

			UIAjax.do(
				'admin-ajax.php?action=vikappointments&task=license.validate',
				{
					key: lickey
				}, (resp) => {
					vikWpStopValidation();

					// redirect to getpro view
					document.location.href = 'admin.php?page=vikappointments&view=getpro';
				}, (err) => {
					vikWpStopValidation();

					// raise error with a short delay to complete loading animation
					// before prompting the alert with the error
					setTimeout(() => {
						alert(err.responseText);
					}, 32);
				}
			);
		}

		const vikWpStartValidation = () => {
			isRunning = true;
			$('#vikwpvalidate').prepend('<i class="fas fa-sync-alt"></i>');
		}

		const vikWpStopValidation = () => {
			isRunning = false;
			$('#vikwpvalidate').find('i').remove();
		}

		$(function() {
			$('#lickey').keyup(function() {
				$(this).val($(this).val().trim());
			});

			$('#lickey').keypress(function(e) {
				if (e.which == 13) {
					// enter key code pressed, run the validation
					vikWpValidateLicenseKey();
					return false;
				}
			});
		});
	})(jQuery);

</script>
