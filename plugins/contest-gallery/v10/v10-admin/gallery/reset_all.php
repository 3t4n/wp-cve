<?php

global $wpdb;

$tablename = $wpdb->prefix . "contest_gal1ery";

$GalleryID = absint($_GET['option_id']);

$wpdb->update(
    "$tablename",
    array('Informed' => '0'),
    array(
        'Informed' => '1','GalleryID' => $GalleryID
    ),
    array('%d'),
    array('%d','%d')
);




?>