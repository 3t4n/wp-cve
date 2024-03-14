<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * Cli command factory
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Factory.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package  IfwPsn_Wp
 */
class IfwPsn_Wp_Plugin_Cli_Factory
{
    protected function __construct()
    {
    }

    /**
     * Return the command class
     *
     * @param string $command
     * @param array $args
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @throws IfwPsn_Wp_Plugin_Cli_Factory_Exception
     * @return IfwPsn_Wp_Plugin_Cli_Command_Abstract
     */
    public static function getCommand($command, $args, IfwPsn_Wp_Plugin_Manager $pm)
    {

        $commandPath = IfwPsn_Wp_Autoloader::getClassPath($command);

        $commandPath = apply_filters('ifw_wp_plugin_cli_factory_command_path', $commandPath, $command, $args);
        $command = apply_filters('ifw_wp_plugin_cli_factory_command', $command, $args);

        if ($commandPath == false) {
    
            throw new IfwPsn_Wp_Plugin_Cli_Factory_Exception('Unkown command: '. $command);
    
        } elseif (!is_subclass_of($command, 'IfwPsn_Wp_Plugin_Cli_Command_Abstract')) {

            throw new IfwPsn_Wp_Plugin_Cli_Factory_Exception('Command class must extend IfwPsn_Wp_Plugin_Cli_Command_Abstract');
    
        } else {
    
            return new $command($command, $args, $pm);
        }
    }
}
