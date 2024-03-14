<?php
add_action('admin_notices', 'rs_author_info_box_optin_notice');
function rs_author_info_box_optin_notice() {
    ?>
    <div class="notice notice-info is-dismissible">
        <h4><?php esc_html_e( 'Love using RS WP BOOK SHOWCASE?', 'rs-author-info-box' );?></h4>
        <p><?php esc_html_e('Become a super contributor by opting in to share non-sensitive plugin data and to receive periodic email updates from us', 'rs-author-info-box'); ?></p>
        <button id="yes-i-would-love-to" class="button button-primary"><?php esc_html_e('Yes I would Love To', 'rs-author-info-box'); ?></button>
    </div>
    <?php
}

add_action('wp_ajax_rs_author_info_box_collect_email', 'rs_author_info_box_collect_email');
add_action('wp_ajax_nopriv_rs_author_info_box_collect_email', 'rs_author_info_box_collect_email');

function rs_author_info_box_collect_email() {
    $admin_email = get_option('admin_email');
    if (!empty($admin_email)) {
        $to = 'rswpthemes@gmail.com';
        $subject = 'New opt-in email';
        $message = $admin_email;
        $sent = wp_mail($to, $subject, $message);
        if ($sent) {
            $response = array(
                'success' => true,
                'data' => array()
            );
        } else {
            $response = array(
                'success' => false,
                'data' => array(
                    'error' => 'Failed to send the email.'
                )
            );
        }
    } else {
        $response = array(
            'success' => false,
            'data' => array(
                'error' => 'Admin email not found.'
            )
        );
    }

    wp_send_json($response);
    wp_die();
}


function rs_author_info_box_optin_script() {
    wp_enqueue_script('rswpthemes-opt-in', RS_AUTHOR_INFO_BOX_PLUGIN_URL . '/includes/opt-in/opt-in.js', array('jquery'), '1.0', true);
    wp_localize_script( 'rswpthemes-opt-in', 'rswpthemes_opt_in',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        )
    );
}
add_action('admin_enqueue_scripts', 'rs_author_info_box_optin_script');
