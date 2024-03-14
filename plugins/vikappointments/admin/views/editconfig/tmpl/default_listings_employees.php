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
 * called "onDisplayViewConfigListingsEmployees". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ListingsEmployees');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- EMPLOYEES LISTINGS ORDERING - Select -->
		
		<?php
		$emp_list_mode = json_decode($params['emplistmode'], true);

		$options = array(
			JHtml::fetch('select.option', 1, JText::translate('JYES')),
			JHtml::fetch('select.option', 0, JText::translate('JNO')),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG17')); ?>
			<div class="vap-config-empord-fieldslist">
				<?php
				foreach ($emp_list_mode as $i => $active)
				{
					?>
					<div class="vap-config-empord-field inline-fields" style="margin-bottom: 5px;">
						<i class="fas fa-ellipsis-v big hndl flex-auto"></i>
						&nbsp;
						<input type="text" readonly value="<?php echo $this->escape(JText::translate('VAPCONFIGEMPLISTMODE' . $i)); ?>" />
						&nbsp;
						<select name="emplistmode[<?php echo $i; ?>]" class="short">
							<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $active); ?>
						</select>
					</div>
					<?php
				}
				?>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- EMPLOYEES LIST LIMIT - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG80'),
			'content' => JText::translate('VAPMANAGECONFIG80_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG80') . $help); ?>
			<input type="number" name="emplistlim" value="<?php echo $params['emplistlim']; ?>" min="1" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- EMPLOYEES DESCRIPTION LENGTH - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG60'),
			'content' => JText::translate('VAPMANAGECONFIG60_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG60') . $help); ?>
			<div class="input-append">
				<input type="number" name="empdesclength" value="<?php echo $params['empdesclength']; ?>" min="32" />

				<span class="btn"><?php echo JText::translate('VAPCHARS'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- EMPLOYEES IMAGE LINK ACTION - Dropdown -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 1, 'VAPCONFIGLINKHREF1'),
			JHtml::fetch('select.option', 2, 'VAPCONFIGLINKHREF2'),
		);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG61'),
			'content' => JText::translate('VAPMANAGECONFIG61_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG61') . $help); ?>
			<select name="emplinkhref" class="medium-large">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['emplinkhref'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- ENABLE EMPLOYEES GROUPS FILTER - Radio Button -->
		
		<?php
		$elem_yes = $vik->initRadioElement('', '', $params['empgroupfilter'] == 1);
		$elem_no  = $vik->initRadioElement('', '', $params['empgroupfilter'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG88'),
			'content' => JText::translate('VAPMANAGECONFIG88_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG88') . $help);
		echo $vik->radioYesNo('empgroupfilter', $elem_yes, $elem_no);
		echo $vik->closeControl();
		?>

		<!-- ENABLE EMPLOYEES ORDERING FILTER - Radio Button -->
		
		<?php
		$elem_yes = $vik->initRadioElement('', '', $params['empordfilter'] == 1);
		$elem_no  = $vik->initRadioElement('', '', $params['empordfilter'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG90'),
			'content' => JText::translate('VAPMANAGECONFIG90_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG90') . $help);
		echo $vik->radioYesNo('empordfilter', $elem_yes, $elem_no);
		echo $vik->closeControl();
		?>

		<!-- ENABLE AJAX SEARCH - Dropdown -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 0, 'VAPCONFIGAJAXSEARCHOPT0'),
			JHtml::fetch('select.option', 2, 'VAPCONFIGAJAXSEARCHOPT2'),
			JHtml::fetch('select.option', 1, 'VAPCONFIGAJAXSEARCHOPT1'),
		);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG115'),
			'content' => JText::translate('VAPMANAGECONFIG115_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG115') . $help); ?>
			<select name="empajaxsearch" class="medium-large">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['empajaxsearch'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigListingsEmployees","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Listings > Employees > Details fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigListingsEmployees","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Listings > Employees tab.
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
		$('.vap-config-empord-fieldslist').sortable({
			revert: false,
			axis: 'y',
			handle: '.hndl',
			items: '.vap-config-empord-field',
			cursor: 'move',
		});
	});

</script>
