<?php
/*
Plugin Name: Simple Redirection
Description: Simply redirect all pages to a specified URL
Plugin URI: https://wordpress.org/plugins/eelv-redirection/
Version: 1.5
Author: N.O.U.S. Open Useful and Simple
Author URI: https://apps.avecnous.eu/?mtm_campaign=wp-plugin&mtm_kwd=eelv-redirection&mtm_medium=wp-repo&mtm_source=author
License: GPLv2
Text Domain: eelv-redirection
Domain Path: /languages/
*/
namespace EELV_REDIRECTION;

\add_action('init', 'EELV_REDIRECTION\mk_redirect');
\add_action('wp_head', 'EELV_REDIRECTION\mk_redirecthtml'); // We never know...
\add_action('admin_menu', 'EELV_REDIRECTION\add_submenu');
\add_action('admin_bar_menu', 'EELV_REDIRECTION\adminbar', 100);
\add_filter('plugin_action_links_eelv-redirection/eelv_redirection.php', 'EELV_REDIRECTION\settings_link' );
\load_plugin_textdomain( 'eelv-redirection', false, 'eelv-redirection/languages' );

function add_submenu() {
    //add_menu_page('Redirection','redirection', 'manage_options', 'eelv_option_redirect', 'eelv_option_redirect' );
    \add_submenu_page('options-general.php', __('Redirection', 'eelv-redirection' ), __('Redirection', 'eelv-redirection' ), 'manage_options', 'eelv_redirection', 'EELV_REDIRECTION\settings_page');
}

function where_redirect(){
    $dir=basename(realpath('.'));
    if(defined( 'WP_CLI' ) && WP_CLI){
        return false;
    }
    return ($dir!='wp-admin' && $dir!='network' && !preg_match('#^/wp-(.*).php#', $_SERVER['SCRIPT_NAME']));
}

function get_redirect_url(){
    $redirect_url = get_option('eelv_url_redirect');
    if(strstr($redirect_url, '%query_string%')){
        $redirect_url =str_replace('%query_string%', $_SERVER['QUERY_STRING'], $redirect_url);
    }
    if(strstr($redirect_url, '%request_uri%')){
        $redirect_url =str_replace('%request_uri%', $_SERVER['REQUEST_URI'], $redirect_url);
    }
    return $redirect_url;
}

function mk_redirect() {
    if(where_redirect()){
      if(get_option('eelv_url_redirect')!='') {
        $eelv_when_redirect = get_option( 'eelv_when_redirect' ,'-1');
        if($eelv_when_redirect==0 || ($eelv_when_redirect==1 && !is_user_logged_in())){
            $eelv_code_redirect=get_option("eelv_code_redirect");
            if($eelv_code_redirect=='301') header("HTTP/1.1 301 Moved Permanently");
            header("location: ".get_redirect_url());
            exit();
        }
     }
  }
}
function mk_redirecthtml() {
    if(where_redirect()){ // only on front
    if(get_option('eelv_url_redirect')!='') {
    $eelv_when_redirect = get_option( 'eelv_when_redirect','-1');
    if($eelv_when_redirect==0 || ($eelv_when_redirect==1 && !is_user_logged_in())){ ?>
    <!-- redirection -->
 <?php if(get_option("eelv_code_redirect")=='301') : ?>
    <meta http-equiv="refresh" content="0; url=<?php echo get_redirect_url(); ?>"/>
 <?php else: ?>
    <link rel="canonical" href="<?php echo get_redirect_url(); ?>" />
<?php endif; ?>
    <script language="JavaScript">
     document.location.href="<?php echo get_redirect_url(); ?>"
    </script>
    <?php    }
    }
  }
}

function settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=eelv_redirection">' . __( 'Settings', 'eelv-redirection' ) . '</a>';
    // place it before other links
    array_unshift( $links, $settings_link );
    return $links;
}

function settings_page() {
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ 'eelv_url_redirect' ])) {
        update_option( 'eelv_url_redirect', esc_url_raw(filter_input(INPUT_POST, 'eelv_url_redirect', FILTER_SANITIZE_URL)));
        update_option( "eelv_code_redirect", sanitize_text_field($_POST[ 'eelv_code_redirect' ]));
        update_option( "eelv_when_redirect", sanitize_text_field($_POST[ 'eelv_when_redirect' ]));
    ?>
    <div class="updated"><p><strong><?php _e('Option saved','eelv-redirection')?></strong></p></div>
    <?php
    }

    $eelv_url_redirect = get_option( 'eelv_url_redirect' );
    $eelv_code_redirect = get_option( "eelv_code_redirect" );
    $eelv_when_redirect = get_option( "eelv_when_redirect",'-1');
    ?>
<div class="wrap">
    <h2>
        <?php _e('Simple Redirection', 'eelv-redirection' ); ?>
    </h2>

    <form name="form1" method="post" action="#">
        <p>
            <label for="eelv_when_redirect"><?php _e('When:','eelv-redirection')?></label>
            <select name="eelv_when_redirect" id="eelv_when_redirect">
                <option value="-1" <?php selected($eelv_when_redirect, -1, true); ?>><?php _e('Never (deactivated)','eelv-redirection')?></option>
                <option value="0" <?php selected($eelv_when_redirect, 0, true); ?>><?php _e('Always','eelv-redirection')?></option>
                <option value="1" <?php selected($eelv_when_redirect, 1, true); ?>><?php _e('Only non-logged-in users','eelv-redirection')?></option>
            </select>
        </p>
        <p>
            <label id="eelv_code_redirect"><?php _e('How:','eelv-redirection')?></label>
            <select name="eelv_code_redirect" id="eelv_code_redirect">
                        <option value="302" <?php selected($eelv_code_redirect, 302, true); ?>><?php _e('302 : Moved Temporarily','eelv-redirection')?></option>
                <option value="301" <?php selected($eelv_code_redirect, 301, true); ?>><?php _e('301 : Moved Permanently','eelv-redirection')?></option>
            </select>
        </p>
        <p>
            <label id='eelv_url_redirect'><?php _e('Where:','eelv-redirection')?></label>
            <input type="text" name='eelv_url_redirect' id='eelv_url_redirect' value="<?php esc_attr_e($eelv_url_redirect) ?>" size="50" placeholder="https://">
            <span class="text-description">
                <?php printf(__('You can use these variables in the redirect URL: %s','eelv-redirection'), '<code>%query_string%</code>, <code>%request_uri%</code>'); ?>
            </span>
        </p>
    <?php \submit_button(); ?>
    </form>
</div>

<?php

}

function adminbar($admin_bar) {
    if (current_user_can('manage_options')) {
        $redirect_url = get_option('eelv_url_redirect');
        $redirect_condition = (int) get_option( 'eelv_when_redirect' ,'-1');
        $redirect_code = (int) get_option( 'eelv_code_redirect' ,'-1');
        if(!empty($redirect_url) && $redirect_condition >= 0){
            $admin_bar->add_menu(
                array(
                    'id' => 'eelv-redirection',
                    'title' => '<span class="ab-icon dashicons dashicons-randomize"></span>',
                    'href' => add_query_arg(array('page'=>'eelv_redirection'), admin_url('options-general.php')),
                    'meta' => array(
                        'title' => sprintf(_x('%s redirection active', 'redirect code', 'eelv-redirection'), $redirect_code),
                    ),
                )
            );
        }
    }
}
