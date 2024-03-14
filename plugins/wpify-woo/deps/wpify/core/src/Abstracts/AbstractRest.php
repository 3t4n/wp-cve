<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WP_REST_Controller;
use WpifyWooDeps\Wpify\Core\Traits\ComponentTrait;
/**
 * @package Wpify\Core
 * @property AbstractPlugin $plugin
 */
abstract class AbstractRest extends WP_REST_Controller
{
    use ComponentTrait;
}
