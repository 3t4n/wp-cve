<?php
/**
 * Cart list template
 */

$close_button_html = $this->_get_icon( 'cart_list_close_icon', '<div class="lakit-cart__close-button lakit-blocks-icon">%s</div>' );
?>
<div class="lakit-cart__list">
	<?php echo $close_button_html; ?>
	<?php $this->_html( 'cart_list_label', '<div class="lakit-cart__list-title h4 theme-heading">%s</div>' ); ?>
    <div class="widget_shopping_cart_content"><?php
	    if( !lastudio_kit()->get_theme_support('elementor::cart-fragments') ){
            woocommerce_mini_cart();
        }
    ?></div>
</div>
