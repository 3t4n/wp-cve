<?php
/**
 * Collapsibles block template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

$attributes['className'] .= ' cnvs-block-collapsibles-' . $attributes['count'];

?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<?php echo (string) $content; // XSS. ?>
</div>
