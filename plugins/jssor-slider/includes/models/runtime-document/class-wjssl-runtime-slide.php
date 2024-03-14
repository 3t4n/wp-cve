<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslRuntimeSlide
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslRuntimeSlide extends WjsslRuntimeNode
{
    public $bgColor;
    //added since 8.9.0, 20180831
    public $bgGradient;

    public $image;
    public $fillMode;
    //added since 8.9.0, 20180831
    public $opacity;
    //added since 8.9.0, 20180831
    public $blur;
    //added since 8.9.0, 20180831
    public$grayscale;

    public $link;

    public $tgt;

    public $thumb;

    public $pDepth;

    public $poX;

    public $poY;

    //added since 6.0.0, 20170911
    public $idle;

    public $layers;

    public $slideoBreakIndex;

    public $normalMotionBegin;

    public $normalMotionEnd;

    public function hasNormalAnimation()
    {
        return $this->normalMotionEnd > 0;
    }

    public $hasActionAnimationLayer;

    public $hasLayer;

    public $has3d;

    public $has3dSpace;

    public $has3dSpaceLayer;

    public $hasTransform;

    public $hasRepeat;

    public function isAnimated() {
        return $this->hasNormalAnimation() || $this->hasActionAnimationLayer;
    }
}
