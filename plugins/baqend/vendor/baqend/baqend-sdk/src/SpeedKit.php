<?php

namespace Baqend\SDK;

use Baqend\SDK\Client\ClientInterface;
use Baqend\SDK\Client\RequestBuilder;
use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\SpeedKitInfo;
use Baqend\SDK\Resource\AbstractResource;
use Baqend\SDK\Service\IOService;
use Symfony\Component\Serializer\Serializer;

/**
 * Class SpeedKit created on 09.08.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
class SpeedKit extends AbstractResource
{

    /**
     * @var IOService
     */
    private $ioService;

    public function __construct(ClientInterface $client, IOService $ioService, Serializer $serializer) {
        parent::__construct($client, $serializer);
        $this->ioService = $ioService;
    }

    /**
     * @param string $swFilename      Filename of the service worker.
     * @param string $snippetFilename Filename of the snippet.
     * @param string $dfFilename      Filename of the dynamic fetcher.
     * @param string $metricsFilename Filename of the snippet metrics.
     * @param string $version         An optional version number to use.
     * @param boolean $useLegacy      An optional flag to use legacy Dynamic Fetcher.
     *
     * @return SpeedKitInfo           An object containing information about
     * @throws ResponseException      When the server did not respond.
     */
    public function createInfo($swFilename, $snippetFilename, $dfFilename, $metricsFilename, $version = 'latest', $useLegacy = false) {
        if (file_exists($swFilename)) {
            $localContent = $this->ioService->convertLineSeparatorsToLf(file_get_contents($swFilename));
            $localVersion = $this->extractServiceWorkerVersion($localContent);

            // Calculate ETags
            $localETag = $this->ioService->calculateEntityTag($swFilename, IOService::DEFAULT_CHUNK_SIZE, true);
        } else {
            $localContent = null;
            $localVersion = null;
            $localETag = null;
        }

        if (file_exists($snippetFilename)) {
            $localSnippet = $this->ioService->convertLineSeparatorsToLf(file_get_contents($snippetFilename));
        } else {
            $localSnippet = null;
        }

        if (file_exists($dfFilename)) {
            $localDynamicFetcher = $this->ioService->convertLineSeparatorsToLf(file_get_contents($dfFilename));
        } else {
            $localDynamicFetcher = null;
        }

        if (file_exists($metricsFilename)) {
            $metricsSnippet = $this->ioService->convertLineSeparatorsToLf(file_get_contents($metricsFilename));
        } else {
            $metricsSnippet = null;
        }

        $request = $this->createRequest()
                        ->withPath('/'.$version.'/sw.js')
                        ->withIfNoneMatch($localETag)
                        ->build();

        $response = $this->execute($request);

        $isLatest = $response->getStatusCode() === 304;
        $isOutdated = $response->getStatusCode() === 200;
        if (!$isLatest && !$isOutdated) {
            throw new ResponseException($response);
        }

        if ($isLatest) {
            $swContent = $localContent;
            $snippetContent = $localSnippet;
            $dynamicFetcherContent = $localDynamicFetcher;
            $metricsContent = $metricsSnippet;
            $remoteVersion = $localVersion;
            $remoteETag = $localETag;
        } else {
            $swContent = $response->getBody()->getContents();
            $remoteVersion = $this->extractServiceWorkerVersion($swContent);
            $remoteETag = substr($response->getHeaderLine('etag'), 1, -1);

            // Load the snippet
            $snippetRequest = $this->createRequest()
                                   ->withPath('/'.$version.'/snippet.js')
                                   ->withIfNoneMatch($localETag)
                                   ->build();

            $snippetResponse = $this->execute($snippetRequest);
            $snippetContent = $snippetResponse->getBody()->getContents();

            // Load the dynamic fetcher
            $legacyPath = $useLegacy ? '/legacy' : '';
            $dynamicFetcherRequest = $this->createRequest()
                                          ->withPath('/'.$version.$legacyPath.'/dynamic-fetcher.js')
                                          ->withIfNoneMatch($localETag)
                                          ->build();

            $dynamicFetcherResponse = $this->execute($dynamicFetcherRequest);
            $dynamicFetcherContent = $dynamicFetcherResponse->getBody()->getContents();

            // Load the metrics snippet
            $metricsRequest = $this->createRequest()
                ->withPath('/'.$version.'/snippet-metrics.js')
                ->withIfNoneMatch($localETag)
                ->build();

            $metricsResponse = $this->execute($metricsRequest);
            $metricsContent = $metricsResponse->getBody()->getContents();
        }

        return new SpeedKitInfo(
            [
                'swContent' => $swContent,
                'snippetContent' => $snippetContent,
                'dynamicFetcherContent' => $dynamicFetcherContent,
                'metricsSnippetContent' => $metricsContent,
                'localVersion' => $localVersion,
                'localETag' => $localETag,
                'remoteVersion' => $remoteVersion,
                'remoteETag' => $remoteETag,
                'isLatest' => $isLatest,
                'isOutdated' => $isOutdated,
            ]
        );
    }

    /**
     * Returns the service worker file.
     *
     * @param string $version An optional version number to use.
     * @return string         The file contents.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getServiceWorker($version = 'latest') {
        return $this->getFile('sw.js', $version);
    }

    /**
     * Returns the snippet file.
     *
     * @param string $version An optional version number to use.
     * @return string         The file contents.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getSnippet($version = 'latest') {
        return $this->getFile('snippet.js', $version);
    }

    /**
     * Returns the dynamic fetcher file.
     *
     * @param string $version An optional version number to use.
     * @return string         The file contents.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getDynamicFetcher($version = 'latest') {
        return $this->getFile('dynamic-fetcher.js', $version);
    }

    /**
     * Returns the metrics snippet file
     *
     * @param string $version An optional version number to use.
     * @return string The file contents.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getMetricsSnippet($version = 'latest') {
        return $this->getFile('snippet-metrics.js', $version);
    }

    /**
     * Compares a local service worker with the remote version.
     *
     * @param string $swFilename The filename of the local service worker.
     * @param string $version    An optional version number to use.
     * @return bool              True, if local service worker is outdated.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function isServiceWorkerOutdated($swFilename, $version = 'latest') {
        $localETag = $this->ioService->calculateEntityTag($swFilename, IOService::DEFAULT_CHUNK_SIZE, true);
        $remoteETag = $this->getServiceWorkerETag($version);

        return $localETag !== $remoteETag;
    }

    /**
     * Compares a local snippet with the remote version.
     *
     * @param string $snippetFilename The filename of the local snippet.
     * @param string $version         An optional version number to use.
     * @return bool True, if local snippet is outdated.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function isSnippetOutdated($snippetFilename, $version = 'latest') {
        $localETag = $this->ioService->calculateEntityTag($snippetFilename, IOService::DEFAULT_CHUNK_SIZE, true);
        $remoteETag = $this->getSnippetETag($version);

        return $localETag !== $remoteETag;
    }

    /**
     * Compares a local dynamic fetcher with the remote version.
     *
     * @param string $dynamicFetcherFilename The filename of the local dynamic fetcher.
     * @param string $version                An optional version number to use.
     *
     * @return bool True, if local dynamic fetcher is outdated.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function isDynamicFetcherOutdated($dynamicFetcherFilename, $version = 'latest') {
        $localETag = $this->ioService->calculateEntityTag($dynamicFetcherFilename, IOService::DEFAULT_CHUNK_SIZE,
            true);
        $remoteETag = $this->getDynamicFetcherETag($version);

        return $localETag !== $remoteETag;
    }

    /**
     * Compares a local snippet with the remote version.
     *
     * @param string $metricsSnippetFileName The filename of the local snippet.
     * @param string $version         An optional version number to use.
     * @return bool True, if local snippet is outdated.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function isMetricsSnippetOutdated($metricsSnippetFileName, $version = 'latest') {
        $localETag = $this->ioService->calculateEntityTag($metricsSnippetFileName, IOService::DEFAULT_CHUNK_SIZE, true);
        $remoteETag = $this->getMetricsSnippetETag($version);

        return $localETag !== $remoteETag;
    }

    /**
     * Returns the service worker version.
     *
     * @param string $version An optional version number to use.
     * @return string The file's ETag.
     * @throws ResponseException
     */
    public function getServiceWorkerETag($version = 'latest') {
        return $this->getFileETag('sw.js', $version);
    }

    /**
     * Returns the snippet version.
     *
     * @param string $version An optional version number to use.
     * @return string The file's ETag.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getSnippetETag($version = 'latest') {
        return $this->getFileETag('snippet.js', $version);
    }

    /**
     * Returns the dynamic fetcher version.
     *
     * @param string $version An optional version number to use.
     * @return string The file's ETag.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getDynamicFetcherETag($version = 'latest') {
        return $this->getFileETag('dynamic-fetcher.js', $version);
    }

    /**
     * Returns the metrics snippet version.
     *
     * @param string $version An optional version number to use.
     * @return string The file's ETag.
     * @throws ResponseException When the server did not respond successfully.
     */
    public function getMetricsSnippetETag($version = 'latest') {
        return $this->getFileETag('snippet-metrics.js', $version);
    }

    /**
     * @param string $file    The file to check.
     * @param string $version An optional version number to use.
     * @return string         The file's content.
     * @throws ResponseException When the server did not respond successfully.
     */
    private function getFile($file, $version = 'latest') {
        $request = $this->createRequest()->withPath('/'.$version.'/'.$file)->build();
        $response = $this->execute($request);

        return $response->getBody()->getContents();
    }

    /**
     * @param string $file    The file to check.
     * @param string $version An optional version number to use.
     * @return string            The file's entity tag.
     * @throws ResponseException When the server did not respond successfully.
     */
    private function getFileETag($file, $version = 'latest') {
        $request = $this->createRequest()->asHead()->withPath('/'.$version.'/'.$file)->build();
        $response = $this->execute($request);

        $etag = substr($response->getHeaderLine('etag'), 1, -1);

        return $etag;
    }

    /**
     * @return RequestBuilder
     */
    private function createRequest() {
        return (new RequestBuilder($this->serializer))
            ->withHost('www.baqend.com')
            ->withScheme('https')
            ->withBasePath('/speed-kit');
    }

    /**
     * @param string $swContent
     * @return string|null
     */
    private function extractServiceWorkerVersion($swContent) {
        if (preg_match('#^/\* ! speed-kit ([\w.-]+) |#', $swContent, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
