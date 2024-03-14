<?php


namespace BDroppy\Pages\EndPoints;

use BDroppy\Init\Core;
use BDroppy\Models\Queue;

class DashboardEndPoints
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

        $d = register_rest_route( $namespace, '/queues', [
            [
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => [$this, 'getQueues'],
                'permission_callback'   => [$this, 'permissionsCheck'],
            ]
        ] );

    }

    public function getQueues(\WP_REST_Request $request)
    {
        $total = Queue::count();
        $page = $request->get_param('page') ? $request->get_param('page') -1 : 0;
        $pageSize = 20;
        ob_start();
        $queues = Queue::limit($pageSize)
            ->offset($page * $pageSize)
            ->orderBy('create_at','DESC')
            ->getWithMask();

        require __DIR__ . '/../Template/Dashboard/queueItems.php';
        $response = ob_get_contents();
        ob_end_clean();
        return new \WP_REST_Response([
            'queues' => $queues,
            'view' => $response,
            'total' => $total,
            'pageSize' => $pageSize,
            'currentPage' => $page + 1,
            'hasMorePage' => ($page * $pageSize) < $total,
        ], 200 );
    }

    public function permissionsCheck( $request ) {
        return current_user_can( 'manage_options' );
    }


}