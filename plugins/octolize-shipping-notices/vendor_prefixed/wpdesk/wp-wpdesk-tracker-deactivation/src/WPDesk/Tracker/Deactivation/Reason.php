<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation;

class Reason
{
    private string $value;
    private string $label;
    private string $description;
    private bool $has_additional_info;
    private string $additional_info_placeholder;
    private bool $selected;
    private bool $hidden;
    public function __construct(string $value, string $label, string $description = '', bool $has_additional_info = \false, string $additional_info_placeholder = '', bool $selected = \false, bool $hidden = \false)
    {
        $this->value = $value;
        $this->label = $label;
        $this->description = $description;
        $this->has_additional_info = $has_additional_info;
        $this->additional_info_placeholder = $additional_info_placeholder;
        $this->selected = $selected;
        $this->hidden = $hidden;
    }
    public function setValue(string $value) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->value = $value;
        return $this;
    }
    public function setLabel(string $label) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->label = $label;
        return $this;
    }
    public function setDescription(string $description) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->description = $description;
        return $this;
    }
    public function setHasAdditionalInfo(bool $has_additional_info) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->has_additional_info = $has_additional_info;
        return $this;
    }
    public function setAdditionalInfoPlaceholder(string $additional_info_placeholder) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->additional_info_placeholder = $additional_info_placeholder;
        return $this;
    }
    public function setSelected(bool $selected) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->selected = $selected;
        return $this;
    }
    public function setHidden(bool $hidden) : \OctolizeShippingNoticesVendor\WPDesk\Tracker\Deactivation\Reason
    {
        $this->hidden = $hidden;
        return $this;
    }
    public function getValue() : string
    {
        return $this->value;
    }
    public function getLabel() : string
    {
        return $this->label;
    }
    public function getDescription() : string
    {
        return $this->description;
    }
    public function hasAdditionalInfo() : bool
    {
        return $this->has_additional_info;
    }
    public function getAdditionalInfoPlaceholder() : string
    {
        return $this->additional_info_placeholder;
    }
    public function isSelected() : bool
    {
        return $this->selected;
    }
    public function isHidden() : bool
    {
        return $this->hidden;
    }
}
