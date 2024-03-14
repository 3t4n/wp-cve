<?php

namespace ZPOS\Admin;

use ZPOS\Admin\Reports\ReportSalesByGateway;
use ZPOS\Admin\Reports\ReportSalesByOrderType;
use ZPOS\Admin\Reports\ReportSalesByUser;

class Reports
{
	public function __construct()
	{
		$this->init();
	}

	private function init()
	{
		add_filter('woocommerce_admin_reports', [$this, 'add_reports']);
	}

	public function add_reports($reports)
	{
		$orders_reports = &$reports['orders']['reports'];
		$orders_reports['sales_by_user'] = [
			'title' => 'Sales by user',
			'hide_title' => true,
			'callback' => [__CLASS__, 'get_user_report']
		];
		$orders_reports['sales_by_pos'] = [
			'title' => 'Sales by order type',
			'hide_title' => true,
			'callback' => [__CLASS__, 'get_order_type_report']
		];
		$orders_reports['sales_by_gateway'] = [
			'title' => 'Sales by payment method',
			'hide_title' => true,
			'callback' => [__CLASS__, 'get_gateway_report']
		];
		return $reports;
	}

	public function get_user_report()
	{
		$reports = new ReportSalesByUser();
		$reports->output_report();
	}

	public function get_order_type_report()
	{
		$reports = new ReportSalesByOrderType();
		$reports->output_report();
	}

	public function get_gateway_report()
	{
		$reports = new ReportSalesByGateway();
		$reports->output_report();
	}
}
