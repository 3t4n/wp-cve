<?php
/*
BMo Expo - a  Wordpress and NextGEN Gallery Plugin by B. Morschheuser
Copyright 2012-2013 by Benedikt Morschheuser (http://bmo-design.de/kontakt/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

http://wordpress.org/about/gpl/
#################################################################
*/
class bmoExpo {
	
     private $arr_JSArray;
	 private $galleryTypes 	= array( // evtl. irgendwann alles dynamisch machen, dass man hier nur die einzelnen Typen anlegen muss und unten im code nichts mehr ändern muss
			'sG' =>  'scrollGallery',
			'slG' => 'scrollLightboxGallery'
		);
    
     function __construct() {
        $arr_JSArray = array();
     }
	 
     //install
     public function BMo_Expo_activation() {
        //set options if not already set with defaults as value- installation
        $options = array(
			'replaceWPGallery'=>  array('type' => 'common_php','valtype' => 'select', 'default' =>'scrollGallery', 'possibilities' => array('sG' =>  'scrollGallery','slG' => 'scrollLightboxGallery', 0 =>'Do not replace'),'desc' => __('Replace the Wordpress [gallery] shortcode with:', 'bmo-expo')),
            'jsInFooter'      =>  array('type' => 'common_php','valtype' => 'bool', 'default' =>false, 'desc' => __('Integrate the javascript in the footer and not in the <head/> of your site?', 'bmo-expo')),
			'replaceNextGENScrollGallery'      =>  array('type' => 'common_php','valtype' => 'bool', 'default' =>false, 'desc' => __("Replace NextGEN Scroll Galleries:", 'bmo-expo').'<a data-content="'.__("Due to the great success of my previous plugin 'NextGEN Scroll Gallery' (http://wordpress.org/plugins/nextgen-scrollgallery/) there are many websites with that plugin out there. If you want to switch all your 'NextGEN Scroll Galleries' to 'BMo-Expo Galleries', you can activate the switcher and all your [scrollGallery id=...] galleries will be visualized as [BMo_scrollGallery id=...] with default options.", 'bmo-expo').'" data-placement="right" data-toggle="popover" href="#" title="'.__("Description:", 'bmo-expo').'"><i class="icon-question-sign"></i></a>'),
			
            'duration'        =>  array('type' => 'common','valtype' => 'string', 'default' =>'slow', 'desc' => __('Animation duration. Durations are given in milliseconds or with the keywords "slow" and "fast"; Higher values indicate slower animations:', 'bmo-expo')),
            'gallery_width'   =>  array('type' => 'common','valtype' => 'int', 'default' =>600, 'desc' => __('Gallery width (px):', 'bmo-expo')),
            'gallery_height'  =>  array('type' => 'common','valtype' => 'int', 'default' =>400, 'desc' => __('Gallery height (px):', 'bmo-expo')),
            'thumbs_width'    =>  array('type' => 'common','valtype' => 'int', 'default' =>100, 'desc' => __('Width of each thumb (px):', 'bmo-expo')),
            'thumbs_height'   =>  array('type' => 'common','valtype' => 'int', 'default' =>100, 'desc' => __('Height of each thumb (px):', 'bmo-expo')),
            
			'sG_design'      =>  array('type' => 'sG','valtype' => 'string', 'default' =>BMO_EXPO_URL.'/css/themes/scrollGallery/default_sG.css', 'desc' => __('Scroll Gallery Design:', 'bmo-expo')),
			'sG_caption'      	=>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Show the caption?', 'bmo-expo')),
            'sG_start'           =>  array('type' => 'sG','valtype' => 'int', 'default' =>1, 'desc' => __('Start at picture number ..., the first picture is number 1:', 'bmo-expo')),
            'sG_thumbPosition'   =>  array('type' => 'sG','valtype' => 'select', 'default' =>'top', 'possibilities' => array('top', 'right', 'bottom', 'left', 'none'), 'desc' => __('Position of the thumbs:', 'bmo-expo')),
            'sG_images'          =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Show the images?', 'bmo-expo')),
            'sG_loop'            =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Scroll back to the first image after the last?', 'bmo-expo')),
            'sG_loopThumbs'      =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Scroll back to the first thumbnail after the last?', 'bmo-expo')),
            'sG_clickable'      =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Are the images clickable?', 'bmo-expo')),
            'sG_opacity'         =>  array('type' => 'sG','valtype' => 'int', 'default' =>40, 'desc' => __('Transparency of the thumbs in %:', 'bmo-expo')),
            'sG_area'            =>  array('type' => 'sG','valtype' => 'int', 'default' =>200, 'desc' => __('Width (px) of the area inside the thumbs in which the thumbs are not scrolling if the mouse is inside:', 'bmo-expo')),
            'sG_scrollSpeed'     =>  array('type' => 'sG','valtype' => 'int', 'default' =>2, 'desc' => __('Thumbnail scroll speed, should be >0:', 'bmo-expo')),
            'sG_autoScroll'      =>  array('type' => 'sG','valtype' => 'bool', 'default' =>false, 'desc' => __('Autoscroll the thumbnails?', 'bmo-expo')),
            'sG_aS_stopOnOver'   =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Stop scrolling on mouse over?', 'bmo-expo')),
            'sG_diashowDelay'    =>  array('type' => 'sG','valtype' => 'int', 'default' =>0, 'desc' => __('Diashow delay time in seconds. Use a number to activate the diashow feature. Deactivate diashow with value 0:', 'bmo-expo')),
			
			'sG_followImages' 	 =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('If the images are clicked, the thumbs will follow:', 'bmo-expo')),
			'sG_responsive' 	 =>  array('type' => 'sG','valtype' => 'bool', 'default' =>true, 'desc' => __('Makes the width responsive and the gallery width relative to parent elements width?', 'bmo-expo')),
			
			'slG_design'    =>  array('type' => 'slG','valtype' => 'string', 'default' =>BMO_EXPO_URL.'/css/themes/scrollLightboxGallery/default_slG.css', 'desc' => __('Scroll Lightbox Gallery:', 'bmo-expo')),
			'slG_caption'      	=>  array('type' => 'slG','valtype' => 'bool', 'default' =>true, 'desc' => __('Show the caption?', 'bmo-expo')),
			'slG_vertical' 	 	 =>  array('type' => 'slG','valtype' => 'bool', 'default' =>false, 'desc' => __('Show thumbs vertical or horizontal?', 'bmo-expo')),
			'slG_loopThumbs' 	 =>  array('type' => 'slG','valtype' => 'bool', 'default' =>true, 'desc' => __('Scroll back to the first thumbnail after the last?', 'bmo-expo')),
			'slG_opacity'        =>  array('type' => 'slG','valtype' => 'int', 'default' =>40, 'desc' => __('Transparency of the thumbs in %:', 'bmo-expo')),
            'slG_area'    		 =>  array('type' => 'slG','valtype' => 'int', 'default' =>200, 'desc' => __('Width (px) of the area inside the thumbs in which the thumbs are not scrolling if the mouse is inside:', 'bmo-expo')),
			'slG_scrollSpeed'    =>  array('type' => 'slG','valtype' => 'int', 'default' =>2, 'desc' => __('Thumbnail scroll speed, should be >0:', 'bmo-expo')),
			'slG_autoScroll' 	 =>  array('type' => 'slG','valtype' => 'bool', 'default' =>false, 'desc' => __('Autoscroll the thumbnails?', 'bmo-expo')),
			'slG_aS_stopOnOver'  =>  array('type' => 'slG','valtype' => 'bool', 'default' =>true, 'desc' => __('Stop scrolling on mouse over?', 'bmo-expo')),
			
			'slG_responsive'	 =>  array('type' => 'slG','valtype' => 'bool', 'default' =>true, 'desc' => __('Makes the width responsive and the gallery width relative to parent elements width?', 'bmo-expo')),
			'slG_relType'	 	 =>  array('type' => 'slG','valtype' => 'string', 'default' =>"'lightbox|_{id}_|'", 'desc' => __('Rel-attribute value ({id} will be replaced by the gallery type and the id. "|_" will be replaces by "[" and "_| by "]" - it is not possible to use "[ ]" inside shortcodes)', 'bmo-expo')),
			'slG_useLightbox'	 =>  array('type' => 'slG','valtype' => 'bool', 'default' =>true, 'desc' => __('Open images with the colorbox script by <a href="http://www.jacklmoore.com/" target="_blank">Jack Moore</a>:', 'bmo-expo')),
			'slG_lightbox_text'	 =>  array('type' => 'slG','valtype' => 'string', 'default' =>"'image {current} of {total}'", 'desc' => __('Colorbox text for the group counter ({current} and {total} will be replaced):', 'bmo-expo')),
			'slG_lightbox_opacity'	 =>  array('type' => 'slG','valtype' => 'int', 'default' =>85, 'desc' => __('Colorbox background opacity in %:', 'bmo-expo')),
			'slG_lightbox_slideshow'	 =>  array('type' => 'slG','valtype' => 'bool', 'default' =>false, 'desc' => __('Use the colorbox slideshow feature?', 'bmo-expo')),
			'slG_lightbox_speed'	 =>  array('type' => 'slG','valtype' => 'int', 'default' =>2500, 'desc' => __('Colorbox slideshow delay in ms:', 'bmo-expo')),
        );
		
		//set defaults as value
		foreach($options as $key => $option){
			$options[$key]['value']=$option['default'];
		}
		
		if(get_option(BMO_EXPO_OPTIONS."_version",false)){
	        if(get_option(BMO_EXPO_OPTIONS."_version")!=BMO_EXPO_VERSION){//update options if new version
				$this->BMo_Expo_updateOptions($options);
			}
	    }
	
		add_option(BMO_EXPO_OPTIONS."_version",BMO_EXPO_VERSION);//It does nothing if the option already exists. 
		add_option(BMO_EXPO_OPTIONS,$options);
     }
    
     //deactivate
     public function BMo_Expo_deactivation() {
        wp_deregister_script(array('jqueryBmoGallery'));
     }
    
	 public function BMo_Expo_enqueueScripts(){
         $inFooter = false;
         if(get_option(BMO_EXPO_OPTIONS,false)){
             $options = get_option(BMO_EXPO_OPTIONS);
             if($options['jsInFooter']['value']==true) $inFooter = true;
         }
		 wp_register_script( 'jqueryBMoExpo', BMO_EXPO_URL.'/js/jquery.bmoGallery.js', array('jquery') , BMO_EXPO_VERSION ,$inFooter);
	 }
            
	 public function BMo_Expo_Head(){ //wp_head()
		 $options = get_option(BMO_EXPO_OPTIONS);
		 wp_register_style('cssBMoExpo', BMO_EXPO_URL.'/css/style.css',false, BMO_EXPO_VERSION ,'all');
	     wp_register_style('cssBMoExpoDesignDefault', BMO_EXPO_URL.'/css/themes/default.css',array('cssBMoExpo'), BMO_EXPO_VERSION ,'all');
		 foreach($this->galleryTypes as $key => $val){
			 wp_register_style($key.'_cssBMoExpoDesign', $options[$key.'_design']['value'],array('cssBMoExpo'), BMO_EXPO_VERSION ,'all');
		  } 
		
		
	     if (function_exists('wp_enqueue_style')) {
			  wp_enqueue_style('cssBMoExpo');
			  wp_enqueue_style('cssBMoExpoDesignDefault');
			  foreach($this->galleryTypes as $key => $val){
			 	 wp_enqueue_style($key.'_cssBMoExpoDesign');
			  }
		  }
		  
		  echo '<!-- BMo The Gallery - Version '.BMO_EXPO_VERSION.' -->';
	 }
    
	 public function BMo_Expo_Foot(){ //wp_footer()
		 if (sizeof($this->arr_JSArray)> 0){ 
			echo '<script type="text/javascript">
						(function($){
							$.data(document.body,"BMo_Expo_Path","'.BMO_EXPO_URL.'/");
							$(document).ready(function(){';
							
							foreach($this->arr_JSArray as $scriptout){
								echo '
									'.$scriptout;
							}
						
			echo '			});
						})(jQuery); 
				  </script>';
		}
	 
	 }
    
     public function BMo_Expo_ScrollGallery($atts, $content = ''){//See http://codex.wordpress.org/Shortcode_API
        $out = $this->BMo_Expo_Generate_Gallery($atts,"scrollGallery");
		return $out.$content;        
     }
     public function BMo_Expo_ScrollLightboxGallery($atts, $content = ''){
        $out = $this->BMo_Expo_Generate_Gallery($atts,"scrollLightboxGallery");
		return $out.$content;    
     }

	 public function BMo_Expo_ReplaceWPGallery(){
		 if(get_option(BMO_EXPO_OPTIONS,false)){
             $options = get_option(BMO_EXPO_OPTIONS);
             if($options['replaceWPGallery']['value']==''||$options['replaceWPGallery']['value']=='Do not replace') return false;
			 foreach($this->galleryTypes as $key => $val){
				if($options['replaceWPGallery']['value']==$val){
					return true;
				}
			}
         }
		return false;
	 }
	 
	 public function BMo_Expo_ReplaceNextGENScrollGallery(){
		 if(get_option(BMO_EXPO_OPTIONS,false)){
             $options = get_option(BMO_EXPO_OPTIONS);
             if($options['replaceNextGENScrollGallery']['value']=='1') return true;
         }
		return false;
	 }
	 
	 public function BMo_Expo_ReplaceNextGENScrollGalleryShortcodes(){//run function after all plugins are loaded
		remove_shortcode('scrollGallery');
		add_shortcode('scrollGallery', array($this, 'BMo_Expo_ScrollGallery'));
	 }
	 
	 public function BMo_Expo_WPGallery($content, $atts){
        $post = get_post();
		$output = "";
		
		//type auswählen
		$options = get_option(BMO_EXPO_OPTIONS);
		$type = $options['replaceWPGallery']['value'];
	 	if($options['replaceWPGallery']['value']==''||$options['replaceWPGallery']['value']=='Do not replace') return false;
		//
		
		$arr_configuration = $this->BMo_Expo_getConfiguration($atts, $type);//falls in atts, parameter für mich gallery übergeben werden auslesen
		$atts['id'] = $post->ID;//auf post ID setzen, da unten benötigt wird
		
		if ( isset( $atts['orderby'] ) ) {
			$atts['orderby'] = sanitize_sql_orderby( $atts['orderby'] );
			if ( !$atts['orderby'] )
				unset( $atts['orderby'] );
		}
		
		extract(shortcode_atts(array(//filtert die folgenden attribute aus den $atts heraus und wendet, falls nicht übergeben die defaults an
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $atts));

		$id = intval($id);
		
		if ( 'RAND' == $order )
			$orderby = 'none';
			
		if ( !empty($include) ) {
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( !empty($exclude) ) {
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}
		
		//build picture array in the same way like nextgen, parameter id wird dann nicht benötigt wird random erzeugt
		$pictures = array();
		foreach ( $attachments as $id => $attachment ) {
			  $getInformation = array();
			  $fullimg = wp_get_attachment_image_src($id, 'full', false);
			  $thumbimg = wp_get_attachment_image_src($id, $size, false);
			
			  $getInformation["title"] = $attachment->post_title;
			  $getInformation["desc"]  = wptexturize($attachment->post_excerpt);
			  $getInformation["link"]  = $fullimg[0];
			  $getInformation["img"]   = $fullimg[0];
			  #$getInformation["img_abs_path"]   = ABSPATH . $path ."/" . $picture->filename;
			  $getInformation["thumb"] = $thumbimg[0];
			  //metaData
			  $serialized_data = unserialize($picture->meta_data);
			  $getInformation["width"]  =  $fullimg[1];
			  $getInformation["height"]  =  $fullimg[2];
			  $getInformation["thumbWidth"] = $thumbimg[1];
			  $getInformation["thumbHeight"] = $thumbimg[2];

if(!empty($getInformation["width"])&&!empty($getInformation["height"])&&!empty($getInformation["thumbWidth"])&&!empty($getInformation["thumbHeight"])){
			  $pictures[] = $getInformation;
			  }
		}
		
		
		$out = $this->BMo_Expo_GenerateHtml($arr_configuration, $type, $pictures);// übergebe pictures
		
		return $out.$content;    
     }

	public function BMo_Expo_get_galleryTypes(){
		return  $this->galleryTypes;
	}
	
	
	public function BMo_Expo_updateOptions($options_new){//if plugin has new options, add the new options
		 if(get_option(BMO_EXPO_OPTIONS,false)){//check ob alte options wirklich da
			 $options_old = get_option(BMO_EXPO_OPTIONS);
			 foreach($options_new as $key => $option){
				if(array_key_exists($key,$options_old)){
					if($options_old[$key]['type']!=$option['type']||$options_old[$key]['valtype']!=$option['valtype']||$options_old[$key]['default']!=$option['default']||$options_old[$key]['desc']!=$option['desc']){//hat sich geändert
						$options_old[$key] = $option;
						$options_old[$key]['value']=$option['default'];
					}
				}else{//existiert gar nicht
					$options_old[$key] = $option;
					$options_old[$key]['value']=$option['default'];
				}
			}
			foreach($options_old as $key => $option){
				if(!array_key_exists($key,$options_new)){//wurde in neuen Options gelöscht
					unset($options_old[$key]);
				}
			}
			update_option(BMO_EXPO_OPTIONS, $options_old);//update the options
		 }
	}
	
	public function BMo_Expo_replace_Brackets($str){//wichtig, da in dem option HTML im Editor keine [ ] vorkommen dürfen. Platzhalter ist |_ und _|
		$str = str_replace("[", "|_", $str);
		$str = str_replace("]", "_|", $str);
		return $str;
	}
	
	public function BMo_Expo_get_Brackets($str){
		$str = str_replace("|_", "[", $str);
		$str = str_replace("_|", "]", $str);
		return $str;
	}
	
	public function BMo_Expo_translation(){
		load_plugin_textdomain( 'bmo-expo', false, BMO_EXPO_PLUGINNAME.'/languages/'  ); 
	}
    
     //private functions:
     private function BMo_Expo_Generate_Gallery($atts, $type="scrollGallery"){
         //builds the html output by type
         $out ="";
		 
         //read shortcodeparameter:
         $arr_configuration = $this->BMo_Expo_getConfiguration($atts, $type);
		 	
		 if(!empty($arr_configuration["id"])){
			$out = $this->BMo_Expo_GenerateHtml($arr_configuration, $type);
         }else if(!empty($arr_configuration["tags"])){
			$pictures = $this->BMo_Expo_getPicturesFromTag($arr_configuration["tags"]);
			$out = $this->BMo_Expo_GenerateHtml($arr_configuration, $type, $pictures);
		 }else{
			$out = '[Gallery not found]';
         }

		return $out;
         
     }
     
     private function BMo_Expo_getConfiguration($atts, $type="scrollGallery"){#
		global $wpdb;
        //build sgconfig from parameter and options
		$options = get_option(BMO_EXPO_OPTIONS);         
	 	$arr_config = array();
		
		$arr_config["id"] = ((is_array($atts)&&array_key_exists("id", $atts))? $atts["id"] :0 );//could be a number or name
		if( !is_numeric($arr_config["id"]) )//if is a name
            $arr_config["id"] = $wpdb->get_var( $wpdb->prepare ("SELECT gid FROM $wpdb->nggallery WHERE name = '%s' ", $atts["id"]) );
		
		$arr_config["id"] = (int) $arr_config["id"];//make sure that it is a number
		
		//tags
		$arr_config["tags"] = (string)((is_array($atts)&&array_key_exists("tags", $atts))? $atts["tags"] :"" );
		
		//schriebe default Configuration aus options
		foreach($options as $key=>$option){
			 if(($option['type']=="sG"&&$type=="scrollGallery")||($option['type']=="slG"&&$type=="scrollLightboxGallery")||$option['type']=='common'){
				switch($option['valtype']){
					case 'bool':
						 $arr_config[$key] = (bool) $option['value'];
					break;
					case 'int':
						 $arr_config[$key] = (int) $option['value'];
					break;
					case 'string':
						 $arr_config[$key] = (string) $option['value'];
					break;
					case 'select':
						 $arr_config[$key] = (string) $option['value'];
					break;
					default:
						/*error*/;
				}
			}
		}
		
		//überschreibe config, falls atts von user übergeben wurden, nach validierung der atts.
		if(!empty($atts)){
			foreach($atts as $atts_key=>$attribute){
				// old: $name=preg_replace("/.*_/","",$key,1);//if user gives option parameter, without slG or sG
				foreach($options as $key=>$option){//check if user gives an correct option parameter, ! atts_key sind immer lowercase
					if(strtolower($atts_key)==strtolower($key)&&(($option['type']=="sG"&&$type=="scrollGallery")||($option['type']=="slG"&&$type=="scrollLightboxGallery")||$option['type']=='common')){
						switch($option['valtype']){
							case 'bool':
								 $arr_config[$key] = (bool) $attribute;
							break;
							case 'int':
								 $arr_config[$key] = (int) $attribute;
							break;
							case 'string':
								 $arr_config[$key] = (string) sanitize_text_field($attribute);
							break;
							case 'select':
								 if(in_array($attribute,$option['possibilities']))
								 $arr_config[$key] = (string) sanitize_text_field($attribute);
							break;
							default:
								/*error*/;
	
						 }
						break;//end option foreach
					}
				}
			}
		}
        return $arr_config;
     }
     
	 private function BMo_Expo_GenerateHtml($arr_configuration, $type="scrollGallery", $pictures = NULL){
	 	global $wpdb;  
		
		$id = $arr_configuration['id'];
		
		 // Default: Get the pictures from id, if id is set
		 if (empty($pictures)&&!empty($id)) {
			$ngg_options = get_option ('ngg_options'); //NextGenGallery Options (sind in version 2.0 gleichgeblieben siehe adapter.nextgen_settings_manager.php)
			$pictures    = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$id' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ");
			
			if($pictures)// Build the image objects from the query result
				$pictures = $this->BMo_Expo_getPictueInformation($pictures);
			
		  } else {
			$id = rand();//falls pictures als parameter uebergeben werden
		  }
		  
		  //Generate HTML with $pictures:
		  if (empty($pictures)||!is_array($pictures)) return __("[No pictures in galley]", 'bmo-expo');
		  
		  //only if we have output enque the registered scripts
		  wp_enqueue_script('jquery');
		  wp_enqueue_script('jqueryBMoExpo');
		  
		  //complete identifier
		  $full_html_id = $this->generateFullHtmlId($id,$type);
		
		  //JS
		  $scriptout = '$("#'.$full_html_id.'").bmoGallery({';
		  foreach($arr_configuration as $key=>$value){
				if($key!="id"){
					$scriptout .= $key.':	"'.$value.'",
					';
				}
		  }
		  if(substr($scriptout, -1, 1)==","){
			$scriptout = substr($scriptout, 0, -1);//delete last ,
		  }
		  $scriptout .= '});';
		   
		
		  $scriptout = $this->BMo_Expo_get_Brackets($scriptout);
		
		  //save JS in array
		  $this->arr_JSArray[$full_html_id] = $scriptout; #save JS for later in foot
		  
		 
		  $type_class ="";
		
	 	  switch($type){
            case "scrollLightboxGallery":
                $type_class = "bmo_scrollLightboxGallery";
             break;
             
            default: //== scrollGallery
             	$type="scrollGallery";
                $type_class = "bmo_scrollGallery";
           }
		 
		   $out = '<div id="'.$full_html_id.'" class="bmo_the_gallery '.$type_class.'">
            		<div class="bmo_the_gallery_thumb_area">
                		<div class="bmo_the_gallery_thumbs">';
		   foreach ($pictures as $picture){
			if ($picture["img"]) {
				$out .= '<div class="bmo_the_gallery_image">';
				
				$out .= '<a href="'.$picture["img"].'"><img src="'.$picture["thumb"].'" alt="'.$picture["title"].'" title="'.$picture["desc"].'"/></a>
                         <div class="bmo_the_gallery_caption"><p class="caption_title">'.$picture["title"].'</p><p>'.$picture["desc"].'</p></div>';
				
				$out .= '</div>';
			}
		   }
						
		   $out .= '		</div>
            		</div>
                </div>';
				
		   return $out;  
	 }
	
	private function generateFullHtmlId($id,$type="scrollGallery"){
		$num = 1;
		
		while(array_key_exists($type."_".$id."_".$num, (array) $this->arr_JSArray)){
			$num++;
		}
		
		return $type."_".$id."_".$num;
	}
	
	private function BMo_Expo_getPicturesFromTag($tags){
		global $wpdb;  
		$pictures = array();  
		
		// extract it into a array
		$tags = explode(",", $tags);
		
		if ( !is_array($tags) )
			$tags = array($tags);
		
		//normalize the given tag string	
		$tags = array_map('trim', $tags); // remove " " with trim()
		$new_slugarray = array_map('sanitize_title', $tags); // build-slug form with - between words with sanitize_title()
		$tag_sluglist   = "'".implode("', '", $new_slugarray)."'";
		
		//Treat % as a litteral in the database, for unicode support
		$tag_sluglist=str_replace("%","%%",$tag_sluglist);

		// get all $term_ids from table wp_tags
		$term_ids = $wpdb->get_col( $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE slug IN ($tag_sluglist) ORDER BY term_id ASC ", NULL));
		$pic_ids = get_objects_in_term($term_ids, 'ngg_tag'); //wp function to get object_id(s) belonging to a term in a taxonom
		
		//get the images from $pic_ids array
        $order_clause = ' ORDER BY t.pid ASC' ;

        if ( is_array($pic_ids) ) {
            $id_list = "'" . implode("', '", $pic_ids) . "'";

            // Save Query database
            $pictures = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggpictures AS t INNER JOIN $wpdb->nggallery AS tt ON t.galleryid = tt.gid WHERE t.pid IN ($id_list) $order_clause", OBJECT_K);

			if($pictures)// Build the image objects from the query result
				$pictures = $this->BMo_Expo_getPictueInformation($pictures);
			
        }
		  
		return $pictures;
	}
	
	private function BMo_Expo_getPictueInformation($pictures){
		$finalInformation = array(); 
		
		foreach($pictures as $picture) {
		  $getInformation = array();
            
          $path = $picture->path;
          /*bmo: if(preg_match('/^\\/wp-content/', $path)==0){//prüfe ob der pfad etwas vor /wp-content enthält
              $newpath = preg_split('/\\/wp-content/i', $path, 2); 
              $path = "/wp-content".$newpath[1];
          }*/
		  $getInformation["title"] = stripslashes($picture->alttext); // $picture->alttext;
		  $getInformation["desc"]  = $picture->description;
		  $getInformation["link"]  = BMO_EXPO_SITEBASE_URL . "/" . $path ."/" . $picture->filename;
		  $getInformation["img"]   = BMO_EXPO_SITEBASE_URL . "/" . $path ."/" . $picture->filename;
		  #$getInformation["img_abs_path"]   = ABSPATH . $path ."/" . $picture->filename;
		  $getInformation["thumb"] = BMO_EXPO_SITEBASE_URL . "/" . $path ."/thumbs/thumbs_" . $picture->filename;
		 //metaData
		  if(class_exists ( "C_NextGen_Metadata")){//new version
			  $meta = new C_NextGen_Metadata($picture);
			  $meta->sanitize();
			  $serialized_data = $meta->get_common_meta();
			  $getInformation["width"]  = $serialized_data["width"];
			  $getInformation["height"]  = $serialized_data["height"];
		  }else{
		  
			  $serialized_data = unserialize($picture->meta_data);
			  $getInformation["width"]  = $serialized_data["width"];
			  $getInformation["height"]  = $serialized_data["height"];
			  $getInformation["thumbWidth"] = $serialized_data["thumbnail"]["width"];
			  $getInformation["thumbHeight"] = $serialized_data["thumbnail"]["height"];
		  }
		
		  if(!empty($getInformation["width"])&&!empty($getInformation["height"])){
		  	$finalInformation[] = $getInformation;
		  }
		}
		
		return $finalInformation;
	}

}
?>