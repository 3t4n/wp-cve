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

JHtml::fetch('vaphtml.assets.select2');

$vik = VAPApplication::getInstance();

JText::script('VAP_ANALYTICS_CUSTOMERS_PLACEHOLDER');

?>

<div class="btn-toolbar" style="height: 32px; font-size: 13px;">

	<input type="hidden" id="vap-users-select" value="" />

</div>

<script>

	(function($) {
		'use strict';

		/**
		 * A queue of requests.
		 *
		 * @var array
		 */
		let CUSTOMERS_UPDATE_QUEUE = [];

		/**
		 * Create private callback to fetch the users previously stored within
		 * the session storage of the browser.
		 *
		 * @return 	object  A lookup of users.
		 */
		const fetchSelectedUsers = () => {
			let json = null;

			if (typeof sessionStorage !== 'undefined') {
				// get previously saved users from session storage
				json = sessionStorage.getItem('analytics.customers.records');
			}

			if (json) {
				try {
					return JSON.parse(json);
				} catch (err) {
					// do nothing
				}
			}

			return {};
		}

		/**
		 * Create private callback to store the selected users within the
		 * session storage of the browser.
		 *
		 * @param 	mixed  users  Either an array or a comma-separated list.
		 *
		 * @return 	void
		 */
		const storeSelectedUsers = (users) => {
			if (typeof users === 'string') {
				users = users.split(',');
			}

			let map = {};

			users.forEach((u) => {
				if (USERS_LOOKUP.hasOwnProperty(u)) {
					map[u] = USERS_LOOKUP[u];
				}
			});

			if (typeof sessionStorage !== 'undefined') {
				// save selected users within the session storage
				sessionStorage.setItem('analytics.customers.records', JSON.stringify(map));
			}
		}

		/**
		 * Flag used to check whether the update thread is running or not.
		 *
		 * @var boolean
		 */
		let isUpdateQueueRunning = false;

		/**
		 * Create private callback to process the internal queue, needed to save
		 * the configuration of the widgets one by one to avoid session conflicts.
		 *
		 * @return 	void
		 */
		const processCustomersUpdateQueue = () => {
			if (CUSTOMERS_UPDATE_QUEUE.length == 0) {
				// nothing else to process
				isUpdateQueueRunning = false;
				return;
			}

			// register running flag
			isUpdateQueueRunning = true;

			// remove first element from queue 
			let id = CUSTOMERS_UPDATE_QUEUE.shift();

			$.vapWidgetDo('save', id).then(() => {
				// widget saved successfully
			}).catch((err) => {
				// an error occurred
				console.error(err);
			}).finally(() => {
				// recursively process the next element of the list
				// once the current one completed its request
				processCustomersUpdateQueue();
			});
		}

		// init with saved users
		const USERS_LOOKUP = fetchSelectedUsers();

		// render select
		$(function() {
			// update select values
			$('#vap-users-select').val(Object.keys(USERS_LOOKUP));

			$('#vap-users-select').select2({
				placeholder: Joomla.JText._('VAP_ANALYTICS_CUSTOMERS_PLACEHOLDER'),
				allowClear: true,
				width: '100%',
				minimumInputLength: 2,
				multiple: true,
				ajax: {
					url: '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=customer.users'); ?>',
					dataType: 'json',
					type: 'POST',
					quietMillis: 50,
					data: (term) => {
						return {
							term: term
						};
					},
					results: (data) => {
						return {
							results: $.map(data, (item) => {
								// always refresh lookup
								USERS_LOOKUP[item.id] = item.billing_name;

								return {
									text: item.text || item.billing_name,
									id:   item.id,
								};
							}),
						};
					},
				},
				initSelection: (element, callback) => {
					var data = [];
			        
			        // init selection with stored users
			        $.each(USERS_LOOKUP, (id, text) => {
			        	 data.push({id: id, text: text});
			        });

			        callback(data);
				},
				formatSelection: (data) => {
					if ($.isEmptyObject(data.billing_name)) {
						// display data returned from ajax parsing
						return data.text;
					}
					// display pre-selected value
					return data.billing_name;
				},
			});

			// save selected users on change and notify all the widgets
			$('#vap-users-select').on('change', function() {
				// get users from select
				const users = $(this).val();

				// store users
				storeSelectedUsers(users);

				// iterate all widget forms
				$('.inspector-fieldset').each(function() {
					let id     = $(this).data('id');
					let widget = $(this).data('widget');

					// make sure the input exists
					const input = $(this).find('input[name="' + widget + '_' + id + '_customers"]');

					if (input.length) {
						// update "customers" parameter with selected users
						input.val(users);

						// Refresh widget contents without saving the configuration, because
						// we noticed a strange behavior while refreshing the contents of several
						// widgets simultaneously. Maybe the session update faced a conflict with
						// other widgets that were saving the state at the same time.
						$.vapWidgetDo('refresh', id);

						// For this reason, we need to create a queue able to save the widgets one
						// by one in background.
						CUSTOMERS_UPDATE_QUEUE.push(id);
					}
				});

				// Process update query once all the widgets have been refreshed.
				// Do not call the queue in case the thread is already running.
				if (!isUpdateQueueRunning) {
					processCustomersUpdateQueue();
				}
			});

			// When the document is ready, look for any widgets with "customers" field
			// different than the globally selected ones.
			// This might occur while creating a new widget with the global customers 
			// field already filled in.
			let keys = Object.keys(USERS_LOOKUP).join(',');

			// iterate all widget forms
			$('.inspector-fieldset').each(function() {
				let id     = $(this).data('id');
				let widget = $(this).data('widget');

				// make sure the input exists
				const input = $(this).find('input[name="' + widget + '_' + id + '_customers"]');

				// check whether the value is different
				if (input.length && keys != input.val()) {
					// update "customers" parameter with selected users
					input.val(keys);

					// NOTE: we do not need to manually refresh the widget contents because this
					// block of code should be executed before loading data of all the widgets.
				}
			});
		});
	})(jQuery);

</script>