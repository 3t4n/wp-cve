<?php
/*
* Load & Display shared sub-folders & files
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

// Get sub-folders
if($folder_id == 'all-shared-files' || $folder_id == 'filter-shared'){ // Get all folders for shared home page
	$folder_meta_query = array(
			array(
				'key' => 'upf_allowed',
				'value' => serialize(strval($user_id)),
				'compare' => 'LIKE',
			)
		);
}
else{
	$folder_meta_query = array(
			'relation'	  => 'AND',
			array(
				'key'     => 'upf_parent_fldr',
				'value'   => $folder_id
			),
			array(
				'key' => 'upf_allowed',
				'value' => serialize(strval($user_id)),
				'compare' => 'LIKE',
			)
		);
}

$fltr_email = $data->fltr_email;
if($fltr_email != ''){
	$user = get_user_by( 'email', $fltr_email );
	if($user){
		$args = array(
			'post_type'		=> 'upf_folder',
			'post_status'	=> 'publish',
			'author'		=> $user->ID,
			'posts_per_page' => -1, 
			'meta_query' 	=> $folder_meta_query
		);
	} else{
		echo '<p class="no-files-err">' . __("No files / Folders here.", "user-private-files") . '</p>';
		return;
	}
	
} else{
	$args = array(
		'post_type'		=> 'upf_folder',
		'post_status'	=> 'publish',
		'posts_per_page' => -1, 
		'meta_query' 	=> $folder_meta_query
	);
}

$folders = get_posts($args);

// Get files inside folders
if( $folder_id == 'all-shared-files' || $folder_id == 'filter-shared' ){
	$folder_sub_query = array();
} else{
	$folder_sub_query = array(
		'key'     => 'upf_foldr_id',
		'value'   => $folder_id
	);
}

if($fltr_email != ''){
	
	$the_query = new WP_Query( array( 
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'author' => $user->ID,
					'meta_query' => array(
						'relation'	  => 'AND',
						array(
							'key'     => 'upf_doc',
							'value'   => 'true',
						),
						array(
							'key' => 'upf_allowed',
							'value' => serialize(strval($user_id)),
							'compare' => 'LIKE',
						),
						$folder_sub_query
					),
					'posts_per_page' => -1 )
				);
	
} else{

	$the_query = new WP_Query( array( 
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'meta_query' => array(
						'relation'	  => 'AND',
						array(
							'key'     => 'upf_doc',
							'value'   => 'true',
						),
						array(
							'key' => 'upf_allowed',
							'value' => serialize(strval($user_id)),
							'compare' => 'LIKE',
						),
						$folder_sub_query
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

if($folder_id == 'all-shared-files' || $folder_id == 'filter-shared'){ // for main all-shared folders & files

	$sf_array = array();
	// Display Folders
	foreach($folders as $sf){
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
		<div id="sub_folder_<?php echo $sf_id; ?>" data-folder-id="<?php echo $sf_id; ?>" data-folder-name="<?php echo $sf_name; ?>" data-share="true" class="folder-item upfp_fldr_obj">
		
			<a class="sub-folder-action" href="javascript:void(0);">
				<img src="<?php echo $folder_prvw_img; ?>">
			</a>
			<p class="folder_ttl"><?php echo $sf_name; ?></p>
			
		</div>
	<?php
	}

	// Display Files
	foreach($all_docs_ids as $doc_id){
		
		$attach_fldr = get_post_meta($doc_id, 'upf_foldr_id', true);
		
		$is_fldr_shared = false;
		if($attach_fldr){
			$fldr_alwd_users = get_post_meta($attach_fldr, 'upf_allowed', true);
			if($fldr_alwd_users){
				$is_fldr_shared = in_array($user_id, $fldr_alwd_users);
			}
		}
		
		if( !$attach_fldr || !$is_fldr_shared ){
		
			$doc_ttl = get_the_title($doc_id);
			$doc_src = wp_get_attachment_url($doc_id);
			$mime_type = get_post_mime_type($doc_id);
		?>

			<div id="doc_<?php echo $doc_id; ?>" class="doc-item" data-share="true">
			
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
	
} else{ // if inside folder
	
	// folders inside folders
	foreach($folders as $folder){
	?>
		<div id="sub_folder_<?php echo $folder->ID; ?>" data-folder-id="<?php echo $folder->ID; ?>" data-folder-name="<?php echo $folder->post_title; ?>" data-share="true" class="folder-item upfp_fldr_obj">
		
			<a class="sub-folder-action" href="javascript:void(0);">
				<img src="<?php echo $folder_prvw_img; ?>">
			</a>
			<p class="folder_ttl"><?php echo $folder->post_title; ?></p>
			
		</div>
	<?php
	}
	
	// files inside sub-folders
	foreach($all_docs_ids as $doc_id){
		$doc_ttl = get_the_title($doc_id);
		$doc_src = wp_get_attachment_url($doc_id);
		$mime_type = get_post_mime_type($doc_id);
	?>
		<div id="doc_<?php echo $doc_id; ?>" class="doc-item" data-share="true">
		
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
	echo '<p class="no-files-err">' . __("No files / Folders here.", "user-private-files") . '</p>';
}
