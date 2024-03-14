<?php

function automatorwp_register_better_messages_integration() {
    automatorwp_register_integration( 'better_messages', array(
        'label' => 'Better Messages',
        'icon'  => plugin_dir_url( __FILE__ ) . 'assets/icon.png',
    ) );

    require_once('actions/send-message.php');
}

if( function_exists('automatorwp_register_integration') && function_exists('automatorwp_register_action') ){
    automatorwp_register_better_messages_integration();
} else {
    add_action('automatorwp_init', 'automatorwp_register_better_messages_integration', 1);
}
