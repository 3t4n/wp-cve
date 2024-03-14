<?php
/**
 * @var string[] $value .
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
		<style>
			[name="<?php echo esc_attr( $value['id'] ); ?>"] {
				width: 100% !important;
			}
		</style>
		<?php wp_editor( $value['value'], sanitize_title( $value['id'] ), [ 'textarea_name' => $value['id'] ] ); ?>
	</td>
</tr>
