<?php

namespace WPRemoteMediaExt\Guzzle\Tests\Mock;

class MockMulti extends \WPRemoteMediaExt\Guzzle\Http\Curl\CurlMulti
{
    public function getHandle()
    {
        return $this->multiHandle;
    }
}
