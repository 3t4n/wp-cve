<?php

namespace XCurrency\WpMVC\Database\Eloquent\Relations;

use XCurrency\WpMVC\Database\Eloquent\Model;
abstract class Relation
{
    public Model $related;
    public $foreign_key;
    public $local_key;
    public function __construct($related, $foreign_key, $local_key)
    {
        $this->related = new $related();
        $this->foreign_key = $foreign_key;
        $this->local_key = $local_key;
    }
    public function get_related()
    {
        return $this->related;
    }
}
