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

JHtml::fetch('vaphtml.assets.select2');

$media = $this->media;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewMedia". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<div class="span12">

			<div class="row-fluid">
			
				<div class="span12">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEMEDIATITLE1'));
					echo $this->loadTemplate('media');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewMedia","key":"media","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Media File" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['media']))
					{
						echo $forms['media'];

						// unset details form to avoid displaying it twice
						unset($forms['media']);
					}
					
					echo $vik->closeFieldset();
					?>
				</div>

			</div>

			<div class="row-fluid">

				<div class="span6">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEMEDIA3'));
					echo $this->loadTemplate('image');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewMedia","key":"image","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Image" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['image']))
					{
						echo $forms['image'];

						// unset details form to avoid displaying it twice
						unset($forms['image']);
					}

					echo $vik->closeFieldset(); ?>
				</div>

				<div class="span6">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEMEDIATITLE4'));
					echo $this->loadTemplate('thumb');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewMedia","key":"thumb","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Thumbnail" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['thumb']))
					{
						echo $forms['thumb'];

						// unset details form to avoid displaying it twice
						unset($forms['thumb']);
					}

					echo $vik->closeFieldset(); ?>
				</div>

			</div>

		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="media" value="<?php echo $media['name']; ?>" />
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
