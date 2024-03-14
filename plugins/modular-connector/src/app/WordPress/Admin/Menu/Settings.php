<?php

namespace Modular\Connector\WordPress\Admin\Menu;

use Modular\Connector\Facades\WhiteLabel;
use Modular\Connector\Helper\OauthClient;
use Modular\ConnectorDependencies\Ares\Framework\Wordpress\Admin\Menu;
use function Modular\ConnectorDependencies\request;

class Settings extends Menu
{
    /**
     * The slug name to refer to this menu by. Should be unique for this
     * menu page and only include lowercase alphanumeric,
     * dashes, and underscores characters to be
     * compatible with sanitize_key().
     *
     * @var string
     */
    public string $slug = 'modular-connector';

    /**
     * Type of Menu Settings
     *
     * Allowed values: 'menu', 'submenu', 'options', 'management', 'theme', 'plugin', 'users', 'pages',
     *   'comments', 'dashboard', 'links', 'media', 'posts'
     *
     * @var string|null
     */
    public string $type = 'management';

    /**
     * Indicate the final method
     * for a specific request method
     *
     * ['post' => 'method']
     *
     * @var array
     */
    protected array $methods = [
        'post' => 'postConnection'
    ];

    /**
     * The text to be used for the menu.
     *
     * @return  string
     */
    public function menuTitle(): string
    {
        $data = WhiteLabel::getWhiteLabeledData();

        return sprintf(__('%s - Connection manager', 'modular-connector'), $data['Name'] ?? 'Modular');
    }

    /**
     * The text to be displayed in the title tags of
     * the page when the menu is selected.
     *
     * @return string
     */
    public function pageTitle(): string
    {
        return $this->menuTitle();
    }

    /**
     * The function to be called to output the content for this page.
     *
     * @return void
     */
    public function render(): void
    {
        $title = $this->pageTitle();

        $connections = OauthClient::getClients();
        $connection = OauthClient::getClient();

        $tags = [
            'h1' => [],
            'h2' => [],
            'strong' => [],
            'p' => [
                'id' => [],
                'class' => []
            ],
            'a' => [],
            'li' => [],
            'ol' => [],
            'th' => [
                'scope' => []
            ],
            'tr' => [],
            'table' => [
                'class' => [],
                'role' => []
            ],
            'h3' => [],
            'label' => [
                'for' => []
            ],
            'td' => [],
            'tbody' => [],
            'button' => [
                'type' => [],
                'id' => [],
                'class' => [],
            ],
            'form' => [
                'method' => [],
                'class' => [],
                'role' => []
            ],
            'input' => [
                'type' => [],
                'class' => [],
                'name' => [],
                'value' => [],
                'required' => [],
                'placeholder' => []
            ],
            'div' => [
                'style' => [],
                'class' => []
            ]
        ];

        $view = $this->view('settings', compact('title', 'connections', 'connection'))
            ->render();

        echo wp_kses($view, $tags);
    }

    /**
     * The function to save connection data
     *
     * @return void
     */
    public function postConnection(): void
    {
        $request = request();

        if (!wp_verify_nonce($request->get('_wpnonce'), '_modular_connector_connection')) {
            wp_nonce_ays('_modular_connector_connection');
        }

        $clientId = $request->get('client_id');

        // TODO allow multiple connection
        $client = OauthClient::mapClient([]);

        $client->setClientId($clientId)
            ->setClientSecret($request->get('client_secret'))
            ->save();

        $this->render();
    }
}
