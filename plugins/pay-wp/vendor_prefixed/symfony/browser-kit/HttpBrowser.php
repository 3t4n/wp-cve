<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\BrowserKit;

use WPPayVendor\Symfony\Component\HttpClient\HttpClient;
use WPPayVendor\Symfony\Component\Mime\Part\AbstractPart;
use WPPayVendor\Symfony\Component\Mime\Part\DataPart;
use WPPayVendor\Symfony\Component\Mime\Part\Multipart\FormDataPart;
use WPPayVendor\Symfony\Component\Mime\Part\TextPart;
use WPPayVendor\Symfony\Contracts\HttpClient\HttpClientInterface;
/**
 * An implementation of a browser using the HttpClient component
 * to make real HTTP requests.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class HttpBrowser extends \WPPayVendor\Symfony\Component\BrowserKit\AbstractBrowser
{
    private $client;
    public function __construct(?\WPPayVendor\Symfony\Contracts\HttpClient\HttpClientInterface $client = null, ?\WPPayVendor\Symfony\Component\BrowserKit\History $history = null, ?\WPPayVendor\Symfony\Component\BrowserKit\CookieJar $cookieJar = null)
    {
        if (!$client && !\class_exists(\WPPayVendor\Symfony\Component\HttpClient\HttpClient::class)) {
            throw new \LogicException(\sprintf('You cannot use "%s" as the HttpClient component is not installed. Try running "composer require symfony/http-client".', __CLASS__));
        }
        $this->client = $client ?? \WPPayVendor\Symfony\Component\HttpClient\HttpClient::create();
        parent::__construct([], $history, $cookieJar);
    }
    /**
     * @param Request $request
     */
    protected function doRequest(object $request) : \WPPayVendor\Symfony\Component\BrowserKit\Response
    {
        $headers = $this->getHeaders($request);
        [$body, $extraHeaders] = $this->getBodyAndExtraHeaders($request, $headers);
        $response = $this->client->request($request->getMethod(), $request->getUri(), ['headers' => \array_merge($headers, $extraHeaders), 'body' => $body, 'max_redirects' => 0]);
        return new \WPPayVendor\Symfony\Component\BrowserKit\Response($response->getContent(\false), $response->getStatusCode(), $response->getHeaders(\false));
    }
    /**
     * @return array [$body, $headers]
     */
    private function getBodyAndExtraHeaders(\WPPayVendor\Symfony\Component\BrowserKit\Request $request, array $headers) : array
    {
        if (\in_array($request->getMethod(), ['GET', 'HEAD']) && !isset($headers['content-type'])) {
            return ['', []];
        }
        if (!\class_exists(\WPPayVendor\Symfony\Component\Mime\Part\AbstractPart::class)) {
            throw new \LogicException('You cannot pass non-empty bodies as the Mime component is not installed. Try running "composer require symfony/mime".');
        }
        if (null !== ($content = $request->getContent())) {
            if (isset($headers['content-type'])) {
                return [$content, []];
            }
            $part = new \WPPayVendor\Symfony\Component\Mime\Part\TextPart($content, 'utf-8', 'plain', '8bit');
            return [$part->bodyToString(), $part->getPreparedHeaders()->toArray()];
        }
        $fields = $request->getParameters();
        if ($uploadedFiles = $this->getUploadedFiles($request->getFiles())) {
            $part = new \WPPayVendor\Symfony\Component\Mime\Part\Multipart\FormDataPart(\array_replace_recursive($fields, $uploadedFiles));
            return [$part->bodyToIterable(), $part->getPreparedHeaders()->toArray()];
        }
        if (empty($fields)) {
            return ['', []];
        }
        \array_walk_recursive($fields, $caster = static function (&$v) use(&$caster) {
            if (\is_object($v)) {
                if ($vars = \get_object_vars($v)) {
                    \array_walk_recursive($vars, $caster);
                    $v = $vars;
                } elseif (\method_exists($v, '__toString')) {
                    $v = (string) $v;
                }
            }
        });
        return [\http_build_query($fields, '', '&'), ['Content-Type' => 'application/x-www-form-urlencoded']];
    }
    protected function getHeaders(\WPPayVendor\Symfony\Component\BrowserKit\Request $request) : array
    {
        $headers = [];
        foreach ($request->getServer() as $key => $value) {
            $key = \strtolower(\str_replace('_', '-', $key));
            $contentHeaders = ['content-length' => \true, 'content-md5' => \true, 'content-type' => \true];
            if (\str_starts_with($key, 'http-')) {
                $headers[\substr($key, 5)] = $value;
            } elseif (isset($contentHeaders[$key])) {
                // CONTENT_* are not prefixed with HTTP_
                $headers[$key] = $value;
            }
        }
        $cookies = [];
        foreach ($this->getCookieJar()->allRawValues($request->getUri()) as $name => $value) {
            $cookies[] = $name . '=' . $value;
        }
        if ($cookies) {
            $headers['cookie'] = \implode('; ', $cookies);
        }
        return $headers;
    }
    /**
     * Recursively go through the list. If the file has a tmp_name, convert it to a DataPart.
     * Keep the original hierarchy.
     */
    private function getUploadedFiles(array $files) : array
    {
        $uploadedFiles = [];
        foreach ($files as $name => $file) {
            if (!\is_array($file)) {
                return $uploadedFiles;
            }
            if (!isset($file['tmp_name'])) {
                $uploadedFiles[$name] = $this->getUploadedFiles($file);
            }
            if (isset($file['tmp_name'])) {
                $uploadedFiles[$name] = \WPPayVendor\Symfony\Component\Mime\Part\DataPart::fromPath($file['tmp_name'], $file['name']);
            }
        }
        return $uploadedFiles;
    }
}
