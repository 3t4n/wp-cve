<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Action;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Action;
class Output_Template extends Abstract_Action
{
    /**
     * @var string
     */
    private $template;
    public function __construct(string $template)
    {
        $this->template = $template;
    }
    /**
     * @throws Exception
     */
    public function run()
    {
        $path = Abstract_Ilabs_Plugin::$initial_instance->get_plugin_templates_dir() . \DIRECTORY_SEPARATOR . $this->template;
        include $path;
    }
    /**
     * @return string
     */
    public function get_template() : string
    {
        return $this->template;
    }
}
