<?php

namespace DominoKitApp\Backend\Package;

class DominokitWebZhaket
{
    /**
     * @var
     */
    protected $market;
    /**
     * @var
     */
    protected $store;

    /**
     * DominokitWebZhaket constructor.
     * @param $market
     * @param $store
     */
    public function __construct($market, $store)
    {
        $this->market = ucwords($market);
        $this->store = $store;
    }

    /**
     * @return mixed|string
     */
    protected function getCurl()
    {
        $post_field = [
            'market' => $this->market,
            'slug' => $this->store
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://api.zhaket.com/public/user/store/products');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_field));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = json_decode(curl_exec($curl));

        if (curl_errno($curl)) {
            return curl_error($curl);
        }

        curl_close($curl);

        if ($response->success !== true) {
            return "اتصال با سایت ژاکت برقرار نشد";
        }

        return $response;
    }

    /**
     * @return array
     * get product in website zhaket.com
     */
    public function getProduct()
    {
        $response = $this->getCurl();
        $products = [];
        foreach ($response->payload as $key => $product) {
            $products[$key]['title'] = $product->title;
            $products[$key]['thumb'] = $product->thumbnail->handler->thumb;
            $products[$key]['link'] = 'https://www.zhaket.com/web/' . $product->slug;
            $products[$key]['description'] = $product->short_description;
            $products[$key]['price'] = number_format($product->regular_price, 0, '.', ',');
        }

        return $products;
    }
}
