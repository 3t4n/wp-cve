<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\Doctrine\Common\Collections\ArrayCollection;
use WpifyWooDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractTaxonomyRepository extends AbstractComponent implements RepositoryInterface
{
    /** @var AbstractTaxonomy */
    private $taxonomy;
    public function init()
    {
        $this->taxonomy = $this->taxonomy();
        parent::init();
    }
    public abstract function taxonomy();
    /**
     * @return AbstractTaxonomy
     */
    public function get_taxonomy()
    {
        return $this->taxonomy;
    }
    /**
     * @param AbstractTaxonomy $taxonomy
     */
    public function set_taxonomy(AbstractTaxonomy $taxonomy) : void
    {
        $this->taxonomy = $taxonomy;
    }
    /**
     * @return string
     */
    public function get_model()
    {
        return $this->taxonomy->get_model();
    }
    /**
     * @return ArrayCollection&AbstractTermModel[]
     */
    public function all() : ArrayCollection
    {
        $args = array('hide_empty' => \false);
        return $this->find($args);
    }
    /**
     * Find terms
     *
     * @param array $args
     *
     * @return ArrayCollection
     */
    public function find($args = array())
    {
        $defaults = array('taxonomy' => $this->taxonomy->get_name());
        $args = wp_parse_args($args, $defaults);
        $collection = new ArrayCollection();
        $terms = get_terms($args);
        foreach ($terms as $term) {
            $collection->add($this->get($term));
        }
        return $collection;
    }
    public function get($term) : AbstractTermModel
    {
        $model = $this->plugin->create_component($this->taxonomy->model, ['term' => $term, 'taxonomy' => $this->taxonomy]);
        $model->init();
        return $model;
    }
}
