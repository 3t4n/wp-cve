<?php
/*
* Handle display of private files
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

global $upf_plugin_url;
global $upvf_template_loader;

$user = wp_get_current_user();
$roles = ( array ) $user->roles;
$uploading_allwd = true;

if($uploading_allwd){
	// Get root folders
	$args = array(
		'post_type'		=> 'upf_folder',
		'post_status'	=> 'publish',
		'author'		=> get_current_user_id(),
		'posts_per_page' => -1, 
		'meta_query' 	=> array(
			array(
				'key'     => 'upf_parent_fldr',
				'value'   => '',
				'compare' => 'NOT EXISTS'
			)
		)
	);
	$folders = get_posts($args);
}

// Root Folders shared with the User
$user_id = get_current_user_id();
$shared_args = array(
	'post_type' => 'upf_folder',
	'post_status' => 'publish',
	'posts_per_page' => -1, 
	'meta_query' => array(
		array(
			'key' => 'upf_allowed',
			'value' => serialize(strval($user_id)),
			'compare' => 'LIKE',
		),
	)
);
$shared_folders = get_posts($shared_args);

?>
<div id="upfp_container" class="upfp_row">
	<!-- NAVBAR SECTION--------------------------------------------------->
	<div id="upfp_nav" class="upfp_col">
		<?php if($uploading_allwd){ ?>
			<h4><?php echo __("Dashboard", "user-private-files"); ?></h4>
			
			<ul class="upfp_nav_dash">
				<li data-folder-id="all-files" data-folder-name="" class="upfp_li_active upfp_fldr_obj">
					<a id="upfp_home_link" class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-home"></i> <span><?php echo __("All Files", "user-private-files"); ?></span></a>
				</li>
			</ul>
			
			<ul class="upfp_nav_list my_folders">
				
				<?php foreach($folders as $folder){ ?>
					<li id="upfp_nav_fldr_<?php echo $folder->ID; ?>" data-folder-id="<?php echo $folder->ID; ?>" data-folder-name="<?php echo $folder->post_title; ?>" class="upfp_fldr_obj">
						<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i> <span> <?php echo $folder->post_title; ?></span></a>
					</li>
				<?php } ?>
				
			</ul>
			
		<?php } ?>
		
		<h4><?php echo __("Shared with me", "user-private-files"); ?></h4>
		<ul class="upfp_nav_list shared_fldrs">
			<li data-folder-id="all-shared-files" data-folder-name="<?php echo __("Shared", "user-private-files"); ?>" data-share="true" class="<?php if(!$uploading_allwd){ ?> upfp_li_active <?php } ?> upfp_fldr_obj">
				<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i> <span> <?php echo __("All Files", "user-private-files"); ?></span></a>
			</li>
			<?php 
			$sf_array = array();
			// select only top level folders
			foreach( $shared_folders as $sf){
				$sf_id = $sf->ID;
				
				$is_shared = false;
				$parent_folder = get_post_meta($sf->ID, 'upf_parent_fldr', true);
				$alwd_users = get_post_meta($parent_folder, 'upf_allowed', true);
				if($parent_folder && $alwd_users){
					$is_shared = in_array($user_id, $alwd_users);
				}
				while($parent_folder && $is_shared){
					$sf_id = (int)$parent_folder;
					$parent_folder = get_post_meta($parent_folder, 'upf_parent_fldr', true);
					$alwd_users = get_post_meta($parent_folder, 'upf_allowed', true);
					if($parent_folder && $alwd_users){
						$is_shared = in_array($user_id, $alwd_users);
					}
				}
				
				$sf_array[] = $sf_id;
				
			}
			
			$sf_array = array_unique($sf_array);
			foreach($sf_array as $sf_id){
				$sf_name = get_the_title($sf_id);
			?>
				<li id="upfp_nav_fldr_<?php echo $sf_id; ?>" data-folder-id="<?php echo $sf_id; ?>" data-folder-name="<?php echo $sf_name; ?>" data-share="true" class="upfp_fldr_obj">
					<a class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-folder"></i> <span> <?php echo $sf_name; ?></span></a>
				</li>
			<?php } ?>
			
		</ul>
		
		<ul class="upfp_nav_trash">
			<li data-folder-id="trash-files" data-folder-name="<?php echo __("Trash", "user-private-files"); ?>" class="upfp_fldr_obj">
				<a id="upfp_trash_link" class="upfp_foldr" href="javascript:void(0);"><i class="fas fa-trash"></i> <span><?php echo __("Trash", "user-private-files"); ?></span></a>
			</li>
		</ul>
		
	</div>
<!-- FILES SECTION--------------------------------------------------->
	<div id="upfp_file" class="upfp_col">
		<div class="upfp_banner">
			
			<a href="javascript:void(0);" id="upfp_upload_btn" class="<?php if(!$uploading_allwd){ echo 'upfp-hidden'; } ?>"><i class="fas fa-cloud-upload-alt"></i> <?php echo __("Upload Files", "user-private-files"); ?></a>
			<a href="javascript:void(0);" id="upfp_newfolder_btn" class="<?php if(!$uploading_allwd){ echo 'upfp-hidden'; } ?>"><i class="fas fa-folder-plus"></i> <?php echo __("New Folder", "user-private-files"); ?></a>
			
			<div class="upfp_search_bar">
				<form id="top_search_frm">
					<input id="upfp_search_box" type="text" placeholder="<?php echo __('Search Files', 'user-private-files'); ?>">
					<input type="submit" value="<?php echo __('Search', 'user-private-files'); ?>">
				</form>
			</div>
			
		</div>
		<div class="upfp_folder_banner">
			<div class="upfp_parmalink">
				<?php if($uploading_allwd){ ?>
					<a id="upfp_pl_home" href="javascript:void(0);"><?php echo __("Home", "user-private-files"); ?></a><span></span><a id="upfp_bc_folder" href="javascript:void(0);"></a>
				<?php } else{ ?>
					<a id="upfp_bc_folder" href="javascript:void(0);"> <span id="bc_fldr_id_all-shared-files" data-share="true"><?php echo __("Shared", "user-private-files"); ?></span></a>
				<?php } ?>
			</div>
			<div class="upfp_folder_tool">
					<a href="javascript:void(0);" class="file_btns upfp-hidden" id="upfp_move_btn"><i class="fas fa-folder-open"></i> <?php echo __("Move To", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="file_btns upfp-hidden" id="upfp_share_btn"><i class="fas fa-share-square"></i> <?php echo __("Share", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="file_btns upfp-hidden" id="upfp_rename_btn"><i class="fas fa-edit"></i> <?php echo __("Rename", "user-private-files"); ?></a>
					
					<a href="javascript:void(0);" class="file_btns upfp-hidden" id="upfp_download_btn"><i class="fas fa-file-download"></i> <?php echo __("Download", "user-private-files"); ?></a>
					
				<?php // if($uploading_allwd){ ?>
					<a href="javascript:void(0);" class="file_btns upfp-hidden" id="upfp_delete_btn"><i class="fas fa-trash"></i> <?php echo __("Delete", "user-private-files"); ?></a>
					
					<a href="javascript:void(0);" class="folder_btns <?php if(!$uploading_allwd){ echo 'upfp-hidden'; } ?>" id="upfp_bulk_slct_fldr_btn"><i class="far fa-check-square"></i> <?php echo __("Bulk Select", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="bulk_act_btns upfp-hidden" id="upfp_bulk_slct_all"><i class="fas fa-check-square"></i> <?php echo __("Select All", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="bulk_act_btns upfp-hidden" id="upfp_bulk_move_btn"><i class="fas fa-folder-open"></i> <?php echo __("Move To", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="bulk_act_btns upfp-hidden" id="upfp_bulk_dlt_btn"><i class="fas fa-trash"></i> <?php echo __("Delete", "user-private-files"); ?></a>
					
					<a href="javascript:void(0);" class="bulk_act_trash_btns upfp-hidden" id="upfp_bulk_rstr_btn"><i class="fas fa-folder-open"></i> <?php echo __("Restore", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="bulk_act_trash_btns upfp-hidden" id="upfp_bulk_dlt_permnt_btn"><i class="fas fa-trash"></i> <?php echo __("Permanent Delete", "user-private-files"); ?></a>
					
					<a href="javascript:void(0);" class="folder_btns upfp-hidden" id="upfp_share_fldr_btn"><i class="fas fa-share-square"></i> <?php echo __("Share", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="folder_btns upfp-hidden" id="upfp_rnm_fldr_btn"><i class="fas fa-edit"></i> <?php echo __("Rename", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="folder_btns upfp-hidden" id="upfp_dlt_fldr_btn"><i class="fas fa-trash"></i> <?php echo __("Delete", "user-private-files"); ?></a>
				<?php // } ?>
				
				<form class="swm_fltr_frm <?php if($uploading_allwd){ ?> upfp-hidden <?php } ?>" id="swm_fltr_frm">
					<input type="text" id="upfp_swm_fltr_box" placeholder="<?php echo __('Filter by user email', 'user-private-files'); ?>">
					<input type="submit" value="<?php echo __('Filter', 'user-private-files'); ?>">
					<button href="javascript:void(0);" id="upfp_reset_fltr"><?php echo __('Reset', 'user-private-files'); ?></button>
				</form>
				
				<?php // if($uploading_allwd){ ?>
					<a href="javascript:void(0);" class="trash_folder_btns upfp-hidden" id="upfp_restore_fldr_btn"><i class="fas fa-trash-restore"></i> <?php echo __("Restore Folder", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="trash_folder_btns upfp-hidden" id="upfp_trash_dlt_fldr_btn"><i class="fas fa-trash"></i> <?php echo __("Permanent Delete", "user-private-files"); ?></a>
					
					<a href="javascript:void(0);" class="trash_file_btns upfp-hidden" id="upfp_restore_file_btn"><i class="fas fa-trash-restore"></i> <?php echo __("Restore File", "user-private-files"); ?></a>
					<a href="javascript:void(0);" class="trash_file_btns upfp-hidden" id="upfp_trash_dlt_file_btn"><i class="fas fa-trash"></i> <?php echo __("Permanent Delete", "user-private-files"); ?></a>
					
					<a href="javascript:void(0);" class="trash_action_btns upfp-hidden" id="upfp_trash_empty_btn"><i class="fas fa-trash"></i> <?php echo __("Empty Trash", "user-private-files"); ?></a>
				<?php // } ?>
				
			</div>
		</div>
		<div class="upfp_content_wrapper">
			
			<div id="preloader_sec" class="upfp_pre_grid">
			<?php for ($i=0; $i<20; $i++){ ?>
				<div class="upfp_pre_card">
					<div class="upfp_pre_header">
						<img class="upfp_pre_header-img upfp_pre_skeleton" />
					</div>
					<div>
						<div class="upfp_pre_skeleton upfp_pre_skeleton-text"></div>
						<div class="upfp_pre_skeleton upfp_pre_skeleton-text"></div>
					</div>
				</div>
			<?php } ?>
			</div>

			<div class="upfp_content" data-current-folder="<?php echo ($uploading_allwd ? 'all-files' : 'all-shared-files'); ?>" style="display: none;">
				<?php
					if($uploading_allwd){
						$data = array( 'folder_id' => 'all-files', 'folder_status' => '' );
						$upvf_template_loader->set_template_data( $data )->get_template_part( 'files' );
					} else{
						$data = array( 'folder_id' => 'all-shared-files', 'fltr_email' => '' );
						$upvf_template_loader->set_template_data( $data )->get_template_part( 'files-shared' );
					}
				?>
			</div>
			<?php
				$upvf_template_loader->get_template_part( 'file-preview' );
			?>
		</div>
	</div>
	<?php
	/* Load right sidebar (INFO SECTION) template */
	$upvf_template_loader->get_template_part( 'right-sidebar' );
	
	/* Load add new file template */
	$upvf_template_loader->get_template_part( 'post-new' );
	
	/* Load file popups template */
	$upvf_template_loader->get_template_part( 'file-popups' );
	
	/* Load share folder template */
	$upvf_template_loader->get_template_part( 'share-folder' );
	
	/* Load folder popups template */
	$upvf_template_loader->get_template_part( 'folder-popups' );
	
	?>
</div>
