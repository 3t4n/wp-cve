<?php

namespace Ikana\EmbedVideoThumbnail\Provider;

interface ProviderInterface
{
    /**
     * ProviderInterface constructor.
     */
    public function __construct(array $options = []);

    public function getName();

    /**
     * @param $content
     * @param mixed $id
     * @param mixed $queryString
     *
     * @return array
     */
    public function buildData($id, $queryString = '');

    /**
     * @param $id
     *
     * @return mixed
     */
    public function apiCall($id);
}
