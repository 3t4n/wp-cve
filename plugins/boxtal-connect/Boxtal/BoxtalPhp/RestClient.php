<?php
namespace Boxtal\BoxtalPhp;

/**
 * @author boxtal <api@boxtal.com>
 * @copyright 2018 Boxtal
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * Class RestClient
 * @package Boxtal\BoxtalPhp
 *
 *  Facilitates REST calls.
 */
class RestClient
{

    /**
     * Access key.
     *
     * @var string
     */
    private $accessKey;

    /**
     * Secret key.
     *
     * @var string
     */
    private $secretKey;

    public static $GET = 'GET';
    public static $POST = 'POST';
    public static $PUT = 'PUT';
    public static $PATCH = 'PATCH';
    public static $DELETE = 'DELETE';

    /**
     * Construct function.
     *
     * @param string $accessKey access key.
     * @param string $secretKey secret key.
     * @void
     */
    public function __construct($accessKey, $secretKey)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    /**
     * Healthcheck
     *
     * @return boolean
     */
    public static function healthcheck()
    {
        return self::fopenHealthcheck() || self::curlHealthcheck();
    }

    /**
     * fopen healthcheck
     *
     * @return boolean
     */
    private static function fopenHealthcheck()
    {
        $ini = ini_get('allow_url_fopen');
        return '' !== $ini && false !== $ini && '0' !== $ini && 0 !== $ini;
    }

    /**
     * curl healthcheck
     *
     * @return boolean
     */
    private static function curlHealthcheck()
    {
        return extension_loaded('curl');
    }

    /**
     * API request
     *
     * @param string $method one of GET, POST, PUT, PATCH, DELETE.
     * @param string $url url for the request.
     * @param array $params array of params.
     * @param array $headers array of headers.
     * @param int $timeout timeout in seconds.
     * @return ApiResponse
     */
    public function request($method, $url, $params = array(), $headers = array(), $timeout = null)
    {

        $headers['Authorization'] = base64_encode($this->accessKey . ':' . $this->secretKey);
        $headers['Content-type'] = 'application/json; charset=UTF-8';

        if ($method === $this::$GET && !empty($params)) {
            if (false === strpos($url, '?')) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= http_build_query($params);
            $url = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $url);
        }

        if (self::fopenHealthcheck()) {
            $header = '';
            foreach ($headers as $key => $value) {
                $header .= $key . ': ' . $value ."\r\n";
            }

            $opts = array(
                'http' => array(
                    'method'  => $method,
                    'header'  => $header,
                    'content' => $method !== $this::$GET ? json_encode($params) : null
                )
            );

            if ($timeout !== null) {
                $opts['http']['timeout'] = $timeout;
            }

            $context = stream_context_create($opts);

            $stream = @fopen($url, 'r', false, $context);

            if (false === $stream) {
                $return = new ApiResponse(400, null);
            } else {
                if ($this->isFopenResponseContentTypeJson($http_response_header)) {
                    $response = json_decode(stream_get_contents($stream));
                } else {
                    $response = stream_get_contents($stream);
                }

                $return = new ApiResponse($this->getStreamStatus($stream), $response);

                fclose($stream);
            }

            return $return;
        } elseif (self::curlHealthcheck()) {
            $curl = curl_init();

            $opts = array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            );

            if (strpos($url, 'https') !== 0) {
                $opts[CURLOPT_SSL_VERIFYPEER] = false;
                $opts[CURLOPT_SSL_VERIFYHOST] = 0;
            } else {
                $opts[CURLOPT_SSL_VERIFYPEER] = true;
                $opts[CURLOPT_SSL_VERIFYHOST] = 2;
            }

            $headerArray = array();
            foreach ($headers as $key => $value) {
                $headerArray[] = $key . ': ' . $value;
            }
            $opts[CURLOPT_HTTPHEADER] = $headerArray;

            if ($method !== $this::$GET) {
                $opts[CURLOPT_CUSTOMREQUEST] = $method;
                if (!empty($params)) {
                    $opts[CURLOPT_POSTFIELDS] = json_encode($params);
                }
            }

            curl_setopt_array($curl, $opts);
            $result = curl_exec($curl);

            if (false === $result) {
                $return = new ApiResponse(400, null);
            } else {
                $response = $this->isCurlResponseContentTypeJson($curl) ? json_decode($result) : $result;
                $return = new ApiResponse($this->getCurlResponseStatus($curl), $response);
            }

            return $return;
        }
        return new ApiResponse(500, null);
    }

    /**
     * Get stream status
     *
     * @return string
     */
    private function getStreamStatus($stream)
    {
        $data = stream_get_meta_data($stream);
        $wrapperLines = $data['wrapper_data'];
        $matches = array();
        for ($i = count($wrapperLines); $i >= 1; $i--) {
            if (0 === strpos($wrapperLines[$i - 1], 'HTTP/1')) {
                preg_match('/(\d{3})/', $wrapperLines[$i - 1], $matches);
                break;
            }
        }
        return empty($matches) ? null : $matches[1];
    }

    /**
     * Check if fopen response content type is json
     *
     * @param array string response headers
     * @return boolean
     */
    private function isFopenResponseContentTypeJson($httpResponseHeaders)
    {
        $return = false;
        foreach ($httpResponseHeaders as $header) {
            if (-1 !== strpos('Content-Type: application/json', $header)) {
                $return = true;
            }
        }
        return $return;
    }

    /**
     * Check if curl response content type is json
     *
     * @param curl request
     * @return boolean
     */
    private function isCurlResponseContentTypeJson($curl)
    {

        $curlInfo = curl_getinfo($curl);
        $contentType = explode(';', $curlInfo['content_type']);

        $return = false;
        foreach ($contentType as $type) {
            if (-1 !== strpos('application/json', $type)) {
                $return = true;
            }
        }
        return $return;
    }

    /**
     * Get curl response status code
     *
     * @param curl request
     * @return boolean
     */
    private function getCurlResponseStatus($curl)
    {

        $curlInfo = curl_getinfo($curl);
        return $curlInfo['http_code'];
    }
}
