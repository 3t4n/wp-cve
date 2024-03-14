<?php


$aalDiscoveryJapan = new aalModule('discoveryjapan','Discovery Japan Links',3);
$aalModules[] = $aalDiscoveryJapan;

$aalDiscoveryJapan->aalModuleHook('content','aalDiscoveryJapanDisplay');




add_action( 'admin_init', 'aal_discoveryjapan_register_settings' );


function aal_discoveryjapan_register_settings() { 
   register_setting( 'aal_discoveryjapan_settings', 'aal_discoveryjapanid' );
   register_setting( 'aal_discoveryjapan_settings', 'aal_discoveryjapanapikey' );
}


function aalDiscoveryJapanDisplay() {
	
	//$amazoncat = get_option('aal_amazoncat');
	
	
	?>

<script type="text/javascript">

function aal_discoveryjapan_validate() {
	
		if(!document.aal_discoveryjapanform.aal_discoveryjapanid.value) { alert("Please add your Discovery Japan ID"); return false; }
		if(!document.aal_discoveryjapanform.aal_discoveryjapanapikey.value) { alert("Please add your Discovery Japan API Key"); return false; }
				
	}
	
	</script>
	
	
<div class="wrap">  
    <div class="icon32" id="icon-options-general"></div>  
        
        
                <h2>Discovery Japan Links</h2>
                <br /><br />
                
                         
                
                
                Once you add your affiliate ID and activate Discovery Japan links, they will start to appear on your website. The manual links that you add will have priority.<br />
                This feature will only work if you have set the API Key in the "API Key" menu.
                <br /><br />
                
<div class="aal_general_settings">
		<form method="post" action="options.php" name="aal_discoveryjapanform" onsubmit="return aal_discoveryjapan_validate();"> 
<?php
		settings_fields( 'aal_discoveryjapan_settings' );
		do_settings_sections('aal_discoveryjapan_settings_display');
		
?>
		<span class="aal_label">Discovery Japan Affiliate ID:</span> <input type="text" name="aal_discoveryjapanid" value="<?php echo get_option('aal_discoveryjapanid'); ?>" />
		
	<br /><br />
		<span class="aal_label">Discovery Japan API key:</span> <input class="aal_big_input" type="text" name="aal_discoveryjapanapikey" value="<?php echo get_option('aal_discoveryjapanapikey'); ?>" />
		<br /><br />


		<p>You can get your Discovery Japan API key and secret from your <a href="https://partner.discovery-japan.me/">Discovery Japan Affiliate Program</a>. <br /><br />



<?php
	submit_button('Save');
	echo '</form></div>';
	
	update_option('aal_settings_updated',time());	
?>
	<a href="<?php echo admin_url('admin.php?page=aal_apimanagement'); ?>" class="button button-primary">Back to API Management</a>

<?php
	
	echo '</div>';

}




?>