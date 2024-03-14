<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

class ShareASale_WC_Tracker_Autovoid {

    /**
    * @var string $version Plugin version
    */

    private $version;

    public function __construct( $version ) {
        $this->version = $version;
        $this->options = get_option( 'shareasale_wc_tracker_options' );
    }

    public function enqueue_scripts( $hook ) {
        $src = esc_url( plugin_dir_url( __FILE__ ) . 'js/shareasale-wc-tracker-autovoid.js' );

        wp_enqueue_script(
            'shareasale-wc-tracker-autovoid',
            $src,
            array(),
            $this->version
        );

        wp_localize_script(
            'shareasale-wc-tracker-autovoid',
            'shareasaleWcTrackerAutovoidData',
            array(
                'autovoid_key' => @$this->options['autovoid-key'],
                'autovoid_value' => @$this->options['autovoid-value'],
            )
        );
    }
}
