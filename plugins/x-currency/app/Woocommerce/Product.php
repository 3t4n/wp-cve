<?php

namespace XCurrency\App\Woocommerce;

use XCurrency\App\Repositories\CurrencyRepository;

defined( 'ABSPATH' ) || exit;

class Product {
    public function boot() {
        add_action( 'woocommerce_product_options_pricing', [$this, 'simple_product_fields'] );
        add_action( 'woocommerce_variation_options_pricing', [$this, 'variation_product_fields'], 10, 3 );
        add_action( 'woocommerce_update_product', [$this, 'save_product_settings'] );
    }

    /**
     * @param $loop
     * @param $variation_data
     * @param $variation
     */
    public function variation_product_fields( $loop, $variation_data, $variation ) {
        x_currency_render( '<div style="width: calc(100% - 15px);">' );
        $saved_data = json_decode( get_post_meta( $variation->post_parent, 'x_currency_variation', true ), true );
        $this->product_options_pricing( isset( $saved_data[$variation->ID] ) ? $saved_data[$variation->ID] : [], 'x_currency_variation[' . $variation->ID . ']', 'border-radius:5px;' );
        x_currency_render( '</div>' );
    }

    public function simple_product_fields() {
        global $post;
        $saved_data = json_decode( get_post_meta( $post->ID, 'x_currency_simple', true ), true );
        $this->product_options_pricing( $saved_data );
    }

    /**
     * @param $saved_data
     * @param $name
     * @param $style
     */
    public function product_options_pricing( $saved_data, $name = 'x_currency_simple', $style = '' ) {
        global $x_currency;
        ?>
        <div style="background:#eef2f4;padding-top:18px;padding-bottom:5px;padding-left:15px;overflow-x:auto;overflow-y:hidden;width:100%;<?php x_currency_render( $style ); ?>">
            <input type="hidden" name="x_currency_nonce" value="<?php x_currency_render( wp_create_nonce( 'x_currency_nonce' ) )?>">
            <h3 style="margin: 0px; color: #007cba; display: flex; align-items:center;padding:0 !important"><i class="xc-icon-logo" style="margin-right: 10px; font-size:25px"></i>X-Currency</h3>
        <?php if ( isset( $x_currency['global_settings']['specific_product_price'] ) && $x_currency['global_settings']['specific_product_price'] == 'true' ) :?>
        <h4 style="margin: 5px 10px 0 0; font-weight: normal;"><?php esc_html_e( 'Specific product price for each currency.', 'x-currency' )?></h4>
            <?php
        
            /**
             * @var CurrencyRepository $currency_repository
             */
            $currency_repository = x_currency_singleton( CurrencyRepository::class );
            $currencies          = $currency_repository->get();
            $base_currency_id    = x_currency_base_id();
            $symbols             = get_woocommerce_currency_symbols();

            foreach ( $currencies as $currency ) :
                $id              = $currency->id;
                $currency_code   = $currency->code;
                $l_currency_code = strtolower( $currency_code );
                $label           = $currency_code;
                $style           = '';
            
                $placeholder = [
                    'regular' => 'auto',
                    'sale'    => 'auto',
                ];

                $prices = [
                    'sale'    => isset( $saved_data[$l_currency_code]['sale'] ) ? $saved_data[$l_currency_code]['sale'] : '',
                    'regular' => isset( $saved_data[$l_currency_code]['regular'] ) ? $saved_data[$l_currency_code]['regular'] : '',
                ];

                if ( $base_currency_id == $id ) {
                    $label                 .= ' (Base Currency)';
                    $style                  = 'pointer-events: none;';
                    $placeholder['regular'] = 'default';
                    $placeholder['sale']    = 'default';
                    $prices                 = [
                        'sale'    => '',
                        'regular' => '',
                    ];
                }
            
                ?>
            <div style="width: 100%;float:left;padding:10px 0;">
                <div class="title" style="float:left;width:200px;box-sizing:border-box;padding-top:5px;font-weight:600"><?php echo esc_html( $label );?></div>
                <div style="float:left; display: grid; grid-template-columns: 1fr 1fr; grid-gap: 20px;" class="xc-pricing-input">
                    <div>
                        <span style="float: left;padding-top:5px;width:140px"><?php echo sprintf( esc_html__( 'Regular Price (%s)', 'x-currency' ), esc_html( $symbols[$currency_code] ) )?></span>
                        <input type="text" class="wc_input_decimal xc-pricing-input__regular" name="<?php echo esc_attr( $name )?>[<?php echo esc_attr( $l_currency_code ) ?>][regular]" placeholder="<?php echo esc_attr( $placeholder['regular'] )?>" style="float:left;width:120px;<?php echo $style; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" value="<?php echo esc_attr( $prices['regular'] )?>">
                    </div>
                    <div>
                        <span style="float: left;padding-top:5px;width:140px"><?php echo sprintf( esc_html__( 'Sale Price (%s)', 'x-currency' ), esc_html( $symbols[$currency_code] ) )?></span>
                        <input type="text" class="wc_input_decimal xc-pricing-input__sale" name="<?php echo esc_attr( $name )?>[<?php echo esc_attr( $l_currency_code ) ?>][sale]" placeholder="<?php echo esc_attr( $placeholder['sale'] )?>" style="float:left;width:100px;<?php echo $style; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" value="<?php echo esc_attr( $prices['sale'] ) ?>">
                        <?php if ( $base_currency_id != $id ) :?>
                        <span style="padding-top: 5px;">
                            <?php x_currency_render( wc_help_tip( esc_html__( 'If you want to automatically exchange product price, leave the amount field blank.', 'x-currency' ) ) ) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
                <?php
        endforeach;
        else : ?>
            <h4 style="margin: 5px 10px; font-weight: normal;"><?php esc_html_e( 'Specific product price for each currency.', 'x-currency' )?> <?php echo sprintf( esc_html__( 'To use this feature, %s this option.', 'x-currency' ), '<a href="' . esc_url( admin_url( 'admin.php?page=' . 'x-currency' ) ) . '#/global-settings">' . esc_html__( 'Enable', 'x-currency' ) . '</a>' )?></h4>
        <?php endif;?>
        <script data-cfasync="false" type="text/javascript">
            jQuery(($) => {
                const getData = ( scope ) => {
                    const $target = $(scope);
                    const $parent = $target.parents('.xc-pricing-input');
                    const $saleInput = $parent.find('.xc-pricing-input__sale');
                    const $regularInput = $parent.find('.xc-pricing-input__regular');

                    return Object.freeze({
                        $target,
                        $parent,
                        $saleInput,
                        $regularInput,
                        get type() {
                            return $target.hasClass('xc-pricing-input__sale') ? 'sale' : 'regular';
                        },

                        get regularPrice() {
                            return parseFloat(
                                window.accounting.unformat(
                                    $regularInput.val(),
                                    woocommerce_admin.mon_decimal_point
                                )
                            );
                        },

                        get salePrice() {
                            return parseFloat(
                                window.accounting.unformat(
                                    $saleInput.val(),
                                    woocommerce_admin.mon_decimal_point
                                )
                            );
                        }
                    })
                }

                $( document.body ).on('keyup', '.xc-pricing-input__sale, .xc-pricing-input__regular',
                    function () {                    
                        const { regularPrice, salePrice, $saleInput } = getData(this);
                        if ( salePrice >= regularPrice ) {
                            $( document.body ).triggerHandler( 'wc_add_error_tip', [
                                $saleInput,
                                'i18n_sale_less_than_regular_error',
                            ] );
                        } else {
                            $(
                                document.body
                            ).triggerHandler( 'wc_remove_error_tip', [
                                $saleInput,
                                'i18n_sale_less_than_regular_error',
                            ] );
                        }
                    }
                )
                .on( 'change', '.xc-pricing-input__sale, .xc-pricing-input__regular',
                    function () {
                        const { regularPrice, salePrice, $saleInput } = getData(this);
                        if ( salePrice >= regularPrice ) {
                            $saleInput.val( '' );
                        }
                    }
                )
            })
        </script>
        </div>
        <?php
    }

    /**
     * @param $product_id
     */
    public function save_product_settings( $product_id ) {
        if ( empty( $_POST['x_currency_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['x_currency_nonce'] ) ), 'x_currency_nonce' ) ) {
            return;
        }

        if ( ! isset( $_POST['product-type'] ) ) {
            return;
        }

        if ( 'simple' === $_POST['product-type'] || 'external' === $_POST['product-type'] ) {
            if ( isset( $_POST['x_currency_simple'] ) ) {
                update_post_meta( $product_id, 'x_currency_simple', json_encode( map_deep( wp_unslash( $_POST['x_currency_simple'] ), 'sanitize_text_field' ) ) );
            }
        } elseif ( 'variable' === $_POST['product-type'] ) {
            if ( ! isset( $_POST['x_currency_variation'] ) || ! is_array( $_POST['x_currency_variation'] ) ) {
                return;
            }

            $variations = map_deep( wp_unslash( $_POST['x_currency_variation'] ), 'sanitize_text_field' );

            //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
            if ( 'woocommerce_save_variations' === $_REQUEST['action'] ) {
                $db_variations = get_post_meta( $product_id, 'x_currency_variation', true );

                if ( $db_variations ) {
                    $db_variations = json_decode( $db_variations, true );
                } else {
                    $db_variations = [];
                }

                foreach ( $db_variations as $id => $variation ) {
                    if ( ! isset( $variations[$id] ) ) {
                        $variations[$id] = $variation;
                    }
                }
            }

            update_post_meta( $product_id, 'x_currency_variation', json_encode( $variations ) );
        }
    }
}
