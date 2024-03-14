<?php

declare (strict_types=1);
namespace WpifyWooDeps\h4kuna\Ares\DataBox;

use WpifyWooDeps\h4kuna\Ares\Exceptions\ResultException;
use WpifyWooDeps\h4kuna\Ares\Exceptions\ServerResponseException;
use WpifyWooDeps\h4kuna\Ares\Http\TransportProvider;
use WpifyWooDeps\h4kuna\Ares\Tools\Xml;
use WpifyWooDeps\Psr\Http\Message\StreamInterface;
use stdClass;
/**
 * @see https://www.mojedatovaschranka.cz/sds/p/download/sds_webove_sluzby.pdf#view=Fit
 */
final class Client
{
    public static string $url = 'https://www.mojedatovaschranka.cz/sds/ws/call';
    public function __construct(private TransportProvider $requestProvider)
    {
    }
    public function request(StreamInterface $body) : stdClass
    {
        $request = $this->requestProvider->createXmlRequest(self::$url, $body);
        $response = $this->requestProvider->response($request);
        $data = Xml::toJson($response);
        if (isset($data->Message)) {
            throw new ResultException($data->Message);
        } elseif (isset($data->Osoba) === \false) {
            throw new ServerResponseException('No content');
        }
        return $data;
    }
}
