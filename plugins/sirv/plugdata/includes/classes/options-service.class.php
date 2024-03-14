<?php

    defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

    class getValue{

        protected static $jsFile = 'https://scripts.sirv.com/sirvjs/v3/sirv.js';

        public static function getOption($optionName){
            $value = '';
            switch ($optionName) {
                case 'SIRV_AWS_HOST':
                    $value = 'http://' . get_option($optionName);
                    break;
                case 'SIRV_JS_FILE':
                    $jsModules = get_option('SIRV_JS_MODULES', 'lazyimage,zoom,spin,hotspots,video,gallery,model');
                    $jsFile = self::$jsFile;

                    if(! empty($jsModules)){
                        if(! self::isSirvFullJS($jsModules, 7)){
                            $jsFile .= "?modules={$jsModules}";
                        }
                    }

                    $value = $jsFile;
                    break;
                default:
                    $value = get_option($optionName);
                    break;
            }

            return $value;
        }


        protected static function isSirvFullJS($modulesStr, $existingModulesCount){
            $modules = explode(',', $modulesStr);

            return count($modules) == $existingModulesCount;
        }


        public static function getJsFileUrl(){
            return self::$jsFile;
        }

    }
?>
