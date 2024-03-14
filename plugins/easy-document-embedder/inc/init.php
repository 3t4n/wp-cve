<?php

/**
 * @package easy-document-embedder
 */

namespace EDE\Inc;
require_once dirname(__FILE__) . '/Base/class-settings-link.php';
require_once dirname(__FILE__) . '/Base/class-custom-post.php';
require_once dirname(__FILE__) . '/Base/class-enqueue.php';
require_once dirname(__FILE__) . '/Base/class-metabox.php';
require_once dirname(__FILE__) . '/Base/shortcode.php';
require_once dirname(__FILE__) . '../../admin/admin.php';
require_once dirname(__FILE__) . '/gutenblock/gutenblock.php';

class Init
{
    // get all class
    public static function getClasses()
    {
        return [
            Base\SettingsLink::class,
            Base\Enqueue::class,
            Base\EDECustomPost::class,
            Base\EDEMetabox::class,
            Base\Shortcode::class,
            Admin\Admin::class,
            Gutenblock\GutenBLock::class
        ];
    }

    public static function ede_register()
    {
        if (!empty( self::getClasses() )) {
            foreach ( self::getClasses() as $class) {
                $service = self::instanciate($class);
                if (method_exists($service,'ede_register')) {
                    $service->ede_register();
                }
            }
        }
    }

    public static function instanciate($class)
    {
        return new $class;
    }
}
