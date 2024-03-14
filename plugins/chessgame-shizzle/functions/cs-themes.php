<?php

/*
 * Give list of piecethemes to use.
 *
 * @since 1.0.3
 *
 * @return array The list of piecethemes.
 */
function chessgame_shizzle_get_piecethemes() {
	$piecethemes = array(
		'adventurer' => array(
			'name' => 'adventurer',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/adventurer/179',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/adventurer/179',
		),
		'alfonso' => array(
			'name' => 'alfonso',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/alfonso/165',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/alfonso/165',
		),
		'alpha' => array(
			'name' => 'alpha',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/alpha/80',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/alpha/80',
		),
		'beyer' => array(
			'name' => 'beyer',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/beyer/250',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/beyer/250',
		),
		'case' => array(
			'name' => 'case',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/case/46',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/case/46',
		),
		'cases' => array(
			'name' => 'cases',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/cases/178',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/cases/178',
		),
		'chesscom' => array(
			'name' => 'chesscom',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/chesscom/40',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/chesscom/40',
		),
		'condal' => array(
			'name' => 'condal',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/condal/177',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/condal/177',
		),
		'fantasy' => array(
			'name' => 'fantasy',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/fantasy/102',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/fantasy/102',
		),
		'harlequin' => array(
			'name' => 'harlequin',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/harlequin/176',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/harlequin/176',
		),
		'kingdom' => array(
			'name' => 'kingdom',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/kingdom/171',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/kingdom/171',
		),
		'leipzig' => array(
			'name' => 'leipzig',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/leipzig/179',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/leipzig/179',
		),
		'lichess' => array(
			'name' => 'lichess',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/lichess/64',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/lichess/64',
		),
		'line' => array(
			'name' => 'line',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/line/179',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/line/179',
		),
		'lucena' => array(
			'name' => 'lucena',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/lucena/180',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/lucena/180',
		),
		'magnetic' => array(
			'name' => 'magnetic',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/magnetic/178',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/magnetic/178',
		),
		'mark' => array(
			'name' => 'mark',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/mark/160',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/mark/160',
		),
		'marroquin' => array(
			'name' => 'marroquin',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/marroquin/188',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/marroquin/188',
		),
		'maya' => array(
			'name' => 'maya',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/maya/179',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/maya/179',
		),
		'mediaeval' => array(
			'name' => 'mediaeval',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/mediaeval/179',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/mediaeval/179',
		),
		'merida' => array(
			'name' => 'merida',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/merida/171',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/merida/171',
		),
		'motif' => array(
			'name' => 'motif',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/motif/168',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/motif/168',
		),
		'smart' => array(
			'name' => 'smart',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/smart/102',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/smart/102',
		),
		'uscf' => array(
			'name' => 'uscf',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/uscf/80',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/uscf/80',
		),
		'usual' => array(
			'name' => 'usual',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/usual/102',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/usual/102',
		),
		'wikipedia' => array(
			'name' => 'wikipedia',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/wikipedia/80',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/wikipedia/80',
		),
		'xboard' => array(
			'name' => 'xboard',
			'url'  => C_SHIZZLE_URL . '/thirdparty/piecethemes/xboard/100',
			'dir'  => C_SHIZZLE_DIR . '/thirdparty/piecethemes/xboard/100',
		),
	);
	return $piecethemes;
}


/*
 * Get current or default piecetheme.
 *
 * @since 1.0.3
 *
 * @return string the current or default piecetheme.
 */
function chessgame_shizzle_get_piecetheme() {
	$piecethemes = chessgame_shizzle_get_piecethemes();
	$option = get_option( 'chessgame_shizzle-piecetheme', 'alpha' );
	if ( isset( $piecethemes[$option] ) && isset( $piecethemes[$option]['name'] ) ) {
		return $piecethemes[$option]['name'];
	}
	return 'alpha';
}


/*
 * Get url of current or default piecetheme.
 *
 * @since 1.0.3
 *
 * @return string the url of current or default piecetheme.
 */
function chessgame_shizzle_get_piecetheme_url() {
	$piecethemes = chessgame_shizzle_get_piecethemes();
	$piecetheme = chessgame_shizzle_get_piecetheme();
	if ( isset( $piecethemes[$piecetheme] ) && isset( $piecethemes[$piecetheme]['url'] ) ) {
		return $piecethemes[$piecetheme]['url'];
	}
	$default = C_SHIZZLE_URL . '/thirdparty/piecethemes/alpha/80';
	return $default;
}


/*
 * Get dir of current or default piecetheme.
 *
 * @since 1.1.0
 *
 * @return string the dir of current or default piecetheme.
 */
function chessgame_shizzle_get_piecetheme_dir() {
	$piecethemes = chessgame_shizzle_get_piecethemes();
	$piecetheme = chessgame_shizzle_get_piecetheme();
	if ( isset( $piecethemes[$piecetheme] ) && isset( $piecethemes[$piecetheme]['dir'] ) ) {
		return $piecethemes[$piecetheme]['dir'];
	}
	$default = C_SHIZZLE_DIR . '/thirdparty/piecethemes/alpha/80';
	return $default;
}


/*
 * Give list of boardthemes with full metadata to use.
 *
 * @since 1.1.0
 *
 * @return array The list of boardthemes.
 */
function chessgame_shizzle_get_boardthemes_full() {
	$boardthemes = array(
		'365chess' => array(
			'name'        => '365chess',
			'boardimage'  => '',
			'lightcolor'  => 'EEEEEE',
			'darkcolor'   => '7186B8',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'bamboo' => array(
			'name'        => 'bamboo',
			'boardimage'  => 'bamboo.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'beyer' => array(
			'name'        => 'beyer',
			'boardimage'  => '',
			'lightcolor'  => 'f4f4f4',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => 'beyer.png',
		),
		'blogger' => array(
			'name'        => 'blogger',
			'boardimage'  => '',
			'lightcolor'  => 'ede8d5',
			'darkcolor'   => 'cfcbb3',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'blue' => array(
			'name'        => 'blue',
			'boardimage'  => '',
			'lightcolor'  => 'dee3e6',
			'darkcolor'   => '8ca2ad',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'burl' => array(
			'name'        => 'burl',
			'boardimage'  => 'burl.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'chesscom_blue' => array(
			'name'        => 'chesscom_blue',
			'boardimage'  => '',
			'lightcolor'  => 'ededd9',
			'darkcolor'   => '4c6d92',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'chesscom_green' => array(
			'name'        => 'chesscom_green',
			'boardimage'  => '',
			'lightcolor'  => 'eeeed2',
			'darkcolor'   => '769656',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'chessonline' => array(
			'name'        => 'chessonline',
			'boardimage'  => '',
			'lightcolor'  => 'ffffff',
			'darkcolor'   => 'deb887',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'chesstempo_grey' => array(
			'name'        => 'chesstempo_grey',
			'boardimage'  => '',
			'lightcolor'  => 'cdcdcd',
			'darkcolor'   => 'aaaaaa',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'coffee_bean' => array(
			'name'        => 'coffee_bean',
			'boardimage'  => 'coffee_bean.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'ebony_pine' => array(
			'name'        => 'ebony_pine',
			'boardimage'  => 'ebony_pine.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'executive' => array(
			'name'        => 'executive',
			'boardimage'  => 'executive.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'falken' => array(
			'name'        => 'falken',
			'boardimage'  => '',
			'lightcolor'  => 'f4f4f4',
			'darkcolor'   => '636b6a',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'informator' => array(
			'name'        => 'informator',
			'boardimage'  => '',
			'lightcolor'  => 'eeeeee',
			'darkcolor'   => 'aaaaaa',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'joomla' => array(
			'name'        => 'joomla',
			'boardimage'  => '',
			'lightcolor'  => 'f6f6f6',
			'darkcolor'   => 'e0e0e0',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'lichess' => array(
			'name'        => 'lichess',
			'boardimage'  => '',
			'lightcolor'  => 'f0d9b5',
			'darkcolor'   => 'b58863',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'magazine' => array(
			'name'        => 'magazine',
			'boardimage'  => '',
			'lightcolor'  => 'ffffff',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => 'magazine.png',
		),
		'marble' => array(
			'name'        => 'marble',
			'boardimage'  => 'marble.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'marble_blue' => array(
			'name'        => 'marble_blue',
			'boardimage'  => '',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => 'marble_blue_white.png',
			'darkimage'   => 'marble_blue_black.png',
		),
		'marble_green' => array(
			'name'        => 'marble_green',
			'boardimage'  => 'marble_green.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'newinchess' => array(
			'name'        => 'newinchess',
			'boardimage'  => '',
			'lightcolor'  => 'ffffff',
			'darkcolor'   => 'ddd2bc',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'pgn4web' => array(
			'name'        => 'pgn4web',
			'boardimage'  => '',
			'lightcolor'  => 'eff4ec',
			'darkcolor'   => 'c6cec3',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'pgnviewer_yui' => array(
			'name'        => 'pgnviewer_yui',
			'boardimage'  => '',
			'lightcolor'  => 'ffffff',
			'darkcolor'   => 'edd6c2',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'shredderchess' => array(
			'name'        => 'shredderchess',
			'boardimage'  => '',
			'lightcolor'  => 'e9ecf0',
			'darkcolor'   => 'b5bdce',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'tornelo' => array(
			'name'        => 'tornelo',
			'boardimage'  => '',
			'lightcolor'  => 'dcecf2',
			'darkcolor'   => '85c9e2',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'wenge' => array(
			'name'        => 'wenge',
			'boardimage'  => 'wenge.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'wikipedia' => array(
			'name'        => 'wikipedia',
			'boardimage'  => '',
			'lightcolor'  => 'ffce9e',
			'darkcolor'   => 'd18b47',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'wood' => array(
			'name'        => 'wood',
			'boardimage'  => 'wood.jpg',
			'lightcolor'  => '',
			'darkcolor'   => '',
			'lightimage'  => '',
			'darkimage'   => '',
		),
		'zeit' => array(
			'name'        => 'zeit',
			'boardimage'  => '',
			'lightcolor'  => 'cccccc',
			'darkcolor'   => '6494b1',
			'lightimage'  => '',
			'darkimage'   => '',
		),
	);
	return $boardthemes;
}


/*
 * Get simple list of boardthemes.
 *
 * @since 1.0.3
 *
 * @return array the simple list of boardthemes.
 */
function chessgame_shizzle_get_boardthemes() {
	$boardthemes = array(
		'365chess',
		'bamboo',
		'beyer',
		'blogger',
		'blue',
		'burl',
		'chesscom_blue',
		'chesscom_green',
		'chessonline',
		'chesstempo_grey',
		'coffee_bean',
		'ebony_pine',
		'executive',
		'falken',
		'informator',
		'joomla',
		'lichess',
		'magazine',
		'marble',
		'marble_blue',
		'marble_green',
		'newinchess',
		'pgn4web',
		'pgnviewer_yui',
		'shredderchess',
		'tornelo',
		'wenge',
		'wikipedia',
		'wood',
		'zeit',
	);
	return $boardthemes;
}


/*
 * Get current or default boardtheme in full.
 *
 * @since 1.1.0
 *
 * @return array the current or default boardtheme in full.
 */
function chessgame_shizzle_get_boardtheme_full() {
	$boardthemes = chessgame_shizzle_get_boardthemes_full();
	$option = get_option( 'chessgame_shizzle-boardtheme', 'shredderchess' );
	foreach ( $boardthemes as $boardtheme ) {
		if ( $option === $boardtheme['name'] ) {
			return $boardtheme;
		}
	}
	$default = array(
		'name'        => 'shredderchess',
		'boardimage'  => '',
		'lightcolor'  => 'e9ecf0',
		'darkcolor'   => 'b5bdce',
		'lightimage'  => '',
		'darkimage'   => '',
	);
	return $default;
}


/*
 * Get current or default boardtheme.
 *
 * @since 1.0.3
 *
 * @return string the current or default boardtheme.
 */
function chessgame_shizzle_get_boardtheme() {
	$boardthemes = chessgame_shizzle_get_boardthemes();
	$option = get_option( 'chessgame_shizzle-boardtheme', 'shredderchess' );
	if ( in_array( $option, $boardthemes ) ) {
		return $option;
	}
	return 'shredderchess';
}


/*
 * Get class for current or default boardtheme.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_get_boardtheme_class() {
	$boardtheme = chessgame_shizzle_get_boardtheme();
	$class = 'cs-boardtheme-' . $boardtheme;
	return $class;
}
