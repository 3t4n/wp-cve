<?php

namespace WpifyWooDeps\Wpify\Model\Abstracts;

use WpifyWooDeps\Wpify\Model\Interfaces\RepositoryInterface;
/**
 * Class AbstractRepository
 *
 * @package Wpify\Model
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @param $object
     *
     * @return mixed
     */
    protected function factory($object)
    {
        $class = $this->model();
        return new $class($this->resolve_object($object), $this);
    }
    /**
     * @param $data
     *
     * @return mixed
     */
    protected abstract function resolve_object($data);
    /**
     * @param array $data
     *
     * @return mixed
     */
    protected function collection_factory(array $data)
    {
        return $data;
    }
}
