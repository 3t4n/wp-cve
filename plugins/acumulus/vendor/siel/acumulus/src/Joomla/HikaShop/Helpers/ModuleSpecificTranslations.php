<?php
/**
 * @noinspection LongLine
 * @noinspection HtmlUnknownTarget
 */

declare(strict_types=1);

namespace Siel\Acumulus\Joomla\HikaShop\Helpers;

use Siel\Acumulus\Joomla\Helpers\ModuleSpecificTranslations as BaseModuleSpecificTranslations;

/**
 * Contains plugin specific overrides.
 *
 * @noinspection PhpUnused
 */
class ModuleSpecificTranslations extends BaseModuleSpecificTranslations
{
    public function __construct()
    {
        $this->nl += [
            'see_billing_address' => 'Verzendadres, bevat dezelfde eigenschappen als het "billing_address" object hierboven',
            'desc_triggerSettings' => 'Met behulp van deze instelling kunt u aangeven op welk(e) moment(en) u de factuur voor een bestelling naar Acumulus wilt versturen. Als u meerdere momenten selecteert, wordt de factuur naar Acumulus verstuurd zodra de bestelling één van de gekozen statussen bereikt. Een factuur zal altijd slechts 1 keer naar Acumulus worden verstuurd. Deze koppeling gebruikt alleen gegevens van de bestelling, dus u kunt elke status kiezen. De webwinkelfactuur hoeft dus nog niet aangemaakt te zijn, tenzij u voor de factuurdatum en nummer de webwinkelfactuurdatum en nummer wilt gebruiken. Als u voor "Niet automatisch versturen" kiest, dient u de facturen zelf over te zetten m.b.v. het <a href="%s">Acumulus batchverzendformulier</a>.',
            'vat_class' => 'belastingcategorie',
            'vat_classes' => 'belastingcategoriën',

            // Address used for vat calculations.
            'fiscal_address_setting' => 'Componenten » HikaShop » Configuratie » Systeem » Hoofd » Belastingen » Soort adres gebruikt voor belasting',
        ];

        $this->en += [
            'see_billing_address' => 'Shipping address, contains the same properties as the "billing_address" object above',
            'desc_triggerSettings' => 'This setting determines at what instants the invoice for an order should be sent to Acumulus. If you select multiple instants, the invoice wil be sent as soon as the order reaches one of the selected statuses. Note that an invoice will only be sent once to Acumulus. This extension only uses order data, so you may select any status, the webshop invoice does not already have to be created,unless you want to use the webshop\'s invoice date and number as invoice date and number for the Acumulus invoice. If you select "Do not send automatically" you will have to use the <a href="%s">Acumulus batch send form</a>.',
            'vat_class' => 'tax category',
            'vat_classes' => 'tax categories',

            // Address used for vat calculations.
            'fiscal_address_setting' => 'Components » HikaShop » Configuration  System » Main » Taxes » Type of address used for tax',
        ];
    }
}
