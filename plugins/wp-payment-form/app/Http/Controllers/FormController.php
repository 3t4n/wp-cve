<?php

namespace WPPayForm\App\Http\Controllers;

use Exception;
use WPPayForm\App\Models\Form;
use WPPayForm\App\Services\FormPlaceholders;
use WPPayForm\App\Services\GeneralSettings;
use WPPayForm\App\Services\GlobalTools;
use WPPayForm\App\Models\Meta;

class FormController extends Controller
{
    public function index(Form $form, $formId)
    {
        try {
            return $form->getFormInfo($formId);
        } catch (Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }

    public function store(Form $form, $formId)
    {
        try {
            $builderSettings = $this->request->get('builder_settings');
            $form->saveForm($formId, $builderSettings, $this->request->get('submit_button_settings'));
            return (array(
                'message' => __('Settings successfully updated', 'wp-payment-form')
            ));
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }


    public function remove($formId)
    {
        try {
            Form::deleteForm($formId);
            return array(
                'message' => __('Selected form successfully deleted', 'wp-payment-form')
            );
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }

    public function editors($formId)
    {
        $builderSettings = Form::getBuilderSettings($formId);

        return array(
            'builder_settings' => $builderSettings,
            'components' => GeneralSettings::getComponents(),
            'form_button_settings' => Form::getButtonSettings($formId),
            'currency_settings' => Form::getCurrencyAndLocale($formId)
        );
    }


    public function saveIntegration(Meta $meta, $formId)
    {
        try {
            $insertId = $meta->saveIntegration($this->request->all(), $formId);
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }

        return [
            'message' => __('Settings has been saved.', 'wp-payment-form'),
            'settings' => json_decode($this->request->get('value'), true),
            'id' => $insertId
        ];
    }

    public function getIntegration(Meta $meta, $formId)
    {
        try {
            return $meta->getIntegration($formId);
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }
    }

    public function update(Form $form, $formId)
    {
        $request_data = $this->request->all();

        try {
            $form->updateForm($formId, $request_data);
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ], 423);
        }

        return array(
            'message' => __('Form successfully updated', 'wp-payment-form')
        );
    }

    public function designSettings($formId)
    {
        return array(
            'layout_settings' => Form::getDesignSettings($formId)
        );
    }

    public function updateDesignSettings($formId)
    {
        $layoutSettings = wp_unslash($this->request->layout_settings);
        update_post_meta($formId, 'wppayform_form_design_settings', $layoutSettings);
        return array(
            'message' => __('Settings successfully updated', 'wp-payment-form')
        );
    }

    public function settings(Form $form, $formId)
    {
        $allPages = $form->getAllPages();

        return array(
            'confirmation_settings' => Form::getConfirmationSettings($formId),
            'receipt_settings' => Form::getReceiptSettings($formId),
            'currency_settings' => Form::getCurrencySettings($formId),
            'editor_shortcodes' => FormPlaceholders::getAllPlaceholders($formId),
            'currencies' => GeneralSettings::getCurrencies(),
            'locales' => GeneralSettings::getLocales(),
            'pages' => $allPages,
            'recaptcha_settings' => GeneralSettings::getRecaptchaSettings(),
            'form_recaptcha_status' => get_post_meta($formId, '_recaptcha_status', true),
            'turnstile_settings' => GeneralSettings::getTurnstileSettings(),
            'form_turnstile_status' => get_post_meta($formId, '_turnstile_status', true),
        );
    }

    public function saveSettings(Form $form, $formId)
    {
        $request_data = $this->request->all();
        try {
            return $form->saveSettings($request_data, $formId);
        } catch (\Exception $e) {
            return $this->sendError(
                ['message' => $e->getMessage()],
                423
            );
        }
    }

    public function duplicateForm(GlobalTools $globalTools, $formId)
    {
        $oldForm = '';
        $oldForm = $globalTools->getForm($formId);
        $oldForm['post_title'] = '(Duplicate) ' . $oldForm['post_title'];
        $oldForm = apply_filters('wppayform/form_duplicate', $oldForm);

        if (!$oldForm) {
            return $this->sendError([
                'message' => __('No form found when duplicating the form', 'wp-payment-form')
            ], 423);
        }

        $newForm = $globalTools->createFormFromData($oldForm);
        return array(
            'message' => __('Form successfully duplicated', 'wp-payment-form'),
            'form' => $newForm
        );
    }

    public function export($formId)
    {
        $globalTools = new GlobalTools();
        $globalTools->exportFormJson($formId);
    }

    // get currency rates
    public function getCurrencyRates($baseCurrency, $apiKey, $cachingInterVal, $formId)
    {
        $builderSettings = Form::getBuilderSettings($formId);
        $ratesRequire = false;
        foreach ($builderSettings as $key => $value) {
            if ('donation_item' === $value['type'] ||  'currency_switcher' === $value['type']) {
                $ratesRequire = true;
            }
        }
        if ($ratesRequire) {
            $data = $this->getUpdatedCurrencyRates($baseCurrency, $cachingInterVal, $apiKey, $formId);
            return $data['rates'];
        }
        return [];
    }


    public function getUpdatedCurrencyRates($baseCurrency, $cachingInterVal, $apiKey, $formId)
    {
        $key = 'currency_convertion_from_' . $baseCurrency;
        $meta = new Meta();
        $data = $meta->getCurrencyMeta($key);
        $ratesValue = $data ? maybe_unserialize($data->meta_value) : [];

        if (!$data || empty($ratesValue)) {
            $rates = $this->getRatesFromApi($baseCurrency, $apiKey, $formId);
            $meta->updateCurrencyRates($rates, $key);
            return [
                'rates' => $rates
            ];
        }

        $updatedAt = new \DateTime($data->updated_at); // Convert $data->updated_at to a DateTime object
        // Calculate the difference in hours between the current time and $updatedAt
        $hoursDifference = (new \DateTime(current_time('mysql')))->diff($updatedAt)->h;
        // dd($hoursDifference, intval($cachingInterVal));
        if ($hoursDifference >= intval($cachingInterVal)) {
            $rates = $this->getRatesFromApi($baseCurrency, $apiKey, $formId);
            if (!empty($rates)) {
                $meta->updateCurrencyRates($rates, $key);
            }

            return [
                'rates' => $rates
            ];
        }

        return [
            'rates' => $ratesValue
        ];
    }

    public function getRatesFromApi($baseCurrency, $apiKey, $formId)
    {
        $url = 'https://api.currencyapi.com/v3/latest';
        $url = add_query_arg(array(
            'base_currency' => $baseCurrency,
            'apikey' => $apiKey,
        ), $url);

        $response = wp_remote_get($url);
        $body = wp_remote_retrieve_body($response);

        $rates = [];

        if (is_wp_error($response)) {
            return $rates;
        }
        $jsonData = json_decode($body, true);
        if (isset($jsonData['data'])) {
            $rates = $jsonData['data'];
        } else {
            do_action('wppayform_log_data', [
                'form_id' => $formId,
                'submission_id' => '',
                'type' => 'failed',
                'created_by' => 'Paymattic BOT',
                'title' => 'Currencyapi',
                'content' => $jsonData['message'] . ' - CurrecnyAPI(Check your API credentials, limitations of your current Currencyapi plan! )'
            ]);
        }
        return $rates;
    }
}
