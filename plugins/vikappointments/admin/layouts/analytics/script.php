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
 * @var  array  $dashboard  An array 
 */
extract($displayData);

$vik = VAPApplication::getInstance();

?>

<script>

	(function($) {
		'use strict';

		/**
		 * Prepare any chart to be responsive.
		 */
		Chart.defaults.global.responsive = true;

		/**
		 * Keep a reference of the widget that was clicked
		 * to update its configuration.
		 *
		 * @var integer
		 */
		var SELECTED_WIDGET = null;

		/**
		 * A pool containing the active AJAX requests for each
		 * widget, so that we can abort an existing request
		 * before launching a new one.
		 *
		 * @var object
		 */
		var CHARTS_REQUESTS_POOL = {};

		/**
		 * Opens the inspector to allow the management of the
		 * selected widget.
		 *
		 * @param 	integer  widget  The widget ID.
		 *
		 * @return 	void
		 */
		const openWidgetConfiguration = (widget) => {
			SELECTED_WIDGET = widget;

			// open inspector
			vapOpenInspector('widget-config-inspector');
		}

		/**
		 * Updates the configuration of the last edited widget.
		 *
		 * @param 	integer  id      The widget ID.
		 * @param 	object   config  The widget configuration.
		 * @param 	mixed    tmp     True to avoid saving the configuration.
		 *
		 * @return 	void
		 */
		const updateWidgetContents = (id, config, tmp) => {
			if (typeof config === 'undefined') {
				// get widget configuration if not specified
				config = getWidgetConfig(id);
			}

			// abort any existing request already made for this widget
			if (CHARTS_REQUESTS_POOL.hasOwnProperty(id)) {
				CHARTS_REQUESTS_POOL[id].abort();
			}

			// keep a reference to the widget
			var box = $('#widget-' + id);

			// get widget class
			var widget = box.data('widget');

			// prepare request data
			Object.assign(config, {
				id:       id,
				widget:   widget,
				location: 'dashboard',
			});

			if (tmp) {
				// skip settings save
				config.tmp = true;
			}

			// hide generic error message
			$(box).find('.widget-error-box').hide();
			// show widget body
			$(box).find('.widget-body').show();

			if (WIDGET_PREFLIGHTS.hasOwnProperty(id)) {
				// let the widget prepares the contents without
				// waiting for the request completion
				WIDGET_PREFLIGHTS[id](box, config);
			}

			// make request to load widget dataset
			var xhr = UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=analytics.loadwidgetdata'); ?>',
				config,
				(resp) => {
					// delete request from pool
					delete CHARTS_REQUESTS_POOL[id];

					// check if the widget registered its own update method
					if (WIDGET_CALLBACKS.hasOwnProperty(id)) {
						// let the widget callback finalizes the update
						WIDGET_CALLBACKS[id](box, resp, config);
					} else {
						if (typeof resp === 'string') {
							// replace widget body with returned string/HTML
							$(box).find('.widget-body').html(resp);
						}
					}
				},
				(error) => {
					// delete request from pool
					delete CHARTS_REQUESTS_POOL[id];

					// hide widget body
					$(box).find('.widget-body').hide();
					// show generic error message
					$(box).find('.widget-error-box').show();
				}
			);

			// update request pool
			CHARTS_REQUESTS_POOL[id] = xhr;
		}

		/**
		 * Saves the configuration of the last edited widget.
		 *
		 * @param 	integer  id      The widget ID.
		 * @param 	object   config  The widget configuration.
		 *
		 * @return 	Promise
		 */
		const saveWidgetContents = (id, config) => {
			if (typeof config === 'undefined') {
				// get widget configuration if not specified
				config = getWidgetConfig(id);
			}

			// keep a reference to the widget
			var box = $('#widget-' + id);

			// get widget class
			var widget = box.data('widget');

			// prepare request data
			Object.assign(config, {
				id:       id,
				widget:   widget,
				location: 'dashboard',
			});

			// create and return promise
			return new Promise((resolve, reject) => {
				// make request to load widget dataset
				UIAjax.do(
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=analytics.savewidgetdata'); ?>',
					config,
					(resp) => {
						// saved successfully
						resolve(resp);
					},
					(err) => {
						// an error occurred
						reject(err);
					}
				);
			});
		}

		/**
		 * Make some helper functions accessible from the outside, because
		 * the widgets might need a way to easily refresh their contents.
		 *
		 * @param 	string  method  The method to perform.
		 * @param 	mixed   data    The data that the method will have to use.
		 *
		 * @return 	mixed   The value returned by the method.
		 */
		$.vapWidgetDo = (method, data) => {
			if (typeof method !== 'string') {
				// invalid method
				throw 'Invalid method';
			}

			if (method.match(/^load$/i)) {
				// load widget configuration, assume data is an integer	
				updateWidgetContents(data);
			} else if (method.match(/^(refresh|reload)$/i)) {
				// Refresh widget configuration, assume data is an integer.
				// Since nothing has changed, do not save the settings.
				updateWidgetContents(data, undefined, true);
			} else if (method.match(/^(update|set)$/)) {
				// update configuration
				if (updateWidgetConfig(data.id, data.key, data.val)) {
					// refresh widget contents
					updateWidgetContents(data.id);
				}
			} else if (method.match(/^(save|commit)$/)) {
				// save configuration, assume data is an integer
				return saveWidgetContents(data);
			} else if (method.match(/^(get|config)$/)) {
				// load configuration, assume data is an integer
				return getWidgetConfig(data);
			}
		};

		/**
		 * Include an helper script to deal with modals.
		 *
		 * @param 	string  method  The method to perform.
		 * @param 	mixed   data    The data that the method will have to use.
		 *
		 * @return 	mixed   The selected element.
		 */
		$.fn.vapJModal = function(method, data) {
			if (typeof method !== 'string') {
				// invalid method
				throw 'Invalid method';
			}

			// immediately exit in case of no element found
			if ($(this).length == 0) {
				return this;
			}

			// extract modal ID
			let id = $(this).attr('id').replace(/^jmodal-/, '');

			if (method.match(/^(open|show|ease)$/i)) {
				let url = typeof data === 'object' ? data.url : data;
				let jqmodal = true;

				// include script to open modal
				<?php echo $vik->bootOpenModalJS(); ?>	
			} else if (method.match(/^(close|hide|fade|dismiss)$/i)) {
				// include script to close modal
				<?php echo $vik->bootDismissModalJS(); ?>	
			}

			return this;
		};

		$(function() {
			// open inspector when clicking the config button
			$('.widget-config-btn').on('click', function() {
				openWidgetConfiguration($(this).data('id'));
			});

			// fill the form before showing the inspector
			$('#widget-config-inspector').on('inspector.show', function() {
				setupWidgetConfig(SELECTED_WIDGET);
			});

			// refresh widget
			$('#widget-save-config').on('click', function() {
				// refresh the new contents displayed within the widget
				$.vapWidgetDo('load', SELECTED_WIDGET);

				// dismiss inspector
				$('#widget-config-inspector').inspector('dismiss');
			});

			// handle widget tables ordering
			$(document).on('click', 'table[data-widget-id] a[data-order-col]', function() {
				// extract ordering data
				let col = $(this).data('order-col');
				let dir = $(this).data('order-dir');

				// find widget ID
				let id = $(this).closest('table').data('widget-id');

				// manually update setting
				$.vapWidgetDo('update', {
					id:  id,
					key: 'ordering',
					val: [col, dir].join('.')
				});

				return false;
			});

			<?php
			// iterate dashboard widgets
			foreach ($dashboard as $widgets)
			{
				// iterate position widgets
				foreach ($widgets as $widget)
				{
					// Load widget contents once the page is ready.
					// Avoid to save the settings at the first widget load.
					?>
					updateWidgetContents('<?php echo $widget->getID(); ?>', undefined, true);
					<?php
				}
			}
			?>
		});
	})(jQuery);
	
</script>
