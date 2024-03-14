<?php

namespace Baqend\SDK\Model;

/**
 * Class SpeedKitInfo created on 10.08.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class SpeedKitInfo
{

    /**
     * @var string
     */
    private $swContent;

    /**
     * @var string
     */
    private $snippetContent;

    /**
     * @var string
     */
    private $dynamicFetcherContent;

    /**
     * @var string
     */
    private $metricsSnippetContent;

    /**
     * @var string|null
     */
    private $localVersion;

    /**
     * @var string|null
     */
    private $localEntityTag;

    /**
     * @var string
     */
    private $remoteVersion;

    /**
     * @var string
     */
    private $remoteEntityTag;

    /**
     * @var bool
     */
    private $isLatest;

    /**
     * @var bool
     */
    private $isOutdated;

    /**
     * SpeedKitInfo constructor.
     * @param array $data
     */
    public function __construct(array $data) {
        $this->swContent = $data['swContent'];
        $this->snippetContent = $data['snippetContent'];
        $this->dynamicFetcherContent = $data['dynamicFetcherContent'];
        $this->metricsSnippetContent = $data['metricsSnippetContent'];
        $this->localVersion = $data['localVersion'];
        $this->localEntityTag = $data['localETag'];
        $this->remoteVersion = $data['remoteVersion'];
        $this->remoteEntityTag = $data['remoteETag'];
        $this->isLatest = $data['isLatest'];
        $this->isOutdated = $data['isOutdated'];
    }

    /**
     * @return string
     */
    public function getSwContent() {
        return $this->swContent;
    }

    /**
     * @return string
     */
    public function getSnippetContent() {
        return $this->snippetContent;
    }

    /**
     * @return string
     */
    public function getDynamicFetcherContent() {
        return $this->dynamicFetcherContent;
    }

    /**
     * @return string
     */
    public function getMetricsSnippetContent() {
        return $this->metricsSnippetContent;
    }

    /**
     * @return string|null
     */
    public function getLocalVersion() {
        return $this->localVersion;
    }

    /**
     * @return string|null
     */
    public function getLocalEntityTag() {
        return $this->localEntityTag;
    }

    /**
     * @return string
     */
    public function getRemoteVersion() {
        return $this->remoteVersion;
    }

    /**
     * @return string
     */
    public function getRemoteEntityTag() {
        return $this->remoteEntityTag;
    }

    /**
     * @return bool
     */
    public function isLatest() {
        return $this->isLatest;
    }

    /**
     * @return bool
     */
    public function isOutdated() {
        return $this->isOutdated;
    }
}
