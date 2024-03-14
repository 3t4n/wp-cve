<?php
/**
 * Helpers functions
 *
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 11.12.2018, Webcraftic
 * @version 1.0
 */

defined( 'ABSPATH' ) || die( 'Cheatin&#8217; uh?' );

if ( ! function_exists( 'wbcr_ctlr_sanitize_title' ) ) {
	/**
	 * Filters all action calls sinitize_title and sanitize_file_name, returns the converted string in Latin.
	 *
	 * @param string $title processed header
	 *
	 * @return string
	 */
	function wbcr_ctlr_sanitize_title( $title ) {
		global $wpdb;
		
		$origin_title = $title;
		
		$is_term   = false;
		$backtrace = debug_backtrace();
		foreach ( $backtrace as $backtrace_entry ) {
			if ( $backtrace_entry['function'] == 'wp_insert_term' ) {
				$is_term = true;
				break;
			}
		}
		
		if ( ! is_admin() ) {
			foreach ( $backtrace as $backtrace_entry ) {
				if ( isset( $backtrace_entry['function'] ) && isset( $backtrace_entry['class'] ) ) {
					$is_query = in_array( $backtrace_entry['function'], array(
						'query_posts',
						'get_terms'
					) ) and in_array( $backtrace_entry['class'], array( 'WP', 'WP_Term_Query' ) );
					
					if ( $is_query ) {
						return $origin_title;
					}
				}
			}
		}
		
		$term = $is_term ? $wpdb->get_var( $wpdb->prepare( "SELECT slug FROM {$wpdb->terms} WHERE name = '%s'", $title ) ) : '';
		
		if ( empty( $term ) ) {
			$title = wbcr_ctlr_transliterate( $title );
		} else {
			$title = $term;
		}
		
		return apply_filters( 'wbcr_ctl_sanitize_title', $title, $origin_title );
	}
}

if ( ! function_exists( 'wbcr_ctlr_transliterate' ) ) {
	/**
	 * Clears special characters and converts all characters to Latin characters.
	 *
	 * @since 1.1.1
	 *
	 * @param string $titles
	 * @param bool $ignore_special_symbols
	 *
	 * @return string
	 */
	function wbcr_ctlr_transliterate( $title, $ignore_special_symbols = false ) {
		$origin_title = $title;
		$iso9_table   = wbcr_ctlr_get_symbols_pack();
		
		$title = strtr( $title, $iso9_table );
		
		if ( function_exists( 'iconv' ) ) {
			$title = iconv( 'UTF-8', 'UTF-8//TRANSLIT//IGNORE', $title );
		}
		
		if ( ! $ignore_special_symbols ) {
			$title = preg_replace( "/[^A-Za-z0-9'_\-\.]/", '-', $title );
			$title = preg_replace( '/\-+/', '-', $title );
			$title = preg_replace( '/^-+/', '', $title );
			$title = preg_replace( '/-+$/', '', $title );
		}
		
		return apply_filters( 'wbcr_ctl_transliterate', $title, $origin_title, $iso9_table );
	}
}

if ( ! function_exists( 'wbcr_ctlr_get_symbols_pack' ) ) {
	
	/**
	 * Function returns the base of characters depending on the installed locale.
	 *
	 * @since 1.1.1
	 * @return array
	 */
	function wbcr_ctlr_get_symbols_pack() {
		$loc = get_locale();
		
		$ret = array(
			// russian
			'А'  => 'A',
			'а'  => 'a',
			'Б'  => 'B',
			'б'  => 'b',
			'В'  => 'V',
			'в'  => 'v',
			'Г'  => 'G',
			'г'  => 'g',
			'Д'  => 'D',
			'д'  => 'd',
			'Е'  => 'E',
			'е'  => 'e',
			'Ё'  => 'Jo',
			'ё'  => 'jo',
			'Ж'  => 'Zh',
			'ж'  => 'zh',
			'З'  => 'Z',
			'з'  => 'z',
			'И'  => 'I',
			'и'  => 'i',
			'Й'  => 'J',
			'й'  => 'j',
			'К'  => 'K',
			'к'  => 'k',
			'Л'  => 'L',
			'л'  => 'l',
			'М'  => 'M',
			'м'  => 'm',
			'Н'  => 'N',
			'н'  => 'n',
			'О'  => 'O',
			'о'  => 'o',
			'П'  => 'P',
			'п'  => 'p',
			'Р'  => 'R',
			'р'  => 'r',
			'С'  => 'S',
			'с'  => 's',
			'Т'  => 'T',
			'т'  => 't',
			'У'  => 'U',
			'у'  => 'u',
			'Ф'  => 'F',
			'ф'  => 'f',
			'Х'  => 'H',
			'х'  => 'h',
			'Ц'  => 'C',
			'ц'  => 'c',
			'Ч'  => 'Ch',
			'ч'  => 'ch',
			'Ш'  => 'Sh',
			'ш'  => 'sh',
			'Щ'  => 'Shh',
			'щ'  => 'shh',
			'Ъ'  => '',
			'ъ'  => '',
			'Ы'  => 'Y',
			'ы'  => 'y',
			'Ь'  => '',
			'ь'  => '',
			'Э'  => 'E',
			'э'  => 'e',
			'Ю'  => 'Ju',
			'ю'  => 'ju',
			'Я'  => 'Ya',
			'я'  => 'ya',
			// global
			'Ґ'  => 'G',
			'ґ'  => 'g',
			'Є'  => 'Ie',
			'є'  => 'ie',
			'І'  => 'I',
			'і'  => 'i',
			'Ї'  => 'I',
			'ї'  => 'i',
			'Ї' => 'i',
			'ї' => 'i',
			'Ё' => 'Jo',
			'ё' => 'jo',
			'й' => 'i',
			'Й' => 'I'
		);
		
		// ukrainian
		if ( $loc == 'uk' ) {
			$ret = array_merge( $ret, array(
				'Г' => 'H',
				'г' => 'h',
				'И' => 'Y',
				'и' => 'y',
				'Х' => 'Kh',
				'х' => 'kh',
				'Ц' => 'Ts',
				'ц' => 'ts',
				'Щ' => 'Shch',
				'щ' => 'shch',
				'Ю' => 'Iu',
				'ю' => 'iu',
				'Я' => 'Ia',
				'я' => 'ia',
			
			) );
			//bulgarian
		} elseif ( $loc == 'bg' || $loc == 'bg_BG' ) {
			$ret = array_merge( $ret, array(
				'Щ' => 'Sht',
				'щ' => 'sht',
				'Ъ' => 'a',
				'ъ' => 'a'
			) );
		}
		
		// Georgian
		if ( $loc == 'ka_GE' ) {
			$ret = array_merge( $ret, array(
				'ა' => 'a',
				'ბ' => 'b',
				'გ' => 'g',
				'დ' => 'd',
				'ე' => 'e',
				'ვ' => 'v',
				'ზ' => 'z',
				'თ' => 'th',
				'ი' => 'i',
				'კ' => 'k',
				'ლ' => 'l',
				'მ' => 'm',
				'ნ' => 'n',
				'ო' => 'o',
				'პ' => 'p',
				'ჟ' => 'zh',
				'რ' => 'r',
				'ს' => 's',
				'ტ' => 't',
				'უ' => 'u',
				'ფ' => 'ph',
				'ქ' => 'q',
				'ღ' => 'gh',
				'ყ' => 'qh',
				'შ' => 'sh',
				'ჩ' => 'ch',
				'ც' => 'ts',
				'ძ' => 'dz',
				'წ' => 'ts',
				'ჭ' => 'tch',
				'ხ' => 'kh',
				'ჯ' => 'j',
				'ჰ' => 'h'
			) );
		}
		
		// Greek
		if ( $loc == 'el' ) {
			$ret = array_merge( $ret, array(
				'α' => 'a',
				'β' => 'v',
				'γ' => 'g',
				'δ' => 'd',
				'ε' => 'e',
				'ζ' => 'z',
				'η' => 'h',
				'θ' => 'th',
				'ι' => 'i',
				'κ' => 'k',
				'λ' => 'l',
				'μ' => 'm',
				'ν' => 'n',
				'ξ' => 'x',
				'ο' => 'o',
				'π' => 'p',
				'ρ' => 'r',
				'σ' => 's',
				'ς' => 's',
				'τ' => 't',
				'υ' => 'u',
				'φ' => 'f',
				'χ' => 'ch',
				'ψ' => 'ps',
				'ω' => 'o',
				'Α' => 'A',
				'Β' => 'V',
				'Γ' => 'G',
				'Δ' => 'D',
				'Ε' => 'E',
				'Ζ' => 'Z',
				'Η' => 'H',
				'Θ' => 'TH',
				'Ι' => 'I',
				'Κ' => 'K',
				'Λ' => 'L',
				'Μ' => 'M',
				'Ν' => 'N',
				'Ξ' => 'X',
				'Ο' => 'O',
				'Π' => 'P',
				'Ρ' => 'R',
				'Σ' => 'S',
				'Τ' => 'T',
				'Υ' => 'U',
				'Φ' => 'F',
				'Χ' => 'CH',
				'Ψ' => 'PS',
				'Ω' => 'O',
				'ά' => 'a',
				'έ' => 'e',
				'ή' => 'h',
				'ί' => 'i',
				'ό' => 'o',
				'ύ' => 'u',
				'ώ' => 'o',
				'Ά' => 'A',
				'Έ' => 'E',
				'Ή' => 'H',
				'Ί' => 'I',
				'Ό' => 'O',
				'Ύ' => 'U',
				'Ώ' => 'O',
				'ϊ' => 'i',
				'ΐ' => 'i',
				'ΰ' => 'u',
				'ϋ' => 'u',
				'Ϊ' => 'I',
				'Ϋ' => 'U'
			) );
		}
		
		// Armenian
		if ( $loc == 'hy' ) {
			$ret = array_merge( $ret, array(
				'Ա'  => 'A',
				'ա'  => 'a',
				'Բ'  => 'B',
				'բ'  => 'b',
				'Գ'  => 'G',
				'գ'  => 'g',
				'Դ'  => 'D',
				'դ'  => 'd',
				' Ե' => ' Ye',
				'Ե'  => 'E',
				' ե' => ' ye',
				'ե'  => 'e',
				'Զ'  => 'Z',
				'զ'  => 'z',
				'Է'  => 'E',
				'է'  => 'e',
				'Ը'  => 'Y',
				'ը'  => 'y',
				'Թ'  => 'T',
				'թ'  => 't',
				'Ժ'  => 'Zh',
				'ժ'  => 'zh',
				'Ի'  => 'I',
				'ի'  => 'i',
				'Լ'  => 'L',
				'լ'  => 'l',
				'Խ'  => 'KH',
				'խ'  => 'kh',
				'Ծ'  => 'TS',
				'ծ'  => 'ts',
				'Կ'  => 'K',
				'կ'  => 'K',
				'Հ'  => 'H',
				'հ'  => 'h',
				'Ձ'  => 'DZ',
				'ձ'  => 'dz',
				'Ղ'  => 'GH',
				'ղ'  => 'gh',
				'Ճ'  => 'J',
				'Ճ'  => 'j',
				'Մ'  => 'M',
				'մ'  => 'm',
				'Յ'  => 'Y',
				'յ'  => 'y',
				'Ն'  => 'N',
				'ն'  => 'n',
				'Շ'  => 'SH',
				'շ'  => 'sh',
				' Ո' => 'VO',
				'Ո'  => 'VO',
				' ո' => ' vo',
				'ո'  => 'o',
				'Չ'  => 'Ch',
				'չ'  => 'ch',
				'Պ'  => 'P',
				'պ'  => 'p',
				'Ջ'  => 'J',
				'ջ'  => 'j',
				'Ռ'  => 'R',
				'ռ'  => 'r',
				'Ս'  => 'S',
				'ս'  => 's',
				'Վ'  => 'V',
				'վ'  => 'v',
				'Տ'  => 'T',
				'տ'  => 't',
				'Ր'  => 'R',
				'ր'  => 'r',
				'Ց'  => 'C',
				'ց'  => 'c',
				'Ու' => 'U',
				'ու' => 'u',
				'Փ'  => 'P',
				'փ'  => 'p',
				'Ք'  => 'Q',
				'ք'  => 'q',
				'Եվ' => 'EV',
				'և'  => 'ev',
				'Օ'  => 'O',
				'օ'  => 'o',
				'Ֆ'  => 'F',
				'ֆ'  => 'f'
			) );
		}
		
		// Serbian
		if ( $loc == 'sr_RS' ) {
			$ret = array_merge( $ret, array(
				"Ђ"  => "DJ",
				"Ж"  => "Z",
				"З"  => "Z",
				"Љ"  => "LJ",
				"Њ"  => "NJ",
				"Ш"  => "S",
				"Ћ"  => "C",
				"Ц"  => "C",
				"Ч"  => "C",
				"Џ"  => "DZ",
				"ђ"  => "dj",
				"ж"  => "z",
				"з"  => "z",
				"и"  => "i",
				"љ"  => "lj",
				"њ"  => "nj",
				"ш"  => "s",
				"ћ"  => "c",
				"ч"  => "c",
				"џ"  => "dz",
				"Ња" => "Nja",
				"Ње" => "Nje",
				"Њи" => "Nji",
				"Њо" => "Njo",
				"Њу" => "Nju",
				"Ља" => "Lja",
				"Ље" => "Lje",
				"Љи" => "Lji",
				"Љо" => "Ljo",
				"Љу" => "Lju",
				"Џа" => "Dza",
				"Џе" => "Dze",
				"Џи" => "Dzi",
				"Џо" => "Dzo",
				"Џу" => "Dzu"
			) );
		}
		
		return apply_filters( 'wbcr_ctl_default_symbols_pack', $ret );
	}
}