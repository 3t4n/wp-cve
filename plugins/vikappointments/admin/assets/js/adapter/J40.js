(function($, w) {
	'use strict';

	/**
	 * Wrap procedures within a registered function, in order
	 * to execute them only in certain cases.
	 *
	 * @return 	void
	 */
	w['__vikappointments_j40_adapter'] = (selector) => {
		if (!selector) {
			// use default selector when not specified
			selector = 'body.com_vikappointments #content, body.com_vikappointments.component';
		}

		/**
		 * Make text, email and phone fields bigger.
		 */
		$(selector)
			.find('input,textarea')
				.each(function() {
					if ($(this).is('input')) {
						// get input type
						switch ($(this).attr('type')) {
							case 'checkbox':
							case 'hidden':
							// ignore field
								return true;
						}
					}

					$(this).addClass('form-control');
				});

		/**
		 * Adjust button groups to new Bootstrap structure.
		 */
		$(selector)
			.find('.input-append,.input-prepend')
			.removeClass('input-append')
				.removeClass('input-prepend')
				.each(function() {
					$($(this).children()).wrapAll('<div class="input-group"></div>');
				});

		/*
		$(selector)
			.find('.input-append,.input-prepend')
				.removeClass('input-append')
				.removeClass('input-prepend')
				.addClass('input-group')
					.each(function() {
						// find first input inside button group
						var input = $(this).find('input[type!="hidden"]');

						// get all elements before the input
						var prevElems = input.prevAll().not('[type="hidden"]');

						// check if we have at least an element
						if (prevElems.length) {
							// create append group and insert it before the input
							var group = $('<span class="input-group-prepend"></span>').insertBefore(input);

							// detach all elements and move them into the created group
							prevElems.detach().appendTo(group);

							// add "primary" style to button
							var btn = prevElems.filter('.btn').addClass('btn-primary');

							// check if we have more than a button
							if (btn.length > 1) {
								// add "secondary" style to odd buttons
								btn.filter(':odd').removeClass('btn-primary').addClass('btn-secondary');
							}
						}

						// get all elements next to the input
						var nextElems = input.nextAll().not('[type="hidden"]');

						// check if we have at least an element
						if (nextElems.length) {
							// create append group and insert it after the input
							var group = $('<span class="input-group-append"></span>').insertAfter(input);

							// detach all elements and move them into the created group
							nextElems.detach().appendTo(group);

							// add "primary" style to button
							var btn = nextElems.filter('.btn').addClass('btn-primary');

							// check if we have more than a button
							if (btn.length > 1) {
								// add "secondary" style to even buttons
								btn.filter(':even').removeClass('btn-primary').addClass('btn-secondary');
							}
						}
					});
		*/

		/**
		 * Adjust form fieldsets to new Bootstrap structure.
		 */
		$(selector)
			.find('.row-fluid')
				.removeClass('row-fluid')
				.addClass('row')
					.find('div[class^="span"]')
						.each(function() {
							// fetch span size
							var size = $(this).attr('class').match(/\bspan(\d+)\b/);

							if (size) {
								// remove previous class and insert the new one
								$(this).removeClass(size[0]).addClass('col-lg-' + size[1]);
							}
						});

		/**
		 * Replace class of small buttons with the correct one.
		 */
		$(selector)
		 	.find('.btn-mini')
				.filter('button,a')
					.each(function() {
						$(this).removeClass('btn-mini');
						$(this).addClass('btn-sm btn-secondary');
					});

		/**
		 * Add classes needed to properly display the buttons according
		 * to the classes used by Joomla 4.
		 */
		$(selector)
			.find('.btn')
				.filter('button,a,span')
				.not('[onclick^="vapToggleSearchToolsButton"]')
					.each(function() {
						var _class = $(this).attr('class');

						if ($(this).parent().hasClass('buttons-wrapper')) {
							// we are probably editing the buttons of the calendar
							return;
						}
						
						// make sure the button doesn't own any style
						if (!_class.match(/\bbtn-(?:success|danger|primary|secondary)\b/)) {
							// add default primary class to buttons
							$(this).addClass('btn-primary');
						}
					});

		/**
		 * Adjust style and text of search tools button.
		 *
		 * @note: DO NOT add btn-primary class to button, otherwise
		 * the first click will have no effect.
		 */
		$(selector)
			.find('button[onclick^="vapToggleSearchToolsButton"]')
				.each(function() {
					// get button HTML
					var html = $(this).html();
					// replace JSEARCH_TOOLS with localised string
					$(this).html(html.replace(/JSEARCH_TOOLS/, Joomla.JText._('JFILTER_OPTIONS')));
					// add special class
					$(this).addClass('js-stools-btn-filter');

					// look for a clear button
					var clear = $(this).closest('.btn-toolbar').find('button[onclick^="clearFilter"]');

					if (clear.length) {
						// save parent of clear button
						var dummy = clear.parent();

						// detach clear button from its position and move it inside the
						// same button group of the search tools
						clear.detach().appendTo($(this).parent());
						// add special class
						clear.addClass('js-stools-btn-clear');

						// remove wrapper in case there are no children in it
						if (dummy.children().length == 0) {
							dummy.remove();
						}
					}
				});

		/**
		 * Add style to filter bar.
		 */
		$(selector)
			.find('.btn-toolbar')
				.next('.btn-toolbar')
					.addClass('js-stools-container-filters clearfix js-stools-container-filters-visible');

		/**
		 * Adjust warnings to be a bit less obtrusive.
		 */
		$(selector)
			.find('.btn-toolbar')
				.nextAll('.alert-warning')
					.each(function() {
						// make sure the alert is not dismissible
						if ($(this).find('.close').length == 0) {
							// remove warning class
							$(this).removeClass('alert-warning');
							// add info class
							$(this).addClass('alert-info');

							// replace alert icon with the correct one
							$(this).find('span.fas')
								.removeClass('fa-exclamation-triangle')
								.addClass('fa-info-circle');
						}
					});

		$(selector)
			.find('.dashboard-wrapper')
				.removeClass('row');
	}

	/**
	 * Propagate Bootstrap modal events without the namespace usage.
	 */
	$(document).on('show.bs.modal hide.bs.modal shown.bs.modal hidden.bs.modal', '.joomla-modal', function(event) {
		if (event.namespace == 'bs.modal') {
			// propagate only in case of namespace set in order
			// to avoid a recursive loop
			$(this).trigger(event.type);
		}
	});

	/**
	 * Catch all the inspectors that loads the contents via AJAX.
	 * Then reformat the HTML with the j40 adapter callback.
	 */
	$(w).on('inspector.aftershow', function(event) {
		// check whether the inspector needs to load the contents via AJAX
		if (event.params && event.params.url) {
			// adapt the inspector body
			__vikappointments_j40_adapter(event.target);
		}
	});

	// only once the document is ready, do the following tasks
	$(function() {
		/**
		 * Auto-adjusts the HTML structure while adding a new option to
		 * a select custom field.
		 */
		$('#vap-customf-select-choose').on('select.option.add', function(event) {
			__vikappointments_j40_adapter(this);
		});

		/**
		 * Adds an hook to the request used to load the Payment fields.
		 * Adjust Payment form on request completion.
		 */
		$('.vikpayparamdiv').on('payment.load', function(event) {
			__vikappointments_j40_adapter(this);
		});

		/**
		 * Adds an hook to the request used to load the SMS API fields.
		 * Adjust SMS API form on request completion.
		 */
		$('#vap-smsapi-params-table').on('smsapi.load', function(event) {
			__vikappointments_j40_adapter(this);
		});

		/**
		 * Adds an hook to the request used to complete a wizard step.
		 * Adjust wizard step layout on request completion.
		 */
		$(window).on('wizard.postflight.after', (event) => {
			__vikappointments_j40_adapter(event.params.step);
		});

		/**
		 * Attach click listener to the button used to append an IP address
		 * within the list of an API user.
		 */
		$(document).on('click', '#add-ip', function(event) {
			__vikappointments_j40_adapter('#ips-container');
		});

		/**
		 * Attach click listener to the button used to append a ZIP interval.
		 */
		$(document).on('click', '#vapaddzipbutton', function(event) {
			__vikappointments_j40_adapter('#vapzipcodescont');
		});
		
		/**
		 * Add custom class to any select rendered with Chosen.
		 */
		$.fn.chosen = function() {
			/**
			 * Style only single select on Joomla 4.
			 */
			$(this).not('[multiple]').not('.time').addClass('form-select');

			/**
			 * Check whether the page supports at least a multiple select,
			 * then render them through select2 on Joomla 4.
			 */
			const multiSelect = $(this).filter('[multiple]');

			if (multiSelect.length) {
				multiSelect.select2({
					allowClear: true,
					width: 'resolve',
				});
			}
		};
	});

})(jQuery, window);
