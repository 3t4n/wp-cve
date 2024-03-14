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
 * @var  VAPStatisticsWidget  $widget  The instance of the widget to be displayed.
 * @var  mixed                $data    The table rows data.
 */
extract($displayData);

// get active tab
$active = JFactory::getApplication()->input->cookie->get('vap_widget_' . $widget->getName() . '_active_' . $widget->getID(), 'latest');

?>

<div class="canvas-align-top">
	
	<!-- widget container -->

	<div class="vapdash-container">

		<!-- widget tabs -->

		<div class="vapdash-tab-head">
			<div class="vapdash-tab-button">
				<a href="javascript:void(0)" data-pane="latest" class="<?php echo ($active == 'latest' ? 'active' : ''); ?>">
					<?php echo JText::translate('VAPDASHLATESTRESERVATIONS'); ?>
				</a>
			</div>

			<div class="vapdash-tab-button">
				<a href="javascript:void(0)" data-pane="incoming" class="<?php echo ($active == 'incoming' ? 'active' : ''); ?>">
					<?php echo JText::translate('VAPDASHINCOMINGRESERVATIONS'); ?>
				</a>
			</div>
		</div>

		<!-- widget latest users pane -->

		<div class="vapdash-tab-pane" data-pane="latest" style="<?php echo $active == 'latest' ? '' : 'display:none'; ?>">

		</div>

		<!-- widget incoming users pane -->

		<div class="vapdash-tab-pane" data-pane="incoming" style="<?php echo $active == 'incoming' ? '' : 'display:none'; ?>">

		</div>

	</div>

</div>

<script>

	(function($) {
		'use strict';

		/**
		 * Register callback to be executed before
		 * launching the update request.
		 *
		 * @param 	mixed 	widget  The widget selector.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_PREFLIGHTS[<?php echo $widget->getID(); ?>] = (widget, config) => {
			// count disabled panes
			var disabled = 0;
			// reference to first enabled tab
			var firstEnabled = null;
			// flag to check if the current active pane is disabled
			var activeDisabled = false;

			var id = $(widget).attr('id');

			// iterate panes and toggle them according to the widget config
			$(widget).find('.vapdash-tab-head a').each(function() {
				var pane = $(this).data('pane');

				if (config[pane]) {
					$(this).show()
						.parent()
							.show();

					// register tab as first available, only
					// if the flag is still empty
					if (!firstEnabled) {
						firstEnabled = this;
					}
				} else {
					$(this).hide()
						.parent()
							.hide();

					// increase disabled counter
					disabled++;
					// inform the caller that the active pane is disabled
					activeDisabled = activeDisabled || $(this).hasClass('active');
				}
			});

			// hide all tabs in case only one is enabled
			if (disabled < 1) {
				$(widget).find('.vapdash-tab-head').show();
			} else {
				$(widget).find('.vapdash-tab-head').hide();
			}

			// in case the active pane was disabled, we should display the first one available
			if (activeDisabled && firstEnabled) {
				$(firstEnabled).trigger('click');
			}
		}

		/**
		 * Register callback to be executed after
		 * completing the update request.
		 *
		 * @param 	mixed 	widget  The widget selector.
		 * @param 	string 	data    The JSON response.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>] = (widget, data, config) => {
			var id = $(widget).attr('id');

			$(widget).find('.vapdash-tab-pane').each(function() {
				// get pane id
				var pane = $(this).data('pane');

				if (data[pane] !== undefined) {
					// fill body with returned HTML
					$(this).html(data[pane]);
				} else {
					// set empty string
					$(this).html('');
				}
			});

			$(widget).find('.hasTooltip').tooltip();
		}

		$(function() {
			// get widget element
			const widget = $('#widget-<?php echo $widget->getID(); ?>');

			// register click event for tab buttons
			$(widget).find('.vapdash-tab-head a').on('click', function() {
				// get button pane
				var pane = $(this).data('pane');

				// deactivate all buttons
				$(widget).find('.vapdash-tab-head a').removeClass('active');
				// active clicked button
				$(this).addClass('active');

				// hide all panes
				$(widget).find('.vapdash-tab-pane').hide();
				// show selected pane
				$(widget).find('.vapdash-tab-pane[data-pane="' + pane + '"]').show();

				// register selected button in cookie
				document.cookie = 'vap.widget.<?php echo $widget->getName(); ?>.active.<?php echo $widget->getID(); ?>=' + pane + '; path=/';
			});
		});
	})(jQuery);

</script>
