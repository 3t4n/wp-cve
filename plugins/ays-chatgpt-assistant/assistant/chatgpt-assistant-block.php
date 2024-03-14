<?php

    function ays_chatgpt_assistant_gutenberg_scripts() {
        global $current_screen;

        if( ! $current_screen ){
            return null;
        }
        if( ! $current_screen->is_block_editor ){
            return null;
        }

        wp_enqueue_script( 'chatgpt-assistant-sweetalert-js', CHATGPT_ASSISTANT_ADMIN_URL . '/js/ays-chatgpt-assistant-sweetalert2.all.min.js', array('jquery'), CHATGPT_ASSISTANT_VERSION, true );
        wp_enqueue_script( 'chatgpt-assistant-block-js', CHATGPT_ASSISTANT_BASE_URL ."/assistant/chatgpt-assistant-block-new.js", array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ), CHATGPT_ASSISTANT_VERSION, true );
        wp_enqueue_style( 'chatgpt-assistant-block-css', CHATGPT_ASSISTANT_BASE_URL ."/assistant/chatgpt-assistant-block-new.css", array(), CHATGPT_ASSISTANT_VERSION, 'all' ); 
    }

    function aysChatgptAssistantVersionCompare($version1, $operator, $version2) {
    
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
    
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
    
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
    
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }

    function checkAndRegister () {
        global $wp_version;
        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.3.12';
        $versionCompare = aysChatgptAssistantVersionCompare($version1, $operator, $version2);
        if($versionCompare){
            add_action( 'enqueue_block_editor_assets', 'ays_chatgpt_assistant_gutenberg_scripts' );
        }
    }

    checkAndRegister();