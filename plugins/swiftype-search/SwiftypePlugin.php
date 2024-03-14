<?php

namespace Swiftype\SiteSearch\Wordpress;

use Swiftype\SiteSearch\Wordpress\Config\Config as PluginConfig;
use Elastic\SiteSearch\Client\ClientBuilder;
use Elastic\OpenApi\Codegen\Exception\ClientException;
use Elastic\OpenApi\Codegen\Exception\AuthenticationException;

/**
 * The Site Search Wordpress Plugin
 *
 * This class encapsulates all of the Swiftype Search plugin's functionality.
 *
 * @author Matt Riley <mriley@swiftype.com>, Quin Hoxie <qhoxie@swiftype.com>, Aurelien Foucret <aurelien.foucret@elastic.co>
 */
class SwiftypePlugin
{
    /**
     * List of sub-components that will be initialized by the plugins.
     * @var array
     */
    private $components = [
        \Swiftype\SiteSearch\Wordpress\Engine\Manager::class,
        \Swiftype\SiteSearch\Wordpress\Search\PostSearch::class,
        \Swiftype\SiteSearch\Wordpress\Search\Widget::class,
        \Swiftype\SiteSearch\Wordpress\Document\Indexer::class,
        \Swiftype\SiteSearch\Wordpress\Admin\Action::class,
        \Swiftype\SiteSearch\Wordpress\Admin\Page::class,
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->registerComponents();

        \add_action('swiftype_config_loaded', [$this, 'initClient']);
        \add_action('init', [$this, 'initConfig']);
    }

    /**
     * Instantiate all sub-components required by the plugin.
     *
     */
    private function registerComponents()
    {
        foreach ($this->components as $component) {
            new $component();
        }
    }

    /**
     * Instantiate plugin config.
     */
    public function initConfig()
    {
        \do_action('swiftype_config_loaded', new PluginConfig());
    }

    /**
     * Build a new client from the config.
     *
     * @param PluginConfig $config Configuration.
     */
    public function initClient(PluginConfig $config)
    {
        $client = null;
        $apiKey = $config->getApiKey();

        if ($apiKey && strlen($apiKey) > 0) {
            $client = $this->getClient($apiKey);
            try {
                $client->listEngines();
                \do_action('swiftype_client_loaded', $client);
            } catch (AuthenticationException $e) {
                $client = null;
            } catch (ClientException $e) {
                $client = null;
                \do_action('swiftype_admin_error', $e);
            }
        }
    }

    /**
     * Get client by API key.
     *
     * @param string $apiKey API Key.
     *
     * @return Client
     *
     */
    private function getClient($apiKey)
    {
        $integrationName = sprintf("%s:%s", "wordpress-site-search", SWIFTYPE_VERSION);

        return ClientBuilder::create($apiKey)->setIntegration($integrationName)->build();
    }
}
