<?php
/**
 * Plugin Name: Webyx FE
 * Description: Create amazing fullpage fullscreen scrolling websites with our fast, configurable and easy extension for Elementor.
 * Requires at least: 6.0
 * Requires PHP: 7.2
 * Version: 1.1.6
 * Author: Webineer Team 
 * Author URI: https://webyx.it/wfe-guide
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: webyx-fe
 * @package webyx-fe
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'WEBYX_FE__FILE__', __FILE__ );
define( 'WEBYX_FE_PATH', plugin_dir_path( WEBYX_FE__FILE__ ) );
define( 'WEBYX_FE_MINIMUM_ELEMENTOR_VERSION', '3.4.7' );
define( 'WEBYX_FE_WP_MIN_VERSION', '5.7' );
define( 'WEBYX_FE_PHP_MIN_VERSION', '7.2' );
define( 'WEBYX_FE_ASSET_MIN', TRUE );
if ( ! version_compare( PHP_VERSION, WEBYX_FE_PHP_MIN_VERSION, '>=' ) ) {
  function webyx_fe_fail_php_version () {
    $message = sprintf( esc_html__( 'Webyx FE plugin requires PHP version %s+, plugin is currently NOT RUNNING.' ), WEBYX_FE_PHP_MIN_VERSION );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo wp_kses_post( $html_message );
  }
  add_action( 'admin_notices', 'webyx_fe_fail_php_version' );
} else if ( ! version_compare( get_bloginfo( 'version' ), WEBYX_FE_WP_MIN_VERSION, '>=' ) ) {
  function webyx_fe_fail_wp_version () {
    $message = sprintf( esc_html__( 'Webyx FE requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.' ), WEBYX_FE_WP_MIN_VERSION );
    $html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
    echo wp_kses_post( $html_message );
  }
  add_action( 'admin_notices', 'webyx_fe_fail_wp_version' );
} else {
  if ( ! class_exists( 'Webyx_FE' ) ) {
    class Webyx_FE {
      private static $webyx_fe_instance = NULL;
      private $is_open_section = FALSE;
      private $fl = TRUE;
      private $templates;
      private $slug = 'webyx-fe';
      private $nada = array();
      private $webyx_section_name = '';
      private $cubic_bezier_animation = array(
        'cubic-bezier(0.64,0,0.34,1)',
        'cubic-bezier(0,0,1,1)',
        'cubic-bezier(0.25,0.1,0.25,1)',
        'cubic-bezier(0.42,0,1,1)',
        'cubic-bezier(0,0,0.58,1)',
      );
      private $tag_name = array(
        'div',
        'section',
        'article',
        'aside',
        'header',
        'footer',
        'ul',
        'ol',
        'li',
      );
      public function __construct () {
        $this->templates = array( 
          'templates/page-webyx-fe.php' => 'webyx FE'
        );
        add_action( 
          'plugins_loaded', 
          array(
            $this, 
            'webyx_fe_on_plugins_loaded' 
          )
        );
      }
      public static function webyx_fe_get_instance () {
        if ( is_null( self::$webyx_fe_instance ) ) {
          self::$webyx_fe_instance = new self();
        }
        return self::$webyx_fe_instance;
      }
      public function webyx_fe_i18n () {
        load_plugin_textdomain( $this->slug );
      }
      public function webyx_fe_sanitize_hex_color ( $color ) {
        $color = preg_replace( '/[^0-9a-fA-F]/', '', $color );
        $length = strlen( $color );
        if ( $length === 3 || $length === 4 ) {
          $color = preg_replace( '/(.)/', '$1$1', $color );
          $length = strlen( $color );
        }
        if ( $length === 6 || $length === 8 ) {
          return $color;
        }
        return false;
      }
      public function webyx_fe_chk_intgr ( $content ) { 
        $pattern = "/data-webyx=\"webyx-fe-fl\"/";
        $chk_intgr = preg_match( $pattern, $content );
        if ( $this->fl && $chk_intgr  ) {
          $this->fl = false;
          return true;
        }
      }
      public function webyx_fe_on_plugins_loaded () {
        if ( $this->webyx_fe_is_compatible() ) {
          add_action( 
            'elementor/init', 
            array(
              $this, 
              'webyx_fe_init'
            )
          );
        }
      }
      public function webyx_fe_is_compatible () {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ( is_plugin_active( 'webyx-fep/webyx-fep.php' ) ) {
          add_action( 
            'admin_notices', 
            array( 
              $this, 
              'webyx_fe_admin_notice_compatibility'
            ) 
          );
          return false;
        }
        if ( ! did_action( 'elementor/loaded' ) ) {
          add_action( 
            'admin_notices', 
            array( 
              $this, 
              'webyx_fe_admin_notice_missing_main_plugin'
            ) 
          );
          return false;
        }
        if ( ! version_compare( ELEMENTOR_VERSION, WEBYX_FE_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
          add_action( 
            'admin_notices', 
            array(
              $this, 
              'webyx_fe_admin_notice_minimum_elementor_version'
            )
          );
          return false;
        }
        if ( version_compare( PHP_VERSION, WEBYX_FE_PHP_MIN_VERSION, '<' ) ) {
          add_action( 
            'admin_notices', 
            array(
              $this, 
              'webyx_fe_admin_notice_minimum_php_version' 
            )
          );
          return false;
        }
        return true;
      }
      public function webyx_fe_get_global_elmnt_control_id ( $s, $el_control_id ) {
        $global_control_id = NULL;
        if ( isset( $s[ '__globals__' ] ) && ! empty( $s[ '__globals__' ][ $el_control_id ] ) ) {
          $q = $s[ '__globals__' ][ $el_control_id ];
          preg_match( "/&?id=([^&]+)/", $q, $matches );
          $global_control_id = $matches ? $matches[ 1 ] : NULL;
        } 
        return $global_control_id;
      }
      public function webyx_fe_get_global_elmnt_css_var_format ( $global_control_id ) {
        $global_css_var_format = NULL;
        if ( $global_control_id ) {
          $global_css_var_format = 'var(--e-global-color-' . $global_control_id . ')';
        }
        return $global_css_var_format;
      }
      public function webyx_fe_get_global_elmnt_control_value ( $s, $el_control_id, $default ) {
        $global_control_id = $this->webyx_fe_get_global_elmnt_control_id( $s, $el_control_id );
        $global_control_value = NULL;
        if ( $global_control_id ) {
          $global_control_value = $this->webyx_fe_get_global_elmnt_css_var_format( $global_control_id );
        } else {
          if ( isset( $s[ $el_control_id ] ) && $this->webyx_fe_sanitize_hex_color( $s[ $el_control_id ] ) ) {
            $global_control_value = $s[ $el_control_id ];
          } else {
            $global_control_value = $default;
          }
        }
        return $global_control_value;
      }
      public function webyx_fe_admin_notice_compatibility () {
        if ( isset( $_GET[ 'activate' ] ) )  {
          unset( $_GET[ 'activate' ] );
        }
        $message = sprintf(
          esc_html__( 
            '"%1$s" will not load together with the Pro version. If you don\'t want to see this message in the future, deactivate Webyx FE', 
            $this->slug 
          ),
          '<strong>' . esc_html__( 'Webyx FE', $this->slug ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
      }
      public function webyx_fe_admin_notice_missing_main_plugin () {
        if ( isset( $_GET[ 'activate' ] ) )  {
          unset( $_GET[ 'activate' ] );
        }
        $message = sprintf(
          esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', $this->slug ),
          '<strong>' . esc_html__( 'Webyx FE', $this->slug ) . '</strong>',
          '<strong>' . esc_html__( 'Elementor', $this->slug ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
      }
      public function webyx_fe_admin_notice_minimum_elementor_version () {
        if ( isset( $_GET[ 'activate' ] ) )  {
          unset( $_GET[ 'activate' ] );
        }
        $message = sprintf(
          esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', $this->slug ),
          '<strong>' . esc_html__( 'Webyx FE', $this->slug ) . '</strong>',
          '<strong>' . esc_html__( 'Elementor', $this->slug ) . '</strong>',
          WEBYX_FE_MINIMUM_ELEMENTOR_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
      }
      public function webyx_fe_admin_notice_minimum_php_version () {
        if ( isset( $_GET[ 'activate' ] ) )  {
          unset( $_GET[ 'activate' ] );
        }
        $message = sprintf(
          esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', $this->slug ),
          '<strong>' . esc_html__( 'Webyx FE', $this->slug ) . '</strong>',
          '<strong>' . esc_html__( 'PHP', $this->slug ) . '</strong>',
          WEBYX_FE_PHP_MIN_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
      }
      public function webyx_fe_is_frontend_view () {
        return ( ! Elementor\Plugin::$instance->editor->is_edit_mode() && ! Elementor\Plugin::$instance->preview->is_preview_mode() );
      }
      public function webyx_fe_is_enable ( $ps ) {
        return( isset( $ps ) && isset( $ps[ 'webyx_enable' ] ) && $ps[ 'webyx_enable' ] === 'on' );
      }
      public function webyx_fe_init () {
        $this->webyx_fe_i18n();
        $this->webyx_fe_admin_enqueue_scripts();
        $this->webyx_fe_admin_document_settings();
        $this->webyx_fe_admin_section_options();
        $this->webyx_fe_frontend_sections_content();
        $this->webyx_fe_frontend_enqueue_assets();
        $this->webyx_fe_admin_settings();
        $this->webyx_fe_top_admin_bar();
        $this->webyx_fe_init_menu();
        $this->webyx_fe_init_page_template();
        $this->webyx_fe_widget_editor_preview();
      }
      public function webyx_fe_is_container_active () {
        $experiments_manager = \Elementor\Plugin::$instance->experiments;
        return $is_container_active = $experiments_manager->is_feature_active( 'container' );
      }
      public function webyx_fe_widget_editor_preview () {
        $is_container_active = $this->webyx_fe_is_container_active();
        add_action( 
          'elementor/preview/enqueue_styles', 
          array(
            $this,
            'webyx_fe_section_enqueue_styles' 
          )
        );
        add_filter( 
          $is_container_active ? 'elementor/container/print_template' : 'elementor/section/print_template', 
          array(
            $this,
            'webyx_fep_widget_outline_js_template'
          ),
          10, 
          2 
        );
      }
      public function webyx_fep_widget_outline_js_template ( $template, $widget ) {
        global $post;
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        $widget_name = $widget->get_name();
        if ( $webyx_fe_is_enable ) {
          if ( 'container' === $widget_name ) {
            ob_start(); ?>
              <# if ( settings.webyx_section_enable ) { #>
                <# if ( 'boxed' === settings.content_width ) { #>
                  <div class="e-con-inner">
                <# } #>
                <# if ( settings.webyx_section_background ) { #>
                  <# if ( settings.webyx_section_background_image.url ) { #>
                    <div class="webyx-background-overlay" style="background-image: url({{settings.webyx_section_background_image.url}});background-size:{{settings.webyx_section_background_image_size}};background-position:{{settings.webyx_section_background_image_position}};background-repeat:{{settings.webyx_section_background_image_repeat}};background-attachment:{{settings.webyx_section_background_image_attachment}}"></div>
                  <# } else { #>
                    <div class="webyx-background-overlay webyx-background-overlay-bkg-color" style="background-color:var(--section-color-dsk);"></div>
                  <# } #>
                <# } else { #>
                  <div class="webyx-background-overlay"></div>
                <# } #>
                <div class="elementor-shape elementor-shape-top"></div>
                <div class="elementor-shape elementor-shape-bottom"></div>
                <# if ( 'boxed' === settings.content_width ) { #>
                  </div>
                <# } #>
              <# } else { #>
                <?php echo $template ?>
              <# } #>
            <?php $template = ob_get_clean();
          }
          if ( 'section' === $widget_name ) {
            ob_start(); ?>
              <# if ( settings.webyx_section_enable ) { #>
                <# if ( settings.webyx_section_background ) { #>
                  <# if ( settings.webyx_section_background_image.url ) { #>
                    <div class="webyx-background-overlay" style="background-image: url({{settings.webyx_section_background_image.url}});background-size:{{settings.webyx_section_background_image_size}};background-position:{{settings.webyx_section_background_image_position}};background-repeat:{{settings.webyx_section_background_image_repeat}};background-attachment:{{settings.webyx_section_background_image_attachment}}"></div>
                  <# } else { #>
                    <div class="webyx-background-overlay webyx-background-overlay-bkg-color" style="background-color:var(--section-color-dsk);"></div>
                  <# } #>
                <# } else { #>
                  <div class="webyx-background-overlay"></div>
                <# } #>
                <div class="elementor-shape elementor-shape-top"></div>
                <div class="elementor-shape elementor-shape-bottom"></div>
                <div class="elementor-container elementor-column-gap-{{ settings.gap }}"></div>
              <# } else { #>
                <# if ( settings.background_video_link ) {
                    let videoAttributes = 'autoplay muted playsinline';
                    if ( ! settings.background_play_once ) {
                      videoAttributes += ' loop';
                    }
                    view.addRenderAttribute( 'background-video-container', 'class', 'elementor-background-video-container' );
                    if ( ! settings.background_play_on_mobile ) {
                      view.addRenderAttribute( 'background-video-container', 'class', 'elementor-hidden-mobile' );
                    } #>
                    <div {{{ view.getRenderAttributeString( 'background-video-container' ) }}}>
                      <div class="elementor-background-video-embed"></div>
                      <video class="elementor-background-video-hosted elementor-html5-video" {{ videoAttributes }}></video>
                    </div>
                <# } #>
                  <div class="elementor-background-overlay"></div>
                  <div class="elementor-shape elementor-shape-top"></div>
                  <div class="elementor-shape elementor-shape-bottom"></div>
                  <div class="elementor-container elementor-column-gap-{{ settings.gap }}">
                </div>
              <# } #>
            <?php
            $template = ob_get_clean();
          }
        }
        return $template;
      }
      public function webyx_fe_section_enqueue_styles () {
        global $post;
        $is_frontend_view = $this->webyx_fe_is_frontend_view();
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        if ( $webyx_fe_is_enable ) {
          wp_enqueue_style( 
            'webyx-fe-section-css', 
            plugins_url( 
              WEBYX_FE_ASSET_MIN ? 'assets/css/webyx-section.min.css' : 'assets/css/webyx-section.css', 
              __FILE__ 
            ),
            array(),
            filemtime( 
              plugin_dir_path( __FILE__ ) . (  WEBYX_FE_ASSET_MIN ? 'assets/css/webyx-section.min.css' : 'assets/css/webyx-section.css' ) 
            )
          );
        }
      }
      public function webyx_fe_admin_settings () {
        add_action( 
          'init',
          array( 
            $this, 
            'webyx_fe_register_settings_admin_page' 
          ), 
          10 
        );
        add_action( 
          'admin_menu',
          array( 
            $this, 
            'webyx_fe_add_setting_admin_page' 
          ), 
          10 
        );
        add_action( 
          'admin_enqueue_scripts', 
          array(
            $this,
            'webyx_fe_enqueue_scripts_settings_admin_page'
          ),
          10
        );
        add_action( 
          'plugin_action_links_' . plugin_basename( __FILE__ ),
          array(
            $this,
            'webyx_fe_add_settings_link' 
          ),
          10 
        );
        add_filter( 
          'plugin_row_meta', 
          array(
            $this,
            'webyx_fe_append_support_and_faq_links' 
          ),
          10,
          4 
        );
      }
      public function webyx_fe_register_settings_admin_page () {
        register_setting(
          'webyx_fe_plugin_settings',
          'webyx_fe_hide_admin_top_bar',
          array(
            'default'      => 'true',
            'show_in_rest' => TRUE,
            'type'         => 'string',
          )
        );
        register_setting(
          'webyx_fe_plugin_settings',
          'webyx_fe_menu',
          array(
            'default'      => 'true',
            'show_in_rest' => TRUE,
            'type'         => 'string',
          )
        );
      }
      public function webyx_fe_add_setting_admin_page () {
        add_options_page(
          esc_html__( 'Webyx FE Settings', $this->slug ),
          esc_html__( 'Webyx FE Settings', $this->slug ),
          'manage_options',
          'webyx_fe_plugin_settings',
          array(
            $this,
            'webyx_fe_print_setting_admin_page'
          )
        );
      }
      public function webyx_fe_print_setting_admin_page () {
        echo '<div id="webyx-fe-settings"></div>';
      }
      public function webyx_fe_enqueue_scripts_settings_admin_page () {
        $dir = __DIR__;
        $script_asset_path = $dir . '/build/index.asset.php';
        if ( ! file_exists( $script_asset_path ) ) {
          throw new Error(
            'You need to run `npm start` or `npm run build` for the "webyx-fe-plugin" block first.'
          );
        }
        $admin_js = 'build/index.js';
        wp_enqueue_script(
          'webyx-fe-settings-admin-editor',
          plugins_url( $admin_js, __FILE__ ),
          array( 'wp-api', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n', 'wp-notices', 'wp-polyfill' ),
          filemtime( $dir . '/' . $admin_js )
        );
        $admin_css = 'build/index.css';
        wp_enqueue_style(
          'webyx-fe-settings-admin-style',
          plugins_url( $admin_css, __FILE__ ),
          array( 'wp-components' ),
          filemtime( $dir . '/' . $admin_css )
        );
      }
      public function webyx_fe_add_settings_link ( $links ) {
        $new_links = array(
          'Settings' => '<a href="options-general.php?page=webyx_fe_plugin_settings">' . esc_html__( 'Settings', $this->slug ) . '</a>',
          'Go to PRO' => '<a class="webyx-fe-go-pro" href="https://webineer.gumroad.com/l/webyx-for-elementor-pro" target="_blank">' . esc_html__( 'Go to PRO', $this->slug ) . '</a>',
        );
        $links = array_merge( $links, $new_links );
        return $links;
      }
      public function webyx_fe_append_support_and_faq_links ( $links_array, $plugin_file_name, $plugin_data, $status ) {
        if ( strpos( $plugin_file_name, basename(__FILE__) ) ) {
          $new_links = array(
            'Docs' => '<a href="https://webyx.it/wfe-guide" target="_blank">' . esc_html__( 'Docs', $this->slug ) . '</a>',
            'FAQs' => '<a href="https://webyx.it/wfe-guide#faq" target="_blank">' . esc_html__( 'FAQs', $this->slug ) . '</a>',
          );
          $links_array = array_merge( $links_array, $new_links );
        }
        return $links_array;
      }
      public function webyx_fe_top_admin_bar () {
        add_filter( 
          'show_admin_bar', 
          array( 
            $this, 
            'webyx_fe_toggle_top_admin_bar' 
          ) 
        );
      }
      public function webyx_fe_toggle_top_admin_bar ( $show_admin_bar ) {
        $page_id = get_queried_object_id();
        if ( 'page' === get_post_type( $page_id ) && 'templates/page-webyx-fe.php' === get_page_template_slug( $page_id ) ) {
          return ! get_option( 'webyx_fe_hide_admin_top_bar', 'true' );
        } else {
          return $show_admin_bar;
        }
      }
      public function webyx_fe_init_page_template () {
        add_filter(
          'theme_page_templates', 
          array( 
            $this, 
            'webyx_fe_add_new_template' 
          )
        );
        add_filter(
          'wp_insert_post_data', 
          array( 
            $this, 
            'webyx_fe_register_project_templates' 
          ) 
        );
        add_filter(
          'template_include', 
          array( 
            $this, 
            'webyx_fe_view_project_template'
          ), 
          99
        );
      }
      public function webyx_fe_add_new_template ( $posts_templates ) {
        $posts_templates = array_merge( $posts_templates, $this->templates );
        return $posts_templates;
      }
      public function webyx_fe_register_project_templates ( $atts ) {
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
          $templates = array();
        } 
        wp_cache_delete( $cache_key , 'themes');
        $templates = array_merge( $templates, $this->templates );
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );
        return $atts;
      }
      public function webyx_fe_view_project_template ( $template ) {
        global $post;
        $webyx_ttfn_path = WEBYX_FE_PATH . 'templates/page-webyx-fe.php';
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $_wp_page_template = get_post_meta( $post->ID, '_wp_page_template', true );
        $ctt = isset( $ps[ 'ctt' ] ) && in_array( $ps[ 'ctt' ], array( 'on', '' ), true ) ? $ps[ 'ctt' ] : '';
        $cttfn = isset( $ps[ 'cttfn' ] ) ? $ps[ 'cttfn' ] : '';
        if ( 'on' === $ctt ) {
          if ( sanitize_text_field( $cttfn ) ) {
            $cttfn_path = get_stylesheet_directory() . '/' . sanitize_text_field( $cttfn );
            if ( file_exists( $cttfn_path ) ) {
              return $cttfn_path;
            }
          } 
          if ( file_exists( $webyx_ttfn_path ) ) {
            return $webyx_ttfn_path;
          } 
        }
        if ( 'templates/page-webyx-fe.php' === $_wp_page_template ) {
          if ( file_exists( $webyx_ttfn_path ) ) {
            return $webyx_ttfn_path;
          }
        }
        return $template;
      }
      public function webyx_fe_init_menu () {
        add_action( 
          'init',
          array( 
            $this, 
            'webyx_fe_register_menu' 
          )
        );
        add_filter( 
          'get_custom_logo', 
          array( 
            $this, 
            'webyx_fe_get_custom_logo' 
          )
        );
      }
      public function webyx_fe_register_menu () {
        remove_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10, 4 );
        $webyx_fe_menu = get_option( 'webyx_fe_menu', 'true' );
        add_theme_support('menus');
        if ( $webyx_fe_menu ) {
          register_nav_menu(
            'webyx-menu', __( 'Webyx Menu', get_template_directory() . '/languages' )
          );
        }
      }
      public function webyx_fe_get_custom_logo ( $html ) {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' ); 
        if ( $image ) {
          $html = sprintf( 
            '<div class="webyx-logo-wrapper"><a href="%1$s" class="webyx-logo-img" rel="home" itemprop="url">%2$s</a></div>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( 
              $custom_logo_id, 
              'full', 
              false, 
              array()   
            )
          );
        }
        return $html;	
      }
      public function webyx_fe_admin_enqueue_scripts () {
        add_action(
          'elementor/editor/before_enqueue_scripts', 
          array(
            $this, 
            'webyx_fe_admin_styles'
          )
        );
        add_action(
          'elementor/editor/before_enqueue_scripts', 
          array(
            $this, 
            'webyx_fe_admin_script'
          )
        );
      }
      public function webyx_fe_admin_styles () {
        $fn = WEBYX_FE_ASSET_MIN ? 'assets/css/webyx-admin.min.css' : 'assets/css/webyx-admin.css';
        $path = plugins_url( 
          $fn, 
          __FILE__ 
        );
        wp_register_style( 
          'webyx-fe-admin-page-options-styles', 
          $path,
          array(),
          filemtime( 
            plugin_dir_path( __FILE__ ) . $fn 
          )
        );
        wp_enqueue_style( 'webyx-fe-admin-page-options-styles' );
      }
      public function webyx_fe_admin_script () {
        $fn = WEBYX_FE_ASSET_MIN ? 'assets/js/webyx-admin.min.js' : 'assets/js/webyx-admin.js';
        $path = plugins_url( 
          $fn, 
          __FILE__ 
        );
        wp_register_script( 
          'webyx-fe-admin-script', 
          $path,
          array(),
          filemtime( 
            plugin_dir_path( __FILE__ ) . $fn 
          )
        );
        wp_enqueue_script( 'webyx-fe-admin-script' );
      }
      public function webyx_fe_admin_document_settings () {
        add_action( 
          'elementor/element/wp-page/document_settings/after_section_end', 
          array(
            $this, 
            'webyx_fe_admin_document_setting_controls'
          ), 
          10, 
          2 
        );
      } 
      public function webyx_fe_enabled_controls ( $page ) {
        $page->start_controls_section(
          'webyx',
          array(
            'label' => esc_html__( 'WEBYX FE', $this->slug ),
            'tab'   => 'webyx-fe'
          )
        );
        $page->add_control(
          'webyx_enable',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Enable Webyx', $this->slug ),
            'description'  => esc_html__( 'Enable Webyx to create cool fullpage fullscreen scrolling websites.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'webyx_reload',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'description' => esc_html__( 'IMPORTANT: click here after you have toggled the \'Enable Webyx\' option to reload and apply the changes.', $this->slug ),
            'text'        => esc_html__( 'Apply changes', $this->slug ),
            'button_type' => 'success',
            'event'       => 'webyxReload',
            'classes'     => 'webyx-reload-button-disabled',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_tmp_design_controls ( $page ) {
        $page->start_controls_section(
          'webyx_template_design',
          array(
            'label'     => esc_html__( 'TEMPLATE DESIGN', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'ctt',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Custom template', $this->slug ),
            'description'  => esc_html__( 'Enable custom template.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'cttfn',
          array(
            'type'        => \Elementor\Controls_Manager::TEXT,
            'label'       => esc_html__( 'Template file name', $this->slug ),
            'description' => esc_html__( 'You can provide your own custom template, such as a modified version of the theme template. Put the template path here if you want to use your own. If left empty or if you write something that doesn\'t exist or it is wrong, the empty Webyx predefined page template will be used.', $this->slug ),
            'default'     => esc_html__( '', $this->slug ),
            'placeholder' => esc_html__( 'template-file-name.php', $this->slug ),
            'condition' => array(
              'ctt' => 'on',
            ),
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_nav_easing_controls ( $page ) {
        $page->start_controls_section(
          'webyx_slide_easings',
          array(
            'label'     => esc_html__( 'NAVIGATION EASINGS', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->start_controls_tabs(
          'webyx_easing_card_tabs'
        );
        $page->start_controls_tab(
          'webyx_easing_vertical_card_tab',
          array(
            'label' => esc_html__( 'Vertical', $this->slug ),
          )
        );
        $page->add_control(
          'vmsd',
          array(
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'label'       => esc_html__( 'Speed', $this->slug ),
            'description' => esc_html__( 'Speed for the vertical scrolling transition to Section in miliseconds (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
            'min'         => 300,
            'max'         => 1200,
            'step'        => 1,
            'default'     => 900,
          )
        );
        $page->add_control(
          'vmcd',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Easing', $this->slug ),
            'default'     => 'cubic-bezier(0.64,0,0.34,1)',
            'description' => esc_html__( 'Set the vertical animation easing from a pre-estabilished set of curve types.', $this->slug ),
            'options'     => array(
              'cubic-bezier(0.64,0,0.34,1)' => esc_html__( 'default', $this->slug ),
              'cubic-bezier(0,0,1,1)' => esc_html__( 'linear', $this->slug ),
              'cubic-bezier(0.25,0.1,0.25,1)' => esc_html__( 'ease', $this->slug ),
              'cubic-bezier(0.42,0,1,1)' => esc_html__( 'easein', $this->slug ),
              'cubic-bezier(0,0,0.58,1)' => esc_html__( 'easeout', $this->slug )
            ),
          )
        );
        $page->end_controls_tab();
        $page->start_controls_tab(
          'webyx_easing_horizontal_card_tab',
          array(
            'label' => esc_html__( 'Horizontal', $this->slug ),
          )
        );
        $page->add_control(
          'hmsd',
          array(
            'type'         => \Elementor\Controls_Manager::NUMBER,
            'label'        => esc_html__( 'Speed', $this->slug ),
            'description' => esc_html__( 'Speed for the horizontal scrolling transition to Section in miliseconds (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
            'min'          => 300,
            'max'          => 1200,
            'step'         => 1,
            'default'      => 900,
          )
        );
        $page->add_control(
          'hmcd',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Easing', $this->slug ),
            'description' => esc_html__( 'Set the horizontal animation easing from a pre-estabilished set of curve types.', $this->slug ),
            'default'     => 'cubic-bezier(0.64,0,0.34,1)',
            'options'     => array(
              'cubic-bezier(0.64,0,0.34,1)' => esc_html__( 'default', $this->slug ),
              'cubic-bezier(0,0,1,1)' => esc_html__( 'linear', $this->slug ),
              'cubic-bezier(0.25,0.1,0.25,1)' => esc_html__( 'ease', $this->slug ),
              'cubic-bezier(0.42,0,1,1)' => esc_html__( 'easein', $this->slug ),
              'cubic-bezier(0,0,0.58,1)' => esc_html__( 'easeout', $this->slug )
            ),
          )
        );
        $page->end_controls_tab();
        $page->end_controls_tabs();
        $page->add_control(
          'webyx_fe_pro_hmcd_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Easing Pro',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_hmcd',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#animation-easings-and-speeds" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_nav_arrows_controls ( $page ) {
        $page->start_controls_section(
          'webyx_navigation_arrows', 
          array(
            'label'     => esc_html__( 'NAVIGATION ARROWS', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'av',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows', $this->slug ),
            'description'  => esc_html__( 'Enable navigation arrows on every Section.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'webyx_navigation_arrows_vertical_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Arrows vertical',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_navigation_arrows_vertical',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-arrows" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'webyx_navigation_arrows_horizontal_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Arrows horizontal',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_navigation_arrows_horizontal',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-arrows" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'avf',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Fixed arrows', $this->slug ),
            'description'  => esc_html__( 'Makes arrows persistent. If disabled arrows will vanish and reapper on mouse hover.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_navigation_arrows_type_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Arrows type',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_navigation_arrows_type',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-arrows" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'mvnast',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Arrows size', $this->slug ),
            'description' => esc_html__( 'Choose navigation arrows size.', $this->slug ),
            'default'     => 'medium',
            'options' => array(
              'small'  => esc_html__( 'small',  $this->slug ),
              'medium' => esc_html__( 'medium', $this->slug ),
              'large'  => esc_html__( 'large',  $this->slug )
            ),
            'condition' => array(
              'av' => 'on',
            ),
            'separator' => 'before',
          )
        );
        $page->add_control(
          'mvnatt',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Arrows thickness', $this->slug ),
            'description' => esc_html__( 'Choose navigation arrows thickness.', $this->slug ),
            'default'     => 'standard',
            'options'     => array(
              'thin'     => esc_html__( 'thin',     $this->slug ),
              'standard' => esc_html__( 'standard', $this->slug ),
              'thick'    => esc_html__( 'thick',    $this->slug )
            ),
            'condition' => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaad',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Arrows area', $this->slug ),
            'description' => esc_html__( 'Choose arrows dimension area type in pixels.', $this->slug ),
            'default'     => 'medium',
            'options'     => array(
              'small'  => esc_html__( 'small (80x50) pixels',   $this->slug ),
              'medium' => esc_html__( 'medium (150x70) pixels', $this->slug ),
              'large'  => esc_html__( 'large (300x90) pixels',  $this->slug ),
            ),
            'condition' => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnac',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Arrows colour', 'plugin-domain' ),
            'description' => esc_html__( 'Choose navigation arrows colour.', $this->slug ),
            'default'     => '#000000',
            'condition'   => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnacl',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Arrows colour light', 'plugin-domain' ),
            'description' => esc_html__( 'Choose navigation arrows colour light.', $this->slug ),
            'default'     => '#000000',
            'condition'   => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnact',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows curvature', $this->slug ),
            'description'  => esc_html__( 'Enable a slight curvature to the navigation arrows aesthetics.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaa',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows background area', $this->slug ),
            'description' => esc_html__( 'Enable visible background area for every arrow.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaac',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Arrows background area colour', $this->slug ),
            'description' => esc_html__( 'Choose navigation arrows background area colour.', $this->slug ),
            'default' => '#00000066',
            'condition' => array(
              'av'    => 'on',
              'mvnaa' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaoc',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows custom offset', $this->slug ),
            'description' => esc_html__( 'Enable custom positioning for every arrow.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'av' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaot',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Arrow top position offset', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a top offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'av'     => 'on',
              'mvnaoc' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaor',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Arrow right position offset', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a right offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'av'     => 'on',
              'mvnaoc' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaob',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Arrow bottom position offset', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a bottom offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'av'     => 'on',
              'mvnaoc' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvnaol',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Arrow left position offset', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a left offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'av'     => 'on',
              'mvnaoc' => 'on',
            ),
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_nav_bullets_controls ( $page ) {
        $page->start_controls_section(
          'webyx_navigation_bullets',
          array(
            'label'     => esc_html__( 'NAVIGATION BULLETS', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->start_controls_tabs(
          'webyx_bullets_style'
        );
        $page->start_controls_tab(
          'webyx_vertical_bullets_tab',
          array(
            'label' => esc_html__( 'Vertical', $this->slug )
          )
        );
        $page->add_control(
          'dv',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Bullets', $this->slug ),
            'description'  => esc_html__( 'Enable vertical navigation bullets on every Section.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'dvp',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Bullets position', $this->slug ),
            'description' => esc_html__( 'Choose vertical navigation bullets position.', $this->slug ),
            'default'     => 'right',
            'options'     => array(
              'left'  => esc_html__( 'left',  $this->slug ),
              'right' => esc_html__( 'right', $this->slug )
            ),
            'condition' => array(
              'dv' => 'on',
            ),
          )
        );
        $page->add_control(
          'dtvoff',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Bullets offset', $this->slug ),
            'description'  => esc_html__( 'Enable bullets offset.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'dv' => 'on',
            ),
          )
        );
        $page->add_control(
          'dtvoffdsk',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Vertical bullet offset desktop', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a vertical offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'dv'     => 'on',
              'dtvoff' => 'on',
            ),
          )
        );
        $page->add_control(
          'dtvoffmob',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Vertical bullet offset mobile', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a vertical offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'dv'     => 'on',
              'dtvoff' => 'on',
            ),
          )
        );
        $page->add_control(
          'dtv',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Tooltips', $this->slug ),
            'description'  => esc_html__( 'Displays vertical Section name on mouse hover.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'dv' => 'on',
            ),
          )
        );
        $page->add_control(
          'dtvcp',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Fixed tooltips', $this->slug ),
            'description'  => esc_html__( 'Vertical bullet tooltips are now persistent.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'dv'  => 'on',
              'dtv' => 'on',
            ),
          )
        );
        $page->end_controls_tab();
        $page->start_controls_tab(
          'webyx_horizontal_bullets_tab',
          array(
            'label' => esc_html__( 'Horizontal', $this->slug ),
          )
        );
        $page->add_control(
          'dh',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Bullets', $this->slug ),
            'description'  => esc_html__( 'Enable horizontal navigation bullets on every Section.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'dhp',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Bullets position', $this->slug ),
            'description' => esc_html__( 'Choose horizontal navigation bullets position.', $this->slug ),
            'default'     => 'bottom',
            'options'     => array(
              'top'    => esc_html__( 'top',    $this->slug ),
              'bottom' => esc_html__( 'bottom', $this->slug )
            ),
            'condition' => array(
              'dh' => 'on',
            ),
          )
        );
        $page->add_control(
          'dthoff',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Bullets offset', $this->slug ),
            'description'  => esc_html__( 'Enable bullets offset.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'dh' => 'on',
            ),
          )
        );
        $page->add_control(
          'dthoffdsk',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Horizontal bullet offset desktop', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a horizontal offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'dh'     => 'on',
              'dthoff' => 'on',
            ),
          )
        );
        $page->add_control(
          'dthoffmob',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Horizontal bullet offset mobile', $this->slug ),
            'description' => esc_html__( 'Insert a value to apply a vertical offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'dh'     => 'on',
              'dthoff' => 'on',
            ),
          )
        );
        $page->add_control(
          'dth',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Tooltips', $this->slug ),
            'description'  => esc_html__( 'Displays horizontal Section name on mouse hover.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'dh' => 'on',
            ),
          )
        );
        $page->add_control(
          'dthcp',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Fixed tooltips', $this->slug ),
            'description'  => esc_html__( 'Horizontal bullet tooltips are now persistent.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'dh'  => 'on',
              'dth' => 'on',
            ),
          )
        );
        $page->add_control(
          'dhs',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Solo bullet', $this->slug ),
            'description'  => esc_html__( 'Displays a bullet in the case of a single horizontal Section.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'    => array(
              'dh' => 'on',
            ),
          )
        );
        $page->end_controls_tab();
        $page->end_controls_tabs();
        $page->add_control(
          'mvndbst',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Bullets type', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets type.', $this->slug ),
            'default'     => 'scale',
            'separator'   => 'before',
            'options'     => array(
              'scale' => esc_html__( 'scale',        $this->slug ),
              'stroke' => esc_html__( 'stroke',       $this->slug ),
              'small_stroke' => esc_html__( 'small stroke', $this->slug )
            ),
          )
        );
        $page->add_control(
          'webyx_mvndbst_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Bullets type Pro',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_mvndbst',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-bullets" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control( 
          'mvndc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Bullets colour', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets colour.', $this->slug ),
            'default'     => '#000000',
          )
        );
        $page->add_control(
          'mvndcl',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Bullets colour light', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets colour light.', $this->slug ),
            'default'     => '#00000066',
          )
        );
        $page->add_control(
          'dbkgace',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Bullets background area', $this->slug ),
            'description'  => esc_html__( 'Enable bullets background area.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'dbkgac',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Bullets background area colour', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets background area colour.', $this->slug ),
            'default'     => '#00000066',
            'condition' => array(
              'dbkgace' => 'on',
            ),
          )
        );
        $page->add_control(
          'mvndttc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Bullet tooltip text colour', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets tooltip text colour.', $this->slug ),
            'default'     => '#000000',
          )
        );
        $page->add_control(
          'mvndttcl',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Bullet tooltip text colour light', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets tooltip text colour light.', $this->slug ),
            'default'     => '#00000066',
          )
        );
        $page->add_control(
          'mvndttace',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Bullet tooltip area', $this->slug ),
            'description'  => esc_html__( 'Enable bullet tooltip area.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'mvndttac',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Bullet tooltip area colour', $this->slug ),
            'description' => esc_html__( 'Choose navigation bullets tooltip area colour.', $this->slug ),
            'default'     => '#ffffff',
            'condition' => array(
              'mvndttace' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_mvndtane_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Bullet animation',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_mvndtane',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-bullets" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_nav_mw_controls ( $page ) {
        $page->start_controls_section(
          'webyx_mouse_wheel',
          array(
            'label'     => esc_html__( 'NAVIGATION MOUSE WHEEL', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'nvvw',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Mouse wheel navigation', $this->slug ),
            'description'  => esc_html__( 'Enable vertical navigation with mouse wheel.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'avvd',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Wheel icon', $this->slug ),
            'description'  => esc_html__( 'If mouse wheel icon fixed option is not enabled this icon will disappear after first vertical movement. WARNING: this icon will be shown ONLY if mouse wheel option is enabled. With this option vertical navigation arrows will be replaced by a mouse wheel icon that will remain visible until the first movement between Slides.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'nvvw' => 'on',
            ),
          )
        );
        $page->add_control(
          'msiwc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Wheel icon colour', $this->slug ),
            'description' => esc_html__( 'Choose mouse icon colour.', $this->slug ),
            'default'     => '#000000',
            'condition' => array(
              'nvvw' => 'on',
              'avvd' => 'on',
            ),
          )
        );
        $page->add_control(
          'msiwbce',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Wheel icon background', $this->slug ),
            'description' => esc_html__( 'Enable mouse wheel icon background.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'nvvw' => 'on',
              'avvd' => 'on',
            ),
          )
        );
        $page->add_control(
          'msiwbc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Wheel icon background colour', $this->slug ),
            'description' => esc_html__( 'Choose wheel icon background colour.', $this->slug ),
            'default'     => '#ffffff',
            'condition' => array(
              'nvvw'    => 'on',
              'avvd'    => 'on',
              'msiwbce' => 'on',
            ),
          )
        );
        $page->add_control(
          'iwhf',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Wheel icon fixed', $this->slug ),
            'description'  => esc_html__( 'Makes wheel icon persistent if present.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'nvvw' => 'on',
              'avvd' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_smooth_animation_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Horizontal smooth animation',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_smooth_animation',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-mouse-wheel" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_smooth_animation_duration_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Smooth animation duration',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_smooth_animation_duration',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-mouse-wheel" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_nav_kb_controls ( $page ) {
        $page->start_controls_section(
          'webyx_navigation_keyboard',
          array(
            'label'     => esc_html__( 'NAVIGATION KEYBOARD', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'kn',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Enable keyboard navigation', $this->slug ),
            'description'  => esc_html__( 'Enable website navigation with keyboard arrows.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_fsb_controls ( $page ) {
        $page->start_controls_section(
          'webyx_full_screen_button',
          array(
            'label'     => esc_html__( 'FULL SCREEN BUTTON', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsb',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Full screen button', $this->slug ),
            'description'  => esc_html__( 'Enable a button to switch to full screen display.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'fsp',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Button position', $this->slug ),
            'description' => esc_html__( 'Choose the position of the full screen button.', $this->slug ),
            'default'     => 'right',
            'options'     => array(
              'left'  => esc_html__( 'left',  $this->slug ),
              'right' => esc_html__( 'right', $this->slug ),
            ),
            'condition' => array(
              'fsb' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsdt',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Button thickness', $this->slug ),
            'default'     => '4px',
            'description' => esc_html__( 'Choose full screen button dimension thickness.', $this->slug ),
            'options'     => array(
              '2px' => esc_html__( 'thin',     $this->slug ),
              '4px' => esc_html__( 'standard', $this->slug ),
              '6px' => esc_html__( 'thick',    $this->slug ),
            ),
            'condition' => array(
              'fsb' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsboff',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Button custom offset', $this->slug ),
            'description'  => esc_html__( 'Enable custom positioning for the button.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'fsb' => 'on',
            ),
            )
          );
        $page->add_control(
          'fsofft',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Button top offset', $this->slug ),
            'description'  => esc_html__( 'Insert a value to apply a top offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'fsb'    => 'on',
              'fsboff' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsoffr',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Button right offset', $this->slug ),
            'description'  => esc_html__( 'Insert a value to apply a right offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'fsb'    => 'on',
              'fsp'    => 'right',
              'fsboff' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsoffl',
          array(
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'label'     => esc_html__( 'Button left offset', $this->slug ),
            'description'  => esc_html__( 'Insert a value to apply a left offset (range from 0 to 5000 pixels with a step of 1).', $this->slug ),
            'min'       => 0,
            'max'       => 5000,
            'step'      => 1,
            'default'   => 0,
            'condition' => array(
              'fsb'    => 'on',
              'fsp'    => 'left',
              'fsboff' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Button colour', $this->slug ),
            'description' => esc_html__( 'Choose button colour.', $this->slug ),
            'default'     => '#000000',
            'condition' => array(
              'fsb' => 'on',
            ),
          )
        ); 
        $page->add_control(
          'fsbce',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Button background', $this->slug ),
            'description' => esc_html__( 'Enable button background.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition' => array(
              'fsb' => 'on',
            ),
          )
        );
        $page->add_control(
          'fsbc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Button background colour', $this->slug ),
            'description' => esc_html__( 'Choose button background colour.', $this->slug ),
            'default'     => '#ffffff00',
            'condition' => array(
              'fsb'   => 'on',
              'fsbce' => 'on',
            ),
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_mob_controls ( $page ) {
        $page->start_controls_section(
          'webyx_mobile_device',
          array(
            'label'     => esc_html__( 'MOBILE DEVICE', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'fdskm',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Force desktop mode', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'description'  => esc_html__( 'WARNING: If enabled, Sections navigation will be possible through arrows/bullets/menu and NOT through swipe/scroll.', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->start_controls_tabs(
          'webyx_mobile_device_tabs',
          array(
            'condition' => array(
              'fdskm' => '',
            ),
          )
        );
        $page->start_controls_tab(
          'vertical_mobile_device_tab',
          array(
            'label' => esc_html__( 'Vertical', $this->slug ),
          )
        );
        $page->add_control(
          'vmsm',
          array(
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'label'       => esc_html__( 'Speed', $this->slug ),
            'description' => esc_html__( 'Speed for the vertical scrolling transition to Section in miliseconds on mobile device (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
            'min'         => 300,
            'max'         => 1200,
            'step'        => 1,
            'default'     => 300,
          )
        );
        $page->add_control(
          'vmcm',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Easing', $this->slug ),
            'description' => esc_html__( 'Set the vertical animation easing from a pre-estabilished set of curve types on mobile device.', $this->slug ),
            'default'     => 'cubic-bezier(0.64,0,0.34,1)',
            'options'     => array(
              'cubic-bezier(0.64,0,0.34,1)' => esc_html__( 'default', $this->slug ),
              'cubic-bezier(0,0,1,1)' => esc_html__( 'linear', $this->slug ),
              'cubic-bezier(0.25,0.1,0.25,1)' => esc_html__( 'ease', $this->slug ),
              'cubic-bezier(0.42,0,1,1)' => esc_html__( 'easein', $this->slug ),
              'cubic-bezier(0,0,0.58,1)' => esc_html__( 'easeout', $this->slug )
            ),
          )
        );
        $page->end_controls_tab();
        $page->start_controls_tab(
          'webyx_horizontal_mobile_device_tab',
          array(
            'label' => esc_html__( 'Horizontal', $this->slug ),
          )
        );
        $page->add_control(
          'hmsm',
          array(
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'label'       => esc_html__( 'Speed', $this->slug ),
            'description' => esc_html__( 'Speed for the horizontal scrolling transition to Section in miliseconds on mobile device (range from 300 to 1200 milliseconds with a step of 1).', $this->slug ),
            'min'         => 300,
            'max'         => 1200,
            'step'        => 1,
            'default'     => 300,
          )
        );
        $page->add_control(
          'hmcm',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Easing', $this->slug ),
            'description' => esc_html__( 'Set the horizontal animation easing from a pre-estabilished set of curve types on mobile device.', $this->slug ),
            'default'     => 'cubic-bezier(0.64,0,0.34,1)',
            'options'     => array(
              'cubic-bezier(0.64,0,0.34,1)' => esc_html__( 'default', $this->slug ),
              'cubic-bezier(0,0,1,1)' => esc_html__( 'linear', $this->slug ),
              'cubic-bezier(0.25,0.1,0.25,1)' => esc_html__( 'ease', $this->slug ),
              'cubic-bezier(0.42,0,1,1)' => esc_html__( 'easein', $this->slug ),
              'cubic-bezier(0,0,0.58,1)' => esc_html__( 'easeout', $this->slug )
            ),
          )
        );
        $page->end_controls_tab();
        $page->end_controls_tabs();
        $page->add_control(
          'webyx_hmcm_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Easing Pro',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_hmcm',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#mobile-settings" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'webyx_threshold_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Threshold',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_threshold',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#mobile-settings" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'webyx_horizontal_swipe_lock_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Horizontal swipe lock',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_horizontal_swipe_lock',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#mobile-settings" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'webyx_scrolling_gesture_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Horizontal scrolling gesture',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_scrolling_gesture',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#mobile-settings" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->add_control(
          'webyx_horizontal_scroll_velocity_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Horizontal scroll velocity',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_horizontal_scroll_velocity',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#mobile-settings" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_scrlb_controls ( $page ) {
        $page->start_controls_section(
          'webyx_scrollbar',
          array(
            'label'     => esc_html__( 'SCROLLBAR', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'scrlbd',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide scrollbar', $this->slug ),
            'description'  => esc_html__( 'Hides browser\'s default scrollbar in Sections when it should be present.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'webyx_scroll_reset_position_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Pro', $this->slug ),
            'label'       => 'Scroll reset position',
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-toggle-link',
            'separator'   => 'before',
          )
        );
        $page->add_control(
          'webyx_fe_pro_scroll_reset_position',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#scrollbar" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
            'classes'         => 'webyx-pro-toggle-link-description',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_splash_controls ( $page ) {
        $page->start_controls_section(
          'webyx_loading_splash_screen',
          array(
            'label'     => esc_html__( 'LOADING SPLASH SCREEN', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'ils',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Loading splash screen', $this->slug ),
            'description'  => esc_html__( 'Enable an initial loading splash screen.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'ilst',
          array(
            'type'        => \Elementor\Controls_Manager::SELECT,
            'label'       => esc_html__( 'Splash screen type', $this->slug ),
            'description' => esc_html__( 'Choose splash screen type.', $this->slug ),
            'default'     => 'default',
            'options'     => array(
              'default' => esc_html__( 'default', $this->slug ),
              'custom'  => esc_html__( 'custom', $this->slug ),
            ),
            'condition' => array(
              'ils' => 'on',
            ),
          )
        );
        $page->add_control(
          'ilsbc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Splash screen background colour', $this->slug ),
            'description' => esc_html__( 'Choose splash screen background colour.', $this->slug ),
            'default'     => '#9933CC',
            'condition'   => array(
              'ils'  => 'on',
              'ilst' => 'default',
            ),
          )
        );
        $page->add_control(
          'ilssbc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Spinner background colour', $this->slug ),
            'description' => esc_html__( 'Choose spinner background colour.', $this->slug ),
            'default'     => '#FFFFFF',
            'condition'   => array(
              'ils'  => 'on',
              'ilst' => 'default',
            ),
          )
        );
        $page->add_control(
          'ilscmt',
          array(
            'type'        => \Elementor\Controls_Manager::TEXT,
            'label'       => esc_html__( 'Initial loading message', $this->slug ),
            'description' => esc_html__( 'This text will be displayed on the splash screen.', $this->slug ),
            'default'     => esc_html__( '', $this->slug ),
            'placeholder' => esc_html__( 'initial message', $this->slug ),
            'condition'   => array(
              'ils'  => 'on',
              'ilst' => 'custom',
            ),
          )
        );
        $page->add_control(
          'ilscmtc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Splash screen text colour', $this->slug ),
            'description' => esc_html__( 'Choose splash screen text colour.', $this->slug ),
            'default'     => '#000000',
            'condition'   => array(
              'ils'  => 'on',
              'ilst' => 'custom',
            ),
          )
        );
        $page->add_control(
          'ilscbc',
          array(
            'type'        => \Elementor\Controls_Manager::COLOR,
            'label'       => esc_html__( 'Background colour', $this->slug ),
            'description' => esc_html__( 'Choose splash screen background colour.', $this->slug ),
            'default'     => '#FFFFFF',
            'condition'   => array(
              'ils'  => 'on',
              'ilst' => 'custom',
            ),
          )
        );
        $page->add_control(
          'ilscbiurl', 
          array(
            'type' => \Elementor\Controls_Manager::MEDIA,
            'label'=> esc_html__( 'Choose image', $this->slug ),
            'description' => esc_html__( 'Choose splash screen background image.', $this->slug ),
            'dynamic' => array(
              'active' => true,
              'categories' => array(
                \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
              ),
            ),
            'default' => array(
              'url' => '',
            ),
            'media_type'  => 'image',
            'render_type' => 'none',
            'condition'   => array(
              'ils'  => 'on',
              'ilst' => 'custom',
            ),
          )
        );
        $page->add_control(
          'ilsctmen',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Enable time duration', $this->slug ),
            'description'  => esc_html__( 'Enable a predefined time duration for the loading splash screen to be shown.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'   => array(
              'ils'  => 'on',
            ),
          )
        );
        $page->add_control(
          'ilscsi',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Always visible', $this->slug ),
            'description'  => esc_html__( 'The loading splash screen will be visible indefinitely. NOTE: use this option for developing reasons only, then disable it when you are satisfied with the result.', $this->slug ),
            'label_on'     => esc_html__( 'on',  $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
            'condition'   => array(
              'ils'      => 'on',
              'ilsctmen' => 'on',
            ),
          )
        );
        $page->add_control(
          'ilsctm',
          array(
            'type'        => \Elementor\Controls_Manager::NUMBER,
            'label'       => esc_html__( 'Time duration', $this->slug ),
            'description' => esc_html__( 'Enter a value for the time duration (range from 1 to 10 seconds with a step of 0.1).', $this->slug ),
            'min'         => 1,
            'max'         => 10,
            'step'        => 0.1,
            'default'     => 1,
            'condition'   => array(
              'ils'      => 'on',
              'ilsctmen' => 'on',
              'ilscsi'   => '',
            ),
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_custom_css_controls ( $page ) {
        $page->start_controls_section(
          'webyx_custom_css',
          array(
            'label'     => esc_html__( 'CUSTOM CSS', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'ccss',
          array(
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Custom CSS', $this->slug ),
            'description'  => esc_html__( 'Open a pop-up window where you can enter your CSS code for the page.', $this->slug ),
            'label_on'     => esc_html__( 'on', $this->slug ),
            'label_off'    => esc_html__( 'off', $this->slug ),
            'return_value' => 'on',
            'default'      => '',
          )
        );
        $page->add_control(
          'ccssp',
          array(
            'type'        => \Elementor\Controls_Manager::CODE,
            'description' => esc_html__( 'Enter your CSS code for the page.', $this->slug ),
            'language'    => 'css',
            'rows'        => 20,
            'default'     => '',
            'condition'   => array(
              'ccss' => 'on',
            ),
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_pro_controls ( $page ) {
        $page->start_controls_section(
          'webyx_fe_pro_section_view_design',
          array(
            'label'     => esc_html__( 'VIEW DESIGN', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_view_design',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#view-design" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_view_design_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_navigation_design',
          array(
            'label'     => esc_html__( 'NAVIGATION DESIGN', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_navigation_design',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-design" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_navigation_design_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_parallax_effect',
          array(
            'label'     => esc_html__( 'PARALLAX EFFECT', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_parallax_effect',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#navigation-design" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_parallax_effect_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_fade_animation',
          array(
            'label'     => esc_html__( 'FADE ANIMATION', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_fade_animation',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#indipendent-animations-on-x-and-y-axes" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_fade_animation_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_normal_scrolling',
          array(
            'label'     => esc_html__( 'NORMAL SCROLLING WEBSITE', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_normal_scrolling',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#normal-scrolling-website" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_normal_scrolling_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_continuous_vertical',
          array(
            'label'     => esc_html__( 'CONTINUOUS VERTICAL', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_continuous_vertical',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#continuous-vertical" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_continuous_vertical_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_horizontal_animation',
          array(
            'label'     => esc_html__( 'HORIZONTAL ANIMATION', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_horizontal_animation',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#horizontal-animation" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_horizontal_animation_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_background_audio',
          array(
            'label'     => esc_html__( 'BACKGROUND AUDIO', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_background_audio',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#background-audio" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_background_audio_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_event_hooks',
          array(
            'label'     => esc_html__( 'EVENT HOOKS', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_event_hooks',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#event-hooks" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_event_hooks_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
        $page->start_controls_section(
          'webyx_fe_pro_section_global_settings',
          array(
            'label'     => esc_html__( 'GLOBAL SETTINGS', $this->slug ),
            'tab'       => 'webyx-fe',
            'condition' => array(
              'webyx_enable' => 'on',
            ),
          )
        );
        $page->add_control(
          'webyx_fe_pro_global_settings',
          array(
            'type'            => \Elementor\Controls_Manager::RAW_HTML,
            'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#global-settings" target="_blank">Webyx website.</a>',
            'content_classes' => 'elementor-control-field-description',
          )
        );
        $page->add_control(
          'webyx_fe_pro_global_settings_link',
          array(
            'type'        => \Elementor\Controls_Manager::BUTTON,
            'text'        => esc_html__( 'Go to Pro', $this->slug ),
            'button_type' => 'default',
            'event'       => 'webyxProLink',
            'classes'     => 'webyx-pro-button-link',
          )
        );
        $page->end_controls_section();
      }
      public function webyx_fe_admin_document_setting_controls ( \Elementor\Core\DocumentTypes\Page $page ) {
        if ( isset( $page ) && $page->get_id() > '' ) {
          $post_type = get_post_type( $page->get_id() );
          if ( 'page' == $post_type || 'revision' == $post_type ) {
            \Elementor\Controls_Manager::add_tab(
              'webyx-fe',
              __( 'WEBYX FE', $this->slug )
            );
            $this->webyx_fe_enabled_controls( $page );
            $this->webyx_fe_tmp_design_controls( $page );
            $this->webyx_fe_nav_easing_controls( $page );
            $this->webyx_fe_nav_arrows_controls( $page );
            $this->webyx_fe_nav_bullets_controls( $page );
            $this->webyx_fe_nav_mw_controls( $page );
            $this->webyx_fe_nav_kb_controls( $page );
            $this->webyx_fe_fsb_controls( $page );
            $this->webyx_fe_mob_controls( $page );
            $this->webyx_fe_scrlb_controls( $page );
            $this->webyx_fe_splash_controls( $page );
            $this->webyx_fe_custom_css_controls( $page );
            $this->webyx_fe_pro_controls( $page );
          }
        }
      }
      public function webyx_fe_admin_section_options () {
        $is_container_active = $this->webyx_fe_is_container_active();
        add_action( 
          $is_container_active ? 'elementor/element/container/section_layout_container/before_section_start' : 'elementor/element/section/section_layout/before_section_start', 
          array( 
            $this, 
            'webyx_fe_admin_section_options_controls'
          ), 
          10, 
          2 
        );
      }
      public function webyx_fe_admin_section_options_controls ( \Elementor\Element_Base $element, $args ) {
        global $post;
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        $is_container_active = $this->webyx_fe_is_container_active();
        $el_root = $is_container_active ? 'Container' : 'Section';
        if ( $webyx_fe_is_enable ) {
          $element->start_controls_section(
            'webyx-section',
            array(
              'tab'           => \Elementor\Controls_Manager::TAB_LAYOUT,
              'label'         => esc_html__( 'WEBYX FE ' . $el_root, $this->slug ),
              'hide_in_inner' => true,
            )
          );
          $element->add_control(
            'webyx_section_enable',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Enable Webyx ' . $el_root, $this->slug ),
              'description'  => esc_html__( 'Enable to set this ' . $el_root . ' as a Webyx ' . $el_root . ' that will be wrapped and managed by the plugin. It will be considered a Webyx ' . $el_root . '. IMPORTANT: for Webyx to function properly, you must keep the root element active. In particular, pay attention if you are using Containers: those nested inside the root element MUST necessarily have this option deactivated, otherwise the system will not work correctly.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'render_type'  => 'template',
              'prefix_class' => 'webyx-section-',
            )
          );
          $element->add_control(
            'webyx_section_is_inner',
            array(
              'label' => esc_html__( 'View', 'textdomain' ),
              'type' => \Elementor\Controls_Manager::HIDDEN,
              'default' => 'on',
              'condition'   => array(
                'webyx_section_enable' => '',
              ),
            )
          );
          $element->add_control(
            'webyx_section_hide_in_frontend',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Hide ' . $el_root, $this->slug ),
              'description'  => esc_html__( 'Enable to set this ' . $el_root . ' NOT visible in the actual website page.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'prefix_class' => 'webyx-section-hide-',
            )
          );
          $element->add_control(
            'webyx_section_name',
            array(
              'type'        => \Elementor\Controls_Manager::TEXT,
              'label'       => esc_html__( $el_root . ' name', $this->slug ),
              'description' => esc_html__( 'Insert ' . $el_root . ' name. IMPORTANT: you should give different titles for each ' . $el_root . ' otherwise some features may have problems.', $this->slug ),
              'default'     => esc_html__( $el_root, $this->slug ),
              'placeholder' => esc_html__( $el_root . ' name', $this->slug ),
              'render_type' => 'none',
              'condition'   => array(
                'webyx_section_enable' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_fe_pro_anchor_name_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Anchor name (#)',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_anchor_name',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#anchor-name" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_section_type',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( $el_root . ' type', $this->slug ),
              'description' => esc_html__( 'FRONT ' . $el_root . ' are the first ones shown in the rows. SIDE ' . $el_root . ' are positioned laterally to the FRONT ones. All are shown in order from top to bottom. IMPORTANT: the very first ' . $el_root . ' of your website MUST be a Front ' . $el_root . '.', $this->slug ),
              'default'     => 'front',
              'options'     => array(
                'front' => esc_html__( 'front', $this->slug ),
                'side'  => esc_html__( 'side',  $this->slug ),
              ),
              'prefix_class' => 'webyx-section-',
              'classes'      => 'elementor-control-direction-ltr',
              'condition'    => array(
                'webyx_section_enable' => 'on',
              ),
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_section_type_header_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => esc_html__( $el_root . ' Header type', $this->slug ),
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_section_type_header',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#header-section" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_section_tag_name',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( $el_root . ' tag type', $this->slug ),
              'description' => esc_html__( 'Select HTML tag name. This parameter changes the ' . $el_root  . ' HTML tag to the specified tag.', $this->slug ),
              'default'     => 'div',
              'options'     => array(
                'div'     => esc_html__( 'div',     $this->slug ),
                'section' => esc_html__( 'section', $this->slug ),
                'article' => esc_html__( 'article', $this->slug ),
                'aside'   => esc_html__( 'aside',   $this->slug ),
                'header'  => esc_html__( 'header',  $this->slug ),
                'footer'  => esc_html__( 'footer',  $this->slug ),
                'ul'      => esc_html__( 'ul',      $this->slug ),
                'ol'      => esc_html__( 'ol',      $this->slug ),
                'li'      => esc_html__( 'li',      $this->slug ),
              ),
              'render_type' => 'none',
              'condition'   => array(
                'webyx_section_enable' => 'on',
              ),
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_section_mq_xs',
            array(
              'type'        => \Elementor\Controls_Manager::SLIDER,
              'label'       => esc_html__( $el_root . ' Media queries', $this->slug ),
              'description' => esc_html__( 'Enter a value that defines the threshold for switching from desktop to mobile mode in pixels (range from 0 to 5000 pixels with a step of 1). This option is used for margins and paddings in the ' . $el_root . ' Wrapper Content option.', $this->slug ),
              'size_units' => array( 
                'px', 
              ),
              'range' => array(
                'px' => array(
                  'min'  => 0,
                  'max'  => 5000,
                  'step' => 1,
                ),
              ),
              'default' => array(
                'unit' => 'px',
                'size' => 760,
              ),
              'separator'   => 'before',
              'render_type' => 'none',
              'condition'   => array(
                'webyx_section_enable' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_fe_pro_scrollable_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Scrolling content',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_scrollable',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#scrolling-content" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_fe_pro_continuous_carousel_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Continuous horizontal',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_continuous_carousel',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#horizontal-continuous" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_fe_pro_horizontal_scrolling_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Horizontal scrolling',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_horizontal_scrolling',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#horizontal-scrolling" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_section_cont_pos_enable',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( $el_root . ' content management', $this->slug ),
              'description'  => esc_html__( 'Enable content position management in the current ' . $el_root . '. If you are using Containers place the content using their properties, and keep this check disabled.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
              'render_type'  => 'none',
              'condition'    => array(
                'webyx_section_enable' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_cont_pos',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Content position', $this->slug ),
              'description' => esc_html__( 'Select general content position in the current ' . $el_root . '.', $this->slug ),
              'default'     => 'middle',
              'render_type' => 'none',
              'options'     => array(
                'top'    => esc_html__( 'top',    $this->slug ),
                'middle' => esc_html__( 'middle', $this->slug ),
                'bottom' => esc_html__( 'bottom', $this->slug ),
              ),
              'condition' => array(
                'webyx_section_enable'          => 'on',
                'webyx_section_cont_pos_enable' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wrapper content', $this->slug ),
              'description'  => esc_html__( 'Enable element wrapper for the ' . $el_root . '\'s content. If you are using Containers, it uses the container itself as a collector.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
              'render_type'  => 'none',
              'condition'    => array(
                'webyx_section_enable' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_classes',
            array(
              'type'        => \Elementor\Controls_Manager::TEXT,
              'label'       => esc_html__( 'Wrapper CSS class(es)', $this->slug ),
              'description' => esc_html__( 'Separate multiple classes with spaces.', $this->slug ),
              'default'     => esc_html__( '', $this->slug ),
              'placeholder' => esc_html__( 'class name', $this->slug ),
              'render_type' => 'none',
              'condition'   => array(
                'webyx_section_enable' => 'on',
                'webyx_section_wrapper_cnt'  => 'on',
              ),
              'render_type' => 'none',
            )
          );
          $element->start_controls_tabs(
            'webyx_section_wrapper_cnt_tabs',
            array(
              'condition' => array(
                'webyx_section_enable'      => 'on',
                'webyx_section_wrapper_cnt' => 'on',
              ),
            )
          );
          $element->start_controls_tab(
            'webyx_section_wrapper_cnt_desktop_tabs',
            array(
              'label' => esc_html__( 'Desktop', $this->slug ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_margin_enable_dsk',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wrapper margin', $this->slug ),
              'description'  => esc_html__( 'Enable wrapper margin for the ' . $el_root  . '\'s content.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'render_type'  => 'none',
              'condition'    => array(
                'webyx_section_enable'      => 'on',
                'webyx_section_wrapper_cnt' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_margin_dsk',
            array(
              'type'        => \Elementor\Controls_Manager::DIMENSIONS,
              'label'       => esc_html__( 'Wrapper margin values', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a margin (px, %, vw, vh).', $this->slug ),
              'render_type' => 'none',
              'size_units'  => array( 
                'px', 
                '%',
                'vw',
                'vh', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'webyx_section_enable'                        => 'on',
                'webyx_section_wrapper_cnt'                   => 'on',
                'webyx_section_wrapper_cnt_margin_enable_dsk' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_padding_enable_dsk',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wrapper padding', $this->slug ),
              'description'  => esc_html__( 'Enable wrapper padding for the ' . $el_root . '\'s content.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'render_type'  => 'none',
              'condition'    => array(
                'webyx_section_enable'      => 'on',
                'webyx_section_wrapper_cnt' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_padding_dsk',
            array(
              'type'        => \Elementor\Controls_Manager::DIMENSIONS,
              'label'       => esc_html__( 'Wrapper padding values', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a padding (px, %, vw, vh).', $this->slug ),
              'render_type' => 'none',
              'size_units' => array( 
                'px', 
                '%',
                'vw',
                'vh', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'webyx_section_enable'                         => 'on',
                'webyx_section_wrapper_cnt'                    => 'on',
                'webyx_section_wrapper_cnt_padding_enable_dsk' => 'on',
              ),
            )
          );
          $element->end_controls_tab();
          $element->start_controls_tab(
            'webyx_section_wrapper_cnt_mobile_tabs',
            array(
              'label' => esc_html__( 'Mobile', $this->slug ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_margin_enable_mob',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wrapper margin', $this->slug ),
              'description'  => esc_html__( 'Enable wrapper margin for the ' . $el_root . '\'s content.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'render_type'  => 'none',
              'return_value' => 'on',
              'default'      => '',
              'condition'    => array(
                'webyx_section_enable'      => 'on',
                'webyx_section_wrapper_cnt' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_margin_mob',
            array(
              'type'        => \Elementor\Controls_Manager::DIMENSIONS,
              'label'       => esc_html__( 'Wrapper margin values', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a margin (px, %, vw, vh).', $this->slug ),
              'render_type' => 'none',
              'size_units'  => array( 
                'px', 
                '%',
                'vw',
                'vh', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'webyx_section_enable'                        => 'on',
                'webyx_section_wrapper_cnt'                   => 'on',
                'webyx_section_wrapper_cnt_margin_enable_mob' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_padding_enable_mob',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Wrapper padding', $this->slug ),
              'description'  => esc_html__( 'Enable wrapper padding for the ' . $el_root . '\'s content.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'render_type'  => 'none',
              'condition'    => array(
                'webyx_section_enable'      => 'on',
                'webyx_section_wrapper_cnt' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_wrapper_cnt_padding_mob',
            array(
              'type'        => \Elementor\Controls_Manager::DIMENSIONS,
              'label'       => esc_html__( 'Wrapper padding values', $this->slug ),
              'description' => esc_html__( 'Insert a value to apply a padding (px, %, vw, vh).', $this->slug ),
              'render_type' => 'none',
              'size_units' => array( 
                'px', 
                '%',
                'vw',
                'vh', 
              ),
              'default'    => array(
                'top'      => 0,
                'right'    => 0,
                'bottom'   => 0,
                'left'     => 0,
                'unit'     => 'px',
                'isLinked' => '',
              ),
              'condition' => array(
                'webyx_section_enable'                         => 'on',
                'webyx_section_wrapper_cnt'                    => 'on',
                'webyx_section_wrapper_cnt_padding_enable_mob' => 'on',
              ),
            )
          );
          $element->end_controls_tab();
          $element->end_controls_tabs();
          $element->add_control(
            'webyx_fe_pro_background_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Background PRO',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_background',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#switchable-foregrounds-background" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_section_background',
            array(
              'type'         => \Elementor\Controls_Manager::SWITCHER,
              'label'        => esc_html__( 'Background', $this->slug ),
              'description'  => esc_html__( 'Enable ' . $el_root . ' background.', $this->slug ),
              'label_on'     => esc_html__( 'on',  $this->slug ),
              'label_off'    => esc_html__( 'off', $this->slug ),
              'return_value' => 'on',
              'default'      => '',
              'separator'    => 'before',
              'condition'    => array(
                'webyx_section_enable' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_background_colour',
            array(
              'type'        => \Elementor\Controls_Manager::COLOR,
              'label'       => esc_html__( 'Background colour', $this->slug ),
              'description' => esc_html__( 'Choose ' . $el_root . ' background colour.', $this->slug ),
              'default'     => '#ffffff',
              'condition'   => array(
                'webyx_section_enable'     => 'on',
                'webyx_section_background' => 'on',
              ),
              'selectors' => array(
                '{{WRAPPER}} .webyx-background-overlay-bkg-color' => '--section-color-dsk: {{VALUE}};',
              ),
            )
          );
          $element->add_control(
            'webyx_section_background_image', 
            array(
              'type' => \Elementor\Controls_Manager::MEDIA,
              'label'=> esc_html__( 'Choose image', $this->slug ),
              'description' => esc_html__( 'Choose ' . $el_root . ' background image.', $this->slug ),
              'dynamic' => array(
                'active'     => true,
                'categories' => array(
                  \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY,
                ),
              ),
              'default' => array(
                'url' => '',
              ),
              'media_type' => 'image',
              'condition'  => array(
                'webyx_section_enable'     => 'on',
                'webyx_section_background' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_background_image_size',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Image size', $this->slug ),
              'description' => esc_html__( 'Select image size.', $this->slug ),
              'default'     => 'cover',
              'options'     => array(
                'auto'    => esc_html__( 'auto', $this->slug ),
                'cover'   => esc_html__( 'cover', $this->slug ),
                'contain' => esc_html__( 'contain', $this->slug ),
              ),
              'condition' => array(
                'webyx_section_enable'     => 'on',
                'webyx_section_background' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_background_image_position',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Image position', $this->slug ),
              'description' => esc_html__( 'Select image position.', $this->slug ),
              'default'     => 'center center',
              'options'     => array(
                'left top'      => esc_html__( 'left top',      $this->slug ),
                'left center'   => esc_html__( 'left center',   $this->slug ),
                'left bottom'   => esc_html__( 'left bottom',   $this->slug ),
                'right top'     => esc_html__( 'right top',     $this->slug ),
                'right center'  => esc_html__( 'right center',  $this->slug ),
                'right bottom'  => esc_html__( 'right bottom',  $this->slug ),
                'center top'    => esc_html__( 'center top',    $this->slug ),
                'center center' => esc_html__( 'center center', $this->slug ),
                'center bottom' => esc_html__( 'center bottom', $this->slug ),
              ),
              'condition' => array(
                'webyx_section_enable'     => 'on',
                'webyx_section_background' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_background_image_repeat',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Image repeat', $this->slug ),
              'description' => esc_html__( 'Select image repeat.', $this->slug ),
              'default'     => 'no-repeat',
              'options'     => array(
                'repeat'    => esc_html__( 'repeat',    $this->slug ),
                'no-repeat' => esc_html__( 'no-repeat', $this->slug ),
              ),
              'condition' => array(
                'webyx_section_enable'     => 'on',
                'webyx_section_background' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_section_background_image_attachment',
            array(
              'type'        => \Elementor\Controls_Manager::SELECT,
              'label'       => esc_html__( 'Image attachment', $this->slug ),
              'description' => esc_html__( 'Select image attachment.', $this->slug ),
              'default'     => 'scroll',
              'options'     => array(
                'scroll' => esc_html__( 'scroll', $this->slug ),
                'fixed'  => esc_html__( 'fixed',  $this->slug ),
              ),
              'condition' => array(
                'webyx_section_enable'     => 'on',
                'webyx_section_background' => 'on',
              ),
            )
          );
          $element->add_control(
            'webyx_fe_pro_name_preview_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Section name preview',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_name_preview',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#upgraded-and-engaging-user-editor" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_fe_pro_container_icon_preview_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Container icon preview',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_container_icon_preview',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#upgraded-and-engaging-user-editor" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->add_control(
            'webyx_fe_pro_background_preview_link',
            array(
              'type'        => \Elementor\Controls_Manager::BUTTON,
              'text'        => esc_html__( 'Pro', $this->slug ),
              'label'       => 'Container background preview',
              'button_type' => 'default',
              'event'       => 'webyxProLink',
              'classes'     => 'webyx-pro-toggle-link',
              'separator'   => 'before',
            )
          );
          $element->add_control(
            'webyx_fe_pro_background_preview',
            array(
              'type'            => \Elementor\Controls_Manager::RAW_HTML,
              'raw'             => esc_html__( 'This is a PRO only feature. For more information go to ', $this->slug ) . '<a class="webyx-fe-pro-link" href="https://webyx.it/wfe-guide#upgraded-and-engaging-user-editor" target="_blank">Webyx website.</a>',
              'content_classes' => 'elementor-control-field-description',
              'classes'         => 'webyx-pro-toggle-link-description',
            )
          );
          $element->end_controls_section();
        }
      }
      public function webyx_fe_frontend_sections_content () {
        $is_container_active = $this->webyx_fe_is_container_active();
        add_action( 
          $is_container_active ? 'elementor/frontend/container/before_render' : 'elementor/frontend/section/before_render', 
          array(
            $this, 
            'webyx_fe_frontend_section_before_render'
          ) 
        );
        add_action( 
          $is_container_active ? 'elementor/frontend/container/after_render' : 'elementor/frontend/section/after_render',   
          array( 
            $this, 
            'webyx_fe_frontend_section_after_render' 
          )
        );
        add_filter( 
          'elementor/frontend/the_content', 
          array(
            $this, 
            'webyx_fe_frontend_sections_container'
          ) 
        );
        add_filter( 
          $is_container_active ? 'elementor/frontend/container/should_render' : 'elementor/frontend/section/should_render', 
          array(
            $this, 
            'webyx_fe_frontend_sections_should_render'
          ),
          10, 2
        );
      }
      public function webyx_fe_frontend_sections_should_render ( $bool, $element ) {
        global $post;
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        $s = $element->get_settings_for_display();
        $is_container_active = $this->webyx_fe_is_container_active();
        $webyx_section_enable = isset( $s[ 'webyx_section_enable' ] ) && in_array( $s[ 'webyx_section_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_enable' ] : '';
        $webyx_section_hide_in_frontend = isset( $s[ 'webyx_section_hide_in_frontend' ] ) && in_array( $s[ 'webyx_section_hide_in_frontend' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_hide_in_frontend' ] : '';
        if ( $is_container_active ) {
          $is_inner = isset( $s[ 'webyx_section_is_inner' ] );
          if ( $webyx_fe_is_enable ) {
            if ( 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend ) { 
              $bool = true;
            } else {
              if ( $is_inner && '' === $webyx_section_hide_in_frontend ) {
                $bool = true;
              } else {
                $bool = false;
              }
            }
          }
        } else {
          $is_inner = $element->get_raw_data()[ 'isInner' ];
          if ( $webyx_fe_is_enable && ! $is_inner ) {
            if ( 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend ) {
              $bool = true;
            } else {
              $bool = false;
            }
          } 
        }
        return $bool;
      }
      public function webyx_fe_frontend_sections_container ( $content ) {
        global $post;
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        if ( $webyx_fe_is_enable && $this->webyx_fe_chk_intgr( $content ) ) {
          $wvscrlb_validated = ( isset( $ps[ 'wvscrlbar' ] ) && in_array( $ps[ 'wvscrlbar' ], array( 'on', '' ), true ) && 'on' === $ps[ 'wvscrlbar' ] ) ? ' webyx-hide-scrollbar' : '';
          $webyx_fe_sp_screen_validated = $this->webyx_fe_get_splash_screen_validated( $ps );
          $webyx_fe_settings_validated = $this->webyx_fe_get_settings_validated( $ps );
          $webyx_fe_css_validated = $this->webyx_fe_print_css_validated( $ps );
          return '<div class="webyx-webyx">' . $webyx_fe_sp_screen_validated . '<div class="webyx-webyx-wrp' . sanitize_html_class( $wvscrlb_validated ) . '">' . $content . '</div></div>' . $webyx_fe_settings_validated .  $webyx_fe_css_validated;
        }
        return $content;
      }
      public function webyx_fe_sanitize_html_classes ( $classes, $return_format = 'input' ) {
        if ( 'input' === $return_format ) {
          $return_format = is_array( $classes ) ? 'array' : 'string';
        }
        $classes = is_array( $classes ) ? $classes : explode( ' ', $classes );
        $sanitized_classes = array_map( 'sanitize_html_class', $classes );
        if ( 'array' === $return_format ) {
          return $sanitized_classes;
        } else {
          return implode( ' ', $sanitized_classes );
        }
      }
      public function webyx_fe_frontend_section_before_render ( \Elementor\Element_Base $element ) {
        global $post;
        $s = $element->get_settings_for_display();
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $element_id = $element->get_id();
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        $webyx_section_is_inner = isset( $s[ 'webyx_section_is_inner' ] ) ;
        $is_container_active = $this->webyx_fe_is_container_active();
        $is_inner = $is_container_active ? $webyx_section_is_inner : $element->get_raw_data()[ 'isInner' ];
        $cn_bkg = 'webyx-section-' . $element_id;
        $cn_bkg_video = 'webyx-video-section-' . $element_id;
        $cn_wrp_cnt = 'webyx-wrapper-cnt-section-' . $element_id;
        $webyx_section_enable = isset( $s[ 'webyx_section_enable' ] ) && in_array( $s[ 'webyx_section_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_enable' ] : '';
        $webyx_section_hide_in_frontend = isset( $s[ 'webyx_section_hide_in_frontend' ] ) && in_array( $s[ 'webyx_section_hide_in_frontend' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_hide_in_frontend' ] : '';
        if ( $webyx_fe_is_enable && 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend && ! $is_inner ) {
          $ws_name = isset( $s[ 'webyx_section_name' ] ) && '' !== $s[ 'webyx_section_name' ] ? $s[ 'webyx_section_name' ] : 'Section';
          $ws_type = isset( $s[ 'webyx_section_type' ] ) && in_array( $s[ 'webyx_section_type' ], array( 'front', 'side' ), true ) ? $s[ 'webyx_section_type' ] : 'front';
          $ws_tag_name = isset( $s[ 'webyx_section_tag_name' ] ) && in_array( $s[ 'webyx_section_tag_name' ], $this->tag_name, true ) ? $s[ 'webyx_section_tag_name' ] : 'div';
          $ws_continuous = isset( $s[ 'webyx_section_continuous_carousel' ] ) && in_array( $s[ 'webyx_section_continuous_carousel' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_continuous_carousel' ] : '';
          $ws_scrollable = isset( $s[ 'webyx_section_scrollable' ] ) && in_array( $s[ 'webyx_section_scrollable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_scrollable' ] : '';
          $ws_cont_pos_en = isset( $s[ 'webyx_section_cont_pos_enable' ] ) && in_array( $s[ 'webyx_section_cont_pos_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_cont_pos_enable' ] : '';
          $ws_cont_pos = isset( $s[ 'webyx_section_cont_pos' ] ) && in_array( $s[ 'webyx_section_cont_pos' ], array( 'top', 'middle', 'bottom' ), true ) ? $s[ 'webyx_section_cont_pos' ] : 'middle';
          $ws_wrp_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
          $ws_wrp_cnt_classes = isset( $s[ 'webyx_section_wrapper_cnt_classes' ] ) ? $s[ 'webyx_section_wrapper_cnt_classes' ] : '';
          $cn_type_side_page = 'front' === $ws_type ? 'webyx-main-page' : '';
          $data_continuous_carousel = 'on' === $ws_continuous ? 'data-loop' : '';
          $data_scrollable = 'on' === $ws_scrollable ? 'data-scroll' : '';
          $cn_ovw_page = 'on' === $ws_scrollable ? 'webyx-scrollbar webyx-ovw-scroll' : 'webyx-ovw-hidden';
          $cn_container = $is_container_active ? 'webyx-flex' : '';
          if ( $ws_type === 'front' ) {
            if ( $this->is_open_section ) {
              echo '</div>';
            }
            echo '<div class="webyx-stripe" data-stripe="' . esc_attr( $ws_name ) . '" ' . esc_attr( $data_continuous_carousel ) . ' data-webyx="webyx-fe-fl">';
            $this->is_open_section = TRUE;
          }
          echo '<' . tag_escape( $ws_tag_name ) . ' ' . esc_attr( $data_scrollable ) . ' class="' . sanitize_html_class( $cn_type_side_page ) . ' ' . 'webyx-side-page' . ' ' . $this->webyx_fe_sanitize_html_classes( $cn_ovw_page ) . ' ' . sanitize_html_class( $cn_bkg ) . ' ' . sanitize_html_class( $cn_container ) . '" data-side-page="' . esc_attr( $ws_name ) . '">';
          echo $this->webyx_fe_get_section_style_validated( $s, $ps, $cn_bkg, $cn_wrp_cnt );
          if ( 'on' === $ws_cont_pos_en ) {
            echo '<div class="webyx-table"><div class="' . sanitize_html_class( 'webyx-table-cell-'. $ws_cont_pos ) . '">';
          }
          if ( 'on' === $ws_wrp_cnt ) {
            $cns_section_wrapper = strlen( $ws_wrp_cnt_classes ) ? "webyx-wrapper-slide-content $cn_wrp_cnt $ws_wrp_cnt_classes" : "webyx-wrapper-slide-content $cn_wrp_cnt";
            echo '<div class="' . $this->webyx_fe_sanitize_html_classes( $cns_section_wrapper ) . ' ' . sanitize_html_class( $cn_container ) . '">'; 
          }
        }
      }
      public function webyx_fe_frontend_section_after_render ( \Elementor\Element_Base $element ) {
        global $post;
        $s = $element->get_settings_for_display();
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        $webyx_section_is_inner = isset( $s[ 'webyx_section_is_inner' ] );
        $is_container_active = $this->webyx_fe_is_container_active();
        $is_inner = $is_container_active ? $webyx_section_is_inner : $element->get_raw_data()[ 'isInner' ];
        $webyx_section_enable = isset( $s[ 'webyx_section_enable' ] ) && in_array( $s[ 'webyx_section_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_enable' ] : '';
        $webyx_section_hide_in_frontend = isset( $s[ 'webyx_section_hide_in_frontend' ] ) && in_array( $s[ 'webyx_section_hide_in_frontend' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_hide_in_frontend' ] : '';
        if ( $webyx_fe_is_enable && 'on' === $webyx_section_enable && '' === $webyx_section_hide_in_frontend && ! $is_inner ) {
          $webyx_section_section_tag_name = isset( $s[ 'webyx_section_tag_name' ] ) && in_array( $s[ 'webyx_section_tag_name' ], $this->tag_name, true ) ? $s[ 'webyx_section_tag_name' ] : 'div';
          $webyx_section_cont_pos_enable = isset( $s[ 'webyx_section_cont_pos_enable' ] ) && in_array( $s[ 'webyx_section_cont_pos_enable' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_cont_pos_enable' ] : '';
          $webyx_section_wrapper_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
          if ( 'on' === $webyx_section_wrapper_cnt ) {
            echo '</div>'; 
          }
          if ( 'on' === $webyx_section_cont_pos_enable ) {
            echo '</div></div>';
          }
          echo '</' . tag_escape( $webyx_section_section_tag_name ) . '>';
        }
      }
      public function webyx_fe_print_css_validated ( $ps ) {
        $css = '';
        $css .= $this->webyx_fe_print_cs_css_validated( $ps ); 
        $css .= $this->webyx_fe_print_na_css_validated( $ps ); 
        $css .= $this->webyx_fe_print_nb_css_validated( $ps ); 
        $css .= $this->webyx_fe_print_imwh_css_validated( $ps ); 
        $css .= $this->webyx_fe_print_fs_css_validated( $ps ); 
        $css .= $this->webyx_fe_print_scrlb_css_validated( $ps ); 
        $css .= $this->webyx_fe_get_custom_css_validated( $ps ); 
        return $css;
      }
      public function webyx_fe_print_cs_css_validated ( $ps ) {
        $hmsd = isset( $ps[ 'hmsd' ] ) && filter_var( $ps[ 'hmsd' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'hmsd' ] : 900;
        $hmcd = isset( $ps[ 'hmcd' ] ) && in_array( $ps[ 'hmcd' ], $this->cubic_bezier_animation, true ) ? $ps[ 'hmcd' ] : 'cubic-bezier(0.64,0,0.34,1)';
        $vmsd = isset( $ps[ 'vmsd' ] ) && filter_var( $ps[ 'vmsd' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'vmsd' ] : 900;
        $vmcd = isset( $ps[ 'vmcd' ] ) && in_array( $ps[ 'vmcd' ], $this->cubic_bezier_animation, true ) ? $ps[ 'vmcd' ] : 'cubic-bezier(0.64,0,0.34,1)';
        $hmsm = isset( $ps[ 'hmsm' ] ) && filter_var( $ps[ 'hmsm' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'hmsm' ] : 300;
        $hmcm = isset( $ps[ 'hmcm' ] ) && in_array( $ps[ 'hmcm' ], $this->cubic_bezier_animation, true ) ? $ps[ 'hmcm' ] : 'cubic-bezier(0.64,0,0.34,1)';
        $vmsm = isset( $ps[ 'vmsm' ] ) && filter_var( $ps[ 'vmsm' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 300, 'max_range' => 1200 ) ) ) ? $ps[ 'vmsm' ] : 300;
        $vmcm = isset( $ps[ 'vmcm' ] ) && in_array( $ps[ 'vmcm' ], $this->cubic_bezier_animation, true ) ? $ps[ 'vmcm' ] : 'cubic-bezier(0.64,0,0.34,1)';
        $body = '';
        $body .= '.webyx-slide-viewport-dsk{transition:left ' . esc_attr( $hmsd ) . 'ms ' . esc_attr( $hmcd ) . ',top ' . esc_attr( $vmsd ) . 'ms ' . esc_attr( $vmcd ) . '}.webyx-slide-viewport-mobile{transition:left ' . esc_attr( $hmsm ) . 'ms ' . esc_attr( $hmcm ) . ',top ' . esc_attr( $vmsm ) . 'ms ' . esc_attr( $vmcm ) . '}';
        return '<style>' . $body . '</style>';
      }
      public function webyx_fe_print_na_css_validated ( $ps ) {
        $av = isset( $ps[ 'av' ] ) && in_array( $ps[ 'av' ], array( 'on', '' ), true ) ? $ps[ 'av' ] : '';
        $body = '';
        if ( 'on' === $av ) {
          $mvnast = isset( $ps[ 'mvnast' ] ) && in_array( $ps[ 'mvnast' ], array( 'small', 'medium', 'large' ), true )  ? $ps[ 'mvnast' ] : 'medium'; 
          $mvnatt = isset( $ps[ 'mvnatt' ] ) && in_array( $ps[ 'mvnatt' ], array( 'thin', 'standard', 'thick' ), true ) ? $ps[ 'mvnatt' ] : 'standard';
          $mvnact = isset( $ps[ 'mvnact' ] ) && in_array( $ps[ 'mvnact' ], array( 'on', '' ), true ) ? $ps[ 'mvnact' ] : '';
          $mvnaoc = isset( $ps[ 'mvnaoc' ] ) && in_array( $ps[ 'mvnaoc' ], array( 'on', '' ), true ) ? $ps[ 'mvnaoc' ] : '';  
          $mvnaot = isset( $ps[ 'mvnaot' ] ) && filter_var( $ps[ 'mvnaot' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaot' ] : 0;                
          $mvnaor = isset( $ps[ 'mvnaor' ] ) && filter_var( $ps[ 'mvnaor' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaor' ] : 0;                 
          $mvnaob = isset( $ps[ 'mvnaob' ] ) && filter_var( $ps[ 'mvnaob' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaob' ] : 0;                 
          $mvnaol = isset( $ps[ 'mvnaol' ] ) && filter_var( $ps[ 'mvnaol' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'mvnaol' ] : 0;                 
          $mvnaa = isset( $ps[ 'mvnaa' ] ) && in_array( $ps[ 'mvnaa' ], array( 'on', '' ), true ) ? $ps[ 'mvnaa'  ] : '';                
          $mvnaad = isset( $ps[ 'mvnaad' ] ) && in_array( $ps[ 'mvnaad' ], array( 'small', 'medium', 'large' ), true ) ? $ps[ 'mvnaad' ] : 'medium';          
          $mvnaac = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvnaac', '#00000066' );
          $mvnac = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvnac', '#000000' );
          $mvnacl = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvnacl', '#00000066' );
          switch ( $mvnast . '-' . $mvnatt ) {
            case 'small-thin': 
              $body .= '.webyx-arrow-viewport-icon{width:10px;height:10px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-6px}.webyx-arrow-viewport-icon-borders{border-top-width:2px;border-right-width:2px}';
              break; 
            case 'medium-thin':
              $body .= '.webyx-arrow-viewport-icon{width:20px;height:20px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-11px}.webyx-arrow-viewport-icon-borders{border-top-width:2px;border-right-width:2px}';
              break; 
            case 'large-thin':
              $body .= '.webyx-arrow-viewport-icon{width:30px;height:30px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-16px}.webyx-arrow-viewport-icon-borders{border-top-width:2px;border-right-width:2px}';
              break; 
            case 'small-standard':
              $body .= '.webyx-arrow-viewport-icon{width:10px;height:10px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-6px}.webyx-arrow-viewport-icon-borders{border-top-width:4px;border-right-width:4px}';
              break; 
            case 'medium-standard':
              $body .= '.webyx-arrow-viewport-icon{width:20px;height:20px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-12px}.webyx-arrow-viewport-icon-borders{border-top-width:4px;border-right-width:4px}'; 
              break; 
            case 'large-standard':
              $body .= '.webyx-arrow-viewport-icon{width:30px;height:30px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-16px}.webyx-arrow-viewport-icon-borders{border-top-width:4px;border-right-width:4px}';
              break; 
            case 'small-thick':
              $body .= '.webyx-arrow-viewport-icon{width:10px;height:10px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-9px}.webyx-arrow-viewport-icon-borders{border-top-width:8px;border-right-width:8px}';
              break; 
            case 'medium-thick':
              $body .= '.webyx-arrow-viewport-icon{width:20px;height:20px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-14px}.webyx-arrow-viewport-icon-borders{border-top-width:8px;border-right-width:8px}';
              break; 
            case 'large-thick':
              $body .= '.webyx-arrow-viewport-icon{width:30px;height:30px}.webyx-arrow-viewport-icon-bottom,.webyx-arrow-viewport-icon-top,.webyx-arrow-viewport-icon-left,.webyx-arrow-viewport-icon-right{margin-left:-19px}.webyx-arrow-viewport-icon-borders{border-top-width:8px;border-right-width:8px}';
              break;
          } 
          if ( 'on' === $mvnact ) {
            $body .= '.webyx-arrow-viewport-icon-borders{border-radius:20%}';
          }
          if ( 'on' === $mvnaoc ) {
            $body .= '.webyx-arrow-viewport-top{margin-top:' . esc_attr( $mvnaot ) . 'px}.webyx-arrow-viewport-right{margin-right:' . esc_attr( $mvnaor ) . 'px}.webyx-arrow-viewport-bottom{margin-bottom:' . esc_attr( $mvnaob ) . 'px}.webyx-arrow-viewport-left{margin-left:' . esc_attr( $mvnaol ) . 'px}';
          }
          switch ( $mvnaad ) {
            case 'small': 
              $body .= '.webyx-arrow-viewport{width:80px;height:50px}.webyx-arrow-viewport-top{margin-left:-40px}.webyx-arrow-viewport-right{right:-15px;margin-top:-25px}.webyx-arrow-viewport-bottom{margin-left:-40px}.webyx-arrow-viewport-left{left:-15px;margin-top:-25px}';
              break; 
            case 'medium':
              $body .= '.webyx-arrow-viewport{width:150px;height:70px}.webyx-arrow-viewport-top{margin-left:-75px}.webyx-arrow-viewport-right{right:-40px;margin-top:-35px}.webyx-arrow-viewport-bottom{margin-left:-75px}.webyx-arrow-viewport-left{left:-40px;margin-top:-35px}';
              break; 
            case 'large':
              $body .= '.webyx-arrow-viewport{width:300px;height:90px}.webyx-arrow-viewport-top{margin-left:-150px}.webyx-arrow-viewport-right{right:-105px;margin-top:-45px}.webyx-arrow-viewport-bottom{margin-left:-150px}.webyx-arrow-viewport-left{left:-105px;margin-top:-45px}';
              break;
          }
          if ( 'on' === $mvnaa ) {
            $body .= '.webyx-arrow-viewport-bkg-area-colour{background-color:' . esc_attr( $mvnaac ) . '}';
          }
          $body .= '.webyx-arrow-viewport-icon-borders{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}.webyx-arrow-viewport-icon-borders-fixed{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}.webyx-arrow-viewport-icon-borders-visible{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}';
          $body .= '.webyx-arrow-viewport-icon-borders-fixed{border-top-color:' . esc_attr( $mvnacl ) . ';border-right-color:' . esc_attr( $mvnacl ) . '}.webyx-arrow-viewport-icon-borders-visible{border-top-color:' . esc_attr( $mvnac ) . ';border-right-color:' . esc_attr( $mvnac ) . '}';
          return '<style>' . $body . '</style>';
        } else {
          return $body;
        }
      }
      public function webyx_fe_print_nb_css_validated ( $ps ) {
        $body = '';
        $mvndbst = isset( $ps[ 'mvndbst' ] ) && in_array( $ps[ 'mvndbst' ], array( 'scale', 'stroke', 'small_stroke' ), true ) ? $ps[ 'mvndbst' ] : 'scale'; 
        $mvndc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvndc', '#000000' );       
        $mvndcl = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvndcl', '#00000066' );              
        $dbkgace = isset( $ps[ 'dbkgace' ] ) && in_array( $ps[ 'dbkgace' ], array( 'on', '' ), true ) ? $ps[ 'dbkgace' ] : '';                    
        $dbkgac = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'dbkgac', '#00000066' );     
        $mvndttace = isset( $ps[ 'mvndttace' ] ) && in_array( $ps[ 'mvndttace' ], array( 'on', '' ), true ) ? $ps[ 'mvndttace' ] : '';                    
        $mvndttac = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvndttac', '#ffffff' );
        $dtvoff = isset( $ps[ 'dtvoff' ] ) && in_array( $ps[ 'dtvoff' ], array( 'on', '' ), true ) ? $ps[ 'dtvoff' ] : '';                    
        $dvp = isset( $ps[ 'dvp' ] ) && in_array( $ps[ 'dvp' ], array( 'left', 'right' ), true ) ? $ps[ 'dvp' ] : 'right';               
        $dtvoffdsk = isset( $ps[ 'dtvoffdsk' ] ) && filter_var( $ps[ 'dtvoffdsk' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dtvoffdsk' ] : 0;                      
        $dtvoffmob = isset( $ps[ 'dtvoffmob' ] ) && filter_var( $ps[ 'dtvoffmob' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dtvoffmob' ] : 0;                      
        $dthoff = isset( $ps[ 'dthoff' ] ) && in_array( $ps[ 'dthoff' ], array( 'on', '' ), true ) ? $ps[ 'dthoff' ] : '';                    
        $dhp = isset( $ps[ 'dhp' ] ) && in_array( $ps[ 'dhp' ], array( 'top', 'bottom' ), true ) ? $ps[ 'dhp' ] : 'bottom';              
        $dthoffdsk = isset( $ps[ 'dthoffdsk' ] ) && filter_var( $ps[ 'dthoffdsk' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dthoffdsk' ] : 0;                       
        $dthoffmob = isset( $ps[ 'dthoffmob' ] ) && filter_var( $ps[ 'dthoffmob' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'dthoffmob' ] : 0;                       
        $mvndttc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvndttc', '#000000' );               
        $mvndttcl = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'mvndttcl', '#00000066' );           
        switch ( $mvndbst ) { 
          case 'scale':
            $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{top:8px;left:8px;background-color:' . esc_attr( $mvndcl ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:' . esc_attr( $mvndc ) . '}';
            break;
          case 'stroke':
            $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:' . esc_attr( $mvndc ) . ';border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{background-color:' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;top:4px;left:4px;width:12px;height:12px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;background-color:' . esc_attr( $mvndcl ) . ';cursor:pointer;overflow:hidden;-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:rgba(255,255,255,0);border-radius:50%;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease,background-color .3s ease;transition:box-shadow .3s ease,background-color .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%}';
            break; 
          case 'small_stroke':
            $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-right{right:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk-left{left:25px}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-vt-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-vt-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk.webyx-dot-vt-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk:hover{background-color:transparent}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk .webyx-dot-current-vt-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-vt-dsk-left{left:14px}.webyx-nav-vt-dsk-right{right:14px}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-vt-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob.webyx-dot-vt-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob:hover{background-color:transparent}.webyx-nav-vt-mob .webyx-dots-wrapper-vt-mob .webyx-dot-wrapper-vt-mob .webyx-dot-current-vt-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-top{top:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk-bottom{bottom:25px}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-hz-dsk{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk{position:absolute;width:12px;height:12px;top:4px;left:4px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after{content:"";position:absolute;bottom:0;height:100%;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-hz-dsk::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk.webyx-dot-hz-dsk::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk:hover{background-color:transparent}.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk .webyx-dot-current-hz-dsk::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob{position:absolute;width:8px;height:8px;top:6px;left:6px;outline:0;border-radius:50%;text-indent:-999em;cursor:pointer;overflow:hidden;background-color:' . esc_attr( $mvndcl ) . ';box-shadow:0 0 0 2px rgba(255,255,255,0);-webkit-transition:box-shadow .3s ease;transition:box-shadow .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;border-radius:50%;background-color:' . esc_attr( $mvndcl ) . ';-webkit-transition:background-color .3s ease,-webkit-transform .3s ease;transition:background-color .3s ease,transform .3s ease}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-hz-mob::after:hover{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob{background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1)}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob.webyx-dot-hz-mob::after{background-color:' . esc_attr( $mvndc ) . '}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob:hover{background-color:transparent}.webyx-nav-hz-mob .webyx-dots-wrapper-hz-mob .webyx-dot-wrapper-hz-mob .webyx-dot-current-hz-mob::after{content:"";position:absolute;bottom:0;height:0;left:0;width:100%;background-color:transparent;box-shadow:0 0 0 2px ' . esc_attr( $mvndc ) . ';border-radius:50%;height:100%;-webkit-transform:scale(.3);transform:scale(.3)}';
            break; 
        }
        if ( 'on' === $dbkgace ) {
          $body .= '.webyx-dots-wrapper-bkg-color{background-color:' . esc_attr( $dbkgac ) . '}';
        }
        if ( 'on' === $mvndttace ) {
          $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-tt-vt-dsk, .webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-tt-hz-dsk{background-color:' .esc_attr( $mvndttac ) . '}';
        }
        if ( 'on' === $dtvoff ) { 
          $body .= '.webyx-nav-vt-dsk-' . esc_attr( $dvp ) . '{' . esc_attr( $dvp ) . ':' . esc_attr( $dtvoffdsk ) . 'px}.webyx-nav-vt-mob-' . esc_attr( $dvp ) . '{' . esc_attr( $dvp ) . ':' . esc_attr( $dtvoffmob ) . 'px}';
        }
        if ( 'on' === $dthoff ) { 
          $body .= '.webyx-nav-hz-dsk-' .esc_attr( $dhp ) . '{' . esc_attr( $dhp) . ':' . esc_attr( $dthoffdsk ) . 'px}.webyx-nav-hz-mob-' . esc_attr( $dhp ) . '{' .  esc_attr( $dhp ) . ':'. esc_attr( $dthoffmob ) . '}px}';
        }
        $body .= '.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-current-vt-dsk,.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-current-vt-dsk+.webyx-dot-tt-vt-dsk, .webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-current-hz-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-current-hz-dsk+.webyx-dot-tt-hz-dsk,.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-current-vt-persistent-dsk, .webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-current-hz-persistent-dsk{color:' . esc_attr( $mvndttc ) . '}.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-current-vt-dsk,.webyx-nav-vt-dsk .webyx-dots-wrapper-vt-dsk .webyx-dot-wrapper-vt-dsk:hover .webyx-dot-tt-vt-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-current-hz-dsk,.webyx-nav-hz-dsk .webyx-dots-wrapper-hz-dsk .webyx-dot-wrapper-hz-dsk:hover .webyx-dot-tt-hz-dsk{color:' . esc_attr( $mvndttcl ) . '}';
        return '<style>' . $body . '</style>';
      }
      public function webyx_fe_print_imwh_css_validated ( $ps ) {
        $nvvw = isset( $ps[ 'nvvw' ] ) && in_array( $ps[ 'nvvw' ], array( 'on', '' ), true ) ? $ps[ 'nvvw' ] : '';
        $avvd = isset( $ps[ 'avvd' ] ) && in_array( $ps[ 'avvd' ], array( 'on', '' ), true ) ? $ps[ 'avvd' ] : '';
        $body = '';
        if ( 'on' === $nvvw && 'on' === $avvd ) {
          $msiwc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'msiwc', '#000000' );                  
          $msiwbce = isset( $ps[ 'msiwbce' ] ) && in_array( $ps[ 'msiwbce' ], array( 'on', '' ), true ) ? $ps[ 'msiwbce' ] : '';                                   
          $msiwbc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'msiwbc', '#ffffff' );                  
          if ( 'on' === $msiwbce ) {
            $body .= '.webyx-icon-scroll-wrapper .webyx-icon-scroll-mouse{background-color:' . esc_attr( $msiwbc ) . '}';
          }
          $body .= '.webyx-icon-scroll-wrapper .webyx-icon-scroll-mouse{border-color:' . esc_attr( $msiwc ) . '}.webyx-icon-scroll-wrapper .webyx-icon-scroll-wheel{background:' . esc_attr( $msiwc ) . '}';
          return '<style>' . $body . '</style>';
        } else {
          return $body;
        }
      }
      public function webyx_fe_print_fs_css_validated ( $ps ) {
        $fsb = isset( $ps[ 'fsb' ] ) && in_array( $ps[ 'fsb' ], array( 'on', '' ), true ) ? $ps[ 'fsb' ] : ''; 
        $body = '';
        if ( 'on' === $fsb ) {
          $fsc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'fsc', '#000000' ); 
          $fsdt = isset( $ps[ 'fsdt' ] ) && in_array( $ps[ 'fsdt' ], array( '2px', '4px', '6px' ), true ) ? $ps[ 'fsdt' ] : '4px';                 
          $fsboff = isset( $ps[ 'fsboff' ] ) && in_array( $ps[ 'fsboff' ], array( 'on', '' ), true ) ? $ps[ 'fsboff' ] : '';                    
          $fsbce = isset( $ps[ 'fsbce' ] ) && in_array( $ps[ 'fsbce' ], array( 'on', '' ), true ) ? $ps[ 'fsbce' ] : '';                    
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-outer{border-top-width:' . esc_attr( $fsdt ) . ';border-left-width:'. esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-inner{border-bottom-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-outer{border-top-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-inner{border-bottom-width:' . esc_attr( $fsdt ) . ';border-left-width:' . esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-outer{border-bottom-width:' . esc_attr( $fsdt ) . ';border-left-width:' . esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-inner{border-top-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-outer{border-bottom-width:' . esc_attr( $fsdt ) . ';border-right-width:' . esc_attr( $fsdt ) . '}';
          $body .= '.webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-inner{border-top-width:' . esc_attr( $fsdt ) . ';border-left-width:' . esc_attr( $fsdt ) . '}';
          $body .= ".webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-inner,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-left-outer,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-inner,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-bottom-right-outer,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-inner,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-left-outer,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-inner,
                    .webyx-full-screen-button-wrapper .webyx-full-screen-button-part-top-right-outer{border-color:" . esc_attr( $fsc ) . '}';
          if ( 'on' === $fsboff ) {
            $fsp = isset( $ps[ 'fsp' ] ) && in_array( $ps[ 'fsp' ], array( 'left', 'right' ), true ) ? $ps[ 'fsp' ] : 'right';
            $fsofft = isset( $ps[ 'fsofft' ] ) && filter_var( $ps[ 'fsofft' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'fsofft' ] : 0;     
            $fsoffl = isset( $ps[ 'fsoffl' ] ) && filter_var( $ps[ 'fsoffl' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'fsoffl' ] : 0;      
            $fsoffr = isset( $ps[ 'fsoffr' ] ) && filter_var( $ps[ 'fsoffr' ], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'max_range' => 5000 ) ) ) ? $ps[ 'fsoffr' ] : 0;      
            switch ( $fsp ) {
              case 'left':
                $body .= '.webyx-full-screen-button-wrapper.webyx-full-screen-button-wrapper-left{top:' . esc_attr( $fsofft ) . 'px;left:' . esc_attr( $fsoffl ) . 'px}';
                break;
              case 'right':
                $body .= '.webyx-full-screen-button-wrapper.webyx-full-screen-button-wrapper-right{top:' . esc_attr( $fsofft ) . 'px;right:' . esc_attr( $fsoffr ) . 'px}';
                break;
            }
          }
          if ( 'on' === $fsbce ) {
            $fsbc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'fsbc', '#ffffff00' );
            $body .= '.webyx-full-screen-button-wrapper-background-color{background-color:' . esc_attr( $fsbc ) . '}';
          }
          return '<style>' . $body . '</style>';
        } else {
          return $body;
        }
      }
      public function webyx_fe_print_scrlb_css_validated ( $ps ) {
        $scrlbd = isset( $ps[ 'scrlbd' ] ) && in_array( $ps[ 'scrlbd' ], array( 'on', '' ), true ) ? $ps[ 'scrlbd' ] : '';
        $body = '';
        if ( 'on' === $scrlbd ) {
          $body .= '.webyx-scrollbar::-webkit-scrollbar{display:none}.webyx-scrollbar{-ms-overflow-style:none;scrollbar-width:none}';
          return '<style>' . $body . '</style>';
        } else {
          return $body;
        }
      }
      private function webyx_fe_css_validate ( $css ) {
        if ( preg_match( '#</?\w+#', $css ) ) {
          return false;
        }
        return $css;
      }
      public function webyx_fe_get_custom_css_validated ( $ps ) {
        $ccss = isset( $ps[ 'ccss' ] ) && in_array( $ps[ 'ccss' ], array( 'on', '' ), true ) ? $ps[ 'ccss' ] : '';
        $ccssp = isset( $ps[ 'ccssp' ] ) && $this->webyx_fe_css_validate( $ps[ 'ccssp' ] ) ? $ps[ 'ccssp' ] : '';
        $body = '';
        if ( 'on' === $ccss ) {
          $body .= $ccssp;
          return '<style>' . $body . '</style>';
        } else {
          return $body;
        }
      }
      public function webyx_fe_get_splash_screen_validated ( $ps ) {
        $ils = isset( $ps[ 'ils' ] ) && in_array( $ps[ 'ils' ], array( 'on', '' ), true ) ? $ps[ 'ils' ] : '';
        $ilst = isset( $ps[ 'ilst' ] ) && in_array( $ps[ 'ilst' ], array( 'default', 'custom' ), true ) ? $ps[ 'ilst' ] : 'default'; 
        $ilsbc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'ilsbc', '#9933CC' ); 
        $ilssbc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'ilssbc', '#FFFFFF' );
        $ilscmt = isset( $ps[ 'ilscmt' ] ) && 'string' === gettype( $ps[ 'ilscmt' ] ) ? $ps[ 'ilscmt' ] : '';
        $ilscmtc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'ilscmtc', '#000000' );
        $ilscbc = $this->webyx_fe_get_global_elmnt_control_value( $ps, 'ilscbc', '#FFFFFF' );
        $ilscbiurl = isset( $ps[ 'ilscbiurl' ] ) ? $ps[ 'ilscbiurl' ] : array( 'url' => '' );
        if ( 'on' === $ils ) {
          switch ( $ilst ) {
            case 'default':
              return '<div class="webyx-splash" style="z-index:10000;background-color:' . esc_attr( $ilsbc ) . '"><div class="webyx-spinner" style="background-color:"' . esc_attr( $ilssbc ) . '"></div></div>';
            case 'custom':
              if ( isset( $ilscbiurl[ 'url' ] ) && '' !== $ilscbiurl[ 'url' ] ) {
                return '<div class="webyx-splash" style="z-index:10000;color:'. esc_attr( $ilscmtc ) . '">' . ( $ilscbiurl[ 'url' ] ? '<div class="webyx-custom-splash-bkg-img" style="background-image:url(' . esc_url( $ilscbiurl[ 'url' ] ) . ')"></div>' : '' ) . '<div class="webyx-custom-splash-txt webyx-animate-flicker">' . esc_html( $ilscmt ) . '</div></div>';
              }
              return '<div class="webyx-splash" style="z-index:10000;color:'. esc_attr( $ilscmtc ) . ';background-color:' . esc_attr( $ilscbc ) . '"><div class="webyx-custom-splash-txt webyx-animate-flicker">' . esc_html( $ilscmt ) . '</div></div>';
          }
        } else {
          return '<div class="webyx-splash" style="z-index:10000;background-color:#FFFFFF"></div>';
        }
      }
      public function webyx_fe_frontend_enqueue_assets () {
        add_action( 
          'elementor/frontend/after_enqueue_scripts', 
          array(
            $this, 
            'webyx_fe_frontend_enqueue_scripts' 
          )
        );
        add_action( 
          'elementor/frontend/after_enqueue_styles', 
          array( 
            $this, 
            'webyx_fe_frontend_enqueue_style' 
          ) 
        );
      }
      public function webyx_fe_frontend_enqueue_scripts () {
        global $post;
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $is_frontend_view = $this->webyx_fe_is_frontend_view();
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        $webyx_fe_menu = get_option( 'webyx_fe_menu', 'true' );
        if ( $webyx_fe_is_enable && $is_frontend_view ) {
          $fn = WEBYX_FE_ASSET_MIN ? 'assets/js/webyx.min.js' : 'assets/js/webyx.js';
          $path = plugins_url( 
            $fn, 
            __FILE__ 
          );
          wp_register_script( 
            'webyx-fe-script', 
            $path,
            array(),
            filemtime( 
              plugin_dir_path( __FILE__ ) . $fn
            )
          );
          wp_enqueue_script( 'webyx-fe-script' );
          if ( $webyx_fe_menu ) {
            $fn_wm = WEBYX_FE_ASSET_MIN ? 'assets/js/webyx-menu.min.js' : 'assets/js/webyx-menu.js';
            $path = plugins_url( 
              $fn_wm, 
              __FILE__ 
            );
            wp_register_script( 
              'webyx-fe-menu-script', 
              $path,
              array(),
              filemtime( 
                plugin_dir_path( __FILE__ ) . $fn_wm
              )
            );
            wp_enqueue_script( 'webyx-fe-menu-script' );
          }
        }
      }
      public function webyx_fe_frontend_enqueue_style () {
        global $post;
        $ps = get_post_meta( $post->ID, '_elementor_page_settings', true );
        $is_frontend_view = $this->webyx_fe_is_frontend_view();
        $webyx_fe_is_enable = $this->webyx_fe_is_enable( $ps );
        if ( $webyx_fe_is_enable && $is_frontend_view ) {
          $fn = WEBYX_FE_ASSET_MIN ? 'assets/css/webyx.min.css' : 'assets/css/webyx.css';
          $path = plugins_url( 
            $fn, 
            __FILE__ 
          );
          wp_register_style( 
            'webyx-fe-style', 
            $path,
            array(),
            filemtime( 
              plugin_dir_path( __FILE__ ) . $fn 
            )
          );
          wp_enqueue_style( 'webyx-fe-style' );
        }
      }
      public function webyx_fe_get_settings_validated ( $ps ) {
        $cnf_s = array(
          'av' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'avf' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'avvd' => array( 
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'nvvw' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'iwhf' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dv' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dvp' => array(
            'value'  => 'right',
            'values' => array( 
              'left'  => 'left', 
              'right' => 'right', 
            ),
            'data_type' => 'string'
          ),
          'dtv' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dtvcp' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dh' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dhp' => array(
            'value'  => 'bottom',
            'values' => array( 
              'top'    => 'top', 
              'bottom' => 'bottom', 
            ),
            'data_type' => 'string'
          ),
          'dhs' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dth' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'dthcp' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'fsb' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'fsp' => array(
            'value'  => 'right',
            'values' => array( 
              'left'  => 'left', 
              'right' => 'right', 
            ),
            'data_type' => 'string'
          ),
          'kn' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'fdskm' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ),
          'ilsctmen' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ), 
          'ilscsi' => array(
            'value'     => FALSE,
            'values'    => array( TRUE, FALSE ),
            'data_type' => 'boolean',
          ), 
          'ilsctm' => array(
            'value'  => 1,
            'values' => array( 
              'min_range' => 1, 
              'max_range' => 10, 
            ),
            'data_type' => 'number',
          ),
        );
        return $this->webyx_fe_get_cast_settings( $cnf_s, $ps );
      }
      public function webyx_fe_get_cast_settings ( $cnf_s, $ps ) {
        $body = array();
        foreach ( $cnf_s as $cnf_key => $cnf_value ) {
          if ( isset( $ps[ $cnf_key ] ) ) {
            $cnf_data_type = $cnf_value[ 'data_type' ];
            $value = $ps[ $cnf_key ];
            switch ( $cnf_data_type ) {
              case 'string':
                $body[ $cnf_key ] = $value;
                break;
              case 'number':
                $body[ $cnf_key ] = intval( $value );
                break;
              case 'boolean':
                $body[ $cnf_key ] = 'on' === $value ? TRUE : FALSE;
                break;
              case 'url':
                $body[ $cnf_key ] = $value[ 'url' ];
                break;
            }
          } else {
            $body[ $cnf_key ] = $cnf_value[ 'value' ];
          }
        }
        return '<script>var wbySet=' . json_encode( $body, true ) . '</script>';
      }
      public function webyx_fe_get_section_style_validated ( $s, $ps, $cn_bkg, $cn_wrp_cnt ) {
        $css_validated = $this->webyx_fe_get_css_section_props_validated( $s, $ps, $cn_bkg, $cn_wrp_cnt );
        return $css_validated ? '<style>' . $css_validated . '</style>' : '';
      }
      public function webyx_fe_get_css_section_props_validated ( $s, $ps, $cn_bkg, $cn_wrp_cnt ) {
        $css_validated = '';
        $css_validated .= $this->webyx_fe_get_bkg_css_rules_validated( $s, $ps, $cn_bkg, $cn_wrp_cnt );
        $css_validated .= $this->webyx_fe_get_cnt_wrp_css_rules_validated( $s, $ps, $cn_bkg, $cn_wrp_cnt );
        $css_validated .= $this->webyx_fe_get_cnt_wrp_css_rules_xs_validated( $s, $ps, $cn_bkg, $cn_wrp_cnt );
        return $css_validated;
      }
      public function webyx_fe_get_bkg_css_rules_validated ( $s, $ps, $cn_bkg, $cn_wrp_cnt ) {
        $ws_bkg = isset( $s[ 'webyx_section_background' ] ) && in_array( $s[ 'webyx_section_background' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_background' ] : ''; 
        if ( 'on' === $ws_bkg ) {
          $section_background_image_url = isset( $s[ 'webyx_section_background_image' ] ) ? $s[ 'webyx_section_background_image' ] : array( 'url' => '' );
          if ( isset( $section_background_image_url[ 'url' ] ) && '' !== $section_background_image_url[ 'url' ] ) {
            $ws_bkg_image_size = isset( $s[ 'webyx_section_background_image_size' ] ) && in_array( $s[ 'webyx_section_background_image_size' ], array( 'auto', 'cover', 'contain' ), true ) ? $s[ 'webyx_section_background_image_size' ] : 'cover';
            $ws_bkg_image_position = isset( $s[ 'webyx_section_background_image_position' ] ) && in_array( $s[ 'webyx_section_background_image_position' ], array( 'left top', 'left center', 'left bottom', 'right top', 'right center', 'right bottom', 'center top', 'center center', 'center bottom' ), true ) ? $s[ 'webyx_section_background_image_position' ] : 'center center';
            $ws_bkg_image_repeat = isset( $s[ 'webyx_section_background_image_repeat' ] ) && in_array( $s[ 'webyx_section_background_image_repeat' ], array( 'repeat', 'no-repeat' ), true ) ? $s[ 'webyx_section_background_image_repeat' ] : 'no-repeat';
            $ws_bkg_image_attachment = isset( $s[ 'webyx_section_background_image_attachment' ] ) && in_array( $s[ 'webyx_section_background_image_attachment' ], array( 'fixed', 'scroll' ), true ) ? $s[ 'webyx_section_background_image_attachment' ] : 'scroll';
            return '.' . sanitize_html_class( $cn_bkg ) . '{background-image:url(' . esc_url( $section_background_image_url[ 'url' ] ) . ');background-size:' . esc_attr( $ws_bkg_image_size ) . ';background-position:' . esc_attr( $ws_bkg_image_position ) . ';background-repeat:' . esc_attr( $ws_bkg_image_repeat ) . ';background-attachment:' . esc_attr( $ws_bkg_image_attachment ) . '}';
          } else {
            $ws_bkg_colour = $this->webyx_fe_get_global_elmnt_control_value( $s, 'webyx_section_background_colour', '#ffffff' );
            return '.' . sanitize_html_class( $cn_bkg ) . '{background-color:' . esc_attr( $ws_bkg_colour ) . '}';
          }
        } 
        return '';
      }
      public function webyx_fe_get_cnt_wrp_css_rules_validated ( $s, $ps, $cn_bkg, $cn_wrp_cnt ) {
        $ws_wrp_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
        $ws_wrp_cnt_mar_en_dsk = isset( $s[ 'webyx_section_wrapper_cnt_margin_enable_dsk' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_margin_enable_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_margin_enable_dsk' ] : '';
        $ws_wrp_cnt_mar_dsk = isset( $s[ 'webyx_section_wrapper_cnt_margin_dsk' ] ) ? $s[ 'webyx_section_wrapper_cnt_margin_dsk' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
        $ws_wrp_cnt_pad_en_dsk = isset( $s[ 'webyx_section_wrapper_cnt_padding_enable_dsk' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_padding_enable_dsk' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_padding_enable_dsk' ] : '';
        $ws_wrp_cnt_padding_dsk = isset( $s[ 'webyx_section_wrapper_cnt_padding_dsk' ] ) ? $s[ 'webyx_section_wrapper_cnt_padding_dsk' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' );
        $mq_xs = isset( $s[ 'webyx_section_mq_xs' ] ) ? $s[ 'webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
        $mq_val = ( $mq_xs[ 'size' ] + 1 ) . $mq_xs[ 'unit' ];
        $mq_xs_val = $mq_xs[ 'size' ] . $mq_xs[ 'unit' ];
        $props = '';
        if ( 'on' === $ws_wrp_cnt ) {
          if ( 'on' === $ws_wrp_cnt_mar_en_dsk ) {
            $m = $ws_wrp_cnt_mar_dsk;
            $props .= 'margin:' . esc_attr( $m[ 'top' ] . $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'right' ] . $m[ 'unit' ] ) . ' ' .  esc_attr( $m[ 'bottom' ] .  $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'left' ] . $m[ 'unit' ] ) . ';';
          }
          if ( 'on' === $ws_wrp_cnt_pad_en_dsk ) {
            $p = $ws_wrp_cnt_padding_dsk;
            $props .= 'padding:' . esc_attr( $p[ 'top' ] . $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'right' ] . $p[ 'unit' ] ) . ' ' .  esc_attr( $p[ 'bottom' ] . $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'left' ] . $p[ 'unit' ] ) . ';';
          }
        }
        $css_validated = $props ? '.webyx-wrapper-slide-content.' . sanitize_html_class( $cn_wrp_cnt ) . '{' . $props . '}' : '';
        $css_mq = $css_validated ? '@media only screen and (min-width:' . esc_attr( $mq_val ) . '){' . $css_validated . '}' : '';
        return $css_mq;
      }
      public function webyx_fe_get_cnt_wrp_css_rules_xs_validated ( $s, $ps, $cn_bkg, $cn_wrp_cnt ) {
        $ws_wrp_cnt = isset( $s[ 'webyx_section_wrapper_cnt' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt' ] : '';
        $ws_wrp_cnt_mar_en_mob = isset( $s[ 'webyx_section_wrapper_cnt_margin_enable_mob' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_margin_enable_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_margin_enable_mob' ]  : '';
        $ws_wrp_cnt_mar_mob = isset( $s[ 'webyx_section_wrapper_cnt_margin_mob' ] ) ? $s[ 'webyx_section_wrapper_cnt_margin_mob' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' ); 
        $ws_wrp_cnt_pad_en_mob = isset( $s[ 'webyx_section_wrapper_cnt_padding_enable_mob' ] ) && in_array( $s[ 'webyx_section_wrapper_cnt_padding_enable_mob' ], array( 'on', '' ), true ) ? $s[ 'webyx_section_wrapper_cnt_padding_enable_mob' ] : '';
        $ws_wrp_cnt_pad_mob = isset( $s[ 'webyx_section_wrapper_cnt_padding_mob' ] ) ? $s[ 'webyx_section_wrapper_cnt_padding_mob' ] : array( 'unit' => 'px', 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, 'isLinked' => '' ); 
        $mq_xs = isset( $s[ 'webyx_section_mq_xs' ] ) ? $s[ 'webyx_section_mq_xs' ] : array( 'unit' => 'px', 'size' => 760 );
        $mq_val = ( $mq_xs[ 'size' ] + 1 ) . $mq_xs[ 'unit' ];
        $mq_xs_val = $mq_xs[ 'size' ] . $mq_xs[ 'unit' ];
        $props_mob = '';
        if ( 'on' === $ws_wrp_cnt ) {
          if ( 'on' === $ws_wrp_cnt_mar_en_mob ) {
            $m = $ws_wrp_cnt_mar_mob;
            $props_mob .= 'margin:' . esc_attr( $m[ 'top' ] . $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'right' ] . $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'bottom' ] . $m[ 'unit' ] ) . ' ' . esc_attr( $m[ 'left' ] . $m[ 'unit' ] ) . ';';
          }
          if ( 'on' === $ws_wrp_cnt_pad_en_mob ) {
            $p = $ws_wrp_cnt_pad_mob;
            $props_mob .= 'padding:' . esc_attr( $p[ 'top' ] . $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'right' ] . $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'bottom' ] . $p[ 'unit' ] ) . ' ' . esc_attr( $p[ 'left' ] . $p[ 'unit' ] ) . ';';
          }
        };
        $css_validated = $props_mob ? '.webyx-wrapper-slide-content.' . sanitize_html_class( $cn_wrp_cnt ) . '{' . $props_mob . '}' : '';
        $css_mq_xs = $css_validated ? '@media only screen and (max-width:' . esc_attr( $mq_xs_val ) . '){' . $css_validated . '}' : '';
        return $css_mq_xs;
      }
    }
    Webyx_FE::webyx_fe_get_instance();
  }
}