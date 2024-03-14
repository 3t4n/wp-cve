<?php

declare(strict_types=1);

namespace ADEV_EmailValidation;

defined('ABSPATH') or die('Nope nope nope...');

use ADEV_EmailValidation\Validations\EmailHostValidator;
use ADEV_EmailValidation\Validations\RoleBasedEmailValidator;
use ADEV_EmailValidation\Validations\DisposableEmailValidator;
use ADEV_EmailValidation\Validations\FreeEmailServiceValidator;
use ADEV_EmailValidation\Validations\MisspelledEmailValidator;
use ADEV_EmailValidation\Validations\MxRecordsValidator;
use ADEV_EmailValidation\Validations\Validator;
use ADEV_EmailValidation\Validations\ValidFormatValidator;

class EmailValidatorFactory
{
    /** @var Validator[] */
    private static $defaultValidators = [
        ValidFormatValidator::class,
        MxRecordsValidator::class,
        MisspelledEmailValidator::class,
        FreeEmailServiceValidator::class,
        DisposableEmailValidator::class,
        RoleBasedEmailValidator::class,
        EmailHostValidator::class
    ];

    /**
     * @param string $emailAddress
     * @return EmailValidator
     */
    public static function create($emailAddress)
    {
        $emailAddress = new EmailAddress($emailAddress);
        $emailDataProvider = new EmailDataProvider();
        $emailValidationResults = new ValidationResults();
        $emailValidator = new EmailValidator($emailAddress, $emailValidationResults, $emailDataProvider);

        foreach (self::$defaultValidators as $validator) {
            $emailValidator->registerValidator(new $validator);
        }

        return $emailValidator;
    }
}
