<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Parser;

use WPPayVendor\BlueMedia\Configuration;
use WPPayVendor\BlueMedia\Common\Util\XMLParser;
use WPPayVendor\BlueMedia\Common\Enum\ClientEnum;
use WPPayVendor\Psr\Http\Message\ResponseInterface;
use WPPayVendor\BlueMedia\Common\Exception\XmlException;
abstract class ResponseParser
{
    /**
     * @var string
     */
    protected $response;
    /**
     * @var Configuration
     */
    protected $configuration;
    public function __construct(\WPPayVendor\Psr\Http\Message\ResponseInterface $response, \WPPayVendor\BlueMedia\Configuration $configuration)
    {
        $this->response = (string) $response->getBody();
        $this->configuration = $configuration;
    }
    protected function isErrorResponse() : void
    {
        if (\preg_match_all(\WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PATTERN_XML_ERROR, $this->response, $data)) {
            $xmlData = \WPPayVendor\BlueMedia\Common\Util\XMLParser::parse($this->response);
            throw \WPPayVendor\BlueMedia\Common\Exception\XmlException::xmlBodyContainsError((string) $xmlData->name);
        }
        if (\preg_match_all(\WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PATTERN_GENERAL_ERROR, $this->response, $data)) {
            throw \WPPayVendor\BlueMedia\Common\Exception\XmlException::xmlGeneralError($this->response);
        }
    }
}
