<?php

/**
 * User: Damian Zamojski (br33f)
 * Date: 22.06.2021
 * Time: 11:10
 */
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Response;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Common\ValidationMessage;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Psr\Http\Message\ResponseInterface;
class DebugResponse extends BaseResponse
{
    /**
     * @var ValidationMessage[]
     */
    protected $validationMessages = [];
    /**
     * @return ValidationMessage[]
     */
    public function getValidationMessages() : array
    {
        return $this->validationMessages;
    }
    /**
     * @param ValidationMessage[] $validationMessages
     * @return DebugResponse
     */
    public function setValidationMessages(array $validationMessages)
    {
        $this->validationMessages = $validationMessages;
        return $this;
    }
    /**
     * @param array|ResponseInterface $blueprint
     */
    public function hydrate($blueprint)
    {
        parent::hydrate($blueprint);
        $validationMessages = [];
        foreach ($this->getData()['validationMessages'] as $validationMessage) {
            $validationMessages[] = new ValidationMessage($validationMessage);
        }
        $this->setValidationMessages($validationMessages);
    }
}
