<?php
/**
 * Progress block template
 *
 * @var     $attributes - block attributes
 * @var     $options - layout options
 *
 * @package Canvas
 */

if ( $attributes['striped'] ) {
	$attributes['className'] .= ' cnvs-block-progress-striped';

	if ( isset( $attributes['animated'] ) && $attributes['animated'] ) {
		$attributes['className'] .= ' cnvs-block-progress-animated';
	}
}

?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>

	<div class="cnvs-block-progress-bar" role="progressbar" aria-valuenow="<?php echo esc_attr( $attributes['width'] ); ?>" aria-valuemin="0" aria-valuemax="<?php echo esc_attr( $attributes['width'] ); ?>">
		<?php
		if ( $attributes['displayPercent'] ) {
			echo esc_html( $attributes['width'] . '%' );
		}
		?>
	</div>
</div>
