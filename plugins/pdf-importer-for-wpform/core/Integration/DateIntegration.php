<?php


namespace rnpdfimporter\core\Integration;


use rnpdfimporter\core\Loader;

class DateIntegration
{
    public $Loader;

    /**
     * DateIntegration constructor.
     * @param $Loader Loader
     */
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetTimezonedDateFromUTCDate($dateString,$format='Y-m-d H:i:s'){
        return \get_date_from_gmt($dateString,$format);
    }

    public function GetUTCDateFromTimezonedDate($dateString,$format='Y-m-d H:i:s'){
        return \get_date_from_gmt($dateString,$format);
    }

    public function GetSiteTimeOffset()
    {
        return get_option('gmt_offset');
    }


}