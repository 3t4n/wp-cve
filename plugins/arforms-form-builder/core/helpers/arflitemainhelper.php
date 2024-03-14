<?php

class arflitemainhelper {

	function arflite_get_pages() {
		global $wpdb, $arflitemainhelper;

		$post = $wpdb->get_results( 'select * from ' . $wpdb->prefix . 'posts where post_type = "page" and (post_status = "publish" or post_status = "private") order by post_title asc limit 0,999' );

		return $post;
	}

	function wp_arflite_pages_dropdown( $field_name, $page_id, $truncate = false, $id = '' ) {

		global $wpdb, $arflitemainhelper, $arflitemaincontroller;

		$pages = $arflitemainhelper->arflite_get_pages();

		if ( $id != '' ) {
			$selec_id = $id;
		} else {
			$selec_id = $field_name;
		}

		$arf_cl_field_selected_option = array();
		$arf_cl_field_options         = array();
		$cntr                         = 0;
		foreach ( $pages as $page ) {

			$post_title_value = ( $truncate ) ? $arflitemainhelper->arflitetruncate( $page->post_title, $truncate ) : $page->post_title;

			if ( ( isset( $_POST[ $field_name ] ) && intval( $_POST[ $field_name ] ) == $page->ID ) || ( ! isset( $_POST[ $field_name ] ) && $page_id == $page->ID ) || $cntr == 0 ) { //phpcs:ignore
				$arf_cl_field_selected_option['page_id'] = $page->ID;
				$arf_cl_field_selected_option['name']    = ! empty( $post_title_value ) ? $post_title_value : esc_html__( 'Page ID:', 'arforms-form-builder' ) . ' ' . $page->ID;
			}

			$arf_cl_field_options[ $page->ID ] = ! empty( $post_title_value ) ? $post_title_value : esc_html__( 'Page ID:', 'arforms-form-builder' ) . ' ' . $page->ID;

			$cntr++;
		}
		$arf_cl_selected_page_id = isset( $arf_cl_field_selected_option['page_id'] ) ? $arf_cl_field_selected_option['page_id'] : '';
		$arf_cl_selected_name    = isset( $arf_cl_field_selected_option['name'] ) ? $arf_cl_field_selected_option['name'] : '';

		echo $arflitemaincontroller->arflite_selectpicker_dom( $field_name, $selec_id . '_arf_wp_pages', '', 'width:240px; margin-left: 10px; clear: none;', $arf_cl_selected_page_id, array(), $arf_cl_field_options, false, array(), false, array(), false, array(), true );//phpcs:ignore
	}

	function arflite_esc_textarea( $text ) {

		$safe_text = str_replace( '&quot;', '"', $text );

		$safe_text = htmlspecialchars( $safe_text, ENT_NOQUOTES );

		return apply_filters( 'esc_textarea', $safe_text, $text );
	}

	function arflite_script_version( $handle, $list = 'scripts' ) {

		global $wp_scripts;

		if ( ! $wp_scripts ) {
			return false;
		}

		$ver = 0;

		if ( isset( $wp_scripts->registered[ $handle ] ) ) {
			$query = $wp_scripts->registered[ $handle ];
		}

		if ( is_object( $query ) ) {
			$ver = $query->ver;
		}

		return $ver;
	}

	function arflite_get_unique_key( $name = '', $table_name = '', $column = '', $id = 0, $num_chars = 6 ) {

		global $wpdb;

		$key = '';

		if ( ! empty( $name ) ) {

			if ( function_exists( 'sanitize_key' ) ) {
				$key = sanitize_key( $name );
			} else {
				$key = sanitize_title_with_dashes( $name );
			}
		}

		if ( empty( $key ) ) {

			$max_slug_value = pow( 36, $num_chars );

			$min_slug_value = 37;

			$key = base_convert( rand( intval( $min_slug_value ), intval( $max_slug_value ) ), 10, 36 );
		}

		if ( is_numeric( $key ) || in_array( $key, array( 'id', 'key', 'created-at', 'detaillink', 'editlink', 'siteurl', 'evenodd' ) ) ) {
			$key = $key . 'a';
		}

		$query = "SELECT $column FROM $table_name WHERE $column = %s AND ID != %d LIMIT 1";

		$key_check = $wpdb->get_var( $wpdb->prepare( $query, $key, $id ) ); //phpcs:ignore

		if ( $key_check || is_numeric( $key_check ) ) {

			$suffix = 2;

			do {

				$alt_post_name = substr( $key, 0, 200 - ( strlen( $suffix ) + 1 ) ) . "$suffix";

				$key_check = $wpdb->get_var( $wpdb->prepare( $query, $alt_post_name, $id ) ); //phpcs:ignore

				$suffix++;
			} while ( $key_check || is_numeric( $key_check ) );

			$key = $alt_post_name;
		}

		return $key;
	}

	function arflite_get_us_states() {

		return apply_filters(
			'arfliteusstates',
			array(
				'AL' => 'Alabama',
				'AK' => 'Alaska',
				'AR' => 'Arkansas',
				'AZ' => 'Arizona',
				'CA' => 'California',
				'CO' => 'Colorado',
				'CT' => 'Connecticut',
				'DE' => 'Delaware',
				'FL' => 'Florida',
				'GA' => 'Georgia',
				'HI' => 'Hawaii',
				'ID' => 'Idaho',
				'IL' => 'Illinois',
				'IN' => 'Indiana',
				'IA' => 'Iowa',
				'KS' => 'Kansas',
				'KY' => 'Kentucky',
				'LA' => 'Louisiana',
				'ME' => 'Maine',
				'MD' => 'Maryland',
				'MA' => 'Massachusetts',
				'MI' => 'Michigan',
				'MN' => 'Minnesota',
				'MS' => 'Mississippi',
				'MO' => 'Missouri',
				'MT' => 'Montana',
				'NE' => 'Nebraska',
				'NV' => 'Nevada',
				'NH' => 'New Hampshire',
				'NJ' => 'New Jersey',
				'NM' => 'New Mexico',
				'NY' => 'New York',
				'NC' => 'North Carolina',
				'ND' => 'North Dakota',
				'OH' => 'Ohio',
				'OK' => 'Oklahoma',
				'OR' => 'Oregon',
				'PA' => 'Pennsylvania',
				'RI' => 'Rhode Island',
				'SC' => 'South Carolina',
				'SD' => 'South Dakota',
				'TN' => 'Tennessee',
				'TX' => 'Texas',
				'UT' => 'Utah',
				'VT' => 'Vermont',
				'VA' => 'Virginia',
				'WA' => 'Washington',
				'WV' => 'West Virginia',
				'WI' => 'Wisconsin',
				'WY' => 'Wyoming',
			)
		);
	}

	function arfliteget_countries() {

		return apply_filters(
			'arflitecountries',
			array(
				__( 'Afghanistan', 'arforms-form-builder' ),
				__( 'Albania', 'arforms-form-builder' ),
				__( 'Algeria', 'arforms-form-builder' ),
				__( 'American Samoa', 'arforms-form-builder' ),
				__( 'Andorra', 'arforms-form-builder' ),
				__( 'Angola', 'arforms-form-builder' ),
				__( 'Anguilla', 'arforms-form-builder' ),
				__( 'Antarctica', 'arforms-form-builder' ),
				__( 'Antigua and Barbuda', 'arforms-form-builder' ),
				__( 'Argentina', 'arforms-form-builder' ),
				__( 'Armenia', 'arforms-form-builder' ),
				__( 'Aruba', 'arforms-form-builder' ),
				__( 'Australia', 'arforms-form-builder' ),
				__( 'Austria', 'arforms-form-builder' ),
				__( 'Azerbaijan', 'arforms-form-builder' ),
				__( 'Bahamas', 'arforms-form-builder' ),
				__( 'Bahrain', 'arforms-form-builder' ),
				__( 'Bangladesh', 'arforms-form-builder' ),
				__( 'Barbados', 'arforms-form-builder' ),
				__( 'Belarus', 'arforms-form-builder' ),
				__( 'Belgium', 'arforms-form-builder' ),
				__( 'Belize', 'arforms-form-builder' ),
				__( 'Benin', 'arforms-form-builder' ),
				__( 'Bermuda', 'arforms-form-builder' ),
				__( 'Bhutan', 'arforms-form-builder' ),
				__( 'Bolivia', 'arforms-form-builder' ),
				__( 'Bosnia and Herzegovina', 'arforms-form-builder' ),
				__( 'Botswana', 'arforms-form-builder' ),
				__( 'Brazil', 'arforms-form-builder' ),
				__( 'Brunei', 'arforms-form-builder' ),
				__( 'Bulgaria', 'arforms-form-builder' ),
				__( 'Burkina Faso', 'arforms-form-builder' ),
				__( 'Burundi', 'arforms-form-builder' ),
				__( 'Cambodia', 'arforms-form-builder' ),
				__( 'Cameroon', 'arforms-form-builder' ),
				__( 'Canada', 'arforms-form-builder' ),
				__( 'Cape Verde', 'arforms-form-builder' ),
				__( 'Cayman Islands', 'arforms-form-builder' ),
				__( 'Central African Republic', 'arforms-form-builder' ),
				__( 'Chad', 'arforms-form-builder' ),
				__( 'Chile', 'arforms-form-builder' ),
				__( 'China', 'arforms-form-builder' ),
				__( 'Colombia', 'arforms-form-builder' ),
				__( 'Comoros', 'arforms-form-builder' ),
				__( 'Congo', 'arforms-form-builder' ),
				__( 'Costa Rica', 'arforms-form-builder' ),
				__( 'Croatia', 'arforms-form-builder' ),
				__( 'Cuba', 'arforms-form-builder' ),
				__( 'Cyprus', 'arforms-form-builder' ),
				__( 'Czech Republic', 'arforms-form-builder' ),
				__( 'Denmark', 'arforms-form-builder' ),
				__( 'Djibouti', 'arforms-form-builder' ),
				__( 'Dominica', 'arforms-form-builder' ),
				__( 'Dominican Republic', 'arforms-form-builder' ),
				__( 'East Timor', 'arforms-form-builder' ),
				__( 'Ecuador', 'arforms-form-builder' ),
				__( 'Egypt', 'arforms-form-builder' ),
				__( 'El Salvador', 'arforms-form-builder' ),
				__( 'Equatorial Guinea', 'arforms-form-builder' ),
				__( 'Eritrea', 'arforms-form-builder' ),
				__( 'Estonia', 'arforms-form-builder' ),
				__( 'Ethiopia', 'arforms-form-builder' ),
				__( 'Fiji', 'arforms-form-builder' ),
				__( 'Finland', 'arforms-form-builder' ),
				__( 'France', 'arforms-form-builder' ),
				__( 'French Guiana', 'arforms-form-builder' ),
				__( 'French Polynesia', 'arforms-form-builder' ),
				__( 'Gabon', 'arforms-form-builder' ),
				__( 'Gambia', 'arforms-form-builder' ),
				__( 'Georgia', 'arforms-form-builder' ),
				__( 'Germany', 'arforms-form-builder' ),
				__( 'Ghana', 'arforms-form-builder' ),
				__( 'Gibraltar', 'arforms-form-builder' ),
				__( 'Greece', 'arforms-form-builder' ),
				__( 'Greenland', 'arforms-form-builder' ),
				__( 'Grenada', 'arforms-form-builder' ),
				__( 'Guam', 'arforms-form-builder' ),
				__( 'Guatemala', 'arforms-form-builder' ),
				__( 'Guinea', 'arforms-form-builder' ),
				__( 'Guinea-Bissau', 'arforms-form-builder' ),
				__( 'Guyana', 'arforms-form-builder' ),
				__( 'Haiti', 'arforms-form-builder' ),
				__( 'Honduras', 'arforms-form-builder' ),
				__( 'Hong Kong', 'arforms-form-builder' ),
				__( 'Hungary', 'arforms-form-builder' ),
				__( 'Iceland', 'arforms-form-builder' ),
				__( 'India', 'arforms-form-builder' ),
				__( 'Indonesia', 'arforms-form-builder' ),
				__( 'Iran', 'arforms-form-builder' ),
				__( 'Iraq', 'arforms-form-builder' ),
				__( 'Ireland', 'arforms-form-builder' ),
				__( 'Israel', 'arforms-form-builder' ),
				__( 'Italy', 'arforms-form-builder' ),
				__( 'Jamaica', 'arforms-form-builder' ),
				__( 'Japan', 'arforms-form-builder' ),
				__( 'Jordan', 'arforms-form-builder' ),
				__( 'Kazakhstan', 'arforms-form-builder' ),
				__( 'Kenya', 'arforms-form-builder' ),
				__( 'Kiribati', 'arforms-form-builder' ),
				__( 'North Korea', 'arforms-form-builder' ),
				__( 'South Korea', 'arforms-form-builder' ),
				__( 'Kuwait', 'arforms-form-builder' ),
				__( 'Kyrgyzstan', 'arforms-form-builder' ),
				__( 'Laos', 'arforms-form-builder' ),
				__( 'Latvia', 'arforms-form-builder' ),
				__( 'Lebanon', 'arforms-form-builder' ),
				__( 'Lesotho', 'arforms-form-builder' ),
				__( 'Liberia', 'arforms-form-builder' ),
				__( 'Libya', 'arforms-form-builder' ),
				__( 'Liechtenstein', 'arforms-form-builder' ),
				__( 'Lithuania', 'arforms-form-builder' ),
				__( 'Luxembourg', 'arforms-form-builder' ),
				__( 'North Macedonia', 'arforms-form-builder' ),
				__( 'Madagascar', 'arforms-form-builder' ),
				__( 'Malawi', 'arforms-form-builder' ),
				__( 'Malaysia', 'arforms-form-builder' ),
				__( 'Maldives', 'arforms-form-builder' ),
				__( 'Mali', 'arforms-form-builder' ),
				__( 'Malta', 'arforms-form-builder' ),
				__( 'Marshall Islands', 'arforms-form-builder' ),
				__( 'Mauritania', 'arforms-form-builder' ),
				__( 'Mauritius', 'arforms-form-builder' ),
				__( 'Mexico', 'arforms-form-builder' ),
				__( 'Micronesia', 'arforms-form-builder' ),
				__( 'Moldova', 'arforms-form-builder' ),
				__( 'Monaco', 'arforms-form-builder' ),
				__( 'Mongolia', 'arforms-form-builder' ),
				__( 'Montenegro', 'arforms-form-builder' ),
				__( 'Montserrat', 'arforms-form-builder' ),
				__( 'Morocco', 'arforms-form-builder' ),
				__( 'Mozambique', 'arforms-form-builder' ),
				__( 'Myanmar', 'arforms-form-builder' ),
				__( 'Namibia', 'arforms-form-builder' ),
				__( 'Nauru', 'arforms-form-builder' ),
				__( 'Nepal', 'arforms-form-builder' ),
				__( 'Netherlands', 'arforms-form-builder' ),
				__( 'New Zealand', 'arforms-form-builder' ),
				__( 'Nicaragua', 'arforms-form-builder' ),
				__( 'Niger', 'arforms-form-builder' ),
				__( 'Nigeria', 'arforms-form-builder' ),
				__( 'Norway', 'arforms-form-builder' ),
				__( 'Northern Mariana Islands', 'arforms-form-builder' ),
				__( 'Oman', 'arforms-form-builder' ),
				__( 'Pakistan', 'arforms-form-builder' ),
				__( 'Palau', 'arforms-form-builder' ),
				__( 'Palestine', 'arforms-form-builder' ),
				__( 'Panama', 'arforms-form-builder' ),
				__( 'Papua New Guinea', 'arforms-form-builder' ),
				__( 'Paraguay', 'arforms-form-builder' ),
				__( 'Peru', 'arforms-form-builder' ),
				__( 'Philippines', 'arforms-form-builder' ),
				__( 'Poland', 'arforms-form-builder' ),
				__( 'Portugal', 'arforms-form-builder' ),
				__( 'Puerto Rico', 'arforms-form-builder' ),
				__( 'Qatar', 'arforms-form-builder' ),
				__( 'Romania', 'arforms-form-builder' ),
				__( 'Russia', 'arforms-form-builder' ),
				__( 'Rwanda', 'arforms-form-builder' ),
				__( 'Saint Kitts and Nevis', 'arforms-form-builder' ),
				__( 'Saint Lucia', 'arforms-form-builder' ),
				__( 'Saint Vincent and the Grenadines', 'arforms-form-builder' ),
				__( 'Samoa', 'arforms-form-builder' ),
				__( 'San Marino', 'arforms-form-builder' ),
				__( 'Sao Tome and Principe', 'arforms-form-builder' ),
				__( 'Saudi Arabia', 'arforms-form-builder' ),
				__( 'Senegal', 'arforms-form-builder' ),
				__( 'Serbia and Montenegro', 'arforms-form-builder' ),
				__( 'Seychelles', 'arforms-form-builder' ),
				__( 'Sierra Leone', 'arforms-form-builder' ),
				__( 'Singapore', 'arforms-form-builder' ),
				__( 'Slovakia', 'arforms-form-builder' ),
				__( 'Slovenia', 'arforms-form-builder' ),
				__( 'Solomon Islands', 'arforms-form-builder' ),
				__( 'Somalia', 'arforms-form-builder' ),
				__( 'South Africa', 'arforms-form-builder' ),
				__( 'Spain', 'arforms-form-builder' ),
				__( 'Sri Lanka', 'arforms-form-builder' ),
				__( 'Sudan', 'arforms-form-builder' ),
				__( 'Suriname', 'arforms-form-builder' ),
				__( 'Swaziland', 'arforms-form-builder' ),
				__( 'Sweden', 'arforms-form-builder' ),
				__( 'Switzerland', 'arforms-form-builder' ),
				__( 'Syria', 'arforms-form-builder' ),
				__( 'Taiwan', 'arforms-form-builder' ),
				__( 'Tajikistan', 'arforms-form-builder' ),
				__( 'Tanzania', 'arforms-form-builder' ),
				__( 'Thailand', 'arforms-form-builder' ),
				__( 'Togo', 'arforms-form-builder' ),
				__( 'Tonga', 'arforms-form-builder' ),
				__( 'Trinidad and Tobago', 'arforms-form-builder' ),
				__( 'Tunisia', 'arforms-form-builder' ),
				__( 'Turkey', 'arforms-form-builder' ),
				__( 'Turkmenistan', 'arforms-form-builder' ),
				__( 'Tuvalu', 'arforms-form-builder' ),
				__( 'Uganda', 'arforms-form-builder' ),
				__( 'Ukraine', 'arforms-form-builder' ),
				__( 'United Arab Emirates', 'arforms-form-builder' ),
				__( 'United Kingdom', 'arforms-form-builder' ),
				__( 'United States', 'arforms-form-builder' ),
				__( 'Uruguay', 'arforms-form-builder' ),
				__( 'Uzbekistan', 'arforms-form-builder' ),
				__( 'Vanuatu', 'arforms-form-builder' ),
				__( 'Vatican City', 'arforms-form-builder' ),
				__( 'Venezuela', 'arforms-form-builder' ),
				__( 'Vietnam', 'arforms-form-builder' ),
				__( 'Virgin Islands, British', 'arforms-form-builder' ),
				__( 'Virgin Islands, U.S.', 'arforms-form-builder' ),
				__( 'Yemen', 'arforms-form-builder' ),
				__( 'Zambia', 'arforms-form-builder' ),
				__( 'Zimbabwe', 'arforms-form-builder' ),
			)
		);
	}

	function arflite_get_country_codes() {

		return apply_filters(
			'arflitecountrycodes',
			array(
				'+1'   => 'North America',
				'+269' => 'Mayotte, Comoros Is.',
				'+501' => 'Belize',
				'+690' => 'Tokelau',
				'+20'  => 'Egypt',
				'+27'  => 'South Africa',
				'+502' => 'Guatemala',
				'+691' => 'F.S. Micronesia',
				'+212' => 'Morocco',
				'+290' => 'Saint Helena',
				'+503' => 'El Salvador',
				'+692' => 'Marshall Islands',
				'+213' => 'Algeria',
				'+291' => 'Eritrea',
				'+504' => 'Honduras',
				'+7'   => 'Russia, Kazakhstan',
				'+216' => 'Tunisia',
				'+297' => 'Aruba',
				'+505' => 'Nicaragua',
				'+800' => 'Int\'l Freephone',
				'+218' => 'Libya',
				'+298' => 'Færoe Islands',
				'+506' => 'Costa Rica',
				'+81'  => 'Japan',
				'+220' => 'Gambia',
				'+299' => 'Greenland',
				'+507' => 'Panama',
				'+82'  => 'Korea (South)',
				'+221' => 'Senegal',
				'+30'  => 'Greece',
				'+508' => 'St Pierre & Miquélon',
				'+84'  => 'Viet Nam',
				'+222' => 'Mauritania',
				'+31'  => 'Netherlands',
				'+509' => 'Haiti',
				'+850' => 'DPR Korea (North)',
				'+223' => 'Mali',
				'+32'  => 'Belgium',
				'+51'  => 'Peru',
				'+224' => 'Guinea',
				'+33'  => 'France',
				'+52'  => 'Mexico',
				'+852' => 'Hong Kong',
				'+225' => 'Ivory Coast',
				'+34'  => 'Spain',
				'+53'  => 'Cuba',
				'+853' => 'Macau',
				'+226' => 'Burkina Faso',
				'+350' => 'Gibraltar',
				'+54'  => 'Argentina',
				'+855' => 'Cambodia',
				'+227' => 'Niger',
				'+351' => 'Portugal',
				'+55'  => 'Brazil',
				'+856' => 'Laos',
				'+228' => 'Togo',
				'+352' => 'Luxembourg',
				'+56'  => 'Chile',
				'+86'  => '(People\'s Rep.) China',
				'+229' => 'Benin',
				'+353' => 'Ireland',
				'+57'  => 'Colombia',
				'+870' => 'Inmarsat SNAC',
				'+230' => 'Mauritius',
				'+354' => 'Iceland',
				'+58'  => 'Venezuela',
				'+871' => 'Inmarsat (Atl-East)',
				'+231' => 'Liberia',
				'+355' => 'Albania',
				'+590' => 'Guadeloupe',
				'+872' => 'Inmarsat (Pacific)',
				'+232' => 'Sierra Leone',
				'+356' => 'Malta',
				'+591' => 'Bolivia',
				'+873' => 'Inmarsat (Indian O.)',
				'+233' => 'Ghana',
				'+357' => 'Cyprus',
				'+592' => 'Guyana',
				'+874' => 'Inmarsat (Atl-West)',
				'+234' => 'Nigeria',
				'+358' => 'Finland',
				'+593' => 'Ecuador',
				'+880' => 'Bangladesh',
				'+235' => 'Chad',
				'+359' => 'Bulgaria',
				'+594' => 'Guiana (French)',
				'+881' => 'Satellite services',
				'+236' => 'Central African Rep.',
				'+36'  => 'Hungary',
				'+595' => 'Paraguay',
				'+886' => 'Taiwan/"reserved"',
				'+237' => 'Cameroon',
				'+370' => 'Lithuania',
				'+596' => 'Martinique',
				'+90'  => 'Turkey',
				'+238' => 'Cape Verde',
				'+371' => 'Latvia',
				'+597' => 'Suriname',
				'+91'  => 'India',
				'+239' => 'São Tomé & Principé',
				'+372' => 'Estonia',
				'+598' => 'Uruguay',
				'+92'  => 'Pakistan',
				'+240' => 'Equatorial Guinea',
				'+373' => 'Moldova',
				'+599' => 'Netherlands Antilles',
				'+93'  => 'Afghanistan',
				'+241' => 'Gabon',
				'+374' => 'Armenia',
				'+60'  => 'Malaysia',
				'+94'  => 'Sri Lanka',
				'+242' => 'Congo',
				'+375' => 'Belarus',
				'+61'  => 'Australia',
				'+95'  => 'Myanmar (Burma)',
				'+243' => 'Zaire',
				'+376' => 'Andorra',
				'+62'  => 'Indonesia',
				'+960' => 'Maldives',
				'+244' => 'Angola',
				'+377' => 'Monaco',
				'+63'  => 'Philippines',
				'+961' => 'Lebanon',
				'+245' => 'Guinea-Bissau',
				'+378' => 'San Marino',
				'+64'  => 'New Zealand',
				'+962' => 'Jordan',
				'+246' => 'Diego Garcia',
				'+379' => 'Vatican City (use +39)',
				'+65'  => 'Singapore',
				'+963' => 'Syria',
				'+247' => 'Ascension',
				'+380' => 'Ukraine',
				'+66'  => 'Thailand',
				'+964' => 'Iraq',
				'+248' => 'Seychelles',
				'+381' => 'Yugoslavia',
				'+670' => 'East Timor',
				'+965' => 'Kuwait',
				'+249' => 'Sudan',
				'+385' => 'Croatia',
				'+966' => 'Saudi Arabia',
				'+250' => 'Rwanda',
				'+386' => 'Slovenia',
				'+672' => 'Australian Ext. Terr.',
				'+967' => 'Yemen',
				'+251' => 'Ethiopia',
				'+387' => 'Bosnia - Herzegovina',
				'+673' => 'Brunei Darussalam',
				'+968' => 'Oman',
				'+252' => 'Somalia',
				'+389' => '(FYR) Macedonia',
				'+674' => 'Nauru',
				'+970' => 'Palestine',
				'+253' => 'Djibouti',
				'+39'  => 'Italy',
				'+675' => 'Papua New Guinea',
				'+971' => 'United Arab Emirates',
				'+254' => 'Kenya',
				'+40'  => 'Romania',
				'+676' => 'Tonga',
				'+972' => 'Israel',
				'+255' => 'Tanzania',
				'+41'  => 'Switzerland, (Liecht.)',
				'+677' => 'Solomon Islands',
				'+973' => 'Bahrain',
				'+256' => 'Uganda',
				'+678' => 'Vanuatu',
				'+974' => 'Qatar',
				'+257' => 'Burundi',
				'+420' => 'Czech Republic',
				'+679' => 'Fiji',
				'+975' => 'Bhutan',
				'+258' => 'Mozambique',
				'+421' => 'Slovakia',
				'+680' => 'Palau',
				'+976' => 'Mongolia',
				'+260' => 'Zambia',
				'+423' => 'Liechtenstein',
				'+681' => 'Wallis and Futuna',
				'+977' => 'Nepal',
				'+261' => 'Madagascar',
				'+43'  => 'Austria',
				'+682' => 'Cook Islands',
				'+98'  => 'Iran',
				'+262' => 'Reunion Island',
				'+44'  => 'United Kingdom',
				'+683' => 'Niue',
				'+992' => 'Tajikistan',
				'+263' => 'Zimbabwe',
				'+45'  => 'Denmark',
				'+684' => 'American Samoa',
				'+993' => 'Turkmenistan',
				'+264' => 'Namibia',
				'+46'  => 'Sweden',
				'+685' => 'Western Samoa',
				'+994' => 'Azerbaijan',
				'+265' => 'Malawi',
				'+47'  => 'Norway',
				'+686' => 'Kiribati',
				'+995' => 'Rep. of Georgia',
				'+266' => 'Lesotho',
				'+48'  => 'Poland',
				'+687' => 'New Caledonia',
				'+996' => 'Kyrgyz Republic',
				'+267' => 'Botswana',
				'+49'  => 'Germany',
				'+688' => 'Tuvalu',
				'+997' => 'Kazakhstan',
				'+268' => 'Swaziland',
				'+500' => 'Falkland Islands',
				'+689' => 'French Polynesia',
				'+998' => 'Uzbekistan',
			)
		);
	}

	function arflite_user_has_permission( $needed_role ) {

		if ( $needed_role == '' || current_user_can( $needed_role ) ) {
			return true;
		}

		$roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' );

		foreach ( $roles as $role ) {

			if ( current_user_can( $role ) ) {
				return true;
			}

			if ( $role == $needed_role ) {
				break;
			}
		}

		return false;
	}

	function arflite_is_super_admin( $user_id = false ) {

		if ( function_exists( 'arflite_is_super_admin' ) ) {
			return arflite_is_super_admin( $user_id );
		} else {
			return is_super_admin( $user_id );
		}
	}

	function arflite_checked( $values, $current ) {

		global $arflitemainhelper;

		if ( $arflitemainhelper->arflite_check_selected( $values, $current ) ) {
			echo ' checked="checked"';
		}
	}

	function arflite_check_selected( $values, $current ) {

		$current = esc_attr( $current );

		if ( is_array( $values ) ) {
			$values = array_map( array( 'armainhelper', 'arflite_recursive_trim' ), $values );
		} else {
			$values = trim( $values );
		}

		$current = trim( $current );

		if ( ( is_array( $values ) && in_array( $current, $values ) ) || ( ! is_array( $values ) && $values == $current ) ) {
			return true;
		} else {
			return false;
		}
	}

	function arflite_recursive_trim( &$value ) {

		if ( is_array( $value ) ) {
			$value = array_map( array( 'armainhelper', 'arflite_recursive_trim' ), $value );
		} else {
			$value = trim( $value );
		}

		return esc_attr( $value );
	}

	function arflite_frm_get_main_message( $message = '' ) {
		return $message;
	}

	function arflitetruncate( $str, $length, $minword = 3, $continue = '...' ) {

		$length = (int) $length;

		$str = strip_tags( $str );

		$original_len = ( function_exists( 'mb_strlen' ) ) ? mb_strlen( $str ) : strlen( $str );

		if ( $length == 0 ) {
			return '';
		} elseif ( $length <= 10 ) {

			$sub = ( function_exists( 'mb_substr' ) ) ? mb_substr( $str, 0, $length ) : substr( $str, 0, $length );
			return $sub . ( ( $length < $original_len ) ? $continue : '' );
		}

		$sub = '';
		$len = 0;

		$words = ( function_exists( 'mb_split' ) ) ? mb_split( ' ', $str ) : explode( ' ', $str );

		foreach ( $words as $word ) {

			$part = ( ( $sub != '' ) ? ' ' : '' ) . $word;

			$sub .= $part;

			$len += ( function_exists( 'mb_strlen' ) ) ? mb_strlen( $part ) : strlen( $part );

			$total_len = ( function_exists( 'mb_strlen' ) ) ? mb_strlen( $sub ) : strlen( $sub );

			if ( str_word_count( $sub ) > $minword && $total_len >= $length ) {
				break;
			}

			unset( $total_len );
		}

		return $sub . ( ( $len < $original_len ) ? $continue : '' );
	}

	function arfliteprepend_and_or_where( $starts_with = ' WHERE ', $where = '' ) {

		if ( is_array( $where ) ) {

			global $ARFLiteMdlDb, $wpdb;

			extract( $ARFLiteMdlDb->arflite_get_where_clause_and_values( $where ) );

			$where = $wpdb->prepare( $where, $values ); //phpcs:ignore
		} else {

			$where = ( ( $where == '' ) ? '' : $starts_with . $where );
		}

		return $where;
	}

	function arflitegetLastRecordNum( $r_count, $current_p, $p_size ) {

		return ( ( $r_count < ( $current_p * $p_size ) ) ? $r_count : ( $current_p * $p_size ) );
	}

	function arflitegetFirstRecordNum( $r_count, $current_p, $p_size ) {

		if ( $current_p == 1 ) {
			return 1;
		} else {
			return ( $this->arflitegetLastRecordNum( $r_count, ( $current_p - 1 ), $p_size ) + 1 );
		}
	}

	function arflitegetRecordCount( $where, $table_name ) {

		global $wpdb, $arflitemainhelper;

		$query = 'SELECT COUNT(*) FROM ' . $table_name . $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE ', $where );

		return $wpdb->get_var( $query ); //phpcs:ignore
	}

	function arflitegetPageCount( $p_size, $where, $table_name ) {

		if ( is_numeric( $where ) ) {
			return ceil( (int) $where / (int) $p_size );
		} else {
			return ceil( (int) $this->arflitegetRecordCount( $where, $table_name ) / (int) $p_size );
		}
	}

	function arflitegetPage( $current_p, $p_size, $where, $order_by, $table_name ) {

		global $wpdb, $arflitemainhelper;

		$end_index = $current_p * $p_size;

		$start_index = $end_index - $p_size;

		$query = 'SELECT *  FROM ' . $table_name . $arflitemainhelper->arfliteprepend_and_or_where( ' WHERE', $where ) . $order_by . ' LIMIT ' . $start_index . ',' . $p_size;

		$results = $wpdb->get_results( $query ); //phpcs:ignore

		return $results;
	}

	function arflite_get_referer_query( $query ) {

		if ( strpos( $query, 'google.' ) ) {

			$pattern = '/^.*[\?&]q=(.*)$/';
		} elseif ( strpos( $query, 'bing.com' ) ) {

			$pattern = '/^.*q=(.*)$/';
		} elseif ( strpos( $query, 'yahoo.' ) ) {

			$pattern = '/^.*[\?&]p=(.*)$/';
		} elseif ( strpos( $query, 'ask.' ) ) {

			$pattern = '/^.*[\?&]q=(.*)$/';
		} else {

			return false;
		}

		preg_match( $pattern, $query, $matches );

		if ( isset( $matches ) && count( $matches ) < 1 ) {
			return urldecode( $query );
		}

		$querystr = substr( $matches[1], 0, strpos( $matches[1], '&' ) );

		return urldecode( $querystr );
	}

	function arflite_get_referer_info() {

		global $arflitemainhelper;

		$referrerinfo = '';

		$keywords = array();

		$i = 1;

		$referrerinfo = !empty( $_SERVER['HTTP_REFERER'])  ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';

		$i = 1;

		if ( isset( $_SESSION ) && isset( $_SESSION['arfhttppages'] ) && $_SESSION['arfhttppages'] ) {

			foreach ( $_SESSION['arfhttppages'] as $page ) {

				$referrerinfo .= str_pad( "Page visited $i: ", 20 ) . $page . "\r\n";

				$i++;
			}

			$referrerinfo .= "\r\n";
		}

		$i = 1;

		foreach ( $keywords as $keyword ) {

			$referrerinfo .= str_pad( "Keyword $i: ", 20 ) . $keyword . "\r\n";

			$i++;
		}

		$referrerinfo .= "\r\n";

		return $referrerinfo;
	}

	function arflite_jquery_classic_themes() {

		return array(
			'default_theme' => 'Default',
			'1'             => 'Sky Blue',
			'2'             => 'Lime Green',
			'3'             => 'White',
			'4'             => 'White (Reverse)',
			'5'             => 'Coral',
			'6'             => 'Violet',
			'7'             => 'Red',
			'8'             => 'Forest Green',
			'9'             => 'Royal Blue',
			'10'            => 'Hot Pink',
			'11'            => 'Aquamarine',
			'12'            => 'Golden',
		);
	}

	function arflite_jquery_solid_themes() {

		return array(
			'13' => 'Violet',
			'14' => 'Forest Green',
			'15' => 'Sky Blue',
			'16' => 'Aqua Blue',
			'17' => 'Hot Pink',
			'18' => 'Coral',
			'19' => 'Red',
			'20' => 'Deep Pink',
			'21' => 'Royal Blue',
			'22' => 'Ivory',
			'23' => 'Off White',
			'24' => 'Black',
		);
	}

	function arflite_jquery_css_url( $arfcalthemecss ) {

		$uploads = wp_upload_dir();

		if ( ! $arfcalthemecss || $arfcalthemecss == '' || $arfcalthemecss == 'default' ) {

			$css_file = ARFLITEURL . '/css/calender/default_theme_bootstrap-datetimepicker.css';
		} elseif ( preg_match( '/^http.?:\/\/.*\..*$/', $arfcalthemecss ) ) {

			$css_file = $arfcalthemecss;
		} else {

			$file_path = ARFLITEURL . '/css/calender/' . $arfcalthemecss . '_bootstrap-datetimepicker.css';

			$css_file = $file_path;
		}

		return $css_file;
	}

	function arflite_datepicker_version() {

		global $arflitemainhelper;

		$jq = $arflitemainhelper->arflite_script_version( 'jquery' );

		$new_ver = true;

		if ( $jq ) {

			$new_ver = ( (float) $jq >= 1.5 ) ? true : false;
		} else {

			global $wp_version;

			$new_ver = true;
		}

		return ( $new_ver ) ? '' : '.1.7.3';
	}

	function arflite_get_user_id_param( $user_id ) {

		if ( $user_id && ! empty( $user_id ) && ! is_numeric( $user_id ) ) {

			if ( $user_id == 'current' ) {

				global $user_ID;

				$user_id = $user_ID;
			} else {

				if ( function_exists( 'get_user_by' ) ) {
					$user = get_user_by( 'login', $user_id );
				} else {
					$user = get_user_by( $user_id );
				}

				if ( $user ) {
					$user_id = $user->ID; 
				}

				unset( $user );
			}
		}

		return $user_id;
	}

	function arflite_get_formatted_time( $date, $date_format = false, $time_format = false ) {

		if ( empty( $date ) ) {
			return $date;
		}

		if ( ! $date_format ) {
			$date_format = get_option( 'date_format' );
		}

		if ( preg_match( '/^\d{1-2}\/\d{1-2}\/\d{4}$/', $date ) ) {

			global $arflite_style_settings, $arflitemainhelper;

			$date = $arflitemainhelper->arfliteconvert_date( $date, $arflite_style_settings->date_format, 'Y-m-d' );
		}

		$do_time = ( date( 'H:i:s', strtotime( $date ) ) == '00:00:00' ) ? false : true;

		$date = get_date_from_gmt( $date );

		$formatted = date_i18n( $date_format, strtotime( $date ) );

		if ( $do_time ) {

			if ( ! $time_format ) {
				$time_format = get_option( 'time_format' );
			}

			$trimmed_format = trim( $time_format );

			if ( $time_format && ! empty( $trimmed_format ) ) {
				$formatted .= ' ' . __( 'at', 'arforms-form-builder' ) . ' ' . date_i18n( $time_format, strtotime( $date ) );
			}
		}

		return $formatted;
	}

	function arflite_get_custom_taxonomy( $post_type, $field ) {

		$taxonomies = get_object_taxonomies( $post_type );

		if ( ! $taxonomies ) {

			return false;
		} else {

			$field = (array) $field;

			if ( ! isset( $field['taxonomy'] ) ) {

				$field['field_options'] = maybe_unserialize( $field['field_options'] );

				$field['taxonomy'] = $field['field_options']['taxonomy'];
			}

			if ( isset( $field['taxonomy'] ) && in_array( $field['taxonomy'], $taxonomies ) ) {
				return $field['taxonomy'];

			} elseif ( $post_type == 'post' ) {
				return 'category';
			} else {
				return reset( $taxonomies );
			}
		}
	}

	function arfliteconvert_date( $date_str, $from_format, $to_format ) {

		$base_struc = preg_split( '/[\/|.| |-]/', $from_format );

		$date_str_parts = preg_split( '/[\/|.| |-]/', $date_str );

		$date_elements = array();

		$p_keys = array_keys( $base_struc );

		foreach ( $p_keys as $p_key ) {

			if ( ! empty( $date_str_parts[ $p_key ] ) ) {
				$date_elements[ $base_struc[ $p_key ] ] = $date_str_parts[ $p_key ];
			} else {
				return false;
			}
		}

		if ( is_numeric( $date_elements['m'] ) ) {
			$dummy_ts = mktime( 0, 0, 0, $date_elements['m'], ( isset( $date_elements['j'] ) ? $date_elements['j'] : $date_elements['d'] ), $date_elements['Y'] );
		} else {
			$dummy_ts = strtotime( $date_str );
		}

		return date( $to_format, $dummy_ts );
	}

	function arfliteget_shortcodes( $content, $form_id ) {

		global $arflitefield;

		$fields = wp_cache_get( 'excluded_captcha_html_field_' . $form_id );

		if ( ! $fields ) {

			$fields = $arflitefield->arflitegetAll( "fi.type not in ('captcha','html') and fi.form_id=" . $form_id );

			wp_cache_set( 'excluded_captcha_html_field_' . $form_id, $fields );
		}

		$tagregexp = 'editlink|siteurl|sitename|id|key|attachment_id|ip_address|created-at';

		foreach ( $fields as $field ) {
			$tagregexp .= '|' . $field->id . '|' . $field->field_key;
		}

		preg_match_all( "/\[(if )?($tagregexp)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER );

		return $matches;
	}

	function arflitehuman_time_diff( $from, $to = '' ) {

		if ( empty( $to ) ) {
			$to = time();
		}

		$chunks = array(
			array( 60 * 60 * 24 * 365, __( 'year', 'arforms-form-builder' ), __( 'years', 'arforms-form-builder' ) ),
			array( 60 * 60 * 24 * 30, __( 'month', 'arforms-form-builder' ), __( 'months', 'arforms-form-builder' ) ),
			array( 60 * 60 * 24 * 7, __( 'week', 'arforms-form-builder' ), __( 'weeks', 'arforms-form-builder' ) ),
			array( 60 * 60 * 24, __( 'day', 'arforms-form-builder' ), __( 'days', 'arforms-form-builder' ) ),
			array( 60 * 60, __( 'hour', 'arforms-form-builder' ), __( 'hours', 'arforms-form-builder' ) ),
			array( 60, __( 'minute', 'arforms-form-builder' ), __( 'minutes', 'arforms-form-builder' ) ),
			array( 1, __( 'second', 'arforms-form-builder' ), __( 'seconds', 'arforms-form-builder' ) ),
		);

		$diff = (int) ( $to - $from );

		if ( 0 > $diff ) {
			return '';
		}

		for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {

			$seconds = $chunks[ $i ][0];

			if ( ( $count = floor( $diff / $seconds ) ) != 0 ) {
				break;
			}
		}

		$output = ( 1 == $count ) ? '1 ' . $chunks[ $i ][1] : $count . ' ' . $chunks[ $i ][2];

		if ( ! (int) trim( $output ) ) {
			$output = '0 ' . __( 'seconds', 'arforms-form-builder' );
		}

		return $output;
	}

	function arfliteupload_dir( $uploads ) {

		$relative_path = apply_filters( 'arfliteuploadfolder', 'arforms-form-builder/userfiles' );

		$relative_path = untrailingslashit( $relative_path );

		if ( ! empty( $relative_path ) ) {

			$uploads['path'] = $uploads['basedir'] . '/' . $relative_path;

			$uploads['url'] = $uploads['baseurl'] . '/' . $relative_path;

			$uploads['subdir'] = '/' . $relative_path;
		}

		return $uploads;
	}

	function arflite_get_param( $param, $default = '', $src = 'get' ) {

		if ( strpos( $param, '[' ) ) {

			$params = explode( '[', $param );

			$param = $params[0];
		}

		$str = '';
		if ( isset( $_POST ) && ! empty( $_POST ) ) { //phpcs:ignore
			$_POST['filtered_form'] = isset( $_POST['filtered_form'] ) ? sanitize_text_field( $_POST['filtered_form'] ) : ''; //phpcs:ignore
			$str                    = isset( $_POST['filtered_form'] ) ? stripslashes_deep( sanitize_text_field( $_POST['filtered_form'] ) ) : ''; //phpcs:ignore
			$str                    = json_decode( $str, true );
		}

		if ( $src == 'get' ) {

			$value = ( isset( $_POST[ $param ] ) ? stripslashes_deep( sanitize_text_field( $_POST[ $param ] ) ) : ( isset( $str[ $param ] ) ? stripslashes_deep( $str[ $param ] ) : ( isset( $_GET[ $param ] ) ? stripslashes_deep( sanitize_text_field( $_GET[ $param ] ) ) : $default ) ) ); //phpcs:ignore

			if ( ( ! isset( $_POST[ $param ] ) || ! isset( $str[ $param ] ) ) && isset( $_GET[ $param ] ) && ! is_array( $value ) ) { //phpcs:ignore
				$value = urldecode( $value );
			}
		} else {

			$value = ( isset( $_POST[ $param ] ) ? stripslashes_deep( maybe_unserialize( sanitize_text_field( $_POST[ $param ] ) ) ) : isset( $str[ $param ] ) ) ? stripslashes_deep( maybe_unserialize( $str[ $param ] ) ) : $default; //phpcs:ignore
		}

		if ( isset( $params ) && is_array( $value ) && ! empty( $value ) ) {

			foreach ( $params as $k => $p ) {

				if ( ! $k || ! is_array( $value ) ) {
					continue;
				}

				$p = trim( $p, ']' );

				$value = ( isset( $value[ $p ] ) ) ? $value[ $p ] : $default;
			}
		}

		return $value;
	}

	function arflite_frm_capabilities() {

		$cap = array(
			'arfviewforms'      => __( 'View Forms and Templates', 'arforms-form-builder' ),
			'arfeditforms'      => __( 'Add/Edit Forms and Templates', 'arforms-form-builder' ),
			'arfdeleteforms'    => __( 'Delete Forms and Templates', 'arforms-form-builder' ),
			'arfchangesettings' => __( 'Access this Settings Page', 'arforms-form-builder' ),
			'arfimportexport'   => __( 'Access this Settings Page', 'arforms-form-builder' ),
		);

		$cap['arfviewentries'] = __( 'View Entries from Admin Area', 'arforms-form-builder' );

		$cap['arfcreateentries'] = __( 'Add Entries from Admin Area', 'arforms-form-builder' );

		$cap['arfdeleteentries'] = __( 'Delete Entries from Admin Area', 'arforms-form-builder' );

		$cap['arfeditdisplays'] = __( 'Add/Edit Custom Displays', 'arforms-form-builder' );

		return $cap;
	}

	function arflite_get_post_param( $param, $default = '' ) {

		return isset( $_POST[ $param ] ) ? stripslashes_deep( maybe_unserialize( sanitize_text_field( $_POST[ $param ] ) ) ) : $default; //phpcs:ignore
	}

	function arflite_load_scripts( $scripts ) {
		global $wp_version;
		foreach ( (array) $scripts as $s ) {
			wp_enqueue_script( $s ); //phpcs:ignore
		}
	}

	function arflite_load_styles( $styles, $print_style = false ) {
		global $wp_version;
		$print_style_func = (true == $print_style ) ? 'wp_print_styles' : 'wp_enqueue_style';
		foreach ( (array) $styles as $s ) {
			$print_style_func( $s );
		}
	}

	function arflite_generate_captcha_code( $length ) {
		$charLength = round( $length * 0.8 );
		$numLength  = round( $length * 0.2 );
		$keywords   = array(
			array(
				'count' => $charLength,
				'char'  => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			),
			array(
				'count' => $numLength,
				'char'  => '0123456789',
			),
		);
		$temp_array = array();
		foreach ( $keywords as $char_set ) {
			for ( $i = 0; $i < $char_set['count']; $i++ ) {
				$temp_array[] = $char_set['char'][ rand( 0, strlen( $char_set['char'] ) - 1 ) ];
			}
		}
		shuffle( $temp_array );
		return implode( '', $temp_array );
	}

	function arflite_update_fa_font_class( $value ) {
		$fa_font_arr = array();
		if ( file_exists( ARFLITE_VIEWS_PATH . '/arforms_font_awesome_array.php' ) ) {
			include_once ARFLITE_VIEWS_PATH . '/arforms_font_awesome_array.php';
			$fa_font_arr = arformslite_font_awesome_font_array();
		}

		foreach ( $fa_font_arr as $k => $val ) {
			if ( $value == $k ) {
				$value = $val['style'] . ' ' . $val['code'];
			}
		}

		return $value;
	}
}
