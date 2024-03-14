<?php defined( 'ABSPATH' ) || exit;
// 1.1.4 (10-08-2023)
// Maxim Glazunov (https://icopydoc.ru)
// This code adds several useful functions to the WordPress.

if ( ! function_exists( 'get_array_as_string' ) ) {
	/**
	 * Converts an array to an easy-to-read format
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	array|string|object	$text (require)
	 * @param	string				$new_line (not require)
	 * @param	int					$i (not require)
	 * @param	string				$res (not require)
	 *
	 * @return	string
	 */
	function get_array_as_string( $text, $new_line = PHP_EOL, $i = 0, $res = '' ) {
		$tab = '';
		for ( $x = 0; $x < $i; $x++ ) {
			$tab = '---' . $tab;
		}
		if ( is_object( $text ) ) {
			$text = (array) $text;
		}
		if ( is_array( $text ) ) {
			$i++;
			foreach ( $text as $key => $value ) {
				if ( is_array( $value ) ) { // массив
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")";
					$res .= $tab . get_array_as_string( $value, $new_line, $i );
				} else if ( is_object( $value ) ) { // объект
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")";
					$value = (array) $value;
					$res .= $tab . get_array_as_string( $value, $new_line, $i );
				} else {
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")" . $value;
				}
			}
		} else {
			$res .= $new_line . $tab . $text;
		}
		return $res;
	}
}

if ( ! function_exists( 'get_from_url' ) ) {
	/**
	 * Return URL without GET parameters or just GET parameters without URL
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$url (require)
	 * @param	string			$whot (not require)
	 *
	 * @return	string|false
	 */
	function get_from_url( $url, $whot = 'url' ) {
		$url = str_replace( "&amp;", "&", $url ); // Заменяем сущности на амперсанд, если требуется
		// Разбиваем URL на 2 части: до знака ? и после
		list( $url_part, $get_part ) = array_pad( explode( "?", $url ), 2, "" );
		switch ( $whot ) {
			case "url":
				$url_part = str_replace( " ", "%20", $url_part ); // заменим пробел на сущность
				return $url_part; // Возвращаем URL без get-параметров (до знака вопроса)
			case "get_params":
				return $get_part; // Возвращаем get-параметры (без знака вопроса)
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'univ_option_add' ) ) {
	/**
	 * Returns what might be the result of a add_blog_option or add_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	mixed			$value (require)
	 * @param	string|bool		$autoload (not require) (yes/no or true/false)
	 *
	 * @return	bool
	 */
	function univ_option_add( $option_name, $value, $autoload = 'no' ) {
		if ( is_multisite() ) {
			return add_blog_option( get_current_blog_id(), $option_name, $value );
		} else {
			return add_option( $option_name, $value, '', $autoload );
		}
	}
}

if ( ! function_exists( 'univ_option_upd' ) ) {
	/** 
	 * Returns what might be the result of a update_blog_option or update_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	mixed			$newvalue (require)
	 * @param	string|bool 	$autoload (not require) (yes/no or true/false)
	 *
	 * @return	bool
	 */
	function univ_option_upd( $option_name, $newvalue, $autoload = 'no' ) {
		if ( is_multisite() ) {
			return update_blog_option( get_current_blog_id(), $option_name, $newvalue );
		} else {
			return update_option( $option_name, $newvalue, $autoload );
		}
	}
}

if ( ! function_exists( 'univ_option_get' ) ) {
	/**
	 * Returns what might be the result of a get_blog_option or get_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	mixed			$default (not require) - value to return if the option does not exist
	 *
	 * @return	mixed
	 */
	function univ_option_get( $option_name, $default = false ) {
		if ( is_multisite() ) {
			return get_blog_option( get_current_blog_id(), $option_name, $default );
		} else {
			return get_option( $option_name, $default );
		}
	}
}

if ( ! function_exists( 'univ_option_del' ) ) {
	/**
	 * Returns what might be the result of a delete_blog_option or delete_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 *
	 * @return	bool
	 */
	function univ_option_del( $option_name ) {
		if ( is_multisite() ) {
			return delete_blog_option( get_current_blog_id(), $option_name );
		} else {
			return delete_option( $option_name );
		}
	}
}

if ( ! function_exists( 'translit_cyr_en' ) ) {
	/**
	 * Returns a formatted string
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$str (require)
	 *
	 * @return	string
	 */
	function translit_cyr_en( $str ) {
		$converter_arr = [ 
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
			'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
			'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
			'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
			'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
			'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
			'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
		];

		$str = mb_strtolower( $str );
		$str = strtr( $str, $converter_arr );
		$str = mb_ereg_replace( '[^-0-9a-z]', '-', $str );
		$str = mb_ereg_replace( '[-]+', '-', $str );
		$str = trim( $str, '-' );
		return $str;
	}
}

if ( ! function_exists( 'get_file_extension' ) ) {
	/**
	 * Returns a file extension or null
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$fileurl (require)
	 *
	 * @return	string|null
	 */
	function get_file_extension( $fileurl ) {
		$path_info = pathinfo( $fileurl );
		if ( isset( $path_info['extension'] ) ) {
			return $path_info['extension'];
		} else {
			return null;
		}
	}
}

if ( ! function_exists( 'print_html_tags_option' ) ) {
	/**
	 * Get or prints html option tags
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$opt_val (require)
	 * @param	array			$opt_data_arr (not require)
	 * @param	bool			$is_echo (not require)
	 * @param	string			$result (not require)
	 *
	 * @return	string|void
	 */
	function print_html_tags_option( $opt_val, $opt_data_arr = [], $is_echo = true, $result = '' ) {
		if ( ! empty( $opt_data_arr ) ) {
			for ( $i = 0; $i < count( $opt_data_arr ); $i++ ) {
				$result .= sprintf( '<option value="%1$s" %2$s>%3$s</option>',
					$opt_data_arr[ $i ][1],
					selected( $opt_val, $opt_data_arr[ $i ][1] ),
					$opt_data_arr[ $i ][0]
				);
			}
		}
		if ( true === $is_echo ) {
			$allowed_html = [ 
				'option' => [ 
					'value' => true,
					'selected' => true
				],
				'p' => [],
				'br' => [],
				'small' => [],
				'strong' => [],
				'i' => [],
				'em' => []
			];
			echo wp_kses( $result, $allowed_html );
		} else {
			return $result;
		}
	}
}

if ( ! function_exists( 'common_option_add' ) ) {
	/**
	 * Returns what might be the result of a add_blog_option or add_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	mixed			$value (require)
	 * @param	string|bool		$autoload (not require) (yes/no or true/false)
	 * @param	string			$n (not require) (key in the array of common settings)
	 * @param	string			$slug (not require)
	 *
	 * @return	bool
	 */
	function common_option_add( $option_name, $value, $autoload = 'no', $n = '0', $slug = '' ) {
		if ( $n === '0' ) {
			$option_name_in_db = $option_name;
			unset( $option_name );
			$value_in_db = $value;
			unset( $value );
		} else {
			$option_name_in_db = $slug . '_settings_arr';
			$settings_arr = univ_option_get( $option_name_in_db );
			$settings_arr[ $n ][ $option_name ] = $value;
			$value_in_db = $settings_arr;
		}

		if ( is_multisite() ) {
			return add_blog_option( get_current_blog_id(), $option_name_in_db, $value_in_db );
		} else {
			return add_option( $option_name_in_db, $value_in_db, '', $autoload );
		}
	}
}

if ( ! function_exists( 'common_option_upd' ) ) {
	/**
	 * Returns what might be the result of a update_blog_option or update_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	mixed			$value (require)
	 * @param	string|bool		$autoload (not require) (yes/no or true/false)
	 * @param	string			$n (not require) (key in the array of common settings)
	 * @param	string			$slug (not require)
	 *
	 * @return	bool
	 */
	function common_option_upd( $option_name, $value, $autoload = 'no', $n = '0', $slug = '' ) {
		if ( $n === '0' ) {
			$option_name_in_db = $option_name;
			unset( $option_name );
			$value_in_db = $value;
			unset( $value );
		} else {
			$option_name_in_db = $slug . '_settings_arr';
			$settings_arr = common_option_get( $option_name_in_db );
			if ( is_array( $settings_arr ) ) {
				$settings_arr[ $n ][ $option_name ] = $value;
			} else {
				$settings_arr = [];
				$settings_arr[ $n ][ $option_name ] = $value;
			}
			$value_in_db = $settings_arr;
		}

		if ( is_multisite() ) {
			return update_blog_option( get_current_blog_id(), $option_name_in_db, $value_in_db );
		} else {
			return update_option( $option_name_in_db, $value_in_db, $autoload );
		}
	}
}

if ( ! function_exists( 'common_option_get' ) ) {
	/**
	 * Returns what might be the result of a get_blog_option or get_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	mixed			$default (not require) - value to return if the option does not exist
	 * @param	string			$n (not require) (key in the array of common settings)
	 * @param	string			$slug (not require)
	 *
	 * @return	mixed
	 */
	function common_option_get( $option_name, $default = false, $n = '0', $slug = '' ) {
		if ( $n === '0' ) {
			$option_name_in_db = $option_name;
		} else {
			$option_name_in_db = $slug . '_settings_arr';
			$settings_arr = common_option_get( $option_name_in_db, [] );
			if ( isset( $settings_arr[ $n ][ $option_name ] ) ) {
				return $settings_arr[ $n ][ $option_name ];
			} else {
				return $default;
			}
		}

		if ( is_multisite() ) {
			return get_blog_option( get_current_blog_id(), $option_name_in_db, $default );
		} else {
			return get_option( $option_name_in_db, $default );
		}
	}
}

if ( ! function_exists( 'common_option_del' ) ) {
	/**
	 * Returns what might be the result of a delete_blog_option or delete_option
	 * 
	 * @since 1.0.0 (23-05-2023)
	 *
	 * @param	string			$option_name (require)
	 * @param	string			$n (not require) (key in the array of common settings)
	 * @param	string			$slug (not require)
	 *
	 * @return	bool
	 */
	function common_option_del( $option_name, $n = '0', $slug = '' ) {
		if ( $n === '0' ) {
			$option_name_in_db = $option_name;
		} else {
			$option_name_in_db = $slug . '_settings_arr';
			$settings_arr = common_option_get( $option_name_in_db, [] );
			if ( isset( $settings_arr[ $n ][ $option_name ] ) ) {
				unset( $settings_arr[ $n ][ $option_name ] );
				if ( is_multisite() ) {
					return update_blog_option( get_current_blog_id(), $option_name_in_db, $settings_arr );
				} else {
					return update_option( $option_name_in_db, $settings_arr );
				}
			} else {
				return false;
			}
		}

		if ( is_multisite() ) {
			return delete_blog_option( get_current_blog_id(), $option_name_in_db );
		} else {
			return delete_option( $option_name_in_db );
		}
	}
}

if ( ! function_exists( 'get_domain' ) ) {
	/**
	 * Return domain
	 * 
	 * @since 1.1.0 (25-05-2023)
	 *
	 * @param string 			$url (require)
	 * @param bool 				$f (not require)
	 *
	 * @return string
	 */
	function get_domain( $url, $f = true ) {
		if ( $f == true ) {
			$pieces = parse_url( $url );
			if ( isset( $pieces['host'] ) ) {
				$url = str_replace( "/", "", $pieces['host'] );
			} else {
				$url = str_replace( "/", "", $pieces['path'] );
			}
		}

		$count = substr_count( $url, '.' );
		if ( $count === 2 ) {
			if ( strlen( explode( '.', $url )[1] ) > 3 ) {
				$url = explode( '.', $url, 2 )[1];
			}
		} else if ( $count > 2 ) {
			$url = get_domain( explode( '.', $url, 2 )[1], false );
		}
		return $url;
	}
}

if ( ! function_exists( 'remove_all_elementor_tags' ) ) {
	/**
	 * The function removes all elementor tags
	 * 
	 * @since 1.1.2 (15-05-2023)
	 *
	 * @param string $content (require)
	 *
	 * @return string
	 */
	function remove_all_elementor_tags( $content ) {
		// remove <div class="elementor-widget-container"></div>
		$re = "/ ?<div[^<]*?class=[\"'][^\"']*\\belementor-widget-container\\b[^\"']*[\"'][^<]*?>| ?<\\/div>/s";
		$content = preg_replace( $re, "", $content );

		// remove <div class="elementor-element"></div>
		$re = "/ ?<div[^<]*?class=[\"'][^\"']*\\belementor-element\\b[^\"']*[\"'][^<]*?>| ?<\\/div>/s";
		$content = preg_replace( $re, "", $content );

		// remove <div class="elementor-container"></div>
		$re = "/ ?<div[^<]*?class=[\"'][^\"']*\\belementor-container\\b[^\"']*[\"'][^<]*?>| ?<\\/div>/s";
		$content = preg_replace( $re, "", $content );

		// remove <div class="elementor-section-wrap"></div>
		$re = "/ ?<div[^<]*?class=[\"'][^\"']*\\belementor-section-wrap\\b[^\"']*[\"'][^<]*?>| ?<\\/div>/s";
		$content = preg_replace( $re, "", $content );

		// remove <section class="elementor-section"></div>
		$re = "/ ?<section[^<]*?class=[\"'][^\"']*\\belementor-section\\b[^\"']*[\"'][^<]*?>| ?<\\/section>/s";
		$content = preg_replace( $re, "", $content );

		// remove <div class="elementor"></div>
		$re = "/ ?<div[^<]*?class=[\"'][^\"']*\\belementor\\b[^\"']*[\"'][^<]*?>| ?<\\/div/s";

		$content = preg_replace( $re, "", $content );
		return $content;
	}

	// Это удалит все классы и свойства тегов в вашем HTML-коде, которые содержат elementor:
	// $content = preg_replace('/class="[^"]*elementor[^"]*"/', '', $content);
	// $content = preg_replace('/[^"]*elementor[^"]*="/', '', $content);
}

if ( ! function_exists( 'remove_directory' ) ) {
	/**
	 * Remove non-empty directories
	 * 
	 * @since 1.1.2 (15-05-2023)
	 *
	 * @param string $dir (require)
	 *
	 * @return void
	 */
	function remove_directory( $dir ) {
		if ( $objs = glob( $dir . "/*" ) ) {
			foreach ( $objs as $obj ) {
				if ( is_dir( $obj ) ) {
					remove_directory( $obj );
				} else {
					unlink( $obj );
				}
			}
		}
		rmdir( $dir );
	}
}

if ( ! function_exists( 'get_format_filesize' ) ) {
	/**
	 * Get format filesize
	 * 
	 * @since 1.1.4 (10-08-2023)
	 * 
	 * @param int|false $bytes
	 * 
	 * @return string
	 */
	function get_format_filesize( $bytes ) {
		if ( false === $bytes ) {
			return '0 B';
		}
		if ( $bytes >= 1073741824 ) {
			$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
		} elseif ( $bytes >= 1048576 ) {
			$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
		} elseif ( $bytes >= 1024 ) {
			$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
		} elseif ( $bytes > 1 ) {
			$bytes = $bytes . ' B';
		} elseif ( $bytes == 1 ) {
			$bytes = $bytes . ' B';
		} else {
			$bytes = '0 B';
		}
		return $bytes;
	}
}