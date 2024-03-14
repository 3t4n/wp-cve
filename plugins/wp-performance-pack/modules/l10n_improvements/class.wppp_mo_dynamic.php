<?php
/**
 * WPPP_MO_Dynamic
 *
 * Class for dynamic loading and parsing of MO files
 * WPPP_MO_Dynamic only loads needed strings and uses hash tables, if present in MO file
 * additional caching can further improve performance
 *
 * Author: Bjoern Ahrens <bjoern@ahrens.net>
 * Author URI: http://www.bjoernahrens.de
 * Version: 2.2
 */


/**
 * Class holds information about a single MO file
 */
class WPPP_MO_Item {
	const PLURAL_SEP = "\x00";
	private $is_overloaded;

	public $fhandle = NULL;
	public $filename = '';
	public $is_open = false;

	var $total = 0;
	var $originals = array();
	var $originals_table;
	var $translations_table;

	var $hash_table;
	var $hash_length = 0;

	private function import_fail() {
		fclose( $this->fhandle );
		$this->fhandle = false;
		$this->is_open = false;
		unset( $this->originals );
		unset( $this->originals_table );
		unset( $this->translations_table );
		unset( $this->hash_table );
	}

	private function strlen( &$string ) {
		if ( $this->is_overloaded ) {
			return mb_strlen( $string, 'ascii' );
		} else {
			return strlen( $string );
		}
	}

	public function getOriginal( $index ) {
		if ( $this->originals_table[ $index ] > 0 ) {
			fseek( $this->fhandle, $this->originals_table[ $index * 2 + 1 ] );
			return fread( $this->fhandle, $this->originals_table[ $index * 2 ] );
		} else
			return '';
	}

	function __construct( $filename ) {
		$this->filename = $filename;
		$this->last_access = 0;
		$this->is_overloaded = ( ( ini_get( "mbstring.func_overload" ) & 2 ) != 0 ) && function_exists( 'mb_substr' );
	}

	function clear_reader() {
		if ( $this->fhandle !== NULL ) {
			fclose( $this->fhandle );
			$this->fhandle = NULL;
		}
		$this->is_open = false;
	}

	/**
	 * Get byte order of MO file.
	 *
	 * @return string V for little endian, N biug endian
	 */
	function get_byteorder() {
		$bytes = fread( $this->fhandle, 4 );
		if ( 4 != $this->strlen( $bytes ) )
			return false;
		$magic = unpack( 'V', $bytes );
		$magic = reset( $magic );

		// The magic is 0x950412de
		// bug in PHP 5.0.2, see https://savannah.nongnu.org/bugs/?func=detailitem&item_id=10565
		$magic_little = (int)-1794895138;
		$magic_little_64 = (int)2500072158;
		// 0xde120495
		$magic_big = ( (int)-569244523 ) & (int)0xFFFFFFFF; // explicit cast 0xFFFFFFFF to int so it doesn't become a float (= 4.294.967.295)
		if ($magic_little == $magic || $magic_little_64 == $magic) {
			return 'V';
		} else if ($magic_big == $magic) {
			return 'N';
		} else {
			return false;
		}
	}

	/**
	 * Open MO file and read initial data (including headers) from file.
	 * Also tests if the file is a valid MO file.
	 *
	 * @return bool|string false on error (file open error, invalid MO file), MO files header entry otherwise
	 */
	function open_mo_file() {
		$file_size = filesize( $this->filename );
		$this->fhandle = fopen( $this->filename, 'rb' );
		$this->is_open = true;

		$endian = $this->get_byteorder();
		if ( false === $endian ) {
			$this->import_fail();
			return false;
		}

		$header = fread( $this->fhandle, 24 );
		if ( $this->strlen( $header ) != 24 ) {
			$this->import_fail();
			return false;
		}

		// parse header
		$header = unpack( "{$endian}revision/{$endian}total/{$endian}originals_lenghts_addr/{$endian}translations_lenghts_addr/{$endian}hash_length/{$endian}hash_addr", $header );
		if ( !is_array( $header ) ) {
			$this->import_fail();
			return false;
		}
		extract( $header );

		// support revision 0 of MO format specs, only
		if ( $revision !== 0 ) {
			$this->import_fail();
			return false;
		}

		// read tables - order in file is: 1. original indices, 2. translation indices, (optional) 3. hash table
		$originals_lengths_length = $translations_lenghts_addr - $originals_lenghts_addr;
		if ( $originals_lengths_length != $total * 8 ) {
			$this->import_fail();
			return false;
		}
		$translations_lenghts_length = $hash_addr - $translations_lenghts_addr;
		if ( $translations_lenghts_length != $total * 8 ) {
			$this->import_fail();
			return false;
		}
		$this->hash_length = $hash_length;
		$this->total = $total;

		// read from file
		fseek( $this->fhandle, $originals_lenghts_addr );
		$str = fread( $this->fhandle, $originals_lengths_length + $translations_lenghts_length + ( $hash_length * 4 ) );
		if ( $this->strlen( $str ) != $originals_lengths_length + $translations_lenghts_length + ( $hash_length * 4 ) ) {
			$this->import_fail();
			return false;
		}
		$tables = array_chunk( unpack( $endian.( $total * 4 + $hash_length ), $str ), $total * 2 );
		if ( class_exists ( 'SplFixedArray' ) ) {
			$this->originals_table = SplFixedArray::fromArray( $tables[ 0 ], false );
			$this->translations_table = SplFixedArray::fromArray( $tables[ 1 ], false );
			if ( $hash_length > 0 )
				$this->hash_table = SplFixedArray::fromArray( $tables[ 2 ], false );
			unset( $tables );
		} else {
			$this->originals_table = $tables[ 0 ];
			$this->translations_table = $tables[ 1 ];
			if ( $hash_length > 0 )
				$this->hash_table = $tables[ 2 ];
		}

		// "sanity checks" ( tests for corrupted mo file )
		for ( $i = 0, $max = $total * 2; $i < $max; $i += 2 ) {
			if ( $this->originals_table[ $i + 1 ] + $this->originals_table[ $i ] > $file_size
				 || $this->translations_table[ $i + 1 ] + $this->translations_table[ $i ] > $file_size ) {
				$this->import_fail();
				return false;
			}
		}

		// search and return header entry (empty original string)
		for ( $i = 0, $max = $total * 2; $i < $max; $i += 2 ) {
			// Search emtpy original. Usually the first entry in the MO file.
			$original = '';
			if ( $this->originals_table[ $i ] > 0 ) {
				fseek( $this->fhandle, $this->originals_table[ $i + 1 ] );
				$original = fread( $this->fhandle, $this->originals_table[ $i ] );

				$j = strpos( $original, self::PLURAL_SEP );
				if ( $j !== false )
					$original = substr( $original, 0, $j );
			}

			if ( $original === '' ) {
				if ( $this->translations_table[ $i ] > 0 ) {
					fseek( $this->fhandle, $this->translations_table[ $i + 1 ] );
					return fread( $this->fhandle, $this->translations_table[ $i ] );
				} else {
					return '';
				}
			}
		}
		return '';
	}
}

/**
 * Class for working with MO files
 * Translation entries are created dynamically.
 * Due to this export and save functions are not implemented.
 */
class WPPP_MO_dynamic extends Gettext_Translations {
	const PLURAL_SEP = "\x00";
	const CONTEXT_SEP = "\x04";

	private $caching = false;
	private $modified = false;
	private $cachegroup;

	protected $domain = '';
	protected $MOs = array();
	protected $translations = NULL;
	protected $base_translations = NULL; 

	function __construct( $domain, $caching = false, $cachegroup = '' ) {
		$this->domain = $domain;
		$this->caching = $caching;
		if ( $caching ) {
			add_action ( 'shutdown', array( $this, 'save_to_cache' ) );
			add_action ( 'admin_init', array( $this, 'save_base_translations' ), 100 );
		}
		// Reader has to be destroyed befor any upgrades or else upgrade might fail, if a
		// reader is loaded (cannot delete old plugin/theme/etc. because a language file
		// is still opened).
		add_filter( 'upgrader_pre_install', array( $this, 'clear_reader_before_upgrade' ), 10, 2 );
	}

	function unhook_and_close () {
		remove_action ( 'shutdown', array( $this, 'save_to_cache' ) );
		remove_action ( 'admin_init', array( $this, 'save_base_translations' ), 100 );
		foreach ( $this->MOs as $moitem ) {
			$moitem->clear_reader();
		}
		$this->MOs = array();
	}

	function __destruct() {
		foreach ( $this->MOs as $moitem ) {
			$moitem->clear_reader();
		}
	}

	function clear_reader_before_upgrade($return, $plugin) {
		// stripped down copy of class-wp-upgrader.php Plugin_Upgrader::deactivate_plugin_before_upgrade
		if ( is_wp_error($return) ) //Bypass.
			return $return;

		foreach ( $this->MOs as $moitem ) {
			$moitem->clear_reader();
		}
	}

	function get_current_url () {
		$current_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		if ( ( $len = strlen( $_SERVER['QUERY_STRING'] ) ) > 0 ) {
			$current_url = substr( $current_url, 0, strlen($current_url) - $len - 1 );
		}
		if ( substr( $current_url, -10 ) === '/wp-admin/' ) {
			$current_url .= 'index.php';
		}
		if ( isset( $_GET['page'] ) ) {
			$current_url .= '?page=' . $_GET['page'];
		}
		return $current_url;
	}

	function import_from_file( $filename ) {
		$this->MOs[] = new WPPP_MO_Item( $filename );
		// because only a reference to the MO file is created, at this point there is no information if $filename is a valid MO file, so the return value is always true
		return true;
	}

	function save_base_translations () {
		if ( is_admin() && $this->translations !== NULL && $this->base_translations === NULL ) {
			$this->base_translations = $this->translations;
			$this->translations = array();
		}
	}

	private function cache_get ( $key, $cache_time ) {
		$t = wp_cache_get( $key, $this->cachegroup );
		if ( $t !== false && isset( $t['data'] ) ) {
			// check soft expire
			if ( $t['softexpire'] < time() ) {
				// update cache with new soft expire time
				$t['softexpire'] = time() + ( $cache_time - ( 5 * MINUTE_IN_SECONDS ) );
				wp_cache_replace( $key, $t, $this->cachegroup, $cache_time );
			}
			return json_decode( gzuncompress( $t['data'] ), true );
		}
		return NULL;
	}

	private function cache_set ( $key, $cache_time, $data ) {
		$t = array();
		$t['softexpire'] = time() + ( $cache_time - ( 5 * MINUTE_IN_SECONDS ) );
		$t['data'] = gzcompress( json_encode( $data ) );
		wp_cache_set( $key, $t, $this->cachegroup, $cache_time );
	}

	function import_domain_from_cache () {
		// build cache key from domain and request uri
		if ( $this->caching ) {
			if ( is_admin() ) {
				$this->base_translations = $this->cache_get( 'backend_' . $this->domain, HOUR_IN_SECONDS );
				$this->translations = $this->cache_get( 'backend_' . $this->domain . '_' . $this->get_current_url(), 30 * MINUTE_IN_SECONDS );
			} else {
				$this->translations = $this->cache_get( 'frontend_' . $this->domain, HOUR_IN_SECONDS );
			}
		}

		if ( $this->translations === NULL ) {
			$this->translations = array();
		}
	}

	function save_to_cache () {
		if ( $this->modified ) {
			if ( is_admin() ) {
				$this->cache_set( 'backend_' . $this->domain . '_' . $this->get_current_url(), 30 * MINUTE_IN_SECONDS, $this->translations ); // keep admin page cache for 30 minutes
				if ( ( $this->base_translations != NULL ) && ( count( $this->base_translations ) > 0 ) ) {
					$this->cache_set( 'backend_'.$this->domain, HOUR_IN_SECONDS, $this->base_translations ); // keep admin base cache for 60 minutes
				}
			} else {
				$this->cache_set( 'frontend_'.$this->domain, HOUR_IN_SECONDS, $this->translations ); // keep front end cache for 60 minutes
			}
		}
	}

	protected function search_translation( $key ) {
		$hash_val = NULL;
		// gettext uses "\n" only as line breaks, so adjust "\r\n" to "\n"
		$key = str_replace( "\r\n", "\n", $key );
		$key_len = strlen( $key );

		for ( $j = 0, $max = count( $this->MOs ); $j < $max; $j++ ) {
			$moitem = $this->MOs[ $j ];
			if ( !$moitem ) {
				// handle null items
				continue;
			}
			if ( !$moitem->is_open ) {
				$header = $moitem->open_mo_file();
				if ( $header === false ) {
					// Error reading MO file, so delete it from MO list to prevent subsequent access
					unset( $this->MOs[ $j ] );
					return false; // return or continue?
				}
				$this->set_headers( $this->make_headers( $header ) );
			}

			if ( $moitem->hash_length > 0 ) {
				/* Use mo file hash table to search translation */

				// calculate hash value
				// hashpjw function by P.J. Weinberger from gettext hash-string.c
				// adapted to php and its quirkiness caused by missing unsigned ints and shift operators...
				if ( $hash_val === NULL) {
					$hash_val = 0;
					// unpack is faster than accessing every single char by ord(char)
					foreach ( unpack( 'C*', $key ) as $char ) {
						$hash_val = ( $hash_val << 4 ) + $char;
						if( 0 !== ( $g = $hash_val & (int)0xF0000000 ) ){
							if ( $g < 0 )
								$hash_val ^= ( ( ( $g & (int)0x7FFFFFFF ) >> 24 ) | (int)0x80 ); // wordaround: php operator >> is arithmetic, not logic, so shifting negative values gives unexpected results. Cut sign bit, shift right, set sign bit again.
								/* 
								workaround based on this function (adapted to actual used parameters):
								
								function shr($var,$amt) {
									$mask = 0x40000000;
									if($var < 0) {
										$var &= 0x7FFFFFFF;
										$mask = $mask >> ($amt-1);
										return ($var >> $amt) | $mask;
									}
									return $var >> $amt;
								} 
								*/
							else
								$hash_val ^= ( $g >> 24 );
							$hash_val ^= $g;
						}
					}
				}

				// calculate hash table index and increment
				if ( $hash_val >= 0 ) {
					$idx = $hash_val % $moitem->hash_length;
					$incr = 1 + ( $hash_val % ( $moitem->hash_length - 2 ) );
				} else {
					$hash_val = (float) sprintf( '%u', $hash_val ); // workaround php not knowing unsigned int - %u outputs $hval as unsigned, then cast to float 
					$idx = fmod( $hash_val, $moitem->hash_length );
					$incr = 1 + fmod( $hash_val, ( $moitem->hash_length - 2 ) );
				}

				if ( isset( $moitem->hash_table[ $idx ] ) ) {
					$orig_idx = $moitem->hash_table[ $idx ];
				} else {
					$orig_idx = 0;
				}
				while ( $orig_idx != 0 ) {
					$orig_idx--; // index adjustment

					$pos = $orig_idx * 2;
					if ( $orig_idx < $moitem->total // orig_idx must be in range
						 && $moitem->originals_table[$pos] >= $key_len ) { // and original length must be equal or greater as key length (original can contain plural forms)

						// read original string
						if ( $moitem->originals_table[ $pos ] > 0 ) {
							fseek( $moitem->fhandle, $moitem->originals_table[ $pos + 1 ] );
							$mo_original = fread( $moitem->fhandle, $moitem->originals_table[ $pos ] );

							// strings can only match if they have the same length, no need to inspect otherwise
							if ( $moitem->originals_table[ $pos ] == $key_len )
								$cmpval = ( $key === $mo_original );
							elseif ( $mo_original[ $key_len ] == self::PLURAL_SEP )
								$cmpval = substr_compare( $key, $mo_original, 0, $key_len );
							else 
								$cmpval = false;
						} else
							$cmpval = ( $key === '' );


						if ( $cmpval ) {
							// key found, read translation string
							fseek( $moitem->fhandle, $moitem->translations_table[$pos+1] );
							$translation = fread( $moitem->fhandle, $moitem->translations_table[$pos] );
							if ( $j > 0 ) {
								// Assuming frequent subsequent translations from the same file move current moitem to front of array.
								unset( $this->MOs[ $j ] );
								array_unshift( $this->MOs, $moitem );
							}
							return $translation;
						}
					}

					if ($idx >= $moitem->hash_length - $incr)
						$idx -= ($moitem->hash_length - $incr);
					else
						$idx += $incr;
					$orig_idx = $moitem->hash_table[$idx];
				}
			} else {
				/* No hash-table, do binary search for matching originals entry */
				$left = 0;
				$right = $moitem->total-1;

				while ( $left <= $right ) {
					$pivot = $left + (int) ( ( $right - $left ) / 2 );
					$pos = $pivot * 2;

					if ( isset( $moitem->originals[$pivot] ) ) {
						$mo_original = $moitem->originals[$pivot];
					} else {
						// read and "cache" original string to improve performance of subsequent searches
						if ( $moitem->originals_table[$pos] > 0 ) {
							fseek( $moitem->fhandle, $moitem->originals_table[$pos+1] );
							$mo_original = fread( $moitem->fhandle, $moitem->originals_table[$pos] );
						} else {
							$mo_original = '';
						}
						$moitem->originals[$pivot] = $mo_original;
					}

					if ( false !== ( $i = strpos( $mo_original, self::PLURAL_SEP ) ) )
						$cmpval = strncmp( $key, $mo_original, $i );
					else
						$cmpval = strcmp( $key, $mo_original );

					if ( $cmpval === 0 ) {
						// key found read translation string
						fseek( $moitem->fhandle, $moitem->translations_table[$pos+1] );
						$translation = fread( $moitem->fhandle, $moitem->translations_table[$pos] );
						if ( $j > 0 ) {
							// Assuming frequent subsequent translations from the same file move current moitem to front of array.
							unset( $this->MOs[ $j ] );
							array_unshift( $this->MOs, $moitem );
						}
						return $translation;
					} else if ( $cmpval < 0 ) {
						$right = $pivot - 1;
					} else { // if ($cmpval>0) 
						$left = $pivot + 1;
					}
				}
			}
		}
		// key not found
		return false;
	}

	function translate( $singular, $context = NULL ) {
		if ( !isset( $singular[ 0 ] ) )
			return $singular;

		if ( $context == NULL ) {
			$s = $singular;
		} else {
			$s = $context . self::CONTEXT_SEP . $singular;
		}

		if ( $this->translations === NULL ) {
			$this->import_domain_from_cache();
		}

		if ( isset( $this->translations[$s] ) ) {
			$t = $this->translations[$s];
		} elseif ( isset ($this->base_translations[$s] ) ) {
			$t = $this->base_translations[$s];
		} else {
			if ( false !== ( $t = $this->search_translation( $s ) ) ) {
				$this->translations[$s] = $t;
				$this->modified = true;
			}
		}

		if ( $t !== false ) {
			if ( false !== ( $i = strpos( $t, self::PLURAL_SEP ) ) ) {
				return substr( $t, 0, $i );
			} else {
				return $t;
			}
		} else {
			$this->translations[$s] = $singular;
			$this->modified = true;
			return $singular;
		}
	}

	function translate_plural( $singular, $plural, $count, $context = NULL ) {
		if ( !isset( $singular[ 0 ] ) )
			return $singular;

		// Get the "default" return-value
		$default = ($count == 1 ? $singular : $plural);

		if ( $context == NULL ) {
			$s = $singular;
		} else {
			$s = $context . self::CONTEXT_SEP . $singular;
		}

		if ( $this->translations === NULL ) {
			$this->import_domain_from_cache();
		}

		if ( isset( $this->translations[$s] ) ) {
			$t = $this->translations[$s];
		} elseif ( isset ($this->base_translations[$s] ) ) {
			$t = $this->base_translations[$s];
		} else {
			if ( false !== ( $t = $this->search_translation( $s ) ) ) {
				$this->translations[$s] = $t;
				$this->modified = true;
			}
		}

		if ( $t !== false ) {
			if ( isset( $this->_nplurals ) ) {
				$ts =  explode( self::PLURAL_SEP, $t, $this->_nplurals );
				$i = $this->gettext_select_plural_form( (int)$count ); // Sometimes $count passed to translate_plural isn't an int value, so explicitly cast it to int to avoid warnings
				if ( isset( $ts[ $i ] ) ) {
					return $ts[ $i ];
				} else { 
					return $default;
				}
			} else {
				return $default;
			}
		} else {
			$this->translations[$s] = $singular . self::PLURAL_SEP . $plural;
			$this->modified = true;
			return $default;
		}
	}

	function merge_with( &$other ) {
		if ( $other instanceof WPPP_MO_dynamic ) {
			if ( $other->translations !== NULL ) {
				foreach( $other->translations as $key => $translation ) {
					$this->translations[$key] = $translation;
				}
			}
			if ( $other->base_translations !== NULL ) {
				foreach( $other->base_translations as $key => $translation ) {
					$this->base_translations[$key] = $translation;
				}
			}

			foreach ( $other->MOs as $moitem ) {
				$i = 0;
				$c = count( $this->MOs );
				$found = false;
				while ( !$found && ( $i < $c ) ) {
					$found = $this->MOs[$i]->filename == $moitem->filename;
					$i++;
				}
				if ( !$found )
					$this->MOs[] = $moitem;
			}
		}
	}

	function select_plural_form( $count ) {
		return $this->gettext_select_plural_form( $count );
	}

	function get_plural_forms_count() {
		return $this->_nplurals;
	}

	function MO_file_loaded ( $mofile ) {
		foreach ( $this->MOs as $moitem ) {
			if ( $moitem->filename == $mofile ) {
				return true;
			}
		}
		return false;
	}
}