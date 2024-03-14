<?php
/*
* Load & Display searched files & folders
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
$keyword = esc_attr( $data->keyword );

$args = array(
	'post_type'		=> 'upf_folder',
	'post_status'	=> 'publish',
	'author'		=> $user_id,
	'posts_per_page' => -1, 
	's' 	=> $keyword
);

$folders = get_posts($args);

// get shared folders
$shared_args = array(
	'post_type'		=> 'upf_folder',
	'post_status'	=> 'publish',
	'posts_per_page' => -1,
	's' 	=> $keyword,
	'meta_query' => array(
			array(
				'key' => 'upf_allowed',
				'value' => serialize(strval($user_id)),
				'compare' => 'LIKE',
			)
		)
);

$shared_folders = get_posts($shared_args);

// get shared files
$shared_file_args = array(
	'post_type'		=> 'attachment',
	'post_status'	=> 'inherit',
	'posts_per_page' => -1,
	's' 	=> $keyword,
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
			)
		)
);

$shared_files = get_posts($shared_file_args);

// Get files
$the_query = new WP_Query( array( 
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'author'	=> $user_id,
				'meta_query' => array(
					array(
						'key'     => 'upf_doc',
						'value'   => 'true',
					)
				),
				'posts_per_page' => -1,
				's' => $keyword )
			);

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
// Display Folders
foreach($folders as $sf){
	$sf_id = $sf->ID;
	$sf_name = get_the_title($sf_id);
?>
	<div id="sub_folder_<?php echo $sf_id; ?>" data-folder-id="<?php echo $sf_id; ?>" data-folder-name="<?php echo $sf_name; ?>" class="folder-item upfp_fldr_obj">
	
		<a class="sub-folder-action" href="javascript:void(0);">
			<img src="<?php echo $folder_prvw_img; ?>">
		</a>
		<p class="folder_ttl"><?php echo $sf_name; ?></p>
		
	</div>
<?php
}

// Display Files
foreach($all_docs_ids as $doc_id){
	
	$doc_ttl = get_the_title($doc_id);
	$doc_src = wp_get_attachment_url($doc_id);
	$mime_type = get_post_mime_type($doc_id);
?>

	<div id="doc_<?php echo $doc_id; ?>" class="doc-item">
	
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

// Display shared folders & files
if( $shared_folders || $shared_files ){
	
	echo '<h4 style="width: 100%;">' . __("Shared Files", "user-private-files") . '</h4>';
	
	// display folders
	foreach($shared_folders as $sf){
		$sf_id = $sf->ID;
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
	
	// display files
	foreach($shared_files as $s_file){
		$doc_id = $s_file->ID;
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


if(!$folders && !$all_docs_ids && !$shared_folders && !$shared_files){
	echo '<p class="no-files-err">' . __("No files / folders found", "user-private-files") . '</p>';
}
