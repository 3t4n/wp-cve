<?php

use Aws\Exception\MultipartUploadException;
use Aws\Result;
use Aws\S3\{Exception\S3Exception, MultipartUploader, ObjectUploader, S3Client};

/**
 * Class CloudManipulator
 */
class AmazonCloudManipulator implements iCloudManipulator
{
    /**
     * @var S3Client $client
     */
    private $client;

    /**
     * CloudManipulator constructor.
     * @param string $accessKeyId
     * @param string $secretAccessKey
     * @param string $region
     */
    public function __construct(string $accessKeyId, string $secretAccessKey, $region, $endPoint)
    {
        $config = [
            'version' => 'latest',
            'region' => $region ?? '',
            'credentials' => [
                'key' => CloudCredentialsEncryption::getInstance()->decrypt($accessKeyId),
                'secret' => CloudCredentialsEncryption::getInstance()->decrypt($secretAccessKey)
            ],
        ];

        if (!empty($endPoint)) {
            $endPoint = str_replace('https://', '', $endPoint);
            $config['endpoint'] = "https://{$endPoint}";
        }

        $this->client = new S3Client($config);
    }

    /**
     * @param string $bucket
     * @param string $destination
     * @param string $imagePath
     */
    public function upload(string $bucket, string $destination, string $imagePath)
    {
        if (file_exists($imagePath)) {
            $source = fopen($imagePath, 'rb');
            $uploader = new ObjectUploader(
                $this->client,
                $bucket,
                $destination,
                $source,
                'public-read'
            );

            do {
                try {
                    $result = $uploader->upload();
                } catch (MultipartUploadException $e) {
                    rewind($source);
                    new MultipartUploader($this->client, $source, [
                        'state' => $e->getState(), array('ACL' => 'public-read')
                    ]);
                }
            } while (!isset($result));

            if (isset(get_option(UPCASTED_S3_OFFLOAD_SETTINGS)[UPCASTED_REMOVE_LOCAL_FILE]) && 'no' === get_option(UPCASTED_S3_OFFLOAD_SETTINGS)[UPCASTED_REMOVE_LOCAL_FILE]) {
                chmod($imagePath, '777');
                wp_delete_file($imagePath);
            }
        }
    }

    /**
     * @param string $bucket
     * @param string $fileName
     * @return Result|mixed|string
     */
    public function delete(string $bucket, string $fileName): Result
    {
        try {
            return $this->client->deleteObject([
                'Bucket' => $bucket,
                'Key' => $fileName
            ]);
        } catch (S3Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @return array|mixed|string
     */
    public function get_buckets()
    {
        return array_map(function ($bucketName) {
            return $bucketName['Name'];
        }, $this->client->listBuckets()['Buckets']);
    }

    /**
     * @param string $bucketName
     */
    public function create_bucket(string $bucketName)
    {
        return $this->client->createBucket([
            'Bucket' => $bucketName
        ]);
    }

    /**
     * @param string $bucket
     * @param string $filePath
     * @return Result|mixed
     */
    public function get_object_from_bucket(string $bucket, string $filePath): Result
    {
        return $this->client->getObject(['Bucket' => $bucket, 'Key' => $filePath]);
    }

    /**
     * @param string $bucket
     * @param string $key
     * @return bool|mixed
     */
    public function check_object($bucket, $key): bool
    {
        if (!empty($bucket) && !empty($key)) {
            return $this->client->doesObjectExist($bucket, $key);
        }
        return false;
    }

    public function get_base_path(): string
    {
        /* 
        !!! IMPORTANT - aici trebuie adaugata o variabile pentru url-ul de la custom endpoint. 
        Trebuie sa poata lege https/https si sa stiu exact sub ce forma trebuie sa adauge URL-ul
        poate sa folosim UPCASTED_S3_OFFLOAD_PROTOCOL la nivel global
        */

        $settings = get_option(UPCASTED_S3_OFFLOAD_SETTINGS);
        if (isset($settings[UPCASTED_S3_OFFLOAD_PROTOCOL], $settings[UPCASTED_S3_OFFLOAD_CUSTOM_DOMAIN])) {
            return $settings[UPCASTED_S3_OFFLOAD_PROTOCOL] . $settings[UPCASTED_S3_OFFLOAD_CUSTOM_DOMAIN];
        }
        if (!empty($settings[UPCASTED_CUSTOM_ENDPOINT])) {
            return "https://{$settings[UPCASTED_CUSTOM_ENDPOINT]}/{$settings[UPCASTED_S3_OFFLOAD_BUCKET]}";
        }
        if (!empty($settings[UPCASTED_OFFLOAD_REGION])) {
            return "https://{$settings[UPCASTED_S3_OFFLOAD_BUCKET]}.s3.{$settings[UPCASTED_OFFLOAD_REGION]}.amazonaws.com";
        }

        return "https://{$settings[UPCASTED_S3_OFFLOAD_BUCKET]}.s3.amazonaws.com";
    }
}