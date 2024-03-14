<?php

namespace Tests\Ikana\EmbedVideoThumbnail\Provider;

use Ikana\EmbedVideoThumbnail\Provider\ProviderInterface;
use ReflectionClass;

/**
 * Class Youtube.
 */
abstract class AbstractProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ProviderInterface
     */
    abstract public function getProvider();

    /**
     * @dataProvider getData
     */
    public function testMatch(array $data)
    {
        $provider = $this->getProvider();
        $this->setRegex($provider, $data[0]);

        $doesMatch = preg_match_all($provider->getRegex(), $this->getBody());
        $this->assertSame((bool) $doesMatch, $data[1]);
    }

    /**
     * @dataProvider getData
     */
    public function testGetContentData(array $data)
    {
        $provider = $this->getProvider();
        $this->setRegex($provider, $data[0]);

        $matches = $provider->getContentData($this->getBody());
        $this->assertEquals($data[2], $matches);
    }

    /**
     * @param $regex
     */
    private function setRegex(ProviderInterface $provider, $regex)
    {
        $reflection = new ReflectionClass($provider);
        $reflection_property = $reflection->getProperty('regex');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($provider, $regex);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return '
        <a href="https://www.youtube.com/watch?v=y_LqoHtLzGY&amp;list=PLHT4ZvmtRHiIoQMCLDK4w5UiyXXFi76Ic">vidéo</a>
        https://www.youtube.com/watch?v=y_LqoHtLzGY&list=PLHT4ZvmtRHiIoQMCLDK4w5UiyXXFi76Ic
        http://red-dot.de/pd/online-exhibition/work/?lang=en&amp;code=08-03974-2016&amp;y=2016
        
        https://www.youtube.com/watch?v=WA4iX5D9Z64&amp;list=PLMC9KNkIncKtPzgY-5rmhvj7fax8fdxoj
        http://red-dot.de/pd/online-exhibition/work/?lang=en&amp;code=08-03974-2016&amp;y=2016
        <iframe src="https://www.youtube.com/embed/o9yKFimI3c0?feature=oembed" width="1144" height="644" frameborder="0" allowfullscreen="allowfullscreen"></iframe>
        &nbsp;
        https://www.youtube.com/watch?v=hsOqEhMumaw
        &nbsp;
        https://vimeo.com/193851364
        http://dai.ly/x4yzane
        <div class="audiotheme-embed"><iframe src="https://www.youtube.com/embed/lIn2A34pvvA?feature=oembed" width="1144" height="644" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
        <div class="et_pb_video_box"><iframe src="https://www.youtube.com/embed/B-8fXb8tts0?feature=oembed" width="1080" height="608" frameborder="0" allowfullscreen="allowfullscreen"></iframe></div>
        ';
    }
}
