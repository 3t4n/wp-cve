<?php

namespace HQRentalsPlugin\HQRentalsHelpers;

use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsVendor\Carbon;

class HQRentalsDatesHelper
{
    protected $replacements = [
        'A' => 'A',      // for the sake of escaping below
        'a' => 'a',      // for the sake of escaping below
        'B' => '',       // Swatch internet time (.beats), no equivalent
        'c' => 'YYYY-MM-DD[T]HH:mm:ssZ', // ISO 8601
        'D' => 'ddd',
        'd' => 'DD',
        'e' => 'zz',     // deprecated since version 1.6.0 of moment.js
        'F' => 'MMMM',
        'G' => 'H',
        'g' => 'h',
        'H' => 'HH',
        'h' => 'hh',
        'I' => '',       // Daylight Saving Time? => moment().isDST();
        'i' => 'mm',
        'j' => 'D',
        'L' => '',       // Leap year? => moment().isLeapYear();
        'l' => 'dddd',
        'M' => 'MMM',
        'm' => 'MM',
        'N' => 'E',
        'n' => 'M',
        'O' => 'ZZ',
        'o' => 'YYYY',
        'P' => 'Z',
        'r' => 'ddd, DD MMM YYYY HH:mm:ss ZZ', // RFC 2822
        'S' => 'o',
        's' => 'ss',
        'T' => 'z',      // deprecated since version 1.6.0 of moment.js
        't' => '',       // days in the month => moment().daysInMonth();
        'U' => 'X',
        'u' => 'SSSSSS', // microseconds
        'v' => 'SSS',    // milliseconds (from PHP 7.0.0)
        'W' => 'W',      // for the sake of escaping below
        'w' => 'e',
        'Y' => 'YYYY',
        'y' => 'YY',
        'Z' => '',       // time zone offset in minutes => moment().zone();
        'z' => 'DDD',
    ];
    public function __construct()
    {
        $this->carbon = new Carbon();
        $this->settings = new HQRentalsSettings();
    }

    public $systemSupportedDatesFormats = array(
        'iso' => 'Y-m-d H:i',
        'eu' => 'd-m-Y H:i',
        'eu_a' => 'd-m-Y g:iA',
        'us' => 'm/d/Y g:iA',
        'us_c' => 'm/d/Y g:i',
        'us_G' => 'm/d/Y G:i',
        've' => 'd/m/Y g:iA',
        'int' => 'd.m.Y g:iA',
        'id' => 'd-M-Y g:iA',
        'ch' => 'd.m.Y H:i',
        'ch_c' => 'Y-m-d G:i',
        'wp_c' => 'd-m-Y G:i',
        'wp_d' => 'Y-m-d G:i',
        'wp_e' => 'd/m/Y G:i'
    );
    public $flatpickReplacements = array(
        'Y-m-d H:i' => 'Y-m-d H:i',
        'd-m-Y H:i' => 'd-m-Y H:i',
        'm/d/Y g:i' => 'm/d/Y h:i',
        'm/d/Y G:i' => 'm/d/Y H:i',
        'd/m/Y g:iA' => 'd/m/Y h:iK',
        'd.m.Y g:iA' => 'd.m.Y h:iK',
        'd-M-Y g:iA' => 'd-M-Y h:iK',
        'd-m-Y g:iA' => 'd-m-Y h:iK',
        'm/d/Y g:iA' => 'm/d/Y h:iK',
        'd/m/Y g:i A' => 'd/m/Y h:i K',
        'd.m.Y g:i A' => 'd.m.Y h:i K',
        'd-M-Y g:i A' => 'd-M-Y h:i K',
        'd-m-Y g:i A' => 'd-m-Y h:i K',
        'm/d/Y g:i A' => 'm/d/Y h:i K',
        'd.m.Y H:i' => 'd.m.Y H:i',
        'Y-m-d G:i' => 'Y-m-d H:i',
        'd-m-Y G:i' => 'd-m-Y H:i',
        'd/m/Y G:i' => 'd/m/Y H:i'
    );

    public function getHtmlOptionForFrontEndDateSettingOption()
    {
        $html = '';
        foreach ($this->systemSupportedDatesFormats as $datesFormat) {
            if ($this->settings->getFrontEndDatetimeFormat() == $datesFormat) {
                $html .= '<option value="' . esc_attr($datesFormat) . '" selected="selected">' . esc_attr($this->carbon->format($datesFormat)) . '</option>';
            } else {
                $html .= '<option value="' . esc_attr($datesFormat) . '">' . esc_attr($this->carbon->format($datesFormat)) . '</option>';
            }
        }
        return $html;
    }

    public function getHtmlOptionForSystemDateSettingOption()
    {
        $html = '';
        foreach ($this->systemSupportedDatesFormats as $datesFormat) {
            if ($this->settings->getHQDatetimeFormat() == $datesFormat) {
                $html .= '<option value="' . esc_attr($datesFormat) . '" selected="selected">' . esc_attr($this->carbon->format($datesFormat)) . '</option>';
            } else {
                $html .= '<option value="' . esc_attr($datesFormat) . '">' . esc_attr($this->carbon->format($datesFormat)) . '</option>';
            }
        }
        return $html;
    }

    public function getDateFormatFromCarbon($format)
    {
        return explode(' ', $format)[0];
    }

    public function getTimeFormatFromCarbon($format)
    {
        return explode(' ', $format)[1];
    }

    public function convertPhpToJsMomentFormat(string $phpFormat): string
    {
        // Converts escaped characters.
        foreach ($this->replacements as $from => $to) {
            $this->replacements['\\' . $from] = '[' . $from . ']';
        }
        return strtr($phpFormat, $this->replacements);
    }
    public function convertPhpToJsFlatpickrFormat(string $phpFormat): string
    {
        try {
            return $this->flatpickReplacements[$phpFormat];
        } catch (\Throwable $e) {
            return $this->flatpickReplacements['Y-m-d H:i'];
        }
    }
    public function getTimeFormatFromPHPDate($format)
    {
        $splitFormat = explode(' ', $format);
        return implode(
            " ",
            array_splice($splitFormat, 1, count($splitFormat))
        );
    }
    public function parseFullWebsiteDateTimeToApiDate($date): string
    {
        // system
        $settings = new HQRentalsSettings();
        $carbon = Carbon::createFromFormat($settings->getTenantDatetimeFormat(), $date);
        return $carbon->format('Y-m-d');
    }
    public function parseFullWebsiteDateTimeToApiTime($date): string
    {
        // system
        $settings = new HQRentalsSettings();
        $carbon = Carbon::createFromFormat($settings->getTenantDatetimeFormat(), $date);
        return $carbon->format('H:i');
    }
}
