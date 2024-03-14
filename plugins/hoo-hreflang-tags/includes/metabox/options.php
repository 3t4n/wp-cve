<?php

add_filter( 'cmb_meta_boxes', 'hoo_hreflang_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function hoo_hreflang_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_hoo_';
	$options     = get_option('hoo_hreflang_post_types',array('page','post'));
	
	/**
	 * Repeatable Field Groups
	 */
	$meta_boxes['alternative_tags'] = array(
		'id'         => 'alternative_tags',
		'title'      => __( 'Hreflang Tags', 'hoo-hreflang-tags' ),
		'pages'      => $options,
		'fields'     => array(
			array(
				'id'          => $prefix . 'alternative_tags',
				'type'        => 'group',
				'description' => '',
				'options'     => array(
					'group_title'   => __( 'Tag {#}', 'hoo-hreflang-tags' ), // {#} gets replaced by row number
					'add_button'    => __( 'Add Another Tag', 'hoo-hreflang-tags' ),
					'remove_button' => __( 'Remove Tag', 'hoo-hreflang-tags' ),
					'sortable'      => true, // beta
				),

				'fields'      => array(
					array(
						'name' => 'Alternative URL',
						'id'   => 'alternative_url',
						'type' => 'text_url',
					),
					array(
						'name' => 'Language',
						'description' => '',
						'id'   => 'language',
						'type' => 'select',
						'options' => array(
							'x-default' => __( 'X-Default', 'hoo-hreflang-tags' ),
							'af' => __( 'Afrikaans', 'hoo-hreflang-tags' ),
							'sq' => __( 'Albanian', 'hoo-hreflang-tags' ),
							'ar' => __( 'Arabic', 'hoo-hreflang-tags' ),
							'hy' => __( 'Armenian', 'hoo-hreflang-tags' ),
							'as' => __( 'Assamese', 'hoo-hreflang-tags' ),
							'az' => __( 'Azerbaijani', 'hoo-hreflang-tags' ),
							'eu' => __( 'Basque', 'hoo-hreflang-tags' ),
							'bel' => __( 'Belarusian', 'hoo-hreflang-tags' ),
							'bn_BD' => __( 'Bengali', 'hoo-hreflang-tags' ),
							'bs_BA' => __( 'Bosnian', 'hoo-hreflang-tags' ),
							'bg_BG' => __( 'Bulgarian', 'hoo-hreflang-tags' ),
							'ca' => __( 'Catalan', 'hoo-hreflang-tags' ),
							'ceb' => __( 'Cebuano', 'hoo-hreflang-tags' ),
							'zh_CN' => __( 'Chinese (China)', 'hoo-hreflang-tags' ),
							'zh_HK' => __( 'Chinese (Hong Kong)', 'hoo-hreflang-tags' ),
							'zh_Hans' => __( 'Chinese (Simplified)', 'hoo-hreflang-tags' ),
							'zh_TW' => __( 'Chinese (Taiwan)', 'hoo-hreflang-tags' ),
							'zh_Hant' => __( 'Chinese (Traditional)', 'hoo-hreflang-tags' ),
							'hr' => __( 'Croatian', 'hoo-hreflang-tags' ),
							'cs_CZ' => __( 'Czech', 'hoo-hreflang-tags' ),
							'cs' => __( 'Czech', 'hoo-hreflang-tags' ),
							'da_DK' => __( 'Danish', 'hoo-hreflang-tags' ),
							'nl_NL' => __( 'Dutch', 'hoo-hreflang-tags' ),
							'nl' => __( 'Dutch', 'hoo-hreflang-tags' ),
							'nl_BE' => __( 'Dutch (Belgium)', 'hoo-hreflang-tags' ),
							'nl_NL_formal' => __( 'Dutch (Formal)', 'hoo-hreflang-tags' ),
							'dzo' => __( 'Dzongkha', 'hoo-hreflang-tags' ),
							'en_AU' => __( 'English (Australia)', 'hoo-hreflang-tags' ),
							'en_CA' => __( 'English (Canada)', 'hoo-hreflang-tags' ),
							'en_CN' => __( 'English (China)', 'hoo-hreflang-tags' ),
							'en' => __( 'English (EN)', 'hoo-hreflang-tags' ),
							'en_HK' => __( 'English (Hong Kong)', 'hoo-hreflang-tags' ),
							'en_IN' => __( 'English (India)', 'hoo-hreflang-tags' ),
							'en_ID' => __( 'English (Indonesia)', 'hoo-hreflang-tags' ),
							'en_IE' => __( 'English (Ireland)', 'hoo-hreflang-tags' ),
							'en_MY' => __( 'English (Malaysia)', 'hoo-hreflang-tags' ),
							'en_MM' => __( 'English (Myanmar)', 'hoo-hreflang-tags' ),
							'en_NL' => __( 'English (Netherlands)', 'hoo-hreflang-tags' ),
							'en_NZ' => __( 'English (New Zealand)', 'hoo-hreflang-tags' ),
							'en_PH' => __( 'English (Philippines)', 'hoo-hreflang-tags' ),
							'en_RO' => __( 'English (Romania)', 'hoo-hreflang-tags' ),
							'en_SG' => __( 'English (Singapore)', 'hoo-hreflang-tags' ),
							'en_ZA' => __( 'English (South Africa)', 'hoo-hreflang-tags' ),
							'en_GB' => __( 'English (UK)', 'hoo-hreflang-tags' ),
							'en_US' => __( 'English (United States)', 'hoo-hreflang-tags' ),
							'eo' => __( 'Esperanto', 'hoo-hreflang-tags' ),
							'et' => __( 'Estonian', 'hoo-hreflang-tags' ),
							'fi' => __( 'Finnish', 'hoo-hreflang-tags' ),
							'fr' => __( 'French', 'hoo-hreflang-tags' ),
							'fr_BE' => __( 'French (Belgium)', 'hoo-hreflang-tags' ),
							'fr_CA' => __( 'French (Canada)', 'hoo-hreflang-tags' ),
							'fr_FR' => __( 'French (France)', 'hoo-hreflang-tags' ),
							'fr_LU' => __( 'French (Luxembourg)', 'hoo-hreflang-tags' ),
							'fur' => __( 'Friulian', 'hoo-hreflang-tags' ),
							'gl_ES' => __( 'Galician', 'hoo-hreflang-tags' ),
							'ka_GE' => __( 'Georgian', 'hoo-hreflang-tags' ),
							'de_AT' => __( 'German (Austria)', 'hoo-hreflang-tags' ),
							'de' => __( 'German (DE)', 'hoo-hreflang-tags' ),
							'de_DE' => __( 'German (Germany)', 'hoo-hreflang-tags' ),
							'de_CH' => __( 'German (Switzerland)', 'hoo-hreflang-tags' ),
							'el' => __( 'Greek', 'hoo-hreflang-tags' ),
							'gu' => __( 'Gujarati', 'hoo-hreflang-tags' ),
							'haz' => __( 'Hazaragi', 'hoo-hreflang-tags' ),
							'he_IL' => __( 'Hebrew', 'hoo-hreflang-tags' ),
							'hi_IN' => __( 'Hindi', 'hoo-hreflang-tags' ),
							'hu_HU' => __( 'Hungarian', 'hoo-hreflang-tags' ),
							'is_IS' => __( 'Icelandic', 'hoo-hreflang-tags' ),
							'id_ID' => __( 'Indonesian', 'hoo-hreflang-tags' ),
							'it' => __( 'Italian', 'hoo-hreflang-tags' ),
							'it_IT' => __( 'Italian (Italy)', 'hoo-hreflang-tags' ),
							'ja' => __( 'Japanese', 'hoo-hreflang-tags' ),
							'jv_ID' => __( 'Javanese', 'hoo-hreflang-tags' ),
							'kab' => __( 'Kabyle', 'hoo-hreflang-tags' ),
							'kk' => __( 'Kazakh', 'hoo-hreflang-tags' ),
							'km' => __( 'Khmer', 'hoo-hreflang-tags' ),
							'ko_KR' => __( 'Korean', 'hoo-hreflang-tags' ),
							'ckb' => __( 'Kurdish (Sorani)', 'hoo-hreflang-tags' ),
							'lo' => __( 'Lao', 'hoo-hreflang-tags' ),
							'lv' => __( 'Latvian', 'hoo-hreflang-tags' ),
							'lt_LT' => __( 'Lithuanian', 'hoo-hreflang-tags' ),
							'mk_MK' => __( 'Macedonian', 'hoo-hreflang-tags' ),
							'ms_MY' => __( 'Malay', 'hoo-hreflang-tags' ),
							'ml_IN' => __( 'Malayalam', 'hoo-hreflang-tags' ),
							'mr' => __( 'Marathi', 'hoo-hreflang-tags' ),
							'mn' => __( 'Mongolian', 'hoo-hreflang-tags' ),
							'ary' => __( 'Moroccan Arabic', 'hoo-hreflang-tags' ),
							'my_MM' => __( 'Myanmar (Burmese)', 'hoo-hreflang-tags' ),
							'ne_NP' => __( 'Nepali', 'hoo-hreflang-tags' ),
							'no_NO' => __( 'Norwegian', 'hoo-hreflang-tags' ),
							'nb_NO' => __( 'Norwegian (Bokmål)', 'hoo-hreflang-tags' ),
							'nn_NO' => __( 'Norwegian (Nynorsk)', 'hoo-hreflang-tags' ),
							'oci' => __( 'Occitan', 'hoo-hreflang-tags' ),
							'ps' => __( 'Pashto', 'hoo-hreflang-tags' ),
							'fa_IR' => __( 'Persian', 'hoo-hreflang-tags' ),
							'pl_PL' => __( 'Polish', 'hoo-hreflang-tags' ),
							'pt' => __( 'Portuguese', 'hoo-hreflang-tags' ),
							'pt_BR' => __( 'Portuguese (Brazil)', 'hoo-hreflang-tags' ),
							'pt_PT' => __( 'Portuguese (Portugal)', 'hoo-hreflang-tags' ),
							'pt_PT_ao90' => __( 'Portuguese (Portugal, AO90)', 'hoo-hreflang-tags' ),
							'pa_IN' => __( 'Punjabi', 'hoo-hreflang-tags' ),
							'rhg' => __( 'Rohingya', 'hoo-hreflang-tags' ),
							'ro_RO' => __( 'Romanian', 'hoo-hreflang-tags' ),
							'ru' => __( 'Russian', 'hoo-hreflang-tags' ),
							'ru_RU' => __( 'Russian (Russia)', 'hoo-hreflang-tags' ),
							'sah' => __( 'Sakha', 'hoo-hreflang-tags' ),
							'gd' => __( 'Scottish Gaelic', 'hoo-hreflang-tags' ),
							'sr_RS' => __( 'Serbian', 'hoo-hreflang-tags' ),
							'szl' => __( 'Silesian', 'hoo-hreflang-tags' ),
							'si_LK' => __( 'Sinhala', 'hoo-hreflang-tags' ),
							'sk_SK' => __( 'Slovak', 'hoo-hreflang-tags' ),
							'sl_SI' => __( 'Slovenian', 'hoo-hreflang-tags' ),
							'azb' => __( 'South Azerbaijani', 'hoo-hreflang-tags' ),
							'es' => __( 'Spanish', 'hoo-hreflang-tags' ),
							'es_AR' => __( 'Spanish (Argentina)', 'hoo-hreflang-tags' ),
							'es_CL' => __( 'Spanish (Chile)', 'hoo-hreflang-tags' ),
							'es_CO' => __( 'Spanish (Colombia)', 'hoo-hreflang-tags' ),
							'es_CR' => __( 'Spanish (Costa Rica)', 'hoo-hreflang-tags' ),
							'es_GT' => __( 'Spanish (Guatemala)', 'hoo-hreflang-tags' ),
							'es_MX' => __( 'Spanish (Mexico)', 'hoo-hreflang-tags' ),
							'es_PE' => __( 'Spanish (Peru)', 'hoo-hreflang-tags' ),
							'es_ES' => __( 'Spanish (Spain)', 'hoo-hreflang-tags' ),
							'es_VE' => __( 'Spanish (Venezuela)', 'hoo-hreflang-tags' ),
							'sv' => __( 'Swedish', 'hoo-hreflang-tags' ),
							'sv_SE' => __( 'Swedish (Sweden) ', 'hoo-hreflang-tags' ),
							'tl' => __( 'Tagalog', 'hoo-hreflang-tags' ),
							'tah' => __( 'Tahitian', 'hoo-hreflang-tags' ),
							'tg' => __( 'Tajik', 'hoo-hreflang-tags' ),
							'ta_IN' => __( 'Tamil', 'hoo-hreflang-tags' ),
							'tt_RU' => __( 'Tatar', 'hoo-hreflang-tags' ),
							'te' => __( 'Telugu', 'hoo-hreflang-tags' ),
							'th' => __( 'Thai', 'hoo-hreflang-tags' ),
							'bo' => __( 'Tibetan', 'hoo-hreflang-tags' ),
							'tr_TR' => __( 'Turkish', 'hoo-hreflang-tags' ),
							'tk' => __( 'Turkmen', 'hoo-hreflang-tags' ),
							'ug_CN' => __( 'Uighur', 'hoo-hreflang-tags' ),
							'uk' => __( 'Ukrainian', 'hoo-hreflang-tags' ),
							'ur' => __( 'Urdu', 'hoo-hreflang-tags' ),
							'uz_UZ' => __( 'Uzbek', 'hoo-hreflang-tags' ),
							'uz' => __( 'Uzbek', 'hoo-hreflang-tags' ),
							'vi' => __( 'Vietnamese', 'hoo-hreflang-tags' ),
							'cy' => __( 'Welsh', 'hoo-hreflang-tags' ),
						),
					),

				),
			),
		),
	);


	// Add other metaboxes as needed

	return $meta_boxes;
}

add_action( 'init', 'hoo_hreflang_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function hoo_hreflang_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
