<?php
/**
 * Section Heading template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

$halign    = isset( $attributes['halign'] ) ? 'halign' . $attributes['halign'] : '';
$block_tag = isset( $attributes['tag'] ) ? $attributes['tag'] : '';
$content   = isset( $attributes['content'] ) ? $attributes['content'] : '';

$class_name = $attributes['className'];

// If align default.
if ( 'haligndefault' === $halign || 'halignnone' === $halign || ! $halign ) {
	$halign = apply_filters( 'canvas_section_heading_align', 'halignleft' );
}

// If tag default.
if ( 'default' === $block_tag || 'none' === $block_tag || ! $block_tag ) {
	$block_tag = apply_filters( 'canvas_section_heading_tag', 'h2' );
}

// Set align class.
$class_name .= ' ' . $halign;
?>

<<?php echo esc_attr( $block_tag ); ?> class="<?php echo esc_attr( $class_name ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<span class="cnvs-section-title">
		<span><?php echo (string) $content; // XSS. ?></span>
	</span>
</<?php echo esc_attr( $block_tag ); ?>>
