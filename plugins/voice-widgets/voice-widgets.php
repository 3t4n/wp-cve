<?php 

/**
 * Plugin Name: Voice Widgets
 * Plugin URI: https://dna88.com/
 * Description: Voice Widgets for WordPress site
 * Author: dna88
 * Version: 3.3.0
 * Author URI: https://dna88.com/
 * Text Domain: voice-widgets
 * Requires PHP: 5.6
 * Requires at least: 4.9
 * Tested up to: 6.4
 **/

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/*
* Define some global constants
*/
if ( ! defined( 'QC_VOICEWIDGET_VERSION' ) ) {
    define( 'QC_VOICEWIDGET_VERSION', '1.0.0' );
}
if ( ! defined( 'QC_VOICEWIDGET_BASE' ) ) {
    define( 'QC_VOICEWIDGET_BASE', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'QC_VOICEWIDGET_PLUGIN_DIR' ) ) {
    define( 'QC_VOICEWIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'QC_VOICEWIDGET_PLUGIN_URL' ) ) {
    define( 'QC_VOICEWIDGET_PLUGIN_URL', plugin_dir_url(__FILE__) );
}
if ( ! defined( 'QC_VOICEWIDGET_PLUGIN_TEMPLATE_URL' ) ) {
    define( 'QC_VOICEWIDGET_PLUGIN_TEMPLATE_URL', plugin_dir_url(__FILE__) . 'templates/' );
}
if ( ! defined( 'QC_VOICEWIDGET_ASSETS_URL' ) ) {
    define( 'QC_VOICEWIDGET_ASSETS_URL', QC_VOICEWIDGET_PLUGIN_URL . 'assets/' );
}

if ( ! class_exists( 'QC_Voice_Widgets' ) ) {

    final class QC_Voice_Widgets {

        private static $instance;
        private function __construct() {

            add_action( 'plugins_loaded', [$this, 'load_textdomain'] );
            add_action( 'init', [ $this, 'register_cpt' ] );
            add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts'] );
            add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts'] );
            add_shortcode( 'qc_audio', [ $this, 'render_shortcode'] );
            if ( is_admin() ) {
                add_action( 'load-post.php',     [ $this, 'meta_box_setup' ] );
                add_action( 'load-post-new.php', [ $this, 'meta_box_setup' ] );
                add_action( 'save_post', [ $this, 'save_post' ], 10, 2 );

                add_filter('manage_wp_voicemsg_record_posts_columns', [ $this, 'table_columns_head' ] );
                add_action('manage_wp_voicemsg_record_posts_custom_column', [ $this, 'table_columns_content' ], 10, 2);
            }


            add_action( 'admin_menu', [$this, 'admin_menu'], 10 );

            add_action('wp_ajax_qcld_audio_save', [ $this, 'save_audio' ]);
            add_action('wp_ajax_nopriv_qcld_audio_save', [ $this, 'save_audio' ]);

            add_action( 'admin_init', [ $this, 'qc_voice_widgets_register_plugin_settings' ] );

        }

        public function admin_menu(){
            
            add_submenu_page(
                'edit.php?post_type=wp_voicemsg_record',
                __( 'CF7 Settings', 'textdomain' ),
                __( 'CF7 Settings', 'textdomain' ),
                'manage_options',
                'wp_voicemsg_settings',
                array($this,'QC_qcwpvoicemessage_help_callback')
            );
        }

        public function admin_scripts() {
            global $post, $pagenow, $typenow;

            if( 'wp_voicemsg_record' === $typenow ) {
                
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_style( 'qc-voice-widgets-audio',  QC_VOICEWIDGET_ASSETS_URL . 'css/audio_admin.css', array(), QC_VOICEWIDGET_VERSION );
                wp_enqueue_script( 'qc-voice-widgets-recorder-js', QC_VOICEWIDGET_ASSETS_URL .  'js/recorder.js', ['jquery'], QC_VOICEWIDGET_VERSION, true );
                wp_enqueue_script( 'qc-voice-widgets-audio-js', QC_VOICEWIDGET_ASSETS_URL .  'js/audio_admin.js', ['jquery', 'wp-color-picker', 'jquery-ui-tabs'], QC_VOICEWIDGET_VERSION, true );

                $voice_obj = array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'capture_duration'  => (get_option('stt_sound_duration') && get_option('stt_sound_duration') != '' ? get_option('stt_sound_duration') : MINUTE_IN_SECONDS * 10 ),
                    'post_id' => isset( $post->ID ) ? $post->ID : 0,
                    'templates' => $this->get_templates()
                );
                wp_localize_script('qc-voice-widgets-audio-js', 'voice_obj', $voice_obj);
            }

        }

        public function frontend_scripts() {
            wp_register_style( 'qc_audio_font_awesome', QC_VOICEWIDGET_ASSETS_URL . 'css/font-awesome.min.css', array(), QC_VOICEWIDGET_VERSION );
            wp_register_style( 'qc-voice-widgets-audio-front',  QC_VOICEWIDGET_ASSETS_URL . 'css/audio_frontend.css', array(), QC_VOICEWIDGET_VERSION );
            wp_register_script( 'qc-voice-widgets-audio-js-frontend', QC_VOICEWIDGET_ASSETS_URL .  'js/audio_frontend.js', ['jquery'], QC_VOICEWIDGET_VERSION, true );
        }

        public function get_templates() {
            return array(
                'default'    => array(
                    'name' => esc_html__( 'Default', 'voice-widgets' ),
                    'image' => esc_url_raw( QC_VOICEWIDGET_PLUGIN_URL . 'templates/admin/images/default-template.png' )
                )
            );
        }

        public function QC_qcwpvoicemessage_help_callback(){
            ob_start();
            wp_enqueue_style('qcld-wp-voice-help-page-css',  QC_VOICEWIDGET_ASSETS_URL . 'css/help-page.css');
            require_once( 'templates/settings.php');
        }

        public function load_textdomain() {
            load_plugin_textdomain( 'voice-widgets', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        /**
         * Set up meta boxes
         *
         * @return void
         */
        public function meta_box_setup():void {
            /* Add meta boxes on the 'add_meta_boxes' hook. */
            add_action( 'add_meta_boxes', [ $this, 'add_post_meta_boxes' ] );
        }

        public function add_post_meta_boxes() {

            add_meta_box(
                'audio-post-class',                                 // Unique ID
                esc_html__( 'Audio Options', 'voice-widgets' ),    // Title
                [ $this, 'render_audio_options_meta_box' ],         // Callback function
                'wp_voicemsg_record',                               // Admin page (or post type)
                'normal',                                           // Context
                'default'                                           // Priority
            );

            add_meta_box(
                'audio-post-class-template',                                 // Unique ID
                esc_html__( 'Template Settings', 'voice-widgets' ),    // Title
                [ $this, 'render_audio_template_meta_box' ],         // Callback function
                'wp_voicemsg_record',                               // Admin page (or post type)
                'normal',                                           // Context
                'default'                                           // Priority
            );

            add_meta_box(
                'audio-post-class-shortcode',                                 // Unique ID
                esc_html__( 'Shortcode', 'voice-widgets' ),    // Title
                [ $this, 'render_audio_shortcode_meta_box' ],         // Callback function
                'wp_voicemsg_record',                               // Admin page (or post type)
                'side',                                           // Context
                'default'                                           // Priority
            );

        }

        public function save_post( $post_id, $posts ) {

            if ( isset( $_POST['qc_audio_url'] ) && '' !== $_POST['qc_audio_url'] ) {
                $audio_url = esc_url_raw( $_POST['qc_audio_url'] );
                update_post_meta( $post_id, 'qc_audio_url', $audio_url );
            } else {
                delete_post_meta( $post_id, 'qc_audio_url' );
            }
            if ( isset( $_POST['qc_audio_template'] ) && '' !== $_POST['qc_audio_template'] ) {
                $qc_audio_template = sanitize_text_field( $_POST['qc_audio_template'] );
                update_post_meta( $post_id, 'qc_audio_template', $qc_audio_template );
            }
            
            if ( isset( $_POST['qc_audio_color'] ) && '' !== $_POST['qc_audio_color'] ) {
                $qc_audio_color = sanitize_text_field( $_POST['qc_audio_color'] );
                update_post_meta( $post_id, 'qc_audio_color', $qc_audio_color );
            }
            
        }

        public function render_audio_options_meta_box( $post ) {
            $template = QC_VOICEWIDGET_PLUGIN_DIR . 'templates/admin/audio_options.php';
            if ( file_exists( $template ) ) {
                include_once $template; 
            }
        }

        public function render_audio_template_meta_box( $post ) {
            $template = QC_VOICEWIDGET_PLUGIN_DIR . 'templates/admin/audio_template.php';
            if ( file_exists( $template ) ) {
                include_once $template; 
            }
        }

        public function render_shortcode( $atts ) {
            $attributes = shortcode_atts( array(
                'id' => 0
            ), $atts );

            extract( $attributes );

            if ( 0 != $id ) {

                // Configuration area.
                $featured_img_url = get_the_post_thumbnail_url( $id, 'full' );

                if ( empty( $featured_img_url ) ) {
                    $featured_img_url = QC_VOICEWIDGET_ASSETS_URL . 'images/thinking.png';
                }

                $audio_url = get_post_meta( $id, 'qc_audio_url', true );
                $audio_template = get_post_meta( $id, 'qc_audio_template', true );

                $audio_template = 'default';

                $template = QC_VOICEWIDGET_PLUGIN_DIR . 'templates/frontend/'.$audio_template.'.php';
                if ( $audio_template === 'call_to_action' ) {
                    $qc_audio_call_to_action_text = get_post_meta( $id, 'qc_audio_call_to_action_text', true );
                    $qc_audio_call_to_action_button_label = get_post_meta( $id, 'qc_audio_call_to_action_button_label', true );
                    $qc_audio_call_to_action_url = get_post_meta( $id, 'qc_audio_call_to_action_url', true );
                    $qc_audio_call_to_action_new_tab = get_post_meta( $id, 'qc_audio_call_to_action_new_tab', true );
                }

                // styles & scripts
                wp_enqueue_script( 'qc-voice-widgets-audio-js-frontend' );
                wp_enqueue_style( 'qc-voice-widgets-audio-front' );
                wp_enqueue_style( 'qc_audio_font_awesome' );
                $qc_audio_color = get_post_meta( $id, 'qc_audio_color', true );
                if ( $qc_audio_color && '' != $qc_audio_color ) {
                    $custom_css = ".{$audio_template}_{$id} .qc_audio_animation{border-color: {$qc_audio_color};}.{$audio_template}_{$id} .circle{background-color:{$qc_audio_color};}.{$audio_template}_{$id} .qc_audio_play_button{color:{$qc_audio_color}}.{$audio_template}_{$id} .qc_audio_bar a{background-color:{$qc_audio_color};}.{$audio_template}_{$id} .wave-block .stroke{background:{$qc_audio_color}}";
                    wp_add_inline_style( 'qc-voice-widgets-audio-front', $custom_css );
                }

                if ( file_exists( $template ) ) {
                    ob_start();
                    include $template; 
                    $data = ob_get_clean();
                    return $data;
                } else {
                    return esc_html__( 'Template does not exists!', 'voice-widgets' );
                }
            }

        }

        public function save_audio() {

            $response['status'] = 'failed';

            if ( isset( $_FILES['audio_data'] ) ) {
                $file_name = 'qc_audio_'.time().'.mp3';

                  $arrContextOptions=array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "verify_peer_name"=>false,
                        ),
                    );  


                $file = wp_upload_bits( $file_name, null, @file_get_contents( $_FILES['audio_data']['tmp_name'], false, stream_context_create($arrContextOptions) ) );
                if ( FALSE === $file['error'] ) {
                    $response['status'] = 'success';
                    $response['url'] =  $file['url'];
                }else{
                    $response['message'] = $file['error'];
                }
                
            }
            echo json_encode($response);
            die();
        }

        public function render_audio_shortcode_meta_box($post) {
            echo '<input id="qc_audio_shortcode" type="text" value="[qc_audio id='. esc_attr( $post->ID ) .']">';
            ?>
            <div class="qc_tooltip">
                <div onclick="qc_myFunction()" onmouseout="qc_outFunc()">
                    <span class="qc_tooltiptext" id="qc_myTooltip"><?php echo esc_html__( 'Copy to clipboard', 'voice-widgets' ); ?></span>
                    <span class="dashicons dashicons-admin-page"></span>
                </div>
            </div>
            <?php
        }

        /**
         * Register Audio posts
         *
         * @return void
         */
        public function register_cpt() {
            register_post_type('wp_voicemsg_record', [
                'public'              => false,
                'labels'              => [
                    'name'                  => esc_html__( 'Voice Widgets', 'voice-widgets' ),
                    'singular_name'         => esc_html__( 'Voice Widget', 'voice-widgets' ),
                    'add_new'               => esc_html__( 'Add New', 'voice-widgets' ),
                    'add_new_item'          => esc_html__( 'Add New Voice Widget', 'voice-widgets' ),
                    'new_item'              => esc_html__( 'New Voice Widget', 'voice-widgets' ),
                    'edit_item'             => esc_html__( 'Edit Voice Widget', 'voice-widgets' ),
                    'view_item'             => esc_html__( 'View Voice Widget', 'voice-widgets' ),
                    'view_items'            => esc_html__( 'View Voice Widget', 'voice-widgets' ),
                    'search_items'          => esc_html__( 'Search Voice Widget', 'voice-widgets' ),
                    'not_found'             => esc_html__( 'No Audio found', 'voice-widgets' ),
                    'not_found_in_trash'    => esc_html__( 'No Audio found in Trash', 'voice-widgets' ),
                    'all_items'             => esc_html__( 'All Voice Widgets', 'voice-widgets' ),
                    'archives'              => esc_html__( 'Audio Archives', 'voice-widgets' ),
                    'attributes'            => esc_html__( 'Audio Attributes', 'voice-widgets' ),
                    'insert_into_item'      => esc_html__( 'Insert to Voice Message for Record', 'voice-widgets' ),
                    'uploaded_to_this_item' => esc_html__( 'Uploaded to this Voice Message for  Record', 'voice-widgets' ),
                    'menu_name'             => esc_html__( 'Voice Widgets', 'voice-widgets' ),
                ],
                'menu_icon'             => 'dashicons-microphone',
                'exclude_from_search'   => true,
                'publicly_queryable'    => false,
                'menu_position'         => false,
                'show_in_rest'          => false,
                'show_in_menu'          => true,
                'supports'              => [ 'title', 'thumbnail' ],
                'capabilities'          => [ 'create_posts' => true ],
                'map_meta_cap'          => true,
                'show_ui'               => true,
            ] );
            
        }

        public function table_columns_head( $defaults ) {
            $new_columns['cb'] = '<input type="checkbox" />';
            $new_columns['title'] = __( 'Title' );
            $new_columns['shortcode'] = esc_html__( 'Shortcode', 'voice-widgets' );

            $new_columns['date'] = __('Date');
            return $new_columns;
        }

        public function table_columns_content( $column_name, $post_ID ) {
            if ( 'shortcode' == $column_name ) {
                echo '<input class="qc_audio_shortcode_elem" type="text" value="[qc_audio id='. esc_attr( $post_ID ) .']">';
            }
        }

        /**
         * Singleton class instance
         *
         * @return QC_Voice_Widgets
         */
        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof QC_Voice_Widgets ) ) {
                self::$instance = new QC_Voice_Widgets;
            }
            return self::$instance;
        }



        public function qc_voice_widgets_register_plugin_settings(){  

            $args = array(
                'type' => 'string', 
                'sanitize_callback' => 'sanitize_text_field',
                'default' => NULL,
            );  

            $args_email = array(
                'type' => 'string', 
                'sanitize_callback' => 'sanitize_email',
                'default' => NULL,
            );    
          
            register_setting( 'qc-voice-widgets-settings-group', 'qc_voice_widget_lan_record_audio', $args );
            register_setting( 'qc-voice-widgets-settings-group', 'qc_voice_widget_lan_speak_now', $args );
            register_setting( 'qc-voice-widgets-settings-group', 'qc_voice_widget_lan_stop_save', $args );
            register_setting( 'qc-voice-widgets-settings-group', 'qc_voice_widget_lan_canvas_not_available', $args );
            register_setting( 'qc-voice-widgets-settings-group', 'qc_voice_widget_lan_please_wait', $args );
            register_setting( 'qc-voice-widgets-settings-group', 'stt_sound_duration', $args );

        }

    }
}

// Start the plugin
QC_Voice_Widgets::get_instance();

include_once('class-dna88-free-plugin-upgrade-notice.php');
include_once('voice-widgets-cf7.php');


if ( ! function_exists( 'QC_VOICEWIDGET_activation_redirect' ) ) {
    function QC_VOICEWIDGET_activation_redirect( $plugin ) {

        if( $plugin == plugin_basename( __FILE__ ) ) {
            exit( wp_redirect( admin_url( 'edit.php?post_type=wp_voicemsg_record') ) );
        }
      
    }
}
add_action( 'activated_plugin', 'QC_VOICEWIDGET_activation_redirect' );


//add_action( 'admin_notices', 'QC_VOICEWIDGET_pro_notice', 100 );
function QC_VOICEWIDGET_pro_notice(){
    global $pagenow, $typenow;

    if(isset($typenow) && ( $typenow == 'wp_voicemsg_record') ){
    ?>
    <div id="message" class="notice notice-info is-dismissible" style="padding:4px 0px 0px 4px;background:#C13825;">
        <?php
            printf(
                __('%s  %s  %s', 'voice-widgets'),
                '<a href="'.esc_url('https://www.dna88.com/product/voice-widgets-pro/').'" target="_blank">',
                '<img src="'.esc_url(QC_VOICEWIDGET_ASSETS_URL).'/images/new-year-23.gif" >',
                '</a>'
            );
        ?>
    </div>
<?php
    }

}