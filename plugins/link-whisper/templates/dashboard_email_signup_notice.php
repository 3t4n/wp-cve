<?php

$dismissed = get_option(WPIL_EMAIL_OFFER_DISMISSED, '');
$signed_up = get_option(WPIL_SIGNED_UP_EMAIL_OFFER, array());
$can_edit_posts = current_user_can('edit_posts');
$user = wp_get_current_user();
$force_show = false; // change to true to make the dashboard notice always display

$screen = null;
if(function_exists('get_current_screen')){
    $screen = get_current_screen();
}

// if the current user is an admin, we're on the dashboard, the email notice hasn't been dismissed, and the user hasn't already signed up for emails
if ($can_edit_posts && null !== $screen && 'dashboard' === $screen->base && ( (empty($dismissed) && !isset($signed_up[$user->ID])) || $force_show) && !Wpil_Base::show_review_notice()) {
        ?>
        <div class="wpil-email-signup-offer notice notice-info is-dismissible">
            <img class="email-signup-logo" src="<?php echo esc_url(WP_INTERNAL_LINKING_PLUGIN_URL . 'images/lw-icon.png'); ?>">
            <span class="wpil-email-signup-shoutout"><?php _e('Get a $15 off special coupon code for Link Whisper! You can use it now, or save it in your inbox for later. PLUS get all the best practices, tips, and tricks for using internal links to gain rankings and traffic!', 'wpil'); ?></span>
            <form action="<?php echo esc_url('https://app.convertkit.com/forms/1211413/subscriptions'); ?>" class="seva-form formkit-form email-signup-inputs" method="post" data-sv-form="1211413" data-uid="5334f7abfd" data-format="inline" data-version="5" data-options="{&quot;settings&quot;:{&quot;after_subscribe&quot;:{&quot;action&quot;:&quot;message&quot;,&quot;success_message&quot;:&quot;Success! Now check your email for discount code and more!&quot;,&quot;redirect_url&quot;:&quot;&quot;},&quot;analytics&quot;:{&quot;google&quot;:null,&quot;facebook&quot;:null,&quot;segment&quot;:null,&quot;pinterest&quot;:null},&quot;modal&quot;:{&quot;trigger&quot;:&quot;timer&quot;,&quot;scroll_percentage&quot;:null,&quot;timer&quot;:5,&quot;devices&quot;:&quot;all&quot;,&quot;show_once_every&quot;:15},&quot;powered_by&quot;:{&quot;show&quot;:true,&quot;url&quot;:&quot;https://mbsy.co/convertkit/31088660&quot;},&quot;recaptcha&quot;:{&quot;enabled&quot;:false},&quot;return_visitor&quot;:{&quot;action&quot;:&quot;show&quot;,&quot;custom_content&quot;:&quot;&quot;},&quot;slide_in&quot;:{&quot;display_in&quot;:&quot;bottom_right&quot;,&quot;trigger&quot;:&quot;timer&quot;,&quot;scroll_percentage&quot;:null,&quot;timer&quot;:5,&quot;devices&quot;:&quot;all&quot;,&quot;show_once_every&quot;:15},&quot;sticky_bar&quot;:{&quot;display_in&quot;:&quot;top&quot;,&quot;trigger&quot;:&quot;timer&quot;,&quot;scroll_percentage&quot;:null,&quot;timer&quot;:5,&quot;devices&quot;:&quot;all&quot;,&quot;show_once_every&quot;:15}},&quot;version&quot;:&quot;5&quot;}" min-width="400 500 600 700 800">
                <div data-style="clean">
                    <ul class="formkit-alert formkit-alert-error" data-element="errors" data-group="alert"></ul>
                    <div data-element="fields" data-stacked="false" class="seva-fields formkit-fields">
                        <div class="formkit-field-container">
                            <div class="formkit-field">
                                <input class="formkit-input email-signup-email-input" name="email_address" placeholder="Your email address" required="true" type="email" value="<?php echo $user->user_email; ?>" style="color: rgb(0, 0, 0); border-color: rgb(227, 227, 227); border-radius: 4px; font-weight: 400;">
                            </div>
                        </div>
                        <button data-element="submit" class="formkit-submit formkit-submit button-primary" style="color: rgb(255, 255, 255); background-color: rgb(22, 119, 190); border-radius: 4px; font-weight: 400;">
                            <div class="formkit-spinner">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <span><?php _e('Get the Coupon Code & Tips', 'wpil'); ?></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php
}
?>
