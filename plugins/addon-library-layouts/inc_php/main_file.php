<?php 

defined('LAYOUTS_EDITOR_INC') or die('Restricted access');


try{

	//add shortcode
	function addonlibrary_uc_layout_shortcode($args){
		
		$layoutID = UniteFunctionsUC::getVal($args, "id");
		$content = HelperUC::outputLayout($layoutID, true);
		
		return($content);
	}
	
	add_shortcode( 'uc_layout', 'addonlibrary_uc_layout_shortcode' );
	add_shortcode( 'unite_addon_layout', 'addonlibrary_uc_layout_shortcode' );
	
	
	//-------------------------------------------------------------
	
	if(is_admin()){		//load admin part
		
		new UniteLayoutEditorAdmin($mainFilepath);
		
	}else{		//load front part
		
		//dmp("load editor front");
		
		//require_once LayoutEditorGlobals::$pathProvider . "provider_front.class.php";
		//$UCproductFront = new UniteProviderFrontUC($mainFilepath);
	}

	
	}catch(Exception $e){
		$message = $e->getMessage();
		$trace = $e->getTraceAsString();
		echo "Addon Library Error: <b>".$message."</b>";
	
		if(LayoutEditorGlobals::SHOW_TRACE == true)
			dmp($trace);
	}
	
	
?>