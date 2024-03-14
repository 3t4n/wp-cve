<?php
namespace PDFPro;


class Init{
   
    public static function get_services(){
        return [
            Database\Init::class,
            Model\AjaxCall::class,
            Base\EnqueueAssets::class,
            Base\GlobalChanges::class,
            Base\AdminNotice::class,
            Base\Shortcodes::class,
            // Base\License::class,
            Field\Settings::class,
            Field\MetaBox::class,
            // API\Dropbox::class,
            // API\GoogleDrive::class,
            PostType\PDFPoster::class,
            Rest\AjaxCall::class,
            Rest\GetMeta::class,
        ];
    }

    public static function register_services(){
        foreach(self::get_services() as $class){
            $services = self::instantiate($class);
            if(method_exists($services, 'register')){
                $services->register();
            }
        }
    }

    private static function instantiate($class){
        if(class_exists($class."Pro") && pdfp_fs()->can_use_premium_code()){
            $class = $class."Pro";
        }
        if(class_exists($class)){
            return new $class();
        }
    }
}


