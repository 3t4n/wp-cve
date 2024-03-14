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

?>

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

<script>

	// files upload

	jQuery(function($) {

		var dragCounter = 0;

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
			
			vapDispatchMediaUploads(files);
			
		});

		$('.vap-media-droptarget #upload-file').on('click', function() {
			// unset selected files before showing the dialog
			$('input#legacy-upload').val(null).trigger('click');
		});

		$('input#legacy-upload').on('change', function() {
			// execute AJAX uploads after selecting the files
			vapDispatchMediaUploads($(this)[0].files);
		});

	});
	
	// upload
	
	function vapDispatchMediaUploads(files) {
		var up_cont = jQuery('#vap-uploads-cont');

		for (var i = 0; i < files.length; i++) {
			if (files[i].name.match(/\.(png|jpe?g|gif|bmp)$/)) {
				// show "uploads" section only in case there is
				// at least a supported file
				jQuery('#vap-uploads').show();

				var status = new createStatusBar();
				status.setFileNameSize(files[i].name, files[i].size);
				status.setProgress(0);
				up_cont.append(status.getHtml());
				
				vapMediaFileUploadThread(status, files[i]);
			} else {
				alert('File [' + files[i].name + '] not supported');
			}
		}
	}
	
	var fileCount = 0;
	function createStatusBar() {
		fileCount++;
		this.statusbar = jQuery("<div class='vap-progressbar-status'></div>");
		this.filename = jQuery("<div class='vap-progressbar-filename'></div>").appendTo(this.statusbar);
		this.size = jQuery("<div class='vap-progressbar-filesize hidden-phone'></div>").appendTo(this.statusbar);
		this.progressBar = jQuery("<div class='vap-progressbar'><div></div></div>").appendTo(this.statusbar);
		this.abort = jQuery("<div class='vap-progressbar-abort hidden-phone'>Abort</div>").appendTo(this.statusbar);
		this.statusinfo = jQuery("<div class='vap-progressbar-info hidden-phone' style='display:none;'><?php echo addslashes(JText::translate('VAPMANAGEMEDIA11')); ?></div>").appendTo(this.statusbar);
		this.completed = false;
	 
		this.setFileNameSize = function(name, size) {
			var sizeStr = "";
			if(size > 1024*1024) {
				var sizeMB = size/(1024*1024);
				sizeStr = sizeMB.toFixed(2)+" MB";
			} else if(size > 1024) {
				var sizeKB = size/1024;
				sizeStr = sizeKB.toFixed(2)+" kB";
			} else {
				sizeStr = size.toFixed(2)+" B";
			}
	 
			this.filename.html(name);
			this.size.html(sizeStr);
		}
		
		this.setProgress = function(progress) {       
			var progressBarWidth = progress*this.progressBar.width()/100;  
			this.progressBar.find('div').css('width', progressBarWidth+'px').html(progress + "% ");
			if(parseInt(progress) >= 100) {
				if( !this.completed ) {
					this.abort.hide();
					this.statusinfo.show();
				}
			}
		}
		
		this.complete = function() {
			this.completed = true;
			this.abort.hide();
			this.statusinfo.hide();
			this.setProgress(100);
			this.progressBar.find('div').addClass('completed');
		}
		
		this.setAbort = function(jqxhr) {
			var bar = this.progressBar;
			this.abort.click(function() {
				jqxhr.abort();
				this.hide();
				bar.find('div').addClass('aborted');
			});
		}
		
		this.getHtml = function() {
			return this.statusbar;
		}
	}

	function vapMediaFileUploadThread(status, file) {
		jQuery.noConflict();
		
		var formData = new FormData();
		formData.append('file', file);
		formData.append('isresize', jQuery('input[name="isresize"]:checked').val());
		formData.append('oriwres', jQuery('input[name="oriwres"]').val());
		formData.append('orihres', jQuery('input[name="orihres"]').val());
		formData.append('smallwres', jQuery('input[name="smallwres"]').val());
		formData.append('smallhres', jQuery('input[name="smallhres"]').val());

		var xhr = UIAjax.upload(
			// end-point URL
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=media.dropupload'); ?>',
			// file post data
			formData,
			// success callback
			function(resp) {
				if (resp) {
					status.complete();
					status.filename.html(resp.name);
				} else {
					status.progressBar.find('div').addClass('aborted');
				}
			},
			// failure callback
			function(error) {
				status.progressBar.find('div').addClass('aborted');
			},
			// progress callback
			function(progress) {
				// update progress
				status.setProgress(progress);
			}
		);

		status.setAbort(xhr);
	}
	
</script>
