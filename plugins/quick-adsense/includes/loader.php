<?php
/**
 * Return the content of the File after processing.
 *
 * @param string  $file File name.
 * @param array   $args Data to pass to the file.
 * @param boolean $echo Choose whether to echo or return the output.
 */
function quick_adsense_load_file( $file, $args = [], $echo = false ) {
	if ( ( '' !== $file ) && file_exists( dirname( __FILE__ ) . '/' . $file ) ) {
		if ( is_array( $args ) ) {
			//phpcs:disable WordPress.PHP.DontExtract.extract_extract
			// Usage of extract() is necessary in this content to simulate templating functionality.
			extract( $args );
			//phpcs:enable
		}
		ob_start();
		include dirname( __FILE__ ) . '/' . $file;
		$content = ob_get_contents();
		ob_end_clean();
		if ( $echo ) {
			echo wp_kses( $content, quick_adsense_get_allowed_html() );
		}
		return $content;
	}
}
