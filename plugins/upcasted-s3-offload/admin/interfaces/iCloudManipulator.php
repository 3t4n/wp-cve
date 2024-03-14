<?php

/**
 * Interface iCloudManipulator
 */
interface iCloudManipulator
{
    /**
     * @param string $bucket
     * @param string $destination
     * @param string $imagePath
     * @return mixed
     */
    public function upload(string $bucket, string $destination, string $imagePath);

    /**
     * @param string $bucket
     * @param string $fileName
     * @return mixed
     */
    public function delete(string $bucket, string $fileName);

    /**
     * @return mixed
     */
    public function get_buckets();

    /**
     * @param string $bucketName
     * @return mixed
     */
    public function create_bucket(string $bucketName);

    /**
     * @param string $bucket
     * @param string $filePath
     * @return mixed
     */
    public function get_object_from_bucket(string $bucket, string $filePath);

    /**
     * @param string $bucket
     * @param string $key
     * @return mixed
     */
    public function check_object(string $bucket, string $key);

    /**
     * @return string
     */
    public function get_base_path(): string;
}