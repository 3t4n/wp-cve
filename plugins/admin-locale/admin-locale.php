<?php
/*
Plugin Name: Admin Locale
Description: This plugin allows you to change the language of the admin panel without changing the whole site language. Just go to Settings &raquo; General and choose your language.
Author: Louy Alakkad
Version: 1.1
Author URI: http://l0uy.com/
*/
/**
 * The admin locale plugin simply changes the locale (or language) of the admin without changing the whole site's one.
 * 
 * to translate this plugin, you need to translate 1 string! it's "Admin Language", to add the translation,
 * add a new value to the $admin_locale_trans array below as 'lang code' => 'translated string', that's it ;)
 */

// Translations!
$admin_locale_trans = array( '' => 'Admin Language',
							 'en_US' => 'Admin Language',
							 'en_GB' => 'Admin Language',
							 'ar' => 'لغة لوحة التحكم' );

/**
 * set the default admin language to the current language when the plugin is activated.
 */
function admin_locale_activate() {
	add_option( 'admin_locale', get_option('WPLANG') );
}
register_activation_hook( __FILE__, 'admin_locale_activate' );

/**
 * filter the locale to send the configured one if this is an admin or login page.
 */
function admin_locale($locale) {
	global $original_locale;
	
	if( !isset($original_locale) || empty($original_locale) )
		$original_locale = $locale;
	
	if ( defined('WP_INSTALLING') )
		return $locale;
	
	if ( //Admin Panel
			is_admin()
		 || //TinyMCE config file
			strpos($_SERVER['REQUEST_URI'], '/wp-includes/js/tinymce/tiny_mce_config.php') !== false
		 || //Login page
			strpos($_SERVER['REQUEST_URI'], '/wp-login.php' ) !== false 
		 ) {
		if( defined('DOING_AJAX') && DOING_AJAX ) { // admin-ajax.php
			
			if ( function_exists('is_user_logged_in') && is_user_logged_in() ) {
				// Admin locale for logged-in users
				return get_option('admin_locale');
			}
			
		} else { // Not in admin-ajax.php
			return get_option('admin_locale');
		}
	}
	return $locale;
}
add_filter( 'locale', 'admin_locale' );

/**
 * Return the original locale in core_version_check_locale
 */
function admin_locale_core_version_check_locale( $locale ) {
	global $original_locale;
	return $original_locale;
}

/**
 * display admin locale field in general settings page
 */
function admin_locale_field() {
	?>

			<select name="admin_locale" id="admin_locale">
				<?php admin_locale_lang_dropdown( get_available_languages(), get_locale() ); ?>
			</select>
<?php
}

/**
 * tell wordpress to update the admin locale when changed.
 */
function admin_locale_whitelist($whitelist) {
	$whitelist['general'][] = 'admin_locale';
	return $whitelist;
}
add_filter( 'whitelist_options', 'admin_locale_whitelist' );

/**
 * check if selected language exists before saving, if not check the previous language, and
 * if that language does not exist neither, revert to the original blog language.
 */
function admin_locale_pre_update($new, $old) {
	$langs = get_available_languages();
	if( !in_array( '', $langs ) )
		$langs[] = '';
	if( !in_array( $new, $langs ) ) {
		if( in_array( $old, $langs ) ) {
			return $old;
		}
		return get_option('WPLANG');
	}
	return $new;
}
add_filter( 'pre_update_option_admin_locale', 'admin_locale_pre_update', 10, 2 );

/**
 * add the settings field to the general options page.
 */
function admin_locale_init() {
	global $admin_locale_trans;
	
	$string = $admin_locale_trans[in_array( get_locale(), array_keys( $admin_locale_trans ) ) ? get_locale() : ''].':';
	
	add_settings_field('admin_locale', $string, 'admin_locale_field', 'general', 'default');
}
add_action('admin_init', 'admin_locale_init');

/**
 * List available languages as dropdown menu options.
 *
 * @see WPMU: mu_dropdown_languages()
 */
function admin_locale_lang_dropdown( $langs, $current = '' ) {
	$flag = false;
	$output = array();

	foreach ( (array) $langs as $val ) {
		$code_lang = basename( $val, '.mo' );

		if ( $code_lang == 'en_US' ) { // American English
			$flag = true;
			$ae = __( 'American English');
			$output[$ae] = '<option value="' . esc_attr( $code_lang ) . '"' . selected( $current, $code_lang, false ) . '> ' . $ae . '</option>';
		} elseif ( $code_lang == 'en_GB' ) { // British English
			$flag = true;
			$be = __( 'British English');
			$output[$be] = '<option value="' . esc_attr( $code_lang ) . '"' . selected( $current, $code_lang, false ) . '> ' . $be . '</option>';
		} else {
			$translated = __(al_format_code_lang( $code_lang ));
			$output[$translated] =  '<option value="' . esc_attr( $code_lang ) . '"' . selected( $current, $code_lang, false ) . '> ' . esc_html ( $translated ) . '</option>';
		}

	}

	if ( $flag === false ) // WordPress english
		$output[] = '<option value=""' . selected( $current, '', false ) . '>' . __( 'English' ) . "</option>";

	// Order by name
	uksort( $output, 'strnatcasecmp' );

	echo implode( "\n\t", $output );
}

/**
 * retrive language name by code.
 * 
 * @see WPMS: format_code_lang()
 */
function al_format_code_lang( $code = '' ) {
	$code = strtolower( substr( $code, 0, 2 ) );
	$lang_codes = array(
		'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'ak' => 'Akan', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'an' => 'Aragonese', 'hy' => 'Armenian', 'as' => 'Assamese', 'av' => 'Avaric', 'ae' => 'Avestan', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'bm' => 'Bambara', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali',
		'bh' => 'Bihari', 'bi' => 'Bislama', 'bs' => 'Bosnian', 'br' => 'Breton', 'bg' => 'Bulgarian', 'my' => 'Burmese', 'ca' => 'Catalan; Valencian', 'ch' => 'Chamorro', 'ce' => 'Chechen', 'zh' => 'Chinese', 'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 'cv' => 'Chuvash', 'kw' => 'Cornish', 'co' => 'Corsican', 'cr' => 'Cree',
		'cs' => 'Czech', 'da' => 'Danish', 'dv' => 'Divehi; Dhivehi; Maldivian', 'nl' => 'Dutch; Flemish', 'dz' => 'Dzongkha', 'en' => 'English', 'eo' => 'Esperanto', 'et' => 'Estonian', 'ee' => 'Ewe', 'fo' => 'Faroese', 'fj' => 'Fijjian', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Western Frisian', 'ff' => 'Fulah', 'ka' => 'Georgian', 'de' => 'German', 'gd' => 'Gaelic; Scottish Gaelic',
		'ga' => 'Irish', 'gl' => 'Galician', 'gv' => 'Manx', 'el' => 'Greek, Modern', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ht' => 'Haitian; Haitian Creole', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hz' => 'Herero', 'hi' => 'Hindi', 'ho' => 'Hiri Motu', 'hu' => 'Hungarian', 'ig' => 'Igbo', 'is' => 'Icelandic', 'io' => 'Ido', 'ii' => 'Sichuan Yi', 'iu' => 'Inuktitut', 'ie' => 'Interlingue',
		'ia' => 'Interlingua (International Auxiliary Language Association)', 'id' => 'Indonesian', 'ik' => 'Inupiaq', 'it' => 'Italian', 'jv' => 'Javanese', 'ja' => 'Japanese', 'kl' => 'Kalaallisut; Greenlandic', 'kn' => 'Kannada', 'ks' => 'Kashmiri', 'kr' => 'Kanuri', 'kk' => 'Kazakh', 'km' => 'Central Khmer', 'ki' => 'Kikuyu; Gikuyu', 'rw' => 'Kinyarwanda', 'ky' => 'Kirghiz; Kyrgyz',
		'kv' => 'Komi', 'kg' => 'Kongo', 'ko' => 'Korean', 'kj' => 'Kuanyama; Kwanyama', 'ku' => 'Kurdish', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'li' => 'Limburgan; Limburger; Limburgish', 'ln' => 'Lingala', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish; Letzeburgesch', 'lu' => 'Luba-Katanga', 'lg' => 'Ganda', 'mk' => 'Macedonian', 'mh' => 'Marshallese', 'ml' => 'Malayalam',
		'mi' => 'Maori', 'mr' => 'Marathi', 'ms' => 'Malay', 'mg' => 'Malagasy', 'mt' => 'Maltese', 'mo' => 'Moldavian', 'mn' => 'Mongolian', 'na' => 'Nauru', 'nv' => 'Navajo; Navaho', 'nr' => 'Ndebele, South; South Ndebele', 'nd' => 'Ndebele, North; North Ndebele', 'ng' => 'Ndonga', 'ne' => 'Nepali', 'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian', 'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
		'no' => 'Norwegian', 'ny' => 'Chichewa; Chewa; Nyanja', 'oc' => 'Occitan, Provençal', 'oj' => 'Ojibwa', 'or' => 'Oriya', 'om' => 'Oromo', 'os' => 'Ossetian; Ossetic', 'pa' => 'Panjabi; Punjabi', 'fa' => 'Persian', 'pi' => 'Pali', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ps' => 'Pushto', 'qu' => 'Quechua', 'rm' => 'Romansh', 'ro' => 'Romanian', 'rn' => 'Rundi', 'ru' => 'Russian',
		'sg' => 'Sango', 'sa' => 'Sanskrit', 'sr' => 'Serbian', 'hr' => 'Croatian', 'si' => 'Sinhala; Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'se' => 'Northern Sami', 'sm' => 'Samoan', 'sn' => 'Shona', 'sd' => 'Sindhi', 'so' => 'Somali', 'st' => 'Sotho, Southern', 'es' => 'Spanish; Castilian', 'sc' => 'Sardinian', 'ss' => 'Swati', 'su' => 'Sundanese', 'sw' => 'Swahili',
		'sv' => 'Swedish', 'ty' => 'Tahitian', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'tg' => 'Tajik', 'tl' => 'Tagalog', 'th' => 'Thai', 'bo' => 'Tibetan', 'ti' => 'Tigrinya', 'to' => 'Tonga (Tonga Islands)', 'tn' => 'Tswana', 'ts' => 'Tsonga', 'tk' => 'Turkmen', 'tr' => 'Turkish', 'tw' => 'Twi', 'ug' => 'Uighur; Uyghur', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek',
		've' => 'Venda', 'vi' => 'Vietnamese', 'vo' => 'Volapük', 'cy' => 'Welsh','wa' => 'Walloon','wo' => 'Wolof', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'za' => 'Zhuang; Chuang', 'zu' => 'Zulu' );
	$lang_codes = apply_filters( 'lang_codes', $lang_codes, $code );
	return strtr( $code, $lang_codes );
}