<?php
/*
editor.php, V 1.07, altm, 22.11.2013
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 init Multimap support
all rights reseved
*/
// check for rights
if ( !defined('ABSPATH'))
    die('You are not allowed to call this page directly.');
	
	global $wpdb;

	@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
	$article_id	= isset($_GET["pid"]) ? $_GET["pid"] : uniqid("trk_");	//
	$map_id = 'map_0';

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<meta http-equiv="cache-control" content="no-cache">
	<title>ATLsoft Editor</title>	
	<base target="_self" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
 	<script language="javascript" type="text/javascript">	
		
		function loadScript(scriptname) {  
		  var snode = document.createElement('script');  
		  snode.setAttribute('type','text/javascript');  
		  snode.setAttribute('src',scriptname);  
		  document.getElementsByTagName('head')[0].appendChild(snode);  
		}  
		
		var rfName = "<?php echo $article_id; ?>";
		var FileBrowserDialog = {
			haveFile:false,
			gpxFile:null,
			gpxFileName:null,
			init : function () {
				// filename from selection
				var fName = null;
				var markedMap = tinyMCE.activeEditor.selection.getContent({format : 'text'});
					var result = markedMap.search(/(http:\/\/.+?\.gpx)/gi);
					var gpxFile = RegExp.$1;
				if(gpxFile != "" && result > -1){
						// we have a map with selected gpx so we edit this
						this.gpxFile = gpxFile;
						var start = this.gpxFile.lastIndexOf('\/');
						var end = this.gpxFile.search(/\.gpx/gi);
						fName = this.gpxFile.substr(start + 1 , end - start - 1);
						this.haveFile = true;
				} else {
					// filname from permalink
					fName = tinyMCE.activeEditor.execCommand('mceGMapPermalink');
					// filename from post id
					if(fName == null){
						fName = tinyMCEPopup.editor.contentDocument.location.href; // baseURI; not for IE
						var start = fName.search(/post=/);
						var end = fName.search(/action=/);
						fName = fName.substr(start + 5, end - start - 6);
					}
					// random name if no post id or permalink
					if(fName == "")
						fName = rfName;
					// if more than one map  expand  filename
					var postContent = tinyMCE.activeEditor.getContent({format : 'text'}).toLowerCase();
					var result = postContent.split("gpx=");
					if(result.length > 1){
						fName += "_"+(result.length-1);
					}			
				}		
				this.gpxFileName = fName;
				document.getElementById('uploadFileName').value = this.gpxFileName; 
			},
			mySubmit : function (fName) {
			tinyMCE.activeEditor.execCommand('mceGMapInsertContent',fName);
			tinyMCEPopup.close();
			}
		}
		tinyMCEPopup.onInit.add(FileBrowserDialog.init, FileBrowserDialog);

	</script>
</head>	

<?php
	$action			= isset($_GET["action"]) ? $_GET["action"] : "none";			// no action by default
	$query			= isset($_GET["q"]) ? $_GET["q"] : "";							// no action by default
	$trackFile		= isset($_POST["trackFile"]) ? $_POST["trackFile"] : "";		// content	
	$uploadFileName	= isset($_POST["uploadFileName"]) ? $_POST["uploadFileName"] : "";	// filename
	$post_id		= isset($_GET["post"]) ? $_GET["post"] : '';	//
	$startCoords	= isset($_POST["startCoords"]) ? $_POST["startCoords"] : '';	//
	$description	= isset($_POST["description"]) ? $_POST["description"] : '';	//
	if($action=="gmap_tinymce_editor"){	
		if($query=="upload"){	
			if(strlen(get_option('gmap_v3_gpx_proKey')) != 32){
			?>
				<body><br /><br /><br />
				<div class="panel_wrapper mceActionPanel" style="text-align:center; font-size:18px; font-family:verdana; margin:50px;"><?php _e("This feature is available in Pro-Version only.  More about Pro-Version you'll find ", GPX_GM_PLUGIN); ?> <a href="http://www.atlsoft.de/programmierung/google-maps-wordpress-plugin-professional/" target="_blank" onclick="FileBrowserDialogue.mySubmit(); return false;"><b><?php _e("here", GPX_GM_PLUGIN); ?></a></b>.</div>  			
				</body> 
				 <?php			
			 }
			else {
				 ?>	<body> <?php	
				upload_content_file($uploadFileName, $post_id, $description, $startCoords, $trackFile);
				 ?>	</body> <?php
			 }
		}
		else{
			display_eddi($map_id, $post_id);	
		}			
	}
?>
</html>

<?php

// displays the upload form
function display_eddi($map_id,$post_id)
{
		global $wpdb;
	?>
<body onload="getIconList();" style="padding: 0px; margin:0px;">

	<?php
	$httpurl = plugins_url(). "/". GPX_GM_PLUGIN."/tinymce/";
	$purl = $_SERVER[REQUEST_URI] . "&q=upload"; 
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>editor/editor.css" />
	<script type="text/javascript" src="//www.google.com/jsapi"></script>
	<script type="text/javascript">google.load("maps", "3", {other_params:"sensor=false"});</script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_wms_overlay.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>editor/jscolor.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>editor/gmap_v3_edit.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>editor/iconic.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_init.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_gpx_overlay.js"></script>
	<script type="text/javascript" src="<?php echo WP_PLUGIN_URL."/".GPX_GM_PLUGIN."/";?>js/gmap_v3_elevation.js"></script>
	<script language="javascript" type="text/javascript">	
	
	function loadGPXFile(map){
		if(FileBrowserDialog.haveFile){
			showGPX(map, FileBrowserDialog.gpxFile);
			map.g_seCookie = false;
		}
		else {
			jQuery('.gm_cMenu').find('a[href$="#newTrack"]').click();
		}
		toTrk(true);
	}

	function doUndo(){
		jQuery('.gm_cMenu').find('a[href$="#undo"]').click();
		return false;
	}	

	function doNewTrack(map){
		jQuery('.gm_cMenu').find('a[href$="#newTrack"]').click();
		return false;
	}	

	function prepUpload(map){
		var lat = "", lon = "";
		var p = map.polies[0];
		var m = map.markers;
		var totalLength = 0.0;
		if (m.length != 0){
			lat = m[0].position.lat();
			lon = m[0].position.lng();
		}
		if (p.latLngs.length!=0){
			var pa = p.getPath().getAt(0);
			if(pa){
				lat = pa.lat();
				lon = pa.lng();
			}
			var path = p.getPath();
			for (var i = 0; i < path.length; i++) {
				if( i > 0 ){
				var pa = path.getAt(i);
				var pb = path.getAt(i-1);
					totalLength += calcDistance(pa,pb);
				}
			}
		}
		jQuery('#startCoords').val(lat + ',' + lon);
		jQuery('#description').val('Länge ca. ' + (Math.round(totalLength * 10)/10).toString() + ' km');
			// uploadTrack(map);
			// document.Uploadform.submit();
		getElevation(map, totalLength);
		
	}	
	
	function getElevation(map, totalLength){
		var path = new Array();
		var length = map.polies[0].getPath().length;
		for (var i = 0; i < length; i++){
			path.push(map.polies[0].getPath().getAt(i));
		}
		// path = gPath.getArray();
		var length = 300; // google max. pts./request
		if(path.length > length){
			var startPos = path[0];
			var endPos = path[path.length-1];
			if(path.length > 100){
				while(path.length > 200){
					var i = path.length-1;
					for (path.length-1; i > 0; i = i-2) {
						path.splice(i,1);
					}
				}
			}
			path[path.length-1] = endPos;		
		}

		var pathRequest = {
		  'path': path,
		  'samples': path.length
		}
		// Create an ElevationService.
		var elevator = new google.maps.ElevationService();
		// Initiate the path request.
		elevator.getElevationAlongPath(pathRequest, function plotElevation(results, status) {
			var deltaHeight = 0.0;
			if (status == google.maps.ElevationStatus.OK) {
				for (var i = 0; i < results.length; i++) {
					if( i > 0 ){
						var diff = results[i].elevation - results[i-1].elevation;
						if(diff > 0)
							deltaHeight += diff;
					}
				}
				jQuery('#description').val('Länge ca. ' + (Math.round(totalLength * 10)/10).toString() + ' km, Steigung ' + (Math.round(deltaHeight * 10)/10).toString() + ' m');
			} else {
				jQuery('#description').val('Länge ca. ' + (Math.round(totalLength * 10)/10).toString() + ' km');
			}
			uploadTrack(map);
			document.Uploadform.submit();
		});
	}		

	
 </script>		
		<div style="height:100%; width:100%;">
			<div style="height:100%; width:100%;">
				<div id="<?php echo $map_id ?>_iconListID" class="iconListUI"></div>
				<div id="<?php echo $map_id ?>" style="position:absolute; height:100%; width:100%; overflow:hidden;"></div>
			</div>
			<div class="ed_main">
				<div class="ed_main_symbol">
					<input type="button" value="<?php _e("Search Location", GPX_GM_PLUGIN); ?>" class="ed_btn"  onclick="gotoGeoLocation(<?php echo $map_id;?>);" /> 
					<hr /><input type="text" id="goto" name="goto" class="ed_addrtext"  onkeypress="if (event.keyCode == 13)gotoGeoLocation(<?php echo $map_id;?>);" /> 
				</div>
		<form  name="Uploadform" action="<?php echo $purl?>" method="post" enctype="multipart/form-data" >
				<div style="width:180px;" class="ed_main_symbol"><div>
					<input type="radio" id="toTrack" class="ed_radio" onclick="toTrk(true); return;" /> <?php _e("Track", GPX_GM_PLUGIN); ?>
					<input type="radio" id="toWpt" class="ed_radio" onclick="toTrk(false); return;" /> <?php _e("Waypoint", GPX_GM_PLUGIN); ?>
					</div><hr />
					<input type="button" value="<?php _e("New Track", GPX_GM_PLUGIN); ?>" class="ed_btn ed_btn_in"  onclick="doNewTrack(<?php echo $map_id;?>);" /> 
					<input type="button" value="<?php _e("Undo", GPX_GM_PLUGIN); ?>" class="ed_btn ed_btn_in"  onclick="doUndo();" />
				</div>
				<div class="ed_main_symbol">
					<input type="button" id="btn_icons" value="<?php _e("Symbol Gallery", GPX_GM_PLUGIN); ?>" onClick=" toggleIconlist();" class="ed_btn" />
					<hr /><?php makeIconListBox(); ?>
				</div> 
				<div class="ed_main_symbol ed_main_last"><a href="http://www.atlsoft.de/programmierung/map-editor-help/" target="_blank"> <?php _e("Help", GPX_GM_PLUGIN); ?></a><p style="margin:10px;">
					<input id="submit_btn" type="button" value="<?php _e("Save", GPX_GM_PLUGIN); ?>" class="updateButton" onclick="prepUpload(<?php echo $map_id; ?>);return;"/></p>
					<input type="hidden"  id="trackFile" name="trackFile"  />
					<input type="hidden"  id="uploadFileName" name="uploadFileName" />
					<input type="hidden"  id="post_id" name="post_id" value="<?php echo $post_id; ?>" />
					<input type="hidden"  id="startCoords" name="startCoords" value="" />
					<input type="hidden"  id="description" name="description" value="" />
					 <div id="progress_div" name="progress_div" style="display:none;visibility:hidden;">
						<img src="<?php echo $httpurl; ?>progress.gif" style="width:100px; height:12px;" alt="wait..." />
					 </div>  			
				</div>
		</form>
			</div>  
</div>	
	<?php
	$retval = '
	<script type="text/javascript">
	';
		$maptypes = get_option('gmap_v3_gpx_maptypes');
		if (is_array($maptypes)){
			foreach($maptypes as $map => $obj) {
				$copy = addslashes($obj[2]);
				if(stristr($copy, 'google')) {
					$copy = "";

				}
				$visible = "true";
				if( $obj[1] == 0)
					$visible = "false";
		$retval .= '
		var gmapv3_disableDefaultUI = false;
		var gmapv3_zoomControl = false;
		var mapobj = { 
			name: "' . $obj[0] . '",
			wms: "' . $obj[3] . '",
			minzoom: ' . $obj[5] . ',
			maxzoom: ' . $obj[6] . ',
			url: "' . $obj[4] . '",
			copy:"' . $copy . '",
			visible:' . $visible . '
		};
		mapTypesArr.push(mapobj);';
			}
		}
		$retval .= '
		var msg_00 = "'.__("click for fullsize","google-maps-gpx-viewer").'";
		var msg_01 = "'.__("IE 8 or higher is needed / switch of compatibility mode","google-maps-gpx-viewer").'";
		var msg_03 = "'.__("Distance","google-maps-gpx-viewer").'";
		var msg_04 = "'.__("Height","google-maps-gpx-viewer").'";
		var msg_05 = "'.__("Download","google-maps-gpx-viewer").'";
		var pluri = "' . WP_PLUGIN_URL."/".GPX_GM_PLUGIN. '/";
		var ieX = false;
		if (window.navigator.appName == "Microsoft Internet Explorer") {
			var err = ieX = true;
			if (document.documentMode > 7) err = false;
			if(err){
				//alert(msg_01);
			}
		}
		';		
		$retval .= '
			var mapSizeButton = false;
			var ' . $map_id . '; 
			google.setOnLoadCallback(function() {		
			';
		
		//init map
		$retval .= '
			' . $map_id . ' = init_map( "", "' . $map_id . '", 1);	
			load_map(' . $map_id . ', "", "", "");	
			' . $map_id . '.g_seCookie = true; // use cookie if no gpx available
			loadGPXFile(' . $map_id . ');
			post_init(' . $map_id . ');
			';
			$kl = get_option('gmap_v3_gpx_proKey');
			if(strlen($kl) == 32)
				$retval .= '
				loadScript("http://www.atlsoft.de/wp_gpx/wp_ed.js.php?key='.get_option('gmap_v3_gpx_proKey').'");  
				';
			else
				$retval .= '
					function uploadTrack(map){}
				';
		$retval .= '
			});
	</script>
	';
				
	echo $retval; 
	
	?></body><?php
	
}

function upload_content_file($fName, $post_id, $description, $startCoords, $trackFile)
{
	global $wpdb;
	$StatusMessage = "failed!";
	if(!isset($fName) || empty($trackFile))
	{		
		ShowPopUp($StatusMessage);
		?>
		<script language="javascript" type="text/javascript">	
			tinyMCEPopup.close();
		</script>
		<?php
	}	
	else
	{	
		$out = '<?xml version="1.0" encoding="utf-8" standalone="no" ?>'.$trackFile;

		$upload_d = wp_upload_dir();
		$upload_dir = $upload_d['basedir'] ;
		$DestFile = $upload_dir.'/'.$fName.'.gpx';

        $handle = fopen($DestFile, "w");
        fwrite($handle, stripslashes($out));
        fclose($handle);

		$upload_dir = $upload_d['baseurl'] ;
		$ActualFileName = $upload_dir . '/' .$fName.'.gpx';		
		
		// GPX file database 
		if(file_exists ( PLUGIN_ROOT . '/php/gpx_database.php')){
			eddi_gpx_database($post_id, $description, $startCoords, $fName.'.gpx' );
		}
		
		CloseWindow($ActualFileName);
	}	
}


function ShowPopUp($PopupText)
{
	?>
	<script language="javascript" type="text/javascript">	
		alert ("<?php echo $PopupText; ?>");
	</script>
	<?php
}

function CloseWindow($ItemValue)
{
	?>
	<script language="javascript" type="text/javascript">	
		FileBrowserDialog.mySubmit('<?php echo $ItemValue; ?>'); //
	</script>
	<?php
}
function makeIconListBox(){
	global $wpdb;
	$upload_d = wp_upload_dir();
	$iconsdir= PLUGIN_ROOT . "/img/gmapIcons/" ; 
	$iconsurl= plugins_url( GPX_GM_PLUGIN ) . "/img/gmapIcons/" ;
	$iconDirs = Array();
	$iconUrls = Array();
 	if (is_dir($iconsdir)) {
		if ($dh = opendir($iconsdir)) {
			?><select class="ed_select" id="iconSelectBoxID" size="1" onchange="listChange(this);"><?php 
			$first = true; $select = ' selected="selected"';
			while (($subdir = readdir($dh)) !== false) {
				if (is_dir($iconsdir.$subdir) &&  $subdir != "." && $subdir != ".."){
					array_push($iconDirs, $subdir);
				}
			}
			closedir($dh);
			sort($iconDirs);
			foreach ($iconDirs as $entry => $subdir) {
					?>
					<option<?php if($first) {echo $select; $first=false;}?> ><?php echo $subdir;?></option>
					<?php		
 					if ($dhsub = opendir($iconsdir.$subdir)) {
						$iconUrls[$subdir] = Array();
						while (($file = readdir($dhsub)) !== false) {
							if(!is_dir($iconsdir.$subdir.'/'.$file)){
 								$iconUrls[$subdir][] = $iconsurl.$subdir.'/'.$file;
							}
						}							
						closedir($dhsub);
					}
			}

			?></select><?php 
			foreach ($iconUrls as $subdir => $entry) {
				?>
				<div class="iconList" id="<?php echo $subdir ?>">
				<?php		
			
				foreach ($entry as $icon => $value) {
					$query = explode(".",  $value);
					$query = $query[count($query)-2];
					$query = explode("/",  $query);
					$title = $query[count($query)-1];
					?>
					<div class="iconItem" href="#" onclick="iconSelect(this);" title="<?php echo $title ?>" alt="<?php echo $title ?>"><img src="<?php echo $value ?>" title="<?php echo $title ?>" alt="<?php echo $title ?>" /></div>
					<?php		
				} 
				?>
				</div>
				<?php		
			}
		}
	} 
}
?>

