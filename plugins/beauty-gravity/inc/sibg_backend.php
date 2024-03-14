<?php
class sibg_backend{


    public function GravityInit(){
        // add beauty gravity menu item to the Form Settings page menu
        add_filter( 'gform_form_settings_menu', array($this,'my_custom_form_settings_menu_item') );
        // handle displaying content for our custom menu when selected
        add_action( 'gform_form_settings_page_Beauty_Gravity', array($this,'my_custom_form_settings_page') );
		// Add tooltipis input for standard fields
        add_action( 'gform_field_standard_settings', array($this,'MyStandardSettings'), 10, 2 );
		add_action( 'admin_init', array($this,'SIBG_admin_init') );
		add_filter('gform_field_groups_form_editor', array($this, 'SIBG_pronotif'));
		
    }

	public function SIBG_admin_init(){
		// Register backend assets
		wp_register_style("sibg_backend_style",SIBG_CSS."bg-backend.css",array('wp-color-picker'),SIBG_VERSION);
		wp_register_style("sibg_tooltip_style",SIBG_CSS."tooltip.css","",SIBG_VERSION);
		wp_register_style( 'font-awesome-icon', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css","",  1.0 );
		wp_register_script( "sibg_custom-admin", SIBG_js."custom-fields.js",  array( 'jquery' ),  SIBG_VERSION, true );
		wp_register_script("sibg_settings-preview",SIBG_js."settings-preview.js",array('jquery','wp-color-picker'),SIBG_VERSION,true);
		
		// Fixed conflict in enqueue backend assets
		add_filter( 'gform_noconflict_scripts', array( __CLASS__, 'register_noconflict_scripts' ) );
	    add_filter( 'gform_noconflict_styles', array( __CLASS__, 'register_noconflict_styles' ) );
		
		// enqueue backend assets
		add_action( 'admin_enqueue_scripts', array($this,'SIBG_load_backend_assetes') );
        // Activation hook
        register_activation_hook('pluginActivation', [$this, 'add_htaccess']);
    }

    public function pluginActivation() {

    }

	// Fixed conflict in enqueue backend scripts
	public static function register_noconflict_scripts( $scripts ) {
	    return array_merge( $scripts, array( 'sibg_custom-admin', 'sibg_settings-preview' ) );
    }

		// Fixed conflict in enqueue backend styles
	public static function register_noconflict_styles( $styles ) {
	    return array_merge( $styles, array( 'sibg_backend_style', 'sibg_tooltip_style', 'font-awesome-icon') );
    }
	
	// enqueue backend assets
	public function SIBG_load_backend_assetes(){
		wp_enqueue_style("sibg_backend_style");
		wp_enqueue_style("sibg_tooltip_style");	
		wp_enqueue_style( 'font-awesome-icon');
		wp_enqueue_script("sibg_custom-admin");
		wp_enqueue_script("sibg_settings-preview");
	}
	
	// Add BeautyGravity item to form settings
    public function my_custom_form_settings_menu_item( $menu_items ) {
		
        $menu_items[] = array(
            'name'      => 'Beauty_Gravity',
            'label'     => esc_html__('Beauty Gravity','beauty-gravity'),
			'icon'	    => 'dashicons-admin-appearance'
        );
		
        return $menu_items;
    }
	
	// add custom description to edit form field group
	function SIBG_pronotif($field_groups){
		if ( is_plugin_active( 'beauty-gravity-pro/beauty_gravity_pro.php') ){ 
		return $field_groups;
		}

		echo '<style>.sidebar-instructions {padding: 6px 10px;}</style>
		<div class="sidebar-instructions" style="background: aquamarine;">
		<p>You can get <strong>Beauty Form Styler Pro</strong> for more styling settings in your gravity form.</p><a target="blank" href="https://sehreideas.com/beauty-gravity/">Show Me More</a>
		</div>';
		return  $field_groups;
	}


	// Add tabs to BeautyGravity setting page
    public function my_custom_form_settings_page() {
        GFFormSettings::page_header();

		if ( is_plugin_active( 'beauty-gravity-pro/beauty_gravity_pro.php') ){ 
			$tabs = array(
				'theme' => 'Theme Settings',
				'settings' => 'Other Settings'
			);
		} else {
		
			$tabs = array(
				'theme' => 'Theme Settings',
				'settings' => 'Other Settings',
				'proversion' => 'Pro Version'
			);
		}
		
        if (isset($_GET['tab'])){
            $currentTab = sanitize_file_name($_GET['tab']);
        }else{
            $currentTab = 'Theme';
        }

		// Validate current form id
        $form_id = null;
        if (isset($_GET["id"])){
            if (is_numeric($_GET["id"])){
                $form_id = $_GET["id"];
            }
        }
		
		// die page if current form id isnt valid
        if ($form_id == null){
            die();
        }
		
		// echo tabs html
        echo '<h2 class="nav-tab-wrapper">';
        foreach( $tabs as $tab => $name ){
            $class = ( $tab == $currentTab ) ? ' nav-tab-active' : "";
            echo "<a class='nav-tab". esc_html($class) ."' href='?page=gf_edit_forms&view=settings&id=$form_id&subview=Beauty_Gravity&tab=$tab'>". esc_html($name) ."</a>";

        }
        echo '</h2>';

		// Call current selected tab function
        $settingFunction ="BG_".$currentTab."_tab";
        $this->$settingFunction();
        

        GFFormSettings::page_footer();
    }

	// Make pro version tab html
    public function BG_proversion_tab(){
		echo '<style>
		.comparison table {
			margin: 0 auto;
		}
		.comparison .price-now span {
		font-size: 28px;
		}
		.comparison tbody tr:nth-child(odd) {
			display: none;
		}
		.comparison tbody tr {
			border-top: solid 1px #f1f1f1;
		}
		.comparison .tick {
			width: 24px;
		}
		.comparison tbody tr:nth-child(2), .comparison tbody tr:last-child {
			border-top: none;
		}
		.comparison tbody tr {
			border-top: solid 1px #f1f1f1;
		}
		.comparison td, .comparison th {
			empty-cells: show;
			padding: 16px;
			text-align: center;
		}
		.comparison tr td:first-child {
			text-align: left;
		}
		table, td, th {
			border: 1px solid rgba(0,0,0,.1);
		}
		.banner-img {
			margin: 0 auto;
			width: 770px;
			display: block;
		}

		</style>';
		echo '
		<div class="banner-img">
		<img width="770" height="454" src="https://sehreideas.com/wp-content/uploads/2019/12/bg-present1.png" class="vc_single_image-img attachment-full" alt="" loading="lazy" srcset="https://sehreideas.com/wp-content/uploads/2019/12/bg-present1.png 770w, https://sehreideas.com/wp-content/uploads/2019/12/bg-present1-300x177.png 300w, https://sehreideas.com/wp-content/uploads/2019/12/bg-present1-768x453.png 768w" sizes="(max-width: 770px) 100vw, 770px">
		</div>
		<div class="ga_form_settings">
		<h2 style="text-align: center;">Free Vs. Pro Version Comparison</h2>
		<div class="wpb_raw_code wpb_content_element wpb_raw_html">
		<div class="wpb_wrapper">
			<div class="comparison">
  <table>
    <thead>
      <tr>
        <th></th>
        <th class="price-info light">
          <div class="price-now"><span>BeautyGravity</span></div>
          
        </th>
        <th class="price-info dark">
          <div class="price-now"><span>BeautyGravity Pro</span></div>
          
        </th>
      </tr>
    </thead>
	<tbody>
		<tr>
			<td></td>
			<td colspan="2">Multi Page Transitions</td>
		</tr>
		<tr class="compare-row">
			<td>Multi Page Transitions</td>
			<td>1 Transition</td>
			<td>10+ Transitions</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Forms Themes</td>
		</tr>
		<tr>
			<td>Forms Themes</td>
			<td>1 Theme</td>
			<td>8+ Themes</td>
		</tr>	
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Filds tooltip</td>
		</tr>
		<tr class="compare-row">
			<td>Filds tooltip</td>
			<td>1 Tooltip Style</td>
			<td>8+ Tooltip Styles</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Icon Filds</td>
		</tr>
		<tr class="compare-row">
			<td>Icon Filds</td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>		

		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Radio &amp; checkbox Toggle Mode</td>
		</tr>
		<tr class="compare-row">
			<td>Radio &amp; checkbox Toggle Mode</td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>		

		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Radio &amp; checkbox Button Mode</td>
		</tr>
		<tr class="compare-row">
			<td>Radio &amp; checkbox Button Mode</td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>		


		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Animated Progress Bar</td>
		</tr>
		<tr class="compare-row">
			<td>Animated Progress Bar</td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>	
		
		
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Range Slider</td>
		</tr>
		<tr class="compare-row">
			<td>Range Slider</td>
			<td></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>		
				
		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Customized Loading</td>
		</tr>
		<tr class="compare-row">
			<td>Customized Loading</td>
			<td></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>		


		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Free Lifetime Update</td>
		</tr>
		<tr class="compare-row">
			<td>Free Lifetime Update</td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>		

		<tr>
			<td>&nbsp;</td>
			<td colspan="2">Priority Support</td>
		</tr>
		<tr class="compare-row">
			<td>Priority Support</td>
			<td></td>
			<td><img class="tick" src="https://sehreideas.com/wp-content/uploads/2020/03/tick.svg"></td>
		</tr>	
	
	</tbody>
  </table>
  <br>
  <a target="blank" href="https://sehreideas.com/beauty-gravity/"><button aria-disabled="false" aria-expanded="false" class="button primary update-form" >GET THE PRO VERSION</button></a>
</div>
		</div>
	</div>
		</div>';
	}


	// Make theme tab html
    public function BG_theme_tab(){


        $formSettingID = null;
		
		// Validate current form id
        if (isset($_GET["id"])){
            if (is_numeric($_GET["id"])){
                $formSettingID = $_GET["id"];
            }
        }
		
		// die page if current form id isnt valid
        if ($formSettingID == null){
            die();
        }
		
		// Die page if form doesnt exist
        $is_form_exist = GFAPI::get_form($formSettingID);
        if(!$is_form_exist){
            die();
        }
		
		// Get current form settings
        $settingMeta            = json_decode(gform_get_meta($formSettingID, "bg_custom_settings"), true);

        $formTheme              = isset($settingMeta["form_theme"]) ? $settingMeta["form_theme"] : "BG_Microsoft";
        $siteThemeType          = isset($settingMeta["theme_type"]) ? $settingMeta["theme_type"] : "Light";
        $formMainColor          = isset($settingMeta["main_color"]) ? $settingMeta["main_color"] : "#EEE";
		$fontColor				= isset($settingMeta["font_color"]) ? $settingMeta["font_color"] : "#000";
        $formFont               = isset($settingMeta["font_name"]) ? $settingMeta["font_name"] : "Default";
        $fontSize               = isset($settingMeta["font_size"]) ? $settingMeta["font_size"] : "medium";
        $multiPageFormAnimation = isset($settingMeta["form_animation"]) ? $settingMeta["form_animation"] : "Fade_Slide";
        $tooltipThemeClass      = isset($settingMeta["tooltip_class"]) ? $settingMeta["tooltip_class"] : "BG_tooltip_1";
		$iconType               = (isset($settingMeta["tooltip_icon_type"])&&boolval($settingMeta["tooltip_icon_type"])) ? $settingMeta["tooltip_icon_type"] : "fas,fa-question-circle";

        if (is_null($settingMeta)) {
            $formCustomMeta["form_theme"]        = $formTheme;
            $formCustomMeta["theme_type"]        = $siteThemeType;
            $formCustomMeta["main_color"]        = $formMainColor;
            $formCustomMeta["font_color"]        = $fontColor;
            $formCustomMeta["font_name"]         = $formFont;
            $formCustomMeta["font_type"]         = 'Default/Default';
            $formCustomMeta["font_size"]         = $fontSize;
            $formCustomMeta["tooltip_class"]     = $tooltipThemeClass;
            $formCustomMeta["form_animation"]    = $multiPageFormAnimation;
            $formCustomMeta["tooltip_position"]  = 'R';
            $formCustomMeta["tooltip_icon_type"] = $iconType;
            $formCustomMeta["tooltip_view_type"] = 'Icon';
            $formCustomMeta                      = json_encode($formCustomMeta);

            gform_update_meta($formSettingID, "bg_custom_settings", $formCustomMeta, $formSettingID);
        }

		// change settings if pro version is not active.
        if(!class_exists("sibg_backend_pro")) {
            $tooltipThemeClass      = $tooltipThemeClass != "BG_tooltip_1" ? "None" : $tooltipThemeClass;
            $formTheme              = $formTheme != "BG_Microsoft" ? "Default" : $formTheme;
            $multiPageFormAnimation = $multiPageFormAnimation !== "Zoom_Slide" ? "None" : $multiPageFormAnimation;
        }

        if (is_rtl()){
            $formTooltipPosition = isset($settingMeta["tooltip_position"]) ? $settingMeta["tooltip_position"] : "L";
        }else{
            $formTooltipPosition = isset($settingMeta["tooltip_position"]) ? $settingMeta["tooltip_position"] : "R";
        }

        $tooltipIconStyle = isset($settingMeta["tooltip_view_type"]) ? $settingMeta["tooltip_view_type"] : "Icon";
        $tooltipThemeType = $siteThemeType == "Dark" ? "Light" : "Dark";
        $tooltipClasses = $tooltipThemeClass . " " . $tooltipThemeType;


		// Array for make select settings
	if ( is_plugin_active( 'beauty-gravity-pro/beauty_gravity_pro.php') ){ 
        $themes = [
            "Default" => "Default",
            "Microsoft" => "BG_Microsoft"
        ];
	} else {
        $themes = [
            "Default" => "Default",
            "Microsoft" => "BG_Microsoft"
        ];
	}
	
        $animations = [
            "None" => "None",
            "Zoom Slide" => "Zoom_Slide"
        ];
        $tooltipThemes = [
            "None" => "None",
            "Style 1" => "BG_tooltip_1"
        ];
        $fonts = [
            "Default" => ["Name" => "Default",
                "Type" => "Default"],
            "Abel" => ["Name" => "Abel",
                "Type" => "sans-serif"],
            "Anton" => ["Name" => "Anton",
                "Type" => "sans-serif"],
            "Bebas Neue" => ["Name" => "Bebas+Neue",
                "Type" => "cursive"],
            "Courgette" => ["Name" => "Courgette",
                "Type" => "cursive"],
            "Dancing Script" => ["Name" => "Dancing+Script",
                "Type" => "cursive"],
            "Dosis" => ["Name" => "Dosis",
                "Type" => "sans-serif"],
            "Inconsolata" => ["Name" => "Inconsolata",
                "Type" => "monospace"],
            "Indie Flower" => ["Name" => "Indie+Flower",
                "Type" => "cursive"],
            "Lato" => ["Name" => "Lato",
                "Type" => "sans-serif"],
            "Lobster" => ["Name" => "Lobster",
                "Type" => "cursive"],
            "Lora" => ["Name" => "Lora",
                "Type" => "serif"],
            "Merriweather" => ["Name" => "Merriweather",
                "Type" => "serif"],
            "Montserrat" => ["Name" => "Montserrat",
                "Type" => "sans-serif"],
            "Noto Sans" => ["Name" => "Noto+Sans",
                "Type" => "sans-serif"],
            "Nunito Sans" => ["Name" => "Nunito+Sans",
                "Type" => "sans-serif"],
            "Open Sans" => ["Name" => "Open+Sans",
                "Type" => "sans-serif"],
            "Oswald" => ["Name" => "Oswald",
                "Type" => "sans-serif"],
            "Playfair Display" => ["Name" => "Playfair+Display",
                "Type" => "serif"],
            "Poppins" => ["Name" => "Poppins",
                "Type" => "sans-serif"],
            "Quicksand" => ["Name" => "Quicksand",
                "Type" => "sans-serif"],
            "Rajdhani" => ["Name" => "Rajdhani",
                "Type" => "sans-serif"],
            "Roboto" => ["Name" => "Roboto",
                "Type" => "sans-serif"],
            "Roboto Condensed" => ["Name" => "Roboto+Condensed",
                "Type" => "sans-serif"],
            "Roboto Mono" => ["Name" => "Roboto+Mono",
                "Type" => "monospace"],
            "Roboto Slab" => ["Name" => "Roboto+Slab",
                "Type" => "serif"],
            "Shadows Into Light" => ["Name" => "Shadows+Into+Light",
                "Type" => "cursive"],
            "Source Sans Pro" => ["Name" => "Source+Sans+Pro",
                "Type" => "sans-serif"],
            "Ubuntu" => ["Name" => "Ubuntu",
                "Type" => "sans-serif"],
            "Varela Round" => ["Name" => "Varela+Round",
                "Type" => "sans-serif"],
            "Work Sans" => ["Name" => "Work+Sans",
                "Type" => "sans-serif"]
        ];
		$icons = [
			array("fas","fa-comment-alt"),
			array("fas","fa-comment"),
			array("fas","fa-exclamation"),
			array("fas","fa-exclamation-circle"),
			array("fas","fa-exclamation-triangle"),
			array("fas","fa-info"),
			array("fas","fa-info-circle"),
			array("fas","fa-question"),
			array("fas","fa-question-circle"),
			array("far","fa-question-circle"),
		];

        $themes = apply_filters("sibg_themes", $themes);
        $animations = apply_filters("sibg_animations", $animations);
        $tooltipThemes = apply_filters("sibg_tooltip", $tooltipThemes);
        ?>
        <form action="" method="POST">
            <table class="ga_form_settings" cellspacing="0" cellpadding="0">
                <tbody>

                <tr>
                    <th>
                        <?php esc_html_e("Choose Theme : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-theme-select" id="" class="ga-theme-select">
                            <?php

							
                            foreach ($themes as $name => $class) {
															
                                if ($formTheme == $class) {
                                    echo "<option value=". esc_html($class) ." class='ga-theme-optoins' selected>" . esc_html($name) . "</option>";
                                } else {
                                    echo "<option value=". esc_html($class) ." class='ga-theme-optoins'>" . esc_html($name) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php esc_html_e("Site Current Theme : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-site-theme-select" id="" class="ga-site-theme-select">
                            <?php
                            if ($siteThemeType == "Dark") {
                                echo "<option value='Dark' class='site-theme-options' selected>" . __('Dark', 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='Dark' class='site-theme-options'>" . __('Dark', 'beauty-gravity') . "</option>";
                            }
                            if ($siteThemeType == "Light") {
                                echo "<option value='Light' class='site-theme-options' selected>" . __('Light', 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='Light' class='site-theme-options'>" . __('Light', 'beauty-gravity') . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr class="bg_form_color">
                    <th>
                        <?php esc_html_e("Form Color : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <?php
                        echo "<input type='text' value=".  esc_html($formMainColor) ." class='my-color-field'/>";
                        echo "<input id='bg-color-picker' name='bg-form-color' type='hidden' value=". esc_html($formMainColor) .">";
                        ?>

                    </td>
                </tr>
				<tr class="bg_font_color"> 
                    <th>
                        <?php esc_html_e("Font Color : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <?php
                        echo "<input type='text' value=". esc_html($fontColor) ." class='font-color-field'/>";
                        echo "<input id='bg-font-color-picker' name='bg-font-color' type='hidden' value=". esc_html($fontColor) .">";
                        ?>

                    </td>
                </tr>
                <tr>
                    <th>
                        <?php esc_html_e("Font : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-font-select" class="ga-font-select">
                            <?php
                            foreach ($fonts as $name => $value) {
                                if ($formFont == $value["Name"]) {
                                    echo "<option value='" . esc_html($value["Name"]) . "/" . esc_html($value["Type"]) . "' class='font-options' selected>" . esc_html($name) . "</option>";
                                } else {
                                    echo "<option value='" . esc_html($value["Name"]) . "/" . esc_html($value["Type"]) . "' class='font-options'>" . esc_html($name) . "</option>";
                                }
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php esc_html_e("Font Size : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-font-size" id="" class="ga-font-select">
                            <?php

                            if ($fontSize == "small") {
                                echo "<option value='small' class='font-options' selected>" . __("Small", 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='small' class='font-options'>" . __("Small", 'beauty-gravity') . "</option>";
                            }
                            if ($fontSize == "medium") {
                                echo "<option value='medium' class='font-options' selected>" . __("Medium", 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='medium' class='font-options'>" . __("Medium", 'beauty-gravity') . "</option>";
                            }
                            if ($fontSize == "large") {
                                echo "<option value='large' class='font-options' selected>" . __("Large", 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='large' class='font-options'>" . __("Large", 'beauty-gravity') . "</option>";
                            }
                            if ($fontSize == "xlarge") {
                                echo "<option value='xlarge' class='font-options' selected>" . __("XLarge", 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='xlarge' class='font-options'>" . __("XLarge", 'beauty-gravity') . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php esc_html_e("Multipage Animation : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-animation-select" id="" class="ga-animation-select">
                            <?php
                            foreach ($animations as $name => $value) {
							
                                if ($multiPageFormAnimation == $value) {
                                    echo "<option value=". esc_html($value) ." class='ga-theme-optoins' selected>" . esc_html($name) . "</option>";
                                } else {
                                    echo "<option value=". esc_html($value) ." class='ga-theme-optoins'>" . esc_html($name) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <?php esc_html_e("Tooltip Style : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-tooltip-select" id="" class="bg-tooltip-select">
                            <?php
                            foreach ($tooltipThemes as $name => $class) {
                                if ($tooltipThemeClass == $class) {
                                    echo "<option value=". esc_html($class) ." class='ga-theme-optoins' selected>" . esc_html($name) . "</option>";
                                } else {
                                    echo "<option value=". esc_html($class) ." class='ga-theme-optoins'>" . esc_html($name) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>
                        <?php esc_html_e("Tooltip position : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <div class="tooltip-pos BG_Hover">

                            <div class="tooltip-pos-top">

									<span class="tooltip_pos_body" data-position='TL' data-text="<?php  _e("Top Left",'beauty-gravity') ?>">
                                        <label value="top-left">
                                            <?php
                                            if ($formTooltipPosition == "TL") {
                                                echo "<input type='radio' value='TL' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='TL' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                            <span class="poisition-title"></span>
										</label>
                                        <?php
                                        if ($tooltipThemeClass!="None") {
                                            echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='TL'><i class='dashicons dashicons-editor-help'></i><span>Top Left</span></span>";
                                        }
                                        ?>
									</span>


                                <span class="tooltip_pos_body" data-position='T' data-text="<?php _e("Top",'beauty-gravity') ?>">
                                        <label value="top">
											<?php
                                            if ($formTooltipPosition == "T") {
                                                echo "<input type='radio' value='T' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='T' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                            <span class="poisition-title"></span>
										</label>
										<?php
                                        if ($tooltipThemeClass!="None") {
                                            echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='T'><i class='dashicons dashicons-editor-help'></i><span>Top</span></span>";
                                        }
                                        ?>
									</span>


                                <span class="tooltip_pos_body" data-position='TR' data-text="<?php  _e("Top Right",'beauty-gravity') ?>">
                                         <label value="top-right">
										 <?php
                                         if ($formTooltipPosition == "TR") {
                                             echo "<input type='radio' value='TR' name='bg-tooltip-position' checked>";
                                         } else {
                                             echo "<input type='radio' value='TR' name='bg-tooltip-position'>";
                                         }
                                         ?>
                                         <span class="poisition-title"></span>
									 </label>
										<?php
                                        if ($tooltipThemeClass!="None") {
                                            echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='TR'><i class='dashicons dashicons-editor-help'></i><span>Top Right</span></span>";
                                        }
                                        ?>
									 </span>


                            </div>

                            <div class="tooltip-pos-middle">

									  <span class="tooltip_pos_body" data-position='L' data-text="<?php _e("Left",'beauty-gravity') ?>">
                                          <label value="left">

											<?php
                                            if ($formTooltipPosition == "L") {
                                                echo "<input type='radio' value='L' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='L' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                              <span class="poisition-title"></span>
										</label>

									   <?php
                                       if ($tooltipThemeClass!="None"){
                                           echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='L'><i class='dashicons dashicons-editor-help'></i><span>Left</span></span>";
                                       }
                                       ?>

									 </span>

                                <span class="tooltip_pos_body" data-position='R' data-text="<?php _e("Right",'beauty-gravity') ?>">
                                        <label value="right">

											<?php
                                            if ($formTooltipPosition == "R") {
                                                echo "<input type='radio' value='R' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='R' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                            <span class="poisition-title"></span>
										</label>
									  <?php
                                      if ($tooltipThemeClass!="None") {
                                          echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='R'><i class='dashicons dashicons-editor-help'></i><span>Right</span></span>";
                                      }
                                      ?>
									</span>


                            </div>

                            <div class="tooltip-pos-bottom">

									<span class="tooltip_pos_body" data-position='BL' data-text="<?php _e("Bottom Left",'beauty-gravity') ?>">
                                        <label value="bottom-left">

											<?php
                                            if ($formTooltipPosition == "BL") {
                                                echo "<input type='radio' value='BL' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='BL' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                            <span class="poisition-title"></span>
										</label>
										<?php
                                        if ($tooltipThemeClass!="None") {
                                            echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='BL'><i class='dashicons dashicons-editor-help'></i><span>Bottom Left</span></span>";
                                        }
                                        ?>
									</span>

                                <span class="tooltip_pos_body" data-position='B' data-text="<?php  _e("Bottom",'beauty-gravity') ?>">
                                            <label value="bottom">

											<?php
                                            if ($formTooltipPosition == "B") {
                                                echo "<input type='radio' value='B' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='B' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                                <span class="poisition-title"></span>
										</label>
										<?php
                                        if ($tooltipThemeClass!="None") {
                                            echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='B'><i class='dashicons dashicons-editor-help'></i><span>Bottom</span></span>";
                                        }
                                        ?>

										</span>

                                <span class="tooltip_pos_body" data-position='BR' data-text="<?php  _e("Bottom Right",'beauty-gravity') ?>">
                                            <label value="bottom-right">

											<?php
                                            if ($formTooltipPosition == "BR") {
                                                echo "<input type='radio' value='BR' name='bg-tooltip-position' checked>";
                                            } else {
                                                echo "<input type='radio' value='BR' name='bg-tooltip-position'>";
                                            }
                                            ?>
                                                <span class="poisition-title"></span>
										</label>
										<?php
                                        if ($tooltipThemeClass!="None") {
                                            echo "<span class='gf_tooltip_body ". esc_html($tooltipClasses) ." 'data-position='BR'><i class='dashicons dashicons-editor-help'></i><span>Bottom Right</span></span>";
                                        }
                                        ?>


								</span>

                            </div>

                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        <?php esc_html_e("Tooltip Icon Style : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <select name="bg-tooltip-icon-select" id="" class="ga-tooltip-icon-select">
                            <?php
                            if ($tooltipIconStyle == "Icon") {
                                echo "<option value='Icon' class='ga-theme-optoins' selected>" . __('Icon', 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='Icon' class='ga-theme-optoins'>" . __('Icon', 'beauty-gravity') . "</option>";
                            }
                            if ($tooltipIconStyle == "Hover") {
                                echo "<option value='Hover' class='ga-theme-optoins' selected>" . __('Hover', 'beauty-gravity') . "</option>";
                            } else {
                                echo "<option value='Hover' class='ga-theme-optoins'>" . __('Hover', 'beauty-gravity') . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
				
				<?php
				if($tooltipIconStyle == "Icon"){
					echo '<tr class="bg-tooltip-icon-type">';
				}else{
					echo '<tr class="bg-tooltip-icon-type" style="display:none;">';
				}
				?>
				
                    <th>
                        <?php esc_html_e("Tooltip Icon : ", 'beauty-gravity'); ?>
                    </th>
                    <td>
                        <div class="bg-tooltip-icon-type-container">
                            <?php
							foreach($icons as $key=>$val){
								$value   = "";
								$classes = "";
								foreach($val as $index=>$class){
									$value   .= $class.",";
								}
								$value   = substr($value, 0, -1);
								$classes = str_replace(","," ",$value);
								if($value == $iconType){
									echo "<label><input type='radio' value='". esc_html($value) ."' name='bg-tooltip-icon-type' checked><i class='". esc_html($classes) ."'></i><span></span></label>";
								}else{
									echo "<label><input type='radio' value='". esc_html($value) ."' name='bg-tooltip-icon-type'><i class='". esc_html($classes) ."'></i><span></span></label>";
								}
							}
                            ?>
						</div>
                    </td>
                </tr>

                </tbody>
            </table>
            <input class="button-primary" type="submit" name="save_theme_settings"
                   value="<?php esc_html_e("Save ", 'beauty-gravity'); ?>">
        </form>
        <?php
		// Save current form settings when click on save
        if (isset($_POST["save_theme_settings"])) {
            $formTheme                           = sanitize_file_name($_POST["bg-theme-select"]);
            $siteThemeType                       = sanitize_file_name($_POST["bg-site-theme-select"]);
            $formMainColor                       = sanitize_hex_color($_POST["bg-form-color"]);
			$fontColor				             = sanitize_hex_color($_POST["bg-font-color"]);
            $formFont                            = sanitize_file_name(explode("/", $_POST["bg-font-select"])[0]);
            $fontType                            = sanitize_file_name(explode("/", $_POST["bg-font-select"])[1]);
            $multiPageFormAnimation              = sanitize_file_name($_POST["bg-animation-select"]);
            $tooltipThemeClass                   = sanitize_file_name($_POST["bg-tooltip-select"]);
            $formTooltipPosition                 = sanitize_file_name($_POST["bg-tooltip-position"]);
            $tooltipIconStyle                    = sanitize_file_name($_POST["bg-tooltip-icon-select"]);
            $fontSize                            = sanitize_file_name($_POST["bg-font-size"]);
            $iconType                            = sanitize_text_field($_POST["bg-tooltip-icon-type"]);
            $formCustomMeta                      = json_decode(gform_get_meta($formSettingID, "bg_custom_settings"), true);
            $formCustomMeta["form_theme"]        = $formTheme;
            $formCustomMeta["theme_type"]        = $siteThemeType;
            $formCustomMeta["main_color"]        = $formMainColor;
			$formCustomMeta["font_color"]        = $fontColor;
            $formCustomMeta["font_name"]         = $formFont;
            $formCustomMeta["font_type"]         = $fontType;
            $formCustomMeta["font_size"]         = $fontSize;
            $formCustomMeta["tooltip_class"]     = $tooltipThemeClass;
            $formCustomMeta["form_animation"]    = $multiPageFormAnimation;
            $formCustomMeta["tooltip_position"]  = $formTooltipPosition;
            $formCustomMeta["tooltip_view_type"] = $tooltipIconStyle;
            $formCustomMeta["tooltip_icon_type"] = $iconType;
            $formCustomMeta = json_encode($formCustomMeta);
            gform_update_meta($formSettingID, "bg_custom_settings", $formCustomMeta, $formSettingID);
			
			// Refreshing the page
            ?>
            <script>location.reload();</script>
            <?php
        }
    }

	// Make setting tab html
    public function BG_settings_tab(){
		
        $form_id = null;
		
		// Validate Current form id
        if (isset($_GET["id"])){
            if (is_numeric($_GET["id"])){
                $form_id = $_GET["id"];
            }
        }
		
		// Die page if current form id is not valid
        if ($form_id==null){
            die();
        }
		
		// Die page if form is not exist
        $is_form_exist = GFAPI::get_form($form_id);
        if(!$is_form_exist){
            die();
        }
		

		
		// Get current form settings
        $jsonSettings      = json_decode(gform_get_meta($form_id , "bg_custom_settings"),true);
        $additionalSetting = isset($jsonSettings["additionalSetting"]) ? $jsonSettings["additionalSetting"]:"";
        $prevUX            = isset($additionalSetting["prev_UX"])      ? $additionalSetting["prev_UX"]     :"false";
		$scroll_pad        = isset($additionalSetting["scroll_pad"])   ? $additionalSetting["scroll_pad"]  : 50;

        if (isset($additionalSetting["use_scroll"])) {
            $use_scroll = $additionalSetting["use_scroll"]  ==  'true' ? true  : false;
        } else {
            $use_scroll = false;
        }

        ?>
        <form action="" method="POST">
            <table class="ga_form_settings" cellspacing="0" cellpadding="0">
                <tbody>

                <tr>                                       
                    <td>
						<label for="bg_prev_input">
						<?php 
							if ($prevUX == "true"){
								echo "<input id='bg_prev_input' type='checkbox' class='ga-prebutton-ux' name='bg-prev-ux' checked>";
							}else{
								echo "<input id='bg_prev_input' type='checkbox' class='ga-prebutton-ux' name='bg-prev-ux'>";
							}
							esc_html_e("Previous button UX perfectly ",'beauty-gravity');
						?>
						</label>
                    </td>
                </tr>
				
				<tr>                                       
                    <td>
						<label for="bg_use_scroll_input">
						<?php 
							if ($use_scroll){
								echo '<input name="bg-use-scroll" value="false" type="hidden">';
								echo "<input id='bg_use_scroll_input' value='true' type='checkbox' class='ga-prebutton-ux' name='bg-use-scroll' checked>";

							}else{
								echo '<input name="bg-use-scroll" value="false" type="hidden">';
								echo "<input id='bg_use_scroll_input' value='true' type='checkbox' class='ga-prebutton-ux' name='bg-use-scroll'>";
							}
							esc_html_e("Scroll page to top of the form in multi-step transition ",'beauty-gravity');
						?>
						</label>
                    </td>
                </tr>
				
				
				
				
				<tr>
						<th>
							<?php
							esc_html_e("Form paadding top in scroll(px)",'beauty-gravity');
							?>
						</th>
						<td>
							<input type="number" name="scroll-pad" value="<?php  esc_html_e($scroll_pad) ?>">
						</td>
					</tr>
				
				
				
				
                </tbody></table>
            <input class="button-primary" type="submit" name="save_settings_settings" value="<?php esc_html_e("Save ",'beauty-gravity'); ?>">
        </form>
        <?php
		
		// Save current form setting when click on save button
        if (isset($_POST["save_settings_settings"])) {
            $prevUX            = isset($_POST["bg-prev-ux"])       ? "true" : "false";
            $use_scroll        = $_POST["bg-use-scroll"] == 'true'  ? 'true' : 'false';
            $scroll_pad        = $_POST["scroll-pad"]       ?  intval($_POST["scroll-pad"]) : 50;
            $jsonSettings      = json_decode(gform_get_meta($form_id , "bg_custom_settings"),true);
			$jsonSettings['additionalSetting']['prev_UX']    = $prevUX;
			$jsonSettings['additionalSetting']['use_scroll'] = $use_scroll;
			$jsonSettings['additionalSetting']['scroll_pad'] = $scroll_pad;
            $jsonSettings                      = json_encode($jsonSettings);
            gform_update_meta( $form_id ,"bg_custom_settings" ,$jsonSettings ,$form_id );
			
			// Refreshing the page
            ?>
            <script>location.reload();</script>
            <?php
        }
    }

    public function MyStandardSettings( $position ) {

        // Create input for field tooltip
        if ( $position == 10 ) {
            echo '<li class="gravity_tooltip">';

            // Set the label for the field.
            echo '<label class="section_label" for="custom_tooltip">';
            _e( 'Tooltip', 'beauty-gravity' );
            echo '</label>';

            // Set the input field.
            echo '<input type="text" class="fieldwidth-3" id="is_tooltip" size="35" onkeyup="SetFieldProperty(\'is_tooltip\', this.value);"/>';

            // Close the <li> tag.
            echo '</li>';


        }
		
		// Create select field for choose radio/checkbox mode
        if ( $position == 25 ) {
            echo '<li class="Beauty_choose field_setting" style="display: none">';

            // Set the label for the field.
            echo '<label class="section_label" for="custom_tooltip">';
            echo __( 'View Mode', 'gravity-tooltips' );
            echo '</label>';

            // Set the input field.
            echo '<select id="view_mode" onchange=\'SetFieldProperty("view_mode", this.value);\'>';
            echo '<option value="default">'.__( "Default", "gravity-tooltips").'</option>';
            echo '<option value="toggle">'.__( "Toggle", "gravity-tooltips").'</option>';
            echo '<option value="Button">'.__( "Button", "gravity-tooltips").'</option></select>';
            // Close the <li> tag.
            echo '</li>';
        }

    }

}
if(is_admin()){
	$page = isset($_GET['page']) ? sanitize_file_name( $_GET['page'] ) : "";
	if($page == "gf_edit_forms"){
		// Load backend jsut in gravity forms page
		$GravityTooltipInput = new sibg_backend();
		$GravityTooltipInput->GravityInit();	
	}
}

