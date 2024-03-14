<?php

namespace wobel\classes\providers\order;

use wobel\classes\providers\column\OrderColumnProvider;
use wobel\classes\repositories\Column;
use wobel\classes\repositories\Order;

class OrderProvider
{
    private static $instance = null;
    private $order_repository;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->order_repository = Order::get_instance();
    }

    public function get_items($items, $columns)
    {
        if (!empty($items)) {
            $column_provider = OrderColumnProvider::get_instance();
            $show_id_column = Column::SHOW_ID_COLUMN;
            foreach ($items as $order_id) {
                $item = $this->order_repository->get_order(intval($order_id));
                include WOBEL_VIEWS_DIR . "data_table/row.php";
            }
        }
    }
}
