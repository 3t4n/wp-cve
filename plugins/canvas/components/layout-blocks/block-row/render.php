<?php
/**
 * Row block template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

$attributes['className'] .= ' cnvs-block-row-columns-' . $attributes['columns'];

if ( isset( $attributes['verticalAlignment'] ) && $attributes['verticalAlignment'] ) {
	$attributes['className'] .= ' cnvs-block-row-valign-' . $attributes['verticalAlignment'];
}

?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<div class="cnvs-block-row-inner">
		<?php echo $content; // XSS. ?>
	</div>
</div>
