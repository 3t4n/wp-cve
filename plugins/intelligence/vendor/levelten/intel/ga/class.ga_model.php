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
if (!empty($_GET['debug'])) {
  require_once __DIR__ . '/../libs/class.debug.php';
}

class GAModel {
  public  $gaApiVer = 2;
  public  $data = array();
  public  $inputFilters = array();
  public  $gaFilters = array();
  public  $inputSubsite = '';
  public  $gaSubsite = '';
  public  $customFilters = array(
    'filter' => '',
    'segement' => '',
  );
  public  $context = '';
  public  $context_mode = '';
  private $start_date;
  private $end_date;
  private $indexByInfo;
  public  $settings;
  public  $pathQueryFilters = array();
  //private $pageAttributeFilter = array();
  private $attributeInfo = array(
    'page' => array(),
    'visitor' => array(),
  );
  private $requestCallback = '';
  private $requestCallbackOptions = array();
  private $feedRowsCallback = '';
  private $dataIndexCallbacks = array();
  private $debug = 0;
  // flag to enable
  private $advancedSort = 0;
  private $reportModes = array();
  private $requestDefault = array(
    'dimensions' => array(),
    'metrics' => array(),
    'sort' => '',
    'start_date' => 0,
    'end_date' => 0,
    'filters' => '',
    'segment' => '',
    'max_results' => 50,
  );
  private $requestSettings = array(
    'type' => 'pageviews',
    'subType' => 'value',
    'indexBy' => 'date',
    'subIndexBy' => '',
    'details' => 0,
    'sortType' => '',
    'excludeGoalFields' => 0,
  );
  // stores array of entities, e.g. page attributes, indexed by pagepaths
  public $pagePathMap = array();
  private $attrStorage = array(
    'vtk' => array(
      'struc' => 'dimension',
      'index' => 5,
      'field' => 'dimension5',
      'format' => 'single',
    ),
    'ts' => array(
      'struc' => 'dimension',
      'index' => 4,
      'field' => 'dimension4',
      'format' => 'single',
    ),
  );

  function __contruct() {

  }

  function setDateRange($start_date, $end_date) {
    $this->start_date = $start_date;
    $this->end_date = $end_date;
    $this->requestDefault['start_date'] = $start_date;
    $this->requestDefault['end_date'] = $end_date;
  }

  function setRequestSetting($param, $value) {
    $this->requestSettings[$param] = $value;
  }

  function setRequestDefaultParam($param, $value) {
    $this->requestDefault[$param] = $value;
  }

  function setRequestCallback ($callback, $options = array()) {
    $this->requestCallback = $callback;
    $this->requestCallbackOptions = $options;
  }

  function setDataIndexCallback($index, $callback) {
    $this->dataIndexCallbacks[$index] = $callback;
  }

  function setFeedRowsCallback($callback) {
    $this->feedRowsCallback = $callback;
  }

  function setReportModes($modes) {
    if (is_string($modes)) {
      $modes = explode('.', $modes);
    }
    $this->reportModes = $modes;
  }

  function setContext($value) {
    $this->context = $value;
  }

  function setContextMode($value) {
    $this->context_mode = $value;
  }

  function setAdvancedSort($value) {
    $this->advancedSort = $value;
  }

  function setDebug($debug) {
    $this->debug = $debug;
    if ($this->debug && !function_exists('Debug::printVar')) {
      require_once __DIR__ . '/../libs/class.debug.php';
    }
  }

  function buildFilters($filters, $subsite = '') {
    $this->inputFilters = $filters;
    $this->inputSubsite = $subsite;
    //$gasegments = array();
    $gafilters = array();
    $gapaths = array(
      'pagePath' => '',
      'landingPagePath' => '',
    );

    if (!empty($filters['page'])) {
      $gafilters['page'] = array();
      if (!is_array($filters['page'])) {
        $filters['page'] = array($filters['page']);
      }
      foreach ($filters['page'] AS $i => $filter) {
        $a = explode(":", $filter);
        $path = $a[1];

        $pathfilter = $this->formatPathFilter($path);
        //$landingpagefilter = str_replace('pagePath', 'landingPagePath', $pathfilter);

        $gafilters['page'][] = $pathfilter;

        /*

        if ($context == 'page') {
          $gapaths['pagePath'] = $pathfilter;
          $gapaths['landingPagePath'] = str_replace('pagePath', 'landingPagePath', $landingpagefilter);
        }
        else {
          if ($a[0] == 'landingPagePath') {
            $gafilters['page'][] = $landingpagefilter;
          }
          else {
            $gafilters['page'][] = $pathfilter;
          }
        }
        */
      }
    }

    if (!empty($filters['page-attr'])) {
      $gafilters['page-attr'] = array();
      if (!is_array($filters['page-attr'])) {
        $filters['page-attr'] = array($filters['page-attr']);
      }
      foreach ($filters['page-attr'] AS $i => $filter) {
        $gaField = 'dimension1';

        $a = explode(':', $filter);
        $attr_key = $a[0];
        $attr_value = isset($a[1]) ? $a[1] : '';
        // TODO add other attribute types
        $f = '';
        if (isset($this->attributeInfo['page'][$attr_key])) {
          if (isset($this->attributeInfo['page'][$attr_key]['storage']['analytics']['struc'])) {
            $gaField = $this->attributeInfo['page'][$attr_key]['storage']['analytics']['struc'] . $this->attributeInfo['page'][$attr_key]['storage']['analytics']['index'];
          }
          if ($this->attributeInfo['page'][$attr_key]['type'] == 'flag') {
            $f = 'ga:' . $gaField . '=@&' . $attr_key . '&';
          }
          else if (in_array($this->attributeInfo['page'][$attr_key]['type'], array('value', 'scalar', 'item'))) {
            // check if filter is a range in the format [floor]-[ceiling]
            $range = explode('-', $attr_value);
            if (count($range) == 2) {
              if (!empty($range[0])) {
                $f = $this->formatGtRegexFilter('ga:' . $gaField, (((int)$range[0])-1), $attr_key);
              }
              if (!empty($range[1])) {
                $f .= (($f) ? ';' : '') . $this->formatLtRegexFilter('ga:' . $gaField, (((int)$range[1])+1), $attr_key);
              }
            }
            else {
              $f = 'ga:' . $gaField . '=@&' . $attr_key . '=' . $attr_value . '&';
            }
          }
          else if (in_array($this->attributeInfo['page'][$attr_key]['type'], array('list'))) {
            $f = 'ga:' . $gaField . '=@&' . $attr_key . '.' . $attr_value . '&';
          }
          else if (in_array($this->attributeInfo['page'][$attr_key]['type'], array('vector'))) {
            $f = 'ga:' . $gaField . '=@&' . $attr_key . '.' . $attr_value . '=';
          }
        }
        if ($f) {
          $gafilters['page-attr'][] = $f;
          if ($filter == $subsite) {
            $this->gaSubsite = $f;
          }
        }

      }
    }

    if (!empty($filters['event'])) {
      $gafilters['event'] = array();
      foreach ($filters['event'] AS $i => $filter) {
        $a = explode(":", $filter);
        if ($a[0] == 'event') {
          $aa = explode('->', $a[1]);
          $i = 0;
          $ek = array(
            'eventCategory',
            'eventAction',
            'eventLabel',
          );
          foreach ($aa AS $n) {
            // for eventCategories filter ignoring last char of category
            //$n = rawurlencode($n);
            //$n = substr($n, 0, -15);
            $n = $this->encodeFilterText($n);
            //$n = htmlspecialchars($n);
            $gafilters['event'][] = ($i == 0) ? "ga:{$ek[$i]}=~^{$n}" : "ga:{$ek[$i]}=@{$n}";
            //$gafilters['event'][] = ($i == 0) ? "ga:{$ek[$i]}=~^{$n}" : "ga:{$ek[$i]}=@Disqus: This is fantastic\,"; //"ga:{$ek[$i]}=@{$n}";
            $i++;
          }
        }
        else {
          $op = isset($a[2]) ? $a[2] : '';
          if (!$op) {
            // if filter is eventCategory, use regex to only filter by string
            // beginning with category enabling !,+ as possible terminators
            $op = ($a[0] == 'eventCategory') ? '=~^' : '=@';
          }
          $gafilters['event'][] = "ga:{$a[0]}$op{$a[1]}";
        }
      }
    }

    if (!empty($filters['trafficsource'])) {
      $gafilters['trafficsource'] = array();
      foreach ($filters['trafficsource'] AS $i => $filter) {
        $a = explode(":", $filter);
        if ($a[0] == 'trafficcategory') {
          if ($a[1] == 'organic search') {
            $gafilters['trafficsource'][] = "ga:medium==organic";
          }
          elseif ($a[1] == 'cpc' || $a[1] == 'ppc') {
            $gafilters['trafficsource'][] = "ga:medium=~(cpc|ppc)";
          }
          elseif ($a[1] == 'social network') {
            //$gafilters['trafficsource'][] = "ga:hasSocialSourceReferral==Yes"; // this is incompataible with entrance request
            $gafilters['trafficsource'][] = "ga:socialNetwork!=(not set)";
          }
          elseif ($a[1] == 'direct') {
            $gafilters['trafficsource'][] = "ga:medium==(none)";
          }
          elseif ($a[1] == 'email') {
            $gafilters['trafficsource'][] = "ga:medium==email";
          }
          elseif ($a[1] == 'referral') {
            $gafilters['trafficsource'][] = "ga:medium==referral";
          }
          elseif ($a[1] == 'feed') {
            $gafilters['trafficsource'][] = "ga:medium==feed";
          }
          elseif ($a[1] == 'other') {
            $gafilters['trafficsource'][] = "ga:medium!~(feed|email|referral|organic|(none));ga:socialNetwork==(not set)";
          }
          else {
            $gafilters['trafficsource'][] = "ga:medium=={$a[1]}";
          }
        }
        elseif ($a[0] == 'searchKeyword') {
          $gafilters['trafficsource'][] = "ga:medium==organic;ga:keyword=={$a[1]}";
        }
        elseif ($a[0] == 'searchEngine') {
          //$gafilters['trafficsource'][] = "ga:source=={$a[1]};ga:medium==organic";
          $gafilters['trafficsource'][] = "ga:sourceMedium=={$a[1]} / organic";
        }
        elseif ($a[0] == 'referralHostpath') {
          $b = explode('/', $a[1], 2);
          $gafilters['trafficsource'][] = "ga:source=={$b[0]};ga:referralPath==/{$b[1]}";
        }
        elseif ($a[0] == 'referralHostname') {
          $b = explode('/', $a[1], 2);
          $gafilters['trafficsource'][] = "ga:source=={$b[0]}";
        }
        else {
          $gafilters['trafficsource'][] = "ga:{$a[0]}=={$a[1]}";
        }
      }
    }

    if (!empty($filters['location'])) {
      $gafilter['location'] = array();
      foreach ($filters['location'] AS $i => $filter) {
        $a = explode(":", $filter);
        $gafilters['location'][] = "ga:{$a[0]}=={$a[1]}";
        if (($a[0] == 'country') || ($a[0] == 'region') || ($a[0] == 'city') || ($a[0] == 'metro')) {
          $this->settings['location_dimension_level'] = 'region';
        }
      }
    }

    if (!empty($filters['visitor'])) {
      $gafilter['visitor'] = array();
      foreach ($filters['visitor'] AS $i => $filter) {
        $a = explode(":", $filter);
        if ($a[0] == 'vtk') {
          $gafilters['visitor'][] = "ga:{$this->attrStorage['vtk']['field']}==" . $a[1];
        }
        else {
          $gafilters['visitor'][] = "ga:{$a[0]}==" . $a[1];
        }
      }
    }

    // TODO
    if (!empty($filters['visitor-attr'])) {
      //$a = str_replace(':', '=', $filters['visitor-attr']);
      //$query = l10insight_build_ga_filter_from_serialized_customvar($a);
      //$ga_segments['visitor-attr'] = "dynamic::ga:dimension3=" . $query;
    }

    if (!empty($filters['datetime'])) {
      $gafilters['datetime'] = array();
      foreach ($filters['datetime'] AS $i => $filter) {
        $a = explode(":", $filter);
        if ($a[0] == 'dateHourMinute') {
          $f = '';
          $ts = explode('-', $a[1]);
          $f .= $this->formatGtRegexFilter('ga:dateHourMinute', substr($ts[0], 2), '', array('fixed_width' => 1, 'prefix' => '20'));
          $f .= ';';
          $f .= $this->formatLtRegexFilter('ga:dateHourMinute', substr($ts[1], 2), '', array('fixed_width' => 1, 'prefix' => '20'));
          $gafilters['datetime'][] = $f;
        }
      }
    }

    $this->gaFilters = array(
      'filters' => $gafilters,
      //'segments' => $gasegments,
      //'filter' => implode(";", $gafilters),
      //'segment' => implode(";", $gasegments),
      'paths' => $gapaths,
    );
  }
  /**
   *
   * @param $item
   * @param $type: valid values = 'filter' or 'segement'
   */
  function addGAFilter($item, $type = 'filter') {
    if ($type == 'segment') {
      $this->gaFilters['segements'][] = $item;
      //$this->gaFilters['segement'] =  implode(";", $this->gaFilters['segements']);
    }
    else if ($type == 'filter') {
      $this->gaFilters['filters'][] = $item;
      //$this->gaFilters['filter'] =  implode(";", $this->gaFilters['filters']);      
    }
  }

  /*
  function setPageAttributeFilter($filter) {
    $this->pageAttributeFilter = $filter;
  }
  */
  function addAttributeInfo($info, $type = 'page') {
    $this->attributeInfo[$type][$info['key']] = $info;
  }


  function setAttributeInfoAll($infos) {
    $this->attributeInfo = $infos;
  }


  function applyFiltersToRequest($request) {
    if (!empty($this->gaFilters['filter'])) {
      $request['filters'] .= (($request['filters']) ? ';' : '') . $this->gaFilters['filter'];
    }
    if (!empty($this->gaFilters['segment'])) {
      $request['segment'] .= (($request['segment']) ? ';' : '') . $this->gaFilters['segment'];
    }
    return $request;
  }

  function setCustomFilter($string, $type = 'filter') {
    $this->customFilters[$type] = $string;
  }

  function getFeedRows($feed) {
    // check if GA Reporting API ver 2
    if (is_array($feed->results)) {
      return $feed->results;
    }
    // GA API ver 3
    else {
      return $feed->results->rows;
    }
  }

  function getFeedTotals($feed) {
    // check if GA Reporting API ver 2
    if (is_array($feed->totals)) {
      return $feed->totals;
    }
    // GA API ver 3
    else {
      return $feed->results->totalsForAllResults;
    }
  }

  //function loadFeedData($type, $indexBy = '', $details = 0, $max_results = 100, $results_index = 0) {
  function loadFeedData($type = '', $indexBy = '', $details = 0) {
    $settings = $this->requestSettings;
    $indexBy = ($indexBy) ?  $indexBy : $settings['indexBy'];

    // format the ga request array
    $request = $this->getRequestArray($type, $indexBy, $details);
    if ($this->debug) { Debug::printVar($request); }

    // advanced sort used on requests with 2 sorts to split the requests into
    // two
    $sorts = array();
    $threshold = 0;
    $advancedSort = $this->advancedSort;
    if ($advancedSort) {
      $sorts = explode(',', $request['sort']);
      if (count($sorts) == 2) {
        // if two sorts, implement advancedSort using half the max results on each request
        $request['max_results'] = floor($request['max_results']/2);
      }
      else {
        $advancedSort = 0;
      }
    }
    // call dyanmic function to fetch data from ga
    if ($this->requestCallback) {
      $func = $this->requestCallback;
      $feed = $func($request, $this->requestCallbackOptions);
    }
    if ($this->debug) {
      Debug::printVar($feed);
      if ($this->feedRowsCallback) {
        Debug::printVar(call_user_func($this->feedRowsCallback, $feed));
      }
    }

    /*
    if ($type == 'entrances' && $this->context_mode == 'subsite' && $indexBy == 'trafficsources') {
      $request = $this->getRequestArray('entrances', 'trafficsources_intersite', $details);
      if ($this->debug) { Debug::printVar($request); }
      if ($this->requestCallback) {
        $func = $this->requestCallback;
        $feed2 = $func($request, $this->requestCallbackOptions);
      }
      if ($this->debug) { Debug::printVar($feed2); }
    }
    */


    // set $feedRows to point to rows array in response based on GA API version
    // we need to alter the data in the feed and thus need the data by reference
    $feedRows = array();
    if (isset($feed->results)) {
      if (is_array($feed->results)) {  // v2
        $feedRows = &$feed->results;
        $feedTotals = &$feed->totals;
      }
      else if (isset($feed->results->rows)) { // v3
        $feedRows = &$feed->results->rows;
        $feedTotals = &$feed->results->totalsForAllResults;
      }
    }

    // if max_results not returned, disable advancedSort
    if (is_array($feedRows) && (count($feedRows) < $request['max_results'])) {
      $advancedSort = 0;
    }

    // if advanced search, determine the threshold for next request by looking
    // at the bottom values for the primary sort field
    if ($advancedSort && is_array($feedRows)) {
      // remove - sign if it exists on sort field
      $field = str_replace('-', '', $sorts[0]);
      $field = str_replace('ga:', '', $field);
      $last_i = count($feedRows)-1;
      $threshold = $feedRows[$last_i][$field];

      for ($i = $last_i - 1; $i; $i--) {
        if ($feedRows[$i][$field] != $threshold) {
          $last_i = $i;
          break;
        }
      }
      // unset records at the threshold
      for ($i = count($feedRows)-1; $i > $last_i; $i--) {
        // decrement totals for removed records
        foreach ($feedTotals AS $key => $value) {
          $feedTotals[$key] -= $feedRows[$i][$key];
        }
        unset($feedRows[$i]);
      }
      if ($this->debug) { Debug::printVar("field=$field, thresh=$threshold, last_i=$last_i"); }
      if ($this->debug) { Debug::printVar($feed); }
    }

    // parse data into $this->data
    $this->addFeedData($feed, $type, $indexBy, $details);
    if ($this->debug) {
      $data = ($type == 'entrances_pagepathmap') ? $this->pagePathMap : $this->data;
      Debug::printVar($data);
    }

    // if advanced search, run second request fliping the sort and using the
    // threshold to
    if ($advancedSort && is_array($feedRows)) {
      $request['sort'] = $sorts[1] . ',' . $sorts[0];
      $request['filters'] .= ($request['filters'] ? ';' : '') . 'ga:' . $field . '<=' . $threshold;
      // expand result to cover unset records from prior query
      $request['max_results'] += ($request['max_results'] - $last_i);
      if ($this->debug) { Debug::printVar($request); }

      // call dyanmic function to fetch data from ga
      if ($this->requestCallback) {
        $func = $this->requestCallback;
        $feed = $func($request, $this->requestCallbackOptions);
      }
      if ($this->debug) { Debug::printVar($feed); }

      // parse data into $this->data
      $this->addFeedData($feed, $type, $indexBy, $details);

      if ($this->debug) {
        $data = ($type == 'entrances_pagepathmap') ? $this->pagePathMap : $this->data;
        Debug::printVar($data);
      }
    }

    return $this->data;
  }

  //function getRequestArray($type, $sub_type = 'value_desc', $indexBy = '', $details = 0, $max_results = 100, $results_index = 0) {
  /**
   * Builds query array for Google Analytics API request.
   *
   * @param string $type
   *   pageviews: general metrics for page hits
   *   entrances: associates all general pageview metrics during sessions with
   *     first page hit of the session (entrance page)
   *   pageviews_valuedevents: valued event metrics associated with the prior pageview
   *   entrances_valuedevents: valued event metrics associated with entrance pages
   *   pageviews_goals: goal values & completions associated with the prior pageview
   *   entrances_goals: goal values & completions associated with entrance pages
   * @param string $indexBy
   * @param int $details
   * @return array
   */
  function getRequestArray($type = '', $indexBy = '', $details = NULL) {
    $request = $this->requestDefault;
    $settings = $this->requestSettings;
    $context = $this->context;
    $context_mode = $this->context_mode;
    $type = ($type) ? $type : $settings['type'];
    $types = explode('_', $type);
    $subType = $settings['subType'];
    $indexBy = ($indexBy) ?  $indexBy : $settings['indexBy'];
    $subIndexBy = $settings['subIndexBy'];
    $details = isset($details) ? $details : $settings['details'];
    $reportModes = $this->reportModes;
    $filters = array();
    $segments = array();
    $gaFilters = $this->gaFilters['filters'];
    $inputFilters = $this->inputFilters;
    //$segments = $this->gaFilters['segments'];

    if ($this->debug) { Debug::printVar("getRequestArray: $type, $indexBy, $subIndexBy, $details"); Debug::printVar(array('details' => $details, 'reportModes' => $reportModes, 'gaFilters' => $this->gaFilters, 'gaSubsite' => $this->gaSubsite, 'context' => $context, 'context_mode' => $context_mode)); }

    // get attr key if report is indexed by pageAttribute
    if (strpos($indexBy, 'pageAttribute:') === 0) {
      $indexBys = explode(':', $indexBy);
      $attrKey = $this->indexByInfo['attrKey'] = $indexBys[1];
      $attrType = $this->indexByInfo['attrType'] = isset($this->attributeInfo['page'][$attrKey]['type']) ? $this->attributeInfo['page'][$attrKey]['type'] : 'value';
      $attrField = $this->indexByInfo['attrField'] = 'dimension1';
      $attrFieldFormat = $this->indexByInfo['attrFieldType'] = '';
      $attrFieldStruc = $this->indexByInfo['attrFieldStruc'] = 'dimension';
      if (isset($this->attributeInfo['page'][$attrKey]['storage']['analytics']['struc'])) {
        $attrField = $this->indexByInfo['attrField'] = $this->attributeInfo['page'][$attrKey]['storage']['analytics']['struc'] . $this->attributeInfo['page'][$attrKey]['storage']['analytics']['index'];
        $attrFieldStruc = $this->indexByInfo['attrFieldStruc'] = $this->attributeInfo['page'][$attrKey]['storage']['analytics']['struc'];
      }
      if (isset($this->attributeInfo['page'][$attrKey]['storage']['analytics']['format'])) {
        $attrFieldFormat = $this->indexByInfo['attrFieldType'] = $this->attributeInfo['page'][$attrKey]['storage']['analytics']['format'];
      }
    }

    // Phase I: Set metrics based on request $type
    // later, phase II: sets dimentions based on $indexBy
    if ($type == 'pageviews') {
      // event filter will disable pageview values
      if (isset($gaFilters['event'])) {
        // if pagePath is used as a dimension, pageviews values return 0
        if ($indexBy == 'content') {
          $request['metrics'] = array('ga:totalEvents');
          $request['sort'] = '-ga:totalEvents';
        }
        else {
          // pageValue incompatable with event filters
          $request['metrics'] = array('ga:pageviews', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll');
          $request['sort'] = '-ga:pageviews';
        }
      }
      elseif (isset($reportModes[0]) && (($reportModes[0] == 'social') || ($reportModes[0] == 'seo'))) {
        $request['metrics'] = array('ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:exits');
        $request['sort'] = '-ga:pageviews';
      }
      else {
        $request['metrics'] = array('ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:exits', 'ga:pageValue', 'ga:goalValueAll', 'ga:goalCompletionsAll');
        $request['sort'] = '-ga:pageValue,-ga:pageviews';
      }
    }
    else if ($type == 'entrances') {
      if (isset($reportModes[0]) && (($reportModes[0] == 'social') || ($reportModes[0] == 'seo'))) {
        $request['metrics'] = array('ga:entrances', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll');
        // v3
        //$request['metrics'] = array('ga:entrances', 'ga:newVisits', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnSite', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll');
        $request['sort'] = '-ga:entrances';
      }
      else {
        $request['metrics'] = array('ga:entrances', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll');
        // v3
        //$request['metrics'] = array('ga:entrances', 'ga:newVisits', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnSite', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll');
        $request['sort'] = '-ga:goalValueAll,-ga:entrances';
      }
    }
    else if ($type == 'sessions') {
      $request['metrics'] = array('ga:sessions', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:pageValue');
      // v3 $request['metrics'] = array('ga:sessions', 'ga:newVisits', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnSite', 'ga:pageValue');
      $request['sort'] = '-ga:pageviews';
    }
    else if ($type == 'visitors') {
      $request['metrics'] = array('ga:newUsers');
    }
    else if ($types[1] == 'events') {
      $request['metrics'] = array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue');
      if (isset($reportModes[0]) && ($reportModes[0] == 'landingpage')) {
        $filters[] = "ga:eventCategory=~^Landing page";
        $request['sort'] = '-ga:totalEvents';
      }
      else if (isset($reportModes[0]) && ($reportModes[0] == 'social')) {
        $filters[] = "ga:eventCategory=~^Social";
        $request['sort'] = '-ga:totalEvents';
      }
      else if (isset($types[2]) && $types[2] == 'valued') {
        $request['metrics'][] = 'ga:metric2';
        $filters[] = "ga:eventCategory=~!$";
        $request['sort'] = '-ga:metric2,-ga:totalEvents';
        // ca $request['sort'] = '-ga:eventValue,-ga:totalEvents';
      }
      else if (isset($types[2]) && $types[2] == 'goal') {
        $request['metrics'][] = 'ga:metric3';
        $filters[] = "ga:eventCategory=~\+$";
        $request['sort'] = '-ga:metric3,-ga:totalEvents';
        // ca $request['sort'] = '-ga:eventValue,-ga:totalEvents';
      }
      else if (isset($types[2]) && $types[2] == 'nonvalued') {
        $filters[] = "ga:eventCategory!~(!|\+)$";
        $request['sort'] = '-ga:totalEvents';
      }

    }
    /*
    else if ($type == 'entrances_valuedevents') {
      $request['metrics'] = array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue');
      $filters[] = "ga:eventCategory=~^*!$";
      $request['sort'] = '-ga:eventValue,-ga:totalEvents';
    }
    */
    else if ($type == 'pageviews_goals_assist') {
      $request['metrics'] = array('ga:goalValueAll', 'ga:goalCompletionsAll');
      $request['dimensions'] = array('ga:goalCompletionLocation', 'ga:goalPreviousStep1', 'ga:goalPreviousStep2', 'ga:goalPreviousStep3');
    }
    else if ($types[1] == 'goals') {
      if (!empty($details) && is_array($details)) {
        foreach ($details AS $id) {
          $request['metrics'][] = "ga:goal{$id}Completions";
          $request['metrics'][] = "ga:goal{$id}Value";
        }
      }
      else {
        $request['metrics'][] = "ga:goalCompletionsAll";
        $request['metrics'][] = "ga:goalValueAll";
      }
    }
    /*
    else if ($type == 'pageviews_goals') {
      if (!empty($details) && is_array($details)) {
        foreach ($details AS $id) {
          $request['metrics'][] = "ga:goal{$id}Completions";
          $request['metrics'][] = "ga:goal{$id}Value";
        }
      }
      else {
        $request['metrics'][] = "ga:goalCompletionsAll";
        $request['metrics'][] = "ga:goalValueAll";
      }
    }
    else if ($type == 'entrances_goals') {
      if (!empty($details) && is_array($details)) {
        foreach ($details AS $id) {
          $request['metrics'][] = "ga:goal{$id}Completions";
          $request['metrics'][] = "ga:goal{$id}Value";
        }
      }
    }
    */
    else if ($type == 'eventsource_events') {
      $request['dimensions'][] = 'ga:eventCategory';
      $request['dimensions'][] = 'ga:eventAction';
      $request['dimensions'][] = 'ga:eventLabel';
      //$request['dimensions'][] = 'ga:pagePath';
      $request['metrics'] = array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue');
      $request['sort'] = '-ga:totalEvents';
    }
    else if ($type == 'visit_info') {
      $request['dimensions'][] = 'ga:country';
      $request['dimensions'][] = 'ga:medium';
      $request['dimensions'][] = 'ga:socialNetwork';
      $request['metrics'] = array('ga:entrances', 'ga:pageviews', 'ga:goalValueAll');
      $request['sort'] = '-ga:goalValueAll,-ga:entrances,-ga:pageviews';
      if ($this->gaFilters['paths']['pagePath']) {
        $segments[] = $this->gaFilters['paths']['pagePath'];
      }
    }
    else if ($type == 'entrances_pagepathmap') {
      $request['metrics'] = array('ga:entrances', 'ga:pageviews', 'ga:pageValue');
      if ($attrFieldStruc == 'metric') {
        $request['metrics'][] = 'ga:' . $attrField;
      }
      else {
        $request['dimensions'][] = 'ga:' . $attrField;
      }
      //$request['dimensions'][] = 'ga:customVarValue1';
      $request['dimensions'][] = 'ga:pagePath';

      $request['sort'] = '-ga:pageValue,-ga:entrances';
      $filters[] = 'ga:entrances>0';
    }

    // Phase II. Set elements based on indexBy

    // if indexing on content, add hostname and landingPagePath dimentions
    if ($indexBy == 'date') {
      $request['dimensions'][] = 'ga:date';
      if (isset($filters['page-attr'])) {
        if ($type == 'entrances') {
          $request['dimensions'][] = 'ga:landingPagePath';
        }
      }
      // TODO do real days calculation
      //$request['max_results'] = 30 * $items;  
    }
    else if ($indexBy == 'content' || $indexBy == 'pagePath') {
      if ($type == 'entrances' || $type == 'entrances_events_valued') {
        $request['dimensions'][] = 'ga:landingPagePath';
      }
      else {
        $request['dimensions'][] = 'ga:pagePath';
      }
    }
    else if ($indexBy == 'visitor') {
      $request['dimensions'][] = 'ga:' . $this->attrStorage['vtk']['field'];
      // for entrance requests sorting by pageviews is somewhat more effective
      // for visitors
      if ($types[0] == 'entrances' && (empty($types[1]) || $types[1] != 'events')) {
        $request['sort'] = '-ga:goalValueAll,-ga:pageviews,-ga:entrances';
      }
    }
    else if ($indexBy == 'visit') {
      $request['dimensions'][] = 'ga:' . $this->attrStorage['vtk']['field'];
      $request['dimensions'][] = 'ga:sessionCount';
      if ($reportModes[1] == 'recent') {
        $request['dimensions'][] = 'ga:' . $this->attrStorage['ts']['field'];
        $request['sort'] = '-ga:' . $this->attrStorage['ts']['field'];
        if ($type == 'entrances') {
          $filters[] = 'ga:entrances>0';
        }
      }
      //$request['segment'] = (!empty($request['segment']) ? ';' . $this->gaFilters['paths']['pagePath'] : ''); 
    }
    else if ($indexBy == 'country') {
      $request['dimensions'][] = 'ga:country';
      if ($types[0] == 'entrances' && (empty($types[1]) || $types[1] != 'events')) {
        $request['sort'] = '-ga:goalValueAll,-ga:pageviews,-ga:entrances';
      }
    }
    else if ($indexBy == 'trafficsources') {
      $request['dimensions'][] = 'ga:medium';
      $request['dimensions'][] = 'ga:source';
      $request['dimensions'][] = 'ga:referralPath';
      $request['dimensions'][] = 'ga:keyword';
      $request['dimensions'][] = 'ga:socialNetwork';
      $request['dimensions'][] = 'ga:campaign';
    }
    else if ($indexBy == 'trafficcategory') {
      $request['dimensions'][] = 'ga:medium';
      $request['dimensions'][] = 'ga:socialNetwork';
    }
    else if ($indexBy == 'searchKeyword') {
      $request['dimensions'][] = 'ga:medium';
      $request['dimensions'][] = 'ga:keyword';
      $filters[] = 'ga:medium==organic';
    }
    else if ($indexBy == 'searchEngine') {
      $request['dimensions'][] = 'ga:medium';
      $request['dimensions'][] = 'ga:source';
      $filters[] = 'ga:medium==organic';
    }
    else if ($indexBy == 'referralHostname') {
      $request['dimensions'][] = 'ga:source';
      $request['dimensions'][] = 'ga:referralPath';
      $filters[] = 'ga:referralPath=@/';
    }
    else if ($indexBy == 'referralHostpath') {
      $request['dimensions'][] = 'ga:source';
      $request['dimensions'][] = 'ga:referralPath';
    }
    else if ($indexBy == 'trafficsources_intersite') {
      $request['dimensions'][] = 'ga:hostname';
      $request['dimensions'][] = 'ga:pagePath';
      $filters[] = 'ga:entrances>0';
    }
    else if (strpos($indexBy, 'pageAttribute:') === 0) {
      $filt = '';
      if (isset($this->attributeInfo['page'][$attrKey])) {
        $attrFieldMetric = 0;
        if ($attrFieldStruc == 'metric') {
          $filt = 'ga:' . $attrField . '>=0';
        }
        else if (($attrFieldFormat == 'single' || $attrFieldFormat == 'single_list')) {
          $filt = 'ga:' . $attrField . '=~.*';
        }
        else if (($attrType == 'flag')) {
          $filt = 'ga:' . $attrField . '=@&';
        }
        else if (in_array($attrType, array('value', 'scalar', 'item'))) {
          $filt = 'ga:' . $attrField . '=@&' . $attrKey . '=';
        }
        else if (in_array($attrType, array('list', 'vector'))) {
          $filt = 'ga:' . $attrField . '=@&' . $attrKey . '.';
        }
        // don't add customVarValue dimension to entrance oriented requests.
        // the customVarValue for every session hit will be included
        $dimOrMet = ($attrFieldStruc == 'metric') ? 'metrics' : 'dimensions';
        if ($types[0] == 'entrances') {
          if ($type != 'entrances_pagepathmap') {
            $request['dimensions'][] = 'ga:landingPagePath';
          }
          // helps to reduce records by filtering only records that enter in page-attr
          $segments[] = 'sessions::sequence::^' . $filt;
        }
        else if ($types[0] == 'pageviews') {
          $request[$dimOrMet][] = 'ga:' . $attrField;
          if ($attrFieldStruc == 'metric') {
            $request['dimensions'][] = 'ga:pagePath';
          }
          // reduce records by filtering only pageviews in page-attr
          $filters[] = $filt;
        }
        else if ($types[0] == 'sessions') {
          $request['dimensions'][] = 'ga:' . $attrField;
          if(isset($types[1])  && $types[1] == 'events') {
            if (isset($types[2]) && $types[2] == 'valued') {
              $segments[] = 'sessions::sequence::' . $filt . ';->>ga:eventCategory=~\!$';
            }
          }
          elseif(isset($types[1])  && $types[1] == 'goals') {
            $segments[] = 'sessions::sequence::' . $filt . ';->>ga:eventCategory=~\+$';
          }
          else {
            $segments[] = 'sessions::sequence::' . $filt;
          }
        }
      }
    }
    else if ($indexBy == 'geo') {
      $request['dimensions'][] = 'ga:country';
    } else if (!empty($indexBy)) {
      $request['dimensions'][] = 'ga:' . $indexBy;
    }

    if ($subIndexBy) {
      if ($subIndexBy == 'trafficcategory') {
        $request['dimensions'][] = 'ga:medium';
        $request['dimensions'][] = 'ga:socialNetwork';
      }
      else if ($subIndexBy == 'socialNetwork') {
        $filters[] = 'ga:socialNetwork!=(not set)';
        $request['sort'] = '-ga:goalValueAll,-ga:entrances';
        $request['dimensions'][] = 'ga:socialNetwork';
      }
      else if ($subIndexBy == 'organicSearch') {
        $request['dimensions'][] = 'ga:medium';
        $request['metrics'][] = 'ga:organicSearches';
        $filters[] = 'ga:medium==organic';
        $request['sort'] = '-ga:organicSearches';
      }
      else if ($subIndexBy == 'searchKeyword') {
        $request['dimensions'][] = 'ga:medium';
        $request['dimensions'][] = 'ga:keyword';
        $request['metrics'][] = 'ga:organicSearches';
        $filters[] = 'ga:medium==organic';
        $filters[] = 'ga:keyword!@(not ';
        $request['sort'] = '-ga:organicSearches';
      }
      else {
        $request['dimensions'][] = 'ga:' . $subIndexBy;
      }

    }

    if (!empty($details)) {
      if ($type == 'pageviews' && (!in_array('ga:pagePath', $request['dimensions']))) {
        $request['dimensions'][] = 'ga:pagePath';
      }
      if (isset($types[1]) && ($types[1] == 'events')) {
        if (is_string($details)) {
          if (strpos($details, 'eventCategory:') !== FALSE) {
            $request['dimensions'][] = 'ga:eventAction';
          }
          elseif (strpos($details, 'event:') !== FALSE) {
            $a = explode('->', $details);
            if (count($a) > 1) {

            }
            $request['dimensions'][] = 'ga:eventAction';
          }
        }
        $request['dimensions'][] = 'ga:eventCategory';
      }
      if (($type == 'entrances_goals') || ($type == 'pageviews_goals')) {

      }
    }

    // Phase III apply filters

    // if page path filter
    if (($types[0] == 'entrances') && ($this->gaFilters['paths']['landingPagePath'])) {
      $filters[] = $this->gaFilters['paths']['landingPagePath'];
    }
    if (($types[0] == 'pageviews') && ($this->gaFilters['paths']['pagePath'])) {
      $filters[] = $this->gaFilters['paths']['pagePath'];
    }

    $segment_scope = ($indexBy == 'visitor') ? 'users' : 'sessions';
//intel_d($type);
//intel_d($types);
//intel_d($gaFilters);
    foreach ($gaFilters AS $filter_type => $gafarr) {
      foreach ($gafarr AS $i => $filt) {
        if ($filter_type == 'datetime') {
          $filters[] = $filt;
        }
        else {
          if ($types[0] == 'entrances') {
            // traffic sources cannot be used in segments, make exception
            if ($filter_type == 'trafficsource') {
              $filters[] = $filt;
            }
            elseif ($filter_type == 'page') {
              // change default of pagePath to landingPagePath
              $filters[] = str_replace('pagePath', 'landingPagePath', $filt);
            }
            else {
              if ($indexBy == 'trafficsources_intersite') {
                $notfilt = str_replace('=@', '!@', $filt);
                $notfilt = str_replace('==', '!=', $notfilt);
                $notfilt = str_replace('=~', '!~', $notfilt);
                $segments[] = 'sessions::sequence::^' . $notfilt . ';->>' . $filt;
              }
              else {
                // if filter is page_group, don't apply as segment
                if ($filter_type == 'page-attr' && ($filt == $this->gaSubsite)) {
                  $filters[] = $filt;
                }
                else {

                  if ($filter_type == 'event') {
                    $segments[] = $segment_scope . '::sequence::' . $filt;
                  }
                  else {
                    $segments[] = 'sessions::sequence::^' . $filt;
                  }

                }

                /*
                if ($context_mode == 'subsite') {
                  $filters[] = $filt;
                }
                */
              }
            }
          }
          elseif ($types[0] == 'sessions') {
            if ($filter_type == 'page-attr' && ($filt == $this->gaSubsite)) {
              if ($type != 'sessions') {
                $filters[] = $filt;
              }
            }
            else {
              if ($type == 'sessions') {
                $segments[] = 'sessions::sequence::' . $filt;
              }
              elseif($types[1] == 'events') {
                if (isset($types[2]) && $types[2] == 'valued') {
                  $segments[] = 'sessions::sequence::' . $filt . ';->>ga:eventCategory=~\!$';
                }
              }
              elseif($types[1] == 'goals') {
                $segments[] = 'sessions::sequence::' . $filt . ';->>ga:eventCategory=~\+$';
              }
            }
          }
          // pageview request type
          else {
            if ($filter_type == 'event') {
              if ($indexBy == 'trafficsource' || $indexBy == 'visitor' || $indexBy == 'visit') {
                $segments[] = $segment_scope . '::condition::' . $filt;
              }
              else {
                $filters[] = $filt;
              }
            }
            else {
              $filters[] = $filt;
            }
          }
        }
      }
    }

    // join filters into request
    if (!empty($this->customFilters['filter'])) {
      $request['filters'] = $this->customFilters['filter'];
    }
    if (count($filters)) {
      $request['filters'] = (!empty($request['filters']) ? ';' : '') . implode(';', $filters);
    }
    if (!empty($this->customFilters['segment'])) {
      $request['segment'] = $this->customFilters['segment'];
    }
    if (count($segments)) {
      $request['segment'] = (!empty($request['segment']) ? ';' : '') . implode(';', $segments);
    }
    return $request;
  }

  function addFeedData($feed, $type = '', $indexBy = '', $details = 0) {
    if (empty($feed->results)) {
      return;
    }

    if (is_array($feed->results)) {  // v2
      $feedRows = &$feed->results;
      $feedTotals = &$feed->totals;
    }
    else { // v3
      $feedRows = &$feed->results->rows;
      $feedTotals = &$feed->results->totalsForAllResults;
    }
    if (!is_array($feedRows)) {
      $feedRows = array();
    }


    $settings = $this->requestSettings;

    $type = ($type) ? $type : $settings['type'];
    $types = explode('_', $type);
    $subType = $settings['subType'];
    $indexBy = ($indexBy) ?  $indexBy : $settings['indexBy'];
    $subIndexBy = !empty($settings['subIndexBy']) ? $settings['subIndexBy'] : '';
    $details = ($details) ? $details : $settings['details'];

    // pagepathmap is a special case
    if ($type == 'entrances_pagepathmap') {
      $this->pagePathMap = $this->addPagePathMapFeedData($this->pagePathMap, $feedRows, $feedTotals, $indexBy);
      return;
    }

    if (!isset($this->data[$indexBy])) {
      $this->data[$indexBy] = $this->initIndexDataStruc();
    }
    $prime_index = $indexBy;
    if ($prime_index == 'trafficsources') {
      $sub_indexes = $this->getTrafficsourcesSubIndexes();
    }
    else {
      $sub_indexes = array('-');
    }

    if (!$prime_index) {
      $prime_index = 'site';
    }

    foreach ($sub_indexes AS $sub_index) {
      if ($sub_index != '-') {
        $d = isset($this->data[$prime_index][$sub_index]) ? $this->data[$prime_index][$sub_index] : array();
        $curIndex = $sub_index;
        if (!isset($this->data[$prime_index][$sub_index])) {
          $this->data[$prime_index][$sub_index] = $this->initIndexDataStruc();
        }
      }
      else {
        if (!isset($this->data[$prime_index])) {
          continue;
        }
        $d = $this->data[$prime_index];
        $curIndex = $prime_index;
      }

      switch ($type) {
        case 'pageviews':
          $d = $this->addPageviewFeedData($d, $feedRows, $feedTotals, $curIndex, $subIndexBy);
          break;
        case 'entrances':
          $d = $this->addEntranceFeedData($d, $feedRows, $feedTotals, $curIndex, $subIndexBy);
          break;
        case 'sessions':
          $d = $this->addSessionFeedData($d, $feedRows, $feedTotals, $curIndex, $subIndexBy);
          break;
        case 'visitors':
          $d = $this->addVisitorFeedData($d, $feedRows, $feedTotals, $curIndex, $subIndexBy);
          break;
        /*
        //case 'pageviews_events':
        //case 'pageviews_events_valued':
        //case 'pageviews_events_nonvalued':
          //$d = $this->addEventsFeedData($d, $feed, $curIndex, 'pageview');
          //break;
        case 'entrances_valuedevents':
          $d = $this->addValuedeventsFeedData($d, $feed, $curIndex, 'entrance');
          break;
        case 'sessions_valuedevents':
          $d = $this->addValuedeventsFeedData($d, $feed, $curIndex, 'session');
          break;
        */
        case 'pageviews_goals':
          $d = $this->addGoalsFeedData($d, $feedRows, $feedTotals, $curIndex, 'pageview', $details);
          break;
        case 'entrances_goals':
          $d = $this->addGoalsFeedData($d, $feedRows, $feedTotals, $curIndex, 'entrance', $details);
          break;
        case 'sessions_goals':
          $d = $this->addGoalsFeedData($d, $feedRows, $feedTotals, $curIndex, 'session', $details);
          break;
        case 'pageviews_goals_assist':
          $d = $this->addGoalsAssistFeedData($d, $feedRows, $feedTotals, $curIndex, 'pageview', $details);
          break;
        case 'eventsource_events':
          $d = $this->addEventsourceEventFeedData($d, $feedRows, $feedTotals, $curIndex, 'pageview');
          break;
        case 'visit_info':
          $d = $this->addVisitInfoFeedData($d, $feedRows, $feedTotals, $curIndex, 'entrance', $details);
          break;
        case 'pagepathmap_entrances':
          $d = $this->addPagePathMapFeedData($d, $feedRows, $feedTotals, $curIndex, 'entrance');
          break;
      }
      if (isset($types[1]) && ($types[1] == 'events')) {
        $type = isset($types[2]) ? $types[2] : '';
        $mode = substr($types[0], 0, -1);
        $d = $this->addEventsFeedData($d, $feedRows, $feedTotals, $curIndex, $mode, $type, $details);
      }
      if ($sub_index != '-') {
        $this->data[$prime_index][$sub_index] = $d;
      }
      else {
        $this->data[$prime_index] = $d;
      }
    }
  }

  function addPageviewFeedData($d, $rows, $totals, $indexBy, $subIndexBy) {
    if (!isset($d['_all']['pageview'])) {
      $d['_all']['pageview'] = $this->initPageviewDataStruc();
      $d['_totals']['pageview'] = $this->initPageviewDataStruc();
    }
    $this->_addPageviewFeedDataRow($d['_totals']['pageview'], $totals);
    foreach($rows AS $row) {
      if (!$indexes = $this->initFeedIndex($row, $indexBy, $d, $pagePath)) {
        continue;
      }
      if (!is_array($indexes)) {
        $indexes = array($indexes);
      }
      foreach ($indexes AS $index) {
        if (!isset($d[$index]['pageview'])) {
          $d[$index]['pageview'] = $this->initPageviewDataStruc();
        }
      }
      $keys = $indexes;
      array_unshift($keys, '_all');
      //$keys = array('_all', $indexes);
      foreach ($keys AS $k) {
        $this->_addPageviewFeedDataRow($d[$k]['pageview'], $row);
      }
    }
    return $d;
  }

  function _addPageviewFeedDataRow(&$data, $row) {
    $data['recordCount']++;

    // pageviews not used with event filters
    $data['pageviews'] += !empty($row['pageviews']) ? $row['pageviews'] : 0;
    // TODO: if event filter used, cant get pageviews, so we request totalEvents
    // instead. Not eactly equivalent, but close.
    $data['pageviews'] += !empty($row['totalEvents']) ? $row['totalEvents'] : 0;
    $data['uniquePageviews'] += !empty($row['uniquePageviews']) ? $row['uniquePageviews'] : 0;
    //$data['pageviewsPerSession'] += ($row['pageviews'] * $row['pageviewsPerSession']);
    $data['timeOnPage'] += $row['timeOnPage'];
    $data['sticks'] += ($row['pageviews'] - $row['exits']);
    $data['goalValueAll'] += !empty($row['goalValueAll']) ? $row['goalValueAll'] : 0;
    $data['goalCompletionsAll'] += !empty($row['goalCompletionsAll']) ? $row['goalCompletionsAll'] : 0;

    // for queries with a single record per indexed entity, these numbers are exact
    // for queries with multiple records, this algo provides an estimated pageValueAll
    // using a weighted average across all records for the entity
    if (!empty($row['pageValue']) && !empty($row['uniquePageviews'])) {
      if ($data['uniquePageviews'] != 0) {
        $upv0 = $data['uniquePageviews'] - $row['uniquePageviews'];
        $data['pageValue'] =
          (($data['pageValue'] * $upv0) + ($row['pageValue'] * $row['uniquePageviews']))
          / ($upv0 + $row['uniquePageviews']);
      }
      //$data['pageValueAll'] = ($data['pageValue'] * $data['uniquePageviews']) / $data['recordCount'];
      $data['pageValueAll'] = ($data['pageValue'] * $data['uniquePageviews']); // / 2;
    }
    if (!empty($row[$this->attrStorage['ts']['field']])) {
      // set the timestamp of the earilest pageview
      if (!isset($data['timestamp']) || ($row[$this->attrStorage['ts']['field']] < $data['timestamp'])) {
        $data['timestamp'] = $row[$this->attrStorage['ts']['field']];
      }
    }

    return $data;
  }

  function addEntranceFeedData($d, $rows, $totals, $indexBy, $subIndexBy = '') {
    if (!isset($d['_all']['entrance'])) {
      $d['_all']['entrance'] = $this->initEntranceDataStruc();
      $d['_totals']['entrance'] = $this->initEntranceDataStruc();
    }
    if ($subIndexBy && !isset($d['_all']['entrance'][$subIndexBy])) {
      $d['_all'][$subIndexBy] = $this->initEntranceDataStruc();
    }
    $this->_addEntranceFeedDataRow($d['_totals']['entrance'], $totals);
    foreach($rows AS $row) {
      if (!$indexes = $this->initFeedIndex($row, $indexBy, $d)) {
        continue;
      }
      $subIndex = $this->initFeedIndex($row, $subIndexBy);
      if (!is_array($indexes)) {
        $indexes = array($indexes);
      }
      foreach ($indexes AS $index) {
        if (!isset($d[$index]['entrance'])) {
          $d[$index]['entrance'] = $this->initEntranceDataStruc();
        }
        if ($subIndexBy) {
          if (!isset($d[$index][$subIndexBy])) {
            $d[$index][$subIndexBy] = array('_all' => array());
            $d[$index][$subIndexBy]['_all']['entrance'] = $this->initEntranceDataStruc();
          }
          if ($subIndex && !isset($d[$index][$subIndexBy][$subIndex])) {
            $d[$index][$subIndexBy][$subIndex] = array('entrance' => $this->initEntranceDataStruc());
            $d[$index][$subIndexBy][$subIndex]['i'] = $subIndex;
          }
        }
      }

      $keys = $indexes;
      array_unshift($keys, '_all');

      $subkeys = array('_all');
      if ($subIndex) {
        $subkeys[] = $subIndex;
      }

      foreach ($keys AS $k) {
        if ($subIndexBy) {
          // only counts for _all once
          if ($k == '_all') {
            $this->_addEntranceFeedDataRow($d['_all'][$subIndexBy], $row);
          }
          else {
            foreach ($subkeys AS $sk) {
              $this->_addEntranceFeedDataRow($d[$k][$subIndexBy][$sk]['entrance'], $row);
            }
          }

        }
        else {
          $this->_addEntranceFeedDataRow($d[$k]['entrance'], $row);
        }
      }
    }
    return $d;
  }

  function _addEntranceFeedDataRow(&$data, $row) {
    $data['entrances'] += $row['entrances'];
    // v3 $data['newVisits'] += $row['newVisits'];
    $data['pageviews'] += !empty($row['pageviews']) ? $row['pageviews'] : 0;
    $data['uniquePageviews'] += !empty($row['uniquePageviews']) ? $row['uniquePageviews'] : 0;
    $data['timeOnPage'] += $row['timeOnPage'];
    // v3 $data['timeOnSite'] += $row['timeOnSite'];
    $data['sticks'] += ($row['entrances'] - $row['bounces']);
    $data['goalValueAll'] += !empty($row['goalValueAll']) ? $row['goalValueAll'] : 0;
    $data['goalCompletionsAll'] += !empty($row['goalCompletionsAll']) ? $row['goalCompletionsAll'] : 0;
    if (!empty($row[$this->attrStorage['ts']['field']])) {
      $data['timestamp'] = $row[$this->attrStorage['ts']['field']];
    }
    $data['recordCount']++;
    return $data;
  }

  function addSessionFeedData($d, $rows, $totals, $indexBy, $subIndexBy) {
    if (!isset($d['_all']['session'])) {
      $d['_all']['session'] = $this->initSessionDataStruc();
      $d['_totals']['session'] = $this->initSessionDataStruc();
    }
    $this->_addSessionFeedDataRow($d['_totals']['session'], $totals);
    $pagePath = '';
    foreach($rows AS $row) {
      if (!$indexes = $this->initFeedIndex($row, $indexBy, $d, $pagePath)) {
        continue;
      }
      if (!is_array($indexes)) {
        $indexes = array($indexes);
      }
      foreach ($indexes AS $index) {
        if (!isset($d[$index]['session'])) {
          $d[$index]['session'] = $this->initSessionDataStruc();
        }
      }
      $keys = $indexes;
      array_unshift($keys, '_all');
      //$keys = array('_all', $indexes);
      foreach ($keys AS $k) {
        $this->_addSessionFeedDataRow($d[$k]['session'], $row);
      }
    }
    return $d;
  }

  function _addSessionFeedDataRow(&$data, $row) {
    $data['recordCount']++;

    $data['sessions'] += !empty($row['sessions']) ? $row['sessions'] : 0;
    // v3 $data['newVisits'] += !empty($row['newVisits']) ? $row['newVisits'] : 0;
    $data['pageviews'] += !empty($row['pageviews']) ? $row['pageviews'] : 0;
    $data['uniquePageviews'] += !empty($row['uniquePageviews']) ? $row['uniquePageviews'] : 0;
    $data['timeOnPage'] += !empty($row['timeOnPage']) ? $row['timeOnPage'] : 0;
    // v3 $data['timeOnSite'] += !empty($row['timeOnSite']) ? $row['timeOnSite'] : 0;
    $data['goalValueAll'] += !empty($row['goalValueAll']) ? $row['goalValueAll'] : 0;
    $data['goalCompletionsAll'] += !empty($row['goalCompletionsAll']) ? $row['goalCompletionsAll'] : 0;

    // for queries with a single record per indexed entity, these numbers are exact
    // for queries with multiple records, this algo provides an estimated pageValueAll
    // using a weighted average across all records for the entity
    if (!empty($row['pageValue']) && !empty($row['uniquePageviews'])) {
      if ($data['uniquePageviews'] != 0) {
        $upv0 = $data['uniquePageviews'] - $row['uniquePageviews'];
        $data['pageValue'] =
          (($data['pageValue'] * $upv0) + ($row['pageValue'] * $row['uniquePageviews']))
          / ($upv0 + $row['uniquePageviews']);
      }
      $data['pageValueAll'] = ($data['pageValue'] * $data['uniquePageviews']); // / 2;
    }
    if (!empty($row[$this->attrStorage['ts']['field']])) {
      // set the timestamp of the earilest pageview
      if (!isset($data['timestamp']) || ($row[$this->attrStorage['ts']['field']] < $data['timestamp'])) {
        $data['timestamp'] = $row[$this->attrStorage['ts']['field']];
      }
    }

    return $data;
  }

  function addVisitorFeedData($d, $rows, $totals, $indexBy, $subIndexBy = '') {
    if (!isset($d['_all']['visitor'])) {
      $d['_all']['visitor'] = $this->initVisitorDataStruc();
      $d['_totals']['visitor'] = $this->initVisitorDataStruc();
    }
    if ($subIndexBy && !isset($d['_all']['visitor'][$subIndexBy])) {
      $d['_all'][$subIndexBy] = $this->initVisitorDataStruc();
    }
    $this->_addVisitorFeedDataRow($d['_totals']['visitor'], $totals);
    foreach($rows AS $row) {
      if (!$indexes = $this->initFeedIndex($row, $indexBy, $d)) {
        continue;
      }
      $subIndex = $this->initFeedIndex($row, $subIndexBy);
      if (!is_array($indexes)) {
        $indexes = array($indexes);
      }
      foreach ($indexes AS $index) {
        if (!isset($d[$index]['visitor'])) {
          $d[$index]['visitor'] = $this->initVisitorDataStruc();
        }
        if ($subIndexBy) {
          if (!isset($d[$index][$subIndexBy])) {
            $d[$index][$subIndexBy] = array('_all' => array());
            $d[$index][$subIndexBy]['_all']['visitor'] = $this->initVisitorDataStruc();
          }
          if ($subIndex && !isset($d[$index][$subIndexBy][$subIndex])) {
            $d[$index][$subIndexBy][$subIndex] = array('visitor' => $this->initVisitorDataStruc());
            $d[$index][$subIndexBy][$subIndex]['i'] = $subIndex;
          }
        }
      }

      $keys = $indexes;
      array_unshift($keys, '_all');

      $subkeys = array('_all');
      if ($subIndex) {
        $subkeys[] = $subIndex;
      }

      foreach ($keys AS $k) {
        if ($subIndexBy) {
          // only counts for _all once
          if ($k == '_all') {
            $this->_addVisitorFeedDataRow($d['_all'][$subIndexBy], $row);
          }
          else {
            foreach ($subkeys AS $sk) {
              $this->_addVisitorFeedDataRow($d[$k][$subIndexBy][$sk]['visitor'], $row);
            }
          }

        }
        else {
          $this->_addVisitorFeedDataRow($d[$k]['visitor'], $row);
        }
      }
    }
    return $d;
  }

  function _addVisitorFeedDataRow(&$data, $row) {
    $data['newUsers'] += $row['newUsers'];
    //$data['recordCount']++;
    return $data;
  }

  function addEventsFeedData($d, $rows, $totals, $indexBy, $mode = 'entrance', $type = '', $details = '') {
    if (!isset($d['_all'][$mode])) {
      $d['_all'][$mode] = $this->{'init' . $mode . 'DataStruc'}();
      $d['_totals'][$mode] = $this->{'init' . $mode . 'DataStruc'}();
    }
    $d['_totals'][$mode]['events']['_all'] = $this->_addEventsFeedDataRow($d['_totals'][$mode]['events']['_all'], $totals);
    $pagePath = '';
    foreach($rows AS $row) {
      if (!$indexes = $this->initFeedIndex($row, $indexBy, $d, $pagePath)) {
        continue;
      }

      if (!is_array($indexes)) {
        $indexes = array($indexes);
      }

      foreach ($indexes AS $index) {
        if (!isset($d[$index][$mode])) {
          $d[$index][$mode] = $this->{'init' . $mode . 'DataStruc'}();
        }
      }

      $keys = $indexes;
      array_unshift($keys, '_all');

      foreach ($keys AS $k) {
        $eventName = '';
        $field = '';
        if (isset($row['eventCategory'])) {
          $eventCategory = $row['eventCategory'];
          $type = '';
          if (substr($eventCategory, -1) == '!') {
            $type = 'valued';
            $eventCategory = substr($eventCategory, 0, -1);
          }
          else if (substr($eventCategory, -1) == '+') {
            $type = 'goal';
            $eventCategory = substr($eventCategory, 0, -1);
          }
          $eventName .= $eventCategory;
        }
        if (isset($row['eventAction'])) {
          $eventName .= ($eventName ? '->' : '') . $this->decodeRequestText($row['eventAction']);
        }
        if ($eventName) {
          if (!isset($d[$k][$mode]['events'][$eventName])) {
            $d[$k][$mode]['events'][$eventName] = $this->initEventsDataStruc();
            $d[$k][$mode]['events'][$eventName]['i'] = $eventName;
          }
          $d[$k][$mode]['events'][$eventName] = $this->_addEventsFeedDataRow($d[$k][$mode]['events'][$eventName], $row, $type);
        }
        $d[$k][$mode]['events']['_all'] = $this->_addEventsFeedDataRow($d[$k][$mode]['events']['_all'], $row, $type);
      }
    }
    return $d;
  }

  function _addEventsFeedDataRow($data, $row, $type = '') {
    // ignore session sticks
    if ($type == 'valued' || $type == 'goal') {
      // leverage metric better float precision back to value
      if ($type == 'valued' && !empty($row['metric2'])) {
        $row['eventValue'] = $row['metric2'];
      }
      else if ($type == 'goal' && !empty($row['metric3'])) {
        $row['eventValue'] = $row['metric3'];
      }
      $data['valuedValue'] += $row['eventValue'];
      $data['totalValuedEvents'] += $row['totalEvents'];
      $data['uniqueValuedEvents'] += $row['uniqueEvents'];
    }

    $data['value'] += $row['eventValue'];
    $data['totalEvents'] += $row['totalEvents'];
    $data['uniqueEvents'] += $row['uniqueEvents'];

    return $data;
  }

  function addGoalsFeedData($d, $rows, $totals, $indexBy, $mode = 'entrance', $details) {
    if (!isset($d['_all'][$mode])) {
      $d['_all'][$mode] = $this->{'init' . $mode . 'DataStruc'}();
      $d['_totals'][$mode] = $this->{'init' . $mode . 'DataStruc'}();
    }
    if (is_array($details)) {
      foreach ($details AS $id) {
        $d['_totals'][$mode]['goals']['_all'] = $this->_addGoalsFeedDataRow($d['_totals'][$mode]['goals']['_all'], $totals, $id);
      }
    }
    else {
      $d['_totals'][$mode]['goals']['_all'] = $this->_addGoalsFeedDataRow($d['_totals'][$mode]['goals']['_all'], $totals, 0);
    }

    foreach($rows AS $row) {
      if (!$indexes = $this->initFeedIndex($row, $indexBy, $d)) {
        continue;
      }

      if (!is_array($indexes)) {
        $indexes = array($indexes);
      }

      foreach ($indexes AS $index) {
        if (!isset($d[$index][$mode])) {
          $d[$index][$mode] = $this->{'init' . $mode . 'DataStruc'}();
        }
      }

      $keys = $indexes;
      array_unshift($keys, '_all');

      foreach ($keys AS $k) {
        if (is_array($details)) {
          foreach ($details AS $id) {
            if (!isset($d[$k][$mode]['goals']["n$id"])) {
              $d[$k][$mode]['goals']["n$id"] = $this->initGoalsDataStruc();
              $d[$k][$mode]['goals']["n$id"]['i'] = "n$id";
            }
            $d[$k][$mode]['goals']["n$id"] = $this->_addGoalsFeedDataRow($d[$k][$mode]['goals']["n$id"], $row, $id);
            $d[$k][$mode]['goals']['_all'] = $this->_addGoalsFeedDataRow($d[$k][$mode]['goals']['_all'], $row, $id);
          }
        }
        else {
          $d[$k][$mode]['goals']['_all'] = $this->_addGoalsFeedDataRow($d[$k][$mode]['goals']['_all'], $row, 0);
        }
      }
    }
    return $d;
  }

  function _addGoalsFeedDataRow($data, $row, $id) {
    if ($id) {
      $data["completions"] += $row["goal{$id}Completions"];
      $data["value"] += $row["goal{$id}Value"];
    }
    else {
      $data["completions"] += !empty($row["goalCompletionsAll"]) ? $row["goalCompletionsAll"] : 0;
      $data["value"] += !empty($row["goalValueAll"]) ? $row["goalValueAll"] : 0;
    }

    return $data;
  }

  function addGoalsAssistFeedData($d, $rows, $totals, $indexBy, $mode = 'entrance', $details = array()) {
    // TODO: incomplete. not used in any reports
    if (!isset($d['_all'][$mode])) {
      //$d['_all'][$mode] = $this->{'init' . $mode . 'DataStruc'}();
      //$d['_totals'][$mode] = $this->{'init' . $mode . 'DataStruc'}();
    }
    foreach ($details AS $id) {
      //$d['_totals'][$mode]['goals']['_all'] = $this->_addGoalsAssistFeedDataRow($d['_totals'][$mode]['goals']['_all'], $totals, $id);
    }
    foreach($rows AS $row) {


      $indexes = array();

      foreach ($indexes AS $i => $index) {
        if (!isset($d[$index][$mode])) {
          $d[$index][$mode] = $this->{'init' . $mode . 'DataStruc'}();
        }
      }

      $keys = $indexes;
      array_unshift($keys, '_all');

      foreach ($keys AS $k) {
        foreach ($details AS $id) {
          if (!isset($d[$k][$mode]['goals']["n$id"])) {
            //$d[$k][$mode]['goals']["n$id"] = $this->initGoalsDataStruc();
            //$d[$k][$mode]['goals']["n$id"]['i'] = "n$id";
          }
          //$d[$k][$mode]['goals']["n$id"] = $this->_addGoalsFeedDataRow($d[$k][$mode]['goals']["n$id"], $row, $id);
          $d[$k][$mode]['goalsAssist']['_all'] = $this->_addGoalsAssistFeedDataRow($d[$k][$mode]['goals']['_all'], $row, $id);
        }
      }
    }
    return $d;
  }

  function _addGoalsAssistFeedDataRow($data, $row, $id) {
    $data["completions"] += $row["goal{$id}Completions"];
    $data["value"] += $row["goal{$id}Value"];
    return $data;
  }

  function addVisitInfoFeedData($d, $rows, $totals, $indexBy, $mode = 'entrance', $details) {
    $keyOps = array(
      'entrances' => 0,
      'goalValueAll' => 0,
      $this->attrStorage['vtk']['field'] => 0,
    );
    foreach($rows AS $row) {
      $index = $this->initFeedIndex($row, $indexBy);
      if (!isset($d[$index]['visitinfo'])) {
        $d[$index]['visitinfo'] = array();
      }
      foreach ($row AS $key => $value) {
        if (!isset($keyOps[$key]) ||  $keyOps[$key]) {
          $d[$index]['visitinfo'][$key] = $value;
        }
      }
      if (isset($row['medium'])) {
        $d[$index]['visitinfo']['trafficcategory'] = $this->initFeedIndex($row, 'trafficcategory');
      }
      $d[$index]['visitinfo']['location'] = $this->initFeedIndex($row, 'location');
    }
    return $d;
  }

  function addEventsourceEventFeedData($d, $rows, $totals, $indexBy) {
    if (!isset($d['_all'])) {
      $d['_all'] = $this->initPageviewDataStruc();
      $d['_totals'] = $this->initPageviewDataStruc();
    }
    $d['_totals']['events']['_all'] = $this->_addEventsourceEventFeedDataRow($d['_totals']['events']['_all'], $totals);

    foreach($rows AS $row) {
      if (!$index = $this->initFeedIndex($row, $indexBy, $d)) {
        continue;
      }
      if (!isset($d[$index])) {
        $d[$index] = $this->initPageviewDataStruc();
      }
      if (!isset($d[$index]['title'])) {
        $d[$index]['title'] = $row['eventAction'];
        $d[$index]['url'] = $row['eventLabel'];
      }
      $keys = array('_all', $index);
      foreach ($keys AS $k) {
        if (isset($row['eventCategory'])) {
          $eventCateogry = $row['eventCategory'];
          if (!isset($d[$k]['events'][$eventCateogry])) {
            $d[$k]['events'][$eventCateogry ] = $this->initEventsDataStruc();
            $d[$k]['events'][$eventCateogry]['i'] = $eventCateogry;
          }
          $d[$k]['events'][$eventCateogry] = $this->_addEventsourceEventFeedDataRow($d[$k]['events'][$eventCateogry], $row);
        }
        $d[$k]['events']['_all'] = $this->_addEventsourceEventFeedDataRow($d[$k]['events']['_all'], $row);
      }
    }
    return $d;
  }

  function _addEventsourceEventFeedDataRow($data, $row) {
    $data['totalEvents'] += $row['totalEvents'];
    $data['uniqueEvents'] += $row['uniqueEvents'];
    if (substr($row['eventCategory'], -1) == '!') {
      $data['value'] += !empty($row['metric2']) ? $row['metric2'] : $row['eventValue'];
      $data['totalValuedEvents'] += $row['totalEvents'];
      $data['uniqueValuedEvents'] += $row['uniqueEvents'];
    }
    return $data;
  }

  function addPagePathMapFeedData($d, $rows, $totals, $indexBy) {

    $pathField = 'pagePath';
    $attrField = $this->indexByInfo['attrField'];
    foreach($rows AS $row) {
      if (!isset($d[$row[$pathField]])) {
        $d[$row[$pathField]] = array();
      }
      $d[$row[$pathField]][$row[$attrField]] = array();
      foreach ($row AS $key => $value) {
        if ($key == $pathField || $key == $attrField) {
          continue;
        }
        $d[$row[$pathField]][$row[$attrField]][$key] = $value;
      }
    }
    return $d;
  }

  function initFeedIndex(&$row, $indexBy, &$d = array(), &$pagePath = '') {
    if (!$indexBy) {
      return '';
    }
    $pagePath = !empty($row['pagePath']) ? $this->filterPagePath($row['pagePath']) : '';
    $landingPagePath = !empty($row['landingPagePath']) ? $this->filterPagePath($row['landingPagePath']) : '';
    $path = ($landingPagePath) ? $landingPagePath : $pagePath;
    if (!empty($this->dataIndexCallbacks[$indexBy])) {
      $func = $this->dataIndexCallbacks[$indexBy];
      $index = $func($row);
    }
    else if ($indexBy == 'content') {
      $index = (!empty($row['hostname']) ? $row['hostname'] : '') . $path;
    }
    else if ($indexBy == 'pagePath') {
      $index = (!empty($row['hostname']) ? $row['hostname'] : '') . $path;
    }
    else if ($indexBy == 'referralHostname') {
      $index = ($row['referralPath'] != '(not set)') ? $row['source'] : FALSE;
    }
    else if ($indexBy == 'referralHostpath') {
      $index = ($row['referralPath'] != '(not set)') ? $row['source'] . $row['referralPath'] : FALSE;
    }
    else if ($indexBy == 'referralPath') {
      $index = ($row['referralPath'] != '(not set)') ? $row['referralPath'] : FALSE;
    }
    else if ($indexBy == 'socialNetwork') {
      $index = ($row['socialNetwork'] != '(not set)') ? $row['socialNetwork'] : FALSE;
    }
    else if ($indexBy == 'searchEngine') {
      if ($row['medium'] == 'organic') {
        $index = (!empty($row['source']) && ($row['source'] != '(not set)')) ? $row['source'] : FALSE;
      }
      else {
        $index = FALSE;
      }
    }
    else if ($indexBy == 'keyword') {
      $index = ($row['keyword'] != '(not set)') ? $row['keyword'] : FALSE;
    }
    else if (($indexBy == 'searchKeyword') || ($indexBy == 'organicSearch')) {
      if ($row['medium'] == 'organic') {
        $index = (!empty($row['keyword']) && ($row['keyword'] != '(not set)')) ? $row['keyword'] : FALSE;
      }
      else {
        $index = FALSE;
      }
    }
    else if ($indexBy == 'campaign') {
      $index = ($row['campaign'] != '(not set)') ? $row['campaign'] : FALSE;
    }
    else if ($indexBy == 'trafficcategory') {
      $index = $this->determineTrafficCategoryIndex($row);
    }
    else if ($indexBy == 'landingpage') {
      $index = $row['eventLabel'];
      // strip query string
      $a = explode('?', $index);
      // strip protocal
      $a = explode('//', $a[0]);
      $index = (count($a) == 2) ? $a[1] : $a[0];
    }
    else if ($indexBy == 'visitor') {
      $index = $row[$this->attrStorage['vtk']['field']];
    }
    else if ($indexBy == 'visit') {
      $index = $row[$this->attrStorage['vtk']['field']] . '-' . $row['sessionCount'];
    }
    else if ($indexBy == 'country') {
      $index = $row['country'];
    }
    else if ($indexBy == 'location') {
      if (isset($row['city']) && isset($row['region'])) {
        $index = $row['city'] . ', ' . $row['region'] . (isset($row['country']) ? ' ' . $row['country'] : '');
      }
      else if (isset($row['country'])) {
        $index = $row['country'];
      }
    }
    /*
    else if ($indexBy == 'author') {
      $decoded = '';
      $data = $this->unserializeCustomVar($row['customVarValue1'], $decoded);
      $row['customVarValue1'] = $decoded;
      $index = $data['a'];
    }
    */
    else if (strpos($indexBy, 'pageAttribute:') === 0) {
      $decoded = '';
      $attrKey = $this->indexByInfo['attrKey'];
      $attrField = $this->indexByInfo['attrField'];
      $attrFieldStruc = $this->indexByInfo['attrFieldStruc'];

      if (isset($row[$this->indexByInfo['attrField']])) {
        $data = $this->unserializeCustomVar($row[$this->indexByInfo['attrField']], $decoded);
      }
      // if customVarValue1 not in request, try looking for path in pagePathMap
      else {
        $v = '';
        if (isset($row['pagePath']) && isset($this->pagePathMap[$row['pagePath']])) {
          // get first key
          reset($this->pagePathMap[$row['pagePath']]);
          $v = key($this->pagePathMap[$row['pagePath']]);
        }
        else if (isset($row['landingPagePath']) && isset($this->pagePathMap[$row['landingPagePath']])) {
          // get first key
          reset($this->pagePathMap[$row['landingPagePath']]);
          $v = key($this->pagePathMap[$row['landingPagePath']]);
        }

        if ($v) {
          $data = $this->unserializeCustomVar($v, $decoded);
        }
      }

      $row[$attrField] = $decoded;
      if (!empty($this->attributeInfo['page'][$attrKey]['storage']['analytics']['format'])) {
        if ($this->attributeInfo['page'][$attrKey]['storage']['analytics']['format'] == 'single') {
          $index = isset($data[$attrKey]) ? $this->getIndexGroupByKey('page', $attrKey, $data[$attrKey]) : '';
        }
        else if ($this->attributeInfo['page'][$attrKey]['storage']['analytics']['format'] == 'single_list') {
          $index = isset($data[$attrKey]) ? $this->getIndexGroupByKey('page', $attrKey, $data[$attrKey]) : array();
        }
      }
      else if ($this->attributeInfo['page'][$attrKey]['type'] == 'flag') {
        $index = isset($data[$attrKey]) ? $this->getIndexGroupByKey('page', $attrKey, $data[$attrKey]) : '';
      }
      else if (in_array($this->attributeInfo['page'][$attrKey]['type'], array('value', 'scalar', 'item'))) {
        $index = isset($data[$attrKey]) ? $this->getIndexGroupByKey('page', $attrKey, $data[$attrKey]) : '';
      }
      else if (in_array($this->attributeInfo['page'][$attrKey]['type'], array('list', 'vector'))) {
        $index = array();
        if (!empty($data) && is_array($data)) {
          foreach ($data AS $key => $value) {
            if (strpos($key, $attrKey) === 0) {
              $a = explode('.', $key);
              $index[] = $a[1];
              //$index[] = $key;
            }
          }
        }
      }
      else {
        $index = $data[$attrKey];
      }
    }
    else if ($indexBy == 'date') {
      // check if value is already in 'Ymd' mode
      if (is_string($row['date']) && (strlen($row['date']) == 8) && substr($row['date'], 0, 2) == '20') {
        return $row['date'];
      }
      else {
        return date('Ymd', $row['date']);
      }
    }
    else if ($indexBy == 'site') {
      return 'site';
    }
    else {
      $index = $row[$indexBy];
    }
    if ($index) {
      $indexes = is_array($index) ? $index : array($index);
      foreach ($indexes as $ind) {
        if (!isset($d[$ind])) {
          $d[$ind] = array();
          $d[$ind]['i'] = $ind;
        }
      }
    }


    return $index;
  }

  function determineTrafficCategoryIndex($row) {
    $medium = strtolower($row['medium']);
    if (!empty($row['socialNetwork']) && ($row['socialNetwork'] != '(not set)')) {
      return 'social network';
    }
    if (!empty($row['hasSocialSourceReferral']) && ($row['hasSocialSourceReferral'] != 'Yes')) {
      return 'social network';
    }
    if ($row['medium'] == 'facebook') {
      return 'social network';
    }
    if ($row['medium'] == 'twitter') {
      return 'social network';
    }
    if ($row['medium'] == 'linkedin') {
      return 'social network';
    }
    if ($row['medium'] == '(none)') {
      return 'direct';
    }
    if ($row['medium'] == 'organic') {
      return 'organic search';
    }
    if ($medium == 'ppc' || $medium == 'cpc') {
      return 'ppc';
    }
    if ($row['medium'] == 'email') {
      return 'email';
    }
    if ($row['medium'] == 'referral') {
      return 'referral link';
    }
    if ($row['medium'] == 'feed') {
      return 'feed';
    }
    return 'other';
  }

  function getTrafficsourcesSubIndexes() {
    $s = array(
      'medium',
      'source',
      'referralHostpath',
      'searchKeyword',
      'socialNetwork',
      'campaign',
      'trafficcategory'
    );
    return $s;
  }

  function initIndexDataStruc() {
    $a = array(
      '_all' => array(),
      '_totals' => array(),
    );
    return $a;
  }

  function initEntranceDataStruc() {
    $a = array(
      'events' => $this->initEventsDataArrayStruc(),
      'goals' => $this->initGoalsDataArrayStruc(),
      'entrances' => 0,
      // v3 'newVisits' => 0,
      'pageviews' => 0,
      'uniquePageviews' => 0,
      'timeOnPage' => 0,
      // v3 'timeOnSite' => 0,
      'sticks' => 0,
      'goalValueAll' => 0,
      'goalCompletionsAll' => 0,
      'pageValue' => 0,
      'pageValueAll' => 0,
      'recordCount' => 0,
    );
    return $a;
  }

  function initPageviewDataStruc() {
    $a = array(
      'events' => $this->initEventsDataArrayStruc(),
      'goals' => $this->initGoalsDataArrayStruc(),
      'goalsAssist' => $this->initGoalsAssistDataArrayStruc(),
      'pageviews' => 0,
      'uniquePageviews' => 0,
      'timeOnPage' => 0,
      'sticks' => 0,
      'goalValueAll' => 0, // the goalValue directly generated by the entity
      'goalCompletionsAll' => 0,
      'pageValue' => 0,
      'pageValueAll' => 0, // the goalValue of any downstream goals (ie goal assists)
      'recordCount' => 0,
    );
    return $a;
  }

  function initSessionDataStruc() {
    $a = array(
      'events' => $this->initEventsDataArrayStruc(),
      'goals' => $this->initGoalsDataArrayStruc(),
      'sessions' => 0,
      //'newVisits' => 0,
      'pageviews' => 0,
      'uniquePageviews' => 0,
      'timeOnPage' => 0,
      // v3 'timeOnSite' => 0,
      'goalValueAll' => 0, // the goalValue directly generated by the entity
      'goalCompletionsAll' => 0,
      'pageValue' => 0,
      'pageValueAll' => 0, // the goalValue of any downstream goals (ie goal assists)
      'recordCount' => 0,
    );
    return $a;
  }

  function initVisitorDataStruc() {
    $a = array(
      'newUsers' => 0,
    );
    return $a;
  }

  function initEventsDataArrayStruc() {
    $a = array();
    $a['_all'] = $this->initEventsDataStruc();
    return $a;
  }

  function initEventsDataStruc() {
    $a = array(
      'value' => 0,
      'totalEvents' => 0,
      'uniqueEvents' => 0,
      'valuedValue' => 0,
      'totalValuedEvents' => 0,
      'uniqueValuedEvents' => 0,
    );
    return $a;
  }

  function initGoalsDataArrayStruc() {
  $a = array();
  $a['_all'] = $this->initGoalsDataStruc();
  return $a;
}

  function initGoalsDataStruc() {
    $a = array(
      'value' => 0,
      'completions' => 0,
    );
    return $a;
  }

  function initGoalsAssistDataArrayStruc() {
    $a = array();
    $a['_all'] = $this->initGoalsAssistDataStruc();
    return $a;
  }

  function initGoalsAssistDataStruc() {
    $a = array(
      'prev1Value' => 0,
      'prev1Completions' => 0,
      'prev2Value' => 0,
      'prev2Completions' => 0,
      'prev3Value' => 0,
      'prev3Completions' => 0,
    );
    return $a;
  }

  function formatPathFilter($path, $type = '', $bestMatch = FALSE) {
    $f = (($type == 'landingpage') || ($type == 'landingPagePath')) ? 'landingPagePath' : 'pagePath';
    $path = urldecode($path);
    if ($bestMatch) {
      $filter = 'ga:' . $f . '=~' . preg_quote(substr($path, -100)) . '(#.*)?$';
      return $filter;
    }
    if (strlen($path) <= 120) {
      $filter = 'ga:' . $f . '=~^' . preg_quote($path) . '(#.*)?$';
    }
    else {
      $filter = 'ga:' . $f . '=@' . $path . ';ga:' . $f . '=~' . preg_quote(substr($path, -100)) . '(#.*)?$';
    }
    return $filter;
  }

  function unserializeCustomVar($str, $decoded_str = '') {
    $decoded_str = html_entity_decode($str);
    $a = explode("&", $decoded_str);
    $data = array();
    foreach ($a AS $i => $e) {
      $kv = explode("=", $e);
      if (empty($kv[0])) {
        continue;
      }
      if (!empty($this->indexByInfo['attrFieldType'])) {
        if ($this->indexByInfo['attrFieldType'] == 'single') {
          $data[$this->indexByInfo['attrKey']] = $kv[0];
        }
        else if ($this->indexByInfo['attrFieldType'] == 'single_list') {
          $data[$this->indexByInfo['attrKey'] . '.' . $kv[0]] = '';
        }
      }
      else if (count($kv) == 2) {
        $data[$kv[0]] = $kv[1];
      }
      else {
        $data[$kv[0]] = '';
      }
    }
    return $data;
  }

  static function getIndexGroup($attr_info, $value, $options = array()) {
    // process special formats
    if (isset($attr_info['format'])) {
      if ($attr_info['format'] == 'datetimedow') {
        return self::getIndexGroupDatetimedow($attr_info, $value, $options);
      }
    }
    if (!isset($attr_info['index_grouping'])) {
      return $value;
    }

    $value = (float)$value;

    $dir = ($attr_info['index_grouping'][1] > $attr_info['index_grouping'][0]) ? 1 : -1;
    $adjust_floor = 0;
    $adjust_ceil = -1;
    if (isset($attr_info['index_grouping_round'])) {
      $round = $attr_info['index_grouping_round'];
      $adjust_floor = round($adjust_floor / (10^$round), $round) ;
      $adjust_ceil = round($adjust_ceil / (10^$round), $round) ;
    }

    foreach ($attr_info['index_grouping'] AS $i => $gv) {
      if ($value < $gv) {
        if ($dir == 1) {
          if ($i > 0) {
            return ($attr_info['index_grouping'][$i-1] + $adjust_floor) . '-' . ($attr_info['index_grouping'][$i] + $adjust_ceil);
          }
          else {
            return '-' . $attr_info['index_grouping'][$i];
          }
        }
      }
    }

    return $attr_info['index_grouping'][count($attr_info['index_grouping'])-1] . '-';
  }

  static function getIndexGroupDatetimedow($attr_info, $value, $options) {
    // formats
    // YYYYMM - year month
    // YYYYMMDD - year month date
    // wW - day of week
    // tHHMM - time of day
    $focus = 'H';
    if ($focus == 'a') {
      return 'Hi' . substr($value, 8, 4);
    }
    else if ($focus == 'Hi') {
      return 'Hi' . substr($value, 8, 4);
    }
    else if ($focus == 'H') {
      return 'H' . substr($value, 8, 2);
    }
    else if ($focus == 'w') {
      return 'w' . substr($value, 12, 1);
    }
    else if ($focus == 'Ymd') {
      return substr($value, 0, 8);
    }
    else {
      return substr($value, 0, 6);
    }
  }

  function getIndexGroupByKey($scope, $attrKey, $value, $options = array()) {
    if (!isset($this->attributeInfo[$scope][$attrKey])) {
      return $value;
    }
    return self::getIndexGroup($this->attributeInfo[$scope][$attrKey], $value, $options);
  }

  function filterPagePath($path) {
    $path = html_entity_decode($path);
    $filter_queries = array(
      'sid',  // webform's submission id
      'submissionGuid', // HubSpot's form ids
      'hsCtaTracking', // HubSpot CTA tracking
      '_hsenc',  // HubSpot code
      '_hsmi', // HubSpot code
      '__hstc',
      '__hssc',
    );
    $a = explode('?', $path);
    $query = '';
    if (!empty($a[1])) {
      $b = explode('&', $a[1]);
      foreach ($b AS $c) {
        $d = explode('=', $c);
        if (!in_array($d[0], $filter_queries)) {
          $query .= (($query) ? '&' : '') . $c;
        }
      }
    }
    if ($query) {
      $path = $a[0] . "?$query";
    }
    else {
      $path = $a[0];
    }
    return $path;
  }

  function decodeRequestText($str) {
    // convert:
    // &#039; -> '
    $str = htmlspecialchars_decode($str, ENT_QUOTES);
    return $str;
  }

  function encodeRequestText($str) {
    $str = htmlspecialchars($str);
    return $str;
  }

  function encodeFilterText($str) {
    $str = $this->encodeRequestText($str);
    $str = str_replace(',', '\,', $str);
    $str = str_replace(';', '\;', $str);
    return $str;
  }

  /**
   * Creates regex to filter dimensions for values greater than $number
   * @param $param
   * @param $number
   * @param string $key
   * @param array options
   * @return string
   */
  static function formatGtRegexFilter($param, $number, $key = '', $options = array()) {
    $k = ($key) ? "&$key=" : "^";
    $end = ($key) ? '&' : "$";

    $nstr = (string)$number;
    $num_arr = str_split($nstr);
    $digits = count($num_arr);

    $prefix = '';
    $prefix_digits = 0;
    if (!empty($options['prefix'])) {
      $prefix = $options['prefix'];
      $nstr = (string)$prefix;
      $prefix_num_arr = str_split($nstr);
      $prefix_digits = count($prefix_num_arr);
    }

    $regex = '';
    if (empty($options['fixed_width'])) {
      $regex .= $param . '=~' . $k . '\d{' . ($prefix_digits + $digits + 1) . '\,}' . $end;
    }

    $p = '';
    foreach ($num_arr AS $i => $digit) {
      $digit = (int)$digit;
      if ($digit < 9) {
        $regex .= ($regex ? ',' : '') . $param .  '=~'  . $k . $prefix . $p;
        if ((1 + $digit) == 9) {
          $regex .= '9';
        }
        else {
          $regex .= '[' . (1 + $digit) . '-9]';
        }
        if ($i < ($digits - 1)) {
          $regex .= '\d{' . ($digits - 1 - $i) . '}' . $end;
        }
        else {
          $regex .= $end;
        }
      }
      $p .= (string)$digit;
    }
    return $regex;
  }

  /**
   * Creates regex to filter dimensions for values greater than or equal to $number
   * @param $param
   * @param $number
   * @param string $key
   * @return string
   */
  static function formatGtEqRegexFilter($param, $number, $key = '', $options = array()) {
    $regex = '';
    $k = ($key) ? "&$key=" : "^";
    $end = ($key) ? '&' : "$";
    $nstr = (string)$number;
    $nstra = explode('.', $nstr);
    $nstr = $nstra[0];
    if (isset($nstra[1])) {
      $nstr_r = $nstra[1];
    }

    if (!$nstr || ((int)$nstr == 0)) {
      $num_arr = array();
      $digits = 0;
    }
    else {
      $num_arr = str_split($nstr);
      $digits = count($num_arr);
    }

    $prefix = '';
    $prefix_digits = 0;
    if (!empty($options['prefix'])) {
      $prefix = $options['prefix'];
      $nstr = (string)$prefix;
      $prefix_num_arr = str_split($nstr);
      $prefix_digits = count($prefix_num_arr);
    }

    $regex = '';
    if (empty($options['fixed_width'])) {
      $regex .= $param . '=~' . $k . '\d{' . ($prefix_digits + $digits + 1) . '\,}' . $end;
    }


    $p = '';
    $rs = $nstr;

    foreach ($num_arr AS $i => $digit) {
      $digit = (int) $digit;
      $rs = substr($rs, 1);
      $r = (int)$rs;

      if ($r > 0) {
        if ($digit < 9) {
          $regex .= ',' . $param . '=~' . $k . $prefix . $p;
          if ((1 + $digit) == 9) {
            $regex .= '9';
          }
          else {
            $regex .= '[' . (1 + $digit) . '-9]';
          }
          $regex .= '\d{' . ($digits - 1 - $i) . '}' . $end;
        }
      }
      else {
        if ($digit > 0) {
          $regex .= ',' . $param . '=~' . $k . $prefix . $p;
          if ($digit == 9) {
            $regex .= '9';
          }
          else {
            $regex .= '[' . ($digit) . '-9]';
          }
          if (($digits - $i) > 1) {
            $regex .= '\d{' . ($digits - 1 - $i) . '}';
          }
          $regex .= $end;
        }
        break;
      }
      $p .= (string) $digit;
    }
    /*

    // process remander if float
    if (isset($nstr_r)) {
      $num_arr = str_split($nstr_r);
      $digits = count($num_arr);
      //$regex .= $param . '=~' . $k . '\d{' . ($digits + 1) . '\,}' . $end;
      $p = '';
      foreach ($num_arr AS $i => $digit) {
        dsm($digit);
        $digit = (int) $digit;
        if ($digit < 9) {
          $regex .= ',' . $param . '=~' .  $k . $nstr . '\.' . $p;
          if ((1 + $digit) == 9) {
            $regex .= '9';
          }
          else {
            $regex .= '[' . ($digit) . '-9]';
          }
          $regex .= $end;
        }
        //$p .= (string) $digit;
      }
    }
    */

    return $regex;
  }

  static function formatLtRegexFilter($param, $number, $key = '', $options = array()) {
    $end = ($key) ? '&' : "$";
    $nstr = (string)$number;
    if (isset($options['prefix'])) {
      $nstr = (string)$options['prefix'] . $nstr;
    }
    $num_arr = str_split($nstr);
    $digits = count($num_arr);
    $regex = '';

    $p = ''; // processed
    $r = ''; // remaining

    foreach ($num_arr AS $i => $digit) {
      $digit = (int)$digit;
      $r = substr($r, 1);
      $rn = (int)$r;

      // if digit is zero or first digit is 1, skip.
      if ($digit > 0 && !($i==0 && $digit == 1)) {
        $regex .= (($regex) ? ',' : '') . $param .  '=~^' . $p;

        if (($digit - 1) == 0) {
          $regex .= '0';
        }
        else {
          $regex .= '[0-' . ($digit - 1) . ']';
        }
        if ($i < ($digits - 1)) {
          $regex .= '\d{' . ($digits - 1 - $i) . '}' . $end;
        }
        else {
          $regex .= $end;
        }
      }
      $p .= (string)$digit;
    }
    if (($digits) > 1) {
      $regex .= (($regex) ? ',' : '') . $param . '=~^\d{0\,' . ($digits - 1) . '}' . $end;
    }

    return $regex;
  }

  /**
   * Creates regex to filter dimensions for values greater than $number
   * @param $param
   * @param $number
   * @param string $key
   * @return string
   */
  static function formatLtEqRegexFilter($param, $number, $key = '', $options = array()) {
    $end = ($key) ? '&' : "$";
    $nstr = (string)$number;
    $num_arr = str_split($nstr);
    $digits = count($num_arr);
    $regex = '';

    $p = ''; // processed
    $r = ''; // remaining

    foreach ($num_arr AS $i => $digit) {
      $digit = (int)$digit;
      $r = substr($r, 1);
      $rn = (int)$r;

      // if digit is zero or first digit is 1, skip.
      if ($digit > 0 && !($i==0 && $digit == 1)) {
        $regex .= (($regex) ? ',' : '') . $param .  '=~^' . $p;

        if (($digit - 1) == 0) {
          $regex .= '0';
        }
        else {
          //$regex .= '[0-' . ($digit - 1) . ']';
          if ($i == ($digits - 1)) {
            $regex .= '[0-' . ($digit) . ']';
          }
          else {
            $regex .= '[0-' . ($digit - 1) . ']';
          }

        }
        if ($i < ($digits - 1)) {
          $regex .= '\d{' . ($digits - 1 - $i) . '}' . $end;
        }
        else {
          $regex .= $end;
        }
      }
      else if ($digit == 0 && ($i == ($digits - 1))) {
        $regex .= (($regex) ? ',' : '') . $param .  '=~^' . $p . '0';
      }
      $p .= (string)$digit;
    }
    if (($digits) > 1) {
      $regex .= (($regex) ? ',' : '') . $param . '=~^\d{0\,' . ($digits-1) . '}' . $end;
    }

    return $regex;
  }

  static function formatNltRegexFilter($param, $number) {
    $nstr = (string)$number;
    $num_arr = str_split($nstr);
    $digits = count($num_arr);
    $regex = $param . '!~^\d{0\,' . ($digits-1) . '}$';
    $p = '';
    foreach ($num_arr AS $i => $digit) {
      $digit = (int)$digit;
      if ($digit > 0) {
        $regex .= ';' . $param .  '!~^' . $p;
        if (($digit - 1) == 0) {
          $regex .= '0';
        }
        else {
          $regex .= '[0-' . ($digit - 1) . ']';
        }
        if ($i < ($digits - 1)) {
          $regex .= '\d{' . ($digits - 1 - $i) . '}$';
        }
        else {
          $regex .= '$';
        }
      }
      $p .= (string)$digit;
    }
    return $regex;
  }

}