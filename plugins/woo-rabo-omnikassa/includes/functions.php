<?php

    if (!defined('ICWOOROK2_FUNCTIONS_LOADED')) {
        define('ICWOOROK2_FUNCTIONS_LOADED', true);
    }

    function icwoorok2_mb_splitAddress($sAddress)
    {
        // Get everything up to the first number with a regex
        $bHasMatch = preg_match('/^[^0-9]*/', $sAddress, $aMatch);

        // If no matching is possible, return the supplied string as the street
        if (!$bHasMatch) {
            return [$sAddress, '', ''];
        }

        // Remove the street from the sAddress.
        $sAddress = str_replace($aMatch[0], '', $sAddress);
        $sStreetname = trim($aMatch[0]);

        // Nothing left to split, return the streetname alone
        if (strlen($sAddress == 0)) {
            return [$sStreetname, '', ''];
        }

        // Explode sAddress to an array using a multiple explode function
        $aAddress = icwoorok2_mb_multiExplodeArray([' ', '-', '|', '&', '/', '_', '\\'], $sAddress);

        // Shift the first element off the array, that is the house number
        $iHousenumber = array_shift($aAddress);

        // If the array is empty now, there is no extension.
        if (count($aAddress) == 0) {
            return [$sStreetname, $iHousenumber, ''];
        }

        // Join together the remaining pieces as the extension.
        $sExtension = substr(implode(' ', $aAddress), 0, 4);

        return [$sStreetname, $iHousenumber, $sExtension];
    }

    function icwoorok2_mb_multiExplodeArray($aDelimiter, $sString)
    {
        $sInput = str_replace($aDelimiter, $aDelimiter[0], $sString);
        $aArray = explode($aDelimiter[0], $sInput);

        return $aArray;
    }

    function icwoorok2_isFolder($sPath)
    {
        if (file_exists($sPath)) {
            return true;
        } else {
            return false;
        }
    }

    function icwoorok2_loadFilesFromFolder($sPath, $sExtraExtension = '') // From ICWOOROK2_ROOT_PATH
    {
        if (icwoorok2_isFolder($sPath)) {
            $bFileFound = false;

            $aFiles = scandir($sPath);

            foreach ($aFiles as $sFile) {
                if (strpos($sFile, $sExtraExtension.'.php') > 0) {
                    $bFileFound = true;
                    require_once $sPath.DIRECTORY_SEPARATOR.$sFile;
                }
            }

            if ($bFileFound) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function icwoorok2_getTransaction($aTransactions)
    {
        foreach ($aTransactions as $aTransaction) {
            if (in_array($aTransaction['status'], ['SUCCESS', 'ACCEPTED'])) {
                return $aTransaction;
            }
        }

        return null;
    }
