<?php

declare(strict_types=1);

namespace Siel\Acumulus\Helpers;

/**
 * Class Countries defines country related features, including:
 * - Country codes
 * - Country names (in Dutch)
 * - Fiscal EU countries
 * - Conversion from EU country codes to ISO country codes.
 */
class Countries
{
    /**
     * Returns whether the country is the Netherlands.
     * For now only the alpha-2 codes are allowed. Other notations will be added
     * as soon we support a web shop with a different way of storing countries.
     *
     * @param string $countryCode
     *   Case-insensitive ISO 3166-1 alpha-2 country code.
     *
     * @return bool
     */
    public function isNl(string $countryCode): bool
    {
        return strtoupper($countryCode) === 'NL';
    }

    /**
     * Converts EU country codes to their ISO equivalent:
     * The EU has 2 country codes that differ from ISO:
     * - UK instead of GB
     * - EL instead of GR.
     *
     * @param string $countryCode
     *   An EU orISO country code.
     *
     * @return string
     *   The ISO country code.
     */
    public function convertEuCountryCode(string $countryCode): string
    {
        if (strtoupper($countryCode) === 'EL') {
            $countryCode = 'GR';
        }
        if (strtoupper($countryCode) === 'UK') {
            $countryCode = 'GB';
        }
        return $countryCode;
    }

    /**
     * Returns the Dutch name for the given country code.
     *
     * @param string $countryCode
     *   ISO country code (2 characters).
     *
     * @return string
     *   The (Dutch) name of the country or the empty string if the code could
     *   not be looked up.
     * @todo: deprecate by replacing it with using 'countryautoname' or calling
     *   https://www.siel.nl/acumulus/API/Picklists/Countries/ (cache results).
     */
    public function getCountryName(string $countryCode): string
    {
        $countryNames = [
            'AF' => 'Afghanistan',
            'AX' => 'Åland',
            'AL' => 'Albanië',
            'DZ' => 'Algerije',
            'VI' => 'Amerikaanse Maagdeneilanden',
            'AS' => 'Amerikaans-Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua en Barbuda',
            'AR' => 'Argentinië',
            'AM' => 'Armenië',
            'AW' => 'Aruba',
            'AU' => 'Australië',
            'AZ' => 'Azerbeidzjan',
            'BS' => 'Bahama\'s',
            'BH' => 'Bahrein',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BE' => 'België',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BQ' => 'Bonaire, Sint Eustatius en Saba',
            'BA' => 'Bosnië en Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouveteiland',
            'BR' => 'Brazilië',
            'VG' => 'Britse Maagdeneilanden',
            'IO' => 'Brits Indische Oceaanterritorium',
            'BN' => 'Brunei',
            'BG' => 'Bulgarije',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodja',
            'CA' => 'Canada',
            'CF' => 'Centraal-Afrikaanse Republiek',
            'CL' => 'Chili',
            'CN' => 'China',
            'CX' => 'Christmaseiland',
            'CC' => 'Cocoseilanden',
            'CO' => 'Colombia',
            'KM' => 'Comoren',
            'CG' => 'Congo-Brazzaville',
            'CD' => 'Congo-Kinshasa',
            'CK' => 'Cookeilanden',
            'CR' => 'Costa Rica',
            'CU' => 'Cuba',
            'CW' => 'Curaçao',
            'CY' => 'Cyprus',
            'DK' => 'Denemarken',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominicaanse Republiek',
            'DE' => 'Duitsland',
            'EC' => 'Ecuador',
            'EG' => 'Egypte',
            'SV' => 'El Salvador',
            'GQ' => 'Equatoriaal-Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estland',
            'ET' => 'Ethiopië',
            'FO' => 'Faeröer',
            'FK' => 'Falklandeilanden',
            'FJ' => 'Fiji',
            'PH' => 'Filipijnen',
            'FI' => 'Finland',
            'FR' => 'Frankrijk',
            'TF' => 'Franse Zuidelijke en Antarctische Gebieden',
            'GF' => 'Frans-Guyana',
            'PF' => 'Frans-Polynesië',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgië',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GD' => 'Grenada',
            'GR' => 'Griekenland',
            'GL' => 'Groenland',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinee',
            'GW' => 'Guinee-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haïti',
            'HM' => 'Heard en McDonaldeilanden',
            'HN' => 'Honduras',
            'HU' => 'Hongarije',
            'HK' => 'Hongkong',
            'IE' => 'Ierland',
            'IS' => 'IJsland',
            'IN' => 'India',
            'ID' => 'Indonesië',
            'IQ' => 'Irak',
            'IR' => 'Iran',
            'IL' => 'Israël',
            'IT' => 'Italië',
            'CI' => 'Ivoorkust',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'YE' => 'Jemen',
            'JE' => 'Jersey',
            'JO' => 'Jordanië',
            'KY' => 'Kaaimaneilanden',
            'CV' => 'Kaapverdië',
            'CM' => 'Kameroen',
            'KZ' => 'Kazachstan',
            'KE' => 'Kenia',
            'KG' => 'Kirgizië',
            'KI' => 'Kiribati',
            'UM' => 'Kleine Pacifische eilanden van de Verenigde Staten',
            'KW' => 'Koeweit',
            'HR' => 'Kroatië',
            'LA' => 'Laos',
            'LS' => 'Lesotho',
            'LV' => 'Letland',
            'LB' => 'Libanon',
            'LR' => 'Liberia',
            'LY' => 'Libië',
            'LI' => 'Liechtenstein',
            'LT' => 'Litouwen',
            'LU' => 'Luxemburg',
            'MO' => 'Macau',
            'MK' => 'Macedonië',
            'MG' => 'Madagaskar',
            'MW' => 'Malawi',
            'MV' => 'Maldiven',
            'MY' => 'Maleisië',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'IM' => 'Man',
            'MA' => 'Marokko',
            'MH' => 'Marshalleilanden',
            'MQ' => 'Martinique',
            'MR' => 'Mauritanië',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldavië',
            'MC' => 'Monaco',
            'MN' => 'Mongolië',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibië',
            'NR' => 'Nauru',
            'NL' => 'Nederland',
            'NP' => 'Nepal',
            'NI' => 'Nicaragua',
            'NC' => 'Nieuw-Caledonië',
            'NZ' => 'Nieuw-Zeeland',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'MP' => 'Noordelijke Marianen',
            'KP' => 'Noord-Korea',
            'NO' => 'Noorwegen',
            'NF' => 'Norfolk',
            'UG' => 'Oeganda',
            'UA' => 'Oekraïne',
            'UZ' => 'Oezbekistan',
            'OM' => 'Oman',
            'AT' => 'Oostenrijk',
            'TL' => 'Oost-Timor',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestina',
            'PA' => 'Panama',
            'PG' => 'Papoea-Nieuw-Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PN' => 'Pitcairneilanden',
            'PL' => 'Polen',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Réunion',
            'RO' => 'Roemenië',
            'RU' => 'Rusland',
            'RW' => 'Rwanda',
            'BL' => 'Saint-Barthélemy',
            'KN' => 'Saint Kitts en Nevis',
            'LC' => 'Saint Lucia',
            'PM' => 'Saint-Pierre en Miquelon',
            'VC' => 'Saint Vincent en de Grenadines',
            'SB' => 'Salomonseilanden',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'SA' => 'Saoedi-Arabië',
            'ST' => 'Sao Tomé en Principe',
            'SN' => 'Senegal',
            'RS' => 'Servië',
            'SC' => 'Seychellen',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SH' => 'Sint-Helena, Ascension en Tristan da Cunha',
            'MF' => 'Sint-Maarten',
            'SX' => 'Sint Maarten',
            'SI' => 'Slovenië',
            'SK' => 'Slowakije',
            'SD' => 'Soedan',
            'SO' => 'Somalië',
            'ES' => 'Spanje',
            'SJ' => 'Spitsbergen en Jan Mayen',
            'LK' => 'Sri Lanka',
            'SR' => 'Suriname',
            'SZ' => 'Swaziland',
            'SY' => 'Syrië',
            'TJ' => 'Tadzjikistan',
            'TW' => 'Taiwan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad en Tobago',
            'TD' => 'Tsjaad',
            'CZ' => 'Tsjechië',
            'TN' => 'Tunesië',
            'TR' => 'Turkije',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks- en Caicoseilanden',
            'TV' => 'Tuvalu',
            'UY' => 'Uruguay',
            'VU' => 'Vanuatu',
            'VA' => 'Vaticaanstad',
            'VE' => 'Venezuela',
            'AE' => 'Verenigde Arabische Emiraten',
            'US' => 'Verenigde Staten',
            'GB' => 'Verenigd Koninkrijk',
            'VN' => 'Vietnam',
            'WF' => 'Wallis en Futuna',
            'EH' => 'Westelijke Sahara',
            'BY' => 'Wit-Rusland',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
            'ZA' => 'Zuid-Afrika',
            'GS' => 'Zuid-Georgia en de Zuidelijke Sandwicheilanden',
            'KR' => 'Zuid-Korea',
            'SS' => 'Zuid-Soedan',
            'SE' => 'Zweden',
            'CH' => 'Zwitserland',
        ];
        return $countryNames[$countryCode] ?? '';
    }
}
