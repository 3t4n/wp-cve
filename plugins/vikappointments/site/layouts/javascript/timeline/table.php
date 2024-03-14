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

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string  $url  The URL used to load the time table via AJAX.
 */

JText::script('VAPWAITLISTADDED0');

$loading_img = VAPASSETS_URI . 'css/images/loading.gif';

echo 
<<<JS
function loadEmployeeAvailTable(id) {
	UIAjax.do(
		'$url',
		{
			id_emp: id,
			id_ser: jQuery('#vapempblock' + id).data('service'),
			date: 	jQuery('#vapempblock' + id).attr('data-day'),
		},
		function(resp) {
			jQuery('#vapempblock' + id).find('.emp-search-box-right').html(resp.html);
		},
		function(err) {
			jQuery('#vapempblock' + id).find('.emp-search-box-right').html(
				'<div class="emp-search-error">' + (err.responseText || Joomla.JText._('VAPWAITLISTADDED0')) + '</div>'
			);
		}
	);
}

function showMoreTimesFromTable(id, btn) {
	// display hidden times
	jQuery('#avail-tbody' + id).find('.timetable-slot.hidden').removeClass('hidden').addClass('visible');
	// hide "show more" button
	jQuery(btn).closest('.avail-table-footer').hide();
}

function loadOtherTableTimes(id, day) {
	jQuery('#vapempblock' + id).find('.emp-search-box-right').html(
		'<div class="emp-search-loading"><img src="$loading_img" /></div>'
	);

	jQuery('#vapempblock' + id).attr('data-day', day);

	loadEmployeeAvailTable(id);
}
JS
;
