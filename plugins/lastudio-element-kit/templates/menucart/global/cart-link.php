<?php
/**
 * Cart Link
 */
$this->add_render_attribute( 'cart-link', 'href', esc_url( wc_get_cart_url() ) );
$this->add_render_attribute( 'cart-link', 'class', 'lakit-cart__heading-link main-color' );

?>
<a <?php echo $this->get_render_attribute_string( 'cart-link' ); ?>><?php

	$this->_icon( 'cart_icon', '<span class="lakit-cart__icon lakit-blocks-icon">%s</span>' );
	$this->_html( 'cart_label', '<span class="lakit-cart__label">%s</span>' );

	if ( 'yes' === $settings['show_count'] ) {
		?>
		<span class="lakit-cart__count"><?php
			ob_start();
			include $this->_get_global_template( 'cart-count' );
			printf( $settings['count_format'], ob_get_clean() );
		?></span>
		<?php
	}

	if ( 'yes' === $settings['show_total'] ) {
		?>
		<span class="lakit-cart__total"><?php
			ob_start();
			include $this->_get_global_template( 'cart-totals' );
			printf( $settings['total_format'], ob_get_clean() );
		?></span>
		<?php
	}

?></a>