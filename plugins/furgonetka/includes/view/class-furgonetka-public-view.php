<?php

/**
 * The file that defines view for public side
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/view
 */

/**
 *  Class Furgonetka_Public_View - views for Public side
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/view
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Public_View
{
    /**
     * Render map
     *
     * @param mixed $plugin_name    - plugin name.
     * @param mixed $selected_point - selected point.
     *
     * @since 1.0.9
     */
    public function render_map( $plugin_name, $selected_point )
    {
        ?>
        <input
                type="hidden"
                id="furgonetkaPoint"
                name="furgonetkaPoint"
                value="<?php echo esc_html( $selected_point['code'] ); ?>"
        />
        <input
                type="hidden"
                id="furgonetkaPointName"
                name="furgonetkaPointName"
                value="<?php echo esc_html( $selected_point['name'] ); ?>"
        />
        <input
                type="hidden"
                id="furgonetkaService"
                name="furgonetkaService"
                value="<?php echo esc_html( $selected_point['service'] ); ?>"
        />
        <?php wp_nonce_field( $plugin_name . '_setPointAction', $plugin_name . '_setPoint' ); ?>
        <?php
    }

    /**
     * Render package information
     *
     * @param mixed $order_information - order info.
     *
     * @since 1.0.9
     */
    public function render_package_information( $order_information )
    {
        ?>
        <section class="woocommerce-order-details">
            <h2 class="woocommerce-order-details__title">
                <?php esc_attr_e( 'Pickup point', 'furgonetka' ); ?>
            </h2>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                <?php echo esc_html( $order_information ); ?>
            </p>
        </section>
        <?php
    }
}
