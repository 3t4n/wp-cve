<?php

/**
 * Class Furgonetka_Public_View - views for Public side
 */
class Furgonetka_Public_View
{
    /**
     * Render map
     *
     * @param $plugin_name
     * @param $selectedPoint
     *
     * @since 1.0.9
     */
    public function render_map( $plugin_name, $selectedPoint )
    {
        ?>
        <input
                type="hidden"
                id="furgonetkaPoint"
                name="furgonetkaPoint"
                value="<?php echo esc_html( $selectedPoint['code'] ); ?>"
        />
        <input
                type="hidden"
                id="furgonetkaPointName"
                name="furgonetkaPointName"
                value="<?php echo esc_html( $selectedPoint['name'] ); ?>"
        />
        <input type="hidden" id="furgonetkaService" name="furgonetkaService"
               value="<?php echo esc_html( $selectedPoint['service'] ); ?>"/>
        <?php wp_nonce_field( $plugin_name . '_setPointAction', $plugin_name . '_setPoint' ); ?>
        <?php
    }

    /**
     * Render package information
     *
     * @param $order_informatio
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
