<?php

if( ! defined('ABSPATH') ){
    exit;
}
if( !class_exists('Guten_Post_Layout_Options') ){
	class Guten_Post_Layout_Options{

		private static $instance = null;

		// instance control
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}
			return self::$instance;
		}

		// constructor function
		public function __construct(){
            add_action('admin_menu', array($this, 'add_admin_menu') );
			add_filter('admin_footer_text', array($this,'admin_footer_text') );
			add_filter( 'plugin_action_links_' . GUTEN_POST_LAYOUT_BASE, [$this, 'add_plugin_action_links'] );
			add_action('admin_enqueue_scripts', array($this,'guten_post_layout_admin_script') );
		}

		public function add_plugin_action_links ( $links ) {
			$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'themes.php?page=guten-post-layout-settings'), __( 'Learn More', 'guten-post-layout' ) );

			array_unshift( $links, $settings_link );

			$links['go_pro'] = sprintf( '<a href="%1$s" target="_blank" style="color: #3dbd4f; font-weight: 700;">%2$s</a>', 'https://gutendev.com/downloads/guten-post-layout-pro/', __( 'Go Pro', 'guten-post-layout' ) );

			return $links;
		}



		public function add_admin_menu(){
			add_submenu_page(
			   'themes.php',
			    esc_html__('Guten Post Layout', 'guten-post-layout'),
				esc_html__('Guten Post Layout', 'guten-post-layout'),
               'manage_options',
               'guten-post-layout-settings',
				array( $this, 'create_admin_page' ));
		}

		public function create_admin_page(){
			?>
            <div class="gpl-welcome-container">
                <div class="gpl-welcome-tab gpl-panel-contain">
                    <h2 class="nav-tab-wrapper">
                        <a class="nav-tab nav-tab-active nav-tab-link" data-tab-id="en-dashboard" href="#"><?php echo esc_html__( 'Dashboard', 'gpl-blocks' ); ?></a>
                        <a class="nav-tab nav-tab-link" data-tab-id="en-help" href="#"><?php echo esc_html__( 'Help', 'gpl-blocks' ); ?></a>
                        <a class="nav-tab nav-tab-link" data-tab-id="en-review" href="#"><?php echo esc_html__( 'Review', 'gpl-blocks' ); ?></a>
                    </h2>
                    <div class="gpl-wrapper">
                        <!-- dashboard page -->
                        <div class="nav-tab-content panel_open" id="en-dashboard">
                            <div class="gpl-welcome-header">
                                <h1 class="title">Get Started with Guten Post Layout</h1>
                                <p>Thank you so much for installing the <a href="https://wordpress.org/plugins/guten-post-layout/">Guten Post Layout</a>. We have designed and developed the most impressive post-layout designs to fire up your blog sites! If you have any confusions, please check out the following video!</p>
                            </div>
                            <!-- Features -->
                            <div class="gpl-intro-section">
                                <div class="gpl-video-features-image">
                                    <iframe class="gpl-embed-responsive-item" width="560" height="315" src="https://www.youtube.com/embed/uLHMInk1DFs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="gpl-cta-wrapper">
                                <a href="<?php echo admin_url() . '/post-new.php?post_type=page'?>" class="button button-primary button-large">Create Your First Layout</a>
                                <a href="https://gutendev.com/docs/guten-post-layout-pro/" class="button button-secondary button-large">View Our Documentation
                                </a>
                            </div>
                        </div>
                        <!-- help page -->
                        <div class="nav-tab-content" id="en-help">
                            <div class="gpl-section">
                                <div class="gpl-row en-mt-minus">
                                    <div class="gpl-columns-8">

                                        <p class="gpl-help-description">
                                            We have prepared a visual <a href="https://gutendev.com/make-your-webpages-more-interactive-with-guten-post-layout/" target="_blank">Documentation</a> for you; You can discover and get start from here:
                                        </p>
                                        <p class="gpl-review-description">We value all types of customer feedback.
                                            We encourage all comments or suggestions you may have to help us review and improve our services. If you need technical help or want any features please open a ticket <a href="https://gutendev.com/submit-ticket/" target="_blank">here</a>.</p>

                                    </div>
                                    <div class="gpl-columns-1">
                                        <img class="gpl-block-help-image" src="<?php echo plugins_url( 'admin/img/help-service.png', dirname( __FILE__ ));?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- review page -->
                        <div class="nav-tab-content" id="en-review">
                            <div class="gpl-section">
                                    <h1 class="gpl-review-title">Give us your feedback</h1>
                                    <p class="gpl-review-description">Please let us know what we are doing well or not so well. Any feedback we receive, both positive and negative, helps us to develop and improve our Guten Post Layout.</p>
                                <p class="gpl-review-description"> We hope you are enjoying Guten Post Layout? Can you please leave us a <a href="https://wordpress.org/support/plugin/guten-post-layout/reviews/" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. It will take less than one minute. We really look forward to getting your reviews which is super helpful to improve your beloved Guten Post Layout</p>
                                    <p class="gpl-review-btn">
                                        <a href="https://wordpress.org/support/plugin/guten-post-layout/reviews/" class="button button-primary" target="_blank"> Post Your Review </a>
                                    </p>
                                    <div class="gpl-columns-1">
                                        <img class="gpl-block-review-image" src="<?php echo plugins_url( 'admin/img/review.png', dirname( __FILE__ ));?>"/>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}

		// Admin footer modification
		public function admin_footer_text ($footer_text)
		{
			$current_screen = get_current_screen();
			if( $current_screen->base === 'appearance_page_guten-post-layout-settings' ){
				$footer_text = sprintf(
				/* translators: 1: Guten Post Layout, 2: Link to plugin review */
					__( 'We hope you are enjoying %1$s? Can you please leave us a %2$s rating. We really appreciate your support!', 'guten-post-layout' ),
					'<strong>' . __( 'Guten Post Layout', 'guten-post-layout' ) . '</strong>',
					'<a href="https://wordpress.org/support/plugin/guten-post-layout/reviews/" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
				);
            }
            return $footer_text;
		}




		public function guten_post_layout_admin_script(){
			$current_screen = get_current_screen();
			if( $current_screen->base === 'appearance_page_guten-post-layout-settings' ) {
				wp_enqueue_style(
					'gpl-admin-css', // Handle.
					GUTEN_POST_LAYOUT_DIR_URL . 'admin/css/styles.css', //css
					array(), // Dependency to include the CSS after it.
					GUTEN_POST_LAYOUT_VERSION // Version: File modification time.
				);

				wp_enqueue_script(
					'gpl-admin-js',
					GUTEN_POST_LAYOUT_DIR_URL . 'admin/js/script.js', //js
					array( 'jquery' ),
					GUTEN_POST_LAYOUT_VERSION
				);
			}
		}

	}
}


Guten_Post_Layout_Options::get_instance();
