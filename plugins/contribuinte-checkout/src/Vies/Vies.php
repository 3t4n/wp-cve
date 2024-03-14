<?php

namespace Checkout\Contribuinte\Vies;

use SoapClient;

class Vies
{
    /**
     * Vies service URL
     * @var string
     */
    private $url = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * Country code
     *
     * @var string
     */
    private $countryCode;

    /**
     * Vat number
     *
     * @var string
     */
    private $vatNumber;

    /**
     * Vies constructor.
     * @param $countryCode
     * @param $vatNumber
     */
    public function __construct($countryCode, $vatNumber)
    {
        $this->countryCode = $countryCode;
        $this->vatNumber = $vatNumber;

        $this->validateVat();
    }

    /**
     * Checks VAT validity
     * @return array
     */
    public function checkVat()
    {
        try {
            $client = new SoapClient($this->url);
            $result = $client->__soapCall('checkVat', [
                [
                    'countryCode' => $this->countryCode,
                    'vatNumber' => $this->vatNumber
                ]
            ]);
        } catch (\SoapFault $exception) {
            //error validating VAT
            $result = $exception->getMessage();
        }

        return $result;
    }

    //        Privates        //

    /**
     * Removes unneeded prefix from VAT
     *
     * @return void
     */
    private function validateVat()
    {
        if (!empty($this->countryCode) && !empty($this->vatNumber)) {
            $codeLength = strlen($this->countryCode);
            $vatStart = substr($this->vatNumber, 0, $codeLength);

            if (strtoupper($vatStart) === strtoupper($this->countryCode)) {
                $this->vatNumber = substr($this->vatNumber, $codeLength);
            }
        }
    }

    //        Renders        //

    /**
     * Renders VIES information
     * @param $result
     */
    public function getViesForOrderDetailsAfterCustomerDetails($result)
    {
        //got error fetching data
        if (!isset($result->valid)) {
            ?>
            <address>
                <h4><?= __('VIES information', 'contribuinte-checkout') ?></h4>
                <?= __('Error fetching VIES  information', 'contribuinte-checkout') . ': ' . esc_html($result) ?>
                <br>
            </address>
            <?php

            return;
        }

        //the vat is not from a company
        if ((int)$result->valid !== 1) {
            return;
        }

        //show fetched company data
        ?>
        <h2><?= __('VIES information', 'contribuinte-checkout') ?></h2>
        <address>
            <?= esc_html($result->name) ?>
            <br>
            <?= esc_html($result->address) ?>
            <br>
            <?= esc_html(strtoupper($result->countryCode)) . esc_html($result->vatNumber) ?>
        </address>
        <?php
    }

    /**
     * Renders VIES information
     * @param $result
     */
    public function getViesForAdminOrderDataAfterBillingAddress($result)
    {
        //got error fetching data
        if (!isset($result->valid)) {
            ?>
            <div class="order_data_column" style="width: 100%;">
                <h4><?= __('VIES information', 'contribuinte-checkout') ?></h4>
                <p><?= __('Error fetching VIES  information', 'contribuinte-checkout') . ': ' . esc_html($result) ?></p>
            </div>
            <?php

            return;
        }

        //the vat is not from a company
        if ((int)$result->valid !== 1) {
            return;
        }

        //show fetched company data
        ?>
        <div class="order_data_column" style="width: 100%;">
            <h3><?= __('VIES information', 'contribuinte-checkout') ?></h3>
            <div class="vies-information">
                <p class="form-field form-field-wide"><?= esc_html($result->name) ?></p>
                <p class="form-field form-field-wide"><?= esc_html($result->address) ?></p>
                <p class="form-field form-field-wide"><?= esc_html(strtoupper($result->countryCode)) . esc_html($result->vatNumber) ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Renders VIES information
     * @param $result
     */
    public function getViesForAfterEditAccountAddressForm($result)
    {
        //got error fetching data
        if (!isset($result->valid)) {
            ?>
            <div class="order_data_column" style="width: 100%;">
                <h3><?= __('VIES information', 'contribuinte-checkout') ?></h3>
                <p><?= __('Error fetching VIES  information', 'contribuinte-checkout') . ': ' . esc_html($result) ?></p>
            </div>
            <?php

            return;
        }

        //the vat is not from a company
        if ((int)$result->valid !== 1) {
            return;
        }

        //Show fetched company data
        ?>
        <div class="vies-information">
            <h3><?= __('VIES information', 'contribuinte-checkout') ?></h3>
            <address>
                <?= esc_html($result->name) ?>
                <br>
                <?= esc_html($result->address) ?>
                <br>
                <?= esc_html(strtoupper($result->countryCode)) . esc_html($result->vatNumber) ?>
            </address>
        </div>
        <?php
    }
}