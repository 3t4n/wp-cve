<?php
//------------------------Add Menu Setting and Its Fields--------------------------//

//-------------------------Add Menu----------------------------//
//-------------------------------------------------------------//
use NtqAdminSetting\AdminSettings; // namepase of AdminSettings Class.

$csh_admin_settings = new AdminSettings;
$csh_admin_settings->set_option_name('CSHLogin');
$csh_admin_settings->set_menu_data(
    __('login setting'),
    'Modal Login',
    'manage_options',
    'csh_login',
    CSHLOGIN_PLUGIN_ASSETS_URL.'img/menu-icon.png',
    75
);

add_action('ntqadmin_header', 'cshlg_add_header');
function cshlg_add_header(){
    ?>
        <div class="cshlg_setting_header">
            <h1>CSH Login Setting</h1>
        </div>
    <?php
}

//-----------------Add fields of setting page------------------//
//-------------------------------------------------------------//
//Add Sections.
$csh_admin_settings->add_section('settup_section', 'Setup');

//Add Fields, array() para is xData.
$csh_admin_settings->add_field_of_section('settup_section', 'widget_desc', 'Login Widget', 'description', array(
    'desc' => 'To add login widget to a sidebar navigate to Appearance > Widgets and drag "CSH Login" to a sidebar.'
));

$csh_admin_settings->add_field_of_section('settup_section', 'shortcode_desc', 'Shortcode', 'description', array(
    'desc' => 'Add the following shortcode to a page: [csh_login]'
));

$csh_admin_settings->add_field_of_section('settup_section', 'phpcode_desc', 'PHP Code', 'description', array(
    'desc' => 'Add Function to add a link to CSH Login: <?php cshlg_link_to_login(); ?>'
));

$csh_admin_settings->add_field_of_section('settup_section', 'type_modal', 'Select Type', 'select', array(
    'options' => array(
        '1' => 'Dropdown',
        '2' => 'LinkToDefault'
    ),
    'desc' => 'Select modal login box type.'
));

$csh_admin_settings->add_field_of_section('settup_section', 'direct_url', 'Modal Login Redirect Url', 'radio', array(
    'options' => array(
        '1' => 'Home Page',
        '2' => 'Current Page',
        '3' => 'Custom URL'
    ),
    'default' => array(
        '1' => '1',
        '2' => '0',
        '3' => '0'
    )
));

$csh_admin_settings->add_field_of_section('settup_section', 'custom_redirect', 'Custom Login Redirect URL', 'text', array(
    'default' => '',
    'desc' => 'Enter an <b><i>http-link</i></b> to custom Login redirect URL.'
));

$csh_admin_settings->add_field_of_section('settup_section', 'logout_redirect', 'Logout Redirect URL', 'text', array(
    'default' => '',
    'desc' => 'Set optional logout redirect URL, if not set you will be redirected to home page.'
));

$csh_admin_settings->add_field_of_section('settup_section', 'registration_direct', 'Registration Redirect URL','text', array(
    'default' => '',
    'desc' => 'Set optional registration redirect URL, if not set you will be redirected to current page.'
));

$csh_admin_settings->add_field_of_section('settup_section', 'generated_pass', 'User Generated Password', 'checkbox', array(
    'desc' => 'Allow users to enter their own password during registration.'
));

//Add Sections.
$csh_admin_settings->add_section('style_section', 'Style');

//Add Fields.
$csh_admin_settings->add_field_of_section('style_section', 'display_labels', 'Display Labels', 'select', array(
    'options' => array(
        '1' => 'Labels',
        '2' => 'Placeholders'
    ),
    'desc' => 'Display textfield labels or placeholders.'
));

$csh_admin_settings->add_field_of_section('style_section', 'background_color', 'Background Color', 'color', array(
    'default' => '',
    'desc' => 'Set modal box background color.'
));

$csh_admin_settings->add_field_of_section('style_section', 'button_color', 'Button Color', 'color', array(
    'default' => '',
    'desc' => 'Set modal box button color.'
));

$csh_admin_settings->add_field_of_section('style_section', 'link_color', 'Link Color', 'color', array(
    'default' => '',
    'desc' => 'Set modal box link color.'
));

$csh_admin_settings->add_field_of_section('style_section', 'background_upload', 'Form Background', 'upload', array(
    'default' => '',
    'desc' => ''
));

$csh_admin_settings->add_field_of_section('style_section', 'font_color', 'Font Color', 'color', array(
    'default' => '',
    'desc' => 'Set modal box font color.'
));
//footer premium
add_action('ntqadmin_footer', 'cshlg_add_footer');
function cshlg_add_footer(){
    ?>
        <div class="cshlg_setting_premium">
            <h2><span style="color: red;">Get CSH Login Premium</span></h2>
            <ul>
                <li>
                    <strong> Support for <span style="color: blue;">Visual Composer</span> </strong>
                    <br>
                    <span style="color: red;">Add new element CSH Login inside Visual Composer</span>
                </li>

                <li>
                    <strong> Social Login <span style="color: blue;">Facebook, Twitter, Google+ </span></strong>
                    <br>
                    <span style="color: red;">Fast Login and Register</span>
                </li>

                <li>
                    <strong> Select Type - <span style="color: blue;">Popup</span> </strong>
                    <br>
                    <span style="color: red;">provides a great way to do Login</span>
                </li>
            </ul>

            <ul>
                <li>
                    <strong> More Form <span style="color: blue;">Layouts</span> </strong>
                    <br>
                    <span style="color: red;">We dont want you feel bored</span>
                </li>
                <li>
                    <strong> Custom <span style="color: blue;">CSS</span></strong>
                    <br>
                    <span style="color: red;">You can use directly CSS from setting page</span>
                </li>
                
            </ul>
            <ul>
                <li>
                    <strong> Custom <span style="color: blue;">Registration Email</span></strong>
                    <br>
                    <span style="color: red;">Email Subject and Body easy to change</span>
                </li>
                <li>
                    <strong> 24/7 support</strong>
                    <br>
                </li>
            </ul>

            <div class="link_premium">
                <a id="cshlg-premium-button" class="button button-primary" href="https://cmssuperheroes.com/wordpress-plugins/csh-login/" target="_blank">Get CSH Login Premium now!</a>
                <br>
                <small>Price is lower than 15$, Extend support to 12 months</small>
            </div>

        </div>

    <?php
}


?>