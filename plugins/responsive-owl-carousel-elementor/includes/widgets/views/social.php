<?php
/**
 * @var array  $item
 * @var string $field_prefix
 * @var string $social_icon_hover_animation_class
 */
if ( ! empty( $settings) && ! $settings[ $field_prefix . 'social_icon_hide' ] ) :
	?>
	<div class="owl-social-icon">
		<?php
		echo owce_get_social_icons(
			$this,
			$item,
			[ 'class' => $social_icon_hover_animation_class ]
		);
		?>
	</div>
	<?php
endif;
