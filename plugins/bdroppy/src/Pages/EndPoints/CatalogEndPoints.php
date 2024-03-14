<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\Init\Core;

class CatalogEndPoints
{
    protected $core;
    protected $config;
    protected $remote;
    protected $system;
    protected $wc;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->remote = $core->getRemote();
        $this->config = $core->getConfig();
        $this->system = $core->getSystem();
        $this->wc = $core->getWc();
        $this->core->getLoader()->addAction( 'rest_api_init', $this, 'registerRoutes' );

    }


    public function registerRoutes()
    {
        $version = '1';
        $namespace = 'bdroppy' . '/v' . $version;

        $d = register_rest_route( $namespace, '/catalog/current', [
            [
                'methods'               => \WP_REST_Server::READABLE,
                'callback'              => [$this, 'getCurrentCatalog'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );

        $d = register_rest_route( $namespace, '/catalog/create', [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'createCatalog'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );

        $d = register_rest_route( $namespace, '/catalog/import_form_wizard', [
            [
                'methods'               => \WP_REST_Server::READABLE,
                'callback'              => [$this, 'getFormWizard'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );

    }

    public function getFormWizard(\WP_REST_Request $request)
    {
        ob_start();
            require __DIR__ . '/../Template/Catalog/CreateImportng.php';
        $response = ob_get_contents();
        ob_end_clean();
        return new \WP_REST_Response($response, 200 );
    }

    public function getCurrentCatalog(\WP_REST_Request $request )
    {
        ob_start();
        $catalog_id = $this->config->catalog->get('catalog');
        if($catalog_id != 0){
            $catalog = $this->remote->catalog->getbyCatalogId($catalog_id);
        }

        require __DIR__ . '/../Template/Catalog/CurrentCatalog.php';
        $response = ob_get_contents();
        ob_end_clean();
        return new \WP_REST_Response($response, 200 );

    }

    public function createCatalog(\WP_REST_Request $request )
    {
        $this->config->catalog->set($request->get_params());
        $catalog = $request->get_param('catalog');
        if ($this->config->catalog->get('catalog') != $catalog)
        {
            delete_option('bdroppy-cron-change-catalog-insert-try-page');
        }
        if (isset($catalog)  && $catalog != 0)
        {
            $remoteCatalog = $this->remote->catalog->getSingleCatalogById($catalog);

            if(count($remoteCatalog->connections) === 0
                || $remoteCatalog->connections[0]->service !== 'turnkeyPlugin')
            {
                $this->remote->main->connectCatalog($catalog);
            }

            $this->remote->main->setCronJob();
        }

        return  $this->config->catalog->get();
    }

    public function permissionsCheck( $request ) {
        return current_user_can( 'manage_options' );
    }


}