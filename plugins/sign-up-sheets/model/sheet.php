<?php
/**
 * Sheet Model
 */

namespace FDSUS\Model;

use FDSUS\Id;
use WP_Post;

if (Id::isPro()) {
    class SheetParent extends Pro\Sheet {}
} else {
    class SheetParent extends SheetBase {}
}

class Sheet extends SheetParent
{
    /**
     * Constructor
     *
     * @param int|WP_Post $id
     * @param bool        $tryV20First try getting by the old v2.0 ID first
     */
    public function __construct($id, $tryV20First = false)
    {
        parent::__construct($id, $tryV20First);
    }
}
