<?php

namespace FluentConnect\App\Models;

use FluentConnect\Framework\Database\Orm\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = ['id'];
}
