<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.rss
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

// prepare modal to display opt-in
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-rss-optin',
	array(
		'title'       => '<i class="fas fa-rss-square"></i> ' . __('VikAppointments - RSS Opt in', 'vikappointments'),
		'closeButton' => false,
		'keyboard'    => false,
		'top'         => true,
		'width'       => 70,
		'height'      => 80,
		'footer'      => '<button type="button" class="btn btn-success" id="rss-optin-save">' . __('Save') . '</button>',
	),
	$this->sublayout('modal')
);

?>

<script>

	jQuery(document).ready(function() {
		var aborted = false;
		
		if (typeof localStorage !== 'undefined') {
			aborted = localStorage.getItem('vikappointments.rss.aborted') ? true : false;
		}

		if (!aborted) {
			// open modal with a short delay
			setTimeout(function() {
				wpOpenJModal('rss-optin');
			}, 1500);
		}

		jQuery('#rss-optin-save').on('click', function() {
			if (jQuery(this).prop('disabled')) {
				// already submitted
				return false;
			}

			jQuery(this).prop('disabled', true);

			// check opt-in status
			var status = jQuery('input[name="rss_optin_status"]').is(':checked') ? 1 : 0;

			// make AJAX request
			doAjax(
				'admin-ajax.php?action=vikappointments&task=rss.optin',
				{
					status: status,
				},
				function(resp) {
					// auto-dismiss on save
					wpCloseJModal('rss-optin');
				},
				function(error) {
					if (!error.responseText) {
						// use default connection lost error
						error.responseText = Joomla.JText._('CONNECTION_LOST');
					}

					// alert error message
					alert(error.responseText);

					// avoid to spam the dialog again and again at every page load
					if (typeof localStorage !== 'undefined') {
						localStorage.setItem('vikappointments.rss.aborted', 1);
					}

					// auto-dismiss on failure
					wpCloseJModal('rss-optin');
				}
			);
		});
	});

</script>
