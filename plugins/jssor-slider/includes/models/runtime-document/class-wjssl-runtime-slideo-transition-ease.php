<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslRuntimeSlideoTransitionEase
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslRuntimeSlideoTransitionEase extends WjsslRuntimeNode
{
    ///// <summary>
    ///// Begin Time
    ///// </summary>
    //public $b;

    ///// <summary>
    ///// Duration
    ///// </summary>
    //public $d;

    public $x;

    public $y;

    /// <summary>
    /// Opacity
    /// </summary>
    public $o;

    /// <summary>
    /// Index
    /// </summary>
    public $i;

    /// <summary>
    /// Rotate
    /// </summary>
    public $r;

    /// <summary>
    /// RotateX
    /// </summary>
    public $rX;

    /// <summary>
    /// RotateY
    /// </summary>
    public $rY;

    /// <summary>
    /// ScaleX
    /// </summary>
    public $sX;

    /// <summary>
    /// ScaleY
    /// </summary>
    public $sY;

    /// <summary>
    /// TranslateZ
    /// </summary>
    public $tZ;

    /// <summary>
    /// SkewX
    /// </summary>
    public $kX;

    /// <summary>
    /// SkewY
    /// </summary>
    public $kY;

    /// <summary>
    /// Clip Horizontal
    /// </summary>
    public $c;

    ///// <summary>
    ///// Ease
    ///// </summary>
    //public $e;

    public function hasValue()
    {
        return
        $this->x !== null
        ||
        $this->y !== null
        ||
        $this->o !== null
        ||
        $this->i !== null
        ||
        $this->r !== null
        ||
        $this->rX !== null
        ||
        $this->rY !== null
        ||
        $this->sX !== null
        ||
        $this->sY !== null
        ||
        $this->tZ !== null
        ||
        $this->kX !== null
        ||
        $this->kX !== null
        ||
        $this->c !== null && $this->c->hasValue()
        ;
    }
}
