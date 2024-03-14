<?php

namespace WcMipConnector\View\Module\ShippingService;

defined('ABSPATH') || exit;

use WcMipConnector\Manager\ShippingServiceManager;
use WcMipConnector\View\Assets\Assets;
use WcMipConnector\View\Module\MinimumRequirements\CheckStatus;

class ShippingServiceList extends \WP_List_Table
{
    /** @var Assets  */
    protected $assets;
    /** @var CheckStatus  */
    protected $checkStatus;
    /** @var ShippingServiceManager  */
    protected $shippingServiceManager;

    public function __construct()
    {
        $this->assets = new Assets();
        $this->checkStatus = new CheckStatus();
        $this->shippingServiceManager = new ShippingServiceManager();
        parent::__construct();
    }

    public function loadShippingServices(): void
    {
        $this->prepare_items();
        $this->display();
    }

    /**
     * @return array
     */
    public function get_columns(): array
    {
        return [
            'cb'		=> '<input type="checkbox" />',
            'id'		=> __( 'ID', 'WC-Mipconnector'),
            'name'      => _x( 'Shipping Services', 'column name', 'WC-Mipconnector' ),
            'active'	=> __( 'Active', 'WC-Mipconnector' ),
        ];
    }

    public function no_items(): void
    {
        _e( 'No Shipping Services available.', 'WC-Mipconnector' );
    }

    public function get_hidden_columns(): array
    {
        return [];
    }

    public function prepare_items(): void
    {
        $orderBy = null;
        $order = 'asc';

        $this->process_bulk_action();
        $resultByPage = $this->get_items_per_page( 'name' );
        $pageNumber = $this->get_pagenum();
        $this->_column_headers = [
            $this->get_columns(),
            $this->get_hidden_columns(),
            $this->get_sortable_columns()
        ];

        if (isset($_GET) && \array_key_exists('orderby', $_GET)) {
            $orderBy = sanitize_text_field($_GET['orderby']);
        }

        if (isset($_GET) && \array_key_exists('order', $_GET)) {
            $order = sanitize_text_field($_GET['order']);
        }

        $shippingServices = $this->shippingServiceManager->findAllAndOrderBy($order, $orderBy);
        $totalShippingServices = \count($shippingServices);

        $this->set_pagination_args(
            [
            'total_items' => $totalShippingServices,
            'per_page'    => $resultByPage,
            'total_pages' => ceil($totalShippingServices / $resultByPage)
            ]
        );
        $this->items = \array_slice($shippingServices, (($pageNumber - 1) * $resultByPage), $resultByPage);
    }

    /**
     * @param array|object $item
     * @param string $column_name
     * @return mixed|void
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'active':
                $item[$column_name] ? $this->checkStatus->getCorrectStatus() : $this->checkStatus->getErrorStatus();

                break;
            case 'name':
            case 'id':
                return $item[$column_name];
        }
    }

    /**
     * @param array|object $item
     * @return string
     */
    protected function column_cb($item): string
    {
        return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item['id']);
    }

    /**
     * @return array
     */
    protected function get_sortable_columns(): array
    {
        return [
            'id' => [ 'id', true ],
            'name'=> ['name', true],
            'active'=> ['active', true]
        ];
    }

    /**
     * @return array
     */
    public function get_bulk_actions(): array
    {
        return [
            'bulk-disable' => __('Disable', 'WC-Mipconnector'),
            'bulk-enable' => __('Enable', 'WC-Mipconnector'),
        ];
    }

    public function process_bulk_action(): void
    {
        $action = $this->current_action();

        if (!isset($_REQUEST['id']) || !$action) {
            return;
        }

        foreach ($_REQUEST['id'] as $shippingServiceId) {
            if ($action === 'bulk-enable') {
                $this->shippingServiceManager->enable((int)$shippingServiceId);

                continue;
            }

            $this->shippingServiceManager->disable((int)$shippingServiceId);
        }
    }
}
