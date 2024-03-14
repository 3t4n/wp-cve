<?php
namespace BDroppy\Services\Remote;

if (!defined('ABSPATH')) exit;


class OrderRemote extends BaseServer
{

    public function sendDropshippingOrder($data)
    {
        $this->headerAccept = 'application/xml';
        $this->headerContentType = 'application/xml';
        $response = $this->post('ghost/orders/0/dropshipping', $data);
        return $response;
    }

    public function getOrderStatusByKey($key)
    {
        $this->headerAccept = 'application/xml';
        $this->headerContentType = 'application/xml';
        $response = $this->get('ghost/clientorders/clientkey/' . $key);
        return $response;
    }

    public function sendOrderSoldLock($data)
    {
        $this->headerAccept = 'application/xml';
        $this->headerContentType = 'application/xml';
        $response = $this->post('ghost/orders/sold', $data);
        $this->logger->debug('ts',json_encode($response));
        return $response;
    }

    public function getOrderDropshippingLock()
    {
        $this->headerAccept = 'application/xml';
        $this->headerContentType = 'application/xml';
        $response = $this->get('ghost/orders/dropshipping/locked/');
        return $response;
    }


}