<?php

namespace WPPayForm\Framework\Database\Concerns;

use WPPayForm\Framework\Support\Collection;

trait ExplainsQueries
{
    /**
     * Explains the query.
     *
     * @return \WPPayForm\Framework\Support\Collection
     */
    public function explain()
    {
        $sql = $this->toSql();

        $bindings = $this->getBindings();

        $explanation = $this->getConnection()->select('EXPLAIN '.$sql, $bindings);

        return new Collection($explanation);
    }
}
