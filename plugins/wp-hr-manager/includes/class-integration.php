<?php
namespace WPHR\HR_MANAGER;

use \WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * Integration Class
 */
class Integration extends WPHR_Settings_Page {
    /**
     * Integration instances.
     */
    public $integrations;

    /**
     * Form option fields.
     *
     * @var array
     */
    public $form_fields = array();

    /**
     * Initializes the clsWP_HR() class
     *
     * Checks for an existing clsWP_HR() instance
     * and if it doesn't find one, creates it.
     */
    public static function wphr_init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Class constructor.
     */
    public function __construct() {
        // Let 3rd parties unhook the above via this hook
        do_action( 'wphr_integration', $this );
    }

    /**
     * Initialize integrations.
     *
     * @return array
     */
    function init_integrations() {
        $this->integrations = apply_filters( 'wphr_integration_classes', $this->integrations );
    }

    /**
     * Return the integration classes - used in admin to load settings.
     *
     * @return array
     */
    public function get_integrations() {
        return $this->integrations;
    }

    /**
     * Get an registered integration instance
     *
     * @param  string  $class_name
     *
     * @return \Integration|false
     */
    public function get_integration( $class_name ) {
        if ( $this->integrations && array_key_exists( $class_name, $this->integrations ) ) {
            return $this->integrations[ $class_name ];
        }

        return false;
    }

    /**
     * Get saved option id
     *
     * @return string
     */
    public function get_option_id() {
        return 'wphr_integration_settings_' . $this->id;
    }

    /**
     * Get the form fields after they are initialized.
     * @return array of options
     */
    public function get_form_fields() {
        return apply_filters( 'wphr_settings_integration_form_fields_' . $this->id, $this->form_fields );
    }

    /**
     * Generate settings html.
     *
     * @return void
     */
    function generate_settings_html() {
        $settings = $this->get_form_fields();
        $this->output_fields( $settings );
    }

    /**
     * Get integration setting by key
     *
     * @param  string  $option
     * @param  string  $default
     *
     * @return string
     */
    public function get_setting( $option, $default = '' ) {
        $settings = get_option( 'wphr_settings_wphr-integration', [] );

        if ( array_key_exists( $option, $settings ) ) {
            return $settings[ $option ];
        }

        return $default;
    }

    /**
     * Get the admin options of this integration.
     *
     * @return void
     */
    public function admin_options() {
        ?>
        <h3><?php echo esc_html( $this->get_title() ); ?></h3>
        <?php echo wpautop( wp_kses_post( $this->get_description() ) ); ?>

        <?php
            /**
             * wphr_email_settings_before action hook.
             *
             * @param string $integration The integration object
             */
            do_action( 'wphr_integration_settings_before', $this );
        ?>

        <table class="form-table">
            <?php $this->generate_settings_html(); ?>
        </table>

        <?php
            /**
             * wphr_integration_settings_after action hook.
             *
             * @param string $integration The integration object
             */
            do_action( 'wphr_integration_settings_after', $this );
        ?>
        <?php
    }

}
