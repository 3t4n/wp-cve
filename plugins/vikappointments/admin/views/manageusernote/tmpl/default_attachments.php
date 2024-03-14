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

JHtml::fetch('vaphtml.assets.contextmenu');

$note = $this->note;

$vik = VAPApplication::getInstance();

?>

<div class="vap-media-gallery vap-usernote-docs">

	<?php
	foreach ($note->attachments as $file)
	{
		// load file details
		$media = AppointmentsHelper::getFileProperties($file);

		if (!$media)
		{
			// file manually deleted...
			continue;
		}

		?>
		<div class="vap-media-block"
			data-name="<?php echo $this->escape($media['name']); ?>"
			data-url="<?php echo $this->escape($media['uri']); ?>"
			data-ext="<?php echo $this->escape($media['file_ext']); ?>"
			data-size="<?php echo $this->escape($media['size']); ?>"
			data-date="<?php echo $this->escape($media['creation']); ?>"
		>
			<div class="media-preview">
				<div class="media-thumbnail">
					<div class="media-centered">
						<?php
						// render media by using model
						echo $this->mediaModel->renderMedia($media);
						?>
					</div>
				</div>
			</div>

			<input type="hidden" name="attachments[]" value="<?php echo $this->escape($media['file']); ?>" />
		</div>
		<?php
	}
	?>

</div>

<div class="vap-media-droptarget">
	<p class="icon">
		<i class="fas fa-upload" style="font-size: 48px;"></i>
	</p>

	<div class="lead">
		<a href="javascript: void(0);" id="upload-file"><?php echo JText::translate('VAPMANUALUPLOAD'); ?></a>&nbsp;<?php echo JText::translate('VAPMEDIADRAGDROP'); ?>
	</div>

	<p class="maxsize">
		<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', JHtml::fetch('vikappointments.maxuploadsize')); ?>
	</p>

	<input type="file" id="legacy-upload" multiple style="display: none;"/>
</div>

<?php
JText::script('VAPOPEN');
JText::script('VAPMOREINFO');
JText::script('VAPDELETE');
JText::script('VAPSYSTEMCONFIRMATIONMSG');
?>

<script>

	(function($) {
		'use strict';

		// class handling the media file
		function MediaFile() {
			// create parent
			this.fileBlock = jQuery('<div class="vap-media-block" data-name="" data-selected="0"></div>');

			// create media thumbnail
			this.fileThumb = jQuery('<div class="media-preview"><div class="media-thumbnail"><div class="media-centered"><i class="fas fa-file-image"></i></div></div></div>').appendTo(this.fileBlock);

			this.setFileName = function(name) {
				this.fileBlock.attr('data-name', name);
			}
			
			this.setProgress = function(progress) {       
				var opacity = parseFloat(progress / 100);

				this.fileThumb.find('i.fas').css('opacity', Math.max(0.1, opacity));
			}
			
			this.complete = function(file) {
				this.setProgress(100);

				this.fileBlock.attr('data-name', file.name);
				this.fileBlock.attr('data-url', file.uri);
				this.fileBlock.attr('data-ext', file.file_ext);
				this.fileBlock.attr('data-size', file.size);
				this.fileBlock.attr('data-date', file.creation);

				// register uploaded file
				this.fileBlock.append($('<input type="hidden" name="attachments[]" />').val(file.file));

				// append file preview
				this.fileThumb.find('.media-centered').html(file.html);

				// attach context menu to uploaded file
				createContextMenu(this.fileBlock);
			}
			
			this.getHtml = function() {
				return this.fileBlock;
			}
		}

		let uploadsPath  = null;
		let uploadsQueue = [];
		let canUpload    = true;

		// upload
		const dispatchMediaUploads = (files) => {
			for (let i = 0; i < files.length; i++) {
				var status = new MediaFile();
				status.setFileName(files[i].name);
				status.setProgress(0);
				
				$('.vap-media-gallery').append(status.getHtml());

				uploadsQueue.push({
					status: status,
					file: files[i],
				});
			}

			// We need to process the uploads one by one because the destination path
			// is created during the first upload. For this reason, we need to wait for
			// the upload completion of the first file, in order to use the same path 
			// also for the other files.
			if (uploadsQueue.length && canUpload) {
				// temporarily lock uploads
				canUpload = false;

				// extract first element
				let seek = uploadsQueue.shift();
				// run upload
				uploadThread(seek.status, seek.file).then((file) => {
					// update uploads path with the folder of the newly created file
					uploadsPath = file.path;
				}).catch((err) => {
					// upload failed
				}).finally(() => {
					canUpload = true;
					// recursively process the uploads queue
					dispatchMediaUploads([]);
				});
			}
		}

		// create upload thread
		const uploadThread = (status, file) => {
			var formData = new FormData();

			if (uploadsPath) {
				// convert uploads path in base64
				formData.append('path', btoa(uploadsPath));
			}

			formData.append('file', file);
			formData.append('id_note', '<?php echo $note->id; ?>');
			formData.append('id_user', '<?php echo $note->id_user; ?>');
			formData.append('id_parent', '<?php echo $note->id_parent; ?>');
			formData.append('group', '<?php echo $note->group; ?>');

			return new Promise((resolve, reject) => {
				UIAjax.upload(
					// end-point URL
					'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=usernote.dropupload'); ?>',
					// file post data
					formData,
					// success callback
					(resp) => {
						// finalize upload
						status.complete(resp);

						resolve(resp);
					},
					// failure callback
					(error) => {
						// raise alert
						alert(error.responseText);

						// remove file from gallery
						$('.vap-media-block[data-name="' + file.name + '"]').remove();

						reject(error);
					},
					// progress callback
					(progress) => {
						// update progress
						status.setProgress(progress);
					}
				);
			});
		}

		// helper used to attach the context menu to the specified element(s)
		const createContextMenu = (elem) => {
			$(elem).vikContextMenu({
				buttons: [
					// OPEN
					{
						// Use a FontAwesome icon.
						icon: 'fas fa-external-link-alt',
						// Enter the text of the button.
						text: Joomla.JText._('VAPOPEN'),
						// Define the callback to dispatch when the
						// button gets clicked.
						action: (root, config) => {
							// open file in a new tab of the browser
							window.open($(root).data('url'), '_blank');
						},
					},
					// SHOW INFO
					{
						// Use a FontAwesome icon.
						icon: 'fas fa-info-circle',
						// Enter the text of the button.
						text: Joomla.JText._('VAPMOREINFO'),
						// Add a bottom separator.
						separator: true,
						// Define the callback to dispatch when the
						// button gets clicked.
						action: (root, config) => {
							// open inspector
							vapOpenInspector('attachments-inspector', {element: root});
						},
					},
					// TRASH
					{
						// Use a FontAwesome icon.
						icon: 'fas fa-trash',
						// Enter the text of the button.
						text: Joomla.JText._('VAPDELETE'),
						// Add a custom class for individual styling.
						class: 'danger',
						// Define the callback to dispatch when the
						// button gets clicked.
						action: (root, config) => {
							// ask confirmation
							if (confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'))) {
								// get file path
								let file = $(root).find('input[name="attachments[]"]').val();
								// register file within the form to properly unlink it after save
								$('#adminForm').append($('<input type="hidden" name="deleted_attachments[]" />').val(file));
								// delete element from document
								$(root).remove();
							}
						},
					},
				]
			});
		};

		// handle D&D upload events
		$(function() {
			var dragCounter = 0;

			// drag&drop actions on target div
			$('.vap-media-droptarget').on('drag dragstart dragend dragover dragenter dragleave drop', (e) => {
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
				dispatchMediaUploads(files);
			});

			$('.vap-media-droptarget #upload-file').on('click', () => {
				// unset selected files before showing the dialog
				$('input#legacy-upload').val(null).trigger('click');
			});

			$('input#legacy-upload').on('change', function() {
				// execute AJAX uploads after selecting the files
				dispatchMediaUploads($(this)[0].files);
			});

			// open context menu while clicking the media file
			createContextMenu('.vap-media-block');
		});
	})(jQuery);
	
</script>
