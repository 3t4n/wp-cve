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

/**
 * Layout variables
 * -----------------
 * @var  VAPWizardStep  $step  The wizard step instance.
 */
extract($displayData);

?>

<div class="wizard-step">
					
	<!-- Wizard step top bar -->
	<div class="wizard-step-top-bar<?php echo $step->isCompleted() ? ' completed' : ''; ?>">

		<span class="top-bar-primary">
			<?php
			if ($icon = $step->getIcon())
			{
				?>
				<span class="wizard-step-icon"><?php echo $icon; ?></span>
				<?php
			}
			?>

			<span class="wizard-step-title"><?php echo $step->getTitle(); ?></span>
		</span>

		<span class="top-bar-secondary pull-right badge badge-info">
			<?php echo $step->getGroup(); ?>
		</span>

	</div>

	<!-- Wizard step body -->
	<div class="wizard-step-body">

		<?php
		if ($step->canExecute())
		{
			?>
			<div class="wizard-step-body-inner">
				<?php
				if ($description = $step->getDescription())
				{
					?>
					<div class="wizard-step-desc">
						<?php echo $description; ?>
					</div>
					<?php
				}

				echo $step->display();
				?>
			</div>
			<?php
		}
		else
		{
			// inform the user that the step cannot be processed yet
			?>
			<div class="wizard-step-body-dep">
				<?php echo JText::translate('VAPWIZARDDEPEND'); ?>

				<ul>
					<?php
					foreach ($step->getDependencies() as $dep)
					{
						?><li><b><?php echo $dep->getTitle(); ?></b></li><?php
					}
					?>
				</ul>
			</div>
			<?php
		}
		?>
	</div>

	<!-- Wizard step footer bar -->
	<div class="wizard-step-footer-bar">
		<?php
		if ($step->isCompleted())
		{
			// step completed, display button to dismiss it
			?>
			<button type="button" class="btn pull-left" data-role="dismiss"><?php echo JText::translate('VAPWIZARDBTNDISMISS'); ?></button>
			<?php
		}
		else
		{
			if ($step->canIgnore())
			{
				// step can be ignored, display button to skip it
				?>
				<button type="button" class="btn pull-left" data-role="ignore"><?php echo JText::translate('VAPWIZARDBTNIGNORE'); ?></button>
				<?php
			}

			if ($step->canExecute())
			{
				// display button to process the step
				echo $step->getExecuteButton();
			}
		}
		?>
	</div>

</div>
