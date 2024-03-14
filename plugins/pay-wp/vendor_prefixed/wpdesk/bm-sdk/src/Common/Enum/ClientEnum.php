<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Enum;

abstract class ClientEnum
{
    public const PAYMENT_ROUTE = '/payment';
    public const PAYWAY_LIST_ROUTE = '/paywayList';
    public const GET_REGULATIONS_ROUTE = '/webapi/regulationsGet';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_NOT_CONFIRMED = 'NOTCONFIRMED';
    public const HASH_MD5 = 'md5';
    public const HASH_SHA1 = 'sha1';
    public const HASH_SHA256 = 'sha256';
    public const HASH_SHA512 = 'sha512';
    public const HASH_TYPES = [self::HASH_MD5, self::HASH_SHA1, self::HASH_SHA256, self::HASH_SHA512];
    public const HASH_SEPARATOR = '|';
    public const PATTERN_PAYWAY = '@<!-- PAYWAY FORM BEGIN -->(.*)<!-- PAYWAY FORM END -->@Usi';
    public const PATTERN_XML = '@xml version="1.0" encoding="UTF-8"@';
    public const PATTERN_XML_ERROR = '@<error>(.*)</error>@Usi';
    public const PATTERN_GENERAL_ERROR = '/error(.*)/si';
    public const MESSAGE_ID_LENGTH = 32;
}
