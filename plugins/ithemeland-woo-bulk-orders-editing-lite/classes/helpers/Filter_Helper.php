<?php

namespace wobel\classes\helpers;

use wobel\classes\repositories\Search;

class Filter_Helper
{
    public static function get_active_filter_data()
    {
        $search_repository = new Search();
        $last_filter_data = (isset($search_repository->get_current_data()['last_filter_data'])) ? $search_repository->get_current_data()['last_filter_data'] : null;
        if (!is_null($last_filter_data)) {
            $filter_data = $last_filter_data;
        } else {
            $preset = $search_repository->get_preset($search_repository->get_use_always());
            if (!isset($preset['filter_data'])) {
                $preset['filter_data'] = [];
            }
            $filter_data = $preset['filter_data'];
        }

        return $filter_data;
    }
}
