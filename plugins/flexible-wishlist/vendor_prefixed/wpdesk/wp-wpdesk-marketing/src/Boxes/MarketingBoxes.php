<?php

namespace FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes;

use FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Api\Client;
use FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer;
use FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FlexibleWishlistVendor\WPDesk\View\Resolver\ChainResolver;
use FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver;
/**
 * Integration class for displaying marketing boxes in a plugin.
 */
class MarketingBoxes
{
    public const VERSION = 'v1';
    /** @var string */
    private $plugin_slug;
    /** @var string */
    private $lang;
    /** @var int */
    private $expiration_time;
    /**
     * @param string $plugin_slug
     * @param string $lang
     * @param int $expiration_time
     */
    public function __construct(string $plugin_slug, string $lang, int $expiration_time = 3600)
    {
        $this->plugin_slug = $plugin_slug;
        $this->lang = $lang;
        $this->expiration_time = $expiration_time;
    }
    /**
     * @return string
     */
    protected function get_cache_name() : string
    {
        return \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Helpers\Cache::create_slug($this->plugin_slug, $this->lang);
    }
    /**
     * @return BoxRenderer
     * @throws \Exception
     */
    public function get_boxes() : \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxRenderer
    {
        try {
            $boxes = \get_transient($this->get_cache_name());
            if (!$boxes || empty($boxes)) {
                $boxes_from_api = $this->get_box_data();
                \set_transient($this->get_cache_name(), $boxes_from_api, $this->expiration_time);
                $boxes = $boxes_from_api;
            }
        } catch (\Exception $e) {
            $boxes = [];
        }
        if (!\is_array($boxes)) {
            $boxes = [];
        }
        return new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxRenderer($boxes);
    }
    /**
     * @return array
     */
    protected function get_box_data() : array
    {
        $lang = $this->lang;
        $client = new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Api\Client($this->plugin_slug);
        $lang = $lang === 'en_US' ? 'en' : $lang;
        $boxes = $client->send_request($lang);
        if (!$boxes) {
            return $client->send_request('en');
        }
        return $boxes;
    }
}
