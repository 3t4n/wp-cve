<?php
class WPForms_Views_Lite_Support {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'wpforms-views-help' ) ) {
			add_filter( 'wpforms_admin_header', '__return_false' );
			add_filter( 'wpforms_admin_flyoutmenu', '__return_false' );
		}

	}

	function add_page() {
		add_submenu_page(
			'edit.php?post_type=wpforms-views',
			__( 'Help', 'textdomain' ),
			__( 'Help', 'textdomain' ),
			'manage_options',
			'wpforms-views-help',
			array( $this, 'help' )
		);
	}

	function help() {
		?>
		<style>
			.wpforms-views-help{
				padding: 10px 30px 41px 30px;
				background: #fff;
				margin: 20px 20px;
			}
			.wpforms-views-help-content{
			    background: #F2F4F5;
				border-radius: 8px;
				padding: 20px;
			}

			.wpforms-footer-promotion {
				display: none !important;
			}

		</style>

			<div class="wpforms-views-help">
			<h3>Support and feedback</h3>
			<div class="wpforms-views-help-content">
			<p><a target="_blank" href="https://formviewswp.com/docs">Please check docs here.</a></p>
			<p><a target="_blank" href="https://formviewswp.com/contact">Support is available here.</a></p>
			<p>If you enjoyed using Views for WPForms, <a target="_blank" href="https://wordpress.org/support/plugin/views-for-wpforms-lite/reviews/?filter=5">please leave a positive review here.</a> On the other hand, if you were not satisfied with it, <a target="_blank" href="https://formviewswp.com/contact">please let us know the reasons why at this link.</a></p>
			</div>
			</div>

		<?php
	}


}
new WPForms_Views_Lite_Support();
