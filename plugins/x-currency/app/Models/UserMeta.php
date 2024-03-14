<?php

namespace XCurrency\App\Models;

use XCurrency\WpMVC\Database\Eloquent\Model;
use XCurrency\WpMVC\Database\Resolver;

class UserMeta extends Model {
    public static function get_table_name():string {
        return 'usermeta';
    }

    public function resolver():Resolver {
        return x_currency_singleton( Resolver::class );
    }
}