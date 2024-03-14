<?php
/*

//////////////////////////////////////////////////////////////////////////////
//                                                                          //
//  MFEN - My FEN Rendering Script - generate PNG image from FEN code       //
//                                                                          //
//  Original plugin: https://wordpress.org/plugins/wp-mfen/                 //
//  Current plugin: https://wordpress.org/plugins/chessgame-shizzle/        //
//                                                                          //
//     Copyright (C) 2008 - 2009, Michiel Sikma <michiel@sikma.org>         //
//     Copyright (C) 2018 - 2021, Marcel Pol <marcel@timelord.nl>           //
//                                                                          //
//  Permission is hereby granted, free of charge, to any person obtaining   //
//  a copy of this software and associated documentation files (the         //
//  "Software"), to deal in the Software without restriction, including     //
//  without limitation the rights to use, copy, modify, merge, publish,     //
//  distribute, sublicense, and/or sell copies of the Software, and to      //
//  permit persons to whom the Software is furnished to do so, subject to   //
//  the following conditions:                                               //
//                                                                          //
//  The above copyright notice and this permission notice shall be          //
//  included in all copies or substantial portions of the Software.         //
//                                                                          //
//  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,         //
//  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF      //
//  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  //
//  IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY    //
//  CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,    //
//  TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE       //
//  SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.                  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////

// Synopsis
// --------
//
// This script will render a PNG image of a chess position derived from a
// Forsyth-Edwards Notation ("FEN") string.	 It does so by first drawing a
// background image and then overlaying it with the images of the pieces.
// Finally, it (optionally) caches the image and sends it to the user.
//
// The FEN string is a standard method for describing a particular board
// position of a chess game. Its purpose is to provide all the necessary
// information to restart a game from a particular position (including the
// castling information, active color, amount of moves, etc., all of which
// are ignored by this script since they do not affect the display of the
// position).
//
// For more information on FEN strings in general, please see
// <http://en.wikipedia.org/wiki/Forsyth-Edwards_Notation>.
//
// This script accepts a number of variables that affect its output:
//
//     * fen: the actual FEN string that is rendered (default:
//     "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1").
//
//     * size: changes the dimensions of the output image (default:
//       "medium").	 Either a number up to 1024 or a standard size ("tiny",
//       "small", "medium", "large", "huge") can be given.
//
//     * l: uses a different color for the light squares
//       (default: "dfe3e8").
//
//     * d: uses a different color for the dark squares (default: "9da7bd").

*/


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


class MFEN {

	/* This class will show a PNG image of a chess position. It does this by
	first creating a background image and then overlaying the chess pieces
	over it. Keep in mind that you should create a directory for caching
	purposes in order to increase performance (at the cost of disk space). */

	private $ERROR_HAS_OCCURRED = false;
	private $ERROR              = '';

	/* The folder in which the piece images are located. */
	private $PIECE_FOLDER = 'pieces/';
	private $pieces       = array();

	/* The colors of the board. */
	private $COLOR_LIGHT = 'DFE3E8';
	private $COLOR_DARK  = '9DA8BD';

	/* The caching settings; turn caching off only when testing.
	When caching is on, rendered FEN positions will be saved as a file so
	that they don't have to be rendered again, saving processing time at the
	cost of disk space. It's recommended that it's kept on.
	Note: the '$CACHE_FOLDER' may contain a path, whereas '$CACHE_URI' may
	contain a URI. The '$CACHE_URI' is used when returning file locations. */
	private $USE_CACHING    = true;
	private $CACHE_FOLDER   = 'cache/cs-mfen';
	private $CACHE_URI      = 'cache/cs-mfen';


	/* '$MIME_TYPE' should be either "image/png" or "image/jpeg". The former
	is recommended.
	'$QUALITY' sets the quality of the image: when PNG is used, it ranges
	from 0 (no compression) to 9. When JPEG is used, it ranges from 0 (worst
	quality, smallest size) to 100.
	'$FILTERS' allows the filtering of PNG files; for more information, see
	PHP's GD documentation at <http://nl.php.net/gd>. It's ignored when
	JPEG is used. */
	private $MIME_TYPE  = 'image/png';
	private $EXTENSION  = '.png';
	private $QUALITY    = 9;
	private $FILTERS    = PNG_NO_FILTER;


	/* After rendering, the image will be resized. There are five preset sizes,
	"tiny" (128 px), "small" (256 px), "medium" (384 px), "large" (512 px),
	"huge" (1024 px). Use either a text or exact value. The maximum size
	is 1024 px. */
	private $SIZE = 'medium';
	private $PRESET_SIZES = array(
		'tiny'   => 128,
		'small'  => 256,
		'medium' => 384,
		'large'  => 512,
		'huge'   => 1024,
	);

	/* The default FEN string is used in case no FEN string is given. This string represents the starting position. */
	private $FEN = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR';

	/* The defaults will be saved upon construction. */
	private $DEFAULTS = array();


	public function __construct( $settings = array() ) {
		/* Set cache to wp-content path if we are using WordPress. */
		$this->set_cache();

		/* Save the defaults so they can be recalled later. */
		$this->save_defaults();

		/* Override any default settings that we have alternatives for. */
		$this->change_settings($settings);
	}


	/* Settings */

	public function save_defaults() {
		$vars = array( 'SIZE', 'FILTERS', 'QUALITY', 'EXTENSION', 'MIME_TYPE', 'COLOR_LIGHT', 'COLOR_DARK' );
		$defaults = array();
		foreach ($vars as $var) {
			$defaults[$var] = $this->$var;
		}
		$this->DEFAULTS = $defaults;
	}

	/* Overwrite an arbitrary amount of settings. */
	public function change_settings( $settings = array() ) {
		foreach ($settings as $key => $value) {
			$this->$key = $value;
		}
	}

	/* Change the settings back to what is defined in the class definition. */
	public function reset_settings_to_defaults() {
		$this->change_settings($this->DEFAULTS);
	}


	/* Setters */

	public function set_fen( $fen = null ) {
		if (isset($fen)) {
			$this->FEN = trim($fen);
		}
	}

	public function set_cache( $cache = null ) {
		if (isset($cache)) {
			$this->CACHE_FOLDER = trim($cache);
			$this->CACHE_URI    = trim($cache);
		} else if ( WP_CONTENT_DIR ) {
			$this->CACHE_FOLDER = WP_CONTENT_DIR . '/cache/cs-mfen/';
			$this->CACHE_URI    = WP_CONTENT_URL . '/cache/cs-mfen/';
		}
	}


	/* Getters */

	public function get_fen() {
		return $this->FEN;
	}

	public function get_description() {
		trigger_error( 'MFEN->get_description() is <strong>deprecated</strong> since version 1.1.8 with no replacement', E_USER_DEPRECATED );
		return '';
	}

	public function get_filename( $prefix = '' ) {
		$hash = md5(
				'fen_'     . $this->FEN .
				'size_'    . $this->SIZE .
				'mime_'    . $this->MIME_TYPE .
				'quality_' . $this->QUALITY .
				'filters_' . $this->FILTERS .
				'l_'       . $this->COLOR_LIGHT .
				'd_'       . $this->COLOR_DARK
			);

		if ( strlen( $prefix > 0 ) ) {
			$filename = $prefix . '-' . $hash;
			return $filename;
		}

		return $hash;
	}

	public function get_pieces() {
		$piece_folder = $this->PIECE_FOLDER;
		if ( function_exists('chessgame_shizzle_get_piecetheme_dir') ) {
			$piece_folder = chessgame_shizzle_get_piecetheme_dir() . '/';
			$this->PIECE_FOLDER = $piece_folder;
		}
		$this->pieces = array(
			'r' => $piece_folder . 'br.png',
			'n' => $piece_folder . 'bn.png',
			'b' => $piece_folder . 'bb.png',
			'q' => $piece_folder . 'bq.png',
			'k' => $piece_folder . 'bk.png',
			'p' => $piece_folder . 'bp.png',
			'R' => $piece_folder . 'wr.png',
			'N' => $piece_folder . 'wn.png',
			'B' => $piece_folder . 'wb.png',
			'Q' => $piece_folder . 'wq.png',
			'K' => $piece_folder . 'wk.png',
			'P' => $piece_folder . 'wp.png',
		);
		return $this->pieces;
	}

	public function get_size() {
		/* Returns an array containing the size in pixels and, if applicable,
		the preset size name as a string. */
		$sizes = array();
		$sizes[] = $this->SIZE;
		$sz = array_flip( $this->PRESET_SIZES );
		if ( isset( $sz["$this->SIZE"] ) ) {
			$sizes[] = $sz["$this->SIZE"];
		}
		return $sizes;
	}

	public function get_board_image() {

		// WordPress plugin, get our current theme in full.
		if ( function_exists('chessgame_shizzle_get_boardtheme_full') ) {
			$boardtheme = chessgame_shizzle_get_boardtheme_full();

			// We have a single image for the board.
			if ( isset($boardtheme['boardimage']) && strlen($boardtheme['boardimage']) > 0 ) {
				$filename = C_SHIZZLE_DIR . '/thirdparty/boardthemes/' . $boardtheme['boardimage'];
				if ( preg_match( '/^.*\.png$/', $filename ) ) {
					$board = imagecreatefrompng($filename); // piece at the wrong size.
				} else if ( preg_match( '/^.*\.jpg$/', $filename ) ) {
					$board = imagecreatefromjpeg($filename); // piece at the wrong size.
				}
				list($width, $height) = getimagesize($filename); // original sizes.

				$image = imagecreatetruecolor(1024, 1024); // real image at right size.

				// Resize $board into $image.
				imagecopyresampled($image, $board, 0, 0, 0, 0, 1024, 1024, $width, $height);
				imagedestroy($board);

				return $image;
			}

			// Use image for the fields, else use a color.
			if ( isset($boardtheme['lightimage']) && strlen($boardtheme['lightimage']) > 0 ) {
				$filename = C_SHIZZLE_DIR . '/thirdparty/boardthemes/' . $boardtheme['lightimage'];
				if ( preg_match( '/^.*\.png$/', $filename ) ) {
					$field = imagecreatefrompng($filename); // piece at the wrong size.
				} else if ( preg_match( '/^.*\.jpg$/', $filename ) ) {
					$field = imagecreatefromjpeg($filename); // piece at the wrong size.
				}
				list($width, $height) = getimagesize($filename); // original sizes.

				$lightfield = imagecreatetruecolor(128, 128); // real image at right size, black background.
				// Make it transparent.
				imagesavealpha($lightfield, true);
				$trans_colour = imagecolorallocatealpha($lightfield, 0, 0, 0, 127);
				imagefill($lightfield, 0, 0, $trans_colour);

				// Resize $board into $image.
				imagecopyresampled($lightfield, $field, 0, 0, 0, 0, 128, 128, $width, $height);
				imagedestroy($field);
			} else if ( isset($boardtheme['lightcolor']) && strlen($boardtheme['lightcolor']) > 0 ) {
				$this->COLOR_LIGHT = $boardtheme['lightcolor'];
			}

			if ( isset($boardtheme['darkimage']) && strlen($boardtheme['darkimage']) > 0 ) {
				$filename = C_SHIZZLE_DIR . '/thirdparty/boardthemes/' . $boardtheme['darkimage'];
				if ( preg_match( '/^.*\.png$/', $filename ) ) {
					$field = imagecreatefrompng($filename); // piece at the wrong size.
				} else if ( preg_match( '/^.*\.jpg$/', $filename ) ) {
					$field = imagecreatefromjpeg($filename); // piece at the wrong size.
				}
				list($width, $height) = getimagesize($filename); // original sizes.

				$darkfield = imagecreatetruecolor(128, 128); // real image at right size, black background.
				// Make it transparent.
				imagesavealpha($darkfield, true);
				$trans_colour = imagecolorallocatealpha($darkfield, 0, 0, 0, 127);
				imagefill($darkfield, 0, 0, $trans_colour);

				// Resize $board into $image.
				imagecopyresampled($darkfield, $field, 0, 0, 0, 0, 128, 128, $width, $height);
				imagedestroy($field);
			} else if ( isset($boardtheme['darkcolor']) && strlen($boardtheme['darkcolor']) > 0 ) {
				$this->COLOR_DARK = $boardtheme['darkcolor'];
			}
		}

		// No WordPress plugin, default colorfill, or the color set from WordPress.
		$image = imagecreatetruecolor(1024, 1024);
		if ( ! $image) {
			$this->_error(3, 'the script could not create an image object.');
			return;
		}
		// Make it base white.
		$w = $this->hex_to_rgb('ffffff');
		$white = imagecolorallocate($image, $w['r'], $w['g'], $w['b']);
		imagefilledrectangle( $image, 0, 0, 1024, 1024, $white );

		$l = $this->hex_to_rgb($this->COLOR_LIGHT);
		$d = $this->hex_to_rgb($this->COLOR_DARK);
		$lightcolor = imagecolorallocate($image, $l['r'], $l['g'], $l['b']);
		$darkcolor  = imagecolorallocate($image, $d['r'], $d['g'], $d['b']);

		/* Draw the background by simply making squares of 128 px in size, alternating between the light and dark colors. (Light first, of course) */
		for ($a = 0; $a < 8; ++$a) { // column
			for ($b = 0; $b < 8; ++$b) { // row
				$x0 = $a * 128;
				$y0 = $b * 128;
				$x1 = $x0 + 128;
				$y1 = $y0 + 128;

				if ( is_int( ($a + $b) / 2 ) ) {
					if ( isset( $lightfield ) ) {
						imagecopyresampled( $image, $lightfield, $x0, $y0, 0, 0, 128, 128, 128, 128 );
					} else {
						imagefilledrectangle( $image, $x0, $y0, $x1, $y1, $lightcolor );
					}
				} else {
					if ( isset( $darkfield ) ) {
						imagecopyresampled( $image, $darkfield, $x0, $y0, 0, 0, 128, 128, 128, 128 );
					} else {
						imagefilledrectangle( $image, $x0, $y0, $x1, $y1, $darkcolor );
					}
				}
			}
		}
		if ( isset( $lightfield ) ) {
			imagedestroy($lightfield);
		}
		if ( isset( $darkfield ) ) {
			imagedestroy($darkfield);
		}

		return $image;
	}


	/* Main functions */

	public function render( $return_filename = false, $prefix = '') {
		/* Render our image. This function does most of the magic. It creates
		the actual image, without sending it anywhere yet. After calling
		this function, one should do something with the image--save it as
		a file, for example, or simply print it to the browser.

		The following steps are taken in order to render the image:

				* the size of the image is determined.

				* the FEN input is sanitized.

				* in case a cached image exists, the function opens it and
				  returns (or simply returns the filename if so desired).

				* the background is created and the pieces are laid over it.

				* the image is downscaled to the desired size.

				* the image is cached.

				* returns the generated filename.

		Set the error flag to false in case we've seen an error before. */

		$this->ERROR_HAS_OCCURRED = false;


		/* First, determine the size that the image should be. */
		$preset_sizes = $this->PRESET_SIZES;

		$sz = $this->SIZE;

		/* The size should at the very least be bigger than 0! */
		if ( ! isset($sz) || (is_numeric($sz) && $sz == 0) ) {
			$sz = 'medium';
		}
		if (is_numeric($sz)) {
			/* Size is numeric, but it can't be larger than 1024. */
			if ($sz > 1024) $sz = 1024;
		} else {
			/* Size is a string, so fetch the actual size from our
			predetermined values. */
			if (isset($preset_sizes[$sz])) {
				$sz = $preset_sizes[$sz];
			} else {
				$this->_error(1, 'illegal \'$SIZE\' variable was given.');
				return;
			}
		}

		$this->SIZE = (int) $sz;


		/* Parse and sanitize the FEN input. A regular expression is used
		to ensure that the FEN string only contains legal characters. */
		$fen_array = explode(' ', $this->FEN);
		$raw_fen = $fen_array[0];
		if ( ! preg_match('/^[RNBQKP12345678\/]+$/i', $raw_fen)) {
			$this->_error(2, 'FEN string was empty or contained illegal characters.');
			return;
		}
		$FEN = explode('/', $raw_fen);

		/* Filter the colors if necessary.
		Note: filtering a leading # won't work since that marks the end
		of the query string. */
		foreach ( array( 'COLOR_LIGHT', 'COLOR_DARK' ) as $c ) {
			if (substr($this->$c, 0, 2) == '0x') {
				$this->$c = substr($this->$c, 2, 6);
			}
		}

		/* Check to see whether a cached version of this FEN position already
		exists. The old image is loaded in case it does. The hash should
		be unique to this combination of settings and should contain
		all variables that would alter the result.

		Note that the cached image is not loaded (or saved) in case the '$ERROR_HAS_OCCURRED' variable is 'true'. */

		$hash = $this->get_filename( $prefix );

		clearstatcache();

		/* Does the cache directory exist? */
		if ( ! is_dir($this->CACHE_FOLDER)) {
			/* Can we create it? */
			if ( ! mkdir($this->CACHE_FOLDER, 0755, true)) {
				$this->_error(5, 'Caching is turned on, but the cache directory does not exist and cannot be created.');
			}
		}

		/* Does the cache directory have the proper mode that allows PHP
		to write to it? */
		if ( ! is_writable($this->CACHE_FOLDER)) {
			if ( ! chmod($this->CACHE_FOLDER, 0755)) {
				$this->_error(6, 'Caching is turned on, but the cache directory is not writable and chmod() failed.');
			}
		}

		/*
		 * Determine the path to the file we'll generate (if it is uncached).
		 * This can be a full path or a relative path.
		 */
		$cache_filename = $this->CACHE_FOLDER . ($hash . $this->EXTENSION);

		/* In case the image is cached, we need to return early with a proper URI. */
		$cache_uri = $this->CACHE_URI . ($hash . $this->EXTENSION);

		if ($this->USE_CACHING)
		if ( ! $this->ERROR_HAS_OCCURRED) {
			if ( $return_filename && file_exists($cache_filename) ) {
				/* If all we need is the filename, returning here is fine. */
				return $cache_uri;
			} else if ( file_exists($cache_filename) ) {
				$mime = $this->MIME_TYPE;
				switch ($mime) {
					case 'image/png':
						$cached_image = @imagecreatefrompng($cache_filename);
						break;
					case 'image/jpeg':
						$cached_image = @imagecreatefromjpeg($cache_filename);
						break;
				}

				/* Could we successfully open the cached image?
				In case we could, the image is done and the function returns.
				In case we couldn't, it means there is no cached image, so we
				should create it now. */
				if ($cached_image) {
					$this->image = $cached_image;
					return;
				}
			}
		}


		/*
		 * No cache, continue.
		 * Since there is no cached image (or caching is turned off), the image
		 * will now be actually created. First, initialize some colors and
		 * settings, then make the background, and then overlay the pieces.
		 * Note that the image is always 1024 px in size at first. It's later
		 * downscaled to the desired size.
		 */
		$image = $this->get_board_image();

		/* Now draw the pieces.
		Remember, '$FEN' looks something like this now:

		[0] => rnbqkbnr
		[1] => pppppppp
		[2] => 8
		[3] => 8
		[4] => 8
		[5] => 8
		[6] => PPPPPPPP
		[7] => RNBQKBNR
		*/

		$pieces = $this->get_pieces();

		for ($a = 0, $p = count( $FEN ); $a < $p; ++$a) {
			$rank = str_split( $FEN["$a"] );
			/* One last input check in case one of the ranks is null. */
			if ($rank[0] == '') {
				continue;
			}
			for ($b = 0, $c = 0; $b < 8; ++$b, ++$c) {
				$token = $rank["$c"];
				if ( isset($token) ) {
					if (is_numeric($token)) {
						/* This token is a number, so skip some squares. This is used to compress the input.
						 * For example, an empty rank is "8". A rank with two pawns, one on	the c-file and one on the h-file, is "2p4p".
						 */
						$b += (int) ($token - 1);
						continue;
					} else {
						/* Since this token is a character, overlay a piece over the background.
						 * Whether it's black or white is determined by the case of the character.
						 * Uppercase is white, lowercase is black.
						 */
						$piece = $pieces["$token"];

						// Get piece at right size.
						list($width, $height) = getimagesize($piece);
						$piece_art = imagecreatefrompng($piece); // piece at the wrong size.
						$piece_art_realsize = imagecreatetruecolor(128, 128); // black background.

						// Make it transparent.
						imagesavealpha($piece_art_realsize, true);
						$trans_colour = imagecolorallocatealpha($piece_art_realsize, 0, 0, 0, 127);
						imagefill($piece_art_realsize, 0, 0, $trans_colour);

						// Resize into $piece_art_realsize.
						imagecopyresampled($piece_art_realsize, $piece_art, 0, 0, 0, 0, 128, 128, $width, $height);

						// Copy piece into board.
						imagecopy($image, $piece_art_realsize, $b * 128, $a * 128, 0, 0, 128, 128);
						imagedestroy($piece_art);
						imagedestroy($piece_art_realsize);
					}
				}
			}
		}

		/* Now that our image is rendered, we can downscale it to the desired
		final size. Unless the size is 1024, of course; in that case we
		can simply use it as-is. */
		if ($sz < 1024) {
			$image_final = imagecreatetruecolor($sz, $sz);
			imagecopyresampled($image_final, $image, 0, 0, 0, 0, $sz, $sz, 1024, 1024);
			imagedestroy($image);
			$image = $image_final;
		}


		/* Finally, cache the image (in case caching is turned on). This is
		done automatically in this function as caching should not be
		something that the user of the class has to worry about.
		Note that we still have '$cache_filename' from before.
		The image is not cached in case an error has occurred. */

		if ( $this->USE_CACHING && ! $this->ERROR_HAS_OCCURRED ) {
			$mime = $this->MIME_TYPE;
			switch ($mime) {
				case 'image/png':
					imagepng($image, $cache_filename, $this->QUALITY, $this->FILTERS);
					break;
				case 'image/jpeg':
					imagejpeg($image, $cache_filename, $this->QUALITY);
					break;
				default:
					$this->_error(4, 'unusable MIME type given.');
					return;
			}
		}

		/* The image is complete, so save it for later. */
		$this->image = $image;

		/* Return the usable filename. */
		return $cache_uri;
	}


	/*
	 * This function outputs the generated image, either directly to the
	 * browser or to a file. In case the image isn't saved to a file,
	 * it should be destroyed after use with the 'destroy()' function.
	 */
	public function output( $filename = NULL ) {
		$mime = $this->MIME_TYPE;
		switch ($mime) {
			case 'image/png':
				header('Content-type: ' . $mime);
				imagepng($this->image, $filename, $this->QUALITY, $this->FILTERS);
				break;
			case 'image/jpeg':
				header('Content-type: ' . $mime);
				imagejpeg($this->image, $filename, $this->QUALITY);
				break;
		}
	}


	/*
	 * This function destroys the image. It should be used when the image
	 * has already been used and is no longer needed.
	 */
	public function destroy() {
		if (isset($this->image)) {
			imagedestroy($this->image);
		}
	}


	/* Error handling */

	public function error_has_occurred() {
		return $this->ERROR_HAS_OCCURRED;
	}


	public function get_last_error() {
		return $this->ERROR;
	}


	public function _error( $errno, $msg ) {
		/* This function creates an error image in case something goes wrong.
		Since the purpose of this script is to create images, it's most
		useful to do just that whenever something goes wrong. */

		$this->ERROR_HAS_OCCURRED = true;
		$this->ERROR = array( $errno, $msg );

		/* First, destroy the image we were working on. */
		$this->destroy();

		/* Now create a new one that shows what went wrong. */
		$string = 'Error ' . $errno . ': ' . $msg;

		$width = imagefontwidth(3) * strlen($string);
		$height = imagefontheight(3);

		$error = imagecreatetruecolor($width, $height);

		imagesavealpha($error, true);
		$color = imagecolorallocatealpha($error, 0, 0, 0, 127);
		imagefill($error, 0, 0, $color);
		$text_color = imagecolorallocate($error, 0, 0, 0);

		imagestring($error, 3, 0, 0, $string, $text_color);

		/* The error is kept (as the usual image variable) until it is used. */
		$this->image = $error;
	}


	/* Helper methods */

	/*
	 * Convert a base-16 hex number to base-10 RGB values.
	 *
	 * @param  string $hex color in hex base-16 like ddeeff.
	 * @return array  $rgb color in rgb base-10 like 152.
	 */
	public function hex_to_rgb( $hex ) {
		if (substr($hex, 0, 1) == '#') {
			$hex = substr($hex, 1);
		}
		if (strlen($hex) == 3) {
			$hex = substr($hex, 0, 1) . substr($hex, 0, 1) .
				substr($hex, 1, 1) . substr($hex, 1, 1) .
				substr($hex, 2, 1) . substr($hex, 2, 1);
		}
		$rgb = array();
		$rgb['r'] = hexdec(substr($hex, 0, 2));
		$rgb['g'] = hexdec(substr($hex, 2, 2));
		$rgb['b'] = hexdec(substr($hex, 4, 2));

		return $rgb;
	}


	/*
	 * Normalize a filesystem path.
	 *
	 * This should be replaced by wp_normalize_path when the plugin's
	 * minimum requirement becomes WordPress 3.9 or higher.
	 *
	 * @param  string $path Path to normalize.
	 * @return string Normalized path.
	 */
	public function normalize_path( $path ) {
		$path = str_replace( '\\', '/', $path );
		$path = preg_replace( '|/+|', '/', $path );
		return $path;
	}

	/* Mind your head, only use in WordPress installs. */
	public function truncate_slug( $slug ) {
		if ( ! function_exists('utf8_uri_encode') || ! function_exists('remove_accents') || ! function_exists('sanitize_file_name') ) {
			return $slug;
		}

		$slug = substr( $slug, 0, 100 );
		$slug = utf8_uri_encode( $slug, 100 );
		$slug = remove_accents( $slug);
		$slug = sanitize_file_name( $slug);
		return rtrim( $slug, '-' );
	}


	/*
	 * Purge old cache files.
	 *
	 * @return int|bool The number of removed files. Return false if error occurred.
	 */
	public function purge() {
		$dir = trailingslashit( $this->CACHE_FOLDER );
		$dir = $this->normalize_path( $dir );
		$dir = path_join( dirname( __FILE__ ), $dir );

		if ( ! is_dir( $dir ) || ! is_readable( $dir ) ) {
			return false;
		}

		$is_win = ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) );

		if ( ! ( $is_win ? win_is_writable( $dir ) : is_writable( $dir ) ) ) {
			return false;
		}

		$count = 0;

		if ( $handle = opendir( $dir ) ) {
			while ( false !== ( $filename = readdir( $handle ) ) ) {
				if ( ! preg_match( '/^.*\.(png|jpg)$/', $filename ) ) {
					continue;
				}

				$file = $this->normalize_path( $dir . $filename );

				if ( ! file_exists( $file ) || ! ( $stat = stat( $file ) ) ) {
					continue;
				}

				if ( ! unlink( $file ) ) {
					chmod( $file, 0644 );
					unlink( $file );
				}
				$count += 1;
			}
			closedir( $handle );
		}

		return $count;
	}

}



/* In case we're using this as a stand-alone script, simply take whatever data we have and output an image.
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {


function trailingslashit( $string ) {
	return untrailingslashit( $string ) . '/';
}
function untrailingslashit( $string ) {
	return rtrim( $string, '/\\' );
}
function path_join( $base, $path ) {
	if ( path_is_absolute($path) )
		return $path;
	return rtrim($base, '/') . '/' . ltrim($path, '/');
}
function path_is_absolute( $path ) {
	if ( realpath($path) == $path )
		return true;

	if ( strlen($path) == 0 || $path[0] == '.' )
		return false;

	if ( preg_match('#^[a-zA-Z]:\\\\#', $path) )
		return true;

	// A path starting with / or \ is absolute; anything else is relative.
	return ( $path[0] == '/' || $path[0] == '\\' );
}

	$fen = isset($_GET['fen']) ? $_GET['fen'] : 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR';
	$settings = array();
	if (isset($_GET['size'])) $settings['SIZE'] = $_GET['size'];
	if (isset($_GET['color_light'])) $settings['COLOR_LIGHT'] = $_GET['color_light'];
	if (isset($_GET['color_dark'])) $settings['COLOR_DARK'] = $_GET['color_dark'];
	$board = new MFEN();
	//$board->purge();
	$board->set_fen($fen);
	$board->change_settings($settings);
	$board->render();
	$board->output();
	$board->destroy();
}
*/
