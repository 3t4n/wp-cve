<?php
/**
 * @author CodeFlavors
 * @project Vimeotheque 2.0 Lite
 */

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Helper;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Status_Page
 *
 * @package Vimeotheque\Admin\Page
 * @ignore
 */
class Status_Page extends Page_Abstract implements Page_Interface {

	/**
	 * @inheritDoc
	 */
	public function get_html() {
	    $wp_info = $this->get_wordpress_info();
	    $server_info = $this->get_server_info();
	    $theme_info = $this->get_theme_info();
	    $options = $this->get_plugin_options();
	    $embed_options = $this->get_embed_options();

		/**
		 * Filter that allows additional options and variables.
         *
         * @param array $options The options to be rgistered.
		 */
	    $other_options = apply_filters( 'vimeotheque\admin\status\other_options', [] );
	    $extensions = parent::get_admin()->get_extensions()->get_registered_extensions();
?>
		<div class="wrap">
			<h1><?php _e( 'System status', 'codeflavors-vimeo-video-post-lite' );?></h1>
            <div class="status-report">
                <div class="export">
                    <textarea id="vimeotheque-report"></textarea>
                </div>
                <div class="command" data-alt-text="<?php esc_attr_e( 'Copy the report and paste it into the support ticket.', 'codeflavors-vimeo-video-post-lite' );?>">
                    <button id="vimeotheque-status-copy" class="button"><?php _e( 'Create report', 'codeflavors-vimeo-video-post-lite' );?></button>
                    <span><?php _e( 'Click to create report and paste the result into the support ticket.', 'codeflavors-vimeo-video-post-lite' );?></span>
                </div>
            </div>
            <div id="status-display">
                <h2 data-export-label="WordPress info"><?php _e('WordPress', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row" data-export-label="WP URL"><?php _e( 'WP URL', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( get_bloginfo('wpurl') );?>"><?php echo get_bloginfo('wpurl');?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="Site address"><?php _e( 'Site address', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( get_bloginfo('url') );?>"><?php echo get_bloginfo('url');?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="Vimeotheque version"><?php _e( 'Vimeotheque version', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( VIMEOTHEQUE_VERSION );?>"><?php echo VIMEOTHEQUE_VERSION;?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="WordPress version"><?php _e( 'WordPress version', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $wp_info['version'] );?>"><?php $this->translate( $wp_info['version'] );?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="WordPress memory limit"><?php _e( 'WordPress memory limit', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $wp_info['memory_limit'] );?>"><?php $this->translate( $wp_info['memory_limit'] );?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="WordPress debug mode"><?php _e( 'WordPress debug mode', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $wp_info['debug_mode'] );?>"><?php $this->translate( $wp_info['debug_mode'] );?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="Language"><?php _e( 'Language', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $wp_info['locale'] );?>"><?php $this->translate( $wp_info['locale'] );?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="WordPress multisite"><?php _e( 'WordPress multisite', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $wp_info['multisite'] );?>"><?php $this->translate( $wp_info['multisite'] );?></td>
                    </tr>
                    </tbody>
                </table>

                <h2 data-export-label="Server information"><?php _e('Server', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row" data-export-label="Server"><?php _e( 'Server info', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['software'] );?>"><?php echo $server_info['software'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="PHP version"><?php _e( 'PHP version', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['php_version'] );?>"><?php echo $server_info['php_version'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="PHP post max size"><?php _e( 'PHP post max size', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['php_post_max_size'] );?>"><?php echo $server_info['php_post_max_size'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="PHP time limit"><?php _e( 'PHP time limit', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['php_time_limit'] );?>"><?php echo $server_info['php_time_limit'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="PHP max input vars"><?php _e( 'PHP max input vars', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['php_max_input_vars'] );?>"><?php echo $server_info['php_max_input_vars'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="PHP default timezone"><?php _e( 'PHP default timezone', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['php_default_timezone'] );?>"><?php echo $server_info['php_default_timezone'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="PHP cURL"><?php _e( 'PHP cURL', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['php_curl'] );?>"><?php $this->translate( $server_info['php_curl'] );?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="cURL version"><?php _e( 'cURL version', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $server_info['curl_version'] );?>"><?php echo $server_info['curl_version'];?></td>
                    </tr>
                    </tbody>
                </table>
                <h2 data-export-label="Active theme information"><?php _e('Theme', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <tr>
                        <th scope="row" data-export-label="Active theme"><?php _e( 'Theme', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $theme_info['name'] );?>"><?php echo $theme_info['name'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="Theme version"><?php _e( 'Version', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $theme_info['version'] );?>"><?php echo $theme_info['version'];?></td>
                    </tr>
                    <tr>
                        <th scope="row" data-export-label="Child theme"><?php _e( 'Child theme', 'codeflavors-vimeo-video-post-lite' );?></th>
                        <td data-value="<?php echo esc_attr( $theme_info['child_theme'] );?>"><?php $this->translate( $theme_info['child_theme'] );?></td>
                    </tr>
                    </tbody>
                </table>
                <h2 data-export-label="Plugin settings"><?php _e('Plugin settings', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <?php foreach( $options as $k => $v ):?>
                    <tr>
                        <th scope="row" data-export-label="<?php echo  ucfirst( str_replace( '_', ' ', $k ) ) . " ({$k})" ;?>"><?php echo  ucfirst( str_replace( '_', ' ', $k ) ) ;?></th>
                        <td data-value="<?php echo esc_attr( $v );?>"><?php $this->translate( $v );?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <h2 data-export-label="Embed settings"><?php _e('Embed settings', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <?php foreach( $embed_options as $k => $v ):?>
                    <tr>
                        <th scope="row" data-export-label="<?php echo  ucfirst( str_replace( '_', ' ', $k ) ) . " ({$k})" ;?>"><?php echo  ucfirst( str_replace( '_', ' ', $k ) ) ;?></th>
                        <td data-value="<?php echo esc_attr( $v );?>"><?php $this->translate( $v );?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <?php if( $other_options && is_array( $other_options ) ):?>
                <h2 data-export-label="Other settings"><?php _e('Other', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <?php foreach( $other_options as $k => $v ):?>
                        <tr>
                            <th scope="row" data-export-label="<?php echo  ucfirst( str_replace( '_', ' ', $k ) ) . " ({$k})" ;?>"><?php echo  ucfirst( str_replace( '_', ' ', $k ) ) ;?></th>
                            <td data-value="<?php echo esc_attr( $v );?>"><?php $this->translate( $v );?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <?php endif;?>

                <?php if( $extensions && is_array( $extensions ) ): ?>
                <h2 data-export-label="Add-ons"><?php _e('Add-ons', 'codeflavors-vimeo-video-post-lite' );?></h2>
                <table class="form-table">
                    <tbody>
                    <?php
                    foreach( $extensions as $extension ):
                        if( !$extension->is_installed() ) {
	                        continue;
                        }
                        $message = sprintf(
                            '%s (version %s)',
	                        $extension->is_activated() ? 'Active' : 'Inactive',
                            $extension->get_plugin_data()['Version']
                        );
                    ?>
                        <tr>
                            <th scope="row" data-export-label="<?php echo  $extension->get_name();?>"><?php echo  $extension->get_name() ;?></th>
                            <td data-value="<?php echo $message;?>"><?php $extension->is_activated() ? _e( 'Active', 'codeflavors-vimeo-video-post-lite' ) : _e( 'Inactive', 'codeflavors-vimeo-video-post-lite' ) ?></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                <?php endif; ?>

            </div>
		</div>
<?php
	}

	/**
	 * @inheritDoc
	 */
	public function on_load() {
		wp_enqueue_style(
		    'vimeotheque-status',
            VIMEOTHEQUE_URL . 'assets/back-end/css/status.css'
        );

		wp_enqueue_script(
		    'vimeotheque-status',
            VIMEOTHEQUE_URL . 'assets/back-end/js/status-page.js',
            ['jquery']
        );
	}

	/**
	 * Get server related info.
	 *
	 * @return array
	 */
	private function get_server_info() {
		$server_data = [];

		if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
		}else{
		    $server_data['software'] = 'unknown';
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data['php_version'] = phpversion();
		}else{
		    $server_data['php_version'] = 'unknown';
		}

		if ( function_exists( 'ini_get' ) ) {
			$server_data['php_post_max_size'] = ini_get( 'post_max_size' );
			$server_data['php_time_limit']  = ini_get( 'max_execution_time' );
			$server_data['php_max_input_vars'] = ini_get( 'max_input_vars' );
		}else{
			$server_data['php_post_max_size'] = 'unknown';
			$server_data['php_time_limit'] = 'unknown';
			$server_data['php_max_input_vars'] = 'unknown';
		}

		$server_data['php_default_timezone'] = date_default_timezone_get();
		$server_data['php_curl'] = function_exists( 'curl_init' ) ? 'Yes' : 'No';

		// Figure out cURL version, if installed.
		$curl_version = '';
		if ( function_exists( 'curl_version' ) ) {
			$curl_version = curl_version();
			$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
		} elseif ( extension_loaded( 'curl' ) ) {
			$curl_version = __( 'cURL installed but unable to retrieve version.', 'codeflavors-vimeo-video-post-lite' );
		}

		$server_data['curl_version'] = $curl_version;

		return $server_data;
	}

	/**
	 * Get the current theme info, theme name and version.
	 *
	 * @return array
	 */
	private function get_theme_info() {
		$theme_data        = wp_get_theme();
		$theme_child_theme = is_child_theme();

		return [
			'name'        => $theme_data->Name, // @phpcs:ignore
			'version'     => $theme_data->Version, // @phpcs:ignore
			'child_theme' => $theme_child_theme ? 'Yes' : 'No',
		];
	}

	/**
	 * Get WordPress related data.
	 *
	 * @return array
	 */
	private function get_wordpress_info() {
		$wp_data = [];

		$wp_data['memory_limit'] = WP_MEMORY_LIMIT;
		$wp_data['debug_mode']   = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
		$wp_data['locale']       = get_locale();
		$wp_data['version']      = get_bloginfo( 'version' );
		$wp_data['multisite']    = is_multisite() ? 'Yes' : 'No';

		return $wp_data;
	}

	/**
	 * @return array
	 */
	private function get_plugin_options(){
	    $options = Plugin::instance()->get_options();
	    // set a fake option to check if api keys are set
	    $options['vimeo_api_keys'] = !empty( $options['vimeo_consumer_key'] ) && !empty( $options['vimeo_secret_key'] );

	    unset( $options['vimeo_consumer_key'] );
	    unset( $options['vimeo_secret_key'] );
	    unset( $options['oauth_token'] );
	    unset( $options['oauth_secret'] );

	    foreach ( $options as $k => $v ){
	        if( is_bool( $v ) ){
	            $options[ $k ] = $v ? 'Yes' : 'No';
            }elseif( is_array( $v ) ){
	            $options[ $k ] = implode( ', ', $v );
            }
        }

	    return $options;
    }

	/**
	 * @return array
	 */
	private function get_embed_options(){
	    $options = Helper::get_embed_options();

	    foreach ( $options as $k => $option ) {
            if( is_bool( $option ) ){
                $options[ $k ] = $option ? 'Yes' : 'No';
            }
	    }
	    
	    return $options;
    }

	/**
	 * @param $value
	 *
	 * @param bool $echo
	 *
	 * @return string|void
	 */
    private function translate( $value, $echo  = true ){
	    $response = $value;
	    if( 'Yes' == $value ){
	        $response =  __( 'Yes', 'codeflavors-vimeo-video-post-lite' );
	    }elseif( 'No' == $value ){
	        $response =  __( 'No', 'codeflavors-vimeo-video-post-lite' );
        }

	    if( $echo ){
	        echo $response;
	    }

	    return $response;
    }
}