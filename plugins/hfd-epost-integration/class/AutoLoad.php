<?php
/**
 * Created by PhpStorm.
 * Date: 6/4/18
 * Time: 5:01 PM
 */
namespace Hfd\Woocommerce;

class AutoLoad
{
    protected $basePath;

    public function __construct()
    {
        $this->basePath = dirname(__FILE__);
    }

    /**
     * @param string $className
     * @return $this
     * @throws \Exception
     */
    public function load($className)
    {
        if (substr($className, 0, strlen('Hfd\\Woocommerce\\')) != 'Hfd\\Woocommerce\\') {
            return;
        }

        if (class_exists($className)) {
            return;
        }

        $path = str_replace('\\', DIRECTORY_SEPARATOR, str_replace('Hfd\\Woocommerce\\', '', $className));
        $path = trim($path, DIRECTORY_SEPARATOR);

        $filePath = $this->basePath . DIRECTORY_SEPARATOR . $path .'.php';

        if (!file_exists($filePath)) {
            throw new \Exception(__('Invalid class '. $className, 'betanet_epost'));
        }

        require_once $filePath;

        return;
    }
}