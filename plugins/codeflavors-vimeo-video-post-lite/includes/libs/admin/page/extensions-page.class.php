<?php
/**
 * @author CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Extensions\Extension_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * @ignore
 */
class Extensions_Page extends Page_Abstract implements Page_Interface{

	/**
	 * Outputs the page
	 *
	 * @return string|void
	 */
	public function get_html() {
		$extensions = parent::get_admin()->get_extensions()->get_registered_extensions();
?>
<div class="wrap vimeotheque vimeotheque-addons-wrap">
	<h1><?php _e( 'Add-ons', 'codeflavors-vimeo-video-post-lite' );?></h1>
	<div class="container">
	<?php foreach( $extensions as $extension ):?>
	<?php
		$classes = ['extension'];
		$classes[] = $extension->is_installed() ? 'is-installed' : 'not-installed';
		$classes[] = $extension->is_activated() ? 'active' : 'inactive';
		$classes[] = $extension->is_pro_addon() ? 'pro-addon' : 'free-addon';
		$data = $extension->get_plugin_data();
	?>
		<div class="<?php echo implode( ' ', $classes );?>">
            <div class="inside">
                <h2>
                    <?php
                        printf(
                            '%s %s',
                            $extension->get_name(),
                            $data ? $data['Version'] : ''
                        );
                    ?>
	                <?php if( $extension->is_pro_addon() ):?>
                        <div class="pro-emblem">PRO</div>
	                <?php endif;?>
                </h2>
                <p><?php echo $extension->get_description();?></p>
                <?php
                    if( !$extension->is_installed() ){
                        if( !$extension->is_pro_addon() ) {
	                        printf(
		                        '<a class="action install" href="%s">%s</a>',
		                        $extension->install_url(),
		                        __( 'Install', 'codeflavors-vimeo-video-post-lite' )
	                        );
                        }else {
	                        /**
	                         * Run action for each extension installation.
	                         * @ignore
                             *
	                         * @param Extension_Interface $extension The extension object.
	                         */
	                        echo apply_filters(
		                        'vimeotheque\admin\page\extensions\pro_installation_message',
		                        sprintf(
		                            '<div class="requires-pro"><span class="dashicons dashicons-warning"></span> %s</div>',
                                    __( 'Requires Vimeotheque PRO', 'codeflavors-vimeo-video-post-lite' )
                                ),
		                        $extension
	                        );
                        }

                    }elseif( !$extension->is_activated() ){
                        printf(
                            '<a class="action activate" href="%s">%s</a>',
                            $extension->activation_url(),
                            __( 'Activate', 'codeflavors-vimeo-video-post-lite' )
                        );
                    }else{ // extensiton is active, show deactivation option
                        printf(
	                        '<a class="action deactivate" href="%s">%s</a>',
	                        $extension->deactivation_url(),
	                        __( 'Deactivate', 'codeflavors-vimeo-video-post-lite' )
                        );
                    }
                ?>
                <?php $this->show_update_message( $extension )?>
            </div>
		</div>
	<?php endforeach;?>
	</div>
</div>
<?php
	}

	/**
	 * Page on load event
	 *
	 * @return mixed|void
	 */
	public function on_load() {
		wp_enqueue_style( 'vimeotheque-extensions-css', VIMEOTHEQUE_URL . 'assets/back-end/css/extensions.css' );
	}

	/**
	 * @param Extension_Interface $extension
	 */
	private function show_update_message( Extension_Interface $extension ){
	    if( !$extension->is_installed() ){
	        return;
        }

		$plugins = get_site_transient( 'update_plugins' );
		if ( isset( $plugins->response ) && is_array( $plugins->response ) ) {
            if( isset( $plugins->response[ $extension->get_file() ] ) ){
                $data = $extension->get_plugin_data();
                $update = $plugins->response[ $extension->get_file() ];
                if( version_compare( $data['Version'], $update->new_version, '<' ) ){
                    $message = sprintf(
                        '<div class="update-notice">%s <a class="update" href="%s">%s</a>.</div>',
                        sprintf(
                            __( '%s version %s is available.', 'codeflavors-vimeo-video-post-lite' ),
                            $data['Name'],
                            $update->new_version
                        ),
                        $extension->upgrade_url(),
                        __( 'Update now', 'codeflavors-vimeo-video-post-lite' )
                    );

	                /**
	                 * Filter the update message.
                     * @ignore
                     *
                     * @param string $message                   The update message
                     * @param Extension_Interface $extension    The extension being displayed
                     * @param \stdClass $update                 The update object from WP
	                 */
                    echo apply_filters(
                        'vimeotheque\admin\page\extensions\update_message',
                        $message,
                        $extension,
                        $update
                    );
                }
            }
        }
    }
}