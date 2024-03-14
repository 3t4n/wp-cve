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
require_once 'class.report_view.php';
require_once __DIR__ . '/../charts/class.table_chart.php';
require_once __DIR__ . '/../charts/class.pie_chart.php';
require_once __DIR__ . '/../charts/class.bubble_chart.php';
require_once __DIR__ . '/../charts/class.column_chart.php';
require_once __DIR__ . '/../charts/class.combo_chart.php';

class EventListReportView extends ReportView {
  private $tableRowCount = 10;
  
  function __construct() {
    parent::__construct();
  }
  
  function setTableRowCount($rowCount) {
    $this->tableRowCount = $rowCount;
  }
  
  function renderReport() {
    $output = '';
    $data = $this->data;
    $datasum = $data['date']['_all'];
    $context = $this->params['context'];
    $context_mode = $this->params['context_mode'];
    $startDate = $this->dateRange['start'];
    $endDate = $this->dateRange['end'];

    $eventType = '';

    $eventScope = 'entrance';
    if ($context == 'site' || $context == 'page' || $context == 'page-attr' || $context == 'event') {
      $eventScope = 'pageview';
    }

    $table = new TableChart('events_value');
    //dsm($datasum[$eventSrc]);
    //dsm($datasum[$eventSrc]['events']['_all']['totalEvents'] . " == " . $datasum[$eventSrc]['events']['_all']['totalValuedEvents']);
    // if totalEvents == totalValuedEvents, this category is all standard events
    if (!empty($datasum[$eventScope]['events']['_all'])) {
      if ($datasum[$eventScope]['events']['_all']['totalEvents'] == $datasum[$eventScope]['events']['_all']['totalValuedEvents']) {
        $eventType = 'valued';
      }
      // if totalEvents == totalValuedEvents, this category is all standard events
      elseif ($datasum[$eventScope]['events']['_all']['totalValuedEvents'] == 0) {
        $eventType = 'nonvalued';
      }
    }


    if ($eventType == 'valued') {
      $table->setOption('sortColumn', 2);
    }
    else if ($eventType == 'nonvalued') {
      $table->setOption('sortColumn', 1);
    }
    else {
      $table->setOption('sortColumn', 1);
      $table->insertColumn(3, 'number', 'Valued events');
      $table->insertColumn(4, 'number', 'Value');
    }



    // Goals
    $rowlimit = 100;
    $chartdata_goals = array();
    $chartdata_events = array();
    $dateChartColsLimit = 8;
    $dateChartUseOther = 0;
    $displayValueChart = 1;

    uasort($datasum[$eventScope]['events'], array($this, 'usort_by_value_then_totalValuedEvents'));
    $i = 1;
    $v['rows'] = array();
    $eventItems = array();
    foreach($datasum[$eventScope]['events'] AS $n => $d) {
      if (empty($d['i'])) { continue; }
      $table->newWorkingRow();
      $item = str_replace('->', ' > ', $d['i']);

      $table->addRowItem($item);
      if ($eventType != 'valued') {
        $table->addRowItem($d['totalEvents'], '', '#,###');
        $table->addRowItem($d['value'], '', '#,###');
      }
      if ($eventType != 'nonvalued') {
        $table->addRowItem($d['totalValuedEvents'], '', '#,###');
        $table->addRowItem($d['valuedValue'], '', '#,###.##');
      }

      $links = isset($d['links']) ? $d['links'] : array();
      $table->addRowItem(implode(' ', $links));
      $table->addRow();

      if ($i <= $dateChartColsLimit) {
        $eventItems[$d['i']] = $item;
      }
      else {
        $dateChartUseOther = 1;
      }

      if ($i++ >= $rowlimit) {
        break;
      }
    }

    $main_chart = new ComboChart('objectives');
    $main_chart->setOption('title', 'Events');
    $main_chart->setOption('isStacked', 1);
    //$leads_source_chart->setOption('title', 'Traffic sources');
    $main_chart->addColumn('date', 'Day');

    $value_chart = new ComboChart('objectives');
    $value_chart->setOption('title', 'Value');
    $value_chart->setOption('isStacked', 1);
    //$leads_source_chart->setOption('title', 'Traffic sources');
    $value_chart->addColumn('date', 'Day');

    $i = 0;
    foreach ($eventItems AS $k => $v) {
      $l = explode(' > ', $v);
      $label = isset($l[1]) ? $l[1] : $l[0];
      $main_chart->addColumn('number', $label);
      $series = array(
        'type' => "bars",
        'color' => $this->chartColors[$i],
      );
      $main_chart->setOption("series.$i", $series);

      $value_chart->addColumn('number', $label);
      $value_chart->setOption("series.$i", $series);
      $i++;
    }
    if ($dateChartUseOther) {
      $main_chart->addColumn('number', 'other');
      $series = array(
        'type' => "bars",
        'color' => 'AAAAAA',
      );
      $main_chart->setOption("series.$i", $series);

      $value_chart->addColumn('number', 'other');
      $value_chart->setOption("series.$i", $series);
    }

    $tinc = 60 * 60 * 24;
    //foreach ($data['date'] AS $day => $d) {
    for ($ts = $startDate; $ts < $endDate; $ts += $tinc) {
      $day = Date("Ymd", $ts);
      $d = isset($data['date'][$day]) ? $data['date'][$day] : array();
      $jstime = 1000 * $ts;

      $main_chart->newWorkingRow();
      $main_chart->addRowItem($jstime);

      $value_chart->newWorkingRow();
      $value_chart->addRowItem($jstime);
      $colEvents = 0;
      $colValue = 0;

      foreach ($eventItems AS $k => $v) {
        $events = isset($d[$eventScope]['events'][$k]['totalEvents']) ? $d[$eventScope]['events'][$k]['totalEvents'] : 0;
        $value = isset($d[$eventScope]['events'][$k]['value']) ? $d[$eventScope]['events'][$k]['value'] : 0;
        $colEvents += $events;
        $colValue += $value;
        $main_chart->addRowItem($events);
        $value_chart->addRowItem($value);
      }

      if ($dateChartUseOther) {
        $events = isset($d[$eventScope]['events']['_all']['totalEvents']) ? $d[$eventScope]['events']['_all']['totalEvents'] : 0;
        $value = isset($d[$eventScope]['events']['_all']['value']) ? $d[$eventScope]['events']['_all']['value'] : 0;
        // subtract value included in named columns
        $events -= $colEvents;
        $value -= $colValue;
        $main_chart->addRowItem($events);
        $value_chart->addRowItem($value);
      }
      $main_chart->addRowToSettings();
      $value_chart->addRowToSettings();
    }

    //dsm($table);

    /*
    usort($datasum[$eventSrc2]['events'],  array($this, 'usort_by_value_then_totalValuedEvents'));
    $i = 1;
    $v['rows'] = array();
    foreach($datasum[$eventSrc2]['events'] AS $cat => $d) {
      if (empty($d['i'])) { continue; }
      $events_2_table->newWorkingRow();
      $events_2_table->addRowItem($d['i']);
      $events_2_table->addRowItem($d['totalValuedEvents']);
      $events_2_table->addRowItem($d['value']);
      $events_2_table->addRow();
      if ($i++ >= $rowlimit) {
        break;
      }
    }

    /*
    $output .= '<div id="goals-section" class="report-section">';
    $output .= '<h3>Goals &amp; valued events</h3>';
    $output .= '<div class="pane-left">';
    $output .= $pie_chart1->renderOutput();
    $output .= '</div><div class="pane-spacer">&nbsp;</div>';
    $output .= '<div class="pane-right">';
    if ($context == 'page' || $context == 'page-attr') {
      $output .= '<h3>Valued events (onpage)</h3>';
      $output .= $events_2_table->renderOutput();
    }
    else {
      $output .= $pie_chart2->renderOutput();
    }
    $output .= '</div>';

    $output .= '<div class="pane-left" style="clear: left;">';
    $output .= '<h3>Goals' . (($context == 'page'  || $context == 'page-attr') ? ' (entrance)': '') . '</h3>';
    $output .= $goals_table->renderOutput();
    $output .= '</div><div class="pane-spacer">&nbsp;</div>';
    $output .= '<div class="pane-right">';
    $output .= '<h3>Valued events' . (($context == 'page' || $context == 'page-attr') ? ' (entrance)': '') . '</h3>';
    $output .= $table->renderOutput();
    $output .= '</div>';
    $output .= '</div>';
*/


    /*
    $output .= '<div id="content-section" class="report-section">';
    $output .= '<div class="pane-left-40">';
    $output .= $entrs_pie_chart->renderOutput();
    $output .= $value_pie_chart->renderOutput();
    $output .= '</div>';
    $output .= '<div class="pane-spacer">&nbsp;</div>';
    $output .= '<div class="pane-right-60">';
    $output .= $bubble_chart->renderOutput();
    $output .= '</div>';
    $output .= '</div>';
*/

    $output = '<div id="report-main-chart">';
    $output .= $main_chart->renderOutput();
    $output .= $value_chart->renderOutput();

    $output .= '</div>';

    $output .= '<div id="event-section" class="report-section">';
    $output .= '<h3>' . 'Events' . '</h3>';
    //$output .= $out_table;
    $output .= $table->renderOutput();
    $output .= '</div>';

    $output .= 'generated by <a href="http://levelten.net" target="_blank">LevelTen Intelligence</a>';
    
    return '<div id="intel-report">' . $output . '</div>';
  }
}