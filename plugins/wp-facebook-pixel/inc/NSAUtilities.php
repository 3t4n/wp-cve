<?php


if( ! function_exists( 'nsau_QuoteString' ) ) {
    /**
     * Adds single quote around a string value.
     * If input is numeric, no quotes are added.
     * 
     * @since 1.0.0
     * 
     * @param double|int|string $value Value to add quotes to if non-numeric.
     * @return double|int|string
     */
    function nsau_QuoteString( $value ) {
        if(is_numeric($value)) { return $value; }
        if(is_array($value)) { return json_encode($value); }
        else { return "'".$value."'"; }
    }
}

if( ! function_exists( 'nsau_AdminNotice' ) ) {
    function nsau_AdminNotice($plugin_name, $title, $message, $domain, $class = '', $isDissmissible = false, $notificationid = 0, $hideOnPage = array()) {
        $screen = get_current_screen();
        $id = (!is_null($screen) ? $screen->id : '');

        if(!in_array($id, $hideOnPage)) {

            if ($isDissmissible) wp_enqueue_script( 'nsa_Notice_Dismissed', plugins_url('/scripts/nsa_Notice_Dismissed.min.js', __FILE__ ), array( 'jquery' ), false, true  );
            wp_enqueue_style('NSANotification', plugins_url('NSANotifications/NSANotification.css', __FILE__), false, false, false);
            wp_enqueue_script('NSANotification', plugins_url('NSANotifications/NSANotification.js', __FILE__), array('jquery'), false, true);
            add_action( 'admin_notices', 
                function() use ($plugin_name, $title, $message, $domain, $class, $isDissmissible, $notificationid) { 
                    $class = "$domain $class".($isDissmissible === true ? ' nsa-dismissible': '');//notice is-dismissible

                    echo('<div class="nsa_notification '.$class.'" data-plugin_id="'.$domain.'" data-notification_id="'.$notificationid.'">
                            <div class="nsa_notice_header">'.__($plugin_name).' - '.__($title, $domain ).'</div>
                            <div class="nsa_notice_header_border"></div>
                            <div class="nsa_notice_message">'.__($message, $domain ).'</div>
                            <!--<a href="javascript: void();" class="snooze_nsa_notice">Snooze</a>-->
                            <a href="javascript: void();" class="dismiss_nsa_notice">Dismiss</a>
                        </div>'); 

                } 
            );
        }
    }
}


if(!function_exists('nsau_GetMetaKeys')) {
    function nsau_GetMetaKeys($post_type) {
        //$cache = get_transient($post_type.'_meta_keys');
        //if($cache) return $cache;

        global $wpdb;
        $query = "
            SELECT DISTINCT($wpdb->postmeta.meta_key) 
            FROM $wpdb->posts 
            LEFT JOIN $wpdb->postmeta 
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
            WHERE $wpdb->posts.post_type = '%s' 
            AND $wpdb->postmeta.meta_key != '' 
            AND $wpdb->postmeta.meta_key NOT RegExp '(^[_].+$)' 
            AND $wpdb->postmeta.meta_key NOT RegExp '(^[(ksp|nsa)].+$)'
        ";
        $meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));
        set_transient($post_type.'_meta_keys', $meta_keys, 60*60*24); //1 day exp
        return $meta_keys;
    }
}


if(!function_exists('nsau_GetSession')) {
    function nsau_GetSession($key) {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];

        } else {
            return false;

        }
    }
}


if ( ! function_exists('nsau_Write_to_Log')) {
    /**
     * Adds to the WordPress debug log
     * @param mixed $log Array, Object or other printable type
     */
    function nsau_Write_to_Log ($title, $log)  {
        $caller = nsau_GetCallingMethod();
        if(is_array($caller)) {
            error_log(sprintf("
nsau_Write_to_Log: %s - 
Caller: %s
Type: %s
Function: %s
Line: @%s
Args: 
%s", $title, $caller['class'], $caller['type'], $caller['function'], $caller['line'], print_r($caller['args'], true)));
        
        } else {
            error_log(sprintf("nsau_Write_to_Log: %s", $title));
            error_log(print_r($caller, true));
        }
        
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
			
        } else {
            error_log( $log );
        }
    }
}


function nsau_GetCallingMethod(){
    $e = new Exception();
    $trace = $e->getTrace();
    //position 0 would be the line that called this function so we ignore it
    $last_call = $trace[2];
    return $last_call;
}


?>