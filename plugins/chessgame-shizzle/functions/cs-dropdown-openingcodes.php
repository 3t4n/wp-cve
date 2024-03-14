<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Get the formatted list of openingcodes for the form in html.
 * https://en.wikipedia.org/wiki/List_of_chess_openings
 *
 * @param  string $selected optional, text with the key value of the ECO code that is wanted as the selected option in the dropdown.
 * @param  string $name     optional, name and class of the select element. (since 1.2.0)
 *
 * @return string $dropdown html with a select element with 500 options for ECO code.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_dropdown_openingcodes( $selected = '', $name = '' ) {

	$name = (string) $name;
	if ( strlen( $name ) < 1 ) {
		$name = 'cs_chessgame_code';
	}

	$dropdown = '
		<select class="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" data-placeholder="' . esc_attr__('Choose an opening code...', 'chessgame-shizzle' ) . '">
			<option value="0">' . esc_html__( 'Select...', 'chessgame-shizzle' ) . '</option>';


	// Eco Code A
	$codes = chessgame_shizzle_get_array_openingcodes_a();
	$dropdown .= '
			<optgroup label="' . esc_attr__('A - Flank Openings', 'chessgame-shizzle') . '">';
	foreach ( $codes as $key => $value ) {
		$dropdown .= '
				<option value="' . esc_attr( $key ) . '"';
		if ( $selected === $key ) {
			$dropdown .= ' selected="selected"';
		}
		$dropdown .= '>' . esc_html( $value ) . '</option>';
	}
	$dropdown .= '
			</optgroup>';

	// Eco Code B
	$codes = chessgame_shizzle_get_array_openingcodes_b();
	$dropdown .= '
			<optgroup label="' . esc_attr__('B - Semi-Open Games other than the French Defense', 'chessgame-shizzle') . '">';
	foreach ( $codes as $key => $value ) {
		$dropdown .= '
				<option value="' . esc_attr( $key ) . '"';
		if ( $selected === $key ) {
			$dropdown .= ' selected="selected"';
		}
		$dropdown .= '>' . esc_html( $value ) . '</option>';
	}
	$dropdown .= '
			</optgroup>';


	// Eco Code C
	$codes = chessgame_shizzle_get_array_openingcodes_c();
	$dropdown .= '
			<optgroup label="' . esc_attr__('C - Open Games and the French Defense', 'chessgame-shizzle') . '">';
	foreach ( $codes as $key => $value ) {
		$dropdown .= '
				<option value="' . esc_attr( $key ) . '"';
		if ( $selected === $key ) {
			$dropdown .= ' selected="selected"';
		}
		$dropdown .= '>' . esc_html( $value ) . '</option>';
	}
	$dropdown .= '
			</optgroup>';


	// Eco Code D
	$codes = chessgame_shizzle_get_array_openingcodes_d();
	$dropdown .= '
			<optgroup label="' . esc_attr__('D - Closed Games and Semi-Closed Games', 'chessgame-shizzle') . '">';
	foreach ( $codes as $key => $value ) {
		$dropdown .= '
				<option value="' . esc_attr( $key ) . '"';
		if ( $selected === $key ) {
			$dropdown .= ' selected="selected"';
		}
		$dropdown .= '>' . esc_html( $value ) . '</option>';
	}
	$dropdown .= '
			</optgroup>';


	// Eco Code E
	$codes = chessgame_shizzle_get_array_openingcodes_e();
	$dropdown .= '
			<optgroup label="' . esc_attr__('E - Indian Defenses', 'chessgame-shizzle') . '">';
	foreach ( $codes as $key => $value ) {
		$dropdown .= '
				<option value="' . esc_attr( $key ) . '"';
		if ( $selected === $key ) {
			$dropdown .= ' selected="selected"';
		}
		$dropdown .= '>' . esc_html( $value ) . '</option>';
	}
	$dropdown .= '
			</optgroup>';


	$dropdown .= '
		</select>';

	return $dropdown;

}


/*
 * Fetch all the ECO codes.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_array_openingcodes() {
	$codes_a = chessgame_shizzle_get_array_openingcodes_a();
	$codes_b = chessgame_shizzle_get_array_openingcodes_b();
	$codes_c = chessgame_shizzle_get_array_openingcodes_c();
	$codes_d = chessgame_shizzle_get_array_openingcodes_d();
	$codes_e = chessgame_shizzle_get_array_openingcodes_e();

	$codes = array_merge( $codes_a, $codes_b, $codes_c, $codes_d, $codes_e );

	return $codes;
}


/*
 * Fetch all the ECO codes starting with A.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_array_openingcodes_a() {
	$codes = array(
		'A00' => esc_html__( 'A00 Irregular Openings', 'chessgame-shizzle' ),
		'A01' => esc_html__( "A01 Larsen's Opening", 'chessgame-shizzle' ),
		'A02' => esc_html__( 'A02 Bird Opening', 'chessgame-shizzle' ),
		'A03' => esc_html__( 'A03 Bird Opening (1. f4 d5)', 'chessgame-shizzle' ),
		'A04' => esc_html__( 'A04 Réti (1.Nf3)', 'chessgame-shizzle' ),
		'A05' => esc_html__( 'A05 Réti (1.Nf3 Nf6)', 'chessgame-shizzle' ),
		'A06' => esc_html__( 'A06 Réti (1.Nf3 d5), Zukertort', 'chessgame-shizzle' ),
		'A07' => esc_html__( "A07 Réti (1.Nf3 d5 2.g3), King's Indian Attack (Barcza System)", 'chessgame-shizzle' ),
		'A08' => esc_html__( "A08 Réti (1.Nf3 d5 2.g3 c5 3.Bg2), King's Indian Attack", 'chessgame-shizzle' ),
		'A09' => esc_html__( 'A09 Réti (1.Nf3 d5 2.c4)', 'chessgame-shizzle' ),
		'A10' => esc_html__( 'A10 English (1.c4)', 'chessgame-shizzle' ),
		'A11' => esc_html__( 'A11 English (1.c4 c6), Caro–Kann defensive system', 'chessgame-shizzle' ),
		'A12' => esc_html__( 'A12 English (1.c4 c6), Caro–Kann defensive system', 'chessgame-shizzle' ),
		'A13' => esc_html__( 'A13 English (1.c4 e6)', 'chessgame-shizzle' ),
		'A14' => esc_html__( 'A14 English (1.c4 e6 2.Nf3 d5 3.g3 Nf6 4.Bg2 Be7), Neo-Catalan declined', 'chessgame-shizzle' ),
		'A15' => esc_html__( 'A15 English (1.c4 Nf6), Anglo-Indian Defence', 'chessgame-shizzle' ),
		'A16' => esc_html__( 'A16 English (1.c4 Nf6 2.Nc3), Anglo-Indian Defence', 'chessgame-shizzle' ),
		'A17' => esc_html__( 'A17 English (1.c4 Nf6 2.Nc3 e6), Hedgehog Defence', 'chessgame-shizzle' ),
		'A18' => esc_html__( 'A18 English (1.c4 Nf6 2.Nc3 e6 3.e4), Mikenas–Carls Variation', 'chessgame-shizzle' ),
		'A19' => esc_html__( 'A19 English (1.c4 Nf6 2.Nc3 e6 3.e4 c5), Mikenas–Carls, Sicilian Variation', 'chessgame-shizzle' ),
		'A20' => esc_html__( 'A20 English (1.c4 e5)', 'chessgame-shizzle' ),
		'A21' => esc_html__( 'A21 English (1.c4 e5 2.Nc3)', 'chessgame-shizzle' ),
		'A22' => esc_html__( 'A22 English (1.c4 e5 2.Nc3 Nf6)', 'chessgame-shizzle' ),
		'A23' => esc_html__( 'A23 English (1.c4 e5 2.Nc3 Nf6 3.g3 c6), Bremen System, Keres Variation', 'chessgame-shizzle' ),
		'A24' => esc_html__( 'A24 English (1.c4 e5 2.Nc3 Nf6 3.g3 g6), Bremen System', 'chessgame-shizzle' ),
		'A25' => esc_html__( 'A25 English (1.c4 e5 2 Nc3 Nc6), Sicilian Reversed', 'chessgame-shizzle' ),
		'A26' => esc_html__( 'A26 English (1.c4 e5 2.Nc3 Nc6 3.g3 g6 4.Bg2 Bg7 5.d3 d6), Closed System', 'chessgame-shizzle' ),
		'A27' => esc_html__( 'A27 English (1.c4 e5 2.Nc3 Nc6 3.Nf3), Three Knights System', 'chessgame-shizzle' ),
		'A28' => esc_html__( 'A28 English (1.c4 e5 2.Nc3 Nc6 3.Nf3 Nf6), Four Knights System', 'chessgame-shizzle' ),
		'A29' => esc_html__( 'A29 English (1.c4 e5 2.Nc3 Nc6 3.Nf3 Nf6 4.g3), Four Knights, Kingside Fianchetto', 'chessgame-shizzle' ),
		'A30' => esc_html__( 'A30 English (1.c4 c5), Symmetrical defence', 'chessgame-shizzle' ),
		'A31' => esc_html__( 'A31 English (1.c4 c5 2.Nf3 Nf6 3.d4), Symmetrical, Benoni formation', 'chessgame-shizzle' ),
		'A32' => esc_html__( 'A32 English (1.c4 c5 2.Nf3 Nf6 3.d4 cxd4 4.Nxd4 e6), Symmetrical', 'chessgame-shizzle' ),
		'A33' => esc_html__( 'A33 English (1.c4 c5 2.Nf3 Nf6 3.d4 cxd4 4.Nxd4 e6 5.Nc3 Nc6), Symmetrical', 'chessgame-shizzle' ),
		'A34' => esc_html__( 'A34 English (1.c4 c5 2.Nc3), Symmetrical', 'chessgame-shizzle' ),
		'A35' => esc_html__( 'A35 English (1.c4 c5 2.Nc3 Nc6), Symmetrical', 'chessgame-shizzle' ),
		'A36' => esc_html__( 'A36 English (1.c4 c5 2.Nc3 Nc6 3.g3), Symmetrical', 'chessgame-shizzle' ),
		'A37' => esc_html__( 'A37 English (1.c4 c5 2.Nc3 Nc6 3.g3 g6 4.Bg2 Bg7 5.Nf3), Symmetrical', 'chessgame-shizzle' ),
		'A38' => esc_html__( 'A38 English (1.c4 c5 2.Nc3 Nc6 3.g3 g6 4.Bg2 Bg7 5.Nf3 Nf6), Symmetrical', 'chessgame-shizzle' ),
		'A39' => esc_html__( 'A39 English (1.c4 c5 2.Nc3 Nc6 3.g3 g6 4.Bg2 Bg7 5.Nf3 Nf6 6.O-O (6.d4)), Symmetrical, Main line', 'chessgame-shizzle' ),
		'A40' => esc_html__( "A40 Queen's Pawn Game, Mikėnas Defence, Englund Gambit", 'chessgame-shizzle' ),
		'A41' => esc_html__( "A41 Queen's Pawn Game, Wade Defence", 'chessgame-shizzle' ),
		'A42' => esc_html__( 'A42 Modern Defence, Averbakh System also Wade Defence', 'chessgame-shizzle' ),
		'A43' => esc_html__( 'A43 Old Benoni defence', 'chessgame-shizzle' ),
		'A44' => esc_html__( 'A44 Old Benoni defence', 'chessgame-shizzle' ),
		'A45' => esc_html__( "A45 Queen's Pawn Game Trompowski", 'chessgame-shizzle' ),
		'A46' => esc_html__( "A46 Queen's Pawn Game (d4 Nf6 without 2.c4), Torre Attack", 'chessgame-shizzle' ),
		'A47' => esc_html__( "A47 Queen's Indian (d4 Nf6 without 2.c4)", 'chessgame-shizzle' ),
		'A48' => esc_html__( "A48 King's Indian, East Indian Defence (d4 Nf6 without 2.c4)", 'chessgame-shizzle' ),
		'A49' => esc_html__( "A49 King's Indian, Fianchetto without c4", 'chessgame-shizzle' ),
		'A50' => esc_html__( "A50 Queen's Pawn Game, 1.d4 d5 2.c4", 'chessgame-shizzle' ),
		'A51' => esc_html__( 'A51 Budapest Gambit declined', 'chessgame-shizzle' ),
		'A52' => esc_html__( 'A52 Budapest Gambit', 'chessgame-shizzle' ),
		'A53' => esc_html__( 'A53 Old Indian, Chigorin Indian Defence', 'chessgame-shizzle' ),
		'A54' => esc_html__( 'A54 Old Indian, Ukrainian Variation', 'chessgame-shizzle' ),
		'A55' => esc_html__( 'A55 Old Indian, Main line', 'chessgame-shizzle' ),
		'A56' => esc_html__( 'A56 Benoni', 'chessgame-shizzle' ),
		'A57' => esc_html__( 'A57 Benko Gambit', 'chessgame-shizzle' ),
		'A58' => esc_html__( 'A58 Benko Gambit Accepted', 'chessgame-shizzle' ),
		'A59' => esc_html__( 'A59 Benko Gambit (7.e4)', 'chessgame-shizzle' ),
		'A60' => esc_html__( 'A60 Modern Benoni', 'chessgame-shizzle' ),
		'A61' => esc_html__( 'A61 Modern Benoni', 'chessgame-shizzle' ),
		'A62' => esc_html__( 'A62 Modern Benoni, Fianchetto Variation without early ...Nbd7', 'chessgame-shizzle' ),
		'A63' => esc_html__( 'A63 Modern Benoni, Fianchetto Variation, 9...Nbd7', 'chessgame-shizzle' ),
		'A64' => esc_html__( 'A64 Modern Benoni, Fianchetto Variation, 11...Re8', 'chessgame-shizzle' ),
		'A65' => esc_html__( 'A65 Modern Benoni (6.e4)', 'chessgame-shizzle' ),
		'A66' => esc_html__( 'A66 Modern Benoni, Pawn Storm Variation', 'chessgame-shizzle' ),
		'A67' => esc_html__( 'A67 Modern Benoni, Taimanov Variation', 'chessgame-shizzle' ),
		'A68' => esc_html__( 'A68 Modern Benoni, Four Pawns Attack', 'chessgame-shizzle' ),
		'A69' => esc_html__( 'A69 Modern Benoni, Four Pawns Attack, Main line', 'chessgame-shizzle' ),
		'A70' => esc_html__( 'A70 Modern Benoni, Classical with e4 and Nf3', 'chessgame-shizzle' ),
		'A71' => esc_html__( 'A71 Modern Benoni, Classical, 8.Bg5', 'chessgame-shizzle' ),
		'A72' => esc_html__( 'A72 Modern Benoni, Classical without 9.0-0', 'chessgame-shizzle' ),
		'A73' => esc_html__( 'A73 Modern Benoni, Classical, 9.0-0', 'chessgame-shizzle' ),
		'A74' => esc_html__( 'A74 Modern Benoni, Classical, 9...a6, 10.a4', 'chessgame-shizzle' ),
		'A75' => esc_html__( 'A75 Modern Benoni, Classical with ...a6 and 10...Bg4', 'chessgame-shizzle' ),
		'A76' => esc_html__( 'A76 Modern Benoni, Classical, 9...Re8', 'chessgame-shizzle' ),
		'A77' => esc_html__( 'A77 Modern Benoni, Classical, 9...Re8, 10.Nd2', 'chessgame-shizzle' ),
		'A78' => esc_html__( 'A78 Modern Benoni, Classical with ...Re8 and ...Na6', 'chessgame-shizzle' ),
		'A79' => esc_html__( 'A79 Modern Benoni, Classical, 11.f3', 'chessgame-shizzle' ),
		'A80' => esc_html__( 'A80 Dutch Defence, Raphael Variation', 'chessgame-shizzle' ),
		'A81' => esc_html__( 'A81 Dutch Defence 2.g3', 'chessgame-shizzle' ),
		'A82' => esc_html__( 'A82 Dutch Defence, Staunton Gambit 2.e4', 'chessgame-shizzle' ),
		'A83' => esc_html__( "A83 Dutch Defence, Staunton Gambit, Staunton's line 2.e4 fxe4 3.Nc3 Nf6 4.Bg5", 'chessgame-shizzle' ),
		'A84' => esc_html__( 'A84 Dutch Defence 2.c4 (without 2...Nf6 3.Nc3 (A85), 2...Nf6 3.g3 (A86–A99))', 'chessgame-shizzle' ),
		'A85' => esc_html__( 'A85 Dutch Defence with 2.c4 Nf6 3.Nc3', 'chessgame-shizzle' ),
		'A86' => esc_html__( 'A86 Dutch Defence with 2.c4 Nf6 3.g3 (without 3...g6 4.Bg2 Bg7 5.Nf3 (A87) and 3...e6 4.Bg2 (A90–A99))', 'chessgame-shizzle' ),
		'A87' => esc_html__( 'A87 Dutch Defence, Leningrad, Main Variation 2.c4 Nf6 3.g3 g6 4.Bg2 Bg7 5.Nf3 (without 5...0-0 6.0-0 d6 7.Nc3 c6 (A88) and 7...Nc6 (A89))', 'chessgame-shizzle' ),
		'A88' => esc_html__( 'A88 Dutch Defence, Leningrad, Main Variation with 5...0-0 6.0-0 d6 7.Nc3 c6', 'chessgame-shizzle' ),
		'A89' => esc_html__( 'A89 Dutch Defence, Leningrad, Main Variation with 5...0-0 6.0-0 d6 7.Nc3 Nc6', 'chessgame-shizzle' ),
		'A90' => esc_html__( 'A90 Dutch Defence 2.c4 Nf6 3.g3 e6 4.Bg2 (without 4...Be7 (A91–A99))', 'chessgame-shizzle' ),
		'A91' => esc_html__( 'A91 Dutch Defence 2.c4 Nf6 3.g3 e6 4.Bg2 Be7 (without 5.Nf3 (A92–A99))', 'chessgame-shizzle' ),
		'A92' => esc_html__( 'A92 Dutch Defence 2.c4 Nf6 3.g3 e6 4.Bg2 Be7 5.Nf3 0-0 (without 6.0-0 (A93–A99))', 'chessgame-shizzle' ),
		'A93' => esc_html__( 'A93 Dutch Defence, Stonewall, Botvinnik Variation 2.c4 Nf6 3.g3 e6 4.Bg2 Be7 5.Nf3 0-0 6.0-0 d5 7. b3 (without 7...c6 8.Ba3 (A94))', 'chessgame-shizzle' ),
		'A94' => esc_html__( 'A94 Dutch Defence, Stonewall with 6.0-0 d5.7.b3 c6 8.Ba3', 'chessgame-shizzle' ),
		'A95' => esc_html__( 'A95 Dutch Defence, Stonewall with 6.0-0 d5.7.Nc3 c6', 'chessgame-shizzle' ),
		'A96' => esc_html__( 'A96 Dutch Defence, Classical Variation 2.c4 Nf6 3.g3 e6 4.Bg2 Be7 5.Nf3 0-0 6.0-0 d6 (without 7.Nc3 Qe8 (A97–A99))', 'chessgame-shizzle' ),
		'A97' => esc_html__( 'A97 Dutch Defence, Ilyin–Genevsky Variation 7.Nc3 Qe8 (without 8.Qc2 (A98) and 8.b3 (A99))', 'chessgame-shizzle' ),
		'A98' => esc_html__( 'A98 Dutch Defence, Ilyin–Genevsky Variation with 7.Nc3 Qe8 8.Qc2', 'chessgame-shizzle' ),
		'A99' => esc_html__( 'A99 Dutch Defence, Ilyin–Genevsky Variation with 7.Nc3 Qe8 8.b3', 'chessgame-shizzle' ),
	);
	return $codes;
}


/*
 * Fetch all the ECO codes starting with B.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_array_openingcodes_b() {
	$codes = array(
		'B00' => esc_html__( "B00 Irregular King's Pawn", 'chessgame-shizzle' ),
		'B01' => esc_html__( 'B01 Scandinavian Defence', 'chessgame-shizzle' ),
		'B02' => esc_html__( "B02 Alekhine's Defence", 'chessgame-shizzle' ),
		'B03' => esc_html__( "B03 Alekhine's Defence 3.d4", 'chessgame-shizzle' ),
		'B04' => esc_html__( "B04 Alekhine's Defence, Modern Variation", 'chessgame-shizzle' ),
		'B05' => esc_html__( "B05 Alekhine's Defence, Modern Variation, 4...Bg4", 'chessgame-shizzle' ),
		'B06' => esc_html__( 'B06 Robatsch Modern & Pterodactyl Defence', 'chessgame-shizzle' ),
		'B07' => esc_html__( 'B07 Pirc Defence', 'chessgame-shizzle' ),
		'B08' => esc_html__( 'B08 Pirc Defence, Classical (Two Knights) System', 'chessgame-shizzle' ),
		'B09' => esc_html__( 'B09 Pirc Defence, Austrian attack', 'chessgame-shizzle' ),
		'B10' => esc_html__( 'B10 Caro-Kann', 'chessgame-shizzle' ),
		'B11' => esc_html__( 'B11 Caro-Kann, Two knights, 3...Bg4', 'chessgame-shizzle' ),
		'B12' => esc_html__( 'B12 Caro-Kann', 'chessgame-shizzle' ),
		'B13' => esc_html__( 'B13 Caro-Kann, Exchange Variation', 'chessgame-shizzle' ),
		'B14' => esc_html__( 'B14 Caro-Kann, Panov–Botvinnik Attack, 5...e6', 'chessgame-shizzle' ),
		'B15' => esc_html__( 'B15 Caro-Kann', 'chessgame-shizzle' ),
		'B16' => esc_html__( 'B16 Caro-Kann, Bronstein–Larsen Variation', 'chessgame-shizzle' ),
		'B17' => esc_html__( 'B17 Caro-Kann, Steinitz Variation, Smyslov Systems', 'chessgame-shizzle' ),
		'B18' => esc_html__( 'B18 Caro-Kann, Classical Variation', 'chessgame-shizzle' ),
		'B19' => esc_html__( 'B19 Caro-Kann, Classical, 7...Nd7', 'chessgame-shizzle' ),
		'B20' => esc_html__( 'B20 Sicilian, including Smith-Morra Gambit', 'chessgame-shizzle' ),
		'B21' => esc_html__( 'B21 Sicilian, Grand Prix Attack, Smith-Morra Gambit', 'chessgame-shizzle' ),
		'B22' => esc_html__( 'B22 Sicilian, Alapin Variation', 'chessgame-shizzle' ),
		'B23' => esc_html__( 'B23 Sicilian Closed, 2.Nc3 (without 2...a6 3.Nf3 (B28), 2...d6 3.Nf3 (B50), 2...Nc6 3.g3 (B24–B26))', 'chessgame-shizzle' ),
		'B24' => esc_html__( 'B24 Sicilian Closed, 2.Nc3 Nc6 3.g3 (without 3...g6 (B25–B26))', 'chessgame-shizzle' ),
		'B25' => esc_html__( 'B25 Sicilian Closed, 2.Nc3 Nc6 3.g3 g6 4.Bg2 Bg7 5.d3 d6 (without 6.Be3 (B26))', 'chessgame-shizzle' ),
		'B26' => esc_html__( 'B26 Sicilian Closed, 6.Be3', 'chessgame-shizzle' ),
		'B27' => esc_html__( 'B27 Sicilian', 'chessgame-shizzle' ),
		'B28' => esc_html__( "B28 Sicilian, O'Kelly Variation", 'chessgame-shizzle' ),
		'B29' => esc_html__( 'B29 Sicilian, Nimzovich–Rubinstein Variation', 'chessgame-shizzle' ),
		'B30' => esc_html__( 'B30 Sicilian, Old Sicilian', 'chessgame-shizzle' ),
		'B31' => esc_html__( 'B31 Sicilian, Nimzovich–Rossolimo Attack', 'chessgame-shizzle' ),
		'B32' => esc_html__( 'B32 Sicilian', 'chessgame-shizzle' ),
		'B33' => esc_html__( 'B33 Sicilian, Sveshnikov (Lasker–Pelikan) Variation', 'chessgame-shizzle' ),
		'B34' => esc_html__( 'B34 Sicilian Accelerated Dragon sidelines', 'chessgame-shizzle' ),
		'B35' => esc_html__( 'B35 Sicilian Accelerated Dragon main line', 'chessgame-shizzle' ),
		'B36' => esc_html__( 'B36 Sicilian Accelerated Fianchetto, Maroczy bind 2.Nf3 Nc6 3.d4 cxd4 4.Nxd4 g6 5.c4 (without 5...Bg7 (B37–B39))', 'chessgame-shizzle' ),
		'B37' => esc_html__( 'B37 Sicilian Accelerated Fianchetto, Maroczy bind, 5...Bg7 (without 6.Be3 (B38))', 'chessgame-shizzle' ),
		'B38' => esc_html__( 'B38 Sicilian Accelerated Fianchetto, Maroczy bind, 5...Bg7 6.Be3 (without 6...Nf6 7.Nc3 Ng4 (B39))', 'chessgame-shizzle' ),
		'B39' => esc_html__( 'B39 Sicilian Accelerated Fianchetto, Breyer Variation', 'chessgame-shizzle' ),
		'B40' => esc_html__( 'B40 Sicilian, 2.Nf3 e6, Delayed Alapin variation', 'chessgame-shizzle' ),
		'B41' => esc_html__( 'B41 Sicilian Kan Variation', 'chessgame-shizzle' ),
		'B42' => esc_html__( 'B42 Sicilian Kan Variation', 'chessgame-shizzle' ),
		'B43' => esc_html__( 'B43 Sicilian Kan Variation', 'chessgame-shizzle' ),
		'B44' => esc_html__( 'B44 Sicilian', 'chessgame-shizzle' ),
		'B45' => esc_html__( 'B45 Sicilian Taimanov Variation, 5.Nc3', 'chessgame-shizzle' ),
		'B46' => esc_html__( 'B46 Sicilian Taimanov Variation', 'chessgame-shizzle' ),
		'B47' => esc_html__( 'B47 Sicilian Taimanov Variation', 'chessgame-shizzle' ),
		'B48' => esc_html__( 'B48 Sicilian Taimanov Variation', 'chessgame-shizzle' ),
		'B49' => esc_html__( 'B49 Sicilian Taimanov Variation', 'chessgame-shizzle' ),
		'B50' => esc_html__( 'B50 Sicilian', 'chessgame-shizzle' ),
		'B51' => esc_html__( 'B51 Sicilian, Canal–Sokolsky Attack', 'chessgame-shizzle' ),
		'B52' => esc_html__( 'B52 Sicilian, Canal–Sokolsky Attack, 3...Bd7', 'chessgame-shizzle' ),
		'B53' => esc_html__( 'B53 Sicilian, Chekhover Variation', 'chessgame-shizzle' ),
		'B54' => esc_html__( 'B54 Sicilian', 'chessgame-shizzle' ),
		'B55' => esc_html__( 'B55 Sicilian, Prins Variation, Venice Attack', 'chessgame-shizzle' ),
		'B56' => esc_html__( 'B56 Sicilian', 'chessgame-shizzle' ),
		'B57' => esc_html__( 'B57 Sicilian, Sozin (not Scheveningen) including Magnus Smith Trap', 'chessgame-shizzle' ),
		'B58' => esc_html__( 'B58 Sicilian, Classical', 'chessgame-shizzle' ),
		'B59' => esc_html__( 'B59 Sicilian, Boleslavsky Variation, 7.Nb3', 'chessgame-shizzle' ),
		'B60' => esc_html__( 'B60 Sicilian Richter-Rauzer', 'chessgame-shizzle' ),
		'B61' => esc_html__( 'B61 Sicilian Richter-Rauzer, Larsen Variation, 7.Qd2', 'chessgame-shizzle' ),
		'B62' => esc_html__( 'B62 Sicilian Richter-Rauzer, 6...e6', 'chessgame-shizzle' ),
		'B63' => esc_html__( 'B63 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B64' => esc_html__( 'B64 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B65' => esc_html__( 'B65 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B66' => esc_html__( 'B66 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B67' => esc_html__( 'B67 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B68' => esc_html__( 'B68 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B69' => esc_html__( 'B69 Sicilian Richter-Rauzer, Rauzer Attack', 'chessgame-shizzle' ),
		'B70' => esc_html__( 'B70 Sicilian Dragon', 'chessgame-shizzle' ),
		'B71' => esc_html__( 'B71 Sicilian Dragon, Levenfish Variation', 'chessgame-shizzle' ),
		'B72' => esc_html__( 'B72 Sicilian Dragon, 6.Be3', 'chessgame-shizzle' ),
		'B73' => esc_html__( 'B73 Sicilian Dragon, Classical, 8.0-0', 'chessgame-shizzle' ),
		'B74' => esc_html__( 'B74 Sicilian Dragon, Classical, 9.Nb3', 'chessgame-shizzle' ),
		'B75' => esc_html__( 'B75 Sicilian Dragon, Yugoslav Attack', 'chessgame-shizzle' ),
		'B76' => esc_html__( 'B76 Sicilian Dragon, Yugoslav Attack', 'chessgame-shizzle' ),
		'B77' => esc_html__( 'B77 Sicilian Dragon, Yugoslav Attack', 'chessgame-shizzle' ),
		'B78' => esc_html__( 'B78 Sicilian Dragon, Yugoslav Attack', 'chessgame-shizzle' ),
		'B79' => esc_html__( 'B79 Sicilian Dragon, Yugoslav Attack', 'chessgame-shizzle' ),
		'B80' => esc_html__( 'B80 Sicilian Scheveningen, English Attack', 'chessgame-shizzle' ),
		'B81' => esc_html__( 'B81 Sicilian Scheveningen, Keres Attack', 'chessgame-shizzle' ),
		'B82' => esc_html__( 'B82 Sicilian Scheveningen, 6.f4', 'chessgame-shizzle' ),
		'B83' => esc_html__( 'B83 Sicilian Scheveningen, 6.Be2', 'chessgame-shizzle' ),
		'B84' => esc_html__( 'B84 Sicilian Scheveningen (Paulsen), Classical Variation', 'chessgame-shizzle' ),
		'B85' => esc_html__( 'B85 Sicilian Scheveningen, Classical Variation with ...Qc7 and ...Nc6', 'chessgame-shizzle' ),
		'B86' => esc_html__( 'B86 Sicilian Sozin Attack', 'chessgame-shizzle' ),
		'B87' => esc_html__( 'B87 Sicilian Sozin with ...a6 and ...b5', 'chessgame-shizzle' ),
		'B88' => esc_html__( 'B88 Sicilian Sozin, Leonhardt Variation', 'chessgame-shizzle' ),
		'B89' => esc_html__( 'B89 Sicilian Sozin, 7.Be3', 'chessgame-shizzle' ),
		'B90' => esc_html__( 'B90 Sicilian Najdorf', 'chessgame-shizzle' ),
		'B91' => esc_html__( 'B91 Sicilian Najdorf, Zagreb (Fianchetto) Variation (6.g3)', 'chessgame-shizzle' ),
		'B92' => esc_html__( 'B92 Sicilian Najdorf, Opocensky Variation (6.Be2)', 'chessgame-shizzle' ),
		'B93' => esc_html__( 'B93 Sicilian Najdorf, 6.f4', 'chessgame-shizzle' ),
		'B94' => esc_html__( 'B94 Sicilian Najdorf, 6.Bg5', 'chessgame-shizzle' ),
		'B95' => esc_html__( 'B95 Sicilian Najdorf, 6...e6', 'chessgame-shizzle' ),
		'B96' => esc_html__( 'B96 Sicilian Najdorf, 7.f4', 'chessgame-shizzle' ),
		'B97' => esc_html__( 'B97 Sicilian Najdorf, 7...Qb6 including Poisoned Pawn Variation', 'chessgame-shizzle' ),
		'B98' => esc_html__( 'B98 Sicilian Najdorf, 7...Be7', 'chessgame-shizzle' ),
		'B99' => esc_html__( 'B99 Sicilian Najdorf, 7...Be7 Main line', 'chessgame-shizzle' ),
	);
	return $codes;
}


/*
 * Fetch all the ECO codes starting with C.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_array_openingcodes_c() {
	$codes = array(
		'C00' => esc_html__( 'C00 French', 'chessgame-shizzle' ),
		'C01' => esc_html__( 'C01 French Perseus Gambit', 'chessgame-shizzle' ),
		'C02' => esc_html__( 'C02 French Advance Variation', 'chessgame-shizzle' ),
		'C03' => esc_html__( 'C03 French Tarrasch', 'chessgame-shizzle' ),
		'C04' => esc_html__( 'C04 French Tarrasch, Guimard Main line', 'chessgame-shizzle' ),
		'C05' => esc_html__( 'C05 French Tarrasch, Closed Variation', 'chessgame-shizzle' ),
		'C06' => esc_html__( 'C06 French Tarrasch, Closed Variation, Main line', 'chessgame-shizzle' ),
		'C07' => esc_html__( 'C07 French Tarrasch, Open Variation', 'chessgame-shizzle' ),
		'C08' => esc_html__( 'C08 French Tarrasch, Open, 4.exd5 exd5', 'chessgame-shizzle' ),
		'C09' => esc_html__( 'C09 French Tarrasch, Open Variation, Main line', 'chessgame-shizzle' ),
		'C10' => esc_html__( 'C10 French, Paulsen Variation', 'chessgame-shizzle' ),
		'C11' => esc_html__( 'C11 French, Burn Variation', 'chessgame-shizzle' ),
		'C12' => esc_html__( 'C12 French, MacCutcheon Variation', 'chessgame-shizzle' ),
		'C13' => esc_html__( 'C13 French Alekhine-Chatard Attack, Albin-Chatard Gambit', 'chessgame-shizzle' ),
		'C14' => esc_html__( 'C14 French Classical', 'chessgame-shizzle' ),
		'C15' => esc_html__( 'C15 French Winawer (Nimzovich) Variation', 'chessgame-shizzle' ),
		'C16' => esc_html__( 'C16 French Winawer, Advance Variation', 'chessgame-shizzle' ),
		'C17' => esc_html__( 'C17 French Winawer, Advance Variation', 'chessgame-shizzle' ),
		'C18' => esc_html__( 'C18 French Winawer, Advance Variation', 'chessgame-shizzle' ),
		'C19' => esc_html__( 'C19 French Winawer, Advance, 6...Ne7', 'chessgame-shizzle' ),
		'C20' => esc_html__( "C20 King's Pawn Game", 'chessgame-shizzle' ),
		'C21' => esc_html__( 'C21 Center Game (includes Danish Gambit)', 'chessgame-shizzle' ),
		'C22' => esc_html__( 'C22 Center Game', 'chessgame-shizzle' ),
		'C23' => esc_html__( "C23 Bishop's Opening", 'chessgame-shizzle' ),
		'C24' => esc_html__( "C24 Bishop's Opening, Berlin Defence", 'chessgame-shizzle' ),
		'C25' => esc_html__( 'C25 Vienna Game', 'chessgame-shizzle' ),
		'C26' => esc_html__( 'C26 Vienna Game, Falkbeer Variation', 'chessgame-shizzle' ),
		'C27' => esc_html__( 'C27 Vienna Game', 'chessgame-shizzle' ),
		'C28' => esc_html__( 'C28 Vienna Game', 'chessgame-shizzle' ),
		'C29' => esc_html__( 'C29 Vienna Gambit, Kaufmann Variation including Würzburger Trap', 'chessgame-shizzle' ),
		'C30' => esc_html__( "C30 King's Gambit", 'chessgame-shizzle' ),
		'C31' => esc_html__( "C31 King's Gambit Declined, Falkbeer and Nimzowitsch (3...c6) Countergambits", 'chessgame-shizzle' ),
		'C32' => esc_html__( "C32 King's Gambit Declined, Falkbeer, 5.dxe4", 'chessgame-shizzle' ),
		'C33' => esc_html__( "C33 King's Gambit Accepted", 'chessgame-shizzle' ),
		'C34' => esc_html__( "C34 King's Gambit Accepted, including Fischer Defence", 'chessgame-shizzle' ),
		'C35' => esc_html__( "C35 King's Gambit Accepted, Cunningham Defence", 'chessgame-shizzle' ),
		'C36' => esc_html__( "C36 King's Gambit Accepted, Abbazia Defence (Classical Defence, Modern Defence)", 'chessgame-shizzle' ),
		'C37' => esc_html__( "C37 King's Gambit Accepted, Quaade Gambit", 'chessgame-shizzle' ),
		'C38' => esc_html__( "C38 King's Gambit Accepted", 'chessgame-shizzle' ),
		'C39' => esc_html__( "C39 King's Gambit Accepted, Allgaier and Kieseritsky Gambits including Rice Gambit", 'chessgame-shizzle' ),
		'C40' => esc_html__( "C40 Irregular King's Knight", 'chessgame-shizzle' ),
		'C41' => esc_html__( 'C41 Philidor Defence', 'chessgame-shizzle' ),
		'C42' => esc_html__( "C42 Petrov's Defence, including Marshall Trap", 'chessgame-shizzle' ),
		'C43' => esc_html__( "C43 Petrov's Defence, Modern (Steinitz) Attack", 'chessgame-shizzle' ),
		'C44' => esc_html__( "C44 King's Knight/Ponziani/Scotch/Göring", 'chessgame-shizzle' ),
		'C45' => esc_html__( 'C45 Scotch Game', 'chessgame-shizzle' ),
		'C46' => esc_html__( 'C46 Three Knights including Halloween Gambit', 'chessgame-shizzle' ),
		'C47' => esc_html__( 'C47 Four Knights', 'chessgame-shizzle' ),
		'C48' => esc_html__( 'C48 Four Knights, Spanish Variation', 'chessgame-shizzle' ),
		'C49' => esc_html__( 'C49 Four Knights, Double Ruy Lopez', 'chessgame-shizzle' ),
		'C50' => esc_html__( 'C50 Italian Game', 'chessgame-shizzle' ),
		'C51' => esc_html__( 'C51 Evans Gambit', 'chessgame-shizzle' ),
		'C52' => esc_html__( 'C52 Evans Gambit with 4...Bxb4 5.c3 Ba5', 'chessgame-shizzle' ),
		'C53' => esc_html__( 'C53 Giuoco Piano', 'chessgame-shizzle' ),
		'C54' => esc_html__( 'C54 Giuoco Piano', 'chessgame-shizzle' ),
		'C55' => esc_html__( 'C55 Two Knights Defence', 'chessgame-shizzle' ),
		'C56' => esc_html__( 'C56 Two Knights Defence', 'chessgame-shizzle' ),
		'C57' => esc_html__( 'C57 Two Knights Defence, including the Fried Liver Attack', 'chessgame-shizzle' ),
		'C58' => esc_html__( 'C58 Two Knights Defence', 'chessgame-shizzle' ),
		'C59' => esc_html__( 'C59 Two Knights Defence', 'chessgame-shizzle' ),
		'C60' => esc_html__( 'C60 Spanish - Ruy Lopez, Unusual Black 3rd moves and 3...g6', 'chessgame-shizzle' ),
		'C61' => esc_html__( "C61 Spanish - Ruy Lopez, Bird's Defence", 'chessgame-shizzle' ),
		'C62' => esc_html__( 'C62 Spanish - Ruy Lopez, Old Steinitz Defence', 'chessgame-shizzle' ),
		'C63' => esc_html__( 'C63 Spanish - Ruy Lopez, Schliemann Defence', 'chessgame-shizzle' ),
		'C64' => esc_html__( 'C64 Spanish - Ruy Lopez, Classical (Cordel) Defence', 'chessgame-shizzle' ),
		'C65' => esc_html__( 'C65 Spanish - Ruy Lopez, Berlin Defence, including Mortimer Trap', 'chessgame-shizzle' ),
		'C66' => esc_html__( 'C66 Spanish - Ruy Lopez, Berlin Defence, 4.0-0 d6', 'chessgame-shizzle' ),
		'C67' => esc_html__( 'C67 Spanish - Ruy Lopez, Berlin Defence, Open Variation', 'chessgame-shizzle' ),
		'C68' => esc_html__( 'C68 Spanish - Ruy Lopez, Exchange Variation', 'chessgame-shizzle' ),
		'C69' => esc_html__( 'C69 Spanish - Ruy Lopez, Exchange Variation, 5.0-0', 'chessgame-shizzle' ),
		'C70' => esc_html__( 'C70 Spanish - Ruy Lopez', 'chessgame-shizzle' ),
		'C71' => esc_html__( "C71 Spanish - Ruy Lopez, Modern Steinitz Defence including Noah's Ark Trap", 'chessgame-shizzle' ),
		'C72' => esc_html__( 'C72 Spanish - Ruy Lopez, Modern Steinitz Defence 5.0-0', 'chessgame-shizzle' ),
		'C73' => esc_html__( 'C73 Spanish - Ruy Lopez, Modern Steinitz Defence, Richter Variation', 'chessgame-shizzle' ),
		'C74' => esc_html__( 'C74 Spanish - Ruy Lopez, Modern Steinitz Defence', 'chessgame-shizzle' ),
		'C75' => esc_html__( 'C75 Spanish - Ruy Lopez, Modern Steinitz Defence', 'chessgame-shizzle' ),
		'C76' => esc_html__( 'C76 Spanish - Ruy Lopez, Modern Steinitz Defence, Fianchetto (Bronstein) Variation', 'chessgame-shizzle' ),
		'C77' => esc_html__( 'C77 Spanish - Ruy Lopez, Morphy Defence', 'chessgame-shizzle' ),
		'C78' => esc_html__( 'C78 Spanish - Ruy Lopez, 5.0-0', 'chessgame-shizzle' ),
		'C79' => esc_html__( 'C79 Spanish - Ruy Lopez, Steinitz Defence Deferred (Russian Defence)', 'chessgame-shizzle' ),
		'C80' => esc_html__( 'C80 Spanish - Ruy Lopez, Open (Tarrasch) Defence', 'chessgame-shizzle' ),
		'C81' => esc_html__( 'C81 Spanish - Ruy Lopez, Open, Howell Attack', 'chessgame-shizzle' ),
		'C82' => esc_html__( 'C82 Spanish - Ruy Lopez, Open, 9.c3', 'chessgame-shizzle' ),
		'C83' => esc_html__( 'C83 Spanish - Ruy Lopez, Open, Classical Defence', 'chessgame-shizzle' ),
		'C84' => esc_html__( 'C84 Spanish - Ruy Lopez, Closed', 'chessgame-shizzle' ),
		'C85' => esc_html__( 'C85 Spanish - Ruy Lopez, Exchange Variation Doubly Deferred (DERLD)', 'chessgame-shizzle' ),
		'C86' => esc_html__( 'C86 Spanish - Ruy Lopez, Worrall Attack', 'chessgame-shizzle' ),
		'C87' => esc_html__( 'C87 Spanish - Ruy Lopez, Closed, Averbakh Variation', 'chessgame-shizzle' ),
		'C88' => esc_html__( 'C88 Spanish - Ruy Lopez, Closed', 'chessgame-shizzle' ),
		'C89' => esc_html__( 'C89 Spanish - Ruy Lopez, Marshall Counterattack', 'chessgame-shizzle' ),
		'C90' => esc_html__( 'C90 Spanish - Ruy Lopez, Closed, 7...d6', 'chessgame-shizzle' ),
		'C91' => esc_html__( 'C91 Spanish - Ruy Lopez, Closed, 9.d4', 'chessgame-shizzle' ),
		'C92' => esc_html__( 'C92 Spanish - Ruy Lopez, Closed, 9.h3', 'chessgame-shizzle' ),
		'C93' => esc_html__( 'C93 Spanish - Ruy Lopez, Closed, Smyslov Defence', 'chessgame-shizzle' ),
		'C94' => esc_html__( 'C94 Spanish - Ruy Lopez, Closed, Breyer Defence, 10.d3', 'chessgame-shizzle' ),
		'C95' => esc_html__( 'C95 Spanish - Ruy Lopez, Closed, Breyer Defence, 10.d4', 'chessgame-shizzle' ),
		'C96' => esc_html__( 'C96 Spanish - Ruy Lopez, Closed, 8...Na5', 'chessgame-shizzle' ),
		'C97' => esc_html__( 'C97 Spanish - Ruy Lopez, Closed, Chigorin Defence', 'chessgame-shizzle' ),
		'C98' => esc_html__( 'C98 Spanish - Ruy Lopez, Closed, Chigorin, 12...Nc6', 'chessgame-shizzle' ),
		'C99' => esc_html__( 'C99 Spanish - Ruy Lopez, Closed, Chigorin, 12...cxd4', 'chessgame-shizzle' ),
	);
	return $codes;
}


/*
 * Fetch all the ECO codes starting with D.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_array_openingcodes_d() {
	$codes = array(
		'D00' => esc_html__( "D00 Queen's Pawn Game (including Blackmar–Diemer Gambit, Halosar Trap and others)", 'chessgame-shizzle' ),
		'D01' => esc_html__( 'D01 Richter–Veresov Attack', 'chessgame-shizzle' ),
		'D02' => esc_html__( "D02 Queen's Pawn Game, 2.Nf3 (including the London System)", 'chessgame-shizzle' ),
		'D03' => esc_html__( 'D03 Torre Attack, Tartakower Variation', 'chessgame-shizzle' ),
		'D04' => esc_html__( "D04 Queen's Pawn Game, Colle System", 'chessgame-shizzle' ),
		'D05' => esc_html__( "D05 Queen's Pawn Game, Zukertort Variation (including Colle system)", 'chessgame-shizzle' ),
		'D06' => esc_html__( "D06 Queen's Gambit Declined (including the Baltic Defence, Marshall Defence and Symmetrical Defence)", 'chessgame-shizzle' ),
		'D07' => esc_html__( "D07 Queen's Gambit Declined Chigorin Defense", 'chessgame-shizzle' ),
		'D08' => esc_html__( "D08 Queen's Gambit Declined Albin Counter Gambit and Lasker Trap", 'chessgame-shizzle' ),
		'D09' => esc_html__( "D09 Queen's Gambit Declined Albin Counter Gambit, 5.g3", 'chessgame-shizzle' ),
		'D10' => esc_html__( "D10 Queen's Gambit Declined Slav Defence", 'chessgame-shizzle' ),
		'D11' => esc_html__( "D11 Queen's Gambit Declined Slav Defence, 3.Nf3", 'chessgame-shizzle' ),
		'D12' => esc_html__( "D12 Queen's Gambit Declined Slav Defence, 4.e3 Bf5", 'chessgame-shizzle' ),
		'D13' => esc_html__( "D13 Queen's Gambit Declined Slav Defence, Exchange Variation", 'chessgame-shizzle' ),
		'D14' => esc_html__( "D14 Queen's Gambit Declined Slav Defence, Exchange Variation", 'chessgame-shizzle' ),
		'D15' => esc_html__( "D15 Queen's Gambit Declined Slav Defence, 4.Nc3", 'chessgame-shizzle' ),
		'D16' => esc_html__( "D16 Queen's Gambit Declined Slav accepted, Alapin Variation", 'chessgame-shizzle' ),
		'D17' => esc_html__( "D17 Queen's Gambit Declined Slav Defence, Czech Defence", 'chessgame-shizzle' ),
		'D18' => esc_html__( "D18 Queen's Gambit Declined Dutch Variation", 'chessgame-shizzle' ),
		'D19' => esc_html__( "D19 Queen's Gambit Declined Dutch Variation", 'chessgame-shizzle' ),
		'D20' => esc_html__( "D20 Queen's Gambit Accepted", 'chessgame-shizzle' ),
		'D21' => esc_html__( "D21 Queen's Gambit Accepted, 3.Nf3", 'chessgame-shizzle' ),
		'D22' => esc_html__( "D22 Queen's Gambit Accepted, Alekhine Defence", 'chessgame-shizzle' ),
		'D23' => esc_html__( "D23 Queen's Gambit Accepted", 'chessgame-shizzle' ),
		'D24' => esc_html__( "D24 Queen's Gambit Accepted, 4.Nc3", 'chessgame-shizzle' ),
		'D25' => esc_html__( "D25 Queen's Gambit Accepted, 4.e3", 'chessgame-shizzle' ),
		'D26' => esc_html__( "D26 Queen's Gambit Accepted, Classical Variation", 'chessgame-shizzle' ),
		'D27' => esc_html__( "D27 Queen's Gambit Accepted, Classical Variation", 'chessgame-shizzle' ),
		'D28' => esc_html__( "D28 Queen's Gambit Accepted, Classical Variation 7.Qe2", 'chessgame-shizzle' ),
		'D29' => esc_html__( "D29 Queen's Gambit Accepted, Classical Variation 8...Bb7", 'chessgame-shizzle' ),
		'D30' => esc_html__( "D30 Queen's Gambit Declined, Orthodox Defence", 'chessgame-shizzle' ),
		'D31' => esc_html__( "D31 Queen's Gambit Declined, 3.Nc3, Noteboom Variation", 'chessgame-shizzle' ),
		'D32' => esc_html__( "D32 Queen's Gambit Declined Tarrasch", 'chessgame-shizzle' ),
		'D33' => esc_html__( "D33 Queen's Gambit Declined Tarrasch, Schlechter–Rubinstein System", 'chessgame-shizzle' ),
		'D34' => esc_html__( "D34 Queen's Gambit Declined Tarrasch, 7...Be7", 'chessgame-shizzle' ),
		'D35' => esc_html__( "D35 Queen's Gambit Declined, Exchange Variation", 'chessgame-shizzle' ),
		'D36' => esc_html__( "D36 Queen's Gambit Declined, Exchange, positional line, 6.Qc2", 'chessgame-shizzle' ),
		'D37' => esc_html__( "D37 Queen's Gambit Declined, 4.Nf3", 'chessgame-shizzle' ),
		'D38' => esc_html__( "D38 Queen's Gambit Declined Ragozin", 'chessgame-shizzle' ),
		'D39' => esc_html__( "D39 Queen's Gambit Declined Ragozin, Vienna Variation", 'chessgame-shizzle' ),
		'D40' => esc_html__( "D40 Queen's Gambit Declined Semi-Tarrasch", 'chessgame-shizzle' ),
		'D41' => esc_html__( "D41 Queen's Gambit Declined Semi-Tarrasch, 5.cxd5", 'chessgame-shizzle' ),
		'D42' => esc_html__( "D42 Queen's Gambit Declined Semi-Tarrasch, 7.Bd3", 'chessgame-shizzle' ),
		'D43' => esc_html__( "D43 Queen's Gambit Declined Semi-Slav", 'chessgame-shizzle' ),
		'D44' => esc_html__( "D44 Queen's Gambit Declined Semi-Slav, 5.Bg5 dxc4", 'chessgame-shizzle' ),
		'D45' => esc_html__( "D45 Queen's Gambit Declined Semi-Slav, 5.e3", 'chessgame-shizzle' ),
		'D46' => esc_html__( "D46 Queen's Gambit Declined Semi-Slav, 6.Bd3", 'chessgame-shizzle' ),
		'D47' => esc_html__( "D47 Queen's Gambit Declined Semi-Slav, 7.Bc4", 'chessgame-shizzle' ),
		'D48' => esc_html__( "D48 Queen's Gambit Declined Meran, 8...a6", 'chessgame-shizzle' ),
		'D49' => esc_html__( "D49 Queen's Gambit Declined Meran, 11.Nxb5", 'chessgame-shizzle' ),
		'D50' => esc_html__( "D50 Queen's Gambit Declined, 4.Bg5", 'chessgame-shizzle' ),
		'D51' => esc_html__( "D51 Queen's Gambit Declined, 4.Bg5 Nbd7 (Cambridge Springs Defence and Elephant Trap)", 'chessgame-shizzle' ),
		'D52' => esc_html__( "D52 Queen's Gambit Declined", 'chessgame-shizzle' ),
		'D53' => esc_html__( "D53 Queen's Gambit Declined, 4.Bg5 Be7", 'chessgame-shizzle' ),
		'D54' => esc_html__( "D54 Queen's Gambit Declined, Anti-neo-Orthodox Variation", 'chessgame-shizzle' ),
		'D55' => esc_html__( "D55 Queen's Gambit Declined, 6.Nf3", 'chessgame-shizzle' ),
		'D56' => esc_html__( "D56 Queen's Gambit Declined Lasker Defense", 'chessgame-shizzle' ),
		'D57' => esc_html__( "D57 Queen's Gambit Declined Lasker Defense, Main line", 'chessgame-shizzle' ),
		'D58' => esc_html__( "D58 Queen's Gambit Declined Tartakower (Tartakower–Makogonov–Bondarevsky) System", 'chessgame-shizzle' ),
		'D59' => esc_html__( "D59 Queen's Gambit Declined Tartakower (Tartakower–Makogonov–Bondarevsky) System, 8.cxd5 Nxd5", 'chessgame-shizzle' ),
		'D60' => esc_html__( "D60 Queen's Gambit Declined Orthodox", 'chessgame-shizzle' ),
		'D61' => esc_html__( "D61 Queen's Gambit Declined Orthodox, Rubinstein Variation", 'chessgame-shizzle' ),
		'D62' => esc_html__( "D62 Queen's Gambit Declined Orthodox, 7.Qc2 c5, 8.cxd5 (Rubinstein)", 'chessgame-shizzle' ),
		'D63' => esc_html__( "D63 Queen's Gambit Declined Orthodox, 7.Rc1", 'chessgame-shizzle' ),
		'D64' => esc_html__( "D64 Queen's Gambit Declined Orthodox, Rubinstein Attack (with Rc1)", 'chessgame-shizzle' ),
		'D65' => esc_html__( "D65 Queen's Gambit Declined Orthodox, Rubinstein Attack, Main line", 'chessgame-shizzle' ),
		'D66' => esc_html__( "D66 Queen's Gambit Declined Orthodox, Bd3 line including Rubinstein Trap", 'chessgame-shizzle' ),
		'D67' => esc_html__( "D67 Queen's Gambit Declined Orthodox, Bd3 line, Capablanca freeing manoeuvre", 'chessgame-shizzle' ),
		'D68' => esc_html__( "D68 Queen's Gambit Declined Orthodox, Classical Variation", 'chessgame-shizzle' ),
		'D69' => esc_html__( "D69 Queen's Gambit Declined Orthodoxe, Classical, 13.dxe5", 'chessgame-shizzle' ),
		'D70' => esc_html__( 'D70 Neo-Grünfeld', 'chessgame-shizzle' ),
		'D71' => esc_html__( 'D71 Neo-Grünfeld, 5.cxd5', 'chessgame-shizzle' ),
		'D72' => esc_html__( 'D72 Neo-Grünfeld, 5.cxd5, Main line', 'chessgame-shizzle' ),
		'D73' => esc_html__( 'D73 Neo-Grünfeld, 5.Nf3', 'chessgame-shizzle' ),
		'D74' => esc_html__( 'D74 Neo-Grünfeld, 6.cxd5 Nxd5, 7.0-0', 'chessgame-shizzle' ),
		'D75' => esc_html__( 'D75 Neo-Grünfeld, 6.cxd5 Nxd5, 7.0-0 c5, 8.Nc3', 'chessgame-shizzle' ),
		'D76' => esc_html__( 'D76 Neo-Grünfeld, 6.cxd5 Nxd5, 7.0-0 Nb6', 'chessgame-shizzle' ),
		'D77' => esc_html__( 'D77 Neo-Grünfeld, 6.0-0', 'chessgame-shizzle' ),
		'D78' => esc_html__( 'D78 Neo-Grünfeld, 6.0-0 c6', 'chessgame-shizzle' ),
		'D79' => esc_html__( 'D79 Neo-Grünfeld, 6.0-0, Main line', 'chessgame-shizzle' ),
		'D80' => esc_html__( 'D80 Grünfeld', 'chessgame-shizzle' ),
		'D81' => esc_html__( 'D81 Grünfeld, Russian Variation', 'chessgame-shizzle' ),
		'D82' => esc_html__( 'D82 Grünfeld, 4.Bf4', 'chessgame-shizzle' ),
		'D83' => esc_html__( 'D83 Grünfeld Gambit', 'chessgame-shizzle' ),
		'D84' => esc_html__( 'D84 Grünfeld, Gambit accepted', 'chessgame-shizzle' ),
		'D85' => esc_html__( 'D85 Grünfeld, Nadanian Variation', 'chessgame-shizzle' ),
		'D86' => esc_html__( 'D86 Grünfeld, Exchange, Classical Variation', 'chessgame-shizzle' ),
		'D87' => esc_html__( 'D87 Grünfeld, Exchange, Spassky Variation', 'chessgame-shizzle' ),
		'D88' => esc_html__( 'D88 Grünfeld, Spassky Variation, Main line, 10...cxd4, 11.cxd4', 'chessgame-shizzle' ),
		'D89' => esc_html__( 'D89 Grünfeld, Spassky Variation, Main line, 13.Bd3', 'chessgame-shizzle' ),
		'D90' => esc_html__( 'D90 Grünfeld, Three Knights Variation', 'chessgame-shizzle' ),
		'D91' => esc_html__( 'D91 Grünfeld, Three Knights Variation', 'chessgame-shizzle' ),
		'D92' => esc_html__( 'D92 Grünfeld, 5.Bf4', 'chessgame-shizzle' ),
		'D93' => esc_html__( 'D93 Grünfeld with 5.Bf4 0-0 6.e3', 'chessgame-shizzle' ),
		'D94' => esc_html__( 'D94 Grünfeld, 5.e3', 'chessgame-shizzle' ),
		'D95' => esc_html__( 'D95 Grünfeld with 5.e3 0-0 6.Qb3', 'chessgame-shizzle' ),
		'D96' => esc_html__( 'D96 Grünfeld, Russian Variation', 'chessgame-shizzle' ),
		'D97' => esc_html__( 'D97 Grünfeld, Russian Variation with 7.e4', 'chessgame-shizzle' ),
		'D98' => esc_html__( 'D98 Grünfeld, Russian, Smyslov Variation', 'chessgame-shizzle' ),
		'D99' => esc_html__( 'D99 Grünfeld, Smyslov, Main line', 'chessgame-shizzle' ),
	);
	return $codes;
}


/*
 * Fetch all the ECO codes starting with E.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_get_array_openingcodes_e() {
	$codes = array(
		'E00' => esc_html__( "E00 Queen's Pawn Game (including Neo-Indian Attack, Trompowsky Attack, Catalan Opening and others)", 'chessgame-shizzle' ),
		'E01' => esc_html__( 'E01 Catalan, Closed', 'chessgame-shizzle' ),
		'E02' => esc_html__( 'E02 Catalan, Open, 5.Qa4', 'chessgame-shizzle' ),
		'E03' => esc_html__( 'E03 Catalan, Open, Alekhine Variation', 'chessgame-shizzle' ),
		'E04' => esc_html__( 'E04 Catalan, Open, 5.Nf3', 'chessgame-shizzle' ),
		'E05' => esc_html__( 'E05 Catalan, Open, Classical line', 'chessgame-shizzle' ),
		'E06' => esc_html__( 'E06 Catalan, Closed, 5.Nf3', 'chessgame-shizzle' ),
		'E07' => esc_html__( 'E07 Catalan, Closed, 6...Nbd7', 'chessgame-shizzle' ),
		'E08' => esc_html__( 'E08 Catalan, Closed, 7.Qc2', 'chessgame-shizzle' ),
		'E09' => esc_html__( 'E09 Catalan, Closed, Main line', 'chessgame-shizzle' ),
		'E10' => esc_html__( 'E10 Blumenfeld/Irregular Indian', 'chessgame-shizzle' ),
		'E11' => esc_html__( 'E11 Bogo-Indian Defense', 'chessgame-shizzle' ),
		'E12' => esc_html__( "E12 Queen's Indian", 'chessgame-shizzle' ),
		'E13' => esc_html__( "E13 Queen's Indian, 4.Nc3, Main line", 'chessgame-shizzle' ),
		'E14' => esc_html__( "E14 Queen's Indian, 4.e3", 'chessgame-shizzle' ),
		'E15' => esc_html__( "E15 Queen's Indian, 4.g3", 'chessgame-shizzle' ),
		'E16' => esc_html__( "E16 Queen's Indian, Capablanca Variation", 'chessgame-shizzle' ),
		'E17' => esc_html__( "E17 Queen's Indian, 5.Bg2 Be7", 'chessgame-shizzle' ),
		'E18' => esc_html__( "E18 Queen's Indian, Old Main line, 7.Nc3", 'chessgame-shizzle' ),
		'E19' => esc_html__( "E19 Queen's Indian, Old Main line, 9.Qxc3", 'chessgame-shizzle' ),
		'E20' => esc_html__( 'E20 Nimzo-Indian', 'chessgame-shizzle' ),
		'E21' => esc_html__( 'E21 Nimzo-Indian, Three Knights Variation', 'chessgame-shizzle' ),
		'E22' => esc_html__( 'E22 Nimzo-Indian, Spielmann Variation', 'chessgame-shizzle' ),
		'E23' => esc_html__( 'E23 Nimzo-Indian, Spielmann, 4...c5, 5.dxc5 Nc6', 'chessgame-shizzle' ),
		'E24' => esc_html__( 'E24 Nimzo-Indian, Sämisch Variation', 'chessgame-shizzle' ),
		'E25' => esc_html__( 'E25 Nimzo-Indian, Sämisch Variation, Keres Variation', 'chessgame-shizzle' ),
		'E26' => esc_html__( 'E26 Nimzo-Indian, Sämisch Variation, 4.a3 Bxc3+ 5.bxc3 c5 6.e3', 'chessgame-shizzle' ),
		'E27' => esc_html__( 'E27 Nimzo-Indian, Sämisch Variation, 5...0-0', 'chessgame-shizzle' ),
		'E28' => esc_html__( 'E28 Nimzo-Indian, Sämisch Variation, 6.e3', 'chessgame-shizzle' ),
		'E29' => esc_html__( 'E29 Nimzo-Indian, Sämisch Variation, Main line', 'chessgame-shizzle' ),
		'E30' => esc_html__( 'E30 Nimzo-Indian, Leningrad Variation', 'chessgame-shizzle' ),
		'E31' => esc_html__( 'E31 Nimzo-Indian, Leningrad Variation, Main line', 'chessgame-shizzle' ),
		'E32' => esc_html__( 'E32 Nimzo-Indian, Classical Variation, 4.Qc2', 'chessgame-shizzle' ),
		'E33' => esc_html__( 'E33 Nimzo-Indian, Classical Variation, 4...Nc6', 'chessgame-shizzle' ),
		'E34' => esc_html__( 'E34 Nimzo-Indian, Classical, Noa Variation, 4...d5', 'chessgame-shizzle' ),
		'E35' => esc_html__( 'E35 Nimzo-Indian, Classical, Noa Variation, 5.cxd5 exd5', 'chessgame-shizzle' ),
		'E36' => esc_html__( 'E36 Nimzo-Indian, Classical, Noa Variation, 5.a3', 'chessgame-shizzle' ),
		'E37' => esc_html__( 'E37 Nimzo-Indian, Classical, Noa Variation, Main line, 7.Qc2', 'chessgame-shizzle' ),
		'E38' => esc_html__( 'E38 Nimzo-Indian, Classical, 4...c5', 'chessgame-shizzle' ),
		'E39' => esc_html__( 'E39 Nimzo-Indian, Classical, Pirc Variation', 'chessgame-shizzle' ),
		'E40' => esc_html__( 'E40 Nimzo-Indian, 4.e3', 'chessgame-shizzle' ),
		'E41' => esc_html__( 'E41 Nimzo-Indian, 4.e3 c5', 'chessgame-shizzle' ),
		'E42' => esc_html__( 'E42 Nimzo-Indian, 4.e3 c5, 5.Ne2 (Rubinstein)', 'chessgame-shizzle' ),
		'E43' => esc_html__( 'E43 Nimzo-Indian, Fischer Variation', 'chessgame-shizzle' ),
		'E44' => esc_html__( 'E44 Nimzo-Indian, Fischer Variation, 5.Ne2', 'chessgame-shizzle' ),
		'E45' => esc_html__( 'E45 Nimzo-Indian, 4.e3, Bronstein (Byrne) Variation', 'chessgame-shizzle' ),
		'E46' => esc_html__( 'E46 Nimzo-Indian, 4.e3 0-0', 'chessgame-shizzle' ),
		'E47' => esc_html__( 'E47 Nimzo-Indian, 4.e3 0-0, 5.Bd3', 'chessgame-shizzle' ),
		'E48' => esc_html__( 'E48 Nimzo-Indian, 4.e3 0-0, 5.Bd3 d5', 'chessgame-shizzle' ),
		'E49' => esc_html__( 'E49 Nimzo-Indian, 4.e3, Botvinnik System', 'chessgame-shizzle' ),
		'E50' => esc_html__( 'E50 Nimzo-Indian, 4.e3 0-0, 5.Nf3, without ...d5', 'chessgame-shizzle' ),
		'E51' => esc_html__( 'E51 Nimzo-Indian, 4.e3 0-0, 5.Nf3 d5', 'chessgame-shizzle' ),
		'E52' => esc_html__( 'E52 Nimzo-Indian, 4.e3, Main line with ...b6', 'chessgame-shizzle' ),
		'E53' => esc_html__( 'E53 Nimzo-Indian, 4.e3, Main line with ...c5', 'chessgame-shizzle' ),
		'E54' => esc_html__( 'E54 Nimzo-Indian, 4.e3, Gligoric System with 7...dxc4', 'chessgame-shizzle' ),
		'E55' => esc_html__( 'E55 Nimzo-Indian, 4.e3, Gligoric System, Bronstein Variation', 'chessgame-shizzle' ),
		'E56' => esc_html__( 'E56 Nimzo-Indian, 4.e3, Main line with 7...Nc6', 'chessgame-shizzle' ),
		'E57' => esc_html__( 'E57 Nimzo-Indian, 4.e3, Main line with 8...dxc4 and 9...Bxc4 cxd4', 'chessgame-shizzle' ),
		'E58' => esc_html__( 'E58 Nimzo-Indian, 4.e3, Main line with 8...Bxc3', 'chessgame-shizzle' ),
		'E59' => esc_html__( 'E59 Nimzo-Indian, 4.e3, Main line', 'chessgame-shizzle' ),
		'E60' => esc_html__( "E60 King's Indian", 'chessgame-shizzle' ),
		'E61' => esc_html__( "E61 King's Indian, 3.Nc3", 'chessgame-shizzle' ),
		'E62' => esc_html__( "E62 King's Indian, Fianchetto Variation", 'chessgame-shizzle' ),
		'E63' => esc_html__( "E63 King's Indian, Fianchetto, Panno Variation", 'chessgame-shizzle' ),
		'E64' => esc_html__( "E64 King's Indian, Fianchetto, Yugoslav System", 'chessgame-shizzle' ),
		'E65' => esc_html__( "E65 King's Indian, Yugoslav, 7.0-0", 'chessgame-shizzle' ),
		'E66' => esc_html__( "E66 King's Indian, Fianchetto, Yugoslav Panno", 'chessgame-shizzle' ),
		'E67' => esc_html__( "E67 King's Indian, Fianchetto with ...Nd7", 'chessgame-shizzle' ),
		'E68' => esc_html__( "E68 King's Indian, Fianchetto, Classical Variation, 8.e4", 'chessgame-shizzle' ),
		'E69' => esc_html__( "E69 King's Indian, Fianchetto, Classical Main line", 'chessgame-shizzle' ),
		'E70' => esc_html__( "E70 King's Indian, Accelerated Averbakh Variation", 'chessgame-shizzle' ),
		'E71' => esc_html__( "E71 King's Indian, Makogonov System (5.h3)", 'chessgame-shizzle' ),
		'E72' => esc_html__( "E72 King's Indian with e4 and g3", 'chessgame-shizzle' ),
		'E73' => esc_html__( "E73 King's Indian, Averbakh, 5.Be2", 'chessgame-shizzle' ),
		'E74' => esc_html__( "E74 King's Indian, Averbakh, 6...c5", 'chessgame-shizzle' ),
		'E75' => esc_html__( "E75 King's Indian, Averbakh, Main line", 'chessgame-shizzle' ),
		'E76' => esc_html__( "E76 King's Indian, Four Pawns Attack", 'chessgame-shizzle' ),
		'E77' => esc_html__( "E77 King's Indian, Four Pawns Attack, 6.Be2", 'chessgame-shizzle' ),
		'E78' => esc_html__( "E78 King's Indian, Four Pawns Attack, with Be2 and Nf3", 'chessgame-shizzle' ),
		'E79' => esc_html__( "E79 King's Indian, Four Pawns Attack, Main line", 'chessgame-shizzle' ),
		'E80' => esc_html__( "E80 King's Indian, Sämisch Variation", 'chessgame-shizzle' ),
		'E81' => esc_html__( "E81 King's Indian, Sämisch, 5...0-0", 'chessgame-shizzle' ),
		'E82' => esc_html__( "E82 King's Indian, Sämisch, Double Fianchetto Variation", 'chessgame-shizzle' ),
		'E83' => esc_html__( "E83 King's Indian, Sämisch, 6...Nc6 (Panno Variation)", 'chessgame-shizzle' ),
		'E84' => esc_html__( "E84 King's Indian, Sämisch, Panno Main line", 'chessgame-shizzle' ),
		'E85' => esc_html__( "E85 King's Indian, Sämisch, Orthodox Variation", 'chessgame-shizzle' ),
		'E86' => esc_html__( "E86 King's Indian, Sämisch, Orthodox, 7.Nge2 c6", 'chessgame-shizzle' ),
		'E87' => esc_html__( "E87 King's Indian, Sämisch, Orthodox, 7.d5", 'chessgame-shizzle' ),
		'E88' => esc_html__( "E88 King's Indian, Sämisch, Orthodox, 7.d5 c6", 'chessgame-shizzle' ),
		'E89' => esc_html__( "E89 King's Indian, Sämisch, Orthodox Main line", 'chessgame-shizzle' ),
		'E90' => esc_html__( "E90 King's Indian, 5.Nf3", 'chessgame-shizzle' ),
		'E91' => esc_html__( "E91 King's Indian, 6.Be2", 'chessgame-shizzle' ),
		'E92' => esc_html__( "E92 King's Indian, Classical Variation", 'chessgame-shizzle' ),
		'E93' => esc_html__( "E93 King's Indian, Petrosian System, Main line", 'chessgame-shizzle' ),
		'E94' => esc_html__( "E94 King's Indian, Orthodox Variation", 'chessgame-shizzle' ),
		'E95' => esc_html__( "E95 King's Indian, Orthodox, 7...Nbd7, 8.Re1", 'chessgame-shizzle' ),
		'E96' => esc_html__( "E96 King's Indian, Orthodox, 7...Nbd7, Main line", 'chessgame-shizzle' ),
		'E97' => esc_html__( "E97 King's Indian, Orthodox, Aronin–Taimanov Variation (Yugoslav Attack / Mar del Plata Variation)", 'chessgame-shizzle' ),
		'E98' => esc_html__( "E98 King's Indian, Orthodox, Aronin–Taimanov, 9.Ne1", 'chessgame-shizzle' ),
		'E99' => esc_html__( "E99 King's Indian, Orthodox, Aronin–Taimanov, Main", 'chessgame-shizzle' ),
	);
	return $codes;
}
