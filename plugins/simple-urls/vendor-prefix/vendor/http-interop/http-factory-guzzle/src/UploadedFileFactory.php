<?php

namespace LassoLiteVendor\Http\Factory\Guzzle;

use LassoLiteVendor\GuzzleHttp\Psr7\UploadedFile;
use LassoLiteVendor\Psr\Http\Message\UploadedFileFactoryInterface;
use LassoLiteVendor\Psr\Http\Message\StreamInterface;
use LassoLiteVendor\Psr\Http\Message\UploadedFileInterface;
class UploadedFileFactory implements UploadedFileFactoryInterface
{
    public function createUploadedFile(StreamInterface $stream, int $size = null, int $error = \UPLOAD_ERR_OK, string $clientFilename = null, string $clientMediaType = null) : UploadedFileInterface
    {
        if ($size === null) {
            $size = $stream->getSize();
        }
        return new UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
}
