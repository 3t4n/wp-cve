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

JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.colorpicker');

$tag = $this->tag;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewTag".
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>

		<!-- LEFT SIDE -->

		<div class="span8">

			<!-- TAG -->
		
			<div class="row-fluid">
				<div class="span12">
					<?php 
					echo $vik->openFieldset(JText::translate('VAPCUSTFIELDSLEGEND1'));
					echo $this->loadTemplate('tag');
					?>	

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewTag","key":"tag","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Details" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['tag']))
					{
						echo $forms['tag'];

						// unset details form to avoid displaying it twice
						unset($forms['tag']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- DESCRIPTION -->

			<div class="row-fluid">
				<div class="span12">
					<?php 
					echo $vik->openFieldset(JText::translate('VAPMANAGEGROUP3'));
					echo $this->loadTemplate('description');
					?>	

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewTag","key":"description","type":"field"} -->

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

		<!-- RIGHT SIDE -->

		<div class="span4 full-width">

			<!-- OPTIONS -->

			<div class="row-fluid">
				<div class="span12">
					<?php 
					echo $vik->openFieldset(JText::translate('JGLOBAL_FIELDSET_BASIC'));
					echo $this->loadTemplate('options');
					?>	

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewTag","key":"options","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Options" fieldset (right-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['options']))
					{
						echo $forms['options'];

						// unset details form to avoid displaying it twice
						unset($forms['options']);
					}

					echo $vik->closeFieldset();
					?>
				</div>
			</div>

			<!-- Define role to detect the supported hook -->
			<!-- {"rule":"customizer","event":"onDisplayViewTag","type":"fieldset"} -->

			<?php
			// iterate forms to be displayed within the sidebar panel
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
		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="group" value="<?php echo $this->escape($tag->group); ?>" />
	<input type="hidden" name="id" value="<?php echo $tag->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<script>

	// keep a global reference for the validator
	var validator;

	(function($) {
		'use strict';

		// create validator instance
		validator = new VikFormValidator('#adminForm');

		Joomla.submitbutton = function(task) {
			if (task.indexOf('save') === -1 || validator.validate()) {
				Joomla.submitform(task, document.adminForm);    
			}
		}
	})(jQuery);

</script>
