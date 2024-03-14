<?php
defined( 'EOS_EWS_PLUGIN_DIR' ) || exit; // Exit not accessed by this plugin

wp_nonce_field( 'eos_ews_export','eos_ews_export' );
$opts = eos_ews_get_option( 'eos_ews_opts' );
$checked = isset( $opts['global'] ) && 'convert' !== $opts['global'] ? '' : ' checked';
$shortcodesA = isset( $opts['keep'] ) ? $opts['keep'] : array();
$shortcodes = !empty( $shortcodesA ) ? implode( PHP_EOL,$shortcodesA ) : '';
?>
<div id="eos-ews-wrp">
  <h3><?php esc_html_e( 'Shortcodes','eos-ews' ); ?></h3>
  <div>
    <label>
      <input id="eos-ews-sht-global" type="checkbox" value="1"<?php echo $checked; ?> />
      <?php esc_html_e( 'Convert shortcodes to HTML','eos-ews' ); ?>
    </label>
  </div>
  <div id="eos-ews-sht-keep-list-wrp" style="margin-top:32px">
    <label>
      <p><?php esc_html_e( 'List of shortcode you want to keep without conversion. Write them one per line.','eos-ews' ); ?></p>
      <textarea id="eos-ews-sht-keep-list" class="large-text code" rows="10" cols="50"><?php echo esc_html( $shortcodes ); ?></textarea>
    </label>
  </div>
  <div style="margin-top:32px;margin-bottom:32px">
    <span id="eos-ews-save" class="button" style="background-image:url(<?php echo EOS_EWS_PLUGIN_URL.'/assets/img/ajax-loader.gif)'; ?>;background-repeat:no-repeat;background-position:-9999px -9999px;background-size:32px 32px"><?php esc_html_e( 'Save shortcodes settings','eos-ews' ); ?></span>
  </div>
</div>
<script>
var eos_ews_chk = document.getElementById('eos-ews-sht-global'),
  eos_ews_save = document.getElementById('eos-ews-save');
if(!eos_ews_chk.checked) document.getElementById('eos-ews-sht-keep-list-wrp').style.display = 'none';
eos_ews_save.addEventListener("click",function(){
  eos_ews_save.style.backgroundPosition = 'center center';
  var downloadRequest = new XMLHttpRequest(),fd=new FormData();
	downloadRequest.onload = function(e) {
		if(this.readyState === 4) {
			if('1' !== e.target.responseText){
        eos_ews_save.style.backgroundPosition = '-99999px -99999px';
			}
		}
		return false;
	};
	fd.append("global",eos_ews_chk.checked);
	fd.append("keep_list",document.getElementById('eos-ews-sht-keep-list').value);
	fd.append("nonce",document.getElementById("eos_ews_export").value);
	downloadRequest.open("POST","<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>" + "?action=eos_ews_save_options",true);
	downloadRequest.send(fd);
});
eos_ews_chk.addEventListener("click",function(){
  document.getElementById('eos-ews-sht-keep-list-wrp').style.display = eos_ews_chk.checked ? 'block' : 'none';
});
</script>
