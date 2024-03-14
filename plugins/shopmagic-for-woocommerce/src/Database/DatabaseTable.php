<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Database;

final class DatabaseTable {

	public static function subscribers(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_marketing_lists';
	}

	public static function optin_email(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_optin_email';
	}

	public static function automation_outcome(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_automation_outcome';
	}

	public static function guest(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_guest';
	}

	public static function guest_meta(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_guest_meta';
	}

	public static function outcome_logs(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_automation_outcome_logs';
	}

	public static function tracked_emails(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_tracked_emails';
	}

	public static function tracked_emails_clicks(): string {
		global $wpdb;

		return $wpdb->prefix . 'shopmagic_tracked_emails_clicks';
	}

}
