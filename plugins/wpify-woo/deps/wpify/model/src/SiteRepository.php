<?php

namespace WpifyWooDeps\Wpify\Model;

use WpifyWooDeps\Wpify\Model\Abstracts\AbstractRepository;
use WpifyWooDeps\Wpify\Model\Exceptions\NotFoundException;
use WpifyWooDeps\Wpify\Model\Interfaces\ModelInterface;
/**
 *
 */
class SiteRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    public function model() : string
    {
        return Site::class;
    }
    protected function resolve_object($data)
    {
        $object = null;
        if (\is_numeric($data)) {
            $object = get_site($data);
        } else {
            if (\is_a($data, 'WpifyWooDeps\\WP_Site')) {
                $object = $data;
            }
        }
        if (!$object) {
            throw new NotFoundException('Site not found');
        }
        return $object;
    }
    public function find(array $args = array())
    {
        $sites = get_sites($args);
        $result = [];
        foreach ($sites as $site) {
            $result[] = $this->get($site);
        }
        return $result;
    }
    /**
     * @return Site[]
     */
    public function all() : array
    {
        return $this->find();
    }
    public function delete(ModelInterface $model)
    {
        wp_delete_site($model->id);
    }
    public function save($model)
    {
        // TODO: Maybe add something here.
        return \true;
    }
    public function get($object = null)
    {
        return !empty($object) ? $this->factory($object) : null;
    }
}
