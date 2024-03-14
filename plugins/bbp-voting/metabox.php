<?php
if(!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Add meta box

add_action('add_meta_boxes', 'bbp_voting_metaboxes');
function bbp_voting_metaboxes() {
    add_meta_box(
        'bbp_voting',
        'bbPress Voting',
        'bbp_voting_forum_metabox',
        'forum',
        'side',
        'low'
    );
}

// Meta box form

function bbp_voting_forum_metabox() {
    $post_id = get_the_ID();
    $options = array(
        '' => 'Default',
        'true' => 'Enable',
        'false' => 'Disable'
    );
    ?>
    <p class="description">
        Enable or disable voting on topics or replies, only for this forum.
    </p>
    <p>
		<strong class="label">Voting on Topics:</strong>
		<select type="checkbox" name="bbp_voting_forum_enable_topics" value="true" id="bbp_voting_forum_enable_topics" class="bbp_dropdown">
            <?php
            $selected = get_post_meta( $post_id, 'bbp_voting_forum_enable_topics', true);
            foreach($options as $value => $label) {
                echo '<option value="'.$value.'" ';
                if($selected==$value) echo 'selected';
                echo '>'.$label.'</option>';
            }
            ?>
        </select>
    </p>
    <p>
		<strong class="label">Voting on Replies:</strong>
		<select type="checkbox" name="bbp_voting_forum_enable_replies" value="true" id="bbp_voting_forum_enable_replies" class="bbp_dropdown">
            <?php
            $selected = get_post_meta( $post_id, 'bbp_voting_forum_enable_replies', true);
            foreach($options as $value => $label) {
                echo '<option value="'.$value.'" ';
                if($selected==$value) echo 'selected';
                echo '>'.$label.'</option>';
            }
            ?>
		</select>
    </p>
    <p class="description">
        Enable or disable sorting based on votes on topics or replies, only for this forum.
    </p>
    <p>
		<strong class="label">Sort Topics by Votes:</strong>
		<select type="checkbox" name="sort_bbpress_topics_by_votes_on_forum" value="true" id="sort_bbpress_topics_by_votes_on_forum" class="bbp_dropdown">
            <?php
            $selected = get_post_meta( $post_id, 'sort_bbpress_topics_by_votes_on_forum', true);
            foreach($options as $value => $label) {
                echo '<option value="'.$value.'" ';
                if($selected==$value) echo 'selected';
                echo '>'.$label.'</option>';
            }
            ?>
        </select>
    </p>
    <p>
		<strong class="label">Sort Replies by Votes:</strong>
		<select type="checkbox" name="sort_bbpress_replies_by_votes_on_forum" value="true" id="sort_bbpress_replies_by_votes_on_forum" class="bbp_dropdown">
            <?php
            $selected = get_post_meta( $post_id, 'sort_bbpress_replies_by_votes_on_forum', true);
            foreach($options as $value => $label) {
                echo '<option value="'.$value.'" ';
                if($selected==$value) echo 'selected';
                echo '>'.$label.'</option>';
            }
            ?>
		</select>
    </p>
    <?php 
}

// Save meta box data

add_action( 'save_post', 'bbp_voting_save_forum_metabox' );
function bbp_voting_save_forum_metabox($post_id) {
    if (array_key_exists('bbp_voting_forum_enable_topics', $_POST)) {
        update_post_meta(
            $post_id,
            'bbp_voting_forum_enable_topics',
            $_POST['bbp_voting_forum_enable_topics']
        );
    }
    if (array_key_exists('bbp_voting_forum_enable_replies', $_POST)) {
        update_post_meta(
            $post_id,
            'bbp_voting_forum_enable_replies',
            $_POST['bbp_voting_forum_enable_replies']
        );
    }
    if (array_key_exists('sort_bbpress_topics_by_votes_on_forum', $_POST)) {
        update_post_meta(
            $post_id,
            'sort_bbpress_topics_by_votes_on_forum',
            $_POST['sort_bbpress_topics_by_votes_on_forum']
        );
    }
    if (array_key_exists('sort_bbpress_replies_by_votes_on_forum', $_POST)) {
        update_post_meta(
            $post_id,
            'sort_bbpress_replies_by_votes_on_forum',
            $_POST['sort_bbpress_replies_by_votes_on_forum']
        );
    }
}