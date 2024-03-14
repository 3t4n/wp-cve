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
require_once ('class.chart.php');

class TableChart extends Chart {
  public function __construct($type = '', $data = array(), $options = array(), $settings = array()) {
    if (count($options) == 0) {
      $options = $this->getDefaultOptions($type);
    }
    if (count($settings) == 0) {
      $settings = $this->getDefaultSettings($type);
    }
    parent::__construct($data, $options, $settings);
  }
  
  function getDefaultOptions($type = '') {
    if ($type == 'entrances_pageview_value_indicators' || $type == 'entrances_pageview_value_indicators_deltas') {
      $options = array(
        'allowHtml' => 1,
        'pageSize' => $this->rowCount,
        'sortColumn' => 3,
        'sortAscending' => 0,
        'showRowNumber' => 1,
      );
      if ($type == 'entrances_pageview_value_indicators_deltas') {
        $options['sortColumn'] = 6;
      }
    }
    else {
      $options = array(
        'allowHtml' => 1,
        'sortAscending' => 0,
        'showRowNumber' => 1,
      );     
    }
    return $options;
  }
  
  function getDefaultSettings($type = '') {
    $settings = array(
      'cols' => $this->getDefaultColumns($type),
      'rows' => array(),
      'formatters' => array(),
      'div_width' => '100%',
    );
    return $settings;  
  }
  
  function getColumns() {
    $header = $this->settings['cols'];
    return $header;
  }
  
  function getDefaultColumns($type = '') {
    $header = array();

    if ($type == 'events_value') {
      $header[] = array(
        'label' => 'Categories',
        'type' => 'string',
      );
      $header[] = array(
        'label' => 'Events',
        'type' => 'number',
        'pattern' => '#,###',
      );
      $header[] = array(
        'label' => 'Value',
        'type' => 'number',
        'pattern' => '#,###.##',
      );
      $header[] = array(
        'label' => 'Reports',
        'type' => 'string',
      );
    }
    else if (strpos($type, 'value_indicators') !== FALSE) {
      $header[] = array(
        'label' => 'Items',
        'type' => 'string',
      );
      $header[] = array(
        'label' => 'Entrances',
        'type' => 'number',
        'pattern' => '#,###',
      );
      $header[] = array(
        'label' => 'Pageviews',
        'type' => 'number',
        'pattern' => '#,###',
      );
      if (strpos($type, 'vevents') !== FALSE) {
        $header[] = array(
          'label' => 'V.&nbsp;Evts',
          'type' => 'number',
          'pattern' => '#,###',
        );
      }
      if (strpos($type, 'goals') !== FALSE) {
        $header[] = array(
          'label' => 'Goals',
          'type' => 'number',
          'pattern' => '#,###',
        );
      }
      $header[] = array(
        'label' => 'Value',
        'type' => 'number',
        'pattern' => '#,###.##',
      );
      $header[] = array(
        'label' => 'Value/day',
        'type' => 'number',
        'pattern' => '#,###.##',
      );
      $header[] = array(
        'label' => 'Entrs/day',
        'type' => 'number',
        'pattern' => '#,###.#',
      );
      $header[] = array(
        'label' => 'Value/entrs',
        'type' => 'number',
        'pattern' => '#,###.##',
      );
      $header[] = array(
        'label' => 'Reports',
        'type' => 'string',
      );
    }
    return $header;
  }
  
  /**
   * Resets the workingRow
   */
  function newWorkingRow() {
    $this->workingRow = array();
  }
  
  /**
   * Adds a row to the table data. 
   * @param row to add or leave blank to add workingRow
   */
  function addRow($row = '') {
    $this->settings['rows'][] = ($row) ? $row : $this->workingRow;
    $this->curRowCount = (count($this->settings['rows']));
  }
  
  function addRowItem($value, $value_formatted = '', $format = '') {
    $this->workingRow[] = $this->formatRowItem($value, $value_formatted, $format);
  }
  
  function formatRowItem($value, $value_formatted = '', $format = '') {
    if (substr($format, 0, 1) == '+') {
      $value_formatted .= ($value >= 0) ? '+' : '';
      $format = substr($format, 1);
    }
    if ($format == '#,###') {
      $value_formatted .= number_format($value);
    }
    else if ($format == '#,###.##') {
      $value_formatted .= number_format($value, 2);
    }
    else if ($format == '#,###.#') {
      $value_formatted .= number_format($value, 1);
    }
    $item = array(
      'v' => $value,
    );
    if ($value_formatted) {
      $item['f'] = $value_formatted;
    }
    return $item;    
  }
  
  function renderOutput() {
    static $vis_loaded;

    if (!isset($vis_loaded)) {
      //ReportPageHeader::addScript("google.load('visualization', '1', {packages: ['table']});", 'inline');
      $vis_loaded = 1;
    }

    $datajson = json_encode($this->data);
    $optionsjson = json_encode($this->options);
    $settingsjson = json_encode($this->settings);
    $script = "_chart['table-{$this->chartIndex}'] = [$datajson, $optionsjson, $settingsjson];";
    
    //drupal_add_js($script, array('type' => 'inline', 'scope' => 'header'));
    $output = '<script>' . $script . '</script>';
    $output .= '<div id="table-' . $this->chartIndex . '" style="width: ' . $this->settings['div_width'] .'"></div>';  
    return $output;
  }
}