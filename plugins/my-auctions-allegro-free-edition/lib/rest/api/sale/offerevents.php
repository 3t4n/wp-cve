<?php
declare(strict_types=1);

require_once __DIR__ . '/../abstract.php';

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Lib_Rest_Api_Sale_Offerevents extends GJMAA_Lib_Rest_Api_Abstract
{

    protected $from = null;

    protected $limit;

    protected $types;

    protected $headerAccept = 'application/vnd.allegro.public.v1+json';

    public function getFrom() : ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from = null) : self
    {
        $this->from = $from;

        return $this;
    }

    public function getLimit() : int
    {
        return $this->limit;
    }

    public function setLimit(int $limit) : self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getTypes() : array
    {
        return $this->types;
    }

    public function setTypes(array $types) : self
    {
        $this->types = $types;

        return $this;
    }

    public function prepareRequest()
    {
        $request = [
            'limit' => $this->getLimit(),
            'type' => $this->getTypes()
        ];

        if($from = $this->getFrom()) {
            $request['from'] = $from;
        }

        return $request;
    }

    public function parseResponse($response)
    {
        return $response['offerEvents'];
    }

    public function getUrl()
    {
        return '/sale/offer-events';
    }

    public function getMethod()
    {
        return 'GET';
    }
}