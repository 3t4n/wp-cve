<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 * Options field text
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: MultiSelect.php 1850578 2018-03-31 19:27:09Z worschtebrot $
 */
require_once dirname(__FILE__) . '/../Field.php';

class IfwPsn_Wp_Options_Field_MultiSelect extends IfwPsn_Wp_Options_Field_Select
{
    protected $_html = '<select id="%s" name="%s" multiple>';

    /**
     * @param $id
     * @param $options
     * @return string
     */
    protected function _getName($id, $options)
    {
        return $options->getPageId() . '['. $id .'][]';
    }
}
