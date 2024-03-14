<?php
/**
 * Created by PhpStorm.
 * User: Frederic
 * Date: 21/12/2018
 * Time: 17:37.
 */

namespace Tests\Ikana\EmbedVideoThumbnail;

use Ikana\EmbedVideoThumbnail\EmbedVideoThumbnail;

final class EmbedVideoThumbnailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider optionsProvider
     *
     * @param mixed $options
     */
    public function testGetOptions($options)
    {
        $embedvideothumbnail = $this->createMock(EmbedVideoThumbnail::class);

        $embedvideothumbnail
            ->method('getOptions')
            ->willReturn($options)
        ;

        var_dump($embedvideothumbnail->getOptions());
    }

    public function optionsProvider()
    {
        return [
            [
                'template' => [
                    'container_class' => 'ikn-evt-frame',
                ],
                'global' => [
                    'enable' => true,
                    'post_type' => false,
                    'exclude_posts' => '',
                ],
                'youtube' => [
                ],
            ],
        ];
    }
}
