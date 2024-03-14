<?php
/*
 * Specific hooks and functions for chessParser libraries.
 */


/*
 * Include chessParser libraries.
 *
 * @since 1.1.8
 */
function chessgame_shizzle_chessparser_include() {
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/Board0x88Config.php' );
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/CHESS_JSON.php' );
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/FenParser0x88.php' );
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/GameParser.php' );
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/MoveBuilder.php' );
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/PgnGameParser.php' );
	include_once( C_SHIZZLE_DIR . '/thirdparty/chessParser/PgnParser.php' );
}
