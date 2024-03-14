<?php
namespace InspireLabs\WoocommerceInpost\shipx\models\shipment_cost;

class ShipX_Shipment_Cost_Model
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var bool
     */
    private $error;

    /**
     * @var string
     */
    private $error_key;

    /**
     * @var string
     */
    private $error_message;

    /**
     * @var float
     */
    private $calculated_charge_amount;

    /**
     * @var float
     */
    private $fuel_charge_amount;

    /**
     * @var float
     */
    private $notification_charge_amount;

    /**
     * @var float
     */
    private $cod_charge_amount;

    /**
     * @var float
     */
    private $insurance_charge_amount;

    /**
     * @var float
     */
    private $calculated_charge_amount_non_commission;

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
     * @return float
     */
    public function getCalculatedChargeAmount()
    {
        return $this->calculated_charge_amount;
    }

    /**
     * @param float $calculated_charge_amount
     */
    public function setCalculatedChargeAmount($calculated_charge_amount)
    {
        $this->calculated_charge_amount = $calculated_charge_amount;
    }

    /**
     * @return float
     */
    public function getFuelChargeAmount()
    {
        return $this->fuel_charge_amount;
    }

    /**
     * @param float $fuel_charge_amount
     */
    public function setFuelChargeAmount($fuel_charge_amount)
    {
        $this->fuel_charge_amount = $fuel_charge_amount;
    }

    /**
     * @return float
     */
    public function getNotificationChargeAmount()
    {
        return $this->notification_charge_amount;
    }

    /**
     * @param float $notification_charge_amount
     */
    public function setNotificationChargeAmount($notification_charge_amount)
    {
        $this->notification_charge_amount = $notification_charge_amount;
    }

    /**
     * @return float
     */
    public function getCodChargeAmount()
    {
        return $this->cod_charge_amount;
    }

    /**
     * @param float $cod_charge_amount
     */
    public function setCodChargeAmount($cod_charge_amount)
    {
        $this->cod_charge_amount = $cod_charge_amount;
    }

    /**
     * @return float
     */
    public function getInsuranceChargeAmount()
    {
        return $this->insurance_charge_amount;
    }

    /**
     * @param float $insurance_charge_amount
     */
    public function setInsuranceChargeAmount($insurance_charge_amount)
    {
        $this->insurance_charge_amount = $insurance_charge_amount;
    }

    /**
     * @return float
     */
    public function getCalculatedChargeAmountNonCommission()
    {
        return $this->calculated_charge_amount_non_commission;
    }

    /**
     * @param float $calculated_charge_amount_non_commission
     */
    public function setCalculatedChargeAmountNonCommission($calculated_charge_amount_non_commission)
    {
        $this->calculated_charge_amount_non_commission = $calculated_charge_amount_non_commission;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return $this->error;
    }

    /**
     * @param bool $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getErrorKey()
    {
        return $this->error_key;
    }

    /**
     * @param string $error_key
     */
    public function setErrorKey($error_key)
    {
        $this->error_key = $error_key;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param string $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
    }
}
