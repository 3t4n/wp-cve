<?php

namespace WpifyWooDeps\Wpify\Model\Relations;

use WpifyWooDeps\Wpify\Model\Interfaces\ModelInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\PostModelInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\PostRepositoryInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\RelationInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\RepositoryInterface;
use WpifyWooDeps\Wpify\Model\Order;
use WpifyWooDeps\Wpify\Model\OrderItemRepository;
class OrderItemsRelation implements RelationInterface
{
    /** @var Order */
    private $model;
    /** @var OrderItemRepository */
    private $repository;
    private $type;
    /**
     * TermRelation constructor.
     *
     * @param PostModelInterface      $model
     * @param PostRepositoryInterface $repository
     */
    public function __construct(ModelInterface $model, RepositoryInterface $repository, string $type = 'line_item')
    {
        $this->model = $model;
        $this->repository = $repository;
        $this->type = $type;
    }
    public function fetch()
    {
        $items = [];
        foreach ($this->model->source_object()->get_items($this->type) as $item) {
            $items[] = $this->repository->get($item);
        }
        return $items;
    }
    public function assign()
    {
    }
}
