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
 * called "onDisplayViewConfigShopPackages". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopPackages');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">

		<!-- ENABLE PACKAGES - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['enablepackages'] == "1", 'onClick="packagesValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', $params['enablepackages'] == "0",  'onClick="packagesValueChanged(0);"');
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG109'),
			'content' => JText::translate('VAPMANAGECONFIG109_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG109') . $help);	
		echo $vik->radioYesNo('enablepackages', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- PACKAGES PER ROW - Dropdown -->
		
		<?php
		$control = array();
		$control['style'] = $params['enablepackages'] == 0 ? 'display:none;' : '';

		$options = array();

		for ($i = 1; $i <= 6; $i++)
		{
			$options[] = JHtml::fetch('select.option', $i, $i);
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG106'),
			'content' => JText::translate('VAPMANAGECONFIG106_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG106') . $help, 'vappackagesrow', $control); ?>
			<select name="packsperrow" class="short">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['packsperrow']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- MAX PACKAGES IN CART - Number -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 1, 'VAPMANAGECONFIG47'),
			JHtml::fetch('select.option', 2, 'VAPMANAGECONFIG97'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG107'), 'vappackagesrow', $control); ?>
			<div class="inline-fields">
				<select class="small-medium" id="vap-maxpackscart-sel" >
					<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['maxpackscart'] == -1 ? 1 : 2, true); ?>
				</select>
				
				<input type="number" name="maxpackscart" value="<?php echo $params['maxpackscart']; ?>" size="20" min="1" max="99999999" id="vapmaxpackscart" style="<?php echo ($params['maxpackscart'] == "-1" ? 'display: none;' : ''); ?>" /> 
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- ALLOW USER REGISTRATION - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['packsreguser'] == 1);
		$no  = $vik->initRadioElement('', '', $params['packsreguser'] == 0);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG108'), 'vappackagesrow', $control);
		echo $vik->radioYesNo('packsreguser', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- MANDATORY PACKAGES - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['packsmandatory'] == 1);
		$no  = $vik->initRadioElement('', '', $params['packsmandatory'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG121'),
			'content' => JText::translate('VAPMANAGECONFIG121_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG121') . $help, 'vappackagesrow', $control);
		echo $vik->radioYesNo('packsmandatory', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- PACKAGES EMAIL TEMPLATE -->
		
		<?php
		$templates = JHtml::fetch('vaphtml.admin.mailtemplates');

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG102'), 'vappackagesrow', $control); ?>
			<div class="inline-fields">
				<select name="packmailtmpl" class="medium-large" id="vap-packemailtmpl-sel">
					<?php echo JHtml::fetch('select.options', $templates, 'value', 'text', $params['packmailtmpl']); ?>
				</select>

				<div class="btn-group flex-auto">
					<button type="button" class="btn" onclick="vapOpenMailTemplateModal('packmailtmpl', null, true); return false;">
						<i class="fas fa-pen"></i>
					</button>

					<button type="button" class="btn" onclick="goToMailPreview('packmailtmpl', 'package');">
						<i class="fas fa-eye"></i>
					</button>
				</div>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopPackages","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Packages > Details fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopPackages","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Packages tab.
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
		$('#vap-maxpackscart-sel').on('change', function() {
			if ($(this).val() == 1) {
				$('#vapmaxpackscart').val(-1);
				$('#vapmaxpackscart').hide();
			} else {
				$('#vapmaxpackscart').val(1);
				$('#vapmaxpackscart').show();
			}
		});
	});

	function packagesValueChanged(is) {
		if (is) {
			jQuery('.vappackagesrow').show();
		} else {
			jQuery('.vappackagesrow').hide();
		}
	}

</script>
