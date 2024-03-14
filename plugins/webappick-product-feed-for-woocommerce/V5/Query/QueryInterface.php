<?php
namespace CTXFeed\V5\Query;
interface QueryInterface {
	public function get_query_arguments();

	public function get_product_types();

	public function get_product_status();

	public function product_ids();
}



