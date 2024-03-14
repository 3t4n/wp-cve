<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}
class WADA_Constants
{
    const LOG_LEVEL_OFF = 0;
    const LOG_LEVEL_ERROR = 1;
    const LOG_LEVEL_WARNING = 2;
    const LOG_LEVEL_INFO = 3;
    const LOG_LEVEL_DEBUG = 4;

    // Log Entry Types (0 = undefined/general entry)
    const LOGENTRY_INSTALLER = 1;
    const LOGENTRY_PLUGIN = 2;
}