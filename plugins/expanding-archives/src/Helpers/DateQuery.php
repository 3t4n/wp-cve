<?php
/**
 * DateQuery.php
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 */

namespace Ashleyfae\ExpandingArchives\Helpers;

use Ashleyfae\ExpandingArchives\ValueObjects\Month;

class DateQuery
{
    protected array $years = [];

    public function queryPeriods(): array
    {
        global $wpdb;

        $months = get_transient('expanding_archives_months');

        if (false === $months) {
            $query = "
SELECT DISTINCT MONTH(post_date) AS month , YEAR(post_date) AS year, COUNT(id) as post_count
FROM {$wpdb->posts}
WHERE post_status = 'publish'
AND post_date <= now()
AND post_type = 'post'
GROUP BY month, year
ORDER BY post_date DESC";

            /**
             * Filters the query for retrieving date periods.
             *
             * @since 1.1.1
             *
             * @param  string  $query
             */
            $query = apply_filters('expanding_archives_query', $query);

            $months = $wpdb->get_results($query);

            set_transient('expanding_archives_months', $months, DAY_IN_SECONDS);
        }

        return (array) apply_filters('expanding-archives/months', $months);
    }

    public function getPeriods(): array
    {
        foreach ($this->queryPeriods() as $period) {
            if (! array_key_exists((int) $period->year, $this->years)) {
                $this->years[(int) $period->year] = [];
            }

            $this->years[(int) $period->year][] = new Month(
                (int) $period->year,
                (int) $period->month,
                (int) $period->post_count
            );
        }

        return $this->years;
    }

}
