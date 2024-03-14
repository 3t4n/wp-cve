<?php

namespace GSBEH;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Integration Class
class Integration_Elementor {

    private static $_instance = null;
    
    public static function get_instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
        
    }

    public function __construct() {

        add_action( 'elementor/widgets/register', [ $this, 'register_elementor_widget' ] );
        add_action( 'elementor/elements/categories_registered', [$this, 'add_elementor_widget_category'] );
        
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'print_elementor_editor_scripts' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'print_elementor_editor_styles' ] );

        add_action( 'elementor/preview/enqueue_styles', [ $this, 'print_elementor_preview_styles' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'print_elementor_preview_scripts' ] );
        
    }

    public function register_elementor_widget( $widgets_manager ) {

        load_elementor_widget_class();
        $widgets_manager->register( new Elementor_Widget() );

    }

    public function add_elementor_widget_category( $elements_manager ) {

        $elements_manager->add_category(
            'gs-plugins',
            [
                'title' => 'GS Plugins',
                'icon' => 'fa fa-plug',
            ]
        );
    
    }

    public function print_elementor_editor_scripts() {

        ?>
        <script>
            
            window.onload = function() {

                elementor.hooks.addAction( 'panel/open_editor/widget/gs-behance', function( panel, model, view ) {

                    var $shortcode_field = jQuery('.elementor-control-gs_behance_shortcode .elementor-control-input-wrapper select');
                    var $edit_link = jQuery('.elementor-control-gs_behance_shortcode .gs-beh-edit-link');
                    var shortcode_id = $shortcode_field.val();
                    var href = $edit_link.attr('href');
                    href = href.substring(0, href.indexOf('/shortcode/')+11);

                    $edit_link.attr( 'href', href + shortcode_id );

                    $shortcode_field.on('change', function() {
                        shortcode_id = jQuery(this).val();
                        $edit_link.attr( 'href', href + shortcode_id );
                    });

                });

            }

        </script>

        <?php

    }

    public function print_elementor_editor_styles() {

        $icon = GSBEH_PLUGIN_URI . '/assets/img/icon.png';

        ?>

        <style>

            body #elementor-controls .elementor-control-gs_behance_shortcode .elementor-control-field-description {
                font-size: 12px;
                line-height: 1.8;
            }

            body #elementor-panel-elements-wrapper .icon .gs-behance {
                background: url('<?php echo esc_url( $icon ); ?>') no-repeat center center;
                background-size: contain;
                height: 29px;
                display: block;
            }

        </style>

        <?php

    }

    public function print_elementor_preview_styles() {

        plugin()->scripts->wp_enqueue_style_all( 'public', ['gs-behance-public-divi'] );

    }

    public function print_elementor_preview_scripts() {

        plugin()->scripts->wp_enqueue_script_all( 'public' );
        wp_enqueue_script( 'gs-behance-elementor-preview', GSBEH_PLUGIN_URI . '/includes/integrations/assets/elementor/elementor-preview.min.js', ['jquery'], GSBEH_VERSION, true );

    }

}

function load_elementor_widget_class() {

    // Elementor Widget Class
    class Elementor_Widget extends \Elementor\Widget_Base {

        public function get_name() {
            return 'gs-behance';
        }

        public function get_title() {
            return __( 'GS Behance', 'gs-behance' );
        }

        public function get_icon() {
            return 'gs-behance';
        }

        public function get_categories() {
            return [ 'gs-plugins', 'general' ];
        }

        protected function register_controls() {

            $this->start_controls_section(
                'content_section',
                [
                    'label' => __( 'Content', 'gs-behance' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'gs_behance_shortcode',
                [
                    'label' => __( 'Behance Shortcode', 'gs-behance' ),
                    'description' => $this->get_field_description(),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'options' => $this->get_shortcode_list()
                ]
            );

            $this->end_controls_section();

        }

        protected function get_field_description() {

            $edit_link = sprintf( '%s: <a class="gs-beh-edit-link" href="%s" target="_blank">%s</a>',
                __('Edit this shortcode', 'gs-behance'),
                admin_url( "admin.php?page=gs-behance-shortcode#/shortcode/" ),
                __('Edit', 'gs-behance')
            );

            $create_link = sprintf( '%s: <a class="gs-beh-create-link" href="%s" target="_blank">%s</a>',
                __('Create new shortcode', 'gs-behance'),
                admin_url( 'admin.php?page=gs-behance-shortcode#/shortcode' ),
                __('Create', 'gs-behance')
            );

            return implode( '<br />', [$edit_link, $create_link] );

        }

        protected function get_shortcode_list() {

            $shortcodes = get_shortcodes();

            if ( !empty($shortcodes) ) {
                return wp_list_pluck( $shortcodes, 'shortcode_name', 'id' );
            }
            
            return [];

        }

        protected function get_default_item() {

            $shortcodes = get_shortcodes();

            if ( !empty($shortcodes) ) {
                return $shortcodes[0]['id'];
            }

            return '';

        }

        protected function render() {

            $shortcode_id = $this->get_settings_for_display( 'gs_behance_shortcode' );

            if ( empty($shortcode_id) ) {
                if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
                    printf( '<div class="gs-beh-elementor-no-shortcode" style="padding:20px;background:rgba(115, 113, 124, 0.3);color:#1a1828;font-size:18px;border-radius:4px;font-weight:500;">%s</div>', __('GS Behance: Please select a shortcode', 'gs-behance') );
                }
            } else {
                echo do_shortcode( "[gs_behance id={$shortcode_id}]" );
            }

        }

    }

}