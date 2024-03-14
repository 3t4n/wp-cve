<?php

defined('ABSPATH') or die('Nope nope nope...');

include_once 'src/EmailValidatorFactory.php';
include_once 'src/EmailAddress.php';
include_once 'src/EmailDataProviderInterface.php';
include_once 'src/EmailDataProvider.php';
include_once 'src/ValidationResults.php';
include_once 'src/EmailValidator.php';
include_once 'src/Validations/ValidatorInterface.php';
include_once 'src/Validations/Validator.php';
include_once 'src/Validations/MxRecordsValidator.php';
include_once 'src/Validations/MisspelledEmailValidator.php';
include_once 'src/Validations/ValidFormatValidator.php';
include_once 'src/Validations/DisposableEmailValidator.php';
include_once 'src/Validations/EmailHostValidator.php';
include_once 'src/Validations/FreeEmailServiceValidator.php';
include_once 'src/Validations/RoleBasedEmailValidator.php';



use ADEV_EmailValidation\EmailValidatorFactory;

if( ! function_exists( 'adev_getLocalValidationResult' ) )
{
    function adev_getLocalValidationResult($email){
        $validator = ADEV_EmailValidation\EmailValidatorFactory::create($email);
        return $validator->getValidationResults()->asArray();
    }
}