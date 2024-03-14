<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$images = array();
$image_number = count($listing->images);
$width = 177;
$height = 120;

if(!$slider_nav){
	$nonav_class = 'slick-carousel-nonav';
}else{
	$nonav_class = 'slick-carousel2';
}

?>
<?php if (count($listing->images) > 1): ?>
<div class="directorypress-listing-gallery-wrapper">
	<div class="directorypress-gallery-field-name"><?php echo esc_html__('Gallery', 'DIRECTORYPRESS'); ?></div>
	<div class="directorypress-listing-gallery">
			<?php
				foreach ($listing->images AS $attachment_id=>$image):
					$image_src_array = wp_get_attachment_image_src($attachment_id, 'full');	
					$image_src = $image_src_array[0]; 
					$param = array('width' => $width, 'height' => $height, 'crop' => true);
					$images = '<div><a class="slide-link" href="' . esc_url($image_src_array[0]) . '" data-lightbox="listing_images" title="images"><i class="directorypress-fic4-zoom-out"></i><img class="" data-lazy="' . bfi_thumb($image_src, $param) . '" width="'. esc_attr($width) .'" height="'. esc_attr($height) .'"  alt="images"/></a></div>';
					echo wp_kses_post($images);
				endforeach;
			?>
		</div>
	</div>
<?php endif; ?>


 