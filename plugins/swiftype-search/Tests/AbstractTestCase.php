<?php

namespace Swiftype\SiteSearch\Wordpress\Tests;

use Swiftype\SiteSearch\Wordpress\Config\Config;
use Elastic\SiteSearch\Client\ClientBuilder;

class AbstractTestCase extends \WP_UnitTestCase
{
    /**
     * @var \Elastic\SiteSearch\Client\Client
     */
    protected $client;

    /**
     * Reset the config and register a method to retrieve the client locally when configuration is loaded.
     */
    public function setUp()
    {
        parent::setUp();

        $this->resetConfig();

        \add_action('swiftype_client_loaded', function($client) {
            $this->client = $client;
        });
    }

    /**
     * Delete the engine created for the test if it exists.
     */
    public function tearDown()
    {
        parent::tearDown();
        $client = ClientBuilder::create($this->getTestApiKey())->build();

        try {
            $client->getEngine($this->getTestEngineName());
            $client->deleteEngine($this->getTestEngineName());
        } catch (\Elastic\OpenApi\Codegen\Exception\NotFoundException $e) {
            ;
        }
    }

    /**
     * Read the API key used in test from env variable ST_API_KEY.
     *
     * @return string
     */
    protected function getTestApiKey()
    {
        return getenv('ST_API_KEY');
    }

    /**
     * Read the engine name used in test from env variable ST_ENGINE_NAME.
     * @return string
     */
    protected function getTestEngineName()
    {
        return getenv('ST_ENGINE_NAME');
    }

    /**
     * Load default test configuration.
     */
    protected function loadDefaultConfig()
    {
        $config = new Config();
        $config->setApiKey($this->getTestApiKey());
        $config->setEngineSlug($this->getTestEngineName());

        \do_action('swiftype_config_loaded', $config);
    }

    /**
     * Create the default engine.
     */
    protected function createDefaultEngine()
    {
        \do_action('swiftype_create_engine');
    }

    /**
     * Reset the config
     */
    protected function resetConfig()
    {
        $config = new Config();
        $config->reset();
    }
}
