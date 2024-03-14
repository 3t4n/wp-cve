<?php
// Get all Map values

function get_map_values($mapID)
{
    $values = (object) array(
        'id' => $mapID,
        'origin' => get_post_meta($mapID, 'mkgd_origin', true ),
        'destination' => get_post_meta($mapID, 'mkgd_destination', true ),
        'lang' => get_post_meta($mapID, 'mkgd_language', true ),
        'unit_system' => get_post_meta($mapID, 'mkgd_unit_system', true ),
        'width' => get_post_meta($mapID, 'mkgd_width', true ),
        'height' => get_post_meta($mapID, 'mkgd_height', true ),
        'hide_origin' => get_post_meta($mapID, 'mkgd_hide_origin', true ),
        'hide_destination' => get_post_meta($mapID, 'mkgd_hide_destination', true ),
    );

    

    return $values;
}
