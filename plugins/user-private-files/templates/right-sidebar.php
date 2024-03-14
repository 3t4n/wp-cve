<?php
/*
* Load & Display right sidebar which contains user, file, folder info
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

global $upf_plugin_url;
$user_id = get_current_user_id();
?>
<div id="upfp_info" class="upfp_col">
	<div class="upfp_user_info">
		<div class="upfp_user_info_panel">
			<h4><?php echo esc_html( get_the_author_meta( 'nickname', $user_id ) ); ?></h4>
			<div class="upfp_user_info_tools">
				<a href="<?php echo wp_logout_url( home_url()); ?>"><i class="fas fa-sign-out-alt"></i></a>
			</div>
		</div>
		<div class="upfp_user_info_avtar">
		
			<?php echo get_avatar($user_id, 64); ?>
			
		<!--	<img src="<?php echo $upf_plugin_url . 'images/user-avtar.png'; ?>"> -->
			
		</div>
	</div>
	<div class="upfp_storage_info">
		<p><?php echo esc_html( get_the_author_meta( 'user_email', $user_id ) ); ?></p>
	</div>
	
	<div class="upfp_file_info upfp-hidden">
		
		<h4><?php echo __("Document Name", "user-private-files"); ?> <a id="pencil_doc_name" href="javascript: void(0);"><i class="fas fa-pencil-alt"></i></a></h4>
		<p id="edit_doc_ttl"></p>
		
		<h4><?php echo __("Description", "user-private-files"); ?> <a id="pencil_doc_desc" href="javascript: void(0);"><i class="fas fa-pencil-alt"></i></a></h4>
		<p id="edit_doc_desc"></p>
		
		<h4 id="file-access-hdng"><?php echo __("Who has access", "user-private-files"); ?> <a id="add_doc_user" href="javascript: void(0);"><i class="fas fa-user-plus"></i></a></h4>
		<ul class="upfp_file_access_list"></ul>
		
		<h4 id="file-shared-by"><?php echo __("Uploaded By", "user-private-files"); ?></h4>
		<p class="upfp_file_shared_by"></p>
		
		<h4 id="file-comments-hdng"><?php echo __("Comments", "user-private-files"); ?></h4>
		<form id="upfp_file_cmnt_frm"><textarea id="upfp_file_new_cmnt"></textarea><input type="submit" value="<?php echo __('Add', 'user-private-files'); ?>"></form>
		<div class="upfp_file_comments"></div>
		
	</div>
	
	<div class="upfp_folder_info upfp-hidden">
		
		<h4><?php echo __("Folder Name", "user-private-files"); ?> <a id="pencil_fldr_name" href="javascript: void(0);"><i class="fas fa-pencil-alt"></i></a></h4>
		<p id="folder_name"></p>
		
		<h4 id="access-hdng"><?php echo __("Who has access", "user-private-files"); ?> <a id="add_fldr_user" href="javascript: void(0);"><i class="fas fa-user-plus"></i></a></h4>
		<ul class="upfp_folder_access_list"></ul>
		
		<h4 id="shared-by" class="upfp-hidden"><?php echo __("Created By", "user-private-files"); ?></h4>
		<p class="upfp_folder_shared_by"></p>
		
	</div>
	
</div>
