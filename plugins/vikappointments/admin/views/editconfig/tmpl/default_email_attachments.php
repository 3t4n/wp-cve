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
 * called "onDisplayViewConfigEmailAttachments". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('EmailAttachments');

?>

<!-- MANUAL -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPATTACHMENTS'); ?></h3>
	</div>

	<div class="config-fieldset-body">
	
		<!-- MAIL ATTACHMENT - File -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG49'),
			'content' => JText::translate('VAPMANAGECONFIG49_DESC'),
		));

		// define media manager options
		$options = array(
			'path'     => VAPMAIL_ATTACHMENTS,
			'multiple' => true,
			'filter'   => false,
			'preview'  => false,
			'icon'     => 'fas fa-upload',
		);

		if ($params['mailattach'])
		{
			// try to JSON decode the attachments list
			$params['mailattach'] = json_decode($params['mailattach']);

			if ($params['mailattach'] === null)
			{
				// an error occurred while trying to decode the list,
				// we probably have a single uploaded file (B.C.)
				$params['mailattach'] = array($params['mailattach']);
			}
		}

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG49') . $help);
		echo JHtml::fetch('vaphtml.mediamanager.field', 'mailattach', $params['mailattach'], 'vap-mail-attach', $options);
		echo $vik->closeControl();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailAttachments","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the E-mail > Attachments > Attachments fieldset.
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

<!-- ICS -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3>ICS</h3>
	</div>

	<div class="config-fieldset-body">

		<?php echo $vik->alert(JText::translate('VAPMANAGECONFIG65_DESC'), 'info'); ?>
	
		<!-- ATTACH ICS TO - Checkbox List -->

		<?php 
		$ics = explode(';', $params['icsattach']);

		for ($i = 0; $i < count($ics); $i++)
		{
			$yes = $vik->initRadioElement('', '', $ics[$i] == 1);
			$no  = $vik->initRadioElement('', '', $ics[$i] == 0);

			echo $vik->openControl(JText::translate('VAPCONFIGSMSAPITO' . $i));
			echo $vik->radioYesNo('icsattach' . ($i + 1), $yes, $no);
			echo $vik->closeControl();
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailAttachments","key":"ics","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the E-mail > Attachments > ICS fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['ics']))
		{
			echo $forms['ics'];

			// unset details form to avoid displaying it twice
			unset($forms['ics']);
		}
		?>

	</div>

</div>

<!-- CSV -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3>CSV</h3>
	</div>

	<div class="config-fieldset-body">

		<?php echo $vik->alert(JText::translate('VAPMANAGECONFIG66_DESC'), 'info'); ?>
	
		<!-- ATTACH CSV TO - Checkbox List -->

		<?php 
		$csv = explode(';', $params['csvattach']);
		
		for ($i = 0; $i < count($csv); $i++)
		{
			$yes = $vik->initRadioElement('', '', $csv[$i] == 1);
			$no  = $vik->initRadioElement('', '', $csv[$i] == 0);

			echo $vik->openControl(JText::translate('VAPCONFIGSMSAPITO' . $i));
			echo $vik->radioYesNo('csvattach' . ($i + 1), $yes, $no);
			echo $vik->closeControl();
		}
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailAttachments","key":"csv","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the E-mail > Attachments > CSV fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['csv']))
		{
			echo $forms['csv'];

			// unset details form to avoid displaying it twice
			unset($forms['csv']);
		}
		?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigEmailAttachments","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the E-mail > Attachments tab.
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
