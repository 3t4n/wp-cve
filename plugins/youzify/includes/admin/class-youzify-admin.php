<?php

class Youzify_Admin {

	public $admin_pages;
	
	function __construct() {

        if ( ! defined( 'YOUZIFY_STORE_URL' ) ) {
            define( 'YOUZIFY_STORE_URL', 'https://youzify.com' );
        }

		// Youzify Admin Pages
	    $this->admin_pages = array( 'youzify-panel', 'youzify-profile-settings', 'youzify-widgets-settings', 'youzify-membership-settings', 'youzify-extensions-settings' );

		// Init Admin Area
		add_action( 'init', array( $this, 'init' ) );

		// Show Change Log on Activation
		// add_action( 'admin_notices', array( $this, 'show_activation_change_log' ), 100 );

        // Add Plugin Links.
        add_filter( 'plugin_action_links_' . YOUZIFY_BASENAME, array( $this, 'plugin_action_links' ) );

        // Add Plugin Links in Multisite..
        add_filter( 'network_admin_plugin_action_links_' . YOUZIFY_BASENAME, array( $this, 'plugin_action_links' ) );

	}

	/**
	 * Init Admin Functions.
	 */
	function init() {

		// Include Files.
		require YOUZIFY_ADMIN_CORE . 'functions/youzify-general-functions.php';
		require YOUZIFY_ADMIN_CORE . 'functions/youzify-account-functions.php';
		require YOUZIFY_ADMIN_CORE . 'functions/youzify-profile-functions.php';
		require YOUZIFY_ADMIN_CORE . 'class-youzify-member-types.php';
		require YOUZIFY_ADMIN_CORE . 'class-youzify-extensions.php';

		// Extension Updaters.
		add_action( 'admin_enqueue_scripts', array( $this, 'extensions_updater' ) );

		// Add Youzify Plugin Admin Pages.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_youzify_save_addon_key_license', array( $this, 'save_addon_key_license' ) );

		// Show Leave Review Notice.
		// add_action( 'admin_notices', array( $this, 'show_leave_review_notice' ) );
        // add_action( 'wp_ajax_youzify_dismiss_review_notice', array( $this, 'dismiss_review_notice' ) );

	    if ( ! wp_doing_ajax() && ! is_youzify_panel() ) {
	    	return;
	    }

	    // Admin Init.
		add_action( 'admin_init',  array( $this, 'admin_init' ) );

		// Load Admin Scripts & Styles .
		add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

	}

	/**
	 * Leave Review
	 */
    public function show_leave_review_notice() {

		global $pagenow;

    	if ( $pagenow != 'index.php' || get_option( 'youzify_review_is_done' ) || get_option( 'youzify_review_is_dismissed' ) ) {
    		return;
    	}

    	// Get Activation.
    	$activation_date = get_option( 'youzify_review_timestamp' );

    	// If activation time not set! Set it now.
    	if ( empty( $activation_date ) ) {

    		// Set New Activation Date.
    		$activation_date = time();

    		update_option( 'youzify_review_timestamp', $activation_date );

    	}

    	// Check if one month has passed.
    	if ( $activation_date > strtotime( '-1 month' ) ) {
    		return;
    	}

	    ?>

        <style>

            .youzify-container {
                display: flex;
                padding: 15px;
            }

            .youzify-container .dashicons {
                margin-left:10px;
                margin-right:5px;
            }

            .youzify-review-image img{
                margin-top:0.5em;
            }

            .youzify-buttons-row {
                margin-top:15px;
                display: flex;
                align-items: center;
            }

            #youzify {
            	font-weight: 600;
			    border-radius: 5px;
			    border: none !important;
			    color: #fff !important;
			    background: linear-gradient(to left,#48eaf8 ,#dd2476);
			    background: -webkit-linear-gradient(right,#48eaf8 ,#9C27B0);
            }

            #youzify a {
			    color: #fff;
			    text-decoration: none;
            }

            #youzify .button-primary {
            	background: #FFEB3B;
				color: #222;
				border: none;
            }

            #youzify button {
				color: #fff !important;
				background: #fff;
				margin: 5px;
				border-radius: 3px;
				padding: 3px;
            }
        </style>
        <div id="youzify" class="updated fade notice is-dismissible youzify" style="border-left:4px solid #333">
            <div class="youzify-container">
                <div class="youzify-review-image"><img src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/logo.png'; ?>" alt=""></div>
                <div style="margin-left:30px">
                    <?php printf(__("<p>Hi, Thanks a lot for choosing Youzify to be a part of your project — It's an honor!</p><p>In the past years we dedicated our lives by working day and night on Youzify doing our best to deliver high quality features and we are still constantly striving to provide ideal experience for our customers.</p><p>Online reviews from awesome customers like you help others feel confident about choosing Youzify, and will really help us grow our business. If you don't mind could you take a moment to leave us a 5-Star rating and a good review? I would really appreciate it. Thank you in advance for helping us out!</p><p>If you have any questions or feedback, %sdon't hesitate to leave us a message%s.</p>", 'youzify'),'<a href="https://youzify.com/contact" target="_blank" style="text-decoration: underline; color: #fff06e;">','</a>'); ?>
                    <i style="color: #ffeb3b;">- Youssef Kaine | KaineLabs CEO</i>
                    <div class="youzify-buttons-row">
                        <a class="button button-primary" target="_blank"
                           href="<?php echo apply_filters( 'youzify_plugin_post_new_review_link', 'https://wordpress.org/support/plugin/youzify/reviews/#new-post' ); ?>"><?php _e( 'Yes, you deserve it', 'youzify' ); ?></a>
                        <div class="dashicons dashicons-calendar"></div><a href="#" id="maybe-later"><?php _e( 'Nope, Maybe later', 'youzify' ); ?></a>
                        <div class="dashicons dashicons-smiley"></div><a href="#" class="review-done"><?php _e( 'I already did', 'youzify' ); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <script type='text/javascript'>

            jQuery( document ).ready( function( $ ) {

                $( '#youzify' ).on( 'click', '.notice-dismiss', function( e ) {
                    youzify_dismiss_review( 'dismiss' );
                });

                $( '#youzify' ).on( 'click', '#maybe-later', function( e ) {
                	e.preventDefault();
                    youzify_dismiss_review( 'later' );
                    $( this ).closest( '.youzify' ).remove();
                });

                $( '#youzify' ).on( 'click', '.review-done', function( e ) {
                	e.preventDefault();
                    youzify_dismiss_review( 'done' );
                    $( this ).closest( '.youzify' ).remove();
                });

                /**
                 * Dismiss Youzify Review Notice.
                 */
                function youzify_dismiss_review( type ) {

                	// Init Data.
                    var data = {
                        'action': 'youzify_dismiss_review_notice',
                        'type' : type,
                        'security': '<?php echo wp_create_nonce( 'youzify-review-notice' ); ?>'
                    };

                    // Submit Action.
                    $.post( ajaxurl, data, function( response ) {});
                }

            });

        </script>
        <?php
    }

    /**
     * Dismiss Review Notice
     */
    function dismiss_review_notice( $links ) {

    	// Get Action.
        $type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : false;

        switch ( $type ) {

        	case 'done':
        		// Mark Review as Dismissed.
	            update_option( 'youzify_review_is_done', 1 );
        		break;

        	case 'dismiss':
        		// Mark Review as Dismissed.
	            update_option( 'youzify_review_is_dismissed', 1 );
        		break;

        	case 'later':
            	// Reset activation timestamp, notice will show again in one month.
	            update_option( 'youzify_review_timestamp', time() );
        		break;
        }

        wp_die();
    }

    /**
     * Youzify Action Links
     */
    function plugin_action_links( $links ) {

        // Get Youzify Plugin Pages.
        $panel_url 	 = esc_url( add_query_arg( array( 'page' => 'youzify-panel' ), admin_url( 'admin.php' ) ) );
        $plugin_url  = 'https://1.envato.market/LWAPO';
        $docs_url    = 'https://kainelabs.ticksy.com/articles/';
        $support_url = 'https://kainelabs.ticksy.com';
        $addons_url  = 'https://youzify.com/buddypress-addons/';

        // Add a few links to the existing links array.
        return youzify_array_merge( $links, array(
            'settings' => '<a href="' . $panel_url . '">' . esc_html__( 'Settings', 'youzify' ) . '</a>',
            'about'    => '<a href="' . $plugin_url . '">' . esc_html__( 'About', 'youzify' ) . '</a>',
            'docs'    => '<a href="' . $docs_url . '">' . esc_html__( 'Docs', 'youzify' ) . '</a>',
            'support'  => '<a href="' . $support_url . '">' . esc_html__( 'Support', 'youzify' ) . '</a>',
            'addons'  => '<a href="' . $addons_url . '">' . esc_html__( 'Add-Ons', 'youzify' ) . '</a>'
        ) );

    }

	/**
	 * Initialize Youzify Admin Panel
	 */
	function admin_init() {

		// Init Admin Files.
		include YOUZIFY_ADMIN_CORE . 'class-youzify-admin-ajax.php';

	}

	/**
	 * Add Youzify Admin Pages .
	 */
	function admin_menu() {

		// Show Youzify Panel to Admin's Only.
		if ( ! current_user_can( 'manage_options' ) && ! apply_filters( 'youzify_show_youzify_panel', false ) ) {
			return false;
		}

	    // Add Youzify Plugin Admin Page.
	    add_menu_page(
	    	__( 'Youzify', 'youzify' ),
	    	__( 'Youzify', 'youzify' ),
	    	'administrator',
	    	'youzify-panel',
	    	array( $this, 'general_settings' ),
	    	YOUZIFY_ADMIN_ASSETS . 'images/icon.png'
	    );

		// Add "General Settings" Page .
	    add_submenu_page(
	    	'youzify-panel',
	    	__( 'Youzify - General Settings', 'youzify' ),
	    	__( 'General Settings', 'youzify' ),
	    	'administrator',
	    	'youzify-panel',
	    	array( $this, 'general_settings' )
	    );

	    // Add "Profile Settings" Page .
	    add_submenu_page(
	    	'youzify-panel',
	    	__( 'Youzify - Profile Settings', 'youzify' ),
	    	__( 'Profile Settings', 'youzify' ),
	    	'administrator',
	    	'youzify-profile-settings',
	    	array( $this, 'profile_settings' )
	    );

	    // Add "Widgets Settings" Page .
	    add_submenu_page(
	    	'youzify-panel',
	    	__( 'Youzify - Widgets Settings', 'youzify' ),
	    	__( 'Widgets Settings', 'youzify' ),
	    	'administrator',
	    	'youzify-widgets-settings',
	    	array( $this, 'widgets_settings' )
	    );

	    if ( youzify_is_membership_system_active() ) {

			// Add "General Settings" Page .
		    add_submenu_page(
		    	'youzify-panel',
		    	__( 'Youzify - Membership Settings', 'youzify' ),
		    	__( 'Membership Settings', 'youzify' ),
		    	'administrator',
		    	'youzify-membership-settings',
		    	array( $this, 'membership_settings' )
		    );

	    }

	    if ( ! empty( apply_filters( 'youzify_extensions_settings_menu', array() ) ) ) {
		    // Add Youzify Plugin Admin Page.
		    add_submenu_page(
		    	'youzify-panel',
		    	__( 'Extensions Settings', 'youzify' ),
		    	__( 'Extensions Settings', 'youzify' ),
		    	'administrator',
		    	'youzify-extensions-settings',
		    	array( $this, 'extensions_settings' )
		    );
	    }

	}

	/**
	 * Extensions Settings.
	 */
	function extensions_settings() {

		// Filter.
		$tabs = apply_filters( 'youzify_extensions_settings_menu', array() );

		// Get Settings.
		$this->get_settings( $tabs, 'extensions-settings' );

	}

	/**
	 * Admin Scripts.
	 */
	function admin_scripts() {

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}


	    if ( in_array( $_GET['page'], $this->admin_pages ) ) {

			// Set Up Variables
			$jquery = array( 'jquery' );

	    	// Admin Panel Script
	    	wp_enqueue_script( 'klabs-settings', YOUZIFY_ADMIN_ASSETS . 'js/youzify-settings.min.js', $jquery, YOUZIFY_VERSION, true );
	        wp_enqueue_script( 'klabs-panel', YOUZIFY_ADMIN_ASSETS . 'js/klabs-panel.min.js', $jquery, YOUZIFY_VERSION, true );
	        wp_localize_script( 'klabs-settings', 'Youzify', array(
	            'reset_error' => __( 'An error occurred while resetting the options!', 'youzify' ),
	            'banner_url'  => __( 'Banner URL not working!', 'youzify' ),
    			'name_exist' => __( 'This name already exists!', 'youzify' ),
    			'required_fields' => __( 'All fields are required!', 'youzify' ),
    			'save_changes' => __( 'Save Changes', 'youzify' ),
	            'default_img' => YOUZIFY_ASSETS . 'images/default-img.png',
	            'ajax_url'    => admin_url( 'admin-ajax.php' ),
    			'done' => __( 'Save', 'youzify' )
        	) );

	        // Load Color Picker
	        wp_enqueue_script( 'wp-color-picker' );
    		wp_enqueue_style( 'wp-color-picker' );

			// Call Scripts.
	    	wp_enqueue_script( 'youzify-select2', YOUZIFY_ADMIN_ASSETS . 'js/select2.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
	    	wp_enqueue_style( 'youzify-select2', YOUZIFY_ADMIN_ASSETS . 'css/select2.min.css', array(), YOUZIFY_VERSION );

	        // Load Tags Script
	        wp_enqueue_script( 'klabs-tags', YOUZIFY_ADMIN_ASSETS .'js/klabs-tags.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

	        // Media
	        wp_enqueue_media();

		    if (
		    	'youzify-panel' == $_GET['page'] || 'youzify-profile-settings' == $_GET['page'] || 'youzify-extensions-settings' ==  $_GET['page']
		    	||
		    	( isset( $_GET['tab'] ) && in_array( $_GET['tab'], array( 'custom-widgets', 'user-tags', 'reaction-settings' ) ) )
		    ) {
			    // Admin Panel Script
			    wp_enqueue_script(
			    	'youzify-functions',
			    	YOUZIFY_ADMIN_ASSETS . 'js/youzify-functions.min.js',
			    	array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'youzify-iconpicker' ),
			    	YOUZIFY_VERSION, true
			    );
		    }

		    if ( $_GET['page'] == 'youzify-extensions-settings' ) {
		    	wp_enqueue_script( 'youzify-automatic-updates', YOUZIFY_ADMIN_ASSETS . 'js/youzify-automatic-updates.js', array(), YOUZIFY_VERSION );
			    wp_localize_script( 'youzify-automatic-updates', 'Youzify_Automatic_Updates', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		    }
	    }
	}

	/**
	 * Panel Styles.
	 */
	function admin_styles() {

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}

		// Load Admin Panel Styles
	    if ( in_array( $_GET['page'], $this->admin_pages ) ) {
	    	// Load Settings Style
		    wp_enqueue_style( 'klabs-settings', YOUZIFY_ADMIN_ASSETS . 'css/klabs-panel.min.css', array(), YOUZIFY_VERSION );
	        // Load Admin Panel Style
		    wp_enqueue_style( 'klabs-admin', YOUZIFY_ADMIN_ASSETS . 'css/klabs-admin.min.css', array(), YOUZIFY_VERSION );
	        // Load Google Fonts
	        wp_enqueue_style( 'youzify-fonts', 'https://fonts.googleapis.com/css?family=Open+Sans:100,400,600', array(), YOUZIFY_VERSION );
			// Icons.
			$this->icon_picker_scripts();
	    }

	}

	/**
	 * Icon
	 */
	function icon_picker_scripts() {
		// Loading Font Awesome.
    	wp_enqueue_style( 'youzify-icons', YOUZIFY_ADMIN_ASSETS . 'css/all.min.css', array(), YOUZIFY_VERSION );
	    youzify_iconpicker_scripts();
	}

	/**
	 * Extension Updaters.
	 **/
	function extensions_updater() {

		global $pagenow;

		switch ( $pagenow ) {

			case 'widgets.php':
				$this->icon_picker_scripts();
				break;

			default:
				break;
		}

	}

	/**
	 * General Settings.
	 */
	function general_settings() {

		// Menu Tabs List
		$tabs = array(
			'general' => array(
				'icon'  => 'fas fa-cogs',
				'id'   	=> 'general',
				'function' => 'youzify_general_settings',
				'title' => __( 'General Settings', 'youzify' ),
			),
			'wall' => array(
				'id' 		=> 'wall',
				'icon'  	=> 'fas fa-address-card',
				'function' 	=> 'youzify_wall_settings',
				'title' 	=> __( 'Wall Settings', 'youzify' ),
			),
			'groups' => array(
				'id'    => 'groups',
				'icon'  => 'fas fa-users',
				'function'  => 'youzify_groups_settings',
				'title' => __( 'Groups Settings', 'youzify' ),
			),
			'media' => array(
				'id' 	=> 'media',
				'icon'  => 'fas fa-photo-video',
				'function' => 'youzify_media_settings',
				'title' => __( 'Media Settings', 'youzify' ),
			),
			'bookmarks' => array(
				'id'    => 'bookmarks',
				'icon'  => 'fas fa-bookmark',
				'function'  => 'youzify_bookmarks_settings',
				'title' => __( 'Bookmarks Settings', 'youzify' ),
			),
			'messages' => array(
				'id'    => 'messages',
				'icon'  => 'fas fa-envelope',
				'function'  => 'youzify_messages_settings',
				'title' => __( 'Messages Settings', 'youzify' ),
			),
			'notifications' => array(
				'id'    => 'notifications',
				'icon'  => 'far fa-bell',
				'function'  => 'youzify_notifications_settings',
				'title' => __( 'Notifications Settings', 'youzify' ),
			),
			'members-directory' => array(
				'id'    => 'members-directory',
				'icon'  => 'fas fa-list',
				'function'  => 'youzify_members_directory_settings',
				'title' => __( 'Members Directory Settings', 'youzify' ),
			),
			'groups-directory' => array(
				'id'    => 'groups-directory',
				'icon'  => 'fas fa-list-alt',
				'function'  => 'youzify_groups_directory_settings',
				'title' => __( 'Groups Directory Settings', 'youzify' ),
			),
			'author-box' => array(
				'id'    => 'author-box',
				'icon'  => 'fas fa-address-book',
				'function' => 'youzify_author_box_settings',
				'title' => __( 'Author Box Settings', 'youzify' ),
			),
			'social-networks' => array(
				'icon'  => 'fas fa-share-alt',
				'id'    => 'social-networks',
				'function'  => 'youzify_social_networks_settings',
				'title' => __( 'Social Networks Settings', 'youzify' ),
			),
			'emoji' => array(
				'icon'  => 'fas fa-smile',
				'id'    => 'emoji',
				'function' => 'youzify_emoji_settings',
				'title' => __( 'Emoji Settings', 'youzify' ),
			),
			'custom-styling' => array(
				'id'    => 'custom-styling',
				'icon'  => 'fas fa-code',
				'function'  => 'youzify_custom_styling_settings',
				'title' => __( 'Custom Styling Settings', 'youzify' ),
			),
			'schemes' => array(
				'id'    => 'schemes',
				'icon'  => 'fas fa-paint-brush',
				'function' => 'youzify_schemes_settings',
				'title' => __( 'Schemes Settings', 'youzify' ),
			),
			'panel' => array(
				'icon'  => 'fas fa-cogs',
				'id'    => 'panel',
				'function' => 'youzify_panel_settings',
				'title' => __( 'Panel Settings', 'youzify' ),
			)
		);

		// Add BBpress Settings.
        if ( class_exists( 'bbPress' ) ) {
			$tabs['bbpress'] = array(
		   	    'icon'  => 'far fa-comments',
		   	    'id'    => 'bbpress',
		   	    'function' => 'youzify_bbpress_settings',
		   	    'title' => __( 'bbPress Settings', 'youzify' ),
		    );
		}

		// Add Woocommerce Settings.
        if ( class_exists( 'WooCommerce' ) ) {
			$tabs['woocommerce'] = array(
		        'id'    => 'woocommerce',
		   	    'icon'  => 'fas fa-shopping-cart',
		   	    'function' => 'youzify_woocommerce_settings',
		   	    'title' => __( 'WooCommerce Settings', 'youzify' ),
		    );
		}

	    // Add Mycred Settings.
        if ( defined( 'myCRED_VERSION' ) ) {
			$tabs['mycred'] = array(
		   	    'icon'  => 'fas fa-trophy',
		   	    'id'    => 'mycred',
		   	    'function' => 'youzify_mycred_settings',
		   	    'title' => __( 'MyCRED Settings', 'youzify' ),
		    );
		}

		// Add GamiPress Settings.
   		$tabs['gamipress'] = array(
	   	    'icon'  => 'fas fa-trophy',
	   	    'id'    => 'gamipress',
	   	    'function' => 'youzify_gamipress_settings',
	   	    'title' => __( 'GamiPress Settings', 'youzify' ),
	    );

   		// if ( bp_is_active( 'blogs' ) && is_multisite() ) {

			$tabs['sites-directory'] = array(
				'id'    => 'sites-directory',
				'icon'  => 'fas fa-globe-asia',
				'function'  => 'youzify_sites_directory_settings',
				'title' => __( 'Sites Directory Settings', 'youzify' ),
			);
   		// }

		// Filter.
		$tabs = apply_filters( 'youzify_panel_general_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'general-settings' );

	}

	/**
	 * Profile Settings.
	 */
	function profile_settings() {

		// Include Navbar Functions.
        include_once YOUZIFY_CORE . 'functions/youzify-navbar-functions.php';

		// Menu Tabs List.
		$tabs = array(
			'general' => array(
				'id' 		=> 'profile',
				'icon'  	=> 'fas fa-cogs',
				'function' 	=> 'youzify_profile_general_settings',
				'title' 	=> __( 'General Settings', 'youzify' ),
			),
			'structure' => array(
				'id' 	=> 'structure',
				'icon'  => 'fas fa-sort-amount-down',
				'function' => 'youzify_profile_structure_settings',
				'title' => __( 'Profile Structure', 'youzify' ),
			),
			'header' => array(
				'id' 	=> 'header',
				'icon'  => 'fas fa-heading',
				'function' => 'youzify_header_settings',
				'title' => __( 'Header Settings', 'youzify' ),
			),
			'navbar' => array(
				'icon'  => 'fas fa-list',
				'id' 	=> 'navbar',
				'function' => 'youzify_navbar_settings',
				'title' => __( 'Navbar Settings', 'youzify' ),
			),
			'reviews' => array(
				'id'    => 'reviews',
				'icon'  => 'fas fa-star',
				'function'  => 'youzify_reviews_settings',
				'title' => __( 'Reviews Settings', 'youzify' ),
			),
			'ads' => array(
				'id'    => 'ads',
				'icon'  => 'fas fa-bullhorn',
				'function' => 'youzify_get_ads_settings',
				'title' => __( 'Ads Settings', 'youzify' ),
			),
			'tabs' => array(
				'id' 	=> 'tabs',
				'icon'  => 'far fa-list-alt',
				'function' => 'youzify_tabs_settings',
				'title' => __( 'Tabs Settings', 'youzify' ),
			),
			'subtabs' => array(
				'id' 	=> 'tabs',
				'icon'  => 'fas fa-indent',
				'function' => 'youzify_profile_subtabs_settings',
				'title' => __( 'Subtabs Settings', 'youzify' ),
			),
			'custom-tabs' => array(
				'id' 	=> 'custom-tabs',
				'icon'  => 'fas fa-plus-square',
				'function' => 'youzify_profile_custom_tabs_settings',
				'title' => __( 'Custom Tabs Settings', 'youzify' ),
			),
			'info' => array(
				'id' 	=> 'info',
				'icon'  => 'fas fa-info',
				'function' => 'youzify_profile_info_tab_settings',
				'title' => __( 'Info Tab Settings', 'youzify' ),
			),
			'posts' => array(
				'id' 	=> 'posts',
				'icon'  => 'fas fa-file-alt',
				'function' => 'youzify_posts_settings',
				'title' => __( 'Posts Tab Settings', 'youzify' ),
			),
			'courses' => array(
				'id' 	=> 'courses',
				'icon'  => 'fas fa-book',
				'function' => 'youzify_courses_settings',
				'title' => __( 'Courses Tab Settings', 'youzify' ),
			),
			'media' => array(
				'id' 	=> 'media',
				'icon'  => 'fas fa-photo-video',
				'function' => 'youzify_profile_media_tab_settings',
				'title' => __( 'Media Tab Settings', 'youzify' ),
			),
			'comments' => array(
				'id' 	=> 'comments',
				'icon'  => 'far fa-comments',
				'function' => 'youzify_comments_settings',
				'title' => __( 'Comments Tab Settings', 'youzify' ),
			),
			'profile-404' => array(
				'id'   => 'profile-404',
				'icon'  => 'fas fa-exclamation-triangle',
				'function' => 'youzify_profile_404_settings',
				'title' => __( 'Profile 404 Settings', 'youzify' ),
			),
			'account-verification' => array(
				'id'    => 'account-verification',
				'icon'  => 'fas fa-check-circle',
				'function'  => 'youzify_account_verification_settings',
				'title' => __( 'Account Verification Settings', 'youzify' ),
			),
		);

		// Add Third Party Plugins Subnavs Settings
        $third_party_tabs = youzify_get_profile_third_party_tabs();

        if ( empty( $third_party_tabs ) ) {
			unset( $tabs['subtabs'] );
        }

		$tabs = apply_filters( 'youzify_panel_profile_settings_menus', $tabs );


		// Get Settings.
		$this->get_settings( $tabs, 'profile-settings' );

	}

	/**
	 * Widgets Settings.
	 */
	function widgets_settings() {

		// Widgets Tabs List.
		$tabs = array(
			'widgets' => array(
				'id' 	=> 'widgets',
				'function' => 'youzify_general_widgets_settings',
				'title' => __( 'Widgets Settings', 'youzify' ),
				'icon'  => 'fas fa-cogs'
			),
			'about-me' => array(
				'id' 	=> 'about_me',
				'title' => __( 'About Me Settings', 'youzify' ),
				'function' => 'youzify_about_me_widget_settings',
				'icon'  => 'fas fa-user'
			),
			'post' => array(
				'id' 	=> 'post',
				'title' => __( 'Post Settings', 'youzify' ),
				'function' => 'youzify_post_widget_settings',
				'icon'  => 'fas fa-pencil-alt'
			),
			'project' => array(
				'id' 	=> 'project',
				'title' => __( 'Project Settings', 'youzify' ),
				'function' => 'youzify_project_widget_settings',
				'icon'  => 'fas fa-suitcase'
			),
			'skills' => array(
				'id' 	=> 'skills',
				'function' => 'youzify_skills_widget_settings',
				'title' => __( 'Skills Settings', 'youzify' ),
				'icon'  => 'fas fa-tasks'
			),
			'services' => array(
				'id' 	=> 'services',
				'title' => __( 'Services Settings', 'youzify' ),
				'function' => 'youzify_services_widget_settings',
				'icon'  => 'fas fa-wrench'
			),
			'portfolio' => array(
				'id' 	=> 'portfolio',
				'title' => __( 'Portfolio Settings', 'youzify' ),
				'function' => 'youzify_portfolio_widget_settings',
				'icon'  => 'fas fa-camera-retro'
			),
			'slideshow' => array(
				'id' 	=> 'slideshow',
				'title' => __( 'Slideshow Settings', 'youzify' ),
				'function' => 'youzify_slideshow_widget_settings',
				'icon'  => 'fas fa-film'
			),
			'quote' => array(
				'id' 	=> 'quote',
				'title' => __( 'Quote Settings', 'youzify' ),
				'function' => 'youzify_quote_widget_settings',
				'icon'  => 'fas fa-quote-right'
			),
			'link' => array(
				'id' 	=> 'link',
				'title' => __( 'Link Settings', 'youzify' ),
				'function' => 'youzify_link_widget_settings',
				'icon'  => 'fas fa-unlink'
			),
			'video' => array(
				'id' 	=> 'video',
				'title' => __( 'Video Settings', 'youzify' ),
				'function' => 'youzify_video_widget_settings',
				'icon'  => 'fas fa-video'
			),
			'instagram' => array(
				'id' 	=> 'instagram',
				'title' => __( 'Instagram Settings', 'youzify' ),
				'function' => 'youzify_instagram_widget_settings',
				'icon'  => 'fab fa-instagram'
			),
			'media' => array(
				'id' 	=> 'wall_media',
				'title' => __( 'Media Settings', 'youzify' ),
				'function' => 'youzify_media_widget_settings',
				'icon'  => 'fas fa-photo-video'
			),
			'flickr' => array(
				'id' 	=> 'flickr',
				'title' => __( 'Flickr Settings', 'youzify' ),
				'function' => 'youzify_flickr_widget_settings',
				'icon'  => 'fab fa-flickr'
			),
			'user-balance' => array(
				'id' 	=> 'user_balance',
				'title' => __( 'User Balance Settings', 'youzify' ),
				'function' => 'youzify_user_balance_widget_settings',
				'icon'  => 'fas fa-gem'
			),
			'user-badges' => array(
				'id' 	=> 'user_badges',
				'title' => __( 'User Badges Settings', 'youzify' ),
				'function' => 'youzify_user_badges_widget_settings',
				'icon'  => 'fas fa-trophy'
			),
			'friends' => array(
				'id' 	=> 'friends',
				'title' => __( 'Friends Settings', 'youzify' ),
				'function' => 'youzify_friends_widget_settings',
				'icon'  => 'far fa-handshake'
			),
			'groups' => array(
				'id' 	=> 'groups',
				'title' => __( 'Groups Settings', 'youzify' ),
				'function' => 'youzify_groups_widget_settings',
				'icon'  => 'fas fa-users'
			),
			'reviews' => array(
				'id' 	=> 'reviews',
				'title' => __( 'Reviews Settings', 'youzify' ),
				'function' => 'youzify_reviews_widget_settings',
				'icon'  => 'far fa-star'
			),
			'info-boxes' => array(
				'id' 	=> 'info_box',
				'title' => __( 'Info Boxes Settings', 'youzify' ),
				'function' => 'youzify_info_boxes_widget_settings',
				'icon'  => 'fas fa-clipboard'
			),
			'user-tags' => array(
				'id' 	=> 'user_tags',
				'title' => __( 'User Tags Settings', 'youzify' ),
				'function' => 'youzify_user_tags_widget_settings',
				'icon'  => 'fas fa-tags'
			),
			'recent-posts' => array(
				'id' 	=> 'recent_posts',
				'title' => __( 'Recent Posts Settings', 'youzify' ),
				'function' => 'youzify_recent_posts_widget_settings',
				'icon'  => 'far fa-newspaper'
			),
			'social-networks' => array(
				'id' 	=> 'social_networks',
				'title' => __( 'Social Networks Settings', 'youzify' ),
				'function' => 'youzify_social_networks_widget_settings',
				'icon'  => 'fas fa-share-alt'
			),
			'custom-widgets' => array(
				'id' 	=> 'custom_widgets',
				'title' => __( 'Custom Widgets Settings', 'youzify' ),
				'function' => 'youzify_custom_widget_settings',
				'icon'  => 'fas fa-plus'
			)
		);

		// Filter
		$tabs = apply_filters( 'youzify_panel_widgets_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'widgets-settings' );

	}

	/**
	 * Membership settings.
	 */
	function membership_settings() {


		// Menu Tabs List
		$tabs = array(
			'general' => array(
				'icon'  	=> 'fas fa-cogs',
				'id' 		=> 'general',
				'function' 	=> 'youzify_membership_general_settings',
				'title' 	=> __( 'General Settings', 'youzify' ),
			),
			'login'	=> array(
				'id' 		=> 'login',
				'icon'  	=> 'fas fa-sign-in-alt',
				'function' 	=> 'youzify_membership_login_settings',
				'title' 	=> __( 'Login Settings', 'youzify' ),
			),
			'register' => array(
				'icon'  	=> 'fas fa-pencil-alt',
				'id' 		=> 'register',
				'function' 	=> 'youzify_membership_register_settings',
				'title' 	=> __( 'Register Settings', 'youzify' ),
			),
			'lost-password' => array(
				'icon'  	=> 'fas fa-lock',
				'id' 		=> 'lost_password',
				'function' 	=> 'youzify_membership_lost_password_settings',
				'title' 	=> __( 'Lost Password Settings', 'youzify' ),
			),
			'captcha' => array(
				'id' 		=> 'captcha',
				'icon'  	=> 'fas fa-user-secret',
				'function' 	=> 'youzify_membership_captcha_settings',
				'title' 	=> __( 'Captcha Settings', 'youzify' ),
			),
			'social-login' => array(
				'icon'  	=> 'fas fa-share-alt',
				'id' 		=> 'social_login',
				'function' 	=> 'youzify_membership_social_login_settings',
				'title' 	=> __( 'Social Login Settings', 'youzify' ),
			),
			'limit-login' => array(
				'icon'  	=> 'fas fa-user-clock',
				'id' 		=> 'limit_login',
				'function' 	=> 'youzify_membership_limit_login_settings',
				'title' 	=> __( 'Login Attempts Settings', 'youzify' ),
			),
			'newsletter' => array(
				'icon'  	=> 'far fa-envelope',
				'id' 		=> 'newsletter',
				'function' 	=> 'youzify_membership_newsletter_settings',
				'title' 	=> __( 'Newsletter Settings', 'youzify' ),
			),
			'login-styling' => array(
				'icon'  	=> 'fas fa-paint-brush',
				'id' 		=> 'login_styling',
				'function' 	=> 'youzify_membership_login_styling_settings',
				'title' 	=> __( 'Login Styling Settings', 'youzify' ),
			),
			'register-styling' => array(
				'icon'  	=> 'fas fa-paint-brush',
				'id' 		=> 'register_styling',
				'function' 	=> 'youzify_membership_register_styling_settings',
				'title' 	=> __( 'Register Styling Settings', 'youzify' ),
			)
		);

		// Filter
		$tabs = apply_filters( 'youzify_panel_membership_settings_menus', $tabs );

		// Get Settings.
		$this->get_settings( $tabs, 'membership-settings' );

	}

	/**
	 * Get Page Settings
	 */
	function get_settings( $tabs, $page = false ) {

		global $Youzify_Settings;

		// Get Tabs Keys
		$settings_tabs = array_keys( $tabs );

		// Get Current Tab.
		$current_tab = isset( $_GET['tab'] ) && in_array( $_GET['tab'], $settings_tabs ) ? (string) $_GET['tab'] : (string) key( $tabs );

		// Append Class to the active tab.
		$tabs[ $current_tab ]['class'] = 'youzify-active-tab';

		// Get Tab Data.
		$tab = $tabs[ $current_tab ];

		// Get Tab Function Name.
		$settings_function = isset( $tab['function'] ) ?  $tab['function']: null;

		ob_start();

        $Youzify_Settings->get_field(
        	array(
	            'type'  => 'start',
	            'id'    => $tab['id'],
	            'icon'  => $tab['icon'],
	            'title' => $tab['title'],
       		)
        );


        $file = YOUZIFY_ADMIN_CORE . $page . '/youzify-settings-' . $current_tab . '.php';

        if ( file_exists( $file ) ) {
			include $file;
        }

		$settings_function();

        $Youzify_Settings->get_field( array( 'type' => 'end' ) );

		$content = ob_get_contents();

		ob_end_clean();

		// Print Panel
		$this->admin_panel( $tabs, $content );

	}

	/**
	 * Add License Activation Notice.
	 */
	function extension_validate_license_notice( $args = null ) {

		?>

		<style type="text/css">

			.youzify-addon-license-area input {
				margin-right: 8px;
			}

			.youzify-addon-license-area .youzify-activate-addon-key {
				background-color: #03A9F4;
				height: 27px;
				line-height: 27px;
				padding: 0 15px;
				color: #fff;
				border-radius: 2px;
				font-weight: 600;
				cursor: pointer;
				font-size: 13px;
				min-width: 80px;
				text-align: center;
			}

			.youzify-addon-license-area input,
			.youzify-addon-license-area .youzify-activate-addon-key {
				display: inline-block;vertical-align: middle;
			}

			.youzify-addon-license-msg {
				color: #616060;
				margin: 12px 0;
				font-size: 13px;
				background: #fff;
				font-weight: 600;
				border-radius: 2px;
				padding: 10px 25px;
				border-left: 5px solid #9E9E9E;
			}

			.youzify-addon-error-msg {
				border-color: #F44336;
			}

			.youzify-addon-success-msg {
				border-color: #8BC34A;
			}

		</style>

		<tr class="active">
			<td>&nbsp;</td>
			<td colspan="2">
				<div class="youzify-addon-license-area">
					<div class="youzify-addon-license-content">
						<?php _e( 'Please enter and activate your license key to enable automatic updates:', 'youzify' ); ?>
						<input type="text" class="youzify-addon-license-key" name="<?php echo $args['field_name']; ?>"><div data-product-name="<?php echo $args['product_name']; ?>" data-nounce="<?php echo wp_create_nonce( 'youzify_addon_license_notice' ); ?>" class="youzify-activate-addon-key"><?php _e( 'Verify Key', 'youzify' ); ?></div>
					</div>
				</div>
		    </td>
		</tr>

		<?php

	}

	/**
	 * Youzify Panel Form.
	 */
	function admin_panel( $menu = null, $settings = null ) {

		do_action( 'youzify_admin_before_form' );

	?>

	<div id="ukai-panel" class="<?php echo youzify_option( 'youzify_panel_scheme', 'youzify-yellow-scheme' ); ?>">

	    <div class="uk-sidebar">
	        <div class="ukai-logo">
	        	<img src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/logo.png'; ?>" alt="">
	        </div>
	        <a class="youzify-tab-extensions" href="<?php echo apply_filters( 'youzify_panel_extensions_page_link', menu_page_url( 'youzify-extensions', false ) ); ?>"><i class="fas fa-plug"></i><?php _e( 'Extensions <span class="new">New</span>') ?></a>
			<div class="kl-responsive-menu">
				<?php _e( 'Menu', 'youzify' ); ?>
				<input class="kl-toggle-btn" type="checkbox" id="kl-toggle-btn">
	  			<label class="kl-toggle-icon" for="kl-toggle-btn"></i><span class="kl-icon-bars"></span></label>
			</div>

			<!-- Panel Menu. -->
	        <?php $this->get_menu( $menu ); ?>
	    </div>

	    <div id="ukai-panel-content" class="ukai-panel">
	        <div class="youzify-main-content"><?php echo $settings; ?></div>
	    </div>

	    <div id="kl-right-sidebar">

	    	<?php if ( ! youzify_is_feature_available () ) : ?>
	    	<div class="kl-sidebar-widget version-pro-widget">

	    		<div class="widget-title"><i class="far fa-gem"></i><?php _e( 'Youzify Pro', 'youzify' ); ?></div>
	    		<p><?php _e( 'More Features, More Advanced, Much More to Love.', 'youzify' ); ?></p>
	    		<ul>
	    			<li><?php _e( 'One-Time Payment', 'youzify' ); ?></li>
	    			<li><?php _e( 'Lifetime Updates', 'youzify' ); ?></li>
	    			<li><?php _e( '6 Months Free Support', 'youzify' ); ?></li>
	    		</ul>
	    		<a class="cta-button" href="https://1.envato.market/zWGyr"><?php _e( 'GO Premium', 'youzify' ); ?></a>
		    </div>
			<?php endif; ?>
	    	<div class="kl-sidebar-widget documentation-widget white-widget">
	    		<div class="widget-title"><i class="fas fa-book"></i><?php _e( 'Documentation', 'youzify' ); ?></div>
	    		<p><?php _e( 'Check our detailed documentation on how to setup Youzify.', 'youzify' ); ?></p>
	    		<a class="cta-button" href="https://kainelabs.ticksy.com/article/16338/"><?php _e( 'View Documentation', 'youzify' ); ?></a>
		    </div>

	    	<div class="kl-sidebar-widget support-widget white-widget">

	    		<div class="widget-title"><i class="fas fa-life-ring"></i><?php _e( "Need Help? We've got your back!", 'youzify' ); ?></div>
	    		<p><?php _e( 'Our support team will be very happy to help and will stay by your side till you solve the problem.', 'youzify' ); ?></p>
	    		<a class="cta-button" href="https://kainelabs.ticksy.com/"><?php _e( 'Premium support', 'youzify' ); ?></a>
		    </div>

	    	<div class="kl-sidebar-widget addons-widget">
	    		<div class="widget-title"><i class="fas fa-puzzle-piece"></i><?php _e( 'Youzify Add-Ons', 'youzify' ); ?></div>
	    		<p><?php _e( 'Unleash the power of your community to make it more Engaging, Advanced, Safe and Profitable.', 'youzify' ); ?></p>
	    		<a class="cta-button" href="https://kainelabs.com/?utm_source=dashboard&utm_medium=panel-sidebar"><?php _e( 'View All Add-Ons', 'youzify' ); ?></a>
		    </div>

	    	<div class="kl-sidebar-widget facebook-widget">
	    		<div class="widget-title"><i class="fab fa-facebook-f"></i><?php _e( "Let's Build Youzify Together!", 'youzify' ); ?></div>
	    		<p><?php _e( 'Become a part of the Youzify VIP Community and Meet other Youzify users that are running a real business and see how they are using it. Share Your Experience, Ask Questions, Request New Features, and more!', 'youzify' ); ?></p>
	    		<strong></strong>
	    		<a class="cta-button" href="https://www.facebook.com/groups/235003947151622/"><?php _e( 'Join The Group', 'youzify' ); ?></a>
		    </div>

	    </div>

	</div>

	<div class="youzify-md-overlay"></div>

	<!-- Reset Dialog -->
	<?php youzify_popup_dialog( 'reset_tab' ); ?>

	<!-- Errors Dialog -->
	<?php youzify_popup_dialog( 'error' ); ?>

	<?php do_action( 'youzify_admin_after_form' ); ?>

	<?php if ( 'on' == youzify_option( 'youzify_enable_panel_fixed_save_btn', 'on' ) ) : ?>
		<div class="youzify-fixed-save-btn"><i class="fas fa-save"></i></div>
	<?php endif; ?>

	<?php

	}

	/**
	 * Get Menu Content.
	 */
	function get_menu( $tabs_list ) {

		$page = isset( $_GET['page'] ) ? $_GET['page'] : 'youzify-panel';

		// Get Current Page Url.
		$current_url = admin_url( 'admin.php?page=' . $page );

		echo '<ul class="youzify-panel-menu youzify-form-menu">';

		foreach ( $tabs_list as $key => $tab ) {

			if ( isset( $tab['hide_menu'] ) && $tab['hide_menu'] === true ) {
				continue;
			}

			// Get Tab Class Name.
			$class = isset( $tab['class'] ) ? 'class="youzify-active-tab"' : null; ?>

			<li>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $key, $current_url ) ); ?>" <?php echo $class; ?>><i class="<?php echo $tab['icon']; ?>"></i><?php echo $tab['title']; ?></a>
			</li>

			<?php

		}

	    echo '</ul>';
	}

	/**
	 * License Settings.
	 */
	function get_license_settings( $args = array() ) {

		// Get License.
		$license = isset( $args['license'] ) ? $args['license'] : get_option( $args['option_id'] );

        global $Youzify_Settings;

		// Get License Dates.
		$support_date = get_option( $args['option_id'] . '_expires' );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'License Settings', 'youzify' ),
                'type'  => 'openBox',
                'class' => 'youzify-addon-license-settings'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title'  => __( 'License Key', 'youzify' ),
                'desc'  => sprintf( __( '<a href="%s">How to find your product license key?</a>', 'youzify' ), 'https://kainelabs.ticksy.com/article/15685/' ),
                'id'    => 'license',
                'type'  => 'text',
                'class' => 'youzify-addon-license-key',
                'std'   => $license,
                'hide_name' => true,
            )
        );

        if ( ! $license || empty( get_option( $args['option_id'] . '_expires' ) ) || ( $support_date != 'lifetime' && strtotime( $support_date ) < time() )  ) {

            $Youzify_Settings->get_field(
                array(
                    'button_title' => __( 'Verify License', 'youzify' ),
                    'id'    => 'youzify-verify-license',
                    'type'  => 'button',
                    'button_class' => 'youzify-activate-addon-key',
                    'button_data' => array(
                        'product-name' => $args['product_id'],
                        'option-name' => $args['option_id'],
                    )
                )
            );

        }

        // Get License Status.
        $this->get_license_status( $args );

        $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

	}

	/**
	 * License Status.
	 */
	function get_license_status( $args ) {

		// Get License.
		$license = get_option( $args['option_id'] );

		if ( $license ) {

			// Get Dates.
			$support_date = get_option( $args['option_id'] . '_expires' );

            if ( empty( $support_date ) ) {
                return;
            }

			// Compare Support Date.
			if ( $support_date == 'lifetime' ) {
				$status = 'lifetime';
			} else {
				$status = strtotime( $support_date ) > time() ? 'active' : 'expired';
			}

			if ( ( $status == 'active' || $status == 'lifetime' ) && ( isset( $args['position'] ) && $args['position'] == 'top' ) ) {
				return;
			}

			// Get License Expiration Date.
			$expiration_date = date_i18n( get_option( 'date_format' ), strtotime( $support_date, current_time( 'timestamp' ) ) );

		    echo '<div class="youzify-addon-expire-notice youzify-addon-license-' . $status . '">';

		    if ( $status == 'expired' ) {
		    	echo sprintf( __( 'Your license key expired on %s.', 'youzify' ), $expiration_date );
		    } elseif ( $status == 'lifetime' ) {
		    	echo sprintf( __( 'Your license key is valid forever.', 'youzify' ), $expiration_date );
			} else {
		    	echo sprintf( __( 'Your license key will expire on %s.', 'youzify' ), $expiration_date );
			}

			if ( $status == 'expired' ) {
		    	echo '<a href="https://www.kainelabs.com/checkout/?edd_license_key=' . $license . '&download_id=' . $args['product_id'] . '">' . __( 'Renew License with 30% OFF.', 'youzify' ) . '</a>';
			}

			echo '</div>';

	    }

	}

	/**
	 * Save Add On Key License
	 */
	function save_addon_key_license() {

		// retrieve the license from the database
		$license = trim( sanitize_text_field( $_POST['license'] ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'  	 => urlencode( sanitize_text_field( $_POST['product_name'] ) ),
			'url'        => home_url()
		);

		$args = apply_filters( 'youzif_addons_request_args', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// Call the custom API.
		$response = wp_remote_post( YOUZIFY_STORE_URL, $args );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'youzify' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success && $license_data->error != 'expired' ) {

				switch( $license_data->error ) {

					// case 'expired' :
					// 	$message = sprintf(
					// 		__( 'Your license key expired on %s.', 'youzify' ),
					// 		date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					// 	);
					// 	break;

					case 'disabled' :
					case 'revoked' :
						$message = __( 'Your license key has been disabled.', 'youzify' );
						break;

					case 'missing' :
						$message = __( 'Invalid license.', 'youzify' );
						break;

					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.', 'youzify' );
						break;

					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'youzify' ), sanitize_text_field( $_POST['product_name'] ) );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'youzify' );
						break;

					default :
						$message = __( 'An error occurred, please try again.', 'youzify' );
						break;
				}

			}

		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			wp_send_json_error( array( 'message' => $message ) );
		} else {

			// Get Addon Name.
			$addon_name = sanitize_text_field( $_POST['name'] );

			youzify_update_option( $addon_name . '_expires', $license_data->expires );
			youzify_update_option( $addon_name, $license );

			wp_send_json_success( array( 'message' => __( 'Success!', 'youzify' ) ) );
		}

		exit();

	}


	/**
	 * Change Log.
	 */
	function show_activation_change_log() {


		$id = 'youzify-change-log-notice-' . YOUZIFY_VERSION;

	    if ( isset( $_GET['youzify-dismiss-offer-notice'] ) ) {
	        youzify_update_option( $_GET['youzify-dismiss-offer-notice'], 1 );
	    }

		if ( youzify_option( $id ) ) {
			return;
		}

	    // Get PRO Tag.
	    $pro = ! youzify_is_feature_available() ? '<span class="youzify-log-pro">PRO</span>' : '';

		$future = strtotime('24 October 2021'); //Future date.
		$timefromdb = strtotime('now');//source time
		$timeleft = $future-$timefromdb;
		$daysleft = round((($timeleft/24)/60)/60);
		$total_days = sprintf( _n( '%d DAY LEFT', '%d DAYS LEFT', $daysleft, 'youzify' ), $daysleft );

		if ( $daysleft <= 0 ) {
			$total_days = 'Offer Expired';
		}

	    // Get Log Details.
	    $logs = array(
	    	// array(
		    //     'log' => array(
		    //         'fix' => array(
		    //             'GamiPress & MyCRED Conflict',
		    //         ),
		    //     ),
	    	// ),
	    	array(
		        'title' => 'Youzify ' . YOUZIFY_VERSION,
		        // 'title' => 'Youzify 3.2.4',
		        'log' => array(
		            'announce' => array(
		                'Due to the Envato pricing regulations we will increase the PRO version price by <strong>24 OCTOBER 2021</strong> instead of 31 October. <a target="_blank" href="https://1.envato.market/Rqjj9"><strong>Click here to get Youzify Pro Version for $49 only</strong></a> - <strong>Onetime-payment, LIFETIME UPDATES.</strong><strong style="color:#ff1e00;"> ( ' . $total_days . ' )</strong>',
		            ),
		            'fix' => array(
		                // $pro .'Live Notifications not working in some sites.',
		                'Members Directory Media Styling.',
		                'Members Directory Search Icon Not Showing Up.',
		                'Profile Header Unclosed Span.',
		            ),
		        ),
	    	)
	    );

	    // Get Offer Details
	    $offer = array(
	        'label' => 'Use "FESLAUNCH30" to SAVE 30% OFF. Ends 09 October.',
	        'image' => 'https://youzify.com/wp-content/uploads//edd/2021/09/buddypress-frontend-subission.png',
	        'title' => 'WordPress – Frontend Post Submission',
	        'description' => 'Allow your website users to create, edit and delete posts from the site frontend also you can allow visitors to submit guest blog posts and other content on your WordPress site without logging into the admin area.',
	        'features' => array(
	        	'Frontend Posting, Editing, Deletio with a Beautiful Design and UX.',
	        	'User-friendly Forms Builder with Drag & Drop.',
	        	'Create Unlimited Frontend Posting Forms',
	        	'Advanced BuddyPress Settings',
	        	'Create Unlimited Form Custom Fields',
	        	'Create Unlimited Advanced Upload Fields',
	        	'Powerful Real-time Shortcodes',
	        	'Allow Anonymous Users Posting',
	        	'Advanced Detailed Styling Options',
	        	'Limit number of posts by time and by user role and for each form.',
	        	'And Much Much More...',
	        ),
	        'button' => 'View All Features',
	        'link' => 'https://youzify.com/downloads/wordpress-frontend-posting-plugin/?utm_campaign=changelog' . YOUZIFY_VERSION . '&utm_medium=notice&utm_source=client-site&utm_content=learn-more'
	    );


        // Get Coupon Details.
        $coupon = array(
        	'label' => 'Special Offer',
        	'description' => 'Use Coupon <strong>FESLAUNCH30 </strong> to save <strong>30% OFF</strong> on any of our addons.',
        	'ends' => 'Ends 21 August at 23.59 GMT.',
        	'button' => 'View all Addons',
        	'date' => '21-08-2021',
        	'link' => 'https://youzify.com/buddypress-addons/?utm_campaign=changelog' . YOUZIFY_VERSION . '-coupon&utm_medium=notice&utm_source=client-site&utm_content=view-all-addons',
        );

	        ?>

	        <style type="text/css">
			.yz-shake-me {
				display: inline-block;
				margin: 0;
				position: relative;
				top: -2px;
				margin-right: 10px;
			    transform: translate3d(0, 0, 0);
			    backface-visibility: hidden;
			    animation-name: bounceRight;
			    animation-duration: 1s;
			    animation-iteration-count: infinite;
			    animation-timing-function: linear;
			}

    /* right bounce */
    @-webkit-keyframes bounceRight {
      0%,
      20%,
      50%,
      80%,
      100% {
        -webkit-transform: translateX(0);
        transform: translateX(0);
      }
      40% {
        -webkit-transform: translateX(-20px);
        transform: translateX(-20px);
      }
      60% {
        -webkit-transform: translateX(-15px);
        transform: translateX(-15px);
      }
    }
    @-moz-keyframes bounceRight {
      0%,
      20%,
      50%,
      80%,
      100% {
        transform: translateX(0);
      }
      40% {
        transform: translateX(-30px);
      }
      60% {
        transform: translateX(-15px);
      }
    }
    @keyframes bounceRight {
      0%,
      20%,
      50%,
      80%,
      100% {
        -ms-transform: translateX(0);
        transform: translateX(0);
      }
      40% {
        -ms-transform: translateX(-20px);
        transform: translateX(-20px);
      }
      60% {
        -ms-transform: translateX(-15px);
        transform: translateX(-15px);
      }
    }

				.youzify-offer-features {
				    margin-top: 25px;
				}

		        .youzify-log-content {
				    margin-bottom: 35px;
				}

				.youzify-log-item a {
					display: contents;
				}

				.youzify-log-item strong {
					display: contents;
					font-weight: 700;
				}

				.youzify-log-content:last-of-type {
				    margin-bottom: 0;
				}

	            .youzify-log-message {
	                display: flex;
	            }

	            .youzify-log-offers {
	                width: 35%;
	                padding: 0 60px;
	                margin-left: auto;
	                /*background: url(https://youzify.com/wp-content/uploads/2021/01/youzify_decor.svg) #f0f0f1;*/
	                background: url(https://youzify.com/wp-content/uploads/2021/01/youzify_decor.svg),linear-gradient(to right,#ffeb3b,#f44336) !important;
	                background-size: cover;
	            }

	            .youzify-log-offer {
	                width: 100%;
	                background: #fff;
	                margin-left: auto;
	                position: relative;
	                border-radius: 8px;
	                overflow: hidden;
	                margin: 25px 25px 25px auto;
	                background-size: cover !important;
	                -webkit-box-shadow: 0 8px 20px rgb(0 0 0 / 6%);
	                box-shadow: 0 8px 20px rgb(0 0 0 / 6%);
	            }

	            .youzify-log-offers .youzify-offer-label {
					display: block;
				    background: #ffeb3b;
				    text-align: center;
				    padding: 12px;
				    border-radius: 5px;
				    top: 0;
				    left: 0;
				    font-size: 14px;
				    text-transform: uppercase;
				    font-weight: 600;
	            }

	            .youzify-log-offers .youzify-offer-details {
	                padding: 25px;
	            }

	            .youzify-log-offers .youzify-offer-img img {
	               display: block;
	                width: 100%;
	            }

	            .youzify-log-offers .youzify-offer-title a {
	                text-decoration: none;
				    color: #000;
				    font-weight: 600;
				    margin-bottom: 10px;
				    display: block;
				    text-align: center;
				    font-size: 18px;
	            }

	            .youzify-offer-feature {
				    display: flex;
	            	margin-bottom: 15px;
				    align-items: center;
	            }

	            .youzify-offer-feature span {
					width: 30px;
					height: 30px;
					color: #000;
					margin-right: 10px;
					line-height: 30px;
					background: #ffd8be;
					border-radius: 100%;
	            }

	            .youzify-log-offers .youzify-offer-desc {
	                    font-size: 15px;
				    color: #898989;
				    text-align: center;
				    margin-top: 15px;
				    line-height: 24px;
	            }

	            .youzify-log-offers .youzify-offer-button a {
	                margin-top: 25px;
				    display: block;
				    background: #ffeb3b;
				    font-size: 19px;
				    font-weight: 600;
				    color: #000;
				    text-decoration: none;
				    line-height: 60px;
				    padding: 0 25px;
				    border-radius: 5px;
				    text-align: center;
				    margin-top: 25px;
	            }

	            .youzify-log-title {
	                font-size: 10px;
	                overflow: hidden;
	                border-radius: 3px;
	                margin-bottom: 10px;
	                display: inline-block;
	            }

	            .youzify-log-type {
	                padding: 5px;
	                display: inline-block;
	                font-weight: 600;
	            }

	            .youzify-title-label {
	                background: #f1f1f2;
	                padding: 5px 8px;
	                display: inline-block;
	                color: #777;
	                font-weight: 600;
	                text-transform: uppercase;
	            }

	            .youzify-free-label {
	                color: #fff;
	                min-width: 40px;
	                text-align: center;
	                background: #8bc34a;
	            }

	            .youzify-log-item span.youzify-log-pro {
	                min-width: 30px;
	                color: #000;
	                line-height: 24px;
	                background: #ffeb3b;
	                min-width: 65px;
	            }

	            .youzify-logs {
	                padding: 25px;
	                width: 60%;
	            }

	            .youzify-log {
	                background: #eee;
	                padding: 10px;
	                border-radius: 8px;
	            }

	            .youzify-log-item {
	                color: #777;
	                display: flex;
	                font-size: 13px;
	                font-weight: 600;
	                background: #fff;
	                margin-bottom: 12px;
	                padding: 10px;
	                border-radius: 5px;
	                line-height: 24px;
	            }

	            .youzify-log-item:last-of-type {
	                margin-bottom: 0;
	            }

	            .youzify-log-item span {
	                color: #fff;
	                font-size: 10px;
	                text-transform: uppercase;
	                padding: 2px 5px;
	                border-radius: 2px;
	                font-weight: 600;
	                margin-right: 10px;
	                min-width: 40px;
	                text-align: center;
	                height: 20px;
	                line-height: 20px;
	            }

	            .youzify-log-new {
	                background: #81d4fa;
	            }

	            .youzify-log-item .youzify-log-announce {
	            	background: yellow;
	            	color: #000;
	            	min-width: 65px;
	          	}

	            .youzify-log-fix {
	                background: #898989;
	            }


	            .youzify-log-improvement {
	                background: #ffc107;
	            }

	            .youzify-offer-coupon {
	            	color: #FFF;
				    padding: 25px;
			        border-radius: 8px;
			        background-size: contain;
			        background-image: linear-gradient(61deg ,#000000 0,#e617ff 100%);
	            }

	            .youzify-coupon-label {
					font-weight: 600;
					color: #e400ff;
					text-transform: uppercase;
					margin-bottom: 15px;
					border-left: 2px solid #da20ff;
					padding-left: 8px;
	            }

	            .youzify-coupon-desc {

	            }

	            .youzify-coupon-ends {
	            	margin-top: 10px;
	            	font-weight: 600;
	            }

	            .youzify-coupon-button a {
	            	background: #c4ff7f;
				    padding: 15px;
				    border-radius: 5px;
				    text-align: center;
				    color: #000;
				    font-weight: 600;
				    margin-top: 15px;
				    display: block;
				    text-decoration: none;
	            }

	            .yzp-cancel-changelog {
	            	float: right;
				    margin-top: 10px;
				    text-decoration: none;
	            }

	            .yzp-cancel-changelog span {
				    margin-left: 15px;
				    background: #eee;
				    width: 35px;
				    height: 35px;
				    line-height: 35px;
				    text-align: center;
				    border-radius: 50%;
	            }
	        </style>
	        <!-- <div style="margin-top: 35px;"> -->
	        	<?php // youzify_offer_banner( true ); ?>
	        <!-- </div> -->
	        <div class="updated notice">
        <a class="yzp-cancel-changelog"  href="<?php echo esc_url( add_query_arg( 'youzify-dismiss-offer-notice', $id, youzify_get_current_page_url() ) ); ?>"><span style="color:#000;" class="dashicons dashicons-no-alt"></span></a>
	            <div class="youzify-log-message">
	                <div class="youzify-logs">
	                	<?php foreach( $logs as $log ) : ?>
	                    <div class="youzify-log-content">
	                        <div class="youzify-log-title"><span class="youzify-log-type youzify-free-label"><?php echo $log['title']; ?></span><span class="youzify-title-label ">Change Log</span></div>
	                        <div class="youzify-log">
	                            <?php if ( isset( $log['log']['announce'] ) ) : foreach ( $log['log']['announce'] as $msg ) : ?>
	                                <div class="youzify-log-item youzify-log-announcement" ><span class="youzify-log-announce" style="background:red;color:#fff;">Important!!</span><?php echo $msg; ?></div>
	                            <?php endforeach; endif; ?>
	                            <?php if ( isset( $log['log']['new'] ) ) : foreach ( $log['log']['new'] as $msg ) : ?>
	                                <div class="youzify-log-item"><span class="youzify-log-new">New</span><?php echo $msg; ?></div>
	                            <?php endforeach; endif; ?>
	                            <?php if ( isset( $log['log']['fix'] ) ) : foreach ( $log['log']['fix'] as $msg ) : ?>
	                                <div class="youzify-log-item"><span class="youzify-log-fix">Fix</span><?php echo $msg; ?></div>
	                            <?php endforeach; endif; ?>
	                            <?php if ( isset( $log['log']['improvement'] ) ) : foreach ( $log['log']['improvement'] as $msg ) : ?>
	                                <div class="youzify-log-item"><span class="youzify-log-improvement">Improvement</span><?php echo $msg; ?></div>
	                            <?php endforeach; endif; ?>
	                        </div>
	                    </div>
	                        <?php endforeach; ?>

	                </div>
	                <div class="youzify-log-offers">
	                    <!-- <div class="youzify-log-offer">
	                            <div class="youzify-offer-label"><?php echo $offer['label']; ?></div>
	                        <div class="youzify-offer-img"><a href="<?php echo $offer['link']; ?>"><img src="<?php echo $offer['image']; ?>" alt=""></a></div>
	                        <div class="youzify-offer-details">
	                            <div class="youzify-offer-title"><a href="<?php echo $offer['link']; ?>"><?php echo $offer['title']; ?></a></div>
	                            <div class="youzify-offer-desc"><?php echo $offer['description']; ?></div>
	                            <div class="youzify-offer-features">
	                            	<?php foreach( $offer['features'] as $feature ) : ?>
	                            		<div class="youzify-offer-feature"><span class="dashicons dashicons-saved"></span><?php echo $feature; ?></div>
	                            	<?php endforeach; ?>
	                            	</div>
	                            <div class="youzify-offer-button"><a target="_blank" href="<?php echo $offer['link']; ?>"><span class="yz-shake-me">👉</span><?php echo $offer['button']; ?></a></div>
	                        </div>
	                    </div> -->

	                    <?php if( strtotime( $coupon['date'] ) > strtotime('now') ) : ?>
		                <div class="youzify-offer-coupon">
		                	<div class="youzify-coupon-label"><?php echo $coupon['label']; ?></div>
		                	<div class="youzify-coupon-desc"><?php echo $coupon['description']; ?></div>
		                	<div class="youzify-coupon-ends"><?php echo $coupon['ends']; ?></div>
		                	<div class="youzify-coupon-button"><a href="<?php echo $coupon['link']; ?>"><?php echo $coupon['button']; ?></div>
		                </div>
	                	<?php endif; ?>

	                </div>

	            </div>
	        </div>

	        <?php

	}
}

global $Youzify_Admin;

$Youzify_Admin = new Youzify_Admin();