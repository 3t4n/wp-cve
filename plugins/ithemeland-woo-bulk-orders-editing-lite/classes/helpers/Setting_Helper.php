<?php


namespace wobef\classes\helpers;


class Setting_Helper
{
    public static function get_count_per_page_items()
    {
        return [
            '10',
            '25',
            '50',
            '75',
            '100',
        ];
    }

    public static function get_arg_order_by($default_sort, $args)
    {
        switch ($default_sort) {
            case 'id':
                $args['orderby'] = 'ID';
                break;
            case 'title':
                $args['orderby'] = 'post_title';
                break;
            case 'post_date':
                $args['orderby'] = 'post_date';
                break;
        }

        return $args;
    }
}
