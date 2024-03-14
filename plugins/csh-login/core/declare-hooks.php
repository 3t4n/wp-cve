<?php
//-------------------------------Add csh Login form-----------------------------
add_action('wp_footer', 'cshlg_add_login_form');

function cshlg_add_login_form() {
    // Check user can register setting.
    global $cshlg_options;
    $rgt_display = "display:none;";
    $fb_login_link = CSHLOGIN_PLUGIN_INCLUDES_URL.'login-with-facebook';
    $twitter_login_link = CSHLOGIN_PLUGIN_INCLUDES_URL.'login-with-twitter';
    $google_login_link = CSHLOGIN_PLUGIN_INCLUDES_URL.'login-with-google';

    $type_class = 'cshlg-dropdown';
    if (isset($cshlg_options['type_modal'])) {
        $type_class = $cshlg_options['type_modal'];
    }
    if ($type_class == 'Dropdown') {
        $type_class = 'cshlg-dropdown';
    }

    $display_labels = 'Labels';
    if (isset($cshlg_options['display_labels'])) {
        $display_labels = $cshlg_options['display_labels'];
    }

    if ( get_option( 'users_can_register' )) {
        $rgt_display = "";
    }

    

    ?>
    <div id="csh-login-wrap" class="<?php echo esc_attr( $type_class ) ?>">

        <div class="login_dialog">

            <a class="boxclose"></a>

            <form class="login_form" id="login_form" method="post" action="#">
                <h2>Please Login</h2>
                <input type="text" class="alert_status" readonly>
                <?php if ($display_labels == 'Labels'): ?>
                    <label for="login_user"> <?php _e('Username'); ?></label>
                <?php endif ?>
                
                <input type="text" name="login_user" id="login_user" />
                <?php if ($display_labels == 'Labels'): ?>
                    <label for="pass_user"> <?php _e('Password'); ?> </label>
                <?php endif ?>

                <input type="password" name="pass_user" id="pass_user" />
                <label for="rememberme" id="lb_rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever"  /> Remember Me</label>
                <input type="submit" name="login_submit" value="<?php _e('LOGIN'); ?>" class="login_submit" />

            </form>

            <form class="register_form" id="register_form" 
                action="<?php echo home_url(); ?>" method="post">
                <h2>Registration</h2>
                <input type="text" class="alert_status" readonly>
                <label for="register_user">Username</label>
                <input type="text" name="register_user" id="register_user" value="" >
                <label for="register_email">E-mail</label>
                <input type="email" name="register_email" id="register_email" value="">
                <div id="allow_pass">
                    <label for="register_pass">Password</label>
                    <input type="password" name="register_pass" id="register_pass" value="">
                    <label for="confirm_pass">Confirm Password</label>
                    <input type="password" name="confirm_pass" id="confirm_pass" value="">
                </div>

                <input type="submit" name="register_submit" id="register_submit" value="REGISTER" />
            </form>

            <form class="lost_pwd_form" action="<?php echo home_url(); ?>" method="post">
                <h2>Forgotten Password?</h2>
                <input type="text" class="alert_status" readonly>
                <label for="lost_pwd_user_email">Username or Email Adress</label>
                <input type="text" name="lost_pwd_user_email" id="lost_pwd_user_email">
                <input type="submit" name="lost_pwd_submit" id="lost_pwd_submit" value="GET NEW PASSWORD">
            </form>

            <div class="pass_and_register" id="pass_and_register">

                <a class="go_to_register_link" href="" style="<?php echo $rgt_display ?>">Register</a>
                <span style="color: black"> </span>
                <a class="go_to_lostpassword_link" href="">Forgot Password</a>
                <span style="color: black"></span>
                <a class="back_login" href="">Back to Login</a>

            </div>


        </div>
    </div>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php
}

add_action( 'plugins_loaded', 'cshlg_register_functions' ); 

function cshlg_register_functions() {
    function cshlg_link_to_login() {
        if (is_user_logged_in()){
            ?>
            <a href="<?php echo wp_logout_url()?>">Logout</a>
            <?php
        }

        if (!is_user_logged_in()) {
            $login_title = 'Login';
            if ( get_option( 'users_can_register' )) {
                $login_title = "Login / Register";
            }
            ?>
            <a class="go_to_login_link" href="<?php echo wp_login_url() ?>" ><?php echo $login_title ?></a>
            <?php
        }
    }
}

//------------------------------Add ShortCode-----------------------------------
add_shortcode( 'csh_login', 'cshlg_shortcode' );

function cshlg_shortcode() {
    $content = "";
    if (is_user_logged_in()) {
        $content = '<a href="'.wp_logout_url().'">Logout</a>';
    }else {
        $login_title = 'Login';
        if ( get_option( 'users_can_register' )) {
            $login_title = "Login / Register";
        }
        $content = '<a class="go_to_login_link" href="'.wp_login_url().'">'.$login_title.'</a>';
    }
    return $content;
}

//--------------------custom redirect logout-----------------------------------------------
add_action('wp_logout','cshlg_redirect_logout');

function cshlg_redirect_logout() {
    global $cshlg_options;
    //background color
    $logout_redirect = home_url();

    if (!empty($cshlg_options['logout_redirect'])) {
        $logout_redirect = $cshlg_options['logout_redirect'];
    }

    wp_redirect( $logout_redirect );
    exit();
}

add_action( 'load-nav-menus.php', 'cshlg_op_register_menu_meta_box' );

function cshlg_op_register_menu_meta_box() {
    add_meta_box(
        'csh-meta-box-id',
        esc_html__('CSH Login', 'text-domain'),
        'cshlg_render_menu_meta_box',
        'nav-menus',
        'side',
        'high'
    );

function cshlg_render_menu_meta_box() {
    ?>
    <div id="posttype-csh-modal-link" class="posttypediv">

        <div id="tabs-panel-csh-modal-link" class="tabs-panel tabs-panel-active">

            <ul id="csh-modal-link-checklist" class="categorychecklist form-no-clear">
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]"
                        value="-1"> <?php _e( 'Login'); ?>/<?php _e( 'Logout' ); ?>
                    </label>
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]"
                    value="<?php _e( 'Login'); ?> // <?php _e( 'Logout'); ?>">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]"
                    value="#csh_modal_login">
                </li>

                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]"
                        value="-1"> <?php _e( 'Register'); ?>
                    </label>
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]"
                    value="<?php _e( 'Register'); ?>">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]"
                    value="#csh_modal_register">
                </li>
            </ul>

        </div>

        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right"
                value="<?php _e( 'Add to Menu' ); ?>" name="add-post-type-menu-item"
                id="submit-posttype-csh-modal-link">
                <span class="spinner"></span>
            </span>
        </p>

    </div>
    <?php
    }
}

// Setup modal links attributes
add_filter( 'nav_menu_link_attributes', 'cshlg_filter_modal_link_atts', 10, 3 );

function cshlg_filter_modal_link_atts($atts, $item, $args) {
    if ('#csh_modal_login' === $atts['href']) {
        if (is_user_logged_in()) {
            $atts['href'] = wp_logout_url();
        }else {
            $atts['class'] = 'go_to_login_link';
            $atts['href'] = wp_login_url(); //for the default type login.
        }
    }elseif ('#csh_modal_register' === $atts['href']) {
        if (is_user_logged_in()) {
            $atts['style'] = 'display:none;';
        }else {
            $atts['class'] = 'menu_register_link';
            global $cshlg_options;
            if ( $cshlg_options['type_modal'] == 'LinkToDefault') {
                $atts['href'] = wp_registration_url(); //for the default registration url.
            }
        }
    }
    return $atts;
}

// Use the right label when displaying modal login/logout link
add_filter( 'wp_nav_menu_objects', 'cshlg_filter_modal_link_label' );

function cshlg_filter_modal_link_label( $items ) {
    foreach ( $items as $i => $item ) {
        if ( '#csh_modal_login' === $item->url ) {
            $item_parts = explode( ' // ', $item->title );

            if ( is_user_logged_in() ) {
                $items[ $i ]->title = array_pop( $item_parts );
            }else {
                $items[ $i ]->title = array_shift( $item_parts );
            }
        }
    }
    return $items;
}

?>