<?php

namespace Tests\Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Provider\Vimeo;

/**
 * Class VimeoTest.
 */
class VimeoTest extends AbstractProviderTest
{
    /**
     * @return Vimeo
     */
    public function getProvider()
    {
        return new Vimeo();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            [
                [
                    'vimeo.abd1234',
                    false,
                    [],
                ],
                [
                    '(?:(?:https:|http:)?(?:\/\/)?(?:www\.)?)(?:player\.)?vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*',
                    true,
                    [
                        'https://vimeo.com/193851364' => [
                            'id' => '193851364',
                            'embed' => '//player.vimeo.com/video/193851364?',
                            'thumb' => '',
                            'title' => '',
                            'source' => 'vimeo',
                        ],
                    ],
                ],
            ],
        ];
    }
}
