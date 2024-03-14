<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field checkbox
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Checkbox.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_Checkbox extends IfwPsn_Wp_Options_Field
{
    public function render(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

        if (isset($this->_params['non_permanent'])) {
            $checked = '';
        } else {
            $checked = checked(1, $options->getOption($this->_id), false);
        }

        $html = $this->_getOutputStart($id, 'opt-type-checkbox');

        $html .= sprintf(
            '<label class="ifw-wp-toggle-switch"><input type="checkbox" id="%1$s" name="%2$s" value="1" %3$s /><span class="ifw-wp-toggle-switch-slider"></span></label> <b class="option-name">%4$s</b>',
            $id,
            $name,
            $checked,
            $this->_label
        );

        if (!empty($this->_description)) {
            //$html .= '<label for="'. $id .'"> '  . $this->_description . '</label>';
            $html .= '<br><p class="description"> ' . $this->_description . '</p>';
        }
        $html .= $this->_getOutputEnd();

        echo $html;
    }

    public function render2(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

        if (isset($this->_params['non_permanent'])) {
            $checked = '';
        } else {
            $checked = checked(1, $options->getOption($this->_id), false);
        }

        $html = $this->_getOutputStart($id);
        //$html .= '<input type="checkbox" id="'. $id .'" name="'. $name .'" value="1" ' . $checked . '/>';
        $html .= '<label class="ifw-wp-toggle-switch"><input type="checkbox" id="'. $id .'" name="'. $name .'" value="1" ' . $checked . ' /><span class="ifw-wp-toggle-switch-slider"></span></label>';
        if (!empty($this->_description)) {
            //$html .= '<label for="'. $id .'"> '  . $this->_description . '</label>';
            $html .= '<br><p class="description"> ' . $this->_description . '</p>';
        }
        $html .= $this->_getOutputEnd();
        echo $html;
    }
}
