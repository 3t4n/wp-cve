<?php

/**
 *
 * @link       https://mythemeshop.com/plugins/url-shortener/
 * @since      1.0.0
 *
 * @package    MTS_URL_Shortener
 * @subpackage MTS_URL_Shortener/admin
 * @author     MyThemeShop <support-team@mythemeshop.com>
 */
class MTS_URL_Shortener_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $links_table;

	public $groups_table;

	public $clicks_table;

	public $prettylinks_installed = false;

	public $prettylinks_imported = false;

	private $settings_api;

	public $screens = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		global $wpdb;

		$this->settings_api = new MTS_URL_Shortener_Settings;

		$this->links_table = $wpdb->prefix.'short_links';
		$this->groups_table = $wpdb->prefix.'short_link_groups';
		$this->clicks_table = $wpdb->prefix.'short_link_clicks';

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->prettylinks_installed = (bool) get_option( 'prli_options', false );
		$this->prettylinks_imported = (bool) get_option( 'urlshortener_prli_imported', false );

		$this->screen_base = 'short-links';
		$this->screens = array(
			'toplevel_page_url_shortener_links',
			$this->screen_base.'_page_url_shortener_add',
			$this->screen_base.'_page_url_shortener_edit',
			'edit-short_link_category',
			$this->screen_base.'_page_url_shortener_settings',
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();
		if ( ! in_array( $screen->id, $this->screens ) ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/url-shortener-admin.css', array( 'wp-color-picker' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( $screen->id == 'toplevel_page_url_shortener_links' ||
			$screen->id == $this->screen_base.'_page_url_shortener_add' ||
			$screen->id == $this->screen_base.'_page_url_shortener_edit' ) {
			wp_enqueue_script( $this->plugin_name.'-clipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/url-shortener-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/url-shortener-admin-notice.js', array( 'jquery' ), $this->version, true );
	}

	public function init_settings() {
		// set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings
		$this->settings_api->init_settings();

	}

	public function init_meta_boxes() {
		// initialize metabox
		add_meta_box( 'shortlinksubmitdiv', __( 'Save', 'mts-url-shortener' ), array( $this, 'link_submit_meta_box' ), 'short_link', 'side', 'core' );
		add_meta_box( 'shortlinkcategorydiv', __( 'Categories', 'mts-url-shortener' ), array( $this, 'link_categories_meta_box' ), 'short_link', 'side', 'default');
		add_meta_box( 'shortlinkreplacements', __( 'Replacements', 'mts-url-shortener' ), array( $this, 'link_replacements_meta_box' ), 'short_link', 'normal', 'low');
	}

	public function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'urlshortener_defaults',
				'title' => __( 'Link Defaults', 'mts-url-shortener' )
			),
		);
		if ( $this->prettylinks_installed ) {
			$sections[] = array(
				'id'    => 'import',
				'title' => __( 'Import', 'mts-url-shortener' )
			);
		}
		return $sections;
	}

	public function get_editor_sections() {
		$sections = array(
			array(
				'id'    => 'urlshortener_basic',
				'title' => __( 'Redirection', 'mts-url-shortener' )
			),
			array(
				'id'    => 'urlshortener_advanced',
				'title' => __( 'Advanced', 'mts-url-shortener' )
			),
		);
		return $sections;
	}


	public function get_editor_fields() {
		$settings_fields = array(
			'urlshortener_basic' => array(
				array(
					'name'              => 'link_name',
					'label'             => __( 'Short Link', 'mts-url-shortener' ),
					'before_field'      => '<span>'.trailingslashit( get_bloginfo( 'url' ) ).'</span>',
					'desc'              => __( 'Short link slug on your site', 'mts-url-shortener' ),
					'placeholder'       => __( 'awesome-blogging-tool', 'mts-url-shortener' ),
					'type'              => 'text',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'link_url',
					'label'             => __( 'Redirect To', 'mts-url-shortener' ),
					'desc'              => __( 'Redirect short link to this URL', 'mts-url-shortener' ),
					'placeholder'       => __( 'http://www.example.com/awesome-blogging-tool?ref=11', 'mts-url-shortener' ),
					'type'              => 'text',
					'size'				=> 'large',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'    => 'link_redirection_method',
					'label'   => __( 'Redirection Method', 'mts-url-shortener' ),
					'desc'    => __( 'Choose redirection method for this link.', 'mts-url-shortener' ),
					'type'    => 'select',
					'default' => '302',
					'options' => array(
						'302' 		 => __('Header: 302 Temporary', 'mts-url-shortener'),
						'307' 		 => __('Header: 307 Temporary', 'mts-url-shortener'),
						'301' 		 => __('Header: 301 Permanent', 'mts-url-shortener'),
					)
				),
			),


			'urlshortener_advanced' => array(
				array(
					'name'              => 'link_attr_title',
					'label'             => __( 'Title', 'mts-url-shortener' ),
					'desc'              => __( 'Default <code>title</code> attribute for the link.', 'mts-url-shortener' ),
					'type'              => 'text',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'link_anchor',
					'label'             => __( 'Anchor Text', 'mts-url-shortener' ),
					'desc'              => __( 'Default anchor text for the link when shortcode is used without link text.', 'mts-url-shortener' ),
					'type'              => 'text',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'  => 'link_newwindow',
					'label' => __( 'New Window', 'mts-url-shortener' ),
					'desc'  => __( 'Open link in new window or tab.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'  => 'link_nofollow',
					'label' => __( 'Nofollow Link', 'mts-url-shortener' ),
					'desc'  => __( 'Add <code>rel="nofollow"</code> attribute for this link to prevent search engines from following it.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'  => 'link_forward_parameters',
					'label' => __( 'Forward Parameters', 'mts-url-shortener' ),
					'desc'  => __( 'Check this box to forward GET parameters from the short link to the destination URL.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'  => 'link_remove_referrer',
					'label' => __( 'Remove Referrer', 'mts-url-shortener' ),
					'desc'  => __( 'Adds <code>rel="noreferrer"</code> attribute to the link and the <code>no-referrer</code> meta tag to the short link redirection page.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
			),

		);
		if ( $this->prettylinks_installed ) {
			$settings_fields['import'] = array(
				array(
					'name'    => 'import_info',
					'desc'    => __( 'From here you can import links from the Pretty Link plugin.', 'mts-url-shortener' ),
					'type'    => 'info',
				),
			);
		}
		return $settings_fields;
	}

	public function get_title_fields() {
		$settings_fields = array(
			'link_title' => array(
				array(
					'name'    => 'title_info',
					'desc'    => __( 'Here you can insert an optional title and description for this link.', 'mts-url-shortener' ),
					'type'    => 'info',
				),
				array(
					'name'              => 'link_title',
					'label'             => __( 'Link Title', 'mts-url-shortener' ),
					'type'              => 'text',
					'size'              => 'large',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'              => 'link_description',
					'label'             => __( 'Link Description', 'mts-url-shortener' ),
					'type'              => 'textarea',
					'size'              => 'large',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field'
				),
				array(
					'name'    => 'show_title',
					'label'   => __( 'Show Title', 'mts-url-shortener' ),
					'desc'    => __( 'Use the title or the description for the <code>title</code> attribute of this link. It may show up in a tooltip when hovering over the link in desktop browsers.', 'mts-url-shortener' ),
					'type'    => 'radio',
					'default' => '',
					'options' => array(
						'' => __('None', 'mts-url-shortener'),
						'title'  => __('Title', 'mts-url-shortener'),
						'description'  => __('Description', 'mts-url-shortener'),
					)
				),
			)
		);
		return $settings_fields;
	}

	public function get_replacement_fields() {
		$settings_fields = array(
			'link_replacements' => array(
				array(
					'name'    => 'link_replace_info',
					'desc'    => __( 'With these fields you can automatically insert the short link in posts and pages. These will replace existing links or replace keywords with links on the fly when the content is displayed to the visitor.', 'mts-url-shortener' ),
					'type'    => 'info',
				),
				array(
					'name'              => 'link_replace_url',
					'label'             => __( 'Replace URL', 'mts-url-shortener' ),
					'desc'              => __( 'Replace the URL of existing links in pages and posts with this short link.', 'mts-url-shortener' ),
					'placeholder'       => __( 'http://www.example.com/awesome-blogging-tool?ref=11', 'mts-url-shortener' ),
					'type'              => 'repeatable_text',
					'default'           => '',
				),
				array(
					'name'              => 'link_replace_keyword',
					'label'             => __( 'Replace Keywords', 'mts-url-shortener' ),
					'desc'              => __( 'Replace plain text keywords in pages and posts with this short link.', 'mts-url-shortener' ),

					'placeholder'       => __( 'awesome tool', 'mts-url-shortener' ),
					'type'              => 'repeatable_text',
					'default'           => '',
				),
			)
		);
		return $settings_fields;
	}

	public function get_settings_fields() {
		$pages = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
		$template_options = array( '' => __('None', 'mts-url-shortener') );
		foreach ( $pages as $page ) {
			$template_options[$page->ID] = $page->post_title;//.' (ID '.$page->ID.')';
		}
		$settings_fields = array(
			'urlshortener_defaults' => array(
				array(
					'name'    => 'defaults_info',
					'desc'    => __( 'Here you can select which options should be selected by default when creating a new short link. These options can be set for each link individually.', 'mts-url-shortener' ),
					'type'    => 'info',
				),
				array(
					'name'    => 'link_redirection_method',
					'label'   => __( 'Redirection Method', 'mts-url-shortener' ),
					'desc'    => __( 'Choose default redirection method for new links.', 'mts-url-shortener' ),
					'type'    => 'select',
					'default' => '302',
					'options' => array(
						'302'        => __('Header: 302 Temporary', 'mts-url-shortener'),
						'307'        => __('Header: 307 Temporary', 'mts-url-shortener'),
						'301'        => __('Header: 301 Permanent', 'mts-url-shortener'),
					)
				),
				array(
					'name'  => 'link_newwindow',
					'label' => __( 'New Window', 'mts-url-shortener' ),
					'desc'  => __( 'Open link in new window or tab.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'  => 'link_nofollow',
					'label' => __( 'Nofollow Link', 'mts-url-shortener' ),
					'desc'  => __( 'Add <code>rel="nofollow"</code> attribute for the links to prevent search engines from following it.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'  => 'link_forward_parameters',
					'label' => __( 'Forward Parameters', 'mts-url-shortener' ),
					'desc'  => __( 'Check this box to forward GET parameters from the short link to the destination URL.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'  => 'link_remove_referrer',
					'label' => __( 'Remove Referrer', 'mts-url-shortener' ),
					'desc'  => __( 'Adds <code>rel="noreferrer"</code> attribute to the link and the <code>no-referrer</code> meta tag to the short link redirection page.', 'mts-url-shortener' ),
					'type'  => 'checkbox'
				),
				array(
					'name'    => 'preview_tool',
					'label'   => __( 'Link Preview', 'mts-url-shortener' ),
					'desc'    => __( 'Choose the default link preview type selected.', 'mts-url-shortener' ),
					'type'    => 'select',
					'default' => 'URL',
					'options' => array(
						'url'        => __('URL', 'mts-url-shortener'),
						'shortcode'  => __('Shortcode', 'mts-url-shortener'),
						'html'       => __('HTML Link', 'mts-url-shortener'),
					)
				),
			),

		);
		return $settings_fields;
	}

	public function add_admin_pages() {
		// add top level menu page

		add_menu_page(
			__('Short Links', 'mts-url-shortener'),
			__('Short Links', 'mts-url-shortener'),
			'manage_options',
			'url_shortener_links',
			array( $this, 'links_page_content' ),
			'dashicons-admin-links',
			110 // menu item position
		);

		add_submenu_page(
			'url_shortener_links',
			__('Add Link', 'mts-url-shortener'),
			__('Add Link', 'mts-url-shortener'),
			'manage_options',
			'url_shortener_add',
			array( $this, 'link_edit_page_content' )
		);

		add_submenu_page(
			'url_shortener_links',
			__('Short Link Categories', 'mts-url-shortener'),
			__('Categories', 'mts-url-shortener'),
			'manage_options',
			'edit-tags.php?taxonomy=short_link_category',
			null
		);

		add_submenu_page(
			'url_shortener_links',
			__('URL Shortener Settings', 'mts-url-shortener'),
			__('Settings', 'mts-url-shortener'),
			'manage_options',
			'url_shortener_settings',
			array( $this, 'settings_page_content' )
		);

		// This one has to be in the last position because it's hidden with CSS :last-child
		add_submenu_page(
			'url_shortener_links',
			__('Edit Link', 'mts-url-shortener'),
			__('Edit Link', 'mts-url-shortener'),
			'manage_options',
			'url_shortener_edit',
			array( $this, 'link_edit_page_content' )
		);

	}

	/**
	 * Menu fix: set correct 'current' class for link category editing pages
	 *
	 * @param  [type] $parent_file [description]
	 * @return [type]              [description]
	 */
	public function modify_admin_menu( $parent_file ) {
		global $submenu_file; //echo 'parent file '.$parent_file.' submenu file '.$submenu_file;
		if ( $submenu_file == 'edit-tags.php?taxonomy=short_link_category' ) {
			$parent_file = 'url_shortener_links';
		} elseif ( isset( $_GET['page'] ) && $_GET['page'] == 'url_shortener_edit' ) {
			$parent_file = 'url_shortener_links';
			$submenu_file = 'url_shortener_links';
		}

		return $parent_file;
	}

	public function screen_options() {
		global $linksListTable;
		include_once 'class-url-shortener-list-table.php';
		$option = 'per_page';
		$args = array(
			'label' => __( 'Links per page', 'mts-url-shortener' ),
			'default' => 10,
			'option' => 'short_links_per_page'
		);
		add_screen_option( $option, $args );

		// Create an instance of our table class, later used in url-shortener-settings.php
		$linksListTable = new Short_Links_List_Table();
	}

	public function save_screen_options( $status, $option, $value ) {
		if ( $option == 'short_links_per_page' ) {
			return $value; // Save it as user meta
		}
		return false;
	}

	public function settings_page_content() {
		include_once 'views/url-shortener-settings.php';
	}

	public function links_page_content() {
		include_once 'views/url-shortener-links.php';
	}

	public function admin_head() {
		global $hook_suffix;
		if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'short_link_category' && ( $hook_suffix == 'edit-tags.php' || $hook_suffix == 'term.php' ) ) { ?>
			<style type="text/css">
			.term-name-wrap p,
			.term-slug-wrap p,
			.term-description-wrap p {
				display: none;
			}
			</style>
			<?php
		} // Output this on all admin pages ?>
		<style type="text/css">
		/* Hide "edit link" menu item */
		#toplevel_page_url_shortener_links .wp-submenu li:last-child {
			display: none;
		}
		</style>
		<?php
	}

	public function link_edit_page_content() {
		$link = $this->get_default_link_to_edit();
		$link_id = 0;
		if ( ! empty ( $_GET['link_id'] ) ) {
			$link_id = absint( $_GET['link_id'] );
			$link = $this->get_link( $link_id );
		}
		$editing_link = false;
		$form_action = admin_url( 'admin.php?page=url_shortener_edit' );
		if ( ! empty( $link_id ) ) {
			$title = __( 'Edit Link', 'mts-url-shortener' );
			$heading = sprintf( __( '<a href="%s">Links</a> / Edit Link', 'mts-url-shortener' ), 'link-manager.php' );
			$submit_text = __( 'Update Link', 'mts-url-shortener' );
			$form_name = 'editshortlink';
			$form_action = add_query_arg( 'link_id', $link_id, $form_action );
			//$nonce_action = 'update-short_link_' . $link_id;
			$nonce_action = 'url_shortener_action';
			$editing_link = true;
		} else {
			$title = __( 'Add New Link', 'mts-url-shortener' );
			$heading = sprintf( __( '<a href="%s">Links</a> / Add New Link', 'mts-url-shortener' ), 'link-manager.php' );
			$submit_text = __( 'Add Link', 'mts-url-shortener' );
			$form_name = 'addshortlink';
			//$nonce_action = 'add-short_link';
			$nonce_action = 'url_shortener_action';
		}
		include_once 'views/url-shortener-edit.php';
	}
	public function do_add_meta_boxes() {
		wp_enqueue_script('postbox');
		wp_enqueue_script('wp-lists');
		add_thickbox();

		$link = $this->get_default_link_to_edit();
		$link_id = 0;
		if ( ! empty ( $_GET['link_id'] ) ) {
			$link_id = absint( $_GET['link_id'] );
			$link = $this->get_link( $link_id );
		}

		$this->editor_api = new MTS_URL_Shortener_Settings;
		// set the settings
		$this->editor_api->set_sections( $this->get_editor_sections() );
		$this->editor_api->set_fields( $this->get_editor_fields() );

		// initialize settings
		$this->editor_api->init_editor( $link );

		add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );

		do_action( 'add_meta_boxes', 'short_link', $link );

		do_action( 'do_meta_boxes', 'short_link', 'normal', $link );
		do_action( 'do_meta_boxes', 'short_link', 'advanced', $link );
		do_action( 'do_meta_boxes', 'short_link', 'side', $link );

		global $wp_meta_boxes;
		$wp_meta_boxes[$this->screen_base.'_page_url_shortener_add'] = $wp_meta_boxes[$this->screen_base.'_page_url_shortener_edit'] = $wp_meta_boxes['short_link'];
	}

	public function fix_screenopts( $result, $option ) {
		if ( $option == 'screen_layout_'.$this->screen_base.'_page_url_shortener_add' || $option == 'screen_layout_'.$this->screen_base.'_page_url_shortener_edit' ) {
			$result = get_user_option( 'screen_layout_short_link' );
		} elseif ( $option == 'metaboxhidden_'.$this->screen_base.'_page_url_shortener_add' || $option == 'metaboxhidden_'.$this->screen_base.'_page_url_shortener_edit' ) {
			$result = get_user_option( 'metaboxhidden_short_link' );
		}
		return $result;
	}

	public function links_stats_page_content() {
		include_once 'views/url-shortener-stats.php';
	}

	public function get_default_link_to_edit() {
		$link = new stdClass;
		if ( isset( $_GET['linkurl'] ) )
			$link->link_url = esc_url( wp_unslash( $_GET['linkurl'] ) );
		else
			$link->link_url = '';
		if ( isset( $_GET['name'] ) )
			$link->link_name = esc_attr( wp_unslash( $_GET['name'] ) );
		else
			$link->link_name = '';

		$default_props = array(
			'link_id' => 0,
			'link_owner' => get_current_user_id(),
			'link_order' => 0,
			'link_url' => '',
			'link_name' => '',
			'link_anchor' => '',
			'link_title' => '',
			'link_image' => '',
			'link_description' => '',
			'link_status' => 'publish',
			'link_created' => current_time( 'mysql' ),
			'link_updated' => current_time( 'mysql' ),
			'link_attr_target' => '',
			'link_attr_rel' => '',
			'link_attr_title' => '',
			'link_notes' => '',
			'link_redirection_method' => '302',
			'link_css' => '',
			'link_hover_css' => '',
		);

		$defaults = get_option( 'urlshortener_defaults', array() );
		if ( ! is_array( $defaults ) )
			$defaults = array();

		$default_props = array_merge( $default_props, $defaults );

		foreach ($default_props as $key => $value) {
			if ( ! isset( $link->$key ) ) {
				$link->$key = $value;
			}
		}

		return $link;
	}

	public function insert_link( $linkarr ) {
		global $wpdb;
		$user_id = get_current_user_id();
		$defaults = array(
			'link_owner' => $user_id,
			'link_order' => 0,
			'link_url' => '',
			'link_name' => '',
			'link_anchor' => '',
			'link_title' => '',
			'link_image' => '',
			'link_description' => '',
			'link_status' => 'publish',
			'link_created' => current_time( 'mysql' ),
			'link_updated' => current_time( 'mysql' ),
			'link_attr_target' => '',
			'link_attr_rel' => '',
			'link_attr_title' => '',
			'link_notes' => '',
			'link_redirection_method' => '302',
			'link_attributes' => '',
			'link_forward_parameters' => '',
			'link_remove_referrer' => '',
			'link_css' => '',
			'link_hover_css' => '',
		);
		$linkarr = wp_parse_args($linkarr, $defaults);

		$wpdb->insert(
			$wpdb->prefix.'short_links',
			$linkarr,
			array(
				'%d', // 'link_owner'
				'%d', // 'link_order'
				'%s', // 'link_url'
				'%s', // 'link_name'
				'%s', // 'link_anchor'
				'%s', // 'link_title'
				'%s', // 'link_image'
				'%s', // 'link_description'
				'%s', // 'link_status'
				'%s', // 'link_created'
				'%s', // 'link_updated'
				'%s', // 'link_attr_target'
				'%s', // 'link_attr_rel'
				'%s', // 'link_attr_title'
				'%s', // 'link_notes'
				'%s', // 'link_redirection_method'
				'%s', // 'link_attributes'
				'%d', // 'link_forward_parameters'
				'%d', // 'link_remove_referrer'
				'%s', // 'link_css'
				'%s', // 'link_hover_css'
			 )
		);
		return $wpdb->insert_id;
	}

	public function update_link( $linkarr, $wp_error = false ) {
		global $wpdb;
		// $linkarr should contain 'id' or 'name' value
		if ( empty( $linkarr['link_id'] ) && empty( $linkarr['link_name'] ) ) {
			if ( $wp_error )
				return new WP_Error( 'invalid_link', __( 'A link ID or slug must be specified.', 'mts-url-shortener' ) );
			return 0;
		}

		// First, get all of the original fields.
		$link = array();
		if ( empty( $linkarr['link_id'] ) && ! empty( $linkarr['link_name'] ) ) {
			$link = self::get_link_by_slug( $linkarr['link_name'], ARRAY_A );
		} else {
			$link = self::get_link( $linkarr['link_id'], ARRAY_A );
		}

		if ( empty( $link ) ) {
			if ( $wp_error )
				return new WP_Error( 'invalid_link', __( 'Invalid link ID.', 'mts-url-shortener' ) );
			return 0;
		}

		$linkarr['link_updated'] = current_time( 'mysql' );
		$new_linkarr = array_merge( $link, $linkarr );

		return $wpdb->update(
			$wpdb->prefix.'short_links',
			$new_linkarr,
			array( 'link_id' => $link['link_id'] )
		);
	}

	/**
	 * Set link status to 'trash' or delete link directly
	 * @param  int  $link_id      	 [description]
	 * @param  boolean $force_delete [description]
	 * @return bool                	 [description]
	 */
	public static function delete_link( $link_id, $force_delete = false ) {
		global $wpdb;
		if ( $force_delete ) {
			do_action( 'delete_short_link', $link_id );
			wp_delete_object_term_relationships( $link_id, 'short_link_category' );
			self::delete_link_replacements( $link_id );
			$wpdb->delete( $wpdb->prefix.'short_links', array( 'link_id' => $link_id ), array( '%d' ) );
			/**
			 * Fires after a link has been deleted.
			 *
			 * @since 1.0.0
			 *
			 * @param int $link_id ID of the deleted link.
			 */
			do_action( 'deleted_short_link', $link_id );
		} else {
			self::set_link_status( $link_id, 'trash' );
		}
		return true;
	}

	public static function get_link( $link_id, $output = OBJECT ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}short_links WHERE link_id = %d", $link_id );
		return $wpdb->get_row( $query, $output );
	}

	public static function get_link_by_slug( $slug, $output = OBJECT ) {
		global $wpdb;
		$query = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}short_links WHERE link_name = %s", $slug );
		return $wpdb->get_row( $query, $output );
	}

	public static function set_link_status( $link_id, $status ) {
		self::update_link( array( 'link_id' => $link_id, 'link_status' => $status ) );
		return true;
	}

	/**
	 * Query links. Todo: query by category
	 * @param  array $args [description]
	 * @return array       Query results
	 */
	public static function get_links( $args, $output = OBJECT_K ) {
		global $wpdb;
		$defaults = array(
			'link_owner' => 0,
			'link_status' => 'publish',
			'link_redirection_method' => '',
			'search' => '',
			'limit' => 10,
			'offset' => 0,
			'cat' => '',
			'orderby' => 'link_created',
			'order' => 'DESC',
			'fields' => ''
		);
		$args = wp_parse_args( $args, $defaults );

		$where = 'WHERE 1 = 1';
		if ( ! empty( $args['link_owner'] ) ) {
			$where .= $wpdb->prepare( ' AND link_owner = %d', $args['link_owner'] );
		}
		if ( ! empty( $args['link_status'] ) ) {
			$where .= $wpdb->prepare( ' AND link_status = %s', $args['link_status'] );
		}
		if ( ! empty( $args['link_redirection_method'] ) ) {
			$where .= $wpdb->prepare( ' AND link_redirection_method = %s', $args['link_redirection_method'] );
		}

		if ( ! empty( $args['search'] ) ) {
			$where .= $wpdb->prepare( ' AND link_name LIKE %s', '%' . $wpdb->esc_like( $args['search'] ) . '%' );
		}

		// Link Category
		$tax_query = array();
		$args['cat'] = preg_replace( '|[^0-9,-]|', '', $args['cat'] );
		// If querystring 'cat' is an array, implode it.
		if ( is_array( $args['cat'] ) ) {
			$args['cat'] = implode( ',', $args['cat'] );
		}
		// Category stuff
		if ( ! empty( $args['cat'] ) ) {
			$cat_in = $cat_not_in = array();
			$cat_array = preg_split( '/[,\s]+/', urldecode( $args['cat'] ) );
			$cat_array = array_map( 'intval', $cat_array );
			$args['cat'] = implode( ',', $cat_array );
			foreach ( $cat_array as $cat ) {
				if ( $cat > 0 )
					$cat_in[] = $cat;
				elseif ( $cat < 0 )
					$cat_not_in[] = abs( $cat );
			}
			if ( ! empty( $cat_in ) ) {
				$tax_query[] = array(
					'taxonomy' => 'short_link_category',
					'terms' => $cat_in,
					'field' => 'term_id',
					'include_children' => true
				);
			}
			if ( ! empty( $cat_not_in ) ) {
				$tax_query[] = array(
					'taxonomy' => 'short_link_category',
					'terms' => $cat_not_in,
					'field' => 'term_id',
					'operator' => 'NOT IN',
					'include_children' => true
				);
			}
			unset( $cat_array, $cat_in, $cat_not_in );
		}
		$the_tax_query = new WP_Tax_Query( $tax_query );
		$tax_sql = $the_tax_query->get_sql( $wpdb->prefix.'short_links', 'link_id' );
		$join = '';
		if ( ! empty( $tax_sql['where'] ) ) {
			$where .= $tax_sql['where'];
			if ( ! empty( $tax_sql['join'] ) ) {
				$join = $tax_sql['join'];
			}
		}

		$order = '';
		$allowed_orderby = array( 'link_name', 'link_url', 'link_created' );
		if ( ! empty( $args['orderby'] ) && in_array( $args['orderby'], $allowed_orderby ) ) {
			$order .= sprintf( ' ORDER BY %s', $args['orderby'] );
			if ( ! empty( $args['order'] ) &&  strtolower( $args['order'] ) == 'asc' ) {
				$order .= ' ASC';
			} else {
				$order .= ' DESC';
			}
		}

		$limit = '';
		if ( $args['limit'] > 0 ) {
			$limit = $wpdb->prepare( ' LIMIT %d', $args['limit'] );
		}

		$offset = '';
		if ( ! empty( $args['offset'] ) ) {
			$offset = $wpdb->prepare( ' OFFSET %d', $args['offset'] );
		}
		if ( ! $args['fields'] ) {
			$query = "SELECT * FROM {$wpdb->prefix}short_links $join $where $order $limit $offset";
		} elseif ( $args['fields'] == 'ids' ) {
			$query = "SELECT link_id FROM {$wpdb->prefix}short_links $join $where $order $limit $offset";
		}

		//echo $query;
		return $wpdb->get_results( $query, $output );
	}

	public static function get_link_click_count( $link_id ) {
		global $wpdb;
		$link_id = absint( $link_id );
		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}short_link_clicks WHERE link_id = $link_id" );
	}

	/**
	 * Set link to 'publish' status
	 *
	 * @param  int $link_id
	 * @return bool
	 */
	public function publish_link( $link_id ) {
		$this->set_link_status( $link_id, 'publish' );

		return true;
	}

	/**
	 * Set link to 'draft' status
	 * @param  int $link_id
	 * @return bool
	 */
	public function unpublish_link( $link_id ) {
		$this->set_link_status( $link_id, 'draft' );

		return true;
	}

	/**
	 * Processes actions: add, edit, delete, activate, deactivate,
	 * And bulk delete, activate, deactivate
	 * @return [type] [description]
	 */
	public function process_actions() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}
		if ( $_GET['page'] != 'url_shortener_links' && $_GET['page'] != 'url_shortener_edit' ) {
			return;
		}
		if ( ! isset( $_REQUEST['action'] ) ) {
			return;
		}
		if ( $_REQUEST['action'] == -1 && isset( $_REQUEST['action2'] ) && $_REQUEST['action2'] != -1 ) {
			$_REQUEST['action'] = $_REQUEST['action2'];
		}
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'url_shortener_action' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		switch ( $_REQUEST['action'] ) {
			case 'add_short_link':
				$id = $this->action_insert_link();

				if ( ! $id ) {
					wp_die( __('An unknown error occurred.', 'mts-url-shortener') );
				} elseif ( is_wp_error( $id ) ) {
					wp_die( $id->get_error_message() );
				} elseif ( $id ) {
					wp_redirect( admin_url( 'admin.php?page=url_shortener_edit&added=1&link_id='.$id ) );
				}

				exit();
			break;

			case 'edit_short_link':
				$edited = $this->action_update_link();

				if ( is_wp_error( $edited ) ) {
					wp_die( $edited->get_error_message() );
				}

			break;

			case 'delete_short_link':
				if ( empty( $_GET['link_id'] ) ) {
					wp_die( __( 'Link ID must be specified.', 'mts-url-shortener' ) );
				}
				$this->delete_link( absint( $_GET['link_id'] ), true);
			break;

			case 'bulk_delete_short_links':

				if ( empty( $_POST['link'] ) || ! is_array( $_POST['link'] ) ) {
					return;
				}
				foreach ($_POST['link'] as $link_id) {
					$this->delete_link( absint( $link_id ), true);
				}

			break;

		}
	}

	public function action_insert_link() {
		$link = array();

		// Short link
		if ( isset( $_POST['link_name'] ) && ! empty( $_POST['link_name'] ) ) {
			$link['link_name'] = esc_attr( wp_unslash( $_POST['link_name'] ) );
		} else {
			return new WP_Error( 'missing_slug', __( 'A link slug must be specified.', 'mts-url-shortener' ) );
		}

		// Redirect to URL
		if ( isset( $_POST['link_url'] ) && ! empty( $_POST['link_url'] ) ) {
			$link['link_url'] = esc_url_raw( wp_unslash( $_POST['link_url'] ) );
		} else {
			return new WP_Error( 'missing_url', __( 'A destination URL must be specified.', 'mts-url-shortener' ) );
		}

		// Redirection Method
		if ( isset( $_POST['link_redirection_method'] ) && ! empty( $_POST['link_redirection_method'] ) ) {
			$link['link_redirection_method'] = esc_attr( wp_unslash( $_POST['link_redirection_method'] ) );
		}

		// --- Advanced Tab ---

		// Nofollow
		if ( isset( $_POST['link_nofollow'] ) && $_POST['link_nofollow'] == 'on' ) {
			$link['link_attr_rel'] = 'nofollow';
		}

		// New Window
		if ( isset( $_POST['link_newwindow'] ) && $_POST['link_newwindow'] == 'on' ) {
			$link['link_attr_target'] = '_blank';
		}

		// Forward Parameters
		if ( isset( $_POST['link_forward_parameters'] ) && $_POST['link_forward_parameters'] == 'on' ) {
			$link['link_forward_parameters'] = 1;
		}

		if ( isset( $_POST['link_remove_referrer'] ) && $_POST['link_remove_referrer'] == 'on' ) {
			$link['link_remove_referrer'] = 1;
		}


		// --- Anchor text & title attribute ---
		if ( isset( $_POST['link_anchor'] ) ) {
			$link['link_anchor'] = wp_unslash( $_POST['link_anchor'] );
		}
		if ( isset( $_POST['link_attr_title'] ) ) {
			$link['link_attr_title'] = wp_unslash( $_POST['link_attr_title'] );
		}

		$id = $this->insert_link( $link );

		// --- Short Link Categories ---
		if ( $id && isset( $_POST['short_link_category'] ) && is_array( $_POST['short_link_category'] ) ) {
			$cats = $_POST['short_link_category'];
			$this->set_link_cats( $id, $cats );
		}

		// --- Replacements ---
		// Base replacement: the short URL itself
		$replacements = array(
			array(
				'replace_key' => trailingslashit( get_bloginfo( 'url' ) ) . $link['link_name'],
				'type' => 'link',
				'link_id' => $id
			)
		);
		// Link replacements
		if ( isset( $_POST['link_replace_url'] ) && is_array( $_POST['link_replace_url'] ) ) {
			foreach ( $_POST['link_replace_url'] as $k => $replacement) {
				$replacements[] = array(
					'replace_key' => $replacement,
					'type' => 'link',
					'link_id' => $id
				);
			}
		}
		// Keyword replacements
		if ( isset( $_POST['link_replace_keyword'] ) && is_array( $_POST['link_replace_keyword'] ) ) {
			foreach ( $_POST['link_replace_keyword'] as $k => $replacement) {
				$replacements[] = array(
					'replace_key' => stripslashes( $replacement ),
					'type' => 'keyword',
					'link_id' => $id
				);
			}
		}
		self::set_link_replacements( $id, $replacements );

		return $id;
	}

	public function action_update_link() {
		$link = array();

		if ( isset( $_POST['link_id'] ) && ! empty( $_POST['link_id'] ) ) {
			$link['link_id'] = absint( $_POST['link_id'] );
		} else {
			return new WP_Error( 'missing_id', __( 'No link ID specified.', 'mts-url-shortener' ) );
		}

		// Short link
		if ( isset( $_POST['link_name'] ) && ! empty( $_POST['link_name'] ) ) {
			$link['link_name'] = esc_attr( wp_unslash( $_POST['link_name'] ) );
		} else {
			return new WP_Error( 'missing_slug', __( 'A link slug must be specified.', 'mts-url-shortener' ) );
		}

		// Redirect to URL
		if ( isset( $_POST['link_url'] ) && ! empty( $_POST['link_url'] ) ) {
			$link['link_url'] = esc_url_raw( wp_unslash( $_POST['link_url'] ) );
		} else {
			return new WP_Error( 'missing_url', __( 'A destination URL must be specified.', 'mts-url-shortener' ) );
		}

		// Redirection Method
		if ( isset( $_POST['link_redirection_method'] ) ) {
			$link['link_redirection_method'] = esc_attr( wp_unslash( $_POST['link_redirection_method'] ) );
		}

		// --- Advanced Tab ---

		// Nofollow
		if ( isset( $_POST['link_nofollow'] ) ) {
			if ( $_POST['link_nofollow'] == 'on' ) {
				$link['link_attr_rel'] = 'nofollow';
			} else {
				$link['link_attr_rel'] = '';
			}
		}
		// New Window
		if ( isset( $_POST['link_newwindow'] ) ) {
			if ( $_POST['link_newwindow'] == 'on' ) {
				$link['link_attr_target'] = '_blank';
			} else {
				$link['link_attr_target'] = '';
			}
		}

		// Forward Parameters
		if ( isset( $_POST['link_forward_parameters'] ) ) {
			if ( $_POST['link_forward_parameters'] == 'on' ) {
				$link['link_forward_parameters'] = 1;
			} else {
				$link['link_forward_parameters'] = 0;
			}
		}

		if ( isset( $_POST['link_remove_referrer'] ) ) {
			if ( $_POST['link_remove_referrer'] == 'on' ) {
				$link['link_remove_referrer'] = 1;
			} else {
				$link['link_remove_referrer'] = 0;
			}
		}


		// --- Anchor text & title attribute ---
		if ( isset( $_POST['link_anchor'] ) ) {
			$link['link_anchor'] = wp_unslash( $_POST['link_anchor'] );
		}
		if ( isset( $_POST['link_attr_title'] ) ) {
			$link['link_attr_title'] = wp_unslash( $_POST['link_attr_title'] );
		}

		// --- Replacements ---
		// Base replacement: the short URL itself
		$replacements = array(
			array(
				'replace_key' => trailingslashit( get_bloginfo( 'url' ) ) . $link['link_name'],
				'type' => 'link',
				'link_id' => $link['link_id']
			)
		);
		// Link replacements
		if ( isset( $_POST['link_replace_url'] ) && is_array( $_POST['link_replace_url'] ) ) {
			foreach ( $_POST['link_replace_url'] as $k => $replacement) {
				$replacements[] = array(
					'replace_key' => $replacement,
					'type' => 'link',
					'link_id' => $link['link_id']
				);
			}
		}
		// Keyword replacements
		if ( isset( $_POST['link_replace_keyword'] ) && is_array( $_POST['link_replace_keyword'] ) ) {
			foreach ( $_POST['link_replace_keyword'] as $k => $replacement) {
				$replacements[] = array(
					'replace_key' => stripslashes( $replacement ),
					'type' => 'keyword',
					'link_id' => $link['link_id']
				);
			}
		}
		self::set_link_replacements( $link['link_id'], $replacements );

		// --- Short Link Categories ---
		if ( ! empty( $_POST['short_link_category'] ) ) {
			$cats = $_POST['short_link_category'];
			if ( ! is_array( $cats ) ) {
				$cats = array( $cats );
			}
			$this->set_link_cats( $link['link_id'], $cats );
		} else {
			$this->set_link_cats( $link['link_id'], array() );
		}

		return $this->update_link( $link );
	}

	/**
	 * Update link with the specified link categories.
	 *
	 * @since 1.0.0
	 *
	 * @param int   $link_id         ID of the link to update.
	 * @param array $link_categories Array of link categories to add the link to.
	 */
	public function set_link_cats( $link_id = 0, $link_categories = array() ) {
		// If $link_categories isn't already an array, make it one:
		if ( !is_array( $link_categories ) || 0 == count( $link_categories ) )
			$link_categories = array(); // = array( get_option( 'default_link_category' ) );
		$link_categories = array_map( 'intval', $link_categories );
		$link_categories = array_unique( $link_categories );
		wp_set_object_terms( $link_id, $link_categories, 'short_link_category' );
		//clean_bookmark_cache( $link_id );
	}

	/**
	 * Display link create form fields.
	 *
	 * @since 1.0.0
	 *
	 * @param object $link
	 */
	public function link_submit_meta_box($link) {
		?>
		<div class="submitbox" id="submitlink">

			<div id="minor-publishing">

				<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
				<div style="display:none;">
				<?php submit_button( __( 'Save', 'mts-url-shortener' ), '', 'save', false ); ?>
				</div>

				<?php if ( !empty($link->link_id) ) { ?>
				<div id="minor-publishing-actions">
					<div id="preview-action">
						<a class="preview button" href="<?php echo trailingslashit( get_bloginfo( 'url' ) ) . $link->link_name; ?>" target="_blank"><?php _e('Visit Link', 'mts-url-shortener'); ?></a>
					</div>

					<div id="copy-action">
						<?php
						$content = $link->link_url;
						$title = '/'.$link->link_name;
						if ( ! empty( $link->link_attr_title ) ) {
							$content = $link->link_attr_title;
						}
						if ( ! empty( $link->link_anchor ) ) {
							$content = $title = $link->link_anchor;
						}
						$content = apply_filters( 'short_link_default_content', $content, $link );

						$url = trailingslashit( get_bloginfo( 'url' ) ) .  $link->link_name;
						$shortcode = '[shortlink '.$link->link_name.']'.$content.'[/shortlink]';
						$html = esc_attr( '<a href="'.$url.'">'.$content.'</a>' );
						$val = $url;

						$url_title = __('Link URL', 'mts-url-shortener');
						$shortcode_title = __('Shortcode', 'mts-url-shortener');
						$html_title = __('Link HTML', 'mts-url-shortener');
						$title = $url_title;

						$state = 'url';
						$defaults = get_option( 'urlshortener_defaults' );
						if ( is_array( $defaults ) && isset( $defaults['preview_tool'] ) )  {
							switch ($defaults['preview_tool']) {
								case 'shortcode':
									$val = $shortcode;
									$state = 'shortcode';
									$title = $shortcode_title;
								break;

								case 'html':
									$val = $html;
									$state = 'html';
									$title = $html_title;
								break;
							}
						}
						?>

						<input type="text" readonly="readonly" id="shortlink-url-<?php echo $link->link_id; ?>" class="shortlink-url-field" value="<?php echo $val; ?>" data-state="<?php echo $state; ?>" data-url="<?php echo $url; ?>" data-shortcode="<?php echo $shortcode; ?>" data-html="<?php echo esc_attr( $html ); ?>" title="<?php echo $title; ?>" data-urltitle="<?php echo $url_title; ?>" data-shortcodetitle="<?php echo $shortcode_title; ?>" data-htmltitle="<?php echo esc_attr( $html_title ); ?>" />
						<button class="button shortlink-url-field-switch"><span class="dashicons dashicons-editor-code"></span> <?php _e( 'Change code', 'mts-url-shortener' ); ?></button>
						<button class="button shortlink-url-field-copy" data-clipboard-target="#shortlink-url-<?php echo $link->link_id; ?>"><span class="dashicons dashicons-clipboard"></span> <?php _e( 'Copy code', 'mts-url-shortener' ); ?></button>

					</div>

					<div class="clear"></div>
				</div>
				<?php } ?>

				<?php if ( !empty($link->link_id) ) {

				/* translators: Publish Link box date format, see https://secure.php.net/date */
				$datef = __( 'M j, Y @ H:i', 'mts-url-shortener' ); ?>

				<div class="misc-pub-section curtime misc-pub-curtime">
					<span id="timestamp">
					<?php _e('Created on:', 'mts-url-shortener'); ?> <b><?php echo mysql2date( $datef, $link->link_created, false ); ?></b></span>
				</div>
				<?php } ?>

			</div>

			<div id="major-publishing-actions">
			<?php
			/** This action is documented in wp-admin/includes/meta-boxes.php */
			do_action( 'post_submitbox_start' );
			?>
			<div id="delete-action">
			<?php
			if ( !empty($link->link_id) ) { ?>
				<a class="submitdelete deletion" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=url_shortener_links&action=delete_short_link&link_id=' . $link->link_id ), 'url_shortener_action' ); ?>" onclick="if ( confirm('<?php echo esc_js(sprintf(__("You are about to delete this link '%s'\n  'Cancel' to stop, 'OK' to delete."), $link->link_name )); ?>') ) {return true;}return false;"><?php _e( 'Delete', 'mts-url-shortener' ); ?></a>
			<?php } ?>
			</div>

			<div id="publishing-action">
			<?php if ( !empty($link->link_id) ) { ?>
				<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Update Link', 'mts-url-shortener' ) ?>" />
			<?php } else { ?>
				<input name="save" type="submit" class="button button-primary button-large" id="publish" value="<?php esc_attr_e( 'Add Link', 'mts-url-shortener' ) ?>" />
			<?php } ?>
			</div>
			<div class="clear"></div>
			</div>


			<?php
			/**
			 * Fires at the end of the Publish box in the Link editing screen.
			 *
			 * @since 1.0.0
			 */
			do_action( 'submitlink_box' );
			?>
			<div class="clear"></div>
		</div>
		<?php
	}


	public function link_title_meta_box() {
		$this->title_editor_api = new MTS_URL_Shortener_Settings;
		// set the settings
		$this->title_editor_api->set_fields( $this->get_title_fields() );

		$link = $this->get_default_link_to_edit();
		$link_id = 0;
		$editing_link = false;
		if ( ! empty ( $_GET['link_id'] ) ) {
			$link_id = absint( $_GET['link_id'] );
			$link = $this->get_link( $link_id );
			$editing_link = true;
		}

		// initialize settings
		$this->title_editor_api->has_tabs = false;
		$this->title_editor_api->init_editor( $link );

		$this->title_editor_api->show_editor_form();
	}

	public function link_replacements_meta_box() {
		$this->replacements_editor_api = new MTS_URL_Shortener_Settings;
		// set the settings
		$this->replacements_editor_api->set_fields( $this->get_replacement_fields() );

		$link = $this->get_default_link_to_edit();
		$link_id = 0;
		$editing_link = false;
		if ( ! empty ( $_GET['link_id'] ) ) {
			$link_id = absint( $_GET['link_id'] );
			$link = $this->get_link( $link_id );
			$editing_link = true;
		}

		// initialize settings
		$this->replacements_editor_api->has_tabs = false;
		$this->replacements_editor_api->init_editor( $link );

		$this->replacements_editor_api->show_editor_form();
	}

	/**
	 * Display link categories form fields.
	 *
	 * @since 1.0.0
	 *
	 * @param object $link
	 */
	public function link_categories_meta_box($link) {
		?>
		<div id="taxonomy-linkcategory" class="categorydiv">
			<ul id="category-tabs" class="category-tabs">
				<li class="tabs"><a href="#categories-all"><?php _e( 'All Categories', 'mts-url-shortener' ); ?></a></li>
				<li class="hide-if-no-js"><a href="#categories-pop"><?php _e( 'Most Used', 'mts-url-shortener' ); ?></a></li>
			</ul>

			<div id="categories-all" class="tabs-panel">
				<ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
					<?php
					if ( isset($link->link_id) )
						$this->link_category_checklist($link->link_id);
					else
						$this->link_category_checklist();
					?>
				</ul>
			</div>

			<div id="categories-pop" class="tabs-panel" style="display: none;">
				<ul id="categorychecklist-pop" class="categorychecklist form-no-clear">
					<?php wp_popular_terms_checklist('short_link_category'); ?>
				</ul>
			</div>

			<div id="category-adder" class="wp-hidden-children">
				<a id="category-add-toggle" href="#category-add" class="taxonomy-add-new"><?php _e( '+ Add New Category', 'mts-url-shortener' ); ?></a>
				<p id="short-link-category-add" class="wp-hidden-child">
					<label class="screen-reader-text" for="newcat"><?php _e( '+ Add New Category', 'mts-url-shortener' ); ?></label>
					<input type="text" name="newcat" id="newcat" class="form-required form-input-tip" value="<?php esc_attr_e( 'New category name', 'mts-url-shortener' ); ?>" aria-required="true" />
					<input type="button" id="short-link-category-add-submit" data-wp-lists="add:categorychecklist:short-link-category-add" class="button" value="<?php esc_attr_e( 'Add', 'mts-url-shortener' ); ?>" />
					<?php wp_nonce_field( 'add-short-link-category', '_ajax_nonce', false ); ?>
					<span id="category-ajax-response"></span>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs a link category checklist element.
	 *
	 * @since 1.0.0
	 *
	 * @param int $link_id
	 */
	public function link_category_checklist( $link_id = 0 ) {
		$default = 1;
		$checked_categories = array();
		if ( $link_id ) {
			$checked_categories = $this->get_link_cats( $link_id );
			// No selected categories, strange
			if ( ! count( $checked_categories ) ) {
				$checked_categories[] = $default;
			}
		} else {
			$checked_categories[] = $default;
		}
		$categories = get_terms( 'short_link_category', array( 'orderby' => 'name', 'hide_empty' => 0 ) );
		if ( empty( $categories ) )
			return;
		foreach ( $categories as $category ) {
			$cat_id = $category->term_id;
			/** This filter is documented in wp-includes/category-template.php */
			$name = esc_html( apply_filters( 'the_category', $category->name ) );
			$checked = in_array( $cat_id, $checked_categories ) ? ' checked="checked"' : '';
			echo '<li id="short-link-category-', $cat_id, '"><label for="in-short-link-category-', $cat_id, '" class="selectit"><input value="', $cat_id, '" type="checkbox" name="short_link_category[]" id="in-short-link-category-', $cat_id, '"', $checked, '/> ', $name, "</label></li>";
		}
	}

	/**
	 * Retrieves the link categories associated with the link specified.
	 *
	 * @since 1.0.0
	 *
	 * @param int $link_id Link ID to look up
	 * @return array The requested link's categories
	 */
	public static function get_link_cats( $link_id = 0 ) {
		$cats = wp_get_object_terms( $link_id, 'short_link_category', array('fields' => 'ids') );
		return array_unique( $cats );
	}

	/**
	 * Ajax handler for adding a link category.
	 *
	 * @since 1.0.0
	 *
	 * @param string $action Action to perform.
	 */
	public function ajax_add_link_category( $action = '' ) {
		if ( empty( $action ) ) {
			$action = 'add-short-link-category';
		}
		check_ajax_referer( $action );
		if ( ! current_user_can( 'manage_categories' ) ) {
			wp_die( -1 );
		}

		$tax = get_taxonomy( 'short_link_category' );
		$names = explode(',', wp_unslash( $_POST['newcat'] ) );
		$x = new WP_Ajax_Response();
		foreach ( $names as $cat_name ) {
			$cat_name = trim($cat_name);
			$slug = sanitize_title($cat_name);
			if ( '' === $slug )
				continue;
			if ( !$cat_id = term_exists( $cat_name, 'short_link_category' ) )
				$cat_id = wp_insert_term( $cat_name, 'short_link_category' );
			if ( is_wp_error( $cat_id ) ) {
				continue;
			} elseif ( is_array( $cat_id ) ) {
				$cat_id = $cat_id['term_id'];
			}
			$cat_name = esc_html( $cat_name );
			$x->add( array(
				'what' => 'short-link-category',
				'id' => $cat_id,
				'data' => "<li id='short-link-category-$cat_id'><label for='in-short-link-category-$cat_id' class='selectit'><input value='" . esc_attr($cat_id) . "' type='checkbox' checked='checked' name='short_link_category[]' id='in-short-link-category-$cat_id'/> $cat_name</label></li>",
				'position' => -1
			) );
		}
		$x->send();
	}

	/**
	 * Replaces core ajax handler for internal linking
	 *
	 * @since 1.0.0
	 */
	public function wp_ajax_wp_link_ajax() {
		check_ajax_referer( 'internal-linking', '_ajax_linking_nonce' );
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			wp_die( -1 );
		}

		$args = array();
		if ( isset( $_POST['search'] ) ) {
			$args['s'] = wp_unslash( $_POST['search'] );
		}
		if ( isset( $_POST['term'] ) ) {
			$args['s'] = wp_unslash( $_POST['term'] );
		}
		$args['pagenum'] = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		require(ABSPATH . WPINC . '/class-wp-editor.php');

		$query_all_links = array('shortlink', 'short link', 'shortlinks', 'short links');
		$results = array();
		$homeurl = trailingslashit( get_bloginfo( 'url' ) );
		if ( in_array( $args['s'], $query_all_links ) ) {
			// Show 20 first short links
			$links = MTS_URL_Shortener_Admin::get_links( array( 'posts_per_page' => '50' ) );
			foreach ($links as $id => $link) {
				$results[] = array(
					'ID' => $link->link_id,
					'info' => __( 'Short Link', 'mts-url-shortener' ),
					'permalink' => $homeurl . $link->link_name,
					'title' => $link->link_name
				);
			}
		} else {
			if ( $args['s'] ) {
				$links = MTS_URL_Shortener_Admin::get_links( array( 'posts_per_page' => '10', 'search' => $args['s'] ) );
				foreach ($links as $id => $link) {
					$results[] = array(
						'ID' => $link->link_id,
						'info' => __( 'Short Link', 'mts-url-shortener' ),
						'permalink' => $homeurl . $link->link_name,
						'title' => $link->link_name
					);
				}
			}
		}

		$results = array_merge( $results, _WP_Editors::wp_link_query( $args ) );

		if ( ! isset( $results ) )
			wp_die( 0 );

		echo wp_json_encode( $results );
		echo "\n";
		wp_die();
	}


	static public function set_link_replacements( $link_id, $link_replacements ) {
		global $wpdb;
		if ( ! is_array( $link_replacements ) ) {
			return false;
		}

		$old_replacement_ids = array_keys( self::get_link_replacements( $link_id ) ); // replacement IDs array
		$new_replacement_ids = array();

		// Set new links
		foreach ($link_replacements as $key => $replace_data) {
			if ( empty( $replace_data['replace_key'] ) )
				continue;

			// Check if it exists already
			if ( $existing_id = $wpdb->get_var( $wpdb->prepare( "SELECT replacement_id FROM {$wpdb->prefix}short_link_replacements WHERE link_id = %d AND replace_key = %s AND type = %s", $link_id, $replace_data['replace_key'], $replace_data['type'] ) ) ) {
				$new_replacement_ids[] = $existing_id;
				continue;
			}

			if ( $replace_data['type'] == 'link' ) {
				$replace_data['replace_key'] = esc_url_raw( $replace_data['replace_key'] );
			}
			$new_replacement = array(
				'replace_key' => $replace_data['replace_key'],
				'type' => $replace_data['type'],
				'link_id' => $link_id
			);
			if ( $wpdb->insert( $wpdb->prefix.'short_link_replacements', $new_replacement ) ) {
				$new_replacement_ids[] = $wpdb->insert_id;
			}
		}

		// Delete unneeded
		$delete_replacement_ids = array_diff( $old_replacement_ids, $new_replacement_ids );
		if ( $delete_replacement_ids ) {
			self::delete_link_replacements( $link_id, $delete_replacement_ids );
		}

		return true;
	}

	static public function get_link_replacements( $link_id = null, $output = OBJECT_K ) {
		global $wpdb;
		if ( $link_id === null )
			return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}short_link_replacements", $output );
		elseif ( $link_id )
			return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}short_link_replacements WHERE link_id = %d", $link_id ), $output );

		return array();
	}

	static function delete_link_replacements( $link_id, $replacement_ids = null ) {
		global $wpdb;

		// Delete some
		if ( $replacement_ids && is_array( $replacement_ids ) ) {
			$delete_query = "DELETE FROM {$wpdb->prefix}short_link_replacements WHERE replacement_id = ";
			$replacement_ids = array_map( 'absint', $replacement_ids );
			$delete_query .= implode( ' OR replacement_id = ', $replacement_ids);
			return $wpdb->query( $delete_query );
		}

		// Delete all
		return $wpdb->delete( $wpdb->prefix.'short_link_replacements', array( 'link_id' => $link_id ), array( '%d' ) );
	}

	/**
	 * Link Shortener meta box in post/page editor
	 * @return
	 */
	public function post_editor_meta_box() {

		$this->post_editor_api = new MTS_URL_Shortener_Settings;
		// set the settings
		$this->post_editor_api->set_fields( $this->get_title_fields() );

		// initialize settings
		$this->post_editor_api->has_tabs = false;
		$this->post_editor_api->init_meta_box();

		$this->post_editor_api->show_editor_form();

	}

	public function import_section() {
		global $wpdb;
		$already_imported = $this->prettylinks_imported;

		$prli_links_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}prli_links" );
		if ( $already_imported ) { ?>
			<p class="already-migrated-msg"><span class="dashicons dashicons-warning"></span> <?php _e('Links have already been imported. Running the importer again may result in duplicate links.', 'mts-url-shortener'); ?></p>
		<?php } ?>
		<div class="settings-tab-migrate">
			<div id="settings-allow-migrate">
				<p><?php _e('Here you can import your links created with Pretty Link Lite or Pretty Link Pro to use them in the <strong>URL Shortener</strong> plugin.', 'mts-url-shortener'); ?></p>
				<p class="migrate-items"><?php printf( __( 'A total of %s links can be imported.', 'mts-url-shortener'), '<span id="migrate-items-num">'.$prli_links_count.'</span>' ); ?></p>

				<p><label><input type="checkbox" checked="checked" id="prli-import-groups" value="1"> <?php _e('Import groups as Link Categories', 'mts-url-shortener'); ?></label></p>
				<p>
					<label><?php _e('Import unsupported redirection types as: ', 'mts-url-shortener'); ?> <br />
						<select id="prli-import-unsupported">
							<option value="no"><?php _e('Do not import', 'mts-url-shortener'); ?></option>
							<option value="301"><?php _e('301 Permanent header redirection', 'mts-url-shortener'); ?></option>
							<option value="302"><?php _e('302 Temporary header redirection', 'mts-url-shortener'); ?></option>
							<option value="307" selected="selected"><?php _e('307 Temporary header redirection', 'mts-url-shortener'); ?></option>
						</select>
					</label>
				</p>

				<a href="#" class="button button-secondary" id="start-migrate" data-start="<?php echo 0; ?>"><?php _e('Start import', 'mts-url-shortener'); ?></a>
				<?php wp_nonce_field( 'short-link-import', '_slimportnonce', false, true ); ?>
				<textarea id="url-shortener-prli-migrate-log"></textarea>
			</div>
		</div>
		<?php $this->import_script(); ?>
	<?php
	}

	public function import_script() { ?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			if ($('#url-shortener-prli-migrate-log').length) {
				var $migrate_log = $('#url-shortener-prli-migrate-log');
				var migrate_started = false;
				var rows_left = parseInt($('#migrate-items-num').text());
				var migrated_rows = $('#start-migrate').data('start');
				var migrate_finished = false;
				var import_groups = 0;
				var redirection_fallback = '307';
				var nonce = $('#_slimportnonce').val();
				var updatelog = function( text ) {
					$migrate_log.css('display', 'block').val(function(index, old) {
						if ( ! old ) return text;
						return old + "\n" + text;
					});
				}
				var ajax_migrate = function( batchindex ) {
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: { action: 'shortlink_prli_import', batch: batchindex, import_groups: import_groups, redirection_fallback: redirection_fallback, _slimportnonce: nonce },
					})
					.done(function( data ) {
						if ( data === '0' || data === '-1' || typeof data.nextbatch === 'undefined' ) {
							updatelog('<?php _e("Importing error", "url-shortener"); ?>');
							return;
						}

						if ( data.message )
							updatelog(data.message);

						if ( data.nextbatch != 0 ) {
							ajax_migrate( data.nextbatch );
						}
					});

				}
				$('#start-migrate').click(function(event) {
					event.preventDefault();
					if (migrate_started)
						return false;

					import_groups = $('#prli-import-groups').prop('checked') ? 1 : 0;
					redirection_fallback = $('#prli-import-unsupported').val();


					migrate_started = true;
					updatelog('Import started, please wait...');

					ajax_migrate(migrated_rows);
				});

			}
		});
		</script>
		<?php
	}

	public function ajax_import_prli() {
		$action = 'short-link-import';
		check_ajax_referer( $action, '_slimportnonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( -1 );
		}

		global $wpdb;
		$batch = isset( $_POST['batch'] ) ? intval( $_POST['batch'] ) : 0;
		$import_groups = !empty( $_POST['import_groups'] ) ? true : false;
		$redirection_fallback = !empty( $_POST['redirection_fallback'] ) ? $_POST['redirection_fallback'] : 'no';
		$prli_links_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}prli_links" );

		$output = array('nextbatch' => 0, 'message' => '', 'finished' => 0);
		if ( $batch == 0 && ! $import_groups ) {
			$batch = 1;
		}

		$limit = apply_filters( 'url_shortener_prli_import_per_batch', 10 );

		if ( $batch == 0 ) {
			$this->prli_import_groups();
			$output['nextbatch'] = 1;
			$output['message'] = __( 'Link groups imported', 'mts-url-shortener' );
		} else {
			$start = max((($batch-1) * $limit), 0);
			$rows = $wpdb->get_results( 'SELECT * from '.$wpdb->prefix.'prli_links LIMIT '.$limit.' OFFSET '.$start );
			foreach ($rows as $k => $prettylink) {
				$this->prli_import_link( $prettylink, $redirection_fallback, $import_groups );
			}
			$output['message'] = sprintf( __( 'Links imported: %1$s - %2$s', 'mts-url-shortener' ), $start+1, min( $start + $limit, $prli_links_count ) );
			if ( $start + $limit >= $prli_links_count ) {
				$output['finished'] = 1;
				$output['nextbatch'] = 0;
				$output['message'] .= ' | '.__( 'Import Finished', 'mts-url-shortener' );
				// Done
				$this->prettylinks_imported = true;
				update_option( 'urlshortener_prli_imported', true );
			} else {
				$output['nextbatch'] = $batch + 1;
			}
		}

		echo wp_json_encode( $output );

		die();
	}

	public function prli_import_link( $link_data, $redirection_fallback = 'no', $import_groups = true ) {
		// If link exists already, skip it
		if ( $this->get_link_by_slug( $link_data->slug ) ) {
			return;
		}
		$new_link = array();
		$new_link['link_name'] = $link_data->slug;
		$new_link['link_url'] = $link_data->url;
		$new_link['link_redirection_method'] = $this->prli_convert_redirectionmethod( $link_data, $redirection_fallback );
		if ( $new_link['link_redirection_method'] == 'no' ) {
			// don't import this
			return;
		}
		$new_link['link_title'] = $link_data->name;
		$new_link['link_created'] = $link_data->created_at;
		if ( $link_data->nofollow ) {
			$new_link['link_attr_rel'] = 'nofollow';
		}
		if ( $link_data->param_forwarding == 'on' ) {
			$new_link['link_forward_parameters'] = 1;
		}


		$id = $this->insert_link( $new_link );


		// Keyword & url replacements
		// Base replacement: the short URL itself
		$replacements = array(
			array(
				'replace_key' => trailingslashit( get_bloginfo( 'url' ) ) . $new_link['link_name'],
				'type' => 'link',
				'link_id' => $id
			)
		);
		// Link replacements
		$replace_urls = $this->prli_get_link_meta( $link_data->id, 'prli-url-replacements' );
		if ( $replace_urls && is_array( $replace_urls ) )  {
			foreach ( $replace_urls as $url ) {
				$replacements[] = array(
					'replace_key' => $url,
					'type' => 'link',
					'link_id' => $id
				);
			}
		}

		// Keyword replacements
		$replace_keywords = $this->prli_get_link_keywords( $link_data->id );
		if ( $replace_keywords && is_array( $replace_keywords ) ) {
			foreach ($replace_keywords as $keyword) {
				$replacements[] = array(
					'replace_key' => $keyword,
					'type' => 'keyword',
					'link_id' => $id
				);
			}
		}

		$this->set_link_replacements( $id, $replacements );

		// Add categories
		if ( $import_groups && $link_data->group_id ) {
			// get map
			$group_to_cat_map = get_option( 'ls_prli_groups2cats' );
			if ( isset( $group_to_cat_map[$link_data->group_id] ) ) {
				$cats = array( $group_to_cat_map[$link_data->group_id] );
				$this->set_link_cats( $id, $cats );
			}
		}
	}

	public function prli_convert_redirectionmethod( $link_data, $fallback ) {
		$allowed = array( '307' => '307', '301' => '301' );
		$delay = 0;
		$redirectionmethod = '';
		if ( array_key_exists( $link_data->redirect_type, $allowed ) ) {
			$redirectionmethod = $allowed[$link_data->redirect_type];
		} else {
			$redirectionmethod = $fallback;
		}

		return $redirectionmethod;
	}

	public function prli_import_groups() {
		global $wpdb;
		$groups_cats_map = array();
		$groups = $wpdb->get_results( 'SELECT * from '.$wpdb->prefix.'prli_groups' );
		foreach ($groups as $i => $group) {
			if ( $id = term_exists( $group->name, 'short_link_category' ) ) {
				// Term exists
				$groups_cats_map[$group->id] = $id;
				continue;
			}
			// Insert term
			$id = wp_insert_term( $group->name, 'short_link_category' );
			$groups_cats_map[$group->id] = $id;
		}
		update_option( 'ls_prli_groups2cats', $groups_cats_map );
	}

	public function prli_get_link_meta( $link_id, $meta_key, $return_var = false ) {
		global $wpdb;
		$query_str = "SELECT meta_value FROM {$wpdb->prefix}prli_link_metas WHERE meta_key = %s and link_id = %d";
		$query = $wpdb->prepare($query_str,$meta_key,$link_id);

		if($return_var)
			return $wpdb->get_var("{$query} LIMIT 1");
		else
			return $wpdb->get_col($query, 0);
	}

	public function prli_get_link_keywords( $link_id ) {
		global $wpdb;
		$query_str = "SELECT text FROM {$wpdb->prefix}prli_keywords WHERE link_id = %d";
		$query = $wpdb->prepare($query_str,$meta_key,$link_id);

		return $wpdb->get_col($query, 0);
	}

	public function show_notices() {
		$screen = get_current_screen();

		if ( $this->prettylinks_installed && ! $this->prettylinks_imported ) {
			?>
			<div class="notice notice-success is-dismissible urlshortener-notice urlshortener-notice-import">
				<p><?php printf(__( 'We noticed Pretty Link plugin has previously been installed on your site. You may want to run the %1$simporter%2$s to use your existing links with the <strong>URL Shortener</strong> plugin.', 'mts-url-shortener' ), '<a href="'.admin_url( 'admin.php?page=url_shortener_settings#import' ).'">', '</a>'); ?></p>
			</div>
			<script type="text/javascript">
			jQuery(window).load(function() {
				jQuery('.urlshortener-notice .notice-dismiss').click(function(event) {
					jQuery.ajax({
						url: ajaxurl,
						type: 'GET',
						data: { action: 'urlshortener_dismiss_importnotice' },
					});
				});
			});
			</script>
			<?php
		}

		// Show below notice only on
		if ( ! in_array( $screen->id, $this->screens ) ) {
			return;
		}

		if ( ! get_option('permalink_structure') ) {
			?>
			<div class="notice notice-error is-dismissible urlshortener-notice urlshortener-notice-import">
				<p><?php printf(__( 'Custom permalinks must be enabled to use the Link Shortener plugin. Please navigate to %1$sSettings &gt; Permalinks%2$s to change the permalink structure.', 'mts-url-shortener' ), '<a href="'.admin_url( 'options-permalink.php' ).'">', '</a>'); ?></p>
			</div>
			<?php
		}

	}

	// Display Pro Notice
	public function url_shortener_admin_notice() {
		global $current_user ;
		$user_id = $current_user->ID;
				$allow_notices = apply_filters('mts_urlshortnener_admin_notices', true );
		/* Check that the user hasn't already clicked to ignore the message */
		/* Only show the notice 2 days after plugin activation */
		if ( $allow_notices && ! get_user_meta($user_id, 'url_shortener_ignore_notice') && time() >= (get_option( 'url_shortener_activated', 0 ) + (2 * 24 * 60 * 60)) ) {
			echo '<div class="updated notice-info wp-url-shortener-notice" id="wpurlshortener-notice" style="position:relative;">';
			echo __('<p>Advanced Stats, Cloaking, 3 new Redirection Methods and much more - <a target="_blank" href="https://mythemeshop.com/plugins/url-shortener-pro/?utm_source=URL+Shortener&utm_medium=Notification+Link&utm_content=URL+Shortener+Pro+LP&utm_campaign=WordPressOrg"><strong>Upgrade to URL Shortener Pro</strong></a></p><a class="notice-dismiss mts-urlshortener-dismiss" data-ignore="0" href="#"></a>');
			echo "</div>";
		}

		/* Other notice appears right after activating */
		/* And it gets hidden after showing 3 times */
		if ( $allow_notices && ! get_user_meta($user_id, 'url_shortener_ignore_notice_2') && get_option('url_shortener_views', 0) < 3 && get_option( 'url_shortener_activated', 0 ) ) {
			$views = get_option('url_shortener_views', 0);
			update_option( 'url_shortener_views', ($views + 1) );
			echo '<div class="updated notice-info url-shortener-notice" id="urlshortener-notice2" style="position:relative;">';
			echo '<p>';
			_e('Thank you for trying URL Shortener. We hope you will like it.', 'mts-url-shortener');
			echo '</p>';
			echo '<a class="notice-dismiss mts-urlshortener-dismiss" data-ignore="1" href="#"></a>';
			echo "</div>";
		}
	}

	public function ajax_dismiss_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		update_option( 'urlshortener_prli_imported', '1' );
	}

	public function url_shortener_admin_notice_ignore() {
		global $current_user;
		$user_id = $current_user->ID;
		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset($_POST['dismiss']) ) {
			if ( '0' == $_POST['dismiss'] ) {
				add_user_meta($user_id, 'url_shortener_ignore_notice', '1', true);
			} elseif ( '1' == $_POST['dismiss'] ) {
				add_user_meta($user_id, 'url_shortener_ignore_notice_2', '1', true);
			}
		}
	}

	public function on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		if ( is_plugin_active_for_network( 'mts-url-shortener-pro/mts-url-shortener-pro.php' ) ) {
			require_once URL_SHORTENER_PLUGIN_PATH . 'includes/class-url-shortener-activator.php';
			switch_to_blog( $blog_id );
			MTS_URL_Shortener_Activator::add_tables();
			restore_current_blog();
		}
	}

	public function on_delete_blog( $tables ) {
		global $wpdb;
		$tables[] = $wpdb->prefix . 'short_links';
		$tables[] = $wpdb->prefix . 'short_link_replacements';
		$tables[] = $wpdb->prefix . 'short_link_clicks';
		return $tables;
	}

}
