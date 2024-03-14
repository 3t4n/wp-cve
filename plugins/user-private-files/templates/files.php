<?php
/*
* Load & Display Private sub folders & files
*/

// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

global $upf_plugin_url;
$file_prvw_img = $upf_plugin_url . 'images/File_thumbnail.png';
$doc_prvw_img = $upf_plugin_url . 'images/Doc_thumbnail.png';
$pdf_prvw_img = $upf_plugin_url . 'images/PDF_thumbnail.png';
$vdo_prvw_img = $upf_plugin_url . 'images/Video_thumbnail.png';
$zip_prvw_img = $upf_plugin_url . 'images/Zip_thumbnail.png';
$folder_prvw_img = $upf_plugin_url . 'images/folder-150.png';

$user_id = get_current_user_id();
$folder_id = $data->folder_id;
$folder_status = $data->folder_status;

$inside_trash = 0;

// Get sub-folders
if( $folder_id == 'trash-files' || $folder_status == 'trash' ){ // from trash
	$post_status = 'trash';
} else{
	$post_status = 'publish';
}

if($folder_id == 'all-files'){ // Get all folders for home
	$args = array(
		'post_type'		=> 'upf_folder',
		'post_status'	=> $post_status,
		'author'		=> $user_id,
		'posts_per_page' => -1, 
		'meta_query' 	=> array(
							array(
								'key'     => 'upf_parent_fldr',
								'value'   => '',
								'compare' => 'NOT EXISTS'
							)
						)
	);
	
} else if($folder_id == 'trash-files'){ // Get all folders for main trash page
	$args = array(
		'post_type'		=> 'upf_folder',
		'post_status'	=> $post_status,
		'author'		=> $user_id,
		'posts_per_page' => -1
	);
} else{
	$args = array(
		'post_type'		=> 'upf_folder',
		'post_status'	=> $post_status,
		'posts_per_page' => -1, 
		'meta_query' 	=> array(
							array(
								'key'     => 'upf_parent_fldr',
								'value'   => $folder_id
							)
						)
	);
}

$folders = get_posts($args);

// Get files inside folders
if( $folder_id == 'trash-files' || $folder_status == 'trash' ){ // from trash
	$file_post_status = 'trash';
	$inside_trash = 1;
} else{
	$file_post_status = 'inherit';
}

if( $folder_id == 'all-files' ){ // for home page
	$the_query = new WP_Query( array( 
				'post_type' => 'attachment',
				'post_status' => $file_post_status,
				'author' => $user_id,
				'meta_query' => array(
					array(
						'key'     => 'upf_doc',
						'value'   => 'true',
					),
					array(
						'key'     => 'upf_foldr_id',
						'value'   => '',
						'compare' => 'NOT EXISTS'
					)
				),
				'posts_per_page' => -1 )
			);
	
} else if($folder_id == 'trash-files'){ // for main trash page
	$the_query = new WP_Query( array( 
				'post_type' => 'attachment',
				'post_status' => $file_post_status,
				'author' => $user_id,
				'meta_query' => array(
					array(
						'key'     => 'upf_doc',
						'value'   => 'true',
					)
				),
				'posts_per_page' => -1 )
			);
	
} else{
	$the_query = new WP_Query( array( 
				'post_type' => 'attachment',
				'post_status' => $file_post_status,
				'meta_query' => array(
					array(
						'key'     => 'upf_doc',
						'value'   => 'true',
					),
					array(
						'key'     => 'upf_foldr_id',
						'value'   => $folder_id
					)
				),
				'posts_per_page' => -1 )
			);
	
}



$all_docs_ids = array();
if($the_query->have_posts()){
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		$all_docs_ids[] = get_the_ID();
	}
}
wp_reset_query();

?>
<?php
// Display Sub Folders
foreach($folders as $folder){
	
	$display_this = true;
	
	if($folder_id == 'trash-files'){
		$parent_folder = get_post_meta($folder->ID, 'upf_parent_fldr', true);
		if($parent_folder){
			$parent_status = get_post_status($parent_folder);
			if($parent_status == 'trash'){
				$display_this = false;
			}
		}
	}
	
	if($display_this){
?>
		<div id="sub_folder_<?php echo $folder->ID; ?>" data-folder-id="<?php echo $folder->ID; ?>" data-folder-name="<?php echo $folder->post_title; ?>" data-status="<?php echo ($inside_trash)?'trash':''; ?>" class="folder-item upfp_fldr_obj">
		
			<a class="sub-folder-action" href="javascript:void(0);">
				<img src="<?php echo $folder_prvw_img; ?>">
			</a>
			<p class="folder_ttl"><?php echo $folder->post_title; ?></p>
			
		</div>
<?php
	}
}

// Display Files
foreach($all_docs_ids as $doc_id){
	
	$display_this = true;
	
	if($folder_id == 'trash-files'){
		$doc_folder = get_post_meta($doc_id, 'upf_foldr_id', true);
		if($doc_folder){
			$doc_folder_status = get_post_status($doc_folder);
			if($doc_folder_status == 'trash'){
				$display_this = false;
			}
		}
	}
	
	if($display_this){
	
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
	?>

		<div id="doc_<?php echo $doc_id; ?>" class="doc-item" data-status="<?php echo ($inside_trash)?'trash':''; ?>" data-alwd-usrs="<?php echo $ca_users_str; ?>">
			
			<?php if (strpos($mime_type, 'image') !== false) { $doc_thumb = wp_get_attachment_image_src($doc_id, 'thumbnail'); ?>
				
				<a class="upfp_single_file edit-doc" href="javascript:void(0);">
					<img data-type="img" data-src="<?php echo $doc_src; ?>" src="<?php echo $doc_thumb[0]; ?>">
				</a>
			
			<?php } else if(strpos($mime_type, 'video') !== false){ ?>
				
				<a class="edit-doc" href="javascript:void(0);">
					<img data-src="<?php echo $doc_src; ?>" src="<?php echo $vdo_prvw_img; ?>">
				</a>
				
			<?php } else if(strpos($mime_type, 'zip') !== false) { ?>
				
				<a class="edit-doc" href="javascript:void(0);">
					<img data-src="<?php echo $doc_src; ?>" src="<?php echo $zip_prvw_img; ?>">
				</a>
				
			<?php } else if(strpos($mime_type, 'pdf') !== false) { ?>
				
				<a class="edit-doc" href="javascript:void(0);">
					<img data-src="<?php echo $doc_src; ?>" src="<?php echo $pdf_prvw_img; ?>">
				</a>
				
			<?php } else if(strpos($mime_type, 'document') !== false) { ?>
				
				<a class="edit-doc" href="javascript:void(0);">
					<img data-src="<?php echo $doc_src; ?>" src="<?php echo $doc_prvw_img; ?>">
				</a>
				
			<?php } else{ ?>
				
				<a class="edit-doc" href="javascript:void(0);">
					<img data-src="<?php echo $doc_src; ?>" src="<?php echo $file_prvw_img; ?>">
				</a>
				
			<?php } ?>
			
			<p class="doc_ttl"><?php echo $doc_ttl; ?></p>
		
		</div>

<?php 
	}
}

if(!$folders && !$all_docs_ids){
	if($folder_status == 'trash'){
		
		if($folder_id == 'trash-files'){
			echo '<p class="no-files-err">' . __("Trash is Empty.", "user-private-files") . '</p>';
		} else{
			echo '<p class="no-files-err">' . __("No Files / Folders here.", "user-private-files") . '</p>';
		}
		
	} else{
		echo '<p class="no-files-err">' . __("No files / Folders here. Upload a file or create a new folder.", "user-private-files") . '</p>';
	}
}
