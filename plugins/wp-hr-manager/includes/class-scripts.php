<?php
namespace WPHR\HR_MANAGER;

use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Scripts and styles
 */
class Scripts {

    use Hooker;

    /**
     * Script and style suffix
     *
     * @var string
     */
    protected $suffix;

    /**
     * Script version number
     *
     * @var integer
     */
    protected $version;

    /**
     * Initialize the class
     */
    public function __construct() {
        $this->suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
        $this->version = '20181003';

        $this->action( 'admin_enqueue_scripts', 'scripts_handler' );
    }

    /**
     * Register and enqueue scripts and styles
     *
     * @return void
     */
    public function scripts_handler() {
        $this->register_scripts();
        $this->register_styles();

        $this->enqueue_scripts();
        $this->enqueue_styles();
    }

    public function register_scripts() {

        // wp_register_script( $handle, $src, $deps, $ver, $in_footer );
        $vendor = WPHR_ASSETS . '/vendor';
        $js     = WPHR_ASSETS . '/js';

        // register vendors first
        wp_register_script( 'wphr-select2', $vendor . '/select2/select2.full.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-tiptip', $vendor . '/tiptip/jquery.tipTip.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-momentjs', $vendor . '/moment/moment.min.js', false, $this->version, true );
        wp_register_script( 'wphr-fullcalendar', $vendor . '/fullcalendar/fullcalendar' . $this->suffix . '.js', array( 'jquery', 'wphr-momentjs' ), $this->version, true );
        wp_register_script( 'wphr-timepicker', $vendor . '/timepicker/jquery.timepicker.min.js', array( 'jquery', 'wphr-momentjs' ), $this->version, true );
        wp_register_script( 'wphr-vuejs', $vendor . '/vue/vue' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-trix-editor', $vendor . '/trix/trix.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-nprogress', $vendor . '/nprogress/nprogress.js', array( 'jquery' ), $this->version, true );

        // sweet alert
        wp_register_script( 'wphr-sweetalert', $vendor . '/sweetalert/sweetalert.min.js', array( 'jquery' ), $this->version, true );

        // flot chart
        wp_register_script( 'wphr-flotchart', $vendor . '/flot/jquery.flot.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-time', $vendor . '/flot/jquery.flot.time.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-orerbars', $vendor . '/flot/jquery.flot.orderBars.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-pie', $vendor . '/flot/jquery.flot.pie.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-axislables', $vendor . '/flot/jquery.flot.axislabels.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-categories', $vendor . '/flot/jquery.flot.categories.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-tooltip', $vendor . '/flot/jquery.flot.tooltip.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-resize', $vendor . '/flot/jquery.flot.resize.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-valuelabel', $vendor . '/flot/jquery.flot.valuelabels.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-navigate', $vendor . '/flot/jquery.flot.navigate.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-selection', $vendor . '/flot/jquery.flot.selection.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-flotchart-stack', $vendor . '/flot/jquery.flot.stack.js', array( 'jquery' ), $this->version, true );

        // core js files
        wp_register_script( 'wphr-popup', $js . '/jquery-popup' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-script', $js . '/wphr' . $this->suffix . '.js', array( 'jquery', 'backbone', 'underscore', 'wp-util', 'jquery-ui-datepicker' ), $this->version, true );
        wp_register_script( 'wphr-file-upload', $js . '/upload' . $this->suffix . '.js', array( 'jquery', 'plupload-handlers' ), $this->version, true );
        wp_register_script( 'wphr-admin-settings', $js . '/settings' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );

        // tether.js
        wp_register_script( 'wphr-tether-main', $vendor . '/tether/tether.min.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'wphr-tether-drop', $vendor . '/tether/drop.min.js', array( 'jquery' ), $this->version, true );

        // clipboard.js
        wp_register_script( 'wphr-clipboard', $vendor . '/clipboard/clipboard.min.js', array( 'jquery' ), $this->version, true );

        // toastr.js
        wp_register_script( 'wphr-toastr', $vendor . '/toastr/toastr.min.js', array(), $this->version, true );
    }

    /**
     * Register all the styles
     *
     * @return void
     */
    public function register_styles() {
        $vendor = WPHR_ASSETS . '/vendor';
        $css     = WPHR_ASSETS . '/css';

        wp_register_style( 'wphr-fontawesome', $vendor . '/fontawesome/font-awesome.min.css', false, $this->version );
        wp_register_style( 'wphr-select2', $vendor . '/select2/select2.min.css', false, $this->version );
        wp_register_style( 'wphr-tiptip', $vendor . '/tiptip/tipTip.css', false, $this->version );
        wp_register_style( 'wphr-fullcalendar', $vendor . '/fullcalendar/fullcalendar' . $this->suffix . '.css', false, $this->version );
        wp_register_style( 'wphr-timepicker', $vendor . '/timepicker/jquery.timepicker.css', false, $this->version );
        wp_register_style( 'wphr-trix-editor', $vendor . '/trix/trix.css', false, $this->version );
        wp_register_style( 'wphr-flotchart-valuelabel-css', $vendor . '/flot/plot.css', false, $this->version );
        wp_register_style( 'wphr-nprogress', $vendor . '/nprogress/nprogress.css', false, $this->version );

        // jquery UI
        wp_register_style( 'jquery-ui', $vendor . '/jquery-ui/jquery-ui-1.9.1.custom.css' );

        // sweet alert
        wp_register_style( 'wphr-sweetalert', $vendor . '/sweetalert/sweetalert.css', false, $this->version );

        // tether drop theme
        wp_register_style( 'wphr-tether-drop-theme', $vendor . '/tether/drop-theme.min.css', false, $this->version );

        // toastr.js
        wp_register_style( 'wphr-toastr', $vendor . '/toastr/toastr.min.css', false, $this->version );

        // core css files
        wp_register_style( 'wphr-styles', $css . '/admin.css', false, $this->version );

    }

    /**
     * Enqueue the scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();
        $screen_base = isset( $screen->base ) ? $screen->base : false;
       $hook = str_replace( sanitize_title( __( 'WPHR Manager', 'wphr' ) ) , 'wphr-manager', $screen_base );

        wp_enqueue_script( 'wphr-select2' );
        wp_enqueue_script( 'wphr-popup' );
        wp_enqueue_script( 'wphr-script' );

        wp_localize_script( 'wphr-script', 'wpHr', array(
            'nonce'           => wp_create_nonce( 'wphr-nonce' ),
            'ajaxurl'         => admin_url( 'admin-ajax.php' ),
            'set_logo'        => __( 'Set company logo', 'wphr' ),
            'upload_logo'     => __( 'Upload company logo', 'wphr' ),
            'remove_logo'     => __( 'Remove company logo', 'wphr' ),
            'update_location' => __( 'Update Location', 'wphr' ),
            'create'          => __( 'Create', 'wphr' ),
            'update'          => __( 'Update', 'wphr' ),
            'confirmMsg'      => __( 'Are you sure?', 'wpuf' ),
            'ajaxurl'         => admin_url( 'admin-ajax.php' ),
            'plupload'        => array(
                'url'              => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'wphr_featured_img' ),
                'flash_swf_url'    => includes_url( 'js/plupload/plupload.flash.swf' ),
                'filters'          => array(array('title' => __( 'Allowed Files' ), 'extensions' => '*')),
                'multipart'        => true,
                'urlstream_upload' => true,
            )
        ) );

        // load country/state JSON on new company page
        if ( 'toplevel_page_wphr-company' == $hook || "wphr-settings_page_wphr-company"== $hook  || isset( $_GET['action'] ) && in_array( sanitize_text_field( $_GET['action'] ), array( 'new', 'edit' ) ) ) {
            wp_enqueue_script( 'post' );
            wp_enqueue_media();

            $country = \WPHR\HR_MANAGER\Countries::instance();
            wp_localize_script( 'wphr-script', 'wpHrCountries', $country->load_country_states() );
        }
        if ( 'wphr-manager_page_wphr-hr-employee' == $hook || 'wphr-manager_page_wphr-hr-my-profile' == $hook ) {
			$country = \WPHR\HR_MANAGER\Countries::instance();
            wp_localize_script( 'wphr-script', 'wpHrCountries', $country->load_country_states() );
        }
    }

    /**
     * Enqueue the stylesheet
     *
     * @return void
     */
    public function enqueue_styles() {
        wp_enqueue_style( 'wphr-fontawesome' );
        wp_enqueue_style( 'wphr-select2' );
        wp_enqueue_style( 'jquery-ui' );

        wp_enqueue_style( 'wphr-styles' );
    }
}
