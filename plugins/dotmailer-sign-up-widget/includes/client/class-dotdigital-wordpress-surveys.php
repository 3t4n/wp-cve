<?php

/**
 * Implementation of survey requests.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Client;

use Dotdigital_WordPress_Vendor\Dotdigital\V2\Resources\Surveys;
class Dotdigital_WordPress_Surveys
{
    /**
     * Dotdigital client.
     *
     * @var Dotdigital_WordPress_Client $dotdigital_client
     */
    private $dotdigital_client;
    /**
     * Construct.
     */
    public function __construct()
    {
        $this->dotdigital_client = new \Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Client();
    }
    /**
     * @return array
     */
    public function list_surveys()
    {
        $surveys = $this->dotdigital_client->get_client()->surveys->show();
        return $surveys->getList();
    }
}
