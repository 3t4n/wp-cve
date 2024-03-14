<?php

    // inserts old postmeta tracking data model into new tracking table
    function wpc_port_postmeta_tracking_to_table () {
        // port old usermeta tracking data to table for future use and better data structure
        global $wpdb;
        $table_name = $wpdb->prefix . 'usermeta';
        $sql = "SELECT * FROM {$table_name} WHERE meta_key = 'wpc-lesson-tracking'";
        $results = $wpdb->get_results( $sql );

        foreach($results as $result) { // loop through rows
            $tracking = unserialize( $result->meta_value );
            if(!empty($tracking)) {
                $completed_tracking = get_user_meta($result->user_id, 'wpc-completed-lesson-tracking', true);
                $completed_tracking = !empty($completed_tracking) ? $completed_tracking : array();
                foreach($tracking as $key => $value){ // loop through each lesson view
                    // check if is array to support legacy data model
                    $viewed_id = is_array($value) ? (int) $value['id'] : $value;
                    $viewed_time = is_array($value) ? $value['time'] : '';
                    $completed_status = 0;
                    $completed_time = '';
                    foreach( $completed_tracking as $completed ) { // loop through each completed view         

                            if( is_array($completed) ) {
                                $completed_id = $completed['id'];
                                $completed_time = $completed['time'];
                            } else {
                                $completed_id = $completed;
                            }

                            if( $completed_id == $viewed_id ){
                                $completed_status = 1;
                                break;
                            } else {
                                $completed_time = '';
                            }

                    }
                    $wpdb->insert(
                        $wpdb->prefix . 'wpc_tracking', array(
                            "user_id"               => $result->user_id,
                            "post_id"               => $viewed_id,
                            "course_id"             => get_post_meta($viewed_id, 'wpc-connected-lesson-to-course', true),
                            "completed"             => $completed_status, 
                            "viewed_timestamp"      => $viewed_time,
                            "completed_timestamp"   => $completed_time
                        ), 
                        array("%d", "%d", "%d", "%d", "%d")
                    );
                } // end foreach
            }
        }
    }

    function wpc_port_postmeta_to_connections_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'postmeta';
        $posts_table = $wpdb->prefix . 'posts';
        $sql = "SELECT {$table_name}.post_id as post_id, {$table_name}.meta_key as meta_key, {$table_name}.meta_value as meta_value, {$posts_table}.menu_order as menu_order, {$posts_table}.post_type as post_type FROM {$table_name} LEFT JOIN {$posts_table} ON {$table_name}.post_id={$posts_table}.ID WHERE meta_key = 'wpc-connected-lesson-to-course' AND post_status != 'NULL' OR meta_key = 'connected_lesson_to_module' AND post_status != 'NULL' OR meta_key = 'wpc-connected-teacher-to-course' AND post_status != 'NULL'";
        $results = $wpdb->get_results($sql);

        foreach($results as $result) {
            if($result->meta_key == 'wpc-connected-lesson-to-course' AND $result->post_type == 'lesson'){
                $lesson_id = get_post_meta($result->post_id, 'wpc-lesson-alias-id', 1);

                // set old clones to drafts
                if( !empty($lesson_id) && $lesson_id != 'none' ) {
                    if ( get_post_status ( $lesson_id ) ) {
                        $args = array(
                          'ID'          => $result->post_id,
                          'post_status' => 'draft',
                        );
                      wp_update_post( $args );
                    }
                }

                $lesson_id = !empty($lesson_id) && $lesson_id != 'none' ? $lesson_id : $result->post_id;

                $wpdb->insert(
                    $wpdb->prefix . 'wpc_connections', array(
                        "post_from"         => $lesson_id,
                        "post_to"           => $result->meta_value,
                        "connection_type"   => 'lesson-to-course',
                        "menu_order"        => $result->menu_order
                    ), 
                    array("%d", "%d", "%s", "%d")
                );
            } elseif($result->meta_key == 'wpc-connected-lesson-to-course' AND $result->post_type == 'wpc-quiz'){
                $wpdb->insert(
                    $wpdb->prefix . 'wpc_connections', array(
                        "post_from"         => $result->post_id,
                        "post_to"           => $result->meta_value, 
                        "connection_type"   => 'quiz-to-course',
                        "menu_order"        => $result->menu_order
                    ), 
                    array("%d", "%d", "%s", "%d")
                );
            } elseif($result->meta_key == 'wpc-connected-teacher-to-course') {
                if(is_array(unserialize($result->meta_value))) {
                    foreach(unserialize($result->meta_value) as $teacher_id) {
                        $wpdb->insert(
                            $wpdb->prefix . 'wpc_connections', array(
                                "post_from"         => (int) $teacher_id,
                                "post_to"           => (int) $result->post_id,
                                "connection_type"   => 'teacher-to-course',
                                "menu_order"        => $result->menu_order
                            ), 
                            array("%d", "%d", "%s", "%d")
                        );
                    }
                } else {
                    $wpdb->insert(
                        $wpdb->prefix . 'wpc_connections', array(
                            "post_from"         => (int) $result->meta_value,
                            "post_to"           => (int) $result->post_id,
                            "connection_type"   => 'teacher-to-course',
                            "menu_order"        => $result->menu_order
                        ), 
                        array("%d", "%d", "%s", "%d")
                    );
                }
            } 

            if($result->post_type == 'wpc-module'){
                // connect module to course
                $course_id = get_post_meta($result->post_id, 'wpc-connected-lesson-to-course', true);
                $wpdb->insert(
                    $wpdb->prefix . 'wpc_connections', array(
                        "post_from"         => (int) $result->post_id,
                        "post_to"           => (int) $course_id,
                        "connection_type"   => 'module-to-course',
                        "menu_order"        => (int) $result->menu_order
                    ), 
                    array("%d", "%d", "%s", "%d")
                );
            }

            if($result->meta_key == 'connected_lesson_to_module' && $result->post_type == 'lesson') {
                // connect lesson to module
                $wpdb->insert(
                    $wpdb->prefix . 'wpc_connections', array(
                        "post_from"         => (int) $result->post_id,
                        "post_to"           => (int) $result->meta_value,
                        "connection_type"   => 'lesson-to-module',
                        "menu_order"        => (int) $result->menu_order
                    ), 
                    array("%d", "%d", "%s", "%d")
                );
            }
        }
    }
?>