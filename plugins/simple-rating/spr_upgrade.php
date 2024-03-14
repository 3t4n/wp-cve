<?php

function upgrade()
{
    $version=get_current_version();
    $verion=comapre_versions($version, '1.3');
    if ($verion=="1")
    {
        update_option('spr_version', '4');
        global $wpdb;
        $query="ALTER TABLE `".$wpdb->prefix."spr_votes`
        CHANGE COLUMN `user_id` `user_id` TINYTEXT NULL COLLATE 'utf8_unicode_ci' AFTER `post_id`;";
        $wpdb->query($query);
    }
    $verion=comapre_versions($version, '1.4');
    if ($verion=="1")
    {
        update_option('spr_version', '1.4');
    }
}

function get_current_version()
{
    return get_option('spr_version', '1.4');
}

function comapre_versions($current, $new)
{
    $current=explode(".", $current);
    if (count($current)==2)
    {
        $current[2]=0;
    }
    $new=explode(".", $new);
    if (count($new)==2)
    {
        $new[2]=0;
    }
    if ($current[0]==$new[0])
    {
        if ($current[1]==$new[1])
        {
            if ($current[2]==$new[2])
            {
                return "-1";
            }
            else if ($current[2]<$new[2])
            {
                return "1";
            }
            else
            {
                return "0";
            }
        }
        else if ($current[1]<$new[1])
        {
            return "1";
        }
        else
        {
            return "0";
        }
    }
    else if ($current[0]<$new[0])
    {
        return "1";
    }
    else
    {
        return "0";
    }
}

?>