<?php

declare (strict_types=1);
namespace DhlVendor\Octolize\DhlExpress\RestApi\Traits;

trait GetRawResponse
{
    private function getRawResponse() : array
    {
        return $this->response;
    }
}
