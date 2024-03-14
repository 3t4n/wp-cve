<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$is_messages_addon = directorypress_is_messages_active();
if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_contact']){
				
				echo '<div class="directorypress-custom-popup" data-popup="single_contact_form">';
					echo '<div class="directorypress-custom-popup-inner single-contact">';
						echo '<div class="directorypress-popup-title">'.esc_html__('Send Message', 'DIRECTORYPRESS').'<a class="directorypress-custom-popup-close" data-popup-close="single_contact_form" href="#"><i class="far fa-times-circle"></i></a></div>';
						echo '<div class="directorypress-popup-content">';
							global $current_user;
							$authorID = $listing->post->post_author;
							$listing_owner = get_userdata($listing->post->post_author);
							$authoruser = get_the_author_meta( 'user_nicename', $authorID );
					
							if($current_user->ID == $listing->post->post_author) {
								echo esc_html__('You can not send message on your own lisitng', 'DIRECTORYPRESS');
							}else{
								if($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'instant_messages'){
									if($is_messages_addon){
										if(is_user_logged_in()){
											echo '<div class="form-new message">';
												echo do_shortcode('[difp_shortcode_new_message_form to="'. esc_attr($authoruser) .'" listing_id="'. esc_attr($listing->post->ID) .'" subject="'. esc_attr($listing->title()).'"]');
											echo '</div>';
										}else{
											directorypress_login_form();
										}
									}else{
										echo esc_html__('DirectoryPress Frontend Messages Addon Required.', 'DIRECTORYPRESS');
									}
								}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['message_system'] == 'email_messages'){
									if ($listing_owner = get_userdata($listing->post->post_author)){
											
										if (defined('WPCF7_VERSION') && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_contact_form_7']){
											echo do_shortcode($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_listing_contact_form_7']);
										}else{
											directorypress_display_template('partials/single-listing/_part/form.php', array('listing' => $listing)); 
												
										}
											
									}
								}else{
									echo esc_html__('Messages are currently disabled by Site Owner', 'DIRECTORYPRESS');
								}
							}
						echo'</div>';
					echo'</div>';
				echo'</div>';
			}