<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options button
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Button.php 592 2018-05-07 12:03:19Z timoreithde $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_Upload extends IfwPsn_Wp_Options_Field
{
    public function render(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

//        $format = '<form method="post" action="%s" class="%s"><input type="file" id="%s" name="%s"><input type="submit"></form>';
        $format = '<input type="file" id="%s" name="%s"><input type="submit" class="button-primary">';

        $action = $this->_params['action'];
        $text = $this->_params['text'];

        if (isset($this->_params['id'])) {
            $id = $this->_params['id'];
        } else {
            $id = $name;
        }

        if (isset($this->_params['filename'])) {
            $filename = $this->_params['filename'];
        } else {
            $filename = 'filename';
        }

        if (isset($this->_params['class'])) {
            $extraClass = ' '.$this->_params['class'];
        } else {
            $extraClass = '';
        }

        $extra = array();
        if (!empty($this->_params['data']) && is_array($this->_params['data'])) {
            foreach ($this->_params['data'] as $k => $v) {
                array_push($extra, sprintf('data-%s="%s"', htmlspecialchars($k), htmlspecialchars($v)));
            }
        }

        if (!empty($extra)) {
            $extra = implode(' ', $extra);
            $extra = ' ' . $extra;
        } else {
            $extra = '';
        }

        $output = $this->_getOutputStart($id);
        $output .= sprintf('<p><b class="option-name">%s</b></p>', $this->_label);
//        $output .= sprintf($format, $action, $extraClass, $id, $filename);
//        $output .= sprintf($format, $id, $filename);
//        $output .= sprintf($format, $id, $id);
        $output .= sprintf($format, $this->_id, $this->_id);

        if (!empty($this->_params['error'])) {
            $output .= '<br><p class="error"> '  . $this->_params['error'] . '</p>';
        }
        if (!empty($this->_description)) {
            $output .= '<br><p class="description"> '  . $this->_description . '</p>';
        }



        $output .= $this->_getOutputEnd();
        echo $output;
    }

    public function render2(array $params)
    {
        /**
         * @var IfwPsn_Wp_Options
         */
        $options = $params[0];

        $id = $options->getOptionRealId($this->_id);
        $name = $options->getPageId() . '['. $id .']';

//        $format = '<form method="post" action="%s" class="%s"><input type="file" id="%s" name="%s"><input type="submit"></form>';
        $format = '<input type="file" id="%s" name="%s"><input type="submit" class="button-primary">';

        $action = $this->_params['action'];
        $text = $this->_params['text'];

        if (isset($this->_params['id'])) {
            $id = $this->_params['id'];
        } else {
            $id = $name;
        }

        if (isset($this->_params['filename'])) {
            $filename = $this->_params['filename'];
        } else {
            $filename = 'filename';
        }

        if (isset($this->_params['class'])) {
            $extraClass = ' '.$this->_params['class'];
        } else {
            $extraClass = '';
        }

        $extra = array();
        if (!empty($this->_params['data']) && is_array($this->_params['data'])) {
            foreach ($this->_params['data'] as $k => $v) {
                array_push($extra, sprintf('data-%s="%s"', htmlspecialchars($k), htmlspecialchars($v)));
            }
        }

        if (!empty($extra)) {
            $extra = implode(' ', $extra);
            $extra = ' ' . $extra;
        } else {
            $extra = '';
        }

        $output = $this->_getOutputStart($id);
//        $output .= sprintf($format, $action, $extraClass, $id, $filename);
//        $output .= sprintf($format, $id, $filename);
//        $output .= sprintf($format, $id, $id);
        $output .= sprintf($format, $this->_id, $this->_id);

        if (!empty($this->_params['error'])) {
            $output .= '<br><p class="error"> '  . $this->_params['error'] . '</p>';
        }
        if (!empty($this->_description)) {
            $output .= '<br><p class="description"> '  . $this->_description . '</p>';
        }



        $output .= $this->_getOutputEnd();
        echo $output;
    }
}
