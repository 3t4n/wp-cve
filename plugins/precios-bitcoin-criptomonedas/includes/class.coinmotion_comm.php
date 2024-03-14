<?php
class CoinmotionComm {
	/*
	*	CONSTANTS
	*/
	public const COINMOTION_API_URL = 'https://api.coinmotion.com/v2';
	public const COINMOTION_API_RATES = CoinmotionComm::COINMOTION_API_URL . '/rates';
	public const COINMOTION_API_CURRENCIES = CoinmotionComm::COINMOTION_API_URL . '/get_currencies';
	public const COINMOTION_API_RATE_HISTORY = CoinmotionComm::COINMOTION_API_URL . '/rate_history';
	public const COINMOTION_API_ALLOWED_COUNTRIES = CoinmotionComm::COINMOTION_API_URL . '/get_allowed_countries';
	public const COINMOTION_API_LANGUAGES = CoinmotionComm::COINMOTION_API_URL . '/get_available_languages';
    public const COINMOTION_OUTSIDE_COINMOTION = ['usdt', 'dot', 'sol', 'matic', 'sand', 'mana'];
    public const COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION = [
        'usdt' => 'tether',
        'dot' => 'polkadot',
        'sol' => 'solana',
        'matic' => 'matic-network',
        'sand' => 'the-sandbox',
        'mana' => 'decentraland',
    ];

    private function checkIfExistsExternalQuery($query, $type)
    {
        $option = get_option('coinmotion_' . $type . '_' . $query);
        if ((!$option || (time() - $option['date']) > 300)){
            return false;
        }
        else{
            return $option['data'];
        }
    }

    private function getExternalCriptoDays($cripto, $days)
    {
        $url = 'https://api.coingecko.com/api/v3/coins/' . self::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION[$cripto] . '/market_chart?vs_currency=eur&days=' . $days;
        $type = 'days';
        $return = self::checkIfExistsExternalQuery($url, $type);
        if (!$return){
            $remote = curl_init($url);
            curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($remote);

            if ($response === false) {
                $info = curl_getinfo($remote);
                curl_close($remote);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            curl_close($remote);
            $decoded = json_decode($response, true);

            $return = [];

            foreach ($decoded['prices'] as $value){
                $return[] = [$value[1], (int)$value[0] / 1000];
            }
            update_option('coinmotion_' . $type . '_' . $url, ['date' => time(), 'data' => $return]);
        }
        return json_encode($return);
    }

    private function getExternalCriptoData($cripto)
    {
        $url =  'https://api.coingecko.com/api/v3/coins/' . $cripto;
        $type = 'current';
        $return = self::checkIfExistsExternalQuery($url, $type);
        if (!$return){
            $remote = curl_init($url);
            curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($remote);

            if ($response === false) {
                $info = curl_getinfo($remote);
                curl_close($remote);
                die('error occured during curl exec. Additioanl info: ' . var_export($info));
            }
            curl_close($remote);
            $decoded = json_decode($response, true);

            $return[strtolower($cripto) . 'Eur'] = [];
            $return[strtolower($cripto) . 'Eur']['baseCurrencyCode'] = 'EUR';
            $return[strtolower($cripto) . 'Eur']['currencyCode'] = strtoupper($cripto);
            $return[strtolower($cripto) . 'Eur']['buy'] = (float)$decoded['market_data']['current_price']['eur'];
            $return[strtolower($cripto) . 'Eur']['low'] = (float)$decoded['market_data']['low_24h']['eur'];
            $return[strtolower($cripto) . 'Eur']['high'] = (float)$decoded['market_data']['high_24h']['eur'];
            $return[strtolower($cripto) . 'Eur']['changeAmount'] = (float)$decoded['market_data']['price_change_24h_in_currency']['eur'];
            $return[strtolower($cripto) . 'Eur']['fchangep'] = (string)$decoded['market_data']['price_change_percentage_24h'] . " %";
            update_option('coinmotion_' . $type . '_' . $url, ['date' => time(), 'data' => $return]);
        }
        return $return;
    }

	public function getRates(){
        $response = json_decode(CoinmotionComm::callToAPI(CoinmotionComm::COINMOTION_API_RATES), true);

        foreach (self::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION as $key => $value){
            $response = array_merge($response, CoinmotionComm::getExternalCriptoData(CoinmotionComm::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION[$key]));
        }
        return json_encode($response);
	}

	public function getCurrencies($type = 'buy'){ // buy, sell, balance, cryptos, interest
		$params = [$type];
		return $this->callToAPI(self::COINMOTION_API_CURRENCIES, $params);
	}

	public function getRateHistory($currency = 'btc', $time_period= 'day', $type = 'price'){ // currencyCode, timePeriod = [hour, day, week, year, month, 3_months], type = [price, interest]
		$params = [$currency, 'eur', strtolower($time_period), strtolower($type)];
        if (array_key_exists($currency, self::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION)){
            $days = 1;
            if ($time_period == 'week'){
                $days = 7;
            }
            if ($time_period == 'month'){
                $days = 30;
            }
            if ($time_period == '3_months'){
                $days = 90;
            }
            if ($time_period == 'year'){
                $days = 365;
            }
            $response = $this->getExternalCriptoDays($currency, $days);
            if ($time_period == 'hour'){
                $response_array = json_decode($response, true);
                $response_array = array_slice($response_array, -13);
                $response = json_encode($response_array);
            }
        }
        else{
            $response = $this->callToAPI(self::COINMOTION_API_RATE_HISTORY, $params);
        }

        return $response;
	}

	/*public function getAllowedCountries(){
		return $this->callToAPI(CoinmotionComm::COINMOTION_API_ALLOWED_COUNTRIES);
	}*/

	/*public function getAvailableLangs(){
		return $this->callToAPI(CoinmotionComm::COINMOTION_API_ALLOWED_COUNTRIES);
	}*/

	public function getDetails($currency = 'btc', $type = 'price'): array
    {

		$currency = strtolower($currency);
		$curren = new CoinmotionGetCurrencies();
		$actual_currency = coinmotion_get_widget_data();

 		$actual_curr_value = (float)$curren->getCotization($actual_currency['default_currency']);

        $hour_data = json_decode($this->getRateHistory($currency, 'hour', $type), true);
		$day_data = json_decode($this->getRateHistory($currency, 'day', $type), true);
		
		$week_data = json_decode($this->getRateHistory($currency, 'week', $type), true);
		$month_data = json_decode($this->getRateHistory($currency, 'month', $type), true);
		$three_month_data = json_decode($this->getRateHistory($currency, '3_months', $type), true);
		$year = json_decode($this->getRateHistory($currency, 'year', $type), true);

		$open_hour = $higher_hour = $higher_day = $higher_month = $higher_3_months = $higher_year = $higher_week = 0.0;

		$now = json_decode($this->getRates(), true);

        $actual_price = 0;
        if (array_key_exists($currency . "Eur", $now))
		    $actual_price = (float)$now[$currency . "Eur"]['buy'];
        else
            $actual_price = (float)$now[self::COINMOTION_OUTSIDE_COINMOTION_TRANSLATAION[$currency] . "Eur"]["buy"];
		$open_day = $day_data[0][0]*$actual_curr_value;

        $total = count($hour_data);
		$lower_hour = $hour_data[0][0]*$actual_curr_value;
		$hour_var_first_value = $hour_data[0][0]*$actual_curr_value;
		$hour_var_last_value = $hour_data[$total-1][0]*$actual_curr_value;
		$variation_hour = number_format((($hour_var_last_value/$hour_var_first_value) - 1)*100, 4);
		for ($i = 0; $i < $total; $i++){
			$actual = $hour_data[$i][0]*$actual_curr_value;
			if ($lower_hour > $actual) {
                $lower_hour = $actual;
            }
			if ($higher_hour < $actual) {
                $higher_hour = $actual;
            }
		}


		$total = count($day_data);
		$lower_day = $day_data[0][0]*$actual_curr_value;
		$day_var_first_value = $day_data[0][0]*$actual_curr_value;
		$day_var_last_value = $day_data[$total-1][0]*$actual_curr_value;
		$variation_day = number_format((($day_var_last_value/$day_var_first_value) - 1)*100, 4);
		for ($i = 0; $i < $total; $i++){
			$actual = $day_data[$i][0]*$actual_curr_value;
			if ($lower_day > $actual) {
                $lower_day = $actual;
            }
			if ($higher_day < $actual) {
                $higher_day = $actual;
            }
		}

		$total = count($week_data);
		$lower_week = $week_data[0][0]*$actual_curr_value;
		$week_var_first_value = $week_data[0][0]*$actual_curr_value;
		$week_var_last_value = $week_data[$total-1][0]*$actual_curr_value;
		$variation_week = number_format((($week_var_last_value/$week_var_first_value) - 1)*100, 4);
		for ($i = 0; $i < $total; $i++){
			$actual = $week_data[$i][0]*$actual_curr_value;
			if ($lower_week > $actual) {
                $lower_week = $actual;
            }
			if ($higher_week < $actual) {
                $higher_week = $actual;
            }
		}

		$total = count($month_data);
		$lower_month = $month_data[0][0]*$actual_curr_value;
		$month_var_first_value = $month_data[0][0]*$actual_curr_value;
		$month_var_last_value = $month_data[$total-1][0]*$actual_curr_value;
		$variation_month = number_format((($month_var_last_value/$month_var_first_value) - 1)*100, 4);
		for ($i = 0; $i < $total; $i++){
			$actual = $month_data[$i][0]*$actual_curr_value;
			if ($lower_month > $actual) {
                $lower_month = $actual;
            }
			if ($higher_month < $actual) {
                $higher_month = $actual;
            }
		}

		$total = count($three_month_data);
		$lower_3_months = $three_month_data[0][0]*$actual_curr_value;
		$three_month_var_first_value = $three_month_data[0][0]*$actual_curr_value;
		$three_month_var_last_value = $three_month_data[$total-1][0]*$actual_curr_value;
		$variation_3_month = number_format((($three_month_var_last_value/$three_month_var_first_value) - 1)*100, 4);
		for ($i = 0; $i < $total; $i++){
			$actual = $three_month_data[$i][0]*$actual_curr_value;
			if ($lower_3_months > $actual) {
                $lower_3_months = $actual;
            }
			if ($higher_3_months < $actual) {
                $higher_3_months = $actual;
            }
		}

		$total = count($year);
		$lower_year = ($year[0][0] * $actual_curr_value);
		$year_first_value = ($year[0][0] * $actual_curr_value);
		$year_last_value = ($year[$total - 1][0] * $actual_curr_value);
		$variation_year = number_format((($year_last_value/$year_first_value) - 1)*100, 4);
		for ($i = 0; $i < $total; $i++){
			$actual = ($year[$i][0] * $actual_curr_value);
			if ($lower_year > $actual)
				$lower_year = $actual;
			if ($higher_year < $actual)
				$higher_year = $actual;
		}

		return ['actual_price' => $actual_price,
                    'open_hour' => $open_hour, 
                    'lower_hour' => $lower_hour,
                    'higher_hour' => $higher_hour, 
                    'open_day' => $open_day, 
                    'lower_day' => $lower_day, 
                    'higher_day' => $higher_day, 
                    'lower_week' => $lower_week, 
                    'higher_week' => $higher_week, 
                    'lower_month' => $lower_month, 
                    'higher_month' => $higher_month, 
                    'lower_3_months' => $lower_3_months, 
                    'higher_3_months' => $higher_3_months, 
                    'lower_year' => $lower_year, 
                    'higher_year' => $higher_year, 
                    'variation_hour' => $variation_hour, 
                    'variation_day' => $variation_day, 
                    'variation_week' => $variation_week, 
                    'variation_month' => $variation_month, 
                    'variation_3_months' => $variation_3_month, 
                    'variation_year' => $variation_year];
	}

	public function callToAPI($method, $params = []){
		$query_string = $method;

		if (count($params) > 0) {
			foreach ($params as $p){
				$query_string .= "/$p";
			}
		}
		$remote = curl_init($query_string);
		curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($remote);
		if ($response === false) {
		    $info = curl_getinfo($remote);
		    curl_close($remote);
		    die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($remote);
		$decoded = json_decode($response);

		if (isset($decoded->success) && !$decoded->success) {
            die('error occured: ');
        }
		return json_encode($decoded->payload, true);
	}
}
