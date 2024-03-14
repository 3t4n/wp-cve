<?php
/**
 * UM Custom Tab Builder Core.
 *
 * @since   1.0.0
 * @package UM_Custom_Tab_Builder
 */

/**
 * UM Custom Tab Builder Core.
 *
 * @since 1.0.0
 */
class UMCTB_Core {
	/**
	 * Parent plugin class.
	 *
	 * @since 1.0.0
	 *
	 * @var   UM_Custom_Tab_Builder
	 */
	protected $plugin = null;

	/**
	 * Tab Builder Post Type.
	 *
	 * @var string
	 */
	public $post_type = 'um_ctb';
	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 *
	 * @param  UM_Custom_Tab_Builder $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->post_type = 'um_ctb';
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  1.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'setup_post_type' ) );
		add_action( 'cmb2_init', array( $this, 'setup_metabox' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_assets' ) );
		add_action( 'save_post_um_ctb', array( $this, 'save_tab_settings' ), 10, 1 );
	}

	public function setup_post_type() {
		$labels = array(
			'name'               => _x( 'Tabs', 'post type general name', 'um-custom-tab-builder-lite' ),
			'singular_name'	     => _x( 'Tab', 'post type singular name', 'um-custom-tab-builder-lite' ),
			'menu_name'          => _x( 'Tabs', 'admin menu', 'um-custom-tab-builder-lite' ),
			'name_admin_bar'     => _x( 'Tab', 'add new on admin bar', 'um-custom-tab-builder-lite' ),
			'add_new'            => _x( 'Add New', 'tab', 'um-custom-tab-builder-lite' ),
			'add_new_item'       => __( 'Add New Tab', 'um-custom-tab-builder-lite' ),
			'new_item'           => __( 'New Tab', 'um-custom-tab-builder-lite' ),
			'edit_item'          => __( 'Edit Tab', 'um-custom-tab-builder-lite' ),
			'view_item'          => __( 'View Tab', 'um-custom-tab-builder-lite' ),
			'all_items'          => __( 'All Tabs', 'um-custom-tab-builder-lite' ),
			'search_items'       => __( 'Search Tabs', 'um-custom-tab-builder-lite' ),
			'parent_item_colon'  => __( 'Parent Tabs:', 'um-custom-tab-builder-lite' ),
			'not_found'          => __( 'No tabs found.', 'um-custom-tab-builder-lite' ),
			'not_found_in_trash' => __( 'No tabs found in Trash.', 'um-custom-tab-builder-lite' ),
		);

		$args = array(
			'labels'			 => $labels,
			'description'		 => __( 'Custom tabs for Ultimate Member.', 'um-custom-tab-builder-lite' ),
			'public'			 => false,
			'publicly_queryable' => false,
			'show_ui'			 => true,
			'show_in_menu'	     => true,
			'query_var'		     => true,
			'capability_type'	 => 'post',
			'has_archive'		 => false,
			'hierarchical'	     => true,
			'menu_position'	     => null,
			'supports'		     => array( 'title' ),
		);

		register_post_type( $this->post_type, $args );
	}


	/**
	 * Add tab to setting on new post save.
	 *
	 * @param integer $post_id
	 * @return void
	 */
	public function save_tab_settings( $post_id = 0 ) {
		// Autosave, do nothing.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// AJAX? Not used here.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}
		
		// Is revision? Do nothing.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		
		$slug = get_post_meta( $post_id, '_um_ctb_slug', true );

		// Slug not saved? Do nothing.
		if ( ! $slug ) {
			return;
		}
		
		$default_options = array(
			'profile_tab_' . $slug              => 1,
			'profile_tab_' . $slug . '_privacy' => 0,
		);

		$options = get_option( 'um_options', array() );
		
		foreach ( $default_options as $key => $value ) {
			//set new options to default
			if ( ! isset( $options[ $key ] ) ) {
				$options[ $key ] = $value;
			}
		}
		update_option( 'um_options', $options );
	}
	/**
	 * Setup metabox.
	 *
	 * @since NEXT
	 */
	public function setup_metabox() {
		$prefix = '_um_ctb_';
		
		$cmb = new_cmb2_box( array(
			'id'           => $prefix . 'metabox',
			'title'        => __( 'Tab Details', 'um-custom-tab-builder-lite' ),
			'object_types' => array( $this->post_type ),
			'context'      => 'normal',
			'priority'     => 'default',
		) );
		
		$cmb->add_field( array(
			'name' => __( 'Icon', 'um-custom-tab-builder-lite' ),
			'id' => $prefix . 'icon',
			'type'    => 'icon_picker',
			'options' => array(
				// The icons you want to use goes into this array:
				'icons' => array('um-faicon-glass','um-faicon-music','um-faicon-search','um-faicon-envelope-o','um-faicon-heart','um-faicon-star','um-faicon-star-o','um-faicon-user','um-faicon-film','um-faicon-th-large','um-faicon-th','um-faicon-th-list','um-faicon-check','um-faicon-times','um-faicon-search-plus','um-faicon-search-minus','um-faicon-power-off','um-faicon-signal','um-faicon-cog','um-faicon-trash-o','um-faicon-home','um-faicon-file-o','um-faicon-clock-o','um-faicon-road','um-faicon-download','um-faicon-arrow-circle-o-down','um-faicon-arrow-circle-o-up','um-faicon-inbox','um-faicon-play-circle-o','um-faicon-repeat','um-faicon-refresh','um-faicon-list-alt','um-faicon-lock','um-faicon-flag','um-faicon-headphones','um-faicon-volume-off','um-faicon-volume-down','um-faicon-volume-up','um-faicon-qrcode','um-faicon-barcode','um-faicon-tag','um-faicon-tags','um-faicon-book','um-faicon-bookmark','um-faicon-print','um-faicon-camera','um-faicon-font','um-faicon-bold','um-faicon-italic','um-faicon-text-height','um-faicon-text-width','um-faicon-align-left','um-faicon-align-center','um-faicon-align-right','um-faicon-align-justify','um-faicon-list','um-faicon-outdent','um-faicon-indent','um-faicon-video-camera','um-faicon-picture-o','um-faicon-pencil','um-faicon-map-marker','um-faicon-adjust','um-faicon-tint','um-faicon-pencil-square-o','um-faicon-share-square-o','um-faicon-check-square-o','um-faicon-arrows','um-faicon-step-backward','um-faicon-fast-backward','um-faicon-backward','um-faicon-play','um-faicon-pause','um-faicon-stop','um-faicon-forward','um-faicon-fast-forward','um-faicon-step-forward','um-faicon-eject','um-faicon-chevron-left','um-faicon-chevron-right','um-faicon-plus-circle','um-faicon-minus-circle','um-faicon-times-circle','um-faicon-check-circle','um-faicon-question-circle','um-faicon-info-circle','um-faicon-crosshairs','um-faicon-times-circle-o','um-faicon-check-circle-o','um-faicon-ban','um-faicon-arrow-left','um-faicon-arrow-right','um-faicon-arrow-up','um-faicon-arrow-down','um-faicon-share','um-faicon-expand','um-faicon-compress','um-faicon-plus','um-faicon-minus','um-faicon-asterisk','um-faicon-exclamation-circle','um-faicon-gift','um-faicon-leaf','um-faicon-fire','um-faicon-eye','um-faicon-eye-slash','um-faicon-exclamation-triangle','um-faicon-plane','um-faicon-calendar','um-faicon-random','um-faicon-comment','um-faicon-magnet','um-faicon-chevron-up','um-faicon-chevron-down','um-faicon-retweet','um-faicon-shopping-cart','um-faicon-folder','um-faicon-folder-open','um-faicon-arrows-v','um-faicon-arrows-h','um-faicon-bar-chart','um-faicon-twitter-square','um-faicon-facebook-square','um-faicon-camera-retro','um-faicon-key','um-faicon-cogs','um-faicon-comments','um-faicon-thumbs-o-up','um-faicon-thumbs-o-down','um-faicon-star-half','um-faicon-heart-o','um-faicon-sign-out','um-faicon-linkedin-square','um-faicon-thumb-tack','um-faicon-external-link','um-faicon-sign-in','um-faicon-trophy','um-faicon-github-square','um-faicon-upload','um-faicon-lemon-o','um-faicon-phone','um-faicon-square-o','um-faicon-bookmark-o','um-faicon-phone-square','um-faicon-twitter','um-faicon-facebook','um-faicon-github','um-faicon-unlock','um-faicon-credit-card','um-faicon-rss','um-faicon-hdd-o','um-faicon-bullhorn','um-faicon-bell','um-faicon-certificate','um-faicon-hand-o-right','um-faicon-hand-o-left','um-faicon-hand-o-up','um-faicon-hand-o-down','um-faicon-arrow-circle-left','um-faicon-arrow-circle-right','um-faicon-arrow-circle-up','um-faicon-arrow-circle-down','um-faicon-globe','um-faicon-wrench','um-faicon-tasks','um-faicon-filter','um-faicon-briefcase','um-faicon-arrows-alt','um-faicon-users','um-faicon-link','um-faicon-cloud','um-faicon-flask','um-faicon-scissors','um-faicon-files-o','um-faicon-paperclip','um-faicon-floppy-o','um-faicon-square','um-faicon-bars','um-faicon-list-ul','um-faicon-list-ol','um-faicon-strikethrough','um-faicon-underline','um-faicon-table','um-faicon-magic','um-faicon-truck','um-faicon-pinterest','um-faicon-pinterest-square','um-faicon-google-plus-square','um-faicon-google-plus','um-faicon-money','um-faicon-caret-down','um-faicon-caret-up','um-faicon-caret-left','um-faicon-caret-right','um-faicon-columns','um-faicon-sort','um-faicon-sort-desc','um-faicon-sort-asc','um-faicon-envelope','um-faicon-linkedin','um-faicon-undo','um-faicon-gavel','um-faicon-tachometer','um-faicon-comment-o','um-faicon-comments-o','um-faicon-bolt','um-faicon-sitemap','um-faicon-umbrella','um-faicon-clipboard','um-faicon-lightbulb-o','um-faicon-exchange','um-faicon-cloud-download','um-faicon-cloud-upload','um-faicon-user-md','um-faicon-stethoscope','um-faicon-suitcase','um-faicon-bell-o','um-faicon-coffee','um-faicon-cutlery','um-faicon-file-text-o','um-faicon-building-o','um-faicon-hospital-o','um-faicon-ambulance','um-faicon-medkit','um-faicon-fighter-jet','um-faicon-beer','um-faicon-h-square','um-faicon-plus-square','um-faicon-angle-double-left','um-faicon-angle-double-right','um-faicon-angle-double-up','um-faicon-angle-double-down','um-faicon-angle-left','um-faicon-angle-right','um-faicon-angle-up','um-faicon-angle-down','um-faicon-desktop','um-faicon-laptop','um-faicon-tablet','um-faicon-mobile','um-faicon-circle-o','um-faicon-quote-left','um-faicon-quote-right','um-faicon-spinner','um-faicon-circle','um-faicon-reply','um-faicon-github-alt','um-faicon-folder-o','um-faicon-folder-open-o','um-faicon-smile-o','um-faicon-frown-o','um-faicon-meh-o','um-faicon-gamepad','um-faicon-keyboard-o','um-faicon-flag-o','um-faicon-flag-checkered','um-faicon-terminal','um-faicon-code','um-faicon-reply-all','um-faicon-star-half-o','um-faicon-location-arrow','um-faicon-crop','um-faicon-code-fork','um-faicon-chain-broken','um-faicon-question','um-faicon-info','um-faicon-exclamation','um-faicon-superscript','um-faicon-subscript','um-faicon-eraser','um-faicon-puzzle-piece','um-faicon-microphone','um-faicon-microphone-slash','um-faicon-shield','um-faicon-calendar-o','um-faicon-fire-extinguisher','um-faicon-rocket','um-faicon-maxcdn','um-faicon-chevron-circle-left','um-faicon-chevron-circle-right','um-faicon-chevron-circle-up','um-faicon-chevron-circle-down','um-faicon-html5','um-faicon-css3','um-faicon-anchor','um-faicon-unlock-alt','um-faicon-bullseye','um-faicon-ellipsis-h','um-faicon-ellipsis-v','um-faicon-rss-square','um-faicon-play-circle','um-faicon-ticket','um-faicon-minus-square','um-faicon-minus-square-o','um-faicon-level-up','um-faicon-level-down','um-faicon-check-square','um-faicon-pencil-square','um-faicon-external-link-square','um-faicon-share-square','um-faicon-compass','um-faicon-caret-square-o-down','um-faicon-caret-square-o-up','um-faicon-caret-square-o-right','um-faicon-eur','um-faicon-gbp','um-faicon-usd','um-faicon-inr','um-faicon-jpy','um-faicon-rub','um-faicon-krw','um-faicon-btc','um-faicon-file','um-faicon-file-text','um-faicon-sort-alpha-asc','um-faicon-sort-alpha-desc','um-faicon-sort-amount-asc','um-faicon-sort-amount-desc','um-faicon-sort-numeric-asc','um-faicon-sort-numeric-desc','um-faicon-thumbs-up','um-faicon-thumbs-down','um-faicon-youtube-square','um-faicon-youtube','um-faicon-xing','um-faicon-xing-square','um-faicon-youtube-play','um-faicon-dropbox','um-faicon-stack-overflow','um-faicon-instagram','um-faicon-flickr','um-faicon-adn','um-faicon-bitbucket','um-faicon-bitbucket-square','um-faicon-tumblr','um-faicon-tumblr-square','um-faicon-long-arrow-down','um-faicon-long-arrow-up','um-faicon-long-arrow-left','um-faicon-long-arrow-right','um-faicon-apple','um-faicon-windows','um-faicon-android','um-faicon-linux','um-faicon-dribbble','um-faicon-skype','um-faicon-foursquare','um-faicon-trello','um-faicon-female','um-faicon-male','um-faicon-gittip','um-faicon-sun-o','um-faicon-moon-o','um-faicon-archive','um-faicon-bug','um-faicon-vk','um-faicon-weibo','um-faicon-renren','um-faicon-pagelines','um-faicon-stack-exchange','um-faicon-arrow-circle-o-right','um-faicon-arrow-circle-o-left','um-faicon-caret-square-o-left','um-faicon-dot-circle-o','um-faicon-wheelchair','um-faicon-vimeo-square','um-faicon-try','um-faicon-plus-square-o','um-faicon-space-shuttle','um-faicon-slack','um-faicon-envelope-square','um-faicon-wordpress','um-faicon-openid','um-faicon-university','um-faicon-graduation-cap','um-faicon-yahoo','um-faicon-google','um-faicon-reddit','um-faicon-reddit-square','um-faicon-stumbleupon-circle','um-faicon-stumbleupon','um-faicon-delicious','um-faicon-digg','um-faicon-pied-piper','um-faicon-pied-piper-alt','um-faicon-drupal','um-faicon-joomla','um-faicon-language','um-faicon-fax','um-faicon-building','um-faicon-child','um-faicon-paw','um-faicon-spoon','um-faicon-cube','um-faicon-cubes','um-faicon-behance','um-faicon-behance-square','um-faicon-steam','um-faicon-steam-square','um-faicon-recycle','um-faicon-car','um-faicon-taxi','um-faicon-tree','um-faicon-spotify','um-faicon-deviantart','um-faicon-soundcloud','um-faicon-database','um-faicon-file-pdf-o','um-faicon-file-word-o','um-faicon-file-excel-o','um-faicon-file-powerpoint-o','um-faicon-file-image-o','um-faicon-file-archive-o','um-faicon-file-audio-o','um-faicon-file-video-o','um-faicon-file-code-o','um-faicon-vine','um-faicon-codepen','um-faicon-jsfiddle','um-faicon-life-ring','um-faicon-circle-o-notch','um-faicon-rebel','um-faicon-empire','um-faicon-git-square','um-faicon-git','um-faicon-hacker-news','um-faicon-tencent-weibo','um-faicon-qq','um-faicon-weixin','um-faicon-paper-plane','um-faicon-paper-plane-o','um-faicon-history','um-faicon-circle-thin','um-faicon-header','um-faicon-paragraph','um-faicon-sliders','um-faicon-share-alt','um-faicon-share-alt-square','um-faicon-bomb','um-faicon-futbol-o','um-faicon-tty','um-faicon-binoculars','um-faicon-plug','um-faicon-slideshare','um-faicon-twitch','um-faicon-yelp','um-faicon-newspaper-o','um-faicon-wifi','um-faicon-calculator','um-faicon-paypal','um-faicon-google-wallet','um-faicon-cc-visa','um-faicon-cc-mastercard','um-faicon-cc-discover','um-faicon-cc-amex','um-faicon-cc-paypal','um-faicon-cc-stripe','um-faicon-bell-slash','um-faicon-bell-slash-o','um-faicon-trash','um-faicon-copyright','um-faicon-at','um-faicon-eyedropper','um-faicon-paint-brush','um-faicon-birthday-cake','um-faicon-area-chart','um-faicon-pie-chart','um-faicon-line-chart','um-faicon-lastfm','um-faicon-lastfm-square','um-faicon-toggle-off','um-faicon-toggle-on','um-faicon-bicycle','um-faicon-bus','um-faicon-ioxhost','um-faicon-angellist','um-faicon-cc','um-faicon-ils','um-faicon-meanpath'),
				// Add font-family to "fonts" !important; Note that you can use multiple iconfonts.
				'fonts' => array('FontAwesome'),
				'description' => __( 'Choose the icon to be displayed in the profile tab. Not available for groups. Scroll to see more options.')
			),	
		) );
		$cmb->add_field( array(
			'name'        => __( 'Slug', 'um-custom-tab-builder-lite' ),
			'id'          => $prefix . 'slug',
			'type'        => 'text_medium',
			'description' => __( 'Choose the slug for the tab', 'um-custom-tab-builder-lite' )
		) );

		$cmb->add_field( array(
			'name'               => __( ' Private Tab', 'um-custom-tab-builder-lite' ),
			'id'                 => $prefix . 'private',
			'type'               => 'checkbox',
			'desc'               => __( 'Only owners of the profile can view tab.', 'um-custom-tab-builder-lite' ),
		) );

		$cmb->add_field( array(
			'name'              => __( 'Roles with this tab', 'um-custom-tab-builder-lite' ),
			'id'                => $prefix . 'roles_own',
			'type'              => 'multicheck',
			'select_all_button' => true,
			'options'           => $this->get_user_roles(),
			'description'       => __( 'Leave unchecked to allow all.', 'um-custom-tab-builder-lite' )
		) );

		$cmb->add_field( array(
			'name'               => __( ' Roles that can view this tab', 'um-custom-tab-builder-lite' ),
			'id'                 => $prefix . 'roles_view',
			'type'               => 'multicheck',
			'select_all_button'  => true,
			'options'            => $this->get_user_roles(),
			'description'       => __( 'Leave unchecked to allow all.', 'um-custom-tab-builder-lite' )
		) );
		
		$type_options = array();
		$type_options['profile'] = __( 'Profile', 'um-custom-tab-builder-lite' );
		if ( class_exists( 'UM_Groups' ) ) {
			//$type_options['profile'] = __( 'Group', 'um-custom-tab-builder-lite' );
		}
		$cmb->add_field( array(
			'name'    => __( 'Tab Type', 'um-custom-tab-builder-lite' ),
			'id'      => $prefix . 'tab_type',
			'type'    => 'select',
			'default' => 'profile',
			'options' => $type_options,
			'description' => __( 'What type of tab is being created? Pro version and Groups extension needed to use group tab.', 'um-custom-tab-builder-lite' )
		) );

		$cmb->add_field( array(
			'name'    => __( 'Content Type', 'um-custom-tab-builder-lite' ),
			'id'      => $prefix . 'content_type',
			'type'    => 'select',
			'options' => $this->get_content_types(),
			'description' => __( 'What type of content will be placed in tab? UM Form not available for group tab types.', 'um-custom-tab-builder-lite' )
		) );


		$cmb->add_field( array(
			'name'    => __( 'Shortcode', 'um-custom-tab-builder-lite' ),
			'id'      => $prefix . 'type_shortcode',
			'type'    => 'textarea',
			'classes' => 'um-ctb--hide',
			'description' => __( 'Add shortcodes and shorttags here. Use UM Form type for UM Forms.', 'um-custom-tab-builder-lite' )
		) );

		$cmb->add_field( array(
			'name'    => __( 'Content', 'um-custom-tab-builder-lite' ),
			'id'      => $prefix . 'type_content',
			'type'    => 'wysiwyg',
			'classes' => 'um-ctb--hide',
			'description' => __( 'Add html content, shortcodes and shorttags here.', 'um-custom-tab-builder-lite' )
		) );
	}

	public function get_user_roles() {
		global $ultimatemember;
		$roles = function_exists( 'UM' ) ? UM()->roles()->get_roles() : $ultimatemember->query->get_roles();

		return $roles;
	}

	public function get_content_types() {
		$content_types = array(
			'shortcode' => __( 'Shortcode', 'um-custom-tab-builder-lite' ),
			'content'   => __( 'Content', 'um-custom-tab-builder-lite' ), 
		);
		return $content_types;
	}

	public function get_parent_tabs() {
		return array();
	}

	public function add_admin_assets() {
		$screen = get_current_screen();
		if ( ! isset( $screen->post_type ) || $this->post_type !== $screen->post_type ) {
			return;
		}

		wp_enqueue_script( 'um_ctb_admin', um_ctb_lite()->url( 'assets/js/um_ctb.js' ), array( 'jquery' ), UM_Custom_Tab_Builder_Lite::VERSION );
		if ( defined( 'um_url' ) ) {
			wp_enqueue_style( 'um_ctb_admin-um-fonts-styles', um_url .  'assets/css/um-fonticons-fa.css' );
		}
		wp_enqueue_style( 'um_ctb_admin-styles', um_ctb_lite()->url( 'assets/css/um_ctb-admin.css' ) );
	}
}
