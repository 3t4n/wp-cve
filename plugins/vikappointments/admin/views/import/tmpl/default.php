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

if ($this->file)
{
	$filename = basename($this->file);
}
else
{
	$filename = '';
}

?>

<!-- UPLOAD TARGET -->

<?php echo $vik->openCard(); ?>
	<div class="span12">
		<?php echo $vik->openEmptyFieldset(); ?>
				
			<div class="vap-media-droptarget" style="position: relative;">
				<p class="icon">
					<i class="fas fa-upload" style="font-size: 48px;"></i>
				</p>

				<div class="lead">
					<a href="javascript: void(0);" id="upload-file"><?php echo JText::translate('VAPMANUALUPLOAD'); ?></a>&nbsp;<?php echo JText::translate('VAPCSVDRAGDROP'); ?>
				</div>

				<p class="maxsize">
					<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', JHtml::fetch('vikappointments.maxuploadsize')); ?>
				</p>

				<input type="file" id="legacy-upload" style="display: none;"/>

				<div id="vap-selected-file" style="position: absolute; bottom: 6px; left: 6px; display: none;">
					<?php
					if ($this->file)
					{
						?>
						<span class="badge badge-info">
							<?php echo substr($filename, strpos($filename, '_') + 1); ?>
							(<?php echo JHtml::fetch('number.bytes', filesize($this->file)); ?>)
						</span>

						<a href="javascript: void(0);" onclick="deleteExistingFile();">
							<i class="fas fa-trash" style="margin-left: 4px;"></i>
						</a>
						<?php
					}
					?>
				</div>

				<div class="vap-upload-progress" style="position: absolute; bottom: 6px; right: 6px; display: flex; visibility: hidden;">
					<progress value="0" max="100">0%</progress>
				</div>
			</div>

		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
<?php echo $vik->closeCard(); ?>

<form action="index.php" method="post" name="adminForm">

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="import" />
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="import_type" value="<?php echo $this->type; ?>" />

	<?php
	foreach ($this->args as $k => $v)
	{
		?>
		<input type="hidden" name="<?php echo $k; ?>" value="<?php echo $this->escape($v); ?>" />
		<input type="hidden" name="import_args[<?php echo $k; ?>]" value="<?php echo $this->escape($v); ?>" />
		<?php
	}
	?>

</form>

<?php
JText::script('VAPIMPORTCSVUPLOADALERT');
JText::script('VAPCONNECTIONLOSTERROR');
?>

<script>

	(function($) {
		'use strict';

		let dragCounter = 0;

		let FILE_UPLOADED = false;
		let IS_UPLOADING = false;

		const isCsv = (name) => {
			return name.toLowerCase().match(/\.csv$/);
		}

		const fileUploadThread = (file) => {
			// manually reset task before uploading a file
			document.adminForm.task.value = '';

			const progressBox = $('.vap-upload-progress');
			progressBox.css('visibility', 'visible');
			progressBox.find('progress').val(0).text('0%');
			
			const formData = new FormData();
			formData.append('source', file);
			formData.append('import_type', '<?php echo $this->type; ?>');

			UIAjax.upload(
				// end-point URL
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=import.dropupload'); ?>',
				// file post data
				formData,
				// success callback
				(resp) => {
					// force progress to 100%
					progressBox.find('progress').val(100).text('100%');

					IS_UPLOADING = false;

					setTimeout(() => {
						// auto-submit for import
						Joomla.submitform('import.add', document.adminForm);
					}, 256);
				},
				// failure callback
				(error) => {
					alert(error.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));

					progressBox.css('visibility', 'hidden');

					IS_UPLOADING = false;
				},
				// progress callback
				(progress) => {
					// update progress
					progressBox.find('progress').val(progress).text(progress + '%');
				}
			);
		}

		const execUpload = (files) => {
			if (IS_UPLOADING) {
				return false;
			}

			const bar = $('#vap-selected-file');

			// hide bar
			bar.hide().html();

			if (!files || !files.length) {
				// invalid argument
				return false;
			}

			let file = files[0];
			
			// make sure we have a valid CSV file
			if (!isCsv(file.name)) {
				return false;
			}

			FILE_UPLOADED = false;
			IS_UPLOADING  = true;

			let sizeStr = "";
			
			if (file.size > 1024 * 1024) {
				sizeStr = (file.size / (1024 * 1024)).toFixed(2) + " MB";
			} else if (file.size > 1024) {
				sizeStr = (file.size / 1024).toFixed(2) + " kB";
			} else {
				sizeStr = file.size.toFixed(2) + " b";
			}

			// display badge
			const badge = $('<span class="badge badge-info"></span>').text(file.name + ' (' + sizeStr + ')');
			bar.html(badge).show();
			
			fileUploadThread(file);

			return true;
		}

		$(function() {

			// drag&drop actions on target div

			$('.vap-media-droptarget').on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
				e.preventDefault();
				e.stopPropagation();
			});

			$('.vap-media-droptarget').on('dragenter', function(e) {
				// increase the drag counter because we may
				// enter into a child element
				dragCounter++;

				$(this).addClass('drag-enter');
			});

			$('.vap-media-droptarget').on('dragleave', function(e) {
				// decrease the drag counter to check if we 
				// left the main container
				dragCounter--;

				if (dragCounter <= 0) {
					$(this).removeClass('drag-enter');
				}
			});

			$('.vap-media-droptarget').on('drop', function(e) {

				$(this).removeClass('drag-enter');
				
				var files = e.originalEvent.dataTransfer.files;
				
				execUpload(files);
				
			});

			$('.vap-media-droptarget #upload-file').on('click', function() {

				$('input#legacy-upload').trigger('click');

			});

			$('input#legacy-upload').on('change', function() {
				
				execUpload($(this)[0].files);

			});

			Joomla.submitbutton = function(task) {
				if (task == 'import.add' && !FILE_UPLOADED) {
					alert(Joomla.JText._('VAPIMPORTCSVUPLOADALERT'));

					return false;
				}	
				
				Joomla.submitform(task, document.adminForm);
			}

			<?php if ($this->file) { ?>

				$('#vap-selected-file').show();

				// this avoids to auto-aubmit the form
				FILE_UPLOADED = true;

			<?php } ?>
		});
	})(jQuery);

	function deleteExistingFile() {
		Joomla.submitform('import.delete', document.adminForm);
	}

</script>
