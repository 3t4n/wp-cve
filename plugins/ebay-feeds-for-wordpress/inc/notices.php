<?php

/**
* Check if still using rover links
*
* @return void
*/
function ebffwp_check_if_rover_links() {

	if ( current_user_can( 'administrator' ) ) {

		$user_id = get_current_user_id();

		if ( ! get_user_meta( $user_id, 'ewpf_ignore_ar_warning' ) ) {

			$default = get_option( 'ebay-feeds-for-wordpress-default' );

			if ( stripos( $default, 'rest.ebay.com' ) ) {
				add_action( 'admin_notices', 'ebffwp_warn_user_on_old_rover_links' );
			} else {
				$restposts = ebay_feeds_for_wordpress_get_number_of_ebay_rest_posts();

				if ( $restposts->found_posts > 0 ) {
					add_action( 'admin_notices', 'ebffwp_warn_user_on_old_rover_links' );
				}
			}

		}
	}

} add_action( 'admin_init', 'ebffwp_check_if_rover_links' );



/**
* Notice to user that they have old rover links installed.
*
* They will need to change to the premium widget
*
* @return void
*/
function ebffwp_warn_user_on_old_rover_links() {


	$firstsep  = strtotime("1 September 2020");
	$tenthjuly = strtotime( "10 July 2020");
	$endjuly   = strtotime( "24th July 2020" );
	$midaugust = strtotime( "14th August 2020" );
	$now       = strtotime("Now");

	if ( $firstsep > $now ) {
		$warning = __( 'The dynamic feed generator from ebay will be removed on 1st September 2020. This means that your links <strong>will not work</strong> from that date.', 'ebay-feeds-for-wordpress' );
	} else {
		$warning = __( 'The dynamic feed generator has been removed, meaning <strong>your ebay listings are broken</strong>.', 'ebay-feeds-for-wordpress' );
	}

	if ( $tenthjuly > $now ) {
		$triallength = __( 'two months', 'ebay-feeds-for-wordpress' );
	} elseif ( $endjuly > $now ) {
		$triallength = __( 'six weeks', 'ebay-feeds-for-wordpress' );
	} elseif ( $midaugust > $now ) {
		$triallength = __( 'one month', 'ebay-feeds-for-wordpress' );
	} else {
		$triallength = __( 'two weeks', 'ebay-feeds-for-wordpress' );
	}

	$arurl = "https://www.winwar.co.uk/recommends/rss-ground-plugin/";

	?>
	<div class="notice notice-warning">
	<h2><?php printf( __( 'Please replace your feeds', 'ebay-feeds-for-wordpress' ) ); ?></h2>
	<p><?php printf( __( 'Thanks for upgrading to eBay Feeds for WordPress 3.0!', 'ebay-feeds-for-wordpress' ) ); ?></p>
	<p><?php printf( __( 'We have detectect you are using the old rest.ebay.com feeds in your link, created by eBay\'s Dynamic Feed Generator.', 'ebay-feeds-for-wordpress' ) ); ?></p>
	<p><?php echo $warning; ?></p>
	<p><?php printf( __( 'An Alternative exists, which uses RSS Ground, which provides alternative feeds for eBay.', 'ebay-feeds-for-wordpress' ) ); ?></p>
	<p><?php printf( __( '<a href="%s" class="button button-primary button-hero"><strong>Visit RSS Ground</strong></a> | <a href="%s">Dismiss</a>', 'ebay-feeds-for-wordpress' ), $arurl, '?ar_ignore_warning=1' ); ?></p>
	</div>
	<?php
}


/**
* If the user clicks "Dismiss Notice", we hide the notice for that user.
* @return void
*/
function ebffwp_ignore_warning() {
	if ( isset( $_GET['ar_ignore_warning'] ) && 1 == $_GET['ar_ignore_warning'] ) {
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'ewpf_ignore_ar_warning', true );
	}
} add_action( 'admin_init', 'ebffwp_ignore_warning' );
