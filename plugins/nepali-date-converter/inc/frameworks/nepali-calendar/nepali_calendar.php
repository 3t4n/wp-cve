<?php
/*
=== Nepali Date Converter ===
Contributors: addonspress, acmeit, codersantosh
Donate link: https://www.addonspress.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Day of Month
d 	Numeric, with leading zeros 	01–31
j 	Numeric, without leading zeros 	1–31
S 	The English suffix for the day of the month 	st, nd or th in the 1st, 2nd or 15th.//This is not supported

Weekday
l 	Full name  (lowercase 'L') 	Sunday – Saturday
D 	Three letter name 	Mon – Sun

Month
m 	Numeric, with leading zeros 	01–12
n 	Numeric, without leading zeros 	1–12
F 	Textual full 	January – December
M 	Textual three letters 	Jan - Dec

Year
Y 	Numeric, 4 digits 	Eg., 1999, 2003
y 	Numeric, 2 digits 	Eg., 99, 03

*/

if ( ! class_exists( 'NDC_Nepali_Calendar' ) ){
	class NDC_Nepali_Calendar {
		/*added*/
		/**
		 * Variable to hold english nepali number
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $eng_nep_num	= array(
			'0'=>'०',
			'1'=>'१',
			'2'=>'२',
			'3'=>'३',
			'4'=>'४',
			'5'=>'५',
			'6'=>'६',
			'7'=>'७',
			'8'=>'८',
			'9'=>'९'
		);

		/**
		 * Variable to hold english nepali long months
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $long_eng_nep_mth = array(
			"January" => 'बैशाख',
			"February" => 'जेष्ठ',
			"March" => 'असार',
			"April" => 'साउन',
			"May" => 'भदौ',
			"June" => 'अशोज',
			"July" => 'कार्तिक',
			"August" => 'मंसिर',
			"September" => 'पुस',
			"October" => 'माघ',
			"November" => 'फाल्गुन',
			"December"=> 'चैत्र'
		);

		/**
		 * Variable to hold english nepali long months in english characters
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $long_eng_nep_mth_in_eng = array(
			"January" => 'Baishakh',
			"February" => 'Jestha',
			"March" => 'Ashad',
			"April" => 'Shrawan',
			"May" => 'Bhadra',
			"June" => 'Ashwin',
			"July" => 'Kartik',
			"August" => 'Mangshir',
			"September" => 'Poush',
			"October" => 'Magh',
			"November" => 'Falgun',
			"December"=> 'Chaitra'
		);

		/**
		 * Variable to hold english nepali short months
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $short_eng_nep_mth = array(
			"Jan" => 'बैशाख',
			"Feb" => 'जेष्ठ',
			"Mar" => 'असार',
			"Apr" => 'साउन',
			"May" => 'भदौ',
			"Jun" => 'अशोज',
			"Jul" => 'कार्तिक',
			"Aug" => 'मंसिर',
			"Sep" => 'पुस',
			"Oct" => 'माघ',
			"Nov" => 'फाल्गुन',
			"Dec" => 'चैत्र'
		);

		/**
		 * Variable to hold english nepali short months in english characters
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $short_eng_nep_mth_in_eng = array(
			"Jan" => 'Baishakh',
			"Feb" => 'Jestha',
			"Mar" => 'Ashad',
			"Apr" => 'Shrawan',
			"May" => 'Bhadra',
			"Jun" => 'Ashwin',
			"Jul" => 'Kartik',
			"Aug" => 'Mangshir',
			"Sep" => 'Poush',
			"Oct" => 'Magh',
			"Nov" => 'Falgun',
			"Dec"=> 'Chaitra'
		);

		/**
		 * Variable to hold english nepali long days of week
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $long_eng_nep_day = array(
			"Sunday" => 'आइतवार',
			"Monday" => 'सोमवार',
			"Tuesday" => 'मङ्लबार',
			"Wednesday" => 'बुधबार',
			"Thursday" => 'बिहिबार',
			"Friday" => 'शुक्रबार',
			"Saturday"=> 'शनिबार'
		);

		/**
		 * Variable to hold english nepali long days of week in english characters
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $long_eng_nep_day_eng = array(
			"Sunday" => 'Aaitabar',
			"Monday" => 'Sombar',
			"Tuesday" => 'Mangalbar',
			"Wednesday" => 'Budhabar',
			"Thursday" => 'Bihibar',
			"Friday" => 'Sukrabar',
			"Saturday"=> 'Sanibar'
		);

		/**
		 * Variable to hold english nepali short days of week
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $short_eng_nep_day = array(
			"Sun" => 'आइत',
			"Mon" => 'सोम',
			"Tue" => 'मङ्ल',
			"Wed" => 'बुध',
			"Thu" => 'बिहि',
			"Fri" => 'शुक्र',
			"Sat"=> 'शनि'
		);

		/**
		 * Variable to hold english nepali short days of week in english characters
		 *
		 * @var array
		 * @access public
		 * @since 1.0
		 *
		 */
		public $short_eng_nep_day_eng = array(
			"Sun" => 'Aaita',
			"Mon" => 'Som',
			"Tue" => 'Mangal',
			"Wed" => 'Budha',
			"Thu" => 'Bihi',
			"Fri" => 'Sukra',
			"Sat"=> 'Sani'
		);

		/*Some numeric functions for date format Start=====================*/
		private function getTwoCharStr($str){
			if(strlen($str) == 0){
				return '00';
			}
			if(strlen($str) == 1){
				return '0'.$str;
			}
			return  $str;
		}
		private function getNepMonthToNumeric($str){
			if(strlen($str) == 0){
				return '00';
			}
			if(strlen($str) == 1){
				return '0'.$str;
			}
			return  $str;
		}
		/*Some numeric functions for date format End=====================*/

		/*added*/

		/**
		 * Variable to hold Data for nepali date
		 * Hold year and number of days in each month nepali date
		 *
		 * @var array
		 * @access private
		 * @since 1.0
		 *
		 */
		private $_c_bs = array(
			0 => array(2000,30,32,31,32,31,30,30,30,29,30,29,31),
			1 => array(2001,31,31,32,31,31,31,30,29,30,29,30,30),
			2 => array(2002,31,31,32,32,31,30,30,29,30,29,30,30),
			3 => array(2003,31,32,31,32,31,30,30,30,29,29,30,31),
			4 => array(2004,30,32,31,32,31,30,30,30,29,30,29,31),
			5 => array(2005,31,31,32,31,31,31,30,29,30,29,30,30),
			6 => array(2006,31,31,32,32,31,30,30,29,30,29,30,30),
			7 => array(2007,31,32,31,32,31,30,30,30,29,29,30,31),
			8 => array(2008,31,31,31,32,31,31,29,30,30,29,29,31),
			9 => array(2009,31,31,32,31,31,31,30,29,30,29,30,30),
			10 => array(2010,31,31,32,32,31,30,30,29,30,29,30,30),
			11 => array(2011,31,32,31,32,31,30,30,30,29,29,30,31),
			12 => array(2012,31,31,31,32,31,31,29,30,30,29,30,30),
			13 => array(2013,31,31,32,31,31,31,30,29,30,29,30,30),
			14 => array(2014,31,31,32,32,31,30,30,29,30,29,30,30),
			15 => array(2015,31,32,31,32,31,30,30,30,29,29,30,31),
			16 => array(2016,31,31,31,32,31,31,29,30,30,29,30,30),
			17 => array(2017,31,31,32,31,31,31,30,29,30,29,30,30),
			18 => array(2018,31,32,31,32,31,30,30,29,30,29,30,30),
			19 => array(2019,31,32,31,32,31,30,30,30,29,30,29,31),
			20 => array(2020,31,31,31,32,31,31,30,29,30,29,30,30),
			21 => array(2021,31,31,32,31,31,31,30,29,30,29,30,30),
			22 => array(2022,31,32,31,32,31,30,30,30,29,29,30,30),
			23 => array(2023,31,32,31,32,31,30,30,30,29,30,29,31),
			24 => array(2024,31,31,31,32,31,31,30,29,30,29,30,30),
			25 => array(2025,31,31,32,31,31,31,30,29,30,29,30,30),
			26 => array(2026,31,32,31,32,31,30,30,30,29,29,30,31),
			27 => array(2027,30,32,31,32,31,30,30,30,29,30,29,31),
			28 => array(2028,31,31,32,31,31,31,30,29,30,29,30,30),
			29 => array(2029,31,31,32,31,32,30,30,29,30,29,30,30),
			30 => array(2030,31,32,31,32,31,30,30,30,29,29,30,31),
			31 => array(2031,30,32,31,32,31,30,30,30,29,30,29,31),
			32 => array(2032,31,31,32,31,31,31,30,29,30,29,30,30),
			33 => array(2033,31,31,32,32,31,30,30,29,30,29,30,30),
			34 => array(2034,31,32,31,32,31,30,30,30,29,29,30,31),
			35 => array(2035,30,32,31,32,31,31,29,30,30,29,29,31),
			36 => array(2036,31,31,32,31,31,31,30,29,30,29,30,30),
			37 => array(2037,31,31,32,32,31,30,30,29,30,29,30,30),
			38 => array(2038,31,32,31,32,31,30,30,30,29,29,30,31),
			39 => array(2039,31,31,31,32,31,31,29,30,30,29,30,30),
			40 => array(2040,31,31,32,31,31,31,30,29,30,29,30,30),
			41 => array(2041,31,31,32,32,31,30,30,29,30,29,30,30),
			42 => array(2042,31,32,31,32,31,30,30,30,29,29,30,31),
			43 => array(2043,31,31,31,32,31,31,29,30,30,29,30,30),
			44 => array(2044,31,31,32,31,31,31,30,29,30,29,30,30),
			45 => array(2045,31,32,31,32,31,30,30,29,30,29,30,30),
			46 => array(2046,31,32,31,32,31,30,30,30,29,29,30,31),
			47 => array(2047,31,31,31,32,31,31,30,29,30,29,30,30),
			48 => array(2048,31,31,32,31,31,31,30,29,30,29,30,30),
			49 => array(2049,31,32,31,32,31,30,30,30,29,29,30,30),
			50 => array(2050,31,32,31,32,31,30,30,30,29,30,29,31),
			51 => array(2051,31,31,31,32,31,31,30,29,30,29,30,30),
			52 => array(2052,31,31,32,31,31,31,30,29,30,29,30,30),
			53 => array(2053,31,32,31,32,31,30,30,30,29,29,30,30),
			54 => array(2054,31,32,31,32,31,30,30,30,29,30,29,31),
			55 => array(2055,31,31,32,31,31,31,30,29,30,29,30,30),
			56 => array(2056,31,31,32,31,32,30,30,29,30,29,30,30),
			57 => array(2057,31,32,31,32,31,30,30,30,29,29,30,31),
			58 => array(2058,30,32,31,32,31,30,30,30,29,30,29,31),
			59 => array(2059,31,31,32,31,31,31,30,29,30,29,30,30),
			60 => array(2060,31,31,32,32,31,30,30,29,30,29,30,30),
			61 => array(2061,31,32,31,32,31,30,30,30,29,29,30,31),
			62 => array(2062,30,32,31,32,31,31,29,30,29,30,29,31),
			63 => array(2063,31,31,32,31,31,31,30,29,30,29,30,30),
			64 => array(2064,31,31,32,32,31,30,30,29,30,29,30,30),
			65 => array(2065,31,32,31,32,31,30,30,30,29,29,30,31),
			66 => array(2066,31,31,31,32,31,31,29,30,30,29,29,31),
			67 => array(2067,31,31,32,31,31,31,30,29,30,29,30,30),
			68 => array(2068,31,31,32,32,31,30,30,29,30,29,30,30),
			69 => array(2069,31,32,31,32,31,30,30,30,29,29,30,31),
			70 => array(2070,31,31,31,32,31,31,29,30,30,29,30,30),
			71 => array(2071,31,31,32,31,31,31,30,29,30,29,30,30),
			72 => array(2072,31,32,31,32,31,30,30,29,30,29,30,30),
			73 => array(2073,31,32,31,32,31,30,30,30,29,29,30,31),
			74 => array(2074,31,31,31,32,31,31,30,29,30,29,30,30),
			75 => array(2075,31,31,32,31,31,31,30,29,30,29,30,30),
			76 => array(2076,31,32,31,32,31,30,30,30,29,29,30,30),
			77 => array(2077,31,32,31,32,31,30,30,30,29,30,29,31),
			78 => array(2078,31,31,31,32,31,31,30,29,30,29,30,30),
			79 => array(2079,31,31,32,31,31,31,30,29,30,29,30,30),
			80 => array(2080,31,32,31,32,31,30,30,30,29,29,30,30),
			81 => array(2081,31,31,32,32,31,30,30,30,29,30,30,30),
			82 => array(2082,30,32,31,32,31,30,30,30,29,30,30,30),
			83 => array(2083,31,31,32,31,31,30,30,30,29,30,30,30),
			84 => array(2084,31,31,32,31,31,30,30,30,29,30,30,30),
			85 => array(2085,31,32,31,32,30,31,30,30,29,30,30,30),
			86 => array(2086,30,32,31,32,31,30,30,30,29,30,30,30),
			87 => array(2087,31,31,32,31,31,31,30,30,29,30,30,30),
			88 => array(2088,30,31,32,32,30,31,30,30,29,30,30,30),
			89 => array(2089,30,32,31,32,31,30,30,30,29,30,30,30),
			90 => array(2090,30,32,31,32,31,30,30,30,29,30,30,30)
		);

		/**
		 * Variable to hold english nepali short months
		 *
		 * @var array
		 * @access private
		 * @since 1.0
		 *
		 */
		private $date_data = array(
			'eng_date'=>array(
				'year'=>'',
				'month'=>'',
				'date'=>''
			),
			'nep_date'=>array(
				'year'=>'',
				'month'=>'',
				'date'=>''
			),
			'from'=>'',
			'result'=>'',

		);
		/*added*/

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 2.0.0
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;

		}

		/**
		 * Check if date range is in english
		 *
		 * @param int $yy
		 * @param int $mm
		 * @param int $dd
		 * @return bool|string
		 */
		private function _is_in_range_eng($yy, $mm, $dd) {
			if ($yy < 1944 || $yy > 2033) {
				return __("Supported only between 1944-2022",'nepali-date-converter');
			}

			if ($mm < 1 || $mm > 12) {
				return __("Error! month value can be between 1-12 only",'nepali-date-converter');
			}

			if ($dd < 1 || $dd > 31) {
				return __("Error! day value can be between 1-31 only",'nepali-date-converter');
			}

			return TRUE;
		}

		/**
		 * Check if date is with in nepali data range
		 *
		 * @param int $yy
		 * @param int $mm
		 * @param int $dd
		 * @return bool
		 */
		private function _is_in_range_nep($yy, $mm, $dd) {
			if ($yy < 2000 || $yy > 2089) {
				return __("Supported only between 2000-2089",'nepali-date-converter');
			}

			if ($mm < 1 || $mm > 12) {
				return __("Error! month value can be between 1-12 only",'nepali-date-converter');
			}

			if ($dd < 1 || $dd > 32) {
				return __("Error! day value can be between 1-31 only",'nepali-date-converter');
			}

			return TRUE;
		}

		/**
		 * Calculates wheather english year is leap year or not
		 *
		 * @param int $year
		 * @return bool
		 */
		public function is_leap_year($year) {
			$a = $year;
			if ($a % 100 == 0) {
				if ($a % 400 == 0)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
			else {
				if ($a % 4 == 0)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}

		/**
		 * currently can only calculate the date between AD 1944-2033...
		 *
		 * @param int $yy
		 * @param int $mm
		 * @param int $dd
		 * @return array
		 */
		public function eng_to_nep( $input_date = array(),$date_format = 'nep_char', $format = 'D, F j, Y' ) {

			// Check for date range
			$yy = $input_date['year'];
			$mm = $input_date['month'];
			$dd = $input_date['day'];

			$chk = $this->_is_in_range_eng($yy, $mm, $dd);

			if($chk !== TRUE) {
				die($chk);
			}
			else {

				$this->date_data['from'] = 'eng';
				$this->date_data['eng_date']['year']= $yy;
				$this->date_data['eng_date']['month']= $mm;
				$this->date_data['eng_date']['date']= $dd;
				// Month data.
				$month  = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

				// Month for leap year
				$lmonth = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

				$def_eyy     = 1944;	// initial english date.
				$def_nyy     = 2000;
				$def_nmm     = 9;
				$def_ndd     = 17 - 1;	// inital nepali date.
				$total_eDays = 0;
				$np_date = 0;
				$a           = 0;
				$day         = 7 - 1;
				$m           = 0;
				$y           = 0;
				$i           = 0;
				$j           = 0;
				$numDay      = 0;

				// Count total no. of days in-terms year
				for ($i = 0; $i < ($yy - $def_eyy); $i++) //total days for month calculation...(english)
				{
					if ($this->is_leap_year($def_eyy + $i) === TRUE)
					{
						for ($j = 0; $j < 12; $j++)
						{
							$total_eDays += $lmonth[$j];
						}
					}
					else
					{
						for ($j = 0; $j < 12; $j++)
						{
							$total_eDays += $month[$j];
						}
					}
				}

				// Count total no. of days in-terms of month
				for ($i = 0; $i < ($mm - 1); $i++)
				{
					if ($this->is_leap_year($yy) === TRUE)
					{
						$total_eDays += $lmonth[$i];
					}
					else
					{
						$total_eDays += $month[$i];
					}
				}

				// Count total no. of days in-terms of date
				$total_eDays += $dd;


				$i           = 0;
				$j           = $def_nmm;
				$np_date = $def_ndd;
				$m           = $def_nmm;
				$y           = $def_nyy;

				// Count nepali date from array
				while ($total_eDays != 0)
				{
					$a = $this->_c_bs[$i][$j];

					$np_date++;		//count the days
					$day++;				//count the days interms of 7 days

					if ($np_date > $a)
					{
						$m++;
						$np_date = 1;
						$j++;
					}

					if ($day > 7)
					{
						$day = 1;
					}

					if ($m > 12)
					{
						$y++;
						$m = 1;
					}

					if ($j > 12)
					{
						$j = 1;
						$i++;
					}

					$total_eDays--;
				}

				/*Date Formatting
				 * no supported
				S, c, r, U
				 may be c= $y.'-'.$m.'-'.$np_date.'स'.date('H',$create_date).':'.date('i',$create_date).':'.date('s',$create_date).'+00:00',
				*/
				/*Optional*/
				$hour = isset($input_date['hour'])?$input_date['hour']:'';
				$min = isset($input_date['min'])?$input_date['min']:'';
				$sec = isset($input_date['sec'])?$input_date['sec']:'';

				$ndcDateStr = $yy.'-'.$mm.'-'.$dd;
				if( $hour){
					$ndcDateStr .= ' '.$hour;
				}
				if( $min){
					$ndcDateStr .= ':'.$min;
				}
				if( $sec){
					$ndcDateStr .= ':'.$sec;
				}

				$create_date = strtotime( $ndcDateStr );

				$ndp_format = array(
					'd' =>$this->getTwoCharStr($np_date),/*add 0 to front if one character*/
					'j' =>$np_date,/*always j */
					'l' =>date('l',$create_date),
					'D' =>date('D',$create_date),
					'm' =>$this->getTwoCharStr($m),
					'n' =>$m,
					'F' =>array_keys($this->long_eng_nep_mth)[(int)$m-1],
					'M' =>array_keys($this->short_eng_nep_mth)[(int)$m-1],
					'Y' =>$y,
					'y' =>substr($y, 2),
					'a' =>date('a',$create_date),
					'A' =>date('A',$create_date),
					'g' =>date('g',$create_date),
					'h' =>date('h',$create_date),
					'G' =>date('G',$create_date),
					'H' =>date('H',$create_date),
					'i' =>date('i',$create_date),
					's' =>date('s',$create_date),
					'T' =>date('T',$create_date),
					'c' =>$y.'-'.$m.'-'.$np_date.'स'.date('H',$create_date).':'.date('i',$create_date).':'.date('s',$create_date).'+00:00',
					'r' =>date('D',$create_date).', '.$np_date.' '.array_keys($this->short_eng_nep_mth)[(int)$m-1].' '.$y.' '.date('H',$create_date).':'.date('i',$create_date).':'.date('s',$create_date).'+0200'
				);

				$nepali_date = strtr( $format, $ndp_format );

				/*current actual date stored as temp*/
				$temp_long_actual_nepali_day = 'coder'.$day.'nepal';
				$temp_short_actual_nepali_day = 'coder'.$day.'nep';
				$temp_long_eng_nep_day = array(
					"Sunday" => $temp_long_actual_nepali_day,
					"Monday" => $temp_long_actual_nepali_day,
					"Tuesday" => $temp_long_actual_nepali_day,
					"Wednesday" => $temp_long_actual_nepali_day,
					"Thursday" => $temp_long_actual_nepali_day,
					"Friday" => $temp_long_actual_nepali_day,
					"Saturday"=> $temp_long_actual_nepali_day
				);
				$temp_short_eng_nep_day = array(
					"Sun" => $temp_short_actual_nepali_day,
					"Mon" => $temp_short_actual_nepali_day,
					"Tue" => $temp_short_actual_nepali_day,
					"Wed" => $temp_short_actual_nepali_day,
					"Thu" => $temp_short_actual_nepali_day,
					"Fri" => $temp_short_actual_nepali_day,
					"Sat"=> $temp_short_actual_nepali_day
				);

				if( 'eng_char' == $date_format ){
					$nepali_date = strtr( $nepali_date, $this->long_eng_nep_mth_in_eng );
					$nepali_date = strtr( $nepali_date, $this->short_eng_nep_mth_in_eng );

					$actual_long_nepali_day = array(
						"coder1nepal" => 'Aaitabar',
						"coder2nepal" => 'Sombar',
						"coder3nepal" => 'Mangalbar',
						"coder4nepal" => 'Budhabar',
						"coder5nepal" => 'Bihibar',
						"coder6nepal" => 'Sukrabar',
						"coder7nepal"=> 'Sanibar'
					);
					$actual_short_nepali_day = array(
						"coder1nep" => 'Aaita',
						"coder2nep" => 'Som',
						"coder3nep" => 'Mangal',
						"coder4nep" => 'Budha',
						"coder5nep" => 'Bihi',
						"coder6nep" => 'Sukra',
						"coder7nep"=> 'Sani'
					);
					/*changing the day*/
					$nepali_date = strtr( $nepali_date, $temp_long_eng_nep_day );
					$nepali_date = strtr( $nepali_date, $temp_short_eng_nep_day );
					$nepali_date = strtr( $nepali_date, $actual_long_nepali_day );
					$nepali_date = strtr( $nepali_date, $actual_short_nepali_day );

					/*AM/PM*/
					$amPmTranslate = array(
						"am" => 'Bihana',
						"pm" => 'Madyana',
					);
					$nepali_date = strtr( $nepali_date, $amPmTranslate );
				}
				else{
					$nepali_date = strtr( $nepali_date, $this->eng_nep_num );
					$nepali_date = strtr( $nepali_date, $this->long_eng_nep_mth );
					$nepali_date = strtr( $nepali_date, $this->short_eng_nep_mth );

					$actual_long_nepali_day = array(
						"coder1nepal" => 'आइतवार',
						"coder2nepal" => 'सोमवार',
						"coder3nepal" => 'मङ्लबार',
						"coder4nepal" => 'बुधबार',
						"coder5nepal" => 'बिहिबार',
						"coder6nepal" => 'शुक्रबार',
						"coder7nepal"=> 'शनिबार'
					);
					$actual_short_nepali_day = array(
						"coder1nep" => 'आइत',
						"coder2nep" => 'सोम',
						"coder3nep" => 'मङ्ल',
						"coder4nep" => 'बुध',
						"coder5nep" => 'बिहि',
						"coder6nep" => 'शुक्र',
						"coder7nep"=> 'शनि'
					);

					/*changing the day*/
					$nepali_date = strtr( $nepali_date, $temp_long_eng_nep_day );
					$nepali_date = strtr( $nepali_date, $temp_short_eng_nep_day );
					$nepali_date = strtr( $nepali_date, $actual_long_nepali_day );
					$nepali_date = strtr( $nepali_date, $actual_short_nepali_day );

					/*AM/PM*/
					$amPmTranslate = array(
						"am" => 'बिहान',
						"pm" => 'मध्यान्ह',
					);
					$nepali_date = strtr( $nepali_date, $amPmTranslate );
				}

				$this->date_data['nep_date']['year']= $y;
				$this->date_data['nep_date']['month']= $m;
				$this->date_data['nep_date']['date']= $np_date;
				$this->date_data['result']= $nepali_date;
				return $this->date_data;
			}
		}


		/**
		 * Currently can only calculate the date between BS 2000-2089
		 *
		 * @param int $yy
		 * @param int $mm
		 * @param int $dd
		 * @return array
		 */
		public function nep_to_eng($input_date = array() ,$format = 'D, F j, Y' ) {
			// Check for date range
			$yy = $input_date['year'];
			$mm = $input_date['month'];
			$dd = $input_date['day'];
			$def_eyy     = 1943;
			$def_emm     = 4;
			$def_edd     = 14 - 1;	// initial english date.
			$def_nyy     = 2000;
			$def_nmm     = 1;
			$def_ndd     = 1;		// iniital equivalent nepali date.
			$total_eDays = 0;
			$np_date = 0;
			$a           = 0;
			$day         = 4 - 1;
			$m           = 0;
			$y           = 0;
			$i           = 0;
			$k           = 0;
			$numDay      = 0;

			$month  = array(
				0,
				31,
				28,
				31,
				30,
				31,
				30,
				31,
				31,
				30,
				31,
				30,
				31
			);
			$lmonth = array(
				0,
				31,
				29,
				31,
				30,
				31,
				30,
				31,
				31,
				30,
				31,
				30,
				31
			);

			// Check for date range
			$chk = $this->_is_in_range_nep($yy, $mm, $dd);

			if ( $chk !== TRUE)
			{
				die($chk);
			}
			else
			{
				$this->date_data['from'] = 'nep';
				$this->date_data['nep_date']['year']= $yy;
				$this->date_data['nep_date']['month']= $mm;
				$this->date_data['nep_date']['date']= $dd;

				// Count total days in-terms of year
				for ($i = 0; $i < ($yy - $def_nyy); $i++)
				{
					for ($j = 1; $j <= 12; $j++)
					{
						$np_date += $this->_c_bs[$k][$j];
					}
					$k++;
				}

				// Count total days in-terms of month
				for ($j = 1; $j < $mm; $j++)
				{
					$np_date += $this->_c_bs[$k][$j];
				}

				// Count total days in-terms of dat
				$np_date += $dd;

				// Calculation of equivalent english date...
				$total_eDays = $def_edd;
				$m           = $def_emm;
				$y           = $def_eyy;
				while ($np_date != 0)
				{
					if ($this->is_leap_year($y))
					{
						$a = $lmonth[$m];
					}
					else
					{
						$a = $month[$m];
					}

					$total_eDays++;
					$day++;

					if ($total_eDays > $a)
					{
						$m++;
						$total_eDays = 1;
						if ($m > 12)
						{
							$y++;
							$m = 1;
						}
					}

					if ($day > 7)
					{
						$day = 1;
					}

					$np_date--;
				}

				$date_formatting_string = $y.'-'.$m.'-'.$total_eDays;
				$create_date = date_create( $date_formatting_string );
				$formatted_date = date_format( $create_date, $format );

				$this->date_data['eng_date']['year']= $y;
				$this->date_data['eng_date']['month']= $m;
				$this->date_data['eng_date']['date']= $total_eDays;
				$this->date_data['result']= $formatted_date;

				return $this->date_data;
			}
		}
	}
}

/**
 * Return Instance
 * NDC_Nepali_Calendar
 *
 * @since    2.0.0
 */
if( !function_exists( 'ndc_nepali_calendar')){

	function ndc_nepali_calendar() {

		return NDC_Nepali_Calendar::get_instance();
	}
}