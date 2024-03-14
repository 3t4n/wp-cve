<?php
namespace Iriven;
use ZipArchive;
use SplFileObject;

/**
 * Class GeoIPCountry
 * @package Iriven\GeoIPCountry
 */
class GeoIPCountry
{
    const DS = DIRECTORY_SEPARATOR;
    private $DataLocation = null;
    private $IsoCode = null;

    /**
     * GeoIPCountry constructor.
     */
    public function __construct()
    {
        $this->PackageLocation = realpath($this->getStoragePath());
        $this->DataLocation  = realpath($this->getStoragePath(false));
        return $this;
    }

    /**
     * @return $this
     */
    public function Admin()
    {
        $this->EditModeEnabled = true;
        return $this;
    }

    /**
     * If IPV6, Returns the IP in it's fullest format.
     * @example
     *          ::1              => 0000:0000:0000:0000:0000:0000:0000:0001
     *          220F::127.0.0.1  => 220F:0000:0000:0000:0000:0000:7F00:0001
     *          2F:A1::1         => 002F:00A1:0000:0000:0000:0000:0000:0001
     * @param $Ip
     * @return mixed|string
     */
    private function ExpandIPAddress($Ip)
    {
        if (strpos($Ip, ':') !== false) // IPv6 address
        {
            $hex = unpack('H*hex', inet_pton($Ip));
            $Ip = substr(preg_replace('/([A-f0-9]{4})/', "$1:", $hex['hex']), 0, -1);
            $Ip = strtoupper($Ip);
        }
        return $Ip;
    }
    /**
     * @param $ip
     * @return null|string
     */
    private function getIPRangeProviderFile($ip)
    {
        try
        {
            if(!preg_match('/[.:]/', $ip)) $ip = $this->long2ip($ip, false);
            if(!filter_var($ip,FILTER_VALIDATE_IP,[FILTER_FLAG_IPV4|FILTER_FLAG_IPV6]))
                throw new \Exception('Invalid IP given');
            $delimiter = (strpos($ip,':')===false)? '.' : ':';
            $DBfile = current(explode($delimiter,$ip)).'.php';
            return $DBfile;
        }
        catch (\Exception $e)
        {
            trigger_error($e->getMessage());
        }
        return null;
    }
    /**
     * @param bool $isArchive
     * @return string
     */
    private function getStoragePath($isArchive=true)
    {
        $tmp = ini_get('upload_tmp_dir')?:sys_get_temp_dir ();
        $isArchive OR $tmp = rtrim(__DIR__, self::DS);
        try{
            if (!is_writeable($tmp))
                throw new \Exception(sprintf('The required destination path is not writable: %s', $tmp));
        }
        catch(\Exception $e)
        {
            trigger_error($e->getMessage(),E_USER_ERROR);
        }
        $tmp .= self::DS.($isArchive? 'GeoIPCountry' : 'GeoIPDatas');
        if(!is_dir($tmp)) mkdir($tmp,'0755', true);
           return $tmp;
    }
    /**
     * Convert both IPV4 and IPv6 address to an integer
     * @param $Ip
     * @return mixed|string
     */
    private function ip2long($Ip)
    {
        $decimal = null;
       $Ip = $this->ExpandIPAddress($Ip);
        try
        {
            switch ($Ip):
                case (strpos($Ip, '.') !== false):
                    if(!filter_var($Ip,FILTER_VALIDATE_IP,[FILTER_FLAG_IPV4]))
                        throw new \Exception('Invalid IPV4 given');
                    $decimal .= ip2long($Ip);
                    break;
                case (strpos($Ip, ':') !== false):
                    if(!filter_var($Ip,FILTER_VALIDATE_IP,[FILTER_FLAG_IPV6]))
                        throw new \Exception('Invalid IPV6 given');
                    $network = inet_pton($Ip);
                    $parts   = unpack('C*', $network);
                    foreach ($parts as &$byte)
                        $decimal.= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
                    break;
                default:
                    throw new \Exception($Ip.' is not a valid IP address');
                    break;
            endswitch;
        }
        catch (\Exception $e)
        {
            trigger_error($e->getMessage(),E_USER_ERROR);
        }
        return $decimal;
    }
    /**
     * Convert an IP address from decimal format to presentation format
     *
     * @param $decimal
     * @param bool $compress
     * @return mixed|string
     */
    private function long2ip($decimal,$compress = true)
    {
        $Ip = null;
        if(preg_match('/[.:]/', $decimal))
            return strtoupper($decimal);
        switch ($decimal):
            case (strlen($decimal) <= 32):
                $Ip .= long2ip($decimal);
                break;
            default:
                $pad = 128 - strlen($decimal);
                for ($i = 1; $i <= $pad; $i++)
                    $decimal = '0'.$decimal;
                for ($bits = 0; $bits <= 7; $bits++)
                {
                    $binPart = substr($decimal,($bits*16),16);
                    $Ip .= dechex(bindec($binPart)).':';
                }
                $Ip = inet_ntop(inet_pton(substr($Ip,0,-1)));
                break;
        endswitch;
            $Ip = strtoupper($Ip);
        return $compress? $Ip : $this->ExpandIPAddress($Ip);
    }
    /**
     * @param null $ip
     * @return bool
     */
    public function isReservedIP($ip=null)
    {
        if($ip) $this->resolve($ip);
        return !$this->IsoCode OR strcasecmp($this->IsoCode,'ZZ') == 0 ;
    }

    /**
     * @param null $ip
     * @return null|string
     */
    public function resolve($ip = null)
    {
        try
        {
            $ip OR $ip = $this->getRemoteIP();
            if(!preg_match('/[.:]/', $ip)) $ip = $this->long2ip($ip);
            $ip = $this->ExpandIPAddress($ip);
            if(!filter_var($ip,FILTER_VALIDATE_IP,[FILTER_FLAG_IPV4|FILTER_FLAG_IPV6]))
                throw new \Exception('Invalid IP given');
            $ipFilename = $this->getIPRangeProviderFile($ip);
            $ipLong = $this->ip2long($ip);
            $ipFilePath = realpath($this->DataLocation.self::DS.$ipFilename);
            if(!file_exists($ipFilePath))
                throw new \Exception('IP Ranges provider file not found');
            $IpRanges = include $ipFilePath;
            foreach($IpRanges as $Range):
                if(!is_array($Range) OR sizeof($Range) !== 3) continue;
                if(preg_match('/^[01]+$/', $ipLong))
                {
                    $Range[0] = $this->ip2long($Range[0]);
                    $Range[1] = $this->ip2long($Range[1]);
                }
                if($Range[1] < $ipLong) continue;
                if(($Range[0]<=$ipLong))
                {
                    $this->IsoCode = $Range[2]?:'ZZ';
                    break;
                }
            endforeach;
        }
        catch (\Exception $e)
        {
            trigger_error($e->getMessage());
        }
        return $this->IsoCode;
    }

    /**
     * Auto Get the current visitor IP Address
     * @return string
     */
    private function getRemoteIP()
    {
        $ip = '';
        $serverIPKeys =['HTTP_X_COMING_FROM', 'HTTP_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_X_CLUSTER_CLIENT_IP',
                        'HTTP_X_FORWARDED', 'HTTP_VIA', 'HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR'];
        foreach ($serverIPKeys AS $IPKey) {
            if(array_key_exists($IPKey,$_SERVER))
            {
                if (!strlen($_SERVER[$IPKey])) continue;
                $ip = $_SERVER[$IPKey];
                break;
            }
        }
        if (($CommaPos = strpos($ip, ',')) > 0) {
            $ip = substr($ip, 0, ($CommaPos - 1));
        }            
        return $ip?:'0.0.0.0';
    }
}
