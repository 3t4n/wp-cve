<?php
// Soubor s funkcemi pro doplňující českou lokalizaci fóra bbPress...

// 1) Různé české hodnoty plurálů...
// Založit ticket pro oddělené hodnoty plurálů...
add_filter( 'bbp_get_time_since', 'separatista_bbpress_aktualnost_pluraly', 10, 3 );
function separatista_bbpress_aktualnost_pluraly( $output, $older_date, $newer_date ) {
	$pole = explode ( ', ', $output );
	foreach ( $pole as $cast ) {
		$hodnota = explode ( ' ', $cast );
		if ( $hodnota[0] > 4 ) {
			if ($hodnota[1] == "sekundy") { $output = str_replace( 'sekundy', 'sekund', $output ); }
			if ($hodnota[1] == "minuty") { $output = str_replace( 'minuty', 'minut', $output ); }
			if ($hodnota[1] == "hodiny") { $output = str_replace( 'hodiny', 'hodin', $output ); }
			if ($hodnota[1] == "dny") {
				$output = str_replace( 'dny', 'dní', $output );
				$output = str_replace( 'týdní', 'týdny', $output ); // Hack pro nechtěnou změnu týdnů...
			}
			if ($hodnota[1] == "týdny") { $output = str_replace( 'týdny', 'týdnů', $output ); }
			if ($hodnota[1] == "měsíce") { $output = str_replace( 'měsíce', 'měsíců', $output ); }
			if ($hodnota[1] == "roky") { $output = str_replace( 'roky', 'let', $output ); }
  	}
  }
  return $output;
}

// 2) Změnit zobrazování nuly...
// Založit ticket pro nulové hodnoty, doplnit případné další nulové varianty...
add_filter( 'bbp_get_single_topic_description', 'separatista_bbpress_odstranit_nulu', 10, 2 );
function separatista_bbpress_odstranit_nulu( $retstr, $r ) {
  $pos = strpos( $retstr , '0 odpovědí' );
  if ( $pos != false ) {
    $retstr = str_replace( 'obsahuje celkem', 'neobsahuje zatím', $retstr );
    $retstr = str_replace( '0 odpovědí', 'žádnou odpověď', $retstr );
  }
  // Založit ticket pro počet skrytých odpovědí...
  preg_match('/ (\d+) (\w+)/', $retstr, $matches);
  if ( $matches != null ) { // Podmínka pro fórum bez odpovědí (nahradili jsme nulu)...
    if ( $matches[1] > 1 && $matches[1] < 5 ) {
      $retstr = str_replace( 'skrytou', 'skryté', $retstr );
    }
    if ( $matches[1] >= 5 ) {
      $retstr = str_replace( 'skrytou', 'skrytých', $retstr );
    }
  }
	return $retstr;
}

// 3) Změna kontextu řetězce v administračním menu...
// http://wordpress.stackexchange.com/questions/9211/changing-admin-menu-labels
function separatista_zmena_kontextu_popisu_menu() {
  global $menu;
  $menu[555555][0] = 'Fóra';
}
add_action( 'admin_menu', 'separatista_zmena_kontextu_popisu_menu' );

function separatista_zmena_kontextu_popisu() {
  global $wp_post_types;
  $labels = &$wp_post_types['forum']->labels;
  $labels->name = 'Fóra';
}
add_action( 'init', 'separatista_zmena_kontextu_popisu' );
?>