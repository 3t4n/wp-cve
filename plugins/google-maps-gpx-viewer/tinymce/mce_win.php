<?php
/*
mce_win.php, V 1.08, altm, 20.09.2013
Author: ATLSoft, Bernd Altmeier
Author URI: http://www.atlsoft.de
Google Map V3 init Multimap support
released under GNU General Public License
*/

// check for rights
if ( !defined('ABSPATH') )
    die('You are not allowed to call this page directly.');
	
global $wpdb;

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
	<title>ATLsoft - Google Maps</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/jquery/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-content/plugins/<?php echo GPX_GM_PLUGIN; ?>/tinymce/tinymce.js"></script>
	<script type="text/javascript">
		
		function sendButtonClick() { 
			return insertGMap();
		}
		

		function toggleMarkerTab(tab, parent){
			jQuery('#marker_tab').remove();
			if(tab.checked){
				jQuery(parent).after('<li id="marker_tab"><span><a href="javascript:mcTabs.displayTab(\'marker_tab\',\'marker_panel\');" onmousedown="return false;"><?php _e('Marker', GPX_GM_PLUGIN); ?></a></span></li>');
			    jQuery('map_tab').focus();
			}
		}
		
		function handleFiles(cmd) {
			tinyMCE.activeEditor.execCommand('mceGmapGpxUpload', cmd, this);
		}	
		tinyMCEPopup.executeOnLoad('bodyloadGMap();');// ie hack onload="" 
		
	</script>	

	<base target="_self" />
</head>
<body id="link" style="display: none">

<!-- <p><?php echo $pPath;?></p> -->
	<form name="GmapGpx" id="GmapGpx" action="" onsubmit="sendButtonClick();"><input type="hidden" name="trackMapFile" id="trackMapFile" value="1" />

	<div class="tabs">
		<ul>
			<li id="map_tab" class="current"><span><a href="javascript:mcTabs.displayTab('map_tab','map_panel');" onmousedown="return false;"><?php _e( 'Map', GPX_GM_PLUGIN ) ?></a></span></li>
			<li id="file_tab"><span><a href="javascript:mcTabs.displayTab('file_tab','file_panel');" onmousedown="return false;"><?php _e('Track', GPX_GM_PLUGIN); ?></a></span></li>
			<li id="style_tab"><span><a href="javascript:mcTabs.displayTab('style_tab','style_panel');" onmousedown="return false;"><?php _e( 'Style', GPX_GM_PLUGIN ) ?></a></span></li>
			<li id="address_tab"><span><a href="javascript:mcTabs.displayTab('address_tab','address_panel');" onmousedown="return false;"><?php _e( 'Address', GPX_GM_PLUGIN ) ?></a></span></li>
			<li id="position_tab"><span><a href="javascript:mcTabs.displayTab('position_tab','position_panel');" onmousedown="return false;"><?php _e('Position', GPX_GM_PLUGIN); ?></a></span></li>
			<li id="fusion_tab"><span><a href="javascript:mcTabs.displayTab('fusion_tab','fusion_panel');" onmousedown="return false;"><?php _e('Fusion', GPX_GM_PLUGIN); ?></a></span></li>
		</ul>
	</div>
	
	<div class="panel_wrapper">
		
		<!-- map panel -->
		<div id="map_panel" class="panel current">
		<table border="0" cellpadding="4" cellspacing="0" style="width:100%">
         <tr>
            <td nowrap="nowrap"><?php _e("Default maptype", GPX_GM_PLUGIN); ?>: "<?php echo get_option('gmap_v3_gpx_defMaptype'); ?>"</td>
            <td style="text-align:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></td>
          </tr>
          <tr>
 			<td><select id="maptypetag" name="maptypetag" style="width: 200px">
				<option value=""><?php _e("select a maptype", GPX_GM_PLUGIN); ?></option>
				<?php
				$maptypes = get_option('gmap_v3_gpx_maptypes');
				if (is_array($maptypes)){
					foreach($maptypes as $map => $obj) {
						$copy = addslashes($obj[2]);
						echo '<option value="' . $obj[0] . '" >' . $obj[0].' - ' . $copy . '</option>'."\n";
					}
				}
				?>
            </select>
			</td>         
            <td><?php _e("Map zoom level", GPX_GM_PLUGIN); ?> <select  id="mapZoom" name="mapZoom"  size="1">
      <option></option>
      <option>0</option>
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
      <option>6</option>
      <option>7</option>
      <option>8</option>
      <option>9</option>
      <option>10</option>
      <option>11</option>
      <option>12</option>
      <option>13</option>
      <option>14</option>
      <option>15</option>
      <option>16</option>
      <option>17</option>
      <option>18</option>
      <option>19</option>
      <option>20</option>
    </select></td>		
		</tr>
        <tr>
            <td colspan="2" style="border: 1px solid grey;">
			<?php _e("Panoramio Image Layer", GPX_GM_PLUGIN); ?> <input type="checkbox" id="mapPano" /> <?php _e("User Id or Tag", GPX_GM_PLUGIN); ?> <input type="text" id="mapPanoTag" /><hr />
			<?php _e("Bike Layer", GPX_GM_PLUGIN); ?> <input type="checkbox" id="mapBike" /> 
			<?php _e("Traffic Layer", GPX_GM_PLUGIN); ?> <input type="checkbox" id="mapTraffic" /> -> <?php _e("Google maps only", GPX_GM_PLUGIN); ?>
			</td>
          </tr>		 
		  </table>
		</div>
		<!-- map panel -->	
		
		<!-- style panel -->
		<div id="style_panel" class="panel">
		<br />
		<table border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td nowrap="nowrap"><?php _e("Map width", GPX_GM_PLUGIN); ?></td>
            <td><input type="text" size="5" id="mapWidth" name="mapWidth" value="auto" /> <?php _e("height", GPX_GM_PLUGIN); ?> 
			     <input type="text" size="5" id="mapHeight" name="mapHeight" value="400" /> <?php _e("pixel", GPX_GM_PLUGIN); ?>
            <div style="float:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></div>
			</td>
        </tr>
        <tr>
            <td nowrap="nowrap" valign="top"><?php _e("float", GPX_GM_PLUGIN); ?></td>
            <td>
				<label><select id="mapFloat" name="mapFloat">
					<option value=""><?php _e("no float", GPX_GM_PLUGIN); ?></option>
					<option value="left"><?php _e("left", GPX_GM_PLUGIN); ?></option>
					<option value="right"><?php _e("right", GPX_GM_PLUGIN); ?></option>
				</select></label>
			</td>
        </tr>
        <tr>
            <td nowrap="nowrap"><?php _e("Border size", GPX_GM_PLUGIN); ?>: </td>
            <td><input type="text" size="5" id="borderSize" value="1" /> <?php _e("pixel", GPX_GM_PLUGIN); ?>, 
			 <?php _e("style", GPX_GM_PLUGIN); ?>: <input type="text" id="borderStyle" size="5" value="solid" />, 
			<?php _e("Color", GPX_GM_PLUGIN); ?> <input type="text" size="5" id="borderColor" name="borderColor" value="black" /></td>
        </tr>
        <tr>
            <td nowrap="nowrap"><?php _e("Margin top", GPX_GM_PLUGIN); ?></td>
            <td>
			<input type="text" size="3" id="marginTop" value="20" /> <?php _e("right", GPX_GM_PLUGIN); ?> 
			<input type="text" size="3" id="marginRight" value="0" /> <?php _e("bottom", GPX_GM_PLUGIN); ?> 
			<input type="text" size="3" id="marginBottom" value="20" /> <?php _e("left", GPX_GM_PLUGIN); ?> 
			<input type="text" size="3" id="marginLeft" value="0" /> <?php _e("pixel", GPX_GM_PLUGIN); ?></td>
        </tr>
        </table>
		</div>
		<!-- end style panel -->
		
		<!-- address panel -->
		<div id="address_panel" class="panel">
		<table border="0" cellpadding="4" cellspacing="0" style="width:100%">
         <tr>
            <td nowrap="nowrap"><label ><?php _e("Insert address or geocode here...", GPX_GM_PLUGIN); ?></label></td>
            <td style="text-align:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></td>
          </tr>
          <tr>
            <td colspan="2"><input type="text" style="width:270px;" id="mapAddress" name="mapAddress" /></td>
          </tr>
          <tr>
            <td colspan="2"><?php _e("Marker", GPX_GM_PLUGIN); ?> <input type="checkbox" class="mapMarker" id="mapMarkerAdr" onclick="toggleMarkerTab(this, '#address_tab');" /></td>
          </tr>
		  </table>
		</div>
		<!-- address panel -->
		
		<!-- position panel -->
		<div id="position_panel" class="panel">
		<br />
		<table border="0" cellpadding="4" cellspacing="0" style="width:100%">
          <tr>
            <td><?php _e("Map position", GPX_GM_PLUGIN); ?></td>
            <td style="text-align:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></td>
         </tr>
        <tr>
            <td><?php _e("Latitude", GPX_GM_PLUGIN); ?></td>
			<td><input type="text" id="mapLat" name="mapLat" size="20" /></td>
        </tr>
        <tr>
            <td><?php _e("Longitude", GPX_GM_PLUGIN); ?></td>
			<td><input type="text" id="mapLon" name="mapLon" size="20" /></td>
        </tr>
        <tr>
            <td><?php _e("Marker", GPX_GM_PLUGIN); ?></td>
			<td><input type="checkbox" class="mapMarker" id="mapMarkerPos" onclick="toggleMarkerTab(this, '#position_tab');"</td>
         </tr>
         </table>
		</div>
		<!-- position panel -->
		
		<!-- marker panel -->
		<div id="marker_panel" class="panel">
		<table border="0" cellpadding="4" cellspacing="0" style="width:100%">
          <tr>
            <td><?php _e("Marker Infowindow", GPX_GM_PLUGIN); ?></td>
            <td style="text-align:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></td>
          </tr>
          <tr>
            <td><?php _e("Content", GPX_GM_PLUGIN); ?></td>
            <td><textarea style="width:200px; height:40px;" id="mapInfoContent" name="mapInfoContent"></textarea></td>
          </tr>
          <tr>
            <td><?php _e("Icon URL", GPX_GM_PLUGIN); ?> </td>
            <td><input type="text" style="width:200px;"  id="mapMarkerURL" name="mapMarkerURL" /></td>
          </tr>
          <tr>
             <td><?php _e("or", GPX_GM_PLUGIN); ?> </td>
            <td><input  style="margin:0px 0 0 0px; width:120px; height:25px;" class="updateButton" type="button" name="uploadfile" id="uploadfile_btn" value="<?php _e("upload", GPX_GM_PLUGIN); ?>"   onclick="handleFiles('mapMarkerURL'); return false;" /></td>
        </tr>
		</table>		
		</div>
		<!-- marker panel -->

		<!-- Track panel -->
		<div id="file_panel" class="panel">
		<table border="0" cellpadding="4" cellspacing="0" style="width:100%">
         <tr>
            <td><label><?php _e("GPX or KML file", GPX_GM_PLUGIN); ?></label></td>
            <td style="text-align:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/google-maps-gpx-viewer/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></td>
       </tr>
        <tr>
			<td colspan="2"><input type="text" style="width:400px;" name="mapFile" id="mapFile" value="" /></td>
         </tr>
        <tr>
            <td style="text-align:center;">
			<input  style="margin:10px 0 0 0px; width:160px; height:25px;"  class="updateButton" type="button" name="uploadfile" id="uploadfile_btn" value="<?php _e("upload", GPX_GM_PLUGIN); ?>"   onclick="handleFiles('mapFile'); return false;" />
			</td>
 			<td style="text-align:center;">
			<input  style="margin:10px 0 0 0px; width:160px; height:25px;"  class="updateButton" type="button" name="createfile" id="createfile" value="<?php _e("Track Editor", GPX_GM_PLUGIN); ?>"   onclick="handleFiles('drawFile'); return false;" />
			</td>
          </tr>
          <tr>
            <td  colspan="2" style="text-align:center;">
			<?php _e("Elevation Profile", GPX_GM_PLUGIN); ?> <input type="checkbox" id="mapElevation" <?php if(get_option('gmap_v3_gpx_elevationProfile') == 1)  echo 'checked value="1"' ?> /> 
			<input type="hidden"  id="mapelevationProfile" value="<?php echo get_option('gmap_v3_gpx_elevationProfile')?>" /> 
			<?php _e("Download Button", GPX_GM_PLUGIN); ?> 
			<input type="checkbox" id="mapDownload"  <?php if(get_option('gmap_v3_gpx_downloadLink') == 1)  echo 'checked value="1"' ?> />
			<input type="hidden"  id="mapdownloadLink" value="<?php echo get_option('gmap_v3_gpx_downloadLink')?>" />
			<?php _e("Marker Manager", GPX_GM_PLUGIN); ?> <input type="checkbox" id="mapMarkerToggle" /> 
			</td>
         </tr>
       </table>
		</div>
		<!-- Track panel -->

		<!-- fusion panel -->
		<div id="fusion_panel" class="panel">
		<table border="0" cellpadding="4" cellspacing="0" style="width:100%">
         <tr>
            <td><label><?php _e("Fusion table", GPX_GM_PLUGIN); ?></label></td>
             <td style="text-align:right;" nowrap="nowrap"><a href="http://www.atlsoft.de/programmierung/fusion-table/" target="_blank"><?php _e("Help", GPX_GM_PLUGIN); ?></a></td>
           </tr>
          <tr>
            <td nowrap="nowrap"><?php _e("table ID", GPX_GM_PLUGIN); ?></td>
            <td><input type="text"  style="width:270px;" id="FusionTab" name="FusionTab" value="" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><?php _e("select", GPX_GM_PLUGIN); ?></td>
            <td><input type="text"  style="width:270px;" id="FusionSelect" name="FusionSelect" value="" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><?php _e("where", GPX_GM_PLUGIN); ?></td>
            <td><input type="text"  style="width:270px;" id="FusionWhere" name="FusionWhere" value="" /></td>
          </tr>
        </table>
		</div>
		<!-- fusion panel -->

	</div> 

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", GPX_GM_PLUGIN); ?>" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e("Insert", GPX_GM_PLUGIN); ?>"  />
		</div>
	</div>
</form>
</body>
</html>
