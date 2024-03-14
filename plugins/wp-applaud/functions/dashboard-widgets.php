<?php

class wpApplaudDashboardWidgetsRegister {
    function __construct() 
    {
        add_action('wp_dashboard_setup', array($this, 'wpApplaud_dashboard_widgets') );
    }

    function wpApplaud_dashboard_widgets() {
        wp_add_dashboard_widget(
            'wpApplaud_dashboard_widget',
            'WpApplaud Statistics',
            array($this, 'wpApplaud_dashboard_widget')
        );
    }

    function wpApplaud_dashboard_widget() {

        wp_enqueue_style( 'wp-applaud', plugins_url( '/assets/styles/wp-applaud-dashboard.css', dirname(__FILE__) ) );

        $post_type = 'post';
        if(isset($_POST['_wp_applaud_type'])) {
            $post_type = $_POST['_wp_applaud_type'];
        }

        $likes_posts_args = array(
            'numberposts' => 10,
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_query' => array(
                array(
                    'key' => '_wp_applaud',
                    'value' => 0,
                    'compare' => '>',
                )
            ),
            'post_type' => $post_type,
            'post_status' => 'publish'
        );
        $likes_posts = get_posts($likes_posts_args);

        echo '
        <div class="wp-applaud-stats-selector activity-block">
            <form method="POST">
                <span>View statistics for:</span>
                <input class="button-link" type="submit" name="_wp_applaud_type" value="post"> |
                <input class="button-link" type="submit" name="_wp_applaud_type" value="page">
            </form>
        </div>
        <div class="wp-applaud-dashboard">
            <ul class="wp-applaud-stats activity-block last">';
            if(!empty($likes_posts)) {
            foreach( $likes_posts as $likes_post ) {
                $likes_post_edit = get_edit_post_link($likes_post->ID);
                $count = get_post_meta( $likes_post->ID, '_wp_applaud', true);
                echo '
                <li class="wp-clearfix">
                    <div class="post-info">
                        <div class="post-info-inner">
                            <a class="post-title" href="' . get_permalink($likes_post->ID) . '">' . get_the_title($likes_post->ID) . '</a>
                            <span class="post-action">
                                <a class="post-edit" href="' . $likes_post_edit . '">Edit</a>
                            </span>
                        </div>
                    </div>
                    <div class="post-count">
                        <span class="wp-applaud-count">' . $count . '</span>
                    </div>
                </li>';
            }
            } else {
                echo '<li>No Applaud\'s recorded yet!</li>';
            }
            echo '</ul>
        </div>
        <p class="wp-applaud-dashboard-footer">
                <a href="https://wpapplaud.com/" target="_blank">WpApplaud<span aria-hidden="true" class="dashicons dashicons-external"></span></a>
        </p>';
    }
}