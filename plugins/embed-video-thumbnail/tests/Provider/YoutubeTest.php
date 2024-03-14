<?php

namespace Tests\Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Provider\Youtube;

/**
 * Class Youtube.
 */
class YoutubeTest extends AbstractProviderTest
{
    /**
     * @return Youtube
     */
    public function getProvider()
    {
        return new Youtube();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            [
                [
                    'youbute.ussr',
                    false,
                    [],
                ],
                [
                    '(?:' .
                    '(?:https:|http:)?(?:\/\/)?(?:www\.)?)' .
                    '(?:youtube(?:-nocookie)?\.com\/' .
                    '(?:[^\/]+\/(?:(?!watch))*\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)' .
                    '([a-zA-Z0-9-_]{11})' .
                    '(?:\?|&)?([\w-]+(=[\w-]*)?(&[\w-]+(=[\w-]*)?)*)?',
                    true,
                    [
                        '<iframe ' .
                        'src="https://www.youtube.com/embed/B-8fXb8tts0?feature=oembed" ' .
                        'width="1080" ' .
                        'height="608" ' .
                        'frameborder="0" ' .
                        'allowfullscreen="allowfullscreen"' .
                        '></iframe>' => [
                            'id' => 'B-8fXb8tts0',
                            'embed' => '//www.youtube.com/embed/B-8fXb8tts0?feature=oembed&controls=0&rel=0',
                            'thumb' => '',
                            'title' => '',
                            'source' => 'youtube',
                        ],
                        '<iframe src="https://www.youtube.com/embed/lIn2A34pvvA?feature=oembed" width="1144" height="644" frameborder="0" allowfullscreen="allowfullscreen"></iframe>' => [
                                'id' => 'lIn2A34pvvA',
                                'embed' => '//www.youtube.com/embed/lIn2A34pvvA?feature=oembed&controls=0&rel=0',
                                'thumb' => '',
                                'title' => '',
                                'source' => 'youtube',
                            ],
                        '<iframe src="https://www.youtube.com/embed/o9yKFimI3c0?feature=oembed" width="1144" height="644" frameborder="0" allowfullscreen="allowfullscreen"></iframe>' => [
                                'id' => 'o9yKFimI3c0',
                                'embed' => '//www.youtube.com/embed/o9yKFimI3c0?feature=oembed&controls=0&rel=0',
                                'thumb' => '',
                                'title' => '',
                                'source' => 'youtube',
                            ],
                        'https://www.youtube.com/watch?v=y_LqoHtLzGY&list=PLHT4ZvmtRHiIoQMCLDK4w5UiyXXFi76Ic' => [
                            'id' => 'y_LqoHtLzGY',
                            'embed' => '//www.youtube.com/embed/y_LqoHtLzGY?list=PLHT4ZvmtRHiIoQMCLDK4w5UiyXXFi76Ic&controls=0&rel=0',
                            'thumb' => '',
                            'title' => '',
                            'source' => 'youtube',
                        ],
                        'https://www.youtube.com/watch?v=WA4iX5D9Z64&amp;' => [
                            'id' => 'WA4iX5D9Z64',
                            'embed' => '//www.youtube.com/embed/WA4iX5D9Z64?amp&controls=0&rel=0',
                            'thumb' => '',
                            'title' => '',
                            'source' => 'youtube',
                        ],
                        'https://www.youtube.com/watch?v=y_LqoHtLzGY&amp;' => [
                            'id' => 'y_LqoHtLzGY',
                            'embed' => '//www.youtube.com/embed/y_LqoHtLzGY?amp&controls=0&rel=0',
                            'thumb' => '',
                            'title' => '',
                            'source' => 'youtube',
                        ],
                        'https://www.youtube.com/watch?v=hsOqEhMumaw' => [
                            'id' => 'hsOqEhMumaw',
                            'embed' => '//www.youtube.com/embed/hsOqEhMumaw?controls=0&rel=0',
                            'thumb' => '',
                            'title' => '',
                            'source' => 'youtube',
                        ],
                    ],
                ],
            ],
        ];
    }
}
