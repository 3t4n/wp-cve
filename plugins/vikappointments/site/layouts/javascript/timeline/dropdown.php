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

$formname = isset($displayData['formname']) ? $displayData['formname'] : 'confirmapp';
$autofill = isset($displayData['autofill']) ? (int) $displayData['autofill'] : 1;

echo 
<<<JS
function checkinSelectValueChanged(select) {

	var link 	 = -1;
	var checkin  = jQuery(select).val();
	var selected = 0;
	var count 	 = 1;
	var html 	 = '';

	if (checkin.length) {
		jQuery('#vap-checkin-sel option').each(function() {

			var checkout = jQuery(this).val();
			var ok = false;

			if (checkin == checkout) {
				// first checkout available found, register chain value (link)
				link = jQuery(this).data('link');
				ok = true;
			} else if (link == jQuery(this).data('link')) {
				// keep all next elements that belong to the same chain
				ok = true;
				count++;
			}

			if (ok) {
				html += '<option value="' + count + '">' + jQuery(this).data('checkout-date') + '</option>';

				if (!selected) {
					selected = count;
				}
			}

		});
	}

	if (!html || !{$autofill}) {
		html = '<option></option>' + html;
	}

	jQuery('#vap-checkout-sel').html(html);
	jQuery('#vap-checkout-sel').prop('disabled', checkin.length ? false : true);

	if (selected && {$autofill}) {
		jQuery('#vap-checkout-sel').select2('val', selected);
	} else {
		jQuery('#vap-checkout-sel').select2('val', '');
	}

	if (checkin) {
		var option = jQuery(select).find('option:selected');
		vapTimeClicked(option.data('hour'), option.data('min'), 0);

		if (!document.{$formname}.duration_factor) {
			jQuery('form[name="{$formname}"]').append('<input type="hidden" name="duration_factor" value="" />');
		}

		// use false to avoid triggering "checkout-changed" event
		checkoutSelectValueChanged(jQuery('#vap-checkout-sel'), false);
	}

}

function checkoutSelectValueChanged(select, trigger) {
	document.{$formname}.duration_factor.value = jQuery(select).val();

	if (trigger === undefined || trigger === true) {
		jQuery(document).trigger('checkout-changed');
	}
}
JS
;
