<?php

/**
 * Handles integration insight data creation.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Client;

use Dotdigital_WordPress_Vendor\Dotdigital\V3\Models\InsightData;
use Dotdigital_WordPress_Vendor\Dotdigital\V3\Client;
class Dotdigital_Wordpress_Insight_Data
{
    /**
     * The client.
     *
     * @var Client $client
     */
    private $dotdigital_client;
    /**
     * Dotdigital_Wordpress_Insight_Data constructor.
     */
    public function __construct()
    {
        $this->dotdigital_client = new \Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Client(Client::class);
    }
    /**
     * Post integration insight data.
     *
     * @param array $data Integration insight data.
     * @return mixed
     */
    public function post($data)
    {
        $insight_data = new InsightData(array('collectionName' => 'Integrations', 'collectionScope' => 'account', 'collectionType' => 'custom', 'records' => array(array('key' => $data['recordId'], 'json' => $data))));
        return $this->dotdigital_client->get_client()->insightData->import($insight_data);
    }
}
