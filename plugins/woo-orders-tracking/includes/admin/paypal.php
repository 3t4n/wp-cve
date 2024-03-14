<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ORDERS_TRACKING_ADMIN_PAYPAL {
	/**
	 * @return array
	 */
	public static function carriers() {
		$carriers = array(
			//GLOBAL
			'ARAMEX'                    => 'Aramex',
			'B_TWO_C_EUROPE'            => 'B2C Europe',
			'CJ_LOGISTICS'              => 'CJ Logistics',
			'CORREOS_EXPRESS'           => 'Correos Express',
			'DHL_ACTIVE_TRACING'        => 'DHL Active Tracing',
			'DHL_BENELUX'               => 'DHL Benelux',
			'DHL_GLOBAL_MAIL'           => 'DHL ecCommerce US',
			'DHL_GLOBAL_MAIL_ASIA'      => 'DHL eCommerce Asia',
			'DHL'                       => 'DHL Express',
			'DHL_GLOBAL_ECOMMERCE'      => 'DHL Global eCommerce',
			'DHL_PACKET'                => 'DHL Packet',
			'DPD'                       => 'DPD Global',
			'DPD_LOCAL'                 => 'DPD Local',
			'DPD_LOCAL_REF'             => 'DPD Local Reference',
			'DPE_EXPRESS'               => 'DPE Express',
			'DPEX'                      => 'DPEX Hong Kong',
			'DTDC_EXPRESS'              => 'DTDC Express Global',
			'ESHOPWORLD'                => 'EShopWorld',
			'FEDEX'                     => 'FedEx',
			'FLYT_EXPRESS'              => 'FLYT Express',
			'GLS'                       => 'GLS',
			'IMX'                       => 'IMX France',
			'INT_SUER'                  => 'International SEUR',
			'LANDMARK_GLOBAL'           => 'Landmark Global',
			'MATKAHUOLTO'               => 'Matkahuoloto',
			'OMNIPARCEL'                => 'Omni Parcel',
			'ONE_WORLD'                 => 'One World',
			'OTHER'                     => 'Other',
			'POSTI'                     => 'Posti',
			'RABEN_GROUP'               => 'Raben Group',
			'SF_EXPRESS'                => 'SF EXPRESS',
			'SKYNET_Worldwide'          => 'SkyNet Worldwide Express',
			'SPREADEL'                  => 'Spreadel',
			'TNT'                       => 'TNT Global',
			'tNT'                       => 'TNT',
			'UPS'                       => 'UPS',
			'UPS_MI'                    => 'UPS Mail Innovations',
			'WEBINTERPRET'              => 'WebInterpret',

			//AG
			'CORREOS_AG'                => 'Correos Antigua and Barbuda',

			//AR
			'EMIRATES_POST'             => 'Emirates Post',
			'OCA_AR'                    => 'OCA Argentina',

			//AU
			'ADSONE'                    => 'Adsone',
			'AUSTRALIA_POST'            => 'Australia Post',
			'TOLL_AU'                   => 'Australia Toll',
			'tOLL_AU'                   => 'MyToll Australia',
			'BONDS_COURIERS'            => 'Bonds Couriers',
			'COURIERS_PLEASE'           => 'Couriers Please',
			'DHL_AU'                    => 'DHL Australia',
			'DTDC_AU'                   => 'DTDC Australia',
			'FASTWAY_AU'                => 'Fastway Australia',
			'HUNTER_EXPRESS'            => 'Hunter Express',
			'SENDLE'                    => 'Sendle',
			'STARTRACK'                 => 'Star Track',
			'sTARTRACK'                 => 'StarTrack',
			'STARTRACK_EXPRESS'         => 'Star Track Express',
			'TNT_AU'                    => 'TNT Australia',
			'TOLL'                      => 'Toll',
			'UBI_LOGISTICS'             => 'UBI Logistics',

			//AT
			'AUSTRIAN_POST_EXPRESS'     => 'Austrian Post Express',
			'AUSTRIAN_POST'             => 'Austrian Post Registered',
			'aUSTRIAN_POST'             => 'Austrian Post',
			'DHL_AT'                    => 'DHL Austria',

			//BE
			'BPOST'                     => 'bpost',
			'BPOST_INT'                 => 'bpost International',
			'MONDIAL_BE'                => 'Mondial Belgium',
			'TAXIPOST'                  => 'TaxiPost',

			//BR
			'CORREOS_BR'                => 'Correos Brazil',
			'cORREOS_BR'                => 'Brazil Correios',
			'DIRECTLOG_BR'              => 'Directlog',

			//BG
			'BULGARIAN_POST'            => 'Bulgarian Post',

			//CA
			'CANADA_POST'               => 'Canada Post',
			'CANPAR'                    => 'Canpar',
			'cANPAR'                    => 'Canpar Courier',
			'GREYHOUND'                 => 'Greyhound',
			'LOOMIS'                    => 'Loomis',
			'PUROLATOR'                 => 'Purolator',

			//CL
			'CORREOS_CL'                => 'Correos Chile',

			//CN
			'FOUR_PX_EXPRESS'           => '4PX Express',
			'fOUR_PX_EXPRESS'           => '4PX',
			'AUPOST_CN'                 => 'AUPOST CHINA',
			'BQC_EXPRESS'               => 'BQC Express',
			'BUYLOGIC'                  => 'Buylogic',
			'CHINA_POST'                => 'China Post',
			'CNEXPS'                    => 'CN Exps',
			'EC_CN'                     => 'EC China',
			'EFS'                       => 'EFS',
			'EMPS_CN'                   => 'EMPS China',
			'EMS_CN'                    => 'EMS China',
			'eMS_CN'                    => 'China EMS( ePacket )',
			'HUAHAN_EXPRESS'            => 'Huahan Express',
			'SFC_EXPRESS'               => 'SFC Express',
			'TNT_CN'                    => 'TNT China',
			'WINIT'                     => 'WinIt',
			'YANWEN_CN'                 => 'Yanwen',

			//CR
			'CORREOS_CR'                => 'Correos De Costa Rica',

			//HR
			'HRVATSKA_HR'               => 'Hrvatska',

			//CY
			'CYPRUS_POST_CYP'           => 'Cyprus Post',

			//CZ
			'CESKA_CZ'                  => 'Ceska',
			'GLS_CZ'                    => 'GLS Czech Republic',

			//FR
			'BERT'                      => 'BERT TRANSPORT',
			'CHRONOPOST_FR'             => 'Chronopost France',
			'COLIPOSTE'                 => 'Coliposte',
			'COLIS'                     => 'Colis France',
			'cOLIS'                     => 'Colis Privé',
			'DHL_FR'                    => 'DHL France',
			'DPD_FR'                    => 'DPD France',
			'GEODIS'                    => 'GEODIS - Distribution & Express',
			'GLS_FR'                    => 'GLS France',
			'LAPOSTE'                   => 'LA Poste',
			'MONDIAL'                   => 'Mondial Relay',
			'RELAIS_COLIS_FR'           => 'Relais Colis',
			'TELIWAY'                   => 'Teliway',
			'TNT_FR'                    => 'TNT France',

			//DE
			'ASENDIA_DE'                => 'Asendia Germany',
			'DELTEC_DE'                 => 'Deltec Germany',
			'DEUTSCHE_DE'               => 'Deutsche',
			'DHL_DEUTSCHE_POST'         => 'DHL Deutsche Post',
			'dHL_DEUTSCHE_POST'         => 'Deutsche Post DHL',
			'DPD_DE'                    => 'DPD Germany',
			'GLS_DE'                    => 'GLS Germany',
			'HERMES_DE'                 => 'Hermes Germany',
			'TNT_DE'                    => 'TNT Germany',

			//GR
			'ELTA_GR'                   => 'ELTA Greece',
			'GENIKI_GR'                 => 'Geniki Greece',
			'ACS_GR'                    => 'GRC Greece',

			//HK
			'ASENDIA_HK'                => 'Asendia Hong Kong',
			'DHL_HK'                    => 'DHL Hong Kong',
			'DPD_HK'                    => 'DPD Hong Kong',
			'HK_POST'                   => 'Hong Kong Post',
			'KERRY_EXPRESS_HK'          => 'Kerry Express Hong Kong',
			'LOGISTICSWORLDWIDE_HK'     => 'Logistics Worldwide Hong Kong',
			'QUANTIUM'                  => 'Quantium',
			'SEKOLOGISTICS'             => 'Seko Logistics',
			'TAQBIN_HK'                 => 'TA-Q-BIN Parcel Hong Kong',

			//HU
			'MAGYAR_HU'                 => 'Magyar',

			//IS
			'POSTUR_IS'                 => 'Postur',

			//IN
			'BLUEDART'                  => 'Bluedart',
			'DELHIVERY_IN'              => 'Delhivery',
			'DOTZOT'                    => 'DotZot',
			'DTDC_IN'                   => 'DTDC India',
			'dTDC_IN'                   => 'DTDC IN',
			'EKART'                     => 'Ekart',
			'INDIA_POST'                => 'India Post',
			'PROFESSIONAL_COURIERS'     => 'Professional Couriers',
			'REDEXPRESS'                => 'Red Express',
			'SWIFTAIR'                  => 'Swift Air',
			'XPRESSBEES'                => 'Xpress Bees',

			//ID
			'FIRST_LOGISITCS'           => 'First Logistics',
			'JNE_IDN'                   => 'JNE Indonesia',
			'LION_PARCEL'               => 'Lion Parcel',
			'NINJAVAN_ID'               => 'Ninjavan Indonesia',
			'PANDU'                     => 'Pandu Logistics',
			'POS_ID'                    => 'Pos Indonesia Domestic',
			'POS_INT'                   => 'Pos Indonesia International',
			'RPX_ID'                    => 'RPX Indonesia',
			'RPX'                       => 'RPX International',
			'TIKI_ID'                   => 'Tiki',
			'WAHANA_ID'                 => 'Wahana',

			//IE
			'AN_POST'                   => 'AN POST Ireland',
			'DPD_IR'                    => 'DPD Ireland',
			'MASTERLINK'                => 'Masterlink',
			'TPG'                       => 'TPG',
			'WISELOADS'                 => 'Wiseloads',

			//IL
			'ISRAEL_POST'               => 'Israel Post',

			//IT
			'BRT_IT'                    => 'BRT Bartolini',
			'DHL_IT	'                => 'DHL Italy',
			'DMM_NETWORK'               => 'DMM Network',
			'FERCAM_IT'                 => 'FERCAM Logistics & Transport',
			'GLS_IT'                    => 'GLS Italy',
			'gLS_IT'                    => 'GLS IT',
			'HERMES_IT'                 => 'Hermes Italy',
			'POSTE_ITALIANE'            => 'Poste Italiane',
			'REGISTER_MAIL_IT'          => 'Register Mail IT',
			'SDA_IT'                    => 'SDA Italy',
			'SGT_IT'                    => 'SGT Corriere Espresso',
			'TNT_CLICK_IT'              => 'TNT Click Italy',
			'TNT_IT'                    => 'TNT Italy',
			'tNT_IT'                    => 'TNT IT',

			//JP
			'DHL_JP'                    => 'DHL Japan',
			'JP_POST'                   => 'Japan Post',
			'JAPAN_POST'                => 'Japan Post',
			'POCZTEX'                   => 'Pocztex',
			'SAGAWA'                    => 'Sagawa',
			'SAGAWA_JP'                 => 'Sagawa',
			'TNT_JP'                    => 'TNT Japan',
			'YAMATO'                    => 'Yamato Japan',

			//KR
			'ECARGO'                    => 'Ecargo',
			'EPARCEL_KR'                => 'eParcel Korea',
			'KOREA_POST'                => 'Korea Post',
			'KOR_KOREA_POST'            => 'Korea Post',
			'CJ_KR'                     => 'Korea Thai CJ',
			'LOGISTICSWORLDWIDE_KR'     => 'Logistics Worldwide Korea',
			'PANTOS'                    => 'Pantos',
			'RINCOS'                    => 'Rincos',
			'ROCKET_PARCEL'             => 'Rocket Parcel International',
			'SRE_KOREA'                 => 'SRE Korea',

			//LT
			'LIETUVOS_LT'               => 'Lietuvos Pastas',

			//MY
			'AIRPAK_MY'                 => 'Airpak',
			'CITYLINK_MY'               => 'CityLink Malaysia',
			'CJ_MY'                     => 'CJ Malaysia',
			'CJ_INT_MY'                 => 'CJ Malaysia International',
			'CUCKOOEXPRESS'             => 'Cuckoo Express',
			'JETSHIP_MY'                => 'Jet Ship Malaysia',
			'KANGAROO_MY'               => 'Kangaroo Express',
			'LOGISTICSWORLDWIDE_MY'     => 'Logistics Worldwide Malaysia',
			'MALAYSIA_POST'             => 'Malaysia Post EMS / Pos Laju',
			'mALAYSIA_POST'             => 'Malaysia Post',
			'NATIONWIDE'                => 'Nationwide',
			'NINJAVAN_MY'               => 'Ninjavan Malaysia',
			'SKYNET_MY'                 => 'Skynet Malaysia',
			'TAQBIN_MY'                 => 'TA-Q-BIN Parcel Malaysia',

			//MX
			'CORREOS_MX'                => 'Correos De Mexico',
			'ESTAFETA'                  => 'Estafeta',
			'AEROFLASH'                 => 'Mexico Aeroflash',
			'REDPACK'                   => 'Mexico Redpack',
			'SENDA_MX'                  => 'Mexico Senda Express',

			//NL
			'DHL_NL'                    => 'DHL Netherlands',
			'DHL_PARCEL_NL'             => 'DHL Parcel Netherlands',
			'GLS_NL'                    => 'GLS Netherlands',
			'KIALA'                     => 'Kiala',
			'POSTNL'                    => 'PostNL',
			'POSTNL_INT'                => 'PostNl International',
			'POSTNL_INT_3_S'            => 'PostNL International 3S',
			'TNT_NL'                    => 'TNT Netherlands',
			'TRANSMISSION'              => 'Transmission Netherlands',

			//NZ
			'COURIER_POST'              => 'Courier Post',
			'FASTWAY_NZ'                => 'Fastway New Zealand',
			'fASTWAY_NZ'                => 'Fastway NZ',
			'NZ_POST'                   => 'New Zealand Post',
			'TOLL_IPEC'                 => 'Toll IPEC',

			//NG
			'COURIERPLUS'               => 'Courier Plus',
			'NIPOST_NG'                 => 'NiPost',

			//NO
			'POSTEN_NORGE'              => 'Posten Norge',

			//PH
			'TWO_GO'                    => '2GO',
			'AIR_21'                    => 'Air 21',
			'AIRSPEED'                  => 'Airspeed',
			'JAMEXPRESS_PH'             => 'Jam Express',
			'LBC_PH'                    => 'LBC Express',
			'NINJAVAN_PH'               => 'Ninjavan Philippines',
			'RAF_PH'                    => 'RAF Philippines',
			'XEND_EXPRESS_PH'           => 'Xend Express',

			//PL
			'DHL_PL'                    => 'DHL Poland',
			'DPD_PL'                    => 'DPD Poland',
			'INPOST_PACZKOMATY'         => 'InPost Paczkomaty',
			'POCZTA_POLSKA'             => 'Poczta Polska',
			'SIODEMKA'                  => 'Siodemka',
			'TNT_PL'                    => 'TNT Poland',

			//PT
			'ADICIONAL_PT'              => 'Adicional Logistics',
			'CHRONOPOST_PT'             => 'Chronopost Portugal',
			'CTT_PT'                    => 'Portugal PTT',
			'cTT_PT'                    => 'Portugal Post - CTT',
			'SEUR_PT'                   => 'Portugal Seur',

			//RO
			'DPD_RO'                    => 'DPD Romania',
			'dPD_RO'                    => 'DPD RO',
			'POSTA_RO'                  => 'Postaromana',

			//RU
			'DPD_RU'                    => 'DPD Russia',
			'RUSSIAN_POST'              => 'Russian Post',

			//SA
			'DAWN_WING'                 => 'Dawn Wing',
			'RAM'                       => 'Ram',
			'THE_COURIER_GUY'           => 'The Courier Guy',

			//CS
			'POST_SERBIA_CS'            => 'Serbia Post',

			//SG
			'DHL_SG'                    => 'DHL Singapore',
			'JETSHIP_SG'                => 'JetShip Singapore',
			'NINJAVAN_SG'               => 'Ninjavan Singapore',
			'PARCELPOST_SG'             => 'Parcel Post',
			'SINGPOST'                  => 'Singapore Post',
			'TAQBIN_SG'                 => 'TA-Q-BIN Parcel Singapore',

			//ZA
			'FASTWAY_ZA'                => 'Fastway South Africa',

			//ES
			'ASM_ES'                    => 'ASM',
			'CBL_LOGISTICA'             => 'CBL Logistics',
			'CORREOS_ES'                => 'Correos De Spain',
			'DHL_PARCEL_ES'             => 'DHL Parcel Spain',
			'GLS_ES'                    => 'GLS Spain',
			'INT_SEUR'                  => 'International Suer',
			'ITIS'                      => 'ITIS',
			'NACEX_ES'                  => 'Nacex Spain',
			'REDUR_ES'                  => 'Redur Spain',
			'SEUR_ES'                   => 'Spanish Seur',
			'TNT_ES'                    => 'TNT Spain',

			//SE
			'DBSCHENKER_SE'             => 'DB Schenker Sweden',
			'DIRECTLINK_SE'             => 'DirectLink Sweden',
			'POSTNORD_LOGISTICS_GLOBAL' => 'PostNord Logistics',
			'POSTNORD_LOGISTICS_DK'     => 'PostNord Logistics Denmark',
			'POSTNORD_LOGISTICS_SE'     => 'PostNord Logistics Sweden',

			//CH
			'SWISS_POST'                => 'Swiss Post',

			//TW
			'CHUNGHWA_POST'             => 'Chunghwa Post',
			'TAIWAN_POST_TW'            => 'Taiwan Post',

			//TH
			'ACOMMMERCE'                => 'Acommerce',
			'ALPHAFAST'                 => 'Alphafast',
			'CJ_TH'                     => 'CJ Thailand',
			'FASTRACK'                  => 'FastTrack Thailand',
			'KERRY_EXPRESS_TH'          => 'Kerry Express Thailand',
			'NIM_EXPRESS'               => 'NIM Express',
			'NINJAVAN_THAI'             => 'Ninjavan Thailand',
			'nINJAVAN_THAI'             => 'Ninja Van',
			'SENDIT'                    => 'SendIt',
			'THAILAND_POST'             => 'Thailand Post',

			//TR
			'PTT_POST'                  => 'PTT Posta',

			//UA
			'NOVA_POSHTA'               => 'Nova Poshta',
			'NOVA_POSHTA_INT'           => 'Nova Poshta International',

			//AE
			'AXL'                       => 'AXL Express & Logistics',
			'CONTINENTAL'               => 'Continental',
			'SKYNET_UAE'                => 'Skynet Worldwide Express UAE',

			//UK
			'AIRBORNE_EXPRESS_UK'       => 'Airborne Express UK',
			'AIRSURE'                   => 'Airsure',
			'APC_OVERNIGHT'             => 'APC Overnight',
			'ASENDIA_UK'                => 'Asendia UK',
			'COLLECTPLUS'               => 'CollectPlus',
			'DELTEC_UK'                 => 'Deltec UK',
			'DHL_UK'                    => 'DHL UK',
			'DPD_DELISTRACK'            => 'DPD Delistrack',
			'DPD_UK'                    => 'DPD UK',
			'FASTWAY_UK'                => 'Fastway UK',
			'HERMESWORLD_UK'            => 'HermesWorld',
			'INTERLINK'                 => 'Interlink Express',
			'MYHERMES'                  => 'MyHermes UK',
			'NIGHTLINE_UK'              => 'Nightline UK',
			'PARCELFORCE'               => 'Parcel Force',
			'pARCELFORCE'               => 'Parcelforce UK',
			'ROYAL_MAIL'                => 'Royal Mail',
			'RPD_2_MAN'                 => 'RPD2man Deliveries',
			'SKYNET_UK'                 => 'Skynet Worldwide Express UK',
			'TNT_UK'                    => 'TNT UK',
			'UK_MAIL'                   => 'UK Mail',
			'YODEL'                     => 'Yodel',

			//US
			'ABC_PACKAGE'               => 'ABC Package Express',
			'AIRBORNE_EXPRESS'          => 'Airborne Express',
			'ASENDIA_US'                => 'Asendia USA',
			'CPACKET'                   => 'Cpacket',
			'ENSENDA'                   => 'Ensenda USA',
			'ESTES'                     => 'Estes',
			'FASTWAY_US'                => 'Fastway USA',
			'GLOBEGISTICS'              => 'Globegistics USA',
			'INTERNATIONAL_BRIDGE'      => 'International Bridge',
			'ONTRAC'                    => 'OnTrac',
			'RL_US'                     => 'RL Carriers',
			'RRDONNELLEY'               => 'RR Donnelley',
			'USPS'                      => 'USPS',

			//VN
			'KERRY_EXPRESS_VN'          => 'Kerry Express Vietnam',
			'VIETNAM_POST'              => 'Vietnam Post',
			'VNPOST_EMS'                => 'Vietnam Post EMS',
		);

		return $carriers;
	}

	/**
	 * @param bool $sandbox
	 *
	 * @return string
	 */
	public static function get_request_url( $sandbox = false ) {

		return $sandbox ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';
	}

	/**
	 * @param $name
	 *
	 * @return bool|false|int|mixed|string|string[]|null
	 */
	public static function get_carrier( $name ) {
		$name     = strtolower( trim( $name ) );
		$carriers = array_map( 'strtolower', self::carriers() );
		$search   = VI_WOO_ORDERS_TRACKING_DATA::array_search_case_insensitive( $name, $carriers );
		if ( $search === false ) {
			$search = 'OTHER';
		} else {
			$search = strtoupper( $search );
		}

		return $search;
	}

	/**
	 * @param $client
	 * @param $secret
	 * @param $sandbox
	 * @param bool $new_token
	 *
	 * @return array
	 */
	public static function get_access_token( $client, $secret, $sandbox, $new_token = false ) {
		$transient_token_name = "woo_orders_tracking_{$client}";
		$transient_token      = get_transient( $transient_token_name );
		if ( ! $new_token && $transient_token ) {
			$token = array( 'status' => 'success', 'data' => $transient_token );
		} else {
			$ch  = curl_init();
			$url = self::get_request_url( $sandbox );
			$url .= '/v1/oauth2/token';
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_SSLVERSION, 6 ); //NEW ADDITION
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_USERPWD, $client . ":" . $secret );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials" );

			$result = curl_exec( $ch );
			self::debug_log( var_export( $result, true ) );
			if ( empty( $result ) ) {
				$token = array(
					'status' => 'error',
					'data'   => esc_html__( 'Your client ID or client secret is wrong', 'woo-orders-tracking' )
				);
			} else {
				$json = vi_wot_json_decode( $result );
				if ( ! empty( $json['access_token'] ) ) {
					$token = array(
						'status' => 'success',
						'data'   => $json['access_token']
					);
					if ( ! empty( $json['expires_in'] ) ) {
						set_transient( $transient_token_name, $json['access_token'], $json['expires_in'] );
					}
				} else {
					$token = array(
						'status' => 'error',
						'data'   => esc_html__( 'Your client ID or client secret is wrong', 'woo-orders-tracking' ),
					);
				}
			}
			curl_close( $ch );
		}

		return $token;
	}

	/**
	 * @param $data
	 * @param $token
	 * @param $url
	 *
	 * @return array
	 */
	private static function add_trackinfo( $data, $token, $url ) {
		$return      = array(
			'status' => 'success',
			'data'   => '',
			'errors' => '',
			'code'   => '',
		);
		$body_params = array();
		foreach ( $data as $item ) {
			$tracking_data = array(
				'transaction_id' => $item['trans_id'],
				'status'         => 'SHIPPED',
			);
			if ( ! empty( $item['tracking_number'] ) ) {
				$carrier                          = self::get_carrier( $item['carrier_name'] );
				$tracking_data['tracking_number'] = $item['tracking_number'];
				$tracking_data['carrier']         = $carrier;
				if ( $carrier === 'OTHER' ) {
					$tracking_data['carrier_name_other'] = $item['carrier_name'];
				}
			}
			$body_params[] = $tracking_data;
		}

		$params = array( 'trackers' => $body_params );
		$url    .= '/v1/shipping/trackers-batch';
		$arg    = array(
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			),
			'body'    => vi_wot_json_encode( $params )
		);

		$request        = VI_WOO_ORDERS_TRACKING_DATA::wp_remote_post( $url, $arg );
		$return['code'] = $request['code'];
		if ( $request['status'] === 'success' ) {
			$return['status'] = 'success';
			$body             = vi_wot_json_decode( $request['data'] );
			if ( ! empty( $body['error'] ) ) {
				self::debug_log( var_export( $request, true ) );
				if ( isset( $body['error_description'] ) ) {
					$return['data'] = $body['error_description'];
				}
				if ( $body['error'] === 'invalid_token' ) {
					$return['status'] = 'invalid_token';
				} else {
					$return['status'] = 'error';
				}
			} elseif ( is_array( $body['errors'] ) && count( $body['errors'] ) ) {
				self::debug_log( var_export( $request, true ) );
				$return['status'] = 'error';
				$return['errors'] = $body['errors'];
				if ( isset( $body['errors'][0]['message'] ) ) {
					$return['data'] = $body['errors'][0]['message'];
				}
			}
		} else {
			self::debug_log( var_export( $request, true ) );
			$return['status'] = 'error';
			$return['data']   = $request['data'];
		}

		return $return;
	}

	/**
	 * @param $method_id
	 *
	 * @return array
	 */
	public static function get_api_credentials( $method_id ) {
		$wot           = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		$paypal_method = $wot->get_params( 'paypal_method' );
		$search        = array_search( $method_id, $paypal_method );
		$credentials   = array(
			'id'      => '',
			'secret'  => '',
			'sandbox' => true,
		);
		if ( $search !== false ) {
			$paypal_sandbox_enable = $wot->get_params( 'paypal_sandbox_enable' );
			if ( $paypal_sandbox_enable[ $search ] ) {
				$client_id = $wot->get_params( 'paypal_client_id_sandbox' );
				$secret    = $wot->get_params( 'paypal_secret_sandbox' );
			} else {
				$client_id              = $wot->get_params( 'paypal_client_id_live' );
				$secret                 = $wot->get_params( 'paypal_secret_live' );
				$credentials['sandbox'] = false;
			}
			$credentials['id']     = $client_id[ $search ];
			$credentials['secret'] = $secret[ $search ];
		}

		return $credentials;
	}

	/**
	 * @return mixed|void
	 */
	public static function get_supported_paypal_gateways() {
		return apply_filters( 'vi_wot_supported_paypal_gateways', array(
			'paypal',
			'ppec_paypal',
		) );
	}

	/**
	 * @param $client_id
	 * @param $secret
	 * @param $data
	 * @param $sandbox
	 *
	 * @return array
	 */
	public static function add_tracking_number( $client_id, $secret, $data, $sandbox ) {
		$token = self::get_access_token( $client_id, $secret, $sandbox );
		if ( $token['status'] === 'success' ) {
			$result = self::add_trackinfo( $data, $token['data'], self::get_request_url( $sandbox ) );
			if ( $result['status'] === 'invalid_token' ) {
				$token = self::get_access_token( $client_id, $secret, $sandbox, true );
				if ( $token['status'] === 'success' ) {
					$result = self::add_trackinfo( $data, $token['data'], self::get_request_url( $sandbox ) );

					return $result;
				} else {
					return $token;
				}
			} else {
				return $result;
			}
		} else {
			return $token;
		}
	}

	private static function wc_log( $content, $source = 'debug', $level = 'debug' ) {
		$content = strip_tags( $content );
		$log     = wc_get_logger();
		$log->log( $level,
			$content,
			array(
				'source' => 'woo-orders-tracking-paypal-' . $source,
			)
		);
	}

	private static function debug_log( $content ) {
		$instance = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		if ( $instance->get_params( 'paypal_debug' ) ) {
			self::wc_log( $content );
		}
	}
}