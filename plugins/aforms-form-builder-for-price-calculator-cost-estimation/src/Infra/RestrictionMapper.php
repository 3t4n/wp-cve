<?php

namespace AForms\Infra;

class RestrictionMapper 
{
    const KEY = 'aforms_restricted';

    public function load($postId) 
    {
        return (get_post_meta($postId, self::KEY, true) != "");
    }

    public function save($postId, $restrict) 
    {
        $prev = (get_post_meta($postId, self::KEY, true) != "");
        if ($restrict == $prev) return;

        if ($restrict) {
            add_post_meta($postId, self::KEY, 'yes_restricted', true);
        } else {
            delete_post_meta($postId, self::KEY);
        }
    }
}