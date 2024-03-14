<?php
namespace Enteraddons\Admin;

/**
 * Enteraddons admin class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

if( !class_exists('Admin') ) {
	class Admin {

		private static $instance = null;

		function __construct() {
			add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_scripts' ] );
			add_action( 'admin_footer', [ __CLASS__, 'admin_footer_inject' ] );
			$this->init();
		}
		public static function getInstance() {

			if( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		public function init() {
			//
			new Admin_Notices();
			//
			Admin_Menu::getInstance();
			//
			Admin_Ajax_handler::getInstance();
			
		}
		public static function admin_scripts( $hook ) {

			$hookStack = ['toplevel_page_enteraddons'];
			$getHookStack = apply_filters( 'ea_admin_scripts_hooks', $hookStack, $hook );
			if( in_array( $hook, $getHookStack ) ||  get_post_type() == 'ea_builder_template'  ) {
			
			wp_enqueue_style( 'select2', ENTERADDONS_DIR_ADMIN_ASSETS. 'css/select2.min.css', array(), ' 1.0.0', false );
			wp_enqueue_script( 'select2', ENTERADDONS_DIR_ADMIN_ASSETS.'js/select2.min.js', array('jquery'), '1.0.0', true );
			wp_enqueue_style( 'font-awesome', ENTERADDONS_DIR_ADMIN_ASSETS. 'css/font-awesome.min.css', array(), ' 4.7.0', false );
			wp_enqueue_style( 'enteraddons-icons', ENTERADDONS_DIR_ADMIN_ASSETS. 'css/enteraddons-icons.css', array(), ' 1.0.0', false );
			wp_enqueue_style( 'jquery-confirm', ENTERADDONS_DIR_ADMIN_ASSETS. 'css/jquery-confirm.min.css', array(), '3.3.2', false );
			wp_enqueue_style( 'enteraddons', ENTERADDONS_DIR_ADMIN_ASSETS. 'css/style.css', array(), '1.0.0', false );
			wp_enqueue_script( 'jquery-confirm', ENTERADDONS_DIR_ADMIN_ASSETS.'js/jquery-confirm.min.js', array('jquery'), '1.0.0', true );
			wp_enqueue_script( 'enteraddons-admin-ajax', ENTERADDONS_DIR_ADMIN_ASSETS. 'js/admin-ajax.js', array('jquery'), '1.0.0', true );
			wp_enqueue_script( 'enteraddons', ENTERADDONS_DIR_ADMIN_ASSETS. 'js/main.js', array('jquery'), '1.0.0', true );
			do_action( 'ea_admin_scripts_after' );
			$args = array(
				'nonce'   => wp_create_nonce( 'enteraddons-settings-data-save' ),
				'ajaxurl' => admin_url('admin-ajax.php')
			);

			wp_localize_script( 'enteraddons-admin-ajax', 'enteraddonsAdmin', $args );
			}
		}

		public static function admin_footer_inject() {

			if( \Enteraddons\Classes\Helper::is_pro_active() ) {
				return;
			}

			?>
			<style>
			.ea-admin-modal-wrap {
				display: none;
			    position: fixed;
			    top: 0;
			    left: 0;
			    right: 0;
			    bottom: 0;
			    background: rgba(0, 0, 0, 0.2);
			    z-index: 9999999;
			}
			.ea-admin-modal-wrap .ea-admin-modal-dialogue {
			    position: absolute;
			    top: 0;
			    left: 0;
			    right: 0;
			    bottom: 0;
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-pack: center;
			    -ms-flex-pack: center;
			    justify-content: center;
			    -webkit-box-align: center;
			    -ms-flex-align: center;
			    align-items: center;
			   
			}
			.ea-admin-modal-wrap .ea-admin-modal-dialogue .ea-modal {
			    width: 100%;
			    max-width: 450px;
			    background: #ffffff;
			    -webkit-box-shadow: 0px 40px 120px rgba(23, 57, 97, 0.1);
			    box-shadow: 0px 40px 120px rgba(23, 57, 97, 0.1);
			    border-radius: 5px;
			    max-height: 90vh;
			    overflow-y: auto;
			}
			.ea-admin-modal-wrap .ea-modal-body .ea-modal-close {
		        position: absolute;
			    top: 16px;
			    right: 15px;
			    color: #ffffff;
			    background: #eb0c0c;
			    width: 25px;
			    display: flex;
			    height: 25px;
			    justify-content: center;
			    align-items: center;
			    font-size: 16px;
			    border-radius: 42px;
			    text-decoration: none;
			}
			.ea-admin-modal-wrap .ea-modal-body {
			    position: relative;
			}
			.ea-admin-modal-wrap .ea-modal-content {
			    padding: 30px;
			    text-align: center;
			}
			.ea-admin-modal-wrap .ea-modal-card h3 {
			    font-size: 22px;
			    color: #041137;
			    font-weight: 500;
			    margin-bottom: 10px;
			}
			.ea-admin-modal-wrap .ea-modal-card p {
			    font-size: 14px;
			    font-weight: 400;
			    color: #727272;
			    margin-bottom: 20px;
			}
			.ea-admin-modal-wrap .ea-modal-card .ea-image {
			    margin-bottom: 20px;
			}
			.ea-admin-modal-wrap .ea-modal-card .ea-image img {
				width: 160px;
			}
			</style>
			<div class="ea-admin-modal-wrap ea-modal-show">
				<div class="ea-admin-modal-dialogue">
					<div class="ea-modal">
						<div class="ea-modal-body">
							<a href="#" class="ea-modal-close ea-admin-popup-close">X</a>
							<div id="ea-pro-popup" class="ea-modal-content">
				                <div class="ea-modal-card">
				                    <div class="ea-image">
				                        <img src="<?php echo ENTERADDONS_DIR_ADMIN_ASSETS.'img/pro.svg'; ?>">
				                    </div>
				                    <h3><?php esc_html_e( 'Go PRO', 'enteraddons' ); ?></h3>
				                    <p><?php esc_html_e( 'Unlock more premium Widgets, Templates, Blocks and possibilities to build awesome websites.', 'enteraddons' ); ?></p>
				                    <a target="_blank" href="https://enteraddons.com/pricing/" class="btn s-btn"><?php esc_html_e( 'Upgrade Now', 'enteraddons' ); ?></a>
				                </div>
				            </div>
			            </div>
	            	</div>
	            </div>
            </div>
            <?php
		}

	}

}
