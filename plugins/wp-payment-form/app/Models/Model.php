<?php

namespace WPPayForm\App\Models;

use WPPayForm\Framework\Database\Orm\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = ['id', 'ID'];
}
