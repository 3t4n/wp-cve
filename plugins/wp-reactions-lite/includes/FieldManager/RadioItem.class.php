<?php

namespace WP_Reactions\Lite\FieldManager;

class RadioItem extends Field {
    private $elemAfter;
    private $tooltip;
    private $data = [];

    public static function create() {
        return new self();
    }

    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    public function setElemAfter($elemAfter)
    {
        $this->elemAfter = $elemAfter;
        return $this;
    }

    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    public function getTooltip()
    {
        return $this->tooltip;
    }

    public function getElemAfter()
    {
        return $this->elemAfter;
    }

    public function getData()
    {
        return $this->data;
    }

    public function build(){}
}
