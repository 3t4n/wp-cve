<?php

namespace ZPOS\Structure;

use ZPOS\Model\Product;

trait ProductIds
{
	public function get_all_ids($request)
	{
		$args = [
			'post_type' => $this->post_type,
			'post_status' => ['publish', 'trash'],
			'posts_per_page' => -1,
			'fields' => 'ids',
		];

		if (isset($request['updated_at_min'])) {
			$args['date_query']['updated_at_min'] = [
				'column' => 'post_modified',
				'after' => $request['updated_at_min'],
				'inclusive' => false,
			];
		}

		$query = new \WP_Query($args);

		$ids = $query->posts;

		// get all deleted products after updated_at_min
		if (isset($request['updated_at_min'])) {
			$lastSync = strtotime($request['updated_at_min']);
			$year = $lastSyncYear = +date('Y', $lastSync);
			$month = $lastSyncMonth = +date('n', $lastSync);
			$lastSyncDay = +date('j', $lastSync);

			/*
			 * loop for years
			 * from updated_at_min year to current year
			 */
			while ($year <= +date('Y')) {
				/*
				 * loop for months
				 * year === updated_at_min year === current year : from updated_at_min month to current month
				 * year === updated_at_min year : from updated_at_min month to 12
				 * from 1 to 12
				 */
				while (($year < +date('Y') && $month <= 12) || $month <= +date('n')) {
					// deleted products for $year and $month
					$daysIds = get_option(Product::getOptionName($this->post_type, $year, $month), []);
					$daysIds = array_filter(
						$daysIds,
						function ($dataDay) use ($year, $lastSyncYear, $month, $lastSyncMonth, $lastSyncDay) {
							// dont apply deleted products from updated_at_min month but before updated_at_min day
							return !(
								$year === $lastSyncYear &&
								$month === $lastSyncMonth &&
								$dataDay < $lastSyncDay
							);
						},
						ARRAY_FILTER_USE_KEY
					);
					$ids = array_reduce($daysIds, 'array_merge', $ids);
					$month++;
				}
				$month = 1;
				$year++;
			}
		}

		$ids = array_values(array_unique($ids));

		return rest_ensure_response($ids);
	}
}
