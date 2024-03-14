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
 * VikAppointments HTML site scripts helper.
 *
 * @since 1.7
 */
abstract class VAPHtmlSitescripts
{
	/**
	 * Declares the statement that will be used to initialize a standard datepicker.
	 *
	 * @param 	string 	$selector  The datepicker selector.
	 * @param 	array 	$options   An array of options to be used while creating the
	 *                             jQuery datepicker.
	 *
	 * @return 	void
	 */
	public static function calendar($selector, array $options = array())
	{
		$config = VAPFactory::getConfig();

		// get date format
		$date_format = $config->get('dateformat');

		// attach regional jQuery datepicker first
		VikAppointments::load_datepicker_regional();

		// stringify options for JS usage
		$json = json_encode($options);

		$js = 
<<<JS
jQuery(function($) {
	// check if we have a mobile or a tablet
	if (window.matchMedia && window.matchMedia("only screen and (max-width: 760px)").matches) {
		// prevent keyboard easing, by blurring the field every time it gets focused
		$('{$selector}')
			.attr('autocomplete', 'off')
			.attr('onfocus', 'this.blur()');
	}

	var format    = "{$date_format}";
	var separator = format[1];

	// strip any separators from date format
	format = format.replace(/[^a-z]/gi, '');

	switch (format) {
		case 'Ymd':
			format = 'yy' + separator + 'mm' + separator + 'dd';
			break;

		case 'mdY':
			format = 'mm' + separator + 'dd' + separator + 'yy';
			break;

		default:
			format = 'dd' + separator + 'mm' + separator + 'yy';
	}

	let options = {$json};
	// set date format
	options.dateFormat = format;

	$('{$selector}').datepicker(options);
});
JS
		;

		// add js to document head
		JFactory::getDocument()->addScriptDeclaration($js);
	}

	/**
	 * Declares a list of functions that can be used to manage the items
	 * inside the cart, useful for the appointment confirmation page and
	 * the cart module.
	 *
	 * @return 	void
	 */
	public static function cart()
	{
		static $loaded = 0;

		if ($loaded)
		{
			// do not load again
			return;
		}

		$loaded = 1;

		// use current Item ID for correct routing
		$itemid = JFactory::getApplication()->input->getUint('Itemid');

		$vik = VAPApplication::getInstance();

		// create AJAX URL for remove item end-point
		$remove_item_url = $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.removeitem' . ($itemid ? '&Itemid=' . $itemid : ''));

		// create AJAX URL for add option end-point
		$add_option_url = $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.addoption' . ($itemid ? '&Itemid=' . $itemid : ''));

		// create AJAX URL for add option end-point
		$remove_option_url = $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.removeoption' . ($itemid ? '&Itemid=' . $itemid : ''));

		// register generic error message
		JText::script('VAPWAITLISTADDED0');

		$js = 
<<<JS
function vapRemoveCartItemRequest(id_service, id_employee, checkin) {
	// prepare request argument
	const args = {
		id_ser:  id_service,
		id_emp:  id_employee,
		checkin: checkin,
	};

	return new Promise((resolve, reject) => {
		UIAjax.do(
			'{$remove_item_url}',
			args,
			(resp) => {
				// resolve promise
				resolve(resp);

				// inject received parameters within the event to dispatch
				const event = jQuery.Event('cart.removeitem');
				// merge response with request arguments
				event.params = Object.assign(resp, args);

				// trigger event to notify any subscriber
				jQuery(window).trigger(event);
			},
			(err) => {
				// reject promise
				reject(err.responseText || Joomla.JText._('VAPWAITLISTADDED0'));
			}
		);
	});
}

function vapAddCartOptionRequest(id_option, id_service, id_employee, checkin, units) {
	// prepare request argument
	const args = {
		id_opt:  id_option,
		id_ser:  id_service,
		id_emp:  id_employee,
		checkin: checkin,
		units:   typeof units === 'undefined' ? 1 : units,
	};
	
	return new Promise((resolve, reject) => {
		UIAjax.do(
			'{$add_option_url}',
			args,
			(resp) => {
				// resolve promise
				resolve(resp);

				// inject received parameters within the event to dispatch
				const event = jQuery.Event('cart.addoption');
				// merge response with request arguments
				event.params = Object.assign(resp, args);

				// trigger event to notify any subscriber
				jQuery(window).trigger(event);
			},
			(err) => {
				// reject promise
				reject(err.responseText || Joomla.JText._('VAPWAITLISTADDED0'));
			}
		);
	});
}

function vapRemoveCartOptionRequest(id_option, id_service, id_employee, checkin, units) {
	// prepare request argument
	const args = {
		id_opt:  id_option,
		id_ser:  id_service,
		id_emp:  id_employee,
		checkin: checkin,
		units:   typeof units === 'undefined' ? 1 : units,
	};

	return new Promise((resolve, reject) => {
		UIAjax.do(
			'{$remove_option_url}',
			args,
			(resp) => {
				// resolve promise
				resolve(resp);

				// inject received parameters within the event to dispatch
				const event = jQuery.Event('cart.removeoption');
				// merge response with request arguments
				event.params = Object.assign(resp, args);

				// trigger event to notify any subscriber
				jQuery(window).trigger(event);
			},
			(err) => {
				// reject promise
				reject(err.responseText || Joomla.JText._('VAPWAITLISTADDED0'));
			}
		);
	});
}
JS
		;

		// add js to document head
		JFactory::getDocument()->addScriptDeclaration($js);
	}

	/**
	 * Animates the document in case the specified selector
	 * is currently not visible within the screen.
	 *
	 * @param 	mixed 	 $selector  The page will be animated as long as the
	 * 							    specified element is not on top of the page.
	 * @param 	integer  $maring    An optional margin to use as threshold.
	 *
	 * @return 	void
	 */
	public static function animate($selector = null, $margin = 20)
	{
		/**
		 * Check whether the pages animation has been disabled.
		 * It is possible safely disable the animation of the
		 * pages by inserting a new record within the configuration
		 * database table of VikAppointments.
		 *
		 * INSERT INTO `#__vikappointments_config` (`param`, `setting`)
		 * VALUES ('animatepages', 0);
		 */
		$disabled = VAPFactory::getConfig()->getBool('animatepages', true);

		if (!$disabled)
		{
			// pages animation has been disabled globally
			return;
		}

		if (!$selector)
		{
			// use default "main" container, which seems to be the
			// default identifier for both Joomla and WP platforms
			$selector = '#main';
		}

		// use a valid margin
		$margin = (int) $margin;

		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(function($) {
	// flag used to check whether the page
	// has been scrolled before executing the
	// animation
	var hasScrolled = false;

	var scrollDetector = function() {
		hasScrolled = true;

		// self turn off scroll detector
		$(window).off('scroll', scrollDetector);
	}

	$(window).on('scroll', scrollDetector);

	onDocumentReady().then(function() {
		// get element
		var elem = $('$selector');

		if (elem.length == 0 && '$selector' == '#main') {
			// the template is not using the default notation,
			// try to observe the beginning of VikAppointments
			elem = $('.vikappointments-start-body');
		}

		// make sure the page hasn't been already scrolled in order to 
		// avoid debounces, then make sure the element exists and it is not visible
		if (!hasScrolled && elem.length && isBoxOutOfMonitor(elem, $margin)) {
			$('html, body').animate({
				scrollTop: elem.offset().top - $margin,
			});
		}
	});
});
JS
		);
	}

	/**
	 * Includes the script to trigger the browser print function
	 * after completing the page loading.
	 *
	 * @param 	integer  $delay  The number of milliseconds to wait.
	 *
	 * @return 	void
	 */
	public static function winprint($delay = null)
	{
		// at least wait 1 ms
		$delay = max(array(1, (int) $delay));

		// include script for document printing
		JFactory::getDocument()->addScriptDeclaration(
<<<JS
(function($) {
	'use strict';

	$(function() {
		setTimeout(() => {
			window.print();
		}, $delay);
	});
})(jQuery);
JS
		);
	}

	/**
	 * Auto set CSRF token to ajaxSetup so all jQuery ajax call will contains CSRF token.
	 *
	 * @return  void
	 *
	 * @see 	JHtmlJquery::csrf()
	 */
	public static function ajaxcsrf($name = 'csrf.token')
	{
		static $loaded = 0;

		if ($loaded)
		{
			// do not load again
			return;
		}

		$loaded = 1;

		try
		{
			// rely on system helper
			JHtml::fetch('jquery.token');
		}
		catch (Exception $e)
		{
			// Helper not declared, installed CMS too old (lower than J3.8).
			// Fallback to our internal helper.
			$csrf = addslashes(JSession::getFormToken());

			JFactory::getDocument()->addScriptDeclaration(
<<<JS
;(function($) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': '{$csrf}',
		},
	});
})(jQuery);
JS
			);
		}
	}
}
