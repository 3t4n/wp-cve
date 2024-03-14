<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/21/2019
 * Time: 7:16 AM
 */

namespace rnpdfimporter\core\Integration\Processors\Settings\Forms;


use rnpdfimporter\core\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;

class FormSettings
{
    public $Id;
    public $OriginalId;
    public $Name;
    /** @var FieldSettingsBase []*/
    public $Fields;
    /** @var array $EmailNotifications */
    public $EmailNotifications;

    public function __construct()
    {
        $this->EmailNotifications=[];
        $this->Fields=[];
    }

    public function AddFields($field)
    {
        $this->Fields[]=$field;
    }


}

class EmailNotification{
    public $Name;
    public $Id;

    public function __construct($id,$name)
    {
        $this->Id=$id;
        $this->Name=$name==null?"Default":$name;
    }


}