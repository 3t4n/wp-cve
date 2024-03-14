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

if ($this->showHelp)
{
	JHtml::fetch('vaphtml.assets.toast', 'bottom-right');
	JText::script('VAPMEDIAFIRSTCONFIG2');
}

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

				<div class="span6">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMANAGEMEDIATITLE1'));
					echo $this->loadTemplate('upload');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewMedia","key":"upload","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Media File" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['upload']))
					{
						echo $forms['upload'];

						// unset details form to avoid displaying it twice
						unset($forms['upload']);
					}
						
					echo $vik->closeFieldset(); ?>
				</div>
				
				<div class="span6">
					<?php
					echo $vik->openFieldset(JText::translate('VAPMEDIAPROPBOXTITLE'));
					echo $this->loadTemplate('media');
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewMedia","key":"media","type":"field"} -->

					<?php	
					/**
					 * Look for any additional fields to be pushed within
					 * the "Media Properties" fieldset (left-side).
					 *
					 * @since 1.7
					 */
					if (isset($forms['media']))
					{
						echo $forms['media'];

						// unset details form to avoid displaying it twice
						unset($forms['media']);
					}
					
					echo $vik->closeFieldset(); ?>
				</div>

			</div>

			<div class="row-fluid">
				<div class="span12" style="display: none;" id="vap-uploads">
					<?php echo $vik->openFieldset(JText::translate('VAPMANAGEMEDIATITLE3')); ?>
						<div class="control" id="vap-uploads-cont"></div>
					<?php echo $vik->closeFieldset(); ?>
				</div>
			</div>

		</div>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<script>

	<?php
	/**
	 * Display a toast message to guide the user about
	 * the steps needed to change the default size
	 * that will be used to create the thumbnails.
	 */
	if ($this->showHelp)
	{
		?>
		jQuery(function($) {
			// display toast message with a delay of 256 ms
			setTimeout(() => {
				ToastMessage.dispatch({
					text: Joomla.JText._('VAPMEDIAFIRSTCONFIG2'),
					status: 3,
					delay: 10000,
				});
			}, 256);
		});
		<?php
	}
	?>
	
</script>
