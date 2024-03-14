<?php

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Admin\Table\Video_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Modal Videos List page 
 * @author CodeFlavors
 * @ignore
 */
class List_Videos_Page extends Page_Abstract implements Page_Interface{
	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::get_html()
	 */
	public function get_html(){
		$screen = get_current_screen();
		_wp_admin_html_begin();
		printf('<title>%s</title>', __('Video list', 'codeflavors-vimeo-video-post-lite'));
		wp_enqueue_style( 'colors' );
		wp_enqueue_style( 'ie' );
		wp_enqueue_script( 'utils' );
		wp_enqueue_script( 'buttons' );
		
		wp_enqueue_style(
			'cvm-video-list-modal',
			VIMEOTHEQUE_URL . 'assets/back-end/css/video-list-modal.css',
			false,
			'1.0'
		);
				
		wp_enqueue_script(
			'cvm-video-list-modal',
			VIMEOTHEQUE_URL . 'assets/back-end/js/video-list-modal.js',
			[ 'jquery' ],
			'1.0'
		);
		/**
		 * @ignore
		 */
		do_action('admin_print_styles');
		/**
		 * @ignore
		 */
		do_action('admin_print_scripts');
		/**
		 * Action triggered on loading the video modal window
         * @ignore
		 */
		do_action('vimeotheque\admin\video_list_modal_print_scripts');
		echo '</head>';
		echo '<body class="wp-core-ui">';
				
		$table = new Video_List_Table();
		$table->prepare_items();
		?>
		<div class="wrap">
			<form method="get" action="" id="cvm-video-list-form">
				<?php $table->views();?>
				<input type="hidden" name="view" value="<?php echo isset( $_REQUEST['view'] ) ? esc_attr( $_REQUEST['view'] ) : '';?>" />
				<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] );?>" />
				<?php $table->search_box( __('Search', 'codeflavors-vimeo-video-post-lite'), 'video' );?>
				<?php $table->display();?>
			</form>
			<div id="cvm-shortcode-atts"></div>
		</div>	
		<?php
		
		echo '</body>';
		echo '</html>';
		die();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Page_Interface::on_load()
	 */
	public function on_load(){
		$_GET['noheader'] = 'true';
		if( !defined('IFRAME_REQUEST') ){
			define('IFRAME_REQUEST', true);
		}
		
		if( isset( $_GET['_wp_http_referer'] ) ){
			wp_redirect(
				remove_query_arg(
					[
							'_wp_http_referer',
							'_wpnonce',
							'volume',
							'width',
							'aspect_ratio',
							'autoplay',
							'controls',
							'cvm_video',
							'filter_videos'
					],
					stripslashes( $_SERVER['REQUEST_URI'] )
				)
			);
		}
	}	
}