<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

class Activate
{
    public static function ede_activate()
    {
        flush_rewrite_rules( true );
    }
}
