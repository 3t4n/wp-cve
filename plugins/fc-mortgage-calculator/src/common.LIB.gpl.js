/**
 * -----------------------------------------------------------------------------
 * (c) 2005-2020 Pine Grove Software, LLC -- All rights reserved.
 * Contact: webmaster@AccurateCalculators.com
 * License: GPL2
 * www.AccurateCalculators.com
 * -----------------------------------------------------------------------------
 * Common code and global variables.
 * -----------------------------------------------------------------------------
 * compiled at: http://closure-compiler.appspot.com/home
 */

/* required for JSLint processing */
/*global Cookies: false */
/*jslint nomen: true, vars: true, sub: true */


/** 
 * @preserve Copyright 2020 Pine Grove Software, LLC
 * AccurateCalculators.com
 * common.LIB.gpl.js
 */

// eslint-disable-next-line no-unused-vars
var LIB$; 

/** 
 * @nocollapse
 * @const
 * @return {Object}
 */
LIB$ = (function () {
	'use strict';

	// object wrappers to expose shared constants, variables & routines
	/** @const */
	var FC = {};

	/**
	 * Error messages for numeric text input
	 */
	FC.ERR_MSGS = {
		noDelKey: 'Please use the backspace key to delete.',
		noCurKeys: 
			'Left, up & down arrow keys are disabled. So are the home, end, pgup and pgdn keys.\n\nUse backspace to delete.\n\nIf value is selected, just start typing new value to clear prior value.\n\nWhen a number is selected (value shown in inverse), use right arrow key to clear selection without clearing value. Then backspace to edit.\n\nTIP: Generally it is best to use the TAB or SHIFT-TAB keys to move from one input to the next or previous input.\n\nTIP 2: Generally, editing a value is inefficient. Since values are auto selected, just type the number you want.',
		noSeparators: 
			'Do not type the thousand separator character.\n\n(If using US convention, that would be the comma.)\n\nI\'m smart enough to enter them for you!'
	};

	// error strings
	var esInvalidDateMath = 'An unknown date calculation error occurred.\nPlease provide us with your inputs and settings so that we can fix this. Thank you.';
	var esInvalidYear = 'Date is not valid - bad year.';


	// errors
	var erInvalidDateMath = new Error(esInvalidDateMath);
	var erInvalidYear = new Error(esInvalidYear);

	/** @const */
	var DEFAULT_SEP = ',';
	/** @const */
	var DEFAULT_DPNT = '.';
	/** @const */
	var DEFAULT_CCY = '';
	/** @const */
	var DEFAULT_CCY_R = '';
	/** @const */
	var DEFAULT_PRECISION = 2;
	/** @const */
	var DEFAULT_DATE_ENUM = 2;
	/** @const */
	var MAX_NO_CCY_FORMATS = 93;
	/** @const */
	var MAX_DATE_ENUM = 6;
	/** @const */
	var INDIAN_RUPEE_INDEX = 83; // HIIN:83, //India INR, required to handle formatting


	/**
	 * A calculator's zoom size - used for CSS scaling
	 * @type {number}
	 * @nocollapse
	 */
	FC.zoomLevel = 1;

	/**
	 * @enum {number}
	 * @nocollapse
	 */
	FC.AMORT_MTHD = {
		AM_NORMAL: 0
	};
	/**
	 * Currency formats
	 */
	FC.CCY_FORMATS = {
		/** @const */
		USD1: 0,
		/** @const */
		USD2: 1,
		/** @const */
		GBH: 2,
		/** @const */
		NON: 3,
		/** @const */
		EUR1: 4,
		/** @const */
		EUR2: 5,
		/** @const */
		EUR3: 6,
		/** @const */
		EUR4: 7,
		/** @const */
		RND: 8, // [KT] 08/08/2017 - South African Rand
		/** @const */
		NOK: 9, // [KT] 09/24/2017 - Norwegian krone, kr
		/** @const */
		NGN: 10, // [KT] 02/11/2018 - Nigerian naira
		/** @const */
		THB: 11, // [KT] 03/31/2018 - Thai Baht
		/** @const */
		DEFAULT: 12,
		AFZA: 62, //South Africa ZAR
		ENZA: 62, //South Africa ZAR
		AZAZ: 84, //Azerbaijan AZN
		ENAZ: 84, //Azerbaijan AZN
		BEBY: 54, //Belarus BYN
		ENBY: 54, //Belarus BYN
		BGBG: 27, //Bulgaria BGN
		ENBG: 27, //Bulgaria BGN
		CSCZ: 15, //Czechia CZK
		ENCZ: 15, //Czechia CZK
		DADK: 30, //Denmark DKK
		DA: 30, //Denmark DKK
		ENDK: 30, //Denmark DKK
		DEAT: 43, //Austria EUR
		ENAT: 43, //Austria EUR
		DECH: 103, //Switzerland CHF
		ENCH: 103, //Switzerland CHF
		DEDE: 34, //Germany EUR
		DE: 34, //Germany EUR
		ENDE: 34, //Germany EUR
		DELI: 103, //Liechtenstein CHF
		ENLI: 103, //Liechtenstein CHF
		DELU: 34, //Luxembourg EUR
		ENLU: 34, //Luxembourg EUR
		DVMV: 99, //Maldives MVR
		ELGR: 33, //Greece EUR
		EL: 33, //Greece EUR
		ENGR: 33, //Greece EUR
		ENAE: 87, //United Arab Emirates (the) AED
		ARAE: 87, //United Arab Emirates (the) AED
		ENAU: 49, //Australia AUD
		ENBH: 89, //Bahrain BHD
		ARBH: 89, //Bahrain BHD
		ENBZ: 53, //Belize BZD
		ENCA: 50, //Canada CAD
		ENDZ: 90, //Algeria DZD
		ARDZ: 90, //Algeria DZD
		ENEG: 70, //Egypt EGP
		AREG: 70, //Egypt EGP
		ENGB: 71, //United Kingdom of Great Britain and Northern Ireland (the) GBP
		ENIE: 80, //Ireland EUR
		ENIQ: 92, //Iraq IQD
		ARIQ: 92, //Iraq IQD
		ENJM: 57, //Jamaica JMD
		ENJO: 93, //Jordan JOD
		ARJO: 93, //Jordan JOD
		ENKW: 95, //Kuwait KWD
		ARKW: 95, //Kuwait KWD
		ENLB: 70, //Lebanon LBP
		ARLB: 70, //Lebanon LBP
		ENLY: 96, //Libya LYD
		ARLY: 96, //Libya LYD
		ENMA: 97, //Morocco MAD
		ARMA: 97, //Morocco MAD
		ENNZ: 49, //New Zealand NZD
		ENOM: 86, //Oman OMR
		AROM: 86, //Oman OMR
		ENPH: 82, //Philippines (the) PHP
		ENQA: 85, //Qatar QAR
		ARQA: 85, //Qatar QAR
		ENSA: 85, //Saudi Arabia SAR
		AR: 85, //Saudi Arabia SAR
		ARSA: 85, //Saudi Arabia SAR
		ENSY: 69, //Syrian Arab Republic (the)
		ARSY: 69, //Syrian Arab Republic (the)
		ENTN: 100, //Tunisia TND
		ARTN: 100, //Tunisia TND
		ENTT: 66, //Trinidad and Tobago TTD
		ENUS: 48, //United States of America (the) USD
		EN: 48, //United States of America (the) USD
		ENYE: 85, //Yemen YER
		ARYE: 85, //Yemen YER
		ENZW: 101, //Zimbabwe ZWL
		ESAR: 36, //Argentina ARS
		ENAR: 36, //Argentina ARS
		ESBO: 38, //Bolivia (Plurinational State of) BOB
		ENBO: 38, //Bolivia (Plurinational State of) BOB
		ESCL: 35, //Chile CLP
		ENCL: 35, //Chile CLP
		ESCO: 36, //Colombia COP
		ENCO: 36, //Colombia COP
		ESCR: 26, //Costa Rica CRC
		ENCR: 26, //Costa Rica CRC
		ESDO: 63, //Dominican Republic (the) DOP
		ENDO: 63, //Dominican Republic (the) DOP
		ESEC: 36, //Ecuador USD
		ENEC: 36, //Ecuador USD
		ESES: 33, //Spain EUR
		ES: 33, //Spain EUR
		ENES: 33, //Spain EUR
		ESGT: 61, //Guatemala GTQ
		ENGT: 61, //Guatemala GTQ
		ESHN: 58, //Honduras HNL
		ENHN: 58, //Honduras HNL
		ESMX: 49, //Mexico MXN
		ENMX: 49, //Mexico MXN
		ESNI: 55, //Nicaragua NIO
		ENNI: 55, //Nicaragua NIO
		ESPA: 52, //Panama PAB
		ENPA: 52, //Panama PAB
		ESPE: 65, //Peru PEN
		ENPE: 65, //Peru PEN
		ESPR: 48, //Puerto Rico USD
		ENPR: 48, //Puerto Rico USD
		ESPY: 39, //Paraguay PYG
		ENPY: 39, //Paraguay PYG
		ESSV: 49, //El Salvador SVC
		ENSV: 49, //El Salvador SVC
		ESUY: 37, //Uruguay UYU
		ENUY: 37, //Uruguay UYU
		ESVE: 46, //Venezuela (Bolivarian Republic of) VES
		ENVE: 46, //Venezuela (Bolivarian Republic of) VES
		ETEE: 20, //Estonia EUR
		ENEE: 20, //Estonia EUR
		FAIR: 85, //Iran (Islamic Republic of) IRR
		FA: 85, //Iran (Islamic Republic of) IRR
		ENIR: 85, //Iran (Islamic Republic of) IRR
		FIFI: 20, //Finland EUR
		FI: 20, //Finland EUR
		ENFI: 20, //Finland EUR
		FOFO: 68, //Faroe Islands (the) DKK
		FO: 68, //Faroe Islands (the) DKK
		ENFO: 68, //Faroe Islands (the) DKK
		FRBE: 18, //Belgium EUR
		ENBE: 18, //Belgium EUR
		FRCA: 13, //Canada CAD
		FRCH: 47, //Switzerland CHF
		FRFR: 18, //France EUR
		FR: 18, //France EUR
		ENFR: 18, //France EUR
		FRLU: 33, //Luxembourg EUR
		FRMC: 18, //Monaco EUR
		ENMC: 18, //Monaco EUR
		HEIL: 78, //Israel ILS
		ENIL: 78, //Israel ILS
		HIIN: 83, //India INR
		HI: 83, //India INR
		ENIN: 83, //India INR
		HRHR: 29, //Croatia HRK
		ENHR: 29, //Croatia HRK
		HUHU: 14, //Hungary HUF
		HU: 14, //Hungary HUF
		ENHU: 14, //Hungary HUF
		HYAM: 88, //Armenia AMD
		IDID: 41, //Indonesia IDR
		ENID: 41, //Indonesia IDR
		ISIS: 67, //Iceland ISK
		IS: 67, //Iceland ISK
		ENIS: 67, //Iceland ISK
		ITCH: 102, //Switzerland CHF
		ITIT: 33, //Italy EUR
		IT: 33, //Italy EUR
		ENIT: 33, //Italy EUR
		JAJP: 72, //Japan JPY
		JA: 72, //Japan JPY
		ENJP: 72, //Japan JPY
		KAGE: 91, //Georgia GEL
		ENGE: 91, //Georgia GEL
		KKKZ: 74, //Kazakhstan KZT
		ENKZ: 74, //Kazakhstan KZT
		KOKR: 77, //Korea (the Republic of) KRW
		KO: 77, //Korea (the Republic of) KRW
		ENKR: 77, //Korea (the Republic of) KRW
		KYKG: 74, //Kyrgyzstan KGS
		KY: 74, //Kyrgyzstan KGS
		ENKG: 74, //Kyrgyzstan KGS
		LTLT: 19, //Lithuania EUR
		ENLT: 19, //Lithuania EUR
		LVLV: 21, //Latvia EUR
		ENLV: 21, //Latvia EUR
		MNMN: 81, //Mongolia MNT
		ENMN: 81, //Mongolia MNT
		MSBN: 49, //Brunei Darussalam BND
		ENBN: 49, //Brunei Darussalam BND
		MSMY: 64, //Malaysia MYR
		ENMY: 64, //Malaysia MYR
		MTMT: 79, //Malta EUR
		NBNO: 25, //Norway NOK
		NB: 25, //Norway NOK
		ENNO: 25, //Norway NOK
		NLBE: 42, //Belgium EUR
		NLNL: 44, //Netherlands (the) EUR
		ENNL: 44, //Netherlands (the) EUR
		NNNO: 68, //Norway NOK
		NN: 68, //Norway NOK
		PLPL: 17, //Poland PLN
		PL: 17, //Poland PLN
		ENPL: 17, //Poland PLN
		PTBR: 40, //Brazil BRL
		ENBR: 40, //Brazil BRL
		PTPT: 18, //Portugal EUR
		PT: 18, //Portugal EUR
		ENPT: 18, //Portugal EUR
		RORO: 31, //Romania RON
		RO: 31, //Romania RON
		ENRO: 31, //Romania RON
		RURU: 23, //Russian Federation (the) RUB
		RU: 23, //Russian Federation (the) RUB
		ENRU: 23, //Russian Federation (the) RUB
		SKSK: 20, //Slovakia EUR
		ENSK: 20, //Slovakia EUR
		SLSI: 34, //Slovenia EUR
		ENSI: 34, //Slovenia EUR
		SQAL: 59, //Albania ALL
		SRBA: 28, //Bosnia and Herzegovina BAM
		SR: 28, //Bosnia and Herzegovina BAM
		ENBA: 28, //Bosnia and Herzegovina BAM
		SVSE: 16, //Sweden SEK
		SV: 16, //Sweden SEK
		ENSE: 16, //Sweden SEK
		SWKE: 94, //Kenya KES
		SW: 94, //Kenya KES
		ENKE: 94, //Kenya KES
		THTH: 75, //Thailand THB
		TH: 75, //Thailand THB
		ENTH: 75, //Thailand THB
		TRTR: 45, //Turkey TRY
		TR: 45, //Turkey TRY
		ENTR: 45, //Turkey TRY
		UKUA: 22, //Ukraine UAH
		UK: 22, //Ukraine UAH
		ENUA: 22, //Ukraine UAH
		URPK: 76, //Pakistan PKR
		UR: 76, //Pakistan PKR
		ENPK: 76, //Pakistan PKR
		UZUZ: 74, //Uzbekistan UZS
		ENUZ: 74, //Uzbekistan UZS
		VIVN: 32, //Viet Nam VND
		ENVN: 32, //Viet Nam VND
		ZHCN: 73, //China CNY
		ZH: 73, //China CNY
		ENCN: 73, //China CNY
		ZHHK: 56, //Hong Kong HKD
		ENHK: 56, //Hong Kong HKD
		ZHMO: 98, //Macao MOP
		ENMO: 98, //Macao MOP
		ZHSG: 51, //Singapore SGD
		ENSG: 51, //Singapore SGD
		ZHTW: 60, //Taiwan (Province of China) TWD
		ENTW: 60, //Taiwan (Province of China) TWD
		ENNG: 104 //Nigeria NGN 06/05/2020
	};

	/** @nocollapse */
	FC.DEFAULT = {
		/** @nocollapse */
		sep: ',',
		/** @nocollapse */
		dPnt: '.',
		/** @nocollapse */
		ccy: '$',
		/** @nocollapse */
		ccy_r: ''
	};

	/**
	 * Currency, number and rate conventions
	 */
	FC.CCY_CONVENTIONS = [
		{indx: 0, sep: ',', dPnt: '.', ccy: '$', ccy_r: '', precision: 2, enum_date: 0},
		{indx: 1, sep: '.', dPnt: ',', ccy: '$', ccy_r: '', precision: 2, enum_date: 0},
		{indx: 2, sep: ',', dPnt: '.', ccy: '£', ccy_r: '', precision: 2, enum_date: 1}, // CCY_FORMATS.GBH
		{indx: 3, sep: ',', dPnt: '.', ccy: '', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.NON
		{indx: 4, sep: ',', dPnt: '.', ccy: '€', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.EUR1
		{indx: 5, sep: '.', dPnt: ',', ccy: '€', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.EUR2
		{indx: 6, sep: ' ', dPnt: ',', ccy: '', ccy_r: '€', precision: 2, enum_date: 2}, // CCY_FORMATS.EUR3
		{indx: 7, sep: '.', dPnt: ',', ccy: '', ccy_r: '€', precision: 2, enum_date: 2}, // CCY_FORMATS.EUR4
		{indx: 8, sep: ' ', dPnt: '.', ccy: 'R', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.RND // [KT] 08/08/2017
		{indx: 9, sep: ' ', dPnt: ',', ccy: 'kr', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.NOK // [KT] 09/24/2017
		{indx: 10, sep: ',', dPnt: '.', ccy: '₦', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.NGN // [KT] 02/11/2018, Nigerian naira
		{indx: 11, sep: ',', dPnt: '.', ccy: '฿', ccy_r: '', precision: 2, enum_date: 2}, // CCY_FORMATS.THB // [KT] 03/31/2018, Thai Baht
		{indx: 12, sep: DEFAULT_SEP, dPnt: DEFAULT_DPNT, ccy: DEFAULT_CCY, ccy_r: DEFAULT_CCY_R, precision: DEFAULT_PRECISION, enum_date: DEFAULT_DATE_ENUM
		}, // CCY_FORMATS.DEFAULT 12
		{indx: 13, dPnt: ',', sep: ' ', ccy: '', ccy_r: '$', precision: 2, enum_date: 2}, //13
		{indx: 14, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u0046\u0074', precision: 2, enum_date: 5}, //14
		{indx: 15, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u004b\u010d', precision: 2, enum_date: 3}, //15
		{indx: 16, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u006b\u0072', precision: 2, enum_date: 2}, //16
		{indx: 17, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u007a\u0142', precision: 2, enum_date: 3}, //17
		{indx: 18, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u20ac', precision: 2, enum_date: 1}, //18
		{indx: 19, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u20ac', precision: 2, enum_date: 2}, //19
		{indx: 20, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u20ac', precision: 2, enum_date: 3}, //20
		{indx: 21, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u20ac', precision: 2, enum_date: 5}, //21
		{indx: 22, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u20b4', precision: 2, enum_date: 3}, //22
		{indx: 23, dPnt: ',', sep: ' ', ccy: '', ccy_r: '\u20bd', precision: 2, enum_date: 3}, //23
		{indx: 24, dPnt: ',', sep: ' ', ccy: '\u0052', ccy_r: '', precision: 2, enum_date: 6}, //24
		{indx: 25, dPnt: ',', sep: ' ', ccy: '\u006b\u0072', ccy_r: '', precision: 2, enum_date: 3}, //25
		{indx: 26, dPnt: ',', sep: ' ', ccy: '\u20a1', ccy_r: '', precision: 2, enum_date: 1}, //26
		{indx: 27, dPnt: ',', sep: '', ccy: '', ccy_r: '\u043b\u0432', precision: 2, enum_date: 3}, //27
		{indx: 28, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u004b\u004d', precision: 2, enum_date: 3}, //28
		{indx: 29, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u006b\u006e', precision: 2, enum_date: 3}, //29
		{indx: 30, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u006b\u0072', precision: 2, enum_date: 3}, //30
		{indx: 31, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u006c\u0065\u0069', precision: 2, enum_date: 3}, //31
		{indx: 32, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u20ab', precision: 0, enum_date: 1}, //32
		{indx: 33, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u20ac', precision: 2, enum_date: 1}, //33
		{indx: 34, dPnt: ',', sep: '.', ccy: '', ccy_r: '\u20ac', precision: 2, enum_date: 3}, //34
		{indx: 35, dPnt: ',', sep: '.', ccy: '\u0024', ccy_r: '', precision: 0, enum_date: 4}, //35
		{indx: 36, dPnt: ',', sep: '.', ccy: '\u0024', ccy_r: '', precision: 2, enum_date: 1}, //36
		{indx: 37, dPnt: ',', sep: '.', ccy: '\u0024\u0055', ccy_r: '', precision: 2, enum_date: 1}, //37
		{indx: 38, dPnt: ',', sep: '.', ccy: '\u0024\u0062', ccy_r: '', precision: 2, enum_date: 1}, //38
		{indx: 39, dPnt: ',', sep: '.', ccy: '\u0047\u0073', ccy_r: '', precision: 0, enum_date: 1}, //39
		{indx: 40, dPnt: ',', sep: '.', ccy: '\u0052\u0024', ccy_r: '', precision: 2, enum_date: 1}, //40
		{indx: 41, dPnt: ',', sep: '.', ccy: '\u0052\u0070', ccy_r: '', precision: 2, enum_date: 1}, //41
		{indx: 42, dPnt: ',', sep: '.', ccy: '\u20ac', ccy_r: '', precision: 2, enum_date: 1}, //42
		{indx: 43, dPnt: ',', sep: '.', ccy: '\u20ac', ccy_r: '', precision: 2, enum_date: 3}, //43
		{indx: 44, dPnt: ',', sep: '.', ccy: '\u20ac', ccy_r: '', precision: 2, enum_date: 4}, //44
		{indx: 45, dPnt: ',', sep: '.', ccy: '\u20ba', ccy_r: '', precision: 2, enum_date: 3}, //45
		{indx: 46, dPnt: ',', sep: '.', ccy: 'VES', ccy_r: '', precision: 2, enum_date: 1}, //46
		{indx: 47, dPnt: '.', sep: ' ', ccy: '', ccy_r: '\u0043\u0048\u0046', precision: 2, enum_date: 3}, //47
		{indx: 48, dPnt: '.', sep: ',', ccy: '\u0024', ccy_r: '', precision: 2, enum_date: 0}, //48
		{indx: 49, dPnt: '.', sep: ',', ccy: '\u0024', ccy_r: '', precision: 2, enum_date: 1}, //49
		{indx: 50, dPnt: '.', sep: ',', ccy: '\u0024', ccy_r: '', precision: 2, enum_date: 2}, //50
		{indx: 51, dPnt: '.', sep: ',', ccy: '\u0024', ccy_r: '', precision: 2, enum_date: 6}, //51
		{indx: 52, dPnt: '.', sep: ',', ccy: '\u0042\u002f\u002e', ccy_r: '', precision: 2, enum_date: 0}, //52
		{indx: 53, dPnt: '.', sep: ',', ccy: '\u0042\u005a\u0024', ccy_r: '', precision: 2, enum_date: 1}, //53
		{indx: 54, dPnt: '.', sep: ',', ccy: '\u0042\u0072', ccy_r: '', precision: 2, enum_date: 0}, //54
		{indx: 55, dPnt: '.', sep: ',', ccy: '\u0043\u0024', ccy_r: '', precision: 2, enum_date: 1}, //55
		{indx: 56, dPnt: '.', sep: ',', ccy: '\u0048\u004b\u0024', ccy_r: '', precision: 2, enum_date: 1}, //56
		{indx: 57, dPnt: '.', sep: ',', ccy: '\u004a\u0024', ccy_r: '', precision: 2, enum_date: 1}, //57
		{indx: 58, dPnt: '.', sep: ',', ccy: '\u004c', ccy_r: '', precision: 2, enum_date: 1}, //58
		{indx: 59, dPnt: '.', sep: ',', ccy: '\u004c\u0065\u006b', ccy_r: '', precision: 2, enum_date: 0}, //59
		{indx: 60, dPnt: '.', sep: ',', ccy: '\u004e\u0054\u0024', ccy_r: '', precision: 2, enum_date: 6}, //60
		{indx: 61, dPnt: '.', sep: ',', ccy: '\u0051', ccy_r: '', precision: 2, enum_date: 1}, //61
		{indx: 62, dPnt: '.', sep: ',', ccy: '\u0052', ccy_r: '', precision: 2, enum_date: 0}, //62
		{indx: 63, dPnt: '.', sep: ',', ccy: '\u0052\u0044\u0024', ccy_r: '', precision: 2, enum_date: 1}, //63
		{indx: 64, dPnt: '.', sep: ',', ccy: '\u0052\u004d', ccy_r: '', precision: 2, enum_date: 1}, //64
		{indx: 65, dPnt: '.', sep: ',', ccy: '\u0053\u002f\u002e', ccy_r: '', precision: 2, enum_date: 1}, //65
		{indx: 66, dPnt: '.', sep: ',', ccy: '\u0054\u0054\u0024', ccy_r: '', precision: 2, enum_date: 1}, //66
		{indx: 67, dPnt: '.', sep: ',', ccy: '\u006b\u0072', ccy_r: '', precision: 0, enum_date: 0}, //67
		{indx: 68, dPnt: '.', sep: ',', ccy: '\u006b\u0072', ccy_r: '', precision: 2, enum_date: 0}, //68
		{indx: 69, dPnt: '.', sep: ',', ccy: '\u00a3', ccy_r: '', precision: 0, enum_date: 0}, //69
		{indx: 70, dPnt: '.', sep: ',', ccy: '\u00a3', ccy_r: '', precision: 2, enum_date: 0}, //70
		{indx: 71, dPnt: '.', sep: ',', ccy: '\u00a3', ccy_r: '', precision: 2, enum_date: 1}, //71
		{indx: 72, dPnt: '.', sep: ',', ccy: '\u00a5', ccy_r: '', precision: 0, enum_date: 6}, //72
		{indx: 73, dPnt: '.', sep: ',', ccy: '\u00a5', ccy_r: '', precision: 2, enum_date: 6}, //73
		{indx: 74, dPnt: '.', sep: ',', ccy: '\u043b\u0432', ccy_r: '', precision: 2, enum_date: 0}, //74
		{indx: 75, dPnt: '.', sep: ',', ccy: '\u0e3f', ccy_r: '', precision: 2, enum_date: 1}, //75
		{indx: 76, dPnt: '.', sep: ',', ccy: '\u20a8', ccy_r: '', precision: 2, enum_date: 0}, //76
		{indx: 77, dPnt: '.', sep: ',', ccy: '\u20a9', ccy_r: '', precision: 0, enum_date: 5}, //77
		{indx: 78, dPnt: '.', sep: ',', ccy: '\u20aa', ccy_r: '', precision: 2, enum_date: 3}, //78
		{indx: 79, dPnt: '.', sep: ',', ccy: '\u20ac', ccy_r: '', precision: 2, enum_date: 0}, //79
		{indx: 80, dPnt: '.', sep: ',', ccy: '\u20ac', ccy_r: '', precision: 2, enum_date: 1}, //80
		{indx: 81, dPnt: '.', sep: ',', ccy: '\u20ae', ccy_r: '', precision: 2, enum_date: 0}, //81
		{indx: 82, dPnt: '.', sep: ',', ccy: '\u20b1', ccy_r: '', precision: 2, enum_date: 1}, //82
		{indx: 83, dPnt: '.', sep: ',', ccy: '\u20b9', ccy_r: '', precision: 2, enum_date: 1}, //83
		{indx: 84, dPnt: '.', sep: ',', ccy: '\u20bc', ccy_r: '', precision: 2, enum_date: 0}, //84
		{indx: 85, dPnt: '.', sep: ',', ccy: '\ufdfc', ccy_r: '', precision: 2, enum_date: 0}, //85
		{indx: 86, dPnt: '.', sep: ',', ccy: '\ufdfc', ccy_r: '', precision: 3, enum_date: 0}, //86
		{indx: 87, dPnt: '.', sep: ',', ccy: 'AED', ccy_r: '', precision: 2, enum_date: 0}, //87
		{indx: 88, dPnt: '.', sep: ',', ccy: 'AMD', ccy_r: '', precision: 2, enum_date: 0}, //88
		{indx: 89, dPnt: '.', sep: ',', ccy: 'BHD', ccy_r: '', precision: 3, enum_date: 0}, //89
		{indx: 90, dPnt: '.', sep: ',', ccy: 'DZD', ccy_r: '', precision: 2, enum_date: 0}, //90
		{indx: 91, dPnt: '.', sep: ',', ccy: 'GEL', ccy_r: '', precision: 2, enum_date: 0}, //91
		{indx: 92, dPnt: '.', sep: ',', ccy: 'IQD', ccy_r: '', precision: 3, enum_date: 0}, //92
		{indx: 93, dPnt: '.', sep: ',', ccy: 'JOD', ccy_r: '', precision: 3, enum_date: 0}, //93
		{indx: 94, dPnt: '.', sep: ',', ccy: 'KES', ccy_r: '', precision: 2, enum_date: 1}, //94
		{indx: 95, dPnt: '.', sep: ',', ccy: 'KWD', ccy_r: '', precision: 3, enum_date: 0}, //95
		{indx: 96, dPnt: '.', sep: ',', ccy: 'LYD', ccy_r: '', precision: 3, enum_date: 0}, //96
		{indx: 97, dPnt: '.', sep: ',', ccy: 'MAD', ccy_r: '', precision: 2, enum_date: 0}, //97
		{indx: 98, dPnt: '.', sep: ',', ccy: 'MOP', ccy_r: '', precision: 2, enum_date: 6}, //98
		{indx: 99, dPnt: '.', sep: ',', ccy: 'MVR', ccy_r: '', precision: 2, enum_date: 0}, //99
		{indx: 100, dPnt: '.', sep: ',', ccy: 'TND', ccy_r: '', precision: 3, enum_date: 0}, //100
		{indx: 101, dPnt: '.', sep: ',', ccy: 'ZWL', ccy_r: '', precision: 2, enum_date: 1}, //101
		{indx: 102, dPnt: '.', sep: '’', ccy: '\u0043\u0048\u0046', ccy_r: '', precision: 2, enum_date: 1}, //102
		{indx: 103, dPnt: '.', sep: '’', ccy: '\u0043\u0048\u0046', ccy_r: '', precision: 2, enum_date: 3}, //103
		// [KT] 06/05/2020 - 
		{indx: 104, dPnt: '.', sep: ',', ccy: '\u20a6', ccy_r: '', precision: 2, enum_date: 3} //104
		// Update MAX_NO_CCY_FORMATS = 105 if a new format is added!!
	];
	

	/**
	 * Date formats - ordinal value for selecting a date_mask
	 */
	FC.DATE_FORMATS = {
		MDY: 0,
		DMY: 1,
		YMD: 2,
		DMY2: 3, // 31.01.2020
		DMY3: 4, // 31-01-2020
		YMD2: 5, // 2020.01.31
		YMD3: 6 // 2020/01/31
	};


	/**
	 * Date formats
	 * [KT] 01/12/2020 - expanded date format option
	 */
	FC.DATE_FORMAT_STRS = ['MM/DD/YYYY', 'DD/MM/YYYY', 'YYYY-MM-DD', 'DD.MM.YYYY', 'DD-MM-YYYY', 'YYYY.MM.DD', 'YYYY/MM/DD'];



	FC.DATE_CONVENTIONS = [
		{date_format: 0, date_mask: 'MM/DD/YYYY', date_sep: '/', sep_pos1: 2, sep_pos2: 5},
		{date_format: 1, date_mask: 'DD/MM/YYYY', date_sep: '/', sep_pos1: 2, sep_pos2: 5},
		{date_format: 2, date_mask: 'YYYY-MM-DD', date_sep: '-', sep_pos1: 4, sep_pos2: 7},
		{date_format: 3, date_mask: 'DD.MM.YYYY', date_sep: '.', sep_pos1: 2, sep_pos2: 5},
		{date_format: 4, date_mask: 'DD-MM-YYYY', date_sep: '-', sep_pos1: 2, sep_pos2: 5},
		{date_format: 5, date_mask: 'YYYY.MM.DD', date_sep: '.', sep_pos1: 4, sep_pos2: 7},
		{date_format: 6, date_mask: 'YYYY/MM/DD', date_sep: '/', sep_pos1: 4, sep_pos2: 7}
		// update MAX_DATE_ENUM = 6 if additional date conventions are added
	];



	/**
	 * @enum {number}
	 * @nocollapse
	 */
	FC.ROW_TYPES = {
		DETAIL: 0,
		ANNUAL_TOTALS: 1,
		RUNNING_TOTALS: 2
	};


	/** @nocollapse */
	FC.STR_FREQUENCIES = [];
	FC.STR_FREQUENCIES[6] = 'Monthly';


	/**
	 * Currency symbols see: http://www.xe.com/symbols.php
	 * @nocollapse
	 */
	FC.CURRENCIES = {
		EUR: '\u20ac', // Euro  Optionally: " \u20AC" note initial space for right aligned symbol.
		GBP: '\u00a3', // Pound
		// INR: '\u20a8', // Indian Rupee - sometimes Rs
		// [KT] 01/12/2020
		INR: '\u20b9', // Indian Rupee - ₹123,456.00 &#8377, new symbol
		JPY: '\u00a5', // Yen
		KPW: '\u20a9', // North Korea Won
		THB: '\u0E3F', // ฿ Thai Baht
		//	"USD": "\x24"
		USD: '$'
	};


	/**
	 * Enum for Initial Period Types. Cash flow at start is a "regular" type.
	 * @enum {number}
	 * @nocollapse
	 */
	FC.INITIAL_PERIOD_TYPE = {
		REGULAR: 1
	};


	/**
	 * global default values, constants
	 */
	/** @const */
	FC.EMPTY_STR = ''; // eliminate JSLint warnings
	/** @const */
	FC.PCT = '%';
	/** @const */
	FC.US_DECIMAL = '.';
	/** @const */
	FC.MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
	//Date objects are based on a time value that is the number of milliseconds since 1 January, 1970 UTC.
	/** @const */
	FC.MIN_YEAR = 1970;
	/** @const */
	FC.MIN_DATE = new Date(FC.MIN_YEAR, 0, 1, 0, 0, 0, 0);
	/** @const */
	FC.MAX_YEAR = 2099;
	/** @const */
	FC.MAX_DATE = new Date(FC.MAX_YEAR, 11, 31, 0, 0, 0, 0);
	/** @const */
	FC.INITIAL_CASH_FLOWS = 500;
	/** @const */
	FC.DIY = 0; // FC.DAYS_IN_YEAR.THREE_SIXTY;
	/** @const */
	FC.PPY = [];
	/** @const */
	FC.PPY[6] = 12; // periods per year


	// global default long initial period interest payment method
	/** @const */
	FC.LIPM = 2; // with first FC.LONG_INTEREST_PMT_MTHD.AMORTIZED;
	// global default short initial period interest payment method
	/** @const */
	FC.SIPM = 2; // FC.SHORT_INTEREST_PMT_MTHD.REDUCEALL;
	// FC.UNKNOWN_STR = "";



	/**
	 * global shared methods
	 */
	FC.displayExceptionMsg = function (e) {
		alert(e);
	};


	/**
	 * Simple Print Routine
	 */
	FC.print_calc = function () {
		window.print();
	};

	/**
	 * Select text in element. Usually <textarea>
	 * http://stackoverflow.com/questions/5797539/jquery-select-all-text-from-a-textarea
	 */
	FC.focusSelect = function (el) {
		el.onfocus = function () {
			el.select();
			// Work around Chrome's little problem
			// el.onmouseup = function () {
			// 	// Prevent further mouseup intervention
			// 	el.onmouseup = null;
			// 	return false;
			// };
		};
	};



	FC.stripCharsInBag = function (s, bag) {
		var i, c, returnString = '';

		// Search through string's characters one by one.
		// If character is not in bag, append to returnString.
		for (i = 0; i < s.length; i += 1) {
			c = s.charAt(i);
			if (bag.indexOf(c) === -1) {
				returnString += c;
			}
		}
		return returnString;
	}; // stripCharsInBag


	//!!.02 [BJ] 09/28/2010 - string buffer for string management.
	/**
	 * StringBuffer is a wrapper around an array.
	 * @constructor
	 */
	FC.StringBuffer = function () {
		this.buffer = [];
	};

	FC.StringBuffer.prototype.append = function append(string) {
		this.buffer.push(string);
		return this;
	};

	FC.StringBuffer.prototype.toString = function toString() {
		return this.buffer.join('');
	};


	/**
	 * @constructor
	 * @param {number=} ccy_format
	 */
	FC.LocalConventions = function (ccy_format) {

		if (ccy_format === undefined) {
			// [KT] 01/06/2020 - allow website owner to set default currency
			var key; // FC.CCY_FORMATS attribute
			// [KT] 01/12/2020 - changed logic to use user's locale per JavaScript's Intl object
			// if there's not a user saved convention, then get the user's locale
			// for internation users to test, see:  https://jsfiddle.net/taa1953/pb5tzg4m/2/   
			var nf = new Intl.NumberFormat();
			var options = nf.resolvedOptions();

			if (options.locale) {
				key = options.locale.replace('-', '');
				key = key.toUpperCase();
				// Bracket notation: something['bar']
				ccy_format = FC.CCY_FORMATS[key];
				if (ccy_format === undefined || ccy_format < 0 || ccy_format > MAX_NO_CCY_FORMATS) {
					ccy_format = FC.CCY_FORMATS.ENUS;
				}
			} else {
				ccy_format = FC.CCY_FORMATS.ENUS;
			}

		}
		// [KT] 06/07/2020 - in case ccy_format is invalid due to a bad or corrupt cookie
		if (FC.CCY_CONVENTIONS[ccy_format] !== undefined) {
			/** @nocollapse */
			this.ccy_format = ccy_format;
			/** @nocollapse */
			this.sep = FC.CCY_CONVENTIONS[ccy_format].sep;
			/** @nocollapse */
			this.dPnt = FC.CCY_CONVENTIONS[ccy_format].dPnt;
			/** @nocollapse */
			this.ccy = FC.CCY_CONVENTIONS[ccy_format].ccy;
			/** @nocollapse */
			this.ccy_r = FC.CCY_CONVENTIONS[ccy_format].ccy_r;
			/** @nocollapse */
			this.precision = FC.CCY_CONVENTIONS[ccy_format].precision;
		} else {
			/** @nocollapse */
			this.ccy_format = FC.CCY_FORMATS.ENUS;
			/** @nocollapse */
			this.sep = DEFAULT_SEP;
			/** @nocollapse */
			this.dPnt = DEFAULT_DPNT;
			/** @nocollapse */
			this.ccy = DEFAULT_CCY;
			/** @nocollapse */
			this.ccy_r = DEFAULT_CCY_R;
			/** @nocollapse */
			this.precision = 2;
			// set a valid currency cookie
			// Cookies.set('ccy_format', this.ccy_format, {expires: Infinity});
		}
	};


	/**
	 * Allocate and initialize numeric conventions without currency 
	 * based on local currency conventions.
	 */
	FC.LocalConventions.prototype.numConvention = function () {
		var c = new FC.LocalConventions(this.ccy_format);

		c.ccy = '';
		c.ccy_r = '';
		return c;
	};


	/**
	 * Allocate and initialize rate conventions without currency 
	 * based on local currency conventions.
	 */
	FC.LocalConventions.prototype.rateConvention = function () {
		var c = new FC.LocalConventions(this.ccy_format);

		c.ccy = '';
		c.ccy_r = '%';
		return c;
	};


	/**
	 * @constructor
	 * @nocollapse
	 * @param {number=} date_format
	 */
	FC.LocalDateConventions = function (date_format) {
		var key; // FC.CCY_FORMATS attribute

		if (date_format === undefined || date_format === null) {
			// [KT] - changed default 01/06/2020
			var nf = new Intl.NumberFormat();
			var options = nf.resolvedOptions();

			if (options.locale) {
				key = options.locale.replace('-', '');
				key = key.toUpperCase();
				// Bracket notation: something['bar']
				// enum_date
				var ccy_format = FC.CCY_FORMATS[key];

				if (!ccy_format || ccy_format < 0 || ccy_format > MAX_NO_CCY_FORMATS) {
					ccy_format = FC.CCY_FORMATS.ENUS;
				}
			} else {
				ccy_format = FC.CCY_FORMATS.ENUS;
			}
			date_format = FC.CCY_CONVENTIONS[ccy_format].enum_date; // get the index into DATE_CONVENTIONS
			if (date_format === undefined || date_format === null || date_format < 0 || date_format > MAX_DATE_ENUM) {
				date_format = FC.DATE_FORMATS.ENUS;
			}
		}
		// [KT] 06/07/2020 - in case there is an invalid cookie
		if (FC.DATE_CONVENTIONS[date_format] !== undefined) {
			/** @nocollapse */
			this.date_format = date_format; // enum value of date_mask selected
			/** @nocollapse */
			this.date_mask = FC.DATE_CONVENTIONS[date_format].date_mask;
			/** @nocollapse */
			this.date_sep = FC.DATE_CONVENTIONS[date_format].date_sep;
			/** @nocollapse */
			this.sep_pos1 = FC.DATE_CONVENTIONS[date_format].sep_pos1;
			/** @nocollapse */
			this.sep_pos2 = FC.DATE_CONVENTIONS[date_format].sep_pos2;
		} else {
			this.date_format = DEFAULT_DATE_ENUM; // enum value of date_mask selected
			/** @nocollapse */
			this.date_mask = FC.DATE_FORMATS.YMD;
			/** @nocollapse */
			this.date_sep = '-';
			/** @nocollapse */
			this.sep_pos1 = 4;
			/** @nocollapse */
			this.sep_pos2 = 7;
			// set a valid cookie
			// Cookies.set('date_format', this.date_format, {expires: Infinity});
		}
	};


	/**
	 * global default values, "now" string variables for building a guaranteed valid date string, NOT '0' based month.
	 */
	FC.dateNow = new Date();
	FC.yearNow = FC.dateNow.getFullYear().toString();
	// when no date parameter, date defaults to 1, valid date is always 1st of next month
	// guarantees leading 0 if needed, used to always return valid date string by DE
	FC.monthNowName = FC.MONTHS[FC.dateNow.getMonth()];
	FC.monthNow = ('0' + (FC.dateNow.getMonth() + 1)).slice(-2);
	FC.dayNow = '01';
	FC.pmt_method = 0; // end-of-period
	FC.pmt_frequency = 6; // monthly
	FC.cmp_frequency = 6; // monthly
	FC.ppy = FC.PPY[FC.pmt_frequency];
	FC.cmpPPY = FC.PPY[FC.cmp_frequency];


	/**
	 * object for localized currency conventions
	 * @nocollapse
	 */
	FC.moneyConventions = new FC.LocalConventions(); // set defaults


	/**
	 * object for localized rate/percentage conventions
	 * @nocollapse
	 */
	FC.rateConventions = FC.moneyConventions.rateConvention(); // clones moneyConventions and sets percent sign


	/**
	 * object for localized number conventions (no currency symbol)
	 * @nocollapse
	 */
	FC.numConventions = FC.moneyConventions.numConvention(); // clones moneyConventions and removes currency symbol


	/**
	 * When user changes conventions, attributes of money, rate and number have to be updated
	 * @nocollapse
	 */
	FC.resetNumConventions = function (ccy_format) {
		if (typeof ccy_format === 'number' && ccy_format !== FC.moneyConventions.ccy_format) {
			// [KT] 06/15/2020 - bug fix, don't use cookie, need parameter to reset
			FC.moneyConventions = new FC.LocalConventions(ccy_format);
			// FC.moneyConventions = new FC.LocalConventions(parseInt(Cookies.get('ccy_format'), 10));
			FC.rateConventions = FC.moneyConventions.rateConvention(); // clones currency conventions with '%' symbol
			FC.numConventions = FC.moneyConventions.numConvention(); // clones currency conventions without currency
			// [KT] 06/15/2020 - ccy_format already a number/integer
			Cookies.set('ccy_format', ccy_format, {expires: Infinity});
			// Cookies.set('ccy_format', parseInt(ccy_format, 10), {expires: Infinity});
		}
	};

	// [KT] 02/05/2020 - new function updates existing conventions and does not does not write cookie
	FC.updateNumConventions = function (ccy_format) {
		if (ccy_format !== FC.moneyConventions.ccy_format && FC.CCY_CONVENTIONS[ccy_format].sep !== undefined) {
			FC.moneyConventions.ccy_format = ccy_format;
			FC.moneyConventions.sep = FC.CCY_CONVENTIONS[ccy_format].sep;
			FC.moneyConventions.dPnt = FC.CCY_CONVENTIONS[ccy_format].dPnt;
			FC.moneyConventions.ccy = FC.CCY_CONVENTIONS[ccy_format].ccy;
			FC.moneyConventions.ccy_r = FC.CCY_CONVENTIONS[ccy_format].ccy_r;
			FC.moneyConventions.precision = FC.CCY_CONVENTIONS[ccy_format].precision;
			// rate
			FC.rateConventions.ccy_format = ccy_format;
			FC.rateConventions.sep = FC.moneyConventions.sep;
			FC.rateConventions.dPnt = FC.moneyConventions.dPnt;
			FC.rateConventions.precision = FC.moneyConventions.precision;
			// number
			FC.numConventions.ccy_format = ccy_format;
			FC.numConventions.sep = FC.moneyConventions.sep;
			FC.numConventions.dPnt = FC.moneyConventions.dPnt;
			FC.numConventions.precision = FC.moneyConventions.precision;
		// } else if (FC.CCY_CONVENTIONS[ccy_format].sep === undefined) {
		// 	alert('An invalid currency has been configured.');
		}
	};

	/**
	 * object for localized date conventions
	 * @nocollapse
	 */
	FC.dateConventions = new FC.LocalDateConventions();

	/**
	 * YYYY-MM-DD date convention for sorting
	 * @nocollapse
	 */
	FC.sortConventions = new FC.LocalDateConventions(FC.DATE_FORMATS.YMD);


	/**
	 * When user changes conventions, attributes of money, rate and number have to be updated
	 * @nocollapse
	 */
	FC.resetDateConventions = function (date_format) {
		if (date_format !== FC.dateConventions.date_format) {
			FC.dateConventions = new FC.LocalDateConventions(date_format);
			Cookies.set('date_format', parseInt(date_format, 10), {expires: Infinity});
		}
	};

	// [KT] 02/05/2020 - new function as 'resetDateConventions' but does not write cookie
	FC.updateDateConventions = function (date_format) {
		if (date_format !== FC.dateConventions.date_format) {
			FC.dateConventions = new FC.LocalDateConventions(date_format);
		}
	};

	/**
	 * Banker's rounding
	 * src: http://stackoverflow.com/questions/3108986/gaussian-bankers-rounding-in-javascript
	 * Google search: "javascript bankers rounding example" -- lots of info function evenRound(num, decimalPlaces) {
	 */
	FC.evenRound = function (num, decimalPlaces) {
		var d = decimalPlaces || 0,
			m = Math.pow(10, d),
			n = +(d ? num * m : num).toFixed(8), // Avoid rounding errors
			i = Math.floor(n),
			f = n - i,
			e = 1e-8, // Allow for rounding errors in f
			r = f > 0.5 - e && f < 0.5 + e ? (i % 2 === 0 ? i : i + 1) : Math.round(n);

		return d ? r / m : r;
	};



	/** @nocollapse */
	FC.roundMoney = function (n, digits, isBankersRounding) {
		// TODO: raise an exception if digits > 10

		// [KT] 01/12/2020
		if (digits === 0) {
			// [KT] 04/22/2020 - added check for negative value
			if (n >= 0) {
				n = Math.round(n) + 0.0001; // need to preserve decimal point for separator formatting
			} else {
				n = Math.round(n) - 0.0001; // need to preserve decimal point for separator formatting
			}
			digits = 4;
		}
		// [KT] end change


		var precision = Math.pow(10, digits || 2);

		if (n === undefined || n === null || typeof n !== 'number') {
			return ''; // So that display shows an empty value.
		}
		if (isBankersRounding === undefined || !isBankersRounding) {
			return Math.round(n * precision) / precision;
		}
		return FC.evenRound(n, digits);
	};


	// /**
	//  * Is a string an integer?
	//  * See also: http://surf11.com/entry/157/javascript-isinteger-function
	//  * @param {string} s
	//  * @return {boolean}
	//  */
	// function isInteger(s) {
	// 	return (s.toString().search(/^-?[0-9]+$/) === 0);
	// }



	/**
	 * Add new formatting
	 * @param {string} nStr
	 * @param {string} sep
	 * @param {string} dPnt
	 * @param {number} precision
	 * @return {string}
	 */
	FC.addSeparators = function (nStr, sep, dPnt, precision) {
		var dpos = nStr.indexOf(dPnt),
			rgx = /(\d+)(\d{3})/,
			numText = '',
			n,
			i;

		// remove formatting
		for (i = 0; i < nStr.length; i += 1) {
			if (nStr.charAt(i) !== sep) {
				numText += nStr.charAt(i);
			}
		}

		// [KT] 04/20/2012 if no decimal & precision !== 0, add decimal char
		if (dpos === -1 && precision > 0) {
			// remove possible leading zeros
			while (((numText.length > 1) && (numText.charAt(0) === '0')) || ((numText.charAt(0) === '-') && (numText.length > 2) && (numText.charAt(1) === '0'))) {
				numText = numText.substr(1, numText.length - 1);
			}
			while (rgx.test(numText)) {
				numText = numText.replace(rgx, '$1' + sep + '$2');
			}
			return numText;
			// [KT] 04/20/2012 new condition, don't add decimal char if 0 precision
		}
		if ((dpos === -1) && (precision === 0)) {
			// [KT] 05/28/2012 - was only adding one separator
			// while (rgx.test(numText && sep !== '')) {
			// [KT] 05/07/2020 - corrected
			while (rgx.test(numText) && sep !== '') {
				numText = numText.replace(rgx, '$1' + sep + '$2');
			}
			return numText;
		}
		n = numText.split(dPnt);
		while (rgx.test(n[0]) && sep !== '') {
			n[0] = n[0].replace(rgx, '$1' + sep + '$2');
		}
		// [KT] 04/20/2012 added 0 precision checks
		if (precision !== 0 && n[1].length <= precision) {
			return n[0] + dPnt + n[1];
		}
		if (precision !== 0) {
			return n[0] + dPnt + n[1].substr(0, precision);
		}
		return n[0];
	}; // FC.addSeparators

	// [KT] 01/12/2020 - special handling
	var formatIndianRupee = function (n, ccy, precision) {
		var style;

		if (typeof n === 'string') {
			n = parseFloat(n);
		}

		// INDIAN_RUPEE_INDEX
		if (ccy !== '') {
			// add the currency symbol
			style = 'currency';
		} else {
			// no currency symbol
			style = 'decimal';
		}
		return n.toLocaleString('en-IN', {
			maximumFractionDigits: precision,
			minimumFractionDigits: precision, // need to allow for explicit 0 for integers
			style: style,
			currency: 'INR'
		});
	}; // formatIndianRupee


	/**
	 * number to conform to local conventions.
	 * @param {?number} nNum
	 * @param {string} dPnt
	 * @return {string}
	 * @nocollapse
	 */
	FC.getLocalNumStr = function (nNum, dPnt) {
		if (nNum === null) {
			return '';
		}
		var strNum = nNum + FC.EMPTY_STR; // convert number to string

		if (dPnt !== FC.US_DECIMAL) {
			strNum = strNum.replace(/\./, dPnt); // escape US decimal point
		}
		return strNum;
	};


	/**
	 * formatFloat(value, sep, dPnt, precision)
	 * Called by the form when it is being initialized.
	 * Generic numeric format w/o currency or '%'.
	 * @param {number} value
	 * @param {string} sep
	 * @param {string} dPnt
	 * @param {number} precision
	 * @return {string}
	 * @nocollapse
	 */
	FC.formatFloat = function (value, sep, dPnt, precision) {
		var numText = FC.getLocalNumStr(value, dPnt),
			dpos = numText.indexOf(dPnt);


		if (numText === null || numText.length === 0) {
			return FC.EMPTY_STR;
		}

		if ((dpos === -1) && (precision !== 0)) {
			numText += dPnt;
			dpos = numText.length - 1;
		}

		if (precision > 0) {
			while (numText.length - 1 - dpos < precision) {
				//while ((((numText.length - 1) - dpos) < precision) && (precision > 0)) {
				// we need to pad
				numText += '0';
			}
		}

		if (this.moneyConventions.ccy_format !== INDIAN_RUPEE_INDEX || (this.moneyConventions.ccy_format === INDIAN_RUPEE_INDEX && this.numConventions.ccy_r === '%')) {
			return FC.addSeparators(numText, sep, dPnt, precision);
		} else {
			return formatIndianRupee(value, this.numConventions.ccy, precision);
		}

	}; // FC.formatFloat


	/**
	 * Formats float with local currency conventions or PCT if ccy_r = PCT
	 * @param {number} value
	 * @param {Object} conventions
	 * @param {number} precision
	 * @param {(number|undefined)} maxLength
	 * @return {string}
	 * @nocollapse
	 */
	FC.formatLocalFloat = function (value, conventions, precision) {
		// [KT] 02/01/2020 - if precision had been 0, then use, indicates an explicit integer
		// [KT] 01/12/2020 - removed precision & maxLength parameter
		// [KT] 09/22/2017 - bug fix
		// [KT] 09/30/2017 - bug fix - added check for number type as value can be a string!
		// precision = null; // precision parameter ignored - need to remove from calls - NO, must keep
		// if precision is 0 then explicit integer, ignore locale's precision, if interest rate, use explicit precision i.e. ignore locale's precision
		// [KT] 03/29/2020 - bug fix, can't depend on '%' logic, allow non currency floats to specify precision
		precision = (precision === 0) ? 0 : (conventions.ccy_r === '%' || (conventions.ccy === '' && conventions.ccy_r === '')) ? precision : conventions.precision;
		if (typeof value === 'number') {
			// [KT] 01/12/2020 - changed to use precision from conventions
			value = FC.roundMoney(value, precision, false);
		}

		// [KT] 01/12/2020 - add check for no decimal point
		if (conventions.dPnt === '') {
			conventions.dPnt = '.';
		}


		// value, as a number, will always have US decimal, convert to a local number string
		var numText = FC.getLocalNumStr(value, conventions.dPnt),
			dpos = numText.indexOf(conventions.dPnt);
			// [KT] 01/12/2020 - no need to check maxLength
			// ml = maxLength !== undefined ? maxLength : 100;

		if (numText === null || numText.length === 0) {
			return FC.EMPTY_STR;
		}

		if (numText.charAt(0) === 'u' || numText.charAt(0) === 'U') {
			return FC.UNKNOWN_STR;
		}

		// [KT] 01/12/2020 - added explicit support for Indian Rupee
		if (this.moneyConventions.ccy_format === INDIAN_RUPEE_INDEX && conventions.ccy_r !== '%') {
			return formatIndianRupee(value, conventions.ccy, precision);
		}

		if (dpos === -1 && precision !== 0) {
			numText += conventions.dPnt;
			dpos = numText.length - 1;
		}

		if (precision > 0) {
			while (numText.length - 1 - dpos < precision) {
				// we need to pad
				numText += '0';
			}
		}

		// never formats Indian Rupee - per above
		numText = FC.addSeparators(numText, conventions.sep, conventions.dPnt, precision);


		// [KT] 01/12/2020 - allow multiple character currency symbols
		if (conventions.ccy !== '' && numText.charAt(0) !== conventions.ccy) {
			numText = conventions.ccy + numText;
		} else if (conventions.ccy_r !== '' && numText.charAt(numText.length - 1) !== conventions.ccy_r) {
			numText = numText + conventions.ccy_r;
		}
		return numText;

	};



	/**
	 * Converts any valid number string to a US numeric string
	 * @nocollapse
	 * @return {string}
	 */
	FC.getUSNumStr = function (sNum, sep, dPnt, ccy, ccy_r) {
		if (sNum !== FC.UNKNOWN_STR) {
			var regExp, Separator;

			Separator = sep !== '.' ? sep : '\\.';
			regExp =
				ccy !== '' ? new RegExp('\\' + ccy + '|' + ccy + '|' + Separator, 'g') : new RegExp('\\' + ccy_r + '|' + ccy_r + '|' + Separator, 'g');
			sNum = sNum.replace(regExp, FC.EMPTY_STR); // strip 1000s separator & CCY symbol
			if (dPnt !== FC.US_DECIMAL) {
				sNum = sNum.replace(dPnt, FC.US_DECIMAL); // must use US decimal point convention
			}
			if (sNum.charAt(sNum.length - 1) === FC.PCT) {
				sNum = sNum.slice(0, -1); // remove percent sign
			}
		} else {
			sNum = '0';
		}
		return sNum;
	};



	/**
	 * Converts numeric string to number.
	 * @return {number}
	 */
	FC.getUSNum = function (sNum, conventions, precision) {
		var n = FC.getUSNumStr(sNum, conventions.sep, conventions.dPnt, conventions.ccy, conventions.ccy_r);

		if (precision) {
			return parseFloat(n);
		}
		return parseInt(n, 10);
	};


	/**
	 * Numeric Editor - custom text input
	 * @constructor
	 * @param {string} id
	 * @param {Object} conventions
	 * @param {number} precision
	 * @param {boolean} isTableEditor
	 * @return {Object}
	 * @nocollapse
	 */
	var NE = function (id, conventions, precision) {
		// var num;

		// assume not a valid object
		this.isValid = false;
		this.element = document.getElementById(id);
		if (this.element !== null) {
			this.id = id;
			if (!conventions || conventions === null) {
				this.conventions = FC.moneyConventions;
			} else {
				this.conventions = conventions;
			}
			this.ccy_format = conventions.ccy_format;
			this.precision = precision !== undefined ? precision : 2;
			this.sep = conventions.sep;
			this.dPnt = conventions.dPnt;
			this.ccy = conventions.ccy;
			this.ccy_r = conventions.ccy_r;
			this.PCT = '%';
			this.customBlurHandler = null;
			this.customKeyPressHandler = null;
			this.customKeyDownHandler = null;
			this.customMouseUpHandler = null;
			// [KT] 01/05/2020 - to fix Android bug - Android does not allow user to select input unless long press.
			// this.customTouchEndHandler = null;
			this.UNKNOWN_STR = FC.UNKNOWN_STR;
			// if editor is contained in a DataTable, then allow up/down keys etc. Don't show instruction message.
			// and disable focus and blur events
			this.isTableEditor = false;
			this.init();
			this.isValid = true;
		}
	};

	FC['NE'] = NE;


	/**
	 * Numeric Editor - change field to interest rate editor
	 * this preserves the local setting for sep & dPnt
	 */
	NE.prototype.initRateEditor = function () {
		this.ccy = '';
		this.ccy_r = '%';
	};

	/**
	 * change field to be a plain number editor
	 * preserves the local setting for sep & dPnt
	 */
	NE.prototype.initNumEditor = function () {
		this.ccy = '';
		this.ccy_r = '';
	};


	/**
	 * Initialize an event listener
	 */
	NE.prototype.addEvent = function (event, callback, caller) {
		// check for modern browsers first
		var handler;

		if (typeof window.addEventListener === 'function') {
			handler = function (event) {
				callback.call(caller, event);
			};
			this.element.addEventListener(event, handler, false);
		} else {
			// then for older versions of IE
			handler = function () {
				callback.call(caller, window.event);
			};
			this.element.attachEvent('on' + event, handler);
		}
		return handler;
	};


	/**
	 * init object
	 */
	NE.prototype.init = function () {
		if (!this.isTableEditor) {
			this.customBlurHandler = this.addEvent('blur', this.onCustomBlur, this);
		}
		this.customKeyPressHandler = this.addEvent('keypress', this.onCustomKeyPress, this);
		this.customKeyDownHandler = this.addEvent('keydown', this.onCustomKeyDown, this);
		this.customMouseUpHandler = this.addEvent('mouseup', this.onCustomMouseUp, this);
		// [KT] 01/05/2020 - to fix Android bug - Android does not allow user to select input unless long press.
		// touchend event listener same as mouseup
		// this.customTouchEndHandler = this.addEvent('touchend', this.onCustomMouseUp, this);
	};


	/**
	 * detach event
	 */
	NE.prototype.removeEvent = function (event, handler) {
		if (typeof window.addEventListener === 'function') {
			this.element.removeEventListener(event, handler, false);
		} else {
			// old versions of IE only
			this.element.detachEvent('on' + event, handler);
		}
	};


	/**
	 * deallocate object 
	 */
	NE.prototype.destroy = function () {
		this.removeEvent('mouseup', this.customMouseUpHandler);
		// [KT] 01/05/2020 - to fix Android bug - Android does not allow user to select input unless long press.
		// this.removeEvent('touchend', this.customMouseUpHandler);
		this.customMouseUpHandler = null;
		// [KT] 01/05/2020 - to fix Android bug - Android does not allow user to select input unless long press.
		// this.customTouchEndHandler = null;
		this.removeEvent('keydown', this.customKeyDownHandler);
		this.customKeyDownHandler = null;
		this.removeEvent('keypress', this.customKeyPressHandler);
		this.customKeyPressHandler = null;
		if (!this.isTableEditor) {
			this.removeEvent('blur', this.customBlurHandler);
			this.customBlurHandler = null;
		}
		this.element = null;
	};



	/**
	 * keydown event handler responds to arrow keys
	 */
	NE.prototype.onCustomKeyDown = function onCustomKeyDown(e) {
		// console.log("keypress event handler");
		// [KT] 12/12/2011 - handling of delete key added.
		if (e.keyCode === 46) {
			e.preventDefault();
			alert(FC.ERR_MSGS.noDelKey);
			return false;
		}
		// this blocks left cursor key, "pgup", "pgdn", "home" or "end" keys
		// in IE but not Mozilla/Firefox
		// note: r. cursor key, #39, can be used to clear a selection without clearing the selected text itself
		// [KT] 12/13/2011 - added block for up(38)/down(40) cursor keys
		if ((e.keyCode === 37) || (e.keyCode === 33) || (e.keyCode === 34) || (e.keyCode === 35) || (e.keyCode === 36) || (e.keyCode === 38) || (e.keyCode === 40)) {
			if (!this.isTableEditor) {
				alert(FC.ERR_MSGS.noCurKeys);
			}
			if (e.preventDefault) {
				e.preventDefault();
			} else {
				// IE work around, of course!
				e.returnValue = false;
			}
			return false;
		}
		if (((e.keyCode === 188) && (this.sep === ',')) || (((e.keyCode === 110) || (e.keyCode === 190)) && (this.sep === '.')) ||
			((e.keyCode === 32) && (this.sep === ' '))) {
			// [KT] 04/22/2012 -- new message. NOTE: period key and decimal point key on numeric keypad return different keycode values
			alert(FC.ERR_MSGS.noSeparators);
			if (e.preventDefault) {
				e.preventDefault();
			} else {
				// IE work around, of course!
				e.returnValue = false;
			}
			return false;
		}
		return true;
	}; // onCustomKeyDown


	/**
	 * onKeypress event handler -- allows only digits and decimal
	 */
	NE.prototype.onCustomKeyPress = function onCustomKeyPress(e) {
		// alert("keypress event handler");
		var key,
			keychar,
			reg,
			isValid,
			isSelected = false,
			numText = '',
			strText = this.element.value,
			pos = strText.indexOf(this.dPnt),
			hasDecimal = pos >= 0,
			rng = null;

		// setCaretToEnd(maskControl);
		// sequence of test conditions important so that it works in Chrome
		if (e.which) {
			// for Mozilla Firefox, Netscape, Chrome see if a range is selected
			isSelected = (this.element.selectionEnd - this.element.selectionStart !== 0);
			key = e.which;
		} else if (window.event) {
			// for IE, see if a range is selected
			rng = document.selection.createRange();
			isSelected = rng.text !== '';
			// for IE, e.keyCode or window.event.keyCode can be used
			key = e.keyCode;
		} else {
			// no event raised in Mozilla/Firefox for l. cursor key "pgup", "pgdn", "home" or "end" keys
			// r. cursor key, #39, can be used to clear a selection without clearing the selected text itself
			if ((e.keyCode === 37) || (e.keyCode === 33) || (e.keyCode === 34) || (e.keyCode === 35) || (e.keyCode === 36)) {
				if (e.preventDefault) {
					e.preventDefault();
				} else {
					// IE work around, of course!
					e.returnValue = false;
				}
				return false;
			} else {
				return true;
			}
		}
		keychar = String.fromCharCode(key);
		reg = /\d/; // test for digits

		// if a number, negative sign (-), backspace or decimal char, then we are good
		// for decimal char to be valid, precision has to be > 0
		isValid = (reg.test(keychar) || key === 8 || (keychar === this.dPnt && !hasDecimal && this.precision > 0) ||
			((key === 45) && ((this.element.value.length === 0) || isSelected)));

		//[KT] 2009-03-07, special handling for "u" or "U"
		if (isValid && !isSelected && (key !== 8) && (this.element.value.length < this.element.maxLength) && (key !== 85) && (key !== 117)) {
			numText = this.element.value + keychar;
			// [KT] 2010-02-19 - handle minus sign, only first char of string.
			if (key === 45) {
				// if negative sign, clear entry so minus is always first char.
				this.element.value = '';
			} else if ((numText.length > 1) && (this.element.value !== this.UNKNOWN_STR)) {
				// remove the char just typed - it will be added by system
				this.element.value = numText.substr(0, numText.length - 1);
			} else {
				// required to eliminate leading zeros
				this.element.value = keychar;
				isValid = false; // block further events
			}
		}


		if (!isValid) {
			if (e.preventDefault) {
				e.preventDefault();
			} else {
				// IE work around, of course!
				e.returnValue = false;
			}
		}
		return isValid;

	}; // onCustomKeyPress


	/**
	 * onblur event handler -- format number, append currency symbol
	 */
	NE.prototype.onCustomBlur = function onCustomBlur() {
		if (this.element.value.length === 0) {
			this.element.value = 0; // so edit control is never left with null value
		} else if (this.element.value === FC.UNKNOWN_STR) {
			return;
		}
		// convert to string with local decimal
		this.element.value = FC.formatLocalFloat(FC.getUSNum(this.element.value, this.conventions, this.precision), this.conventions, this.precision, this.element.maxLength);
		return true;
	}; // onCustomBlur


	/**
	 * mouseup event hander - select content of input
	 */
	NE.prototype.onCustomMouseUp = function onCustomMouseUp(e) {
		//console.log("mouseup event handler");
		// value in editor is selected
		if (e.preventDefault) {
			this.element.selectionStart = 0;
			this.element.selectionEnd = this.element.value.length;
			e.preventDefault();
		} else {
			// IE work around, of course!
			this.element.select();
			e.returnValue = false;
		}
		return false;
	}; // onCustomMouseUp


	/**
	 * Converts a US number to local number.
	 * @param {number} v
	 */
	NE.prototype.setValue = function (v) {
		this.element.value = FC.formatLocalFloat(v, this.conventions, this.precision, this.element.maxLength);
	};



	/**
	 * Remove local conventions from input's value. Return number with US decimal ('.').
	 * @return {number}
	 */
	NE.prototype.getUSNumber = function () {
		var n = FC.getUSNumStr(this.element.value, this.sep, this.dPnt, this.ccy, this.ccy_r);

		if (this.precision !== 0) {
			return parseFloat(n);
		}
		return parseInt(n, 10);
	};


	/**
	 * Date routines.
	 * @nocollapse
	 */
	FC.dateMath = (function () {
		var days = 0,
			periods = 0,
			oddDays = 0,

			term = {
				/** @nocollapse */
				days: null,
				/** @nocollapse */
				periods: null,
				/** @nocollapse */
				oddDays: null
			},


			// rewritten to add months to d(ate).
			addMonths_ = function (d, months) {
				d.setMonth(d.getMonth() + months);
				return d.getTime();
			};


		/**
		 * @param {Date} aDate
		 * @param {number} n periods
		 */
		var addPeriods_ = function (aDate, n) {
			// preserve value of 'aDate'
			var d = new Date();

			d.setTime(aDate.getTime());
			d.setHours(0, 0, 0, 0);
			addMonths_(d, n);
			return d.getTime();
		}; // addPeriods_


		// call to addPeriods_() resets 'dat' member
		var countMonthBasePeriods = function (fDate, lDate) {
				// temp dates are used to preserve fDate, lDate values
				var tempDate = new Date(),
					fTempDate = new Date(),
					lTempDate = new Date(),
					indx = 0; // used to avoid infinite loops - should not be needed

				fTempDate.setTime(fDate.getTime());
				lTempDate.setTime(lDate.getTime());

				//alert(fTempDate.toDateString() + "\n" + lTempDate.toDateString() + "\n lTempDate - fTempDate = " + (lTempDate.getTime() - fTempDate.getTime()) + "\nDays = " + ((lTempDate.getTime() - fTempDate.getTime()) / ONE_DAY) +"\nOne day = "+ONE_DAY);
				days = Math.round((lTempDate.getTime() - fTempDate.getTime()) / FC.ONE_DAY); //round() required due to daylight savings time
				do {
					periods += 1;
					// Needs to preserve last date. If we go too far, for example, from Dec 31 and went to Nov 30th, then when adding back below, we end up on Dec 30th and one too few odd days
					tempDate.setTime(lTempDate.getTime()); // save current date
					lTempDate.setTime(addMonths_(lTempDate, -1));

					indx += 1;
					if (indx === 10000) {
						throw erInvalidDateMath;
					}
				} while ((lTempDate.getTime() > fTempDate.getTime()) && indx < 10000);

				if (lTempDate.getTime() < fTempDate.getTime()) {
					// Went too far. There are odd days
					periods -= 1;
					lTempDate.setTime(tempDate.getTime());
					oddDays = Math.round((lTempDate.getTime() - fTempDate.getTime()) / FC.ONE_DAY); //round() required due to daylight savings time
				}
				//alert('lTempDate = ' + lTempDate.toDateString() + "\n" + fTempDate.toDateString());
				return true;
			},

			// first cash flow date, last cash flow date
			countPeriods_ = function (fDate, lDate) {
				// Note, members, not local
				days = 0;
				periods = 0;
				oddDays = 0;

				if (fDate.getTime() > lDate.getTime()) {
					alert('Error: dates out of sequence.');
					return false;
				} else if (fDate.getTime() === lDate.getTime()) {
					term.days = days;
					term.periods = periods;
					term.oddDays = oddDays;

					// return object with results
					return term;
				}

				countMonthBasePeriods(fDate, lDate);
				term.days = days;
				term.periods = periods;
				term.oddDays = oddDays;

				// return object with results
				return term;

			}, // countPeriods_


			/**
			 * isValidDate - given a year, month, day
			 * month is not a JavaScript month, month range 1..12
			 * @nocollapse
			 */
			isValidDate = function (y, m, d) {
				var isValid = true;

				if (m < 1 || m > 12 || isNaN(m)) {
					//throw erInvalidMonth;
					isValid = false;
				}
				if (y < FC.MIN_YEAR || y > FC.MAX_YEAR || isNaN(y)) {
					//throw erInvalidYear;
					isValid = false;
				}
				if ((m === 9 || m === 4 || m === 6 || m === 11) && (d < 1 || d > 30)) {
					//throw erInvalidDay;
					isValid = false;
				} else if (m === 2) {
					// Feb. logic -- need check for leap year
					if (d < 1 || d > 29) {
						//throw erInvalidDay;
						isValid = false;
					}
				} else if (d < 1 || d > 31 || isNaN(d)) {
					//throw erInvalidDay;
					isValid = false;
				}
				return isValid;
			};

		/**
		 * [KT] 01/12/2020 - added support for new date masks
		 * dateStrToDate
		 * If successful, returns Date obj otherwise returns null.
		 * @nocollapse
		 */
		var dateStrToDate = function (dateStr, dateConventions) {
				var sep, date_format, datePartsStr = [],
					dateParts = [],
					dt = new Date();

				if (dateConventions) {
					date_format = dateConventions.date_format;
					sep = dateConventions.date_sep;
				} else {
					date_format = FC.dateConventions.date_format;
					sep = FC.dateConventions.date_sep;
				}

				// Note: not a 0 based month
				datePartsStr = dateStr.split(sep);
				dateParts[0] = parseInt(datePartsStr[0], 10);
				dateParts[1] = parseInt(datePartsStr[1], 10);
				dateParts[2] = parseInt(datePartsStr[2], 10);

				// isValidDate takes month range 1..12
				switch (date_format) {
				//		case "MM/DD/YYYY":
				case FC.DATE_FORMATS.MDY:
					if (isValidDate(dateParts[2], dateParts[0], dateParts[1])) {
						dt = new Date(dateParts[2], dateParts[0] - 1, dateParts[1], 0, 0, 0, 0);
					}
					break;
					//		case "DD/MM/YYYY":
				case FC.DATE_FORMATS.DMY:
				case FC.DATE_FORMATS.DMY2:
				case FC.DATE_FORMATS.DMY3:
					if (isValidDate(dateParts[2], dateParts[1], dateParts[0])) {
						dt = new Date(dateParts[2], dateParts[1] - 1, dateParts[0], 0, 0, 0, 0);
					}
					break;
					//		case "YYYY-MM-DD":
				case FC.DATE_FORMATS.YMD:
				case FC.DATE_FORMATS.YMD2:
				case FC.DATE_FORMATS.YMD3:
					if (isValidDate(dateParts[0], dateParts[1], dateParts[2])) {
						dt = new Date(dateParts[0], dateParts[1] - 1, dateParts[2], 0, 0, 0, 0);
					}
					break;
				}
				return dt;
			},


			/**
			 * dateToDateStr
			 * Validate date within range and return string based on date_format.
			 * TODO: If invalid, return today's date as string?
			 * @nocollapse
			 */
			dateToDateStr = function (date, dateConventions) {
				var sep, date_format, dateStr, d, m, y = date.getFullYear();

				if (y < FC.MIN_YEAR || y > FC.MAX_YEAR) {
					throw erInvalidYear;
				}

				if (dateConventions) {
					date_format = dateConventions.date_format;
					sep = dateConventions.date_sep;
				} else {
					date_format = FC.dateConventions.date_format;
					sep = FC.dateConventions.date_sep;
				}

				// guarantees leading 0 if needed
				m = ('0' + (date.getMonth() + 1)).slice(-2);
				d = ('0' + date.getDate()).slice(-2);

				switch (date_format) {
				case FC.DATE_FORMATS.MDY:
					dateStr = m + sep + d + sep + y;
					break;
				case FC.DATE_FORMATS.DMY:
				case FC.DATE_FORMATS.DMY2:
				case FC.DATE_FORMATS.DMY3:
					dateStr = d + sep + m + sep + y;
					break;
				case FC.DATE_FORMATS.YMD:
				case FC.DATE_FORMATS.YMD2:
				case FC.DATE_FORMATS.YMD3:
					dateStr = y + sep + m + sep + d;
					break;
				}
				return dateStr;
			}; // dateToDateStr

		// returns first of next month
		var getFirstNextMonth_ = function (aDate) {
			// when no date parameter, date defaults to 1
			return new Date(aDate.getFullYear(), aDate.getMonth() + 1);
		};

		return {
			/** @nocollapse */
			addPeriods: addPeriods_,
			/** @nocollapse */
			countPeriods: countPeriods_,
			/** @nocollapse */
			isValidDate: isValidDate,
			/** @nocollapse */
			dateStrToDate: dateStrToDate,
			/** @nocollapse */
			dateToDateStr: dateToDateStr,
			/** @nocollapse */
			getFirstNextMonth: getFirstNextMonth_
		};

	}()); // dateMath


	// Read cookies - not needed here since uses user's international settings first


	/**
	 * Constructs the object
	 * @constructor
	 * @param {number} inc
	 * @return {Object}
	 */
	var Vector = function (inc) {
		if (inc === undefined || inc === 0) {
			inc = 100;
		}

		/* Properties */
		this.data = [];
		this.increment = inc;
		this.size = 0; // misnomer, actually after trimToSize() this is the last index, BUG: property changes meaning

	};

	FC['Vector'] = Vector;
	FC.Vector = Vector;


	// Number of elements the vector can hold
	Vector.prototype.getCapacity = function () {
		return this.data.length;
	};

	// Current size of the vector
	Vector.prototype.getSize = function () {
		return this.size;
	};


	// Checks to see if the Vector has any elements
	Vector.prototype.isEmpty = function () {
		return this.getSize() === 0;
	};


	// Returns the last element
	Vector.prototype.getLastElement = function () {
		if (this.data[this.getSize() - 1] !== null) {
			return this.data[this.getSize() - 1];
		}
	};


	// Returns the first element
	Vector.prototype.getFirstElement = function () {
		if (this.data[0] !== null) {
			return this.data[0];
		}
	};



	/** @type {function(number):(Object|string)} */
	Vector.prototype.getElementAt = function (i) {
		try {
			return this.data[i];
		} catch (e) {
			// exception
			return 'Exception ' + e + ' occurred when accessing ' + i;
		}
	};


	Vector.prototype.addElement = function (obj) {
		this.data[this.size] = obj; // 2 line construct replaces original to eliminate JSLint error
		this.size += 1;
	};


	// Inserts an element at a given position
	Vector.prototype.insertElementAt = function (obj, index) {
		var i;

		try {
			// 11/09/14 - corrected bug in original code. No 'capacity' property.
			if (this.size === this.getCapacity()) {
				this.resize();
			}

			for (i = this.getSize(); i > index; i -= 1) {
				this.data[i] = this.data[i - 1];
			}
			this.data[index] = obj;
			this.size += 1;
		} catch (e) {
			// exception
			return 'Invalid index ' + i;
		}
	};


	// Removes an element at a specific index
	Vector.prototype.removeElementAt = function (index) {
		var i, element;

		try {
			element = this.data[index];

			for (i = index; i < this.getSize() - 1; i += 1) {
				this.data[i] = this.data[i + 1];
			}

			this.data[this.getSize() - 1] = null;
			this.size -= 1;
			return element;
		} catch (e) {
			// exception
			return 'Invalid index ' + index;
		}
	};


	// Removes all elements in the Vector
	Vector.prototype.removeAllElements = function () {
		var i;

		this.size = 0;

		for (i = 0; i < this.data.length; i += 1) {
			this.data[i] = null;
		}
	};


	// Get the index of a searched element
	Vector.prototype.indexOf = function (obj) {
		var i;

		for (i = 0; i < this.getSize(); i += 1) {
			if (this.data[i] === obj) {
				return i;
			}
		}
		return -1;
	};


	// true if the element is in the Vector
	Vector.prototype.contains = function (obj) {
		var i;

		for (i = 0; i < this.getSize(); i += 1) {
			if (this.data[i] === obj) {
				return true;
			}
		}
		return false;
	};


	// resize() -- increases the size of the Vector
	Vector.prototype.resize = function () {
		var i,
			newData = [];

		for (i = 0; i < this.data.length; i += 1) {
			newData[i] = this.data[i];
		}

		this.data = newData;
	};


	// trimToSize() -- trims the vector down to it's size
	Vector.prototype.trimToSize = function () {
		var i,
			// temp = new Array(this.getSize());
			temp = [];

		for (i = 0; i < this.getSize(); i += 1) {
			temp[i] = this.data[i];
		}
		// [KT] 11/13/2015, bug fix, schedule_and_charts will have to have calls to getSize() adjusted by one
		this.size = temp.length;
		this.data = temp;
	};


	// sort() - sorts the collection based on a field name - f
	Vector.prototype.sort = function (f) {
		var i, j, currentValue, currentObj, compareObj, compareValue;

		for (i = 1; i < this.getSize(); i += 1) {
			currentObj = this.data[i];
			currentValue = currentObj[f];

			j = i - 1;
			compareObj = this.data[j];
			compareValue = compareObj[f];

			while (j >= 0 && compareValue > currentValue) {
				this.data[j + 1] = this.data[j];
				j -= 1;
				if (j >= 0) {
					compareObj = this.data[j];
					compareValue = compareObj[f];
				}
			}
			this.data[j + 1] = currentObj;
		}
	};


	/**
	 * A deep cloning
	 */
	Vector.prototype.clone = function () {
		var i, newVector = new FC.Vector(this.size);

		for (i = 0; i < this.size; i += 1) {
			if (typeof this.data[i].clone === 'function') {
				newVector.addElement(this.data[i].clone());
			} else {
				newVector.addElement(this.data[i]); // warning: potentially not a clone!
			}
		}
		return newVector;
	};


	// toString() -- returns a string rep. of the Vector
	Vector.prototype.toString = function () {
		var i, prop, obj = {},
			str =
			'Vector Object properties:\n' +
			'Increment: ' +
			this.increment +
			'\n' +
			'Size: ' +
			this.size +
			'\n' +
			'Elements:\n';

		for (i = 0; i < this.getSize(); i += 1) {
			for (prop in this.data[i]) {
				if (this.data[i].hasOwnProperty(prop)) {
					obj = this.data[i];
					str += '\tObject.' + prop + ' = ' + obj[prop] + '\n';
				}
			}
		}
		return str;
	};


	// overwriteElementAt() - overwrites the element with an object at the specific index.
	Vector.prototype.overwriteElementAt = function (obj, index) {
		this.data[index] = obj;
	};

	return FC;
}());
