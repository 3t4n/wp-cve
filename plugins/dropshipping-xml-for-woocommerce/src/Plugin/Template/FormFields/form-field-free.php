<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Forms\Field $field
 * @var \WPDesk\View\Renderer\Renderer $renderer
 * @var string $name_prefix
 * @var string $value
 * @var string $url
 * @var string $template_name Real field template.
 */
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
	<div class="buy-pro-version"><a target="_blank" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( __( 'Upgrade to PRO &rarr;', 'dropshipping-xml-for-woocommerce' ) ); ?>  </a> <?php echo esc_html( __( 'to fully unlock this feature', 'dropshipping-xml-for-woocommerce' ) ); ?></div>	
	</td>
</tr>
<?php
