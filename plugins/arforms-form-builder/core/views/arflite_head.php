<?php
global $arfliteversion;
if ( isset( $css_file ) ) {

	if ( is_array( $css_file ) ) {
		$i = 1;
		foreach ( $css_file as $file ) {

			wp_register_style( 'arfliteformheadcss-' . $i, $file, array(), $arfliteversion );
			wp_print_styles( 'arfliteformheadcss-' . $i );
			$i++;
		}
	} else { ?>

			<?php
			wp_register_style( 'arflite-formheadcss', $css_file, array(), $arfliteversion );
			wp_print_styles( 'arflite-formheadcss' );
			?>
			<?php
	}
}

if ( isset( $js_file ) ) {

	if ( is_array( $js_file ) ) {
		$i = 1;
		foreach ( $js_file as $file ) {

			wp_register_script( 'arflite-arformsjs-' . $i, $file, array(), $arfliteversion );
			wp_print_scripts( 'arflite-arformsjs-' . $i );
			$i++;
		}
	} else {
		?>

		<?php
		wp_register_script( 'arflite-arformsjs', $js_file, array(), $arfliteversion );
		wp_print_scripts( 'arflite-arformsjs' );
		?>
		<?php

	}
}
