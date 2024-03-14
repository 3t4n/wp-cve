<?php
/**
 * Template Reset.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for the FDP reset page.
function eos_dp_reset_settings_callback() {
	?><style id="fdp-reset-settings-css">#fdp-opt-reset_submit{padding:10px}.fdp-form-table th{text-align:initial !important}#fdp-opt-reset_confirmation{text-transform:uppercase !important}</style>
	<?php
	$args        = array(
		'reset'              => array(
			'type'        => 'checkbox',
			'description' => esc_html__( 'I want to reset all the settings of FDP. I am aware that this choice is irreversible.', 'freesoul-deactivate-plugins' ),
		),
		'reset_confirmation' => array(
			'type'          => 'text',
			'style'         => 'text-transform:uppercase',
			'wrapper_id'    => 'fdp-reset-confirmation-wrp',
			'wrapper_class' => 'eos-hidden',
			'description'   => sprintf( esc_html__( 'Write %s to confirm that you want to reset the FDP settings.', 'freesoul-deactivate-plugins' ), 'RESET' ),
		),
		'reset_submit'       => array(
			'type'          => 'submit',
			'value'         => esc_html__( 'Reset all the settings', 'freesoul-deactivate-plugins' ),
			'wrapper_id'    => 'fdp-reset-submit-wrp',
			'wrapper_class' => 'eos-hidden',
			'class'         => 'hover',
			'description'   => esc_html__( 'Click on the button if you are really sure.', 'freesoul-deactivate-plugins' ),
		),
	);
	$description = '<p>' . esc_html__( 'All the settings will be totally cleared. Do it only if you are totally sure, and after making a full backup.', 'freesoul-deactivate-plugins' ) . '</p>';
	fdp_add_settings_page( 'eos_dp_reset', $args, 'tools', esc_attr__( 'Settings Reset', 'freesoul-deactivate-plugins' ), $description, false, false, false, false );
	?>
  <div id="fdp-reset-submit-wrp" class="eos-hidden" style="position:relative;bottom:0"><button id="fdp-reset-settings-submit" class="button"><?php esc_html_e( 'Reset all the settings', 'freesoul-deactivate-plugins' ); ?></button></div>
  <script id="fdp-reset-settings">
  document.getElementById('fdp-opt-reset').addEventListener('click',function(){
	document.getElementById('fdp-reset-confirmation-wrp').className = '';
	var fdp_reset_interval = setInterval(function(){
	  if('reset' === document.getElementById('fdp-opt-reset_confirmation').value.toLowerCase()){
		document.getElementById('fdp-reset-submit-wrp').className = '';
		document.getElementById('fdp-opt-reset_submit').addEventListener('click',function(){
		  this.className = this.className.replace(' eos-dp-progress','') + ' eos-dp-progress';
		  clearInterval(fdp_reset_interval);
		  eos_dp_call_ajax({dataset:{nonce:'<?php echo esc_attr( wp_create_nonce( 'eos_dp_reset_fdp' ) ); ?>',data:document.getElementById('fdp-opt-reset_confirmation').value.toLowerCase(),action:'eos_dp_reset_fdp'}});
		  setTimeout(function(){
			window.location.href = '<?php echo esc_url( add_query_arg( 'page', 'eos_dp_menu', admin_url( 'admin.php' ) ) ); ?>';
		  },2000);
		});
	  }
	  else{
		document.getElementById('fdp-reset-submit-wrp').className = 'eos-hidden';
	  }
	},500);
  });
  </script>
	<?php
	return;
}
