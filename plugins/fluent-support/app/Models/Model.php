<?php

namespace FluentSupport\App\Models;

use FluentSupport\Framework\Database\Orm\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded = ['id', 'ID'];

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getPerPage()
    {
        return (isset($_REQUEST['per_page'])) ? intval($_REQUEST['per_page']) : 15;
    }
}
