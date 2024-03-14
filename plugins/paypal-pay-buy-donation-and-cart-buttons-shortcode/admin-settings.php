<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
add_action('admin_menu', 'wpdev_paypal_button_admin_menu');

function wpdev_paypal_button_admin_menu() {
    add_options_page('Paypal Button Options', 'Paypal Button', 'manage_options', 'wpdev-paypal-button', 'wpdev_paypal_button_admin_output');
    add_action('admin_init', 'wpdev_register_paypal_button_settings');
}

function wpdev_register_paypal_button_settings() {
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_type');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_email');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_name');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_currency');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_thankyou_url');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_logo_url');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_custom_url');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_checkout_header_border_color');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_checkout_header_bg_color');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_checkout_bg_color');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_add_note');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_display_cc');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_tax_rate');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_shipping_charges');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_size');
    register_setting('wpdev_paypal_button_options', 'wpdev_paypal_button_lang');

    add_settings_section('wpdev_paypal_button_general_options', 'General Options', 'wpdev_paypal_button_general_options_code', 'wpdev_paypal_button_general_options');
    
    add_settings_field('wpdev_paypal_button_type', 'Default Type of Paypal Button', 'wpdev_paypal_button_type', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_email', 'Paypal E-Mail Address', 'wpdev_paypal_button_email', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_name', 'Business/Product Name', 'wpdev_paypal_button_name', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_currency', 'Currency', 'wpdev_paypal_button_currency', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_lang', 'Language', 'wpdev_paypal_button_lang', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_tax_rate', 'Tax Rate', 'wpdev_paypal_button_tax_rate', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_shipping_charges', 'Shipping Charges', 'wpdev_paypal_button_shipping_charges', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_add_note', 'Enable Note', 'wpdev_paypal_button_add_note', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_logo_url', 'Logo URL', 'wpdev_paypal_button_logo_url', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_custom_url', 'Custom Payment Button', 'wpdev_paypal_button_custom_url', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_checkout_header_border_color', 'Checkout Page Border Color', 'wpdev_paypal_button_checkout_header_border_color', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_checkout_header_bg_color', 'Checkout Page Header Background Color', 'wpdev_paypal_button_checkout_header_bg_color', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_checkout_bg_color', 'Checkout Page Background Color', 'wpdev_paypal_button_checkout_bg_color', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_display_cc', 'Display CC in Button', 'wpdev_paypal_button_display_cc', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_size', 'Button Size', 'wpdev_paypal_button_size', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');
    add_settings_field('wpdev_paypal_button_thankyou_url', 'Thank You Page URL', 'wpdev_paypal_button_thankyou_url', 'wpdev_paypal_button_general_options', 'wpdev_paypal_button_general_options');

}








function wpdev_paypal_button_lang(){
    $langs= array(
        'da_DK/DK' => "Danish",
        'nl_NL/NL' => "Dutch",
        'en_US' => "English",
        'fr_FR/FR' => "French",
        'de_DE/DE' => "German",
        'he_IL/IL' => "Hebrew",
        'it_IT/IT' => "Italian",
        'ja_JP/JP' => "Japanese",
        'no_NO/NO' => "Norwegian",
        'pl_PL/PL' => "Polish",
        'pt_BR/BR' => "Portuguese",
        'ru_RU/RU' => "Russion",
        'es_ES/ES' => "Spanish",
        'zh_XC/C2' => "Simplified Chinese",
        'sv_SE/SE' => "Swedish",
        'th_TH/TH' => "Thai",
        'tr_TR/TR' => "Turkish"
    );
    $selected_lang = get_option('wpdev_paypal_button_lang');
    echo '<select id="wpdev_paypal_button_lang" name="wpdev_paypal_button_lang">';
    foreach ($langs as $key => $value) {
        echo '<option value="'.$key.'" '.selected($selected_lang,$key, false).'>'.$value.'</option>';
    }
    echo '</select>';
    
}
function wpdev_paypal_button_custom_url() {
    echo '<input id="wpdev_paypal_button_custom_url" name="wpdev_paypal_button_custom_url" type="text" value="' . get_option("wpdev_paypal_button_custom_url") . '" size=100 /> <br/>The URL to Custom Button instead of PayPal\'s default button. <br />';
}

function wpdev_paypal_button_general_options_code() {
    echo '<p>' . _e("This section allow you to configure default settings for the [paypal_button] shortcode") . '<a href="#shotcode-help-section">Click here for shortcode help section.</a></p>';
}
function wpdev_paypal_button_checkout_customization_code() {
    echo '<p>' . _e("This section allow you to customize the PayPal checkout page") . '</p>';
}

function wpdev_paypal_button_type() {
    
    echo '<label><input id="wpdev_paypal_button_email1" name="wpdev_paypal_button_type" type="radio" value="paynow" ' . checked(get_option("wpdev_paypal_button_type"), 'paynow', false ) . ' /> Pay Now </label><br />';
    echo '<label><input id="wpdev_paypal_button_email1" name="wpdev_paypal_button_type" type="radio" value="buynow" ' . checked(get_option("wpdev_paypal_button_type"), 'buynow', false ) . ' /> Buy Now</label><br />';
    echo '<label><input id="wpdev_paypal_button_email3" name="wpdev_paypal_button_type" type="radio" value="cart" ' . checked( get_option("wpdev_paypal_button_type"), 'cart', false ) . ' /> Add to Cart</label><br />';
    echo '<label><input id="wpdev_paypal_button_email3" name="wpdev_paypal_button_type" type="radio" value="donation" ' . checked( get_option("wpdev_paypal_button_type"), 'donation', false ) . ' /> Donate</label><br />';
}
function wpdev_paypal_button_email() {
    echo '<input id="wpdev_paypal_button_email" name="wpdev_paypal_button_email" type="text" value="' . get_option("wpdev_paypal_button_email") . '" size=50 /> <br/>Your PayPal ID or an email address associated with your PayPal account. <br/>Leave Empty for site\'s e-mail address<br />';
}
function wpdev_paypal_button_name() {
    echo '<input id="wpdev_paypal_button_name" name="wpdev_paypal_button_name" type="text" value="' . get_option("wpdev_paypal_button_name") . '" size=50 /> <br/>Your Business name in case of donations button and Product name for pay and cart buttons. <br/>Leave Empty for site\'s name<br />';
}
function wpdev_paypal_button_currency() {
    echo '<input id="wpdev_paypal_button_currency" name="wpdev_paypal_button_currency" type="text" value="' . get_option("wpdev_paypal_button_currency") . '" /> <br/>e.g. USD, CAD etc<br />';
}

function wpdev_paypal_button_tax_rate() {
    echo '<input id="wpdev_paypal_button_tax_rate" name="wpdev_paypal_button_tax_rate" type="text" value="' . get_option("wpdev_paypal_button_tax_rate") . '" /> <br/>Set tax percentage that applies to the amount multiplied by the quantity selected during checkout. Allowable values are numbers 0.001 through 100. Valid only for Buy Now, Pay Now and Add to Cart buttons.<br />';
}

function wpdev_paypal_button_shipping_charges() {
    echo '<input id="wpdev_paypal_button_shipping_charges" name="wpdev_paypal_button_shipping_charges" type="text" value="' . get_option("wpdev_paypal_button_shipping_charges") . '" /> <br/>The cost of shipping this item. This flat amount is charged regardless of the quantity of items purchased. Valid only for Buy Now, Pay Now and Add to Cart buttons.<br />';
}
function wpdev_paypal_button_size() {
    echo '<label><input id="wpdev_paypal_button_size1" name="wpdev_paypal_button_size" type="radio" value="large" ' . checked(get_option("wpdev_paypal_button_size"), 'large', false ) . ' /> Large </label><br />';
    echo '<label><input id="wpdev_paypal_button_size2" name="wpdev_paypal_button_size" type="radio" value="small" ' . checked(get_option("wpdev_paypal_button_size"), 'small', false ) . ' /> Small</label><br />';
}
function wpdev_paypal_button_display_cc() {
    echo '<label><input id="wpdev_paypal_button_display_cc1" name="wpdev_paypal_button_display_cc" type="radio" value="yes" ' . checked(get_option("wpdev_paypal_button_display_cc"), 'yes', false ) . ' /> Yes </label><br />';
    echo '<label><input id="wpdev_paypal_button_display_cc2" name="wpdev_paypal_button_display_cc" type="radio" value="no" ' . checked(get_option("wpdev_paypal_button_display_cc"), 'no', false ) . ' /> No</label><br />';
}
function wpdev_paypal_button_logo_url() {
    echo '<input id="wpdev_paypal_button_logo_url" name="wpdev_paypal_button_logo_url" type="text" value="' . get_option("wpdev_paypal_button_logo_url") . '" size=100 /> <br/>The image at the top left of the checkout page. The image’s maximum size is 750 pixels wide by 90 pixels high.<br />';
}
function wpdev_paypal_button_add_note() {
    echo '<label><input id="wpdev_paypal_button_add_note1" name="wpdev_paypal_button_add_note" type="radio" value="yes" ' . checked(get_option("wpdev_paypal_button_add_note"), 'yes', false ) . ' /> Yes </label><br />';
    echo '<label><input id="wpdev_paypal_button_add_note2" name="wpdev_paypal_button_add_note" type="radio" value="no" ' . checked(get_option("wpdev_paypal_button_add_note"), 'no', false ) . ' /> No</label><br />Let buyers to include a note with their payments. <br/>';
}
function wpdev_paypal_button_checkout_header_border_color() {
    echo '<input id="wpdev_paypal_button_checkout_header_border_color" name="wpdev_paypal_button_checkout_header_border_color" type="text" value="' . get_option("wpdev_paypal_button_checkout_header_border_color") . '" /> <br/>The border color around the header of the checkout page. Valid value is an HTML hex code. e.g. FFDDFF<br />';
}
function wpdev_paypal_button_checkout_header_bg_color() {
    echo '<input id="wpdev_paypal_button_checkout_header_bg_color" name="wpdev_paypal_button_checkout_header_bg_color" type="text" value="' . get_option("wpdev_paypal_button_checkout_header_bg_color") . '" /> <br/>The background color for the header of the checkout page. Valid value is an HTML hex code. e.g. FFDDFF<br />';
}
function wpdev_paypal_button_checkout_bg_color() {
    echo '<input id="wpdev_paypal_button_checkout_bg_color" name="wpdev_paypal_button_checkout_bg_color" type="text" value="' . get_option("wpdev_paypal_button_checkout_bg_color") . '" /> <br/>The background color for the checkout page below the header. Valid value is an HTML hex code. e.g. FFDDFF<br />';
}

function wpdev_paypal_button_thankyou_url() {
    echo '<input id="wpdev_paypal_button_thankyou_url" name="wpdev_paypal_button_thankyou_url" type="text" value="' . get_option("wpdev_paypal_button_thankyou_url") . '" size=100 /> <br/>The URL to which PayPal redirects buyers’ browser after they complete their payments. <br />';
}



function wpdev_paypal_button_admin_output() {
    ?>
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="post-body">
                <div id="dashboard-widgets-main-content">
                    <div class="postbox-container" id="main-container" style="width:75%;">
        <h2>PayPal Button Default Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('wpdev_paypal_button_options');
            do_settings_sections('wpdev_paypal_button_general_options');
            ?>
            <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
        </form>
        <h2>Shortcode Help Section</h2>
        <a name="shotcode-help-section"></a>
        <p><strong>type:</strong> <br>
        (string) (optional) Type of transaction. Allowed Values are <br>
        <em>Possible Values</em>: 'paynow', 'buynow', 'cart' or 'donations'</p>
        
        <p><strong>id:</strong> <br>
        (string) (optional) Product Number<br>
        <em>Possible Values</em>: Any numeric product id</p>

        <p><strong>email:</strong> <br>
        (string) (optional) Your PayPal E-Mail address. <br>
        <em>Possible Values</em>: A valid PayPal E-Mail address</p>

        <p><strong>name:</strong> <br>
        (string) (optional) Name of the Product<br>
        <em>Possible Values</em>: Any String</p>

        <p><strong>amount:</strong> <br>
        (numeric) (optional) Product price to be charged. Yes, you can left empty for user to input amount. This can be used for donations.<br>
        <em>Possible Values</em>: Any numeric value</p>

        <p><strong>quantity:</strong> <br>
        (numeric or string) (optional) Specify quantity as number or range or possible comma separated values. Leave empty to let user specify any quantity.<br>
        <em>Possible Values</em>: "1" or "1,5,10" or "1-10"</p>

        <p><strong>quantity_txt_postfix:</strong> <br>
        (string) (optional) Post fix text to be shown in quantity dropdown.<br>
        <em>Possible Values</em>: " items" or " products"</p>

        <p><strong>field_sep:</strong> <br>
        (string) (optional) HTML code to separate the generated visible HTML fields. Use "&lt;br /&gt;" for new line.<br>
        <em>Possible Values</em>: "&nbsp;" or "&lt;br /&gt;"</p>

        <p><strong>amount_prompt:</strong> <br>
        (boolean) (optional) Setting to "1" will enable user to enter amount they would like to pay.<br>
        <em>Possible Values</em>: 1 or 0</p>

        <p><strong>echo_link:</strong> <br>
        (boolean) (optional) Set to "1" for linked output<br>
        <em>Possible Values</em>: 1 or 0</p>

        <p><strong>open_new_window:</strong> <br>
        (boolean) (optional) Set to "1" to open payment page in a new window<br>
        <em>Possible Values</em>: 1 or 0</p>

        <p><strong>currency:</strong> <br>
        (string) (optional) Currency of the Transaction. <br>
        <em>Possible Values</em>: 'USD' or 'CAD' or any currency code</p>

        <p><strong>tax_rate:</strong> <br>
        (numeric) (optional) Tax rate in percentage applied to the total price.<br/>
        <em>Possible Values</em>: 0.0001 to 100</p>
    
        <p><strong>shipping_charges:</strong> <br>
        (numeric) (optional) Shipping charges for the product. <br/>
        <em>Possible Values</em>: Any numeric value</p>

        <p><strong>btn_size:</strong> <br>
        (string) (optional) Set size of the button either 'large' or 'small'.<br>
        <em>Possible Values</em>: 'large' or 'small'</p>

        <p><strong>btn_display_cc:</strong> <br>
        (string) (optional) Display Credit Cards Logo under the button.<br>
        <em>Possible Values</em>: 'yes' or 'no'</p>

        <p><strong>add_note:</strong> <br>
        (string) (optional) Let buyer add a note to order.<br>
        <em>Possible Values</em>: 'yes' or 'no'</p>

        <p><strong>thankyou_page_url:</strong> <br>
        (string) (optional) Buyer will be redirect to this page after successful payment.<br>
        <em>Possible Values</em>: An absolute URL e.g. http://abc.com/thankyou</p>

        <p><strong>checkout_logo_url:</strong> <br>
        (string) (optional) URL to your Logo image.<br>
        <em>Possible Values</em>: An absolute URL e.g. http://abc.com/logo.png</p>

        <p><strong>checkout_header_border_color:</strong> <br>
        (string) (optional) Set border color of the checkout page header.<br>
        <em>Possible Values</em>: A HTML Hexa-decimal code. e.g. FFFF00, 999999 etc </p>

        <p><strong>checkout_header_bg_color:</strong> <br>
        (string) (optional) Change background color of the checkout page header.<br>
        <em>Possible Values</em>: A HTML Hexa-decimal code. e.g. FFFF00, 999999 etc </p>

        <p><strong>checkout_bg_color:</strong> <br>
        (string) (optional) Change background color of the entire checkout page.<br>
        <em>Possible Values</em>: A HTML Hexa-decimal code. e.g. FFFF00, 999999 etc </p>

    </div>
                    </div>
                    <div class="postbox-container" id="side-container" style="width:24%;">
                        <div id="right-sortables" class="meta-box-sortables ui-sortable">
                            <div id="ngg_meta_box" class="postbox ">                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Need Support ?</span></h3>
                                <div class="inside">
                                    <p>We will get back to within 24 hours. Have doubts? Try us!</p>
                                    <ul>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_wordpress_blue.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/extend/plugins/paypal-pay-buy-donation-and-cart-buttons-shortcode/" target="_blank">Visit the WordPress plugin homepage</a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_doc_find.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://mohsinrasool.wordpress.com/2013/01/11/paypal-pay-buy-donation-cart-button-wordpress-shortcode/" target="_blank"><strong>Visit the Documentation page</strong></a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px;line-height: 30px; background:transparent url(http://wpdevsnippets.com/plugin-images/icon_person_help.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/support/plugin/paypal-pay-buy-donation-and-cart-buttons-shortcode" target="_blank">Report a bug </a>
                                         </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="ngg_meta_box" class="postbox ">                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Do you like this Plugin?</span></h3>
                                <div class="inside">
                                    <p>This plugin is developed with the support of <a href="http://wpdevsnippets.com" target="_blank">WPDevSnippets</a>. Please contribute some of your time to appreciate us and spread the word.</p>
                                    <ul>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                             <a href="http://wordpress.org/extend/plugins/paypal-pay-buy-donation-and-cart-buttons-shortcode/" target="_blank">Give it a good rating on WordPress.org</a>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                             <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fwpdevsnippets&amp;send=false&amp;layout=button_count&amp;width=150&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=recommend&amp;height=21&amp;appId=493527070687256" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>
                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                            <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wpdevsnippets.com/wordpress-shortcode-paypal-pay-buy-donation-cart-buttons/" data-text="Awsome Customizable PayPal Payment Buttons Plugin from WPDevSnippets.com" data-related="WPDevSnippets">Tweet</a>
                                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>                                         </li>
                                         <li style="padding-left: 30px; min-height:30px; min-height:30px;line-height: 30px;  background:transparent url(http://wpdevsnippets.com/plugin-images/icon_thumb_up.png) no-repeat scroll center left; text-decoration: none;">
                                            <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
                                            <div class="g-plusone" data-size="medium" data-href="http://wpdevsnippets.com"></div>                                         
                                         </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="ngg_meta_box" class="postbox ">
                                
                                <div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span>Appreciate by Cash?</span></h3>
                                <div class="inside">
                                    <p>This plug-in consumed several of our precious hours, if you use and like it, please donate a token of your appreciation!</p>
                                    <div class="social" style="text-align:center;margin:15px 0 10px 0;">
                                        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DG2R3ZVH44FE4" target="_blank">
                                        <img src="http://wpdevsnippets.com/plugin-images/btn_donate.gif" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <?php
}


?>