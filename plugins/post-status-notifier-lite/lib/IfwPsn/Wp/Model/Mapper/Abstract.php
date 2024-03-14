<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @version   $Id: Abstract.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package   
 */
abstract class IfwPsn_Wp_Model_Mapper_Abstract implements IfwPsn_Wp_Model_Mapper_Interface
{
    /**
     * @var array
     */
    protected static $_modelBuffer = array();

    /**
     * @param string $prefix
     * @param string $itemName
     * @return array
     */
    public function getExportOptions($prefix = '', $itemName = '')
    {
        if (!empty($itemName)) {
            $itemName = '_' . $itemName;
        }

        return array(
            'node_name_plural' => sprintf('%s%s', $prefix, $this->getPlural()),
            'node_name_singular' => sprintf('%s%s', $prefix, $this->getSingular()),
            'filename' => sprintf('%s%s%s_%s', $prefix, $this->getSingular(), $itemName, date('Y-m-d_H_i_s')),
            'filename_bundle' => sprintf('%s%s_bundle_%s', $prefix, $this->getPlural(), date('Y-m-d_H_i_s'))
        );
    }

    /**
     * @param $obj
     * @return bool
     */
    public static function existsByData($obj)
    {
        $result = false;
        $modelClassname = get_class($obj);

        if (!empty($modelClassname)) {
            $model = IfwPsn_Wp_ORM_Model::factory($modelClassname);

            foreach ($obj->as_array() as $k => $v) {
                $model->where($k, $v);
            }

            $result = $model->find_one() !== false;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return IfwPsn_Wp_ORM_Model::factory($this->getModelName())->find_array();
    }

    /**
     * @param string $prefix
     * @return string
     */
    public function getPerPageId($prefix = '')
    {
        return $prefix . $this->getPlural() . '_per_page';
    }
}
