<?php


namespace rnpdfimporter\core\Integration;


class SiteIntegration
{
    /** @var $Loader */
    public $Loader;
    public function __construct($loader)
    {
        $this->Loader=$loader;
    }

    public function GetEmail(){
        return \get_bloginfo('admin_email');
    }

}