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

$vik = VAPApplication::getInstance();

$code_mirror = $vik->getCodeMirror('content', $this->content);

$data = array(
	'name'   => basename($this->file),
	'path'   => $this->file,
	'base64' => base64_encode($this->file),
);

?>

<form action="index.php" method="POST" name="adminForm" id="adminForm">

	<?php
	if ($this->blank)
	{
		?>
		<div class="btn-toolbar" style="display:none;">
			<div class="btn-group pull-left">
				<button type="button" class="btn btn-success" name="tmplSaveButton" onclick="fileSaveButtonPressed(this);">
					<i class="icon-apply"></i>&nbsp;<?php echo JText::translate('VAPSAVE'); ?>
				</button>

				<button type="button" class="btn btn-success" name="tmplSaveCopyButton" onclick="fileSaveAsCopyButtonPressed(this);">
					<i class="icon-save-copy"></i>&nbsp;<?php echo JText::translate('VAPSAVEASCOPY'); ?>
				</button>
			</div>
		</div>
		<?php
	}
	?>
	
	<div class="managefile-wrapper" style="padding:0 10px;">

		<h3><?php echo basename($this->file); ?></h3>
		
		<div class="vap-file-box">
			<?php echo $code_mirror; ?>
		</div>
	
	</div>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="file" value="<?php echo base64_encode($this->file); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />

	<?php
	if ($this->blank)
	{
		?><input type="hidden" name="tmpl" value="component" /><?php
	}
	?>
	
</form>

<?php
JText::script('VAPNEWFILENAMETITLE');
?>

<script>
	
	function fileSaveButtonPressed(button) {
		if (jQuery(button).prop('disabled')) {
			// button already submitted
			return false;
		}

		// disable button
		jQuery(button).prop('disabled', true);

		<?php
		/**
		 * In WordPress the codemirror seems to have rendering problems while
		 * initialized on a hidden panel. For this reason, we need to refresh
		 * its contents when the modal is displayed.
		 * @wponly
		 */
		if (VersionListener::isWordpress())
		{
			?>
			Joomla.editors.instances.content.element.codemirror.save();
			<?php
		}
		?>

		Joomla.submitform('file.save', document.adminForm);
	}

	function fileSaveAsCopyButtonPressed(button) {
		if (jQuery(button).prop('disabled')) {
			// button already submitted
			return false;
		}

		// disable button
		jQuery(button).prop('disabled', true);

		// ask for the new name
		var name = prompt(Joomla.JText._('VAPNEWFILENAMETITLE'), 'file.php');

		if (!name) {
			// enable button
			jQuery(button).prop('disabled', false);

			// invalid name or aborted
			return false;
		}

		<?php
		/**
		 * In WordPress the codemirror seems to have rendering problems while
		 * initialized on a hidden panel. For this reason, we need to refresh
		 * its contents when the modal is displayed.
		 * @wponly
		 */
		if (VersionListener::isWordpress())
		{
			?>
			Joomla.editors.instances.content.element.codemirror.save();
			<?php
		}
		?>

		if (!name.match(/\.php$/i)) {
			// append ".php" if not specified
			name += '.php';
		}

		jQuery('#adminForm').append('<input type="hidden" name="dir" value="<?php echo base64_encode(dirname($this->file)); ?>" />');
		jQuery('#adminForm').append('<input type="hidden" name="filename" value="' + name + '" />');

		Joomla.submitform('file.savecopy', document.adminForm);
	}

	<?php
	if ($this->blank)
	{
		?>
		// transfer submit buttons instances to parent for being clicked
		window.parent.modalFileSaveButton     = document.adminForm.tmplSaveButton;
		window.parent.modalFileSaveCopyButton = document.adminForm.tmplSaveCopyButton;

		// transfer saved file path to parent
		window.parent.modalSavedFile = <?php echo json_encode($data); ?>;
		<?php
	}
	else
	{
		?>
		Joomla.submitbutton = function(task) {
			if (task == 'file.savecopy') {
				fileSaveAsCopyButtonPressed(null);
			} else {
				Joomla.submitform(task, document.adminForm);
			}
		}
		<?php
	}
	?>
	
</script>
