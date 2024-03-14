<?php

namespace WilokeEmailCreator\DataFactory\DataServer;

use Exception;
use WilokeEmailCreator\DataFactory\Interfaces\IDataFactory;
use WilokeEmailCreator\DataFactory\Shared\TraitHandlePosts;
use WilokeEmailCreator\DataFactory\Shared\TraitHandleProducts;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\Helper;


class DataServerService implements IDataFactory
{
	const URL_SERVER = 'https://emailcreator.app/wp-json/email-template-management/v1/';
	use TraitHandlePosts, TraitHandleProducts;

	protected function _handleGetTokenServer()
	{
		try {
			$aResult = wp_remote_post(self::URL_SERVER . 'accounts', [
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => [
						'Content-Type: application/json'
					],
					'body'        => [
						'info' => [
							'email' => get_option('admin_email'),
						]
					]
				]
			);
			if (is_wp_error($aResult)) {
				throw new Exception($aResult->get_error_message(), $aResult->get_error_code());
			}
			$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);

			if ($aResponse['status'] == 'error') {
				throw new Exception($aResponse['message'], $aResponse['code']);
			}
			Helper::updateServerToken($aResponse['data']['token']);
			return MessageFactory::factory()->success($aResponse['message'], ['token' => $aResponse['data']['token']]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function getToken($refreshToken = false)
	{
		$token = Helper::getServerToken();
		if (empty($token) || $refreshToken) {
			$aResponse = $this->_handleGetTokenServer();
			if ($aResponse['status'] == 'success') {
				$token = $aResponse['data']['token'];
			}
		}
		return $token;
	}


	public function getTemplateDetail($templateId)
	{
		try {
			$aData = [];
			$refresh = true;
			$i = 1;
			do {
				$aResult = wp_remote_get(self::URL_SERVER . 'templates/'.$templateId, [
						'method'      => 'GET',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => [
							'Content-Type: application/json',
							'Authorization' => $this->getToken()
						]
					]
				);
				$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);
				if ($aResponse['code'] === 'incorrect_password') {
					$this->getToken(true);
					$i++;
				}
				if ($aResponse['status'] == 'error' || is_wp_error($aResult)) {
					throw new Exception($aResponse['message']);
				}
				if ($aResponse['status'] == 'success'){
					$aData = $aResponse['data'];
					$refresh = false;
				}
			} while ($refresh && ($i < 4));

			return $aData;
		}
		catch (Exception $exception) {
			return [];
		}
	}

	public function getTemplates()
	{
		try {
			$aData = [];
			$refresh = true;
			$i = 1;
			do {
				$aResult = wp_remote_get(self::URL_SERVER . 'templates', [
						'method'      => 'GET',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => [
							'Content-Type: application/json',
							'Authorization' => $this->getToken()
						]
					]
				);
				$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);
				if ($aResponse['code'] === 'incorrect_password') {
					$this->getToken(true);
					$i++;
				}
				if ($aResponse['status'] == 'error' || is_wp_error($aResult)) {
					throw new Exception($aResponse['message']);
				}
				if ($aResponse['status'] == 'success'){
					$aData = $aResponse['data'];
					$refresh = false;
				}
			} while ($refresh && ($i < 4));
			return $aData['items'];
		}
		catch (Exception $exception) {
			return [];
		}
	}

	public function getCategories()
	{
		try {
			$aData = [];
			$refresh = true;
			$i = 1;
			do {
				$aResult = wp_remote_get(self::URL_SERVER . 'categories', [
						'method'      => 'GET',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => [
							'Content-Type: application/json',
							'Authorization' => $this->getToken()
						]
					]
				);
				$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);
				if ($aResponse['code'] === 'incorrect_password') {
					$this->getToken(true);
					$i++;
				}
				if ($aResponse['status'] == 'error' || is_wp_error($aResult)) {
					throw new Exception($aResponse['message']);
				}
				if ($aResponse['status'] == 'success'){
					$aData = $aResponse['data'];
					$refresh = false;
				}
			} while ($refresh && ($i < 4));
			return $aData['items'];
		}
		catch (Exception $exception) {
			return [];
		}
	}

	public function getCustomerTemplates()
	{
		// TODO: Implement getCustomerTemplates() method.
	}

	public function getSections()
	{
		// TODO: Implement getSections() method.
	}

	public function getSection($categoryId)
	{
		try {
			$aData = [];
			$refresh = true;
			$i = 1;
			do {
				$aResult = wp_remote_get(self::URL_SERVER . 'sections/'.$categoryId, [
						'method'      => 'GET',
						'timeout'     => 45,
						'redirection' => 5,
						'httpversion' => '1.0',
						'blocking'    => true,
						'headers'     => [
							'Content-Type: application/json',
							'Authorization' => $this->getToken()
						]
					]
				);
				$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);
				if ($aResponse['code'] === 'incorrect_password') {
					$this->getToken(true);
					$i++;
				}
				if ($aResponse['status'] == 'error' || is_wp_error($aResult)) {
					throw new Exception($aResponse['message']);
				}
				if ($aResponse['status'] == 'success'){
					$aData = $aResponse['data'];
					$refresh = false;
				}
			} while ($refresh && ($i < 4));
			return $aData['items'];
		}
		catch (Exception $exception) {
			return [];
		}
	}
}
