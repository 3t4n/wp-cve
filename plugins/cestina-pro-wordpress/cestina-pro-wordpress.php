<?php
/*
Plugin Name: Čeština pro WordPress
Plugin URI: http://www.separatista.net
Description: Doplňuje funkčnost oficiální češtiny ve WordPressu (a výchozích šablonách) a obsahuje další překlady pluginů a šablon do češtiny.
Author: Separatista
Version: 0.4
Author URI: http://www.separatista.net
*/

// TODO:
// Zkontrolovat, zda jsou pluginy/šablony aktivovány?
// Ošetřit deaktivaci původního pluginu s úpravami češtiny.
// Doplnit obecné načítání pluginů

// Soubor s funkcemi pro českou lokalizaci WordPressu (cs_CZ)...

// 1) Tvrdá mezera jako oddělovač tisíců zabrání zalamování čísel...
// Zdroj: http://www.honza.info/wordpress/jak-na-ceske-formatovani-cisel-ve-wordpressu/
function separatista_cisla_hezky_cesky( $number ) {
  return str_replace( ' ', '&nbsp;', $number );
}
add_filter( 'number_format_i18n', 'separatista_cisla_hezky_cesky' );

// 2) Odkaz s počtem komentářů ve výchozích šablonách rozlišuje výrazy "komentáře" a "komentářů"...
function separatista_komentare_hezky_cesky( $output, $number ) {
	// WordPress 3.4
	if ( function_exists( 'wp_get_theme' ) ) { $nazev_sablony = wp_get_theme()->get( 'Name' ); }
	else { $nazev_sablony = get_current_theme(); }

  if( $number >= 5 && ( $nazev_sablony == 'Twenty Ten' || $nazev_sablony == 'Twenty Eleven' || $nazev_sablony == 'Twenty Twelve' || $nazev_sablony == 'Twenty Thirteen' ) ) {
    $output = str_replace( 'komentáře', 'komentářů', $output );
  }
  return $output;
}
add_filter( 'comments_number', 'separatista_komentare_hezky_cesky', 10, 2 );

// Nahradíme existující lokalizační soubory pro bbPress...
function separatista_bbpress_cesky_preklad( $mofile, $domain='' ) {
  $custom_mofile = '';
  if ( in_array( $domain, array( 'bbpress' ) ) ) {
    $pathinfo = pathinfo( $mofile );
    $custom_mofile = WP_PLUGIN_DIR . '/cestina-pro-wordpress/plugins/bbpress/' . $pathinfo['basename'];
  }   
  if ( file_exists( $custom_mofile ) )
    return ( $custom_mofile );
  else
    return $mofile;
}

// http://bbpress.trac.wordpress.org/ticket/1647
function separatista_load_textdomain_bbpress() { 
  global $bbp;
  $mofile_local = WP_PLUGIN_DIR . '/bbpress/bbp-languages/bbpress-cs_CZ.mo';
  $mofile_global = WP_LANG_DIR . '/bbpress/bbpress-cs_CZ.mo';
  
  // Pokud není dostupný žádný lokalizační soubor...
  if ( !file_exists( $mofile_local ) && !file_exists( $mofile_global ) ) { // && class_exists( 'bbPress' )
    $mofile_path  = WP_PLUGIN_DIR . '/cestina-pro-wordpress/plugins/bbpress/bbpress-cs_CZ.mo';
    if ( file_exists( $mofile_path ) ) {
      remove_action( 'bbp_load_textdomain', array( $bbp, 'load_textdomain' ), 5 );
      load_textdomain( 'bbpress', $mofile_path ); 
    }
  }
  
  // Nahradíme existující lokalizační soubory...
  else {
  	add_filter( 'load_textdomain_mofile', 'separatista_bbpress_cesky_preklad', 10, 2 );
  }
  
  // Načteme soubor s dalšími úpravami češtiny...
  require( WP_PLUGIN_DIR . '/cestina-pro-wordpress/plugins/bbpress/bbpress.php' );
}

// Akce se spouští pouze pokud je plugin bbPress aktivován...
add_action( 'bbp_load_textdomain', 'separatista_load_textdomain_bbpress', 4 );

// Nastavení správné cesty k překladům šablony...
function separatista_ceske_sablony( $mofile, $domain='' ) {
  $custom_mofile = '';
  if ( in_array( $domain, array( 'twentythirteen', 'twentytwelve', 'twentyeleven', 'twentyten' ) ) ) {
    $pathinfo = pathinfo( $mofile );
    $custom_mofile = WP_PLUGIN_DIR . '/cestina-pro-wordpress/themes/' . $domain . '/' . $pathinfo['basename'];
  }
  if ( file_exists( $custom_mofile ) )
    return ( $custom_mofile );
  else
    return $mofile;
}
add_filter( 'load_textdomain_mofile', 'separatista_ceske_sablony', 10, 2 );

// Upravený font Bitter s českými znaky pro šablonu Twenty Thirteen...
function separatista_bitter_font_cestina() {
	echo '<style>';
	echo '
@font-face {
		font-family: "Bitter";
		src: url("' . plugins_url( '/fonts/bitter-regular-webfont.eot', __FILE__ ) . '");
		src: url("' . plugins_url( '/fonts/bitter-regular-webfont.eot?#iefix', __FILE__ ) . '") format("embedded-opentype"),
				 url("' . plugins_url( '/fonts/bitter-regular-webfont.woff', __FILE__ ) . '") format("woff"),
				 url("' . plugins_url( '/fonts/bitter-regular-webfont.ttf', __FILE__ ) . '") format("truetype");
		font-weight: normal;
		font-style: normal;
}
@font-face {
		font-family: "Bitter";
		src: url("' . plugins_url( '/fonts/bitter-bold-webfont.eot', __FILE__ ) . '");
		src: url("' . plugins_url( '/fonts/bitter-bold-webfont.eot?#iefix', __FILE__ ) . '") format("embedded-opentype"),
				 url("' . plugins_url( '/fonts/bitter-bold-webfont.woff', __FILE__ ) . '") format("woff"),
				 url("' . plugins_url( '/fonts/bitter-bold-webfont.ttf', __FILE__ ) . '") format("truetype");
		font-weight: bold;
		font-style: normal;
}
';
	echo '</style>';
}
add_action( 'wp_head', 'separatista_bitter_font_cestina', 1 );

// Poznámky:
// Porovnávání verzí pro existenci funkcí...
// http://code.google.com/p/wp-e-commerce/source/detail?r=1787
?>