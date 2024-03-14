<?php

namespace Baqend\SDK\Model;

/**
 * Class FileBucket created on 14.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class FileBucket
{

    /** @var string */
    private $name;

    /** @var BucketAcl */
    private $acl;

    /**
     * FileBucket constructor.
     *
     * @param mixed $bucket The bucket reference or name.
     * @param BucketAcl|null $acl
     */
    public function __construct(
        $bucket,
        BucketAcl $acl = null
    ) {
        $this->name = self::normalizeBucketName($bucket);
        $this->acl = $acl ?: new BucketAcl();
    }

    /**
     * Normalizes the given bucket name.
     *
     * @param mixed $bucket
     * @return string
     */
    private static function normalizeBucketName($bucket) {
        if (is_string($bucket)) {
            if (preg_match('#^[\w\-]*(\.([\w\-]*\.)*[\w\-]{4,})?$#', $bucket, $matches) === 1) {
                return $bucket;
            }

            if (preg_match('#^/file/([\w\-]*(?:\.(?:[\w\-]*\.)*[\w\-]{4,})?)/?$#', $bucket, $matches) === 1) {
                return $matches[1];
            }
        }

        throw new \InvalidArgumentException('"'.$bucket.'" is not a valid file bucket reference.');
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return BucketAcl
     */
    public function getAcl() {
        return $this->acl;
    }

    /**
     * @return string
     */
    public function getPath() {
        $name = $this->name;

        return "/file/$name";
    }
}
