<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

require_once WP_JSSOR_SLIDER_PATH . 'includes/models/shared/class-wjssl-document-node.php';

/**
 * Class WjsslDesignTimeDocument
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslDesignTimeOptions extends WjsslDocumentNode
{
    public $hwa;

    public $autoPlay;

    public $autoPlayL;

    public $autoPlaySteps;

    public $idle;

    public $slideDuration;

    public $slideEasing;

    public $dragOrientation;

    public $dragRatio;

    public $minDragOffsetToSlide;

    public $dragSteps;

    public $startIndex;

    public $loop;

    public $arrowKeyNavigation;

    public $pauseOnHover;

    public $otpId;

    public $otpCenter;

    public $otpWaterMark;

    public $otpLazyLoading;

    public $otpTitle;

    //public $rspScaleTo;

    //public $rspAdjust;

    //public $rspMin;

    //public $rspMax;

    public $sswPlay;

    public $sswLink;

    public $sswFirst;

    public $sswTrans;
}
