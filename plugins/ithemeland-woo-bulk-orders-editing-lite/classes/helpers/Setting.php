<?php


namespace wobel\classes\helpers;


class Setting
{
    public static function get_arg_order_by($default_sort, $args)
    {
        switch ($default_sort) {
            case 'ID':
            case 'id':
                $args['orderby'] = 'ID';
                break;
            case 'post_date':
                $args['orderby'] = 'post_date';
                break;
        }

        return $args;
    }
}
