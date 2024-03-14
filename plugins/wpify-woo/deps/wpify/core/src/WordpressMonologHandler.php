<?php

namespace WpifyWooDeps\Wpify\Core;

use WpifyWooDeps\Monolog\Logger;
use WpifyWooDeps\Monolog\Handler\AbstractProcessingHandler;
/**
 * This class is a handler for Monolog, which can be used
 * to write records in a MySQL table with WordPress Functions
 * Class WordPressHandler
 *
 * @package bradmkjr\WordPressHandler
 */
class WordpressMonologHandler extends AbstractProcessingHandler
{
    /**
     * @var bool defines whether the MySQL connection is been initialized
     */
    public $initialized = \false;
    /**
     * @var \wpdb wpdb object of database connection
     */
    protected $wpdb;
    /**
     * @var PDOStatement statement to insert a new record
     */
    // private $statement;
    /**
     * @var string the table to store the logs in
     */
    private $table = 'logs';
    /**
     * @var string the table prefix to store the logs in
     */
    private $prefix = 'wp_';
    /**
     * @var string[] additional fields to be stored in the database
     * For each field $field, an additional context field with the name $field
     * is expected along the message, and further the database needs to have these fields
     * as the values are stored in the column name $field.
     */
    private $additionalFields = array();
    /**
     * @var int defines the maximum number of rows allowed in the log table. 0 means no limit
     */
    protected $max_table_rows = 0;
    /**
     * Constructor of this class, sets the PDO and calls parent constructor
     *
     * @param \wpdb    $wpdb wpdb object of database connection
     * @param bool     $table Table in the database to store the logs in
     * @param array    $additionalFields Additional Context Parameters to store in database
     * @param bool|int $level Debug level which this handler should store
     * @param bool     $bubble
     */
    public function __construct($wpdb = null, $table = 'wpify_logs', $additionalFields = array(), $level = Logger::DEBUG, $bubble = \true)
    {
        if (!\is_null($wpdb)) {
            $this->wpdb = $wpdb;
        }
        $this->table = $table;
        $this->prefix = $this->wpdb->prefix;
        $this->additionalFields = $additionalFields;
        parent::__construct($level, $bubble);
    }
    /**
     * Set the limit of maximum number of table rows to collect.
     * Use 0 (or any negative number) to disable limit.
     *
     * @param int $max_table_rows
     */
    public function set_max_table_rows(int $max_table_rows)
    {
        $this->max_table_rows = \max(0, $max_table_rows);
    }
    /**
     * Returns the full log tables name
     *
     * @return string
     */
    public function get_table_name()
    {
        return $this->prefix . $this->table;
    }
    /**
     * Initializes this handler by creating the table if it not exists
     */
    public function initialize(array $record)
    {
        // referenced
        // https://codex.wordpress.org/Creating_Tables_with_Plugins
        // $this->wpdb->exec(
        // 'CREATE TABLE IF NOT EXISTS `'.$this->table.'` '
        // .'(channel VARCHAR(255), level INTEGER, message LONGTEXT, time INTEGER UNSIGNED)'
        // );
        $charset_collate = $this->wpdb->get_charset_collate();
        $table_name = $this->get_table_name();
        // allow for Extra fields
        $extraFields = '';
        if (!empty($record['extra'])) {
            foreach ($record['extra'] as $key => $val) {
                $extraFields .= ",\n`{$key}` TEXT NULL DEFAULT NULL";
            }
        }
        // additional fields
        $additionalFields = '';
        foreach ($this->additionalFields as $f) {
            $additionalFields .= ",\n`{$f}` TEXT NULL DEFAULT NULL";
        }
        $sql = "CREATE TABLE {$table_name} (\n            id INT(11) NOT NULL AUTO_INCREMENT,\n            channel VARCHAR(255),\n            level INTEGER,\n            message LONGTEXT,\n            time INTEGER UNSIGNED{$extraFields}{$additionalFields},\n            PRIMARY KEY  (id)\n            ) {$charset_collate};";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        $this->initialized = \true;
    }
    /**
     * Uninitializes this handler by deleting the table if it exists
     */
    public function uninitialize()
    {
        $table_name = $this->get_table_name();
        $sql = "DROP TABLE IF EXISTS {$table_name};";
        if (!\is_null($this->wpdb)) {
            $this->wpdb->query($sql);
        }
    }
    /**
     * Deletes the oldest records from the log table to ensure there are no more
     * rows than the defined limit.
     * Use {@see set_max_table_rows()} to configure the limit!
     *
     * @return boolean True if rows were deleted, false otherwise.
     */
    public function maybe_truncate()
    {
        if ($this->max_table_rows <= 0) {
            return \false;
        }
        $table_name = $this->get_table_name();
        $sql = "SELECT count(*) FROM {$table_name};";
        $count = $this->wpdb->get_var($sql);
        if (\is_numeric($count) && $this->max_table_rows <= (int) $count) {
            // using `LIMIT -1`, `LIMIT 0`, `LIMIT NULL` may not be compatible with all db systems
            // deleting 10000 rows in one go is good enough anyway, it'll converge pretty fast
            $sql = "DELETE FROM {$table_name} WHERE `id` IN ( SELECT * FROM (SELECT `id` FROM {$table_name} ORDER BY `id` DESC LIMIT 10000 OFFSET {$this->max_table_rows}) as `workaround_subquery_for_older_mysql_versions` );";
            return \false !== $this->wpdb->query($sql);
        }
        return \false;
    }
    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  $record []
     *
     * @return void
     */
    protected function write(array $record) : void
    {
        if (!$this->initialized) {
            $this->initialize($record);
        }
        // 'context' contains the array
        $contentArray = array('channel' => $record['channel'], 'level' => $record['level'], 'message' => isset($record['formatted']['message']) ? $record['formatted']['message'] : $record['message'], 'time' => $record['datetime']->format('U'));
        // Make sure to use the formatted values for context and extra, if available
        $recordExtra = isset($record['formatted']['extra']) ? $record['formatted']['extra'] : $record['extra'];
        $recordContext = isset($record['formatted']['context']) ? $record['formatted']['context'] : $record['context'];
        $recordContExtra = \array_merge($recordExtra, $recordContext);
        // json encode values as needed
        \array_walk($recordContExtra, function (&$value, $key) {
            if (\is_array($value) || $value instanceof \Traversable) {
                $value = \json_encode($value);
            }
        });
        $contentArray = $contentArray + $recordContExtra;
        if (\count($this->additionalFields) > 0) {
            // Fill content array with "null" values if not provided
            $contentArray = $contentArray + \array_combine($this->additionalFields, \array_fill(0, \count($this->additionalFields), null));
        }
        $table_name = $this->get_table_name();
        $this->wpdb->insert($table_name, $contentArray);
        $this->maybe_truncate();
    }
}
