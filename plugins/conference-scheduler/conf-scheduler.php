<?php
/*
Plugin Name: Conference Scheduler
Plugin URI: https://conferencescheduler.com/
Description: Display and organize your conference workshops in a powerful, easy-to-use system.
Version: 2.4.7
Author: Shane Warner
Author URI: https://myceliumdesign.ca/
Text Domain: conf-scheduler
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('CONF_SCHEDULER_VERSION'))
    define('CONF_SCHEDULER_VERSION', '2.4.7');

if (!defined('CONF_SCHEDULER_PATH'))
    define('CONF_SCHEDULER_PATH', wp_normalize_path(plugin_dir_path( __FILE__ )));

if (!defined('CONF_SCHEDULER_URL'))
    define('CONF_SCHEDULER_URL', plugin_dir_url( __FILE__ ));

if( ! class_exists('Conference_Scheduler') ) :

class Conference_Scheduler {
  /**
   * Used for recursive session output to track when to break and show next day header
   * @var string
   */
  public $last_date = -1;

  /**
   * Stores live settings values
   * @var array
   */
  public $options = array(
    'workshop_sort_field' => 'title',
    'workshop_sort_order' => 'asc',
    'day_format' => 'l j M Y',
    'day_format_custom' => 'l j M Y',
    'view_mode' => 'session_groups',
    'day_mode' => 'list',
    'workshops_slug' => ''
  );

  /**
   * Setup plugin and register hooks
   * @return null
   */
	function initialize() {
		add_action( 'init', array($this, 'conf_create_post_types'), 10 );
		add_action( 'init', array($this, 'activiation_check'), 15);
    add_action( 'init', array($this, 'translate'), 1);
    add_action( 'wp_enqueue_scripts', array($this, 'register_assets'), 5);
    add_action( 'wp_enqueue_scripts', array($this, 'hook_frontend_assets'));
    add_action( 'customize_register',array($this, 'setup_customizer')); // errors if only register with admin
    add_action( 'customize_controls_enqueue_scripts', array($this, 'customizer_panels_js') );
    add_shortcode( 'conf_scheduler', array($this, 'render_schedule'));
    add_filter( 'conf_scheduler_after_filters', array($this, 'render_day_tabs'), 100, 3);
    add_shortcode( 'conf_scheduler_block_schedule', array($this, 'render_block_schedule'));
    add_filter( 'the_content', array($this, 'single_workshop_view'));
    add_filter( 'the_time', array($this, 'set_post_time_as_start_time'), 10, 3); // set post time to workshop time on front-end

    // Block editor support
    add_action( 'init', array($this, 'register_blocks' ));
    add_action( 'rest_api_init', array($this,'rest_api_init'));
    add_filter( 'rest_prepare_taxonomy', array($this,'hide_sessions_ui_from_block_editor'), 10, 2 );
    //add_action( 'rest_api_init', array($this,'register_session_meta_in_rest'));

    if ( is_admin() ) {
      add_action( 'in_plugin_update_message-conf-scheduler/conf-scheduler.php', array($this, 'extra_plugin_update_message'), 10, 2);
      add_action( 'doing_dark_mode', array($this, 'conf_scheduler_dark_mode') );

      add_action( 'admin_footer', array($this, 'conf_scheduler_info_html' ));
      add_action( 'admin_enqueue_scripts', array($this, 'register_assets'), 5);
      add_action( 'admin_enqueue_scripts', array($this, 'admin_enqueue_assets' ) );
      add_action( 'admin_menu', array($this, 'setup_admin_pages'), 100 );
      add_action( 'admin_menu', array($this, 'process_options'), 1 ); // process options before admin_notices
      add_action( 'conf_scheduler_options_section', array($this, 'output_general_options'), 10, 2);
      add_action( 'wp_ajax_conf_scheduler_delete_data', array($this, 'ajax_process_remove_data_click') );

      add_action( 'add_meta_boxes_conf_workshop', array($this, 'workshop_meta_boxes'));
      add_action( 'conf_sessions_add_form_fields', array($this, 'sessions_meta_box_html'));
      add_action( 'conf_sessions_edit_form_fields', array($this, 'sessions_edit_meta_box_html'), 100);
  		add_filter( 'manage_edit-conf_sessions_columns', array($this, 'sessions_custom_columns_head'));
  		add_filter( 'manage_conf_sessions_custom_column', array($this, 'sessions_custom_columns_content'), 10, 3);
  		add_filter( 'manage_edit-conf_sessions_sortable_columns',array($this, 'sessions_sortable_columns'), 10);
      add_filter( 'manage_conf_workshop_posts_columns', array($this, 'workshops_custom_columns_head'));
      add_filter( 'manage_conf_workshop_posts_custom_column', array($this, 'workshops_custom_columns_content'), 10, 2);
      add_filter( 'manage_edit-conf_workshop_sortable_columns',array($this, 'workshop_sortable_columns'), 10);
  		add_action( 'pre_get_terms', array($this, 'sessions_admin_orderby') );
      add_action( 'pre_get_posts', array($this, 'workshops_admin_orderby') );
      add_filter( 'posts_clauses', array($this, 'workshop_admin_search_clauses'), 10, 2 );
      add_action( 'save_post_conf_workshop', array($this, 'save_workshop_meta'), 10, 2 );
      add_action( 'create_conf_sessions', array($this, 'save_session_meta'), 10, 2 );
      add_action( 'edit_conf_sessions', array($this, 'save_session_meta'), 10, 2 );
      add_action( 'admin_init', array($this, 'permalink_slug_settings'));
    }
	}

  function option( $option ) {
    $value = get_option( 'conf_scheduler_'.$option, null );
    if ( $value === null )
      $value = $this->options[$option];

    return $value;
  }

  function permalink_slug_settings() {
    // have to manually check for and trigger save
    $this->permalink_settings_save();

    add_settings_field(
			'conf_scheduler_workshop_slug',
			__( 'Workshop base', 'conf-scheduler' ),
			array( $this, 'workshop_slug_setting_input' ),
			'permalink',
			'optional'
		);
  }

  function workshop_slug_setting_input() {
    ?>
		<input name="conf_scheduler_workshop_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $this->option('workshops_slug') ); ?>" placeholder="<?php echo esc_attr_x( 'workshops', 'URL slug', 'conf-scheduler' ); ?>" />
		<?php
      wp_nonce_field( 'workshops_permalink_slug', 'conf_scheduler_permalink_nonce');
	}

  function permalink_settings_save() {
		if ( ! is_admin() ) return;

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page.
		if ( isset( $_POST['conf_scheduler_workshop_slug'] ) && wp_verify_nonce( wp_unslash( $_POST['conf_scheduler_permalink_nonce'] ), 'workshops_permalink_slug' ) ) {
			$slug = sanitize_text_field( wp_unslash( $_POST['conf_scheduler_workshop_slug'] ) );
			update_option( 'conf_scheduler_workshop_slug', $slug );
		}
	}

  /**
   * Register Gutenberg blocks with WP
   * @return null
   */
  function register_blocks() {
    if( function_exists( 'register_block_type') ){
      wp_enqueue_script(
          'conf-scheduler-blocks',
          CONF_SCHEDULER_URL .'build/conf-scheduler.build.js',
          array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components' ),
          CONF_SCHEDULER_VERSION
      );

      // Notify WP about translations in JS
      wp_set_script_translations( 'conf-scheduler-blocks', 'conf-scheduler' );

      /* not currently used
      wp_enqueue_style(
          'conf-scheduler-blocks-editor',
          CONF_SCHEDULER_URL .'build/conf-scheduler-editor.css',
          array( 'wp-edit-blocks' )
      );

      wp_enqueue_style(
          'conf-scheduler-blocks',
          CONF_SCHEDULER_URL .'build/conf-scheduler-style.css'
      );

      ** Removed from register_block_type atts array
      'editor_script' => 'conf-scheduler-blocks',
      'editor_style' => 'conf-scheduler-blocks-editor',
      */

      register_block_type( 'conf-scheduler/display', array(
        'attributes'      => array(
          'displaySession' => array(
            'type'    => 'number',
          ),
          'limitSessions' => array(
            'type'    => 'number',
          ),
          'content'   => array(
            'type'    => 'string',
          ),
          'defaultState'   => array(
            'type'    => 'string',
          ),
        ),
        'render_callback' => array($this, 'render_schedule'),
      ) );
    }
  }

  /**
   * Add REST routes
   * @return null
   */
  function rest_api_init(){
    register_rest_route('conference-scheduler/v1','/get-block/(?P<id>\d+)',array(
      //'methods'         => WP_REST_Server::CREATABLE,
      'methods'         => WP_REST_Server::READABLE,
      //'methods'         => WP_REST_Server::ALLMETHODS,
			'callback'	=> array( $this, 'output_block_contents' ),
      'permission_callback' => function () { return current_user_can( 'edit_others_posts' ); },
    ));

    register_rest_route('conference-scheduler/v1','/get-block',array(
      //'methods'         => WP_REST_Server::CREATABLE,
      'methods'         => WP_REST_Server::READABLE,
      //'methods'         => WP_REST_Server::ALLMETHODS,
			'callback'	=> array( $this, 'output_block_contents' ),
      'permission_callback' => function () { return current_user_can( 'edit_others_posts' ); },
    ));
  }

  /**
   * Workaround to hide sessions UI in Block Editor
   * @param  object  $response  REST response data
   * @param  WP_Term $taxonomy  Taxonomy object being output
   * @return object             REST response data
   */
  function hide_sessions_ui_from_block_editor ( $response, $taxonomy ){
  	if ( 'conf_sessions' === $taxonomy->name ) {
  		$response->data['visibility']['show_ui'] = false;
  	}
  	return $response;
  }

  /**
   * Display extra upgrade notice to user on plugin list screen.
   * @param  array $data     [description]
   * @param  array $response [description]
   * @return null           [description]
   */
  function extra_plugin_update_message( $data, $response ) {
    if( isset( $data['upgrade_notice']) && strlen(trim($data['upgrade_notice'])) > 0 )  {
      // create full local links
      $message = str_replace('href="/wp-admin/', 'href="'.admin_url(), $data['upgrade_notice']);
      $message = str_replace('href="/', 'href="'.get_site_url().'/', $message);

      printf(
        '</p><div style="margin: 0 -12px; padding: 10px 10px 10px 12px; border-top: 1px solid #ffb900;">%s</div><p style="display:none">',
        $message
      );
    }
  }

  /**
   * Outputs Conference Scheduler HTML via REST for use in CSP block
   * @param  WP_REST_Request $request  Request object
   * @return JSON                      JSON object with HTML for default block state
   */
  function output_block_contents( WP_REST_Request $request){
      $params = $request->get_params();
      $nullArray = array("html" => "");

      /*
      //security check
      if( $request->get_header( 'X-WP-Nonce' ) ){
          if( !wp_verify_nonce( $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ){
              echo json_encode( $nullArray );
              die();
          }
      } else {
          echo json_encode( $nullArray );
          die();
      } */

      $atts = array('hideWorkshops' => true);

      if( isset($params['id']) ) {
        $id = sanitize_text_field( $params['id'] );
        if( is_numeric($id) ) {
          $atts['id'] = $id;
        }
      }

      $session_tree = $this->get_sessions($atts);

      $headers = array();
      foreach ($session_tree as $session) {
        if (date('Y-m-d', $session->start) != $this->last_date) {
          $date_format = $this->option('day_format') == 'custom' ? $this->option('day_format_custom') : $this->option('day_format');
          $headers[] = array('title'=>date_i18n($date_format, $session->start), 'type'=>'day', 'id' => $session->start);
          $this->last_date = date('Y-m-d', $session->start);
        }
        $headers[] = $this->get_session_title(array($session));
      }
      $return = array('renderedSessions' => $headers);
      echo json_encode( $return );
      die;
  }

  /**
   * Get the title for the sessions and any children
   * @param  WP_Term $session  Term object for the session
   * @return array             array of title and child sessions
   */
  function get_session_title($sessions) {
    $return = array();
    foreach ($sessions as $session) {
      $title = '';
      if(!$session->hide_time) $title .= date(get_option('time_format'), $session->start).' - '.date(get_option('time_format'), ($session->start + $session->length *60) );
      if(!$session->hide_title) $title .= ' '.$session->name;
      $current = array('title'=>$title, 'id'=>$session->term_id);
      if (isset($session->children)) $current['children'] = $this->get_session_title($session->children);
      $return[] = $current;
    }
    return count($sessions) == 1 ? $return[0] : $return;
  }

  /**
   * Add Conference section and settings to the WordPress Customizer
   * @hooked customize_register
   * @param  object $wp_customize     The WordPress Customizer object
   * @return null
   */
  function setup_customizer( $wp_customize ) {

    $wp_customize->add_section( 'conf_scheduler' , array(
      'title' => _x( 'Conference', 'customizer section title', 'conf-scheduler' ),
      'priority' => 105, // Before Widgets.
    ) );

    // view mode
    $wp_customize->add_setting( 'conf_scheduler_view_mode', array(
      'default' => 'session_groups',
      'type' => 'option',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_view_mode', array(
      'label' => __( 'View Mode', 'conf-scheduler' ),
      'type' => 'radio',
      'choices'=> array(
        'session_groups' => __('Session Groups', 'conf-scheduler'),
        'timeline' => __('Timeline', 'conf-scheduler'),
      ),
      'section' => 'conf_scheduler',
    ) );

    // view mode
    $wp_customize->add_setting( 'conf_scheduler_day_mode', array(
      'default' => 'list',
      'type' => 'option',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_day_mode', array(
      'label' => __( 'Day Layout', 'conf-scheduler' ),
      'type' => 'radio',
      'choices'=> array(
        'list' => __('Vertical List', 'conf-scheduler'),
        'tabs' => __('Horizontal Tabs', 'conf-scheduler'),
      ),
      'section' => 'conf_scheduler',
    ) );

    // Workshops per row
    $wp_customize->add_setting( 'conf_scheduler_per_row', array(
      'default' => '3',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_per_row', array(
      'label' => __( 'Workshops per row', 'conf-scheduler' ),
      'type' => 'number',
      'section' => 'conf_scheduler',
    ) );

    // Workshops sort field
    $wp_customize->add_setting( 'conf_scheduler_workshop_sort_field', array(
      'default' => 'title',
      'type' => 'option',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_workshop_sort_field', array(
      'label' => __( 'Workshops Sort Field', 'conf-scheduler' ),
      'type' => 'select',
      'choices'=> apply_filters('conf_scheduler_workshop_sort_field', array(
        'title' => __('Workshop Title', 'conf-scheduler'),
        'date' => __('Date Created', 'conf-scheduler'),
        'modified' => __('Date Modified', 'conf-scheduler'),
        'workshop_id' => __('Workshop ID', 'conf-scheduler'),
        'presenter' => __('Presenter', 'conf-scheduler'),
        'location' => __('Location', 'conf-scheduler')
      )),
      'section' => 'conf_scheduler',
    ) );

    // Workshops sort order
    $wp_customize->add_setting( 'conf_scheduler_workshop_sort_order', array(
      'default' => 'asc',
      'type' => 'option',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_workshop_sort_order', array(
      'label' => __( 'Workshops Sort Order', 'conf-scheduler' ),
      'type' => 'select',
      'choices'=> apply_filters('conf_scheduler_workshop_sort_order', array(
        'asc' => __('Ascending', 'conf-scheduler'),
        'desc' => __('Descending', 'conf-scheduler'),
      )),
      'section' => 'conf_scheduler',
    ) );

    // Day Format
    $wp_customize->add_setting( 'conf_scheduler_day_format', array(
      'default' => 'l j M Y',
      'type' => 'option',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_day_format', array(
      'label' => __( 'Format for Day Titles', 'conf-scheduler' ),
      'type' => 'select',
      'choices'=> array(
        'l j M Y' => '"l j M Y" - '.date_i18n( 'l j M Y'),
        'jS F Y' => '"jS F Y" - ' . date_i18n( 'jS F Y'),
        'Y-m-d' => '"Y-m-d" - ' . date_i18n( 'Y-m-d'),
        'd/m/Y' => '"d/m/Y" - ' . date_i18n( 'd/m/Y'),
        'm/d/Y' => '"m/d/Y" - ' . date_i18n( 'm/d/Y'),
        'custom' => __('Custom'),
      ),
      'section' => 'conf_scheduler',
    ) );

    // Date format custom
    $wp_customize->add_setting( 'conf_scheduler_day_format_custom', array(
      'default' => 'l j M Y',
      'type' => 'option',
      'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'conf_scheduler_day_format_custom', array(
      'label' => __( 'Custom date format', 'conf-scheduler' ),
      'type' => 'text',
      'section' => 'conf_scheduler',
    ) );

    $colours = array(
      'conf_scheduler_border_color' => __('Border Color', 'conf-scheduler'),
      'conf_scheduler_title_color' => __('Workshop Title Color', 'conf-scheduler'),
      'conf_scheduler_background_color' => __('Workshop Background Color', 'conf-scheduler'),
      'conf_scheduler_background_alt_color' => __('Open/Picked Background Color', 'conf-scheduler'),
      'conf_scheduler_session_color' => __('Session Name Color', 'conf-scheduler'),
      'conf_scheduler_day_color' => __('Day Name Color', 'conf-scheduler'),
    );
    foreach ($colours as $setting => $label) {
      $wp_customize->add_setting( $setting, array(
        'default' => '',
        'sanitize_callback' => 'sanitize_hex_color',
      ) );
      $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
        'label' => __( $label, 'conf-scheduler' ),
        'section' => 'conf_scheduler',
      ) ) );
    }

  }

  /**
   * Enqueue script to manage customizer panel
   * @hooked customize_controls_enqueue_scripts
   * @return null
   */
  function customizer_panels_js() {
    wp_enqueue_script( 'conf_scheduler-customize-controls', CONF_SCHEDULER_URL .'static/js/customize-controls.js', array('jquery'), CONF_SCHEDULER_VERSION, true );
  }

  /**
   * Load translations
   * @hooked plugins_loaded
   * @return null
   */
  function translate() {
    load_plugin_textdomain( 'conf-scheduler', false, basename( dirname( __FILE__ ) ) . '/languages' );
  }

	/**
   * Register Workshop post type and session, theme and keyword taxonomies
   * @hooked init
   * @return null
   */
	function conf_create_post_types() {

		// Add Sessions taxonomy, make it hierarchical
		$labels = array(
			'name'              => _x( 'Sessions', 'taxonomy general name' , 'conf-scheduler'),
			'singular_name'     => _x( 'Session', 'taxonomy singular name' , 'conf-scheduler'),
			'search_items'      => __( 'Search Sessions' , 'conf-scheduler'),
			'all_items'         => __( 'All Sessions' , 'conf-scheduler'),
			'parent_item'       => __( 'Parent Session' , 'conf-scheduler'),
			'parent_item_colon' => __( 'Parent Session:' , 'conf-scheduler'),
			'edit_item'         => __( 'Edit Session' , 'conf-scheduler'),
			'update_item'       => __( 'Update Session' , 'conf-scheduler'),
			'add_new_item'      => __( 'Add New Session' , 'conf-scheduler'),
			'new_item_name'     => __( 'New Session Name' , 'conf-scheduler'),
			'menu_name'         => __( 'Sessions' , 'conf-scheduler'),
      'view_item'         => __( 'View Session' , 'conf-scheduler'),
      'not_found'         => __( 'No sessions found' , 'conf-scheduler'),
      'no_terms'          => __( 'No sessions' , 'conf-scheduler'),
      'items_list_navigation'   => __( 'Sessions list navigation' , 'conf-scheduler'),
      'items_list'        => __( 'Sessions list' , 'conf-scheduler'),
      'back_to_items'     => __( 'Back to sessions' , 'conf-scheduler'),
		);


		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
      'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sessions' ),
      'meta_box_cb'       => false // hide meta box on edit workshop page
		);

		register_taxonomy( 'conf_sessions', array( 'conf_workshop' ), $args );

		// Add Themes, make it hierarchical
		$labels = array(
			'name'              => _x( 'Themes', 'taxonomy general name' , 'conf-scheduler'),
			'singular_name'     => _x( 'Theme', 'taxonomy singular name' , 'conf-scheduler'),
			'search_items'      => __( 'Search Themes' , 'conf-scheduler'),
			'all_items'         => __( 'All Themes' , 'conf-scheduler'),
			'parent_item'       => __( 'Parent Theme' , 'conf-scheduler'),
			'parent_item_colon' => __( 'Parent Theme:' , 'conf-scheduler'),
			'edit_item'         => __( 'Edit Theme' , 'conf-scheduler'),
			'update_item'       => __( 'Update Theme' , 'conf-scheduler'),
			'add_new_item'      => __( 'Add New Theme' , 'conf-scheduler'),
			'new_item_name'     => __( 'New Theme Name' , 'conf-scheduler'),
			'menu_name'         => __( 'Themes' , 'conf-scheduler'),
      'view_item'         => __( 'View Theme' , 'conf-scheduler'),
      'not_found'         => __( 'No themes found' , 'conf-scheduler'),
      'no_terms'          => __( 'No themes' , 'conf-scheduler'),
      'items_list_navigation'   => __( 'Themes list navigation' , 'conf-scheduler'),
      'items_list'        => __( 'Themes list' , 'conf-scheduler'),
      'back_to_items'     => __( 'Back to themes' , 'conf-scheduler'),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
      'show_in_rest'      => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'themes' ),
		);

		register_taxonomy( 'conf_streams', array( 'conf_workshop' ), $args );

    // Add Keywords taxonomy
    $labels = array(
      'name'              => _x( 'Keywords', 'taxonomy general name' , 'conf-scheduler'),
      'singular_name'     => _x( 'Keyword', 'taxonomy singular name' , 'conf-scheduler'),
      'search_items'      => __( 'Search Keywords' , 'conf-scheduler'),
      'all_items'         => __( 'All Keywords' , 'conf-scheduler'),
      'parent_item'       => __( 'Parent Keyword' ,'conf-scheduler'),
      'parent_item_colon' => __( 'Parent Keyword:' , 'conf-scheduler'),
      'edit_item'         => __( 'Edit Keyword' , 'conf-scheduler'),
      'update_item'       => __( 'Update Keyword' , 'conf-scheduler'),
      'add_new_item'      => __( 'Add New Keyword' , 'conf-scheduler'),
      'new_item_name'     => __( 'New Keyword Name' , 'conf-scheduler'),
      'menu_name'         => __( 'Keywords' , 'conf-scheduler'),
      'view_item'         => __( 'View Keyword' , 'conf-scheduler'),
      'not_found'         => __( 'No keywords found' , 'conf-scheduler'),
      'no_terms'          => __( 'No keywords' , 'conf-scheduler'),
      'items_list_navigation'   => __( 'Keywords list navigation' , 'conf-scheduler'),
      'items_list'        => __( 'Keywords list' , 'conf-scheduler'),
      'back_to_items'     => __( 'Back to keywords' , 'conf-scheduler'),
      'popular_items'     => __( 'Popular Keywords' , 'conf-scheduler'),
      'separate_items_with_commas' => __( 'Separate keywords with commas' , 'conf-scheduler'),
      'add_or_remove_items'  => __( 'Add or remove keywords' , 'conf-scheduler'),
      'choose_from_most_used'   => __( 'Choose from the most used keywords' , 'conf-scheduler'),
    );

    $args = array(
      'hierarchical'      => false,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'show_in_rest'      => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'keywords' ),
    );

    register_taxonomy( 'conf_areas', array( 'conf_workshop' ), $args );

		// Register Workshops
		$labels = array(
	    'name'               => _x( 'Workshops' ,'taxonomy general name', 'conf-scheduler'),
	    'singular_name'      => _x( 'Workshop' , 'taxonomy singular name', 'conf-scheduler'),
	    'add_new'            => __( 'Add New' , 'conf-scheduler'),
	    'add_new_item'       => __( 'Add New Workshop' , 'conf-scheduler'),
	    'edit_item'          => __( 'Edit Workshop' , 'conf-scheduler'),
	    'new_item'           => __( 'New Workshop' , 'conf-scheduler'),
	    'all_items'          => __( 'Workshops' , 'conf-scheduler'),
	    'view_item'          => __( 'View Workshop' , 'conf-scheduler'),
      'view_items'         => __( 'View Workshops' , 'conf-scheduler'),
	    'search_items'       => __( 'Search Workshops', 'conf-scheduler' ),
	    'not_found'          => __( 'No workshops found', 'conf-scheduler' ),
	    'not_found_in_trash' => __( 'No workshops found in Trash', 'conf-scheduler' ),
	    'menu_name'          => _x( 'Conference', 'menu title', 'conf-scheduler' )
		);

    $template = array(
        array( 'core/heading', array(
            'placeholder' => __('Workshop title...', 'conf-scheduler'),
        ) ),
        array( 'core/columns', array(), array(
          array( 'core/paragraph', array(
              'placeholder' => __('Location', 'conf-scheduler'),
              'layout' => 'column-1'
          ) ),
          array( 'core/paragraph', array(
              'placeholder' => __('Workshop ID...', 'conf-scheduler'),
              'layout' => 'column-2'
          ) ),
      ) )
    );

    $slug = get_option( 'conf_scheduler_workshop_slug') ?: _x( 'workshops', 'URL slug', 'conf-scheduler' );

		$args = array(
		'labels' => $labels,
		'public' => true,
		'exclude_from_search' => false,
		'taxonomies'=> array('conf_sessions', 'conf_streams', 'conf_areas'),
		'supports' =>  array('thumbnail', 'title', 'editor'),
		'menu_position' => 20,
    'show_in_rest' => true,
    'rest_base'    => 'workshops',
    'template_lock' => 'all',
    'rewrite' => array('slug' => $slug),
    'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode( '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 80 80" xml:space="preserve"><path fill="#a0a5aa" d="M35,2.5h10v20H35V2.5z M26.094,38.594c0-1.667-0.599-3.099-1.797-4.297S21.667,32.5,20,32.5s-3.099,0.599-4.297,1.797s-1.797,2.63-1.797,4.297s0.599,3.073,1.797,4.219s2.63,1.719,4.297,1.719s3.099-0.573,4.297-1.719S26.094,40.261,26.094,38.594z M30,60.312c0-3.75-0.963-6.901-2.891-9.453s-4.245-3.828-6.953-3.828s-5.026,1.276-6.953,3.828s-2.891,5.703-2.891,9.453c0,2.292,1.641,3.906,4.922,4.844s6.562,0.938,9.844,0S30,62.604,30,60.312z M37.5,57.5c-0.729,0-1.328,0.234-1.797,0.703S35,59.271,35,60s0.234,1.328,0.703,1.797S36.771,62.5,37.5,62.5h30.469c0.625,0,1.198-0.234,1.719-0.703s0.781-1.067,0.781-1.797s-0.26-1.328-0.781-1.797S68.594,57.5,67.969,57.5H37.5z M37.5,47.5c-0.729,0-1.328,0.234-1.797,0.703S35,49.271,35,50s0.234,1.328,0.703,1.797S36.771,52.5,37.5,52.5h30.469c0.625,0,1.198-0.234,1.719-0.703s0.781-1.067,0.781-1.797s-0.26-1.328-0.781-1.797S68.594,47.5,67.969,47.5H37.5z M37.5,37.5c-0.729,0-1.328,0.234-1.797,0.703S35,39.271,35,40s0.234,1.328,0.703,1.797S36.771,42.5,37.5,42.5h30.469c0.625,0,1.198-0.234,1.719-0.703s0.781-1.067,0.781-1.797s-0.26-1.328-0.781-1.797S68.594,37.5,67.969,37.5H37.5z M75,12.5c1.354,0,2.526,0.495,3.516,1.484S80,16.146,80,17.5v55c0,1.354-0.495,2.526-1.484,3.516S76.354,77.5,75,77.5H5c-1.354,0-2.526-0.495-3.516-1.484S0,73.854,0,72.5v-55c0-1.354,0.495-2.526,1.484-3.516S3.646,12.5,5,12.5h25v5c-1.354,0-2.526,0.495-3.516,1.484S25,21.146,25,22.5s0.495,2.526,1.484,3.516S28.646,27.5,30,27.5h5h10h5c1.354,0,2.526-0.495,3.516-1.484S55,23.854,55,22.5s-0.495-2.526-1.484-3.516S51.354,17.5,50,17.5v-5H75z"/></svg>')
		);

		register_post_type( 'conf_workshop', $args );

	}

  /**
   * Add session meta fields to REST responses
   * @hooked rest_api_init
   * @return null
   */
  function register_session_meta_in_rest() {
      $meta_fields = array(
        'hide_time' => array(
          'description' => __( 'Hide session time?' ),
          'type'        => 'integer'
        ),
        'hide_title' => array(
          'description' => __( 'Hide session title?' ),
          'type'        => 'integer'
        ),
        'start' => array(
          'description' => __( 'Session start MYSQL timestamp.' ),
          'type'        => 'string'
        ),
        'length' => array(
          'description' => __( 'Session length in minutes.' ),
          'type'        => 'integer'
        ),
        'group_by_location' => array(
          'description' => __( 'Group session workshops by location.' ),
          'type'        => 'integer'
        ),
        'collapse' => array(
          'description' => __( 'Collapse subsessions?' ),
          'type'        => 'string'
        ),
      );
      foreach ($meta_fields as $key => $schema) {
        register_rest_field( 'conf_sessions',
            $key,
            array(
                'get_callback'    => array($this,'rest_get_term_meta_field'),
                'update_callback' => array($this,'rest_update_term_meta_field'),
                'schema' => $schema
            )
        );
      }
  }

  /**
   * Update session meta from REST
   * @param  [type] $value      [description]
   * @param  [type] $object     [description]
   * @param  [type] $field_name [description]
   * @return [type]             [description]
   */
  function rest_update_term_meta_field( $value, $object, $field_name ) {
      if ( ! $value || ! is_string( $value ) ) {
          return;
      }
      return update_term_meta( $object->ID, $field_name, $value );
  }

  /**
   * Get session meta for REST
   * @param  [type] $object     [description]
   * @param  [type] $field_name [description]
   * @param  [type] $request    [description]
   * @return [type]             [description]
   */
  function rest_get_term_meta_field( $object, $field_name, $request ) {
      return get_term_meta( $object[ 'id' ], $field_name, true );
  }

  /**
   * Check if clean install or upgrade needed on activation
   * @hooked init
   * @return null
   */
	function activiation_check() {
    $installed = get_option('conf_scheduler_version');
		if ($installed && CONF_SCHEDULER_VERSION != $installed)
      $this->do_upgrade();

    if(!$installed) {
      $this->install();
    }
	}

  /**
   * Setup a clean installation
   * @return null
   */
  function install() {
    update_option('conf_scheduler_version', CONF_SCHEDULER_VERSION);
  }

  /**
   * Upgrade from a previous version
   * @return null
   */
	function do_upgrade() {
    $installed = get_option('conf_scheduler_version');
    if (version_compare($installed, '2.4', '<') ) {
      // explode options array to separate options
      $options = get_option('conf_scheduler_options', array());
      foreach ($options as $slug => $value) {
        update_option('conf_scheduler_'.$slug, $value);
      }
    }

	  update_option('conf_scheduler_version', CONF_SCHEDULER_VERSION);
	}

  /**
   * Remove data from DB on plugin deletion
   * @return [type] [description]
   */
  function uninstall_clean_db() {
    // nothing for now - leave it there
  }

  /**
   * Load a template
   * @param  string $template_name filename of template to load
   * @param  array  $data          data to pass to the template
   * @return null
   */
  static function get_template( $template_name , $data = array() ) {
    $template_path = locate_template( array( 'conference-scheduler/'.$template_name ) );

  	if ( ! $template_path )
  		$template_path = CONF_SCHEDULER_PATH . 'templates/' . $template_name;

    $template_path = apply_filters('conf_scheduler_template', $template_path, $template_name );

  	if ( ! file_exists( $template_path ) ) {
  		_doing_it_wrong( 'Conference_Scheduler::get_template', sprintf( '<code>%s</code> does not exist.', $template_path ), '1.2.0' );
  		return;
  	}

    // extract args data and include template
    if ( is_array( $data ) && isset( $data ) )
  		extract( $data );

  	include $template_path;
  }

  /**
   * Modify the_content for single workshop view, outputting the workshop template
   * @hooked the_content
   * @param  string $content    WP Post post_content
   * @return string             modified post_content
   */
  function single_workshop_view( $content ) {
    global $post;
    if (is_singular( 'conf_workshop' )) {
      $sessions = get_the_terms( $post, 'conf_sessions' );
      $session = $sessions && !is_wp_error($sessions) ? $sessions[0] : (object) array();
      $content = apply_filters('conf_scheduler_single_view_content', $this->render_workshop($post, $session), $post, $session);
    }
    return $content;
  }

  /**
   * Set get_post_time to workshop start time for single view
   * @hooked get_post_time
   * @param string $formatted_time       formatted post_modified time string
   * @param string $format               PHP time format string
   * @param WP_Post|null $post           WP_Post object
   */
  function set_post_time_as_start_time( $formatted_time, $format = '', $post = null) {
    if (is_singular('conf_workshop')) {
      $post = get_post( $post );
      $_format = ! empty( $format ) ? $format : get_option( 'time_format' );
      return date_i18n( $_format, $post->start_time );
    }
    return $formatted_time;
  }

  /**
   * Output the form HTML for Session metadata fields
   * @hooked conf_sessions_add_form_fields
   * @return null
   */
  function sessions_meta_box_html() {
    wp_nonce_field( "save_conf_session_meta", 'conf_scheduler_sessions' );
    ?>

    <div class="form-field">
      <label for="start"><?php _e('Start date/time','conf-scheduler')?>:</label>
      <div class="datetime-field-wrap">
        <input type='text' name='start' id='start' value=''/>
        <label for="start"><span class="dashicons dashicons-calendar-alt"></span></label>
      </div>
    </div>

    <?php if ($this->option('view_mode') != 'timeline') : ?>
    <div class="form-field">
      <label for="length"><?php _e('Length','conf-scheduler')?>:</label>
      <input type='number' name='length' id='length' value=''/>
      <p><?php _e('Length of session in minutes.','conf-scheduler')?></p>
    </div>
    <?php endif; ?>

    <div class="form-field group-wrap">
  		<label for="collapse"><?php _e('Group Workshops','conf-scheduler')?></label>
        <ul>
          <li><label><input type="checkbox" id="group_by_location" name="group_by_location" value="1"><?php _e('Group by location','conf-scheduler')?></label></li>
        </ul>
        <p><?php _e('Group workshops in this session together by location.','conf-scheduler')?></p>
    </div>

    <div class="form-field collapse-wrap">
  		<label for="collapse"><?php _e('Show Sub-sessions','conf-scheduler')?></label>
      <ul>
        <li><label><input type="radio" id="collapse-0" name="collapse" value="separate" checked><?php _e('Separate','conf-scheduler')?></label></li>
        <li><label><input type="radio" id="collapse-1" name="collapse" value="collapse"><?php _e('Collapsed','conf-scheduler')?></label></li>
      </ul>
      <p><?php _e('Show sub-sessions collapsed in with this session, or show them as separate sessions.','conf-scheduler')?></p>
    </div>

    <div class="form-field display-wrap">
  		<label for="display"><?php _e('Display','conf-scheduler')?></label>
        <ul>
          <li><label><input type="checkbox" id="hide_time" name="hide_time" value="1"><?php _e('Hide time','conf-scheduler')?></label></li>
          <li><label><input type="checkbox" id="hide_title" name="hide_title" value="1"><?php _e('Hide title','conf-scheduler')?></label></li>
        </ul>
        <p><?php _e('Customize what information is shown for this session.','conf-scheduler')?></p>
    </div>

    <?php
  }

  /**
   * Output the form HTML for Session metadata fields on edit Session page
   * @hooked conf_sessions_edit_form_fields
   * @return null
   */
  function sessions_edit_meta_box_html() {
    global $tag;

    $fields = array('start', 'length', 'collapse', 'hide_title', 'hide_time', 'group_by_location');
    $values = array();

    foreach ($fields as $field) {
      $values[$field] = get_term_meta( $tag->term_id, $field, true );
    }

    // reformat time for datetime field
    $values['start'] = date('Y-m-d '.get_option('time_format'), strtotime($values['start']));

    wp_nonce_field( 'save_conf_session_meta', 'conf_scheduler_sessions' );
    ?>
    <tr class="form-field">
      <th scope="row"><label for="start"><?php _e('Start date/time','conf-scheduler')?>:</label></th>
      <td><div class="datetime-field-wrap"><input type='text' name='start' id='start' value='<?php echo esc_attr($values['start']);?>' data-date='<?php echo esc_attr($values['start']);?>'/><label for="start"><span class="dashicons dashicons-calendar-alt"></span></label></div></td>
    </tr>

    <?php if ($this->option('view_mode') != 'timeline') : ?>
    <tr class="form-field">
      <th scope="row"><label for="length"><?php _e('Length','conf-scheduler')?>:</label></th>
      <td><input type='number' name='length' id='length' value='<?php echo esc_attr($values['length']);?>'/>
      <p class="description"><?php _e('Length of session in minutes.','conf-scheduler')?></p></td>
    </tr>
    <?php endif; ?>

    <tr class="form-field group_by_location-wrap">
  		<th scope="row"><label for="group_by_location"><?php _e('Group Workshops','conf-scheduler')?></label></th>
      <td>
        <ul>
          <li><label><input type="checkbox" id="group_by_location" name="group_by_location" value="1"<?php if($values['group_by_location']) echo ' checked';?>/><?php _e('Group by location','conf-scheduler')?></label></li>
        </ul>
        <p class="description"><?php _e('Group workshops in this session together by location.','conf-scheduler')?></p>
      </td>
    </tr>

    <tr class="form-field collapse-wrap">
  		<th scope="row"><label for="collapse"><?php _e('Show Sub-sessions','conf-scheduler')?></label></th>
      <td>
        <ul>
          <li><label><input type="radio" id="collapse-0" name="collapse" value="separate"<?php if(!isset($values['collapse']) || $values['collapse']!='collapse') echo ' checked';?>><?php _e('Separate','conf-scheduler')?></label></li>
          <li><label><input type="radio" id="collapse-1" name="collapse" value="collapse"<?php if($values['collapse']=='collapse') echo ' checked';?>><?php _e('Collapsed','conf-scheduler')?></label></li>
        </ul>
        <p class="description"><?php _e('Show sub-sessions collapsed in with this session, or show them as separate sessions.','conf-scheduler')?></p>
      </td>
    </tr>

    <tr class="form-field display-wrap">
  		<th scope="row"><label for="collapse"><?php _e('Display Name','conf-scheduler')?></label></th>
      <td>
        <ul>
          <li><label><input type="checkbox" id="hide_time" name="hide_time" value="1"<?php if($values['hide_time']) echo ' checked';?>><?php _e('Hide time','conf-scheduler')?></label></li>
          <li><label><input type="checkbox" id="hide_title" name="hide_title" value="1"<?php if($values['hide_title']) echo ' checked';?>><?php _e('Hide title','conf-scheduler')?></label></li>
        </ul>
      </td>
    </tr>

    <?php
  }

  /**
   * Register meta boxes for the Edit Workshop screen
   * @return null
   */
  function workshop_meta_boxes() {
    add_meta_box(
  		'workshop_meta_box',
  		__('Workshop Details', 'conf-scheduler'),
  		array($this, 'workshop_meta_box_html'),
  		'conf_workshop',
  		'advanced',
  		'high',
      array(
        '__block_editor_compatible_meta_box' => true,
        '__back_compat_meta_box' => false
      )
  	);

    add_meta_box(
  		'workshop_side_meta_box',
  		__('Workshop Info', 'conf-scheduler'),
  		array($this, 'workshop_side_meta_box_html'),
  		'conf_workshop',
  		'side',
  		'high',
      array(
        '__block_editor_compatible_meta_box' => true,
        '__back_compat_meta_box' => false
      )

  	);
  }

  /**
   * Output form HTML for lower, wide workshop metadata fields
   * @return null
   */
  function workshop_meta_box_html() {
    global $post;

  	wp_nonce_field( 'save_conf_workshop_meta', 'conf_scheduler' );
  	$fields = array('presenter_bio', 'file_attachments_id');
    $values = array();

    foreach ($fields as $field) {
      $values[$field] = get_post_meta( $post->ID, $field, true );
    }

    $values['file_attachments_id'] = $values['file_attachments_id'] ? $values['file_attachments_id'] : '';

    ?>
    <label for="presenter_bio"><?php _e('Presenter Bio','conf-scheduler')?>:</label>
    <textarea name="presenter_bio" id="presenter_bio"><?php echo esc_textarea($values['presenter_bio']);?></textarea>
    <div id="file_attachments_section">
      <label for="upload_file_button"><?php _e('Files','conf-scheduler')?>:</label>
    	<button id="upload_file_button" class="button"><?php _e( 'Add Files', 'conf-scheduler' ); ?></button>
    	<input type='hidden' name='file_attachments_id' id='file_attachments_id' value='<?php echo esc_attr($values['file_attachments_id']);?>'/>
      <div id="file_attachments"></div>
    </div>
    <?php
    do_action('conf_scheduler_workshop_fields_html', $post);
  }

  /**
   * Output form HTML for sidebar workshop metadata fields
   * @return null
   */
  function workshop_side_meta_box_html() {
    global $post;

    // Get values for fields for this workshop
    $fields = array('workshop_id', 'location', 'presenter', 'limit', 'location_url');
    $values = array();

    foreach ($fields as $field) {
      $values[$field] = get_post_meta( $post->ID, $field, true );
    }

    $values['session'] = '';
    if ($this->option('view_mode') != 'timeline') {
      $workshop_session = get_the_terms($post->ID, 'conf_sessions');
      if($workshop_session) $values['session'] = $workshop_session[0]->term_id;

      $sessions = get_terms( array(
        'taxonomy'=> 'conf_sessions',
        'hide_empty' => false,
      ));

      // add start datetime data to sessions
      $sessions = array_map(
          function($obj){
            $obj->start = strtotime(get_term_meta($obj->term_id, 'start', true));
            return $obj;
          },
          $sessions);

      // sort sessions by datetime
      $session_tree = array();
      $this->sort_terms_hierarchicaly($sessions, $session_tree);
    }

    ?>
    <label for="workshop_id"><?php _e('Workshop ID','conf-scheduler')?>:</label>
    <input type='text' name='workshop_id' id='workshop_id' value='<?php echo esc_attr($values['workshop_id']);?>'/>

    <label for="location"><?php _e('Location','conf-scheduler')?>:</label>
    <input type='text' name='location' id='location' value='<?php echo esc_attr($values['location']);?>'/>

    <label for="location_url"><?php _e('Location URL','conf-scheduler')?>:</label>
    <input type='text' name='location_url' id='location_url' value='<?php echo esc_attr($values['location_url']);?>' placeholder="https://"/>

    <label for="presenter"><?php _e('Presenter','conf-scheduler')?>:</label>
    <input type='text' name='presenter' id='presenter' value='<?php echo esc_attr($values['presenter']);?>'/>

    <label for="limit"><?php _e('Participant Limit','conf-scheduler')?>:</label>
    <input type='number' name='limit' id='limit' value='<?php echo esc_attr($values['limit']);?>'/>

    <?php if ($this->option('view_mode') != 'timeline') : ?>
    <div id="add_session"><a href="<?php echo admin_url('edit-tags.php?taxonomy=conf_sessions&post_type=conf_workshop');?>"><?php _e('+ Add Session', 'conf-scheduler');?></a></div><label for="session"><?php _e('Session','conf-scheduler')?>:</label>
    <select name="session" id="session" style="width: 100%;" >
      <?php
        $walker = function($sessions, $d) use ( &$walker, $values ) {
          foreach($sessions as $session) {
            $selected = (intval($values['session']) == $session->term_id ) ? 'selected' : '';
            $n = str_repeat('-', $d).' '.$session->name . ' (' . date_i18n('j-M '.get_option('time_format'),$session->start).')';
            echo "<option value='{$session->term_id}' $selected>$n</option>";
            if($session->children) $walker($session->children, $d+1);
          }
        };
        $walker($session_tree, 0);
      ?>
    </select>
    <?php endif; ?>

    <?php if ($this->option('view_mode') == 'timeline') :
      if ( version_compare(get_bloginfo( 'version' ), '5.3', '>=') ) {
        $now = current_datetime();
        $now_stamp = $now->getTimestamp();
      } else {
        $now_stamp = current_time('timestamp', false);
      }
      $values['start_time'] = (int) get_post_meta( $post->ID, 'start_time', true );
      $values['start_time'] = $values['start_time'] ?: $now_stamp; // defualt to current time
      $values['end_time'] = (int) get_post_meta( $post->ID, 'end_time', true );
      $length = $values['end_time'] ? intval(($values['end_time'] - $values['start_time'])/60) : '';
      ?>

    <label for="start_time"><?php _e('Start date/time','conf-scheduler')?>:</label>
    <div class="datetime-field-wrap">
      <input type='text' name='start_time' id='start_time' value='<?php echo esc_attr(date_i18n('Y-m-d '.get_option('time_format'), $values['start_time']));?>'/>
      <label for="start_time"><span class="dashicons dashicons-calendar-alt"></span></label>
    </div>

    <label for="length"><?php _e('Length (min)','conf-scheduler')?>:</label>
    <input type='number' name='length' id='length' value='<?php echo $length;?>'/>

    <?php endif;

    do_action('conf_scheduler_workshop_fields_side_html', $post);

  }

  /**
   * Process and save metadata for Workshops
   * @hooked save_post_conf_workshop
   * @param  int $post_id     ID of workshop post
   * @param  WP_Post $post    Post object for workshop
   * @return null
   */
  function save_workshop_meta( $post_id, $post ) {
  	// Check we should save data (capability, nonce, not revision)
  	if ( ! current_user_can( 'edit_post', $post_id ) ) {
  		return $post_id;
  	}
  	if ( ! isset($_POST['conf_scheduler']) || ! wp_verify_nonce( $_POST['conf_scheduler'], 'save_conf_workshop_meta' ) ) {
  		return $post_id;
  	}
    if ( 'revision' === $post->post_type ) return;

    // Validate attachment ids
    $file_attachments_id = implode(',', array_map(function($e){if(absint($e) > 0) return absint($e); }, explode(',', $_POST['file_attachments_id'])));
    update_post_meta( $post_id, 'file_attachments_id', $file_attachments_id );

    // Validate and sanitize text fields
  	update_post_meta( $post_id, 'presenter_bio', sanitize_text_field($_POST['presenter_bio']) );
    update_post_meta( $post_id, 'workshop_id', sanitize_text_field($_POST['workshop_id']) );
    update_post_meta( $post_id, 'location', sanitize_text_field($_POST['location']) );
    update_post_meta( $post_id, 'location_url', sanitize_url($_POST['location_url']) );
    update_post_meta( $post_id, 'presenter', sanitize_text_field($_POST['presenter']) );

    // Validate and sanitize number fields
    $limit = sanitize_text_field($_POST['limit']);
    $limit = is_numeric($limit) ? absint($limit) : '';
    update_post_meta( $post_id, 'limit', $limit );

    if (isset($_POST['session'])) {
      $session = absint(sanitize_text_field($_POST['session']));
      $session_check = get_term($session, 'conf_sessions'); // returns null if not a valid session, uses term cache

      if(isset($session_check)) {
        wp_set_object_terms( $post_id, $session, 'conf_sessions', false);
        $start = strtotime( get_term_meta( $session, 'start', true ) );
        $end = $start + (get_term_meta( $session, 'length', true ) * 60 );
        update_post_meta( $post_id, 'start_time', (int) $start );
        update_post_meta( $post_id, 'end_time', (int) $end );
      }
    }

    // save start/length data if set (timeline mode)
    if ( isset($_POST['start_time']) && apply_filters('conf_scheduler_timeline_process_start', true, $post_id)) {
      $start_time = strtotime(sanitize_text_field($_POST['start_time']));
      $start_time = $start_time ? $start_time : 0;
      $length = absint(sanitize_text_field($_POST['length']));
      $end_time = $start_time + $length * 60;
      update_post_meta( $post_id, 'start_time', $start_time );
      update_post_meta( $post_id, 'end_time', $end_time );

      // relink to correct session
      $this->update_timeline_session($post_id);
    }

    do_action('conf_scheduler_save_workshop_meta', $post_id, $_POST);

  }

  /**
   * Link workshop to correct session
   * @param  int $post_id   WP post_id of the workshop
   * @return null
   */
  function update_timeline_session( $post_id ) {
    $start_time = get_post_meta( $post_id, 'start_time', true );
    $session_slug = date('Ymd-Hi', $start_time);
    $session_check = apply_filters('conf_scheduler_timeline_session', get_term_by('slug', $session_slug, 'conf_sessions'), $post_id); // returns null if not a valid session, uses term cache

    if($session_check) {
      // link to existing session
      wp_set_object_terms( $post_id, $session_check->term_id, 'conf_sessions', false);
    } else {
      // check for a named session at that time
      $named_session_array = get_terms( array( 'taxonomy' => 'conf_sessions', 'meta_query' => array(array('key' => 'start', 'value' => date('Y-m-d H:i', $start_time))), 'hide_empty' => false, 'number' => 1) ); // returns empty array if not found, uses term cache
      $named_session = $named_session_array ? apply_filters('conf_scheduler_timeline_session', $named_session_array[0], $post_id) : false;

      if($named_session) {
        // link to existing session
        wp_set_object_terms( $post_id, $named_session->term_id, 'conf_sessions', false);
      } else {
        // create new session
        $end_time = get_post_meta( $post_id, 'end_time', true );
        $length = ($end_time - $start_time) / 60;
        $new_term = wp_insert_term( $session_slug, 'conf_sessions' );
        if(!is_wp_error( $new_term )) {
          $term_id = $new_term['term_id'];
          wp_set_object_terms( $post_id, $term_id, 'conf_sessions' );
          update_term_meta( $term_id, 'start', date('Y-m-d H:i', $start_time));
          update_term_meta( $term_id, 'length', $length );
          update_term_meta( $term_id, 'hide_title', 1 );
        }
      }
    }
  }

  /**
   * Process and save metadata for Sessions
   * @hooked create_conf_sessions, edit_conf_sessions
   * @param  int $term_id     ID of session term
   * @return null
   */
  function save_session_meta( $term_id ) {
  	// Check we should save data (capability, nonce, not revision)
  	if ( ! current_user_can( 'edit_term', $term_id ) ) {
  		return $term_id;
  	}
  	if ( !isset($_POST['conf_scheduler_sessions']) || ! wp_verify_nonce( $_POST['conf_scheduler_sessions'], 'save_conf_session_meta' ) ) {
  		return $term_id;
  	}

    // Sanitize, validate and format fields
    $start = strtotime(sanitize_text_field($_POST['start']));
    $start = $start !== false ? date('Y-m-d H:i',$start) : '';

    if ( isset($_POST['length']) ) {
      // length not set in timeline mode - don't clear otherwise
      $length = (int) sanitize_text_field($_POST['length']);
      $length = $length !== 0 ? $length : '';
      update_term_meta( $term_id, 'length',  $length);
    }

    $collapse = sanitize_text_field($_POST['collapse']);
    $collapse = $collapse == 'collapse' ? 'collapse' : 'separate';

    $hide_time = isset($_POST['hide_time']) && $_POST['hide_time'] == '1' ? 1 : 0;
    $hide_title = isset($_POST['hide_title']) && $_POST['hide_title'] == '1' ? 1 : 0;
    $group_by_location = isset($_POST['group_by_location']) && $_POST['group_by_location'] == '1' ? 1 : 0;

  	update_term_meta( $term_id, 'start', $start );
    update_term_meta( $term_id, 'collapse', $collapse );
    update_term_meta( $term_id, 'hide_time', $hide_time );
    update_term_meta( $term_id, 'hide_title', $hide_title );
    update_term_meta( $term_id, 'group_by_location', $group_by_location );

  }

  /**
   * Filter Sessions table column heads
   * @hooked manage_edit-conf_sessions_columns
   * @param  array $defaults    array of columns
   * @return array              filtered columns
   */
	function sessions_custom_columns_head($defaults) {
	    $defaults['conf_start']  = __('Start', 'conf-scheduler');
	    if ($this->option('view_mode') != 'timeline')
        $defaults['conf_length'] = __('Length', 'conf-scheduler');
			unset($defaults['description']);
	    return $defaults;
	}

  /**
   * Output data for session custom columns
   * @hooked manage_conf_sessions_custom_column
   * @param  string $content        default content for the column
   * @param  string $column_name    name of the column being output
   * @param  int $term_id           ID of the current session
   * @return null
   */
	function sessions_custom_columns_content($content,$column_name,$term_id) {
    $start = strtotime(get_term_meta( $term_id, 'start', true ));
    $length = get_term_meta( $term_id, 'length', true );

		if ($column_name == 'conf_start') {
        echo date('d-M-Y',$start).'<br/>'.date(get_option('time_format'),$start);
    }
    if ($column_name == 'conf_length') {
        echo sprintf(__('%dmin', 'conf-scheduler'), $length);
    }
	}

  /**
   * Filter sortable columns for sessions
   * @hooked manage_edit-conf_sessions_sortable_columns
   * @param  array $columns     array of columns that are sortable
   * @return array              filtered array of sortable columns
   */
	function sessions_sortable_columns( $columns ) {
	    $columns['conf_start'] = 'conf_start';
			$columns['conf_length'] = 'conf_length';

	    return $columns;
	}

  /**
   * Filter Workshops table columns
   * @hooked manage_conf_workshop_posts_columns
   * @param  array $defaults  array of columns
   * @return array            filtered array of columns
   */
  function workshops_custom_columns_head($defaults) {

    unset($defaults['date']);
    unset($defaults['taxonomy-conf_areas']);
    unset($defaults['taxonomy-conf_streams']);
    unset($defaults['taxonomy-conf_sessions']);

    $columns = array(
      'cb' => $defaults['cb'],
      'title' => $defaults['title'],
      'workshop_id' => __('ID','conf-scheduler'),
      'start_time' => __('Timing','conf-scheduler'),
      'location' => __('Location','conf-scheduler'),
      'presenter' => __('Presenter','conf-scheduler'),
    ) + $defaults;

    return $columns;
	}

  /**
   * Output data for Workshops custom columns
   * @hooked manage_conf_workshop_posts_custom_column
   * @param  string $column_name    name of the column being output
   * @param  int $workshop_id       ID of the column workshop being output
   * @return null
   */
	function workshops_custom_columns_content($column_name,$workshop_id) {
    if ($column_name == 'workshop_id') {
      echo get_post_meta($workshop_id, 'workshop_id',true);
    }
    if ($column_name == 'location') {
      echo get_post_meta($workshop_id, 'location',true);
    }
    if ($column_name == 'presenter') {
      echo get_post_meta($workshop_id, 'presenter',true);
    }
    if ($column_name == 'start_time') {
      if ($this->option('view_mode') == 'timeline') {
        $start_time = (int) get_post_meta($workshop_id, 'start_time',true);
        $end_time = (int) get_post_meta($workshop_id, 'end_time',true);
        $length = (int) ($end_time - $start_time)/60;
        if ($start_time) {
          echo '<a href="'.admin_url( 'edit.php?post_type=conf_workshop&conf_sessions='.date('Ymd-Hi', $start_time)).'">' .
            date_i18n( 'Y-m-d '.get_option('time_format'), $start_time) . '</a><br/>'. $length . _x( 'min', 'minutes, abbrev', 'conf-scheduler' );
        } else { echo '-'; }
      } else {
        $sessions = wp_get_post_terms( $workshop_id, 'conf_sessions');
        if ($sessions && !is_wp_error( $sessions )) {
          $session = reset($sessions);
          $start = get_term_meta( $session->term_id, 'start', true );
          $length = get_term_meta( $session->term_id, 'length', true );
          echo '<a href="'.admin_url( 'edit.php?post_type=conf_workshop&conf_sessions='.$session->slug).'">'.$session->name. '<br/>' .
            date_i18n( 'Y-m-d '.get_option('time_format'), strtotime($start)) . '</a><br/>'. $length . _x( 'min', 'minutes, abbrev', 'conf-scheduler' );
        } else {
          echo '-';
        }
      }
    }
	}

  /**
   * Filter sortable Workshops columns
   * @hooked manage_edit-conf_workshop_sortable_columns
   * @param  array $columns     array of sortable columns for workshop table
   * @return array              filtered array of sortable columns for workshops table
   */
  function workshop_sortable_columns( $columns ) {
      $columns['workshop_id'] = 'workshop_id';
      $columns['location'] = 'location';
      $columns['presenter'] = 'presenter';
      $columns['start_time'] = 'start_time';

      return $columns;
  }

  /**
   * Modify Admin Sessions query to support custom sortable columns
   * @hooked pre_get_terms
   * @param  WP_Query $query    query object
   * @return null
   */
	function sessions_admin_orderby( $query ) {
	    if( ! is_admin() )
	        return;

        $orderby = ( isset( $_GET['orderby'] ) ) ? trim( sanitize_text_field( $_GET['orderby'] ) ) : '';

		    if( 'conf_start' == $orderby ) {
		        $query->query_vars['meta_key'] = 'start';
		        $query->query_vars['orderby'] = 'meta_value';
            $query->meta_query->parse_query_vars( $query->query_vars );
		    }
				if( 'conf_length' == $orderby ) {
		        $query->query_vars['meta_key'] = 'length';
		        $query->query_vars['orderby'] = 'meta_value_num';
            $query->meta_query->parse_query_vars( $query->query_vars );
		    }
	}

  /**
   * Extend search box on Admin Workshop list screen to search presenter and location
   * @hooked posts_clauses
   * @param  array    $clauses  array of all SQL clauses
   * @param  WP_QUERY $query    reference - WP_QUERY object
   * @return array              modified $clauses array
   */
  function workshop_admin_search_clauses ( $clauses, $query ) {
    global $wpdb, $pagenow;
    if( is_admin() && $pagenow == 'edit.php' && $query->is_search() && $query->is_main_query() && $query->get('post_type') == 'conf_workshop' ){
      $clauses['join'] .='LEFT JOIN '.$wpdb->postmeta. ' AS search_meta ON '. $wpdb->posts . '.ID = search_meta.post_id ';
      $clauses['where'] = preg_replace(
      "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
      "(".$wpdb->posts.".post_title LIKE $1) OR ( search_meta.meta_key = 'location' AND search_meta.meta_value LIKE $1 ) OR ( search_meta.meta_key = 'presenter' AND search_meta.meta_value LIKE $1 )", $clauses['where'] );
      $clauses['groupby'] = $wpdb->posts.'.ID';
    }

    return $clauses;
  }

  /**
   * Modify Admin Workshops query to support custom sortable columns
   * @hooked pre_get_posts
   * @param  WP_Query $query    query object
   * @return null
   */
  function workshops_admin_orderby( $query ) {
      if ( ! is_admin() || ! $query->is_main_query() ) {
          return;
      }

      if( $query->get( 'orderby' ) == 'workshop_id' ) {
        $query->query_vars['meta_key'] = 'workshop_id';
        $query->query_vars['orderby'] = array('meta_value_num' => $query->get( 'order' ), 'meta_value' => $query->get( 'order' ));
	    }
      if( $query->get( 'orderby' ) == 'location' ) {
        $query->query_vars['meta_key'] = 'location';
        $query->query_vars['orderby'] = array('meta_value' => $query->get( 'order' ), 'meta_value' => $query->get( 'order' ));
	    }
      if( $query->get( 'orderby' ) == 'presenter' ) {
        $query->query_vars['meta_key'] = 'presenter';
        $query->query_vars['orderby'] = array('meta_value' => $query->get( 'order' ), 'meta_value' => $query->get( 'order' ));
	    }
      if( $query->get( 'orderby' ) == 'start_time' ) {
        $query->query_vars['meta_key'] = 'start_time';
        $query->query_vars['orderby'] = 'meta_value_num';
        $query->query_vars['order'] = $query->get( 'order' );
	    }

  }

  /**
   * Registers all static assets with WP in one go
   * @hooked wp_enqueue_scripts
   * @return null
   */
  function register_assets() {
    global $wp_version;
    if (version_compare($wp_version, '5.0') == -1 )
      wp_register_script('wp-hooks', CONF_SCHEDULER_URL .'static/js/hooks.min.js', array(), null);

    // Frontend assets
    wp_register_style('conf_scheduler', CONF_SCHEDULER_URL .'static/css/conf_scheduler.css', array(), CONF_SCHEDULER_VERSION );
    wp_register_style('select2v4-css', CONF_SCHEDULER_URL .'vendor/select2/select2.min.css', array(), '4.0.13' );
    //wp_register_style( 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7' );
    wp_register_style( 'fontawesome5', 'https://use.fontawesome.com/releases/v5.10.2/css/all.css', array(), '5.10.2' );
		wp_register_script('isotope', CONF_SCHEDULER_URL .'static/js/isotope.pkgd.min.js', array( 'jquery' ), null);
		wp_register_script('js-cookie', CONF_SCHEDULER_URL .'static/js/js.cookie.js' );
		wp_register_script('conf_scheduler', CONF_SCHEDULER_URL .'static/js/conf_scheduler.js', array('wp-hooks'), CONF_SCHEDULER_VERSION );
    wp_register_script('select2v4-js', CONF_SCHEDULER_URL .'vendor/select2/select2.full.min.js', array( 'jquery' ), '4.0.13');

    // Admin assets
    wp_register_style('conf_scheduler_admin-css', CONF_SCHEDULER_URL .'static/css/conf_scheduler_admin.css' );
    wp_register_style('conf_scheduler_admin_dark', CONF_SCHEDULER_URL .'static/css/conf_scheduler_admin_dark.css' );
    wp_register_script('conf_scheduler_admin-js', CONF_SCHEDULER_URL .'static/js/conf_scheduler_admin.js', array(), CONF_SCHEDULER_VERSION, true );
    wp_register_style('datetime_picker-css', CONF_SCHEDULER_URL .'vendor/bootstrap_datetime_picker/bootstrap_datetime_picker.css' );
    wp_register_script('datetime_picker-js', CONF_SCHEDULER_URL .'vendor/bootstrap_datetime_picker/cs_bootstrap_datetime_picker.min.js' );
  }

  /**
   * Enqueue dark mode style if requested
   * @param  int $user_id ID of user
   * @return null
   */
  function conf_scheduler_dark_mode ( $user_id ) {
    wp_enqueue_style( 'conf_scheduler_admin_dark' );
  }

  /**
   * Enqueues assests for the frontend
   * @hooked wp_enqueue_scripts
   * @return null
   */
  function hook_frontend_assets( $force = null ) {
  	global $post;

    $enqueue = $force ||
        ( isset($post->post_content) && has_shortcode( $post->post_content, 'conf_scheduler') ) ||
        ( function_exists('has_block') && has_block('conf-scheduler/display') ) ||
        is_singular('conf_workshop');

  	if( apply_filters('conf_scheduler_frontend_assets', $enqueue) ) {
      // load scripts and styles
      wp_enqueue_script('select2v4-js');
      wp_enqueue_style('select2v4-css');
      wp_enqueue_style('conf_scheduler');
      wp_enqueue_style( 'fontawesome5');
      wp_enqueue_script('isotope');
      wp_enqueue_script('js-cookie');

      $css = array();

      // Colors for day tabs
      if ($this->option('day_mode') == 'tabs') {
        $day_colour = get_theme_mod( 'conf_scheduler_day_color' ) ?: '#000000';
        $hex = array(substr($day_colour, 1, 2), substr($day_colour, 3, 2), substr($day_colour, 5, 2));
        $rgb = array_map(function($part) {
            return hexdec($part) / 255;
        }, $hex);

        $max = max($rgb);
        $min = min($rgb);

        $day_l = ($max + $min) / 2;
        $text_color = $day_l < 0.5 ? '#FFFFFF' : '#000000';

        $css[] = ".conf_scheduler.day_tabs ul.day_tabs li { color: $day_colour; border-color: $day_colour; }";
        $css[] = ".conf_scheduler.day_tabs ul.day_tabs { border-color: $day_colour; }";
        $css[] = ".conf_scheduler.day_tabs ul.day_tabs li.open { color: $text_color; background-color: $day_colour; }";
      }

      $rules = array(
        array(
          's' => '.conf_scheduler .session .workshop',
          'o' => 'conf_scheduler_background_color',
          'r' => 'background: %s;'
        ),
        array(
          's' => '.conf_scheduler .session .workshop:hover, .conf_scheduler .session .workshop.open, .conf_scheduler .session .workshop.picked',
          'o' => 'conf_scheduler_background_alt_color',
          'r' => 'background: %s;'
        ),
        array(
          's' => '.conf_scheduler .session .workshop h4.title',
          'o' => 'conf_scheduler_title_color',
          'r' => 'color: %s;'
        ),
        array(
          's' => '.conf_scheduler .conference_day > h3',
          'o' => 'conf_scheduler_day_color',
          'r' => 'color: %s;'
        ),
        array(
          's' => '.conf_scheduler .session > h3',
          'o' => 'conf_scheduler_session_color',
          'r' => 'color: %s;'
        ),
        array(
          's' => '.conf_scheduler .conf_block',
          'o' => 'conf_scheduler_border_color',
          'r' => 'border: 1px solid %s;'
        ),
        array(
          's' => '.conf_scheduler .session .conf_block .workshop',
          'o' => 'conf_scheduler_border_color',
          'r' => 'border-bottom: 1px solid %s;'
        ),
        array(
          's' => '.conf_scheduler .session .conf_block .workshop:last-child',
          'o' => 'conf_scheduler_border_color',
          'r' => ' border-bottom: none;'
        )
      );

      // workshops per row
      $per_row = (int)get_theme_mod( 'conf_scheduler_per_row', 3 );
      $css[] = "@media only screen and (min-width: 780px) { .conf_scheduler .conf_block { width: calc((100% - {$per_row}em)/{$per_row}); } }";
      foreach ($rules as $style) {
        if($val = get_theme_mod( $style['o'], '' )) $css[] = $style['s'] . ' { ' . sprintf( $style['r'], $val ) . "}";
      }
      if ($per_row == 1) {
        $css[] = "@media only print { .conf_scheduler .conf_block { width: 100% !important;  } }";
      }

      if($css) {
        wp_add_inline_style( 'conf_scheduler', implode("\n", $css) );
      }

      // localize the js to give data and translations
      $searchable_selectors = apply_filters('conf_scheduler_searchable', array('.title', '.presenter'));
      $i18n = array(
        'search' => __('Filter/Search...','conf-scheduler')
      );
      $ldata = array(
        'searchSelectors' => $searchable_selectors,
        'ajaxUrl'         => admin_url( 'admin-ajax.php'),
        'i18n'            => $i18n
      );
      wp_localize_script('conf_scheduler', 'conf_scheduler_ldata', apply_filters('conf_scheduler_ldata', $ldata) );
      wp_enqueue_script('conf_scheduler');
  	}
  }

  /**
   * Get prepared session objects
   * @param  array $atts  input attributes - optional
   * @return array        nested array of WP_Term objects for sessions
   */
  function get_sessions( $atts ) {
    $get_args = array( 'taxonomy'=> 'conf_sessions', 'hide_empty' => false, 'pad_counts' => true );
    $mq = array();
    if (isset($atts['start'])) $mq[] = array('key' => 'start', 'value' => $atts['start'], 'compare' => '>=', 'type' => 'DATETIME');
    if (isset($atts['end'])) $mq[] = array('key' => 'start', 'value' => $atts['end'], 'compare' => '<=', 'type' => 'DATETIME');
    if ($mq) $get_args['meta_query'] = array_merge( array('relation' => 'AND'), $mq);
    if (isset($atts['singlesession'])) $get_args['include'] = (int) $atts['singlesession'];

    $event_session_ids = array();
    if (isset($atts['event'])) {
      // find the event workshops and load them (loads term data into cache)
      $event_args = array(
        'post_type' => 'conf_workshop',
        'tax_query' => array( array(
          'taxonomy' => 'conf_events',
          'field' => 'slug',
          'terms' => $atts['event']
        )),
        'fields' => 'ids',
        'nopaging'=>true
      );
      $event_workshop_ids = get_posts($event_args);

      foreach ($event_workshop_ids as $workshop_id) {
        $event = get_the_terms( $event_workshop_ids, 'conf_events' );
        if ($event) $event_session_ids[] = $event->term_id;
      }
    }

    $raw_sessions = get_terms( $get_args );

    $sessions = array();

		// check for sessions to collapse
		foreach ($raw_sessions as $session) {
      // hide empty sessions
      // ##### TODO: fix pad_count not working ####
      // if (empty($session->description) && $session->count === 0) {
      //   continue;
      // }
      if ($event_session_ids) {
        // skip sessions not in the specified event
        if (!in_array($session->term_id, $event_session_ids) ) continue;
      }


      if (get_term_meta($session->term_id, 'collapse', true) == 'collapse') {
				$session->merge_children = true;
			}

      // add custom data to session object
      $session->start = strtotime(get_term_meta($session->term_id, 'start', true));
      if (!$session->start) $session->start = 0;
      $session->length = (int) get_term_meta($session->term_id, 'length', true);
      $session->hide_title = get_term_meta($session->term_id, 'hide_title', true);
      $session->hide_time = get_term_meta($session->term_id, 'hide_time', true);
      $session->group_by_location = get_term_meta($session->term_id, 'group_by_location', true);
      $sessions[] = $session;
		}
    // error_log(var_export($sessions, true));

    // Filter sessions if requested
    $topParent = 0; // Defualt to show all sessions (starting with parent=>0 which is no parent)
    $session_tree = array();

    if(isset($atts['session']) && $atts['session']) {
      $parents = get_terms( array( 'taxonomy'=> 'conf_sessions', 'slug'=>$atts['session'], 'hide_empty' => false ) );
      $topParent = -1; // default to -1 to show no results
      foreach ($parents as $parent) {
        if ($parent->slug == $atts['session']) $topParent = $parent->term_id;
      }
    }

    if(isset($atts['id']) && $atts['id'] && (int)$atts['id'] > 0 )
      $topParent = (int) $atts['id'];

    $session_sort = apply_filters('conf_scheduler_session_sort', array('orderby' => 'name', 'order' => 'ASC'));
		$this->sort_terms_hierarchicaly($sessions, $session_tree, $session_sort, $topParent);

    if($topParent != 0 ) {
      foreach($session_tree as &$top_session) {
        $top_session->parent = 0; // set to 0 so date shows
      }
    }

    return $session_tree;
  }

  /**
   * Get sorted workshops in the session
   * @param  WP_Term $session   Session object
   * @return array             array of WP_POST objects for workshops in the session
   */
  function get_session_workshops($session, $atts = array()) {
    $query_args = array(
      'post_type' => 'conf_workshop',
      'tax_query' => array( array(
        'taxonomy' => 'conf_sessions',
        'field' => 'term_id',
        'terms' => $session->term_id ,
        'include_children'=>false
      )),
      'nopaging'=>true,
    );
    if ( isset($atts['theme']) ) $query_args['tax_query'][] = array(
      'taxonomy' => 'conf_streams',
      'field' => 'slug',
      'terms' => explode(',', $atts['theme']),
      'include_children'=>true
    );

    if ( isset($atts['keywords']) ) $query_args['tax_query'][] = array(
      'taxonomy' => 'conf_areas',
      'field' => 'slug',
      'terms' => explode(',', $atts['keywords'])
    );

    if ( isset($atts['event']) ) $query_args['tax_query'][] = array(
      'taxonomy' => 'conf_events',
      'field' => 'slug',
      'terms' => $atts['event']
    );

    if (!in_array($this->option('workshop_sort_field'), array('name', 'author', 'date', 'title', 'modified', 'menu_order', 'parent', 'ID', 'rand'))) {
      $query_args['meta_query'] = array(
        'relation' => 'OR',
        array(
          'key' => $this->option('workshop_sort_field'),
          'compare' => 'NOT EXISTS'
        ),
        array(
          'key' => $this->option('workshop_sort_field'),
          'compare' => 'EXISTS'
        )
      );
      $query_args['orderby'] = array('meta_value_num' => $this->option('workshop_sort_order'), 'meta_value' => $this->option('workshop_sort_order'));
    } else {
      $query_args['orderby'] = $this->option('workshop_sort_field');
    }
    $query_args['order'] = $this->option('workshop_sort_order');

    $workshops = get_posts(apply_filters('conf_scheduler_workshops_query',$query_args));

    return $workshops;
  }

  /**
   * Render the tabs for days if in tab mode
   * @param  string $output       HTML
   * @param  array $session_tree  nested array of session objects
   * @param  array $atts          input attributes from the block/shortcode
   * @return string               HTML output
   */
  function render_day_tabs( $output, $session_tree, $atts) {
    if ( $this->option('day_mode') != 'tabs' ) return;

    $last_date = -1;
    $date_format = $this->option('day_format') == 'custom' ? $this->option('day_format_custom') : $this->option('day_format');
    $days = array();

    foreach ($session_tree as $session_id => $session) {

      // Output day if needed
      if ($session->parent == 0) {
				if ($session->start >= $last_date) {
          $days[] = $session->start;
          if ($last_date == -1 ) $last_date = $session->start;
          $new_cutoff = strtotime('tomorrow 12am', max($session->start, $last_date) );
          $last_date = apply_filters('conf_scheduler_next_day_timestamp', $new_cutoff, $session->start, $last_date);
				}
			}
    }

    if ($days) {
      $output .= '<ul class="day_tabs">';
      foreach ($days as $i => $day) {
        $state = $i == 0 ? ' open' : '';
        $output .= '<li data-day="'.date('Ymd', $day).'" class="'.$state.'">'.date_i18n($date_format, $day).'</li>';
      }
      $output .= '</ul>';
    }

    return $output;
  }

  /**
   * Render Conference Scheduler - main entry function
   * @shortcode conf_scheduler
   * @param  array $atts    shortcode attributes to parse
   * @return string         HTML output
   */
	function render_schedule($atts) {
    global $post;

    $this->last_date = -1; // reset tracker in case of multiple shortcodes on post

    // map Block attributes to shortcode atts
    if ( isset($atts['displaySession']) ) $atts['id'] = $atts['displaySession'];
    if ( isset($atts['defaultState']) ) $atts['defaultstate'] = $atts['defaultState'];

    // Enqueue assets for block display
  	if( $post && !has_shortcode( $post->post_content, 'conf_scheduler') )
      $this->hook_frontend_assets(true);
    $class = $this->option('view_mode') == 'timeline' ? ' timeline' : '';
    $class .= $this->option('day_mode') == 'tabs' ? ' day_tabs' : '';
		$output = "<div class=\"conf_scheduler$class\">";

    $output .= '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;"><symbol id="fav-star" viewBox="0 0 576 512"><path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"/><path class="conf_scheduler_star_outline" d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"/></symbol></svg>';

		$session_tree = $this->get_sessions($atts);
        $output .= apply_filters('conf_scheduler_before_filters', '', $session_tree, $atts);

		// Search/filter options
		$filter = '<div class="filter">';
    $options = array('<optgroup label="'.__('Type to Search', 'conf-scheduler-pro').'"></optgroup>');

    $themes = get_terms( array(
			'taxonomy'=> 'conf_streams',
			'hide_empty' => false
		));
    if ($themes) {
      $theme_options = '<optgroup label="'.__('Themes', 'conf-scheduler-pro').'">';
  		foreach ($themes as $theme ) {
  				$theme_options .= '<option value="theme_'.$theme->slug.'">'.$theme->name.'</option>';
  		}
      $theme_options .= '</optgroup>';
      $options[] = $theme_options;
    }

    $keywords = get_terms( array(
			'taxonomy'=> 'conf_areas',
			'hide_empty' => false
		));
    if ($keywords) {
      $keyword_options = '<optgroup label="'.__('Keywords', 'conf-scheduler-pro').'">';
  		foreach ($keywords as $keyword ) {
  				$keyword_options .= '<option value="keyword_'.$keyword->slug.'">'.$keyword->name.'</option>';
  		}
      $keyword_options .= '</optgroup>';
      $options[] = $keyword_options;
    }

    $filter .= '<select class="multi_filter" multiple="multiple">'.implode(apply_filters('conf_scheduler_filter_options', $options)).'</select>';

    $buttons = '<button class="toggle_all">'.__('Show/Hide All','conf-scheduler').'</button>';
		$buttons .= '<button class="my_picks">'.__('Show My Picks','conf-scheduler').'</button>';

    $filter .= '<div class="buttons">' . apply_filters('conf_scheduler_schedule_buttons', $buttons) .'</div>';
    $filter .= '</div>'; // close .filter

    $output .= apply_filters('conf_scheduler_filters', $filter);

    $output .= apply_filters('conf_scheduler_after_filters', '', $session_tree, $atts);

    if ($session_tree) {
      $output .= $this->render_session($session_tree, $atts);
      $output .= "</div><!-- .conference_day -->\n"; // close last day
    } else {
      $output .= __('No sessions found.', 'conf-scheduler');
    }

		$output .= "</div><!-- .conf_scheduler -->\n"; // close .conf_scheduler

		return $output;
	}

    /**
     * Create HTML output for an individual session
     * @param  array $session_tree  nested array of session objects
     * @param  array $atts          render attributes passed in
     * @return string               HTML output
     */
	function render_session($session_tree, $atts = null) {
	  $output = '';
  
      $default_state = (isset($atts['defaultstate']) && in_array($atts['defaultstate'], array('open', 'parent_sessions', 'all_sessions'))) ? $atts['defaultstate'] : '';
      $hideWorkshops = ( isset($atts['hideworkshops']) && $atts['hideworkshops'] ) ? true : false;
	  foreach ($session_tree as $session_id => $session) {
        // Determine initial view state
        $session_class = ' session_'.$session->slug;
        $day_view_state = $default_state != '' ? ' open' : '';
        if ( $this->option('day_mode') == 'tabs' ) $day_view_state = ' open';
  
        $has_visible_sub_sessions = ( isset($session->children) && count($session->children) && !$session->merge_children );
        if ( $default_state == 'all_sessions' && $has_visible_sub_sessions ) $session_class .= ' open';
        if ( $default_state == 'open' ) $session_class .= ' open';
  
        // Output day if needed
        if ($session->parent == 0) {
		  if ($session->start >= $this->last_date) {
		    if ($this->last_date != -1) $output .= "</div><!-- .conference_day -->\n"; // close day
            $date_format = $this->option('day_format') == 'custom' ? $this->option('day_format_custom') : $this->option('day_format');
            if ($this->last_date == -1 ) {
              $this->last_date = $session->start;
            } else {
              if ( $this->option('day_mode') == 'tabs' ) $day_view_state = '';
            }
            $output .= '<div class="conference_day day_'.date('Ymd', $session->start).$day_view_state.'">'."\n".'<h3>'.date_i18n($date_format, $session->start).'</h3>';
            $new_cutoff = strtotime('tomorrow 12am', max($session->start, $this->last_date) );
            $this->last_date = apply_filters('conf_scheduler_next_day_timestamp', $new_cutoff, $session->start, $this->last_date);
		  }
	    }
  
        if ($session->count == 0) $session_class .= ' no_workshops';
  
        $output .= '<div class="session'.$session_class.'">'."\n";
        $title = '';
        if(!$session->hide_time) {
          $title .= date(get_option('time_format'), $session->start);
          if (!apply_filters('conf_scheduler_session_time_hide_end', $this->option('view_mode') == 'timeline'))
            $title .= ' - '.date(get_option('time_format'), ($session->start + $session->length *60) );
        }
        if($this->option('view_mode') != 'timeline' && !$session->hide_title) $title .= ' '.$session->name;
  
			  $output .= '<h3>'.trim(apply_filters('conf_scheduler_session_title', $title, $session)).'</h3>';
        if ( $session->description )
          $output .= '<div class="description">'.wpautop($session->description).'</div>';
  
        $output .= '<div class="workshops">'."\n";
  
        $workshops = array();
        if(!$hideWorkshops) {
          if($session->group_by_location) {
		    // group workshops by location within this session
		    $output .= $this->render_session_grouped_workshops($session, $atts);
		  } else {
		    // render session workshops one by one
            $workshops = $this->get_session_workshops($session, $atts);
  
            foreach ($workshops as $workshop) {
              $output .= $this->render_workshop($workshop, $session, $atts);
            }
          }
        }
  
	    if(!$hideWorkshops && $session->merge_children && isset($session->children)) {
		    // add child sessions as split boxes
		    $output .= $this->render_session_merged_workshops($session->children, $atts);
	    }
	    $output .= "</div> <!-- .workshops -->\n"; // close workshops
  
        if(!$workshops && count($session->children) == 0 && $session->count == 0 && !$session->description) {
          $output .= '<p class="no_workshops">'.__('No workshops in this session.', 'conf-scheduler')."</p>\n";
        }
  
	    if(!$session->merge_children && isset($session->children)) {
		    $output .= $this->render_session($session->children, $atts);
	    }
    
	    $output .= "</div><!-- .session -->\n"; // close session
	  }
  
	  return $output;
	}

  /**
   * Create HTML output for a single workshop
   * @param  WP_Post $workshop Workshop post object
   * @param  WP_Term $session  Sesion object
   * @param  array   $atts     attributes passed from block/shortcode
   * @param  string $block     Class to use for output
   * @return string            HTML output
   */
	function render_workshop($workshop, $session, $atts = array(), $block = 'conf_block') {
		$output = '';
    $data = new stdClass();
    $data->classes = array('workshop');
    $data->classes[] = esc_attr($block);

    $data->post_id = $workshop->ID;
    $data->title = $workshop->post_title;
    $data->description = wpautop( $workshop->post_content );

		// format session details
		if (!$workshop->start_time) {
      $workshop->start_time = $session->start;
      $workshop->end_time = $workshop->start_time + $session->length * 60;
    }

    $data->session_text = '<span class="session '.$session->slug.'">'.date_i18n('D',$workshop->start_time).' '.date(get_option('time_format'), $workshop->start_time).' - '.date(get_option('time_format'), $workshop->end_time ).'</span>';

		// generate edit link
		if (current_user_can( 'edit_post' , $workshop->ID )) {
			$data->edit_link = '<a class="edit_link" title="'.__('Edit Workshop', 'conf-scheduler').'" href="'.get_edit_post_link( $workshop->ID, 'link' ).'"></a>';
		} else { $data->edit_link = ''; }

		// get & format themes
    $theme_tags = array();
		$themes = get_the_terms($workshop->ID,'conf_streams');
		if ($themes) {
			foreach ($themes as $theme) {
				$theme_tags[] = '<span class="theme theme_'.$theme->slug.'">'.$theme->name. '</span>';
				$data->classes[] = 'theme_'.$theme->slug;
			}
		}
    $data->themes_html = $theme_tags ? '<div class="themes">'.implode(', ',$theme_tags).'</div>' : '';

    // get & format keywords
    $keyword_tags = array();
		$keywords = get_the_terms($workshop->ID,'conf_areas');
		if ($keywords) {
			foreach ($keywords as $keyword) {
				$keyword_tags[] = '<span class="keyword keyword_'.$keyword->slug.'">'.$keyword->name. '</span>';
				$data->classes[] = 'keyword_'.$keyword->slug;
			}
		}
    $data->keywords_html = $keyword_tags ? '<div class="keywords">'.implode(', ',$keyword_tags).'</div>' : '';

		$details = array();
    $details['workshop_id'] = get_post_meta($workshop->ID,'workshop_id', true);
    $details['location'] = get_post_meta($workshop->ID,'location', true);
    $details['location_url'] = get_post_meta($workshop->ID,'location_url', true);
    $details['presenter'] = get_post_meta($workshop->ID,'presenter', true);
    $details['limit'] = get_post_meta($workshop->ID,'limit', true);
    $details['session'] = get_post_meta($workshop->ID,'session', true);
    $details['presenter_bio'] = get_post_meta($workshop->ID,'presenter_bio', true);
    $files_data = get_post_meta($workshop->ID,'file_attachments_id', true);

    $data->workshop_id = '';
  	if (isset($details['workshop_id']) && !empty($details['workshop_id'])) {
  		$data->workshop_id = $details['workshop_id'];
  	}

    $presenter = '';
    if (isset($details['presenter']) && !empty($details['presenter'])) {
      $presenter = '<span class="presenter">'.$details['presenter'].'</span>';
    }
    $data->presenter = $presenter;
    $data->presenter_bio = esc_html($details['presenter_bio']);

    $location = '';
    if (isset($details['location']) && !empty($details['location'])) {
      if ( isset($details['location_url']) && !empty($details['location_url']) ) {
        $location = '<span class="location"><a target="_blank" href="'.esc_url( $details['location_url']).'">'.$details['location'].'</a></span>';
      } else {
        $location = '<span class="location">'.$details['location'].'</span>';
      }
    }
    $data->location = $location;

    $files = '';
    if ($files_data) {
      $file_ids = array_map('intval', explode(',', $files_data));
      $files = '<div class="files"><span>'.__('Documents','conf-scheduler').'</span>';
      foreach($file_ids as $file_id) {
        $files .= '<a class="file" href="'.wp_get_attachment_url($file_id).'">'.get_the_title($file_id)."</a> <small>(".size_format( filesize( get_attached_file($file_id))).")</small><br/>";
      }
      $files .= '</div>';
    }
    $data->files = $files;

    $limit = '';
    if ($details['limit'] && $details['limit'] > 0) {
      $limit = '<div class="limit" title="'.__('Participant limit', 'conf-scheduler').'">'.sprintf( __('%d max', 'conf-scheduler'), $details['limit']).'</div>';
    }
    $data->limit = $limit;

    $data->image = get_the_post_thumbnail($workshop->ID, 'medium');

    $data->favorite = '<div class="favorite" title="'.esc_attr(__('Pick this workshop','conf-scheduler')).'">
        <svg><use xlink:href="#fav-star"></use></svg>
      </div>';

    // Filter to allow extensions to add output info
    $data = apply_filters('conf_scheduler_workshop_data', $data, $workshop, $session, $atts, $block);

    // collect classes
    $data->class_text = implode(' ',$data->classes);

    // capture template
    ob_start();
    $this->get_template('workshop.php', $data);
    $output .= ob_get_contents();
    ob_end_clean();

		return $output;
	}

  /**
   * Create HTML output for a session with Grouped Workshops
   * @param  WP_Term $session   Session object
   * @param  array   $atts      attributes passed from block/shortcode
   * @return string             HTML output
   */
  function render_session_grouped_workshops( $session, $atts = array() ) {
    $output = '';
    $workshops = $this->get_session_workshops( $session );

    if($workshops && count($workshops) > 0) {
      $max_workshops = count($workshops);

      $grouped_workshops = array();
      foreach ($workshops as $workshop) {
        $location = $workshop->location;
        $grouped_workshops[$location][] = $workshop;
      }

      ksort($grouped_workshops); // sort groups by location name

      // Output
      foreach ( $grouped_workshops as $loc => $block_workshops ) {
        $max_workshops = max(array_map( 'count', $grouped_workshops ));
        $output .= '<div class="workshop_group conf_block">';
        for ($i = 0; $i < $max_workshops; $i++ ) {  // for ($i = 0; $i < count($block_workshops); $i++ ) {
          if(isset($block_workshops[$i])) {
            $output .= $this->render_workshop($block_workshops[$i], $session, $atts, '');
          }
        }
        $output .= '</div>';
      }
    }

    return $output;
  }

  /**
   * Create HTML output for a session with merged children
   * @param  WP_Term $sessions  Session object
   * @param  array   $atts      attributes passed from block/shortcode
   * @return string             HTML output
   */
  function render_session_merged_workshops( $sessions, $atts = array() ) {
		$output = '';
		$workshops = array();
		$sessions = array_values($sessions); // $sessions is numerical array

    foreach ($sessions as $session ) {
      $workshops[] = $this->get_session_workshops($session);
    }

    if(count($workshops) > 0) {
  		$max_workshops = max(array_map( 'count', $workshops ));
      // pivot workshop array
      $grouped_workshops = array();
      for ($i = 0; $i < $max_workshops; $i++ ) { //traverse session workshops
  			foreach ($workshops as $k => $session) { //traverse sessions
          if(isset($session[$i])) {
            $location = $session[$i]->location;
            $grouped_workshops[$location][$k] = $session[$i];
          }
        }
      }

      ksort($grouped_workshops); // sort groups by location name

      // Output
      foreach ( $grouped_workshops as $loc => $block_workshops ) {
  			$output .= '<div class="workshop_group conf_block">';
        for ($i = 0; $i < count($sessions); $i++ ) {  // for ($i = 0; $i < count($block_workshops); $i++ ) {
        	if(isset($block_workshops[$i])) {
  					$output .= $this->render_workshop($block_workshops[$i], $sessions[$i], $atts, '');
  				} else {
  					//$output .= '<div class="workshop placeholder"></div>';
  				}
  			}
  			$output .= '</div>';
  		}
    }

		return $output;
	}

  /**
   * Parse term objects into a nested array, sorted by specified property
   * @param  Array   $cats      1-D array of objects for input
   * @param  Array   $into      return array of processed objects
   * @param  string  $sortby    parameter to sort siblings based on
   * @param  integer $parentId  ID of the object to set as the top level, 0 indicates objects with no parent
   * @return null
   */
	function sort_terms_hierarchicaly(Array &$cats, Array &$into, $sort_args = array('orderby' => 'name', 'order' => 'ASC'), $parentId = 0) {
      if($parentId != -1) {
        foreach ($cats as $i => $cat) {
  	        if ($cat->parent == $parentId) {
  	            $into[$cat->term_id] = $cat;
  	            unset($cats[$i]);
  	        }
  	    }
      }

			// sort terms by date/start_time
			uasort($into, function ($a, $b) use ( $sort_args ) {
			    if ($a->start == $b->start) {
              $field = $sort_args['orderby'];
              $order = $sort_args['order'] == 'ASC' ? 1 : -1;
              if ( in_array($field, array('term_id', 'count', 'length') ) ) {
                return $order == 1 && $a->$field > $b->$field ? 1 : -1;
              } elseif ( in_array($field, array('name', 'slug', 'length') ) ) {
                return $order * strcasecmp($a->$field,$b->$field); // default ot alphabetical if same time
              } else {
                return 0;
              }
			    }
			    return ($a->start < $b->start) ? -1 : 1;
			});

	    foreach ($into as $topCat) {
	        $topCat->children = array();
	        $this->sort_terms_hierarchicaly($cats, $topCat->children, $sort_args, $topCat->term_id);
	    }
	}


  function render_block_schedule( $args ) {
    global $wpdb;

    // Get all locations
    $location_mkey = 'location';
    $locations = $wpdb->get_col(
  		$wpdb->prepare( "
  			SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
  			LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
  			WHERE pm.meta_key = '%s'
  			AND p.post_status = 'publish'
        AND p.post_type = 'conf_workshop'
  			ORDER BY pm.meta_value",
  			$location_mkey
  		)
  	);

    $sessions = $this->get_sessions($args);

    $output = '<table>';
    $output .= '<tr><th></th>';
    foreach ($locations as $location) {
      $output .= '<th>'.$location.'</th>';
    }
    $output .= '</tr>';

    $output .= $this->render_block_session($sessions, $locations);

    $output .= '</table>';

    return $output;
  }


  function render_block_session($sessions, $locations) {
    $output = '';
    foreach($sessions as $session) {
      // Get workshops
      $children = isset($session->merge_children) && $session->merge_children ? true : false;
      $post_args = array(
        'post_type' => 'conf_workshop',
        'nopaging' => true,
        'tax_query' => array( array(
          'taxonomy' => 'conf_sessions',
          'terms' => $session->term_id,
          'include_children' => $children
        ) )
      );

      if($children) unset($session->children);

      $workshop_objs = get_posts($post_args);
      $workshops = array();
      foreach ($workshop_objs as $obj) {
        $workshops[$obj->ID] = $obj;
      }

      // Setup row data
      $row = array_fill_keys($locations, array());
      foreach( $workshops as $workshop ) {
        if ( $workshop->location != '' ) {
          $row[$workshop->location][] = $workshop->ID;
        }
      }

      $output .= '<tr><td>'.$session->name.' '.date_i18n(get_option('time_format'), $session->start).' - '.date_i18n(get_option('time_format'), $session->start + $session->length * 60).'</td>';
      foreach ( $row as $location_cell ) {
        $output .= '<td>';
        if (count($location_cell) == 1) {
          $workshop = $workshops[$location_cell[0]];
          $output .= '<a href="'.esc_url( get_permalink($workshop)).'">'.$workshop->workshop_id.' - '.$workshop->post_title.'</a>';
        } elseif (count($location_cell) > 1) {
          foreach ($location_cell as $id) {
            $workshop = $workshops[$id];
            $output .= '<a href="'.esc_url( get_permalink($workshop)).'">'.$workshop->workshop_id.'</a>, ';
          }
        }
        $output .= '</td>';
      }

      $output .= '</tr>';

      if(isset($session->children) && is_array($session->children)) {
        $output .= $this->render_block_session($session->children, $locations);
      }
    } // endforeach $sessions

    return $output;
  }

  /**
   * Show CS Info Sidebar on workshop list screen
   * @return null
   */
  function conf_scheduler_info_html() {
    $screen = get_current_screen();
    if ('edit-conf_workshop' == $screen->id):
    ?>
    <div id="conf-sidebar-wrap" style="display: none;">
      <div class="conf-sidebar">
      		<div class="content">
            <img class="logo" src="<?php echo plugins_url('static/images/logo.png', __FILE__);?>" height="34"/>
            <h2><?php _e('Conference Scheduler','conf-scheduler');?></h2>
            <p class="subhead"><?php echo sprintf(__('Developed by: %s', 'conf-scheduler'), '<a href="https://myceliumdesign.ca/">Shane Warner</a>');?></p>
            <p><a href="https://conferencescheduler.com/documentation/"><?php _e('Documentation','conf-scheduler');?></a></p>

            <h3><?php _e('Looking for more? Go Pro!','conf-scheduler');?></h3>
            <p><?php echo sprintf(__('%s has everything here and a lot more features like:', 'conf-scheduler'), '<a href="https://conferencescheduler.com/pro/"><strong>'.__('Conference Scheduler Pro', 'conf-scheduler').'</strong></a>');?></p>
            <ul>
              <li><?php _e('Import/Export from/to Excel','conf-scheduler');?></li>
              <li><?php _e('Advanced registration system','conf-scheduler');?></li>
              <li><?php _e('Multi-lingual integration with <a href="https://wpml.org/">WPML','conf-scheduler');?></a></li>
              <li><?php _e('Extensible architecture','conf-scheduler');?></li>
            </ul>
            <p><?php _e('All of this plus more, with one-year of premium support and plugin updates.','conf-scheduler');?></p>
            <p style="text-align: center;"><a class="button" href="https://conferencescheduler.com/pro/"><?php _e('Learn More','conf-scheduler');?> &raquo;</a></p>
          </div>
      		<div class="footer">
      			<p><?php _e('Thanks for using Conference Scheduler!','conf-scheduler' ); ?></p>
      		</div>
      </div>
    </div>

    <script type="text/javascript">
      (function($){
      	$('#posts-filter').wrap('<div class="conf-content"/></div>');
      	$('#posts-filter').after( $('#conf-sidebar-wrap').html() );
      })(jQuery);
      </script>
    <?php
    endif;
  }

  /**
   * Register the Settings page for Conference Scheduler
   * @hooked admin_menu
   * @return null
   */
  function setup_admin_pages() {
    $args = apply_filters( 'conf_scheduler_menupage_args', array(
      'parent_slug' => 'edit.php?post_type=conf_workshop', //string $parent_slug
      'page_title' => __('Conference Scheduler Settings','conf-scheduler'), //string $page_title
      'menu_title' => __('Settings', 'conf-scheduler'), // string $menu_title
      'capability' => 'manage_options', // string $capability
      'menu_slug' => 'conf_scheduler_options', //string $menu_slug
      'function' => array($this,'conf_scheduler_options') // callable $function = ''
    ));
    
    add_submenu_page(
      $args['parent_slug'],
      $args['page_title'],
      $args['menu_title'],
      $args['capability'],
      $args['menu_slug'],
      $args['function']
    );
  }

  /**
   * Enqueue JS and CSS assets for Admin pages
   * @hooked admin_enqueue_scripts
   * @return null
   */
  function admin_enqueue_assets() {
    $i18n = array(
      'delete_workshops' => __('Are you sure you want to delete all workshops?','conf-scheduler'),
      'delete_sessions' => __('Are you sure you want to delete all sessions?','conf-scheduler'),
      'delete_themes' => __('Are you sure you want to delete all themes?','conf-scheduler'),
      'delete_keywords' => __('Are you sure you want to delete all keywords?','conf-scheduler'),
    );
    $ldata = apply_filters('conf_scheduler_admin_ldata', array(
      'i18n'            => $i18n,
      'timeFormat' => get_option('time_format'),
      'locale' => get_user_locale(),
    ));

    $screen = get_current_screen();
    if( 'conf_workshop_page_conf_scheduler_options' == $screen->id ) { // CS Options
      wp_enqueue_script('select2v4-js');
      wp_enqueue_style('select2v4-css');
      wp_localize_script('conf_scheduler_admin-js', 'conf_scheduler_ldata', $ldata );
      wp_enqueue_script('conf_scheduler_admin-js');
      wp_enqueue_style('conf_scheduler_admin-css');

    }
    if( 'edit-conf_workshop' == $screen->id ) { // Workshops list
      wp_localize_script('conf_scheduler_admin-js', 'conf_scheduler_ldata', $ldata );
      wp_enqueue_script('conf_scheduler_admin-js');
      wp_enqueue_style('conf_scheduler_admin-css');
    }
    if( 'conf_workshop' == $screen->id ) { // Edit Workshop
      wp_enqueue_media();
      wp_enqueue_script('datetime_picker-js');
      $locale = substr(get_user_locale(),0,2);
      if ($locale != 'en')
        wp_enqueue_script('datetime_picker-locale-js', CONF_SCHEDULER_URL ."vendor/bootstrap_datetime_picker/locales/bootstrap-datetimepicker.$locale.js", array('datetime_picker-js') );
      wp_enqueue_style('datetime_picker-css');
      wp_enqueue_script('select2v4-js');
      wp_enqueue_style('select2v4-css');
      wp_localize_script('conf_scheduler_admin-js', 'conf_scheduler_ldata', $ldata );
      wp_enqueue_script('conf_scheduler_admin-js');
      wp_enqueue_style('conf_scheduler_admin-css');
    }
    if ('edit-conf_sessions' == $screen->id ) { // List & edit session
      wp_enqueue_script('datetime_picker-js');
      $locale = substr(get_user_locale(),0,2);
      if ($locale != 'en')
        wp_enqueue_script('datetime_picker-locale-js', CONF_SCHEDULER_URL ."vendor/bootstrap_datetime_picker/locales/bootstrap-datetimepicker.$locale.js", array('datetime_picker-js') );
      wp_enqueue_style('datetime_picker-css');
      wp_localize_script('conf_scheduler_admin-js', 'conf_scheduler_ldata', $ldata );
      wp_enqueue_script('conf_scheduler_admin-js');
      wp_enqueue_style('conf_scheduler_admin-css');
    }

  }

  /**
   * Process options form and save to database
   * @hooked admin_menu
   * @return null
   */
  function process_options() {

    if (!current_user_can( apply_filters('conf_scheduler_manage_cap', 'manage_options', 'process_options') )) {
        return false;
    }

    if (isset($_POST['conf_scheduler_options_nonce'])) {
      // form posted back - process settings
      if (!wp_verify_nonce( $_POST['conf_scheduler_options_nonce'], 'conf_scheduler_options' )) {
          wp_die(__('Nonce verification failed', 'conf-scheduler'));
      } else {
        do_action('conf_scheduler_process_options');
        // $options = apply_filters('conf_scheduler_options', $this->options);
        $values = array();
        $values['filter_multiple'] = isset($_POST['filter_multiple']) && $_POST['filter_multiple'] == 1 ? 1 : 0;

        foreach ($values as $option => $value) {
          update_option('conf_scheduler_'.$option, $value);
        }

        do_action('conf_scheduler_options_saved');

        add_settings_error('csp_settings', 'csp_settings_updated', __('Settings updated.','conf-scheduler'), 'updated'); // Display message
      }
    }
  }

  /**
   * Display the Settings page
   * @return [type] [description]
   */
  function conf_scheduler_options() {
    if (!current_user_can( apply_filters('conf_scheduler_manage_cap', 'manage_options', 'process_options') )) {
        wp_die(__('Unauthorized user', 'conf-scheduler'));
    }

    $debug_info = $this->cs_debug_info();

    include 'views/options.php';
  }

  /**
   * Display general options section
   * @param  string $tab    slug of current tab
   * @return null
   */
  function output_general_options( $options, $tab) {
    if($tab == '') {
      if (!current_user_can( apply_filters('conf_scheduler_manage_cap', 'manage_options', 'process_options') )) {
          wp_die(__('Unauthorized user', 'conf-scheduler'));
      }

      include 'views/options-general.php';
    }
  }

  /**
   * Generate system status report
   * @return [type] [description]
   */
  function cs_debug_info() {
  	global $wpdb;

  	// WP memory limit
		$wp_memory_limit = $this->human_filesize_to_num( WP_MEMORY_LIMIT );

    $license_key = get_option( 'wc_am_client_15800' );
  	$environment = apply_filters( 'conf_scheduler_debug_data', array(
  			'home_url'                  => get_option( 'home' ),
  			'site_url'                  => get_option( 'siteurl' ),
  			'wp_version'                => get_bloginfo( 'version' ),
        'conf-scheduler_version'    => defined( 'CONF_SCHEDULER_VERSION' ) ? CONF_SCHEDULER_VERSION : 'none',
        'conf-scheduler-pro_version' => defined( 'CONF_SCHEDULER_PRO_VERSION' ) ? CONF_SCHEDULER_PRO_VERSION : 'none',
        'pro_instance_id'           => get_option( 'wc_am_client_15800_instance' ) ?: 'none',
        'pro_license_key'           => $license_key ? $license_key['wc_am_client_15800_api_key'] : 'none',
  			'wp_multisite'              => is_multisite() ? 'Yes' : 'No',
  			'wp_memory_limit'           => size_format($wp_memory_limit),
  			'php_memory_limit'					=> @ini_get('memory_limit'),
  			'wp_debug_mode'             => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Enabled' : 'Disabled',
  			'wp_cron'                   => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) ? 'Enabled' : 'Disabled',
  			'language'                  => get_user_locale(),
  			'server_info'               => $_SERVER['SERVER_SOFTWARE'],
  			'php_version'               => phpversion(),
  			'php_post_max_size'         => size_format($this->human_filesize_to_num( ini_get( 'post_max_size' ) )),
  			'php_max_execution_time'    => ini_get( 'max_execution_time' ),
  			'php_max_input_vars'        => ini_get( 'max_input_vars' ),
  			'max_upload_size'           => size_format(wp_max_upload_size()),
  			'mysql_version'             => ( ! empty( $wpdb->is_mysql ) ? $wpdb->db_version() : '' ),
  			'default_timezone'          => date_default_timezone_get(),
  			'domdocument_enabled'       => class_exists( 'DOMDocument' ) ? 'Enabled' : 'Disabled',
  			'gzip_enabled'              => is_callable( 'gzopen' ) ? 'Enabled' : 'Disabled',
  			'mbstring_enabled'          => extension_loaded( 'mbstring' ) ? 'Enabled' : 'Disabled',
        'conf_scheduler_path'       => CONF_SCHEDULER_PATH,
        'conf_scheduler_url'       => CONF_SCHEDULER_URL,
  		));

  		$output = "      ## Conference Scheduler System Report ##\n\n";
  		foreach($environment as $k => $e) {
  			$output .= $k.":\t".$e."\n";
  		}

      return $output;
  }

  /**
   * Convert human-readable filesizes to integers
   * @param  string $size     filesize string to convert
   * @return int              integer value of filesize
   */
  function human_filesize_to_num( $size ) {
  	$l   = substr( $size, -1 );
  	$ret = substr( $size, 0, -1 );
  	switch ( strtoupper( $l ) ) {
  		case 'P':
  			$ret *= 1024;
  		case 'T':
  			$ret *= 1024;
  		case 'G':
  			$ret *= 1024;
  		case 'M':
  			$ret *= 1024;
  		case 'K':
  			$ret *= 1024;
  	}
  	return $ret;
  }

  /**
   * Sanitize a hex-colour value
   * @param  string $value    Hex color value to sanitize
   * @return string           sanitized hex colour string
   */
  function sanitize_color( $value ) {
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #
        return $value;
    }
    return '';
  }

  /**
   * Handle click on the Delete All data button of the options page
   * @hooked wp_ajax_conf_scheduler_delete_data
   * @return null
   */
  function ajax_process_remove_data_click() {
    if( ! isset( $_POST ) || empty( $_POST ) || ! is_user_logged_in() ) {
      header( 'HTTP/1.1 400 Empty POST Values' );
      echo '';
      wp_die();
    }

    $action = $_POST['dataType'];
    $cs_nonce = $_POST['cs_nonce'];

    $resp = array('action'=>$action, 'msg' => '');
    if ( wp_verify_nonce($cs_nonce, 'conf-scheduler-delete-data') && current_user_can( apply_filters('conf_scheduler_manage_cap', 'manage_options', 'remove_data'))) {

      if( in_array($action, array('delete_sessions', 'delete_themes', 'delete_keywords'))) {
        $tax = str_replace('delete_', '', $action);
        $taxonomies = array(
          'sessions' => 'conf_sessions',
          'themes' => 'conf_streams',
          'keywords' => 'conf_areas',
        );
        $tax_names = array(
          'sessions' => __('sessions', 'conf-scheduler'),
          'themes' => __('themes', 'conf-scheduler'),
          'keywords' => __('keywords', 'conf-scheduler'),
        );
        $this->remove_all_terms($taxonomies[$tax]);
        $resp['msg'] = sprintf( __('All %s deleted.' ,'conf-scheduler'), $tax_names[$tax]);
      }
      if($action == 'delete_workshops') {
        $this->remove_all_workshops();
        $resp['msg'] =  __('All workshops deleted.', 'conf-scheduler');
      }
      $resp['msg'] = apply_filters( 'conf_scheduler_delete_data',$resp['msg'], $action);

      echo json_encode($resp);
      die();
    } else {
      // permissions/nonce check failed
      $resp['error'] = true;
      echo json_encode($resp);
      die();
    }
  }

  /**
   * Delete all workshops and related meta from the database
   * @return null
   */
  private function remove_all_workshops() {
    global $wpdb;

    // Delete postmeta records
    $wpdb->get_results( $wpdb->prepare( "
      DELETE pm.*
      FROM {$wpdb->postmeta} AS pm
      WHERE pm.post_id IN (
        SELECT p.ID
        FROM {$wpdb->posts} AS p
        WHERE p.post_type = '%s'
      )", 'conf_workshop' ) );

    // Delete post records
    $wpdb->get_results( $wpdb->prepare( "
      DELETE FROM {$wpdb->posts}
      WHERE post_type = '%s'
      ", 'conf_workshop' ) );

    // Reset workshop term counts
    $wpdb->get_results( "
      UPDATE {$wpdb->term_taxonomy}
      SET count = 0
      WHERE taxonomy IN ('conf_sessions', 'conf_areas', 'conf_streams')
      " );
  }

  /**
   * Delete all terms and related records from the database
   * @param  string $tax  taxonomy to be removed
   * @return null
   */
  private function remove_all_terms( $tax ) {
    global $wpdb;

    // Delete term_relationships records
    $wpdb->get_results( $wpdb->prepare( "
      DELETE tr.*
      FROM {$wpdb->term_relationships} AS tr
      WHERE tr.term_taxonomy_id IN (
        SELECT tt1.term_taxonomy_id
        FROM {$wpdb->term_taxonomy} AS tt1
        WHERE tt1.taxonomy = '%s'
      )", $tax ) );

    // Delete termmeta records
    $wpdb->get_results( $wpdb->prepare( "
      DELETE tm.*
      FROM {$wpdb->termmeta} AS tm
      WHERE tm.term_id IN (
        SELECT t1.term_id
        FROM {$wpdb->terms} AS t1
        JOIN {$wpdb->term_taxonomy} AS tt1
        ON tt1.term_id = t1.term_id
        WHERE tt1.taxonomy = '%s'
      )", $tax ) );

    // Delete terms and term_taxonomy records
    $wpdb->get_results( $wpdb->prepare( "
      DELETE t.*, tt.*
      FROM {$wpdb->terms} AS t
      INNER JOIN {$wpdb->term_taxonomy} AS tt ON t.term_id = tt.term_id
      WHERE t.term_id IN (
        SELECT * FROM (
          SELECT t1.term_id
          FROM {$wpdb->terms} AS t1
          JOIN {$wpdb->term_taxonomy} AS tt1
          ON tt1.term_id = t1.term_id
          WHERE tt1.taxonomy = '%s'
        ) as T
      )", $tax ) );

  }

} // close Conference_Scheduler class

/**
 * Instantiate Conference Scheduler
 * @return Conference_Scheduler  The main class
 */
function conf_scheduler() {
	global $conf_scheduler;

	if( !isset($conf_scheduler) ) {
		$conf_scheduler = new Conference_Scheduler();
		$conf_scheduler->initialize();
	}

	return $conf_scheduler;
}

// Instantiate Conference Scheduler
conf_scheduler();

endif; // class_exists check

?>
