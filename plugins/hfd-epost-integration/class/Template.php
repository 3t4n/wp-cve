<?php
/**
 * Created by PhpStorm.
 * Date: 6/5/18
 * Time: 4:04 PM
 */
namespace Hfd\Woocommerce;

class Template extends DataObject
{
    protected $basePath;

    public function __construct(array $data = [])
    {
        $this->basePath = dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR . 'templates';
        parent::__construct($data);
    }

    /**
     * Retrieve template file path
     * @param string $file
     * @return string
     */
    public function getTemplate($file)
    {
        return $this->basePath . DIRECTORY_SEPARATOR . ltrim($file, DIRECTORY_SEPARATOR);
    }

    /**
     * Render template into html
     * @param string $template
     * @param array $variables
     * @return string
     */
    public function fetchView($template, $variables = array())
    {
        $template = $this->getTemplate($template);

        if (!file_exists($template)) {
            return '';
        }

        ob_start();
        extract($variables);
        require $template;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getSkinUrl($file)
    {
        return HFD_EPOST_PLUGIN_URL . '/'. ltrim($file, '/');
    }
}