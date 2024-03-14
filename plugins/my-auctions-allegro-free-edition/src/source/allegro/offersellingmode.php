<?php
declare(strict_types=1);

class GJMAA_Source_Allegro_Offersellingmode extends GJMAA_Source
{
    public const BUY_NOW = 'BUY_NOW';

    public const ADVERTISEMENT = 'ADVERTISEMENT';

    public const AUCTION = 'AUCTION';

    public function getOptions($param = null)
    {
        return [
            self::ADVERTISEMENT => __('Advertisement', GJMAA_TEXT_DOMAIN),
            self::AUCTION       => __('Auction', GJMAA_TEXT_DOMAIN),
            self::BUY_NOW       => __('Buy now', GJMAA_TEXT_DOMAIN)
        ];
    }
}