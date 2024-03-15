<?php
add_action('add_meta_boxes', 'kopa_staff_meta_box_add');

function kopa_staff_meta_box_add() {
    add_meta_box('kopa-staff-edit', 'Staff Social Meta Box', 'kopa_meta_box_staff_cb', 'staffs', 'normal', 'high');
}

function kopa_meta_box_staff_cb($post) {
    $position = get_post_meta($post->ID, 'position', true);
    $facebook = get_post_meta($post->ID, 'facebook', true);
    $twitter = get_post_meta($post->ID, 'twitter', true);
    $gplus = get_post_meta($post->ID, 'gplus', true);

    wp_nonce_field('staff_meta_box_nonce', 'staff_meta_box_nonce');
    
    ?>

    <p class="kopa_option_box">
        <label for="position" class="kopa-desc"><?php _e('Position', 'kopa-nictitate-toolkit'); ?>:</label>
        <input id="position" type="text" name="position" 
            class="kopa-option-input" value="<?php echo $position; ?>">
        <span>Ex: Project Manager</span>
    </p> 
    
    <p class="kopa_option_box">
        <label for="facebook" class="kopa-desc"><?php _e('Facebook', 'kopa-nictitate-toolkit'); ?>:</label>
        <input id="facebook" type="text" name="facebook" 
            class="kopa-option-input" value="<?php echo $facebook; ?>">
        <span>Ex: http://facebook.com/kopatheme</span>
    </p>  

    <p class="kopa_option_box">
        <label for="twitter" class="kopa-desc"><?php _e('Twitter', 'kopa-nictitate-toolkit'); ?>:</label>
        <input id="twitter" type="text" name="twitter" 
            class="kopa-option-input" value="<?php echo $twitter; ?>">
        <span>Ex: http://twitter.com/kopatheme</span>
    </p>

    <p class="kopa_option_box">
        <label for="gplus" class="kopa-desc"><?php _e('Google Plus', 'kopa-nictitate-toolkit'); ?>:</label>
        <input id="gplus" type="text" name="gplus" 
            class="kopa-option-input" value="<?php echo $gplus; ?>">
        <span>Ex: http://plus.google.com/kopatheme</span>
    </p>

    <?php
}

add_action('save_post', 'kopa_save_staff_data');

function kopa_save_staff_data($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['staff_meta_box_nonce']) || !wp_verify_nonce($_POST['staff_meta_box_nonce'], 'staff_meta_box_nonce'))
        return;
    if (!current_user_can('edit_post'))
        $allowed = array(
            'a' => array(
                'href' => array()
            )
        );

    if (isset($_POST['position']))
        update_post_meta($post_id, 'position', wp_kses($_POST['position'], $allowed));
    if (isset($_POST['facebook']))
        update_post_meta($post_id, 'facebook', wp_kses($_POST['facebook'], $allowed));
    if (isset($_POST['twitter']))
        update_post_meta($post_id, 'twitter', wp_kses($_POST['twitter'], $allowed));
    if (isset($_POST['gplus']))
        update_post_meta($post_id, 'gplus', wp_kses($_POST['gplus'], $allowed));
}