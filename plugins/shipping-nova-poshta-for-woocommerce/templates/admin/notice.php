<?php
/**
 * Notice view
 *
 * @package NovaPosta\Templates\Admin
 * @var $type
 * @var $message
 * @var $btn_label
 * @var $btn_url
 */

if ( $btn_label && $btn_url ) {
	$message =
		'<p>' .
		wp_kses(
			$message,
			[
				'a'      => [
					'href'   => true,
					'class'  => true,
					'target' => true,
				],
				'strong' => true,
				'span'   => [
					'class' => true,
				],
			]
		) .
		sprintf(
			'<a href="%s" target="_blank" class="button button-primary">%s</a>',
			esc_url( $btn_url ),
			esc_html( $btn_label )
		)
		. '</p>';
} else {
	$message = sprintf(
		'<p>%s</p>',
		wp_kses(
			$message,
			[
				'a'      => [
					'href'   => true,
					'class'  => true,
					'target' => true,
				],
				'strong' => true,
				'span'   => [
					'class' => true,
				],
			]
		)
	);
}
?>
<div class="shipping-nova-poshta-for-woocommerce-notice notice notice-<?php echo esc_attr( $type ); ?> is-dismissible">
	<?php
	echo wp_kses(
		$message,
		[
			'p'      => [],
			'a'      => [
				'href'   => true,
				'class'  => true,
				'target' => true,
			],
			'strong' => [],
			'span'   => [
				'class' => true,
			],
		]
	);
	?>
</div>
