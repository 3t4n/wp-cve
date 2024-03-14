<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Model;

defined('ABSPATH') || exit;

class SellingChannelConnector
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string
     */
    public $url;

    public function __construct()
    {
        $this->name = '';
        $this->version = '';
        $this->url = '';
    }


}
