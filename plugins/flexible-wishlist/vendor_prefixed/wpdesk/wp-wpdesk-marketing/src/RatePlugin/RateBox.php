<?php

namespace FlexibleWishlistVendor\WPDesk\Library\Marketing\RatePlugin;

use FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer;
use FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FlexibleWishlistVendor\WPDesk\View\Resolver\ChainResolver;
use FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver;
/**
 * Displays a rating box for the plugin in the WordPress repository.
 */
class RateBox
{
    /** @var Renderer */
    private $renderer;
    public function __construct(?\FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        $this->renderer = $renderer ?? new \FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer(new \FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver(__DIR__ . '/Views/'));
    }
    /**
     * @param string $url
     * @param string $description
     * @param string $header
     * @param string $footer
     *
     * @return string
     */
    public function render(string $url, string $description = '', string $header = '', string $footer = '') : string
    {
        return $this->renderer->render('rate-plugin', ['url' => $url, 'description' => $description, 'header' => $header, 'footer' => $footer]);
    }
}
