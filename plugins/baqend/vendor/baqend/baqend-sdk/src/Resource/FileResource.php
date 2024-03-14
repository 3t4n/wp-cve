<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Client\RestClientInterface;
use Baqend\SDK\Exception\BadRequestException;
use Baqend\SDK\Exception\NeedsAuthorizationException;
use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\Acl;
use Baqend\SDK\Model\BucketAcl;
use Baqend\SDK\Model\File;
use Baqend\SDK\Model\FileBucket;
use Baqend\SDK\Service\IOService;
use Baqend\SDK\Value\MediaType;
use GuzzleHttp\Psr7\LazyOpenStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class FileResource created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
class FileResource extends AbstractRestResource
{

    const TAR_GZ = '.tar.gz';
    const TAR_XZ = '.tar.xz';
    const ZIP = '.zip';

    /**
     * @var IOService
     */
    private $ioService;

    public function __construct(RestClientInterface $client, Serializer $serializer, IOService $ioService) {
        parent::__construct($client, $serializer);
        $this->ioService = $ioService;
    }

    /**
     * Gets a file bucket instance by its name.
     *
     * @param string $bucket The bucket reference or name to get the instance for.
     * @return FileBucket The file bucket instance being referenced.
     * @throws NeedsAuthorizationException When the user is not privileged to download the given file.
     */
    public function get($bucket) {
        $fileBucket = new FileBucket($bucket);
        $request = $this->sendJson('GET', $fileBucket->getPath());

        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('GET', $fileBucket->getPath());
        }

        $acl = $this->receiveJson($response, BucketAcl::class);
        $this->assignFileBucketAcl($fileBucket, $acl);

        return $fileBucket;
    }

    /**
     * Creates or updates a file bucket instance.
     *
     * @param string|FileBucket $bucket The name of the bucket to create.
     * @param array $acl                The ACL to set on the bucket.
     * @return FileBucket The file bucket instance being referenced.
     * @throws NeedsAuthorizationException When the user is not privileged to download the given file.
     */
    public function put($bucket, array $acl = []) {
        if ($bucket instanceof FileBucket) {
            $fileBucket = $bucket;
        } else {
            $fileBucket = new FileBucket($bucket);
            /** @var BucketAcl $acl */
            $acl = $this->serializer->denormalize($acl, BucketAcl::class);
            $this->assignFileBucketAcl($fileBucket, $acl);
        }

        $request = $this->sendJson('PUT', $fileBucket->getPath(), $fileBucket->getAcl());
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('PUT', $fileBucket->getPath());
        }

        if ($response->getStatusCode() !== 204) {
            throw new NeedsAuthorizationException('PUT', $fileBucket->getPath());
        }

        return $fileBucket;
    }

    /**
     * Deletes an existing file bucket.
     *
     * @param FileBucket|string $bucket The name of the bucket to delete.
     * @return void
     * @throws NeedsAuthorizationException When the user is not privileged to delete the bucket.
     */
    public function delete($bucket) {
        if ($bucket instanceof FileBucket) {
            $fileBucket = $bucket;
        } else {
            $fileBucket = new FileBucket($bucket);
        }

        $request = $this->sendJson('DELETE', $fileBucket->getPath());
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('DELETE', $fileBucket->getPath());
        }

        if ($response->getStatusCode() !== 204) {
            throw new NeedsAuthorizationException('DELETE', $fileBucket->getPath());
        }
    }

    /**
     * Downloads the contents of a file.
     *
     * @param string|File $fileId The file id or a file to download the contents of.
     * @param string $eTag        An optional ETag for version control.
     * @return File The File with the downloaded content and meta data.
     * @throws NeedsAuthorizationException When the user is not privileged to download the given file.
     */
    public function download($fileId, $eTag = null) {
        if ($fileId instanceOf File) {
            return $this->download($fileId->getId(), $eTag);
        }

        $request =
            $this->getClient()->createRequest()
                 ->asGet()
                 ->withPath($fileId)
                 ->withIfNoneMatch($eTag)
                 ->build();

        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('GET', $fileId);
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('GET', $fileId);
        }

        $file = $this->createFile($fileId, $response);

        if ($response->getStatusCode() !== 304) {
            $file->setBody($response->getBody());
        }

        return $file;
    }

    /**
     * Uploads the contents of a file.
     *
     * @param File $file               The file to upload the contents to.
     * @param StreamInterface $content The contents of the file to upload.
     * @return File A new file reference.
     * @throws NeedsAuthorizationException When the user is not privileged to upload the given file.
     */
    public function upload(File $file, StreamInterface $content) {
        $request = $this->sendStream('PUT', $file->getId(), $content);
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('PUT', $file->getId());
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('PUT', $file->getId());
        }

        return $this->receiveJson($response, File::class);
    }

    /**
     * Uploads the contents of a file.
     *
     * @param File $file       The file to upload the contents to.
     * @param string $filename The name of the file to upload.
     * @return File            A new file reference.
     * @throws NeedsAuthorizationException When the user is not privileged to upload the given file.
     */
    public function uploadFile(File $file, $filename) {
        $stream = new LazyOpenStream($filename, 'r');
        try {
            return $this->upload($file, $stream);
        } finally {
            $stream->close();
        }
    }

    /**
     * Downloads the contents of a bucket as an archive.
     *
     * @param string $bucket The bucket to download the contents of.
     * @param string $type   The archive type to use.
     * @return StreamInterface A stream containing the file archive.
     * @throws NeedsAuthorizationException When the user is not privileged to download the given bucket.
     */
    public function downloadArchive($bucket, $type = self::TAR_GZ) {
        $path = "/file/$bucket$type";
        $request = $this->sendJson('GET', $path);
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        return $response->getBody();
    }

    /**
     * Uploads the contents of a bucket as an archive.
     *
     * @param string $bucket           The file to upload the contents to.
     * @param StreamInterface $content The contents of the file to upload.
     * @param string $type             The archive type to use.
     * @return int The number of files uploaded.
     * @throws NeedsAuthorizationException When the user is not privileged to upload the given bucket.
     */
    public function uploadArchive($bucket, StreamInterface $content, $type = self::TAR_GZ) {
        $path = "/file/$bucket$type";
        $request = $this->sendStream('POST', $path, $content);
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('POST', $path);
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('POST', $path);
        }

        return (int) $this->receiveJson($response)['writtenFiles'];
    }

    /**
     * Uploads the contents of a bucket as an archive.
     *
     * @param string $bucket   The file to upload the contents to.
     * @param string $filename The name of the file to upload.
     * @param string $type     The archive type to use.
     * @return int The number of files uploaded.
     * @throws NeedsAuthorizationException When the user is not privileged to upload the given bucket.
     */
    public function uploadArchiveFile($bucket, $filename, $type = self::TAR_GZ) {
        $stream = new LazyOpenStream($filename, 'r');
        try {
            return $this->uploadArchive($bucket, $stream, $type);
        } finally {
            $stream->close();
        }
    }

    /**
     * Finds a file by its ID.
     *
     * @param string $fileId The file ID to look for.
     * @return File|null Returns the file found or null otherwise.
     * @throws NeedsAuthorizationException When the user is not privileged to find a file in the given bucket.
     */
    public function findFile($fileId) {
        $request = $this->sendJson('HEAD', $fileId);
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('HEAD', $fileId);
        }

        if ($response->getStatusCode() === 404) {
            return null;
        }

        if ($response->getStatusCode() === 466) {
            throw new NeedsAuthorizationException('HEAD', $fileId);
        }

        return $this->createFile($fileId, $response);
    }

    /**
     * Updates the metadata of a file.
     *
     * @param File $file The file to update the metadata of.
     * @return File The updated file.
     * @throws BadRequestException When the user sends invalid meta data for the given file.
     * @throws NeedsAuthorizationException When the user is not privileged to find a file in the given bucket.
     */
    public function updateFile(File $file) {
        $request = $this->sendJson('POST', $file->getId(), $file);
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('POST', $file->getId());
        }

        if ($response->getStatusCode() === 400) {
            throw new BadRequestException($response);
        }

        if ($response->getStatusCode() !== 200) {
            throw new NeedsAuthorizationException('POST', $file->getId());
        }

        return $this->receiveJson($response, File::class);
    }

    /**
     * Deletes a file.
     *
     * @param File $file The file to delete.
     * @return void
     * @throws NeedsAuthorizationException When the user is not privileged to delete a file in the given bucket.
     */
    public function deleteFile(File $file) {
        $request = $this->sendJson('DELETE', $file->getId());
        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('DELETE', $file->getId());
        }

        if ($response->getStatusCode() !== 204) {
            throw new NeedsAuthorizationException('DELETE', $file->getId());
        }
    }

    /**
     * Lists files of a bucket.
     *
     * @param string $bucket Name of the bucket to list files of.
     * @param string $path   The path root to start.
     * @param string $start  The element to start retrieving files from.
     * @param int $count     The maximum amount of files to get.
     * @param boolean $deep  If true, also include subdirectories.
     * @return File[] The file metadata retrieved.
     * @throws NeedsAuthorizationException When the user is not privileged to list files in the given bucket.
     */
    public function listFiles($bucket, $path = '/', $start = '', $count = -1, $deep = false) {
        $query = ['path' => $path, 'start' => $start, 'count' => $count, 'deep' => $deep];
        $requestPath = "/file/$bucket/ids";
        $request = $this->sendQuery('GET', $requestPath, $query);

        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw new NeedsAuthorizationException('GET', $requestPath);
        }

        if ($response->getStatusCode() !== 200) {
            throw new NeedsAuthorizationException('GET', $requestPath);
        }

        return $this->receiveJson($response, File::class.'[]');
    }

    /**
     * Crates a new file bucket.
     *
     * @param string|FileBucket $bucket The name of the bucket to create.
     * @param array $acl                The ACL to set on the bucket.
     * @return FileBucket The created file bucket.
     * @throws NeedsAuthorizationException When the user is not privileged to create a bucket.
     * @deprecated Use the {@see FileResource::put()} method instead.
     */
    public function createBucket($bucket, array $acl = []) {
        return $this->put($bucket, $acl);
    }

    /**
     * Deletes an existing file bucket.
     *
     * @param FileBucket|string $bucket The name of the bucket to delete.
     * @return void
     * @throws NeedsAuthorizationException When the user is not privileged to delete the bucket.
     * @deprecated Use the {@see FileResource::delete()} method instead.
     */
    public function deleteBucket($bucket) {
        $this->delete($bucket);
    }

    /**
     * Create a random file in a bucket.
     *
     * @param string $bucket
     * @return File The file created.
     * @throws ResponseException When no such file could be created.
     */
    public function createRandomFile($bucket) {
        $request = $this->sendJson('POST', "/file/$bucket");
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 201) {
            throw new ResponseException($response);
        }

        /** @var File $file */
        $file = $this->receiveJson($response, File::class);
        $file->setContentLength(0);

        return $file;
    }

    /**
     * @param string $fileId
     * @param ResponseInterface $response
     * @return File
     */
    private function createFile($fileId, ResponseInterface $response) {
        $acl = $this->serializer->deserialize($response->getHeaderLine('baqend-acl') ?: '{}', Acl::class, 'json');
        if ($response->hasHeader('content-type')) {
            $contentType = MediaType::parse($response->getHeaderLine('content-type'));
        } else {
            $contentType = null;
        }
        if ($response->hasHeader('content-length')) {
            $contentLength = intval($response->getHeaderLine('content-type'));
        } else {
            $contentLength = null;
        }

        if ($response->hasHeader('last-modified')) {
            $lastModified = new \DateTime($response->getHeaderLine('last-modified'));
        } else {
            $lastModified = null;
        }

        return new File($this->getClient()->getEndpoint()->__toString(), [
            'id' => $fileId,
            'acl' => $acl,
            'eTag' => substr($response->getHeaderLine('etag'), 1, -1),
            'contentType' => $contentType,
            'contentLength' => $contentLength,
            'lastModified' => $lastModified,
        ]);
    }

    /**
     * Assigns a bucket ACL to FileBucket.
     *
     * @param FileBucket $fileBucket
     * @param BucketAcl $acl
     */
    private function assignFileBucketAcl(FileBucket $fileBucket, BucketAcl $acl) {
        $fileBucket->getAcl()->getInsert()->setRules($acl->getInsert()->getRules());
        $fileBucket->getAcl()->getQuery()->setRules($acl->getQuery()->getRules());
        $fileBucket->getAcl()->getDelete()->setRules($acl->getDelete()->getRules());
        $fileBucket->getAcl()->getLoad()->setRules($acl->getLoad()->getRules());
        $fileBucket->getAcl()->getUpdate()->setRules($acl->getUpdate()->getRules());
    }
}
