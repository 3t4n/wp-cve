<?php

namespace FluentSupport\App\Models;

class Meta extends Model
{
    protected $table = 'fs_meta';

    protected $fillable = ['object_id', 'object_type', 'key', 'value'];
}
