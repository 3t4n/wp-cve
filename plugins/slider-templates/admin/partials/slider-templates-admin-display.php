<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       codeless.co
 * @since      1.0.0
 *
 * @package    Slider_Templates
 * @subpackage Slider_Templates/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h1>Slider-Templates</h1>
<p>Plugin powered by <a href="https://slider-templates.com" target="_blank">Slider-Templates.com</a> & <a href="https://codeless.co" target="_blank">Codeless</a></p>

<?php if( ! $logged_user && !$premium_theme_actived_key ): ?>
<?php $slider_templates_login_nonce = wp_create_nonce( 'slider_templates_login_nonce' ); ?>

<div class="st-credentials">
    <div class="st-credentials__form" >
        <input required type="email" name="email" class="st-credentials__email" placeholder="E-mail Address" />
        <input required type="password" name="password" class="st-credentials__password" placeholder="Password" />
        <input type="button" class="st-credentials__signin button button-primary" value="Sign In" />
        <p><strong>IMPORTANT:</strong> By signing in, these data will be verified in our server. Absolutely no data will be collected remotely on our server!</p>
        <p>Not yet registered? <a href="https://slider-templates.com/subscription-plan/?utm_source=plugin&utm_medium=joinnow" target="_blank">Join Now</a> to get full access on FREE or Premium Templates</p>
        <p>You don't need to register to get <strong>THEME-INCLUDED</strong> templates!</p>
    </div>
</div>
<?php endif; ?>

<?php if( $logged_user && !$premium_theme_actived_key ): ?>
<div class="st-logged-in sucess notice">
    <?php $user_level = $user_data['is_premium'] ? 'Premium' : 'Free' ?>
    <p>You are logged in as: <strong><?php echo esc_html( $user_data['email'] ) ?></strong>. User Level: <strong><?php echo esc_html( $user_level ) ?></strong>. <a href="<?php echo admin_url() ?>admin.php?page=slider-templates&st_action=logout">Logout</a></p>
    <?php if( $user_level == 'Free' ): ?>
    <p>You can import all 'Theme Included' Sliders and 1 free slider from ST Library with a FREE account! <br /><a href="https://slider-templates.com/subscription-plan/?utm_source=plugin&utm_medium=gopremium1" target="_blank">Click here</a> to <strong>GO Premium</strong> and get access to 100+ Premium Slider Revolution Templates, just for <strong>$1/month</strong>
        <br />
        <br />
        <a href="https://slider-templates.com/subscription-plan/?utm_source=plugin&utm_medium=gopremiumbutton" class="button button-primary">Go Premium</a>
    </p>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="st-message">
    <?php if( $message == 'installed-template' ): ?>
        <div class="updated notice">
            <p>Template Installed Successfully! Check <a href="<?php echo admin_url() ?>admin.php?page=revslider">here</a></p>
        </div>
    <?php endif; ?>

    <?php if( $message == 'no-login' ): ?>
        <div class="error notice">
            <p>You can't install this template without signin</p>
        </div>
    <?php endif; ?>

    <?php if( $message == 'premium-needed' ): ?>
        <div class="error notice">
            <p>Premium Account needed to install this template. <a href="https://slider-templates.com/subscription-plan/?utm_source=plugin&utm_medium=premiumacc">Purchase now</a> $1/month</p>
        </div>
    <?php endif; ?>

    <?php if( $message == 'limit-end' ): ?>
        <div class="error notice">
            <p>You can't import more than 1 free slider (theme-included templates excluded, you can install them all) <a href="https://slider-templates.com/subscription-plan/?utm_source=plugin&utm_medium=limitend">Purchase now</a> $1/month</p>
        </div>
    <?php endif; ?>


    <?php if( !class_exists( 'RevSlider' ) ): ?>
        <div class="error notice">
            <p>You should install Slider Revolution Plugin before!</p>
        </div>
    <?php endif; ?>
</div>


<div class="st-connect">
    By connecting, you agree to data sharing with our server. No sensitive and personal data will be stored in our servers!<br />
    You should connect to get full-list of Slider Templates items.<br /><br />
    <?php if( !$connected ): ?>
        <a href="<?php echo admin_url() . 'admin.php?page=slider-templates&st_action=connect'; ?>" class="button button-primary">Connect with Slider-Templates.com</a>
    <?php endif; ?>

    <?php if( $connected ): ?>
        <a href="<?php echo admin_url() . 'admin.php?page=slider-templates&st_action=disconnect'; ?>" class="button button-primary">Disconnect</a>
    <?php endif; ?>
</div>


<?php if( $connected ): ?>
<div class="st-templates-list">
    <?php foreach( $templates as $template ): ?>
        <?php $is_theme_included = in_array( $template->portfolio_download_id, $theme_included) || $premium_theme_actived_key; ?>
        <div class="st-template <?php echo $is_theme_included ? 'st-theme-included' : '' ?>" data-id="<?php echo esc_attr( $template->portfolio_download_id ) ?>">
            <div class="st-template__image">
            <?php $embedded = (array) $template->_embedded ?>
                <img src="<?php echo esc_url( $embedded['wp:featuredmedia']['0']->source_url ) ?>" />
                <a target="_blank" href="<?php echo esc_url( $template->portfolio_custom_link ) ?>"></a>
            </div>

            <div class="st-template__actions">
                <?php if( class_exists( 'RevSlider' ) ): ?>
                    <a class="button button-secondary st-install-template" data-purchase-code="<?php echo esc_attr( $premium_theme_actived_key ) ?>" href="#" data-download="<?php echo esc_attr( $template->portfolio_download_id ) ?>">Import</a>
                <?php endif; ?>
                <?php if( $is_theme_included ): ?>
                    <span class="st-theme-included-tag">Theme Included</span>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
