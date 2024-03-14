<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class CountriesHelper extends SearchDropdownProviderHelper
{
	public function __construct($provider = null)
	{
		$this->class_name = 'Countries';

		parent::__construct($provider);
	}

	/**
	 * Parses given data to a key,value array
	 * 
	 * @param   array  $items
	 * 
	 * @return  array
	 */
	public static function parseData($items)
	{
		$items = (array) $items;
		$items = array_filter($items);

		if (empty($items))
		{
			return [];
		}

		foreach ($items as $key => $value)
		{
			$data[] = [
				'id' => $key,
				'title' => $value
			];
		}
		
		return $data;
	}
	
    /**
     * Return a country's code from it's name
     *
     * @param   string  $country
	 * 
     * @return  mixed
     */
    public static function getCode($country)
    {
		if (!is_string($country) || empty(trim($country)))
		{
			return null;
		}
		
		$country = \ucwords(strtolower(trim($country)));

        foreach (self::getCountriesData() as $key => $value)
        {
            if (strpos($value['name'], $country) !== false)
            {
                return $value['code'];
            }
		}
		
        return null;
	}
	
    /**
     * Return a country's name from it's code
     *
     * @param   string  $code
	 * 
     * @return  mixed
     */
    public static function getCountryName($code)
    {
		if (!is_string($code) || empty(trim($code)))
		{
			return null;
		}
		
		$code = strtoupper(trim($code));

        foreach (self::getCountriesData() as $key => $country)
        {
            if ($code == $country['code'])
            {
                return $country['name'];
            }
		}
		
        return null;
	}

    /**
     *  Return the country code from the dial code
     *
     *  @param   string $dial_code
	 * 
     *  @return  string|void
     */
    public static function getCountryCodeByDialCode($dial_code)
    {
        foreach (self::getCountriesData() as $key => $country)
        {
            if ($dial_code == $country['calling_code'])
            {
                return $country['code'];
            }
        }
        return null;
	}
	
	/**
	 * Returns translatable countries list
	 * 
	 * @return  array
	 */
	public static function getCountriesList()
	{
		$countries = [];

		foreach (self::getCountriesData() as $key => $country)
        {
			$countries[$country['code']] = $country['name'];
		}

		return $countries;
	}

	/**
	 * Holds the following data for each country:
	 * - Name
	 * - Code
	 * - Calling Code
	 * - Currency Code
	 * - Curency Name
	 * - Currency Symbol
	 * 
	 * @return  array
	 */
	public static function getCountriesData()
	{
		return [
			[ 'name' => fpframework()->_('FPF_COUNTRY_AF'), 'code' => 'AF', 'calling_code' => '93', 'currency_code' => 'AFN', 'currency_name' => 'Afghan Afghani', 'currency_symbol' => '؋' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AX'), 'code' => 'AX', 'calling_code' => '35818', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AL'), 'code' => 'AL', 'calling_code' => '355', 'currency_code' => 'ALL', 'currency_name' => 'Lek', 'currency_symbol' => 'Lek' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_DZ'), 'code' => 'DZ', 'calling_code' => '213', 'currency_code' => 'DZD', 'currency_name' => 'Dinar', 'currency_symbol' => 'دج' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AS'), 'code' => 'AS', 'calling_code' => '1684', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AD'), 'code' => 'AD', 'calling_code' => '376', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AO'), 'code' => 'AO', 'calling_code' => '244', 'currency_code' => 'AOA', 'currency_name' => 'Kwanza', 'currency_symbol' => 'Kz' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AI'), 'code' => 'AI', 'calling_code' => '1264', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AQ'), 'code' => 'AQ', 'calling_code' => '672', 'currency_code' => '', 'currency_name' => '', 'currency_symbol' => '' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AG'), 'code' => 'AG', 'calling_code' => '1268', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AR'), 'code' => 'AR', 'calling_code' => '54', 'currency_code' => 'ARS', 'currency_name' => 'Peso', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AM'), 'code' => 'AM', 'calling_code' => '374', 'currency_code' => 'AMD', 'currency_name' => 'Dram', 'currency_symbol' => '֏' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AW'), 'code' => 'AW', 'calling_code' => '297', 'currency_code' => 'AWG', 'currency_name' => 'Guilder', 'currency_symbol' => 'ƒ' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AU'), 'code' => 'AU', 'calling_code' => '61', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AT'), 'code' => 'AT', 'calling_code' => '43', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AZ'), 'code' => 'AZ', 'calling_code' => '994', 'currency_code' => 'AZN', 'currency_name' => 'Manat', 'currency_symbol' => 'ман' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BS'), 'code' => 'BS', 'calling_code' => '1242', 'currency_code' => 'BSD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BH'), 'code' => 'BH', 'calling_code' => '973', 'currency_code' => 'BHD', 'currency_name' => 'Dinar', 'currency_symbol' => 'د.ب' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BD'), 'code' => 'BD', 'calling_code' => '880', 'currency_code' => 'BDT', 'currency_name' => 'Taka', 'currency_symbol' => '৳' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BB'), 'code' => 'BB', 'calling_code' => '1246', 'currency_code' => 'BBD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BY'), 'code' => 'BY', 'calling_code' => '375', 'currency_code' => 'BYR', 'currency_name' => 'Ruble', 'currency_symbol' => 'p.' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BE'), 'code' => 'BE', 'calling_code' => '32', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BZ'), 'code' => 'BZ', 'calling_code' => '501', 'currency_code' => 'BZD', 'currency_name' => 'Dollar', 'currency_symbol' => 'BZ$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BJ'), 'code' => 'BJ', 'calling_code' => '229', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BM'), 'code' => 'BM', 'calling_code' => '1441', 'currency_code' => 'BMD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BQ_BO'), 'code' => 'BO', 'calling_code' => '599-7', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BQ_SA'), 'code' => 'SA', 'calling_code' => '599-4', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BQ_SE'), 'code' => 'SE', 'calling_code' => '599-3', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BT'), 'code' => 'BT', 'calling_code' => '975', 'currency_code' => 'BTN', 'currency_name' => 'Ngultrum', 'currency_symbol' => 'Nu.' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BO'), 'code' => 'BO', 'calling_code' => '591', 'currency_code' => 'BOB', 'currency_name' => 'Boliviano', 'currency_symbol' => '$b' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BA'), 'code' => 'BA', 'calling_code' => '387', 'currency_code' => 'BAM', 'currency_name' => 'Marka', 'currency_symbol' => 'KM' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BW'), 'code' => 'BW', 'calling_code' => '267', 'currency_code' => 'BWP', 'currency_name' => 'Pula', 'currency_symbol' => 'P' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BV'), 'code' => 'BV', 'calling_code' => '', 'currency_code' => 'NOK', 'currency_name' => 'Krone', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BR'), 'code' => 'BR', 'calling_code' => '55', 'currency_code' => 'BRL', 'currency_name' => 'Real', 'currency_symbol' => 'R$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IO'), 'code' => 'IO', 'calling_code' => '', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VG'), 'code' => 'VG', 'calling_code' => '1284', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BN'), 'code' => 'BN', 'calling_code' => '673', 'currency_code' => 'BND', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BG'), 'code' => 'BG', 'calling_code' => '359', 'currency_code' => 'BGN', 'currency_name' => 'Lev', 'currency_symbol' => 'лв' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BF'), 'code' => 'BF', 'calling_code' => '226', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_BI'), 'code' => 'BI', 'calling_code' => '257', 'currency_code' => 'BIF', 'currency_name' => 'Franc', 'currency_symbol' => 'FBu' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KH'), 'code' => 'KH', 'calling_code' => '855', 'currency_code' => 'KHR', 'currency_name' => 'Riels', 'currency_symbol' => '៛' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CM'), 'code' => 'CM', 'calling_code' => '237', 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'currency_symbol' => 'FCF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CA'), 'code' => 'CA', 'calling_code' => '1', 'currency_code' => 'CAD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CV'), 'code' => 'CV', 'calling_code' => '238', 'currency_code' => 'CVE', 'currency_name' => 'Escudo', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KY'), 'code' => 'KY', 'calling_code' => '1345', 'currency_code' => 'KYD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CF'), 'code' => 'CF', 'calling_code' => '236', 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'currency_symbol' => 'FCF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TD'), 'code' => 'TD', 'calling_code' => '235', 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'currency_symbol' => 'FCFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CL'), 'code' => 'CL', 'calling_code' => '56', 'currency_code' => 'CLP', 'currency_name' => 'Peso', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CN'), 'code' => 'CN', 'calling_code' => '86', 'currency_code' => 'CNY', 'currency_name' => 'YuanRenminbi', 'currency_symbol' => '¥' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CX'), 'code' => 'CX', 'calling_code' => '61', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CC'), 'code' => 'CC', 'calling_code' => '61', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CO'), 'code' => 'CO', 'calling_code' => '57', 'currency_code' => 'COP', 'currency_name' => 'Peso', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KM'), 'code' => 'KM', 'calling_code' => '269', 'currency_code' => 'KMF', 'currency_name' => 'Franc', 'currency_symbol' => 'CF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CK'), 'code' => 'CK', 'calling_code' => '682', 'currency_code' => 'NZD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CR'), 'code' => 'CR', 'calling_code' => '506', 'currency_code' => 'CRC', 'currency_name' => 'Colon', 'currency_symbol' => '₡' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_HR'), 'code' => 'HR', 'calling_code' => '385', 'currency_code' => 'HRK', 'currency_name' => 'Kuna', 'currency_symbol' => 'kn' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CU'), 'code' => 'CU', 'calling_code' => '53', 'currency_code' => 'CUP', 'currency_name' => 'Peso', 'currency_symbol' => '₱' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CW'), 'code' => 'CW', 'calling_code' => '5999', 'currency_code' => 'ANG', 'currency_name' => 'Guilder', 'currency_symbol' => 'ƒ' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CY'), 'code' => 'CY', 'calling_code' => '357', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CZ'), 'code' => 'CZ', 'calling_code' => '420', 'currency_code' => 'CZK', 'currency_name' => 'Koruna', 'currency_symbol' => 'Kč' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CD'), 'code' => 'CD', 'calling_code' => '243', 'currency_code' => 'CDF', 'currency_name' => 'Franc', 'currency_symbol' => 'FC' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_DK'), 'code' => 'DK', 'calling_code' => '45', 'currency_code' => 'DKK', 'currency_name' => 'Krone', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_DJ'), 'code' => 'DJ', 'calling_code' => '253', 'currency_code' => 'DJF', 'currency_name' => 'Franc', 'currency_symbol' => 'Fdj' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_DM'), 'code' => 'DM', 'calling_code' => '1767', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_DO'), 'code' => 'DO', 'calling_code' => '1809', 'currency_code' => 'DOP', 'currency_name' => 'Peso', 'currency_symbol' => 'RD$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TL'), 'code' => 'TL', 'calling_code' => '670', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_EC'), 'code' => 'EC', 'calling_code' => '593', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_EG'), 'code' => 'EG', 'calling_code' => '20', 'currency_code' => 'EGP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SV'), 'code' => 'SV', 'calling_code' => '503', 'currency_code' => 'SVC', 'currency_name' => 'Colone', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GQ'), 'code' => 'GQ', 'calling_code' => '240', 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'currency_symbol' => 'FCF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ER'), 'code' => 'ER', 'calling_code' => '291', 'currency_code' => 'ERN', 'currency_name' => 'Nakfa', 'currency_symbol' => 'Nfk' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_EE'), 'code' => 'EE', 'calling_code' => '372', 'currency_code' => 'EEK', 'currency_name' => 'Kroon', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ET'), 'code' => 'ET', 'calling_code' => '251', 'currency_code' => 'ETB', 'currency_name' => 'Birr', 'currency_symbol' => 'Br' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_FK'), 'code' => 'FK', 'calling_code' => '500', 'currency_code' => 'FKP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_FO'), 'code' => 'FO', 'calling_code' => '298', 'currency_code' => 'DKK', 'currency_name' => 'Krone', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_FJ'), 'code' => 'FJ', 'calling_code' => '679', 'currency_code' => 'FJD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_FI'), 'code' => 'FI', 'calling_code' => '358', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_FR'), 'code' => 'FR', 'calling_code' => '33', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GF'), 'code' => 'GF', 'calling_code' => '594', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PF'), 'code' => 'PF', 'calling_code' => '689', 'currency_code' => 'XPF', 'currency_name' => 'Franc', 'currency_symbol' => 'F' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TF'), 'code' => 'TF', 'calling_code' => '262', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GA'), 'code' => 'GA', 'calling_code' => '241', 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'currency_symbol' => 'FCF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GM'), 'code' => 'GM', 'calling_code' => '220', 'currency_code' => 'GMD', 'currency_name' => 'Dalasi', 'currency_symbol' => 'D' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GE'), 'code' => 'GE', 'calling_code' => '995', 'currency_code' => 'GEL', 'currency_name' => 'Lari', 'currency_symbol' => '₾' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_DE'), 'code' => 'DE', 'calling_code' => '49', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GH'), 'code' => 'GH', 'calling_code' => '233', 'currency_code' => 'GHC', 'currency_name' => 'Cedi', 'currency_symbol' => '¢' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GI'), 'code' => 'GI', 'calling_code' => '350', 'currency_code' => 'GIP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GR'), 'code' => 'GR', 'calling_code' => '30', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GL'), 'code' => 'GL', 'calling_code' => '299', 'currency_code' => 'DKK', 'currency_name' => 'Krone', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GD'), 'code' => 'GD', 'calling_code' => '1473', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GP'), 'code' => 'GP', 'calling_code' => '590', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GU'), 'code' => 'GU', 'calling_code' => '1671', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GT'), 'code' => 'GT', 'calling_code' => '502', 'currency_code' => 'GTQ', 'currency_name' => 'Quetzal', 'currency_symbol' => 'Q' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GN'), 'code' => 'GN', 'calling_code' => '224', 'currency_code' => 'GNF', 'currency_name' => 'Franc', 'currency_symbol' => 'FG' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GW'), 'code' => 'GW', 'calling_code' => '245', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GY'), 'code' => 'GY', 'calling_code' => '592', 'currency_code' => 'GYD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_HT'), 'code' => 'HT', 'calling_code' => '509', 'currency_code' => 'HTG', 'currency_name' => 'Gourde', 'currency_symbol' => 'G' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_HM'), 'code' => 'HM', 'calling_code' => '', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_HN'), 'code' => 'HN', 'calling_code' => '504', 'currency_code' => 'HNL', 'currency_name' => 'Lempira', 'currency_symbol' => 'L' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_HK'), 'code' => 'HK', 'calling_code' => '852', 'currency_code' => 'HKD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_HU'), 'code' => 'HU', 'calling_code' => '36', 'currency_code' => 'HUF', 'currency_name' => 'Forint', 'currency_symbol' => 'Ft' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IS'), 'code' => 'IS', 'calling_code' => '354', 'currency_code' => 'ISK', 'currency_name' => 'Krona', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IN'), 'code' => 'IN', 'calling_code' => '91', 'currency_code' => 'INR', 'currency_name' => 'Rupee', 'currency_symbol' => '₹' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ID'), 'code' => 'ID', 'calling_code' => '62', 'currency_code' => 'IDR', 'currency_name' => 'Rupiah', 'currency_symbol' => 'Rp' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IR'), 'code' => 'IR', 'calling_code' => '98', 'currency_code' => 'IRR', 'currency_name' => 'Rial', 'currency_symbol' => '﷼' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IQ'), 'code' => 'IQ', 'calling_code' => '964', 'currency_code' => 'IQD', 'currency_name' => 'Dinar', 'currency_symbol' => 'د.ع' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IE'), 'code' => 'IE', 'calling_code' => '353', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IM'), 'code' => 'IM', 'calling_code' => '44', 'currency_code' => 'GBP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IL'), 'code' => 'IL', 'calling_code' => '972', 'currency_code' => 'ILS', 'currency_name' => 'Shekel', 'currency_symbol' => '₪' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_IT'), 'code' => 'IT', 'calling_code' => '39', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CI'), 'code' => 'CI', 'calling_code' => '225', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_JM'), 'code' => 'JM', 'calling_code' => '1876', 'currency_code' => 'JMD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_JP'), 'code' => 'JP', 'calling_code' => '81', 'currency_code' => 'JPY', 'currency_name' => 'Yen', 'currency_symbol' => '¥' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_JO'), 'code' => 'JO', 'calling_code' => '962', 'currency_code' => 'JOD', 'currency_name' => 'Dinar', 'currency_symbol' => 'د.أ' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KZ'), 'code' => 'KZ', 'calling_code' => '7', 'currency_code' => 'KZT', 'currency_name' => 'Tenge', 'currency_symbol' => 'лв' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KE'), 'code' => 'KE', 'calling_code' => '254', 'currency_code' => 'KES', 'currency_name' => 'Shilling', 'currency_symbol' => 'KSh' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KI'), 'code' => 'KI', 'calling_code' => '686', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KW'), 'code' => 'KW', 'calling_code' => '965', 'currency_code' => 'KWD', 'currency_name' => 'Dinar', 'currency_symbol' => 'د.ك' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KG'), 'code' => 'KG', 'calling_code' => '996', 'currency_code' => 'KGS', 'currency_name' => 'Som', 'currency_symbol' => 'лв' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LA'), 'code' => 'LA', 'calling_code' => '856', 'currency_code' => 'LAK', 'currency_name' => 'Kip', 'currency_symbol' => '₭' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LV'), 'code' => 'LV', 'calling_code' => '371', 'currency_code' => 'LVL', 'currency_name' => 'Lat', 'currency_symbol' => 'Ls' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LB'), 'code' => 'LB', 'calling_code' => '961', 'currency_code' => 'LBP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LS'), 'code' => 'LS', 'calling_code' => '266', 'currency_code' => 'LSL', 'currency_name' => 'Loti', 'currency_symbol' => 'L' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LR'), 'code' => 'LR', 'calling_code' => '231', 'currency_code' => 'LRD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LY'), 'code' => 'LY', 'calling_code' => '218', 'currency_code' => 'LYD', 'currency_name' => 'Dinar', 'currency_symbol' => 'ل.د' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LI'), 'code' => 'LI', 'calling_code' => '423', 'currency_code' => 'CHF', 'currency_name' => 'Franc', 'currency_symbol' => 'CHF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LT'), 'code' => 'LT', 'calling_code' => '370', 'currency_code' => 'LTL', 'currency_name' => 'Litas', 'currency_symbol' => 'Lt' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LU'), 'code' => 'LU', 'calling_code' => '352', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MO'), 'code' => 'MO', 'calling_code' => '853', 'currency_code' => 'MOP', 'currency_name' => 'Pataca', 'currency_symbol' => 'MOP' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MK'), 'code' => 'MK', 'calling_code' => '389', 'currency_code' => 'MKD', 'currency_name' => 'Denar', 'currency_symbol' => 'ден' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MG'), 'code' => 'MG', 'calling_code' => '261', 'currency_code' => 'MGA', 'currency_name' => 'Ariary', 'currency_symbol' => 'Ar' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MW'), 'code' => 'MW', 'calling_code' => '265', 'currency_code' => 'MWK', 'currency_name' => 'Kwacha', 'currency_symbol' => 'MK' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MY'), 'code' => 'MY', 'calling_code' => '60', 'currency_code' => 'MYR', 'currency_name' => 'Ringgit', 'currency_symbol' => 'RM' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MV'), 'code' => 'MV', 'calling_code' => '960', 'currency_code' => 'MVR', 'currency_name' => 'Rufiyaa', 'currency_symbol' => 'Rf' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ML'), 'code' => 'ML', 'calling_code' => '223', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MT'), 'code' => 'MT', 'calling_code' => '356', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MH'), 'code' => 'MH', 'calling_code' => '692', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MQ'), 'code' => 'MQ', 'calling_code' => '596', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MR'), 'code' => 'MR', 'calling_code' => '222', 'currency_code' => 'MRO', 'currency_name' => 'Ouguiya', 'currency_symbol' => 'UM' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MU'), 'code' => 'MU', 'calling_code' => '230', 'currency_code' => 'MUR', 'currency_name' => 'Rupee', 'currency_symbol' => '₨' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_YT'), 'code' => 'YT', 'calling_code' => '262', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MX'), 'code' => 'MX', 'calling_code' => '52', 'currency_code' => 'MXN', 'currency_name' => 'Peso', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_FM'), 'code' => 'FM', 'calling_code' => '691', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MD'), 'code' => 'MD', 'calling_code' => '373', 'currency_code' => 'MDL', 'currency_name' => 'Leu', 'currency_symbol' => 'L' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MC'), 'code' => 'MC', 'calling_code' => '377', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MN'), 'code' => 'MN', 'calling_code' => '976', 'currency_code' => 'MNT', 'currency_name' => 'Tugrik', 'currency_symbol' => '₮' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ME'), 'code' => 'ME', 'calling_code' => '382', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MS'), 'code' => 'MS', 'calling_code' => '1664', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MA'), 'code' => 'MA', 'calling_code' => '212', 'currency_code' => 'MAD', 'currency_name' => 'Dirham', 'currency_symbol' => 'DH' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MZ'), 'code' => 'MZ', 'calling_code' => '258', 'currency_code' => 'MZN', 'currency_name' => 'Meticail', 'currency_symbol' => 'MT' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MM'), 'code' => 'MM', 'calling_code' => '95', 'currency_code' => 'MMK', 'currency_name' => 'Kyat', 'currency_symbol' => 'K' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NA'), 'code' => 'NA', 'calling_code' => '264', 'currency_code' => 'NAD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NR'), 'code' => 'NR', 'calling_code' => '674', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NP'), 'code' => 'NP', 'calling_code' => '977', 'currency_code' => 'NPR', 'currency_name' => 'Rupee', 'currency_symbol' => '₨' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NL'), 'code' => 'NL', 'calling_code' => '31', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NC'), 'code' => 'NC', 'calling_code' => '687', 'currency_code' => 'XPF', 'currency_name' => 'Franc', 'currency_symbol' => 'F' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NZ'), 'code' => 'NZ', 'calling_code' => '64', 'currency_code' => 'NZD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NI'), 'code' => 'NI', 'calling_code' => '505', 'currency_code' => 'NIO', 'currency_name' => 'Cordoba', 'currency_symbol' => 'C$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NE'), 'code' => 'NE', 'calling_code' => '227', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NG'), 'code' => 'NG', 'calling_code' => '234', 'currency_code' => 'NGN', 'currency_name' => 'Naira', 'currency_symbol' => '₦' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NU'), 'code' => 'NU', 'calling_code' => '683', 'currency_code' => 'NZD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NF'), 'code' => 'NF', 'calling_code' => '672', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KP'), 'code' => 'KP', 'calling_code' => '850', 'currency_code' => 'KPW', 'currency_name' => 'Won', 'currency_symbol' => '₩' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_MP'), 'code' => 'MP', 'calling_code' => '1670', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_NO'), 'code' => 'NO', 'calling_code' => '47', 'currency_code' => 'NOK', 'currency_name' => 'Krone', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_OM'), 'code' => 'OM', 'calling_code' => '968', 'currency_code' => 'OMR', 'currency_name' => 'Rial', 'currency_symbol' => '﷼' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PK'), 'code' => 'PK', 'calling_code' => '92', 'currency_code' => 'PKR', 'currency_name' => 'Rupee', 'currency_symbol' => '₨' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PW'), 'code' => 'PW', 'calling_code' => '680', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PS'), 'code' => 'PS', 'calling_code' => '970', 'currency_code' => 'ILS', 'currency_name' => 'Shekel', 'currency_symbol' => '₪' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PA'), 'code' => 'PA', 'calling_code' => '507', 'currency_code' => 'PAB', 'currency_name' => 'Balboa', 'currency_symbol' => 'B/.' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PG'), 'code' => 'PG', 'calling_code' => '675', 'currency_code' => 'PGK', 'currency_name' => 'Kina', 'currency_symbol' => 'K' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PY'), 'code' => 'PY', 'calling_code' => '595', 'currency_code' => 'PYG', 'currency_name' => 'Guarani', 'currency_symbol' => 'Gs' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PE'), 'code' => 'PE', 'calling_code' => '51', 'currency_code' => 'PEN', 'currency_name' => 'Sol', 'currency_symbol' => 'S/.' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PH'), 'code' => 'PH', 'calling_code' => '63', 'currency_code' => 'PHP', 'currency_name' => 'Peso', 'currency_symbol' => 'Php' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PN'), 'code' => 'PN', 'calling_code' => '870', 'currency_code' => 'NZD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PL'), 'code' => 'PL', 'calling_code' => '48', 'currency_code' => 'PLN', 'currency_name' => 'Zloty', 'currency_symbol' => 'zł' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PT'), 'code' => 'PT', 'calling_code' => '351', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PR'), 'code' => 'PR', 'calling_code' => '1', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_QA'), 'code' => 'QA', 'calling_code' => '974', 'currency_code' => 'QAR', 'currency_name' => 'Rial', 'currency_symbol' => '﷼' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CG'), 'code' => 'CG', 'calling_code' => '242', 'currency_code' => 'XAF', 'currency_name' => 'Franc', 'currency_symbol' => 'FCF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_RE'), 'code' => 'RE', 'calling_code' => '262', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_RO'), 'code' => 'RO', 'calling_code' => '40', 'currency_code' => 'RON', 'currency_name' => 'Leu', 'currency_symbol' => 'lei' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_RU'), 'code' => 'RU', 'calling_code' => '7', 'currency_code' => 'RUB', 'currency_name' => 'Ruble', 'currency_symbol' => 'руб' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_RW'), 'code' => 'RW', 'calling_code' => '250', 'currency_code' => 'RWF', 'currency_name' => 'Franc', 'currency_symbol' => 'FRw' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SH'), 'code' => 'SH', 'calling_code' => '290', 'currency_code' => 'SHP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KN'), 'code' => 'KN', 'calling_code' => '1869', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LC'), 'code' => 'LC', 'calling_code' => '1758', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_PM'), 'code' => 'PM', 'calling_code' => '508', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VC'), 'code' => 'VC', 'calling_code' => '1784', 'currency_code' => 'XCD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_WS'), 'code' => 'WS', 'calling_code' => '685', 'currency_code' => 'WST', 'currency_name' => 'Tala', 'currency_symbol' => 'WS$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SM'), 'code' => 'SM', 'calling_code' => '378', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ST'), 'code' => 'ST', 'calling_code' => '239', 'currency_code' => 'STD', 'currency_name' => 'Dobra', 'currency_symbol' => 'Db' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SA'), 'code' => 'SA', 'calling_code' => '966', 'currency_code' => 'SAR', 'currency_name' => 'Rial', 'currency_symbol' => '﷼' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SN'), 'code' => 'SN', 'calling_code' => '221', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_RS'), 'code' => 'RS', 'calling_code' => '381', 'currency_code' => 'RSD', 'currency_name' => 'Dinar', 'currency_symbol' => 'Дин' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SC'), 'code' => 'SC', 'calling_code' => '248', 'currency_code' => 'SCR', 'currency_name' => 'Rupee', 'currency_symbol' => '₨' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SL'), 'code' => 'SL', 'calling_code' => '232', 'currency_code' => 'SLL', 'currency_name' => 'Leone', 'currency_symbol' => 'Le' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SG'), 'code' => 'SG', 'calling_code' => '65', 'currency_code' => 'SGD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SK'), 'code' => 'SK', 'calling_code' => '421', 'currency_code' => 'SKK', 'currency_name' => 'Koruna', 'currency_symbol' => 'Sk' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SI'), 'code' => 'SI', 'calling_code' => '386', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SB'), 'code' => 'SB', 'calling_code' => '677', 'currency_code' => 'SBD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SO'), 'code' => 'SO', 'calling_code' => '252', 'currency_code' => 'SOS', 'currency_name' => 'Shilling', 'currency_symbol' => 'S' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ZA'), 'code' => 'ZA', 'calling_code' => '27', 'currency_code' => 'ZAR', 'currency_name' => 'Rand', 'currency_symbol' => 'R' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GS'), 'code' => 'GS', 'calling_code' => '500', 'currency_code' => 'GBP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_KR'), 'code' => 'KR', 'calling_code' => '82', 'currency_code' => 'KRW', 'currency_name' => 'Won', 'currency_symbol' => '₩' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ES'), 'code' => 'ES', 'calling_code' => '34', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_LK'), 'code' => 'LK', 'calling_code' => '94', 'currency_code' => 'LKR', 'currency_name' => 'Rupee', 'currency_symbol' => '₨' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SD'), 'code' => 'SD', 'calling_code' => '249', 'currency_code' => 'SDD', 'currency_name' => 'Dinar', 'currency_symbol' => 'ج.س' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SS'), 'code' => 'SS', 'calling_code' => '211', 'currency_code' => 'SSP', 'currency_name' => 'Pound', 'currency_symbol' => 'SS£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SR'), 'code' => 'SR', 'calling_code' => '597', 'currency_code' => 'SRD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SJ'), 'code' => 'SJ', 'calling_code' => '47', 'currency_code' => 'NOK', 'currency_name' => 'Krone', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SZ'), 'code' => 'SZ', 'calling_code' => '268', 'currency_code' => 'SZL', 'currency_name' => 'Lilangeni', 'currency_symbol' => 'L' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SE'), 'code' => 'SE', 'calling_code' => '46', 'currency_code' => 'SEK', 'currency_name' => 'Krona', 'currency_symbol' => 'kr' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_CH'), 'code' => 'CH', 'calling_code' => '41', 'currency_code' => 'CHF', 'currency_name' => 'Franc', 'currency_symbol' => 'CHF' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_SY'), 'code' => 'SY', 'calling_code' => '963', 'currency_code' => 'SYP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TW'), 'code' => 'TW', 'calling_code' => '886', 'currency_code' => 'TWD', 'currency_name' => 'Dollar', 'currency_symbol' => 'NT$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TJ'), 'code' => 'TJ', 'calling_code' => '992', 'currency_code' => 'TJS', 'currency_name' => 'Somoni', 'currency_symbol' => 'SM' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TZ'), 'code' => 'TZ', 'calling_code' => '255', 'currency_code' => 'TZS', 'currency_name' => 'Shilling', 'currency_symbol' => 'TSh' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TH'), 'code' => 'TH', 'calling_code' => '66', 'currency_code' => 'THB', 'currency_name' => 'Baht', 'currency_symbol' => '฿' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TG'), 'code' => 'TG', 'calling_code' => '228', 'currency_code' => 'XOF', 'currency_name' => 'Franc', 'currency_symbol' => 'CFA' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TK'), 'code' => 'TK', 'calling_code' => '690', 'currency_code' => 'NZD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TO'), 'code' => 'TO', 'calling_code' => '676', 'currency_code' => 'TOP', 'currency_name' => 'Paanga', 'currency_symbol' => 'T$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TT'), 'code' => 'TT', 'calling_code' => '1868', 'currency_code' => 'TTD', 'currency_name' => 'Dollar', 'currency_symbol' => 'TT$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TN'), 'code' => 'TN', 'calling_code' => '216', 'currency_code' => 'TND', 'currency_name' => 'Dinar', 'currency_symbol' => 'د.ت' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TR'), 'code' => 'TR', 'calling_code' => '90', 'currency_code' => 'TRY', 'currency_name' => 'Lira', 'currency_symbol' => 'YTL' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TM'), 'code' => 'TM', 'calling_code' => '993', 'currency_code' => 'TMM', 'currency_name' => 'Manat', 'currency_symbol' => 'm' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TC'), 'code' => 'TC', 'calling_code' => '1649', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_TV'), 'code' => 'TV', 'calling_code' => '688', 'currency_code' => 'AUD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VI'), 'code' => 'VI', 'calling_code' => '1340', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_UG'), 'code' => 'UG', 'calling_code' => '256', 'currency_code' => 'UGX', 'currency_name' => 'Shilling', 'currency_symbol' => 'USh' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_UA'), 'code' => 'UA', 'calling_code' => '380', 'currency_code' => 'UAH', 'currency_name' => 'Hryvnia', 'currency_symbol' => '₴' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_AE'), 'code' => 'AE', 'calling_code' => '971', 'currency_code' => 'AED', 'currency_name' => 'Dirham', 'currency_symbol' => 'د.إ' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_GB'), 'code' => 'GB', 'calling_code' => '44', 'currency_code' => 'GBP', 'currency_name' => 'Pound', 'currency_symbol' => '£' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_US'), 'code' => 'US', 'calling_code' => '1', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_UM'), 'code' => 'UM', 'calling_code' => '246', 'currency_code' => 'USD', 'currency_name' => 'Dollar', 'currency_symbol' => '$' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_UY'), 'code' => 'UY', 'calling_code' => '598', 'currency_code' => 'UYU', 'currency_name' => 'Peso', 'currency_symbol' => '$U' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_UZ'), 'code' => 'UZ', 'calling_code' => '998', 'currency_code' => 'UZS', 'currency_name' => 'Som', 'currency_symbol' => 'лв' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VU'), 'code' => 'VU', 'calling_code' => '678', 'currency_code' => 'VUV', 'currency_name' => 'Vatu', 'currency_symbol' => 'Vt' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VA'), 'code' => 'VA', 'calling_code' => '39', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'currency_symbol' => '€' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VE'), 'code' => 'VE', 'calling_code' => '58', 'currency_code' => 'VEF', 'currency_name' => 'Bolivar', 'currency_symbol' => 'Bs' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_VN'), 'code' => 'VN', 'calling_code' => '84', 'currency_code' => 'VND', 'currency_name' => 'Dong', 'currency_symbol' => '₫' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_WF'), 'code' => 'WF', 'calling_code' => '681', 'currency_code' => 'XPF', 'currency_name' => 'Franc', 'currency_symbol' => 'F' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_EH'), 'code' => 'EH', 'calling_code' => '212', 'currency_code' => 'MAD', 'currency_name' => 'Dirham', 'currency_symbol' => 'DH' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_YE'), 'code' => 'YE', 'calling_code' => '967', 'currency_code' => 'YER', 'currency_name' => 'Rial', 'currency_symbol' => '﷼' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ZM'), 'code' => 'ZM', 'calling_code' => '260', 'currency_code' => 'ZMK', 'currency_name' => 'Kwacha', 'currency_symbol' => 'ZK' ],
			[ 'name' => fpframework()->_('FPF_COUNTRY_ZW'), 'code' => 'ZW', 'calling_code' => '263', 'currency_code' => 'ZWD', 'currency_name' => 'Dollar', 'currency_symbol' => 'Z$' ]
		];
	}
}