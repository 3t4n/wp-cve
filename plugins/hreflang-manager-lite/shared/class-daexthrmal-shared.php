<?php
/**
 * This class should be used to stores properties and methods shared by the
 * admin and public side of WordPress.
 *
 * @package hreflang-manager-lite
 */

/**
 * This class should be used to stores properties and methods shared by the
 * admin and public side of WordPress.
 */
class Daexthrmal_Shared {

	/**
	 * The singleton instance of this class.
	 *
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * Array with general plugin data.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Constructor.
	 */
	private function __construct() {

		load_plugin_textdomain( 'hreflang-manager-lite', false, 'hreflang-manager-lite/lang/' );

		$this->data['slug']        = 'daexthrmal';
		$this->data['ver']         = '1.07';
		$this->data['dir']         = substr( plugin_dir_path( __FILE__ ), 0, -7 );
		$this->data['url']         = substr( plugin_dir_url( __FILE__ ), 0, -7 );

		// Here are stored the plugin option with the related default values.
		$this->data['options'] = array(

			// Database Version ---------------------------------------------------------------------------------------.
			$this->get( 'slug' ) . '_database_version'     => '0',

			// General ------------------------------------------------------------------------------------------------.
			$this->get( 'slug' ) . '_show_log'             => '0',
			$this->get( 'slug' ) . '_https'                => '1',
			$this->get( 'slug' ) . '_detect_url_mode'      => '1',
			$this->get( 'slug' ) . '_auto_trailing_slash'  => '1',
			$this->get( 'slug' ) . '_auto_delete'          => '1',
			$this->get( 'slug' ) . '_auto_alternate_pages' => '0',
		);

		// Defaults ---------------------------------------------------------------------------------------------------.
		for ( $i = 1; $i <= 10; $i++ ) {
			$this->data['options'][ $this->get( 'slug' ) . '_default_language_' . $i ] = 'en';
			$this->data['options'][ $this->get( 'slug' ) . '_default_script_' . $i ]   = '';
			$this->data['options'][ $this->get( 'slug' ) . '_default_locale_' . $i ]   = '';
		}

		// language list (ISO_639-1).
		$daexthrmal_language = array(
			"don't target a specific language or locale" => 'x-default',
			'Abkhaz'                                     => 'ab',
			'Afar'                                       => 'aa',
			'Afrikaans'                                  => 'af',
			'Akan'                                       => 'ak',
			'Albanian'                                   => 'sq',
			'Amharic'                                    => 'am',
			'Arabic'                                     => 'ar',
			'Aragonese'                                  => 'an',
			'Armenian'                                   => 'hy',
			'Assamese'                                   => 'as',
			'Avaric'                                     => 'av',
			'Avestan'                                    => 'ae',
			'Aymara'                                     => 'ay',
			'Azerbaijani'                                => 'az',
			'Bambara'                                    => 'bm',
			'Bashkir'                                    => 'ba',
			'Basque'                                     => 'eu',
			'Belarusian'                                 => 'be',
			'Bengali/Bangla'                             => 'bn',
			'Bihari'                                     => 'bh',
			'Bislama'                                    => 'bi',
			'Bosnian'                                    => 'bs',
			'Breton'                                     => 'br',
			'Bulgarian'                                  => 'bg',
			'Burmese'                                    => 'my',
			'Catalan/Valencian'                          => 'ca',
			'Chamorro'                                   => 'ch',
			'Chechen'                                    => 'ce',
			'Chichewa/Chewa/Nyanja'                      => 'ny',
			'Chinese'                                    => 'zh',
			'Chuvash'                                    => 'cv',
			'Cornish'                                    => 'kw',
			'Corsican'                                   => 'co',
			'Cree'                                       => 'cr',
			'Croatian'                                   => 'hr',
			'Czech'                                      => 'cs',
			'Danish'                                     => 'da',
			'Divehi/Dhivehi/Maldivian'                   => 'dv',
			'Dutch'                                      => 'nl',
			'Dzongkha'                                   => 'dz',
			'English'                                    => 'en',
			'Esperanto'                                  => 'eo',
			'Estonian'                                   => 'et',
			'Ewe'                                        => 'ee',
			'Faroese'                                    => 'fo',
			'Fijian'                                     => 'fj',
			'Finnish'                                    => 'fi',
			'French'                                     => 'fr',
			'Fula/Fulah/Pulaar/Pular'                    => 'ff',
			'Galician'                                   => 'gl',
			'Georgian'                                   => 'ka',
			'German'                                     => 'de',
			'Greek/Modern'                               => 'el',
			'Guaraní'                                    => 'gn',
			'Gujarati'                                   => 'gu',
			'Haitian/Haitian Creole'                     => 'ht',
			'Hausa'                                      => 'ha',
			'Hebrew (modern)'                            => 'he',
			'Herero'                                     => 'hz',
			'Hindi'                                      => 'hi',
			'Hiri Motu'                                  => 'ho',
			'Hungarian'                                  => 'hu',
			'Interlingua'                                => 'ia',
			'Indonesian'                                 => 'id',
			'Interlingue'                                => 'ie',
			'Irish'                                      => 'ga',
			'Igbo'                                       => 'ig',
			'Inupian'                                    => 'ik',
			'Ido'                                        => 'io',
			'Icelandic'                                  => 'is',
			'Italian'                                    => 'it',
			'Inuktitut'                                  => 'iu',
			'Japanese'                                   => 'ja',
			'Javanese'                                   => 'jv',
			'Kalaallisut/Greenlandic'                    => 'kl',
			'Kannada'                                    => 'kn',
			'Kanuri'                                     => 'kr',
			'Kashmiri'                                   => 'ks',
			'Kazakh'                                     => 'kk',
			'Khmer'                                      => 'km',
			'Kikuyu/Gikuyu'                              => 'ki',
			'Kinyarwanda'                                => 'rw',
			'Kyrgyz'                                     => 'ky',
			'Komi'                                       => 'kv',
			'Kongo'                                      => 'kg',
			'Korean'                                     => 'ko',
			'Kurdish'                                    => 'ku',
			'Kwanyama/Kuanyama'                          => 'kj',
			'Latin'                                      => 'la',
			'Luxembourgish/Letzeburgesch'                => 'lb',
			'Ganda'                                      => 'lg',
			'Limburgish/Limburgan/Limburger'             => 'li',
			'Lingala'                                    => 'ln',
			'Lao'                                        => 'lo',
			'Lithuanian'                                 => 'lt',
			'Luba-Katanga'                               => 'lu',
			'Latvian'                                    => 'lv',
			'Manx'                                       => 'gv',
			'Macedonian'                                 => 'mk',
			'Malagasy'                                   => 'mg',
			'Malay'                                      => 'ms',
			'Malayalam'                                  => 'ml',
			'Maltese'                                    => 'mt',
			'Māori'                                      => 'mi',
			'Marathi/Marāṭhī'                            => 'mr',
			'Marshallese'                                => 'mh',
			'Mongolian'                                  => 'mn',
			'Nauru'                                      => 'na',
			'Navajo/Navaho'                              => 'nv',
			'Norwegian Bokmål'                           => 'nb',
			'North Ndebele'                              => 'nd',
			'Nepali'                                     => 'ne',
			'Ndonga'                                     => 'ng',
			'Norwegian Nynorsk'                          => 'nn',
			'Norwegian'                                  => 'no',
			'Nuosu'                                      => 'ii',
			'South Ndebele'                              => 'nr',
			'Occitan'                                    => 'oc',
			'Ojibwe/Ojibwa'                              => 'oj',
			'Old C. Slavonic/C. Slavic/C. Slavonic/Old Bulgarian/Old Slavonic' => 'cu',
			'Oromo'                                      => 'om',
			'Orija'                                      => 'or',
			'Ossetian/Ossetic'                           => 'os',
			'Panjabi/Punjabi'                            => 'pa',
			'Pāli'                                       => 'pi',
			'Persian (Farsi)'                            => 'fa',
			'Polish'                                     => 'pl',
			'Pashto/Pushto'                              => 'ps',
			'Portuguese'                                 => 'pt',
			'Quechua'                                    => 'qu',
			'Romansh'                                    => 'rm',
			'Kirundi'                                    => 'rn',
			'Romanian'                                   => 'ro',
			'Russian'                                    => 'ru',
			'Sanskrit (Saṁskṛta)'                        => 'sa',
			'Sardinian'                                  => 'sc',
			'Sindhi'                                     => 'sd',
			'Northern Sami'                              => 'se',
			'Samoan'                                     => 'sm',
			'Sango'                                      => 'sg',
			'Serbian'                                    => 'sr',
			'Scottish Gaelic/Gaelic'                     => 'gd',
			'Shona'                                      => 'sn',
			'Sinhala/Sinhalese'                          => 'si',
			'Slovak'                                     => 'sk',
			'Slovene'                                    => 'sl',
			'Somali'                                     => 'so',
			'Southern Sotho'                             => 'st',
			'South Azebaijani'                           => 'az',
			'Spanish/Castilian'                          => 'es',
			'Sundanese'                                  => 'su',
			'Swahili'                                    => 'sw',
			'Swati'                                      => 'ss',
			'Swedish'                                    => 'sv',
			'Tamil'                                      => 'ta',
			'Telugu'                                     => 'te',
			'Tajik'                                      => 'tg',
			'Thai'                                       => 'th',
			'Tigrinya'                                   => 'ti',
			'Tibetan Standard/Tibetan/Central'           => 'bo',
			'Turkmen'                                    => 'tk',
			'Tagalog'                                    => 'tl',
			'Tswana'                                     => 'tn',
			'Tonga (Tonga Islands)'                      => 'to',
			'Turkish'                                    => 'tr',
			'Tsonga'                                     => 'ts',
			'Tatar'                                      => 'tt',
			'Twi'                                        => 'tw',
			'Tahitian'                                   => 'ty',
			'Uyghur/Uighur'                              => 'ug',
			'Ukrainian'                                  => 'uk',
			'Urdu'                                       => 'ur',
			'Uzbek'                                      => 'uz',
			'Venda'                                      => 've',
			'Vietnamese'                                 => 'vi',
			'Volapük'                                    => 'vo',
			'Walloon'                                    => 'wa',
			'Welsh'                                      => 'cy',
			'Wolof'                                      => 'wo',
			'Western Frisian'                            => 'fy',
			'Xhosa'                                      => 'xh',
			'Yiddish'                                    => 'yi',
			'Yoruba'                                     => 'yo',
			'Zhuang/Chuang'                              => 'za',
			'Zulu'                                       => 'zu',
		);
		$this->data['options'][ $this->get( 'slug' ) . '_language' ] = $daexthrmal_language;

		// script list (ISO 15924).
		$daexthrmal_script = array(
			'Adlam'                                        => 'Adlm',
			'Afaka'                                        => 'Afak',
			'Caucasian Albanian'                           => 'Aghb',
			'Ahom, Tai Ahom'                               => 'Ahom',
			'Arabic'                                       => 'Arab',
			'Arabic (Nastaliq variant)'                    => 'Aran',
			'Imperial Aramaic'                             => 'Armi',
			'Armenian'                                     => 'Armn',
			'Avestan'                                      => 'Avst',
			'Balinese'                                     => 'Bali',
			'Bamum'                                        => 'Bamu',
			'Bassa Vah'                                    => 'Bass',
			'Batak'                                        => 'Batk',
			'Bengali (Bangla)'                             => 'Beng',
			'Bhaiksuki'                                    => 'Bhks',
			'Blissymbols'                                  => 'Blis',
			'Bopomofo'                                     => 'Bopo',
			'Brahmi'                                       => 'Brah',
			'Braille'                                      => 'Brai',
			'Buginese'                                     => 'Bugi',
			'Buhid'                                        => 'Buhd',
			'Chakma'                                       => 'Cakm',
			'Unified Canadian Aboriginal Syllabics'        => 'Cans',
			'Carian'                                       => 'Cari',
			'Cham'                                         => 'Cham',
			'Cherokee'                                     => 'Cher',
			'Chorasmian'                                   => 'Chrs',
			'Cirth'                                        => 'Cirt',
			'Coptic'                                       => 'Copt',
			'Cypro-Minoan'                                 => 'Cpmn',
			'Cypriot syllabary'                            => 'Cprt',
			'Cyrillic'                                     => 'Cyrl',
			'Cyrillic (Old Church Slavonic variant)'       => 'Cyrs',
			'Devanagari (Nagari)'                          => 'Deva',
			'Dives Akuru'                                  => 'Diak',
			'Dogra'                                        => 'Dogr',
			'Deseret (Mormon)'                             => 'Dsrt',
			'Duployan shorthand, Duployan stenography'     => 'Dupl',
			'Egyptian demotic'                             => 'Egyd',
			'Egyptian hieratic'                            => 'Egyh',
			'Egyptian hieroglyphs'                         => 'Egyp',
			'Elbasan'                                      => 'Elba',
			'Elymaic'                                      => 'Elym',
			'Ethiopic (Geʻez)'                             => 'Ethi',
			'Khutsuri (Asomtavruli and Nuskhuri)'          => 'Geok',
			'Georgian (Mkhedruli and Mtavruli)'            => 'Geor',
			'Glagolitic'                                   => 'Glag',
			'Gunjala Gondi'                                => 'Gong',
			'Masaram Gondi'                                => 'Gonm',
			'Gothic'                                       => 'Goth',
			'Grantha'                                      => 'Gran',
			'Greek'                                        => 'Grek',
			'Gujarati'                                     => 'Gujr',
			'Gurmukhi'                                     => 'Guru',
			'Han with Bopomofo (alias for Han + Bopomofo)' => 'Hanb',
			'Hangul (Hangŭl, Hangeul)'                     => 'Hang',
			'Han (Hanzi, Kanji, Hanja)'                    => 'Hani',
			'Hanunoo (Hanunóo)'                            => 'Hano',
			'Han (Simplified variant)'                     => 'Hans',
			'Han (Traditional variant)'                    => 'Hant',
			'Hatran'                                       => 'Hatr',
			'Hebrew'                                       => 'Hebr',
			'Hiragana'                                     => 'Hira',
			'Anatolian Hieroglyphs (Luwian Hieroglyphs, Hittite Hieroglyphs)' => 'Hluw',
			'Pahawh Hmong'                                 => 'Hmng',
			'Nyiakeng Puachue Hmong'                       => 'Hmnp',
			'Japanese syllabaries (alias for Hiragana + Katakana)' => 'Hrkt',
			'Old Hungarian (Hungarian Runic)'              => 'Hung',
			'Indus (Harappan)'                             => 'Inds',
			'Old Italic (Etruscan, Oscan, etc.)'           => 'Ital',
			'Jamo (alias for Jamo subset of Hangul)'       => 'Jamo',
			'Javanese'                                     => 'Java',
			'Japanese (alias for Han + Hiragana + Katakana)' => 'Jpan',
			'Jurchen'                                      => 'Jurc',
			'Kayah Li'                                     => 'Kali',
			'Katakana'                                     => 'Kana',
			'Kharoshthi'                                   => 'Khar',
			'Khmer'                                        => 'Khmr',
			'Khojki'                                       => 'Khoj',
			'Khitan large script'                          => 'Kitl',
			'Khitan small script'                          => 'Kits',
			'Kannada'                                      => 'Knda',
			'Korean (alias for Hangul + Han)'              => 'Kore',
			'Kpelle'                                       => 'Kpel',
			'Kaithi'                                       => 'Kthi',
			'Tai Tham (Lanna)'                             => 'Lana',
			'Lao'                                          => 'Laoo',
			'Latin (Fraktur variant)'                      => 'Latf',
			'Latin (Gaelic variant)'                       => 'Latg',
			'Latin'                                        => 'Latn',
			'Leke'                                         => 'Leke',
			'Lepcha (Róng)'                                => 'Lepc',
			'Limbu'                                        => 'Limb',
			'Linear A'                                     => 'Lina',
			'Linear B'                                     => 'Linb',
			'Lisu (Fraser)'                                => 'Lisu',
			'Loma'                                         => 'Loma',
			'Lycian'                                       => 'Lyci',
			'Lydian'                                       => 'Lydi',
			'Mahajani'                                     => 'Mahj',
			'Makasar'                                      => 'Maka',
			'Mandaic, Mandaean'                            => 'Mand',
			'Manichaean'                                   => 'Mani',
			'Marchen'                                      => 'Marc',
			'Mayan hieroglyphs'                            => 'Maya',
			'Medefaidrin (Oberi Okaime, Oberi Ɔkaimɛ)'     => 'Medf',
			'Mende Kikakui'                                => 'Mend',
			'Meroitic Cursive'                             => 'Merc',
			'Meroitic Hieroglyphs'                         => 'Mero',
			'Malayalam'                                    => 'Mlym',
			'Modi, Moḍī'                                   => 'Modi',
			'Mongolian'                                    => 'Mong',
			'Moon (Moon code, Moon script, Moon type)'     => 'Moon',
			'Mro, Mru'                                     => 'Mroo',
			'Meitei Mayek (Meithei, Meetei)'               => 'Mtei',
			'Multani'                                      => 'Mult',
			'Myanmar (Burmese)'                            => 'Mymr',
			'Nandinagari'                                  => 'Nand',
			'Old North Arabian (Ancient North Arabian)'    => 'Narb',
			'Nabataean'                                    => 'Nbat',
			'Newa, Newar, Newari, Nepāla lipi'             => 'Newa',
			'Naxi Dongba (na²¹ɕi³³ to³³ba²¹, Nakhi Tomba)' => 'Nkdb',
			"Naxi Geba (na²¹ɕi³³ gʌ²¹ba²¹, 'Na-'Khi ²Ggŏ-¹baw, Nakhi Geba)" => 'Nkgb',
			'N’Ko'                                         => 'Nkoo',
			'Nüshu'                                        => 'Nshu',
			'Ogham'                                        => 'Ogam',
			'Ol Chiki (Ol Cemet’, Ol, Santali)'            => 'Olck',
			'Old Turkic, Orkhon Runic'                     => 'Orkh',
			'Oriya (Odia)'                                 => 'Orya',
			'Osage'                                        => 'Osge',
			'Osmanya'                                      => 'Osma',
			'Old Uyghur'                                   => 'Ougr',
			'Palmyrene'                                    => 'Palm',
			'Pau Cin Hau'                                  => 'Pauc',
			'Proto-Cuneiform'                              => 'Pcun',
			'Proto-Elamite'                                => 'Pelm',
			'Old Permic'                                   => 'Perm',
			'Phags-pa'                                     => 'Phag',
			'Inscriptional Pahlavi'                        => 'Phli',
			'Psalter Pahlavi'                              => 'Phlp',
			'Book Pahlavi'                                 => 'Phlv',
			'Phoenician'                                   => 'Phnx',
			'Miao (Pollard)'                               => 'Plrd',
			'Klingon (KLI pIqaD)'                          => 'Piqd',
			'Inscriptional Parthian'                       => 'Prti',
			'Proto-Sinaitic'                               => 'Psin',
			'Reserved for private use (start)'             => 'Qaaa',
			'Reserved for private use (end)'               => 'Qabx',
			'Ranjana'                                      => 'Ranj',
			'Rejang (Redjang, Kaganga)'                    => 'Rjng',
			'Hanifi Rohingya'                              => 'Rohg',
			'Rongorongo'                                   => 'Roro',
			'Runic'                                        => 'Runr',
			'Samaritan'                                    => 'Samr',
			'Sarati'                                       => 'Sara',
			'Old South Arabian'                            => 'Sarb',
			'Saurashtra'                                   => 'Saur',
			'SignWriting'                                  => 'Sgnw',
			'Shavian (Shaw)'                               => 'Shaw',
			'Sharada, Śāradā'                              => 'Shrd',
			'Shuishu'                                      => 'Shui',
			'Siddham, Siddhaṃ, Siddhamātṛkā'               => 'Sidd',
			'Khudawadi, Sindhi'                            => 'Sind',
			'Sinhala'                                      => 'Sinh',
			'Sogdian'                                      => 'Sogd',
			'Old Sogdian'                                  => 'Sogo',
			'Sora Sompeng'                                 => 'Sora',
			'Soyombo'                                      => 'Soyo',
			'Sundanese'                                    => 'Sund',
			'Syloti Nagri'                                 => 'Sylo',
			'Syriac'                                       => 'Syrc',
			'Syriac (Estrangelo variant)'                  => 'Syre',
			'Syriac (Western variant)'                     => 'Syrj',
			'Syriac (Eastern variant)'                     => 'Syrn',
			'Tagbanwa'                                     => 'Tagb',
			'Takri, Ṭākrī, Ṭāṅkrī'                         => 'Takr',
			'Tai Le'                                       => 'Tale',
			'New Tai Lue'                                  => 'Talu',
			'Tamil'                                        => 'Taml',
			'Tangut'                                       => 'Tang',
			'Tai Viet'                                     => 'Tavt',
			'Telugu'                                       => 'Telu',
			'Tengwar'                                      => 'Teng',
			'Tifinagh (Berber)'                            => 'Tfng',
			'Tagalog (Baybayin, Alibata)'                  => 'Tglg',
			'Thaana'                                       => 'Thaa',
			'Thai'                                         => 'Thai',
			'Tibetan'                                      => 'Tibt',
			'Tirhuta'                                      => 'Tirh',
			'Tangsa'                                       => 'Tnsa',
			'Toto'                                         => 'Toto',
			'Ugaritic'                                     => 'Ugar',
			'Vai'                                          => 'Vaii',
			'Visible Speech'                               => 'Visp',
			'Vithkuqi'                                     => 'Vith',
			'Warang Citi (Varang Kshiti)'                  => 'Wara',
			'Wancho'                                       => 'Wcho',
			'Woleai'                                       => 'Wole',
			'Old Persian'                                  => 'Xpeo',
			'Cuneiform, Sumero-Akkadian'                   => 'Xsux',
			'Yezidi'                                       => 'Yezi',
			'Yi'                                           => 'Yiii',
			'Zanabazar Square (Zanabazarin Dörböljin Useg, Xewtee Dörböljin Bicig, Horizontal Square Script)' => 'Zanb',
			'Code for inherited script'                    => 'Zinh',
			'Mathematical notation'                        => 'Zmth',
			'Symbols (Emoji variant)'                      => 'Zsye',
			'Symbols'                                      => 'Zsym',
			'Code for unwritten documents'                 => 'Zxxx',
			'Code for undetermined script'                 => 'Zyyy',
			'Code for uncoded script'                      => 'Zzzz',
		);
		$this->data['options'][ $this->get( 'slug' ) . '_script' ] = $daexthrmal_script;

		// country list (ISO 3166-1 alpha-2).
		$daexthrmal_locale = array(
			'Andorra'                                      => 'ad',
			'United Arab Emirates'                         => 'ae',
			'Afghanistan'                                  => 'af',
			'Antigua and Barbuda'                          => 'ag',
			'Anguilla'                                     => 'ai',
			'Albania'                                      => 'al',
			'Armenia'                                      => 'am',
			'Angola'                                       => 'ao',
			'Antartica'                                    => 'aq',
			'Argentina'                                    => 'ar',
			'American Samoa'                               => 'as',
			'Austria'                                      => 'at',
			'Australia'                                    => 'au',
			'Aruba'                                        => 'aw',
			'Åland Islands'                                => 'ax',
			'Azerbaijan'                                   => 'az',
			'Bosnia and Herzegovina'                       => 'ba',
			'Barbados'                                     => 'bb',
			'Bangladesh'                                   => 'bd',
			'Belgium'                                      => 'be',
			'Burkina Faso'                                 => 'bf',
			'Bulgaria'                                     => 'bg',
			'Bahrain'                                      => 'bh',
			'Burundi'                                      => 'bi',
			'Benin'                                        => 'bj',
			'Saint Barthélemy'                             => 'bl',
			'Bermuda'                                      => 'bm',
			'Brunei Darussalam'                            => 'bn',
			'Bolivia'                                      => 'bo',
			'Bonaire, Sint Eustatius and Saba'             => 'bq',
			'Brazil'                                       => 'br',
			'Bahamas'                                      => 'bs',
			'Bhutan'                                       => 'bt',
			'Bouvet Island'                                => 'bv',
			'Botswana'                                     => 'bw',
			'Belarus'                                      => 'by',
			'Belize'                                       => 'bz',
			'Canada'                                       => 'ca',
			'Cocos (Keeling) Islands'                      => 'cc',
			'Congo Democratic Republic'                    => 'cd',
			'Central African Republic'                     => 'cf',
			'Congo'                                        => 'cg',
			'Switzerland'                                  => 'ch',
			'Côte d\'Ivoire'                               => 'ci',
			'Cook Islands'                                 => 'ck',
			'Chile'                                        => 'cl',
			'Cameroon'                                     => 'cm',
			'China'                                        => 'cn',
			'Colombia'                                     => 'co',
			'Costa Rica'                                   => 'cr',
			'Cuba'                                         => 'cu',
			'Cape Verde'                                   => 'cv',
			'Curaçao'                                      => 'cw',
			'Christmas Island'                             => 'cx',
			'Cyprus'                                       => 'cy',
			'Czech Republic'                               => 'cz',
			'Germany'                                      => 'de',
			'Djibouti'                                     => 'dj',
			'Denmark'                                      => 'dk',
			'Dominica'                                     => 'dm',
			'Dominican Republic'                           => 'do',
			'Algeria'                                      => 'dz',
			'Ecuador'                                      => 'ec',
			'Estonia'                                      => 'ee',
			'Egypt'                                        => 'eg',
			'Western Sahara'                               => 'eh',
			'Eritrea'                                      => 'er',
			'Spain'                                        => 'es',
			'Ethiopia'                                     => 'et',
			'Finland'                                      => 'fi',
			'Fiji'                                         => 'fj',
			'Falkland Islands (Malvinas)'                  => 'fk',
			'Micronesia Federated States of'               => 'fm',
			'Faroe Islands'                                => 'fo',
			'France'                                       => 'fr',
			'Gabon'                                        => 'ga',
			'United Kingdom'                               => 'gb',
			'Grenada'                                      => 'gd',
			'Georgia'                                      => 'ge',
			'French Guiana'                                => 'gf',
			'Guernsey'                                     => 'gg',
			'Ghana'                                        => 'gh',
			'Gibraltar'                                    => 'gi',
			'Greenland'                                    => 'gl',
			'Gambia'                                       => 'gm',
			'Guinea'                                       => 'gn',
			'Guadeloupe'                                   => 'gp',
			'Equatorial Guinea'                            => 'gq',
			'Greece'                                       => 'gr',
			'South Georgia and the South Sandwich Islands' => 'gs',
			'Guatemala'                                    => 'gt',
			'Guam'                                         => 'gu',
			'Guinea-Bissau'                                => 'gw',
			'Guyana'                                       => 'gy',
			'Hong Kong'                                    => 'hk',
			'Heard Island and McDonald Islands'            => 'hm',
			'Honduras'                                     => 'hn',
			'Croatia'                                      => 'hr',
			'Haiti'                                        => 'ht',
			'Hungary'                                      => 'hu',
			'Indonesia'                                    => 'id',
			'Ireland'                                      => 'ie',
			'Israel'                                       => 'il',
			'Isle of Man'                                  => 'im',
			'India'                                        => 'in',
			'British Indian Ocean Territory'               => 'io',
			'Iraq'                                         => 'iq',
			'Iran, Islamic Republic of'                    => 'ir',
			'Iceland'                                      => 'is',
			'Italy'                                        => 'it',
			'Jersey'                                       => 'je',
			'Jamaica'                                      => 'jm',
			'Jordan'                                       => 'jo',
			'Japan'                                        => 'jp',
			'Kenya'                                        => 'ke',
			'Kyrgyzstan'                                   => 'kg',
			'Cambodia'                                     => 'kh',
			'Kiribati'                                     => 'ki',
			'Comoros'                                      => 'km',
			'Saint Kitts and Nevis'                        => 'kn',
			'Korea, Democratic People\'s Republic of'      => 'kp',
			'Korea, Republic of'                           => 'kr',
			'Kuwait'                                       => 'kw',
			'Cayman Islands'                               => 'ky',
			'Kazakhstan'                                   => 'kz',
			'Lao People\'s Democratic Republic'            => 'la',
			'Lebanon'                                      => 'lb',
			'Saint Lucia'                                  => 'lc',
			'Liechtenstein'                                => 'li',
			'Sri Lanka'                                    => 'lk',
			'Liberia'                                      => 'lr',
			'Lesotho'                                      => 'ls',
			'Lithuania'                                    => 'lt',
			'Luxembourg'                                   => 'lu',
			'Latvia'                                       => 'lv',
			'Libya'                                        => 'ly',
			'Morocco'                                      => 'ma',
			'Monaco'                                       => 'mc',
			'Moldova, Republic of'                         => 'md',
			'Montenegro'                                   => 'me',
			'Saint Martin (French part)'                   => 'mf',
			'Madagascar'                                   => 'mg',
			'Marshall Islands'                             => 'mh',
			'Macedonia, the former Yugoslav Republic of'   => 'mk',
			'Mali'                                         => 'ml',
			'Myanmar'                                      => 'mm',
			'Mongolia'                                     => 'mn',
			'Macao'                                        => 'mo',
			'Northern Mariana Islands'                     => 'mp',
			'Martinique'                                   => 'mq',
			'Mauritania'                                   => 'mr',
			'Montserrat'                                   => 'ms',
			'Malta'                                        => 'mt',
			'Mauritius'                                    => 'mu',
			'Maldives'                                     => 'mv',
			'Malawi'                                       => 'mw',
			'Mexico'                                       => 'mx',
			'Malaysia'                                     => 'my',
			'Mozambique'                                   => 'mz',
			'Namibia'                                      => 'na',
			'New Caledonia'                                => 'nc',
			'Niger'                                        => 'ne',
			'Norfolk Island'                               => 'nf',
			'Nigeria'                                      => 'ng',
			'Nicaragua'                                    => 'ni',
			'Netherlands'                                  => 'nl',
			'Norway'                                       => 'no',
			'Nepal'                                        => 'np',
			'Nauru'                                        => 'nr',
			'Niue'                                         => 'nu',
			'New Zealand'                                  => 'nz',
			'Oman'                                         => 'om',
			'Panama'                                       => 'pa',
			'Peru'                                         => 'pe',
			'French Polynesia'                             => 'pf',
			'Papua New Guinea'                             => 'pg',
			'Philippines'                                  => 'ph',
			'Pakistan'                                     => 'pk',
			'Poland'                                       => 'pl',
			'Saint Pierre and Miquelon'                    => 'pm',
			'Pitcairn'                                     => 'pn',
			'Puerto Rico'                                  => 'pr',
			'Palestine, State of'                          => 'ps',
			'Portugal'                                     => 'pt',
			'Palau'                                        => 'pw',
			'Paraguay'                                     => 'py',
			'Qatar'                                        => 'qa',
			'Réunion'                                      => 're',
			'Romania'                                      => 'ro',
			'Serbia'                                       => 'rs',
			'Russian Federation'                           => 'ru',
			'Rwanda'                                       => 'rw',
			'Saudi Arabia'                                 => 'sa',
			'Solomon Islands'                              => 'sb',
			'Seychelles'                                   => 'sc',
			'Sudan'                                        => 'sd',
			'Sweden'                                       => 'se',
			'Singapore'                                    => 'sg',
			'Saint Helena, Ascension and Tristan da Cunha' => 'sh',
			'Slovenia'                                     => 'si',
			'Svalbard and Jan Mayen'                       => 'sj',
			'Slovakia'                                     => 'sk',
			'Sierra Leone'                                 => 'sl',
			'San Marino'                                   => 'sm',
			'Senegal'                                      => 'sn',
			'Somalia'                                      => 'so',
			'Suriname'                                     => 'sr',
			'South Sudan'                                  => 'ss',
			'Sao Tome and Principe'                        => 'st',
			'El Salvador'                                  => 'sv',
			'Sint Maarten (Dutch part)'                    => 'sx',
			'Syrian Arab Republic'                         => 'sy',
			'Swaziland'                                    => 'sz',
			'Turks and Caicos Islands'                     => 'tc',
			'Chad'                                         => 'td',
			'French Southern Territories'                  => 'tf',
			'Togo'                                         => 'tg',
			'Thailand'                                     => 'th',
			'Tajikistan'                                   => 'tj',
			'Tokelau'                                      => 'tk',
			'Timor-Leste'                                  => 'tl',
			'Turkmenistan'                                 => 'tm',
			'Tunisia'                                      => 'tn',
			'Tonga'                                        => 'to',
			'Turkey'                                       => 'tr',
			'Trinidad and Tobago'                          => 'tt',
			'Tuvalu'                                       => 'tv',
			'Taiwan, Province of China'                    => 'tw',
			'Tanzania, United Republic of'                 => 'tz',
			'Ukraine'                                      => 'ua',
			'Uganda'                                       => 'ug',
			'United States Minor Outlying Islands'         => 'um',
			'United States'                                => 'us',
			'Uruguay'                                      => 'uy',
			'Uzbekistan'                                   => 'uz',
			'Holy See (Vatican City State)'                => 'va',
			'Saint Vincent and the Grenadines'             => 'vc',
			'Venezuela, Bolivarian Republic of'            => 've',
			'Virgin Islands, British'                      => 'vg',
			'Virgin Islands, U.S.'                         => 'vi',
			'Viet Nam'                                     => 'vn',
			'Vanuatu'                                      => 'vu',
			'Wallis and Futuna'                            => 'wf',
			'Samoa'                                        => 'ws',
			'Yemen'                                        => 'ye',
			'Mayotte'                                      => 'yt',
			'South Africa'                                 => 'za',
			'Zambia'                                       => 'zm',
			'Zimbabwe'                                     => 'zw',
		);
		$this->data['options'][ $this->get( 'slug' ) . '_locale' ] = $daexthrmal_locale;
	}

	/**
	 * Get the instance of the class.
	 *
	 * @return self|null
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Retrieve data.
	 *
	 * @param string $index The index of the data that should be retrieved.
	 *
	 * @return mixed
	 */
	public function get( $index ) {
		return $this->data[ $index ];
	}

	/**
	 * Generate an array with the connections associated with the current url.
	 *
	 * @return An array with the connections associated with the current url or False if there are not connections
	 * associated with the current url.
	 */
	public function generate_hreflang_output() {

		// get the current url.
		$current_url = $this->get_current_url();

		global $wpdb;
		$table_name = $wpdb->prefix . $this->get( 'slug' ) . '_connection';

		/**
		 * If the 'Auto Trailing Slash' option is enabled compare the 'url_to_connect' value in the database not only
		 * with $current_url, but also with the URL present in $current_url with the trailing slash manually added or
		 * removed.
		 */
		if ( 1 === intval( get_option( 'daexthrmal_auto_trailing_slash' ), 10 ) ) {

			if ( '/' === substr( $current_url, strlen( $current_url ) - 1 ) ) {

				/**
				 * In this case there is a trailing slash, so remove it and compare the 'url_to_connect' value in the
				 * database not only with $current_url, but also with $current_url_without_trailing_slash, which is
				 * $current_url with the trailing slash removed.
				 */
				$current_url_without_trailing_slash = substr( $current_url, 0, -1 );
				$safe_sql                           = $wpdb->prepare( "SELECT * FROM $table_name WHERE url_to_connect = %s or url_to_connect = %s", $current_url, $current_url_without_trailing_slash );

			} else {

				/**
				 * In this case there is no trailing slash, so add it and compare the 'url_to_connect' value in the
				 * database not only with $current_url, but also with $current_url_with_trailing_slash, which is
				 * $current_url with the trailing slash added.
				 */
				$current_url_with_trailing_slash = $current_url . '/';
				$safe_sql                        = $wpdb->prepare( "SELECT * FROM $table_name WHERE url_to_connect = %s or url_to_connect = %s", $current_url, $current_url_with_trailing_slash );

			}
		} else {
			$safe_sql = $wpdb->prepare( "SELECT * FROM $table_name WHERE url_to_connect = %s", $current_url );
		}

		$results = $wpdb->get_row( $safe_sql );

		if ( null === $results ) {

			return false;

		} else {

			// init $hreflang_output.
			$hreflang_output = array();

			// generate an array with all the connections.
			for ( $i = 1; $i <= 10; $i++ ) {

				// check if this is a valid hreflang.
				if ( strlen( $results->{'url' . $i} ) > 0 && strlen( $results->{'language' . $i} ) > 0 ) {

					$language = $results->{'language' . $i};

					if ( strlen( $results->{'script' . $i} ) > 0 ) {
						$script = '-' . $results->{'script' . $i};
					} else {
						$script = '';
					}

					if ( strlen( $results->{'locale' . $i} ) > 0 ) {
						$locale = '-' . $results->{'locale' . $i};
					} else {
						$locale = '';
					}

					// Add the link element to the output.
					$hreflang_output[ $i ] = '<link rel="alternate" href="' . esc_url( $results->{'url' . $i} ) . '" hreflang="' . esc_attr( $language . $script . $locale ) . '" />';

				}
			}

			if ( is_array( $hreflang_output ) ) {
				return $hreflang_output;
			} else {
				return false;
			}
		}
	}

	/**
	 * Get the current URL.
	 */
	public function get_current_url() {

		if ( 0 === intval( get_option( 'daexthrmal_detect_url_mode' ), 10 ) ) {

			// Detect the URL using the "Server Variable" method.
			if ( 0 === intval( get_option( 'daexthrmal_https' ), 10 ) ) {
				$protocol = 'http';
			} else {
				$protocol = 'https';
			}
			return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		} else {

			// Detect the URL using the "WP Request" method.
			global $wp;
			return trailingslashit( home_url( add_query_arg( array(), $wp->request ) ) );

		}
	}

	/**
	 * Returns the number of records available in the '[prefix]_daexthrmal_connect' db table.
	 *
	 * @return int The number of records available in the '[prefix]_daexthrmal_connect' db table.
	 */
	public function number_of_connections() {

		global $wpdb;
		$table_name  = $wpdb->prefix . $this->get( 'slug' ) . '_connection';
		$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );

		return $total_items;
	}
}
