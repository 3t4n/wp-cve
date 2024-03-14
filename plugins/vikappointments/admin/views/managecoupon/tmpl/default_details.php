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

$currency = VAPFactory::getCurrency();

?>

<!-- COUPON CODE - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECOUPON2') . '*'); ?>
	<input type="text" name="code" class="required" value="<?php echo $coupon->code; ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- TYPE CODE - Select -->

<?php 
$options = array(
	JHtml::fetch('select.option', 1, 'VAPCOUPONTYPEOPTION1'),
	JHtml::fetch('select.option', 2, 'VAPCOUPONTYPEOPTION2'),
);

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPMANAGECOUPON3'),
	'content' => JText::translate('VAPMANAGECOUPON3_DESC')
));

echo $vik->openControl(JText::translate('VAPMANAGECOUPON3') . $help); ?>
	<select name="type" id="vap-gift-select">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $coupon->type, true); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- PERCENT OR TOTAL - Select -->

<?php 
$options = array(
	JHtml::fetch('select.option', 1, JText::translate('VAPCOUPONPERCENTOTOPTION1')),
	JHtml::fetch('select.option', 2, $currency->getSymbol()),
);

echo $vik->openControl(JText::translate('VAPMANAGECOUPON4')); ?>
	<select name="percentot" id="vap-percentot-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $coupon->percentot); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- VALUE - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECOUPON5')); ?>
	<div class="input-prepend currency-field" id="value-currency">
		<span class="btn"><?php echo $coupon->percentot == 1 ? '%' : $currency->getSymbol(); ?></span>

		<input type="number" name="value" value="<?php echo $coupon->value; ?>" min="0" max="99999999" step="any" />
	</div>
<?php echo $vik->closeControl(); ?>

<!-- MINIMUM COST - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGECOUPON6')); ?>
	<div class="input-prepend currency-field">
		<span class="btn"><?php echo $currency->getSymbol(); ?></span>

		<input type="number" name="mincost" value="<?php echo $coupon->mincost; ?>" min="0" max="99999999" step="any" />
	</div>
<?php echo $vik->closeControl(); ?>

<!-- GROUP - Select -->

<?php
$options = array();
$options[] = JHtml::fetch('select.option', '', '');
$options[] = JHtml::fetch('select.option', 0, JText::translate('VAPFILTERCREATENEW'));

$options = array_merge($options, JHtml::fetch('vaphtml.admin.coupongroups'));

echo $vik->openControl(JText::translate('VAPMANAGESERVICE10')); ?>
	<select name="id_group" id="vap-group-sel">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $coupon->id_group ? $coupon->id_group : ''); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- CREATE NEW GROUP - Text -->

<?php echo $vik->openControl('', 'create-group-control', array('style' => 'display:none;')); ?>
	<input type="text" name="group_name" placeholder="<?php echo $this->escape(JText::translate('VAPMANAGEGROUP2')); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<script>

	jQuery(function($) {
		$('#vap-group-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 300,
		});

		$('#vap-gift-select, #vap-percentot-sel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150,
		});

		$('#vap-percentot-sel').on('change', function() {
			var text = $(this).find('option:selected').text();

			$('#value-currency').find('button').text(text);
		});

		$('#vap-gift-select').on('change', function() {
			if ($(this).val() == 2) {
				$('.vap-gift-child').show();
				$('#vap-maxq-field').prop('readonly', false);
			} else {
				$('.vap-gift-child').hide();
				$('#vap-maxq-field').prop('readonly', true);
			}
		});

		$('#vap-group-sel').on('change', function() {
			var group = $('input[name="group_name"]');

			if (parseInt($(this).val()) === 0) {
				$('.create-group-control').show();
				group.focus();
				validator.registerFields(group);
			} else {
				$('.create-group-control').hide();
				validator.unregisterFields(group);
				group.val('');
			}
		});
	});

</script>
