<?php
/**
 * A class for importing demo content.
 */

/**
 * Bail if we are not in WP.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'AyeCode_Demo_Content' ) ) {

	/**
	 * The settings for AyeCode Connect
	 */
	class AyeCode_Demo_Content {
		/**
		 * The title.
		 *
		 * @var string
		 */
		public $name = 'Import Demo Data';

		/**
		 * The relative url to the assets.
		 *
		 * @var string
		 */
		public $url = '';

		/**
		 * The AyeCode_Connect instance.
		 * @var
		 */
		public $client;

		/**
		 * The base url of the plugin.
		 * 
		 * @var
		 */
		public $base_url;

		/**
		 * AyeCode_UI_Settings instance.
		 *
		 * @access private
		 * @since  1.0.0
		 * @var    AyeCode_Connect_Settings There can be only one!
		 */
		private static $instance = null;

		/**
		 * Main AyeCode_Connect_Settings Instance.
		 *
		 * Ensures only one instance of AyeCode_Connect_Settings is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @return AyeCode_Connect_Settings - Main instance.
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AyeCode_Demo_Content ) ) {
				self::$instance = new AyeCode_Demo_Content;

				$args                     = ayecode_connect_args();
				self::$instance->client   = new AyeCode_Connect( $args );

				if ( is_admin() ) {
					add_action( 'admin_menu', array( self::$instance, 'menu_item' ) );


					self::$instance->base_url = str_replace( "/includes/../", "/", plugins_url( '../', __FILE__ ) );

					// prevent redirects after plugin/theme activations
					self::$instance->prevent_redirects();
					add_action( 'init', array( self::$instance, 'prevent_redirects' ),12 );



					// ajax
					add_action( 'wp_ajax_ayecode_connect_demo_content', array( self::$instance, 'import_content' ) );
//					add_action( 'wp_ajax_ayecode_connect_disconnect', array( self::$instance, 'ajax_disconnect_site' ) );
//					add_action( 'wp_ajax_ayecode_connect_licences', array( self::$instance, 'ajax_toggle_licences' ) );
//					add_action( 'wp_ajax_ayecode_connect_support', array( self::$instance, 'ajax_toggle_support' ) );
//					add_action( 'wp_ajax_ayecode_connect_support_user', array( self::$instance, 'ajax_toggle_support_user' ) );
//					add_action( 'wp_ajax_ayecode_connect_install_must_use_plugin', array( self::$instance, 'install_mu_plugin' ) );

				}
			}

			return self::$instance;
		}

		/**
		 * Prevent plugin/theme redirects after activation.
		 */
		public function prevent_redirects(){
			// prevent redirects when doing ajax
			if ( wp_doing_ajax() && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'ayecode_connect_demo_content' ) {
				// prevent redirects to settings screens
				add_filter('wp_redirect','__return_empty_string',200);

				// prevent some transient redirects
				delete_transient( '_gd_activation_redirect' );
				delete_transient( 'gd_social_importer_redirect' );
			}
		}


		/**
		 * Add the WordPress settings menu item.
		 */
		public function menu_item() {
			$url_change_disconnection_notice = get_transient( $this->client->prefix . '_site_moved');

			$menu_name = $this->name;

//			add_menu_page(
//				$menu_name,
//				$url_change_disconnection_notice ? sprintf($menu_name.' <span class="awaiting-mod">%s</span>', "!") : $menu_name,
//				'manage_options',
//				'ayecode-connect',
//				array(
//					$this,
//					'settings_page'
//				),
//				'data:image/svg+xml;base64,' . base64_encode( file_get_contents( dirname( __FILE__ ).'/../assets/img/ayecode.svg' ) ),
//				4
//			);


			$page = add_submenu_page(
				'ayecode-connect',
				$this->name,
				$url_change_disconnection_notice ? sprintf($this->name.' <span class="awaiting-mod">%s</span>', "!") : $this->name,
				'manage_options',
//				$this->client->is_registered() ? 'ayecode-demo-content' : 'ayecode-connect&alert=connect',
				'ayecode-demo-content',// : 'ayecode-connect&alert=connect',
				array(
					$this,
					'settings_page'
				)
			);

			add_action( "admin_print_styles-{$page}", array( $this, 'scripts' ) );



			// maybe clear licenses
			$nonce = !empty($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : '';
			$action = !empty($_REQUEST['ac_action']) ? sanitize_title_with_dashes($_REQUEST['ac_action']) : '';
			if ( $action && $action == 'clear-licenses' && $nonce && wp_verify_nonce( $nonce, 'ayecode-connect-debug' ) ) {
				$this->clear_all_licenses();
				wp_redirect(admin_url( "admin.php?page=ayecode-connect&ayedebug=1" ));
				exit;
			}

		}

		/**
		 * Add scripts to our settings page.
		 */
		public function scripts() {
//			wp_enqueue_style( 'ayecode-connect-bootstrap', $this->base_url . 'assets/css/ayecode-ui-compatibility.css', array(), AYECODE_CONNECT_VERSION );

			// Register the script
			wp_register_script( 'ayecode-connect', $this->base_url . 'assets/js/ayecode-connect.js', array( 'jquery' ), AYECODE_CONNECT_VERSION );

			// Localize the script with new data
			$translation_array = array(
				'nonce'          => wp_create_nonce( 'ayecode-connect' ),
				'error_msg'      => __( "Something went wrong, try refreshing the page and trying again.", "ayecode-connect" ),
				'disconnect_msg' => __( "Are you sure you with to disconnect your site?", "ayecode-connect" ),
			);
			wp_localize_script( 'ayecode-connect', 'ayecode_connect', $translation_array );
			wp_enqueue_script( 'ayecode-connect' );
		}

		/**
		 * Settings page HTML.
		 */
		public function settings_page( $wizard = false ) {
            global $aui_bs5;

			// if not connectd then redirect to connection screen
			if(!$this->client->is_active()){
				$maybe_demo_redirect = !empty($_REQUEST['ac-demo-import']) ? '&ac-demo-import='.sanitize_title_with_dashes($_REQUEST['ac-demo-import']) : '';
				$connect_url = admin_url("admin.php?page=ayecode-connect&alert=connect".$maybe_demo_redirect);
				?>
				<script>
					window.location.replace("<?php echo esc_url_raw($connect_url);?>");
				</script>
				<?php
			}else{

			// bsui wrapper makes our bootstrap wrapper work
			?>
			<!-- Clean & Mean UI -->
			<style>
				#wpbody-content > div.notice,
				#wpbody-content > div.error{
					display: none;
				}

				<?php if($wizard){ ?>
				.bsui .modal-backdrop.fade.show{
					display: none !important;
				}
				<?php } ?>
			</style>

				<?php if(!$wizard){ ?>
			<div class="bsui" style="margin-left: -20px;">
				<!-- Just an image -->
				<nav class="navbar bg-white border-bottom">
					<a class="navbar-brand p-0" href="#">
						<img src="<?php echo $this->base_url; ?>assets/img/ayecode.png" width="120" alt="AyeCode Ltd">
					</a>
				</nav>
			</div>
					<?php } ?>


			<div class="bsui" style="<?php if(!$wizard){ ?>margin-left: -20px; display: flex<?php } ?>">
				<div class="<?php if(!$wizard){ ?>container<?php } ?>">
					<?php
					echo aui()->alert(array(
							'type'=> 'info',
                            'class' => 'mt-4',
							'content'=> __("This importer should only be used on NEW sites, it will change the whole look and appearance of your site.","ayecode-connect")
						)
					);
					?>
					<div class="row row-cols-1 row-cols-sm-2  row-cols-md-2 mt-4">

						<?php
						foreach ($this->get_sites() as $site){
							global $ac_site_args,$ac_prefix;
							$ac_prefix = $this->client->prefix;
							$ac_site_args = $site;
							load_template( dirname( __FILE__ )."/../templates/import/site.php", false ); // $args only introduced in wp 5.5 so lets use a more backwards compat way
						}
						?>

					</div>
				</div>






				<!-- Modal -->
				<div class="modal fade p-0 m-0" id="ac-item-preview" data-demo="" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 10000;">
					<div class="modal-dialog mw-100  p-0 m-0">
						<div class="modal-content vh-100 rounded-0">
							<div class="row overflow-hidden">
								<div class="col-3 border-right pr-0 vh-100 d-flex flex-column">
									<div class="modal-header">
										<h5 class="modal-title" id="staticBackdropLabel"></h5>
										<button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
											<?php echo $aui_bs5 ? '' : '<span aria-hidden="true">&times;</span>'; ?>
										</button>
									</div>
									<div class="modal-body overflow-auto bg-light scrollbars-ios ac-import-progress d-none">
										<h6 class=" h6"><?php _e("Importing Demo","ayecode-connect");?></h6>
										<div class="progress">
											<div class="progress-bar main-progress progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
										</div>
										<div class="alert alert-danger aci-error mt-3 d-none" role="alert"></div>
										<ul class="list-group mt-3 aci-import-steps">
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Theme","ayecode-connect");?>
													<span class="spinner-border spinner-border-sm" role="status"></span>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Plugins","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Settings","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Categories","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Page Templates","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Dummy Posts","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
												<div class="progress mt-1 d-none ">
													<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Widgets","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
											</li>
											<li class="list-group-item mb-0">
												<div class="d-flex justify-content-between align-items-center">
													<?php _e("Menus","ayecode-connect");?>
													<span class="text-muted h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>
												</div>
											</li>
										</ul>
									</div>
									<div class="modal-body overflow-auto bg-light scrollbars-ios ac-item-info">
										<div class="ac-item-img shadow-sm"></div>
										<div class="ac-item-desc pt-4"></div>
										<div class="ac-item-theme pt-4"></div>
										<div class="ac-item-plugins pt-4"></div>
									</div>
									<div class="modal-footer">
										<button onclick="aci_init(this);return false;" type="button" class="btn btn-primary w-100"><?php _e("Import","ayecode-connect");?></button>
									</div>
								</div>
								<div class="col-9 p-0 m-0 ">
									<div class="ac-preview-loading text-center position-absolute w-100 text-white vh-100 overlay overlay-black p-0 m-0 d-none d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div></div>
									<iframe id="embedModal-iframe" class="w-100 vh-100 p-0 m-0" src="" width="100%" height="100%" frameborder="0" allowtransparency="true"></iframe>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>


			<script>

				var $aci_url = '';
				var $aci_demo = '';
				var $aci_percent = 0;
				var $aci_sub_percent = 0;
				var $aci_step = 0;
				var $aci_page = 0;
				function aci_init($item) {

					var r = confirm("<?php _e("This import may remove all current GeoDirectory data, please only proceed if you are ok with this.","ayecode-connect");?>");
					if (r == true) {

						// set the import url
						$aci_url = jQuery('#ac-item-preview').find('iframe').attr('src');

						// prevent navigate away
						jQuery('#ac-item-preview').find('.modal-header button,.modal-footer .btn').prop('disabled', true);

						// set button as importing
						jQuery($item).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <?php _e( "Importing...", "ayecode-connect" );?>');

						// set status
						jQuery('#ac-item-preview').find('.ac-item-info,.ac-import-progress').toggleClass('d-none');

						// start import
						aci_step();

					}
				}
				
				
				function aci_step() {
					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'ayecode_connect_demo_content',
							security: ayecode_connect.nonce,
							demo: $aci_demo,
							step: $aci_step,
							p_num: $aci_page
						},
						beforeSend: function() {

						},
						success: function(data, textStatus, xhr) {
							console.log(data);
							if(data.success){
								aci_progress($aci_step,data.data);
								if(data.data.step==8){
									//done
								}else{
									if(data.data.step==5 && data.data.page!== "undefined"){
										$aci_step = 5;
										$aci_sub_percent = data.data.sub_percent;
										$aci_page++;
										aci_step();
									}else{
										$aci_step++;
										aci_step();
									}

								}

							}else{
								aci_error($aci_step,data.data);
							}
						},
						error: function(xhr, textStatus, errorThrown) {
							alert(textStatus);
						}
					}); // end of ajax
				}

				function aci_error($step,$error){
					$li = jQuery('#ac-item-preview .aci-import-steps').find('li').eq($step);
					// mark as failed
					$li.find('span').replaceWith('<span class="text-danger h6 p-0 m-0"><i class="fas fa-times-circle"></i></span>');

					// show error
					jQuery('#ac-item-preview .aci-error').html($error).removeClass('d-none');

					// stop progress animation
					jQuery('#ac-item-preview .progress-bar').removeClass('progress-bar-animated progress-bar-striped');
					// un-prevent navigate away
					jQuery('#ac-item-preview').find('.modal-header button,.modal-footer .btn').prop('disabled', false);

					// set button as view site
					jQuery('#ac-item-preview .modal-footer').html('<a href="#reload" onclick="location.reload();return false;" class="btn btn-primary w-100"><?php _e("ERROR","ayecode-connect");?></a>');
				}

				function aci_progress($step,$data){
					$li = jQuery('#ac-item-preview .aci-import-steps').find('li').eq($step);
					$li_next = jQuery('#ac-item-preview .aci-import-steps').find('li').eq($step+1);

					// set sub percent
					if(typeof($data.sub_percent) !== 'undefined' ){
						$li.find('.progress').removeClass('d-none');
						$li.find('.progress-bar').css("width",$data.sub_percent+"%");
					}else{
						$li.find('.progress').addClass('d-none');
					}

					// set percent done
					jQuery('#ac-item-preview .progress-bar.main-progress').css("width",$data.percent+"%");

					if(typeof($data.sub_percent) !== 'undefined' ){

					}else{
						// mark as done
						$li.find('span').replaceWith('<span class="text-success h6 p-0 m-0"><i class="fas fa-check-circle"></i></span>');

						// mark as doing
						$li_next.find('span').replaceWith('<span class="spinner-border spinner-border-sm" role="status"></span>');
					}



					// finish up
					if($step===7){
						// stop progress animation
						jQuery('#ac-item-preview .progress-bar.main-progress').removeClass('progress-bar-animated progress-bar-striped');
						// un-prevent navigate away
						jQuery('#ac-item-preview').find('.modal-header button,.modal-footer .btn').prop('disabled', false);

						// set button as view site
						jQuery('#ac-item-preview .modal-footer').html('<a href="<?php echo get_home_url();?>" class="btn btn-primary w-100"><?php _e("View Site","ayecode-connect");?></a>');

					}
				}

				jQuery(function(){
					var iFrame = jQuery( '#embedModal-iframe') ;

					jQuery( '#ac-item-preview' ).on( 'show.bs.modal', function ( e ) {
						jQuery('.ac-preview-loading').addClass('d-flex');
						var url = jQuery( '#ac-item-preview' ).data('iframe-url');
						iFrame.attr({
							src: url
						});
					});
					jQuery( "#ac-item-preview" ).on( "hidden.bs.modal", function() {
						iFrame.removeAttr( "src allow" );
					});

					//resize the iframe once loaded.
					iFrame.load(function() {
						jQuery('.ac-preview-loading').removeClass('d-flex');
					});

					// check for direct link
					<?php
						if(!empty($_REQUEST['ac-demo-import'])){
							$demo = sanitize_title_with_dashes($_REQUEST['ac-demo-import']);
							?>
							jQuery(".col").find("[data-demo='<?php echo esc_attr($demo);?>']").find(".btn").click();
							<?php
						}
					?>
				});

				function ac_preview_site($item){

					// replace vars
					var $title = jQuery($item).closest('.card').find('.card-title').html();
					jQuery('#ac-item-preview').find('.modal-title').html($title);

					// desc
					var $desc = jQuery($item).closest('.card').find('.card-body').html();
					jQuery('#ac-item-preview').find('.ac-item-desc').html($desc);

					// theme
					var $theme = jQuery($item).closest('.card').find('.sd-src-theme').html();
					jQuery('#ac-item-preview').find('.ac-item-theme').html($theme);

					// plugins
					var $plugins = jQuery($item).closest('.card').find('.sd-src-plugins').html();
					jQuery('#ac-item-preview').find('.ac-item-plugins').html($plugins);

					// img
					var $img = jQuery($item).closest('.card').find('img').clone();
					jQuery('#ac-item-preview').find('.ac-item-img').html($img);

					// iframe
					jQuery('#ac-item-preview').data('iframe-url',jQuery($item).attr('href'));

					// demo slug data
					jQuery('#ac-item-preview').data('demo',jQuery($item).closest('.card').data('demo'));
					$aci_demo = jQuery($item).closest('.card').data('demo');
					
					// open modal
					jQuery('#ac-item-preview').modal('show');

				}
			</script>
			<?php
			}
		}

		/**
		 * Get demo site info.
		 *
		 * @return mixed
		 */
		public function get_sites(){

			$sites = get_transient( 'ayecode_connect_demos' );
			if ( empty( $demos ) ) {

				$args = array(
					'timeout'     => 30,
					'redirection' => 0,
					'sslverify'   => AYECODE_CONNECT_SSL_VERIFY,
				);
				$url  = $this->client->get_api_url( '/demos' );
				$data = wp_remote_get( $url, $args );

				if ( ! is_wp_error( $data ) && $data['response']['code'] == 200 ) {
					$responseBody = wp_remote_retrieve_body( $data );
					$sites        = json_decode( $responseBody );
					set_transient( 'ayecode_connect_demos', $sites, HOUR_IN_SECONDS );
				}
			}


			return $sites;

		}

		public function import_content(){
			// security
			check_ajax_referer( 'ayecode-connect', 'security' );
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( - 1 );
			}


			$sites = $this->get_sites();
			$step = isset($_POST['step']) ? absint($_POST['step']) : '';
			$demo = isset($_POST['demo']) ? sanitize_title_with_dashes($_POST['demo']) : '';
			$page = isset($_POST['p_num']) ? absint($_POST['p_num']) : 0;
			$site = isset($sites->{$demo}) ? $sites->{$demo} : array();
			$data = array(
				'step'  => $step,
				'percent'   => 0,
			);
			$error = array();

			if($step === 0){

				// set the demo url
				update_option('_acdi_demo_url',"https://demos.ayecode.io/".$demo );

				// theme
				$result =  $this->set_theme( $demo, $site );
				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 10,
					);
				}

			}elseif($step === 1){
				// plugins
				$result = $this->set_plugins( $demo, $site );
				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 30,
						//'warning'     => 'X failed to install', // @todo implement these
						//'info'     => 'WP Rocket will help with speed ', // @todo implement these
//						'result'    => print_r($result , true)
					);
				}

			}elseif($step === 2){
				// settings
				$result =  $this->client->request_demo_content( $demo, 'settings' );

				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 40,
					);
				}

			}elseif($step === 3){

				// categories
				$result =  $this->client->request_demo_content( $demo, 'categories' );


				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 50,
					);
				}

			}elseif($step === 4){

				// page templates
				$result =  $this->client->request_demo_content( $demo, 'templates' );

				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 60,
					);
				}

			}elseif($step === 5){
				// dummy posts
				$result =  $this->client->request_demo_content( $demo, 'dummy_posts', $page );
//				wp_mail('stiofansisland@gmail.com','dummy posts step',$result );
//				wp_mail('stiofansisland@gmail.com','dummy posts stepa',print_r($result,true) );

				if(is_wp_error( $result ) ){
					$error = $result;
				}else{

					if(isset($result->total) && isset($result->offset) && $result->total >= $result->offset){
						$sub_percent = $result->offset > $result->total ? 100 : ($result->offset / $result->total) * 100;
						$data = array(
							'step'  => $step,
							'percent'   => 60,
							'page'  => $page++,
							'sub_percent'   => round($sub_percent),
							'total'   => $result->total,
							'offset'   => $result->offset,
//							'result' => $result

						);
					}else{
						$data = array(
							'step'  => $step+1,
							'percent'   => 80,
//							'result' => $result
						);
					}


				}

			}elseif($step === 6){
				// widgets
				$result =  $this->client->request_demo_content( $demo, 'widgets' );

				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 90,
					);
				}

			}elseif($step === 7){
				// menus
				$result =  $this->client->request_demo_content( $demo, 'menus' );

				if(is_wp_error( $result ) ){
					$error = $result;
				}else{
					$data = array(
						'step'  => $step+1,
						'percent'   => 100,
					);
				}

				// Clear any unwanted data and flush rules
				delete_transient( 'geodir_cache_excluded_uris' );
				wp_schedule_single_event( time(), 'geodir_flush_rewrite_rules' );

			}elseif($step === 8){
				// done
			}

			if(empty($error)){
				wp_send_json_success( $data );
			}else{
				wp_send_json_error( $error->get_error_message() );
			}

			exit;
		}

		public function set_plugins( $demo, $site = array() ){

			// maybe get site info
			if(empty($site)){
				$sites = $this->get_sites();
				$site = isset($sites->{$demo}) ? $sites->{$demo} : array();
			}

			$result = false;

			if(!empty($site->plugins)){
				$result = $this->client->request_demo_content( $demo, 'plugins' );

			}


			return $result;
		}


		/**
		 * Install and activate a theme if needed.
		 *
		 */
		public function set_theme( $demo, $site = array() ){

			// maybe get site info
			if(empty($site)){
				$sites = $this->get_sites();
				$site = isset($sites->{$demo}) ? $sites->{$demo} : array();
			}

			$slug = esc_attr($site->theme->slug);

			$result = false;
			$activate_theme = false;
			$theme = wp_get_theme($slug);

			if(!$theme->exists()){
				$result = $this->client->request_demo_content( $demo, 'theme' );

				if(empty($result->{$slug}->success)){
					$result = new WP_Error( 'theme_install_fail', __( "The theme installation failed.", "ayecode-connect" ) );
				}else{
					$activate_theme = true;
				}

			}elseif($slug == get_option('stylesheet')){
				// its installed and active
				$result = true;
			}else{
				// activate
				$activate_theme = true;
			}

			// Maybe activate theme
			if ( $activate_theme ) {
				// activate

				switch_theme( $slug );
				if($slug == get_option('stylesheet')){
					$result = true;
				}

				// if a child theme then the main templare option can fail to update
				if($result && !empty($site->theme->Template)){
					$parent_slug = esc_attr( $site->theme->Template );

					update_option('template',$parent_slug);
				}
			}

			return $result;
		}

	}



	/**
	 * Run the class if found.
	 */
	AyeCode_Demo_Content::instance();

}

/*


Import order

theme
plugins

CPTs
- Settings
- Price packages
- Custom Fields
- Search items
- Sort orders
- Tabs
- Posts
- Media? Do we import images or hotlink?

GD General Settings

Widgets
Menus (make it later so we know what items to add from CPTs)
Customizer settings






 */