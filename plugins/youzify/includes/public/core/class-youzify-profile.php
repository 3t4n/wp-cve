<?php

class Youzify_Profile {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 *
	 * @since 3.0.0
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
			self::$instance->init();
		}

		return self::$instance;
	}

	function __construct() {}

	/**
	 * Init Profile.
	 */
	function init() {

		// Call Tabs.
		add_action( 'youzify_profile_main_column', array( $this, 'get_tabs' ) );

		// Profile Custom Styling
		$styling = youzify_styling();
		$styling->custom_styling( 'profile' );
		$styling->custom_snippets( 'profile' );
		unset( $styling );

		

		add_action( 'wp_head', array( $this, 'open_graph' ) );

		// Load Profile Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'profile_scripts' ) );

		// Profile Navbar Content
		if ( youzify_get_profile_layout() == 'youzify-horizontal-layout' ) {
				add_action( 'youzify_profile_navbar', array( $this, 'navbar' ) );
		} else {
			if ( 'wild-navbar' == youzify_option( 'youzify_vertical_layout_navbar_type', 'wild-navbar' ) ) {
				add_action( 'youzify_profile_before_header', array( $this, 'navbar' ) );
			} else {
				add_action( 'youzify_profile_main_column', array( $this, 'navbar' ), 1 );
			}
		}

		// Profile Main Content
		add_action( 'youzify_profile_main_content', array( $this, 'profile_main_content' ) );

	}

	/**
	 * Navbar Menu.
	 */
	function navbar() {

		if ( ! apply_filters( 'youzify_display_profile_navbar', true ) ) {
			return;
		}

		// Get Navbar Options.
		$navbar_effect = youzify_option( 'youzify_navbar_load_effect', 'fadeIn' );

		// Get Navbar Data.
		$navbar_data  = youzify_widgets()->get_loading_effect( $navbar_effect );

		echo "<nav id='youzify-profile-navmenu' class='" . $this->get_navbar_class() . "' $navbar_data>";
		echo '<div class="youzify-inner-content">';

		// Get Toogle Menu Code.
		echo apply_filters( 'youzify_profile_navbar_toggle_menu', '<div class="youzify-open-nav"><button class="youzify-responsive-menu"><span>toggle menu</span></button></div>' );


		// Get Primary Navigation Menu
		youzify_profile_navigation_menu();

	    // Get Account Settings
		$this->account_settings_menu();

		echo '</div></nav>';

	}

	/**
	 * Navbar Settings Menu.
	 */
	function account_settings_menu() {

	    do_action( 'youzify_profile_navbar_right_area' );

	    // Get Header Layout.
	    $header_layout = youzify_get_profile_layout();

	    if ( ! bp_is_my_profile() && 'youzify-horizontal-layout' == $header_layout  ) {
	        youzify_get_social_buttons();
	        return;
	    }

	    if ( ! bp_is_my_profile() ) {
	        return;
	    }

    	if ( apply_filters( 'youzify_display_user_profile_navigation_right_menu', true ) ) {

	    ?>

	    <div class="youzify-settings-area">

	        <?php

	            // Get Navbar Quick Buttons.
	            if ( 'youzify-horizontal-layout' == $header_layout || youzify_is_wild_navbar_active() ) {
	                youzify_user_quick_buttons( bp_loggedin_user_id() );
	            }

	        ?>

	        <?php if ( apply_filters( 'youzify_display_user_profile_quick_menu', true ) ):  ?>
		        <div class="youzify-nav-settings">
		            <div class="youzify-settings-img"><?php echo bp_core_fetch_avatar( array( 'item_id' => bp_displayed_user_id(), 'type' => 'thumb', 'width' => 35, 'height' => 35 ) ); ?></div><i class="fas fa-angle-down youzify-settings-icon"></i></div>
		        <?php $this->user_settings_menu(); ?>
	        <?php endif; ?>

	    </div>

	    <?php

    	}
	}

	/**
	 * User Settings Menu.
	 */
	function user_settings_menu( $user_id = null ) {

	    // Get User ID.
	    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

		// New Array
		$links = array();
		$is_xprofile_active = bp_is_active( 'xprofile' );
		$is_settings_active = bp_is_active( 'settings' );

		// Profile Settings
		if ( $is_xprofile_active ) {

			$links['profile'] = array(
				'icon'	=> 'fas fa-user',
				'href'	=> youzify_get_profile_settings_url( false, $user_id ),
				'title'	=> __( 'Profile Settings', 'youzify' )
			);

		}

		// Account Settings
    	if ( $is_settings_active ) {
			$links['account'] = array(
				'icon'	=> 'fas fa-cogs',
				'href'	=> bp_members_get_user_url( $user_id ) . bp_get_settings_slug(),
				'title'	=> __( 'Account Settings', 'youzify' )
			);
    	}

		// Widgets Settings
    	if ( apply_filters( 'youzify_create_widgets_settings_page', true ) ) {
			$links['widgets'] = array(
				'icon'	=> 'fas fa-sliders-h',
				'href'	=> youzify_get_widgets_settings_url( false, $user_id ),
				'title'	=> __( 'Widgets Settings', 'youzify' )
			);
    	}

		if ( $is_xprofile_active ) {

			// Change Photo Link
			$links['change-photo'] = array(
				'icon'	=> 'fas fa-camera-retro',
				'href'	=> youzify_get_profile_settings_url( 'change-avatar', $user_id ),
				'title'	=> __( 'Change Avatar', 'youzify' )
			);
		}

		if ( $is_settings_active ) {

			// Change Password Link
			$links['change-password'] = array(
				'icon'	=> 'fas fa-lock',
				'href'	=> bp_members_get_user_url( $user_id ) . bp_get_settings_slug() . '/general',
				'title'	=> __( 'Change Password', 'youzify' )
			);

		}

		// Logout Link
		$links['logout'] = array(
			'icon'	=> 'fas fa-power-off',
			'href'	=> wp_logout_url(),
			'title'	=> __( 'Logout', 'youzify' )
		);

		// Filter.
		$links = apply_filters( 'youzify_get_profile_account_menu', $links, $user_id );

		?>

		<div class="youzify-settings-menu">
			<?php foreach ( $links as $link ) : ?>
				<a href="<?php echo esc_url( $link['href'] ); ?>">
					<div class="youzify-icon"><i class="<?php echo $link['icon'];?>"></i></div>
					<span class="youzify-button-title"><?php echo $link['title']; ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<?php
	}

	/**
	 * Navbar Class.
	 */
	function get_navbar_class() {

		// Create Empty Array.
		$navbar_class = array();

		$navbar_effect = youzify_option( 'youzify_navbar_load_effect', 'fadeIn' );

		// Add Header Main Class
		$navbar_class[] = youzify_widgets()->get_loading_effect( $navbar_effect, 'navbar' );

		// Get Icons Style
		$navbar_class[] = 'youzify-' . youzify_option( 'youzify_navbar_icons_style', 'navbar-inline-icons' );

		if ( 'youzify-horizontal-layout' == youzify_get_profile_layout() ) {

			// Get Options.
			$header_layout = youzify_option( 'youzify_header_layout', 'hdr-v1' );

			// Add a class depending on another one.
			if ( 'hdr-v2' == $header_layout || 'hdr-v7' == $header_layout ) {
				$navbar_class[] = 'youzify-boxed-navbar';
			}

		} else {
			$navbar_class[] = 'youzify-boxed-navbar';
		}

	 	// Return Class Name.
		return youzify_generate_class( $navbar_class );
	}

	/**
	 * Profile Main Content.
	 */
	function profile_main_content() {

        // Hide sidebar if profile is private.
        $show_profile = youzify_display_profile();

        if ( ! $show_profile && youzify_get_profile_layout() == 'youzify-horizontal-layout' ) {
        	youzify_private_account_message();
        	return;
        }

        // Get Main Profile Layout
        $layout = youzify_option( 'youzify_profile_layout', 'youzify-right-sidebar' );

        if ( $layout == 'youzify-3columns' && apply_filters( 'youzify_disable_3columns_sidebar', true ) ) {
        	$accepted_3columns_tabs= array( 'activity' => 1, 'overview' => 1, 'info' => 1 );
        	if ( ! isset( $accepted_3columns_tabs[ bp_current_component() ] ) ) {
       			$layout = youzify_option( 'youzify_profile_main_sidebar', 'youzify-right-sidebar' );
        	}
        }

		?>

		<div class="<?php echo $layout; ?>-layout">

			<?php do_action( 'youzify_before_profile_layout' ); ?>

			<div class="youzify-main-column grid-column">
				<?php
					if ( ! $show_profile ) {
			            youzify_private_account_message();
			        } else {
			        	do_action( 'youzify_profile_main_column' );
			        }
		        ?>
			</div>

			<?php if ( apply_filters( 'youzify_display_profile_sidebar', true ) ) : ?>

			<?php if ( $layout != 'youzify-right-sidebar' ) : ?>
			<div class="youzify-sidebar-column grid-column youzify-profile-sidebar youzify-left-sidebar"><?php $this->sidebar_widgets( 'left', youzify_option( 'youzify_profile_left_sidebar_widgets' ), $show_profile ); ?>
			</div><?php endif; ?>

			<?php if ( $layout != 'youzify-left-sidebar' ) : ?>
			<div class="youzify-sidebar-column grid-column youzify-profile-sidebar youzify-right-sidebar"><?php $this->sidebar_widgets( 'right', youzify_option( 'youzify_profile_sidebar_widgets', array(
					'login'          		 => 'visible',
			        'user_balance'    		 => 'visible',
			        'user_badges'     		 => 'visible',
			        'gamipress_user_balance' => 'visible',
			        'gamipress_user_badges'  => 'visible',
			        'about_me'        		 => 'visible',
			        'wall_media'      		 => 'visible',
			        'social_networks' 		 => 'visible',
			        'friends'         		 => 'visible',
			        'flickr'          		 => 'visible',
			        'groups'         		 => 'visible',
			        'recent_posts'    		 => 'visible',
			        'user_tags'       		 => 'visible',
			        'email'           		 => 'visible',
			        'address'         		 => 'visible',
			        'website'         		 => 'visible',
			        'phone'           		 => 'visible'
			    ) ), $show_profile ); ?></div>
			<?php endif; endif; ?>

		</div>

		<?php

		do_action( 'youzify_profile_content' );

	}

	/**
	 * Sidebar Content .
	 */
	function sidebar_widgets( $position, $sidebar_widgets = false, $show_profile = true ) {

		do_action( 'youzify_before_' . $position . '_sidebar_widgets', $position );

		if ( $show_profile ) {

	        $sidebar_widgets = apply_filters( 'youzify_profile_sidebar_widgets', $sidebar_widgets );

			// Get Widget Content.
			if ( $position == 'right' ) {
				do_action( 'youzify_profile_sidebar' );
			}

	        if ( ! empty( $sidebar_widgets ) ) {
				youzify_widgets()->get_widget_content( $sidebar_widgets );
	        }

		}

		do_action( 'youzify_after_' . $position . '_sidebar_widgets', $position );

	}

	/**
	 * Add Profiles Open Graph Support.
	 */
	function open_graph() {

	    if ( bp_is_single_activity() ) {
	        return false;
	    }

	    // Get Displayed Profile user id.
	    $user_id = bp_displayed_user_id();

	    // Get Username
	    $user_name = bp_core_get_user_displayname( $user_id );

	    // Get User Cover Image
	    $user_image = apply_filters( 'youzify_og_profile_cover_image', bp_attachments_get_attachment( 'url', array( 'object_dir' => 'members', 'item_id' => $user_id ) ) );

	    // Get Avatar if Cover Not found.
	    if ( empty( $user_image ) ) {
	        $user_image = apply_filters( 'youzify_og_profile_default_thumbnail', null );
	    }

	    // Get User Description.
	    $user_desc = get_the_author_meta( 'description', $user_id );

	    // Get Page Url !
	    $url = bp_members_get_user_url( $user_id );

	    // if description empty get about me description.
	    if ( empty( $user_desc ) ) {
	        $user_desc = get_the_author_meta( 'youzify_wg_about_me_bio', $user_id );
	    }

	    youzify_get_open_graph_tags( 'profile', $url, $user_name, $user_desc, $user_image );

	}

	/**
	 * Profile Scripts .
	 */
	function profile_scripts() {

        // Load Profile Schemes.
        wp_enqueue_style( 'youzify-schemes' );

        // Load Profile Style
        wp_enqueue_style( 'youzify-profile', YOUZIFY_ASSETS . 'css/youzify-profile.min.css', array(), YOUZIFY_VERSION );

        // Load Profile Script.
	    wp_enqueue_script( 'youzify-profile', YOUZIFY_ASSETS . 'js/youzify-profile.min.js', array( 'jquery', 'jquery-effects-fade' ), YOUZIFY_VERSION, true );

        // If Effects are enabled active effects scripts.
        if ( 'on' == youzify_option( 'youzify_use_effects', 'off' ) ) {
            // Profile Animation CSS
            wp_enqueue_style( 'youzify-animation', YOUZIFY_ASSETS . 'css/youzify-animate.min.css', array(), YOUZIFY_VERSION );
	        // Load View Port Checker Script
	        wp_enqueue_script( 'youzify-viewchecker', YOUZIFY_ASSETS . 'js/youzify-viewportChecker.min.js', array( 'jquery' ), YOUZIFY_VERSION, true  );
        }

	}

	/**
	 * Get Tabs Content
	 */
	public function get_tabs() {

		// Show Private Account Message.
		if ( ! youzify_display_profile() ) {
			youzify_private_account_message();
			return false;
		}

		// If page is single activity show single activity template.
	    if ( bp_is_single_activity() ) {
	        youzify_get_single_wall_post();
	        return;
	    }

		/**
		 * Fires before the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_body' );

		if ( bp_is_user_front() ) :
			bp_displayed_user_front_template_part();

		elseif ( bp_is_user_activity() ) :
			bp_get_template_part( 'members/single/activity' );

		elseif ( bp_is_user_blogs() ) :
			bp_get_template_part( 'members/single/blogs'    );

		elseif ( bp_is_user_friends() ) :
			bp_get_template_part( 'members/single/friends'  );

		elseif ( bp_is_user_groups() ) :
			bp_get_template_part( 'members/single/groups'   );

		elseif ( bp_is_user_messages() ) :
			bp_get_template_part( 'members/single/messages' );

		elseif ( bp_is_user_profile() ) :
			bp_get_template_part( 'members/single/profile'  );

		elseif ( bp_is_user_notifications() ) :
			bp_get_template_part( 'members/single/notifications' );

		elseif ( bp_is_user_members_invitations() ) :
			bp_get_template_part( 'members/single/invitations' );

		elseif ( bp_is_user_settings() ) :
			bp_get_template_part( 'members/single/settings' );


		// If nothing sticks, load a generic template
		else :
			bp_get_template_part( 'members/single/plugins'  );

		endif;

		/**
		 * Fires after the display of member body content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_body' );

	}

}

/**
 * Get a unique instance of Youzify Profile.
 */
function youzify_profile() {
	return Youzify_Profile::get_instance();
}

/**
 * Launch Youzify Profile!
 */
youzify_profile();