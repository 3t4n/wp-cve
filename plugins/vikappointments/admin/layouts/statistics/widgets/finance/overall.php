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
 */
extract($displayData);

?>

<style>

	.canvas-align-center .overall {
		text-align: center;
	}
	.canvas-align-center .overall .overall-earning {
		font-size: 40px;
		margin-bottom: 20px;
		color: #476799;
		font-weight: bold;
	}
	.canvas-align-center .overall .overall-count {
		font-size: 26px;
		color: #002243;
		font-weight: 500;
	}

</style>

<div class="canvas-align-center">
	
	<div class="overall">
		<div class="overall-earning"></div>
		<div class="overall-count"></div>
	</div>

</div>

<div class="widget-floating-box">

	<span class="badge badge-success pull-left summary" style="margin-right: 4px;"></span>
	<span class="badge badge-warning pull-left datefrom" style="margin-right: 4px;"></span>
	<span class="badge badge-warning pull-left dateto"></span>

</div>

<script>

	(function($) {
		'use strict';

		/**
		 * Register callback to be executed after
		 * completing the update request.
		 *
		 * @param 	mixed 	widget  The widget selector.
		 * @param 	mixed   data    The AJAX response.
		 * @param 	object  config  The widget configuration.
		 *
		 * @return 	void
		 */
		WIDGET_CALLBACKS[<?php echo $widget->getID(); ?>] = (widget, data, config) => {
			// show overall information
			$(widget).find('.canvas-align-center').show();

			// update total earning
			$(widget).find('.overall-earning').text(data.formattedTotal);
			$(widget).find('.overall-count').text(data.formattedCount);

			// update quick summary
			$(widget).find('.badge.summary').text(data.summary ? data.summary : '');

			// display dates only in case the summary is empty
			data.from = !data.summary && data.from ? data.from : '';
			data.to   = !data.summary && data.to   ? data.to   : '';

			if (!data.from && data.to) {
				// display LT meaning the date is a "ceil"
				data.to = '< ' + data.to;
			}

			if (data.from && !data.to) {
				// display GT meaning the date is a "floor"
				data.from = '> ' + data.from;
			}

			// update from date
			$(widget).find('.badge.datefrom').text(data.from);
			// update to date (hide in case it is equals to the from date)
			$(widget).find('.badge.dateto').text(data.to != data.from ? data.to : '');
		}
	})(jQuery);

</script>
