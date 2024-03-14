<?php

namespace LitExtension;

class Connector_Errors
{
    const MODULE_DEFAULT = 2612;
    const MODULE_CONNECTOR_ALREADY_INSTALLED = 2613;
    const MODULE_CONNECTOR_SUCCESSFULLY_INSTALLED = 2614;

    const MODULE_ERROR_DEFAULT = 'defaultError';
    const MODULE_ERROR_EMPTY_TOKEN = 2615;
    const MODULE_ERROR_ROOT_DIR_PERMISSION = 2616;
    const MODULE_ERROR_INSTALLED_PERMISSION = 2617;
    const MODULE_ERROR_PERMISSION = 2618;
    const CONNECTOR_FILE_PERMISSION = 2619;

    protected static $_errorMessages = array(
        self::CONNECTOR_FILE_PERMISSION => 'Bad permission for Connector file.',
        self::MODULE_ERROR_DEFAULT => 'Connector Install action executed successfully.',
        self::MODULE_ERROR_EMPTY_TOKEN => 'Token can\'t be blank.',
        self::MODULE_ERROR_ROOT_DIR_PERMISSION => 'Please change the permission for Wordpress root folder to 777 or install the Connector manually. If this error persists, please contact our Support Team.',
        self::MODULE_ERROR_INSTALLED_PERMISSION => 'Please provide following permissions: 777 for the Connector folder; 666 for the files inside the folder. If assistance needed, feel free to contact our Support Team.',
        self::MODULE_ERROR_PERMISSION => 'Ensure Wordpress store root folder is writable. The Connector folder permission should be set to 777; files inside the folder - 666. If this doesn’t resolve the issue, try reinstalling the Connector manually. If this error persists, please contact our Support Team for assistance.',
    );

    public static function getErrorMessage($code)
    {
        return (!empty(self::$_errorMessages[$code])) ? self::$_errorMessages[$code] : self::$_errorMessages[self::MODULE_ERROR_DEFAULT];
    }
}