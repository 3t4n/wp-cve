<?php

namespace XCurrency\App\Woocommerce;

defined( 'ABSPATH' ) || exit;

use XCurrency\App\Repositories\CurrencyRepository;

class Coupon {
    const META_KEY = 'x_currency_coupon_amounts';

    public function boot() {
        add_action( 'woocommerce_coupon_options', [$this, 'admin_settings'] );
        add_action( 'woocommerce_coupon_options_save', [$this, 'save_coupon_options'] );
    }

    /**
     * @param $coupon_id
     */
    public function save_coupon_options( $coupon_id ) {
        if ( empty( $_POST['x_currency_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['x_currency_nonce'] ) ), 'x_currency_nonce' ) ) {
            return;
        }
        
        if ( isset( $_POST[self::META_KEY] ) ) {
            $data = serialize( map_deep( wp_unslash( $_POST[self::META_KEY] ), 'sanitize_text_field' ) );
            update_post_meta( $coupon_id, self::META_KEY, $data );
        }
    }

    public function admin_settings( $coupon_id ) {
        global $x_currency;
        ?>
    <div style="background: #e6ecf0; padding-top: 16px; padding-bottom: 5px; padding-left: 4px;">
    <input type="hidden" name="x_currency_nonce" value="<?php echo esc_html( wp_create_nonce( 'x_currency_nonce' ) )?>">
    <h3 style="margin: 0px;margin-left: 10px; color: var(--wp-admin-theme-color); display: flex; align-items:center; font-size:30px;"><i class="xc-icon-logo" style="margin-right: 10px; font-size:30px"></i>X-Currency</h3>
        <?php
        if ( isset( $x_currency['global_settings']['specific_coupon_amount'] ) && $x_currency['global_settings']['specific_coupon_amount'] == 'true' ) :
            ?>
        <h3 style="margin: 5px 10px 20px 10px; font-weight: normal;"><?php echo esc_html__( 'Specific coupon price for each currency.', 'x-currency' )?></h3>
            <?php
            /**
             * @var CurrencyRepository $currency_repository
             */
            $currency_repository = x_currency_singleton( CurrencyRepository::class );
            $currencies          = $currency_repository->get();
            $base_currency_id    = x_currency_base_id();

            $amounts = get_post_meta( $coupon_id, self::META_KEY, true );

            if ( ! $amounts ) {
                $amounts = [];
            } else {
                $amounts = unserialize( $amounts );
            }

            foreach ( $currencies as $currency ) :
                $currency_code = $currency->code;
                $attr          = [
                    'id'        => 'x_currency_coupon_amount_' . strtolower( $currency_code ),
                    'label'     => $currency_code,
                    'name'      => self::META_KEY . '[' . $currency_code . ']',
                    'type'      => 'number',
                    'data_type' => 'price',
                    'value'     => isset( $amounts[$currency_code] ) ? $amounts[$currency_code] : ''
                ];

                if ( $base_currency_id == $currency->id ) {
                    $attr['label']      .= ' (Base Currency)';
                    $attr['style']       = 'pointer-events: none;';
                    $attr['placeholder'] = 'Coupon amount field';
                } else {
                    $attr['description'] = esc_html__( 'If you want to automatically exchange coupon amounts, leave the amount field blank.', 'x-currency' );
                    $attr['desc_tip']    = true;
                    $attr['placeholder'] = 'auto';
                }

                woocommerce_wp_text_input( $attr );
            endforeach;
    else :
        ?>
        <h3 style="margin: 5px 10px; font-weight: normal;"><?php esc_html_e( 'Specific coupon price for each currency.', 'x-currency' )?> <?php echo sprintf( esc_html__( 'To use this feature, %s this option.', 'x-currency' ), '<a href="' . esc_url( admin_url( 'admin.php?page=' . 'x-currency' ) ) . '#/global-settings">' . esc_html__( 'Enable', 'x-currency' ) . '</a>' )?></h3>
        <?php
    endif;
    ?>
    </div>
        <?php
    }
}
