<?php
namespace IfSo\Extensions\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class IFSO_Elementor_Widgets {

	protected static $instance = null;

    public $isOn = false;

	public static function get_instance() {
		if ( ! isset( static::$instance ) ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	protected function __construct() {}

	public function init_elementor_widget(){
        if(did_action( 'elementor/loaded' )){
            $this->isOn = true;
        }

        if($this->isOn){
            require_once( 'widgets/ifso_dynamic_widget.php' );
            if(version_compare(ELEMENTOR_VERSION,'3.5','<'))
                add_action( 'elementor/widgets/widgets_registered', [ $this, 'ifso_register_widgets' ] );
            else
                add_action( 'elementor/widgets/register', [ $this, 'ifso__register_widgets_new' ] );
            // scripts and styles
            add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'ifso_enqueue_scripts' ] );
            add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'ifso_enqueue_styles' ] );
            add_action( 'elementor/preview/enqueue_styles', [ $this, 'ifso_enqueue_preview_styles' ] );

            if(!defined('IFSO_ELEMENTOR_ON') || !IFSO_ELEMENTOR_ON){
                add_action( 'elementor/element/column/section_advanced/after_section_end', [$this,'add_elementor_conditions_extension_notif'], 10, 3 );
                add_action( 'elementor/element/section/section_advanced/after_section_end', [$this,'add_elementor_conditions_extension_notif'], 10, 3 );
                add_action( 'elementor/element/common/_section_style/after_section_end', [$this,'add_elementor_conditions_extension_notif'], 10, 3 );
                add_action( 'elementor/element/popup/section_advanced/after_section_end', [$this,'add_elementor_conditions_extension_notif'], 10, 3 );
            }
        }
    }


	public function ifso_register_widgets() {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\IFSO_Dynamic_Widget() );
	}

    public function ifso__register_widgets_new(){
        \Elementor\Plugin::instance()->widgets_manager->register( new \Elementor\IFSO_Dynamic_Widget() );
    }

	public function ifso_enqueue_preview_styles(){
		wp_enqueue_style( 'ifso-preview', plugin_dir_url(__FILE__)  . 'assets/css/ifso-preview.css' );
	}

	public function ifso_enqueue_scripts() {
	    global $wp_version;
	    global $wp_scripts;
        $ajax_nonce = wp_create_nonce( "ifso-admin-nonce" );
        echo "<script>var nonce = '".$ajax_nonce."';</script>";
		wp_enqueue_script( 'datetime', IFSO_PLUGIN_DIR_URL . 'admin/js/jquery.ifsodatetimepicker.full.min.js',  [ 'jquery' ]);
		wp_enqueue_script( 'WeeklyScheduleMinJs', IFSO_PLUGIN_DIR_URL . 'admin/js/jquery.weekly-schedule-plugin.min.js',  [ 'jquery' ] );
        if(version_compare($wp_version,'5.6')!== -1 || version_compare($wp_scripts->registered['jquery']->ver,'3.5.1')!==-1)    //wp 5.6 intrduced a new version of jquery
            wp_enqueue_script( 'ifso-jquery-ui', IFSO_PLUGIN_DIR_URL . 'admin/js/jquery-ui.min.js', array( 'jquery' ), IFSO_WP_VERSION, false );
        else
            wp_enqueue_script( 'ifso-jquery-ui', IFSO_PLUGIN_DIR_URL . 'admin/js/jquery-ui-old.min.js', array( 'jquery' ), IFSO_WP_VERSION, false );

		wp_enqueue_script( 'ifso-elementor-tr-widget', plugin_dir_url(__FILE__)  . 'assets/js/ifso-elementor-tr-widget.js', ['jquery'] );
	}

	public function ifso_enqueue_styles() {
		wp_enqueue_style( 'ifso-font', plugin_dir_url(__FILE__)  . 'assets/css/ifso-font.css' );
		wp_enqueue_style( 'ifso-editor-css', plugin_dir_url(__FILE__)  . 'assets/css/ifso-editor.css' );
	}

    public function register_elementor_category() {
            \Elementor\Plugin::instance()->elements_manager->add_category(
                'IFSO',
                [
                    'title' => 'IF-SO',
                ]
            );
    }

    public function add_elementor_conditions_extension_notif($element, $section_id = null, $args = null){
        $element->start_controls_section(
            'ifso_elementor_extenesion_notif',
            [
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                'label' => 'If-So Dynamic Condition',
            ]
        );
        $element->add_control(
            'ifso_elementor_extenesion_notif_heading',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => "Please install the <i>If-So and Elementor integration</i> in order to create conditional elements. <a href='https://www.if-so.com/elementor-personalization/?utm_source=Plugin&utm_medium=elementor&utm_campaign=download_elementor_extension' target='_blank'>Download the integration</a>"
            ]
        );
        $element->end_controls_section();
    }

}





