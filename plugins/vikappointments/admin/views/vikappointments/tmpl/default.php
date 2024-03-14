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

JHtml::fetch('behavior.keepalive');
JHtml::fetch('formbehavior.chosen');
JHtml::fetch('vaphtml.assets.chartjs');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

?>

<script>

	/**
	 * A lookup of preflights to be used before refreshing
	 * the contents of the widgets.
	 *
	 * If needed, a widget can register its own callback
	 * to be executed before the AJAX request is started.
	 *
	 * The property name MUST BE equals to the ID of 
	 * the widget that is registering its callback.
	 *
	 * @var object
	 */
	var WIDGET_PREFLIGHTS = {};

	/**
	 * A lookup of callbacks to be used when refreshing
	 * the contents of the widgets.
	 *
	 * If needed, a widget can register its own callback
	 * to be executed once the AJAX request is completed.
	 *
	 * The property name MUST BE equals to the ID of 
	 * the widget that is registering its callback.
	 *
	 * @var object
	 */
	var WIDGET_CALLBACKS = {};

</script>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<?php
	// Display widgets dashboard through the apposite layout, since the same contents
	// are used also by the analytics page.
	echo JLayoutHelper::render('analytics.dashboard', ['dashboard' => $this->dashboard]);
	?>
	
	<input type="hidden" name="location" value="dashboard" />
	<input type="hidden" name="view" value="analytics" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
// customer modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-custinfo',
	array(
		'title'       => JText::translate('VAPMANAGECUSTOMER21'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
		'footer'	  => '<a href="index.php?option=com_vikappointments&task=customer.edit&cid[]=0" class="btn btn-success pull-right" id="custinfo-edit-btn">' . JText::translate('VAPEDIT') . '</a>',
	)
);

// order modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-orderinfo',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATIONTITLE1'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
		'footer'	  => '<a href="index.php?option=com_vikappointments&task=reservation.edit&cid[]=0" class="btn btn-success pull-right" id="orderinfo-edit-btn">' . JText::translate('VAPEDIT') . '</a>',
	)
);

// package modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-packinfo',
	array(
		'title'       => JText::translate('VAPMENUPACKORDERDETAILS'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
		'footer'	  => '<a href="index.php?option=com_vikappointments&task=packorder.edit&cid[]=0" class="btn btn-success pull-right" id="packinfo-edit-btn">' . JText::translate('VAPEDIT') . '</a>',
	)
);

// render inspector to manage widgets configuration
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'widget-config-inspector',
	array(
		'title'       => JText::translate('VAPMENUCONFIG'),
		'closeButton' => true,
		'keyboard'    => false,
		'footer'      => '<button type="button" class="btn btn-success" id="widget-save-config" data-role="save">' . JText::translate('JAPPLY') . '</button>',
		'width'       => 400,
	),
	// render inspector model through the apposite layout
	JLayoutHelper::render('analytics.config', ['dashboard' => $this->dashboard])
);

// Register scripts through the apposite layout, since the same functions
// are used also by the analytics page.
echo JLayoutHelper::render('analytics.script', ['dashboard' => $this->dashboard]);
?>

<script>

	(function($) {
		'use strict';

		/**
		 * A constant containing the seconds needed to
		 * launch the dashboard refresh.
		 *
		 * @var integer
		 */
		const DASH_REFRESH_TIME = <?php echo VAPFactory::getConfig()->getUint('refreshtime'); ?>;

		/**
		 * Counts the seconds passed since the last refresh.
		 *
		 * @var integer
		 */
		var DASH_REFRESH_COUNT = 0;

		/**
		 * The dashboard interval timer.
		 *
		 * @var mixed
		 */
		var DASH_THREAD = null;

		/**
		 * Starts the dashboard timer for next refresh.
		 *
		 * @return 	void
		 */
		const startDashboardListener = () => {
			if (DASH_THREAD) {
				// stop thread first if it is already running
				stopDashboardListener();
			}

			// creates the interval
			DASH_THREAD = setInterval(refreshDashboardListener, 1000);
		}

		/**
		 * Stops the dashboard timer.
		 *
		 * @return 	void
		 */
		const stopDashboardListener = () => {
			clearInterval(DASH_THREAD);

			DASH_THREAD = null;
		}

		/**
		 * Toggles the dashboard timer.
		 * If it was running, the timer is stopped, otherwise
		 * it is restarted from its last position.
		 *
		 * @return 	void
		 */
		const toggleDashboardListener = () => {
			if (DASH_THREAD) {
				// stop timer if running
				stopDashboardListener();
			} else {
				// restart timer
				startDashboardListener();
			}
		}

		/**
		 * Callback used by the refresh interval.
		 * In case the timeout expired, launches the
		 * request to refresh the widgets.
		 *
		 * @return 	void
		 */
		const refreshDashboardListener = () => {
			DASH_REFRESH_COUNT++;

			// check if the timer expired
			if (DASH_REFRESH_COUNT >= DASH_REFRESH_TIME) {
				// reset counter
				DASH_REFRESH_COUNT = 0;

				// stop timer
				stopDashboardListener();

				// launch dashboard refresh
				refreshDashboard();

				// restart dashboard timer
				startDashboardListener();
			}
		}

		/**
		 * Refreshes all the dashboard widgets.
		 *
		 * @return 	void
		 */
		const refreshDashboard = () => {
			<?php
			// iterate both groups
			foreach ($this->dashboard as $widgets)
			{
				// iterate position widgets
				foreach ($widgets as $widget)
				{
					?>
					$.vapWidgetDo('refresh', '<?php echo $widget->getID(); ?>');
					<?php
				}
			}
			?>
		}

		/**
		 * Plays a notification sound.
		 *
		 * @return 	void
		 */
		const playNotificationSound = () => {
			// fetch sound path
			var src = '<?php echo VikAppointments::getNotificationSound(); ?>';
			// Try to play the sound.
			// Make sure the same sound is not played
			// again for the next 5 seconds.
			SoundTry.playOnce(src, 5000);
		}

		// start the refresh timer
		startDashboardListener();

		/**
		 * Make some helper functions accessible from the outside, because
		 * the widgets might need a way to easily toggle the refresh timer.
		 *
		 * @param 	string  method  The method to perform.
		 *
		 * @return 	void
		 */
		$.vapDashboard = (method) => {
		 	if (typeof method !== 'string') {
				// invalid method
				throw 'Invalid method';
			}

			if (method.match(/^start$/)) {
				startDashboardListener();
			} else if (method.match(/^stop$/)) {
				stopDashboardListener();
			} else if (method.match(/^toggle$/)) {
				toggleDashboardListener();
			} else if (method.match(/^running$/)) {
				return DASH_THREAD ? true : false;
			} else if (method.match(/^refresh$/)) {
				refreshDashboard();
			} else if (method.match(/^play$/)) {
				playNotificationSound();
			}
		}

		$('#widget-config-inspector').on('inspector.show', () => {
			// stop dashboard timer as long as the widget configuration is open
			stopDashboardListener();
		});

		$('#widget-config-inspector').on('inspector.close', () => {
			// restart dashboard timer after closing the configuration of the widget
			startDashboardListener();
		});
	})(jQuery);

</script>
