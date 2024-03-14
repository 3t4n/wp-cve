<?php

/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Shipping;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\BaseParcelPacker')):

class BaseParcelPacker
{
	protected $id;
	protected $logger;
	protected $boxes;
	protected $combineBoxes;
	protected $minLength;
	protected $minWidth;
	protected $minHeight;
	protected $minWeight;
	protected $weightAdjustmentPercent;
	protected $weightAdjustment;
	protected $weightUnit;
	protected $dimensionUnit;

	protected $useCubeDimensions;
	protected $defaultPackageType;
	protected $packageTypes;
	protected $itemParents;

	protected $parcels;

	public function __construct($id)
	{
		$this->id = $id;
		$this->boxes = array();
		$this->combineBoxes = false;
		$this->minLength = 0;
		$this->minWidth = 0;
		$this->minHeight = 0;
		$this->minWeight = 0;
		$this->weightAdjustmentPercent = 0;
		$this->weightAdjustment = 0;
		$this->weightUnit = get_option('woocommerce_weight_unit');
		$this->dimensionUnit = get_option('woocommerce_dimension_unit');

		$this->useCubeDimensions = false;
		$this->packageTypes = array();
		$this->defaultPackageType = 'parcel';
		$this->itemParents = array();
		$this->parcels = array();

		$this->logger = &\OneTeamSoftware\WooCommerce\Logger\LoggerInstance::getInstance($this->id);
	}

	public function setSettings(array $settings)
	{
		foreach ($settings as $key => $val) {
			if ($key == 'boxes') {
				$this->setBoxes($val);
			} else if (property_exists($this, $key)) {
				if ($val == 'yes') {
					$this->$key = true;
				} else if ($val == 'no') {
					$this->$key = false;
				} else if (is_bool($this->$key)) {
					$this->$key = filter_var($val, FILTER_VALIDATE_BOOLEAN);
				} else if (is_numeric($this->$key)) {
					$this->$key = $this->toNumber($val);
				} else {
					$this->$key = $val;
				}
			} else if ($key == 'weight_unit') {
				$this->weightUnit = $val;
			} else if ($key == 'dimension_unit') {
				$this->dimensionUnit = $val;
			}
		}
	}

	public function setPackageTypes(array $packageTypes)
	{
		$this->packageTypes = $packageTypes;

		if (!empty($this->packageTypes)) {
			$this->defaultPackageType = current(array_keys($this->packageTypes));
		} else {
			$this->defaultPackageType = 'parcel';
		}
	}

	public function pack(array $packageContents)
	{
		$this->logger->debug(__FILE__, __LINE__, "pack");

		$this->itemParents = array();
		$this->parcels = array();

		$this->logger->debug(__FILE__, __LINE__, "Boxes: " . print_r($this->boxes, true));

		$this->startPacking();

		foreach ($packageContents as $itemId => $item) {
			$product = $item['data'];

			if (!is_object($product)) {
				$this->logger->debug(__FILE__, __LINE__, "Item is not an object, so skip it");

				continue;
			} else if (!$product->needs_shipping()) {
				$this->logger->debug(__FILE__, __LINE__, "Product does not need to be shipped, so skip it. Product id: " . $product->get_id() . ", type: " . $product->get_type() . ", name: " . $product->get_name());

				continue;
			} else if (isset($this->itemParents[$itemId])) {
				$this->logger->debug(__FILE__, __LINE__, "Product is a child of another product, so skip it. Product id: " . $product->get_id() . ", type: " . $product->get_type() . ", name: " . $product->get_name());

				continue;
			}

			$quantity = floatval($item['quantity']);

			$this->logger->debug(__FILE__, __LINE__, "Pack product #" . $product->get_id() . ", qty: " . $quantity);

			$this->setItemParents($item);
			
			for ($itemIdx = 0; $itemIdx < $quantity; ++$itemIdx) {
				if (!$this->maybePackProduct($product)) {
					--$itemIdx;
				}
			}
		}

		$this->finishPacking();

		return $this->adjustParcels($this->parcels);
	}

	protected function setBoxes($boxes)
	{
	}

	protected function maybePackProduct($product)
	{
		$item = $this->toParcelItem($product);
		if (empty($item)) {
			return false;
		}

		$this->packSingleItem($item);

		return true;
	}

	protected function packSingleItem(array $item)
	{
		$this->logger->debug(__FILE__, __LINE__, "Pack single item as a parcel");

		$parcel = array();
		$copyKeys = array('width', 'height', 'length', 'weight', 'volume', 'value');
		foreach ($copyKeys as $key) {
			if (isset($item[$key])) {
				$parcel[$key] = $item[$key];
			}
		}

		$parcel['boxName'] = 'Product';
		$parcel['type'] = $this->defaultPackageType;
		$parcel['items'] = array($item['id'] => $item);

		$this->addParcel($parcel);
	}

	protected function toParcelItem($product)
	{
		$this->logger->debug(__FILE__, __LINE__, "toParcelItem");

		if (!is_object($product)) {
			$this->logger->debug(__FILE__, __LINE__, "Invalid product");

			return array();
		}

		// we try to fallback to meta values, because some evil plugins can overwrite this info and we need it
		$productId = $product->get_id();

		$item = array();
		$item['id'] = $productId;
		$item['productType'] = $product->get_type();
		$item['name'] = $product->get_name();
		$item['sku'] = $product->get_sku();
		$item['value'] = $this->toNumber($product->get_price());

		$item['length'] = $this->toNumber($product->get_length());
		if (empty($item['length'])) {
			$item['length'] = $this->toNumber(get_post_meta($productId, '_length', true));
		}

		$item['width'] = $this->toNumber($product->get_width());
		if (empty($item['width'])) {
			$item['width'] = $this->toNumber(get_post_meta($productId, '_width', true));
		}

		$item['height'] = $this->toNumber($product->get_height());
		if (empty($item['height'])) {
			$item['height'] = $this->toNumber(get_post_meta($productId, '_height', true));
		}

		$item['weight'] = $this->toNumber($product->get_weight());
		if (empty($item['weight'])) {
			$item['weight'] = $this->toNumber(get_post_meta($productId, '_weight', true));
		}

		$item['volume'] = $this->getVolume($item);
		$item['quantity'] = 1;

		$this->logger->debug(__FILE__, __LINE__, "Item: " . print_r($item, true));

		return $item;
	}

	protected function adjustParcels(array $parcels)
	{
		$this->logger->debug(__FILE__, __LINE__, "Adjust Parcels: " . print_r($parcels, true));

		$parcels = $this->getCombinedParcels($parcels);

		foreach ($parcels as $key => $parcel) {
			$parcel = $this->adjustParcel($parcel);
			$parcel = $this->getCubeParcel($parcel);
			$parcel = $this->addFreightClass($parcel);
			$parcel = $this->fixParcelItems($parcel);

			$parcels[$key] = $parcel;
		}

		$this->logger->debug(__FILE__, __LINE__, "Adjusted parcels: " . print_r($parcels, true));

		return $parcels;
	}

	protected function adjustParcel(array $parcel)
	{
		$this->logger->debug(__FILE__, __LINE__, "Adjust Parcel: " . print_r($parcel, true));

		$parcel['weight'] = $this->adjustWeight($parcel['weight']);

		$numberOfItems = 0;
		foreach ($parcel['items'] as $item) {
			$numberOfItems += $item['quantity'];
		}

		foreach ($parcel['items'] as $key => $item) {
			$item['weight'] = $this->adjustWeight($item['weight'], $numberOfItems);
			$parcel['items'][$key] = $item;
		}

		if ($parcel['length'] < $this->minLength) {
			$parcel['length'] = $this->minLength;

			$this->logger->debug(__FILE__, __LINE__, "Set min length to: " . $parcel['length']);
		}

		if ($parcel['width'] < $this->minWidth) {
			$parcel['width'] = $this->minWidth;

			$this->logger->debug(__FILE__, __LINE__, "Set min width to: " . $parcel['width']);
		}

		if ($parcel['height'] < $this->minHeight) {
			$parcel['height'] = $this->minHeight;

			$this->logger->debug(__FILE__, __LINE__, "Set min height to: " . $parcel['height']);
		}

		if ($parcel['weight'] < $this->minWeight) {
			$parcel['weight'] = $this->minWeight;

			$this->logger->debug(__FILE__, __LINE__, "Set min weight to: " . $parcel['weight']);
		}
	
		$parcel['weight'] = round($parcel['weight'], 2);
		$parcel['length'] = round($parcel['length'], 2);
		$parcel['height'] = round($parcel['height'], 2);
		$parcel['width'] = round($parcel['width'], 2);
		$parcel['volume'] = $this->getVolume($parcel);
		$parcel['value'] = round($parcel['value'], 2);

		return $parcel;
	}

	protected function addFreightClass(array $parcel)
	{
		$this->logger->debug(__FILE__, __LINE__, "addFreightClass");

		$parcel['freightClass'] = $this->getFreightClass($parcel);
		
		return $parcel;
	}

	protected function getFreightClass(array $parcel)
	{
		return 150;
	}

	protected function adjustWeight($weight, $numberOfItems = 1)
	{
		$this->logger->debug(__FILE__, __LINE__, 'Adjust weight: ' . $weight . ', number of items: ' . $numberOfItems);

		if ($this->weightAdjustmentPercent > 0) {
			$this->logger->debug(__FILE__, __LINE__, 'Weight multiplier: ' . $this->weightAdjustmentPercent);

			$weight *= $this->weightAdjustmentPercent;
		}

		if (!empty($this->weightAdjustment) && $numberOfItems > 0) {
			$this->logger->debug(__FILE__, __LINE__, 'Fixed weight adjustment: ' . $this->weightAdjustment);

			$weight += $this->weightAdjustment / $numberOfItems;
		}

		$this->logger->debug(__FILE__, __LINE__, 'New weight: ' . $weight);

		return $weight;
	}

	protected function getCombinedParcels(array $parcels)
	{
		if (!$this->combineBoxes || count($parcels) <= 1) {
			return $parcels;
		}

		$this->logger->debug(__FILE__, __LINE__, "Combine Parcels");

		$newParcel = array(
			'combined' => true,
			'boxName' => 'Combined Parcel',
			'type' => $this->defaultPackageType,
			'length' => 0,
			'width' => 0,
			'height' => 0,
			'weight' => 0,
			'value' => 0,
			'volume' => 0,
			'items' => array(),
		);

		foreach ($parcels as $parcel) {
			if (($parcel['width'] + $parcel['length']) > ($newParcel['width'] + $newParcel['length'])) {
				$newParcel['width'] = $parcel['width'];
				$newParcel['length'] = $parcel['length'];
			}
			$newParcel['height'] += $parcel['height'];
			$newParcel['weight'] += $parcel['weight'];
			$newParcel['value'] += $parcel['value'];
			$newParcel['volume'] += $parcel['volume'];
			$newParcel['items'] = $this->mergeItems($newParcel['items'], $parcel['items']);
		}

		$this->logger->debug(__FILE__, __LINE__, "new combined parcel: " . print_r($newParcel, true));

		return array($newParcel);
	}

	protected function fixParcelItems(array $parcel)
	{
		if (empty($parcel['items']) || empty($parcel['weight'])) {
			return $parcel;
		}

		$this->logger->debug(__FILE__, __LINE__, "fixParcelItems");

		$items = &$parcel['items'];
		$avgWeightPerItem = round($parcel['weight'] / count($items), 2);

		foreach ($items as $idx => $item) {
			if (empty($item['weight']) && !empty($item['quantity'])) {
				$item['weight'] = $avgWeightPerItem / $item['quantity'];
				$items[$idx] = $item;
			}
		}

		return $parcel;
	}

	protected function getCubeParcel(array $parcel)
	{
		if (!$this->useCubeDimensions || empty($parcel['combined'])) {
			return $parcel;
		}

		$this->logger->debug(__FILE__, __LINE__, "getCubeParcel");

		$cubeSideLength = round(pow($parcel['volume'], 1/3), 2);
		$parcel['length'] = $cubeSideLength;
		$parcel['width'] = $cubeSideLength;
		$parcel['height'] = $cubeSideLength;

		return $parcel;
	}

	protected function mergeItems($items1, $items2)
	{
		foreach ($items2 as $itemId => $item) {
			if (isset($items1[$itemId])) {
				$items1[$itemId]['quantity'] += $item['quantity'];
			} else {
				$items1[$itemId] = $item;
			}
		}

		return $items1;
	}

	protected function startPacking()
	{
		$this->logger->debug(__FILE__, __LINE__, 'Start packing a new parcel');
	}

	protected function finishPacking()
	{
		$this->logger->debug(__FILE__, __LINE__, 'Finish packing current parcel');
	}

	protected function addParcel(array $parcel)
	{
		$this->logger->debug(__FILE__, __LINE__, 'Add parcel to the pile');

		if (isset($parcel['length']) &&
			isset($parcel['width']) &&
			isset($parcel['height']) &&
			isset($parcel['weight'])) {

			$this->parcels[] = $parcel;

			$this->logger->debug(__FILE__, __LINE__, "Added parcel: " . print_r($parcel, true));
		} else {
			$this->logger->debug(__FILE__, __LINE__, "Unable to add parcel: " . print_r($parcel, true));
		}
	}

	protected function toNumber($value)
	{
		$number = 0;
		$value = preg_replace('/[^\d\.]/i', '', $value);
		if (is_numeric($value)) {
			$number = floatval($value);
		}

		//$this->logger->debug(__FILE__, __LINE__, "value: $value -> $number");

		return round($number, 2);
	}

	protected function getVolume(array $item)
	{
		return $item['width'] * $item['height'] * $item['length'];
	}

	protected function getChildrenItemIds($item)
	{
		$childrenItemIds = array();

		if (!empty($item['bundled_items']) && is_array($item['bundled_items'])) {
			$childrenItemIds = $item['bundled_items'];
		} else if (!empty($item['composite_children']) && is_array($item['composite_children'])) {
			$childrenItemIds = $item['composite_children'];
		}

		return $childrenItemIds;
	}

	protected function setItemParents($item)
	{
		$childrenItemIds = $this->getChildrenItemIds($item);
		if (!empty($childrenItemIds)) {
			$this->logger->debug(__FILE__, __LINE__, "Found a product with children");

			foreach ($childrenItemIds as $childItemId) {
				$this->itemParents[$childItemId] = $itemId;
			}
		}
	}
}

endif;
