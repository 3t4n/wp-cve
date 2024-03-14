<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Plugin_Command_Executor' ) ) {
	return;
}

use Payever\Sdk\Plugins\Enum\PluginCommandNameEnum;
use Payever\Sdk\Plugins\Command\PluginCommandExecutorInterface;
use Payever\Sdk\Plugins\Http\MessageEntity\PluginCommandEntity;

class WC_Payever_Plugin_Command_Executor implements PluginCommandExecutorInterface {

	use WC_Payever_WP_Wrapper_Trait;

	/**
	 * @inheritDoc
	 */
	public function executeCommand( PluginCommandEntity $command ) {
		switch ( $command->getName() ) {
			case PluginCommandNameEnum::SET_SANDBOX_HOST:
				return $this->get_wp_wrapper()->update_option( WC_Payever_Helper::SANDBOX_URL_CONFIG_KEY, $command->getValue() );
				break;
			case PluginCommandNameEnum::SET_LIVE_HOST:
				return $this->get_wp_wrapper()->update_option( WC_Payever_Helper::LIVE_URL_CONFIG_KEY, $command->getValue() );
				break;
			case PluginCommandNameEnum::NOTIFY_NEW_PLUGIN_VERSION:
				return $this->get_wp_wrapper()->update_option( WC_Payever_Helper::KEY_PLUGIN_VERSION, $command->getValue() );
				break;
			case PluginCommandNameEnum::SET_API_VERSION:
				return $this->get_wp_wrapper()->update_option( WC_Payever_Helper::KEY_API_VERSION, $command->getValue() );
				break;
			default:
				throw new \UnexpectedValueException(
					sprintf(
						'Command %s with value %s is not supported',
						$command->getName(),
						$command->getValue()
					)
				);
		}
	}
}
