<?php

namespace Premmerce\WoocommerceMulticurrency\Admin\RatesUpdate;

use Premmerce\WoocommerceMulticurrency\Model\Model;

class RatesUpdateController
{
    const UPDATERS_DIR = __DIR__ . '/Updaters';

    /**
     * @var AbstractRatesUpdater[] All available rates updaters classes. Each class represents one API service.
     */
    private $availableRatesUpdaters = array();

    /**
     * @var Model
     */
    private $model;

    /**
     * @var string
     */
    private $mainCurrencyCode;

    /**
     * @var string
     */
    private $updatersNamespace = 'Premmerce\WoocommerceMulticurrency\Admin\RatesUpdate\Updaters\\';

    /**
     * RatesUpdateController constructor.
     *
     * @param Model $model
     *
     *
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->mainCurrencyCode = $this->model->getMainCurrency()['code'];

        add_action('plugins_loaded', array($this, 'loadUpdaters'));

        add_action('shutdown', array($this, 'saveUpdatersData'));
    }

    /**
     * Save all updaters limits before die
     */
    public function saveUpdatersData()
    {
        foreach ($this->availableRatesUpdaters as $updater) {
            $updater->saveRequestsLimit();
        }
    }

    /**
     * Get new rates and update DB
     *
     * @return array;
     */
    public function updateRates()
    {
        do_action('premmerce_multicurrency_before_update_rates');

        $currenciesToUpdate = $this->model->getCurrenciesToUpdateRates();
        $response = $this->getRates($currenciesToUpdate);
        $this->model->setNewRates($response ['data']);

        do_action('premmerce_multicurrency_after_update_rates', $response);

        return $response['message'];
    }

    /**
     * Return array with all available price updaters and their data
     *
     * @return AbstractRatesUpdater[]
     */
    public function getAvailableUpdaters()
    {
        return $this->availableRatesUpdaters;
    }

    /**
     * Return array with rates for $currencies.
     *
     * @param  array $currencies Ids is keys, codes is values.
     *
     * @return array
     */
    public function getRates($currencies)
    {
        $ratesByIds = array();

        //Do this to prevent duplicate requests for currencies with same code and same updater
        $currenciesByUpdaters = $this->sortCurrenciesByUpdaters($currencies);
        $notUpdatedCurrencies = array();


        foreach ($currenciesByUpdaters as $updaterName => $updaterCurrencies) {
            if (!$updater = $this->getUpdaterById($updaterName)) {
                $notUpdatedCurrencies += array_values($updaterCurrencies);
                continue;
            }

            $currenciesCodes = array_unique(array_values($updaterCurrencies));
            $ratesByCodes = $updater->getRates($currenciesCodes);

            foreach ($updaterCurrencies as $currencyId => $currencyCode) {
                $ratesByIds[$currencyId] = $ratesByCodes[$currencyCode];
            }
        }

        $message = $this->prepareResultMessage($notUpdatedCurrencies);

        $ratesByIds = apply_filters('premmerce_multicurrency_new_rates', $ratesByIds);

        return array('data' => $ratesByIds, 'message' => $message);
    }

    /**
     * Return exchange rate for $currencyCode using $updaterId
     *
     * @param $currencyCode
     * @param $updaterId
     *
     * @return float
     */
    public function getCurrencyRate($currencyCode, $updaterId)
    {
        $rate = 0;

        if ($updater = $this->getUpdaterById($updaterId)) {
            $rates = $updater->getRates(array($currencyCode));
            $rate = isset($rates[$currencyCode]) ? $rates[$currencyCode] : 0;
        }

        return (float)$rate;
    }

    /**
     * @param $id
     *
     * @return AbstractRatesUpdater
     */
    public function getUpdaterById($id)
    {
        foreach ($this->availableRatesUpdaters as $updaterInstance) {
            if ($id === $updaterInstance->getId()) {
                return $updaterInstance;
            }
        }
    }

    /**
     * Delete currencies cache of each rates updater service
     */
    public function cleanAllCurrenciesCaches()
    {
        foreach ($this->availableRatesUpdaters as $updater) {
            $updater->cleanCurrenciesCache();
        }
    }

    /**
     * Save rates updater options.
     *
     * @return array
     */
    public function saveUpdaterOptions()
    {
        $updaterId = isset($_POST['updater_id']) ? sanitize_text_field(wp_unslash($_POST['updater_id'])) : '';
        $updaterInstance = $this->getUpdaterById($updaterId);

        if ($updaterInstance) {
            $updated = $updaterInstance->process_admin_options();
            if ($updated) {
                $updaterInstance->cleanCurrenciesCache();
            }
            do_action('premmerce_multicurrency_updater_options_saved', $updaterInstance, $updated);
            $result['message'] = __('Settings was successfully saved', 'premmerce-woocommerce-multicurrency');
            $result['type'] = 'success';
        } else {
            $result['message'] = __(
                'You are trying to save updaters options, but such updater not found. Data wasn\'t saved.',
                'premmerce-woocommerce-multicurrency'
            );
            $result['type'] = 'error';
        }

        return $result;
    }

    /**
     * Return possible updaters ids for given currency.
     *
     * @param string $currencyCode
     *
     * @return string[]
     */
    public function getUpdatersForCurrency($currencyCode)
    {
        $updatersIds = array();

        foreach ($this->getAvailableUpdaters() as $updaterInstance) {
            $availableCurrencies = $updaterInstance->getAvailableCurrenciesList();

            if (in_array($currencyCode, $availableCurrencies)) {
                $updatersIds[] = $updaterInstance->getId();
            }
        }

        return $updatersIds;
    }

    /**
     * Check all services statuses
     *
     * bool[]
     */
    public function checkUpdatersStatuses()
    {
        $statuses = array();

        foreach ($this->availableRatesUpdaters as $updaterInstance) {
            $statuses[$updaterInstance->getId()] = $updaterInstance->isAlive();
        }

        return $statuses;
    }

    /**
     * Create multidimensional array with currencies sorted by assigned services ids.
     *
     * @param $currencies
     *
     * @return array
     */
    private function sortCurrenciesByUpdaters($currencies)
    {
        $byUpdaters = array();
        foreach ($currencies as $currencyId => $currencyCode) {
            $updaterId = $this->model->getUpdaterIdForCurrency($currencyId);
            if ($updaterId) {
                $byUpdaters[$updaterId][$currencyId] = $currencyCode;
            }
        }

        return $byUpdaters;
    }

    /**
     * Load all updaters classes
     *
     */
    public function loadUpdaters()
    {
        $updatersClassesNames = array(
            $this->updatersNamespace . 'CurrencylayerUpdater',
            $this->updatersNamespace . 'FreeCurrencyConverterUpdater'
        );

        //Plugins can add own updaters classes. Updater class must extend AbstractRatesUpdater class. See RatesUpdaterInterface for more info.
        $updatersClassesNames = apply_filters('premmerce_multicurrency_updaters_classes', $updatersClassesNames);

        $updaters = array();
        foreach ($updatersClassesNames as $updaterClassName) {
            if (!$this->updaterValid($updaterClassName)) {
                continue;
            }

            $updaterInstance = new $updaterClassName($this->mainCurrencyCode);
            $updaters[$updaterClassName] = $updaterInstance;
        }

        $this->availableRatesUpdaters = $updaters;

        do_action('premmerce_multicurrency_updaters_loaded', $this);
    }

    /**
     * Check if updater class exists, can be loaded and implements updaters interface.
     *
     * @param $className
     *
     * @return bool
     */
    private function updaterValid($className)
    {
        $result = false;


        if (class_exists($className) && is_subclass_of($className, AbstractRatesUpdater::class)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get message to display after update rates
     *
     * @param $currenciesUpdateFailed
     * @return array
     */
    private function prepareResultMessage($currenciesUpdateFailed)
    {
        if ($failedCount = count($currenciesUpdateFailed)) {
            $placeholders = array_fill(0, $failedCount, '%s');
            $placeholders = implode(', ', $placeholders);
            $message['message'] = sprintf(
                __("Can not find updater selected for currencies {$placeholders} . Please, set another updater on currency edit page or fix chosen to update this currency exchange rate."),
                implode(',', $currenciesUpdateFailed)
            );
            $message['type'] = 'error';
        } elseif (count($this->model->getCurrencies()) < 2) {
            $message = array(
                'message' => __('You have no additional currencies to update rates.', 'premmerce-woocommerce-multicurrency'),
                'type'    => 'warning'
            );
        } else {
            $message = array(
                'message' => __('Currencies rates was updated.', 'premmerce-woocommerce-multicurrency'),
                'type' => 'success'
            );
        }

        return $message;
    }
}
