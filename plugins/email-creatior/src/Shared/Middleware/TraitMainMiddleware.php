<?php

namespace WilokeEmailCreator\Shared\Middleware;


use Exception;
use WilokeEmailCreator\Illuminate\Message\MessageFactory;
use WilokeEmailCreator\Shared\Middleware\Middlewares\IMiddleware;

trait TraitMainMiddleware
{
	public function processMiddleware(array $aMiddleware, array $aAdditional = []): array
	{
		$aReturnData = [];
		try {
			foreach ($aMiddleware as $middlewareKey) {
				$aResponseGetClassOrFunction = $this->getMiddleware($middlewareKey);
				if ($aResponseGetClassOrFunction['status'] == 'error') {
					throw new Exception($aResponseGetClassOrFunction['message'],
						$aResponseGetClassOrFunction['code']);
				} else {
					$classOrFunction = $aResponseGetClassOrFunction['data']['middlewareKey'];
					if (function_exists($classOrFunction)) {
						$aFunctionResponse = call_user_func($classOrFunction, $aAdditional);
						if ($aFunctionResponse['status'] == 'success') {
							$aReturnData[$middlewareKey] = $aFunctionResponse['data'];
						} else {
							throw new Exception($aFunctionResponse['message'], $aFunctionResponse['code']);
						}
					} elseif (class_exists($classOrFunction)) {
						$oInit = new $classOrFunction;
						if (!($oInit instanceof IMiddleware)) {
							throw new Exception(
								sprintf(esc_html__('Look like your class or function %s does\'t exist. PLease re-check it!',
									'emailcreator'),
									$classOrFunction),
								400);
						}
						$aClassResponse = $oInit->validation($aAdditional);
						if ($aClassResponse['status'] == 'success') {
							$aReturnData[$middlewareKey] = $aClassResponse['data'];
						} else {
							throw new Exception($aClassResponse['message'], $aClassResponse['code']);
						}
					} else {
						throw new Exception(sprintf(esc_html__('Look like your class or function %s does\'t exist. PLease re-check it!',
							'emailcreator'), $classOrFunction), 400);
					}
				}
			}

			return MessageFactory::factory()->success('Passed', $aReturnData);
		}
		catch (Exception $exception) {
			return MessageFactory::factory()->error($exception->getMessage(), $exception->getCode());
		}
	}

	private function getMiddleware($middlewareKey): array
	{
		$aAlMiddlewares = $this->getAllMiddlewares();
		if ($aAlMiddlewares['status'] == 'error') {
			return MessageFactory::factory()->error($aAlMiddlewares['message'], $aAlMiddlewares['code']);
		}
		return array_key_exists($middlewareKey, $aAlMiddlewares['data']) ?
			MessageFactory::factory()
				->success('ok', ['middlewareKey' => $aAlMiddlewares['data'][$middlewareKey]]) :
			MessageFactory::factory()
				->error(sprintf('%s middleware does not exists', $middlewareKey), 400);
	}

	private function getAllMiddlewares(): array
	{
		$aAllMiddleware = include(WILOKE_EMAIL_CREATOR_PATH . 'src/Shared/Middleware/Configs/Configuration.php');
		if (is_array($aAllMiddleware) && !empty($aAllMiddleware)) {
			return MessageFactory::factory()->success('OK', $aAllMiddleware);
		}

		return MessageFactory::factory()
			->error(esc_html__('Look like you have return wrong data type when configure the configuration',
				'emailcreator'), 400);
	}
}
