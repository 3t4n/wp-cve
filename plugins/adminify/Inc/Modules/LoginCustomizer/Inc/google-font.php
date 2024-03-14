<?php
// Google Fonts
$adminify_font_url = '';
if ( isset( $this->options['login_google_font'] ) ) {
	$adminify_font_family     = isset( $this->options['login_google_font']['font-family'] ) ? $this->options['login_google_font']['font-family'] : '';
	$adminify_font_weight     = isset( $this->options['login_google_font']['font-weight'] ) ? $this->options['login_google_font']['font-weight'] : '';
	$adminify_text_align      = isset( $this->options['login_google_font']['text-align'] ) ? $this->options['login_google_font']['text-align'] : '';
	$adminify_text_transform  = isset( $this->options['login_google_font']['text-transform'] ) ? $this->options['login_google_font']['text-transform'] : '';
	$adminify_text_decoration = isset( $this->options['login_google_font']['text-decoration'] ) ? $this->options['login_google_font']['text-decoration'] : '';
	$adminify_font_size       = isset( $this->options['login_google_font']['font-size'] ) ? $this->options['login_google_font']['font-size'] : '';
	$adminify_line_height     = isset( $this->options['login_google_font']['line-height'] ) ? $this->options['login_google_font']['line-height'] : '';
	$adminify_letter_spacing  = isset( $this->options['login_google_font']['letter-spacing'] ) ? $this->options['login_google_font']['letter-spacing'] : '';
	$adminify_word_spacing    = isset( $this->options['login_google_font']['word-spacing'] ) ? $this->options['login_google_font']['word-spacing'] : '';
	$adminify_font_color      = isset( $this->options['login_google_font']['color'] ) ? $this->options['login_google_font']['color'] : '';
	$adminify_font_unit       = isset( $this->options['login_google_font']['unit'] ) ? $this->options['login_google_font']['unit'] : '';

	$jltwp_adminify_query_args = [
		'family' => rawurlencode( $adminify_font_family ),
		// 'subset' => urlencode($font_style_subset),
	];
	$adminify_font_url        = add_query_arg( $jltwp_adminify_query_args, '//fonts.googleapis.com/css' );
	$jltwp_adminify_fonts_url = esc_url_raw( $adminify_font_url );
	?>
	<link href="<?php echo esc_url( $jltwp_adminify_fonts_url ); ?>" rel='stylesheet'>
	<style type="text/css">
		<?php
		if ( $adminify_font_family ) {
			?>
			body {
			font-family: <?php echo '"' . esc_attr( $adminify_font_family ) . '"'; ?> !important;
		}

		<?php } ?>.login input[type="submit"],
		.login form .input,
		.login input[type="text"] {
			<?php
			if ( $adminify_font_family ) {
				?>
				font-family: <?php echo '"' . esc_attr( $adminify_font_family ) . '"'; ?> !important;
			<?php } ?>
					<?php
					if ( $adminify_font_weight ) {
						?>
				font-weight: <?php echo esc_attr( $adminify_font_weight ); ?> !important;
					<?php } ?>
					<?php
					if ( $adminify_text_align ) {
						?>
				text-align: <?php echo esc_attr( $adminify_text_align ); ?> !important;
					<?php } ?>
					<?php
					if ( $adminify_text_transform ) {
						?>
				text-transform: <?php echo esc_attr( $adminify_text_transform ); ?>
			!important;
					<?php } ?>
					<?php
					if ( $adminify_text_decoration ) {
						?>
				text-decoration: <?php echo esc_attr( $adminify_text_decoration ); ?>
			!important;
					<?php } ?>
					<?php
					if ( $adminify_font_size ) {
						?>
				font-size: <?php echo esc_attr( $adminify_font_size . $adminify_font_unit ); ?> !important;
					<?php } ?>
					<?php
					if ( $adminify_line_height ) {
						?>
				line-height: <?php echo esc_attr( $adminify_line_height . $adminify_font_unit ); ?>
			!important;
					<?php } ?>
					<?php
					if ( $adminify_letter_spacing ) {
						?>
				letter-spacing:
						<?php echo esc_attr( $adminify_letter_spacing . $adminify_font_unit ); ?> !important;
					<?php } ?>
					<?php
					if ( $adminify_word_spacing ) {
						?>
				word-spacing: <?php echo esc_attr( $adminify_word_spacing . $adminify_font_unit ); ?>
			!important;
					<?php } ?>
					<?php
					if ( $adminify_font_color ) {
						?>
				color: <?php echo esc_attr( $adminify_font_color ); ?> !important;
					<?php } ?>
		}
	</style>
	<?php
}
?>


<?php
if ( ! empty( $this->options['jltwp_adminify_customizer_custom_css'] ) ) :
	echo '<style>';
	echo "\n" . wp_strip_all_tags( $this->options['jltwp_adminify_customizer_custom_css'] ) . "\n";
	echo '</style>';
endif;
?>

<?php if ( ! empty( $this->options['jltwp_adminify_customizer_custom_js'] ) ) :
	echo '<script>';
	echo "\n" . wp_strip_all_tags( $this->options['jltwp_adminify_customizer_custom_js'] ) . "\n";
	echo '</script>';
endif; ?>
