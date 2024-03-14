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

$country = $this->country;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewCountry". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->openCard(); ?>
	
		<div class="<?php echo $forms ? 'span6' : 'span12'; ?>">
			<?php echo $vik->openEmptyFieldset(); ?>
				
				<!-- COUNTRY NAME - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGECOUNTRY1') . '*'); ?>
					<input type="text" name="country_name" class="input-xxlarge input-large-text required" value="<?php echo $country->country_name; ?>" size="40" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- COUNTRY 2 CODE - Text -->
				
				<?php echo $vik->openControl(JText::translate('VAPMANAGECOUNTRY2') . '*'); ?>
					<input type="text" name="country_2_code" class="required" value="<?php echo $country->country_2_code; ?>" size="20" maxlength="2" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- COUNTRY 3 CODE - Text -->
				
				<?php echo $vik->openControl(JText::translate('VAPMANAGECOUNTRY3') . '*'); ?>
					<input type="text" name="country_3_code" class="required" value="<?php echo $country->country_3_code; ?>" size="20" maxlength="3" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- PHONE PREFIX - Text -->
				
				<?php echo $vik->openControl(JText::translate('VAPMANAGECOUNTRY4') . '*'); ?>
					<input type="text" name="phone_prefix" class="required" value="<?php echo $country->phone_prefix; ?>" size="20" />
				<?php echo $vik->closeControl(); ?>
				
				<!-- PUBLISHED - Radio Button -->
				
				<?php
				$yes = $vik->initRadioElement('', '', $country->published == 1);
				$no  = $vik->initRadioElement('', '', $country->published == 0);
				
				echo $vik->openControl(JText::translate('VAPMANAGECOUNTRY5'));
				echo $vik->radioYesNo('published', $yes, $no, false);
				echo $vik->closeControl();
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewCountry","key":"country","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Details" fieldset (left-side).
				 *
				 * @since 1.7
				 */
				if (isset($forms['country']))
				{
					echo $forms['country'];

					// unset details form to avoid displaying it twice
					unset($forms['country']);
				}
				?>
				
			<?php echo $vik->closeEmptyFieldset(); ?>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewCountry","type":"fieldset"} -->

		<?php
		if ($forms)
		{
			?>
			<div class="span6 full-width">
				<?php
				/**
				 * Iterate remaining forms to be displayed within
				 * the sidebar.
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
			</div>
			<?php
		}
		?>

	<?php echo $vik->closeCard(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $country->id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_vikappointments"/>
</form>

<script>

	jQuery(function($) {
		$('input[name="phone_prefix"]').on('keypress', function(e) {
			if (e.charCode != 43 
				&& (e.charCode < 48 || e.charCode > 57)) {
				return false;
			}

			if (e.charCode == 43 && $(this).val().indexOf('+') != -1) {
				return false;
			}
		});

		$('input[name="phone_prefix"]').on('keyup', function(e) {
			var val = $(this).val();

			if (val.length && val.charAt(0) != '+') {
				$(this).val('+' + val);
			}
		});
	});

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
