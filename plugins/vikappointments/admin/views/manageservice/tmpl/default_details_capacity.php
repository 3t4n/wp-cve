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

$service = $this->service;

$vik = VAPApplication::getInstance();

?>

<!-- MAX CAPACITY - Number -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE21'),
	'content' => JText::translate('VAPMANAGESERVICE21_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE21') . $help); ?>
	<input type="number" name="max_capacity" value="<?php echo $service->max_capacity; ?>" size="10" min="1" max="999999" />
<?php echo $vik->closeControl(); ?>

<!-- MIN PEOPLE PER APP - Number -->

<?php
$capControl = array();
$capControl['style'] = $service->max_capacity <= 1 ? 'display: none;' : '';

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE22'),
	'content' => JText::translate('VAPMANAGESERVICE22_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE22') . $help, 'vaptrmaxcapchild', $capControl); ?>
	<input type="number" name="min_per_res" value="<?php echo $service->min_per_res; ?>" size="10" min="1" max="999999" />
<?php echo $vik->closeControl(); ?>

<!-- MAX PEOPLE PER APP - Number -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE22'),
	'content' => JText::translate('VAPMANAGESERVICE22_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE23') . $help, 'vaptrmaxcapchild', $capControl); ?>
	<input type="number" name="max_per_res" value="<?php echo $service->max_per_res; ?>" size="10" min="1" max="999999" />
<?php echo $vik->closeControl(); ?>

<!-- PRICE PER PEOPLE - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->priceperpeople == 1);
$no  = $vik->initRadioElement('', '', $service->priceperpeople == 0);

echo $vik->openControl(JText::translate('VAPMANAGESERVICE26'), 'vaptrmaxcapchild', $capControl);
echo $vik->radioYesNo('priceperpeople', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- APP PER SLOT - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->app_per_slot == 1);
$no  = $vik->initRadioElement('', '', $service->app_per_slot == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE34'),
	'content' => JText::translate('VAPMANAGESERVICE34_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE34') . $help, 'vaptrmaxcapchild', $capControl);
echo $vik->radioYesNo('app_per_slot', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- DISPLAY SEATS - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $service->display_seats == 1, 'onclick="timelineLayoutValueChanged(\'display_seats\');"');
$no  = $vik->initRadioElement('', '', $service->display_seats == 0);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGESERVICE36'),
	'content' => JText::translate('VAPMANAGESERVICE36_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE36'), 'vaptrmaxcapchild', $capControl);
echo $vik->radioYesNo('display_seats', $yes, $no, false);
echo $vik->closeControl();
?>

<script>

	jQuery(function($) {

		$('input[name="max_capacity"]').on('change', function() {
			let max = parseInt($(this).val());

			if (max <= 1) {
				jQuery('.vaptrmaxcapchild').hide();
			} else {
				jQuery('.vaptrmaxcapchild').show();
			}

			$('input[name="max_per_res"]').val(max).trigger('change');
		});

	});

</script>
