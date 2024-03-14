<?php

namespace WunderAuto\Tokenizer;

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 *
 * Adopted to the WunderAuto namespace to avoid conflicts
 */

/**
 * Simple token.
 */
class Token
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var int
     */
    public $type;

    /**
     * @var int
     */
    public $offset;

    /**
     * @param string $value
     * @param int    $type
     * @param int    $offset
     */
    public function __construct($value, $type, $offset)
    {
        $this->value  = $value;
        $this->type   = $type;
        $this->offset = $offset;
    }
}
