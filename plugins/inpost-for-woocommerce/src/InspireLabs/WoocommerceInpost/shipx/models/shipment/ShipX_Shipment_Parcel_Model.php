<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

class ShipX_Shipment_Parcel_Model
{
    /**
     * 8 x 38 x 64 cm	up to 25 kg Size A
     */
    const SIZE_TEMPLATE_SMALL = 'small';

    /**
     * 19 x 38 x 64 cm	up to 25 kg Size B
     */
    const SIZE_TEMPLATE_MEDIUM = 'medium';

    /**
     * 41 x 38 x 64 cm	up to 25 kg Size C
     */
    const SIZE_TEMPLATE_LARGE = 'large';

    /**
     * 50 x 50 x 80 cm	up to 25 kg Size D
     */
    const SIZE_TEMPLATE_XLARGE = 'xlarge';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $template;

    /**
     * @var ShipX_Shipment_Parcel_Dimensions_Model
     */
    private $dimensions;

    /**
     * @var ShipX_Shipment_Parcel_Weight_Model
     */
    private $weight;

    /**
     * @var string
     */
    private $tracking_number;

    /**
     * @var bool
     */
    private $is_non_standard;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return ShipX_Shipment_Parcel_Dimensions_Model
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * @param ShipX_Shipment_Parcel_Dimensions_Model $dimensions
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;
    }

    /**
     * @return ShipX_Shipment_Parcel_Weight_Model
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param ShipX_Shipment_Parcel_Weight_Model $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->tracking_number;
    }

    /**
     * @param string $tracking_number
     */
    public function setTrackingNumber($tracking_number)
    {
        $this->tracking_number = $tracking_number;
    }

    /**
     * @return bool
     */
    public function is_non_standard()
    {
        return $this->is_non_standard;
    }

    /**
     * @param bool $is_non_standard
     */
    public function setIsNonstandard($is_non_standard)
    {
        $this->is_non_standard = $is_non_standard;
    }
}
