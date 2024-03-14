<?php

// add_action( 'admin_menu', 'idea_push_remove_metaboxes');
// function idea_push_remove_metaboxes(){
//     remove_meta_box('', 'idea', 'normal');
//     remove_meta_box('boardsdiv', 'idea', 'normal');
// }



 add_action( 'add_meta_boxes', 'idea_push_add_metaboxes');
 function idea_push_add_metaboxes() {
    //  add_meta_box( 'status-div', 'Status','idea_push_status_metabox','idea' ,'side','core');
    //  add_meta_box( 'boards-div', 'Board','idea_push_boards_metabox','idea' ,'side','core');
     add_meta_box( 'votes-div', 'Votes','idea_push_votes_metabox','idea' ,'side','core');
 }


function idea_push_votes_metabox( $post ) {
    
    wp_nonce_field( basename( __FILE__ ), 'votes_meta_box_nonce' );
    
    $existingVotes = get_post_meta($post->ID, 'votes', true);	
	
    echo '<div class="inside"><p>';
        
    echo '<input type="number" name="votes" min="" max="" value="'.$existingVotes.'">';
        
    echo '</p></div>';      
    
}


function idea_push_save_votes_metabox( $post_id ){
	
    if ( !isset( $_POST['votes_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['votes_meta_box_nonce'], basename( __FILE__ ) ) ){
        return;
    }
    
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
	   return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ){
        return;
    }
    
    if ( isset( $_REQUEST['votes'] ) ) {
        update_post_meta( $post_id, 'votes', sanitize_text_field( $_POST['votes'] ) );
    }

  
}
add_action( 'save_post_idea', 'idea_push_save_votes_metabox', 10, 2 );





function idea_push_taxonomy_radio_meta_box($post, $box) {
    $defaults = array('taxonomy' => 'category');
    if (!isset($box['args']) || !is_array($box['args']))
        $args = array();
    else
        $args = $box['args'];
    extract(wp_parse_args($args, $defaults), EXTR_SKIP);
    $tax = get_taxonomy($taxonomy);
    $selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
    $hierarchical = $tax->hierarchical;

    ?>
    <div id="taxonomy-<?php echo $taxonomy; ?>" class="checkdiv">
        <?php if (current_user_can($tax->cap->edit_terms)): ?>
            <input type="hidden" name="tax_input[<?php echo $taxonomy ?>][]" value="0" />
            <ul class="categorychecklist">
                <?php foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term):
                    $value = $hierarchical ? $term->term_id : $term->slug;
                    ?>
                    <li>
                        <label class="selectit">
                            <input type="radio" name="<?php echo "tax_input[$taxonomy][]"; ?>"
                                    value="<?php echo esc_attr($value); ?>" <?php checked(in_array($term->term_id, $selected)); ?> />
                            <?php echo esc_html($term->name); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}

function idea_push_taxonomy_radio_meta_box_status($post, $box) {

    $options = get_option('idea_push_settings'); 

    $defaults = array('taxonomy' => 'category');
    if (!isset($box['args']) || !is_array($box['args']))
        $args = array();
    else
        $args = $box['args'];
    extract(wp_parse_args($args, $defaults), EXTR_SKIP);
    $tax = get_taxonomy($taxonomy);
    $selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));
    $hierarchical = $tax->hierarchical;

    // print_r($selected);

    if(empty($selected)){
        $selected = array(2);   
    }

    ?>
    <div id="taxonomy-<?php echo $taxonomy; ?>" class="checkdiv">
        <?php if (current_user_can($tax->cap->edit_terms)): ?>
            <input type="hidden" name="tax_input[<?php echo $taxonomy ?>][]" value="0" />
            <ul class="categorychecklist">
                <?php foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term):

                    if(isset($options['idea_push_disable_approved_status']) && $term->slug == 'approved'){
                        continue;
                    }

                    if(isset($options['idea_push_disable_declined_status']) && $term->slug == 'declined'){
                        continue;
                    }

                    if(isset($options['idea_push_disable_in_progress_status']) && $term->slug == 'in-progress'){
                        continue;
                    }

                    if(isset($options['idea_push_disable_completed_status']) && $term->slug == 'completed'){
                        continue;
                    }

                    $value = $hierarchical ? $term->term_id : $term->slug;
                    ?>
                    <li>
                        <label class="selectit">
                            <input type="radio" name="<?php echo "tax_input[$taxonomy][]"; ?>"
                                    value="<?php echo esc_attr($value); ?>" <?php checked(in_array($term->term_id, $selected)); ?> />
                                    
                            <?php echo esc_html(idea_push_translate_status($term->name)); ?>
                        </label>
                    </li>


                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php
}
	









?>