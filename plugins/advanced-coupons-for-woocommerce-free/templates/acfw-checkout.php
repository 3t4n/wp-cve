<?php
/**
 * Store credits checkout balance row.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/acfw-checkout.php.
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

<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( implode( ' ', $classnames ) ); ?>">
    <div class="acfw-accordions">

        <?php foreach ( $accordions as $accordion ) : ?>
            <div class="acfw-accordion <?php echo esc_attr( $accordion['classname'] ?? '' ); ?>">
                <h3>
                    <span class="acfw-accordion-title"><?php echo esc_html( $accordion['title'] ?? '' ); ?></span>
                    <span class="caret"><img src="<?php echo esc_url( $caret_img_src ); ?>" /></span>
                </h3>
                <div class="acfw-accordion-inner">
                    <div class="acfw-accordion-content">
                        <?php do_action( 'acfw_checkout_accordion_content', $accordion ); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
