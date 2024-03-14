<?php
/**
 * Plugin Name: Builder for Contact Form 7
 * Description: Builder for Contact Form 7 is a user-friendly form builder plugin which makes Contact Form 7 form easy to build just using drag & drop tool.
 * Version: 1.2.2
 * Author: Webconstruct team
 * Author URI: https://planetstudio.am/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || die('Access Denied');

define('CF7B_PREFIX', 'cf7b');
define('CF7B_DIR', plugin_dir_path( __FILE__ ));
define('CF7B_DIR_NAME', dirname(plugin_basename( __FILE__ )));
define('CF7B_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('CF7B_VERSION', '1.2.2');
define('CF7B_PRO', true);
define('CF7B_UPGRADE_PRO_URL', 'https://store.planetstudio.am/product/builder-for-contact-form-7/');

define('CF7B_BUILDER_INT_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
/**
 * Main Class.
 *
 * @class cf7Builder
 * @version	2.1.0
 */
 final class cf7Builder {


  /**
   * Plugin directory path.
   */
  public $plugin_dir = '';
  /**
   * Plugin directory url.
   */
  public $plugin_url = '';
  /**
   * Plugin prefix.
   */
  public $prefix = '';
   /**
    * Plugin version.
    */
   public $plugin_version = '';
   /**
    * Plugin version.
    */
   public $is_pro = 1;


   /**
   * The single instance of the class.
   */
  protected static $_instance = null;

  /**
   * Main cfBuilder Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return cf7Builder - Main instance.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * cfBuilder Constructor.
   */
  public function __construct() {
    $this->plugin_dir = WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__));
    $this->plugin_url = plugins_url(plugin_basename(dirname(__FILE__)));
    $this->plugin_version = '2.0.8';
    $this->prefix = 'cf7b';

    require_once($this->plugin_dir . '/framework/CF7B_Library.php');
    $this->cf7b_add_actions();
/*    $this->cf7b_includes();*/
  }

  /**
   * Activate.
   */
  public function cf7b_activate() {
    require_once( $this->plugin_dir . "/insert.php");
    CFBInsert::tables();
    if ( !CF7B_PRO ) {
      add_option('cf7b_do_activation_redirect', TRUE);
    }

    // Using this insted of flush_rewrite_rule() for better performance with multisite.
    //flush_rewrite_rules( false );
  }

  public $controller;


  /**
   * Add actions.
   */
  private function cf7b_add_actions() {
    register_activation_hook(__FILE__, array($this, 'cf7b_activate'));

    if ( !CF7B_PRO ) {
      add_action('admin_init', array( $this, 'cf7b_overview_page_redirect' ));
    }
    // Register meta box scripts and styles.
    add_action( 'init', array($this, 'gobal_init') );
    add_action('admin_menu', array( $this, 'cf7b_admin_menu' ) );

    add_action( 'admin_enqueue_scripts', array($this, 'cf7b_backend_js_css') );
    add_action( 'wp_enqueue_scripts', array($this, 'cf7b_front_js_css') );
    add_action( 'wp_ajax_cf7b_ajax',  array($this, 'cf7b_includes') );
    add_action( 'wp_ajax_nopriv_cf7b_ajax',  array($this, 'cf7b_includes') );

    add_filter('wpcf7_editor_panels', array( $this, 'cf7b_visual_panel_load' ), 10, 1);
    // add the action after_save of CF7 hook
    add_action('wpcf7_after_save', array( $this, 'cf7b_action_wpcf7_after_save' ), 10, 1);
    add_action('wpcf7_admin_footer', array( $this, 'cf7b_prepare_revision' ), 10, 1);
  }

   /* Redirect to overview page after plugin activation */
   public function cf7b_overview_page_redirect() {
     if ( get_option('cf7b_do_activation_redirect', false) ) {
       delete_option('cf7b_do_activation_redirect');
       wp_redirect(admin_url('admin.php?page=overview_cf7b'));
     }
   }

   /* Global init */
  public function gobal_init() {
    add_filter( 'shortcode_atts_wpcf7', array($this, 'cf7b_include_front_theme_style'), 10, 3 );
    if( !has_action('wpcf7_admin_init') ) {
      add_action( 'admin_notices', array( $this, 'my_error_notice' ) );
    }
    if ( is_admin() ) {
      require_once($this->plugin_dir . "/deactivate/deactivate.php");
      new WCLibDeactivate();
    }

    $this->cf7b_register_cfB_preview_cpt();
  }


  public function cf7b_include_front_theme_style( $out ) {
    $form_id = $out['id'];
    $preview_id = CF7B_Library::cf7b_get_preview_id();

    $preview_post_id = get_option('cf7b_preview_post_id');
    $form_id = ($preview_id == $form_id) ? $preview_post_id : $form_id;

    $wp_upload_dir = wp_upload_dir();
    $cf7b_form_settings = get_option('cf7b_form_settings');
    if( isset($cf7b_form_settings['form_'.$form_id]) && isset($cf7b_form_settings['form_' . $form_id]['theme']) && $cf7b_form_settings['form_' . $form_id]['theme'] != 0 ) {

      $theme_id = $cf7b_form_settings['form_' . $form_id]['theme'];
       wp_register_style('cfB_frontend-theme', $wp_upload_dir['baseurl'] . '/cf7-builder/cf7b-theme-style' . $theme_id . '.css', FALSE, $this->plugin_version);
    }
    if( CF7B_PRO ) {
      wp_enqueue_style('cfB_frontend-theme');
    }
    wp_register_style('cfB_frontend', plugins_url(plugin_basename(dirname(__FILE__))) . '/style/cf7b_frontend.css', FALSE, $this->plugin_version);
    wp_print_styles('cfB_frontend');
    wp_enqueue_script('cfB_frontend', plugins_url(plugin_basename(dirname(__FILE__))) . '/script/cf7b_frontend.js', array('jquery'), $this->plugin_version);
    if( isset($cf7b_form_settings['form_'.$form_id]) && isset($cf7b_form_settings['form_'.$form_id]['action_type']) ) {
        $action_value = $cf7b_form_settings['form_' . $form_id]['action_value'];
        $action_type = $cf7b_form_settings['form_' . $form_id]['action_type'];
        if ( $action_type == 1 || $action_type == 2 ) {
          $action_value = get_permalink( $action_value );
        }
        wp_localize_script('cfB_frontend', 'cf7b_settings', array(
         "action_type" => $action_type,
         "action_value" => $action_value,
        ));
    } else {
        wp_localize_script('cfB_frontend', 'cf7b_settings', array(
          "action_type" => 0,
          "action_value" => '',
        ));
    }

    return $out;
  }

  public function cf7b_admin_menu() {
    $parent_slug = 'submissions_cf7b';
    $nicename = 'CF7 Builder';
    add_menu_page($nicename, $nicename, 'manage_options', $parent_slug, array( $this, 'cf7b_includes' ), '', 31);
    add_submenu_page($parent_slug, __('Submissions', 'cf7b'), __('Submissions', 'cf7b'), 'manage_options', $parent_slug, array($this, 'cf7b_includes'));
    add_submenu_page($parent_slug, __('Themes', 'cf7b'), __('Themes', 'cf7b'), 'manage_options', 'themes_cf7b', array($this, 'cf7b_includes'));
    add_submenu_page($parent_slug, __('Overview', 'cf7b'), __('Overview', 'cf7b'), 'manage_options', 'overview_cf7b', array($this, 'cf7b_includes'));
  }

   /**
    * Include required files.
    */
   public function cf7b_includes() {
/*     if (function_exists('current_user_can')) {
       if (!current_user_can('manage_options')) {
         die('Access Denied');
       }
     }
     else {
       die('Access Denied');
     }*/
     $page = CF7B_Library::get('page');

     if (($page != '') && (($page == 'overview_cf7b') || ($page == 'options_cf7b') || ($page == 'submissions_cf7b') || ($page == 'themes_cf7b'))) {
         $page = str_replace("_cf7b","",$page);
         require_once ($this->plugin_dir . '/admin/controllers/' . $page . '.php');
         $controller_class = 'Controller' . ucfirst($page).'_cf7b';
         new $controller_class();
     } else {
       if ( is_admin() ) {
         require_once(wp_normalize_path($this->plugin_dir . '/admin/controllers/controller.php'));
         $this->controller = new Controller_cf7b();
       }
     }
   }


   /* Show message if contact form 7 plugin is not active */
  public function my_error_notice() {
   ?>
   <div class="error notice">
     <p><?php _e( 'Please install or activate Contact Form 7 plugin to start using Builder for Contact Form 7 plugin.', 'cf7b' ); ?></p>
   </div>
   <?php
  }


   /**
   * Register form preview custom post type.
   */
  public function cf7b_register_cfB_preview_cpt() {

    $preview_id = CF7B_Library::cf7b_get_preview_id();
    if ( empty($preview_id) ) {
        $post_params = array(
          'post_content' => '',
          'post_title' => 'CFB PREVIEW',
          'post_status' => 'trash',
          'post_type' => 'wpcf7_contact_form',
          'comment_status' => 'closed',
          'ping_status' => 'closed',
        );
        $insert_id = wp_insert_post($post_params);
    } else {
        $insert_id = $preview_id;
    }

    $this->cf7b_register_preview_cpt();
    $this->cf7b_get_form_preview_post($insert_id);

  }

  /**
   * Register form preview custom post type.
   */
  public function cf7b_register_preview_cpt() {
    $args = array(
      'label' => 'CF7B Preview',
      'public' => true,
      'publicly_queryable' => true,
      'exclude_from_search' => true,
      'show_in_menu' => false,
      'show_in_nav_menus' => false,
      'create_posts' => 'do_not_allow',
      'capabilities' => array(
        'create_posts' => FALSE,
        'edit_post' => 'edit_posts',
        'read_post' => 'edit_posts',
        'delete_posts' => FALSE,
      )
    );

    register_post_type('cf7b_builder', $args);
    flush_rewrite_rules();
  }

  /**
   * Create Preview Form post.
   *
   * @return string $guid
   */
  public function cf7b_get_form_preview_post( $form_post_id ) {
    $post_type = 'cf7b_builder';
    $row = get_posts(array( 'post_type' => $post_type ));

    if ( !empty($row[0]) ) {
      //var_dump($row[0]->ID); die();
      $id = $row[0]->ID;
      $permalink = get_post_permalink(intval($id));
      update_option('cf7b_preview_permalink', $permalink);
    }
    else {
      $post_params = array(
        'post_author' => 1,
        'post_status' => 'publish',
        'post_content' => '[contact-form-7 id="' . $form_post_id . '" title="temp"]',
        'post_title' => 'Preview',
        'post_type' => $post_type,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_parent' => 0,
        'menu_order' => 0,
        'import_id' => 0,
      );
      // Create new post by fmformpreview type.
      $insert_id = wp_insert_post($post_params);
      if ( !is_wp_error($insert_id) ) {
        //flush_rewrite_rules();
        $permalink = get_post_permalink($insert_id);
        update_option('cf7b_preview_permalink', $permalink);
      }

    }
  }


  /* Create revision popup view */
  public function cf7b_prepare_revision() {
    require_once(wp_normalize_path($this->plugin_dir . '/admin/controllers/controller.php'));
    $this->controller = new Controller_cf7b();
    $this->controller->cf7b_create_popup_revision();
  }

  /**
   * Add tab of plugin using CF7 hook
   *
   * @param array $panels getting from cf7 hook
   *
   * @return array
  */
  public function cf7b_visual_panel_load( $panels ) {
    // make filter magic happen here...
    $tab['visual-panel'] = array(
      'title'=>'Builder',
      'callback' => array($this,'wpcf7_editor_panel_settings')
    );
     $panels = $tab+$panels;
    return $panels;
  }

   public function wpcf7_editor_panel_settings() {
     require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/controllers/tabSettings.php') );
     new ControllerTabSettings_cf7b();
   }

  /**
   * Run function after cf7 save
   *
   * @param object $cf_data getting from cf7 hook
  */
  public function cf7b_action_wpcf7_after_save($cf_data) {
    $params['post_id'] = intval($cf_data->id());
    $params['cf7b_active_theme'] = isset($_POST['cf7b_active_theme']) ? intval($_POST['cf7b_active_theme']) : 0;
    $params['cf7b_action_after_submit'] = isset($_POST['cf7b_action_after_submit']) ? intval($_POST['cf7b_action_after_submit']) : 0;
    $params['cf7b_aftersubmit_page'] = isset($_POST['cf7b_aftersubmit_page']) ? intval($_POST['cf7b_aftersubmit_page']) : 0;
    $params['cf7b_aftersubmit_post'] = isset($_POST['cf7b_aftersubmit_post']) ? intval($_POST['cf7b_aftersubmit_post']) : 0;
    $params['cf7b_aftersubmit_text'] = isset($_POST['cf7b_aftersubmit_text']) ? esc_html($_POST['cf7b_aftersubmit_text']) : '';
    $params['cf7b_aftersubmit_custom'] = isset($_POST['cf7b_aftersubmit_custom']) ? sanitize_url($_POST['cf7b_aftersubmit_custom']) : '';
    $params['cf7b_task'] = 'cf7b_save_tabSettings';
    require_once(wp_normalize_path($this->plugin_dir . '/admin/controllers/tabSettings.php'));
    $this->controller = new ControllerTabSettings_cf7b( $params );

    $params['template'] = $cf_data->prop('form');
    require_once(wp_normalize_path($this->plugin_dir . '/admin/controllers/controller.php'));
    $this->controller = new Controller_cf7b();
    $this->controller->cf7b_create_revision($params);

  }

  /**
   * Enqueue a script in the WordPress admin on edit.php.
   */
  public function cf7b_backend_js_css() {

    if($this->cf7b_check_cf7_page_is()) {
      wp_enqueue_script( 'thickbox' );
      wp_enqueue_style( 'thickbox' );

      wp_enqueue_style('dashicons');
      wp_enqueue_script('jquery-ui-sortable');
      wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . 'script/cf7b_admin.js', array( 'jquery' ), $this->plugin_version);
      wp_localize_script('my_custom_script', 'cf7b_object', array(
        'loader_url' => $this->plugin_url . "/images/loading.gif",
        'preview_url' => get_option('cf7b_preview_permalink'),
        'is_pro' => $this->is_pro,
      ));
      wp_register_style('cfB_backend', plugins_url(plugin_basename(dirname(__FILE__))) . '/style/cf7b_admin.css', FALSE, $this->plugin_version);
      wp_print_styles('cfB_backend');
    }
    if( !CF7B_PRO ) {
      wp_register_style('cfB_topbar', plugins_url(plugin_basename(dirname(__FILE__))) . '/style/cf7b_topbar.css', FALSE, $this->plugin_version);
      wp_print_styles('cfB_topbar');
    }

    wp_register_style('cfB_themes', plugins_url(plugin_basename(dirname(__FILE__))) . '/style/cf7b_themes.css', FALSE, $this->plugin_version);
    wp_register_script('cfB_themes', plugins_url(plugin_basename(dirname(__FILE__))) . '/script/cf7b_themes.js', array('jquery'), $this->plugin_version);
    wp_register_script('cf7b_submissions', plugins_url(plugin_basename(dirname(__FILE__))) . '/script/cf7b_submissions.js', array('jquery'), $this->plugin_version);
    wp_register_style('cf7b_submissions', plugins_url(plugin_basename(dirname(__FILE__))) . '/style/cf7b_submissions.css', FALSE, $this->plugin_version);

  }

   /* Check if admin page is cf7 page */
   public function cf7b_check_cf7_page_is() {
     $page = isset($_GET['page']) ? esc_html($_GET['page']) : '';
     if ( is_admin() && $page != '' && ($page == 'overview_cf7b' || $page == 'overview_cf7b' || $page == 'wpcf7-new' || $page == 'wpcf7') || (isset($_POST['action']) && esc_html($_POST['action']) == 'cf7b_ajax') ) {
       return true;
     }
     return false;
   }


   public function cf7b_front_js_css() {
/*      wp_register_style('cfB_frontend', plugins_url(plugin_basename(dirname(__FILE__))) . '/style/cf7b_frontend.css', FALSE, $this->plugin_version);
      wp_print_styles('cfB_frontend');
      wp_enqueue_script('cfB_frontend', plugins_url(plugin_basename(dirname(__FILE__))) . '/script/frontend.js', array('jquery'), $this->plugin_version);*/
  }

 }

/**
 * Main instance of cfBuilder.
 *
 * @return cf7Builder The main instance to prevent the need to use globals.
 */
if (!function_exists('cf7BInstance')) {
  function cf7BInstance() {
    return cf7Builder::instance();
  }
}

cf7BInstance();


add_action( 'wpcf7_before_send_mail', 'cf7b_submissions', 10, 3);
function cf7b_submissions( $contact_form, $abort, $submission ) {
  // Getting user input through the your-email field

  $preview_id = CF7B_Library::cf7b_get_preview_id();

  $preview_post_id = get_option('cf7b_preview_post_id');
  $form_id = ($preview_id == $contact_form->id()) ? $preview_post_id : $contact_form->id();

  $data = array(
    'form_id'     => $form_id,
    'fields'      => $submission->get_posted_data(),
    'ip_address'  => $submission->get_meta( 'remote_ip' ),
    'user_agent'  => $submission->get_meta( 'user_agent' )
  );
  require_once('admin/controllers/submissions.php');
  $ob = new ControllerSubmissions_cf7b('save_submissions');
  $ob->save_submission( $data );
}

?>