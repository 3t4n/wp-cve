<?php

namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");

class lrga_earnings_sales{
	
	protected $pluginId;
	protected $graphData  = array();
	protected $currentFilter;
	protected $graphItemsIds = array();	
	protected $rawData;
	protected $storeCurrencyPrefix  = "";
	protected $storeCurrencySuffix  = "";
	protected $graphLabel;
	protected $salesLabel;
	protected $earningsLabel;
	protected $defaultSettings;
	protected $defaultFilterID;
	protected $defaultFilters;
	protected $swatches;
	protected $defaultColor;
	protected $startDate;
	protected $endDate;	
	protected $periodArray = array();
	protected $filtersData = array();
	private $sales;
	private $earnings;

	public function __construct($startDate, $endDate){
		$this->rawData             = array();
		$this->startDate           = new \DateTime($startDate);
		$this->endDate             = new \DateTime($endDate);
		$this->swatches            = array("#D32F2F", "#C2185B", "#7B1FA2", "#512DA8", "#303F9F", "#1976D2", "#0288D1", "#0097A7", "#00796B", "#388E3C", "#689F38", "#F57C00", "#E64A19", "#5D4037");
		$this->defaultColor        = "#E0E0E0";
		
		$this->defaultFilterID     = "all";
		$this->defaultFilters      = array("all"       => array(  "orders"   => array("on","#F8B195")));
																  
		$this->defaultSettings     = array("showempty" => array(  "id"      => "showempty",
																  "name"    => __('Empty Series', 'lara-google-analytics'),
																  "default" => "off",
																  "options" => array('on'    => __('Show', 'lara-google-analytics'),
																					 'off'   => __('Hide', 'lara-google-analytics'))
																),
											"showtotal" => array( "id"      => "showtotal",
											   					  "name"    => __('Totals', 'lara-google-analytics'),
																  "default" => "on",
																  "options" => array('on'    => __('Show', 'lara-google-analytics'),
																    				 'off'   => __('Hide', 'lara-google-analytics'))
																),		
											);
	}

	protected function getSales(){
		if ((!is_array($this->rawData['sales'])) || (empty($this->rawData['sales']))) {
			$this->rawData['sales'] = $this->generateEmptyPeriodArray();
		}
		$this->sales = $this->prepareSeriesData($this->rawData['sales']);

	}
	
	protected function getEarnings(){
		if ((!is_array($this->rawData['earnings'])) || (empty($this->rawData['earnings']))) {
			$this->rawData['earnings'] =  $this->generateEmptyPeriodArray();
		}		
		$this->earnings = $this->prepareSeriesData($this->rawData['earnings']);
	}

	private function getMaxAxisValue($arr){
		$maxAxisValue =  max($arr) * 1.5;
		return $maxAxisValue;
	}
	
	private function getTotals($arr){
		$totals = array_sum($arr);
		return $totals;
	}
	
	private function prepareSeriesData($arr){
		$preparedArray = array("config" => array("maxAxisValue" => 0,
												 "Total"        => 0));
		
		foreach ($arr as $series){
			if (is_array($series) && !empty($series)){
				$finalArray = array();
				$finalArrayValues = array();

				foreach ($series["data"] as $id => $value){ 
					$finalArray[] = array($id, $value);
					$finalArrayValues[] = $value;
				}
				
				@array_walk($finalArray, array($this, 'convertDate'));
				$maxAxisValue = $this->getMaxAxisValue($finalArrayValues);
				$total = $this->getTotals($finalArrayValues);
				$preparedArray['series'][] = array("data"  			=> $finalArray,
												   "id"  		    => $series["id"],	
												   "label"  		=> $series["label"],
												   "color"  		=> $series["color"],
												   "total"			=> $total);	
				$preparedArray['config']['maxAxisValue'] = $preparedArray['config']['maxAxisValue'] + $maxAxisValue;
				$preparedArray['config']['Total'] = $preparedArray['config']['Total'] + $total;
			}
		}
			
		return $preparedArray;
	}

	private function convertDate(&$item){
		$item[0] = strtotime($item[0]." UTC") * 1000;
	}
	
	protected function generateEmptyPeriodArray(){
		$period = new \DatePeriod($this->startDate, new \DateInterval('P1D'), $this->endDate->modify( '+1 day' ));
		$periods = iterator_to_array($period);
		foreach($periods as $date) { 
				$array[$date->format('Y-m-d')] = 0; 
		}
		return $array;
	}
	
	protected function buildTree(&$items) {
		$map = array(0 => array('children' => array()));

		foreach ($items as &$item) {
			$map[$item['id']] = &$item;
		}

		foreach ($items as &$item) {
			$map[$item['parent']]['children'][$item['id']] = &$item;
		}

		return $map[0]['children'];
	}
	
	protected function getColor($type, $id){
		if (!empty($this->graphData["filters"][$type][$id][1])){
			$color =  $this->graphData["filters"][$type][$id][1];
		}else {$color = $this->defaultColor;}
		return $color;
	}

	protected function getState($type, $id){
		$state = "off";
		if (!empty($this->graphData["filters"][$type][$id][0])){
			$state =  $this->graphData["filters"][$type][$id][0];
		}
		return $state;
	}

	protected function initializeGraphData(){
			$this->graphData["currentfilter"] = $this->defaultFilterID;
			$this->graphData["filters"]       = $this->defaultFilters;

			$settings = $this->getPluginSettings();
			foreach ($settings as $setting){
				$this->graphData["settings"][$setting["id"]] = $setting["default"];
			}
		$this->currentFilter = $this->graphData["currentfilter"];
	}
	
	public function getGraphData(){
		$graphData = array();
		$pluginSettings = $this->getPluginSettings();
		$pluginFilters  = $this->getPluginFilters();
		$this->getFiltersData();
		
		$settings = array();
		foreach ($pluginSettings as $setting){
			$setting["value"] = $this->graphData["settings"][$setting['id']];
			$settings[$setting['id']] = $setting;
		}
		
		$filters = array();
		foreach ($pluginFilters as $filter){
			$filter["data"] = array();
			if (!empty($this->filtersData[$filter['id']])){
				$filter["data"] = $this->filtersData[$filter['id']];
			}
			$filters[$filter['id']] = $filter;
		}		
		
		$graphData["defaultcolor"]   = $this->defaultColor;
		$graphData["swatches"]       = $this->swatches;
		$graphData["currentfilter"]  = $this->currentFilter;
		$graphData["settings"]       = $settings;
		$graphData["filters"]        = $filters;
			
		return $graphData;	
	}	


	public function getGraphOutput(){
		
		$this->getRawSeriesData();
		$this->getSales();
		$this->getEarnings();
		
		$sales = array("config" => array("label" => $this->salesLabel,
						"lrbefore"=>"",
						"lrafter"=>"",
						"lrformat"=>"",
						"maxv"=> $this->sales['config']['maxAxisValue'],
						"total"=>$this->sales['config']['Total']),
						"series"  => $this->sales["series"]);

		$earnings = array("config" => array("label" => $this->earningsLabel,
						  "lrbefore"=>$this->storeCurrencyPrefix,
						  "lrafter"=>$this->storeCurrencySuffix,
						  "lrformat"=>"",
						  "maxv"=> $this->earnings['config']['maxAxisValue'],
						  "total"=>$this->earnings['config']['Total']),
						  "series"  =>  $this->earnings["series"]);
			
		$this->graphData["settings"]['graphlabel'] = $this->graphLabel;
		return array($sales,$earnings,$this->graphData["settings"]);
	}
}
?>