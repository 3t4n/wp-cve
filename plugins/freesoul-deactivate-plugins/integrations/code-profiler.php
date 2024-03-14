<?php
defined( 'ABSPATH' ) || exit;

if ( isset( $_GET['page'] ) && 'cp-fdp' === $_GET['page'] ) {
	add_action(
		'admin_init',
		function() {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'network_admin_notices' );
			remove_all_actions( 'all_admin_notices' );
			remove_all_actions( 'user_admin_notices' );
		}
	);
}
add_action(
	'admin_menu',
	function() {
		add_submenu_page( 'fdp_hidden_menu', esc_html__( 'Plugins', 'freesoul-deactivate-plugins' ), esc_html__( 'Plugins', 'freesoul-deactivate-plugins' ), apply_filters( 'eos_dp_settings_capability', 'activate_plugins' ), 'cp-fdp', 'eos_dp_code_profiler_page', 10 );
	}
);

function eos_dp_code_profiler_page() {
	$active_plugins = eos_dp_active_plugins();
	$plugins        = eos_dp_get_plugins();
	$opts           = eos_dp_get_option( 'fdp_code_profiler' );
	$cp             = isset( $opts['plugins'] ) ? $opts['plugins'] : array();
	wp_nonce_field( 'fdp-cp-nonce', 'fdp-cp-nonce' );
	?>
  <style id="fdp-cp">
	#wpbody-content>div{display:none !important;opacity:0 !important;height:0 !important;position:fixed !important;top:-99999px !important}
	.fdp-cp-plugins{opacity:<?php echo ! isset( $opts['fdp_cp'] ) || 'fdp' === $opts['fdp_cp'] ? '0.7' : ''; ?>}
	.fdp-cp-plugins>div:first-child p{margin-top:0}
	#fdp-code-profiler-save{background-repeat:no-repeat;background-size:32px 32px;background-position:-99999px -9999px;background-image:url(<?php echo esc_url( EOS_DP_PLUGIN_URL . '/admin/assets/img/ajax-loader.gif' ); ?>);}
	@media screen and (min-width:900px){
	  .fdp-cp-plugins{
		column-count:<?php echo esc_attr( max( 1, min( 3, absint( count( $active_plugins ) / 6 ) ) ) ); ?>
	  }
	}
  </style>
  <h1><?php esc_html_e( 'Code Profiler - Plugins', 'freesoul-deactivate-plugins' ); ?></h1>
  <h2><?php esc_html_e( 'Plugins active during the code profiling', 'freesoul-deactivate-plugins' ); ?></h2>
  <p><input id="fdp-radio-fdp" type="radio" name="fdp_cp_setts" value="fdp" <?php echo ! isset( $opts['fdp_cp'] ) || 'fdp' === $opts['fdp_cp'] ? 'checked' : ''; ?>/><span><?php esc_html_e( 'According to the FDP settings', 'freesoul-deactivate-plugins' ); ?></span></p>
  <p><input id="fdp-radio-cp" type="radio" name="fdp_cp_setts" value="cp" <?php echo isset( $opts['fdp_cp'] ) && 'cp' === $opts['fdp_cp'] ? 'checked' : ''; ?>/><span><?php esc_html_e( 'According to this page settings', 'freesoul-deactivate-plugins' ); ?></span></p>
	<section class="fdp-cp-plugins" style="margin-top:32px">
		<?php
		$n = 0;
		foreach ( $active_plugins as $p ) {
			if ( isset( $plugins[ $p ] ) ) {
				$plugin_name = strtoupper( str_replace( '-', ' ', dirname( $p ) ) );
				?>
				<div>
					<div class="eos-dp-cp-chk-col">
						<p class="fdp-cp-chk-wrp">
							<input id="fdp-cp-<?php echo esc_attr( $n + 1 ); ?>" class="eos-dp-cp" title="<?php printf( esc_attr__( 'Activate/deactivate %s during the code profiling', 'freesoul-deactivate-plugins' ), esc_attr( $plugin_name ) ); ?>" data-path="<?php echo esc_attr( $p ); ?>" type="checkbox"<?php echo $cp && in_array( $p, $cp ) ? '' : ' checked'; ?> />
			  <span><?php echo esc_html( $plugin_name ); ?></span>
			</p>
					</div>
				</div>
				<?php
				++$n;
			}
		}
		?>
	</section>
  <p style="margin-top:32px">
	  <input type="button" class="button-primary" id="fdp-code-profiler-save" onclick="fdp_code_profiler_save_plugins(this);" value="<?php esc_html_e( 'Save settings', 'freesoul-deactivate-plugins' ); ?>" title="<?php esc_html_e( 'Save settings', 'freesoul-deactivate-plugins' ); ?>">
	<a type="button" class="button" title="<?php esc_html_e( 'Code Profiler', 'freesoul-deactivate-plugins' ); ?>" href="
															 <?php
																if ( defined( 'CODE_PROFILER_PRO_MU_ON' ) ) {
																	echo esc_url( admin_url( '?page=code-profiler-pro&cptab=profiler' ) );
																} else {
																	echo esc_url( admin_url( '?page=code-profiler&cptab=profiler' ) );
																}
																?>
	"><?php esc_html_e( 'Code Profiler', 'freesoul-deactivate-plugins' ); ?></a>
  </p>
  <p id="fdp-msg-succ" class="eos-dp-opts-msg eos-dp-opts-msg_success msg_response" style="display:none;padding:10px;margin:10px;border-left:4px solid #00a32a;background:#fff">
		<span><?php esc_html_e( 'Options saved', 'freesoul-deactivate-plugins' ); ?></span>
	</p>
  <p id="fdp-msg-fail" class="eos-dp-opts-msg_failed eos-dp-opts-msg eos-hidden msg_response" style="display:none;padding:10px;margin:10px;border-left:4px solid #d63638;background:#fff">
		<span><?php esc_html_e( 'Something went wrong, maybe you need to refresh the page and try again, but you will lose all your changes', 'freesoul-deactivate-plugins' ); ?></span>
	</p>
  <script>
  document.getElementById('fdp-radio-cp').addEventListener('click',function(){
	document.getElementsByClassName('fdp-cp-plugins')[0].style.opacity = '1';
  });
  document.getElementById('fdp-radio-fdp').addEventListener('click',function(){
	document.getElementsByClassName('fdp-cp-plugins')[0].style.opacity = '0.7';
  });
  function fdp_code_profiler_save_plugins(btn){
	var deactivated_plugins = [],xmlHttp = new XMLHttpRequest(),f = new FormData(),v = document.getElementsByClassName('eos-dp-cp'),msg_succ = document.getElementById('fdp-msg-succ'),msg_fail = document.getElementById('fdp-msg-fail');
	btn.style.backgroundPosition = 'center center';
	msg_succ.style.display = 'none';
	msg_fail.style.display = 'none';
	for(var i=0;i<v.length;i++){
	  if(!v[i].checked){
		deactivated_plugins[i] = v[i].dataset.path;
	  }
	}
	f.append('plugins',deactivated_plugins);
	f.append('nonce', document.getElementById('fdp-cp-nonce').value);
	f.append('fdp_cp',document.querySelector('input[name=fdp_cp_setts]:checked').value);
	xmlHttp.onreadystatechange = function(){
	  btn.style.backgroundPosition = '-9999px -9999px';
	  if(4 === xmlHttp.readyState && 200 === xmlHttp.status){
		if('1' === xmlHttp.responseText.trim()){
		  msg_succ.style.display = 'block';
		  return false;
		}
		else{
		  msg_fail.style.display = 'block';
		  return false;
		}
	  }
	}
	xmlHttp.open("POST","<?php echo esc_url( admin_url( 'admin-ajax.php?action=eos_dp_code_profiler_save', true ) ); ?>");
	xmlHttp.send(f);
	return false;
  }
  </script>
	<?php
}
