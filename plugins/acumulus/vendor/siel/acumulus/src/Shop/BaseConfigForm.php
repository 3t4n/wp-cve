<?php

declare(strict_types=1);

namespace Siel\Acumulus\Shop;

use Siel\Acumulus\Config\Config;
use Siel\Acumulus\Config\Environment;
use Siel\Acumulus\Config\ShopCapabilities;
use Siel\Acumulus\Helpers\Form;
use Siel\Acumulus\Helpers\FormHelper;
use Siel\Acumulus\Helpers\Log;
use Siel\Acumulus\Helpers\Translator;
use Siel\Acumulus\ApiClient\Acumulus;

use function count;

/**
 * Provides basic config form handling.
 */
abstract class BaseConfigForm extends Form
{
    public function __construct(
        AboutForm $aboutForm,
        Acumulus $acumulusApiClient,
        FormHelper $formHelper,
        ShopCapabilities $shopCapabilities,
        Config $config,
        Environment $environment,
        Translator $translator,
        Log $log
    ) {
        parent::__construct($acumulusApiClient, $formHelper, $shopCapabilities, $config, $environment, $translator, $log);
        $this->aboutForm = $aboutForm;
        $this->translator->add(new ConfigFormTranslations());
    }

    /**
     * {@inheritdoc}
     *
     * The results are restricted to the known config keys.
     */
    protected function setSubmittedValues(): void
    {
        parent::setSubmittedValues();
        $submittedValues = $this->submittedValues;
        $this->submittedValues = [];

        foreach ($this->acumulusConfig->getKeys() as $key) {
            if (!$this->addIfIsset($this->submittedValues, $key, $submittedValues)) {
                // Add unchecked checkboxes and empty arrays, but only if they
                // were defined on the form.
                if ($this->isCheckbox($key)) {
                    $this->submittedValues[$key] = '';
                } elseif ($this->isArray($key)) {
                    $this->submittedValues[$key] = [];
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * This is the set of values as are stored in the config.
     */
    protected function getDefaultFormValues(): array
    {
        return $this->acumulusConfig->getCredentials()
               + $this->acumulusConfig->getShopSettings()
               + $this->acumulusConfig->getShopEventSettings()
               + $this->acumulusConfig->getCustomerSettings()
               + $this->acumulusConfig->getInvoiceSettings()
               + $this->acumulusConfig->getEmailAsPdfSettings()
               + $this->acumulusConfig->getInvoiceStatusSettings()
               + $this->acumulusConfig->getDocumentsSettings()
               + $this->acumulusConfig->getPluginSettings();
    }

    /**
     * {@inheritdoc}
     *
     * Saves the submitted and validated form values in the configuration store.
     */
    protected function execute(): bool
    {
        $submittedValues = $this->submittedValues;
        return $this->acumulusConfig->save($submittedValues);
    }

    /**
     * Translates and formats an account based error message.
     */
    protected function translateAccountMessage(string $message): string
    {
        if (!empty($message)) {
            $formType = $this->getType();
            $message = sprintf($this->t($message), $this->t("message_error_arg1_$formType"), $this->t("message_error_arg2_$formType"));
        }
        return $message;
    }

    /**
     * Returns a field that explains and links to the possibility to register.
     *
     * @return array[]
     *   The register field.
     */
    protected function getRegisterFields(): array
    {
        return [
            'register_text' => [
                'type' => 'markup',
                'value' => sprintf($this->t('config_form_register'), $this->t('module')),
            ],
            'register_button' => [
                'type' => 'markup',
                'value' => sprintf($this->t('config_form_register_button'), $this->shopCapabilities->getLink('register'), $this->t('button_class')),
            ],
        ];
    }

    /**
     * Creates a hidden or an option field
     * If there is only 1 option, a hidden value with a fixed value will be
     * created, an option field that gives the user th choice otherwise.
     *
     * @param string $name
     *   The name of the field.
     * @param string $type
     *   The type of the field: radio or select.
     * @param bool $required
     *   Whether the required attribute should be rendered.
     * @param array|null $options
     *   An array with value => label pairs that can be used as an option set.
     *   If null, a method on $this->shopCapabilities named after $name will be
     *   called to get the options.
     *
     * @return array
     *   A form field definition.
     */
    protected function getOptionsOrHiddenField(
        string $name,
        string $type,
        bool $required = true,
        array $options = null
    ): array {
        if ($options === null) {
            $methodName = 'get' . ucfirst($name) . 'Options';
            $options = $this->shopCapabilities->$methodName();
        }
        if (count($options) === 1) {
            // Make it a hidden field.
            $field = [
                'type' => 'hidden',
                'value' => reset($options),
            ];
        } else {
            $field = [
                'type' => $type,
                'label' => $this->t("field_$name"),
                'description' => $this->t($this->t("desc_$name")),
                'options' => $options,
                'attributes' => [
                    'required' => $required,
                ],
            ];
        }
        return $field;
    }

    /**
     * Returns an option list of all order statuses including an empty choice.
     *
     * @return array
     *   An options array of all order statuses.
     */
    protected function getOrderStatusesList(): array
    {
        $result = [];

        // Because many users won't know how to deselect a single option in a
        // multiple select element, an empty option is added.
        $result['0'] = $this->t('option_empty_triggerOrderStatus');
        $result += $this->shopCapabilities->getShopOrderStatuses();

        return $result;
    }
}
