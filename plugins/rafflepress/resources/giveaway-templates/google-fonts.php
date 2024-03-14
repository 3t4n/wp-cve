<?php

// $rafflepress_fontfile = RAFFLEPRESS_PLUGIN_PATH . 'resources/webfonts.json';
// $rafflepress_fonts_json = file_get_contents($rafflepress_fontfile);
// $rafflepress_fonts[0] = 'Inherit';
// $rafflepress_fonts['Standard Fonts'] = array(
//     "Helvetica, Arial, sans-serif"                         => "Helvetica, Arial, sans-serif",
//     "'Arial Black', Gadget, sans-serif"                    => "'Arial Black', Gadget, sans-serif",
//     "'Bookman Old Style', serif"                           => "'Bookman Old Style', serif",
//     "'Comic Sans MS', cursive"                             => "'Comic Sans MS', cursive",
//     "Courier, monospace"                                   => "Courier, monospace",
//     "Garamond, serif"                                      => "Garamond, serif",
//     "Georgia, serif"                                       => "Georgia, serif",
//     "Impact, Charcoal, sans-serif"                         => "Impact, Charcoal, sans-serif",
//     "'Lucida Console', Monaco, monospace"                  => "'Lucida Console', Monaco, monospace",
//     "'Lucida Sans Unicode', 'Lucida Grande', sans-serif"   => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
//     "'MS Sans Serif', Geneva, sans-serif"                  => "'MS Sans Serif', Geneva, sans-serif",
//     "'MS Serif', 'New York', sans-serif"                   => "'MS Serif', 'New York', sans-serif",
//     "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
//     "Tahoma,Geneva, sans-serif"                            => "Tahoma, Geneva, sans-serif",
//     "'Times New Roman', Times,serif"                       => "'Times New Roman', Times, serif",
//     "'Trebuchet MS', Helvetica, sans-serif"                => "'Trebuchet MS', Helvetica, sans-serif",
//     "Verdana, Geneva, sans-serif"                          => "Verdana, Geneva, sans-serif",
// );
//$rafflepress_gfonts = json_decode($rafflepress_fonts_json,true);

$rafflepress_gfonts = array(
	'Helvetica', // Helvetica
	'Playfair Display with Source Sans Pro',
	'Merriweather with Montserrat',
	'Raleway with Lato',
	'Abril Fatface with Roboto',
	'Baloo with Montserrat',
	'Amaranth with Open Sans',
	'Palanquin with Roboto',
	'Sansita with Open Sans',
	'Roboto Slab with Lato',
);
foreach ( $rafflepress_gfonts as $k => $v ) {
	$rafflepress_font_families[ str_replace( ' ', '+', $v ) ] = $v;
}
$rafflepress_fonts = $rafflepress_gfonts;
