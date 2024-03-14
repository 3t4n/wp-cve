<?php
require_once __DIR__ . '/../abstract.php';

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Lib_Rest_Api_Sale_Offers extends GJMAA_Lib_Rest_Api_Abstract
{

    protected $request;

    protected $auctionId;

    protected $headerAccept = 'application/vnd.allegro.public.v1+json';

    public function getMethod()
    {
        return 'GET';
    }

    public function setAuctionId($auctionId)
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    public function prepareRequest()
    {
        return $this->request;
    }

    public function parseResponse($response)
    {
        return $response;
    }

    public function getUrl()
    {
        if(!$this->auctionId){
            return '/sale/offers';
        }

        return '/sale/product-offers/'.$this->auctionId;
    }

    public function setOffset($offset = 0)
    {
        $this->request['offset'] = $offset;
    }

    public function setLimit($limit = 20)
    {
        $this->request['limit'] = $limit;
    }

    public function setCategoryId($categoryId)
    {
    	$this->request['category.id'] = $categoryId;
    }

    public function setSellingMode($sellingMode = [])
    {
        $this->request['sellingMode.format'] = $sellingMode;
    }

    public function setSort($sort)
    {
        $sortData = explode('_',$sort);

        if(count($sortData) > 1){
            list($sortName, $sortOrder) = $sortData;
        } else {
            $sortName = $sortData[0];
			$sortOrder = 'asc';
        }

        switch($sortName){
            case 'startingTime':
                $sortName = 'startTime';
                break;
            case 'endingTime':
                $sortName = 'endTime';
                break;
            case 'deliveryPrice':
                $sortName = 'withDeliveryPrice';
                break;
        }

        $restSort = $sortOrder == 'asc' ? '' : '-';
        $restSort .= $sortName;

        $this->request['sort'] = $restSort;
    }

    public function parseError($result, $url = '/', $httpCode = 500)
    {
        try {
            parent::parseError($result, $url, $httpCode);
        } catch (Requests_Exception_HTTP_404 $exception) {
            return [
                'success' => false,
                'to_delete' => true
            ];
        }
    }
}
