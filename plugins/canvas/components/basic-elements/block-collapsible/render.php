<?php
/**
 * Collapsible block template
 *
 * @var     $attributes - block attributes
 * @var     $content - inner blocks
 * @var     $options - layout options
 *
 * @package Canvas
 */

if ( $attributes['opened'] ) {
	$attributes['className'] .= ' cnvs-block-collapsible-opened';
}

?>
<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<div class="cnvs-block-collapsible-title">
		<h6 class="cnvs-block-collapsible-heading">
			<a href="#"><?php echo (string) $attributes['title']; // XSS. ?></a>
		</h6>
	</div>
	<div class="cnvs-block-collapsible-content">
		<?php echo (string) $content; // XSS. ?>
	</div>
</div>
