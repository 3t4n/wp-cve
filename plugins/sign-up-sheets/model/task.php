<?php
/**
 * Task Model
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Sheet as SheetModel;
use WP_Post;

if (Id::isPro()) {
    class TaskParent extends Pro\Task {}
} else {
    class TaskParent extends TaskBase {}
}

class Task extends TaskParent
{
    /**
     * Constructor
     *
     * @param int|WP_Post     $taskId id or post object
     * @param null|SheetModel $sheet
     */
    public function __construct($taskId = 0, $sheet = null)
    {
        parent::__construct($taskId, $sheet);
    }
}
