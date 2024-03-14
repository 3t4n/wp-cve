<?php
// Exit if directlye accessed
defined('ABSPATH') or die('Cannot access pages directly.');

function gp_social_options_page_add_plugin_page()
{
    add_theme_page(
        'GP Social Settings',
        'GP Social Settings',
        'manage_options',
        'gp-social-options-page',
        'gp_social_options_page'
    );
}
add_action('admin_menu', 'gp_social_options_page_add_plugin_page');

function gp_social_settings_init()
{

    register_setting('gp_social_icons', 'gp_social_settings');

    add_settings_section(
        'gp_social_icon_section',
        __('Icon Settings', 'generatepress'),
        'gp_social_settings_section_callback',
        'gp_social_icons'
    );

    add_settings_section(
        'gp_social_colour_section',
        __('Colour Settings', 'generatepress'),
        'gp_social_settings_section_callback',
        'gp_social_icons'
    );

    add_settings_section(
        'gp_social_settings_section',
        __('Output Settings', 'generatepress'),
        'gp_social_settings_section_callback',
        'gp_social_icons'
    );

    add_settings_field(
        'facebook_icon',
        __('Facebook Icon', 'generatepress'),
        'gp_social_facebook_icon_render',
        'gp_social_icons',
        'gp_social_icon_section'
    );

    add_settings_field(
        'facebook_colour',
        __('Facebook Icon Colour', 'generatepress'),
        'gp_social_facebook_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'facebook_hover_colour',
        __('Facebook Icon Hover Colour', 'generatepress'),
        'gp_social_facebook_hover_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'twitter_icon',
        __('Twitter Icon', 'generatepress'),
        'gp_social_twitter_icon_render',
        'gp_social_icons',
        'gp_social_icon_section'
    );

    add_settings_field(
        'twitter_colour',
        __('Twitter Icon Colour', 'generatepress'),
        'gp_social_twitter_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'twitter_hover_colour',
        __('Twitter Icon Hover Colour', 'generatepress'),
        'gp_social_twitter_hover_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'linkedin_icon',
        __('LinkedIn Icon', 'generatepress'),
        'gp_social_linkedin_icon_render',
        'gp_social_icons',
        'gp_social_icon_section'
    );

    add_settings_field(
        'linkedin_colour',
        __('LinkedIn Icon Colour', 'generatepress'),
        'gp_social_linkedin_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'linkedin_hover_colour',
        __('LinkedIn Icon Hover Colour', 'generatepress'),
        'gp_social_linkedin_hover_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'pinterest_icon',
        __('Pinterest Icon', 'generatepress'),
        'gp_social_pinterest_icon_render',
        'gp_social_icons',
        'gp_social_icon_section'
    );

    add_settings_field(
        'pinterest_colour',
        __('Pinterest Icon Colour', 'generatepress'),
        'gp_social_pinterest_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'pinterest_hover_colour',
        __('Pinterest Icon Hover Colour', 'generatepress'),
        'gp_social_pinterest_hover_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'whatsapp_icon',
        __('WhatsApp Icon', 'generatepress'),
        'gp_social_whatsapp_icon_render',
        'gp_social_icons',
        'gp_social_icon_section'
    );

    add_settings_field(
        'whatsapp_colour',
        __('WhatsApp Icon Colour', 'generatepress'),
        'gp_social_whatsapp_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'whatsapp_hover_colour',
        __('WhatsApp Icon Hover Colour', 'generatepress'),
        'gp_social_whatsapp_hover_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'email_icon',
        __('Email Icon', 'generatepress'),
        'gp_social_email_icon_render',
        'gp_social_icons',
        'gp_social_icon_section'
    );

    add_settings_field(
        'email_colour',
        __('Email Icon Colour', 'generatepress'),
        'gp_social_email_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'email_hover_colour',
        __('Email Icon Hover Colour', 'generatepress'),
        'gp_social_email_hover_colour_render',
        'gp_social_icons',
        'gp_social_colour_section'
    );

    add_settings_field(
        'hook_locations',
        __('Hook Locations', 'generatepress'),
        'gp_social_hook_locations_render',
        'gp_social_icons',
        'gp_social_settings_section'
    );

    if ( class_exists( 'WooCommerce' ) ) {
        add_settings_field(
            'gp_woo_global_hook',
            __('WooCommerce Global Hooks', 'generatepress'),
            'gp_social_woo_global_render',
            'gp_social_icons',
            'gp_social_settings_section'
        );
        add_settings_field(
            'gp_woo_single_hook',
            __('WooCommerce Product Hooks', 'generatepress'),
            'gp_social_woo_single_render',
            'gp_social_icons',
            'gp_social_settings_section'
        );
        add_settings_field(
            'gp_woo_shop_hook',
            __('WooCommerce Shop Hooks', 'generatepress'),
            'gp_social_woo_shop_render',
            'gp_social_icons',
            'gp_social_settings_section'
        );
    }

    add_settings_field(
        'hook_disable',
        __('Disable Hook Location', 'generatepress'),
        'gp_social_disable_hook_render',
        'gp_social_icons',
        'gp_social_settings_section'
    );

}
add_action('admin_init', 'gp_social_settings_init');

function gp_social_facebook_icon_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<textarea cols='80' rows='5' name='gp_social_settings[facebook_icon]'><?php echo isset($options['facebook_icon']) ? esc_attr($options['facebook_icon']) : gp_social_default_facebook(); ?></textarea>
	<?php
}

function gp_social_twitter_icon_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<textarea cols='80' rows='5' name='gp_social_settings[twitter_icon]'><?php echo isset($options['twitter_icon']) ? esc_attr($options['twitter_icon']) : gp_social_default_twitter(); ?></textarea>
	<?php
}

function gp_social_linkedin_icon_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<textarea cols='80' rows='5' name='gp_social_settings[linkedin_icon]'><?php echo isset($options['linkedin_icon']) ? esc_attr($options['linkedin_icon']) : gp_social_default_linkedin(); ?></textarea>
	<?php
}

function gp_social_pinterest_icon_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<textarea cols='80' rows='5' name='gp_social_settings[pinterest_icon]'><?php echo isset($options['pinterest_icon']) ? esc_attr($options['pinterest_icon']) : gp_social_default_pinterest(); ?></textarea>
	<?php
}

function gp_social_whatsapp_icon_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<textarea cols='80' rows='5' name='gp_social_settings[whatsapp_icon]'><?php echo isset($options['whatsapp_icon']) ? esc_attr($options['whatsapp_icon']) : gp_social_default_whatsapp(); ?></textarea>
	<?php
}

function gp_social_email_icon_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<textarea cols='80' rows='5' name='gp_social_settings[email_icon]'><?php echo isset($options['email_icon']) ? esc_attr($options['email_icon']) : gp_social_default_email(); ?></textarea>
	<?php
}

function gp_social_facebook_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[facebook_colour]' value='<?php echo isset($options['facebook_colour']) ? esc_attr($options['facebook_colour']) : gp_social_default_icon_color(); ?>'>
	<?php
}

function gp_social_facebook_hover_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[facebook_hover_colour]' value='<?php echo isset($options['facebook_hover_colour']) ? esc_attr($options['facebook_hover_colour']) : '#1e73be'; ?>'>
	<?php
}

function gp_social_twitter_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[twitter_colour]' value='<?php echo isset($options['twitter_colour']) ? esc_attr($options['twitter_colour']) : gp_social_default_icon_color(); ?>'>
	<?php
}

function gp_social_twitter_hover_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[twitter_hover_colour]' value='<?php echo isset($options['twitter_hover_colour']) ? esc_attr($options['twitter_hover_colour']) : '#00acee'; ?>'>
	<?php
}

function gp_social_linkedin_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[linkedin_colour]' value='<?php echo isset($options['linkedin_colour']) ? esc_attr($options['linkedin_colour']) : gp_social_default_icon_color(); ?>'>
	<?php
}

function gp_social_linkedin_hover_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[linkedin_hover_colour]' value='<?php echo isset($options['linkedin_hover_colour']) ? esc_attr($options['linkedin_hover_colour']) : '#0077b5'; ?>'>
	<?php
}

function gp_social_pinterest_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[pinterest_colour]' value='<?php echo isset($options['pinterest_colour']) ? esc_attr($options['pinterest_colour']) : gp_social_default_icon_color(); ?>'>
	<?php
}

function gp_social_pinterest_hover_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[pinterest_hover_colour]' value='<?php echo isset($options['pinterest_hover_colour']) ? esc_attr($options['pinterest_hover_colour']) : '#c92228'; ?>'>
	<?php
}

function gp_social_whatsapp_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[whatsapp_colour]' value='<?php echo isset($options['whatsapp_colour']) ? esc_attr($options['whatsapp_colour']) : gp_social_default_icon_color(); ?>'>
	<?php
}

function gp_social_whatsapp_hover_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[whatsapp_hover_colour]' value='<?php echo isset($options['whatsapp_hover_colour']) ? esc_attr($options['whatsapp_hover_colour']) : '#075e54'; ?>'>
	<?php
}

function gp_social_email_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[email_colour]' value='<?php echo isset($options['email_colour']) ? esc_attr($options['email_colour']) : gp_social_default_icon_color(); ?>'>
	<?php
}

function gp_social_email_hover_colour_render()
{
    $options = get_option('gp_social_settings');
    ?>
	<input class="color-picker" ype='text' name='gp_social_settings[email_hover_colour]' value='<?php echo isset($options['email_hover_colour']) ? esc_attr($options['email_hover_colour']) : '#f1f1d4'; ?>'>
	<?php
}

function gp_social_disable_hook_render()
{
    $options = get_option('gp_social_settings');
    $checked = isset($options['hook_disable']) ? 'checked' : '';
    ?>
    <label class="switch">
        <input type='checkbox' name='gp_social_settings[hook_disable]' <?php echo $checked; ?> value='1'>
        <span class="slider round"></span>
    </label>
    <?php
}

function gp_social_hook_locations_render(  )
{ 
    $options = get_option( 'gp_social_settings' );
    $hooks = gp_social_gp_hooks();
    $location = isset($options['hook_locations']) ? esc_attr($options['hook_locations']) : 'generate_after_content'; ?>
    <select class="select-hook" name='gp_social_settings[hook_locations]'>
    <?php foreach ( $hooks as $hook ) { ?>
        <option value="<?php echo $hook; ?>" <?php if( $location == $hook ) { echo 'selected'; }; ?>><?php echo $hook; ?></option>
    <?php } ?>
    </select>

<?php
}

function gp_social_woo_global_render(  )
{ 
    $options = get_option( 'gp_social_settings' );
    $hooks = gp_social_wc_global_hooks();
    $location = isset($options['gp_woo_single_hook']) ? esc_attr($options['gp_woo_single_hook']) : ''; ?>
    <select class="select-hook" name='gp_social_settings[gp_woo_single_hook]'>
    <?php foreach ( $hooks as $hook ) { ?>
        <option value="<?php echo $hook; ?>" <?php if( $location == $hook ) { echo 'selected'; }; ?>><?php echo $hook; ?>><?php echo $hook; ?></option>
    <?php } ?>
    </select>

<?php
}

function gp_social_woo_single_render(  )
{ 
    $options = get_option( 'gp_social_settings' );
    $hooks = gp_social_wc_single_hooks();
    $location = isset($options['gp_woo_global_hook']) ? esc_attr($options['gp_woo_global_hook']) : ''; ?>
    <select class="select-hook" name='gp_social_settings[gp_woo_global_hook]'>
    <?php foreach ( $hooks as $hook ) { ?>
        <option value="<?php echo $hook; ?>" <?php if( $location == $hook ) { echo 'selected'; }; ?>><?php echo $hook; ?>><?php echo $hook; ?></option>
    <?php } ?>
    </select>

<?php
}

function gp_social_woo_shop_render(  )
{ 
    $options = get_option( 'gp_social_settings' );
    $hooks = gp_social_wc_shops_hooks(); ?>
    <select class="select-hook" name='gp_social_settings[gp_woo_shop_hook]'>
    <?php foreach ( $hooks as $hook ) { ?>
        <option value="<?php echo $hook; ?>" <?php selected( isset($options['gp_woo_shop_hook']), $hook ); ?>><?php echo $hook; ?></option>
    <?php } ?>
    </select>

<?php
}

function gp_social_gp_hooks() {
    $hooks = array(
        'generate_before_header' => 'generate_before_header',
        'generate_after_header' => 'generate_after_header',
        'generate_before_header_content' => 'generate_before_header_content',
        'generate_after_header_content' => 'generate_after_header_content',
        'generate_before_logo' => 'generate_before_logo',
        'generate_after_logo' => 'generate_after_logo',
        'generate_header' => 'generate_header',
        'generate_inside_navigation' => 'generate_inside_navigation',
        'generate_inside_secondary_navigation' => 'generate_inside_secondary_navigation',
        'generate_inside_mobile_menu' => 'generate_inside_mobile_menu',
        'generate_inside_mobile_menu_bar' => 'generate_inside_mobile_menu_bar',
        'generate_inside_mobile_header' => 'generate_inside_mobile_header',
        'generate_inside_slideout_navigation' => 'generate_inside_slideout_navigation',
        'generate_after_slideout_navigation' => 'generate_after_slideout_navigation',
        'generate_inside_container' => 'generate_inside_container',
        'generate_before_main_content' => 'generate_before_main_content',
        'generate_after_main_content' => 'generate_after_main_content',
        'generate_before_content' => 'generate_before_content',
        'generate_after_content' => 'generate_after_content',
        'generate_after_primary_content_area' => 'generate_after_primary_content_area',
        'generate_before_entry_title' => 'generate_before_entry_title',
        'generate_after_entry_title' => 'generate_after_entry_title',
        'generate_after_entry_header' => 'generate_after_entry_header',
        'generate_after_archive_description' => 'generate_after_archive_description',
        'generate_before_comments_container' => 'generate_before_comments_container',
        'generate_before_comments' => 'generate_before_comments',
        'generate_inside_comments' => 'generate_inside_comments',
        'generate_below_comments_title' => 'generate_below_comments_title',
        'generate_before_right_sidebar_content' => 'generate_before_right_sidebar_content',
        'generate_after_right_sidebar_content' => 'generate_after_right_sidebar_content',
        'generate_before_left_sidebar_content' => 'generate_before_left_sidebar_content',
        'generate_after_left_sidebar_content' => 'generate_after_left_sidebar_content',
        'generate_before_footer' => 'generate_before_footer',
        'generate_after_footer' => 'generate_after_footer',
        'generate_after_footer_widgets' => 'generate_after_footer_widgets',
        'generate_before_footer_content' => 'generate_before_footer_content',
        'generate_after_footer_content' => 'generate_after_footer_content',
        'generate_footer' => 'generate_footer',
    );
    return $hooks;
}

function gp_social_wc_global_hooks() {
    $hooks = array (
        'woocommerce_before_main_content' => 'woocommerce_before_main_content',
        'woocommerce_after_main_content' => 'woocommerce_after_main_content',
        'woocommerce_sidebar' => 'woocommerce_sidebar',
        'woocommerce_breadcrumb' => 'woocommerce_breadcrumb',
    );
    return $hooks;
}
function gp_social_wc_shops_hooks() {
    $hooks = array (
        'woocommerce_archive_description' => 'woocommerce_archive_description',
        'woocommerce_before_shop_loop' => 'woocommerce_before_shop_loop',
        'woocommerce_after_shop_loop' => 'woocommerce_after_shop_loop',
        'woocommerce_before_shop_loop_item_title' => 'woocommerce_before_shop_loop_item_title',
        'woocommerce_after_shop_loop_item_title' => 'woocommerce_after_shop_loop_item_title',
    );
    return $hooks;
}
function gp_social_wc_single_hooks() {
    $hooks = array (
        'woocommerce_before_single_product' => 'woocommerce_before_single_product',
        'woocommerce_before_single_product_summary' => 'woocommerce_before_single_product_summary',
        'woocommerce_after_single_product_summary' => 'woocommerce_after_single_product_summary',
        'woocommerce_single_product_summary' => 'woocommerce_single_product_summary',
        'woocommerce_simple_add_to_cart' => 'woocommerce_simple_add_to_cart',
        'woocommerce_before_add_to_cart_form' => 'woocommerce_before_add_to_cart_form',
        'woocommerce_after_add_to_cart_form' => 'woocommerce_after_add_to_cart_form',
        'woocommerce_before_add_to_cart_button' => 'woocommerce_before_add_to_cart_button',
        'woocommerce_after_add_to_cart_button' => 'woocommerce_after_add_to_cart_button',
        'woocommerce_before_add_to_cart_quantity' => 'woocommerce_before_add_to_cart_quantity',
        'woocommerce_after_add_to_cart_quantity' => 'woocommerce_after_add_to_cart_quantity',
        'woocommerce_product_meta_start' => 'woocommerce_product_meta_start',
        'woocommerce_product_meta_end' => 'woocommerce_product_meta_end',
        'woocommerce_after_single_product' => 'woocommerce_after_single_product',
        'woocommerce_share' => 'woocommerce_share',
    );
    return $hooks;
}


function gp_social_settings_section_callback()
{

    echo __('', 'generatepress');

}

function gp_social_options_page()
{

    ?>
    <style>.switch{position:relative;display:inline-block;width:60px;height:34px}.switch input{opacity:0;width:0;height:0}.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;-o-transition:.4s;transition:.4s}.slider:before{position:absolute;content:"";height:26px;width:26px;left:4px;bottom:4px;background-color:#fff;-webkit-transition:.4s;-o-transition:.4s;transition:.4s}input:checked+.slider{background-color:#2196f3}input:focus+.slider{-webkit-box-shadow:0 0 1px #2196f3;box-shadow:0 0 1px #2196f3}input:checked+.slider:before{-webkit-transform:translateX(26px);-ms-transform:translateX(26px);transform:translateX(26px)}.slider.round{border-radius:34px}.slider.round:before{border-radius:50%}span.wp-picker-input-wrap{margin-top:10px}button.button.wp-color-result{width:100px}.wp-picker-container.wp-picker-active{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column}.iris-picker.iris-border{margin-top:10px}.wrapper{display:-ms-grid;display:grid;-ms-grid-columns:auto 20px 28%;grid-template-columns:auto 28%;grid-column-gap:20px;margin-top:58px;padding-right:20px;}.sidebar-wrapper{background:#fff;border:1px solid #999;padding:6px}.form-table{display:none}.gp-social-settings h2{display:none}#button-wrapper{margin-bottom:50px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.toggle-settings{border-bottom:1px solid #ccc;text-decoration:none;padding:10px 20px;color:#23282d}.toggle-settings:focus{-webkit-box-shadow:none;box-shadow:none}.toggle-settings.active{border:1px solid #ccc;border-bottom-color:transparent;border-top-left-radius:4px;border-top-right-radius:4px;color:#0073aa}</style>
    <script>jQuery(document).ready(function(e){e("a#icon-settings").addClass("active"),e("input.color-picker").wpColorPicker(),e(".select-hook").select2({width:"resolve",placeholder:"Select an option"}),e(".gp-social-settings .form-table").each(function(){var t=e(this).prev("h2").html().toLowerCase().replace(/\s+/g,"-");e(this).attr("id",t).add}),e("table#icon-settings").show(),e("table#icon-settings").prev("h2").show(),e(".toggle-settings").click(function(t){t.preventDefault();var s="table#"+e(this).attr("id");e("table.form-table").hide(),e("table.form-table").prev("h2").hide(),e(s).show(),e(s).prev("h2").show()}),e(".toggle-settings").click(function(t){e(".toggle-settings").removeClass("active"),e(this).addClass("active")})});</script>
    <div class="wrapper">
		<form class="gp-social-settings" action='options.php' method='post'>
            <div id="button-wrapper">
                    <a id="icon-settings" href="#" class="toggle-settings"><?php echo __('Icon Settings'); ?></a>
                    <a id="colour-settings" href="#" class="toggle-settings"><?php echo __('Colour Settings'); ?></a>
                    <a id="output-settings" href="#" class="toggle-settings"><?php echo __('Custom Settings'); ?></a>
            </div>
            <?php
            settings_fields('gp_social_icons');
            do_settings_sections('gp_social_icons');
            submit_button();
            ?>
		</form>
        <div id="sidebar">
            <div class="sidebar-wrapper">
                <h2>GP Social Share Settings</h2>
            
                <h3><?php echo __('Contact'); ?></h3>
                <p>Don't hesitate to <a href="mailto:jon@westcoastdigital.com.au" target="_blank">contact me</a> to request new features, ask questions, or just say hi.</p>
                <h3>Other West Coast Digtal Plugins</h3>
                <p>Check out some of my other plugins available on the Repository and GitHub</p>
                <p><a class="button" href="https://en-au.wordpress.org/plugins/gp-elements-admin-link/" target="_blank">GP Elements Admin Link</a></p>
                <p><a class="button" href="https://wordpress.org/plugins/gp-related-posts/" target="_blank">GP Related Posts</a></p>
                <p><a class="button" href="https://github.com/WestCoastDigital/wcd-login" target="_blank">Custom Login</a></p>
                <p><a class="button" href="https://github.com/WestCoastDigital/WordPress-Breadcrumbs" target="_blank">Breadcrumbs</a></p>
                <h3>Donate</h3>
                <p>If you wish to buy me a cup of coffee to say thanks, use the button below.</p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick">
                    <input type="hidden" name="hosted_button_id" value="4EUJJDGZPBB56">
                    <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button">
                    <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
		</div>
    </div>
		<?php

}

// Default svg icons
function gp_social_default_facebook()
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/></svg>';
    return $svg;
} // default facebook icon

function gp_social_default_twitter()
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"/></svg>';
    return $svg;
} // default twitter icon

function gp_social_default_linkedin()
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/></svg>';
    return $svg;
} // default linkedin icon

function gp_social_default_pinterest()
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm0 19c-.721 0-1.418-.109-2.073-.312.286-.465.713-1.227.87-1.835l.437-1.664c.229.436.895.804 1.604.804 2.111 0 3.633-1.941 3.633-4.354 0-2.312-1.888-4.042-4.316-4.042-3.021 0-4.625 2.027-4.625 4.235 0 1.027.547 2.305 1.422 2.712.132.062.203.034.234-.094l.193-.793c.017-.071.009-.132-.049-.202-.288-.35-.521-.995-.521-1.597 0-1.544 1.169-3.038 3.161-3.038 1.72 0 2.924 1.172 2.924 2.848 0 1.894-.957 3.205-2.201 3.205-.687 0-1.201-.568-1.036-1.265.197-.833.58-1.73.58-2.331 0-.537-.288-.986-.886-.986-.702 0-1.268.727-1.268 1.7 0 .621.211 1.04.211 1.04s-.694 2.934-.821 3.479c-.142.605-.086 1.454-.025 2.008-2.603-1.02-4.448-3.553-4.448-6.518 0-3.866 3.135-7 7-7s7 3.134 7 7-3.135 7-7 7z"/></svg>';
    return $svg;
} // default pinterest icon

function gp_social_default_whatsapp()
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 1.856.001 3.598.723 4.907 2.034 1.31 1.311 2.031 3.054 2.03 4.908-.001 3.825-3.113 6.938-6.937 6.938z"/></svg>';
    return $svg;
} // default whatsapp icon

function gp_social_default_email()
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .02c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.99 6.98l-6.99 5.666-6.991-5.666h13.981zm.01 10h-14v-8.505l7 5.673 7-5.672v8.504z"/></svg>';
    return $svg;
} // default email icon

function gp_social_default_icon_color()
{
    return '#999999';
}

// Register the frontend output content
function social_share_filter() {

    global $post;
    $id = get_the_ID();
    $post_object = get_post( $id );
    $excerpt = wp_strip_all_tags( get_the_excerpt(), true );
    $excerpt = str_replace(' ... Read more', '', $excerpt);
    

    $twitter_meta = get_post_meta( $post->ID, 'gp_social_share_twitter-content', true ) ? get_post_meta( $post->ID, 'gp_social_share_twitter-content', true ) : $excerpt;
    $email_meta = get_post_meta( $post->ID, 'gp_social_share_email-content', true ) ? get_post_meta( $post->ID, 'gp_social_share_email-content', true ) : __('Check out this awesome article', 'gp-social') . ' by ' . get_the_author() . ' on ' . get_permalink();;
    $image_meta = get_post_meta( $post->ID, 'gp_social_share_social-share-image', true );

    $title = get_the_title( $id );
    $url = urlencode( get_permalink( $id ) );
    //$excerpt = wp_trim_words( $content, 40 );
    $thumbnail = $image_meta ? $image_meta : get_the_post_thumbnail_url( $id, 'full' );
    $author_id = $post->post_author;
    $author = get_the_author_meta( 'display_name' , $author_id );
    $options = get_option('gp_social_settings');

    $facebook = isset($options['facebook_icon']) ? esc_attr($options['facebook_icon']) : gp_social_default_facebook();
    $twitter = isset($options['twitter_icon']) ? esc_attr($options['twitter_icon']) : gp_social_default_twitter();
    $linkedin = isset($options['linkedin_icon']) ? esc_attr($options['linkedin_icon']) : gp_social_default_linkedin();
    $pinterest = isset($options['pinterest_icon']) ? esc_attr($options['pinterest_icon']) : gp_social_default_pinterest();
    $whatsapp = isset($options['whatsapp_icon']) ? esc_attr($options['whatsapp_icon']) : gp_social_default_whatsapp();
    $email = isset($options['email_icon']) ? esc_attr($options['email_icon']) : gp_social_default_email();

    $custom_email = '';
    $disable_author = '';
    
    // Add support to change email body
    if( !$custom_email ) {
        if ( !function_exists( 'gp_social_email_body' ) ) {
            $email_body = $email_meta;
        } else {
            $email_body = gp_social_email_body();
        }
    } else {
        $email_body = $custom_email;
        if( !$disable_author) {
            $email_body .= ' by ' . $author ;
        }
        $email_body .= '. ' . $url;
    }

    // Add support to change facebook link
    if ( function_exists( 'gp_social_facebook_link' ) ) {
        $facebook_link = gp_social_facebook_link();
    } else {
        $facebook_link = '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" onclick="return false" class="fb-share" title="' . __( 'Share this post!', 'gp-social' ) . '">' . html_entity_decode($facebook) . '</a>';
    }

    // Add support to change twitter link
    if ( function_exists( 'gp_social_twitter_link' ) ) {
        $twitter_link = gp_social_twitter_link();
    } else {
        $twitter_link = '<a href="https://twitter.com/share?url=' . $url . '&text=' . $twitter_meta . '" class="tw-share" title="' . __( 'Tweet this post!', 'gp-social' ) . '">' . html_entity_decode($twitter) . '</a>';
    }
    
    // Add support to change linkedin link
    if ( function_exists( 'gp_social_linkedin_link' ) ) {
        $linkedin_link = gp_social_linkedin_link();
    } else {
        $linkedin_link = '<a href="http://www.linkedin.com/shareArticle?url=' . $url . '&title=' . $title . '" class="li-share" title="' . __( 'Share this post!', 'gp-social' ) . '">' . html_entity_decode($linkedin) . '</a>';
    }
    
    // Add support to change pinterest link
    if ( function_exists( 'gp_social_pinterest_link' ) ) {
        $pinterest_link = gp_social_pinterest_link();
    } else {
        $pinterest_link = '<a href="https://pinterest.com/pin/create/bookmarklet/?media=' . $thumbnail . '&url=' . $url . '&description=' . $title . '" class="pt-share" title="' . __( 'Pin this post!', 'gp-social' ) . '">' . html_entity_decode($pinterest) . '</a>';
    }
    
    // Add support to change whatsapp link
    if ( function_exists( 'gp_social_whatsapp_link' ) ) {
        $whatsapp_link = gp_social_whatsapp_link();
    } else {
        $whatsapp_link = '<a href="whatsapp://send?text=' . $url . '" class="wa-share" data-action="share/whatsapp/share" title="' . __( 'Share this post!', 'gp-social' ) . '">' . html_entity_decode($whatsapp) . '</a>';
    }

    $social_links = array();
    $list = '';

    // Add support to add prefix text
    if( has_filter('add_social_prefix') ) {
        $list .= apply_filters( 'add_social_prefix', $content );
    }


    $list .= '<ul id="gp-social-share">';

        if( $facebook ) {
            $list .= '<li class="gp-social-facebook">' . $facebook_link . '</li>';
        }
        if( $twitter ) {
            $list .= '<li class="gp-social-twitter">' . $twitter_link . '</li>';
        }
        if( $linkedin ) {
            $list .= '<li class="gp-social-linkedin">' . $linkedin_link . '</li>';
        }
        if( $pinterest ) {
            $list .= '<li class="gp-social-pinterest">' . $pinterest_link . '</li>';
        }
        if( $whatsapp ) {
            $list .= '<li class="gp-social-whatsapp">' . $whatsapp_link . '</li>';
        }
        if( $email ) {
            $list .= '<li class="gp-social-email"><a href="mailto:?Subject=' .  $title . '&Body=' . $email_body . '" target="_top" class="em-share" title="' . __( 'Email this post!', 'gp-social' ) . '">' . html_entity_decode($email) . '</a></li>';
        }
    // Users can now add additional icons as they require them (example in readme.md)
    if( has_filter('add_social_icons') ) {

        $social_links = apply_filters( 'add_social_icons', $social_links );

    }

    // Create the social list
    foreach( $social_links as $social_link ) :
        
        $list .= '<li>' . $social_link . '</li>';

    endforeach;

    $list .= '</ul>';

    return $list;
}// social_share_filter


// Register CSS and JS
function register_styles_scripts() {

    wp_register_style( 'social-share-css', plugins_url( '/css/gp-social-share.css', __FILE__ ), array(), 'all' );
        
    wp_register_script( 'social-share-js', plugin_dir_url( __FILE__ ) . 'js/gp-social-share.js', array('jquery'), '1.0' );

}
add_action( 'wp_enqueue_scripts', 'register_styles_scripts' );

// Create the frontend output
function add_social_icons() {

    // Check to ensure we are on a single post
    if ( is_single() ) {

        // Enqueue base style now we are in the hook
        wp_enqueue_style( 'social-share-css' );

        // Enqueue base script now we are in the hook
        wp_enqueue_script( 'social-share-js' );

        // Echo out the social icons
        echo social_share_filter();

    }// is_single

}// add_social_icons

// Create shortcode
function gp_social_shortcode() {
    wp_enqueue_style( 'social-share-css' );
    wp_enqueue_script( 'social-share-js' );
    return social_share_filter();
}
add_shortcode( 'gp-social', 'gp_social_shortcode' );

class GPSocialMetaBoxes {
	private $screens = array(
        'post',
        'product',
    );

	private $fields = array(
		array(
			'id' => 'twitter-content',
            'label' => 'Twitter Content',
            'description' => 'Leave blank for excerpt',
            'type' => 'textarea',
		),
		array(
			'id' => 'email-content',
			'label' => 'Email Content',
            'description' => 'Leave blank for default email body',
			'type' => 'textarea',
		),
		array(
			'id' => 'social-share-image',
			'label' => 'Social Share Image',
            'description' => '',
			'type' => 'media',
		),
	);

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'gp-social-share',
				__( 'GP Social Share', 'generatepress' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'side',
				'default'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 * 
	 * @param object $post WordPress post object
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'gp_social_share_data', 'gp_social_share_nonce' );
		$this->generate_fields( $post );
	}

	/**
	 * Hooks into WordPress' admin_footer function.
	 * Adds scripts for media uploader.
	 */
	public function admin_footer() {
		?><script>
			// https://codestag.com/how-to-use-wordpress-3-5-media-uploader-in-theme-options/
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.rational-metabox-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$("#"+id).val(attachment.url);
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
				}
			});
		</script><?php
	}

	/**
	 * Generates the field's HTML for the meta box.
	 */
	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '<small style="display: block;"><em>' . $field['description'] . '</em></small></label>';
            $db_value = get_post_meta( $post->ID, 'gp_social_share_' . $field['id'], true );
            if( $field['id'] == 'twitter-content' ) {
                if( $db_value != '' ) {
                    $textarea = $db_value;
                } else {
                    $textarea = $excerpt;
                }
            } elseif($field['id'] == 'email-content' ) {
                if( $db_value != '' ) {
                    $textarea = $db_value;
                } else {
                    $textarea = __('Check out this awesome article', 'gp-social') . ' by ' . get_the_author() . ' on ' . get_permalink();
                }
            } else {

            }
			switch ( $field['type'] ) {
				case 'checkbox':
					$input = sprintf(
						'<input %s id="%s" name="%s" type="checkbox" value="1">',
						$db_value === '1' ? 'checked' : '',
						$field['id'],
						$field['id']
					);
					break;
				case 'media':
					$input = sprintf(
						'<input id="%s" name="%s" type="text" value="%s"> <input class="button rational-metabox-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
						$field['id'],
						$field['id'],
						$db_value,
						$field['id'],
						$field['id']
					);
					break;
				case 'textarea':
					$input = sprintf(
						'<textarea id="%s" name="%s" rows="5">%s</textarea>',
						$field['id'],
						$field['id'],
						$textarea
					);
					break;
				default:
					$input = sprintf(
						'<input id="%s" name="%s" type="%s" value="%s">',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= '<p>' . $label . '<br>' . $input . '</p>';
		}
		echo $output;
	}

	/**
	 * Hooks into WordPress' save_post function
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['gp_social_share_nonce'] ) )
			return $post_id;

		$nonce = $_POST['gp_social_share_nonce'];
		if ( !wp_verify_nonce( $nonce, 'gp_social_share_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'gp_social_share_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'gp_social_share_' . $field['id'], '0' );
			}
		}
	}
}
new GPSocialMetaBoxes;
