<?php

namespace Dotdigital_WordPress_Vendor\Dotdigital\V3\Resources;

use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress_Vendor\Dotdigital\Resources\AbstractResource;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\AbstractSingletonModel;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\InsightData as InsightDataModel;
use Dotdigital_WordPress_Vendor\Http\Client\Exception;
class InsightData extends AbstractResource
{
    public const RESOURCE_BASE = '/insightData/v3';
    /**
     * @param InsightDataModel $insightData
     * @return string
     * @throws \Dotdigital\Exception\ResponseValidationException
     * @throws \Http\Client\Exception
     */
    public function import(InsightDataModel $insightData) : string
    {
        return $this->put(\sprintf('%s/%s', self::RESOURCE_BASE, 'import'), \json_decode(\json_encode($insightData), \true));
    }
    /**
     * @param string $collectionName
     * @param string $recordId
     * @param array $insightData
     *
     * @return string
     * @throws ResponseValidationException
     * @throws Exception
     */
    public function createOrUpdateAccountCollectionRecord(string $collectionName, string $recordId, array $insightData) : string
    {
        return $this->put(\sprintf('%s/%s/%s/%s/', self::RESOURCE_BASE, 'account', $collectionName, $recordId), $insightData);
    }
}
