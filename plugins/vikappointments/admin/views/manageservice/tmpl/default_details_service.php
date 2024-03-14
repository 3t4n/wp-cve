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

<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE2') . '*'); ?>
	<input type="text" name="name" class="input-xxlarge input-large-text required" value="<?php echo $service->name; ?>" size="64" />
<?php echo $vik->closeControl(); ?>

<!-- ALIAS - Text -->

<?php echo $vik->openControl(JText::translate('JFIELD_ALIAS_LABEL')); ?>
	<input type="text" name="alias" value="<?php echo $service->alias; ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- PRICE - Number -->

<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE5')); ?>
	<div class="input-prepend currency-field">
		<span class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></span>

		<input type="number" name="price" value="<?php echo $service->price; ?>" size="10" min="0" max="99999999" step="any" />
	</div>
<?php echo $vik->closeControl(); ?>

<!-- TAXES - Select -->

<?php
$control = array();
$control['style'] = $service->price > 0 ? '' : 'display:none;';

$taxes = JHtml::fetch('vaphtml.admin.taxes', $blank = '');

echo $vik->openControl(JText::translate('VAPTAXFIELDSET'), 'taxes-control', $control); ?>
	<select name="id_tax" id="vap-taxes-sel">
		<?php echo JHtml::fetch('select.options', $taxes, 'value', 'text', $service->id_tax); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- IMAGE - Media -->

<?php
echo $vik->openControl(JText::translate('VAPMANAGESERVICE9'));
echo JHtml::fetch('vaphtml.mediamanager.field', 'image', $service->image);
echo $vik->closeControl();
?>

<?php
JText::script('VAP_SELECT_USE_DEFAULT');
?>

<script>

	jQuery(function($) {
		$('#vap-taxes-sel').select2({
			placeholder: Joomla.JText._('VAP_SELECT_USE_DEFAULT'),
			allowClear: true,
			width: 300,
		});

		$('input[name="price"]').on('change', function() {
			const price = parseFloat(jQuery(this).val());

			if (!isNaN(price) && price > 0) {
				$('.taxes-control').show();
			} else {
				$('.taxes-control').hide();
			}
		});
	});

</script>
