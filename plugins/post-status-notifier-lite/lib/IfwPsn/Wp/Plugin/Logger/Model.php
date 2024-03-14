<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 * 
 * 
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Model.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */ 
abstract class IfwPsn_Wp_Plugin_Logger_Model extends IfwPsn_Wp_ORM_Model
{
    /**
     * @var array
     */
    public static $eventItems = array(
        'priority',
        'message',
        'type',
        'timestamp',
        'extra'
    );

    /**
     * @param $tablename
     * @param bool $networkwide
     */
    public function createTable($tablename, $networkwide = false)
    {
        global $wpdb;

        $query = '
        CREATE TABLE IF NOT EXISTS `%s` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `priority` int(11) NOT NULL,
          `message` varchar(255) CHARACTER SET utf8 NOT NULL,
          `type` smallint(4) NOT NULL,
          `timestamp` datetime NOT NULL,
          `extra` longtext COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`id`),
          KEY `type` (`type`)
        );
        ';

        return $this->_create(sprintf($query, $wpdb->prefix . $tablename), $networkwide);
    }
}
