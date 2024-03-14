<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSjsjobswidgetsModel {

    function __construct() {
        
    }


    function getClasses($for) {
        $class = '';
        switch ($for) {
            case 1: // Show all
                $class = ' visible-all ';
                break;
            case 2: // Show desktop and tablet
                $class = ' visible-desktop visible-tablet ';
                break;
            case 3: // Show desktop and mobile
                $class = ' visible-desktop visible-mobile ';
                break;
            case 4: // Show tablet and mobile
                $class = ' visible-tablet visible-mobile ';
                break;
            case 5: // Show desktop
                $class = ' visible-desktop ';
                break;
            case 6: // Show tablet
                $class = ' visible-tablet ';
                break;
            case 7: // Show mobile
                $class = ' visible-mobile ';
                break;
        }
        return $class;
    }


}
?>