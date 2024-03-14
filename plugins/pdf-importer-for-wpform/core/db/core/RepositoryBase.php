<?php


namespace rnpdfimporter\core\db\core;


use rnpdfimporter\core\Loader;

abstract class RepositoryBase
{
    /** @var Loader */
    public $Loader;

    /** @var DBManager */
    public $DBManager;

    public function __construct($loader)
    {
        $this->Loader=$loader;
        $this->DBManager=new DBManager();
    }

}