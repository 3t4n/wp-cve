<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
class On extends Abstract_Event
{
    /**
     * @var int
     */
    private $priority;
    /**
     * @var string
     */
    private $hook_name;
    public function __construct(string $hook_name, int $priority = 10)
    {
        $this->priority = $priority;
        $this->hook_name = $hook_name;
    }
    public function create()
    {
        add_action($this->hook_name, function () {
            $this->callback();
        }, $this->priority);
    }
}
