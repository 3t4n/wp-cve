<?php
    if ( ! function_exists( 'xolo_web_activation_logic' ) ) {
        function xolo_web_activation_logic() {
            //if dependent plugin is not active
            if ( is_plugin_active( 'rara-one-click-demo-import/rara-one-click-demo-import.php' ) ) {
                deactivate_plugins( 'rara-one-click-demo-import/rara-one-click-demo-import.php' );
            }

            if ( is_plugin_active( 'theme-demo-import/theme-demo-import.php' ) ) {
                deactivate_plugins( 'theme-demo-import/theme-demo-import.php' );
            }
        }
    }
