<?php

/**
 * @package pdf-doc-embedder
 */
namespace EDE\Inc\Base;

class Deactivate
{
    public static function ede_deactivate()
    {
        flush_rewrite_rules( true );
    }
}
