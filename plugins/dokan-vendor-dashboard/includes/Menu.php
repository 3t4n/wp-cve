<?php

namespace WeDevs\DokanVendorDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Vendor Dashboard Menus.
 *
 *------------------------------------------------------------------*
 *      How we've handled the Menu for both Old and New Routes      *
 *------------------------------------------------------------------*
 *
 * Case 1: User doesn’t switch yet. [It's optional]
 *      1) Add switch toggle menu button
 *      2) On click switch toggle menu button -
 *          i) Store in Options table that user switched.
 *
 * Case 2: User switched.
 *      *) Loop throw the menu items and modify menu links like so -
 *          1) If Menu is in supported/New menu list
 *              i) Current Page is old
 *                  a) Generate Full-Page React Router URL (https://test.com/dashboard/#/test) for that Menu Item
 *              ii) Current Page is new
 *                  a) Generate a React Router URL for that Menu Item
 *                  b) Replace Icon Name  for that Menu Item
 *
 *          2) If Menu is not in supported/New menu list - that means, we don’t process this link yet.
 *              i) Current Page is old
 *                  // Do nothing
 *              ii) Current Page is new
 *                  a) Modify the icon
 *
 * @since 1.0.0
 */
class Menu {

	/**
	 * Supported menus for new dashboard.
	 *
	 * @var array
	 */
	private $supported_menus;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->set_supported_menus();

		add_filter( 'dokan_get_dashboard_nav', array( $this, 'get_menus' ) );
		add_filter( 'dvd_routes', array( $this, 'only_new_dashboards_routes_and_menus' ) );
		add_filter( 'dokan_vendor_dashboard_menu_hamburger', array( $this, 'add_site_image_with_hamburger_menu_for_old_dashboard' ) );
		add_action( 'dokan_dashboard_content_before', array( $this, 'add_dashboard_nav_for_design_compatibility' ) );
		add_filter( 'dokan_get_navigation_url', array( $this, 'modify_navigation_url' ), 10, 2 );
	}

	/**
	 * Modifies navigation url for the new dashboard menus.
	 *
	 * @since DOKAN_PRO_SINCE
	 *
	 * @param string $url The already created url.
	 * @param string $menu The menu key.
	 *
	 * @return string The modified url.
	 */
	public function modify_navigation_url( $url, $menu ) {
		if ( ! in_array( $menu, $this->get_supported_menus(), true ) ) {
			return $url;
		}

		if ( ! Settings::is_switched_new_dashboard() ) {
			return $url;
		}

		// Remove the filter to avoid unnecessary recursion.
		remove_filter( 'dokan_get_navigation_url', array( $this, 'modify_navigation_url' ), 10, 2 );

		$url = dokan_get_navigation_url( "#/$menu" );

		// Add the filter again as the url is modified already.
		add_filter( 'dokan_get_navigation_url', array( $this, 'modify_navigation_url' ), 10, 2 );

		return $url;
	}

	/**
	 * Get supported menus for new dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_supported_menus() {
		return $this->supported_menus;
	}

	/**
	 * Set supported menus for new dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @param array $supported_menus
	 *
	 * @return void
	 */
	public function set_supported_menus( $menus = array() ) {
		if ( count( $menus ) ) {
			$this->supported_menus = $menus;
			return $this;
		}

		$new_menus = $this->get_new_menus( [] );

		// filter the menus to add new ones which has is_supported = true
		$this->supported_menus = array_filter(
            $new_menus, function( $menu ) {
				return $menu['is_supported'];
			}
        );

		$this->supported_menus = array_keys( $this->supported_menus );
	}

	/**
	 * Modify the old dashboard menu and make a new one.
	 *
	 * @since 1.0.0
	 *
	 * @param array $menus
	 *
	 * @return array
	 */
	public function get_menus( $menus ) {
		foreach ( $menus as $menu_name => $menu ) {
			$menus[ $menu_name ] = $this->get_menu_item( $menu_name, $menu );
		}

		// Add menu items which was not in the old menu or whose sidebar = false
		$hidden_menus = array_filter(
            $this->get_new_menus( [] ), function( $menu ) {
				return ! wp_validate_boolean( $menu['sidebar'] );
			}
        );

		// Merge hidden menus with the menus
		return array_merge( $hidden_menus, $menus );
	}

	/**
	 * Modify the menu item and make a new one.
	 *
	 * This method will modify the menu item based on the current page.
	 * and the menu item is supported or not.
	 *
	 * Steps:
	 * 1) If Menu is in supported/New menu list
 	 *      i) Current Page is old
 	 *          a) Generate Full-Page React Router URL (https://test.com/dashboard/#/test) for that Menu Item
 	 *      ii) Current Page is new
 	 *          a) Generate a React Router URL for that Menu Item
 	 *          b) Replace Icon Name  for that Menu Item
 	 *
 	 * 2) If Menu is not in supported/New menu list - that means, we don’t process this link yet.
 	 *      i) Current Page is old
 	 *          // Do nothing
 	 *      ii) Current Page is new
 	 *          a) Modify the icon
	 *
	 * @since 1.0.0
	 *
	 * @param string $menu_name
	 * @param array  $menu
	 *
	 * @return array
	 */
	public function get_menu_item( $menu_name, $menu ) {
		$is_supported_new = in_array( $menu_name, $this->get_supported_menus(), true );
		$is_old_page = dokan_vendor_dashboard()->assets->is_old_route();

		$new_menu = $this->get_new_menus(
			array(
				'menu_name' => $menu_name,
			)
		);

		// If not found in new menu, but exists in old menu,
		// then we should also add this in the new menu sidebar
		if ( empty( $new_menu ) ) {
			$menu['sidebar']      = true;
			$menu['is_supported'] = false;

			return $menu;
		}

		// Check if menu is in supported/new menu list
		if ( $is_supported_new && $is_old_page ) {
			// Generate Full-Page React Router URL (https://test.com/dashboard/#/test) for that Menu Item
            $new_menu['url'] = ( '/' !== $new_menu['url'] ) ? '#' . $new_menu['url'] : '';
            $menu['url']     = dokan_get_navigation_url( $new_menu['url'] );
            $menu['icon']    = "<span class='dokan-old-menu-icon'><i class=" . $new_menu['icon'] . '></i></span>';

            // Trim the last character of the url because the last character is / and it is not required for react routes
            if ( $menu['url'] && '/' === substr( $menu['url'], -1 ) ) {
                $menu['url'] = rtrim( $menu['url'], '/' );
            }
		} elseif ( $is_supported_new && ! $is_old_page ) {
			// Generate a React Router URL for that Menu Item
			$menu['url'] = $new_menu['url'];

			// Replace Icon Name for that Menu Item
			$menu['icon'] = $new_menu['icon'];
		}

		// Handle for menus which are not in supported/new menu list
		if ( ! $is_supported_new && ! $is_old_page ) {
			// Modify the icon only
			$menu['icon'] = $new_menu['icon'];
		}

		// Modify for Settings
		if ( 'settings' === $menu_name && ! $is_old_page ) {
			$menu['title'] = __( 'Settings', 'dokan-vendor-dashboard' );
		}

		// Update some other options for new menu
		$menu['component']    = $new_menu['component'];
		$menu['is_supported'] = $new_menu['is_supported'];
		$menu['sidebar']      = $new_menu['sidebar'];
		$menu['submenu']      = isset( $new_menu['submenu'] ) ? $new_menu['submenu'] : [];

		return $this->process_sub_menus( $menu_name, $menu, $is_supported_new, $is_old_page );
	}

	/**
	 * Process submenus and return the menu.
	 *
	 * @since 1.0.0
	 *
	 * @param string $menu_name
	 * @param array $menu
	 * @param bool $is_supported_new
	 * @param bool $is_old_page
	 *
	 * @return array $menu
	 */
	public function process_sub_menus( $menu_name, $menu, $is_supported_new, $is_old_page ) {
		if ( ! isset( $menu['submenu'] ) ) {
			return $menu;
		}

		foreach ( $menu['submenu'] as $sub_menu_name => $sub_menu ) {
			$menu['submenu'][ $sub_menu_name ] = $this->get_menu_item( $sub_menu_name, $sub_menu );
			$new_sub_menu = $this->get_new_menus(
				array(
					'menu_name'    => $menu_name,
					'is_submenu'   => true,
					'submenu_name' => $sub_menu_name,
				)
			);

			$new_sub_menu['url'] = isset( $new_sub_menu['url'] ) ? $new_sub_menu['url'] : $menu['submenu'][ $sub_menu_name ]['url'];
			$new_sub_menu['icon'] = isset( $new_sub_menu['icon'] ) ? $new_sub_menu['icon'] : $menu['submenu'][ $sub_menu_name ]['icon'];

			if ( $is_supported_new && $is_old_page ) {
				$new_sub_menu['url'] = ( '/' !== $new_sub_menu['url'] ) ? '#' . $new_sub_menu['url'] : '/';
				$menu['submenu'][ $sub_menu_name ]['url'] = dokan_get_navigation_url( $new_sub_menu['url'] );
			} elseif ( $is_supported_new && ! $is_old_page ) {
				$menu['submenu'][ $sub_menu_name ]['url'] = $new_sub_menu['url'];
				$menu['submenu'][ $sub_menu_name ]['icon'] = $new_sub_menu['icon'];
			}

			if ( ! $is_supported_new && ! $is_old_page ) {
				$menu['submenu'][ $sub_menu_name ]['icon'] = $new_sub_menu['icon'];
			}
		}

		return $menu;
	}

	/**
	 * Get New Menus for vendor-dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 *
	 * @return array|null
	 */
	public function get_new_menus( $args ) {
		$defaults = [
			'menu_name'  => '',
			'is_submenu' => false,
		];

		$args = wp_parse_args( $args, $defaults );

		$menus = [
			'dashboard' => [
				'title'         => __( 'Dashboard', 'dokan-vendor-dashboard' ),
				'url'           => '/',
				'component'     => 'Dashboard',
				'icon'          => 'dokan-icon-category',
				'sidebar'       => true,
				'is_supported'  => true,
			],
			'products' => [
				'title'         => __( 'Products', 'dokan-vendor-dashboard' ),
				'url'           => '/products',
				'component'     => 'Products',
				'icon'          => 'dokan-icon-products',
				'sidebar'       => true,
				'is_supported'  => true,
			],
			'orders' => [
				'title'         => __( 'Orders', 'dokan-vendor-dashboard' ),
				'url'           => '/orders',
				'component'     => 'Orders',
				'icon'          => 'dokan-icon-cart-1',
				'sidebar'       => true,
				'is_supported'  => true,
			],
			'withdraw' => [
				'title'         => __( 'Withdraw', 'dokan-vendor-dashboard' ),
				'url'           => '/withdraw',
				'component'     => 'Withdraw',
				'icon'          => 'dokan-icon-withdraw',
				'sidebar'       => true,
				'is_supported'  => true,
			],
			'followers' => [
				'title'         => __( 'Followers', 'dokan-vendor-dashboard' ),
				'url'           => '/followers',
				'component'     => 'Followers',
				'icon'          => 'fas fa-heart',
				'sidebar'       => true,
				'is_supported'  => true,
			],
			'seller-badge' => [
				'title'         => __( 'Seller Badge', 'dokan-vendor-dashboard' ),
				'url'           => '/seller-badge',
				'component'     => 'SellerBadgePro',
				'icon'          => 'fas fa-award',
				'sidebar'       => true,
				'is_supported'  => true,
			],
		];

		if ( $args['menu_name'] && $args['is_submenu'] && $args['submenu_name'] ) {
			return isset( $menus[ $args['menu_name'] ]['submenu'][ $args['submenu_name'] ] ) ? $menus[ $args['menu_name'] ]['submenu'][ $args['submenu_name'] ] : null;
		}

		if ( $args['menu_name'] ) {
			return isset( $menus[ $args['menu_name'] ] ) ? $menus[ $args['menu_name'] ] : null;
		}

		return $menus;
	}

	/**
	 * This routes are for new vendor dashboard and will not show in menu and also will not show in backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param array $routes
	 *
	 * @return array
	 */
	public function only_new_dashboards_routes_and_menus( $routes ) {
		$new_routes = [
			'product_create' => [
				'title'         => __( 'Product Create', 'dokan-vendor-dashboard' ),
				'url'           => '/products/create',
				'component'     => 'ProductCreate',
				'icon'          => 'dokan-icon-coupons',
				'sidebar'       => false,
				'is_supported'  => true,
			],
			'product_update' => [
				'title'         => __( 'Product Update', 'dokan-vendor-dashboard' ),
				'url'           => '/products/update/:productId',
				'component'     => 'ProductEdit',
				'icon'          => 'dokan-icon-coupons',
				'sidebar'       => false,
				'is_supported'  => true,
			],
			'order_details' => [
				'title'         => __( 'Order Details', 'dokan-vendor-dashboard' ),
				'url'           => '/orders/:orderId',
				'component'     => 'OrderDetails',
				'icon'          => 'dokan-icon-coupons',
				'sidebar'       => false,
				'is_supported'  => true,
			],
		];

		if ( 'on' === dokan_get_option( 'enable_pricing', 'dokan_spmv', 'on' ) ) {
			$new_routes['product_spmv'] = [
				'title'         => __( 'SPMV similar products', 'dokan-pro' ),
				'url'           => '/products/spmv',
				'component'     => 'SpmvProducts',
				'icon'          => 'dokan-icon-coupons',
				'sidebar'       => false,
				'is_supported'  => true,
			];
		}

		return apply_filters(
			'dokan_vd_internal_routes_and_menus',
			array_merge(
				$routes,
				$new_routes
			)
		);
	}

	/**
	 * Adding site image in menu for old dashboard backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hamburger_menu
	 *
	 * @return string $content
	 */
	public function add_site_image_with_hamburger_menu_for_old_dashboard( $hamburger_menu ) {
		$content = "
			<div class='dokan-vendor-dashboard-hamburger-menu-content'>
				<img src='" . get_site_icon_url() . "' alt=''/>
				<span>" . get_bloginfo( 'name' ) . '</span>
				' . $hamburger_menu . '
			</div>
		';
		return $content;
	}

	/**
	 * We are adding a nav bar in old backward compatibility dashboard to replicate as new dashboard.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_dashboard_nav_for_design_compatibility() {
		echo '<div class="dokan-vendor-dashboard-nav"><label class="mobile-menu-icon-expend" for="toggle-mobile-menu" aria-label="Menu">&#9776;</label></div>';
	}
}
