<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Client\RestClientInterface;

/**
 * Interface ResourceInterface created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
interface ResourceInterface
{

    const ASSET_RESOURCE  = 'asset';
    const CODE_RESOURCE   = 'code';
    const CONFIG_RESOURCE = 'config';
    const CRUD_RESOURCE   = 'crud';
    const FILE_RESOURCE   = 'file';
    const USER_RESOURCE   = 'user';

    /**
     * Returns the client used by this resource.
     *
     * @return RestClientInterface The client used by this resource.
     */
    public function getClient();
}
