<?php

namespace BDroppy\Services\System;

use BDroppy\Init\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Allows log files to be written to for debugging purposes
 *
 * @class \BrandsSync\Logger
 * @author WooThemes
 */
class System {

    public $info;
    public $language;

    public function __construct(Core $core)
    {
        $this->info = new SystemInfo($core);
        $this->language = new SystemLanguage($core);
    }


}
