<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\AssetFilter;
use Baqend\SDK\Model\AssetRequest;

/**
 * Class AssetResource created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
class AssetResource extends AbstractRestResource
{

    /**
     * Asynchronously revalidates assets stored for the Speed Kit.
     *
     * @param AssetFilter $assetFilter Filter to apply on the assets before revalidation.
     * @param string $triggeredBy Optional information about what triggered of the revalidation (e.g. 'wordpress').
     * @throws ResponseException If the request was not successful.
     */
    public function revalidate(AssetFilter $assetFilter, $triggeredBy) {
        $assetRequest = new AssetRequest(['filter' => $assetFilter, 'triggeredBy' => $triggeredBy]);
        $request = $this->sendJson('POST', '/asset/revalidate', $assetRequest);
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 202) {
            throw new ResponseException($response);
        }
    }
}
