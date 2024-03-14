<?php

class sibg_frontend {
	
	// Is used icon for adding font awesome
	public $addIcon   = false;
	
	// Is included form_themes.php file or not
    public $is_add_style    = false;
	
    public function GravityInit(){
		
		if (!$this->is_add_style){
            require_once("form_themes.php");
            $this->is_add_style    = true;
        }
		
		// Enqueu fron assets
		add_action( 'gform_enqueue_scripts', array($this, 'sibg_enqueue_scripts') );
		
		add_filter( 'gform_pre_render', array($this,'make_hidden_input'),10,3 );
		
		// Enable ajax mode for forms that use multi-step animation
		add_filter( 'gform_form_args', array($this,'enable_ajax'), 1000, 1);
		
		// class adding with js and php(for form jumping)
		// data attribute adding with js
        add_filter( 'gform_form_tag', array($this,'AddStyleClass'), 100, 2 );
		
		// Customize each field
        add_filter( 'gform_field_content',array( $this, 'FormCustomization'),11, 5);
		
		// Add animation functions to onclick
        add_filter( 'gform_next_button', array($this,'add_next_onclick'), 10, 2 );
        add_filter( 'gform_previous_button', array($this,'add_previous_onclick'), 10, 2 );
        add_filter( 'gform_submit_button', array($this,'add_submit_onclick'), 10, 2 );
		
		// Add costum class to previous button
        add_filter( 'gform_previous_button', array($this,'add_previous_class'), 11, 2 );
		
        add_filter( 'gform_ajax_spinner_url', array($this,'change_loading') );
        add_filter( 'gform_confirmation_anchor', '__return_false' ); 
    }

	public function enable_ajax($form_args){

		$form_id   = $form_args['form_id'];
		$customize = self::GetCustomizeSetting($form_id);
		$animation = isset($customize["form_animation"]) ? $customize["form_animation"] : "None";
		
		// Enable ajax mode for forms that use multi-step animation
		if (!empty($animation) && $animation != "None"){
			$form_args['ajax'] = 'true';
		}

        // Restart options that used in add_next_onclick for next form
        $this->nxt_form_id      = $form_id;
        $this->next_page_num    = [];
        $this->next_button_text = null;

        // Restart options that used in add_previous_onclick for next form
        $this->prev_form_id     = $form_id;
        $this->prev_page_num    = [];
        $this->prev_button_text = null;
        $this->count_page       = 1;



		return $form_args;
	}

	public function make_hidden_input($form){
		
		// Add sibg-input field for enqueue script and styles for each form
		if(class_exists('GF_Field')){
			$object = new GF_Field();
			$object->type = 'sibg-init';
			if(is_array($form['fields'])){
				array_unshift($form['fields'], $object);
			}
		}	
		return $form;
	}

    public $color_style = null;
	public function sibg_enqueue_scripts($form) {
		$form_id = $form['id'];
		$options = $this->GetCustomizeSetting($form_id);
		$theme   = isset($options['form_theme']) ? $options['form_theme'] : "Default";
		
		if($theme!="Default"){
			
			// Get form color styles from form-theme.php
            $this->color_style .= sibg_GetThemesSettings($form_id);
			
            add_action( 'wp_footer', array($this, 'add_dynamic_style') );
			
			
			if (is_rtl()){
				wp_enqueue_style('sibg_theme_general', SIBG_CSS.'themes/BG_themes_general_rtl.css',"",SIBG_VERSION);	
			}else{
				wp_enqueue_style('sibg_theme_general', SIBG_CSS.'themes/BG_themes_general.css',"",SIBG_VERSION);	
			}
			wp_enqueue_script("theme",SIBG_js."theme.js",array("jquery"),SIBG_VERSION,true); 
		}
		
    }
 
    public $next_anim_type   = "None";
	public $nxt_form_id      = null;
	public $next_page_num    = [];
	public $next_button_text = null;
	
	// Add animation function on onclick and add button name
    public function add_next_onclick( $button, $form ) {

        $form_id              = $form['id'];
        $customSetting        = self::GetCustomizeSetting($form_id);
		$this->next_anim_type = $customSetting["form_animation"];
        $dom                  = new DOMDocument();
        $dom->loadHTML( $button );
        $input      = $dom->getElementsByTagName( 'input' )->item(0);
        $onclick    = $input->getAttribute( 'onclick' );
        $onkeypress = $input->getAttribute( 'onkeypress' );

        // Deprecated and moved to enable_ajax function
		// Restart options for next form
//		if($this->nxt_form_id != $form_id){
//			$this->nxt_form_id      = $form_id;
//			$this->next_page_num    = [];
//			$this->next_button_text = null;
//		}
		
		//get current page number
		if(!isset($this->next_page_num['current'])){
			$current = 1;
			$this->next_page_num['current'] = [1];
		}else{
			$current = sizeof($this->next_page_num['current']) + 1;
			array_push($this->next_page_num['current'], $current);
		}

		//add next button text
		foreach($form['fields'] as $key=>$field){
			if($field->type == "page" && $field->pageNumber == $current + 1 && $field->nextButton['type'] == "text"){
				$this->next_button_text = __( $field->nextButton['text'], 'gravityforms' );
				
			}
		}
		if(!empty($this->next_button_text)){		
			$input->setAttribute( 'value', $this->next_button_text);
		}
		
		//add animation function to onclick
        if ($this->next_anim_type != "None" && $this->next_anim_type != ""){
            if (is_rtl()){
                $onclick    = "bg_form_animation('next','".$this->next_anim_type."_rtl',$form_id,$current);";
                $onkeypress = "if( event.keyCode == 13 ){".$onclick."}";
            }else{
                $onclick    = "bg_form_animation('next','$this->next_anim_type',$form_id,$current);";
                $onkeypress = "if( event.keyCode == 13 ){".$onclick."}";
            }
        }

        $input->setAttribute( 'onclick', $onclick );
        $input->setAttribute( 'onkeypress', $onkeypress );
        return $dom->saveHtml( $input );

    }

    public $prev_anim_type   = "None";
	public $prev_form_id     = null;
	public $prev_page_num    = [];
	public $prev_button_text = null;
	public $count_page       = 1;
	
	// Add animation function on onclick and add button name
    public function add_previous_onclick( $previous_button, $form ) {
		
        $form_id              = $form['id'];
        $customSetting        = self::GetCustomizeSetting($form_id);
		$this->prev_anim_type = $customSetting["form_animation"];

        $dom = new DOMDocument();
        $dom->loadHTML( $previous_button );
        $input       = $dom->getElementsByTagName( 'input' )->item(0);
        $onclick     = $input->getAttribute( 'onclick' );
        $onkeypress  = $input->getAttribute( 'onkeypress' );

        // Deprecated and moved to enable_ajax function
		// Restart options for next form
//		if($this->prev_form_id != $form_id){
//			$this->prev_form_id     = $form_id;
//			$this->prev_page_num    = [];
//			$this->prev_button_text = null;
//			$this->count_page       = 1;
//		}
		
		//get current page number
		if(!isset($this->prev_page_num['current'])){
			$current = 2;
			$this->prev_page_num['current'] = [2];
		}else{
			$current = sizeof($this->prev_page_num['current']) + 2;
			array_push($this->prev_page_num['current'], $current);
		}
		
		//add prev button text
		$this->count_page = 1;
		foreach($form['fields'] as $key=>$field){
			if($field->type == "page" && $field->pageNumber == $current + 1 && $field->previousButton['type'] == "text"){
				$this->prev_button_text = __( $field->previousButton['text'], 'gravityforms' );	
			}
			if($field->type == "page"){
				$this->count_page++;
			}
		}
		
		if($current >= $this->count_page){
			if($form['lastPageButton']['type'] == "text"){
				$prev_text = $form['lastPageButton']['text'];
				$prev_text = __( $prev_text, 'gravityforms' );
				$input->setAttribute( 'value', $prev_text);
			}  
		}elseif(!empty($this->prev_button_text)){
			$input->setAttribute( 'value', $this->prev_button_text);
		}
		
		//add animation function to onclick
        if( $this->prev_anim_type != "None" && $this->prev_anim_type != ""){
            if (is_rtl()){
                $onclick   = "bg_form_animation('prev','".$this->prev_anim_type."_rtl',$form_id,$current);";
                $onkeypress = "if( event.keyCode == 13 ){".$onclick."}";
            }else{
                $onclick   = "bg_form_animation('prev','$this->prev_anim_type',$form_id,$current);";
                $onkeypress = "if( event.keyCode == 13 ){".$onclick."}";
            }
        }

        $input->setAttribute( 'onclick', $onclick );
        $input->setAttribute( 'onkeypress', $onkeypress );
        return $dom->saveHtml( $input);

    }

	// Add custome class to previous button
    public function add_previous_class( $previous_button, $form ) {
        $customize           = self::GetCustomizeSetting($form["id"]);
		if(isset($customize ["additionalSetting"]["prev_UX"])){
			if ($customize ["additionalSetting"]["prev_UX"]=="true"){
				$start           = strpos( $previous_button,"class");
				$previous_button = substr_replace( $previous_button, "class=\"BG_prev_ux ",$start, "7" );
			}
		}
        
        return $previous_button;
    }

	// Add animation function on onclick
    public function add_submit_onclick( $button, $form ){
		
        $customize = self::GetCustomizeSetting($form["id"]);
		if(is_null($customize)) return $button;
		
        $animation = $customize["form_animation"] != "" && $customize["form_animation"] != "undifined" ? $customize["form_animation"] : "None";
        if ($animation != "None"){
			?>
			<script>
				if(typeof sibg_add_submit_on_click === 'undefined')
					var sibg_add_submit_on_click= new Array();
					sibg_add_submit_on_click[<?php  esc_html_e($form['id']) ?>] = true;
			</script>
			<?php

        }
        return $button;
    }

	// Change ajax url for hide
    public function change_loading( $src ) {
        $S_Url = get_option("siteurl");
        return $S_Url;

    }

	// Add costume classe to current form
    public function AddStyleClass($form_tag , $form) {
        
        $customize   = self::GetCustomizeSetting($form["id"]);
		
		if(is_null($customize)) return $form_tag;
		
        $customStyle = $customize["form_theme"] != "Default" && $customize["form_theme"] != "" ? $customize["form_theme"] : "bg_default_theme";
        $customFont  = $customize["font_name"]  != "Default" ? $customize["font_name"]  : "";
        $themeType   = $customize["theme_type"]!="" ? "BG_".$customize["theme_type"]:"BG_Light";  				
        
        if ($customFont){
            $customFont = "BG_{$customFont}_font";
        }
        $costumeFontSize = $customize["font_size"]!= ""?"BG_".$customize["font_size"]."_size":"BG_medium_size";
        $customFont      = str_replace("+","_",$customFont);
        $customClass     = $customStyle." ".$customFont." ".$costumeFontSize." ".$themeType." ";
		$customClass     = apply_filters('bg_form_class',$customClass, $form);
		
		// Return if costume classes doesnt exist
        if ($customClass == ""){
            return $form_tag;
        }

        //if gravityforms <form> tag has property class
        $has_class = strpos($form_tag,"class");
        if ($has_class){
            $start = $has_class;
        }else{
            $start = strpos($form_tag,"id");
        }
		
        //add custom class to <form>
        if($has_class){
            $form_tag = substr_replace( $form_tag, $customClass, $start+7, "0" );
        }else{
            $form_tag = substr_replace( $form_tag, " class='".$customClass."' ", "5", "0" );
        }

        return $form_tag;
    }

    public $nextStart       = 0;
    public function FormCustomization($content, $field, $value, $lead_id, $form_id){
		
        $customize   = self::GetCustomizeSetting($form_id);
		
		if(is_null($customize)) return $content;
	
		/*add classe to form tag when (gform_form_tag) filter not exist	
		  and enqueue animation script*/
		if ($field->type == "sibg-init"){
			$content = self::add_class_to_form($field, $form_id, $customize);
			$name = isset($customize["form_animation"]) ? $customize["form_animation"]:"None";
			if ($name != "None" && $name != ""){
				
				$name = strtolower($name);
				if (is_rtl()){
					if ($name === "zoom_slide"){
						// Free version
						$temp = SIBG_js."animations/".$name."_rtl_animation.js";
					}else{
						// Pro version
						$temp = SIBGP_js."animations/".$name."_rtl_animation.js";
					}
					wp_enqueue_script($name."_rtl_animation",$temp,array("jquery"),SIBG_VERSION,true);
				}else{
					if ($name === "zoom_slide") {
						// Free version
						$temp = SIBG_js . "animations/" . $name . "_animation.js";
					}else{
						// Pro version
						$temp = SIBGP_js . "animations/" . $name . "_animation.js";
					}
					wp_enqueue_script($name."_animation",$temp,"jquery",SIBG_VERSION,true);
				}
				wp_enqueue_style("sibg_animation_style",SIBG_CSS."bg-animations.css","",SIBG_VERSION);
				wp_enqueue_script("bg-validation-fields-js",SIBG_js."bg-validation-fields.js",array("jquery"),SIBG_VERSION,true);
			}
			
			return $content;
        }
		
        //Change fileupload field design
        if ( ($field->type=="fileupload" || $field->type=="post_image" || ($field->type=="post_custom_field" && $field->inputType=="fileupload")) && $customize["form_theme"] != "Default"){
			
			$search  = $field->type != "post_custom_field" ? $field->type : "fileupload";
            $content = self::ChangeUploadField($content, $customize, $search, $field);
			
        }
		

        do_action("bg_before_Customization_action");
        $content = apply_filters('bg_before_Customization',$content,$field,$customize);
		
        // Add form fields tooltip
        $content = self::FormTooltip($field,$content,$customize);

        do_action("after_Customization_action");
        $content = apply_filters("bg_after_Customization",$content,$field,$customize);

        // Add bg_label_container in Android theme
        if ($customize["form_theme"] == "BG_Android"){
            $tooltipTheme = $customize["tooltip_class"]?$customize["tooltip_class"]:"None";
            
			if ($field->is_tooltip && $tooltipTheme != "None") {
				$start = strpos($content, "<label");
				$content = substr_replace($content, '<div class="bg_label_container">', $start, 0);
				$finish = strpos($content, "</div>");
				$content = substr_replace($content, "</div>", $finish, 0);
			}
            
        }
		
		// Enqueue font awesome
		if($this->addIcon){
			wp_enqueue_style( 'font-awesome-icon', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css","",  1.0 );
		}
		
        return $content;
    }
	
	// Echo form color styles in style tag
	public function add_dynamic_style(){
		
	echo "<style>". strip_tags($this->color_style,'<br><a>') ."</style>";

	}

	// Add form attribute and classes to the hidden input for adding to form with js
	// use this methode beacuse somethimes gform_form_tag filter is not called
	public function add_class_to_form($field, $form_id, $customize){
		$customStyle = $customize["form_theme"] != "Default" && $customize["form_theme"] != "" ? $customize["form_theme"] : "bg_default_theme";
        $customFont  = $customize["font_name"]  != "Default" ? $customize["font_name"]  : "";
        $themeType   = $customize["theme_type"]!="" ? "BG_".$customize["theme_type"]:"BG_Light";  				
        if ($customFont){
            wp_enqueue_style("BG_font".$customFont,"https://fonts.googleapis.com/css?family={$customFont}&display=swap","",1.0,false);
            $customFont = "BG_{$customFont}_font";
        }
        $costumeFontSize = $customize["font_size"]!= ""?"BG_".$customize["font_size"]."_size":"BG_medium_size";
        $customFont      = str_replace("+","_",$customFont);
		$mainColor       = isset($customize["main_color"]) ? $customize["main_color"] : "#0389ff";
		$classes         = isset($field->has_sibg_page) && !empty($field->has_sibg_page) ? $field->has_sibg_page : "";
		$classes        .= "$customStyle, $customFont, $costumeFontSize, $themeType";
		$animation       = isset($customize['form_animation'])?$customize['form_animation'] : "None";
		$is_rtl          = is_rtl()? "true" : "false";
		$is_ux           = isset($customize ["additionalSetting"]["prev_UX"])?$customize ["additionalSetting"]["prev_UX"]:"false";	
		$is_scroll       = isset($customize ["additionalSetting"]["use_scroll"])?$customize ["additionalSetting"]["use_scroll"]:"false";	
		$scroll_pad      = isset($customize ["additionalSetting"]["scroll_pad"])?$customize ["additionalSetting"]["scroll_pad"]: 50;
		
		$content = "<input type='hidden' class='sibg_form_init' value='gform_$form_id' data-class='$classes' data-color='$mainColor' data-animation='$animation' is_rtl='$is_rtl' is_ux='$is_ux' use_scroll='$is_scroll' scroll_pad='$scroll_pad'>";
		return $content;
	}

	// Valid field for use tooltip
    public function GetFieldType(){
        $types = array(
            'text',
            'textarea',
            'select',
            'multiselect',
            'number',
            'name',
            'date',
            'time',
            'phone',
            'address',
            'website',
            'email',
            'fileupload',
            'captcha',
            'list',
            'consent',
            'post_title',
            'post_content',
            'post_excerpt',
            'post_tags',
            'post_category',
            'post_image',
            'post_custom_field',
            'product',
            'quantity',
            'option',
            'shipping',
            'total',
            'creditcard',
            'password',
            'singleproduct',
            'calculation',
            'price',
            'hiddenproduct',
            'singleshipping',
            'donation',
			'radio',
			'checkbox',
        );

        return $types;

    }

	// Change fileupload field html
    public function ChangeUploadField($content, $customize, $search, $field){
		$is_multiple = isset($field->multipleFiles) && boolval($field->multipleFiles) ? true : false;
 		if($is_multiple){
			return $content;
		}
		
        $start   = strpos( $content,"ginput_container_".$search);
        if($customize["form_theme"] == 'BG_Android'){
            $content = substr_replace( $content, " ginput_container_{$search}'><i class='BG_fileupload_icon'></i><label class='BG_fileupload'><span class='BG_fileupload_text'>Upload File</span>",$start, "29" );
        }
        else{
            $content = substr_replace( $content, " ginput_container_{$search}'><label class='BG_fileupload'><i class='BG_fileupload_icon'></i><span class='BG_fileupload_text'>Upload File</span>",$start, "29" );
        }

        $start   = strlen($content) - 6;
        $start   = strpos( $content,"</div>",$start);
        $content = substr_replace( $content, "</label></div>",$start, "6" );
        return $content;
    }

	// Get form settings
    public static function GetCustomizeSetting($form_id){
        $customize = json_decode(gform_get_meta($form_id,"bg_custom_settings"),true);

        // Default option values
        if (is_null($customize)) {
            $customize['form_theme']       = "BG_Microsoft";
            $customize['theme_type']       = "Light";
            $customize['main_color']       = "#EEE";
            $customize['fontColor']        = "#000";
            $customize['font_type']        = "Default";
            $customize['font_name']        = "Default";
            $customize['font_size']        = "medium";
            $customize['form_animation']   = "Fade_Slide";
            $customize['tooltip_class']    = "BG_tooltip_1";
            $customize['tooltip_icon_type']        = "fas,fa-question-circle";
            $customize["tooltip_position"] = 'R';
            $customize["tooltip_view_type"] = 'Icon';
        }

        if(!class_exists("sibg_frontend_pro") && is_array($customize)) {
            $customize["tooltip_class"]  = $customize["tooltip_class"]  != "BG_tooltip_1" ? "None"    : $customize["tooltip_class"];
            $customize["form_theme"]     = $customize["form_theme"]     != "BG_Microsoft" ? "Default" : $customize["form_theme"];
            $customize["form_animation"] = $customize["form_animation"] != "Zoom_Slide"       ? "None"    : $customize["form_animation"];
        }
        return $customize;
    }

	// Make form tooltip
    public function FormTooltip($field,$content,$customize){
		// Get valid field types for tooltip
        $type        = self::GetFieldType();
		
        if(in_array($field->type,$type)){
            if ( $field->type == 'radio' || $field->type == 'checkbox' ||
				($field->type == 'product' && $field->inputType == 'radio') || 
				 ( $field->type == 'post_custom_field' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ||
				 ( $field->type == 'option' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ||
				 ( $field->type == 'post_tags' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ||
				 ( $field->type == 'post_category' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ){
					 // Make radio/checkbox field tooltip
                $content = self::RadioCheckTooltip($content,$field,$customize);
            }else{
				// Make other field tooltip
                $content = self::FieldTooltip($content,$field,$customize);
            }
        }
        return $content;
    }

	// Make field tooltip
    public function FieldTooltip($content,$field,$customize){
        $tooltipTheme = isset($customize["tooltip_class"])?$customize["tooltip_class"]:"None";
        if ($tooltipTheme != "None"){
            if( $field->is_tooltip && ($customize["form_theme"]!="BG_Material" &&
                                       $customize["form_theme"]!="BG_Material_out" &&
                                       $customize["form_theme"]!="BG_Material_out_rnd") ){
				// Add tooltip html to field						  
                $viewTooltip = "BG_".$customize["tooltip_view_type"];
                $start   = strpos( $content,"<label>");
                $content = substr_replace( $content, "<div class='main_label {$viewTooltip}'><label ",$start, "7" );
                $start   = strpos( $content,"</label>");
                $content = substr_replace( $content, "</label></div>",$start, "8" );
                $start   = strpos( $content,"</label>");
                $tooltip = self::RenderTooltip($customize,$field->is_tooltip);
                $content = substr_replace( $content, "</label>".$tooltip, $start, "8" );
            }
        }

        if ($field->type =="consent" && $customize["form_theme"]!= "Default"){
            $start       = strpos($content,"ginput_container_consent");
            $classLength = strlen("ginput_container_consent");
            $content     = substr_replace( $content, " BG_default ",$start + $classLength , "0" );
//            $start       = strpos($content,"</label>",$start);
//            $content     = substr_replace( $content, "<span class='BG_check'></span>",$start , "0" );
        }

        return $content;
    }

	// Make radio/checkbox field tooltip
    public function RadioCheckTooltip($content,$field,$customize){
		
	if (GFCommon::$version < 2.5){
		
        $this->nextStart = strpos( $content,"</label>");
        $tooltipTheme = $customize["tooltip_class"]?$customize["tooltip_class"]:"None";

        if ($field->is_tooltip && $tooltipTheme != "None"){

            $viewTooltip     = "BG_".$customize["tooltip_view_type"];
            if ($customize["form_theme"]=="BG_Material" ||
                $customize["form_theme"]=="BG_Material_out" ||
                $customize["form_theme"]=="BG_Material_out_rnd"){
                    $viewTooltip = "BG_Hover";
            }

			// Add tooltip html to field
            $this->nextStart = strpos( $content,"<label>");
            $content         = substr_replace( $content, "<div class='main_label {$viewTooltip}'><label ",$this->nextStart, "7" );
            $this->nextStart = strpos( $content,"</label>");
            $content         = substr_replace( $content, "</label></div>",$this->nextStart, "8" );
            $tooltip         = self::RenderTooltip($customize,$field->is_tooltip);
            $content         = substr_replace( $content, "</label>".$tooltip, $this->nextStart , "8" );
            $toolLength      = strlen("</label>".$tooltip);
            $this->nextStart = strpos( $content,"</label>",$this->nextStart + $toolLength + 1);

        }else{
            $this->nextStart = $this->nextStart + 8;
            $limitLength = strlen($content);
            if ($this->nextStart > $limitLength){

            }else{
                $this->nextStart = strpos( $content,"</label>",$this->nextStart);
            }

        }
		
	} else {

     
        $tooltipTheme = $customize["tooltip_class"]?$customize["tooltip_class"]:"None";

        if ($field->is_tooltip && $tooltipTheme != "None"){

            $viewTooltip     = "BG_".$customize["tooltip_view_type"];
            if ($customize["form_theme"]=="BG_Material" ||
                $customize["form_theme"]=="BG_Material_out" ||
                $customize["form_theme"]=="BG_Material_out_rnd"){
                    $viewTooltip = "BG_Hover";
            }

			// Add tooltip html to field
            $this->nextStart = strpos( $content,"<label>");
            $content         = substr_replace( $content, "<label {$viewTooltip}'><label ",$this->nextStart, "7" );
            $this->nextStart = strpos( $content,"</label>");
            $content         = substr_replace( $content, "</label></legend>",$this->nextStart, "8" );
            $tooltip         = self::RenderTooltip($customize,$field->is_tooltip);
            $content         = substr_replace( $content, "</label>".$tooltip, $this->nextStart , "8" );
            $toolLength      = strlen("</label>".$tooltip);
            $this->nextStart = strpos( $content,"</label>",$this->nextStart + $toolLength + 1);

        }else{
            $this->nextStart = $this->nextStart + 9;
            $limitLength = strlen($content);
            if ($this->nextStart > $limitLength){

            }else{
                $this->nextStart = strpos( $content,"</label>",$this->nextStart);
            }

        }

	}
	
        if  ( $field->type == "checkbox" || $field->type == "radio" || 
			( $field->type == 'product' && $field->inputType == 'radio' ) || 
			( $field->type == 'option' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ||
			( $field->type == 'post_custom_field' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ||
			( $field->type == 'post_tags' && ($field->inputType == 'radio' || $field->inputType == 'checkbox'))||( $field->type == 'post_category' && ($field->inputType == 'radio' || $field->inputType == 'checkbox')) ){

			$not_use_image_choices = isset($field->imageChoices_enableImages) && boolval($field->imageChoices_enableImages) ? "false" : "true";
			
			// If not use image mode for choices(jetsloth plugin)
			if($not_use_image_choices == "true"){

				$viewMode             = $field->view_mode ? $field->view_mode : "default";
				$viewMode             = "BG_".$viewMode;
				$viewTooltip          = "BG_".$customize["tooltip_view_type"];
				$containerClass = $field->type;
				// in default theme delete radio/checkbox view mode
				if ($customize["form_theme"]==="Default" || $customize["form_theme"]===""){
					$viewMode = "";
				}elseif ($customize["form_theme"]=="BG_Material" ||
						 $customize["form_theme"]=="BG_Material_out" ||
						 $customize["form_theme"]=="BG_Material_out_rnd"){
							$viewTooltip = "BG_Hover";
				}

				if ($field->type == 'product' || $field->type == 'post_custom_field' || $field->type == 'post_tags'   || $field->type == 'post_category' || $field->type == 'option'){
						$containerClass = $field->inputType;
				}
				
				$classPos             = strpos( $content,"gfield_".$containerClass);
				$classLength          = strlen("gfield_".$containerClass);
				$content              = substr_replace( $content, " ".$viewMode." ".$viewTooltip, $classPos+$classLength, "0" );
				$this->nextStart     += strlen($viewMode) + strlen($viewTooltip) + 2;
				foreach ($field->choices as $key=>$value){
//					$content          = substr_replace( $content, "<span class='BG_check'></span></label>", $this->nextStart , "8" );
//					$this->nextStart += strlen("<span class='BG_check'></span>");
					$content          = self::GetChoicesTooltip($content,$customize,$value,$tooltipTheme);
				}
				
			}

            
        }
        return $content;
    }

	// Add tooltip for each choice in radio/checkbox field
    public function GetChoicesTooltip($content,$customize,$value,$tooltipTheme){
		$value["is_tooltip"] = isset($value["is_tooltip"]) ? $value["is_tooltip"] : "";
        if ($value["is_tooltip"] != "" && $tooltipTheme != "None"){
            $tooltip         = self::RenderTooltip($customize,$value["is_tooltip"]);
            $toolLength      = strlen("</label>".$tooltip);
            $content         = substr_replace( $content, "</label>".$tooltip, $this->nextStart, "8" );
            $this->nextStart = stripos( $content,"</label>",$this->nextStart + $toolLength);
        }else{
            $this->nextStart  = stripos( $content,"</label>",$this->nextStart + 10);
        }
        return $content;
    }

	// Make tooltip html
    public function RenderTooltip($customize,$content){
		$this->addIcon = true;
        do_action( 'wpml_register_single_string', 'BeautyGravity', 'bg_text_' . $content, $content );
		$content = apply_filters( 'wpml_translate_single_string', $content, 'BeautyGravity', 'bg_text_' . $content );
        $tooltipThemeClass   = $customize["tooltip_class"];
        $tooltipThemeType    = $customize["theme_type"] == "Dark" || $customize["theme_type"] == "" ? "Light":"Dark";
        $formTooltipPosition = $customize["tooltip_position"];
		$iconType            = (isset($customize["tooltip_icon_type"]) && boolval($customize["tooltip_icon_type"])) ? $customize["tooltip_icon_type"] : "fas,fa-question-circle";
		$iconClass           = str_replace(","," ",$iconType);
        $tooltipClasses    = $tooltipThemeClass . " " . $tooltipThemeType;
        $tooltip = "<span class='gf_tooltip_body {$tooltipClasses}'data-position={$formTooltipPosition}><i class='{$iconClass}'></i><span>{$content}</span></span>";
        return $tooltip;
    }

}
if(!is_admin()){
	$gravityTooltip = new sibg_frontend();
	$gravityTooltip->GravityInit();
}
