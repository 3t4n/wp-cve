<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_DateUtils
{
    public static function getUTCforMySQLTimestamp($dateTime=null){
        if(is_null($dateTime)){
            $dateTime = 'now';
        }
        try {
            $dateUTC = new DateTime($dateTime, new DateTimeZone('UTC'));
            return $dateUTC->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            WADA_Log::error('WADA_DateUtils/getUTCforMySQLTimestamp error: '.$e->getMessage());
            WADA_Log::error('dateTime: '.$dateTime);
        }
        return false;
    }


    public static function getUTCforMySQLDate($dateTime=null){
        if(is_null($dateTime)){
            $dateTime = 'now';
        }
        try {
            $dateUTC = new DateTime($dateTime, new DateTimeZone('UTC'));
            return $dateUTC->format('Y-m-d');
        } catch (Exception $e) {
            WADA_Log::error('WADA_DateUtils/getUTCforMySQLDate error: '.$e->getMessage());
            WADA_Log::error('dateTime: '.$dateTime);
        }
        return false;
    }

    public static function getUTCforMySQLTimestampFromUnixTime($unixTimestamp){
        $unixTimestampInt = intval($unixTimestamp);
        try {
            $dateUTC = new DateTime('@' . $unixTimestampInt);
            return $dateUTC->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            WADA_Log::error('WADA_DateUtils/getUTCforMySQLTimestampFromUnixTime error: '.$e->getMessage());
            WADA_Log::error('unixTimestamp: '.$unixTimestamp);
        }
        return false;
    }

    /**
     * @param string $utcDateTimeStr
     * @param string $locale
     * @return false|string
     */
    public static function formatUTCasDatetimeForWP($utcDateTimeStr, $locale = null){
        list($dateType, $timeType) = WADA_Settings::getDateFormatForDatetime(true, true);
        return self::doIntlDateFormatting($utcDateTimeStr, $dateType, $timeType, $locale);
    }

    /**
     * @param string $utcDateTimeStr
     * @param string $locale
     * @return false|string
     */
    public static function formatUTCasDateForWP($utcDateTimeStr, $locale = null){
        $dateType = WADA_Settings::getDateFormatForDateOnly(true);
        $timeType = IntlDateFormatter::NONE;
        return self::doIntlDateFormatting($utcDateTimeStr, $dateType, $timeType, $locale);
    }

    protected static function doIntlDateFormatting($utcDateTimeStr,
                                                   $dateType, $timeType,
                                                   $locale = null,
                                                   $sourceTimezone = null, $targetTimezone = null){
        if(!$sourceTimezone){ // default source timezone is UTC, this is what we use
            $sourceTimezone = new DateTimeZone('UTC');
        }
        if(!$targetTimezone){ // target = whatever WP user wants to have
            $targetTimezone = wp_timezone();
        }
        if(!$locale){
            $locale = get_user_locale();
        }
        try {
            $dateUTC = new DateTime($utcDateTimeStr, $sourceTimezone);
            $dateUTC->setTimezone($targetTimezone);

            $dateFormatter = new IntlDateFormatter(
                $locale,
                $dateType,
                $timeType,
                $targetTimezone
            );
            return $dateFormatter->format($dateUTC);
        } catch (Exception $e) {
            WADA_Log::error('WADA_DateUtils/doIntlDateFormatting error: '.$e->getMessage());
            WADA_Log::error('utcDateTimeStr: '.$utcDateTimeStr);
            WADA_Log::error('dateType: '.$dateType);
            WADA_Log::error('timeType: '.$timeType);
            WADA_Log::error('sourceTimezone (name): '.(($sourceTimezone && method_exists($sourceTimezone, 'getName')) ? $sourceTimezone->getName() : ''));
            WADA_Log::error('targetTimezone (name): '.(($targetTimezone && method_exists($targetTimezone, 'getName')) ? $targetTimezone->getName() : ''));
        }
        return false;
    }

    public static function timeAgo($utcDateTimeStr){
        try {
            $from = self::formatUTCdateTimeAsUnixTimestamp($utcDateTimeStr);
            $to = self::getUTCUnixTimestamp();
            $diff = human_time_diff($from, $to);
            return sprintf(__('%s ago', 'wp-admin-audit'), $diff);
        } catch (Exception $e) {
            WADA_Log::error('WADA_DateUtils/timeAgo error: '.$e->getMessage());
            WADA_Log::error('utcDateTimeStr: '.$utcDateTimeStr);
        }
    }

    /**
     * @throws Exception
     */
    public static function getUTCUnixTimestamp(){
        return self::formatUTCdateTimeAsUnixTimestamp('now');
    }

    /**
     * @throws Exception
     */
    public static function formatUTCdateTimeAsUnixTimestamp($utcDateTimeStr){
        $dateUTC = new DateTime($utcDateTimeStr, new DateTimeZone('UTC'));
        return $dateUTC->getTimestamp();
    }

    public static function formatWPDatetimeForWP($wpDateTimeStr, $locale = null){
        // only speciality is that the source timezone here is not the UTC, but WP timezone as well (same as target)
        $sourceTimezone = wp_timezone();
        $targetTimezone = wp_timezone();
        list($dateType, $timeType) = WADA_Settings::getDateFormatForDatetime(true, true);
        return self::doIntlDateFormatting($wpDateTimeStr, $dateType, $timeType, $locale, $sourceTimezone, $targetTimezone);
    }
}