<?php

if (! class_exists('AM_MT_REST_Settings_Controller') ) {

    include AUTOMIZELY_MARKETING_PATH . '/includes/class-am-rest-controller.php';

    /**
     * Class AM_MT_REST_Settings_Controller
     */
    class AM_MT_REST_Settings_Controller extends AM_REST_Controller
    {


        /**
         * API endpoint always wc/marketing/v1.
         *
         * @var string
         */
        protected $namespace = 'wc/marketing/v1';
        /**
         * API resource is settings.
         *
         * @var string
         */
        protected $rest_base = 'settings';

        /**
         * Marketing option name.
         *
         * @var string
         */
        private $option_name = 'marketing_option_name';

        /**
         * AM_DP_REST_Settings_Controller constructor.
         */
        public function __construct()
        {
        }

        /**
         * GET Marketing Settings
         *
         * @return array
         */
        public function get_settings()
        {
            $settings            = get_option($this->option_name);
            if (!isset($settings['connected'])) {
                $settings['connected'] = false;
            }
            $settings['currency']    = get_woocommerce_currency();
            $settings['weight_unit'] = get_option('woocommerce_weight_unit');
            $settings['locale']      = get_locale();
            $settings['version'] = array(
                'wordpress'   => get_bloginfo('version'),
                'woocommerce' => WC()->version,
                'marketing'   => AUTOMIZELY_MARKETING_VERSION,
            );
            return array( 'settings' => $settings );
        }

        /**
         * Create or update settings
         *
         * @param  WP_REST_Request $data Request object.
         * @return array
         */
        public function create_or_update_settings( WP_REST_Request $data )
        {
            $options            = get_option($this->option_name);
            if (isset($data['connected']) && in_array($data['connected'], array( true, false ), true) ) {
                $options['connected'] = $data['connected'];
            }

            update_option($this->option_name, $options);
            return $this->get_settings();
        }

        /**
         * Register router.
         */
        public function register_routes()
        {
            register_rest_route(
                $this->namespace,
                '/' . $this->rest_base,
                array(
                    array(
                        'methods'             => WP_REST_Server::READABLE,
                        'callback'            => array( $this, 'get_settings' ),
                        'permission_callback' => array( $this, 'get_items_permissions_check' ),
                        'args'                => array(),
                    ),
                    array(
                        'methods'             => WP_REST_Server::CREATABLE,
                        'callback'            => array( $this, 'create_or_update_settings' ),
                        'permission_callback' => array( $this, 'create_item_permissions_check' ),
                        'args'                => array(),
                    ),
                )
            );
        }
    }
}
