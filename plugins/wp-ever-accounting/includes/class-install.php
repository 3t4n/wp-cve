<?php
/**
 * Main Plugin Install Class.
 *
 * @since       1.0.2
 * @package     EverAccounting
 */

namespace EverAccounting;

defined( 'ABSPATH' ) || exit();

/**
 * Class Install
 *
 * @package EverAccounting
 * @since 1.0.2
 */
class Install {
	/**
	 * Updates and callbacks that need to be run per version.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	private static $updates = array(
		'1.0.2' => 'eaccounting_update_1_0_2',
		'1.1.0' => 'eaccounting_update_1_1_0',
	);

	/**
	 * Initialize all hooks.
	 *
	 * @since 1.0.2
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_filter( 'plugin_action_links_' . EACCOUNTING_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
		add_filter( 'wpmu_drop_tables', array( __CLASS__, 'wpmu_drop_tables' ) );
		add_filter( 'cron_schedules', array( __CLASS__, 'cron_schedules' ) );
		add_action( 'init', array( __CLASS__, 'background_updater' ) );
	}

	/**
	 * Check EverAccounting version and run the updater is required.
	 * This check is done on all requests and runs if the versions do not match.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public static function check_version() {
		// todo remove on later version.
		if ( false === get_option( 'eaccounting_version' ) && ! empty( get_option( 'eaccounting_localisation' ) ) ) {
			update_option( 'eaccounting_version', '1.0.1.1' );
		}

		if ( version_compare( get_option( 'eaccounting_version' ), eaccounting()->get_version(), '<' ) ) {
			self::maybe_update();
			do_action( 'eaccounting_updated' );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin Action links.
	 *
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=ea-settings' ) . '" aria-label="' . esc_attr__( 'View settings', 'wp-ever-accounting' ) . '">' . esc_html__( 'Settings', 'wp-ever-accounting' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file Plugin Base file.
	 *
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( EACCOUNTING_BASENAME !== $file ) {
			return $links;
		}

		$row_meta = array(
			'docs' => '<a href="' . esc_url( apply_filters( 'eaccounting_docs_url', 'https://wpeveraccounting.com/docs/' ) ) . '" aria-label="' . esc_attr__( 'View documentation', 'wp-ever-accounting' ) . '">' . esc_html__( 'Docs', 'wp-ever-accounting' ) . '</a>',
		);

		return array_merge( $links, $row_meta );
	}


	/**
	 * Uninstall tables when MU blog is deleted.
	 *
	 * @param array $tables List of tables that will be deleted by WP.
	 *
	 * @return string[]
	 */
	public static function wpmu_drop_tables( $tables ) {
		return array_merge( $tables, self::get_tables() );
	}

	/**
	 * Add more cron schedules.
	 *
	 * @param array $schedules List of WP scheduled cron jobs.
	 *
	 * @return array
	 */
	public static function cron_schedules( $schedules ) {
		$schedules['monthly'] = array(
			'interval' => 2635200,
			'display'  => esc_html__( 'Monthly', 'wp-ever-accounting' ),
		);

		$schedules['fifteendays'] = array(
			'interval' => 1296000,
			'display'  => esc_html__( 'Every 15 Days', 'wp-ever-accounting' ),
		);

		$schedules['weekly'] = array(
			'interval' => 604800,
			'display'  => esc_html__( 'Once Weekly', 'wp-ever-accounting' ),
		);

		return $schedules;
	}

	/**
	 * Install EverAccounting.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'eaccounting_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'eaccounting_installing', 'yes', MINUTE_IN_SECONDS * 1 );
		eaccounting_maybe_define_constant( 'EACCOUNTING_INSTALLING', true );
		require_once dirname( __FILE__ ) . '/admin/class-notices.php';
		require_once dirname( __FILE__ ) . '/class-settings.php';

		if ( ! eaccounting()->settings ) {
			eaccounting()->settings = new \EverAccounting\Settings();
		}

		self::remove_admin_notices();
		self::create_tables();
		self::verify_base_tables();
		self::create_options();
		self::create_categories();
		self::create_currencies();
		self::create_accounts();
		self::create_defaults();
		self::create_roles();
		self::schedule_events();
		self::maybe_enable_setup_wizard();

		eaccounting_protect_files( true );
		flush_rewrite_rules();
		delete_transient( 'eaccounting_installing' );
		do_action( 'eaccounting_installed' );
	}

	/**
	 * Check if all the base tables are present.
	 *
	 * @return array.
	 */
	public static function verify_base_tables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;
		$missing_tables = array();
		$tables         = self::get_tables();
		$notices        = \EverAccounting\Admin\Notices::init();
		foreach ( $tables as $table ) {
			if ( ! $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) ) {
				$missing_tables[] = $table;
			}
		}
		if ( 0 < count( $missing_tables ) ) {
			$notices->add_core_notice( 'tables_missing' );
		} else {
			$notices->remove_notice( 'tables_missing' );
		}

		return $missing_tables;
	}

	/**
	 * Create default options.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function create_options() {
		if ( empty( eaccounting_get_option( 'financial_year_start' ) ) ) {
			eaccounting_update_option( 'financial_year_start', '01-01' );
		}
		if ( empty( eaccounting_get_option( 'default_payment_method' ) ) ) {
			eaccounting_update_option( 'default_payment_method', 'cash' );
		}

		$installation_time = get_option( 'eaccounting_install_date' );
		if ( empty( $installation_time ) ) {
			update_option( 'eaccounting_install_date', time() );
		}
	}

	/**
	 * Create categories.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function create_categories() {
		// If no categories then create default categories.
		if ( ! eaccounting_get_categories( array( 'count_total' => true ) ) ) {
			eaccounting_insert_category(
				array(
					'name'    => esc_html__( 'Deposit', 'wp-ever-accounting' ),
					'type'    => 'income',
					'enabled' => '1',
				)
			);

			eaccounting_insert_category(
				array(
					'name'    => esc_html__( 'Other', 'wp-ever-accounting' ),
					'type'    => 'expense',
					'enabled' => '1',
				)
			);

			eaccounting_insert_category(
				array(
					'name'    => esc_html__( 'Sales', 'wp-ever-accounting' ),
					'type'    => 'income',
					'enabled' => '1',
				)
			);
		}

		// create transfer category.
		if ( ! eaccounting_get_currencies(
			array(
				'count_total' => true,
				'search'      => esc_html__(
					'Transfer',
					'wp-ever-accounting'
				),
			)
		) ) {
			eaccounting_insert_category(
				array(
					'name'    => esc_html__( 'Transfer', 'wp-ever-accounting' ),
					'type'    => 'other',
					'enabled' => '1',
				)
			);
		}
	}

	/**
	 * Create currencies.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function create_currencies() {
		// create currencies.
		if ( ! eaccounting_get_currencies( array( 'count_total' => true ) ) ) {

			eaccounting_insert_currency(
				array(
					'code' => 'USD',
					'rate' => '1',
				)
			);

			eaccounting_insert_currency(
				array(
					'code' => 'EUR',
					'rate' => '1.25',
				)
			);

			eaccounting_insert_currency(
				array(
					'code' => 'GBP',
					'rate' => '1.6',
				)
			);
			eaccounting_insert_currency(
				array(
					'code' => 'CAD',
					'rate' => '1.31',
				)
			);
			eaccounting_insert_currency(
				array(
					'code' => 'JPY',
					'rate' => '106.22',
				)
			);
			eaccounting_insert_currency(
				array(
					'code' => 'BDT',
					'rate' => '84.81',
				)
			);
		}
	}

	/**
	 * Create accounts.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function create_accounts() {
		if ( ! eaccounting_get_accounts( array( 'count_total' => true ) ) ) {
			eaccounting_insert_account(
				array(
					'name'            => 'Cash',
					'currency_code'   => 'USD',
					'number'          => '001',
					'opening_balance' => '0',
					'enabled'         => '1',
				)
			);
		}
	}

	/**
	 * Create default data.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function create_defaults() {
		if ( empty( eaccounting_get_option( 'default_account' ) ) ) {
			$accounts = eaccounting_get_accounts();
			if ( ! empty( $accounts ) ) {
				$account = array_pop( $accounts );
				eaccounting_update_option( 'default_account', $account->get_id() );
			}
		}
		if ( empty( eaccounting_get_option( 'default_currency' ) ) ) {
			$currencies = eaccounting_get_currencies( array( 'return' => 'raw' ) );
			$currencies = wp_list_pluck( $currencies, 'code' );
			$currency   = current( $currencies );
			if ( in_array( 'USD', $currencies, true ) ) {
				$currency = 'USD';
			}
			eaccounting_update_option( 'default_currency', $currency );
		}

		$defaults = array(
			'default_payment_method' => 'cash',
			'financial_year_start'   => '01-01',
			'company_name'           => eaccounting_get_site_name(),
			'company_email'          => get_option( 'admin_url' ),
			'invoice_prefix'         => 'INV-',
			'invoice_digit'          => '5',
			'invoice_due'            => '15',
			'invoice_item_label'     => esc_html__( 'Item', 'wp-ever-accounting' ),
			'invoice_price_label'    => esc_html__( 'Price', 'wp-ever-accounting' ),
			'invoice_quantity_label' => esc_html__( 'Quantity', 'wp-ever-accounting' ),
			'bill_prefix'            => 'BILL-',
			'bill_digit'             => '5',
			'bill_due'               => '15',
			'bill_item_label'        => esc_html__( 'Item', 'wp-ever-accounting' ),
			'bill_price_label'       => esc_html__( 'Price', 'wp-ever-accounting' ),
			'bill_quantity_label'    => esc_html__( 'Quantity', 'wp-ever-accounting' ),
		);

		foreach ( $defaults as $key => $value ) {
			if ( empty( eaccounting_get_option( $key ) ) ) {
				eaccounting_update_option( $key, $value );
			}
		}
	}

	/**
	 * Reset any notices added to admin.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function remove_admin_notices() {
		update_option( 'eaccounting_notices', array() );
	}


	/**
	 * Get Table schema.
	 *
	 * When adding or removing a table, make sure to update the list of tables in get_tables().
	 *
	 * @return void
	 */
	public static function create_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$max_index_length = 191;
		$collate          = $wpdb->get_charset_collate();

		$tables = array(
			"CREATE TABLE {$wpdb->prefix}ea_accounts(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
		    `name` VARCHAR(191) NOT NULL COMMENT 'Account Name',
		    `number` VARCHAR(191) NOT NULL COMMENT 'Account Number',
		    `opening_balance` DOUBLE(15,4) NOT NULL DEFAULT '0.0000',
		    `currency_code` varchar(3) NOT NULL DEFAULT 'USD',
		    `bank_name` VARCHAR(191) DEFAULT NULL,
		    `bank_phone` VARCHAR(20) DEFAULT NULL,
		    `bank_address` VARCHAR(191) DEFAULT NULL,
		    `thumbnail_id` INT(11) DEFAULT NULL,
		   	`enabled` tinyint(1) NOT NULL DEFAULT '1',
		   	`creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `currency_code` (`currency_code`),
		    KEY `enabled` (`enabled`),
		    UNIQUE KEY (`number`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_categories(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
  		  	`name` VARCHAR(191) NOT NULL,
		  	`type` VARCHAR(50) NOT NULL,
		  	`color` VARCHAR(20) NOT NULL,
		  	`enabled` tinyint(1) NOT NULL DEFAULT '1',
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `type` (`type`),
		    KEY `enabled` (`enabled`),
		    UNIQUE KEY (`name`, `type`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_contacts(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) DEFAULT NULL,
			`name` VARCHAR(191) NOT NULL,
			`company` VARCHAR(191) NOT NULL,
			`email` VARCHAR(191) DEFAULT NULL,
			`phone` VARCHAR(50) DEFAULT NULL,
			`website` VARCHAR(191) DEFAULT NULL,
			`birth_date` date DEFAULT NULL,
			`vat_number` VARCHAR(50) DEFAULT NULL,
			`street` VARCHAR(191) DEFAULT NULL,
			`city` VARCHAR(191) DEFAULT NULL,
			`state` VARCHAR(191) DEFAULT NULL,
			`postcode` VARCHAR(20) DEFAULT NULL,
			`country` VARCHAR(3) DEFAULT NULL,
			`currency_code` varchar(3),
  			`type` VARCHAR(100) DEFAULT NULL COMMENT 'Customer or vendor',
			`thumbnail_id` INT(11) DEFAULT NULL,
			`enabled` tinyint(1) NOT NULL DEFAULT '1',
			`creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `user_id`(`user_id`),
		    KEY `name`(`name`),
		    KEY `email`(`email`),
		    KEY `phone`(`phone`),
		    KEY `enabled`(`enabled`),
		    KEY `type`(`type`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_contactmeta(
			`meta_id` bigINT(20) NOT NULL AUTO_INCREMENT,
			`contact_id` bigint(20) unsigned NOT NULL default '0',
			`meta_key` varchar(255) default NULL,
			`meta_value` longtext,
			 PRIMARY KEY (`meta_id`),
		    KEY `contact_id`(`contact_id`),
			KEY `meta_key` (meta_key($max_index_length))
			) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_transactions(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
            `type` VARCHAR(100) DEFAULT NULL,
		  	`payment_date` date NOT NULL,
		  	`amount` DOUBLE(15,4) NOT NULL,
		  	`currency_code` varchar(3) NOT NULL DEFAULT 'USD',
		  	`currency_rate` double(15,8) NOT NULL DEFAULT 1,
            `account_id` INT(11) NOT NULL,
            `document_id` INT(11) DEFAULT NULL,
		  	`contact_id` INT(11) DEFAULT NULL,
		  	`category_id` INT(11) NOT NULL,
		  	`description` text,
	  		`payment_method` VARCHAR(100) DEFAULT NULL,
		  	`reference` VARCHAR(191) DEFAULT NULL,
			`attachment_id` INT(11) DEFAULT NULL,
		  	`parent_id` INT(11) DEFAULT NULL,
		    `reconciled` tinyINT(1) NOT NULL DEFAULT '0',
		    `creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `amount` (`amount`),
		    KEY `currency_code` (`currency_code`),
		    KEY `currency_rate` (`currency_rate`),
		    KEY `type` (`type`),
		    KEY `account_id` (`account_id`),
		    KEY `document_id` (`document_id`),
		    KEY `category_id` (`category_id`),
		    KEY `contact_id` (`contact_id`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_transfers(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
  			`income_id` INT(11) NOT NULL,
  			`expense_id` INT(11) NOT NULL,
  			`creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `income_id` (`income_id`),
		    KEY `expense_id` (`expense_id`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_documents(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
            `document_number` VARCHAR(100) NOT NULL,
            `type` VARCHAR(60) NOT NULL,
            `order_number` VARCHAR(100) DEFAULT NULL,
            `status` VARCHAR(100) DEFAULT NULL,
            `issue_date` DATETIME NULL DEFAULT NULL,
            `due_date` DATETIME NULL DEFAULT NULL,
            `payment_date` DATETIME NULL DEFAULT NULL,
            `category_id` INT(11) NOT NULL,
  			`contact_id` INT(11) NOT NULL,
  			`address` longtext DEFAULT NULL,
            `discount` DOUBLE(15,4) DEFAULT 0,
            `discount_type`  ENUM('percentage', 'fixed') DEFAULT 'percentage',
            `subtotal` DOUBLE(15,4) DEFAULT 0,
            `total_tax` DOUBLE(15,4) DEFAULT 0,
            `total_discount` DOUBLE(15,4) DEFAULT 0,
            `total_fees` DOUBLE(15,4) DEFAULT 0,
            `total_shipping` DOUBLE(15,4) DEFAULT 0,
            `total` DOUBLE(15,4) DEFAULT 0,
            `tax_inclusive` tinyINT(1) NOT NULL DEFAULT '0',
  			`note` TEXT DEFAULT NULL,
  			`terms` TEXT DEFAULT NULL,
			`attachment_id` INT(11) DEFAULT NULL,
		  	`currency_code` varchar(3) NOT NULL DEFAULT 'USD',
		  	`currency_rate` double(15,8) NOT NULL DEFAULT 1,
  			`key` VARCHAR(30) DEFAULT NULL,
  			`parent_id` INT(11) DEFAULT NULL,
  			`creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `type` (`type`),
		    KEY `status` (`status`),
		    KEY `issue_date` (`issue_date`),
		    KEY `contact_id` (`contact_id`),
		    KEY `category_id` (`category_id`),
		    KEY `total` (`total`),
		    KEY `currency_code` (`currency_code`),
		    KEY `currency_rate` (`currency_rate`),
		    UNIQUE KEY (`document_number`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_document_items(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
  			`document_id` INT(11) DEFAULT NULL,
  			`item_id` INT(11) DEFAULT NULL,
  			`item_name` VARCHAR(191) NOT NULL,
  			`price` double(15,4) NOT NULL,
  			`quantity` double(7,2) NOT NULL DEFAULT 0.00,
  			`subtotal` double(15,4) NOT NULL DEFAULT 0.00,
  			`tax_rate` double(15,4) NOT NULL DEFAULT 0.00,
  			`discount` double(15,4) NOT NULL DEFAULT 0.00,
  			`tax` double(15,4) NOT NULL DEFAULT 0.00,
  			`total` double(15,4) NOT NULL DEFAULT 0.00,
  			`currency_code` varchar(3) NOT NULL DEFAULT 'USD',
  			`extra` longtext DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `document_id` (`document_id`),
		    KEY `item_id` (`item_id`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_notes(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
  			`parent_id` INT(11) NOT NULL,
  			`type` VARCHAR(20) NOT NULL,
  			`note` TEXT DEFAULT NULL,
  			`extra` longtext DEFAULT NULL,
  			`creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `parent_id` (`parent_id`),
		    KEY `type` (`type`)
            ) $collate",

			"CREATE TABLE {$wpdb->prefix}ea_items(
            `id` bigINT(20) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(191) NOT NULL,
  			`sku` VARCHAR(100) NULL default '',
			`description` TEXT DEFAULT NULL ,
  			`sale_price` double(15,4) NOT NULL,
  			`purchase_price` double(15,4) NOT NULL,
  			`quantity` int(11) NOT NULL DEFAULT '1',
  			`category_id` int(11) DEFAULT NULL,
  			`sales_tax` double(15,4) DEFAULT NULL,
  			`purchase_tax` double(15,4) DEFAULT NULL,
  			`thumbnail_id` INT(11) DEFAULT NULL,
			`enabled` tinyint(1) NOT NULL DEFAULT '1',
			`creator_id` INT(11) DEFAULT NULL,
		    `date_created` DATETIME NULL DEFAULT NULL COMMENT 'Create Date',
		    PRIMARY KEY (`id`),
		    KEY `sale_price` (`sale_price`),
		    KEY `purchase_price` (`purchase_price`),
		    KEY `category_id` (`category_id`),
		    KEY `quantity` (`quantity`)
            ) $collate",
		);

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}
	}

	/**
	 * Return a list of EverAccounting tables.
	 * Used to make sure all EverAccounting tables are dropped when uninstalling the plugin
	 * in a single site or multi site environment.
	 *
	 * @return array EverAccounting tables.
	 */
	public static function get_tables() {
		global $wpdb;

		$tables = array(
			"{$wpdb->prefix}ea_accounts",
			"{$wpdb->prefix}ea_categories",
			"{$wpdb->prefix}ea_contacts",
			"{$wpdb->prefix}ea_contactmeta",
			"{$wpdb->prefix}ea_transactions",
			"{$wpdb->prefix}ea_transfers",
			"{$wpdb->prefix}ea_documents",
			"{$wpdb->prefix}ea_document_items",
			"{$wpdb->prefix}ea_notes",
			"{$wpdb->prefix}ea_items",
		);

		$tables = apply_filters( 'eaccounting_install_get_tables', $tables );

		return $tables;
	}

	/**
	 * Drop EverAccounting tables.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		$tables = self::get_tables();

		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
		}
	}

	/**
	 * Create roles and capabilities.
	 */
	public static function create_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new \WP_Roles();
		}

		// Dummy gettext calls to get strings in the catalog.
		_x( 'Accounting Manager', 'User role', 'wp-ever-accounting' );
		_x( 'Accountant', 'User role', 'wp-ever-accounting' );

		// Accountant role.
		add_role(
			'ea_accountant',
			'Accountant',
			array(
				'manage_eaccounting' => true,
				'ea_manage_customer' => true,
				'ea_manage_vendor'   => true,
				'ea_manage_account'  => true,
				'ea_manage_payment'  => true,
				'ea_manage_revenue'  => true,
				'ea_manage_transfer' => true,
				'ea_manage_category' => true,
				'ea_manage_currency' => true,
				'ea_manage_item'     => true,
				'ea_manage_invoice'  => true,
				'ea_manage_bill'     => true,
				'read'               => true,
			)
		);

		// Accounting manager role.
		add_role(
			'ea_manager',
			'Accounting Manager',
			array(
				'manage_eaccounting' => true,
				'ea_manage_report'   => true,
				'ea_manage_options'  => true,
				'ea_import'          => true,
				'ea_export'          => true,
				'ea_manage_customer' => true,
				'ea_manage_vendor'   => true,
				'ea_manage_account'  => true,
				'ea_manage_payment'  => true,
				'ea_manage_revenue'  => true,
				'ea_manage_transfer' => true,
				'ea_manage_category' => true,
				'ea_manage_currency' => true,
				'ea_manage_item'     => true,
				'ea_manage_invoice'  => true,
				'ea_manage_bill'     => true,
				'read'               => true,
			)
		);

		// add caps to admin.
		global $wp_roles;

		if ( is_object( $wp_roles ) ) {
			$wp_roles->add_cap( 'administrator', 'manage_eaccounting' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_report' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_options' );
			$wp_roles->add_cap( 'administrator', 'ea_import' );
			$wp_roles->add_cap( 'administrator', 'ea_export' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_customer' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_vendor' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_account' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_payment' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_revenue' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_transfer' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_category' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_currency' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_item' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_invoice' );
			$wp_roles->add_cap( 'administrator', 'ea_manage_bill' );
		}
	}

	/**
	 * Remove EverAccounting roles.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public static function remove_roles() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new \WP_Roles();
		}

		remove_role( 'ea_accountant' );
		remove_role( 'ea_manager' );
	}


	/**
	 * Create cron jobs (clear them first).
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public static function schedule_events() {
		wp_clear_scheduled_hook( 'eaccounting_twicedaily_scheduled_events' );
		wp_clear_scheduled_hook( 'eaccounting_daily_scheduled_events' );
		wp_clear_scheduled_hook( 'eaccounting_weekly_scheduled_events' );

		wp_schedule_event( time() + ( 6 * HOUR_IN_SECONDS ), 'twicedaily', 'eaccounting_twicedaily_scheduled_events' );
		wp_schedule_event( time() + 10, 'daily', 'eaccounting_daily_scheduled_events' );
		wp_schedule_event( time() + ( 3 * HOUR_IN_SECONDS ), 'weekly', 'eaccounting_weekly_scheduled_events' );
	}

	/**
	 * See if we need the wizard or not.
	 *
	 * @since 1.0.2
	 */
	private static function maybe_enable_setup_wizard() {
		if ( apply_filters( 'eaccounting_enable_setup_wizard', true ) && self::is_new_install() ) {
			set_transient( '_eaccounting_activation_redirect', 1, 30 );
		}
	}

	/**
	 * See if we need to show or run database updates during install.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function maybe_update() {
		if ( self::needs_update() ) {
			self::update();
		} else {
			self::update_version();
		}
	}

	/**
	 * Is an update needed?
	 *
	 * @return boolean
	 * @since  1.0.2
	 */
	public static function needs_update() {
		$current_version = get_option( 'eaccounting_version', null );
		$updates         = self::$updates;
		$update_versions = array_keys( $updates );
		usort( $update_versions, 'version_compare' );

		return ! is_null( $current_version ) && version_compare( $current_version, end( $update_versions ), '<' );
	}

	/**
	 * Push all needed updates to the queue for processing.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	private static function update() {
		$current_version = get_option( 'eaccounting_version' );
		foreach ( self::$updates as $version => $update_callbacks ) {

			if ( version_compare( $current_version, $version, '<' ) ) {
				if ( is_array( $update_callbacks ) ) {
					array_map( array( __CLASS__, 'run_update_callback' ), $update_callbacks );
				} else {
					self::run_update_callback( $update_callbacks );
				}
				update_option( 'eaccounting_version', $version );
			}
		}
	}

	/**
	 * Run an update callback.
	 *
	 * @param string $callback Callback name.
	 *
	 * @since 1.0.2
	 */
	public static function run_update_callback( $callback ) {
		include_once EACCOUNTING_ABSPATH . '/includes/ea-update-functions.php';
		if ( is_callable( $callback ) ) {
			eaccounting_maybe_define_constant( 'EACCOUNTING_UPDATING', true );
			call_user_func( $callback );
		}
	}

	/**
	 * Update version to current.
	 *
	 * @param string|null $version New version or null.
	 *
	 * @since 1.1.0
	 */
	public static function update_version( $version = null ) {
		update_option( 'eaccounting_version', is_null( $version ) ? eaccounting()->version : $version );
	}

	/**
	 * Is this a brand new install?
	 *
	 * A brand new install has no version yet. Also treat empty installs as 'new'.
	 *
	 * @return boolean
	 * @since  1.0.2
	 */
	public static function is_new_install() {
		$transaction_count = eaccounting_get_transactions( array( 'count_total' => true ) );

		return is_null( get_option( 'eaccounting_version', null ) ) || ( 0 === $transaction_count );
	}

	/**
	 * Handle background updates.
	 *
	 * @since 1.1.0
	 */
	public static function background_updater() {
		include_once EACCOUNTING_ABSPATH . '/includes/ea-update-functions.php';
		$updaters = get_option( 'eaccounting_background_updater', array() );

		if ( is_array( $updaters ) && ! empty( $updaters ) ) {
			foreach ( $updaters as $updater ) {
				if ( ! is_callable( $updater ) ) {
					continue;
				}
				$updater();
			}
		}
	}
}

Install::init();
