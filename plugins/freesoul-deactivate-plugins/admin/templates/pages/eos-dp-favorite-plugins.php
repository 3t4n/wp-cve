<?php
/**
 * Template Favorite Plugins.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// It adds the section for favorite plugins.
function eos_dp_favorite_plugins_callback() {
	if ( apply_filters( 'fdp_hide_favorite_plugins_page', ( ! current_user_can( 'activate_plugins' ) && ! current_user_can( 'fdp_plugins_viewer' ) && ! defined( 'FDP_EMERGENCY_LOG_ADMIN' ) ) ) ) {
		eos_dp_navigation();
		?>
		<h2><?php esc_html_e( 'Sorry, you have not the right for this page', 'freesoul-deactivate-plugins' ); ?></h2>
		<?php
		return;
	}
	wp_enqueue_media();
	eos_dp_navigation();
	wp_nonce_field( 'fdp_export_plugins_list', 'fdp_export_plugins_list' );
	wp_nonce_field( 'fdp_import_plugins_list', 'fdp_import_plugins_list' );
	wp_nonce_field( 'fdp_install_plugin', 'fdp_install_plugin' );
	add_action( 'admin_footer', 'eos_dp_search_plugins_popup' );
	$active_plugins = eos_dp_active_plugins();
	$column_count   = max( 1, min( 3, absint( count( $active_plugins ) / 11 ) ) );
	?>
	<style>
	#fdp-plugins-list p:first-child{margin-top:0}
	#fdp-plugins-list{column-count:<?php echo esc_attr( $column_count ); ?>}
	#fdp-favorite-export-wrp .fdp-list-item{border-radius:50%}
	@media screen and (max-width:967px){#fdp-plugins-list{column-count:<?php echo esc_attr( min( 2, $column_count ) ); ?>}}
	@media screen and (max-width:600px){#fdp-plugins-list{column-count:<?php echo esc_attr( min( 1, $column_count ) ); ?>}}
	</style>
	<section id="fdp-favorite-plugins-wrp">
		<div id="fdp-favorite-tabs" class="fdp-tabs">
			<span id="fdp-favorite-export-tab" class="button eos-active"><?php esc_html_e( 'Export', 'freesoul-deactivate-plugins' ); ?></span>
			<?php if ( current_user_can( 'activate_plugins' ) ) { ?>
			<span id="fdp-favorite-import-tab" class="button"><?php esc_html_e( 'Import', 'freesoul-deactivate-plugins' ); ?></span>
			<?php } ?>
		</div>
		<input type="hidden" id="fdp_favorites_list_parent" />
		<div id="fdp-favorite-export-wrp" class="eos-dp-margin-top-48">
			<div id="fdp-plugins-list">
			<?php
			foreach ( $active_plugins as $plugin ) {
				$plugin_name = eos_dp_get_plugin_name_by_slug( $plugin );
				if( $plugin_name && !empty( $plugin_name ) ) {
				?>
				<p><input type="checkbox" class="fdp-list-item" value="<?php echo esc_attr( dirname( $plugin ) ); ?>" checked /><span><?php echo esc_html( strtoupper( $plugin_name ) ); ?></span></p>
			<?php } } ?>
			<div id="fdp-plugins-list-added"></div>
			</div>
			<div class="eos-dp-margin-top-32">
				<span id="eos-dp-select-plugins" class="button"><?php esc_html_e( 'Add plugins to the list', 'freesoul-deactivate-plugins' ); ?></span>
				<span id="eos-dp-export-plugins-list" class="button"><span class="dashicons dashicons-download" style="line-height:1.5"></span><?php esc_html_e( 'Export the list', 'freesoul-deactivate-plugins' ); ?></span>
				<div id="fdp-added-plugins"></div>
			</div>
		</div>
		<?php if ( current_user_can( 'activate_plugins' ) ) { ?>
		<div id="fdp-favorite-import-wrp"  class="eos-dp-margin-top-48 eos-hidden">
			<p><?php esc_html_e( 'Upload a list of plugins created by FDP.', 'freesoul-deactivate-plugins' ); ?></p>
			<span id="eos-dp-import-plugins" class="button"><span class="dashicons dashicons-upload" style="line-height:1.5"></span><?php esc_html_e( 'Import list', 'freesoul-deactivate-plugins' ); ?></span>
		</div>
		<?php } ?>
		<div id="fdp-installed-plugin-wrp" class="eos-dp-margin-top-32 eos-hidden" style="padding:10px">
			<h4 id="fdp-installing-progress"><?php esc_html_e( 'Installing plugins from the list...', 'freesoul-deactivate-plugins' ); ?></h4>
			<div id="fdp-installing-done" class="eos-hidden">
				<h4><?php esc_html_e( 'Plugins installed from the list:', 'freesoul-deactivate-plugins' ); ?></h4>
				<div id="fdp-installed-plugin"></div>
				<div class="eos-dp-margin-top-32">
					<a class="button" href="<?php echo esc_url( admin_url( 'plugins.php?plugin_status=inactive' ) ); ?>" target="_fdp_inactive_plugins"><?php esc_html_e( 'Activate the plugins', 'freesoul-deactivate-plugins' ); ?></a>
				</div>
			</div>
		</div>
		<div id="fdp-fail" class="notice notice-error eos-hidden" style="padding:10px" data-default_msg="<?php esc_attr_e( 'Something went wrong!', 'eos-dp-pro' ); ?>"></div>
	</section>
	<?php
	eos_dp_favorite_plugins_inline();
}

// Disaplay popup to search plugins.
function eos_dp_search_plugins_popup() {
	$dir = is_rtl() ? 'left' : 'right';
	?>
  <div id="fdp-search-plugins-popup" class="eos-hidden" style="position:fixed;left:0;right:0;top:0;bottom:0;padding: 40px;z-index: 999999;background:#000;background:rgba(0,0,0,0.6)">
		<div style="position:absolute;<?php echo esc_attr( $dir ); ?>:45px;top:45px;text-align:<?php echo esc_attr( $dir ); ?>">
			<span class="button" id="fdp-plugins-iframe-close"><?php esc_html_e( 'Close', 'freesoul-deactivate-plugins' ); ?></span>
			<span class="button" id="fdp-plugins-iframe-ok"><?php esc_html_e( 'Add to list', 'freesoul-deactivate-plugins' ); ?></span>
		</div>
		<div id="fdp-selected-plugins" style="background:#fff;padding:40px 20px 20px 20px"></div>
		<?php echo eos_dp_get_plugins_iframe(); //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on the output of eos_dp_get_plugins_iframe(). ?>
		<a id="fdp-export-plugins-link" href="#" class="eos-hidden" download="FDP-favorite-plugins-<?php echo esc_attr( substr( sanitize_key( md5( time() ) ), 0, 8 ) ); ?>.txt">Exported Plugins</a>
  </div>
	<script>
	var fdp_show_plugins = document.getElementById('eos-dp-select-plugins'),
		fdp_popup = document.getElementById('fdp-search-plugins-popup'),
		fdp_popup_close = document.getElementById('fdp-plugins-iframe-close'),
		fdp_export_wrp = document.getElementById('fdp-favorite-export-wrp'),
		fdp_import_wrp = document.getElementById('fdp-favorite-import-wrp'),
		fdp_export_tab = document.getElementById('fdp-favorite-export-tab'),
		fdp_import_tab = document.getElementById('fdp-favorite-import-tab');
	fdp_show_plugins.addEventListener('click',function(){
		fdp_popup.className = fdp_popup.className.replace('eos-hidden','');
	});
	fdp_popup_close.addEventListener('click',function(){
		fdp_popup.className = fdp_popup.className = 'eos-hidden';
	});
	fdp_export_tab.addEventListener('click',function(e){
		this.className = this.className.replace(' eos-active','') + ' eos-active';
		fdp_import_tab.className = fdp_import_tab.className.replace( ' eos-active','');
		fdp_export_wrp.className = fdp_export_wrp.className.replace( 'eos-hidden','');
		fdp_import_wrp.className = fdp_import_wrp.className.replace( 'eos-hidden','') + ' eos-hidden';
	});
	document.getElementById('fdp-favorite-import-tab').addEventListener('click',function(e){
		this.className = this.className.replace(' eos-active','') + ' eos-active';
		fdp_export_tab.className = fdp_export_tab.className.replace( ' eos-active','');
		fdp_import_wrp.className = fdp_import_wrp.className.replace( 'eos-hidden','');
		fdp_export_wrp.className = fdp_export_wrp.className.replace( 'eos-hidden','') + ' eos-hidden';
	});
	document.getElementById('eos-dp-import-plugins').addEventListener('click',function(e){
		e.preventDefault();
		var button = this,plugins_list_uploader = wp.media({
			title: 'Upload list',
			library : {
				type : "text"
			},
			button: {
				text: "<?php echo esc_js( __( 'Upload list', 'freesoul-deactivate-plugins' ) ); ?>"
			},
			multiple: false
		}).on('select', function() {
			button.className = button.className.replace(' eos-dp-progress','') + ' eos-dp-progress';
			var obj = plugins_list_uploader.state().get('selection')._byId,
				id = obj[Object.keys(obj)[0]].id,
				xhr_i = new XMLHttpRequest(),
				installed_msg = document.getElementById('fdp-installed-plugin'),
				installed_msg_wrp = document.getElementById('fdp-installed-plugin-wrp'),
				installing_progress = document.getElementById('fdp-installing-progress'),
				installing_done = document.getElementById('fdp-installing-done');
			installed_msg.innerHTML = '';
			installed_msg_wrp.className = installed_msg_wrp.className.replace(' eos-hidden','') + ' eos-hidden';
			xhr_i.open("POST",ajaxurl + '?action=eos_dp_import_plugins_list&id=' + id + '&nonce=' + document.getElementById('fdp_import_plugins_list').value,true);
			xhr_i.send();
			xhr_i.onload = function(){
				if('' !== xhr_i.response && '0' !== xhr_i.response){
					var plugins = xhr_i.response.split(';'),installed = '',n=0,xhr_p = new XMLHttpRequest();
					installed_msg_wrp.className = installed_msg_wrp.className.replace(' eos-hidden','');
					installing_progress.className = installing_progress.className.replace('eos-hidden','');
					installing_done.className = 'eos-hidden';
					eos_dp_install_plugin(xhr_p,plugins[n]);
					if('undefined' !== typeof(plugins[n])){
						installed_msg.innerHTML = 'Installing <span style="text-transform:capitalize">' + plugins[n].split('-').join(' ') + '</span>...';
						installed += '<span class="fdp-installed">' + plugins[n] + '</span>';
					}
					xhr_p.onload = function(){
						if('' === xhr_p.response || '0' === xhr_p.response){
							installed += '<span class="fdp-not-installed" style="padding:10px;margin:0 2px;background:#d8cbc3">' + plugins[n] + ' not installed</span>';
						}
						if('undefined' !== typeof(plugins[n])){
							installed += '<span class="fdp-installed" style="padding:10px;margin:0 2px;background:#d8cbc3">' + plugins[n] + '</span>';
						}
						++n;
						if(n < plugins.length + 1){
							eos_dp_install_plugin(xhr_p,plugins[n]);
							if(undefined !== typeof(plugins[n])){
								installed_msg.innerHTML = 'Installing <span style="text-transform:capitalize">' + plugins[n].split('-').join(' ') + '</span>...';
								installing_done.className = installing_done.className.replace('eos-hidden','');
							}
						}
						else{
							installed_msg.innerHTML = installed;
							installing_progress.className = 'eos-hidden';
							button.className = button.className.replace(' eos-dp-progress','');
							return;
						}
					}
				}
			}
		}).open();

	});
	document.getElementById('fdp-plugins-iframe-ok').addEventListener('click',function(){
		fdp_popup.className = fdp_popup.className = 'eos-hidden';
	});
	document.getElementById('eos-dp-export-plugins-list').addEventListener('click',function(){
		var data = {},active_plugins = [],checkboxes = document.getElementsByClassName('fdp-list-item');
		for(n in checkboxes){
			if(checkboxes[n].checked){
				active_plugins.push(checkboxes[n].value);
			}
		}
		data['plugins'] = document.getElementById('fdp_favorites_list_parent').value + ';' + active_plugins.join(';');
		data['nonce'] = document.getElementById('fdp_export_plugins_list').value;
		var xhr = new XMLHttpRequest(),fd = new FormData();
		fd.append('data',JSON.stringify(data));
		xhr.open("POST",ajaxurl + '?action=eos_dp_export_plugins_list',true);
		xhr.send(fd);
		xhr.onload = function(){
			if('' === xhr.response || '0' === xhr.response || xhr.response.indexOf('http') < 0){
				alert('Something went wrong.');
			}
			else{
				var link = document.getElementById('fdp-export-plugins-link');
				link.href = xhr.response;
				link.click();
			}
		}
		fdp_popup.className = fdp_popup.className = 'eos-hidden';
	});
	function eos_dp_install_plugin(request,plugin){
		request.open("POST",ajaxurl + '?action=eos_dp_install_plugin&nonce=' + document.getElementById('fdp_install_plugin').value + '&plugin=' + plugin,true);
		request.send(null);
	}
	</script>
	<?php
}
