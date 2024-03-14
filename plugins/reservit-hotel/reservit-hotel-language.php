<?php
/*
*      Reservit Hotel Languages
*      Version: 1.4
*      By Reservit
*
*      Contact: http://www.reservit.com/hebergement
*      Created: 2017
*      Modified: 21/02/2019
*
*      Copyright (c) 2017, Reservit. All rights reserved.
*
*      Licensed under the GPLv2 license - https://www.gnu.org/licenses/gpl-2.0.html
*
*/

//Language used to call Reservit sevice and button text - French is default
function setRsvitLanguageDefault()
{
    $bln = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    
    if ((strpos($_SERVER['REQUEST_URI'], '/es/') !== false) || (strpos($_SERVER['REQUEST_URI'], 'lang=es') !== false)) {
        return 'es';
    } elseif ((strpos($_SERVER['REQUEST_URI'], '/pt/') !== false) || (strpos($_SERVER['REQUEST_URI'], 'lang=pt') !== false)) {
        return 'pt';
    } elseif ((strpos($_SERVER['REQUEST_URI'], '/it/') !== false) || (strpos($_SERVER['REQUEST_URI'], 'lang=it') !== false)) {
        return 'it';
    } elseif ((strpos($_SERVER['REQUEST_URI'], '/en/') !== false) || (strpos($_SERVER['REQUEST_URI'], 'lang=en') !== false)) {
        return 'en';
    } elseif ((strpos($_SERVER['REQUEST_URI'], '/fr/') !== false) || (strpos($_SERVER['REQUEST_URI'], 'lang=fr') !== false)) {
        return 'fr';
    } elseif ((strpos($_SERVER['REQUEST_URI'], '/de/') !== false) || (strpos($_SERVER['REQUEST_URI'], 'lang=de') !== false)) {
        return 'de';
    } else {
        if ($bln == 'es') {
            return 'es';
        } elseif ($bln == 'pt') {
            return 'pt';
        } elseif ($bln == 'it') {
            return 'it';
        } elseif ($bln == 'en') {
            return 'en';
        } elseif ($bln == 'de') {
            return 'de';
        } else {
            return 'fr';
        }
    }
}

?>
