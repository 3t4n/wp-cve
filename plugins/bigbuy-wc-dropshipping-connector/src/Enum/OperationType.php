<?php

namespace WcMipConnector\Enum;

defined('ABSPATH') || exit;

class OperationType
{
    public const CREATE = 'LIST';
    public const UPDATE = 'UPDATE';
    public const GET = 'GET';
    public const DELETE = 'DELETE';

    public const SYSTEM_LOG = 'LOG';
    public const SYSTEM_TAXES = 'TAXES';
    public const SYSTEM_LANGUAGES = 'LANGUAGES';
    public const SYSTEM_UPGRADE = 'UPGRADE';
    public const SYSTEM_HEALTHREPORT = 'HEALTHREPORT';
    public const SYSTEM_ACCOUNTINFO = 'ACCOUNTINFO';
    public const SYSTEM_STATUSREPORT = 'STATUSREPORT';
    public const SYSTEM_PERMISSION = 'PERMISSION';
}
