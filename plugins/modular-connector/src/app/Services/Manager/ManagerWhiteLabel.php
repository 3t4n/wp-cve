<?php

namespace Modular\Connector\Services\Manager;

use Modular\Connector\Helper\OauthClient;
use function Modular\ConnectorDependencies\base_path;

/**
 * Handles all functionality related to WordPress translations.
 */
class ManagerWhiteLabel
{
    /**
     * @var string
     */
    protected string $key = '_modular_white_label';

    /**
     * @return void
     */
    public function init()
    {
        add_filter('all_plugins', [$this, 'setPluginName'], 10, 2);
        add_filter('plugin_row_meta', [$this, 'setPluginMeta'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'setPluginScripts']);
    }

    /**
     * @return array|null
     */
    public function getWhiteLabeledData()
    {
        $whiteLabeled = get_transient($this->key);

        if (empty($whiteLabeled) && !is_null($whiteLabeled)) {
            try {
                $client = OauthClient::getClient();
                $client->validateOrRenewAccessToken();

                $response = $client->wordpress->getWhiteLabel();

                $this->update($response);
            } catch (\Exception $e) {
                $this->update(null);
            }
        }

        return get_transient($this->key);
    }

    /**
     * @param $payload
     * @return void
     */
    public function update($payload)
    {
        delete_transient($this->key);

        if (!empty($payload) || isset($payload->status) && $payload->status === 'enabled') {
            $payload = $this->mapPayloadIntoWhiteLabelData($payload);
        } else {
            $payload = null;
        }

        set_transient($this->key, $payload, 3 * DAY_IN_SECONDS);
    }

    /**
     * @param $payload
     * @return array
     */
    private function mapPayloadIntoWhiteLabelData($payload)
    {
        return [
            'Name' => $payload->name ?? '',
            'Title' => $payload->name ?? '',
            'Description' => $payload->description ?? '',
            'AuthorURI' => $payload->author_url ?? '',
            'Author' => $payload->author ?? '',
            'AuthorName' => $payload->author_name ?? '',
            'PluginURI' => '',
            'hide' => !empty($payload->hide ?? ''),
            'status' => $payload->status ?? ''
        ];
    }

    /**
     * @param $meta
     * @param $slug
     * @return mixed
     */
    public function setPluginMeta($meta, $slug)
    {
        if ($slug !== MODULAR_CONNECTOR_BASENAME) {
            return $meta;
        }

        if (isset($meta[2])) {
            unset($meta[2]);
        }

        return $meta;
    }

    /**
     * @param array $plugins
     * @return array|mixed
     */
    public function setPluginName(array $plugins)
    {
        $whiteLabel = $this->getWhiteLabeledData();

        if (empty($whiteLabel) || $whiteLabel['status'] === 'disabled') {
            return $plugins;
        }

        $basename = plugin_basename(realpath(base_path('../init.php')));

        if ($whiteLabel['hide']) {
            unset($plugins[$basename]);
            return $plugins;
        }

        $plugins[$basename]['PluginURI'] = '';

        if (!empty($whiteLabel['Name'])) {
            $plugins[$basename]['Name'] = $whiteLabel['Name'];
        }

        if (!empty($whiteLabel['Title'])) {
            $plugins[$basename]['Title'] = $whiteLabel['Name'];
        }

        if (!empty($whiteLabel['Description'])) {
            $plugins[$basename]['Description'] = $whiteLabel['Description'];
        }

        if (!empty($whiteLabel['AuthorURI'])) {
            $plugins[$basename]['AuthorURI'] = $whiteLabel['AuthorURI'];
        }

        if (!empty($whiteLabel['Author'])) {
            $plugins[$basename]['Author'] = $whiteLabel['Author'];
        }

        if (!empty($whiteLabel['AuthorName'])) {
            $plugins[$basename]['AuthorName'] = $whiteLabel['AuthorName'];
        }

        return $plugins;
    }

    /**
     * @param $page
     * @return void
     */
    public function setPluginScripts($page)
    {
        if ($page !== 'plugins.php' && $page !== 'update-core.php') {
            return;
        }

        if (isset($_GET['plugin_status']) && $_GET['plugin_status'] !== 'mustuse') {
            return;
        }

        $whiteLabel = $this->getWhiteLabeledData();

        if ($page === 'update-core.php' && !empty($whiteLabel['Name'])) {
            echo '<script>
				document.addEventListener("DOMContentLoaded", function(event) {
					const checkbox = document.querySelector("input[value=\'modular-connector/init.php\']")

					if(checkbox) {
						checkbox.closest("tr").style.display = "none";
					}
				});
			</script>';
        }
    }
}
