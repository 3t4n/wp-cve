<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslRuntimeLayer
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslRuntimeLayer extends WjsslRuntimeNode
{
    public $x;

    public $y;

    public $width;

    public $height;

    public $type;

    public $image;

    public $content;

    public $bgColor;

    //move to bgImageEx since 6.0.0
    // public $bgImage;

    // public $bgRepeat;

    public $slideoTransitionIndex;

    public $slideoMotionControlIndex;

    public $children;

    public $className;

    #region since 8.0.0, 20180305

    public $id;

    #endregion

    public $ptrEvts;

    public $ofX;

    public $ofY;

    #region since 8.0.0, 20180305

    public $pDepth;
    public $poX;
    public $poY;

    #endregion

    public $borderWidth;

    public $borderStyle;

    public $borderColor;

    public $borderRadius;

    /// <summary>
    /// transform origin
    /// </summary>
    public $to;

    /// <summary>
    /// backface visibility
    /// </summary>
    public $backVisibility;

    public $font;

    public $fontEx;

    public $fontSize;

    public $fontWeight;

    public $color;

    // change since 6.0.0, 20170908
    public $italic;

    //move to lineHeightEx since 6.0.0
    // public $lineHeight;

    public $textAlign;

    //move to paddingX, paddingT, paddingY, paddingM since 6.0.0
    // public $padding;

    //move to linkEx since 6.0.0
    // public $link;
    // public $tgt;

    public $blend;

    public $isolation;

    // change since 6.0.0, 20170908
    public $lineHeightEx;

    //added since 6.0.0, 20170908
    public $letterSpacing;

    //added since 6.0.0, 20170908
    public $border;

    //added since 6.0.0, 20170908
    public $linkEx;

    //added since 6.0.0, 20170908
    public $bgImageEx;

    //added since 6.0.0, 20170908
    public $paddingX;

    public $paddingT;

    public $paddingY;

    public $paddingM;

    //added since 8.1.0, 20180516
    public $conditions;

    //added since 8.1.0, 20180629
    public $textShadow;

    //added since 8.3.0, 20180712
    public $boxShadow;

    //added since 8.9.0, 20180831
    public $acclk;

    //added since 8.2.0, 20180711

    public $normalMotionBegin;

    public $normalMotionEnd;

    public $actionMotionBegin;

    public $actionMotionEnd;

    public function hasNormalAnimation() {
        return $this->normalMotionEnd > 0;
    }

    public function hasActionAnimation() {
        return $this->actionMotionEnd > 0;
    }

    public $hasActionAnimationChild;

    public function isAnimated() {
        return $this->hasNormalAnimation() || $this->hasActionAnimation() || $this->hasActionAnimationChild;
    }

    public $hasChild;

    public $has3d;

    public $has3dChild;

    public $has3dSpace;

    public $has3dSpaceChild;

    public $hasTransform;

    public $hasTransformChild;

    public $hasRepeat;

    public $hasRepeatChild;
}
