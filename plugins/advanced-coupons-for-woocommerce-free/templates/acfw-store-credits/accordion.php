<?php
/**
 * Store credits checkout balance row.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/acfw-store-credits/accordion.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package ACFWF\Templates
 * @version 4.5.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}?>

<p class="acfw-store-credit-user-balance">
<?php
    echo wp_kses_post(
        sprintf(
            /* Translators: %s User store credit balance */
            $labels['balance_text'],
            '<strong>' . wc_price( $user_balance ) . '</strong>'
        )
    );
?>
</p>

<p class="acfw-store-credit-instructions">
    <?php echo wp_kses_post( $labels['instructions'] ); ?>
</p>

<?php
    woocommerce_form_field(
        'acfw_redeem_store_credit',
        array(
            'id'          => 'acfw_redeem_store_credit',
            'type'        => 'acfw_redeem_store_credit',
            'value'       => '',
            'label'       => '',
            'placeholder' => $labels['placeholder'],
        )
    );
