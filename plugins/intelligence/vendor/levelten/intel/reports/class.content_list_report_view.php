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

class ContentListReportView extends ListReportView {
  private $tableRowCount = 10;

  function __construct() {
    parent::__construct();
  }

  function renderReport() {
    $this->setParam('headerLabel', 'Pages');
    $this->setParam('indexBy', 'content');
    $this->setParam('indexByLabel', 'Path');
    if ($this->params['context'] == 'event') {
      $this->params['context'] = 'page';
    }
    return parent::renderReport();
  }

}