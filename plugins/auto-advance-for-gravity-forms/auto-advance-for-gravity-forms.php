<?php

/*
Plugin Name: Auto Advance for GravityForms
Description:  The Auto Advance plugin for Gravity Forms makes the form filling process quicker and more user friendly for visitors. The plugin gives an easy way to choose which field(s) trigger an auto advance to the next step of your form.
Version: 4.6.3
Author: Frog Eat Fly
Tested up to: 6.0.2
Author URI: https://www.multipagepro.com/
*/
define( 'AAFG_PRO_PLAN_NAME', 'autoadvanceforgravityformspro' );
define( 'AAFG_PLUS_PLAN_NAME', 'autoadvanceforgravityformsplus' );
define( 'ZZD_AAGF_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZZD_AAGF_URL', plugin_dir_url( __FILE__ ) );
define( 'AUTO_ADVANCED_ZZD', '4.6.3' );
define( 'AUTO_ADVANCED_ASSETS', time() );

if ( !function_exists( 'aafgf_fs' ) ) {
    // Create a helper function for easy SDK access.
    function aafgf_fs()
    {
        global  $aafgf_fs ;
        
        if ( !isset( $aafgf_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $aafgf_fs = fs_dynamic_init( array(
                'id'             => '6159',
                'slug'           => 'auto-advance-for-gravity-forms',
                'type'           => 'plugin',
                'public_key'     => 'pk_03c636a8e7786094d99a1bf5e2e43',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'first-path' => 'plugins.php',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $aafgf_fs;
    }
    
    // Init Freemius.
    aafgf_fs();
    // Signal that SDK was initiated.
    do_action( 'aafgf_fs_loaded' );
    aafgf_fs()->add_filter( 'pricing_url', 'aafgf_upgrade_url' );
    function aafgf_upgrade_url( $url )
    {
        $modified_url = "https://www.multipagepro.com/";
        return $modified_url;
    }

}

add_action( 'gform_loaded', array( 'GF_Auto_Advanced_AddOn', 'load' ), 5 );
class GF_Auto_Advanced_AddOn
{
    public static function load()
    {
        if ( !method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
        require_once ZZD_AAGF_DIR . 'php/class-gfautoadvancedaddon.php';
        GFAddOn::register( 'GFAutoAdvancedAddOn' );
    }

}
add_action( 'plugins_loaded', 'load_aagf_languages', 0 );
function load_aagf_languages()
{
    load_plugin_textdomain( 'gf-autoadvanced', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function aafg_simple_addon()
{
    return GFAutoAdvancedAddOn::get_instance();
}

function aagf_fn_validate_required_plugins()
{
    if ( !method_exists( 'GFForms', 'include_payment_addon_framework' ) ) {
        return false;
    }
    return true;
}

function aagf_fn_admin_notice()
{
    $show = false;
    if ( aafgf_fs()->is_not_paying() ) {
        $show = true;
    }
    
    if ( isset( $_GET['show_notices'] ) ) {
        delete_transient( 'aafg-notice' );
        $show = true;
    }
    
    
    if ( !aagf_fn_validate_required_plugins() ) {
        ?>
		<div id="aafg-notice-error" class="aafg-notice-error notice notice-error">
			<div class="notice-container">
				<span> <?php 
        _e( "Auto Advanced Needs GravityForms Active", "gf-autoadvanced" );
        ?></span>
			</div>
		</div>
		<?php 
    } else {
        
        if ( $show && false == get_transient( 'aafg-notice' ) && current_user_can( 'install_plugins' ) ) {
            ?>
    <div id="aafg-notice" class="aafg-notice notice is-dismissible ">
		<div class="notice-container">
			<div class="notice-image">
				<img src="<?php 
            echo  ZZD_AAGF_URL ;
            ?>/images/icon.png" class="custom-logo" alt="AAFG">
			</div> 
			<div class="notice-content">
				<div class="notice-heading">
					<?php 
            _e( "Hi there, Thanks for using Multi Page Auto Advanced for Gravity Forms", "gf-autoadvanced" );
            ?>
				</div>
				<?php 
            _e( "Did you know our PRO version includes the ability to use the auto advance functionality conditionally per selection? Check it out!", "gf-autoadvanced" );
            ?>  <br>
				<div class="aafg-review-notice-container">
					<a href="https://gformsdemo.com/gravity-forms-auto-advance-demo/#multipro" class="aafg-notice-close aafg-review-notice button-primary" target="_blank">
					<?php 
            _e( "See The Demo", "gf-autoadvanced" );
            ?>
					</a>
					
				<span class="dashicons dashicons-smiley"></span>
					<a href="#" class="aafg-notice-close notice-dis aafg-review-notice">
					<?php 
            _e( "Dismiss", "gf-autoadvanced" );
            ?>
					</a>
				</div>
			</div>				
		</div>
	</div>
    <?php 
        }
    
    }
    
    echo  '<style>.notice-container{padding-top:10px;padding-bottom:10px;display:flex;justify-content:left;align-items:center;}.notice-image img{max-width:90px;}.notice-content{margin-left:15px;}.notice-content.notice-heading{padding-bottom:5px;}.aafg-review-notice-container a{padding-left:5px;text-decoration:none;}.aafg-review-notice-container{display:flex;align-items:center;padding-top:10px;}.aafg-review-notice-container.dashicons{font-size:1.4em;padding-left:10px;}</style>' ;
}

add_action( 'admin_notices', 'aagf_fn_admin_notice' );
add_action( 'wp_ajax_aafg-notice-dismiss', 'aafg_ajax_fn_dismiss_notice' );
function aafg_ajax_fn_dismiss_notice()
{
    $notice_id = ( isset( $_POST['notice_id'] ) ? sanitize_key( $_POST['notice_id'] ) : '' );
    $repeat_notice_after = 60 * 60 * 24;
    if ( !empty($notice_id) ) {
        
        if ( !empty($repeat_notice_after) ) {
            set_transient( $notice_id, true, $repeat_notice_after );
            wp_send_json_success();
        }
    
    }
}

add_action( "admin_footer", "aafg_footer_script" );
function aafg_footer_script()
{
    $admin_url = admin_url( "admin-ajax.php" );
    echo  "<script type='text/javascript'>\n\t\tvar \$ = jQuery;\n\t\tvar admin_url_zzd = '" . $admin_url . "';\n\t\tjQuery(document).on('click', '#aafg-notice .notice-dis', function(){ \n\t\t\t\$( this ).parents('#aafg-notice').find('.notice-dismiss').click();\n\t\t});\n\t\tjQuery(document).on('click', '#aafg-notice .notice-dismiss', function(){ \n\t\t\t\n\t\t\tvar notice_id = \$( this ).parents('#aafg-notice').attr( 'id' ) || '';\n\t\t\tjQuery.ajax({\n\t\t\t\turl: admin_url_zzd,\n\t\t\t\ttype: 'POST',\n\t\t\t\tdata: {\n\t\t\t\t\taction            : 'aafg-notice-dismiss',\n\t\t\t\t\tnotice_id         : notice_id,\n\t\t\t\t},\n\t\t\t});\n\t\t});\n\t</script>" ;
}
