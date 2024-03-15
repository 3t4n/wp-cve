<?php
add_action('add_meta_boxes', 'kopa_testimonial_meta_box_add');

function kopa_testimonial_meta_box_add() {
    add_meta_box('kopa-testimonial-edit', 'Meta box', 'kopa_meta_box_testimonial_cb', 'testimonials', 'normal', 'high');
}

function kopa_meta_box_testimonial_cb($post) {
    $author_url = get_post_meta($post->ID, 'author_url', true);

    wp_nonce_field('testimonial_meta_box_nonce', 'testimonial_meta_box_nonce');
    
    ?>
    
    <p class="kopa_option_box">
        <label for="author_url" class="kopa-desc"><?php _e('Author URL', 'kopa-nictitate-toolkit'); ?>:</label>
        <input id="author_url" type="text" name="author_url" 
            class="kopa-layout-select" value="<?php echo $author_url; ?>">
        <span>Ex: http://kopatheme.com</span>
    </p>  

    <?php
}

add_action('save_post', 'kopa_save_testimonial_data');

function kopa_save_testimonial_data($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['testimonial_meta_box_nonce']) || !wp_verify_nonce($_POST['testimonial_meta_box_nonce'], 'testimonial_meta_box_nonce'))
        return;
    if (!current_user_can('edit_post'))
        $allowed = array(
            'a' => array(
                'href' => array()
            )
        );

    if (isset($_POST['author_url']))
        update_post_meta($post_id, 'author_url', wp_kses($_POST['author_url'], $allowed));
}