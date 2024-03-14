<?php
class wappPress_admin_setting extends wappPress {

	function __construct() {

			add_action( 'admin_menu', array( $this, 'maker_menu' ), 7);

			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_init', array( $this, 'register_upgrade' ) );

			add_action( 'wp_ajax_create_app', array( $this, 'create_app' ) );
			add_action( 'wp_ajax_upload_crop', array( $this, 'upload_crop' ) );

			add_action( 'wp_ajax_create_push_app', array( $this, 'create_push_app' ) );

			add_action( 'wp_ajax_get_app', array( $this, 'get_app' ) );

			add_action( 'wp_ajax_search_post_handler', array( $this, 'search_post_results' ) );
			
			add_action( 'admin_notices', array( $this, 'pro_admin_notice__success' )  );

			if ( isset( $_GET['clear_app_cookie'] ) && 'true' === $_GET['clear_app_cookie'] ) {

				  self::reset_cookie();

			}
			//Custom Post New
		if(@$options['wapppress_push_post']=='on'){			
			add_action( 'publish_post', 'send_push_on_new_post', 10, 3 );
			}		
	    if(@$options['wapppress_push_post_edit']=='on'){			
			add_action( 'publish_post', 'send_push_on_new_post', 10, 3 );
			}
		if(@$options['wapppress_push_product']=='on'){			
			add_action( 'transition_post_status', 'send_push_on_product', 10, 3 );
			}		
	    if(@$options['wapppress_push_product_edit']=='on'){			
			add_action( 'transition_post_status', 'send_push_on_product', 10, 3 );
			}
			

	}



	public function maker_menu() {

		$dirPlgUrl  = trailingslashit( plugins_url('wapppress-builds-android-app-for-website') );

		$pageTitle = __( 'WappPress', 'WappPress' );

		$maPlgin = 'wapppressplugin';

		$maSett = 'wapppresssettings';
		$advSett = 'advancesettings';

		$maTheme = 'wapppresstheme';

		$maPush = 'wapppresspush';
		$maUpgrade = 'wapppressupgrade';

		$plgIcon  = $dirPlgUrl  . 'images/view.png';

		$dirInc1  = $dirPlgUrl  . 'includes/';

		
		$options_upgrdate = get_option( 'wapppress_upgrade' );
		// Create main menu 
		if (preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $options_upgrdate['wapppress_pro_license']))
		{
		$pageTitle = __( 'WappPress', 'WappPress' );
		$mainMenu = add_menu_page( $pageTitle, $pageTitle, 'manage_options', $maPlgin, array( $this, 'maker_settings_page' ),$plgIcon  );
		}else{
		$pageTitle = __( 'WappPress Basic', 'wappPress' );
		$mainMenu = add_menu_page( $pageTitle, $pageTitle, 'manage_options', $maPlgin, array( $this, 'maker_basic_page' ),$plgIcon  );
		}

		

		global $submenu;

		// Settings page sub menu

		$subSettingMenu = add_submenu_page($maPlgin, __( 'Build App', 'wappPress' ), __( 'Build App', 'wappPress' ),  'manage_options', $maSett, array( $this, 'maker_settings_page' ));
		
		$subAdvSettingMenu = add_submenu_page($maPlgin, __( 'Advance Settings', 'wappPress' ), __( 'Advance Settings', 'wappPress' ),  'manage_options', $advSett, array( $this, 'advance_settings_page' ));
		
		$subPushMenu = add_submenu_page($maPlgin, __( 'Push Notification', 'wappPress' ), __( 'Push Notification', 'wappPress' ),  'manage_options', $maPush, array( $this, 'maker_push_page' ));
		
		$subUpgradeMenu = add_submenu_page($maPlgin, __( 'Upgrade', 'wappPress' ), __( 'Upgrade', 'wappPress' ),  'manage_options', $maUpgrade, array( $this, 'upgrade_wapppress_page' ));

		//$subThemeMenu = add_submenu_page($maPlgin, __( 'Themes', 'wappPress' ), __( 'Themes', 'wappPress' ),  'manage_options', $maTheme, array( $this, 'maker_theme_page' ));

				

	}

	

	//Basic Page 

	public function maker_basic_page(){
		

	require_once(  'header.php' );

	?>

	<div class="contant-section1">
		

			
				<div class="row">
				  <!-- First Section -->
				  <div class="col-sm-12 col-md-4 section">
			

					<div class="wrapper">

						<div class="contant-section">


							<h3>

							Android <br/><span>BASIC VERSION<span> &nbsp; &nbsp;(Free)-15 Days Validity

							</h3>

							

							<div class="inner-contant">

								<div class="list-sec">

									<ul>
										<li class="list-cross">App Validity - Unlimited Time</li>
										<li>Android App</li>
										<li class="list-cross">iOS App</li>
										<li>Push Notification </li>
										<li>Monetize Your App</li>										
										<li>Different home page for Mobile app</li>

										<li>Different theme for mobile app   </li>

										<li>Select and customize launcher icon</li>

										<li>Upload your own custom icon</li>

										<li>Select and customize splash screen</li>

										<li>Upload your own splash screen </li>
										

										<li>Ads Free - i.e. no ads/brand name </li>

										<li>Allow to Build App in Real Time</li>

										

									</ul>
									<a href="<?php echo admin_url('admin.php?page=wapppresssettings'); ?>" class="btn btn-primary">Create App Now</a>
									
								</div>

								

								<div class="clear">

								</div>

							</div>

						

						</div>

					</div>

				
					
					
				  </div>
				  <!-- Second Section -->
				  <div class="col-sm-12 col-md-4 section">
							

					<div class="wrapper">

						<div class="contant-section">
						<h3>

							Android <br/><span>PRO VERSION<span> &nbsp; &nbsp;($24) &nbsp;<span> One Time Payment<span>

							</h3>

							

								<div class="sec-2">

								
									<div class="inner-contant">

										<div class="list-sec1">

											<ul>
												<li>App Validity - Unlimited Time</li>
												<li>Android App</li>
												<li class="list-cross">iOS App</li>
												<li>Push Notification - Unlimited</li>
												<li>Monetize Your App</li>										
												<li>Different home page for Mobile app</li>

												<li>Different theme for mobile app   </li>

												<li>Select and customize launcher icon</li>

												<li>Upload your own custom icon</li>

												<li>Select and customize splash screen</li>

												<li>Upload your own splash screen </li>
												

												<li>Ads Free - i.e. no ads/brand name </li>

												<li>Allow to Build App in Real Time</li>

												

											</ul>

											<a href="http://goo.gl/bcEb25" class="btn btn-primary">Upgrade to WappPress PRO</a>

											

											<span>

											<h2 style="padding:0">$24 <strong>Only</strong></h2>

											</span>

										</div>


										<div class="clear">

										</div>

									</div>

								</div>

							

							</div>

						</div>

					
				  </div>
				   <!-- Third Section -->
				  <div class="col-sm-12 col-md-4 section">
							

					<div class="wrapper">

						<div class="contant-section">
						<h3>

							iOS <br/><span>PRO VERSION<span> &nbsp; &nbsp;($24) &nbsp;<span> One Time Payment<span>

							</h3>

							

								<div class="sec-2">

								
									<div class="inner-contant">

										<div class="list-sec1">

											<ul>
												<li>App Validity - Unlimited Time</li>
												<li class="list-cross">Android App</li>
												<li >iOS App</li>
												<li>Push Notification  - Unlimited</li>
												<li>Monetize Your App</li>										
												<li>Different home page for Mobile app</li>

												<li>Different theme for mobile app   </li>

												<li>Select and customize launcher icon</li>

												<li>Upload your own custom icon</li>

												<li>Select and customize splash screen</li>

												<li>Upload your own splash screen </li>
												

												<li>Ads Free - i.e. no ads/brand name </li>

												<li>Allow to Build App in Real Time</li>

												

											</ul>

											 <a href="https://codecanyon.net/item/iwapppress-builds-ios-app-for-any-wordpress-website/15984730" class="btn btn-primary">Get iWappPress PRO</a>
										       <span>

											<h2 style="padding:0">$24 <strong>Only</strong></h2>

											</span>

										</div>


										<div class="clear">

										</div>

									</div>

								</div>

							

							</div>

						</div>

					
				  </div>
				  
				  
				  
				</div>
					
					
					
		
	
	

	<div class="section" style="margin-top:0px">

		<div class="wrapper">

			<div class="contant-section">

				<div class="sec-2">

					<h3> <u>Publish App</u> </h3>

					<p>

						<strong>If you need any help regarding publishing your app on Google Play or App Store </strong><span>  <a href="mailto:info@wapppress.com" class="btn btn-primary">contact US</a></span>

					</p>

				</div>

			</div>

		</div>

	</div>	

	</div>	


	<?php	

	require_once(  'footer.php' );

	}
	// Advance Setting Page 

public function advance_settings_page()
{
	require_once(  'header.php' );

	

	$dirIncImg  = trailingslashit(plugins_url('wapppress-builds-android-app-for-website'));

	$options = get_option('wapppress_settings');

	$args= array();	

	$all_themes = wp_get_themes( $args );

	//$check = isset( $options['wapppress_theme_switch'] ) ? esc_attr( $options['wapppress_theme_switch'] ) : '';

	$authorCheck = isset( $options['wapppress_theme_author'] ) ? esc_attr( $options['wapppress_theme_author'] ) : '';

	$dateCheck = isset( $options['wapppress_theme_date'] ) ? esc_attr( $options['wapppress_theme_date'] ) : '';

	$commentCheck = isset( $options['wapppress_theme_comment'] ) ? esc_attr( $options['wapppress_theme_comment'] ) : '';

	$frontpage_id2 =  get_option('page_on_front');
	$pushPostCheck 			= isset( $options['wapppress_push_post'] ) ? esc_attr( $options['wapppress_push_post'] ) : '';
	$pushPostEditCheck 		= isset( $options['wapppress_push_post_edit'] ) ? esc_attr( $options['wapppress_push_post_edit'] ) : '';
	$pushProductCheck 		= isset( $options['wapppress_push_product'] ) ? esc_attr( $options['wapppress_push_product'] ) : '';
	$pushProductEditCheck	= isset( $options['wapppress_push_product_edit'] ) ? esc_attr( $options['wapppress_push_product_edit'] ) : '';
	
	 ?>

	<!--div style="text-align:center">
	<div style='float:left;'>
	<h1 >Want To See How Your App Looks Like </h1 ></div>
	<div style='float:left;'><a href='#bulid1'><img src='<?php echo plugins_url( '../images/btn6.png',  __FILE__ ) ?>' /></a> </div>
	
	

	</div>
	<div style="clear:both">&nbsp;</div--->
	
	<div class="contant-section1">
		
		<div class="section">

		<div class="wrapper">

			<div class="contant-section">
				
				<div class="setting-head">

					<h3  id='settings'>ADVANCE SETTINGS [Optional]</h3>

					<img src="<?php echo plugins_url( '../images/line.png',  __FILE__ ) ?>" title="" alt=""/>

				</div>

				

				<!--===Setting Box Start===--->

				<div class="setting-box">

					<div class="inner_left">

						<div class="inner_header2">

							<div class="tabs">

								<div class="tab-content">

								<form method="post" action="options.php">

									<div id="tab1" class="tab active">

										<ul id="toggle-view">

										<?php

											// settings_fields( $option_group )

											settings_fields( 'wapppress_group' );

											// do_settings_sections( $page )

											do_settings_sections( __FILE__ );

											?>

											<li>

											<h3 class="test">Enter Your App name</h3>

											<span><img src="<?php echo plugins_url( '../images/arrow.png',  __FILE__ ) ?>" alt=""></span>

											<div class="panel">

												<p>

													<input class="app_input"  type="text" id="wapppress_name" name="wapppress_settings[wapppress_name]" value="WappPress<?php  //echo @$options['wapppress_name']; ?>" />

												</p>

											</div>

											</li>

											

											<li>

											<h3>Select Theme</h3>

											<span><img src="<?php echo plugins_url( '../images/arrow.png',  __FILE__ ) ?>" alt=""></span>

											<div class="panel">

												<p>

													<select name="wapppress_settings[wapppress_theme_setting]" id="wapppress_theme_setting"  class="app_input_select">

														<?php $the = array(); 

														foreach($all_themes as $theme_val =>$theme_name){ 

														 $nonce = wp_create_nonce('switch-theme_'.$theme_val);

														 $src = admin_url().'customize.php?action=preview&theme='.$theme_val;

														 $theme_val = $theme_val == 'option-none' ? '' : esc_attr( $theme_val ); 

														 echo $the[ $theme_val ] = '<option id="'.$src.'" value="'. $theme_val .'" '. selected( @$options['wapppress_theme_setting'],$theme_val, false) .'>'. esc_html( $theme_name ) .'</option>

														'."\n"; 

														} ?>

													</select>

												</p>

											</div>

											</li>

											<li>

											<h3>Use a unique homepage for your app</h3>

											<span><img src="<?php echo plugins_url( '../images/arrow.png',  __FILE__ ) ?>" alt=""></span>

											<div class="panel">

												<p>Start typing to search for a page, or enter a page ID. e.g. Sample</p>

												<p>

													<?php $frontpage_id1 =  get_option('page_on_front'); 

													if($frontpage_id1 !=@$options['wapppress_home_setting']){

													?>

													<input class="app_input"  type="text" id="wapppress_home_setting" name="wapppress_settings[wapppress_home_setting]" value="<?php echo @$options['wapppress_home_setting']; ?>" />

													<?php }else{ ?>

													<input class="app_input"  type="text" id="wapppress_home_setting" name="wapppress_settings[wapppress_home_setting]" value="" />

													<?php } ?>

												</p>

										<div class='wapppress_field_markup_text' id="wapppress_field_markup_text"></div>

											</div>

											</li>

											<li>

											<h3>Customize Your Theme</h3>

											<span><img src="<?php echo plugins_url( '../images/arrow.png',  __FILE__ ) ?>" alt=""></span>

											<div class="panel">

												<p>

													<input  type="checkbox" name="wapppress_settings[wapppress_theme_date]"  class="checkbox"  <?php checked( $dateCheck, 'on'.false ); ?> /> Display Date

												</p>

												<p>

													<input  type="checkbox" name="wapppress_settings[wapppress_theme_comment]"  class="checkbox"  <?php checked($commentCheck, 'on'.false ); ?> />  Display Comments

												</p>

												

											</div>

											</li>
											<li>

											<h3>Custom Push Notificaton Settings</h3>

											<span><img src="<?php echo plugins_url( '../images/arrow.png',  __FILE__ ) ?>" alt=""></span>

											<div class="panel">

												<p>

													<input  type="checkbox" name="wapppress_settings[wapppress_push_post]"  class="checkbox"  <?php checked( $pushPostCheck, 'on'.false ); ?> /> Send Push Notification on New Post

												</p>
												<p>

													<input  type="checkbox" name="wapppress_settings[wapppress_push_post_edit]"  class="checkbox"  <?php checked( $pushPostEditCheck, 'on'.false ); ?> /> Send Push Notification on Post Updation

												</p>
												<p>

													<input  type="checkbox" name="wapppress_settings[wapppress_push_product]"  class="checkbox"  <?php checked($pushProductCheck, 'on'.false ); ?> /> Send Push Notification on New Product

												</p>
												<p>

													<input  type="checkbox" name="wapppress_settings[wapppress_push_product_edit]"  class="checkbox"  <?php checked($pushProductEditCheck, 'on'.false ); ?> /> Send Push Notification on Product Updation

												</p>
												
												

											</div>

											</li>

										</ul>

									</div>

									

									<div class="save-btn">



<input   style='padding:0 !important' onclick="document.getElementById('bulid').scrollIntoView();return false;" type="image" src="<?php echo plugins_url( '../images/btn3.png',  __FILE__ ) ?>" value="Save Changes"  >
						
									</div>

									
								</div>


								</form>

								

							</div>

						</div>

					</div>

					<div class="wrap-right mobileFrame">
					<iframe frameborder="0" allowtransparency="no" src="https://wapppress.com/prw.php?shop_url=https://wapppress.com/demo/" sandbox="allow-forms allow-scripts" name="mobile_frame_demo" id="mobile_frame_demo" style="box-sizing: content-box;" >

						
						</iframe>

					</div>

					

					<div class="clear">

					</div>

				</div>

				<!--===Setting Box End===--->

				

				<!--===Android APP Box Start===--->

			

				<!--===Android APP Box End===--->

				

			</div>
		</div>

	</div>

</div>

<?php require_once( 'footer.php' );

}

	// Setting Page 

public function maker_settings_page()
{

	require_once(  'header.php' );

	$dirIncImg  = trailingslashit(plugins_url('wapppress-builds-android-app-for-website'));

	$options = get_option('wapppress_settings');

	$args= array();	

	$all_themes = wp_get_themes( $args );


	$authorCheck = isset( $options['wapppress_theme_author'] ) ? esc_attr( $options['wapppress_theme_author'] ) : '';

	$dateCheck = isset( $options['wapppress_theme_date'] ) ? esc_attr( $options['wapppress_theme_date'] ) : '';

	$commentCheck = isset( $options['wapppress_theme_comment'] ) ? esc_attr( $options['wapppress_theme_comment'] ) : '';

	$frontpage_id2 =  get_option('page_on_front');
	$pushPostCheck 			= isset( $options['wapppress_push_post'] ) ? esc_attr( $options['wapppress_push_post'] ) : '';
	$pushPostEditCheck 		= isset( $options['wapppress_push_post_edit'] ) ? esc_attr( $options['wapppress_push_post_edit'] ) : '';
	$pushProductCheck 		= isset( $options['wapppress_push_product'] ) ? esc_attr( $options['wapppress_push_product'] ) : '';
	$pushProductEditCheck	= isset( $options['wapppress_push_product_edit'] ) ? esc_attr( $options['wapppress_push_product_edit'] ) : '';
	
	
 ?>

	
<div class="contant-section1">
		
	<div class="section">

		<div class="wrapper">

			<div class="contant-section">
				
			
				<div class="setting-head">

					<h3  id='settings'>BUILD APP</h3>

					<img src="<?php echo plugins_url( '../images/line.png',  __FILE__ ) ?>" title="" alt=""/>

				</div>

				

				<!--===Setting Box Start===--->

				<div class="setting-box">
					
					<div class="inner_left">
					<!--Inner Left Start===--->
						<?php
							$current_user = wp_get_current_user();
							$user_name=$current_user->user_login;
							$user_email=$current_user->user_email;
							
							?>				
							<style>
							.android-icon 
							{
								background-image: url('<?php echo plugins_url( '../images/app_logo/ic_launcher.png',  __FILE__ ) ?>'); /* Replace with the actual path to your Android icon */
								border-radius: 50%;
							}</style>
						
						<div id='errorResponse' class='msgAlert'></div>
						<div class="setting-form">
							
							
							<div class="supportForms_input">

								<p>

									 App Name (<em><span class='fon_cls'>Please enter only unique app name.</span></em>) :- <br /><input type="text" name='app_name_temp' id='app_name_temp' placeholder="Please enter only unique app name" value="<?php //echo @$options['wapppress_name']; ?>" />

								</p>

							</div>
						</div>

						<!---------------Image Section Start-------------------->
						<div class="row">
						   
							<!-- Wrapper for the next two sections with border -->
							<div class="col-md-4" style="padding-top:10px;">
							
							  <div class="border-wrapper">
							  <div class="col-md-12">
									<div class="supportForms_input">
										<p><u>Launcher Icon</u></p>
									</div>
								
							   </div>
								<div class="row">
									  <!-- 1 section -->
									  <div class="col-md-12">
													
												 <div class="section section-mrg" >
														<div class="launcher-icon android-icon"></div>
												</div>				
										
									  </div>
								</div>
								<div class="row">
									  <!-- 2 section -->
									  <div class="col-md-6">
										
													<div class="section" style="height: 100%;" >
														  <!-- Trigger the modal with a button -->
														<button type="button" id="changeIcon" class="btn btn-info" data-toggle="modal" data-target="#iconModal" style="margin-left:15px">Change</button>
													  </div>
										
									  </div>
								</div>
							  </div>
							</div>
							<!-- Wrapper for the last two sections with border -->
							<div class="col-md-8" style="padding-top:10px;">
							  <div class="border-wrapper">
							  <div class="col-md-12">								
									<div class="supportForms_input">
									
										<p><u>Splash Screen</u></p>
									</div>
							   </div>
								<div class="row">
								  <!-- 3 section -->
								  <div class="col-md-6">
									
											<div class="section section-mrg">
												<div id="imagePreviewContainer">
												<div id="imagePreview"><img src="<?php echo plugins_url( '../images/app_splash_screen/splash_screen.png',  __FILE__ ) ?>" style="max-width: 84px; max-height: 84px;"  /></div>
											</div>
										  </div>
									
								  </div>
								  <!-- 4 section -->
								  <div class="col-md-6">
									
									
												<div class="section">
													
													 <div class="section">
													  <div class="supportForms_input">
															<p style="margin-left:20px">Background Color</p>
														</div>
														 <p><input type="color" id="BgcolorPicker" onchange="changePreviewColor()" />   </p>
														
														
													 </div>
													 <button type="button" id="changeSplash" class="btn btn-info" data-toggle="modal" data-target="#iconModal"style="margin-left:20px">Change</button> 
												</div>
									
									
								  </div>
								</div>
							  </div>
							</div>
						  </div>
						<!---------------Image Section End-------------------->
						<!---------------form Section Start-------------------->
						<form role="form" action="#"  id="customer_support">

						<input type="hidden" name='dirPlgUrl1' id='dirPlgUrl1' value='<?php echo $dirIncImg; ?>'/>

						<div class="setting-form">

							<div class="supportForms_input" style="display:none">
								<p>

									Name:- <br /><input type="text" name='name' id='name' value="WappPress" />

								</p>

							</div>
									
							<div class="supportForms_input" >
								
								<p>

							
								<input type="hidden" name='app_splash_bg_color' id='app_splash_bg_color' value="#FFFFFF"  />
								</p><br/>
								

							</div>
							
							<div class="supportForms_input">

								<p>

									<input type="hidden" name='app_name' id='app_name' value="WappPress<?php //echo @$options['wapppress_name']; ?>" />

								</p>

							</div>
						<?php  $options_upgrdate = get_option( 'wapppress_upgrade' );	?>
					<input type="hidden" name='license' id='license' placeholder='CodeCanyon "Item Purchase Code"' value="<?php echo isset( $options_upgrdate['wapppress_pro_license'] ) ? $options_upgrdate['wapppress_pro_license'] : ''; ?>"  />
					<div class="supportForms_input">
					<p>Ads[Optional]</p>
									<p>

									<input style='width:0% !important' type="checkbox" name='adbmob_interstitial' id='adbmob_interstitial'  onclick='return show_AdMob();'  value='0'/>
									
									Ads (<em><span class='fon_cls'>Interstitial/Banner</span></em>):-</p>
								 <p id="show_adbmob_interstitial" style="display:none">
									<br />
									Interstitial(Ad unit ID):- <br /><input type="text" name='interstitial_unit_id' id='interstitial_unit_id' placeholder='e.g. ca-app-pub-????????????????/??????????' />
									
								 </p>
									

								
							</div>
					<p>&nbsp;</p>
							<div class="supportForms_input">
							<p>App Type</p>
									<p>
										<input style='width:0% !important' type="radio" name='app_type' id='app_type_aab'   checked  value='1'/>									
									.aab (<em><span class='fon_cls'>Choose this option if you want to upload your app to Google play store.</span></em>)

									</p>
							</div>
							<div class="supportForms_input">
									<p>

									<input style='width:0% !important' type="radio" name='app_type' id='app_type_apk'   value='2'/>									
									.apk (<em><span class='fon_cls'>Choose this option if you don't want to upload your app to Google play store.</span></em>)
										</p>
							</div>
														

							<div class="sve_change_btn sve_change_btn2">
											
								<div class="row">								
									<div class="col-md-6">
									<input id="submit" class='submit-build btn btn-info btn-lg'  type="submit" value="Build / Generate App" name="submit">
									</div>
									<div class="col-md-6">
										<span id="build-btn-load" style="display:none"><img src="<?php echo plugins_url( '../images/loading-img.gif',  __FILE__ ) ?>" /></span>	
									
										<span id='dwnloakId' style="display: block; float:right;" ></span>
									</div>
								</div>
									<p>&nbsp;</p>
								<p>
								<em><span class='fon_cls'>(Click on "BUILD/Generate App" butoon  to create app for your website now. )</span></em>	
								</p>	
							</div>
<div style="padding:10px;color:#000080;font-size: 14px;">NOTE: This is just a limited time validity demo app to allow you to test app in real-time.</div>
							
						</div>
				

						</form>
			
						<script>

						function changePreviewColor() {
							var BgcolorPicker = document.getElementById("BgcolorPicker");
							var imagePreview = document.getElementById("imagePreview");
							
							// Set the background color of the image preview
							imagePreview.style.backgroundColor = BgcolorPicker.value;
							jQuery('#app_splash_bg_color').val(BgcolorPicker.value);
							
						}
			
		
						jQuery(document).ready(function(){
						jQuery('#app_name_temp').on('change', function (ev)
							{
								jQuery('#app_name').val(jQuery('#app_name_temp').val());
							});
							function convertToURL(text) {
								// Check if text already starts with 'https://' or 'http://'
								if (text.toLowerCase().startsWith('https://') || text.toLowerCase().startsWith('http://')) {
									return text.trim(); // Return the text as it is
								} else {
									// Replace spaces with hyphens
									var url = text.trim().replace(/\s+/g, '-');
									// Convert to lowercase
									url = url.toLowerCase();
									// Add 'https://' prefix
									url = 'https://' + url;
									return url;
								}
							}
						});
						
						</script>
					<!--Inner Left End===--->
					</div>
					

					<div class="wrap-right mobileFrame">
						<iframe frameborder="0" allowtransparency="no" src="https://wapppress.com/prw.php?shop_url=https://wapppress.com/demo/" sandbox="allow-forms allow-scripts" name="mobile_frame_demo" id="mobile_frame_demo" style="box-sizing: content-box;" >						
						</iframe>

					</div>

					

					<div class="clear"></div>

				</div>

				<!--===Setting Box End===--->

				

				<!--===Android APP Box Start===--->

				<div id='bulid'>&nbsp;</div>

				<div class="sec-2" style="border-bottom:0px;">
				
			

								<!---------------------------------->
								<div class="container">


									  <!-- Modal -->
									  <div class="modal fade" id="iconModal" role="dialog">
										<div class="modal-dialog">
										
										  <!-- Modal content-->
										  <div class="modal-content">
											<div class="modal-header">
											
											  <h4 class="modal-title img-title"></h4><button type="button" id="closeIconModel" class="btn btn-primary" data-dismiss="modal">Close</button>
											</div>
											<div class="modal-body">
											<!--==== New Code Start ====-->
												
												<div class="container">
												<div class="card" style="border:none;margin:0;padding:0" >
													
													<div class="card-body">														
														<div>
																<div class="form-group">
																	
																	<input type="file" id="image" class="form-control" required>
																	<span id="error" class="text-danger"></span><br> 
																</div>
																
															</div>
															<div class="text-center">
																<div id="upload-demo"></div>
															</div>
															
															<div style="display:none">
																<div id="preview-crop-image" style="background:#9d9d9d;width:300px;padding:50px 50px;height:300px;"></div>
															</div>
															
															<input type="hidden" id="img-type" value="1">
														
													</div>
												</div>
											</div>        
											<!---------------------------------->	
											</div>
											<div class="modal-footer">
											 <button class="btn btn-primary btn-upload-image">Apply Change</button>
											</div>
										  </div>
										  
										</div>
									  </div>
									  
									</div>

								<!---------------------------------->
								
				</div>

			</div>

				<!--===Android APP Box End===--->

				

		</div>
	</div>

</div>


<script type="text/javascript">
var resize = jQuery('#upload-demo').croppie({
			enableExif: true,
			enableOrientation: true,    
				viewport: { // Default { width: 100, height: 100, type: 'square' } 
				width: 100,
				height: 100,
				type: 'square' //square
			},
			boundary: {
				width: 125,
				height: 125
			}
		});

		//$("input").prop('required',true);
		jQuery('#image').change(function () {
			var ext = this.value.match(/\.(.+)$/)[1];
				switch (ext) {
					case 'jpg':
					case 'png':
					case 'jpeg':
					case 'pneg':
						jQuery('#error').text("");
						jQuery('button').attr('disabled', false);
						break;
				default:
					jQuery('#error').text("File must be of type jpg,png,pneg,jpeg.");
					jQuery('button').attr('disabled', true);
					this.value = '';
			}
		});

		jQuery('#image').on('change', function () { 
			var reader = new FileReader();
				reader.onload = function (e) {
				resize.croppie('bind',{
					url: e.target.result										
				}).then(function(){
					console.log('jQuery bind complete');
				});
			}
				reader.readAsDataURL(this.files[0]);
		});

		jQuery('#changeIcon').on('click', function (ev)
		{
			jQuery('.img-title').text('Select Launcher Icon');
			jQuery('#img-type').val(1);
		});
		jQuery('#changeSplash').on('click', function (ev)
		{
			jQuery('.img-title').text('Select Splash Screen');
			jQuery('#img-type').val(2);
		});
				 
		jQuery('.btn-upload-image').on('click', function (ev) {
			resize.croppie('result', {
				type: 'canvas',
				size: 'viewport'
			}).then(function (img) {
				var imgtype= jQuery('#img-type').val()	;		
				 jQuery.ajax({
					url: ajaxurl, // WordPress AJAX URL
					type: 'POST',
					data: {
						action: 'upload_crop', // Action registered in functions.php
						image: img,
						imagetype: imgtype
					},
					success: function(data) {
						if(imgtype>1)
						{												
						 document.getElementById("imagePreview").innerHTML = '<img src="' + img + '" style="max-width: 84px; max-height:84px;"  />';
						}else{
							jQuery('.android-icon').css('background-image', 'url(' + img + ')');
						}
						document.getElementById("imagePreviewContainer").style.display = "block";
						jQuery('#closeIconModel').click();
						return false;
						
					},
					error: function(xhr, status, error) {
						//console.log('AJAX Error:', xhr.responseText); // Log AJAX error
					}
				});
				
			
			});
		});
						
jQuery(window).on('load', function(){
	jQuery("#build-btn-load").hide();
	});
		

function show_AdMob()
{
		
	if(jQuery('#adbmob_interstitial').val()==0)
	{
		jQuery('#show_adbmob_interstitial').show('slow');
		jQuery('#adbmob_interstitial').val('1')
		
	}else{
		jQuery('#show_adbmob_interstitial').hide('fast');
		jQuery('#adbmob_interstitial').prop('checked', false);
		jQuery('#adbmob_interstitial').val('0')
		
	}
				

}


jQuery.validator.addMethod("alphanumeric", function(value, element) {

	return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);

}, "Only allow alpha/numeric.");

jQuery( "#customer_support" ).validate({

			rules: {

				name:{

					required: true

				},

				semail: {

					required: true,

					email:true

				},

				

				app_logo_text: {

				  required: function() {

					var a_logo =jQuery('input:radio[name=custom_launcher_logo]:checked').val();

					 if (a_logo==1){

						 return true;

					 }else{

						 return false;

					 }

				  },

				  maxlength:5

				},

				 

				app_splash_text: {

				  required: function() {

					var splash_logo =jQuery('input:radio[name=custom_splash_logo]:checked').val();

					 if (splash_logo==1){

						 return true;

					 }else{

						 return false;

					 }

				  },

				  maxlength:10

				},
				app_name_temp: {

					required: true

				}

			},

			messages: {

					name: {

						required: "Please enter your name."

					},

					semail: {

						required: "Please enter your email."

					},

					app_name_temp: {

						required: "Please enter only unique app name."

					},
					app_logo_text: {

						required: "Please enter your app icon text."

					},

					app_splash_text: {

						required: "Please enter your app splash screen text."

					}

				},

				submitHandler: function(form) {
					//alert("App Creation has been disabled in Demo. Thanks");

				  ajax_wapp_api_form();

			}

	});
</script>
<?php require_once( 'footer.php' );

}
function pro_admin_notice__success() 
{
	$options_pro = get_option('wapppress_upgrade');
	if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $options_pro['wapppress_pro_license'])){
	?>
	<div class="notice notice-success is-dismissible notice-error">
	<p><?php _e( 'You are using WappPress BASIC VERSION (free) <strong>GET PRO VERSION</strong> to get app <strong>Validity for Unlimited Time</strong> <a href="http://goo.gl/bcEb25" target="_blank" style="color:#f89400" >Click here to upgrade to WappPress PRO VERSION (Android) </a>', 'wapppress-update' ); ?></p>
	</div>
	<?php } ?>
	<?php if ( !is_plugin_active('iwapppress/iWappPress.php') ) {?>
	<div class="notice notice-success is-dismissible notice-error">
	<p><?php _e( 'Create <strong>iOS app</strong> with <strong>Unlimited Time Validity</strong> using <strong>iWappPress</strong> <a href="https://codecanyon.net/item/iwapppress-builds-ios-app-for-any-wordpress-website/15984730" target="_blank" style="color:#f89400" >Click here to upgrade to iWappPress PRO VERSION (iOS)</a>', 'iwapppress-update' ); ?></p>
	</div>
	<?php } ?>

<?php
}
	
//Upgrade WappPress

	 function register_upgrade() {
        register_setting( 'wapppress_upgrade_group', 'wapppress_upgrade', array( $this, 'upgrade_validate' ) );
    }

    function upgrade_validate( $arr_input ) {
        $options_upgrdate = get_option( 'wapppress_upgrade' );
        $purchase_code = isset( $arr_input['wapppress_pro_license'] ) ? trim( $arr_input['wapppress_pro_license'] ) : '';

        if ( preg_match( "/^(\w{8})-((\w{4})-){3}(\w{12})$/", $purchase_code ) ) {
            $options_upgrdate['wapppress_pro_license'] = $purchase_code;
        } else {
            $options_upgrdate['wapppress_pro_license'] = 'Invalid Code';
        }
        return $options_upgrdate;
    }
//App Core Setting function	

	function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback )

		register_setting( 'wapppress_group', 'wapppress_settings', array($this, 'settings_validate') );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )

			{

				//

			}

	}
	function settings_validate($arr_input) {
		
		$frontpage_id =  get_option('page_on_front');

		$options = get_option('wapppress_settings');

		@$options['wapppress_name'] = trim( $arr_input['wapppress_name'] );

		//@$options['wapppress_theme_switch'] = trim( $arr_input['wapppress_theme_switch'] );

		@$options['wapppress_theme_setting'] = trim( $arr_input['wapppress_theme_setting'] );

		if(!empty($arr_input['wapppress_home_setting'])){

			@$options['wapppress_home_setting'] =	trim( $arr_input['wapppress_home_setting']);

		}else{

			@$options['wapppress_home_setting'] =	trim( $frontpage_id );

		}

		@$options['wapppress_theme_author'] = trim( $arr_input['wapppress_theme_author'] );

		@$options['wapppress_theme_date'] = trim( $arr_input['wapppress_theme_date'] );

		@$options['wapppress_theme_comment'] = trim( $arr_input['wapppress_theme_comment'] );
		@$options['wapppress_push_post'] 			= trim( $arr_input['wapppress_push_post'] );
		@$options['wapppress_push_post_edit']		= trim( $arr_input['wapppress_push_post_edit'] );
		@$options['wapppress_push_product'] 		= trim( $arr_input['wapppress_push_product'] );
		@$options['wapppress_push_product_edit'] 	= trim( $arr_input['wapppress_push_product_edit'] );

		return $options;

	}

	

	// Theme Page 

	public function maker_theme_page(){

	require_once( 'header.php' );

	$args = array();

	$themes = wp_get_themes( $args );

	$dirIncImg  = trailingslashit( plugins_url('wapppress-builds-android-app-for-website') );

?>

<!--===Theme Listing Box Start===--->

<div class="contant-section1">	

	<div class="section">

		<div class="wrapper">

			<div class="contant-section">

				<h5>

				<img src="<?php echo plugins_url( '../images/img1.png',  __FILE__ ) ?>" title="" alt=""/> &nbsp; <i>All Themes Listing</i>

				</h5>

				<div class="wrapper">

					<div class="container_main">

						<?php $the = array(); foreach($themes as $theme_val => $theme_name){

						$options = get_option('wapppress_settings');

						$currentTheme= $options['wapppress_theme_setting'];

						if($currentTheme==$theme_val){

						$theme_img = get_theme_root_uri().'/'.$theme_val.'/'.'screenshot.png';

						$url = esc_url(add_query_arg( array('wapppress' => true,'theme' =>$currentTheme,), admin_url( 'customize.php' ) ));

						 ?>

						<div class="theme-box-main">

							<div class="theme_box">

								<span><img src="<?php echo $theme_img?>" alt="<?php echo $theme_name?>" width='244' height="225" /></span>

								<a class="customize" href="<?php  echo $url; ?>">Customize</a>

							</div>

							<p>

								<img src="<?php echo plugins_url( '../images/shadow.png',  __FILE__ ) ?>" title=""/>

							</p>

						</div>

						<?php } } ?>

						<?php

						$the = array(); foreach($themes as $theme_val => $theme_name){

						$options = get_option('wapppress_settings');

						$currentTheme= $options['wapppress_theme_setting'];

						if($currentTheme!=$theme_val){

						$theme_img = get_theme_root_uri().'/'.$theme_val.'/'.'screenshot.png';

						$nonce = wp_create_nonce('switch-theme_'.$theme_val);

						?>

						<div class="theme-box-main">

							<div class="theme_box">

								<span><img src="<?php echo $theme_img; ?>" alt="<?php echo $theme_name; ?>" width='244' height="225" /></span>

								<a class="customize" style="opacity:0.5;pointer-events: none;" href="<?php  echo $url; ?>">Customize</a>

							</div>

							<p>

								<img src="<?php echo plugins_url( '../images/shadow.png',  __FILE__ ) ?>" title=""/>

							</p>

						</div>

						<?php } } ?>

					</div>

					<div class="clear"></div>

				</div>

			</div>

		</div>

	</div>

</div>

<!--===Theme Listing Box End===--->



<?php require_once( 'footer.php' );

}	



// Push Notification Page 

public function maker_push_page(){

require_once( 'header.php' );

$args =array();

$themes = wp_get_themes( $args );

$dirIncImg  = trailingslashit( plugins_url('wapppress-builds-android-app-for-website') );

$dirPath1  = trailingslashit( plugin_dir_path( __FILE__ ) );

?>

<!--===Push Notification Box Start===--->

<div class="contant-section1">	

	<div class="section">

	<div class="wrapper">

		<div class="contant-section">

			<div class="setting-head">

				<h3>Push Notifications</h3>

				<img src="<?php echo plugins_url( '../images/line.png',  __FILE__ ) ?>" title="" alt=""/>

			</div>

			<div class="sec-2" style="border:none;">

				<div class="setting-sec">


					<div class="setting-form">

						<div class="headingIn">

							You can send messages/alerts or push notifications to all the app installations as and when you want to

							send. This message/alert would be delivered instantly to all the users who have installed your Mobile App. This would help in reaching out to your users for advertisement, new product notifications , offers or any message/alert that you want to sent to your users.

						</div>

						<form id='push_from' name='push_from'>

						<div id='msgId' class='msgAlert'></div>

							<div class="supportForms_input">

								<p>Message:- <br /><textarea name="push_msg" id='push_msg'></textarea></p>

							</div>

							<br/>

							

							

							<input type="hidden" name='dirPath1' id='dirPath1' value='<?php echo $dirPath1; ?>'/>

							<input type="hidden" name='dirPlgUrl1' id='dirPlgUrl1' value='<?php echo $dirIncImg; ?>'/>

							

							<div class="sendAlert">

								<input id="push_btn"  type="image" src="<?php echo plugins_url( '../images/send-alert.png',  __FILE__ ) ?>" onclick="alert('Sending Push Notification has been disabled in Demo. Thanks'); return false;
" value="Send Alert" name="push_btn">&nbsp;

							</div>

						</form>

						

						

						<script type="text/javascript">

						
							jQuery( "#push_from" ).validate({

									rules: {

										push_msg:{

											required: true

										}

									},

									messages: {

											push_msg: {

												required: "Please enter your message."

											}

										},

										submitHandler: function(form) {

										 ajax_wapp_push_form();

									}

							});

							

							

							</script>

					</div>

				

				</div>

			</div>

		</div>

	</div>

  </div>

</div>

<!--===Push Notification Box End===--->



<?php require_once( 'footer.php' );

}
// Upgrade WappPress Page 

public function upgrade_wapppress_page(){

require_once( 'header.php' );

$args =array();

$themes = wp_get_themes( $args );

$dirIncImg  = trailingslashit( plugins_url('wapppress-builds-android-app-for-website') );

$dirPath1  = trailingslashit( plugin_dir_path( __FILE__ ) );
// Enable error reporting for debugging
?>

<!--===Push Notification Box Start===--->

<div class="contant-section1">	

	<div class="section">

	<div class="wrapper">

		<div class="contant-section">

			<div class="setting-head">

				<h3>Upgrade</h3>

				<img src="<?php echo plugins_url( '../images/line.png',  __FILE__ ) ?>" title="" alt=""/>

			</div>

			<div class="sec-2" style="border:none;">

				<div class="setting-sec">


					<div class="setting-form">
					<?php $options_upgrdate = get_option('wapppress_upgrade'); ?>	
					<?php if (!preg_match("/^(\w{8})-((\w{4})-){3}(\w{12})$/", $options_upgrdate['wapppress_pro_license'])){ ?>
						<p>You are using WappPress BASIC VERSION (free)<br/>
						<strong>Upgrade to Android PRO </strong> and get <strong> Unlimited Time</strong> App Validity&nbsp;<a href="http://goo.gl/bcEb25" class="btn btn-primary">Click here to upgrade Now</a></p>
					<?php }else{ ?>
					<p>You are using <strong>WappPress Pro (Andriod)</strong></p>
					<?php }?>
						<form method="post" action="<?php echo esc_url( admin_url('options.php') ); ?>">
							<?php settings_fields( 'wapppress_upgrade_group' ); ?>
						<p>Enter (Codecanyon) Purchase Code .</p>
						<p>
												
							<input class="app_input" type="text" id="wapppress_pro_license" name="wapppress_upgrade[wapppress_pro_license]" value="<?php echo isset( $options_upgrdate['wapppress_pro_license'] ) ? $options_upgrdate['wapppress_pro_license'] : ''; ?>" />
						</p>
						<?php submit_button( 'Submit' ); ?>
					</form>

						

						</div>

						

					</div>

				

				</div>

			</div>

		</div>

	</div>

  </div>



<!--===Push Notification Box End===--->



<?php require_once( 'footer.php' );

}
///////////////////////////////////////////////////////////////////////////////////
public function upload_crop() {
    // Ensure that the request came from a logged-in user with the necessary permissions
    if (!current_user_can('manage_options')) {
        return;
    }

    $p = trailingslashit(plugin_dir_path(__FILE__));
    $plugin_path = str_replace('includes/', '', $p);

    // Define the directory path based on the 'imagetype'
    $directory = ($_POST['imagetype'] == 2) ? 'app_splash_screen' : 'app_logo';
    $directory_path = $plugin_path . "/images/" . $directory;

    // Ensure that the directory exists and is writable
    if (!file_exists($directory_path)) {
        if (!mkdir($directory_path, 0777, true)) {
            // Directory creation failed, handle the error (e.g., log, display error message)
            return;
        }
    }

    // File Type Validation
    $image_data = isset($_POST['image']) ? $_POST['image'] : '';
    if (empty($image_data)) {
        // Image data is empty, handle the error (e.g., log, display error message)
        return;
    }

    // Decode the base64-encoded image data
    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    $image_data = base64_decode($image_data);
    if (!$image_data) {
        // Failed to decode image data, handle the error (e.g., log, display error message)
        return;
    }

    // File Type Validation
    $image_info = getimagesizefromstring($image_data);
    if (!$image_info || !in_array($image_info['mime'], array('image/png', 'image/jpeg', 'image/gif'))) {
        // Invalid or unsupported image format, handle the error (e.g., log, display error message)
        return;
    }

    // File Size Limit
    $max_file_size = wp_max_upload_size(); // Get the maximum upload size from WordPress settings
    if (strlen($image_data) > $max_file_size) {
        // Image size exceeds the maximum upload size, handle the error (e.g., log, display error message)
        return;
    }

    // Generate a unique filename for the uploaded image
    $image_name = ($_POST['imagetype'] == 2) ? 'splash_screen.png' : 'ic_launcher.png';
    $file_path = $directory_path . '/' . $image_name;

    // Save the decoded image data to the file
    if (file_put_contents($file_path, $image_data) !== false) {
        // Set proper file permissions
        chmod($file_path, 0644); // Adjust permissions as needed

        // Optionally, perform additional actions (e.g., update database, display success message)
    } else {
        // Failed to save the image, handle the error (e.g., log, display error message)
        return;
    }
}
///////////////////////////////////////////////////////////////////////////////////

//Create App 

public function  create_app(){

$p  = trailingslashit( plugin_dir_path( __FILE__ ) );	

$plugin_path = str_replace('includes/', '', $p);

ini_set('memory_limit', '2048M');

set_time_limit(300);

//Android API Form Start

if( isset($_POST['type']) && $_POST['type'] =='api_create_form') {
//Get Current Website URL

	function curl_site_url() {

		 $pageURL = 'http';

		 if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

		 $pageURL .= "://";

		 if ($_SERVER["SERVER_PORT"] != "80") {

		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];

		 } else {

		  $pageURL .= $_SERVER["SERVER_NAME"];

		 }

		 $subDirURL='';

		 if(!empty($_SERVER['SCRIPT_NAME'])){

			 $subDirURL .= str_replace("/wp-admin/admin-ajax.php","",$_SERVER['SCRIPT_NAME']);

		 }

		 return $pageURL.$subDirURL;

	}

	

	$name = $_POST['name'];	

	$email = $_POST['semail'];	

	$website = curl_site_url();
							
		

	$dirPlgUrl1 = $_POST['dirPlgUrl1'];

	$ap = $_POST['ap'];	

	$ip = $_POST['ip'];	

	$file = $_POST['file'];	

	function wcurlrequest($ac,$d_name,$an,$data) {

		set_time_limit(300);

		$fields = '';

		foreach ($data as $key => $value) {

			$fields .= $key . '=' . $value . '&';

		}

		rtrim($fields, '&');

	
		$post = curl_init();

			curl_setopt($post, CURLOPT_URL,$ac);

			curl_setopt($post, CURLOPT_VERBOSE, 0);  

			curl_setopt($post, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($post, CURLOPT_SSL_VERIFYHOST, false);

			curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($post, CURLOPT_CONNECTTIMEOUT, 10);

			//curl_setopt($post, CURLOPT_TIMEOUT, 900);

			curl_setopt($post, CURLOPT_TIMEOUT, 300);

			$agent = 'Mozilla/5.0 (X11; U; Linux x86_64; pl-PL; rv:1.9.2.22) Gecko/20110905 Ubuntu/10.04 (lucid) Firefox/3.6.22';

			if(!empty($_SERVER['HTTP_USER_AGENT'])){

				$agent = $_SERVER['HTTP_USER_AGENT'];

			}

			curl_setopt($post, CURLOPT_USERAGENT, $agent);

			curl_setopt($post, CURLOPT_FAILONERROR, 1);

			curl_setopt($post, CURLOPT_POST, count($data));

			curl_setopt($post, CURLOPT_POSTFIELDS, $fields);

			

		$result = curl_exec($post);

	    $code = curl_getinfo($post, CURLINFO_HTTP_CODE);

        $success = ($code == 200);

        curl_close($post);

        if (!$success) {

			 setcookie( 'wapppress_proxy', 'true', time() + ( DAY_IN_SECONDS * 100 ) );

			 $str = "0~test";			

			 wp_send_json_success( $str );

			 exit();

        } else {

		

			if($result!=0)

			 {
					if($result==5)
					{
						$str = "5~test";	

						wp_send_json_success( $str );

						exit();
					}
					else if($result==9)
					{
						$str = "9~test";	

						wp_send_json_success( $str );

						exit();
					}
					else{
						//Save comment Response
						global $wpdb;
						$tablename = $wpdb->prefix.'wappcomment';
						$all_data = $wpdb->get_row( 'SELECT * FROM '.$tablename.'');
						
						if(!empty($all_data)){
							$data = array(
								'wapp_response'=>$result,
								'wapp_date'=>date('Y-m-d')
							);
							$where_arr = array(
								'wapp_id'=>$all_data->wapp_id
							);
							$wpdb->update( $tablename, $data, $where_arr );
						}else{
							$data = array(
								'wapp_response'=>$result,
								'wapp_date'=>date('Y-m-d')
							);	
							$wpdb->insert( $tablename, $data);
						}
						

						$d_name = str_replace("-","_",$d_name);

						$str = '1'.'~'.$d_name;

						wp_send_json_success( $str );

						  exit();				
					 }
				}else{

					setcookie( 'wapppress_proxy', 'true', time() + ( DAY_IN_SECONDS * 100 ) );

					$str = "0~test";					

					wp_send_json_success( $str );

					exit();

					

				}

		}	

	

	}



	function get_domain($url){

	  $pieces = parse_url($url);

	  $domain = isset($pieces['host']) ? $pieces['host'] : '';

	  if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,10})$/i', $domain, $regs)) {

		//
		function isLetter($domain_name) {
		  return preg_match('/^\s*[a-z,A-Z]/', $domain_name) > 0;
		}
		if(isLetter($regs['domain']))
		{
			 return $regs['domain'];
		}else{
			 return "com_".$regs['domain'];			
		}
		//

	  }

	  return false;

	}

	

	$domain_name = get_domain($website); 

	$domain_arr= explode('.',$domain_name);

	$domain_fname = $domain_arr[0];
	$app_name = $_POST['app_name'];
	$app_logo_name = $plugin_path."/images/app_logo/ic_launcher.png";
	$base64_app_logo= base64_encode(file_get_contents($app_logo_name));
	
	
	//$app_splash_image =$plugin_path."/images/app_splash_screen/splash_screen.png";
	$app_splash_image =$plugin_path."/images/app_splash_screen/splash_screen.png";
	$base64_app_splash= base64_encode(file_get_contents($app_splash_image));	

	$data = array(
			"name" => $_POST['name'],
			"app_name" => $_POST['app_name'],
			"base64_app_logo" => $base64_app_logo,
			"base64_app_splash" => $base64_app_splash,
			"email" => $_POST['semail'],
			"app_splash_bg_color" => $_POST['app_splash_bg_color'],
			"license" => $_POST['license'],			
			"interstitial_unit_id" => $_POST['interstitial_unit_id'],
			"banner_unit_id" => $_POST['banner_unit_id'],
			"website" => $website,
			"domain_name"=>$domain_name,
			"domain_fname"=>$domain_fname,
			'app_site_url'=>$dirPlgUrl1

		);

	

	$custom_launcher_logo = $_POST['custom_launcher_logo'];

	$custom_splash_logo = $_POST['custom_splash_logo'];

	

	if(isset($custom_launcher_logo) && $custom_launcher_logo =='0'){

		$data['app_launcher_logo_name'] = 'ic_launcher.png';

		$data['app_push_icon'] = 'ic_stat_gcm.png';

	}elseif(isset($custom_launcher_logo) && $custom_launcher_logo =='1'){

		$data['app_logo_color'] = $_POST['app_logo_color'];

		$data['app_logo_text_color'] = $_POST['app_logo_text_color'];

		$data['app_logo_text'] = $_POST['app_logo_text'];

		$data['app_logo_text_font_family'] = $_POST['app_logo_text_font_family'];

		$data['app_logo_text_font_size'] = $_POST['app_logo_text_font_size'];

	}

	

	

	if(isset($custom_splash_logo) && $custom_splash_logo =='0'){

		$data['app_splash_screen_name'] = 'splash_screen.png';

	}elseif(isset($custom_splash_logo) && $custom_splash_logo =='1'){

		$data['app_splash_color'] = $_POST['app_splash_color'];

		$data['app_splash_text'] = $_POST['app_splash_text'];

		$data['app_splash_text_color'] = $_POST['app_splash_text_color'];

		$data['app_splash_text_font_family'] = $_POST['app_splash_text_font_family'];

		$data['app_splash_text_font_size'] = $_POST['app_splash_text_font_size'];

	}

	





	// cURL Enable/Disable Function

	function _is_curl_installed() {

		if  (in_array  ('curl', get_loaded_extensions())) {

			return true;

		} else {

			return false;

		}

	}

	

	$whitelist = array('127.0.0.1', "::1",'localhost');
	$whitelist = array();



	// Check cURL Enable/Disable 

	if (_is_curl_installed()) {

		if(in_array($_SERVER['SERVER_NAME'], $whitelist)){

			$str = "3~test";

			wp_send_json_success( $str );

			exit();

		}else{	

			wcurlrequest($ip.$ap.$file,$domain_name,$app_name,$data);

		}

	} else {

		if(in_array($_SERVER['SERVER_NAME'], $whitelist)){

			$str = "3~test";

			wp_send_json_success( $str );

			exit();

		}else{

			$str = "2~test";

			wp_send_json_success( $str );

			exit();

		}

	}

}

//Android API Form End		



}

public function  get_app()

{

if( isset($_POST['type']) && $_POST['type'] =='api_get_form') {

	

	//Get Current Website URL

	function curl_site_url() {

		 $pageURL = 'http';

		 if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

		 $pageURL .= "://";

		 if ($_SERVER["SERVER_PORT"] != "80") {

		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];

		 } else {

		  $pageURL .= $_SERVER["SERVER_NAME"];

		 }

		 $subDirURL='';

		 if(!empty($_SERVER['SCRIPT_NAME'])){

			 $subDirURL .= str_replace("/wp-admin/admin-ajax.php","",$_SERVER['SCRIPT_NAME']);

		 }

		 return $pageURL.$subDirURL;

	}

	$ap = $_POST['ap'];	

	$ip = $_POST['ip'];	

	$file = $_POST['file'];	

	$app_name = $_POST['app_name'];

	function get_domain($url){

	  $pieces = parse_url($url);

	  $domain = isset($pieces['host']) ? $pieces['host'] : '';

	  if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,10})$/i', $domain, $regs)) {

		
		//
		function isLetter($domain_name) {
		  return preg_match('/^\s*[a-z,A-Z]/', $domain_name) > 0;
		}
		if(isLetter($regs['domain']))
		{
			 return $regs['domain'];
		}else{
			 return "com_".$regs['domain'];			
		}
		//
		

	  }

	  return false;

	}

	// cURL Enable/Disable Function

	function _is_curl_installed() {

		if  (in_array  ('curl', get_loaded_extensions())) {

			return true;

		} else {

			return false;

		}

	}

	$website = curl_site_url();	

	$domain_name = get_domain($website); 

	$domain_arr= explode('.',$domain_name);

	$domain_fname = $domain_arr[0];

	$app_name = $_POST['app_name'];

	$data = array(

			"name" => $_POST['name'],

			"app_name" => $_POST['app_name'],

			"email" => $_POST['semail'],

			"license" => '',
			
			"interstitial_unit_id" => $_POST['interstitial_unit_id'],

			"website" => $website,

			"domain_name"=>$domain_name,

			"domain_fname"=>$domain_fname

		);

	function wcurlRErequest($ac,$d_name,$an,$data) 

	{

		set_time_limit(300);

		$fields = '';

		foreach ($data as $key => $value) {

			$fields .= $key . '=' . $value . '&';

		}

		rtrim($fields, '&');

	

		$post = curl_init();

			curl_setopt($post, CURLOPT_URL,$ac);

			curl_setopt($post, CURLOPT_VERBOSE, 0);  

			curl_setopt($post, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($post, CURLOPT_SSL_VERIFYHOST, false);

			curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($post, CURLOPT_CONNECTTIMEOUT, 10);

			//curl_setopt($post, CURLOPT_TIMEOUT, 900);

			curl_setopt($post, CURLOPT_TIMEOUT, 300);

			$agent = 'Mozilla/5.0 (X11; U; Linux x86_64; pl-PL; rv:1.9.2.22) Gecko/20110905 Ubuntu/10.04 (lucid) Firefox/3.6.22';

			if(!empty($_SERVER['HTTP_USER_AGENT'])){

				$agent = $_SERVER['HTTP_USER_AGENT'];

			}

			curl_setopt($post, CURLOPT_USERAGENT, $agent);

			curl_setopt($post, CURLOPT_FAILONERROR, 1);

			curl_setopt($post, CURLOPT_POST, count($data));

			curl_setopt($post, CURLOPT_POSTFIELDS, $fields);

			

		$result = curl_exec($post);

	    $code = curl_getinfo($post, CURLINFO_HTTP_CODE);

        $success = ($code == 200);

        curl_close($post);

        if (!$success) {

			 setcookie( 'wapppress_proxy', 'true', time() + ( DAY_IN_SECONDS * 100 ) );

			 $str = "0~test";			

			 wp_send_json_success( $str );

			 exit();

        } else {

		

			if($result!=0)

			 {

					$dirPath = dirname(__FILE__);

					$myFile = $dirPath."/wp_comment.txt";

					$fh = fopen($myFile, 'w') or die("can't open file");

					$stringData = $result;

					fwrite($fh, $stringData);

					fclose($fh);

					$d_name = str_replace("-","_",$d_name);

					$str = '1'.'~'.$d_name;
					setcookie( 'wapppress_proxy', 'true', time() - 1000 );
					wp_send_json_success( $str );				
					exit();		
					
				}else{

					setcookie( 'wapppress_proxy', 'true', time() + ( DAY_IN_SECONDS * 100 ) );

					$str = "0~test";					

					wp_send_json_success( $str );

					exit();

					

				}

		}	

	

	}

	$whitelist = array('127.0.0.1', "::1",'localhost');

	// Check cURL Enable/Disable 

	if (_is_curl_installed()) {

		if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){

			$str = "3~test";

			wp_send_json_success( $str );

			exit();

		}else{	

			wcurlRErequest($ip.$ap.$file,$domain_name,$app_name,$data);

		}

	} else {

		if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){

			$str = "3~test";

			wp_send_json_success( $str );

			exit();

		}else{

			$str = "2~test";

			wp_send_json_success( $str );

			exit();

		}

	}

}

}





//Create App 

public function  create_push_app(){

ini_set('memory_limit', '2048M');

set_time_limit(300);

//Push Notification Form Start

if(isset($_POST['type']) && $_POST['type'] =='push_form') {


	$dirPath = dirname(__FILE__);


		function wcurlpushrequest($ac,$data) {

			set_time_limit(100);

			$fields = '';

			foreach ($data as $key => $value) {

				$fields .= $key . '=' . $value . '&';

			}

			rtrim($fields, '&');	

			$post = curl_init();

			curl_setopt($post, CURLOPT_URL,$ac);

			curl_setopt($post, CURLOPT_VERBOSE, 0);  

			curl_setopt($post, CURLOPT_RETURNTRANSFER, true);

			curl_setopt($post, CURLOPT_SSL_VERIFYHOST, false);

			curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($post, CURLOPT_CONNECTTIMEOUT, 10);

			curl_setopt($post, CURLOPT_TIMEOUT, 300);

			$agent = 'Mozilla/5.0 (X11; U; Linux x86_64; pl-PL; rv:1.9.2.22) Gecko/20110905 Ubuntu/10.04 (lucid) Firefox/3.6.22';

			if(!empty($_SERVER['HTTP_USER_AGENT'])){

				$agent = $_SERVER['HTTP_USER_AGENT'];

			}

			curl_setopt($post, CURLOPT_USERAGENT, $agent);

			curl_setopt($post, CURLOPT_FAILONERROR, 1);

			curl_setopt($post, CURLOPT_POST, count($data));

			curl_setopt($post, CURLOPT_POSTFIELDS, $fields);

			$result = curl_exec($post);
			curl_close($post);

			if($result==1){

				$str = '1';

				wp_send_json_success( $str );

				exit();

			}else if($result==4){

				$str = '4';

				wp_send_json_success( $str );

				exit();

			}else{

				$str = '0';

				wp_send_json_success( $str );

				exit();

			}	

		}

		
function get_domain_name($url){

	  $pieces = parse_url($url);

	  $domain = isset($pieces['host']) ? $pieces['host'] : '';

	  if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,10})$/i', $domain, $regs)) {

		
		//
		function isLetter($domain_name) {
		  return preg_match('/^\s*[a-z,A-Z]/', $domain_name) > 0;
		}
		if(isLetter($regs['domain']))
		{
			 return $regs['domain'];
		}else{
			 return "com_".$regs['domain'];			
		}
		//
		

	  }

	  return false;

	}
	function curl_site_url() {

		 $pageURL = 'http';

		 if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

		 $pageURL .= "://";

		 if ($_SERVER["SERVER_PORT"] != "80") {

		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];

		 } else {

		  $pageURL .= $_SERVER["SERVER_NAME"];

		 }

		 $subDirURL='';

		 if(!empty($_SERVER['SCRIPT_NAME'])){

			 $subDirURL .= str_replace("/wp-admin/admin-ajax.php","",$_SERVER['SCRIPT_NAME']);

		 }

		 return $pageURL.$subDirURL;

	}
	$website = curl_site_url();	

	$domain_name = get_domain_name($website); 			

		$ap = $_POST['ap'];	

		$ip = $_POST['ip'];	

		$file = $_POST['file'];	

		

		$data = array(

			'push_msg'=> $_POST['push_msg'],
			'domain_name'=> $domain_name,

			'app_auth_key'=>$get_contant

		); 

		// Return cURL Enable/Disable Function

		function check_push_is_curl_installed() {

			if(in_array  ('curl', get_loaded_extensions())) {

				return true;

			} else {

				return false;

			}

		}


		$whitelist = array('127.0.0.1', "::1",'localhost');
			$whitelist = array();

		// Check cURL Enable/Disable 

		if (check_push_is_curl_installed()) {

			if(in_array($_SERVER['SERVER_NAME'], $whitelist)){

				$str = '3';

				wp_send_json_success( $str );

				exit();

			}else{

				wcurlpushrequest($ip.$ap.$file,$data);

			}

		} else {

			if(in_array($_SERVER['SERVER_NAME'], $whitelist)){

				$str = '3';

				wp_send_json_success( $str );

				exit();

			}else{

				$str = '2';

				wp_send_json_success( $str );

				exit();

			}

		}

	

	

}

//Push Notification From End

	

}





//Search Home Page  

public function search_post_results() {

	   $searchVal = sanitize_text_field($_POST['search_val']);

	   $nonceVal = sanitize_text_field($_POST['nonce']);

		if( !(isset($searchVal,$nonceVal) && wp_verify_nonce($nonceVal, 'wapppress_group-options' ) ) ){

			wp_send_json_error( '<p>'. __( 'Security check failed', 'wapppress' ) .'</p>' );

		}	

		

		if ( empty( $searchVal ) ){

			wp_send_json_error( '<p>'. __( 'Please Try Again', 'wapppress' ) .'</p>' );

		}

		global $wpdb;

		$allResults = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title LIKE '%%%s%%' AND post_status = 'publish' AND post_type = 'page' LIMIT 10", $searchVal ) );

		if ( empty( $allResults ) ){

			wp_send_json_error( '<p>'. __('No Results Found', 'wapppress' ) .'</p>' );

		}

		if ( !empty( $allResults ) ){

			$str = '<p>'. __('Please choose a page', 'wapppress' ) .'</p>';

			$str .= '<ol>';

			foreach ( $allResults as $postID ) {

				//$str .= '<li><a href="'. get_permalink( $postID ) .'"  data-postID="'. $postID .'">'. get_the_title( $postID ) .'</a></li>';
				$str .= '<li><a href="javascript:void(0)" OnClick="custom_page('. $postID .')" data-postID="'. $postID .'">'. get_the_title( $postID ) .'</a></li>';

			}

			$str .= '</ol>';

			wp_reset_postdata();

			wp_send_json_success( $str );

		}

	}

	

	public function reset_cookie() {

		setcookie( 'wapppress_app', 'true', time() - DAY_IN_SECONDS );

	}

}

new wappPress_admin_setting();

