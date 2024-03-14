<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Condition;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Condition;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Interfaces\Condition_Interface;
class When_Request_Value_Equals extends Abstract_Condition implements Condition_Interface
{
    /**
     * @var string
     */
    private $key;
    private $test_value;
    public function __construct(string $key, $test_value)
    {
        $this->key = $key;
        $this->test_value = $test_value;
    }
    public function assert() : bool
    {
        $value = Abstract_Ilabs_Plugin::$initial_instance->get_request()->get_by_key($this->key);
        return $this->test_value === $value;
    }
    /**
     * @return string
     */
    public function get_key() : string
    {
        return $this->key;
    }
}
