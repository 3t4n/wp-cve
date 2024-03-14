<?php
if (!function_exists('add_action'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}
else if (!defined('ABSPATH'))
{
    echo "<h3>an error occured! You may not be able to access this plugin via direct URL...</h3>";
    exit();
}

if (!class_exists('pfmdz_admin')){

    class pfmdz_admin {

        //CONSTRUCTOR
        public function __construct(){

            //activate & deactivate hooks
            register_activation_hook(PFMDZ_plugin, array($this, 'onactivate'));
            register_deactivation_hook(PFMDZ_plugin, array($this, 'ondeactivate'));

            //add menu
            add_action("admin_menu", array($this, "add_admin_menu"));

            //Check plugin verison
            $version = get_option("pfmdz_plugin_version"); 

            if(version_compare($version, PFMDZ_VERSION, "<") && $version != "" ) {//Just runs after an update

                update_option("pfmdz_plugin_version", PFMDZ_VERSION);

                $css_path = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."dynamicAdminFont.css"; //admin css dynamic file path

                $css_front_path = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."dynamic-front-fonts.css"; //front css dynamic file path

                $css_code_admin = get_option("pfmdz_admincss_file");
                $css_code_front = get_option("pfmdz_frontcss_file");

                $this->writetofile($css_path, $css_code_admin);
                $this->writetofile($css_front_path, $css_code_front);

            }

            //get needed options
            $is_active = get_option('pfmdz_isactive');
            $is_tinymce = get_option('pfmdz_tinymce');
            $is_elementor = get_option('pfmdz_elementorfonts');
            $allow_fonts_upload = get_option('pfmdz_allowfontsupload');

            //add css to admin
            if ($is_active == '1') { add_action('admin_enqueue_scripts', array($this, 'add_font')); }

            //TinyMCE css
            if ($is_active == '1' && $is_tinymce == '1'){ $this->tinymce_checker(); }

            //Elementor css
            if ($is_elementor == '1') { add_action('plugins_loaded', array($this, 'addCssto_elementor')); }

            //Alllow fonts mime-types to upload
            if($allow_fonts_upload == '1'){ add_filter( 'upload_mimes', array($this, 'allow_fonts_mimes') ); }

            //misc
            $this->checkfirstredirect(); //redirect on first time activation
            $this->deactivationConfirmation(); //Confirm to empty or not the data
            
        }

        //FUNCTIONS

        //activate
        public function onactivate(){

            add_option('pfmdz_empty_onDeactivate', 0);
            add_option('pfmdz_redirect_onActivate', 1);

        }

        //deactivate
        public function ondeactivate(){
            update_option('pfmdz_redirect_onActivate', 1);
        }

        //admin menue adder
        public function add_admin_menu(){
            add_submenu_page(
                'options-general.php',
                'Persian Fonts by M_Design',
                __('Persian-Fonts', 'pfmdz'),
                'administrator',
                'persian-fonts-options',
                array($this, 'settingsPage_func') 
            );
        }

        //settings page function
        public function settingsPage_func(){

          $user_id = get_current_user_id();
          $user_color = get_user_option( 'admin_color', $user_id );
          include persianfontsmdez_PATH . "admin".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."pfmdz-admincss.php";

          include_once persianfontsmdez_PATH . "templates".DIRECTORY_SEPARATOR ."html".DIRECTORY_SEPARATOR ."settingspagehtml.php";
        }

        //edit css file
        public static function writeToFile($path, $content){

            if(empty($path)) {
                return 0; //nothing done!
            }

            // write file
            $handle = fopen($path, 'w+');
            fwrite($handle, $content);
            fclose($handle);
        
            // update last modified & access time
            touch($path);

            return 1; //updated successfully

        }

        //add admin font
        public function add_font(){

            $tmp_version = get_option("pfmdz_tmpversion");
            $final_version = PFMDZ_VERSION.".".$tmp_version;
            wp_register_style('pfmdz-adminFont', persianfontsmdez_URL . 'libs/fonts/css/dynamicAdminFont.css', false, $final_version);
            wp_enqueue_style('pfmdz-adminFont');

            //add wp themes
            $wp_theme = get_option('pfmdz_wptheme');
            if($wp_theme != 'none'){

              switch ($wp_theme){

                case '1':

                  $is_themesPlugin_active = get_option('pfmdz_themes_plugin_isactive');
                  if ($is_themesPlugin_active == '1'){

                    $check_dir = persianfonts_themesmdez_PATH . "themes".DIRECTORY_SEPARATOR ."wpadmin".DIRECTORY_SEPARATOR ."blue-dash".DIRECTORY_SEPARATOR ."blue-dash-css.css";

                    clearstatcache();
                    
                    if (file_exists($check_dir)){
                      $css_path_url =  persianfonts_themesmdez_URL . "themes/wpadmin/blue-dash/blue-dash-css.css";

                      wp_register_style('pfmdz-wpadmintheme-bluedash', $css_path_url, false, PFMDZ_VERSION);
                      wp_enqueue_style('pfmdz-wpadmintheme-bluedash');
                     
                    }else echo "<script> alert('".__('Looks like you havent purchased this theme for your dashboard!', 'pfmdz')."'); </script>"; 

                  }else echo "<script> alert('".__('The desired theme was not found :(', 'pfmdz')."'); </script>";
                  break;

                default:
                  break;
              }
            }

        }

        //check for wp post pages
        public function tinymce_checker(){

            global $pagenow;
            if (is_admin()){
              switch ($pagenow){
                case 'post.php':
                    add_action('admin_footer', array($this, 'addcsstoTinymce'));
                    break;
                case 'post-new.php':
                    add_action('admin_footer', array($this, 'addcsstoTinymce'));
                    break;
              }
            }

        }

        //add TinyMCE fonts
        public function addcsstoTinymce(){

            $tmp_version = get_option("pfmdz_tmpversion");
            $final_version = PFMDZ_VERSION.".".$tmp_version;

            ?>
            <script>
            window.addEventListener('load', function(){
              setTimeout(function(){
                var iframes = document.getElementsByTagName("iframe");
                if (iframes != null){
                  for (var i = 0; i < iframes.length; i++) {
                    let head = iframes[i].contentWindow.document.getElementsByTagName("head")[0];
                    let el = document.createElement("link");
                    el.rel = "stylesheet";
                    el.setAttribute("type", "text/css");
                    el.href = '<?php echo esc_url(persianfontsmdez_URL . "libs/fonts/css/dynamicAdminFont.css").'?ver='.$final_version; ?>';
                    head.appendChild(el);
                  }     
                }
              }, 500);
            });
            </script>
            <?php

        }

        //add css to Elementor
        public function addCssto_elementor(){

            global $pagenow;

            if ($pagenow == 'post.php' || $pagenow == "admin.php") {

              $is_elem_page = 0;
              $pageurl_action_slug = home_url($_SERVER['REQUEST_URI']);
              $pageurl_action_slug = explode('action=', $pageurl_action_slug);

              if(isset($pageurl_action_slug[1]) && $pageurl_action_slug[1] == "elementor"){

                $is_elem_page = 1;
            
              }
              /*if($is_elem_page == "0"){

                echo "<script>alert('Here!!!');</script>";
                $pageurl_action_slug = home_url($_SERVER['REQUEST_URI']);
                $pageurl_action_slug = explode('post_type=', $pageurl_action_slug);

                if($pageurl_action_slug[1] == "elementor"){
                  $is_elem_page = 1;
                }

              }*/
              if($is_elem_page == "0"){

                $pageurl_action_slug = home_url($_SERVER['REQUEST_URI']);
                $pageurl_action_slug = explode('page=', $pageurl_action_slug);

                if(isset($pageurl_action_slug[1]) && str_contains($pageurl_action_slug[1], "elementor-app")){
                  
                  $is_elem_page = 1;

                  $tmp_version = get_option("pfmdz_tmpversion");
                  $final_version = PFMDZ_VERSION.".".$tmp_version;
                  wp_enqueue_style("pfmdz-main-elemcss", persianfontsmdez_URL."libs/fonts/css/pfmdz-elementorcss.css", [], $final_version);
                }
              }

              if ($is_elem_page == '1') {

                if(!function_exists('pfmdz_addcsstoelem_byjs')){
                function pfmdz_addcsstoelem_byjs($path_url, $id){//Add CSS file by js to the head tag

                  $tmp_version = get_option("pfmdz_tmpversion");
                  $final_version = PFMDZ_VERSION.".".$tmp_version;

                ?>
                <script type="text/javascript">
                var all_heads = document.getElementsByTagName("head");
                var el = document.createElement("link");
                el.rel = "stylesheet";
                el.setAttribute("type", "text/css");
                el.id = "<?php echo $id; ?>";
                el.href = '<?php echo esc_url($path_url)."?ver=".$final_version; ?>';
                all_heads[0].appendChild(el);
                </script>
                <?php
        
                }
                }

                $is_italic = get_option('pfmdz_istalic');
                if ($is_italic == '1'){ $is_italic = 'italic'; }else $is_italic = 'normal';

                $is_customelemcss = get_option('pfmdz_customelemcss');
                if ($is_customelemcss == '1'){
                    $custom_elem_css = get_option('pfmdz_user_customelemcss');
                }else $custom_elem_css = '';

                $arr_font = get_option('pfmdz_currentFont');
                $arr_font = explode('/', $arr_font);

                $is_admin_custfonts = get_option("pfmdz_useadmincustomfonts");
                if($is_admin_custfonts != '1'){

                  $elem_fonts = '
                  @font-face {
                  font-family: "'.$arr_font[0].'";
                  font-style: normal;
                  font-weight: "'.$arr_font[2].'";
                  src: url("' . persianfontsmdez_URL . 'libs/fonts/fonts/' . $arr_font[0] . '/eot/' . $arr_font[1] . '.eot");
                  src: url("' . persianfontsmdez_URL . 'libs/fonts/fonts/' . $arr_font[0] . '/eot/' . $arr_font[1] . '.eot?#iefix") format("embedded-opentype"),
                  url("' . persianfontsmdez_URL . 'libs/fonts/fonts/' . $arr_font[0] . '/woff2/' . $arr_font[1] . '.woff2") format("woff2"),
                  url("' . persianfontsmdez_URL . 'libs/fonts/fonts/' . $arr_font[0] . '/woff/' . $arr_font[1] . '.woff") format("woff"),
                  url("' . persianfontsmdez_URL . 'libs/fonts/fonts/' . $arr_font[0] . '/ttf/' . $arr_font[1] . '.ttf") format("truetype");
                  }';

                }else if($is_admin_custfonts == '1'){
                  
                  $admin_woff = get_option("pfmdz_admincustomfont1");
                  $admin_woff2 = get_option("pfmdz_admincustomfont2");
                  $admin_family = get_option("pfmdz_admincustomfamily");
                  $admin_weight = get_option("pfmdz_admincustomweight");

                  $font_arr = explode("/", $admin_woff);
                  $font_arr = end($font_arr);
                  $font_arr2 = explode("-", $font_arr);
                  $font_arr = array();
                  $font_arr[0] = $font_arr2[0];//Set Font Family

                  $font_weight = explode(".", $font_arr2[1]);
                  $font_weight[0] = strtolower($font_weight[0]);

                  $font_arr[2] = $define_font_Weights[$font_weight[0]];
                  if($font_arr[2] == "NULL" || $font_arr[2] === NULL){
                    $font_arr[2] = "normal";
                  }

                  if($admin_family != "" && $admin_family != " " && $admin_family != false && $admin_family != NULL){
                    $font_arr[0] = $admin_family;
                  }

                  if($admin_weight != "" && $admin_weight != " " && $admin_weight != false && $admin_weight != NULL){
                    $font_arr[2] = $admin_weight;
                  }

                  $elem_fonts = "
                  @font-face {
                  font-family: '".$font_arr[0]."';
                  font-style: normal;
                  font-weight: ".$font_arr[2].";
                  src: url('".$admin_woff2."') format('woff2'),
                  url('".$admin_woff."') format('woff'),
                  }";
                  $arr_font[0] = $font_arr[0];
                }

                $elem_fonts .= '
                .elementor-panel-category-title:before, .elementor-control:not(.elementor-open) .elementor-panel-heading-toggle .eicon:before, .elementor-panel-scheme-item:not(.elementor-open) .elementor-panel-heading-toggle .eicon:before {font-family: dashicons !important; content: "\f347" !important;}
                #elementor-mode-switcher-inner i.eicon, #elementor-mode-switcher-inner i.eicon:before, div#elementor-template-library-header-preview-back i:before {font-family: dashicons !important;} #elementor-mode-switcher-inner i.eicon:before, div#elementor-template-library-header-preview-back i:before, .elementor-control.elementor-open .elementor-panel-heading-toggle .eicon:before, .elementor-panel-scheme-item.elementor-open .elementor-panel-heading-toggle .eicon:before {font-family: dashicons !important; content: "\f345" !important;}
                .elementor-editor-preview #elementor-mode-switcher-preview .eicon::before {content: "\f341" !important;}
                .elementor-panel, .elementor-button, body, input#elementor-template-library-save-template-name, input#elementor-panel-elements-search-input, .wp-picker-clear.button, .elementor-templates-modal .dialog-widget-content, select, textarea, .tipsy-inner, .elementor-add-section-drag-title, .elementor-add-section-drag-title, .elementor-safe-mode-toast .elementor-toast-content, .elementor-safe-mode-toast header h2, #elementor-finder__search__input, #elementor-template-library-filter-text, .elementor-element-title-wrapper .title, .elementor-add-section-drag-title, .elementor-select-preset-title, .elementor-color-picker__saved-colors-edit, input.pcr-clear, .elementor-color-picker__header, .elementor-color-picker__saved-colors-title, #elementor-panel input.tooltip-target, .e-global__preview-item.e-global__typography, .media-modal-content *:not(div.dashicons):not(span.dashicons), a:not(.dashicons), button.dialog-button {font-family: "' . $arr_font[0] . '" !important; font-style: ' . $is_italic . ' !important;}
                #elementor-panel label.elementor-choices-label.elementor-control-unit-1.tooltip-target i {margin-top: 6px;} 
                .elementor-panel-heading-title, .eps-app {font-family: "' . $arr_font[0] . '" !important; font-style: ' . $is_italic . ' !important;}
                '.$custom_elem_css;

                $css_path = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."css".DIRECTORY_SEPARATOR ."pfmdz-elementorcss.css";
                $this->writeToFile($css_path, $elem_fonts);

                
                add_action('elementor/editor/footer', function(){

                  $css_path_url = persianfontsmdez_URL . "libs/fonts/css/pfmdz-elementorcss.css";
                  pfmdz_addcsstoelem_byjs($css_path_url, 'pfmdz-main-elemcss');

                });


                /*Elem Themes*/
                $current_theme = get_option('pfmdz_elemtheme');

                switch ($current_theme){

                  case '1': 

                    add_action('elementor/editor/footer', function(){

                      $css_path_url =  persianfontsmdez_URL . "libs/themes/elemtheme1.css";
                      pfmdz_addcsstoelem_byjs($css_path_url, 'pfmdz-elem-theme1');
    
                    });
                    break;

                  case '2':

                    $is_themesPlugin_active = get_option('pfmdz_themes_plugin_isactive');
                    if ($is_themesPlugin_active == '1'){

                      $check_dir = persianfonts_themesmdez_PATH . "themes".DIRECTORY_SEPARATOR ."elementor".DIRECTORY_SEPARATOR ."netrox".DIRECTORY_SEPARATOR ."elemtheme2.css";
                      clearstatcache();
                      if (file_exists($check_dir)){//Have the Themes Plugin
                      
                        add_action('elementor/editor/footer', function(){

                          $css_path_url =  persianfonts_themesmdez_URL . "themes/elementor/netrox/elemtheme2.css";
                          pfmdz_addcsstoelem_byjs($css_path_url, 'pfmdz-elem-netrox');
        
                        });
                      }else echo "<script> alert('".__('Looks like you havent purchased this theme!', 'pfmdz')."'); </script>"; 

                    }else echo "<script> alert('".__('The desired theme was not found :(', 'pfmdz')."'); </script>";
                    break;

                  case '3':
                    break;
                  default:
                    break;
                }
              }
            }

        }

        //first redirect checker
        public function checkfirstredirect(){

            global $pagenow;
            $do_redirect = get_option('pfmdz_redirect_onActivate');
            if ($pagenow == 'plugins.php' && $do_redirect == '1'){
                add_action('admin_footer', array($this, 'doredirect'));
            }

        }

        //redirect doer
        public function doredirect(){

            ?>
            <script type="text/javascript">
            window.location.href = "<?php echo esc_url(admin_url()); ?>options-general.php?page=persian-fonts-options";
            </script>
            <?php
            update_option('pfmdz_redirect_onActivate', 0);

        }

        //empty on deactivate checker
        public function deactivationConfirmation(){

            global $pagenow;
            if ($pagenow == 'plugins.php'){
                add_action('admin_footer', array($this, 'emptyonDeactivate'));
            }
        }

        //empty on deactivate
        public function emptyonDeactivate(){

            ?>
                <script type="text/javascript">
                var btn = document.getElementById("deactivate-persian-admin-fonts");
                if (btn != null){
                btn.addEventListener('click', function(){
                let conf = confirm('<?php echo __('Do you want to KEEP all of the data of this plugin\ninside your database?\nPlease remind that, all of the plugins options get stored inside\nWP_OPTIONS table inside your database and having too much extra data on this table\nwill cause your web-site to load slowly\nif this is a TEMPORARY de-activation\nclick on the OK button otherwise click Cancel', 'pfmdz') ?>');
                
                if (conf == false){
                    var data = {
                      action: "pfmsz_emptyOptions_AjaxConf",
                      tmp: 1,
                    };
                    jQuery.post(ajaxurl, data, function (resp){
                        console.log(resp);
                    });
                }else {
                    console.log('User Decided to keep Options!');
                } 
                });
                }  
               </script>
    
            <?php
    
        }

        //get fonts
        public function loadFonts(){
            
            /*def Font Weights*/
            $define_font_Weights = array(
              'black' => 900,
              'extrablack' => 'bolder',
              'bold' => 'bold',
              'boldfd' => 'bold',
              'medium' => 500,
              'light' => 300,
              'ultralight' => 200,
              '' => 'normal',
              'normal' => 'normal',
              'fd' => 'normal',
              'web' => 'normal',
              'thin' => 10,
              'extralight' => 200,
              'demibold' => 600,
              'semibold' => 650,
              'ultrabold' => 750,
              'extrabold' => 800,
              'regular' => 'normal',
              'heavy' => 'bolder',
              'superheavy' => 1000,
          );

            /*start the func*/
            $directory = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR;
            $files = scandir($directory);
            $files = array_diff(scandir($directory), array('.', '..'));
            $counter = 0;
            $fonts_library = array();

            foreach($files as $file){

              $options = "<option disabled>".$file."  (Font Family)</option>";
              echo wp_kses($options, array('option' => array('disabled' => array())));
              $directory2 = persianfontsmdez_PATH . "libs".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR ."fonts".DIRECTORY_SEPARATOR .$file.DIRECTORY_SEPARATOR ;
              $files2 = scandir($directory2);
              $firstFile = $directory2 . $files2[2];
              $files3 = scandir($firstFile);
              $files3 = array_diff(scandir($firstFile), array('.', '..'));

              foreach ($files3 as $file3){

                $counter++;
                $file3 = explode(".", $file3);
                $res_font = $file3[0];

                /*$font_weight = preg_split("/[_-]/", $res_font);*/
                if(str_contains($res_font, "-")){
                  $font_weight = explode("-", $res_font);
                }else if(str_contains($res_font, "_")){
                  $font_weight = explode("_", $res_font);
                }else {
                  $font_weight[1] = "normal";
                }

                $font_weight_opt = strtolower($font_weight[1]);
                $font_weight_opt = $define_font_Weights[$font_weight_opt];

                if($font_weight_opt == "NULL" || $font_weight_opt === NULL){
                  $font_weight_opt = "normal";
                }

                $options2 =  "<option value='".$file."/".$res_font."/".$font_weight_opt."'>".$res_font."</option>";
                echo wp_kses($options2, array('option' => array('value' => array())));
              }
            }
        }

        //custom sanitize
        public function sanitize_checkbox( $input ){         
            //returns true if checkbox is checked
            return ( isset( $input ) ? true : false );
        }

        //Allow fonts upload
        public function allow_fonts_mimes($mimes){

          $mimes['ttf']   = 'font/ttf';
          $mimes['woff']  = 'font/woff';
          $mimes['woff2'] = 'font/woff2';

          return $mimes;
        }

        //Set Options Autoload off (no)
        public function set_autoload_off($option) {

          $tmp_option_val = get_option($option);
          delete_option($option);

          add_option($option, $tmp_option_val, '', 'no');

        }

        //Version Increaser
        public function version_increaser($ver){

          $ver_arr = explode(".", $ver);
          $indexer = count($ver_arr)-1;

          $outputter = [];

          for($i = $indexer; $i >= 0; $i--){
              
              $tmp = intval($ver_arr[$i]);
              $tmp2 = intval($ver_arr[$i-1]);

              if($tmp <= 999){

                  $tmp++;
                  $ver_arr[$i] = $tmp;
                  break;

              }else if($i != 0 && $tmp > 999){
                  $tmp = 0;
                  $tmp2++;
                  $ver_arr[$i] = $tmp;
                  $ver_arr[$i-1] = $tmp2;
              }
          }

          $outputter = implode(".", $ver_arr);
          return $outputter;
        }

    }
}

new pfmdz_admin(); //init
//close the PHP tag to reduce the blank spaces ?>