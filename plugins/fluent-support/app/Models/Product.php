<?php

namespace FluentSupport\App\Models;

class Product extends Model
{
    protected $table = 'fs_products';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_uid',
        'title',
        'description',
        'settings',
        'source',
        'created_by'
    ];

    protected $searchable = ['title', 'description'];

    /**
     * Local scope to filter products by search/query string
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
     * This `getProducts` method will return the list of products
     * @param string $search
     * @return array
     */
    public function getProducts ( $search )
    {
        return [
          'products' => $this->orderBy('id', 'DESC')->searchBy($search)->paginate()
        ];
    }

    /**
     * This `getProduct` method will get product by id and return
     * @param int $productId
     * @return array
     */
    public function getProduct ( $productId )
    {
        return [
          'product' => $this->findOrFail($productId)
        ];
    }

    /**
     * This `createProduct` method will create a new product
     * @param array $data
     * @return array
     */
    public function createProduct ( $data )
    {
        $data = wp_unslash($data);
        $product = $this->create($data);

        return [
            'message' => __('Product has been successfully created', 'fluent-support'),
            'product' => $product
        ];
    }

    /**
     * This `updateProduct` method will update an exiting product by id
     * @param int $productId
     * @param array $data
     * @return array
     */
    public function updateProduct ( $productId, $data )
    {
        $data = wp_unslash($data);
        $product = $this->findOrFail($productId);
        $product->fill($data);
        $product->save();

        return [
            'message' => __('Product has been updated', 'fluent-support'),
            'product' => $product
        ];
    }

    /**
     * This `deleteProduct` method will delete an exiting product by id
     * @param int $productId
     * @return array
     */
    public function deleteProduct ( $productId )
    {
        $this->where('id', $productId)->delete();

        return [
            'message' => __('Product has been deleted', 'fluent-support')
        ];
    }

}
