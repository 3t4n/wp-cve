<?php
/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Services;

class CurlConnection
{
    const CHARSET = 'utf-8', ENCODING_IN = 'application/json', ENCODING_OUT = 'application/x-www-form-urlencoded', USER_AGENT = 'CashBill SDK REQUEST';
    /**
     *
     * @var string $endpoint
     */
    private $endpoint;
    
    /**
     *
     * @staticvar cURL $ch
     */
    private static $ch;
    
    /**
     *
     * @param string $endpoint
     */
    public function __construct($endpoint)
    {
        $this->setEndpoint($endpoint);
    }
    
    /**
     *
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }
    public function prepareRequest()
    {
        $headers = array(
                "Accept: " . self::ENCODING_IN,
                "Content-type: " . self::ENCODING_OUT . ';charset=' . self::CHARSET
        );
        
        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt(self::$ch, CURLOPT_USERAGENT, self::USER_AGENT);
        curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    /**
     *
     * @param string $url
     * @param array $args
     * @return mixed
     */
    public function get($url, $args = array())
    {
        return $this->request($url, "GET", $args);
    }
    
    /**
     *
     * @param string $url
     * @param array $args
     * @return mixed
     */
    public function post($url, $args = array())
    {
        return $this->request($url, "POST", $args);
    }
    
    /**
     *
     * @param string $url
     * @param array $args
     * @return mixed
     */
    public function put($url, $args = array())
    {
        return $this->request($url, "PUT", $args);
    }
    
    /**
     *
     * @param string $url
     * @param string $type
     * @param array $args
     * @throws CashBillCurlException
     * @return mixed
     */
    private function request($url, $type, $args)
    {
        self::$ch = curl_init();
        $this->prepareRequest();
        switch ($type) {
            
            case "GET":
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "GET");
                $url .= '?' . http_build_query($args);
                break;
            
            case "POST":
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($args));
                break;
            
            case "PUT":
                curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($args));
                break;
        }
        curl_setopt(self::$ch, CURLOPT_URL, $this->endpoint . $url);
        
        $response = curl_exec(self::$ch);
        
        $httpCode = curl_getinfo(self::$ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode !== 200 && $httpCode !== 204) {
            throw new CashBillCurlException(strip_tags($response), $httpCode);
        }
        curl_close(self::$ch);
        
        return json_decode($response);
    }
}
