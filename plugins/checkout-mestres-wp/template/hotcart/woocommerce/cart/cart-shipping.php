<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>
		<?php if ( $available_methods ) : ?>

        <?php
        if ( 1 === count( $available_methods ) ) :

            $method = current( $available_methods );

            //echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) );
            ?>
            <input type="hidden" name="shipping_method[<?php echo esc_attr( $index ); ?>]"
                   data-index="<?php echo esc_attr( $index ); ?>"
                   id="shipping_method_<?php echo esc_attr( $index ); ?>"
                   value="<?php echo esc_attr( $method->id ); ?>" class="shipping_method"/>
			<div id="<?php echo $method->id; ?>" class="cwmp_method_shipping" <?php selected( $method->id, $chosen_method ); ?>>
				<div class="">
					<h4><?php echo $method->label; ?></h4>
					<?php
					if(isset($method->get_meta_data()['prazo'])){
						if(get_option('cwmo_day_aditional_correios')){
							$days = $method->meta_data['prazo']+get_option('cwmo_day_aditional_correios');
						}else{
							$days = $method->meta_data['prazo'];
						}
					}
					if(!empty($days)){
						echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
					}
					?>
				</div>
				<div class="">
					<span><?php if($method->cost=="0.00"){ echo "Grátis"; }else{ echo wc_price($method->cost); } ?></span>
					<div class="active">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.6 14.6L15.65 7.55L14.25 6.15L8.6 11.8L5.75 8.95L4.35 10.35L8.6 14.6ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6867 3.825 17.9743 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.263333 12.6833 0.000666667 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31333 4.88333 2.02567 3.825 2.925 2.925C3.825 2.025 4.88333 1.31267 6.1 0.788C7.31667 0.263333 8.61667 0.000666667 10 0C11.3833 0 12.6833 0.262667 13.9 0.788C15.1167 1.31333 16.175 2.02567 17.075 2.925C17.975 3.825 18.6877 4.88333 19.213 6.1C19.7383 7.31667 20.0007 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6867 15.1167 17.9743 16.175 17.075 17.075C16.175 17.975 15.1167 18.6877 13.9 19.213C12.6833 19.7383 11.3833 20.0007 10 20Z" fill="#43D19E"/>
						</svg>
					</div>
					<div class="no-active">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.6 14.6L15.65 7.55L14.25 6.15L8.6 11.8L5.75 8.95L4.35 10.35L8.6 14.6ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6867 3.825 17.9743 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.263333 12.6833 0.000666667 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31333 4.88333 2.02567 3.825 2.925 2.925C3.825 2.025 4.88333 1.31267 6.1 0.788C7.31667 0.263333 8.61667 0.000666667 10 0C11.3833 0 12.6833 0.262667 13.9 0.788C15.1167 1.31333 16.175 2.02567 17.075 2.925C17.975 3.825 18.6877 4.88333 19.213 6.1C19.7383 7.31667 20.0007 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6867 15.1167 17.9743 16.175 17.075 17.075C16.175 17.975 15.1167 18.6877 13.9 19.213C12.6833 19.7383 11.3833 20.0007 10 20Z" fill="#C0C0C0"/>
						</svg>
					</div>
				</div>
			</div>			
				   
        <?php elseif ( get_option( 'woocommerce_shipping_method_format' ) === 'select' ) : ?>

            <select name="shipping_method[<?php echo esc_attr( $index ); ?>]"
                    data-index="<?php echo esc_attr( $index ); ?>"
                    id="shipping_method_<?php echo esc_attr( $index ); ?>" class="shipping_method dswsdm-shipping">
					<option value="" ></option>
                <?php foreach ( $available_methods as $method ) : ?>
                    <option value="<?php echo esc_attr( $method->id ); ?>" <?php selected( $method->id, $chosen_method ); ?>><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?></option>
                <?php endforeach; ?>
            </select>
            <?php foreach ( $available_methods as $method ) : ?>
                <div><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?></div>
            <?php endforeach; ?>
        <?php else : ?>
			 <select name="shipping_method[<?php echo esc_attr( $index ); ?>]"
                    data-index="<?php echo esc_attr( $index ); ?>"
                    id="shipping_method_<?php echo esc_attr( $index ); ?>" class="shipping_method dswsdm-shipping hide">
					<option value="" ></option>
                <?php foreach ( $available_methods as $method ) : ?>
                    <option value="<?php echo esc_attr( $method->id ); ?>" <?php selected( $method->id, $chosen_method ); ?>><?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?></option>
                <?php endforeach; ?>
            </select>
			<?php foreach ( $available_methods as $method ) :  ?>
            <div id="<?php echo $method->id; ?>" class="cwmp_method_shipping" <?php selected( $method->id, $chosen_method ); ?>>
				<div class="">
					<h4><?php echo $method->label; ?></h4>
					<?php
					if(isset($method->get_meta_data()['prazo'])){
						if(get_option('cwmo_day_aditional_correios')){
							$days = $method->meta_data['prazo']+get_option('cwmo_day_aditional_correios');
						}else{
							$days = $method->meta_data['prazo'];
						}
					}
					if(!empty($days)){
						echo __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$days.' days')),get_option('cwmo_format_correios'))."</p>");
					}
					?>
				</div>
				<div class="">
					<span><?php if($method->cost=="0.00"){ echo "Grátis"; }else{ echo wc_price($method->cost); } ?></span>
					<div class="active">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.6 14.6L15.65 7.55L14.25 6.15L8.6 11.8L5.75 8.95L4.35 10.35L8.6 14.6ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6867 3.825 17.9743 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.263333 12.6833 0.000666667 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31333 4.88333 2.02567 3.825 2.925 2.925C3.825 2.025 4.88333 1.31267 6.1 0.788C7.31667 0.263333 8.61667 0.000666667 10 0C11.3833 0 12.6833 0.262667 13.9 0.788C15.1167 1.31333 16.175 2.02567 17.075 2.925C17.975 3.825 18.6877 4.88333 19.213 6.1C19.7383 7.31667 20.0007 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6867 15.1167 17.9743 16.175 17.075 17.075C16.175 17.975 15.1167 18.6877 13.9 19.213C12.6833 19.7383 11.3833 20.0007 10 20Z" fill="#43D19E"/>
						</svg>
					</div>
					<div class="no-active">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M8.6 14.6L15.65 7.55L14.25 6.15L8.6 11.8L5.75 8.95L4.35 10.35L8.6 14.6ZM10 20C8.61667 20 7.31667 19.7373 6.1 19.212C4.88333 18.6867 3.825 17.9743 2.925 17.075C2.025 16.175 1.31267 15.1167 0.788 13.9C0.263333 12.6833 0.000666667 11.3833 0 10C0 8.61667 0.262667 7.31667 0.788 6.1C1.31333 4.88333 2.02567 3.825 2.925 2.925C3.825 2.025 4.88333 1.31267 6.1 0.788C7.31667 0.263333 8.61667 0.000666667 10 0C11.3833 0 12.6833 0.262667 13.9 0.788C15.1167 1.31333 16.175 2.02567 17.075 2.925C17.975 3.825 18.6877 4.88333 19.213 6.1C19.7383 7.31667 20.0007 8.61667 20 10C20 11.3833 19.7373 12.6833 19.212 13.9C18.6867 15.1167 17.9743 16.175 17.075 17.075C16.175 17.975 15.1167 18.6877 13.9 19.213C12.6833 19.7383 11.3833 20.0007 10 20Z" fill="#C0C0C0"/>
						</svg>
					</div>
				</div>
			</div>
			
            <?php endforeach; ?>
        <?php endif; ?>

            <?php if ( is_cart() ) : ?>
				<p class="woocommerce-shipping-destination">
					<?php
					if ( $formatted_destination ) {
						// Translators: $s shipping destination.
						printf( esc_html__( 'Shipping to %s.', 'woo-shipping-display-mode' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' );
						$calculator_text = esc_html__( 'Change address', 'woo-shipping-display-mode' );
					} else {
						echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woo-shipping-display-mode' ) ) );
					}
					?>
				</p>
			<?php endif; ?>
			<?php
		elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
			echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woo-shipping-display-mode' ) ) );
		elseif ( ! is_cart() ) :
			echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woo-shipping-display-mode' ) ) );
		else :
			// Translators: $s shipping destination.
			echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woo-shipping-display-mode' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
			$calculator_text = esc_html__( 'Enter a different address', 'woo-shipping-display-mode' );
		endif;
		?>

		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ( $show_shipping_calculator ) : ?>
			<?php woocommerce_shipping_calculator( $calculator_text ); ?>
		<?php endif; ?>
