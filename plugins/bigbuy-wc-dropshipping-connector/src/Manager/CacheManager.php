<?php

declare(strict_types=1);

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Repository\CacheRepository;

class CacheManager
{
    /** @var CacheRepository */
    private $repository;

    public function __construct()
    {
        $this->repository = new CacheRepository();
    }

    public function findOneById(string $itemId): ?string
    {
        return $this->repository->findOneById($itemId);
    }

    public function set(string $itemId, string $itemData, string $namespace): void
    {
        $data = [
            'item_id' => $itemId,
            'item_data' => $itemData,
            'item_expiration_timestamp' => time() + 900,
            'namespace' => $namespace,
        ];

        $this->repository->set($data);
    }

    public function prune(int $limit): void
    {
        $this->repository->prune($limit);
    }
}