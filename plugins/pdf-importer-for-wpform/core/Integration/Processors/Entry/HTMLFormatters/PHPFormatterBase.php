<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:48 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters;


abstract class PHPFormatterBase
{
    public  abstract function __toString();
    public abstract function IsEmpty();
    public abstract function ToText();
}