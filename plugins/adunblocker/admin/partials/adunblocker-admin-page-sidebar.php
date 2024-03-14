<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://digitalapps.com
 * @since      1.0.0
 *
 * @package    Adblock Detect
 * @subpackage Adblock Detect/admin/partials
 */

$user = wp_get_current_user(); ?>

<div id="postbox-container-1" class="postbox-container daabd-admin-sidebar">
    <a class="daabd-banner" target="_blank" href="https://digitalapps.com/adunblocker-pro/?utm_source=plugin&utm_medium=web&utm_content=sidebar&utm_campaign=freeplugin">
        <img src="<?php echo plugins_url( '/img/adunblocker-pro.jpg', dirname(__FILE__) ); ?>" alt="<?php _e( 'AdUnblocker Pro &mdash; Kindly inform your visitors to whitelist your website.', 'adunblocker' ); ?>" />
    </a>

    <form method="post" action="https://digitalapps.com/email-subscribe/" target="_blank" class="subscribe block">
        <h3><?php _e( 'Get 20% Off!', 'adunblocker' ); ?></h3>

        <p class="interesting">
            <?php echo wptexturize( __( "Submit your name and email and we'll send you a coupon for 20% off your upgrade to the pro version.", 'adunblocker' ) ); ?>
        </p>

        <div class="field">
            <input type="email" name="email" value="<?php echo esc_attr( $user->user_email ); ?>" placeholder="<?php _e( 'Your Email', 'adunblocker' ); ?>"/>
        </div>

        <div class="field">
            <input type="text" name="first_name" value="<?php echo esc_attr( trim( $user->first_name ) ); ?>" placeholder="<?php _e( 'First Name', 'adunblocker' ); ?>"/>
        </div>

        <div class="field">
            <input type="text" name="last_name" value="<?php echo esc_attr( trim( $user->last_name ) ); ?>" placeholder="<?php _e( 'Last Name', 'adunblocker' ); ?>"/>
        </div>

        <input type="hidden" name="campaigns[]" value="4" />
        <input type="hidden" name="source" value="8" />

        <div class="field submit-button">
            <input type="submit" class="button" value="<?php _e( 'Send me the coupon', 'adunblocker' ); ?>"/>
        </div>

        <p class="promise">
            <?php _e( 'We promise we will not use your email for anything else and you can unsubscribe with 1-click anytime.', 'adunblocker' ); ?>
        </p>
    </form>

    <div class="block testimonial">
        <p class="stars">
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
            <span class="dashicons dashicons-star-filled"></span>
        </p>

        <p class="quote">
            &#8220;Superb. AdUnblocker Pro works like a swiss-watch. Simple and undetectable with easy to use admin tools. What a brilliant plugin. Worth every penny.&#8221;
        </p>

        <p class="author">&mdash; Luke Harris</p>

        <p class="via"><a target="_blank" href="https://twitter.com/Marketing/status/458965600434675712">via Twitter</a></p>
    </div>
</div>