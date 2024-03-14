<!-- Dashboard Settings panel content -->
<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$js = ' ';

?>
<div class="row">
	<div class="post-social-wrapper clearfix">
		<div class="col-md-12 post-social-item">
			<div class="panel panel-default">
				<div class="panel-heading padding-none">
					<div class="post-social post-social-xs" id="post-social-5">
						<div class="text-center padding-all text-center">
							<div class="textbox text-white   margin-bottom settings-title">
								<?php esc_html_e( 'Google Captcha Settings', WEBLIZAR_ACL ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e( 'Captcha Display', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<span>
							<input type="radio" name="enable_gacptcha" value="yes" id="login_enable_gcaptcha1" 
							<?php
							if ( $login_enable_gcaptcha == 'yes' ) {
								echo esc_attr( 'checked' );}
							?>
							 />&nbsp;<label for="login_enable_gcaptcha1"><?php esc_html_e( 'Enable', WEBLIZAR_ACL ); ?></label><br>
						</span>
						<span>
							<input type="radio" name="enable_gacptcha" value="no" id="login_enable_gcaptcha2" 
							<?php
							if ( $login_enable_gcaptcha == 'no' ) {
								echo esc_attr( 'checked' );}
							?>
							 />&nbsp;<label for="login_enable_gcaptcha2"><?php esc_html_e( 'Disable', WEBLIZAR_ACL ); ?></label><br>
						</span>
						<div class="acl_gcaptcha_info"><strong><?php esc_html_e( 'Note : ', WEBLIZAR_ACL ); ?></strong> <?php esc_html_e( 'After enable google captcha display please insert site key & secret key.', WEBLIZAR_ACL ); ?></div>
					</td>
				</tr>

				<tr>
					<th scope="row" ><?php esc_html_e( 'Google Captcha v2 and v3 Settings', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td><?php esc_html_e( 'Need Help?', WEBLIZAR_ACL ); ?>
						<a href="http://www.weblizar.com" target="_blank"><?php esc_html_e( 'Visit Help Center', WEBLIZAR_ACL ); ?> </a>
						<div class="acl_gcaptcha_sub_label"><?php esc_html_e( 'Authentication', WEBLIZAR_ACL ); ?></div>
						<div class="acl_gcaptcha_info"><?php esc_html_e( 'Register your website with Google to get required API keys and enter them below.', WEBLIZAR_ACL ); ?> <a target="_blank" href="https://weblizar.com/blog/how-to-generate-recaptcha-keys-for-your-domain/"><?php esc_html_e( 'Get the API Keys', WEBLIZAR_ACL ); ?></a></div>
					</td>
				</tr>

				<tr>
					<th scope="row" ><?php esc_html_e( 'Choose Google Captcha Version', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<span>
							<input type="radio" name="v_gacptcha" value="v2" id="login_v_gcaptcha1" 
							<?php
							if ( $login_v_gcaptcha == 'v2' ) {
								echo esc_attr( 'checked' );}
							?>
							 />&nbsp;<label for="login_v_gcaptcha1"><?php esc_html_e( 'v2', WEBLIZAR_ACL ); ?></label><br>
						</span>
						<span>
							<input type="radio" name="v_gacptcha" value="v3" id="login_v_gcaptcha2" 
							<?php
							if ( $login_v_gcaptcha == 'v3' ) {
								echo esc_attr( 'checked' );}
							?>
							 />&nbsp;<label for="login_v_gcaptcha2"><?php esc_html_e( 'v3', WEBLIZAR_ACL ); ?></label><br>
						</span>
						<div class="acl_gcaptcha_info"><strong><?php esc_html_e( 'Note :', WEBLIZAR_ACL ); ?> </strong><?php esc_html_e( 'After select google captcha version please insert site key & secret key.', WEBLIZAR_ACL ); ?> </div>
					</td>
				</tr>

				<tr class="recaptcha_keys_v2">
					<th scope="row" ><?php esc_html_e( 'Site Key', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span recaptcha_keys_v2">
					<td>
						<input type="text" class="pro_text" id="site-key" name="site-key" placeholder="<?php esc_attr_e( 'Site Key', WEBLIZAR_ACL ); ?>" size="56" value="<?php echo esc_attr( $site_key ); ?>"/>
					</td>
				</tr>
				<tr class="recaptcha_keys_v2">
					<th scope="row" ><?php esc_html_e( 'Secret Key', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span recaptcha_keys_v2">
					<td>
						<input type="text" class="pro_text" id="secret-key" name="secret-key" placeholder="<?php esc_attr_e( 'Secret Key', WEBLIZAR_ACL ); ?>" size="56" value="<?php echo esc_attr( $secret_key ); ?>"/>
					</td>
				</tr>
				<tr class="recaptcha_keys_v2">
					<th scope="row" ><?php esc_html_e( 'Captcha Theme', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span recaptcha_keys_v2">
					<td>
						<span>
							<input type="radio" name="acl_gcaptcha_theme" value="yes" id="acl_gcaptcha_theme1" 
							<?php
							if ( $acl_gcaptcha_theme == 'yes' ) {
								echo esc_attr( 'checked' );}
							?>
							 />&nbsp;<label for="acl_gcaptcha_theme1"><?php esc_html_e( 'Light', WEBLIZAR_ACL ); ?></label><br>
						</span>
						<span>
							<input type="radio" name="acl_gcaptcha_theme" value="no" id="acl_gcaptcha_theme2" 
							<?php
							if ( $acl_gcaptcha_theme == 'no' ) {
								echo esc_attr( 'checked' );}
							?>
							 />&nbsp;<label for="acl_gcaptcha_theme2"><?php esc_html_e( 'Dark', WEBLIZAR_ACL ); ?></label><br>
						</span>
					</td>
				</tr> 

				<tr class="recaptcha_keys_v3">
					<th scope="row" ><?php esc_html_e( 'Site Key', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span recaptcha_keys_v3">
					<td>
						<input type="text" class="pro_text" id="site-key-v3" name="site-key-v3" placeholder="<?php esc_attr_e( 'Site Key', WEBLIZAR_ACL ); ?>" size="56" value="<?php echo esc_attr( $site_key_v3 ); ?>"/>
					</td>
				</tr>
				<tr class="recaptcha_keys_v3">
					<th scope="row" ><?php esc_html_e( 'Secret Key', WEBLIZAR_ACL ); ?></th>
					<td></td>
				</tr>
				<tr class="radio-span recaptcha_keys_v3">
					<td>
						<input type="text" class="pro_text" id="secret-key-v3" name="secret-key-v3" placeholder="<?php esc_attr_e( 'Secret Key', WEBLIZAR_ACL ); ?>" size="56" value="<?php echo esc_attr( $secret_key_v3 ); ?>"/>
					</td>
				</tr>

			</table>
		</div>
	</div>
	
	<button data-dialog49="somedialog49" class="dialog-button49"><?php esc_html_e( 'Open Dialog', WEBLIZAR_ACL ); ?></button>
	<div id="somedialog49" class="dialog">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e( 'Google Captcha', WEBLIZAR_ACL ); ?></strong> <?php esc_html_e( 'Setting Save Successfully', WEBLIZAR_ACL ); ?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button49"><?php esc_html_e( 'Close', WEBLIZAR_ACL ); ?></button></div>
			</div>
		</div>
	</div>
	<button data-dialog109="somedialog109" class="dialog-button109"><?php esc_html_e( 'Open Dialog', WEBLIZAR_ACL ); ?></button>
	<div id="somedialog109" class="dialog">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e( 'Google Captcha', WEBLIZAR_ACL ); ?></strong> <?php esc_html_e( 'Setting Reset Successfully', WEBLIZAR_ACL ); ?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button109"><?php esc_html_e( 'Close', WEBLIZAR_ACL ); ?></button></div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary save-button-block">
		<div class="panel-body">
				<button type="button" onclick="return Custom_gcaptcha('googleSave', '');" class="btn btn-info btn-lg"><?php esc_html_e( 'Save Changes', WEBLIZAR_ACL ); ?></button>
				<button type="button" onclick="return Custom_gcaptcha('googleReset', '');" class="btn btn-primary btn-lg"><?php esc_html_e( 'Reset Default', WEBLIZAR_ACL ); ?></button>
		</div>
	</div>
</div>
<!-- /row -->
<?php // ob_start(); ?>
<script type="text/javascript">
	var recaptcha_keys_v2 = jQuery('.recaptcha_keys_v2');
	var recaptcha_keys_v3 = jQuery('.recaptcha_keys_v3');
	recaptcha_keys_v2.hide();
	recaptcha_keys_v3.hide();
	var current_recaptcha_v = '<?php echo esc_attr( $login_v_gcaptcha ); ?>';
	if('v2' == current_recaptcha_v) {
		recaptcha_keys_v2.show();
	} else {
		recaptcha_keys_v3.show();
	}
	jQuery(document).on('change', 'input[name="v_gacptcha"]', function() {
		if('v3' === this.value) {
			recaptcha_keys_v2.hide();
			recaptcha_keys_v3.show();
		} else {
			recaptcha_keys_v3.hide();
			recaptcha_keys_v2.show();
		}
	});
	function Custom_gcaptcha(Action, id){
		if(Action == "googleSave") {
			(function(){
				var dlgtrigger = document.querySelector( '[data-dialog49]' ),
					somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog49' ) ),
					// svg..
					morphEl = somedialog.querySelector( '.morph-shape' ),
					s = Snap( morphEl.querySelector( 'svg' ) ),
					path = s.select( 'path' ),
					steps = { 
						open : morphEl.getAttribute( 'data-morph-open' ),
						close : morphEl.getAttribute( 'data-morph-close' )
					},
					dlg = new DialogFx( somedialog, {
						onOpenDialog : function( instance ) {
							// animate path
							setTimeout(function() {
								path.stop().animate( { 'path' : steps.open }, 1500, mina.elastic );
							}, 250 );
						},
						onCloseDialog : function( instance ) {
							// animate path
							path.stop().animate( { 'path' : steps.close }, 250, mina.easeout );
						}
					} );
				dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );
			})();

			var site_key = jQuery("#site-key").val();
			var secret_key = jQuery("#secret-key").val();
			var site_key_v3 = jQuery("#site-key-v3").val();
			var secret_key_v3 = jQuery("#secret-key-v3").val();
			if (document.getElementById('login_v_gcaptcha1').checked) {
				var login_v_gcaptcha = document.getElementById('login_v_gcaptcha1').value;
			} else {
				var login_v_gcaptcha = document.getElementById('login_v_gcaptcha2').value;
			}
			if (document.getElementById('login_enable_gcaptcha1').checked) {
				var login_enable_gcaptcha = document.getElementById('login_enable_gcaptcha1').value;
			} else {
				var login_enable_gcaptcha = document.getElementById('login_enable_gcaptcha2').value;
			}

			if (document.getElementById('acl_gcaptcha_theme1').checked) {
				var acl_gcaptcha_theme = document.getElementById('acl_gcaptcha_theme1').value;
			} else {
				var acl_gcaptcha_theme = document.getElementById('acl_gcaptcha_theme2').value;
			}
			
			var PostData = "Action=" + Action + "&site_key=" + site_key + "&secret_key=" + secret_key + "&site_key_v3=" + site_key_v3 + "&secret_key_v3=" + secret_key_v3 + "&login_v_gcaptcha=" + login_v_gcaptcha + "&login_enable_gcaptcha=" + login_enable_gcaptcha + "&acl_gcaptcha_theme=" + acl_gcaptcha_theme;
			
			jQuery.ajax({
				dataType : 'html',
				type: 'POST',
				url : location.href,
				cache: false,
				//data : PostData,
				data: {
					nonce_ajax : WEBLIZARADMINNONCE.adminsecurity,
					Action : Action,
					site_key : site_key,
					secret_key : secret_key,
					site_key_v3 : site_key_v3,
					secret_key_v3 : secret_key_v3,
					login_v_gcaptcha : login_v_gcaptcha,
					login_enable_gcaptcha : login_enable_gcaptcha,
					acl_gcaptcha_theme : acl_gcaptcha_theme,
				},
				complete : function() { alert(captcha); },
				success: function(data) {
					// Save message box open
					jQuery(".dialog-button49").click();
					// Function to close message box
					setTimeout(function(){
						jQuery("#dialog-close-button49").click();
					}, 1000);
				}
			});
		}
		
		// Save Message box Close On Mouse Hover
		document.getElementById('dialog-close-button49').disabled = false;
		jQuery('#dialog-close-button49').hover(function () {
			jQuery("#dialog-close-button49").click();
			document.getElementById('dialog-close-button49').disabled = true; 
		});
		
		// Reset Message box Close On Mouse Hover
		document.getElementById('dialog-close-button109').disabled = false;
		jQuery('#dialog-close-button109').hover(function () {
		jQuery("#dialog-close-button109").click();
		document.getElementById('dialog-close-button109').disabled = true; 
		});
		
		if(Action == "googleReset") {		
			(function(){
				var dlgtrigger = document.querySelector( '[data-dialog109]' ),
					somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog109' ) ),
					// svg..
					morphEl = somedialog.querySelector( '.morph-shape' ),
					s = Snap( morphEl.querySelector( 'svg' ) ),
					path = s.select( 'path' ),
					steps = { 
						open : morphEl.getAttribute( 'data-morph-open' ),
						close : morphEl.getAttribute( 'data-morph-close' )
					},
					dlg = new DialogFx( somedialog, {
						onOpenDialog : function( instance ) {
							// animate path
							setTimeout(function() {
								path.stop().animate( { 'path' : steps.open }, 1500, mina.elastic );
							}, 250 );
						},
						onCloseDialog : function( instance ) {
							// animate path
							path.stop().animate( { 'path' : steps.close }, 250, mina.easeout );
						}
					} );
				dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );
			})();
			
			var PostData = "Action=" + Action ;
			jQuery.ajax({
				dataType : 'html',
				type: 'POST',
				url : location.href,
				cache: false,
				//data : PostData,
				data: {
					nonce_ajax : WEBLIZARADMINNONCE.adminsecurity,
					Action : Action,
				},
				complete : function() {  },
				success: function(data) {
					
					document.getElementById("site-key").value ="";
					document.getElementById("secret-key").value ="";
					document.getElementById("site-key-v3").value ="";
					document.getElementById("secret-key-v3").value ="";
					jQuery(document).ready( function() {
						jQuery('input[name=enable_gcaptcha]').val(['yes']);
					});
					jQuery(document).ready( function() {
						jQuery('input[name=acl_gcaptcha_theme]').val(['yes']);
					});

					// Reset message box open
					jQuery(".dialog-button109").click();
					// Function to close message box
					setTimeout(function(){
						jQuery("#dialog-close-button109").click();
					}, 1000);
				}
			});
		}
	}
</script>
	<?php // $js .= ob_get_clean(); ?>
<?php

if ( isset( $_POST['Action'] ) ) {
	$Action = sanitize_text_field( $_POST['Action'] );
	if ( ! wp_verify_nonce( $_POST['nonce_ajax'], 'weblizar_admin_nonce' ) ) {
		die( 'Not authorized' );
	} else {
		// Save Page Values
		if ( $Action == 'googleSave' ) {
			$site_key              = sanitize_text_field( $_POST['site_key'] );
			$secret_key            = sanitize_text_field( $_POST['secret_key'] );
			$site_key_v3           = sanitize_text_field( $_POST['site_key_v3'] );
			$secret_key_v3         = sanitize_text_field( $_POST['secret_key_v3'] );
			$login_v_gcaptcha      = sanitize_text_field( $_POST['login_v_gcaptcha'] );
			$login_enable_gcaptcha = sanitize_text_field( $_POST['login_enable_gcaptcha'] );
			$acl_gcaptcha_theme    = sanitize_text_field( $_POST['acl_gcaptcha_theme'] );

			// save values in option table
			$g_page = serialize(
				array(
					'site_key'              => $site_key,
					'secret_key'            => $secret_key,
					'site_key_v3'           => $site_key_v3,
					'secret_key_v3'         => $secret_key_v3,
					'login_v_gcaptcha'      => $login_v_gcaptcha,
					'login_enable_gcaptcha' => $login_enable_gcaptcha,
					'acl_gcaptcha_theme'    => $acl_gcaptcha_theme,
				)
			);
			update_option( 'Admin_custome_login_gcaptcha', $g_page );
		}

		// Reset Page Settings
		if ( $Action == 'googleReset' ) {
			$g_page = serialize(
				array(

					'site_key'              => '',
					'secret_key'            => '',
					'site_key_v3'           => '',
					'secret_key_v3'         => '',
					'login_v_gcaptcha'      => 'v2',
					'login_enable_gcaptcha' => 'no',
					'acl_gcaptcha_theme'    => 'yes',

				)
			);
			update_option( 'Admin_custome_login_gcaptcha', $g_page );
		}
	}
}
function add_google_captcha_script() {
	wp_register_script( 'wl-acl-login-gcaptcha', '', array(), false, true );
	wp_enqueue_script( 'wl-acl-login-gcaptcha' );
	wp_add_inline_script( 'wl-acl-login-gcaptcha', $js );
}
add_action( 'wp_enqueue_scripts', 'add_google_captcha_script' );
