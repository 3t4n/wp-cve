<?php
/**
 * TaskTable
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Signup as SignupModel;

class TaskTableRow
{
    /** @var SignupModel */
    public $signupId;

    /** @var int  */
    public $taskId = 0;

    /** @var array  */
    public $cells = array();

    /** @var string  */
    public $class = '';

    /**
     * Constructor
     */
    public function __construct($signupId, $taskId, $class = '')
    {
        $this->signupId = $signupId;
        $this->taskId = $taskId;
        $this->class = $class;

        return $this;
    }

    /**
     * @param string $slug
     * @param string $value
     * @param string $class
     * @param int    $colspan
     * @param int    $rowspan
     * @param string $element 'td' or 'th'
     */
    public function addCell($slug, $value, $class = '', $colspan = 1, $rowspan = 1, $element = 'td')
    {
        $this->cells[] = array(
            'slug'    => $slug,
            'value'   => $value,
            'class'   => $class,
            'colspan' => (int)$colspan,
            'rowspan' => (int)$rowspan,
            'element' => $element,
        );
    }
}
