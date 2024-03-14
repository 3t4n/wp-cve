<?php
class CoinmotionGetCurrencies{
	/*
	*   CONSTANTS  
	*/

	public const CURRENCIES_API_URL = 'https://v6.exchangerate-api.com/v6/2d027645e315e1db84b23602/latest/EUR';
	public const OPTION_KEY = "coinmotion_currencies";

	public function getCurrencies(): void
    {
		$actual_option = get_option(self::OPTION_KEY, false);

		if (!$actual_option ||
            $actual_option === "1" ||
            !isset($actual_option['time_last_update_unix']) ||
            (date('Y-m-d', $actual_option['time_last_update_unix']) !== date('Y-m-d')))
            {
			$data = $this->callToAPI();
            $data['last_update'] = date('Y-m-d H:i:s');
			//ksort($data['conversion_rates']);
			update_option(self::OPTION_KEY, $data);
		}
	}

	public function getCotization($currency){
		
		$currencies = get_option(self::OPTION_KEY);
		if (($currencies === false) || ((int)$currencies === 1)){
			$this->getCurrencies();
			$currencies = get_option(self::OPTION_KEY);
		}
		return $currencies['conversion_rates'][$currency];
	}

	public function callToAPI(){
		$remote = curl_init(self::CURRENCIES_API_URL);
		curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($remote);

		if ($response === false) {
		    $info = curl_getinfo($remote);
		    curl_close($remote);
		    die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($remote);
		$decoded = json_decode($response, true);
		if (isset($decoded->result) && !$decoded->result) {
            die('error occured parsing rates: ' . $decoded->message);
        }
		return $decoded;
	}
}
