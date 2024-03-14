<?php
/**
 * Copyright (c) 2012 Ra�l Ferras raul.ferras@gmail.com
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 * 3. Neither the name of copyright holders nor the names of its
 * contributors may be used to endorse or promote products derived
 * from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * ''AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL COPYRIGHT HOLDERS OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * https://github.com/raulferras/PHP-po-parser
 *
 * Class to parse .po file and extract its strings.
 * @version 3.0
 */
class MY_WP_Translate_Po_Parser {

	protected $entries = array();

	protected $headers = array();

	/**
	 * Reads and parses strings of a .po file.
	 *
	 * @throws Exception.
	 * @return Array. List of entries found in .po file.
	 */
	public function read( $file_path ) {
		$tcomment = null;
		$ccomment = null;
		$reference = null;

		if ( empty( $file_path ) ) {
			throw new Exception( 'MY_WP_Translate_Po_Parser: Input File not defined.' );
		} elseif ( file_exists( $file_path ) === false ) {
			throw new Exception( 'MY_WP_Translate_Po_Parser: Input File does not exists: "' . htmlspecialchars( $file_path ) . '"' );
		} elseif ( is_readable( $file_path ) === false ) {
			throw new Exception( 'MY_WP_Translate_Po_Parser: File is not readable: "' . htmlspecialchars( $file_path ) . '"' );
		}

		$handle   = file_get_contents( $file_path );
		$handle   = preg_split( '/$(\r|\n)?^/m', $handle );
		$headers  = array();
		$hash     = array();
		$fuzzy    = false;
		$entry    = array();
		$just_new_entry    = false;
		$first_line        = true;
		$last_obsolete_key = null;

		foreach ( $handle as $l ) {
			$line = trim( $l );

			if ( '' === $line ) {

				if ( $just_new_entry ) {
					// Two consecutive blank lines
					continue;
				}

				if ( $first_line ) {
					$first_line = false;
					if ( self::is_header( $entry ) ) {
						array_shift( $entry['msgstr'] );
						$headers = $entry['msgstr'];
					} else {
						$hash[] = $entry;
					}
				} else {
					// A new entry is found!
					$hash[] = $entry;
				}

				$entry  = array();
				$state  = null;
				$just_new_entry    = true;
				$last_obsolete_key = null;
				continue;
			}

			$just_new_entry = false;
			$split = preg_split( '/\s+/ ', $line, 2 );
			$key   = $split[0];
			$data  = isset( $split[1] ) ? $split[1] : null;

			switch ( $key ) {
				// Flagged translation
				case '#,':
					$entry['fuzzy'] = in_array( 'fuzzy', preg_split( '/,\s*/', $data ) );
					$entry['flags'] = $data;
					break;

				// # Translator comments
				case '#':
					$entry['tcomment'] = ! isset( $entry['tcomment'] ) ? array() : $entry['tcomment'];
					$entry['tcomment'][] = $data;
					break;

				// #. Comments extracted from source code
				case '#.':
					$entry['ccomment'] = ! isset( $entry['ccomment'] ) ? array() : $entry['ccomment'];
					$entry['ccomment'][] = $data;
					break;

				// Reference
				case '#:':
					$entry['reference'][] = addslashes( $data );
					break;

				// #| Previous untranslated string
				case '#|':
					// Start a new entry
					break;

				// #~ Old entry
				case '#~':
					$entry['obsolete'] = true;

					$tmp_parts = explode( ' ', $data );
					$tmp_key   = $tmp_parts[0];

					if ( 'msgid' !== $tmp_key && 'msgstr' !== $tmp_key ) {
						$tmp_key = $last_obsolete_key;
						$str = $data;
					} else {
						$str = implode( ' ', array_slice( $tmp_parts, 1 ) );
					}

					switch ( $tmp_key ) {
						case 'msgid':
							$entry['msgid'][] = $str;
							$last_obsolete_key  = $tmp_key;
							break;

						case 'msgstr':
							if ( '""' === $str ) {
								$entry['msgstr'][] = trim( $str, '"' );
							} else {
								$entry['msgstr'][] = $str;
							}
							$last_obsolete_key   = $tmp_key;
							break;

						default:
							break;
					}

					break;

				// context
				// Allows disambiguations of different messages that have same msgid.
				// Example:
				//
				// #: tools/observinglist.cpp:700
				// msgctxt "First letter in 'Scope'"
				// msgid "S"
				// msgstr ""
				//
				// #: skycomponents/horizoncomponent.cpp:429
				// msgctxt "South"
				// msgid "S"
				// msgstr ""
				case 'msgctxt':
				case 'msgid': // untranslated-string
				case 'msgid_plural': // untranslated-string-plural
					$state = $key;
					$entry[ $state ][] = $data;
					break;
				// translated-string
				case 'msgstr':
					$state = 'msgstr';
					$entry[ $state ][] = $data;
					break;

				default:
					if ( false !== strpos( $key, 'msgstr[' ) ) {
						// translated-string-case-n
						$state = 'msgstr';
						$entry[ $state ][] = $data;
					} else {
						// continued lines
						switch ( $state ) {
							case 'msgctxt':
							case 'msgid':
							case 'msgid_plural':
								if ( is_string( $entry[ $state ] ) ) {
									// Convert it to array
									$entry[ $state ] = array( $entry[ $state ] );
								}
								$entry[ $state ][] = $line;
								break;

							case 'msgstr':
								// Special fix where msgid is ""
								if ( '""' === $entry['msgid'] ) {
									$entry['msgstr'][] = trim( $line, '"' );
								} else {
									$entry['msgstr'][] = $line;
								}
								break;

							default:
								throw new Exception( 'MY_WP_Translate_Po_Parser: Parse error! Unknown key "' . $key . '" on line ' . $line );
						}
					}
					break;
			}
		}

		// Add final entry.
		if ( 'msgstr' === $state ) {
			$hash[] = $entry;
		}

		// - Cleanup header data.
		$this->headers = array();
		foreach ( $headers as $header ) {
			$this->headers[] = '"' . preg_replace( "/\\n/", "\\n", $this->clean( $header ) ) . '"';
		}

		// - Cleanup data,
		// - merge multiline entries
		// - Reindex hash for ksort
		$temp = $hash;
		$this->entries = array();
		foreach ( $temp as $entry ) {
			foreach ( $entry as &$v ) {
				$or = $v;
				$v = $this->clean( $v );
				if ( false === $v ) {
					// parse error
					throw new Exception( 'MY_WP_Translate_Po_Parser: Parse error! poparser::clean returned false on "' . htmlspecialchars( $or ) . '"' );
				}
			}

			if ( isset( $entry['msgid'] ) && isset( $entry['msgstr'] ) ) {
				$id = $this->entry_id( $entry );
				$this->entries[ $id ] = $entry;
			}
		}

		return $this->entries;
	}

	/**
	 * Get File headers
	 */
	public function headers() {
		return $this->headers;
	}

	/**
	 * Updates an entry.
	 *
	 * @param $original. String. Original string to translate.
	 * @param $translation. String. Translated string
	 */
	public function update_entry( $original, $translation ) {
		$this->entries[ $original ]['fuzzy'] = false;
		$this->entries[ $original ]['msgstr'] = array( $translation );

		if ( isset( $this->entries[ $original ]['flags'] ) ) {
			$flags = $this->entries[ $original ]['flags'];
			$this->entries[ $original ]['flags'] = str_replace( 'fuzzy', '', $flags );
		}
	}

	/**
	 * Output content of a po file.
	 */
	public function output( $file_path ) {
		$output = '';
		$handle = @fopen( $file_path, 'r' );

		if ( false !== $handle ) {
			if ( count( $this->headers ) > 0 ) {
				$output .= "msgid \"\"\n";
				$output .= "msgstr \"\"\n";
				foreach ( $this->headers as $header ) {
					$output .= $header . "\n";
				}
				$output .= "\n";
			}

			$entries_count = count( $this->entries );
			$counter = 0;
			foreach ( $this->entries as $entry ) {
				$is_obsolete = isset( $entry['obsolete'] ) && $entry['obsolete'];
				$is_plural   = isset( $entry['msgid_plural'] );

				if ( isset( $entry['tcomment'] ) ) {
					foreach ( $entry['tcomment'] as $comment ) {
						$output .= '# ' . $comment . "\n";
					}
				}

				if ( isset( $entry['ccomment'] ) ) {
					foreach ( $entry['ccomment'] as $comment ) {
						$output .= '#. ' . $comment . "\n";
					}
				}

				if ( isset( $entry['reference'] ) ) {
					foreach ( $entry['reference'] as $ref ) {
						$output .= '#: ' . $ref . "\n";
					}
				}

				if ( isset( $entry['flags'] ) && ! empty( $entry['flags'] ) ) {
					$output .= '#, ' . $entry['flags'] . "\n";
				}

				if ( isset( $entry['@'] ) ) {
					$output .= '#@ ' . $entry['@'] . "\n";
				}

				if ( isset( $entry['msgctxt'] ) ) {
					$output .= 'msgctxt ' . $this->clean_export( $entry['msgctxt'][0] ) . "\n";
				}

				if ( $is_obsolete ) {
					$output .= '#~ ';
				}

				if ( isset( $entry['msgid'] ) ) {

					// Special clean for msgid.
					if ( is_string( $entry['msgid'] ) ) {
						$msgid = explode( "\n", $entry['msgid'] );
					} elseif ( is_array( $entry['msgid'] ) ) {
						$msgid = $entry['msgid'];
					}

					$output .= 'msgid ';
					foreach ( $msgid as $i => $id ) {
						if ( $i > 0 && $is_obsolete ) {
							$output .= '#~ ';
						}
						$output .= $this->clean_export( $id ) . "\n";
					}
				}

				if ( isset( $entry['msgid_plural'] ) ) {

					// Special clean for msgid_plural.
					if ( is_string( $entry['msgid_plural'] ) ) {
						$msgid_plural = explode( "\n", $entry['msgid_plural'] );
					} elseif ( is_array( $entry['msgid_plural'] ) ) {
						$msgid_plural = $entry['msgid_plural'];
					}

					$output .= 'msgid_plural ';
					foreach ( $msgid_plural as $plural ) {
						$output .= $this->clean_export( $plural ) . "\n";
					}
				}

				if ( isset( $entry['msgstr'] ) ) {
					if ( $is_plural ) {
						foreach ( $entry['msgstr'] as $i => $t ) {
							$output .= "msgstr[$i] " . $this->clean_export( $t ) . "\n";
						}
					} else {
						foreach ( (array) $entry['msgstr'] as $i => $t ) {
							if ( 0 === $i ) {
								if ( $is_obsolete ) {
									$output .= '#~ ';
								}

								$output .= 'msgstr ' . $this->clean_export( $t ) . "\n";
							} else {
								if ( $is_obsolete ) {
									$output .= '#~ ';
								}

								$output .= $this->clean_export( $t ) . "\n";
							}
						}
					}
				}

				$counter++;
				// Avoid inserting an extra newline at end of file.
				if ( $counter < $entries_count ) {
					$output .= "\n";
				}
			}

			fclose( $handle );
		}

		return $output;
	}

	/**
	 * Prepares a string to be outputed into a file.
	 *
	 * @param $string. The string to be converted.
	 */
	protected function clean_export( $string ) {
		$quote = '"';
		$slash = '\\';
		$newline = "\n";

		$replaces = array(
			"$slash" => "$slash$slash",
			"$quote" => "$slash$quote",
			"\t"     => '\t',
		);

		$string = str_replace( array_keys( $replaces ), array_values( $replaces ), $string );
		$po = $quote . implode( "${slash}n$quote$newline$quote", explode( $newline, $string ) ) . $quote;

		// remove empty strings
		return str_replace( "$newline$quote$quote", '', $po );
	}

	/**
	 * Generates the internal key for a msgid.
	 */
	protected function entry_id( $entry ) {
		if ( isset( $entry['msgctxt'] ) ) {
			$id = implode( ',', (array) $entry['msgctxt'] ) . '!' . implode( ',', (array) $entry['msgid'] );
		} else {
			$id = implode( ',', (array) $entry['msgid'] );
		}

		return $id;
	}

	/**
	 * Undos `clean_export` actions on a string.
	 *
	 * @param $input
	 * @return string|array.
	 */
	protected function clean( $x ) {
		if ( is_array( $x ) ) {
			foreach ( $x as $k => $v ) {
				$x[ $k ] = $this->clean( $v );
			}
		} else {
			// Remove double quotes from start and end of string
			if ( '' === $x ) {
				return '';
			}

			if ( '"' === $x[0] ) {
				$x = substr( $x, 1, -1 );
			}

			$x = stripcslashes( $x );
		}

		return $x;
	}

	/**
	 * Checks if entry is a header by
	 */
	static protected function is_header( $entry ) {
		if ( empty( $entry ) || ! isset( $entry['msgstr'] ) ) {
			return false;
		}

		 $header_keys = array(
			 'Project-Id-Version:' => false,
			 'PO-Revision-Date:'   => false,
			 'MIME-Version:'       => false,
		 );

		 $count = count( $header_keys );
		 $keys = array_keys( $header_keys );

		 $header_items = 0;
		 foreach ( $entry['msgstr'] as $str ) {
			 $tokens = explode( ':', $str );
			 $tokens[0] = trim( $tokens[0], '"' ) . ':';

			 if ( in_array( $tokens[0], $keys ) ) {
				 $header_items++;
				 unset( $header_keys[ $tokens[0] ] );
				 $keys = array_keys( $header_keys );
			 }
		 }

		 return ( $header_items === $count ) ? true : false;
	}
}
