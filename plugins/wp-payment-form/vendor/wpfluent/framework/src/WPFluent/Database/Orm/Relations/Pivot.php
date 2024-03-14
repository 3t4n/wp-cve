<?php

namespace WPPayForm\Framework\Database\Orm\Relations;

use WPPayForm\Framework\Database\Orm\Model;
use WPPayForm\Framework\Database\Orm\Relations\Concerns\AsPivot;

class Pivot extends Model
{
    use AsPivot;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
