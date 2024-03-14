<?php
/**
 * @author      Flycart (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.flycart.org
 * */
namespace WPLoyalty;
defined("ABSPATH") or die();
class Notifications
{
    function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
        return '';
    }
}