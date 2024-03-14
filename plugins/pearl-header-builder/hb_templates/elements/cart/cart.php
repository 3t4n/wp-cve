<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$style = (!empty($element['value'])) ? $element['value'] : 'style_1'; ?>

<div class="stm-cart stm-cart_<?php echo esc_attr($style); ?>">
	<?php if( class_exists( 'WooCommerce' )) : ?>
        <div class="cart cart_rounded stm_hb_mbc_h stm_hb_mbdc">
            <a href="<?php echo esc_url(stm_hb_get_cart_url()); ?>"></a>
            <i class="cart__icon fa fa-shopping-cart fa-16px"></i>

            <!--Quantitiy-->
			<?php stm_hb_load_element('cart', array(),'quantity'); ?>

            <!--Mini cart-->
			<?php stm_hb_load_element('cart', array(),'mini-cart'); ?>
        </div>
	<?php endif; ?>
</div>