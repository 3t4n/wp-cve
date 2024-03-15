<?php

function test2(){
	
	$data = array();
	$data["_type"] = "query";
	$data["q"] = "";
	
	$objPostsSelect = new UniteGalleryPostsSelect();
	$arrPostList = $objPostsSelect->getPostListForSelectFromData($data);
	
	
	dmp($arrPostList);
	exit();
	
}


function testViewPutPostSelect(){
	
	?>
	<h2>try on the gallery items!!!</h2>
	
	<div id="ug_manager_settings_wrapper" style="width:350px;">
	<?php 
		$objPostsSelect = new UniteGalleryPostsSelect();
		
		$objSettings = $objPostsSelect->getPostsSelectSettings();
		
		$output = new UniteSettingsOutputWideUGNEW();
		$output->init($objSettings);
				
		$output->draw("unitegallery_manager_settings");
	?>
	</div>
	
	<script>

		jQuery(document).ready(function(){
			
			var objSettings = new UniteSettingsUGNEW();

			var objSettingsWrapper = jQuery("#ug_manager_settings_wrapper");
			var objSettingsInner = objSettingsWrapper.find(".unite_settings_wrapper");

			objSettings.init(objSettingsInner);
			
		});
	
					
	</script>
	
	<?php 
	
}

//test2();

testViewPutPostSelect();