<?php
/**
 * Template Experiments.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Callback for the Experiments page.
function eos_dp_experiments_callback() {
	?><style id="fdp-experiments-css">
  #fdp-opt-move_db_confirmation,#fdp-opt-move_filesystem_confirmation{text-transform:uppercase !important}
  .fdp-form-table th {
	padding-top:20px;
	padding-bottom:20px;
	max-width: 400px;
	text-align:<?php echo is_rtl() ? 'right' : 'left'; ?>;
  }
  .fdp-form-table td{
	padding: 20px
  }
  </style>
	<?php
	$opts        = eos_dp_get_option( 'eos_dp_opts' );
	$skip_db     = isset( $opts['skip_db_for_archives'] ) && 'true' === $opts['skip_db_for_archives'] ? ' checked' : '';
	$args        = array(
		'move_db'                      => array(
			'type'        => 'checkbox',
			'attribute'   => $skip_db,
			'wrapper_id'  => 'db_filesystem_chk_wrp',
			'description' => __( 'Check this if you want to save the Archives and Terms Archives settings in the filesystem. Uncheck it if you want to save it in the database.', 'freesoul-deactivate-plugins' ),
		),
		'move_db_confirmation'         => array(
			'type'          => 'text',
			'style'         => 'text-transform:uppercase',
			'wrapper_id'    => 'fdp-move_db-confirmation-wrp',
			'wrapper_class' => 'eos-hidden',
			'description'   => sprintf( __( 'Write %s to confirm that you want to move the Archives and Terms Archives settings from the filesystem to the database.', 'freesoul-deactivate-plugins' ), 'DATABASE' ),
		),
		'move_filesystem_confirmation' => array(
			'type'          => 'text',
			'style'         => 'text-transform:uppercase',
			'wrapper_id'    => 'fdp-move_filesystem-confirmation-wrp',
			'wrapper_class' => 'eos-hidden',
			'description'   => sprintf( __( 'Write %s to confirm that you want to move the Archives and Terms Archives settings from the database to the filesystem.', 'freesoul-deactivate-plugins' ), 'FILESYSTEM' ),
		),
		'move_db_submit'               => array(
			'type'          => 'submit',
			'value'         => __( "Yes, I'm sure", 'freesoul-deactivate-plugins' ),
			'wrapper_id'    => 'fdp-move_db-submit-wrp',
			'wrapper_class' => 'eos-hidden',
			'class'         => 'button hover',
			'description'   => __( 'Click on the button if you are really sure.', 'freesoul-deactivate-plugins' ),
		),
	);
	$description = '<strong>' . __( 'Important! Make always a full backup before to try any experiment.', 'freesoul-deactivate-plugins' ) . '</strong>';
	fdp_add_settings_page( 'eos_dp_move_db', $args, 'tools', __( 'Save settings in the Filesystem', 'freesoul-deactivate-plugins' ), $description, false, false, false, false );
	?>
  <div id="fdp-move_db-submit-wrp" class="eos-hidden" style="position:relative;bottom:0"><button id="fdp-move_db-settings-submit" class="button"><?php esc_html_e( 'Reset all the settings', 'freesoul-deactivate-plugins' ); ?></button></div>
  <script id="fdp-move_db-settings">
  document.getElementById('db_filesystem_chk_wrp').addEventListener('click',function(e){
	if('checkbox' !== e.target.type) return false;
	if(e.target.checked){
		fdp_wrapper = 'fdp-move_filesystem-confirmation-wrp';
		fdp_anti_wrapper = 'fdp-move_db-confirmation-wrp';
		window.confirmation_text = 'filesystem';
		fdp_input_wrp = 'fdp-opt-move_db_confirmation';
		fdp_input = 'fdp-opt-move_db_confirmation';
		fdp_anti_input = 'fdp-opt-move_filesystem_confirmation';
	}
	else{
		fdp_wrapper = 'fdp-move_db-confirmation-wrp';
		fdp_anti_wrapper = 'fdp-move_filesystem-confirmation-wrp';
		window.confirmation_text = 'database';
		fdp_input = 'fdp-opt-move_filesystem_confirmation';
		fdp_anti_input = 'fdp-opt-move_db_confirmation';
	}
	document.getElementById(fdp_input).value = '';
	document.getElementById(fdp_anti_input).value = '';
	document.getElementById(fdp_wrapper).className = '';
	document.getElementById(fdp_anti_wrapper).className = 'eos-hidden';
	if('undefined' !== typeof(fdp_move_db_interval)){
	  clearInterval(fdp_move_db_interval);
	}
	fdp_move_db_interval = setInterval(function(){
	  if(window.confirmation_text === document.getElementById(fdp_anti_input).value.toLowerCase()){
		document.getElementById('fdp-move_db-submit-wrp').className = '';
		document.getElementById('fdp-opt-move_db_submit').addEventListener('click',function(){
		  clearInterval(fdp_move_db_interval);
		  document.getElementById('db_filesystem_chk_wrp').parentNode.removeChild(document.getElementById('db_filesystem_chk_wrp'));
		  this.className = 'button eos-dp-progress';
		  eos_dp_call_ajax({dataset:{nonce:'<?php echo esc_attr( wp_create_nonce( 'eos_dp_filesystem_db' ) ); ?>',data:{to:window.confirmation_text,option:'eos_dp_archives'},action:'eos_dp_filesystem_db'}});
		  setTimeout(function(){
			location.href = '<?php echo esc_url( add_query_arg( 'page', 'eos_dp_experiments', admin_url( 'admin.php' ) ) ); ?>';
		  },2000);
		  return false;
		});
	  }
	  else{
		document.getElementById('fdp-move_db-submit-wrp').className = 'eos-hidden';
	  }
	},500);
  });
  </script>
	<?php
	return;
}
