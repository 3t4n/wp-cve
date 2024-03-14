<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use Exception;
use WP_Error;
use WP_Term;
use WpifyWooDeps\Wpify\Core\Interfaces\CustomFieldsFactoryInterface;
use WpifyWooDeps\Wpify\Core\Interfaces\TermModelInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractTermModel extends AbstractComponent implements TermModelInterface
{
    /**
     * Disable auto init by default
     * @var bool
     */
    protected $auto_init = \false;
    private $term;
    private $taxonomy;
    private $id;
    private $name;
    /**
     * @param int    $term
     * @param string $taxonomy
     * @param null   $filter
     */
    public function __construct($term, $taxonomy, $filter = null)
    {
        $this->taxonomy = $taxonomy;
        if (!empty($term) && !empty($taxonomy)) {
            $this->term = get_term($term, $taxonomy->get_name(), null, $filter);
        }
    }
    /**
     * Get single term
     * @return array|WP_Error|WP_Term|null
     */
    public function get_term()
    {
        return $this->term;
    }
    /**
     * Get term name
     * @return string|null
     */
    public function get_name()
    {
        if ($this->name) {
            return $this->name;
        }
        return $this->term->name ?? null;
    }
    /**
     * Get term slug
     * @return string|null
     */
    public function get_slug()
    {
        return $this->term->slug ?? null;
    }
    /**
     * Get custom field value
     *
     * @param $field
     *
     * @return mixed
     * @throws Exception
     */
    public function get_custom_field($field)
    {
        $factory = $this->get_custom_fields_factory();
        if (!$factory) {
            throw new Exception(__('You need to set custom fields factory to register and retrieve custom fields', 'wpify'));
        }
        return $factory->get_field($this->get_id(), $field);
    }
    /**
     * @return CustomFieldsFactoryInterface|false
     */
    private function get_custom_fields_factory()
    {
        return $this->taxonomy->get_custom_fields_factory();
    }
    /**
     * Get term ID
     * @return int|null
     */
    public function get_id()
    {
        if ($this->id) {
            return $this->id;
        }
        return $this->term->term_id ?? null;
    }
    /**
     * Get custom field value
     *
     * @param $field
     * @param $value
     *
     * @return mixed
     * @throws Exception
     */
    public function save_custom_field($field, $value)
    {
        $factory = $this->get_custom_fields_factory();
        if (!$factory) {
            throw new Exception(__('You need to set custom fields factory to register and save custom fields', 'wpify'));
        }
        return $factory->save_field($this->get_id(), $field, $value);
    }
    /**
     * @param mixed $name
     */
    public function set_name(string $name) : void
    {
        $this->name = $name;
    }
    /**
     * @param mixed $id
     */
    public function set_id($id) : void
    {
        $this->id = $id;
    }
}
