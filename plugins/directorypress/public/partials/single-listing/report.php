<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS;
$text_string = ($button_text)? esc_html__('Report', 'DIRECTORYPRESS'): '';
$tooltip = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Report listing', 'DIRECTORYPRESS').'"':'';
?>
<?php if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_report_button']): ?>
	<a class="report-button button-style-<?php echo esc_attr($button_style); ?>" href="#" data-popup-open="single_report_form" <?php echo wp_kses_post($tooltip); ?>>
		<?php
			if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_type']) && $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_type'] == 'img'){
				echo '<img src="'. esc_url($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_url']) .'" alt="'. esc_attr__('report', 'DIRECTORYPRESS') .'"/>';
			}else{
				$icon = (isset($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon_type']) && !empty($DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon']))? $DIRECTORYPRESS_ADIMN_SETTINGS['single_listing_button_report_icon'] : 'dicode-material-icons dicode-material-icons-flag-outline'; 
				echo '<i class="'. esc_attr($icon) .'"></i>';
			}
		?>
		<?php echo esc_html($text_string); ?>
	</a>

	<?php
		echo '<div class="directorypress-custom-popup" data-popup="single_report_form">';
					echo '<div class="directorypress-custom-popup-inner single-report">';
						echo '<div class="directorypress-popup-title">'.esc_html__('Report This Listing', 'DIRECTORYPRESS').'<a class="directorypress-custom-popup-close" data-popup-close="single_report_form" href="#"><i class="far fa-times-circle"></i></a></div>';
						echo '<div class="directorypress-popup-content">';
							global $current_user;
							//$listing_owner = get_userdata($listing->post->post_author);
							$authorID = get_the_author_meta( 'ID' );
							if( is_user_logged_in() && $current_user->ID == $authorID) {
								echo esc_html__('You can not send message on your own lisitng', 'DIRECTORYPRESS');
							}elseif(!is_user_logged_in()) {
								echo esc_html__('Login Required', 'DIRECTORYPRESS');
							}else{
								directorypress_display_template('partials/single-listing/_part/report.php', array('listing' => $listing));
							}
						echo'</div>';
					echo'</div>';
		echo'</div>';
	?>
<?php endif; ?>