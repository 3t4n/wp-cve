<?php

namespace UpsFreeVendor\GuzzleHttp\Exception;

use UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface;
use UpsFreeVendor\Psr\Http\Message\RequestInterface;
use UpsFreeVendor\Psr\Http\Message\ResponseInterface;
use UpsFreeVendor\Psr\Http\Message\UriInterface;
/**
 * HTTP Request exception
 */
class RequestException extends \UpsFreeVendor\GuzzleHttp\Exception\TransferException
{
    /** @var RequestInterface */
    private $request;
    /** @var ResponseInterface|null */
    private $response;
    /** @var array */
    private $handlerContext;
    public function __construct($message, \UpsFreeVendor\Psr\Http\Message\RequestInterface $request, \UpsFreeVendor\Psr\Http\Message\ResponseInterface $response = null, \Exception $previous = null, array $handlerContext = [])
    {
        // Set the code of the exception if the response is set and not future.
        $code = $response && !$response instanceof \UpsFreeVendor\GuzzleHttp\Promise\PromiseInterface ? $response->getStatusCode() : 0;
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
        $this->handlerContext = $handlerContext;
    }
    /**
     * Wrap non-RequestExceptions with a RequestException
     *
     * @param RequestInterface $request
     * @param \Exception       $e
     *
     * @return RequestException
     */
    public static function wrapException(\UpsFreeVendor\Psr\Http\Message\RequestInterface $request, \Exception $e)
    {
        return $e instanceof \UpsFreeVendor\GuzzleHttp\Exception\RequestException ? $e : new \UpsFreeVendor\GuzzleHttp\Exception\RequestException($e->getMessage(), $request, null, $e);
    }
    /**
     * Factory method to create a new exception with a normalized error message
     *
     * @param RequestInterface  $request  Request
     * @param ResponseInterface $response Response received
     * @param \Exception        $previous Previous exception
     * @param array             $ctx      Optional handler context.
     *
     * @return self
     */
    public static function create(\UpsFreeVendor\Psr\Http\Message\RequestInterface $request, \UpsFreeVendor\Psr\Http\Message\ResponseInterface $response = null, \Exception $previous = null, array $ctx = [])
    {
        if (!$response) {
            return new self('Error completing request', $request, null, $previous, $ctx);
        }
        $level = (int) \floor($response->getStatusCode() / 100);
        if ($level === 4) {
            $label = 'Client error';
            $className = \UpsFreeVendor\GuzzleHttp\Exception\ClientException::class;
        } elseif ($level === 5) {
            $label = 'Server error';
            $className = \UpsFreeVendor\GuzzleHttp\Exception\ServerException::class;
        } else {
            $label = 'Unsuccessful request';
            $className = __CLASS__;
        }
        $uri = $request->getUri();
        $uri = static::obfuscateUri($uri);
        // Client Error: `GET /` resulted in a `404 Not Found` response:
        // <html> ... (truncated)
        $message = \sprintf('%s: `%s %s` resulted in a `%s %s` response', $label, $request->getMethod(), $uri, $response->getStatusCode(), $response->getReasonPhrase());
        $summary = static::getResponseBodySummary($response);
        if ($summary !== null) {
            $message .= ":\n{$summary}\n";
        }
        return new $className($message, $request, $response, $previous, $ctx);
    }
    /**
     * Get a short summary of the response
     *
     * Will return `null` if the response is not printable.
     *
     * @param ResponseInterface $response
     *
     * @return string|null
     */
    public static function getResponseBodySummary(\UpsFreeVendor\Psr\Http\Message\ResponseInterface $response)
    {
        return \UpsFreeVendor\GuzzleHttp\Psr7\get_message_body_summary($response);
    }
    /**
     * Obfuscates URI if there is a username and a password present
     *
     * @param UriInterface $uri
     *
     * @return UriInterface
     */
    private static function obfuscateUri(\UpsFreeVendor\Psr\Http\Message\UriInterface $uri)
    {
        $userInfo = $uri->getUserInfo();
        if (\false !== ($pos = \strpos($userInfo, ':'))) {
            return $uri->withUserInfo(\substr($userInfo, 0, $pos), '***');
        }
        return $uri;
    }
    /**
     * Get the request that caused the exception
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * Get the associated response
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * Check if a response was received
     *
     * @return bool
     */
    public function hasResponse()
    {
        return $this->response !== null;
    }
    /**
     * Get contextual information about the error from the underlying handler.
     *
     * The contents of this array will vary depending on which handler you are
     * using. It may also be just an empty array. Relying on this data will
     * couple you to a specific handler, but can give more debug information
     * when needed.
     *
     * @return array
     */
    public function getHandlerContext()
    {
        return $this->handlerContext;
    }
}
