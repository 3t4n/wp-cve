<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/partials/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$resurva_url = get_post_meta($listing->post->ID, '_post_resurva_url', true);
//$resurva_url = 'https://designinvento129.resurva.com/book?embedded=true';
//if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_bidding']){
	echo '<div class="directorypress-custom-popup" data-popup="single_reserva_booking_form">';
		echo '<div class="directorypress-custom-popup-inner single-contact">';
			echo '<div class="directorypress-popup-title">'.esc_html__('Book Your Appointment', 'DIRECTORYPRESS').'<a class="directorypress-custom-popup-close" data-popup-close="single_reserva_booking_form" href="#"><i class="far fa-times-circle"></i></a></div>';
			echo '<div class="directorypress-popup-content">';
				echo '<div class="directorypress_resurva_form">';
					echo '<iframe src="'. esc_url($resurva_url) .'" name="resurva-frame" frameborder="0" width="450" height="450" style="max-width:100%"></iframe>';
				echo '</div>';
			echo'</div>';
		echo'</div>';
	echo'</div>';
//}