<?php

namespace Hurrytimer;

/**
 * This class is used to store all plugin's constants.
 *
 * @package Hurrytimer
 */
abstract class C
{
    // Modes.
    const MODE_REGULAR = 1;
    const MODE_EVERGREEN = 2;
    const MODE_RECURRING = 3;

    // WC single product positions.
    const WC_POSITION_ABOVE_TITLE = 2.3;
    const WC_POSITION_BELOW_TITLE = 9.3;
    const WC_POSITON_BELOW_REVIEW_RATING = 11.3;
    const WC_POSITION_BELOW_PRICE = 17.3;
    const WC_POSITION_BELOW_ATC_BUTTON = 39.3;
    const WC_POSITION_ABOVE_ATC_BUTTON = 29.3;

    // Actions.
    const ACTION_NONE = 1;
    const ACTION_HIDE = 2;
    const ACTION_REDIRECT = 3;
    const ACTION_CHANGE_STOCK_STATUS = 4;
    const ACTION_HIDE_ADD_TO_CART_BUTTON = 5;
    const ACTION_DISPLAY_MESSAGE = 6;
    const ACTION_EXPIRE_COUPON = 7;

    // WC products selection type.
    const WC_PS_TYPE_ALL = 1;
    const WC_PS_TYPE_INCLUDE_PRODUCTS = 2;
    const WC_PS_TYPE_EXCLUDE_PRODUCTS = 3;
    const WC_PS_TYPE_ALL_CATEGORIES = 4;
    const WC_PS_TYPE_INCLUDE_CATEGORIES = 5;
    const WC_PS_TYPE_EXCLUDE_CATEGORIES = 6;

    // Headline positions.
    const HEADLINE_POSITION_ABOVE_TIMER = 1;
    const HEADLINE_POSITION_BELOW_TIMER = 2;

    // Restart options.
    const RESTART_NONE = 1;
    const RESTART_IMMEDIATELY = 2;
    const RESTART_AFTER_RELOAD = 3;
    const RESTART_AFTER_DURATION = 4;
    
    // Transform text cases.
    const TRANSFORM_NONE = 'none';
    const TRANSFORM_UPPERCASE = 'uppercase';
    const TRANSFORM_LOWERCASE = 'lowercase';

    // WC stock status.
    const WC_IN_STOCK = 'instock';
    const WC_OUT_OF_STOCK = 'outofstock';
    const WC_ON_BACKORDER = 'onbackorder';

    const YES = "yes";
    const NO = "no";

    const RECURRING_END_NEVER = 1;
    const RECURRING_END_OCCURRENCES = 2;
    const RECURRING_END_TIME = 3;

    const RECURRING_MINUTELY = 'minutely';
    const RECURRING_HOURLY = 'hourly';
    const RECURRING_DAILY = 'daily';
    const RECURRING_WEEKLY = 'weekly';
    const RECURRING_MONTHLY = 'monthly';

    const RECURRING_MONTHLY_DAY_OF_MONTH = 'day_of_month';
    const RECURRING_MONTHLY_DAY_OF_WEEK = 'day_of_week';

    const DETECTION_METHOD_COOKIE = 1;
    const DETECTION_METHOD_IP = 2;
    const DETECTION_METHOD_USER_SESSION = 3;

}