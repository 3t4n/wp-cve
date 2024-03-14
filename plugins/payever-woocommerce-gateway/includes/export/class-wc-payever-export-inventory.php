<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Export_Inventory' ) ) {
	return;
}

use Payever\Sdk\Inventory\Base\InventoryIteratorInterface;
use Payever\Sdk\Inventory\Http\RequestEntity\InventoryCreateRequestEntity;

class WC_Payever_Export_Inventory implements InventoryIteratorInterface {

	/** @var ArrayIterator */
	private $products_iterator;

	/** @var int */
	private $position = 0;

	/**
	 * @param array $wc_products
	 */
	public function __construct( $wc_products ) {
		$this->products_iterator = $wc_products;
	}

	/**
	 * @return InventoryCreateRequestEntity
	 */
	public function current() {
		$inventory_request_entity = new InventoryCreateRequestEntity();
		$inventory_request_entity->setSku( $this->products_iterator[ $this->position ]->get_sku() );
		$inventory_request_entity->setStock( $this->products_iterator[ $this->position ]->get_stock_quantity() );

		return $inventory_request_entity;
	}

	/**
	 * @inheritDoc
	 */
	public function next() {
		++$this->position;
	}

	/**
	 * @inheritDoc
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * @inheritDoc
	 */
	public function valid() {
		return isset( $this->products_iterator[ $this->key() ] );
	}

	/**
	 * @inheritDoc
	 */
	public function rewind() {
		$this->position = 0;
	}
}
