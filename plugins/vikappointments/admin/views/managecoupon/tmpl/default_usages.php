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

$coupon = $this->coupon;

$vik = VAPApplication::getInstance();

?>

<!-- USED QUANTITY - Number -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON14'),
	'content' => JText::translate('VAPMANAGECOUPON14_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON14') . $help); ?>
	<input type="number" name="used_quantity" value="<?php echo $coupon->used_quantity; ?>" size="40" min="1" max="99999999" />
<?php echo $vik->closeControl(); ?>

<!-- MAX QUANTITY - Number -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON13'),
	'content' => JText::translate('VAPMANAGECOUPON13_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON13') . $help); ?>
	<input type="number" name="max_quantity" value="<?php echo $coupon->max_quantity; ?>" size="40" min="1" max="99999999" id="vap-maxq-field" <?php echo ($coupon->type == 1 ? 'readonly' : ''); ?> />
<?php echo $vik->closeControl(); ?>

<!-- MAX PER USER - Number -->

<?php
$options = array(
	JHtml::fetch('select.option', 1, 'VAPMANAGECONFIG47'),
	JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG97'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON22'),
	'content' => JText::translate('VAPMANAGECOUPON22_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON22') . $help); ?>
	<div class="inline-fields">
		<select id="vap-maxperuser-sel" class="flex-basis-70">
			<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $coupon->maxperuser == 0 ? 1 : 2, true); ?>
		</select>

		<input type="number" name="maxperuser" value="<?php echo $coupon->maxperuser; ?>" class="flex-basis-30"  min="1" max="999999" style="<?php echo $coupon->maxperuser ? '' : 'display:none;'; ?>" />
	</div>
<?php echo $vik->closeControl(); ?>

<!-- REMOVE GIFT - Radio Button -->

<?php
$yes = $vik->initRadioElement('', '', $coupon->remove_gift == 1);
$no  = $vik->initRadioElement('', '', $coupon->remove_gift == 0);

$control = array();
$control['style'] = $coupon->type == 1 ? 'display: none;' : '';

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON15'),
	'content' => JText::translate('VAPMANAGECOUPON15_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON15') . $help, 'vap-gift-child', $control);
echo $vik->radioYesNo('remove_gift', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- APPLICABLE - Select -->

<?php
$options = array(
	JHtml::fetch('select.option', '', ''),
	JHtml::fetch('select.option', 'appointments',  'VAPMENUTITLEHEADER2'),
	JHtml::fetch('select.option', 'packages',      'VAPMENUPACKAGES'),
	JHtml::fetch('select.option', 'subscriptions', 'VAPMENUSUBSCRIPTIONS'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON23'),
	'content' => JText::translate('VAPMANAGECOUPON23_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON23') . $help); ?>
	<select name="applicable" id="vap-applicable-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $coupon->applicable, true); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<?php
JText::script('JGLOBAL_SELECT_AN_OPTION');
?>

<script>

	jQuery(function($) {
		$('#vap-applicable-sel').select2({
			minimumResultsForSearch: -1,
			placeholder: Joomla.JText._('JGLOBAL_SELECT_AN_OPTION'),
			allowClear: true,
			width: '90%',
		});

		$('#vap-maxperuser-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 'resolve',
		});

		$('#vap-maxperuser-sel').on('change', function() {
			if ($(this).val() == 1) {
				$('input[name="maxperuser"]').hide().val(0);
			} else {
				$('input[name="maxperuser"]').val(1).show();
			}
		});
	});

</script>
