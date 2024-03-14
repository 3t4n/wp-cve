<?php
/**
 * Alert block template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

if ( isset( $attributes['dismissible'] ) && $attributes['dismissible'] ) {
	$attributes['className'] .= ' cnvs-block-alert-dismissible';
}

?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<div class="cnvs-block-alert-inner">
		<?php echo (string) $content; // XSS. ?>
	</div>
	<?php if ( isset( $attributes['dismissible'] ) && $attributes['dismissible'] ) : ?>
		<button class="cnvs-close" type="button" data-dismiss="alert" aria-label="<?php echo esc_attr__( 'Close', 'canvas' ); ?>">
			<i class="cnvs-icon-x"></i>
		</button>
	<?php endif; ?>
</div>
