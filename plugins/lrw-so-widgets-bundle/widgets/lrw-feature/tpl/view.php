<?php
$wrapper_classes[] = ( ! empty( $icon_position ) ) ? 'icon-position-' . $icon_position : '';
$wrapper_classes[] = ( $icon_position == 'left' or $icon_position == 'right' ) ? 'padding-size-' . $icon_size : '';
$wrapper_classes[] = ( $shape_format !== 'none' ) ? 'has-shape' : '';

// Class icon wrapper
$icon_wrapper_classes   = array();
$icon_wrapper_classes[] = 'element-' . ( ! empty( $image ) ? 'shape_image' : 'shape_icon' );

// Define icon type
$icon_classes   = array();
$icon_classes[] = ( $shape_format ? 'icon-shape-' . $shape_format : '' );
$icon_classes[] = ( ! empty( $icon_size )  && empty( $image ) ) ? 'icon-size-' . $icon_size : '';
$icon_classes[] = ( $shape_format == 'outline-circle' or $shape_format == 'outline-square' or $shape_format == 'outline-rounded' ) ? 'icon-element-outline' : 'icon-element-background';
$icon_classes[] = ( $shape_format == 'none' ) ? 'no-bg' : '';
$icon_classes[] = ( $icon_type == 'type_image' && $image_overflow == false ? 'overflow-hidden' : '' );
?>
<div class="lrw-feature <?php echo esc_attr( implode( ' ', array_filter( $wrapper_classes ) ) ); ?>">
	<div class="lrw-icon-element <?php echo esc_attr( implode( ' ', array_filter( $icon_wrapper_classes ) ) ); ?>">
		<?php if ( $icon_type == 'type_icon' ) : ?>
			<div class="icon-inner <?php echo esc_attr( implode( ' ', array_filter( $icon_classes ) ) ); ?>">
				<?php echo siteorigin_widget_get_icon( $icon ); ?>
			</div>
		<?php elseif ( $icon_type == 'type_image' ): ?>
			<div class="image-wrapper <?php echo esc_attr( implode( ' ', array_filter( $icon_classes ) ) ); ?>">
				<?php $src = wp_get_attachment_image( $image, $image_size ); ?>
				<?php echo $src; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="feature-content">
		<?php echo '<' . $heading_type . ' class="feature-heading heading-align">' . wp_kses_post( $heading_text ) . '</' . $heading_type . '>'; ?>
		<div class="feature-text text-align">
			<?php echo wp_kses_post( $text ); ?>
		</div>
	</div>
</div>
