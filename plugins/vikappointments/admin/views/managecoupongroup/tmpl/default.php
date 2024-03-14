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

$group = $this->group;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCoupongroup".
 * It is also possible to use "onDisplayViewCoupongroupSidebar"
 * to include any additional fieldsets within the right sidebar.
 * The event method receives the view instance as argument.
 *
 * @since 1.7
 */
$detailsForms = $this->onDisplayView();
$sidebarForms = $this->onDisplayView('Sidebar');

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<div class="<?php echo ($sidebarForms ? 'span7' : 'span12'); ?>">
	
			<?php echo $vik->openFieldset($sidebarForms ? JText::translate('VAPCUSTFIELDSLEGEND1') : ''); ?>

				<!-- NAME - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEGROUP2') . '*'); ?>
					<input type="text" name="name" class="input-xxlarge input-large-text required" value="<?php echo $this->escape($group->name); ?>" size="64" />
				<?php echo $vik->closeControl(); ?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewCoupongroup","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the main fieldset (left-side).
				 */
				foreach ($detailsForms as $formField)
				{
					echo $formField;
				}
				?>
			
				<!-- DESCRIPTION - Textarea -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEGROUP3')); ?>
					<textarea name="description" style="width:600px;height:200px;resize:vertical;"><?php echo $group->description; ?></textarea>
				<?php echo $vik->closeControl(); ?>

			<?php echo $vik->closeFieldset(); ?>

		</div>

		<?php
		if ($sidebarForms)
		{
			?>
			<div class="span5 full-width">

				<?php
				// iterate forms to be displayed within the sidebar panel
				foreach ($sidebarForms as $formName => $formHtml)
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
			</div>
			<?php
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewCoupongroupSidebar","type":"fieldset"} -->

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $group->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

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
