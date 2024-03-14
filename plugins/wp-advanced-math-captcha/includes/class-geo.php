<?php

class MathCaptcha_GEO
{

    private static $DATA_SECTION_SEPARATOR_SIZE = 16;
    private static $METADATA_START_MARKER = "\xAB\xCD\xEFMaxMind.com";
    private static $METADATA_START_MARKER_LENGTH = 14;
    private static $METADATA_MAX_SIZE = 131072; // 128 * 1024 = 128KB

    private $decoder;
    private $fileHandle;
    private $fileSize;
    private $ipV4Start;
    private $metadata;
    private $fileStream;
    private $pointerBase;
    // This is only used for unit testing
    private $pointerTestHack;
    private $switchByteOrder;

    private $types = array(
        0 => 'extended',
        1 => 'pointer',
        2 => 'utf8_string',
        3 => 'double',
        4 => 'bytes',
        5 => 'uint16',
        6 => 'uint32',
        7 => 'map',
        8 => 'int32',
        9 => 'uint64',
        10 => 'uint128',
        11 => 'array',
        12 => 'container',
        13 => 'end_marker',
        14 => 'boolean',
        15 => 'float',
    );
	
	private $map = array(
	'A1' => "Anonymous Proxy",
	'A2' => "Satellite Provider",
	'O1' => "Other Country",
	'AD' => "Andorra",
	'AE' => "United Arab Emirates",
	'AF' => "Afghanistan",
	'AX' => "Aland Islands",
	'AG' => "Antigua and Barbuda",
	'AI' => "Anguilla",
	'AL' => "Albania",
	'AM' => "Armenia",
	'AO' => "Angola",
	'AP' => "Asia/Pacific Region",
	'AQ' => "Antarctica",
	'AR' => "Argentina",
	'AS' => "American Samoa",
	'AT' => "Austria",
	'AU' => "Australia",
	'AW' => "Aruba",
	'AX' => "Aland Islands",
	'AZ' => "Azerbaijan",
	'BA' => "Bosnia and Herzegovina",
	'BB' => "Barbados",
	'BD' => "Bangladesh",
	'BE' => "Belgium",
	'BF' => "Burkina Faso",
	'BG' => "Bulgaria",
	'BH' => "Bahrain",
	'BI' => "Burundi",
	'BJ' => "Benin",
	'BL' => "Saint Bartelemey",
	'BM' => "Bermuda",
	'BN' => "Brunei Darussalam",
	'BO' => "Bolivia",
	'BQ' => "Bonaire, Saint Eustatius and Saba",
	'BR' => "Brazil",
	'BS' => "Bahamas",
	'BT' => "Bhutan",
	'BV' => "Bouvet Island",
	'BW' => "Botswana",
	'BY' => "Belarus",
	'BZ' => "Belize",
	'CA' => "Canada",
	'CC' => "Cocos (Keeling) Islands",
	'CD' => "Congo, The Democratic Republic of the",
	'CF' => "Central African Republic",
	'CG' => "Congo",
	'CH' => "Switzerland",
	'CI' => "Cote d'Ivoire",
	'CK' => "Cook Islands",
	'CL' => "Chile",
	'CM' => "Cameroon",
	'CN' => "China",
	'CO' => "Colombia",
	'CR' => "Costa Rica",
	'CU' => "Cuba",
	'CV' => "Cape Verde",
	'CW' => "Curacao",
	'CX' => "Christmas Island",
	'CY' => "Cyprus",
	'CZ' => "Czech Republic",
	'DE' => "Germany",
	'DJ' => "Djibouti",
	'DK' => "Denmark",
	'DM' => "Dominica",
	'DO' => "Dominican Republic",
	'DZ' => "Algeria",
	'EC' => "Ecuador",
	'EE' => "Estonia",
	'EG' => "Egypt",
	'EH' => "Western Sahara",
	'ER' => "Eritrea",
	'ES' => "Spain",
	'ET' => "Ethiopia",
	'EU' => "Europe",
	'FI' => "Finland",
	'FJ' => "Fiji",
	'FK' => "Falkland Islands (Malvinas)",
	'FM' => "Micronesia, Federated States of",
	'FO' => "Faroe Islands",
	'FR' => "France",
	'GA' => "Gabon",
	'GB' => "United Kingdom",
	'GD' => "Grenada",
	'GE' => "Georgia",
	'GF' => "French Guiana",
	'GG' => "Guernsey",
	'GH' => "Ghana",
	'GI' => "Gibraltar",
	'GL' => "Greenland",
	'GM' => "Gambia",
	'GN' => "Guinea",
	'GP' => "Guadeloupe",
	'GQ' => "Equatorial Guinea",
	'GR' => "Greece",
	'GS' => "South Georgia and the South Sandwich Islands",
	'GT' => "Guatemala",
	'GU' => "Guam",
	'GW' => "Guinea-Bissau",
	'GY' => "Guyana",
	'HK' => "Hong Kong",
	'HM' => "Heard Island and McDonald Islands",
	'HN' => "Honduras",
	'HR' => "Croatia",
	'HT' => "Haiti",
	'HU' => "Hungary",
	'ID' => "Indonesia",
	'IE' => "Ireland",
	'IM' => "Isle of Man",
	'IL' => "Israel",
	'IM' => "Isle of Man",
	'IN' => "India",
	'IO' => "British Indian Ocean Territory",
	'IQ' => "Iraq",
	'IR' => "Iran, Islamic Republic of",
	'IS' => "Iceland",
	'IT' => "Italy",
	'JE' => "Jersey",
	'JM' => "Jamaica",
	'JO' => "Jordan",
	'JP' => "Japan",
	'KE' => "Kenya",
	'KG' => "Kyrgyzstan",
	'KH' => "Cambodia",
	'KI' => "Kiribati",
	'KM' => "Comoros",
	'KN' => "Saint Kitts and Nevis",
	'KP' => "Korea, Democratic People's Republic of",
	'KR' => "Korea, Republic of",
	'KW' => "Kuwait",
	'KY' => "Cayman Islands",
	'KZ' => "Kazakhstan",
	'LA' => "Lao People's Democratic Republic",
	'LB' => "Lebanon",
	'LC' => "Saint Lucia",
	'LI' => "Liechtenstein",
	'LK' => "Sri Lanka",
	'LR' => "Liberia",
	'LS' => "Lesotho",
	'LT' => "Lithuania",
	'LU' => "Luxembourg",
	'LV' => "Latvia",
	'LY' => "Libyan Arab Jamahiriya",
	'MA' => "Morocco",
	'MC' => "Monaco",
	'MD' => "Moldova, Republic of",
	'ME' => "Montenegro",
	'MF' => "Saint Martin",
	'MG' => "Madagascar",
	'MH' => "Marshall Islands",
	'MK' => "Macedonia",
	'ML' => "Mali",
	'MM' => "Myanmar",
	'MN' => "Mongolia",
	'MO' => "Macao",
	'MP' => "Northern Mariana Islands",
	'MQ' => "Martinique",
	'MR' => "Mauritania",
	'MS' => "Montserrat",
	'MT' => "Malta",
	'MU' => "Mauritius",
	'MV' => "Maldives",
	'MW' => "Malawi",
	'MX' => "Mexico",
	'MY' => "Malaysia",
	'MZ' => "Mozambique",
	'NA' => "Namibia",
	'NC' => "New Caledonia",
	'NE' => "Niger",
	'NF' => "Norfolk Island",
	'NG' => "Nigeria",
	'NI' => "Nicaragua",
	'NL' => "Netherlands",
	'NO' => "Norway",
	'NP' => "Nepal",
	'NR' => "Nauru",
	'NU' => "Niue",
	'NZ' => "New Zealand",
	'OM' => "Oman",
	'PA' => "Panama",
	'PE' => "Peru",
	'PF' => "French Polynesia",
	'PG' => "Papua New Guinea",
	'PH' => "Philippines",
	'PK' => "Pakistan",
	'PL' => "Poland",
	'PM' => "Saint Pierre and Miquelon",
	'PN' => "Pitcairn",
	'PR' => "Puerto Rico",
	'PS' => "Palestinian Territory",
	'PT' => "Portugal",
	'PW' => "Palau",
	'PY' => "Paraguay",
	'QA' => "Qatar",
	'RE' => "Reunion",
	'RO' => "Romania",
	'RS' => "Serbia",
	'RU' => "Russian Federation",
	'RW' => "Rwanda",
	'SA' => "Saudi Arabia",
	'SB' => "Solomon Islands",
	'SC' => "Seychelles",
	'SD' => "Sudan",
	'SE' => "Sweden",
	'SG' => "Singapore",
	'SH' => "Saint Helena",
	'SI' => "Slovenia",
	'SJ' => "Svalbard and Jan Mayen",
	'SK' => "Slovakia",
	'SL' => "Sierra Leone",
	'SM' => "San Marino",
	'SN' => "Senegal",
	'SO' => "Somalia",
	'SR' => "Suriname",
	'ST' => "Sao Tome and Principe",
	'SV' => "El Salvador",
	'SX' => "Sint Maarten",
	'SY' => "Syrian Arab Republic",
	'SZ' => "Swaziland",
	'TC' => "Turks and Caicos Islands",
	'TD' => "Chad",
	'TF' => "French Southern Territories",
	'TG' => "Togo",
	'TH' => "Thailand",
	'TJ' => "Tajikistan",
	'TK' => "Tokelau",
	'TL' => "Timor-Leste",
	'TM' => "Turkmenistan",
	'TN' => "Tunisia",
	'TO' => "Tonga",
	'TR' => "Turkey",
	'TT' => "Trinidad and Tobago",
	'TV' => "Tuvalu",
	'TW' => "Taiwan",
	'TZ' => "Tanzania, United Republic of",
	'TL' => "Timor-Leste",
	'UA' => "Ukraine",
	'UG' => "Uganda",
	'UM' => "United States Minor Outlying Islands",
	'US' => "United States",
	'UY' => "Uruguay",
	'UZ' => "Uzbekistan",
	'VA' => "Holy See (Vatican City State)",
	'VC' => "Saint Vincent and the Grenadines",
	'VE' => "Venezuela",
	'VG' => "Virgin Islands, British",
	'VI' => "Virgin Islands, U.S.",
	'VN' => "Vietnam",
	'VU' => "Vanuatu",
	'WF' => "Wallis and Futuna",
	'WS' => "Samoa",
	'YE' => "Yemen",
	'YT' => "Mayotte",
	'ZA' => "South Africa",
	'ZM' => "Zambia",
	'ZW' => "Zimbabwe"
	);	
	
	
	
    public function __construct()
    {
		$database = dirname(__FILE__).'/geo.mmdb';

        if (!is_readable($database)) return false;
		
        $this->fileHandle = @fopen($database, 'rb');
        if ($this->fileHandle === false) return false;
		
        $this->fileSize = @filesize($database);
        if ($this->fileSize === false) return false;

        $start = $this->findMetadataStart($database);
        $this->decoderFunc($this->fileHandle, $start);
        list($metadataArray) = $this->decode($start);
        $this->metadata = $this->setMetaData($metadataArray);
        $this->decoder = $this->decoderFunc(
            $this->fileHandle,
            $this->metadata->searchTreeSize + self::$DATA_SECTION_SEPARATOR_SIZE
        );
    }

	public function getNameByCountryCode($code){
		if(isset($this->map[$code])){
			return $this->map[$code];
		} else {
			return '';
		}
	}

	public function getCountryMapList(){
		$list = $this->map;
		unset($list['A1']);
		unset($list['A2']);
		unset($list['O1']);
		
		return $list;
	}	


    public function GetSessionIP()
    {
        $ip_address = $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER["HTTP_X_REAL_IP"]) && filter_var($_SERVER["HTTP_X_REAL_IP"], FILTER_VALIDATE_IP)) $ip_address = $_SERVER["HTTP_X_REAL_IP"];
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && filter_var($_SERVER["HTTP_X_FORWARDED_FOR"], FILTER_VALIDATE_IP)) $ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) && filter_var($_SERVER["HTTP_CF_CONNECTING_IP"], FILTER_VALIDATE_IP)) $ip_address = $_SERVER["HTTP_CF_CONNECTING_IP"];

        return $ip_address;
    }
    
    public function checkIP_in_List($ipAddress, $list = array())
    {
        if (count($list) > 0)
        {
            if ($ipAddress === false) $ipAddress = $this->GetSessionIP();
            
            foreach ($list as $rule_ip)
            {
                $rule_ip = trim($rule_ip);
                
                if (strpos($rule_ip, '/')) {
                	$ip_long = ip2long($ipAddress);
                	$range = array();
                	$rule_ip = explode('/', $rule_ip);
                	$range[0] = long2ip((ip2long($rule_ip[0])) & ((-1 << (32 - (int)$rule_ip[1]))));
                	$range[1] = long2ip((ip2long($range[0])) + pow(2, (32 - (int)$rule_ip[1])) - 1);
                	if ($ip_long >= ip2long($range[0]) && $ip_long <= ip2long($range[1])) return true;
                  
                } else {
    				$tmp_i = stripos($ipAddress, $rule_ip);
    
    				if ( $tmp_i !== false && $tmp_i == 0) {
    					// match
    					return true;
    				}
    			}
            }
            
        }
        else return false;
    }


    public function getCountryByIP($ipAddress)
    {
        if ($ipAddress === false) $ipAddress = $this->GetSessionIP();
        if (strpos($this->metadata()->databaseType, 'Country') === false) return false;
        $record = $this->get($ipAddress);
        if ($record === null) return false;
        if (!is_array($record)) return false;

        return $record['country']['iso_code'];
    }
	
    public function get($ipAddress)
    {
        if (func_num_args() !== 1) return false;

        if (!is_resource($this->fileHandle)) return false;

        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) return false;

        if ($this->metadata->ipVersion === 4 && strrpos($ipAddress, ':')) return false;
        $pointer = $this->findAddressInTree($ipAddress);
        if ($pointer === 0) {
            return null;
        }

        return $this->resolveDataPointer($pointer);
    }

    private function findAddressInTree($ipAddress)
    {
        $rawAddress = array_merge(unpack('C*', inet_pton($ipAddress)));

        $bitCount = count($rawAddress) * 8;

        $node = $this->startNode($bitCount);

        for ($i = 0; $i < $bitCount; $i++) {
            if ($node >= $this->metadata->nodeCount) {
                break;
            }
            $tempBit = 0xFF & $rawAddress[$i >> 3];
            $bit = 1 & ($tempBit >> 7 - ($i % 8));

            $node = $this->readNode($node, $bit);
        }
        if ($node === $this->metadata->nodeCount) {
            return 0;
        } elseif ($node > $this->metadata->nodeCount) {
            return $node;
        }
        return false;
    }

    private function startNode($length)
    {

        if ($this->metadata->ipVersion === 6 && $length === 32) {
            return $this->ipV4StartNode();
        }

        return 0;
    }

    private function ipV4StartNode()
    {

        if ($this->metadata->ipVersion === 4) {
            return 0;
        }

        if ($this->ipV4Start) {
            return $this->ipV4Start;
        }
        $node = 0;

        for ($i = 0; $i < 96 && $node < $this->metadata->nodeCount; $i++) {
            $node = $this->readNode($node, 0);
        }
        $this->ipV4Start = $node;

        return $node;
    }

    private function readNode($nodeNumber, $index)
    {
        $baseOffset = $nodeNumber * $this->metadata->nodeByteSize;

        switch ($this->metadata->recordSize) {
            case 24:
                $bytes = $this->read($this->fileHandle, $baseOffset + $index * 3, 3);
                list(, $node) = unpack('N', "\x00" . $bytes);

                return $node;
            case 28:
                $middleByte = $this->read($this->fileHandle, $baseOffset + 3, 1);
                list(, $middle) = unpack('C', $middleByte);
                if ($index === 0) {
                    $middle = (0xF0 & $middle) >> 4;
                } else {
                    $middle = 0x0F & $middle;
                }
                $bytes = $this->read($this->fileHandle, $baseOffset + $index * 4, 3);
                list(, $node) = unpack('N', chr($middle) . $bytes);

                return $node;
            case 32:
                $bytes = $this->read($this->fileHandle, $baseOffset + $index * 4, 4);
                list(, $node) = unpack('N', $bytes);

                return $node;
            default:
                return false;
        }
    }

    private function resolveDataPointer($pointer)
    {
        $resolved = $pointer - $this->metadata->nodeCount
            + $this->metadata->searchTreeSize;
        if ($resolved > $this->fileSize) return false;

        list($data) = $this->decode($resolved);

        return $data;
    }

    private function findMetadataStart($filename)
    {
        $handle = $this->fileHandle;
        $fstat = fstat($handle);
        $fileSize = $fstat['size'];
        $marker = self::$METADATA_START_MARKER;
        $markerLength = self::$METADATA_START_MARKER_LENGTH;
        $metadataMaxLengthExcludingMarker
            = min(self::$METADATA_MAX_SIZE, $fileSize) - $markerLength;

        for ($i = 0; $i <= $metadataMaxLengthExcludingMarker; $i++) {
            for ($j = 0; $j < $markerLength; $j++) {
                fseek($handle, $fileSize - $i - $j - 1);
                $matchBit = fgetc($handle);
                if ($matchBit !== $marker[$markerLength - $j - 1]) {
                    continue 2;
                }
            }

            return $fileSize - $i;
        }
        return false;
    }

    public function metadata()
    {
        if (func_num_args()) return false;
        if (!is_resource($this->fileHandle)) return false;

        return $this->metadata;
    }

    public function close()
    {
        if (!is_resource($this->fileHandle)) return false;
        fclose($this->fileHandle);
    }
	


    public function setMetaData($metadata)
    {
        $this->metadata = new stdclass();
        $this->metadata->binaryFormatMajorVersion = $metadata['binary_format_major_version'];
        $this->metadata->binaryFormatMinorVersion = $metadata['binary_format_minor_version'];
        $this->metadata->buildEpoch = $metadata['build_epoch'];
        $this->metadata->databaseType = $metadata['database_type'];
        $this->metadata->languages = $metadata['languages'];
        $this->metadata->description = $metadata['description'];
        $this->metadata->ipVersion = $metadata['ip_version'];
        $this->metadata->nodeCount = $metadata['node_count'];
        $this->metadata->recordSize = $metadata['record_size'];
        $this->metadata->nodeByteSize = $this->metadata->recordSize / 4;
        $this->metadata->searchTreeSize = $this->metadata->nodeCount * $this->metadata->nodeByteSize;
		return $this->metadata;
    }

    public function read($stream, $offset, $numberOfBytes)
    {
        if ($numberOfBytes === 0) {
            return '';
        }
        if (fseek($stream, $offset) === 0) {
            $value = fread($stream, $numberOfBytes);

            if (ftell($stream) - $offset === $numberOfBytes) {
                return $value;
            }
        }
        return false;
    }

    public function decoderFunc(
        $fileStream,
        $pointerBase = 0,
        $pointerTestHack = false
    ) {
        $this->fileStream = $fileStream;
        $this->pointerBase = $pointerBase;
        $this->pointerTestHack = $pointerTestHack;

        $this->switchByteOrder = $this->isPlatformLittleEndian();
    }

    public function decode($offset)
    {
        list(, $ctrlByte) = unpack(
            'C',
            $this->read($this->fileStream, $offset, 1)
        );
        $offset++;

        $type = $this->types[$ctrlByte >> 5];

        if ($type === 'pointer') {
            list($pointer, $offset) = $this->decodePointer($ctrlByte, $offset);

            // for unit testing
            if ($this->pointerTestHack) {
                return array($pointer);
            }

            list($result) = $this->decode($pointer);

            return array($result, $offset);
        }

        if ($type === 'extended') {
            list(, $nextByte) = unpack(
                'C',
                $this->read($this->fileStream, $offset, 1)
            );

            $typeNum = $nextByte + 7;

            if ($typeNum < 8) return false;

            $type = $this->types[$typeNum];
            $offset++;
        }

        list($size, $offset) = $this->sizeFromCtrlByte($ctrlByte, $offset);

        return $this->decodeByType($type, $offset, $size);
    }

    private function decodeByType($type, $offset, $size)
    {
        switch ($type) {
            case 'map':
                return $this->decodeMap($size, $offset);
            case 'array':
                return $this->decodeArray($size, $offset);
            case 'boolean':
                return array($this->decodeBoolean($size), $offset);
        }

        $newOffset = $offset + $size;
        $bytes = $this->read($this->fileStream, $offset, $size);
        switch ($type) {
            case 'utf8_string':
                return array($this->decodeString($bytes), $newOffset);
            case 'double':
                $this->verifySize(8, $size);

                return array($this->decodeDouble($bytes), $newOffset);
            case 'float':
                $this->verifySize(4, $size);

                return array($this->decodeFloat($bytes), $newOffset);
            case 'bytes':
                return array($bytes, $newOffset);
            case 'uint16':
            case 'uint32':
                return array($this->decodeUint($bytes), $newOffset);
            case 'int32':
                return array($this->decodeInt32($bytes), $newOffset);
            case 'uint64':
            case 'uint128':
                return array($this->decodeBigUint($bytes, $size), $newOffset);
            default:
                return false;
        }
    }

    private function verifySize($expected, $actual)
    {
        if ($expected !== $actual) return false;
    }

    private function decodeArray($size, $offset)
    {
        $array = array();

        for ($i = 0; $i < $size; $i++) {
            list($value, $offset) = $this->decode($offset);
            array_push($array, $value);
        }

        return array($array, $offset);
    }

    private function decodeBoolean($size)
    {
        return $size === 0 ? false : true;
    }

    private function decodeDouble($bits)
    {
        // XXX - Assumes IEEE 754 double on platform
        list(, $double) = unpack('d', $this->maybeSwitchByteOrder($bits));

        return $double;
    }

    private function decodeFloat($bits)
    {
        // XXX - Assumes IEEE 754 floats on platform
        list(, $float) = unpack('f', $this->maybeSwitchByteOrder($bits));

        return $float;
    }

    private function decodeInt32($bytes)
    {
        $bytes = $this->zeroPadLeft($bytes, 4);
        list(, $int) = unpack('l', $this->maybeSwitchByteOrder($bytes));

        return $int;
    }

    private function decodeMap($size, $offset)
    {
        $map = array();

        for ($i = 0; $i < $size; $i++) {
            list($key, $offset) = $this->decode($offset);
            list($value, $offset) = $this->decode($offset);
            $map[$key] = $value;
        }

        return array($map, $offset);
    }

    private $pointerValueOffset = array(
        1 => 0,
        2 => 2048,
        3 => 526336,
        4 => 0,
    );

    private function decodePointer($ctrlByte, $offset)
    {
        $pointerSize = (($ctrlByte >> 3) & 0x3) + 1;

        $buffer = $this->read($this->fileStream, $offset, $pointerSize);
        $offset = $offset + $pointerSize;

        $packed = $pointerSize === 4
            ? $buffer
            : (pack('C', $ctrlByte & 0x7)) . $buffer;

        $unpacked = $this->decodeUint($packed);
        $pointer = $unpacked + $this->pointerBase
            + $this->pointerValueOffset[$pointerSize];

        return array($pointer, $offset);
    }

    private function decodeUint($bytes)
    {
        list(, $int) = unpack('N', $this->zeroPadLeft($bytes, 4));

        return $int;
    }

    private function decodeBigUint($bytes, $byteLength)
    {
        $maxUintBytes = log(PHP_INT_MAX, 2) / 8;

        if ($byteLength === 0) {
            return 0;
        }

        $numberOfLongs = ceil($byteLength / 4);
        $paddedLength = $numberOfLongs * 4;
        $paddedBytes = $this->zeroPadLeft($bytes, $paddedLength);
        $unpacked = array_merge(unpack("N$numberOfLongs", $paddedBytes));

        $integer = 0;

        $twoTo32 = '4294967296';

        foreach ($unpacked as $part) {
            if ($byteLength <= $maxUintBytes) {
                $integer = ($integer << 32) + $part;
            } elseif (extension_loaded('gmp')) {
                $integer = gmp_strval(gmp_add(gmp_mul($integer, $twoTo32), $part));
            } elseif (extension_loaded('bcmath')) {
                $integer = bcadd(bcmul($integer, $twoTo32), $part);
            } else return false;
        }

        return $integer;
    }

    private function decodeString($bytes)
    {
        return $bytes;
    }

    private function sizeFromCtrlByte($ctrlByte, $offset)
    {
        $size = $ctrlByte & 0x1f;
        $bytesToRead = $size < 29 ? 0 : $size - 28;
        $bytes = $this->read($this->fileStream, $offset, $bytesToRead);
        $decoded = $this->decodeUint($bytes);

        if ($size === 29) {
            $size = 29 + $decoded;
        } elseif ($size === 30) {
            $size = 285 + $decoded;
        } elseif ($size > 30) {
            $size = ($decoded & (0x0FFFFFFF >> (32 - (8 * $bytesToRead))))
                + 65821;
        }

        return array($size, $offset + $bytesToRead);
    }

    private function zeroPadLeft($content, $desiredLength)
    {
        return str_pad($content, $desiredLength, "\x00", STR_PAD_LEFT);
    }

    private function maybeSwitchByteOrder($bytes)
    {
        return $this->switchByteOrder ? strrev($bytes) : $bytes;
    }

    private function isPlatformLittleEndian()
    {
        $testint = 0x00FF;
        $packed = pack('S', $testint);

        return $testint === current(unpack('v', $packed));
    }


}