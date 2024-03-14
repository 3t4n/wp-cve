<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View;

use DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\Assets;
use DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes;
use DropshippingXmlFreeVendor\WPDesk\Library\Marketing\RatePlugin\RateBox;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
/**
 * Class MarketingViewAction, marketing view action.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Action\View
 */
class MarketingViewAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    /**
     * @var Renderer
     */
    private $renderer;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\View\Renderer\Renderer $renderer)
    {
        $this->renderer = $renderer;
        $this->config = $config;
    }
    public function hooks()
    {
        \add_action('admin_footer', [$this, 'append_plugin_rate']);
        \add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        \DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\Assets::enqueue_assets();
        \DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\Assets::enqueue_owl_assets();
    }
    public function show()
    {
        $locale = $this->is_pl_lang() ? 'pl_PL' : 'en';
        $boxes = new \DropshippingXmlFreeVendor\WPDesk\Library\Marketing\Boxes\MarketingBoxes($this->config->get_param('plugin.marketing_slug')->get(), $locale);
        $this->renderer->output_render('Marketing/marketing-page', ['boxes' => $boxes, 'is_pl' => $this->is_pl_lang(), 'marketing_slug' => $this->config->get_param('plugin.marketing_slug')->get()]);
    }
    /**
     * Add plugin rate box to settings & support page
     */
    public function append_plugin_rate()
    {
        $rate_box = new \DropshippingXmlFreeVendor\WPDesk\Library\Marketing\RatePlugin\RateBox();
        $this->renderer->output_render('Marketing/rate-box-footer', ['rate_box' => $rate_box]);
    }
    /**
     * @param string $screen_id
     */
    public function admin_enqueue_scripts($screen_id)
    {
        \wp_enqueue_style('marketing-page', $this->config->get_param('assets.css.core_dir_url')->get() . 'marketing/marketing.css', [], $this->config->get_param('plugin.version')->get());
        \wp_enqueue_script('marketing-page', $this->config->get_param('assets.js.core_dir_url')->get() . 'marketing/modal.js', ['jquery'], $this->config->get_param('plugin.version')->get(), \true);
    }
    private function is_pl_lang()
    {
        return \get_locale() === 'pl_PL' || \get_locale() === 'pl';
    }
}
