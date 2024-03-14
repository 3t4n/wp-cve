<?php
/**
 * Displays select values.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

foreach ( $args as $key => $value ) {
	if ( false === $value['isPro'] ) : ?>
		<div class="item" data-value="<?php echo esc_attr( $key ); ?>"> <?php echo esc_html( $value['val'] ); ?></div>
	<?php else : ?>
		<div class="item d-flex justify-content-between align-items-center item pro_feature_input pro_input_select"
			data-value="<?php echo esc_attr( $key ); ?>">
			<span><?php echo esc_html( $value['val'] ); ?></span>
			<i class="fas fa-star pro_star_icon"></i>
		</div>
		<?php
	endif;
}
?>
