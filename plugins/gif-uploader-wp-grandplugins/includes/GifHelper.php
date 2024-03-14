<?php
namespace Grafika\Gd\Helper;

final class GifHelper {

	/**
	 * @param $imageFile
	 *
	 * @return GifByteStream
	 * @throws \Exception
	 */
	public function open( $imageFile ) {
		$fp = fopen( $imageFile, 'rb' ); // Binary read

		if ( $fp === false ) {
			throw new \Exception( sprintf( 'Error loading file: "%s".', $imageFile ) );
		}

		$size  = filesize( $imageFile );
		$bytes = fread( $fp, $size );
		$bytes = unpack( 'H*', $bytes ); // Unpack as hex
		$bytes = $bytes[1];
		fclose( $fp );

		return new GifByteStream( $bytes );
	}

	/**
	 * @param string $bin Raw binary data from imagegif or file_get_contents
	 *
	 * @return GifByteStream
	 */
	public function load( $bin ) {
		$bytes = unpack( 'H*', $bin ); // Unpack as hex
		$bytes = $bytes[1];

		return new GifByteStream( $bytes );
	}

	/**
	 * @param GifByteStream $bytes
	 *
	 * @return bool
	 */
	public function isAnimated( $bytes ) {

		$bytes->setPosition( 13 );
		$lastPos  = $bytes->getPosition();
		$gceCount = 0;
		while ( ( $lastPos = $bytes->find( '21f904', $lastPos ) ) !== false ) {
			$gceCount++;
			if ( $gceCount > 1 ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Encode data into GIF hex string.
	 *
	 * @param array $data The array returned by decode.
	 *
	 * @return string Hex string of GIF
	 */
	public function encode( $data ) {
		$hex = '';
		// header block
		$hex .= $this->_fixSize( $this->_asciiToHex( $data['signature'] ), 3 );
		$hex .= $this->_fixSize( $this->_asciiToHex( $data['version'] ), 3 );

		// logical screen descriptor block
		$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $data['canvasWidth'] ), 4 ) );
		$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $data['canvasHeight'] ), 4 ) );
		$packedField  = decbin( $data['globalColorTableFlag'] );
		$packedField .= $this->_fixSize( decbin( $data['colorResolution'] ), 3 );
		$packedField .= decbin( $data['sortFlag'] );
		$packedField .= $this->_fixSize( decbin( $data['sizeOfGlobalColorTable'] ), 3 );
		$hex         .= $this->_fixSize( dechex( bindec( $packedField ) ), 2 );
		$hex         .= $this->_fixSize( dechex( $data['backgroundColorIndex'] ), 2 );
		$hex         .= $this->_fixSize( dechex( $data['pixelAspectRatio'] ), 2 );

		// global color table optional
		if ( $data['globalColorTableFlag'] > 0 ) {
			$hex .= $data['globalColorTable'];
		}
		// app ext optional
		if ( isset( $data['applicationExtension'] ) ) {
			foreach ( $data['applicationExtension'] as $app ) {
				$hex .= '21ff0b';
				$hex .= $this->_fixSize( $this->_asciiToHex( $app['appId'] ), 8 );
				$hex .= $this->_fixSize( $this->_asciiToHex( $app['appCode'] ), 3 );
				foreach ( $app['subBlocks'] as $subBlock ) {
					$len  = $this->_fixSize( dechex( strlen( $subBlock ) / 2 ), 2 );
					$hex .= $len . $subBlock;
				}
				$hex .= '00';
			}
		}

		foreach ( $data['frames'] as $i => $frame ) {

			// graphics control optional
			if ( isset( $frame['delayTime'] ) ) {
				$hex         .= '21f904';
				$packedField  = '000'; // reserved
				$packedField .= $this->_fixSize( decbin( $frame['disposalMethod'] ), 3 );
				$packedField .= decbin( $frame['userInputFlag'] );
				$packedField .= decbin( $frame['transparentColorFlag'] );
				$hex         .= $this->_fixSize( dechex( bindec( $packedField ) ), 2 );
				$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $frame['delayTime'] ), 4 ) );
				$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $frame['transparentColorIndex'] ), 2 ) );
				$hex         .= '00';
			}

			// image desc
			$hex         .= '2c';
			$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $frame['imageLeft'] ), 4 ) );
			$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $frame['imageTop'] ), 4 ) );
			$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $frame['imageWidth'] ), 4 ) );
			$hex         .= $this->_switchEndian( $this->_fixSize( dechex( $frame['imageHeight'] ), 4 ) );
			$packedField  = decbin( $frame['localColorTableFlag'] );
			$packedField .= decbin( $frame['interlaceFlag'] );
			$packedField .= decbin( $frame['sortFlag'] );
			$packedField .= '00'; // reserved
			$packedField .= $this->_fixSize( decbin( $frame['sizeOfLocalColorTable'] ), 3 );
			$hex         .= $this->_fixSize( dechex( bindec( $packedField ) ), 2 );

			// local color table optional
			if ( $frame['localColorTableFlag'] > 0 ) {
				$hex .= $frame['localColorTable'];
			}

			$hex .= $frame['imageData'];
		}
		$hex .= $data['trailer'];
		return $hex;
	}

	/**
	 * Decode GIF into array of data for easy use in PHP userland.
	 *
	 * @param GifByteStream $bytes Decode byte stream into array of GIF blocks.
	 *
	 * @return array Array containing GIF data
	 * @throws \Exception
	 */
	public function decode( $bytes ) {
		$bytes->setPosition( 0 );
		$blocks = $this->decodeToBlocks( $bytes );

		return $this->expandBlocks( $blocks );
	}

	/**
	 * Decompose GIF into its block components. The GIF blocks are in the order that they appear in the byte stream.
	 *
	 * @param GifByteStream $bytes
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function decodeToBlocks( $bytes ) {
		$bytes->setPosition( 0 );
		$blocks = array();

		// Header block
		$blocks['header'] = $bytes->bite( 6 );

		// Logical screen descriptor block
		$part                   = $bytes->bite( 2 ); // canvass w
		$hex                    = $part;
		$part                   = $bytes->bite( 2 ); // canvass h
		$hex                   .= $part;
		$part                   = $bytes->bite( 1 ); // packed field
		$hex                   .= $part;
		$bin                    = $this->_fixSize( $this->_hexToBin( $part ), 8 );
		$globalColorTableFlag   = bindec( substr( $bin, 0, 1 ) );
		$sizeOfGlobalColorTable = bindec( substr( $bin, 5, 3 ) );

		$part                              = $bytes->bite( 1 ); // backgroundColorIndex
		$hex                              .= $part;
		$part                              = $bytes->bite( 1 ); // pixelAspectRatio
		$hex                              .= $part;
		$blocks['logicalScreenDescriptor'] = $hex;

		// Global color table is optional so check its existence
		if ( $globalColorTableFlag > 0 ) {
			// Formula: 3 * (2^(N+1))
			$colorTableLength           = 3 * ( pow( 2, ( $sizeOfGlobalColorTable + 1 ) ) );
			$part                       = $bytes->bite( $colorTableLength );
			$blocks['globalColorTable'] = $part;
		}

		$commentC = $plainTextC = $appCount = $gce = $dc = 0; // index count
		while ( ! $bytes->isEnd() ) {
			$part = $bytes->bite( 1 );

			if ( '21' === $part ) { // block tests
				$hex  = $part;
				$part = $bytes->bite( 1 );
				if ( 'ff' === $part ) { // App extension block
					$hex .= $part;
					$part = $bytes->bite( 1 ); // app name length should be 0x0b or int 11 but we check anyways
					$size = hexdec( $part ); // turn it to int
					$hex .= $part;
					$part = $bytes->bite( $size ); // app name
					$hex .= $part;
					while ( ! $bytes->isEnd() ) { // loop thru all app sub blocks
						$nextSize = $bytes->bite( 1 );
						if ( $nextSize !== '00' ) {
							$hex .= $nextSize;
							$size = hexdec( $nextSize );
							$part = $bytes->bite( $size );
							$hex .= $part;
						} else {
							$hex .= $nextSize;
							$blocks[ 'applicationExtension-' . $appCount ] = $hex;
							break;
						}
					}

					$appCount++;
				} elseif ( 'f9' === $part ) { // graphic
					$hex                                        .= $part;
					$part                                        = $bytes->bite( 1 ); // size
					$hex                                        .= $part;
					$part                                        = $bytes->bite( 1 ); // packed field
					$hex                                        .= $part;
					$part                                        = $bytes->bite( 2 ); // delay time
					$hex                                        .= $part;
					$part                                        = $bytes->bite( 1 ); // trans color index
					$hex                                        .= $part;
					$part                                        = $bytes->bite( 1 ); // terminator
					$hex                                        .= $part;
					$blocks[ 'graphicControlExtension-' . $gce ] = $hex;
					$gce++;
				} elseif ( '01' === $part ) { // plain text ext
					$hex .= $part;

					while ( ! $bytes->isEnd() ) { // loop thru all app sub blocks
						$nextSize = $bytes->bite( 1 );
						if ( $nextSize !== '00' ) {
							$hex .= $nextSize;
							$size = hexdec( $nextSize );
							$part = $bytes->bite( $size );
							$hex .= $part;
						} else {
							$hex .= $nextSize;
							$blocks[ 'plainTextExtension-' . $plainTextC ] = $hex;
							break;
						}
					}
					$plainTextC++;
				} elseif ( 'fe' === $part ) { // comment ext
					$hex .= $part;

					while ( ! $bytes->isEnd() ) { // loop thru all app sub blocks
						$nextSize = $bytes->bite( 1 );
						if ( $nextSize !== '00' ) {
							$hex .= $nextSize;
							$size = hexdec( $nextSize );
							$part = $bytes->bite( $size );
							$hex .= $part;
						} else {
							$hex                                      .= $nextSize;
							$blocks[ 'commentExtension-' . $commentC ] = $hex;
							break;
						}
					}
					$commentC++;
				}
			} elseif ( '2c' === $part ) { // image descriptors
				$hex                                = $part;
				$part                               = $bytes->bite( 2 ); // imageLeft
				$hex                               .= $part;
				$part                               = $bytes->bite( 2 ); // imageTop
				$hex                               .= $part;
				$part                               = $bytes->bite( 2 ); // imageWidth
				$hex                               .= $part;
				$part                               = $bytes->bite( 2 ); // imageHeight
				$hex                               .= $part;
				$part                               = $bytes->bite( 1 ); // packed field
				$hex                               .= $part;
				$blocks[ 'imageDescriptor-' . $dc ] = $hex;
				$bin                                = $this->_fixSize( $this->_hexToBin( $part ), 8 );
				$localColorTableFlag                = bindec( substr( $bin, 0, 1 ) );
				$sizeOfLocalColorTable              = bindec( substr( $bin, 5, 3 ) );

				// LC
				if ( $localColorTableFlag ) {
					// Formula: 3 * (2^(N+1))
					$localColorTableLen                 = 3 * ( pow( 2, ( $sizeOfLocalColorTable + 1 ) ) );
					$part                               = $bytes->bite( $localColorTableLen );
					$blocks[ 'localColorTable-' . $dc ] = $part;
				}

				// Image data
				$part = $bytes->bite( 1 ); // LZW code
				$hex  = $part;
				while ( $bytes->isEnd() === false ) {
					$nextSize = $bytes->bite( 1 );
					$hex     .= $nextSize;
					if ( $nextSize !== '00' ) {
						// In case the Frame imageData is corrupted.
						if ( hexdec( $nextSize ) + $bytes->getPosition() >= $bytes->length() ) {
							break;
						}
						$subBlockLen = hexdec( $nextSize );
						$subBlock    = $bytes->bite( $subBlockLen );
						$hex        .= $subBlock;
					} else {
						$blocks[ 'imageData-' . $dc ] = $hex;
						break;
					}
				}

				// Skip a frame if it has no imageData.
				if ( ! isset( $blocks[ 'imageData-' . $dc ] ) ) {
					unset( $blocks[ 'localColorTable-' . $dc ] );
					unset( $blocks[ 'imageDescriptor-' . $dc ] );
					unset( $blocks[ 'graphicControlExtension-' . $dc ] );
				}
				$dc++;

			} else {
				$blocks['trailer'] = $part;
				break;
			}
		}

		$blocks['trailer'] = '3b';

		return $blocks;
	}

	/**
	 * Expand GIF blocks into useful info.
	 *
	 * @param array $blocks Accepts the array returned by decodeToBlocks
	 *
	 * @return array
	 */
	public function expandBlocks( $blocks ) {

		$decoded = array();
		foreach ( $blocks as $blockName => $block ) {
			$bytes = new GifByteStream( $block );
			if ( false !== strpos( $blockName, 'header' ) ) {
				$part                 = $bytes->bite( 3 );
				$decoded['signature'] = $this->_hexToAscii( $part );
				$part                 = $bytes->bite( 3 );
				$decoded['version']   = $this->_hexToAscii( $part );
			} elseif ( false !== strpos( $blockName, 'logicalScreenDescriptor' ) ) {
				$part                              = $bytes->bite( 2 );
				$decoded['canvasWidth']            = hexdec( $this->_switchEndian( $part ) );
				$part                              = $bytes->bite( 2 );
				$decoded['canvasHeight']           = hexdec( $this->_switchEndian( $part ) );
				$part                              = $bytes->bite( 1 );
				$bin                               = $this->_fixSize( $this->_hexToBin( $part ), 8 ); // Make sure len is correct
				$decoded['globalColorTableFlag']   = bindec( substr( $bin, 0, 1 ) );
				$decoded['colorResolution']        = bindec( substr( $bin, 1, 3 ) );
				$decoded['sortFlag']               = bindec( substr( $bin, 4, 1 ) );
				$decoded['sizeOfGlobalColorTable'] = bindec( substr( $bin, 5, 3 ) );
				$part                              = $bytes->bite( 1 );
				$decoded['backgroundColorIndex']   = hexdec( $part );
				$part                              = $bytes->bite( 1 );
				$decoded['pixelAspectRatio']       = hexdec( $part );

			} elseif ( false !== strpos( $blockName, 'globalColorTable' ) ) {
				$decoded['globalColorTable'] = $block;
			} elseif ( false !== strpos( $blockName, 'applicationExtension' ) ) {
				$index = explode( '-', $blockName, 2 );
				$index = $index[1];

				$bytes->next( 2 ); // Skip ext intro and label: 21 ff
				$appNameSize = $bytes->bite( 1 ); // 0x0b or 11 according to spec but we check anyways
				$appNameSize = hexdec( $appNameSize );
				$appName     = $this->_hexToAscii( $bytes->bite( $appNameSize ) );
				$subBlocks   = array();
				while ( ! $bytes->isEnd() ) { // loop thru all app sub blocks
					$nextSize = $bytes->bite( 1 );
					if ( $nextSize !== '00' ) {
						$size        = hexdec( $nextSize );
						$subBlocks[] = $bytes->bite( $size );

					}
				}
				if ( $appName === 'NETSCAPE2.0' ) {
					$decoded['applicationExtension'][ $index ]['appId']     = 'NETSCAPE';
					$decoded['applicationExtension'][ $index ]['appCode']   = '2.0';
					$decoded['applicationExtension'][ $index ]['subBlocks'] = $subBlocks;
					$decoded['loopCount']                                   = hexdec( $this->_switchEndian( substr( $subBlocks[0], 2, 4 ) ) );
				} else {
					$decoded['applicationExtension'][ $index ]['appId']     = substr( $appName, 0, 8 );
					$decoded['applicationExtension'][ $index ]['appCode']   = substr( $appName, 8, 3 );
					$decoded['applicationExtension'][ $index ]['subBlocks'] = $subBlocks;
				}
			} elseif ( false !== strpos( $blockName, 'graphicControlExtension' ) ) {
				$index = explode( '-', $blockName, 2 );
				$index = $index[1];

				$bytes->next( 3 ); // Skip ext intro, label, and block size which is always 4: 21 f9 04
				$part = $bytes->bite( 1 ); // packed field
				$bin  = $this->_fixSize( $this->_hexToBin( $part ), 8 ); // Make sure len is correct
				$decoded['frames'][ $index ]['disposalMethod']       = bindec( substr( $bin, 3, 3 ) );
				$decoded['frames'][ $index ]['userInputFlag']        = bindec( substr( $bin, 6, 1 ) );
				$decoded['frames'][ $index ]['transparentColorFlag'] = bindec( substr( $bin, 7, 1 ) );
				$part                                     = $bytes->bite( 2 );
				$decoded['frames'][ $index ]['delayTime'] = hexdec( $this->_switchEndian( $part ) );
				$part                                     = $bytes->bite( 1 );
				$decoded['frames'][ $index ]['transparentColorIndex'] = hexdec( $part );
			} elseif ( false !== strpos( $blockName, 'imageDescriptor' ) ) {
				$index = explode( '-', $blockName, 2 );
				$index = $index[1];

				$bytes->next( 1 ); // skip separator: 2c
				$part                                       = $bytes->bite( 2 );
				$decoded['frames'][ $index ]['imageLeft']   = hexdec( $this->_switchEndian( $part ) );
				$part                                       = $bytes->bite( 2 );
				$decoded['frames'][ $index ]['imageTop']    = hexdec( $this->_switchEndian( $part ) );
				$part                                       = $bytes->bite( 2 );
				$decoded['frames'][ $index ]['imageWidth']  = hexdec( $this->_switchEndian( $part ) );
				$part                                       = $bytes->bite( 2 );
				$decoded['frames'][ $index ]['imageHeight'] = hexdec( $this->_switchEndian( $part ) );
				$part                                       = $bytes->bite( 1 ); // packed field
				$bin                                        = $this->_fixSize(
					$this->_hexToBin( $part ),
					8
				);
				$decoded['frames'][ $index ]['localColorTableFlag']   = bindec( substr( $bin, 0, 1 ) );
				$decoded['frames'][ $index ]['interlaceFlag']         = bindec( substr( $bin, 1, 1 ) );
				$decoded['frames'][ $index ]['sortFlag']              = bindec( substr( $bin, 2, 1 ) );
				$decoded['frames'][ $index ]['sizeOfLocalColorTable'] = bindec( substr( $bin, 5, 3 ) );
			} elseif ( false !== strpos( $blockName, 'localColorTable' ) ) {
				$index = explode( '-', $blockName, 2 );
				$index = $index[1];
				$decoded['frames'][ $index ]['localColorTable'] = $block;
			} elseif ( false !== strpos( $blockName, 'imageData' ) ) {
				$index = explode( '-', $blockName, 2 );
				$index = $index[1];

				$decoded['frames'][ $index ]['imageData'] = $block;
			} elseif ( $blockName === 'trailer' ) {
				$decoded['trailer'] = $block;
			}
			unset( $bytes );
		}

		return $decoded;
	}

	/**
	 * @param array $blocks The array returned by decode.
	 *
	 * @return array Array of images each containing 1 of each frames of the original image.
	 */
	public function splitFrames( $blocks ) {
		$images = array();
		if ( isset( $blocks['frames'] ) ) {
			foreach ( $blocks['frames'] as $a => $unused ) {
				$images[ $a ] = $blocks;
				unset( $images[ $a ]['frames'] ); // remove all frames.
				foreach ( $blocks['frames'] as $b => $frame ) {
					if ( $a === $b ) {
						$images[ $a ]['frames'][0] = $frame; // Re-add frames but use only 1 frame and discard others
						break;
					}
				}
			}
		}
		return $images;
	}

	/**
	 * @param $blocks
	 * @param $newW
	 * @param $newH
	 *
	 * @return array $blocks
	 */
	public function resize( $blocks, $newW, $newH ) {
		$images       = $this->splitFrames( $blocks );
		$firstFrameGd = null;

		if ( empty( $images ) ) {
			return $blocks;
		}
		$cX = $newW / $blocks['canvasWidth'];
		$cY = $newH / $blocks['canvasHeight'];

		// Loop on individual images and resize them using Gd
		foreach ( $images as $imageIndex => $image ) {
			$hex       = $this->encode( $image );
			$binaryRaw = pack( 'H*', $hex );

			// Utilize gd for resizing
			$old    = imagecreatefromstring( $binaryRaw );
			$width  = imagesx( $old );
			$height = imagesy( $old );
			$new    = imagecreatetruecolor( $newW, $newH ); // Create a blank image

			// Fill transparent background with white bg for separate frames [ DisposalMethod = 2 ].
			if ( ! empty( $blocks['frames'][ $imageIndex ] ) && ( 2 === $blocks['frames'][ $imageIndex ]['disposalMethod'] ) ) {
				$blank_text_bg_color = imagecolorallocate( $new, 255, 255, 255 );
				imagefill( $new, 0, 0, $blank_text_bg_color );
			}

			// otherwise stack the frames on the first frame [ DisposalMethod = 1|3 ]
			if ( ! is_null( $firstFrameGd ) && ! empty( $blocks['frames'][ $imageIndex ] ) && ( 2 !== $blocks['frames'][ $imageIndex ]['disposalMethod'] ) ) {
				imagedestroy( $new );
                $new = $firstFrameGd;
			}
			// Account for frame imageLeft and imageTop
			$dX           = $image['frames'][0]['imageLeft'];
			$dY           = $image['frames'][0]['imageTop'];
			$frame_width  = $image['frames'][0]['imageWidth'];
			$frame_height = $image['frames'][0]['imageHeight'];
			$dst_x        = (int) round( $dX * $cX );
			$dst_y        = (int) round( $dY * $cY );
			$dst_width    = (int) round( $frame_width * $cX );
			$dst_height   = (int) round( $frame_height * $cY );

			imagecopyresampled(
				$new,
				$old,
				$dst_x,
				$dst_y,
				0,
				0,
				$dst_width,
				$dst_height,
				$width,
				$height
			);

			ob_start();
			imagegif( $new );
			$binaryRaw = ob_get_contents();
			ob_end_clean();


			if ( is_null( $firstFrameGd ) ) {
				$firstFrameGd = $new;
			}
			imagedestroy( $old );

			// Hex of resized
			$bytes  = $this->load( $binaryRaw );
			$hexNew = $this->decode( $bytes );

			// Update original frames with hex from resized frames
			$blocks['frames'][ $imageIndex ]['imageWidth']  = $hexNew['frames'][0]['imageWidth'];
			$blocks['frames'][ $imageIndex ]['imageHeight'] = $hexNew['frames'][0]['imageHeight'];
			$blocks['frames'][ $imageIndex ]['imageLeft']   = $hexNew['frames'][0]['imageLeft'];
			$blocks['frames'][ $imageIndex ]['imageTop']    = $hexNew['frames'][0]['imageTop'];
			// $blocks['frames'][ $imageIndex ]['imageLeft']     = $dst_x;
			// $blocks['frames'][ $imageIndex ]['imageTop']      = $dst_y;
			$blocks['frames'][ $imageIndex ]['imageData']     = $hexNew['frames'][0]['imageData'];
			$blocks['frames'][ $imageIndex ]['interlaceFlag'] = $hexNew['frames'][0]['interlaceFlag'];
			$blocks['frames'][ $imageIndex ]['sortFlag']      = $hexNew['frames'][0]['sortFlag'];
			// We use local color tables on each frame. This will result in faster processing since we dont have to process the global color table at the cost of a larger file size.
			$blocks['frames'][ $imageIndex ]['localColorTableFlag'] = $hexNew['globalColorTableFlag'];
			if ( 1 === $hexNew['globalColorTableFlag'] && ! empty( $hexNew['globalColorTable'] ) ) {
				$blocks['frames'][ $imageIndex ]['localColorTable']       = $hexNew['globalColorTable'];
				$blocks['frames'][ $imageIndex ]['sizeOfLocalColorTable'] = $hexNew['sizeOfGlobalColorTable'];
				$blocks['frames'][ $imageIndex ]['transparentColorFlag']  = 0;
			}
		}
		// Update dimensions or else imagecreatefromgif will choke.
		$blocks['canvasWidth']  = $newW;
		$blocks['canvasHeight'] = $newH;
		// Disable flickering bug. Also we are using localColorTable anyways.
		$blocks['globalColorTableFlag'] = 0;
		$blocks['globalColorTable']     = '';
		if ( ! is_null( $firstFrameGd ) ) {
			@imagedestroy( $firstFrameGd );
		}
		return $blocks;
	}

	/**
	 * @param $asciiString
	 *
	 * @return string
	 */
	private function _asciiToHex( $asciiString ) {
		$chars  = str_split( $asciiString, 1 );
		$string = '';
		foreach ( $chars as $char ) {
			$string .= dechex( ord( $char ) );
		}
		return $string;
	}

	/**
	 * @param $hexString
	 *
	 * @return string
	 */
	private function _hexToAscii( $hexString ) {
		$bytes  = str_split( $hexString, 2 );
		$string = '';
		foreach ( $bytes as $byte ) {
			$string .= chr( hexdec( $byte ) ); // convert hex to dec to ascii character. See http://www.ascii.cl/
		}
		return $string;
	}

	/**
	 * @param $hexString
	 *
	 * @return string
	 */
	private function _hexToBin( $hexString ) {
		return base_convert( $hexString, 16, 2 );
	}

	/**
	 * @param $string
	 * @param $size
	 * @param string $char
	 *
	 * @return string
	 */
	private function _fixSize( $string, $size, $char = '0' ) {
		return str_pad( $string, $size, $char, STR_PAD_LEFT );
	}

	/**
	 * @param $hexString
	 *
	 * @return string
	 */
	private function _switchEndian( $hexString ) {
		return implode( '', array_reverse( str_split( $hexString, 2 ) ) );
	}
}
