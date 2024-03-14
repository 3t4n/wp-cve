<?php

defined( 'ABSPATH' ) || exit;
/**
 * This class is responsible for all tax related operations
 */

include_once OLIVER_POS_ABSPATH . 'includes/models/class-pos-bridge-tax.php';

use bridge_models\Pos_Bridge_Tax as Tax;

class Pos_Bridge_Tax {

    private $pos_bridge_tax;

    function __construct() {
        $this->pos_bridge_tax = new Tax();
    }

    public function oliver_pos_get_taxes() {
        return $this->pos_bridge_tax->oliver_pos_get_taxes();
    }

    public function oliver_pos_get_tax( $request_data ) {
        $parameters = $request_data->get_params();

        if (isset($parameters['id']) && !empty($parameters['id'])) {
            $id = (int) sanitize_text_field( $parameters['id'] );
            return $this->pos_bridge_tax->oliver_pos_get_tax($id);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_get_tax_by_location( $request_data ) {

        $parameters = $request_data->get_params();
        return $this->pos_bridge_tax->oliver_pos_get_tax_by_location( $parameters );

    }

    public function oliver_pos_tax_rate_added_listener( $id, $tax_rate ) {
        oliver_log("create tax trigger");
        //$this->tax_sync_dotnet( $id, esc_url_raw( ASP_TRIGGER_CREATE_TAX ) );
        $this->oliver_pos_post_tax_data_to_dotnet( $id, esc_url_raw( ASP_TRIGGER_CREATE_TAX ) );
        //Schedules a hook which will be triggered by WordPress at the specified time
        wp_schedule_single_event(  time() + 5, 'woocommerce_tax_location_updated', array($id, $tax_rate));
        return 1;
    }

    public function oliver_pos_tax_rate_updated_listener( $id, $tax_rate ) {
        oliver_log("update tax trigger");
        //$this->tax_sync_dotnet( $id, esc_url_raw( ASP_TRIGGER_UPDATE_TAX ) );
        $this->oliver_pos_post_tax_data_to_dotnet( $id, esc_url_raw( ASP_TRIGGER_UPDATE_TAX ) );
        //Schedules a hook which will be triggered by WordPress at the specified time
        wp_schedule_single_event(  time() + 5, 'woocommerce_tax_location_updated', array($id, $tax_rate));
        return 1;
    }
    //Since version 2.3.8.1 Add
    public function oliver_pos_tax_rate_updated_listener_delay_call( $id, $tax_rate ) {
        oliver_log("update tax trigger delay");
        //$this->tax_sync_dotnet( $id, esc_url_raw( ASP_TRIGGER_UPDATE_TAX ) );
        $this->oliver_pos_post_tax_data_to_dotnet( $id, esc_url_raw( ASP_TRIGGER_UPDATE_TAX ) );
        return 1;
    }

    public function oliver_pos_tax_rate_deleted_listener( $id ) {
        oliver_log("Start delete tax row trigger");

        $this->oliver_pos_tax_sync_dotnet( $id, esc_url_raw( ASP_TRIGGER_REMOVE_TAX ) );

        oliver_log("End delete tax row trigger");
        return $id;
    }

    public static function oliver_pos_tax_count() {
        return Tax::oliver_pos_tax_count();
    }

    public function oliver_pos_get_tax_settings( $request_data ) {
        $woocommerce_tax_classes = WC_Tax::get_tax_classes();
        return array(
            'pos_prices_include_tax' => get_option('woocommerce_prices_include_tax'),
            'pos_tax_based_on' => get_option('woocommerce_tax_based_on'),
            'pos_shipping_tax_class' => get_option('woocommerce_shipping_tax_class'),
            'pos_tax_round_at_subtotal' => get_option('woocommerce_tax_round_at_subtotal'),
            'pos_additional_tax_classes' => $woocommerce_tax_classes,
            'pos_tax_display_shop' => get_option('woocommerce_tax_display_shop'),
            'pos_tax_display_cart' => get_option('woocommerce_tax_display_cart'),
            'pos_price_display_suffix' => get_option('woocommerce_price_display_suffix'),
            'pos_tax_total_display' => get_option('woocommerce_tax_total_display'),
            'pos_automated_taxes' => get_option('wc_connect_taxes_enabled')
        );
    }

    private function oliver_pos_tax_sync_dotnet( $row_id, $method ) {
        $udid = ASP_DOT_NET_UDID;
        $url = esc_url_raw("{$method}?udid={$udid}&wpid={$row_id}");
        wp_remote_get( $url, array(
            'timeout'   => 0.01,
            'blocking'  => false,
            'sslverify' => false,
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
    }
    /**
     * post tax details.
     * @since 2.3.8.8
     * @param int tax id and post method
     */
    private function oliver_pos_post_tax_data_to_dotnet( $row_id, $post_method ) {
        $tax_data = $this->pos_bridge_tax->oliver_pos_get_tax($row_id);
        wp_remote_post( esc_url_raw( $post_method ), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
				'Authorization' => AUTHORIZATION,
            ),
            'body' => json_encode($tax_data),

        ) );
    }
    /* Woo tax setting post data section */
    //Since 2.3.9.1. Update
    public function oliver_pos_woocommerce_tax_settings_post_listener() {
        oliver_log("=== post woo tax setting ===");

        $woo_tax_settings_data = $this->oliver_pos_get_tax_settings($request_data = NULL);
        wp_remote_post( esc_url_raw(ASP_TRIGGER_TAX_SETTING), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
				'Authorization' => AUTHORIZATION,
            ),
            'body' => json_encode($woo_tax_settings_data),
        ));
    }
}