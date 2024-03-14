<?php

namespace XCurrency\Database\Migrations;

use XCurrency\WpMVC\Contracts\Migration;

class Rounding implements Migration {
    public function more_than_version() {
        return '1.4.6';
    }

    public function execute(): bool {
        global $wpdb;

        $currency = $wpdb->get_results( "select * from {$wpdb->prefix}x_currency limit 1" );

        if ( ! empty( $currency[0]->rounding ) ) {
            return true;
        }

        $wpdb->query( "ALTER TABLE {$wpdb->prefix}x_currency ADD rounding VARCHAR(50) DEFAULT 'disabled' AFTER max_decimal;" );

        return true;
    }
}