<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;

$is_messages_addon = directorypress_is_messages_active();
if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_bidding']){
				echo '<div class="directorypress-custom-popup" data-popup="single_contact_form_bid">';
					echo '<div class="directorypress-custom-popup-inner single-contact">';
						echo '<div class="directorypress-popup-title">'.esc_html__('Send Your Offer', 'DIRECTORYPRESS').'<a class="directorypress-custom-popup-close" data-popup-close="single_contact_form_bid" href="#"><i class="far fa-times-circle"></i></a></div>';
						echo '<div class="directorypress-popup-content">';
							if($is_messages_addon){
								global $current_user;
								$authorID = $listing->post->post_author;
								$listing_owner = get_userdata($listing->post->post_author);
								$authoruser = get_the_author_meta( 'user_nicename', $authorID );
								if( is_user_logged_in()) {
									if($current_user->ID == $authorID){
										echo esc_html__('You can not send message on your own lisitng', 'DIRECTORYPRESS');
									}else{
										echo '<div class="form-new bidding-form">';
											echo do_shortcode('[difp_shortcode_new_bidding_form to="'. esc_attr($authoruser) .'" listing_id="'. esc_attr($listing->post->ID) .'" subject="'. esc_attr($listing->title()).'"]');
										echo '</div>';
									}
								}else{
									directorypress_login_form();
								}
							}else{
								echo esc_html__('DirectoryPress Frontend Messages Addon Required', 'DIRECTORYPRESS');
							}
						echo'</div>';
					echo'</div>';
				echo'</div>';
}