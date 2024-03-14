<?php
/*
* Handle display of private files
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

add_shortcode('upf_display', 'upvf_classic_display_prvt_files');
if (!function_exists('upvf_classic_display_prvt_files')) {
	function upvf_classic_display_prvt_files($atts){
		if( is_user_logged_in() && !is_admin()){
			
			wp_enqueue_style('upf-classic-style');
			wp_enqueue_script('upf-classic-script');
			
			global $upf_plugin_url;
			$doc_prvw_img = $upf_plugin_url . 'images/document.png';
			$user_id = get_current_user_id();
			$all_docs_ids = array();
			$the_query = new WP_Query( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'author' => $user_id, 'meta_key' => 'upf_doc', 'meta_value' => 'true', 'posts_per_page' => -1 ) ); 
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$all_docs_ids[] = get_the_ID();
				}
			}
			wp_reset_query();
			
			$all_docs_ids = apply_filters( "upvf_all_docs", $all_docs_ids, $all_docs_ids );
			
			$grp_by = 0;
			$grp_by_checked = '';
			if( isset($_GET['grp_by']) && isset($_GET['upf_grp_nonce']) ){
				if( wp_verify_nonce($_GET['upf_grp_nonce'], 'upf_clsc_check_grp') ){
					$grp_by = sanitize_text_field($_GET['grp_by']);
					$grp_by_checked = 'checked';
				}
			}
			
			$doc_item_html = $grp_img_html = $grp_doc_html = '';
			foreach($all_docs_ids as $doc_id){
				$doc_ttl = get_the_title($doc_id);
				$doc_src = wp_get_attachment_url($doc_id);
				$doc_desc = get_post_field('post_content', $doc_id);
				$alwd_emails = array();
				$ca_users_str = '';
				$curr_allowed_users = get_post_meta($doc_id, 'upf_allowed', true);
				if($curr_allowed_users){
					foreach($curr_allowed_users as $alwd_usr){
						$alwd_usr_obj = get_userdata( $alwd_usr );
						if($alwd_usr_obj){ $alwd_emails[] = $alwd_usr . ':' . $alwd_usr_obj->user_email; }
					}
					$ca_users_str = implode(',', $alwd_emails);
				}
				
				$mime_type = get_post_mime_type($doc_id);
				
				if($grp_by){ // group together same type of files
					if (strpos($mime_type, 'image') !== false) {
						$grp_img_html .= '<div id="doc_'.$doc_id.'" class="doc-item" data-alwd-usrs="'.$ca_users_str.'" doc_type="'.$mime_type.'">';
						$grp_img_thumb = wp_get_attachment_image_src($doc_id, 'thumbnail');
						$grp_img_html .= '<a class="edit-doc" href="javascript:void(0);"><img data-type="img" data-src="'.$doc_src.'" src="'.$grp_img_thumb[0].'"></a>';
						$grp_img_html .= '<p class="doc_ttl">'.$doc_ttl.'</p>';
						$grp_img_html .= '<p class="doc_desc upvf-hidden">'.$doc_desc.'</p></div>';
					} else{
						$grp_doc_html .= '<div id="doc_'.$doc_id.'" class="doc-item" data-alwd-usrs="'.$ca_users_str.'" doc_type="'.$mime_type.'">';
						$grp_doc_html .= '<a class="edit-doc" href="javascript:void(0);"><img data-src="'.$doc_src.'" src="'.$doc_prvw_img.'"></a>';
						$grp_doc_html .= '<p class="doc_ttl">'.$doc_ttl.'</p>';
						$grp_doc_html .= '<p class="doc_desc upvf-hidden">'.$doc_desc.'</p></div>';
					}
				} else{ // No grouping
					$doc_item_html .= '<div id="doc_'.$doc_id.'" class="doc-item" data-alwd-usrs="'.$ca_users_str.'" doc_type="'.$mime_type.'">';
					if (strpos($mime_type, 'image') !== false) {
						$doc_thumb = wp_get_attachment_image_src($doc_id, 'thumbnail');
						$doc_item_html .= '<a class="edit-doc" href="javascript:void(0);">
								<img data-type="img" data-src="'.$doc_src.'" src="'.$doc_thumb[0].'"></a>';
					} else{
						$doc_item_html .= '<a class="edit-doc" href="javascript:void(0);"><img data-src="'.$doc_src.'" src="'.$doc_prvw_img.'"></a>';
					}
					$doc_item_html .= '<p class="doc_ttl">'.$doc_ttl.'</p>';
					$doc_item_html .= '<p class="doc_desc upvf-hidden">'.$doc_desc.'</p></div>';
				}
			}
			
			$my_files_hdng = __("My Files", "user-private-files");
			$my_files_hdng = apply_filters( "upvf_my_files_hdng", $my_files_hdng, $my_files_hdng );
			
			$render_html = '<div class="upf_files_sec">
				<div class="filter_box"><h3>'.$my_files_hdng.'</h3>
					<div class="switch_btn"> <span>'.__("Group by File Type", "user-private-files").'</span>
					<form id="files_filter" method="GET">
						<input type="checkbox" name="grp_by" id="grp_by" '.$grp_by_checked.'>
						' . wp_nonce_field( 'upf_clsc_check_grp', 'upf_grp_nonce', false, false ) . '
					</form>
				</div></div>';
				
				$render_html .= apply_filters( "upvf_before_files", '', $grp_by );
				
				if($grp_by){
					$render_html .= '<div class="all-docs docs-list">'.$grp_img_html.$grp_doc_html.'</div>';
				} else{
					$render_html .= '<div class="all-docs docs-list">'.$doc_item_html.'</div>';
				}
				
				$render_html .= '<div class="edit_doc_upf_popup upvf-popup upvf-hidden"><div class="upf_doc_inner">
					<span class="closePopup">X</span>
					<div class="doc_view"></div>
					<div class="doc_tool">';
					
				$render_html .= apply_filters("upvf_before_doc_detail", '', '' );
					
				$render_html .= '<h5>'.__("Document Name", "user-private-files").'</h5>
						<p id="edit_doc_ttl"></p>
						<h5>'.__("Description", "user-private-files").'</h5>
						<p id="edit_doc_desc"></p>
						<div class="doc_curr_alwd_users"></div>
						<form id="upf_allow_access_frm">
							<h5>'.__("Share with others", "user-private-files").'</h5>
							<input type="email" placeholder="'.__("Email Address", "user-private-files").'" name="allowed_usr_mail" id="allowed_usr_mail" required>
							<input type="submit" value="'.__("Allow Access", "user-private-files").'" name="upf_allowed_usr_sbmt" id="upf_allowed_usr_sbmt">
						</form>
						<button id="dlt-doc-file">'.__("Delete File", "user-private-files").'</button>
					</div>
				</div></div>
				
			</div>';
			
			// Files shared with the User
			$args = array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'upf_doc',
						'value' => 'true',
						'compare' => 'LIKE'
					),
					array(
						'key' => 'upf_allowed',
						'value' => serialize(strval($user_id)),
						'compare' => 'LIKE',
					),
				)
			);
			
			$all_shared_docs = array();
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$all_shared_docs[] = get_the_ID();
				}
			}
			wp_reset_query();
			
			if($all_shared_docs){
				$render_html .= '<h3 style="padding: 0 25px;">'.__("Shared with me", "user-private-files").'</h3>';
				$swm_item_html = '';
				foreach($all_shared_docs as $swm_doc){
					$swm_doc_ttl = get_the_title($swm_doc);
					$swm_doc_src = wp_get_attachment_url($swm_doc);
					$mime_type = get_post_mime_type($swm_doc);
					$swm_item_html .= '<div id="swm_doc_'.$swm_doc.'" class="swm-doc-item" doc_type="'.$mime_type.'">';
					if (strpos($mime_type, 'image') !== false) {
						$doc_thumb = wp_get_attachment_image_src($swm_doc, 'thumbnail');
						$swm_item_html .= '<a href="'.$swm_doc_src.'" target="_blank">
								<img class="full-width" data-src="'.$swm_doc_src.'" src="'.$doc_thumb[0].'"></a>';
					} else{
						$swm_item_html .= '<a href="'.$swm_doc_src.'" target="_blank"><img src="'.$doc_prvw_img.'"></a>';
					}
					$doc_author = get_post_field ('post_author', $swm_doc);
					$user_obj = get_userdata( $doc_author );
					$swm_item_html .= '<p class="doc_ttl">'.$swm_doc_ttl.'</br><span>'.__("Shared By", "user-private-files").' '.$user_obj->user_login.'</span></p></div>';
				}
				$render_html .= '<div class="swm_sec"><div class="swm_all_items">';
				$render_html .= $swm_item_html;
				$render_html .= '</div></div>';
			}
			
			return $render_html;
		}
	}
}
