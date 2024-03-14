<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslRuntimeSlideoTransitionClip
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslRuntimeSlideoTransitionClip extends WjsslRuntimeNode
{
    /// <summary>
    /// Left
    /// </summary>
    public $x;

    /// <summary>
    /// Right
    /// </summary>
    public $t;

    /// <summary>
    /// Top
    /// </summary>
    public $y;

    /// <summary>
    /// Bottom
    /// </summary>
    public $m;

    public function hasValue()
    {
        return $this->x !== NULL
            ||
            $this->t !== NULL
            ||
            $this->y !== NULL
            ||
            $this->m !== NULL;
    }
}
