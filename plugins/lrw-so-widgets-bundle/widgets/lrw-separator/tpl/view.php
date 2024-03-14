<?php
$sep_classes   = array();
$sep_classes[] = 'lrw-sep-content-' . $instance['title_align'];
$sep_classes[] = 'lrw-sep-width-' . $instance['sep_width'];
$sep_classes[] = 'lrw-sep-' . $instance['border_style'];
$sep_classes[] = 'lrw-sep-border-width-' . $instance['border_width'];
$sep_classes[] = 'lrw-sep-align-' . $instance['separator_align'];
$sep_classes[] = ( $instance['icon_active'] == 'yes' && ! empty( $instance['icon_options']['icon'] ) ? 'lrw-sep-has-icon' : '' );
$icon_classes   = array();
$icon_classes[] = ( $instance['icon_options']['shape_format'] ? 'icon-shape-' . $instance['icon_options']['shape_format'] : '' );
$icon_classes[] = ( ! empty( $instance['icon_options']['icon_size'] ) ) ? 'icon-size-' . $instance['icon_options']['icon_size'] : '';
$icon_classes[] = ( ! empty( $instance['icon_options']['shape_format'] ) ) ? 'icon-has-style' : '';
$icon_classes[] = ( $instance['icon_options']['shape_format'] == 'outline-circle' or $instance['icon_options']['shape_format'] == 'outline-square' or $instance['icon_options']['shape_format'] == 'outline-rounded' ) ? 'icon-element-outline' : 'icon-element-background';
?>

<div class="lrw-separator <?php echo esc_attr( implode( ' ', $sep_classes ) ); ?>">
	<span class="lrw-sep-wrap lrw-sep-l"><span></span></span>
	<?php if ( $instance['icon_active'] == 'yes' ) : ?>
		<?php if ( ! empty( $instance['icon_options']['icon'] ) ) : ?>
			<div class="icon-wrapper <?php echo esc_attr( implode( ' ', $icon_classes ) ); ?>">
				<?php echo siteorigin_widget_get_icon( $instance['icon_options']['icon'] ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( $instance['title'] ) echo '<' . $instance['title_type'] . ' class="lrw-sep-text">' . $instance['title'] . '</' . $instance['title_type'] . '>'; ?>
	<span class="lrw-sep-wrap lrw-sep-r"><span></span></span>
</div>