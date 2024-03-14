<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\TagMap;
use WcMipConnector\Repository\TagMapRepository;

class TagMapManager
{
    /** @var TagMapRepository */
    protected $repository;

    public function __construct()
    {
        $this->repository = new TagMapRepository();
    }

    /**
     * @param array $tagMapIds
     *
     * @return array
     */
    public function findByTagMapIdsIndexedByTagMapId(array $tagMapIds): array
    {
        return $this->repository->findByTagMapIdsIndexedByTagMapId($tagMapIds);
    }

    /**
     * @param array $tagMapIds
     *
     * @return array
     */
    public function findVersionsIndexedByTagMapId(array $tagMapIds): array
    {
        return $this->repository->findVersionsIndexedByTagMapId($tagMapIds);
    }

    /**
     * @param TagMap $tagMap
     *
     * @return bool
     */
    public function save(TagMap $tagMap): bool
    {
        $data = [
            'tag_id' => $tagMap->tagId,
            'tag_shop_id' => $tagMap->shopTagId,
            'version' => $tagMap->version,
            'date_add' => date('Y-m-d H:i:s'),
            'date_update' => null
        ];

        return $this->repository->save($data);
    }

    /**
     * @param int    $tagId
     * @param TagMap $tagMap
     *
     * @return bool
     */
    public function update(int $tagId, TagMap $tagMap): bool
    {
        $data = [
            'tag_shop_id' => $tagId,
            'version' => $tagMap->version,
            'date_update' => date('Y-m-d H:i:s')
        ];

        $where = [
            'tag_id' => $tagMap->tagId
        ];

        return $this->repository->update($data, $where);
    }

    /**
     * @param string $data
     * @return int
     */
    public function delete(string $data): int
    {
        return $this->repository->delete($data);
    }

    /**
     * @param array $ids
     * @return int
     */
    public function deleteByIds(array $ids): int
    {
        return $this->repository->deleteByIds($ids);
    }

    /**
     * @param array $tagIds
     * @return array
     */
    public function findByTagShopIdsIndexedByTagMapIds(array $tagIds): array
    {
        return $this->repository->findByTagShopIdsIndexedByTagMapIds($tagIds);
    }
}