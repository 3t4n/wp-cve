<?php
/**
 * Tabs for page options
 *
 * @package NovaPosta\Templates\Admin
 *
 * @var array  $tabs       List of available tabs.
 * @var string $url        Admin page url without any additional GET arguments.
 * @var string $active_tab Slug of active tab.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>

<div class="shipping-nova-poshta-for-woocommerce-nav-tab-wrapper">
	<?php
	foreach ( $tabs as $slug => $label ) {
		$tab_url = add_query_arg( 'tab', $slug, $url );
		?>
		<a
			href="<?php echo esc_url( $tab_url ); ?>"
			class="shipping-nova-poshta-for-woocommerce-tab<?php echo $active_tab === $slug ? ' shipping-nova-poshta-for-woocommerce-tab--active' : ''; ?>"
		>
			<?php
			echo wp_kses(
				$label,
				[
					'span' => [
						'class' => true,
					],
				]
			);
			?>
		</a>
	<?php } ?>
</div>
