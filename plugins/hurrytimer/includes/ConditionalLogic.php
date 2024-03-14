<?php
namespace HurryTimer;

class ConditionalLogic
{
    protected $rules = [];

    function addRule($rule)
    {
        $this->rules[$rule['key']] = $rule;

        return $this;
    }

    function get()
    {
        return $this->rules;
    }
}
