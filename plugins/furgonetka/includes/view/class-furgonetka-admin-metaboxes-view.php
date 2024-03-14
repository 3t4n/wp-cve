<?php

/**
 * The file that defines view for metaboxes in cms
 *
 * @link  https://furgonetka.pl
 * @since 1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/view
 */

/**
 *  Class Furgonetka_Admin_Metaboxes_View- views for Orders page
 *
 * @since      1.0.0
 * @package    Furgonetka
 * @subpackage Furgonetka/includes/rest_api/endpoint_controller/view
 * @author     Furgonetka.pl <woocommerce@furgonetka.pl>
 */
class Furgonetka_Admin_Metaboxes_View
{
    /**
     * Render package link
     *
     * @param  string $link    - link.
     * @param  string $img_src - image url.
     * @return void
     */
    public function render_package_link( $link, $img_src, $order_id = null )
    {
        ?>
        <a class="get-furgonetka" href="<?php echo esc_html( $link ); ?>">
            <img height="16px" src="<?php echo esc_html( $img_src ); ?>" alt="<?php esc_attr_e( 'Furgonetka.pl courier - Package', 'furgonetka' ); ?>"> <?php esc_html_e( 'Package', 'furgonetka' ); ?>
        </a>
        <hr/>
        <a class="get-furgonetka" href="#" onclick="window.FurgonetkaAdmin.quickAction.apply(this, arguments);" data-order-id="<?php echo $order_id ; ?>">
            <img height="16px" src="<?php echo esc_html( $img_src ); ?>" alt="<?php esc_attr_e( 'Furgonetka.pl courier - Quick action', 'furgonetka' ); ?>"> <?php esc_html_e( 'Quick action', 'furgonetka' ); ?>
        </a>
        <?php
    }

    /**
     * Render package numbers
     *
     * @param  mixed $package_numbers - bool|array package numbers.
     * @return void
     */
    public function render_packages_tracking_info( $package_numbers )
    {
        ?>
        <?php if ( ! empty( $package_numbers ) ) : ?>
            <div style="margin: 15px 0">
                <div>
                    <strong>
                        <?php esc_attr_e( 'Packages tracking numbers:', 'furgonetka' ); ?>
                    </strong>
                </div>

                <?php foreach ( $package_numbers as $package_number => $package_data ) : ?>
                    <?php if ( Furgonetka_Admin::get_test_mode() ) : ?>
                        <a
                                style="text-decoration: underline;"
                                target="_blank"
                                href="https://sandbox.furgonetka.pl/zlokalizuj/<?php echo esc_html( $package_number ); ?>"
                        >
                            <?php echo esc_html( $package_number ); ?>
                        </a>
                        <br>
                    <?php else : ?>
                        <a
                                style="text-decoration: underline;"
                                target="_blank"
                                href="https://furgonetka.pl/zlokalizuj/<?php echo esc_html( $package_number ); ?>"
                        >
                            <?php echo esc_html( $package_number ); ?>
                        </a>
                        <br>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php
    }
}
