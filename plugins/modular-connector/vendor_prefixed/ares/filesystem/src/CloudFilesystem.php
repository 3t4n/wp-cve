<?php

namespace Modular\ConnectorDependencies\Ares\Filesystem;

use Modular\ConnectorDependencies\League\Flysystem\Exception;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\File\File;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;
/** @internal */
class CloudFilesystem
{
    /**
     * @var array
     */
    protected $upload_dir;
    /**
     * @var string
     */
    protected $origin_disk;
    /**
     * @var string
     */
    protected $default_disk;
    /**
     * CloudFilesystem constructor.
     */
    public function __construct()
    {
        if (\function_exists('wp_upload_dir')) {
            $this->upload_dir = \wp_upload_dir();
            $this->setOriginDisk('local');
            $this->setDefaultDisk(\Modular\ConnectorDependencies\config('filesystems.default'));
        }
    }
    /**
     * @param string $disk
     *
     * @return $this
     */
    public function setDefaultDisk(string $disk)
    {
        $this->default_disk = $disk;
        return $this;
    }
    /**
     * @param string $origin_disk
     */
    public function setOriginDisk(string $origin_disk)
    {
        $this->origin_disk = $origin_disk;
        return $this;
    }
    /**
     * Get file from local disk (wp-content folder)
     *
     * @param String $base_path
     * @return File
     */
    public function getFile(string $base_path) : File
    {
        $file_path = Storage::disk($this->origin_disk)->path($base_path);
        return new File($file_path);
    }
    /**
     * Update the file name with the relative sizes
     *
     * @param $sizes
     * @param $file
     */
    private function parseSizes($sizes, $file, $base_path)
    {
        foreach ($sizes as $key => $size) {
            $name = $this->parseSize($file->getExtension(), $base_path, $size['width'], $size['height']);
            $sizes[$key]['file'] = $name;
            $sizes[$key]['url'] = $this->getStorageUrl($name);
        }
        return $sizes;
    }
    /**
     * Get specific size
     *
     * @param $extension
     * @param $base_path
     * @param $width
     * @param $height
     * @return string
     */
    private function parseSize($extension, $base_path, $width, $height)
    {
        $ext = '.' . $extension;
        $size_name = '-' . $width . 'x' . $height;
        return \str_replace($ext, $size_name . $ext, $base_path);
    }
    /**
     * Copy file to default disk (S3 / Azure...)
     *
     * @param string $base_path
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadToDisk(string $base_path)
    {
        if (!Storage::disk($this->default_disk)->exists($base_path)) {
            $file = Storage::disk($this->origin_disk)->get($base_path);
            Storage::disk($this->default_disk)->put($base_path, $file);
        }
    }
    /**
     * Get URL from CDN
     *
     * @param string $local_url
     * @param int $post_id
     * @return string
     */
    public function getUrlFile(string $local_url, $post_id = 0)
    {
        $parse_url = \parse_url($local_url);
        $path = $parse_url['path'] ?? null;
        if (!$path) {
            return $path;
        }
        $path = \explode('/', $path);
        $path = \trim(@$path[3] . '/' . @$path[4] . '/' . @$path[5], '/');
        return $this->getStorageUrl($path, $local_url);
    }
    /**
     * Get URL from CDN
     *
     * @param string $path
     * @param $original_url
     * @return string
     */
    public function getStorageUrl(string $path, ?string $original_url = null)
    {
        $disk = Storage::disk($this->default_disk);
        if (!$disk->exists($path)) {
            if (!$original_url) {
                return $path;
            } else {
                return $original_url;
            }
        }
        return $disk->url($path);
    }
    /**
     * Get images url for responsive
     *
     * @param $sources
     * @param $size_array
     * @param $image_src
     * @param $meta
     * @param $attachment_id
     * @return array
     */
    public function getResponsiveUrls($sources, $size_array, $image_src, $meta, $attachment_id = 0)
    {
        $meta = \apply_filters('wp_calculate_image_srcset_meta', $meta, $size_array, $image_src, $attachment_id);
        if (empty($meta['sizes']) || !isset($meta['file']) || \strlen($meta['file']) < 4 || !\is_array($sources)) {
            return $sources;
        }
        $base_path = $meta['file'];
        $image_width = $size_array[0];
        $image_height = $size_array[1];
        // check if WP use cropped image
        try {
            $file = $this->getFile($base_path);
        } catch (\Exception $e) {
            return $sources;
        }
        $sizes = $this->parseSizes($meta['sizes'], $file, $base_path);
        foreach ($sizes as $image) {
            if (\wp_image_matches_ratio($image_width, $image_height, $image['width'], $image['height'])) {
                // The 'src' image has to be the first in the 'srcset', because of a bug in iOS8. See #35030.
                $w = $image['width'];
                if (isset($sources[$w])) {
                    $sources[$w]['url'] = $image['url'];
                }
            }
        }
        return $sources;
    }
    /**
     * @param $data
     * @param $post_id
     * @return array|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function uploadFile($data, $post_id)
    {
        $base_path = $this->upload_dir['basedir'];
        $base_path = \str_replace($base_path, '', \get_attached_file($post_id));
        $base_path = \ltrim($base_path, \DIRECTORY_SEPARATOR);
        $file = $this->getFile($base_path);
        // Original Size
        $this->uploadToDisk($base_path);
        // Only for images
        $meta = \get_post_meta($post_id, '_wp_attachment_metadata', \true);
        if ($meta) {
            if (!empty($meta['sizes'])) {
                $sizes = $this->parseSizes($meta['sizes'], $file, $base_path);
                foreach ($sizes as $size) {
                    $this->uploadToDisk($size['file']);
                }
            }
        }
        return $data;
    }
    /**
     * delete files from the default disk (  S3 / Azure... )
     *
     * @param $post_id
     */
    public function destroyFile($post_id)
    {
        $base_path = $this->upload_dir['basedir'];
        $base_path = \str_replace($base_path, '', \get_attached_file($post_id));
        $file = $this->getFile($base_path);
        Storage::disk($this->default_disk)->delete($base_path);
        //only for images
        $meta = \get_post_meta($post_id, '_wp_attachment_metadata', \true);
        if ($meta) {
            if (!empty($meta['sizes'])) {
                $sizes = $this->parseSizes($meta['sizes'], $file, $base_path);
                foreach ($sizes as $size) {
                    Storage::disk($this->default_disk)->delete($size['file']);
                }
            }
        }
    }
}
