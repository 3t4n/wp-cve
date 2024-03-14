<?php
/**
 * Widgetized Area block template
 *
 * @var     $attributes - block attributes
 * @var     $options - layout options
 *
 * @package Canvas
 */
?>

<div class="<?php echo esc_attr( $attributes['className'] ); ?>" <?php echo ( isset( $attributes['anchor'] ) ? ' id="' . esc_attr( $attributes['anchor'] ) . '"' : '' ); ?>>
	<?php
	dynamic_sidebar( $attributes['area'] );
	?>
</div>
