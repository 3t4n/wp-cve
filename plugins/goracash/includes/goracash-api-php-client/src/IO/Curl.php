<?php
/**
 * Copyright 2015 Goracash
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Goracash\IO;

use Goracash\Client as Client;
use Goracash\Http\Request as Request;
use Goracash\Http\Response;

class Curl extends Primary
{
    // cURL hex representation of version 7.30.0
    const NO_QUIRK_VERSION = 0x071E00;

    private $options = array();

    /*
     * @param Client
     */
    public function __construct(Client $client)
    {
        if (!extension_loaded('curl')) {
            $error = 'The cURL IO handler requires the cURL extension to be enabled';
            $client->getLogger()->critical($error);
            throw new Exception($error);
        }

        parent::__construct($client);
    }

    /**
     * Execute an HTTP Request
     *
     * @param Request $request the http request to be executed
     * @return array containing response headers, body, and http code
     * @throws Exception on curl or IO error
     */
    public function executeRequest(Request $request)
    {
        $curl = curl_init();

        if ($request->getPostBody()) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getPostBody());
        }

        $requestHeaders = $request->getRequestHeaders();
        if ($requestHeaders && is_array($requestHeaders)) {
            $curlHeaders = array();
            foreach ($requestHeaders as $k => $v) {
                $curlHeaders[] = "$k: $v";
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeaders);
        }
        curl_setopt($curl, CURLOPT_URL, $request->getUrl());

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getRequestMethod());
        curl_setopt($curl, CURLOPT_USERAGENT, $request->getUserAgent());

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        // 1 is CURL_SSLVERSION_TLSv1, which is not always defined in PHP.
        curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);

        if ($request->canGzip()) {
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        }

        $options = $this->client->getClassConfig('Goracash\IO\Curl', 'options');
        if (is_array($options)) {
            $this->setOptions($options);
        }

        foreach ($this->options as $key => $var) {
            curl_setopt($curl, $key, $var);
        }

        $this->client->getLogger()->debug(
            'cURL request',
            array(
                'url' => $request->getUrl(),
                'method' => $request->getRequestMethod(),
                'headers' => $requestHeaders,
                'body' => $request->getPostBody()
            )
        );

        $result = curl_exec($curl);
        if ($result === false) {
            $error = curl_error($curl);
            $code = curl_errno($curl);
            $this->client->getLogger()->error('cURL ' . $error);
            throw new Exception($error, $code);
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        list($responseHeaders, $responseBody) = $this->parseHttpResponse($result, $headerSize);

        $response = new Response();
        $response->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response->url = $request->getUrl();
        $response->method = $request->getRequestMethod();
        $response->headers = $responseHeaders;
        $response->body = $responseBody;
        $response->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response->setContentType(curl_getinfo($curl, CURLINFO_CONTENT_TYPE));

        $this->client->getLogger()->debug(
            'cURL response',
            array(
                'code' => $response->code,
                'headers' => $response->headers,
                'body' => $response->body,
            )
        );
        return $response;
    }

    /**
     * Set options that update the transport implementation's behavior.
     * @param $options
     */
    public function setOptions($options)
    {
        $this->options = $options + $this->options;
    }

    /**
     * Set the maximum request time in seconds.
     * @param $timeout in seconds
     */
    public function setTimeout($timeout)
    {
        // Since this timeout is really for putting a bound on the time
        // we'll set them both to the same. If you need to specify a longer
        // CURLOPT_TIMEOUT, or a higher CONNECTTIMEOUT, the best thing to
        // do is use the setOptions method for the values individually.
        $this->options[CURLOPT_CONNECTTIMEOUT] = $timeout;
        $this->options[CURLOPT_TIMEOUT] = $timeout;
    }

    /**
     * Get the maximum request time in seconds.
     * @return timeout in seconds
     */
    public function getTimeout()
    {
        return $this->options[CURLOPT_TIMEOUT];
    }

    /**
     * Test for the presence of a cURL header processing bug
     *
     * {@inheritDoc}
     *
     * @return boolean
     */
    protected function needsQuirk()
    {
        $ver = curl_version();
        $versionNum = $ver['version_number'];
        return $versionNum < Curl::NO_QUIRK_VERSION;
    }
}