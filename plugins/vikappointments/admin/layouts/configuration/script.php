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

/**
 * Layout variables
 * -----------------
 * @param 	string   suffix  An optional suffix to support different configurations.
 */
extract($displayData);

$suffix = isset($suffix) ? $suffix : '';

?>

<script>

	jQuery(function($) {
		// handle main configuration nav buttons
		$('.vaptabli').on('click', function() {
			if ($(this).hasClass('vapconfigtabactive')) {
				// pane already selected
				return false;
			}

			$('.vaptabli').removeClass('vapconfigtabactive');
			$(this).addClass('vapconfigtabactive');

			let tab = $(this).data('id');
			
			$('.vaptabview').hide();
			$('#vaptabview' + tab).show();

			/**
			 * Store active tab in a cookie and keep it there until the session expires.
			 *
			 * @since 1.7
			 */
			document.cookie = 'vikappointments.config<?php echo $suffix; ?>.tab=' + tab + '; path=/';
		});

		// create lambda to register the selected tab within a cookie
		const cacheActiveTab = (pane, tab) => {
			let paneId = $(pane).attr('id').replace(/^vaptabview/, '');
			let tabId  = $(tab).data('id');

			document.cookie = 'vikappointments.config<?php echo $suffix; ?>.tab' + paneId + '=' + tabId + '; path=/';
		};

		// handle configuration panel nav buttons
		$('.vaptabview .config-panel-subnav li').on('click', function() {
			if ($(this).hasClass('active')) {
				// pane already selected
				return false;
			}

			// back to parent tab
			const pane = $(this).closest('.vaptabview');

			pane.find('.config-panel-subnav li').removeClass('active');
			pane.find('.config-panel-tabview-inner').hide();

			$(this).addClass('active');
			pane.find('.config-panel-tabview-inner')
				.filter('[data-id="' + $(this).data('id') + '"]')
					.show();

			cacheActiveTab(pane, this);
		});

		// check if the URL requested a specific setting
		if (document.location.hash) {
			// get setting input (starts with the specified hash)
			const input = $('*[name^="' + document.location.hash.replace(/^#/, '') + '"]').first();

			// extract fieldset ID
			const idFieldset = input.closest('.config-panel-tabview-inner[data-id]').data('id');

			// find tab view to which the input belong
			const tabView = input.closest('.vaptabview');

			// extract tabView index from ID
			const matches = tabView.attr('id').match(/^vaptabview(\d+)$/);

			if (matches && matches.length) {
				// activate the tab view of the input
				$('.vaptabli[data-id="' + matches[1] + '"]').trigger('click');
				// active the inner fieldset
				$('.config-panel-subnav li[data-id="' + idFieldset + '"]').trigger('click');
				// set the focus to the input
				$(input).focus();
				// animate to the input position
				$('html, body').animate({ scrollTop: input.offset().top - 200 });
			}
		}
	});

</script>
