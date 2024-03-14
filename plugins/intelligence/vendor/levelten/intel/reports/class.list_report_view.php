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

class ListReportView extends ReportView {
  private $tableRowCount = 10;
  
  function __construct() {
    parent::__construct();
  }
  
  function setTableRowCount($rowCount) {
    $this->tableRowCount = $rowCount;
  }
  
  function renderReport() {
    $output = '';
    $startDate = $this->dateRange['start'];
    $endDate = $this->dateRange['end'];
    $reportDays = $this->dateRange['days'];
    $reportModes = $this->modes;
    $targets = $this->targets;
    $indexBy = $this->params['indexBy'];
    $indexByLabel = !empty($this->params['indexByLabel']) ? $this->params['indexByLabel'] : $this->params['indexBy'];
    $context = $this->params['context'];
    $enablePageCount = isset($this->params['enablePageCount']) ? $this->params['enablePageCount'] : 0;

    $isComp = 0;
    $entranceScope = 'entrance';
    $pageviewScope = 'entrance';
    $eventScope = 'entrance';
    $goalScope = 'entrance';

    if ($context == 'page' || $context == 'page-attr') {
      $pageviewScope = 'pageview';
      $eventScope = 'pageview';
    }

    // if content report, flip to pageview context
    if ($indexBy == 'content') {
      if ($context == 'visitor') {
        $pageviewScope = 'pageview';
        $eventScope = 'pageview';
        $goalScope = 'pageview';
      }
    }

    /*
    if ($context == 'visitor') {
      $pageviewScope = 'pageview';
      $eventScope = 'pageview';
      $goalScope = 'pageview';
    }
    */

    // set formaters and data sources for standard & trend reports
    $dataSource = $indexBy;
    $metricSource = $indexBy;

    $entranceFormat = '#,###';
    $pageviewFormat = '#,###';
    $valueFormat = '#,###.##';
    $entranceDeltaFormat = '+#,###.#';
    $pageviewDeltaFormat = '+#,###.#';
    $valueDeltaFormat = '+#,###.##';

    if ($reportModes[1] == 'trend') {
      $isComp = 1;
      $dataSource = $indexBy . '_delta';
      $metricSource = $indexBy . '_comp';
    }

    $sortColumn = 5;
    //$init = ($isComp) ? 'entrances_pageviews_value_indicators_deltas' : 'entrances_pageviews_value_indicators';
    $init = ($isComp) ? 'entrances_pageviews_vevents_goals_value_indicators_deltas' : 'entrances_pageviews_vevents_goals_value_indicators';
    $table = new TableChart($init);
    $table->setColumnElement(0, 'label', $indexByLabel);
    if ($enablePageCount) {
      $table->insertColumn(1, 'number', 'Pages');
      $table->insertColumn(8, 'number', 'Val/day/pg');
      $table->insertColumn(10, 'number', 'Entrs/day/pg');
      $sortColumn++;
    }
    $table->setOption('sortColumn', $sortColumn);

    if ($isComp && TRUE) {
      $table->insertColumn(2, 'number', '&Delta;');
      $table->insertColumn(4, 'number', '&Delta;');
      $table->insertColumn(6, 'number', '&Delta;');
      $table->insertColumn(8, 'number', '&Delta;');
      $table->insertColumn(10, 'number', '&Delta;');
      $table->setOption('sortColumn', 10);
    }

    $entrs_pie_chart = new PieChart();
    $entrs_pie_chart->setOption('title', 'Entrances');
    $entrs_pie_chart->addColumn('string', $indexByLabel);
    $entrs_pie_chart->addColumn('number', 'Entrances');

    $total = !empty($this->data[$dataSource]['_totals']['entrance']['entrances']) ? $this->data[$dataSource]['_totals']['entrance']['entrances'] : 0;
    $entrs_pie_chart->setSetting('total', $total);
    if ($reportModes[1] != 'trend') {
      $entrs_pie_chart->setSetting('useTotal', 1);
    }
    
    $value_pie_chart = new PieChart();
    $value_pie_chart->setOption('title', 'Value');
    $value_pie_chart->addColumn('string', $indexByLabel);
    $value_pie_chart->addColumn('number', 'Entrances');

    $total = !empty($this->data[$dataSource]['_totals']['score']) ? $this->data[$dataSource]['_totals']['score'] : (!empty($this->data[$dataSource]['_all']['score']) ? $this->data[$dataSource]['_all']['score'] : 0);
    $value_pie_chart->setSetting('total', $total);
    if ($reportModes[1] != 'trend') {
      $value_pie_chart->setSetting('useTotal', 1);
    }
    
    $bubble_chart = new BubbleChart();
    $item = array(
      'type' => 'string',
      'label' => $this->getParam('itemLabel'),
    );
    $bubble_chart->addColumn($item);
    
    $item = array(
      'type' => 'number',
      'label' => 'Value/entr',
      'pattern' => '#,###.##',
    );
    $bubble_chart->addColumn($item);
    $item = array(
      'type' => 'number',
      'label' => 'Entr/day',
      'pattern' => '#,###.#',
    );
    $bubble_chart->addColumn($item);
    $hAxis = array(
      'title' => 'Value/entrance',
      'format' => '#,###.##',
    );
    $bubble_chart->setOption('hAxis', $hAxis);
    $vAxis = array(
      'title' => 'Entrances/day',
      'format' => '#,###.#',
    );
    $bubble_chart->setOption('vAxis', $vAxis);
    
    $item = array(
      'type' => 'number',
      'label' => 'Value/day',
      'pattern' => '#,###.##',
    );
    $bubble_chart->addColumn($item);

    $chartArea = array(
      'left' => 50,
      'top' =>  30,
      'width' => "95%",
      'height' => 330,
    );
    $bubble_chart->setOption('chartArea', $chartArea);

    $colorAxis = array(
      'minValue' => $targets['value_per_page_per_day_warning'],
      'maxValue' => $targets['value_per_page_per_day'],
      'colors' => array(
        $this->chartColors[2],
        $this->chartColors[3],
        $this->chartColors[1],
      ),
    );
    $bubble_chart->setOption('colorAxis', $colorAxis);
    $bubble_chart->setSetting('div_height', '400px');

    $value_str = '';

    // data loop
    $this->sortData('by_score_then_entrances', $dataSource);

    $linkItem = array(
      'content' => 1,
      'referralHostpath' => 1,
    );
//dsm($this->data);
//dsm($this->targets);
    $itemCount = isset($this->data[$dataSource]['_info']['item_count']) ? $this->data[$dataSource]['_info']['item_count'] : (count($this->data[$dataSource]) - 2);
    $divTargetByItemCount = 0;
    if ($context == 'page-attr') {
      $divTargetByItemCount = 1;
    }
    if (empty($this->data[$dataSource]) || !is_array($this->data[$dataSource])) {
      $this->data[$dataSource] = array();
    }
    foreach($this->data[$dataSource] AS $n => $d) {
      if (empty($d['i']) || (substr($d['i'], 0 , 1) == '_')) { continue; }

      // for trend reports, score is a delta and can be negative. Don't allow
      // in report
      if ($d['score'] < 0) {
        break;
      }

      $entrancesDelta = $entrances = !empty($d[$entranceScope]['entrances']) ? $d[$entranceScope]['entrances'] : 0;
      $pageviewsDelta = $pageviews = !empty($d[$pageviewScope]['pageviews']) ? $d[$pageviewScope]['pageviews'] : 0;
      $events = 0;
      $goals = 0;
      $pageCount = ($indexBy == 'content') ? 1 : 0;
      if ($eventScope) {
        $eventsDelta = $events = !empty($d[$eventScope]['events']['_all']['totalValuedEvents']) ? $d[$eventScope]['events']['_all']['totalValuedEvents'] : 0;
      }
      if ($goalScope) {
        // have to get a little creative to get session (assist goals) based on what is being indexed
        if ($goalScope == 'session') {
          if ($indexBy == 'content') {
            //$goalsDelta = $goals = $d['pageview']['']['_all']['completions'] : 0;
            $goalsDelta = $goals = 0;
          }
          else {
            $goalsDelta = $goals = !empty($d[$goalScope]['goals']['_all']['completions']) ? $d[$goalScope]['goals']['_all']['completions'] : 0;
          }
        }
        else {
          $goalsDelta = $goals = !empty($d[$goalScope]['goalCompletionsAll']) ? $d[$goalScope]['goalCompletionsAll'] : 0;
        }
      }

      $valueDelta = $value = $d['score'];
      if ($isComp) {
        $entrances = !empty($this->data[$metricSource][$n]['entrance']['entrances']) ? $this->data[$metricSource][$n]['entrance']['entrances'] : 0;
        $pageviews = !empty($this->data[$metricSource][$n]['pageview']['pageviews']) ? $this->data[$metricSource][$n]['pageview']['pageviews'] : 0;
        $value = !empty($this->data[$metricSource][$n]['score']) ? $this->data[$metricSource][$n]['score'] : 0;

        //$entrancesDelta = $entrances - $entrancesDelta;
        //$pageviewsDelta = $pageviews - $pageviewsDelta;
        //$valueDelta = $value - $valueDelta;
      }
      //$days = isset($d['score_dates']['days']) ? $d['score_dates']['days'] : 0;
      $days = $reportDays;
      $itemLabel = $this->formatRowString($d['i'], 60);

      $table->newWorkingRow();
      if (isset($d['info'])) {
        $itemLabel = $this->formatRowString($d['info']['title'], 60);
        if (isset($d['info']['uri'])) {
          $table->addRowItem(render::link($itemLabel, $d['info']['uri']));
        }
        else {
          $table->addRowItem($itemLabel);
        }

        if ($enablePageCount) {
          if (isset($d['info']['page_count'])) {
            $pageCount = (int)$d['info']['page_count'];
            $table->addRowItem($pageCount, '', '#,###');
          }
          else {
            $table->addRowItem(0, '', '#,###');
          }
        }
      }
      else {
        if (!empty($linkItem[$indexBy])) {
          // check if link is relative or absolute
          $a = explode('/', $d['i'], 2);
          $host = $a[0];
          $path = isset($a[1]) ? $a[1] : '';
          $url = ($host ? "http://$host" : '') . "/$path";
          $table->addRowItem(render::link($itemLabel, $url, array('attributes' => array('target' => '_blank'))));
        } else {
          $table->addRowItem($itemLabel);
        }
        if ($enablePageCount) {
          $table->addRowItem(0, '', '#,###');
        }
      }
      
      $table->addRowItem($entrances, '', $entranceFormat);
      if ($isComp) {
        $table->addRowItem($entrancesDelta, '', $entranceDeltaFormat);
      }
      
      $table->addRowItem($pageviews, '', $pageviewFormat);
      if ($isComp) {
        $table->addRowItem($pageviewsDelta, '', $pageviewDeltaFormat);
      }

      if ($eventScope) {
        $table->addRowItem($events, '', $pageviewFormat);
        if ($isComp) {
          $table->addRowItem($eventsDelta, '', $pageviewDeltaFormat);
        }
      }
      if ($goalScope) {
        $table->addRowItem($goals, '', $pageviewFormat);
        if ($isComp) {
          $table->addRowItem($goalsDelta, '', $pageviewDeltaFormat);
        }
      }

      $table->addRowItem($value, '', $valueFormat);
      if ($isComp) {
        $table->addRowItem($valueDelta, '', $valueDeltaFormat);
      }
      $type_mod = '';
      if ($indexBy == 'content') {
        $type_mod = '_per_page';
      }
      elseif ($context == 'trafficsource') {
        $type_mod = '_per_trafficsource';
      }
      $vars = array('value' => $value, 'type' => "value{$type_mod}_per_day", 'days' => $days);
      if ($divTargetByItemCount) {
        $vars['target_div'] = $itemCount;
      }
      $format = $this->renderValueScore($vars, $value_str);
      $table->addRowItem((float)$value_str, $format);

      if ($enablePageCount) {
        if ($pageCount) {
          $vars = array('value' => $value, 'type' => "value_per_page_per_day", 'days' => $days, 'pages' => $pageCount);
          $format = $this->renderValueScore($vars, $value_str);
          $table->addRowItem((float)$value_str, $format);
        }
        else {
          $table->addRowItem(0);
        }
      }

      if ($entrances) {
        $vars = array('value' => $entrances, 'type' => "entrances{$type_mod}_per_day", 'days' => $days);
        if ($divTargetByItemCount) {
          $vars['target_div'] = $itemCount;
        }
        $format = $this->renderValueScore($vars, $value_str);
        $table->addRowItem((float)$value_str, $format);

        if ($enablePageCount) {
          if ($pageCount) {
            $vars = array('value' => $entrances, 'type' => "entrances_per_page_per_day", 'days' => $days, 'pages' => $pageCount);
            $format = $this->renderValueScore($vars, $value_str);
            $table->addRowItem((float)$value_str, $format);
          }
          else {
            $table->addRowItem(0);
          }
        }

        if ($context == 'page-attr') {
          $type_mod = '_per_page';
        }

        $vars = array('value' => $value, 'type' => "value{$type_mod}_per_entrance", 'entrances' => $entrances);
        $format = $this->renderValueScore($vars, $value_str);
        $table->addRowItem((float)$value_str, $format);
      }
      else {
        $table->addRowItem(0);
        if ($enablePageCount) {
          $table->addRowItem(0);
        }
        $table->addRowItem(0);
      }

      $table->addRowItem(implode(' ', $d['links']));
      $table->addRow();
      
      $entrs_pie_chart->addRow(array($itemLabel, $entrances));

      $value_pie_chart->addRow(array($itemLabel, $value));

      $vpe = $entrances ? $value/$entrances : 0;
      $epd = $entrances/$days;
      $bubble_chart->addRow(array($itemLabel, round($vpe, 2), round($epd, 1), round($value/$days, 2)));

      if ($table->curRowCount >= $this->tableRowCount) {
        break;
      }
    }
//dsm($table);

    $bubble_chart->setSetting('plotThreshold', array('columnIndex' => 2, 'minValue' => 1));
    $output = '';
    $output .= '<div id="content-section" class="report-section">';
    $output .= '<div class="pane-left-40">';
    $output .= $value_pie_chart->renderOutput();
    $output .= $entrs_pie_chart->renderOutput();
    $output .= '</div>';
    $output .= '<div class="pane-spacer">&nbsp;</div>';
    $output .= '<div class="pane-right-60">';
    $output .= $bubble_chart->renderOutput();
    $output .= '</div>'; 
    $output .= '</div>';   
    
    $output .= '<div id="content-section" class="report-section">';
    $output .= '<h3>' . $this->params['headerLabel'] . '</h3>';
    //$output .= $out_table; 
    $output .= $table->renderOutput();
    $output .= '</div>';  
    
    $output .= 'generated by <a href="http://www.intelligencewp.com" target="_blank">Intelligence</a>';
    
    return '<script>var _chart = _chart || [];</script><div id="intel-report">' . $output . '</div>';
  }
}