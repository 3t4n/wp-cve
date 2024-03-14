<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Analytics\Ajax;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

Trait Shared
{
    protected function getPreviousPeriodDates($startDate, $period)
    {
		if (!\DateTime::createFromFormat('Y/m/d 00:00:00', $startDate))
		{
			return false;
		}

        $newStartDate = new \DateTime($startDate);
        $newStartDate->sub(new \DateInterval("P{$period}D"));
        $newStartDateStr = $newStartDate->format('Y/m/d 00:00:00');
        
        $period--;
        $newEndDate = clone $newStartDate;
        $newEndDate->add(new \DateInterval("P{$period}D"));
        $newEndDateStr = $newEndDate->format('Y/m/d 23:59:59');

        return [$newStartDateStr, $newEndDateStr];
    }
}