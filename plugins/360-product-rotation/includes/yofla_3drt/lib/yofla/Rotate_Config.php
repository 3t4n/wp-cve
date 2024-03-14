<?php
/**
 * Generates the contest of the config.js file dynamically, based
 * on provided image list and settings. If no settings are provided,
 * defaults are used
 */

class Rotate_Config {

    public $images_list; //holds arrays: "images","imageslarge"
    public $products_path;
    public $products_url;
    public $settings;
    public $product_id;

    private $_config = array(); //config in form of array, to be exported e.g. in JSON

    /**
     *
     */
    function __construct() {
        $this->_init_config();
    }


    /**
     * Returns config in JSON format
     *
     */
    public function get_config_json()
    {
        $this->_make_config();
        $json_config = json_encode($this->_config);
        $output =  'var RotationData = '.$json_config.';'.PHP_EOL;

        if($this->product_id)
        {
            $output .=  'var RotationData_'.$this->product_id.' = RotationData;'.PHP_EOL;
        }

        return  $output;

    }

    /**
     * Steps required to create/modify config values
     *
     */
    private function _make_config()
    {
        $this->_use_settings();
        $this->_add_images_to_config();
    }//make_config


    /**
     * Initializes config variable with minimum default settings
     *
     *
     */
    private function _init_config()
    {
        $this->_config = array();

        //settings
        $this->_config["settings"] = array(
            "control"=>array(
                "maxZoom"=>300,
                "dragSpeed"=>0.5,
                "reverseDrag"=>false,
            ),
            "userInterface"=>array(
                "showArrows"=>true,
                "showToggleFullscreenButton"=>false,
                "showZoombar"=>false,
                "showTogglePlayButton"=>true,
            ),
            "preloader"=>array(
                "color1"=>'#FF000',
                "type"=>'wave',
            ),
            "rotation"=>array(
                "rotatePeriod"=>3,
                "rotateDirection"=>1,
                "bounce"=>false,
                "rotate"=>'true',
            ),
        );

        //custom
        $this->_config["custom"] = array();

        //hotspots
        $this->_config["hotspots"] = array();

        //images
        $this->_config["images"] = array();

    }//_init_config()

    /**
     * Adds the images in the list to the _config array
     */
    private function _add_images_to_config()
    {
        //sort images
        $this->_sort_images();

        //add images to config
        for ($i=0; $i<sizeof($this->images_list["images"]); $i++)
        {
            $this->_add_image_to_config($this->_create_image_info($i));
        }

    }//add_images_to_config


    /**
     * Modifies the settings _config array with the provided settings
     */
    private function _use_settings()
    {
       //use defaults from "global" settings.ini
       if(isset($this->settings))
       {
           if(isset($this->settings["config"]))
           {
               $config = $this->settings["config"];
               $this->_use_settings_config($config);
           }
       }

       //used if images folder has own settings.ini
       if (isset($this->images_list["settings"]))
       {
           if (isset($this->images_list["settings"]["config"]))
           {
               $config = $this->images_list["settings"]["config"];
               $this->_use_settings_config($config);
           }
       }
    }

    /**
     * Converts the provided default or directory level settings into
     * a more structured format suitable for _config
     *
     * @param $config
     */
    private function _use_settings_config($config)
    {


        //custom logo
        if (isset($config["logo_imageUrl"])){
            $this->_config["custom"]["logo"] = array();
            $this->_config["custom"]["logo"]["imageUrl"] = $config["logo_imageUrl"];

            if (isset($config["logo_linkUrl"])) $this->_config["custom"]["logo"]["linkUrl"] = $config["logo_linkUrl"];
            if (isset($config["logo_linkTarget"])) $this->_config["custom"]["logo"]["linkTarget"] = $config["logo_linkTarget"];

            if (isset($config["logo_imagePositionLeft"]) && isset($config["logo_imagePositionTop"])){
                $this->_config["custom"]["logo"]["imagePosition"] = array();
                $this->_config["custom"]["logo"]["imagePosition"]["top"] = intval($config["logo_imagePositionTop"]);
                $this->_config["custom"]["logo"]["imagePosition"]["left"] = intval($config["logo_imagePositionLeft"]);
            }
        }

        //rotation
        if (isset($config["bounce"])) $this->_config["settings"]["rotation"]["bounce"] = ($config["bounce"] == TRUE);
        if (isset($config["rotate"])) $this->_config["settings"]["rotation"]["rotate"] = $config["rotate"];
        if (isset($config["rotatePeriod"])) $this->_config["settings"]["rotation"]["rotatePeriod"] = floatval($config["rotatePeriod"]);
        if (isset($config["rotateDirection"])) $this->_config["settings"]["rotation"]["rotateDirection"] = intval($config["rotateDirection"]);


        //rotation multi level
        if (isset($config["multilevel_verticalSteps"])){
            $this->_config["settings"]["rotation"]["multilevel"] = array();
            $this->_config["settings"]["rotation"]["multilevel"]["verticalSteps"] = max(1,intval($config["multilevel_verticalSteps"]));

            if (isset($config["multilevel_horizontalSteps"])){
                $horizontalSteps = intval($config["multilevel_horizontalSteps"]);
            }
            else{
                $horizontalSteps = sizeof($this->images_list["images"]) / $this->_config["settings"]["rotation"]["multilevel"]["verticalSteps"];
                $horizontalSteps = intval($horizontalSteps);
            }
            $this->_config["settings"]["rotation"]["multilevel"]["horizontalSteps"] = $horizontalSteps;
        }

        //control
        if (isset($config["maxZoom"])) $this->_config["settings"]["control"]["maxZoom"] = intval($config["maxZoom"]);
        if (isset($config["maxZoomAuto"])) $this->_config["settings"]["control"]["maxZoom"] = ($config["maxZoomAuto"] == TRUE);
        if (isset($config["dragSpeed"])) $this->_config["settings"]["control"]["dragSpeed"] = floatval($config["dragSpeed"]);
        if (isset($config["reverseDrag"])) $this->_config["settings"]["control"]["reverseDrag"] = ($config["reverseDrag"] == TRUE);
        if (isset($config["enableSwing"])) $this->_config["settings"]["control"]["enableSwing"] = ($config["enableSwing"] == TRUE);
        if (isset($config["rotateOnMouseHover"])) $this->_config["settings"]["control"]["rotateOnMouseHover"] = ($config["rotateOnMouseHover"] == TRUE);
        if (isset($config["clickUrl"])) $this->_config["settings"]["control"]["clickUrl"] = $config["clickUrl"];
        if (isset($config["clickUrlTarget"])) $this->_config["settings"]["control"]["clickUrlTarget"] = $config["clickUrlTarget"];
        if (isset($config["disableMouseControl"])) $this->_config["settings"]["control"]["disableMouseControl"] = ($config["disableMouseControl"] == TRUE);
        if (isset($config["switchToPanOnYmovement"])) $this->_config["settings"]["control"]["switchToPanOnYmovement"] = ($config["switchToPanOnYmovement"] == TRUE);
        if (isset($config["mouseWheelZooms"])) $this->_config["settings"]["control"]["mouseWheelZooms"] = ($config["mouseWheelZooms"] == TRUE);

		//touchPageScroll: (typeof(jsonData.settings.control.touchPageScroll) === 'undefined') ? false : jsonData.settings.control.touchPageScroll,

        //preloader
        if (isset($config["color1"])) $this->_config["settings"]["preloader"]["color1"] = $config["color1"];
        if (isset($config["type"])) $this->_config["settings"]["preloader"]["type"] = $config["type"];
        if (isset($config["image"])) $this->_config["settings"]["preloader"]["image"] = $config["image"];
        if (isset($config["showStartButton"])) $this->_config["settings"]["preloader"]["showStartButton"] = $config["showStartButton"];
        if (isset($config["largeImagesPreloader"])) $this->_config["settings"]["preloader"]["largeImagesPreloader"] = $config["largeImagesPreloader"];

        //userinterface
        if (isset($config["showToggleFullscreenButton"])) $this->_config["settings"]["userInterface"]["showToggleFullscreenButton"] = ($config["showToggleFullscreenButton"] == TRUE);
        if (isset($config["showZoombar"])) $this->_config["settings"]["userInterface"]["showZoombar"] = ($config["showZoombar"] == TRUE);
        if (isset($config["showZoomButtons"])) $this->_config["settings"]["userInterface"]["showZoomButtons"] = ($config["showZoomButtons"] == TRUE);
        if (isset($config["showArrows"])) $this->_config["settings"]["userInterface"]["showArrows"] = ($config["showArrows"] == TRUE);
        if (isset($config["showTogglePlayButton"])) $this->_config["settings"]["userInterface"]["showTogglePlayButton"] = ($config["showTogglePlayButton"] == TRUE);
        if (isset($config["showToggleRotateButton"])) $this->_config["settings"]["userInterface"]["showToggleRotateButton"] = ($config["showToggleRotateButton"] == TRUE);
        if (isset($config["showMouseWheelToolTip"])) $this->_config["settings"]["userInterface"]["showMouseWheelToolTip"] = ($config["showMouseWheelToolTip"] == TRUE);
    }


    /**
     * Sorts provided images (before they are written to config)
     */
    private function _sort_images()
    {
        if (is_array($this->images_list["images"])) usort($this->images_list["images"],array($this,"cmp"));
        if (is_array($this->images_list["imageslarge"])) usort($this->images_list["imageslarge"],array($this,"cmp"));
    }

    private function  cmp($a,$b)
    {
        return strnatcmp(basename($a), basename($b));
    }


    /**
     * Creates the images entry (in array format) in the exported config
     *
     * @param $image_id
     * @return array
     */
    private function _create_image_info($image_id)
    {
        $image_info = array();

        $image_path_normal = $this->images_list["images"][$image_id];
        $image_path_large = (isset($this->images_list["imageslarge"])) ? $this->images_list["imageslarge"][$image_id] : NULL;

        $src = $this->_get_image_url($image_path_normal);
        $image_info["src"] = $src;

        //add info on large image, if defined
        if($image_path_large)
        {
            $image_info["srcLarge"] = $this->_get_image_url($image_path_large);
        }

        return $image_info;
    }

    /**
     * Adds image to list in exported config
     *
     * @param $image_info
     */
    private function _add_image_to_config($image_info)
    {
        $this->_config["images"][] = $image_info;
    }

    /**
     * The path for images in the exported config must be valigenerating config.jss, adding comments
     *
     * @param $image_path
     * @return string
     */
    private function _get_image_url($image_path)
    {
        $path_relative = substr($image_path,strlen($this->products_path));
        $url = $this->products_url.$path_relative;
        return $url;
    }

}//class