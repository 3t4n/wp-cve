<?php
/**
 * Plugin Name:       WP Login Image Captcha
 * Plugin URI:        https://wordpress.org/plugins/wplic-image-captcha/
 * Description:       Add a simple image captcha and Honeypot to the WordPress Login page
 * Version:           1.2
 * Author:            KC Computing
 * Author URI:        https://profiles.wordpress.org/ktc_88
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wplic-image-captcha
 */

// register style on initialization
add_action('init', 'wplic_register_style');
function wplic_register_style() {
    wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'wplic_style', plugins_url('/style.css', __FILE__), false, '1.1.0', 'all');
}


/**
 * Add "Go Pro" action link to plugins table
 */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wplic_plugin_action_links' );
function wplic_plugin_action_links( $links ) {
    return array_merge(
        array(
            'go-pro' => '<a href="http://kccomputing.net/downloads/wp-login-image-captcha-pro/">' . __( 'Go Pro', 'wplic-image-captcha' ) . '</a>'
        ),
        $links
    );
}


// RESOURCE HELP
// http://stackoverflow.com/questions/17541614/use-thumbnail-image-instead-of-radio-button    
// http://jsbin.com/pafifi/1/edit?html,css,output   
// http://jsbin.com/nenarugiwe/1/edit?html,css,output

function wplic_Function( $args ){
    // Adds an argument to the shortcode to record the type of form (Contact us, Request a Visit, Refer a Friend...) - [fuel-spam-guard form="Contact Us"]
    extract( shortcode_atts( array( 'form' => '' ), $args ) );
    
    // Create an array to hold the image library
    $captchas = array(
        __( 'Heart', 'wplic-image-captcha') => "fa-heart", 
        __( 'House', 'wplic-image-captcha') => "fa-home", 
        __( 'Star', 'wplic-image-captcha')  => "fa-star", 
        __( 'Car', 'wplic-image-captcha')   => "fa-car", 
        __( 'Cup', 'wplic-image-captcha')   => "fa-coffee", 
        __( 'Flag', 'wplic-image-captcha')  => "fa-flag", 
        __( 'Key', 'wplic-image-captcha')   => "fa-key", 
        __( 'Truck', 'wplic-image-captcha') => "fa-truck", 
        __( 'Tree', 'wplic-image-captcha')  => "fa-tree", 
        __( 'Plane', 'wplic-image-captcha') => "fa-plane"
    );

    $choice = array_rand( $captchas, 3);
    foreach($choice as $key) {
        $choices[$key] = $captchas[$key];
    }
    
    // Pick a number between 0-2 and use it to determine which array item will be used as the answer
    $human = rand(0,2);
    
    ob_start(); ?>
    
        <div class="captcha-image">
            <p><?php _e('Please prove you are human by selecting the', 'wplic-image-captcha'); ?> <span><?php echo $choice[$human]; ?></span><?php _e('.', 'wplic-image-captcha'); ?></p>
            
            <?php
            $i = -1;
            foreach($choices as $title => $image) {
                $i++;
                if($i == $human) { $value = "kc_human"; } else { $value = "bot"; };
                echo  '<label><input type="radio" name="kc_captcha" value="'. $value .'"/><i class="fa '. $image .'"></i></label>';
            } ?>
        </div>
        <div style="display:none">
            <input type="text" name="kc_honeypot">
            <input type="hidden" name="FormType" value="<?php echo $form ?>"/>
            <input type="hidden" name="wplicic_exists" value="true"/>
        </div>

    <?php
    $result = ob_get_contents(); // get everything in to $result variable
    ob_end_clean();
    return $result;
}
add_shortcode('wplic', 'wplic_Function');



// RESOURCE HELP
//http://wordpress.stackexchange.com/questions/45900/adding-extra-authentication-field-in-login-page
// https://codex.wordpress.org/Plugin_API/Action_Reference/login_form
// https://codex.wordpress.org/Plugin_API/Filter_Reference/login_head

add_action( 'login_form', 'wplic_add_captcha' );
function wplic_add_captcha() {
    echo do_shortcode('[wplic]');
}

add_filter( 'authenticate', 'wplic_authenticate', 10, 3 );
function wplic_authenticate( $user, $username, $password ){
    //Get POSTED value
    if(isset($_POST['kc_captcha'])) {
        $my_value = $_POST['kc_captcha'];
    }

    //Get user object
    $user = get_user_by('login', $username );

    global $error;
    if(isset($_POST['wp-submit']) && (!$user || empty($my_value) || $my_value != "kc_human" || !empty($_POST['kc_honeypot']) ) ) {
        //User not found, or no value entered or doesn't match stored value - don't proceed.
        remove_action('authenticate', 'wp_authenticate_username_password', 20); 

        if(empty($my_value) || $my_value != "kc_human") {
            $error  = 'Please select the correct image.';
        }
    }

    //Make sure you return null 
    return null;
}