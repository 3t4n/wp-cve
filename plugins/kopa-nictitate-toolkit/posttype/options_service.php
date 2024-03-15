<?php
add_action('add_meta_boxes', 'kopa_service_meta_box_add');

function kopa_service_meta_box_add() {
    add_meta_box('kopa-service-edit', 'Meta box', 'kopa_meta_box_service_cb', 'services', 'normal', 'high');
}

function kopa_meta_box_service_cb($post) {
    // for upload custom icon
    $dir = get_template_directory_uri() . '/library/js';
    wp_enqueue_script('kopa-uploader', "{$dir}/uploader.js", array('jquery'), NULL, TRUE);

    $icon_class = get_post_meta($post->ID, 'icon_class', true);
    $service_external_page = get_post_meta( $post->ID, 'service_external_page', true );
    $service_static_page = get_post_meta( $post->ID, 'service_static_page', true );
    $service_percentage = (int) get_post_meta($post->ID, 'service_percentage', true);
    wp_nonce_field('service_meta_box_nonce', 'service_meta_box_nonce');
    $kopa_icon = unserialize(KOPA_ICON);
    ?>
    <p class="kopa_option_box">
        <label for="service_external_page" class="kopa-desc"><?php _e( 'Link to external page:', 'kopa-nictitate-toolkit' ); ?></label>
        <input type="url" name="service_external_page" id="service_external_page" value="<?php echo esc_attr( $service_external_page ); ?>" class="regular-text code">
        <small><?php _e( 'Leave it blank if you want to use static page option below.', 'kopa-nictitate-toolkit' ); ?></small>
    </p> 
    <p class="kopa_option_box">
        <label for="service_static_page" class="kopa-desc"><?php _e( 'Link to static page:', 'kopa-nictitate-toolkit' ); ?></label>
        <?php wp_dropdown_pages( array( 'name' => 'service_static_page', 'show_option_none' => __( '&mdash; Select &mdash;', 'kopa-nictitate-toolkit' ), 'option_none_value' => '0', 'selected' => $service_static_page ) ) ; ?>
    </p>
    <p class="kopa_option_box">
        <label for="service_percentage" class="kopa-desc"><?php _e('Service Expertise', 'kopa-nictitate-toolkit'); ?>:</label>
        <select autocomplete="off" name="service_percentage">
            <?php
            for ($i = 1; $i <= 100; $i++) {
                echo '<option value="' . $i . '"';
                if ($i === $service_percentage) {
                    echo ' selected="selected"';
                }
                echo '>' . $i . '</option>';
            }
            ?>
        </select><span>%</span>

    </p>     
    <p class="kopa_option_box">
        <label for="icon_class" class="kopa-desc"><?php _e('Choose icon:', 'kopa-nictitate-toolkit'); ?></label><br>
    <ul class="select-icon clearfix">
        <?php
        foreach ($kopa_icon as $keys => $value) {
            echo '<li';
            if ($keys == $icon_class) {
                echo ' class="selected"';
            }
            echo '><span lang="' . $keys . '" onclick="on_change_icon(jQuery(this));" class="icon-sample" data-icon="' . $value . '"></span></li>';
        }
        ?>
    </ul>
    <input type="hidden" autocomplete="off" name="icon_class" class="icon_class" value="<?php echo $icon_class; ?>">
    </p>
    <?php
}

add_action('save_post', 'kopa_save_service_data');

function kopa_save_service_data($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['service_meta_box_nonce']) || !wp_verify_nonce($_POST['service_meta_box_nonce'], 'service_meta_box_nonce'))
        return;

    if (isset($_POST['icon_class']))
        update_post_meta($post_id, 'icon_class', $_POST['icon_class']);

    if (isset($_POST['service_percentage']))
        update_post_meta($post_id, 'service_percentage', $_POST['service_percentage']);

    if ( isset( $_POST['service_external_page'] ) ) {
        update_post_meta($post_id, 'service_external_page', esc_url($_POST['service_external_page']));
    }

    if ( isset( $_POST['service_static_page'] ) ) {
        update_post_meta($post_id, 'service_static_page', $_POST['service_static_page']);
    }
}