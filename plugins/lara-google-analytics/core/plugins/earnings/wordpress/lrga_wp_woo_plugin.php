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

class lrga_wp_woo_plugin extends lrga_earnings_sales{
	
	private  $rawSales;
	private  $rawEarnings;


	public function __construct($startDate, $endDate){
		parent::__construct($startDate, $endDate);
		$this->pluginId       = "woocommerce";
		$this->graphLabel     = __('WooCommerce', 'lara-google-analytics');
		$this->salesLabel     = __('Orders', 'lara-google-analytics');
		$this->earningsLabel  = __('Income', 'lara-google-analytics');
		$this->getStoreCurrency();
		$this->initializeGraphData();	
	}

	protected function getStoreCurrency(){
		$this->storeCurrencyPrefix  = get_woocommerce_currency_symbol();
		$this->storeCurrencySuffix  = "";
	}

	protected function getPluginSettings(){
		$settings  = array("status" => array("id"      => "status",
											 "name"    => __('Order Status', 'lara-google-analytics'),
											 "default" => "completed",
											 "options" => array('pending'    => __('Pending', 'lara-google-analytics'),
																'processing' => __('Processing', 'lara-google-analytics'),
																'on-hold'    => __('On Hold', 'lara-google-analytics'),
																'completed'  => __('Completed', 'lara-google-analytics'),
																'refunded'   => __('Refunded', 'lara-google-analytics'),
																'failed'     => __('Failed', 'lara-google-analytics'),
																'cancelled'  => __('Cancelled', 'lara-google-analytics'))
											),
							"date"  =>  array("id"      => "date",
											  "name"    => __('Order Date', 'lara-google-analytics'),
											  "default" => "date_paid",
											  "options" => array('date_created'   => __('Created', 'lara-google-analytics'),
																 'date_modified'  => __('Modified', 'lara-google-analytics'),
																 'date_completed' => __('Completed', 'lara-google-analytics'),
																 'date_paid'      => __('Paid', 'lara-google-analytics'))
										),		
						);
		$results = array_merge($settings, $this->defaultSettings);
		return $results;
	}
	
	protected function getPluginFilters(){
		$filters = array("all"        => array( "id"         => "all",
												"name"       => __('All Orders', 'lara-google-analytics')
											  ),
						"categories"  => array( "id"         => "categories",
												"name"       => __('Categories', 'lara-google-analytics')
											 ),		  
						"products"    => array( "id"         => "products",
												"datasource" => "categories",
												"name"       => __('Products', 'lara-google-analytics')
											  ),
						"types"       => array( "id"         => "types",
												"name"       => __('Types', 'lara-google-analytics')
											  ),							  
						);

		return $filters;
	}	
	
	private function getAllItems($ids = array(), $setFilterData = false){
		$results = array();
		$type = "all";
		
		$id         = "orders";
		$name       = __('All Orders', 'lara-google-analytics');
		$state      = $this->getState($type, $id);
		$color      = $this->getColor($type, $id);	
		
		$results['orders'] = array("id"    => $id,
								   "name"  => $name,
								   "state" => $state,
								   "color" => $color);
						 
		if($setFilterData === true){
			$this->filtersData['all'] = $results;
		}

		return $results;	
	}
	
	private function getProducts($ids = array(), $setFilterData = false){
		$query = new \WC_Product_Query( array(
			'include'  => $ids,
			'limit'    => -1,
			'orderby'  => 'ID',
			'order'    => 'ASC',
			'return'   => 'objects',
		) );
		
		$products = $query->get_products();	

		$results   = array();
		$type = "products";
		
		foreach ($products as $product){
			$id         = $product->get_id();
			$name       = $product->get_name();
			$state      = $this->getState($type, $id);
			$color      = $this->getColor($type, $id);
			$categories = (!empty($product->get_category_ids()) ? $product->get_category_ids() : array(0));			
			
			$results[$id] =  array("id"          => $id,
								   "name"        => $name,
								   "state"       => $state,
								   "color"       => $color,
								   "type"        => $type,
								   "categories"  => $categories);
								   
			if($setFilterData === true){
				foreach ($categories as $category){
					$this->filtersData['products'][$category][$id] = $results[$id];
				}				
			}
		}
		
		if($setFilterData === true){
			ksort($this->filtersData['products']);
		}

		return $results;
	}
	
	private function getCategories($ids = array(), $setFilterData = false){
		$args = array(
			'include'      => $ids,
		    'taxonomy'     => 'product_cat',
			'orderby'      => 'term_id',
			'hide_empty'   => true,
			'hierarchical' => true, 
			'limit' => -1,
		);	
		
		$categories = get_terms($args);

		$results  = array();
		$type = "categories";
		
		foreach($categories as $category){
			$id         = $category->term_id;
			$name       = $category->name;
			$state      = $this->getState($type, $id);
			$color      = $this->getColor($type, $id);
			$parent     = $category->parent;			
			
			$results[$id] =  array("id"          => $id,
								   "name"        => $name,
								   "state"       => $state,
								   "color"       => $color,
								   "type"        => $type,
								   "parent"      => $parent);
								   
			if($setFilterData === true){
				if (!empty($this->filtersData['products'][$id])){
					$results[$id]["products"] = $this->filtersData['products'][$id];
				}				
			}								   
		}
		
		if($setFilterData === true){
			$this->filtersData['categories'] = $this->buildTree($results);
		}
	
		return $results;
	}


	private function getProductTypes($ids = array(), $setFilterData = false){
		$productTypes = wc_get_product_types();
		
		$results  = array();
		$type = "types";
		
		foreach($productTypes as $id => $name){
			if (!empty($ids) && !in_array($id, $ids)){
				continue;
			}else{
				$state      = $this->getState($type, $id);
				$color      = $this->getColor($type, $id);
		
				$results[$id] =  array("id"          => $id,
									   "name"        => $name,
									   "state"       => $state,
									   "color"       => $color,
									   "type"        => $type);
			}
		}

		if($setFilterData === true){
			$this->filtersData['types'] = $results;
		}
		return $results;		
	}	
	
	private function getOrders(){
		$args = array( 'date_paid' => ''.$this->startDate->format('U').'...'.$this->endDate->format('U').'',
					   'status'    => 'completed',
					   'limit'     => -1
					 );
		return wc_get_orders($args);
	}
	

	protected function getFiltersData(){
		$this->getAllItems(array(), true);
		$this->getProducts(array(), true);
		$this->getCategories(array(), true);
		$this->getProductTypes(array(), true);
		unset($this->filtersData['products']);
	}
	
	

	private function initializeGraphSeriesArray($items){
		foreach($items as $item){
			$this->rawSales["series_".$item['id']]    =  array("id" =>$this->currentFilter."_".$item['id'], "label" => $item['name'], "color" => $item['color'], "data"  => $this->periodArray);
			$this->rawEarnings["series_".$item['id']] =  array("id" =>$this->currentFilter."_".$item['id'], "label" => $item['name'], "color" => $item['color'], "data"  => $this->periodArray);
		}
	}
	
	private function setGraphSeriesArray(){
		$items = $this->getAllItems();
		$this->initializeGraphSeriesArray($items);
	}
	
	private function setRawData($type, $id, $date, $quantity, $total){
		if ((!empty($this->graphData["filters"][$type][$id][0])) && ($this->graphData["filters"][$type][$id][0] === "on") ){
			$this->rawSales["series_".$id]['data'][$date] = $this->rawSales["series_".$id]['data'][$date] + $quantity;
			$this->rawEarnings["series_".$id]['data'][$date] = $this->rawEarnings["series_".$id]['data'][$date] + $total;
		}		
	}
	
	protected function getRawSeriesData(){

		$this->periodArray = $this->generateEmptyPeriodArray();
		$this->setGraphSeriesArray();

		$orders = $this->getOrders();

		foreach ($orders as $order){
			$order_id    = $order->get_id();
			$order_total = $order->get_total();
			$cDate = new \DateTime($order->get_date_paid());
			$cDate = $cDate->format('Y-m-d');
				$this->setRawData("all", "orders", $cDate, 1, $order_total);
		}

		$this->rawData['sales']                = $this->rawSales;
		$this->rawData['earnings']             = $this->rawEarnings;
	}
}
?>