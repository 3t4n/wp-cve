<?php
	/*
	* @Author 		Themepoints
	* Copyright: 	2016 Themepoints
	* Version : 1.0.1
	*/

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	// Review Notice Message
	function tp_team_showcase_review_notice_message() {
	    // Show only to Admins
	    if ( ! current_user_can( 'manage_options' ) ) {
	        return;
	    }

	    $installed = get_option( 'tpteamshowcase_installed' );
	    if ( !$installed ) {
	        update_option( 'tpteamshowcase_installed', time() );
	    }

	    $dismiss_notice  = get_option( 'tp_team_showcase_review_notice_dismiss', 'no' );
	    // $activation_time = strtotime( '-15 days' );
	    $activation_time = get_option( 'tpteamshowcase_installed' );
	    $plugin_info     = get_plugin_data( __FILE__ , true, true );
	    $plugin_url      = esc_url( 'https://wordpress.org/support/plugin/'. sanitize_title( $plugin_info['Name'] ) . '/reviews/' );

	    // check if it has already been dismissed
	    // and don't show notice in 15 days of installation, 1296000 = 15 Days in seconds
	    if ( 'yes' === $dismiss_notice ) {
	        return;
	    }

	    if ( time() - $activation_time < 432000 ) {
	        return;
	    }
	    ?>
	        <div id="tp-team-showcase-review-notice" class="tp-team-showcase-review-notice">
	            <div class="tp-teamshowcase-review-text">
	                <h3><?php echo wp_kses_post( 'Enjoying Team Showcase?', 'team-manager-free' ); ?></h3>
	                <p><?php echo wp_kses_post( 'You have been using <b> Team Showcase </b> for a while. Would you please show us a little love by rating us in the <a href="https://wordpress.org/support/plugin/team-showcase/reviews/#new-post" target="_blank"><strong>WordPress.org</strong></a>?', 'team-manager-free' ); ?></p>
	                <ul class="tp-teamshowcase-review-ul">
	                    <li><a href="https://wordpress.org/support/plugin/team-showcase/reviews/#new-post" target="_blank"><span class="dashicons dashicons-external"></span><?php esc_html_e( 'Sure! I\'d love to!', 'team-manager-free' ); ?></a></li>
	                    <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-smiley"></span><?php esc_html_e( 'I\'ve already left a review', 'team-manager-free' ); ?></a></li>
	                    <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-dismiss"></span><?php esc_html_e( 'Never show again', 'team-manager-free' ); ?></a></li>
	                 </ul>
	            </div>
	        </div>
	        <style type="text/css">
	            #tp-team-showcase-review-notice .notice-dismiss{
	                padding: 0 0 0 26px;
	            }
	            #tp-team-showcase-review-notice .notice-dismiss:before{
	                display: none;
	            }
	            #tp-team-showcase-review-notice.tp-team-showcase-review-notice {
	                padding: 15px;
	                background-color: #fff;
	                border-radius: 3px;
	                margin: 30px 20px 0 0;
	                border-left: 4px solid transparent;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-text {
	                overflow: hidden;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-text h3 {
	                font-size: 24px;
	                margin: 0 0 5px;
	                font-weight: 400;
	                line-height: 1.3;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-text p {
	                font-size: 15px;
	                margin: 0 0 10px;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-ul {
	                margin: 0;
	                padding: 0;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-ul li {
	                display: inline-block;
	                margin-right: 15px;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-ul li a {
	                display: inline-block;
	                color: #2271b1;
	                text-decoration: none;
	                padding-left: 26px;
	                position: relative;
	            }
	            #tp-team-showcase-review-notice .tp-teamshowcase-review-ul li a span {
	                position: absolute;
	                left: 0;
	                top: -2px;
	            }
	        </style>
	        <script type='text/javascript'>
	            jQuery('body').on('click', '#tp-team-showcase-review-notice .notice-dismiss', function(e) {
	                e.preventDefault();
	                jQuery("#tp-team-showcase-review-notice").hide();

	                wp.ajax.post('tp-team-showcase-dismiss-review-notice', {
	                    dismissed: true,
	                    _wpnonce: '<?php echo esc_attr( wp_create_nonce( 'teamhowcase_nonce' ) ); ?>'
	                });
	            });
	        </script>
	    <?php
	}

	add_action( 'admin_notices', 'tp_team_showcase_review_notice_message' );

	// Dismiss Review Notice
	function tp_team_showcase_dismiss_review_notice() {
	    if ( empty( $_POST['_wpnonce'] ) ) {
	         wp_send_json_error( __( 'Unauthorized operation', 'team-manager-free' ) );
	    }
	    if ( isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_wpnonce'] ) ), 'teamhowcase_nonce' ) ) {
	        wp_send_json_error( __( 'Unauthorized operation', 'team-manager-free' ) );
	    }
	    if ( ! empty( $_POST['dismissed'] ) ) {
	        update_option( 'tp_team_showcase_review_notice_dismiss', 'yes' );
	    }
	}
	add_action( 'wp_ajax_tp-team-showcase-dismiss-review-notice', 'tp_team_showcase_dismiss_review_notice' );