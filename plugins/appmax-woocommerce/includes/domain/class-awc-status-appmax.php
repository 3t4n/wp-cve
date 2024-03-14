<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AWC_Status_Appmax
{
    const AWC_AUTHORIZED = 'autorizado';

    const AWC_APPROVED = 'aprovado';

    const AWC_INTEGRATED = 'integrado';

    public static function approved()
    {
        return [
            self::AWC_APPROVED      => self::AWC_APPROVED,
            self::AWC_AUTHORIZED    => self::AWC_AUTHORIZED,
        ];
    }
}