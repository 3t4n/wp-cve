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

<!-- PUBLISHING MODE - Select -->

<?php 
$options = array(
	JHtml::fetch('select.option', 1, 'VAPCOUPONPUBMODEOPT1'),
	JHtml::fetch('select.option', 2, 'VAPCOUPONPUBMODEOPT2'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON21'),
	'content' => JText::translate('VAPMANAGECOUPON21_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON21') . $help); ?>
	<select name="pubmode" id="vap-pubmode-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $coupon->pubmode, true); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- START DATE - Calendar -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGECOUPON7'));
echo $vik->calendar(VAPDateHelper::sql2date($coupon->dstart), 'dstart', 'dstart', null, array('showTime' => true));
echo $vik->closeControl();
?>

<!-- END DATE - Calendar -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGECOUPON8'));
echo $vik->calendar(VAPDateHelper::sql2date($coupon->dend), 'dend', 'dend', null, array('showTime' => true));
echo $vik->closeControl();
?>

<!-- LAST MINUTE - Radio Button -->

<?php
$yes = $vik->initRadioElement('', '', $coupon->lastminute > 0, 'onclick="displayLastMinuteAmount(1);"');
$no  = $vik->initRadioElement('', '', $coupon->lastminute == 0, 'onclick="displayLastMinuteAmount(0);"');

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON10'),
	'content' => JText::translate('VAPMANAGECOUPON10_DESC'),
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON10') . $help);
echo $vik->radioYesNo('islastminute', $yes, $no, false);
echo $vik->closeControl();
?>

<!-- LAST MINUTE AMOUNT - Number -->

<?php
$control = array();
$control['style'] = $coupon->lastminute == 0 ? 'display:none;' : '';

echo $vik->openControl(JText::translate('VAPMANAGECOUPON11'), 'vap-lastminute-child', $control); ?>
	<div class="input-append">
		<input type="number" name="lastminute" id="vap-lastminute-input" value="<?php echo $coupon->lastminute; ?>" size="40" min="0" max="9999" />

		<span class="btn"><?php echo JText::translate('VAPFORMATHOURS'); ?></span>
	</div>
<?php echo $vik->closeControl(); ?>

<script>

	jQuery(function($) {
		$('#vap-pubmode-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: '90%',
		});
	});

	function displayLastMinuteAmount(is) {
		if (is) {
			jQuery('#vap-lastminute-input').val(24);
			jQuery('.vap-lastminute-child').show();
		} else {
			jQuery('#vap-lastminute-input').val(0);
			jQuery('.vap-lastminute-child').hide();
		}
	}

</script>
