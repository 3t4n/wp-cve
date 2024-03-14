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
JHtml::fetch('formbehavior.chosen');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

$vik->addScript(VAPASSETS_ADMIN_URI . 'js/wizard.js');
$vik->addStyleSheet(VAPASSETS_ADMIN_URI . 'css/wizard.css');
$vik->addStyleSheet(VAPASSETS_ADMIN_URI . 'css/percentage-circle.css');

$layout = new JLayoutFile('wizard.step');

// calculate overall progress
$progress = $this->wizard->getProgress();

?>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<!-- Wizard -->
	<div class="vap-wizard" id="vap-wizard">

		<!-- Wizard toolbar -->
		<div class="vap-wizard-toolbar">

			<!-- Wizard progress -->
			<div class="vap-wizard-progress" id="vap-wizard-progress" style="margin-bottom: 0;">

			</div>

			<!-- Wizard description -->
			<div class="vap-wizard-text">
				<?php echo $vik->alert(JText::translate('VAPWIZARDWHAT'), 'info', false, array('style' => 'margin: 0')); ?>
			</div>

		</div>

		<!-- Wizard steps container -->
		<div class="vap-wizard-steps">

			<?php
			// scan all the supported/active steps
			foreach ($this->wizard as $step)
			{
				?>
				<!-- Wizard step -->
				<div class="wizard-step-outer" data-id="<?php echo $step->getID(); ?>" style="<?php echo $step->isVisible() ? '' : 'display:none;'; ?>">
					<?php
					// display the step by using an apposite layout
					echo $layout->render(array('step' => $step));
					?>
				</div>
				<?php
			}
			?>

		</div>

	</div>
	
	<input type="hidden" name="view" value="vikappointments" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
JText::script('VAPWIZARDBTNDONE_DESC');
JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>

	(function($) {
		'use strict';

		Joomla.submitbutton = (task) => {
			if (task == 'wizard.done') {
				// ask for a confirmation
				var r = confirm(Joomla.JText._('VAPWIZARDBTNDONE_DESC'));

				if (!r) {
					return false;
				}
			}

			// submit form
			Joomla.submitform(task, document.adminForm);
		}

		const dismissWizard = () => {
			UIAjax.do('index.php?option=com_vikappointments&task=wizard.done');
		}

		<?php
		if ($progress == 100)
		{
			// wizard completed send AJAX request to dismiss the wizard
			?>
			dismissWizard();
			<?php
		}
		?>

		$(function() {
			// delegate click event to all buttons with a specific role
			$('#vap-wizard').on('click', '[data-role]', function(event) {
				// executes wizard step according to the button role
				VAPWizard.execute(this).then((data) => {
					// update progress
					$('#vap-wizard-progress').percentageCircle('progress', data.progress);

					if (data.progress == 100) {
						// auto-dismiss wizard on completion
						dismissWizard();
					}
				}).catch((error) => {
					if (error === false) {
						// suppress error
						return false;
					}

					if (!error) {
						// use default connection lost error
						error = Joomla.JText._('VAPCONNECTIONLOSTERROR');
					}

					// use default system alert to display error
					alert(error);
				});
			});

			// render progress circle
			$('#vap-wizard-progress').percentageCircle({
				progress: <?php echo $progress; ?>,
				size: 'small',
				color: '<?php echo $progress == 100 ? 'green' : null; ?>',
			});

			// set green color on complete
			$('#vap-wizard-progress').on('complete', function() {
				$(this).percentageCircle('color', 'green');
				// disable all the buttons to dismiss the steps after reaching the 100% completion
				$('.wizard-step-footer-bar button[data-role="dismiss"]').prop('disabled', true);
			});
		});
	})(jQuery);

</script>
