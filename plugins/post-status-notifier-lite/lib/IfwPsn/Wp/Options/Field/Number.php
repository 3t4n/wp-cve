<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field text
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Number.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_Number extends IfwPsn_Wp_Options_Field
{
    public function render(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

        $extra = '';
        $min = 1;
        if (isset($this->_params['min'])) {
            $min = $this->_params['min'];
        }
        $extra .= sprintf('min="%d" ', (int)$min);

        $max = 99;
        if (isset($this->_params['max'])) {
            $max = $this->_params['max'];
        }
        $extra .= sprintf('max="%d" ', (int)$max);

        $step = 1;
        if (isset($this->_params['step'])) {
            $step = $this->_params['step'];
        }
        $extra .= sprintf('step="%d" ', (int)$step);

        if (isset($this->_params['maxlength'])) {
            $extra .= sprintf('maxlength="%d" ', (int)$this->_params['maxlength']);
        }
        if (isset($this->_params['size'])) {
            $extra .= sprintf('size="%d" ', (int)$this->_params['size']);
        }
        if (isset($this->_params['placeholder'])) {
            $extra .= sprintf('placeholder="%s" ', htmlentities($this->_params['placeholder']));
        }

        $html = $this->_getOutputStart($id, 'opt-type-number');
        $html .= sprintf('<p><b class="option-name">%s</b></p>', $this->_label);
        $html .= '<input type="number" autocomplete="off" id="'. $id .'" name="'. $name .'" value="'. esc_attr($options->getOption($this->_id)) .'" '. $extra .' />';
        if (!empty($this->_params['error'])) {
            $html .= '<br><p class="error"> '  . $this->_params['error'] . '</p>';
        }
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

        $extra = '';
        $min = 1;
        if (isset($this->_params['min'])) {
            $min = $this->_params['min'];
        }
        $extra .= sprintf('min="%d" ', (int)$min);

        $max = 99;
        if (isset($this->_params['max'])) {
            $max = $this->_params['max'];
        }
        $extra .= sprintf('max="%d" ', (int)$max);

        $step = 1;
        if (isset($this->_params['step'])) {
            $step = $this->_params['step'];
        }
        $extra .= sprintf('step="%d" ', (int)$step);

        if (isset($this->_params['maxlength'])) {
            $extra .= sprintf('maxlength="%d" ', (int)$this->_params['maxlength']);
        }
        if (isset($this->_params['size'])) {
            $extra .= sprintf('size="%d" ', (int)$this->_params['size']);
        }
        if (isset($this->_params['placeholder'])) {
            $extra .= sprintf('placeholder="%s" ', htmlentities($this->_params['placeholder']));
        }

        $html = $this->_getOutputStart($id);
        $html .= '<input type="number" autocomplete="off" id="'. $id .'" name="'. $name .'" value="'. esc_attr($options->getOption($this->_id)) .'" '. $extra .' />';
        if (!empty($this->_params['error'])) {
            $html .= '<br><p class="error"> '  . $this->_params['error'] . '</p>';
        }
        if (!empty($this->_description)) {
            $html .= '<br><p class="description"> '  . $this->_description . '</p>';
        }
        $html .= $this->_getOutputEnd();
        echo $html;
    }
}
