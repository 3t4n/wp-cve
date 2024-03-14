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

$params = $this->params;

$vik = VAPApplication::getInstance();

$args   = VikAppointments::getPdfParams();
$constr = VikAppointments::getPdfConstraints();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigShopInvoice". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopInvoice');

?>

<!-- DETAILS -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPINVOICEDETAILS'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- AUTO GENERATE INVOICE - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['invoiceorders'] == 1);
		$no  = $vik->initRadioElement('', '', $params['invoiceorders'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG95'),
			'content' => JText::translate('VAPMANAGECONFIG95_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG95') . $help);
		echo $vik->radioYesNo('invoiceorders', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- INVOICE IDENTIFIER - Number/Text -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGEINVOICE1'),
			'content' => JText::translate('VAPMANAGEINVOICE1_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGEINVOICE1') . $help); ?>
			<div class="inline-fields" id="invoice-number-field">
				<input type="number" name="attr_invoicenumber" value="<?php echo $args->number; ?>" min="1" max="99999999" style="text-align: right;">
				&nbsp;/&nbsp;
				<input type="text" name="attr_invoicesuffix" value="<?php echo $args->suffix; ?>" size="10">
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- DATE - Dropdown -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 1, 'VAPMANAGERESTRMODE1'),
			JHtml::fetch('select.option', 2, 'VAPINVOICEDATEOPT2'),
			JHtml::fetch('select.option', 3, 'VAPMANAGERESTRMODE2'),
		);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGEINVOICE2'),
			'content' => JText::translate('VAPMANAGEINVOICE2_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGEINVOICE2') . $help); ?>
			<select name="attr_datetype" class="medium-large">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $args->datetype, true); ?></td>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- TAXES - Select -->
		
		<?php
		$taxes = JHtml::fetch('vaphtml.admin.taxes', '');

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGEINVOICE3'),
			'content' => JText::translate('VAPMANAGEINVOICE3_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGEINVOICE3') . $help);
		?>
			<select name="deftax" id="vap-deftax-sel">
				<?php echo JHtml::fetch('select.options', $taxes, 'value', 'text', $params['deftax']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- USE TAX BREAKDOWN - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['usetaxbd'] == 1);
		$no  = $vik->initRadioElement('', '', $params['usetaxbd'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG119'),
			'content' => JText::translate('VAPMANAGECONFIG119_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG119') . $help);
		echo $vik->radioYesNo('usetaxbd', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- LEGAL INFORMATION - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGEINVOICE4'),
			'content' => JText::translate('VAPMANAGEINVOICE4_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGEINVOICE4') . $help); ?>
			<textarea name="attr_legalinfo" style="width: 60%; height: 120px;"><?php echo $args->legalinfo; ?></textarea>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SEND INVOICE VIA E-MAIL - Checkbox -->
		
		<?php echo $vik->openControl(JText::translate('')); ?>
			<input type="checkbox" name="attr_sendinvoice" value="1" id="vapsendinvoicebox" <?php echo ($args->sendinvoice ? 'checked="checked"' : ''); ?>/>
			<label for="vapsendinvoicebox" style="display: inline-block;"><?php echo JText::translate("VAPMANAGEINVOICE5"); ?></label>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopInvoice","key":"params","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Invoice > Details fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['params']))
		{
			echo $forms['params'];

			// unset details form to avoid displaying it twice
			unset($forms['params']);
		}
		?>

	</div>
	
</div>

<!-- PROPERTIES -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPINVOICEPROPERTIES'); ?></h3>
	</div>

	<div class="config-fieldset-body">
		
		<!-- PAGE ORIENTATION - Dropdown -->

		<?php
		$options = array(
			JHtml::fetch('select.option', VAPInvoiceConstraints::PAGE_ORIENTATION_PORTRAIT, 'VAPINVOICEPROPORIENTATIONOPT1'),
			JHtml::fetch('select.option', VAPInvoiceConstraints::PAGE_ORIENTATION_LANDSCAPE, 'VAPINVOICEPROPORIENTATIONOPT2'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGEINVOICEPROP1')); ?>
			<select name="prop_page_orientation" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $constr->pageOrientation, true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- PAGE FORMAT - Dropdown -->

		<?php
		$options = array(
			JHtml::fetch('select.option', VAPInvoiceConstraints::PAGE_FORMAT_A4, 'A4'),
			JHtml::fetch('select.option', VAPInvoiceConstraints::PAGE_FORMAT_A5, 'A5'),
			JHtml::fetch('select.option', VAPInvoiceConstraints::PAGE_FORMAT_A6, 'A6'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGEINVOICEPROP2')); ?>
			<select name="prop_page_format" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $constr->pageFormat); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- UNIT - Dropdown -->

		<?php
		$options = array(
			JHtml::fetch('select.option', VAPInvoiceConstraints::UNIT_POINT, 'VAPINVOICEPROPUNITOPT1'),
			JHtml::fetch('select.option', VAPInvoiceConstraints::UNIT_MILLIMETER, 'VAPINVOICEPROPUNITOPT2'),
			// JHtml::fetch('select.option', VAPInvoiceConstraints::UNIT_CENTIMETER, 'VAPINVOICEPROPUNITOPT3'),
			// JHtml::fetch('select.option', VAPInvoiceConstraints::UNIT_INCH, 'VAPINVOICEPROPUNITOPT4'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGEINVOICEPROP3')); ?>
			<select name="prop_unit" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $constr->unit, true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SCALE - Number -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGEINVOICEPROP4'),
			'content' => JText::translate('VAPMANAGEINVOICEPROP4_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGEINVOICEPROP4') . $help); ?>
			<div class="input-append">
				<input type="number" name="prop_scale" value="<?php echo max(array(10, round($constr->imageScaleRatio * 100))); ?>" min="10" step="1" style="text-align: right;">
				
				<span class="btn">%</span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopInvoice","key":"constraints","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Invoice > Properties fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['constraints']))
		{
			echo $forms['constraints'];

			// unset details form to avoid displaying it twice
			unset($forms['constraints']);
		}
		?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopInvoice","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Invoice tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}
?>

<script>
	jQuery(function($) {
		$('#vap-deftax-sel').select2({
			placeholder: '--',
			allowClear: true,
			width: 250,
		});
	});
</script>
