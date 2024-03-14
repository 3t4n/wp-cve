<?php


define('MF_WEEK_IN_SECONDS', 604800);
define('MF_TWO_MONTH_IN_SECONDS', 5259492);
define('MF_SIX_MONTH_IN_SECONDS', 15778476);


function mf_ask_to_leave_review_handler()
{
    global $current_user;
    $user_id = $current_user->ID;
    /* Check that the user hasn't already clicked to ignore the message */
    if (!get_user_meta($user_id, 'mf_next_schedule_review_notice_time')) { ?>
        <div class="notice notice-success is-dismissible" style="padding-bottom: 1rem;">
            <p><?php _e('Thank you for using MightyForms. It would help us a great deal if you could give us your feedback on WP directory. We are hoping we earned your 5-stars! 😉', 'mightyforms'); ?></p>

            <div style="display: flex; flex-direction: column;">
                <a data-rate-action="do-rate" href="?mf_review=do" style="font-size: 16px;">
                    <img src="<?php echo plugin_dir_url(__FILE__) . '/images/thumbs-up.svg'; ?>" class="mf-feedback-icon"><?php _e('Sure!', 'mightyforms') ?>
                </a>
                <a data-rate-action="later" href="?mf_review=later">
                    <img src="<?php echo plugin_dir_url(__FILE__) . '/images/later.svg'; ?>" class="mf-feedback-icon"><?php _e('No, maybe later', 'mightyforms') ?>
                </a>
            </div>
        </div>
<?php }
}



function  mf_review_later_handler()
{

    $user_id = wp_get_current_user()->ID;

    if ($user_id && isset($_GET['mf_review'])) {

        if ($_GET['mf_review'] === 'later') {

            add_user_meta($user_id, 'mf_next_schedule_review_notice_time', time() + MF_TWO_MONTH_IN_SECONDS, true);

            header('Location: ' . $_SERVER['HTTP_REFERER']);

        } elseif ($_GET['mf_review'] === 'do') {

            add_user_meta($user_id, 'mf_next_schedule_review_notice_time', time() + MF_SIX_MONTH_IN_SECONDS, true);
            wp_redirect('https://wordpress.org/support/plugin/mightyforms/reviews/#new-post');
        }
    }
}
