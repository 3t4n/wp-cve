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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$tax = $this->tax;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewTax". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- MAIN -->

		<div class="span8">

			<!-- TAX -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGECOUPONFIELDSET1'));
					echo $this->loadTemplate('tax');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewTax","key":"tax","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Details" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['tax']))
					{
						echo $forms['tax'];

						// unset details form to avoid displaying it twice
						unset($forms['tax']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- DESCRIPTION -->

			<div class="row-fluid">
				<div class="span12">
					<?php echo $vik->openFieldset(JText::translate('VAPMANAGEOPTION3')); ?>

					<textarea name="description" class="full-width" style="height: 160px; resize: vertical;"><?php echo $tax->description; ?></textarea>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewTax","key":"description","type":"field"} -->
					
					<?php
					/**
					 * Look for any additional fields to be pushed within
					 * the "Description" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['description']))
					{
						echo $forms['description'];

						// unset details form to avoid displaying it twice
						unset($forms['description']);
					}
						
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

		</div>

		<!-- SIDEBAR -->

		<div class="span4">

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewTax","type":"fieldset"} -->

			<?php
			/**
			 * Iterate remaining forms to be displayed within
			 * the sidebar (above "Rules" fieldset).
			 *
			 * @since 1.7
			 */
			foreach ($forms as $formName => $formHtml)
			{
				$title = JText::translate($formName);
				?>
				<div class="row-fluid">
					<div class="span12">
						<?php
						echo $vik->openFieldset($title);
						echo $formHtml;
						echo $vik->closeFieldset();
						?>
					</div>
				</div>
				<?php
			}
			?>

			<!-- RULES -->

			<div class="row-fluid">
				<div class="span12">
					<?php
					$help = $vik->createPopover(array(
						'title'   => JText::translate('VAPTAXRULEFIELDSET'),
						'content' => JText::translate('VAP_EDIT_SORT_DRAG_DROP'),
					));

					echo $vik->openFieldset(JText::translate('VAPTAXRULEFIELDSET') . $help);
					echo $this->loadTemplate('rules');
					echo $vik->closeFieldset();
					?>
				</div>
			</div>

		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="id" value="<?php echo $tax->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

// render inspector to manage tax rules
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'tax-rule-inspector',
	array(
		'title'       => JText::translate('VAPMANAGECUSTOMF12'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 400,
	),
	$this->loadTemplate('rules_modal')
);
?>

<script>

	// validate

	var validator = new VikFormValidator('#adminForm');

	Joomla.submitbutton = function(task) {
		if (task.indexOf('save') !== -1) {
			if (validator.validate()) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}
	
</script>
