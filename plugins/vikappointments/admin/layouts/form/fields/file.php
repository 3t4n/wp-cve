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

$name     = !empty($displayData['name'])    ? $displayData['name']     : 'name';
$id       = !empty($displayData['id'])      ? $displayData['id']       : $name;
$value    = isset($displayData['value'])    ? $displayData['value']    : '';
$class    = isset($displayData['class'])    ? $displayData['class']    : '';
$multiple = isset($displayData['multiple']) ? $displayData['multiple'] : false;
$files    = isset($displayData['files'])    ? $displayData['files']    : array();

$countFiles = count($files);

if ($countFiles > 1)
{
	// display number of selected files
	$text_value = JText::plural('VAP_DEF_N_SELECTED', $countFiles);

	// load context menu to implement preview for multi-files
	JHtml::fetch('vaphtml.assets.contextmenu');
}
else
{
	// display name of uploaded file (if any)
	$text_value = $files ? $files[0]['name'] : '';
}

?>

<div class="<?php echo ($countFiles ? 'input-prepend ' : ''); ?>input-append file-field">
	<?php
	if ($countFiles > 1)
	{
		?>
		<button type="button" class="btn" id="<?php echo $id; ?>-preview">
			<i class="fas fa-eye"></i>
		</button>
		<?php
	}
	else if ($countFiles == 1)
	{
		?>
		<a href="<?php echo $files[0]['uri']; ?>" class="btn" target="_blank">
			<i class="fas fa-eye"></i>
		</a>
		<?php
	}
	?>

	<input
		type="text"
		id="<?php echo $this->escape($id); ?>-text"
		value="<?php echo $this->escape($text_value); ?>"
		placeholder="<?php echo $this->escape(JText::translate('VAP_DEF_N_SELECTED_0')); ?>"
		size="30"
		readonly
	/>

	<button type="button" class="btn btn-primary" id="<?php echo $this->escape($id); ?>-trigger">
		<i class="fas fa-upload"></i>
	</button>
</div>

<input
	type="file"
	name="<?php echo $name; ?>"
	id="<?php echo $id; ?>"
	class="<?php echo $class; ?>"
	style="display:none;"
	<?php echo $multiple ? 'multiple' : ''; ?>
/>

<?php
if ($value)
{
	// fetch name for the input holding the uploaded file(s)
	$old_input_name = 'old_' . $name;

	if (!$multiple && $countFiles > 1)
	{
		// The input was multiple and the user already uploaded
		// more than a file... In order to manage them in the
		// correct way at the next upload, we need to treat the
		// input as an array.
		$old_input_name .= '[]';
	}

	// register already uploaded files for being able to remove
	// them after uploading new files
	foreach ((array) $value as $old)
	{
		?>
		<input type="hidden" name="<?php echo $old_input_name; ?>" value="<?php echo $old; ?>" />
		<?php
	}
}

JText::script('VAP_DEF_N_SELECTED');
?>

<script>

	jQuery(function($) {
		let input = $('#<?php echo $id; ?>');

		let oldFiles = <?php echo json_encode($files); ?>;

		// manually force form enctype to support file uploads
		$(input[0].form).attr('enctype', 'multipart/form-data');

		// open file selection dialog
		$('#<?php echo $id; ?>-trigger').on('click', () => {
			// unset selected files before showing the dialog
			input.val(null).trigger('click');
		});

		// update preview text
		input.on('click change', () => {
			let count = input[0].files.length;
			let text  = '';

			if (count == 1) {
				// set file name
				text = input[0].files[0].name;
			} else if (count > 1) {
				// use count
				text = Joomla.JText._('VAP_DEF_N_SELECTED').replace(/%d/, count);
			} else {
				// no selection, rely on uploaded files
				if (oldFiles.length == 1) {
					text = oldFiles[0].name;
				} else if (count > 1) {
					text = Joomla.JText._('VAP_DEF_N_SELECTED').replace(/%d/, oldFiles.length);
				}
			}

			// update preview text
			$('#<?php echo $id; ?>-text').val(text);
		});

		if (oldFiles.length > 1) {
			// construct preview buttons
			let buttons = [];

			oldFiles.forEach((file) => {
				buttons.push({
					// use file name as button text
					text: file.name,
					// use icon to describe that the file will be
					// opened in a different tab of the browser
					icon: 'fas fa-external-link-square-alt',
					// add separator between the files
					separator: true,
					// store an internal reference of the window
					frame: null,
					// action used to open the linked file
					action: function() {
						// check whether the window is currently open
						if (this.frame && !this.frame.closed) {
							// window already open, just focus it
							this.frame.focus()
						} else {
							// open file within a new tab of the browser
							// and save window reference
							this.frame = window.open(file.uri, '_blank');
						}
					},
				});
			});

			// open context menu while clicking the preview button
			let cm = $('#<?php echo $id; ?>-preview').vikContextMenu({
				buttons: buttons,
			});
		}
	});

</script>
