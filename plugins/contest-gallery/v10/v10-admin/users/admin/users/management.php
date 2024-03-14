<?php

$GalleryID = absint($_GET['option_id']);

global $wpdb;

$tablename_contest_gal1ery_options = $wpdb->prefix . "contest_gal1ery_options";

$GalleryName = $wpdb->get_var("SELECT GalleryName FROM $tablenameOptions WHERE id = '$GalleryID'");

include(dirname(__FILE__) . "/../../../nav-menu.php");

    include("management-show-users.php");

