<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

/**
 * Class WjsslRuntimeLayout
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WjsslRuntimeLayout extends WjsslRuntimeNode
{
    public $slideWidth;
    public $slideHeight;
    public $slidesX;
    public $slidesY;
    public $slidesWidth;
    public $slidesHeight;

    public $slidesBorderWidth;
    public $slidesBorderStyle;
    public $slidesBorderColor;

    public $ocWidth;
    public $ocHeight;
    public $ocBgColor;
    public $ocBgImage;
    public $otpCenter;
    public $otpId;
    //public $ldSkin;
    public $dirRTL;
}
