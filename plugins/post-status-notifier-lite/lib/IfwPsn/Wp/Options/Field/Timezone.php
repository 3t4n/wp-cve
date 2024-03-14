<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field checkbox
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Checkbox.php 708 2023-04-23 11:30:22Z timoreithde $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_Timezone extends IfwPsn_Wp_Options_Field
{
    protected $_html = '<select id="%s" name="%s">';

    protected $_removeOptionsUTC = false;

    public function render(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

        $selectDefault = $this->_params['optionsDefault'] ?? null;

        $html = $this->_getOutputStart($id, 'opt-type-select');

        $html .= sprintf('<p><b class="option-name">%s</b></p>',
            ($this->hasLabelIcon() ? $this->getLabelIcon() : '') . $this->_label);

        $html .= sprintf($this->_html, $id, $name);

        if (!empty($selectDefault)) {
            $html .= '<option selected="selected" value="">' . __('Select a city') . '</option>';
        }

        $selectOptions = wp_timezone_choice($selectDefault);
        $optionsHtml = '';

        if ($this->_removeOptionsUTC) {
            $lines = preg_split("/((\r?\n)|(\r\n?))/", $selectOptions);
            foreach($lines as $line){
                // do something with $line
                if (!str_contains($line, 'value="UTC')) {
                    $optionsHtml .= $line . PHP_EOL;
                }
            }

            $pattern = "/<optgroup[^>]*>[\n\r]+<\/optgroup>/";
            $replacement = "";
            $optionsHtml = preg_replace($pattern, $replacement, $optionsHtml);

        } else {
            $optionsHtml = $selectOptions;
        }

        $html .= $optionsHtml;

        $html .= '</select>';

        if (!empty($this->_description)) {
            //$html .= '<label for="'. $id .'"> '  . $this->_description . '</label>';
            $html .= '<br><p class="description"> ' . $this->_description . '</p>';
        }
        $html .= $this->_getOutputEnd();

        echo $html;
    }

}
