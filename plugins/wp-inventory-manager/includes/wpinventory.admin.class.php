<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* TODO:
	[ ] For images / media, save with error loses sort.  Fix.
*/

final class WPIMAdmin extends WPIMCore {

	private static $instance;

	/**
	 * Local instance of the item class
	 * @var WPIMItem class
	 */
	private static $item;

	private static $db;

	private static $page_state_cookie = 'wpim_page_state';

	private static $page_state = NULL;

	private static $admin_filters = [
		"inventory_search"      => "search",
		"inventory_sort_by"     => "order",
		"inventory_status"      => "inventory_status",
		"inventory_category_id" => "category_id",
		"inventory_page"        => "page"
	];

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		self::stripslashes();
		self::$self_url = 'admin.php?page=wpinventory';
		self::$item     = new WPIMItem();
		self::$category = new WPIMCategory();
		self::$label    = new WPIMLabel();
		self::$status   = new WPIMStatus();
		self::$db       = new WPIMDB();
		self::prep_sort();
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	// TODO: Review with Cale, consider removing
	public static function stripslashes() {
		if ( ! self::is_wpinventory_page() ) {
			return;
		}

		$_POST    = array_map( 'stripslashes_deep', $_POST );
		$_GET     = array_map( 'stripslashes_deep', $_GET );
		$_COOKIE  = array_map( 'stripslashes_deep', $_COOKIE );
		$_REQUEST = array_map( 'stripslashes_deep', $_REQUEST );
	}

	public function admin_init() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$page = self::request( 'page' );

		self::handle_filter_state( $page );

		if ( 'wpim_manage_settings' !== $page ) {
			return;
		}

		$action = self::get_action();

		if ( 'save' == $action || 'save-force' == $action ) {
			if ( self::save_settings() ) {
				delete_transient( 'wpim_full_add_ons' );
				set_transient( 'wpim_flush_rewrite_rules', TRUE );
				wp_redirect( admin_url( 'admin.php?page=wpim_manage_settings&message=save' ) );
				die();
			}
		}

		if ( ! session_id() ) {
			session_start();
		}
	}

	/**
	 * Attempt to process the search / filter / pagination states for the admin listing,
	 * and preserve that state between or after doing a search, edit, save.
	 *
	 * @param string $page
	 */
	private function handle_filter_state( $page ) {
		if ( 'wpim_manage_inventory_items' == $page ) {
			$viewing_add_or_edit = ( 'edit' == self::request( 'action' ) );
			$saving_add_or_edit  = ( 'save' == self::request( 'action' ) && self::request( 'inventory_name' ) );
			$state               = [ 'counter' => 1 ];
			$new_filters         = FALSE;

			// When adding / editing, some of these values WILL be set, which will bork the filter, so don't check in this case
			if ( $viewing_add_or_edit || $saving_add_or_edit ) {
				$existing = self::get_admin_page_state();
				if ( ! empty( $existing ) ) {
					// refresh, tracking counter just in case
					$existing['counter'] = ( ! empty( $existing['counter'] ) ) ? ++ $existing['counter'] : 1;
					self::store_admin_page_state( $existing );
				}

				return;
			}

			// Load the filter values from the form
			if ( self::request( 'inventory_filter' ) ) {
				foreach ( self::$admin_filters AS $key => $name ) {
					if ( FALSE !== self::request( $key, FALSE ) ) {
						$new_filters   = TRUE;
						$state[ $key ] = self::request( $key );
					}
				}
			}

			// The below should ONLY run if not viewing / editing / saving an item
			if ( $new_filters ) {
				self::store_admin_page_state( $state );
			} else {
				$existing = self::get_admin_page_state();

				if ( ! empty( $existing ) ) {
					// refresh, tracking counter just in case
					$existing['counter'] = ( ! empty( $existing['counter'] ) ) ? ++ $existing['counter'] : 1;
					self::store_admin_page_state( $existing );
				}
			}
		} else {
			// flush the saved filter state when NOT on either "Listing" or "Add / Edit" item
			$existing = self::get_admin_page_state();

			if ( $existing ) {
				unset( $_COOKIE[ self::$page_state_cookie ] );
				self::store_admin_page_state( FALSE );
			}
		}
	}

	private static function apply_admin_page_state( $existing = NULL ) {
		if ( NULL === $existing ) {
			$existing = self::get_admin_page_state();
		}

		if ( empty( $existing ) ) {
			return;
		}

		// assign in so filter state is stored
		foreach ( $existing AS $key => $value ) {
			// ONLY set if not already set - otherwise may be stomping "Save" fields....
			$_POST[ $key ] = $value;
		}

		if ( ! empty( $_POST['inventory_sort_by'] ) ) {
			$_POST['sortby'] = sanitize_text_field( $_POST['inventory_sort_by'] );
		}
	}

	private static function get_admin_page_state() {
		if ( NULL !== self::$page_state ) {
			return self::$page_state;
		}

		// This data is escaped when the results of this function are looped through in other locations
		$existing = ( ! empty( $_COOKIE[ self::$page_state_cookie ] ) ) ? $_COOKIE[ self::$page_state_cookie ] : FALSE;
		if ( $existing ) {
			$existing = @json_decode( $existing, TRUE );
		}

		return $existing;
	}

	private static function store_admin_page_state( $state ) {
		self::$page_state = $state;

		$expire = time() + HOUR_IN_SECONDS;

		if ( empty( $state ) ) {
			$expire           = 1;
			$state            = FALSE;
			self::$page_state = $state;
		} else if ( is_array( $state ) ) {
			$state = json_encode( $state );
		}

		setcookie( self::$page_state_cookie, $state, $expire, ADMIN_COOKIE_PATH, COOKIE_DOMAIN );
	}

	/**
	 * Inventory Status Page
	 * also acts as the "Get Started" page in the right circumstances
	 */
	public static function instructions() {
		$total_items = self::$item->get_all( [], TRUE );

		if ( ! $total_items ) {
			self::get_started();

			return;
		}

		if ( 'keep_default' == self::request( 'action' ) ) {
			delete_option( 'wpim_default_data' );
		}

		if ( 'remove_default' == self::request( 'action' ) ) {
			require_once 'wpinventory.default.php';
			$default = new WPIMDefaultItems();
			$default->delete_default_data();
		}

		$message_data = self::analysis_messages( $total_items );

		$messages   = $message_data['messages'];
		$counts     = $message_data['counts'];
		$count_data = $message_data['count_data'];
		$classes    = $message_data['classes'];

		$items_class   = $classes['items_class'];
		$reserve_class = $classes['reserve_class'];

		$settings = self::$config->get_all();

		$reserve_word = ( (int) $settings['reserve_allow'] ) ? self::__( 'Yes' ) : self::__( 'No' );

		self::admin_heading( self::__( 'WP Inventory Status' ) );


		// TODO: Messages dismissible - track in table, allow "reset"
		// TODO: Update badge count at certain points? Currently only updates when view Status page, AFTER viewing status...

		do_action( 'wpim_status_dashboard_before', $counts, $messages, $count_data );

		self::dashboard_panel( self::__( 'Inventory Items' ), self::__( 'Visible Items' ), $count_data['total_items'], $counts['items'], $items_class );

		do_action( 'wpim_status_dashboard_after_items', $counts, $messages, $count_data );

		self::dashboard_panel( self::__( 'Reserve' ), self::__( 'Can Reserve Items' ), $reserve_word, $counts['reserve'], $reserve_class );

		do_action( 'wpim_status_dashboard_after', $counts, $messages, $count_data );

		usort( $messages, [ __CLASS__, 'usort_messages' ] );

		foreach ( $messages AS $key => $message ) {
			$message = apply_filters( 'wpim_status_' . $key, $message );
			self::status_message( $message );
		}

		self::admin_footer();
	}

	/**
	 * Runs a variety of analyses to determine if everything looks healthy / good or not.
	 * Attempts to find common issues, report them to the user, so the user can resolve them.
	 *
	 * @param null $total_items
	 *
	 * @return mixed
	 */
	public static function analysis_messages( $total_items = NULL ) {
		$messages         = [];
		$classes          = [];
		$counts           = [];
		$count_data       = [];
		$on_status_screen = FALSE;

		// Rebuild slugs if that action was passed in....
		$action = self::request( 'action' );
		if ( 'rebuild_slugs' == $action ) {
			$item_count = self::repair_slugs();
			$cat_count  = self::repair_category_slugs();

			echo '<div class="notice notice-success"><p>' . sprintf( self::__( '%d Inventory Item slugs and %d Category slugs repaired.' ), $item_count, $cat_count ) . '</p></div>';
		}

		$current_screen = get_current_screen();
		if ( ! empty( $current_screen->id ) ) {
			$current_screen   = $current_screen->id;
			$on_status_screen = ( 'toplevel_page_wpinventory' == $current_screen );
		}

		if ( NULL === $total_items ) {
			$total_items = self::$item->get_all( [], TRUE );
		}

		$all_items = $total_items;

		$using_default_items = get_option( 'wpim_default_data' );


		$settings = self::$config->get_all();

		$settings_url = admin_url( 'admin.php?page=wpim_manage_settings' );

		$urls                     = [];
		$urls['settings_license'] = $settings_url;
		$urls['settings_display'] = $settings_url . '#itemdisplay';

		$urls['display'] = admin_url( 'admin.php?page=wpim_manage_display' );
		$urls['status']  = admin_url( 'admin.php?page=wpinvetory' );

		foreach ( $urls AS $key => $url ) {
			$urls[ $key ] = '<a target="_blank" href="' . $url . '">';
		}

		$close_a = '</a>';

		$counts['items']              = [];
		$counts['items']['all_items'] = sprintf( self::__( '<span>%s</span> total items' ), number_format( $all_items, 0 ) );

		$items_class = [];

		if ( ! empty( $using_default_items ) ) {
			$item_count         = ( ! empty( $using_default_items['items'] ) ) ? count( $using_default_items['items'] ) : 0;
			$category_count     = ( ! empty( $using_default_items['categories'] ) ) ? count( $using_default_items['categories'] ) - 1 : 0;
			$media_count        = ( ! empty( $using_default_items['media'] ) ) ? count( $using_default_items['media'] ) : 0;
			$name               = sprintf( self::__( "Are you sure you want to permanently delete %s inventory items, %s categories, and the %s images / media that belong to those items?\nNOTE: If you have modified any of the default items, they will still be removed.\nContinue and Delete?" ), $item_count, $category_count, $media_count );
			$remove_default_url = '<a class="delete wpim-delete" href="' . admin_url( 'admin.php?page=wpinventory&action=remove_default' ) . '" data-prompt="' . $name . '">';
			$keep_default_url   = '<a href="' . admin_url( 'admin.php?page=wpinventory&action=keep_default' ) . '">';

			$messages['default_items'] = [
				'message'  => sprintf( self::__( 'The default inventory items are still in the system.  
				    Would you like to %sRemove default items?%s 
				    (Note: this also removes the Inventory Categories, and their images and media) 
				    Or, would you like to %sKeep The Default Items%s? 
				    (You can always delete them one at a time later)' ), $remove_default_url, $close_a, $keep_default_url, $close_a ),
				'class'    => 'notice',
				'priority' => 1
			];
		}

		if ( empty( $settings['theme'] ) ) {
			$messages['theme'] = [
				'message'  => sprintf( self::__( 'You have not selected a theme in %sGeneral Settings%s, so your inventory may not display with attractive styles.' ), $urls['settings_display'], $close_a ),
				'class'    => 'notice',
				'priority' => 100
			];
		}

		/**
		 * Display Issues
		 */
		if ( ! empty( $settings['hide_low'] ) ) {
			$low_quantity = (int) $settings['hide_low_quantity'];

			$count_low_quantity = self::$db->get_var( 'SELECT count(*) FROM ' . self::$db->inventory_table . ' WHERE inventory_quantity <= ' . (int) $low_quantity );

			if ( $count_low_quantity && ( $count_low_quantity / $total_items > .2 ) ) {
				$message    = sprintf( self::__( '"Hide Low Quantities" is turned on in %sGeneral Settings%s, which is hiding many of your items (%d items).' ), $urls['settings_display'], $close_a, $count_low_quantity );
				$this_class = 'warning';
				$priority   = 10;

				if ( $count_low_quantity >= $total_items ) {
					$message    = sprintf( self::__( 'No items will show on the front end: "Hide Low Quantities" is turned on in %sGeneral Settings%s, and all items are below that quantity.' ), $urls['settings_display'], $close_a );
					$this_class = 'danger';
					$priority   = 1;
				}

				$messages['display_low'] = [
					'message'  => $message,
					'class'    => $this_class,
					'priority' => $priority
				];

				$items_class['display_low'] = $this_class;
			}

			$total_items -= $count_low_quantity;

			$counts['items']['low_quantity'] = sprintf( self::__( '<span>%d</span> hidden by low quantity' ), $count_low_quantity );
		}

		if ( empty( $settings['display_listing'] ) ) {
			$messages['display_listing'] = [
				'message'  => sprintf( self::__( 'You are using the default "Listing" fields on the front end: You can change the fields to show in the %sDisplay Settings%s' ), $urls['display'], $close_a ),
				'class'    => 'warning',
				'priority' => 1
			];

			$items_class['display_listing'] = 'danger';

			$counts['items']['display_listing'] = sprintf( self::__( '<span>%d</span> hidden due to display settings' ), $all_items );
			$total_items                        = apply_filters( 'wpim_status_display_item_count', 0, $total_items );
		}

		if ( empty( $settings['display_detail'] ) ) {
			$messages['display_detail'] = [
				'message'  => sprintf( self::__( 'You are using the default "Detail" fields on the front end: You can change the fields to show in the %sDisplay Settings%s' ), $urls['display'], $close_a ),
				'class'    => 'warning',
				'priority' => 10
			];

			$items_class['display_detail'] = 'warning';
		}

		// TODO: May not be accurate for Themify, Divi
		$shortcodes = wpinventory_find_shortcode();
		$shortcodes = array_map( function ( $s ) {
			return $s->ID;
		}, $shortcodes );

		if ( empty( $shortcodes ) ) {
			$messages['no_shortcodes'] = [
				'message'  => self::__( 'Inventory may not display on the front end: It does not appear that the [wpinventory] shortcode is installed on any pages or posts. NOTE: If you are using Divi or Themify or other Builder themes, this could be a false positive.' ),
				'class'    => 'danger',
				'priority' => 0
			];

			$items_class['no_shortcodes'] = 'danger';
		}

		if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_on_front' ) && in_array( get_option( 'page_on_front' ), $shortcodes ) && ! $settings['shortcode_on_home'] ) {
			$messages['shortcode_on_home'] = [
				'message'  => sprintf( self::__( 'Inventory Detail will not show: The [wpinventory] shortcode is on the home page, but the "Shortcode on Home" setting is not set to "Yes".  You need to turn it on in %sGeneral Settings%s' ), $urls['settings_display'], $close_a ),
				'class'    => 'danger',
				'priority' => 10
			];

			$items_class['shortcode_on_home'] = 'warning';
		}

		$inactive_statii = self::$status->get_inactive();

		if ( ! empty( $inactive_statii ) ) {
			$inactive_statii = array_map( function ( $s ) {
				return $s['status_id'];
			}, $inactive_statii );

			$inactive_statii      = implode( ',', $inactive_statii );
			$count_inactive_items = self::$db->get_var( 'SELECT count(*) FROM ' . self::$db->inventory_table . ' WHERE inventory_status IN (' . $inactive_statii . ')' );

			if ( $count_inactive_items && $count_inactive_items > 2 && ( $count_inactive_items / $all_items ) > .2 ) {

				$message    = sprintf( self::__( 'Many items will not show on the front end: %d items are set to an inactive status.' ), $count_inactive_items );
				$this_class = 'warning';
				$priority   = 10;

				if ( $count_inactive_items >= $all_items ) {
					$message    = self::__( 'No items will show on the front end: all of the items are set to an "Inactive" status.' );
					$this_class = 'danger';
					$priority   = 1;
				}

				$messages['display_inactive'] = [
					'message'  => $message,
					'class'    => $this_class,
					'priority' => $priority
				];

				$items_class['display_inactive'] = $this_class;
			}
		}

		$seo_urls = self::$config->get( 'seo_urls' );

		if ( $seo_urls ) {
			$slugs = self::$db->get_results( "SELECT inventory_slug, count(*) AS number FROM `" . self::$db->inventory_table . "` GROUP BY inventory_slug HAVING count(*) > 1 " );
			if ( ! empty( $slugs ) ) {
				$duplicate_slugs = array_map( function ( $s ) {
					return $s->number;
				}, $slugs );

				$duplicate_slugs = array_sum( $duplicate_slugs );

				$rebuild_slugs_url = '<a href="' . add_query_arg( 'action', 'rebuild_slugs', admin_url( self::$self_url ) ) . '">';

				$messages['display_inactive'] = [
					'message'  => sprintf( self::__( "%d items have the same slug, and since you are using SEO url's, this means that they will not display properly when viewing the single item on the front-end. Click to %sRebuild Slugs Now%s" ), $duplicate_slugs, $rebuild_slugs_url, $close_a ),
					'class'    => 'danger',
					'priority' => 1
				];
			}
		}

		if ( $on_status_screen ) {
			// load available columns
			$columns = self::$db->get_results( 'SHOW COLUMNS FROM ' . self::$db->inventory_table );
			$columns = array_map( function ( $row ) {
				return $row->Field;
			}, $columns );

			$labels = self::get_labels();
			$labels = array_filter( $labels, function ( $label ) {
				return $label['is_numeric'];
			} );

			$mixed = [];

			foreach ( $labels AS $field => $data ) {
				// only check fields that exist in the table!
				if ( in_array( $field, $columns ) ) {
					$mixed[ $field ] = (int) self::$db->get_var( "SELECT count(*) FROM " . self::$db->inventory_table . " WHERE `{$field}` REGEXP '[A-Za-z]'" );
				}
			}

			if ( ! empty( $mixed ) ) {
				foreach ( $mixed AS $field => $count ) {
					if ( $count ) {
						$label                                = $labels[ $field ]['label'];
						$messages[ 'numeric_sort_' . $field ] = [
							'message'  => sprintf( self::__( 'Numeric sort has been selected for the field "%s", but there are %d items with letters in the field.  This will result in unexpected sorting for this field.' ), $label, $count ),
							'class'    => 'warning',
							'priority' => 100
						];
					}
				}
			}
		}

		/**
		 * BEGIN: Reserve Form Issues
		 */
		$counts['reserve'] = [];
		$reserve_on        = (int) $settings['reserve_allow'];
		$reserve_class     = 'success';

		if ( $reserve_on ) {
			$word                                  = ( (int) $settings['reserve_quantity'] ) ? self::__( 'Can' ) : self::__( 'Can Not' );
			$counts['reserve']['reserve_quantity'] = sprintf( self::__( 'Users <span>%s</span> set quantity' ), $word );

			$word                                   = ( (int) $settings['reserve_decrement'] ) ? self::__( 'Do' ) : self::__( 'Do Not' );
			$counts['reserve']['reserve_decrement'] = sprintf( self::__( 'Reservations <span>%s</span> reduce item quantity' ), $word );

			$word                                      = ( (int) $settings['reserve_confirmation'] ) ? self::__( 'Are' ) : self::__( 'Are Not' );
			$counts['reserve']['reserve_confirmation'] = sprintf( self::__( 'Emails <span>%s</span> sent to submitter' ), $word );
			$to_email                                  = wpinventory_get_config( 'reserve_email' );
			if ( ! $to_email ) {
				$to_email = get_option( 'admin_email' );
			}

			$counts['reserve']['reserve_email'] = sprintf( self::__( 'Notifications sent to <span>%s</span>' ), $to_email );

			// TODO: This is fundamentally borked.  Need to PROBABLY apply filters to the display_detail count, so AIM can hook in.
			// TODO: BUT - need to check that AIM settings are USED on reserve email...
			if ( empty( $settings['display_detail'] ) ) {
				// Reserve: fields not set up on "details" page will cause item to not list in EMAIL
				$messages['reserve_display_detail'] = [
					'message'  => sprintf( self::__( 'Reserve emails will not include item details. You need to set the "Detail" fields to show in the %sDisplay Settings%s' ), $urls['display'], $close_a ),
					'class'    => 'warning',
					'priority' => 10
				];
			}

		} else {
			$reserve_class = 'danger';
		}

		$count_data ['all_items']  = $all_items;
		$count_data['total_items'] = $total_items;

		$messages      = apply_filters( 'wpim_status_warning_messages', $messages );
		$counts        = apply_filters( 'wpim_status_counts_messages', $counts, $count_data );
		$items_class   = apply_filters( 'wpim_status_items_panel_class', $items_class, $count_data );
		$reserve_class = apply_filters( 'wpim_status_reserve_panel_class', $reserve_class, $count_data );

		$classes['items_class']   = $items_class;
		$classes['reserve_class'] = $reserve_class;

		// Set and save the number of "notifications" for the Admin Menu count
		$danger = array_map( function ( $m ) {
			return ( ! empty( $m['class'] ) && 'danger' == $m['class'] );
		}, $messages );

		$danger = array_filter( $danger );

		self::$config->set( 'status_notifications', $danger );

		$statii = [
			'messages'   => $messages,
			'counts'     => $counts,
			'classes'    => $classes,
			'count_data' => $count_data
		];

		return apply_filters( 'wpim_status_array', $statii );
	}

	/**
	 * Utility method to sort the "Status" messages.
	 * Attempts to sort by priority, if no priority set, then uses message classes ("danger", "warning", etc")
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public static function usort_messages( $a, $b ) {

		$ap = ( isset( $a['priority'] ) ) ? $a['priority'] : FALSE;
		$bp = ( isset( $b['priority'] ) ) ? $b['priority'] : FALSE;

		if ( FALSE !== $ap && FALSE !== $bp ) {
			if ( $ap == $bp ) {
				return 0;
			}

			return ( $ap < $bp ) ? - 1 : 1;
		}

		$a = ( isset( $a['class'] ) ) ? $a['class'] : '';
		$b = ( isset( $b['class'] ) ) ? $b['class'] : '';

		if ( $a == $b ) {
			return 0;
		}

		$classes = [ 'danger', 'warning', 'notice', 'success' ];

		foreach ( $classes AS $class ) {
			if ( $a == $class ) {
				return - 1;
			}

			if ( $b == $class ) {
				return 1;
			}
		}
	}

	/**
	 * Utility to set the class to the "minimum".
	 * For example, if the current class is "warning", but the "minimum" is "danger", sets the class to "danger"
	 *
	 * @param string $class
	 * @param string $minimum
	 *
	 * @return string mixed
	 */
	public static function minimum_class( $class, $minimum = '' ) {
		$classes = [ 'danger', 'warning', 'notice', 'success' ];
		if ( is_array( $class ) ) {
			foreach ( $classes AS $name ) {
				if ( in_array( $name, $class ) ) {
					return $name;
				}
			}
		}

		if ( empty( $minimum ) ) {
			if ( empty( $class ) ) {
				return 'success';
			}

			return $class;
		}

		foreach ( $classes AS $name ) {
			if ( $name == $class || $name == $minimum ) {
				return $name;
			}
		}

		return $class;
	}

	/**
	 * Output a "Status" panel (on "WP Inventory" => "Status")
	 *
	 * @param string       $title
	 * @param string       $primary_label
	 * @param int          $count
	 * @param array        $counts
	 * @param string|array $class
	 */
	public static function dashboard_panel( $title, $primary_label, $count, $counts = [], $class = 'success' ) {
		$class  = self::minimum_class( $class );
		$number = ( is_numeric( $count ) ) ? number_format( max( 0, $count ), 0 ) : $count;
		echo '<div class="wpim-status-panel wpim-status-' . $class . '">';
		echo '<h3>' . $title . '</h3>';
		echo '<div class="status-primary">';
		echo '<p>' . $number . '</p>';
		echo '<span>' . $primary_label . '</span>';
		echo '</div>';

		if ( $counts ) {
			echo '<div class="status-related">';
			foreach ( $counts AS $count ) {
				echo '<p class="status-sub">' . $count . '</p>';
			}
			echo '</div>';
		}

		echo '</div>';
	}

	private static function status_message( $message, $type = 'warning' ) {
		if ( is_array( $message ) ) {
			if ( ! empty( $message['class'] ) ) {
				$type = $message['class'];
			}

			if ( ! empty( $message['message'] ) ) {
				$message = $message['message'];
			}
		}

		echo '<div class="wpim_notice wpim_status_notice wpim_' . $type . '">' . $message . '</div>';
	}

	public static function get_started() {
		self::admin_heading( self::__( 'Instructions' ) );

		echo '<h3>' . self::__( 'Quick-Start Guide' ) . '</h3>';
		echo '<p>' . self::__( 'Before you begin setting up your items, you should follow these steps:' ) . '</p>' . PHP_EOL;
		echo '<ol>';
		echo '<li>' . self::__( 'Set up your' ) . ' <a href="admin.php?page=wpim_manage_categories">' . self::__( 'Inventory Categories' ) . '</a></li>' . PHP_EOL;
		echo '<li>' . self::__( 'Configure the' ) . ' <a href="admin.php?page=wpim_manage_settings">' . self::__( 'Settings' ) . '</a></li>' . PHP_EOL;
		echo '<li>' . self::__( 'Configure labels' ) . ' <a href="admin.php?page=wpim_manage_labels">' . self::__( 'Labels' ) . '</a></li>' . PHP_EOL;
		echo '<li>' . self::__( 'Set Display Options' ) . ' <a href="admin.php?page=wpim_manage_display">' . self::__( 'Display' ) . '</a></li>' . PHP_EOL;
		echo '<li>' . self::__( 'Then, you can ' ) . '<a href="admin.php?page=wpim_manage_inventory_items">' . self::__( 'Add Inventory Items' ) . '</a></li>' . PHP_EOL;
		echo '<li>' . self::__( 'Finally, install the shortcode' ) . ' [wpinventory] ' . self::__( 'on any pages you would like to display inventory.' ) . '</li>' . PHP_EOL;
		echo '</ol>';
		self::admin_footer();
	}

	public static function wpim_manage_inventory_items() {
		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action       = self::get_action();
		$nonce        = wp_create_nonce( $action . '-item' );
		$inventory_id = self::request( 'inventory_id' );

		if ( 'save' == $action ) {
			if ( self::save_item() ) {
				$action        = '';
				self::$message = self::__( 'Inventory Item' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		} else if ( 'delete' == $action ) {
			$inventory_id = self::request( 'delete_id' );
			$success      = self::delete_item( $inventory_id, self::request( 'wp_nonce' ) );
			$action       = '';
		}

		self::admin_heading( self::__( 'Manage Inventory Items' ) );

		if ( $action == 'edit' || $action == 'add' || $action == 'duplicate' ) {
			if ( 'duplicate' == $action ) {
				$inventory_id = self::request( 'duplicate_id' );
			}

			self::edit_item( $inventory_id, $action );
		}

		if ( ! $action ) {
			self::list_items();
		}

		self::admin_footer();
	}

	/**
	 * Returns a list of fields that are ignored / not used in the admin listing.
	 *
	 * @param bool $include_labels - to make the array an associative array of "field" => "Label"
	 *
	 * @return array
	 */
	private static function admin_ignore_columns( $include_labels = FALSE ) {
		$ignore_columns = [ 'inventory_image', 'inventory_images', 'inventory_media' ];

		if ( $include_labels ) {
			$iterate        = $ignore_columns;
			$ignore_columns = [];
			foreach ( $iterate AS $column ) {
				$ignore_columns[ $column ] = self::get_label( $column );
			}
		}

		return $ignore_columns;
	}


	/**
	 * View for displaying the inventory items in the admin dashboard.
	 */
	public static function list_items() {
		self::apply_admin_page_state();

		$inventory_display = wpinventory_get_display_settings( 'admin' );

		$columns        = [];
		$name_columns   = [ 'inventory_name', 'inventory_description' ];
		$ignore_columns = self::admin_ignore_columns();

		foreach ( $inventory_display AS $item ) {
			$class = ( in_array( $item, $name_columns ) ) ? 'name' : 'medium';
			if ( ! in_array( $item, $ignore_columns ) ) {
				$columns[ $item ] = [
					'title' => self::get_label( $item ),
					'class' => $class
				];
			}
		}

		echo wpinventory_filter_form_admin();

		$args = [];

		foreach ( self::$admin_filters AS $filter => $field ) {
			if ( self::request( $filter ) ) {
				$args[ $field ] = self::request( $filter );
			}
		}

		$args = self::permission_args( $args );

		$loop = new WPIMLoop( $args );

		global $wpinventory_item;

		?>
		<?php if ( self::check_permission( 'add_item', FALSE ) ) { ?>
        <div class="wpinventory-add-container"><a class="button button-primary"
                                                  href="<?php echo self::$self_url; ?>&action=add"><?php self::_e( 'Add Inventory Item' ); ?></a>
        </div>
		<?php } ?>
		<?php do_action( 'wpim_admin_items_pre_listing', $loop->get_query_args() ); ?>
      <table class="grid itemgrid">
		  <?php

		  $include_id = (int) apply_filters( 'wpim_show_item_id_in_locations_listing', wpinventory_get_config( 'show_item_id_in_admin_listing' ) );
		  $hash       = ( 'wpim_manage_inventory_items' !== sanitize_text_field( $_GET['page'] ) ) ? '' : '';

		  echo self::grid_columns( $columns, apply_filters( 'wpim_item_list_sort_url', self::$self_url ), 'inventory_number', FALSE, $hash, $include_id );
		  while ( $loop->have_items() ) {
			  $loop->the_item();
			  $edit_url      = ( self::check_permission( 'view_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=edit&inventory_id=' . $wpinventory_item->inventory_id : '';
			  $delete_url    = ( self::check_permission( 'edit_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=delete&delete_id=' . $wpinventory_item->inventory_id . '&wp_nonce=' . wp_create_nonce( 'delete-item-' . $wpinventory_item->inventory_id ) : '';
			  $duplicate_url = ( self::check_permission( 'view_item', $wpinventory_item->inventory_id ) ) ? self::$self_url . '&action=duplicate&duplicate_id=' . $wpinventory_item->inventory_id : '';

			  if ( ! $edit_url ) {
				  continue;
			  }

			  $class = '';

			  if ( ( (int) self::$config->get( 'low_quantity_alert' ) ) && ( $wpinventory_item->inventory_quantity <= apply_filters( 'wpim_low_quantity_amount', self::$config->get( 'low_quantity_amount' ), $wpinventory_item->inventory_id ) ) ) {
				  $class = ' class="warning"';
			  }

			  ?>
            <tr data-id="<?php esc_attr_e( $wpinventory_item->inventory_id ); ?>"<?php esc_attr_e( $class ); ?>>
				<?php
				if ( (int) wpinventory_get_config( 'show_item_id_in_admin_listing' ) ) {
					echo '<td>' . $wpinventory_item->inventory_id . '</td>';
				}
				foreach ( $columns as $field => $data ) {
					$field = ( $field == 'category_id' ) ? 'inventory_category' : $field;
					$field = ( $field == 'inventory_updated_by' ) ? 'updated_by_name' : $field;

					$url = $edit_url;
					if ( $field == 'user_id' || $field == 'inventory_user_id' ) {
						$url = get_edit_user_link( $wpinventory_item->{$field} );
					}
					echo '<td class="' . $field . '"><a href="' . $url . '">' . $loop->get_field( $field ) . '</a></td>';
				}
				?>
              <td class="action">
				  <?php if ( $edit_url ) { ?>
                    <a href="<?php echo esc_url( $edit_url ); ?>"><span class="dashicons dashicons-edit"></span><span
                          class="tip"><?php self::_e( 'edit item' ); ?></span></a>
				  <?php }
				  if ( $delete_url ) { ?>
                    <a class="delete" data-name="<?php esc_attr_e( $wpinventory_item->inventory_name ); ?>"
                       href="<?php echo esc_url( $delete_url ); ?>"><span class="dashicons dashicons-trash"></span><span
                          class="tip"><?php self::_e( 'delete item' ); ?></span></a>
				  <?php }
				  if ( $duplicate_url ) { ?>
                    <a class="duplicate" data-name="<?php esc_attr_e( $wpinventory_item->inventory_name ); ?>"
                       href="<?php echo esc_url( $duplicate_url ); ?>"><span class="dashicons dashicons-admin-page"></span><span
                          class="tip"><?php self::_e( 'duplicate item' ); ?></span></a>
				  <?php } ?>
				  <?php do_action( 'wpim_admin_action_links', $wpinventory_item->inventory_id ); ?>
              </td>
            </tr>
		  <?php } ?>
      </table>

		<?php
		echo wpinventory_pagination( self::$self_url, $loop->get_pages() );
		do_action( 'wpim_admin_items_listing', $loop->get_query_args() );
	}

	/**
	 * Creates the admin view for editing an inventory item.
	 *
	 * @param int    $inventory_id
	 * @param string $action
	 */
	public static function edit_item( $inventory_id = NULL, $action = 'edit' ) {
		if ( 'edit' == $action && ! self::check_permission( 'edit_item', $inventory_id ) ) {
			echo '<div class="error"><p>' . self::__( 'You do not have permission to edit this item.' ) . '</p></div>';

			return;
		}

		$item        = NULL;
		$image       = [];
		$media       = [];
		$media_title = [];

		// trigger this here to ensure any default configs filtered in are properly loaded
		self::$config->loadConfig();
		$fields = self::get_item_fields();
		// TODO: Someday, set these manually
		foreach ( $fields AS $f ) {
			if ( ! isset( ${$f} ) ) {
				${$f} = '';
			}
		}

		$inventory_status = self::$status->get_active();
		if ( empty( $inventory_status ) ) {
			$inventory_status = self::$status->get_all();
		}

		$inventory_status = reset( $inventory_status );
		$inventory_status = $inventory_status['status_id'];


		if ( isset( $_POST['inventory_name'] ) ) {
			$inventory_item_id           = self::request( 'inventory_item_id' );
			$inventory_number            = self::request( 'inventory_number' );
			$inventory_name              = self::request( 'inventory_name' );
			$inventory_slug              = self::request( 'inventory_slug' );
			$inventory_status            = self::request( 'inventory_status' );
			$category_id                 = self::request( 'category_id' );
			$inventory_description       = self::request( 'inventory_description', '', 'wysiwyg' );
			$inventory_size              = self::request( 'inventory_size' );
			$inventory_manufacturer      = self::request( 'inventory_manufacturer' );
			$inventory_make              = self::request( 'inventory_make' );
			$inventory_model             = self::request( 'inventory_model' );
			$inventory_year              = self::request( 'inventory_year' );
			$inventory_serial            = self::request( 'inventory_serial' );
			$inventory_fob               = self::request( 'inventory_fob' );
			$inventory_quantity          = self::request( 'inventory_quantity' );
			$inventory_quantity_reserved = self::request( 'inventory_quantity_reserved' );
			$inventory_price             = self::request( 'inventory_price' );
			$inventory_sort_order        = self::request( 'inventory_sort_order' );

			$inventory_id = $inventory_item_id;
		} else if ( $inventory_id ) {
			$item = self::get_item( $inventory_id );

			if ( empty( $item->inventory_id ) ) {
				echo '<img class="not_the_page" src="' . self::$PLUGIN_URL . 'images/obiwonkenobi.jpg">';
				return;
			}

			$inventory_number            = $item->inventory_number;
			$inventory_name              = $item->inventory_name;
			$inventory_slug              = $item->inventory_slug;
			$inventory_status            = $item->inventory_status;
			$category_id                 = $item->category_id;
			$inventory_description       = $item->inventory_description;
			$inventory_size              = $item->inventory_size;
			$inventory_manufacturer      = $item->inventory_manufacturer;
			$inventory_make              = $item->inventory_make;
			$inventory_model             = $item->inventory_model;
			$inventory_year              = $item->inventory_year;
			$inventory_serial            = $item->inventory_serial;
			$inventory_fob               = $item->inventory_fob;
			$inventory_quantity          = $item->inventory_quantity;
			$inventory_quantity_reserved = $item->inventory_quantity_reserved;
			$inventory_price             = $item->inventory_price;
			$inventory_sort_order        = $item->inventory_sort_order;

			$image       = self::get_item_images( $inventory_id );
			$media       = self::get_item_media( $inventory_id );
			$media_title = [];
			foreach ( $media AS $i => $m ) {
				$media_title[ $i ] = $m->media_title;
			}
		}

		do_action( 'wpim_admin_pre_edit_item', $item );

		?>
      <form method="post" id="inventory_item_form" action="<?php echo self::$self_url; ?>">
        <table class="form-table">
			<?php do_action( 'wpim_admin_edit_form_start', $item, $inventory_id ); ?>
          <tr class="inventory_number">
            <th><label for="inventory_number"><?php self::label( 'inventory_number' ); ?></label></th>
            <td><input type="text" name="inventory_number" class="regular-text"
                       value="<?php echo esc_attr( $inventory_number ); ?>"/></td>
          </tr>
			<?php
			do_action( 'wpim_admin_edit_form_after_number', $item, $inventory_id );
			?>
          <tr class="inventory_name">
            <th><label for="inventory_name"><?php self::label( 'inventory_name' ); ?></label></th>
            <td><input type="text" name="inventory_name" class="regular-text"
                       value="<?php echo esc_attr( $inventory_name ); ?>"/></td>
          </tr>
			<?php
			do_action( 'wpim_admin_edit_form_after_name', $item, $inventory_id );
			if ( self::getOption( 'seo_urls' ) ) { ?>
              <tr class="inventory_slug">
                <th><label for="inventory_slug"><?php self::label( 'inventory_slug' ); ?></label></th>
                <td><input type="text" name="inventory_slug"
                           value="<?php echo esc_attr( $inventory_slug ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_slug', $item, $inventory_id );
			?>
          <tr class="inventory_status">
            <th><label for="inventory_status"><?php self::label( 'inventory_status' ); ?></label></th>
            <td><?php echo self::$status->dropdown( 'inventory_status', $inventory_status ); ?></td>
          </tr>
			<?php
			do_action( 'wpim_admin_edit_form_after_status', $item, $inventory_id );
			?>
          <tr class="inventory_category_id">
            <th><label for="category_id"><?php self::label( 'category_id' ); ?></label></th>
            <td><?php echo self::$category->dropdown( 'category_id', $category_id ); ?></td>
          </tr>
			<?php
			do_action( 'wpim_admin_edit_form_after_category', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_description' ) ) { ?>
              <tr class="inventory_description">
                <th><?php self::label( 'inventory_description' ); ?></th>
                <td><?php wp_editor( $inventory_description, 'description', [
						'media_buttons' => FALSE,
						'textarea_name' => 'inventory_description'
					] ); ?></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_description', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_size' ) ) { ?>
              <tr class="inventory_size">
                <th><label for="inventory_size"><?php self::label( 'inventory_size' ); ?></label></th>
                <td><input type="text" name="inventory_size" class="regular-text"
                           value="<?php echo esc_attr( $inventory_size ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_size', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_manufacturer' ) ) { ?>
              <tr class="inventory_manufacturer">
                <th>
                  <label for="inventory_manufacturer"><?php self::label( 'inventory_manufacturer' ); ?></label>
                </th>
                <td><input type="text" name="inventory_manufacturer" class="regular-text"
                           value="<?php echo esc_attr( $inventory_manufacturer ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_manufacturer', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_make' ) ) { ?>
              <tr class="inventory_make">
                <th><label for="inventory_make"><?php self::label( 'inventory_make' ); ?></label></th>
                <td><input type="text" name="inventory_make" class="regular-text"
                           value="<?php echo esc_attr( $inventory_make ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_make', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_model' ) ) { ?>
              <tr class="inventory_model">
                <th><label for="inventory_model"><?php self::label( 'inventory_model' ); ?></label></th>
                <td><input type="text" name="inventory_model" class="regular-text"
                           value="<?php echo esc_attr( $inventory_model ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_model', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_year' ) ) { ?>
              <tr class="inventory_year">
                <th><label for="inventory_year"><?php self::label( 'inventory_year' ); ?></label></th>
                <td><input type="text" name="inventory_year" class="regular-text"
                           data-original-value="<?php echo esc_attr( $inventory_year ); ?>"
                           value="<?php echo esc_attr( $inventory_year ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_year', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_serial' ) ) { ?>
              <tr class="inventory_serial">
                <th><label for="inventory_serial"><?php self::label( 'inventory_serial' ); ?></label></th>
                <td><input type="text" name="inventory_serial" class="regular-text"
                           value="<?php echo esc_attr( $inventory_serial ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_serial', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_fob' ) ) { ?>
              <tr class="inventory_fob">
                <th><label for="inventory_fob"><?php self::label( 'inventory_fob' ); ?></label></th>
                <td><input type="text" name="inventory_fob" class="regular-text"
                           value="<?php echo esc_attr( $inventory_fob ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_fob', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_quantity' ) ) { ?>
              <tr class="inventory_quantity">
                <th><label for="inventory_quantity"><?php self::label( 'inventory_quantity' ); ?></label></th>
                <td><input type="text" name="inventory_quantity" class="small-text"
                           value="<?php echo esc_attr( $inventory_quantity ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_quantity', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_quantity_reserved' ) ) { ?>
              <tr class="inventory_quantity_reserved">
                <th>
                  <label for="inventory_quantity_reserved"><?php self::label( 'inventory_quantity_reserved' ); ?></label>
                </th>
                <td><input type="text" name="inventory_quantity_reserved" class="small-text"
                           value="<?php echo esc_attr( $inventory_quantity_reserved ); ?>"/></td>
              </tr>
			<?php }
			do_action( 'wpim_admin_edit_form_after_quantity_reserved', $item, $inventory_id );
			if ( self::label_is_on( 'inventory_price' ) ) { ?>
              <tr class="inventory_price">
                <th><label for="inventory_price"><?php self::label( 'inventory_price' ); ?></label></th>
                <td><input type="text" name="inventory_price" class="medium-text"
                           value="<?php echo esc_attr( $inventory_price ); ?>"/></td>
              </tr>
			<?php } ?>
          <tr class="inventory_images images">
            <th><?php self::label( 'inventory_images' ); ?>
            <td>
				<?php self::item_image_input( $inventory_id, $image ); ?>
            </td>
          </tr>
			<?php
			do_action( 'wpim_admin_edit_form_after_images', $item, $inventory_id );
			?>
          <tr class="inventory_sort_order">
            <th><label for="inventory_sort_order"><?php self::_e( 'Sort Order' ); ?></label></th>
            <td><input type="text" name="inventory_sort_order" class="small-text"
                       value="<?php esc_attr_e( $inventory_sort_order ); ?>"/></td>
          </tr>
			<?php
			do_action( 'wpim_admin_edit_form_after_sort', $item, $inventory_id );
			do_action( 'wpim_admin_edit_form_end', $item, $inventory_id ); ?>
        </table>
		  <?php
		  $collection = [];
		  if ( ! empty( $item ) ) {
			  foreach ( (array) $item AS $key => $value ) {
				  $collection[] = [ 'name' => $key, 'value' => $value ];
			  }

			  $collection = apply_filters( 'wpim_original_item_cdata', $collection );
		  }
		  ?>
        <script>
          var WPIMOriginalItem = <?php echo json_encode( $collection ); ?>;
        </script>
		  <?php do_action( 'wpim_edit_item', $inventory_id ); ?>
        <input type="hidden" name="action" value="save"/>
		  <?php
		  // When duplicating, we want the inventory ID to be set to nothing so system recognizes it's a new item
		  if ( 'duplicate' == $action ) {
			  $inventory_id = NULL;
		  }
		  ?>
        <input type="hidden" name="inventory_item_id" value="<?php esc_attr_e( $inventory_id ); ?>"/>
		  <?php wp_nonce_field( self::NONCE_ACTION, 'nonce' ); ?>
        <p class="submit">
          <a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
			<?php if ( self::check_permission( 'save_item', $inventory_id ) ) { ?>
              <input type="submit" name="save" class="button button-primary"
                     value="<?php self::_e( 'Save Item' ); ?>"/>
			<?php } ?>
        </p>
        <div class="description wpim-last-updated"><?php
			if ( ! empty( $inventory_date_updated ) ) {
				$formatted_date = self::format_date( $inventory_date_updated );
				if ( ! self::$config->get( 'time_format' ) ) {
					$formatted_date = sprintf( self::__( '%s at %s' ), $formatted_date, date( 'H:i:s', strtotime( $inventory_date_updated ) ) );
				}

				if ( ! empty( $inventory_updated_by ) ) {
					$updated_by = get_user_by( 'ID', $inventory_updated_by );
					if ( ! $updated_by ) {
						echo sprintf( self::__( 'last updated by user with ID %d (user missing / deleted)' ), $inventory_updated_by );
						return;
					}

					$updated_name = $updated_by->get( 'display_name' );
					$updated_url  = get_edit_user_link( $inventory_updated_by );
					$link         = '<a href="' . $updated_url . '">' . esc_attr( $updated_name ) . '</a>';
					echo sprintf( self::__( 'Item last updated %s by %s' ), $formatted_date, $link );
				} else {
					echo sprintf( self::__( 'Item last updated %s' ), $formatted_date );
				}
			} ?>
        </div>
      </form>
		<?php
	}

	/**
	 * Creates the image input fields when editing an item.
	 *
	 * @param       $inventory_id
	 * @param array $images_posted
	 */
	public static function item_image_input( $inventory_id, $images_posted = NULL ) {
		$count  = 0;
		$images = [];

		// Load the images
		echo '<div data-type="image" class="mediasortable media-container">';
		if ( $inventory_id ) {
			$images = self::get_item_images( $inventory_id );
		} else if ( $images_posted ) {
			$images = [];
			foreach ( $images_posted AS $key => $image ) {
				$images[ $key ] = (object) [
					'thumbnail' => $image,
					'post_id'   => NULL
				];
			}
		}

		// Loop through the images
		foreach ( (array) $images as $image ) {
			// Output the field for each existing
			if ( $image->thumbnail ) {
				self::item_image_field( $count, $image );
				$count ++;
			}
		}
		// Output one more new one
		self::item_image_field( $count );
		echo '<input type="hidden" name="imagesort" value="" id="imagesort" />';
		echo '</div>';
		echo ( $count > 1 ) ? '<p class="sortnotice">' . self::__( 'Drag and drop images to change sort order' ) . '</p>' : '';
	}

	/**
	 * Creates the markup for a single image input
	 *
	 * @param int    $count
	 * @param string $image
	 */
	private static function item_image_field( $count, $image = NULL, $field_name = '' ) {

		$word = ( $image ) ? 'Change' : 'Add New';
		if ( is_object( $image ) ) {
			$url = '';
			$url = ( ! empty( $image->thumbnail ) ) ? $image->thumbnail : $url;
			$url = ( ! empty( $image->medium ) ) ? $image->medium : $url;
			$url = ( ! empty( $image->large ) ) ? $image->large : $url;
			$url = ( ! empty( $image->full ) ) ? $image->full : $url;
		} else {
			$url = $image;
		}

		if ( ! $field_name ) {
			$field_name = 'image[' . $count . ']';
		}

		echo '<div class="imagewrapper mediawrap" data-count="' . $count . '">';
		echo '<div class="imagecontainer" id="inventory-div-' . $count . '">';
		if ( $url ) {
			if ( is_object( $url ) ) {
				$url = ( ! empty( $url->thumbnail ) ) ? $url->thumbnail : $url->image;
			}
			echo '<img class="image-upload" id="inventory-image-' . $count . '" src="' . esc_url( $url ) . '" />';
			echo '<a href="javascript:removeImage(' . $count . ');" class="delete" id="inventory-delete-' . $count . '" title="Click to remove image">X</a>';
		}
		echo '</div>';
		echo '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="inventory-link-' . $count . '" class="wpinventory-upload">' . $word . ' ' . self::__( 'Image' ) . '</a>';
		echo '<input type="hidden" name="' . $field_name . '" value="' . esc_url( $url ) . '" id="inventory-field-' . $count . '" />';
		echo '</div>';
	}

	public static function item_media_input( $inventory_id, $media_posted = NULL, $media_title_posted = NULL ) {
		$count = 0;
		$media = [];

		// Load the media
		echo '<div data-type="media" class="mediasortable media-container">';
		if ( $inventory_id ) {
			$media = self::get_item_media( $inventory_id );
		} else if ( $media_posted ) {
			$media = [];
			foreach ( $media_posted AS $key => $m ) {
				$media[ $key ] = (object) [
					'media'       => $m,
					'media_title' => $media_title_posted[ $key ],
					'post_id'     => NULL
				];
			}
		}

		// Loop through the images
		foreach ( (array) $media as $item ) {
			// Output the field for each existing
			if ( $item->media ) {
				self::item_media_field( $count, $item );
				$count ++;
			}
		}
		// Output one more new one
		self::item_media_field( $count );
		echo '</div>';
		echo '<input type="hidden" name="mediasort" value="" id="mediasort" />';
		echo '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="inventory-link-' . $count . '" class="button wpinventory-upload">' . self::__( 'Add Media' ) . '</a>';
		echo ( $count > 1 ) ? '<p class="mediasortnotice">' . self::__( 'Drag and drop media to change sort order' ) . '</p>' : '';
	}

	private static function item_media_field( $count, $media = NULL ) {
		$url   = ( ! empty( $media->media ) ) ? $media->media : '';
		$title = ( ! empty( $media->media_title ) ) ? $media->media_title : '';
		if ( $url ) {
			echo '<div class="mediacontainer mediawrap" data-count="' . $count . '" id="inventory-media-' . $count . '">';
			echo '<a href="javascript:removeMedia(' . $count . ');" class="delete" id="inventory-delete-' . $count . '" title="Click to remove Media">X</a>';
			echo '<p><label>' . self::__( 'Title' ) . ':</label><input type="text" class="widefat" name="media_title[' . $count . ']" value="' . esc_attr( $title ) . '" />';
			echo '<p class="media_url"><label>' . self::__( 'URL' ) . ':</label>' . esc_url( $url ) . '</p>';
			echo '<input type="hidden" name="media[' . $count . ']" value="' . esc_url( $url ) . '" id="inventory-media-field-' . $count . '" />';
			echo '</div>';
		}
	}

	/**
	 * Function to save an item.
	 * Checks permission first.
	 * Then loads all the labels that are configured (and can be extended via filter 'wpim_default_labels') and loads
	 * from _$POST
	 *
	 * @return bool
	 */
	public static function save_item() {
		$inventory_slug = '';
		$image          = [];
		$media          = [];

		$inventory_id                = self::request( 'inventory_id' );
		$inventory_name              = self::request( 'inventory_name' );
		$inventory_number            = self::request( 'inventory_number' );
		$inventory_size              = self::request( 'inventory_size' );
		$inventory_manufacturer      = self::request( 'inventory_manufacturer' );
		$inventory_make              = self::request( 'inventory_make' );
		$inventory_model             = self::request( 'inventory_model' );
		$inventory_year              = self::request( 'inventory_year' );
		$inventory_serial            = self::request( 'inventory_serial' );
		$inventory_fob               = self::request( 'inventory_fob' );
		$inventory_quantity          = self::request( 'inventory_quantity' );
		$inventory_quantity_reserved = self::request( 'inventory_quantity_reserved' );
		$inventory_price             = self::request( 'inventory_price' );
		$inventory_status            = self::request( 'inventory_status' );
		$inventory_sort_order        = self::request( 'inventory_sort_order' );
		$category_id                 = self::request( 'category_id' );

		// Rather than extract $_POST, get the specific fields we require
		$fields = self::get_labels();
		foreach ( $fields AS $field => $labels ) {
			${$field} = self::request( $field, '', 'wysiwyg' );
		}

		$inventory_id          = self::request( "inventory_item_id" );
		$inventory_description = self::request( 'inventory_description', '', 'wysiwyg' );

		if ( ! apply_filters( 'wpim_do_save_item', TRUE, $inventory_id ) ) {
			return TRUE;
		}

		if ( ! self::check_permission( 'save_item', $inventory_id ) ) {
			self::$error = self::__( 'You do not have permission to save this item.' );
		}

		if ( ! wp_verify_nonce( self::request( "nonce" ), self::NONCE_ACTION ) ) {
			self::$error = self::__( 'Security failure.  Please try again.' );
		}

		if ( ! $inventory_number && ! $inventory_name ) {
			self::$error = sprintf( self::__( 'Either %s or %s is required.' ), self::get_label( 'inventory_name' ), self::get_label( 'inventory_number' ) );
		}

		$data = [
			'inventory_id'                => $inventory_id,
			'inventory_number'            => $inventory_number,
			'inventory_name'              => $inventory_name,
			'inventory_slug'              => $inventory_slug,
			'inventory_description'       => $inventory_description,
			'inventory_size'              => $inventory_size,
			'inventory_manufacturer'      => $inventory_manufacturer,
			'inventory_make'              => $inventory_make,
			'inventory_model'             => $inventory_model,
			'inventory_year'              => $inventory_year,
			'inventory_serial'            => $inventory_serial,
			'inventory_fob'               => $inventory_fob,
			'inventory_quantity'          => $inventory_quantity,
			'inventory_quantity_reserved' => $inventory_quantity_reserved,
			'inventory_price'             => $inventory_price,
			'inventory_status'            => $inventory_status,
			'inventory_sort_order'        => (int) $inventory_sort_order,
			'category_id'                 => $category_id
		];

		self::$error = apply_filters( 'wpim_save_item_errors', self::$error, $data );

		if ( ! self::$error ) {
			$data = apply_filters( 'wpim_pre_save_data', $data, $inventory_id );
			do_action( 'wpim_pre_save_item', $inventory_id, $data );

			if ( $inventory_id = self::$item->save( $data ) ) {
				$imagesort = explode( ',', self::request( 'imagesort' ) );
				$mediasort = explode( ',', self::request( 'mediasort' ) );

				self::$item->save_images( $inventory_id, self::request( 'image' ), $imagesort );

				do_action( 'wpim_save_item', $inventory_id, $data );

				// Only call this if enabled
				if ( self::$config->get( 'use_media' ) ) {
					self::$item->save_media( $inventory_id, self::request( 'media' ), self::request( 'media_title' ), $mediasort );
				}

				self::analysis_messages();

				return TRUE;
			}
		}
	}

	public static function delete_item( $inventory_id, $nonce ) {
		$inventory_id = (int) self::request( "delete_id" );
		if ( ! wp_verify_nonce( $nonce, 'delete-item-' . $inventory_id ) ) {
			self::$error = self::__( 'There was a problem deleting your inventory item. Invalid permissions.' );

			return FALSE;
		}

		if ( ! $inventory_id ) {
			self::$error = self::__( 'Inventory id not set.  Item not deleted.' );

			return FALSE;
		}

		if ( ! self::$item->delete( $inventory_id ) ) {
			self::$error = self::$item->get_message();

			return FALSE;
		}

		self::$message = self::__( 'Inventory item deleted successfully.' );

		self::analysis_messages();

		return TRUE;
	}

	/**
	 * Mini controller method for handling categories
	 */
	public static function wpim_manage_categories() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action      = self::get_action();
		$category_id = self::request( "category_id" );

		if ( $action == 'save' ) {
			if ( self::save_category() ) {
				$action        = '';
				self::$message = self::__( 'Category' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		if ( $action == 'delete' ) {
			if ( self::delete_category( $category_id ) ) {
				self::$message = self::__( 'Category' ) . ' ' . self::__( 'deleted successfully.' );
			} else {
				self::output_errors();
			}
			$action = '';
		}

		self::admin_heading( self::__( 'Manage Categories' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::edit_category( $category_id );
		}

		if ( ! $action ) {
			self::list_categories();
		}

		self::admin_footer();
	}

	public static function list_categories() {

		$categories = self::get_categories();

		$columns = [
			'category_name'       => [
				'title' => self::__( 'Category' ),
				'class' => 'name'
			],
			'category_sort_order' => [
				'title' => self::__( 'Sort Order' ),
				'class' => 'number'
			]
		];

		$include_id = (int) apply_filters( 'wpim_show_item_id_in_admin_categories_listing', wpinventory_get_config( 'show_item_id_in_admin_listing' ) );


		?>
      <a class="button button-primary"
         href="<?php echo self::$self_url; ?>&action=add"><?php self::_e( 'Add Category' ); ?></a>
      <table class="grid categorygrid">
		  <?php echo self::grid_columns( $columns, self::$self_url, 'category_name', FALSE, '', $include_id );
		  foreach ( (array) $categories AS $category ) { ?>
        <tr>
			<?php
			if ( $include_id ) {
				?>
              <td><?php esc_attr_e( $category->category_id ); ?></td>
				<?php
			}
			?>
          <td class="name"><a
                href="<?php echo self::$self_url; ?>&action=edit&category_id=<?php esc_attr_e( $category->category_id ); ?>"><?php echo esc_attr( $category->category_name ); ?></a>
          </td>
          <td class="number"><?php esc_attr_e( $category->category_sort_order ); ?></td>
          <td class="action">
            <a href="<?php echo self::$self_url; ?>&action=edit&category_id=<?php esc_attr_e( $category->category_id ); ?>"><?php self::_e( 'Edit' ); ?></a>
            <a class="delete"
               href="<?php echo self::$self_url; ?>&action=delete&category_id=<?php esc_attr_e( $category->category_id ); ?>"><?php self::_e( 'Delete' ); ?></a>
          </td>
			<?php } ?>
      </table>

		<?php
	}

	public static function edit_category( $category_id ) {
		$category_name        = '';
		$category_description = '';
		$category_slug        = '';
		$category_sort_order  = 1;

		if ( isset( $_POST['category_name'] ) ) {
			$category_name        = self::request( 'category_name' );
			$category_description = self::request( 'category_description', '', 'textarea' );
			$category_slug        = self::request( 'category_slug' );
			$category_sort_order  = self::request( 'category_sort_order' );

		} else if ( $category_id ) {
			$category = self::get_category( $category_id );

			$category_name        = $category->category_name;
			$category_description = $category->category_description;
			$category_slug        = $category->category_slug;
			$category_sort_order  = $category->category_sort_order;
		}

		?>
      <form method="post" action="<?php echo self::$self_url; ?>">
        <table class="form-table">
          <tr>
            <th><?php self::_e( 'Category Name' ); ?></th>
            <td><input name="category_name" class="regular-text"
                       value="<?php echo esc_attr( $category_name ); ?>"/></td>
          </tr>
			<?php if ( self::getOption( 'seo_friendly' ) ) { ?>
              <tr>
                <th><?php self::_e( 'Permalink' ); ?></th>
                <td><input name="category_slug" value="<?php esc_attr_e( $category_slug ); ?>"/></td>
              </tr>
			<?php } ?>
          <tr>
            <th><?php self::_e( 'Description' ); ?></th>
            <td><textarea
                  name="category_description"><?php echo esc_textarea( $category_description ); ?></textarea>
            </td>
          </tr>
          <tr>
            <th><?php self::_e( 'Sort Order' ); ?></th>
            <td><input name="category_sort_order" class="small-text"
                       value="<?php esc_attr_e( $category_sort_order ); ?>"/></td>
          </tr>
        </table>
        <input type="hidden" name="action" value="save"/>
        <input type="hidden" name="category_id" value="<?php esc_attr_e( $category_id ); ?>"/>
		  <?php wp_nonce_field( self::NONCE_ACTION, 'nonce' ); ?>
        <p class="submit">
          <a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
          <input type="submit" name="save" class="button button-primary"
                 value="<?php self::_e( 'Save Category' ); ?>"/>
        </p>
      </form>
		<?php
	}

	/**
	 * Gathers the $_POST variables from the category form, preps them, and sends
	 * to the category class to be saved.
	 *
	 * @return bool|int
	 */
	public static function save_category() {
		$nonce                = self::request( 'nonce' );
		$category_name        = self::request( 'category_name' );
		$category_slug        = self::request( 'category_slug' );
		$category_description = self::request( 'category_description', '', 'textarea' );
		$category_sort_order  = self::request( 'category_sort_order' );
		$category_id          = self::request( 'category_id' );


		if ( ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			self::$error = self::__( 'Security failure.  Please try again.' );
		}

		if ( ! $category_name ) {
			self::$error = self::__( 'Category Name' ) . ' ' . self::__( 'is required.' );
		}

		if ( ! self::$error ) {
			$data = [
				'category_name'        => $category_name,
				'category_slug'        => $category_slug,
				'category_description' => $category_description,
				'category_sort_order'  => $category_sort_order,
				'category_id'          => $category_id
			];

			self::analysis_messages();

			return self::$category->save( $data );
		}

	}

	public static function delete_category() {
		$category_id = (int) self::request( "category_id" );
		if ( ! $category_id ) {
			self::$error = self::__( 'Category id not set.  Category not deleted.' );

			return FALSE;
		}

		if ( ! self::$category->delete( $category_id ) ) {
			self::$error = self::$category->get_message();

			return FALSE;
		}

		self::analysis_messages();
		self::$message = self::__( 'Category item deleted successfully.' );

		return TRUE;
	}

	/**
	 * Mini controller method for handling labels
	 */
	public static function wpim_manage_labels() {
		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action      = self::get_action();
		$category_id = self::request( "label_id" );

		if ( $action == 'save' ) {
			if ( self::save_labels() ) {
				$action        = '';
				self::$message = self::__( 'Labels' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		self::admin_heading( self::__( 'Manage Labels' ) );

		echo apply_filters( 'wpim_manage_labels_notices', '' );

		if ( apply_filters( 'wpim_manage_labels_allowed', TRUE ) ) {
			if ( $action == 'edit' || $action == 'add' ) {
				self::list_labels( TRUE );
			}

			if ( $action == 'default' ) {
				self::reset_labels();
				$action = '';
			}

			if ( ! $action ) {
				self::list_labels();
			}
		}

		self::admin_footer();
	}

	public static function list_labels( $edit = FALSE ) {
		$always_on = self::get_labels_always_on();
		$labels    = self::get_labels();

		if ( ! $edit ) { ?>
          <a class="button-primary"
             href="<?php echo self::$self_url; ?>&action=edit"><?php self::_e( 'Edit Labels' ); ?></a>
		<?php } ?>
      <form method="post" action="<?php echo self::$self_url; ?>">
		  <?php if ( $edit ) { ?>
            <input type="hidden" name="action" value="save"/>
            <p class="submit">
              <a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
              <input type="submit" class="button-primary" name="save"
                     value="<?php self::_e( 'Save Labels' ); ?>"/>
              <a class="button"
                 href="<?php echo self::$self_url; ?>&action=default"><?php self::_e( 'Reset to Defaults' ); ?></a>
            </p>
		  <?php } ?>
        <table class="form-table">
			<?php
			foreach ( $labels

			AS $field => $label ) {
			$class   = ( ! $label['is_used'] ) ? ' class="not_used"' : '';
			$default = ( isset( $label['default'] ) ) ? $label['default'] : $label['label']; ?>
          <tr<?php esc_attr_e( $class ); ?>>
            <th><label for="<?php esc_attr_e( $field ); ?>"><?php esc_attr_e( $default ); ?>:</label></th>
			  <?php if ( $edit ) {
				  $in_use_checked          = ( $label['is_used'] ) ? ' checked' : '';
				  $include_in_sort_checked = ( $label['include_in_sort'] ) ? ' checked' : '';
				  $numeric_checked         = ( $label['is_numeric'] ) ? ' checked' : ''; ?>
                <td><input type="text" name="<?php esc_attr_e( $field ); ?>"
                           value="<?php echo esc_attr( $label['label'] ); ?>"/>
                </td>
                <td>
					<?php if ( ! in_array( $field, $always_on ) ) { ?>
                      <input type="checkbox" class="is_used" id="is_used<?php esc_attr_e( $field ); ?>"
                             name="is_used[<?php esc_attr_e( $field ); ?>]"<?php esc_attr_e( $in_use_checked ); ?> />
                      <label for="is_used<?php esc_attr_e( $field ); ?>"><?php self::_e( 'Use Field' ); ?></label>
					<?php } else { ?>
                      <span class="always_on"><?php self::_e( 'Always On' ); ?></span>
					<?php } ?>
                </td>
                <td>
                  <input type="checkbox" class="is_numeric" id="is_used<?php esc_attr_e( $field ); ?>"
                         name="is_numeric[<?php esc_attr_e( $field ); ?>]"<?php esc_attr_e( $numeric_checked ); ?> />
                  <label
                      for="is_numeric<?php esc_attr_e( $field ); ?>"><?php self::_e( 'Sort Numerically' ); ?></label>
                </td>
                <td>
                  <input type="checkbox" class="include_in_sort" id="include_in_sort<?php esc_attr_e( $field ); ?>"
                         name="include_in_sort[<?php esc_attr_e( $field ); ?>]"<?php esc_attr_e( $include_in_sort_checked ); ?> />
                  <label
                      for="include_in_sort<?php esc_attr_e( $field ); ?>"><?php self::_e( 'Include In Sort' ); ?></label>
                </td>
			  <?php } else { ?>
                <td><span><?php echo esc_attr( $label['label'] ); ?></span></td>
			  <?php }
			  } ?>
        </table>
      </form>
		<?php
	}

	public static function save_labels() {
		$labels          = self::get_labels();
		$is_used         = (array) self::request( "is_used" );
		$is_numeric      = (array) self::request( "is_numeric" );
		$include_in_sort = (array) self::request( "include_in_sort" );

		$save_data = [];

		foreach ( $labels AS $field => $data ) {
			if ( isset( $_POST[ $field ] ) ) {
				$save_data[ $field ] = self::request( $field );
			}
			$is_used[ $field ]         = ( isset( $is_used[ $field ] ) ) ? 1 : 0;
			$is_numeric[ $field ]      = ( isset( $is_numeric[ $field ] ) ) ? 1 : 0;
			$include_in_sort[ $field ] = ( isset( $include_in_sort[ $field ] ) ) ? 1 : 0;
		}

		self::analysis_messages();

		return self::$label->save( $save_data, $is_used, $is_numeric, $include_in_sort );
	}

	/**
	 * Mini controller method for handling statuses
	 */
	public static function wpim_manage_statuses() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;


		$action = self::get_action();


		if ( $action == 'save' ) {
			if ( self::save_statuses() ) {
				$action        = '';
				self::$message = self::__( 'Statuses' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		self::admin_heading( self::__( 'Manage Statuses' ) );

		if ( $action == 'edit' || $action == 'add' ) {
			self::list_statuses( TRUE );
		}

		if ( ! $action ) {
			self::list_statuses();
		}

		self::admin_footer();
	}

	public static function list_statuses( $edit = FALSE ) {

		$statuses = self::get_statuses();

		if ( ! $edit ) { ?>
          <a class="button-primary"
             href="<?php echo self::$self_url; ?>&action=edit"><?php self::_e( 'Edit Statuses' ); ?></a>
		<?php } ?>
      <form method="post" action="<?php echo self::$self_url; ?>">
		  <?php if ( $edit ) { ?>
            <input type="hidden" name="action" value="save"/>
            <p class="submit">
              <a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
              <input type="submit" class="button-primary" name="save"
                     value="<?php self::_e( 'Save Statuses' ); ?>"/>
            </p>
		  <?php }
		  $class = ( $edit ) ? ' edit' : ''; ?>
        <table class="form-table wpim_statuses<?php esc_attr_e( $class ); ?>">
          <tr>
			  <?php if ( $edit ) {
				  echo '<th>&nbsp;</th>';
			  } ?>
            <th><?php self::_e( 'Status' ) ?></th>
            <th><?php self::_e( 'Description' ) ?></th>
            <th><?php self::_e( 'Hide Items' ) ?></th>
          </tr>
			<?php foreach ( $statuses AS $status_id => $status ) { ?>
              <tr class="status">
				  <?php
				  if ( $edit ) {
					  $hide_items = ( $status['is_active'] ) ? 0 : 1; ?>
                    <td style="width: 3%;"><span class="sortable-handle dashicons dashicons-sort"></span></td>
                    <td><input type="text" name="status_name[]"
                               value="<?php echo esc_attr( $status['status_name'] ); ?>"/>
                      <input type="hidden" name="status_id[]" value="<?php esc_attr_e( $status['status_id'] ); ?>"/>
                    </td>
                    <td><input type="text" class="large-text" name="status_description[]"
                               value="<?php echo esc_textarea( $status['status_description'] ); ?>"/></td>
                    <td>
                      <label
                          for="is_active<?php esc_attr_e( $status_id ); ?>"><?php self::_e( 'Hide Items' ); ?></label>
						<?php echo self::dropdown_yesno( 'is_active[]', $hide_items ); ?>
                    </td>
				  <?php } else {
					  $status_hidden = ( $status['is_active'] ) ? '' : self::__( ' hide items' ); ?>
                    <td><?php echo esc_attr( $status['status_name'] ); ?></td>
                    <td><?php echo esc_textarea( $status['status_description'] ); ?></td>
                    <td><?php esc_attr_e( $status_hidden ); ?></td>
				  <?php }
				  ?>
              </tr>
			<?php } ?>
        </table>
		  <?php if ( $edit ) { ?>
            <div><a class="button wpim_add_status" href="javascript:void(0;"><?php self::_e( 'Add Status' ); ?></a>
            </div>
		  <?php } ?>
        <script>
          jQuery( function( $ ) {
            $( 'a.wpim_add_status' ).click(
              function() {
                var html = '<tr class="status"><td><span class="sortable-handle dashicons dashicons-sort">';
                html += '<td><input type="text" name="status_name[]"></td>';
                html += '<td><input type="text" class="large-text" name="status_description[]"></td>';
                html += '<td>';
                html += '<label for="is_active">';
                html += "<?php self::_e( 'Hide Items' ); ?>";
                html += '</label>';
                html += '<?php echo str_replace( [
					"\r",
					"\n"
				], "", self::dropdown_yesno( 'is_active[]', 0 ) ); ?>';
                html += '</td></tr>';
                $( 'table.wpim_statuses' ).append( html );
              }
            );

            $( 'table.wpim_statuses.edit' ).sortable( {
              items: 'tr.status',
              placeholder: 'ui-sortable-placeholder'
            } );
          } );
        </script>
      </form>
		<?php
	}

	public static function save_statuses() {
		$status_name        = self::request( "status_name" );
		$status_description = self::request( "status_description", '', 'textarea' );
		$is_hidden          = self::request( "is_active" );
		$status_id          = self::request( "status_id" );

		$save_data = [];

		foreach ( $status_name AS $index => $name ) {
			$is_active   = ( (int) $is_hidden[ $index ] ) ? 0 : 1;
			$save_data[] = [
				'status_name'        => $name,
				'status_description' => $status_description[ $index ],
				'is_active'          => $is_active,
				'status_id'          => ( isset( $status_id[ $index ] ) ) ? $status_id[ $index ] : FALSE,
				'status_sort_order'  => ( $index + 1 )
			];
		}

		self::analysis_messages();

		return self::$status->save( $save_data );
	}

	/**
	 * Mini controller method for handling display settings
	 */
	public static function wpim_manage_display() {
		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		$action = self::get_action();

		if ( $action == 'save' ) {
			if ( self::save_display() ) {
				$action        = '';
				self::$message = self::__( 'Display Settings' ) . ' ' . self::__( 'saved successfully.' );
			} else {
				$action = 'edit';
			}
		}

		self::admin_heading( self::__( 'Manage Display' ) );
		echo apply_filters( 'wpim_manage_display_notices', '' );
		self::edit_display();
		self::admin_footer();
	}

	public static function edit_display() {
		if ( apply_filters( 'wpim_manage_display_disabled', FALSE ) ) {
			return;
		}

		$titles = [
			'listing' => self::__( 'Show in Listing' ),
			'detail'  => self::__( 'Show on Detail' ),
			'admin'   => self::__( 'Show in Admin' ),
			'search'  => self::__( 'Show in Search Results' )
		];

		$display = self::get_display_screens();
		$titles  = apply_filters( 'wpim_admin_display_titles', $titles );

		$display_fields  = [];
		$selected_fields = [];

		foreach ( $display AS $screen ) {
			$display_fields[ $screen ] = (array) self::getDisplay( $screen );
		}

		$table_display_array = [
			0 => self::__( 'No' ),
			1 => self::__( 'Yes - Standard' ),
			2 => self::__( 'Yes - DataTables' )
		];

		$settings = self::getOptions();

		$labels = self::get_labels();

		$available = '';

		foreach ( (array) $display_fields AS $screen => $fields ) {
			$selected_fields[ $screen ] = '';
			if ( ! is_array( $fields ) ) {
				continue;
			}

			foreach ( $fields AS $sort => $key ) {
				$data = ( isset( $labels[ $key ] ) ) ? $labels[ $key ] : NULL;
				if ( $data ) {
					$selected_fields[ $screen ] .= '<li data-field-id="' . $key . '">' . esc_attr( $data['label'] ) . '</li>';
				}
			}
		}

		foreach ( (array) $labels AS $key => $data ) {
			$available .= ( $data['is_used'] ) ? '<li data-field-id="' . $key . '">' . esc_attr( $data['label'] ) . '</li>' : '';
		}

		$sizes = [
			'thumbnail' => self::__( 'Thumbnail' ),
			'medium'    => self::__( 'Medium' ),
			'large'     => self::__( 'Large' ),
			'full'      => self::__( 'Full' )
		];

		?>
      <form method="post" action="<?php echo self::$self_url; ?>">
        <div class="submit">
          <a href="<?php echo self::$self_url; ?>" class="button"><?php _e( 'Cancel' ); ?></a>
          <input type="submit" name="save" value="<?php self::_e( 'Save Settings' ); ?>" class="button-primary"/>
        </div>
        <p><?php echo self::_e( 'Drag and drop fields from the left hand "available" column into the display views on the right.' ); ?></p>
        <div class="lists draggable display_setting_fields">
          <div class="wpim_available_display_fields">
            <div class="list list_available"><h3><?php self::_e( 'Available Fields' ); ?></h3>
              <ul id="available" class="sortable">
				  <?php echo wp_kses( $available, 'post' ); ?>
                <li style="display: none !important; data-field-id="
                ">Shiv for jQuery to insert before</li>
              </ul>
            </div>
          </div>
          <div class="wpim_field_views">
			  <?php foreach ( $selected_fields AS $screen => $fields ) { ?>
                <div class="list wpim_display_list list_selected" data-input="selected_<?php esc_attr_e( $screen ); ?>">
                  <h3><?php esc_attr_e( $titles[ $screen ] ); ?></h3>
                  <ul id="selected_listing" class="sortable">
					  <?php echo wp_kses( $selected_fields[ $screen ], 'post' ); ?>
                  </ul>
                  <input name="selected_<?php esc_attr_e( $screen ); ?>" type="hidden" value=""/>
                  <a href="javascript:void(0)" class="add_all"><?php self::_e( 'Add All Fields' ); ?></a>
                </div>
			  <?php } ?>
          </div>
			<?php do_action( 'wpim_admin_display_lists' ); ?>
        </div>
        <table>
          <tr>
            <td colspan="2">
              <h2><?php do_action( 'wpim_above_admin_display_settings' ); ?></h2>
            </td>
          </tr>
          <tr>
            <th><?php self::_e( 'Show Labels in Listing' ); ?></th>
            <td><?php echo self::dropdown_yesno( "display_listing_labels", $settings['display_listing_labels'] ); ?></td>
          </tr>
          <tr>
            <th valign="top"><?php self::_e( 'Display Listing as Table' ); ?></th>
            <td><?php echo self::dropdown_array( "display_listing_table", $settings['display_listing_table'], $table_display_array ); ?>
              <p class="description"><?php echo sprintf( self::__( 'No: Each item will be in a separate box / panel.  Yes - Standard: Table (spreadsheet) view.  Yes - DataTables: Table (spreadsheet) view, using the %sDataTables Library%s to provide sorting / filtering.' ), '<a target="_blank" href="https://datatables.net/">', '</a>' ); ?>
              <p class="wpim_notice wpim_warning"
                 style="display: none;"><?php echo sprintf( self::__( 'IMPORTANT: You are choosing to use the jQuery DataTables library to display items on the front end.  Please be sure to %sRead the DataTables Docs%s to understand what you are doing. Some WP Inventory %sshortcode arguments%s (such as order, page, page size) will NOT work with this setting.  Further, if you have over 1000 inventory items, DataTables may cause the listing to be slow.' ), '<a target="_blank" href="https://datatables.net/">', '</a>', '<a target="_blank" href="https://www.wpinventory.com/documentation/user/displaying-inventory/shortcode-options/">', '</a>' ); ?></p>
            </td>
          </tr>
          <tr>
            <th><?php self::_e( 'Image size in Listing' ); ?></th>
            <td><?php echo self::dropdown_array( "display_listing_image_size", $settings['display_listing_image_size'], $sizes ); ?></td>
          </tr>
			<?php
			$detail_page_settings = '<tr>
                    <th>' . self::__( 'Show Labels on Detail' ) . '</th>
                    <td>' . self::dropdown_yesno( "display_detail_labels", $settings['display_detail_labels'] ) . '</td>
                </tr>
                <tr>
                    <th>' . self::__( 'Image size on Detail' ) . '</th>
                    <td>' . self::dropdown_array( "display_detail_image_size", $settings['display_detail_image_size'], $sizes ) . '</td>
                </tr>';

			echo apply_filters( 'wpim_admin_detail_page_display_settings', $detail_page_settings );
			?>

        </table>
		  <?php do_action( 'wpim_manage_display_form_end', $labels ); ?>
        <div class="submit">
          <input type="hidden" name="action" value="save"/>
          <a href="<?php echo self::$self_url; ?>" class="button"><?php _e( 'Cancel' ); ?></a>
          <input type="submit" name="save" value="<?php self::_e( 'Save Settings' ); ?>" class="button-primary"/>
        </div>
      </form>
      <script>
        jQuery( function( $ ) {
          $( 'select[name="display_listing_table"]' ).on( 'change', function() {
            var $alert = $( this ).closest( 'tr' ).find( '.wpim_notice' );
            if ( 2 === +$( this ).val() ) {
              $alert.slideDown();
            } else {
              $alert.slideUp();
            }
          } ).trigger( 'change' );

          var pos;
          $( '.sortable' ).sortable( {
            connectWith: '.sortable',
            placeholder: 'ui-state-highlight',
            helper: 'clone',
            start: function( event, ui ) {
              ui.placeholder.html( $( ui.item ).html() );
              pos = $( ui.item ).index();
            },
            receive: function( event, ui ) {
              var sender = ui.sender.attr( 'id' );
              if ( sender == 'available' ) {
                $( ui.item ).clone().insertBefore( '#available li:eq(' + pos + ')' );
              } else if ( $( ui.item ).closest( 'ul' ).attr( 'id' ) == 'available' ) {
                ui.item.remove();
              }

              var receiver = $( this ).closest( 'div.wpim_display_list' ).data( 'input' );
              var ignore;
              if ( receiver && receiver.length ) {
                ignore = wpimIgnore[ receiver.replace( 'selected_', '' ) ];
              }

              if ( ignore ) {
                var field = ui.item.attr( 'data-field-id' );
                if ( ignore[ field ] ) {
                  var labels = [];
                  for ( var key in ignore ) {
                    labels.push( ignore[ key ] );
                  }
                  labels            = labels.join( ', ' );
                  var receiverLabel = receiver.replace( 'selected_', '' );
                  receiverLabel     = (wpimLabels[ receiverLabel ]) ? wpimLabels[ receiverLabel ] : receiverLabel;
                  var message       = '<?php echo self::__( 'You may not display the following fields in the %s listing: %s' ); ?>';
                  message           = message.replace( '%s', receiverLabel );
                  message           = message.replace( '%s', labels );
                  ui.item.remove();
                  alert( message );
                }
              }
            },
            update: function() {
              updateDisplay();
            }

          } );

          $( '.wpim_field_views h3' ).on( 'click', function() {
            $( this ).closest( '.list' ).toggleClass( 'expanded' );
          } );

          $( '.wpim_field_views h3' ).first().closest( '.list' ).addClass( 'expanded' );

          $( '.sortable li' ).on( 'click', '.remove', function( e ) {
            e.stopPropagation();
            $( this ).closest( 'li' ).remove();
            updateDisplay();
          } );

          $( '.add_all' ).on( 'click',
            function() {
              var _this = $( this ).siblings( 'ul' );
              $( '#available li' ).each(
                function() {
                  if ( _this.find( 'li[data-field-id="' + $( this ).attr( 'data-field-id' ) + '"]' ).length <= 0 ) {
                    $( this ).clone().appendTo( _this );
                  }
                }
              );
              updateDisplay();
            }
          );

          updateDisplay();

          function updateDisplay() {
            $( '.wpim_display_list [data-field-id] span.remove' ).remove();
            $( '.wpim_display_list' ).each(
              function() {
                var val     = '';
                var inputID = $( this ).data( 'input' );
                jQuery( '[data-field-id]', this ).each( function() {
                  val += jQuery( this ).attr( 'data-field-id' ) + ',';
                } );

                jQuery( 'input[name="' + inputID + '"]' ).val( val );
              }
            );

            $( '.wpim_display_list [data-field-id]' ).append( '<span class="dashicons dashicons-no remove"></span>' );
          }
        } );
        var wpimIgnore = {
          'admin': <?php echo json_encode( self::admin_ignore_columns( TRUE ) ); ?>
        };

        var wpimLabels = {
          'admin': "<?php self::_e( 'Admin' ); ?>"
        };
      </script>
	<?php }

	private static function get_display_screens() {
		$display = [
			'listing',
			'detail',
			'admin',
			'search'
		];

		return apply_filters( 'wpim_admin_display_screens', $display );
	}

	public static function save_display() {
		$screens = self::get_display_screens();

		foreach ( $screens AS $screen ) {
			$display = trim( self::request( "selected_{$screen}" ), ',' );
			$display = str_ireplace( ',undefined', '', $display );
			$key     = self::getDisplayKey( $screen );
			self::updateOption( $key, $display );
		}

		$fields = [
			'display_listing_labels',
			'display_listing_table',
			'display_detail_labels',
			'display_listing_image_size',
			'display_detail_image_size'
		];

		foreach ( $fields AS $field ) {
			self::updateOption( $field, self::request( $field ) );
		}

		self::analysis_messages();

		return TRUE;
	}

	public static function wpim_manage_settings() {
		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		if ( self::request( 'message' ) == 'save' ) {
			self::$message = self::__( 'Settings' ) . ' ' . self::__( 'saved successfully, and rewrite rules flushed.' );
		}

		self::admin_heading( self::__( 'Manage Settings' ) );
		self::edit_settings();
		self::admin_footer();
	}

	/**
	 * Admin Settings View.
	 * Renders the HTML for the "Settings" interface.
	 */
	public static function edit_settings() {
		require_once ABSPATH . "wp-includes/class-wp-editor.php";
		_WP_Editors::wp_link_dialog();

		$settings = self::getOptions();

		$themes = self::load_available_themes();
		$themes = array_keys( $themes );
		$themes = array_combine( $themes, $themes );
		$themes = array_merge( [ '' => self::__( ' - None / No CSS -' ) ], $themes );

		/**
		 * Currency formatting.  Names are pretty clear.
		 * 'currency_symbol'                => '$',
		 * 'currency_thusands_separator'    => ',',
		 * 'currency_decimal_separator'    => '.',
		 * 'currency_decimal_precision'    => '2',
		 * // Date format.  Uses PHP formats: http://php.net/manual/en/function.date.php
		 * 'date_format'                    => 'm/d/Y',
		 */

		$permission_array = [
			'manage_options'    => self::__( 'Administrator' ),
			'edit_others_posts' => self::__( 'Editor' ),
			'publish_posts'     => self::__( 'Author' ),
			'edit_posts'        => self::__( 'Contributor' ),
			'read'              => self::__( 'Subscriber' )
		];

		$permission_user_array = [
			1 => self::__( "Any items" ),
			2 => self::__( "Only their own items" )
		];

		$time_format_array = [
			''      => self::__( 'Do not display' ),
			'g:i'   => '3:45',
			'h:i'   => '03:45',
			'g:i a' => '3:45 pm',
			'h:i a' => '03:45 pm',
			'g:i A' => '3:45 PM',
			'h:i A' => '03:45 PM',
			'H:i'   => '15:45',
			'H:i a' => '15:45 pm',
			'H:i A' => '15:45 PM',
		];

		$currency_symbol_location_array = [
			'0' => self::__( 'Before' ),
			'1' => self::__( 'After' )
		];

		$currency_decimal_precision_array = [
			0 => 0,
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4
		];

		$include_in_search_array = [
			''       => self::__( 'No' ),
			'before' => self::__( 'Yes - at top of search results' ),
			'after'  => self::__( 'Yes - at bottom of search results' ),
		];

		$open_image_array = [
			''     => self::__( 'No' ),
			'same' => self::__( 'Yes - Open in Same Window' ),
			'new'  => self::__( 'Yes - Open in New Window' ),
		];

		$open_image_array = apply_filters( 'wpim_open_image_options', $open_image_array );

		$permalinks    = get_option( 'permalink_structure' );
		$permalink_tip = '';
		if ( ! $permalinks ) {
			$settings['seo_urls'] = 0;
			$permalink_tip        = '<p class="description">' . self::__( 'SEO URLs will not work with your current ' ) . '<a href="options-permalink.php">' . self::__( 'Permalink Structure' ) . '</a></p>';
		}
		?>
      <form method="post" action="<?php echo self::$self_url; ?>" class="inventory-config">
        <div class="wpim_tabs"></div>
        <div class="wpim_main_content">
          <h3 data-tab="general_settings"><?php self::_e( 'General Settings' ); ?></h3>
          <table class="form-table">
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="permissions_settings"><?php self::_e( 'Permissions Settings' ); ?></h4></th>
            </tr>
            <tr>
              <th><?php self::_e( 'Minimum Role to Add/Edit Items' ); ?></th>
              <td><?php echo self::dropdown_array( "permissions_lowest_role", $settings['permissions_lowest_role'], $permission_array ); ?></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Allow Users to Edit' ); ?></th>
              <td><?php echo self::dropdown_array( "permissions_user_restricted", $settings['permissions_user_restricted'], $permission_user_array ); ?></td>
            </tr>
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="item_display"><?php self::_e( 'Item Display' ); ?></h4></th>
            </tr>
			  <?php
			  $seo_settings = '
                <tr>
                    <th>' . self::__( 'Use SEO URLs' ) . '</th>
                    <td>' . self::dropdown_yesno( "seo_urls", $settings['seo_urls'] ) . $permalink_tip . '</td>
                </tr>
                <tr class="seo_urls">
                    <th>' . self::__( 'SEO Endpoint' ) . '</th>
                    <td><input type="text" class="medium-text" name="seo_endpoint"
                               value="' . esc_attr( $settings['seo_endpoint'] ) . '"/></td>
                </tr>';

			  echo apply_filters( 'wpim_admin_seo_settings', $seo_settings );
			  ?>
            <tr class="seo_urls">
              <th><?php self::_e( 'Shortcode on Home?' ); ?></th>
              <td><?php
				  echo self::dropdown_yesno( "shortcode_on_home", $settings['shortcode_on_home'] );
				  echo '<p class="description"> ' . self::__( 'Select YES if you are using inventory on the home page. This disables canonical redirects for the home page.' ) . '</p>';
				  ?></td>
            </tr>
            <tr class="theme_row">
              <th><?php self::_e( 'Theme' ); ?></th>
              <td colspan="2"><?php
				  echo self::dropdown_array( "theme", $settings['theme'], $themes, 'wpinventory_themes' );
				  echo '<div class="theme_screenshot"></div>';
				  ?></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Items Per Page' ); ?></th>
              <td><input type="text" class="small-text" name="page_size"
                         value="<?php echo esc_attr( $settings['page_size'] ); ?>"></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Hide Items Low Quantity' ); ?></th>
              <td><?php echo self::dropdown_yesno( "hide_low", $settings['hide_low'] ); ?>
                <p class="description"><?php self::_e( 'Set the low quantity threshold below' ); ?>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Hide Items Below Quantity' ); ?></th>
              <td><input type="text" class="small-text" name="hide_low_quantity"
                         value="<?php echo esc_attr( $settings['hide_low_quantity'] ); ?>">

                <p class="description"><?php self::_e( 'Only honored if "Hide Items Low Quantity" set to yes' ); ?></p>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Display "Out of Stock" message' ); ?></th>
              <td><?php echo self::dropdown_yesno( "out_of_stock_message", $settings['out_of_stock_message'] ); ?>
                <p class="description"><?php self::_e( 'This will display a banner on the front end that the item is out of stock and it will also hide the reserve form' ); ?>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Quantity item must reach to display out of stock' ); ?></th>
              <td><input type="number" name="out_of_stock_quantity"
                         value="<?php echo esc_attr( $settings['out_of_stock_quantity'] ); ?>">

                <p class="description"><?php self::_e( 'Only honored if "Display "Out of Stock" message" set to yes AND "Hide Items Low Quantity" set to no' ); ?></p>
                <p class="description"><?php self::_e( 'Also note that the "Out of Stock" notice will only display on the <strong>listing page</strong> if you <u>do not</u> use tables' ); ?></p>
              </td>
            </tr>
			  <?php do_action( 'wpim_edit_settings_general', $settings ); ?>
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="date_format_settings"><?php self::_e( 'Date Format Settings' ); ?></h4></th>
            </tr>
            <tr>
              <th><?php self::_e( 'Date Format' ); ?></th>
              <td>
                <p>
					<?php
					$date_options = [
						0 => self::__( 'US' ),
						1 => self::__( 'UK' ),
						2 => self::__( 'International' )
					];

					$date_type = ( wpinventory_get_config( 'date_type' ) ) ? wpinventory_get_config( 'date_type' ) : 0;
					echo self::dropdown_array( 'date_type', $date_type, $date_options );
					?>
                </p>
                <p><br>
					<?php
					echo self::dropdown_date_format( "date_format", $settings['date_format'] ); ?></p></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Time Format' ); ?></th>
              <td><?php echo self::dropdown_array( "time_format", $settings['time_format'], $time_format_array ); ?></td>
            </tr>
			  <?php do_action( 'wpim_edit_settings_date', $settings ); ?>
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="currency_format_settings"><?php self::_e( 'Currency Format Settings' ); ?></h4></th>
            </tr>
            <tr>
              <th><?php self::_e( 'Currency Symbol' ); ?></th>
              <td><input type="text" name="currency_symbol" class="small-text"
                         value="<?php echo esc_attr( $settings['currency_symbol'] ); ?>"/></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Currency Symbol Location' ); ?></th>
              <td><?php echo self::dropdown_array( 'currency_symbol_location', $settings['currency_symbol_location'], $currency_symbol_location_array ); ?></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Currency Thousands Separator' ); ?></th>
              <td><input type="text" name="currency_thousands_separator" class="small-text"
                         value="<?php echo esc_attr( $settings['currency_thousands_separator'] ); ?>"/></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Currency Decimal Separator' ); ?></th>
              <td><input type="text" name="currency_decimal_separator" class="small-text"
                         value="<?php echo esc_attr( $settings['currency_decimal_separator'] ); ?>"/></td>
            </tr>
            <tr>
              <th><?php self::_e( 'Currency Precision (decimal places)' ); ?></th>
              <td><?php echo self::dropdown_array( 'currency_decimal_precision', $settings['currency_decimal_precision'], $currency_decimal_precision_array ); ?></td>
            </tr>
            <tr>
              <td><?php self::_e( 'Currency Example (with settings):' ); ?></td>
              <td><?php echo esc_attr( self::format_currency( 45250.25555 ) ) ?>
            </tr>
			  <?php do_action( 'wpim_edit_settings_currency', $settings ); ?>
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="search_settings"><?php self::_e( 'Search Settings' ); ?></h4>
              </th>
            </tr>
            <tr>
              <th><?php self::_e( 'Include Inventory in Search' ); ?></th>
              <td><?php echo self::dropdown_array( 'include_in_search', $settings['include_in_search'], $include_in_search_array ); ?>
                <p class="description"><?php self::_e( 'WARNING: May not work with your theme.  Be sure to test.' ); ?></p>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Search Results Links to' ); ?></th>
              <td>
				  <?php
				  $pages = wpinventory_find_shortcode();
				  if ( ! $pages ) {
					  self::_e( 'WARNING: [wpinventory] shortcode not found on any pages!' );
				  } else {
					  $pages_array = [];
					  foreach ( $pages AS $page ) {
						  $pages_array[ $page->ID ] = $page->post_title;
					  }
					  echo self::dropdown_array( 'search_page_id', $settings['search_page_id'], $pages_array );
					  echo '<p class="description">' . self::__( 'This is a list of all posts and pages with the [wpinventory] shortcode on it.  This is where you will go when you click to view the inventory item from the search results listing.' ) . '</p>';
				  } ?>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Display Filter at Top of Item Listing' ); ?></th>
              <td><?php echo self::dropdown_yesno( "display_inventory_filter", $settings['display_inventory_filter'] ); ?>
              </td>
            </tr>
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="dashboard_admin"><?php self::_e( 'Dashboard Admin' ); ?></h4>
              </th>
            </tr>
            <tr>
              <th><?php self::_e( 'Hide header in the admin' ); ?></th>
              <td><?php echo self::dropdown_yesno( 'hide_admin_header', $settings['hide_admin_header'] ); ?>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Show The Item ID in The Admin Listing' ); ?></th>
              <td><?php echo self::dropdown_yesno( 'show_item_id_in_admin_listing', $settings['show_item_id_in_admin_listing'] ); ?>
              </td>
            </tr>
          </table>
          <h3 data-tab="reserve_settings"><?php self::_e( 'Reserve Settings' ); ?></h3></th>
          <table class="form-table">
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="general"><?php self::_e( 'General' ); ?></h4></th>
            </tr>
            <tr>
              <th><?php self::_e( 'Allow Visitors to Reserve Items' ); ?></th>
              <td><?php echo self::dropdown_yesno( "reserve_allow", $settings['reserve_allow'] ); ?></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Ask for Qty When Reserving' ); ?></th>
              <td><?php echo self::dropdown_yesno( "reserve_quantity", $settings['reserve_quantity'] ); ?></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Decrease Qty in System on Reserve' ); ?></th>
              <td><?php echo self::dropdown_yesno( "reserve_decrement", $settings['reserve_decrement'] ); ?></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Send-to Email When Reserve' ); ?></th>
              <td><input type="text" class="widefat" name="reserve_email"
                         value="<?php echo esc_attr( $settings['reserve_email'] ); ?>">

                <p class="description"><?php self::_e( 'If left blank, the E-Mail Address from Settings -> General will be used.' ); ?></p>
              </td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Send Reserve Confirmation' ); ?></th>
              <td><?php echo self::dropdown_yesno( "reserve_confirmation", $settings['reserve_confirmation'] ); ?>
                <p class="description"><?php self::_e( 'Should a confirmation e-mail be sent to the submitter when a reserve form
                            is submitted?' ); ?></p></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Confirmation Email Message' ); ?></th>
              <td><textarea name="reserve_email_message"><?php echo esc_textarea( $settings['reserve_email_message'] ); ?></textarea>
                <p class="description"><?php self::_e( 'Note to include to customer in confirmation email.' ); ?></p></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Confirmation Email Message Placement' ); ?></th>
              <td><?php echo self::dropdown_array( "reserve_email_message_position", $settings['reserve_email_message_position'], [ 0 => self::__( 'Top' ), 1 => self::__( 'Bottom' ) ] ); ?></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Reserve Message' ); ?></th>
              <td><textarea
                    name="reserve_message"><?php echo esc_textarea( $settings['reserve_message'] ); ?></textarea>
                <p class="description"><?php self::_e( 'Message displayed on the page when a visitor completes a reservation.' ); ?></p></td>
            </tr>
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="form_settings"><?php self::_e( 'Form Settings' ); ?></h4></th>
            </tr>
            <tr class="reserve reserve_form_title">
              <th><?php self::_e( 'Form Title' ) ?></th>
              <td><input type="text" name="reserve_form_title" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_form_title'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require Name' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_name", $settings['reserve_require_name'] ); ?></td>
            </tr>
            <tr class="reserve reserve_name">
              <th><?php self::_e( 'Name Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_name" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_name'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require Address' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_address", $settings['reserve_require_address'] ); ?></td>
            </tr>
            <tr class="reserve reserve_address">
              <th><?php self::_e( 'Address Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_address" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_address'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require City' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_city", $settings['reserve_require_city'] ); ?></td>
            </tr>
            <tr class="reserve reserve_city">
              <th><?php self::_e( 'City Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_city" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_city'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require State' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_state", $settings['reserve_require_state'] ); ?></td>
            </tr>
            <tr class="reserve reserve_state">
              <th><?php self::_e( 'State Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_state" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_state'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require Zip' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_zip", $settings['reserve_require_zip'] ); ?></td>
            </tr>
            <tr class="reserve reserve_zip">
              <th><?php self::_e( 'Zip Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_zip" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_zip'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require Phone' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_phone", $settings['reserve_require_phone'] ); ?></td>
            </tr>
            <tr class="reserve reserve_phone">
              <th><?php self::_e( 'Phone Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_phone" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_phone'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require Email' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_email", $settings['reserve_require_email'] ); ?></td>
            </tr>
            <tr class="reserve reserve_email">
              <th><?php self::_e( 'Email Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_email" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_email'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Require Message' ); ?></th>
              <td><?php echo self::dropdown_required( "reserve_require_message", $settings['reserve_require_message'] ); ?></td>
            </tr>
            <tr class="reserve reserve_message">
              <th><?php self::_e( 'Message Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_message" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_message'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Quantity Input Label' ) ?></th>
              <td><input type="text" name="reserve_label_quantity" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_quantity'] ); ?>"></td>
            </tr>
            <tr class="reserve">
              <th><?php self::_e( 'Reserve Button text' ) ?></th>
              <td><input type="text" name="reserve_label_button" class="medium-text"
                         value="<?php echo esc_attr( $settings['reserve_label_button'] ); ?>"></td>
            </tr>
			  <?php do_action( 'wpim_edit_settings_reserve', $settings ); ?>
          </table>
          <h3 data-tab="image_media"><?php self::_e( 'Image / Media' ); ?></h3>
          <table class="form-table">
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="image_settings"><?php self::_e( 'Image Settings' ); ?></h4></th>
            </tr>
            <tr>
              <th><?php self::_e( 'Placeholder Image' ); ?></th>
              <td>
                <div data-type="image" class="media-container">
					<?php
					$placeholder = wpinventory_get_placeholder_image( 'all' );
					self::item_image_field( 0, $placeholder, 'placeholder_image' ); ?>
                </div>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Clicking Image Displays Larger Image' ); ?></th>
              <td> <?php echo self::dropdown_array( 'open_images_new_window', $settings['open_images_new_window'], $open_image_array ); ?>
                <p class="description"><?php self::_e( 'This setting only applies to Detailed view.  In Listing View, clicking image links to product details.' ); ?></p>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Open Media in New Window' ); ?></th>
              <td> <?php echo self::dropdown_yesno( 'open_media_new_window', $settings['open_media_new_window'] ); ?> </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Rebuild Image Thumbnails' ); ?></th>
              <td><input type="checkbox" name="rebuild_thumbnails"/></td>
            </tr>
			  <?php do_action( 'wpim_edit_settings_media', $settings ); ?>
          </table>
          <h3 data-tab="notifications"><?php self::_e( 'Notifications' ); ?></h3>
          <table class="form-table">
            <tr class="subtab">
              <th colspan="2"><h4 data-tab="notifications"><?php self::_e( 'Notifications' ); ?></h4></th>
            </tr>
            <tr>
              <th><?php self::_e( 'Send Low Quantity Email' ); ?></th>
              <td>
				  <?php echo self::dropdown_yesno( "low_quantity_email_check", $settings['low_quantity_email_check'] ); ?>
              </td>
            </tr>
            <tr>
              <th><?php self::_e( 'Display Indicator and Message' ); ?></th>
              <td><?php echo self::dropdown_yesno( "low_quantity_alert", $settings['low_quantity_alert'] ); ?><p class="description"><?php echo self::_e( 'Highlights the inventory item in the admin dashboard with a red background. Also adds a message to the top of the edit item page.' ); ?></p></td>
            </tr>
            <tr class="email_notifications">
              <th><?php self::_e( 'Email Address' ) ?></th>
              <td><input type="email" name="low_quantity_email" class="medium-text"
                         value="<?php echo esc_attr( $settings['low_quantity_email'] ); ?>">
                <p class="description"><?php
					$admin_email = get_option( 'admin_email' );
					self::_e( 'Set the email you want to receive the low quantity alerts. If none is set, we will use ' . $admin_email ); ?></p>

              </td>
            </tr>
            <tr class="email_notifications">
              <th><?php self::_e( 'Low Quantity' ) ?></th>
              <td><input type="number" name="low_quantity_amount"
                         value="<?php echo esc_attr( $settings['low_quantity_amount'] ); ?>">
                <p class="description"><?php self::_e( 'This is the quantity that triggers a notification. Once an item hits this quantity you will receive an email if you selected to receive them above.' ); ?></p>
              </td>
            </tr>
			  <?php do_action( 'wpim_edit_settings_notifications', $settings ); ?>
          </table>
			<?php self::render_settings_tabs(); ?>
			<?php do_action( 'wpim_edit_settings', $settings ); ?>
          <input type="hidden" name="action" value="save"/>
			<?php wp_nonce_field( 'wpim_save_settings_action', 'wpim_save_settings_nonce' ); ?>
          <p class="submit">
            <a href="<?php echo self::$self_url; ?>" class="button"><?php self::_e( 'Cancel' ); ?></a>
            <input type="submit" class="button-primary" name="save" value="<?php self::_e( 'Save Settings' ); ?>"/>
            <!-- <a class="button"
                   href="<?php echo self::$self_url; ?>&action=default"><?php self::_e( 'Reset to Defaults' ); ?></a> -->
          </p>
        </div>
      </form>
      <script>
        jQuery( function( $ ) {
          // TODO: Refactor these two to utilize the more generic system using data-show-toggle and data-show-if
          $( '.inventory-config' ).on( 'change', 'select[name="seo_urls"]', function() {
            if ( $( this ).val() == "1" ) {
              $( 'tr.seo_urls' ).fadeIn();
            } else {
              $( 'tr.seo_urls' ).fadeOut();
            }
          } );

          // Note:  This is marked for deletion or an enhancement.  The issue is when this fires, the other <tr> are still present for the sub-tabs "Form Settings" and "Anti-Spam"
          // which leads to the "Form Settings" <tr> going under the "General" tab.  Not only this, but if there are no reservation, then the subtabs are irrelevant as well.

          //$( '.inventory-config' ).on( 'change', 'select[name="reserve_allow"]', function () {
          //  if ( $( this ).val() == "1" ) {
          //    $( 'tr.reserve' ).fadeIn();
          //  } else {
          //    $( 'tr.reserve' ).fadeOut();
          //  }
          //} );

          $( '.action-license-key-tab' ).on( 'click', function() {
            // attempt to set proper tab on click
            sessionStorage.setItem( 'wpim_tab', 'licensekeys' );
            sessionStorage.setItem( 'wpim_subtab', '' );
          } );

          $( '.inventory-config' ).on( 'change', 'select[data-show-toggle]', function() {
            var sel     = $( this ).data( 'show-toggle' );
            var show_if = $( this ).has( '[data-show-if]' ) ? $( this ).data( 'show-if' ) : 1;
            var val     = +$( this ).val();
            var $el     = $( sel );

            if ( !$el.length ) {
              return;
            }

            if ( val === show_if ) {
              $el.fadeIn();
            } else {
              $el.fadeOut();
            }
          } );

          // cannot chain this with method above.  boo.
          $( 'select[data-show-toggle]' ).trigger( 'change' );

          $( 'select[name="seo_urls"], select[name="reserve_allow"]' ).trigger( 'change' );

          var $tabs = $( 'div.wpim_tabs' );

          var curtab    = sessionStorage.getItem( 'wpim_tab' );
          var cursubtab = sessionStorage.getItem( 'wpim_subtab' );

          $( 'form h3' ).each( function() {
            var title  = $( this ).text();
            var anchor = $( this ).data( 'tab' );
            if ( !anchor ) {
              console.error( 'WARNING: H3 Tab ' + $( this ).text() + ' does NOT have a valid "data-tab" attribute.' );
              anchor = title.replace( ' Settings', '' );
              anchor = anchor.replace( /\W+/g, '' ).toLowerCase();
            }
            title = title.replace( ' Settings', '' );
            $( this ).attr( 'id', anchor );
            $tabs.append( '<a href="#' + anchor + '">' + title + '</a>' );
            $( this ).after( '<div class="wpim_subtabs"></div>' );
          } );

          $( 'form h4' ).each( function() {
            var title = $( this ).data( 'tab' );
            if ( !title ) {
              console.error( 'WARNING: H4 Tab ' + $( this ).text() + ' does NOT have a valid "data-tab" attribute.' );
              title = $( this ).text();
              title = title.replace( ' Settings', '' );
              title = title.replace( /\W+/g, '' ).toLowerCase();
            }
            $( this ).attr( 'id', title );
            $( this ).closest( 'table' ).prevAll( '.wpim_subtabs' ).first().append( '<a href="#' + title + '">' + $( this ).text() + '</a>' );
            var row = $( this ).closest( 'tr' );
            row.nextUntil( '.subtab' ).addBack().addClass( title );
          } );

          // for tabs with only one subtab, hide it to make it less "noisy" on the screen
          $( '.wpim_subtabs' ).each( function() {
            if ( $( this ).find( 'a' ).length === 1 ) {
              $( this ).hide();
              $( this ).next( 'table' ).find( '.subtab' ).remove();
            }
          } );

          $tabs.on( 'click', 'a', function( event ) {
            event.preventDefault();
            var section = $( this ).attr( 'href' ).replace( '#', '' );
            $( 'a', $tabs ).removeClass( 'active' );
            $( this ).addClass( 'active' );
            $( 'form table, form h3' ).removeClass( 'active' );
            $( 'h3#' + section ).addClass( 'active' ).nextAll( 'table' ).first().addClass( 'active' );
            $( 'form table, form h3' ).hide();
            $( 'table.active, form h3.active' ).fadeIn();

            $( '.wpim_subtabs' ).removeClass( 'active' );
            var $subtabs = $( 'h3#' + section ).nextAll( '.wpim_subtabs' ).first();

            if ( $subtabs.length ) {
              $subtabs.addClass( 'active' );
              $subtabs.find( 'a' ).first().trigger( 'click' );
            }

            sessionStorage.setItem( 'wpim_tab', section );
            sessionStorage.setItem( 'wpim_subtab', '' );
          } );

          $( '.wpim_subtabs' ).on( 'click', 'a', function( event ) {
            event.preventDefault();
            switchSubTab( $( this ) );
          } );

          $( '.wpim_subtab_link' ).on( 'click', function( event ) {
            event.preventDefault();
            switchSubTab( $( this ) );
          } );

          function switchSubTab( $el ) {
            var section = $el.attr( 'href' ).replace( '#', '' );
            $( '.wpim_subtabs a' ).removeClass( 'active' );
            $( '.wpim_subtabs a[href="' + $el.attr( 'href' ) + '"]' ).addClass( 'active' );
            $( 'table.active' ).find( 'tr' ).hide();
            $( 'table.active' ).find( 'tr.' + section ).show();

            sessionStorage.setItem( 'wpim_subtab', section );
          }

          $( 'select[name^="reserve_require"]' ).on( 'change', function() {
            var name = $( this ).attr( 'name' );
            name     = name.replace( 'reserve_require_', 'tr.reserve.reserve_' );
            name     = $( name );
            var show = ($( this ).val() != '0') ? true : false;
            if ( show ) {
              name.fadeIn();
            } else {
              name.fadeOut();
            }
          } ).trigger( 'change' );

          if ( curtab ) {
            $( 'a[href="#' + curtab + '"]', $tabs ).trigger( 'click' );
          } else {
            $( '.wpim_tabs a:first' ).trigger( 'click' );
          }

          if ( cursubtab ) {
            $( '.wpim_subtabs a[href="#' + cursubtab + '"]' ).trigger( 'click' );
          } else {
            var hash = window.location.hash;
            if ( hash ) {
              var $el  = $( '.wpim_subtabs a[href="' + hash + '"]' );
              var $tab = $el.closest( '.wpim_subtabs' ).prevAll( 'h3' ).first().attr( 'id' );
              switchSubTab( $( '.wpim_tabs a[href="#' + $tab + '"]' ) );
              $el.trigger( 'click' );
            }
          }

          $( '.wpim-link-button' ).on( 'click', function() {
            var id = $( this ).data( 'input-id' );
            $( '#' + id ).data( 'old-link', $( this ).val() );
            $( '#' + id ).text( '' );
            wpLink.open( id );
          } );

          $( document ).on( 'wplink-close', function( event ) {
            var $link = $( event.currentTarget.activeElement );
            var link  = $link.val();

            if ( !link ) {
              link = $link.data( 'old-link' );
            }

            var match = /<a\s+(?:[^>]*?\s+)?href="([^"]*)"/g.exec( link );

            if ( match && match[ 1 ] ) {
              link = match[ 1 ];
            }

            $link.val( link );
          } );

			<?php do_action( 'wpim_edit_settings_script' ); ?>
        } );
      </script>
		<?php do_action( 'wpim_edit_settings_after_script' );
	}

	private static function save_settings() {
		if ( ! isset( $_POST['wpim_save_settings_nonce'] ) || ! wp_verify_nonce( $_POST['wpim_save_settings_nonce'], 'wpim_save_settings_action' ) ) {
			print 'Security issue in your form submission';
			die();
		}

		$settings = self::$config->defaults();

		$placeholder_image = self::request( 'placeholder_image' );
		if ( $placeholder_image ) {
			$item = new WPIMItem();
			// Images can be id's as well...
			if ( ! is_numeric( $placeholder_image ) ) {
				// Get the attachment id
				$post_id = $item->get_attachment_id_from_url( $placeholder_image );
			} else {
				$post_id           = (int) $placeholder_image;
				$placeholder_image = wp_get_attachment_url( $post_id );
			}

			// Now - get large size, medium, plus thumbnail
			$sizes         = $item->get_image_urls( $post_id );
			$sizes['full'] = $placeholder_image;

			$_POST['placeholder_image'] = json_encode( $sizes );
		} else {
			$_POST['placeholder_image'] = '';
		}

		foreach ( $settings AS $field => $value ) {
			if ( isset( $_POST[ $field ] ) ) {
				// email intentionally omitted
				if ( is_numeric( $_POST[ $field ] ) ) {
					$val = (float) $_POST[ $field ];
				} else if ( strpos( $_POST[ $field ], "\n" ) ) {
					$val = sanitize_textarea_field( $_POST[ $field ] );
				} else {
					$val = sanitize_text_field( $_POST[ $field ] );
				}

				self::updateOption( $field, $val );
			}
		}

		if ( self::request( "rebuild_thumbnails" ) ) {
			self::rebuild_thumbnails();
		}

		do_action( 'wpim_save_settings' );

		self::analysis_messages();

		return TRUE;
	}

	/**
	 * Store messages in a transient for display on next page load.
	 *
	 * @param string|array $messages
	 */
	public static function update_transient_messages( $messages ) {
		if ( empty( $messages ) || is_bool( $messages ) ) {
			return;
		}

		$existing = get_transient( 'wpim_messages' );
		if ( $existing ) {
			if ( ! is_array( $existing ) ) {
				$existing = [ $existing ];
			}

			if ( ! is_array( $messages ) ) {
				$messages = [ $messages ];
			}

			$messages = array_merge( $existing, $messages );
		}

		set_transient( 'wpim_messages', $messages );
	}

	/**
	 * Common function available to render the table headings with relevant sort-by links / display
	 *
	 * @param array  $columns
	 * @param string $self
	 * @param string $default
	 * @param bool   $action
	 * @param string $hash
	 * @param bool   $include_id
	 *
	 * @return string
	 */
	public static function grid_columns( $columns, $self, $default = 'name', $action = FALSE, $hash = '', $include_id = TRUE ) {
		if ( ! self::$sortby ) {
			self::$sortby = $default;
		}

		$content = '<tr class="title">';

		if ( $include_id ) {
			$content .= '<th class="medium">' . self::__( 'ID #:' ) . '</th>';
		}

		foreach ( $columns as $sort_field => $column ) {
			$sort_field = apply_filters( 'wpim_admin_listing_sortfield', $sort_field );
			$class      = ( isset( $column['class'] ) ) ? $column['class'] : '';
			$sortdir    = self::parse_sort_dir( $sort_field );
			$content    .= '<th class="' . $class . '">';

			if ( is_numeric( $sort_field ) ) {
				$sort_field = '';
			}

			if ( $sort_field ) {
				$content .= '<a href="' . $self . '&sortby=' . $sort_field . '&sortdir=' . $sortdir . $hash . '">';
			}

			$content .= esc_attr( $column['title'] );

			if ( $sort_field ) {
				$content .= '</a>';
			}

			if ( self::compare_sort_field( $sort_field ) ) {
				$alt     = ( self::$sortdir == 'ASC' ) ? '&uarr;' : '&darr;';
				$content .= '<strong>' . $alt . '</strong>';
			}
		}

		$content .= ( $action == NULL ) ? '<th class="actions">' . self::__( 'Actions' ) . '</th>' : '';
		$content .= '</tr>';

		$content = apply_filters( 'wpim_grid_columns', $content, $columns, $self );

		return $content;
	}

	/**
	 * Determine if the passed-in field is the same field being currently sorted,
	 * and return the corresponding direction.
	 *
	 * @param string $sort_field
	 *
	 * @return string
	 */
	private static function parse_sort_dir( $sort_field ) {
		$dir = ( self::compare_sort_field( $sort_field ) && strtolower( self::$sortdir ) == 'asc' ) ? 'DESC' : 'ASC';

		return $dir;
	}

	/**
	 * Compare sort field with current sort field.
	 * Since numeric sorting can munge the sort field, parse out the function, etc.
	 *
	 * @param $sort_field
	 *
	 * @return bool
	 */
	private static function compare_sort_field( $sort_field ) {
		$current = self::$sortby;
		if ( FALSE !== stripos( $current, 'CAST(' ) ) {
			$current = trim( str_ireplace( [ 'CAST(', ' AS DECIMAL)', '`' ], '', $current ) );
		}

		if ( FALSE !== stripos( $current, ' ASC' ) || FALSE !== stripos( $current, ' DESC' ) ) {
			$current = trim( str_replace( [ ' ASC', ' DESC' ], '', $current ) );
		}

		return ( $current == $sort_field );
	}

	private static function get_item( $inventory_id ) {
		return self::$item->get( $inventory_id );
	}

	private static function get_item_fields( $args = NULL ) {
		return self::$item->get_fields();
	}

	public static function get_item_images( $inventory_id ) {
		return self::$item->get_images( $inventory_id );
	}

	public static function get_item_media( $inventory_id ) {
		return self::$item->get_media( $inventory_id );
	}

	private static function get_categories( $args = NULL ) {
		return self::$category->get_all( $args );
	}

	private static function get_category( $category_id = NULL ) {
		if ( ! $category_id ) {
			$category_id = self::$config->query_vars["category_id"];
		}

		return self::$category->get( $category_id );
	}

	private static function rebuild_thumbnails() {
		self::$item->rebuild_image_thumbs();
	}

	/**
	 * Utility tool to go through all items and validate / repair duplicate slugs.
	 *
	 * return int
	 */
	private static function repair_slugs() {
		$total           = 0;
		$duplicate_slugs = self::$db->get_results( "SELECT inventory_slug, count(*) AS number FROM `" . self::$db->inventory_table . "` GROUP BY inventory_slug HAVING count(*) > 1 " );

		foreach ( $duplicate_slugs AS $info ) {
			$duplicate_slug = $info->inventory_slug;

			$where = ( NULL === $duplicate_slug ) ? 'IS NULL' : self::$db->prepare( '= %s', $duplicate_slug );

			$items = self::$db->get_results( 'SELECT inventory_id, inventory_name, inventory_number, inventory_slug FROM ' . self::$db->inventory_table . ' WHERE inventory_slug ' . $where );
			$total += count( $items );

			// If there's already a slug in place, the FIRST one is OK / legit, only need to change others
			if ( $duplicate_slug ) {
				array_shift( $items );
			}

			foreach ( $items AS $item ) {
				$name     = ( trim( $item->inventory_name ) ) ? $item->inventory_name : $item->inventory_number;
				$new_slug = self::$item->validate_slug( 'inventory', $item->inventory_slug, $name, $item->inventory_id );

				self::$item->update( $item->inventory_id, [ 'inventory_slug' => $new_slug ] );
			}

		}

		return $total;
	}

	/**
	 * Utility tool to go through all categories and validate / repair duplicate slugs.
	 */
	private static function repair_category_slugs() {
		$total           = 0;
		$duplicate_slugs = self::$db->get_results( "SELECT category_slug, count(*) AS number FROM `" . self::$db->category_table . "` GROUP BY category_slug HAVING count(*) > 1 " );

		foreach ( $duplicate_slugs AS $info ) {
			$duplicate_slug = $info->category_slug;
			$where          = ( NULL === $duplicate_slug ) ? 'IS NULL' : self::$db->prepare( '= %s', $duplicate_slug );

			$categories = self::$db->get_results( 'SELECT category_id, category_name, category_slug FROM ' . self::$db->category_table . ' WHERE category_slug ' . $where );
			$total      += count( $categories );

			// If there's already a slug in place, the FIRST one is OK / legit, only need to change others
			if ( $duplicate_slug ) {
				array_shift( $categories );
			}

			foreach ( $categories AS $category ) {
				$name     = $category->category_name;
				$new_slug = self::$item->validate_slug( 'category', $category->category_slug, $name, $category->category_id );

				self::$category->update( $category->category_id, [ 'category_slug' => $new_slug ] );
			}
		}

		return $total;
	}

	/**
	 * Add Ons page
	 */
	public static function wpim_manage_add_ons() {

		self::$self_url = 'admin.php?page=' . __FUNCTION__;

		self::admin_heading( self::__( 'WP Inventory Add Ons' ) );

		$add_ons = self::get_add_ons();

		echo sprintf( self::__( '%sUpgrade to %sWP Inventory Pro%s to use these add ons!%s' ), '<p>', '<a href="https://www.wpinventory.com" target="_blank">', '</a>', '</p>' );

		foreach ( $add_ons AS $add_on ) {
			echo '<div class="add_on">';
			echo '<h3>' . $add_on->title;
			echo '<span style="font-size: 11px; color: #ccc; float: right">' . $add_on->item_id . '</span>';
			echo '</h3>';
			if ( ! empty( $add_on->image ) ) {
				echo '<div class="image"><img src="' . $add_on->image . '"></div>';
			}
			echo '<div class="description">' . $add_on->description . '</div>';
			echo '<p class="learn_more">';
			if ( ! empty( $add_on->learn_more_url ) ) {
				echo '<a href="' . $add_on->learn_more_url . '">' . self::__( 'Learn More' ) . '</a></p>';
			}
			if ( ! empty( $add_on->download_url ) ) {
				echo '<a class="download" href="' . $add_on->download_url . '">' . self::__( 'Download' ) . '</a>';
			}
			echo '</p>';
			echo '</div>';
		}

		self::admin_footer();
	}

	private static function admin_heading( $subtitle ) {
		echo '<div class="wrap inventorywrap">' . PHP_EOL;
		self::header();
		echo '<h3>' . esc_attr( $subtitle ) . '</h3>' . PHP_EOL;
		echo self::output_errors();
		echo self::output_messages();
	}

	private static function admin_footer() {
		echo '</div>' . PHP_EOL;
	}
}
