<?php

/**
 * @addtogroup database
 * @{
 */
namespace d7;

use DatabaseLog;
use DatabaseSchema;
use Exception;
use Iterator;
use PDO;
use PDOException;
use PDOStatement;
use SelectQuery;
use SelectQueryInterface;
use Traversable;

intel_load_include('includes/intel.d7_core');
/**
 * @file
 * Core systems for the database layer.
 *
 * Classes required for basic functioning of the database system should be
 * placed in this file.  All utility functions should also be placed in this
 * file only, as they cannot auto-load the way classes can.
 */

/**
 * @defgroup database Database abstraction layer
 * @{
 * Allow the use of different database servers using the same code base.
 *
 * Drupal provides a database abstraction layer to provide developers with
 * the ability to support multiple database servers easily. The intent of
 * this layer is to preserve the syntax and power of SQL as much as possible,
 * but also allow developers a way to leverage more complex functionality in
 * a unified way. It also provides a structured interface for dynamically
 * constructing queries when appropriate, and enforcing security checks and
 * similar good practices.
 *
 * The system is built atop PHP's PDO (PHP Data Objects) database API and
 * inherits much of its syntax and semantics.
 *
 * Most Drupal database SELECT queries are performed by a call to db_query() or
 * db_query_range(). Module authors should also consider using the PagerDefault
 * Extender for queries that return results that need to be presented on
 * multiple pages (see https://drupal.org/node/508796), and the TableSort
 * Extender for generating appropriate queries for sortable tables
 * (see https://drupal.org/node/1848372).
 *
 * For example, one might wish to return a list of the most recent 10 nodes
 * authored by a given user. Instead of directly issuing the SQL query
 * @code
 * SELECT n.nid, n.title, n.created FROM node n WHERE n.uid = $uid
 *   ORDER BY n.created DESC LIMIT 0, 10;
 * @endcode
 * one would instead call the Drupal functions:
 * @code
 * $result = db_query_range('SELECT n.nid, n.title, n.created
 *   FROM {node} n WHERE n.uid = :uid
 *   ORDER BY n.created DESC', 0, 10, array(':uid' => $uid));
 * foreach ($result as $record) {
 *   // Perform operations on $record->title, etc. here.
 * }
 * @endcode
 * Curly braces are used around "node" to provide table prefixing via
 * DatabaseConnection::prefixTables(). The explicit use of a user ID is pulled
 * out into an argument passed to db_query() so that SQL injection attacks
 * from user input can be caught and nullified. The LIMIT syntax varies between
 * database servers, so that is abstracted into db_query_range() arguments.
 * Finally, note the PDO-based ability to iterate over the result set using
 * foreach ().
 *
 * All queries are passed as a prepared statement string. A
 * prepared statement is a "template" of a query that omits literal or variable
 * values in favor of placeholders. The values to place into those
 * placeholders are passed separately, and the database driver handles
 * inserting the values into the query in a secure fashion. That means you
 * should never quote or string-escape a value to be inserted into the query.
 *
 * There are two formats for placeholders: named and unnamed. Named placeholders
 * are strongly preferred in all cases as they are more flexible and
 * self-documenting. Named placeholders should start with a colon ":" and can be
 * followed by one or more letters, numbers or underscores.
 *
 * Named placeholders begin with a colon followed by a unique string. Example:
 * @code
 * SELECT nid, title FROM {node} WHERE uid=:uid;
 * @endcode
 *
 * ":uid" is a placeholder that will be replaced with a literal value when
 * the query is executed. A given placeholder label cannot be repeated in a
 * given query, even if the value should be the same. When using named
 * placeholders, the array of arguments to the query must be an associative
 * array where keys are a placeholder label (e.g., :uid) and the value is the
 * corresponding value to use. The array may be in any order.
 *
 * Unnamed placeholders are simply a question mark. Example:
 * @code
 * SELECT nid, title FROM {node} WHERE uid=?;
 * @endcode
 *
 * In this case, the array of arguments must be an indexed array of values to
 * use in the exact same order as the placeholders in the query.
 *
 * Note that placeholders should be a "complete" value. For example, when
 * running a LIKE query the SQL wildcard character, %, should be part of the
 * value, not the query itself. Thus, the following is incorrect:
 * @code
 * SELECT nid, title FROM {node} WHERE title LIKE :title%;
 * @endcode
 * It should instead read:
 * @code
 * SELECT nid, title FROM {node} WHERE title LIKE :title;
 * @endcode
 * and the value for :title should include a % as appropriate. Again, note the
 * lack of quotation marks around :title. Because the value is not inserted
 * into the query as one big string but as an explicitly separate value, the
 * database server knows where the query ends and a value begins. That is
 * considerably more secure against SQL injection than trying to remember
 * which values need quotation marks and string escaping and which don't.
 *
 * INSERT, UPDATE, and DELETE queries need special care in order to behave
 * consistently across all different databases. Therefore, they use a special
 * object-oriented API for defining a query structurally. For example, rather
 * than:
 * @code
 * INSERT INTO node (nid, title, body) VALUES (1, 'my title', 'my body');
 * @endcode
 * one would instead write:
 * @code
 * $fields = array('nid' => 1, 'title' => 'my title', 'body' => 'my body');
 * db_insert('node')->fields($fields)->execute();
 * @endcode
 * This method allows databases that need special data type handling to do so,
 * while also allowing optimizations such as multi-insert queries. UPDATE and
 * DELETE queries have a similar pattern.
 *
 * Drupal also supports transactions, including a transparent fallback for
 * databases that do not support transactions. To start a new transaction,
 * simply call $txn = db_transaction(); in your own code. The transaction will
 * remain open for as long as the variable $txn remains in scope.  When $txn is
 * destroyed, the transaction will be committed.  If your transaction is nested
 * inside of another then Drupal will track each transaction and only commit
 * the outer-most transaction when the last transaction object goes out out of
 * scope, that is, all relevant queries completed successfully.
 *
 * Example:
 * @code
 * function my_transaction_function() {
 *   // The transaction opens here.
 *   $txn = db_transaction();
 *
 *   try {
 *     $id = db_insert('example')
 *       ->fields(array(
 *         'field1' => 'mystring',
 *         'field2' => 5,
 *       ))
 *       ->execute();
 *
 *     my_other_function($id);
 *
 *     return $id;
 *   }
 *   catch (Exception $e) {
 *     // Something went wrong somewhere, so roll back now.
 *     $txn->rollback();
 *     // Log the exception to watchdog.
 *     watchdog_exception('type', $e);
 *   }
 *
 *   // $txn goes out of scope here.  Unless the transaction was rolled back, it
 *   // gets automatically committed here.
 * }
 *
 * function my_other_function($id) {
 *   // The transaction is still open here.
 *
 *   if ($id % 2 == 0) {
 *     db_update('example')
 *       ->condition('id', $id)
 *       ->fields(array('field2' => 10))
 *       ->execute();
 *   }
 * }
 * @endcode
 *
 * @see http://drupal.org/developing/api/database
 */


/**
 * Base Database API class.
 *
 * This class provides a Drupal-specific extension of the PDO database
 * abstraction class in PHP. Every database driver implementation must provide a
 * concrete implementation of it to support special handling required by that
 * database.
 *
 * @see http://php.net/manual/book.pdo.php
 */
abstract class DatabaseConnection extends PDO {

  /**
   * The database target this connection is for.
   *
   * We need this information for later auditing and logging.
   *
   * @var string
   */
  protected $target = NULL;

  /**
   * The key representing this connection.
   *
   * The key is a unique string which identifies a database connection. A
   * connection can be a single server or a cluster of master and slaves (use
   * target to pick between master and slave).
   *
   * @var string
   */
  protected $key = NULL;

  /**
   * The current database logging object for this connection.
   *
   * @var DatabaseLog
   */
  protected $logger = NULL;

  /**
   * Tracks the number of "layers" of transactions currently active.
   *
   * On many databases transactions cannot nest.  Instead, we track
   * nested calls to transactions and collapse them into a single
   * transaction.
   *
   * @var array
   */
  protected $transactionLayers = [];

  /**
   * Index of what driver-specific class to use for various operations.
   *
   * @var array
   */
  protected $driverClasses = [];

  /**
   * The name of the Statement class for this connection.
   *
   * @var string
   */
  protected $statementClass = 'd7\DatabaseStatementBase';

  /**
   * Whether this database connection supports transactions.
   *
   * @var bool
   */
  protected $transactionSupport = TRUE;

  /**
   * Whether this database connection supports transactional DDL.
   *
   * Set to FALSE by default because few databases support this feature.
   *
   * @var bool
   */
  protected $transactionalDDLSupport = FALSE;

  /**
   * An index used to generate unique temporary table names.
   *
   * @var integer
   */
  protected $temporaryNameIndex = 0;

  /**
   * The connection information for this connection object.
   *
   * @var array
   */
  protected $connectionOptions = [];

  /**
   * The schema object for this connection.
   *
   * @var object
   */
  protected $schema = NULL;

  /**
   * The prefixes used by this database connection.
   *
   * @var array
   */
  protected $prefixes = [];

  /**
   * List of search values for use in prefixTables().
   *
   * @var array
   */
  protected $prefixSearch = [];

  /**
   * List of replacement values for use in prefixTables().
   *
   * @var array
   */
  protected $prefixReplace = [];

  /**
   * List of escaped database, table, and field names, keyed by unescaped names.
   *
   * @var array
   */
  protected $escapedNames = [];

  /**
   * List of escaped aliases names, keyed by unescaped aliases.
   *
   * @var array
   */
  protected $escapedAliases = [];

  function __construct($dsn, $username, $password, $driver_options = []) {
    global $wpdb;
    // Initialize and prepare the connection prefix.
    $this->setPrefix(isset($this->connectionOptions['prefix']) ? $this->connectionOptions['prefix'] : $wpdb->prefix);
    //$this->setPrefix(isset($this->connectionOptions['prefix']) ? $this->connectionOptions['prefix'] : '');

    // Because the other methods don't seem to work right.
    $driver_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

    // Call PDO::__construct and PDO::setAttribute.
    parent::__construct($dsn, $username, $password, $driver_options);

    // Set a Statement class, unless the driver opted out.
    if (!empty($this->statementClass)) {
      $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [
        $this->statementClass,
        [$this]
      ]);
    }
  }

  /**
   * Destroys this Connection object.
   *
   * PHP does not destruct an object if it is still referenced in other
   * variables. In case of PDO database connection objects, PHP only closes the
   * connection when the PDO object is destructed, so any references to this
   * object may cause the number of maximum allowed connections to be exceeded.
   */
  public function destroy() {
    // Destroy all references to this connection by setting them to NULL.
    // The Statement class attribute only accepts a new value that presents a
    // proper callable, so we reset it to PDOStatement.
    $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['PDOStatement', []]);
    $this->schema = NULL;
  }

  /**
   * Returns the default query options for any given query.
   *
   * A given query can be customized with a number of option flags in an
   * associative array:
   * - target: The database "target" against which to execute a query. Valid
   *   values are "default" or "slave". The system will first try to open a
   *   connection to a database specified with the user-supplied key. If one
   *   is not available, it will silently fall back to the "default" target.
   *   If multiple databases connections are specified with the same target,
   *   one will be selected at random for the duration of the request.
   * - fetch: This element controls how rows from a result set will be
   *   returned. Legal values include PDO::FETCH_ASSOC, PDO::FETCH_BOTH,
   *   PDO::FETCH_OBJ, PDO::FETCH_NUM, or a string representing the name of a
   *   class. If a string is specified, each record will be fetched into a new
   *   object of that class. The behavior of all other values is defined by PDO.
   *   See http://php.net/manual/pdostatement.fetch.php
   * - return: Depending on the type of query, different return values may be
   *   meaningful. This directive instructs the system which type of return
   *   value is desired. The system will generally set the correct value
   *   automatically, so it is extremely rare that a module developer will ever
   *   need to specify this value. Setting it incorrectly will likely lead to
   *   unpredictable results or fatal errors. Legal values include:
   *   - Database::RETURN_STATEMENT: Return the prepared statement object for
   *     the query. This is usually only meaningful for SELECT queries, where
   *     the statement object is how one accesses the result set returned by the
   *     query.
   *   - Database::RETURN_AFFECTED: Return the number of rows affected by an
   *     UPDATE or DELETE query. Be aware that means the number of rows actually
   *     changed, not the number of rows matched by the WHERE clause.
   *   - Database::RETURN_INSERT_ID: Return the sequence ID (primary key)
   *     created by an INSERT statement on a table that contains a serial
   *     column.
   *   - Database::RETURN_NULL: Do not return anything, as there is no
   *     meaningful value to return. That is the case for INSERT queries on
   *     tables that do not contain a serial column.
   * - throw_exception: By default, the database system will catch any errors
   *   on a query as an Exception, log it, and then rethrow it so that code
   *   further up the call chain can take an appropriate action. To suppress
   *   that behavior and simply return NULL on failure, set this option to
   *   FALSE.
   *
   * @return
   *   An array of default query options.
   */
  protected function defaultOptions() {
    return [
      'target' => 'default',
      'fetch' => PDO::FETCH_OBJ,
      'return' => Database::RETURN_STATEMENT,
      'throw_exception' => TRUE,
    ];
  }

  /**
   * Returns the connection information for this connection object.
   *
   * Note that Database::getConnectionInfo() is for requesting information
   * about an arbitrary database connection that is defined. This method
   * is for requesting the connection information of this specific
   * open connection object.
   *
   * @return
   *   An array of the connection information. The exact list of
   *   properties is driver-dependent.
   */
  public function getConnectionOptions() {
    return $this->connectionOptions;
  }

  /**
   * Set the list of prefixes used by this database connection.
   *
   * @param $prefix
   *   The prefixes, in any of the multiple forms documented in
   *   default.settings.php.
   */
  protected function setPrefix($prefix) {
    if (is_array($prefix)) {
      $this->prefixes = $prefix + ['default' => ''];
    }
    else {
      $this->prefixes = ['default' => $prefix];
    }

    // Set up variables for use in prefixTables(). Replace table-specific
    // prefixes first.
    $this->prefixSearch = [];
    $this->prefixReplace = [];
    foreach ($this->prefixes as $key => $val) {
      if ($key != 'default') {
        $this->prefixSearch[] = '{' . $key . '}';
        $this->prefixReplace[] = $val . $key;
      }
    }
    // Then replace remaining tables with the default prefix.
    $this->prefixSearch[] = '{';
    $this->prefixReplace[] = $this->prefixes['default'];
    $this->prefixSearch[] = '}';
    $this->prefixReplace[] = '';
  }

  /**
   * Appends a database prefix to all tables in a query.
   *
   * Queries sent to Drupal should wrap all table names in curly brackets. This
   * function searches for this syntax and adds Drupal's table prefix to all
   * tables, allowing Drupal to coexist with other systems in the same database
   * and/or schema if necessary.
   *
   * @param $sql
   *   A string containing a partial or entire SQL query.
   *
   * @return
   *   The properly-prefixed string.
   */
  public function prefixTables($sql) {
    return str_replace($this->prefixSearch, $this->prefixReplace, $sql);
  }

  /**
   * Find the prefix for a table.
   *
   * This function is for when you want to know the prefix of a table. This
   * is not used in prefixTables due to performance reasons.
   */
  public function tablePrefix($table = 'default') {
    if (isset($this->prefixes[$table])) {
      return $this->prefixes[$table];
    }
    else {
      return $this->prefixes['default'];
    }
  }

  /**
   * Prepares a query string and returns the prepared statement.
   *
   * This method caches prepared statements, reusing them when
   * possible. It also prefixes tables names enclosed in curly-braces.
   *
   * @param $query
   *   The query string as SQL, with curly-braces surrounding the
   *   table names.
   *
   * @return \DatabaseStatementInterface
   *   A PDO prepared statement ready for its execute() method.
   */
  public function prepareQuery($query) {
    $query = $this->prefixTables($query);

    // Call PDO::prepare.
    return parent::prepare($query);
  }

  /**
   * Tells this connection object what its target value is.
   *
   * This is needed for logging and auditing. It's sloppy to do in the
   * constructor because the constructor for child classes has a different
   * signature. We therefore also ensure that this function is only ever
   * called once.
   *
   * @param $target
   *   The target this connection is for. Set to NULL (default) to disable
   *   logging entirely.
   */
  public function setTarget($target = NULL) {
    if (!isset($this->target)) {
      $this->target = $target;
    }
  }

  /**
   * Returns the target this connection is associated with.
   *
   * @return
   *   The target string of this connection.
   */
  public function getTarget() {
    return $this->target;
  }

  /**
   * Tells this connection object what its key is.
   *
   * @param $target
   *   The key this connection is for.
   */
  public function setKey($key) {
    if (!isset($this->key)) {
      $this->key = $key;
    }
  }

  /**
   * Returns the key this connection is associated with.
   *
   * @return
   *   The key of this connection.
   */
  public function getKey() {
    return $this->key;
  }

  /**
   * Associates a logging object with this connection.
   *
   * @param $logger
   *   The logging object we want to use.
   */
  public function setLogger(DatabaseLog $logger) {
    $this->logger = $logger;
  }

  /**
   * Gets the current logging object for this connection.
   *
   * @return DatabaseLog
   *   The current logging object for this connection. If there isn't one,
   *   NULL is returned.
   */
  public function getLogger() {
    return $this->logger;
  }

  /**
   * Creates the appropriate sequence name for a given table and serial field.
   *
   * This information is exposed to all database drivers, although it is only
   * useful on some of them. This method is table prefix-aware.
   *
   * @param $table
   *   The table name to use for the sequence.
   * @param $field
   *   The field name to use for the sequence.
   *
   * @return
   *   A table prefix-parsed string for the sequence name.
   */
  public function makeSequenceName($table, $field) {
    return $this->prefixTables('{' . $table . '}_' . $field . '_seq');
  }

  /**
   * Flatten an array of query comments into a single comment string.
   *
   * The comment string will be sanitized to avoid SQL injection attacks.
   *
   * @param $comments
   *   An array of query comment strings.
   *
   * @return
   *   A sanitized comment string.
   */
  public function makeComment($comments) {
    if (empty($comments)) {
      return '';
    }

    // Flatten the array of comments.
    $comment = implode('; ', $comments);

    // Sanitize the comment string so as to avoid SQL injection attacks.
    return '/* ' . $this->filterComment($comment) . ' */ ';
  }

  /**
   * Sanitize a query comment string.
   *
   * Ensure a query comment does not include strings such as "* /" that might
   * terminate the comment early. This avoids SQL injection attacks via the
   * query comment. The comment strings in this example are separated by a
   * space to avoid PHP parse errors.
   *
   * For example, the comment:
   *
   * @code
   * db_update('example')
   *  ->condition('id', $id)
   *  ->fields(array('field2' => 10))
   *  ->comment('Exploit * / DROP TABLE node; --')
   *  ->execute()
   * @endcode
   *
   * Would result in the following SQL statement being generated:
   * @code
   * "/ * Exploit * / DROP TABLE node; -- * / UPDATE example SET field2=..."
   * @endcode
   *
   * Unless the comment is sanitised first, the SQL server would drop the
   * node table and ignore the rest of the SQL statement.
   *
   * @param $comment
   *   A query comment string.
   *
   * @return
   *   A sanitized version of the query comment string.
   */
  protected function filterComment($comment = '') {
    return strtr($comment, ['*' => ' * ']);
  }

  /**
   * Executes a query string against the database.
   *
   * This method provides a central handler for the actual execution of every
   * query. All queries executed by Drupal are executed as PDO prepared
   * statements.
   *
   * @param $query
   *   The query to execute. In most cases this will be a string containing
   *   an SQL query with placeholders. An already-prepared instance of
   *   DatabaseStatementInterface may also be passed in order to allow calling
   *   code to manually bind variables to a query. If a
   *   DatabaseStatementInterface is passed, the $args array will be ignored.
   *   It is extremely rare that module code will need to pass a statement
   *   object to this method. It is used primarily for database drivers for
   *   databases that require special LOB field handling.
   * @param $args
   *   An array of arguments for the prepared statement. If the prepared
   *   statement uses ? placeholders, this array must be an indexed array.
   *   If it contains named placeholders, it must be an associative array.
   * @param $options
   *   An associative array of options to control how the query is run. See
   *   the documentation for DatabaseConnection::defaultOptions() for details.
   *
   * @return DatabaseStatementInterface
   *   This method will return one of: the executed statement, the number of
   *   rows affected by the query (not the number matched), or the generated
   *   insert ID of the last query, depending on the value of
   *   $options['return']. Typically that value will be set by default or a
   *   query builder and should not be set by a user. If there is an error,
   *   this method will return NULL and may throw an exception if
   *   $options['throw_exception'] is TRUE.
   *
   * @throws PDOException
   */
  public function query($query, array $args = [], $options = []) {

    // Use default values if not already set.
    $options += $this->defaultOptions();

    try {
      // We allow either a pre-bound statement object or a literal string.
      // In either case, we want to end up with an executed statement object,
      // which we pass to PDOStatement::execute.
      if ($query instanceof DatabaseStatementInterface) {
        $stmt = $query;
        $stmt->execute(NULL, $options);
      }
      else {
        $this->expandArguments($query, $args);
        $stmt = $this->prepareQuery($query);
        $stmt->execute($args, $options);
      }

      // Depending on the type of query we may need to return a different value.
      // See DatabaseConnection::defaultOptions() for a description of each
      // value.
      switch ($options['return']) {
        case Database::RETURN_STATEMENT:
          return $stmt;
        case Database::RETURN_AFFECTED:
          return $stmt->rowCount();
        case Database::RETURN_INSERT_ID:
          return $this->lastInsertId();
        case Database::RETURN_NULL:
          return;
        default:
          throw new PDOException('Invalid return directive: ' . $options['return']);
      }
    } catch (PDOException $e) {
      if ($options['throw_exception']) {
        // Add additional debug information.
        if ($query instanceof DatabaseStatementInterface) {
          $e->query_string = $stmt->getQueryString();
        }
        else {
          $e->query_string = $query;
        }
        $e->args = $args;
        throw $e;
      }
      return NULL;
    }
  }

  /**
   * Expands out shorthand placeholders.
   *
   * Drupal supports an alternate syntax for doing arrays of values. We
   * therefore need to expand them out into a full, executable query string.
   *
   * @param $query
   *   The query string to modify.
   * @param $args
   *   The arguments for the query.
   *
   * @return
   *   TRUE if the query was modified, FALSE otherwise.
   */
  protected function expandArguments(&$query, &$args) {
    $modified = FALSE;

    // If the placeholder value to insert is an array, assume that we need
    // to expand it out into a comma-delimited set of placeholders.
    foreach (array_filter($args, 'is_array') as $key => $data) {
      $new_keys = [];
      foreach (array_values($data) as $i => $value) {
        // This assumes that there are no other placeholders that use the same
        // name.  For example, if the array placeholder is defined as :example
        // and there is already an :example_2 placeholder, this will generate
        // a duplicate key.  We do not account for that as the calling code
        // is already broken if that happens.
        $new_keys[$key . '_' . $i] = $value;
      }

      // Update the query with the new placeholders.
      // preg_replace is necessary to ensure the replacement does not affect
      // placeholders that start with the same exact text. For example, if the
      // query contains the placeholders :foo and :foobar, and :foo has an
      // array of values, using str_replace would affect both placeholders,
      // but using the following preg_replace would only affect :foo because
      // it is followed by a non-word character.
      $query = preg_replace('#' . $key . '\b#', implode(', ', array_keys($new_keys)), $query);

      // Update the args array with the new placeholders.
      unset($args[$key]);
      $args += $new_keys;

      $modified = TRUE;
    }

    return $modified;
  }

  /**
   * Gets the driver-specific override class if any for the specified class.
   *
   * @param string $class
   *   The class for which we want the potentially driver-specific class.
   * @param array $files
   *   The name of the files in which the driver-specific class can be.
   * @param $use_autoload
   *   If TRUE, attempt to load classes using PHP's autoload capability
   *   as well as the manual approach here.
   *
   * @return string
   *   The name of the class that should be used for this driver.
   */
  public function getDriverClass($class, array $files = [], $use_autoload = FALSE) {
    if (empty($this->driverClasses[$class])) {
      $driver = $this->driver();
      $this->driverClasses[$class] = $class . '_' . $driver;
      Database::loadDriverFile($driver, $files);
      if (!class_exists($this->driverClasses[$class], $use_autoload)) {
        $this->driverClasses[$class] = $class;
      }
    }
    return $this->driverClasses[$class];
  }

  /**
   * Prepares and returns a SELECT query object.
   *
   * @param $table
   *   The base table for this query, that is, the first table in the FROM
   *   clause. This table will also be used as the "base" table for query_alter
   *   hook implementations.
   * @param $alias
   *   The alias of the base table of this query.
   * @param $options
   *   An array of options on the query.
   *
   * @return SelectQueryInterface
   *   An appropriate SelectQuery object for this database connection. Note that
   *   it may be a driver-specific subclass of SelectQuery, depending on the
   *   driver.
   *
   * @see SelectQuery
   */
  public function select($table, $alias = NULL, array $options = []) {
    //$class = $this->getDriverClass('d7\SelectQuery', ['query.inc', 'select.inc']);
    $class = $this->getDriverClass('d7\SelectQuery', ['query', 'select']);
    return new $class($table, $alias, $this, $options);
  }

  /**
   * Prepares and returns an INSERT query object.
   *
   * @param $options
   *   An array of options on the query.
   *
   * @return \InsertQuery
   *   A new InsertQuery object.
   *
   * @see InsertQuery
   */
  public function insert($table, array $options = []) {
    $class = $this->getDriverClass('d7\InsertQuery', ['query.inc']);
    return new $class($this, $table, $options);
  }

  /**
   * Prepares and returns a MERGE query object.
   *
   * @param $options
   *   An array of options on the query.
   *
   * @return \MergeQuery
   *   A new MergeQuery object.
   *
   * @see MergeQuery
   */
  public function merge($table, array $options = []) {
    $class = $this->getDriverClass('MergeQuery', ['query.inc']);
    return new $class($this, $table, $options);
  }


  /**
   * Prepares and returns an UPDATE query object.
   *
   * @param $options
   *   An array of options on the query.
   *
   * @return \UpdateQuery
   *   A new UpdateQuery object.
   *
   * @see UpdateQuery
   */
  public function update($table, array $options = []) {
    $class = $this->getDriverClass('UpdateQuery', ['query.inc']);
    return new $class($this, $table, $options);
  }

  /**
   * Prepares and returns a DELETE query object.
   *
   * @param $options
   *   An array of options on the query.
   *
   * @return \DeleteQuery
   *   A new DeleteQuery object.
   *
   * @see DeleteQuery
   */
  public function delete($table, array $options = []) {
    $class = $this->getDriverClass('DeleteQuery', ['query.inc']);
    return new $class($this, $table, $options);
  }

  /**
   * Prepares and returns a TRUNCATE query object.
   *
   * @param $options
   *   An array of options on the query.
   *
   * @return \TruncateQuery
   *   A new TruncateQuery object.
   *
   * @see TruncateQuery
   */
  public function truncate($table, array $options = []) {
    $class = $this->getDriverClass('TruncateQuery', ['query.inc']);
    return new $class($this, $table, $options);
  }

  /**
   * Returns a DatabaseSchema object for manipulating the schema.
   *
   * This method will lazy-load the appropriate schema library file.
   *
   * @return DatabaseSchema
   *   The DatabaseSchema object for this connection.
   */
  public function schema() {
    if (empty($this->schema)) {
      $class = $this->getDriverClass('DatabaseSchema', ['schema.inc']);
      if (class_exists($class)) {
        $this->schema = new $class($this);
      }
    }
    return $this->schema;
  }

  /**
   * Escapes a table name string.
   *
   * Force all table names to be strictly alphanumeric-plus-underscore.
   * For some database drivers, it may also wrap the table name in
   * database-specific escape characters.
   *
   * @return string
   *   The sanitized table name string.
   */
  public function escapeTable($table) {
    if (!isset($this->escapedNames[$table])) {
      $this->escapedNames[$table] = preg_replace('/[^A-Za-z0-9_.]+/', '', $table);
    }
    return $this->escapedNames[$table];
  }

  /**
   * Escapes a field name string.
   *
   * Force all field names to be strictly alphanumeric-plus-underscore.
   * For some database drivers, it may also wrap the field name in
   * database-specific escape characters.
   *
   * @return string
   *   The sanitized field name string.
   */
  public function escapeField($field) {
    if (!isset($this->escapedNames[$field])) {
      $this->escapedNames[$field] = preg_replace('/[^A-Za-z0-9_.]+/', '', $field);
    }
    return $this->escapedNames[$field];
  }

  /**
   * Escapes an alias name string.
   *
   * Force all alias names to be strictly alphanumeric-plus-underscore. In
   * contrast to DatabaseConnection::escapeField() /
   * DatabaseConnection::escapeTable(), this doesn't allow the period (".")
   * because that is not allowed in aliases.
   *
   * @return string
   *   The sanitized field name string.
   */
  public function escapeAlias($field) {
    if (!isset($this->escapedAliases[$field])) {
      $this->escapedAliases[$field] = preg_replace('/[^A-Za-z0-9_]+/', '', $field);
    }
    return $this->escapedAliases[$field];
  }

  /**
   * Escapes characters that work as wildcard characters in a LIKE pattern.
   *
   * The wildcard characters "%" and "_" as well as backslash are prefixed with
   * a backslash. Use this to do a search for a verbatim string without any
   * wildcard behavior.
   *
   * For example, the following does a case-insensitive query for all rows whose
   * name starts with $prefix:
   *
   * @code
   * $result = db_query(
   *   'SELECT * FROM person WHERE name LIKE :pattern',
   *   array(':pattern' => db_like($prefix) . '%')
   * );
   * @endcode
   *
   * Backslash is defined as escape character for LIKE patterns in
   * DatabaseCondition::mapConditionOperator().
   *
   * @param $string
   *   The string to escape.
   *
   * @return
   *   The escaped string.
   */
  public function escapeLike($string) {
    return addcslashes($string, '\%_');
  }

  /**
   * Determines if there is an active transaction open.
   *
   * @return
   *   TRUE if we're currently in a transaction, FALSE otherwise.
   */
  public function inTransaction() {
    return ($this->transactionDepth() > 0);
  }

  /**
   * Determines current transaction depth.
   */
  public function transactionDepth() {
    return count($this->transactionLayers);
  }

  /**
   * Returns a new DatabaseTransaction object on this connection.
   *
   * @param $name
   *   Optional name of the savepoint.
   *
   * @return \DatabaseTransaction
   *   A DatabaseTransaction object.
   *
   * @see DatabaseTransaction
   */
  public function startTransaction($name = '') {
    $class = $this->getDriverClass('DatabaseTransaction');
    return new $class($this, $name);
  }

  /**
   * Rolls back the transaction entirely or to a named savepoint.
   *
   * This method throws an exception if no transaction is active.
   *
   * @param $savepoint_name
   *   The name of the savepoint. The default, 'drupal_transaction', will roll
   *   the entire transaction back.
   *
   * @throws \DatabaseTransactionNoActiveException
   *
   * @see DatabaseTransaction::rollback()
   */
  public function rollback($savepoint_name = 'drupal_transaction') {
    if (!$this->supportsTransactions()) {
      return;
    }
    if (!$this->inTransaction()) {
      throw new DatabaseTransactionNoActiveException();
    }
    // A previous rollback to an earlier savepoint may mean that the savepoint
    // in question has already been accidentally committed.
    if (!isset($this->transactionLayers[$savepoint_name])) {
      throw new DatabaseTransactionNoActiveException();
    }

    // We need to find the point we're rolling back to, all other savepoints
    // before are no longer needed. If we rolled back other active savepoints,
    // we need to throw an exception.
    $rolled_back_other_active_savepoints = FALSE;
    while ($savepoint = array_pop($this->transactionLayers)) {
      if ($savepoint == $savepoint_name) {
        // If it is the last the transaction in the stack, then it is not a
        // savepoint, it is the transaction itself so we will need to roll back
        // the transaction rather than a savepoint.
        if (empty($this->transactionLayers)) {
          break;
        }
        $this->query('ROLLBACK TO SAVEPOINT ' . $savepoint);
        $this->popCommittableTransactions();
        if ($rolled_back_other_active_savepoints) {
          throw new \DatabaseTransactionOutOfOrderException();
        }
        return;
      }
      else {
        $rolled_back_other_active_savepoints = TRUE;
      }
    }
    parent::rollBack();
    if ($rolled_back_other_active_savepoints) {
      throw new DatabaseTransactionOutOfOrderException();
    }
  }

  /**
   * Increases the depth of transaction nesting.
   *
   * If no transaction is already active, we begin a new transaction.
   *
   * @throws \DatabaseTransactionNameNonUniqueException
   *
   * @see DatabaseTransaction
   */
  public function pushTransaction($name) {
    if (!$this->supportsTransactions()) {
      return;
    }
    if (isset($this->transactionLayers[$name])) {
      throw new DatabaseTransactionNameNonUniqueException($name . " is already in use.");
    }
    // If we're already in a transaction then we want to create a savepoint
    // rather than try to create another transaction.
    if ($this->inTransaction()) {
      $this->query('SAVEPOINT ' . $name);
    }
    else {
      parent::beginTransaction();
    }
    $this->transactionLayers[$name] = $name;
  }

  /**
   * Decreases the depth of transaction nesting.
   *
   * If we pop off the last transaction layer, then we either commit or roll
   * back the transaction as necessary. If no transaction is active, we return
   * because the transaction may have manually been rolled back.
   *
   * @param $name
   *   The name of the savepoint
   *
   * @throws DatabaseTransactionNoActiveException
   * @throws \DatabaseTransactionCommitFailedException
   *
   * @see DatabaseTransaction
   */
  public function popTransaction($name) {
    if (!$this->supportsTransactions()) {
      return;
    }
    // The transaction has already been committed earlier. There is nothing we
    // need to do. If this transaction was part of an earlier out-of-order
    // rollback, an exception would already have been thrown by
    // Database::rollback().
    if (!isset($this->transactionLayers[$name])) {
      return;
    }

    // Mark this layer as committable.
    $this->transactionLayers[$name] = FALSE;
    $this->popCommittableTransactions();
  }

  /**
   * Internal function: commit all the transaction layers that can commit.
   */
  protected function popCommittableTransactions() {
    // Commit all the committable layers.
    foreach (array_reverse($this->transactionLayers) as $name => $active) {
      // Stop once we found an active transaction.
      if ($active) {
        break;
      }

      // If there are no more layers left then we should commit.
      unset($this->transactionLayers[$name]);
      if (empty($this->transactionLayers)) {
        if (!parent::commit()) {
          throw new DatabaseTransactionCommitFailedException();
        }
      }
      else {
        $this->query('RELEASE SAVEPOINT ' . $name);
      }
    }
  }

  /**
   * Runs a limited-range query on this database object.
   *
   * Use this as a substitute for ->query() when a subset of the query is to be
   * returned. User-supplied arguments to the query should be passed in as
   * separate parameters so that they can be properly escaped to avoid SQL
   * injection attacks.
   *
   * @param $query
   *   A string containing an SQL query.
   * @param $args
   *   An array of values to substitute into the query at placeholder markers.
   * @param $from
   *   The first result row to return.
   * @param $count
   *   The maximum number of result rows to return.
   * @param $options
   *   An array of options on the query.
   *
   * @return DatabaseStatementInterface
   *   A database query result resource, or NULL if the query was not executed
   *   correctly.
   */
  abstract public function queryRange($query, $from, $count, array $args = [], array $options = []);

  /**
   * Generates a temporary table name.
   *
   * @return
   *   A table name.
   */
  protected function generateTemporaryTableName() {
    return "db_temporary_" . $this->temporaryNameIndex++;
  }

  /**
   * Runs a SELECT query and stores its results in a temporary table.
   *
   * Use this as a substitute for ->query() when the results need to stored
   * in a temporary table. Temporary tables exist for the duration of the page
   * request. User-supplied arguments to the query should be passed in as
   * separate parameters so that they can be properly escaped to avoid SQL
   * injection attacks.
   *
   * Note that if you need to know how many results were returned, you should do
   * a SELECT COUNT(*) on the temporary table afterwards.
   *
   * @param $query
   *   A string containing a normal SELECT SQL query.
   * @param $args
   *   An array of values to substitute into the query at placeholder markers.
   * @param $options
   *   An associative array of options to control how the query is run. See
   *   the documentation for DatabaseConnection::defaultOptions() for details.
   *
   * @return
   *   The name of the temporary table.
   */
  abstract function queryTemporary($query, array $args = [], array $options = []);

  /**
   * Returns the type of database driver.
   *
   * This is not necessarily the same as the type of the database itself. For
   * instance, there could be two MySQL drivers, mysql and mysql_mock. This
   * function would return different values for each, but both would return
   * "mysql" for databaseType().
   */
  abstract public function driver();

  /**
   * Returns the version of the database server.
   */
  public function version() {
    return $this->getAttribute(PDO::ATTR_SERVER_VERSION);
  }

  /**
   * Determines if this driver supports transactions.
   *
   * @return
   *   TRUE if this connection supports transactions, FALSE otherwise.
   */
  public function supportsTransactions() {
    return $this->transactionSupport;
  }

  /**
   * Determines if this driver supports transactional DDL.
   *
   * DDL queries are those that change the schema, such as ALTER queries.
   *
   * @return
   *   TRUE if this connection supports transactions for DDL queries, FALSE
   *   otherwise.
   */
  public function supportsTransactionalDDL() {
    return $this->transactionalDDLSupport;
  }

  /**
   * Returns the name of the PDO driver for this connection.
   */
  abstract public function databaseType();


  /**
   * Gets any special processing requirements for the condition operator.
   *
   * Some condition types require special processing, such as IN, because
   * the value data they pass in is not a simple value. This is a simple
   * overridable lookup function. Database connections should define only
   * those operators they wish to be handled differently than the default.
   *
   * @param $operator
   *   The condition operator, such as "IN", "BETWEEN", etc. Case-sensitive.
   *
   * @return
   *   The extra handling directives for the specified operator, or NULL.
   *
   * @see DatabaseCondition::compile()
   */
  abstract public function mapConditionOperator($operator);

  /**
   * Throws an exception to deny direct access to transaction commits.
   *
   * We do not want to allow users to commit transactions at any time, only
   * by destroying the transaction object or allowing it to go out of scope.
   * A direct commit bypasses all of the safety checks we've built on top of
   * PDO's transaction routines.
   *
   * @throws \DatabaseTransactionExplicitCommitNotAllowedException
   *
   * @see DatabaseTransaction
   */
  public function commit() {
    throw new DatabaseTransactionExplicitCommitNotAllowedException();
  }

  /**
   * Retrieves an unique id from a given sequence.
   *
   * Use this function if for some reason you can't use a serial field. For
   * example, MySQL has no ways of reading of the current value of a sequence
   * and PostgreSQL can not advance the sequence to be larger than a given
   * value. Or sometimes you just need a unique integer.
   *
   * @param $existing_id
   *   After a database import, it might be that the sequences table is behind,
   *   so by passing in the maximum existing id, it can be assured that we
   *   never issue the same id.
   *
   * @return
   *   An integer number larger than any number returned by earlier calls and
   *   also larger than the $existing_id if one was passed in.
   */
  abstract public function nextId($existing_id = 0);

  /**
   * Checks whether utf8mb4 support is configurable in settings.php.
   *
   * @return bool
   */
  public function utf8mb4IsConfigurable() {
    // Since 4 byte UTF-8 is not supported by default, there is nothing to
    // configure.
    return FALSE;
  }

  /**
   * Checks whether utf8mb4 support is currently active.
   *
   * @return bool
   */
  public function utf8mb4IsActive() {
    // Since 4 byte UTF-8 is not supported by default, there is nothing to
    // activate.
    return FALSE;
  }

  /**
   * Checks whether utf8mb4 support is available on the current database system.
   *
   * @return bool
   */
  public function utf8mb4IsSupported() {
    // By default we assume that the database backend may not support 4 byte
    // UTF-8.
    return FALSE;
  }
}

/**
 * @addtogroup database
 * @{
 */

class DatabaseConnection_wpdb extends DatabaseConnection {

  /**
   * Flag to indicate if the cleanup function in __destruct() should run.
   *
   * @var boolean
   */
  protected $needsCleanup = FALSE;

  public function __construct(array $connection_options = array()) {
    // This driver defaults to transaction support, except if explicitly passed FALSE.
    $this->transactionSupport = !isset($connection_options['transactions']) || ($connection_options['transactions'] !== FALSE);

    // MySQL never supports transactional DDL.
    $this->transactionalDDLSupport = FALSE;

    $this->connectionOptions = $connection_options;

    $charset = 'utf8';
    // Check if the charset is overridden to utf8mb4 in settings.php.
    if ($this->utf8mb4IsActive()) {
      $charset = 'utf8mb4';
    }

    // The DSN should use either a socket or a host/port.
    if (isset($connection_options['unix_socket'])) {
      $dsn = 'mysql:unix_socket=' . $connection_options['unix_socket'];
    }
    else {
      // Default to TCP connection on port 3306.
      $dsn = 'mysql:host=' . $connection_options['host'] . ';port=' . (empty($connection_options['port']) ? 3306 : $connection_options['port']);
    }
    // Character set is added to dsn to ensure PDO uses the proper character
    // set when escaping. This has security implications. See
    // https://www.drupal.org/node/1201452 for further discussion.
    $dsn .= ';charset=' . $charset;
    $dsn .= ';dbname=' . $connection_options['database'];
    // Allow PDO options to be overridden.
    $connection_options += array(
      'pdo' => array(),
    );
    $connection_options['pdo'] += array(
      // So we don't have to mess around with cursors and unbuffered queries by default.
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
      // Because MySQL's prepared statements skip the query cache, because it's dumb.
      PDO::ATTR_EMULATE_PREPARES => TRUE,
    );
    if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
      // An added connection option in PHP 5.5.21+ to optionally limit SQL to a
      // single statement like mysqli.
      $connection_options['pdo'] += array(PDO::MYSQL_ATTR_MULTI_STATEMENTS => FALSE);
    }

    parent::__construct($dsn, $connection_options['username'], $connection_options['password'], $connection_options['pdo']);

    // Force MySQL to use the UTF-8 character set. Also set the collation, if a
    // certain one has been set; otherwise, MySQL defaults to 'utf8_general_ci'
    // for UTF-8.
    if (!empty($connection_options['collation'])) {
      $this->exec('SET NAMES ' . $charset . ' COLLATE ' . $connection_options['collation']);
    }
    else {
      $this->exec('SET NAMES ' . $charset);
    }

    // Set MySQL init_commands if not already defined.  Default Drupal's MySQL
    // behavior to conform more closely to SQL standards.  This allows Drupal
    // to run almost seamlessly on many different kinds of database systems.
    // These settings force MySQL to behave the same as postgresql, or sqlite
    // in regards to syntax interpretation and invalid data handling.  See
    // http://drupal.org/node/344575 for further discussion. Also, as MySQL 5.5
    // changed the meaning of TRADITIONAL we need to spell out the modes one by
    // one.
    $connection_options += array(
      'init_commands' => array(),
    );
    $connection_options['init_commands'] += array(
      'sql_mode' => "SET sql_mode = 'REAL_AS_FLOAT,PIPES_AS_CONCAT,ANSI_QUOTES,IGNORE_SPACE,STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER'",
    );
    // Execute initial commands.
    foreach ($connection_options['init_commands'] as $sql) {
      $this->exec($sql);
    }
  }

  public function __destruct() {
    if ($this->needsCleanup) {
      $this->nextIdDelete();
    }
  }

  public function queryRange($query, $from, $count, array $args = array(), array $options = array()) {
    return $this->query($query . ' LIMIT ' . (int) $from . ', ' . (int) $count, $args, $options);
  }

  public function queryTemporary($query, array $args = array(), array $options = array()) {
    $tablename = $this->generateTemporaryTableName();
    $this->query('CREATE TEMPORARY TABLE {' . $tablename . '} Engine=MEMORY ' . $query, $args, $options);
    return $tablename;
  }

  public function driver() {
    return 'mysql';
  }

  public function databaseType() {
    return 'mysql';
  }

  public function mapConditionOperator($operator) {
    // We don't want to override any of the defaults.
    return NULL;
  }

  public function nextId($existing_id = 0) {
    $new_id = $this->query('INSERT INTO {sequences} () VALUES ()', array(), array('return' => \Database::RETURN_INSERT_ID));
    // This should only happen after an import or similar event.
    if ($existing_id >= $new_id) {
      // If we INSERT a value manually into the sequences table, on the next
      // INSERT, MySQL will generate a larger value. However, there is no way
      // of knowing whether this value already exists in the table. MySQL
      // provides an INSERT IGNORE which would work, but that can mask problems
      // other than duplicate keys. Instead, we use INSERT ... ON DUPLICATE KEY
      // UPDATE in such a way that the UPDATE does not do anything. This way,
      // duplicate keys do not generate errors but everything else does.
      $this->query('INSERT INTO {sequences} (value) VALUES (:value) ON DUPLICATE KEY UPDATE value = value', array(':value' => $existing_id));
      $new_id = $this->query('INSERT INTO {sequences} () VALUES ()', array(), array('return' => Database::RETURN_INSERT_ID));
    }
    $this->needsCleanup = TRUE;
    return $new_id;
  }

  public function nextIdDelete() {
    // While we want to clean up the table to keep it up from occupying too
    // much storage and memory, we must keep the highest value in the table
    // because InnoDB  uses an in-memory auto-increment counter as long as the
    // server runs. When the server is stopped and restarted, InnoDB
    // reinitializes the counter for each table for the first INSERT to the
    // table based solely on values from the table so deleting all values would
    // be a problem in this case. Also, TRUNCATE resets the auto increment
    // counter.
    try {
      $max_id = $this->query('SELECT MAX(value) FROM {sequences}')->fetchField();
      // We know we are using MySQL here, no need for the slower db_delete().
      $this->query('DELETE FROM {sequences} WHERE value < :value', array(':value' => $max_id));
    }
      // During testing, this function is called from shutdown with the
      // simpletest prefix stored in $this->connection, and those tables are gone
      // by the time shutdown is called so we need to ignore the database
      // errors. There is no problem with completely ignoring errors here: if
      // these queries fail, the sequence will work just fine, just use a bit
      // more database storage and memory.
    catch (PDOException $e) {
    }
  }

  /**
   * Overridden to work around issues to MySQL not supporting transactional DDL.
   */
  protected function popCommittableTransactions() {
    // Commit all the committable layers.
    foreach (array_reverse($this->transactionLayers) as $name => $active) {
      // Stop once we found an active transaction.
      if ($active) {
        break;
      }

      // If there are no more layers left then we should commit.
      unset($this->transactionLayers[$name]);
      if (empty($this->transactionLayers)) {
        if (!PDO::commit()) {
          throw new \DatabaseTransactionCommitFailedException();
        }
      }
      else {
        // Attempt to release this savepoint in the standard way.
        try {
          $this->query('RELEASE SAVEPOINT ' . $name);
        }
        catch (PDOException $e) {
          // However, in MySQL (InnoDB), savepoints are automatically committed
          // when tables are altered or created (DDL transactions are not
          // supported). This can cause exceptions due to trying to release
          // savepoints which no longer exist.
          //
          // To avoid exceptions when no actual error has occurred, we silently
          // succeed for MySQL error code 1305 ("SAVEPOINT does not exist").
          if ($e->errorInfo[1] == '1305') {
            // If one SAVEPOINT was released automatically, then all were.
            // Therefore, clean the transaction stack.
            $this->transactionLayers = array();
            // We also have to explain to PDO that the transaction stack has
            // been cleaned-up.
            PDO::commit();
          }
          else {
            throw $e;
          }
        }
      }
    }
  }

  public function utf8mb4IsConfigurable() {
    return TRUE;
  }

  public function utf8mb4IsActive() {
    return isset($this->connectionOptions['charset']) && $this->connectionOptions['charset'] === 'utf8mb4';
  }

  public function utf8mb4IsSupported() {
    // Ensure that the MySQL driver supports utf8mb4 encoding.
    $version = $this->getAttribute(PDO::ATTR_CLIENT_VERSION);
    if (strpos($version, 'mysqlnd') !== FALSE) {
      // The mysqlnd driver supports utf8mb4 starting at version 5.0.9.
      $version = preg_replace('/^\D+([\d.]+).*/', '$1', $version);
      if (version_compare($version, '5.0.9', '<')) {
        return FALSE;
      }
    }
    else {
      // The libmysqlclient driver supports utf8mb4 starting at version 5.5.3.
      if (version_compare($version, '5.5.3', '<')) {
        return FALSE;
      }
    }

    // Ensure that the MySQL server supports large prefixes and utf8mb4.
    try {
      $this->query("CREATE TABLE {drupal_utf8mb4_test} (id VARCHAR(255), PRIMARY KEY(id(255))) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ROW_FORMAT=DYNAMIC ENGINE=INNODB");
    }
    catch (Exception $e) {
      return FALSE;
    }
    $this->query("DROP TABLE {drupal_utf8mb4_test}");
    return TRUE;
  }
}

/**
 * Primary front-controller for the database system.
 *
 * This class is uninstantiatable and un-extendable. It acts to encapsulate
 * all control and shepherding of database connections into a single location
 * without the use of globals.
 */
abstract class Database {

  /**
   * Flag to indicate a query call should simply return NULL.
   *
   * This is used for queries that have no reasonable return value anyway, such
   * as INSERT statements to a table without a serial primary key.
   */
  const RETURN_NULL = 0;

  /**
   * Flag to indicate a query call should return the prepared statement.
   */
  const RETURN_STATEMENT = 1;

  /**
   * Flag to indicate a query call should return the number of affected rows.
   */
  const RETURN_AFFECTED = 2;

  /**
   * Flag to indicate a query call should return the "last insert id".
   */
  const RETURN_INSERT_ID = 3;

  /**
   * An nested array of all active connections. It is keyed by database name
   * and target.
   *
   * @var array
   */
  static protected $connections = [];

  /**
   * A processed copy of the database connection information from settings.php.
   *
   * @var array
   */
  static protected $databaseInfo = NULL;

  /**
   * A list of key/target credentials to simply ignore.
   *
   * @var array
   */
  static protected $ignoreTargets = [];

  /**
   * The key of the currently active database connection.
   *
   * @var string
   */
  static protected $activeKey = 'default';

  /**
   * An array of active query log objects.
   *
   * Every connection has one and only one logger object for all targets and
   * logging keys.
   *
   * array(
   *   '$db_key' => DatabaseLog object.
   * );
   *
   * @var array
   */
  static protected $logs = [];

  /**
   * Starts logging a given logging key on the specified connection.
   *
   * @param $logging_key
   *   The logging key to log.
   * @param $key
   *   The database connection key for which we want to log.
   *
   * @return DatabaseLog
   *   The query log object. Note that the log object does support richer
   *   methods than the few exposed through the Database class, so in some
   *   cases it may be desirable to access it directly.
   *
   * @see DatabaseLog
   */
  final public static function startLog($logging_key, $key = 'default') {
    if (empty(self::$logs[$key])) {
      self::$logs[$key] = new DatabaseLog($key);

      // Every target already active for this connection key needs to have the
      // logging object associated with it.
      if (!empty(self::$connections[$key])) {
        foreach (self::$connections[$key] as $connection) {
          $connection->setLogger(self::$logs[$key]);
        }
      }
    }

    self::$logs[$key]->start($logging_key);
    return self::$logs[$key];
  }

  /**
   * Retrieves the queries logged on for given logging key.
   *
   * This method also ends logging for the specified key. To get the query log
   * to date without ending the logger request the logging object by starting
   * it again (which does nothing to an open log key) and call methods on it as
   * desired.
   *
   * @param $logging_key
   *   The logging key to log.
   * @param $key
   *   The database connection key for which we want to log.
   *
   * @return array
   *   The query log for the specified logging key and connection.
   *
   * @see DatabaseLog
   */
  final public static function getLog($logging_key, $key = 'default') {
    if (empty(self::$logs[$key])) {
      return NULL;
    }
    $queries = self::$logs[$key]->get($logging_key);
    self::$logs[$key]->end($logging_key);
    return $queries;
  }

  /**
   * Gets the connection object for the specified database key and target.
   *
   * @param $target
   *   The database target name.
   * @param $key
   *   The database connection key. Defaults to NULL which means the active key.
   *
   * @return \DatabaseConnection
   *   The corresponding connection object.
   */
  final public static function getConnection($target = 'default', $key = NULL) {
    if (!isset($key)) {
      // By default, we want the active connection, set in setActiveConnection.
      $key = self::$activeKey;
    }
    // If the requested target does not exist, or if it is ignored, we fall back
    // to the default target. The target is typically either "default" or
    // "slave", indicating to use a slave SQL server if one is available. If
    // it's not available, then the default/master server is the correct server
    // to use.
    if (!empty(self::$ignoreTargets[$key][$target]) || !isset(self::$databaseInfo[$key][$target])) {
      $target = 'default';
    }

    if (!isset(self::$connections[$key][$target])) {
      // If necessary, a new connection is opened.
      self::$connections[$key][$target] = self::openConnection($key, $target);
    }
    return self::$connections[$key][$target];
  }

  /**
   * Determines if there is an active connection.
   *
   * Note that this method will return FALSE if no connection has been
   * established yet, even if one could be.
   *
   * @return
   *   TRUE if there is at least one database connection established, FALSE
   *   otherwise.
   */
  final public static function isActiveConnection() {
    return !empty(self::$activeKey) && !empty(self::$connections) && !empty(self::$connections[self::$activeKey]);
  }

  /**
   * Sets the active connection to the specified key.
   *
   * @return
   *   The previous database connection key.
   */
  final public static function setActiveConnection($key = 'default') {
    if (empty(self::$databaseInfo)) {
      self::parseConnectionInfo();
    }

    if (!empty(self::$databaseInfo[$key])) {
      $old_key = self::$activeKey;
      self::$activeKey = $key;
      return $old_key;
    }
  }

  /**
   * Process the configuration file for database information.
   */
  final public static function parseConnectionInfo() {
    global $wpdb;

    self::$databaseInfo['default'] = array(
      'default' => array(
        'database' => $wpdb->dbname,
        'username' => $wpdb->dbuser,
        'password' => $wpdb->dbpassword,
        'host' => $wpdb->dbhost,
        'port' => '',
        'prefix' => $wpdb->prefix,
        'driver' => 'wpdb',
      ),
    );

    return;

    // TODO

    global $databases;

    $database_info = is_array($databases) ? $databases : [];
    foreach ($database_info as $index => $info) {
      foreach ($database_info[$index] as $target => $value) {
        // If there is no "driver" property, then we assume it's an array of
        // possible connections for this target. Pick one at random. That allows
        //  us to have, for example, multiple slave servers.
        if (empty($value['driver'])) {
          $database_info[$index][$target] = $database_info[$index][$target][mt_rand(0, count($database_info[$index][$target]) - 1)];
        }

        // Parse the prefix information.
        if (!isset($database_info[$index][$target]['prefix'])) {
          // Default to an empty prefix.
          $database_info[$index][$target]['prefix'] = [
            'default' => '',
          ];
        }
        elseif (!is_array($database_info[$index][$target]['prefix'])) {
          // Transform the flat form into an array form.
          $database_info[$index][$target]['prefix'] = [
            'default' => $database_info[$index][$target]['prefix'],
          ];
        }
      }
    }

    if (!is_array(self::$databaseInfo)) {
      self::$databaseInfo = $database_info;
    }

    // Merge the new $database_info into the existing.
    // array_merge_recursive() cannot be used, as it would make multiple
    // database, user, and password keys in the same database array.
    else {
      foreach ($database_info as $database_key => $database_values) {
        foreach ($database_values as $target => $target_values) {
          self::$databaseInfo[$database_key][$target] = $target_values;
        }
      }
    }
  }

  /**
   * Adds database connection information for a given key/target.
   *
   * This method allows the addition of new connection credentials at runtime.
   * Under normal circumstances the preferred way to specify database
   * credentials is via settings.php. However, this method allows them to be
   * added at arbitrary times, such as during unit tests, when connecting to
   * admin-defined third party databases, etc.
   *
   * If the given key/target pair already exists, this method will be ignored.
   *
   * @param $key
   *   The database key.
   * @param $target
   *   The database target name.
   * @param $info
   *   The database connection information, as it would be defined in
   *   settings.php. Note that the structure of this array will depend on the
   *   database driver it is connecting to.
   */
  public static function addConnectionInfo($key, $target, $info) {
    if (empty(self::$databaseInfo[$key][$target])) {
      self::$databaseInfo[$key][$target] = $info;
    }
  }

  /**
   * Gets information on the specified database connection.
   *
   * @param $connection
   *   The connection key for which we want information.
   */
  final public static function getConnectionInfo($key = 'default') {
    if (empty(self::$databaseInfo)) {
      self::parseConnectionInfo();
    }

    if (!empty(self::$databaseInfo[$key])) {
      return self::$databaseInfo[$key];
    }
  }

  /**
   * Rename a connection and its corresponding connection information.
   *
   * @param $old_key
   *   The old connection key.
   * @param $new_key
   *   The new connection key.
   *
   * @return
   *   TRUE in case of success, FALSE otherwise.
   */
  final public static function renameConnection($old_key, $new_key) {
    if (empty(self::$databaseInfo)) {
      self::parseConnectionInfo();
    }

    if (!empty(self::$databaseInfo[$old_key]) && empty(self::$databaseInfo[$new_key])) {
      // Migrate the database connection information.
      self::$databaseInfo[$new_key] = self::$databaseInfo[$old_key];
      unset(self::$databaseInfo[$old_key]);

      // Migrate over the DatabaseConnection object if it exists.
      if (isset(self::$connections[$old_key])) {
        self::$connections[$new_key] = self::$connections[$old_key];
        unset(self::$connections[$old_key]);
      }

      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Remove a connection and its corresponding connection information.
   *
   * @param $key
   *   The connection key.
   *
   * @return
   *   TRUE in case of success, FALSE otherwise.
   */
  final public static function removeConnection($key) {
    if (isset(self::$databaseInfo[$key])) {
      self::closeConnection(NULL, $key);
      unset(self::$databaseInfo[$key]);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Opens a connection to the server specified by the given key and target.
   *
   * @param $key
   *   The database connection key, as specified in settings.php. The default is
   *   "default".
   * @param $target
   *   The database target to open.
   *
   * @throws \DatabaseConnectionNotDefinedException
   * @throws \DatabaseDriverNotSpecifiedException
   */
  final protected static function openConnection($key, $target) {
    $driver = 'wpdb';
    $driver_class = 'd7\DatabaseConnection_' . $driver;

    if (empty(self::$databaseInfo)) {
      self::parseConnectionInfo();
    }

    // If the requested database does not exist then it is an unrecoverable
    // error.
    if (!isset(self::$databaseInfo[$key])) {
      throw new DatabaseConnectionNotDefinedException('The specified database connection is not defined: ' . $key);
    }

    if (!$driver = self::$databaseInfo[$key][$target]['driver']) {
      throw new DatabaseDriverNotSpecifiedException('Driver not specified for this database connection: ' . $key);
    }

    // We cannot rely on the registry yet, because the registry requires an
    // open database connection.
    $driver_class = 'd7\DatabaseConnection_' . $driver;
    //require_once DRUPAL_ROOT . '/includes/database/' . $driver . '/database.inc';
    $new_connection = new $driver_class(self::$databaseInfo[$key][$target]);
    $new_connection->setTarget($target);
    $new_connection->setKey($key);

    // If we have any active logging objects for this connection key, we need
    // to associate them with the connection we just opened.
    if (!empty(self::$logs[$key])) {
      $new_connection->setLogger(self::$logs[$key]);
    }

    return $new_connection;
  }

  /**
   * Closes a connection to the server specified by the given key and target.
   *
   * @param $target
   *   The database target name.  Defaults to NULL meaning that all target
   *   connections will be closed.
   * @param $key
   *   The database connection key. Defaults to NULL which means the active key.
   */
  public static function closeConnection($target = NULL, $key = NULL) {
    // Gets the active connection by default.
    if (!isset($key)) {
      $key = self::$activeKey;
    }
    // To close a connection, it needs to be set to NULL and removed from the
    // static variable. In all cases, closeConnection() might be called for a
    // connection that was not opened yet, in which case the key is not defined
    // yet and we just ensure that the connection key is undefined.
    if (isset($target)) {
      if (isset(self::$connections[$key][$target])) {
        self::$connections[$key][$target]->destroy();
        self::$connections[$key][$target] = NULL;
      }
      unset(self::$connections[$key][$target]);
    }
    else {
      if (isset(self::$connections[$key])) {
        foreach (self::$connections[$key] as $target => $connection) {
          self::$connections[$key][$target]->destroy();
          self::$connections[$key][$target] = NULL;
        }
      }
      unset(self::$connections[$key]);
    }
  }

  /**
   * Instructs the system to temporarily ignore a given key/target.
   *
   * At times we need to temporarily disable slave queries. To do so, call this
   * method with the database key and the target to disable. That database key
   * will then always fall back to 'default' for that key, even if it's defined.
   *
   * @param $key
   *   The database connection key.
   * @param $target
   *   The target of the specified key to ignore.
   */
  public static function ignoreTarget($key, $target) {
    self::$ignoreTargets[$key][$target] = TRUE;
  }

  /**
   * Load a file for the database that might hold a class.
   *
   * @param $driver
   *   The name of the driver.
   * @param array $files
   *   The name of the files the driver specific class can be.
   */
  public static function loadDriverFile($driver, array $files = []) {
    static $base_path;

    if (empty($base_path)) {
      $base_path = dirname(realpath(__FILE__));
    }

    $driver_base_path = "$base_path/$driver";
    foreach ($files as $file) {
      // Load the base file first so that classes extending base classes will
      // have the base class loaded.
      foreach (["$base_path/$file", "$driver_base_path/$file"] as $filename) {
        // The OS caches file_exists() and PHP caches require_once(), so
        // we'll let both of those take care of performance here.
        if (file_exists($filename)) {
          require_once $filename;
        }
      }
    }
  }
}

/**
 * Exception for when popTransaction() is called with no active transaction.
 */
class DatabaseTransactionNoActiveException extends Exception {

}

/**
 * Exception thrown when a savepoint or transaction name occurs twice.
 */
class DatabaseTransactionNameNonUniqueException extends Exception {

}

/**
 * Exception thrown when a commit() function fails.
 */
class DatabaseTransactionCommitFailedException extends Exception {

}

/**
 * Exception to deny attempts to explicitly manage transactions.
 *
 * This exception will be thrown when the PDO connection commit() is called.
 * Code should never call this method directly.
 */
class DatabaseTransactionExplicitCommitNotAllowedException extends Exception {

}

/**
 * Exception thrown when a rollback() resulted in other active transactions being rolled-back.
 */
class DatabaseTransactionOutOfOrderException extends Exception {

}

/**
 * Exception thrown for merge queries that do not make semantic sense.
 *
 * There are many ways that a merge query could be malformed.  They should all
 * throw this exception and set an appropriately descriptive message.
 */
class InvalidMergeQueryException extends Exception {

}

/**
 * Exception thrown if an insert query specifies a field twice.
 *
 * It is not allowed to specify a field as default and insert field, this
 * exception is thrown if that is the case.
 */
class FieldsOverlapException extends Exception {

}

/**
 * Exception thrown if an insert query doesn't specify insert or default fields.
 */
class NoFieldsException extends Exception {

}

/**
 * Exception thrown if an undefined database connection is requested.
 */
class DatabaseConnectionNotDefinedException extends Exception {

}

/**
 * Exception thrown if no driver is specified for a database connection.
 */
class DatabaseDriverNotSpecifiedException extends Exception {

}


/**
 * A wrapper class for creating and managing database transactions.
 *
 * Not all databases or database configurations support transactions. For
 * example, MySQL MyISAM tables do not. It is also easy to begin a transaction
 * and then forget to commit it, which can lead to connection errors when
 * another transaction is started.
 *
 * This class acts as a wrapper for transactions. To begin a transaction,
 * simply instantiate it. When the object goes out of scope and is destroyed
 * it will automatically commit. It also will check to see if the specified
 * connection supports transactions. If not, it will simply skip any transaction
 * commands, allowing user-space code to proceed normally. The only difference
 * is that rollbacks won't actually do anything.
 *
 * In the vast majority of cases, you should not instantiate this class
 * directly. Instead, call ->startTransaction(), from the appropriate connection
 * object.
 */
class DatabaseTransaction {

  /**
   * The connection object for this transaction.
   *
   * @var DatabaseConnection
   */
  protected $connection;

  /**
   * A boolean value to indicate whether this transaction has been rolled back.
   *
   * @var Boolean
   */
  protected $rolledBack = FALSE;

  /**
   * The name of the transaction.
   *
   * This is used to label the transaction savepoint. It will be overridden to
   * 'drupal_transaction' if there is no transaction depth.
   */
  protected $name;

  public function __construct(DatabaseConnection $connection, $name = NULL) {
    $this->connection = $connection;
    // If there is no transaction depth, then no transaction has started. Name
    // the transaction 'drupal_transaction'.
    if (!$depth = $connection->transactionDepth()) {
      $this->name = 'drupal_transaction';
    }
    // Within transactions, savepoints are used. Each savepoint requires a
    // name. So if no name is present we need to create one.
    elseif (!$name) {
      $this->name = 'savepoint_' . $depth;
    }
    else {
      $this->name = $name;
    }
    $this->connection->pushTransaction($this->name);
  }

  public function __destruct() {
    // If we rolled back then the transaction would have already been popped.
    if (!$this->rolledBack) {
      $this->connection->popTransaction($this->name);
    }
  }

  /**
   * Retrieves the name of the transaction or savepoint.
   */
  public function name() {
    return $this->name;
  }

  /**
   * Rolls back the current transaction.
   *
   * This is just a wrapper method to rollback whatever transaction stack we are
   * currently in, which is managed by the connection object itself. Note that
   * logging (preferable with watchdog_exception()) needs to happen after a
   * transaction has been rolled back or the log messages will be rolled back
   * too.
   *
   * @see DatabaseConnection::rollback()
   * @see watchdog_exception()
   */
  public function rollback() {
    $this->rolledBack = TRUE;
    $this->connection->rollback($this->name);
  }
}

/**
 * Represents a prepared statement.
 *
 * Some methods in that class are purposefully commented out. Due to a change in
 * how PHP defines PDOStatement, we can't define a signature for those methods
 * that will work the same way between versions older than 5.2.6 and later
 * versions.  See http://bugs.php.net/bug.php?id=42452 for more details.
 *
 * Child implementations should either extend PDOStatement:
 *
 * @code
 * class DatabaseStatement_oracle extends PDOStatement implements DatabaseStatementInterface {}
 * @endcode
 * or define their own class. If defining their own class, they will also have
 * to implement either the Iterator or IteratorAggregate interface before
 * DatabaseStatementInterface:
 * @code
 * class DatabaseStatement_oracle implements Iterator, DatabaseStatementInterface {}
 * @endcode
 */
interface DatabaseStatementInterface extends Traversable {

  /**
   * Executes a prepared statement
   *
   * @param $args
   *   An array of values with as many elements as there are bound parameters in
   *   the SQL statement being executed.
   * @param $options
   *   An array of options for this query.
   *
   * @return
   *   TRUE on success, or FALSE on failure.
   */
  public function execute($args = [], $options = []);

  /**
   * Gets the query string of this statement.
   *
   * @return
   *   The query string, in its form with placeholders.
   */
  public function getQueryString();

  /**
   * Returns the number of rows affected by the last SQL statement.
   *
   * @return
   *   The number of rows affected by the last DELETE, INSERT, or UPDATE
   *   statement executed.
   */
  public function rowCount();

  /**
   * Sets the default fetch mode for this statement.
   *
   * See http://php.net/manual/pdo.constants.php for the definition of the
   * constants used.
   *
   * @param $mode
   *   One of the PDO::FETCH_* constants.
   * @param $a1
   *   An option depending of the fetch mode specified by $mode:
   *   - for PDO::FETCH_COLUMN, the index of the column to fetch
   *   - for PDO::FETCH_CLASS, the name of the class to create
   *   - for PDO::FETCH_INTO, the object to add the data to
   * @param $a2
   *   If $mode is PDO::FETCH_CLASS, the optional arguments to pass to the
   *   constructor.
   */
  // public function setFetchMode($mode, $a1 = NULL, $a2 = array());

  /**
   * Fetches the next row from a result set.
   *
   * See http://php.net/manual/pdo.constants.php for the definition of the
   * constants used.
   *
   * @param $mode
   *   One of the PDO::FETCH_* constants.
   *   Default to what was specified by setFetchMode().
   * @param $cursor_orientation
   *   Not implemented in all database drivers, don't use.
   * @param $cursor_offset
   *   Not implemented in all database drivers, don't use.
   *
   * @return
   *   A result, formatted according to $mode.
   */
  // public function fetch($mode = NULL, $cursor_orientation = NULL, $cursor_offset = NULL);

  /**
   * Returns a single field from the next record of a result set.
   *
   * @param $index
   *   The numeric index of the field to return. Defaults to the first field.
   *
   * @return
   *   A single field from the next record, or FALSE if there is no next record.
   */
  public function fetchField($index = 0);

  /**
   * Fetches the next row and returns it as an object.
   *
   * The object will be of the class specified by DatabaseStatementInterface::setFetchMode()
   * or stdClass if not specified.
   */
  // public function fetchObject();

  /**
   * Fetches the next row and returns it as an associative array.
   *
   * This method corresponds to PDOStatement::fetchObject(), but for associative
   * arrays. For some reason PDOStatement does not have a corresponding array
   * helper method, so one is added.
   *
   * @return
   *   An associative array, or FALSE if there is no next row.
   */
  public function fetchAssoc();

  /**
   * Returns an array containing all of the result set rows.
   *
   * @param $mode
   *   One of the PDO::FETCH_* constants.
   * @param $column_index
   *   If $mode is PDO::FETCH_COLUMN, the index of the column to fetch.
   * @param $constructor_arguments
   *   If $mode is PDO::FETCH_CLASS, the arguments to pass to the constructor.
   *
   * @return
   *   An array of results.
   */
  // function fetchAll($mode = NULL, $column_index = NULL, array $constructor_arguments);

  /**
   * Returns an entire single column of a result set as an indexed array.
   *
   * Note that this method will run the result set to the end.
   *
   * @param $index
   *   The index of the column number to fetch.
   *
   * @return
   *   An indexed array, or an empty array if there is no result set.
   */
  public function fetchCol($index = 0);

  /**
   * Returns the entire result set as a single associative array.
   *
   * This method is only useful for two-column result sets. It will return an
   * associative array where the key is one column from the result set and the
   * value is another field. In most cases, the default of the first two columns
   * is appropriate.
   *
   * Note that this method will run the result set to the end.
   *
   * @param $key_index
   *   The numeric index of the field to use as the array key.
   * @param $value_index
   *   The numeric index of the field to use as the array value.
   *
   * @return
   *   An associative array, or an empty array if there is no result set.
   */
  public function fetchAllKeyed($key_index = 0, $value_index = 1);

  /**
   * Returns the result set as an associative array keyed by the given field.
   *
   * If the given key appears multiple times, later records will overwrite
   * earlier ones.
   *
   * @param $key
   *   The name of the field on which to index the array.
   * @param $fetch
   *   The fetchmode to use. If set to PDO::FETCH_ASSOC, PDO::FETCH_NUM, or
   *   PDO::FETCH_BOTH the returned value with be an array of arrays. For any
   *   other value it will be an array of objects. By default, the fetch mode
   *   set for the query will be used.
   *
   * @return
   *   An associative array, or an empty array if there is no result set.
   */
  public function fetchAllAssoc($key, $fetch = NULL);
}

/**
 * Default implementation of DatabaseStatementInterface.
 *
 * PDO allows us to extend the PDOStatement class to provide additional
 * functionality beyond that offered by default. We do need extra
 * functionality. By default, this class is not driver-specific. If a given
 * driver needs to set a custom statement class, it may do so in its
 * constructor.
 *
 * @see http://us.php.net/pdostatement
 */
class DatabaseStatementBase extends PDOStatement implements DatabaseStatementInterface {

  /**
   * Reference to the database connection object for this statement.
   *
   * The name $dbh is inherited from PDOStatement.
   *
   * @var DatabaseConnection
   */
  public $dbh;

  protected function __construct($dbh) {
    $this->dbh = $dbh;
    $this->setFetchMode(PDO::FETCH_OBJ);
  }

  public function execute($args = [], $options = []) {
    if (isset($options['fetch'])) {
      if (is_string($options['fetch'])) {
        // Default to an object. Note: db fields will be added to the object
        // before the constructor is run. If you need to assign fields after
        // the constructor is run, see http://drupal.org/node/315092.
        $this->setFetchMode(PDO::FETCH_CLASS, $options['fetch']);
      }
      else {
        $this->setFetchMode($options['fetch']);
      }
    }

    $logger = $this->dbh->getLogger();
    if (!empty($logger)) {
      $query_start = microtime(TRUE);
    }

    $return = parent::execute($args);

    if (!empty($logger)) {
      $query_end = microtime(TRUE);
      $logger->log($this, $args, $query_end - $query_start);
    }

    return $return;
  }

  public function getQueryString() {
    return $this->queryString;
  }

  public function fetchCol($index = 0) {
    return $this->fetchAll(PDO::FETCH_COLUMN, $index);
  }

  public function fetchAllAssoc($key, $fetch = NULL) {
    $return = [];
    if (isset($fetch)) {
      if (is_string($fetch)) {
        $this->setFetchMode(PDO::FETCH_CLASS, $fetch);
      }
      else {
        $this->setFetchMode($fetch);
      }
    }

    foreach ($this as $record) {
      $record_key = is_object($record) ? $record->$key : $record[$key];
      $return[$record_key] = $record;
    }

    return $return;
  }

  public function fetchAllKeyed($key_index = 0, $value_index = 1) {
    $return = [];
    $this->setFetchMode(PDO::FETCH_NUM);
    foreach ($this as $record) {
      $return[$record[$key_index]] = $record[$value_index];
    }
    return $return;
  }

  public function fetchField($index = 0) {
    // Call PDOStatement::fetchColumn to fetch the field.
    return $this->fetchColumn($index);
  }

  public function fetchAssoc() {
    // Call PDOStatement::fetch to fetch the row.
    return $this->fetch(PDO::FETCH_ASSOC);
  }
}

/**
 * Empty implementation of a database statement.
 *
 * This class satisfies the requirements of being a database statement/result
 * object, but does not actually contain data.  It is useful when developers
 * need to safely return an "empty" result set without connecting to an actual
 * database.  Calling code can then treat it the same as if it were an actual
 * result set that happens to contain no records.
 *
 * @see SearchQuery
 */
class DatabaseStatementEmpty implements Iterator, DatabaseStatementInterface {

  public function execute($args = [], $options = []) {
    return FALSE;
  }

  public function getQueryString() {
    return '';
  }

  public function rowCount() {
    return 0;
  }

  public function setFetchMode($mode, $a1 = NULL, $a2 = []) {
    return;
  }

  public function fetch($mode = NULL, $cursor_orientation = NULL, $cursor_offset = NULL) {
    return NULL;
  }

  public function fetchField($index = 0) {
    return NULL;
  }

  public function fetchObject() {
    return NULL;
  }

  public function fetchAssoc() {
    return NULL;
  }

  function fetchAll($mode = NULL, $column_index = NULL, array $constructor_arguments = []) {
    return [];
  }

  public function fetchCol($index = 0) {
    return [];
  }

  public function fetchAllKeyed($key_index = 0, $value_index = 1) {
    return [];
  }

  public function fetchAllAssoc($key, $fetch = NULL) {
    return [];
  }

  /* Implementations of Iterator. */

  public function current() {
    return NULL;
  }

  public function key() {
    return NULL;
  }

  public function rewind() {
    // Nothing to do: our DatabaseStatement can't be rewound.
  }

  public function next() {
    // Do nothing, since this is an always-empty implementation.
  }

  public function valid() {
    return FALSE;
  }
}

/**
 * The following utility functions are simply convenience wrappers.
 *
 * They should never, ever have any database-specific code in them.
 */

/**
 * Executes an arbitrary query string against the active database.
 *
 * Use this function for SELECT queries if it is just a simple query string.
 * If the caller or other modules need to change the query, use db_select()
 * instead.
 *
 * Do not use this function for INSERT, UPDATE, or DELETE queries. Those should
 * be handled via db_insert(), db_update() and db_delete() respectively.
 *
 * @param $query
 *   The prepared statement query to run. Although it will accept both named and
 *   unnamed placeholders, named placeholders are strongly preferred as they are
 *   more self-documenting.
 * @param $args
 *   An array of values to substitute into the query. If the query uses named
 *   placeholders, this is an associative array in any order. If the query uses
 *   unnamed placeholders (?), this is an indexed array and the order must match
 *   the order of placeholders in the query string.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return DatabaseStatementInterface
 *   A prepared statement object, already executed.
 *
 * @see DatabaseConnection::defaultOptions()
 */
function db_query($query, array $args = [], array $options = []) {
  if (empty($options['target'])) {
    $options['target'] = 'default';
  }

  return Database::getConnection($options['target'])
    ->query($query, $args, $options);
}

/**
 * Executes a query against the active database, restricted to a range.
 *
 * @param $query
 *   The prepared statement query to run. Although it will accept both named and
 *   unnamed placeholders, named placeholders are strongly preferred as they are
 *   more self-documenting.
 * @param $from
 *   The first record from the result set to return.
 * @param $count
 *   The number of records to return from the result set.
 * @param $args
 *   An array of values to substitute into the query. If the query uses named
 *   placeholders, this is an associative array in any order. If the query uses
 *   unnamed placeholders (?), this is an indexed array and the order must match
 *   the order of placeholders in the query string.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return DatabaseStatementInterface
 *   A prepared statement object, already executed.
 *
 * @see DatabaseConnection::defaultOptions()
 */
function db_query_range($query, $from, $count, array $args = [], array $options = []) {
  if (empty($options['target'])) {
    $options['target'] = 'default';
  }

  return Database::getConnection($options['target'])
    ->queryRange($query, $from, $count, $args, $options);
}

/**
 * Executes a SELECT query string and saves the result set to a temporary table.
 *
 * The execution of the query string happens against the active database.
 *
 * @param $query
 *   The prepared SELECT statement query to run. Although it will accept both
 *   named and unnamed placeholders, named placeholders are strongly preferred
 *   as they are more self-documenting.
 * @param $args
 *   An array of values to substitute into the query. If the query uses named
 *   placeholders, this is an associative array in any order. If the query uses
 *   unnamed placeholders (?), this is an indexed array and the order must match
 *   the order of placeholders in the query string.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return
 *   The name of the temporary table.
 *
 * @see DatabaseConnection::defaultOptions()
 */
function db_query_temporary($query, array $args = [], array $options = []) {
  if (empty($options['target'])) {
    $options['target'] = 'default';
  }

  return Database::getConnection($options['target'])
    ->queryTemporary($query, $args, $options);
}

/**
 * Returns a new InsertQuery object for the active database.
 *
 * @param $table
 *   The table into which to insert.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return InsertQuery
 *   A new InsertQuery object for this connection.
 */
function db_insert($table, array $options = []) {
  if (empty($options['target']) || $options['target'] == 'slave') {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])->insert($table, $options);
}

/**
 * Returns a new MergeQuery object for the active database.
 *
 * @param $table
 *   The table into which to merge.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return MergeQuery
 *   A new MergeQuery object for this connection.
 */
function db_merge($table, array $options = []) {
  if (empty($options['target']) || $options['target'] == 'slave') {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])->merge($table, $options);
}

/**
 * Returns a new UpdateQuery object for the active database.
 *
 * @param $table
 *   The table to update.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return UpdateQuery
 *   A new UpdateQuery object for this connection.
 */
function db_update($table, array $options = []) {
  if (empty($options['target']) || $options['target'] == 'slave') {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])->update($table, $options);
}

/**
 * Returns a new DeleteQuery object for the active database.
 *
 * @param $table
 *   The table from which to delete.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return DeleteQuery
 *   A new DeleteQuery object for this connection.
 */
function db_delete($table, array $options = []) {
  if (empty($options['target']) || $options['target'] == 'slave') {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])->delete($table, $options);
}

/**
 * Returns a new TruncateQuery object for the active database.
 *
 * @param $table
 *   The table from which to delete.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return TruncateQuery
 *   A new TruncateQuery object for this connection.
 */
function db_truncate($table, array $options = []) {
  if (empty($options['target']) || $options['target'] == 'slave') {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])
    ->truncate($table, $options);
}

/**
 * Returns a new SelectQuery object for the active database.
 *
 * @param $table
 *   The base table for this query. May be a string or another SelectQuery
 *   object. If a query object is passed, it will be used as a subselect.
 * @param $alias
 *   The alias for the base table of this query.
 * @param $options
 *   An array of options to control how the query operates.
 *
 * @return SelectQuery
 *   A new SelectQuery object for this connection.
 */
function db_select($table, $alias = NULL, array $options = []) {
  if (empty($options['target'])) {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])
    ->select($table, $alias, $options);
}

/**
 * Returns a new transaction object for the active database.
 *
 * @param string $name
 *   Optional name of the transaction.
 * @param array $options
 *   An array of options to control how the transaction operates:
 *   - target: The database target name.
 *
 * @return DatabaseTransaction
 *   A new DatabaseTransaction object for this connection.
 */
function db_transaction($name = NULL, array $options = []) {
  if (empty($options['target'])) {
    $options['target'] = 'default';
  }
  return Database::getConnection($options['target'])->startTransaction($name);
}

/**
 * Sets a new active database.
 *
 * @param $key
 *   The key in the $databases array to set as the default database.
 *
 * @return
 *   The key of the formerly active database.
 */
function db_set_active($key = 'default') {
  return Database::setActiveConnection($key);
}

/**
 * Restricts a dynamic table name to safe characters.
 *
 * Only keeps alphanumeric and underscores.
 *
 * @param $table
 *   The table name to escape.
 *
 * @return
 *   The escaped table name as a string.
 */
function db_escape_table($table) {
  return Database::getConnection()->escapeTable($table);
}

/**
 * Restricts a dynamic column or constraint name to safe characters.
 *
 * Only keeps alphanumeric and underscores.
 *
 * @param $field
 *   The field name to escape.
 *
 * @return
 *   The escaped field name as a string.
 */
function db_escape_field($field) {
  return Database::getConnection()->escapeField($field);
}

/**
 * Escapes characters that work as wildcard characters in a LIKE pattern.
 *
 * The wildcard characters "%" and "_" as well as backslash are prefixed with
 * a backslash. Use this to do a search for a verbatim string without any
 * wildcard behavior.
 *
 * For example, the following does a case-insensitive query for all rows whose
 * name starts with $prefix:
 *
 * @code
 * $result = db_query(
 *   'SELECT * FROM person WHERE name LIKE :pattern',
 *   array(':pattern' => db_like($prefix) . '%')
 * );
 * @endcode
 *
 * Backslash is defined as escape character for LIKE patterns in
 * DatabaseCondition::mapConditionOperator().
 *
 * @param $string
 *   The string to escape.
 *
 * @return
 *   The escaped string.
 */
function db_like($string) {
  return Database::getConnection()->escapeLike($string);
}

/**
 * Retrieves the name of the currently active database driver.
 *
 * @return
 *   The name of the currently active database driver.
 */
function db_driver() {
  return Database::getConnection()->driver();
}

/**
 * Closes the active database connection.
 *
 * @param $options
 *   An array of options to control which connection is closed. Only the target
 *   key has any meaning in this case.
 */
function db_close(array $options = []) {
  if (empty($options['target'])) {
    $options['target'] = NULL;
  }
  Database::closeConnection($options['target']);
}

/**
 * Retrieves a unique id.
 *
 * Use this function if for some reason you can't use a serial field. Using a
 * serial field is preferred, and InsertQuery::execute() returns the value of
 * the last ID inserted.
 *
 * @param $existing_id
 *   After a database import, it might be that the sequences table is behind, so
 *   by passing in a minimum ID, it can be assured that we never issue the same
 *   ID.
 *
 * @return
 *   An integer number larger than any number returned before for this sequence.
 */
function db_next_id($existing_id = 0) {
  return Database::getConnection()->nextId($existing_id);
}

/**
 * Returns a new DatabaseCondition, set to "OR" all conditions together.
 *
 * @return \DatabaseCondition
 */
function db_or() {
  return new DatabaseCondition('OR');
}

/**
 * Returns a new DatabaseCondition, set to "AND" all conditions together.
 *
 * @return DatabaseCondition
 */
function db_and() {
  return new DatabaseCondition('AND');
}

/**
 * Returns a new DatabaseCondition, set to "XOR" all conditions together.
 *
 * @return DatabaseCondition
 */
function db_xor() {
  return new DatabaseCondition('XOR');
}

/**
 * Returns a new DatabaseCondition, set to the specified conjunction.
 *
 * Internal API function call.  The db_and(), db_or(), and db_xor()
 * functions are preferred.
 *
 * @param $conjunction
 *   The conjunction to use for query conditions (AND, OR or XOR).
 *
 * @return DatabaseCondition
 */
function db_condition($conjunction) {
  return new DatabaseCondition($conjunction);
}

/**
 * @} End of "defgroup database".
 */


/**
 * @addtogroup schemaapi
 * @{
 */

/**
 * Creates a new table from a Drupal table definition.
 *
 * @param $name
 *   The name of the table to create.
 * @param $table
 *   A Schema API table definition array.
 */
function db_create_table($name, $table) {
  return Database::getConnection()->schema()->createTable($name, $table);
}

/**
 * Returns an array of field names from an array of key/index column specifiers.
 *
 * This is usually an identity function but if a key/index uses a column prefix
 * specification, this function extracts just the name.
 *
 * @param $fields
 *   An array of key/index column specifiers.
 *
 * @return
 *   An array of field names.
 */
function db_field_names($fields) {
  return Database::getConnection()->schema()->fieldNames($fields);
}

/**
 * Checks if an index exists in the given table.
 *
 * @param $table
 *   The name of the table in drupal (no prefixing).
 * @param $name
 *   The name of the index in drupal (no prefixing).
 *
 * @return
 *   TRUE if the given index exists, otherwise FALSE.
 */
function db_index_exists($table, $name) {
  return Database::getConnection()->schema()->indexExists($table, $name);
}

/**
 * Checks if a table exists.
 *
 * @param $table
 *   The name of the table in drupal (no prefixing).
 *
 * @return
 *   TRUE if the given table exists, otherwise FALSE.
 */
function db_table_exists($table) {
  return Database::getConnection()->schema()->tableExists($table);
}

/**
 * Checks if a column exists in the given table.
 *
 * @param $table
 *   The name of the table in drupal (no prefixing).
 * @param $field
 *   The name of the field.
 *
 * @return
 *   TRUE if the given column exists, otherwise FALSE.
 */
function db_field_exists($table, $field) {
  return Database::getConnection()->schema()->fieldExists($table, $field);
}

/**
 * Finds all tables that are like the specified base table name.
 *
 * @param $table_expression
 *   An SQL expression, for example "simpletest%" (without the quotes).
 *   BEWARE: this is not prefixed, the caller should take care of that.
 *
 * @return
 *   Array, both the keys and the values are the matching tables.
 */
function db_find_tables($table_expression) {
  return Database::getConnection()->schema()->findTables($table_expression);
}

function _db_create_keys_sql($spec) {
  return Database::getConnection()->schema()->createKeysSql($spec);
}

/**
 * Renames a table.
 *
 * @param $table
 *   The current name of the table to be renamed.
 * @param $new_name
 *   The new name for the table.
 */
function db_rename_table($table, $new_name) {
  return Database::getConnection()->schema()->renameTable($table, $new_name);
}

/**
 * Drops a table.
 *
 * @param $table
 *   The table to be dropped.
 */
function db_drop_table($table) {
  return Database::getConnection()->schema()->dropTable($table);
}

/**
 * Adds a new field to a table.
 *
 * @param $table
 *   Name of the table to be altered.
 * @param $field
 *   Name of the field to be added.
 * @param $spec
 *   The field specification array, as taken from a schema definition. The
 *   specification may also contain the key 'initial'; the newly-created field
 *   will be set to the value of the key in all rows. This is most useful for
 *   creating NOT NULL columns with no default value in existing tables.
 * @param $keys_new
 *   (optional) Keys and indexes specification to be created on the table along
 *   with adding the field. The format is the same as a table specification, but
 *   without the 'fields' element. If you are adding a type 'serial' field, you
 *   MUST specify at least one key or index including it in this array. See
 *   db_change_field() for more explanation why.
 *
 * @see db_change_field()
 */
function db_add_field($table, $field, $spec, $keys_new = []) {
  return Database::getConnection()
    ->schema()
    ->addField($table, $field, $spec, $keys_new);
}

/**
 * Drops a field.
 *
 * @param $table
 *   The table to be altered.
 * @param $field
 *   The field to be dropped.
 */
function db_drop_field($table, $field) {
  return Database::getConnection()->schema()->dropField($table, $field);
}

/**
 * Sets the default value for a field.
 *
 * @param $table
 *   The table to be altered.
 * @param $field
 *   The field to be altered.
 * @param $default
 *   Default value to be set. NULL for 'default NULL'.
 */
function db_field_set_default($table, $field, $default) {
  return Database::getConnection()
    ->schema()
    ->fieldSetDefault($table, $field, $default);
}

/**
 * Sets a field to have no default value.
 *
 * @param $table
 *   The table to be altered.
 * @param $field
 *   The field to be altered.
 */
function db_field_set_no_default($table, $field) {
  return Database::getConnection()->schema()->fieldSetNoDefault($table, $field);
}

/**
 * Adds a primary key to a database table.
 *
 * @param $table
 *   Name of the table to be altered.
 * @param $fields
 *   Array of fields for the primary key.
 */
function db_add_primary_key($table, $fields) {
  return Database::getConnection()->schema()->addPrimaryKey($table, $fields);
}

/**
 * Drops the primary key of a database table.
 *
 * @param $table
 *   Name of the table to be altered.
 */
function db_drop_primary_key($table) {
  return Database::getConnection()->schema()->dropPrimaryKey($table);
}

/**
 * Adds a unique key.
 *
 * @param $table
 *   The table to be altered.
 * @param $name
 *   The name of the key.
 * @param $fields
 *   An array of field names.
 */
function db_add_unique_key($table, $name, $fields) {
  return Database::getConnection()
    ->schema()
    ->addUniqueKey($table, $name, $fields);
}

/**
 * Drops a unique key.
 *
 * @param $table
 *   The table to be altered.
 * @param $name
 *   The name of the key.
 */
function db_drop_unique_key($table, $name) {
  return Database::getConnection()->schema()->dropUniqueKey($table, $name);
}

/**
 * Adds an index.
 *
 * @param $table
 *   The table to be altered.
 * @param $name
 *   The name of the index.
 * @param $fields
 *   An array of field names.
 */
function db_add_index($table, $name, $fields) {
  return Database::getConnection()->schema()->addIndex($table, $name, $fields);
}

/**
 * Drops an index.
 *
 * @param $table
 *   The table to be altered.
 * @param $name
 *   The name of the index.
 */
function db_drop_index($table, $name) {
  return Database::getConnection()->schema()->dropIndex($table, $name);
}

/**
 * Changes a field definition.
 *
 * IMPORTANT NOTE: To maintain database portability, you have to explicitly
 * recreate all indices and primary keys that are using the changed field.
 *
 * That means that you have to drop all affected keys and indexes with
 * db_drop_{primary_key,unique_key,index}() before calling db_change_field().
 * To recreate the keys and indices, pass the key definitions as the optional
 * $keys_new argument directly to db_change_field().
 *
 * For example, suppose you have:
 *
 * @code
 * $schema['foo'] = array(
 *   'fields' => array(
 *     'bar' => array('type' => 'int', 'not null' => TRUE)
 *   ),
 *   'primary key' => array('bar')
 * );
 * @endcode
 * and you want to change foo.bar to be type serial, leaving it as the primary
 * key. The correct sequence is:
 * @code
 * db_drop_primary_key('foo');
 * db_change_field('foo', 'bar', 'bar',
 *   array('type' => 'serial', 'not null' => TRUE),
 *   array('primary key' => array('bar')));
 * @endcode
 *
 * The reasons for this are due to the different database engines:
 *
 * On PostgreSQL, changing a field definition involves adding a new field and
 * dropping an old one which causes any indices, primary keys and sequences
 * (from serial-type fields) that use the changed field to be dropped.
 *
 * On MySQL, all type 'serial' fields must be part of at least one key or index
 * as soon as they are created. You cannot use
 * db_add_{primary_key,unique_key,index}() for this purpose because the ALTER
 * TABLE command will fail to add the column without a key or index
 * specification. The solution is to use the optional $keys_new argument to
 * create the key or index at the same time as field.
 *
 * You could use db_add_{primary_key,unique_key,index}() in all cases unless you
 * are converting a field to be type serial. You can use the $keys_new argument
 * in all cases.
 *
 * @param $table
 *   Name of the table.
 * @param $field
 *   Name of the field to change.
 * @param $field_new
 *   New name for the field (set to the same as $field if you don't want to
 *   change the name).
 * @param $spec
 *   The field specification for the new field.
 * @param $keys_new
 *   (optional) Keys and indexes specification to be created on the table along
 *   with changing the field. The format is the same as a table specification
 *   but without the 'fields' element.
 */
function db_change_field($table, $field, $field_new, $spec, $keys_new = []) {
  return Database::getConnection()
    ->schema()
    ->changeField($table, $field, $field_new, $spec, $keys_new);
}

/**
 * @} End of "addtogroup schemaapi".
 */

/**
 * Sets a session variable specifying the lag time for ignoring a slave server.
 */
function db_ignore_slave() {
  $connection_info = Database::getConnectionInfo();
  // Only set ignore_slave_server if there are slave servers being used, which
  // is assumed if there are more than one.
  if (count($connection_info) > 1) {
    // Five minutes is long enough to allow the slave to break and resume
    // interrupted replication without causing problems on the Drupal site from
    // the old data.
    $duration = variable_get('maximum_replication_lag', 300);
    // Set session variable with amount of time to delay before using slave.
    $_SESSION['ignore_slave_server'] = REQUEST_TIME + $duration;
  }
}


/**
 * Interface for a conditional clause in a query.
 */
interface QueryConditionInterface {

  /**
   * Helper function: builds the most common conditional clauses.
   *
   * This method can take a variable number of parameters. If called with two
   * parameters, they are taken as $field and $value with $operator having a
   * value of IN if $value is an array and = otherwise.
   *
   * Do not use this method to test for NULL values. Instead, use
   * QueryConditionInterface::isNull() or QueryConditionInterface::isNotNull().
   *
   * @param $field
   *   The name of the field to check. If you would like to add a more complex
   *   condition involving operators or functions, use where().
   * @param $value
   *   The value to test the field against. In most cases, this is a scalar.
   *   For more complex options, it is an array. The meaning of each element in
   *   the array is dependent on the $operator.
   * @param $operator
   *   The comparison operator, such as =, <, or >=. It also accepts more
   *   complex options such as IN, LIKE, or BETWEEN. Defaults to IN if $value is
   *   an array, and = otherwise.
   *
   * @return QueryConditionInterface
   *   The called object.
   *
   * @see QueryConditionInterface::isNull()
   * @see QueryConditionInterface::isNotNull()
   */
  public function condition($field, $value = NULL, $operator = NULL);

  /**
   * Adds an arbitrary WHERE clause to the query.
   *
   * @param $snippet
   *   A portion of a WHERE clause as a prepared statement. It must use named
   *   placeholders, not ? placeholders.
   * @param $args
   *   An associative array of arguments.
   *
   * @return QueryConditionInterface
   *   The called object.
   */
  public function where($snippet, $args = array());

  /**
   * Sets a condition that the specified field be NULL.
   *
   * @param $field
   *   The name of the field to check.
   *
   * @return QueryConditionInterface
   *   The called object.
   */
  public function isNull($field);

  /**
   * Sets a condition that the specified field be NOT NULL.
   *
   * @param $field
   *   The name of the field to check.
   *
   * @return QueryConditionInterface
   *   The called object.
   */
  public function isNotNull($field);

  /**
   * Sets a condition that the specified subquery returns values.
   *
   * @param SelectQueryInterface $select
   *   The subquery that must contain results.
   *
   * @return QueryConditionInterface
   *   The called object.
   */
  public function exists(SelectQueryInterface $select);

  /**
   * Sets a condition that the specified subquery returns no values.
   *
   * @param SelectQueryInterface $select
   *   The subquery that must not contain results.
   *
   * @return QueryConditionInterface
   *   The called object.
   */
  public function notExists(SelectQueryInterface $select);

  /**
   * Gets a complete list of all conditions in this conditional clause.
   *
   * This method returns by reference. That allows alter hooks to access the
   * data structure directly and manipulate it before it gets compiled.
   *
   * The data structure that is returned is an indexed array of entries, where
   * each entry looks like the following:
   * @code
   * array(
   *   'field' => $field,
   *   'value' => $value,
   *   'operator' => $operator,
   * );
   * @endcode
   *
   * In the special case that $operator is NULL, the $field is taken as a raw
   * SQL snippet (possibly containing a function) and $value is an associative
   * array of placeholders for the snippet.
   *
   * There will also be a single array entry of #conjunction, which is the
   * conjunction that will be applied to the array, such as AND.
   */
  public function &conditions();

  /**
   * Gets a complete list of all values to insert into the prepared statement.
   *
   * @return
   *   An associative array of placeholders and values.
   */
  public function arguments();

  /**
   * Compiles the saved conditions for later retrieval.
   *
   * This method does not return anything, but simply prepares data to be
   * retrieved via __toString() and arguments().
   *
   * @param $connection
   *   The database connection for which to compile the conditionals.
   * @param $queryPlaceholder
   *   The query this condition belongs to. If not given, the current query is
   *   used.
   */
  public function compile(DatabaseConnection $connection, QueryPlaceholderInterface $queryPlaceholder);

  /**
   * Check whether a condition has been previously compiled.
   *
   * @return
   *   TRUE if the condition has been previously compiled.
   */
  public function compiled();
}


/**
 * Interface for a query that can be manipulated via an alter hook.
 */
interface QueryAlterableInterface {

  /**
   * Adds a tag to a query.
   *
   * Tags are strings that identify a query. A query may have any number of
   * tags. Tags are used to mark a query so that alter hooks may decide if they
   * wish to take action. Tags should be all lower-case and contain only
   * letters, numbers, and underscore, and start with a letter. That is, they
   * should follow the same rules as PHP identifiers in general.
   *
   * @param $tag
   *   The tag to add.
   *
   * @return QueryAlterableInterface
   *   The called object.
   */
  public function addTag($tag);

  /**
   * Determines if a given query has a given tag.
   *
   * @param $tag
   *   The tag to check.
   *
   * @return
   *   TRUE if this query has been marked with this tag, FALSE otherwise.
   */
  public function hasTag($tag);

  /**
   * Determines if a given query has all specified tags.
   *
   * @param $tags
   *   A variable number of arguments, one for each tag to check.
   *
   * @return
   *   TRUE if this query has been marked with all specified tags, FALSE
   *   otherwise.
   */
  public function hasAllTags();

  /**
   * Determines if a given query has any specified tag.
   *
   * @param $tags
   *   A variable number of arguments, one for each tag to check.
   *
   * @return
   *   TRUE if this query has been marked with at least one of the specified
   *   tags, FALSE otherwise.
   */
  public function hasAnyTag();

  /**
   * Adds additional metadata to the query.
   *
   * Often, a query may need to provide additional contextual data to alter
   * hooks. Alter hooks may then use that information to decide if and how
   * to take action.
   *
   * @param $key
   *   The unique identifier for this piece of metadata. Must be a string that
   *   follows the same rules as any other PHP identifier.
   * @param $object
   *   The additional data to add to the query. May be any valid PHP variable.
   *
   * @return QueryAlterableInterface
   *   The called object.
   */
  public function addMetaData($key, $object);

  /**
   * Retrieves a given piece of metadata.
   *
   * @param $key
   *   The unique identifier for the piece of metadata to retrieve.
   *
   * @return
   *   The previously attached metadata object, or NULL if one doesn't exist.
   */
  public function getMetaData($key);
}

/**
 * Interface for a query that accepts placeholders.
 */
interface QueryPlaceholderInterface {

  /**
   * Returns a unique identifier for this object.
   */
  public function uniqueIdentifier();

  /**
   * Returns the next placeholder ID for the query.
   *
   * @return
   *   The next available placeholder ID as an integer.
   */
  public function nextPlaceholder();
}

/**
 * Base class for query builders.
 *
 * Note that query builders use PHP's magic __toString() method to compile the
 * query object into a prepared statement.
 */
abstract class Query implements QueryPlaceholderInterface {

  /**
   * The connection object on which to run this query.
   *
   * @var DatabaseConnection
   */
  protected $connection;

  /**
   * The target of the connection object.
   *
   * @var string
   */
  protected $connectionTarget;

  /**
   * The key of the connection object.
   *
   * @var string
   */
  protected $connectionKey;

  /**
   * The query options to pass on to the connection object.
   *
   * @var array
   */
  protected $queryOptions;

  /**
   * A unique identifier for this query object.
   */
  protected $uniqueIdentifier;

  /**
   * The placeholder counter.
   */
  protected $nextPlaceholder = 0;

  /**
   * An array of comments that can be prepended to a query.
   *
   * @var array
   */
  protected $comments = array();

  /**
   * Constructs a Query object.
   *
   * @param DatabaseConnection $connection
   *   Database connection object.
   * @param array $options
   *   Array of query options.
   */
  public function __construct(DatabaseConnection $connection, $options) {
    $this->uniqueIdentifier = uniqid('', TRUE);

    $this->connection = $connection;
    $this->connectionKey = $this->connection->getKey();
    $this->connectionTarget = $this->connection->getTarget();

    $this->queryOptions = $options;
  }

  /**
   * Implements the magic __sleep function to disconnect from the database.
   */
  public function __sleep() {
    $keys = get_object_vars($this);
    unset($keys['connection']);
    return array_keys($keys);
  }

  /**
   * Implements the magic __wakeup function to reconnect to the database.
   */
  public function __wakeup() {
    $this->connection = Database::getConnection($this->connectionTarget, $this->connectionKey);
  }

  /**
   * Implements the magic __clone function.
   */
  public function __clone() {
    $this->uniqueIdentifier = uniqid('', TRUE);
  }

  /**
   * Runs the query against the database.
   */
  abstract protected function execute();

  /**
   * Implements PHP magic __toString method to convert the query to a string.
   *
   * The toString operation is how we compile a query object to a prepared
   * statement.
   *
   * @return
   *   A prepared statement query string for this object.
   */
  abstract public function __toString();

  /**
   * Returns a unique identifier for this object.
   */
  public function uniqueIdentifier() {
    return $this->uniqueIdentifier;
  }

  /**
   * Gets the next placeholder value for this query object.
   *
   * @return int
   *   Next placeholder value.
   */
  public function nextPlaceholder() {
    return $this->nextPlaceholder++;
  }

  /**
   * Adds a comment to the query.
   *
   * By adding a comment to a query, you can more easily find it in your
   * query log or the list of active queries on an SQL server. This allows
   * for easier debugging and allows you to more easily find where a query
   * with a performance problem is being generated.
   *
   * The comment string will be sanitized to remove * / and other characters
   * that may terminate the string early so as to avoid SQL injection attacks.
   *
   * @param $comment
   *   The comment string to be inserted into the query.
   *
   * @return Query
   *   The called object.
   */
  public function comment($comment) {
    $this->comments[] = $comment;
    return $this;
  }

  /**
   * Returns a reference to the comments array for the query.
   *
   * Because this method returns by reference, alter hooks may edit the comments
   * array directly to make their changes. If just adding comments, however, the
   * use of comment() is preferred.
   *
   * Note that this method must be called by reference as well:
   * @code
   * $comments =& $query->getComments();
   * @endcode
   *
   * @return
   *   A reference to the comments array structure.
   */
  public function &getComments() {
    return $this->comments;
  }
}

/**
 * General class for an abstracted INSERT query.
 */
class InsertQuery extends Query {

  /**
   * The table on which to insert.
   *
   * @var string
   */
  protected $table;

  /**
   * An array of fields on which to insert.
   *
   * @var array
   */
  protected $insertFields = array();

  /**
   * An array of fields that should be set to their database-defined defaults.
   *
   * @var array
   */
  protected $defaultFields = array();

  /**
   * A nested array of values to insert.
   *
   * $insertValues is an array of arrays. Each sub-array is either an
   * associative array whose keys are field names and whose values are field
   * values to insert, or a non-associative array of values in the same order
   * as $insertFields.
   *
   * Whether multiple insert sets will be run in a single query or multiple
   * queries is left to individual drivers to implement in whatever manner is
   * most appropriate. The order of values in each sub-array must match the
   * order of fields in $insertFields.
   *
   * @var array
   */
  protected $insertValues = array();

  /**
   * A SelectQuery object to fetch the rows that should be inserted.
   *
   * @var SelectQueryInterface
   */
  protected $fromQuery;

  /**
   * Constructs an InsertQuery object.
   *
   * @param DatabaseConnection $connection
   *   A DatabaseConnection object.
   * @param string $table
   *   Name of the table to associate with this query.
   * @param array $options
   *   Array of database options.
   */
  public function __construct($connection, $table, array $options = array()) {
    if (!isset($options['return'])) {
      $options['return'] = Database::RETURN_INSERT_ID;
    }
    parent::__construct($connection, $options);
    $this->table = $table;
  }

  /**
   * Adds a set of field->value pairs to be inserted.
   *
   * This method may only be called once. Calling it a second time will be
   * ignored. To queue up multiple sets of values to be inserted at once,
   * use the values() method.
   *
   * @param $fields
   *   An array of fields on which to insert. This array may be indexed or
   *   associative. If indexed, the array is taken to be the list of fields.
   *   If associative, the keys of the array are taken to be the fields and
   *   the values are taken to be corresponding values to insert. If a
   *   $values argument is provided, $fields must be indexed.
   * @param $values
   *   An array of fields to insert into the database. The values must be
   *   specified in the same order as the $fields array.
   *
   * @return InsertQuery
   *   The called object.
   */
  public function fields(array $fields, array $values = array()) {
    if (empty($this->insertFields)) {
      if (empty($values)) {
        if (!is_numeric(key($fields))) {
          $values = array_values($fields);
          $fields = array_keys($fields);
        }
      }
      $this->insertFields = $fields;
      if (!empty($values)) {
        $this->insertValues[] = $values;
      }
    }

    return $this;
  }

  /**
   * Adds another set of values to the query to be inserted.
   *
   * If $values is a numeric-keyed array, it will be assumed to be in the same
   * order as the original fields() call. If it is associative, it may be
   * in any order as long as the keys of the array match the names of the
   * fields.
   *
   * @param $values
   *   An array of values to add to the query.
   *
   * @return InsertQuery
   *   The called object.
   */
  public function values(array $values) {
    if (is_numeric(key($values))) {
      $this->insertValues[] = $values;
    }
    else {
      // Reorder the submitted values to match the fields array.
      foreach ($this->insertFields as $key) {
        $insert_values[$key] = $values[$key];
      }
      // For consistency, the values array is always numerically indexed.
      $this->insertValues[] = array_values($insert_values);
    }
    return $this;
  }

  /**
   * Specifies fields for which the database defaults should be used.
   *
   * If you want to force a given field to use the database-defined default,
   * not NULL or undefined, use this method to instruct the database to use
   * default values explicitly. In most cases this will not be necessary
   * unless you are inserting a row that is all default values, as you cannot
   * specify no values in an INSERT query.
   *
   * Specifying a field both in fields() and in useDefaults() is an error
   * and will not execute.
   *
   * @param $fields
   *   An array of values for which to use the default values
   *   specified in the table definition.
   *
   * @return InsertQuery
   *   The called object.
   */
  public function useDefaults(array $fields) {
    $this->defaultFields = $fields;
    return $this;
  }

  /**
   * Sets the fromQuery on this InsertQuery object.
   *
   * @param SelectQueryInterface $query
   *   The query to fetch the rows that should be inserted.
   *
   * @return InsertQuery
   *   The called object.
   */
  public function from(SelectQueryInterface $query) {
    $this->fromQuery = $query;
    return $this;
  }

  /**
   * Executes the insert query.
   *
   * @return
   *   The last insert ID of the query, if one exists. If the query
   *   was given multiple sets of values to insert, the return value is
   *   undefined. If no fields are specified, this method will do nothing and
   *   return NULL. That makes it safe to use in multi-insert loops.
   */
  public function execute() {
    // If validation fails, simply return NULL. Note that validation routines
    // in preExecute() may throw exceptions instead.
    if (!$this->preExecute()) {
      return NULL;
    }

    // If we're selecting from a SelectQuery, finish building the query and
    // pass it back, as any remaining options are irrelevant.
    if (!empty($this->fromQuery)) {
      $sql = (string) $this;
      // The SelectQuery may contain arguments, load and pass them through.
      return $this->connection->query($sql, $this->fromQuery->getArguments(), $this->queryOptions);
    }

    $last_insert_id = 0;

    // Each insert happens in its own query in the degenerate case. However,
    // we wrap it in a transaction so that it is atomic where possible. On many
    // databases, such as SQLite, this is also a notable performance boost.
    $transaction = $this->connection->startTransaction();

    try {
      $sql = (string) $this;
      foreach ($this->insertValues as $insert_values) {
        $last_insert_id = $this->connection->query($sql, $insert_values, $this->queryOptions);
      }
    }
    catch (Exception $e) {
      // One of the INSERTs failed, rollback the whole batch.
      $transaction->rollback();
      // Rethrow the exception for the calling code.
      throw $e;
    }

    // Re-initialize the values array so that we can re-use this query.
    $this->insertValues = array();

    // Transaction commits here where $transaction looses scope.

    return $last_insert_id;
  }

  /**
   * Implements PHP magic __toString method to convert the query to a string.
   *
   * @return string
   *   The prepared statement.
   */
  public function __toString() {
    // Create a sanitized comment string to prepend to the query.
    $comments = $this->connection->makeComment($this->comments);

    // Default fields are always placed first for consistency.
    $insert_fields = array_merge($this->defaultFields, $this->insertFields);

    if (!empty($this->fromQuery)) {
      return $comments . 'INSERT INTO {' . $this->table . '} (' . implode(', ', $insert_fields) . ') ' . $this->fromQuery;
    }

    // For simplicity, we will use the $placeholders array to inject
    // default keywords even though they are not, strictly speaking,
    // placeholders for prepared statements.
    $placeholders = array();
    $placeholders = array_pad($placeholders, count($this->defaultFields), 'default');
    $placeholders = array_pad($placeholders, count($this->insertFields), '?');

    return $comments . 'INSERT INTO {' . $this->table . '} (' . implode(', ', $insert_fields) . ') VALUES (' . implode(', ', $placeholders) . ')';
  }

  /**
   * Preprocesses and validates the query.
   *
   * @return
   *   TRUE if the validation was successful, FALSE if not.
   *
   * @throws FieldsOverlapException
   * @throws NoFieldsException
   */
  public function preExecute() {
    // Confirm that the user did not try to specify an identical
    // field and default field.
    if (array_intersect($this->insertFields, $this->defaultFields)) {
      throw new FieldsOverlapException('You may not specify the same field to have a value and a schema-default value.');
    }

    if (!empty($this->fromQuery)) {
      // We have to assume that the used aliases match the insert fields.
      // Regular fields are added to the query before expressions, maintain the
      // same order for the insert fields.
      // This behavior can be overridden by calling fields() manually as only the
      // first call to fields() does have an effect.
      $this->fields(array_merge(array_keys($this->fromQuery->getFields()), array_keys($this->fromQuery->getExpressions())));
    }
    else {
      // Don't execute query without fields.
      if (count($this->insertFields) + count($this->defaultFields) == 0) {
        throw new NoFieldsException('There are no fields available to insert with.');
      }
    }

    // If no values have been added, silently ignore this query. This can happen
    // if values are added conditionally, so we don't want to throw an
    // exception.
    if (!isset($this->insertValues[0]) && count($this->insertFields) > 0 && empty($this->fromQuery)) {
      return FALSE;
    }
    return TRUE;
  }
}

/**
 * General class for an abstracted DELETE operation.
 */
class DeleteQuery extends Query implements QueryConditionInterface {

  /**
   * The table from which to delete.
   *
   * @var string
   */
  protected $table;

  /**
   * The condition object for this query.
   *
   * Condition handling is handled via composition.
   *
   * @var DatabaseCondition
   */
  protected $condition;

  /**
   * Constructs a DeleteQuery object.
   *
   * @param DatabaseConnection $connection
   *   A DatabaseConnection object.
   * @param string $table
   *   Name of the table to associate with this query.
   * @param array $options
   *   Array of database options.
   */
  public function __construct(DatabaseConnection $connection, $table, array $options = array()) {
    $options['return'] = Database::RETURN_AFFECTED;
    parent::__construct($connection, $options);
    $this->table = $table;

    $this->condition = new DatabaseCondition('AND');
  }

  /**
   * Implements QueryConditionInterface::condition().
   */
  public function condition($field, $value = NULL, $operator = NULL) {
    $this->condition->condition($field, $value, $operator);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNull().
   */
  public function isNull($field) {
    $this->condition->isNull($field);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNotNull().
   */
  public function isNotNull($field) {
    $this->condition->isNotNull($field);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::exists().
   */
  public function exists(SelectQueryInterface $select) {
    $this->condition->exists($select);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::notExists().
   */
  public function notExists(SelectQueryInterface $select) {
    $this->condition->notExists($select);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::conditions().
   */
  public function &conditions() {
    return $this->condition->conditions();
  }

  /**
   * Implements QueryConditionInterface::arguments().
   */
  public function arguments() {
    return $this->condition->arguments();
  }

  /**
   * Implements QueryConditionInterface::where().
   */
  public function where($snippet, $args = array()) {
    $this->condition->where($snippet, $args);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::compile().
   */
  public function compile(DatabaseConnection $connection, QueryPlaceholderInterface $queryPlaceholder) {
    return $this->condition->compile($connection, $queryPlaceholder);
  }

  /**
   * Implements QueryConditionInterface::compiled().
   */
  public function compiled() {
    return $this->condition->compiled();
  }

  /**
   * Executes the DELETE query.
   *
   * @return int
   *   The number of rows affected by the delete query.
   */
  public function execute() {
    $values = array();
    if (count($this->condition)) {
      $this->condition->compile($this->connection, $this);
      $values = $this->condition->arguments();
    }

    return $this->connection->query((string) $this, $values, $this->queryOptions);
  }

  /**
   * Implements PHP magic __toString method to convert the query to a string.
   *
   * @return string
   *   The prepared statement.
   */
  public function __toString() {
    // Create a sanitized comment string to prepend to the query.
    $comments = $this->connection->makeComment($this->comments);

    $query = $comments . 'DELETE FROM {' . $this->connection->escapeTable($this->table) . '} ';

    if (count($this->condition)) {

      $this->condition->compile($this->connection, $this);
      $query .= "\nWHERE " . $this->condition;
    }

    return $query;
  }
}


/**
 * General class for an abstracted TRUNCATE operation.
 */
class TruncateQuery extends Query {

  /**
   * The table to truncate.
   *
   * @var string
   */
  protected $table;

  /**
   * Constructs a TruncateQuery object.
   *
   * @param DatabaseConnection $connection
   *   A DatabaseConnection object.
   * @param string $table
   *   Name of the table to associate with this query.
   * @param array $options
   *   Array of database options.
   */
  public function __construct(DatabaseConnection $connection, $table, array $options = array()) {
    $options['return'] = Database::RETURN_AFFECTED;
    parent::__construct($connection, $options);
    $this->table = $table;
  }

  /**
   * Implements QueryConditionInterface::compile().
   */
  public function compile(DatabaseConnection $connection, QueryPlaceholderInterface $queryPlaceholder) {
    return $this->condition->compile($connection, $queryPlaceholder);
  }

  /**
   * Implements QueryConditionInterface::compiled().
   */
  public function compiled() {
    return $this->condition->compiled();
  }

  /**
   * Executes the TRUNCATE query.
   *
   * @return
   *   Return value is dependent on the database type.
   */
  public function execute() {
    return $this->connection->query((string) $this, array(), $this->queryOptions);
  }

  /**
   * Implements PHP magic __toString method to convert the query to a string.
   *
   * @return string
   *   The prepared statement.
   */
  public function __toString() {
    // Create a sanitized comment string to prepend to the query.
    $comments = $this->connection->makeComment($this->comments);

    // In most cases, TRUNCATE is not a transaction safe statement as it is a
    // DDL statement which results in an implicit COMMIT. When we are in a
    // transaction, fallback to the slower, but transactional, DELETE.
    // PostgreSQL also locks the entire table for a TRUNCATE strongly reducing
    // the concurrency with other transactions.
    if ($this->connection->inTransaction()) {
      return $comments . 'DELETE FROM {' . $this->connection->escapeTable($this->table) . '}';
    }
    else {
      return $comments . 'TRUNCATE {' . $this->connection->escapeTable($this->table) . '} ';
    }
  }
}

/**
 * General class for an abstracted UPDATE operation.
 */
class UpdateQuery extends Query implements QueryConditionInterface {

  /**
   * The table to update.
   *
   * @var string
   */
  protected $table;

  /**
   * An array of fields that will be updated.
   *
   * @var array
   */
  protected $fields = array();

  /**
   * An array of values to update to.
   *
   * @var array
   */
  protected $arguments = array();

  /**
   * The condition object for this query.
   *
   * Condition handling is handled via composition.
   *
   * @var DatabaseCondition
   */
  protected $condition;

  /**
   * Array of fields to update to an expression in case of a duplicate record.
   *
   * This variable is a nested array in the following format:
   * @code
   * <some field> => array(
   *  'condition' => <condition to execute, as a string>,
   *  'arguments' => <array of arguments for condition, or NULL for none>,
   * );
   * @endcode
   *
   * @var array
   */
  protected $expressionFields = array();

  /**
   * Constructs an UpdateQuery object.
   *
   * @param DatabaseConnection $connection
   *   A DatabaseConnection object.
   * @param string $table
   *   Name of the table to associate with this query.
   * @param array $options
   *   Array of database options.
   */
  public function __construct(DatabaseConnection $connection, $table, array $options = array()) {
    $options['return'] = Database::RETURN_AFFECTED;
    parent::__construct($connection, $options);
    $this->table = $table;

    $this->condition = new DatabaseCondition('AND');
  }

  /**
   * Implements QueryConditionInterface::condition().
   */
  public function condition($field, $value = NULL, $operator = NULL) {
    $this->condition->condition($field, $value, $operator);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNull().
   */
  public function isNull($field) {
    $this->condition->isNull($field);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNotNull().
   */
  public function isNotNull($field) {
    $this->condition->isNotNull($field);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::exists().
   */
  public function exists(SelectQueryInterface $select) {
    $this->condition->exists($select);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::notExists().
   */
  public function notExists(SelectQueryInterface $select) {
    $this->condition->notExists($select);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::conditions().
   */
  public function &conditions() {
    return $this->condition->conditions();
  }

  /**
   * Implements QueryConditionInterface::arguments().
   */
  public function arguments() {
    return $this->condition->arguments();
  }

  /**
   * Implements QueryConditionInterface::where().
   */
  public function where($snippet, $args = array()) {
    $this->condition->where($snippet, $args);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::compile().
   */
  public function compile(DatabaseConnection $connection, QueryPlaceholderInterface $queryPlaceholder) {
    return $this->condition->compile($connection, $queryPlaceholder);
  }

  /**
   * Implements QueryConditionInterface::compiled().
   */
  public function compiled() {
    return $this->condition->compiled();
  }

  /**
   * Adds a set of field->value pairs to be updated.
   *
   * @param $fields
   *   An associative array of fields to write into the database. The array keys
   *   are the field names and the values are the values to which to set them.
   *
   * @return UpdateQuery
   *   The called object.
   */
  public function fields(array $fields) {
    $this->fields = $fields;
    return $this;
  }

  /**
   * Specifies fields to be updated as an expression.
   *
   * Expression fields are cases such as counter=counter+1. This method takes
   * precedence over fields().
   *
   * @param $field
   *   The field to set.
   * @param $expression
   *   The field will be set to the value of this expression. This parameter
   *   may include named placeholders.
   * @param $arguments
   *   If specified, this is an array of key/value pairs for named placeholders
   *   corresponding to the expression.
   *
   * @return UpdateQuery
   *   The called object.
   */
  public function expression($field, $expression, array $arguments = NULL) {
    $this->expressionFields[$field] = array(
      'expression' => $expression,
      'arguments' => $arguments,
    );

    return $this;
  }

  /**
   * Executes the UPDATE query.
   *
   * @return
   *   The number of rows affected by the update.
   */
  public function execute() {

    // Expressions take priority over literal fields, so we process those first
    // and remove any literal fields that conflict.
    $fields = $this->fields;
    $update_values = array();
    foreach ($this->expressionFields as $field => $data) {
      if (!empty($data['arguments'])) {
        $update_values += $data['arguments'];
      }
      unset($fields[$field]);
    }

    // Because we filter $fields the same way here and in __toString(), the
    // placeholders will all match up properly.
    $max_placeholder = 0;
    foreach ($fields as $field => $value) {
      $update_values[':db_update_placeholder_' . ($max_placeholder++)] = $value;
    }

    if (count($this->condition)) {
      $this->condition->compile($this->connection, $this);
      $update_values = array_merge($update_values, $this->condition->arguments());
    }

    return $this->connection->query((string) $this, $update_values, $this->queryOptions);
  }

  /**
   * Implements PHP magic __toString method to convert the query to a string.
   *
   * @return string
   *   The prepared statement.
   */
  public function __toString() {
    // Create a sanitized comment string to prepend to the query.
    $comments = $this->connection->makeComment($this->comments);

    // Expressions take priority over literal fields, so we process those first
    // and remove any literal fields that conflict.
    $fields = $this->fields;
    $update_fields = array();
    foreach ($this->expressionFields as $field => $data) {
      $update_fields[] = $field . '=' . $data['expression'];
      unset($fields[$field]);
    }

    $max_placeholder = 0;
    foreach ($fields as $field => $value) {
      $update_fields[] = $field . '=:db_update_placeholder_' . ($max_placeholder++);
    }

    $query = $comments . 'UPDATE {' . $this->connection->escapeTable($this->table) . '} SET ' . implode(', ', $update_fields);

    if (count($this->condition)) {
      $this->condition->compile($this->connection, $this);
      // There is an implicit string cast on $this->condition.
      $query .= "\nWHERE " . $this->condition;
    }

    return $query;
  }

}

/**
 * General class for an abstracted MERGE query operation.
 *
 * An ANSI SQL:2003 compatible database would run the following query:
 *
 * @code
 * MERGE INTO table_name_1 USING table_name_2 ON (condition)
 *   WHEN MATCHED THEN
 *   UPDATE SET column1 = value1 [, column2 = value2 ...]
 *   WHEN NOT MATCHED THEN
 *   INSERT (column1 [, column2 ...]) VALUES (value1 [, value2 ...
 * @endcode
 *
 * Other databases (most notably MySQL, PostgreSQL and SQLite) will emulate
 * this statement by running a SELECT and then INSERT or UPDATE.
 *
 * By default, the two table names are identical and they are passed into the
 * the constructor. table_name_2 can be specified by the
 * MergeQuery::conditionTable() method. It can be either a string or a
 * subquery.
 *
 * The condition is built exactly like SelectQuery or UpdateQuery conditions,
 * the UPDATE query part is built similarly like an UpdateQuery and finally the
 * INSERT query part is built similarly like an InsertQuery. However, both
 * UpdateQuery and InsertQuery has a fields method so
 * MergeQuery::updateFields() and MergeQuery::insertFields() needs to be called
 * instead. MergeQuery::fields() can also be called which calls both of these
 * methods as the common case is to use the same column-value pairs for both
 * INSERT and UPDATE. However, this is not mandatory. Another convenient
 * wrapper is MergeQuery::key() which adds the same column-value pairs to the
 * condition and the INSERT query part.
 *
 * Several methods (key(), fields(), insertFields()) can be called to set a
 * key-value pair for the INSERT query part. Subsequent calls for the same
 * fields override the earlier ones. The same is true for UPDATE and key(),
 * fields() and updateFields().
 */
class MergeQuery extends Query implements QueryConditionInterface {
  /**
   * Returned by execute() if an INSERT query has been executed.
   */
  const STATUS_INSERT = 1;

  /**
   * Returned by execute() if an UPDATE query has been executed.
   */
  const STATUS_UPDATE = 2;

  /**
   * The table to be used for INSERT and UPDATE.
   *
   * @var string
   */
  protected $table;

  /**
   * The table or subquery to be used for the condition.
   */
  protected $conditionTable;

  /**
   * An array of fields on which to insert.
   *
   * @var array
   */
  protected $insertFields = array();

  /**
   * An array of fields which should be set to their database-defined defaults.
   *
   * Used on INSERT.
   *
   * @var array
   */
  protected $defaultFields = array();

  /**
   * An array of values to be inserted.
   *
   * @var string
   */
  protected $insertValues = array();

  /**
   * An array of fields that will be updated.
   *
   * @var array
   */
  protected $updateFields = array();

  /**
   * Array of fields to update to an expression in case of a duplicate record.
   *
   * This variable is a nested array in the following format:
   * @code
   * <some field> => array(
   *  'condition' => <condition to execute, as a string>,
   *  'arguments' => <array of arguments for condition, or NULL for none>,
   * );
   * @endcode
   *
   * @var array
   */
  protected $expressionFields = array();

  /**
   * Flag indicating whether an UPDATE is necessary.
   *
   * @var boolean
   */
  protected $needsUpdate = FALSE;

  /**
   * Constructs a MergeQuery object.
   *
   * @param DatabaseConnection $connection
   *   A DatabaseConnection object.
   * @param string $table
   *   Name of the table to associate with this query.
   * @param array $options
   *   Array of database options.
   */
  public function __construct(DatabaseConnection $connection, $table, array $options = array()) {
    $options['return'] = Database::RETURN_AFFECTED;
    parent::__construct($connection, $options);
    $this->table = $table;
    $this->conditionTable = $table;
    $this->condition = new DatabaseCondition('AND');
  }

  /**
   * Sets the table or subquery to be used for the condition.
   *
   * @param $table
   *   The table name or the subquery to be used. Use a SelectQuery object to
   *   pass in a subquery.
   *
   * @return MergeQuery
   *   The called object.
   */
  protected function conditionTable($table) {
    $this->conditionTable = $table;
    return $this;
  }

  /**
   * Adds a set of field->value pairs to be updated.
   *
   * @param $fields
   *   An associative array of fields to write into the database. The array keys
   *   are the field names and the values are the values to which to set them.
   *
   * @return MergeQuery
   *   The called object.
   */
  public function updateFields(array $fields) {
    $this->updateFields = $fields;
    $this->needsUpdate = TRUE;
    return $this;
  }

  /**
   * Specifies fields to be updated as an expression.
   *
   * Expression fields are cases such as counter = counter + 1. This method
   * takes precedence over MergeQuery::updateFields() and it's wrappers,
   * MergeQuery::key() and MergeQuery::fields().
   *
   * @param $field
   *   The field to set.
   * @param $expression
   *   The field will be set to the value of this expression. This parameter
   *   may include named placeholders.
   * @param $arguments
   *   If specified, this is an array of key/value pairs for named placeholders
   *   corresponding to the expression.
   *
   * @return MergeQuery
   *   The called object.
   */
  public function expression($field, $expression, array $arguments = NULL) {
    $this->expressionFields[$field] = array(
      'expression' => $expression,
      'arguments' => $arguments,
    );
    $this->needsUpdate = TRUE;
    return $this;
  }

  /**
   * Adds a set of field->value pairs to be inserted.
   *
   * @param $fields
   *   An array of fields on which to insert. This array may be indexed or
   *   associative. If indexed, the array is taken to be the list of fields.
   *   If associative, the keys of the array are taken to be the fields and
   *   the values are taken to be corresponding values to insert. If a
   *   $values argument is provided, $fields must be indexed.
   * @param $values
   *   An array of fields to insert into the database. The values must be
   *   specified in the same order as the $fields array.
   *
   * @return MergeQuery
   *   The called object.
   */
  public function insertFields(array $fields, array $values = array()) {
    if ($values) {
      $fields = array_combine($fields, $values);
    }
    $this->insertFields = $fields;
    return $this;
  }

  /**
   * Specifies fields for which the database-defaults should be used.
   *
   * If you want to force a given field to use the database-defined default,
   * not NULL or undefined, use this method to instruct the database to use
   * default values explicitly. In most cases this will not be necessary
   * unless you are inserting a row that is all default values, as you cannot
   * specify no values in an INSERT query.
   *
   * Specifying a field both in fields() and in useDefaults() is an error
   * and will not execute.
   *
   * @param $fields
   *   An array of values for which to use the default values
   *   specified in the table definition.
   *
   * @return MergeQuery
   *   The called object.
   */
  public function useDefaults(array $fields) {
    $this->defaultFields = $fields;
    return $this;
  }

  /**
   * Sets common field-value pairs in the INSERT and UPDATE query parts.
   *
   * This method should only be called once. It may be called either
   * with a single associative array or two indexed arrays. If called
   * with an associative array, the keys are taken to be the fields
   * and the values are taken to be the corresponding values to set.
   * If called with two arrays, the first array is taken as the fields
   * and the second array is taken as the corresponding values.
   *
   * @param $fields
   *   An array of fields to insert, or an associative array of fields and
   *   values. The keys of the array are taken to be the fields and the values
   *   are taken to be corresponding values to insert.
   * @param $values
   *   An array of values to set into the database. The values must be
   *   specified in the same order as the $fields array.
   *
   * @return MergeQuery
   *   The called object.
   */
  public function fields(array $fields, array $values = array()) {
    if ($values) {
      $fields = array_combine($fields, $values);
    }
    foreach ($fields as $key => $value) {
      $this->insertFields[$key] = $value;
      $this->updateFields[$key] = $value;
    }
    $this->needsUpdate = TRUE;
    return $this;
  }

  /**
   * Sets the key field(s) to be used as conditions for this query.
   *
   * This method should only be called once. It may be called either
   * with a single associative array or two indexed arrays. If called
   * with an associative array, the keys are taken to be the fields
   * and the values are taken to be the corresponding values to set.
   * If called with two arrays, the first array is taken as the fields
   * and the second array is taken as the corresponding values.
   *
   * The fields are copied to the condition of the query and the INSERT part.
   * If no other method is called, the UPDATE will become a no-op.
   *
   * @param $fields
   *   An array of fields to set, or an associative array of fields and values.
   * @param $values
   *   An array of values to set into the database. The values must be
   *   specified in the same order as the $fields array.
   *
   * @return MergeQuery
   *   The called object.
   */
  public function key(array $fields, array $values = array()) {
    if ($values) {
      $fields = array_combine($fields, $values);
    }
    foreach ($fields as $key => $value) {
      $this->insertFields[$key] = $value;
      $this->condition($key, $value);
    }
    return $this;
  }

  /**
   * Implements QueryConditionInterface::condition().
   */
  public function condition($field, $value = NULL, $operator = NULL) {
    $this->condition->condition($field, $value, $operator);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNull().
   */
  public function isNull($field) {
    $this->condition->isNull($field);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNotNull().
   */
  public function isNotNull($field) {
    $this->condition->isNotNull($field);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::exists().
   */
  public function exists(SelectQueryInterface $select) {
    $this->condition->exists($select);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::notExists().
   */
  public function notExists(SelectQueryInterface $select) {
    $this->condition->notExists($select);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::conditions().
   */
  public function &conditions() {
    return $this->condition->conditions();
  }

  /**
   * Implements QueryConditionInterface::arguments().
   */
  public function arguments() {
    return $this->condition->arguments();
  }

  /**
   * Implements QueryConditionInterface::where().
   */
  public function where($snippet, $args = array()) {
    $this->condition->where($snippet, $args);
    return $this;
  }

  /**
   * Implements QueryConditionInterface::compile().
   */
  public function compile(DatabaseConnection $connection, QueryPlaceholderInterface $queryPlaceholder) {
    return $this->condition->compile($connection, $queryPlaceholder);
  }

  /**
   * Implements QueryConditionInterface::compiled().
   */
  public function compiled() {
    return $this->condition->compiled();
  }

  /**
   * Implements PHP magic __toString method to convert the query to a string.
   *
   * In the degenerate case, there is no string-able query as this operation
   * is potentially two queries.
   *
   * @return string
   *   The prepared query statement.
   */
  public function __toString() {
  }

  public function execute() {
    if (!count($this->condition)) {
      throw new InvalidMergeQueryException(t('Invalid merge query: no conditions'));
    }
    $select = $this->connection->select($this->conditionTable)
      ->condition($this->condition);
    $select->addExpression('1');
    if (!$select->execute()->fetchField()) {
      try {
        $insert = $this->connection->insert($this->table)->fields($this->insertFields);
        if ($this->defaultFields) {
          $insert->useDefaults($this->defaultFields);
        }
        $insert->execute();
        return self::STATUS_INSERT;
      }
      catch (Exception $e) {
        // The insert query failed, maybe it's because a racing insert query
        // beat us in inserting the same row. Retry the select query, if it
        // returns a row, ignore the error and continue with the update
        // query below.
        if (!$select->execute()->fetchField()) {
          throw $e;
        }
      }
    }
    if ($this->needsUpdate) {
      $update = $this->connection->update($this->table)
        ->fields($this->updateFields)
        ->condition($this->condition);
      if ($this->expressionFields) {
        foreach ($this->expressionFields as $field => $data) {
          $update->expression($field, $data['expression'], $data['arguments']);
        }
      }
      $update->execute();
      return self::STATUS_UPDATE;
    }
  }
}

/**
 * Generic class for a series of conditions in a query.
 */
class DatabaseCondition implements QueryConditionInterface, Countable {

  /**
   * Array of conditions.
   *
   * @var array
   */
  protected $conditions = array();

  /**
   * Array of arguments.
   *
   * @var array
   */
  protected $arguments = array();

  /**
   * Whether the conditions have been changed.
   *
   * TRUE if the condition has been changed since the last compile.
   * FALSE if the condition has been compiled and not changed.
   *
   * @var bool
   */
  protected $changed = TRUE;

  /**
   * The identifier of the query placeholder this condition has been compiled against.
   */
  protected $queryPlaceholderIdentifier;

  /**
   * Constructs a DataBaseCondition object.
   *
   * @param string $conjunction
   *   The operator to use to combine conditions: 'AND' or 'OR'.
   */
  public function __construct($conjunction) {
    $this->conditions['#conjunction'] = $conjunction;
  }

  /**
   * Implements Countable::count().
   *
   * Returns the size of this conditional. The size of the conditional is the
   * size of its conditional array minus one, because one element is the
   * conjunction.
   */
  public function count() {
    return count($this->conditions) - 1;
  }

  /**
   * Implements QueryConditionInterface::condition().
   */
  public function condition($field, $value = NULL, $operator = NULL) {
    if (!isset($operator)) {
      if (is_array($value)) {
        $operator = 'IN';
      }
      elseif (!isset($value)) {
        $operator = 'IS NULL';
      }
      else {
        $operator = '=';
      }
    }
    $this->conditions[] = array(
      'field' => $field,
      'value' => $value,
      'operator' => $operator,
    );

    $this->changed = TRUE;

    return $this;
  }

  /**
   * Implements QueryConditionInterface::where().
   */
  public function where($snippet, $args = array()) {
    $this->conditions[] = array(
      'field' => $snippet,
      'value' => $args,
      'operator' => NULL,
    );
    $this->changed = TRUE;

    return $this;
  }

  /**
   * Implements QueryConditionInterface::isNull().
   */
  public function isNull($field) {
    return $this->condition($field);
  }

  /**
   * Implements QueryConditionInterface::isNotNull().
   */
  public function isNotNull($field) {
    return $this->condition($field, NULL, 'IS NOT NULL');
  }

  /**
   * Implements QueryConditionInterface::exists().
   */
  public function exists(SelectQueryInterface $select) {
    return $this->condition('', $select, 'EXISTS');
  }

  /**
   * Implements QueryConditionInterface::notExists().
   */
  public function notExists(SelectQueryInterface $select) {
    return $this->condition('', $select, 'NOT EXISTS');
  }

  /**
   * Implements QueryConditionInterface::conditions().
   */
  public function &conditions() {
    return $this->conditions;
  }

  /**
   * Implements QueryConditionInterface::arguments().
   */
  public function arguments() {
    // If the caller forgot to call compile() first, refuse to run.
    if ($this->changed) {
      return NULL;
    }
    return $this->arguments;
  }

  /**
   * Implements QueryConditionInterface::compile().
   */
  public function compile(DatabaseConnection $connection, QueryPlaceholderInterface $queryPlaceholder) {
    // Re-compile if this condition changed or if we are compiled against a
    // different query placeholder object.
    if ($this->changed || isset($this->queryPlaceholderIdentifier) && ($this->queryPlaceholderIdentifier != $queryPlaceholder->uniqueIdentifier())) {
      $this->queryPlaceholderIdentifier = $queryPlaceholder->uniqueIdentifier();

      $condition_fragments = array();
      $arguments = array();

      $conditions = $this->conditions;
      $conjunction = $conditions['#conjunction'];
      unset($conditions['#conjunction']);
      foreach ($conditions as $condition) {
        if (empty($condition['operator'])) {
          // This condition is a literal string, so let it through as is.
          $condition_fragments[] = ' (' . $condition['field'] . ') ';
          $arguments += $condition['value'];
        }
        else {
          // It's a structured condition, so parse it out accordingly.
          // Note that $condition['field'] will only be an object for a dependent
          // DatabaseCondition object, not for a dependent subquery.
          if ($condition['field'] instanceof QueryConditionInterface) {
            // Compile the sub-condition recursively and add it to the list.
            $condition['field']->compile($connection, $queryPlaceholder);
            $condition_fragments[] = '(' . (string) $condition['field'] . ')';
            $arguments += $condition['field']->arguments();
          }
          else {
            // For simplicity, we treat all operators as the same data structure.
            // In the typical degenerate case, this won't get changed.
            $operator_defaults = array(
              'prefix' => '',
              'postfix' => '',
              'delimiter' => '',
              'operator' => $condition['operator'],
              'use_value' => TRUE,
            );
            $operator = $connection->mapConditionOperator($condition['operator']);
            if (!isset($operator)) {
              $operator = $this->mapConditionOperator($condition['operator']);
            }
            $operator += $operator_defaults;

            $placeholders = array();
            if ($condition['value'] instanceof SelectQueryInterface) {
              $condition['value']->compile($connection, $queryPlaceholder);
              $placeholders[] = (string) $condition['value'];
              $arguments += $condition['value']->arguments();
              // Subqueries are the actual value of the operator, we don't
              // need to add another below.
              $operator['use_value'] = FALSE;
            }
            // We assume that if there is a delimiter, then the value is an
            // array. If not, it is a scalar. For simplicity, we first convert
            // up to an array so that we can build the placeholders in the same way.
            elseif (!$operator['delimiter']) {
              $condition['value'] = array($condition['value']);
            }
            if ($operator['use_value']) {
              foreach ($condition['value'] as $value) {
                $placeholder = ':db_condition_placeholder_' . $queryPlaceholder->nextPlaceholder();
                $arguments[$placeholder] = $value;
                $placeholders[] = $placeholder;
              }
            }
            $condition_fragments[] = ' (' . $connection->escapeField($condition['field']) . ' ' . $operator['operator'] . ' ' . $operator['prefix'] . implode($operator['delimiter'], $placeholders) . $operator['postfix'] . ') ';
          }
        }
      }

      $this->changed = FALSE;
      $this->stringVersion = implode($conjunction, $condition_fragments);
      $this->arguments = $arguments;
    }
  }

  /**
   * Implements QueryConditionInterface::compiled().
   */
  public function compiled() {
    return !$this->changed;
  }

  /**
   * Implements PHP magic __toString method to convert the conditions to string.
   *
   * @return string
   *   A string version of the conditions.
   */
  public function __toString() {
    // If the caller forgot to call compile() first, refuse to run.
    if ($this->changed) {
      return NULL;
    }
    return $this->stringVersion;
  }

  /**
   * PHP magic __clone() method.
   *
   * Only copies fields that implement QueryConditionInterface. Also sets
   * $this->changed to TRUE.
   */
  function __clone() {
    $this->changed = TRUE;
    foreach ($this->conditions as $key => $condition) {
      if ($key !== '#conjunction') {
        if ($condition['field'] instanceOf QueryConditionInterface) {
          $this->conditions[$key]['field'] = clone($condition['field']);
        }
        if ($condition['value'] instanceOf SelectQueryInterface) {
          $this->conditions[$key]['value'] = clone($condition['value']);
        }
      }
    }
  }

  /**
   * Gets any special processing requirements for the condition operator.
   *
   * Some condition types require special processing, such as IN, because
   * the value data they pass in is not a simple value. This is a simple
   * overridable lookup function.
   *
   * @param $operator
   *   The condition operator, such as "IN", "BETWEEN", etc. Case-sensitive.
   *
   * @return
   *   The extra handling directives for the specified operator, or NULL.
   */
  protected function mapConditionOperator($operator) {
    // $specials does not use drupal_static as its value never changes.
    static $specials = array(
      'BETWEEN' => array('delimiter' => ' AND '),
      'IN' => array('delimiter' => ', ', 'prefix' => ' (', 'postfix' => ')'),
      'NOT IN' => array('delimiter' => ', ', 'prefix' => ' (', 'postfix' => ')'),
      'EXISTS' => array('prefix' => ' (', 'postfix' => ')'),
      'NOT EXISTS' => array('prefix' => ' (', 'postfix' => ')'),
      'IS NULL' => array('use_value' => FALSE),
      'IS NOT NULL' => array('use_value' => FALSE),
      // Use backslash for escaping wildcard characters.
      'LIKE' => array('postfix' => " ESCAPE '\\\\'"),
      'NOT LIKE' => array('postfix' => " ESCAPE '\\\\'"),
      // These ones are here for performance reasons.
      '=' => array(),
      '<' => array(),
      '>' => array(),
      '>=' => array(),
      '<=' => array(),
    );
    if (isset($specials[$operator])) {
      $return = $specials[$operator];
    }
    else {
      // We need to upper case because PHP index matches are case sensitive but
      // do not need the more expensive drupal_strtoupper because SQL statements are ASCII.
      $operator = strtoupper($operator);
      $return = isset($specials[$operator]) ? $specials[$operator] : array();
    }

    $return += array('operator' => $operator);

    return $return;
  }

}

/**
 * @} End of "addtogroup database".
 */
