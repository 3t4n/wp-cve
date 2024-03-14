<?php
/*
 * Template Name: Job XML Page
 * Description:
 */

if ( ! defined('ABSPATH')) exit;  // if direct access 

header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
header('Expires: -1');



do_action('job_bm_job_xml', $_REQUEST);