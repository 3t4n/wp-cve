<?php
/**
 *  Plugin Name: WordPress Social Login extends NAVER
 *  Description: WordPress Social Login 확장 플러그인입니다. ( 네이버, 카카오 로그인이 추가되었습니다. )
 *  Author: SIR Soft
 *  Author URI: http://sir.co.kr
 *  Version: 0.1.4
 *  Text Domain: SIR Soft
 */

/*
 * 아래코드는 http://www.usefulparadigm.com/2014/07/15/adding-kakao-login-to-wordpress/ 의 내용을 참고했습니다.
*/
if( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'WSL_LOGIN_EXTENDS_NAVER_KAKAO' ) ) :

Class WSL_LOGIN_EXTENDS_NAVER_KAKAO {

    public $dir_path = null;
    public $is_plugin_active = false;

    public function __construct() {

        $this->is_plugin_active = $this->is_wsl_active();

        add_action( 'plugins_loaded', array($this, 'start' ));

        add_action( 'admin_notices', array($this, 'plugin_activation_message'), 0 ) ;
        add_filter( 'wsl_hook_process_login_alter_wp_insert_user_data', array($this, 'user_login_check'), 10, 3 );

        $this->dir_path = plugin_dir_path( __FILE__ );
        $this->dir_url = plugin_dir_url( __FILE__ );
    }

    public function user_login_check( $userdata, $provider, $hybridauth_user_profile ){

        if( in_array(strtolower($provider), array('naver', 'kakao')) && isset($userdata['user_login']) ){
            $user_login = sanitize_user($userdata['user_login'], true);

            if( !$user_login ){

                if( !empty($userdata['user_email']) ){
                    $tmp = explode("@", $userdata['user_email']);
                    $user_login = $tmp[0];
                } else {
                    $user_login = $provider.'_'.substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 1, 8);
                }

                // user name should be unique
                if( username_exists( $user_login ) )
                {
                    $i = 1;
                    $user_login_tmp = $user_login;

                    do
                    {
                        $user_login_tmp = $user_login . "_" . ($i++);
                    }
                    while( username_exists ($user_login_tmp));

                    $user_login = $user_login_tmp;
                }

                if( $user_login ){
                    $userdata['user_login'] = $user_login;
                }
            }
        }

        return $userdata;
    }

    public function plugin_activation_message(){
        if ( ! $this->is_plugin_active ) :
            deactivate_plugins( plugin_basename( __FILE__ ) );			
            $html = '<div class="error">';
                $html .= '<p>';
                    $html .= __( '<strong>WordPress Social Login extends NAVER</strong> is enabled but not effective. It requires <strong>WordPress Social Login</strong> plugin.', 'wsl_login_extends_naver' );
                $html .= '</p>';
            $html .= '</div><!-- /.updated -->';
            echo $html;
            
        endif;
    }

    public function is_wsl_active(){
        $active_plugins = (array) get_option( 'active_plugins', array() );
        if ( is_multisite() )
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        return in_array( 'wordpress-social-login/wp-social-login.php', $active_plugins ) || array_key_exists( 'wordpress-social-login/wp-social-login.php', $active_plugins );
    }

    public function start(){

        //번역파일
        load_plugin_textdomain( 'wsl_login_extends_naver', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

        if( defined('WORDPRESS_SOCIAL_LOGIN_ABS_PATH') && function_exists('wsl_activate') ){   //WORDPRESS_SOCIAL_LOGIN 플러그인이 활성화 되어 있다면
            add_action( 'init', array($this, 'add_provider_to_wsl' ));
            add_filter( 'wsl_hook_alter_provider_config', array($this, 'hook_add_hybridauth_filter'), 10, 2 );
            add_action( 'wsl_component_networks_setup_end', array($this, 'add_script') );
            add_action( 'wsl_component_loginwidget_setup_sections', array($this, 'add_widget_script') );
            add_action( 'wsl_render_auth_widget_end', array($this, 'add_widget_script') );
        }

    }

    public function add_script(){
        wp_enqueue_script('jquery');
        add_action( 'admin_footer', array( $this, 'echo_script' ), 36 );
    }

    public function add_widget_script(){
        wp_enqueue_script('jquery');
        add_action( 'wp_footer', array( $this, 'icon_view_script' ), 37 );
        add_action( 'login_footer', array( $this, 'icon_view_script' ), 37 );
        add_action( 'admin_footer', array( $this, 'icon_view_script' ), 37 );
    }

    public function echo_script(){
        $naver_label_src = $this->dir_url.'assets/img/16x16/Naver.png';
        $naver_inside_src = $this->dir_url.'assets/img/32x32/icondock/Naver.png';
        $kakao_label_src = $this->dir_url.'assets/img/16x16/kakao.png';
        $kakao_inside_src = $this->dir_url.'assets/img/32x32/icondock/kakao.png';
    ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    jQuery(document).ready(function($) {
        $(".wp-neworks-label").find('img[alt="Kakao"]').attr("src", "<?php echo $kakao_label_src; ?>");
        $(".inside").find('img[alt="Kakao"]').attr("src", "<?php echo $kakao_inside_src; ?>");

        $(".wp-neworks-label").find('img[alt="Naver"]').attr("src", "<?php echo $naver_label_src; ?>");
        $(".inside").find('img[alt="Naver"]').attr("src", "<?php echo $naver_inside_src; ?>");
    });
    /* ]]> */
    </script>
    <?php
    }

    public function icon_view_script(){
        $naver_provier_src = $this->dir_url.'assets/img/32x32/wpzoom/Naver.png';
        $kakao_provier_src = $this->dir_url.'assets/img/32x32/wpzoom/kakao.png';
    ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    jQuery(document).ready(function($) {
        $(".wp-social-login-provider-list").find('img[alt="Naver"]').attr("src", "<?php echo $naver_provier_src; ?>");
        $(".wp-social-login-provider-list").find('img[alt="Kakao"]').attr("src", "<?php echo $kakao_provier_src; ?>");
    });
    /* ]]> */
    </script>
    <?php
    }

    public function add_provider_to_wsl() {
        global $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG;

        $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG[] = ARRAY(
            "provider_id"       => "Naver",
            "provider_name"     => "Naver",
            "require_client_id" => true,
            "callback"          => true,
            "new_app_link"      => "https://nid.naver.com/devcenter/register.nhn",
            "cat"               => "socialnetworks",
        );

        $WORDPRESS_SOCIAL_LOGIN_PROVIDERS_CONFIG[] = ARRAY(
            "provider_id"       => "Kakao",
            "provider_name"     => "Kakao",
            "require_client_id" => true,
            "callback"          => true,
            "new_app_link"      => "https://developers.kakao.com/apps/new",
            "cat"               => "socialnetworks",
        );
    }

    public function hook_add_hybridauth_filter($config_var, $provider){

        if( $provider === 'Naver' || $provider === 'Kakao' ){
            $config_var['wrapper'] = array(
                    'path'  =>  $this->dir_path.'providers/'.$provider . '.php',
                    'class' =>  'Hybrid_Providers_'.$provider,
                );
        }

        return $config_var;
    }

}   //end Class

New WSL_LOGIN_EXTENDS_NAVER_KAKAO();

endif;  //Class exists WSL_LOGIN_EXTENDS_NAVER_KAKAO end if
?>