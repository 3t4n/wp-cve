<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Version
{
    const PT_NAME = 'WP Admin Audit';
    const PT_DATE = 'February 2024';

    const PT_TOP = '1';
    const PT_SUB = '2';
    const PT_FIX = '9';
    const PT_BET = '-';
    const PT_BLD = '127';

    const FT_RET_OCT        = '36';
    const FT_CSV_EXP        = 'yeah';
    const FT_NOTI           = 'yeah';
    const FT_UAC_AUTO_A     = 'yeah';
    const FT_UAC_ENF_PWC    = 'yeah';
    const FT_REPLICATE      = 'yeah';
    const FT_INTEG_CHK      = 'yeah';
    const FT_EXT            = 'yeah';
    
    const FT_ID_RET         = 'MAX_RET';
    const FT_ID_CSV_EXP     = 'CSV_EXP';
    const FT_ID_NOTI        = 'NOTI';
    const FT_ID_UAC_AUTO_A  = 'UAC_AUTO_A';
    const FT_ID_UAC_ENF_PWC = 'UAC_ENF_PWC';
    const FT_ID_REPLICATE   = 'REPL';
    const FT_ID_INTEG_CHK   = 'INTEG_CHK';
    const FT_ID_EXT         = 'EXT';

    const UD_OK         = 'true';
    const UD_VERSION    = 'WP Admin Audit Startup';
    const UD_OPT_1      = 'WP Admin Audit Startup';
    const UD_OPT_2      = 'WP Admin Audit Business';
    const UD_OPT_3      = 'WP Admin Audit Enterprise';
    const UD_LINK       = 'https://wpadminaudit.com/upgrade/startup?utm_source=wada-plg&utm_medium=upgrade&utm_campaign=intro';

    /**
     * @return array
     */
    public static function getFtMap(){
        $ftMap = array();
        $ftMap[self::FT_ID_RET]         = octdec(self::FT_RET_OCT);
        $ftMap[self::FT_ID_CSV_EXP]     = (self::FT_CSV_EXP === 'true');
        $ftMap[self::FT_ID_NOTI]        = (self::FT_NOTI === 'true');
        $ftMap[self::FT_ID_UAC_AUTO_A]  = (self::FT_UAC_AUTO_A === 'true');
        $ftMap[self::FT_ID_UAC_ENF_PWC] = (self::FT_UAC_ENF_PWC === 'true');
        $ftMap[self::FT_ID_REPLICATE]   = (self::FT_REPLICATE === 'true');
        $ftMap[self::FT_ID_INTEG_CHK]   = (self::FT_INTEG_CHK === 'true');
        $ftMap[self::FT_ID_EXT]         = (self::FT_EXT === 'true');
        return $ftMap;
    }
    
    public static function getMinV4Ft($ft){
        $ftMap[self::FT_ID_RET]         = null;
        $ftMap[self::FT_ID_EXT]         = self::UD_OPT_1;
        $ftMap[self::FT_ID_CSV_EXP]     = self::UD_OPT_1;
        $ftMap[self::FT_ID_NOTI]        = self::UD_OPT_1;
        $ftMap[self::FT_ID_UAC_AUTO_A]  = self::UD_OPT_2;
        $ftMap[self::FT_ID_UAC_ENF_PWC] = self::UD_OPT_2;
        $ftMap[self::FT_ID_REPLICATE]   = self::UD_OPT_2;
        $ftMap[self::FT_ID_INTEG_CHK]   = self::UD_OPT_3;
        return $ftMap[$ft];
    }

    /**
     * @param $ft
     * @return mixed
     */
    public static function getFtSetting($ft){
        $ftMap = self::getFtMap();
        if(array_key_exists($ft, $ftMap)){
            return $ftMap[$ft];
        }
        return null;
    }

    /**
     * @param bool $includeVersion
     * @param bool $includeBuild
     * @return string
     */
    public static function getProductName($includeVersion=false, $includeBuild=true){
        if($includeVersion){
            return (self::PT_NAME.' '.self::getProductVersion($includeBuild));
        }
        return self::PT_NAME;
    }

    /**
     * @return string
     */
    public static function getProductDate(){
        return self::PT_DATE;
    }

    /**
     * @param bool $includeBuild
     * @param bool $includeBeta
     * @return string
     */
    public static function getProductVersion($includeBuild=false, $includeBeta=true){
        $version = self::PT_TOP.'.'.self::PT_SUB.'.'.self::PT_FIX;
        if($includeBeta && self::PT_BET && strlen(self::PT_BET)>0 && self::PT_BET !== '-'){
            $version .= ' - '.self::PT_BET;
        }
        if($includeBuild){
            return ($version.' BUILD '.self::PT_BLD);
        }
        return $version;
    }

    /**
     * @return bool
     */
    public static function upgradePossible(){
        return (self::UD_OK === 'true');
    }

    /**
     * @return string
     */
    public static function getUpgradeLink(){
        return self::UD_LINK;
    }

    /**
     * @return string
     */
    public static function getUpgradeOption(){
        return self::UD_VERSION;
    }
    
}