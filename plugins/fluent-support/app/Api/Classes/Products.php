<?php

namespace FluentSupport\App\Api\Classes;

use FluentSupport\App\Models\Product;

/**
 *  Products class for PHP API
 *  Example Usage: $productApi = FluentSupportApi('products');
 *
 * @package FluentSupport\App\Api\Classes
 *
 * @version 1.0.0
 */
class Products
{
    private $instance = null;

    private $allowedInstanceMethods = [
        'all',
        'get',
        'find',
        'first',
        'paginate'
    ];

    public function __construct(Product $instance)
    {
        $this->instance = $instance;
    }

    /**
     * getProducts method will return all available products
     */
    public function getProducts()
    {
        Product::paginate();
    }

    /**
     * getProduct method returns a specific product by id
     * @param int $id
     */
    public function getProduct(int $id)
    {
        if (!$id) {
            return;
        }

        Product::findOrFail($id);
    }

    /**
     * createProduct method will create a new product
     * @param array $data
     */
    public function createProduct(array $data)
    {
        if (empty($data['title'])) {
            return;
        }
        Product::create(wp_unslash($data));
    }

    /**
     * updateProduct method will update product by id
     * @param int $id
     * @param array $data
     */
    public function updateProduct(int $id, array $data)
    {
        if (!$id || !$data) {
            return;
        }
        Product::findOrFail($id)->update($data);
    }

    /**
     * deleteProduct method will delete product by id
     * @param int $id
     */
    public function deleteProduct(int $id)
    {
        if (!$id) {
            return;
        }
        Product::findOrFail($id)->delete();
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function __call($method, $params)
    {
        if (in_array($method, $this->allowedInstanceMethods)) {
            return call_user_func_array([$this->instance, $method], $params);
        }

        throw new \Exception("Method {$method} does not exist.");
    }
}
