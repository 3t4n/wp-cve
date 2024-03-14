<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

/**
 * Validation of VAT numbers.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class ValidateVatNumber
{
    const COUNTRY_ISO_SLUG = ['AT', 'BE', 'BG', 'CHE', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL', 'ES', 'EU', 'FI', 'FR', 'GB', 'GR', 'HR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'NO', 'PL', 'PT', 'RO', 'RS', 'SI', 'SK', 'SE'];
    /**
     * @param string $vat_number
     *
     * @return bool
     */
    public static function is_valid(string $vat_number) : bool
    {
        $billing_country = \WC()->customer->get_billing_country();
        // Validate VAT number only for defined countries.
        if (!\in_array($billing_country, self::COUNTRY_ISO_SLUG, \true)) {
            return \true;
        }
        return self::validate($vat_number, $billing_country);
    }
    /**
     * Return TRUE if supplied tax ID is valid for supplied country.
     *
     * @param string $vat_number       Taxation ID, e.g. ATU99999999 for Austria.
     * @param string $customer_country Country code, e.g. AT
     *
     * @return bool
     */
    public static function validate(string $raw_vat_number, string $customer_country) : bool
    {
        $vat_number = \strtoupper(\trim($raw_vat_number));
        $vat_number = \preg_replace('/[ -,.]/', '', $vat_number);
        if (\strlen($vat_number) < 8 || \strlen($vat_number) !== \strlen($raw_vat_number)) {
            return \false;
        }
        $country = \substr($vat_number, 0, 2);
        $woocommerce_default_country = \get_option('woocommerce_default_country', 0);
        if (!\in_array($customer_country, self::COUNTRY_ISO_SLUG, \true)) {
            $vat_number = $customer_country . $vat_number;
            $country = $customer_country;
        } elseif ($woocommerce_default_country === $customer_country && $country !== $customer_country) {
            $vat_number = $customer_country . $vat_number;
            $country = $customer_country;
        }
        if ($country !== $customer_country) {
            return \false;
        }
        return self::is_valid_vat_number($country, $vat_number);
    }
    /**
     * @param string $country
     * @param string $vat_number
     *
     * @return bool
     */
    public static function is_valid_vat_number(string $country, string $vat_number) : bool
    {
        //phpcs:ignore;
        switch ($country) {
            case 'AT':
                // AUSTRIA.
                $is_valid = (bool) \preg_match('/^(AT)U(\\d{8})$/', $vat_number);
                break;
            case 'BE':
                // BELGIUM.
                $is_valid = (bool) \preg_match('/(BE)(0?\\d{9})$/', $vat_number);
                break;
            case 'BG':
                // BULGARIA.
                $is_valid = (bool) \preg_match('/(BG)(\\d{9,10})$/', $vat_number);
                break;
            case 'CHE':
                // Switzerland.
                $is_valid = (bool) \preg_match('/(CHE)(\\d{9})(MWST)?$/', $vat_number);
                break;
            case 'CY':
                // CYPRUS.
                $is_valid = (bool) \preg_match('/^(CY)([0-5|9]\\d{7}[A-Z])$/', $vat_number);
                break;
            case 'CZ':
                // CZECH REPUBLIC.
                $is_valid = (bool) \preg_match('/^(CZ)(\\d{8,10})(\\d{3})?$/', $vat_number);
                break;
            case 'DE':
                // GERMANY.
                $is_valid = (bool) \preg_match('/^(DE)([1-9]\\d{8})/', $vat_number);
                break;
            case 'DK':
                // DENMARK.
                $is_valid = (bool) \preg_match('/^(DK)(\\d{8})$/', $vat_number);
                break;
            case 'EE':
                // ESTONIA.
                $is_valid = (bool) \preg_match('/^(EE)(10\\d{7})$/', $vat_number);
                break;
            case 'EL':
                // GREECE.
                $is_valid = (bool) \preg_match('/^(EL)(\\d{9})$/', $vat_number);
                break;
            case 'ES':
                // SPAIN.
                $is_valid = \preg_match('/^(ES)([A-Z]\\d{8})$/', $vat_number) || \preg_match('/^(ES)([A-H|N-S|W]\\d{7}[A-J])$/', $vat_number) || \preg_match('/^(ES)([0-9|Y|Z]\\d{7}[A-Z])$/', $vat_number) || \preg_match('/^(ES)([K|L|M|X]\\d{7}[A-Z])$/', $vat_number);
                break;
            case 'EU':
                // EU type.
                $is_valid = (bool) \preg_match('/^(EU)(\\d{9})$/', $vat_number);
                break;
            case 'FI':
                // FINLAND.
                $is_valid = (bool) \preg_match('/^(FI)(\\d{8})$/', $vat_number);
                break;
            case 'FR':
                // FRANCE.
                $is_valid = \preg_match('/^(FR)(\\d{11})$/', $vat_number) || \preg_match('/^(FR)([(A-H)|(J-N)|(P-Z)]\\d{10})$/', $vat_number) || \preg_match('/^(FR)(\\d[(A-H)|(J-N)|(P-Z)]\\d{9})$/', $vat_number) || \preg_match('/^(FR)([(A-H)|(J-N)|(P-Z)]{2}\\d{9})$/', $vat_number);
                break;
            case 'GB':
                // GREAT BRITAIN.
                $is_valid = (bool) \preg_match('/^(GB)?(\\d{9})$/', $vat_number) || \preg_match('/^(GB)?(\\d{12})$/', $vat_number) || \preg_match('/^(GB)?(GD\\d{3})$/', $vat_number) || \preg_match('/^(GB)?(HA\\d{3})$/', $vat_number);
                break;
            case 'GR':
                // GREECE.
                $is_valid = (bool) \preg_match('/^(GR)(\\d{8,9})$/', $vat_number);
                break;
            case 'HR':
                // CROATIA.
                $is_valid = (bool) \preg_match('/^(HR)(\\d{11})$/', $vat_number);
                break;
            case 'HU':
                // HUNGARY.
                $is_valid = (bool) \preg_match('/^(HU)(\\d{8})$/', $vat_number);
                break;
            case 'IE':
                // IRELAND.
                $is_valid = \preg_match('/^(IE)(\\d{7}[A-W])$/', $vat_number) || \preg_match('/^(IE)([7-9][A-Z\\*\\+)]\\d{5}[A-W])$/', $vat_number) || \preg_match('/^(IE)(\\d{7}[A-W][AH])$/', $vat_number);
                break;
            case 'IT':
                // ITALY.
                $is_valid = (bool) \preg_match('/^(IT)(\\d{11})$/', $vat_number);
                break;
            case 'LV':
                // LATVIA.
                $is_valid = (bool) \preg_match('/^(LV)(\\d{11})$/', $vat_number);
                break;
            case 'LT':
                // LITHUNIA.
                $is_valid = (bool) \preg_match('/^(LT)(\\d{9}|\\d{12})$/', $vat_number);
                break;
            case 'LU':
                // LUXEMBOURG.
                $is_valid = (bool) \preg_match('/^(LU)(\\d{8})$/', $vat_number);
                break;
            case 'MT':
                // MALTA.
                $is_valid = (bool) \preg_match('/^(MT)([1-9]\\d{7})$/', $vat_number);
                break;
            case 'NL':
                // NETHERLAND.
                $is_valid = (bool) \preg_match('/^(NL)(\\d{9})B\\d{2}$/', $vat_number);
                break;
            case 'NO':
                // NORWAY.
                $is_valid = (bool) \preg_match('/^(NO)(\\d{9})$/', $vat_number);
                break;
            case 'PL':
                // POLAND.
                $is_valid = (bool) \preg_match('/^(PL)(\\d{10})$/', $vat_number);
                break;
            case 'PT':
                // PORTUGAL.
                $is_valid = (bool) \preg_match('/^(PT)(\\d{9})$/', $vat_number);
                break;
            case 'RO':
                // ROMANIA.
                $is_valid = (bool) \preg_match('/^(RO)([1-9]\\d{1,9})$/', $vat_number);
                break;
            case 'RS':
                // SERBIA.
                $is_valid = (bool) \preg_match('/^(RS)(\\d{9})$/', $vat_number);
                break;
            case 'SI':
                // SLOVENIA.
                $is_valid = (bool) \preg_match('/^(SI)([1-9]\\d{7})$/', $vat_number);
                break;
            case 'SK':
                // SLOVAK REPUBLIC.
                $is_valid = (bool) \preg_match('/^(SK)([1-9]\\d[(2-4)|(6-9)]\\d{7})$/', $vat_number);
                break;
            case 'SE':
                // SWEDEN.
                $is_valid = (bool) \preg_match('/^(SE)(\\d{10}01)$/', $vat_number);
                break;
            default:
                $is_valid = \false;
        }
        return $is_valid;
    }
}
