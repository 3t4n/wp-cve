<?php
/**
 * TaskTable
 */

namespace FDSUS\Model;

use FDSUS\Model\Sheet as SheetModel;

class TaskTable
{

    /** @var TaskTableRow[]  */
    public $rows = array();

    /** @var array array('slug' => 'some_slug', 'value' => 'Something for Display')  */
    public $header = array();

    /** @var SheetModel  */
    protected $sheet;

    /**
     * Constructor
     *
     * @param SheetModel $sheet
     */
    public function __construct($sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * Add row
     *
     * @param int    $signupId
     * @param int    $taskId
     * @param string $rowClass
     *
     * @return int|string|null
     */
    public function addRow($signupId, $taskId, $rowClass = '')
    {
        $row = new TaskTableRow($signupId, $taskId, $rowClass);
        $this->rows[] = $row;

        return $this->getLastRowKey();
    }

    /**
     * Add row cell
     *
     * @param int|string $rowKey  if 'auto', use getLastRowKey
     * @param string     $slug
     * @param string     $value
     * @param string     $class
     * @param int        $colspan
     * @param int        $rowspan
     * @param string     $element 'td' or 'th'
     */
    public function addRowCell($rowKey, $slug, $value, $class = '', $colspan = 1, $rowspan = 1, $element = 'td')
    {
        if ($rowKey === 'auto') {
            $rowKey = $this->getLastRowKey();
        }

        /**
         * Filter task table task cell slug
         *
         * @param string     $slug
         * @param string     $value
         * @param SheetModel $sheet
         * @param string     $class
         *
         * @return string
         * @since 2.2
         */
        $slug = apply_filters('fdsus_tasktable_task_cell_slug', $slug, $value, $this->sheet, $class);

        /**
         * Filter task table task cell value
         *
         * @param string     $value
         * @param string     $slug
         * @param SheetModel $sheet
         * @param string     $class
         *
         * @return string
         * @since 2.2
         */
        $value = apply_filters('fdsus_tasktable_task_cell_value', $value, $slug, $this->sheet, $class);

        if ($slug !== false) {
            $this->rows[$rowKey]->addCell($slug, $value, $class, $colspan, $rowspan, $element);
        }
    }

    /**
     * Add header cell
     *
     * @param string     $slug
     * @param string     $value
     * @param string     $class
     */
    public function addHeaderCell($slug, $value, $class = '')
    {
        /**
         * Filter task table header cell slug
         *
         * @param string     $slug
         * @param string     $value
         * @param SheetModel $sheet
         * @param string     $class
         *
         * @return string
         * @since 2.2
         */
        $slug = apply_filters('fdsus_tasktable_header_cell_slug', $slug, $value, $this->sheet, $class);

        if ($slug !== false) {
            $this->header[] = array(
                'slug'  => $slug,
                'value' => $value,
                'class' => $class,
            );
        }
    }

    /**
     * Get header count
     *
     * @return int
     */
    public function getHeaderCount()
    {
        return count($this->header);
    }

    /**
     * Get last key from an array
     *
     * @param array $array
     *
     * @return int|string|null|void
     */
    public function arrayKeyLast(array $array)
    {
        if (!empty($array)) {
            return key(array_slice($array, -1, 1, true));
        }
    }

    /**
     * Get last row key
     *
     * @return int|string|null
     */
    public function getLastRowKey()
    {
        return $this->arrayKeyLast($this->rows);
    }

}
