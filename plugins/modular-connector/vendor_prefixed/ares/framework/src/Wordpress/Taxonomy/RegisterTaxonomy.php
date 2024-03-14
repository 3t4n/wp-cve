<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\Taxonomy;

/**
 * Class RegisterTaxonomy
 *
 * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
 * @package Ares\Framework\Wordpress\Post
 * @internal
 */
abstract class RegisterTaxonomy implements RegisterCustomTaxonomyInterface
{
    /**
     * Taxonomy name
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var string
     */
    protected string $taxonomy;
    /**
     * Taxonomy post type name
     *
     * @example 'post', customPostType like 'video' or 'portfolio'
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var array
     */
    protected array $postType;
    /**
     * Has parents/childs
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var bool
     */
    protected bool $hierarchical = \true;
    /**
     * Whether to generate and allow a UI for managing
     * terms in this taxonomy in the admin.
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var bool
     */
    protected bool $showUi = \true;
    /**
     * Whether to display a column for the taxonomy on
     * its post type listing screens.
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var bool
     */
    protected bool $showAdminColumn = \true;
    /**
     * Sets the query var key for this taxonomy
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var bool
     */
    protected bool $queryVar = \false;
    /**
     * Whether a taxonomy is intended for use publicly either
     * via the admin interface or by front-end users
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @var bool
     */
    protected bool $public = \false;
    /**
     *  General name for the taxonomy, usually plural.
     *
     * @return string
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#arguments
     */
    protected abstract function label() : string;
    /**
     * Name for one object of this taxonomy.
     *
     * @return string
     */
    protected abstract function singularName() : string;
    /**
     * Triggers the handling of rewrites for this taxonomy, available options:
     * slug, with_front, hierarchical, ep_mask
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters Search 'rewrite' on page
     * @return mixed
     */
    protected function rewrite() : array
    {
        return [];
    }
    /**
     * Use if you want to set some diferent values from the next link
     * which are not declared previously
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#arguments
     * @return mixed
     */
    protected function extraArgs() : array
    {
        return [];
    }
    /**
     * Array of capabilities for this taxonomy
     *
     * @link https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
     * @return mixed
     */
    protected function capabilities() : array
    {
        return ['post'];
    }
    /**
     * An array of labels for this taxonomy
     *
     * @link https://developer.wordpress.org/reference/functions/get_taxonomy_labels/
     * @return array
     */
    protected function labels() : array
    {
        return ['name' => $this->label(), 'singular_name' => $this->singularName()];
    }
    public function postType()
    {
        if (\count($this->postType) === 0) {
            throw new \Exception('Screen is not defined in class: ' . __CLASS__);
        }
        foreach ($this->postType as $key => $screen) {
            if (\class_exists($screen)) {
                $this->postType[$key] = \Modular\ConnectorDependencies\app()->make($screen)->postType();
            }
        }
        return $this->postType;
    }
    /**
     * Return all the values as an array
     *
     * @return array
     */
    private function getTaxonomies() : array
    {
        return ['label' => $this->label(), 'hierarchical' => $this->hierarchical, 'labels' => $this->labels(), 'show_ui' => $this->showUi, 'show_admin_column' => $this->showAdminColumn, 'query_var' => $this->queryVar, 'public' => $this->public, 'rewrite' => $this->rewrite(), 'capabilities' => $this->capabilities()];
    }
    /**
     * Merge post type with the rest of params
     *
     * @return array
     */
    private function completeTaxonomies()
    {
        $tempArray = \array_merge($this->getTaxonomies(), $this->extraArgs());
        return [$this->taxonomy => \array_merge(['post_type' => $this->postType()], $tempArray)];
    }
    /**
     * Register new taxonomy (category)
     *
     * @return $this
     */
    private function registerTaxonomy() : self
    {
        if ($taxonomies = $this->completeTaxonomies()) {
            foreach ($taxonomies as $name => $taxonomy) {
                \register_taxonomy($name, $this->postType, $taxonomy);
            }
        }
        return $this;
    }
    /**
     * Init process for WordPress
     */
    public function register() : void
    {
        $this->registerTaxonomy();
    }
}
