<?php

namespace ImageSeo\Client\Endpoints;

/**
 * @package ImageSeo\Client\Endpoints
 */
class SocialMedia extends AbstractEndpoint
{
    const RESOURCE_NAME = "SocialMedia";

    /**
     * Generate social media image
     *
     * @param array $data
     * @return Image
     */
    public function generateSocialMediaImage($data)
    {
        $url = sprintf('/projects/social-media');
        return $this->makeRequest('IMAGE', $url, $data, [], false);
    }
}
