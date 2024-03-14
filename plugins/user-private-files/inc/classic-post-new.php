<?php
/*
* Shortcode to display new post form
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

add_shortcode('upf_upload', 'upvf_upload_frm');
if (!function_exists('upvf_upload_frm')) {
	function upvf_upload_frm($atts){
		if(is_user_logged_in()){
			
			wp_enqueue_style('upf-classic-style');
			wp_enqueue_script('upf-classic-script');

			$extensions = array();
			$wp_allowed_files = get_allowed_mime_types();
			foreach($wp_allowed_files as $ext => $type){
				$ext = str_replace("|", ", .", $ext);
				$extensions[] = '.' . $ext;
			}
			$exts = implode(', ', $extensions);
			
			$html = '<div id="upf_upload_sec">
				
				<div class="uploader">
					<div class="upload-area" id="upload_doc_file">
						<span>'.__("Drag and drop a file you want to upload", "user-private-files").'</span>
					</div>
					<input type="file" name="upload_doc" id="upload_doc" class="upvf-hidden" accept="' . $exts . '" />
					<button>'.__("Choose file", "user-private-files").'</button>
				</div>
				
				<div class="upvf-popup upvf-hidden add-doc-pp">
					<div class="upf_inner">
						<span class="closePopup">X</span>
						<form id="add-doc-frm">
							<fieldset>
								<div class="uploaded-doc">
									<div class="doc_prvw">
										<img class="doc_prvw_img" src="">
										<p class="doc_prvw_txt"></p>
									</div>
									<div class="fill-dets">
										<input type="text" name="doc_ttl" id="doc_ttl" value="" placeholder="'.__("Title", "user-private-files").'">
										<input name="doc_desc" id="doc_desc" value="" placeholder="'.__("Note", "user-private-files").'">';
										
									$html .= apply_filters( "upvf_file_upload_extra_fields", '', '' );
										
									$html .= '</div>
									<input type="submit" value="'.__("Upload", "user-private-files").'" id="submitFile">
								</div>
							</fieldset>
						</form>
						<div class="progress_bar"></div>
					</div>
				</div>
				
			</div>';
			
			return $html;
		} else{
			return '<p class="error">'.__("You must be logged in to upload/manage the files", "user-private-files").'</p>';
		}
	}
}