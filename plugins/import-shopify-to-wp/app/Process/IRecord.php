<?php

namespace S2WPImporter\Process;

interface IRecord
{
    public function __construct($item, $obj);

    public function parse();

    public function beforeSave();

    public function save();

    public function afterSave($objId);
}
