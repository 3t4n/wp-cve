<?php

namespace WpifyWooDeps\Wpify\Model\Relations;

use WpifyWooDeps\Wpify\Model\Interfaces\PostRepositoryInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\RelationInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\TermModelInterface;
class MenuItemsRelation implements RelationInterface
{
    /** @var TermModelInterface */
    private $model;
    /** @var string */
    private $key;
    /** @var PostRepositoryInterface */
    private $post_repository;
    /**
     * TermPostsRelation constructor.
     *
     * @param TermModelInterface      $model
     * @param string                  $key
     * @param PostRepositoryInterface $post_repository
     */
    public function __construct(TermModelInterface $model, PostRepositoryInterface $post_repository)
    {
        $this->model = $model;
        $this->post_repository = $post_repository;
    }
    public function fetch()
    {
        $menu_items = wp_get_nav_menu_items($this->model->id);
        $items = [];
        foreach ($menu_items as $menu_item) {
            $items[] = $this->post_repository->get($menu_item);
        }
        return $this->sort_items($items);
    }
    public function sort_items($items, $parent_id = 0)
    {
        $result = array();
        foreach ($items as $item) {
            if ($item->menu_item_parent == $parent_id) {
                $item->item_children = $this->sort_items($items, $item->id);
                $result[$item->id] = $item;
            }
        }
        return $result;
    }
    public function assign()
    {
        // TODO
    }
}
