<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Http\Requests\ProductRequest;
use FluentSupport\App\Models\Product;
use FluentSupport\Framework\Request\Request;

class ProductController extends Controller
{
    /**
     * index method will return the list of product
     * @param Request $request
     * @param Product $product
     * @return array
     */
    public function index ( Request $request, Product $product )
    {
      return $product->getProducts( $request->getSafe('search', 'sanitize_text_field') );
    }

    /**
     * get method will get product by id and return
     * @param Product $product
     * @param int $product_id
     * @return array
     */
    public function get ( Product $product, $product_id )
    {
        return $product->getProduct( $product_id );
    }

    /**
     * creare method will create new product
     * @param Request $request
     * @param Product $product
     * @return array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function create ( ProductRequest $request, Product $product )
    {
        $data = $request->all();

        return $product->createProduct( $data );
    }


    /**
     * update methd will update an exiting product by id
     * @param Request $request
     * @param Product $product
     * @param int $product_id
     * @return array
     * @throws \FluentSupport\Framework\Validator\ValidationException
     */
    public function update ( ProductRequest  $request, Product $product, $product_id )
    {
        $data = $request->all();

        return $product->updateProduct( $product_id, $data );
    }

    /**
     * delete method will delete an existing product by id
     * @param Product $product
     * @param int $product_id
     * @return array
     */
    public function delete ( Product $product, $product_id )
    {
        return $product->deleteProduct( $product_id );
    }
}
