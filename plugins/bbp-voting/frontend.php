<?php
if(!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

add_action('init', 'bbp_voting_frontend_hooks');
function bbp_voting_frontend_hooks() {
    if(is_admin()) return;
    if(apply_filters('bbp_voting_use_filter_hooks_for_buttons', false)) {
        // Hooks for BuddyBoss Platform ---------------------
        // Vote Buttons and Score
        add_filter('bbp_get_reply_content', 'bbp_voting_buttons_filter', 90, 2);
        add_filter('bbp_get_topic_content', 'bbp_voting_buttons_filter', 90, 2);
    } else {
        // Hooks for standard bbPress ---------------------
        // Vote Buttons and Score
        add_action('bbp_theme_before_topic_title', 'bbp_voting_buttons'); // Forum index
        // add_action('bbp_template_before_lead_topic', 'bbp_voting_buttons');
        add_action('bbp_theme_before_topic_content', 'bbp_voting_buttons');
        add_action('bbp_theme_before_reply_content', 'bbp_voting_buttons');
    }
    // Shared Hooks ---------------------
    // Enqueue Scripts
    add_action('wp_enqueue_scripts', 'bbp_voting_styles');
    add_action('wp_enqueue_scripts', 'bbp_voting_scripts');
    // Vote Buttons and Score for Custom Post Type
    add_action('bbp_voting_cpt', 'bbp_voting_buttons', 10, 1);
    // Sort by Votes
    add_filter('bbp_has_topics_query', 'sort_bbpress_posts_by_votes', 99);
    add_filter('bbp_has_replies_query', 'sort_bbpress_posts_by_votes', 99);
}

// Enqueue Scripts

function bbp_voting_styles() {
    wp_enqueue_style( 'bbp-voting-css', plugin_dir_url(__FILE__) . 'css/bbp-voting.css', array(), filemtime(plugin_dir_path(__FILE__) . 'css/bbp-voting.css') );
}

function bbp_voting_scripts() {
    if(function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) {
        // AMP = No JS
    } else {
        wp_enqueue_script( 'bbp-voting-js', plugin_dir_url(__FILE__) . 'js/bbp-voting.js', array('jquery'), filemtime(plugin_dir_path(__FILE__). 'js/bbp-voting.js') );
        wp_localize_script( 'bbp-voting-js', 'bbp_voting_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

// Vote Buttons and Score

function bbp_voting_buttons_filter( $content = '', $reply_id = 0 ) {
    global $bbp_voting_button_filter_reply_ids;
    if(!is_array($bbp_voting_button_filter_reply_ids)) {
        $bbp_voting_button_filter_reply_ids = [];
    }
    if(!in_array($reply_id, $bbp_voting_button_filter_reply_ids)) {
        ob_start();
        bbp_voting_buttons();
        $content = ob_get_clean() . $content;
        $bbp_voting_button_filter_reply_ids[] = $reply_id;
    }
    return $content;
}

function bbp_voting_buttons($post_obj = false) { // $author_link = '', $r = arrray(), $args = array()
    // global $bbp_voting_last_author_link_post_id;
    $current_action = current_action();
    if($current_action === 'bbp_voting_cpt') {
        // Using a custom hook for a custom post type
        if(!$post_obj) return;
        $post = $post_obj;
    } else {
        // Using a bbPress hook
        $topic_post_type = bbp_get_topic_post_type();
        $reply_post_type = bbp_get_reply_post_type();
        if(in_array($current_action, ['bbp_get_topic_content', 'bbp_theme_before_topic_title', 'bbp_template_before_lead_topic', 'bbp_theme_before_topic_content'])) {
            // Topic hook will always be the topic
            $this_post_type = $topic_post_type;
        }
        if(in_array($current_action, ['bbp_get_reply_content', 'bbp_theme_before_reply_content'])) {
            // Reply hook could be topic (OP) or a reply
            $this_post_type = bbp_voting_get_current_post_type();
        }
        // Get the post
        if($this_post_type == $topic_post_type) $post = bbpress()->topic_query->post;
        if($this_post_type == $reply_post_type) $post = bbpress()->reply_query->post;
    }
    // Do we have a post?
    if(!empty($post)) {
        $post_id = $post->ID;
        // Since we're using a filter on the author link, avoid duplicates
        // if($bbp_voting_last_author_link_post_id === $post_id) {
        //     // Duplicate
        //     return $author_link;
        // } else {
        //     // New, continue, but set the global variable
        //     $bbp_voting_last_author_link_post_id = $post_id;
        // }
        if($current_action === 'bbp_voting_cpt') {
            $post_setting = true;
            $broad_disable = false;
        } else {
            switch($this_post_type) {
                case $topic_post_type:
                    $forum_id = bbp_get_topic_forum_id($post_id);
                    $post_setting = get_post_meta( $forum_id, 'bbp_voting_forum_enable_topics', true);
                    $broad_disable = apply_filters('bbp_voting_only_replies', false);
                break;
                case $reply_post_type:
                    $forum_id = bbp_get_reply_forum_id($post_id);
                    $post_setting = get_post_meta( $forum_id, 'bbp_voting_forum_enable_replies', true);
                    $broad_disable = apply_filters('bbp_voting_only_topics', false);
                break;
            }
            // Filter Hook: 'bbp_voting_allowed_on_forum'
            if(!apply_filters('bbp_voting_allowed_on_forum', true, $forum_id)) return;
        }
        if(!empty($post_setting)) {
            // Forum-specific override is set (not Default)
            if($post_setting === 'false') return;
        } else {
            // Use broad disable settings
            if($broad_disable === true) return;
        }
        // Done with "allowed" checks... let's do this
        $score = (int) get_post_meta($post_id, 'bbp_voting_score', true);
        $weighted_score = get_post_meta($post_id, 'bbp_voting_weighted_score', true);
        $trending_score = get_post_meta($post_id, 'bbp_voting_trending_score', true);
        $ups = (int) get_post_meta($post_id, 'bbp_voting_ups', true);
        $downs = (int) get_post_meta($post_id, 'bbp_voting_downs', true);
        $votes = get_post_meta($post_id, 'bbp_voting_votes', true);
        $score_graph = '';
        if(is_array($votes) && apply_filters('bbp_voting_show_visualization', false)) {
            if(function_exists('bbp_voting_get_score_graph')) {
                $score_graph = bbp_voting_get_score_graph($votes, $post_id);
            }
        }
        // Check for, and correct, discrepancies
        $calc_score = $ups + $downs;
        if($score > $calc_score) {
            $diff = $score - $calc_score;
            $ups += $diff;
            update_post_meta($post_id, 'bbp_voting_ups', $ups);
        }
        // Get user's vote by ID or IP
        $voting_log = get_post_meta($post_id, 'bbp_voting_log', true);
        $voting_log = is_array($voting_log) ? $voting_log : array(); // Set up new array
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $identifier = is_user_logged_in() ? get_current_user_id() : $client_ip;
        $existing_vote = array_key_exists($identifier, $voting_log) ? $voting_log[$identifier] : 0;
        // Admin bypass?
        $admin_bypass = current_user_can('administrator') && apply_filters('bbp_voting_admin_bypass', false);
        // View only score?
        // View only for visitors option
        $view_only = (!is_user_logged_in() && apply_filters('bbp_voting_disable_voting_for_visitors', false)) ? true : false;
        if(!$view_only) {
            // View only for closed topic option
            if($current_action !== 'bbp_voting_cpt') {
                $topic_id = $this_post_type == $topic_post_type ? $post_id : bbp_get_reply_topic_id($post_id);
                $topic_status = get_post_status( $topic_id );
                $view_only = ($topic_status == 'closed' && apply_filters('bbp_voting_disable_voting_on_closed_topic', false)) ? true : false;
            }
            if(!$view_only) {
                // View only for author of post
                $view_only = (apply_filters('bbp_voting_disable_author_vote', false) && $post->post_author == get_current_user_ID()) ? true : false;
            }
        }
        // Show labels?
        $show_labels = apply_filters('bbp_voting_show_labels', true);
        // Disable down votes?
        $disable_down = apply_filters('bbp_voting_disable_down_votes', false);
        // How to display vote numbers?
        $display_vote_nums = 'num-'. apply_filters('bbp_voting_display_vote_nums', 'hover');
        // Start HTML
        $html = '';
        $float = in_array(current_action(), ['bbp_get_topic_content', 'bbp_get_reply_content', 'bbp_theme_before_reply_content', 'bbp_theme_before_topic_content', 'bbp_voting_cpt']);
        $html .= '<div class="bbp-voting bbp-voting-post-' . $post_id . (
            $view_only ? ' view-only' : (
                $existing_vote == 1 ? ' voted-up' : (
                    $existing_vote == -1 ? ' voted-down' : ''
                )
            )
        ) . (
            $admin_bypass ? ' admin-bypass' : ''
        ) . (
            $float ? ' bbp-voting-float' : ''
        ) . (
            $calc_score > 0 ? ' positive' : (
                $calc_score < 0 ? ' negative' : ''
            )
        ) . '">';
        //adds the word 'helpful' in red above the arrow
        if($show_labels)
            $html .= '<div class="bbp-voting-label helpful">'.apply_filters('bbp_voting_helpful', 'Helpful').'</div>';
        if(function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) {
            // AMP = No JS
            $post_url = admin_url('admin-ajax.php');
            // Up vote
            $plusups = $ups ? '+'.$ups : ' ';
            $html .= '<form name="amp-form' . $post_id . '" method="post" action-xhr="'.$post_url.'" target="_top" on="submit-success: AMP.setState({\'voteup'. $post_id .'\': '.($ups + 1).'})">
                <input type="hidden" name="action" value="bbpress_post_vote_link_clicked">
                <input type="hidden" name="post_id" value="' . $post_id . '" />
                <input type="hidden" name="direction" value="1" />
                <input type="submit" class="nobutton upvote-amp" value="🔺" />
                <span class="vote up" [text]="voteup'. $post_id .' ? \'+\' + voteup'. $post_id .' : \''.$plusups.'\'">'.$plusups.'</span>
            </form>';
            // Display current vote count for post
            // $html .= '<div class="score">'. $score. '</div>';
            // $html .= '<div class="score" style="background-color: rgb('. floor((1 - $score) * 255). ', '.floor($score * 255).', 0); width:'.floor($score * 100).'%;"></div>';
            $html .= $score_graph;
            // Down vote
            if(!$disable_down) $html .= '<form name="amp-form' . $post_id . '" method="post" action-xhr="'.$post_url.'" target="_top" on="submit-success: AMP.setState({\'votedown'. $post_id .'\': '.($downs - 1).'})">
                <input type="hidden" name="action" value="bbpress_post_vote_link_clicked">
                <input type="hidden" name="post_id" value="' . $post_id . '" />
                <input type="hidden" name="direction" value="-1" />
                <input type="submit" class="nobutton downvote-amp" value="🔻" />
                <span class="vote down" [text]="votedown'. $post_id .' || \''.($downs ? $downs : ' ').'\'">'.($downs ? $downs : '').'</span>
            </form>';
        } else {
            // Normal JS AJAX version
            // Up vote
            $html .= '<a class="vote up '. $display_vote_nums .'" data-votes="'.($ups ? '+'.$ups : '').'" onclick="bbpress_post_vote_link_clicked(' . $post_id . ', 1); return false;">'.__('Up', 'bbp-voting').'</a>';
            // Display current vote count for post
            $html .= '<div class="score" data-sort="'. ($weighted_score !== false && $weighted_score !== '' ? round($weighted_score, 3) : $score) .'">'. (defined('BBPVOTINGDEBUG') ? ('simple: '. $score .'<br>weighted: '. round($weighted_score, 3) .'<br>trending: '. round($trending_score, 3) .'<br>ups:'. $ups .'<br>downs: '. $downs) : $score) . '</div>';
            $html .= $score_graph;
            // Down vote
            if(!$disable_down) $html .= '<a class="vote down '. $display_vote_nums .'" data-votes="'.($downs < 0 ? $downs : '').'" onclick="bbpress_post_vote_link_clicked(' . $post_id . ', -1); return false;">'.__('Down', 'bbp-voting').'</a>';
        }
        //adds the words 'not helpful' in red below the arrow
        if(!$disable_down && $show_labels)
            $html .= '<div class="bbp-voting-label not-helpful">'.apply_filters('bbp_voting_not_helpful', 'Not Helpful').'</div>';
        if($this_post_type == $reply_post_type)
            $html = apply_filters('bbp_voting_after_reply_voting_buttons', $html, $post_id);
        $html .= '</div>';
        // Special hidden mark after the voting buttons for using regex to strip them off of things that pull excerpts using jQuery text()
        $html .= '<span style="display:none;">::</span>';
        // return $html . $author_link;
        echo $html;
    }
}

// Sort by Votes

function sort_bbpress_posts_by_votes( $args = array() ) {
    $forum_id = bbp_get_forum_id();
    // if($forum_id === 0) return $args;
    // $this_post_type = isset($args['post_type']) ? $args['post_type'] : bbp_voting_get_current_post_type();
    // $this_post_type = bbp_voting_get_current_post_type();
    $forum_post_type = bbp_get_forum_post_type();
    $topic_post_type = bbp_get_topic_post_type();
    switch(current_filter()) {
        case 'bbp_has_topics_query':
            $this_post_type = $forum_post_type;
            $post_setting = get_post_meta( $forum_id, 'sort_bbpress_topics_by_votes_on_forum', true);
            $broad_enable = apply_filters('sort_bbpress_topics_by_votes', false);
        break;
        case 'bbp_has_replies_query':
            $this_post_type = $topic_post_type;
            $post_setting = get_post_meta( $forum_id, 'sort_bbpress_replies_by_votes_on_forum', true);
            $broad_enable = apply_filters('sort_bbpress_replies_by_votes', false);
        break;
        default:
            return $args;
    }
    // Do "allowed" checks
    if(isset($_GET['bbp-voting-sort'])) {
        // Sort dropdown used, skip the rest of the checks
        if($_GET['bbp-voting-sort'] === 'best' || $_GET['bbp-voting-sort'] === 'trending') {
            // Proceed with score sorting
        } elseif($_GET['bbp-voting-sort'] === 'default' || $_GET['bbp-voting-sort'] === '') {
            // bbp default non-score sort
            return $args;
        }
    } else {
        // Sort dropdown not used... check the settings
        if($post_setting === 'false') {
            // Forum-specific override is set (not Default)
            return $args;
        } elseif($broad_enable === false) {
            // Use broad disable settings
            return $args;
        } elseif(!apply_filters('bbp_voting_allowed_on_forum', true, $forum_id)) {
            // Voting not allowed on this Forum ID
            return $args;
        } elseif(apply_filters('bbp_voting_sort_by_dropdown_default', '') === 'default') {
            // Sort dropdown default is "default"
            return $args;
        }
        
    }
    // Done with "allowed" checks... let's do this
    // Filter the sort meta key.  Default = bbp_voting_score
    $sort_meta_key = apply_filters('bbp_voting_sort_meta_key', 'bbp_voting_score', esc_attr($_GET['bbp-voting-sort'] ?? ''));

    // Reset for testing only -------------------------------------
    // $argsreset = $args;
    // $query = new WP_Query($argsreset);
    // foreach($query->posts as $reply) {
    //     delete_post_meta($reply->ID, $sort_meta_key); 
    // }
    // -----------------------------------
    
    // Find any replies that are missing the bbp_voting_score post meta and fill them with 0
    $args2 = $args;
    $args2['meta_query'] = [
        [
            'key' => $sort_meta_key,
            'compare' => 'NOT EXISTS',
            'value' => ''
        ],
    ];
    $query = new WP_Query($args2);
    foreach($query->posts as $reply) {
        $fill_in_default = apply_filters('bbp_voting_sort_meta_key_default_value', '0', $reply->ID);
        update_post_meta($reply->ID, $sort_meta_key, $fill_in_default);
    }

    // Now that all missing scores are filled in, we can sort the original args by the score
    // $args['meta_key'] = 'bbp_voting_score';
    // $args['orderby'] = [
    //     'post_type' => 'DESC', 
    //     'meta_value_num' => 'DESC',
    //     'date' => 'DESC'
    // ];
    unset($args['meta_key']);
    unset($args['meta_type']);
    unset($args['orderby']);
    unset($args['order']);
    $args['meta_query'] = [
        'relation' => 'AND',
        'score_clause' => [
            'key' => $sort_meta_key,
            'compare' => 'EXISTS'
        ]
    ];
    if($sort_meta_key == 'bbp_voting_score') {
        $args['meta_query']['score_clause']['type'] = 'NUMERIC';
    }
    if($sort_meta_key == 'bbp_voting_weighted_score') {
        $args['meta_query']['score_clause']['type'] = 'DECIMAL(5,3)';
    }
    if($sort_meta_key == 'bbp_voting_trending_score') {
        $args['meta_query']['score_clause']['type'] = 'DECIMAL(8,3)';
    }
    $args['orderby'] = [
        'post_type' => 'DESC', 
        'score_clause' => 'DESC'
    ];
    if($this_post_type === $topic_post_type) {
        // Add Date as another orderby for topics on a forum
        $args['orderby']['date'] = 'ASC';
    }
    if($this_post_type === $forum_post_type) {
        // Add Freshness as another orderby for topics on a forum
        $args['meta_query']['orderby_freshness'] = [
            'key' => '_bbp_last_active_time',
            'type' => 'DATETIME',
        ];
        $args['orderby']['orderby_freshness'] = 'DESC';
    }
    return $args;
}

add_action('init', 'bbp_voting_lead_topic');
function bbp_voting_lead_topic() {
    if(apply_filters('bbp_voting_lead_topic', false)) {
        add_filter('bbp_show_lead_topic', '__return_true');
    }
}
