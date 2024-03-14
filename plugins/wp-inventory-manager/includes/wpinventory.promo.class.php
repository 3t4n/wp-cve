<?php

class WPIMPromo extends WPIMCore {
	private $plugins;
	private $promote = [];
	private $promotion_shown = FALSE;
	private $can_dismiss = FALSE;
	private $this_promo = '';
	private $dismissed = '';
	private $TEST_MODE = FALSE;

	public function __construct() {
		$this->set_up_promotions();

		add_action( 'admin_notices', [ $this, 'dismissal_notices' ] );
		add_action( 'wpim_admin_menu', [ $this, 'wpim_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'admin_footer', [ $this, 'admin_footer' ] );
	}

	public function dismissal_notices() {
		if ( ! $this->dismissed ) {
			return;
		}

		$dismissed = $this->promote[ $this->dismissed ];
		if ( empty( $dismissed['title'] ) ) {
			return;
		}

		$add_on_link      = admin_url( 'admin.php?page=wpim_manage_add_ons' );
		$wpinventory_link = 'https://www.wpinventory.com/add-ons';
		$add_on_link      = '<a href="' . $add_on_link . '">' . self::__( 'the Add-Ons page' ) . '</a>';
		$wpinventory_link = '<a href="' . $wpinventory_link . '">' . self::__( 'The WPInventory Website' );

		echo '<div class="notice notice-success">';
		echo '<p>' . self::__( sprintf( 'The %s Add-On promo will not be shown again.  You can always find it by checking out %s or visiting %s.', $dismissed['title'], $add_on_link, $wpinventory_link ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * WP Inventory wpim_admin_menu hook,
	 * which is a "child" hook of WordPress admin_menu hook.
	 * Allows registering menus that are sub-menus of the WP Inventory menu
	 * at the appropriate position.
	 *
	 * This function first checks if the Add-Ons are present (even if not active),
	 * and if so, prevents adding the menus.
	 */
	public function wpim_admin_menu() {
		$this->handle_dismissal();
		$lowest_role = self::$config->get( 'permissions_lowest_role' );
		$this->find_wpim_plugins();
		$this->ensure_can_promote();

		if ( empty( $this->promote ) ) {
			return;
		}

		foreach ( $this->promote AS $key => $data ) {
			if ( ! empty( self::$config->get( "dismissed_{$key}" ) ) ) {
				continue;
			}

			if ( is_callable( [ $this, $data['callback'] ] ) ) {
				add_submenu_page( self::MENU, $data['menu'], $data['menu'], $lowest_role, "wpim_{$data['callback']}", [ $this, $data['callback'] ] );
			}
		}
	}

	/**
	 * WordPress admin_enqueue_scripts hook.
	 * Registers the styles, which are then only included (in the footer)
	 * if a "promo" page is displayed.
	 */
	public function admin_enqueue_scripts() {
		wp_register_style( 'wpim-promo', self::$url . 'css/promo-admin.css' );
	}

	/**
	 * WordPress admin_footer hook.
	 * IF the promo page has been shown, then output the special stylesheet.
	 */
	public function admin_footer() {
		if ( ! $this->promotion_shown ) {
			return;
		}

		wp_print_styles( [ 'wpim-promo' ] );
	}

	private function handle_dismissal() {
		if ( ! self::is_wpinventory_page() ) {
			return;
		}

		if ( empty( $_GET['dismiss'] ) ) {
			return;
		}

		$this->dismissed = sanitize_text_field( $_GET['dismiss'] );

		self::$config->set( "dismissed_{$this->dismissed}", TRUE );
	}

	/**
	 * Get a list of all installed plugins that appear to be "WP Inventory" related
	 * (including add-ons, even if not activated).
	 */
	private function find_wpim_plugins() {
		$plugins = get_plugins();
		$plugins = array_map( function ( $plugin ) {
			return $plugin['Name'];
		}, $plugins );

		$this->plugins = array_filter( $plugins, function ( $plugin ) {
			return ( 0 === stripos( $plugin, 'wp inventory' ) );
		} );
	}

	/**
	 * Reduces the "promote" list to ONLY add-ons that aren't actually
	 * installed (even if not activated).
	 */
	private function ensure_can_promote() {
		if ( $this->TEST_MODE ) {
			return;
		}

		foreach ( $this->promote AS $key => $data ) {
			$keywords = $data['keywords'];
			foreach ( $this->plugins AS $plugin ) {
				$match = TRUE;
				foreach ( $keywords AS $keyword ) {
					if ( FALSE === stripos( $plugin, $keyword ) ) {
						$match = FALSE;
					}
				}

				if ( $match ) {
					unset( $this->promote[ $key ] );
					break;
				}
			}
		}
	}

	/**
	 * Common method to render the "promo" markup.
	 * Additionally sets the flag for styles to be output in the footer.
	 * NOTE: This MUST be used, otherwise the styles won't be output!
	 *
	 * @param string $title
	 * @param string $key
	 */
	private function open_promo_markup( $title, $key ) {
		$this->this_promo = $key;
		echo '<div class="inventorywrap">' . PHP_EOL;

		WPIMAdmin::header( $title, 'latest', 'version' );

		$this->promotion_shown = TRUE;
	}

	/**
	 * Common method to render the "promo" closing markup.
	 */
	private function close_promo_markup() {
		if ( $this->can_dismiss ) {
			echo '<div class="dismiss-promo">';
			echo '<a href="' . add_query_arg( 'dismiss', $this->this_promo, self::$self_url ) . '">' . self::__( 'Dismiss and Do Not Show Again' ) . '</a>';
			echo '</div>';
		}

		echo '</div>';
	}


	/**
	 * Render the purchase link
	 *
	 * @param string $id
	 *
	 * @return string
	 */
	public function render_checkout_link( $id ) {
		if ( ! $id ) {
			return '';
		}

		return '<a class="button button-primary green" href="https://www.wpinventory.com/checkout?edd_action=add_to_cart&download_id=' . $id . '" target="_blank">' . self::__( 'Buy Now' ) . '</a>';

	}

	/**
	 * Renders the "All Access Pass" sidebar, which is common to all of the promo pages.
	 */
	private function promote_all_access_pass() {
		echo '<div class="col col-2">';
		echo '<div class="wpim_sidebar">';
		echo '<h2>' . self::__( 'All-Access Pass' ) . '</h2>';
		echo '<h3 class="best-value">' . self::__( 'Best Value!' ) . '</h3>';
		echo '<h3>' . self::__( 'For just $149, unlock full access to all WP Inventory Add-ons:' ) . '</h3>';
		echo '<ul>';
		echo '<li><a href="https://www.wpinventory.com/downloads/add-advanced-inventory-manager/" target="_blank">Advanced Inventory Manager</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/wp-inventory-import-and-export/" target="_blank">Import / Export</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/wp-inventory-ledger/" target="_blank">Ledger</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/advanced-user-control/" target="_blank">Advanced User Control</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/add-on-locations-manager/" target="_blank">Locations Manager</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/add-on-bulk-item-manager/" target="_blank">Bulk Item Manager</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/add-on-advanced-search/" target="_blank">Advanced Search Filter</a></li>';
		echo '<li><a href="https://www.wpinventory.com/downloads/add-reserve-cart/" target="_blank">Reserve Cart</a></li>';
		echo '</ul>';

		echo '<div class="promotion_ctas"><a href="https://www.wpinventory.com/downloads/wp-inventory-manager-all-access-pass/" target="_blank">Learn More</a> <a class="button button-primary green" href="https://www.wpinventory.com/checkout?edd_action=add_to_cart&download_id=1990" target="_blank">' . self::__( 'Buy Now' ) . '</a></div>';
		echo '</div>';
		echo '</div>'; // End wpim_sidebar

	}

	/**
	 * Promo markup for Import / Export add-on.
	 */
	public function promote_ie() {
		$this->open_promo_markup( self::__( 'Import / Export' ), 'ie' );

		echo '<div class="col col-6">';
		echo '<h2>' . self::__( 'Powerful Importing / Exporting Tools' ) . '</h2>';
		echo '<h3>' . self::__( 'Just $59.99' ) . '</h3>';
		echo '<ul>';
		echo '<li>' . self::__( 'Allows importing and exporting items quickly and easily.' ) . '</li>';
		echo '<li>' . self::__( 'Update your inventory offline, and simply import a CSV.' ) . '</li>';
		echo '<li>' . self::__( 'Add new items quickly.' ) . '</li>';
		echo '</ul>';
		echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/K9wmzE_viMM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

		echo '<div class="promotion_ctas"><a href="https://www.wpinventory.com/documentation/user/add-on-documentation/importing-exporting-inventory/" target="_blank">' . self::__( 'Learn More' ) . '</a> ' . self::render_checkout_link( 655 ) . '</div>';

		echo '<p><strong>Please note</strong> that an active license of <a href="https://www.wpinventory.com/checkout?edd_action=add_to_cart&download_id=675" target="_blank">WP Inventory Manager</a> is required in addition to this add on.</p>';
		echo '</div>';

		$this->promote_all_access_pass();
		$this->close_promo_markup();
	}

	/**
	 * Promo markup for Advanced Inventory Manager
	 */
	public function promote_aim() {
		$this->open_promo_markup( self::__( 'Advanced Inventory Manager' ), 'aim' );

		echo '<div class="col col-6">';
		echo '<h2>' . self::__( 'More fields, more types.' ) . '</h2>';
		echo '<h3>' . self::__( 'Just $79.99' ) . '</h3>';
		echo '<ul>';
		echo '<li>' . self::__( 'Add "types" of inventory and fields for each' ) . '</li>';
		echo '<li>' . self::__( 'Add as many fields as you want and various types like radio, text, number and drop down select' ) . '</li>';
		echo '<li>' . self::__( 'Display item types via shortcode option on any page' ) . '</li>';
		echo '</ul>';

		echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/LBnlSokdOpk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

		echo '<div class="promotion_ctas"><a href="https://www.wpinventory.com/documentation/user/add-on-documentation/advanced-inventory-manager/" target="_blank">' . self::__( 'Learn More' ) . '</a> ' . self::render_checkout_link( 2917 ) . '</div>';
		echo '</div>';

		$this->promote_all_access_pass();
		$this->close_promo_markup();
	}

	/**
	 * Promo markup for Locations Manager
	 */
	public function promote_locations() {
		$this->open_promo_markup( self::__( 'Locations Manager' ), 'locations' );

		echo '<div class="col col-6">';
		echo '<h2>' . self::__( 'Track Inventory Across Multiple Locations.' ) . '</h2>';
		echo '<h3>' . self::__( 'Just $39.99' ) . '</h3>';
		echo '<ul>';
		echo '<li>' . self::__( 'Add as many locations as you want' ) . '</li>';
		echo '<li>' . self::__( 'Manage each item and the location(s) it is at' ) . '</li>';
		echo '<li>' . self::__( 'No limits on quantities you can add to each location' ) . '</li>';
		echo '</ul>';

		echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/6xs4jIhpE58" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

		echo '<div class="promotion_ctas"><a href="https://www.wpinventory.com/documentation/user/add-on-documentation/managing-inventory-locations/" target="_blank">' . self::__( 'Learn More' ) . '</a> ' . self::render_checkout_link( 13153 ) . '</div>';
		echo '</div>';

		$this->promote_all_access_pass();
		$this->close_promo_markup();
	}

	/**
	 * Sets up the data for all the promotions.
	 * Data is structured as an array.
	 * The key is just for convenient reference, the data
	 * dictates:
	 * keywords - which keywords must be present in the add-on name in order for it to be considered a "match"
	 * menu - the "Title" that is used in the admin menu
	 * callback - the name of the function in this class that is called when the menu item is clicked
	 */
	private function set_up_promotions() {
		$this->promote = [
			'ie'        => [
				'keywords' => [
					'Import',
					'Export'
				],
				'menu'     => self::__( 'Import / Export' ),
				'title'    => self::__( 'Import / Export' ),
				'callback' => 'promote_ie'
			],
			'aim'       => [
				'keywords' => [
					'Advanced',
					'Inventory',
					'Manager'
				],
				'menu'     => self::__( 'Advanced Management' ),
				'title'    => self::__( 'Advanced Inventory Manager' ),
				'callback' => 'promote_aim'
			],
			'locations' => [
				'keywords' => [ 'Location' ],
				'menu'     => self::__( 'Locations Manager' ),
				'title'    => self::__( 'Locations Manager' ),
				'callback' => 'promote_locations'
			]
		];
	}
}

new WPIMPromo();
