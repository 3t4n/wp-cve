<?php
$photo  		= $instance['photo_settings']['photo'];
$photo_size		= $instance['photo_settings']['photo_size'];
$photo_fallback	= $instance['photo_settings']['photo_fallback'];
$photo_shape	= $instance['photo_settings']['photo_shape'];
$name  			= $instance['name_settings']['name'];
$name_type		= $instance['name_settings']['name_type'];
$role  			= $instance['role_settings']['role'];
$role_type		= $instance['role_settings']['role_type'];
$resume			= $instance['resume_settings']['resume'];
$profiles		= $instance['social']['profiles'];
$align			= $instance['member_align'];
$src 			= siteorigin_widgets_get_attachment_image_src( $photo, $photo_size, ! empty( $photo_fallback ) ? $photo_fallback : false );

echo '<div class="lrw-member person-align-' . $align . '">';

		// Define shape type
		$image_shape_type   = array();
		$image_shape_type[] = ( $photo_shape !== 'none' ? 'image-shape-' . $photo_shape : '' );

		echo '<figure class="image-wrapper"><img src="' . $src[0] . '"' . ( ! empty( $src[1] ) ? ' width="' . $src[1] . '"' : '' ) . ( ! empty( $src[2] ) ? ' height="' . $src[2] . '"' : '' ) . 'class="' . esc_attr( implode( ' ', $image_shape_type ) ) . '" title="' . $name . '"></figure>';
		echo '<' . $name_type . ' class="member-name">' . wp_kses_post( $name ) . '</' . $name_type . '>';
		echo '<' . $role_type . ' class="member-role">' . wp_kses_post( $role ) . '</' . $role_type . '>';

		echo '<ul class="person-social-profiles">';

		foreach ( $profiles as $i => $profile ) :

			$title		 = $profile['title'];
			$icon_shape  = $profile['icon_shape'];
			$shape_color = $profile['shape_color'];
			$icon 		 = $profile['icon'];
			$icon_size 	 = $profile['icon_size'];
			$icon_color	 = $profile['icon_color'];
			$url 		 = $profile['url'];
			$new_window	 = $profile['new_window'];

			// Class icon wrapper
			$class_icon_wrapper   = array();
			$class_icon_wrapper[] = 'element-' . ( ! empty( $image ) ? 'shape_image' : 'shape_icon' );

			// Define icon type
			$icon_shape_type   = array();
			$icon_shape_type[] = ( $icon_shape ? 'icon-shape-' . $icon_shape : '' );
			$icon_shape_type[] = ( ! empty( $icon_size ) ) ? 'icon-size-' . $icon_size : '';
			$icon_shape_type[] = ( $icon_shape == 'outline-circle' or $icon_shape == 'outline-square' or $icon_shape == 'outline-rounded' ) ? 'icon-element-outline' : 'icon-element-background';

			// Shape styles
			$shape_styles = array();
			if ( $icon_shape == 'outline-circle' or $icon_shape == 'outline-square' or $icon_shape == 'outline-rounded' && ! empty( $shape_color ) && $icon_shape != 'outline-none' ) {
				$shape_styles[] = 'border-color: ' . $shape_color;
			} elseif ( ! empty( $shape_color ) && $icon_shape != 'none' ) {
				$shape_styles[] = 'background-color: ' . $shape_color;
			} else {
				$shape_styles = '';
			}

			// Icon styles
			$icon_styles = array();
			$icon_styles[] = ( ! empty( $icon_color ) ) ? 'color: ' . $icon_color : '';

			$url_target = ( $new_window ? 'target="_blank"' : '' );

		?>
			<li>
				<div class="lrw-icon-element <?php echo esc_attr( implode( ' ', $class_icon_wrapper ) ); ?>">
					<div class="icon-inner <?php echo esc_attr( implode( ' ', $icon_shape_type ) ); ?>" <?php if ( ! empty( $shape_styles ) ) echo 'style="' . esc_attr( implode( '; ', $shape_styles ) ) . '"'; ?>>
						<?php echo siteorigin_widget_get_icon( $icon, $icon_styles ); ?>
						<?php  if ( ! empty( $url ) ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" <?php echo $url_target; ?>></a>
						<?php endif; ?>
					</div>
				</div>
			</li>

		<?php endforeach; ?>
	</ul>
	<div class="member-resume">
		<?php echo wp_kses_post( $resume ); ?>
	</div>
</div><!-- .lrw-member -->
