<?php

function wpc_change_lesson_restriction_javascript() { 
    $ajax_nonce = wp_create_nonce( "request-is-good-wpc" ); ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {
        function wpcChangeRestriction() {
            var inputElement, parent, inputValue, lessonId, dripContentSection, dripContentDays, restriction, data;
            
            inputElement = $(this);
            inputValue = inputElement.val();
            lessonId = inputElement.data('id');

            if (inputElement.hasClass('wpc-woo-content-drip-days')) { // Is "Drip Content" - number input
                dripContentDays = inputValue < 1 ? '' : inputValue;
                restriction = 'woo-paid';    
            } else if (inputValue === 'none' || inputValue === 'free-account') { // Is "None" or "Login Required" - radio button
                dripContentSection = inputElement.parent().parent().find('.wpc-metabox-item-content-drip');
                dripContentSection.addClass('wpc-hide-element') // Hide "Drip Content" section
                dripContentSection.find('input').val(''); // Reset drip content days

                dripContentDays = '';
                restriction = inputValue;              
            } else { // Is "Must Purchase w WooCommerce" - radio button
                dripContentSection = inputElement.parent().parent().find('.wpc-metabox-item-content-drip');
                dripContentSection.removeClass('wpc-hide-element'); // Show "Drip Content" section

                dripContentDays = '';
                restriction = inputValue;    
            }

            data = {
                'security': "<?php echo $ajax_nonce; ?>",
                'action': 'wpc_change_restriction',
                'lesson_id': lessonId,
                'restriction': restriction,
                'drip_content_days': dripContentDays
            }

            wpcShowAjaxIcon();
            $.post(ajaxurl, data, function(response) {
                wpcHideAjaxIcon();
            });
        }

        $('.wpc-woo-content-drip-days').on('input', wpcChangeRestriction);
        $('.lesson-restriction-radio').click(wpcChangeRestriction);
    });
    </script> <?php
}

add_action( 'wp_ajax_wpc_change_restriction', 'wpc_change_restriction_action_callback' );
function wpc_change_restriction_action_callback(){
    check_ajax_referer( 'request-is-good-wpc', 'security' );

    if (!current_user_can( 'administrator' )) {
        wp_die();
    }
    
    $lesson_id = isset( $_POST['lesson_id'] ) ? (int) $_POST['lesson_id'] : 0;
    $restriction = isset( $_POST['restriction'] ) ? sanitize_text_field( wp_unslash( $_POST['restriction'] ) ) : '';
    $drip_content_days = isset( $_POST['drip_content_days'] ) ? sanitize_text_field( wp_unslash( $_POST['drip_content_days'] ) ) : '';

    if ( empty( $lesson_id ) ) {
        wp_die();
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'pmpro_memberships_pages';

    if ( $restriction !== 'membership' ) {
        $wpdb->delete( $table_name, array( 'page_id' => $lesson_id ), array( '%d' ) );
    }

    update_post_meta( $lesson_id, 'wpc-lesson-restriction', $restriction );
    update_post_meta( $lesson_id, 'wpc-lesson-content-drip-days', $drip_content_days );

    wp_die(); // Required
}
