<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
        exit;
}

delete_option( 'la_words_rotator' );

?>