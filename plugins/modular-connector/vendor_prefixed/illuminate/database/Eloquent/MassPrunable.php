<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\Eloquent;

use Modular\ConnectorDependencies\Illuminate\Database\Events\ModelsPruned;
use LogicException;
/** @internal */
trait MassPrunable
{
    /**
     * Prune all prunable models in the database.
     *
     * @param  int  $chunkSize
     * @return int
     */
    public function pruneAll(int $chunkSize = 1000)
    {
        $query = \Modular\ConnectorDependencies\tap($this->prunable(), function ($query) use($chunkSize) {
            $query->when(!$query->getQuery()->limit, function ($query) use($chunkSize) {
                $query->limit($chunkSize);
            });
        });
        $total = 0;
        do {
            $total += $count = \in_array(SoftDeletes::class, class_uses_recursive(\get_class($this))) ? $query->forceDelete() : $query->delete();
            if ($count > 0) {
                event(new ModelsPruned(static::class, $total));
            }
        } while ($count > 0);
        return $total;
    }
    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        throw new LogicException('Please implement the prunable method on your model.');
    }
}
