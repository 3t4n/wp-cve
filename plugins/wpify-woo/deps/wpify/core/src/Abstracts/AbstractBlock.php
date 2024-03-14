<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

/**
 * Class AbstractBlock
 *
 * @package Wpify\Core\Abstracts
 */
abstract class AbstractBlock extends AbstractComponent
{
    /**
     * Registers the block. Called by init method.
     */
    public abstract function register() : void;
    /**
     * Initializes the block
     *
     * @return bool|\Exception|void
     * @throws \ReflectionException Nonexistent base methods.
     */
    public function init()
    {
        add_action('init', array($this, 'register'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        parent::init();
    }
    /**
     * Parses block attributes and replaces empty values with the defaults.
     *
     * @param array|null $block_attributes Array with the current block attributes.
     *
     * @return mixed|void
     */
    public function parse_attributes($block_attributes)
    {
        $attributes = wp_parse_args($block_attributes, $this->default_values());
        $attributes = apply_filters('wpify_block_attributes', $attributes, $this->name());
        $attributes = apply_filters("wpify_block_attributes_{$this->name()}", $attributes, $this->name());
        return $attributes;
    }
    /**
     * Returns default attributes for the block.
     *
     * @return array
     */
    public function default_values() : array
    {
        $attributes = $this->attributes();
        $default_values = array();
        foreach ($attributes as $name => $attribute) {
            if (isset($attribute['default'])) {
                $default_values[$name] = $attribute['default'];
            } else {
                $default_values[$name] = null;
            }
        }
        return $default_values;
    }
    /**
     * Returns attributes definition
     *
     * @return array
     */
    public abstract function attributes() : array;
    /**
     * Returns block name
     *
     * @return string
     */
    public abstract function name() : string;
    /**
     * Enqueues frontend assets if the block is present on the output.
     */
    public function enqueue_frontend_assets()
    {
        if (has_block($this->name()) && \method_exists($this, 'enqueue_assets')) {
            $this->enqueue_assets();
        }
    }
}
