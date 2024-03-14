<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

abstract class AbstractProvider
{
    /**
     * @var string
     */
    protected $regex;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * AbstractProvider constructor.
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function isTitleEnabled()
    {
        return !empty($this->options[$this->getName()]['api']['display_title']);
    }

    public function isLoopEnabled()
    {
        return !empty($this->options[$this->getName()]['embed']['loop']);
    }

    public function isRelEnabled()
    {
        return !empty($this->options[$this->getName()]['embed']['rel']);
    }

    public function isModestEnabled()
    {
        return !empty($this->options[$this->getName()]['embed']['modestbranding']);
    }

    public function isThumbCopyEnabled()
    {
        return !empty($this->options[$this->getName()]['api']['thumb_copy']);
    }

    public function areControlsDisabled()
    {
        return empty($this->options[$this->getName()]['embed']['controls']);
    }

    public function hasNoCookie()
    {
        return !empty($this->options[$this->getName()]['embed']['no_cookie']);
    }

    /**
     * @param $id
     * @param $thumb
     *
     * @return mixed
     */
    public function copyThumb($id, $thumb)
    {
        $provider_dir = IKANAWEB_EVT_IMAGE_PATH . DIRECTORY_SEPARATOR . $this->getName();
        if (!is_dir($provider_dir)) {
            wp_mkdir_p($provider_dir);
        }

        $fileName = basename($thumb);
        $destinationFile = $provider_dir . '/' . $id . '-' . $fileName;

        $copied = true;

        if (!is_file($destinationFile)) {
            $copied = copy($thumb, $destinationFile);
        }

        if (!$copied) {
            return $thumb;
        }

        $uploadUrlPath = get_option('upload_url_path');

        if (!empty($uploadUrlPath)) {
            $destination = $uploadUrlPath;
        } else {
            $destination = '/wp-content/uploads';
        }

        return str_replace(\dirname(IKANAWEB_EVT_IMAGE_PATH), $destination, $destinationFile);
    }

    public function getRegex()
    {
        return '#(?:<iframe[^>]*src=\"|[^"\[]|\[embed\])?' . $this->regex . '(?:\".*><\/iframe>|[^"\[]|\[\/embed\])?#i';
    }

    public function parseContent($content)
    {
        $data = [];

        preg_match_all($this->getRegex(), $content, $matches, PREG_SET_ORDER);

        if (empty($matches[0][1])) {
            return $data;
        }

        foreach ($matches as $m) {
            $queryString = !empty($m[2]) ? trim($m[2]) : '';
            $data[trim($m[0])] = $this->buildData($m[1], $queryString);
        }

        $keys = array_map('strlen', array_keys($data));
        array_multisort($keys, SORT_DESC, $data);

        return $data;
    }

    /**
     * @param int $id
     * @param mixed $queryString
     *
     * @return array
     */
    abstract protected function buildData($id, $queryString = '');
}
