<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var string $value
 * @var string $template_name Real field template.
 */

$is_pl = \get_locale() === 'pl_PL';
?>

<tr valign="top">
	<?php
	if ( $field->has_label() ) {
		$renderer->output_render( 'form-label', [ 'field' => $field ] );
	}
	?>

	<td class="forminp">
		<?php
		$renderer->output_render(
			$template_name,
			[
				'field'       => $field,
				'renderer'    => $renderer,
				'name_prefix' => $name_prefix,
				'value'       => $value,
			]
		);
		?>

		<?php
		if ( $field->has_description() ) {
			?>
			<p class="description">
			<?php
			echo \wp_kses_post( $field->get_description() );
			?>
	</p>
				<?php
		}
		?>
	<span class="description" style="font-weight: bold; color: black;">
			<?php
			echo \wp_kses_post( __( 'Read more in the <a href="https://wpde.sk/dropshipping-conditions" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce' ) );
			?>
	</span>
	<?php $url = $is_pl? 'https://www.wpdesk.pl/sklep/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=no-product' : 'https://wpdesk.net/products/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=no-product'; ?>
	<div class="buy-pro-version"><a target="_blank" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( __( 'Upgrade to PRO &rarr;', 'dropshipping-xml-for-woocommerce' ) ); ?>  </a> <?php echo esc_html( __( 'to fully unlock this feature', 'dropshipping-xml-for-woocommerce' ) ); ?></div>	
	</td>
</tr>
<?php
