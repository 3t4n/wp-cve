<?php

declare(strict_types=1);

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

class ShippingServiceDelayTranslationService
{
    private const SEA_SERVICE = 'Sea service:';
    private const DAYS = 'days';
    private const DELIVERY = 'Delivery';

    protected $delayTranslations;

    public function __construct()
    {
        $this->delayTranslations = [
            'en' => [
                self::SEA_SERVICE => self::SEA_SERVICE,
                self::DAYS => self::DAYS,
                self::DELIVERY => self::DELIVERY,
            ],
            'es' => [
                self::SEA_SERVICE => 'Servicio marítimo:',
                self::DAYS => 'días',
                self::DELIVERY => 'Entrega',
            ],
            'fr' => [
                self::SEA_SERVICE => 'Service maritime:',
                self::DAYS => 'jours',
                self::DELIVERY => 'Livraison',
            ],
            'de' => [
                self::SEA_SERVICE => 'Seetransport:',
                self::DAYS => 'Tage',
                self::DELIVERY => 'Lieferung',
            ],
            'pt' => [
                self::SEA_SERVICE => 'Serviço marítimo:',
                self::DAYS => 'dias',
                self::DELIVERY => 'Entrega',
            ],
            'el' => [
                self::SEA_SERVICE => 'Υπηρεσία θαλάσσης:',
                self::DAYS => 'ημέρες',
                self::DELIVERY => 'Παράδοση',
            ],
            'hr' => [
                self::SEA_SERVICE => 'Morskim putem:',
                self::DAYS => 'dana',
                self::DELIVERY => 'dostava',
            ],
            'it' => [
                self::SEA_SERVICE => 'Servizio marittimo:',
                self::DAYS => 'giorni',
                self::DELIVERY => 'Consegna',
            ],
            'et' => [
                self::SEA_SERVICE => 'Mereteenus:',
                self::DAYS => 'päeva',
                self::DELIVERY => 'Kohaletoimetamine',
            ],
            'da' => [
                self::SEA_SERVICE => 'Søtransport:',
                self::DAYS => 'dage',
                self::DELIVERY => 'Levering',
            ],
            'fi' => [
                self::SEA_SERVICE => 'Merirahti:',
                self::DAYS => 'päivää',
                self::DELIVERY => 'Toimitus',
            ],
            'ro' => [
                self::SEA_SERVICE => 'Serviciu maritim:',
                self::DAYS => 'zile',
                self::DELIVERY => 'Livrare',
            ],
            'bg' => [
                self::SEA_SERVICE => 'Услуга по море:',
                self::DAYS => 'дни',
                self::DELIVERY => 'Доставка',
            ],
            'hu' => [
                self::SEA_SERVICE => 'Tengeri szállítmányozás:',
                self::DAYS => 'nap',
                self::DELIVERY => 'Szállítás',
            ],
            'sk' => [
                self::SEA_SERVICE => 'Lodná doprava:',
                self::DAYS => 'dni',
                self::DELIVERY => 'Doručenie',
            ],
            'si' => [
                self::SEA_SERVICE => 'Prevoz po morju:',
                self::DAYS => 'dni',
                self::DELIVERY => 'Dostava',
            ],
            'lt' => [
                self::SEA_SERVICE => 'Jūros transporto paslaugos:',
                self::DAYS => 'dienos',
                self::DELIVERY => 'Pristatymas',
            ],
            'lv' => [
                self::SEA_SERVICE => 'Jūras transports:',
                self::DAYS => 'dienas',
                self::DELIVERY => 'Pristatymas',
            ],
            'pl' => [
                self::SEA_SERVICE => 'Spedycja morska:',
                self::DAYS => 'dni',
                self::DELIVERY => 'Dostawa',
            ],
            'nl' => [
                self::SEA_SERVICE => 'Overzee dienst:',
                self::DAYS => 'dagen',
                self::DELIVERY => 'Levering',
            ],
            'ru' => [
                self::SEA_SERVICE => 'Морская служба:',
                self::DAYS => 'дня',
                self::DELIVERY => 'Доставка',
            ],
            'no' => [
                self::SEA_SERVICE => 'Sjø service:',
                self::DAYS => 'dager',
                self::DELIVERY => 'Leveranse',
            ],
            'sv' => [
                self::SEA_SERVICE => 'Sjöfrakt:',
                self::DAYS => 'dagar',
                self::DELIVERY => 'Afhendingu',
            ],
            'cs' => [
                self::SEA_SERVICE => 'Po moři:',
                self::DAYS => 'dny',
                self::DELIVERY => 'Dodávka',
            ],
        ];
    }

    /**
     * @param string $delay
     * @return string
     */
    public function getDelayTranslationFromIsoCode(string $delay): string
    {
        $woocommerceDefaultCountryIsoCode = explode('_', get_locale());
        $defaultCountryIsoCode = \strtolower($woocommerceDefaultCountryIsoCode[0]);

        if (!array_key_exists($defaultCountryIsoCode, $this->delayTranslations)) {
            return $delay;
        }

        $delayReplaced = str_replace(
            [self::SEA_SERVICE, self::DAYS],
            [
                $this->delayTranslations[$defaultCountryIsoCode][self::SEA_SERVICE],
                $this->delayTranslations[$defaultCountryIsoCode][self::DAYS],
            ],
            $delay
        );

        if (strpos(self::DELIVERY, $delay) !== false || strpos($this->delayTranslations[$defaultCountryIsoCode][self::DELIVERY], $delay) !== false) {
            return $delayReplaced;
        }

        return $this->delayTranslations[$defaultCountryIsoCode][self::DELIVERY] . ' '.$delayReplaced;
    }
}