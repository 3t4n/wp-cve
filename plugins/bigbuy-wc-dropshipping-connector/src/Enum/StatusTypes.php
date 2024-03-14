<?php

namespace WcMipConnector\Enum;

defined('ABSPATH') || exit;

class StatusTypes
{
    public const HTTP_OK = 200;
    public const HTTP_TOO_MANY_REQUESTS = 429;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_CONFLICT = 409;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const INVALID_REQUEST_CODE = 600;
}