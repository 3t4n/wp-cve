<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models;
defined('ABSPATH') or die();

class Referral extends Base
{

    function __construct()
    {
        parent::__construct();
        $this->table = self::$db->prefix . 'wlr_referral';
        $this->primary_key = 'id';
        $this->fields = array(
            'advocate_email' => '%s',
            'friend_email' => '%s',
            'status' => '%s',
            'created_date' => '%s'
        );
    }

    function beforeTableCreation()
    {
    }

    function runTableCreation()
    {
        $create_table_query = "CREATE TABLE IF NOT EXISTS {$this->table} (
				 `{$this->getPrimaryKey()}` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                 `advocate_email` varchar(180) DEFAULT NULL,
                 `friend_email` varchar(180) DEFAULT NULL,
                 `status` varchar(180) DEFAULT NULL,
                 `created_date` BIGINT DEFAULT 0,
                 PRIMARY KEY (`{$this->getPrimaryKey()}`)
			)";
        $this->createTable($create_table_query);
    }

    function afterTableCreation()
    {
        $index_fields = array('advocate_email', 'friend_email', 'status');
        $this->insertIndex($index_fields);
    }

}