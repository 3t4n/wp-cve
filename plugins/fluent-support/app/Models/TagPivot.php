<?php

namespace FluentSupport\App\Models;

class TagPivot extends Model
{
    protected $table = 'fs_tag_pivot';

    protected $fillable = ['tag_id', 'source_id', 'source_type'];
}
