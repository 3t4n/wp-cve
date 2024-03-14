<?php

/**
 * Show upgrading notifications for EOI  
 *
 */
class EasyOptInsUpgrade {
    
    private $settings;

    private $submenu_link;
    private $sidebar_link;
    private $review_link;
    private $support_link;
    private $editor_link;

    public function __construct( $settings=null ) {
        
        global $pagenow;
        $this->settings = $settings;

        if ( $this->has_provider( 'aweber' ) ) {
            $this->submenu_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=wp%2Bsubmenu&utm_source=Optin%2BCat%2BFree%2BAweber&utm_medium=plugin';
            $this->sidebar_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BAweber&utm_medium=plugin';
            $this->editor_link  = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=editor%2Bad&utm_source=Optin%2BCat%2BFree%2BAweber&utm_medium=plugin';
			$this->review_link  = 'https://wordpress.org/support/view/plugin-reviews/aweber-wp?rate=5#postform';
			$this->support_link = 'https://wordpress.org/support/plugin/aweber-wp';
			  
		} elseif ( $this->has_provider( 'campaignmonitor' ) ) {
            $this->submenu_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=wp%2Bsubmenu&utm_source=Optin%2BCat%2BFree%2BCampaign%2BMonitor&utm_medium=plugin';
            $this->sidebar_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BCampaign%2BMonitor&utm_medium=plugin';
            $this->editor_link  = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=editor%2Bad&utm_source=Optin%2BCat%2BFree%2BCampaign%2BMonitor&utm_medium=plugin';
        	$this->review_link  = 'https://wordpress.org/support/view/plugin-reviews/campaign-monitor-wp?rate=5#postform';
			$this->support_link = 'https://wordpress.org/support/plugin/campaign-monitor-wp';
		
		} elseif ( $this->has_provider( 'getresponse' ) ) {
            $this->submenu_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=wp%2Bsubmenu&utm_source=Optin%2BCat%2BFree%2BGetResponse&utm_medium=plugin';
            $this->sidebar_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BGetResponse&utm_medium=plugin';
            $this->editor_link  = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=editor%2Bad&utm_source=Optin%2BCat%2BFree%2BGetResponse&utm_medium=plugin';
          	$this->review_link  = 'https://wordpress.org/support/view/plugin-reviews/getresponse?rate=5#postform';
			$this->support_link = 'https://wordpress.org/support/plugin/getresponse';
			
		} elseif ( $this->has_provider( 'mailchimp' ) ) {
            $this->submenu_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=wp%2Bsubmenu&utm_source=Optin%2BCat%2BFree%2BMailChimp&utm_medium=plugin';
            $this->sidebar_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BMailChimp&utm_medium=plugin';
            $this->editor_link  = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=editor%2Bad&utm_source=Optin%2BCat%2BFree%2BMailChimp&utm_medium=plugin';
           	$this->review_link  = 'https://wordpress.org/support/view/plugin-reviews/mailchimp-wp?rate=5#postform';
			$this->support_link = 'https://wordpress.org/support/plugin/mailchimp-wp';       
		} elseif ( $this->has_provider('madmimi') ) {
            $this->submenu_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=wp%2Bsubmenu&utm_source=Optin%2BCat%2BFree%2BPopup&utm_medium=plugin';
            $this->sidebar_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree%2BPopup&utm_medium=plugin';
            $this->editor_link  = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=editor%2Bad&utm_source=Optin%2BCat%2BFree%2BPopup&utm_medium=plugin';
			$this->review_link  = 'https://wordpress.org/support/view/plugin-reviews/mad-mimi-wp?rate=5#postform';
			$this->support_link = 'https://wordpress.org/support/plugin/mad-mimi-wp';       
                      
		} else {
            $this->submenu_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=wp%2Bsubmenu&utm_source=Optin%2BCat%2BFree&utm_medium=plugin';
            $this->sidebar_link = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=sidebar%2Bad&utm_source=Optin%2BCat%2BFree&utm_medium=plugin';
            $this->editor_link  = 'https://fatcatapps.com/optincat/upgrade/?utm_campaign=editor%2Bad&utm_source=Optin%2BCat%2BFree&utm_medium=plugin';
            $this->review_link  = 'https://wordpress.org/support/view/plugin-reviews/mailchimp-wp?rate=5#postform';
			$this->support_link = 'https://wordpress.org/support/plugin/mailchimp-wp';       
		}

        add_action( 'admin_menu', array( $this, 'fca_eoi_upgrade_to_premium_menu' ));
        add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_filter( 'admin_footer_text', array( $this, 'show_upgrade_encouragement' ) );
		add_action( 'admin_notices', array( $this, 'show_quick_links' ) );
    }

    private function has_provider( $provider ) {
        return in_array( $provider, array_keys( $this->settings['providers'] ) );
    }

    function admin_footer() {
        $this->fca_eoi_upgrade_to_premium_menu_js();
        $this->show_upgrade_bar();
    }
	
	function show_quick_links() {
        if ( ! $this->is_in_eoi_page() ) {
            return;
        }
        echo '<div class="notice notice-info is-dismissible">';
		echo	'<p><strong>Quick Links:</strong> <a href="'. $this->support_link .'" target="_blank">Problems?  Get help here</a>. | <a href="'. $this->review_link .'" target="_blank"> Like this plugin?  Please leave a review</a>.</p>';
		echo '</div>';
        
    }

    function show_upgrade_encouragement( $text ) {
        if ( ! $this->is_in_eoi_page() ) {
            return $text;
        }

        $message =  "<span style='font-style: italic;'>| Thank you for using <a href='$this->sidebar_link' target='_blank'>Optin Cat</a>. " .
					"Wanna grow your list faster? Check out <a href='$this->sidebar_link' target='_blank'>Optin Cat Premium</a>. " .
					"Love it or get your money back. </span>";
								
        return $text . $message;
        
    }

    function show_upgrade_bar() {
        if ( ! $this->is_in_eoi_page() ) {
            return;
        }

        ?>
        <style>
            .fca_eoi_upgrade_bar {
                padding: 12px;
                background-color: #FCF8E3;
				width: 100%;
            }
			
			.fca_eoi_upgrade_bar.top_border {
                border-top: 1px solid #ccc;
				width: auto;
            }

            .fca_eoi_upgrade_bar.fca_eoi_upgrade_bar_inner {
                border-top: 1px solid #e5e5e5;
                margin: 0 0 -12px -12px;
				margin-top: 10px;
            }

            .fca_eoi_upgrade_bar.fca_eoi_upgrade_bar_page {
                border: 1px solid #e5e5e5;
                margin: 12px 12px 12px 0;
            }

            .fca_eoi_upgrade_bar a {
                font-size: 14px;
                font-weight: bold;
            }

            .fca_eoi_upgrade_sidebar {
                float: right;
                margin-right: 22px;
                margin-top: 11px;
                width: 270px;
            }

            .fca_eoi_upgrade_sidebar .fca_eoi_centered {
                text-align: center;
            }

            .fca_eoi_upgrade_sidebar .button-large {
                font-size: 17px;
                line-height: 30px;
                height: 32px;
            }

            .fca_eoi_upgrade_sidebar .last-blurb {
                font-size: 17px;
            }

            #wpbody-content.fca_eoi_upgrade_sidebar_present {
                width: calc( 100% - 300px );
            }
        </style>

        <div class="sidebar-container metabox-holder fca_eoi_upgrade_sidebar" id="fca_eoi_upgrade_sidebar" style="display: none">
            <div class="postbox">
                <h3 class="wp-ui-primary"><span>Get More Email Subscribers</span></h3>
                <div class="inside">
                    <div class="main">
                        <p class="last-blurb fca_eoi_centered">
                            Optin Cat Premium Boosts Conversions
                        </p>
                        <p>Optin Cat Premium comes with <em>everything you need</em> to grow your email list.</p>
                        <ul>
                            <li><div class="dashicons dashicons-yes"></div> 4 Brand New Optin Types</li>
                            <li><div class="dashicons dashicons-yes"></div> Tons of Layout & Design Options</li>
                            <li><div class="dashicons dashicons-yes"></div> Smart Optin Targeting</li>
                            <li><div class="dashicons dashicons-yes"></div> Content Upgrades</li>
                            <li><div class="dashicons dashicons-yes"></div> Exit Intervention Popups</li>
                            <li><div class="dashicons dashicons-yes"></div> Attention Grabbing Effects</li>
                            <li><div class="dashicons dashicons-yes"></div> And much more &hellip;</li>
                        </ul>

                        <div class="fca_eoi_centered">
                            <a href="<?php echo $this->sidebar_link ?>" class="button-primary button-large" target="_blank">
                                Upgrade to Premium >
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php

        $template = '<div class="__class__"><a href="' . $this->editor_link . '" target="_blank">__text__ &gt;&gt;</a></div>';

        $script = basename( $_SERVER['SCRIPT_NAME'] );
        if ( $script == 'post.php' || $script == 'post-new.php' ) { ?>
            <script>
                jQuery(document).ready( function($) {
                    var layouts_message = <?php echo json_encode( str_replace(
                        array( '__class__', '__text__' ),
                        array( 'fca_eoi_upgrade_bar top_border', 'Upgrade to Premium to get more powerful optins (optin bar, slide in, comment checkbox, 2-step optin) and tons of design options.' ),
                        $template
                    ) ) ?>;

                    $( '.fca_eoi_accordion_tab' ).last().next().after( layouts_message );
					
					 layouts_message = <?php echo json_encode( str_replace(
                        array( '__class__', '__text__' ),
                        array( 'fca_eoi_upgrade_bar', 'Upgrade to Premium for more layouts & design options' ),
                        $template
                    ) ) ?>;
									
                    $( '#fca_eoi_form_preview' ).next().after( layouts_message );

                    $( '#fca_eoi_publish_lightbox_mode_two_step_optin' ).after( <?php echo json_encode( str_replace(
                        array( '__class__', '__text__' ),
                        array( 'fca_eoi_upgrade_bar fca_eoi_upgrade_bar_inner', 'Want smart optin targeting, exit intervention popups or two-step optins? Upgrade to Premium' ),
                        $template
                    ) ) ?> );


                    var $power_ups_span = $( '.hndle span:contains("Power Ups")' );
                    if ( $power_ups_span && $power_ups_span.length > 0 ) {
                        $power_ups_span.parent().next( '.inside' ).append( <?php echo json_encode( '<br/>' . str_replace(
                            array( '__class__', '__text__' ),
                            array( 'fca_eoi_upgrade_bar fca_eoi_upgrade_bar_inner', 'Upgrade to Premium and get access to the Optin Bait Delivery Powerup' ),
                            $template
                        ) ) ?> );
                    }
					
					var publication_message = <?php echo json_encode( str_replace(
                        array( '__class__', '__text__' ),
                        array( 'fca_eoi_upgrade_bar fca_eoi_upgrade_bar_inner', 'Get more opt-ins and happier users with our advanced rule builder. Upgrade to Premium >>' ),
                        $template
                    ) ) ?>;

                    $( '#fca_eoi_publish_lightbox' ).children().last().after( publication_message );
									
                } );
            </script>
        <?php }

        elseif ( $script == 'edit.php' && ( empty( $_REQUEST['page'] ) || $_REQUEST['page'] == 'eoi_powerups' ) ) { ?>
            <script>
                jQuery( function( $ ) {
                    $( '#wpbody-content' ).addClass( 'fca_eoi_upgrade_sidebar_present' ).before( $( '#fca_eoi_upgrade_sidebar' ).show() );
                } );
            </script>
        <?php }
    }

    function fca_eoi_upgrade_to_premium_menu() {
        
        $page_hook = add_submenu_page( 'edit.php?post_type=easy-opt-ins', __( 'Upgrade to Premium'), __( 'Upgrade to Premium' ), 'manage_options', 'eoi_premium_upgrade', array( $this, 'fca_eoi_upgrade_to_premium' ));
        add_action( 'load-' . $page_hook , array( $this, 'fca_eoi_upgrade_to_premium_redirect' ));
     }
   
    function fca_eoi_upgrade_to_premium_redirect() {
        
        wp_redirect( $this->submenu_link, 301 );
        exit();
      }
      
    function fca_eoi_upgrade_to_premium_menu_js()
     {
    ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('a[href="edit.php?post_type=easy-opt-ins&page=eoi_premium_upgrade"]').on('click', function () {
                            $(this).attr('target', '_blank');
                });
            });
        </script>
        <style>
            a[href="edit.php?post_type=easy-opt-ins&page=eoi_premium_upgrade"] {
                color: #ee6800 !important;
            }
            a[href="edit.php?post_type=easy-opt-ins&page=eoi_premium_upgrade"]:hover {
                color: #C65700 !important;
            }
            .eoi-changelogs {
                background: #f1f1f1;
            }
            .eoi-changelogs-content {
                margin: 20px 10px;
                background: #fff;
            }
            
        </style>
    <?php 
    }
    
    function is_in_eoi_page() {
        return ( ! empty( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] == 'easy-opt-ins' ) ||
               ( ! empty( $GLOBALS['post'] ) && $GLOBALS['post']->post_type == 'easy-opt-ins' );
    }
}