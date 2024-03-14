<?php
/**
 * Animated text list template
 */

$item_text = $this->_loop_item( array( 'item_text' ) );
$classes = [];
$classes[] = 'lakit-animated-text__animated-text-item';
$settings = $this->get_settings_for_display();

if ( 0 == $this->_processed_index ) {
	$classes[] = 'active';
	$classes[] = 'visible';
}

$split_type = ( 'fx12' === $settings['animation_effect'] ) ? 'symbol' : $settings['split_type'];

?>
<span class="<?php echo implode( ' ', $classes ); ?>">
	<?php
		echo $this->str_to_spanned_html( $item_text, $split_type );
	?>
</span>
