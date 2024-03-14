<div style="width:100%; height: auto; display: table;">
	<div id="pano" style="width:100%;">
	
		<?php if ( !is_file(get_home_path() . $js_url)) echo "<h2>ERROR: JS File " . $js_url . " not exists</h2>"; ?>
		<?php if ( !is_file(get_home_path() . $xml_url)) echo "<h2>ERROR: XML File " . $xml_url . " not exists</h2>"; ?>
		<?php if ( !is_file(get_home_path() . $swf_url)) echo "<h2>ERROR: SWF File " . $swf_url . " not exists</h2>"; ?>
		
	</div>
</div>