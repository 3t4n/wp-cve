<?php

$typeName = WPAPE_GALLERY_NAMESPACE.'type';
$type = isset($_REQUEST[$typeName]) && trim($_REQUEST[$typeName]) ? trim($_REQUEST[$typeName]) : '';

if( isset($_REQUEST['post']) && (int) $_REQUEST['post'] ){
	$type = get_post_meta( (int) $_REQUEST['post'], $typeName, true );
}

$type = trim($type);

if( $type == false ){
	$url = admin_url('post-new.php?post_type=wpape_gallery_theme&wpape_type=grid');
	printf('<script>window.location.replace("%1$s");window.location.href = "%1$s";</script>', $url);
	exit;
}

$layouts = array(
	'slideshow' => 'slideshow.png',
	'base' 		=> 'grid3x3.png',
);
?>
<div id="wapeGalleryThemeTypeDiv"> 

	<img src="<?php echo WPAPE_GALLERY_URL; ?>modules/type/images/<?php echo isset($layouts[$type]) ? $layouts[$type] : '';?>" />
	
	<h4><?php _e( "Gallery Grid", 'gallery-images-ape'); ?></h4>
	
	<p>
		<?php _e( "Gallery Grid - basic gallery grid with custom hover effects and customizable layout. Flexible interface settings, icons, buttons, image title, description. Lightbox with description panel and social sharing options for the gallery images ", 'gallery-images-ape'); ?>
		<?php echo $type; ?>
	</p>
</div>