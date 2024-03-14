<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Admin {

    
	protected static $instance = null;

    
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	
	public function __construct() {
		
		if ( ! $this->validate_settings() ) {
			return;
		}
		
		/*
		 * Admin hooks.  This class is only included if is_admin() = true
		 */
		
		// Admin assets
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		
		// Admin page
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		add_action( 'admin_init', array( $this, 'handle_backup' ) );
		add_action( 'admin_init', array( $this, 'handle_settings_export' ) );
		
		// Ajax actions
		add_action( 'wp_ajax_social_rocket_recalc_all', array( $this, 'tools_recalc_all' ) );
		add_action( 'wp_ajax_social_rocket_tweet_settings_delete', array( $this, 'tweet_settings_delete' ) );
		add_action( 'wp_ajax_social_rocket_tweet_settings_load', array( $this, 'tweet_settings_load' ) );
		add_action( 'wp_ajax_social_rocket_tweet_settings_save', array( $this, 'tweet_settings_save' ) );
		add_action( 'wp_ajax_social_rocket_tweet_settings_update', array( $this, 'tweet_settings_update' ) );
		
		// Attachments
		add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_display' ), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_save' ), 10, 2 );
		add_filter( 'wp_prepare_attachment_for_js', array( $this, 'attachment_fields_js_data' ), 10, 3 );
		
		// Posts/Pages columns
		add_filter( 'manage_posts_columns', array( $this, 'add_shares_column' ) );
		add_filter( 'manage_pages_columns', array( $this, 'add_shares_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'add_shares_column_content' ), 10, 2 );
		add_action( 'manage_pages_custom_column', array( $this, 'add_shares_column_content' ), 10, 2 );
		add_filter( 'manage_edit-post_sortable_columns', array( $this, 'add_shares_column_sortable' ) );
		add_filter( 'manage_edit-page_sortable_columns', array( $this, 'add_shares_column_sortable' ) );
		add_action( 'pre_get_posts', array( $this, 'add_shares_column_sort_query' ) );
		
		// Posts/Pages metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ) );
		
		// Taxonomies metaboxes
		add_action( 'admin_init', array( $this, 'add_taxonomy_metabox' ) );
		add_action( 'edit_term', array( $this, 'taxonomy_metabox_save' ) );
		
		// TinyMCE buttons
		add_filter( 'mce_buttons', array( $this, 'tinymce_register_button' ) );
		add_filter( 'mce_external_plugins', array( $this, 'tinymce_register_plugin' ) );
		add_filter( 'tiny_mce_version', array( $this, 'tinymce_force_refresh' ) );
		
		// User profile fields
		add_action( 'edit_user_profile', array( $this , 'user_profile_fields_display' ) );
		add_action( 'show_user_profile', array( $this , 'user_profile_fields_display' ) );
		add_action( 'edit_user_profile_update', array( $this , 'user_profile_fields_save' ) );
		add_action( 'personal_options_update', array( $this , 'user_profile_fields_save' ) );
		
	}
	
	// caution: passing by reference here creates the array key you're looking for if it doesn't exist --DG
	public static function _isset( &$var, $default = null ) {
		return isset( $var ) ? $var: $default;
	}
	
	// this will not alter $array in any way, but it may not be as convenient as $this->_isset() when dealing with multi-dimensional arrays --DG
	public static function _issetarr( $array, $key, $default = null ) {
	    return isset( $array[$key] ) ? $array[$key] : $default;
	}
	
	/*
	 * _stripslashes_deep.
	 *
	 * @version 1.3.3
	 * @since   1.0.0
	 */
	public static function _stripslashes_deep( $value ) {
		$value = is_array( $value ) ?
					array_map( array( 'Social_Rocket_Admin', '_stripslashes_deep' ), $value ) :
					wp_kses_post( stripslashes( $value ) );
		return $value;
	}
	
	
	public function add_menu_pages() {
		
		add_menu_page(
			'Social Rocket',
			'Social Rocket',
			'manage_options',
			'social_rocket',
			array( $this, 'admin_settings_page_get_started' ),
			'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="#82878c" d="M1504 448q0-40-28-68t-68-28-68 28-28 68 28 68 68 28 68-28 28-68zm224-288q0 249-75.5 430.5t-253.5 360.5q-81 80-195 176l-20 379q-2 16-16 26l-384 224q-7 4-16 4-12 0-23-9l-64-64q-13-14-8-32l85-276-281-281-276 85q-3 1-9 1-14 0-23-9l-64-64q-17-19-5-39l224-384q10-14 26-16l379-20q96-114 176-195 188-187 358-258t431-71q14 0 24 9.5t10 22.5z"/></svg>' ),
			'99.001'
		);
		
		$submenu_pages = apply_filters( 'social_rocket_admin_submenu_pages', array(
			array(
				'menu_slug'  => 'social_rocket',
				'menu_title' => 'Get Started',
				'callback'   => array( $this, 'admin_settings_page_get_started' ),
			),
			array(
				'menu_slug'  => 'social_rocket_inline_buttons',
				'menu_title' => 'Inline Buttons',
				'callback'   => array( $this, 'admin_settings_page_inline_buttons' ),
			),
			array(
				'menu_slug'  => 'social_rocket_floating_buttons',
				'menu_title' => 'Floating Buttons',
				'callback'   => array( $this, 'admin_settings_page_floating_buttons' ),
			),
			array(
				'menu_slug'  => 'social_rocket_click_to_tweet',
				'menu_title' => 'Click to Tweet',
				'callback'   => array( $this, 'admin_settings_page_click_to_tweet' ),
			),
			array(
				'menu_slug'  => 'social_rocket_settings',
				'menu_title' => 'Settings',
				'callback'   => array( $this, 'admin_settings_page_settings' ),
			),
			array(
				'menu_slug'  => 'social_rocket_license_keys',
				'menu_title' => 'License Keys',
				'callback'   => array( $this, 'admin_settings_page_license_keys' ),
			),
		) );
		
		foreach ( $submenu_pages as $submenu_page ) {
			add_submenu_page( 'social_rocket', 'Social Rocket Settings', $submenu_page['menu_title'], 'manage_options', $submenu_page['menu_slug'], $submenu_page['callback'] );
		}
		
		add_submenu_page( 'social_rocket_callbacks', 'Social Rocket Callbacks', 'Social Rocket Callbacks', 'manage_options', 'social_rocket_callbacks', array( $this, 'admin_callbacks_page_display' ) );
		
	}
	
	
	public function add_metabox() {
	
		$post_types = Social_Rocket::get_post_types();
		
		foreach ( $post_types as $post_type ) {
			if ( $post_type === 'attachment' ) {
				continue;
			}
			add_meta_box(
				'social_rocket_metabox',
				'Social Rocket Settings',
				array( $this, 'metabox_display' ),
				$post_type,
				'normal',
				'high'
			);
		}
		
	}
	
	
	public function add_shares_column( $columns ) {
		$columns['social_rocket_shares'] = '<svg style="display: inline; vertical-align: middle;" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="#444" d="M1504 448q0-40-28-68t-68-28-68 28-28 68 28 68 68 28 68-28 28-68zm224-288q0 249-75.5 430.5t-253.5 360.5q-81 80-195 176l-20 379q-2 16-16 26l-384 224q-7 4-16 4-12 0-23-9l-64-64q-13-14-8-32l85-276-281-281-276 85q-3 1-9 1-14 0-23-9l-64-64q-17-19-5-39l224-384q10-14 26-16l379-20q96-114 176-195 188-187 358-258t431-71q14 0 24 9.5t10 22.5z"/></svg> Shares';
		return $columns;
	}
	
	
	public function add_shares_column_content( $column, $post_id ) {
		if ( $column === 'social_rocket_shares' ) {
			$total = get_post_meta( $post_id, 'social_rocket_total_shares', true );
			echo (int)$total;
		}
	}
	
	
	public function add_shares_column_sortable( $columns ) {
		$columns['social_rocket_shares'] = 'social_rocket_total_shares';
		return $columns;
	}
	
	
	public function add_shares_column_sort_query( $query ) {
		if ( ! is_admin() ) {
			return false;
		}
		$orderby = $query->get( 'orderby');
		if ( $orderby === 'social_rocket_total_shares' ) {
			$query->set( 'meta_key', 'social_rocket_total_shares' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
	
	
	public function add_taxonomy_metabox() {
		
		$taxonomies = get_taxonomies(
			array(
				'public'      => true,
				'_builtin'    => false,
			),
			'names'
		);
		$taxonomies[] = 'category';
		$taxonomies[] = 'post_tag';
		
		$taxonomies = apply_filters( 'social_rocket_add_taxonomy_metabox', $taxonomies );
		
		foreach ( $taxonomies as $taxonomy ) {
			add_action( $taxonomy . '_edit_form', array( $this, 'taxonomy_metabox_display' ), 10, 1 );
		}
		
	}
	
	
	public function admin_callbacks_page_display() {
		#region admin_callbacks_page_display
		?>
		<div class="social-rocket-settings-header">
			
			<h1><?php _e( 'Social Rocket', 'social-rocket' ); ?></h1>
			
			<?php do_action( 'social_rocket_admin_callbacks' ); ?>
			
			<p>&nbsp;</p>
			
			<p><a class="button-primary" href="#" onclick="window.close();"><?php _e( 'Close', 'social-rocket' ); ?></a></p>
				
		</div>
		<?php
		#endregion admin_callbacks_page_display
	}
	
	
	/**
	 * Outputs opening & header code used by all settings pages.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_header() {
		#region admin_settings_header
		?>
		<div class="wrap">
		
			<?php /* keep this */ ?>
			<h1 style="display:none;"></h1>
			<?php /* warning notices will display here */ ?>
		
		<form action="" enctype="multipart/form-data" method="post" id="social-rocket-settings">
		
			<div class="sr-grid">
			
				<div id="social-rocket-settings-content" class="sr-grid__col sr-grid__col--8-of-12">
				
					<div class="social-rocket-settings-header">
					
						<div class="social-rocket-settings-header-logo">
							<a href="<?php echo admin_url( 'admin.php?page=social_rocket' ); ?>"><img src="<?php echo apply_filters( 'social_rocket_settings_logo', plugin_dir_url( dirname( __FILE__ ) ) . 'img/social-rocket-logo.png' ); ?>" alt="Social Rocket" /></a>
							<span>v<?php echo SOCIAL_ROCKET_VERSION; ?></span>
						</div>
						
					</div>
		<?php
		#endregion admin_settings_header
	}
	
	
	/**
	 * Outputs closing & footer code used by all settings pages.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_footer( $save_button = true ) {
		#region admin_settings_footer
		?>
				</div>
			
				<div id="social-rocket-settings-content-sidebar" class="sr-grid__col sr-grid__col--3-of-12 sr-grid__col--push-1-of-12">
				
					<?php if ( $save_button ): ?>
					<div id="social_rocket_big_save_button">
						<input type="submit" name="social_rocket_save" class="button-secondary" value="Save" />
					</div>
					<?php endif; ?>
					
					<?php do_action( 'social_rocket_settings_sidebar_before' ); ?>
					
					<?php echo apply_filters( 'social_rocket_settings_sidebar_html', '
					<div class="social_rocket_sidebar_item">
						<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_sidebar_1&utm_campaign=Free" target="_blank">
							<img src="' . plugin_dir_url( dirname( __FILE__ ) ) . 'img/sidebar-1.png' . '" alt="" />
						</a>
					</div>
					<p>&nbsp;</p>
					' ); ?>
					
					<p class="description"><?php printf( __( 'Need help? <a href="%s" target="_blank">Read the Documentation</a> or <a href="%s" target="_blank">Visit our Support Site</a>.', 'social-rocket' ), 'https://docs.wpsocialrocket.com/', 'https://wpsocialrocket.com/support/?utm_source=Plugin&utm_content=settings_sidebar_help&utm_campaign=Free' ); ?></p>
					<p class="description"><?php _e( 'Opening a support ticket? Get your System Information by clicking the button below:', 'social-rocket' ); ?></p>
					<button type="button" id="social_rocket_show_system_info" class="button-secondary"><?php _e( 'Get System Info', 'social-rocket' ); ?></button>
					<div id="social_rocket_system_info" style="display: none;">
						<p><?php _e( 'Press Ctrl + C (PC) or Cmd + C (Mac) to copy this information.', 'social-rocket' ); ?></p>
						<pre><textarea rows="10" readonly="readonly" onclick="this.focus(); this.select()"><?php
							echo $this->get_system_info();
						?></textarea></pre>
					</div>
					
					<?php do_action( 'social_rocket_settings_sidebar_after' ); ?>
					
				</div>
			
			</div>
			
			<?php wp_nonce_field( 'social_rocket_settings' ); ?>
			
		</form>
		
		</div>
		<?php
		#endregion admin_settings_footer
	}
	
	
	/**
	 * Outputs network-specific settings thickbox popup code.
	 *
	 * @since 1.0.0
	 *
	 * @param string $scope    Internal name used in class names, keys, etc.
	 * @param array  $settings Settings group used to populate existing values, if any.
	 */
	public function admin_settings_network_popups( $scope, $settings ) {
		#region admin_settings_network_popups
		
		$SR = Social_Rocket::get_instance();
		
		foreach ( $SR->networks as $network => $network_name ) {
			
			if ( property_exists( 'Social_Rocket_'.ucfirst($network), 'configurable_settings' ) ) {
			
				$SRN = call_user_func( array( 'Social_Rocket_'.ucfirst($network), 'get_instance' ) );
				$configurable_settings = $SRN->configurable_settings;
				
				$colorpicker_first = false; // the first colorpicker in the list will get some extra code
				?>
				<div id="social-rocket-settings-<?php echo $scope; ?>-network-<?php echo $network; ?>" class="social-rocket-<?php echo $scope; ?>-network-settings" style="display:none;">
					<div class="social-rocket-network-settings-wrapper" data-network="<?php echo $network; ?>" data-scope="social-rocket-<?php echo $scope; ?>">
						<h2><?php echo $network_name; ?></h2>
						<p class="description"><?php _e( 'To use the default setting for any field, just leave it empty.', 'social-rocket' ); ?></p>
						<table class="form-table">
							<?php foreach ( $configurable_settings as $key => $value ):?>
							<?php if ( $this->_isset( $value['type'] ) === 'colorpicker' && ! $colorpicker_first ): ?>
							<tr class="social-rocket-network-settings-colorpicker-toggle-row">
								<th scope="row">
									<label for="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[color_override]"><?php _e( 'Color Override', 'social-rocket' ); ?></label>
									<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
									<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'This will allow you to override the colors set by your color scheme (under Display Settings)', 'social-rocket' ); ?></div>
								</td>
								<td>
									<input type="checkbox" class="social-rocket-network-settings-colorpicker-toggle" name="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[color_override]" id="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[color_override]" value="on" <?php checked( isset( $settings['networks'][$network]['settings']['color_override'] ) ); ?>> <?php _e( 'I want to make the colors for this network different from my chosen color scheme', 'social-rocket' ); ?>
								</td>
							</tr>
							<?php $colorpicker_first = true; ?>
							<?php endif; ?>
							<tr class="social-rocket-network-settings-<?php echo $this->_isset( $value['type'] ); ?>-row" <?php echo ( $this->_isset( $value['type'] ) === 'colorpicker' && ! $this->_isset( $settings['networks'][$network]['settings']['color_override'] ) ? 'style="display:none;"' : '' ); ?>>
								<th scope="row">
									<label for="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]"><?php echo $value['title']; ?></label>
									<?php if ( $key === 'icon_class' ): ?>
									<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
									<div class="social-rocket-tooltip" style="display:none;"><?php printf( __( 'This determines which Font Awesome icon is displayed as this network\'s icon. To use a different icon, find the icon you want on the <a href="%s" target="_blank">Font Awesome</a> website and copy the class(es) here.', 'social-rocket' ), 'https://fontawesome.com/icons?d=gallery&s=brands,solid&m=free' ); ?></div>
									<?php endif; ?>
								</th>
								<td>
									<?php if ( $this->_isset( $value['type'] ) === 'colorpicker' ): ?>
									<input type="text" class="social-rocket-color-picker" name="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]" id="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]" placeholder="<?php echo esc_attr( $value['default'] ); ?>" value="<?php echo esc_attr( $this->_isset( $settings['networks'][$network]['settings'][$key], '' ) > '' ? $settings['networks'][$network]['settings'][$key] : '' ); ?>" />
									<?php elseif ( $this->_isset( $value['type'] ) === 'html' ): ?>
									<textarea name="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]" id="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]" placeholder="<?php echo esc_attr( $value['default'] ); ?>"><?php echo stripslashes( $this->_isset( $settings['networks'][$network]['settings'][$key], '' ) ); ?></textarea>
									<?php else: ?>
									<input type="text" name="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]" id="social_rocket_<?php echo $scope; ?>_network_<?php echo $network; ?>[<?php echo $key; ?>]" placeholder="<?php echo esc_attr( $value['default'] ); ?>" value="<?php echo esc_attr( $this->_isset( $settings['networks'][$network]['settings'][$key], '' ) ); ?>" />
									<?php endif; ?>
									<?php if ( $this->_isset( $value['desc'] ) ): ?>
									<p class="description"><?php echo $value['desc']; ?></p>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
						<p>
							<button type="button" class="button button-primary social-rocket-network-settings-close"><?php _e( 'Done', 'social-rocket' ); ?></button>
							<button type="button" class="button button-secondary social-rocket-network-settings-reset"><?php _e( 'Reset to Default', 'social-rocket' ); ?></button>
						</p>
					</div>
				</div>
				<?php
			}
		}
		#endregion admin_settings_network_popups
	}
	
	
	/**
	 * Outputs Click To Tweet settings page.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_page_click_to_tweet() {
		#region admin_settings_page_click_to_tweet
	
		$this->admin_settings_post_actions();
		
		$this->admin_settings_header();
		
		$SR = Social_Rocket::get_instance();
		
		?>
		<h2><?php _e( 'Click to Tweet', 'social-rocket' ); ?></h2>
		
		<h2 class="nav-tab-wrapper social-rocket-tabs">
			<?php
			$tabs = apply_filters( 'social_rocket_settings_click_to_tweet_tabs', array() );
			$i = 0;
			foreach ( $tabs as $tab => $label ) {
				echo '<a href="#' . $tab . '" class="nav-tab social-rocket-tab ' . ( $i === 0 ? 'nav-tab-active' : '' ) . '" data-tab="' . $tab . '">' . $label . '</a>';
				$i++;
			}
			?>
		</h2>
		
		<p><?php _e( "Use the builder below to create and save Click to Tweet styles. When using the Click to Tweet button inside the page/post editor screen, you'll be able to select which style you'd like to display.", 'social-rocket' ); ?></p>
		<p><?php printf( __( 'Our <a href="%s" target="_blank">documentation</a> explains the builder in more detail. Need help finding something? <a href="%s" target="_blank">Let us know</a>!', 'social-rocket' ), 'https://docs.wpsocialrocket.com/article/25-click-to-tweet', 'https://wpsocialrocket.com/support/?utm_source=Plugin&utm_content=settings_click_to_tweet&utm_campaign=Free' ); ?></p>
		
		<div id="social-rocket-settings-tweet" class="social-rocket-settings-section">
			<h3><?php _e( 'Style Builder', 'social-rocket' ); ?></h3>
			<div class="sr-grid" style="background-color: #4fc78c; font-size: 16px; margin-left: 0; padding: 30px 20px;">
				<div class="sr-grid__col sr-grid__col--1-of-2" style="vertical-align: middle;">
					<strong style="color: #000;"><?php _e( 'Current Style:', 'social-rocket' ); ?></strong> <span id="social-rocket-settings-tweet-saved-settings-current-style"><?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['name'] ); ?></span> <em>(ID: <span id="social-rocket-settings-tweet-saved-settings-id">default</span>)</em>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2" style="text-align: right;">
					<button type="button" id="social-rocket-settings-tweet-saved-settings-update" class="button-secondary" data-id="default"><?php _e( 'Save', 'social-rocket' ); ?></button> 
					<a class="thickbox button button-secondary" href="#TB_inline?width=760&height=150&inlineId=social-rocket-settings-save-as-modal" title="<?php _e( 'Save As New Style', 'social-rocket' ); ?>"><?php _e( 'Save As New Style', 'social-rocket' ); ?></a>
				</div>
			</div>
			<p>&nbsp;</p>
			<div id="social_rocket_tweet_preview" class="postbox">
				<?php
				social_rocket_tweet( array(
					'quote'       => __( 'Social sharing... to the moon!', 'social-rocket' ),
					'tweet'       => __( "I'm using Social Rocket for WordPress!", 'social-rocket' ),
					'include_url' => true,
					'url'         => 'https://wpsocialrocket.com/',
					'include_via' => true,
					'via'         => 'wpsocialrocket',
				) );
				?>
			</div>
			<div class="sr-grid" id="social-rocket-settings-tweet-default-settings">
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_text_color"><?php _e( 'Text Color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_tweet_text_color" id="social_rocket_tweet_text_color" class="social-rocket-color-picker" placeholder="#ffffff" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['text_color'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_text_size"><?php _e( 'Text Size (px)', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="number" name="social_rocket_tweet_text_size" id="social_rocket_tweet_text_size" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['text_size'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_background_color"><?php _e( 'Background Color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_tweet_background_color" id="social_rocket_tweet_background_color" class="social-rocket-color-picker" placeholder="#429cd6" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['background_color'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_accent_color"><?php _e( 'Accent Color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_tweet_accent_color" id="social_rocket_tweet_accent_color" class="social-rocket-color-picker" placeholder="#3c87b2" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['accent_color'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_include_url"><?php _e( 'Include URL in tweet', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="hidden" name="social_rocket_tweet_include_url" value="0" />
								<input type="checkbox" name="social_rocket_tweet_include_url" id="social_rocket_tweet_include_url" value="1" <?php checked( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['include_url'] ) ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_include_via"><?php _e( 'Include "via" in tweet', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="hidden" name="social_rocket_tweet_include_via" value="0" />
								<input type="checkbox" name="social_rocket_tweet_include_via" id="social_rocket_tweet_include_via" value="1" <?php checked( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['include_via'] ) ); ?> />
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_cta_text"><?php _e( 'Call to Action Text', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_tweet_cta_text" id="social_rocket_tweet_cta_text" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['cta_text'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_cta_position"><?php _e( 'Call to Action Position', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_tweet_cta_position" id="social_rocket_tweet_cta_position">
									<option value="left" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['cta_position'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
									<option value="right" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['cta_position'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_cta_color"><?php _e( 'Call to Action Color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_tweet_cta_color" id="social_rocket_tweet_cta_color" class="social-rocket-color-picker" placeholder="#ffffff" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['cta_color'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_border"><?php _e( 'Border', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_tweet_border" id="social_rocket_tweet_border">
									<option value="none" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
									<option value="solid" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'solid' ); ?>><?php _e( 'Solid', 'social-rocket' ); ?></option>
									<option value="double" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'double' ); ?>><?php _e( 'Double', 'social-rocket' ); ?></option>
									<option value="dashed" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'dashed' ); ?>><?php _e( 'Dashed', 'social-rocket' ); ?></option>
									<option value="dotted" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'dotted' ); ?>><?php _e( 'Dotted', 'social-rocket' ); ?></option>
									<option value="inset" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'inset' ); ?>><?php _e( 'Inset', 'social-rocket' ); ?></option>
									<option value="outset" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'outset' ); ?>><?php _e( 'Outset', 'social-rocket' ); ?></option>
									<option value="groove" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'groove' ); ?>><?php _e( 'Groove', 'social-rocket' ); ?></option>
									<option value="ridge" <?php selected( $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border'] ), 'ridge' ); ?>><?php _e( 'Ridge', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_border_size"><?php _e( 'Border Size (px)', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="number" name="social_rocket_tweet_border_size" id="social_rocket_tweet_border_size" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border_size'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_border_radius"><?php _e( 'Border Radius (px)', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="number" name="social_rocket_tweet_border_radius" id="social_rocket_tweet_border_radius" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border_radius'] ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_tweet_border_color"><?php _e( 'Border Color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_tweet_border_color" id="social_rocket_tweet_border_color" class="social-rocket-color-picker" placeholder="#dddddd" value="<?php echo $this->_isset( $SR->settings['tweet_settings']['saved_settings']['default']['border_color'] ); ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<h3><?php _e( 'Saved Styles', 'social-rocket' ); ?></h3>
			<table id="social-rocket-settings-tweet-saved-settings" class="widefat">
				<thead>
				<tr>
					<th style="width: 50%;">
						<label><?php _e( 'Name', 'social-rocket' ); ?></label>
					</th>
					<th>
						<label><?php _e( 'Style ID', 'social-rocket' ); ?></label>
					</th>
					<th>
						<label><?php _e( 'Actions', 'social-rocket' ); ?></label>
					</th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ( isset( $SR->settings['tweet_settings']['saved_settings'] ) ) {
					foreach ( $SR->settings['tweet_settings']['saved_settings'] as $id => $saved_setting ) {
						?>
						<tr>
							<td>
								<span class="social_rocket_tweet_saved_settings_name" data-id="<?php echo $id; ?>"><?php echo ( $saved_setting['name'] > '' ? $saved_setting['name'] : '(no name)' ); ?></span>
							</td>
							<td>
								<span class="description"><?php echo $id; ?></span>
							</td>
							<td>
								<button type="button" class="social-rocket-settings-tweet-saved-settings-load button button-small" data-id="<?php echo $id; ?>"><?php _e( 'Load', 'social-rocket' ); ?></button> 
								<?php if ( $id !== 'default' ): ?>
								<button type="button" class="social-rocket-settings-tweet-saved-settings-delete button button-small" data-id="<?php echo $id; ?>"><?php _e( 'Delete', 'social-rocket' ); ?></button>
								<?php endif; ?>
							</td>
						</tr>
						<?php
					}
				}
				?>
				</tbody>
			</table>
		</div>
		
		<div id="social-rocket-settings-save-as-modal" style="display: none;">
			<div class="social-rocket-settings-save-as-modal-wrapper">
				<h2><?php _e( 'Style Name:', 'social-rocket' ); ?></h2>
				<input type="text" placeholder="<?php _e( 'Name', 'social-rocket' ); ?>" id="social-rocket-settings-tweet-saved-settings-name" style="width: 100%;" value="" />
				<p style="text-align:right;">
					<button type="button" id="social-rocket-settings-tweet-saved-settings-save" class="button-secondary" disabled="disabled"><?php _e( 'Save As New Style', 'social-rocket' ); ?></button>
				</p>
			</div>
		</div>
		<?php
		$this->admin_settings_footer( false );
		#endregion admin_settings_page_click_to_tweet
	}
	
	
	/**
	 * Outputs Floating Buttons settings page.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_page_floating_buttons() {
		#region admin_settings_page_floating_buttons
	
		$this->admin_settings_post_actions();
		
		$this->admin_settings_header();
		
		$SR = Social_Rocket::get_instance();
		
		$settings   = $SR->settings['floating_buttons'];
		$post_types = $SR->get_post_types();
		$archives   = $SR->get_archive_types();
		
		?>
		<h2><?php _e( 'Floating Buttons', 'social-rocket' ); ?></h2>
		
		<h2 class="nav-tab-wrapper social-rocket-tabs">
			<?php
			$tabs = apply_filters( 'social_rocket_settings_floating_buttons_tabs', array(
				'floating-buttons-desktop' => __( 'Desktop', 'social-rocket' ),
				'floating-buttons-mobile'  => __( 'Mobile', 'social-rocket' ),) );
			$i = 0;
			foreach ( $tabs as $tab => $label ) {
				echo '<a href="#' . $tab . '" class="nav-tab social-rocket-tab ' . ( $i === 0 ? 'nav-tab-active' : '' ) . '" data-tab="' . $tab . '">' . $label . '</a>';
				$i++;
			}
			?>
		</h2>
		
		<div id="social-rocket-settings-floating-buttons-desktop" class="social-rocket-settings-section">
			<div class="social-rocket-settings-networks-selector-wrapper">
				<h3>
					<?php _e( 'Choose Social Networks', 'social-rocket' ); ?>
					<a class="add-new-h2 social-rocket-mini-button social-rocket-select-networks" href="#"><?php _e( 'Activate Networks', 'social-rocket' ); ?></a>
				</h3>
				<div class="social-rocket-settings-networks-selector-outer" style="display: none;">
					<input type="hidden" name="social_rocket_floating_networks" value="" />
					<?php foreach ( $SR->networks as $key => $value ): ?>
					<div class="social-rocket-settings-networks-selector"<?php echo ( $key === '_more' ? ' style="display: none;"' : '' ); ?>>
						<input type="checkbox" id="social_rocket_floating_networks_<?php echo $key; ?>" name="social_rocket_floating_networks[]" value="<?php echo $key; ?>" data-network="<?php echo $key; ?>" <?php checked( isset( $settings['networks'][$key] ) ); ?> />
						<label for="social_rocket_floating_networks_<?php echo $key; ?>" class="social-rocket-button social-rocket-<?php echo $key; ?>"><i class="<?php echo $SR->get_icon_class( $key ); ?>"><?php echo $SR->get_icon_svg( $key ); ?></i>
						 <?php echo $value; ?></label>
					</div>
					<?php endforeach; ?>
					<div class="social-rocket-settings-networks-selector-footer"><a href="#" class="button button-primary social-rocket-select-networks-apply"><?php _e( 'Apply Selection', 'social-rocket' ); ?></a></div>
				</div>
				<div class="social-rocket-settings-networks-empty <?php echo ( empty( $settings['networks'] ) ? 'active' : '' ); ?>" style="<?php echo ( empty( $settings['networks'] ) ? '' : 'display: none;' ); ?>">
					<p><?php _e( 'Select social networks to display', 'social-rocket' ); ?></p>
				</div>
				<table class="social-rocket-settings-networks" <?php echo ( empty( $settings['networks'] ) ? 'style="display: none;"' : '' ); ?>>
					<thead>
						<th style="white-space: nowrap;"><span class="dashicons dashicons-sort"></span></th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Network', 'social-rocket' ); ?></th>
						<th width="99%"> </th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Actions', 'social-rocket' ); ?></th>
					</thead>
					<tbody class="social-rocket-settings-networks-sortable">
						<?php
						
						// available networks
						$available_networks = $SR->networks;
						if ( isset( $available_networks['_more'] ) ) {
							$tmp = $available_networks['_more']; // move "more" to the end
							unset( $available_networks['_more'] );
							$available_networks['_more'] = $tmp;
						}
						
						// active networks in this scope
						$active_networks = array();
						foreach ( $settings['networks'] as $key => $value ) {
							if ( isset( $available_networks[ $key ] ) ) {
								$active_networks[$key] = $value['name'];
							} else {
								unset( $settings['networks'][$key] );
							}
						}
						$active_networks = array_merge( // this will keep inactive networks at the end of the array
							$active_networks,
							array_diff_key( $available_networks, $settings['networks'] )
						);
						
						foreach ( $active_networks as $key => $value ):
						?>
						<tr data-network="<?php echo $key; ?>"<?php echo ( isset( $settings['networks'][$key] ) ? ' class="active"' : ' style="display: none;"' ); ?>>
							<td class="social-rocket-settings-networks-sort-handle"><span class="dashicons dashicons-menu"></span></td>
							<td><i class="<?php echo $SR->get_icon_class( $key ); ?> social-rocket-button social-rocket-<?php echo $key; ?>"><?php echo $SR->get_icon_svg( $key ); ?></i></td>
							<td style="white-space: nowrap;">
								<?php echo $value; ?>
								<?php if ( $key === '_more' ): ?>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none; white-space: normal;"><?php _e( 'The "More" button is a special button; all networks after this will be hidden behind it until clicked.', 'social-rocket' ); ?></div>
								<?php endif; ?>
							</td>
							<td></td>
							<td>
								<?php if ( property_exists( 'Social_Rocket_'.ucfirst($key), 'configurable_settings' ) ): ?>
								<a class="thickbox button button-small" href="#TB_inline?width=760&height=550&inlineId=social-rocket-settings-floating-network-<?php echo $key; ?>" title="<?php _e( 'Advanced Network Settings', 'social-rocket' ); ?>"><?php _e( 'Advanced', 'social-rocket' ); ?></a></td>
								<?php endif; ?>
							<td><a class="social-rocket-settings-networks-remove button button-small" href="#"><?php _e( 'Remove', 'social-rocket' ); ?></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<input type="hidden" name="social_rocket_floating_networks_order" value="<?php echo implode( ',', array_keys( $settings['networks'] ) ); ?>" />
			</div>
			<p>&nbsp;</p>
			<h3><?php _e( 'Color Scheme Settings', 'social-rocket' ); ?></h3>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="social_rocket_floating_button_color_scheme"><?php _e( 'Button color scheme', 'social-rocket' ); ?></label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Configure the color scheme for your buttons here. If you want to override a specific button\'s colors, go to the "Advanced" settings for that network (see above).', 'social-rocket' ); ?></div>
					</th>
					<td>
						<select name="social_rocket_floating_button_color_scheme" id="social_rocket_floating_button_color_scheme">
							<option value="default" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
							<option value="inverted" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'inverted' ); ?>><?php _e( 'Inverted', 'social-rocket' ); ?></option>
							<option value="custom" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'custom' ); ?>><?php _e( 'Custom', 'social-rocket' ); ?></option>
						</select>
						<p class="social_rocket_floating_button_color_scheme_custom_toggle" <?php echo ( $this->_isset( $settings['button_color_scheme'] ) === 'custom' ? '' : 'style="display: none;"' ); ?>><a class="social-rocket-collapsable-toggle" data-sr-toggle=".social_rocket_floating_button_color_scheme_custom_colors"><i class="fas fa-plus-square"></i> <span><?php _e( 'Show', 'social-rocket' ); ?></span></a></p>
						<div class="postbox social_rocket_floating_button_color_scheme_custom_colors" style="display: none;">
							<div class="sr-grid">
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Icon Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_floating_button_color_scheme_custom_icon" id="social_rocket_floating_button_color_scheme_custom_icon_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_icon'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_floating_button_color_scheme_custom_icon_color" id="social_rocket_floating_button_color_scheme_custom_icon_color" data-for="social_rocket_floating_button_color_scheme_custom_icon_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_icon_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_floating_button_color_scheme_custom_icon_network_icon">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_icon" id="social_rocket_floating_button_color_scheme_custom_icon_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_icon' ); ?> /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_icon_network_background">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_icon" id="social_rocket_floating_button_color_scheme_custom_icon_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_background' ); ?> /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_icon_network_border">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_icon" id="social_rocket_floating_button_color_scheme_custom_icon_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_border' ); ?> /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Icon Hover Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover" id="social_rocket_floating_button_color_scheme_custom_hover_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_floating_button_color_scheme_custom_hover_color" id="social_rocket_floating_button_color_scheme_custom_hover_color" data-for="social_rocket_floating_button_color_scheme_custom_hover_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_hover_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_floating_button_color_scheme_custom_hover_network_icon">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover" id="social_rocket_floating_button_color_scheme_custom_hover_network_icon" value="network_hover" <?php checked( $settings['button_color_scheme_custom_hover'] === 'network_hover' ); ?> /> <?php _e( 'Use Network Default Icon Hover Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_hover_none">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover" id="social_rocket_floating_button_color_scheme_custom_hover_none" value="none" <?php checked( $settings['button_color_scheme_custom_hover'] === 'none' ); ?> /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Background Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_floating_button_color_scheme_custom_background" id="social_rocket_floating_button_color_scheme_custom_background_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_background'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_floating_button_color_scheme_custom_background_color" id="social_rocket_floating_button_color_scheme_custom_background_color" data-for="social_rocket_floating_button_color_scheme_custom_background_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_background_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_floating_button_color_scheme_custom_background_network_background">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_background" id="social_rocket_floating_button_color_scheme_custom_background_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_background' ); ?> /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_background_network_icon">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_background" id="social_rocket_floating_button_color_scheme_custom_background_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_icon' ); ?> /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_background_network_border">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_background" id="social_rocket_floating_button_color_scheme_custom_background_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_border' ); ?> /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_background_none">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_background" id="social_rocket_floating_button_color_scheme_custom_background_none" value="none" <?php checked( $settings['button_color_scheme_custom_background'] === 'none' ); ?> /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Background Hover Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover_bg" id="social_rocket_floating_button_color_scheme_custom_hover_bg_custom" value="custom" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_floating_button_color_scheme_custom_hover_bg_color" id="social_rocket_floating_button_color_scheme_custom_hover_bg_color" data-for="social_rocket_floating_button_color_scheme_custom_hover_bg_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_hover_bg_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_floating_button_color_scheme_custom_hover_bg_network_icon">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover_bg" id="social_rocket_floating_button_color_scheme_custom_hover_bg_network_icon" value="network_hover_bg" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'network_hover_bg' ); ?> /> <?php _e( 'Use Network Default Background Hover Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_hover_bg_none">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover_bg" id="social_rocket_floating_button_color_scheme_custom_hover_bg_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'none' ); ?> /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Border Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_floating_button_color_scheme_custom_border" id="social_rocket_floating_button_color_scheme_custom_border_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_border'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_floating_button_color_scheme_custom_border_color" id="social_rocket_floating_button_color_scheme_custom_border_color" data-for="social_rocket_floating_button_color_scheme_custom_border_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_border_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_floating_button_color_scheme_custom_border_network_border">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_border" id="social_rocket_floating_button_color_scheme_custom_border_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_border' ); ?> /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_border_network_icon">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_border" id="social_rocket_floating_button_color_scheme_custom_border_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_icon' ); ?> /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_border_network_background">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_border" id="social_rocket_floating_button_color_scheme_custom_border_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_background' ); ?> /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_border_none">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_border" id="social_rocket_floating_button_color_scheme_custom_border_none" value="none" <?php checked( $settings['button_color_scheme_custom_border'] === 'none' ); ?> /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
									</label>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Border Hover Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover_border" id="social_rocket_floating_button_color_scheme_custom_hover_border_custom" value="custom" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_floating_button_color_scheme_custom_hover_border_color" id="social_rocket_floating_button_color_scheme_custom_hover_border_color" data-for="social_rocket_floating_button_color_scheme_custom_hover_border_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_hover_border_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_floating_button_color_scheme_custom_hover_border_network_icon">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover_border" id="social_rocket_floating_button_color_scheme_custom_hover_border_network_icon" value="network_hover_border" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'network_hover_border' ); ?> /> <?php _e( 'Use Network Default Border Hover Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_floating_button_color_scheme_custom_hover_border_none">
										<input type="radio" name="social_rocket_floating_button_color_scheme_custom_hover_border" id="social_rocket_floating_button_color_scheme_custom_hover_border_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'none' ); ?> /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<h3><?php _e( 'Display Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label><?php _e( 'Default buttons placement', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Your buttons will be automatically inserted at the location specified here.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<label for="social_rocket_floating_default_position_left">
									<input type="radio" name="social_rocket_floating_default_position" id="social_rocket_floating_default_position_left" value="left" <?php checked( $settings['default_position'] === 'left' ); ?> />
									<?php _e( 'Left', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_floating_default_position_right">
									<input type="radio" name="social_rocket_floating_default_position" id="social_rocket_floating_default_position_right" value="right" <?php checked( $settings['default_position'] === 'right' ); ?> />
									<?php _e( 'Right', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_floating_default_position_top">
									<input type="radio" name="social_rocket_floating_default_position" id="social_rocket_floating_default_position_top" value="top" <?php checked( $settings['default_position'] === 'top' ); ?> />
									<?php _e( 'Top', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_floating_default_position_bottom">
									<input type="radio" name="social_rocket_floating_default_position" id="social_rocket_floating_default_position_bottom" value="bottom" <?php checked( $settings['default_position'] === 'bottom' ); ?> />
									<?php _e( 'Bottom', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_floating_default_position_none">
									<input type="radio" name="social_rocket_floating_default_position" id="social_rocket_floating_default_position_none" value="none" <?php checked( $settings['default_position'] === 'none' ); ?> />
									<?php _e( 'None', 'social-rocket' ); ?>
								</label>
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<table class="form-table">
						<tr class="social_rocket_floating_default_position_left_right"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'left', 'right' ) ) ? '' : ' style="display: none;"' ); ?>>
							<th scope="row">
								<label><?php _e( 'Vertical position', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If your floating buttons are positioned on the left or the right, this setting allows you to vertically align them to the top, center, or bottom of the browser window.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<label for="social_rocket_floating_vertical_position_top">
									<input type="radio" name="social_rocket_floating_vertical_position" id="social_rocket_floating_vertical_position_top" value="top" <?php checked( $settings['vertical_position'] === 'top' ); ?> />
									<?php _e( 'Top', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_floating_vertical_position_center">
									<input type="radio" name="social_rocket_floating_vertical_position" id="social_rocket_floating_vertical_position_center" value="center" <?php checked( $settings['vertical_position'] === 'center' ); ?> />
									<?php _e( 'Center', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_floating_vertical_position_bottom">
									<input type="radio" name="social_rocket_floating_vertical_position" id="social_rocket_floating_vertical_position_bottom" value="bottom" <?php checked( $settings['vertical_position'] === 'bottom' ); ?> />
									<?php _e( 'Bottom', 'social-rocket' ); ?>
								</label>
							</td>
						</tr>
						<tr class="social_rocket_floating_default_position_left_right"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'left', 'right' ) ) ? '' : ' style="display: none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_floating_vertical_offset"><?php _e( 'Vertical offset', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Change the vertical position of left/right side buttons by a certain offset. You can enter a value in px or %, like "100px" or "10%".', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="text" name="social_rocket_floating_vertical_offset" id="social_rocket_floating_vertical_offset" value="<?php echo $settings['vertical_offset']; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_floating_default_position_top_bottom"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'top', 'bottom' ) ) ? '' : ' style="display: none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_floating_button_alignment"><?php _e( 'Horizontal alignment', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_floating_button_alignment" id="social_rocket_floating_button_alignment">
									<option value="left" <?php selected( $this->_isset( $settings['button_alignment'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
									<option value="center" <?php selected( $this->_isset( $settings['button_alignment'] ), 'center' ); ?>><?php _e( 'Center', 'social-rocket' ); ?></option>
									<option value="right" <?php selected( $this->_isset( $settings['button_alignment'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
									<option value="stretch" <?php selected( $this->_isset( $settings['button_alignment'] ), 'stretch' ); ?><?php echo ( in_array( $settings['button_style'], array( 'round', 'square' ) ) ? ' style="display:none;"' : '' ); ?>><?php _e( 'Stretch', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr class="social_rocket_floating_default_position_top_bottom"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'top', 'bottom' ) ) ? '' : ' style="display: none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_floating_bar_background_color"><?php _e( 'Button Bar background color', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If your floating buttons are positioned at the top or the bottom, this setting allows you to set a background color for the button bar.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="text" name="social_rocket_floating_bar_background_color" id="social_rocket_floating_bar_background_color" class="social-rocket-color-picker" value="<?php echo $settings['background_color']; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_floating_default_position_top_bottom"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'top', 'bottom' ) ) ? '' : ' style="display: none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_floating_bar_padding"><?php _e( 'Button Bar padding', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If your floating buttons are positioned at the top or the bottom, this setting allows you to add space between the buttons and the button bar. If not 0, a unit must be provided (px, em, %, etc).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="text" name="social_rocket_floating_bar_padding" id="social_rocket_floating_bar_padding" value="<?php echo $settings['padding']; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_button_style"><?php _e( 'Button style', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Choose which style of buttons you want to be displayed (oval, rectangle, round, square).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<select name="social_rocket_floating_button_style" id="social_rocket_floating_button_style">
									<option value="oval" <?php selected( $this->_isset( $settings['button_style'] ), 'oval' ); ?>><?php _e( 'Oval', 'social-rocket' ); ?></option>
									<option value="rectangle" <?php selected( $this->_isset( $settings['button_style'] ), 'rectangle' ); ?>><?php _e( 'Rectangle', 'social-rocket' ); ?></option>
									<option value="round" <?php selected( $this->_isset( $settings['button_style'] ), 'round' ); ?>><?php _e( 'Round', 'social-rocket' ); ?></option>
									<option value="square" <?php selected( $this->_isset( $settings['button_style'] ), 'square' ); ?>><?php _e( 'Square', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_button_size"><?php _e( 'Button size (%)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Increase or decrease the size of the buttons by percentage (default: 100).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_button_size" id="social_rocket_floating_button_size" value="<?php echo $this->_isset( $settings['button_size'], '' ); ?>" />
							</td>
						</tr>
						<tr class="social_rocket_floating_button_show_cta_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'oval' ) ) ? '' : ' style="display: none;"'; ?>>
							<th scope="row">
								<label for="social_rocket_floating_button_show_cta"><?php _e( 'Show Button Text', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the Button Text for each button. If you want to override a specific button\'s Button Text, go to the "Advanced" settings for that network (see above).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_floating_button_show_cta" value="0" />
								<input type="checkbox" name="social_rocket_floating_button_show_cta" id="social_rocket_floating_button_show_cta" value="1" <?php checked( $settings['button_show_cta'] ); ?> />
							</td>
						</tr>
						<?php echo apply_filters( 'social_rocket_settings_floating_more_enable_html', '
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_more_enable">' . __( '(PRO) Enable "More" button', 'social-rocket' ) . '</label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;">' . __( 'Allows extra networks to be combined behind a "more" button.', 'social-rocket' ) . '</div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_floating_more_enable" value="0" />
								<input type="checkbox" name="social_rocket_floating_more_enable" id="social_rocket_floating_more_enable" value="1" disabled />
								<p class="description">' . sprintf( __( '%s adds the "More" button feature, which allows you to combine extra networks into one button.', 'social-rocket' ), '<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_floating_more_enable&utm_campaign=Free" target="_blank">Social Rocket Pro</a>' ) . '</p>
							</td>
						</tr>
						' ); ?>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_border"><?php _e( 'Border', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Set the style of the border around your buttons.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<select name="social_rocket_floating_border" id="social_rocket_floating_border">
									<option value="none" <?php selected( $this->_isset( $settings['border'] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
									<option value="solid" <?php selected( $this->_isset( $settings['border'] ), 'solid' ); ?>><?php _e( 'Solid', 'social-rocket' ); ?></option>
									<option value="double" <?php selected( $this->_isset( $settings['border'] ), 'double' ); ?>><?php _e( 'Double', 'social-rocket' ); ?></option>
									<option value="dashed" <?php selected( $this->_isset( $settings['border'] ), 'dashed' ); ?>><?php _e( 'Dashed', 'social-rocket' ); ?></option>
									<option value="dotted" <?php selected( $this->_isset( $settings['border'] ), 'dotted' ); ?>><?php _e( 'Dotted', 'social-rocket' ); ?></option>
									<option value="inset" <?php selected( $this->_isset( $settings['border'] ), 'inset' ); ?>><?php _e( 'Inset', 'social-rocket' ); ?></option>
									<option value="outset" <?php selected( $this->_isset( $settings['border'] ), 'outset' ); ?>><?php _e( 'Outset', 'social-rocket' ); ?></option>
									<option value="groove" <?php selected( $this->_isset( $settings['border'] ), 'groove' ); ?>><?php _e( 'Groove', 'social-rocket' ); ?></option>
									<option value="ridge" <?php selected( $this->_isset( $settings['border'] ), 'ridge' ); ?>><?php _e( 'Ridge', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_border_size"><?php _e( 'Border size (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Thickness of the border, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_border_size" id="social_rocket_floating_border_size" value="<?php echo $settings['border_size']; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_floating_border_radius_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'square' ) ) ? '' : ' style="display: none;"'; ?>>
							<th scope="row">
								<label for="social_rocket_floating_border_radius"><?php _e( 'Border radius (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Allows you to round the corners of the buttons.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_border_radius" id="social_rocket_floating_border_radius" value="<?php echo $settings['border_radius']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_margin_right"><?php _e( 'Horizontal margin (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Determines the horizontal space between buttons, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_margin_right" id="social_rocket_floating_margin_right" value="<?php echo $settings['margin_right']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_margin_bottom"><?php _e( 'Vertical margin (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Determines the vertical space between buttons, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_margin_bottom" id="social_rocket_floating_margin_bottom" value="<?php echo $settings['margin_bottom']; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<p>&nbsp;</p>
			<h3><?php _e( 'Share Count Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_show_counts"><?php _e( 'Show share count', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the share count for each button (if applicable).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_floating_show_counts" value="0" />
								<input type="checkbox" name="social_rocket_floating_show_counts" id="social_rocket_floating_show_counts" value="1" <?php checked( $settings['show_counts'] ); ?> />
							</td>
						</tr>
						<tr class="social_rocket_floating_show_counts_min"<?php echo ( $settings['show_counts'] ? '' : ' style="display:none;"' ) ; ?>>
							<th scope="row">
								<label for="social_rocket_floating_show_counts_min"><?php _e( 'Minimum shares', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the network\'s share count only if greater than or equal to this number.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_show_counts_min" id="social_rocket_floating_show_counts_min" value="<?php echo $settings['show_counts_min'] ; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_rounding"><?php _e( 'Round large share counts', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If any share count is greater than 1000, it will be rounded to a shorter format.  For example, 1234 will show as 1.2K, etc.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_floating_rounding" value="0" />
								<input type="checkbox" name="social_rocket_floating_rounding" id="social_rocket_floating_rounding" value="1" <?php checked( $settings['rounding'] ); ?> />
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_show_total"><?php _e( 'Show total share count', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the total share count for all social networks combined.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_floating_show_total" value="0" />
								<input type="checkbox" name="social_rocket_floating_show_total" id="social_rocket_floating_show_total" value="1" <?php checked( $settings['show_total'] ); ?> />
							</td>
						</tr>
						<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
							<th scope="row">
								<label for="social_rocket_floating_show_total_min"><?php _e( 'Minimum total', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the total share count only if greater than or equal to this number.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_show_total_min" id="social_rocket_floating_show_total_min" value="<?php echo $settings['show_total_min'] ; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
							<th scope="row">
								<label for="social_rocket_floating_total_position"><?php _e( 'Total share count position', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_floating_total_position" id="social_rocket_floating_total_position">
									<option value="before" <?php selected( $this->_isset( $settings['total_position'] ), 'before' ); ?>><?php _e( 'Before Buttons', 'social-rocket' ); ?></option>
									<option value="after" <?php selected( $this->_isset( $settings['total_position'] ), 'after' ); ?>><?php _e( 'After Buttons', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_floating_total_color"><?php _e( 'Total share text/icon color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_floating_total_color" id="social_rocket_floating_total_color" class="social-rocket-color-picker" value="<?php echo $settings['total_color']; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_floating_total_show_icon"><?php printf( __( 'Show total share icon (%s)', 'social-rocket' ), '<i class="fas fa-share-alt"></i>' ); ?></label>
							</th>
							<td>
								<input type="hidden" name="social_rocket_floating_total_show_icon" value="0" />
								<input type="checkbox" name="social_rocket_floating_total_show_icon" id="social_rocket_floating_total_show_icon" value="1" <?php checked( $this->_isset( $settings['total_show_icon'] ) ); ?> />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<p>&nbsp;</p>
			<h3><?php _e( 'Advanced Placement Settings', 'social-rocket' ); ?></h3>
			<p class="description"><?php _e( 'The defaults are set above under Display Settings, but you can (optionally) refine it here if you want something other than the default for individual pages, posts, CPTs, or archives.', 'social-rocket' ); ?></p>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<h4>
						<?php _e( 'Individual Pages, Posts, CPTs', 'social-rocket' ); ?>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Here you can override the placement settings for specific post types.', 'social-rocket' ); ?></div>
					</h4>
					<div id="social-rocket-floating-position-posts">
						<table class="form-table">
							<?php foreach ( $post_types as $post_type ): ?>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_position_post_type_<?php echo esc_attr( $post_type ); ?>"><?php echo ucfirst( $post_type ); ?></label>
								</th>
								<td>
									<select name="social_rocket_floating_position_post_type_<?php echo esc_attr( $post_type ); ?>" id="social_rocket_floating_position_post_type_<?php echo esc_attr( $post_type ); ?>">
										<option value="default" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
										<option value="left" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
										<option value="right" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
										<option value="top" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'top' ); ?>><?php _e( 'Top', 'social-rocket' ); ?></option>
										<option value="bottom" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'bottom' ); ?>><?php _e( 'Bottom', 'social-rocket' ); ?></option>
										<option value="none" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<h4>
						<?php _e( 'Archives Pages', 'social-rocket' ); ?>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Here you can override the placement settings for specific types of archives pages.', 'social-rocket' ); ?></div>
					</h4>
					<div id="social-rocket-floating-position-archives">
						<table class="form-table">
							<?php foreach ( $archives as $key => $archive ): ?>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_position_archive_<?php echo esc_attr( $key ); ?>"><?php echo $archive['display_name']; ?></label>
								</th>
								<td>
									<select name="social_rocket_floating_position_archive_<?php echo esc_attr( $key ); ?>" id="social_rocket_floating_position_archive_<?php echo esc_attr( $key ); ?>">
										<option value="default" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
										<option value="left" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
										<option value="right" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
										<option value="top" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'top' ); ?>><?php _e( 'Top', 'social-rocket' ); ?></option>
										<option value="bottom" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'bottom' ); ?>><?php _e( 'Bottom', 'social-rocket' ); ?></option>
										<option value="none" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<?php do_action( 'social_rocket_settings_floating_buttons_desktop_after' ); ?>
		</div>
		
		<div id="social-rocket-settings-floating-buttons-mobile" class="social-rocket-settings-section" style="display: none;">
			<h3><?php _e( 'Mobile Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<td style="width:40px;">
								<input type="radio" name="social_rocket_floating_mobile_setting" id="social_rocket_floating_mobile_setting_disabled" value="disabled" <?php checked( $SR->settings['floating_mobile_setting'] === 'disabled' ); ?> />
							</td>
							<td>
								<label for="social_rocket_floating_mobile_setting_disabled"><strong><?php _e( 'Disable on mobile', 'social-rocket' ); ?></strong></label>
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" name="social_rocket_floating_mobile_setting" id="social_rocket_floating_mobile_setting_default" value="default" <?php checked( $SR->settings['floating_mobile_setting'] === 'default' ); ?> />
							</td>
							<td>
								<label for="social_rocket_floating_mobile_setting_default"><strong><?php _e( 'Use same settings as desktop', 'social-rocket' ); ?></strong></label>
							</td>
						</tr>
						<?php echo apply_filters( 'social_rocket_settings_floating_mobile_setting_html', '
						<tr>
							<td>
								<input type="radio" name="social_rocket_floating_mobile_setting" id="social_rocket_floating_mobile_setting_custom" value="custom" disabled />
							</td>
							<td>
								<label for="social_rocket_floating_mobile_setting_custom"><strong>' . __( '(PRO) Use custom settings:', 'social-rocket' ) . '</strong></label>
								<p class="description">' . sprintf( __( '%s adds the ability to customize all the following settings specifically for mobile devices:', 'social-rocket' ), '<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_floating_mobile_custom&utm_campaign=Free" target="_blank">Social Rocket Pro</a>' ) . '</p>
							</td>
						</tr>
						' ); ?>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">			
						<tr>
							<th scope="row">
								<label for="social_rocket_floating_mobile_breakpoint"><?php _e( 'Mobile breakpoint (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Mobile styling applies to screens less than or equal to this width, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_floating_mobile_breakpoint" id="social_rocket_floating_mobile_breakpoint" value="<?php echo $SR->settings['floating_mobile_breakpoint']; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="social-rocket-settings-floating-buttons-mobile-default" <?php echo ( $SR->settings['floating_mobile_setting'] === 'default' ? '' : 'style="display:none;"' ); ?>>
				<h3>
					<?php _e( 'Choose Social Networks', 'social-rocket' ); ?>
					<a class="add-new-h2 social-rocket-mini-button" href="#"><?php _e( 'Activate Networks', 'social-rocket' ); ?></a>
				</h3>
				<div class="social-rocket-settings-networks-empty <?php echo ( empty( $settings['networks'] ) ? 'active' : '' ); ?>" style="<?php echo ( empty( $settings['networks'] ) ? '' : 'display: none;' ); ?>">
					<p><?php _e( 'Select social networks to display', 'social-rocket' ); ?></p>
				</div>
				<table class="social-rocket-settings-networks" <?php echo ( empty( $settings['networks'] ) ? 'style="display: none;"' : '' ); ?>>
					<thead>
						<th style="white-space: nowrap;"><span class="dashicons dashicons-sort"></span></th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Network', 'social-rocket' ); ?></th>
						<th width="99%"> </th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Actions', 'social-rocket' ); ?></th>
					</thead>
					<tbody class="social-rocket-settings-networks-sortable inactive">
						<?php
						
						// available networks
						$available_networks = $SR->networks;
						if ( isset( $available_networks['_more'] ) ) {
							$tmp = $available_networks['_more']; // move "more" to the end
							unset( $available_networks['_more'] );
							$available_networks['_more'] = $tmp;
						}
						
						// active networks in this scope
						$active_networks = array();
						foreach ( $settings['networks'] as $key => $value ) {
							if ( isset( $available_networks[ $key ] ) ) {
								$active_networks[$key] = $value['name'];
							} else {
								unset( $settings['networks'][$key] );
							}
						}
						$active_networks = array_merge( // this will keep inactive networks at the end of the array
							$active_networks,
							array_diff_key( $available_networks, $settings['networks'] )
						);
						
						foreach ( $active_networks as $key => $value ):
						?>
						<tr data-network="<?php echo $key; ?>"<?php echo ( isset( $settings['networks'][$key] ) ? ' class="active"' : ' style="display: none;"' ); ?>>
							<td class="social-rocket-settings-networks-sort-handle"><span class="dashicons dashicons-menu"></span></td>
							<td><i class="<?php echo $SR->get_icon_class( $key ); ?> social-rocket-button social-rocket-<?php echo $key; ?>"><?php echo $SR->get_icon_svg( $key ); ?></i></td>
							<td style="white-space: nowrap;">
								<?php echo $value; ?>
								<?php if ( $key === '_more' ): ?>
								<a><span class="dashicons dashicons-editor-help"></span></a>
								<?php endif; ?>
							</td>
							<td></td>
							<td>
								<?php if ( property_exists( 'Social_Rocket_'.ucfirst($key), 'configurable_settings' ) ): ?>
								<a class="button button-small" href="#" title="<?php _e( 'Advanced Network Settings', 'social-rocket' ); ?>"><?php _e( 'Advanced', 'social-rocket' ); ?></a></td>
								<?php endif; ?>
							<td><a class="button button-small" href="#"><?php _e( 'Remove', 'social-rocket' ); ?></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p>&nbsp;</p>
				<h3><?php _e( 'Color Scheme Settings', 'social-rocket' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="social_rocket_floating_mobile_button_color_scheme"><?php _e( 'Button color scheme', 'social-rocket' ); ?></label>
							<a><span class="dashicons dashicons-editor-help"></span></a>
						</th>
						<td>
							<select id="social_rocket_floating_mobile_button_color_scheme" disabled>
								<option value="default" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
								<option value="inverted" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'inverted' ); ?>><?php _e( 'Inverted', 'social-rocket' ); ?></option>
								<option value="custom" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'custom' ); ?>><?php _e( 'Custom', 'social-rocket' ); ?></option>
							</select>
							<p class="social_rocket_floating_button_color_scheme_custom_toggle" <?php echo ( $this->_isset( $settings['button_color_scheme'] ) === 'custom' ? '' : 'style="display: none;"' ); ?>><a data-sr-toggle=".social_rocket_floating_button_color_scheme_custom_colors"><i class="fas fa-plus-square"></i> <span><?php _e( 'Show', 'social-rocket' ); ?></span></a></p>
							<div class="postbox social_rocket_floating_button_color_scheme_custom_colors" style="display: none;">
								<div class="sr-grid">
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Icon Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_icon_custom">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_icon" id="social_rocket_floating_mobile_button_color_scheme_custom_icon_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_icon'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_button_color_scheme_custom_icon_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_icon_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_icon_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_icon_network_icon">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_icon" id="social_rocket_floating_mobile_button_color_scheme_custom_icon_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_icon' ); ?> disabled /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_icon_network_background">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_icon" id="social_rocket_floating_mobile_button_color_scheme_custom_icon_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_background' ); ?> disabled /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_icon_network_border">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_icon" id="social_rocket_floating_mobile_button_color_scheme_custom_icon_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Icon Hover Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_custom">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_hover_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_hover_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_network_icon">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_network_icon" value="network_hover" <?php checked( $settings['button_color_scheme_custom_hover'] === 'network_hover' ); ?> disabled /> <?php _e( 'Use Network Default Icon Hover Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_none">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_none" value="none" <?php checked( $settings['button_color_scheme_custom_hover'] === 'none' ); ?> disabled /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Background Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_background_custom">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_background" id="social_rocket_floating_mobile_button_color_scheme_custom_background_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_background'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_button_color_scheme_custom_background_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_background_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_background_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_background_network_background">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_background" id="social_rocket_floating_mobile_button_color_scheme_custom_background_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_background' ); ?> disabled /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_background_network_icon">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_background" id="social_rocket_floating_mobile_button_color_scheme_custom_background_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_icon' ); ?> disabled /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_background_network_border">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_background" id="social_rocket_floating_mobile_button_color_scheme_custom_background_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_background_none">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_background" id="social_rocket_floating_mobile_button_color_scheme_custom_background_none" value="none" <?php checked( $settings['button_color_scheme_custom_background'] === 'none' ); ?> disabled /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Background Hover Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_custom">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover_bg'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_hover_bg_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_hover_bg_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_network_icon">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_network_icon" value="network_hover_bg" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'network_hover_bg' ); ?> disabled /> <?php _e( 'Use Network Default Background Hover Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_none">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_bg_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'none' ); ?> disabled /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Border Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_border_custom">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_border" id="social_rocket_floating_mobile_button_color_scheme_custom_border_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_border'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_button_color_scheme_custom_border_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_border_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_border_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_border_network_border">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_border" id="social_rocket_floating_mobile_button_color_scheme_custom_border_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_border_network_icon">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_border" id="social_rocket_floating_mobile_button_color_scheme_custom_border_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_icon' ); ?> disabled /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_border_network_background">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_border" id="social_rocket_floating_mobile_button_color_scheme_custom_border_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_background' ); ?> disabled /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_border_none">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_border" id="social_rocket_floating_mobile_button_color_scheme_custom_border_none" value="none" <?php checked( $settings['button_color_scheme_custom_border'] === 'none' ); ?> disabled /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
										</label>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Border Hover Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_custom">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover_border" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover_border'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_hover_border_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_hover_border_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_network_icon">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover_border" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_network_icon" value="network_hover_border" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'network_hover_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Hover Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_none">
											<input type="radio" name="social_rocket_floating_mobile_button_color_scheme_custom_hover_border" id="social_rocket_floating_mobile_button_color_scheme_custom_hover_border_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'none' ); ?> disabled /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<h3><?php _e( 'Display Settings', 'social-rocket' ); ?></h3>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label><?php _e( 'Default buttons placement', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<label for="social_rocket_floating_mobile_default_position_left">
										<input type="radio" name="social_rocket_floating_mobile_default_position" id="social_rocket_floating_mobile_default_position_left" value="left" <?php checked( $settings['default_position'] === 'left' ); ?> disabled />
										<?php _e( 'Left', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_floating_mobile_default_position_right">
										<input type="radio" name="social_rocket_floating_mobile_default_position" id="social_rocket_floating_mobile_default_position_right" value="right" <?php checked( $settings['default_position'] === 'right' ); ?> disabled />
										<?php _e( 'Right', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_floating_mobile_default_position_top">
										<input type="radio" name="social_rocket_floating_mobile_default_position" id="social_rocket_floating_mobile_default_position_top" value="top" <?php checked( $settings['default_position'] === 'top' ); ?> disabled />
										<?php _e( 'Top', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_floating_mobile_default_position_bottom">
										<input type="radio" name="social_rocket_floating_mobile_default_position" id="social_rocket_floating_mobile_default_position_bottom" value="bottom" <?php checked( $settings['default_position'] === 'bottom' ); ?> disabled />
										<?php _e( 'Bottom', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_floating_mobile_default_position_none">
										<input type="radio" name="social_rocket_floating_mobile_default_position" id="social_rocket_floating_mobile_default_position_none" value="none" <?php checked( $settings['default_position'] === 'none' ); ?> disabled />
										<?php _e( 'None', 'social-rocket' ); ?>
									</label>
								</td>
							</tr>
						</table>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<table class="form-table">
							<tr class="social_rocket_floating_default_position_left_right"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'left', 'right' ) ) ? '' : ' style="display: none;"' ); ?>>
								<th scope="row">
									<label><?php _e( 'Vertical position', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<label for="social_rocket_floating_mobile_vertical_position_top">
										<input type="radio" name="social_rocket_floating_mobile_vertical_position" id="social_rocket_floating_mobile_vertical_position_top" value="top" <?php checked( $settings['vertical_position'] === 'top' ); ?> disabled />
										<?php _e( 'Top', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_floating_mobile_vertical_position_center">
										<input type="radio" name="social_rocket_floating_mobile_vertical_position" id="social_rocket_floating_mobile_vertical_position_center" value="center" <?php checked( $settings['vertical_position'] === 'center' ); ?> disabled />
										<?php _e( 'Center', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_floating_mobile_vertical_position_bottom">
										<input type="radio" name="social_rocket_floating_mobile_vertical_position" id="social_rocket_floating_mobile_vertical_position_bottom" value="bottom" <?php checked( $settings['vertical_position'] === 'bottom' ); ?> disabled />
										<?php _e( 'Bottom', 'social-rocket' ); ?>
									</label>
								</td>
							</tr>
							<tr class="social_rocket_floating_default_position_left_right"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'left', 'right' ) ) ? '' : ' style="display: none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_vertical_offset"><?php _e( 'Vertical offset', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="text" name="social_rocket_floating_mobile_vertical_offset" id="social_rocket_floating_mobile_vertical_offset" value="<?php echo $settings['vertical_offset']; ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_floating_default_position_top_bottom"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'top', 'bottom' ) ) ? '' : ' style="display: none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_button_alignment"><?php _e( 'Horizontal alignment', 'social-rocket' ); ?></label>
								</th>
								<td>
									<select name="social_rocket_floating_mobile_button_alignment" id="social_rocket_floating_mobile_button_alignment" disabled>
										<option value="left" <?php selected( $this->_isset( $settings['button_alignment'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
										<option value="center" <?php selected( $this->_isset( $settings['button_alignment'] ), 'center' ); ?>><?php _e( 'Center', 'social-rocket' ); ?></option>
										<option value="right" <?php selected( $this->_isset( $settings['button_alignment'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
										<option value="stretch" <?php selected( $this->_isset( $settings['button_alignment'] ), 'stretch' ); ?><?php echo ( in_array( $settings['button_style'], array( 'round', 'square' ) ) ? ' style="display:none;"' : '' ); ?>><?php _e( 'Stretch', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr class="social_rocket_floating_default_position_top_bottom"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'top', 'bottom' ) ) ? '' : ' style="display: none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_bar_background_color"><?php _e( 'Button Bar background color', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<div>
										<div class="wp-picker-container">
											<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_bar_background_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['background_color'] ) ? 'background-color: ' . $settings['background_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
										</div>
									</div>
								</td>
							</tr>
							<tr class="social_rocket_floating_default_position_top_bottom"<?php echo ( in_array( $this->_isset( $settings['default_position'] ), array( 'top', 'bottom' ) ) ? '' : ' style="display: none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_bar_padding"><?php _e( 'Button Bar padding', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="text" name="social_rocket_floating_mobile_bar_padding" id="social_rocket_floating_mobile_bar_padding" value="<?php echo $settings['padding']; ?>" disabled />
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_button_style"><?php _e( 'Button style', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<select name="social_rocket_floating_mobile_button_style" id="social_rocket_floating_mobile_button_style" disabled>
										<option value="oval" <?php selected( $this->_isset( $settings['button_style'] ), 'oval' ); ?>><?php _e( 'Oval', 'social-rocket' ); ?></option>
										<option value="rectangle" <?php selected( $this->_isset( $settings['button_style'] ), 'rectangle' ); ?>><?php _e( 'Rectangle', 'social-rocket' ); ?></option>
										<option value="round" <?php selected( $this->_isset( $settings['button_style'] ), 'round' ); ?>><?php _e( 'Round', 'social-rocket' ); ?></option>
										<option value="square" <?php selected( $this->_isset( $settings['button_style'] ), 'square' ); ?>><?php _e( 'Square', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_button_size"><?php _e( 'Button size (%)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_button_size" id="social_rocket_floating_mobile_button_size" value="<?php echo $this->_isset( $settings['button_size'], '' ); ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_floating_button_show_cta_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'oval' ) ) ? '' : ' style="display: none;"'; ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_button_show_cta"><?php _e( 'Show Button Text', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_floating_mobile_button_show_cta" id="social_rocket_floating_mobile_button_show_cta" value="1" <?php checked( $settings['button_show_cta'] ); ?> disabled />
								</td>
							</tr>
							<?php echo apply_filters( 'social_rocket_settings_floating_mobile_more_enable_html', '
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_more_enable">' . __( '(PRO) Enable "More" button', 'social-rocket' ) . '</label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_floating_mobile_more_enable" id="social_rocket_floating_mobile_more_enable" value="1" disabled />
									<p class="description">' . sprintf( __( '%s adds the "More" button feature, which allows you to combine extra networks into one button.', 'social-rocket' ), '<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_floating_mobile_more_enable&utm_campaign=Free" target="_blank">Social Rocket Pro</a>' ) . '</p>
								</td>
							</tr>
							' ); ?>
						</table>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_border"><?php _e( 'Border', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<select name="social_rocket_floating_mobile_border" id="social_rocket_floating_mobile_border" disabled>
										<option value="none" <?php selected( $this->_isset( $settings['border'] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
										<option value="solid" <?php selected( $this->_isset( $settings['border'] ), 'solid' ); ?>><?php _e( 'Solid', 'social-rocket' ); ?></option>
										<option value="double" <?php selected( $this->_isset( $settings['border'] ), 'double' ); ?>><?php _e( 'Double', 'social-rocket' ); ?></option>
										<option value="dashed" <?php selected( $this->_isset( $settings['border'] ), 'dashed' ); ?>><?php _e( 'Dashed', 'social-rocket' ); ?></option>
										<option value="dotted" <?php selected( $this->_isset( $settings['border'] ), 'dotted' ); ?>><?php _e( 'Dotted', 'social-rocket' ); ?></option>
										<option value="inset" <?php selected( $this->_isset( $settings['border'] ), 'inset' ); ?>><?php _e( 'Inset', 'social-rocket' ); ?></option>
										<option value="outset" <?php selected( $this->_isset( $settings['border'] ), 'outset' ); ?>><?php _e( 'Outset', 'social-rocket' ); ?></option>
										<option value="groove" <?php selected( $this->_isset( $settings['border'] ), 'groove' ); ?>><?php _e( 'Groove', 'social-rocket' ); ?></option>
										<option value="ridge" <?php selected( $this->_isset( $settings['border'] ), 'ridge' ); ?>><?php _e( 'Ridge', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_border_size"><?php _e( 'Border size (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_border_size" id="social_rocket_floating_mobile_border_size" value="<?php echo $settings['border_size']; ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_floating_border_radius_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'square' ) ) ? '' : ' style="display: none;"'; ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_border_radius"><?php _e( 'Border radius (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_border_radius" id="social_rocket_floating_mobile_border_radius" value="<?php echo $settings['border_radius']; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_margin_right"><?php _e( 'Horizontal margin (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_margin_right" id="social_rocket_floating_mobile_margin_right" value="<?php echo $settings['margin_right']; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_margin_bottom"><?php _e( 'Vertical margin (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_margin_bottom" id="social_rocket_floating_mobile_margin_bottom" value="<?php echo $settings['margin_bottom']; ?>" disabled />
								</td>
							</tr>
						</table>
					</div>
				</div>
				<p>&nbsp;</p>
				<h3><?php _e( 'Share Count Settings', 'social-rocket' ); ?></h3>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_show_counts"><?php _e( 'Show share count', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_floating_mobile_show_counts" id="social_rocket_floating_mobile_show_counts" value="1" <?php checked( $settings['show_counts'] ); ?> disabled />
								</td>
							</tr>
							<tr class="social_rocket_floating_show_counts_min"<?php echo ( $settings['show_counts'] ? '' : ' style="display:none;"' ) ; ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_show_counts_min"><?php _e( 'Minimum shares', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_show_counts_min" id="social_rocket_floating_mobile_show_counts_min" value="<?php echo $settings['show_counts_min'] ; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_rounding"><?php _e( 'Round large share counts', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_floating_mobile_rounding" id="social_rocket_floating_mobile_rounding" value="1" <?php checked( $settings['rounding'] ); ?> disabled />
								</td>
							</tr>
						</table>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_floating_mobile_show_total"><?php _e( 'Show total share count', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_floating_mobile_show_total" id="social_rocket_floating_mobile_show_total" value="1" <?php checked( $settings['show_total'] ); ?> disabled />
								</td>
							</tr>
							<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_show_total_min"><?php _e( 'Minimum total', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_floating_mobile_show_total_min" id="social_rocket_floating_mobile_show_total_min" value="<?php echo $settings['show_total_min'] ; ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_total_position"><?php _e( 'Total share count position', 'social-rocket' ); ?></label>
								</th>
								<td>
									<select name="social_rocket_floating_mobile_total_position" id="social_rocket_floating_mobile_total_position" disabled>
										<option value="before" <?php selected( $this->_isset( $settings['total_position'] ), 'before' ); ?>><?php _e( 'Before Buttons', 'social-rocket' ); ?></option>
										<option value="after" <?php selected( $this->_isset( $settings['total_position'] ), 'after' ); ?>><?php _e( 'After Buttons', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_total_color"><?php _e( 'Total share text/icon color', 'social-rocket' ); ?></label>
								</th>
								<td>
									<div>
										<div class="wp-picker-container">
											<button type="button" class="button wp-color-result" id="social_rocket_floating_mobile_total_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['total_color'] ) ? 'background-color: ' . $settings['total_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
										</div>
									</div>
								</td>
							</tr>
							<tr class="social_rocket_floating_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_floating_mobile_total_show_icon"><?php printf( __( 'Show total share icon (%s)', 'social-rocket' ), '<i class="fas fa-share-alt"></i>' ); ?></label>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_floating_mobile_total_show_icon" id="social_rocket_floating_mobile_total_show_iconsocial_rocket_floating_mobile_total_show_icon" value="1" <?php checked( $this->_isset( $settings['total_show_icon'] ) ); ?> disabled />
								</td>
							</tr>
						</table>
					</div>
				</div>
				<p>&nbsp;</p>
				<h3><?php _e( 'Advanced Placement Settings', 'social-rocket' ); ?></h3>
				<p class="description"><?php _e( 'The defaults are set above under Display Settings, but you can (optionally) refine it here if you want something other than the default for individual pages, posts, CPTs, or archives.', 'social-rocket' ); ?></p>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<h4>
							<?php _e( 'Individual Pages, Posts, CPTs', 'social-rocket' ); ?>
							<a><span class="dashicons dashicons-editor-help"></span></a>
						</h4>
						<div id="social-rocket-floating-mobile-position-posts">
							<table class="form-table">
								<?php foreach ( $post_types as $post_type ): ?>
								<tr>
									<th scope="row">
										<label for="social_rocket_floating_mobile_position_post_type_<?php echo esc_attr( $post_type ); ?>"><?php echo ucfirst( $post_type ); ?></label>
									</th>
									<td>
										<select name="social_rocket_floating_mobile_position_post_type_<?php echo esc_attr( $post_type ); ?>" id="social_rocket_floating_mobile_position_post_type_<?php echo esc_attr( $post_type ); ?>">
											<option value="default" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
											<option value="left" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
											<option value="right" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
											<option value="top" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'top' ); ?>><?php _e( 'Top', 'social-rocket' ); ?></option>
											<option value="bottom" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'bottom' ); ?>><?php _e( 'Bottom', 'social-rocket' ); ?></option>
											<option value="none" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
										</select>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<h4>
							<?php _e( 'Archives Pages', 'social-rocket' ); ?>
							<a><span class="dashicons dashicons-editor-help"></span></a>
						</h4>
						<div id="social-rocket-floating-mobile-position-archives">
							<table class="form-table">
								<?php foreach ( $archives as $key => $archive ): ?>
								<tr>
									<th scope="row">
										<label for="social_rocket_floating_mobile_position_archive_<?php echo esc_attr( $key ); ?>"><?php echo $archive['display_name']; ?></label>
									</th>
									<td>
										<select name="social_rocket_floating_mobile_position_archive_<?php echo esc_attr( $key ); ?>" id="social_rocket_floating_mobile_position_archive_<?php echo esc_attr( $key ); ?>">
											<option value="default" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
											<option value="left" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
											<option value="right" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
											<option value="top" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'top' ); ?>><?php _e( 'Top', 'social-rocket' ); ?></option>
											<option value="bottom" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'bottom' ); ?>><?php _e( 'Bottom', 'social-rocket' ); ?></option>
											<option value="none" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
										</select>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>
				<p>&nbsp;</p>
			</div>
			<?php do_action( 'social_rocket_settings_floating_buttons_mobile_after' ); ?>
		</div>
		
		<div class="social-rocket-settings-preview-wrapper">
			<div id="social_rocket_floating_preview">
				<h3>
					<?php _e( 'Preview', 'social-rocket' ); ?>&nbsp;&nbsp;<a class="social-rocket-collapsable-toggle" data-sr-toggle="social_rocket_floating_preview_inner"><i class="fas fa-plus-square"></i> <span><?php _e( 'Show', 'social-rocket' ); ?></span></a>
					<?php /*
					<a class="add-new-h2 social-rocket-mini-button" id="social_rocket_floating_preview_mobile_toggle" href="#"><?php _e( 'Mobile', 'social-rocket' ); ?></a>
					<a class="add-new-h2 social-rocket-mini-button" id="social_rocket_floating_preview_desktop_toggle" href="#"><?php _e( 'Desktop', 'social-rocket' ); ?></a>
					*/ ?>
				</h3>
				<div id="social_rocket_floating_preview_inner" style="display: none;">
					<div id="social_rocket_floating_preview_desktop" class="social-rocket-floating-preview">
						<div class="social-rocket-floating-preview-before"></div>
						<?php
						social_rocket_floating( array(
							'networks' => array_keys( $SR->networks ),
						) );
						?>
						<div class="social-rocket-floating-preview-after"></div>
					</div>
					<div id="social_rocket_floating_preview_mobile" class="social-rocket-floating-preview" style="display: none;">
						<div class="social-rocket-floating-preview-before"></div>
						<?php
						social_rocket_floating( array(
							'networks'  => array_keys( $SR->networks ),
							'add_class' => 'social-rocket-mobile-only',
						) );
						?>
						<div class="social-rocket-floating-preview-after"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
		
		do_action( 'social_rocket_settings_floating_buttons_after' );
		
		$this->admin_settings_network_popups( 'floating', $settings );
		
		$this->admin_settings_footer();
		
		#endregion admin_settings_page_floating_buttons
	}
	
	
	/**
	 * Outputs Get Started settings page.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_page_get_started() {
		#region admin_settings_page_get_started
		
		$this->admin_settings_post_actions();
		
		$this->admin_settings_header();
		
		$SR = Social_Rocket::get_instance();
		
		?>
		<h2 class="nav-tab-wrapper social-rocket-tabs">
			<?php
			$tabs = apply_filters( 'social_rocket_settings_get_started_tabs', array() );
			$i = 0;
			foreach ( $tabs as $tab => $label ) {
				echo '<a href="#' . $tab . '" class="nav-tab social-rocket-tab ' . ( $i === 0 ? 'nav-tab-active' : '' ) . '" data-tab="' . $tab . '">' . $label . '</a>';
				$i++;
			}
			?>
		</h2>
		
		<h4><?php _e( 'Welcome to Social Rocket! We’re so glad you’re here!', 'social-rocket' ); ?></h4>
		<p><?php _e( 'Use the tiles below to view/change the way Social Rocket looks and behaves. There are many settings and options available to customize your sharing buttons.', 'social-rocket' ); ?></p>
		<p><?php printf( __( 'Our <a href="%s" target="_blank">documentation</a> explains each setting in greater detail. Need help finding something? <a href="%s" target="_blank">Let us know</a>!', 'social-rocket' ), 'https://docs.wpsocialrocket.com/', 'https://wpsocialrocket.com/support/?utm_source=Plugin&utm_content=settings_get_started&utm_campaign=Free' ); ?></p>
		
		<p>&nbsp;</p>
		
		<div id="social-rocket-settings-landing" class="social-rocket-settings-section">
			
			<?php do_action( 'social_rocket_settings_section_landing_before' ); ?>
			
			<div class="sr-grid">
				<?php
				$tiles = apply_filters( 'social_rocket_settings_tiles', array(
					array(
						'image' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-inline.png',
						'title' => __( 'Inline Buttons', 'social-rocket' ),
						'slug'  => 'social_rocket_inline_buttons',
					),
					array(
						'image' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-floating.png',
						'title' => __( 'Floating Buttons', 'social-rocket' ),
						'slug'  => 'social_rocket_floating_buttons',
					),
					array(
						'image' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-click-to-tweet.png',
						'title' => __( 'Click to Tweet', 'social-rocket' ),
						'slug'  => 'social_rocket_click_to_tweet',
					),
					array(
						'image' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-settings.png',
						'title' => __( 'Settings', 'social-rocket' ),
						'slug'  => 'social_rocket_settings',
					),
					array(
						'image' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-social-extras.png',
						'title' => __( 'Social Extras', 'social-rocket' ),
						'slug'  => 'social_rocket_settings#social-extras',
					),
					array(
						'image' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-license-keys.png',
						'title' => __( 'License Keys', 'social-rocket' ),
						'slug'  => 'social_rocket_license_keys',
					),
				) );
				foreach ( $tiles as $tile ) {
					?>
					<div class="social-rocket-settings-tile sr-grid__col sr-grid__col--1-of-4 sr-grid__col--l-1-of-3 sr-grid__col--m-1-of-2">
						<a href="<?php echo admin_url( 'admin.php?page='.$tile['slug'] ); ?>">
							<img src="<?php echo ( $tile['image'] ? $tile['image'] : plugin_dir_url( dirname( __FILE__ ) ) . 'img/intro-generic.png' ); ?>" alt="<?php echo $tile['title']; ?>" />
							<h4><?php echo $tile['title']; ?></h4>
						</a>
					</div>
					<?php
				}
				?>
			</div>
			
			<p>&nbsp;</p>
			
			<?php do_action( 'social_rocket_settings_section_landing_after' ); ?>
			
		</div>
		<?php
		$this->admin_settings_footer( false );
		#endregion admin_settings_page_get_started
	}
	
	
	/**
	 * Outputs Inline Buttons settings page.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_page_inline_buttons() {
		#region admin_settings_page_inline_buttons
	
		$this->admin_settings_post_actions();
		
		$this->admin_settings_header();
		
		$SR = Social_Rocket::get_instance();
		
		$settings   = $SR->settings['inline_buttons'];
		$post_types = $SR->get_post_types();
		$archives   = $SR->get_archive_types();
		
		?>
		<h2><?php _e( 'Inline Buttons', 'social-rocket' ); ?></h2>
		
		<h2 class="nav-tab-wrapper social-rocket-tabs">
			<?php
			$tabs = apply_filters( 'social_rocket_settings_inline_buttons_tabs', array(
				'inline-buttons-desktop' => __( 'Desktop', 'social-rocket' ),
				'inline-buttons-mobile'  => __( 'Mobile', 'social-rocket' ),
			) );
			$i = 0;
			foreach ( $tabs as $tab => $label ) {
				echo '<a href="#' . $tab . '" class="nav-tab social-rocket-tab ' . ( $i === 0 ? 'nav-tab-active' : '' ) . '" data-tab="' . $tab . '">' . $label . '</a>';
				$i++;
			}
			?>
		</h2>
		
		<div id="social-rocket-settings-inline-buttons-desktop" class="social-rocket-settings-section">
			<div class="social-rocket-settings-networks-selector-wrapper">
				<h3>
					<?php _e( 'Choose Social Networks', 'social-rocket' ); ?>
					<a class="add-new-h2 social-rocket-mini-button social-rocket-select-networks" href="#"><?php _e( 'Activate Networks', 'social-rocket' ); ?></a>
				</h3>
				<div class="social-rocket-settings-networks-selector-outer" style="display: none;">
					<input type="hidden" name="social_rocket_inline_networks" value="" />
					<?php foreach ( $SR->networks as $key => $value ): ?>
					<div class="social-rocket-settings-networks-selector"<?php echo ( $key === '_more' ? ' style="display: none;"' : '' ); ?>>
						<input type="checkbox" id="social_rocket_inline_networks_<?php echo $key; ?>" name="social_rocket_inline_networks[]" value="<?php echo $key; ?>" data-network="<?php echo $key; ?>" <?php checked( isset( $settings['networks'][$key] ) ); ?> />
						<label for="social_rocket_inline_networks_<?php echo $key; ?>" class="social-rocket-button social-rocket-<?php echo $key; ?>"><i class="<?php echo $SR->get_icon_class( $key ); ?>"><?php echo $SR->get_icon_svg( $key ); ?></i>
						 <?php echo $value; ?></label>
					</div>
					<?php endforeach; ?>
					<div class="social-rocket-settings-networks-selector-footer"><a href="#" class="button button-primary social-rocket-select-networks-apply"><?php _e( 'Apply Selection', 'social-rocket' ); ?></a></div>
				</div>
				<div class="social-rocket-settings-networks-empty <?php echo ( empty( $settings['networks'] ) ? 'active' : '' ); ?>" style="<?php echo ( empty( $settings['networks'] ) ? '' : 'display: none;' ); ?>">
					<p><?php _e( 'Select social networks to display', 'social-rocket' ); ?></p>
				</div>
				<table class="social-rocket-settings-networks" <?php echo ( empty( $settings['networks'] ) ? 'style="display: none;"' : '' ); ?>>
					<thead>
						<th style="white-space: nowrap;"><span class="dashicons dashicons-sort"></span></th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Network', 'social-rocket' ); ?></th>
						<th width="99%"> </th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Actions', 'social-rocket' ); ?></th>
					</thead>
					<tbody class="social-rocket-settings-networks-sortable">
						<?php
						
						// available networks
						$available_networks = $SR->networks;
						if ( isset( $available_networks['_more'] ) ) {
							$tmp = $available_networks['_more']; // move "more" to the end
							unset( $available_networks['_more'] );
							$available_networks['_more'] = $tmp;
						}
						
						// active networks in this scope
						$active_networks = array();
						foreach ( $settings['networks'] as $key => $value ) {
							if ( isset( $available_networks[ $key ] ) ) {
								$active_networks[$key] = $value['name'];
							} else {
								unset( $settings['networks'][$key] );
							}
						}
						$active_networks = array_merge( // this will keep inactive networks at the end of the array
							$active_networks,
							array_diff_key( $available_networks, $settings['networks'] )
						);
						
						foreach ( $active_networks as $key => $value ):
						?>
						<tr data-network="<?php echo $key; ?>"<?php echo ( isset( $settings['networks'][$key] ) ? ' class="active"' : ' style="display: none;"' ); ?>>
							<td class="social-rocket-settings-networks-sort-handle"><span class="dashicons dashicons-menu"></span></td>
							<td><i class="<?php echo $SR->get_icon_class( $key ); ?> social-rocket-button social-rocket-<?php echo $key; ?>"><?php echo $SR->get_icon_svg( $key ); ?></i></td>
							<td style="white-space: nowrap;">
								<?php echo $value; ?>
								<?php if ( $key === '_more' ): ?>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none; white-space: normal;"><?php _e( 'The "More" button is a special button; all networks after this will be hidden behind it until clicked.', 'social-rocket' ); ?></div>
								<?php endif; ?>
							</td>
							<td></td>
							<td>
								<?php if ( property_exists( 'Social_Rocket_'.ucfirst($key), 'configurable_settings' ) ): ?>
								<a class="thickbox button button-small" href="#TB_inline?width=760&height=550&inlineId=social-rocket-settings-inline-network-<?php echo $key; ?>" title="<?php _e( 'Advanced Network Settings', 'social-rocket' ); ?>"><?php _e( 'Advanced', 'social-rocket' ); ?></a></td>
								<?php endif; ?>
							<td><a class="social-rocket-settings-networks-remove button button-small" href="#"><?php _e( 'Remove', 'social-rocket' ); ?></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<input type="hidden" name="social_rocket_inline_networks_order" value="<?php echo implode( ',', array_keys( $settings['networks'] ) ); ?>" />
			</div>
			<p>&nbsp;</p>
			<h3><?php _e( 'Color Scheme Settings', 'social-rocket' ); ?></h3>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="social_rocket_inline_button_color_scheme"><?php _e( 'Button color scheme', 'social-rocket' ); ?></label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Configure the color scheme for your buttons here. If you want to override a specific button\'s colors, go to the "Advanced" settings for that network (see above).', 'social-rocket' ); ?></div>
					</th>
					<td>
						<select name="social_rocket_inline_button_color_scheme" id="social_rocket_inline_button_color_scheme">
							<option value="default" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
							<option value="inverted" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'inverted' ); ?>><?php _e( 'Inverted', 'social-rocket' ); ?></option>
							<option value="custom" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'custom' ); ?>><?php _e( 'Custom', 'social-rocket' ); ?></option>
						</select>
						<p class="social_rocket_inline_button_color_scheme_custom_toggle" <?php echo ( $this->_isset( $settings['button_color_scheme'] ) === 'custom' ? '' : 'style="display: none;"' ); ?>><a class="social-rocket-collapsable-toggle" data-sr-toggle=".social_rocket_inline_button_color_scheme_custom_colors"><i class="fas fa-plus-square"></i> <span><?php _e( 'Show', 'social-rocket' ); ?></span></a></p>
						<div class="postbox social_rocket_inline_button_color_scheme_custom_colors" style="display: none;">
							<div class="sr-grid">
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Icon Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_inline_button_color_scheme_custom_icon" id="social_rocket_inline_button_color_scheme_custom_icon_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_icon'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_inline_button_color_scheme_custom_icon_color" id="social_rocket_inline_button_color_scheme_custom_icon_color" data-for="social_rocket_inline_button_color_scheme_custom_icon_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_icon_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_inline_button_color_scheme_custom_icon_network_icon">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_icon" id="social_rocket_inline_button_color_scheme_custom_icon_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_icon' ); ?> /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_icon_network_background">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_icon" id="social_rocket_inline_button_color_scheme_custom_icon_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_background' ); ?> /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_icon_network_border">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_icon" id="social_rocket_inline_button_color_scheme_custom_icon_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_border' ); ?> /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Icon Hover Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover" id="social_rocket_inline_button_color_scheme_custom_hover_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_inline_button_color_scheme_custom_hover_color" id="social_rocket_inline_button_color_scheme_custom_hover_color" data-for="social_rocket_inline_button_color_scheme_custom_hover_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_hover_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_inline_button_color_scheme_custom_hover_network_icon">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover" id="social_rocket_inline_button_color_scheme_custom_hover_network_icon" value="network_hover" <?php checked( $settings['button_color_scheme_custom_hover'] === 'network_hover' ); ?> /> <?php _e( 'Use Network Default Icon Hover Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_hover_none">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover" id="social_rocket_inline_button_color_scheme_custom_hover_none" value="none" <?php checked( $settings['button_color_scheme_custom_hover'] === 'none' ); ?> /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Background Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_inline_button_color_scheme_custom_background" id="social_rocket_inline_button_color_scheme_custom_background_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_background'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_inline_button_color_scheme_custom_background_color" id="social_rocket_inline_button_color_scheme_custom_background_color" data-for="social_rocket_inline_button_color_scheme_custom_background_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_background_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_inline_button_color_scheme_custom_background_network_background">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_background" id="social_rocket_inline_button_color_scheme_custom_background_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_background' ); ?> /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_background_network_icon">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_background" id="social_rocket_inline_button_color_scheme_custom_background_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_icon' ); ?> /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_background_network_border">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_background" id="social_rocket_inline_button_color_scheme_custom_background_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_border' ); ?> /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_background_none">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_background" id="social_rocket_inline_button_color_scheme_custom_background_none" value="none" <?php checked( $settings['button_color_scheme_custom_background'] === 'none' ); ?> /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Background Hover Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover_bg" id="social_rocket_inline_button_color_scheme_custom_hover_bg_custom" value="custom" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_inline_button_color_scheme_custom_hover_bg_color" id="social_rocket_inline_button_color_scheme_custom_hover_bg_color" data-for="social_rocket_inline_button_color_scheme_custom_hover_bg_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_hover_bg_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_inline_button_color_scheme_custom_hover_bg_network_icon">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover_bg" id="social_rocket_inline_button_color_scheme_custom_hover_bg_network_icon" value="network_hover_bg" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'network_hover_bg' ); ?> /> <?php _e( 'Use Network Default Background Hover Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_hover_bg_none">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover_bg" id="social_rocket_inline_button_color_scheme_custom_hover_bg_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'none' ); ?> /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Border Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_inline_button_color_scheme_custom_border" id="social_rocket_inline_button_color_scheme_custom_border_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_border'] === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_inline_button_color_scheme_custom_border_color" id="social_rocket_inline_button_color_scheme_custom_border_color" data-for="social_rocket_inline_button_color_scheme_custom_border_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_border_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_inline_button_color_scheme_custom_border_network_border">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_border" id="social_rocket_inline_button_color_scheme_custom_border_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_border' ); ?> /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_border_network_icon">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_border" id="social_rocket_inline_button_color_scheme_custom_border_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_icon' ); ?> /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_border_network_background">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_border" id="social_rocket_inline_button_color_scheme_custom_border_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_background' ); ?> /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_border_none">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_border" id="social_rocket_inline_button_color_scheme_custom_border_none" value="none" <?php checked( $settings['button_color_scheme_custom_border'] === 'none' ); ?> /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
									</label>
								</div>
								<div class="sr-grid__col sr-grid__col--1-of-2">
									<p><strong><?php _e( 'Custom Border Hover Color', 'social-rocket' ); ?></strong></p>
									<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover_border" id="social_rocket_inline_button_color_scheme_custom_hover_border_custom" value="custom" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'custom' ); ?> /> 
									<div style="display: inline-block; line-height: 30px;">
										<input type="text" class="social-rocket-color-picker" name="social_rocket_inline_button_color_scheme_custom_hover_border_color" id="social_rocket_inline_button_color_scheme_custom_hover_border_color" data-for="social_rocket_inline_button_color_scheme_custom_hover_border_custom" value="<?php echo esc_attr( $this->_isset( $settings['button_color_scheme_custom_hover_border_color'], '' ) ); ?>" />
									</div><br /><br />
									<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_network_icon">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover_border" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_network_icon" value="network_hover_border" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'network_hover_border' ); ?> /> <?php _e( 'Use Network Default Border Hover Color', 'social-rocket' ); ?>
									</label><br />
									<label for="social_rocket_inline_button_color_scheme_custom_hover_border_none">
										<input type="radio" name="social_rocket_inline_button_color_scheme_custom_hover_border" id="social_rocket_inline_button_color_scheme_custom_hover_border_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'none' ); ?> /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
									</label>
									<p>&nbsp;</p>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<p>&nbsp;</p>
			<h3><?php _e( 'Display Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label><?php _e( 'Default buttons placement', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Sets the default placement position for your inline buttons, on all individual pages, posts, CPTs.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<label for="social_rocket_inline_default_position_above">
									<input type="radio" name="social_rocket_inline_default_position" id="social_rocket_inline_default_position_above" value="above" <?php checked( $settings['default_position'] === 'above' ); ?> />
									<?php _e( 'Above the content', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_position_below">
									<input type="radio" name="social_rocket_inline_default_position" id="social_rocket_inline_default_position_below" value="below" <?php checked( $settings['default_position'] === 'below' ); ?> />
									<?php _e( 'Below the content', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_position_both">
									<input type="radio" name="social_rocket_inline_default_position" id="social_rocket_inline_default_position_both" value="both" <?php checked( $settings['default_position'] === 'both' ); ?> />
									<?php _e( 'Both above and below the content', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_position_none">
									<input type="radio" name="social_rocket_inline_default_position" id="social_rocket_inline_default_position_none" value="none" <?php checked( $settings['default_position'] === 'none' ); ?> />
									<?php _e( 'None/manual placement', 'social-rocket' ); ?>
								</label>
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label><?php _e( 'Archive buttons placement', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Sets the default placement position for your inline buttons, on all archives pages.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<label for="social_rocket_inline_default_archive_position_above">
									<input type="radio" name="social_rocket_inline_default_archive_position" id="social_rocket_inline_default_archive_position_above" value="above" <?php checked( $settings['default_archive_position'] === 'above' ); ?> />
									<?php _e( 'Above the content (shares the archive)', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_archive_position_below">
									<input type="radio" name="social_rocket_inline_default_archive_position" id="social_rocket_inline_default_archive_position_below" value="below" <?php checked( $settings['default_archive_position'] === 'below' ); ?> />
									<?php _e( 'Below the content (shares the archive)', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_archive_position_both">
									<input type="radio" name="social_rocket_inline_default_archive_position" id="social_rocket_inline_default_archive_position_both" value="both" <?php checked( $settings['default_archive_position'] === 'both' ); ?> />
									<?php _e( 'Both above and below (shares the archive)', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_archive_position_item">
									<input type="radio" name="social_rocket_inline_default_archive_position" id="social_rocket_inline_default_archive_position_item" value="item" <?php checked( $settings['default_archive_position'] === 'item' ); ?> />
									<?php _e( 'Buttons for each item (shares the item)', 'social-rocket' ); ?>
								</label>
								<label for="social_rocket_inline_default_archive_position_none">
									<input type="radio" name="social_rocket_inline_default_archive_position" id="social_rocket_inline_default_archive_position_none" value="none" <?php checked( $settings['default_archive_position'] === 'none' ); ?> />
									<?php _e( 'None/manual placement', 'social-rocket' ); ?>
								</label>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_button_style"><?php _e( 'Button style', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Choose which style of buttons you want to be displayed (oval, rectangle, round, square).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<select name="social_rocket_inline_button_style" id="social_rocket_inline_button_style">
									<option value="oval" <?php selected( $this->_isset( $settings['button_style'] ), 'oval' ); ?>><?php _e( 'Oval', 'social-rocket' ); ?></option>
									<option value="rectangle" <?php selected( $this->_isset( $settings['button_style'] ), 'rectangle' ); ?>><?php _e( 'Rectangle', 'social-rocket' ); ?></option>
									<option value="round" <?php selected( $this->_isset( $settings['button_style'] ), 'round' ); ?>><?php _e( 'Round', 'social-rocket' ); ?></option>
									<option value="square" <?php selected( $this->_isset( $settings['button_style'] ), 'square' ); ?>><?php _e( 'Square', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_button_alignment"><?php _e( 'Button alignment', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_inline_button_alignment" id="social_rocket_inline_button_alignment">
									<option value="left" <?php selected( $this->_isset( $settings['button_alignment'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
									<option value="center" <?php selected( $this->_isset( $settings['button_alignment'] ), 'center' ); ?>><?php _e( 'Center', 'social-rocket' ); ?></option>
									<option value="right" <?php selected( $this->_isset( $settings['button_alignment'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
									<option value="stretch" <?php selected( $this->_isset( $settings['button_alignment'] ), 'stretch' ); ?><?php echo ( in_array( $settings['button_style'], array( 'round', 'square' ) ) ? ' style="display:none;"' : '' ); ?>><?php _e( 'Stretch', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_button_size"><?php _e( 'Button size (%)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Increase or decrease the size of the buttons by percentage (default: 100).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_button_size" id="social_rocket_inline_button_size" value="<?php echo $this->_isset( $settings['button_size'], '' ); ?>" />
							</td>
						</tr>
						<tr class="social_rocket_inline_button_show_cta_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'oval' ) ) ? '' : ' style="display: none;"'; ?>>
							<th scope="row">
								<label for="social_rocket_inline_button_show_cta"><?php _e( 'Show Button Text', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the Button Text for each button. If you want to override a specific button\'s Button Text, go to the "Advanced" settings for that network (see above).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_inline_button_show_cta" value="0" />
								<input type="checkbox" name="social_rocket_inline_button_show_cta" id="social_rocket_inline_button_show_cta" value="1" <?php checked( $settings['button_show_cta'] ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_heading_text"><?php _e( 'Heading text (optional)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php printf( __( 'If entered, this text will be displayed above the inline sharing buttons. (You can customize the styling by adding <a href="%s">custom CSS</a> code targeting the class ".social-rocket-buttons-heading".)', 'social-rocket' ), admin_url( 'admin.php?page=social_rocket_settings#advanced' ) ); ?></div>
							</th>
							<td>
								<input type="text" name="social_rocket_inline_heading_text" id="social_rocket_inline_heading_text" value="<?php echo $settings['heading_text']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_heading_alignment"><?php _e( 'Heading alignment', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_inline_heading_alignment" id="social_rocket_inline_heading_alignment">
									<option value="default" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
									<option value="left" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
									<option value="right" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
									<option value="center" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'center' ); ?>><?php _e( 'Center', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_heading_element"><?php _e( 'Heading element', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_inline_heading_element" id="social_rocket_inline_heading_element">
									<option value="h1" <?php selected( $this->_isset( $settings['heading_element'] ), 'h1' ); ?>><?php _e( 'H1', 'social-rocket' ); ?></option>
									<option value="h2" <?php selected( $this->_isset( $settings['heading_element'] ), 'h2' ); ?>><?php _e( 'H2', 'social-rocket' ); ?></option>
									<option value="h3" <?php selected( $this->_isset( $settings['heading_element'] ), 'h3' ); ?>><?php _e( 'H3', 'social-rocket' ); ?></option>
									<option value="h4" <?php selected( $this->_isset( $settings['heading_element'] ), 'h4' ); ?>><?php _e( 'H4 (Default)', 'social-rocket' ); ?></option>
									<option value="h5" <?php selected( $this->_isset( $settings['heading_element'] ), 'h5' ); ?>><?php _e( 'H5', 'social-rocket' ); ?></option>
									<option value="h6" <?php selected( $this->_isset( $settings['heading_element'] ), 'h6' ); ?>><?php _e( 'H6', 'social-rocket' ); ?></option>
									<option value="p" <?php selected( $this->_isset( $settings['heading_element'] ), 'p' ); ?>><?php _e( 'P', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_border"><?php _e( 'Border', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Set the style of the border around your buttons.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<select name="social_rocket_inline_border" id="social_rocket_inline_border">
									<option value="none" <?php selected( $this->_isset( $settings['border'] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
									<option value="solid" <?php selected( $this->_isset( $settings['border'] ), 'solid' ); ?>><?php _e( 'Solid', 'social-rocket' ); ?></option>
									<option value="double" <?php selected( $this->_isset( $settings['border'] ), 'double' ); ?>><?php _e( 'Double', 'social-rocket' ); ?></option>
									<option value="dashed" <?php selected( $this->_isset( $settings['border'] ), 'dashed' ); ?>><?php _e( 'Dashed', 'social-rocket' ); ?></option>
									<option value="dotted" <?php selected( $this->_isset( $settings['border'] ), 'dotted' ); ?>><?php _e( 'Dotted', 'social-rocket' ); ?></option>
									<option value="inset" <?php selected( $this->_isset( $settings['border'] ), 'inset' ); ?>><?php _e( 'Inset', 'social-rocket' ); ?></option>
									<option value="outset" <?php selected( $this->_isset( $settings['border'] ), 'outset' ); ?>><?php _e( 'Outset', 'social-rocket' ); ?></option>
									<option value="groove" <?php selected( $this->_isset( $settings['border'] ), 'groove' ); ?>><?php _e( 'Groove', 'social-rocket' ); ?></option>
									<option value="ridge" <?php selected( $this->_isset( $settings['border'] ), 'ridge' ); ?>><?php _e( 'Ridge', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_border_size"><?php _e( 'Border size (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Thickness of the border, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_border_size" id="social_rocket_inline_border_size" value="<?php echo $settings['border_size']; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_inline_border_radius_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'square' ) ) ? '' : ' style="display: none;"'; ?>>
							<th scope="row">
								<label for="social_rocket_inline_border_radius"><?php _e( 'Border radius (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Allows you to round the corners of the buttons.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_border_radius" id="social_rocket_inline_border_radius" value="<?php echo $settings['border_radius']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_margin_right"><?php _e( 'Horizontal margin (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Determines the horizontal space between buttons, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_margin_right" id="social_rocket_inline_margin_right" value="<?php echo $settings['margin_right']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_margin_bottom"><?php _e( 'Vertical margin (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Determines the vertical space between buttons, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_margin_bottom" id="social_rocket_inline_margin_bottom" value="<?php echo $settings['margin_bottom']; ?>" />
							</td>
						</tr>
						<?php echo apply_filters( 'social_rocket_settings_inline_more_enable_html', '
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_more_enable">' . __( '(PRO) Enable "More" button', 'social-rocket' ) . '</label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;">' . __( 'Allows extra networks to be combined behind a "more" button.', 'social-rocket' ) . '</div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_inline_more_enable" value="0" />
								<input type="checkbox" name="social_rocket_inline_more_enable" id="social_rocket_inline_more_enable" value="1" disabled />
								<p class="description">' . sprintf( __( '%s adds the "More" button feature, which allows you to combine extra networks into one button.', 'social-rocket' ), '<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_inline_more_enable&utm_campaign=Free" target="_blank">Social Rocket Pro</a>' ) . '</p>
							</td>
						</tr>
						' ); ?>
					</table>
				</div>
			</div>
			<p>&nbsp;</p>
			<h3><?php _e( 'Share Count Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_show_counts"><?php _e( 'Show share count', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the share count for each button (if applicable).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_inline_show_counts" value="0" />
								<input type="checkbox" name="social_rocket_inline_show_counts" id="social_rocket_inline_show_counts" value="1" <?php checked( $settings['show_counts'] ); ?> />
							</td>
						</tr>
						<tr class="social_rocket_inline_show_counts_min"<?php echo ( $settings['show_counts'] ? '' : ' style="display:none;"' ) ; ?>>
							<th scope="row">
								<label for="social_rocket_inline_show_counts_min"><?php _e( 'Minimum shares', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the network\'s share count only if greater than or equal to this number.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_show_counts_min" id="social_rocket_inline_show_counts_min" value="<?php echo $settings['show_counts_min'] ; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_rounding"><?php _e( 'Round large share counts', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If any share count is greater than 1000, it will be rounded to a shorter format.  For example, 1234 will show as 1.2K, etc.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_inline_rounding" value="0" />
								<input type="checkbox" name="social_rocket_inline_rounding" id="social_rocket_inline_rounding" value="1" <?php checked( $settings['rounding'] ); ?> />
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_show_total"><?php _e( 'Show total share count', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the total share count for all social networks combined.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_inline_show_total" value="0" />
								<input type="checkbox" name="social_rocket_inline_show_total" id="social_rocket_inline_show_total" value="1" <?php checked( $settings['show_total'] ); ?> />
							</td>
						</tr>
						<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
							<th scope="row">
								<label for="social_rocket_inline_show_total_min"><?php _e( 'Minimum total', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Display the total share count only if greater than or equal to this number.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_show_total_min" id="social_rocket_inline_show_total_min" value="<?php echo $settings['show_total_min'] ; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
							<th scope="row">
								<label for="social_rocket_inline_total_position"><?php _e( 'Total share count position', 'social-rocket' ); ?></label>
							</th>
							<td>
								<select name="social_rocket_inline_total_position" id="social_rocket_inline_total_position">
									<option value="before" <?php selected( $this->_isset( $settings['total_position'] ), 'before' ); ?>><?php _e( 'Before Buttons', 'social-rocket' ); ?></option>
									<option value="after" <?php selected( $this->_isset( $settings['total_position'] ), 'after' ); ?>><?php _e( 'After Buttons', 'social-rocket' ); ?></option>
								</select>
							</td>
						</tr>
						<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_inline_total_color"><?php _e( 'Total share text/icon color', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_inline_total_color" id="social_rocket_inline_total_color" class="social-rocket-color-picker" value="<?php echo $settings['total_color']; ?>" />
							</td>
						</tr>
						<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
							<th scope="row">
								<label for="social_rocket_inline_total_show_icon"><?php printf( __( 'Show total share icon (%s)', 'social-rocket' ), '<i class="fas fa-share-alt"></i>' ); ?></label>
							</th>
							<td>
								<input type="hidden" name="social_rocket_inline_total_show_icon" value="0" />
								<input type="checkbox" name="social_rocket_inline_total_show_icon" id="social_rocket_inline_total_show_icon" value="1" <?php checked( $this->_isset( $settings['total_show_icon'] ) ); ?> />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<p>&nbsp;</p>
			<h3><?php _e( 'Advanced Placement Settings', 'social-rocket' ); ?></h3>
			<p class="description"><?php _e( 'The defaults are set above under Display Settings, but you can (optionally) refine it here if you want something other than the default for individual pages, posts, CPTs, or archives.', 'social-rocket' ); ?></p>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<h4>
						<?php _e( 'Individual Pages, Posts, CPTs', 'social-rocket' ); ?>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Here you can override the placement settings for specific post types.', 'social-rocket' ); ?></div>
					</h4>
					<div id="social-rocket-inline-position-posts">
						<table class="form-table">
							<?php foreach ( $post_types as $post_type ): ?>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_position_post_type_<?php echo esc_attr( $post_type ); ?>"><?php echo ucfirst( $post_type ); ?></label>
								</th>
								<td>
									<select name="social_rocket_inline_position_post_type_<?php echo esc_attr( $post_type ); ?>" id="social_rocket_inline_position_post_type_<?php echo esc_attr( $post_type ); ?>">
										<option value="default" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
										<option value="above" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'above' ); ?>><?php _e( 'Above the content', 'social-rocket' ); ?></option>
										<option value="below" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'below' ); ?>><?php _e( 'Below the content', 'social-rocket' ); ?></option>
										<option value="both" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'both' ); ?>><?php _e( 'Both above and below the content', 'social-rocket' ); ?></option>
										<option value="none" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'none' ); ?>><?php _e( 'None/manual placement', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<h4>
						<?php _e( 'Archives Pages', 'social-rocket' ); ?>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Here you can override the placement settings for specific types of archives pages.', 'social-rocket' ); ?></div>
					</h4>
					<div id="social-rocket-inline-position-archives">
						<table class="form-table">
							<?php foreach ( $archives as $key => $archive ): ?>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_position_archive_<?php echo esc_attr( $key ); ?>"><?php echo $archive['display_name']; ?></label>
								</th>
								<td>
									<select name="social_rocket_inline_position_archive_<?php echo esc_attr( $key ); ?>" id="social_rocket_inline_position_archive_<?php echo esc_attr( $key ); ?>">
										<option value="default" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
										<option value="above" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'above' ); ?>><?php _e( 'Above the content (shares the archive)', 'social-rocket' ); ?></option>
										<option value="below" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'below' ); ?>><?php _e( 'Below the content (shares the archive)', 'social-rocket' ); ?></option>
										<option value="both" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'both' ); ?>><?php _e( 'Both above and below (shares the archive)', 'social-rocket' ); ?></option>
										<option value="item" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'item' ); ?>><?php _e( 'Buttons for each item (shares the item)', 'social-rocket' ); ?></option>
										<option value="none" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'none' ); ?>><?php _e( 'None/manual placement', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<?php do_action( 'social_rocket_settings_inline_buttons_desktop_after' ); ?>
		</div>
		
		<div id="social-rocket-settings-inline-buttons-mobile" class="social-rocket-settings-section" style="display: none;">
			<h3><?php _e( 'Mobile Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<td style="width:40px;">
								<input type="radio" name="social_rocket_inline_mobile_setting" id="social_rocket_inline_mobile_setting_disabled" value="disabled" <?php checked( $SR->settings['inline_mobile_setting'] === 'disabled' ); ?> />
							</td>
							<td>
								<label for="social_rocket_inline_mobile_setting_disabled"><strong><?php _e( 'Disable on mobile', 'social-rocket' ); ?></strong></label>
							</td>
						</tr>
						<tr>
							<td>
								<input type="radio" name="social_rocket_inline_mobile_setting" id="social_rocket_inline_mobile_setting_default" value="default" <?php checked( $SR->settings['inline_mobile_setting'] === 'default' ); ?> />
							</td>
							<td>
								<label for="social_rocket_inline_mobile_setting_default"><strong><?php _e( 'Use same settings as desktop', 'social-rocket' ); ?></strong></label>
							</td>
						</tr>
						<?php echo apply_filters( 'social_rocket_settings_inline_mobile_setting_html', '
						<tr>
							<td>
								<input type="radio" name="social_rocket_inline_mobile_setting" id="social_rocket_inline_mobile_setting_custom" value="custom" disabled />
							</td>
							<td>
								<label for="social_rocket_inline_mobile_setting_custom"><strong>' . __( '(PRO) Use custom settings:', 'social-rocket' ) . '</strong></label>
								<p class="description">' . sprintf( __( '%s adds the ability to customize all the following settings specifically for mobile devices:', 'social-rocket' ), '<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_inline_mobile_custom&utm_campaign=Free" target="_blank">Social Rocket Pro</a>' ) . '</p>
							</td>
						</tr>
						' ); ?>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">			
						<tr>
							<th scope="row">
								<label for="social_rocket_inline_mobile_breakpoint"><?php _e( 'Mobile breakpoint (px)', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Mobile styling applies to screens less than or equal to this width, in pixels.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_inline_mobile_breakpoint" id="social_rocket_inline_mobile_breakpoint" value="<?php echo $SR->settings['inline_mobile_breakpoint']; ?>" />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="social-rocket-settings-inline-buttons-mobile-default" <?php echo ( $SR->settings['inline_mobile_setting'] === 'default' ? '' : 'style="display:none;"' ); ?>>
				<h3>
					<?php _e( 'Choose Social Networks', 'social-rocket' ); ?>
					<a class="add-new-h2 social-rocket-mini-button" href="#"><?php _e( 'Activate Networks', 'social-rocket' ); ?></a>
				</h3>
				<div class="social-rocket-settings-networks-empty <?php echo ( empty( $settings['networks'] ) ? 'active' : '' ); ?>" style="<?php echo ( empty( $settings['networks'] ) ? '' : 'display: none;' ); ?>">
					<p><?php _e( 'Select social networks to display', 'social-rocket' ); ?></p>
				</div>
				<table class="social-rocket-settings-networks" <?php echo ( empty( $settings['networks'] ) ? 'style="display: none;"' : '' ); ?>>
					<thead>
						<th style="white-space: nowrap;"><span class="dashicons dashicons-sort"></span></th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Network', 'social-rocket' ); ?></th>
						<th width="99%"> </th>
						<th colspan="2" style="white-space: nowrap;"><?php _e( 'Actions', 'social-rocket' ); ?></th>
					</thead>
					<tbody class="social-rocket-settings-networks-sortable inactive">
						<?php
						
						// available networks
						$available_networks = $SR->networks;
						if ( isset( $available_networks['_more'] ) ) {
							$tmp = $available_networks['_more']; // move "more" to the end
							unset( $available_networks['_more'] );
							$available_networks['_more'] = $tmp;
						}
						
						// active networks in this scope
						$active_networks = array();
						foreach ( $settings['networks'] as $key => $value ) {
							if ( isset( $available_networks[ $key ] ) ) {
								$active_networks[$key] = $value['name'];
							} else {
								unset( $settings['networks'][$key] );
							}
						}
						$active_networks = array_merge( // this will keep inactive networks at the end of the array
							$active_networks,
							array_diff_key( $available_networks, $settings['networks'] )
						);
						
						foreach ( $active_networks as $key => $value ):
						?>
						<tr data-network="<?php echo $key; ?>"<?php echo ( isset( $settings['networks'][$key] ) ? ' class="active"' : ' style="display: none;"' ); ?>>
							<td class="social-rocket-settings-networks-sort-handle"><span class="dashicons dashicons-menu"></span></td>
							<td><i class="<?php echo $SR->get_icon_class( $key ); ?> social-rocket-button social-rocket-<?php echo $key; ?>"><?php echo $SR->get_icon_svg( $key ); ?></i></td>
							<td style="white-space: nowrap;">
								<?php echo $value; ?>
								<?php if ( $key === '_more' ): ?>
								<a><span class="dashicons dashicons-editor-help"></span></a>
								<?php endif; ?>
							</td>
							<td></td>
							<td>
								<?php if ( property_exists( 'Social_Rocket_'.ucfirst($key), 'configurable_settings' ) ): ?>
								<a class="button button-small" href="#" title="<?php _e( 'Advanced Network Settings', 'social-rocket' ); ?>"><?php _e( 'Advanced', 'social-rocket' ); ?></a></td>
								<?php endif; ?>
							<td><a class="button button-small" href="#"><?php _e( 'Remove', 'social-rocket' ); ?></a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p>&nbsp;</p>
				<h3><?php _e( 'Color Scheme Settings', 'social-rocket' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="social_rocket_inline_mobile_button_color_scheme"><?php _e( 'Button color scheme', 'social-rocket' ); ?></label>
							<a><span class="dashicons dashicons-editor-help"></span></a>
						</th>
						<td>
							<select id="social_rocket_inline_mobile_button_color_scheme" disabled>
								<option value="default" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
								<option value="inverted" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'inverted' ); ?>><?php _e( 'Inverted', 'social-rocket' ); ?></option>
								<option value="custom" <?php selected( $this->_isset( $settings['button_color_scheme'] ), 'custom' ); ?>><?php _e( 'Custom', 'social-rocket' ); ?></option>
							</select>
							<p class="social_rocket_inline_button_color_scheme_custom_toggle" <?php echo ( $this->_isset( $settings['button_color_scheme'] ) === 'custom' ? '' : 'style="display: none;"' ); ?>><a data-sr-toggle=".social_rocket_inline_button_color_scheme_custom_colors"><i class="fas fa-plus-square"></i> <span><?php _e( 'Show', 'social-rocket' ); ?></span></a></p>
							<div class="postbox social_rocket_inline_button_color_scheme_custom_colors" style="display: none;">
								<div class="sr-grid">
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Icon Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_icon_custom">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_icon" id="social_rocket_inline_mobile_button_color_scheme_custom_icon_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_icon'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_button_color_scheme_custom_icon_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_icon_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_icon_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_icon_network_icon">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_icon" id="social_rocket_inline_mobile_button_color_scheme_custom_icon_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_icon' ); ?> disabled /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_icon_network_background">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_icon" id="social_rocket_inline_mobile_button_color_scheme_custom_icon_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_background' ); ?> disabled /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_icon_network_border">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_icon" id="social_rocket_inline_mobile_button_color_scheme_custom_icon_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_icon'] === 'network_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Icon Hover Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_custom">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_hover_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_hover_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_network_icon">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_network_icon" value="network_hover" <?php checked( $settings['button_color_scheme_custom_hover'] === 'network_hover' ); ?> disabled /> <?php _e( 'Use Network Default Icon Hover Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_none">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_none" value="none" <?php checked( $settings['button_color_scheme_custom_hover'] === 'none' ); ?> disabled /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Background Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_background_custom">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_background" id="social_rocket_inline_mobile_button_color_scheme_custom_background_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_background'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_button_color_scheme_custom_background_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_background_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_background_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_background_network_background">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_background" id="social_rocket_inline_mobile_button_color_scheme_custom_background_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_background' ); ?> disabled /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_background_network_icon">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_background" id="social_rocket_inline_mobile_button_color_scheme_custom_background_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_icon' ); ?> disabled /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_background_network_border">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_background" id="social_rocket_inline_mobile_button_color_scheme_custom_background_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_background'] === 'network_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_background_none">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_background" id="social_rocket_inline_mobile_button_color_scheme_custom_background_none" value="none" <?php checked( $settings['button_color_scheme_custom_background'] === 'none' ); ?> disabled /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Background Hover Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_custom">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover_bg'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_hover_bg_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_hover_bg_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_network_icon">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_network_icon" value="network_hover_bg" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'network_hover_bg' ); ?> disabled /> <?php _e( 'Use Network Default Background Hover Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_none">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_bg_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_bg'] ) === 'none' ); ?> disabled /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Border Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_border_custom">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_border" id="social_rocket_inline_mobile_button_color_scheme_custom_border_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_border'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_button_color_scheme_custom_border_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_border_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_border_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_border_network_border">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_border" id="social_rocket_inline_mobile_button_color_scheme_custom_border_network_border" value="network_border" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_border_network_icon">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_border" id="social_rocket_inline_mobile_button_color_scheme_custom_border_network_icon" value="network_icon" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_icon' ); ?> disabled /> <?php _e( 'Use Network Default Icon Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_border_network_background">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_border" id="social_rocket_inline_mobile_button_color_scheme_custom_border_network_background" value="network_background" <?php checked( $settings['button_color_scheme_custom_border'] === 'network_background' ); ?> disabled /> <?php _e( 'Use Network Default Background Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_border_none">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_border" id="social_rocket_inline_mobile_button_color_scheme_custom_border_none" value="none" <?php checked( $settings['button_color_scheme_custom_border'] === 'none' ); ?> disabled /> <?php _e( 'None (Transparent)', 'social-rocket' ); ?>
										</label>
									</div>
									<div class="sr-grid__col sr-grid__col--1-of-2">
										<p><strong><?php _e( 'Custom Border Hover Color', 'social-rocket' ); ?></strong></p>
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_custom">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover_border" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_custom" value="custom" <?php checked( $settings['button_color_scheme_custom_hover_border'] === 'custom' ); ?> disabled /> 
											<div>
												<div class="wp-picker-container">
													<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['button_color_scheme_custom_hover_border_color'] ) ? 'background-color: ' . $settings['button_color_scheme_custom_hover_border_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
												</div>
											</div>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_network_icon">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover_border" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_network_icon" value="network_hover_border" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'network_hover_border' ); ?> disabled /> <?php _e( 'Use Network Default Border Hover Color', 'social-rocket' ); ?>
										</label><br />
										<label for="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_none">
											<input type="radio" name="social_rocket_inline_mobile_button_color_scheme_custom_hover_border" id="social_rocket_inline_mobile_button_color_scheme_custom_hover_border_none" value="none" <?php checked( $this->_isset( $settings['button_color_scheme_custom_hover_border'] ) === 'none' ); ?> disabled /> <?php _e( 'None (No Hover Effect)', 'social-rocket' ); ?>
										</label>
										<p>&nbsp;</p>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
				<p>&nbsp;</p>
				<h3><?php _e( 'Display Settings', 'social-rocket' ); ?></h3>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label><?php _e( 'Default buttons placement', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<label for="social_rocket_inline_mobile_default_position_above">
										<input type="radio" name="social_rocket_inline_mobile_default_position" id="social_rocket_inline_mobile_default_position_above" value="above" <?php checked( $settings['default_position'] === 'above' ); ?> disabled />
										<?php _e( 'Above the content', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_position_below">
										<input type="radio" name="social_rocket_inline_mobile_default_position" id="social_rocket_inline_mobile_default_position_below" value="below" <?php checked( $settings['default_position'] === 'below' ); ?> disabled />
										<?php _e( 'Below the content', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_position_both">
										<input type="radio" name="social_rocket_inline_mobile_default_position" id="social_rocket_inline_mobile_default_position_both" value="both" <?php checked( $settings['default_position'] === 'both' ); ?> disabled />
										<?php _e( 'Both above and below the content', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_position_none">
										<input type="radio" name="social_rocket_inline_mobile_default_position" id="social_rocket_inline_mobile_default_position_none" value="none" <?php checked( $settings['default_position'] === 'none' ); ?> disabled />
										<?php _e( 'None/manual placement', 'social-rocket' ); ?>
									</label>
								</td>
							</tr>
						</table>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label><?php _e( 'Archive buttons placement', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<label for="social_rocket_inline_mobile_default_archive_position_above">
										<input type="radio" name="social_rocket_inline_mobile_default_archive_position" id="social_rocket_inline_mobile_default_archive_position_above" value="above" <?php checked( $settings['default_archive_position'] === 'above' ); ?> disabled />
										<?php _e( 'Above the content (shares the archive)', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_archive_position_below">
										<input type="radio" name="social_rocket_inline_mobile_default_archive_position" id="social_rocket_inline_mobile_default_archive_position_below" value="below" <?php checked( $settings['default_archive_position'] === 'below' ); ?> disabled />
										<?php _e( 'Below the content (shares the archive)', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_archive_position_both">
										<input type="radio" name="social_rocket_inline_mobile_default_archive_position" id="social_rocket_inline_mobile_default_archive_position_both" value="both" <?php checked( $settings['default_archive_position'] === 'both' ); ?> disabled />
										<?php _e( 'Both above and below (shares the archive)', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_archive_position_item">
										<input type="radio" name="social_rocket_inline_mobile_default_archive_position" id="social_rocket_inline_mobile_default_archive_position_item" value="item" <?php checked( $settings['default_archive_position'] === 'item' ); ?> disabled />
										<?php _e( 'Buttons for each item (shares the item)', 'social-rocket' ); ?>
									</label>
									<label for="social_rocket_inline_mobile_default_archive_position_none">
										<input type="radio" name="social_rocket_inline_mobile_default_archive_position" id="social_rocket_inline_mobile_default_archive_position_none" value="none" <?php checked( $settings['default_archive_position'] === 'none' ); ?> disabled />
										<?php _e( 'None/manual placement', 'social-rocket' ); ?>
									</label>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_button_style"><?php _e( 'Button style', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<select name="social_rocket_inline_mobile_button_style" id="social_rocket_inline_mobile_button_style" disabled>
										<option value="oval" <?php selected( $this->_isset( $settings['button_style'] ), 'oval' ); ?>><?php _e( 'Oval', 'social-rocket' ); ?></option>
										<option value="rectangle" <?php selected( $this->_isset( $settings['button_style'] ), 'rectangle' ); ?>><?php _e( 'Rectangle', 'social-rocket' ); ?></option>
										<option value="round" <?php selected( $this->_isset( $settings['button_style'] ), 'round' ); ?>><?php _e( 'Round', 'social-rocket' ); ?></option>
										<option value="square" <?php selected( $this->_isset( $settings['button_style'] ), 'square' ); ?>><?php _e( 'Square', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_button_alignment"><?php _e( 'Button alignment', 'social-rocket' ); ?></label>
								</th>
								<td>
									<select name="social_rocket_inline_mobile_button_alignment" id="social_rocket_inline_mobile_button_alignment" disabled>
										<option value="left" <?php selected( $this->_isset( $settings['button_alignment'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
										<option value="center" <?php selected( $this->_isset( $settings['button_alignment'] ), 'center' ); ?>><?php _e( 'Center', 'social-rocket' ); ?></option>
										<option value="right" <?php selected( $this->_isset( $settings['button_alignment'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
										<option value="stretch" <?php selected( $this->_isset( $settings['button_alignment'] ), 'stretch' ); ?><?php echo ( in_array( $settings['button_style'], array( 'round', 'square' ) ) ? ' style="display:none;"' : '' ); ?>><?php _e( 'Stretch', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_button_size"><?php _e( 'Button size (%)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_button_size" id="social_rocket_inline_mobile_button_size" value="<?php echo $this->_isset( $settings['button_size'], '' ); ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_inline_button_show_cta_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'oval' ) ) ? '' : ' style="display: none;"'; ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_button_show_cta"><?php _e( 'Show Button Text', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_inline_mobile_button_show_cta" id="social_rocket_inline_mobile_button_show_cta" value="1" <?php checked( $settings['button_show_cta'] ); ?> disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_heading_text"><?php _e( 'Heading text (optional)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="text" name="social_rocket_inline_mobile_heading_text" id="social_rocket_inline_mobile_heading_text" value="<?php echo $settings['heading_text']; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_heading_alignment"><?php _e( 'Heading alignment', 'social-rocket' ); ?></label>
								</th>
								<td>
									<select name="social_rocket_inline_mobile_heading_alignment" id="social_rocket_inline_mobile_heading_alignment" disabled>
										<option value="default" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
										<option value="left" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
										<option value="right" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
										<option value="center" <?php selected( $this->_isset( $settings['heading_alignment'] ), 'center' ); ?>><?php _e( 'Center', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_heading_element"><?php _e( 'Heading element', 'social-rocket' ); ?></label>
								</th>
								<td>
									<select name="social_rocket_inline_mobile_heading_element" id="social_rocket_inline_mobile_heading_element" disabled>
										<option value="h1" <?php selected( $this->_isset( $settings['heading_element'] ), 'h1' ); ?>><?php _e( 'H1', 'social-rocket' ); ?></option>
										<option value="h2" <?php selected( $this->_isset( $settings['heading_element'] ), 'h2' ); ?>><?php _e( 'H2', 'social-rocket' ); ?></option>
										<option value="h3" <?php selected( $this->_isset( $settings['heading_element'] ), 'h3' ); ?>><?php _e( 'H3', 'social-rocket' ); ?></option>
										<option value="h4" <?php selected( $this->_isset( $settings['heading_element'] ), 'h4' ); ?>><?php _e( 'H4 (Default)', 'social-rocket' ); ?></option>
										<option value="h5" <?php selected( $this->_isset( $settings['heading_element'] ), 'h5' ); ?>><?php _e( 'H5', 'social-rocket' ); ?></option>
										<option value="h6" <?php selected( $this->_isset( $settings['heading_element'] ), 'h6' ); ?>><?php _e( 'H6', 'social-rocket' ); ?></option>
										<option value="p" <?php selected( $this->_isset( $settings['heading_element'] ), 'p' ); ?>><?php _e( 'P', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_border"><?php _e( 'Border', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<select name="social_rocket_inline_mobile_border" id="social_rocket_inline_mobile_border" disabled>
										<option value="none" <?php selected( $this->_isset( $settings['border'] ), 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
										<option value="solid" <?php selected( $this->_isset( $settings['border'] ), 'solid' ); ?>><?php _e( 'Solid', 'social-rocket' ); ?></option>
										<option value="double" <?php selected( $this->_isset( $settings['border'] ), 'double' ); ?>><?php _e( 'Double', 'social-rocket' ); ?></option>
										<option value="dashed" <?php selected( $this->_isset( $settings['border'] ), 'dashed' ); ?>><?php _e( 'Dashed', 'social-rocket' ); ?></option>
										<option value="dotted" <?php selected( $this->_isset( $settings['border'] ), 'dotted' ); ?>><?php _e( 'Dotted', 'social-rocket' ); ?></option>
										<option value="inset" <?php selected( $this->_isset( $settings['border'] ), 'inset' ); ?>><?php _e( 'Inset', 'social-rocket' ); ?></option>
										<option value="outset" <?php selected( $this->_isset( $settings['border'] ), 'outset' ); ?>><?php _e( 'Outset', 'social-rocket' ); ?></option>
										<option value="groove" <?php selected( $this->_isset( $settings['border'] ), 'groove' ); ?>><?php _e( 'Groove', 'social-rocket' ); ?></option>
										<option value="ridge" <?php selected( $this->_isset( $settings['border'] ), 'ridge' ); ?>><?php _e( 'Ridge', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_border_size"><?php _e( 'Border size (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_border_size" id="social_rocket_inline_mobile_border_size" value="<?php echo $settings['border_size']; ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_inline_border_radius_wrapper"<?php echo in_array( $this->_isset( $settings['button_style'] ), array( 'rectangle', 'square' ) ) ? '' : ' style="display: none;"'; ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_border_radius"><?php _e( 'Border radius (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_border_radius" id="social_rocket_inline_mobile_border_radius" value="<?php echo $settings['border_radius']; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_margin_right"><?php _e( 'Horizontal margin (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_margin_right" id="social_rocket_inline_mobile_margin_right" value="<?php echo $settings['margin_right']; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_margin_bottom"><?php _e( 'Vertical margin (px)', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_margin_bottom" id="social_rocket_inline_mobile_margin_bottom" value="<?php echo $settings['margin_bottom']; ?>" disabled />
								</td>
							</tr>
							<?php echo apply_filters( 'social_rocket_settings_inline_mobile_more_enable_html', '
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_more_enable">' . __( '(PRO) Enable "More" button', 'social-rocket' ) . '</label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_inline_mobile_more_enable" id="social_rocket_inline_mobile_more_enable" value="1" disabled />
									<p class="description">' . sprintf( __( '%s adds the "More" button feature, which allows you to combine extra networks into one button.', 'social-rocket' ), '<a href="https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_inline_mobile_more_enable&utm_campaign=Free" target="_blank">Social Rocket Pro</a>' ) . '</p>
								</td>
							</tr>
							' ); ?>
						</table>
					</div>
				</div>
				<p>&nbsp;</p>
				<h3><?php _e( 'Share Count Settings', 'social-rocket' ); ?></h3>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_show_counts"><?php _e( 'Show share count', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_inline_mobile_show_counts" id="social_rocket_inline_mobile_show_counts" value="1" <?php checked( $settings['show_counts'] ); ?> disabled />
								</td>
							</tr>
							<tr class="social_rocket_inline_show_counts_min"<?php echo ( $settings['show_counts'] ? '' : ' style="display:none;"' ) ; ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_show_counts_min"><?php _e( 'Minimum shares', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_show_counts_min" id="social_rocket_inline_mobile_show_counts_min" value="<?php echo $settings['show_counts_min'] ; ?>" disabled />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_rounding"><?php _e( 'Round large share counts', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_inline_mobile_rounding" id="social_rocket_inline_mobile_rounding" value="1" <?php checked( $settings['rounding'] ); ?> disabled />
								</td>
							</tr>
						</table>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="social_rocket_inline_mobile_show_total"><?php _e( 'Show total share count', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_inline_mobile_show_total" id="social_rocket_inline_mobile_show_total" value="1" <?php checked( $settings['show_total'] ); ?> disabled />
								</td>
							</tr>
							<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_show_total_min"><?php _e( 'Minimum total', 'social-rocket' ); ?></label>
									<a><span class="dashicons dashicons-editor-help"></span></a>
								</th>
								<td>
									<input type="number" name="social_rocket_inline_mobile_show_total_min" id="social_rocket_inline_mobile_show_total_min" value="<?php echo $settings['show_total_min'] ; ?>" disabled />
								</td>
							</tr>
							<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ) ; ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_total_position"><?php _e( 'Total share count position', 'social-rocket' ); ?></label>
								</th>
								<td>
									<select name="social_rocket_inline_mobile_total_position" id="social_rocket_inline_mobile_total_position" disabled>
										<option value="before" <?php selected( $this->_isset( $settings['total_position'] ), 'before' ); ?>><?php _e( 'Before Buttons', 'social-rocket' ); ?></option>
										<option value="after" <?php selected( $this->_isset( $settings['total_position'] ), 'after' ); ?>><?php _e( 'After Buttons', 'social-rocket' ); ?></option>
									</select>
								</td>
							</tr>
							<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_total_color"><?php _e( 'Total share text/icon color', 'social-rocket' ); ?></label>
								</th>
								<td>
									<div>
										<div class="wp-picker-container">
											<button type="button" class="button wp-color-result" id="social_rocket_inline_mobile_total_color" aria-expanded="false" style="<?php echo esc_attr( isset( $settings['total_color'] ) ? 'background-color: ' . $settings['total_color'] : '' ); ?>"><span class="wp-color-result-text"><?php _e( 'Select Color' ); ?></span></button>
										</div>
									</div>
								</td>
							</tr>
							<tr class="social_rocket_inline_show_total"<?php echo ( $settings['show_total'] ? '' : ' style="display:none;"' ); ?>>
								<th scope="row">
									<label for="social_rocket_inline_mobile_total_show_icon"><?php printf( __( 'Show total share icon (%s)', 'social-rocket' ), '<i class="fas fa-share-alt"></i>' ); ?></label>
								</th>
								<td>
									<input type="checkbox" name="social_rocket_inline_mobile_total_show_icon" id="social_rocket_inline_mobile_total_show_icon" value="1" <?php checked( $this->_isset( $settings['total_show_icon'] ) ); ?> disabled />
								</td>
							</tr>
						</table>
					</div>
				</div>
				<p>&nbsp;</p>
				<h3><?php _e( 'Advanced Placement Settings', 'social-rocket' ); ?></h3>
				<p class="description"><?php _e( 'The defaults are set above under Display Settings, but you can (optionally) refine it here if you want something other than the default for individual pages, posts, CPTs, or archives.', 'social-rocket' ); ?></p>
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<h4>
							<?php _e( 'Individual Pages, Posts, CPTs', 'social-rocket' ); ?>
							<a><span class="dashicons dashicons-editor-help"></span></a>
						</h4>
						<div id="social-rocket-inline-mobile-position-posts">
							<table class="form-table">
								<?php foreach ( $post_types as $post_type ): ?>
								<tr>
									<th scope="row">
										<label for="social_rocket_inline_mobile_position_post_type_<?php echo esc_attr( $post_type ); ?>"><?php echo ucfirst( $post_type ); ?></label>
									</th>
									<td>
										<select name="social_rocket_inline_mobile_position_post_type_<?php echo esc_attr( $post_type ); ?>" id="social_rocket_inline_mobile_position_post_type_<?php echo esc_attr( $post_type ); ?>" disabled>
											<option value="default" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
											<option value="above" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'above' ); ?>><?php _e( 'Above the content', 'social-rocket' ); ?></option>
											<option value="below" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'below' ); ?>><?php _e( 'Below the content', 'social-rocket' ); ?></option>
											<option value="both" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'both' ); ?>><?php _e( 'Both above and below the content', 'social-rocket' ); ?></option>
											<option value="none" <?php selected( $this->_isset( $settings['position_post_type_'.$post_type] ), 'none' ); ?>><?php _e( 'None/manual placement', 'social-rocket' ); ?></option>
										</select>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
						<h4>
							<?php _e( 'Archives Pages', 'social-rocket' ); ?>
							<a><span class="dashicons dashicons-editor-help"></span></a>
						</h4>
						<div id="social-rocket-inline-mobile-position-archives">
							<table class="form-table">
								<?php foreach ( $archives as $key => $archive ): ?>
								<tr>
									<th scope="row">
										<label for="social_rocket_inline_mobile_position_archive_<?php echo esc_attr( $key ); ?>"><?php echo $archive['display_name']; ?></label>
									</th>
									<td>
										<select name="social_rocket_inline_mobile_position_archive_<?php echo esc_attr( $key ); ?>" id="social_rocket_inline_mobile_position_archive_<?php echo esc_attr( $key ); ?>" disabled>
											<option value="default" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'default' ); ?>><?php _e( 'Default', 'social-rocket' ); ?></option>
											<option value="above" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'above' ); ?>><?php _e( 'Above the content (shares the archive)', 'social-rocket' ); ?></option>
											<option value="below" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'below' ); ?>><?php _e( 'Below the content (shares the archive)', 'social-rocket' ); ?></option>
											<option value="both" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'both' ); ?>><?php _e( 'Both above and below (shares the archive)', 'social-rocket' ); ?></option>
											<option value="item" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'item' ); ?>><?php _e( 'Buttons for each item (shares the item)', 'social-rocket' ); ?></option>
											<option value="none" <?php selected( $this->_isset( $settings['position_archive_'.$key] ), 'none' ); ?>><?php _e( 'None/manual placement', 'social-rocket' ); ?></option>
										</select>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</div>
				</div>
				<p>&nbsp;</p>
			</div>
			<?php do_action( 'social_rocket_settings_inline_buttons_mobile_after' ); ?>
		</div>
		
		<div class="social-rocket-settings-preview-wrapper">
			<div id="social_rocket_inline_preview">
				<h3>
					<?php _e( 'Preview', 'social-rocket' ); ?>&nbsp;&nbsp;<a class="social-rocket-collapsable-toggle" data-sr-toggle="social_rocket_inline_preview_inner"><i class="fas fa-plus-square"></i> <span><?php _e( 'Show', 'social-rocket' ); ?></span></a>
					<?php /*
					<a class="add-new-h2 social-rocket-mini-button" id="social_rocket_inline_preview_mobile_toggle" href="#"><?php _e( 'Mobile', 'social-rocket' ); ?></a>
					<a class="add-new-h2 social-rocket-mini-button" id="social_rocket_inline_preview_desktop_toggle" href="#"><?php _e( 'Desktop', 'social-rocket' ); ?></a>
					*/ ?>
				</h3>
				<div id="social_rocket_inline_preview_inner" style="display: none;">
					<div id="social_rocket_inline_preview_desktop" class="social-rocket-inline-preview">
						<?php
						social_rocket( array(
							'networks'    => array_keys( $SR->networks ),
							'heading'     => ' ',
							'show_counts' => 'true',
							'show_total'  => 'after',
						) );
						?>
					</div>
					<div id="social_rocket_inline_preview_mobile" class="social-rocket-inline-preview" style="display: none;">
						<?php
						social_rocket( array(
							'networks'    => array_keys( $SR->networks ),
							'add_class'   => 'social-rocket-mobile-only',
							'heading'     => ' ',
							'show_counts' => 'true',
							'show_total'  => 'after',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		
		do_action( 'social_rocket_settings_inline_buttons_after' );
		
		$this->admin_settings_network_popups( 'inline', $settings );
		
		$this->admin_settings_footer();
		
		#endregion admin_settings_page_inline_buttons
	}
	
	
	/**
	 * Outputs License Keys settings page.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_page_license_keys() {
		#region admin_settings_page_license_keys
	
		$this->admin_settings_post_actions();
		
		$this->admin_settings_header();
		
		$SR = Social_Rocket::get_instance();
		
		$all_good = apply_filters( 'social_rocket_settings_licenses_status', true );
		?>
		<h2><?php _e( 'License Keys', 'social-rocket' ); ?></h2>
		
		<h2 class="nav-tab-wrapper social-rocket-tabs">
			<?php
			$tabs = apply_filters( 'social_rocket_settings_license_keys_tabs', array() );
			$i = 0;
			foreach ( $tabs as $tab => $label ) {
				echo '<a href="#' . $tab . '" class="nav-tab social-rocket-tab ' . ( $i === 0 ? 'nav-tab-active' : '' ) . '" data-tab="' . $tab . '">' . $label . '</a>';
				$i++;
			}
			?>
		</h2>
		
		<div id="social-rocket-settings-license-keys" class="social-rocket-settings-section">
		
			<?php do_action( 'social_rocket_settings_section_license_keys_before' ); ?>
		
			<p><?php printf( __( 'This page is where you enter license keys for any pro products you have purchased from <a target="_blank" href="%s">Social Rocket</a>.', 'social-rocket' ), 'https://wpsocialrocket.com/?utm_source=Plugin&utm_content=license_keys&utm_campaign=Free' ); ?></p>
			
			<h4><?php _e( 'Instructions', 'social-rocket' ); ?></h4>
			<p>
				<?php printf( __( 'Before you can enter your license key, you must install and activate the product first.  You can do this from your <a href="%s" target="_blank">Plugins</a> page.', 'social-rocket' ), admin_url( 'plugins.php' ) ); ?><br />
				<?php printf( __( 'For step-by-step instructions, please read our FAQ page <a href="%s" target="_blank">How do I install and activate Social Rocket pro products?</a>', 'social-rocket' ), 'https://docs.wpsocialrocket.com/article/19-activating-and-deactivating-license-keys?utm_source=Plugin&utm_content=license_keys&utm_campaign=Free' ); ?><br /><br />
				<?php _e( 'Once your products are installed and activated, you can activate your license keys by doing the following:', 'social-rocket' ); ?><br /><br />
				<?php _e( '1. Copy the license key for your product and paste it into the corresponding field below.', 'social-rocket' ); ?><br />
				<?php _e( '2. Click the <strong>Activate License</strong> button.', 'social-rocket' ); ?><br />
				<?php _e( '3. That\'s it!  Be sure to watch for any new updates.', 'social-rocket' ); ?><br /><br />
			</p>
			
			<h4><?php _e( 'Where can I find my license keys?', 'social-rocket' ); ?></h4>
			<p>
				<?php _e( 'You should have received a Purchase Receipt email that contains the license key for each product you have purchased from Social Rocket', 'social-rocket' ); ?><br />
				<?php printf( __( 'If you have lost the email, you can login to your account at Social Rocket <a target="_blank" href="%s">here</a> to get your license key(s).', 'social-rocket' ), 'https://wpsocialrocket.com/?utm_source=Plugin&utm_content=license_keys&utm_campaign=Free' ); ?><br /><br />
			</p>
			
			<hr />
			<p>&nbsp;</p>
			
			<?php do_action( 'social_rocket_settings_licenses' ); ?>
			
			<?php if ( ! class_exists( 'Social_Rocket_Plugin_Updater' ) ): ?>
			<p class="description"><?php _e( 'No products found.', 'social-rocket' ); ?></p>
			<?php endif; ?>
			
			<?php do_action( 'social_rocket_settings_section_license_keys_after' ); ?>
			
		</div>
		<?php
		
		$this->admin_settings_footer();
		
		#endregion admin_settings_page_license_keys
	}
	
	
	/**
	 * Outputs Settings settings page.
	 *
	 * @since 1.0.0
	 */
	public function admin_settings_page_settings() {
		#region admin_settings_page_settings
		
		$this->admin_settings_post_actions();
		
		$this->admin_settings_header();
		
		$SR = Social_Rocket::get_instance();
		
		?>
		<h2><?php _e( 'Settings', 'social-rocket' ); ?></h2>
		
		<h2 class="nav-tab-wrapper social-rocket-tabs">
			<?php
			$tabs = apply_filters( 'social_rocket_settings_tabs', array(
				'advanced'         => __( 'Advanced', 'social-rocket' ),
				'social-extras'    => __( 'Social Extras', 'social-rocket' ),
				'tools'            => __( 'Tools', 'social-rocket' ),
			) );
			$i = 0;
			foreach ( $tabs as $tab => $label ) {
				echo '<a href="#' . $tab . '" class="nav-tab social-rocket-tab ' . ( $i === 0 ? 'nav-tab-active' : '' ) . '" data-tab="' . $tab . '">' . $label . '</a>';
				$i++;
			}
			?>
		</h2>
		
		<div id="social-rocket-settings-advanced" class="social-rocket-settings-section">
			<?php do_action( 'social_rocket_settings_section_advanced_before' ); ?>
			<h3><?php _e( 'Advanced Settings', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_custom_css"><?php _e( 'Custom CSS', 'social-rocket' ); ?></label>
							</th>
							<td>
								<textarea name="social_rocket_custom_css" id="social_rocket_custom_css" rows="5" style="width:100%;"><?php echo stripslashes( $this->_isset( $SR->settings['custom_css'], '' ) ); ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_decimal_places"><?php _e( 'Decimal places', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'How many decimal places to show in share counts.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="number" name="social_rocket_decimal_places" id="social_rocket_decimal_places" value="<?php echo $SR->settings['decimal_places']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_decimal_separator"><?php _e( 'Decimal separator', 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="text" name="social_rocket_decimal_separator" id="social_rocket_decimal_separator" value="<?php echo $SR->settings['decimal_separator']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_disable_fontawesome"><?php _e( "Don't load FontAwesome", 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If checked, prevents Social Rocket from loading our bundled version of FontAwesome icons. Use this if you are already loading FontAwesome some other way.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_disable_fontawesome" value="0" />
								<input type="checkbox" name="social_rocket_disable_fontawesome" id="social_rocket_disable_fontawesome" value="1" <?php checked( $SR->settings['disable_fontawesome'] ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_disable_og_tags"><?php _e( "Don't output OG tags", 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If checked, prevents Social Rocket from adding Open Graph meta tags to your site\'s code. Use this if you already have a different plugin handling your OG tags.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_disable_og_tags" value="0" />
								<input type="checkbox" name="social_rocket_disable_og_tags" id="social_rocket_disable_og_tags" value="1" <?php checked( $SR->settings['disable_og_tags'] ); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_disable_twitter_cards"><?php _e( "Don't output Twitter cards", 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If checked, prevents Social Rocket from adding Twitter Card meta tags to your site\'s code. Use this if you already have a different plugin handling your Twitter Cards.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_disable_twitter_cards" value="0" />
								<input type="checkbox" name="social_rocket_disable_twitter_cards" id="social_rocket_disable_twitter_cards" value="1" <?php checked( $SR->settings['disable_twitter_cards'] ); ?> />
							</td>
						</tr>
					</table>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2 sr-grid__col--l-1-of-2">
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="social_rocket_auto_backup"><?php _e( "Automatically Backup Data Before Plugin Update", 'social-rocket' ); ?></label>
							</th>
							<td>
								<input type="hidden" name="social_rocket_auto_backup" value="0" />
								<input type="checkbox" name="social_rocket_auto_backup" id="social_rocket_auto_backup" value="1" <?php checked( $SR->settings['auto_backup'] ); ?> />
								<p class="description"><?php _e( 'Backup file will be saved in your WP uploads folder, i.e. /wp-content/uploads/social-rocket-backups/', 'social-rocket' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_refresh_interval"><?php _e( 'Refresh interval', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'How often, in seconds, to check for updated counts. Recommended value: 3600 (i.e. 1 hour).', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="text" name="social_rocket_refresh_interval" id="social_rocket_refresh_interval" value="<?php echo $SR->settings['refresh_interval']; ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_master_throttle"><?php _e( 'Master API throttle', 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Minimum delay, in seconds, between background API requests. Recommended value: 1 (i.e. 1 second).  If your site has limited CPU resources or you are hitting an API rate limit, you can increase this value to slow down background processing.  However things like Share Counts make take longer to update.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="text" name="social_rocket_master_throttle" id="social_rocket_master_throttle" value="<?php echo apply_filters( 'social_rocket_master_throttle', $this->_isset( $SR->settings['master_throttle'], 1 ) ); ?>" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="social_rocket_auto_fix_gutenberg"><?php _e( "Automatically fix Gutenberg blocks", 'social-rocket' ); ?></label>
								<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
								<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If checked, will automatically fix issues detected with Gutenberg blocks.', 'social-rocket' ); ?></div>
							</th>
							<td>
								<input type="hidden" name="social_rocket_auto_fix_gutenberg" value="0" />
								<input type="checkbox" name="social_rocket_auto_fix_gutenberg" id="social_rocket_auto_fix_gutenberg" value="1" <?php checked( $this->_isset( $SR->settings['auto_fix_gutenberg'] ) ); ?> />
							</td>
						</tr>
					</table>
				</div>
			</div>
			<p>&nbsp;</p>
			<div class="postbox social-rocket-settings-postbox social-rocket-settings-danger">
				<p><strong><?php _e( 'Danger Zone', 'social-rocket' ); ?></strong></p>
				<table class="form-table">
					<tr>
						<th scope="row">
							<label for="social_rocket_reset_settings"><?php _e( 'Reset all Social Rocket settings to factory defaults', 'social-rocket' ); ?></label>
						</th>
						<td>
							<p><input type="submit" id="social_rocket_reset_settings" name="social_rocket_reset_settings" class="social-rocket-excluded-input button-secondary" value="Reset" /></p>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="social_rocket_delete_settings"><?php _e( 'Delete all Social Rocket data and settings when plugin is deactivated', 'social-rocket' ); ?></label>
						</th>
						<td>
							<input type="hidden" name="social_rocket_delete_settings" value="0" />
							<input type="checkbox" name="social_rocket_delete_settings" id="social_rocket_delete_settings"  value="1" <?php checked( $SR->settings['delete_settings'] ); ?> />
							<p class="description"><?php _e( 'Warning: this cannot be undone. If this is the Primary Site in a WP Multisite environment, and you network-deactivate the plugin with this setting checked, all Social Rocket data from all sites in the network will be deleted.', 'social-rocket' ); ?></p>
						</td>
					</tr>
				</table>
			</div>
			<p>&nbsp;</p>
			<?php do_action( 'social_rocket_settings_section_advanced_after' ); ?>
		</div>
		
		<div id="social-rocket-settings-social-extras" class="social-rocket-settings-section" style="display: none;">
			<?php do_action( 'social_rocket_settings_section_social_extras_before' ); ?>
			<h3><?php _e( 'Facebook Settings', 'social-rocket' ); ?></h3>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="social_rocket_facebook_access_token"><?php _e( 'Access Token', 'social-rocket' ); ?></label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'To get the most accurate counts, a Facebook API v3.0 access token is needed.', 'social-rocket' ); ?></div>
					</th>
					<td>
						<input type="text" name="social_rocket_facebook_access_token" id="social_rocket_facebook_access_token" value="<?php echo $this->_isset( $SR->settings['facebook']['access_token'] ); ?>" />
						<p class="description"><?php printf( __( 'In order to get accurate share counts, Facebook requires an API access token.  Enter your access token here.  For help getting your access token, <a href="%s" target="_blank">see our website for more info</a>.', 'social-rocket' ), 'https://docs.wpsocialrocket.com/article/66-facebook-access-token' ); ?></p>
					</td>
				</tr>
			</table>
			<h3><?php _e( 'Pinterest Settings', 'social-rocket' ); ?></h3>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="social_rocket_social_identity_pinterest"><?php _e( 'Pinterest Username', 'social-rocket' ); ?></label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'Entering a username will tag your user in Pinterest shares.', 'social-rocket' ); ?></div>
					</th>
					<td>
						<input type="text" name="social_rocket_social_identity_pinterest" id="social_rocket_social_identity_pinterest" value="<?php echo $this->_isset( $SR->settings['social_identity']['pinterest'] ); ?>" placeholder="<?php _e( '@username', 'social-rocket' ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="social_rocket_pinterest_image_fallback"><?php _e( 'Pinterest Image Fallback', 'social-rocket' ); ?></label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php _e( 'If no Pinterest Image is set in the post settings, this setting allows you to choose which image will be shared instead.', 'social-rocket' ); ?></div>
					</th>
					<td>
						<select name="social_rocket_pinterest_image_fallback" id="social_rocket_pinterest_image_fallback">
							<option value="featured" <?php selected( $this->_isset( $SR->settings['pinterest']['image_fallback'] ), 'featured' ); ?>><?php _e( 'Use Featured Image, if available', 'social-rocket' ); ?></option>
							<option value="chooser" <?php selected( $this->_isset( $SR->settings['pinterest']['image_fallback'] ), 'chooser' ); ?>><?php _e( 'Ask user to choose from images on the page', 'social-rocket' ); ?></option>
						</select>
					</td>
				</tr>
				<?php echo apply_filters( 'social_rocket_settings_section_social_extras_pinterest_html', '
				<tr>
					<th scope="row">
						<label>' . __( '(PRO) Enable Pin Image for Browser Extensions', 'social-rocket' ) .'</label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;">' . __( 'Loads your Pinterest Image specially for use in Pinterest Browser Extensions.', 'social-rocket' ) . '</div>
					</th>
					<td>
						<input type="checkbox" disabled />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="social_rocket_pinterest_pin_description_enable">' . __( '(PRO) Enable Pin Description for all images', 'social-rocket' ) . '</label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;">' . __( 'Adds a <code>data-pin-description</code> attribute to all images on the page which don\'t already have one.', 'social-rocket' ) . '</div>
					</th>
					<td>
						<input type="checkbox" disabled />
					</td>
				</tr>
				' ); ?>
			</table>
			<?php do_action( 'social_rocket_settings_section_social_extras_pinterest' ); ?>
			<p>&nbsp;</p>
			<h3><?php _e( 'Twitter Settings', 'social-rocket' ); ?></h3>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="social_rocket_social_identity_twitter"><?php _e( 'Twitter Username', 'social-rocket' ); ?></label>
						<a class="social-rocket-tooltip-toggle"><span class="dashicons dashicons-editor-help"></span></a>
						<div class="social-rocket-tooltip" style="display:none;"><?php printf( __( 'Entering a username will tag your user in tweets. This can be overridden for specific users by editing their <a href="%s">profile</a>.', 'social-rocket' ), admin_url( 'users.php' ) ); ?></div>
					</th>
					<td>
						<input type="text" name="social_rocket_social_identity_twitter" id="social_rocket_social_identity_twitter" value="<?php echo $this->_isset( $SR->settings['social_identity']['twitter'] ); ?>" placeholder="<?php _e( '@username', 'social-rocket' ); ?>" />
					</td>
				</tr>
				<?php echo apply_filters( 'social_rocket_settings_section_social_extras_twitter_html', '
				<tr>
					<th scope="row">
						<label>' . __( '(PRO) Enable Twitter counts', 'social-rocket' ) . '</label>
					</th>
					<td>
						<input type="checkbox" disabled />
						<p class="description">' . __( sprintf( 'Twitter <a href="%s" target="_blank">disabled share counts</a> on November 20, 2015. However <a href="%s" target="_blank">Social Rocket Pro</a> can connect with third-party providers to make your share counts work again.', 'https://blog.twitter.com/2015/hard-decisions-for-a-sustainable-platform', 'https://wpsocialrocket.com/?utm_source=Plugin&utm_content=settings_social_extras_twitter_counts&utm_campaign=Free' ), 'social-rocket' ) . '</p>
					</td>
				</tr>
				' ); ?>
			</table>
			<?php do_action( 'social_rocket_settings_section_social_extras_twitter' ); ?>
			<p>&nbsp;</p>
			<?php do_action( 'social_rocket_settings_section_social_extras_after' ); ?>
		</div>
		
		<div id="social-rocket-settings-tools" class="social-rocket-settings-section" style="display: none;">
			<?php do_action( 'social_rocket_settings_section_tools_before' ); ?>
			<h3><?php _e( 'Tools', 'social-rocket' ); ?></h3>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<div id="social-rocket-settings-export-settings" class="postbox social-rocket-settings-postbox">
						<p><strong><?php _e( 'Export Settings', 'social-rocket' ); ?></strong></p>
						<p><?php _e( 'Export your Social Rocket settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'social-rocket' ); ?></p>
						<p><input type="submit" name="social_rocket_settings_export" class="social-rocket-excluded-input button-primary" value="Export" /></p>
					</div>
					<div id="social-rocket-settings-import-settings" class="postbox social-rocket-settings-postbox">
						<p><strong><?php _e( 'Import Settings', 'social-rocket' ); ?></strong></p>
						<p><?php _e( 'Import Social Rocket settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'social-rocket' ); ?></p>
						<input type="file" name="social_rocket_settings_import_file" id="social_rocket_settings_import_file" class="social-rocket-excluded-input" />
						<p><input type="submit" name="social_rocket_settings_import" class="social-rocket-excluded-input button-secondary" value="Import" /></p>
					</div>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<div id="social-rocket-settings-backup" class="postbox social-rocket-settings-postbox">
						<p><strong><?php _e( 'Backup All Data', 'social-rocket' ); ?></strong></p>
						<p><?php _e( 'Backup all your Social Rocket data and settings for this site to a .json file. Includes post/page-specific data. (Do not use to restore to a different site.)', 'social-rocket' ); ?></p>
						<p><input type="submit" name="social_rocket_settings_backup" class="social-rocket-excluded-input button-primary" value="Backup" /></p>
					</div>
					<div id="social-rocket-settings-restore" class="postbox social-rocket-settings-postbox">
						<p><strong><?php _e( 'Restore All Data', 'social-rocket' ); ?></strong></p>
						<p><?php _e( 'Restore a backup .json file for this site. Includes post/page-specific data. (Do not use a backup file from a different site.)', 'social-rocket' ); ?></p>
						<input type="file" name="social_rocket_settings_restore_file" id="social_rocket_settings_restore_file" class="social-rocket-excluded-input" />
						<p><input type="submit" name="social_rocket_settings_restore" class="social-rocket-excluded-input button-secondary" value="Restore" /></p>
					</div>
				</div>
			</div>
			<div class="sr-grid">
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<div id="social-rocket-settings-recalc" class="postbox social-rocket-settings-postbox">
						<p><strong><?php _e( 'Recalculate All Count Data', 'social-rocket' ); ?></strong></p>
						<p><?php _e( 'This will reset all your share count data and begin recalculating from scratch.', 'social-rocket' ); ?></p>
						<p><button type="button" id="social-rocket-settings-recalc-all" class="button-primary"><?php _e( 'Recalculate all counts', 'social-rocket' ); ?></button></p>
						<?php wp_nonce_field( 'social_rocket_recalc_all', 'social-rocket-settings-recalc-nonce' ); ?>
					</div>
				</div>
				<div class="sr-grid__col sr-grid__col--1-of-2">
					<div id="social-rocket-settings-reset-queue" class="postbox social-rocket-settings-postbox">
						<p><strong><?php _e( 'Clear Background Processing Queue', 'social-rocket' ); ?></strong></p>
						<p><?php _e( 'This will reset your background processing queue. You won\'t lose any data.', 'social-rocket' ); ?></p>
						<p><input type="submit" name="social_rocket_settings_reset_queue" class="social-rocket-excluded-input button-primary" value="Clear background queue" /></p>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<?php do_action( 'social_rocket_settings_section_tools_after' ); ?>
		</div>
		
		<?php do_action( 'social_rocket_settings_sections' ); ?>
		
		<?php
		$this->admin_settings_footer();
		#endregion admin_settings_page_settings
	}
	
	
	public function admin_settings_post_actions() {
		
		global $wpdb;
		
		if (
			! isset( $_POST['_wpnonce'] ) ||
			! wp_verify_nonce( $_POST['_wpnonce'], 'social_rocket_settings' )
		) {
			return;
		}
		
		$SR = Social_Rocket::get_instance();
		$post_types = $SR->get_post_types();
		$archives = $SR->get_archive_types();
		
		// if we just POSTed, do we need to do something?
		if ( isset( $_POST['social_rocket_settings_import'] ) ) {
		
			// import settings
			if( current_user_can( 'manage_options' ) ) {
			
				$status = 'error';
				$message = '';
			
				if(
					isset( $_FILES['social_rocket_settings_import_file']['name'] ) &&
					substr( strtolower( $_FILES['social_rocket_settings_import_file']['name'] ), -5 ) === '.json'
				) {
					
					$import_file = $_FILES['social_rocket_settings_import_file']['tmp_name'];
					$settings = json_decode( file_get_contents( $import_file ), true );
					
					if ( $settings && isset( $settings['social-rocket'] ) ) {
						
						$SR->settings = $settings['social-rocket'];
						update_option( 'social_rocket_settings', $SR->settings );
						
						do_action( 'social_rocket_settings_import', $settings );
						
						$status = 'success';
						$message = __( 'Settings successfully imported', 'social-rocket' );
						
					} else {
					
						$message = __( 'Error: unable to parse .json file', 'social-rocket' );
						
					}
					
				} else {
				
					$message = __( 'Error: please upload a valid .json file', 'social-rocket' );
					
				}
				
				echo '<div class="notice notice-' . $status . '"><p>' . $message . '</p></div>';
				
			}
		
		} elseif ( isset( $_POST['social_rocket_settings_restore'] ) ) {
		
			// restore settings & data
			if( current_user_can( 'manage_options' ) ) {
			
				set_time_limit(0);
				$status = 'error';
				$message = '';
			
				if(
					isset( $_FILES['social_rocket_settings_restore_file']['name'] ) &&
					substr( strtolower( $_FILES['social_rocket_settings_restore_file']['name'] ), -5 ) === '.json'
				) {
					
					$import_file = $_FILES['social_rocket_settings_restore_file']['tmp_name'];
					$settings = json_decode( file_get_contents( $import_file ), true );
					
					$message = __( 'Error: unable to parse .json file', 'social-rocket' ); // will be overwritten if success
					
					if ( $settings ) {
						
						if ( isset( $settings['social-rocket'] ) ) {
							$SR->settings = $settings['social-rocket'];
							update_option( 'social_rocket_settings', $SR->settings );
						}
						
						/*
						// TODO: we don't need to restore the count data table like this, we could just trigger a refresh instead
						if ( isset( $settings['social-rocket-count-data'] ) ) {
							$rows_count = count( $settings['social-rocket-count-data'] );
							$table_name = $wpdb->prefix . 'social_rocket_count_data';
							$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
							social_rocket_activate_db_delta(); // re-creates the table
							$sql = "INSERT INTO $table_name ( post_id, term_id, user_id, url, data, last_updated ) VALUES ";
							$i = 0;
							foreach ( $settings['social-rocket-count-data'] as $row ) {
								$i++;
								$sql .= "(";
								$sql .= ( is_null( $row['post_id'] ) ? 'NULL' : $row['post_id'] ) . ",";
								$sql .= ( is_null( $row['term_id'] ) ? 'NULL' : $row['term_id'] ) . ",";
								$sql .= ( is_null( $row['user_id'] ) ? 'NULL' : $row['user_id'] ) . ",";
								$sql .= ( is_null( $row['url'] ) ? 'NULL' : "'" . addslashes( $row['url'] ) . "'" ) . ",";
								$sql .= ( is_null( $row['data'] ) ? 'NULL' : "'" . addslashes( $row['data'] ) . "'" ) . ",";
								$sql .= ( is_null( $row['last_updated'] ) ? 'NULL' : "'" . $row['last_updated'] . "'" );
								$sql .= ")";
								$sql .= $i === $rows_count ? ";" : ",";
							}
						}
						*/
						
						if ( isset( $settings['social-rocket-postmetas'] ) ) {
							foreach ( $settings['social-rocket-postmetas'] as $postmeta ) {
								update_post_meta( $postmeta['post_id'], $postmeta['meta_key'], is_serialized( $postmeta['meta_value'] ) ? unserialize( $postmeta['meta_value'] ) : $postmeta['meta_value'] );
							}
						}
						
						if ( isset( $settings['social-rocket-termmetas'] ) ) {
							foreach ( $settings['social-rocket-termmetas'] as $termmeta ) {
								update_term_meta( $termmeta['term_id'], $termmeta['meta_key'], is_serialized( $termmeta['meta_value'] ) ? unserialize( $termmeta['meta_value'] ) : $termmeta['meta_value'] );
							}
						}
						
						if ( isset( $settings['social-rocket-usermetas'] ) ) {
							foreach ( $settings['social-rocket-usermetas'] as $usermeta ) {
								update_user_meta( $postmeta['user_id'], $postmeta['meta_key'], is_serialized( $postmeta['meta_value'] ) ? unserialize( $postmeta['meta_value'] ) : $postmeta['meta_value'] );
							}
						}
						
						do_action( 'social_rocket_restore', $settings );
						
						$status = 'success';
						$message = __( 'Restore successfully completed', 'social-rocket' );
					}
					
				} else {
				
					$message = __( 'Error: please upload a valid .json file', 'social-rocket' );
					
				}
				
				echo '<div class="notice notice-' . $status . '"><p>' . $message . '</p></div>';
				
			}
		
		} elseif ( isset( $_POST['social_rocket_reset_settings'] ) ) {
		
			// reset settings
			if( current_user_can( 'manage_options' ) ) {
			
				$status = 'error';
				$message = '';
			
				delete_option( 'social_rocket_settings' );
				social_rocket_activate( false );
				$SR->settings = get_option( 'social_rocket_settings' );
				
				do_action( 'social_rocket_settings_reset' );
				
				$status = 'success';
				$message = __( 'Settings reset successfully', 'social-rocket' );
				
				echo '<div class="notice notice-' . $status . '"><p>' . $message . '</p></div>';
				
			}
		
		} elseif ( isset( $_POST['social_rocket_settings_reset_queue'] ) ) {
			
			// clear background queue
			if( current_user_can( 'manage_options' ) ) {
			
				$table_name = $wpdb->prefix . 'social_rocket_count_queue';
				$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
				social_rocket_activate_db_delta(); // re-creates the table
				
				$status = 'success';
				$message = __( 'Background queue cleared successfully', 'social-rocket' );
				
				echo '<div class="notice notice-' . $status . '"><p>' . $message . '</p></div>';
				
			}
			
		} elseif ( isset( $_POST['social_rocket_save'] ) ) {
		
			// save settings
			
			// first, do a recursive stripslashes() on $_POST to make sure it's clean
			$_POST = $this->_stripslashes_deep( $_POST );
			
			// network selectors
			$scopes = array( 'inline', 'floating' );
			foreach ( $scopes as $scope ) {
				if ( isset( $_POST['social_rocket_'.$scope.'_networks'] ) ) {
					$SR->settings[$scope.'_buttons']['networks'] = array();
					if ( is_array( $_POST['social_rocket_'.$scope.'_networks'] ) ) {
						foreach ( $_POST['social_rocket_'.$scope.'_networks'] as $network ) {
							$SR->settings[$scope.'_buttons']['networks'][ $network ] = array( 'name' => $SR->networks[$network] );
							// network specific settings
							if ( isset( $_POST['social_rocket_'.$scope.'_network_'.$network] ) ) {
								$SR->settings[$scope.'_buttons']['networks'][ $network ]['settings'] = $_POST['social_rocket_'.$scope.'_network_'.$network];
							}
						}
					}
				}
				if ( isset( $_POST['social_rocket_'.$scope.'_networks_order'] ) && $_POST['social_rocket_'.$scope.'_networks_order'] > '' ) {
					$order = explode( ',', sanitize_text_field( $_POST['social_rocket_'.$scope.'_networks_order'] ) );
					if ( ! empty( $order ) ) {
						$sorted_array = array();
						foreach ( $order as $network ) {
							if ( isset( $SR->settings[$scope.'_buttons']['networks'][ $network ] ) ) {
								$sorted_array[ $network ] = $SR->settings[$scope.'_buttons']['networks'][ $network ];
							}
						}
						foreach ( $SR->settings[$scope.'_buttons']['networks'] as $network => $array ) {
							if ( ! isset( $sorted_array[ $network ] ) ) {
								$sorted_array[ $network ] = $SR->settings[$scope.'_buttons']['networks'][ $network ];
							}
						}
						$SR->settings[$scope.'_buttons']['networks'] = $sorted_array;
					}
				}
			}
			
			// inline networks
			foreach ( $post_types as $post_type ) {
				if ( isset( $_POST['social_rocket_inline_position_post_type_'.$post_type] ) ) {
					$SR->settings['inline_buttons']['position_post_type_'.$post_type] = sanitize_text_field( $_POST['social_rocket_inline_position_post_type_'.$post_type] );
				}
			}
			foreach ( $archives as $key => $archive ) {
				if ( isset( $_POST['social_rocket_inline_position_archive_'.$key] ) ) {
					$SR->settings['inline_buttons']['position_archive_'.$key] = sanitize_text_field( $_POST['social_rocket_inline_position_archive_'.$key] );
				}
			}
			
			// floating networks
			foreach ( $post_types as $post_type ) {
				if ( isset( $_POST['social_rocket_floating_position_post_type_'.$post_type] ) ) {
					$SR->settings['floating_buttons']['position_post_type_'.$post_type] = sanitize_text_field( $_POST['social_rocket_floating_position_post_type_'.$post_type] );
				}
			}
			foreach ( $archives as $key => $archive ) {
				if ( isset( $_POST['social_rocket_floating_position_archive_'.$key] ) ) {
					$SR->settings['floating_buttons']['position_archive_'.$key] = sanitize_text_field( $_POST['social_rocket_floating_position_archive_'.$key] );
				}
			}
			
			// active networks
			$SR->settings['active_networks'] = array_keys(
				array_merge( $SR->settings['inline_buttons']['networks'], $SR->settings['floating_buttons']['networks'] )
			);
			
			// everything else
			$settings = array(
				'social_rocket_auto_backup' => array(
					'path'           => array( 'auto_backup' ),
					'type'           => 'checkbox',
				),
				'social_rocket_auto_fix_gutenberg' => array(
					'path'           => array( 'auto_fix_gutenberg' ),
					'type'           => 'checkbox',
				),
				'social_rocket_custom_css' => array(
					'path'           => array( 'custom_css' ),
					'type'           => 'raw',
				),
				'social_rocket_decimal_places' => array(
					'path'           => array( 'decimal_places' ),
					'type'           => 'integer',
				),
				'social_rocket_decimal_separator' => array(
					'path'           => array( 'decimal_separator' ),
					'type'           => 'text',
				),
				'social_rocket_delete_settings' => array(
					'path'           => array( 'delete_settings' ),
					'type'           => 'checkbox',
				),
				'social_rocket_disable_fontawesome' => array(
					'path'           => array( 'disable_fontawesome' ),
					'type'           => 'checkbox',
				),
				'social_rocket_disable_og_tags' => array(
					'path'           => array( 'disable_og_tags' ),
					'type'           => 'checkbox',
				),
				'social_rocket_disable_twitter_cards' => array(
					'path'           => array( 'disable_twitter_cards' ),
					'type'           => 'checkbox',
				),
				'social_rocket_facebook_access_token' => array(
					'path'           => array( 'facebook', 'access_token' ),
					'type'           => 'text',
				),
				'social_rocket_floating_bar_background_color' => array(
					'path'           => array( 'floating_buttons', 'background_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_bar_padding' => array(
					'path'           => array( 'floating_buttons', 'padding' ),
					'type'           => 'text',
				),
				'social_rocket_floating_border' => array(
					'path'           => array( 'floating_buttons', 'border' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'none', 'solid', 'double', 'dashed', 'dotted', 'inset', 'outset', 'groove', 'ridge' ),
				),
				'social_rocket_floating_border_radius' => array(
					'path'           => array( 'floating_buttons', 'border_radius' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_border_size' => array(
					'path'           => array( 'floating_buttons', 'border_size' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_button_alignment' => array(
					'path'           => array( 'floating_buttons', 'button_alignment' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'left', 'right', 'center', 'stretch' ),
				),
				'social_rocket_floating_button_color_scheme' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'default', 'inverted', 'custom' ),
				),
				'social_rocket_floating_button_color_scheme_custom_background' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_background' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'network_icon', 'network_background', 'network_border', 'none' ),
				),
				'social_rocket_floating_button_color_scheme_custom_background_color' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_background_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_button_color_scheme_custom_border' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_border' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'network_icon', 'network_background', 'network_border', 'none' ),
				),
				'social_rocket_floating_button_color_scheme_custom_border_color' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_border_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_button_color_scheme_custom_hover' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_hover' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'none', 'network_hover' ),
				),
				'social_rocket_floating_button_color_scheme_custom_hover_color' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_hover_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_button_color_scheme_custom_hover_bg' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_hover_bg' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'none', 'network_hover_bg' ),
				),
				'social_rocket_floating_button_color_scheme_custom_hover_bg_color' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_hover_bg_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_button_color_scheme_custom_hover_border' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_hover_border' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'none', 'network_hover_border' ),
				),
				'social_rocket_floating_button_color_scheme_custom_hover_border_color' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_hover_border_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_button_color_scheme_custom_icon' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_icon' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'network_icon', 'network_background', 'network_border' ),
				),
				'social_rocket_floating_button_color_scheme_custom_icon_color' => array(
					'path'           => array( 'floating_buttons', 'button_color_scheme_custom_icon_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_button_show_cta' => array(
					'path'           => array( 'floating_buttons', 'button_show_cta' ),
					'type'           => 'checkbox',
				),
				'social_rocket_floating_button_size' => array(
					'path'           => array( 'floating_buttons', 'button_size' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_button_style' => array(
					'path'           => array( 'floating_buttons', 'button_style' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'oval', 'rectangle', 'round', 'square' ),
				),
				'social_rocket_floating_default_position' => array(
					'path'           => array( 'floating_buttons', 'default_position' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'left', 'right', 'top', 'bottom', 'none' ),
				),
				'social_rocket_floating_margin_bottom' => array(
					'path'           => array( 'floating_buttons', 'margin_bottom' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_margin_right' => array(
					'path'           => array( 'floating_buttons', 'margin_right' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_mobile_breakpoint' => array(
					'path'           => array( 'floating_mobile_breakpoint' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_mobile_setting' => array(
					'path'           => array( 'floating_mobile_setting' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'disabled', 'default', 'custom' ),
				),
				'social_rocket_floating_rounding' => array(
					'path'           => array( 'floating_buttons', 'rounding' ),
					'type'           => 'checkbox',
				),
				'social_rocket_floating_show_counts' => array(
					'path'           => array( 'floating_buttons', 'show_counts' ),
					'type'           => 'checkbox',
				),
				'social_rocket_floating_show_counts_min' => array(
					'path'           => array( 'floating_buttons', 'show_counts_min' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_show_total' => array(
					'path'           => array( 'floating_buttons', 'show_total' ),
					'type'           => 'checkbox',
				),
				'social_rocket_floating_show_total_min' => array(
					'path'           => array( 'floating_buttons', 'show_total_min' ),
					'type'           => 'integer',
				),
				'social_rocket_floating_total_color' => array(
					'path'           => array( 'floating_buttons', 'total_color' ),
					'type'           => 'text',
				),
				'social_rocket_floating_total_position' => array(
					'path'           => array( 'floating_buttons', 'total_position' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'before', 'after' ),
				),
				'social_rocket_floating_total_show_icon' => array(
					'path'           => array( 'floating_buttons', 'total_show_icon' ),
					'type'           => 'checkbox',
				),
				'social_rocket_floating_vertical_offset' => array(
					'path'           => array( 'floating_buttons', 'vertical_offset' ),
					'type'           => 'text',
				),
				'social_rocket_floating_vertical_position' => array(
					'path'           => array( 'floating_buttons', 'vertical_position' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'top', 'center', 'bottom' ),
				),
				'social_rocket_inline_border' => array(
					'path'           => array( 'inline_buttons', 'border' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'none', 'solid', 'double', 'dashed', 'dotted', 'inset', 'outset', 'groove', 'ridge' ),
				),
				'social_rocket_inline_border_radius' => array(
					'path'           => array( 'inline_buttons', 'border_radius' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_border_size' => array(
					'path'           => array( 'inline_buttons', 'border_size' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_button_alignment' => array(
					'path'           => array( 'inline_buttons', 'button_alignment' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'left', 'right', 'center', 'stretch' ),
				),
				'social_rocket_inline_button_color_scheme' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'default', 'inverted', 'custom' ),
				),
				'social_rocket_inline_button_color_scheme_custom_background' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_background' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'network_icon', 'network_background', 'network_border', 'none' ),
				),
				'social_rocket_inline_button_color_scheme_custom_background_color' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_background_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_button_color_scheme_custom_border' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_border' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'network_icon', 'network_background', 'network_border', 'none' ),
				),
				'social_rocket_inline_button_color_scheme_custom_border_color' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_border_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_button_color_scheme_custom_hover' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_hover' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'none', 'network_hover' ),
				),
				'social_rocket_inline_button_color_scheme_custom_hover_color' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_hover_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_button_color_scheme_custom_hover_bg' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_hover_bg' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'none', 'network_hover_bg' ),
				),
				'social_rocket_inline_button_color_scheme_custom_hover_bg_color' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_hover_bg_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_button_color_scheme_custom_hover_border' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_hover_border' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'none', 'network_hover_border' ),
				),
				'social_rocket_inline_button_color_scheme_custom_hover_border_color' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_hover_border_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_button_color_scheme_custom_icon' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_icon' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'custom', 'network_icon', 'network_background', 'network_border' ),
				),
				'social_rocket_inline_button_color_scheme_custom_icon_color' => array(
					'path'           => array( 'inline_buttons', 'button_color_scheme_custom_icon_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_button_show_cta' => array(
					'path'           => array( 'inline_buttons', 'button_show_cta' ),
					'type'           => 'checkbox',
				),
				'social_rocket_inline_button_size' => array(
					'path'           => array( 'inline_buttons', 'button_size' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_button_style' => array(
					'path'           => array( 'inline_buttons', 'button_style' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'oval', 'rectangle', 'round', 'square' ),
				),
				'social_rocket_inline_default_archive_position' => array(
					'path'           => array( 'inline_buttons', 'default_archive_position' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'above', 'below', 'both', 'item', 'none' ),
				),
				'social_rocket_inline_default_position' => array(
					'path'           => array( 'inline_buttons', 'default_position' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'above', 'below', 'both', 'none' ),
				),
				'social_rocket_inline_heading_alignment' => array(
					'path'           => array( 'inline_buttons', 'heading_alignment' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'default', 'left', 'right', 'center' ),
				),
				'social_rocket_inline_heading_element' => array(
					'path'           => array( 'inline_buttons', 'heading_element' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p' ),
				),
				'social_rocket_inline_heading_text' => array(
					'path'           => array( 'inline_buttons', 'heading_text' ),
					'type'           => 'text',
				),
				'social_rocket_inline_margin_bottom' => array(
					'path'           => array( 'inline_buttons', 'margin_bottom' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_margin_right' => array(
					'path'           => array( 'inline_buttons', 'margin_right' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_mobile_breakpoint' => array(
					'path'           => array( 'inline_mobile_breakpoint' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_mobile_setting' => array(
					'path'           => array( 'inline_mobile_setting' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'disabled', 'default', 'custom' ),
				),
				'social_rocket_inline_rounding' => array(
					'path'           => array( 'inline_buttons', 'rounding' ),
					'type'           => 'checkbox',
				),
				'social_rocket_inline_show_counts' => array(
					'path'           => array( 'inline_buttons', 'show_counts' ),
					'type'           => 'checkbox',
				),
				'social_rocket_inline_show_counts_min' => array(
					'path'           => array( 'inline_buttons', 'show_counts_min' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_show_total' => array(
					'path'           => array( 'inline_buttons', 'show_total' ),
					'type'           => 'checkbox',
				),
				'social_rocket_inline_show_total_min' => array(
					'path'           => array( 'inline_buttons', 'show_total_min' ),
					'type'           => 'integer',
				),
				'social_rocket_inline_total_color' => array(
					'path'           => array( 'inline_buttons', 'total_color' ),
					'type'           => 'text',
				),
				'social_rocket_inline_total_position' => array(
					'path'           => array( 'inline_buttons', 'total_position' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'before', 'after' ),
				),
				'social_rocket_inline_total_show_icon' => array(
					'path'           => array( 'inline_buttons', 'total_show_icon' ),
					'type'           => 'checkbox',
				),
				'social_rocket_master_throttle' => array(
					'path'           => array( 'master_throttle' ),
					'type'           => 'integer',
				),
				'social_rocket_pinterest_image_fallback' => array(
					'path'           => array( 'pinterest', 'image_fallback' ),
					'type'           => 'whitelist',
					'allowed_values' => array( 'featured', 'chooser' ),
				),
				'social_rocket_refresh_interval' => array(
					'path'           => array( 'refresh_interval' ),
					'type'           => 'integer',
				),
				'social_rocket_social_identity_pinterest' => array(
					'path'           => array( 'social_identity', 'pinterest' ),
					'type'           => 'text',
				),
				'social_rocket_social_identity_twitter' => array(
					'path'           => array( 'social_identity', 'twitter' ),
					'type'           => 'text',
				),
			);
			
			foreach ( $settings as $post_key => $setting ) {
				if ( ! isset( $_POST[ $post_key ] ) ) {
					continue;
				}
				$value = null;
				switch ( $setting['type'] ) {
					case 'checkbox':
						if ( $_POST[ $post_key ] === '1' ) {
							$value = true;
						} else {
							$value = false;
						}
						break;
					case 'integer':
						$value = intval( $_POST[ $post_key ] );
						break;
					case 'raw':
						$value = $_POST[ $post_key ];
						break;
					case 'repeatable':
						if ( is_array( $_POST[ $post_key ] ) ) {
							$value = array();
							foreach ( $_POST[ $post_key ] as $array_element ) {
								if ( $array_element > '' ) {
									$value[] = $array_element;
								}
							}
						}
						break;
					case 'text':
						$value = sanitize_text_field( $_POST[ $post_key ] );
						break;
					case 'whitelist':
						if ( in_array( $_POST[ $post_key ], $setting['allowed_values'] ) ) {
							$value = sanitize_text_field( $_POST[ $post_key ] );
						}
						break;
				}
				if ( $value !== null ) {
					$ref = &$SR->settings;
					foreach( $setting['path'] as $key ) {
						$ref = &$ref[ $key ];
					}
					$ref = $value;
				}
			}
			
			// save
			do_action( 'social_rocket_settings_save' );
			update_option( 'social_rocket_settings', $SR->settings );
			
			// clear old errors, if any
			delete_option( '_social_rocket_facebook_invalid_token' );
			
		}
	}
	
	
	public function attachment_fields_display( $form_fields, $post ) {
	    
	    if ( strpos( $post->post_mime_type, 'image' ) === false ) {
	    	return $form_fields;
		}

	    $pinterest_description = get_post_meta( $post->ID, 'social_rocket_pinterest_description', true );
		
		$form_fields['social_rocket_settings_heading'] = array(
	    	'input'	=> 'html',
			'html'  => ' ',
	        'label' => '<h2>' . __( 'Social Rocket Settings', 'social-rocket' ) . '</h2>',
	    );

	    $form_fields['social_rocket_pinterest_description'] = array(
	    	'input'	=> 'textarea',
	        'value' => $pinterest_description ? $pinterest_description : '',
	        'label' => __( 'Pinterest<br />Description', 'social-rocket' ),
	    );
		
	    $form_fields['srp_pinterest_pin_title'] = array(
	    	'input'	=> 'html',
	        'html'  => '<input type="text" disabled="disabled" />',
	        'label' => __( '(PRO) Pinterest<br />Title', 'social-rocket' ),
	    );
		
	    $form_fields['srp_pinterest_pin_id'] = array(
	    	'input'	=> 'html',
	        'html'  => '<input type="text" disabled="disabled" />',
	        'label' => __( '(PRO) Pinterest<br />Re-pin ID', 'social-rocket' ),
	    );
		
	    $form_fields['srp_pinterest_allow_pinning'] = array(
	    	'input'	=> 'html',
			'html'  => '<input type="checkbox" checked="checked" disabled="disabled" />',
	        'label' => __( '(PRO) Allow Image<br />to be Pinned', 'social-rocket' ),
	    );

	    return $form_fields;

	}
	
	
	public function attachment_fields_js_data( $response, $attachment, $meta ) {

		$response['social_rocket_pinterest_description'] = esc_attr( get_post_meta( $attachment->ID, 'social_rocket_pinterest_description', true ) );

		return $response;

	}
	
	
	public function attachment_fields_save( $post, $attachment ) {
		if ( isset( $attachment['social_rocket_pinterest_description'] ) ) {
			update_post_meta( $post['ID'], 'social_rocket_pinterest_description', $attachment['social_rocket_pinterest_description'] );
		}
		return $post;
	}
	
	
	public function enqueue_scripts() {
	
		global $pagenow;
		$SR = Social_Rocket::get_instance();
		
		if (
			$pagenow === 'admin.php' && isset( $_GET['page'] ) &&
		    in_array( $_GET['page'], array( 'social_rocket_inline_buttons', 'social_rocket_floating_buttons', 'social_rocket_click_to_tweet' ) )
		) {
			wp_enqueue_script( 'thickbox' );
		}
		
		wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'social-rocket-admin', plugin_dir_url( dirname( __FILE__ ) ) .'js/admin.js', array( 'jquery', 'wp-color-picker' ), SOCIAL_ROCKET_VERSION, true );
		
		wp_localize_script(
			'social-rocket-admin',
			'socialRocketAdmin',
			array(
				'auto_fix_gutenberg'          => $this->_isset( $SR->settings['auto_fix_gutenberg'] ),
				'floating_networks'           => $SR->settings['floating_buttons']['networks'],
				'i18n'                        => array(
					'collapsable_hide'                => __( 'Hide', 'social-rocket' ),
					'collapsable_show'                => __( 'Show', 'social-rocket' ),
					'colorpicker_reset'               => __( 'Reset', 'social-rocket' ),
					'confirm'                         => __( 'Are you sure?', 'social-rocket' ),
					'confirm_tweet_settings_update'   => __( 'Are you sure? This Saved Style will be replaced with the currently displayed settings.', 'social-rocket' ),
					'confirm_unsaved'                 => __( 'You have unsaved changes. Are you sure you want to leave this page?', 'social-rocket' ),
					'media_pin_description'           => __( 'Pinterest Description', 'social-rocket' ),
					'media_pin_id'                    => __( '(PRO) Pinterest Re-pin ID', 'social-rocket' ),
					'media_pin_nopin'                 => __( '(PRO) Allow Image to be Pinned', 'social-rocket' ),
					'media_pin_title'                 => __( '(PRO) Pinterest Title', 'social-rocket' ),
					'media_section_header'            => __( 'Social Rocket Settings', 'social-rocket' ),
					'recalc_requested'                => __( 'Recalculation scheduled', 'social-rocket' ),
					'settings_tweet_delete'           => __( 'Delete', 'social-rocket' ),
					'settings_tweet_load'             => __( 'Load', 'social-rocket' ),
					'settings_tweet_no_name'          => __( 'no name', 'social-rocket' ),
					'shortcode_inline_header_title'   => __( 'Social Rocket Inline Buttons - Shortcode Builder', 'social-rocket' ),
					'shortcode_inline_heading'        => __( 'Heading text (optional)', 'social-rocket' ),
					'shortcode_inline_networks'       => __( 'Networks (optional; defaults to all active networks)', 'social-rocket' ),
					'shortcode_inline_show_counts'    => __( 'Show Counts', 'social-rocket' ),
					'shortcode_inline_show_total'     => __( 'Show Total', 'social-rocket' ),
					'tinymce_tweet_tooltip'           => __( 'Social Rocket - Click to Tweet', 'social-rocket' ),
					'tinymce_tweet_header_title'      => __( 'Social Rocket Click to Tweet - Shortcode Builder', 'social-rocket' ),
					'tinymce_tweet_buttons_add'       => __( 'Add Shortcode', 'social-rocket' ),
					'tinymce_tweet_buttons_cancel'    => __( 'Cancel', 'social-rocket' ),
					'tinymce_tweet_label_tweet'       => __( 'Tweet to be shared on Twitter', 'social-rocket' ),
					'tinymce_tweet_label_quote'       => __( 'Quote to be displayed on your page', 'social-rocket' ),
					'tinymce_tweet_label_include_url' => __( 'Include the URL', 'social-rocket' ),
					'tinymce_tweet_desc_include_url'  => __( 'The URL of the post will be added to the tweet', 'social-rocket' ),
					'tinymce_tweet_label_include_via' => __( 'Include "via"', 'social-rocket' ),
					'tinymce_tweet_desc_include_via'  => __( 'The Twitter username saved in the Settings page will be added to the tweet.', 'social-rocket' ),
					'tinymce_tweet_style'             => __( 'Display Style', 'social-rocket' ),
					'tinymce_tweet_style_css_class'   => __( 'Custom CSS class (optional)', 'social-rocket' ),
					'tinymce_tweet_custom_url'        => __( 'Custom URL (optional)', 'social-rocket' ),
					'tinymce_tweet_custom_via'        => __( 'Custom Via (optional)', 'social-rocket' ),
					'tweet_settings_loaded'           => __( 'Style loaded successfully', 'social-rocket' ),
					'tweet_settings_saved'            => __( 'Style saved successfully', 'social-rocket' ),
				),
				'inline_networks'             => $SR->settings['inline_buttons']['networks'],
				'networks'                    => $SR->networks,
				'pinterest_image_placeholder' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/pinterest-image-placeholder.png',
				'social_image_placeholder'    => plugin_dir_url( dirname( __FILE__ ) ) . 'img/social-image-placeholder.png',
				'tweet_settings'              => array(
					'include_url'                  => $SR->settings['tweet_settings']['saved_settings']['default']['include_url'],
					'include_via'                  => $SR->settings['tweet_settings']['saved_settings']['default']['include_via'],
					'saved_settings'               => $SR->settings['tweet_settings']['saved_settings'],
					'via_username'                 => $SR->settings['social_identity']['twitter'],
				),
				'wp_version_gte_3_5'          => version_compare( get_bloginfo( 'version' ), '3.5', '>=' ), // not used at the moment
			)
		);
		
	}
	
	
	public function enqueue_styles() {
	
		global $pagenow;
		$SR = Social_Rocket::get_instance();
		
		if ( ! $SR->settings['disable_fontawesome'] ) {
			wp_enqueue_style(
				'fontawesome_all',
				plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/css/all.min.css',
				array(),
				SOCIAL_ROCKET_VERSION,
				'all'
			);
		}
		
		if (
			$pagenow === 'admin.php' && isset( $_GET['page'] ) &&
		    in_array( $_GET['page'], array( 'social_rocket_inline_buttons', 'social_rocket_floating_buttons', 'social_rocket_click_to_tweet' ) )
		) {
			wp_enqueue_style( 'thickbox' );
		}
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'social_rocket_admin', plugin_dir_url( dirname( __FILE__ ) ) . 'css/admin.css', array(), SOCIAL_ROCKET_VERSION, 'all' );
		
		$custom_css = '';
		
		wp_add_inline_style( 'social_rocket_admin', apply_filters( 'social_rocket_admin_custom_css', $custom_css ) );
		
	}
	
	
	/**
	 * Gets System Information
	 *
	 * @since 1.0.0
	 *
	 * @return string Human-readable system info.
	 */
	public function get_system_info() {
		global $wpdb;
		
		$SR = Social_Rocket::get_instance();
		$settings = apply_filters( 'social_rocket_system_info_get_settings', $SR->settings );
		
		// Get browser info
		$browser = new Browser();

		// Get theme info
		$theme_data = wp_get_theme();
		$theme      = $theme_data->Name . ' ' . $theme_data->Version;

		// Try to identify the hosting provider
		if( defined( 'WPE_APIKEY' ) ) {
			$host = 'WP Engine';
		} elseif( defined( 'PAGELYBIN' ) ) {
			$host = 'Pagely';
		} elseif( DB_HOST == 'localhost:/tmp/mysql5.sock' ) {
			$host = 'ICDSoft';
		} elseif( DB_HOST == 'mysqlv5' ) {
			$host = 'NetworkSolutions';
		} elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
			$host = 'iPage';
		} elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
			$host = 'IPower';
		} elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
			$host = 'MediaTemple Grid';
		} elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
			$host = 'pair Networks';
		} elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
			$host = 'Rackspace Cloud';
		} elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
			$host = 'SysFix.eu Power Hosting';
		} elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
			$host = 'Flywheel';
		} else {
			$host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
		}

		$return  = '### Begin System Info ###' . "\n\n";

		// Start with the basics...
		$return .= '/////-- Site Info' . "\n\n";
		$return .= 'Site URL:                 ' . site_url() . "\n";
		$return .= 'Home URL:                 ' . home_url() . "\n";
		$return .= 'Multisite:                ' . ( is_multisite() ? 'Yes' : 'No' ) . "\n";
		$return .= 'Host:                     ' . $host . "\n\n";

		// The local user's browser information, handled by the Browser class
		$return .= "\n" . '/////-- User Browser' . "\n\n";
		$return .= wp_strip_all_tags( $browser ) . "\n\n";

		// WordPress configuration
		$return .= "\n" . '/////-- WordPress Configuration' . "\n\n";
		$return .= 'Version:                  ' . get_bloginfo( 'version' ) . "\n";
		$return .= 'Language:                 ' . ( defined( 'WPLANG' ) && WPLANG ? WPLANG : 'en_US' ) . "\n";
		$return .= 'Permalink Structure:      ' . ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ) . "\n";
		$return .= 'Active Theme:             ' . $theme . "\n";
		$return .= 'Show On Front:            ' . get_option( 'show_on_front' ) . "\n";

		// Only show page specs if frontpage is set to 'page'
		if( get_option( 'show_on_front' ) == 'page' ) {
			$front_page_id = get_option( 'page_on_front' );
			$blog_page_id = get_option( 'page_for_posts' );
			$return .= 'Page On Front:            ' . ( $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset' ) . "\n";
			$return .= 'Page For Posts:           ' . ( $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset' ) . "\n";
		}

		$return .= 'Table Prefix:             ' . 'Length: ' . strlen( $wpdb->prefix ) . '   Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' ) . "\n";
		$return .= 'WP_DEBUG:                 ' . ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ) . "\n";
		$return .= 'Memory Limit:             ' . WP_MEMORY_LIMIT . "\n";
		$return .= 'Registered Post Status:   ' . implode( ', ', get_post_stati() ) . "\n\n";

		// Social Rocket configuration
		$plugin_data = get_plugin_data( SOCIAL_ROCKET_PATH . 'social-rocket.php', false );
		
		// mask sensitive info
		$sensitive_infos = array(
		);
		foreach ( $sensitive_infos as $sensitive_info ) {
			if ( isset( $settings[$sensitive_info] ) ) {
				$settings[$sensitive_info] = empty( $settings[$sensitive_info] ) ? '(not set)' : '(set)';
			}
		}

		$return .= "\n" . '/////-- Social Rocket Configuration' . "\n\n";
		$return .= 'Version:                  ' . $plugin_data['Version'] . "\n";
		$return .= 'Settings:' . "\n";
		foreach ( $settings as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = json_encode( $value );
			}
			$return .= '    ' . $key . ':' . str_repeat( ' ', 34 - strlen( $key ) ) . $value . "\n";
		}
		$invalid_facebook_token = get_option( '_social_rocket_facebook_invalid_token' );
		$return .= 'Facebook token error message: ';
		if ( $invalid_facebook_token > '' ) {
			$return .= $invalid_facebook_token . "\n";
		} elseif ( $invalid_facebook_token === '' ) {
			$return .= "(permanently dismissed)\n";
		} else {
			$return .= "(no error)\n";
		}
		$return .= "\n";

		// Get plugins that have an update
		$updates = get_plugin_updates();

		// Must-use plugins
		// NOTE: MU plugins can't show updates!
		$muplugins = get_mu_plugins();
		if ( count( $muplugins ) > 0 ) {
			$return .= "\n" . '/////-- Must-Use Plugins' . "\n\n";
			foreach( $muplugins as $plugin => $plugin_data ) {
				$return .= $plugin_data['Name'] . ': ' . $plugin_data['Version'] . "\n";
			}
		}

		// WordPress active plugins
		$return .= "\n" . '/////-- WordPress Active Plugins' . "\n\n";
		$plugins = get_plugins();
		$active_plugins = get_option( 'active_plugins', array() );
		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( ! in_array( $plugin_path, $active_plugins ) ) {
				continue;
			}
			$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		}
		$return .= "\n";

		// WordPress inactive plugins
		$return .= "\n" . '/////-- WordPress Inactive Plugins' . "\n\n";
		foreach ( $plugins as $plugin_path => $plugin ) {
			if ( in_array( $plugin_path, $active_plugins ) ) {
				continue;
			}
			$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
			$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
		}
		$return .= "\n";

		if( is_multisite() ) {
			// WordPress Multisite active plugins
			$return .= "\n" . '/////-- Network Active Plugins' . "\n\n";
			$plugins = wp_get_active_network_plugins();
			$active_plugins = get_site_option( 'active_sitewide_plugins', array() );
			foreach ( $plugins as $plugin_path ) {
				$plugin_base = plugin_basename( $plugin_path );
				if( ! array_key_exists( $plugin_base, $active_plugins ) ) {
					continue;
				}
				$update = ( array_key_exists( $plugin_path, $updates ) ) ? ' (needs update - ' . $updates[$plugin_path]->update->new_version . ')' : '';
				$plugin  = get_plugin_data( $plugin_path );
				$return .= $plugin['Name'] . ': ' . $plugin['Version'] . $update . "\n";
			}
			$return .= "\n";
		}

		// Server configuration (really just versioning)
		$return .= "\n" . '/////-- Webserver Configuration' . "\n\n";
		$return .= 'PHP Version:              ' . PHP_VERSION . "\n";
		$return .= 'MySQL Version:            ' . $wpdb->db_version() . "\n";
		$return .= 'Webserver Info:           ' . $_SERVER['SERVER_SOFTWARE'] . "\n\n";

		// PHP configs... now we're getting to the important stuff
		$return .= "\n" . '/////-- PHP Configuration' . "\n\n";
		$return .= 'Safe Mode:                ' . ( ini_get( 'safe_mode' ) ? 'Enabled' : 'Disabled' . "\n" );
		$return .= 'Memory Limit:             ' . ini_get( 'memory_limit' ) . "\n";
		$return .= 'Upload Max Size:          ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Post Max Size:            ' . ini_get( 'post_max_size' ) . "\n";
		$return .= 'Upload Max Filesize:      ' . ini_get( 'upload_max_filesize' ) . "\n";
		$return .= 'Time Limit:               ' . ini_get( 'max_execution_time' ) . "\n";
		$return .= 'Max Input Vars:           ' . ini_get( 'max_input_vars' ) . "\n";
		$return .= 'Display Errors:           ' . ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ) . "\n\n";

		// PHP extensions and such
		$return .= "\n" . '/////-- PHP Extensions' . "\n\n";
		$return .= 'cURL:                     ' . ( function_exists( 'curl_init' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'fsockopen:                ' . ( function_exists( 'fsockopen' ) ? 'Supported' : 'Not Supported' ) . "\n";
		$return .= 'SOAP Client:              ' . ( class_exists( 'SoapClient' ) ? 'Installed' : 'Not Installed' ) . "\n";
		$return .= 'Suhosin:                  ' . ( extension_loaded( 'suhosin' ) ? 'Installed' : 'Not Installed' ) . "\n";
		$return .= 'Mbstring:                 ' . ( extension_loaded( 'mbstring' ) ? 'Installed' : 'Not Installed' ) . "\n\n";

		// Session stuff
		$return .= "\n" . '/////-- Session Configuration' . "\n\n";
		$return .= 'Session:                  ' . ( isset( $_SESSION ) ? 'Enabled' : 'Disabled' ) . "\n";

		// The rest of this is only relevant is session is enabled
		if( isset( $_SESSION ) ) {
			$return .= 'Session Name:             ' . esc_html( ini_get( 'session.name' ) ) . "\n";
			$return .= 'Cookie Path:              ' . esc_html( ini_get( 'session.cookie_path' ) ) . "\n";
			$return .= 'Save Path:                ' . esc_html( ini_get( 'session.save_path' ) ) . "\n";
			$return .= 'Use Cookies:              ' . ( ini_get( 'session.use_cookies' ) ? 'On' : 'Off' ) . "\n";
			$return .= 'Use Only Cookies:         ' . ( ini_get( 'session.use_only_cookies' ) ? 'On' : 'Off' ) . "\n";
		}

		$return = apply_filters( 'social_rocket_system_info', $return );

		$return .= "\n" . '### End System Info ###';

		return $return;
	}
	
	
	public static function handle_backup( $return = false ) {
		
		global $pagenow, $wpdb;
		
		if (
			$return ||
			(
				$pagenow === 'admin.php' &&
				isset( $_GET['page'] ) &&
				$_GET['page'] === 'social_rocket_settings' &&
				isset( $_POST['social_rocket_settings_backup'] ) &&
				current_user_can( 'manage_options' )
			)
		) {
			
			set_time_limit(0);
			
			$SR = Social_Rocket::get_instance();
			
			/* TODO: save for future use?
			$table_name = $wpdb->prefix . 'social_rocket_count_data';
			$counts     = $wpdb->get_results( "SELECT * FROM $table_name", ARRAY_A );
			*/
			
			$postmetas = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key IN ( 'social_rocket_og_description', 'social_rocket_og_image', 'social_rocket_og_title', 'social_rocket_pinterest_description', 'social_rocket_pinterest_image', 'social_rocket_twitter_message', 'social_rocket_floating_position', 'social_rocket_inline_position' )", ARRAY_A );
			
			$termmetas = $wpdb->get_results( "SELECT * FROM $wpdb->termmeta WHERE meta_key IN ( 'social_rocket_og_description', 'social_rocket_og_image', 'social_rocket_og_title', 'social_rocket_pinterest_description', 'social_rocket_pinterest_image', 'social_rocket_twitter_message', 'social_rocket_floating_position', 'social_rocket_inline_position' )", ARRAY_A );
			
			$usermetas = $wpdb->get_results( "SELECT * FROM $wpdb->usermeta WHERE meta_key IN ( 'social_rocket_facebook_profile_url', 'social_rocket_twitter_username' )", ARRAY_A );
			
			$output = array(
				'social-rocket'            => $SR->settings,
				// 'social-rocket-count-data' => $counts, // TODO: save for future use?
				'social-rocket-postmetas'  => $postmetas,
				'social-rocket-termmetas'  => $termmetas,
				'social-rocket-usermetas'  => $usermetas,
			);
			$output = apply_filters( 'social_rocket_backup_output', $output );

			if ( $return ) {
				return json_encode( $output );
			}
			
			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=social-rocket-backup-' . date( 'Y-m-d' ) . '-' . preg_replace( '/[^a-z0-9]+/', '-', strtolower( site_url() ) ) . '.json' );
			header( 'Expires: 0' );

			echo json_encode( $output );
			exit;
			
		}
	}
	
	
	public function handle_settings_export() {
		
		global $pagenow;
		
		if (
			$pagenow === 'admin.php' &&
			isset( $_GET['page'] ) &&
			$_GET['page'] === 'social_rocket_settings' &&
			isset( $_POST['social_rocket_settings_export'] )
		) {
			
			// export settings
			if( ! current_user_can( 'manage_options' ) ) {
				exit;
			}
			
			$SR = Social_Rocket::get_instance();
			$output = apply_filters( 'social_rocket_settings_export_output', array( 'social-rocket' => $SR->settings ) );

			nocache_headers();
			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=social-rocket-settings-export-' . date( 'Y-m-d' ) . '.json' );
			header( 'Expires: 0' );

			echo json_encode( $output );
			exit;
			
		}
	}
	
	
	public function metabox_display() {
		#region metabox_display
	
		if ( version_compare( get_bloginfo( 'version' ), '3.5', '>=' ) ) {
			wp_enqueue_media();
		} else {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'thickbox' );
		}
		
		$SR = Social_Rocket::get_instance();
		
		$nonce = wp_create_nonce( 'social_rocket_metabox' );
		
		$post_id = get_the_ID();
		
		$og_description    = get_post_meta( $post_id, 'social_rocket_og_description', true );
		$og_image          = get_post_meta( $post_id, 'social_rocket_og_image', true );
		$og_title          = get_post_meta( $post_id, 'social_rocket_og_title', true );
		$pinterest_desc    = get_post_meta( $post_id, 'social_rocket_pinterest_description', true );
		$pinterest_image   = get_post_meta( $post_id, 'social_rocket_pinterest_image', true );
		$twitter_message   = get_post_meta( $post_id, 'social_rocket_twitter_message', true );
		$inline_position   = get_post_meta( $post_id, 'social_rocket_inline_position', true );
		$floating_position = get_post_meta( $post_id, 'social_rocket_floating_position', true );
		
		?>
		
		<input type="hidden" name="_social_rocket_metabox" value="<?php echo $nonce; ?>" />
		
		<div class="sr-grid">
			
			<div class="sr-grid__col sr-grid__col--1-of-3">
				
				<label><?php _e( 'Social Media Image', 'social-rocket' ); ?></label>
				<div class="social-rocket-image-uploader <?php echo $og_image ? 'has-image' : ''; ?>">
					<div class="social-rocket-image-uploader-inner">
						<div class="social-rocket-image-uploader-image-overlay"></div>
						<div class="social-rocket-image-uploader-image-bar">
							<a href="<?php echo $og_image ? get_edit_post_link( $og_image, 'raw' ) : '#'; ?>" class="social-rocket-image-uploader-image-edit" title="Edit" target="_blank">
								<span class="dashicons dashicons-edit"></span>
							</a>
							<a href="#" class="social-rocket-image-uploader-image-remove" title="Remove">
								<span class="dashicons dashicons-no-alt"></span>
							</a>
						</div>
						<div class="social-rocket-image-uploader-image">
							<img src="<?php echo $og_image ? wp_get_attachment_url( $og_image ) : plugin_dir_url( dirname( __FILE__ ) ) . 'img/social-image-placeholder.png'; ?>" />
						</div>
					</div>
					<input type="hidden" class="social-rocket-image-upload-id" name="social_rocket_og_image" value="<?php echo esc_attr( $og_image ); ?>" />
					<button type="button" class="button-secondary social-rocket-image-upload-button"><?php _e( 'Upload Image', 'social-rocket' ); ?></button>
				</div>
				<p class="social-rocket-metabox-description"><?php _e( 'An image optimized for maximum impact on most social networks, including Facebook. Recommended size: 1200 x 630 px.', 'social-rocket' ); ?></p>
				
				<label><i class="fab fa-pinterest"></i>&nbsp;<?php _e( 'Pinterest Image', 'social-rocket' ); ?></label>
				<div class="social-rocket-image-uploader <?php echo $pinterest_image ? 'has-image' : ''; ?>">
					<div class="social-rocket-image-uploader-inner">
						<div class="social-rocket-image-uploader-image-overlay"></div>
						<div class="social-rocket-image-uploader-image-bar">
							<a href="<?php echo $pinterest_image ? get_edit_post_link( $pinterest_image, 'raw' ) : '#'; ?>" class="social-rocket-image-uploader-image-edit" title="Edit" target="_blank">
								<span class="dashicons dashicons-edit"></span>
							</a>
							<a href="#" class="social-rocket-image-uploader-image-remove" title="Remove">
								<span class="dashicons dashicons-no-alt"></span>
							</a>
						</div>
						<div class="social-rocket-image-uploader-image">
							<img src="<?php echo $pinterest_image ? wp_get_attachment_url( $pinterest_image ) : plugin_dir_url( dirname( __FILE__ ) ) . 'img/pinterest-image-placeholder.png'; ?>" />
						</div>
					</div>
					<input type="hidden" class="social-rocket-image-upload-id" name="social_rocket_pinterest_image" value="<?php echo esc_attr( $pinterest_image ); ?>" />
					<button type="button" class="button-secondary social-rocket-image-upload-button"><?php _e( 'Upload Image', 'social-rocket' ); ?></button>
				</div>
				<p class="social-rocket-metabox-description"><?php _e( 'An image optimized for maximum impact on Pinterest. Recommended size: 735 x 1102 px.', 'social-rocket' ); ?></p>
				
				<?php do_action( 'social_rocket_metabox_content_left' ); ?>
				
			</div>
			
			<div class="sr-grid__col sr-grid__col--2-of-3">
				
				<label for="social_rocket_og_title"><?php _e( 'Social Media Title', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_og_title" name="social_rocket_og_title" rows="3"><?php echo esc_attr( $og_title ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_og_title" data-max-chars="60"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 60 - strlen( $og_title ) < 0 ? 'negative' : '' ) . '">' . ( 60 - strlen( $og_title ) ) . '/60</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'Content to populate the open graph "title" meta tag, which is shown when your content is shared to most social networks. If left blank, the post title will be used by default.', 'social-rocket' ); ?></p>
				
				<label for="social_rocket_og_description"><?php _e( 'Social Media Description', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_og_description" name="social_rocket_og_description" rows="3"><?php echo esc_attr( $og_description ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_og_description" data-max-chars="160"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 160 - strlen( $og_description ) < 0 ? 'negative' : '' ) . '">' . ( 160 - strlen( $og_description ) ) . '/160</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'Content to populate the open graph "description" meta tag, which is shown when your content is shared to most social networks.', 'social-rocket' ); ?></p>
				
				<p>&nbsp;</p>
				
				<label for="social_rocket_twitter_message"><i class="fab fa-twitter"></i>&nbsp;<?php _e( 'Custom Tweet', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_twitter_message" name="social_rocket_twitter_message" rows="3"><?php echo esc_attr( $twitter_message ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_twitter_message" data-max-chars="280"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 280 - strlen( $twitter_message ) < 0 ? 'negative' : '' ) . '">' . ( 280 - strlen( $twitter_message ) ) . '/280</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'If this is left blank your post title will be used. Be sure to leave enough room for a link to be added!', 'social-rocket' ); ?></p>
				
				<p>&nbsp;</p>
				
				<label for="social_rocket_pinterest_description"><i class="fab fa-pinterest"></i>&nbsp;<?php _e( 'Pinterest Description', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_pinterest_description" name="social_rocket_pinterest_description" rows="3"><?php echo esc_attr( $pinterest_desc ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_pinterest_description" data-max-chars="500"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 500 - strlen( $pinterest_desc ) < 0 ? 'negative' : '' ) . '">' . ( 500 - strlen( $pinterest_desc ) ) . '/500</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'The description that will be used when this post is shared on Pinterest. If left blank, the post title will be used by default.', 'social-rocket' ); ?></p>
				<?php do_action( 'social_rocket_metabox_content_pinterest' ); ?>
				
				<p>&nbsp;</p>
				
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<label for="social_rocket_inline_position"><?php _e( 'Inline Buttons Position', 'social-rocket' ); ?></label>
						<select id="social_rocket_inline_position" name="social_rocket_inline_position">
							<option value="" <?php selected( $inline_position, '' ); ?>><?php _e( 'Use default setting', 'social-rocket' ); ?></option>
							<option value="above" <?php selected( $inline_position, 'above' ); ?>><?php _e( 'Above the content', 'social-rocket' ); ?></option>
							<option value="below" <?php selected( $inline_position, 'below' ); ?>><?php _e( 'Below the content', 'social-rocket' ); ?></option>
							<option value="both" <?php selected( $inline_position, 'both' ); ?>><?php _e( 'Both above and below the content', 'social-rocket' ); ?></option>
							<option value="none" <?php selected( $inline_position, 'none' ); ?>><?php _e( 'None/manual placement', 'social-rocket' ); ?></option>
						</select>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<label for="social_rocket_floating_position"><?php _e( 'Floating Buttons Position', 'social-rocket' ); ?></label>
						<select id="social_rocket_floating_position" name="social_rocket_floating_position">
							<option value="" <?php selected( $floating_position, '' ); ?>><?php _e( 'Use default setting', 'social-rocket' ); ?></option>
							<option value="left" <?php selected( $floating_position, 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
							<option value="right" <?php selected( $floating_position, 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
							<option value="top" <?php selected( $floating_position, 'top' ); ?>><?php _e( 'Top', 'social-rocket' ); ?></option>
							<option value="bottom" <?php selected( $floating_position, 'bottom' ); ?>><?php _e( 'Bottom', 'social-rocket' ); ?></option>
							<option value="none" <?php selected( $floating_position, 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
						</select>
					</div>
				</div>
				
				<?php do_action( 'social_rocket_metabox_content_right' ); ?>
				
			</div>
			
		</div>
		
		<?php
		
		do_action( 'social_rocket_metabox_content_bottom' );
	
		#endregion metabox_display
	}
	
	
	public function metabox_save( $post_id ) {

		if ( ! isset( $_REQUEST['_social_rocket_metabox'] ) ) {
			return;
		}
		
		$nonce = $_REQUEST['_social_rocket_metabox'];
		if ( ! wp_verify_nonce( $nonce, 'social_rocket_metabox' ) ) {
			return;
		}

		$postmetas = array(
			'social_rocket_og_description'                       => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_og_description'] ) ),
			'social_rocket_og_image'                             => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_og_image'] ) ),
			'social_rocket_og_title'                             => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_og_title'] ) ),
			'social_rocket_pinterest_description'                => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_pinterest_description'] ) ),
			'social_rocket_pinterest_image'                      => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_pinterest_image'] ) ),
			'social_rocket_twitter_message'                      => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_twitter_message'] ) ),
			'social_rocket_floating_position'                    => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_floating_position'] ) ),
			'social_rocket_inline_position'                      => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_inline_position'] ) ),
		);
		
		foreach ( $postmetas as $key => $value ) {
			if ( $value > '' ) {
				update_post_meta( $post_id, $key, $value );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
		
		do_action( 'social_rocket_metabox_save', $post_id );

	}
	
	
	public function taxonomy_metabox_display( $term ) {
		#region taxonomy_metabox_display
	
		if ( version_compare( get_bloginfo( 'version' ), '4.4', '<' ) ) {
			return; // gotta bail if we don't have term_meta support
		}
		
		if ( version_compare( get_bloginfo( 'version' ), '3.5', '>=' ) ) {
			wp_enqueue_media();
		} else {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'thickbox' );
		}
		
		$SR = Social_Rocket::get_instance();
		
		$nonce = wp_create_nonce( 'social_rocket_metabox' );
		
		$term_id = $term->term_id;
		
		$og_description    = get_term_meta( $term_id, 'social_rocket_og_description', true );
		$og_image          = get_term_meta( $term_id, 'social_rocket_og_image', true );
		$og_title          = get_term_meta( $term_id, 'social_rocket_og_title', true );
		$pinterest_desc    = get_term_meta( $term_id, 'social_rocket_pinterest_description', true );
		$pinterest_image   = get_term_meta( $term_id, 'social_rocket_pinterest_image', true );
		$twitter_message   = get_term_meta( $term_id, 'social_rocket_twitter_message', true );
		$inline_position   = get_term_meta( $term_id, 'social_rocket_inline_position', true );
		$floating_position = get_term_meta( $term_id, 'social_rocket_floating_position', true );
		
		?>
		
		<div class="metabox-holder">
		<div class="meta-box-sortables ui-sortable">
		<div id="social_rocket_metabox" class="postbox social-rocket-taxonomy-metabox">
		<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Social Rocket Settings</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle" style="cursor:pointer;"><span><?php _e( 'Social Rocket Settings', 'social-rocket' ); ?></span></h2>
		<div class="inside">

		<input type="hidden" name="_social_rocket_metabox" value="<?php echo $nonce; ?>" />
		<input type="hidden" name="social_rocket_edited_taxonomy" value="<?php echo $term_id; ?>" />
		
		<div class="sr-grid">
			
			<div class="sr-grid__col sr-grid__col--1-of-3">
				
				<label><?php _e( 'Social Media Image', 'social-rocket' ); ?></label>
				<div class="social-rocket-image-uploader <?php echo $og_image ? 'has-image' : ''; ?>">
					<div class="social-rocket-image-uploader-inner">
						<div class="social-rocket-image-uploader-image-overlay"></div>
						<div class="social-rocket-image-uploader-image-bar">
							<a href="<?php echo $og_image ? get_edit_post_link( $og_image, 'raw' ) : '#'; ?>" class="social-rocket-image-uploader-image-edit" title="Edit" target="_blank">
								<span class="dashicons dashicons-edit"></span>
							</a>
							<a href="#" class="social-rocket-image-uploader-image-remove" title="Remove">
								<span class="dashicons dashicons-no-alt"></span>
							</a>
						</div>
						<div class="social-rocket-image-uploader-image">
							<img src="<?php echo $og_image ? wp_get_attachment_url( $og_image ) : plugin_dir_url( dirname( __FILE__ ) ) . 'img/social-image-placeholder.png'; ?>" />
						</div>
					</div>
					<input type="hidden" class="social-rocket-image-upload-id" name="social_rocket_og_image" value="<?php echo esc_attr( $og_image ); ?>" />
					<button type="button" class="button-secondary social-rocket-image-upload-button"><?php _e( 'Upload Image', 'social-rocket' ); ?></button>
				</div>
				<p class="social-rocket-metabox-description"><?php _e( 'An image optimized for maximum impact on most social networks, including Facebook. Recommended size: 1200 x 630 px.', 'social-rocket' ); ?></p>
				
				<label><i class="fab fa-pinterest"></i>&nbsp;<?php _e( 'Pinterest Image', 'social-rocket' ); ?></label>
				<div class="social-rocket-image-uploader <?php echo $pinterest_image ? 'has-image' : ''; ?>">
					<div class="social-rocket-image-uploader-inner">
						<div class="social-rocket-image-uploader-image-overlay"></div>
						<div class="social-rocket-image-uploader-image-bar">
							<a href="<?php echo $pinterest_image ? get_edit_post_link( $pinterest_image, 'raw' ) : '#'; ?>" class="social-rocket-image-uploader-image-edit" title="Edit" target="_blank">
								<span class="dashicons dashicons-edit"></span>
							</a>
							<a href="#" class="social-rocket-image-uploader-image-remove" title="Remove">
								<span class="dashicons dashicons-no-alt"></span>
							</a>
						</div>
						<div class="social-rocket-image-uploader-image">
							<img src="<?php echo $pinterest_image ? wp_get_attachment_url( $pinterest_image ) : plugin_dir_url( dirname( __FILE__ ) ) . 'img/pinterest-image-placeholder.png'; ?>" />
						</div>
					</div>
					<input type="hidden" class="social-rocket-image-upload-id" name="social_rocket_pinterest_image" value="<?php echo esc_attr( $pinterest_image ); ?>" />
					<button type="button" class="button-secondary social-rocket-image-upload-button"><?php _e( 'Upload Image', 'social-rocket' ); ?></button>
				</div>
				<p class="social-rocket-metabox-description"><?php _e( 'An image optimized for maximum impact on Pinterest. Recommended size: 735 x 1102 px.', 'social-rocket' ); ?></p>
				
				<?php do_action( 'social_rocket_metabox_content_left' ); ?>
				
			</div>
			
			<div class="sr-grid__col sr-grid__col--2-of-3">
				
				<label for="social_rocket_og_title"><?php _e( 'Social Media Title', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_og_title" name="social_rocket_og_title" rows="3"><?php echo esc_attr( $og_title ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_og_title" data-max-chars="60"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 60 - strlen( $og_title ) < 0 ? 'negative' : '' ) . '">' . ( 60 - strlen( $og_title ) ) . '/60</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'Content to populate the open graph "title" meta tag, which is shown when your content is shared to most social networks. If left blank, the post title will be used by default.', 'social-rocket' ); ?></p>
				
				<label for="social_rocket_og_description"><?php _e( 'Social Media Description', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_og_description" name="social_rocket_og_description" rows="3"><?php echo esc_attr( $og_description ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_og_description" data-max-chars="160"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 160 - strlen( $og_description ) < 0 ? 'negative' : '' ) . '">' . ( 160 - strlen( $og_description ) ) . '/160</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'Content to populate the open graph "description" meta tag, which is shown when your content is shared to most social networks.', 'social-rocket' ); ?></p>
				
				<p>&nbsp;</p>
				
				<label for="social_rocket_twitter_message"><i class="fab fa-twitter"></i>&nbsp;<?php _e( 'Custom Tweet', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_twitter_message" name="social_rocket_twitter_message" rows="3"><?php echo esc_attr( $twitter_message ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_twitter_message" data-max-chars="280"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 280 - strlen( $twitter_message ) < 0 ? 'negative' : '' ) . '">' . ( 280 - strlen( $twitter_message ) ) . '/280</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'If this is left blank your post title will be used. Be sure to leave enough room for a link to be added!', 'social-rocket' ); ?></p>
				
				<p>&nbsp;</p>
				
				<label for="social_rocket_pinterest_description"><i class="fab fa-pinterest"></i>&nbsp;<?php _e( 'Pinterest Description', 'social-rocket' ); ?></label>
				<textarea id="social_rocket_pinterest_description" name="social_rocket_pinterest_description" rows="3"><?php echo esc_attr( $pinterest_desc ); ?></textarea>
				<div class="social-rocket-characters-remaining" data-for-textarea="social_rocket_pinterest_description" data-max-chars="500"><?php printf( __( '%s Characters Remaining', 'social-rocket' ), '<span class="chars ' . ( 500 - strlen( $pinterest_desc ) < 0 ? 'negative' : '' ) . '">' . ( 500 - strlen( $pinterest_desc ) ) . '/500</span>' ); ?></div>
				<p class="social-rocket-metabox-description"><?php _e( 'The description that will be used when this post is shared on Pinterest. If left blank, the post title will be used by default.', 'social-rocket' ); ?></p>
				<?php do_action( 'social_rocket_taxonomy_metabox_content_pinterest', $term ); ?>
				
				<p>&nbsp;</p>
				
				<div class="sr-grid">
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<label for="social_rocket_inline_position"><?php _e( 'Inline Buttons Position', 'social-rocket' ); ?></label>
						<select id="social_rocket_inline_position" name="social_rocket_inline_position">
							<option value="" <?php selected( $inline_position, '' ); ?>><?php _e( 'Use default setting', 'social-rocket' ); ?></option>
							<option value="above" <?php selected( $inline_position, 'above' ); ?>><?php _e( 'Above the content', 'social-rocket' ); ?></option>
							<option value="below" <?php selected( $inline_position, 'below' ); ?>><?php _e( 'Below the content', 'social-rocket' ); ?></option>
							<option value="both" <?php selected( $inline_position, 'both' ); ?>><?php _e( 'Both above and below the content', 'social-rocket' ); ?></option>
							<option value="none" <?php selected( $inline_position, 'none' ); ?>><?php _e( 'None/manual placement', 'social-rocket' ); ?></option>
						</select>
					</div>
					<div class="sr-grid__col sr-grid__col--1-of-2">
						<label for="social_rocket_floating_position"><?php _e( 'Floating Buttons Position', 'social-rocket' ); ?></label>
						<select id="social_rocket_floating_position" name="social_rocket_floating_position">
							<option value="" <?php selected( $floating_position, '' ); ?>><?php _e( 'Use default setting', 'social-rocket' ); ?></option>
							<option value="left" <?php selected( $floating_position, 'left' ); ?>><?php _e( 'Left', 'social-rocket' ); ?></option>
							<option value="right" <?php selected( $floating_position, 'right' ); ?>><?php _e( 'Right', 'social-rocket' ); ?></option>
							<option value="top" <?php selected( $floating_position, 'top' ); ?>><?php _e( 'Top', 'social-rocket' ); ?></option>
							<option value="bottom" <?php selected( $floating_position, 'bottom' ); ?>><?php _e( 'Bottom', 'social-rocket' ); ?></option>
							<option value="none" <?php selected( $floating_position, 'none' ); ?>><?php _e( 'None', 'social-rocket' ); ?></option>
						</select>
					</div>
				</div>
				
				<?php do_action( 'social_rocket_taxonomy_metabox_content_right', $term ); ?>
				
			</div>
			
		</div>
		
		<?php
		
		do_action( 'social_rocket_taxonomy_metabox_content_bottom', $term );
		
		?>
		</div>
		</div>
		</div>
		</div>
		<?php
		#endregion taxonomy_metabox_display
	}
	
	
	public function taxonomy_metabox_save() {
	
		if ( ! isset ( $_REQUEST['social_rocket_edited_taxonomy'] ) ) {
			return;
		}
		
		$nonce = $_REQUEST['_social_rocket_metabox'];
		if ( ! wp_verify_nonce( $nonce, 'social_rocket_metabox' ) ) {
			return;
		}

		$termmetas = array(
			'social_rocket_og_description'                       => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_og_description'] ) ),
			'social_rocket_og_image'                             => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_og_image'] ) ),
			'social_rocket_og_title'                             => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_og_title'] ) ),
			'social_rocket_pinterest_description'                => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_pinterest_description'] ) ),
			'social_rocket_pinterest_image'                      => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_pinterest_image'] ) ),
			'social_rocket_twitter_message'                      => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_twitter_message'] ) ),
			'social_rocket_floating_position'                    => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_floating_position'] ) ),
			'social_rocket_inline_position'                      => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_inline_position'] ) ),
		);
		
		$term_id = $_REQUEST['social_rocket_edited_taxonomy'];
		
		foreach ( $termmetas as $key => $value ) {
			if ( $value > '' ) {
				update_term_meta( $term_id, $key, $value );
			} else {
				delete_term_meta( $term_id, $key );
			}
		}
		
		do_action( 'social_rocket_taxonomy_metabox_save', $term_id );
		
	}
	
	
	public function tinymce_register_button( $buttons ) {
		$buttons[] = '|';
		$buttons[] = 'social_rocket_shortcode';
		$buttons[] = 'social_rocket_shortcode_tweet';
		$buttons[] = '|';
		return $buttons;
	}
	
	
	public function tinymce_register_plugin( $plugins ) {
		$plugins['social_rocket_shortcode']       = plugin_dir_url( dirname( __FILE__ ) ) .'js/shortcode.js';
		$plugins['social_rocket_shortcode_tweet'] = plugin_dir_url( dirname( __FILE__ ) ) .'js/shortcode-tweet.js';
		return $plugins;
	}
	
	
	public function tinymce_force_refresh( $version ) {
		$version += 3;
		return $version;
	}
	
	
	public function tools_recalc_all() {
		
		$nonce = $this->_isset( $_POST['nonce'] );
		
		if ( ! $nonce ) {
			wp_die( 'You do not have permissions to do this.', null, array( 'response' => 400 ) );
			return;
		}
		
		if ( ! wp_verify_nonce( $_POST['nonce'], 'social_rocket_recalc_all' ) ) {
			wp_die( 'Nonce expired. Please reload page and try again.', null, array( 'response' => 400 ) );
			return;
		}
		
		$SR = Social_Rocket::get_instance();
		$SR->recalc_all_share_counts();
		
		$response = array(
			'status' => 'success',
		);
	
		echo json_encode( $response );
		wp_die();
	}
	
	
	public function tweet_settings_delete() {
	
		$SR = Social_Rocket::get_instance();
		
		$id = sanitize_text_field( $this->_isset( $_POST['id'] ) );
		
		if ( ! $id ) {
			wp_die( 'ID not found', null, array( 'response' => 400 ) );
			return;
		}
		
		unset( $SR->settings['tweet_settings']['saved_settings'][$id] );
		
		update_option( 'social_rocket_settings', $SR->settings );
		
		$response = array(
			'status' => 'success',
			'id'     => $id,
		);
	
		echo json_encode( $response );
		wp_die();
		
	}
	
	
	public function tweet_settings_load() {
	
		$SR = Social_Rocket::get_instance();
		
		$id = $this->_isset( $_GET['id'] );
		
		if ( ! $id ) {
			wp_die( 'ID not found', null, array( 'response' => 400 ) );
			return;
		}
		
		$response = array(
			'status' => 'success',
			'id'     => $id,
			'data'   => $this->_isset( $SR->settings['tweet_settings']['saved_settings'][$id], array() ),
		);
		
		echo json_encode( $response );
		wp_die();
	}
	
	
	public function tweet_settings_process_post() {
		
		$output = array();
		
		$post = array();
		if ( isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
			$post = $_POST['data'];
		}
		
		$settings = array(
			'name' => array(
				'path'           => array( 'name' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_accent_color' => array(
				'path'           => array( 'accent_color' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_background_color' => array(
				'path'           => array( 'background_color' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_border' => array(
				'path'           => array( 'border' ),
				'type'           => 'whitelist',
				'allowed_values' => array( 'none', 'solid', 'double', 'dashed', 'dotted', 'inset', 'outset', 'groove', 'ridge' ),
			),
			'social_rocket_tweet_border_color' => array(
				'path'           => array( 'border_color' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_border_radius' => array(
				'path'           => array( 'border_radius' ),
				'type'           => 'integer',
			),
			'social_rocket_tweet_border_size' => array(
				'path'           => array( 'border_size' ),
				'type'           => 'integer',
			),
			'social_rocket_tweet_cta_color' => array(
				'path'           => array( 'cta_color' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_cta_position' => array(
				'path'           => array( 'cta_position' ),
				'type'           => 'whitelist',
				'allowed_values' => array( 'left', 'right' ),
			),
			'social_rocket_tweet_cta_text' => array(
				'path'           => array( 'cta_text' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_include_url' => array(
				'path'           => array( 'include_url' ),
				'type'           => 'checkbox',
			),
			'social_rocket_tweet_include_via' => array(
				'path'           => array( 'include_via' ),
				'type'           => 'checkbox',
			),
			'social_rocket_tweet_text_color' => array(
				'path'           => array( 'text_color' ),
				'type'           => 'text',
			),
			'social_rocket_tweet_text_size' => array(
				'path'           => array( 'text_size' ),
				'type'           => 'integer',
			),
		);
		
		foreach ( $settings as $post_key => $setting ) {
			if ( ! isset( $post[ $post_key ] ) ) {
				continue;
			}
			$value = null;
			switch ( $setting['type'] ) {
				case 'checkbox':
					if ( $post[ $post_key ] === '1' ) {
						$value = true;
					} else {
						$value = false;
					}
					break;
				case 'integer':
					$value = intval( $post[ $post_key ] );
					break;
				case 'raw':
					$value = $post[ $post_key ];
					break;
				case 'repeatable':
					if ( is_array( $post[ $post_key ] ) ) {
						$value = array();
						foreach ( $post[ $post_key ] as $array_element ) {
							if ( $array_element > '' ) {
								$value[] = $array_element;
							}
						}
					}
					break;
				case 'text':
					$value = sanitize_text_field( $post[ $post_key ] );
					break;
				case 'whitelist':
					if ( in_array( $post[ $post_key ], $setting['allowed_values'] ) ) {
						$value = sanitize_text_field( $post[ $post_key ] );
					}
					break;
			}
			if ( $value !== null ) {
				$ref = &$output;
				foreach( $setting['path'] as $key ) {
					$ref = &$ref[ $key ];
				}
				$ref = $value;
			}
		}
		
		return $output;
	}
	
	
	public function tweet_settings_save() {
	
		$SR = Social_Rocket::get_instance();
		
		$data = $this->tweet_settings_process_post();
		$id   = uniqid();
		
		foreach ( $data as $key => $value ) {
			$SR->settings['tweet_settings']['saved_settings'][ $id ][ $key ] = $value;
		}
		
		update_option( 'social_rocket_settings', $SR->settings );
		
		$response = array(
			'status' => 'success',
			'id'     => $id,
		);
	
		echo json_encode( $response );
		wp_die();
		
	}
	
	
	public function tweet_settings_update() {
	
		$SR = Social_Rocket::get_instance();
		
		$data = $this->tweet_settings_process_post();
		$id   = $this->_isset( $_POST['id'] );
		
		if ( ! $id ) {
			wp_die( 'ID not found', null, array( 'response' => 400 ) );
			return;
		}
		
		foreach ( $data as $key => $value ) {
			$SR->settings['tweet_settings']['saved_settings'][ $id ][ $key ] = $value;
		}
		
		update_option( 'social_rocket_settings', $SR->settings );
		
		$response = array(
			'status' => 'success',
			'id'     => $id,
		);
	
		echo json_encode( $response );
		wp_die();
		
	}
	
	
	public function user_profile_fields_display( $user ) {
		#region user_profile_fields_display
		
		$facebook = get_user_meta( $user->ID, 'social_rocket_facebook_profile_url', true );
		$twitter  = get_user_meta( $user->ID, 'social_rocket_twitter_username', true );
		
		?>
		<h3><?php _e( 'Social Rocket', 'social-rocket' ); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="social_rocket_facebook_profile_url"><?php _e( 'Facebook Profile URL', 'social-rocket' ); ?></label></th>
				<td>
					<input type="text" name="social_rocket_facebook_profile_url" id="social_rocket_facebook_profile_url" value="<?php echo esc_attr( $facebook ); ?>" class="regular-text" />
					<br /><span class="description"><?php _e( 'Please enter the URL of your Facebook profile.', 'social-rocket' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="social_rocket_twitter_username"><?php _e( 'Twitter Username', 'social-rocket' ); ?></label></th>
				<td>
					<input type="text" name="social_rocket_twitter_username" id="social_rocket_twitter_username" value="<?php echo esc_attr( $twitter ); ?>" class="regular-text" />
					<br /><span class="description"><?php _e( 'Please enter your Twitter username.', 'social-rocket' ); ?></span>
				</td>
			</tr>
		</table>
		<?php
		#endregion user_profile_fields_display
	}
	
	
	public function user_profile_fields_save( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		
		$usermetas = array(
			'social_rocket_twitter_username'     => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_twitter_username'] ) ),
			'social_rocket_facebook_profile_url' => sanitize_text_field( $this->_isset( $_REQUEST['social_rocket_facebook_profile_url'] ) ),
		);
		
		foreach ( $usermetas as $key => $value ) {
			if ( $value > '' ) {
				update_user_meta( $user_id, $key, $value );
			} else {
				delete_user_meta( $user_id, $key );
			}
		}
		
		do_action( 'social_rocket_user_profile_fields_save', $user_id );
		
	}


	public function validate_settings() {
	
		global $wpdb;
		
		if ( class_exists( 'Social_Rocket_Admin_Notices' ) ) {
			
			if ( 
				get_option( '_social_rocket_facebook_invalid_token' ) > '' &&
				! isset( $_POST['social_rocket_save'] ) // make sure we didn't just save
			) {
				if ( ! Social_Rocket_Admin_Notices::has_notice( 'sr_invalid_facebook_token' ) ) {
					$notice_args = array(
						'class' => 'notice-error',
						'content' => sprintf( 
							'<p>' . __( 'Social Rocket received the following error from the Facebook API: "%s".  Please make sure your Facebook Access Token is correct on the <a href="%s">settings page</a>.', 'social-rocket' ) . '</p>' .
							'<p><a href="https://wpsocialrocket.com/support/" target="_blank">' . __( 'I don\'t know what this means', 'social-rocket' ) . '</a></p>' .
							'<p><a href="#" onclick="jQuery(\'#sr_admin_notice_sr_invalid_facebook_token .notice-dismiss\').click();">' . __( 'I fixed it already, leave me alone!', 'social-rocket' ) . '</a></p>',
							get_option( '_social_rocket_facebook_invalid_token' ),
							admin_url( 'admin.php?page=social_rocket_settings#social-extras' )
						),
						'dismissable' => true,
						'dismiss_transient' => 60 * 60 * 24 * 7 * 30, // 30 days
					);
					Social_Rocket_Admin_Notices::add_custom_notice( 'sr_invalid_facebook_token', $notice_args );
				}
			} else {
				if ( get_option( 'sr_admin_notice_sr_invalid_facebook_token' ) !== '' ) {
					Social_Rocket_Admin_Notices::remove_notice( 'sr_invalid_facebook_token' );
				}
			}
		
		}
		
		return true;
	}
	
	
}
