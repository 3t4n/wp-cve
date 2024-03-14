<?php
/**
 *
 *
 * @author    Timo Reith <timo@ifeelweb.de>
 * @copyright Copyright (c) 2014 ifeelweb.de
 * @version   $Id: DeferredTask.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 * @package
 */
abstract class IfwPsn_Wp_Helper_DeferredTask
{
    /**
     * works as transient token
     * @var string
     */
    protected $_id;

    /**
     * works as transient timeout
     * @var int
     */
    protected $_maxInterval;

    /**
     * WP action on which the task should be executed (optional)
     * @var null|string
     */
    protected $_action;

    /**
     * to prevent double execution on same request/process
     * @var bool
     */
    protected $_done = false;



    /**
     * IfwPsn_Wp_Helper_DeferredTask constructor.
     *
     * @param string $id
     * @param int $maxInterval in seconds
     * @param null|string $action
     */
    public function __construct($id, $maxInterval = 3600, $action = null)
    {
        $this->_id = $id;

        $this->_maxInterval = $maxInterval;

        if ($action !== null) {
            $this->_action = $action;
        }

        if ($this->_action !== null) {
            add_action($this->_action, array($this, 'init'));
        } else {
            $this->init();
        }
    }

    /**
     * Executes the task if no transient data was found
     */
    public function init()
    {
        if (!$this->_done) {

            $result = get_transient($this->_id);

            if (empty($result)) {
                $this->_execute();
                set_transient($this->_id, true, $this->_maxInterval);
            }

            $this->_done = true;
        }
    }

    abstract protected function _execute();
}
