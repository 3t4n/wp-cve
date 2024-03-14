<?php
namespace CTXFeed\V5\Tax;
interface TaxInterface {
	public function get_tax();
	public function get_taxes( );
	public function merchant_formatted_tax($key );
}