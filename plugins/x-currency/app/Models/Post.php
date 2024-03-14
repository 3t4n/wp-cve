<?php

namespace XCurrency\App\Models;

use XCurrency\WpMVC\Database\Eloquent\Model;
use XCurrency\WpMVC\Database\Eloquent\Relations\HasMany;
use XCurrency\WpMVC\Database\Resolver;

class Post extends Model {
    public static function get_table_name():string {
        return 'posts';
    }

    public function meta(): HasMany {
        return $this->has_many( PostMeta::class, 'post_id', 'ID' );
    }

    public function resolver():Resolver {
        return x_currency_singleton( Resolver::class );
    }
}