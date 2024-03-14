<?php

namespace XCurrency\WpMVC\Database\Query;

use XCurrency\WpMVC\Database\Eloquent\Model;
class JoinClause extends Builder
{
    /**
     * The type of join being performed.
     *
     * @var string
     */
    public $type;
    /**
     * The table the join clause is joining to.
     *
     * @var string
     */
    public $table;
    public array $ons = [];
    /**
     * Create a new join clause instance.
     * 
     * @param  string  $table
     * @param  string  $type
     * @return void
     */
    public function __construct(string $table, string $type, Model $model)
    {
        parent::__construct($model);
        $table = \explode(' as ', $table);
        $this->from($table[0], isset($table[1]) ? $table[1] : null);
        $this->type = $type;
    }
    /**
     * Add a basic on clause to the query.
     *
     * @param  string  $column
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return $this
     */
    public function on(string $column, $operator = null, $value = null)
    {
        $this->ons[] = $this->where($column, $operator, $value, 'and', \true);
        return $this;
    }
    /**
     * Add an "or on" clause to the query.
     *
     * @param  Closure|array|string|array  $column
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return $this
     */
    public function or_on($column, $operator = null, $value = null)
    {
        $this->ons[] = $this->or_where($column, $operator, $value, \true);
        return $this;
    }
    /**
     * Add a "on column" clause comparing two columns to the query.
     * 
     * @param  string  $first_column
     * @param  mixed  $operator
     * @param  mixed  $second_column
     * @return $this
     */
    public function on_column($first_column, $operator = null, $second_column = null)
    {
        $this->ons[] = $this->where_column($first_column, $operator, $second_column, 'and', \true);
        return $this;
    }
    /**
     * Add a "or on column" clause comparing two columns to the query.
     * 
     * @param  string  $first_column
     * @param  mixed  $operator
     * @param  mixed  $second_column
     * @return $this
     */
    public function or_on_column($first_column, $operator = null, $second_column = null)
    {
        $this->ons[] = $this->or_where_column($first_column, $operator, $second_column, \true);
        return $this;
    }
    /**
     * Add a "on in" clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return $this
     */
    public function on_in(string $column, array $values)
    {
        $this->ons[] = $this->where_in($column, $values, 'and', \false, \true);
        return $this;
    }
    /**
     * Add a or "on in" clause to the query.
     *
     * @param  string  $column
     * @param  array  $values
     * @return $this|array
     */
    public function or_on_in(string $column, array $values)
    {
        $this->ons[] = $this->or_where_in($column, $values, \true);
        return $this;
    }
    /**
     * Add where raw query
     *
     * @param string $sql
     * @return $this|array
     */
    public function on_raw(string $sql)
    {
        $this->ons[] = $this->where_raw($sql, 'and', \true);
        return $this;
    }
    /**
     * Add or on raw query
     *
     * @param string $sql
     * @return $this
     */
    public function or_on_raw(string $sql)
    {
        $this->ons[] = $this->or_where_raw($sql, \true);
        return $this;
    }
}
