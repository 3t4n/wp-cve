<?php

namespace Reamaze\API;

class Category extends APIResource {
    public static function path() {
        return "/" . self::$API_VERSION . "/categories";
    }
}
