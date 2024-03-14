<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field radio
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Radio.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_Radio extends IfwPsn_Wp_Options_Field
{
    public function render(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $this->_getName($id, $options);

        $options = $this->_params['options'];
        $default = $this->_params['optionsDefault'];
        if (!is_array($options)) {
            $options = array($options);
        }

        $html = $this->_getOutputStart($id);

        if (!empty($this->_description)) {
            $html .= '<p class="description"> '  . $this->_description . '</p>';
        }

        foreach ($options as $k => $v) {

            if ($this->_isDefault($k, $default)) {
                $checked = ' checked ';
            } else {
                $checked = '';
            }

            $html .= sprintf(
                '<label><input type="radio" name="%s" value="%s"%s>%s</label>',
                $name, $k, $checked, $v
            );
        }

        $html .= '</div>';
        
        echo $html;
    }

    /**
     * @param $id
     * @param $options
     * @return string
     */
    protected function _getName($id, $options)
    {
        return $options->getPageId() . '['. $id .']';
    }

    /**
     * @param $k
     * @param $default
     * @return bool
     */
    protected function _isDefault($k, $default)
    {
        if (is_string($default)) {
            return $k == $default;
        } elseif (is_array($default)) {
            return in_array($k, $default);
        }
        return false;
    }
}
