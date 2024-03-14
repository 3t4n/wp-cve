<?php

/**
 * Meta data interpreter.
 *
 * @package WPDesk\WooCommerceShipping\Ups
 */
namespace UpsFreeVendor\WPDesk\WooCommerceShipping\Ups\MetaDataInterpreters;

use UpsFreeVendor\WPDesk\View\Renderer\Renderer;
use UpsFreeVendor\WPDesk\WooCommerceShipping\OrderMetaData\SingleFrontOrderMetaDataInterpreter;
/**
 * Can interpret UPS meta data from WooCommerce order shipping on admin and front.
 */
class UpsSingleFrontMetaDataInterpreter implements \UpsFreeVendor\WPDesk\WooCommerceShipping\OrderMetaData\SingleFrontOrderMetaDataInterpreter
{
    /**
     * Key.
     *
     * @var string
     */
    private $key;
    /**
     * Display key.
     *
     * @var string
     */
    private $label;
    /**
     * Template.
     *
     * @var string
     */
    private $template;
    /**
     * Renderer.
     *
     * @var Renderer
     */
    private $renderer;
    /**
     * UpsFrontMetaDataInterpreter constructor.
     *
     * @param string   $key ,
     * @param string   $label .
     * @param string   $template .
     * @param Renderer $renderer .
     */
    public function __construct($key, $label, $template, $renderer)
    {
        $this->key = $key;
        $this->label = $label;
        $this->template = $template;
        $this->renderer = $renderer;
    }
    /**
     * Get meta key on admin order edit page.
     *
     * @param string         $display_key .
     * @param \WC_Meta_Data  $meta .
     * @param \WC_Order_Item $order_item .
     *
     * @return void
     */
    public function display_order_meta_on_front($display_key, $meta, $order_item)
    {
        $data = $meta->get_data();
        $params = array('label' => $this->label, 'value' => $data['value']);
        echo $this->renderer->render($this->template, $params);
    }
    public function is_supported_key_on_front($display_key)
    {
        return $this->key === $display_key;
    }
}
