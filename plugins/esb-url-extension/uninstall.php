<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

    //delete settings for this plugin
    delete_option( 'esb_eu_settings' );

    //delete plugin version option
    delete_option( 'esb_eu_set_option' );
?>