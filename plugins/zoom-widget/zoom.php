<?php
/*
Plugin Name: Z
Plugin URI: http://web-dorado.com/products/zoom-widget-wordpress.html
Version: 1.2.8
Author: WebDorado
Author URI: https://web-dorado.com
Author License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
$zoom_function__once=1;
function Spider_Zoom_shotrcode($atts) {
	
     return front_end_Spider_Zoom();
}
add_shortcode('Web-Dorado_Zoom', 'Spider_Zoom_shotrcode');



function front_end_Spider_Zoom(){
	
	
$tag		=get_option('Spider_Zoom_tag');
$class		=get_option('Spider_Zoom_class');
$id			=get_option('Spider_Zoom_id');
$max		=get_option('Spider_Zoom_max');
$min		=get_option('Spider_Zoom_min');
$imggroup	=get_option('Spider_Zoom_imggroup');
$imgsize	=get_option('Spider_Zoom_imgsize');

$a				=explode("***", $imggroup);
$but_type		=$a[0];
$but_pos		=$a[1];
$img_group_id	=$a[2];
$text['plus']	=$a[3];
$text['100']	=$a[4];
$text['minus']	=$a[5];

$func['plus']="changeFontSize_my(2); return false;";
$func['100']="revertStyles_my(2); return false;";
$func['minus']="changeFontSize_my(-2); return false;";

$size['plus']	=$a[5];

switch ($imgsize) {
    case "bms":
		$size['plus']= 60;
        $size['100']=50;
       	$size['minus']=40;
        break;
    case "same":
		$size['plus']=50;
        $size['100']=50;
       	$size['minus']=50;
        break;
    default :
        $b=explode('***',$imgsize);
        $size['plus']=$b[1];
        $size['100']=$b[2];
        $size['minus']=$b[3];
        break;
}
global $zoom_function__once;
if($zoom_function__once){
	$zoom_function__once=0;
$zoom_front_end="
<script type=\"text/javascript\"> 
var tag='".$tag."';
var class_='".$class."' ;
var id_='".$id."' ;
var max_=parseInt('".$max."') ;
var min_=parseInt('".$min."') ;
var all_elems=new Array();
var elements_id=null;
var al_id=false;
var al_class=false;
var allow_ids=new Array();
var allow_classes=new Array();

x=id_;
if(x)
	while(x.indexOf('#')!=-1)
	{
	val=x.substr(0,x.indexOf('#'));	   
	allow_ids.push(val);
	x=x.substr(x.indexOf('#')+1);
	}
else
	allow_ids[0]=false;

x=class_;
if(x)
	while(x.indexOf('#')!=-1)
	{
	val=x.substr(0,x.indexOf('#'));	   
	allow_classes.push(val);
	x=x.substr(x.indexOf('#')+1);
	}
else
	allow_classes[0]=false;

function getTextNodesIn(node, includeWhitespaceNodes, fontSize)
{
    var textNodes = [], whitespace = /^\s*$/;
    function getTextNodes(node) 
    {
    		    //alert(node.parentNode);

        if (node.nodeType == 3) 
	{
		    if (includeWhitespaceNodes || !whitespace.test(node.nodeValue)) 
		    {
		    parent_=node.parentNode;
			  if(parent_.nodeName==\"FONT\" && parent_.getAttribute(\"my\")==\"my\" )
			  {if(((tag.indexOf('#'+parent_.parentNode.tagName)!=-1) || (tag.indexOf(\"all\")!=-1)) && (parent_.parentNode.tagName!=\"SCRIPT\"))
				{
					x=fontSize+\"%\";
					parent_.style.fontSize=x;
				}
			  }
						  
			  else
			  {	
				if(((tag.indexOf('#'+parent_.tagName)!=-1) || (tag.indexOf(\"all\")!=-1)) && (parent_.tagName!=\"SCRIPT\"))
				 {

					var newnode=document.createElement('font');
					newnode.setAttribute('style','font-size:'+fontSize+'%');
					newnode.setAttribute('my','my');
					
				    var text = document.createTextNode(node.nodeValue);
				    
				    newnode.appendChild(text);
				    parent_.replaceChild(newnode,node);
					textNodes.push(node);
				 }   
			   }
		  
		    }
        } 
	else 
	{
            for (var i = 0, len = node.childNodes.length; i < len; ++i) 
	    {
                getTextNodes(node.childNodes[i]);
            }
        }
    }

    getTextNodes(node);
    return textNodes;
}

var prefsLoaded_my = false;
var defaultFontSize_my =100;
var currentFontSize_my = defaultFontSize_my;
    //alert(currentFontSize_my);

function changeFontSize_my(sizeDifference_my){
    currentFontSize_my = parseInt(currentFontSize_my) + parseInt(sizeDifference_my * 5);
    if(currentFontSize_my > max_){
        currentFontSize_my = max_;
    }else if(currentFontSize_my < min_){
        currentFontSize_my = min_;
    }
setFontSize_my(currentFontSize_my);
};

function setFontSize_my(fontSize){
for(i=0; i<all_elems.length; i++)
    	getTextNodesIn(all_elems[i],false, currentFontSize_my);
};


function revertStyles_my()
{

    currentFontSize_my = defaultFontSize_my;
    setFontSize_my(0);

}


function createCookie_my(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = \"; expires=\"+date.toGMTString();
  }
  else expires = \"\";
  document.cookie = name+\"=\"+value+expires+\"; path=/\";
};

function readCookie_my(name) {
  var nameEQ = name + \"=\";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
};


	var getElementsByClassName = function (className, tag, elm){
	
	if (document.getElementsByClassName) {
		getElementsByClassName = function (className, tag, elm) {
			elm = elm || document;
			var elements = elm.getElementsByClassName(className),
				nodeName = (tag)? new RegExp(\"\\b\" + tag + \"\\b\", \"i\") : null,
				returnElements = [],
				current;
			for(var i=0, il=elements.length; i<il; i+=1){
				current = elements[i];
				if(!nodeName || nodeName.test(current.nodeName)) {
					returnElements.push(current);
				}
			}
			return returnElements;
		};
	}
	else if (document.evaluate) {
		getElementsByClassName = function (className, tag, elm) {
			tag = tag || \"*\";
			elm = elm || document;
			var classes = className.split(\" \"),
				classesToCheck = \"\",
				xhtmlNamespace = \"http://www.w3.org/1999/xhtml\",
				namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace)? xhtmlNamespace : null,
				returnElements = [],
				elements,
				node;
			for(var j=0, jl=classes.length; j<jl; j+=1){
				classesToCheck += \"[contains(concat(' ', @class, ' '), ' \" + classes[j] + \" ')]\";
			}
			try	{
				elements = document.evaluate(\".//\" + tag + classesToCheck, elm, namespaceResolver, 0, null);
			}
			catch (e) {
				elements = document.evaluate(\".//\" + tag + classesToCheck, elm, null, 0, null);
			}
			while ((node = elements.iterateNext())) {
				returnElements.push(node);
			}
			return returnElements;
		};
	}
	else {
		getElementsByClassName = function (className, tag, elm) {
			tag = tag || \"*\";
			elm = elm || document;
			var classes = className.split(\" \"),
				classesToCheck = [],
				elements = (tag === \"*\" && elm.all)? elm.all : elm.getElementsByTagName(tag),
				current,
				returnElements = [],
				match;
			for(var k=0, kl=classes.length; k<kl; k+=1){
				classesToCheck.push(new RegExp(\"(^|\\s)\" + classes[k] + \"(\\s|$)\"));
			}
			for(var l=0, ll=elements.length; l<ll; l+=1){
				current = elements[l];
				match = false;
				for(var m=0, ml=classesToCheck.length; m<ml; m+=1){
					match = classesToCheck[m].test(current.className);
					if (!match) {
						break;
					}
				}
				if (match) {
					returnElements.push(current);
				}
			}
			return returnElements;
		};
	}
	return getElementsByClassName(className, tag, elm);
};

function zoomOnload()
{
	if(allow_ids[0])
	for(i=0; i<allow_ids.length; i++)
	{
		if(allow_ids[i])
		{
			if(document.getElementById(allow_ids[i]))
			{
				all_elems.push(document.getElementById(allow_ids[i]));
			}
		}
	}
	
	else

	{
			al_id=true;
	}
	
	if(allow_classes[0])
	for(i=0; i<allow_classes.length; i++)
	{
		if(allow_classes[i])
		{
			if(getElementsByClassName(allow_classes[i])[0])
			{
				var elements_class=new Array();
				elements_class=getElementsByClassName(allow_classes[i]);
				for(x=0; x<elements_class.length; x++)
					all_elems.push(elements_class[x]);
			}
		}


	}
	
	else

	{
			al_class=true;
	}
	
if(al_id && al_class)
all_elems[0]=document.body;
setUserOptions_my();

}


function setUserOptions_my(){

    if(!prefsLoaded_my)
    {
        cookie = readCookie_my(\"fontSize\");
        currentFontSize_my = cookie ? cookie : defaultFontSize_my;
	for(i=0; i<all_elems.length; i++)
	{

		if(all_elems[i])
			setFontSize_my(all_elems[i], false, currentFontSize_my);
	}
        prefsLoaded_my = true;
    }
}

function saveSettings_my()
{
  createCookie_my(\"fontSize\", currentFontSize_my, 365);
}

function zoomAddToOnload()
{ 
	if(zoomOldFunctionOnLoad){ zoomOldFunctionOnLoad(); }
	zoomOnload();
}

function zoomAddToOnUnload()
{ 
	if(zoomOldFunctionOnUnload){ zoomOldFunctionOnUnload(); }
	saveSettings_my();
}

function zoomLoadBody()
{
	zoomOldFunctionOnLoad = window.onload;
	zoomOldFunctionOnUnload = window.onunload;
	window.onload = zoomAddToOnload;
	window.onunload = zoomAddToOnUnload;
}

var zoomOldFunctionOnLoad = null;
var zoomOldFunctionOnUnload = null;

zoomLoadBody();
</script>
";

}

if($but_type)
for($i=6; $i<=8; $i++)
{
	 if($a[$i]=='plus') $xxxxx='+'; if($a[$i]=='minus') $xxxxx='-';  if($a[$i]=='100') $xxxxx='100%' ;
	$zoom_front_end.="<img src=\"".plugins_url('elements/',__FILE__)."images/".$img_group_id."/".$a[$i].".png\" alt=\"".$xxxxx."\" id=\"".$a[$i]."\" style=\"display:";
	if($but_pos) $zoom_front_end.='inline'; else $zoom_front_end.="block";
	$zoom_front_end.=";cursor:pointer; height:".$size[$a[$i]]."px\"  onclick=\"".$func[$a[$i]]."\"/>";
	

}
else
for($i=6; $i<=8; $i++)
{
$zoom_front_end.='<span id="'.  $a[$i].'" style="display:';
if($but_pos) $zoom_front_end.="inline"; else $zoom_front_end.="block";
$zoom_front_end.=';cursor:pointer; font-size:'.$size[$a[$i]].'px; line-height:normal" onclick="'.$func[$a[$i]].'">'.$text[$a[$i]].'</span>';

}
 
	
	return $zoom_front_end;
	}












































//// add editor new mce button
add_filter('mce_external_plugins', "Spider_Zoom_register");
add_filter('mce_buttons', 'Spider_Zoom_add_button', 0);

function Spider_Zoom_add_button($buttons)
{
    array_push($buttons, "Spider_Zoom_mce");
    return $buttons;
}
 /// function for registr new button
function Spider_Zoom_register($plugin_array)
{
    $url = plugins_url( 'js/editor_plugin.js' , __FILE__ ); 
    $plugin_array["Spider_Zoom_mce"] = $url;
    return $plugin_array;
}



function add_button_style_Spider_Zoom()
{
echo '<style type="text/css">
.wp_themeSkin span.mce_Spider_Zoom_mce {background:url('.plugins_url( 'images/Spider_ZoomLogo.png' , __FILE__ ).') no-repeat !important;}
.wp_themeSkin .mceButtonEnabled:hover span.mce_Spider_Zoom_mce,.wp_themeSkin .mceButtonActive span.mce_Spider_Zoom_mce
{background:url('.plugins_url( 'images/Spider_ZoomLogoHover.png' , __FILE__ ).') no-repeat !important;}
</style>';
}

add_action('admin_head', 'add_button_style_Spider_Zoom');







add_action('admin_menu', 'Spider_Zoom_menu');
function Spider_Zoom_menu(){
    $page =add_menu_page('Theme page title', 'Zoom', 'manage_options', 'Spider_Zoom', 'Spider_Zoom')  ;
	
	
	
	 add_action('admin_print_styles-' . $page, 'Spider_Zoom_admin_styles');
	 

}


function Spider_Zoom_admin_styles()
{
	wp_enqueue_script( 'jquery-1.7.1',plugins_url("elements/jquery-1.7.1.js",__FILE__));
wp_enqueue_script( 'jquery.ui.core',plugins_url("elements/jquery.ui.core.js",__FILE__));
wp_enqueue_script( 'jquery.ui.widget',plugins_url("elements/jquery.ui.widget.js",__FILE__));
wp_enqueue_script( 'jquery.ui.mouse',plugins_url("elements/jquery.ui.mouse.js",__FILE__));
wp_enqueue_script( 'jquery.ui.slider',plugins_url("elements/jquery.ui.slider.js",__FILE__));
wp_enqueue_script( 'jquery.ui.sortable',plugins_url("elements/jquery.ui.sortable.js",__FILE__));
wp_enqueue_style( 'jquery-ui',plugins_url("elements/jquery-ui.css",__FILE__));
wp_enqueue_style( 'parseTheme',plugins_url("elements/parseTheme.css",__FILE__));
	
	
}




function registrmy_jquer_scripts()
{
wp_register_script( 'jquery-1.7.1',plugins_url("elements/jquery-1.7.1.js",__FILE__));
wp_register_script( 'jquery.ui.core',plugins_url("elements/jquery.ui.core.js",__FILE__));
wp_register_script( 'jquery.ui.widget',plugins_url("elements/jquery.ui.widget.js",__FILE__));
wp_register_script( 'jquery.ui.mouse',plugins_url("elements/jquery.ui.mouse.js",__FILE__));
wp_register_script( 'jquery.ui.slider',plugins_url("elements/jquery.ui.slider.js",__FILE__));
wp_register_script( 'jquery.ui.sortable',plugins_url("elements/jquery.ui.sortable.js",__FILE__));
wp_register_script( 'jquery-ui',plugins_url("elements/jquery-ui.css",__FILE__));
wp_register_script( 'parseTheme',plugins_url("elements/parseTheme.css",__FILE__));
	
}


add_action( 'admin_init', 'registrmy_jquer_scripts' );

function Spider_Zoom()
{

	require_once("elements/class.php");
	require_once("elements/id.php");
	require_once("elements/imggroup.php");
	require_once("elements/imgsize.php");
	require_once("elements/max.php");
	require_once("elements/min.php");
	require_once("elements/plus.php");
	require_once("elements/tag.php");
	require_once("elements/plus.php");
	?>
   
    <h1>Zoom</h1>
   
    <form id="adminform" method="post" name="adminform" action="admin.php?page=Spider_Zoom&task=save">
    <table><tr><td width="600px" valign="top">
    <table  cellspacing="1" style="height: inherit; table-layout: fixed ">
<tbody><tr>
<td width="50%" ><span class="editlinktip"><label id="paramstag-lbl" for="paramstag" class="hasTip">Tags:</label></span></td>
<td width="250px"><?php Spider_tag(get_option('Spider_Zoom_tag')); ?></td>
</tr>

<tr>
<td width="50%" ><span class="editlinktip"><label id="paramsclass-lbl" for="paramsclass" class="hasTip">Class:</label></span></td>
<td><?php Spider_class(get_option('Spider_Zoom_class')); ?></td>
</tr>

<tr>
<td width="50%" ><span class="editlinktip"><label id="paramsid-lbl" for="paramsid" class="hasTip">Id:</label></span></td>
<td><?php Spider_id(get_option('Spider_Zoom_id')); ?></td>
</tr>

<tr>
<td width="50%" ><span class="editlinktip"><label id="paramsmax-lbl" for="paramsmax">Maximum size (%):</label></span></td>
<td><?php Spider_max(get_option('Spider_Zoom_max')); ?></td>
</tr>
<tr>
<td width="50%" ><span class="editlinktip"><label id="paramsmin-lbl" for="paramsmin">Minimum size (%):</label></span></td>
<td><?php Spider_min(get_option('Spider_Zoom_min')); ?></td>
</tr>

<tr>
<td width="50%" ><span class="editlinktip"><label id="paramsimggroup-lbl" for="paramsimggroup">Buttons:</label></span></td>
<td><?php Spider_imggroup(get_option('Spider_Zoom_imggroup')); ?></td>
</tr>
<tr>
<td width="50%" ><span class="editlinktip"><label id="paramsimgsize-lbl" for="paramsimgsize">Buttons size:</label></span></td>
<td><?php Spider_imgsize(get_option('Spider_Zoom_imgsize')); ?></td>
</tr>
</tbody>
</table>
</td>
<td>		<p>
			<div style="text-align:justify;font-size:14px;width:210px; border:1px solid #999999; padding:10px">
				<a href="http://web-dorado.com/files/fromZoom.php" target="_blank" style="color:red; text-decoration:none;">
					<img src="<?php echo plugins_url('images/',__FILE__) ?>header_wd.png" border="0" alt="www.web-dorado.com" width="215"><br />
				The options are disabled in the free version.<br /><br />
				If you want to select the tags, classes and IDs to zoom, customize the maximum and minimum relative resizing percentage or select the zoom buttons design from 42 available themes, that's not a problem either.<br /><br />
				Get the full version here.<br /><br />
				<img src="<?php echo plugins_url('images/',__FILE__) ?>zoom-wp-comm.png" />
				</a>
			</div>
		</p>
		</td></tr></table>
<br />
<script>

function submitbutton() 
{
	tox='';
	for(k=0; k<document.getElementById('tr_arr').childNodes.length; k++)
		if(document.getElementById('tr_arr').childNodes[k].id)
			tox+='***'+document.getElementById('tr_arr').childNodes[k].id;
			
	but_type=0;
	but_pos=0;
	
	if(document.getElementById('but_type_img').checked)
		but_type=1;
	if(document.getElementById('but_pos_hor').checked)
		but_pos=1;
		var gago;
	document.getElementById('Spider_Zoom_imggroup').value=but_type+'***'+but_pos+'***'+document.getElementById('img_group_id').value+'***'+document.getElementById('change_plus_text').value+'***'+document.getElementById('change_100_text').value+'***'+document.getElementById('change_minus_text').value+tox;
	document.getElementById('adminform').submit();
	
}
</script>
<input type="button" onclick="submitbutton()" disabled value="Save Changes" class="button-primary" />
    <?php
	//Spider_plus();
	
		
	 ?>     
    </form>
    <?php
	
}



require_once("zoom_widget.php");










function Zoom_activate()
{
	add_option( 'Spider_Zoom_title', '', '', 'yes' );
	add_option( 'Spider_Zoom_tag','##P#A#H1#H2#H3#H4#H5#H6#SPAN#DIV#TD#LI#BUTTON#B#I#FONT#LABEL#STRONG#EM', '', 'yes' );
	add_option( 'Spider_Zoom_class', '', '', 'yes' );
	add_option( 'Spider_Zoom_id', '', '', 'yes' );
	add_option( 'Spider_Zoom_max', '120', '', 'yes' );
	add_option( 'Spider_Zoom_min', '80', '', 'yes' );
	add_option( 'Spider_Zoom_imggroup', '1***1***1***+***100%***-***plus***100***minus', '', 'yes' );
	add_option( 'Spider_Zoom_imgsize', 'bms', '', 'yes' );	
}


register_activation_hook( __FILE__, 'Zoom_activate' );
function Zoom_deactivate(){
	delete_option('Spider_Zoom_title');
	delete_option('Spider_Zoom_tag');
	delete_option('Spider_Zoom_id');
	delete_option('Spider_Zoom_class');
	delete_option('Spider_Zoom_max');
	delete_option('Spider_Zoom_min');
	delete_option('Spider_Zoom_imggroup');
	delete_option('Spider_Zoom_imgsize');
	
	
	}

register_deactivation_hook( __FILE__, 'Zoom_deactivate' );