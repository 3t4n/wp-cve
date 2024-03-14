<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslRuntimeOptions
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslRuntimeOptions extends WjsslRuntimeNode
{
    private static $keys_to_ignore = array (
        'HWA' => true,
        'OtpWaterMark' => true,
        'OtpTitle' => true
        );

    public $HWA;
    public $OtpWaterMark;
    public $OtpTitle;
    public $AutoPlay;
    public $Idle;
    public $AutoPlaySteps;
    public $SlideDuration;
    public $SlideEasing;
    public $FillMode;
    public $LazyLoading;
    public $DragOrientation;
    public $DragRatio;
    public $MinDragOffsetToSlide;
    public $DragSteps;
    public $StartIndex;
    public $Loop;
    public $ArrowKeyNavigation;
    public $PauseOnHover;
    public $UiSearchMode;
    public $PlayOrientation;
    public $SlideWidth;
    public $SlideHeight;
    public $SlideSpacing;
    //public $SlideCols;
    public $Align;
    public $SlideshowOptions;
    public $CaptionSliderOptions;
    public $ArrowNavigatorOptions;
    public $BulletNavigatorOptions;
    public $ThumbnailNavigatorOptions;

    public function prefix_key_with_dollar() {
        return true;
    }

    public function keys_to_ignore() {
        return WjsslRuntimeOptions::$keys_to_ignore;
    }
}
