<?php
/**
 * EverAccounting Updates
 *
 * Functions for updating data.
 *
 * @package EverAccounting/Functions
 * @version 1.0.2
 */

defined( 'ABSPATH' ) || exit;

function eaccounting_add_background_updater( $action ) {
	if ( empty( $action ) ) {
		return;
	}
	$updater   = get_option( 'eaccounting_background_updater', array() );
	$updater[] = $action;
	update_option( 'eaccounting_background_updater', $updater );
}

function eaccounting_remove_background_updater( $action ) {
	if ( empty( $action ) ) {
		return;
	}
	$updater = get_option( 'eaccounting_background_updater', array() );
	if ( in_array( $action, $updater, true ) ) {
		unset( $updater[ array_flip( $updater )[ $action ] ] );
		update_option( 'eaccounting_background_updater', $updater );
	}
}

function eaccounting_update_1_0_2() {
	\EverAccounting\Install::create_tables();
	\EverAccounting\Install::create_roles();

	global $wpdb;
	$prefix          = $wpdb->prefix;
	$current_user_id = eaccounting_get_current_user_id();

	$settings = array();
	delete_option( 'eaccounting_settings' );
	$localization  = get_option( 'eaccounting_localisation', array() );
	$currency_code = array_key_exists( 'currency', $localization ) ? $localization['currency'] : 'USD';
	$currency_code = empty( $currency_code ) ? 'USD' : sanitize_text_field( $currency_code );

	$currency = eaccounting_insert_currency(
		array(
			'code' => $currency_code,
			'rate' => 1,
		)
	);

	$settings['financial_year_start']   = '01-01';
	$settings['default_payment_method'] = 'cash';

	if ( ! is_wp_error( $currency ) ) {
		$settings['default_currency'] = $currency->get_code();
	}

	update_option( 'eaccounting_settings', $settings );

	// transfers
	$wpdb->query( "ALTER TABLE {$prefix}ea_transfers DROP COLUMN `updated_at`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transfers ADD `creator_id` INT(11) DEFAULT NULL AFTER `revenue_id`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transfers CHANGE `payment_id` `expense_id` INT(11) NOT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transfers CHANGE `revenue_id` `income_id` INT(11) NOT NULL;" );
	$wpdb->query( $wpdb->prepare( "UPDATE {$prefix}ea_transfers SET creator_id=%d", $current_user_id ) );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transfers CHANGE `created_at` `date_created` DATETIME NULL DEFAULT NULL;" );

	$transfers = $wpdb->get_results( "SELECT * FROM {$prefix}ea_transfers" );
	foreach ( $transfers as $transfer ) {
		$revenue = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}ea_revenues where id=%d", $transfer->income_id ) );
		$expense = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$prefix}ea_payments where id=%d", $transfer->expense_id ) );

		$wpdb->insert(
			$prefix . 'ea_transactions',
			array(
				'type'           => 'income',
				'payment_date'   => $revenue->payment_date,
				'amount'         => $revenue->amount,
				'currency_code'  => $currency_code,
				'currency_rate'  => 1, // protected.
				'account_id'     => $revenue->account_id,
				'invoice_id'     => null,
				'contact_id'     => null,
				'category_id'    => $revenue->category_id,
				'description'    => $revenue->description,
				'payment_method' => $revenue->payment_method,
				'reference'      => $revenue->reference,
				'attachment'     => $revenue->attachment_url,
				'parent_id'      => 0,
				'reconciled'     => 0,
				'creator_id'     => $current_user_id,
				'date_created'   => $revenue->created_at,
			)
		);

		$income_id = $wpdb->insert_id;

		$wpdb->insert(
			$prefix . 'ea_transactions',
			array(
				'type'           => 'expense',
				'payment_date'   => $expense->payment_date,
				'amount'         => $expense->amount,
				'currency_code'  => $currency_code,
				'currency_rate'  => 1, // protected.
				'account_id'     => $expense->account_id,
				'invoice_id'     => null,
				'contact_id'     => null,
				'category_id'    => $expense->category_id,
				'description'    => $expense->description,
				'payment_method' => $expense->payment_method,
				'reference'      => $expense->reference,
				'attachment'     => $expense->attachment_url,
				'parent_id'      => 0,
				'reconciled'     => 0,
				'creator_id'     => $current_user_id,
				'date_created'   => $expense->created_at,
			)
		);

		$expense_id = $wpdb->insert_id;

		$wpdb->update(
			$prefix . 'ea_transfers',
			array(
				'income_id'  => $income_id,
				'expense_id' => $expense_id,
			),
			array( 'id' => $transfer->id )
		);

		$wpdb->delete(
			$prefix . 'ea_revenues',
			array( 'id' => $revenue->id )
		);

		$wpdb->delete(
			$prefix . 'ea_payments',
			array( 'id' => $expense->id )
		);
	}

	$revenues = $wpdb->get_results( "SELECT * FROM {$prefix}ea_revenues order by id asc" );
	foreach ( $revenues as $revenue ) {
		$wpdb->insert(
			$prefix . 'ea_transactions',
			array(
				'type'           => 'income',
				'payment_date'   => $revenue->payment_date,
				'amount'         => $revenue->amount,
				'currency_code'  => $currency_code,
				'currency_rate'  => 1, // protected
				'account_id'     => $revenue->account_id,
				'invoice_id'     => null,
				'contact_id'     => $revenue->contact_id,
				'category_id'    => $revenue->category_id,
				'description'    => $revenue->description,
				'payment_method' => $revenue->payment_method,
				'reference'      => $revenue->reference,
				'attachment'     => $revenue->attachment_url,
				'parent_id'      => 0,
				'reconciled'     => 0,
				'creator_id'     => $current_user_id,
				'date_created'   => $revenue->created_at,
			)
		);

	}

	// expenses.
	$expenses = $wpdb->get_results( "SELECT * FROM {$prefix}ea_payments order by id asc" );
	foreach ( $expenses as $expense ) {
		$wpdb->insert(
			$prefix . 'ea_transactions',
			array(
				'type'           => 'expense',
				'payment_date'   => $expense->payment_date,
				'amount'         => $expense->amount,
				'currency_code'  => $currency_code,
				'currency_rate'  => 1, // protected.
				'account_id'     => $expense->account_id,
				'invoice_id'     => null,
				'contact_id'     => $expense->contact_id,
				'category_id'    => $expense->category_id,
				'description'    => $expense->description,
				'payment_method' => $expense->payment_method,
				'reference'      => $expense->reference,
				'attachment'     => $expense->attachment_url,
				'parent_id'      => 0,
				'reconciled'     => 0,
				'creator_id'     => $current_user_id,
				'date_created'   => $expense->created_at,
			)
		);

	}

	// accounts
	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts DROP COLUMN `updated_at`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts ADD `currency_code` varchar(3) NOT NULL AFTER `opening_balance`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts ADD `creator_id` INT(11) DEFAULT NULL AFTER `bank_address`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts ADD `enabled` tinyint(1) NOT NULL DEFAULT '1' AFTER `bank_address`;" );
	$wpdb->query( $wpdb->prepare( "UPDATE {$prefix}ea_accounts SET creator_id=%d, currency_code=%s ", $current_user_id, $currency_code ) );
	$wpdb->update( "{$prefix}ea_accounts", array( 'enabled' => '1' ), array( 'status' => 'active' ) );
	$wpdb->update( "{$prefix}ea_accounts", array( 'enabled' => '0' ), array( 'status' => 'inactive' ) );
	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts DROP COLUMN `status`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts CHANGE `created_at` `date_created` DATETIME NULL DEFAULT NULL;" );

	// categories
	$wpdb->query( "ALTER TABLE {$prefix}ea_categories DROP COLUMN `updated_at`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_categories ADD `enabled` tinyint(1) NOT NULL DEFAULT '1' AFTER `color`;" );
	$wpdb->update( "{$prefix}ea_categories", array( 'enabled' => '1' ), array( 'status' => 'active' ) );
	$wpdb->update( "{$prefix}ea_categories", array( 'enabled' => '0' ), array( 'status' => 'inactive' ) );
	$wpdb->query( "ALTER TABLE {$prefix}ea_categories DROP COLUMN `status`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_categories CHANGE `created_at` `date_created` DATETIME NULL DEFAULT NULL;" );

	// contacts
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `name` VARCHAR(191) NOT NULL AFTER `user_id`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `fax` VARCHAR(50) DEFAULT NULL AFTER `phone`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `birth_date` date DEFAULT NULL AFTER `phone`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `type` VARCHAR(100) DEFAULT NULL AFTER `note`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `enabled` tinyint(1) NOT NULL DEFAULT '1' AFTER `note`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `creator_id` INT(11) DEFAULT NULL AFTER `note`;" );
	$contacts = $wpdb->get_results( "SELECT * FROM {$prefix}ea_contacts" );

	foreach ( $contacts as $contact ) {
		$types = maybe_unserialize( $contact->types );
		if ( count( $types ) === 1 ) {
			$type = reset( $types );
			$wpdb->update(
				$wpdb->prefix . 'ea_contacts',
				array(
					'type' => $type,
				),
				array( 'id' => $contact->id )
			);
		} else {
			$wpdb->update(
				$wpdb->prefix . 'ea_contacts',
				array(
					'type' => 'customer',
				),
				array( 'id' => $contact->id )
			);

			$data         = (array) $contact;
			$data['type'] = 'vendor';
			unset( $data['types'] );
			unset( $data['id'] );
			$wpdb->insert( $wpdb->prefix . 'ea_contacts', $data );
			if ( ! empty( $wpdb->insert_id ) ) {
				$vendor_id = $wpdb->insert_id;

				$wpdb->update(
					$wpdb->prefix . 'ea_transactions',
					array(
						'contact_id' => $vendor_id,
					),
					array(
						'contact_id' => $contact->id,
						'type'       => 'expense',
					)
				);
			}
		}
	}

	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `currency_code` varchar(3) NOT NULL AFTER `tax_number`;" );

	foreach ( $contacts as $contact ) {
		$name = implode( ' ', array( $contact->first_name, $contact->last_name ) );
		$wpdb->update(
			$wpdb->prefix . 'ea_contacts',
			array(
				'currency_code' => $currency_code,
				'enabled'       => $contact->status === 'active' ? 1 : 0,
				'name'          => $name,
				'creator_id'    => $current_user_id,
			),
			array( 'id' => $contact->id )
		);
	}
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `avatar_url`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `updated_at`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `city`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `state`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `postcode`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `status`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `types`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts CHANGE `created_at` `date_created` DATETIME NULL DEFAULT NULL;" );

	delete_option( 'eaccounting_localisation' );
}

function eaccounting_update_1_1_0() {
	global $wpdb;
	$prefix = $wpdb->prefix;

	$wpdb->query( "ALTER TABLE {$prefix}ea_accounts ADD `thumbnail_id` INT(11) DEFAULT NULL AFTER `bank_address`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_categories ADD INDEX enabled (`enabled`);" );

	// $wpdb->query( "ALTER TABLE {$prefix}ea_contacts CHANGE `attachment` `avatar_id` INT(11) DEFAULT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts CHANGE `tax_number` `vat_number` VARCHAR(50) DEFAULT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `fax`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts DROP COLUMN `note`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `company` VARCHAR(191) NOT NULL AFTER `name`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `website` VARCHAR(191) NOT NULL AFTER `phone`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `street` VARCHAR(191) NOT NULL AFTER `vat_number`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `city` VARCHAR(191) NOT NULL AFTER `street`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `state` VARCHAR(191) NOT NULL AFTER `city`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `postcode` VARCHAR(191) NOT NULL AFTER `state`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD `thumbnail_id` INT(11) DEFAULT NULL AFTER `type`;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD INDEX enabled (`enabled`);" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_contacts ADD INDEX user_id (`user_id`);" );

	$wpdb->query( "ALTER TABLE {$prefix}ea_transactions CHANGE `paid_at` `payment_date` date NOT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transactions CHANGE `invoice_id` `document_id` INT(11) DEFAULT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transactions CHANGE `parent_id` `parent_id` INT(11) DEFAULT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transactions ADD `attachment_id` INT(11) DEFAULT NULL AFTER `reference`;" );
	// $wpdb->query( "ALTER TABLE {$prefix}ea_transactions CHANGE `attachment` `attachment_id` INT(11) DEFAULT NULL;" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transactions ADD INDEX document_id (`document_id`);" );
	$wpdb->query( "ALTER TABLE {$prefix}ea_transactions ADD INDEX category_id (`category_id`);" );

	// update currency table to options
	$currencies = $wpdb->get_results( "SELECT * FROM {$prefix}ea_currencies order by id asc" );

	if ( is_array( $currencies ) && count( $currencies ) ) {
		foreach ( $currencies as $currency ) {
			eaccounting_insert_currency(
				array(
					'name'               => $currency->name,
					'code'               => $currency->code,
					'rate'               => $currency->rate,
					'precision'          => $currency->precision,
					'symbol'             => $currency->symbol,
					'position'           => $currency->position,
					'decimal_separator'  => $currency->decimal_separator,
					'thousand_separator' => $currency->thousand_separator,
					'date_created'       => $currency->date_created,
				)
			);
		}
	}

	// update permissions
	global $wp_roles;

	if ( is_object( $wp_roles ) ) {
		$wp_roles->add_cap( 'ea_manager', 'ea_manage_item' );
		$wp_roles->add_cap( 'ea_manager', 'ea_manage_invoice' );
		$wp_roles->add_cap( 'ea_manager', 'ea_manage_bill' );
		$wp_roles->add_cap( 'ea_accountant', 'ea_manage_item' );
		$wp_roles->add_cap( 'ea_accountant', 'ea_manage_invoice' );
		$wp_roles->add_cap( 'ea_accountant', 'ea_manage_bill' );
		$wp_roles->add_cap( 'administrator', 'ea_manage_item' );
		$wp_roles->add_cap( 'administrator', 'ea_manage_invoice' );
		$wp_roles->add_cap( 'administrator', 'ea_manage_bill' );
	}

	\EverAccounting\Install::install();

	// todo upload transaction files as attachment then update transaction table and delete attachment column
	flush_rewrite_rules();
	eaccounting_add_background_updater( 'eaccounting_update_attachments_1_1_0' );
}

function eaccounting_update_attachments_1_1_0() {
	global $wpdb;
	$prefix      = $wpdb->prefix;
	$attachments = $wpdb->get_results( "SELECT id, attachment url from {$wpdb->prefix}ea_transactions WHERE attachment_id IS NULL AND attachment !='' limit 5" );
	if ( empty( $attachments ) ) {
		eaccounting_remove_background_updater( 'eaccounting_update_attachments_1_1_0' );
		$wpdb->query( "ALTER TABLE {$prefix}ea_transactions DROP COLUMN `attachment`;" );
	}

	$dir = wp_get_upload_dir();

	foreach ( $attachments as $attachment ) {
		$path       = $attachment->url;
		$site_url   = wp_parse_url( $dir['url'] );
		$image_path = wp_parse_url( $path );

		// Force the protocols to match if needed.
		if ( isset( $image_path['scheme'] ) && ( $image_path['scheme'] !== $site_url['scheme'] ) ) {
			$path = str_replace( $image_path['scheme'], $site_url['scheme'], $path );
		}

		if ( 0 === strpos( $path, $dir['baseurl'] . '/' ) ) {
			$path = substr( $path, strlen( $dir['baseurl'] . '/' ) );
		}

		$path      = str_replace( 'axis.byteever.com', 'axis.test', $path );
		$path      = str_replace( $dir['baseurl'], '', $path );
		$full_path = untrailingslashit( $dir['basedir'] ) . '/' . ltrim( $path, '/' );

		if ( ! file_exists( $full_path ) ) {
			continue;
		}
		$attachment_id = eaccounting_file_to_attachment( $full_path );
		if ( $attachment_id && is_numeric( $attachment_id ) ) {
			$wpdb->update( "{$wpdb->prefix}ea_transactions", array( 'attachment_id' => $attachment_id ), array( 'id' => $attachment->id ) );
		}
	}
}
