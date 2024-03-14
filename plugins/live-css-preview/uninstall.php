<?php

global $wpdb;

$wpdb->query( $wpdb->prepare( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name="dojodigital_live_css_data"' ) );