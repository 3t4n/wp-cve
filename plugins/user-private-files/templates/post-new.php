<?php
/*
* Template file for uploading new file form
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

?>
<div class="upfp-popup upfp-hidden add-doc-pp">

	<div id="upf_upload_sec">
		<div class="upf_inner">
			
			<span class="closePopup">X</span>
			
			<div class="uploader">
				<input type="file" name="upload_doc" id="upload_doc" class="upfp-hidden" multiple accept=".doc, .docx, application/pdf, image/*, .zip, video/*, audio/*, text/plain, .csv" />
				<button><?php echo __("Choose files", "user-private-files"); ?></button>
			</div>
			
			<div class="upfp_upload_error"></div>
			<div class="upfp_uploaded-sec"></div>
			
			<div class="progress_bar"></div>
			
		</div>
	</div>

</div>
