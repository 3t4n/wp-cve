<?php


function edpl_emitStyle() {
	 $font_path = plugins_url( 'fonts', __FILE__ );

	$css = "
    @font-face {
      font-family: 'eplus-icon';
      src: url('$font_path/eplus-icon.eot?xs1351');
      src: url('$font_path/eplus-icon.eot?xs1351#iefix')
          format('embedded-opentype'),
        url('$font_path/eplus-icon.ttf?xs1351') format('truetype'),
        url('$font_path/eplus-icon.woff?xs1351') format('woff'),
        url('$font_path/eplus-icon.svg?xs1351#eplus-icon') format('svg');
      font-weight: normal;
      font-style: normal;
      font-display: block;
    }";

	add_filter(
		'editor_plus_plugin_css',
		function ( $plugin_css ) use ( $css ) {
			$plugin_css .= $css;

			return $plugin_css;
		}
	);
}


$opt = get_option( 'editor_plus_extensions_icon_inserter__enable', true );

$is_extension_enabled = $opt === '1' || $opt === true ? true : false;

if ( $is_extension_enabled ) {
	edpl_emitStyle();
}
