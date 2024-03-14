<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Utils;

class MchHttpRequest
{
	CONST HEADER_SUCURI_WAF = 'HTTP_X_SUCURI_CLIENTIP';

	private static $sucuriCloudProxyIp = null;

	public static function getServerRequestTime($withMicroSecondPrecision = false)
	{
		static $requestTime = null;
		if(null !== $requestTime && !$withMicroSecondPrecision)
			return $requestTime;

		if($withMicroSecondPrecision && isset($_SERVER['REQUEST_TIME_FLOAT'])){
			return $_SERVER['REQUEST_TIME_FLOAT'];
		}

		return $requestTime = empty($_SERVER['REQUEST_TIME']) ? time() : $_SERVER['REQUEST_TIME'];
	}

	public static function getClientIp(array $arrTrustedProxyIps = array())
	{
		static $clientIp = 0;
		if(0 !== $clientIp )
			return $clientIp;

		// Handle NGINX Proxies
		(!empty($_SERVER['HTTP_REMOTE_ADDR']) && empty( $_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_REMOTE_ADDR'] : null;

		if(empty($_SERVER['REMOTE_ADDR']) || -1 === ($ipVersion = MchIPUtils::getIpAddressVersion($_SERVER['REMOTE_ADDR'])))
			return null;


		$arrProxyHeaders = array(
			'HTTP_X_FORWARDED_FOR',
			'HTTP_CLIENT_IP',
			'HTTP_X_REAL_IP',
			'HTTP_X_FORWARDED',
			'HTTP_FORWARDED'
		);

		if(!empty($arrTrustedProxyIps) && in_array($_SERVER['REMOTE_ADDR'], $arrTrustedProxyIps, true))
		{
			foreach ($arrProxyHeaders as $proxyHeader)
			{
				if(null !== ($clientIp = self::getClientIpAddressFromProxyHeader($proxyHeader)))
					return $clientIp;
			}
		}

		return $clientIp = $_SERVER['REMOTE_ADDR'];

	}

	public static function getClientIpAddressFromProxyHeader($proxyHeader)
	{
		if(empty($_SERVER[$proxyHeader]))
			return null;

		$arrClientIps = explode(',', $_SERVER[$proxyHeader]);

		if (empty($arrClientIps[0]))
			return null;

		$arrClientIps[0] = str_replace(' ', '', $arrClientIps[0]);

		if (preg_match('{((?:\d+\.){3}\d+)\:\d+}', $arrClientIps[0], $match))
			$arrClientIps[0] = trim($match[1]);

		return (-1 !== MchIPUtils::getIpAddressVersion($arrClientIps[0])) ? $arrClientIps[0] : null;

	}

	private static function getIpAddressFromSucuriCloudProxy()
	{
		if(empty($_SERVER['HTTP_X_SUCURI_CLIENTIP']))
			return null;

		$arrKnownSucuriIps = array('104.154.32.210'=>1,'104.154.45.156'=>1,'104.154.87.56'=>1,'104.155.10.238'=>1,'104.155.14.104'=>1,'104.155.192.51'=>1,'104.155.193.50'=>1,'104.155.196.241'=>1,'104.155.196.61'=>1,'104.155.201.239'=>1,'104.155.203.164'=>1,'104.155.21.66'=>1,'104.155.216.248'=>1,'104.155.219.236'=>1,'104.155.224.68'=>1,'104.155.232.138'=>1,'104.155.25.207'=>1,'104.155.5.0'=>1,'104.155.8.180'=>1,'106.186.124.83'=>1,'106.186.124.89'=>1,'106.186.124.93'=>1,'106.186.24.25'=>1,'106.186.30.76'=>1,'106.187.37.42'=>1,'107.167.181.64'=>1,'107.167.186.147'=>1,'107.167.188.230'=>1,'108.59.87.219'=>1,'109.74.195.21'=>1,'109.74.195.221'=>1,'130.211.110.78'=>1,'130.211.115.186'=>1,'130.211.124.3'=>1,'130.211.132.147'=>1,'130.211.146.206'=>1,'130.211.153.64'=>1,'130.211.155.136'=>1,'130.211.161.17'=>1,'130.211.168.22'=>1,'130.211.168.244'=>1,'130.211.173.11'=>1,'130.211.175.145'=>1,'130.211.187.207'=>1,'130.211.246.150'=>1,'130.211.251.190'=>1,'130.211.255.48'=>1,'130.211.49.162'=>1,'130.211.59.253'=>1,'130.211.91.237'=>1,'130.211.93.99'=>1,'130.211.98.63'=>1,'142.4.200.62'=>1,'142.4.214.85'=>1,'146.148.112.120'=>1,'146.148.117.213'=>1,'146.148.121.78'=>1,'146.148.52.221'=>1,'146.148.6.82'=>1,'146.148.67.128'=>1,'146.148.70.132'=>1,'146.148.74.167'=>1,'146.148.76.232'=>1,'146.148.88.173'=>1,'146.148.88.90'=>1,'146.148.9.252'=>1,'162.216.16.217'=>1,'162.216.19.28'=>1,'173.230.128.205'=>1,'173.230.129.138'=>1,'173.230.130.238'=>1,'173.230.135.26'=>1,'173.230.136.38'=>1,'173.230.138.214'=>1,'173.230.139.234'=>1,'173.255.193.216'=>1,'173.255.193.238'=>1,'173.255.195.55'=>1,'173.255.197.109'=>1,'173.255.228.169'=>1,'173.255.229.143'=>1,'173.255.234.105'=>1,'176.58.101.133'=>1,'176.58.101.27'=>1,'176.58.109.155'=>1,'176.58.111.141'=>1,'176.58.115.105'=>1,'176.58.127.128'=>1,'176.58.98.225'=>1,'178.79.157.63'=>1,'178.79.171.54'=>1,'178.79.174.239'=>1,'192.155.85.137'=>1,'192.155.88.106'=>1,'192.155.90.132'=>1,'192.155.94.137'=>1,'192.155.94.163'=>1,'192.155.95.252'=>1,'192.155.95.9'=>1,'192.95.62.65'=>1,'192.95.62.66'=>1,'192.95.62.67'=>1,'192.95.62.68'=>1,'192.95.62.69'=>1,'192.95.62.70'=>1,'192.95.62.71'=>1,'192.95.62.72'=>1,'192.95.62.73'=>1,'192.95.62.74'=>1,'192.95.62.75'=>1,'192.95.62.76'=>1,'192.95.62.77'=>1,'192.95.62.78'=>1,'192.99.11.177'=>1,'192.99.12.218'=>1,'192.99.14.168'=>1,'192.99.14.169'=>1,'192.99.14.40'=>1,'192.99.170.120'=>1,'192.99.20.153'=>1,'192.99.32.74'=>1,'192.99.32.75'=>1,'192.99.34.32'=>1,'192.99.34.33'=>1,'192.99.35.158'=>1,'192.99.35.164'=>1,'198.50.174.33'=>1,'198.50.174.34'=>1,'198.50.174.35'=>1,'198.50.174.36'=>1,'198.50.174.37'=>1,'198.50.174.38'=>1,'198.50.174.39'=>1,'198.50.174.40'=>1,'198.50.174.41'=>1,'198.50.174.42'=>1,'198.50.174.43'=>1,'198.50.174.44'=>1,'198.50.176.209'=>1,'198.50.176.210'=>1,'198.50.176.211'=>1,'198.50.192.128'=>1,'198.50.203.225'=>1,'198.50.203.226'=>1,'198.50.203.227'=>1,'198.50.203.228'=>1,'198.50.203.229'=>1,'198.50.203.230'=>1,'198.50.203.231'=>1,'198.50.203.232'=>1,'198.50.203.233'=>1,'198.50.203.234'=>1,'198.50.203.235'=>1,'198.50.203.236'=>1,'198.50.203.237'=>1,'198.50.203.238'=>1,'198.50.250.160'=>1,'198.58.100.4'=>1,'198.58.106.207'=>1,'198.58.107.96'=>1,'198.58.112.219'=>1,'198.58.113.167'=>1,'198.58.115.22'=>1,'198.58.116.166'=>1,'198.58.118.221'=>1,'198.58.119.164'=>1,'198.58.120.106'=>1,'198.58.122.154'=>1,'198.58.122.183'=>1,'198.58.123.76'=>1,'198.58.123.90'=>1,'198.58.123.91'=>1,'198.58.124.178'=>1,'198.58.124.59'=>1,'198.58.125.130'=>1,'198.58.125.150'=>1,'198.58.126.105'=>1,'198.58.126.231'=>1,'198.58.126.237'=>1,'198.58.127.14'=>1,'198.58.127.212'=>1,'198.58.127.232'=>1,'198.58.127.239'=>1,'198.58.127.47'=>1,'198.58.127.55'=>1,'198.58.127.56'=>1,'198.58.97.207'=>1,'198.74.50.203'=>1,'198.74.52.127'=>1,'198.74.55.221'=>1,'198.74.57.34'=>1,'198.74.60.190'=>1,'198.74.62.16'=>1,'212.71.237.244'=>1,'212.71.239.9'=>1,'212.71.250.43'=>1,'23.236.57.154'=>1,'23.236.62.44'=>1,'23.239.12.204'=>1,'23.239.13.100'=>1,'23.239.13.30'=>1,'23.239.16.158'=>1,'23.239.16.217'=>1,'23.239.16.40'=>1,'23.239.17.162'=>1,'23.239.8.183'=>1,'23.251.134.134'=>1,'23.251.141.173'=>1,'23.251.142.128'=>1,'23.251.151.227'=>1,'23.251.152.127'=>1,'23.92.18.116'=>1,'23.92.18.145'=>1,'23.92.20.215'=>1,'23.92.21.111'=>1,'23.92.22.151'=>1,'23.92.27.114'=>1,'23.92.28.9'=>1,'23.92.29.160'=>1,'23.92.29.174'=>1,'23.92.29.247'=>1,'23.92.31.179'=>1,'23.92.31.35'=>1,'23.92.31.52'=>1,'5.196.120.33'=>1,'5.196.120.34'=>1,'5.196.120.35'=>1,'5.196.120.36'=>1,'5.196.120.37'=>1,'5.196.120.38'=>1,'5.196.120.39'=>1,'5.196.120.40'=>1,'5.196.120.41'=>1,'5.196.120.42'=>1,'5.196.120.43'=>1,'5.196.120.44'=>1,'5.196.120.45'=>1,'5.196.120.46'=>1,'5.196.237.1'=>1,'5.196.237.2'=>1,'5.39.61.17'=>1,'5.39.61.18'=>1,'50.112.93.111'=>1,'50.116.21.217'=>1,'50.116.27.16'=>1,'50.116.28.203'=>1,'50.116.28.73'=>1,'50.116.31.82'=>1,'50.116.33.60'=>1,'50.116.34.228'=>1,'50.116.37.141'=>1,'50.116.4.10'=>1,'50.116.41.212'=>1,'50.116.44.172'=>1,'50.116.49.32'=>1,'50.116.50.65'=>1,'50.116.52.193'=>1,'50.116.53.73'=>1,'50.116.58.224'=>1,'50.116.9.139'=>1,'52.64.20.248'=>1,'52.64.87.93'=>1,'54.203.246.220'=>1,'54.203.246.96'=>1,'54.203.249.141'=>1,'54.206.22.37'=>1,'54.206.39.9'=>1,'54.206.81.164'=>1,'54.207.101.199'=>1,'54.207.103.31'=>1,'54.207.104.59'=>1,'54.207.7.122'=>1,'54.212.248.178'=>1,'54.244.225.118'=>1,'54.244.252.17'=>1,'54.244.254.79'=>1,'54.244.255.155'=>1,'54.244.88.211'=>1,'54.245.101.36'=>1,'54.245.113.142'=>1,'54.252.143.188'=>1,'54.66.141.167'=>1,'54.66.198.151'=>1,'54.66.202.19'=>1,'54.66.218.196'=>1,'54.66.236.138'=>1,'54.79.71.74'=>1,'54.94.131.55'=>1,'54.94.158.235'=>1,'54.94.235.73'=>1,'66.135.32.125'=>1,'66.135.32.126'=>1,'66.135.36.233'=>1,'66.135.36.243'=>1,'66.228.39.6'=>1,'66.228.50.149'=>1,'66.228.50.72'=>1,'66.228.56.74'=>1,'66.228.59.100'=>1,'66.228.59.46'=>1,'66.228.59.55'=>1,'66.228.60.40'=>1,'66.228.62.123'=>1,'69.164.212.216'=>1,'69.164.212.249'=>1,'69.164.222.186'=>1,'72.14.176.225'=>1,'72.14.176.234'=>1,'72.14.179.118'=>1,'72.14.181.33'=>1,'72.14.189.243'=>1,'74.207.225.231'=>1,'74.207.225.24'=>1,'74.207.226.15'=>1,'74.207.227.97'=>1,'74.207.233.230'=>1,'74.207.237.189'=>1,'76.74.249.198'=>1,'76.74.251.68'=>1,'76.74.251.69'=>1,'76.74.251.70'=>1,'76.74.251.71'=>1,'85.159.209.79'=>1,'88.80.184.186'=>1,'88.80.184.202'=>1,'88.80.189.62'=>1,'96.126.105.50'=>1,'96.126.114.200'=>1,'96.126.116.198'=>1,'96.126.116.39'=>1,'96.126.122.26'=>1,'96.126.123.61'=>1,'97.107.142.93'=>1);

		if(isset($arrKnownSucuriIps[$_SERVER['REMOTE_ADDR']]))
			return self::$sucuriCloudProxyIp = $_SERVER['HTTP_X_SUCURI_CLIENTIP'];

		$arrKnownSucuriIps = null;

		$hostAddress = null;
		if(!empty($_SERVER['SERVER_ADDR']))
			$hostAddress = $_SERVER['SERVER_ADDR'];
		elseif(!empty($_SERVER['LOCAL_ADDR']))
			$hostAddress = $_SERVER['LOCAL_ADDR'];
		elseif(!empty($_SERVER['SERVER_NAME']))
			$hostAddress = @gethostbyname($_SERVER['SERVER_NAME'] . '.');

		if(!MchIPUtils::isPublicIpAddress($hostAddress))
			return null;

		$hostName = @gethostbyaddr($hostAddress);

		return @preg_match('/^cloudproxy[0-9]+\.sucuri\.net$/', $hostName) ? self::$sucuriCloudProxyIp = $_SERVER['HTTP_X_SUCURI_CLIENTIP'] : null;

	}

	private static function getIpAddressFromRackSpace()
	{
		if(0 !== strpos($_SERVER['REMOTE_ADDR'], '10.'))
			return null;

		$arrProxyHeaders = array('HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_X_FORWARDED_FOR');

		foreach($arrProxyHeaders as $proxyHeader)
		{
			if(empty($_SERVER[$proxyHeader]))
				continue;

			if(null === ($ipAddress = self::getClientIpAddressFromProxyHeader($proxyHeader)))
				continue;

			if( ! MchGdbcTrustedIPRanges::isIPInRackSpaceRanges($_SERVER['REMOTE_ADDR'], MchIPUtils::getIpAddressVersion($_SERVER['REMOTE_ADDR'])) )
				continue;

			return $ipAddress;
		}

		return null;
	}

	private static function getIpAddressFromIncapsula()
	{
		if(empty($_SERVER['HTTP_INCAP_CLIENT_IP']) || (-1 === ($ipVersion = MchIPUtils::getIpAddressVersion($_SERVER['HTTP_INCAP_CLIENT_IP']))))
			return null;

		return MchGdbcTrustedIPRanges::isIPInIncapsulaRanges( $_SERVER['REMOTE_ADDR'], MchIPUtils::getIpAddressVersion($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['HTTP_INCAP_CLIENT_IP'] : null;

	}

	private static function getIpAddressFromCloudFlare()
	{
		static $ipAddress = 0;

		if(0 !== $ipAddress)
			return $ipAddress;

		if(empty($_SERVER['HTTP_CF_CONNECTING_IP']) || -1 === ($ipVersion = MchIPUtils::getIpAddressVersion($_SERVER['HTTP_CF_CONNECTING_IP'])))
			return $ipAddress = null;

		return $ipAddress = MchGdbcTrustedIPRanges::isIPInCloudFlareRanges( $_SERVER['REMOTE_ADDR'], MchIPUtils::getIpAddressVersion($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : null;

	}


	private static function getIpAddressFromAmazonEC2()
	{
		if(null === ($proxyIpAddress = self::getClientIpAddressFromProxyHeader('HTTP_X_FORWARDED_FOR')))
			return null;


		if( ! MchIPUtils::isPublicIpAddress($_SERVER['REMOTE_ADDR']) && MchIPUtils::isPublicIpAddress($proxyIpAddress) )
		{
			$isAmazon =  ( !empty($_SERVER['SERVER_SOFTWARE'])  && (false !== stripos($_SERVER['SERVER_SOFTWARE'], 'Amazon')) ) ? true : ( !empty($_SERVER['SERVER_SIGNATURE'])  && (false !== stripos($_SERVER['SERVER_SIGNATURE'], 'Amazon')) );

			if($isAmazon) {
				return $proxyIpAddress;
			}
		}


		return MchGdbcTrustedIPRanges::isIPInAmazonEC2Ranges( $_SERVER['REMOTE_ADDR'], MchIPUtils::getIpAddressVersion($_SERVER['REMOTE_ADDR']) ) ? $proxyIpAddress : null;
	}

	private static function getIpAddressFromAmazonCloudFront()
	{
		if( !empty($_SERVER['HTTP_X_AMZ_CF_ID']) || (!empty($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] === 'Amazon CloudFront') || (!empty($_SERVER['HTTP_VIA']) &&  false !== stripos($_SERVER['HTTP_VIA'], 'CloudFront')) )
		{

			if(null === ($proxyIpAddress = self::getClientIpAddressFromProxyHeader('HTTP_X_FORWARDED_FOR')))
				return null;

			return MchGdbcTrustedIPRanges::isIPInAmazonCloudFrontRanges( $_SERVER['REMOTE_ADDR'], MchIPUtils::getIpAddressVersion($_SERVER['REMOTE_ADDR']) ) ? $proxyIpAddress : null;

		}

		return null;
	}

}