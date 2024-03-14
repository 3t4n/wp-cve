<?php

namespace Baqend\SDK\Model;

use Baqend\SDK\Value\MediaType;
use Psr\Http\Message\StreamInterface;

/**
 * Class File created on 25.07.2017.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class File
{

    /** @var string */
    private $endpoint;

    /** @var string|null */
    private $id;

    /** @var Acl */
    private $acl;

    /** @var StreamInterface|null */
    private $body;

    /** @var string|null */
    private $eTag;

    /** @var MediaType|null */
    private $contentType;

    /** @var int */
    private $contentLength;

    /** @var \DateTime|null */
    private $lastModified;

    /**
     * File constructor.
     *
     * @param string $endpoint
     * @param array $data
     */
    public function __construct($endpoint, array $data = []) {
        $this->endpoint = $endpoint;
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->acl = isset($data['acl']) ? $data['acl'] : new Acl();
        $this->setETag(isset($data['eTag']) ? $data['eTag'] : null);
        $this->setContentType(isset($data['contentType']) ? $data['contentType'] : null);
        $this->setContentLength(isset($data['contentLength']) ? $data['contentLength'] : -1);
        $this->setLastModified(isset($data['lastModified']) ? $data['lastModified'] : null);
    }

    /**
     * @return string|null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return Acl
     */
    public function getAcl() {
        return $this->acl;
    }

    /**
     * @return StreamInterface|null
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * @return string|null
     */
    public function getBucket() {
        $str = substr($this->id, 6);
        $slash = strpos($str, '/');

        return substr($str, 0, $slash);
    }

    /**
     * @return string|null
     */
    public function getPathname() {
        $slash = strpos($this->id, '/', 6);

        return substr($this->id, $slash + 1);
    }

    /**
     * @param StreamInterface|null $body
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * @return bool
     */
    public function isDirectory() {
        return substr($this->id, -1) === '/';
    }

    /**
     * @return null|string
     */
    public function getETag() {
        return $this->eTag;
    }

    /**
     * @param null|string $eTag
     */
    public function setETag($eTag) {
        $this->eTag = $eTag;
    }

    /**
     * @return MediaType|null
     */
    public function getContentType() {
        return $this->contentType;
    }

    /**
     * @param MediaType|null $contentType
     */
    public function setContentType(MediaType $contentType = null) {
        $this->contentType = $contentType;
    }

    /**
     * @return int
     */
    public function getContentLength() {
        return $this->contentLength;
    }

    /**
     * @param int $contentLength
     */
    public function setContentLength($contentLength) {
        $this->contentLength = is_int($contentLength) ? $contentLength : -1;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastModified() {
        return $this->lastModified;
    }

    /**
     * @param \DateTime|null $lastModified
     */
    public function setLastModified(\DateTime $lastModified = null) {
        $this->lastModified = $lastModified;
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl() {
        return $this->endpoint.$this->id;
    }
}
