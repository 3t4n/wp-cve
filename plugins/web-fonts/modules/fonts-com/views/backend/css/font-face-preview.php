@font-face {
	font-family: '<?php echo $font->FontCSSName; ?>';
	src: url('<?php echo $font->EOTURL; ?>') format('embedded-opentype'), 
	     url('<?php echo $font->WOFFURL; ?>') format('woff'), 
	     url('<?php echo $font->TTFURL; ?>')  format('truetype'),
	     url('<?php echo $font->SVGURL; ?>#svgFontName') format('svg');
}