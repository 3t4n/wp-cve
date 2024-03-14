<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

$id         = isset( $_GET['id'] ) ? sanitize_text_field($_GET['id']) : '';
$rsgd_base  = new raysgrid_Base();
$rsgd_tbls  = new raysgrid_Tables();

$rsgd_base->rsgd_create_settings($id);
$rsgd_tbls->rsgd_insert_update($id);



