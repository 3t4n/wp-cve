<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Api\ValidationCollection\ValidationCollection;
use CKPL\Pay\Exception\Api\ValidationCollection\ValidationCollectionInterface;
use CKPL\Pay\Exception\Http\HttpBadRequestException;

/**
 * Class ValidationErrorException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class ValidationErrorException extends HttpBadRequestException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'validation-error';

    /**
     * @var ValidationCollectionInterface
     */
    protected $validationCollection;

    /**
     * @param bool $recreate
     *
     * @return ValidationCollectionInterface
     */
    public function createValidationCollection(bool $recreate = false): ValidationCollectionInterface
    {
        if ($recreate || null === $this->validationCollection) {
            $this->validationCollection = new ValidationCollection();
        }

        return $this->validationCollection;
    }

    /**
     * @param string $languageCode
     * @param string|null $type
     *
     * @return string
     */
    private function getErrorMessage(string $languageCode = 'en', ?string $type = null): string
    {
        $supportedLanguages = ['en', 'pl'];

        if (!in_array($languageCode, $supportedLanguages)) {
            $languageCode = 'en';
        }

        $errors = $this->validationCollection->getErrors();
        $error = $errors[0];

        $errorKey = $error['message-key'];
        $fieldName = '';

        if ($error['context-key'] != null) {
            $fieldName = '\'' . $error['context-key'] .'\' ';
        }

        switch ($errorKey) {
            case 'not-null':
            case 'not-empty':
                $message = [
                    'en' => 'The ' . $fieldName . 'field cannot be empty.',
                    'pl' => 'Pole ' . $fieldName . 'nie może być puste.'
                ];
                break;
            case 'positive':
                $message = [
                    'en' => 'Value must be greater than zero.',
                    'pl' => 'Wartość musi być większa niż 0.'
                ];
                break;
            case 'out-of-range':
                $min = $error['params']['min'];
                $max = $error['params']['max'];

                $message = [
                    'en' => 'The ' . $fieldName . 'field cannot be longer than ' . $max . ' characters or shorter than ' . $min . ' characters.',
                    'pl' => 'Pole ' . $fieldName . 'nie może być dłuższe niż ' . $max . ', ani krótsze niż ' . $min . ' znaków.'
                ];
                break;
            case 'positive-or-zero':
                $min = $error['params']['min'];
                $max = $error['params']['max'];

                $message = [
                    'en' => 'Value must be in range of ' . $min . ' to ' . $max . '.',
                    'pl' => 'Wartość musi być w zakresie od ' . $min . ' do ' . $max . '.'
                ];
                break;
            default:
                $message = [
                    'en' => 'Error \'' . $error['message'] . '\'. Check details in the logs.',
                    'pl' => 'Błąd \'' . $error['message'] . '\'. Sprawdź szczegóły w logach.'
                ];

                if ($type == 'log') {
                    $message = ['en' => json_encode($error)];
                }
                break;
        }

        return $message[$languageCode];
    }

    /**
     * @return string
     */
    public function getLogMessage(): string
    {
        return $this->getErrorMessage('en', 'log');
    }

    /**
     * @return string
     */
    public function getLocalizedMessage($languageCode): string
    {
        return $this->getErrorMessage($languageCode);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
