<?php
/**
 * @var array  $item
 * @var string $field_prefix
 */
if ( ! $settings[ $field_prefix . 'rating_icon_hide' ] ) { ?>
	<div class="owl-rating-icon">
		<?php echo owce_get_rendered_icons( $settings[ $field_prefix . 'rating_icon' ], $item['item_rating']['size'] ); ?>
	</div>
	<?php
}
