<?php
add_action( 'admin_notices', array( 'featured_image_pro_notices', 'featured_image_pro_premium_notice' ) );
add_action( 'admin_init', array ( 'featured_image_pro_notices', 'featured_image_pro_premium_nag_ignore' ) );

/**
 * featured_image_pro_notices class.
 */
if ( !class_exists ('featured_image_pro_notices' ) ):
class featured_image_pro_notices
{
		/**
		 * featured_image_pro_premium_notice function.
		 * create the premium/conversion notice
		 * @access public
		 * @static
		 * @return void
		 */
		static function featured_image_pro_premium_notice( ) {
			$hide = __( 'Hide Notice', 'featured-image-pro' );
			global $current_user;
		    $user_id = $current_user->ID;
		    /* Check that the user hasn't already clicked to ignore the message */
			$nag_id = 'nag_3';
		    $user_nag_meta = get_user_meta($user_id, 'featured_image_pro_premium_nag_ignore', true);

		    $nag_ignore = $user_nag_meta && isset( $user_nag_meta[$nag_id] ) ? $user_nag_meta[$nag_id] : false;
			$link = site_url ( '/wp-admin/options-general.php?page=featured-image-pro-admin' );
			$quesamp = strrchr($link, '?') != false ? '&' : '?';
			if ( !$nag_ignore )   {
		        echo '<div class="updated"><p>';
		        printf(__('Thanks for installing our plugin Featured Image Pro!!  <a href="http://plugins.shooflysolutions.com/featured-image-pro/" target="_blank">Visit our Website for examples!</a>    | <a href="%s">%s</a>'), $link  . $quesamp .'featured_image_pro_premium_nag_ignore=0', $hide);
		        echo "</p></div>";
			}
		}


		/**
		 * featured_image_pro_premium_nag_ignore function.
		 * update the nag ignore funtion if 'hide notice' has been clicked
		 * @access public
		 * @static
		 * @return void
		 */
		static function featured_image_pro_premium_nag_ignore( ) {
			global $current_user;
			$nag_id = 'nag_3';
	        $user_id = $current_user->ID;
		    $user_nag_meta = get_user_meta($user_id, 'featured_image_pro_premium_nag2_ignore', false);

	        /* If user clicks to ignore the notice, add that to their user meta */
	        if ( isset($_GET['featured_image_pro_premium_nag_ignore']) && '0' == $_GET['featured_image_pro_premium_nag_ignore'] ) {
		        $user_nag_meta[$nag_id] = true;
		        if (! $user_nag_meta )
	             	add_user_meta($user_id, 'featured_image_pro_premium_nag_ignore', $user_nag_meta, false);
	            else
	            	update_user_meta( $user_id, 'featured_image_pro_premium_nag_ignore', $user_nag_meta );
			}
		}
}
endif;