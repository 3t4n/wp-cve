<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Action;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Action;
class Action extends Abstract_Action
{
    public function __construct(callable $callable_arguments)
    {
        $this->callable_arguments = $callable_arguments;
    }
    /**
     * @return void
     */
    public function run()
    {
        parent::run();
    }
}
