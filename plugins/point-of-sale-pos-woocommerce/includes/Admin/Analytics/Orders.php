<?php

namespace ZPOS\Admin\Analytics;

class Orders
{
	public function __construct()
	{
		add_filter('woocommerce_analytics_orders_query_args', [$this, 'apply_arg']);
		add_filter('woocommerce_analytics_orders_stats_query_args', [$this, 'apply_arg']);

		add_filter('woocommerce_analytics_clauses_join_orders_subquery', [$this, 'add_join_subquery']);
		add_filter('woocommerce_analytics_clauses_join_orders_stats_total', [
			$this,
			'add_join_subquery',
		]);
		add_filter('woocommerce_analytics_clauses_join_orders_stats_interval', [
			$this,
			'add_join_subquery',
		]);

		add_filter('woocommerce_analytics_clauses_where_orders_subquery', [
			$this,
			'add_where_subquery',
		]);
		add_filter('woocommerce_analytics_clauses_where_orders_stats_total', [
			$this,
			'add_where_subquery',
		]);
		add_filter('woocommerce_analytics_clauses_where_orders_stats_interval', [
			$this,
			'add_where_subquery',
		]);
	}

	public function apply_arg(array $args)
	{
		if (isset($_GET['filter'])) {
			$args['filter'] = sanitize_text_field(wp_unslash($_GET['filter']));
		}

		return $args;
	}

	public function add_join_subquery(array $clauses)
	{
		if (empty($_GET['filter'])) {
			return $clauses;
		}

		$filter = sanitize_text_field(wp_unslash($_GET['filter']));

		if ('pos-online' !== $filter && 'pos-stations' !== $filter) {
			return $clauses;
		}

		global $wpdb;

		$clauses[] = "INNER JOIN {$wpdb->postmeta} order_postmeta ON {$wpdb->prefix}wc_order_stats.order_id = order_postmeta.post_id";

		return $clauses;
	}

	public function add_where_subquery(array $clauses)
	{
		if (empty($_GET['filter'])) {
			return $clauses;
		}

		$filter = sanitize_text_field(wp_unslash($_GET['filter']));

		if ('pos-online' === $filter) {
			$clauses[] =
				"AND order_postmeta.meta_key = '_created_via' AND order_postmeta.meta_value = 'checkout'";
		}

		if ('pos-stations' === $filter) {
			$clauses[] = "AND order_postmeta.meta_key = '_pos_by'";
		}

		return $clauses;
	}
}
