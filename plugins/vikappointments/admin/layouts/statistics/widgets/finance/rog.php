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

	.canvas-align-center .rog {
		text-align: center;
	}
	.canvas-align-center .rog .rog-earning {
		font-size: 40px;
		margin-bottom: 20px;
		color: #476799;
		font-weight: bold;
	}
	.canvas-align-center .rog .rog-percent {
		font-size: 26px;
		color: #002243;
		font-weight: bold;
	}
	.canvas-align-center .rog .rog-percent > .down {
		color: #ec4d56;
	}
	.canvas-align-center .rog .rog-percent > .up {
		color: #29a449;
	}
	.canvas-align-center .rog .rog-percent i {
		margin-left: 5px;
	}

</style>

<div class="canvas-align-center">
	
	<div class="rog">
		<div class="rog-earning"></div>
		<div class="rog-percent"></div>
	</div>

</div>

<div class="no-results" style="display:none;">
	<?php echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS')); ?>
</div>

<div class="widget-floating-box">

	<span class="badge badge-info pull-left month1" style="margin-right:4px;"></span>
	<span class="badge badge-important pull-left month2"></span>

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
			if (data.nodata) {
				// hide rog information
				$(widget).find('.canvas-align-center').hide();
				// show "no results" box
				$(widget).find('.no-results').show();
			} else {
				// hide "no results" box
				$(widget).find('.no-results').hide();
				// show rog information
				$(widget).find('.canvas-align-center').show();

				// fetch rog
				var rog = parseFloat(data.rogPercent);
				var rogIcon = '';
				var sfx = 'equals';

				if (rog > 0) {
					rogIcon = '<i class="fas fa-arrow-up"></i>';
					sfx = 'up';
				} else if (rog < 0) {
					rogIcon = '<i class="fas fa-arrow-down"></i>';
					sfx = 'down';
				}

				// strip decimals in case the rog is higher than 10 or lower than -10
				if (Math.abs(rog) > 10) {
					rog = Math.round(rog);
				}

				var rogHtml = '<span class="' + sfx + '">' + rog + '%' + rogIcon + '</span>';

				// update total earning
				$(widget).find('.rog-earning').text(Currency.getInstance().format(data[data.month1].total));
				$(widget).find('.rog-percent').html(rogHtml);
			}

			// update badges
			$(widget).find('.badge.month1').text(data[data.month1].date);
			$(widget).find('.badge.month2').text(data[data.month2].date);
		}
	})(jQuery);

</script>
