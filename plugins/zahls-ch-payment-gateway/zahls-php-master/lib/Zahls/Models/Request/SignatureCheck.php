<?php

namespace Zahls\Models\Request;

/**
 * Class SignatureCheck
 * @package Zahls\Models\Request
 */
class SignatureCheck extends \Zahls\Models\Base
{
    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return new \Zahls\Models\Response\SignatureCheck();
    }
}
