<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 5:25 AM
 */

namespace rnpdfimporter\core\Integration\Adapters\WPForm\Entry\EntryItems;


use rnpdfimporter\core\Integration\Adapters\WPForm\Settings\Forms\Fields\WPFormAddressFieldSettings;
use rnpdfimporter\core\Integration\Processors\Entry\EntryItems\ListEntryItem\EntryItemBase;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleBoxFormatter\MultipleBoxFormatter;
use rnpdfimporter\core\Integration\Processors\Entry\HTMLFormatters\MultipleLineFormatter;

class WPFormAddressEntryItem extends EntryItemBase
{
    public $Address1;
    public $Address2;
    public $City;
    public $State;
    public $Postal;
    public $Country;
    protected function InternalGetObjectToSave()
    {
        $value=$this->Address1.', '.$this->Address2.', '.$this->City.', '.$this->State.', '.$this->Postal;
        if($this->Country!='')
            $value.=', '.$this->Country;
        return (object)array(
            'Value'=>$value,
            'Address1'=>$this->Address1,
            'Address2'=>$this->Address2,
            'City'=>$this->City,
            'State'=>$this->State,
            'Postal'=>$this->Postal,
            'Country'=>$this->Country
        );
    }

    public function InitializeWithValues($field,$address1,$address2,$city,$state,$postal,$country)
    {
        $this->Initialize($field);
        $this->Address1=$address1;
        $this->Address2=$address2;
        $this->City=$city;
        $this->State=$state;
        $this->Postal=$postal;
        $this->Country=$country;
        return $this;
    }

    public function InitializeWithOptions($field,$options)
    {
        $this->Address1='';
        $this->Address2='';
        $this->City='';
        $this->State='';
        $this->Postal='';
        $this->Country='';

        $this->Field=$field;
        if(isset($this->Address1))
            $this->Address1=$options->Address1;
        if(isset($this->Address2))
            $this->Address2=$options->Address2;
        if(isset($this->City))
            $this->City=$options->City;
        if(isset($this->State))
            $this->State=$options->State;
        if(isset($this->Postal))
            $this->Postal=$options->Postal;

        if(isset($this->Country))
            $this->Country=$options->Country;
    }

    public function GetHtml($style='standard')
    {
        if($style=='similar')
        {
            /** @var WPFormAddressFieldSettings $field */
            $field=$this->Field;
            $formatter=new MultipleBoxFormatter();
            $formatter->CreateRowWithColumn("Address 1",$this->Address1,100);
            if(!$field->HideAddress2)
                $formatter->CreateRowWithColumn("Address 2",$this->Address2,100);

            $row=$formatter->CreateRow();
            $row->AddColumn('City',$this->City,50);
            $row->AddColumn('State',$this->State,50);

            if(!$field->HidePostal||!$field->HideCountry)
            {
                $row = $formatter->CreateRow();
                if (!$field->HidePostal)
                    $row->AddColumn('Postal', $this->Postal, 50);
                if (!$field->HideCountry)
                    $row->AddColumn('Country', $this->Country, 50);
            }

            return $formatter;

        }
        $formatter=new MultipleLineFormatter();

        if($this->Address1!='')
            $formatter->AddLine($this->Address1);
        if($this->Address2!='')
            $formatter->AddLine($this->Address2);
        if($this->City!='')
            $formatter->AddLine($this->City);
        if($this->State!='')
            $formatter->AddLine($this->State);
        if($this->Postal!='')
            $formatter->AddLine($this->Postal);

        if($this->Country!='')
            $formatter->AddLine($this->Country);

        return $formatter;
    }

    public function GetFullCountryName()
    {
        $countryDictionary=\json_decode('[{"Label":"Afghanistan","Id":"AF"},{"Label":"Aland Islands","Id":"AX"},{"Label":"Albania","Id":"AL"},{"Label":"Algeria","Id":"DZ"},{"Label":"American Samoa","Id":"AS"},{"Label":"Andorra","Id":"AD"},{"Label":"Angola","Id":"AO"},{"Label":"Anguilla","Id":"AI"},{"Label":"Antarctica","Id":"AQ"},{"Label":"Antigua and Barbuda","Id":"AG"},{"Label":"Argentina","Id":"AR"},{"Label":"Armenia","Id":"AM"},{"Label":"Aruba","Id":"AW"},{"Label":"Ascension Island","Id":"AC"},{"Label":"Australia","Id":"AU"},{"Label":"Austria","Id":"AT"},{"Label":"Azerbaijan","Id":"AZ"},{"Label":"Bahamas","Id":"BS"},{"Label":"Bahrain","Id":"BH"},{"Label":"Bangladesh","Id":"BD"},{"Label":"Barbados","Id":"BB"},{"Label":"Belarus","Id":"BY"},{"Label":"Belgium","Id":"BE"},{"Label":"Belize","Id":"BZ"},{"Label":"Benin","Id":"BJ"},{"Label":"Bermuda","Id":"BM"},{"Label":"Bhutan","Id":"BT"},{"Label":"Bolivia","Id":"BO"},{"Label":"Bosnia and Herzegovina","Id":"BA"},{"Label":"Botswana","Id":"BW"},{"Label":"Bouvet Island","Id":"BV"},{"Label":"Brazil","Id":"BR"},{"Label":"British Indian Ocean Territory","Id":"IO"},{"Label":"British Virgin Islands","Id":"VG"},{"Label":"Brunei","Id":"BN"},{"Label":"Bulgaria","Id":"BG"},{"Label":"Burkina Faso","Id":"BF"},{"Label":"Burundi","Id":"BI"},{"Label":"Cambodia","Id":"KH"},{"Label":"Cameroon","Id":"CM"},{"Label":"Canada","Id":"CA"},{"Label":"Canary Islands","Id":"IC"},{"Label":"Cape Verde","Id":"CV"},{"Label":"Caribbean Netherlands","Id":"BQ"},{"Label":"Cayman Islands","Id":"KY"},{"Label":"Central African Republic","Id":"CF"},{"Label":"Ceuta and Melilla","Id":"EA"},{"Label":"Chad","Id":"TD"},{"Label":"Chile","Id":"CL"},{"Label":"China","Id":"CN"},{"Label":"Christmas Island","Id":"CX"},{"Label":"Clipperton Island","Id":"CP"},{"Label":"Cocos (Keeling) Islands","Id":"CC"},{"Label":"Colombia","Id":"CO"},{"Label":"Comoros","Id":"KM"},{"Label":"Congo (DRC)","Id":"CD"},{"Label":"Congo (Republic)","Id":"CG"},{"Label":"Cook Islands","Id":"CK"},{"Label":"Costa Rica","Id":"CR"},{"Label":"Côte d’Ivoire","Id":"CI"},{"Label":"Croatia","Id":"HR"},{"Label":"Cuba","Id":"CU"},{"Label":"Curaçao","Id":"CW"},{"Label":"Cyprus","Id":"CY"},{"Label":"Czech Republic","Id":"CZ"},{"Label":"Denmark (Danmark)","Id":"DK"},{"Label":"Diego Garcia","Id":"DG"},{"Label":"Djibouti","Id":"DJ"},{"Label":"Dominica","Id":"DM"},{"Label":"Dominican Republic","Id":"DO"},{"Label":"Ecuador","Id":"EC"},{"Label":"Egypt","Id":"EG"},{"Label":"El Salvador","Id":"SV"},{"Label":"Equatorial Guinea","Id":"GQ"},{"Label":"Eritrea","Id":"ER"},{"Label":"Estonia","Id":"EE"},{"Label":"Ethiopia","Id":"ET"},{"Label":"Falkland Islands","Id":"FK"},{"Label":"Faroe Islands","Id":"FO"},{"Label":"Fiji","Id":"FJ"},{"Label":"Finland","Id":"FI"},{"Label":"France","Id":"FR"},{"Label":"French Guiana","Id":"GF"},{"Label":"French Polynesia","Id":"PF"},{"Label":"French Southern Territories","Id":"TF"},{"Label":"Gabon","Id":"GA"},{"Label":"Gambia","Id":"GM"},{"Label":"Georgia","Id":"GE"},{"Label":"Germany","Id":"DE"},{"Label":"Ghana","Id":"GH"},{"Label":"Gibraltar","Id":"GI"},{"Label":"Greece","Id":"GR"},{"Label":"Greenland","Id":"GL"},{"Label":"Grenada","Id":"GD"},{"Label":"Guadeloupe","Id":"GP"},{"Label":"Guam","Id":"GU"},{"Label":"Guatemala","Id":"GT"},{"Label":"Guernsey","Id":"GG"},{"Label":"Guinea","Id":"GN"},{"Label":"Guinea-Bissau","Id":"GW"},{"Label":"Guyana","Id":"GY"},{"Label":"Haiti","Id":"HT"},{"Label":"Heard & McDonald Islands","Id":"HM"},{"Label":"Honduras","Id":"HN"},{"Label":"Hong Kong","Id":"HK"},{"Label":"Hungary","Id":"HU"},{"Label":"Iceland","Id":"IS"},{"Label":"India","Id":"IN"},{"Label":"Indonesia","Id":"ID"},{"Label":"Iran","Id":"IR"},{"Label":"Iraq","Id":"IQ"},{"Label":"Ireland","Id":"IE"},{"Label":"Isle of Man","Id":"IM"},{"Label":"Israel","Id":"IL"},{"Label":"Italy","Id":"IT"},{"Label":"Jamaica","Id":"JM"},{"Label":"Japan","Id":"JP"},{"Label":"Jersey","Id":"JE"},{"Label":"Jordan","Id":"JO"},{"Label":"Kazakhstan","Id":"KZ"},{"Label":"Kenya","Id":"KE"},{"Label":"Kiribati","Id":"KI"},{"Label":"Kosovo","Id":"XK"},{"Label":"Kuwait","Id":"KW"},{"Label":"Kyrgyzstan","Id":"KG"},{"Label":"Laos","Id":"LA"},{"Label":"Latvia","Id":"LV"},{"Label":"Lebanon","Id":"LB"},{"Label":"Lesotho","Id":"LS"},{"Label":"Liberia","Id":"LR"},{"Label":"Libya","Id":"LY"},{"Label":"Liechtenstein","Id":"LI"},{"Label":"Lithuania","Id":"LT"},{"Label":"Luxembourg","Id":"LU"},{"Label":"Macau","Id":"MO"},{"Label":"Macedonia (FYROM)","Id":"MK"},{"Label":"Madagascar","Id":"MG"},{"Label":"Malawi","Id":"MW"},{"Label":"Malaysia","Id":"MY"},{"Label":"Maldives","Id":"MV"},{"Label":"Mali","Id":"ML"},{"Label":"Malta","Id":"MT"},{"Label":"Marshall Islands","Id":"MH"},{"Label":"Martinique","Id":"MQ"},{"Label":"Mauritania","Id":"MR"},{"Label":"Mauritius","Id":"MU"},{"Label":"Mayotte","Id":"YT"},{"Label":"Mexico","Id":"MX"},{"Label":"Micronesia","Id":"FM"},{"Label":"Moldova","Id":"MD"},{"Label":"Monaco","Id":"MC"},{"Label":"Mongolia","Id":"MN"},{"Label":"Montenegro","Id":"ME"},{"Label":"Montserrat","Id":"MS"},{"Label":"Morocco","Id":"MA"},{"Label":"Mozambique","Id":"MZ"},{"Label":"Myanmar","Id":"MM"},{"Label":"Namibia","Id":"NA"},{"Label":"Nauru","Id":"NR"},{"Label":"Nepal","Id":"NP"},{"Label":"Netherlands","Id":"NL"},{"Label":"New Caledonia","Id":"NC"},{"Label":"New Zealand","Id":"NZ"},{"Label":"Nicaragua","Id":"NI"},{"Label":"Niger","Id":"NE"},{"Label":"Nigeria","Id":"NG"},{"Label":"Niue","Id":"NU"},{"Label":"Norfolk Island","Id":"NF"},{"Label":"Northern Mariana Islands","Id":"MP"},{"Label":"North Korea","Id":"KP"},{"Label":"Norway","Id":"NO"},{"Label":"Oman","Id":"OM"},{"Label":"Pakistan","Id":"PK"},{"Label":"Palau","Id":"PW"},{"Label":"Palestine","Id":"PS"},{"Label":"Panama","Id":"PA"},{"Label":"Papua New Guinea","Id":"PG"},{"Label":"Paraguay","Id":"PY"},{"Label":"Peru","Id":"PE"},{"Label":"Philippines","Id":"PH"},{"Label":"Pitcairn Islands","Id":"PN"},{"Label":"Poland","Id":"PL"},{"Label":"Portugal","Id":"PT"},{"Label":"Puerto Rico","Id":"PR"},{"Label":"Qatar","Id":"QA"},{"Label":"Réunion","Id":"RE"},{"Label":"Romania","Id":"RO"},{"Label":"Russia","Id":"RU"},{"Label":"Rwanda","Id":"RW"},{"Label":"Saint Barthélemy","Id":"BL"},{"Label":"Saint Helena","Id":"SH"},{"Label":"Saint Kitts and Nevis","Id":"KN"},{"Label":"Saint Lucia","Id":"LC"},{"Label":"Saint Martin","Id":"MF"},{"Label":"Saint Pierre and Miquelon","Id":"PM"},{"Label":"Samoa","Id":"WS"},{"Label":"San Marino","Id":"SM"},{"Label":"São Tomé and Príncipe","Id":"ST"},{"Label":"Saudi Arabia","Id":"SA"},{"Label":"Senegal","Id":"SN"},{"Label":"Serbia","Id":"RS"},{"Label":"Seychelles","Id":"SC"},{"Label":"Sierra Leone","Id":"SL"},{"Label":"Singapore","Id":"SG"},{"Label":"Sint Maarten","Id":"SX"},{"Label":"Slovakia","Id":"SK"},{"Label":"Slovenia","Id":"SI"},{"Label":"Solomon Islands","Id":"SB"},{"Label":"Somalia","Id":"SO"},{"Label":"South Africa","Id":"ZA"},{"Label":"South Georgia & South Sandwich Islands","Id":"GS"},{"Label":"South Korea","Id":"KR"},{"Label":"South Sudan","Id":"SS"},{"Label":"Spain","Id":"ES"},{"Label":"Sri Lanka","Id":"LK"},{"Label":"St. Vincent & Grenadines","Id":"VC"},{"Label":"Sudan","Id":"SD"},{"Label":"Suriname","Id":"SR"},{"Label":"Svalbard and Jan Mayen","Id":"SJ"},{"Label":"Swaziland","Id":"SZ"},{"Label":"Sweden","Id":"SE"},{"Label":"Switzerland","Id":"CH"},{"Label":"Syria","Id":"SY"},{"Label":"Taiwan","Id":"TW"},{"Label":"Tajikistan","Id":"TJ"},{"Label":"Tanzania","Id":"TZ"},{"Label":"Thailand","Id":"TH"},{"Label":"Timor-Leste","Id":"TL"},{"Label":"Togo","Id":"TG"},{"Label":"Tokelau","Id":"TK"},{"Label":"Tonga","Id":"TO"},{"Label":"Trinidad and Tobago","Id":"TT"},{"Label":"Tristan da Cunha","Id":"TA"},{"Label":"Tunisia","Id":"TN"},{"Label":"Turkey","Id":"TR"},{"Label":"Turkmenistan","Id":"TM"},{"Label":"Turks and Caicos Islands","Id":"TC"},{"Label":"Tuvalu","Id":"TV"},{"Label":"U.S. Outlying Islands","Id":"UM"},{"Label":"U.S. Virgin Islands","Id":"VI"},{"Label":"Uganda","Id":"UG"},{"Label":"Ukraine","Id":"UA"},{"Label":"United Arab Emirates","Id":"AE"},{"Label":"United Kingdom","Id":"GB"},{"Label":"United States","Id":"US"},{"Label":"Uruguay","Id":"UY"},{"Label":"Uzbekistan","Id":"UZ"},{"Label":"Vanuatu","Id":"VU"},{"Label":"Vatican City","Id":"VA"},{"Label":"Venezuela","Id":"VE"},{"Label":"Vietnam","Id":"VN"},{"Label":"Wallis and Futuna","Id":"WF"},{"Label":"Western Sahara","Id":"EH"},{"Label":"Yemen","Id":"YE"},{"Label":"Zambia","Id":"ZM"},{"Label":"Zimbabwe","Id":"ZW"}]');

        foreach($countryDictionary as $country)
        {
            if($country->Id==$this->Country)
                return $country->Label;
        }

        return $this->Country;
    }

    public function GetText()
    {
        $formatter=new MultipleLineFormatter();

        if($this->Address1!='')
            $formatter->AddLine($this->Address1);
        if($this->Address2!='')
            $formatter->AddLine($this->Address2);
        if($this->City!='')
            $formatter->AddLine($this->City);
        if($this->State!='')
            $formatter->AddLine($this->State);
        if($this->Postal!='')
            $formatter->AddLine($this->Postal);

        if($this->Country!='')
            $formatter->AddLine($this->Country);

        return $formatter->ToText();

    }
}