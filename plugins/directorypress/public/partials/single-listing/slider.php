<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$images = array();
$image_number = count($listing->images);
$full_with_image = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_100_single_logo_width'];
if($full_with_image){
	$width = '';
	$height = '';
}else{
	$width = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_logo_width'];
	$height = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_single_logo_height'];
}
if(!$slider_nav){
	$nonav_class = 'slick-carousel-nonav';
}else{
	$nonav_class = 'slick-carousel2';
}
$items = array();
$nav_items = array();

?>
<div class="directorypress-listing-figure-wrap directorypress-single-listing-logo-wrap" id="images">
	<?php
		foreach ($listing->images AS $attachment_id=>$image):
				$image_src_array = wp_get_attachment_image_src($attachment_id, 'full');
				$image_src = (is_array($image_src_array))? $image_src_array[0]: $image_src_array; 
				if($image_src){
					$image_src = $image_src;
				}elseif(!$image_src && (isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url']))){
					$image_src = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_nologo_url']['url'];
				}else{
					$image_src = DIRECTORYPRESS_RESOURCES_URL.'images/no-thumbnail.jpg';
				}
				if(!$full_with_image){
					$param = array(
						'width' => $width,
						'height' => $height,
						'crop' => true
					);
					$url = bfi_thumb($image_src, $param);
				}else{
					$url = $image_src;
				}
				$keys = array_search($attachment_id, $listing->images);
				echo $keys;
				//if ((count($listing->videos) >= 1 && count($listing->images) >= 1) || count($listing->images) > 1){
				if (count($listing->images) > 1){					
					if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_enable_lighbox_gallery']){		
						$items[] = '<div><a class="slide-link" href="' . esc_url($image_src) . '" data-lightbox="listing_images" title="images"><i class="dicode-material-icons dicode-material-icons-fullscreen"></i><img class="" data-lazy="' . esc_url($url) . '"  alt="images"/></a></div>';
					}else{
						$items[] = '<img class="slide-link" data-lazy="' . esc_url($url) . '"  alt="images"/><i class="dicode-material-icons dicode-material-icons-fullscreen"></i>';
					}
				}else{
					$items[] = '<a class="media-link" href="' . esc_url($image_src) . '" data-lightbox="listing_images" title="images"><img class="media-link" src="' . esc_url($url) . '"  alt="Media" data-lightbox="listing_images" title="images"/></a>';
				}
		endforeach;
		
		if(count($items) > 1){
			echo '<div class="'. esc_attr($nonav_class) .'">';
		}
			foreach($items AS $item){
				echo $item;
			}
		if(count($items) > 1){
			echo '</div>';
		}
		if($slider_nav && count($items) > 1){
			// slider nav
			foreach ($listing->images AS $attachment_id=>$image):
					$image_src_array = wp_get_attachment_image_src($attachment_id, 'full');
					$image_src = (is_array($image_src_array))? $image_src_array[0]: $image_src_array;
					$param = array(
						'width' => 152,
						'height' => 100,
						'crop' => true
					);
					$nav_items[] = '<img class="slide-link" data-lazy="' . bfi_thumb($image_src, $param) . '" width="152" height="100"  alt="thumbnail"/>';
			endforeach;
			// output
			echo '<div class="slider-nav">';
				foreach($nav_items AS $item){
					echo $item;
				}
			echo '</div>';
		}
	?>
</div>