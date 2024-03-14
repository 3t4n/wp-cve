<div class='ewd-uwcf-ratings-slider-title'>
	<?php echo esc_html( $this->get_label( 'label-rating' ) ); ?>
</div>

<div id='ewd-uwcf-ratings-slider' data-min_rating='<?php echo ( isset( $_GET['min_rating'] ) ? esc_attr( intval( $_GET['min_rating'] ) ) : 1 ); ?>' data-max_rating='<?php echo ( isset($_GET['max_rating'] ) ? esc_attr( intval( $_GET['max_rating'] ) ) : 5 ); ?>'></div>
<div class='ewd-uwcf-ratings-slider-min'><?php echo ( isset( $_GET['min_rating'] ) ? esc_attr( intval( $_GET['min_rating'] ) ) : 1 ); ?></div>
<div class='ewd-uwcf-ratings-slider-max'><?php echo ( isset($_GET['max_rating']) ? esc_attr( intval( $_GET['max_rating'] ) ) : 5 ); ?></div>