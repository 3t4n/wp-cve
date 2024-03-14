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

$status = $this->status;

$vik = VAPApplication::getInstance();

?>

<!-- APPOINTMENTS - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->appointments == 1);
$no  = $vik->initRadioElement('', '', $status->appointments == 0);

echo $vik->openControl(JText::translate('VAPMENUTITLEHEADER2'));
echo $vik->radioYesNo('appointments', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- PACKAGES - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->packages == 1);
$no  = $vik->initRadioElement('', '', $status->packages == 0);

echo $vik->openControl(JText::translate('VAPMENUPACKAGES'));
echo $vik->radioYesNo('packages', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- SUBSCRIPTIONS - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $status->subscriptions == 1);
$no  = $vik->initRadioElement('', '', $status->subscriptions == 0);

echo $vik->openControl(JText::translate('VAPMENUSUBSCRIPTIONS'));
echo $vik->radioYesNo('subscriptions', $yes, $no, false);
echo $vik->closeControl();
?>

<script>

	jQuery(function($) {

		// extend form validation by checking whether the user
		// selected at least one of the available groups
		validator.addCallback((form) => {
			// get all group fields
			const fields = $('input[name="appointments"]')
				.add($('input[name="packages"]'))
				.add($('input[name="subscriptions"]'));

			let checked = false;

			// look for a group selection
			fields.each(function() {
				if ($(this).is(':checkbox')) {
					checked = checked || $(this).is(':checked');
				} else {
					checked = checked || ($(this).val() == 1);
				}
			});

			if (!checked) {
				// no selected groups, mark as invalid
				form.setInvalid(fields);

				return false;
			}

			// at least one selected, mark as valid
			form.unsetInvalid(fields);

			return true;
		});

	});

</script>
