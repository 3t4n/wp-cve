<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    LaStudio Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! class_exists( 'LaStudio_Kit_Post_Meta' ) ) {

    /**
     * Define LaStudio_Kit_Post_Meta class
     */
    class LaStudio_Kit_Post_Meta {

        /**
         * A reference to an instance of this class.
         *
         * @since 1.0.0
         * @var   LaStudio_Kit_Post_Meta
         */
        private static $instance = null;

        /**
         * Mata options
         *
         * @var array
         */
        private $options = array();

        /**
         * Constructor for the class
         */
        public function __construct() {
            add_action( 'init', array( $this, 'init_post_meta' ) );
        }

        /**
         * Add meta options
         *
         * @param $options
         */
        public function add_options( array $options = array() ) {
            $this->options[] = $options;
        }

        /**
         * Init meta
         */
        public function init_post_meta() {

            foreach ( $this->options as $options ) {

                if ( ! isset( $options['builder_cb'] ) ) {
                    $options['builder_cb'] = array( $this, 'get_interface_builder' );
                }

                new Cherry_X_Post_Meta( $options );
            }
        }

        public function get_interface_builder() {

            $builder_data = lastudio_kit()->module_loader->get_included_module_data( 'interface-builder.php' );

            return new CX_Interface_Builder(
                array(
                    'path' => $builder_data['path'],
                    'url'  => $builder_data['url'],
                )
            );
        }

        /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @return LaStudio_Kit_Post_Meta
         */
        public static function get_instance() {

            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
    }

}

function lastudio_kit_post_meta() {
    return LaStudio_Kit_Post_Meta::get_instance();
}

lastudio_kit_post_meta();
