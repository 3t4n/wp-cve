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
JHtml::fetch('vaphtml.assets.fontawesome');

if ($this->firstConfig)
{
	JHtml::fetch('vaphtml.assets.toast', 'bottom-center', '.vap-media-modal');
	JText::script('VAPMEDIAFIRSTCONFIG');
}

$files    = $this->rows;
$selected = $this->selected;
$multiple = $this->multiple;

$vik = VAPApplication::getInstance();

$model = $this->getModel();

?>

<div class="vap-media-modal">

	<div class="vap-media-grid-box">

		<div class="btn-toolbar" style="height: 32px;">

			<div class="btn-group pull-left input-append hide-with-size-320">
				<input type="text" id="mediakeysearch" size="32" value="" placeholder="<?php echo JText::translate('JSEARCH_FILTER_SUBMIT'); ?>" autocomplete="off" />

				<button type="button" class="btn" onclick="jQuery('#mediakeysearch').trigger('change');">
					<i class="fas fa-search"></i>
				</button>
			</div>

			<div class="btn-group pull-left hide-with-size-390">
				<button type="button" class="btn" onclick="jQuery('#mediakeysearch').val('').trigger('change');"><?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>

		</div>

		<?php
		$attrs = array();
		$attrs['id'] = 'no-media-results-alert';

		if ($files)
		{
			$attrs['style'] = 'display:none;';
		}

		echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'), 'warning', false, $attrs);
		?>

		<div class="vap-media-gallery">

			<?php
			foreach ($files as $media)
			{
				?>
				<div class="vap-media-block"
					data-name="<?php echo $this->escape($media['name']); ?>"
					data-ext="<?php echo $this->escape($media['file_ext']); ?>"
					data-size="<?php echo $this->escape($media['size']); ?>"
					data-date="<?php echo $this->escape($media['creation']); ?>"
					data-width="<?php echo $this->escape(isset($media['width']) ? $media['width'] : ''); ?>"
					data-height="<?php echo $this->escape(isset($media['height']) ? $media['height'] : ''); ?>"
					data-selected="<?php echo in_array($media['name'], $selected) ? 1 : 0; ?>"
				>
					<div class="media-preview">
						<div class="media-thumbnail">
							<div class="media-centered">
								<?php
								// render media by using model
								echo $model->renderMedia($media);
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>

		</div>
	</div>

	<div class="vap-media-sidebar">
		
		<div class="vap-media-droptarget" id="media-upload-box">
			<p class="icon">
				<i class="fas fa-upload" style="font-size: 48px;"></i>
			</p>

			<div class="lead">
				<a href="javascript:void(0)" id="media-upload-file"><?php echo JText::translate('VAPMANUALUPLOAD'); ?></a>&nbsp;<?php echo JText::translate('VAPMEDIADRAGDROP'); ?>
			</div>

			<p class="maxsize">
				<?php echo JText::sprintf('JGLOBAL_MAXIMUM_UPLOAD_SIZE_LIMIT', JHtml::fetch('vikappointments.maxuploadsize')); ?>
			</p>

			<input type="file" id="media-legacy-upload" multiple style="display: none;" />
		</div>

		<div class="vap-media-inspector">
			
			<div class="inspector-title">
				<span><?php echo JText::translate('VAPMANAGEMEDIATITLE1'); ?></span>
				<a href="javascript:void(0)" id="clear-media-selection">
					<i class="far fa-window-close"></i>
				</a>
			</div>

			<div class="selected-media-pool">

				<div class="media-info-box" data-name="">
					<div class="media-thumbnail media-thumbnail-image">
						<a href="#" target="_blank">
							<img src="" />
						</a>
					</div>

					<div class="media-details">
						<div class="filename"></div>
						<div class="uploaded"></div>
						<div class="file-size"></div>
						<div class="dimensions"></div>

						<a href="javascript:void(0)" class="delete-media-image" onclick="vapDeleteMediaFile(this);"><?php echo JText::translate('VAP_DELETE_PERMANENTLY'); ?></a>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

<?php
JText::script('VAP_DEF_N_SELECTED');
JText::script('VAP_DEF_N_SELECTED_1');
JText::script('VAP_DEF_N_SELECTED_0');
JText::script('VAPSYSTEMCONFIRMATIONMSG');
?>

<script type="text/javascript">

	if (!window.parent.hasOwnProperty('VAP_TMP_FILES')) {
		window.parent.VAP_TMP_FILES = [];
	}

	vapShowMediaInspector();

	jQuery(document).ready(function() {

		jQuery('.vap-media-block').on('click', vapHandleMediaClick);

		jQuery('#mediakeysearch').on('change', function() {

			var search = jQuery(this).val().toLowerCase();

			var at_least_one = false;

			jQuery('.vap-media-block').each(function() {

				if (!search.length || vapMatchingMediaBox(this, search)) {
					jQuery(this).show();
					at_least_one = true;
				} else {
					jQuery(this).hide();
				}

			});

			if (at_least_one) {
				jQuery('#no-media-results-alert').hide();
			} else {
				jQuery('#no-media-results-alert').show();
			}

		});

		jQuery('#clear-media-selection').on('click', function() {
			// clear all selected media
			jQuery('.vap-media-block[data-selected="1"]').trigger('click');
		});

	});

	function vapHandleMediaClick() {
		var checked = parseInt(jQuery(this).attr('data-selected')) ? 0 : 1;

		<?php
		if (!$multiple)
		{
			?>
			if (checked) {
				jQuery('.vap-media-block[data-selected="1"]').each(function() {
					jQuery(this).attr('data-selected', 0);
				});
			}

			window.parent.VAP_TMP_FILES = [];
			<?php
		}
		?>

		jQuery(this).attr('data-selected', checked);

		var file  = jQuery(this).data('name');
		var index = window.parent.VAP_TMP_FILES.indexOf(file);

		if (checked && index === -1) {
			// add file to list
			window.parent.VAP_TMP_FILES.push(file);
		}

		if (!checked && index !== -1) {
			// remove file from list
			window.parent.VAP_TMP_FILES.splice(index, 1);
		}

		if (window.parent.VAP_TMP_FILES.length) {
			vapShowMediaInspector();
		} else {
			vapHideMediaInspector();
		}
	}

	function vapMatchingMediaBox(box, search) {
		// search by media name
		var media_name = jQuery(box).data('name').trim().toLowerCase();

		if (media_name.indexOf(search) !== -1) {
			return true;
		}

		return false;
	}

	// inspector

	jQuery(document).ready(function() {
		/**
		 * Assign the transition animations 32 ms after the page loading.
		 * Hack for Safari, which seems to apply the transition also
		 * for the initial state of the inspector, causing an unwanted 
		 * animation when the page loads.
		 */
		setTimeout(function() {
			jQuery('.vap-media-inspector').css('transition', '0.4s ease-out all');
			jQuery('.vap-media-inspector').css('-moz-transition', '0.4s ease-out all');
			jQuery('.vap-media-inspector').css('-webkit-transition', '0.4s ease-out all');
		}, 32);
	});

	function vapShowMediaInspector() {
		var inspector = jQuery('.vap-media-inspector');
		if (!inspector.hasClass('slide-in')) {
			jQuery('.vap-media-inspector').addClass('slide-in');
		}

		// directly use the list of temporary file
		var list = window.parent.VAP_TMP_FILES;

		// filter the list and remove all the files that doesn't exist
		list = list.filter(function(file) {
			return jQuery('.vap-media-block[data-name="' + file + '"]').length ? true : false
		});

		// update reference as certain images might have been deleted
		window.parent.VAP_TMP_FILES = list;

		if (list.length == 0) {
			// hide media inspector because, even if the selection pool owned some files,
			// all them didn't exist and were removed to avoid unexpected behaviors
			vapHideMediaInspector();
		}

		<?php
		if ($multiple)
		{
			?>
			// remove all except for first media box
			jQuery('.vap-media-inspector .media-info-box:not(:first-child)').remove();

			for (var i = 0; i < list.length; i++) {
				if (i > 0) {
					var clone = jQuery('.vap-media-inspector .media-info-box').last().clone();
					jQuery('.selected-media-pool').append(clone);
				}

				vapFillInspectorMediaInfo(list[i]);
			}
			<?php
		}
		else
		{
			?>
			vapFillInspectorMediaInfo(list[0]);
			<?php
		}
		?>

		jQuery('.vap-media-inspector .media-info-box').show();
	}

	function vapFillInspectorMediaInfo(filename) {
		var media = jQuery('.vap-media-block[data-name="' + filename + '"]');

		var mediaUrl = media.find('*[src],*[data-src]');
		mediaUrl = mediaUrl.attr('src') || mediaUrl.data('src');

		var box = jQuery('.vap-media-inspector .media-info-box').last();

		box.attr('data-name', media.data('name'))
			.find('.media-thumbnail-image a')
				.attr('href', mediaUrl)
				.html(media.find('.media-centered').html());

		box.find('.media-details .filename').text(media.data('name'));
		box.find('.media-details .uploaded').text(media.data('date'));
		box.find('.media-details .file-size').text(media.data('size'));

		if (media.data('width')) {
			box.find('.media-details .dimensions').text(media.data('width') + 'x' + media.data('height') + ' pixel').show();
		} else {
			box.find('.media-details .dimensions').hide();
		}
	}

	function vapHideMediaInspector() {
		jQuery('.vap-media-inspector .media-info-box').hide();
		jQuery('.vap-media-inspector').removeClass('slide-in');
	}

	function vapDeleteMediaFile(link) {
		// double confirm
		var r = confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'));

		if (!r) {
			return false;
		}

		// find media name
		var media = jQuery(link).closest('.media-info-box').attr('data-name');

		// delete media
		UIAjax.do(
			'index.php?option=com_vikappointments&task=media.delete&ajax=1',
			{
				cid:  [media],
				path: '<?php echo base64_encode($this->path); ?>',
			}
		);

		// toggle media selection and then remove it
		jQuery('.vap-media-block[data-name="' + media + '"]').trigger('click').remove();
	}

	// files upload

	jQuery(document).ready(function() {

		var dragCounter = 0;

		// drag&drop actions on target div

		jQuery('#media-upload-box.vap-media-droptarget').on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
			e.preventDefault();
			e.stopPropagation();
		});

		jQuery('#media-upload-box.vap-media-droptarget').on('dragenter', function(e) {
			// increase the drag counter because we may
			// enter into a child element
			dragCounter++;

			jQuery(this).addClass('drag-enter');
		});

		jQuery('#media-upload-box.vap-media-droptarget').on('dragleave', function(e) {
			// decrease the drag counter to check if we 
			// left the main container
			dragCounter--;

			if (dragCounter <= 0) {
				jQuery(this).removeClass('drag-enter');
			}
		});

		jQuery('#media-upload-box.vap-media-droptarget').on('drop', function(e) {

			jQuery(this).removeClass('drag-enter');
			
			var files = e.originalEvent.dataTransfer.files;
			
			vapDispatchMediaUploads(files);
			
		});

		jQuery('#media-upload-box.vap-media-droptarget #media-upload-file').on('click', function() {
			jQuery('input#media-legacy-upload').val(null).trigger('click');
		});

		jQuery('input#media-legacy-upload').on('change', function() {
			vapDispatchMediaUploads(jQuery(this)[0].files);
		});

	});
	
	// upload
	
	function vapDispatchMediaUploads(files) {
		for (var i = 0; i < files.length; i++) {
			var status = new UploadingMediaFile();
			status.setFileName(files[i].name);
			status.setProgress(0);
			
			jQuery('.vap-media-gallery').prepend(status.getHtml());
			
			vapMediaFileUploadThread(status, files[i]);
		}
	}
	
	function UploadingMediaFile() {
		// create parent
		this.fileBlock = jQuery('<div class="vap-media-block" data-name="" data-selected="0"></div>');

		// create media thumbnail
		this.fileThumb = jQuery('<div class="media-preview"><div class="media-thumbnail"><div class="media-centered"><i class="fas fa-file-image"></i></div></div></div>').appendTo(this.fileBlock);
	 
		this.setFileName = function(name) {
			this.fileBlock.attr('data-name', name);
		}
		
		this.setProgress = function(progress) {       
			var opacity = parseFloat(progress / 100);

			this.fileThumb.find('i.fas').css('opacity', opacity);
		}
		
		this.complete = function(file) {
			this.setProgress(100);

			this.fileBlock.attr('data-name', file.name);
			this.fileBlock.attr('data-ext', file.file_ext);
			this.fileBlock.attr('data-size', file.size);
			this.fileBlock.attr('data-date', file.creation);
			this.fileBlock.attr('data-width', file.width);
			this.fileBlock.attr('data-height', file.height);

			// append file preview
			this.fileThumb.find('.media-centered').html(file.html);
		}
		
		this.getHtml = function() {
			return this.fileBlock;
		}
	}
	
	function vapMediaFileUploadThread(status, file) {
		jQuery.noConflict();
		
		var formData = new FormData();
		formData.append('file', file);
		formData.append('path', '<?php echo base64_encode($this->path); ?>');

		UIAjax.upload(
			// end-point URL
			'index.php?option=com_vikappointments&task=media.dropupload',
			// file post data
			formData,
			// success callback
			function(resp) {
				// finalize upload
				status.complete(resp);

				// register click event and trigger it for media auto-selection
				jQuery(status.fileBlock).on('click', vapHandleMediaClick).trigger('click');

				// refresh search after uploading the image
				jQuery('#mediakeysearch').trigger('change');
			},
			// failure callback
			function(error) {
				// raise alert
				alert(error.responseText);

				// remove file from gallery
				jQuery('.vap-media-block[data-name="' + file.name + '"]').remove();
			},
			// progress callback
			function(progress) {
				// update progress
				status.setProgress(progress);
			}
		);
	}

	<?php
	/**
	 * Display a toast message to inform the administrator
	 * that it is recommended to set up the media configuration
	 * before uploading the images for the first time.
	 */
	if ($this->firstConfig)
	{
		?>
		jQuery(document).ready(function() {
			// display toast message with a delay of 256 ms
			setTimeout(function() {
				ToastMessage.dispatch({
					text: Joomla.JText._('VAPMEDIAFIRSTCONFIG'),
					status: 2,
					delay: 15000,
					action: function() {
						// open new media view in a blank page
						window.open('index.php?option=com_vikappointments&task=media.add&configure=1', '_blank');
					},
				});
			}, 256);
		});
		<?php
	}
	?>

</script>
