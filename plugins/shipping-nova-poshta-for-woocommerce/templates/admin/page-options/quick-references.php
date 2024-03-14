<?php
/**
 * Quick references
 *
 * @package NovaPosta\Templates\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$is_pro   = nova_poshta()->is_pro();
$features = [
	[
		'title'       => esc_html__( 'Delivery by Nova Poshta', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => sprintf(
			'%s <a href="https://wp-unit.com/yak-dodaty-novyj-metod-dostavky/">%s</a>',
			esc_html__( 'Delivery by Nova Poshta with the smart choice of the city and of the warehouse. These fields have quick and convenient search.', 'shipping-nova-poshta-for-woocommerce' ),
			esc_html__( 'How to add a new shipping method?', 'shipping-nova-poshta-for-woocommerce' )
		),
		'url'         => get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping' ),
		'button'      => esc_html__( 'Add the shipping method', 'shipping-nova-poshta-for-woocommerce' ),
	],
	[
		'title'       => esc_html__( 'Courier delivery by Nova Poshta', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => sprintf(
			'%s <a href="https://wp-unit.com/yak-dodaty-novyj-metod-dostavky/">%s</a>',
			esc_html__( 'Allow your customers to deliver your products right to their door. Your customer needs to choose a city and fill in the address. Also, you can gift customers free delivery depend on their total price in the cart.', 'shipping-nova-poshta-for-woocommerce' ),
			esc_html__( 'How to add a new shipping method?', 'shipping-nova-poshta-for-woocommerce' )
		),
		'url'         => get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping' ),
		'button'      => esc_html__( 'Add the shipping method', 'shipping-nova-poshta-for-woocommerce' ),
		'pro'         => true,
	],
	[
		'title'       => esc_html__( 'Cash on delivery payment', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => esc_html__( 'Your customers can pay upon receipt of the goods. You can also specify the amount of the prepayment using formulas for calculation.', 'shipping-nova-poshta-for-woocommerce' ),
		'url'         => get_admin_url( null, 'admin.php?page=wc-settings&tab=payment' ),
		'button'      => esc_html__( 'Add the COD delivery ', 'shipping-nova-poshta-for-woocommerce' ),
		'pro'         => true,
	],
	[
		'title'       => esc_html__( 'Shipping cost', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => esc_html__( 'We spent a lot of time calculating the cost of the parcels. Filling in the dimensions and weight for all goods is inhuman labor, and that is why we have made formulas that describe the calculation of the cost. If you have the same type of products, you can fill in only 4 formulas, and that\'s it. And if you have many different products, you can fill in the same formulas for categories or products.', 'shipping-nova-poshta-for-woocommerce' ),
		'url'         => get_admin_url( null, 'admin.php?page=shipping-nova-poshta-for-woocommerce&tab=shipping-cost' ),
		'button'      => esc_html__( 'Proceed to setup', 'shipping-nova-poshta-for-woocommerce' ),
		'pro'         => true,
	],
	[
		'title'       => esc_html__( 'Free delivery', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => esc_html__( 'You can gift your customers free delivery if they have bought more than some amount using our smart shipping methods.', 'shipping-nova-poshta-for-woocommerce' ),
		'url'         => get_admin_url( null, 'admin.php?page=wc-settings&tab=shipping' ),
		'button'      => esc_html__( 'Proceed to setup', 'shipping-nova-poshta-for-woocommerce' ),
		'pro'         => true,
	],
	[
		'title'       => esc_html__( 'Manage orders', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => esc_html__( 'We\'ve added a page where you can bulk manage orders that use our plugin. Create all day invoices in just a few clicks.', 'shipping-nova-poshta-for-woocommerce' ),
		'url'         => get_admin_url( null, 'admin.php?page=shipping-nova-poshta-for-woocommerce-manage-orders' ),
		'button'      => esc_html__( 'Visit page', 'shipping-nova-poshta-for-woocommerce' ),
		'pro'         => true,
	],
	[
		'title'       => esc_html__( 'Support', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => esc_html__( 'Do you have any questions or found any bugs or any ideas for new features? We will be happy to answer you. Let\'s make this product better together.', 'shipping-nova-poshta-for-woocommerce' ),
		'url'         => 'https://wordpress.org/support/plugin/shipping-nova-poshta-for-woocommerce/#new-topic-0',
		'button'      => esc_html__( 'Create a ticket', 'shipping-nova-poshta-for-woocommerce' ),
	],
	[
		'title'       => esc_html__( 'Rate our plugin', 'shipping-nova-poshta-for-woocommerce' ),
		'description' => esc_html__( 'Do you like our product? Then give us 5 stars, we will be very grateful for that. If you think that we don\'t deserve 5 stars, we will gladly accept your rating and work on it.', 'shipping-nova-poshta-for-woocommerce' ),
		'url'         => 'https://wordpress.org/support/plugin/shipping-nova-poshta-for-woocommerce/reviews/#new-post',
		'button'      => esc_html__( 'Rate the plugin', 'shipping-nova-poshta-for-woocommerce' ),
	],
];
?>

	<h2><?php esc_html_e( 'Quick references', 'shipping-nova-poshta-for-woocommerce' ); ?></h2>
<?php foreach ( $features as $feature ) { ?>
	<div>
		<h3>
			<?php
			echo wp_kses(
				$feature['title'],
				[
					'a' => [
						'target' => true,
						'href'   => true,
					],
				]
			);
			if ( ! $is_pro && ! empty( $feature['pro'] ) ) {
				?>
				<span class="shipping-nova-poshta-for-woocommerce-pro"></span>
			<?php } ?>
		</h3>
		<p>
			<?php
			echo wp_kses(
				$feature['description'],
				[
					'a' => [
						'target' => true,
						'href'   => true,
					],
				]
			);
			?>
		</p>
		<a
			href="<?php echo esc_url( $feature['url'] ); ?>"
			class="button <?php echo ! $is_pro && ! empty( $feature['pro'] ) ? 'button-primary' : 'button-default'; ?>"
		>
			<?php
			echo ! $is_pro && ! empty( $feature['pro'] ) ?
				esc_html__( 'Upgrade', 'shipping-nova-poshta-for-woocommerce-pro' ) :
				esc_html( $feature['button'] );
			?>
		</a>
	</div>
	<?php
}
