<?php

if ( !defined('ABSPATH') )
    die('You are not allowed to call this page directly.');

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 	die(__('You are not allowed to call this page directly.','bmo-expo')); }

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset')); //da per ajax als html zurück gegeben wird 

// Get WordPress scripts and styles
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-widget');
wp_enqueue_script('jquery-ui-position');
wp_enqueue_script('jquery-ui-accordion');

wp_enqueue_style('admin-bar');
wp_enqueue_style('media-views');
wp_enqueue_style('wp-admin');
wp_enqueue_style('buttons');

wp_register_style( 'bmo_admin_css', BMO_EXPO_URL.'/css/admin/bootstrap.css', array(), BMO_EXPO_VERSION ,'all');
wp_register_style( 'bmo_admin_style_css', BMO_EXPO_URL.'/css/admin/bmo_admin_style.css', array(), BMO_EXPO_VERSION ,'all');
wp_register_script( 'bmo_admin_js', BMO_EXPO_URL.'/js/admin/bootstrap.js', array(), BMO_EXPO_VERSION ,'all');
if (function_exists('wp_enqueue_script')) {
	wp_enqueue_style('bmo_admin_css');
	wp_enqueue_style('bmo_admin_style_css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('bmo_admin_js');
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>BMo Expo</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo site_url(); ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<?php wp_print_scripts();
	wp_print_styles(); ?>
	<style>
	html, body{
		background: none repeat scroll 0 0 #FFFFFF;/*#F1F1F1*/
	}
	body{
		padding:10px;
	}
	ul{
		list-style: none outside none;
		margin:0px;
		padding:0px
	}
	li{
		margin:0px;
		padding:0px;
	}
	p{
		 margin: 1em 0;
		padding:0px;
	}
	h4{
		margin:0px;
	}
	.alignleft {
	    float: left;
	}
	.clear {
	    clear: both;
		height:0px;
	}
	.form-table {
		font-size:0.94em;
		margin-top:0px;
	}
	.form-table td{
		padding-left:0px;
	}
	.form-table th {
		width:75%;
	}
	.tab-content{
		overflow:visible;
	}
	#gallerycontainer{
		width:100%;
		overflow:auto; 
		overflow-y:hidden; 
		background-color:#eeeeee;
		border:1px solid #DFDFDF;
		border-radius: 3px;
	}
	#gallerycontainer #galleryscroller{
		width:800px;
	}
	#gallerycontainer #galleryscroller div{
		margin:0px 10px;
	}
	#galleryoptions{
		background-color:#eeeeee;
		border:1px solid #DFDFDF;
		margin:10px 0px;
		border-radius: 3px;
	}
	#galleryoptions p{
		margin:0px;
	}
	#galleryoptions .galleryoptions_content p{
		margin:15px 10px 0px 10px;
		font-weight:700;
	}
	#galleryoptions .header{
		border-top-left-radius: 3px;
	    border-top-right-radius: 3px;
		background: linear-gradient(to top, #ECECEC, #F9F9F9) repeat scroll 0 0 #F1F1F1;
		border-bottom-color: #DFDFDF;
	 	box-shadow: 0 1px 0 #EEEEEE;
		border-bottom:1px solid #DFDFDF;
		padding:2px;
		
	}
	#galleryoptions .header span.arrows{
		border-top: 4px solid transparent;
		border-bottom: 4px solid transparent;
		border-left: 4px solid #999999;
		display: block;
		float: right;
		height: 0;
		width: 0;
		margin:7px 5px 0px 0px;
	}
	#galleryoptions .ui-accordion-header-active span.arrows{
		border-left: 4px solid transparent;
		border-right: 4px solid transparent;
		border-top: 4px solid #999999;
		display: block;
		float: right;
		height: 0;
		width: 0;
		margin:7px 5px 0px 0px;
	}
	
	
	</style>
	<script language="javascript" type="text/javascript" >
	(function($){
			$(document).ready(function(){
				$("body").on("load",function(){
					tinyMCEPopup.executeOnLoad('init();');
					tinyMCEPopup.resizeToInnerSize();
				});
				
				$("#insert").on("click",function(e){
					e.preventDefault();
					var gallerytag = $('.tab-content .active .gallerytag').val();
					var showtype = $(".type:checked").val();
					
					//remove designs
					$('.gallerytag').css('border','');
					$('#gallerycontainer').css('border','');
					
					//variables
					if(!gallerytag|| gallerytag== 0 ){
						$('.gallerytag').css('border','1px solid #f00000');
						return false;
					}
					if (!showtype){
						$('#gallerycontainer').css('border','1px solid #f00000');
						return false;
					}
					
					var tagtext ="";
					//generate tagtext depending on the type of picture selector
					if($(".tab-content .active").attr("id")=="tag"){
						if (gallerytag != "" )
							tagtext = showtype.replace("id=xxx", "tags="+gallerytag);	//set tags
					}else{//default is id
						gallerytag = parseInt(gallerytag);
						if (gallerytag != 0 )
							tagtext = showtype.replace("id=xxx", "id="+gallerytag);	//set id
					}
					
					
					
					//array of all parameter
					var _parameterFromTagText = parameterFromTagText(tagtext);
						
					//add options to array of all parameter
					var arr_options = $('#galleryoptions input, #galleryoptions select');
					$.each(arr_options,function(index,option){
						var key = $(option).attr('name').replace('options_bmo_expo[','').replace('][value]','');
						if(!(Object.prototype.hasOwnProperty.call(_parameterFromTagText[1], key))){
							if($(option).is(':checkbox')){
								if($(option).prop('checked')){
									_parameterFromTagText[1][key] = "1";
								}else{
									_parameterFromTagText[1][key] = "0";
								}
							}else{
								_parameterFromTagText[1][key] = $(option).val();
							}
						}
					});
					
					//build output
					var poutpout ="";
					$.each(_parameterFromTagText[1], function(p,val){
						poutpout += p+"="+val+" ";
					});
					tagtext = _parameterFromTagText[0]+" "+poutpout+_parameterFromTagText[2];
					
					if(window.tinyMCE) {
						//einfügen des codes, man könnte hier auch ein bild einfügen
						var tmce_ver=window.tinyMCE.majorVersion;
						var activeEditor = window.tinyMCE.activeEditor.id;

						if (tmce_ver>="4") {
							window.tinyMCE.execCommand('mceInsertContent', false, tagtext);
						} else {
							window.tinyMCE.execInstanceCommand(activeEditor, 'mceInsertContent', false, tagtext);
						}
						
						//Peforms a clean up of the current editor HTML.
						tinyMCEPopup.editor.execCommand('mceCleanup');//für shortcode erzeugng
						//Repaints the editor. Sometimes the browser has graphic glitches.
						tinyMCEPopup.editor.execCommand('mceRepaint');
						tinyMCEPopup.close();
					}
					return true;
				});
				
				
				//accordeon, das per default geschlossen ist
				$('#galleryoptions').accordion({
				     collapsible: true,
					 active: false,
					heightStyleType: "auto"
				});
				
				
				$("#gallerycontainer input.type").on('click',function(e){//siehe wplink.js unten
						var ajaxurl= tinyMCEPopup.getWindowArg('ajax_url','');//get the parameter - wp ajaxurl
						var type = $(e.currentTarget).val().match(/\[BMo_([^ ]*) ?/);
						var _parameterFromTagText = parameterFromTagText($(e.currentTarget).val());
						var typep ="";
						if(type) typep = type[1];
						var query = {
							action : 'BMoExpo_tinymce_options',
							type: typep,
							parameter: _parameterFromTagText[1]
						};
						lastXhr = $.getJSON(ajaxurl, query, function( anwser, status, xhr ) {//ajaxurl wird von wp definiert
							if ( xhr === lastXhr ) {
								$('#galleryoptions .galleryoptions_content').html(anwser[0].html);//output option html
								$('#galleryoptions').accordion( "refresh" );
								
								//entfernen einiger options, die über bild gesetzt werden
								$.each(anwser[0].parameter,function(key,val){
									if(key!='id')//da sonst auch width usw. weg gehen
										$("#galleryoptions [name*='"+key+"']").parents("tr").hide();
								});
							}
						});
				});
				
				function parameterFromTagText(tagtext){
					//array of all parameter
					var arrayOfParameter={}, begin="", end="";
					
					tagtext = tagtext.replace("]"," ]");
					$.each(tagtext.split(" "),function(index,part){
						if(part.indexOf("[") != -1) begin = part;
						else if(part.indexOf("]") != -1) end = part;
						else if(part.indexOf("=") != -1) {
							var ppart = part.split("="); 
							arrayOfParameter[ppart[0].toString()]=ppart[1];
						}
						
					});
					return [begin, arrayOfParameter, end];
				}
			});
	
	})(jQuery);
	</script>
    <base target="_self" />
</head>
<body style="display: none">
	<form name="BMoExpo" action="#">
		<ul class='nav nav-tabs' id='bmoTab' data-tabs='tabs'>
	    	<li class='active'><a id='tab_gallery' href='#gallery' data-toggle='tab'><?php _e("Gallery","bmo-expo") ?></a></li>
	    	<li class=''><a id='tab_tag' href='#tag' data-toggle='tab'><?php _e("Tag","bmo-expo") ?></a></li>
	    </ul>
		<div class='tab-content'>
			<div class='tab-pane active' id='gallery'>
		
				<table border="0" cellpadding="4" cellspacing="0" class="form-table" style="width:auto">
		         <tr>
		            <td nowrap="nowrap"><label for="gallerytag"><?php _e("1. Select a NextGen Gallery:","bmo-expo") ?></label></td>
		            <td><select class="gallerytag" name="gallerytag" style="width: 200px">
						<option value="0" selected="selected"><?php _e("Select a gallery", 'nggallery'); ?></option>
						<?php
					
							global $wpdb;  //todo evtl. auch lieber per ajax laden, aus sicherheit, wird zwar per include direkt reingeladen, aber naja, das global gefällt mir nicht ganz. Ansonsten, hab ich geprüft, sollte alles so passen von der sicherheit. Glaub gibt kaum ne bessere lösung, ohne ne template engin zu programmieren. die normalen wp tiny mce plugins arbeiten mit language php usw. Es gibt dort keine iframe lösung bei dyn. content, geht aber schwer zu erweitern, hab ich versucht
					
							$ngg_options = get_option ('ngg_options'); //NextGenGallery Options
							$gallerys    = $wpdb->get_results("SELECT gid, name FROM $wpdb->nggallery  ORDER BY gid");
				 	
							foreach($gallerys as $key => $value){
								echo '<option value="'.$value->gid.'">'.$value->gid.' - '.$value->name.'</option>';
							}
					
						?>
		                </select>
		            </td>
		          </tr>
				</table>
			</div>
			<div class='tab-pane' id='tag'>
		
				<table border="0" cellpadding="4" cellspacing="0" class="form-table" style="width:auto">
		         <tr>
		            <td nowrap="nowrap"><label for="gallerytag"><?php _e("1. Select images by NextGen Gallery Tags:","bmo-expo") ?></label></td>
		            <td><input class="gallerytag" name="gallerytag" style="width: 200px" value="" /> <span class="small"><?php _e("(separate tags by comma)","bmo-expo") ?></span>
		            </td>
		          </tr>
				</table>
			</div>
			
		</div>
		<p><?php _e("2. Show as:","bmo-expo") ?></p>
		<div id="gallerycontainer">
			<div id="galleryscroller">
				<div class="alignleft">
            		<h4>Scroll Gallery</h4>
					<ul>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollGallery id=xxx sG_thumbPosition=bottom sG_images=1]" /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollGallery_bottom.png" alt="bottom" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollGallery id=xxx sG_thumbPosition=top sG_images=1]"  /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollGallery_top.png" alt="top" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollGallery id=xxx sG_thumbPosition=left sG_images=1]"  /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollGallery_left.png" alt="left" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollGallery id=xxx sG_thumbPosition=right sG_images=1]"  /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollGallery_right.png" alt="right" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollGallery id=xxx sG_thumbPosition=none sG_images=1]"  /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollGallery_none.png" alt="none" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
					</ul>
				</div>
				<div class="alignleft">
					<h4>Scroll Lightbox Gallery</h4>
					<ul>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollLightboxGallery id=xxx slG_vertical=0]" /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollLightboxGallery_h.png" alt="horizontal" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
						<li class="alignleft"><p><input name="type" class="type" type="radio" value="[BMo_scrollLightboxGallery id=xxx slG_vertical=1]" /><img src="<?php echo BMO_EXPO_URL?>/css/admin/img/BMoIcons_scrollLightboxGallery_v.png" alt="vertical" width="80px" align="absmiddle" style="margin-right:5px"/></p><div class="clear">&nbsp;</div></li>
					</ul>
				</div>
				<div class="clear">&nbsp;</div>
	 		</div>
		</div>
		<div id="galleryoptions">
			<p class="header"><?php _e("3. Gallery options:","bmo-expo") ?><span class="arrows">&nbsp;</span></p>
			<div class="galleryoptions_content"></div>
		</div>
		<div style="float: right; margin-bottom:15px;">
			<input type="submit" id="insert" name="insert" value="Insert" />
		</div>
	</form>
</body>
</html>