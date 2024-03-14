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

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigCurrencyDetails". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('CurrencyDetails');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- CURRENCY SYMBOL - Text -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG7')); ?>
			<input type="text" name="currencysymb" value="<?php echo $params['currencysymb']?>" size="10" onchange="formatSamplePrice();">
		<?php echo $vik->closeControl(); ?>
		
		
		<!-- CURRENCY NAME - Text -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG8'),
			'content' => JText::translate('VAPMANAGECONFIG8_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG8') . $help); ?>
			<input type="text" name="currencyname" value="<?php echo $params['currencyname']?>" size="10">
		<?php echo $vik->closeControl(); ?>
		
		
		<!-- CURRENCY SYMBOL POSITION - Select -->
		<?php
		$elements = array(
			JHtml::fetch('select.option',  1, JText::translate('VAPCONFIGSYMBPOSITION2')),
			JHtml::fetch('select.option', -1, JText::translate('VAPCONFIGSYMBPOSITION4')),
			JHtml::fetch('select.option',  2, JText::translate('VAPCONFIGSYMBPOSITION1')),
			JHtml::fetch('select.option', -2, JText::translate('VAPCONFIGSYMBPOSITION3')),
		);
		?>
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG25')); ?>
			<select name="currsymbpos" class="medium" onchange="formatSamplePrice();">
				<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', $params['currsymbpos']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		

		<!-- CURRENCY DECIMAL SEPARATOR - Text -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG103')); ?>
			<input type="text" name="currdecimalsep" value="<?php echo $params['currdecimalsep']?>" size="10" onchange="formatSamplePrice();">
		<?php echo $vik->closeControl(); ?>

		<!-- CURRENCY THOUSANDS SEPARATOR - Text -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG104')); ?>
			<input type="text" name="currthousandssep" value="<?php echo $params['currthousandssep']?>" size="10" onchange="formatSamplePrice();">
		<?php echo $vik->closeControl(); ?>

		<!-- CURRENCY NUMBER OF DECIMALS - Number -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG105')); ?>
			<input type="number" name="currdecimaldig" value="<?php echo $params['currdecimaldig']; ?>" min="0" max="9999" onchange="formatSamplePrice();"/>
		<?php echo $vik->closeControl(); ?>

		<!-- FINAL RESULT - LABEL -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG116')); ?>
			<span id="currency-sample-price">
				<?php echo VAPFactory::getCurrency()->format(1234.56); ?>
			</span>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigCurrencyDetails","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Currency > Currency > Details fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigCurrencyDetails","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Currency > Currency tab.
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
		// get currency instance
		const currency = Currency.getInstance();

		// create helper function to format the demo currency
		const formatSamplePrice = () => {
		
			// update currency configuration
			currency.decimals  = $('input[name="currdecimalsep"]').val();
			currency.digits    = parseInt($('input[name="currdecimaldig"]').val());
			currency.position  = parseInt($('select[name="currsymbpos"]').val());
			currency.symbol    = $('input[name="currencysymb"]').val();
			currency.thousands = $('input[name="currthousandssep"]').val();

			$('#currency-sample-price').html(currency.format(1234.56));
		};

		$('input[name="currencysymb"]')
			.add('input[name="currdecimalsep"]')
			.add('input[name="currthousandssep"]')
			.add('input[name="currdecimaldig"]')
			.add('select[name="currsymbpos"]')
			.on('change', formatSamplePrice);
	});

</script>
