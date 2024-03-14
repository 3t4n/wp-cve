/**
 * JS for classic editor metaboxes
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
(function ($, window, document) {
    'use strict';
    // execute when the DOM is ready
    $(document).ready(function () {

        // check if we already have a pixel and show disable message
        // yes > confirm to assign new one and disable old one
        // no  > assign new pixel
        function step_has_previous_pixel(current_pid, new_pid, post_id, posts_count, nonce) {

            if (current_pid && current_pid !== '-') {

                if(posts_count < 2) {
                    const sure = confirm(wp_metis_metabox_obj.confirm_disable_message);
                    // exit if answer is no
                    if (!sure) {
                        return;
                    }

                }
            }
            // finally add the new pixel
            step_add_manual_pixel(new_pid, post_id, nonce);
        }

        // add the manual pixel or display various error messages
        function step_add_manual_pixel(new_pid, post_id, nonce) {
            // wp ajax call to assign pixel
            $.post(wp_metis_metabox_obj.ajax_url, {
                    action: 'wp_metis_metabox_manual_assign_pixel',
                    post_id: post_id,
                    public_identification_id: new_pid,
                    nonce: nonce
                }, function (data) {
                    // handle response data, show success or error messages
                    if (data) {
                        switch (data) {
                            case 'error-has-same-post-id':
                                alert(wp_metis_metabox_obj.error_has_same_post_id);
                                break;
                            case 'error-assign-to-post-failed':
                                alert(wp_metis_metabox_obj.error_assign_to_post_failed);
                                break;
                            case 'error-remove-pixel-from-post':
                                alert(wp_metis_metabox_obj.error_remove_pixel_from_post);
                                break;
                            case 'error-new-pixel-is-disabled':
                                alert(wp_metis_metabox_obj.error_new_pixel_is_disabled);
                                break;
                            case 'error-inserting-pixel':
                                alert(wp_metis_metabox_obj.error_inserting_pixel);
                                break;
                            case 'success':
                                // alert success message and save / reload page
                                alert(wp_metis_metabox_obj.success);
                                document.getElementById('publish').click();
                                break;
                        }
                    } else {
                        alert(wp_metis_metabox_obj.error_general);
                        return;
                    }
                }
            );
        }

        // get the current pid from data attribute
        const current_public_identification_id = $('#wp_metis_metabox_pixel_action_manual_assign').data('current-public-identification-id');
        // get the current post id from data attribute
        const post_id = $('#wp_metis_metabox_pixel_action_manual_assign').data('post-id');
        // get the count assigned posts for current pixel
        const posts_count = $('#wp_metis_metabox_pixel_action_manual_assign').data('posts-count');
        // get the nonce for sending ajax requests from data attribute
        const nonce = $('#wp_metis_metabox_pixel_action_manual_assign').data('nonce');

        $('#wp_metis_metabox_pixel_action_manual_assign').on('click', () => {
            // ask the user to enter pid of the new pixel
            const new_pid = prompt(wp_metis_metabox_obj.enter_pixel_message);

            if(new_pid !== null) {

                // check ownership / validity
                $.post(wp_metis_metabox_obj.ajax_url, {
                    action: 'wp_metis_metabox_check_validity_and_ownership',
                    post_id: post_id,
                    public_identification_id: new_pid,
                    nonce: nonce
                }, function (data) {
                    // handle response
                    if (data) {
                        switch (data) {
                            // pixel is valid, check if post has a previous pixel
                            case wp_metis_metabox_obj.status_valid:
                                step_has_previous_pixel(current_public_identification_id, new_pid, post_id, posts_count, nonce);
                                break;
                            // pixel not valid, show message and return
                            case wp_metis_metabox_obj.status_not_valid:
                                alert(wp_metis_metabox_obj.status_not_valid_message);
                                break;
                            // pixel not found, show message and return
                            case wp_metis_metabox_obj.status_not_found:
                                alert(wp_metis_metabox_obj.status_not_found_message);
                                break;
                            // no pixel ownership, confirm if we really want to add the pixel, if yes, check if post has previous pixel
                            case wp_metis_metabox_obj.status_not_owner:
                                const answer = confirm(wp_metis_metabox_obj.not_own_pixel_confirmation);
                                if (answer) {
                                    step_has_previous_pixel(current_public_identification_id, new_pid, post_id, posts_count, nonce);
                                }
                                break;
                            // error, show message and return
                            case 'error-is-valid-and-ownership':
                                alert(wp_metis_metabox_obj.error_is_valid_and_ownership);
                                break;
                            // if none of the above, show a general error
                            default:
                                alert(wp_metis_metabox_obj.error_general);
                                break;
                        }
                        // end this
                        return;
                    } else {
                        alert(wp_metis_metabox_obj.error_general);
                        return;
                    }
                });
            }
        });
    });
}(jQuery, window, document));