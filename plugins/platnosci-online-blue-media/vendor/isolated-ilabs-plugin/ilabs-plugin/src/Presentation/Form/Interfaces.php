<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
class Interfaces
{
    /**
     * @throws Exception
     */
    public static function extract_field_ajax_interface(Field_Interface $field_interface) : Field_Ajax_Interface
    {
        if ($field_interface instanceof Field_Ajax_Interface) {
            return $field_interface;
        }
        self::throw_exception('Field_Ajax_Interface');
    }
    /**
     * @throws Exception
     */
    private static function throw_exception(string $excepted_type)
    {
        throw new Exception("Object must be type of {$excepted_type}");
    }
}
