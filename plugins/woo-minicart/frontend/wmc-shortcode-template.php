<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() ) return false;

$wmc_options = get_option( 'wmc_options' );
$minicart_icon = $wmc_options['minicart-icon'];
if( $minicart_icon == 'wmc-icon-1' ) :
	$icon = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-1.png';
elseif( $minicart_icon == 'wmc-icon-2' ) :
	$icon = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-2.png';
elseif( $minicart_icon == 'wmc-icon-3' ) :
	$icon = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-3.png';
elseif( $minicart_icon == 'wmc-icon-4' ) :
	$icon = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-4.png';
elseif( $minicart_icon == 'wmc-icon-5' ) :
	$icon = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/graphics/wmc-icon-5.png';
elseif( $minicart_icon == 'wmc-icon-custom' ) :
	$custom_cart_url = get_option('wmc_pro_options')['custom-cart-icon'];
	$icon = esc_url( $custom_cart_url );
endif;
?>
<div class="wmc-cart-wrapper shortcode-wrapper">
	<a class="wmc-cart">
		<?php //echo $icon; ?>
		<img src="<?php echo esc_url($icon); ?>" alt="Mini Cart" width="50" height="50" >
		<span class="wmc-count"><?php echo is_object( WC()->cart ) ? esc_html(WC()->cart->get_cart_contents_count()) : ''; ?></span>
	</a>
	<?php include 'wmc-content.php'; ?>
</div>