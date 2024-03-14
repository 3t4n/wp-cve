<?php
/**
 * @var string[] $value             .
 * @var string[] $custom_attributes .
 */

defined( 'ABSPATH' ) || exit;

?>

<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="<?php echo esc_attr( $value['id'] ); ?>">
			<?php echo esc_html( $value['title'] ); ?>

			<?php if ( isset( $value['desc_tip'] ) && ! empty( $value['desc_tip'] ) ) : ?>
				<?php echo wc_help_tip( $value['desc_tip'] );// WPCS: XSS ok. ?>
			<?php endif; ?>
		</label>
	</th>
	<td class="forminp">
		<textarea
			name="<?php echo esc_attr( $value['id'] ); ?>"
			id="<?php echo esc_attr( $value['id'] ); ?>"
			style="<?php echo esc_attr( $value['css'] ); ?>"
			class="<?php echo esc_attr( $value['class'] ); ?>"
			placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
			<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
			><?php echo esc_textarea( $value['value'] ); // WPCS: XSS ok. ?></textarea>

		<p class="description"><?php echo wp_kses_post( $value['desc'] ); ?></p>
	</td>
</tr>
