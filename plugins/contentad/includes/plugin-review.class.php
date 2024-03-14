<?php

/**
 * Plugin review class.
 * Prompts users to give a review of the plugin on WordPress.org after a period of usage.
 *
 * Based on code by Rhys Wynne
 * https://winwar.co.uk/2014/10/ask-wordpress-plugin-reviews-week/
 *
 * @version   1.0
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * 
 * @version   1.1
 * Hevaily Modified by Juan Lucero
 * @author Juan Lucero <jlucero@content.ad>
 */

if ( ! class_exists( 'Contentad__Plugin__Review' ) ) :
class Contentad__Plugin__Review {

	/**
	 * Constructor intentionally left blank, add code as needed here
	 */
	public function __construct() { }
  
  /**
   * Fire Up the Plugin
   */
  public static function init() {
    add_action( 'init', array( 'Contentad__Plugin__Review', 'check_installation_date') );
  }

	/**
	 * Check date on admin initiation and add to admin notice if it was more than the time limit.
	 */
	public static function check_installation_date() {
    $contentad_log = 'Checking Installation Date';

		if ( true != get_site_option( CONTENTAD_SLUG . '-no-bug' ) ) {

			// If not installation date set, then add it
			$install_date = get_site_option( CONTENTAD_SLUG . '-activation-date' );
			if ( '' == $install_date ) {
				add_site_option( CONTENTAD_SLUG . '-activation-date', time() );
        $install_date = get_site_option( CONTENTAD_SLUG . '-activation-date' );
        $contentad_log .= PHP_EOL . "    Plugin Activated On: " . date('Y-m-d h:i:s', $install_date);
			}

			// If difference between install date and now is greater than time limit, then display notice
			if ( ( time() - $install_date ) >  CONTENTAD_REVIEW_DELAY  ) {
				add_action( 'admin_notices', array( 'Contentad__Plugin__Review', 'display_admin_notice' ) );
			}

			contentAd_append_to_log( $contentad_log );

		}

	}

	/**
	 * Display Admin Notice, asking for a review.
	 */
	public static function display_admin_notice() {

    $review_url = esc_url( 'https://wordpress.org/support/plugin/' . CONTENTAD_SLUG . '/reviews/#new-post' );
    $support_url = esc_url( 'https://wordpress.org/support/plugin/' . CONTENTAD_SLUG . '/' );

    echo '
    <div id="cad_review_notice" class="notice notice-info is-dismissible">
      <p>' . sprintf( __( 'How do you like the %s WordPress plugin? <a href="%s" target="_blank" id="cad_review_link">Leave us a positive review</a> or let us know if you need <a href="%s" target="_blank">help from our support team</a>.', 'contentad' ), CONTENTAD_NAME, $review_url, $support_url ) . '</p>
    </div>';
    
   ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
      // Do Not Show Review Notice Again
      function runNoBug() {
        $.post(
          ajaxurl,
          {
            action : 'track_registration_clicks',
            _ajax_nonce : '<?php echo wp_create_nonce( 'set_no_bug' ); ?>',
            postType : typenow,
          },
          function( response ){
            console.log(response.status);
            if( 'success' === response.status ){
            }
          },
          'json'
        );
      }
      // Plugin review event listener
      $( '#cad_review_link' ).click( function() {
        runNoBug();
      });
      // Opt-out event listener
      $( '#cad_review_notice' ).on( 'click', '.notice-dismiss', function( event, el )  {
        runNoBug();
      });
    });
    </script>
  <?php
    
    contentAd_append_to_log( "Plugin Rating Request Displayed In Admin" );
    
	}

	/**
	 * Set the plugin to no longer bug users if user asks not to be.
	 */
	public function set_no_bug() {
    
		add_site_option( CONTENTAD_SLUG . '-no-bug', true );
    contentAd_append_to_log( "Plugin Rating Request Dismissed" );
    wp_die();
    
	}

	/**
	 * Delete Activation Date and No-Bug options when the plugin is deactivated
	 */
  public static function delete_activation_date() {
    
    delete_option( CONTENTAD_SLUG . '-activation-date' );
    delete_option( CONTENTAD_SLUG . '-no-bug' );
    
  }
  
}
endif;

Contentad__Plugin__Review::init();
