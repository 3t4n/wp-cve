<?php
/**
 * @file
 * @author  Tom McCracken <tomm@levelten.net>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */
namespace LevelTen\Intel;
require_once 'class.list_report_view.php';

class AudienceListReportView extends ListReportView {

  function renderReport() {
    //$this->setParam('indexBy', 'content');
    $this->setParam('headerLabel', 'Audience');
    //$this->setParam('indexByLabel', 'Path');
    return parent::renderReport();
  }
}