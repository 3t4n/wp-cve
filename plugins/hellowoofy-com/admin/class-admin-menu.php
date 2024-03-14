<?php
/**
 * Create admin menu.
 *
 * PHP version 7
 *
 * @package  Admin_Menu
 */

/**
 * Create Admin menu.
 *
 * Template Class
 *
 * @package  Admin_Menu
 */
class Admin_Menu {

	/** Constructor call admin menu hook */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'mws_create_admin_menu' ) );

	}
	/** Create the admin menu */
	public function mws_create_admin_menu() {
		$total_path   = plugin_dir_url( __FILE__ );
		$menu_icon  = dirname( $total_path ) . '/assets/img/icon.png';

		add_menu_page(
			'HelloWoofy.com, Smart Marketing for Underdogs',
			'HelloWoofy.com, Smart Marketing for Underdogs',
			'manage_options',
			'hellowoofy-webstories',
			array( $this, 'mws_hellowoofy_callback' ),
			$menu_icon,
			20
		);

		add_submenu_page(
			'hellowoofy-webstories',
			'API Key',
			'API Key',
			'manage_options',
			'hellowoofy-webstories',
			array( $this, 'mws_hellowoofy_callback' )
		);

		add_submenu_page(
			'hellowoofy-webstories',
			'Setting',
			'Setting',
			'manage_options',
			'mws_hellow_woofy_setting',
			array( $this, 'mws_hellowoofy_setting_callback' )
		);

		add_submenu_page(
			'hellowoofy-webstories',
			'All Stories',
			'All Stories',
			'manage_options',
			'mws_hellow_all_stories',
			array( $this, 'mws_hellow_all_stories_callback' )
		);

		add_submenu_page(
			'hellowoofy-webstories',
			'Categories (Coming Soon)',
			'Categories (Coming Soon)',
			'manage_options',
			'mws_categories',
			array( $this, 'mws_cat_callback' )
		);

		add_submenu_page(
			'hellowoofy-webstories',
			'Tags (Coming Soon)',
			'Tags (Coming Soon)',
			'manage_options',
			'mws_tags',
			array( $this, 'mws_tags_callback' )
		);

		add_submenu_page(
			'hellowoofy-webstories',
			'<a target="_blank" href="https://app.hellowoofy.com/">Add New</a>',
			'<a target="_blank" href="https://app.hellowoofy.com/">Add New</a>',
			'manage_options',
			'mws_add_new_menu',
			array( $this, 'mws_add_new_menu_callback' )
		);

	}
	/** HelloWoofy Categories Callback */
	public function mws_cat_callback() {
		echo '<h1 style="padding-top:70px; text-align:center;">Coming Soon</h1>';
	}
	/** HelloWoofy Tags Callback */
	public function mws_tags_callback() {
		echo '<h1 style="padding-top:70px; text-align:center;">Coming Soon</h1>';
	}
	/** HelloWoofy Setting Callback */
	public function mws_add_new_menu_callback() {

	}
	/** All WebStrories Callback */
	public function mws_hellow_all_stories_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'all-stories.php';
	}
	/** HelloWoofy Setting Callback */
	public function mws_hellowoofy_setting_callback() {
		$default_tab = null;
		$tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<nav class="nav-tab-wrapper">
					<a href="?page=mws_hellow_woofy_setting"
					   class="nav-tab 
					   <?php
						if ( null === $tab ) :
							?>
							nav-tab-active<?php endif; ?>">Hello Woofy Setting</a>
					<a href="?page=mws_hellow_woofy_setting&tab=mws_google_analytic"
					   class="nav-tab 
					   <?php
						if ( 'mws_google_analytic' === $tab ) :
							?>
							nav-tab-active<?php endif; ?>">Google Analytic ID</a>      
				</nav>
				<div class="tab-content">
					<?php
					switch ( $tab ) :
						case 'mws_google_analytic':
							$this->mws_google_analytic_fun();
							break;
						default:
							$this->mws_default_setting();
							break;
					endswitch;
					?>
				</div>
		</div>
		<?php
	}
	/**
	 * Get all pages.
	 *
	 * @param integer $page_ids This will return page ids.
	 */
	public function mws_get_all_pages( $page_ids = array() ) {
		$param = array();
		$param['post_type'] = 'page';
		$param['post_status'] = 'publish';
		$param['order'] = 'ASC';
		$param['orderby'] = 'title';

		if ( ! empty( $page_ids ) ) {
			$param['post__in'] = $page_ids;
		}

		// $param['post__not_in'] = [];
		$get_pages = new WP_Query( $param );
		$pages = array();
		while ( $get_pages->have_posts() ) {
			$get_pages->the_post();
			$page_content = get_the_content();
			$pages[] = array(
				'id' => get_the_ID(),
				'slug' => basename( get_permalink() ),
				'title' => get_the_title(),
			);
		}
		wp_reset_postdata();
		wp_reset_query();
		$num_index = $get_pages->found_posts + 1;
		$home = array(
			'id' => 0,
			'slug' => 'home',
			'title' => 'Home',
		);
		$pages[ $num_index ] = $home;
		return $pages;
	}
	/** Google Analytic function */
	public function mws_google_analytic_fun() {
		if ( isset( $_POST['mws_google_analytic_field'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mws_google_analytic_field'] ) ), 'mws_google_analytic_id_action' ) ) {
			if ( isset( $_POST['mws_google_analytic_id'] ) ) {

				$key  = sanitize_text_field( wp_unslash( $_POST['mws_google_analytic_id'] ) );

			}
			update_option( 'mws_google_analytic_id', $key );
		}
			$key = get_option( 'mws_google_analytic_id' );
		?>
		<style type="text/css">
			.mws_heading{
				font-size: 22px;
				font-weight: bold;
			}
			.mws_woofly_api_form button{
				background-color: #0073B1;
				border: none;
				color: white;
				padding:8px 20px;
				margin-top: 18px;
			}
			.mws_woofly_api_form p{
				font-size: 18px;
			}
			.mws_woofly_api_form input{
				margin-left: 20px;
				width: 400px;
				margin-top: 18px;
			}
		</style>

		<h3 class="mws_heading">Google Analytic ID</h3>

		<form method="post" class="mws_woofly_api_form">
			 <?php wp_nonce_field( 'mws_google_analytic_id_action', 'mws_google_analytic_field' ); ?>
			<div style="display: flex; width: 100%;">
				<div >
					<p>Enter  IDs</p>
				</div>
				<div>
					<input type="text" name="mws_google_analytic_id" value="<?php echo ! empty( esc_html( $key ) ) ? esc_html( $key ) : ''; ?>">
				</div>
			</div>
			<br>
			<button type='submit' name='mws_save_google_id'>Save Changes</button>
		</form>
		<?php
	}
	/** Defaul tab of admin setting page */
	public function mws_default_setting() {
		wp_enqueue_media();
		wp_enqueue_style( 'mws_select2_css' );
		wp_enqueue_style( 'mws_admin_boostrap_min_css' );
		wp_enqueue_script( 'mws_jquery' );
		wp_enqueue_script( 'mws_admin_bundle_min_js' );
		wp_enqueue_script( 'mws_select2_js' );
		wp_enqueue_script( 'mws_custom_admin_js' );
		if ( isset( $_POST['mws_admin_setting_field'] ) &&
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mws_admin_setting_field'] ) ), 'mws_admin_setting_action' ) ) {

			if ( isset( $_POST['mws_enable'] ) ) {
				$mws_enable  = sanitize_text_field( wp_unslash( $_POST['mws_enable'] ) );
				update_option( 'mws_enable', $mws_enable );
			} else {
				update_option( 'mws_enable', '0' );
			}

			if ( ! empty( $_POST['mws_select_page'] ) ) {
				$mws_select_page = map_deep( wp_unslash( $_POST['mws_select_page'] ), 'sanitize_text_field' );
				update_option( 'mws_select_page', $mws_select_page );
			}

			if ( ! empty( $_POST['mws_select_position'] ) ) {
				$mws_select_position  = sanitize_text_field( wp_unslash( $_POST['mws_select_position'] ) );
				update_option( 'mws_select_position', $mws_select_position );
			}

			if ( ! empty( $_POST['mws_web_story_icon'] ) ) {
				$mws_web_story_icon  = sanitize_text_field( wp_unslash( $_POST['mws_web_story_icon'] ) );
				update_option( 'mws_default_webstory_icon', $mws_web_story_icon );
			}
		}

		$check_enable       = get_option( 'mws_enable' );
		$check_seleted_page = get_option( 'mws_select_page' );
		$position           = get_option( 'mws_select_position' );
		$attachment_id      = get_option( 'mws_default_webstory_icon' );

		global $wpdb;
		$img = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $attachment_id ) );
		$all_page = $this->mws_get_all_pages();
		if ( empty( $check_seleted_page ) ) {
			$check_seleted_page = array();
		}
		?>
		<style type="text/css">
			span.select2-selection.select2-selection--multiple {
				width: 300px;
			}
			span.select2-dropdown.select2-dropdown--below {
				width: 300px !important;
			}
		</style>
		<form method="post" >
			<?php wp_nonce_field( 'mws_admin_setting_action', 'mws_admin_setting_field' ); ?>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" >
							<label for="mws_enable">Enable / Disable</label>
						</th>
						<td class="forminp forminp-checkbox">
							<label for="mws_enable">
							<input name="mws_enable"   type="checkbox" class="mws_enable" value="1" <?php echo ! empty( $check_enable ) ? 'checked' : ''; ?> >
							 Enable WebStories to display on Front Pages.                           
							</label>             
						</td>   
					</tr>
					<tr valign="top" class="mws_select_page_tr">
							<th scope="row" class="titledesc">
								<label for="mws_select_page">Select Pages</label>
							</th>
							<td class="forminp forminp-select">
								<select name="mws_select_page[]" multiple="multiple"  style="" class="mws_enable_popup" >
									<?php

									foreach ( $all_page as $page ) {
										if ( ! empty( $page ) ) {
											if ( in_array( $page['slug'], $check_seleted_page ) ) {
												?>
												<option value="<?php echo esc_html( $page['slug'] ); ?>" selected><?php echo esc_html( $page['title'] ); ?></option>
												<?php
											} else {
												?>
												<option value="<?php echo esc_html( $page['slug'] ); ?>" ><?php echo esc_html( $page['title'] ); ?></option>
												<?php
											}
										}
									}

									?>
								  
								</select>
							</td>
					</tr>
					<tr valign="top" class="mws_select_position_tr">
							<th scope="row" class="titledesc">
								<label for="mws_select_position">Select Position </label>
							</th>
							<td class="forminp forminp-select">
								<select name="mws_select_position"   >
									<option value="">Select Position</option>
									<option value="left" <?php echo ( 'left' == $position ) ? 'selected' : ''; ?>>Left</option>
									<option value="right" <?php echo ( 'right' == $position ) ? 'selected' : ''; ?>>Right</option>
								</select>
							</td>
					</tr>
					 <tr valign="top"  class="mws_select_page_tr">
							<th scope="row" class="titledesc">
								<label for="mws_web_story_icon">Upload Icon </label>
							</th>
							<td class="forminp forminp-select" style="padding-top: -10px;">
								 <div style="display: flex;justify-content: space-around; width:34%;">
									 <div><button type="button" name="mws_web_story_icon_upload" id="mws_web_story_icon_upload" style="padding: 10px 20px;color: #00bdff; border: 2px solid #00bdff; border-radius: 20px; margin-left: -60px;">Image upload</button></div>
									 <div><input type="hidden" id="mws_web_story_icon" name="mws_web_story_icon">
										 <img width="150" id="mws_web_story_img" src="<?php echo ! empty( $img->guid ) ? esc_attr( $img->guid ) : ''; ?>">
									 </div>
								 </div>
								
								
							</td>
					   
					</tr>
		   
				</tbody>
			</table>
			<br><br>
			<input type="submit" name="mws_save_setting" id="submit" class="button button-primary" value="Save Changes">
		</form>
		<?php
	}
	/** Max HelloWoofy callback */
	public function mws_hellowoofy_callback() {
		?>
		<style type="text/css">
			@font-face {
				font-family: 'ProximaNova Regular';
				src: url('../fonts/ProximaNova-Regular.eot');
				src: local('Proxima Nova Regular'), local('ProximaNova-Regular'),
				url('../fonts/ProximaNova-Regular.eot?#iefix') format('embedded-opentype'),
				url('../fonts/ProximaNova-Regular.woff') format('woff'),
				url('../fonts/ProximaNova-Regular.ttf') format('truetype');
				font-weight: normal;
				font-style: normal;
			}
			.mws_woofy__container {
				font-family: 'ProximaNova Regular';
				font-size: 18px;
				height: 100vh;
				display: flex;
				justify-content: space-between;
				flex-wrap: wrap;
				padding-top: 45px;
			}

			.mws_woofy__container p {
				font-size: 20px;
			}

			.woofy__content-block--right {
				overflow: hidden;
				flex-basis: 100%;
				padding-top: 50px;
				padding-left: 25px;
			}
			.woofy__content-block--center {
				flex-basis: 100%;
				overflow: hidden;
				margin-left: 15%;
			}
		</style>
		<?php

		$key_for_display = base64_encode( get_current_user_id() . '=' . AUTH_SALT . parse_url( home_url() )['host'] );
		$total_path = plugin_dir_url( __FILE__ );
		$img_bg_url = dirname( $total_path ) . '/img/image.png';
		?>
				<div class="mws_woofy__container">
					 <div class="woofy__content-block--center">
					<iframe width="800" height="500" src="https://www.youtube.com/embed/xtkRi7QCCro" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
					<div class="woofy__content-block--right">
						<p>Hey there👋, thanks for installing the <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a> plugin for Google Web Stories. You are just one step away from automating your web stories to your Wordpress blog! Woot!</p>
						<p>Please remember, you will need to use this API key below in order to start working with <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a> plugin when you connect your <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a> account with your blog on Wordpress.</p>
						<p>API Key: <input id='api_key' size='140' type='text' disabled value='<?php echo esc_html( $key_for_display ); ?>'></p>                                            
						<p>In case you have any questions or need help installing the plugin, please, visit our <a target="_blank" href="https://hellowoofy.com/knowledge-base/">FAQ page</a></p>
						<p>Wishing you and your small business the very best.🤝</p>
						<p>Best,<br>
						   Arjun Rai,<br>
						   Founder + CEO, <a href="https://hellowoofy.com/" target="_blank">HelloWoofy.com</a></p>
					</div>
				   
				 
				</div>      
		<?php

	}

}

new Admin_Menu();



