<?php

namespace WpifyWoo\Modules\PricesLog;

use WpifyWooDeps\Wpify\Model\Abstracts\AbstractDbTableModel;

class PricesLogModel extends AbstractDbTableModel {
	public $product_id;
	public $regular_price;
	public $sale_price;
	public $created_at;
}
