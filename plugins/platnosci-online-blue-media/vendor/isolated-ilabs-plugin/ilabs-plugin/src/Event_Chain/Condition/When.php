<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Condition;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Condition;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Condition_Interface;
class When extends Abstract_Condition implements Condition_Interface
{
    public function __construct(?callable $callable_arguments)
    {
        $this->callable_arguments = $callable_arguments;
    }
}
