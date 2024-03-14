<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.0.3
 *
 * @package    Courtres
 * @subpackage Courtres/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Courtres
 * @subpackage Courtres/public
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres_Public extends Courtres_Base {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $reservations;
	public $blocks;
	public $max_hours;
	public $isReservatedPerPersonInFuture;
	public $isSeveralReservePerson;
	public $assets_version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.3
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->assets_version = $version . '.04';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.3
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/courtres-public.css', array(), $this->assets_version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-ui-theme', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.theme.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-ui-struct', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.structure.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.3
	 */
	public function enqueue_scripts() {
		 // 22.02.2019, astoian - resolving jquery-ui conflicts
		// wp_enqueue_script( 'jquery-ui-core', false, array('jquery') );
		wp_enqueue_script( 'jquery-ui-dialog', false, array( 'jquery' ) );
		// 04.02.2019, astoian - see bottom public_shortcode
		// wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/courtres-public.js', array('jquery'), $this->version, false);
		// wp_localize_script($this->plugin_name,''.$this->plugin_name.'_params',array('cr_id'=>$atts['id']));
		// 2020-06-08, astoian - momentjs
		// wp_enqueue_script($this->plugin_name . '-moment', plugin_dir_url(__FILE__) . 'js/moment.js', array('jquery'), $this->version, false);
	}

	public function getCourtByID( $courtID ) {
		global $wpdb;
		$table_courts = $this->getTable( 'courts' );
		return $wpdb->get_row( "SELECT * FROM $table_courts WHERE id = $courtID" );
	}

	public function getAllCourts() {
		global $wpdb;
		$table_courts = $this->getTable( 'courts' );
		return $wpdb->get_results( "SELECT * FROM $table_courts" );
	}

	/**
	 * @return array courts or empty array
	 */
	public static function getCourts() {
		global $wpdb;
		$table_courts = $wpdb->prefix . 'courtres_courts';
		$items        = $wpdb->get_results( "SELECT * FROM $table_courts", ARRAY_A );
		return $items ? $items : array();
	}

	// 18.01.2019, astoian - if allow to show more the one court, if someone created directly in DB
	public function isCourtPremium( $courtID ) {
		global $wpdb;
		if ( ! cr_fs()->is_plan_or_trial( 'premium', false ) ) {
			$table_courts = $this->getTable( 'courts' );
			$court_first  = $wpdb->get_row( "SELECT * FROM $table_courts ORDER BY id ASC" );
			if ( $courtID != $court_first->id ) {
				return false;
			}
		}
		return true;
	}

	// 2021-03-14, astoian - if allow to show more the one court, if someone created directly in DB
	public function isCourtUltimate() {
		 // false - premium or higher, true - plan name exactly
		if ( ! cr_fs()->is_plan_or_trial( 'ultimate', false ) ) {
			return false;
		}
		return true;
	}

	// get future reservations without authors
	public function getCurrentReservationsByID( $courtID, $days ) {
		global $wpdb;

		$theTime = getCurrentDateTime();
		// 2020-07-23 as - for differt UTC is very important, db could have another time_zone
		// $wpdb->get_results('SET @@time_zone = "'.$theTime["offset"].'";');

				$sql_join = sprintf( ' LEFT JOIN %1$s ON %1$s.reservation_gid = %2$s.gid', $this->getTable( 'reserv_players' ), $this->getTable( 'reservations' ) );
		$sql_select_more  = sprintf( ', GROUP_CONCAT(%1$s.player_id) AS players, GROUP_CONCAT(%1$s.is_author) AS is_author', $this->getTable( 'reserv_players' ) );
		$group_by         = ' GROUP BY ' . $this->getTable( 'reservations' ) . '.id';
				$res      = $wpdb->get_results(
					"SELECT {$this->getTable('reservations')}.*{$sql_select_more} 
			FROM {$this->getTable('reservations')}
			{$sql_join} 
			WHERE courtid = $courtID AND date >= CURDATE() 
			{$group_by}
			ORDER BY date, time, minute"
				);

		return $res;
	}

	public function getBlocksByID( $courtID ) {
		global $wpdb;
		$table_blocks = $this->getTable( 'events' );
		return $wpdb->get_results( "SELECT * FROM $table_blocks WHERE courtid = $courtID ORDER BY dow" );
	}

	public function getBlocksRepeatFutureByID( $courtID ) {
		global $wpdb;

		$theTime = getCurrentDateTime();
		// 2020-07-23 as - for differt UTC is very important, db could have another time_zone
		// $wpdb->get_results('SET @@time_zone = "'.$theTime["offset"].'";');

		$table_blocks = $this->getTable( 'events' );
		return $wpdb->get_results( "SELECT * FROM $table_blocks WHERE courtid = $courtID AND (repeatone IS NULL OR repeatone >= CURDATE()) ORDER BY dow, start" );
	}

	public function getMaxHours() {
		 global $wpdb;
		$table_settings = $this->getTable( 'settings' );
		$max_hours      = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'max_hours_per_reservation'" );
		if ( ! $max_hours ) {
			return static::DEFAULT_MAX_HOURS;
		}

		return $max_hours->option_value;
	}

	public function getColour() {
		global $wpdb;
		$table_settings = $this->getTable( 'option' );
		$colour      = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_reservation_type_color'" );
		echo "SELECT * FROM $table_settings WHERE option_name = 'option_reservation_type_color'" ;
		if ( ! $colour ) {
			return static::DEFAULT_COLOUR;
		}

		return $colour->option_value;
	}

	private function doesOverlap( $hour, $from, $to ) {
		return ( $hour >= $from && $hour < $to );
	}


	private function isBlockedByDate( $date, $hour ) {
		foreach ( $this->blocks as $block ) {
			$event_start_m    = property_exists( $block, 'start_ts' ) && $block->start_ts ? date_i18n( 'i', $block->start_ts ) : 0;
			$event_start_time = (int) $block->start + (int) $event_start_m / 60;

			$event_end_m    = property_exists( $block, 'end_ts' ) && $block->end_ts ? date_i18n( 'i', $block->end_ts ) : 0;
			$event_end_time = (int) $block->end + (int) $event_end_m / 60;

			if ( ! $this->ishalfhour() ) {
				$event_start_time = floor( $event_start_time );
				$event_end_time   = ceil( $event_end_time );
			}

			if ( $block->weekly_repeat ) {
				$currentDate = new DateTime( $date );
				$eventDate   = new DateTime( $block->event_date );
				$interval    = $currentDate->diff( $eventDate );
				if ( $interval->days % 7 === 0 ) {
					if ( $this->doesOverlap( $hour, $event_start_time, $event_end_time ) ) {
						return $block;
					}
				}
			} else {
				if ( $block->event_date == $date ) {
					if ( $this->doesOverlap( $hour, $event_start_time, $event_end_time ) ) {
						return $block;
					}
				}
			}
		}
		return null;
	}

	private function isBlockedByDate_multi( $date, $hour, $court_id ) {

		foreach ( $this->blocks as $block ) {
			// proba($block);
			if ( $block->courtid == $court_id ) {
				$event_start_m    = property_exists( $block, 'start_ts' ) && $block->start_ts ? date_i18n( 'i', $block->start_ts ) : 0;
				$event_start_time = (int) $block->start + (int) $event_start_m / 60;

				$event_end_m    = property_exists( $block, 'end_ts' ) && $block->end_ts ? date_i18n( 'i', $block->end_ts ) : 0;
				$event_end_time = (int) $block->end + (int) $event_end_m / 60;

				if ( ! $this->ishalfhour() ) {
					$event_start_time = floor( $event_start_time );
					$event_end_time   = ceil( $event_end_time );
				}

				if ( $block->weekly_repeat ) {
					$currentDate = new DateTime( $date );
					$eventDate   = new DateTime( $block->event_date );
					$interval    = $currentDate->diff( $eventDate );
					if ( $interval->days % 7 === 0 ) {
						if ( $this->doesOverlap( $hour, $event_start_time, $event_end_time ) ) {
							return $block;
						}
					}
				} else {
					if ( $block->event_date == $date ) {
						if ( $this->doesOverlap( $hour, $event_start_time, $event_end_time ) ) {
							return $block;
						}
					}
				}
			}
		}
		return null;
	}


	private function isReservated( $day, $hour, $min ) {
		$theTime  = getCurrentDateTime();
		$datetime = new DateTime( $theTime['datetime'] );
		$datetime->modify( '+' . $day . ' day' );
		$now = $datetime->format( 'Y-m-d' );
		foreach ( $this->reservations as $res ) {
			$restime = ( new DateTime( $res->date ) )->format( 'Y-m-d' );
			if ( $now != $restime ) {
				continue;
			}
			if ( $min > -1 ) {
				if ( $hour == $res->time && $min == $res->minute ) {
					return $res;
				}
			} else {
				if ( $hour == $res->time ) {
					return $res;
				}
			}
		}
		return null;
	}

	private function isReservated_byID( $day, $hour, $min, $court ) {
		$theTime  = getCurrentDateTime();
		$datetime = new DateTime( $theTime['datetime'] );
		$datetime->modify( '+' . $day . ' day' );
		$now         = $datetime->format( 'Y-m-d' );
		$rezervacije = $this->getCurrentReservationsByID( $court->id, $court->days + 1 );
		foreach ( $rezervacije as $res ) {
			$restime = ( new DateTime( $res->date ) )->format( 'Y-m-d' );
			if ( $now != $restime ) {
				continue;
			}
			if ( $min > -1 ) {
				if ( $hour == $res->time && $min == $res->minute ) {
					return $res;
				}
			} else {
				if ( $hour == $res->time ) {
					return $res;
				}
			}
		}
		return null;
	}

	private function reservationLastTime( $reservation ) {
		global $wpdb;
		$founds = $wpdb->get_results( "SELECT * FROM {$this->getTable('reservations')} WHERE gid = '$reservation->gid' ORDER BY time DESC, minute DESC LIMIT 1" );
		foreach ( $founds as $res ) {
			return $res;
		}
		return null;
	}


	private function isReservatedOnce( $courtID, $day, $gid ) {
		global $wpdb;
		$theTime  = getCurrentDateTime();
		$datetime = new DateTime( $theTime['datetime'] );
		$datetime->modify( '+' . $day . ' day' );
		$sdt      = $datetime->format( 'Y-m-d' );
		$rowcount = $wpdb->get_var( "SELECT COUNT(*) FROM {$this->getTable('reservations')} WHERE courtid = $courtID AND DATE(date) = '$sdt' AND gid = '$gid'" );
		return $rowcount;
	}


	/**
	 * For displaying table cell
	 *
	 * @param  [type]  $court  - court ID
	 * @param  [type]  $day    - cell day
	 * @param  [type]  $hour   - cell hour
	 * @param  integer $mstart - cell minutes (0 four hours and 30 for halfhours rows)
	 * @param  integer $mend   - cell minutes (30 four hours and 0 for halfhours rows)
	 * @param  boolean $date   - cell date
	 * @return string of table cell  <td...>
	 */
	public function getTD( $court, $day, $hour, $mstart = 0, $mend = 0, $date = false ) {
		$now     = getCurrentDateTime();
		$theTime = getCurrentDateTime();
		$nowTZ   = new DateTime( $theTime['datetime'] );
		$nowTZTS = $nowTZ->format( 'U' );

		$isPast   = false;
		$hourD    = $hour + round( $mstart / 60, 2 );
		$nowHourD = $now['hour'] + round( $now['minute'] / 60, 2 );
		if ( $day == 0 && $hourD <= $nowHourD ) {
			$isPast = true;
		}

		$helper = ' date: ' . $date . ', hour: ' . $hour . ', hourD: ' . $hourD;

		// Events >
		$block = $this->isBlockedByDate( $date, $hourD );
		if ( $block != null ) {

			$event_start_m    = property_exists( $block, 'start_ts' ) && $block->start_ts ? date_i18n( 'i', $block->start_ts ) : 0;
			$event_start_time = (int) $block->start + (int) $event_start_m / 60;

			$event_end_m    = property_exists( $block, 'end_ts' ) && $block->end_ts ? date_i18n( 'i', $block->end_ts ) : 0;
			$event_end_time = (int) $block->end + (int) $event_end_m / 60;

			if ( ! $this->ishalfhour() ) {
				$event_start_time = floor( $event_start_time );
				$event_end_time   = ceil( $event_end_time );
			}

			// to display ONLY FIRST time cell of event (others will be unioned by rowspan)
			if ( max( $event_start_time, $court->open ) != $hourD ) {
				return '';
			}

			$rowspan = 1;

			if ( $this->ishalfhour() ) {
				$rowspan = ( min( $event_end_time, $court->close ) - max( $event_start_time, $court->open ) ) * 2;
			} else {
				$rowspan = ceil( ( min( $event_end_time, $court->close ) - max( $event_start_time, $court->open ) ) );
			}

			$helper .= ', event_start_time: ' . $event_start_time;
			$helper .= ', event_end_time: ' . $event_end_time;
			$helper .= ', rowspan: ' . $rowspan;

			// as: if block end time is in the range of current time
			if ( $isPast ) {
				$hourDBlockLastTime = $block->end;
				if ( $hourDBlockLastTime <= $nowHourD ) {
					return '<td class="unavailable" rowspan="' . $rowspan . '" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h">&mdash;</td>';
				}
			}

			$helper       = false; // set to false before pushing to production to remove dev data!
			$helper_title = $helper ? ' title="' . $helper . '"' : '';

			$block_colours = get_option('option_event_type_color');
			$block_type=$block->name; 
			if ($block_colours[$block_type]=="0") { $block_colours[$block_type]="inherit"; }
			else { $block_colours[$block_type]=$block_colours[$block_type] . " !important"; }

			// $output       = '<td class="blocked" style="background-color: ' . $block_colours[$block_type] . '" rowspan="' . $rowpan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $style . $helper_title . '  data-gid="' . $reservation->gid . '">';
			$output       = '<td class="blocked" style="background-color: ' . $block_colours[$block_type] . '" rowspan="' . $rowspan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $helper_title . '>' . esc_html( $block->name ) . '</td>';
			return $output;
		}
		// < events

		// 20.05.2019, astoian - define link class
		$link_class_red   = '';
		$link_class_green = '';
		if ( ! $this->isuilink() ) {
			$link_class_red   = ' button button_red button_size_1 ';
			$link_class_green = ' button button_green button_size_1 ';
		}

		// cells for author of reservation and partners >>
		$mincheckres = $mstart;
		if ( ! $this->ishalfhour() ) {
			$mincheckres = -1;}
		$reservation = $this->isReservated( $day, $hour, $mincheckres );
		if ( $reservation != null ) {
			$style  = '';
			$rowpan = 1;

			if ( ! is_null( $reservation->gid ) && $reservation->gid !== '' ) {
				$timeResMinus = $now['DateTime'];
				$timeResMinus->setTime( $hour, $mstart, 0 );
				if ( $this->ishalfhour() ) {
					$timeResMinus->sub( new DateInterval( 'PT30M' ) );
					$reservation_prev_gid = $this->isReservated( $day, $timeResMinus->format( 'H' ), $timeResMinus->format( 'i' ) );
				} else {
					$timeResMinus->sub( new DateInterval( 'PT30M' ) );
					$reservation_prev_gid = $this->isReservated( $day, $timeResMinus->format( 'H' ), $mincheckres );
				}
				if ( ! is_null( $reservation_prev_gid ) && $reservation->gid === $reservation_prev_gid->gid ) {
					return '';
				}

				$helper .= ', r_gid: ' . $reservation->gid . ', r_time: ' . $reservation->time . ', r_minute: ' . $reservation->minute;

				if ( ! is_null( $reservation->id ) && $reservation->id !== '' ) {
					$rowpan = $this->isReservatedOnce( $court->id, $day, $reservation->gid );

					if ( $rowpan > 1 && ! $this->ishalfhour() ) {
						$rowpan = (int) ( $rowpan / 2 ) + ( ( $rowpan % 2 ) > 0 ? 1 : 0 );
						if ( $reservation->minute > 0 ) {
							$rowpan++;
						}
					}
				}
			}

			// as: if blocked reservation end time is in the range of current time
			if ( $isPast ) {
				$reservationLastTime = $this->reservationLastTime( $reservation );
				if ( $reservationLastTime ) {
					if ( $reservationLastTime->minute == 30 ) {
						$hourDReservationLastTime = ( $reservationLastTime->time + 1 );
					} else {
						$hourDReservationLastTime = ( $reservationLastTime->time ) + round( $reservationLastTime->minute / 60, 2 );
					}
					if ( $hourDReservationLastTime <= $nowHourD ) {
						return '<td class="unavailable" rowspan="' . $rowpan . '" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h">&mdash;</td>';
					}
				}
			}

			if ( $isPast ) {
				$style = ' style = "opacity:.5; pointer-events: none;"';
			}

			$helper       = false; // activate before pushing to production to remove dev data!
			$helper_title = $helper ? ' title="' . $helper . '"' : '';

			$block_colours = get_option('option_reservation_type_color');
			$block_type=$reservation->type; 
			if ($block_colours[$block_type]=="0") { $block_colours[$block_type]="inherit"; }
			else { $block_colours[$block_type]=$block_colours[$block_type] . " !important"; }

			$output       = '<td class="blocked" style="background-color: ' . $block_colours[$block_type] . '" rowspan="' . $rowpan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $style . $helper_title . '  data-gid="' . $reservation->gid . '">';

			// Display players >
			$output .= ( new WP_User( $reservation->userid ) )->display_name . '<br/>';
			$output .= '<strong>';
			$output .= esc_html( $reservation->type );
			$output .= '</strong>';

			// from 1.5.0 >
			if ( property_exists( $reservation, 'players' ) ) {
				$partners = explode( ',', $reservation->players );

				// remove author from result
				$is_authors = explode( ',', $reservation->is_author );
				$author_key = array_search( 1, $is_authors );
				if ( $author_key !== false ) {
					array_splice( $partners, $author_key, 1 );
				}
				if ( $partners ) {
					$wp_users = get_users(
						array(
							'include' => $partners,
							'orderby' => 'display_name',
							'order'   => 'ASC',
						)
					);
					if ( count( $wp_users ) ) {
						$first_user = array_shift( $wp_users );
						$output    .= '<br/>' . $first_user->display_name;
					}
					if ( count( $wp_users ) ) {
						$output .= '<div class="cr-tooltip"><span class="cr-tooltiptext cr-tooltip-right">';
						$counter = 0;
						foreach ( $wp_users as $key => $wp_user ) {
							$output .= ( $counter > -1 ? ', ' : '' ) . $wp_user->display_name;
							$counter++;
						}
						$output .= '</span></div>';
					}
				}
			}
			// <from 1.5.0
			// < Display players

			if ( ! $isPast ) {
				if ( (int) $reservation->userid == wp_get_current_user()->ID ) {
					$output .= '<br/><a class="' . $link_class_red . ' delete" data-id="' . $reservation->id . '">' . $this->option_ui_btn_title_2() . '</a>';
				}
			}

			$output .= '</td>';
			return $output;
		}

		// << past cells for author of reservation and partners
		// as: allow users to reserve a court till the end of the HOUR/HALF-HOUR
		if ( $isPast ) {
			$hourD = $hour + round( $mstart / 60, 2 );
			if ( $this->ishalfhour() ) {
				if ( ( $now['minute'] - 30 ) <= 0 ) {
					$nowHourDPlus = ( $now['hour'] - 1 ) + round( $now['minute'] / 60, 2 );
				} else {
					$nowHourDPlus = ( $now['hour'] ) + round( ( $now['minute'] - 30 ) / 60, 2 );
				}
			} else {
				$nowHourDPlus = ( $now['hour'] - 1 ) + round( $now['minute'] / 60, 2 );
			}
			if ( $hourD <= $nowHourDPlus ) {
				$output = '<td class="unavailable" data-now="' . $now['hour'] . ':' . $now['minute'] . '">-</td>';
				return $output;
			}
		}

		// << cells for user who can't to reserve
		if ( ! $this->isSeveralReservePerson ) {
			if ( $this->isReservatedPerPersonInFuture ) {
				$output = '<td class="unavailable" data-now="' . $now['hour'] . ':' . $now['minute'] . '">-</td>';
				return $output;
			}
		}
		// << cells for reserve

		$min_players = $this->getMinPlayers();
		// print_r($min_players);

		if (!isset($min_players['Single'])) { $min_players['Single']=0; }
		if (!isset($min_players['Double'])) { $min_players['Double']=0; }
		if (!isset($min_players['Championship'])) { $min_players['Championship']=0; }
		if (!isset($min_players['Training'])) { $min_players['Training']=0; }
		if (!isset($min_players['Competition'])) { $min_players['Competition']=0; }

		$helper       = false; // activate before pushing to production to remove dev data!
		$helper_title = $helper ? ' title="' . $helper . '"' : '';
		// $output = '<td class="available" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h"><a class=" ' . $link_class_green . ' reservation" data-day="'
		$output = '<td class="available" court-id="' . $court->id . '" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h"><a class=" ' . $link_class_green . ' reservation" court-id="' . $court->id . '" data-day="'
		. $day
		. '" data-hour="' . $hour
		. '" data-hourD="' . $hourD
		. '" data-date="'
		. date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) )
		. '" data-date-display="'
		. date_i18n( get_option( 'date_format' ), strtotime( '+' . $day . ' day', $nowTZTS ) )
		. '" data-time="' . $hour . ' - ' . ( $hour + 1 ) . ' ' . '"'
		. ' data-min-start="' . $mstart . '"'
		. ' data-min-player="' . $min_players['Single'] . ',' . $min_players['Double'] . ',' . $min_players['Championship'] . ',' . $min_players['Training'] . ',' . $min_players['Competition'] . '"'
		. $helper_title
		. '>' . $this->option_ui_btn_title_1() . '</a></td>';
		return $output;
	}

	public function getTD_multi( $court, $day, $hour, $klasa, $mstart = 0, $mend = 0, $date = false ) {
		$now     = getCurrentDateTime();
		$theTime = getCurrentDateTime();
		$nowTZ   = new DateTime( $theTime['datetime'] );
		$nowTZTS = $nowTZ->format( 'U' );

		$isPast   = false;
		$hourD    = $hour + round( $mstart / 60, 2 );
		$nowHourD = $now['hour'] + round( $now['minute'] / 60, 2 );
		if ( $day == 0 && $hourD <= $nowHourD ) {
			$isPast = true;
		}

		$helper = ' date: ' . $date . ', hour: ' . $hour . ', hourD: ' . $hourD;

		// Events >
		$block = $this->isBlockedByDate_multi( $date, $hourD, $court->id );
		if ( $block != null && $block->courtid == $court->id ) {
			$event_start_m    = property_exists( $block, 'start_ts' ) && $block->start_ts ? date_i18n( 'i', $block->start_ts ) : 0;
			$event_start_time = (int) $block->start + (int) $event_start_m / 60;

			$event_end_m    = property_exists( $block, 'end_ts' ) && $block->end_ts ? date_i18n( 'i', $block->end_ts ) : 0;
			$event_end_time = (int) $block->end + (int) $event_end_m / 60;

			if ( ! $this->ishalfhour() ) {
				$event_start_time = floor( $event_start_time );
				$event_end_time   = ceil( $event_end_time );
			}

			// to display ONLY FIRST time cell of event (others will be unioned by rowspan)
			if ( max( $event_start_time, $court->open ) != $hourD ) {
				return '';
			}

			$rowspan = 1;

			if ( $this->ishalfhour() ) {
				$rowspan = ( min( $event_end_time, $court->close ) - max( $event_start_time, $court->open ) ) * 2;
			} else {
				$rowspan = ceil( ( min( $event_end_time, $court->close ) - max( $event_start_time, $court->open ) ) );
			}

			$helper .= ', event_start_time: ' . $event_start_time;
			$helper .= ', event_end_time: ' . $event_end_time;
			$helper .= ', rowspan: ' . $rowspan;

			// as: if block end time is in the range of current time
			if ( $isPast ) {
				$hourDBlockLastTime = $block->end;
				if ( $hourDBlockLastTime <= $nowHourD ) {
					return '<td class="unavailable ' . $klasa . '" rowspan="' . $rowspan . '" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h">&mdash;</td>';
				}
			}

			$helper       = false; // set to false before pushing to production to remove dev data!
			$helper_title = $helper ? ' title="' . $helper . '"' : '';
			$output       = "<td class=\"blocked $klasa\" rowspan=\"" . $rowspan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $helper_title . '>' . esc_html( $block->name ) . '</td>';

			$block_colours = get_option('option_event_type_color');
			$block_type=$block->name; 
			if ($block_colours[$block_type]=="0") { $block_colours[$block_type]="inherit"; }
			else { $block_colours[$block_type]=$block_colours[$block_type] . " !important"; }

			$output       = "<td class=\"blocked $klasa\" style='background-color: " . $block_colours[$block_type] . "' rowspan=\"" . $rowspan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $helper_title . '>' . esc_html( $block->name ) . '</td>';
			// $output       = '<td class="blocked" style="background-color: ' . $block_colours[$block_type] . '" rowspan="' . $rowspan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $helper_title . '>' . esc_html( $block->name ) . '</td>';

			return $output;
		}
		// < events

		// 20.05.2019, astoian - define link class
		$link_class_red   = '';
		$link_class_green = '';
		if ( ! $this->isuilink() ) {
			$link_class_red   = ' button button_red button_size_1 ';
			$link_class_green = ' button button_green button_size_1 ';
		}

		// cells for author of reservation and partners >>
		$mincheckres = $mstart;
		if ( ! $this->ishalfhour() ) {
			$mincheckres = -1;}
		$reservation = $this->isReservated_byID( $day, $hour, $mincheckres, $court );
		if ( $reservation != null ) {
			$style  = '';
			$rowpan = 1;

			if ( ! is_null( $reservation->gid ) && $reservation->gid !== '' ) {
				$timeResMinus = $now['DateTime'];
				$timeResMinus->setTime( $hour, $mstart, 0 );
				if ( $this->ishalfhour() ) {
					$timeResMinus->sub( new DateInterval( 'PT30M' ) );
					$reservation_prev_gid = $this->isReservated_byID( $day, $timeResMinus->format( 'H' ), $timeResMinus->format( 'i' ), $court );
				} else {
					$timeResMinus->sub( new DateInterval( 'PT30M' ) );
					$reservation_prev_gid = $this->isReservated_byID( $day, $timeResMinus->format( 'H' ), $mincheckres, $court );
				}
				if ( ! is_null( $reservation_prev_gid ) && $reservation->gid === $reservation_prev_gid->gid ) {
					return '';
				}

				$helper .= ', r_gid: ' . $reservation->gid . ', r_time: ' . $reservation->time . ', r_minute: ' . $reservation->minute;

				if ( ! is_null( $reservation->id ) && $reservation->id !== '' ) {
					$rowpan = $this->isReservatedOnce( $court->id, $day, $reservation->gid );

					if ( $rowpan > 1 && ! $this->ishalfhour() ) {
						$rowpan = (int) ( $rowpan / 2 ) + ( ( $rowpan % 2 ) > 0 ? 1 : 0 );
						if ( $reservation->minute > 0 ) {
							$rowpan++;
						}
					}
				}
			}

			// as: if blocked reservation end time is in the range of current time
			if ( $isPast ) {
				$reservationLastTime = $this->reservationLastTime( $reservation );
				if ( $reservationLastTime ) {
					if ( $reservationLastTime->minute == 30 ) {
						$hourDReservationLastTime = ( $reservationLastTime->time + 1 );
					} else {
						$hourDReservationLastTime = ( $reservationLastTime->time ) + round( $reservationLastTime->minute / 60, 2 );
					}
					if ( $hourDReservationLastTime <= $nowHourD ) {
						return '<td class="unavailable ' . $klasa . '" rowspan="' . $rowpan . '" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h">&mdash;</td>';
					}
				}
			}

			if ( $isPast ) {
				$style = ' style = "opacity:.5; pointer-events: none;"';
			}

			$block_colours = get_option('option_reservation_type_color');
			$block_type=$reservation->type; 
			if ($block_colours[$block_type]=="0") { $block_colours[$block_type]="inherit"; }
			else { $block_colours[$block_type]=$block_colours[$block_type] . " !important"; }

			$helper       = false; // activate before pushing to production to remove dev data!
			$helper_title = $helper ? ' title="' . $helper . '"' : '';
			$output       = "<td class=\"blocked $klasa\" style=\"background-color: " . $block_colours[$block_type] . "\" rowspan=\"" . $rowpan . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '"' . $style . $helper_title . '  data-gid="' . $reservation->gid . '">';

			// Display players >
			$output .= ( new WP_User( $reservation->userid ) )->display_name . '<br/>';
			$output .= '<strong>';
			$output .= esc_html( $reservation->type );
			$output .= '</strong>';

			// from 1.5.0 >
			if ( property_exists( $reservation, 'players' ) ) {
				$partners = explode( ',', $reservation->players );

				// remove author from result
				$is_authors = explode( ',', $reservation->is_author );
				$author_key = array_search( 1, $is_authors );
				if ( $author_key !== false ) {
					array_splice( $partners, $author_key, 1 );
				}
				if ( $partners ) {
					$wp_users = get_users(
						array(
							'include' => $partners,
							'orderby' => 'display_name',
							'order'   => 'ASC',
						)
					);
					if ( count( $wp_users ) ) {
						$first_user = array_shift( $wp_users );
						$output    .= '<br/>' . $first_user->display_name;
					}
					if ( count( $wp_users ) ) {
						$output .= '<div class="cr-tooltip"> <span class="cr-tooltiptext cr-tooltip-right">';
						$counter = 0;
						foreach ( $wp_users as $key => $wp_user ) {
							$output .= ( $counter > 0 ? ', ' : '' ) . $wp_user->display_name;
							$counter++;
						}
						$output .= '</span></div>';
					}
				}
			}
			// <from 1.5.0
			// < Display players

			if ( ! $isPast ) {
				if ( (int) $reservation->userid == wp_get_current_user()->ID ) {
					$output .= '<br/><a class="' . $link_class_red . ' delete" data-id="' . $reservation->id . '">' . $this->option_ui_btn_title_2() . '</a>';
				}
			}

			$output .= '</td>';
			return $output;
		}

		// << past cells for author of reservation and partners
		// as: allow users to reserve a court till the end of the HOUR/HALF-HOUR
		if ( $isPast ) {
			$hourD = $hour + round( $mstart / 60, 2 );
			if ( $this->ishalfhour() ) {
				if ( ( $now['minute'] - 30 ) <= 0 ) {
					$nowHourDPlus = ( $now['hour'] - 1 ) + round( $now['minute'] / 60, 2 );
				} else {
					$nowHourDPlus = ( $now['hour'] ) + round( ( $now['minute'] - 30 ) / 60, 2 );
				}
			} else {
				$nowHourDPlus = ( $now['hour'] - 1 ) + round( $now['minute'] / 60, 2 );
			}
			if ( $hourD <= $nowHourDPlus ) {
				$output = '<td class="unavailable ' . $klasa . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '">-</td>';
				return $output;
			}
		}

		// << cells for user who can't to reserve
		if ( ! $this->isSeveralReservePerson ) {
			if ( $this->isReservatedPerPersonInFuture ) {
				$output = '<td class="unavailable ' . $klasa . '" data-now="' . $now['hour'] . ':' . $now['minute'] . '">-</td>';
				return $output;
			}
		}
		// << cells for reserve

		$min_players = $this->getMinPlayers();
		// print_r($min_players);

		if (!isset($min_players['Single'])) { $min_players['Single']=0; }
		if (!isset($min_players['Double'])) { $min_players['Double']=0; }
		if (!isset($min_players['Championship'])) { $min_players['Championship']=0; }
		if (!isset($min_players['Training'])) { $min_players['Training']=0; }
		if (!isset($min_players['Competition'])) { $min_players['Competition']=0; }


		$helper       = false; // activate before pushing to production to remove dev data!
		$helper_title = $helper ? ' title="' . $helper . '"' : '';
		// $output = '<td class="available" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h"><a class=" ' . $link_class_green . ' reservation" data-day="'
		$output = '<td class="available ' . $klasa . '" court-id="' . $court->id . '" data-now="' . $nowHourD . 'h" data-cell="' . $hourD . 'h"><a class=" ' . $link_class_green . ' reservation" court-id="' . $court->id . '" data-day="'
		. $day
		. '" data-hour="' . $hour
		. '" data-hourD="' . $hourD
		. '" data-date="'
		. date_i18n( 'Y-m-d', strtotime( '+' . $day . ' day', $nowTZTS ) )
		. '" data-date-display="'
		. date_i18n( get_option( 'date_format' ), strtotime( '+' . $day . ' day', $nowTZTS ) )
		. '" data-time="' . $hour . ' - ' . ( $hour + 1 ) . ' ' . '"'
		. ' data-min-start="' . $mstart . '"'
		. ' data-min-player="' . $min_players['Single'] . ',' . $min_players['Double'] . ',' . $min_players['Championship'] . ',' . $min_players['Training'] . ',' . $min_players['Competition'] . '"'
		. $helper_title
		. '>' . $this->option_ui_btn_title_1() . '</a></td>';
		return $output;
	}
	public function public_shortcode( $atts, $content = null ) {
		global $cr_ids;
		$cr_ids[] = $atts['id'];
		ob_start();
		include 'partials/' . $this->plugin_name . '-public-display.php';
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/courtres-public.js', array( 'jquery' ), $this->assets_version, false );
		wp_localize_script(
			$this->plugin_name,
			$this->plugin_name . '_params',
			array(
				'cr_ids'                  => $cr_ids,
				'cr_url'                  => plugins_url( '', __FILE__ ),
				'cr_btn_save'             => __( 'Save', 'court-reservation' ),
				'cr_btn_cancel'           => __( 'Cancel', 'court-reservation' ),
				'cr_option_ui_dateformat' => $this->getDateFormat(),
				'ajax_url'                => admin_url( 'admin-ajax.php' ),
			)
		);

		return ob_get_clean();
	}

	public function public_shortcode_full_view( $atts, $content = null ) {
		global $cr_ids;
		// $cr_ids[] = $atts['id'];
		if (!is_array($cr_ids)) { $cr_ids=array(); }
		if (!is_array($atts)) { $atts=array(); }
		if (isset($atts['id'])) { $cr_ids[] = str_replace(",","_",$atts['id']); }
		ob_start();
		include 'partials/' . $this->plugin_name . '-public-display-full-view.php';
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/courtres-public.js', array( 'jquery' ), $this->assets_version, false );
		wp_localize_script(
			$this->plugin_name,
			$this->plugin_name . '_params',
			array(
				'cr_ids'                  => $cr_ids,
				'cr_url'                  => plugins_url( '', __FILE__ ),
				'cr_btn_save'             => __( 'Save', 'court-reservation' ),
				'cr_btn_cancel'           => __( 'Cancel', 'court-reservation' ),
				'cr_option_ui_dateformat' => $this->getDateFormat(),
				'ajax_url'                => admin_url( 'admin-ajax.php' ),
			)
		);

		return ob_get_clean();
	}

	public function isseveralreserveperson() {
		global $wpdb;
		$table_name                    = $this->getTable( 'settings' );
		$option_several_reserve_person = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'several_reserve_person'" );
		if ( ! isset( $option_several_reserve_person ) ) {
			return false;
		}
		return $option_several_reserve_person->option_value === '1' ? true : false;
	}

	public function iscalenderviewnavigator() {
		 global $wpdb;
		$table_name                     = $this->getTable( 'settings' );
		$option_calender_view_navigator = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'calender_view_navigator'" );
		if ( ! isset( $option_calender_view_navigator ) ) {
			return false;
		}
		return $option_calender_view_navigator->option_value === '1' ? true : false;
	}

	public function ishalfhour() {
		global $wpdb;
		$table_name       = $this->getTable( 'settings' );
		$option_half_hour = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'half_hour_reservation'" );
		if ( ! isset( $option_half_hour ) ) {
			return false;
		}
		return $option_half_hour->option_value === '1' ? true : false;
	}

	public function isuilink() {
		global $wpdb;
		$table_name     = $this->getTable( 'settings' );
		$option_ui_link = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_link'" );
		if ( ! isset( $option_ui_link ) ) {
			return false;
		}
		return $option_ui_link->option_value === '1' ? true : false;
	}

	public function option_ui_tbl_brdr_clr() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_brdr_clr'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		  table.reservations, table.reservations td, table.reservations th {
			border-color: ' . $opt->option_value . ';
			border-style: solid;
		  }
		</style>';
	}

	public function option_ui_tbl_bg_clr_1() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_1'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		  table.reservations td.available {
			background-color: ' . $opt->option_value . ' ;
		  }
		</style>';
	}

	public function option_ui_tbl_bg_clr_2() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_2'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		  table.reservations td.blocked {
			background-color: ' . $opt->option_value . ' !important;
		  }
		</style>';
	}

	public function option_ui_tbl_bg_clr_3() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_3'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		  table.reservations td.unavailable {
			background-color: ' . $opt->option_value . ' !important;
		  }
		</style>';
	}

	public function option_ui_tbl_bg_clr_4() {
		global $wpdb;
		// $bg_color = "#f8f8f8";
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_tbl_bg_clr_4'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		$bg_color = $opt->option_value;
		return '<style>
		table.reservations thead th,
		table.reservations tbody th {
			background-color: ' . $bg_color . ' !important ;
		}
		</style>';
	}

	public function option_ui_link_clr() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_link_clr'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		  table.reservations td a.reservation:not(.button), table.reservations td a.delete:not(.button), .cr-dialog-reserve .login_button {
			color: ' . $opt->option_value . ';
		  }
		</style>';
	}

	public function option_ui_button_clr() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_button_clr'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		  table.reservations td a.button {
			background-color: ' . $opt->option_value . ';
		  }
		</style>';
	}

	public function option_ui_btn_title_1() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_btn_title_1'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return __( 'reserve', 'court-reservation' );
		}
		return $opt->option_value;
	}

	public function option_ui_btn_title_2() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_btn_title_2'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return __( 'delete', 'court-reservation' );
		}
		return $opt->option_value;
	}

	public function option_ui_table_cell_width() {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_width'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		table.reservations {
			min-width: initial;
			width: initial;
		  }
		table.reservations th, table.reservations td {
			min-width: ' . $opt->option_value . 'px;
		}
		table.reservations tbody th {
			position: sticky;
			left: 0;
			white-space: nowrap;
		}
		</style>';
	}

	public function option_ui_table_cell_height() {
		 global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_height'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		return '<style>
		table.reservations {
			height: 100%;
		}
		table.reservations thead, table.reservations tbody tr {
			table-layout:fixed;
		}
		table.reservations th, table.reservations td {
			/*min-height: ' . $opt->option_value . 'px;*/
			height: ' . $opt->option_value . 'px;
			padding-top: 0;
			padding-bottom: 0;
		}
		</style>';
	}

	public function option_ui_table_cell_mouseover_background() {
		global $wpdb;
		// $bg_color = "#f8f8f8";
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_mouseover_background'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		$bg_color = $opt->option_value;
		return '<style>
		table.reservations th:hover, table.reservations td:hover {
			background-color: ' . $bg_color . ' !important;
		}
		</style>';
	}

	public function option_ui_table_cell_mouseover_linktext() {
		 global $wpdb;
		$color      = '#333333';
		$table_name = $this->getTable( 'settings' );
		$opt        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = 'option_ui_table_cell_mouseover_linktext'" );
		if ( ! isset( $opt ) || $opt->option_value === '' ) {
			return '';
		}
		$bg_color = $opt->option_value;
		return '<style>
		table.reservations th:hover, table.reservations td:hover {
			color: ' . $bg_color . ' !important;
		}
		</style>';
	}

	public function ajax_cr_navigator() {
		global $wpdb;
		status_header( 200 );
		$courtID = isset( $_REQUEST['id'] ) ? sanitize_text_field( $_REQUEST['id'] ) : 0;
		include 'partials/' . $this->plugin_name . '-public-table.php';
		wp_die();
	}

	public function ajax_cr_navigator_full_view() {
		global $wpdb;
		status_header( 200 );
		$courtID = isset( $_REQUEST['id'] ) ? sanitize_text_field( $_REQUEST['id'] ) : 0;
		include 'partials/' . $this->plugin_name . '-public-table-full-view.php';
		wp_die();
	}

	public function ajax_cr_navigator2() {
		global $wpdb;
		status_header( 200 );
		include 'partials/' . $this->plugin_name . '-public-table-full-view.php';
		wp_die();
	}

	public function ajax_cr_navigator_calendar() {
		global $wpdb;
		status_header( 200 );
		$courtID = isset( $_REQUEST['id'] ) ? sanitize_text_field( $_REQUEST['id'] ) : 0;
		include 'partials/' . $this->plugin_name . '-public-table-calendar.php';
		wp_die();
	}

	/**
	 * get list of possible reservation types
	 *
	 * @return separated by "\r\n" string of types ("Single\r\nDouble\r\nChampionship\r\nTraining\r\nCompetition") or false if not exists in db
	 */
	public function getReservationTypes() {
		 global $wpdb;
		$table_settings    = $this->getTable( 'settings' );
		$reservation_types = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'reservation_types'" );
		if ( ! $reservation_types ) {
			return false;
		}

		return $reservation_types->option_value;
	}

	/**
	 * get available reservation types
	 *
	 * @return array of types or false if not exists in db
	 */
	public function getAvailableReservationTypes() {
		global $wpdb;
		/*
		$table_settings = $this->getTable('settings');
		$reservation_types = $wpdb->get_row("SELECT * FROM $table_settings WHERE option_name = 'available_reservation_types'");
		if (!$reservation_types) {
			return false;
		}

		// return unserialize($reservation_types->option_value);
		 */
		 $reservation_types = get_option( 'available_reservation_types' );
		return $reservation_types;
	}

	/**
	 * get Max. Number of Other Players
	 *
	 * @return array of types or empty array if not exists in db
	 */
	public function getMaxPlayers() {
		global $wpdb;

		$option_max_players_for_reserv_type = get_option( 'max_players_for_reserv_type' );

		/*
		$table_settings = $this->getTable('settings');
		$option_max_players_for_reserv_type = $wpdb->get_row("SELECT * FROM $table_settings WHERE option_name = 'max_players_for_reserv_type'");
		 */
		if ( ! $option_max_players_for_reserv_type ) {
			return array();
		}
		return $option_max_players_for_reserv_type;
	}

	/**
	 * get Min. Number of Other Players
	 *
	 * @return array of types or empty array if not exists in db
	 */
	public function getMinPlayers() {
		global $wpdb;
		/*
		$table_settings = $this->getTable('settings');
		$option_min_players_for_reserv_type = $wpdb->get_row("SELECT * FROM $table_settings WHERE option_name = 'court_min_players_for_reserv_type'");
		if (!$option_min_players_for_reserv_type) {
			return array();
		}
		// return unserialize($option_min_players_for_reserv_type->option_value);
		 */

		$option_min_players_for_reserv_type = get_option( 'court_min_players_for_reserv_type' );

		if ( ! $option_min_players_for_reserv_type ) {
			return array();
		}
		return $option_min_players_for_reserv_type;
	}

	/**
	 * get Fixed Match Duration
	 *
	 * @return array of Fixed Match Durations in sec or empty array if not exists in db
	 */
	public function getMatchDurations() {
		global $wpdb;
		/*
		$table_settings = $this->getTable('settings');
		$option_match_durations_ts = $this->getOption("match_durations_ts");
		if (!$option_match_durations_ts) {
			return array();
		}
		return unserialize($option_match_durations_ts->option_value);
		 */
		$get_duration = get_option( 'match_durations_ts' );
		if ( ! $get_duration ) {
			return array();
		}
		return $get_duration;
	}

	/**
	 * get comfirmation email with placeholders
	 *
	 * @return string
	 */
	public function getEmailComfirmationTemplate() {
		global $wpdb;
		$table_settings = $this->getTable( 'settings' );
		$email_template = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_email_template'" );
		if ( ! $email_template ) {
			return false;
		}

		return $email_template->option_value;
	}

	/**
	 * get array of available date formats
	 *
	 * @param string  Example: "d.m. = German\r\nm.d. = USA"
	 * @return array  Example: array("d.m." => "German", "m.d." => "U.S.");
	 */
	public function getDateformats( $str = '' ) {
		$delimiter = '=';
		global $wpdb;
		$table_settings = $this->getTable( 'settings' );
		$result         = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_dateformats'" );

		$str   = preg_replace( array( '/ /' ), array( '' ), $result->option_value );
		$items = explode( "\r\n", $str );

		$dateformats = array();
		foreach ( $items as $item ) {
			$arr           = explode( $delimiter, $item );
			$dateformats[] = array(
				'format' => $arr[0],
				'name'   => $arr[1],
			);
		}
		return $dateformats;
	}

	/**
	 * get setted date format
	 *
	 * @return string date format for date_i18n()
	 */
	public function getDateFormat() {
		global $wpdb;
		$table_settings = $this->getTable( 'settings' );
		$dateformat     = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_ui_dateformat'" );
		if ( ! $dateformat ) {
			return false;
		}
		return $dateformat->option_value;
	}

	/**
	 * check if option_is_team_mate_mandatory
	 *
	 * @return bool
	 */
	public function isTeamMateMandatory() {
		 global $wpdb;
		$table_settings                = $this->getTable( 'settings' );
		$option_is_team_mate_mandatory = $wpdb->get_row( "SELECT * FROM $table_settings WHERE option_name = 'option_is_team_mate_mandatory'" );
		$option_is_team_mate_mandatory->option_value=0; // always 0 because we are removing this function
		if ( ! $option_is_team_mate_mandatory ) {
			return false;
		}
		return (bool) $option_is_team_mate_mandatory->option_value;
	}

	// Add Reservation Popup
	// get teammate row
	function get_teammate_row( $args ) {

		$player_counter    = isset( $args['player_counter'] ) ? intval( $args['player_counter'] ) : '';
		$isPartnerRequired = $player_counter === 0 && $this->isTeamMateMandatory();

		$players = $this->getAvailablePlayers();
		// fppr($players, __FILE__.' $players');

		// $html_options = '<option value="0">' . __('Select partner', 'court-reservation') . '</option>';
		$html_options = '';
		$html_select='<datalist id="polja_">';
		foreach ( $players as $player ) 
		{
			$html_options .= '<option data-value="' . $player->id . '">' . $player->display_name . '</option>';
			$html_select=$html_select . '<option data-value="' . $player->id . '">' . $player->display_name . '</option>';
		}
		$html_select=$html_select . '</datalist>';
		/*
		$html_select = '
			<select 
				class="partner-select" name="partners[]"' .
				' data-parnter-counter="' . $player_counter . '"' .
				' autocomplete="off" multiple size="5"' .
				' data-min="' . ( $isPartnerRequired ? 1 : 0 ) . '"' .
				' data-max="1"' .
				( $isPartnerRequired ? ' required' : '' ) .
			'>' .
				$html_options .
			'</select>'; */


			$args['court_id_']=rand(1,999999);

		for ( $x__=1; $x__<=$args['max_players']; $x__++ )
		{ 


			$html_select = $html_select . '
				<input style="margin-bottom: 10px;" list="igrac' . $x__ . '" placeholder="Type or click to select" oninput="
					var vrijednost=this.value;
					var igracici = document.querySelectorAll(\'#igrac' . $x__ . ' option\');
					show_options(igracici,vrijednost,\'' . $x__ . '_' . $args['court_id_'] . '\');
					datalistevi=[]; 
					skriveni_=[]; ';

					for ( $y__=0; $y__<$args['max_players']; $y__++ )
					{ 
						$html_select = $html_select . '

					datalistevi[' . $y__ . ']=\'igrac' . ($y__+1) . '\'; 
					skriveni_[' . $y__ . ']=\'odg_' . ($y__+1) . '_' . $args['court_id_'] . '\'; ';
					}


			$html_select = $html_select . '

					makni(datalistevi,skriveni_);
				">
				<datalist name="igrac[' .  $x__ . ']" id="igrac' . $x__  . '">
					' . $html_options . '
				</datalist>
				<input type="hidden" value="" name="partners[]" id="odg_' . $x__ . '_' . $args['court_id_'] . '" class="odg_' . $x__ . '_' . $args['court_id_'] . '">
			';

		} 

		$html_select = $html_select . '
			<input type="hidden" id="player-min" name="player-min" value="' .  $args['min_players'] . '">
			<input type="hidden" id="player-number" name="player-number" value="' .  $args['max_players'] . '">
		';
		$html        = '
		<tr class="type-depending-row partner-row">
			<td>' .
				__( 'Teammate', 'court-reservation' ) . ( $isPartnerRequired ? '*' : '' ) . '<br /><span id="part_min">' .
				( $isPartnerRequired ? __( 'min.', 'court-reservation' ) . ': 1' : '' ) . '</span><br />' .
				__( 'max.', 'court-reservation' ) . ': ' . '<span class="max-players-quantity"></span>' . '<br />' .
				// __('min.', 'court-reservation') . ': ' . '<span class="min-players-quantity"></span>' . '<br />' .
				// '<i class="cr-help">' . __( 'Hold Ctrl / Cmd for the selection of multipe players', 'court-reservation' ) . '</i>' .
			'</td>
			<td>' .
				$html_select .
				// '<p class="partners-info">Selected: <span class="partners-list"></span></p>' .
			'</td>
		</tr>
		';
		return $html;
	}

	// for ajax calls
	function ajax_get_court() {
		$response = array(
			'request' => $_POST,
			'errors'  => array(),
			'success' => false,
		);
		$res      = false;

		$court_id = isset( $_POST['court_id'] ) ? intval( $_POST['court_id'] ) : false;
		if ( ! $court_id ) {
			$response['errors'][] = __( 'Court id is not received', 'courtres' );
			echo json_encode( $response );
			wp_die();
		}

		$response['court']   = $this->getCourtByID( $court_id );
		$response['success'] = true;
		echo json_encode( $response );
		wp_die();
	}


	// Add Reservation Popup
	// get Time select row
	function get_time_row( $args ) {
		$row_template = '
		<tr class="type-depending-row time-row">
			<td>' .
				__( 'Time', 'court-reservation' ) . '*<br />' .
			'</td>
			<td>%s</td>
		</tr>
		';

		$court_id = isset( $args['court_id'] ) ? intval( $args['court_id'] ) : false;
		// echo "<pre>"; print_r($args); die;
		if ( ! $court_id ) {
			$html = sprintf( $row_template, __( 'The court_id param not defined', 'court-reservation' ) );
			return $html;
		}
		$start_ts = isset( $args['start_ts'] ) ? intval( $args['start_ts'] ) : false;
		if ( ! $start_ts ) {
			$html = sprintf( $row_template, __( 'The start_ts param not defined', 'court-reservation' ) );
			return $html;
		}
		$is_halfhour = isset( $args['is_halfhour'] ) ? intval( $args['is_halfhour'] ) : false;

				$date_format = get_option( 'date_format' );
		$time_format         = get_option( 'time_format' );
		$time_step_m         = $is_halfhour ? 30 : 60;
		$time_step_ts        = $time_step_m * 60;

		$max_hours_per_reserv_option = $this->getOption( 'max_hours_per_reservation' );
		$max_hours_per_reserv        = is_object( $max_hours_per_reserv_option ) ? $max_hours_per_reserv_option->option_value : static::DEFAULT_MAX_HOURS;

		$court          = $this->getCourtByID( $court_id );
		$court_close_ts = $court->close * 3600;

		$duration_ts  = isset( $args['duration_ts'] ) && $args['duration_ts'] ? intval( $args['duration_ts'] ) : false;
		$time_options = array();
		if ( ! $duration_ts ) {
			$max_hours_ts = $max_hours_per_reserv * 3600;
			$limit_ts     = $start_ts + $max_hours_ts;
			$i            = 1;
			$end_ts       = $start_ts;
			while ( $end_ts < $limit_ts && $end_ts < $court_close_ts ) {
				$end_ts        += $time_step_ts;
				$time_options[] = array(
					'value' => $time_step_m * $i,
					'name'  => date_i18n( $time_format, $start_ts ) . ' - ' . date_i18n( $time_format, $end_ts ),
				);
				$i++;
			}
		} else {
			$max_hours_ts = $max_hours_per_reserv * 3600;
			$limit_ts     = $start_ts + $duration_ts;
			$i            = 1;
			$end_ts       = $start_ts;
			while ( $end_ts < $limit_ts && $end_ts < $court_close_ts ) {
				$end_ts        += $time_step_ts;
				$time_options[] = array(
					'value' => $time_step_m * $i,
					'name'  => date_i18n( $time_format, $start_ts ) . ' - ' . date_i18n( $time_format, $end_ts ),
				);
				$i++;
			}
			// $end_ts         = $start_ts + $duration_ts;
			// $time_options[] = array(
			//	'value' => floor( $duration_ts / 60 ),
			//	'name'  => date_i18n( $time_format, $start_ts ) . ' - ' . date_i18n( $time_format, $end_ts ),
			// );
		}

		$html_options = '';
		foreach ( $time_options as $option ) {
			$html_options .= '<option value="' . $option['value'] . '">' . $option['name'] . '</option>';
		}
		$html_select = '
			<select 
				class="time-select" name="hourplus" id="hourplus"' .
				' autocomplete="off" size="1"' .
			'>' .
				$html_options .
			'</select>
		';

		$html = sprintf( $row_template, $html_select );
		return $html;
	}


	// Add Reservation Popup
	// for ajax call
	// get Time select row
	function get_more_rows_html() {
		$html  = $this->get_time_row( $_POST );
		$html .= $this->get_teammate_row( $_POST ); 

		$allowed_html = array(
			'tr'  => array(
				'class'      => array()
			),
			'td' => array(
				'class'     => array(),
			),
			'select'  => array(
				'class' => array(),
				'name' => array(),
				'data-partner-counter' => array(),
				'multiple' => array(),
				'required' => array(),
				'data-min' => array(),
				'data-max' => array(),
				'id' => array(),
				'autocomplete' => array(),
				'size' => array()
			),
			'datalist'  => array(
				'class' => array(),
				'name' => array(),
				'data-partner-counter' => array(),
				'multiple' => array(),
				'required' => array(),
				'data-min' => array(),
				'data-max' => array(),
				'id' => array(),
				'autocomplete' => array(),
				'size' => array()
			),
			'option' => array(
				'value'     => array(),
				'data-value'     => array()
			),
			'span' => array(
				'id'     => array(),
				'class'     => array()
			),
			'br' => array(),
			'i' => array(
				'class'     => array()
			),
			'p' => array(
				'class'     => array()
			),
			'input' => array(
				'type'     => array(),
				'style'     => array(),
				'id'     => array(),
				'name'     => array(),
				'oninput'     => array(),
				'value'     => array(),
				'list'     => array(),
				'placeholder'     => array(),
				'class'     => array()
			)

		);

		echo wp_kses( $html, $allowed_html );
		wp_die();
	}

}
