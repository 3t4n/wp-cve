<?php
/**
*    Plugin Name: HoweScape Unity3d WebGL
*    Plugin URI: http://www.howescape.com 
*    Description: Plugin for Wordpress to create short code for Unity3d Game
*    Author: P.T.Howe
*    Text Domain: hs_unity3d_web_gl
*	Domain Path: /languages
*    Version: 2.3.6
*    Author URI: http://www.HoweScape.com 
* License: GPL2
*/ 
// Roll-A-Ball game created with unity3d.com  5.3.3f1
// Space-Shooter game created with unity3d.com 5.3.3f1
// Roll-a-ball game created with unity3d 2018.4
// Roll-a-ball game created with unity3d 2019.4
// Roll-a-ball game created with unity3d 2020.3
// Roll-A-Ball game created with unity3d 2021.3.9f1

// Constants
include_once (plugin_dir_path( __FILE__ )."./include/hs_unity3d_constants.php");

require_once(ABSPATH . 'wp-includes/pluggable.php');

// Setup
// Check which system and path separator 
//if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
//    //echo 'This is a server using Windows!';
//	DEFINE('DS', '\\'); 
//} else {
    //echo 'This is a server not using Windows!';
	DEFINE("DS", DIRECTORY_SEPARATOR); 
//}

class hs_unity3d_web_gl {
	
	function hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		/* */
		_e('<p>Game not found<br>', 'hs_unity3d_web_gl');
		if (strcmp($errorReport, HS_UNITY3D_ERROR_VERBOSE) == 0) {
			_e('Short Code Parameters:<br>','hs_unity3d_web_gl');	
			_e(' GameName: ','hs_unity3d_web_gl');
				echo $gameName.'<br>';
			_e('  GameDir: ','hs_unity3d_web_gl');
				echo $gameDir.'<br>';
			_e('    width: ','hs_unity3d_web_gl');
				echo $width.'<br>';
			_e('   height: ','hs_unity3d_web_gl');
				echo $height.'<br>';
			_e('  GameVer: ','hs_unity3d_web_gl');
				echo $gameVersion.'<br>';
			_e(' BuildTyp: ','hs_unity3d_web_gl');
				echo $buildType.'<br>';
		}
		echo '</p>';
	}
	
	function hs_unity3d_5_3_1($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {										
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		$gameLocation = "";
		$gameLocationPath = "";
		$gameVersionPath = "";
		$gameVersionPath = str_replace(".","_",$gameVersion);
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;
		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;
		} else {
			//_e('Game not found', 'hs_unity3d_web_gl');
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion,  
									$buildType, $errorReport);
		}

		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {
	
		$altGameName = 'Builds_WebGL';

		$gameFileExt ='';

		$my_gameName = __($gameName, 'hs_unity3d_web_gl');
		// Build page based on Builds.html created in game Builds directory
		//	wp_deregister_script('jquery-migrate');
		$game_page = "<meta charset=\"utf-8\">".
				"<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">".
				"<title>Unity WebGL Player | ".$gameName."</title>".
				"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\"/>".
				"<p class=\"header\"><span>Unity WebGL Player | </span>".$gameName."</p>".
				"<div class=\"template-wrap clear\">".
				"<canvas class=\"emscripten\" id=\"canvas\" oncontextmenu=\"event.preventDefault()\" height=\"".$height."px\" width=\"".$width."px\"></canvas>".
					"<br>".
					"<div class=\"logo\"></div>".
					"<div class=\"fullscreen\"><img src=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/fullscreen.png", __FILE__)."\" width=\"38\" height=\"38\" alt=\"Fullscreen\" title=\"Fullscreen\" onclick=\"SetFullscreen(1);\" /></div>".
					"<div class=\"title\">".$gameName."</div>".
				"</div>".
				"<script type='text/javascript'> \n".
					"var Module = { \n".
						"TOTAL_MEMORY:   268435456, \n".
						"errorhandler: null, \n".
						"dataUrl: \"".plugins_url($gameLocationPath.DS."Builds_WebGL.data".$gameFileExt,__FILE__)."\", \n".
						"codeUrl: \"".plugins_url($gameLocationPath.DS."Builds_WebGL.js".$gameFileExt,__FILE__)."\", \n".
						"memUrl: \"".plugins_url($gameLocationPath.DS."Builds_WebGL.mem".$gameFileExt,__FILE__)."\", \n".
					"};\n".
				"</script>\n".
				"<script type='text/javascript' src=\"".plugins_url(GAME_DIR.DS.$gameName."_".$gameVersionPath.RELEASE_SUFFIX.DS."UnityLoader.js",__FILE__)."\"></script>\n";

		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";			
		}
		return $game_page;
	}

	function hs_unity3d_5_5_1($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		$underIndex = strpos($gameName,'_');
		if ($underIndex === FALSE) {
			$gameNameOnly = $gameName;
		} else {
			$gameNameOnly = substr($gameName, 0, $underIndex);
		}
		//echo ("<br> gamenameO:".$gameNameOnly."<br>gameName:".$gameName."<br>dir:".$gameDir."<br>w:".$width."<br>h:".$height."<br>gameVer:".$gameVersion);
		$gameLocation = "";
		$gameLocationPath = "";
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;
		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;
		} else {
			//_e('Game not found', 'hs_unity3d_web_gl');
			$buildType = "";
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {

			$gameFileExt = "";

			$my_gameName = __($gameName, 'hs_unity3d_web_gl');
			$game_page = "<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\"/>".
				//"<title>Unity WebGL Player | ".$gameName."</title>".
				"<p class=\"header\"><span>Unity WebGL Player | </span>".$my_gameName."</p>".
				"<div class=\"template-wrap clear\">".
				"<canvas class=\"emscripten\" id=\"canvas\" oncontextmenu=\"event.preventDefault()\" height=\"".$height."px\" width=\"".$width."px\"></canvas>".
					"<br>\n".
					"<div class=\"logo\"></div>\n".
					"<div class=\"fullscreen\"><img src=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/fullscreen.png", __FILE__)."\" width=\"38\" height=\"38\" alt=\"Fullscreen\" title=\"Fullscreen\" onclick=\"SetFullscreen(1);\" /></div>\n".
					"<div class=\"title\">".$my_gameName."</div>\n".
				"</div>\n".
				"<script type='text/javascript'>\n".
					"var Module = {\n".
						"TOTAL_MEMORY:   268435456,\n".
						"errorhandler: null,\n".
						"compatibilitycheck: null,\n".
						"backgroundColor: \"#222C36\",\n".
						"splashStyle: \"Light\",\n".
						"dataUrl: \"".plugins_url($gameLocationPath.DS.$gameNameOnly.".data".$gameFileExt,__FILE__)."\",\n".
						"codeUrl: \"".plugins_url($gameLocationPath.DS.$gameNameOnly.".js".$gameFileExt,__FILE__)."\",\n".
						"asmUrl: \"".plugins_url($gameLocationPath.DS.$gameNameOnly.".asm.js".$gameFileExt,__FILE__)."\",\n".
						"memUrl: \"".plugins_url($gameLocationPath.DS.$gameNameOnly.".mem".$gameFileExt,__FILE__)."\",\n".
					"};\n".
				"</script>\n".
				"<script type='text/javascript' src=\"".plugins_url($gameLocationPath.DS."UnityLoader.js",__FILE__)."\"></script>";				
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";						
		}
	return $game_page;
}

	function hs_unity3d_5_6_0($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		$gameLocation = "";
		$gameLocationPath = "";
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		//echo "checkPath:".plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir.":<br>";
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;
		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;
		} else {
			//_e('Game not found', 'hs_unity3d_web_gl');
			$buildType = "";
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {
		//_e(' width='.$width.' height='.$height.'<br>');
		$my_gameName = __($gameName, 'hs_unity3d_web_gl');
		$game_page = 
				"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\">".
				"<link rel=\"stylesheet\" href=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_5_6_0/style.css",__FILE__)."\">".
				"<script type='text/javascript' src=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_5_6_0/UnityProgress.js",__FILE__)."\"></script>".
				"<script type='text/javascript' src=\"".plugins_url($gameLocationPath.DS."UnityLoader.js",__FILE__)."\"></script>".
				"<script>".
				"var gameInstance = UnityLoader.instantiate(\"gameContainer\", \"".plugins_url($gameLocationPath.DS.$gameName.".json",__FILE__)."\", {onProgress: UnityProgress});".
				"</script>".
				"<div class=\"webgl-content\">".
				"<div id=\"gameContainer\" style=\"width: ".$width."px; height: ".$height."px\">".
				"</div>".
				"<div class=\"footer\">".
				"<div class=\"webgl-logo\"></div>".
				"<div class=\"fullscreen\" onclick=\"gameInstance.SetFullscreen(1)\"></div>".
				"<div class=\"title\">".$my_gameName."</div>".
				"</div>".
				"</div>";	
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";			
		}
		return $game_page;
	}

	function hs_unity3d_5_3_1A() {
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		//echo "checkPath:".plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir.":<br>";
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;
		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;
		} else {
			_e('Game not found', 'hs_unity3d_web_gl');
		}
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {
			//_e(' width='.$width.' height='.$height.'<br>');
			$my_gameName = __($gameName, 'hs_unity3d_web_gl');
			$game_page = 
					"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\">".
					"<link rel=\"stylesheet\" href=\"".plugins_url(SUPPORT_DIR.DS."TemplateData/style.css",__FILE__)."\">".
					"<script src=\"".plugins_url(SUPPORT_DIR.DS."TemplateData/UnityProgress.js",__FILE__)."\"></script>".
					"<script src=\"".plugins_url($gameLocationPath.DS."UnityLoader.js",__FILE__)."\"></script>";
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";			
		}
		return $game_page;
	}
	
	function hs_unity3d_2017_4_0f1($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		$gameLocation ="";
		$gameLocationPath = "";
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;

		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;

		} else {
//			_e('Game not found', 'hs_unity3d_web_gl');
			$buildType = "";
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {

		$my_gameName = __($gameName, 'hs_unity3d_web_gl');
		$game_page = 
				"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\">".
				"<link rel=\"stylesheet\" href=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2017_4_0f1/style.css",__FILE__)."\">".
				"<script type='text/javascript' src=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2017_4_0f1/UnityProgress.js",__FILE__)."\"></script>".
				"<script type='text/javascript' src=\"".plugins_url($gameLocationPath.DS."UnityLoader.js",__FILE__)."\"></script>".
				"<script type='text/javascript'>".
				"var gameInstance = UnityLoader.instantiate(\"gameContainer\", \"".plugins_url($gameLocationPath.DS.$gameName.".json",__FILE__)."\", {onProgress: UnityProgress});".
				"</script>".
				"<div class=\"webgl-content\">".
				"<div id=\"gameContainer\" style=\"width: ".$width."px; height: ".$height."px\"></div>".
				"<div class=\"footer\">".
				"<div class=\"webgl-logo\"></div>".
				"<div class=\"fullscreen\" onclick=\"gameInstance.SetFullscreen(1)\"></div>".
				"<div class=\"title\">".$my_gameName."</div>".
				"</div>".
				"</div>";
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";
		}
		return $game_page;
	}

	function hs_unity3d_2018_4($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		$gameLocation ="";
		$gameLocationPath = "";
		//echo (" file: ".GAME_DIR.DS.$gameDir.' :<br>');
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;

		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;

		} else {
//			_e('Game not found<br>', 'hs_unity3d_web_gl');
			$buildType = "";
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		//echo ("gamLoc: ".$gameLocation." :<br>");
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {

		$my_gameName = __($gameName, 'hs_unity3d_web_gl');
		$game_page = 
				"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\">".
				"<link rel=\"stylesheet\" href=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2018_4/style.css",__FILE__)."\">".
				"<script type='text/javascript' src=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2018_4/UnityProgress.js",__FILE__)."\"></script>".
				"<script type='text/javascript' src=\"".plugins_url($gameLocationPath.DS."UnityLoader.js",__FILE__)."\"></script>".
				"<script type='text/javascript'>".
				"var gameInstance = UnityLoader.instantiate(\"gameContainer\", \"".plugins_url($gameLocationPath.DS.$gameName.".json",__FILE__)."\", {onProgress: UnityProgress});".
				"</script>".
				"<div class=\"webgl-content\">".
				"<div id=\"gameContainer\" style=\"width: ".$width."px; height: ".$height."px\"></div>".
				"<div class=\"footer\">".
				"<div class=\"webgl-logo\"></div>".
				"<div class=\"fullscreen\" onclick=\"gameInstance.SetFullscreen(1)\"></div>".
				"<div class=\"title\">".$my_gameName."</div>".
				"</div>".
				"</div>";
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";
		}
		return $game_page;
	}

	function hs_unity3d_2019_4($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		$gameLocation ="";
		$gameLocationPath = "";
		//echo (" file: ".GAME_DIR.DS.$gameDir.' :<br>');
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;

		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;

		} else {
//			_e('Game not found<br>', 'hs_unity3d_web_gl');
			$buildType = "";
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		//echo ("gamLoc: ".$gameLocation." :<br>");
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {

		$my_gameName = __($gameName, 'hs_unity3d_web_gl');
		$game_page = 
				"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData/favicon.ico",__FILE__)."\">".
				"<link rel=\"stylesheet\" href=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2019_4/style.css",__FILE__)."\">".
				"<script type='text/javascript' src=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2019_4/UnityProgress.js",__FILE__)."\"></script>".
				"<script type='text/javascript' src=\"".plugins_url($gameLocationPath.DS."UnityLoader.js",__FILE__)."\"></script>".
				"<script type='text/javascript'>".
				"var gameInstance = UnityLoader.instantiate(\"gameContainer\", \"".plugins_url($gameLocationPath.DS.$gameName.".json",__FILE__)."\", {onProgress: UnityProgress});".
				"</script>".
				"<div class=\"webgl-content\">".
				"<div id=\"gameContainer\" style=\"width: ".$width."px; height: ".$height."px\"></div>".
				"<div class=\"footer\">".
				"<div class=\"webgl-logo\"></div>".
				"<div class=\"fullscreen\" onclick=\"gameInstance.SetFullscreen(1)\"></div>".
				"<div class=\"title\">".$my_gameName."</div>".
				"</div>".
				"</div>";
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";
		}
		return $game_page;
	}

	function hs_unity3d_2020_3($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		// Build page based on Builds.html created in game Builds directory
		// Build Symbol to represent path of game. Give preference to Release
		$gameLocation = '';
		$gameLocationPath = '';
		$game_page = '';
		//echo (' file: '.GAME_DIR.DS.$gameDir.' :<br>');
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;
			//echo '<br> FileLoc:'.$gameLocation;
			//echo '<br> FIlePath:'.$gameLocationPath;
		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;
		} else {
//			_e('Game not found<br>', 'hs_unity3d_web_gl');
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		// Set default extensions , Test param for update
		$dataExtension = '.data.gz';
		$frameworkExtension = '.js.gz';
		$asmExtension = '.wasm.gz';
		$compareType = strcasecmp($buildType, 'Development');
		//echo '<br>CompareResult:'.$compareType.'<br>';
		if (strcasecmp($buildType, HS_UNITY3D_BUILDTYPE_DEVELOPMENT) == 0) {
			$dataExtension = '.data';
			$frameworkExtension = '.js';
			$asmExtension = '.wasm';
		} elseif (strcasecmp($buildType, HS_UNITY3D_BUILDTYPE_PRODUCTION_BROTLI) == 0) {
			$dataExtension = '.data.br';
			$frameworkExtension = '.js.br';
			$asmExtension = '.wasm.br';
		} elseif (strcasecmp($buildType, HS_UNITY3D_BUILDTYPE_PRODUCTION_GZIP) == 0) {
			$dataExtension = '.data.gz';
			$frameworkExtension = '.js.gz';
			$asmExtension = '.wasm.gz';
		}
		
//		echo 'param    gameName: '.$gameName.'<br>';
//		echo 'param     gameDir: '.$gameDir.'<br>';
//		echo 'param       width: '.$width.'<br>';
//		echo 'param      height: '.$height.'<br>';
//		echo 'param gameVersion: '.$gameVersion.'<br>';
//		echo 'param   buildType: '.$buildType.'<br>';
//		
//		echo ('gamLoc: '.$gameLocation.' :<br>');
//		echo ('gamLocPath: '.$gameLocationPath.' :<br>');
//		echo ' BuildType: '.$buildType.'<br>';
//		echo 'DataExt: '.$dataExtension.'<br>';
//		echo 'FrameExt: '.$frameworkExtension.'<br>';
//		echo 'AsmExt: '.$asmExtension.'<br>';
//		$buildPath = plugins_url(SUPPORT_DIR.DS."GeneralTemplateData".DS."favicon.ico",__FILE__);
//		echo "   path ICO: ".$buildPath."<br>";
//		echo " path style: ".plugins_url(SUPPORT_DIR.DS."TemplateData_2020_3/style.css",__FILE__)."<br>";
//		echo "path loader: ".plugins_url($gameLocationPath.DS.$gameName.".loader.js",__FILE__)."<br>";
//		echo "  path Data: ".plugins_url($gameLocationPath.DS.$gameName.$dataExtension,__FILE__)."<br>";
//		echo " path frame: ".plugins_url($gameLocationPath.DS.$gameName.".framework".$frameworkExtension,__FILE__)."<br>";
//		echo " path Assem: ".plugins_url($gameLocationPath.DS.$gameName.$asmExtension,__FILE__)."<br>";
		
		if (strlen($gameLocation) > 0 && strlen($gameLocationPath) > 0) {
			$my_gameName = __($gameName, 'hs_unity3d_web_gl');
			$game_page = 
				 "<meta charset=\"utf-8\">".
				 "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">".
				"<title>Unity WebGL Player | ".$my_gameName."</title>".
				"<link rel=\"shortcut icon\" href=\"".plugins_url(SUPPORT_DIR.DS."GeneralTemplateData".DS."favicon.ico",__FILE__)."\"> ".
				"<link rel=\"stylesheet\" href=\"".plugins_url(SUPPORT_DIR.DS."TemplateData_2020_3".DS."style.css",__FILE__)."\"> ".
				"<div id=\"unity-container\" class=\"unity-desktop\"> ".
					"<canvas id=\"unity-canvas\" width=".$width." height=".$height."></canvas> ".
					"<div id=\"unity-loading-bar\"> ".
						"<div id=\"unity-logo\"></div> ".
						"<div id=\"unity-progress-bar-empty\"> ".
							"<div id=\"unity-progress-bar-full\"></div> ".
						"</div> ".
					"</div> ".
					"<div id=\"unity-mobile-warning\"> ".
						"WebGL builds are not supported on mobile devices. ".
					"</div> ".
					"<div id=\"unity-footer\"> ".
						"<div id=\"unity-webgl-logo\"></div> ".
						"<div id=\"unity-fullscreen-button\"></div> ".
						"<div id=\"unity-build-title\">".$my_gameName."</div> ".
					"</div> ".
				"</div> ".
				"<script> ".
					"var loaderUrl = \"".plugins_url($gameLocationPath.DS.$gameName.".loader.js",__FILE__)."\"; ".
					"var config = { ".
						"dataUrl: \"".plugins_url($gameLocationPath.DS.$gameName.$dataExtension,__FILE__)."\", ".
						"frameworkUrl: \"".plugins_url($gameLocationPath.DS.$gameName.".framework".$frameworkExtension,__FILE__)."\", ".
						"codeUrl: \"".plugins_url($gameLocationPath.DS.$gameName.$asmExtension,__FILE__)."\", ".
						"streamingAssetsUrl: \"StreamingAssets\", ". 
						"companyName: \"DefaultCompany\", ". 
						"productName: \"".$gameName."\", ".
						"productVersion: \"0.1\", ".
					"}; ".  
				    "var container = document.querySelector(\"#unity-container\"); ".
					"var canvas = document.querySelector(\"#unity-canvas\"); ".
					"var loadingBar = document.querySelector(\"#unity-loading-bar\"); ".
					"var progressBarFull = document.querySelector(\"#unity-progress-bar-full\"); ".
					"var fullscreenButton = document.querySelector(\"#unity-fullscreen-button\"); ".
					"var mobileWarning = document.querySelector(\"#unity-mobile-warning\"); ".
					"if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) { ".
						"container.className = \"unity-mobile\"; ".
						"config.devicePixelRatio = 1; ".
						"mobileWarning.style.display = \"block\"; ".
						"setTimeout(() => { ".
							"mobileWarning.style.display = \"none\"; ".
						"}, 5000); ".
					"} else { ".
						"canvas.style.width = \"".$width."px\"; ".
						"canvas.style.height = \"".$height."px\"; ".
					"} ".
					"loadingBar.style.display = \"block\"; ".
					"var script = document.createElement(\"script\"); ".
					//'script.src = loaderUrl; '.
					"script.src = \"".plugins_url($gameLocationPath.DS.$my_gameName.".loader.js",__FILE__)."\"; ".
					"script.onload = () => { ".
						"createUnityInstance(canvas, config, (progress) => { ".
							"progressBarFull.style.width = 100 * progress + \"%\"; ".
						"}).then((unityInstance) => { ".
							"loadingBar.style.display = \"none\"; ".
							"fullscreenButton.onclick = () => { ".
								"unityInstance.SetFullscreen(1); ".
							"}; ".
						"}).catch((message) => { ".
							"alert(message); ".
					"}); ".
				"}; ".
				"document.body.appendChild(script);	".
				"</script>";
		//echo "<br>PageTxtStart: ".$game_page." :endPageTxt<br>";
		} else {
			_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
			$game_page = "";
		}
		return $game_page;
		//return "";
	}

	function hs_unity3d_2021_3_9f1($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport) {
		//$game_page = " parm-1: ".$gameName.", ".$gameNameDir.", ".$width.", ".$height.", ".$gameVersion.", ".$buildType.", ".$errorReport."<br>";
		//echo $game_page;
		$gameLocation = '';
		$gameLocationPath = '';
		$game_page = '';
		//echo (' file: '.GAME_DIR.DS.$gameDir.' :<br>');
		if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameDir;
			$gameLocationPath = GAME_DIR.DS.$gameDir;
			//echo '<br> FileLoc:'.$gameLocation;
			//echo '<br> FIlePath:'.$gameLocationPath;
		} else if (file_exists(plugin_dir_path( __FILE__ ).$gameDir)) {
			$gameLocation = plugin_dir_path( __FILE__ ).$gameDir;
			$gameLocationPath = $gameDir;
		} else {
//			_e('Game not found<br>', 'hs_unity3d_web_gl');
			$this->hs_unity3d_error_param_passed ($gameName, $gameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}

		$errorReportShowBanner = '';
		if (strcmp($errorReport, HS_UNITY3D_ERROR_VERBOSE) == 0) {
			$errorReportShowBanner = '  showBanner: unityShowBanner,';
		}
		// Set default extensions , Test param for update
		$dataExtension = '.data.gz';
		$frameworkExtension = '.js.gz';
		$asmExtension = '.wasm.gz';
		$compareType = strcasecmp($buildType, 'Development');
		//echo '<br>CompareResult:'.$compareType.'<br>';
		if (strcasecmp($buildType, HS_UNITY3D_BUILDTYPE_DEVELOPMENT) == 0) {
			$dataExtension = '.data';
			$frameworkExtension = '.js';
			$asmExtension = '.wasm';
		} elseif (strcasecmp($buildType, HS_UNITY3D_BUILDTYPE_PRODUCTION_BROTLI) == 0) {
			$dataExtension = '.data.br';
			$frameworkExtension = '.js.br';
			$asmExtension = '.wasm.br';
		} elseif (strcasecmp($buildType, HS_UNITY3D_BUILDTYPE_PRODUCTION_GZIP) == 0) {
			$dataExtension = '.data.gz';
			$frameworkExtension = '.js.gz';
			$asmExtension = '.wasm.gz';
		}

		$game_page = '<link rel="shortcut icon" href="'.plugins_url(SUPPORT_DIR.DS.'TemplateData_2021_3_9f1/favicon.ico',__FILE__).'">'.
					 '<link rel="stylesheet" href="'.plugins_url(SUPPORT_DIR.DS.'TemplateData_2021_3_9f1/style.css',__FILE__).'">'.
					'<div id="unity-container" class="unity-desktop">'.
						'<canvas id="unity-canvas" width="'.$width.'" height="'.$height.'"></canvas>'.
						'<div id="unity-loading-bar">'.
							'<div id="unity-logo"></div>'.
							'<div id="unity-progress-bar-empty">'.
								'<div id="unity-progress-bar-full"></div>'.
							'</div>'.
						'</div>'.
						'<div id="unity-warning"> </div>'.
						'<div id="unity-footer">'.
							'<div id="unity-webgl-logo"></div>'.
							'<div id="unity-fullscreen-button"></div>'.
							'<div id="unity-build-title">'.$gameName.'</div>'.
						'</div>'.
					'</div>'.					
				'<script>'.
					'var container = document.querySelector("#unity-container");'.
					'var canvas = document.querySelector("#unity-canvas");'.
					'var loadingBar = document.querySelector("#unity-loading-bar");'.
					'var progressBarFull = document.querySelector("#unity-progress-bar-full");'.
					'var fullscreenButton = document.querySelector("#unity-fullscreen-button");'.
					'var warningBanner = document.querySelector("#unity-warning");'.
	  ''.
//      '// Shows a temporary message banner/ribbon for a few seconds, or'.
//      '// a permanent error message on top of the canvas if type==\'error\'.'.
//      '// If type==\'warning\', a yellow highlight color is used.'.
//      '// Modify or remove this function to customize the visually presented'.
//      '// way that non-critical warnings and error messages are presented to the'.
//      '// user.'.
      'function unityShowBanner(msg, type) {'.
      '  function updateBannerVisibility() {'.
      '    warningBanner.style.display = warningBanner.children.length ? \'block\' : \'none\';'.
      '  }'.
      '  var div = document.createElement(\'div\');'.
      '  div.innerHTML = msg;'.
      '  warningBanner.appendChild(div);'.
      '  if (type == \'error\') div.style = \'background: red; padding: 10px;\';'.
      '  else {'.
      '    if (type == \'warning\') div.style = \'background: yellow; padding: 10px;\';'.
      '    setTimeout(function() {'.
      '      warningBanner.removeChild(div);'.
      '      updateBannerVisibility();'.
      '    }, 5000);'.
      '  }'.
      '  updateBannerVisibility();'.
      '}'.
		''.
//
      'var loaderUrl = "'.plugins_url($gameLocationPath.DS.$gameNameDir.DS.$gameName.".loader.js",__FILE__).'";'.
      'var config = {'.
           '  dataUrl: "'.plugins_url($gameLocationPath.DS.$gameNameDir.DS.$gameName.$dataExtension,__FILE__).'",'.
      '  frameworkUrl: "'.plugins_url($gameLocationPath.DS.$gameNameDir.DS.$gameName.".framework".$frameworkExtension,__FILE__).'",'.
           '  codeUrl: "'.plugins_url($gameLocationPath.DS.$gameNameDir.DS.$gameName.$asmExtension,__FILE__).'",'.
      '  streamingAssetsUrl: "StreamingAssets",'.
      '  companyName: "DefaultCompany",'.
      '  productName: "'.$gameName.'",'.
      '  productVersion: "1.0",'.
		$errorReportShowBanner .
//      '  showBanner: unityShowBanner,'.
      '};'.
//
//
      'loadingBar.style.display = "block";'.
//
      'var script = document.createElement("script");'.
      'script.src = "'.plugins_url($gameLocationPath.DS.$gameNameDir.DS.$gameName.".loader.js",__FILE__).'";'.
      'script.onload = () => {'.
      '  createUnityInstance(canvas, config, (progress) => {'.
      '    progressBarFull.style.width = 100 * progress + "%";'.
      '  }).then((unityInstance) => {'.
      '    loadingBar.style.display = "none";'.
      '    fullscreenButton.onclick = () => {'.
      '      unityInstance.SetFullscreen(1);'.
      '    };'.
      '  }).catch((message) => {'.
      '    alert(message);'.
      '  });'.
      '};'.
      'document.body.appendChild(script);'.
    '</script>';
 
		return $game_page;
	}
	
	function hs_unity3d_game ( $atts ) {
		
		/* Fill in default values for Application */
		$pull_unity_atts = shortcode_atts ( array(
					'src' => 'Roll-A-Ball',
					'width' => '480',
					'height' => '640',
					'u3dver' => '5.3.1',
					'buildtype' => HS_UNITY3D_BUILDTYPE_PRODUCTION,
					'errorreport' => HS_UNITY3D_ERROR_NORMAL), $atts);
		/* Error message for missing parameter */
		if ( !$pull_unity_atts['src'] ) 	return "(missing unity src)";
		if ( !$pull_unity_atts['width'] ) 	return "(missing unity width)";
		if ( !$pull_unity_atts['height'] ) 	return "(missing unity height)";
		if ( !$pull_unity_atts['u3dver'] ) 	return "(missing unity version)";

		$width 	= $pull_unity_atts['width'];
		$height	= $pull_unity_atts['height'];
		$gameName = $pull_unity_atts['src'];
		$buildParm = $pull_unity_atts['buildtype'];
		$errorParm = $pull_unity_atts['errorreport'];
		//echo "BuildParm:".$buildParm." len:".strlen($buildParm)."<br>";
		//echo "errorreport:".$errorParm." len:".strlen($errorParm)."<br>";
		if ( strlen($buildParm) > 0 ) {
			$buildType = $buildParm;
		} else {
			$buildType = HS_UNITY3D_BUILDTYPE_PRODUCTION;
		}
		if (strlen($errorParm) > 0) {
			$errorReport = $errorParm;
		} else {
			$errorReport = HS_UNITY3D_ERROR_NORMAL;
		}

		//foreach ($atts as $oneParm => $oneValue) {
		//	echo "<br> parm: ".$oneParm." : ".$oneValue."<br>";
		//}

		$gameNameUpdated = preg_replace('/[^\x00-\x7f]/', '', $gameName);
		//	echo 'nam2:'.$gameNameUpdated.':<br>';
		//echo 'hex:'.bin2hex($result).':<br>';
		$gameVersion = $pull_unity_atts['u3dver'];
		// Test if dir exists and then check game_dir
		$gameVersionDir = str_replace(".","_",$gameVersion);
		if (strlen($gameVersion)>0) {
			$gameNameDir = $gameNameUpdated."_".$gameVersionDir.RELEASE_SUFFIX;
		} else {
			$gameNameDir = $gameNameUpdated.RELEASE_SUFFIX;
		}

		if ($gameVersion == '2021.3.9f1'){
			//echo " 2021-3-8 parm-1: ".$gameName.", ".$gameNameDir.", ".$width.", ".$height.", ".$gameVersion.", ".$buildType.", ".$errorReport."<br>";
			$game_page = $this->hs_unity3d_2021_3_9f1($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} elseif ($gameVersion == '2020.3') {
			//echo " parm-1: ".$gameName.", ".$gameNameDir.", ".$width.", ".$height.", ".$gameVersion.", ".$buildType.", ".$errorReport."<br>";
			$game_page = $this->hs_unity3d_2020_3($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} elseif ($gameVersion == '2019.4') {
			$game_page = $this->hs_unity3d_2019_4($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} elseif ($gameVersion == '2018.4') {
			$game_page = $this->hs_unity3d_2018_4($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} elseif ($gameVersion == '2017.4.0f1') {
			$game_page = $this->hs_unity3d_2017_4_0f1($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);	
		} elseif ($gameVersion == '5.6.0') {
			$game_page = $this->hs_unity3d_5_6_0($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} elseif ($gameVersion == '5.5.1') {
			$game_page = $this->hs_unity3d_5_5_1($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} elseif ($gameVersion == '5.3.1') {
			$game_page = $this->hs_unity3d_5_3_1($gameName, $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		} else {

			$game_page = $this->hs_unity3d_5_3_1($gameName."_5_3_1", $gameNameDir, $width, $height, $gameVersion, $buildType, $errorReport);
		}
		return $game_page;
	}

	// function for short code for displaying different games on same page
	function hs_unity3d_gamepage ( $attrs ) {
		global	$wpdb;

		$current_game = 'Roll-A-Ball';
		$current_width = '500';
		$current_height = '600';
		$current_version = '5.3.1';
		$current_build = HS_UNITY3D_BUILDTYPE_PRODUCTION;
		$current_error = HS_UNITY3D_ERROR_NORMAL;
		$gamePage = '';

		if (isset($_POST['HS_SETTINGS_UNITY3D_TESTPAGE_NONCE']) && wp_verify_nonce($_POST['HS_SETTINGS_UNITY3D_TESTPAGE_NONCE'], 'HS_UNITY3D_TESTPAGE') != false) {
			if (isset($_POST['unity3dTest-form-submit'])) {
				if (isset($_POST['GameName'])) {
					$current_game = $_POST['GameName'];
				} else {
					$current_game = 'Roll-A-Ball';
				}
				if (isset($_POST['GameHeight'])) {
					$current_height = $_POST['GameHeight'];
				} else {
					$current_height = '500';
				}
				if (isset($_POST['GameWidth'])) {
					$current_width = $_POST['GameWidth'];
				} else {
					$current_width = '600';
				}
				if (isset($_POST['GameVersion'])) {
					$current_version = $_POST['GameVersion'];
				} else {
					$current_version = '5.3.1';
				}
				if (isset($_POST['buildType'])) {
					$current_build = $_POST['buildType'];
				} else {
					$current_build = HS_UNITY3D_BUILDTYPE_PRODUCTION;
				}
				if (isset($_POST['errorReport'])) {
					$current_error = $_POST['errorReport'];
				} else {
					$current_error = HS_UNITY3D_ERROR_NORMAL;
				}
			}
			$pathVersion = '_'.str_replace('.','_',$current_version);
			$underIndex = strpos($current_game,'_');
			$gameNameOnly = substr($current_game, 0, $underIndex);										
			// Test if directory exists 
			//echo (" Test value:".plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameNameOnly.$pathVersion.RELEASE_SUFFIX."<br>");
			if (file_exists(plugin_dir_path( __FILE__ ).GAME_DIR.DS.$gameNameOnly.$pathVersion.RELEASE_SUFFIX)) {
				if (0 == strcmp($current_version, '2021.3.9f1')) {
					$gamePage = $this->hs_unity3d_2021_3_9f1($gameNameOnly, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				} elseif (0 == strcmp($current_version, '2020.3')) {
					//echo "<br>2020.3";
					//echo "<br> gameSel3:".$gameNameOnly.":=:".$current_game.RELEASE_SUFFIX.":=:".$current_width.":=:".$current_height.":=:".$current_version.":=:".$current_build.":=:".$current_error."<br>";
					$gamePage = $this->hs_unity3d_2020_3($gameNameOnly, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				} elseif (0 == strcmp($current_version, '2019.4')) {
					$gamePage = $this->hs_unity3d_2019_4($gameNameOnly, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				} elseif (0 == strcmp($current_version, '2018.4')) {
					$gamePage = $this->hs_unity3d_2018_4($gameNameOnly, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				} elseif (0 == strcmp($current_version, '2017.4.0f1')) {
					//echo ("<br>2017.4.0f1");
					//echo "gameSel3:".$current_game.":<br>:".$current_game.RELEASE_SUFFIX.":<br>:".$current_version.":<br>";
					$gamePage = $this->hs_unity3d_2017_4_0f1($gameNameOnly, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);	
				} elseif ( 0 == strcmp($current_version,'5.6.0')) {
					//echo ("<br>5.6.0");
					//$gamePage = $this->hs_unity3d_5_6_0($gameList[$index][0], $gameList[$index][1], $gameList[$index][2], $gameList[$index][3], $gameList[$index][0], $gameList[$index][5]);		
					$gamePage = $this->hs_unity3d_5_6_0($current_game, $current_game.$pathVersion.RELEASE_SUFFIX, $current_width, $current_height, $current_version);		
				} elseif ( 0 == strcmp($current_version,'5.5.1')) {
					//echo ("<br>5.5.1-0");
					$gamePage = $this->hs_unity3d_5_5_1($current_game, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				} else {
					//echo ("<br>5.3.1-1");
					//echo "<br>Params:".$gameNameOnly.", ".$current_game.RELEASE_SUFFIX.", ".$current_width.", ".$current_height.", ".$current_version."<br>";
					//$gamePage = $this->hs_unity3d_5_3_1('Roll-A-Ball', 'Roll-A-Ball_5_3_1-Release', $current_width, $current_height);
					$gamePage = $this->hs_unity3d_5_3_1($gameNameOnly, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				} 
			} else {
				if ($current_error == HS_UNITY3D_ERROR_NORMAL) {
					_e('<p>Short cut parameters do not match available games.</p>', 'hs_unity3d_web_gl');
				} else {
					$this->hs_unity3d_error_param_passed ($current_game, $current_game.RELEASE_SUFFIX, $current_width, $current_height, $current_version, $current_build, $current_error);
				}
			}
			echo $gamePage;
		}		
		
		echo '<div class="wrap">';

		$my_buttonValue = __('Test Shortcode Values', 'hs_unity3d_web_gl');
		$my_testShortCode = __('Test Shortcode', 'hs_unity3d_web_gl');
		$my_availableGames = __('HoweScape Unity3d Available Games', 'hs_unity3d_web_gl');
		$widthList = Array("200","300","320","400","500","600","700","800");
		$heightList = Array("200","300","320","400","500","600","700","800");
		$versionList = Array('5.3.1', '5.5.1','2017.4.0f1','5.6.0','2018.4','2019.4','2020.3','2021.3.9f1');
		$buildTypeList = Array(HS_UNITY3D_BUILDTYPE_PRODUCTION,HS_UNITY3D_BUILDTYPE_DEVELOPMENT,HS_UNITY3D_BUILDTYPE_PRODUCTION_GZIP,HS_UNITY3D_BUILDTYPE_PRODUCTION_BROTLI);
		$reportTypeList = Array(HS_UNITY3D_ERROR_NORMAL,HS_UNITY3D_ERROR_VERBOSE);
		
		$gameDir = plugin_dir_path( __FILE__ ).GAME_DIR.DS;
		$releaseTagZip = RELEASE_SUFFIX.".zip";
		$dirLen = strlen($gameDir);
		$releaseLen = strlen($gameDir);
		$releaseExt = strlen(RELEASE_SUFFIX);
			
		//get_screen_icon();
		echo('<h2>'.$my_availableGames.'</h2>');
		echo '<p>';
		echo '[hs_unity3d_web_gl_game src="'.$current_game.
				'"  height="'.$current_height.
				'"  width="'.$current_width.
				'"  u3dver="'.$current_version.
				'"  buildtype="'.$current_build.
				'"  errorReport="'.$current_error.'"]<br>';
		echo '</p>';
		echo('<div class="divTable grayTable">');
		echo('<div class="divTableBody">');
		
		echo ('<div class="divTableRow">');
			echo ('<div class="divTableCell"><h3>'.$my_testShortCode.'</h3></div>');
		echo ('</div>');
		// Form for sample Shortcode with values
		echo ('<form id="" method="post" target="_blank">');
			wp_nonce_field('HS_UNITY3D_TESTPAGE', 'HS_SETTINGS_UNITY3D_TESTPAGE_NONCE', true, true);		
		echo ('<div class="divTableRow">');
				echo ('<div class="divTableCell">');
					echo ('[hs_unity3d_web_gl_game src=<select name="GameName">');
						foreach (glob($gameDir."*".RELEASE_SUFFIX, GLOB_ONLYDIR) as $gameNamePath) {
							$fullLen = strlen($gameNamePath);
							$gameName_ver = substr($gameNamePath, $dirLen, $fullLen - $releaseLen - $releaseExt);
							$gameVerIndex = strpos($gameName_ver,'_');
							$gameName = substr($gameName_ver, 0, $gameVerIndex);
							$gameVersion = substr($gameName_ver, $gameVerIndex+1);
							$gameVersion = str_replace('_','.',$gameVersion);
							$gameVersion_len = strlen($gameVersion);
							if (strcmp($current_game, $gameName_ver) == 0) {
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo('<option value="'.$gameName_ver.'" '.$selected.' >'.$gameName_ver.'</option>');
						}							
					echo ('</select>');
					echo (' height=<select name="GameHeight">');
						foreach ($heightList as $singleHeight) {
							if (strcmp($current_height, $singleHeight) == 0){
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo ('<option value="'.$singleHeight.'" '.$selected.' >'.$singleHeight.'</option>');
						}
					echo ('</select>');
					echo (' width=<select name="GameWidth">');
						foreach ($widthList as $singleWidth) {
							if (strcmp($current_width, $singleWidth) == 0){
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo ('<option value="'.$singleWidth.'" '.$selected.' >'.$singleWidth.'</option>');
						}
					echo ('</select>');
					echo (' u3dver=<select name="GameVersion">');
						foreach ($versionList as $singleVersion) {
							if (strcmp($current_version, $singleVersion) == 0){
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo ('<option value="'.$singleVersion.'" '.$selected.' >'.$singleVersion.'</option>');
						}
					echo ('</select>');
					echo (' buildType=<select name="buildType">');
						foreach ($buildTypeList as $singleBuildType) {
							if (strcmp($current_build, $singleBuildType) == 0){
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo ('<option value="'.$singleBuildType.'" '.$selected.' >'.$singleBuildType.'</option>');
						}
					
					echo ('</select>');
					echo (' errorReport=<select name="errorReport">');
						foreach ($reportTypeList as $singleErrorType) {
							if (strcmp($current_error, $singleErrorType) == 0){
								$selected = 'selected="selected"';
							} else {
								$selected = '';
							}
							echo ('<option value="'.$singleErrorType.'" '.$selected.' >'.$singleErrorType.'</option>');
						}

					
					echo ('</select>');
					echo (']');
				echo ('</div>');
		echo ('</div>');
		echo ('<div class="divTableRow">');
			echo ('<div class="divTableCell"><input type="submit" name="unity3dTest-form-submit" value="'.$my_buttonValue.'" class="button button-primary"/></di>');
		echo ('</div>');
		echo ('</form>');

		echo('</div>');			
		echo('</div>');  // body
	echo '</div>';		// table
echo'</section>';
	//echo $gamePage;
	//$gamePage = '<section><h2>game</h2>'.$gamePage.'</section>';
	//return $gamePage;
	return;
	}
	
	/**
	* This method is recursive to remove a directory and its contents.
	* This is necessary when a unity3d game is being extracted from the ZIP file. 
	* In order to ensure only the files from the zip are present.
	*/
	function recursiveRemoveDirectory($directory) {
		foreach(glob("{$directory}/*") as $file)
		{
			if(is_dir($file)) { 
				recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($directory);
	}
	
	// Code for short code to add javascript variables which have the current logged in user ID and User Name
	function hs_unity3d_currentUser($inParams) {
		
		//foreach($inParams as $key => $value)
		//{
		//	echo $key." has the value". $value."<br>";
		//}
		//ob_start();

		$current_user = wp_get_current_user();

		echo '<script>var '.HS_WORDPRESS_CURRENT_USER_ID.' = '.$current_user->ID.';';
		echo  'var '.HS_WORDPRESS_CURRENT_USER_NAME.' = "'.$current_user->display_name.'";</script>';
		//$output = ob_get_clean();
		return; 		
	}
	
	function hs_unity3d_web_gl_admin() {
		$my_settingsPage = __('HoweScape Unity3d Game Settings', 'hs_unity3d_web_gl');
		$my_pageTitle = __('Unity3d Games', 'hs_unity3d_web_gl');
		add_options_page($my_pageTitle, $my_settingsPage, 'manage_options', 
					'hs_unity3d_web_gl_setting', array($this, 'hs_unity3d_admin_options_page'));
	}
	
	function hs_unity3d_admin_options_page($PageName) {
		global	$wpdb;
				
		if (isset($_POST['HS_SETTINGS_UNITY3D_EXTRACT_NONCE']) && wp_verify_nonce($_POST['HS_SETTINGS_UNITY3D_EXTRACT_NONCE'], 'HS_UNITY3D_EXTRACT') != false) {
			if (isset( $_POST['extract-game']) && !empty($_POST['extract-game'])) {
				// Test for a radio button has been selected. on extract
				if (isset( $_POST['extractGroup']) && !empty($_POST['extractGroup'])) {
					//echo "<br> game to extract <BR>";
					// Get selected zip file to unzip into plugin
					$zipfilename = $_POST['extractGroup'];
					// Get file name with out extension
					$zipfilenameparts = explode("/",$zipfilename);
					$partscount = count($zipfilenameparts);
					$i = 0;

					$zipfileext = $zipfilenameparts[$partscount-1];
					//echo ("ext:+:".$zipfileext.":+:".$partscount."<br>");
					$zipfilepart = explode(".",$zipfileext);

					// Check for second upload in month/days
					$zipfilepart_version = explode(RELEASE_SUFFIX, $zipfilepart[0]);
					$versionCount = count($zipfilepart_version);
					if ($versionCount > 1) {
						echo ("version: ".$zipfilepart_version[0].RELEASE_SUFFIX);
						$expandedGameDir = plugin_dir_path(__FILE__).GAME_DIR.DS.$zipfilepart_version[0].RELEASE_SUFFIX.DS;
					} else {
						// Build dir name to expanded game dir
						$expandedGameDir = plugin_dir_path(__FILE__).GAME_DIR.DS.$zipfilepart[0].DS;
					}
					
					// Create / verify that game_dir exists
					if (!file_exists(plugin_dir_path(__FILE__).GAME_DIR)) {
						$makedirstatus = mkdir(plugin_dir_path(__FILE__).GAME_DIR);
					}
					if (file_exists($expandedGameDir)) {
						// Remove current files to create new ones.
						$this->recursiveRemoveDirectory($expandedGameDir);
					}
					//echo ("Mkdir: ".$expandedGameDir.": end<br>");
					$makedirstatus = mkdir($expandedGameDir);	// Create game dir
			
					WP_Filesystem();
					$mediaDir = wp_upload_dir();	// Get dir of media
					$zipfilename = $_POST['extractGroup'];

					$fileToUnZip = $mediaDir['basedir'].$zipfilename;

					$unzipfilestatus = unzip_file($fileToUnZip, $expandedGameDir);
		
					if ( is_wp_error($unzipfilestatus) ) {
						echo '<br> error Open: '.$fileToUnZip.' :End<br>';
						//echo '<br> error Open: '.zipFileErrMsg($Zip_Handle).' :End<br>';
					} else {
						//echo "<br>Files extracted <br>";
					}
				} else {
					// No game from media directory selected
					$my_errorMessage =  __('No Available Media File selected', 'hs_unity3d_web_gl');;
					echo "<br><h2>".$my_errorMessage."</h2><BR>";
				}
				
			}
		} elseif (isset($_POST['HS_SETTINGS_UNITY3D_GAMES_NONCE']) && wp_verify_nonce($_POST['HS_SETTINGS_UNITY3D_GAMES_NONCE'], 'HS_UNITY3D_GAMES') != false) {
			if (isset( $_POST['remove-game']) && !empty($_POST['remove-game'])) {				
				// Test for a radio button has been selected. on extract
				if (isset( $_POST['deleteGroup']) && !empty($_POST['deleteGroup'])) {
					// Get selected zip file to unzip into plugin
					$dirfilename = $_POST['removeGroup'];
					$dir = plugin_dir_path( __FILE__ );
					//$fullDir = $dir.substr($dirfilename,0).DS."*";		
					$fullDir = $dir.DS.GAME_DIR.DS.substr($dirfilename,0).RELEASE_SUFFIX;		
					$fullDir = str_replace("\\",DS,$fullDir);	
					//echo "fulldir:".$fullDir."<br>";
					$this->recursiveRemoveDirectory($fullDir);
					//recursiveRemoveDirectoryGame($fullDir);
				} else {
					// No game from media directory selected
					$my_errorMessage =  __('No Game selected', 'hs_unity3d_web_gl');;
					echo "<br><h2>".$my_errorMessage."</h2><BR>";
				}
			} 
		} elseif (isset($_POST['HS_SETTINGS_UNITY3D_TESTPAGE_NONCE']) && wp_verify_nonce($_POST['HS_SETTINGS_UNITY3D_TESTPAGE_NONCE'], 'HS_UNITY3D_TESTPAGE') != false) {
			if (isset( $_POST['unity3dTest-form-submit']) && !empty($_POST['unity3dTest-form-submit'])) {
				// short code parameters
				$selectedGame = $_POST['GameName'];
				$selectedHeight = $_POST['GameHeight'];
				$selectedWidth = $_POST['GameWidth'];
				$selectedVersion = $_POST['GameVersion'];
				// Open new tab with selected values
				echo " begin: ".$selectedGame." ".$selectedHeight." ".$selectedWidth." ".$selectedVersion." <br>";
			}
		}
		
		$my_pluginGames = __('Plugin Games:', 'hs_unity3d_web_gl');
		$my_availableGames = __('HoweScape Unity3d Available Games', 'hs_unity3d_web_gl');
		$my_buttonValue = __('Test Shortcode Values', 'hs_unity3d_web_gl');
		$my_testShortCode = __('Test Shortcode', 'hs_unity3d_web_gl');

		$my_extractGames = __('Extract games from media zip file into plugin for play', 'hs_unity3d_web_gl');
		$my_extractGameButton = __('Extract Game', 'hs_unity3d_web_gl');
		$my_removeGame = __('Remove Game', 'hs_unity3d_web_gl');
		$my_availableMediaFileGames = __('Available Media File games', 'hs_unity3d_web_gl');

		$gameDir = plugin_dir_path( __FILE__ ).GAME_DIR.DS;
		$releaseTagZip = RELEASE_SUFFIX.".zip";
		$dirLen = strlen($gameDir);
		$releaseLen = strlen($gameDir);
		$releaseExt = strlen(RELEASE_SUFFIX);


		echo '<div class="wrap">';
		//get_screen_icon();
		echo('<h2>'.$my_availableGames.'</h2>');
		echo('<div class="divTable grayTable">');
		echo('<div class="divTableBody">');
			echo ('<div class="divTableRow">');
				echo ('<div class="divTableCell"><h3>'.$my_pluginGames.'</h3></div>');
			echo ('</div>');
			//echo ("debug:".$gameDir."*".RELEASE_SUFFIX."<br>");

			echo '<form id="" method="post">';
			wp_nonce_field('HS_UNITY3D_GAMES', 'HS_SETTINGS_UNITY3D_GAMES_NONCE', true, true);
			foreach (glob($gameDir."*".RELEASE_SUFFIX, GLOB_ONLYDIR) as $gameNamePath) {
				$fullLen = strlen($gameNamePath);
				$gameName = substr($gameNamePath, $dirLen, $fullLen - $releaseLen - $releaseExt);
				echo ('<div class="divTableRow">');
					echo('<div class="divTableCellCenter"><input type="radio" name="deleteGroup" value="'.$gameName.'" '.
													' onclick="document.getElementById(\'remove-game-button\').disabled = false; " /></div>');
					echo('<div class="divTableCell">'.$gameName.'</div>');
				echo('</div>');
			}
			echo ('<div class="divTableRow">');
				echo('<div class="divTableCell"></div>');
				echo('<div class="divTableCell"><input id="remove-game-button" type="submit" name="remove-game" value="'.$my_removeGame.'" class="button button-primary" disabled/></div>'); 
			echo ('</div>');
			echo '</form>';
			echo "</div>";	// Table Body
			echo "</div>";	// Gray Table
			echo "<br>";	// Break between tables
			$upload_dir = wp_upload_dir();
			$basedirLen = strlen($upload_dir['basedir']);
		echo('<div class="divTable grayTable">');
		echo('<div class="divTableBody">');
			echo ('<div class="divTableRow">');
				echo ('<div class="divTableCell"><h3>'.$my_availableMediaFileGames.'</h3></div>');
			echo ('</div>');
			echo '<form id="" method="post">';
			wp_nonce_field('HS_UNITY3D_EXTRACT', 'HS_SETTINGS_UNITY3D_EXTRACT_NONCE', true, true);
			foreach (glob($upload_dir['basedir'].DS."*", GLOB_ONLYDIR) as $gameNamePathYear) {
				$gameNamePathYearLen = strlen($gameNamePathYear);
				foreach (glob("$gameNamePathYear/*", GLOB_ONLYDIR) as $gameNamePathYearMonth) {
					foreach (glob("$gameNamePathYearMonth/*".RELEASE_SUFFIX."*.zip") as $gameNamePathYearMonthZip) {
						echo ('<div class="divTableRow">');
							echo ('<div class="divTableCellCenter"><input type="radio" name="extractGroup" value="'.substr($gameNamePathYearMonthZip,$basedirLen).'" '.
													' onclick="document.getElementById(\'extract-game-button\').disabled = false;" /></div>');
							echo ('<div class="divTableCell">'.substr($gameNamePathYearMonthZip,$basedirLen).'</div>');
						echo ('</div>');
					}
				}
			}
		echo ('<div class="divTableRow">');
			echo('<div class="divTableCell"></div>');
			echo ('<div class="divTableCell"><input id="extract-game-button" type="submit" name="extract-game" value="'.$my_extractGameButton.'" class="button button-primary" disabled /></div>'); 
		echo ('</div>');		
		echo '</form>';
//				
		echo('</div>');	// Body
		echo('</div>');	// Table
		echo '</div>';
	}
	
	function hs_unity3d_web_gl_load_textdomain() {
		load_plugin_textdomain( 'wp-admin-motivation', false, dirname( plugin_basename(__FILE__) ) . '/language/' );
	}

	function load_js_css($hook) {
		wp_register_style ( 'hs_Unity3dGames',  plugins_url('include/hs_unity3d_style.css', __FILE__));
		wp_enqueue_style  ( 'hs_Unity3dGames' );	
	}
	
	
	function hs_unity3d_web_gl_settings_description ($links, $file) {
		$settings_link = 'options-general.php?page=hs_unity3d_web_gl_setting.php';
		$my_settings = __('Settings', 'hs_unity3d_web_gl');
		if (strpos( $file, 'hs_unity3d_web_gl.php' ) != false ) {
			$new_links = array('<a href="'.$settings_link.'" target="_blank">'.$my_settings.'</a>');
			
			$links = array_merge ($links, $new_links);
		}
		return $links; 
	}
	
	function hs_unity3d_game_mime_types ($mimes) {
		/* */
		$mimes['wasm'] = 'application/wasm';
		//$mimes['br'] = 'application/x-br';
		$mimes['br'] = 'application/brotli';
		$mimes['gz'] = 'application/x-gz';
		$mimes['js.gz'] = 'application/x-javascript; charset=UTF-8';
		$mimes['data.gz'] = 'application/octet-stream';
		$mimes['wasm.gz'] = 'application/octet-stream';
		
		return $mimes;
	}


	public function __Construct() {	

		// Add Javascript and CSS for admin screens
		add_action('admin_init', array($this, 'load_js_css'));
	
        // Add Javascript and CSS for front-end display
		add_action( 'wp_enqueue_scripts',    array($this, 'load_js_css'));
		add_action( 'admin_enqueue_scripts', array($this, 'load_js_css'));
	
		// Create Cookie
		$next_date =  time() - 42000; //date('Y-m-d', strtotime($current_date . "+1 days"));
		//setcookie(HS_COOKIE_UNITY3D_USER, $current_user->user_login, $next_date, '/', get_site_url());

		// add settings link to plugin
		add_filter( 'plugin_row_meta', array($this, 'hs_unity3d_web_gl_settings_description'), 10, 2 );
		
//PTH		add_filter( 'mime_types', array($this, 'hs_unity3d_game_mime_types'));
		
		// Install Admin Options
		add_action('admin_menu', array($this, 'hs_unity3d_web_gl_admin'));
		// Transation Link
		add_action('plugins_loaded', array($this, 'hs_unity3d_web_gl_load_textdomain'));
		// Unity Short Code
		add_shortCode('hs_unity3d_web_gl_game', 		array($this, 'hs_unity3d_game'));
		add_shortCode('hs_unity3d_web_gl_gamepage', 	array($this, 'hs_unity3d_gamepage'));
		add_shortCode('hs_unity3d_current_user', 		array($this, 'hs_unity3d_currentUser'));
	}
}

// Variable of plugin object
global	$hs_unity3d_webgl_obj;

// Create an instance of the class to kick off the whole thing
$hs_unity3d_webgl_obj = new hs_unity3d_web_gl();

?>