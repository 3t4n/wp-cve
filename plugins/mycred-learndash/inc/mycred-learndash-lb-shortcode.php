<?php

function mycred_leaderboard_att($atts) {

    global $wpdb, $mycred_log_table;
    $default = array(
        'number' => '10',
        'pt_types' => array('mycred_default'),
        'type' => 'learndash_course',
        'based_on' => '',
        'ids' => array(),
        'timeframe' => 'monthly',
        'start_date' => '',
        'end_date' => '',
        'per_page' => '20',
        'show_pagination' => false,
        'associated_course_id' => '',
        'associated_lesson_topic_id' => ''
    );

    $reference = 'l.ref = %s';

    $leaderboard_atts = shortcode_atts($default, $atts);


    $timeframe = myCred_Learndash_lb::get_timefilter($leaderboard_atts['timeframe'], $leaderboard_atts['start_date'], $leaderboard_atts['end_date']);

    $ref = $leaderboard_atts['type'] . '_complete';

   

    if ($leaderboard_atts['type'] == 'learndash_course') {
        $ref_id = myCred_Learndash_lb::get_course_ref_id($leaderboard_atts['based_on'], $leaderboard_atts['ids']);
    } elseif ($leaderboard_atts['type'] == 'lesson') {
        $ref_id = myCred_Learndash_lb::get_lesson_ref_id($leaderboard_atts['based_on'], $leaderboard_atts['ids'], $leaderboard_atts['associated_course_id']);
    } elseif ($leaderboard_atts['type'] == 'topic') {
        $ref_id = myCred_Learndash_lb::get_topic_ref_id($leaderboard_atts['based_on'], $leaderboard_atts['ids'], $leaderboard_atts['associated_course_id'], $leaderboard_atts['associated_lesson_topic_id']);
    } elseif ($leaderboard_atts['type'] == 'quiz') {
        $ref_id = myCred_Learndash_lb::get_quiz_ref_id($leaderboard_atts['based_on'], $leaderboard_atts['ids'], $leaderboard_atts['associated_course_id'], $leaderboard_atts['associated_lesson_topic_id']);
    }

   

    $pt_type = $leaderboard_atts['pt_types'] ? $leaderboard_atts['pt_types'] : 'mycred_default';
    $pt_types = explode(",", $pt_type);
    $pt_type_list = "'" . implode("', '", $pt_types) . "'";
    $top = $leaderboard_atts['number'] ? $leaderboard_atts['number'] : 10;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $show_per_page = $leaderboard_atts['show_pagination'];


    $main_query = "SELECT DISTINCT l.user_id AS ID,
                    SUM( l.creds ) AS cred 
                    FROM {$mycred_log_table} l
                    WHERE l.ctype IN ({$pt_type_list}) AND ( l.creds > 0 )  AND ({$reference})
                    {$timeframe}    
                    GROUP BY l.user_id ORDER BY SUM( l.creds ) DESC,l.time ";



    if ($top) {
        $top_users = $main_query . "LIMIT $top";
        $query_users = $wpdb->prepare($top_users, $ref);
    } else {
        $query_users = $wpdb->prepare($main_query, $ref);
    }

    if ($show_per_page == 1) {
        $posts_per_page = $leaderboard_atts['per_page'] ? $leaderboard_atts['per_page'] : 10;
        $offset = ($paged * $posts_per_page) - $posts_per_page;
        $postsQuery = $main_query . "LIMIT $offset,$posts_per_page";
        $prepare_limit = $wpdb->prepare($postsQuery, $ref);
    } else {
        $prepare_limit = $query_users;
    }



    if (@ceil(count($wpdb->get_results($query_users, 'OBJECT')) / $posts_per_page) == 1) {
        $results = $wpdb->get_results($query_users, 'OBJECT');
    } else {
        $results = $wpdb->get_results($prepare_limit, 'OBJECT');

       
    }



    $big = 999999999;
    ob_start();
    ?>
    <?php if ($results):  ?>
        <?php echo '<ul class="top-scores">'; ?>

        <?php foreach ($results as $result):  ?>
            <?php $user_info = get_userdata($result->ID); ?>
            <?php echo '<li>'; ?>
            <?php echo '<label class="user-name">'; ?>
            <?php echo esc_html($user_info->user_login); ?>
            <?php echo '</label>'; ?>
            <?php echo '<div class="user-score">'; ?>
            <?php echo esc_html($result->cred); ?>
            <?php echo '</div>'; ?>
            <?php echo '</li>'; ?>
        <?php endforeach; ?>
        <?php

        

        if ($show_per_page == 1) {
            
            echo '<br/>';
            echo esc_html(paginate_links(array(
                'type' => 'plain',
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => ceil(count($wpdb->get_results($query_users, 'OBJECT')) / $posts_per_page),
            )));
        }
        ?>
    <?php endif; ?>

    <?php

    return ob_get_clean();
}

add_shortcode('mycred_leaderboard_custom', 'mycred_leaderboard_att');
