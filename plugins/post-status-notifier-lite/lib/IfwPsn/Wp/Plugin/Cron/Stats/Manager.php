<?php
/**
 * ifeelweb.de Wordpress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Manager.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Plugin_Cron_Stats_Manager
{
    const DEFAULT_EXPIRATION = 604800;

    /**
     * Instance store
     * @var array
     */
    public static $_instances = array();

    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;


    /**
     * IfwPsn_Wp_Cron_Stats_Manager constructor.
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    protected function __construct(IfwPsn_Wp_Plugin_Manager $pm)
    {
        $this->_pm = $pm;
    }

    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     * @return IfwPsn_Wp_Plugin_Cron_Stats_Manager
     */
    public static function getInstance(IfwPsn_Wp_Plugin_Manager $pm)
    {
        if (!isset(self::$_instances[$pm->getAbbr()])) {
            self::$_instances[$pm->getAbbr()] = new self($pm);
        }
        return self::$_instances[$pm->getAbbr()];
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = array();

        try {
            $db = IfwPsn_Wp_Proxy_Db::getObject();
            $sql = sprintf('SELECT * FROM `%soptions` WHERE `option_name` LIKE "%%transient_%s%%"', $db->prefix, $this->getTokenPrefix());

            $dbResult = IfwPsn_Wp_Proxy_Db::getObject()->get_results($sql, ARRAY_A);

            foreach ($dbResult as $row) {
                if (isset($row['option_value'])) {
                    $id = substr($row['option_name'], strlen('_transient_' . $this->getTokenPrefix()));
                    $data = unserialize($row['option_value']);
                    $stats = new IfwPsn_Wp_Plugin_Cron_Stats($id);
                    $stats->setName($data['name']);
                    $stats->setSuccess($data['success'] == true);
                    $stats->setMessage($data['message']);
                    $stats->setTimestamp($data['timestamp']);
                    $stats->setStatus($data['status']);
                    array_push($result, $stats);
                }
            }

        } catch (Exception $e) {

        }

        return $result;
    }

    /**
     * @param IfwPsn_Wp_Plugin_Cron_Stats $stats
     * @param null $expiration
     */
    public function saveStats(IfwPsn_Wp_Plugin_Cron_Stats $stats, $expiration = null)
    {
        $token = $this->getTokenPrefix() . $stats->getId();

        $data = array(
            'name' => $stats->getName(),
            'success' => $stats->isSuccess(),
            'message' => $stats->getMessage(),
            'status' => $stats->getStatus(),
            'timestamp' => date('Y-m-d H:i:s')
        );

        if (asa2_option_is('log_cronjobs')) {
            $logTitle = sprintf('Cronjob "%s" ' . __('Status', 'asa2') . ': %s', $stats->getName(), $stats->isSuccess() ? __('Success', 'asa2') : __('Errors found', 'asa2'));
            asa2_log_debug($logTitle, $stats->getMessage(), Asa2_Module_Log_Logger_Abstract::LOG_TYPE_CRONJOB);
        }

        if (!is_int($expiration)) {
            $expiration = self::DEFAULT_EXPIRATION;
        }

        set_transient($token, $data, $expiration);
    }

    /**
     * @return string
     */
    public function getTokenPrefix()
    {
        return $this->_pm->getAbbrLower() . '_cron_stats_';
    }
}
