<?php
add_action('wpcf7_editor_panels', 'MPFCF7_editor_panel', 10, 1);

function MPFCF7_editor_panel( $panels ) {
    $panels['my-custom-panel'] = array(
        'title' => __( 'Popup Setting', 'my-plugin' ),
        'callback' => 'MPFCF7_panel_callback',
    );
    return $panels;
}

function MPFCF7_panel_callback() {

    if(isset($_REQUEST['post']) && $_REQUEST['post'] != '') {
        $formid = sanitize_text_field($_REQUEST['post']);
    } else {
        $formid = NULL;
    }
    ?>
    <div class="my-custom-panel">
        <table class="form-table">
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Success Message','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_popup_success_text = get_post_meta( $formid,'mpfcf7_popup_success_text', true );
                    ?>
                    <input type="text" id="mpfcf7_popup_width" name="mpfcf7_popup_success_text" value="<?php echo esc_attr($mpfcf7_popup_success_text); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Button Text','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <input type="text" id="mpfcf7_btn_text" name="mpfcf7_btn_text" value="OK" disabled><label class="mpfcf7_comman_link"><?php echo __('This Option Available in ','message-popup-for-contact-form-7');?> <a href="https://topsmodule.com/product/message-popup-for-contact-form-7/" target="_blank"><?php echo esc_html( __( 'Pro Version', 'message-popup-for-contact-form-7' ) ); ?></a></label>
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Popup Width','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_popup_width = get_post_meta( $formid,'mpfcf7_popup_width', true );
                    if($mpfcf7_popup_width == ''){
                        $mpfcf7_popup_width = '478px';
                    }
                    ?>
                    <input type="text" id="mpfcf7_popup_width" name="mpfcf7_popup_width" value="<?php echo esc_attr($mpfcf7_popup_width); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Popup Border Radius','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_popup_border_radious = get_post_meta( $formid,'mpfcf7_popup_border_radious', true );
                    if($mpfcf7_popup_border_radious == ''){
                        $mpfcf7_popup_border_radious = '5px';
                    }
                    ?>
                    <input type="text" id="mpfcf7_popup_border_radious" name="mpfcf7_popup_border_radious" value="<?php echo esc_attr($mpfcf7_popup_border_radious); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Background Overlay Color','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_background_overlay = get_post_meta( $formid,'mpfcf7_background_overlay', true );
                    if($mpfcf7_background_overlay == ''){
                        $mpfcf7_background_overlay = 'rgba(0,0,0,.4)';
                    }
                    ?>
                    <input type="text" id="mpfcf7_background_overlay" name="mpfcf7_background_overlay" class="mpfcf7_color" value="<?php echo esc_attr($mpfcf7_background_overlay); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Popup Background Color','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_background_color = get_post_meta( $formid,'mpfcf7_background_color', true );
                    if($mpfcf7_background_color == ''){
                        $mpfcf7_background_color = '#ffffff';
                    }
                    ?>
                    <input type="text" id="mpfcf7_background_color" name="mpfcf7_background_color" class="mpfcf7_color" value="<?php echo esc_attr($mpfcf7_background_color); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Popup Border Width','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_popup_border_width = get_post_meta( $formid,'mpfcf7_popup_border_width', true );
                    if($mpfcf7_popup_border_width == ''){
                        $mpfcf7_popup_border_width = '3px';
                    }
                    ?>
                    <input type="text" id="mpfcf7_popup_border_width" name="mpfcf7_popup_border_width" value="<?php echo esc_attr($mpfcf7_popup_border_width); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Popup Border Color','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_border_color = get_post_meta( $formid,'mpfcf7_border_color', true );
                    if($mpfcf7_border_color == ''){
                        $mpfcf7_border_color = '#ffffff';
                    }
                    ?>
                    <input type="text" id="mpfcf7_border_color" name="mpfcf7_border_color" class="mpfcf7_color" value="<?php echo esc_attr($mpfcf7_border_color); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Popup Text Color','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_popup_text_color = get_post_meta( $formid,'mpfcf7_popup_text_color', true );
                    if($mpfcf7_popup_text_color == ''){
                        $mpfcf7_popup_text_color = '#61534e';
                    }
                    ?>
                    <input type="text" id="mpfcf7_popup_text_color" name="mpfcf7_popup_text_color" class="mpfcf7_color" value="<?php echo esc_attr($mpfcf7_popup_text_color); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Button Background Color','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <?php 
                    $mpfcf7_btn_background_color = get_post_meta( $formid,'mpfcf7_btn_background_color', true );
                    if($mpfcf7_btn_background_color == ''){
                        $mpfcf7_btn_background_color = '#7cd1f9';
                    }
                    ?>
                    <input type="text" id="mpfcf7_btn_background_color" name="mpfcf7_btn_background_color" class="mpfcf7_color" value="<?php echo esc_attr($mpfcf7_btn_background_color); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row" colspan="2">
                    <label><?php echo __('Hide Popup','message-popup-for-contact-form-7');?></label>
                </th>
                <td>
                    <input type="text" id="mpfcf7_hide_popup" name="mpfcf7_hide_popup" value="5000" disabled>
                    <span class="description"><?php echo __('Add value like this eg. 5000 (Popup will hide after 5 Seconds).','message-popup-for-contact-form-7');?></span><br>
                    <label class="mpfcf7_comman_link"><?php echo __('This Option Available in ','message-popup-for-contact-form-7');?> <a href="https://topsmodule.com/product/message-popup-for-contact-form-7/" target="_blank"><?php echo esc_html( __( 'Pro Version', 'message-popup-for-contact-form-7' ) ); ?></a></label>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/* Save Form Value*/
function MPFCF7_update_editor_panel_meta($post_id) {

    $formids = $post_id->id;

    if (isset($_POST['mpfcf7_popup_success_text'])) {
        update_post_meta($formids, 'mpfcf7_popup_success_text', $_POST['mpfcf7_popup_success_text']);
    }
    if (isset($_POST['mpfcf7_popup_width'])) {
        update_post_meta($formids, 'mpfcf7_popup_width', $_POST['mpfcf7_popup_width']);
    }
    if (isset($_POST['mpfcf7_popup_border_radious'])) {
        update_post_meta($formids, 'mpfcf7_popup_border_radious', $_POST['mpfcf7_popup_border_radious']);
    }
    if (isset($_POST['mpfcf7_background_overlay'])) {
        update_post_meta($formids, 'mpfcf7_background_overlay', $_POST['mpfcf7_background_overlay']);
    }
    if (isset($_POST['mpfcf7_background_color'])) {
        update_post_meta($formids, 'mpfcf7_background_color', $_POST['mpfcf7_background_color']);
    }
    if (isset($_POST['mpfcf7_popup_border_width'])) {
        update_post_meta($formids, 'mpfcf7_popup_border_width', $_POST['mpfcf7_popup_border_width']);
    }
    if (isset($_POST['mpfcf7_border_color'])) {
        update_post_meta($formids, 'mpfcf7_border_color', $_POST['mpfcf7_border_color']);
    }
    if (isset($_POST['mpfcf7_popup_text_color'])) {
        update_post_meta($formids, 'mpfcf7_popup_text_color', $_POST['mpfcf7_popup_text_color']);
    }
    if (isset($_POST['mpfcf7_btn_background_color'])) {
        update_post_meta($formids, 'mpfcf7_btn_background_color', $_POST['mpfcf7_btn_background_color']);
    }
}
add_action( 'wpcf7_after_save', 'MPFCF7_update_editor_panel_meta' , 10, 1 ); 

/* Default save value */
function MPFCF7_activate() {
    $args = array(
        'post_type' => 'wpcf7_contact_form', 
        'posts_per_page' => -1
    ); 
    $cf7Forms = get_posts( $args );

    foreach ($cf7Forms as $form) {
        $contact_form_id = $form->ID;
    }

    update_post_meta($contact_form_id, 'mpfcf7_popup_width', '478px');
    update_post_meta($contact_form_id, 'mpfcf7_popup_border_radious', '5px');
    update_post_meta($contact_form_id, 'mpfcf7_background_overlay', 'rgba(0,0,0,.4)');
    update_post_meta($contact_form_id, 'mpfcf7_background_color', '#ffffff');
    update_post_meta($contact_form_id, 'mpfcf7_popup_border_width', '3px');
    update_post_meta($contact_form_id, 'mpfcf7_border_color', '#ffffff');
    update_post_meta($contact_form_id, 'mpfcf7_popup_text_color', '#61534e');
    update_post_meta($contact_form_id, 'mpfcf7_btn_background_color', '#7cd1f9');
}
register_activation_hook( mpfcf7_plugin_file, 'MPFCF7_activate' );

/* Popup Style Value */
function MPFCF7_popup_customize(){

    $args = array(
        'post_type' => 'wpcf7_contact_form', 
        'posts_per_page' => -1
    ); 
    $cf7Forms = get_posts( $args );

    foreach ($cf7Forms as $form) {
        $contact_form_id = $form->ID;
    }

    $mpfcf7_popup_width = get_post_meta( $contact_form_id,'mpfcf7_popup_width', true );
    $mpfcf7_popup_border_radious = get_post_meta( $contact_form_id,'mpfcf7_popup_border_radious', true );
    $mpfcf7_background_overlay = get_post_meta( $contact_form_id,'mpfcf7_background_overlay', true );
    $mpfcf7_background_color = get_post_meta( $contact_form_id,'mpfcf7_background_color', true );
    $mpfcf7_popup_border_width = get_post_meta( $contact_form_id,'mpfcf7_popup_border_width', true );
    $mpfcf7_border_color = get_post_meta( $contact_form_id,'mpfcf7_border_color', true );
    $mpfcf7_popup_text_color = get_post_meta( $contact_form_id,'mpfcf7_popup_text_color', true );
    $mpfcf7_btn_background_color = get_post_meta( $contact_form_id,'mpfcf7_btn_background_color', true );
    ?>
    <style type="text/css">
        .swal-overlay {
            background-color: <?php echo esc_attr($mpfcf7_background_overlay); ?>;
        }
        .swal-modal {
            width: <?php echo esc_attr($mpfcf7_popup_width); ?>;
            background-color: <?php echo esc_attr($mpfcf7_background_color); ?>;
            border: <?php echo esc_attr($mpfcf7_popup_border_width); ?> solid;
            border-color: <?php echo esc_attr($mpfcf7_border_color); ?>;
            border-radius: <?php echo esc_attr($mpfcf7_popup_border_radious); ?>;
        }
        .swal-text {
            color: <?php echo esc_attr($mpfcf7_popup_text_color); ?>;
        }
        .swal-button {
            background-color: <?php echo esc_attr($mpfcf7_btn_background_color); ?>!important;
        }
    </style>
<?php
}
add_action( 'wp_footer', 'MPFCF7_popup_customize' );