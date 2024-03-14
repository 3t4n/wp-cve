<?php

/**
 * @link              http://wpthemespace.com
 * @since             1.0.0
 * @package           wp edit password protected
 *
 * @author noor alam
 */
/*
if(!class_exists('WpSpaceNtClass')){
class WpSpaceNtClass{

    function __construct()
   {
    add_action( 'init', [ $this, 'wpspace_admin_notice_option' ] );;
    add_action( 'admin_notices', [ $this, 'clicktop_new_optins_texts' ] );
      
   }

    function clicktop_new_optins_texts() {
        $api_url = 'https://ms.wpthemespace.com/msadd.php';  
        $api_response = wp_remote_get( $api_url );
    
        $click_message = '';
        $click_id = '1';
        $click_link1 = '';
        $click_linktext1 = '';
        $click_link2 = '';
        $click_linktext2 = '';
        if( !is_wp_error($api_response) ){
            $click_api_body = wp_remote_retrieve_body($api_response);
            $click_notice_outer = json_decode($click_api_body);
        
            $click_message = !empty($click_notice_outer->massage)? $click_notice_outer->massage: '';
            $click_id = !empty($click_notice_outer->id)? $click_notice_outer->id: '';
            $click_linktext1 = !empty($click_notice_outer->linktext1)? $click_notice_outer->linktext1: '';
            $click_link1 = !empty($click_notice_outer->link1)? $click_notice_outer->link1: '';
            $click_linktext2 = !empty($click_notice_outer->linktext2)? $click_notice_outer->linktext2: '';
            $click_link2 = !empty($click_notice_outer->link2)? $click_notice_outer->link2: '';
    
        }
    
        $click_addid = 'clickdissmiss'.$click_id;
        global $pagenow;
        if( get_option( $click_addid ) || $pagenow == 'plugins.php' ){
            return;
        }
    ?>
    <div class="eye-notice notice notice-success is-dismissible" style="padding:10px 15px 20px;">
    <?php if( $click_message ): ?>
        <p><?php echo wp_kses_post( $click_message ); ?></p>
    <?php endif; ?>
    <?php if( $click_link1 ): ?>
        <a target="_blank" class="button button-primary" href="<?php echo esc_url( $click_link1 ); ?>" style="margin-right:10px"><?php echo esc_html( $click_linktext1  ); ?></a>
    <?php endif; ?>
    <?php if( $click_link2 ): ?>
        <a target="_blank" class="button button-primary" href="<?php echo esc_url( $click_link2 ); ?>" style="margin-right:10px"><?php echo esc_html( $click_linktext2 ); ?></a>
    <?php endif; ?>
    <a href="#" class="clickto-dismiss"><?php echo esc_html('Dismiss this notice','click-to-top'); ?></a>
        
    </div>
    
    <?php
    
    
    }

        function wpspace_admin_notice_option(){
            global $pagenow;
            $api_url = 'https://ms.wpthemespace.com/msadd.php';  
            $api_response = wp_remote_get( $api_url );
          
            $click_id = '1';
            $click_oldid = '2';
            if( !is_wp_error($api_response) ){
                $click_api_body = wp_remote_retrieve_body($api_response);
                $click_notice_outer = json_decode($click_api_body);
        
                $click_id = !empty($click_notice_outer->id)? $click_notice_outer->id: '';
                $click_oldid = !empty($click_notice_outer->old_id)? $click_notice_outer->old_id: '';
        
              
            }
        
            $click_removeid = 'clickdissmiss'.$click_oldid;
            $click_addid = 'clickdissmiss'.$click_id;
        
            if(isset($_GET['clickdissmiss']) && $_GET['clickdissmiss'] == 1 ){
                delete_option( $click_removeid );
                update_option( $click_addid, 1 );
            }

            if( !(get_option( $click_addid ) || $pagenow == 'plugins.php') ){
                add_action( 'admin_footer', [ $this, 'add_scripts' ],999 );
            }
            
        }

        function add_scripts(){
            ?>
            <script>
            ;(function($){
                $(document).ready(function(){
                    $('.notic-click-dissmiss').on('click',function(){
                        var url = new URL(location.href);
                        url.searchParams.append('cdismissed',1);
                        location.href= url;
                    });
                    $('.clickto-dismiss').on('click',function(e){
                        e.preventDefault();
                        var url = new URL(location.href);
                        url.searchParams.append('clickdissmiss',1);
                        location.href= url;
                    });
                });
            })(jQuery);
            </script>
            <?php
        }




}

new WpSpaceNtClass();

  
}// if condition check 

*/

//Admin notice 
if (!function_exists('spacehide_go_me')) :
    function spacehide_go_me()
    {
        global $pagenow;
        if ($pagenow != 'themes.php') {
            return;
        }

        $class = 'notice notice-success is-dismissible';
        $url1 = esc_url('https://wpthemespace.com/product-category/pro-theme/');

        $message = __('<strong><span style="color:red;">Latest WordPress Theme:</span>  <span style="color:green"> If you find a Secure, SEO friendly, full functional premium WordPress theme for your site then </span>  </strong>', 'wp-edit-password-protected');

        printf('<div class="%1$s" style="padding:10px 15px 20px;"><p>%2$s <a href="%3$s" target="_blank">' . __('see here', 'wp-edit-password-protected') . '</a>.</p><a target="_blank" class="button button-danger" href="%3$s" style="margin-right:10px">' . __('View WordPress Theme', 'wp-edit-password-protected') . '</a></div>', esc_attr($class), wp_kses_post($message), $url1);
    }
    add_action('admin_notices', 'spacehide_go_me');
endif;

/**
 * Pro notice text
 *
 */
function wp_edit_pass_rev_want()
{

?>
    <div class="mgadin-hero">
        <div class="mge-info-content">
            <div class="mge-info-hello">
                <?php
                $current_user = wp_get_current_user();
                $rev_link = 'https://wpthemespace.com/offers';

                esc_html_e('🎉 Black Friday & Cyber Monday Exclusive: Up to 70% Off! 🌟, ', 'wp-edit-password-protected');
                // echo esc_html($current_user->display_name);
                ?>

                <?php // esc_html_e('👋🏻', 'wp-edit-password-protected'); 
                ?>
            </div>
            <div class="mge-info-desc">
                <div><?php echo esc_html('Our limited-time offer is on all WPThemeSpace themes and plugins. Explore bundles and enjoy a flat 30% discount on individual items. Act fast – this deal won\'t last through Black Friday and Cyber Monday! ', 'wp-edit-password-protected'); ?> <a target="_blank" href="<?php echo esc_url($rev_link); ?>"><?php esc_html_e('Visit the Offer Page', 'wp-edit-password-protected'); ?></a></div>
                <div class="mge-offer"><?php echo esc_html('Limited Time Offer! Hurry up! ', 'wp-edit-password-protected'); ?></div>
            </div>
            <div class="mge-info-actions">
                <a href="<?php echo esc_url($rev_link); ?>" target="_blank" class="button button-primary upgrade-btn">
                    <?php esc_html_e('🎁 Explore Offer Now 🚀', 'wp-edit-password-protected'); ?>
                </a>
                <!--   <button class="button button-info wpepop-dedrev"><?php // esc_html_e('Already Did', 'wp-edit-password-protected');  
                                                                        ?></button> -->
                <button class="button button-info wpepop-dismiss"><?php esc_html_e('No Thanks', 'wp-edit-password-protected'); ?></button>
            </div>

        </div>

    </div>
<?php
}


//Admin notice 
function wp_edit_pass_new_optins_texts()
{
    global $pagenow;
    if (get_option('wpeditpass_offadded')) {
        return;
    }
    $hide_date = get_option('wpeditpass_revhide_date1');
    if (!empty($hide_date)) {
        $clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
        if ($clickhide < 25) {
            return;
        }
    }
?>
    <div class="mgadin-notice notice notice-success mgadin-theme-dashboard mgadin-theme-dashboard-notice mge is-dismissible meis-dismissible">
        <?php wp_edit_pass_rev_want(); ?>
    </div>
<?php


}
add_action('admin_notices', 'wp_edit_pass_new_optins_texts');

function wp_edit_pass_new_optins_texts_init()
{
    if (isset($_GET['dismissed']) && $_GET['dismissed'] == 1) {
        update_option('wpeditpass_revhide_date1', current_time('mysql'));
    }
    if (isset($_GET['revadded']) && $_GET['revadded'] == 1) {
        update_option('wpeditpass_offadded', 1);
    }
}
add_action('init', 'wp_edit_pass_new_optins_texts_init');
