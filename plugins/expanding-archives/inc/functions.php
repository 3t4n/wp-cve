<?php
/**
 * Plugin Functions
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 */

/**
 * Get Months
 *
 * @since 1.0.5
 * @return array
 */
function expanding_archives_get_months(): array
{
    return (new \Ashleyfae\ExpandingArchives\Helpers\DateQuery())->queryPeriods();
}
