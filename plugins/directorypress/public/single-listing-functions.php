<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

// single listing title
add_action('single-listing-title', 'single_listing_title');
function single_listing_title($listing){
	directorypress_display_template('partials/single-listing/title.php', array('listing' => $listing));
}

// single listing categories
add_action('single-listing-categories', 'single_listing_categories');
function single_listing_categories($listing){
	directorypress_display_template('partials/single-listing/categories.php', array('listing' => $listing));
}

// single listing location string
add_action('single-listing-location', 'single_listing_location');
function single_listing_location($listing){
	directorypress_display_template('partials/single-listing/location.php', array('listing' => $listing));
}

// single listing date pubslished
add_action('single-listing-date-published', 'single_listing_date_published');
function single_listing_date_published($listing){
	directorypress_display_template('partials/single-listing/date-published.php', array('listing' => $listing));
}

// single listing click views
add_action('single-listing-views', 'single_listing_views');
function single_listing_views($listing){
	directorypress_display_template('partials/single-listing/views.php', array('listing' => $listing));
}

// single listing click views
add_action('single-listing-id', 'single_listing_id');
function single_listing_id($listing){
	directorypress_display_template('partials/single-listing/listing-id.php', array('listing' => $listing));
}

// single listing print
add_action('single-listing-print', 'single_listing_print', 10, 3);
function single_listing_print($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/print.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

// single listing pdf
add_action('single-listing-pdf', 'single_listing_pdf', 10, 3);
function single_listing_pdf($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/pdf.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

// single listing bookmark
add_action('single-listing-bookmark', 'single_listing_bookmark', 10, 3);
function single_listing_bookmark($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/bookmark.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

// single listing report
add_action('single-listing-report', 'single_listing_report', 10, 3);
function single_listing_report($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/report.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

// single listing share
add_action('single-listing-share', 'single_listing_share', 10, 3);
function single_listing_share($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/share.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

add_action('single-listing-review-button', 'single_listing_review_button', 10, 3);
function single_listing_review_button($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/review-button.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

add_action('single-listing-booking-button', 'single_listing_booking_button', 10, 3);
function single_listing_booking_button($listing, $button_text = false, $button_style = 1){
	directorypress_display_template('partials/single-listing/booking-button.php', array('listing' => $listing, 'button_text' => $button_text, 'button_style' => $button_style));
}

add_action('single-listing-resurva-booking-form', 'single_listing__resurva_booking_form', 10, 1);
function single_listing__resurva_booking_form($listing){
	directorypress_display_template('partials/single-listing/resurva-booking-form.php', array('listing' => $listing));
}

// single listing slider

add_action('single-listing-slider', 'single_listing_slider', 10, 2);
function single_listing_slider($listing, $slider_nav){
	
	directorypress_display_template('partials/single-listing/slider.php', array('listing' => $listing, 'slider_nav' => $slider_nav));
}

// single listing slider

add_action('single-listing-gallery', 'single_listing_gallery', 10, 2);
function single_listing_gallery($listing, $slider_nav){
	
	directorypress_display_template('partials/single-listing/gallery.php', array('listing' => $listing, 'slider_nav' => $slider_nav));
}

// single listing tabs
add_action('single-listing-tabs', 'single_listing_tabs', 10, 2);
function single_listing_tabs($listing, $hash = null){
	directorypress_display_template('partials/single-listing/tabs.php', array('listing' => $listing, 'hash' => $hash));
}

// single listing
add_action('single-listing-videos', 'single_listing_videos');
function single_listing_videos($listing){
	directorypress_display_template('partials/single-listing/videos.php', array('listing' => $listing));
}

// videos gallery
add_action('single-listing-video-gallery', 'single_listing_video_gallery');
function single_listing_video_gallery($listing){
	
	directorypress_display_template('partials/single-listing/video-gallery.php', array('listing' => $listing));
}

// single listing
add_action('single-listing-map', 'single_listing_map');
function single_listing_map($listing, $hash = null){
	directorypress_display_template('partials/single-listing/map.php', array('listing' => $listing, 'hash' => $hash));
}

// single listing
add_action('single-listing-review-form', 'single_listing_review_form');
function single_listing_review_form($listing){
	
	directorypress_display_template('partials/single-listing/review-form.php', array('listing' => $listing));
}

// single listing
add_action('directorypress-edit-listing-button', 'directorypress_edit_listing_button', 10, 3);
function directorypress_edit_listing_button($listing_id,  $button_text = false, $button_style = 1){
	global $current_user;
	if ($listing = directorypress_get_listing($listing_id)) {
		$text_string = ($button_text)? esc_html__('Edit Listing', 'DIRECTORYPRESS'): '';
		$tooltip = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Edit Listing', 'DIRECTORYPRESS').'"':'';
		
		if ( is_user_logged_in() &&  $current_user->ID == $listing->post->post_author) {
			echo '<a class="edit-button button-style-'. esc_attr($button_style) .'" href="'. esc_url(directorypress_edit_post_url($listing_id)) .'" '. wp_kses_post($tooltip) .'>';
				if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_type'] == 'img'){
					echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_url']) .'" alt="'. esc_attr__('Edit', 'DIRECTORYPRESS') .'"/>';
				}else{
					$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_edit_icon'] : 'dicode-material-icons dicode-material-icons-square-edit-outline'; 
					echo '<i class="'. esc_attr($icon) .'"></i>';
				}
				echo esc_html($text_string);
			echo '</a>';
		}
	}
}

// single listing
add_action('single-listing-similar', 'single_listing_similar');
function single_listing_similar($listing){
	directorypress_display_template('partials/single-listing/similar-listing.php', array('listing' => $listing));
}

add_action('single_listing_contact', 'single_listing_contact_function');
function single_listing_contact_function($listing){
	directorypress_display_template('partials/single-listing/contact.php', array('listing' => $listing));
}

add_action('single_listing_bidding', 'single_listing_bidding_function');
function single_listing_bidding_function($listing){
	directorypress_display_template('partials/single-listing/bidding.php', array('listing' => $listing));
}

add_filter('template_include', 'printlisting_template', 100000);
function printlisting_template($template) {
	global $directorypress_object;
	if ((is_page($directorypress_object->directorypress_archive_page_id) || is_page($directorypress_object->listing_page_id)) && ($directorypress_object->action == 'printlisting' || $directorypress_object->action == 'pdflisting')) {
		$template = directorypress_has_template('partials/single-listing/_part/print.php');	
	}
	return $template;
}

// breadcrumb
add_action('directorypress-breadcrumb', 'directorypress_breadcrumb', 10, 2);
function directorypress_breadcrumb($listing, $hash){
	
	if ($hash->breadcrumbs){
		echo '<ul class="directorypress-breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
			echo wp_kses_post($hash->getBreadCrumbs());
		echo '</ul>';
	}
}

// Business Hours

add_action('directorypress-business-hours', 'directorypress_business_hours');
function directorypress_business_hours($listing){
	directorypress_display_template('partials/single-listing/business-hours.php', array('listing' => $listing));									
}

// listing social links

add_action('directorypress-listing-social-links', 'directorypress_listing_social_links');
function directorypress_listing_social_links($listing){
	directorypress_display_template('partials/single-listing/social_profiles.php', array('listing' => $listing));									
}

add_action('single-listing-directory-head-section', 'single_listing_directory_head_section', 10, 2);
function single_listing_directory_head_section($listing, $hash){
	
	directorypress_display_template('partials/single-listing/directory-head-section.php', array('listing' => $listing, 'hash' => $hash));
}
 
// fb
//add_action('wp_footer', 'directorypress_fb_messenger');

function directorypress_fb_messenger($id = 'designinvento') {
    //if( $id != '' ) {
     // $genCode = "";
     // $genCode .= "
	 ?>
        <div id='fb-root'>
		<div class='fb-customerchat'
            attribution='wordpress'
            attribution_version='1.8'
            page_id='807907085905337'
          >
		</div>
		</div>
          <script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];js=d.createElement(s);js.id=id;js.src='https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js#xfbml=1&version=v6.0&autoLogAppEvents=1';fjs.parentNode.insertBefore(js,fjs)}(document,'script','facebook-jssdk'))</script>
       <?php 
      //  ";
     // _e($genCode);
    //}
}