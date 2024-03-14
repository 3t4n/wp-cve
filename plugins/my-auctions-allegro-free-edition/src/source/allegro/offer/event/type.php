<?php
declare(strict_types=1);

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Source_Allegro_Offer_Event_Type extends GJMAA_Source
{
    public const OFFER_ACTIVATED = 'OFFER_ACTIVATED';
    public const OFFER_STOCK_CHANGED = 'OFFER_STOCK_CHANGED';
    public const OFFER_PRICE_CHANGED = 'OFFER_PRICE_CHANGED';
    public const OFFER_ENDED = 'OFFER_ENDED';
    public const OFFER_ARCHIVED = 'OFFER_ARCHIVED';
    public const OFFER_CHANGED = 'OFFER_CHANGED';

    public function getOptions($param = null)
    {
        return [
            self::OFFER_ACTIVATED => __('Offer Activated', GJMAA_TEXT_DOMAIN),
            self::OFFER_STOCK_CHANGED => __('Offer Stock Changed', GJMAA_TEXT_DOMAIN),
            self::OFFER_PRICE_CHANGED => __('Offer Price Changed', GJMAA_TEXT_DOMAIN),
            self::OFFER_ENDED => __('Offer ended', GJMAA_TEXT_DOMAIN),
            self::OFFER_ARCHIVED => __('Offer archived', GJMAA_TEXT_DOMAIN),
            self::OFFER_CHANGED => __('Offer changed', GJMAA_TEXT_DOMAIN)
        ];
    }
}