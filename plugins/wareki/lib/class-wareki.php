<?php
/**
 * Wareki
 *
 * @package    Wareki
 * @subpackage Wareki Main function
/*
	Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$wareki = new Wareki();

/** ==================================================
 * Main Functions
 */
class Wareki {

	/** ==================================================
	 * Gengo List
	 *
	 * @var $gengo_list  gengo_list.
	 */
	private $gengo_list;

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		$this->gengo_list = array(
			array(
				'name' => '明治',
				'name_short' => 'M',
				'timestamp' => -3216790800,
			),
			array(
				'name' => '大正',
				'name_short' => 'T',
				'timestamp' => -1812186000,
			),
			array(
				'name' => '昭和',
				'name_short' => 'S',
				'timestamp' => -1357635600,
			),
			array(
				'name' => '平成',
				'name_short' => 'H',
				'timestamp' => 600188400,
			),
			array(
				'name' => '令和',
				'name_short' => 'R',
				'timestamp' => 1556636400,
			),
		);

		if ( ! function_exists( 'wp_date' ) ) {
			/* WordPress 5.3.0 previous */
			foreach ( $this->gengo_list as $key ) {
				$this->gengo_list[ $key ]['timestamp'] = $this->gengo_list[ $key ]['timestamp'] + 32400;
			}
		}

		add_filter( 'date_formats', array( $this, 'wareki_date_formats' ), 10, 1 );
		add_filter( 'time_formats', array( $this, 'wareki_time_formats' ), 10, 1 );

		if ( function_exists( 'wp_date' ) ) {
			/* WordPress 5.3.0 later */
			add_filter( 'wp_date', array( $this, 'wareki_date_i18n' ), 10, 4 );
		} else {
			add_filter( 'date_i18n', array( $this, 'wareki_date_i18n' ), 10, 4 );
		}

		add_filter( 'pre_get_document_title', array( $this, 'wareki_archive_title_tag' ), 11 );
		add_filter( 'get_the_archive_title', array( $this, 'wareki_archive_title' ), 999 );
		add_filter( 'get_archives_link', array( $this, 'wareki_archives_link' ), 10, 6 );
		add_filter( 'get_calendar', array( $this, 'wareki_calendar' ) );
	}

	/** ==================================================
	 * Date Format Hook
	 *
	 * @param array $date_array  date_array.
	 * @since 1.10
	 */
	public function wareki_date_formats( $date_array ) {

		$add_array = array( 'K年n月j日', 'k年n月j日' );

		return array_merge( $date_array, $add_array );
	}

	/** ==================================================
	 * Date Time Hook
	 *
	 * @param array $time_array  time_array.
	 * @since 1.10
	 */
	public function wareki_time_formats( $time_array ) {

		$add_array = array( 'Eg時i分' );

		return array_merge( $time_array, $add_array );
	}

	/** ==================================================
	 * Wareki date_i18n Hook
	 *
	 * @param string $j  j.
	 * @param string $req_format  req_format.
	 * @param int    $i  i.
	 * @param bool   $gmt  gmt.
	 *        DateTimeZone $timezone  Timezone.
	 * @return string $j
	 * @since 1.10
	 */
	public function wareki_date_i18n( $j, $req_format, $i, $gmt ) {

		if ( strpos( $req_format, 'K' ) !== false ) {
			$j = $this->gengo_date_i18n( $i, $j, 'K' );
		}

		if ( strpos( $req_format, 'k' ) !== false ) {
			$j = $this->gengo_date_i18n( $i, $j, 'k' );
		}

		if ( strpos( $req_format, 'E' ) !== false ) {
			$j = $this->ampm_date_i18n( $i, $j );
		}

		return $j;
	}

	/** ==================================================
	 * Archive Title Hook
	 *
	 * @param string $title  title.
	 * @return string $title
	 * @since 1.01
	 */
	public function wareki_archive_title( $title ) {

		if ( is_year() || is_month() || is_day() ) {
			return $this->wareki_archive( $title );
		}
	}

	/** ==================================================
	 * Archive Title Tag Hook
	 *
	 * @param string $title  title.
	 * @return string $title
	 * @since 1.01
	 */
	public function wareki_archive_title_tag( $title ) {

		if ( is_year() || is_month() || is_day() ) {
			return $this->wareki_archive( $title ) . ' &#8211; ' . get_option( 'blogname' );
		}
	}

	/** ==================================================
	 * Archive Link Hook
	 *
	 * @param string $link_html  link_html.
	 * @param string $url  url.
	 * @param string $text  text.
	 * @param string $format  format.
	 * @param string $before  before.
	 * @param string $after  after.
	 * @return string $html
	 * @since 1.01
	 */
	public function wareki_archives_link( $link_html, $url, $text, $format, $before, $after ) {

		if ( 'html' == $format || 'option' == $format ) {
			$url2 = untrailingslashit( $url );
			$str = substr( $url2, -7 );
			if ( strpos( $str, '/' ) !== false ) {
				$year = intval( substr( $str, 0, 4 ) );
				$month = intval( substr( $str, 5, 2 ) );
			} else {
				$str = substr( $url2, -6 );
				$year = intval( substr( $str, 0, 4 ) );
				$month = intval( substr( $str, 4, 2 ) );
			}
			$year_text = $this->gengo_archive( $year, $month );
			if ( 'html' == $format ) {
				$link_html = "\t" . '<li><a href=' . $url . '>' . $year_text . $month . '月</a></li>' . "\n";
			} elseif ( 'option' == $format ) {
				$link_html = "\t" . '<option value=' . $url . '>' . $year_text . $month . '月</option>' . "\n";
			}
		}

		return $link_html;
	}

	/** ==================================================
	 * Calendar Title
	 *
	 * @param  string $calendar  calendar.
	 * @return string $calendar
	 * @since 1.02
	 */
	public function wareki_calendar( $calendar ) {

		global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;

		/* week_begins = 0 stands for Sunday */
		$week_begins = intval( get_option( 'start_of_week' ) );

		/* Let's figure out when we are */
		if ( ! empty( $monthnum ) && ! empty( $year ) ) {
			$thismonth = '' . zeroise( intval( $monthnum ), 2 );
			$thisyear = '' . intval( $year );
		} elseif ( ! empty( $w ) ) {
			/* We need to get the month from MySQL */
			$thisyear = '' . intval( substr( $m, 0, 4 ) );
			$d = ( ( $w - 1 ) * 7 ) + 6; /* it seems MySQL's weeks disagree with PHP's */
			$wpdb->thisyear = $thisyear;
			$wpdb->intervalday = $d;
			$thismonth = $wpdb->get_var( "SELECT DATE_FORMAT((DATE_ADD('{$wpdb->thisyear}0101', INTERVAL $wpdb->intervalday DAY) ), '%m')" );
		} elseif ( ! empty( $m ) ) {
			$thisyear = '' . intval( substr( $m, 0, 4 ) );
			if ( 6 > strlen( $m ) ) {
				$thismonth = '01';
			} else {
				$thismonth = '' . zeroise( intval( substr( $m, 4, 2 ) ), 2 );
			}
		} else {
			$thisyear = gmdate( 'Y', time() );
			$thismonth = gmdate( 'm', time() );
		}

		$thisyear = intval( $thisyear );
		$thismonth = intval( $thismonth );
		$def_title = $thisyear . '年' . $thismonth . '月';

		$thisyear = $this->gengo_archive( $thisyear, $thismonth );
		$title = $thisyear . $thismonth . '月';

		$calendar = str_replace( $def_title, $title, $calendar );

		return $calendar;
	}

	/** ==================================================
	 * Archive Title
	 *
	 * @param string $title  title.
	 * @return string $title
	 * @since 1.01
	 */
	private function wareki_archive( $title ) {

		list($year, $month, $day) = $this->query_get();

		if ( is_year() ) {
			/* translators: Year */
			$title = sprintf( __( 'Year: %s' ), $this->gengo_archive( $year, 0 ) );
		} else if ( is_month() ) {
			/* translators: Month */
			$title = sprintf( __( 'Month: %s' ), $this->gengo_archive( $year, $month ) . $month . '月' );
		} else if ( is_day() ) {
			/* translators: Day */
			$title = sprintf( __( 'Day: %s' ), $this->gengo_archive( $year, $month ) . $month . '月' . $day . '日' );
		}

		return $title;
	}

	/** ==================================================
	 * Gengo date_i18n
	 *
	 * @param int    $timestamp  timestamp.
	 * @param string $j  j.
	 * @param string $k  k.
	 * @return string $j
	 * @since 1.10
	 */
	private function gengo_date_i18n( $timestamp, $j, $k ) {

		$gengo_name      = $this->gengo_list[0]['name'];
		$gengo_short     = $this->gengo_list[0]['name_short'];
		$gengo_timestamp = $this->gengo_list[0]['timestamp'];
		foreach ( $this->gengo_list as $key => $value ) {
			if ( $timestamp >= $value['timestamp'] ) {
				$gengo_name = $value['name'];
				if ( array_key_exists( 'name_short', $value ) ) {
					$gengo_short = $value['name_short'];
				} else {
					$gengo_short = $value['name'];
				}
				$gengo_timestamp = $value['timestamp'];
			}
		}

		if ( function_exists( 'wp_date' ) ) {
			/* WordPress 5.3.0 later */
			$year = intval( wp_date( 'Y', $timestamp ) );
			$gengo_year = intval( wp_date( 'Y', $gengo_timestamp ) );
		} else {
			$year = intval( date_i18n( 'Y', $timestamp ) );
			$gengo_year = intval( date_i18n( 'Y', $gengo_timestamp ) );
		}

		$y = $year - $gengo_year + 1;
		if ( 1 == $y ) {
			$y = '元';
		}
		if ( 'K' == $k ) {
			$gengo = $gengo_name;
		} else if ( 'k' == $k ) {
			$gengo = $gengo_short;
		}
		$j = str_replace( $k, $gengo . $y, $j );

		return $j;
	}

	/** ==================================================
	 * AM PM date_i18n
	 *
	 * @param int    $timestamp  timestamp.
	 * @param string $j  j.
	 * @return string $j
	 * @since 1.10
	 */
	private function ampm_date_i18n( $timestamp, $j ) {

		if ( function_exists( 'wp_date' ) ) {
			/* WordPress 5.3.0 later */
			$ampm = wp_date( 'a', $timestamp );
		} else {
			$ampm = date_i18n( 'a', $timestamp );
		}
		if ( 'am' == $ampm ) {
			$e = '午前';
		} else {
			$e = '午後';
		}

		$j = str_replace( 'E', $e, $j );

		return $j;
	}

	/** ==================================================
	 * Archive Url Query
	 *
	 * @return array $year, $month, $day
	 * @since 1.01
	 */
	private function query_get() {

		global $wp_locale;

		$m = get_query_var( 'm' );
		$year = get_query_var( 'year' );
		$monthnum = get_query_var( 'monthnum' );

		$my_month = 0;
		$my_day = 0;
		if ( ! empty( $monthnum ) && ! empty( $year ) ) {
			$my_year = intval( $year );
			$my_month = intval( $wp_locale->get_month( $monthnum ) );
			if ( isset( $_SERVER['HTTP_HOST'] ) && ! empty( $_SERVER['HTTP_HOST'] ) ) {
				$host = sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
			} else {
				return;
			}
			$uri = null;
			if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
				$uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			}
			$full_url = 'http://' . $host . $uri;
			if ( is_ssl() ) {
				$full_url = str_replace( 'http:', 'https:', $full_url );
			}
			$path = untrailingslashit( str_replace( home_url() . '/', '', $full_url ) );
			$path_arr = explode( '/', $path );
			if ( ! empty( $path_arr[2] ) ) {
				$my_day = intval( $path_arr[2] );
			}
		} elseif ( ! empty( $m ) ) {
			$my_year = intval( substr( $m, 0, 4 ) );
			$my_month = intval( $wp_locale->get_month( substr( $m, 4, 2 ) ) );
			$my_day = intval( substr( $m, 6, 2 ) );
		} else {
			$my_year = intval( $year );
		}

		return array( $my_year, $my_month, $my_day );
	}

	/** ==================================================
	 * Gengo for archive
	 *
	 * @param int $year  year.
	 * @param int $month  month.
	 * @return string $year
	 * @since 1.01
	 */
	private function gengo_archive( $year, $month ) {

		if ( 0 == $month ) {
			$date = new DateTime( $year . '-12-31 23:59:59' );
			$timestamp = $date->format( 'U' );
		} else {
			switch ( intval( $month ) ) {
				case 1:
					$date_end = 31;
					break;
				case 2:
					$date_end = 28;
					break;
				case 3:
					$date_end = 31;
					break;
				case 4:
					$date_end = 30;
					break;
				case 5:
					$date_end = 31;
					break;
				case 6:
					$date_end = 30;
					break;
				case 7:
					$date_end = 31;
					break;
				case 8:
					$date_end = 31;
					break;
				case 9:
					$date_end = 30;
					break;
				case 10:
					$date_end = 31;
					break;
				case 11:
					$date_end = 30;
					break;
				case 12:
					$date_end = 31;
					break;
			}
			$date = new DateTime( $year . '-' . $month . '-' . $date_end . ' 00:00:00' );
			$timestamp = $date->format( 'U' );
		}

		$gengo_before_name      = $this->gengo_list[0]['name'];
		$gengo_before_timestamp = $this->gengo_list[0]['timestamp'];
		$gengo_name             = $this->gengo_list[1]['name'];
		$gengo_timestamp        = $this->gengo_list[1]['timestamp'];
		foreach ( $this->gengo_list as $key => $value ) {
			if ( $timestamp >= $value['timestamp'] ) {
				$gengo_before_name      = $gengo_name;
				$gengo_before_timestamp = $gengo_timestamp;
				$gengo_name             = $value['name'];
				$gengo_timestamp        = $value['timestamp'];
			}
		}

		if ( function_exists( 'wp_date' ) ) {
			/* WordPress 5.3.0 later */
			$gengo_year        = intval( wp_date( 'Y', $gengo_timestamp ) );
			$gengo_before_year = intval( wp_date( 'Y', $gengo_before_timestamp ) );
			$gengo_month       = intval( wp_date( 'MM', $gengo_timestamp ) );
			$gengo_date        = intval( wp_date( 'd', $gengo_timestamp ) );
		} else {
			$gengo_year        = intval( date_i18n( 'Y', $gengo_timestamp ) );
			$gengo_before_year = intval( date_i18n( 'Y', $gengo_before_timestamp ) );
			$gengo_month       = intval( date_i18n( 'MM', $gengo_timestamp ) );
			$gengo_date        = intval( date_i18n( 'd', $gengo_timestamp ) );
		}

		if ( $gengo_year == $year ) {
			if ( 0 == $month ) {
				$year = sprintf( $gengo_before_name . '%d年', $year - $gengo_before_year + 1 ) . '、' . $gengo_name . '元年';
			} else if ( $gengo_month <= $month ) {
				if ( $gengo_month == $month && 1 < $gengo_date ) {
					$year = sprintf( $gengo_before_name . '%d年', $year - $gengo_before_year + 1 ) . '、' . $gengo_name . '元年';
				} else {
					$year = $gengo_name . '元年';
				}
			} else {
				$year = sprintf( $gengo_before_name . '%d年', $year - $gengo_before_year + 1 );
			}
		} else {
			$year = sprintf( $gengo_name . '%d年', $year - $gengo_year + 1 );
		}

		return $year;
	}
}
