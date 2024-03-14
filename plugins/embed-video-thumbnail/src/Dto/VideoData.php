<?php

namespace Ikana\EmbedVideoThumbnail\Dto;

class VideoData
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string|null
     */
    private $thumbnail;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string
     */
    private $source;

    public function __construct($source, $id, $url)
    {
        $this->source = $source;
        $this->id = $id;
        $this->url = $url;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }
}
