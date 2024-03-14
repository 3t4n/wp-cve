<?php

namespace XCurrency\App\Models;

use XCurrency\WpMVC\Database\Resolver;
use XCurrency\WpMVC\Database\Eloquent\Model;

class Currency extends Model {
    public static function get_table_name():string {
        return 'x_currency';
    }

    public function resolver():Resolver {
        return x_currency_singleton( Resolver::class );
    }
}