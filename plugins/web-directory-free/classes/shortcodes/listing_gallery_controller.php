<?php 

/**
 *  [webdirectory-listing-gallery] shortcode
 *
 *
 */
class w2dc_listing_gallery_controller extends w2dc_frontend_controller {
	public $listing;

	public function init($args = array()) {
		global $w2dc_instance;
		
		parent::init($args);
		
		$shortcode_atts = array_merge(array(
				'listing' => '',
		), $args);

		$this->args = $shortcode_atts;
		
		if (empty($this->args['listing'])) {
			if ($shortcode_controller = w2dc_getShortcodeController()) {
				if ($shortcode_controller->is_single) {
					if ($shortcode_controller->is_listing) {
						$this->listing = $shortcode_controller->listing;
					}
				}
			}
		} else {
			$this->listing = w2dc_getListing($this->args['listing']);
		}
		
		apply_filters('w2dc_listing_gallery_controller_construct', $this);
	}
	
	public function display() {
		
		if ($this->listing) {
			ob_start();
			
			$listing = $this->listing;
			
			if ($listing->logo_image && (!get_option('w2dc_exclude_logo_from_listing') || count($listing->images) > 1)) {
				echo '<meta itemprop="image" content="' . esc_attr($listing->get_logo_url()) . '" />';
				$listing->renderImagesGallery();
			}
			
			return ob_get_clean();
		}
	}
}

?>