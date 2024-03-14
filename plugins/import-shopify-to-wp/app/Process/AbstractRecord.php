<?php

namespace S2WPImporter\Process;

use S2WPImporter\Traits\ErrorTrait;

abstract class AbstractRecord
{
    use ErrorTrait;

    public function beforeSave()
    {
        // Implement if required
    }

    public function afterSave($objId)
    {
        // Implement if required
    }

}
