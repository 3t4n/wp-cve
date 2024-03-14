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
 * called "onDisplayViewConfigListingsServices". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ListingsServices');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- SERVICES DESCRIPTION LENGTH - Number -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG60'),
			'content' => JText::translate('VAPMANAGECONFIG60_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG60') . $help); ?>
			<div class="input-append">
				<input type="number" name="serdesclength" value="<?php echo $params['serdesclength']; ?>" min="32" />

				<span class="btn"><?php echo JText::translate('VAPCHARS'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SERVICES IMAGE LINK ACTION - Dropdown -->

		<?php
		$options = array(
			JHtml::fetch('select.option', 1, 'VAPCONFIGLINKHREF3'),
			JHtml::fetch('select.option', 2, 'VAPCONFIGLINKHREF2'),
			JHtml::fetch('select.option', 3, 'VAPCONFIGLINKHREF4'),
		);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG61'),
			'content' => JText::translate('VAPMANAGECONFIG61_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG61') . $help); ?>
			<select name="serlinkhref" class="medium-large">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['serlinkhref'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigListingsServices","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Listings > Services > Details fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigListingsServices","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Listings > Services tab.
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
