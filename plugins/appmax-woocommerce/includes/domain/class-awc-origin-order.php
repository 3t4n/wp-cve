<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Origin_Order
{
    const AWC_API = 'API';

    const AWC_SITE = 'Site';

    const AWC_RECUPERATION = 'Recuperação';

    const AWC_TEAM_PRODUCER = 'Equipe Parceiro';

    const AWC_CALL_CENTER = 'Call Center';

    const AWC_NONE = null;

    public static function callCenter()
    {
        return [
            self::AWC_RECUPERATION  => self::AWC_RECUPERATION,
            self::AWC_TEAM_PRODUCER => self::AWC_TEAM_PRODUCER,
            self::AWC_CALL_CENTER   => self::AWC_CALL_CENTER
        ];
    }
}