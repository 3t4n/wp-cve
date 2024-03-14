<?php namespace flow\db\migrations;

use Exception;
use flow\db\LADBManager;
use flow\db\SafeMySQL;
use la\core\db\migrations\ILADBMigration;

if ( ! defined( 'WPINC' ) ) die;

/**
 * Flow-Flow
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
class FFMigration_3_13_1 implements ILADBMigration{
    public function version() {
        return '3.13.1';
    }

    /**
     * @param SafeMySQL $conn
     * @param LADBManager $manager
     *
     * @throws Exception
     */
    public function execute( $conn, $manager ) {
        $streams = $this->streams($conn, $manager->streams_table_name);
        foreach ( $streams as $stream ) {
            $options = unserialize( $stream['value'] );
            if ($options->order != 'compareByTime'){
                $options->order = 'compareByTime';
                $value = serialize($options);

                if ( false === $conn->query( 'UPDATE ?n SET `value` = ?s WHERE `id` = ?s',
                        $manager->streams_table_name, $value, $stream['id'] ) ) {
                    throw new Exception();
                }

                $options->id = $stream['id'];
                $manager->generateCss($options);
            }
        }

        $cache_table_name = $manager->cache_table_name;
        $all = $conn->getAll('select * from ?n', $cache_table_name);
        foreach ( $all as $source ) {
            $settings = unserialize($source['settings']);
            if (isset($settings->{'api-type'}) && $settings->{'api-type'} != 'official2'){
                $settings->{'api-type'} = 'official2';
                $value = serialize($settings);

                if ( false === $conn->query( 'UPDATE ?n SET `settings` = ?s WHERE `feed_id` = ?s',
                        $cache_table_name, $value, $source['feed_id'] ) ) {
                    throw new Exception();
                }
            }
        }

        $options = $manager->getOption('options', true);
        $options['general-settings-disable-proxy-server'] = 'yep';
        $manager->setOption('options', $options, true);

        if ( false === $conn->query( 'UPDATE ?n SET `cache_lifetime` = 120 WHERE `cache_lifetime` = 60', $cache_table_name ) ) {
            throw new Exception();
        }
    }

    private function streams($conn, $table_name){
        if (false !== ($result = $conn->getAll('SELECT `id`, `name`, `value` FROM ?n ORDER BY `id`',
                $table_name))){
            return $result;
        }
        return array();
    }
}