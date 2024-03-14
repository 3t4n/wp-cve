<?php

namespace FluentConnect\Framework\Database\Orm;

interface Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \FluentConnect\Framework\Database\Orm\Builder  $builder
     * @param  \FluentConnect\Framework\Database\Orm\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model);
}
