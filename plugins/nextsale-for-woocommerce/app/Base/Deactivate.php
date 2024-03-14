<?php

namespace App\Base;

class Deactivate
{
    /**
     * Deactivate
     * @return void
     */
    public static function invoke()
    {
        flush_rewrite_rules();
    }
}
