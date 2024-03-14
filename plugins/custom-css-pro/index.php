<?php
/*
Plugin Name: Custom CSS Pro
Plugin URI: https://wordpress.org/plugins/custom-css-pro/
Description: Add Custom CSS to your wordpress site in live preview as professional.
Version: 1.0.7
Author: WaspThemes
Author URI: https://yellowpencil.waspthemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/* ---------------------------------------------------- */
/* Saving to database                                   */
/* ---------------------------------------------------- */
function ccp_save_data(){

    if(check_admin_referer("ccp_saving")){

        if(current_user_can('edit_theme_options') == false){
            die();
        }

        $data = stripslashes(wp_strip_all_tags($_POST['data']));

        if($data == true || empty($data) == true){
            update_option('ccp-data',$data);
        }else{
            add_option('ccp-data',$data);
        }

    }

    die();

}

add_action( 'wp_ajax_ccp_save_data', 'ccp_save_data' );


/* ---------------------------------------------------- */
/* Adding a blank page for live CSS                     */
/* ---------------------------------------------------- */
function ccp_blank_page_api() {


    $hook = add_submenu_page(null, 'CCP', 'CCP', 'edit_theme_options', 'ccp-editor','ccp_blank_page');

}

add_action('admin_menu', 'ccp_blank_page_api');

function ccp_blank_page(){
    
}

add_action('load-admin_page_ccp-editor', 'ccp_blank_page_markup');

function ccp_blank_page_markup() {

    $protocol = is_ssl() ? 'https' : 'http';

    $link = get_home_url();
    $linkGo = esc_url($link,array($protocol));

    if(empty($linkGo) == true && strstr($link,'://') == true){
        $linkGo = explode("://",$link);
        $linkGo = $protocol.'://'.$linkGo[1];
    }elseif(empty($linkGo) == true && strstr($link,'://') == false){
        $linkGo = $protocol.'://'.$link;
    }

    $linkGo = add_query_arg(array('ccp-iframe' => 'true'),$linkGo);

    ?>
    <!DOCTYPE html>
    <html lang="en-US">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="robots" content="noindex">
    <title>Loading..</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
    <link rel='stylesheet' href='<?php echo esc_url(plugins_url( 'css/custom-css-pro.css' , __FILE__ )); ?>' type='text/css' />
    <script src='<?php echo esc_url(includes_url( 'js/jquery/jquery.js' , __FILE__ )); ?>'></script>
    <script src='<?php echo esc_url(plugins_url( 'js/ace/ace.js' , __FILE__ )); ?>'></script>
    <script src='<?php echo esc_url(plugins_url( 'js/ace/ext-language_tools.js' , __FILE__ )); ?>'></script>
    <script src='<?php echo esc_url(plugins_url( 'js/custom-css-pro.js' , __FILE__ )); ?>'></script>
    <script>
    window.ccp_ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
    window.ccp_admin_link = '<?php echo admin_url("/options-general.php"); ?>';
    </script>
    </head>
    <body>

        <div id="ccp-section">
            <div id="ccp-bar">
                <a class="ccp-close" href="<?php echo get_home_url(); ?>"></a>
                <a class="ccp-btn ccp-visual-editor" target="_blank" href="https://yellowpencil.waspthemes.com/?utm_source=ccp&utm_medium=text&utm_campaign=ccp">Visual Editor [AD]</a>
                <a id="ccp-save" data-nonce="<?php echo wp_create_nonce('ccp_saving'); ?>" class="ccp-btn">Saved</a>
            </div>
            <div id="ccp-data"><?php echo get_option('ccp-data'); ?></div>
        </div>

        <div id="ccp-bg">
            <div id="ccp-loading"></div>
        </div>
        
        <iframe id="ccp-iframe" data-href="<?php  echo $linkGo; ?>"></iframe>
    </body>
    </html>
    <?php
    exit;

}

add_action('template_redirect', 'ccp_blank_page');



/* ---------------------------------------------------- */
/* Hide Wordpress Admin Bar                             */
/* ---------------------------------------------------- */
function ccp_disable_bar() {
    if (isset($_GET['ccp-iframe'])){
        show_admin_bar(false);
    }
}

add_action('init', 'ccp_disable_bar');



/* ---------------------------------------------------- */
/* Adding Custom CSS Pro menu to wp panel               */
/* ---------------------------------------------------- */ 
function ccp_register_admin_menu() {
    add_submenu_page('options-general.php','Custom CSS Pro','Custom CSS Pro','manage_options','custom-css-pro','ccp_register_admin_menu_markup');
}

add_action('admin_menu', 'ccp_register_admin_menu');


/* ---------------------------------------------------- */
/* Admin page redirect to live editor                   */
/* ---------------------------------------------------- */ 
function ccp_register_admin_menu_markup() {

    $uri = admin_url("/admin.php?page=ccp-editor");

    // Loading
    echo '<div id="ccp-bg" class="ccp-bg-panel"><div id="ccp-loading"></div></div>';

    // Script
    echo '<script>window.location = "'.$uri.'";</script>';

}


/* ---------------------------------------------------- */
/* Add Style to admin bar                               */
/* ---------------------------------------------------- */
function ccp_register_style($hook) {
    
    if($hook == 'settings_page_custom-css-pro'){
        wp_enqueue_style('ccp', plugins_url( 'custom-css-pro.css' , __FILE__ ));
    }
    
}
add_action( 'admin_enqueue_scripts', 'ccp_register_style' );



/* ---------------------------------------------------- */
/* Add Link to wp admin bar                             */
/* ---------------------------------------------------- */

function ccp_admin_bar($wp_admin_bar){

    if(current_user_can("edit_theme_options")){

        $args = array(
            'id'     => 'ccp-editor',
            'title' =>  '<span class="ab-icon"></span>Custom CSS Pro',
            'href' => admin_url("/admin.php?page=ccp-editor"),
            'meta'   => array( 'class' => 'first-toolbar-group' )
        );

        $wp_admin_bar->add_node( $args );

    }            
    
}

add_action( 'admin_bar_menu', 'ccp_admin_bar',1000);



/* ---------------------------------------------------- */
/* Wp admin bar link icon                               */
/* ---------------------------------------------------- */
function ccp_wpadminbar_link_style(){

    if(current_user_can("edit_theme_options")){
        $custom_css = "<style>#wpadminbar #wp-admin-bar-ccp-editor .ab-icon::before{content:'\\f499';top: 3px;}</style>\r";
        echo $custom_css;
    }

}

add_action( 'wp_head', 'ccp_wpadminbar_link_style' );
add_action( 'admin_head', 'ccp_wpadminbar_link_style' );


/* ---------------------------------------------------- */
/* Get Saved styles                                     */
/* ---------------------------------------------------- */
function ccp_get_styles(){

    $data = get_option('ccp-data');

    if($data != false && isset($_GET['ccp-iframe']) == false){
        $custom_css = "<style id='custom-css-pro'>".$data."</style>\r";
        echo $custom_css;
    }

}

add_action( 'wp_head', 'ccp_get_styles',99999999);

