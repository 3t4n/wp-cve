<?php
/**
 * Promo Notice
 *
 * @version 1.0
 * @package Blog Designer
 */

/**
 * BD Load Plugin.
 */
function bd_load_plugin() {
	// The promo time.
	$first_review_time  = get_option( 'blog_designer_promo_time' );
	$second_review_time = get_option( 'blog_designer_promo_time_review' );

	if ( '' == $first_review_time ) {
		$first_review_time = time();
		update_option( 'blog_designer_promo_time', $first_review_time );
	}

	if ( '' == $second_review_time ) {
		$second_review_time = time();
		update_option( 'blog_designer_promo_time_review', $second_review_time );
	}

	if ( $second_review_time < ( time() - ( 60 * 60 * 24 * 3 ) ) ) {
		update_option( 'blog_designer_promo_time', $second_review_time );
	}

	// Are we to show the Blog Designer promo.
	if ( '' != $first_review_time && $first_review_time > 0 ) {
		add_action( 'admin_notices', 'bd_promo' );
	} else {
		$already_dismissed      = get_option( 'blog_designer_already_dismissed' );
		$already_dismissed_time = get_option( 'blog_designer_already_dismissed_time' );
		if ( 'yes' == $already_dismissed && $already_dismissed_time < ( time() - ( 60 * 60 * 24 * 7 ) ) ) {
			update_option( 'blog_designer_promo_time_review', '' );
			update_option( 'blog_designer_already_dismissed', '' );
			update_option( 'blog_designer_already_dismissed_time', '' );
		}
	}

	// Are we to disable the promo.
	if ( isset( $_GET['blog_designer_promo'] ) && 0 == (int) $_GET['blog_designer_promo'] ) {
		if ( $second_review_time < ( time() - ( 60 * 60 * 24 * 3 ) ) ) {
			update_option( 'blog_designer_promo_time', ( 0 - time() ) );
			update_option( 'blog_designer_promo_time_review', ( 0 - time() ) );
			update_option( 'blog_designer_already_dismissed', 'yes' );
			update_option( 'blog_designer_already_dismissed_time', time() );
			die( 'DONE' );
		} else {
			update_option( 'blog_designer_promo_time', ( 0 - time() ) );
			die( 'DONE' );
		}
	}
}

if ( ! function_exists( 'bd_promo' ) ) {

	/**
	 * BD Promo.
	 */
	function bd_promo() {
		$upgrade_display   = false;
		$first_review_time = get_option( 'blog_designer_promo_time' );
		if ( $first_review_time < ( time() - ( 60 * 60 * 24 * 3 ) ) ) {
			$upgrade_display = true;
		}
		echo '
            <script>
            jQuery(document).ready( function() {
                    (function($) {
                            $("#blog_designer_promo .blog_designer_promo-close").click(function(){
                                    var data;

                                    // Hide it
                                    $("#blog_designer_promo").hide();

                                    // Save this preference
                                    $.post("' . esc_attr( admin_url( '?blog_designer_promo=0' ) ) . '", data, function(response) {
                                            //alert(response);
                                    });
                            });
                    })(jQuery);
            });
            </script>
            <style>/* Promotional notice css*/
                .bd_button {
                    background-color: #4CAF50; /* Green */
                    border: none;
                    color: white;
                    padding: 8px 16px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    -webkit-transition-duration: 0.4s; /* Safari */
                    transition-duration: 0.4s;
                    cursor: pointer;
                }
                .bd_button:focus{
                    border: none;
                    color: white;
                }
                .bd_button1 {
                    color: white;
                    background-color: #4CAF50;
                    border:3px solid #4CAF50;
                }
                .bd_button1:hover {
                    box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
                    color: white;
                    border:3px solid #4CAF50;
                }
                .bd_button2 {
                    color: white;
                    background-color: #0085ba;
                }
                .bd_button2:hover {
                    box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
                    color: white;
                }
                .bd_button3 {
                    color: white;
                    background-color: #365899;
                }
                .bd_button3:hover {
                    box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
                    color: white;
                }
                .bd_button4 {
                    color: white;
                    background-color: rgb(66, 184, 221);
                }
                .bd_button4:hover {
                    box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
                    color: white;
                }
                .bd_button5 {
                    color: white;
                    background-color: #2c3e50;
                }
                .bd_button5:hover {
                    box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
                    color: white;
                }
                .blog_designer_promo-close {
                    float:right;
                    text-decoration:none;
                    margin: 5px 10px 0px 0px;
                }
                .blog_designer_promo-close:hover {
                    color: red;
                }
                </style>';
		if ( $upgrade_display ) {
			echo '
                <div class="notice notice-success" id="blog_designer_promo" style="min-height:120px">
                        <a class="blog_designer_promo-close" href="javascript:" aria-label="Dismiss this Notice">
                                <span class="dashicons dashicons-dismiss"></span> Dismiss
                        </a>
                        <img src="' . esc_attr( BLOGDESIGNER_URL ) . 'images/blog-designer-200.png" style="float:left; margin:10px 20px 10px 10px" width="100" />
                        <p style="font-size:16px">' . esc_html__( "It's been a while you are using <strong>Blog Designer</strong>, tell us did you like it or not? Care to share some love.", 'blog-designer' ) . '</p>
                        <p>
                                <a class="bd_button bd_button2" target="_blank" href="https://wordpress.org/support/plugin/blog-designer/reviews/?filter=5">' . esc_html__( "Rate it 5&#9733;'s", 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button3" target="_blank" href="https://www.facebook.com/SolwinInfotech/">' . esc_html__( 'Like us on Facebook', 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button4" target="_blank" href="https://twitter.com/home?status=' . rawurlencode( 'I use #blogdesigner to design my #WordPress blog site - http://blogdesigner.solwininfotech.com' ) . '">' . esc_html__( 'Follow us on Twitter', 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button5" target="_blank" href="https://www.solwininfotech.com/documents/wordpress/blog-designer/">' . esc_html__( 'Explore Documentation', 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button1" target="_blank" href="http://blogdesigner.solwininfotech.com/pricing/#ptp-816">' . esc_html__( 'Upgrade to Pro', 'blog-designer' ) . '</a>
                        </p>
                </div>';
		} else {
			echo '
                <div class="notice notice-success" id="blog_designer_promo" style="min-height:120px">
                        <a class="blog_designer_promo-close" href="javascript:" aria-label="Dismiss this Notice">
                                <span class="dashicons dashicons-dismiss"></span> Dismiss
                        </a>
                        <img src="' . esc_attr( BLOGDESIGNER_URL ) . 'images/blog-designer-200.png" style="float:left; margin:10px 20px 10px 10px" width="100" />
                        <p style="font-size:16px">' . esc_html__( 'We are delighted that you are using <strong>Blog Designer</strong>.', 'blog-designer' ) . '</p>
                        <p>
                                <a class="bd_button bd_button2" target="_blank" href="https://wordpress.org/support/plugin/blog-designer/reviews/?filter=5">' . esc_html__( "Rate it 5&#9733;'s", 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button3" target="_blank" href="https://www.facebook.com/SolwinInfotech/">' . esc_html__( 'Like us on Facebook', 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button4" target="_blank" href="https://twitter.com/home?status=' . rawurlencode( 'I use #blogdesigner to design my #WordPress blog site - http://blogdesigner.solwininfotech.com' ) . '">' . esc_html__( 'Follow us on Twitter', 'blog-designer' ) . '</a>
                                <a class="bd_button bd_button5" target="_blank" href="https://www.solwininfotech.com/documents/wordpress/blog-designer/">' . esc_html__( 'Explore Documentation', 'blog-designer' ) . '</a>
                        </p>
                </div>';
		}

	}
}
