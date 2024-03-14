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
 * VikAppointments HTML admin scripts helper.
 *
 * @since 1.7
 */
abstract class VAPHtmlScripts
{
	/**
	 * Registers a script that will be used to handle the dismiss
	 * button of the alerts. If dismissed, they won't appear anymore
	 * until the expiration date.
	 *
	 * @param 	string 	$selector  The alert HTML selector.
	 *
	 * @return 	void
	 */
	public static function cookiealert($selector = '.alert')
	{
		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(document).ready(function() {

	jQuery('{$selector}').find('button[data-signature]').on('click', function() {
		var cookie = [];
		cookie.push('alert_dismiss_' + jQuery(this).data('signature') + '=1');

		if (jQuery(this).data('expdate')) {
			var date = new Date(jQuery(this).data('expdate'));
			cookie.push('expires=' + date.toUTCString());
		}

		cookie.push('path=/');

		document.cookie = cookie.join('; ');
	});

});
JS
		);
	}

	/**
	 * Returns the script used to store the selected view tab
	 * within the user state as a cookie.
	 *
	 * @param 	string   $tab   The tab group name.
	 * @param 	string   $key   The cookie name.
	 * @param 	integer  $days  The number of days for which the cookie should exist.
	 *
	 * @return 	string   The script (without <script> delimiters).
	 */
	public static function tabhandler($tab, $key, $days = null)
	{
		$days = (int) $days;

		$is_j4 = (int) VersionListener::isJoomla4x();

		return
<<<JS
(function($) {
	'use strict';

	$(function() {
		if (document.location.hash) {
			// Hash set, try to auto-access the given tab.
			// Trigger before registering the click event because
			// we don't want to cookie a tab selected via URL.
			if ({$is_j4}) {
				$('div[role="tablist"] button[role="tab"][aria-controls="' + document.location.hash + '"]').trigger('click');
			} else {
				$('a[href="' + document.location.hash + '"]').trigger('click');
			}
		}

		let selector = 'a[href^="#{$tab}_"]';

		if ({$is_j4}) {
			selector = 'div[role="tablist"] button[role="tab"][aria-controls^="{$tab}"]';
		}

		$(selector).on('click', function() {
			let href;

			if ({$is_j4}) {
				href = $(this).attr('aria-controls');
			} else {
				href = $(this).attr('href').substr(1);
			}

			if ({$days} > 0) {
				var date = new Date();
				date.setDate(date.getDate() + {$days});
				
				document.cookie = '{$key}=' + href + '; expires=' + date.toUTCString() + '; path=/';
			} else {
				// keep only for current session
				document.cookie = '{$key}=' + href + '; path=/';
			}
		});
	});
})(jQuery);
JS
		;
	}

	/**
	 * Renders the flag image within the options of the dropdowns
	 * matching the specified selector.
	 *
	 * @param 	mixed 	$options  An array of options. In case of a scalar
	 *                            value, it will be considered as the width.
	 *
	 * @return 	void
	 */
	public static function selectflags($selector = '.vap-flag-sel', $options = array())
	{
		// make sure select2 is loaded
		JHtml::fetch('vaphtml.assets.select2');

		$uri = VAPASSETS_URI . 'css/flags/';

		if (is_scalar($options))
		{
			// B.C. create an empty configuration with the given width
			$options = array('width' => $options);
		}

		// create default configuration
		$default = array(
			'width'                   => 200,
			'allowClear'              => false,
			'minimumResultsForSearch' => -1,
		);

		// merge given options with default ones
		$options = array_merge($default, $options);

		if ($options['allowClear'] && !isset($options['placeholder']))
		{
			// use default placeholder
			$options['placeholder'] = '--';
		}

		// prepare options for JS usage
		$options = json_encode($options);

		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(function($) {
	// create dropdown configuration
	const options = Object.assign({$options}, {
		formatResult: vapFormatFlags,
		formatSelection: vapFormatFlags,
		escapeMarkup: function(m) { return m; },
	});

	$('{$selector}').select2(options);

	function vapFormatFlags(opt) {
		if (!opt.id) {
			// optgroup
			return opt.text;
		}

		var tag = opt.id;

		if (opt.id.match(/^[a-z]{2,3}-[a-z]{2,2}$/i)) {
			// we have a langtag
			tag = tag.split('-').pop();
		}

		tag = tag.toLowerCase();

		switch (tag) {
			case 'el':
				tag = 'gr';
				break;
		}

		return '<img class="vap-opt-flag" src="{$uri}' + tag + '.png" />' + opt.text;
	}
});
JS
		);
	}

	/**
	 * Method to make the specified table listable.
	 *
	 * @param   string   $tableId  DOM id of the table.
	 * @param   string   $formId   DOM id of the form.
	 * @param   string   $sortDir  Sort direction.
	 * @param   string   $saveUrl  Save ordering url, ajax-load after an item is dropped.
	 * @param 	mixed 	 $filters  A list of filters to use when rearranging the records.
	 *
	 * @return  void
	 */
	public static function sortablelist($tableId, $formId = 'adminForm', $sortDir = 'asc', $saveOrderingUrl = null, $filters = array())
	{
		$vik = VAPApplication::getInstance();

		// load sortable list script
		$vik->addScript(VAPASSETS_URI . 'js/sortablelist.js');

		// create JSON data
		$data = array(
			'form'          => '#' . $formId,
			'direction'     => strtolower($sortDir),
			'saveUrl'       => $vik->ajaxUrl($saveOrderingUrl),
			'inputSelector' => 'input[name="order[]"]',
		);

		if ($filters)
		{
			// inject filters if specified
			$data['filters'] = $filters;
		}

		$data = json_encode($data);

		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(function($) {
	$('#{$tableId}').viksortablelist({$data});
});
JS
		);
	}

	/**
	 * Replaces the default behavior.modal function provided by Joomla,
	 * by supporting our Fancybox jQuery plugin.
	 *
	 * @return  void
	 */
	public static function modal($selector = 'a.modal', $params = array())
	{
		static $loaded = 0;

		if ($loaded)
		{
			return;
		}

		$loaded = 1;

		// load fancybox scripts
		JHtml::fetch('vaphtml.assets.fancybox');

		// add script to support modal
		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(function($) {
	$('{$selector}').on('click', function(e) {
		// get link HREF
		var href = $(this).attr('href');

		// prevent default link action
		e.preventDefault();

		// extract href from link
		var href = $(this).attr('href');

		// check if we have an image
		if (href.match(/\.(png|jpe?g|gif|bmp)$/i)) {
			// open fancybox containing image preview
			vapOpenModalImage(href);
		} else {
			// otherwise fallback to default browser opening
			vapOpenPopup(href);
		}

		return false;
	}).removeClass('modal').removeAttr('target');
});
JS
		);
	}
}
