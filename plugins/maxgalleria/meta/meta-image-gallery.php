<script type="text/javascript">		
	jQuery(document).ready(function() {
		jQuery("#maxgalleria-tabs").tabs();
		
		//jQuery(".mg-tabs").click(function(){
    jQuery(document).on("click", ".mg-tabs", function() {
      var tabref = jQuery(this).attr("id");
      if (window.sessionStorage){
        sessionStorage.setItem('tab', tabref);
			}
	  });	
		
		if (window.sessionStorage){
      var current_tab = sessionStorage.getItem('tab');
			if(current_tab !== null) {
				jQuery('#' + current_tab).trigger("click");
			}	
		}
	});
</script>

<div class="maxgalleria-meta">
	<div id="maxgalleria-tabs">
		<ul>
			<li><a class="mg-tabs" href="#maxgalleria-tabs-media"><?php esc_html_e('Images', 'maxgalleria') ?></a></li>
			<li><a class="mg-tabs" href="#maxgalleria-tabs-template"><?php esc_html_e('Template', 'maxgalleria') ?></a></li>
			<li><a class="mg-tabs" href="#maxgalleria-tabs-options"><?php esc_html_e('Options', 'maxgalleria') ?></a></li>
			<li><a class="mg-tabs" href="#maxgalleria-tabs-description"><?php esc_html_e('Description', 'maxgalleria') ?></a></li>
			<li><a class="mg-tabs" href="#maxgalleria-tabs-advanced"><?php esc_html_e('Advanced', 'maxgalleria') ?></a></li>
		</ul>
		
		<div id="maxgalleria-tabs-media">
			<?php include_once 'meta-images.php' ?>
		</div>
		<div id="maxgalleria-tabs-template">
			<?php include_once 'meta-template.php' ?>
		</div>		
		<div id="maxgalleria-tabs-options">
			<?php include_once 'meta-options.php' ?>
		</div>
		<div id="maxgalleria-tabs-description">
			<?php include_once 'meta-description.php' ?>
		</div>
		<div id="maxgalleria-tabs-advanced">
			<?php include_once 'meta-advanced.php' ?>
		</div>
	</div>
</div>