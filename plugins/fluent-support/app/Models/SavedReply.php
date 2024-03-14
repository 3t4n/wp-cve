<?php

namespace FluentSupport\App\Models;

class SavedReply extends Model
{
    protected $table = 'fs_saved_replies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by',
        'product_id',
        'title',
        'content'
    ];

    /**
     * $searchable Columns in table to search
     * @var array
     */
    protected $searchable = [
        'title',
        'content'
    ];

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param string $search
     * @return ModelQueryBuilder
     */
    public function scopeSearchBy($query, $search)
    {
        if ($search) {
            $fields = $this->searchable;
            $query->where(function ($query) use ($fields, $search) {
                $query->where(array_shift($fields), 'LIKE', "%$search%");

                foreach ($fields as $field) {
                    $query->orWhere($field, 'LIKE', "$search%");
                }
            });
        }

        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param array $statuses
     * @return ModelQueryBuilder
     */
    public function scopeProductBy($query, $productId)
    {
        if ($productId) {
            $query->where('product_id', $productId);
        }

        return $query;
    }

    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function person()
    {
        $class = __NAMESPACE__ . '\Person';

        return $this->belongsTo(
            $class, 'created_by', 'id'
        );
    }

    /**
     * One2Many: Customer has to many Click Tickets
     * @return Model Collection
     */
    public function product()
    {
        $class = __NAMESPACE__ . '\Product';

        return $this->belongsTo(
            $class, 'product_id', 'id'
        );
    }

}
