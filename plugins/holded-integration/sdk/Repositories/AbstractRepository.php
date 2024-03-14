<?php

declare(strict_types=1);

namespace Holded\SDK\Repositories;

use Holded\SDK\Services\HTTP\Client;

abstract class AbstractRepository
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
