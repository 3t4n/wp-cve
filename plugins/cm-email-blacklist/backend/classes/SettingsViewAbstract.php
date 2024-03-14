<?php

abstract class CMEB_SettingsViewAbstract
{
    protected $categories = array();
    protected $subcategories = array();

    public function render()
    {
        $result = '';
        $categories = $this->getCategories();
        foreach($categories as $category => $title)
        {
            $result .= $this->renderCategory($category);
        }
        return $result;
    }

    public function renderCategory($category)
    {
        $result = '';
        $subcategories = $this->getSubcategories();
        if( !empty($subcategories[$category]) )
        {
            foreach($subcategories[$category] as $subcategory => $title)
            {
                $result .= $this->renderSubcategory($category, $subcategory);
            }
        }
        return $result;
    }

    abstract protected function getCategories();

    abstract protected function getSubcategories();

    public function renderSubcategory($category, $subcategory)
    {
        $result = '';
        $subcategories = $this->getSubcategories();
        if( isset($subcategories[$category]) AND isset($subcategories[$category][$subcategory]) )
        {
            $options = CMEB_Settings::getOptionsConfigByCategory($category, $subcategory);
            foreach($options as $name => $option)
            {
                $result .= $this->renderOption($name, $option);
            }
        }
        return $result;
    }

    public function renderOption($name, array $option = array())
    {
        if( empty($option) )
        {
            $option = CMEB_Settings::getOptionConfig($name);
        }
        return $this->renderOptionTitle($option)
                . $this->renderOptionControls($name, $option)
                . $this->renderOptionDescription($option);
    }

    public function renderOptionTitle($option)
    {
        return $option['title'];
    }

    public function renderOptionControls($name, array $option = array())
    {
        if( empty($option) ) $option = CMEB_Settings::getOptionConfig($name);
        switch($option['type'])
        {
            case CMEB_Settings::TYPE_BOOL:
                return $this->renderBool($name);
            case CMEB_Settings::TYPE_INT:
                return $this->renderInputNumber($name);
            case CMEB_Settings::TYPE_TEXTAREA:
                return $this->renderTextarea($name);
            case CMEB_Settings::TYPE_RADIO:
                return $this->renderRadio($name, $option['options']);
            case CMEB_Settings::TYPE_SELECT:
                return $this->renderSelect($name, $option['options']);
            case CMEB_Settings::TYPE_MULTISELECT:
                return $this->renderMultiSelect($name, $option['options']);
            case CMEB_Settings::TYPE_CSV_LINE:
                return $this->renderCSVLine($name);
            case CMEB_Settings::TYPE_FILEUPLOAD:
                return $this->renderFileupload($name);
            default:
                return $this->renderInputText($name);
        }
    }

    public function renderOptionDescription($option)
    {
        return (isset($option['desc']) ? $option['desc'] : '');
    }

    protected function renderInputText($name, $value = null)
    {
        if( is_null($value) )
        {
            $value = CMEB_Settings::getOption($name);
        }
        return sprintf('<input type="text" name="%s" value="%s" />', esc_attr($name), esc_attr($value));
    }

    protected function renderFileupload($name, $value = null)
    {
    	if( is_null($value) )
    	{
    		$value = CMEB_Settings::getOption($name);
    	}
    	return sprintf('<input class="upload_image_%s" type="text" size="10" name="%s" value="%s" /><input class="upload_image_button_%s" type="button" value="Upload Image" />', esc_attr($name), esc_attr($name), esc_attr($value), esc_attr($name));
    }

    protected function renderInputNumber($name)
    {
        return sprintf('<input type="number" name="%s" value="%s" />', esc_attr($name), esc_attr(CMEB_Settings::getOption($name)));
    }

    protected function renderCSVLine($name)
    {
        $value = CMEB_Settings::getOption($name);
        if( is_array($value) ) $value = implode(',', $value);
        return $this->renderInputText($name, $value);
    }

    protected function renderTextarea($name)
    {
        return sprintf('<textarea name="%s" cols="60" rows="5">%s</textarea>', esc_attr($name), esc_html(CMEB_Settings::getOption($name)));
    }

    protected function renderBool($name)
    {
        return $this->renderRadio($name, array(0 => 'Disabled', 1 => 'Enabled'), intval(CMEB_Settings::getOption($name)));
    }

    protected function renderRadio($name, $options, $currentValue = null)
    {
        if( is_null($currentValue) )
        {
            $currentValue = CMEB_Settings::getOption($name);
        }
        $result = '';
        $fieldName = esc_attr($name);
        foreach($options as $value => $text)
        {
            $fieldId = esc_attr($name . '_' . $value);
            $result .= sprintf('<div><input type="radio" name="%s" id="%s" value="%s"%s />'
                    . '<label for="%s"> %s</label></div>', $fieldName, $fieldId, esc_attr($value), ( $currentValue == $value ? ' checked="checked"' : ''), $fieldId, esc_html($text)
            );
        }
        return $result;
    }

    protected function renderSelect($name, $options, $currentValue = null)
    {
        return sprintf('<div><select name="%s">%s</select>', esc_attr($name), $this->renderSelectOptions($name, $options, $currentValue));
    }

    protected function renderSelectOptions($name, $options, $currentValue = null)
    {
        if( is_null($currentValue) )
        {
            $currentValue = CMEB_Settings::getOption($name);
        }
        $result = '';
        if( is_callable($options) ) $options = call_user_func($options, $name);
        foreach($options as $value => $text)
        {
            $result .= sprintf('<option value="%s"%s>%s</option>', esc_attr($value), ( $this->isSelected($value, $currentValue) ? ' selected="selected"' : ''), esc_html($text)
            );
        }
        return $result;
    }

    protected function isSelected($option, $value)
    {
        if( is_array($value) )
        {
            return in_array($option, $value);
        }
        else
        {
            return ($option == $value);
        }
    }

    protected function renderMultiSelect($name, $options, $currentValue = null)
    {
        return sprintf('<div><select name="%s[]" multiple="multiple">%s</select>', esc_attr($name), $this->renderSelectOptions($name, $options, $currentValue));
    }

}
