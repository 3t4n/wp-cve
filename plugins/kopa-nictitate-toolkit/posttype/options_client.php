<?php
add_action('add_meta_boxes', 'kopa_client_meta_box_add');

function kopa_client_meta_box_add() {
    add_meta_box('kopa-client-edit', 'Meta box', 'kopa_meta_box_client_cb', 'clients', 'normal', 'high');
}

function kopa_meta_box_client_cb($post) {
    $client_url = get_post_meta($post->ID, 'client_url', true);

    wp_nonce_field('client_meta_box_nonce', 'client_meta_box_nonce');
    
    ?>
    
    <p class="kopa_option_box">
        <label for="client_url" class="kopa-desc"><?php _e('Client URL', 'kopa-nictitate-toolkit'); ?>:</label>
        <input id="client_url" type="text" name="client_url" 
            class="kopa-layout-select" value="<?php echo $client_url; ?>">
        <span>Ex: http://kopatheme.com</span>
    </p>  

    <?php
}

add_action('save_post', 'kopa_save_client_data');

function kopa_save_client_data($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['client_meta_box_nonce']) || !wp_verify_nonce($_POST['client_meta_box_nonce'], 'client_meta_box_nonce'))
        return;
    if (!current_user_can('edit_post'))
        $allowed = array(
            'a' => array(
                'href' => array()
            )
        );

    if (isset($_POST['client_url']))
        update_post_meta($post_id, 'client_url', wp_kses($_POST['client_url'], $allowed));
}