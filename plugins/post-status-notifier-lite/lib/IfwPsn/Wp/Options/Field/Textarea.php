<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field textarea
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Textarea.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_Textarea extends IfwPsn_Wp_Options_Field
{
    public function render(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

        $html = $this->_getOutputStart($id);
        $html .= sprintf('<p><b class="option-name">%s</b></p>', $this->_label);
        $html .= '<textarea id="'. $id .'" name="'. $name .'">' . $options->getOption($this->_id) . '</textarea>';
        if (!empty($this->_description)) {
            $html .= '<br><p class="description"> '  . $this->_description . '</p>';
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

        $html = $this->_getOutputStart($id);
        $html .= '<textarea id="'. $id .'" name="'. $name .'">' . $options->getOption($this->_id) . '</textarea>';
        if (!empty($this->_description)) {
            $html .= '<br><p class="description"> '  . $this->_description . '</p>';
        }
        $html .= $this->_getOutputEnd();
        echo $html;
    }
}
