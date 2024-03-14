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

$config = $this->config;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigappBackupDetails". The event method
 * receives the view instance as argument.
 *
 * @since 1.7.1
 */
$forms = $this->onDisplayView('BackupDetails');

?>

<div class="config-fieldset" id="backup-config-panel">

	<div class="config-fieldset-body">

		<!-- TYPE - Select -->

		<?php
		$options = [];

		foreach ($this->backupExportTypes as $type => $handler)
		{
			$options[] = JHtml::fetch('select.option', $type, $handler->getName());	
		}

		$backup_export_type = $config->get('backuptype', 'full');

		echo $vik->openControl(JText::translate('VAPBACKUPCONFIG1')); ?>
			<select name="backuptype" class="medium-large">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $backup_export_type); ?>
			</select>
		<?php

		// display a description for the export types
		foreach ($this->backupExportTypes as $type => $handler)
		{
			echo $vik->alert($handler->getDescription(), 'info', $dismiss = false, [
				'style' => $type === $backup_export_type ? '' : 'display: none;',
				'id'    => 'backup_export_type_' . $type,
			]);
		}

		echo $vik->closeControl();
		?>

		<!-- FOLDER - Text -->

		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPBACKUPCONFIG2'),
			'content' => JText::translate('VAPBACKUPCONFIG2_HELP'),
		));

		// get saved path
		$path = rtrim($config->get('backupfolder', ''), DIRECTORY_SEPARATOR);

		// get system temporary path
		$tmp_path = rtrim(JFactory::getApplication()->get('tmp_path', ''), DIRECTORY_SEPARATOR);

		if (!$path)
		{
			$path = $tmp_path;
		}

		echo $vik->openControl(JText::translate('VAPBACKUPCONFIG2') . $help); ?>
			<input type="text" name="backupfolder" value="<?php echo $this->escape($path); ?>" size="64" />
		<?php
		// check whether the specified path is equals to the temporary path
		if ($path === $tmp_path)
		{
			// inform the administrator that it is not safe to use the temporary path to store the back-up of the system
			$warning = JText::sprintf('VAPBACKUPCONFIG2_WARN', $tmp_path . DIRECTORY_SEPARATOR . VikAppointments::generateSerialCode(8));
			echo $vik->alert($warning, 'warning');
		}

		echo $vik->closeControl();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigappBackupDetails","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Backup > Settings > Details fieldset.
		 *
		 * @since 1.7.1
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

		<!-- BACK-UP MANAGEMENT - Button -->

		<?php echo $vik->openControl(''); ?>
			<a href="index.php?option=com_vikappointments&amp;view=backups" class="btn" id="backup-btn">
				<?php echo JText::translate('VAPCONFIGSEEBACKUP'); ?>
			</a>
		<?php echo $vik->closeControl(); ?>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigappBackupDetails","type":"fieldset"} -->

<?php
// iterate remaining forms to be displayed as new fieldsets
// within the Backup > Settings tab
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

JText::script('VAPFORMCHANGEDCONFIRMTEXT');
?>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('select[name="backuptype"]').on('change', function() {
				const type = $(this).val();

				$('#adminForm *[id^="backup_export_type_"]').hide();
				$('#backup_export_type_' + type).show();
			});

			$('#backup-btn').on('click', (event) => {
				if (!configObserver.isChanged()) {
					// nothing has changed, go ahead
					return true;
				}

				// ask for a confirmation
				let r = confirm(Joomla.JText._('VAPFORMCHANGEDCONFIRMTEXT'));

				if (!r) {
					// do not leave the page
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
			});
		});
	})(jQuery);

</script>
